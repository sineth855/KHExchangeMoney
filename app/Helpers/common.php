<?php 	
	
	namespace App\Helpers;
	use App\Http\Controllers\Controller;
	use App\Http\Models\MoneyTransfer\MerchantAccount\Account;
	use App\Http\Models\MoneyTransfer\Currency\ExchangeRate;
	use App\Http\Models\MoneyTransfer\Currency\Currency;
	use App\Http\Models\MoneyTransfer\MerchantAccount\AccountCredit;
	use App\Http\Models\MoneyTransfer\MerchantAccount\AccountMobileLog;
	use App\Http\Models\MoneyTransfer\MerchantAccount\AccountTransaction;
	use App\Http\Models\MoneyTransfer\MerchantAccount\AccountNotification;
	use App\Models\POS\POSCusOrder;
	use Carbon\Carbon;
	use DB;
	use Validator;
	use Auth;
	use Session;
	use Helpers;

	class common{

		public static function getTotalSale(){
			$current_date=date('Y-m-d');
			$result = POSCusOrder::select(DB::raw('SUM(sub_total_amount)+(SUM(sub_total_amount)/100*vat_percentag)-(SUM(sub_total_amount)/100*discount) as total_sales'),'vat_percentag','discount')
									->Where('check_out_date',"LIKE",'%'.$current_date.'%')
					                ->Where('is_void',0)
					                ->Where('status_id',4)
					                ->first();
			$total = floatval($result->total_sales);
			//+floatval($result->discount)+floatval($result->vat_percentag)
			return common::FormatCurrentcy($total);
		}

		public static function generateMerchantID(){
			$generateMerchanID = rand(10000000, 99999999);
			return $generateMerchanID;
		}
		// Process Credit Balance Base Account
		public static function DoCreditAccountBaseCurrency($account_id,$req_amount,$message,$transaction_type){
			$boolen = true;
			$Account = Account::where('account_id',$account_id)->first();
			$TotalCredit = $Account->credit_amount;
			$cal_amount = floatval($TotalCredit) + floatval($req_amount);
			if($Account){
				$sql = Account::where('account_id',$Account->account_id)->update(['credit_amount'=>$cal_amount]);
				AccountTransaction::create([
					'account_master_id' => $Account->account_master_id,
					'account_no' => $Account->account_no,
					'transaction_type' => $transaction_type,
					'transaction_rule' => $Account->TransactionRule->transaction_rule_name,
					'transaction_no' => "TR-".rand(20000000, 99999999),
					'currency' => $Account->Currency->title,
					'req_amount' => $req_amount,
					'transaction_detail' => $message,
					'transaction_date' => date("Y-m-d H:i:s")
				]);
				$boolen = true;
			}else{
				$boolen = false;
			}
			return $boolen;
		}
		// 
		public static function generateSecureNum(){
            $random = rand(100000, 999999);
            return config_receipt_prefix.$random;
        }
		// do transaction by account
		public static function DoTransactionByAccount($account_master_id,$account_no,$req_amount,$message,$transaction_type){
			$Account = Account::where('account_no',$account_no)->first();
			$sql = AccountTransaction::create([
				'account_master_id' => $account_master_id,
				'account_no' => $account_no,
				'transaction_type' => $transaction_type,
				'transaction_rule' => $Account->TransactionRule->transaction_rule_name,
				'transaction_no' => "TR-".rand(20000000, 99999999),
				'currency' => $Account->Currency->title,
				'req_amount' => $req_amount,
				'transaction_detail' => $message,
				'transaction_date' => date("Y-m-d H:i:s")
			]);
			$boolen = true;
			if($sql){
				$TotalCredit = $Account->credit_amount;//common::calculateAcountCredit($Account->account_id);
				$cal_amount = floatval($TotalCredit) - floatval($req_amount);
				Account::where('account_id',$Account->account_id)->update(['credit_amount'=>$cal_amount]);
				$boolen = true;
			}else{
				$boolen = false;
			}
			return $boolen;
		}
		// DoUpdateAccountTransferTo
		public static function DoUpdateAccountTransferTo($transferee_account_no,$req_amount){
			$Account = Account::where('account_no',$transferee_account_no)->first();
			$boolen = true;
			if($Account){
				$TotalCredit = $Account->credit_amount;//common::calculateAcountCredit($Account->account_id);
				$cal_amount = floatval($TotalCredit) + floatval($req_amount);
				Account::where('account_id',$Account->account_id)->update(['credit_amount'=>$cal_amount]);
				$boolen = true;
			}else{
				$boolen = false;
			}
			return $boolen;
		}
		// Save Notification
		public static function SaveNotification($noti_type,$account_no,$noti_title,$trans_amount,$currency,$trans_detail){
			$sql = AccountNotification::create([
				'notification_type' => $noti_type,
				'account_no' => $account_no,
				'notification_title' => $noti_title,
				'transaction_amount' => $trans_amount,
				'currency' => $currency,
				'transaction_detail' => $trans_detail,
				'transaction_date' => date("Y-m-d H:i:s")
			]);
			$boolen = true;
			if($sql){
				$boolen = true;
			}else{
				$boolen = false;
			}
			return $boolen;
		}
		// Save transaction to account mobile log
		public static function AccountMobileLog($merchant_id,$token){
			$AccountMobileLog = AccountMobileLog::create([
				'merchant_no' => $merchant_id,
				'token_id' => $token,
				'ip' => '192.168.160.100',
				'expire_date' => date('Y-m-d H:i:s'),
				'security_key' => '123456',//$this->generateSecureNum(),
				'date_added ' => date('Y-m-d H:i:s')
			]);
			$security_number = $AccountMobileLog->security_key;
			return $security_number;
		}
		
		// calculateSaveAccount
		public static function calculateSaveAccount($account_no,$amount){
			$Account = Account::where('account_no',$account_no)->where('is_active',1)->first();
			$boolen = false;
			$cal_amount = 0;
			$TotalCredit = 0;
			if($Account){
				$TotalCredit = $Account->credit_amount;//common::calculateAcountCredit($Account->account_id);
				if(floatval($amount) <= floatval($TotalCredit)){
					// AccountCredit::where('account_id',$Account->account_id)->update(['credit_amount'=>$cal_amount]);
					$boolen = true;
				}else{
					$boolen = false;
				}
			}else{
				$boolen = false;
			}
			return $boolen;
		}

		// calculateExchangeCurrencyAccount
		public static function calculateExchangeCurrencyAccount($account_no,$receiving_currency,$sending_amount){
			$Account = Account::where('account_no',$account_no)->where('is_active',1)->first();
			$boolen = false;
			$cal_amount = 0;
			$TotalCredit = 0;
			$default_dolar = 1;
			$CreditBalance = common::convertCurrency($Account->currency_id,$Account->credit_amount);
			if($Account->currency_id==$receiving_currency){
				$TotalCredit = $Account->credit_amount;//common::calculateAcountCredit($Account->account_id);
				if(floatval($sending_amount) <= floatval($TotalCredit)){
					// AccountCredit::where('account_id',$Account->account_id)->update(['credit_amount'=>$cal_amount]);
					$boolen = true;
				}else{
					$boolen = false;
				}
			}else{
				$CompareBalance = common::convertCurrency($receiving_currency,$sending_amount);
				if(floatval($CompareBalance) <= floatval($CreditBalance)){
					$boolen = true;
				}else{
					$boolen = false;
				}
			}
			return $boolen;
		}

		public static function convertCurrency($currency_id,$compare_amount){
			$currency = Currency::where('currency_id',1)->first();
			$ExchangeRate = ExchangeRate::where('to_currency_id',$currency_id)->first();
			$new_amount = floatval($compare_amount)/floatval($ExchangeRate->buy_in);
			return $new_amount;
		}
		public static function getAmountConvertCurrency($transaction_amount,$default_currency_id,$config_currency){
			$total = 0;
			$default_exchange_dollar = 1;
			$ExchangeRateDefault = ExchangeRate::where('to_currency_id',$default_currency_id)->first();
			$ExchangeRateConvert = ExchangeRate::where('to_currency_id',$config_currency)->first();
			$total = (floatval($transaction_amount) / floatval($ExchangeRateConvert->rate)) * floatval($ExchangeRateDefault->rate);
			return $total;
		}
		public static function CalculateExchangeRate($transaction_amount,$default_currency_id,$convert_currency){
			$total = 0;
			$ExchangeRateDefault = ExchangeRate::where('to_currency_id',$default_currency_id)->first();
			$ExchangeRateConvert = ExchangeRate::where('to_currency_id',$convert_currency)->first();
			$total = (floatval($transaction_amount) / floatval($ExchangeRateDefault->rate)) * floatval($ExchangeRateConvert->rate);
			return $total;
		}
		public static function CalculateExchangeRateBaseAccount($transaction_amount,$default_currency_id,$convert_currency){
			$total = 0;
			$ExchangeRateDefault = ExchangeRate::where('to_currency_id',$default_currency_id)->first();
			$ExchangeRateConvert = ExchangeRate::where('to_currency_id',$convert_currency)->first();
			$total = (floatval($transaction_amount) / floatval($ExchangeRateDefault->rate)) * floatval($ExchangeRateConvert->rate);
			return $total;
		}

		// calculateAcountCredit
		public static function calculateAcountCredit($account_id){
			$total = 0;
			$AccountCredits = AccountCredit::where('account_id',$account_id)->get();
			foreach($AccountCredits as $row){
				$total += $row->credit_amount;
			}
			return $total;
		}

		public static function getReilFraction(){
			$Currency = Currency::Where('id',2)->Select('exchange_rate')->first();
			return $Currency->exchange_rate;
		}
		public static function getDiscountPermission($table_field=0){
			$DiscountPermission = DiscountPermission::Where('user_id',Auth::user()->id)->first();
			if($DiscountPermission){
				return $DiscountPermission->$table_field;
			}else{
				return 0;
			}
		}
		
		public static function getCurrencyDefault($table_field){
	      $Currency = Currency::Where('is_default',1)->first();
	      return $Currency->$table_field;
	    }
		// formatCurrentcy
		public static function FormatCurrentcy($price){
			$Currency = Currency::Where('is_default',1)->first();
			$exchange_rate = $Currency->exchange_rate;
			$fraction_unit = $Currency->fraction_unit;
			$total = floatval($price) * ($exchange_rate * $fraction_unit);
			// $decimal = str_split($total, "3");
			$decimals = number_format($total,2);
			// number_format($number, 2, ',', ' ');
			$result = $Currency->currency_sign.' '.$decimals;
			return $result;
		}
		// getMenu
		public static function getMenus($language_id=1,$menu_type_id=1){
			$menu = array();
			$Group_ID = 1;//Auth::user()->group_id;
			$parent_menus = DB::table("group_role as gr")
				->JOIN('group_role_detail as grd', 'grd.group_role_id', '=', 'gr.id')
				->JOIN('menu as m','m.menu_code','=','grd.menu_code')
				->JOIN('menu_description as md','m.id','=','md.menu_id')
				->WHERE('m.is_active',1)
				->WHERE('gr.group_id','=',$Group_ID)
				->WHERE('m.parent_id',0)
				->WHERE('m.menu_type_id',$menu_type_id)
				->WHERE('md.language_id',$language_id)
				->SELECT('grd.menu_id as groupMenu_id','m.url as p_url','gr.id as grouRole_id','m.fa_icon as fa_icon','m.default as default','m.menu_link as p_menu_link','m.menu_code as menu_code','md.name as parent_menu_name','m.id as parent_menu_id')
				->OrderBy('m.ordering')
				->get();

			foreach ($parent_menus as $parent_menu) {
			  	$groupMenu_id = $parent_menu->groupMenu_id;
            	$grouRole_id = $parent_menu->grouRole_id;

				$children_menu = array();
				$children = DB::table("menu as m")
							->JOIN('menu_description as md','m.id','=','md.menu_id')
							->JOIN('group_role_detail as grd','grd.menu_id','=','m.id')
							->WHERE('m.is_active',1)
							->WHERE('grd.group_role_id','=',$grouRole_id)
							->WHERE('m.parent_id','=',$groupMenu_id)
							->WHERE('m.parent_id','>',0)
							->WHERE('m.parent_id',$parent_menu->parent_menu_id)
							->WHERE('md.language_id',$language_id)
							->SELECT('m.menu_code as menu_code','m.menu_link as c_menu_link','md.name as child_menu_name')
							->OrderBy('m.ordering')
							// ->OrderBy('md.name')
							->get(); 

				foreach ($children as $child) {
					$children_menu[] = array(
						'child_menu_name' => $child->child_menu_name,
						'c_menu_link' => $child->c_menu_link,
						'menu_code' => $child->menu_code,
					);
				}

				$menu[] = array(
					'parent_menu_id'=>$parent_menu->parent_menu_id,
					'parent_menu_name' => $parent_menu->parent_menu_name,
					'fa_icon' => $parent_menu->fa_icon,
					'default' => $parent_menu->default,
					'menu_code' => $parent_menu->menu_code,
					'p_menu_link' => $parent_menu->p_menu_link,
					'p_url' => $parent_menu->p_url,
					'children_menu' => $children_menu
				);
			}
			return $menu;
		}
		public static function getMenusName($language_id=2,$menu_code){
			
			$data = DB::table("menu_description as md")
							->join("menu as m",'m.id','=','md.menu_id')			  				
							->WHERE('m.menu_code',$menu_code)							
							->WHERE('md.language_id',$language_id)
							->SELECT('md.name','md.menu_id')							
							->get();
			if(sizeof($data)>0)
				return $data[0]->name; 
			else return '';
		}
		public static function getMenusTitle($language_id=2,$menu_code){
			
			$data = DB::table("menu_description as md")
							->join("menu as m",'m.id','=','md.menu_id')			  				
							->WHERE('m.menu_code',$menu_code)							
							->WHERE('md.language_id',$language_id)
							->SELECT('md.name','md.meta_description','md.menu_id')							
							->get();
			if(sizeof($data)>0)
				if($data[0]->meta_description!='')
					return $data[0]->meta_description; 
				else
					return $data[0]->name; 

			else return '';
		}
		
		public static function getLanguage(){
			$lang = DB::table("language")->Where('is_delete',0)->Where('is_active',1)->OrderBy('order_level','ASC')->get();
			return $lang;
		}

		// substring formart
		public static function substrTime($time){
			$newTime = date(DEFAULT_TIME_FORMAT,strtotime($time));
	        //$replace = str_replace(':', '', $time);
	        //$newTime = substr($replace,0,4);
	        return $newTime;
	    }
	    public static function substrTimeInput($time){
			//$newTime = date(DEFAULT_TIME_FORMAT,strtotime($time));
	        $replace = str_replace(':', '', $time);
	        $newTime = substr($replace,0,4);
	        return $newTime;
	    }
	    
	    // substring formart
		public static function FormatDate($datetime){
			$newDate = date("d-M-Y", strtotime($datetime));
	        return $newDate;
	    }
	    public static function FormatDateTime($datetime){
			$newDate = date(DATE_TIME_FORMAT, strtotime($datetime));
	        return $newDate;
	    }
	    public static function FormatDateSql($datetime){
			$newDate = date("Y-m-d", strtotime($datetime));
	        return $newDate;
	    }


	    public static function getLayoutNotification(){

	    	$user_id = Auth::user()->id;
			$html ='';
			$notifications = DB::table('notification as n')
	                ->join('notification_users as nu','nu.notification_id','=','n.id')
	                ->join('notification_group as ng','ng.id','=','n.notification_group_id')
	                ->where('nu.user_id',$user_id)
	                ->where('nu.is_read',0)
	                ->OrderBy('id','desc')
	                ->Limit(4)
	                ->select('n.id as id',
	                		'ng.icon as icon',
	                		'n.date_added as date_added',
	                		'n.notification_group_id as notification_group_id',
	                		'ng.notification_group as notification_group_name',
	                		'n.notification_name as notification_name',
	                		'n.url as url') 
	                ->get();


	    	foreach($notifications as $notification){
	    	  // $getLayoutRealTime = new getLayoutRealTime($notification->date_added); 
	          $html .='<li>';
	            $html .='<a href="'.SITE_HTTP_URL.$notification->url.'">';
	              $html .='<span class="image">';
	                $html .='<i class="'.$notification->icon.'"></i> &nbsp;';
	                // $html .='<img src="'.SITE_HTTP_URL.'images/no-image.png" alt="Profile Image"/>';
	              $html .='</span>';
	              $html .='<span>';
	                $html .='<span>'.$notification->notification_group_name.'</span>';
	                // $html .='<span class="time">'.common::getLayoutRealTime($notification->date_added).'</span>';
	              $html .='</span>';
	              $html .='<span class="message">';
	                $html.= '<i class="fa fa-wa fa-clock-o"></i> ';
	                $html .= common::CalRealTime($notification->date_added);
	                $html .= '<p><i class="fa fa-wa fa-pencil"></i> '.$notification->notification_name.'</p>';
	              $html .='</span>';
	            $html .='</a>';
	          $html .='</li>';
	        }

	        $html .='<li>';
	          $html .='<div class="text-center">';
	            $html .='<a href="'.SITE_HTTP_URL.'admin/notification?menuCode=y5_m12">';
	              $html .='<strong>See All Alerts</strong>';
	              $html .='<i class="fa fa-angle-right"></i>';
	            $html .='</a>';
	          $html .='</div>';
	        $html .='</li>';

	        return $html;
	    }

	    public static function CalRealTime($date_add){
	    	$str_date = '';
	        $currently_date = date('Y-m-d H:i:s');
	        $uploaded_date = $date_add;
	        $diff = abs(strtotime($currently_date) - strtotime($uploaded_date)); 
	        $years   = floor($diff / (365*60*60*24)); 
	        $months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
	        $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
	        $hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60));
	        $minutes = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 
	        $seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60)); 
	        if($years == 0 && $months == 0 && $days == 0 && $hours == 0 && $minutes == 0) {
	          $str_date .= $seconds." seconds ago.";
	        }elseif($years == 0 && $months == 0 && $days == 0 && $hours == 0) {
	          $str_date .= $minutes." Minutes and ".$seconds." seconds ago.";
	        }elseif($years == 0 && $months == 0 && $days == 0) {
	          $str_date .= $hours." hours and ".$minutes." minutes ago.";
	        }elseif($years == 0 && $months == 0) {
	          $str_date .= $days." days and ".$hours." hours ago.";
	        }elseif($years == 0) {
	          $str_date .= $months." months and ".$days." days ago.";
	        }else{
	          $str_date .= $years." years and ".$months." months ago.";
	        }
	        return $str_date;
	    }

	    public static function getNumNotification(){
	    	$total_notif = count(DB::table('notification_users')->where('user_id',Auth::user()->id)->where('is_read',0)->get());
	    	return $total_notif;
	    }
	    // notification
	    public static function notification(){
			$user_id = Auth::user()->id;
			$notifications = DB::table('notification as n')
			            ->join('notification_users as nu','nu.notification_id','=','n.id')
			            ->join('notification_group as ng','ng.id','=','n.notification_group_id')
			            ->where('nu.user_id',$user_id)
			            ->where('nu.is_read',0)
			            ->OrderBy('id','desc')
			            ->select('n.id as id',
			            		'ng.icon as icon',
			            		'n.date_added as date_added',
			            		'n.notification_group_id as notification_group_id',
			            		'ng.notification_group as notification_group_name',
			            		'n.notification_name as notification_name',
			            		'n.url as url') 
			            ->get();

			return $notifications;
	    }

	    //sum 2 times 
	    //leak add
	    public static function SumTime ($time1, $time2) {
			$t1=explode(":",$time1);
			$t2=explode(":",$time2);
			//dd($t2);
			$hours=$t1[0]+$t2[0];
			$minutes=$t1[1]+$t2[1];
			if($minutes > 59){
				$minutes=$minutes-60;
				$hours++;
			}
			if($minutes < 10){
				$minutes = "0".$minutes;
			}
			if($minutes == 0){
				$minutes = "00";
			}
			if($hours < 10){
				$hours = "0".$hours;
			}
			if($hours == 0){
				$hours = "00";
			}
			$sum=$hours.':'.$minutes;
			return $sum;
		}
		public static function TotalTimeFormat($time) {
			//echo $time;
			if(TOTAL_TIME_FORMAT=='H:i') return $time;
			else return str_replace(':','',$time);
			
		}

		//////////////////////////////////////////////////////////////////////
		//PARA: Date Should In YYYY-MM-DD Format
		//RESULT FORMAT:
		// '%y Year %m Month %d Day %h Hours %i Minute %s Seconds'        =>  1 Year 3 Month 14 Day 11 Hours 49 Minute 36 Seconds
		// '%y Year %m Month %d Day'                                    =>  1 Year 3 Month 14 Days
		// '%m Month %d Day'                                            =>  3 Month 14 Day
		// '%d Day %h Hours'                                            =>  14 Day 11 Hours
		// '%d Day'                                                        =>  14 Days
		// '%h Hours %i Minute %s Seconds'                                =>  11 Hours 49 Minute 36 Seconds
		// '%i Minute %s Seconds'                                        =>  49 Minute 36 Seconds
		// '%h Hours                                                    =>  11 Hours
		// '%a Days                                                        =>  468 Days
		//////////////////////////////////////////////////////////////////////
		public static function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
		{
		    $datetime1 = date_create($date_1);
		    $datetime2 = date_create($date_2);
		   
		    $interval = date_diff($datetime1, $datetime2);
		   
		    return $interval->format($differenceFormat);
		   
		} 		


		
		// $uploaded_date = $row['upload_time'];
		// $currently_date = $create_date;

		public static function RealtimeCheckDate($uploaded_date){
			$currently_date = date('Y-m-d H:i:s');
			
			$diff = abs(strtotime($currently_date) - strtotime($uploaded_date)); 
			$years   = floor($diff / (365*60*60*24)); 
			$months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
			$days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
			$hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60));
			$minutes = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 
			$seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60)); 
			if($years == 0 && $months == 0 && $days == 0 && $hours == 0 && $minutes == 0) {
				return $seconds." seconds ago.";
			}elseif($years == 0 && $months == 0 && $days == 0 && $hours == 0) {
				return $minutes." Minutes and ".$seconds." seconds ago.";
			}elseif($years == 0 && $months == 0 && $days == 0) {
				return $hours." hours and ".$minutes." minutes ago.";
			}elseif($years == 0 && $months == 0) {
				return $days." days and ".$hours." hours ago.";
			}elseif($years == 0) {
				return $months." months and ".$days." days ago.";
			}else{
				return $years." years and ".$months." months ago.";
			}
		}
		
	/*
		 * strTime formate must be h:m AM
		 */
		public static function getTime($strTime){
			$text = explode(":",$strTime);
			$subText = explode(" ", $text[1]);
			$hour = (int)$text[0];
			$minute = (int)$subText[0];
			$format = strtoupper($subText[1]);
			if($format == "PM" && $hour != 12){
				$hour += 12;
			}
			$time = array(
					'hour'=> $hour,
					'minute'=> $minute
			);
			return $time;
		}
		//utc Duration 
		public static function utcTimeDuration($location_id)
		{
		    
        	$localTime = DB::table('flight_location as l')
        					->join('utctimezone as t','l.utctimezone_id','=','t.id')
        					->select('value')
        					->where('l.id',$location_id)
        					->get();
		   	foreach ($localTime as $d) {
		   		return $d->value;
		   	}
		    
		} 
		//convert time 
		public static function ConvertTime($d='date',$contvert_to='LC', $location_id, $date,$time) {
			$duration = helpers::utcTimeDuration($location_id);
          	$nowtime = $date.' '.$time;

          	if(($d=='date')&&($date==''||$date=='0000-00-00')) return '';
	        else if(($d=='time')&&($time==''||$time=='00:00:00')) return '';

          	if((DEFAULT_UTC_TIME==1)&&($contvert_to=='UTC')){
          		if($d=='date') return date(DEFAULT_DATE_FORMAT,strtotime($date));
          		else return date(DEFAULT_TIME_FORMAT,strtotime($time));
          	}else{
	          	$new_datetime = date('Y-m-d H:i:s', strtotime($nowtime . " $duration hours"));
		       	
		        $new_date = date(DEFAULT_DATE_FORMAT,strtotime($new_datetime));
		        $new_time = date(DEFAULT_TIME_FORMAT,strtotime($new_datetime));

		        if($d=='date') return $new_date;
		        else return $new_time;
		        
          	}
		}
		//check day or night time 
		public static function CheckDayNight($time){
			$day_time = strtotime(DAY_TIME);
            $night_time = strtotime(NIGHT_TIME);

            $time = substr($time, 0,2).':'.substr($time, 2,2);
            $this_time = strtotime($time);
            
            if(($this_time>=$night_time)||($this_time<$day_time)) return '<i class="fa fa-moon-o"></i><span style="display:none;">0</span>';
            else return '<i class="fa fa-sun-o"></i><span style="display:none;">1</span>';
		}
		//convert time from second
		public static function ConvertTimeSecond($seconds){			
			$h=0;
			$i=0;
			$s=0;
			if($seconds>=3600){
				$h = floor($seconds/3600);
				$seconds = $seconds - (3600*$h);

				if($seconds>=60){
					$i = floor(($seconds/60));
				}
				if($h<10) $h='0'.$h;
				if($i<10) $i='0'.$i;
				$time = $h.':'.$i;
			}else if($seconds>=60){
				$i = floor(($seconds/60));				
				if($i<10) $i='0'.$i;
				$time = '00:'.$i;
			}else{
				$time = '00:00';
			}

	        return $time;
	  }
	
	
	}
?>