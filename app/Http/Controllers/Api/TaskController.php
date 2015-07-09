<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

        //记录一笔打卡资料
        $task_sign = new TaskSign();
        $task_sign->type = Input::get('type');
        $task_sign->user_id = Auth::user()->id;
        $task_sign->date = date('Y-m-d', time());
        $task_sign->save();

        //新增，修改考勤统计表
        $attn_info = Attn::where('user_id', Auth::user()->id)
            ->where('ymonth', date('Ym', time()))
            ->where('day', date('d', time()))
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
            $attn->ymonth = date('Ym', time());
            $attn->day = date('d', time());
            $attn->weekday = date('w', time());

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
        $task_log->visit_time = date('Y-m-d H:i:s', time());
        $task_log->save();

        if ($task_log->task) {
            unset($task_log->task);
        }


        return $this->apiReturn(200, '打卡成功', $task_log);

    }

    /**
     * 巡查门店列表
     */
    public function getAssignCustList()
    {


    }


    /**
     * 巡查门店签到
     */
    public function postAssignCustSign(Request $request)
    {

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
            ->where('ymonth', date('Ym', time()))
            ->where('day', date('d', time()))
            ->where('is_sign_on', 1)
            ->first();

        if (is_null($attn_info)) {
            return $this->apiReturn(402, "还未操作上班打卡，请先上班打卡");
        }

        //客户信息
        $cust_info = Cust::find(Input::get('cust_id'));

        //新增外勤记录
        $task_log = new TaskLog();
        $task_log->user_id = Auth::user()->id;
        $task_log->lng = Input::get('lng');
        $task_log->lat = Input::get('lat');
        $task_log->location = Input::get('location');
        //$task_log->task()->associate($task_sign);
        $task_log->title = "巡查".$cust_info->name;
        $task_log->mileage = Input::get('mileage');
        $task_log->visit_time = date('Y-m-d H:i:s', time());
        $task_log->save();


        //更新统计表
        $attn_info->increment('mileage',Input::get('mileage'));
        $attn_info->increment('visited_custs',1);

        return $this->apiReturn(402, "巡店签到成功",$attn_info);

    }


}
