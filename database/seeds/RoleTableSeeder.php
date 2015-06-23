<?php
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleTableSeeder extends Seeder
{

    public function run()
    {
        // 清空表数据
        Role::truncate();

        // 填充总管理员角色
        $role = new Role();
        $role->key = 'super_admin';
        $role->name = '超级管理员';
        $role->remark = '系统后台管理员，万能的天神';
        $role->status = Role::STATUS_ON;
        $role->save();

        // 填充普通业务员角色
        $role = new Role();
        $role->key = 'salesman';
        $role->name = '外勤业务员';
        $role->remark = '勤劳的苦逼的业务员';
        $role->status = Role::STATUS_ON;
        $role->save();
    }
}