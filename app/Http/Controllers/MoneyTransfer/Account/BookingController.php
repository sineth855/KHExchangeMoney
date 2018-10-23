<?php

    namespace App\Http\Controllers\MoneyTransfer\Account;

    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use App\Http\Models\MoneyTransfer\MerchantAccount\AccountMaster;
    use App\Http\Models\MoneyTransfer\Booking\Booking;
    use App\Http\Models\MoneyTransfer\Booking\BookingType;
    use App\Http\Models\MoneyTransfer\MerchantAccount\Account;
    use Validator;
    use Carbon\Carbon;
    use DB;
    use Illuminate\Support\Facades\Auth;
    use Session;
    use App\Helpers\common;
    
    class BookingController extends Controller
    {
        /**
         * Display a listing of the BestSeller Create On 01-02-2018
         *
         * @return \Illuminate\Http\Response
         */
        
        public function booking(Request $request){
            $input = $request->all();
            $user = Auth::user();
            $checkHasAccount = Account::checkHasAccount($input['account_id']);
            if($checkHasAccount){
                // dd(Account::getCurrencyByAccountId($input['account_id'],'code'));
                $account_no = Account::fetchAccountById($input['account_id'],'account_no');
                $AccountMaster = AccountMaster::select('account_master_id')
                                                ->where('user_id',$user->id)
                                                ->first();
                $input['account_master_id']=$AccountMaster->account_master_id;
                $input['date_added'] = date("Y-m-d H:i:s");
                $input['sector'] = $input['origin'].' - '.$input['destination'];
                $input['booking_date'] = date("Y-m-d H:i:s");
                $input['date_modified'] = date("Y-m-d H:i:s");
                $input['status']=0;
                $input['currency'] = Account::getCurrencyByAccountId($input['account_id'],'code');
                $input['account_master_id'] = $AccountMaster->account_master_id;
                $Booking = Booking::create($input);
                $input['success'] = true;
                $input['message'] = "Booking Successfully.";
                $input['booking'] = $Booking;
                $message = "Do Bookings";
                $transaction_type = 'Do Bookings';
                $sending_amount = 0;
                common::DoTransactionByAccount($AccountMaster->account_master_id,$account_no,$sending_amount,$message,$transaction_type);
                return response()->json($input, 200);
            }else{
                $data['success'] = false;
                $data['message'] = "Booking fail, no such account!";
                $data['transfer_info'] = $input;
                return response()->json($data, 404);
            }
        }

        public function getBookingType(){
            $BookingTypes=BookingType::all();
            return response()->json(['success'=>true,'message'=>'Success','data'=>$BookingTypes],200);
        }
    }