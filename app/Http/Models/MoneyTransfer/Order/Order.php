<?php

namespace App\Http\Models\MoneyTransfer\Order;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table='order';
    protected $primaryKey='order_id';
    protected $fillable=[
        'invoice_no',
        'account_id',
        'firstname',
        'lastname',
        'email',
        'telephone',
        'custom_field',
        'payment_firstname',
        'payment_lastname',
        'payment_company',
        'payment_address_1',
        'payment_address_2',
        'payment_city',
        'payment_postcode',
        'payment_country',
        'payment_country_id',
        'payment_zone',
        'payment_zone_id',
        'payment_address_format',
        'payment_custom_field',
        'payment_method',
        'payment_code',
        'comment',
        'total',
        'order_status_id',
        'commission',
        'currency_id',
        'currency_code',
        'currency_value',
        'exchange_rate',
        'forwarded_ip',
        'user_agent',
        'accept_language',
        'created_add',
        'updated_add'
    ];
    public $timestamps=false;
}
