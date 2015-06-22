<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbacksTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 意见反馈表
        Schema::create('feedbacks', function (Blueprint $table)
        {
            // 主键ID
            $table->increments('id');

            // 反馈者
            $table->unsignedInteger('user_id');

            // 反馈内容
            $table->string('content');

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
        Schema::drop('feedbacks');
    }
}
