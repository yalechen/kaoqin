<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Purview;

class CreatePurviewsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 权限表
        Schema::create('purviews', function (Blueprint $table)
        {
            // 主键ID
            $table->increments('id');

            // 名称
            $table->string('name');

            // 标识
            $table->string('key');

            // 上级路径
            $table->string('parent_path');

            // 所属控制器
            $table->string('controller');

            // 所属方法
            $table->string('action');

            // 参数条件
            $table->string('condition');

            // 类型
            $table->enum('type', [
                Purview::TYPE_MENU,
                Purview::TYPE_ACTION
            ])->default(Purview::TYPE_ACTION);

            // 使用来源
            $table->enum('useon', [
                Purview::USEON_APP,
                Purview::USEON_PC
            ])->default(Purview::USEON_PC);

            // 排序
            $table->unsignedInteger('sort')->default(100);

            // 状态
            $table->enum('status', [
                Purview::STATUS_ON,
                Purview::STATUS_OFF
            ])->default(Purview::STATUS_ON);

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
        Schema::drop('purviews');
    }
}
