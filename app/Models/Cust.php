<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TaskLog;
use Auth;

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

    /**
     * 所属省份
     */
    public function province()
    {
        return $this->belongsTo('App\Models\Province');
    }

    /**
     * 所属市级
     */
    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    /**
     * 所属县区
     */
    public function district()
    {
        return $this->belongsTo('App\Models\District');
    }

    /**
     * 获取头像的绝对路径
     */
    public function getLogoPathAttribute()
    {
        return config('app.url') . str_replace('\\', '/', $this->attributes['logo_path']);
    }

    /**
     * 门店签到状态
     */
    public function getCustSignStatusAttribute(){

        $task_log = TaskLog::where('visit_date',date('Y-m-d',time()))
            ->where('cust_id',$this->attributes['id'])
            ->where('user_id',Auth::user()->id)
            ->first();
        if(is_null($task_log)){
            $sign_status = 'N';
        }else{
            $sign_status = 'Y';
        }

        return $sign_status;

    }
}
