<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskCust extends Model
{
    // 结果：进行中
    const STATUS_STARTING = 'Starting';
    // 结果：已都拜访完毕
    const STATUS_END = 'End';

    protected $table = 'task_custs';

    /**
     * 所属任务
     */
    public function task()
    {
        return $this->morphTo();
    }

    /**
     * 拜访者
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * 所属商户
     */
    public function cust()
    {
        return $this->belongsTo('App\Models\Cust');
    }
}
