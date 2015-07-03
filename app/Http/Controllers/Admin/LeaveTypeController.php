<?php
namespace App\Http\Controllers\Admin;

use App\Models\LeaveType;
use Validator;
use Input;
use Auth;
use Redirect;
use Response;
use URL;

class LeaveTypeController extends BaseController
{

    /**
     * 请假类型管理页
     */
    public function index()
    {
        // 所有类型
        $data = LeaveType::paginate(15);
        if (Input::has('sort')) {
            $data = LeaveType::orderBy('sort', Input::get('sort'))->paginate(15);
        }

        // 返回视图
        return v('index')->with(compact('data'));
    }

    /**
     * 新增&编辑请假类型
     */
    public function edit()
    {
        // 获取编辑信息
        if (Input::has('id')) {
            $data = LeaveType::find(Input::input('id'));
        }

        return v('edit', compact('data'));
    }

    /**
     * 保存请假类型
     */
    public function save()
    {
        // 验证输入。
        $validator = Validator::make(Input::all(), [
            'name' => 'required|unique:leave_type,name,' . Input::get('id'),
            'sort' => 'required|integer|min:1'
        ], [
            'name.required' => '名称不能为空',
            'name.unique' => '名称已经存在',
            'sort.required' => '排序值不能为空',
            'sort.integer' => '排序值只能为整数',
            'sort.min' => '排序值必须要大于等于1'
        ]);
        if ($validator->fails()) {
            return Redirect::to(URL::previous())->withMessageError($validator->messages()
                ->all())
                ->withInput();
        }

        $id = Input::has('id') ? Input::get('id') : 0;
        $leave_type = LeaveType::findOrNew(Input::get('id'));
        $leave_type->name = trim(Input::get('name'));
        $leave_type->sort = Input::get('sort');
        $leave_type->save();

        return Redirect::route("LeaveTypeIndex")->withMessageSuccess($id > 0 ? '修改成功' : '新增成功');
    }

    /**
     * 删除请假类型
     */
    public function delete()
    {
        // 验证数据。
        $validator = Validator::make(Input::all(), [
            'id' => 'required|exists:leave_type,id'
        ], [
            'id.required' => '所选请假类型不能为空',
            'id.exists' => '所选请假类型不存在'
        ]);
        if ($validator->fails()) {
            return Redirect::route("LeaveTypeIndex")->withMessageError($validator->messages()
                ->all());
        }

        $leave_type = LeaveType::find(Input::get('id'));
        $leave_type->delete();
        return Redirect::to(URL::previous())->withMessageSuccess('删除成功');
    }
}