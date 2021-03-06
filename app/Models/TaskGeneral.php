<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskGeneral extends Model
{
    // 结果：进行中
    const STATUS_STARTING = 'Starting';
    // 结果：已都拜访完毕
    const STATUS_END = 'End';

    protected $table = 'task_general';

    protected $casts = [
        'cust_ids' => 'array'
    ];

    public static function boot()
    {
        parent::boot();
    }

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

    /**
     * 门店等级
     */
    public function custLevel()
    {
        return $this->belongsTo('App\Models\CustLevel');
    }
}
