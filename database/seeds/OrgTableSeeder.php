<?php
use Illuminate\Database\Seeder;
use App\Models\Org;

class OrgTableSeeder extends Seeder
{

    /**
     * 机构表填充
     */
    public function run()
    {
        // 清空表数据
        Org::truncate();

        // 创建机构数据
        $data = array(
            array(
                'name' => '四川移动',
                'number' => 'JG00001',
                'sub_org' => array(
                    array(
                        'name' => '成都移动',
                        'number' => 'JG00001-1',
                        'sub_org' => array(
                            array(
                                'name' => '成华区',
                                'number' => 'JG00001-1-1',
                                'sub_org' => array()
                            ),
                            array(
                                'name' => '武侯区',
                                'number' => 'JG00001-1-2',
                                'sub_org' => array()
                            ),
                            array(
                                'name' => '青羊区',
                                'number' => 'JG00001-1-3',
                                'sub_org' => array()
                            ),
                            array(
                                'name' => '锦江区',
                                'number' => 'JG00001-1-4',
                                'sub_org' => array()
                            ),
                            array(
                                'name' => '金牛区',
                                'number' => 'JG00001-1-5',
                                'sub_org' => array()
                            )
                        )
                    ),
                    array(
                        'name' => '德阳移动',
                        'number' => 'JG00001-2',
                        'sub_org' => array()
                    ),
                    array(
                        'name' => '乐山移动',
                        'number' => 'JG00001-3',
                        'sub_org' => array()
                    ),
                    array(
                        'name' => '宜宾移动',
                        'number' => 'JG00001-4',
                        'sub_org' => array()
                    ),
                    array(
                        'name' => '广安移动',
                        'number' => 'JG00001-5',
                        'sub_org' => array()
                    )
                )
            ),
            array(
                'name' => '福建移动',
                'number' => 'JG00002',
                'sub_org' => array(
                    array(
                        'name' => '福州移动',
                        'number' => 'JG00002-1',
                        'sub_org' => array()
                    ),
                    array(
                        'name' => '厦门移动',
                        'number' => 'JG00002-2',
                        'sub_org' => array(
                            array(
                                'name' => '思明移动',
                                'number' => 'JG00002-2-1',
                                'sub_org' => array()
                            ),
                            array(
                                'name' => '湖里移动',
                                'number' => 'JG00002-2-2',
                                'sub_org' => array()
                            ),
                            array(
                                'name' => '集美移动',
                                'number' => 'JG00002-2-3',
                                'sub_org' => array()
                            ),
                            array(
                                'name' => '海沧移动',
                                'number' => 'JG00002-2-4',
                                'sub_org' => array()
                            ),
                            array(
                                'name' => '翔安移动',
                                'number' => 'JG00002-2-5',
                                'sub_org' => array()
                            )
                        )
                    ),
                    array(
                        'name' => '泉州移动',
                        'number' => 'JG00002-3',
                        'sub_org' => array()
                    ),
                    array(
                        'name' => '漳州移动',
                        'number' => 'JG00002-4',
                        'sub_org' => array()
                    )
                )
            ),
            array(
                'name' => '浙江移动',
                'number' => 'JG00003',
                'sub_org' => array(
                    array(
                        'name' => '杭州移动',
                        'number' => 'JG00003-1',
                        'sub_org' => array()
                    ),
                    array(
                        'name' => '温州移动',
                        'number' => 'JG00003-2',
                        'sub_org' => array()
                    ),
                    array(
                        'name' => '绍兴移动',
                        'number' => 'JG00003-3',
                        'sub_org' => array()
                    ),
                    array(
                        'name' => '宁波移动',
                        'number' => 'JG00003-4',
                        'sub_org' => array()
                    )
                )
            )
        );

        // 添加商品分类列表
        $this->addOrg($data);
    }

    protected function addOrg($data, $parent_id = 0)
    {
        foreach ($data as $item) {
            $org = new Org();
            $org->number = $item['number'];
            $org->name = $item['name'];
            $org->parent_id = $parent_id;
            $org->save();

            $this->addOrg($item['sub_org'], $org->id);
        }
    }
}
