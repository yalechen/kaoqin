<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 用户文件模型
 */
class UserFile extends Model
{

    protected $table = 'user_files';

    /**
     * 所属用户
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * 文件
     */
    public function storage()
    {
        return $this->belongsTo('App\Models\Storage', 'storage_hash');
    }
}
