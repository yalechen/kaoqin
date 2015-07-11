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


class TaskController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 上下班打卡
     */
    public function postTaskSign(Request $request)
    {

        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:On,Off',
            'lat' => 'required',
            'lng' => 'required',
            'location' => 'required',
        ], [
            'type.in' => '类型只能为On或Off',
            'lat.required' => '经度不能为空',
            'lng.required' => '纬度不能为空',
            'location.required' => '当前位置不能为空',
        ]);

        if ($validator->fails()) {
            // 验证失败，返回错误信息。
            return $this->apiReturn(402, $validator->messages()->first());
        }

        //判断是否打过卡
        $info = TaskSign::where('user_id', Auth::user()->id)
            ->where('date', date('Y-m-d', time()))
            ->where('type', Input::get('type'))
            ->first();

        if (!is_null($info)) {
            // 已打过卡
            return $this->apiReturn(402, '已打过卡，无需重复操作');
        }

        //查询巡店门店
        $cust_ids = TaskCust::where('user_id',Auth::user()->id)
            ->where('ymonth',date('Y-m',time()))
            ->lists('cust_id');

        $can_sign = "N";

        //数组有值
        if(count($cust_ids)){
            foreach($cust_ids as $key => $id){
                $cust = Cust::find($id);
                if($cust->district){
                    //app上报的位置有包含门店地区，则可以打卡，退出循环
                    if( strpos(Input::get('location'),$cust->district->name)  !== false ){
                        $can_sign = "Y";
                        break;
                    }
                }else{
                    continue;
                }
            }
        }

        if($can_sign == 'N'){
            return $this->apiReturn(402, '还未到达指定区域，无法进行打卡');
        }


        //记录一笔打卡资料
        $task_sign = new TaskSign();
        $task_sign->type = Input::get('type');
        $task_sign->user_id = Auth::user()->id;
        $task_sign->date = date('Y-m-d', time());
        $task_sign->save();

        //新增，修改考勤统计表
        $attn_info = Attn::where('user_id', Auth::user()->id)
            ->where('date', date('Y-m-d', time()))
            ->first();

        if (!is_null($attn_info)) {
            if (Input::get('type') == TaskSign::TYPE_ON) {
                $attn_info->is_sign_on = 1;
            } else {
                $attn_info->is_sign_off = 1;

                //考勤记录正常，修改状态为为OK
                if ($attn_info->is_sign_on == 1 && $attn_info->is_late == 0
                    && $attn_info->is_vacation == 0 && $attn_info->is_leave == 0
                    && $attn_info->is_overtime == 0
                ) {
                    $attn_info->status = 'Ok';
                }
            }
            $attn_info->save();
        } else {
            $attn = new Attn();
            $attn->user_id = Auth::user()->id;
            $attn->date = date('Y-m-d', time());
            $attn->weekday = date('w', time());
            $attn->day = date('d', time());
            $attn->ymonth = date('Y-m', time());

            if (Input::get('type') == TaskSign::TYPE_ON) {
                $attn->is_sign_on = 1;
            } else {
                $attn->is_sign_off = 1;
            }

            $attn->save();
        }

        //新增外勤记录
        $task_log = new TaskLog();
        $task_log->user_id = Auth::user()->id;
        $task_log->lng = Input::get('lng');
        $task_log->lat = Input::get('lat');
        $task_log->location = Input::get('location');
        $task_log->task()->associate($task_sign);
        if (Input::get('type') == TaskSign::TYPE_ON) {
            $task_log->title = "上班打卡";
        } else {
            $task_log->title = "下班打卡";
        }
        $task_log->visit_date = date('Y-m-d', time());
        $task_log->save();

        if ($task_log->task) {
            unset($task_log->task);
        }

        DB::commit();


        return $this->apiReturn(200, '打卡成功', $task_log);

    }

    /**
     * 巡查门店列表
     */
    public function getAssignCustList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
        ], [
            'lng.required' => '经度不能为空',
            'lat.required' => '纬度不能为空',
        ]);

        if ($validator->fails()) {
            // 验证失败，返回错误信息。
            return $this->apiReturn(402, $validator->messages()->first());
        }

        //员工该月巡店门店数组
        $task_cust_list = TaskCust::where('user_id',Auth::user()->id)
            ->where('ymonth',date('Y-m',time()))
            ->lists('cust_id');

        //签到位置距离
        $sign_cust_range = config('base_info.sign_cust_range',0);

        //过滤当前位置能签到的门店列表
        $cust_ids = [];
        $cust_list = [];

        //按照经纬度查询
        foreach($task_cust_list as $key => $id){
            $cust = Cust::find($id);
            if($cust->lat && $cust->lng){
                $distinct = $this->getDistance($cust->lat,$cust->lng,Input::get('lat'),Input::get('lng'));
                //后台设置签到距离范围内，显示门店
                if($sign_cust_range >= $distinct  ){
                    $cust_ids[] = $id;
                }
            }
        }


        if(Input::has('name')){
            //按照门店名称查询
            $cust_ids = TaskCust::where('user_id',Auth::user()->id)
                ->where('ymonth',date('Y-m',time()))
                ->whereHas('cust',function($q){
                    $q->where('name','like','%'.Input::get('name').'%');
                })
                ->lists('cust_id');
        }

        //门店等级搜索
        if(Input::has('level_id')){
            //按照门店名称查询
            $cust_ids = TaskCust::where('user_id',Auth::user()->id)
                ->where('ymonth',date('Y-m',time()))
                ->where('cust_level_id',Input::get('level_id'))
                ->lists('cust_id');
        }


        if(count($cust_ids)){
            foreach($cust_ids as $key => $id){
                $cust = Cust::find($id);
                $cust_list[$key]['id'] = $cust->id;
                $cust_list[$key]['name'] = $cust->name;
                $cust_list[$key]['number'] = $cust->number;
                $cust_list[$key]['lat'] = $cust->lat;
                $cust_list[$key]['lng'] = $cust->lng;
                $cust_list[$key]['cust_level_id'] = $cust->custLevel->id;
                $cust_list[$key]['cust_level_name'] = $cust->custLevel->name;
                $cust_list[$key]['sign_status'] = $cust->CustSignStatus;
                $cust_list[$key]['province'] = $cust->province ? $cust->province->name : "";
                $cust_list[$key]['city'] = $cust->city ? $cust->city->name : "";
                $cust_list[$key]['district'] = $cust->district ? $cust->district->name : "";
                $cust_list[$key]['address'] = $cust->address;
            }
        }

        return $this->apiReturn(200, '获取门店成功', $cust_list);

    }




    /**
     * 巡查门店签到
     */
    public function postAssignCustSign(Request $request)
    {

        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'cust_id' => 'required|exists:custs,id',
            'mileage' => 'required',
            'location' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ], [
            'cust_id.required' => '签到客户id不能为空',
            'cust_id.exists' => '签到客户id不存在',
            'mileage.required' => '距上次签到的里程不能为空',
            'location.required' => '当前位置不能为空',
            'lat.required' => '经度不能为空',
            'lng.required' => '纬度不能为空',
        ]);

        if ($validator->fails()) {
            // 验证失败，返回错误信息。
            return $this->apiReturn(402, $validator->messages()->first());
        }

        //判断是否有上班打卡
        $attn_info = Attn::where('user_id', Auth::user()->id)
            ->where('date', date('Y-m-d', time()))
            ->where('is_sign_on', 1)
            ->first();

        if (is_null($attn_info)) {
            return $this->apiReturn(402, "还未操作上班打卡，请先上班打卡");
        }

        //判断此门店是否已巡
        $cust = Cust::find(Input::get('cust_id'));
        if($cust->CustSignStatus == "Y"){
            return $this->apiReturn(402, "此门店今日已巡");
        }

        //客户信息
        $cust_info = Cust::find(Input::get('cust_id'));

        //修改常规任务巡店次数
        $task_general = TaskGeneral::where('cust_level_id',$cust_info->cust_level_id)
                                    ->where('ymonth',date('Y-m',time()))
                                    ->where('accept_user_id',Auth::user()->id)
                                    ->first();

        $task_general->increment('visited_times',1);


        //修改常规任务详情巡店次数
        $task_cust = TaskCust::where('ymonth',date('Y-m',time()))
                            ->where('user_id',Auth::user()->id)
                            ->where('cust_id',Input::get('cust_id'))
                            ->first();

        $task_cust->increment('visited_times',1);

        //新增外勤记录
        $task_log = new TaskLog();
        $task_log->user_id = Auth::user()->id;
        $task_log->cust_id = Input::get('cust_id');
        $task_log->lng = Input::get('lng');
        $task_log->lat = Input::get('lat');
        $task_log->location = Input::get('location');
        $task_log->task()->associate($task_general);
        $task_log->title = "巡查".$cust_info->name;
        $task_log->mileage = Input::get('mileage');
        $task_log->visit_date = date('Y-m-d', time());
        $task_log->save();


        //更新统计表
        $attn_info->increment('mileage',Input::get('mileage'));
        $attn_info->increment('visited_custs',1);

        DB::commit();

        if ($task_log->task) {
            unset($task_log->task);
        }

        return $this->apiReturn(402, "巡店签到成功",$task_log);

    }

    /**
     * 外勤日志首页
     */
    public function getTaskLogIndex()
    {
        $sum_need_visit_times =  TaskGeneral::where('accept_user_id',Auth::user()->id)
            ->where('ymonth',date('Y-m',time()))
            ->sum('times');

        $sum_visited_times =  TaskGeneral::where('accept_user_id',Auth::user()->id)
            ->where('ymonth',date('Y-m',time()))
            ->sum('visited_times');


        if($sum_need_visit_times <= 0 ){
            $complete_ratio = 0;
        }else{
            //完成率
            $complete_ratio = ($sum_visited_times / $sum_need_visit_times) * 100  ;
        }

        //当日统计信息
        $today_info = [];

        //本月统计信息
        $month_info = [];

        //月总里程
        $month_mileage = Attn::where('user_id',Auth::user()->id)
            ->where('ymonth',date('Y-m',time()))
            ->sum('mileage');

        $month_info['complete_ratio'] = round($complete_ratio,2)."%";
        $month_info['month_mileage'] = $month_mileage;


        //今日签到统计信息
        $attn = Attn::where('user_id',Auth::user()->id)
                ->where('date',date('Y-m-d',time()))->first();


        if(! is_null($attn)){
            $today_info['date'] = $attn->date;
            $today_info['mileage'] = $attn->mileage;
            $today_info['visited_custs'] = $attn->visited_custs;
        }

        //签到记录
        $task_logs = $this->_getTaskLogs(date('Y-m-d',time()), 'desc');

        $rs['month_info'] = $month_info;
        $rs['today_info'] = $today_info;
        $rs['log_list'] = $task_logs;

        return $this->apiReturn(402, "外勤日志首页信息",$rs);

    }


    /**
     * 外勤日志列表
     */
    public function getTaskLogList()
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

        $list = [];

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

        return $this->apiReturn(402, "外勤日志列表",$list,$count);
    }


    /**
     * 外勤日志详情
     */
    public function getTaskLogDetail()
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
        $task_logs = $this->_getTaskLogs(Input::get('visit_date'));

        $rs['info'] = $info;
        $rs['log_list'] = $task_logs;

        return $this->apiReturn(402, "外勤日志详情",$rs);

    }

    /**
     * 获取今天签到记录
     */
    public function _getTaskLogs($date , $orderBy = "asc"){

        if($orderBy == "desc"){
            $orderBy = "desc";
        }
        $logs = TaskLog::where('user_id',Auth::user()->id)->where('visit_date',$date)->orderBy('id',$orderBy)->get();

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
    public function getTaskVisit()
    {
        $sum_need_visit_times =  TaskGeneral::where('accept_user_id',Auth::user()->id)
            ->where('ymonth',date('Y-m',time()))
            ->sum('times');

        $sum_visited_times =  TaskGeneral::where('accept_user_id',Auth::user()->id)
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
                $task_general = TaskGeneral::where('cust_level_id',$item->id)
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
