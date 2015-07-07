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
use App\Models\TaskAssignCust;

class TaskAssignController extends BaseController
{

    /**
     * 临时指定任务管理页
     */
    public function index()
    {
        // 所有临时指定任务
        $data = TaskAssign::with('acceptUser', 'custs');
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
        // 按照完成度排序
        if (Input::has('rate')) {
            $data->orderByRaw('`visited_times`/`times` ' . Input::get('rate'));
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
        // 所有客户
        $cust = Cust::all();

        // 获取编辑信息
        if (Input::has('id')) {
            $data = TaskAssign::with('acceptUser')->find(Input::get('id'));

            // 已分配的商户门店
            $task_custs = TaskAssignCust::whereTaskAssignId(Input::get('id'))->get();

            // 参考图
            $images_str = $data->image1_path . $data->image2_path . $data->image3_path . $data->image4_path . $data->image5_path;
            $arr = explode('.', $images_str);
            $hashs = [];
            foreach ($arr as $k => $v) {
                if (strlen($v) > 32) {
                    $hashs[] = substr($v, - 32);
                }
            }
            $hashs = array_unique($hashs);
            $pictures = Storage::findMany($hashs);
        }
        return v('edit', compact('data', 'cust', 'task_custs', 'pictures'));
    }

    /**
     * 保存临时指定任务
     */
    public function save()
    {
        // 验证输入。
        $validator = Validator::make(Input::all(), [
            'title' => 'required',
            'accept_user_id' => 'required|exists:users,id,status,' . User::STATUS_ON,
            'start_time' => 'required|date_format:Y-m-d H:i',
            'end_time' => 'required|date_format:Y-m-d H:i|after:' . Input::get('start_time'),
            'times' => 'required',
            'cust' => 'required'
        ], [
            'title.required' => '任务标题不能为空',
            'accept_user_id.required' => '任务接收者不能为空',
            'accept_user_id.exists' => '任务接收者不存在',
            'start_time.required' => '开始时间不能为空',
            'start_time.date_format' => '开始时间格式错误',
            'end_time.required' => '结束时间不能为空',
            'end_time.date_format' => '结束时间格式错误',
            'end_time.after' => '结束时间必须实在开始时间后面',
            'times.required' => '客户拜访次数不能为空',
            'cust.required' => '客户不能为空'
        ]);
        if (Input::has('images')) {
            $old_images = explode(',', Input::get('images'));
            $old_images = Storage::findMany($old_images);
        }
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

        // 验证商户是否存在
        foreach ($arr as $cust_id => $num) {
            if (is_null(Cust::find($cust_id))) {
                unset($arr[$cust_id]);
                continue;
            }
        }
        if (empty($arr)) {
            return Redirect::to(URL::previous())->withMessageError('需要拜访的商户未指派')
                ->withInput()
                ->withOldImages($old_images);
        }

        $task_assign = TaskAssign::findOrNew(Input::get('id'));
        if (Input::has('id')) {
            // 已被执行的门店IDS
            $logs_cust_ids = TaskLog::whereTaskId(Input::get('id'))->whereTaskType($task_assign->getMorphClass())
                ->lists('cust_id')
                ->all();
            if (! empty($logs_cust_ids)) {
                $logs_cust_ids = array_unique($logs_cust_ids);
                // key和value互换
                $logs_cust_ids = array_flip($logs_cust_ids);
                // 求得差集，新的商户数组应该都有包含已被执行的商户门店ID
                $arr_temp = $arr;
                $arr_temp = array_flip($arr_temp);
                foreach ($logs_cust_ids as $k => $v) {
                    $temp = Cust::find($k);
                    if (in_array($k, $arr_temp)) {
                        // 查询旧的门店是否已经被执行了任务了，被执行了就不能删除了
                        // 判断旧的拜访数被改成已经拜访的数量了
                        $count = TaskLog::whereTaskId(Input::get('id'))->whereTaskType($task_assign->getMorphClass())
                            ->whereCustId($k)
                            ->count();
                        if ($count > $arr[$k]) {
                            return Redirect::to(URL::previous())->withMessageError('"' . $temp->name . '"已经有被接收者执行了任务' . $count . '次，大于你修改的' . $arr[$v])
                                ->withInput()
                                ->withOldImages($old_images);
                        }
                    } else {
                        // 原先有指派并被执行了任务，这次修改被删除了，要提示无法删除
                        return Redirect::to(URL::previous())->withMessageError('"' . $temp->name . '"已经有被接收者执行了拜访任务，无法删除这家门店，请保留')
                            ->withInput()
                            ->withOldImages($old_images);
                    }
                }
            }
            // 先删除原来的参考图
            $task_assign->image1_path = '';
            $task_assign->image2_path = '';
            $task_assign->image3_path = '';
            $task_assign->image4_path = '';
            $task_assign->image5_path = '';
            $task_assign->save();
        }

        $id = Input::has('id') ? Input::get('id') : 0;
        $task_assign->remark = Input::get('remark', '');
        $task_assign->title = trim(Input::get('title'));
        $task_assign->accept_user_id = Input::get('accept_user_id');
        $task_assign->publish_user_id = Auth::user()->id;
        $task_assign->start_time = Input::get('start_time');
        $task_assign->end_time = Input::get('end_time');
        $task_assign->times = array_sum($arr);
        if (Input::has('images')) {
            $images = explode(',', Input::get('images'));
            foreach ($images as $key => $image) {
                $field = 'image' . ($key + 1) . '_path';
                $task_assign->$field = Storage::find($image)->path;
            }
        }
        $task_assign->save();

        // 先删除旧的商户门店(这边保证新商户门店都是正确的了)
        TaskAssignCust::whereTaskAssignId($task_assign->id)->delete();
        foreach ($arr as $key => $value) {
            $task_assign_cust = TaskAssignCust::whereTaskAssignId(Input::get('id'))->whereCustId($key)->first();
            if (is_null($task_assign_cust)) {
                $task_assign_cust = new TaskAssignCust();
            }
            $task_assign_cust->task()->associate($task_assign);
            $task_assign_cust->cust_id = $key;
            $task_assign_cust->times = $value;
            $task_assign_cust->save();
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
        $data = $data->get();

        return v('accept_users_list')->with(compact('data'));
    }
}