<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 请教表
        Schema::create('leave', function (Blueprint $table)
        {
            // 主键ID
            $table->increments('id');

            //请假类型
            $table->unsignedInteger('leave_type_id');

            // 请假者
            $table->unsignedInteger('user_id');

            // 请假者真实姓名
            $table->string('realname');

            // 所属部门
            $table->unsignedInteger('dept_id');

            // 开始时间
            $table->timestamp('start_time');

            // 结束时间
            $table->timestamp('end_time');

            // 请假天数
            $table->tinyInteger('days');

            // 请假时数
            $table->tinyInteger('hours');

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
        Schema::drop('leave');
    }
}
