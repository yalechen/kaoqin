<?php
namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Overtime;
use Validator;
use Input;
use Auth;
use Redirect;
use Response;
use URL;
use Carbon\Carbon;

class OvertimeController extends BaseController
{

    /**
     * 加班管理页
     */
    public function index()
    {
        // 根据参数获取加班信息
        $data = Overtime::with('user', 'auditUser');
        if (Input::has('hours')) {
            $data->orderBy('hours', Input::get('hours'));
        } else {
            $data->latest();
        }
        if (Input::has('status')) {
            $data->whereStatus(Input::get('status'));
        }
        if (Input::has('key')) {
            $key = Input::get('key');
            $data->where('reason', 'like', "%{$key}%");
        }
        if (Input::has('start_time') && Input::has('end_time')) {
            $data->where('start_time', '>=', Input::get('start_time'))->where('end_time', '<=', Input::get('end_time'));
        } elseif (Input::has('start_time')) {
            $data->where('start_time', '>=', Input::get('start_time'));
        } elseif (Input::has('end_time')) {
            $data->where('end_time', '<=', Input::get('end_time'));
        }

        $data = $data->paginate(15);

        // 返回视图
        return v('index')->with(compact('data'));
    }

    /**
     * 编辑加班
     */
    public function edit()
    {
        // 获取编辑信息
        if (Input::has('id')) {
            $data = Overtime::find(Input::get('id'));
            return v('edit')->with(compact('data'));
        }

        return Redirect::route('OvertimeIndex')->withMessageError('参数错误！');
    }

    /**
     * 保存
     */
    public function save()
    {
        // 验证输入。
        $validator = Validator::make(Input::all(), [
            'id' => 'required|exists:overtime,id',
            'start_time' => 'required|date_format:Y-m-d H:i',
            'end_time' => 'required|date_format:Y-m-d H:i|after:' . Input::get('start_time'),
            'hours' => 'required|integer|min:0',
            'reason' => 'required'
        ], [
            'start_time.required' => '开始时间不能为空',
            'start_time.date_format' => '开始时间格式错误',
            'end_time.required' => '结束时间不能为空',
            'end_time.date_format' => '结束时间格式错误',
            'end_time.after' => '结束时间必须实在开始时间后面',
            'hours.required' => '请假小时数不能为空',
            'hours.integer' => '请假小时数只能为整数',
            'hours.min' => '请假小时数必须要大于等于0',
            'id.required' => '加班记录不能为空',
            'id.exists' => '加班记录不存在',
            'reason.required' => '加班理由不能为空'
        ]);
        if ($validator->fails()) {
            return Redirect::to(URL::previous())->withMessageError($validator->messages()
                ->all())
                ->withInput();
        }
        // 保存
        $overtime = Overtime::find(Input::get('id'));
        $overtime->start_time = Input::get('start_time');
        $overtime->end_time = Input::get('end_time');
        $overtime->hours = Input::get('hours');
        $overtime->reason = Input::get('reason');
        $overtime->audit_user_id = Input::get('audit_user_id');
        $overtime->save();

        return Redirect::route("CustIndex")->withMessageSuccess('修改成功');
    }

    /**
     * 删除加班
     */
    public function delete()
    {
        // 验证数据。
        $validator = Validator::make(Input::all(), [
            'id' => 'required|exists:overtime,id'
        ], [
            'id.required' => '所选加班记录不能为空',
            'id.exists' => '所选加班记录不存在'
        ]);
        if ($validator->fails()) {
            return Redirect::route("OvertimeIndex")->withMessageError($validator->messages()
                ->all());
        }

        $overtime = Overtime::find(Input::get('id'));
        $overtime->delete();
        return Redirect::to(URL::previous())->withMessageSuccess('删除成功');
    }

    /**
     * 加班审核
     */
    public function audit()
    {
        // 验证数据。
        $validator = Validator::make(Input::all(), [
            'overtime_id' => 'required|exists:overtime,id',
            'status' => 'required|in:' . Overtime::STATUS_REJECT . ',' . Overtime::STATUS_PASS,
            'remark' => 'required_if:status,' . Overtime::STATUS_REJECT
        ], [
            'overtime_id.required' => '所选加班记录不能为空',
            'overtime_id.exists' => '所选加班记录不存在',
            'status.required' => '审核状态不能为空',
            'status.exists' => '审核状态只能为审核通过或者审核驳回',
            'remark.required_if' => '当审核驳回，审核备注不能为空'
        ]);
        if ($validator->fails()) {
            return Response::make($validator->messages()->first(), 402);
        }

        $overtime = Overtime::find(Input::get('overtime_id'));
        if ($overtime->status != Overtime::STATUS_WAIT) {
            return Response::make('已经审核完毕，不能重复审核', 402);
        }
        // 只有该用户的上级才能审核
        if (User::find($overtime->user_id)->parent_user_id != Auth::user()->id) {
            return Response::make('只有该用户的上级领导才有审核的权限', 402);
        }

        $overtime->auditUser()->associate(Auth::user());
        $overtime->audit_time = new Carbon();
        $overtime->status = Input::get('status');
        $overtime->remark = Input::get('remark', '');
        $overtime->save();
        return 'success';
    }
}