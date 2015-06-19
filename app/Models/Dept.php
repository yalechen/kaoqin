<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dept extends Model
{

    // 状态：可用
    const STATUS_ON = 'On';
    // 状态：禁用
    const STATUS_OFF = 'Off';

    protected $table = 'depts';

    public static function boot()
    {
        parent::boot();
    }

    /**
     * 所属机构
     */
    public function org()
    {
        return $this->belongsTo('App\Org');
    }
}
