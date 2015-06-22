<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{

    protected $table = 'locations';

    /**
     * 旗下部门
     */
    public function user()
    {
        return $this->hasMany('App\Models\User');
    }
}
