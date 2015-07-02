<?php
namespace App\Http\Controllers\Admin;

use App\Models\TaskAssign;
use Validator;
use Input;
use Auth;
use Redirect;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use App\Models\Storage;
use App\Models\Cust;
use App\Models\TaskLog;

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
        $data = $data->latest()->paginate(15);

        // 返回视图
        return v('index')->with(compact('data'));
    }

    /**
     * 新增&编辑临时指定任务
     */
    public function edit()
    {
        // 所有客户
        $cust = Cust::all();

        // 获取编辑信息
        if (Input::has('id')) {
            $data = TaskAssign::find(Input::input('id'));
        }
        return v('edit', compact('data', 'cust'));
    }

    /**
     * 保存临时指定任务
     */
    public function save()
    {
        // 验证输入。
        $validator = Validator::make(Input::all(), [
            'title' => 'required|unique:task_assign,title,id,' . Input::get('id'),
            'accept_user_id' => 'required|exists:users,id,status,' . User::STATUS_ON,
            'start_time' => 'required|date_format:Y-m-d H:i',
            'end_time' => 'required|date_format:Y-m-d H:i|after:' . Input::get('start_time'),
            'times' => 'required',
            'cust' => 'required'
        ], [
            'title.required' => '任务标题不能为空',
            'title.unique' => '任务标题已经存在',
            'accept_user_id.required' => '任务接收者不能为空',
            'accept_user_id.exists' => '任务接收者不存在',
            'id.required' => '参数错误，只能修改常规任务，不能新增',
            'id.exists' => '所选常规任务不存在',
            'remark.required' => '任务备注不能为空',
            'start_time.required' => '开始时间不能为空',
            'start_time.date_format' => '开始时间格式错误',
            'end_time.required' => '结束时间不能为空',
            'end_time.date_format' => '结束时间格式错误',
            'end_time.after' => '结束时间必须实在开始时间后面',
            'times.required' => '客户拜访次数不能为空',
            'cust.required' => '客户不能为空'
        ]);
        if ($validator->fails()) {
            return Redirect::to(URL::previous())->withMessageError($validator->messages()
                ->all())
                ->withInput();
        }

        // 判断并得到客户和拜访数
        $cust = (array) Input::get('cust');
        $times = (array) Input::get('times');
        $arr = [];
        foreach ($cust as $key => $item) {
            if ($times[$key] > 0 && $item > 0) {
                $arr[$item] = intval($times[$key]);
            }
        }
        // $arr=array_unique($arr);
        // 验证商户是否存在
        foreach ($arr as $cust_id => $num) {
            if (is_null(Cust::find($cust_id))) {
                unset($arr[$cust_id]);
                continue;
            }
        }
        if (empty($arr)) {
            return Redirect::to(URL::previous())->withMessageError('需要拜访的商户未指派')->withInput();
        }

        $id = Input::has('id') ? Input::get('id') : 0;
        $task_assign = TaskAssign::findOrNew(Input::get('id'));
        $task_assign->remark = Input::get('remark','');
        $task_assign->title = trim(Input::get('title'));
        $task_assign->accept_user_id = Input::get('accept_user_id');
        $task_assign->publish_user_id = Auth::user()->id;
        $task_assign->start_time = Input::get('start_time');
        $task_assign->end_time = Input::get('end_time');
        $task_assign->times = array_sum($arr);
        if (Input::has('images')) {
            $images = explode(',', Input::get('images'));
            foreach ($images as $key => $image) {
                if ($key == 0) {
                    $task_assign->image1 = Storage::find($image)->path;
                }
                if ($key == 1) {
                    $task_assign->image2 = Storage::find($image)->path;
                }
                if ($key == 2) {
                    $task_assign->image3 = Storage::find($image)->path;
                }
                if ($key == 3) {
                    $task_assign->image4 = Storage::find($image)->path;
                }
                if ($key == 4) {
                    $task_assign->image5 = Storage::find($image)->path;
                }
            }
        }
        $task_assign->save();

        // 添加任务拜访记录
        foreach ($arr as $key => $value) {
            $task_log = new TaskLog();
            $task_log->user_id = Input::get('accept_user_id');
            $task_log->task()->associate($task_assign);
            $task_log->cust_id = $key;
            $task_log->times = $value;
            $task_log->save();
        }

        return Redirect::route("TaskAssignIndex")->withMessageSuccess($id > 0 ? '修改成功' : '新增成功');
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
            return Redirect::route("TaskAssignIndex")->withMessageError($validator->messages()
                ->all());
        }

        $task_assign = TaskAssign::find(Input::get('id'));
        $task_assign->delete();
        return Redirect::to(URL::previous())->withMessageSuccess('删除成功');
    }

    /**
     * 接收者搜索
     */
    public function searchAcceptUser()
    {
        // 取得数据模型。
        $data = User::latest('id')->where('status', User::STATUS_ON);

        // 处理筛选条件。
        if (Input::has('modal_key')) {
            $key = Input::get('modal_key');
            $data->where('name', 'like', "%{$key}%")
                ->orWhere('mobile', 'like', "%{$key}%")
                ->orWhere('realname', 'like', "%{$key}%");
        }
        // 返回单页数据。
        $data = $data->paginate(Input::get('limit', 6));

        return v('accept_users_list')->with(compact('data'));
    }
}