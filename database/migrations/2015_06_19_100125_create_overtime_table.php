<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Overtime;

class CreateOvertimeTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 加班表
        Schema::create('overtime', function (Blueprint $table)
        {
            // 主键ID
            $table->increments('id');

            // 加班者
            $table->unsignedInteger('user_id');

            // 开始时间
            $table->timestamp('start_time');

            // 结束时间
            $table->timestamp('end_time');

            // 加班工时
            $table->tinyInteger('hours');

            // 加班原因
            $table->string('reason');

            // 审核者
            $table->unsignedInteger('audit_user_id');

            // 审核时间
            $table->timestamp('audit_time');

            // 审核状态
            $table->enum('status', [
                Overtime::STATUS_OK,
                Overtime::STATUS_NG
            ])->default(Overtime::STATUS_OK);

            // reject_reason
            $table->string('reject_reason');

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
        Schema::drop('overtime');
    }
}
