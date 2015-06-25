<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Dept;

class CreateDeptsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 部门表
        Schema::create('depts', function (Blueprint $table)
        {
            // 主键ID
            $table->increments('id');

            // 名称
            $table->string('name');

            // 描述
            $table->string('remark')->default('');

            // 所属机构
            $table->unsignedInteger('org_id', false);

            // 排序
            $table->unsignedInteger('sort')->default(100);

            // 状态
            $table->enum('status', [
                Dept::STATUS_ON,
                Dept::STATUS_OFF
            ])->default(Dept::STATUS_ON);

            $table->unique([
                'name',
                'org_id'
            ]);

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
        Schema::drop('depts');
    }
}
