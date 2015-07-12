<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustLevel;
use App\Models\TaskGeneral;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Session;
use Auth;
use Response;
use Input;
use Hash;
use App\Models\TaskSign;
use App\Models\User;
use App\Models\Attn;
use App\Models\TaskLog;
use App\Models\Cust;
use App\Models\TaskCust;
use DB;


class AttendanceController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 考勤列表
     */
    public function getAttendanceList()
    {
        $validator = Validator::make(Input::all(), [
            'ymonth' => 'required',

        ], [
            'ymonth.required' => '年月不能为空'
        ]);

        if ($validator->fails()) {
            // 验证失败，返回错误信息。
            return $this->apiReturn(402, $validator->messages()->first());
        }

        $date = Input::get('ymonth');

        //本月请假天数
        $month_leave_days =  Attn::where('user_id', Auth::user()->id)
            ->where('ymonth',  $date)
            ->where('is_leave', 1)
            ->count();

        //本月迟到天数
        $month_late_days = Attn::where('user_id', Auth::user()->id)
            ->where('ymonth',  $date)
            ->where('is_late', 1)
            ->count();

        //本月加班天数
        $month_overtime_days = Attn::where('user_id', Auth::user()->id)
            ->where('ymonth',  $date)
            ->where('is_overtime', 1)
            ->count();

        //本月正常天数
        $month_normal_days = Attn::where('user_id', Auth::user()->id)
            ->where('ymonth',  $date)
            ->where('status', 'Ok')
            ->count();

        //本月未打卡天数: 本月总天数-已打卡天数
        $month_sign_days = Attn::where('user_id', Auth::user()->id)
            ->where('ymonth',  $date)
            ->count();

        $month_unsign_days = date('t') - $month_sign_days;


        //统计信息
        $info = [];

        //列表信息
        $list = [];

        //本月开始结束日期
        $firstday = date("Y-m-01",strtotime($date));
        $lastday = date("d",strtotime("$firstday +1 month -1 day"));

        //获取每天的考勤信息
        for($day=1;$day<=$lastday;$day++){
            $attn = Attn::where('ymonth',  $date)->where('day',$day)->first();
            $list[$day]['date'] = $date.'-'.$day;
            if(! is_null($attn)){
                $list[$day]['is_sign_on'] = $attn->is_sign_on;
                $list[$day]['is_sign_off'] = $attn->is_sign_off;
                $list[$day]['is_late'] = $attn->is_late;
                $list[$day]['is_leave'] = $attn->is_leave;
                $list[$day]['is_overtime'] = $attn->is_overtime;
                $list[$day]['status'] = $attn->status;
            }
        }

        $info['month_leave_days'] = $month_leave_days;
        $info['month_late_days'] = $month_late_days;
        $info['month_overtime_days'] = $month_overtime_days;
        $info['month_normal_days'] = $month_normal_days;
        $info['month_unsign_days'] = $month_unsign_days;

        $rs['info'] = $info;
        $rs['list'] = $list;

        return $this->apiReturn(402, "本月考勤信息",$rs);

    }


    /**
     * 外勤日志列表
     */
    public function getAttendanceLogList()
    {
        $validator = Validator::make(Input::all(), [
            'limit' => 'integer',
            'page' => 'integer',
            'from_date' => 'date',
            'to_date' => 'date',


        ], [
            'limit.integer' => '每页数量需为整数',
            'page.integer' => '页码需为整数',
            'from_date.date' => '开始日期格式不合法',
            'to_date.date' => '结束日期格式不合法',
        ]);

        if ($validator->fails()) {
            // 验证失败，返回错误信息。
            return $this->apiReturn(402, $validator->messages()->first());
        }

        //统计信息
        $info = [];

        //列表信息
        $list = [];


        //里程统计
        if (Input::has('from_date') && Input::has('to_date')) {
            $mileage = Attn::where('user_id', Auth::user()->id)
                ->where('date', '>=' , Input::get('from_date'))
                ->where('date', '<=' , Input::get('to_date'))
                ->sum('mileage');

            $info['from_date'] = Input::get('from_date');
            $info['to_date'] = Input::get('to_date');
            $info['mileage'] = $mileage;
        }

        $attn_list = Attn::where('user_id',Auth::user()->id);


        //开始日期
        if (Input::has('from_date')) {
            $attn_list = $attn_list->where('date', '>=' , Input::get('from_date'));
        }

        //结束日期
        if (Input::has('to_date')) {
            $attn_list = $attn_list->where('date', '<=' , Input::get('to_date'));
        }

        $limit = Input::get('limit');

        if(Input::has('limit')){
            $attn_list =  $attn_list->latest()->paginate($limit)->getCollection();;
        }else{
            $attn_list =  $attn_list->latest()->get();
        }



        //处理返回结果
        if(! $attn_list->isEmpty()){
            foreach($attn_list as $key=>$item){
                $list[$key]['id'] = $item->id;
                $list[$key]['date'] = $item->date;
                $list[$key]['mileage'] = $item->mileage;
                $list[$key]['visited_custs'] = $item->visited_custs;
            }
        }

        $count = Attn::where('user_id',Auth::user()->id)->count();

        $rs['info'] = $info;
        $rs['list'] = $list;

        return $this->apiReturn(402, "外勤日志列表",$rs,$count);
    }


    /**
     * 外勤日志详情
     */
    public function getAttendanceLogDetail()
    {
        $validator = Validator::make(Input::all(), [
            'visit_date' => 'date|exists:task_logs,visit_date,user_id,'.Auth::user()->id,

        ], [
            'visit_date.date' => '日期格式不合法',
            'visit_date.exists' => '该日期没有外勤日志详情',
        ]);

        if ($validator->fails()) {
            // 验证失败，返回错误信息。
            return $this->apiReturn(402, $validator->messages()->first());
        }

        $attn = Attn::where('user_id',Auth::user()->id)->where('date',Input::get('visit_date'))->first();

        //当日统计信息
        $info = [];

        if(! is_null($attn)){
            $info['date'] = $attn->date;
            $info['mileage'] = $attn->mileage;
            $info['visited_custs'] = $attn->visited_custs;
        }

        //签到记录
        $task_logs = $this->_getAttendanceLogs(Input::get('visit_date'));

        $rs['info'] = $info;
        $rs['log_list'] = $task_logs;

        return $this->apiReturn(402, "外勤日志详情",$rs);

    }

    /**
     * 获取今天签到记录
     */
    public function _getAttendanceLogs($date , $orderBy = "asc"){

        if($orderBy == "desc"){
            $orderBy = "desc";
        }
        $logs = AttendanceLog::where('user_id',Auth::user()->id)->where('visit_date',$date)->orderBy('id',$orderBy)->get();

        $list = [];

        //处理返回结果
        if(! $logs->isEmpty()){
            foreach($logs as $key=>$item){
                $list[$key]['id'] = $item->id;
                $list[$key]['visit_date'] = $item->visit_date;
                $list[$key]['title'] = $item->title;
                $list[$key]['location'] = $item->location;
                $list[$key]['mileage'] = $item->mileage;
                $list[$key]['created_at'] = date_format($item->created_at,'H:i:s');
            }
        }

        return $list;

    }


    /**
     * 我的巡店任务
     */
    public function getAttendanceVisit()
    {
        $sum_need_visit_times =  AttendanceGeneral::where('accept_user_id',Auth::user()->id)
            ->where('ymonth',date('Y-m',time()))
            ->sum('times');

        $sum_visited_times =  AttendanceGeneral::where('accept_user_id',Auth::user()->id)
            ->where('ymonth',date('Y-m',time()))
            ->sum('visited_times');


        if($sum_need_visit_times <= 0 ){
            $complete_ratio = 0;
        }else{
            //完成率
            $complete_ratio = ($sum_visited_times / $sum_need_visit_times) * 100  ;
        }

        //按门店等级统计信息
        $task_list = [];

        //本月统计信息
        $month_info = [];

        //月总里程
        $month_mileage = Attn::where('user_id',Auth::user()->id)
            ->where('ymonth',date('Y-m',time()))
            ->sum('mileage');

        $month_info['complete_ratio'] = round($complete_ratio,2)."%";
        $month_info['month_mileage'] = $month_mileage;


        //按门店等级遍历任务
        $cust_level = CustLevel::all();

        if(! $cust_level->isEmpty()){
            foreach($cust_level as $key => $item){
                $task_general = AttendanceGeneral::where('cust_level_id',$item->id)
                    ->where('accept_user_id',Auth::user()->id)
                    ->where('ymonth',date('Y-m',time()))
                    ->first();

                $task_list[$key]['level_id']  = $item->id;
                $task_list[$key]['level_name']  = $item->name;
                $task_list[$key]['times']  = $item->times;

                if(is_null($task_general)){
                    $task_list[$key]['cust_count']  = 0;
                }else{
                    $task_list[$key]['cust_count']  = $task_general->custs;
                }
            }
        }

        $rs['month_info'] = $month_info;
        $rs['task_list'] = $task_list;

        return $this->apiReturn(402, "我的巡店任务",$rs);

    }

}
