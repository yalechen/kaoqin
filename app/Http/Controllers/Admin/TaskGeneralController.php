<?php
namespace App\Http\Controllers\Admin;

use App\Models\TaskGeneral;
use Validator;
use Input;
use Auth;
use Redirect;
use App\Models\CustLevel;
use Illuminate\Support\Facades\URL;

class TaskGeneralController extends BaseController
{

    /**
     * 常规任务管理页
     */
    public function index()
    {
        // 所有门店等级
        $cust_level = CustLevel::all();

        // 所有常规任务
        $data = TaskGeneral::with('acceptUser', 'custLevel');
        if (Input::has('ymonth')) {
            $data->whereYmonth(Input::get('ymonth'));
        }
        if (Input::has('cust_level_id')) {
            $data->wherecustLevelId(Input::get('cust_level_id'));
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
        return v('index')->with(compact('data', 'cust_level'));
    }

    /**
     * 新增&编辑常规任务
     */
    public function edit()
    {

        // 获取编辑信息
        if (Input::has('id')) {
            $data = TaskGeneral::find(Input::input('id'));
            return v('edit', compact('data'));
        }
        return v('edit')->withMessageError('参数错误，常规任务为系统自动生成，只能修改');
    }

    /**
     * 保存常规任务
     */
    public function save()
    {
        // 验证输入。
        $validator = Validator::make(Input::all(), [
            'id' => 'required|exists:task_general,id',
            'remark' => 'required',
            'custs' => 'required|integer|min:1'
        ], [
            'remark.required' => '任务备注不能为空',
            'id.required' => '参数错误，只能修改常规任务，不能新增',
            'id.exists' => '所选常规任务不存在',
            'custs.required' => '拜访次数不能为空',
            'custs.integer' => '拜访次数只能为整数',
            'custs.min' => '拜访次数必须要大于等于1'
        ]);
        if ($validator->fails()) {
            return Redirect::to(URL::previous())->withMessageError($validator->messages()
                ->all())
                ->withInput();
        }

        $id = Input::has('id') ? Input::get('id') : 0;
        $task_general = TaskGeneral::find(Input::get('id'));
        $task_general->remark = trim(Input::get('remark'));
        $task_general->custs = Input::get('custs');
        $task_general->save();

        return Redirect::route("TaskGeneralIndex")->withMessageSuccess('修改成功');
    }

    /**
     * 删除常规任务
     */
    public function delete()
    {
        // 验证数据。
        $validator = Validator::make(Input::all(), [
            'id' => 'required|exists:task_general,id'
        ], [
            'id.required' => '所选常规任务不能为空',
            'id.exists' => '所选常规任务不存在'
        ]);
        if ($validator->fails()) {
            return Redirect::route("TaskGeneralIndex")->withMessageError($validator->messages()
                ->all());
        }

        // TODO:判断任务是否已经有被执行了，有则不能被删除
        $task_general = TaskGeneral::find(Input::get('id'));
        $task_general->delete();
        return Redirect::to(URL::previous())->withMessageSuccess('删除成功');
    }
}