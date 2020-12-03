<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Admin extends Model implements AuthenticatableContract, CanResetPasswordContract
{

    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dbo_admin';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'username',
        'admin',
        'otp',
        'active',
        'is_super_admin'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function admin_group()
    {
        return $this->hasMany('App\UserGroup', 'user_id', 'id');
    }

    public function group_code()
    {
        $group_code = '';
        $admin_group = $this->admin_group;
        if (!count($admin_group))
            return $group_code;
        foreach ($admin_group as $item) {
            // Code Here
            $group_code .= $item->user_group_detail->code;
        }
        return $group_code;
    }
}
