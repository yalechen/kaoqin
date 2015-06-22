<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{

    protected $table = 'leave';

    /**
     * 请假类型
     */
    public function type()
    {
        return $this->belongsTo('App\Models\LevelType');
    }

    /**
     * 请假者
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * 所属部门
     */
    public function dept()
    {
        return $this->belongsTo('App\Models\Dept');
    }
}
