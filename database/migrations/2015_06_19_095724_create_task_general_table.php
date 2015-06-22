<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\TaskGeneral;

class CreateTaskGeneralTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 常规任务表
        Schema::create('task_general', function (Blueprint $table)
        {
            // 主键ID
            $table->increments('id');

            // 标题
            $table->string('title');

            // 月份
            $table->string('ymongth');

            // 接收者
            $table->unsignedInteger('accept_user_id');

            // 发布者
            $table->unsignedInteger('publish_user_id');

            // 描述
            $table->string('remark')->default('');

            // 等级
            $table->unsignedInteger('cust_level_id');

            // 客户ID，一对多，为数组
            $table->string('cust_ids');

            // 拜访客户数，这个数字要小于等于拜访次数
            $table->tinyInteger('custs')->default(0);

            // 拜访描述(eg:5天一次,2周一次)
            $table->string('times_remark');

            // 已拜访次数
            $table->tinyInteger('visited_times')->default(0);

            // 结果:完成&进行中
            $table->enum('status', [
                TaskGeneral::STATUS_STARTING,
                TaskGeneral::STATUS_END
            ])->default(TaskGeneral::STATUS_STARTING);

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
        Schema::drop('task_general');
    }
}
