<?php
use Illuminate\Database\Seeder;

/**
 * 省市区填充
 */
class RegionTableSeeder extends Seeder
{

    /**
     * 省市县数据
     *
     * @return void
     */
    public function run()
    {
        // 导入数据
        $file = fopen(database_path('seeds/Region.sql'), 'r');
        while (($sql = fgets($file)) !== false) {
            $sql = preg_replace('/--.*/', '', $sql);
            $sql = trim($sql);
            if ($sql == '') {
                continue;
            }
            DB::insert($sql);
        }
        fclose($file);
    }
}