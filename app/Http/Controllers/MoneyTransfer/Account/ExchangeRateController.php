<?php

    namespace App\Http\Controllers\MoneyTransfer\Account;

    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Http\Models\MoneyTransfer\Currency\ExchangeRate;
    use App\Http\Models\MoneyTransfer\Currency\Currency;
    use App\Account;
    use Validator;
    use Carbon\Carbon;
    use DB;
    use Illuminate\Support\Facades\Auth;
    use Session;

    class ExchangeRateController extends Controller
    {
        /**
         * Display a listing of the BestSeller Create On 01-02-2018
         *
         * @return \Illuminate\Http\Response
         */
        public function getExchangeRate(){
            $MasterCurrency = Currency::where('currency_id',config_currency)->first();
            $CurrencyArr = array();
            $ExchangeRateArray = array();
            $ExchangeRates = ExchangeRate::all();
            foreach ($ExchangeRates as $ExchangeRate) {
                $ExchangeRateArray[] = array(
                    'from_currency'=>$ExchangeRate->ExchangeFromCurrency->title,
                    'from_currency_code'=>$ExchangeRate->ExchangeFromCurrency->code,
                    'to_currency'=>$ExchangeRate->ExchangeToCurrency->title,
                    'to_currency_code'=>$ExchangeRate->ExchangeToCurrency->code,
                    'buy_in'=>$ExchangeRate->buy_in,
                    'sell_out'=>$ExchangeRate->sell_out,
                    // 'rate'=>$ExchangeRate->rate
                );
            }

            $data['exchange_rate'] = array(
                'master_currency' => $MasterCurrency,
                'exchange_rate' =>$ExchangeRateArray
            );

            return response()->json($data);
        }
    }