<?php
namespace App\Http\Controllers\Admin;

use App\Models\Org;
use App\Models\Dept;
use App\Models\Province;
use Validator;
use Input;
use Auth;
use Redirect;
use Response;
use URL;
use App\Models\City;

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

        // 省份
        $province = Province::all();

        // 获取编辑信息
        if (Input::has('id')) {
            $data = Org::find(Input::input('id'));
            $parent_node = $data->parentNode()->first();
            if ($data->province_id > 0) {
                $city = City::whereProvinceId($data->province_id)->get();
            }
            return v('edit')->with(compact('data', 'parent_node', 'province', 'city', 'org'));
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
            'name' => 'required|unique:orgs,name,' . Input::get('id') . '|min:3|max:60',
            'number' => 'required|unique:orgs,number,' . Input::get('id'),
            'sort' => 'required|integer|min:0',
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
            'name.min' => '机构名称必须最少要有3个字符',
            'name.max' => '机构名称不能大于60个字符',
            'number.required' => '机构编号不能为空',
            'number.unique' => '机构编号已经存在',
            'sort.required' => '排序值不能为空',
            'sort.integer' => '排序值只能是大于0的数字',
            'sort.min' => '排序值只能是大于0的数字',
            'longitude.required' => '经度不能为空',
            'latitude.required' => '纬度不能为空',
            'address.required' => '详细地址不能为空',
            'area.required' => '所在区域不能为空',
            'path.required' => '父级机构不能为空！'
        ]);
        if ($validator->fails()) {
            return Redirect::to(URL::previous())->withMessageError($validator->messages()
                ->all())
                ->withInput();
        }

        $id = Input::has('id') ? Input::get('id') : 0;
        $parent_id = array_filter(Input::get('path'));
        $org = Org::findOrNew(Input::get('id'));

        // 修改时判断
        if ($id > 0 && ! empty($parent_id)) {
            // 上级机构不能为自己，及其子孙机构所在机构
            $parent_info = Org::find(end($parent_id));

            $path_node = array_filter(explode(':', $parent_info->path));
            if (in_array($id, $path_node)) {
                return Redirect::route("OrgEdit", $id)->withInput()->withMessageError('修改失败，新的机构不能是其本身或其子孙机构');
            }
        }

        // 上级为全部机构，即新增的为第一级机构
        $parent_id = empty($parent_id) ? 0 : end($parent_id);

        $org->name = trim(Input::get('name'));
        $org->number = Input::get('number');
        $org->address = Input::get('address');
        $org->area = Input::get('area');
        $org->sort = Input::get('sort', '100');
        $org->province_id = Input::get('province_id');
        $org->city_id = Input::get('city_id');
        $org->lng = Input::get('longitude');
        $org->lat = Input::get('latitude');
        $org->parent_id = $parent_id;
        $org->save();

        return Redirect::route("OrgIndex")->withMessageSuccess($id > 0 ? '修改成功' : '新增成功');
    }

    /**
     * 删除机构
     */
    public function delete()
    {
        // 验证数据。
        $validator = Validator::make(Input::all(), [
            'id' => 'required|exists:orgs,id'
        ], [
            'id.required' => '所选机构不能为空',
            'id.exists' => '所选机构不存在'
        ]);
        if ($validator->fails()) {
            return Redirect::route("OrgIndex")->withMessageError($validator->messages()
                ->all());
        }

        // 判断此机构是否有下级机构，有则不能删除
        $child_ids = Org::find(Input::get('id'))->childNodes()
            ->lists('id')
            ->all();
        if (! empty($child_ids)) {
            return Redirect::to(URL::previous())->withMessageError('此机构有下级机构，不能删除！');
        }

        array_push($child_ids, Input::get('id'));
        $temp = Dept::whereIn('org_id', $child_ids)->first();
        if (is_null($temp)) {
            $org = Org::find(Input::get('id'));
            $org->delete();
            return Redirect::to(URL::previous())->withMessageSuccess('删除成功');
        }

        return Redirect::to(URL::previous())->withMessageError('此机构底下有部门，需先删除机构旗下的部门');
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
                $cid = Org::find($id)->childNode()
                    ->lists('id')
                    ->all();
                $ids = array_merge($ids, $cid);
            }
            $org = Org::findMany($ids);
        } elseif ($parent_id > 0) {
            $org = Org::find(Input::get('parent_id'))->childNode()->get();
        }
        return Response::json($org);
    }

    /**
     * 获取某机构下级所有部门
     */
    public function subDepts()
    {
        $parent_id = Input::get('parent_id');
        $ids = [];
        if (is_array($parent_id)) {
            foreach ($parent_id as $id) {
                $cid = Org::find($id)->childNode()
                    ->lists('id')
                    ->all();
                $ids = array_merge($ids, $cid);
            }
            $ids = array_merge($ids, $parent_id);
        } elseif ($parent_id > 0) {
            $ids = Org::find(Input::get('parent_id'))->childNodes()
                ->lists('id')
                ->all();
            $ids = array_merge($ids, [
                0 => intval($parent_id)
            ]);
        }
        // 获取该机构及所有子机构下的部门
        $dept_ids = [];
        foreach ($ids as $org_id) {
            $did = Org::find($org_id)->depts()
                ->lists('id')
                ->all();
            $dept_ids = array_merge($dept_ids, $did);
        }
        return Response::json(Dept::findMany($dept_ids));
    }
}