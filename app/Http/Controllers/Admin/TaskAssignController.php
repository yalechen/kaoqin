<?php
namespace App\Http\Controllers\Admin;

use App\Models\TaskAssign;
use Validator;
use Input;
use Auth;
use Redirect;
use App\Models\User;

class TaskAssignController extends BaseController
{

    /**
     * 临时指定任务管理页
     */
    public function index()
    {
        // 所有临时指定任务
        $data = TaskAssign::with('acceptUser');
        if (Input::has('ymonth')) {
            $data->whereYmonth(Input::get('ymonth'));
        }
        if (Input::has('key')) {
            $key = trim(Input::get('key'));
            $data->where('title', 'like', "%{$key}%");
        }
        if (Input::has('status')) {
            $data->whereStatus(Input::get('status'));
        }
        $data = $data->paginate(15);

        // 返回视图
        return v('index')->with(compact('data'));
    }

    /**
     * 新增&编辑临时指定任务
     */
    public function edit()
    {
        // 获取编辑信息
        if (Input::has('id')) {
            $data = TaskAssign::find(Input::input('id'));
        }
        return v('edit', compact('data'));
    }

    /**
     * 保存临时指定任务
     */
    public function save()
    {
        // 验证输入。
        $validator = Validator::make(Input::all(), [
            'title' => 'required|exists:task_assign,title,id,' . Input::get('id'),
            'accept_user_id' => 'required|exists:users,id,status,' . User::STATUS_ON,
            'remark' => 'required',
            'start_time' => 'required|date_format:Y-m-d H:i',
            'end_time' => 'required|date_format:Y-m-d H:i',
            'image1_path' => 'required',
            'times' => 'required|integer|min:1'
        ], [
            'title.required' => '任务标题不能为空',
            'title.exists' => '任务标题已经存在',
            'accept_user_id.required' => '任务接收者不能为空',
            'accept_user_id.exists' => '任务接收者不存在',
            'id.required' => '参数错误，只能修改常规任务，不能新增',
            'id.exists' => '所选常规任务不存在',
            'remark.required' => '任务备注不能为空',
            'start_time.required' => '开始时间不能为空',
            'start_time.date_format' => '开始时间格式错误',
            'end_time.required' => '结束时间不能为空',
            'end_time.date_format' => '结束时间格式错误',
            'times.required' => '拜访次数不能为空',
            'times.integer' => '拜访次数只能为整数',
            'times.min' => '拜访次数必须要大于等于1'
        ]);
        if ($validator->fails()) {
            return Redirect::to(URL::previous())->withMessageError($validator->messages()
                ->all())
                ->withInput();
        }

        $id = Input::has('id') ? Input::get('id') : 0;
        $task_assign = TaskAssign::find(Input::get('id'));
        $task_assign->remark = trim(Input::get('remark'));
        $task_assign->title = Input::get('title');
        $task_assign->accept_user_id = Input::get('accept_user_id');
        $task_assign->publish_user_id = Auth::user()->id;
        $task_assign->start_time = Input::get('start_time');
        $task_assign->end_time = Input::get('end_time');
        $task_assign->times = Input::get('times');
        if (Input::has('image1_path')) {
            $task_assign->image1_path = Input::get('image1_path');
        }
        $task_assign->save();

        return Redirect::route("TaskGeneralIndex")->withMessageSuccess($id > 0 ? '修改成功' : '新增成功');
    }

    /**
     * 删除临时指定任务
     */
    public function delete()
    {
        // 验证数据。
        $validator = Validator::make(Input::all(), [
            'id' => 'required|exists:task_assign,id'
        ], [
            'id.required' => '所选常规任务不能为空',
            'id.exists' => '所选常规任务不存在'
        ]);
        if ($validator->fails()) {
            return Redirect::route("TaskGeneralIndex")->withMessageError($validator->messages()
                ->all());
        }

        $task_assign = TaskAssign::find(Input::get('id'));
        $task_assign->delete();
        return Redirect::to(URL::previous())->withMessageSuccess('删除成功');
    }
}