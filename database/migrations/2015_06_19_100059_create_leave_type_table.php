<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveTypeTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 请教类型表
        Schema::create('leave_type', function (Blueprint $table)
        {
            // 主键ID
            $table->increments('id');

            // 名称
            $table->string('name');

            // 排序
            $table->unsignedInteger('sort')->default(100);

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
        Schema::drop('leave_type');
    }
}
