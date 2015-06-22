<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskCust extends Model
{

    protected $table = 'task_custs';

    /**
     * 所属任务
     */
    public function task()
    {
        return $this->morphTo();
    }

    /**
     * 所属商户
     */
    public function cust()
    {
        return $this->belongsTo('App\Models\Cust');
    }
}
