<?php
namespace App\Http\Controllers\Admin;

use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\User;
use Validator;
use Input;
use Auth;
use Redirect;
use Response;
use URL;
use SplFileObject;
use Illuminate\Support\Facades\DB;

class LeaveController extends BaseController
{

    /**
     * 请假批量导入页
     */
    public function index()
    {
        // 返回视图
        return v('index');
    }

    /**
     * 请假批量导入处理
     */
    public function import()
    {
        set_time_limit(0);
        if (! Input::hasFile('file')) {
            return Redirect::route('LeaveExcelIndex')->withMessageError('请先选择文件后在进行提交！');
        }

        $file = Input::file('file');
        $fp = new SplFileObject($file, 'rb');
        $fp->seek(filesize($file));
        $total = $rows = $fp->key();

        // 重新定位
        $fp->seek(0);
        $inputs = [];
        $logs = [];
        $data = [];

        $success = 0;
        while ($rows -- && ! $fp->eof()) {
            if ($rows == $total - 1) {
                $tmp = $fp->fgetcsv();
                $fp->next();
                continue;
            }
            $tmp = $fp->fgetcsv();
            $inputs['realname'] = $realname = mb_convert_encoding(trim($tmp[0]), 'utf-8', 'gbk');
            $inputs['mobile'] = $mobile = mb_convert_encoding(trim($tmp[1]), 'utf-8', 'gbk');
            $inputs['leave_type_name'] = $leave_type_name = mb_convert_encoding(trim($tmp[2]), 'utf-8', 'gbk');
            $inputs['start_time'] = $start_time = mb_convert_encoding($tmp[3], 'utf-8', 'gbk');
            $inputs['end_time'] = $end_time = mb_convert_encoding($tmp[4], 'utf-8', 'gbk');
            $inputs['days'] = $days = intval($tmp[5]);
            $inputs['hours'] = $hours = intval($tmp[6]);
            $created_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');
            // dd(compact('leave_type_name', 'realname', 'start_time', 'end_time', 'days', 'hours', 'created_at', 'updated_at'));
            // 验证输入。
            $validator = Validator::make($inputs, [
                'realname' => 'required|exists:users,realname,mobile,' . $mobile,
                'mobile' => 'required|mobile',
                'leave_type_name' => 'required|exists:leave_type,name',
                'start_time' => 'required|date_format:Y-m-d H:i:s',
                'end_time' => 'required|date_format:Y-m-d H:i:s',
                'days' => 'required|integer|min:0',
                'hours' => 'required|integer|min:0'
            ], [
                'realname.required' => '真实姓名不能为空',
                'realname.exists' => '用户不存在，请用此真实姓名和手机号注册此用户',
                'mobile.mobile' => '手机号格式不存在',
                'mobile.required' => '手机号不能为空',
                'leave_type_name.required' => '假别不能为空',
                'leave_type_name.exists' => '假别不存在，请建立请假类型',
                'start_time.required' => '开始时间不能为空',
                'start_time.date_format' => '开始时间格式错误，请输入0000-00-00 00:00:00的格式',
                'end_time.required' => '结束时间不能为空',
                'end_time.date_format' => '结束时间格式错误，请输入0000-00-00 00:00:00的格式',
                'days.integer' => '请假天数必须是一个整数',
                'days.min' => '请假天数必须大于等于0！',
                'days.required' => '请假天数不能为空',
                'hours.integer' => '请假小时数必须是一个整数',
                'hours.min' => '请假小时数必须大于等于0！',
                'hours.required' => '请假小时数不能为空'
            ]);
            if ($validator->fails()) {
                $logs[] = [
                    'realname' => $realname,
                    'mobile' => $mobile,
                    'msg' => $validator->messages()->first()
                ];
                continue;
            }
            // 查询同一个人相同的时间段是否已经插入
            $leave = Leave::whereRealname($realname)->whereLeaveTypeName($leave_type_name)
                ->where('start_time', '<=', $start_time)
                ->where('end_time', '>', $start_time)
                ->first();
            if (! is_null($leave)) {
                $logs[] = [
                    'realname' => $realname,
                    'mobile' => $mobile,
                    'msg' => '请假时间内已经重复请假了'
                ];
                continue;
            }
            $leave = Leave::whereRealname($realname)->whereLeaveTypeName($leave_type_name)
                ->where('start_time', '<', $end_time)
                ->where('end_time', '>=', $end_time)
                ->first();
            if (! is_null($leave)) {
                $logs[] = [
                    'realname' => $realname,
                    'mobile' => $mobile,
                    'msg' => '请假时间内已经重复请假了'
                ];
                continue;
            }

            // 得到用户ID
            $user = User::whereRealname($realname)->whereMobile($mobile)->first();
            $user_id = $user->id;
            $dept_id = $user->dept_id;
            // 得到假别ID
            $leave_type = LeaveType::whereName($leave_type_name)->first();
            $leave_type_id = $leave_type->id;

            $data[] = "('" . implode("','", compact('leave_type_id', 'leave_type_name', 'user_id', 'realname', 'dept_id', 'start_time', 'end_time', 'days', 'hours', 'created_at', 'updated_at')) . "')";

            $success ++;
            $fp->next();
        }
        $values = '';
        if (! empty($data)) {
            $values = implode(',', $data);
            DB::insert("insert into `leave`(leave_type_id,leave_type_name,user_id,realname,dept_id,start_time,end_time,days,hours,created_at,updated_at) values {$values}");
        }
        return Redirect::route('LeaveExcelIndex')->withMessageWarning("成功导入{$success}条数据！")->with(compact('logs'));
    }
}