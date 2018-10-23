<?php

namespace App\Http\Models\MoneyTransfer\SetupMaster;

use Illuminate\Database\Eloquent\Model;

class DeliveryMethod extends Model
{
    protected $table='delivery_method';
    protected $primaryKey='delivery_method_id';
    protected $fillable=[
        'delivery_method_type_id',
        'name',
        'image',
        'order_level',
    	'created_by',
    	'modified_by',
    	'created_at',
    	'updated_at'
    ];
    public $timestamps=false;

    public function DeliveryMethodType() 
    {
      return $this->belongsTo(DeliveryMethodType::class, 'delivery_method_type_id');
    }
}
