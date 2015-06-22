<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // 状态：开启
    const STATUS_ON = 'On';
    // 状态：关闭
    const STATUS_OFF = 'Off';

    protected $table = 'roles';
}
