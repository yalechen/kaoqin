<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolePurviewsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 角色权限表
        Schema::create('role_purviews', function (Blueprint $table)
        {
            // 主键ID
            $table->increments('id');

            // 角色ID
            $table->unsignedInteger('role_id');

            // 权限ID
            $table->unsignedInteger('purview_id');

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
        Schema::drop('role_purviews');
    }
}
