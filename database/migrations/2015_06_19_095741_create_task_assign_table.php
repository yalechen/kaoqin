<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\TaskAssign;

class CreateTaskAssignTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 指定任务表，即临时任务
        Schema::create('task_assign', function (Blueprint $table)
        {
            // 主键ID
            $table->increments('id');

            // 标题
            $table->string('title');

            // 接收者
            $table->unsignedInteger('accept_user_id');

            // 发布者
            $table->unsignedInteger('publish_user_id');

            // 描述
            $table->string('remark')->default('');
            // 开始时间
            $table->timestamp('start_time');

            // 结束时间
            $table->timestamp('end_time');

            // 结果:完成-有门店签到即算完成&进行中
            $table->enum('status', [
                TaskAssign::STATUS_STARTING,
                TaskAssign::STATUS_END
            ])->default(TaskAssign::STATUS_STARTING);

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
        Schema::drop('task_assign');
    }
}
