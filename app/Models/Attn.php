<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attn extends Model
{
    // 状态：正常
    const STATUS_OK = 'Ok';
    // 状态：异常
    const STATUS_NG = 'Ng';

    protected $table = 'attn';

    public static function boot()
    {
        parent::boot();
    }

    /**
     * 旗下部门
     */
    public function depts()
    {
        return $this->hasMany('App\Models\Dept');
    }
}
