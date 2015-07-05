<?php
namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Org;
use App\Models\Dept;
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

class UserController extends BaseController
{

    /**
     * 用户管理页
     */
    public function index()
    {
        // TODO:只可管理部门用户，比如要扣除角色为管理员的用户
        // 根据参数获取用户信息
        if (Input::has('number')) {
            $data = User::orderBy('number', Input::get('number'));
        } elseif (Input::has('name')) {
            $data = User::orderBy('name', Input::get('name'));
        } else {
            $data = User::orderBy('number', 'desc');
        }
        if (Input::has('key')) {
            $key = Input::get('key');
            $data->where('name', 'like', "%{$key}%")
                ->orWhere('mobile', 'like', "%{$key}%")
                ->orWhere('realname', 'like', "%{$key}%");
        }
        $data = $data->paginate(15);

        // 返回视图
        return v('index')->with(compact('data'));
    }

    /**
     * 新增&编辑用户
     */
    public function edit()
    {
        // TODO:点击个人资料进来时修改id是否看会改到别人的资料，要权限判定
        // 获取第一级机构
        $org = Org::whereParentPath('')->get();

        // 省份
        $province = Province::all();

        // 获取编辑信息
        if (Input::has('id')) {
            $data = User::find(Input::input('id'));
            if ($data->province_id > 0) {
                $city = City::whereProvinceId($data->province_id)->get();
            }
            return v('edit')->with(compact('data', 'org', 'province', 'city'));
        }

        return v('edit', compact('data', 'org', 'province'));
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
            'name' => 'required|unique:users,name,' . Input::get('id') . '|min:3|max:30',
            'password' => 'required_without:id',
            'number' => 'required|unique:users,number,' . Input::get('id'),
            'email' => 'required|email',
            'mobile' => 'required|mobile',
            'realname' => 'required|realname',
            'gender' => 'required|in:' . User::GENDER_FEMALE . ',' . User::GENDER_MALE,
            'path' => 'required',
            'dept_id' => 'required|exists:depts,id',
            'status' => 'required|in:' . User::STATUS_OFF . ',' . User::STATUS_ON,
            'birthday' => 'date_format:Y-m-d'
        ], [
            'province_id.required' => '省份不能为空',
            'province_id.exists' => '省份不存在',
            'city_id.required' => '城市不能为空',
            'city_id.exists' => '城市不存在',
            'name.required' => '用户名称不能为空',
            'name.unique' => '用户名称已经存在',
            'name.min' => '用户名称必须最少要有3个字符',
            'name.max' => '用户名称不能大于60个字符',
            'password.required_without' => '密码不能为空',
            'number.required' => '用户编号不能为空',
            'number.unique' => '用户编号已经存在',
            'email.required' => '邮箱不能为空',
            'email.email' => '邮箱格式错误',
            'mobile.required' => '手机号不能为空',
            'mobile.mobile' => '手机号格式错误',
            'realname.required' => '真实姓名不能为空',
            'realname.realname' => '真实姓名格式错误，仅能为2-4个汉字',
            'gender.required' => '性别不能为空',
            'path.required' => '所属机构不能为空',
            'dept_id.required' => '所属部门不能为空,或请在所选机构下建立部门',
            'dept_id.exists' => '所属部门不存在',
            'status.required' => '状态不能为空',
            'status.in' => '状态仅能为离职和在职',
            'birthday.date_format' => '生日格式错误'
        ]);
        if ($validator->fails()) {
            return Redirect::to(URL::previous())->withMessageError($validator->messages()
                ->all())
                ->withInput();
        }
        // 验证部门ID和机构ID是否对应
        $id = Input::has('id') ? Input::get('id') : 0;
        $org_id = array_filter(Input::get('path'));
        $org_id = empty($org_id) ? 0 : end($org_id);
        if (Dept::find(Input::get('dept_id'))->org_id != $org_id) {
            $org_id = Dept::find(Input::get('dept_id'))->org_id;
        }

        $user = User::findOrNew(Input::get('id'));
        $user->name = trim(Input::get('name'));
        $user->email = Input::get('email');
        $user->mobile = Input::get('mobile');
        $user->number = Input::get('number');
        if (Input::has('password')) {
            $user->password = Input::get('password');
        }
        $user->realname = Input::get('realname', '');
        $user->gender = Input::get('gender');
        $user->birthday = Input::get('birthday', '0000-00-00');
        $user->province_id = Input::get('province_id');
        $user->city_id = Input::get('city_id');
        $user->address = Input::get('address');
        $user->org_id = $org_id;
        $user->dept_id = Input::get('dept_id');
        $user->status = Input::get('status');
        if (Input::has('avatar_path')) {
            $user->avatar_path = Input::get('avatar_path');
        }
        $user->save();

        return Redirect::route("UserIndex")->withMessageSuccess($id > 0 ? '修改成功' : '新增成功');
    }

    /**
     * 删除用户
     */
    public function delete()
    {
        // 验证数据。
        $validator = Validator::make(Input::all(), [
            'id' => 'required|exists:users,id'
        ], [
            'id.required' => '所选用户不能为空',
            'id.exists' => '所选用户不存在'
        ]);
        if ($validator->fails()) {
            return Redirect::route("UserIndex")->withMessageError($validator->messages()
                ->all());
        }

        $user = User::find(Input::get('id'));
        $user->delete();
        return Redirect::to(URL::previous())->withMessageSuccess('删除成功');
    }

    /**
     * 切换状态
     */
    public function postToggleStatus()
    {
        $validator = Validator::make(Input::all(), [
            'id' => 'required|exists:users,id',
            'status' => 'required|in:' . User::STATUS_ON . ',' . User::STATUS_OFF
        ]);

        if ($validator->fails()) {
            return Response::make($validator->messages()->first(), 402);
        }

        $user = User::find(Input::get('id'));
        if (Input::get('status') == User::STATUS_ON) {
            $user->status = User::STATUS_OFF;
        } else {
            $user->status = User::STATUS_ON;
        }
        $user->save();

        return 'success';
    }

    /**
     * 查询指派所巡店商户
     */
    public function assignCust()
    {
        $validator = Validator::make(Input::all(), [
            'user_id' => 'required|exists:users,id,status,' . User::STATUS_ON
        ], [
            'user_id.required' => '所指派门店用户不能为空',
            'user_id.exists' => '用户不存在或已离职'
        ]);
        if ($validator->fails()) {
            return Redirect::route("UserIndex")->withMessageError($validator->messages()
                ->all());
        }
        $user = User::find(Input::get('user_id'));
        // 省份
        $province = Province::all();

        // 获取编辑信息
        $data = Cust::with('user', 'custLevel', 'province', 'city', 'district')->latest();
        if (Input::has('key')) {
            $key = Input::get('key');
            $data->where(function ($q) use($key)
            {
                $q->where('name', 'like', "%{$key}%")
                    ->orWhere('mobile', 'like', "%{$key}%")
                    ->orWhere('number', 'like', "%{$key}%");
            });
        }
        if (Input::has('type')) {
            $data->whereType(Input::get('type'));
        }
        if (Input::has('district_id')) {
            $data->whereDistrictId(Input::get('district_id'));
        } elseif (Input::has('city_id')) {
            $data->whereCityId(Input::get('city_id'));
        } elseif (Input::has('province_id')) {
            $data->whereProvinceId(Input::get('province_id'));
        }
        $data = $data->paginate(15);

        if (is_null(Input::all())) {
            // 如果没有参数查询，获取前20个商户门店
            $data = Cust::with('user', 'custLevel', 'province', 'city', 'district')->latest()
                ->take(20)
                ->paginate(15);
        }

        // 旧的巡查门店
        ! is_null(Cust::whereUserId($user->id)->first()) ? $cust = Cust::whereUserId($user->id)->first() : $cust = null;

        return v('assignCust', compact('user', 'data', 'cust', 'province'));
    }

    /**
     * 指派所巡店商户
     */
    public function saveAssignCust()
    {
        $validator = Validator::make(Input::all(), [
            'cust_id' => 'required|exists:users,id',
            'user_id' => 'required|exists:users,id,status,' . User::STATUS_ON
        ], [
            'cust_id.required' => '商户门店不能为空',
            'cust_id.exists' => '商户门店不存',
            'user_id.required' => '用户不能为空',
            'user_id.exists' => '用户不存在或已离职'
        ]);
        if ($validator->fails()) {
            return Redirect::route("UserAssignCust", [
                'user_id' => Input::get('user_id')
            ])->withMessageError($validator->messages()
                ->all());
        }

        $user = User::find(Input::get('user_id'));
        $cust = Cust::whereUserId($user->id)->first();
        if (! is_null($cust)) {
            if ($cust->id == Input::get('cust_id')) {
                return Redirect::route("UserAssignCust", [
                    'user_id' => Input::get('user_id')
                ])->withMessageError('无需重复指派');
            }
            // 移除旧门店的巡店员
            $cust->user_id = 0;
            $cust->save();
        }

        // 添加新门店的巡店员
        $cust = Cust::find(Input::get('cust_id'));
        $cust->user_id = Input::get('user_id');
        $cust->save();

        return Redirect::route("UserIndex")->withMessageSuccess($user->name . '被成功指派的门店为' . $cust->name);
    }

    /**
     * 上级搜索
     */
    public function searchParentUser()
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
        // 返回数据。
        //$data = $data->paginate(Input::get('limit', 6));
        $data = $data->get();

        return v('parent_users_list')->with(compact('data', 'user_id'));
    }

    /**
     * 指派上级领导
     */
    public function assignParentUser()
    {
        $validator = Validator::make(Input::all(), [
            'parent_user_id' => 'required|exists:users,id,status,' . User::STATUS_ON,
            'user_id' => 'required|exists:users,id,status,' . User::STATUS_ON
        ], [
            'parent_user_id.required' => '上级用户不能为空',
            'parent_user_id.exists' => '上级用户不存在或已离职',
            'user_id.required' => '被指派用户不能为空',
            'user_id.exists' => '被指派用户不存在或已离职'
        ]);
        if ($validator->fails()) {
            return Response::make($validator->messages()->first(), 402);
        }

        $user = User::find(Input::get('user_id'));
        $user->parent_user_id = Input::get('parent_user_id');
        $user->save();

        return $user;
    }

    /**
     * 保存头像
     */
    public function avatarSave()
    {
        $validator = Validator::make(Input::all(), [
            'hash' => 'required|exists:storages,hash',
            'user_id' => 'required|exists:users,id,status,' . User::STATUS_ON
        ], [
            'hash.required' => '图片不能为空',
            'hash.exists' => '图片上传不成功',
            'user_id.required' => '用户不能为空',
            'user_id.exists' => '用户不存在或已离职'
        ]);
        if ($validator->fails()) {
            return Response::make($validator->messages()->first(), 402);
        }

        $storage = Storage::find(Input::get('hash'));
        $user = User::find(Input::get('user_id'));
        $user->avatar_path = $storage->path;
        $user->save();

        return $user;
    }

    /**
     * 异步获取上级领导
     */
    public function parentUser()
    {
        $validator = Validator::make(Input::all(), [
            'user_id' => 'required|exists:users,id,status,' . User::STATUS_ON
        ], [
            'user_id.required' => '用户不能为空',
            'user_id.exists' => '用户不存在或已离职'
        ]);
        if ($validator->fails()) {
            return Response::make($validator->messages()->first(), 402);
        }

        $user = User::find(Input::get('user_id'));
        if (! is_null($user->leader)) {
            return $user->leader;
        }
        return null;
    }
}