<?php namespace App;
use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dbo_user_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'action',
        'description'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
}
