<?php
use Illuminate\Database\Seeder;
use App\Models\Feedback;
use App\Models\User;

class FeedbackTableSeeder extends Seeder
{

    /**
     * 意见反馈表填充
     */
    public function run()
    {
        // 清空表数据
        Feedback::truncate();

        $users = User::where('id', '>', 2)->get();

        foreach ($users as $user) {
            $feedback = new Feedback();
            $feedback->user()->associate($user);
            $feedback->content = str_random(16);
            $feedback->save();
        }
    }
}
