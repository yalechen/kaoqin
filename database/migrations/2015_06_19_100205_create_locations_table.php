<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 位置表
        Schema::create('locations', function (Blueprint $table)
        {
            // 主键ID
            $table->increments('id');

            // 员工ID
            $table->unsignedInteger('user_id');

            // 日期
            $table->date('date');

            // 时间
            $table->string('time');

            // 经度
            $table->string('lng');

            // 纬度
            $table->string('lat');

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
        Schema::drop('locations');
    }
}
