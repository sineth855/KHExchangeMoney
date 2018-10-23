<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider fhin a group which
| contains the "web" middleware group. Now create something great!
|
*/
// link reference multiple authentication https://www.codementor.io/okoroaforchukwuemeka/9-tips-to-set-up-multiple-authentication-in-laravel-ak3gtwjvt
// language

if(isset($_COOKIE['Language'])){
    session(['languageActive' => $_COOKIE['Language']]);
} else {
    session(['languageActive' => 'English']);
    setcookie("Language", 'English', time()+3600*24*365, '/');
}

// account master authentication 
Auth::routes();
if (Request::is('account*')){
    Route::get('account', 'FrontEnd\Account\AccountController@customer');
    Route::get('account/{id}', 'FrontEnd\Account\AccountController@customer');
};
Route::get('api/account/getAccount', 'MoneyTransfer\Account\AccountController@getAccount');
Route::get('login',array('as'=>'login',function(){
    // return view('auth/account_login');
    // return view('index');
    return redirect("auth/logout");
}));

Route::get('logout',array('as'=>'login',function(){
    // return view('auth/account_login');
    // return view('index');
    return redirect("auth/logout");
}));

Route::get('logout', function()
{
    Auth::guard('account')->logout();
    return redirect("/#/pages/login");
    // return redirect("/login");
})->name('login');

// authentication login admin and credential account login
Route::post('login', function(Illuminate\Http\Request $request) 
{
    // if($request->is_account==1){
        // if (Auth::guard('account')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
        //     return response()->json(['success' => true, 'message' => 'Login successfully performed'], 200);
        // } 
    // }else{
        // if (Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {
        //     return response()->json(['success' => true, 'message' => 'Login successfully performed'], 200);
        // }
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $success['userId'] = $user->id;
            // $success['user_id'] =  $user->token->id;
            $success['token'] =  $user->createToken('Token Name')->accessToken;
            $token = $user->createToken('Token Name')->accessToken;
            return response($success)
                    ->header('Content-Type', 'application/json')
                    ->header('X-Header-One', 'Header Value')
                    ->header('X-Header-Two', 'Header Value')
                    ->header('Authorization', 'Bearer ' . $success['token']); //Theoretically
            // $contents = '';
            // $statusCode = '';
            // return response()->header('Authorization', 'Bearer '.$token)
            //                  ->json(['success' => $success], 200);
                    // ->header('Authorization', 'Bearer ' . $success['token'])
                    // ->json(['success' => $success], 200);
        }
    // }
    return response()->json(['success' => false, 'message' => 'Unable to login'], 401);
});


// API Back Office is place here
// Route::group(['middleware' => 'auth'], function() {

Route::get('/', function () {
    return view('welcome');
});

