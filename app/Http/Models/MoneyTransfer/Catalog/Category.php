<?php

namespace App\Http\Models\MoneyTransfer\Catalog;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table='category';
    protected $primaryKey='category_id';
    protected $fillable=[
        'parent_id',
        'name',
        'order_level',
        'status',
        'created_at',
        'updated_at'
    ];
    public $timestamps=false;
}
