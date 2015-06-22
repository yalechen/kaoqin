<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purview extends Model
{

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
}
