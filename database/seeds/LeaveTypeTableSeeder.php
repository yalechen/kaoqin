<?php
use Illuminate\Database\Seeder;
use App\Models\LeaveType;

class LeaveTypeTableSeeder extends Seeder
{

    /**
     * 请假类型表填充
     */
    public function run()
    {
        // 清空表数据
        LeaveType::truncate();

        $names = [
            '事假',
            '病假',
            '产假',
            '婚假',
            '工商假',
            '年休假',
            '丧假',
            '公假'
        ];
        foreach ($names as $name) {
            $leave_type = new LeaveType();
            $leave_type->name = $name;
            $leave_type->save();
        }
    }
}
