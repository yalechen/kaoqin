<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskLog extends Model
{

    protected $table = 'task_assign';

    /**
     * 执行者
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * 所属任务
     */
    public function task()
    {
        return $this->morphTo();
    }
}
