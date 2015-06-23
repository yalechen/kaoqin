<?php
use Illuminate\Database\Seeder;
use App\Models\Dept;
use App\Models\Org;

class DeptTableSeeder extends Seeder
{

    /**
     * 部门表填充
     */
    public function run()
    {
        // 清空表数据
        Dept::truncate();

        $names = [
            '综合部',
            '发展战略部',
            '法律事务部',
            '计划建设部',
            '财务部',
            '人力资源部',
            '市场经营部',
            '数据部',
            '网络部',
            '技术部',
            '业务支撑系统部',
            '对外投资管理部',
            '内审部',
            '党群工作部',
            '纪检组监察室',
            '集团工会'
        ];

        // 给四川移动添加部门
        foreach ($names as $name) {
            $dept = new Dept();
            $dept->name = $name;
            $dept->org()->associate(Org::find(mt_rand(2, 11)));
            $dept->save();
        }

        // 给福建移动添加部门
        foreach ($names as $name) {
            $dept = new Dept();
            $dept->name = $name;
            $dept->org()->associate(Org::find(mt_rand(13, 21)));
            $dept->save();
        }
    }
}
