<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\TaskSign;

class CreateTaskSignTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 上下班打卡表
        Schema::create('task_sign', function (Blueprint $table)
        {
            // 主键ID
            $table->increments('id');

            // 用户ID
            $table->unsignedInteger('user_id');

            // 日期
            $table->date('date')->index();

            // 类型：上班On，下班Off
            $table->enum('type', [
                TaskSign::TYPE_OFF,
                TaskSign::TYPE_ON
            ])->default(TaskSign::TYPE_ON);

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
        Schema::drop('task_sign');
    }
}
