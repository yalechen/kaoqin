<?php
namespace App\Http\Controllers\Admin;

use App\Models\Org;
use App\Models\Province;
use Validator;
use Input;
use Auth;
use Redirect;
use Response;

class OrgController extends BaseController
{

    /**
     * 机构管理页
     */
    public function index()
    {
        // 获取第一级机构
        $org = Org::whereParentPath('')->get();

        // 根据参数获取机构信息
        $data = Org::orderBy('sort', 'desc');
        if (Input::has('key')) {
            $key = Input::get('key');
            $data->where('name', 'like', "%{$key}%");
        }
        if (Input::has('org_id')) {
            $word = Input::get('org_id');
            if ($word) {
                $data->where('parent_path', 'like', "%:{$word}:%")->orWhere('id', $word);
            }
        }
        $data = $data->paginate(15);

        // 返回视图
        return v('index')->with(compact('data', 'org'));
    }

    /**
     * 新增&编辑机构
     */
    public function edit()
    {
        // 获取第一级机构
        $org = Org::whereParentPath('')->get();

        // 获取编辑信息
        if (Input::has('id')) {
            $data = Org::find(Input::input('id'));
            $parent_node = $data->parentNode()->first();
            return v('edit')->with(compact('data', 'parent_node'));
        }

        // 省份
        $province = Province::all();

        return v('edit', compact('data', 'org', 'province'));
    }

    /**
     * 获取下级机构
     */
    public function subOrgs()
    {
        $org = array();
        $parent_id = Input::get('parent_id');
        if (is_array($parent_id)) {
            $ids = [];
            foreach ($parent_id as $id) {
                $cid = Org::find($id)->childNode()->lists('id');
                $ids = array_merge($ids, $cid);
            }
            $org = Org::findMany($ids);
        } elseif ($parent_id > 0) {
            $org = Org::find(Input::get('parent_id'))->childNode()->get();
        }
        return Response::json($org);
    }

    /**
     * 保存
     */
    /**
     * 保存企业信息
     */
    public function save()
    {
        // 验证输入。
        $validator = Validator::make(Input::all(), [
            'province_id' => 'required|exists:province,id',
            'city_id' => 'required|exists:city,id',
            'name' => 'required',
            'number' => 'required',
            'sort' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'address' => 'required',
            'area' => 'required'
        ]
        , [
            'province_id.required' => '省份不能为空',
            'province_id.exists' => '省份不存在',
            'city_id.required' => '城市不能为空',
            'city_id.exists' => '城市不存在',
            'name.required' => '企业名称不能为空',
            'legal.required' => '法人代表不能为空',
            'contacts.required' => '联系人姓名不能为空',
            'phone.required' => '联系电话不能为空',
            'address.required' => '详细地址不能为空',
            'description.required' => '企业简介不能为空'
        ]);
        if ($validator->fails()) {
            return $validator->messages()->first();
        }

        $enterprise = Enterprise::find($this->enterprise_id);

        $enterprise->name = $inputs['name'];
        $enterprise->brand_name = $inputs['brand_name'];
        $enterprise->legal = $inputs['legal'];
        $enterprise->contacts = $inputs['contacts'];
        $enterprise->phone = $inputs['phone'];
        $enterprise->address = $inputs['address'];
        $enterprise->description = $inputs['description'];
        $enterprise->logo_id = $inputs['logo_id'];
        $enterprise->weibo = $inputs['weibo'];
        $enterprise->web_url = $inputs['web_url'];
        $enterprise->weixin = $inputs['weixin'];
        $enterprise->weixin_picture_id = $inputs['weixin_picture_id'];
        $enterprise->license = Input::get('license', '');
        $enterprise->trademark = Input::get('trademark', '');
        $enterprise->scope = Input::get('scope', '');
        if (Input::has('build_date')) {
            $enterprise->build_date = Input::get('build_date');
        }
        $enterprise->province_id = $inputs['province_id'];
        $enterprise->city_id = $inputs['city_id'];
        $enterprise->district_id = $inputs['district_id'];
        $enterprise->longitude = $inputs['longitude'];
        $enterprise->latitude = $inputs['latitude'];
        $enterprise->save();

        return $enterprise;
    }
}