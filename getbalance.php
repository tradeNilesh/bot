<?php 
//require_once('mobile_common_new.php');  
// $keyworddetails= $_REQUEST['keyworddetails'];
// $printflag= $_REQUEST['printflag'];

// $AccountID= $_REQUEST['AccountID'];
// $profileID= $_REQUEST['profileID'];
// $text= $_REQUEST['text'];

echo $data = $text= $_REQUEST['data']; 


if(isset($data) && $data != null){
	echo "---------plan--------";
	echo $planText = decrypt($data);
	echo "---------plan end--------";
	if(isset($planText) && $planText!=false) {
		$paramArray     = explode('|',$planText);
		$keyworddetails = isset($paramArray[0]) ? $paramArray[0] : "";
		$printflag	   	= isset($paramArray[4]) ? $paramArray[4] : "";
		$AccountID		= isset($paramArray[2]) ? $paramArray[2] : "";
		$profileID		= isset($paramArray[1]) ? $paramArray[1] : "";
		$text			= isset($paramArray[3]) ? $paramArray[3] : "";
	} else {
		echo "Authentication Fail";
		exit();
	}
}


////-----------------------Mysql escape string------------------------------------------/////
$keyworddetails = isset($keyworddetails) ? mysql_real_escape_string($keyworddetails) : $keyworddetails;
$printflag		= isset($printflag) ? mysql_real_escape_string($printflag) : $printflag;
$AccountID		= isset($AccountID) ? mysql_real_escape_string($AccountID) : $AccountID;
$profileID		= isset($profileID) ? mysql_real_escape_string($profileID) : $profileID;
$text			= isset($text) ? mysql_real_escape_string($text) : $text;
////-----------------------Mysql escape string------------------------------------------/////



$sql1 = "SELECT * from mt4_servers where id = ".$AccountID;
$sql_get1 = $mDatabase->ExecuteQuery($sql1);
$qry_res = $mDatabase->FetchArray($sql_get1);
 

$mt4_account = $qry_res['mt4_account'];
$default_symbol = $qry_res['default_symbol'];
$user_profile_id = $qry_res['user_profile_id'];
$MsSQL_AccountID = $qry_res['MsSQL_AccountID'];
$MsSQL_ManagerID = $qry_res['MsSQL_ManagerID'];
$account_no = $qry_res['mt4_account'];
$broker_id = $qry_res['broker_id'];
$total_return = $qry_res['total_return'];
$latest_balance = $qry_res['latest_balance'];
$mt4_id = $qry_res['id'];
$max_dd = $qry_res['max_dd_percentage'];

