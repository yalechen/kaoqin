<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    // 状态：审核通过
    const STATUS_PASS = 'Pass';
    // 状态：审核驳回
    const STATUS_REJECT = 'Reject';
    // 状态：待审核中
    const STATUS_WAIT = 'Wait';

    protected $table = 'overtime';

    public static function boot()
    {
        parent::boot();
    }

    /**
     * 加班者
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * 审核者
     */
    public function auditUser()
    {
        return $this->belongsTo('App\Models\User', 'audit_user_id', 'id');
    }
}
