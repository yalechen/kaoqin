<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Role;

class CreateRolesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 角色表
        Schema::create('roles', function (Blueprint $table)
        {
            // 主键ID
            $table->increments('id');

            // 标识
            $table->string('key');

            // 名称
            $table->string('name');

            // 备注
            $table->string('remark')->default('');

            // 状态
            $table->enum('status', [
                Role::STATUS_ON,
                Role::STATUS_OFF
            ])->default(Role::STATUS_ON);

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
        Schema::drop('roles');
    }
}
