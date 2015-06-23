<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\InfiniteHierarchy;

class Org extends Model
{
    use InfiniteHierarchy;

    protected $table = 'orgs';

    protected $appends = [
        'path',
        'parent_id'
    ];

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
