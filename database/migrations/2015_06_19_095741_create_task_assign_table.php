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
        // 指定任务表，即临时任务，不能和常规任务重复
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

            // 月份，根据开始时间得来，方便查找
            $table->string('ymonth')->index();

            // 开始时间
            $table->timestamp('start_time');

            // 结束时间
            $table->timestamp('end_time');

            // 任务执行参考图片
            $table->string('image1_path')->default('');

            // 任务执行参考图片
            $table->string('image2_path')->default('');

            // 任务执行参考图片
            $table->string('image3_path')->default('');

            // 任务执行参考图片
            $table->string('image4_path')->default('');

            // 任务执行参考图片
            $table->string('image5_path')->default('');

            // 总的拜访次数，必须大于0
            $table->tinyInteger('times');

            // 总的已拜访次数
            $table->tinyInteger('visited_times')->default(0);

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
