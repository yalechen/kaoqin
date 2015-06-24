<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustLevelTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 商户登记表
        Schema::create('cust_level', function (Blueprint $table)
        {
            // 主键ID
            $table->increments('id');

            // 等级名称
            $table->string('name')->unique();

            // 每月拜访次数
            $table->tinyInteger('times')->default(0);

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
        Schema::drop('cust_level');
    }
}
