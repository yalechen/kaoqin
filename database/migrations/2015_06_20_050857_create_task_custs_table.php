<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskCustsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 任务客户表，针对常规和指定任务中指派该任务的多个商户和它们的拜访次数
        Schema::create('task_custs', function (Blueprint $table)
        {
            // 主键ID
            $table->increments('id');

            // 所属任务
            $table->morphs('task');

            // 拜访者
            $table->unsignedInteger('user_id', false);

            // 商户ID
            $table->unsignedInteger('cust_id', false);

            // 拜访次数
            $table->tinyInteger('times')->default(0);

            // 已拜访次数
            $table->tinyInteger('visited_times')->default(0);

            $table->unique([
                'task_id',
                'cust_id'
            ]);

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
        Schema::drop('task_custs');
    }
}
