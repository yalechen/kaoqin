<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cust extends Model
{
    // 类型：门店
    const TYPE_STORE = 'Store';
    // 类型：集团
    const TYPE_GROUP = 'Group';

    protected $table = 'custs';

    public static function boot()
    {
        parent::boot();
    }

    /**
     * 旗下部门
     */
    public function custLevel()
    {
        return $this->belongsTo('App\Models\CustLevel');
    }

    /**
     * 巡店人
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
