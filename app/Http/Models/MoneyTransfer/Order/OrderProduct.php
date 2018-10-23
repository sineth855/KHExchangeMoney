<?php

namespace App\Http\Models\MoneyTransfer\Order;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $table='order_product';
    protected $primaryKey='order_product_id';
    protected $fillable=[
        'order_id',
        'product_id',
        'name',
        'model',
        'quantity',
        'price',
        'total',
        'tax',
        'reward'
    ];
    public $timestamps=false;

    public function Order() 
    {
      return $this->belongsTo(Order::class, 'order_id');
    }
}
