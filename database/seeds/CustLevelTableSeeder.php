<?php
use Illuminate\Database\Seeder;
use App\Models\CustLevel;

class CustLevelTableSeeder extends Seeder
{

    /**
     * 商户等级表填充
     */
    public function run()
    {
        // 清空表数据
        CustLevel::truncate();

        $names = [
            'V1',
            'V2',
            'V3',
            'V4',
            'V5',
            'V6',
            'V7',
            'V8'
        ];

        // 添加商户等级
        foreach ($names as $key => $name) {
            $times = is_null(CustLevel::find($key)) ? 1 : CustLevel::find($key)->times + mt_rand(1, $key+1);
            $cust_level = new CustLevel();
            $cust_level->name = $name;
            $cust_level->times = $times;
            $cust_level->save();
        }
    }
}
