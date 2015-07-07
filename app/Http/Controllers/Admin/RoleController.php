<?php
namespace App\Http\Controllers\Admin;

use App\Models\Role;
use Validator;
use Input;
use Auth;
use Redirect;
use Response;
use URL;

class RoleController extends BaseController
{

    /**
     * 角色管理页
     */
    public function index()
    {
        // 根据参数获取角色信息
        if (Input::has('key_sort')) {
            $data = Role::orderBy('key', Input::get('key_sort'));
        } else {
            $data = Role::orderBy('id', 'desc');
        }
        if (Input::has('key')) {
            $key = Input::get('key');
            $data->where(function ($q) use($key)
            {
                $q->where('key', 'like', "%{$key}%")
                    ->orWhere('name', 'like', "%{$key}%");
            });
        }

        $data = $data->paginate(15);

        // 返回视图
        return v('index')->with(compact('data'));
    }

    /**
     * 新增&编辑角色
     */
    public function edit()
    {
        // 获取编辑信息
        if (Input::has('id')) {
            $data = Role::find(Input::get('id'));
        }

        return v('edit', compact('data'));
    }

    /**
     * 保存角色
     */
    public function save()
    {
        // 验证输入。
        $validator = Validator::make(Input::all(), [
            'key' => 'required|min:3|max:30|alpha_dash|exists:roles,key,' . Input::get('key'),
            'name' => 'required|min:3|max:30',
            'status' => 'required|in:' . Role::STATUS_OFF . ',' . Role::STATUS_ON
        ], [
            'key.required' => '标识key不能为空',
            'key.alpha_dash' => '标识key仅允许字母、数字、破折号（-）以及底线（_）',
            'key.exists' => '标识key不存在',
            'key.min' => '标识key不能少于3个字符',
            'key.max' => '标识key不能多余30个字符',
            'name.required' => '角色名称不能为空',
            'name.min' => '角色名称必须最少要有3个字符',
            'name.max' => '角色名称不能大于30个字符',
            'status.required' => '状态不能为空',
            'status.in' => '状态仅能为开启和关闭'
        ]);
        if ($validator->fails()) {
            return Redirect::to(URL::previous())->withMessageError($validator->messages()
                ->all())
                ->withInput();
        }
        // 获取Logo图片信息
        $id = Input::has('id') ? Input::get('id') : 0;
        $role = Role::findOrNew(Input::get('id'));
        $role->key = Input::get('key');
        $role->name = trim(Input::get('name'));
        $role->remark = Input::get('remark', '');
        $role->status = Input::get('status');
        $role->save();

        return Redirect::route("RoleIndex")->withMessageSuccess($id > 0 ? '修改成功' : '新增成功');
    }

    /**
     * 删除角色
     */
    public function delete()
    {
        // 验证数据。
        $validator = Validator::make(Input::all(), [
            'id' => 'required|exists:roles,id'
        ], [
            'id.required' => '所选角色不能为空',
            'id.exists' => '所选角色不存在'
        ]);
        if ($validator->fails()) {
            return Redirect::route("RoleIndex")->withMessageError($validator->messages()
                ->all());
        }

        // 查看是否有指派了权限或者角色
        $role = Role::find(Input::get('id'));
        if ($role->users) {
            return Redirect::route("RoleIndex")->withMessageError('角色已经被指派了用户，请先取消指派，在进行删除');
        }
        if ($role->purviews) {
            return Redirect::route("RoleIndex")->withMessageError('角色已经被指派了权限，请先取消指派，在进行删除');
        }

        $role->delete();
        return Redirect::to(URL::previous())->withMessageSuccess('删除成功');
    }
}