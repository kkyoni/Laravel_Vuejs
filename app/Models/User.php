<?php
namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable{
    use Notifiable;
    use SoftDeletes;
    /**
     * * The attributes that are mass assignable.
     * *
     * * @var array
     * */
    public $table = 'users';
    protected $fillable = ['first_name','last_name','email', 'password', 'user_type','dob','status'];
    /**
     * * The attributes that should be hidden for arrays.
     * *
     * * @var array
     * */
    protected $hidden = ['password', 'remember_token' ];
}