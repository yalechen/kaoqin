<?php
namespace App\Http\Controllers\Admin;

use App\Models\Dept;
use App\Models\Province;
use Validator;
use Input;
use Auth;
use Redirect;
use Response;
use URL;
use App\Models\City;
use App\Models\Org;

class DeptController extends BaseController
{

    /**
     * 部门管理页
     */
    public function index()
    {
        // 获取第一级机构
        $org = Org::whereParentPath('')->get();

        if (Input::has('sort')) {
            $data = Dept::orderBy('sort', Input::get('sort'));
        } elseif (Input::has('id')) {
            $data = Dept::orderBy('id', Input::get('id'));
        } else {
            $data = Dept::latest();
        }
        // 根据参数获取部门信息
        if (Input::has('key')) {
            $key = Input::get('key');
            $data->where('name', 'like', "%{$key}%");
        }
        if (Input::has('org_id')) {
            $ids = Org::find(Input::get('org_id'))->childNodes(true)
                ->lists('id')
                ->all();
            if ($ids) {
                $data->whereIn('org_id', $ids);
            }
        }
        $data = $data->paginate(15);

        // 返回视图
        return v('index')->with(compact('data', 'org'));
    }

    /**
     * 新增&编辑
     */
    public function edit()
    {
        // 获取第一级机构
        $org = Org::whereParentPath('')->get();

        // 获取编辑信息
        if (Input::has('id')) {
            $data = Dept::find(Input::input('id'));
        }

        return v('edit', compact('data', 'org'));
    }

    /**
     * 保存
     */
    public function save()
    {
        // 验证输入。
        $validator = Validator::make(Input::all(), [
            'name' => 'required|unique:orgs,name,org_id,' . Input::get('org_id') . '|min:3|max:60',
            'path' => 'required',
            'sort' => 'required|integer|min:0'
        ], [
            'name.required' => '部门名称不能为空',
            'name.unique' => '部门名称已经存在',
            'name.min' => '部门名称必须最少要有3个字符',
            'name.max' => '部门名称不能大于60个字符',
            'path.required' => '所属机构不能为空',
            'sort.required' => '排序值不能为空',
            'sort.integer' => '排序值只能是大于0的数字',
            'sort.min' => '排序值只能是大于0的数字'
        ]);
        if ($validator->fails()) {
            return Redirect::to(URL::previous())->withMessageError($validator->messages()
                ->all())
                ->withInput();
        }

        // 获取所属机构
        $parent_id = array_filter(Input::get('path'));
        $parent_id = empty($parent_id) ? 0 : end($parent_id);

        $dept = Dept::findOrNew(Input::get('id'));
        $dept->name = trim(Input::get('name'));
        $dept->org()->associate(Org::find($parent_id));
        $dept->sort = Input::get('sort');
        $dept->remark = Input::get('remark');
        Input::has('status') ? $dept->status = Dept::STATUS_ON : $dept->status = Dept::STATUS_OFF;
        $dept->save();

        return Redirect::route("DeptIndex")->withMessageSuccess(Input::get('id', 0) > 0 ? '修改成功' : '新增成功');
    }

    /**
     * 删除部门
     */
    public function delete()
    {
        // 验证数据。
        $validator = Validator::make(Input::all(), [
            'id' => 'required|exists:depts,id'
        ], [
            'id.required' => '所选部门不能为空',
            'id.exists' => '所选部门不存在'
        ]);
        if ($validator->fails()) {
            return Redirect::route("DeptIndex")->withMessageError($validator->messages()
                ->all());
        }

        $dept = Dept::find(Input::get('id'));
        $dept->delete();
        return Redirect::to(URL::previous())->withMessageSuccess('删除成功');
    }

    /**
     * 切换状态
     */
    public function postToggleStatus()
    {
        $validator = Validator::make(Input::all(), [
            'id' => 'required|exists:depts,id',
            'status' => 'required|in:' . Dept::STATUS_ON . ',' . Dept::STATUS_OFF
        ]);

        if ($validator->fails()) {
            return Response::make($validator->messages()->first(), 402);
        }

        $dept = Dept::find(Input::get('id'));
        if (Input::get('status') == Dept::STATUS_ON) {
            $dept->status = Dept::STATUS_OFF;
        } else {
            $dept->status = Dept::STATUS_ON;
        }
        $dept->save();

        return 'success';
    }
}