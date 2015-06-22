<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRolesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 用户角色表
        Schema::create('user_roles', function (Blueprint $table)
        {
            // 主键ID
            $table->increments('id');

            // 角色ID
            $table->unsignedInteger('role_id');

            // 用户ID
            $table->unsignedInteger('user_id');

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
        Schema::drop('user_roles');
    }
}
