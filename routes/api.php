<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::post('/login','AuthController@login');
Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});

Route::group(['middleware' => ['auth:api','SettingConfig']], function(){
	Route::get('get_transaction_by_accountId', 'MoneyTransfer\Account\AccountMasterController@getTransactionByAccountId');
	Route::get('get_account_transaction', 'MoneyTransfer\Account\AccountMasterController@getAccountTransaction');
	Route::post('getAccount', 'MoneyTransfer\Account\AccountController@getAccount');
	Route::get('getMasterAccount', 'MoneyTransfer\Account\AccountMasterController@getMasterAccount');
	// get_amount_top_up
	Route::get('get_amount_top_up', 'MoneyTransfer\Account\VoucherController@getAmountTopUp');
	// get vouchers
	Route::get('get_voucher', 'MoneyTransfer\Account\VoucherController@getVoucher');
	// confirm_security_key
	Route::post('account/confirm_security_key', 'MoneyTransfer\Account\AccountMasterController@ConfirmSecurityKey');
	// Send Money
	Route::post('send_money', 'MoneyTransfer\Account\SendMoneyController@send_money');
	Route::post('confirm_send_money', 'MoneyTransfer\Account\SendMoneyController@confirm_send_money');
	// Transfer Money
	Route::post('transfer_money', 'MoneyTransfer\Account\TransferMoneyController@transfer_money');
	Route::post('confirm_transfer_money', 'MoneyTransfer\Account\TransferMoneyController@confirm_transfer');
	// Booking
	Route::post('booking', 'MoneyTransfer\Account\BookingController@booking');
	// voip
	Route::post('buy_voip', 'MoneyTransfer\Account\VoipController@buy_voip');
	Route::post('confirm_buy_voip', 'MoneyTransfer\Account\VoipController@confirm_buy_voip');
	// voucher
	Route::post('buy_voucher', 'MoneyTransfer\Account\VoucherController@buy_voucher');
	Route::post('confirm_buy_voucher', 'MoneyTransfer\Account\VoucherController@confirm_buy_voucher');
});

Route::group(['middleware' => ['SettingConfig']], function(){
	// get delivery method
	Route::get('get_delivery_method', 'MoneyTransfer\SetupMaster\DeliveryMethodController@index');
	// get delivery method Type
	Route::get('get_delivery_method_type', 'MoneyTransfer\SetupMaster\DeliveryMethodTypeController@index');
	// get_location
	Route::resource('get_location', 'MoneyTransfer\Location\LocationController');
	// get currency
	Route::get('get_currency', 'MoneyTransfer\Currencies\CurrenciesController@index');
	// get booking type
	Route::get('get_booking_type', 'MoneyTransfer\Account\BookingController@getBookingType');
	// exchange Rate
	Route::get('exchange_rate', 'MoneyTransfer\Account\ExchangeRateController@getExchangeRate');
	// language
	Route::get('languages', 'MoneyTransfer\Languages\LanguagesController@index');
	// information
	Route::get('information/about', 'MoneyTransfer\Account\InformationController@getAbout');
	// get Country
	Route::get('get_country', 'MoneyTransfer\SetupMaster\CountryController@index');
	// get Flight Location
	Route::get('get_flight_location', 'MoneyTransfer\SetupMaster\FlightLocationController@index');
	Route::get('getFlyLocationBaseCountry', 'MoneyTransfer\SetupMaster\FlightLocationController@getFlyLocationBaseCountry');

	// authentication
	Route::post('account_master/relogin', 'MoneyTransfer\Account\AccountMasterController@relogin');
	Route::post('account_master/login', 'MoneyTransfer\Account\AccountMasterController@login');
	Route::post('account_master/logout', 'MoneyTransfer\Account\AccountMasterController@logout');
	Route::post('account_master/register', 'MoneyTransfer\Account\AccountMasterController@register');
	Route::post('account_master/confirm_register', 'MoneyTransfer\Account\AccountMasterController@confirm_register');
	Route::post('del_account', 'MoneyTransfer\Account\AccountMasterController@del_account');
});