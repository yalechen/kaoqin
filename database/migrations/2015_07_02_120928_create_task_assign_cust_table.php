<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\TaskAssignCust;

class CreateTaskAssignCustTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 临时指定任务的商户门店关联表
        Schema::create('task_assign_cust', function (Blueprint $table)
        {
            // 自增主键ID
            $table->increments('id');

            // 任务ID
            $table->unsignedInteger('task_assign_id');

            // 商户门店ID
            $table->unsignedInteger('cust_id');

            // 拜访次数
            $table->tinyInteger('times');

            // 已拜访次数
            $table->tinyInteger('visited_times')->default(0);

            // 状态
            $table->enum('status', [
                TaskAssignCust::STATUS_STARTING,
                TaskAssignCust::STATUS_END
            ])->default(TaskAssignCust::STATUS_STARTING);

            $table->unique([
                'task_assign_id',
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
        Schema::drop('task_assign_cust');
    }
}
