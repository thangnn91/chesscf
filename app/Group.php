<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'dbo_group';
    protected $fillable = [];

    public function group()
    {
        return $this->hasMany('App\UserGroup', 'group_id', 'id');
    }
}
