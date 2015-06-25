<?php
use Illuminate\Database\Seeder;
use App\Models\CustLevel;
use App\Models\Cust;
use App\Models\User;

class CustTableSeeder extends Seeder
{

    /**
     * 商户表填充
     */
    public function run()
    {
        // 清空表数据
        Cust::truncate();

        // 创建20个商户数据
        $datas = array(
            array(
                'name' => '枋湖路营业厅',
                'number' => 'SH00001',
                'type' => Cust::TYPE_GROUP,
                'contacts' => '赵长斌',
                'mobile' => '13739711688'
            ),
            array(
                'name' => '瑞景营业厅',
                'number' => 'SH00002',
                'type' => Cust::TYPE_GROUP,
                'contacts' => '郝娇玲',
                'mobile' => '13292279696'
            ),
            array(
                'name' => '观音山营业厅',
                'number' => 'SH00003',
                'type' => Cust::TYPE_STORE,
                'contacts' => '李恩勇',
                'mobile' => '13930752757'
            ),
            array(
                'name' => '蔡塘营业厅',
                'number' => 'SH00004',
                'type' => Cust::TYPE_STORE,
                'contacts' => '潘岳',
                'mobile' => '15133160753'
            ),
            array(
                'name' => '湖里万达营业厅',
                'number' => 'SH00005',
                'type' => Cust::TYPE_STORE,
                'contacts' => '王付荣',
                'mobile' => '13180463510'
            ),
            array(
                'name' => '集美万达营业厅',
                'number' => 'SH00006',
                'type' => Cust::TYPE_STORE,
                'contacts' => '李会泽',
                'mobile' => '15646857931'
            ),
            array(
                'name' => '鼓浪屿营业厅',
                'number' => 'SH00007',
                'type' => Cust::TYPE_STORE,
                'contacts' => '杨占魁',
                'mobile' => '15613869665'
            ),
            array(
                'name' => '中山街营业厅',
                'number' => 'SH00008',
                'type' => Cust::TYPE_GROUP,
                'contacts' => '叶万春',
                'mobile' => '15284227770'
            ),
            array(
                'name' => '中华城营业厅',
                'number' => 'SH00009',
                'type' => Cust::TYPE_STORE,
                'contacts' => '黄海棠 ',
                'mobile' => '15933381372'
            ),
            array(
                'name' => '松柏营业厅',
                'number' => 'SH00010',
                'type' => Cust::TYPE_STORE,
                'contacts' => '牛惠来',
                'mobile' => '13739728002'
            ),
            array(
                'name' => '新景营业厅',
                'number' => 'SH00011',
                'type' => Cust::TYPE_STORE,
                'contacts' => '张顺魁',
                'mobile' => '15931574653'
            ),
            array(
                'name' => '江头营业厅',
                'number' => 'SH00012',
                'type' => Cust::TYPE_STORE,
                'contacts' => '杨金发',
                'mobile' => '13630843280'
            ),
            array(
                'name' => '东渡营业厅',
                'number' => 'SH00013',
                'type' => Cust::TYPE_GROUP,
                'contacts' => '金宏欣',
                'mobile' => '13463817886'
            ),
            array(
                'name' => '厦大营业厅',
                'number' => 'SH00014',
                'type' => Cust::TYPE_STORE,
                'contacts' => '韦健文',
                'mobile' => '18733065765'
            ),
            array(
                'name' => '前埔营业厅',
                'number' => 'SH00015',
                'type' => Cust::TYPE_STORE,
                'contacts' => '刘树强',
                'mobile' => '13231197960'
            ),
            array(
                'name' => '软件园营业厅',
                'number' => 'SH00016',
                'type' => Cust::TYPE_STORE,
                'contacts' => '杨玉峰',
                'mobile' => '13933935646'
            ),
            array(
                'name' => '吕厝营业厅',
                'number' => 'SH00017',
                'type' => Cust::TYPE_GROUP,
                'contacts' => '庞浩然',
                'mobile' => '13473766198'
            ),
            array(
                'name' => '马垅营业厅',
                'number' => 'SH00018',
                'type' => Cust::TYPE_STORE,
                'contacts' => '刘涛',
                'mobile' => '15033500114'
            ),
            array(
                'name' => '高崎营业厅',
                'number' => 'SH00019',
                'type' => Cust::TYPE_STORE,
                'contacts' => '沈南',
                'mobile' => '13484581427'
            ),
            array(
                'name' => '莲坂营业厅',
                'number' => 'SH00020',
                'type' => Cust::TYPE_STORE,
                'contacts' => '李爱兰',
                'mobile' => '13663381874'
            )
        );
        // 添加商户等级
        foreach ($datas as $key => $item) {
            $cust = new Cust();
            $cust->name = $item['name'];
            $cust->number = $item['number'];
            $cust->type = $item['type'];
            $cust->user()->associate(User::find($key + 3));
            $cust->custLevel()->associate(CustLevel::find(mt_rand(1, 7)));
            $cust->contacts = $item['contacts'];
            $cust->mobile = $item['mobile'];
            $cust->logo_path = 'upload_files/init/6335184992d848f341a2aa83cf1fb277.png';
            $cust->save();
        }
    }
}
