<?php 

$profileID = "22319";
$AccountID = "32707";
 
postApi("balance" , $profileID , $AccountID);

$method = $_SERVER['REQUEST_METHOD'];

function postApi($action,$profileID,$AccountID)
{
 
	$domain = "https://tradebottest.herokuapp.com/test/getbalance.php";
 
	if ($action == "balance" && isset($AccountID) && isset($profileID)) {
		$plain = "balance|".$profileID."|" ."$AccountID";
		$request = encrypts($plain);
		$request = strtr($request, '+/=', '-_,' );
		$curlURL = $domain."?data=".$request;
	}	
	 
	if ($action == "equity" && isset($profileID) && isset($AccountID)) {
		$plain = "equity|".$profileID."|" ."$AccountID";
		$request = encrypts($plain);
		$request = strtr($request, '+/=', '-_,' );
		$curlURL = $domain."webservices_new/getbalance.php?data=".$request;
	}	
  
	if ( $action == "switchaccount" && isset($profileID) && isset($AccountID)  ) {
		$plain = "switchaccount|".$profileID."|" ."$AccountID";
		$request = encrypts($plain);
		$request = strtr($request, '+/=', '-_,' );
		$curlURL = $domain."webservices_new/getbalance.php?data=".$request;
	}
	
	if ($action == "activeallocations" && isset($profileID) && isset($AccountID)){
		$plain = "activeallocations|".$profileID."|" ."$AccountID";
		$request = encrypts($plain);
		$request = strtr($request, '+/=', '-_,' );
		$curlURL = $domain."webservices_new/getbalance.php?data=".$request;
	}	
	 
	if ($action == "mysummary" && isset($profileID) && isset($AccountID)){
		$plain = "mysummary|".$profileID."|" ."$AccountID";
		$request = encrypts($plain);
		$request = strtr($request, '+/=', '-_,' );
		$curlURL = $domain."webservices_new/getbalance.php?data=".$request;
	}
  
	if ($action == "setoption" && isset($profileID) && isset($AccountID)){
		$plain = "setoption|".$profileID."|" ."$AccountID";
		$request = encrypts($plain);
		$request = strtr($request, '+/=', '-_,' );
		$curlURL = $domain."webservices_new/getbalance.php?data=".$request;
	}
	
	if ($action == "openposition" && isset($profileID) && isset($AccountID)){
		$plain = "openposition|".$profileID."|" ."$AccountID";
		$request = encrypts($plain);
		$request = strtr($request, '+/=', '-_,' );
		$curlURL = $domain."webservices_new/getbalance.php?data=".$request;
	}	
	
	if ($action == "closedposition" && isset($profileID) && isset($AccountID)){
		$plain = "closedposition|".$profileID."|" ."$AccountID";
		$request = encrypts($plain);
		$request = strtr($request, '+/=', '-_,' );
		$curlURL = $domain."webservices_new/getbalance.php?data=".$request;
	}	
	
	if ($action == "maxdd" && isset($profileID) && isset($AccountID)){
		$plain = "maxdd|".$profileID."|" ."$AccountID";
		$request = encrypts($plain);
		$request = strtr($request, '+/=', '-_,' );
	  $curlURL = $domain."webservices_new/getbalance.php?data=".$request;
	}
		
	if ($action == "returnofinvestment" && isset($profileID) && isset($AccountID)) {
		$plain = "returnofinvestment|".$profileID."|" ."$AccountID";
		$request = encrypts($plain);
		$request = strtr($request, '+/=', '-_,' );
		$curlURL = $domain."webservices_new/getbalance.php?data=".$request;
	}	
	
	if ($action == "availableinvested" && isset($profileID) && isset($AccountID)) {
		$plain = "availableinvested|".$profileID."|" ."$AccountID";
		$request = encrypts($plain);
		$request = strtr($request, '+/=', '-_,' );
		$curlURL = $domain."webservices_new/getbalance.php?data=".$request;
	}	
	
	if ($action == "totalinvested" && isset($profileID) && isset($AccountID)) {
		$plain = "totalinvested|".$profileID."|" ."$AccountID";
		$request = encrypts($plain);
		$request = strtr($request, '+/=', '-_,' );
		$curlURL = $domain."webservices_new/getbalance.php?data=".$request;
	}	
	
	
	try
	{
		$ch = curl_init();
		if (FALSE === $ch){
		throw new Exception('failed to initialize');
	}

	echo "test";
	curl_setopt($ch, CURLOPT_URL,$curlURL);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	echo $p_result = curl_exec($ch);
	echo "test";
	
	if (FALSE === $p_result) {
	throw new Exception(curl_error(), curl_errno());
	curl_close($ch);
	} else{
	curl_close($ch);
	return $p_result;
	}
	}catch(Exception $e) {
	trigger_error(sprintf('Curl failed with error #%d: %s',$e->getCode(),
	$e->getMessage()),E_USER_ERROR); 
	return $output ; 
	}
}

	
 

# Encrypt a value using AES-256.
function encrypts($plain, $key = null, $hmacSalt = null) {
			
	# Private salt
	$salt = 'ZfTfbip&Gs0Z4yz3ZfTfbip&Gs0Z4yz3';
	# Private key
	$key =  'SDefrfdrghgfdfE)SDefrfdrghgfdfE)';
   
	$hmac = hash_hmac('sha256', $plain, $salt); # Generate a keyed hash value using the HMAC method
  $time = time();
	$plain = $plain."@".$hmac."@".$time;
	$encryptionMethod = "AES-256-CBC"; 
	$ivlen = openssl_cipher_iv_length($encryptionMethod);
	$iv = openssl_random_pseudo_bytes($ivlen);
	
	$encryptedMessage = openssl_encrypt($plain, $encryptionMethod, $key,$options=0, $iv);
	 
	$data = base64_encode($encryptedMessage);

	

 
	return  $data;
}




?>
