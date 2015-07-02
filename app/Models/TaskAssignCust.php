<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskAssignCust extends Model
{
    // 结果：进行中
    const STATUS_STARTING = 'Starting';
    // 结果：已都拜访完毕
    const STATUS_END = 'End';

    protected $table = 'task_assign_cust';

    /**
     * 所属临时指定任务
     */
    public function task()
    {
        return $this->belongsTo('App\Models\TaskAssign', 'task_assign_id', 'id');
    }

    /**
     * 所属商户门店
     */
    public function cust()
    {
        return $this->belongsTo('App\Models\Cust');
    }
}
