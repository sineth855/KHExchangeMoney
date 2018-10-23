<?php

namespace App\Http\Models\MoneyTransfer\SetupMaster;

use Illuminate\Database\Eloquent\Model;

class ProductLoan extends Model
{
    protected $table='loan_product';
    protected $primaryKey='loan_product_id';
    protected $fillable=[
        'name'
    ];
    public $timestamps=false;
}
