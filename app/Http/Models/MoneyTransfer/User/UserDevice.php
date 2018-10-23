<?php

namespace App\Http\Models\MoneyTransfer\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class UserDevice extends Model
{
    protected $table='user_device';
    public $timestamps=false;
    protected $primaryKey='user_device_id';
    protected $fillable=[
        'sec_user_id',
    	'username',
    	'version',
        'device_id',
        'device_type',
        'is_enable'
    ]; 

}