if(Request::is('admin*')){
    Route::middleware(['auth'])->prefix('admin')->group(function () {
        // if (Request::is('admin/api*')){
        //     Route::get('admin/{any?}',function(){
        //         return view('index');
        //     })->where(['any'=>'.*']);
        // }else{
            Route::get('/{any?}',function(){
                return view('index');
            })->where(['any'=>'.*']);
        // }
    });
}else if(Request::is('auth*')){
    Route::post('/#/pages/login', 'Auth\LoginController@login');
    Route::get('/#/pages/login',function(){
        // return view('index');
        return view('welcome');
        // return view('auth/account_login');
    });
    // Route::get('auth/login', 'Auth\LoginController@showLoginForm');
    Route::get('auth/logout', 'Auth\AuthController@getLogout');

    Route::get('auth/logout', function()
    {
        Auth::logout();
        return redirect("/#/pages/login");
    })->name('login');
    
}else if (Request::is('api*')){
    Route::get('api/check_authentication', 'AuthController@checkAuthentication');
    Route::middleware(['auth'])->prefix('api')->group(function () {
        Route::resource('categories', 'MoneyTransfer\Category\CategoryController');
        Route::get('getCategories', 'MoneyTransfer\Category\CategoryController@getCategoriesList');
        Route::get('getCategories_type', 'MoneyTransfer\Category\CategoryController@getCategoriesType');
        Route::get('getCategories_parent', 'MoneyTransfer\Category\CategoryController@getCategoriesParent');
        //=====Product API=============================
        Route::resource('products', 'MoneyTransfer\Products\ProductsController');
        Route::get('/getManufacturers','MoneyTransfer\Manufacturers\ManufacturersController@getManufacturers');
        Route::get('/getMenus', 'MoneyTransfer\Settings\GroupRolesController@index');
        //=====Setup Master API=============================
        Route::resource('account_rule','MoneyTransfer\SetupMaster\AccountRuleController');
        Route::resource('account_type','MoneyTransfer\SetupMaster\AccountTypeController');
        Route::resource('amount_top_up','MoneyTransfer\SetupMaster\AmountTopUpController');
        Route::resource('booking_type','MoneyTransfer\SetupMaster\BookingTypeController');
        Route::resource('bookings','MoneyTransfer\Booking\BookingController');
        Route::resource('country','MoneyTransfer\SetupMaster\CountryController');
        Route::resource('currencies','MoneyTransfer\SetupMaster\CurrenciesController');
        Route::resource('delivery_method','MoneyTransfer\SetupMaster\DeliveryMethodController');
        Route::resource('interest_method','MoneyTransfer\SetupMaster\InterestMethodController');
        Route::resource('interest_rate_period','MoneyTransfer\SetupMaster\InterestRatePeriodController');
        Route::resource('payment_cycle','MoneyTransfer\SetupMaster\PaymentCycleController');
        Route::resource('term_day','MoneyTransfer\SetupMaster\TermDayController');
        Route::resource('delivery_method_type','MoneyTransfer\SetupMaster\DeliveryMethodTypeController');
        Route::get('voucher/getVoucherBaseCountry','MoneyTransfer\SetupMaster\VoucherController@getVoucherBaseCountry');
        Route::resource('voucher','MoneyTransfer\SetupMaster\VoucherController');
        Route::resource('buy_voucher','MoneyTransfer\Voucher\BuyVoucherController');
        Route::resource('buy_voip','MoneyTransfer\Voucher\BuyVoipController');
        Route::resource('voip','MoneyTransfer\Voip\VoipController');
        Route::get('fly_location/getFlyLocationBaseCountry','MoneyTransfer\SetupMaster\FlightLocationController@getFlyLocationBaseCountry');
        Route::resource('fly_location','MoneyTransfer\SetupMaster\FlightLocationController');
        Route::resource('product_loan','MoneyTransfer\SetupMaster\ProductLoanController');
        Route::resource('stock_status','MoneyTransfer\SetupMaster\StockStatusController');
        Route::resource('restrict_type','MoneyTransfer\SetupMaster\RestrictTypeController');
        Route::resource('rule_type','MoneyTransfer\SetupMaster\RuleTypeController');
        Route::resource('transaction_charge_fee','MoneyTransfer\SetupMaster\TransactionChargeFeeController');
        Route::resource('transaction_rule','MoneyTransfer\SetupMaster\TransactionRuleController');
        //=====Catalog
        Route::resource('catalog/category', 'MoneyTransfer\Catalog\CategoryController');
        Route::get('catalog/product/product_base_category/{category_id}', 'MoneyTransfer\Catalog\ProductController@getProductBaseCategory');                
        Route::resource('catalog/product', 'MoneyTransfer\Catalog\ProductController');        
        //=====Merchant Account
        Route::resource('merchant_account/account_master', 'MoneyTransfer\MerchantAccount\AccountMasterController');        
        Route::get('merchant_account/account_master/get_account/{id}', 'MoneyTransfer\MerchantAccount\AccountMasterController@getAccountBaseMerchant');
        Route::put('merchant_account/account_master/update_account/{id}', 'MoneyTransfer\MerchantAccount\AccountMasterController@updateAccountBaseMerchant');
        Route::get('merchant_account/account_master/get_account_detail/{id}', 'MoneyTransfer\MerchantAccount\AccountMasterController@getAccountDetail');
        Route::resource('merchant_account/account_credit', 'MoneyTransfer\MerchantAccount\AccountCreditController');
        Route::post('merchant_account/account_master/save_account', 'MoneyTransfer\MerchantAccount\AccountMasterController@saveAccount');
        Route::get('getAccountMaster', 'MoneyTransfer\MerchantAccount\AccountMasterController@getAccountMaster');
        Route::get('generateMerchantID','MoneyTransfer\MerchantAccount\AccountMasterController@generateMerchantID');
        Route::resource('merchant_account/account_loan', 'MoneyTransfer\MerchantAccount\AccountLoanController');
        Route::get('merchant_account/account_loan_payment/getLoanPayment/{loan_id}', 'MoneyTransfer\MerchantAccount\AccountLoanPaymentController@getLoanPayment');
        Route::resource('merchant_account/account_loan_payment', 'MoneyTransfer\MerchantAccount\AccountLoanPaymentController');
        Route::resource('merchant_account/account_transaction', 'MoneyTransfer\MerchantAccount\AccountTransactionController');
        Route::resource('merchant_account/account_withdrawal', 'MoneyTransfer\MerchantAccount\AccountWithdrawalController');
        Route::resource('merchant_account/account_notification', 'MoneyTransfer\MerchantAccount\AccountNotificationController');
        //=====Stock Balance
        Route::resource('stocks/back_account', 'MoneyTransfer\StockBalance\BankAccountController');
        Route::resource('stocks/account_owner', 'MoneyTransfer\StockBalance\AccountOwnerController');
        Route::get('stocks/account_owner/get_account_detail/{id}', 'MoneyTransfer\StockBalance\AccountOwnerController@getAccountDetail');
        Route::resource('stocks/account_owner_credit', 'MoneyTransfer\StockBalance\AccountOwnerCreditController');
        Route::resource('stocks/account_owner_history', 'MoneyTransfer\StockBalance\AccountOwnerHistoryController');
        Route::resource('stocks/account_owner_transfer', 'MoneyTransfer\StockBalance\AccountOwnerTransferController');
        Route::resource('stocks/account_owner_withdraw', 'MoneyTransfer\StockBalance\AccountOwnerWithdrawController');
        //=====Send Money
        Route::resource('money/send_money', 'MoneyTransfer\SendMoney\SendMoneyController');
        Route::resource('money/transfer_money', 'MoneyTransfer\TransferMoney\TransferMoneyController');
        //=====Currency
        Route::get('getCurrency', 'MoneyTransfer\Currencies\CurrenciesController@getCurrency');
        Route::resource('exchange_rates','MoneyTransfer\Currencies\ExchangeRateController');
        Route::resource('exchange_rate_history','MoneyTransfer\Currencies\ExchangeRateHistoryController');
        // Order
        Route::resource('order','MoneyTransfer\Order\OrderController');
        // user
        Route::resource('user_groups','MoneyTransfer\UserGroups\UserGroupsController');
        Route::get('users_group','MoneyTransfer\Users\UsersController@UserGroup');
        Route::Resource('users','MoneyTransfer\Users\UsersController');
        Route::Resource('setting/configuration','MoneyTransfer\Settings\SettingsController');
        // file upload services
        Route::post('file_upload', 'Services\FileUploadController@fileUploadService');
    });
    Route::get('auth/logout', function()
    {
        Auth::logout();
        return redirect("auth/login");
    })->name('login');
}
