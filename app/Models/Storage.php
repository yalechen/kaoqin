<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 文件存储模型
 */
class Storage extends Model
{

    protected $table = 'storages';

    protected $primaryKey = 'hash';

    public $incrementing = false;

    protected $appends = [
        'url'
    ];

    /**
     * 获取图片的路径
     */
    public function getPathAttribute()
    {
        return str_replace('\\', '/', $this->attributes['path']);
    }

    /**
     * 获取图片的缩列图
     */
    public function thumbnails()
    {
        return $this->hasMany('App\Models\Storage', 'parent_hash', 'hash');
    }

    /**
     * 获取文件的url地址
     */
    public function getUrlAttribute()
    {
        return config('app.url') . $this->attributes['path'];
    }
}