<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{

    protected $table = 'feedbacks';

    /**
     * 反馈者
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