switch ($keyworddetails) 
{
	case 'balance':
		$equity_balance_RTAA_arr= get_equity_balance_RTAA($mt4_account,$MsSQL_ManagerID,$MsSQL_AccountID);
		$accbalance= $equity_balance_RTAA_arr['latest_balance'];
 		echo  $msg = "Your balance of Account Number $mt4_account is ".$accbalance." $default_symbol.";
		break;
		
	case 'equity':
		$equity_balance_RTAA_arr= get_equity_balance_RTAA($mt4_account,$MsSQL_ManagerID,$MsSQL_AccountID);
		$equity = $equity_balance_RTAA_arr['latest_equity'];
 		 echo     $msg = "Your equity of Account Number $mt4_account is ".$equity." $default_symbol.";	
		 break;
		 
	case 'activeallocations':
		$sql1 = "SELECT count(id) from trade_copy_user_map where follower_profile_id = ".$mt4_id;
		$sql_get1 = $mDatabase->ExecuteQuery($sql1);
		$activeallocations = $mDatabase->NumRows($sql_get1);
		echo  $msg = "The total number of allocations are  $activeallocations";	
		break;
		
	case 'totalinvested':
		$allocated_fund = getAllotedAmountOfSlave($MsSQL_AccountID);
		echo  $msg = "The total amount invested is  $allocated_fund";	
		break;
		
		
	case 'availableinvested':
		 $allocated_fund = getAllotedAmountOfSlave($MsSQL_AccountID);   
		 $unallocated_fund = $accbalance - $allocated_fund;
		 echo  $msg = "The total amount to be invested is $unallocated_fund";	
		 break;

	case 'returnofinvestment':
		 echo  $msg = "The return of investment on your account is  $total_return";	
		 break;

	case 'maxdd':
		 echo   $msg = "The maximum drawdown on your account is $max_dd";
		 break;

    case 'closedposition':
		$sel_close = "SELECT count(orderid) AS cnt_order, SUM(avg_pl) AS pnl FROM `mt4_closed_orders` where FK_ManagerID='$MsSQL_ManagerID' and account_no=$account_no";
		$counter=0;
		$sel_close_result = $mDatabase->ExecuteQuery($sel_close);	
		while ($sel_result_row = $mDatabase->FetchArray($sel_close_result)) {
				$sel_close_num = $sel_result_row['cnt_order'];
				$pnl = $sel_result_row['pnl'];
				$counter=$counter+1;
		}	
		if($counter==0)
			$msg="Sorry ! You dont have open orders";
		else
		   $msg = "You have $sel_close_num with a total Profit of $pnl";
		
		echo $msg ;
		break;

    case 'openposition':
 		$sel_open ="SELECT count(orderid) AS cnt_order, SUM(avg_pl) AS pnl FROM `mt4_open_orders` where FK_ManagerID='$MsSQL_ManagerID' and mt4_account_id=$account_no";
		$counter=0;
		$sel_open_result = $mDatabase->ExecuteQuery($sel_open);	
		while ($sel_result_row = $mDatabase->FetchArray($sel_open_result)) {
				$sel_open_num = $sel_result_row['cnt_order'];
				$pnl = $sel_result_row['pnl'];
				$counter=$counter+1;
		}
		if($counter==0)
			$msg="Sorry ! You dont have open orders";
		else
			$msg = "You have $sel_open_num with a total Profit of $pnl";
		echo $msg ;
		
		break;

    case 'mysummary':
		 echo $msg = "Open Summary Tab";
		 break;

		
	case 'switchaccount':
		$profileID= $_REQUEST['profileID'];

		$sql1 = "SELECT * from mt4_servers where user_profile_id = ".$profileID." and id!= ".$mt4_id." order by id asc ";
		$sql_get1 = $mDatabase->ExecuteQuery($sql1);
		$msg ="Please choose the account ";
		$acccounter=0;
		$letters = range('A', 'Z'); 
		//print_r($letters);
		while($qry_res = $mDatabase->FetchArray($sql_get1))
		{
			$mt4_account = $qry_res['mt4_account'];
			
			
			$msg .= "Option ". $letters[$acccounter].":".$mt4_account." ";
			$acccounter=$acccounter + 1;
		}
		
		if($acccounter==0)
		  $msg  = "You just have one account which is already selected";
		 
		 
		 //setDefaultMt4Account($mDatabase, $mt4_id );
	     echo $msg ;
		 break;		 

	case 'setoption':
		$profileID= $_REQUEST['profileID'];
		$accnumbertext= explode("option ",$_REQUEST['text']);
		//print_r($accnumbertext);
		$accnumbertext1= explode(" ==",$accnumbertext[1]);
		//print_r($accnumbertext1);
		$text = $accnumbertext1[0] ;
		$letters = range('A', 'Z'); 
		$letters=array_flip($letters);
		//print_r($letters);
		$key =$letters[strtoupper($text)];
		//echo $key;
		//$text=trim(str_ireplace('option','',$text));
		$sql1 = "SELECT * from mt4_servers where user_profile_id = ".$profileID." and id!= ".$mt4_id." order by id asc limit $key, 1 ";//and id!= ".$mt4_id."
		//echo $sql1;
		$sql_get1 = $mDatabase->ExecuteQuery($sql1);
		$msg ="Please choose the account ";
		$acccounter=0;
		 
		$acc_array = array();
		while($qry_res = $mDatabase->FetchArray($sql_get1))
		{
			$acc_array[] = $qry_res['mt4_account'];
			$accid_array[] = $qry_res['id'];
		}
		if(count($acc_array)>0)
		{
			setDefaultMt4Account($mDatabase, $accid_array[0] );
			$mt4_id=$accid_array[0] ;
			$msg = "Account " .$acc_array[0]." has been set successfully ";
		}
		else
		{
			$msg = "Please select valid option.";
		}
		//print_r($acc_array);
		
		echo $msg ;
		 break;		 

}



function decrypt($cipher, $key = null, $hmacSalt = null) {
	echo "-----------inside decrypt------------";
	# Private salt
	$salt = 'ZfTfbip&Gs0Z4yz3ZfTfbip&Gs0Z4yz3';
	# Private key
	$key =  'SDefrfdrghgfdfE)SDefrfdrghgfdfE)';
	 
	if (empty($cipher)) {
		echo 'The data to decrypt cannot be empty.'; die();
	}
	if ($hmacSalt === null) {
		$hmacSalt = $salt;
	}
	//echo "----------cipher text------------";
	//$ciphertext = base64_decode($cipher);
	echo $cipher;
	//echo $cipher = strtr($cipher, '-_,' , '+/=');
	//echo "----------cipher text end------------";
	$c = base64_decode($cipher);
	$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
	$iv = substr($c, 0, $ivlen);
	$hmac = substr($c, $ivlen, $sha2len=32);
	$ciphertext_raw = substr($c, $ivlen+$sha2len);
	$original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
	$calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
	if (hash_equals($hmac, $calcmac))//PHP 5.6+ timing attack safe comparison
	{
		echo $original_plaintext."\n";
	}

	return $original_plaintext;
	
}


?>