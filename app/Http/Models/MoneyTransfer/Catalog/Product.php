<?php

namespace App\Http\Models\MoneyTransfer\Catalog;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table='product';
    protected $primaryKey='product_id';
    protected $fillable=[
        'category_id',
        'name',
        'quantity',
        'model',
        'cost',
        'price',
        'manufacturer',
        'stock_status_id',
        'status',
        'created_at',
        'updated_at'
    ];
    public $timestamps=false;

    public function Category() 
    {
      return $this->belongsTo("App\Http\Models\MoneyTransfer\Catalog\Category", 'category_id');
    }

    public function StockStatus() 
    {
      return $this->belongsTo("App\Http\Models\MoneyTransfer\SetupMaster\StockStatus", 'stock_status_id');
    }
}
