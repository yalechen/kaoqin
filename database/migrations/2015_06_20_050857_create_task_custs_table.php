<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\TaskCust;

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

            // 年月份，针对常规任务，临时任务为空
            $table->string('ymonth')->default('');

            // 拜访者
            $table->unsignedInteger('user_id', false);

            // 商户ID
            $table->unsignedInteger('cust_id', false);

            // 商户门店等级
            $table->unsignedInteger('cust_level_id', false);

            // 拜访次数
            $table->tinyInteger('times')->default(0);

            // 已拜访次数
            $table->tinyInteger('visited_times')->default(0);

            // 状态
            $table->enum('status', [
                TaskCust::STATUS_STARTING,
                TaskCust::STATUS_END
            ])->default(TaskCust::STATUS_STARTING);

            $table->unique([
                'task_id',
                'task_type',
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
