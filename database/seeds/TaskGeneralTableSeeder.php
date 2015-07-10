<?php
use Illuminate\Database\Seeder;
use App\Models\TaskGeneral;
use App\Models\User;
use App\Models\CustLevel;
use App\Models\Cust;
use App\Models\TaskCust;

class TaskGeneralTableSeeder extends Seeder
{

    /**
     * 常规任务表填充
     */
    public function run()
    {
        // 清空表数据
        TaskGeneral::truncate();
        TaskCust::truncate();

        // 常规任务表通过每天自动根据门店等级来分配
        $cust_level = CustLevel::all();
        foreach ($cust_level as $item) {
            $custs = $item->custs;
            foreach ($custs as $cust) {
                // 生成本月和下个月的用户的常规任务
                if (! is_null($cust->user)) {
                    // 得到此人此等级的总门店数和总拜访次数
                    $sum_custs = Cust::whereUserId($cust->user_id)->whereCustLevelId($item->id)->count();
                    $sum_times = $sum_custs * $item->times;

                    // 生成本月
                    $task_general = TaskGeneral::whereAcceptUserId($cust->user_id)->whereCustLevelId($item->id)
                        ->whereYmonth(date('Y-m'))
                        ->first();
                    if (is_null($task_general)) {
                        $task_general = new TaskGeneral();
                        $task_general->title = '[' . date('Y-m') . ']' . $cust->user->realname . '常规拜访' . $item->name . '级门店';
                        $task_general->ymonth = date('Y-m');
                        $task_general->acceptUser()->associate($cust->user);
                        if ($cust->user->leader) {
                            $task_general->publishUser()->associate($cust->user->leader);
                        } else {
                            $task_general->publishUser()->associate(User::find(1));
                        }
                        $task_general->remark = '';
                        $task_general->custLevel()->associate($item);
                        $task_general->custs = $sum_custs;
                        $task_general->times = $sum_times;
                        $task_general->times_remark = times_remark($sum_times);
                        $task_general->save();
                    }
                    // 拜访明细
                    $task_cust = new TaskCust();
                    $task_cust->task()->associate($task_general);
                    $task_cust->ymonth = date('Y-m');
                    $task_cust->user()->associate($cust->user);
                    $task_cust->cust()->associate($cust);
                    $task_cust->custLevel()->associate($item);
                    $task_cust->times = $item->times;
                    $task_cust->save();

                    // 生成下个月
                    $task_general = TaskGeneral::whereAcceptUserId($cust->user_id)->whereCustLevelId($item->id)
                        ->whereYmonth(date('Y-m', strtotime('+1 month')))
                        ->first();
                    if (is_null($task_general)) {
                        $task_general = new TaskGeneral();
                        $task_general->title = '[' . date('Y-m', strtotime('+1 month')) . ']' . $cust->user->realname . '常规拜访' . $item->name . '级门店';
                        $task_general->ymonth = date('Y-m', strtotime('+1 month'));
                        $task_general->acceptUser()->associate($cust->user);
                        if ($cust->user->leader) {
                            $task_general->publishUser()->associate($cust->user->leader);
                        } else {
                            $task_general->publishUser()->associate(User::find(1));
                        }
                        $task_general->remark = '';
                        $task_general->custLevel()->associate($item);
                        $task_general->custs = $sum_custs;
                        $task_general->times = $sum_times;
                        $task_general->times_remark = times_remark($sum_times);
                        $task_general->save();
                    }
                    // 拜访明细
                    $task_cust = new TaskCust();
                    $task_cust->task()->associate($task_general);
                    $task_cust->ymonth = date('Y-m', strtotime('+1 month'));
                    $task_cust->user()->associate($cust->user);
                    $task_cust->cust()->associate($cust);
                    $task_cust->custLevel()->associate($item);
                    $task_cust->times = $item->times;
                    $task_cust->save();
                }
            }
        }
    }
}
