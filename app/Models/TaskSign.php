<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskSign extends Model
{
    // 类型：上班
    const TYPE_ON = 'On';
    // 类型：下班
    const TYPE_OFF = 'Off';

    protected $table = 'task_sign';

    public static function boot()
    {
        parent::boot();
    }

    /**
     * 打卡人
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
