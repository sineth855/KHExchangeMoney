<?php

namespace App;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\Account as Authenticatable;

class Account extends Authenticatable
{
    use Notifiable;
    use HasApiTokens, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guard = 'account_master';
    protected $table = 'account_master';
    protected $primaryKey = 'account_master_id';

    protected $fillable = [
        'first_name', 
        'last_name',
        'finger_print',
        'gender',
        'expired_account',
        'merchant_id',
        'contact1',
        'contact2',
        'email',
        'username',
        'password',
        'post_code',
        'country',
        'zip_code',
        'business',
    ];

    public $timestamps=false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','api_token'
    ];

}

