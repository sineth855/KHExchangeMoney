<?php

namespace App\Http\Models\MoneyTransfer\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Http\Models\MoneyTransfer\UserGroup\UserGroup;
class User extends Model
{
    protected $table='users';
    public $timestamps=true;
    protected $primaryKey='id';
    protected $fillable=[
        'id',
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
        'country_code',
    	'province',
    	'city',
    	'code',
    	'image',
    	'status',
        'remember_token',
        'api_token'
    ]; 
    //protected $hidden=['user_group_id','password','salt','ip'];
    
    public function UserGroup(){
		return $this->belongsTo(UserGroup::class,'user_group_id');
	}
    static function AllUser()
    {
        $users=DB::table('users')
            ->join('user_group', 'users.user_group_id', '=', 'user_group.user_group_id')
            ->select(
                'id as id', 
                'username', 
                'user_group.name as group',
                'first_name',
                'last_name',
                'email', 
                'code',
                'image',
                'status',
                'created_at'
            )
            ->get();
        return $users;
    }

    static function getUserReseller($group_id)
    {
        $users=DB::table('users')
            ->join('user_group', 'users.user_group_id', '=', 'user_group.user_group_id')
            ->select(
                'id as id', 
                'username', 
                'user_group.name as group',
                'first_name',
                'last_name',
                'email', 
                'code',
                'image',
                'status',
                'created_at'
            )
            ->where('users.user_group_id',$group_id)
            ->get();
        return $users;
    }

    static function getUserCarriers($group_id)
    {
        $users=DB::table('users')
            ->join('user_group', 'users.user_group_id', '=', 'user_group.user_group_id')
            ->select(
                'id as id', 
                'username', 
                'user_group.name as group',
                'first_name',
                'last_name',
                'email', 
                'code',
                'image',
                'status',
                'created_at'
            )
            ->where('users.user_group_id',$group_id)
            ->get();
        return $users;
    }

}

