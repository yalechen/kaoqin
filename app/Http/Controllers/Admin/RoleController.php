<?php
namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use Validator;
use Input;
use Auth;
use Redirect;
use Response;
use URL;
use App\Models\Purview;

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
            'key' => 'required|min:3|max:30|alpha_dash|unique:roles,key,' . Input::get('id'),
            'name' => 'required|min:3|max:30',
            'status' => 'required|in:' . Role::STATUS_OFF . ',' . Role::STATUS_ON
        ], [
            'key.required' => '标识key不能为空',
            'key.alpha_dash' => '标识key仅允许字母、数字、破折号（-）以及底线（_）',
            'key.unique' => '标识key已存在',
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
        if (empty($role->users) !== true) {
            return Redirect::route("RoleIndex")->withMessageError('角色已经被指派了用户，请先取消指派，在进行删除');
        }
        if (empty($role->purviews) !== true) {
            return Redirect::route("RoleIndex")->withMessageError('角色已经被指派了权限，请先取消指派，在进行删除');
        }

        $role->delete();
        return Redirect::to(URL::previous())->withMessageSuccess('删除成功');
    }

    /**
     * 角色成员指派页面
     */
    public function users()
    {
        // 验证数据。
        $validator = Validator::make(Input::all(), [
            'role_id' => 'required|exists:roles,id,status,' . Role::STATUS_ON
        ], [
            'role_id.required' => '所选角色不能为空',
            'role_id.exists' => '所选角色不存在或状态已被禁用'
        ]);
        if ($validator->fails()) {
            return Redirect::to(URL::previous())->withMessageError($validator->messages()
                ->all());
        }

        // 所有状态为在职的成员,当页的所有用户ID
        $user = User::whereStatus(User::STATUS_ON);
        if (Input::has('number')) {
            $users = $user->orderBy('number', Input::get('number'))->paginate(15);
            $user_ids = $users->lists('id')->all();
        } else {
            $users = $user->paginate(15);
            $user_ids = $users->lists('id')->all();
        }
        $user_ids_str = implode(',', $user_ids);

        // 当页中此角色已指派的成员ID
        $role_id = Input::get('role_id');
        $role = Role::find($role_id);
        $assigned_user_ids = Role::find($role_id)->users()
            ->whereIn('user_id', $user_ids)
            ->lists('user_id')
            ->all();
        $assigned_user_ids_str = implode(',', $assigned_user_ids);

        return v('users', compact('role_id', 'role', 'users', 'user_ids', 'assigned_user_ids', 'user_ids_str', 'assigned_user_ids_str'));
    }

    /**
     * 角色成员指派
     */
    public function userAssign()
    {
        $validator = Validator::make(Input::all(), [
            'user_id' => 'required|exists:users,id,status,' . User::STATUS_ON,
            'role_id' => 'required|exists:roles,id,status,' . Role::STATUS_ON,
            'flag' => 'required|in:1,2'
        ], [
            'user_id.required' => '用户不能为空',
            'user_id.exists' => '用户不存在或已离职',
            'role_id.required' => '被指派角色不能为空',
            'role_id.exists' => '被指派角色不存在或已禁用',
            'flag.required' => '指派方式不能为空',
            'flag.in' => '指派方式错误'
        ]);
        if ($validator->fails()) {
            return Response::make($validator->messages()->first(), 402);
        }

        // 查看是否已经指派
        $role = Role::find(Input::get('role_id'));
        if (Input::get('flag') == 1) {
            // 单个指派
            $temp = $role->users()
                ->whereUserId(Input::get('user_id'))
                ->first();
            if (is_null($temp)) {
                $role->users()->attach(Input::get('user_id'));
                return '指派成功';
            } else {
                $role->users()->detach(Input::get('user_id'));
                return '取消指派成功';
            }
        } else {
            // 批量指派，同时要传当页的所有用户ID，因为可能存在page=1指派了几个，到page=2又指派了一些，那page=1是否就不存在了
            // 所以要只针对当页的用户ID做增删
            if (Input::has('all_user_ids')) {
                $user_id = explode(',', Input::get('user_id'));
                $all_user_ids = explode(',', Input::get('all_user_ids'));
                foreach ($all_user_ids as $uid) {
                    if (in_array($uid, $user_id)) {
                        $uid = intval($uid);
                        $temp = $role->users()
                            ->whereUserId($uid)
                            ->first();
                        if (is_null($temp)) {
                            $role->users()->attach($uid);
                        }
                    } else {
                        $uid = intval($uid);
                        $role->users()->detach($uid);
                    }
                }
                return '批量指派成功';
            }
            return '批量指派不成功，因为当页用户不存在';
        }
    }

    /**
     * 获取角色权限【返回ztree 格式】
     */
    public function getPurview()
    {
        $validator = Validator::make(Input::all(), [
            'role_id' => 'required|exists:roles,id,status,' . Role::STATUS_ON
        ], [
            'role_id.required' => '被指派角色不能为空',
            'role_id.exists' => '被指派角色不存在或已禁用'
        ]);
        if ($validator->fails()) {
            return Response::make($validator->messages()->first(), 402);
        }

        $purviews = Purview::whereStatus(Purview::STATUS_ON)->get();

        $role_purview_ids = Role::find(Input::get('role_id'))->purviews()
            ->lists('purview_id')
            ->all();

        $data = [];
        foreach ($purviews as $i => $item) {
            $data[$i]['id'] = $item['id'];
            $data[$i]['pid'] = $item['parent_id'];
            $data[$i]['key'] = $item['key'];
            $data[$i]['name'] = $item['name'];
            if (in_array($item['id'], $role_purview_ids)) {
                $data[$i]['checked'] = "true";
            } else {
                $data[$i]['checked'] = "false";
            }
        }

        return json_encode($data);
    }

    /**
     * 角色权限指派
     */
    public function purviewAssign()
    {
        $validator = Validator::make(Input::all(), [
            'role_id' => 'required|exists:roles,id,status,' . Role::STATUS_ON
        ], [
            'role_id.required' => '被指派角色不能为空',
            'role_id.exists' => '被指派角色不存在或已禁用'
        ]);
        if ($validator->fails()) {
            return Response::make($validator->messages()->first(), 402);
        }

        $role = Role::find(Input::get('role_id'));
        $purview_ids = Input::get('purview_ids', []);

        $role->purviews()->sync($purview_ids);

        return $role;
    }
}