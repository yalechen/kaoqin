<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    // 类型：关于我们
    const TYPE_ABOUT = 'About';
    // 类型：功能介绍
    const TYPE_ACTION = 'Action';

    protected $table = 'articles';

    /**
     * 发布者
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
