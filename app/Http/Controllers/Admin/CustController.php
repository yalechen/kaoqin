<?php
namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Cust;
use App\Models\Province;
use Validator;
use Input;
use Auth;
use Redirect;
use Response;
use URL;
use App\Models\City;
use App\Models\Storage;
use App\Models\CustLevel;
use App\Models\District;

class CustController extends BaseController
{

    /**
     * 商户管理页
     */
    public function index()
    {
        // 所有等级
        $level = CustLevel::all();

        // 根据参数获取用户信息
        $data = Cust::with('custLevel', 'user')->orderBy('number', 'desc');
        if (Input::has('type')) {
            $data->whereType(Input::get('type'));
        }
        if (Input::has('key')) {
            $key = Input::get('key');
            $data->where(function ($q) use($key)
            {
                $q->where('name', 'like', "%{$key}%")
                    ->orWhere('number', 'like', "%{$key}%")
                    ->orWhere('mobile', 'like', "%{$key}%")
                    ->orWhere('contacts', 'like', "%{$key}%");
            });
        }
        if (Input::has('level_id')) {
            $data->whereCustLevelId(Input::get('level_id'));
        }

        $data = $data->paginate(15);

        // 返回视图
        return v('index')->with(compact('data', 'level'));
    }

    /**
     * 新增&编辑商户
     */
    public function edit()
    {
        // 所有等级
        $level = CustLevel::all();

        // 省份
        $province = Province::all();

        // 获取编辑信息
        if (Input::has('id')) {
            $data = Cust::find(Input::input('id'));
            if ($data->province_id > 0) {
                $city = City::whereProvinceId($data->province_id)->get();
            }
            if ($data->city_id > 0) {
                $district = District::whereCityId($data->city_id)->get();
            }
        }

        return v('edit', compact('data', 'province', 'city', 'district', 'level'));
    }

    /**
     * 保存
     */
    public function save()
    {
        // 验证输入。
        $validator = Validator::make(Input::all(), [
            'province_id' => 'required|exists:province,id',
            'city_id' => 'required|exists:city,id',
            'district_id' => 'exists:district,id',
            'name' => 'required|unique:custs,name,' . Input::get('id') . '|min:2|max:20',
            'number' => 'required|unique:users,number,' . Input::get('id'),
            'contacts' => 'required|realname',
            'mobile' => 'required|mobile',
            'logo_path' => 'required',
            'address' => 'required',
            'type' => 'required|in:' . Cust::TYPE_GROUP . ',' . Cust::TYPE_STORE,
            'longitude' => 'required',
            'latitude' => 'required'
        ], [
            'province_id.required' => '省份不能为空',
            'province_id.exists' => '省份不存在',
            'city_id.required' => '城市不能为空',
            'city_id.exists' => '城市不存在',
            'district_id.exists' => '县区不存在',
            'name.required' => '商户名称不能为空',
            'name.unique' => '商户名称已经存在',
            'name.min' => '商户名称必须最少要有2个字符',
            'name.max' => '商户名称不能大于20个字符',
            'number.required' => '商户编号不能为空',
            'number.unique' => '商户编号已经存在',
            'contacts.required' => '联系人不能为空',
            'contacts.realname' => '联系人必须为2-4个汉字',
            'mobile.required' => '手机号不能为空',
            'mobile.mobile' => '手机号格式错误',
            'logo_path.required' => 'Logo图片不能为空',
            'address.required' => '具体地址不能为空',
            'type.required' => '类型不能为空',
            'type.in' => '类型仅能为门店和集团',
            'longitude.required' => '经度不能为空',
            'latitude.required' => '纬度不能为空'
        ]);
        if ($validator->fails()) {
            return Redirect::to(URL::previous())->withMessageError($validator->messages()
                ->all())
                ->withInput();
        }
        // 获取Logo图片信息
        $id = Input::has('id') ? Input::get('id') : 0;
        $cust = Cust::findOrNew(Input::get('id'));
        $cust->name = trim(Input::get('name'));
        $cust->contacts = trim(Input::get('contacts'));
        $cust->mobile = Input::get('mobile');
        $cust->number = Input::get('number');
        $cust->logo_path = Input::get('logo_path');
        $cust->address = Input::get('address');
        $cust->lng = Input::get('longitude');
        $cust->lat = Input::get('latitude');
        $cust->province_id = Input::get('province_id');
        $cust->city_id = Input::get('city_id');
        $cust->district_id = Input::get('district_id');
        $cust->address = Input::get('address');
        if (Input::has('remark')) {
            $cust->remark = trim(Input::get('remark'));
        }
        $cust->type = Input::get('type');
        $cust->save();

        return Redirect::route("CustIndex")->withMessageSuccess($id > 0 ? '修改成功' : '新增成功');
    }

    /**
     * 删除商户
     */
    public function delete()
    {
        // 验证数据。
        $validator = Validator::make(Input::all(), [
            'id' => 'required|exists:custs,id'
        ], [
            'id.required' => '所选商户不能为空',
            'id.exists' => '所选商户不存在'
        ]);
        if ($validator->fails()) {
            return Redirect::route("CustIndex")->withMessageError($validator->messages()
                ->all());
        }

        $cust = Cust::find(Input::get('id'));
        $cust->delete();
        return Redirect::to(URL::previous())->withMessageSuccess('删除成功');
    }

    /**
     * 巡店者搜索
     */
    public function searchCustUser()
    {
        $validator = Validator::make(Input::all(), [
            'user_id' => 'required|exists:users,id,status,' . User::STATUS_ON
        ], [
            'user_id.required' => '用户不能为空',
            'user_id.exists' => '用户不存在或已离职'
        ]);
        if ($validator->fails()) {
            return v('parent_users_list')->withMessageError($validator->messages()
                ->first());
        }

        // 取得数据模型。
        $user_id = Input::get('user_id');
        $data = User::latest('id')->where('status', User::STATUS_ON);

        // 处理筛选条件。
        if (Input::has('modal_key')) {
            $key = Input::get('modal_key');
            $data->where('name', 'like', "%{$key}%")
                ->orWhere('mobile', 'like', "%{$key}%")
                ->orWhere('realname', 'like', "%{$key}%");
        }
        // 返回单页数据。
        $data = $data->paginate(Input::get('limit', 6));

        return v('cust_users_list')->with(compact('data', 'user_id'));
    }

    /**
     * 指派巡店者
     */
    public function assignCustUser()
    {
        $validator = Validator::make(Input::all(), [
            'user_id' => 'required|exists:users,id,status,' . User::STATUS_ON,
            'cust_id' => 'required|exists:custs,id'
        ], [
            'user_id.required' => '用户不能为空',
            'user_id.exists' => '用户不存在或已离职',
            'cust_id.required' => '商户不能为空',
            'cust_id.exists' => '商户户不存在'
        ]);
        if ($validator->fails()) {
            return Response::make($validator->messages()->first(), 402);
        }

        $cust = Cust::find(Input::get('cust_id'));
        $cust->user_id = Input::get('user_id');
        $cust->save();

        return $cust;
    }
}