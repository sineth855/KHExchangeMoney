<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_group_id', 
        'first_name', 
        'last_name',
        'gender',
        'dob',
        'company',
        'website',
        'owner',
        'address_1',
        'address_2',
        'username',
        'email', 
        'email_2',
        'password',
        'telephone_1',
        'telephone_2',
        'country',
        'country_id',
        'province',
        'city',
        'code',
        'image',
        'status',
        'remember_token',
        'api_token',
        'created_at',
        'updated_at'

    ];

    public function AccountMaster()
    {
        return $this->belongsTo("App\Http\Models\MoneyTransfer\MerchantAccount\AccountMaster",'id');
    }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];
}
