<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Request as I;
use App\Models\City;
use App\Models\District;
use App\Models\Overtime;
use Auth;
use View;
use App\Models\TaskGeneral;
use App\Models\TaskAssign;

class BaseController extends Controller
{

    protected $wait_audit_overtime_nums;

    protected $unfinish_task_general_nums;

    protected $unfinish_task_assign_nums;

    public function __construct()
    {
        parent::__construct();

        // 加班审核有多少个是待审的,常规任务本月有几个是未完成的,临时任务本月有几个是未完成的
        $this->wait_audit_overtime_nums = 0;
        if (Auth::check()) {
            $this->wait_audit_overtime_nums = Overtime::whereAuditUserId(Auth::user()->id)->whereStatus(Overtime::STATUS_WAIT)->count();
            $this->unfinish_task_general_nums = TaskGeneral::whereAcceptUserId(Auth::user()->id)->whereStatus(TaskGeneral::STATUS_STARTING)
                ->whereYmonth(date('Y-m'))
                ->count();
            $this->unfinish_task_assign_nums = TaskAssign::whereAcceptUserId(Auth::user()->id)->whereStatus(TaskGeneral::STATUS_STARTING)
                ->whereYmonth(date('Y-m'))
                ->count();
        }
        View::share('wait_audit_overtime_nums', $this->wait_audit_overtime_nums);
        View::share('unfinish_task_general_nums', $this->unfinish_task_general_nums);
        View::share('unfinish_task_assign_nums', $this->unfinish_task_assign_nums);
    }

    /**
     * 获取某省份下城市列表
     */
    public function getCity()
    {
        return City::whereProvinceId(I::get("province_id", '0'))->get();
    }

    /**
     * 获取某城市下区县列表
     */
    public function getDistrict()
    {
        return District::whereCityId(I::get("city_id", '0'))->get();
    }
}