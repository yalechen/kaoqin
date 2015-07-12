<?php
namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserLastLoginTimeSeek extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        // 填充最后登录时间
        if (is_null($user->this_login_time) || $user->this_login_time == '0000-00-00 00:00:00') {
            $user->last_login_time = new \Carbon\Carbon();
           // $user->this_login_time = new \Carbon\Carbon();
        } else {
            $user->last_login_time = $user->this_login_time;
          //  $user->this_login_time = new \Carbon\Carbon();
        }
        $user->save();
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
