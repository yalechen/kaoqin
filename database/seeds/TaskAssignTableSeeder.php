<?php
use Illuminate\Database\Seeder;
use App\Models\TaskAssign;
use App\Models\User;
use App\Models\Cust;
use App\Models\TaskLog;

class TaskAssignTableSeeder extends Seeder
{

    /**
     * 临时任务表填充
     */
    public function run()
    {
        // 清空表数据
        TaskAssign::truncate();
        TaskLog::truncate();

        // 给每个用户添加1-3个临时任务
        $users = User::all();
        foreach ($users as $user) {
            $num = mt_rand(1, 3);
            for ($i = 0; $i <= $num; $i ++) {
                $task_assign = new TaskAssign();
                $task_assign->ymonth='2015-07';
                $task_assign->title = '[' . date('Y-m') . ']' . $user->realname . '临时拜访任务';
                $task_assign->acceptUser()->associate($user);
                if ($user->leader) {
                    $task_assign->publishUser()->associate($user->leader);
                } else {
                    $task_assign->publishUser()->associate(User::find(1));
                }
                $task_assign->remark = '';
                $task_assign->start_time ='2015-07-13 09:10:00';
                $task_assign->end_time = '2015-07-14 18:10:00';
                $task_assign->times = mt_rand(1, 3);
                $task_assign->save();

                // 随机找1-3家门店
                $cust_num = mt_rand(1, 3);
                for ($j = 0; $j <= $cust_num; $j ++) {
                    $cust = Cust::find(mt_rand(1, 20));
                    $task_log = new TaskLog();
                    $task_log->user()->associate($user);
                    $task_log->task()->associate($task_assign);
                    $task_log->cust()->associate($cust);
                    $task_log->remark = '临时指定任务-拜访' . $cust->name . '门店';
                    $task_log->save();
                }
            }
        }
    }
}
