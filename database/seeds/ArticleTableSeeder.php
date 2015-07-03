<?php
use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\User;

class ArticleTableSeeder extends Seeder
{

    /**
     * 文章表填充
     */
    public function run()
    {
        // 清空表数据
        Article::truncate();

        $user = User::find(1);
        // 新增20篇关于我们的文章
        for ($i = 1; $i <= 20; $i ++) {
            $article = new Article();
            $article->user()->associate($user);
            $article->type = Article::TYPE_ABOUT;
            $article->title = '关于我们文章' . $i;
            $article->content = '关于我们文章' . $i;
            $article->sort = mt_rand(50, 150);
            $article->save();
        }

        // 新增20篇功能介绍的文章
        for ($i = 1; $i <= 20; $i ++) {
            $article = new Article();
            $article->user()->associate($user);
            $article->type = Article::TYPE_ACTION;
            $article->title = '功能介绍文章标题' . $i;
            $article->content = '功能介绍文章内容' . $i;
            $article->sort = mt_rand(50, 150);
            $article->save();
        }
    }
}
