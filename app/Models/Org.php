<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Org extends Model
{

    protected $table = 'orgs';

    public static function boot()
    {
        parent::boot();
    }

    /**
     * 旗下部门
     */
    public function depts()
    {
        return $this->hasMany('App\Dept');
    }
}
