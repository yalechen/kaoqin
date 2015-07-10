<?php
use Illuminate\Database\Seeder;
use App\Models\Purview;

class PurviewTableSeeder extends Seeder
{

    /**
     * 权限表填充
     */
    public function run()
    {
        // 清空表数据
        Purview::truncate();

        // 菜单权限填充数据，默认是type=Mennu，status=On
        $data = array(
            array(
                'name' => '后台系统设置',
                'key' => 'SystemModule',
                'useon' => Purview::USEON_PC,
                'sub_categorys' => array()
            ),
            array(
                'name' => '后台组织架构',
                'key' => 'OrgDeptModule',
                'useon' => Purview::USEON_PC,
                'sub_categorys' => array()
            ),
            array(
                'name' => '后台CRM管理',
                'key' => 'CRMModule',
                'useon' => Purview::USEON_PC,
                'sub_categorys' => array()
            ),
            array(
                'name' => '后台考勤管理',
                'key' => 'AttnModule',
                'useon' => Purview::USEON_PC,
                'sub_categorys' => array(
                    array(
                        'name' => '请假管理',
                        'key' => 'LevelModule',
                        'useon' => Purview::USEON_PC,
                        'sub_categorys' => array()
                    )
                )
            ),
            array(
                'name' => '后台任务管理',
                'key' => 'TaskModule',
                'useon' => Purview::USEON_PC,
                'sub_categorys' => array()
            ),
            array(
                'name' => '后台报表统计',
                'key' => 'ReportModule',
                'useon' => Purview::USEON_PC,
                'sub_categorys' => array(
                    array(
                        'name' => '任务报表',
                        'key' => 'TaskReportModule',
                        'useon' => Purview::USEON_PC,
                        'sub_categorys' => array()
                    ),
                    array(
                        'name' => '考勤报表',
                        'key' => 'AttnReportModule',
                        'useon' => Purview::USEON_PC,
                        'sub_categorys' => array()
                    )
                )
            ),
            array(
                'name' => 'APP权限模块',
                'key' => 'AppModule',
                'useon' => Purview::USEON_APP,
                'sub_categorys' => array()
            )
        );

        // 添加菜单权限
        $this->addPurviews($data);
    }

    protected function addPurviews($data, $parent_id = 0)
    {
        foreach ($data as $item) {
            // 添加菜单权限
            $purview = new Purview();
            $purview->name = $item['name'];
            $purview->key = $item['key'];
            $purview->parent_id = $parent_id;
            $purview->type = Purview::TYPE_MENU;
            $purview->status = Purview::STATUS_ON;
            $purview->useon = $item['useon'];
            $purview->save();

            $this->addPurviews($item['sub_categorys'], $purview->id);
        }
    }
}
