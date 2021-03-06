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

    /**
     * 所拜访的商户门店
     */
    public function custs()
    {
        return $this->morphMany('App\Models\TaskCust', 'task');
    }

/**
 * 参考图片1
 */
    // public function getImage1PathAttribute()
    // {
    // return config('app.url') . str_replace('\\', '/', $this->attributes['image1_path']);
    // }
}
