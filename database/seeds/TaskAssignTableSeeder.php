<?php
use Illuminate\Database\Seeder;
use App\Models\TaskAssign;
use App\Models\TaskAssignCust;
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
        TaskAssignCust::truncate();
        TaskLog::truncate();

        // 给每个用户添加1-3个临时任务
        $users = User::all();
        foreach ($users as $user) {
            $num = mt_rand(1, 3);
            for ($i = 0; $i <= $num; $i ++) {
                $task_assign = new TaskAssign();
                $task_assign->ymonth = date('Y-m');
                $task_assign->title = '[' . date('Y-m') . ']' . $user->realname . '临时拜访任务';
                $task_assign->acceptUser()->associate($user);
                if ($user->leader) {
                    $task_assign->publishUser()->associate($user->leader);
                } else {
                    $task_assign->publishUser()->associate(User::find(1));
                }
                $task_assign->remark = '';
                $task_assign->start_time = '2015-07-13 09:10:00';
                $task_assign->end_time = '2015-07-14 18:10:00';
                $task_assign->times = mt_rand(1, 3);
                $user->image1_path = '/upload_files/init/fa24a612110135f42e8a3b1ec5db9239.png';
                $task_assign->save();

                // 门店拜访明细
                $all_times = 0;
                for ($j = 0; $j <= 3; $j ++) {
                    // 拜访次数和总拜访次数
                    $times = mt_rand(1, 3);
                    $all_times += $times;
                    $task_assign_cust = new TaskAssignCust();
                    $task_assign_cust->task()->associate($task_assign);
                    $task_assign_cust->cust();
                    $task_assign_cust->times = $times;
                    $task_assign_cust->save();
                }
                $task_assign->times = $all_times;
                $task_assign->save();
            }
        }
    }
}
