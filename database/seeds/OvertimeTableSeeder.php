<?php
use Illuminate\Database\Seeder;
use App\Models\Overtime;
use App\Models\User;

class OvertimeTableSeeder extends Seeder
{

    /**
     * 加班表填充
     */
    public function run()
    {
        // 清空表数据
        Overtime::truncate();

        // 每个用户新增一笔加班记录
        $datas = array(
            array(
                'start_time' => '2015-06-06 18:00',
                'end_time' => '2015-06-06 20:00',
                'hours' => 2,
                'reason' => '加班理由很长很长，无法诉说呀。'
            ),
            array(
                'start_time' => '2015-06-06 19:00',
                'end_time' => '2015-06-06 22:00',
                'hours' => 3,
                'reason' => '别人都没走，我怎么好意思走？'
            ),
            array(
                'start_time' => '2015-06-08 18:00',
                'end_time' => '2015-06-08 21:00',
                'hours' => 3,
                'reason' => '事前计划不够，往往在急需某一项工作时发现自己还没有做完，于是只好加班'
            ),
            array(
                'start_time' => '2015-06-06 18:00',
                'end_time' => '2015-06-06 20:00',
                'hours' => 2,
                'reason' => '上班时不能把该做的工作做完'
            ),
            array(
                'start_time' => '2015-06-13 10:00',
                'end_time' => '2015-06-13 19:00',
                'hours' => 7,
                'reason' => '喜欢自己的工作，愿意为此多付出'
            ),
            array(
                'start_time' => '2015-06-15 19:00',
                'end_time' => '2015-06-15 20:00',
                'hours' => 1,
                'reason' => '博得老板和上司的好感'
            ),
            array(
                'start_time' => '2015-06-09 19:00',
                'end_time' => '2015-06-09 22:00',
                'hours' => 3,
                'reason' => '打酱油混时间'
            ),
            array(
                'start_time' => '2015-06-12 19:00',
                'end_time' => '2015-06-12 21:00',
                'hours' => 2,
                'reason' => '突然临时任务量增多，无法不加班完成'
            ),
            array(
                'start_time' => '2015-06-13 09:00',
                'end_time' => '2015-06-13 18:00',
                'hours' => 8,
                'reason' => '一个技术问题没解决掉，明天无法上线运行'
            ),
            array(
                'start_time' => '2015-06-14 10:00',
                'end_time' => '2015-06-14 17:00',
                'hours' => 6,
                'reason' => '学习新的技术'
            ),
            array(
                'start_time' => '2015-06-12 18:00',
                'end_time' => '2015-06-12 23:00',
                'hours' => 5,
                'reason' => '查找系统漏洞和bug，生命不息，bug不止'
            ),
            array(
                'start_time' => '2015-06-22 18:00',
                'end_time' => '2015-06-22 22:00',
                'hours' => 4,
                'reason' => '没有充分的理由，只想呆在公司不想回去'
            ),
            array(
                'start_time' => '2015-06-17 19:00',
                'end_time' => '2015-06-17 21:00',
                'hours' => 2,
                'reason' => '因为我没有男朋友，老板安排我来加班'
            ),
            array(
                'start_time' => '2015-06-16 19:00',
                'end_time' => '2015-06-16 22:00',
                'hours' => 3,
                'reason' => '为了赶工，为了多赚钱养家，拼了'
            ),
            array(
                'start_time' => '2015-06-18 18:00',
                'end_time' => '2015-06-18 19:00',
                'hours' => 1,
                'reason' => '钱少，只好加班赚点'
            ),
            array(
                'start_time' => '2015-06-19 19:00',
                'end_time' => '2015-06-19 23:00',
                'hours' => 4,
                'reason' => '为了和ta一起加班'
            ),
            array(
                'start_time' => '2015-06-23 18:00',
                'end_time' => '2015-06-23 20:00',
                'hours' => 2,
                'reason' => '奇葩理由，还是不说好了'
            ),
            array(
                'start_time' => '2015-06-20 09:00',
                'end_time' => '2015-06-20 18:00',
                'hours' => 8,
                'reason' => '做技术加班是常态，已经习惯了'
            ),
            array(
                'start_time' => '2015-06-20 09:00',
                'end_time' => '2015-06-20 16:00',
                'hours' => 6,
                'reason' => '为了明天的合同，生死书啊，没办法'
            ),
            array(
                'start_time' => '2015-06-24 18:00',
                'end_time' => '2015-06-24 19:00',
                'hours' => 1,
                'reason' => '最后一个理由，就是没有理由'
            )
        );
        // 添加商户等级
        foreach ($datas as $key => $item) {
            $user = User::find($key + 3);
            if (! is_null($user)) {
                $overtime = new Overtime();
                $overtime->start_time = $item['start_time'];
                $overtime->end_time = $item['end_time'];
                $overtime->hours = $item['hours'];
                $overtime->reason = $item['reason'];
                $overtime->user()->associate($user);
                if (! is_null($user->leader)) {
                    $overtime->auditUser()->associate($user->leader);
                }
                $overtime->save();
            }
        }
    }
}
