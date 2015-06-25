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
            'name' => 'required|unique:orgs,name,id,' . Input::get('id'),
            'number' => 'required|unique:orgs,number,id,' . Input::get('id'),
            'sort' => 'required|integer',
            'longitude' => 'required',
            'latitude' => 'required',
            'address' => 'required',
            'area' => 'required',
            'path' => 'required'
        ], [
            'province_id.required' => '省份不能为空',
            'province_id.exists' => '省份不存在',
            'city_id.required' => '城市不能为空',
            'city_id.exists' => '城市不存在',
            'name.required' => '机构名称不能为空',
            'name.unique' => '机构名称已经存在',
            'number.required' => '机构编号不能为空',
            'number.unique' => '机构编号已经存在',
            'sort.required' => '排序值不能为空',
            'sort.integer' => '排序值只能是大于0的数字',
            'longitude.required' => '经度不能为空',
            'latitude.required' => '纬度不能为空',
            'address.required' => '详细地址不能为空',
            'area.required' => '所在区域不能为空',
            'path.required' => '父级机构不能为空！'
        ]);
        if ($validator->fails()) {
            return Redirect::route('OrgEdit')->withError($validator->messages()
                ->toArray())
                ->withInput();
        }

        $id = Input::has('id') ? Input::get('id') : 0;
        $parent_id = array_filter(Input::get('path'));
        $org = Org::findOrNew(Input::get('id'));

        // 修改时判断
        if ($id > 0 && ! empty($parent_id)) {
            // 上级分类不能为自己，及其子孙分类所在分类
            $parent_info = GoodsCategory::find(end($parent_id));

            $path_node = array_filter(explode(':', $parent_info->path));
            if (in_array($id, $path_node)) {
                return Redirect::route("OrgEdit", $id)->withInput()->withMessageError('修改失败，新的父级分类不能是其本类或其子孙分类');
            }
        }

        // 上级为全部分类，即新增的为第一级分类
        $parent_id = empty($parent_id) ? 0 : end($parent_id);

        $org->name = trim(Input::get('name'));
        $org->number = Input::get('number');
        $org->address = Input::get('address');
        $org->area = Input::get('area');
        $org->sort = Input::get('scope', '100');
        $org->province_id = Input::get('province_id');
        $org->city_id = Input::get('city_id');
        //$org->district_id = Input::get('district_id');
        $org->lng = Input::get('longitude');
        $org->lat = Input::get('latitude');
        $org->parent_id = $parent_id;
        $org->save();

        return Redirect::route("OrgIndex")->withMessageSuccess('保存成功');
    }
}