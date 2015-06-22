<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Article;

class CreateArticlesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 文章表
        Schema::create('articles', function (Blueprint $table)
        {
            // 主键ID
            $table->increments('id');

            // 文章类型
            $table->enum('type', [
                Article::TYPE_ABOUT,
                Article::TYPE_ACTION
            ]);

            // 发布者
            $table->unsignedInteger('user_id');

            // 标题
            $table->string('title')->unique();

            // 内容
            $table->text('content');

            // 排序
            $table->unsignedInteger('sort');

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
        Schema::drop('articles');
    }
}
