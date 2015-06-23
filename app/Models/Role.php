<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 角色模型
 */
class Role extends Model
{
    // 状态：开启
    const STATUS_ON = 'On';
    // 状态：关闭
    const STATUS_OFF = 'Off';

    protected $table = 'roles';

    public static function boot()
    {
        parent::boot();

        static::deleted(function ($model)
        {
            // 删除成员关联数据
            $model->users()->sync([]);

            // 删除权限关联数据
            $model->purviews()->sync([]);
        });
    }

    /**
     * 所属用户
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_roles', 'user_id', 'role_id');
    }

    /**
     * 拥有的权限列表
     */
    public function purviews()
    {
        return $this->belongsToMany('App\Models\Purview', 'role_purviews', 'role_id', 'purview_id');
    }
}
