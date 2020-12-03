<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    protected $table = 'dbo_user_group';
    protected $fillable = [];


    public function admin_group()
    {
        return $this->belongsTo('App\Admin', 'user_id');
    }

    public function user_group_detail()
    {
        return $this->belongsTo('App\Group', 'group_id');
    }
}
