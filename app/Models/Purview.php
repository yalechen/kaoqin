<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\InfiniteHierarchy;

/**
 * 权限模型
 */
class Purview extends Model
{
    use InfiniteHierarchy;
    // 类型：菜单
    const TYPE_MENU = 'Menu';
    // 类型：功能方法
    const TYPE_ACTION = 'Action';

    // 使用来源：pc
    const USEON_PC = 'Pc';
    // 使用来源：app
    const USEON_APP = 'App';

    // 状态：开启
    const STATUS_ON = 'On';
    // 状态：关闭
    const STATUS_OFF = 'Off';

    protected $table = 'purviews';

    public $timestamps = false;

    protected $appends = [
        'path',
        'parent_id'
    ];

    /**
     * 所属角色
     */
    public function roles()
    {
        return $this->belongsToMany('Role', 'role_purviews', 'purview_id', 'role_id');
    }

/**
 * 生成路径附加条件
 */
    // public function getConditionAttribute()
    // {
    // if (! empty($this->attributes['condition'])) {
    // parse_str($this->attributes['condition'], $query);
    // return count(array_filter($query)) > 0 ? $query : [
    // $this->attributes['condition']
    // ];
    // }
    // return [];
    // }
}
