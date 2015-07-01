<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskLogsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 外勤记录表
        Schema::create('task_logs', function (Blueprint $table)
        {
            // 主键ID
            $table->increments('id');

            // 用户ID
            $table->unsignedInteger('user_id');

            // 任务ID及类型
            $table->morphs('task');

            // 拜访的客户ID(只有常规任务，或者是指定任务)
            $table->unsignedInteger('cust_id')->default(0);

            // 拜访时间
            $table->timestamp('visit_time');

            // 备注
            $table->string('remark');

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
        Schema::drop('task_logs');
    }
}
