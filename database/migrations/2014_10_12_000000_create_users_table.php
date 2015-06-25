<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\User;

class CreateUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 用户表
        Schema::create('users', function (Blueprint $table)
        {
            // 主键ID
            $table->increments('id');

            // 用户名
            $table->string('name');

            // 邮箱
            $table->string('email')->unique();

            // 密码
            $table->string('password', 60);

            // 头像
            $table->string('avatar_path');

            // 工号
            $table->string('number')->unique();

            // 真实姓名
            $table->string('realname')->default('');

            // 手机号
            $table->string('mobile')->unique();

            // 性别
            $table->enum('gender', [
                User::GENDER_MALE,
                User::GENDER_FEMALE
            ])->default(User::GENDER_MALE);

            // 出生年月
            $table->date('birthday');

            // 地址：省
            $table->unsignedInteger('province_id')->default(0);

            // 地址：市
            $table->unsignedInteger('city_id')->default(0);

            // 地址：详细地址
            $table->string('address')->default('');

            // 所属机构
            $table->unsignedInteger('org_id')->default(0);

            // 所属部门
            $table->unsignedInteger('dept_id')->default(0);

            // 上级用户
            $table->unsignedInteger('parent_user_id')->default(0);

            // 所巡门店ID
            $table->unsignedInteger('cust_id')->default(0);

            // 状态
            $table->enum('status', [
                User::STATUS_ON,
                User::STATUS_OFF
            ])->default(User::STATUS_ON);

            $table->rememberToken();
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
        Schema::drop('users');
    }
}
