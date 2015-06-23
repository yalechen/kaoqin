<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrgsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 机构表
        Schema::create('orgs', function (Blueprint $table)
        {
            // 主键ID
            $table->increments('id');

            // 编号
            $table->string('number')->unique();

            // 名称
            $table->string('name')->unique();

            // 所属区域
            $table->string('area')->default('');

            // 上级路径
            $table->string('parent_path')->default('');

            // 地址：省
            $table->unsignedInteger('province_id')->default(0);

            // 地址：市
            $table->unsignedInteger('city_id')->default(0);

            // 地址：详细地址
            $table->string('address')->default('');

            // 经度
            $table->string('lng')->default('');

            // 纬度
            $table->string('lat')->default('');

            // 排序
            $table->unsignedInteger('sort')->default(100);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('orgs');
    }
}
