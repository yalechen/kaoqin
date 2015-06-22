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
}
