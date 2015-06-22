<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Cust;

class CreateCustsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 客户
        Schema::create('custs', function (Blueprint $table)
        {
            // 主键ID
            $table->increments('id');

            // 编号
            $table->string('number')->unique();

            // 名称
            $table->string('name');

            // 类型[门店&集团]
            $table->enum('type', [
                Cust::TYPE_STORE,
                Cust::TYPE_GROUP
            ])->default(Cust::TYPE_GROUP);

            // 负责的巡店人
            $table->unsignedInteger('user_id');

            // 等级
            $table->unsignedBigInteger('cust_level_id');

            // 联系人名称
            $table->string('contacts');

            // 联系方式
            $table->string('mobile');

            // logo
            $table->string('logo_path');

            // 详细地址
            $table->string('address')->default('');

            // 经度
            $table->string('lng')->default('');

            // 纬度
            $table->string('lat')->default('');

            // 所属区域
            $table->string('area')->default('');

            // 备注
            $table->string('remark')->default('');

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
        Schema::drop('custs');
    }
}
