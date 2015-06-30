<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // 开始提醒
        $this->command->info('seed running!');

        // 省市区
        $this->call('RegionTableSeeder');
        // 角色
        $this->call('RoleTableSeeder');
        // 用户
        $this->call('UserTableSeeder');
        // 机构
        $this->call('OrgTableSeeder');
        // 部门
        $this->call('DeptTableSeeder');
        // 客户等级
        $this->call('CustLevelTableSeeder');
        // 客户
        $this->call('CustTableSeeder');
        // 意见反馈
        $this->call('FeedbackTableSeeder');
        // 请假类型
        $this->call('LeaveTypeTableSeeder');


        // 结束提醒
        $this->command->info('seed end!');

        Model::reguard();
    }
}
