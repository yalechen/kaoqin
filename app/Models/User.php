<?php
namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    // 性别：男
    const GENDER_MALE = 'Male';
    // 性别：女
    const GENDER_FEMALE = 'Female';
    // 状态：在职
    const STATUS_ON = 'On';
    // 状态：离职
    const STATUS_OFF = 'Off';

    use Authenticatable, CanResetPassword;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model)
        {
            // 保存的时候自动对密码进行加密。
            if (isset($model->password) && Hash::needsRehash($model->password)) {
                $model->password = Hash::make($model->password);
            }
        });
    }

    /**
     * 所属机构
     */
    public function org()
    {
        return $this->hasMany('App\Models\Org');
    }

    /**
     * 所属部门
     */
    public function dept()
    {
        return $this->hasOne('App\Models\Dept');
    }

    /**
     * 上级领导
     */
    public function leader()
    {
        return $this->hasMany('App\Models\User', 'parent_user_id', 'id');
    }
}
