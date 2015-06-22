<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeptUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 部门用户表
        Schema::create('dept_users', function (Blueprint $table)
        {
            // 主键ID
            $table->increments('id');

            // 部门ID
            $table->unsignedInteger('dept_id', false);

            // 用户ID
            $table->unsignedInteger('user_id', false);

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
        Schema::drop('dept_users');
    }
}
