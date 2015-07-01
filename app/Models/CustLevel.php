<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustLevel extends Model
{

    protected $table = 'cust_level';

    public static function boot()
    {
        parent::boot();
    }

    /**
     * 此等级旗下商户
     */
    public function custs()
    {
        return $this->hasMany('App\Models\Cust');
    }
}
