<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskAssign extends Model
{
    // 结果：进行中
    const STATUS_STARTING = 'Starting';
    // 结果：已都拜访完毕
    const STATUS_END = 'End';

    protected $table = 'task_assign';

    /**
     * 接收者
     */
    public function acceptUser()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * 发布者
     */
    public function publishUser()
    {
        return $this->belongsTo('App\Models\User');
    }
}
