<?php 
		if(!session_id()) { @session_start(); }
    $root = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));	
	if (file_exists($root.'/wp-load.php')) {
		require_once($root.'/wp-load.php');	
	}		
	
	$bwid = $_POST['bwid'];
	$plugin_url = plugins_url('',  dirname(__FILE__));
	$base =   dirname(dirname(dirname(__FILE__)));
	$partialdeposite_status = get_option('appointment_partial_deposit_status'.'_'.$bwid);
	if($partialdeposite_status=='E'){
		$amt = number_format($_SESSION['apt_partialdeposit'],2,".",',');
	}else{
		$amt = number_format($_SESSION['apt_nettotal'],2,".",',');
	}
	
	/** Paytm - define credentials start**/
	$paytm_merchant_key = get_option('appointment_paytm_merchantkey'.'_'.$bwid);
	$paytm_merchant_id = get_option('appointment_paytm_merchantid'.'_'.$bwid);
	$paytm_website_url = get_option('appointment_paytm_website'.'_'.$bwid);
	if(get_option('appointment_paytm_testing_mode'.'_'.$bwid)=='E'){
		define('PAYTM_ENVIRONMENT', 'TEST');
		$PAYTM_DOMAIN = "pguat.paytm.com";
	}else{
		define('PAYTM_ENVIRONMENT', 'PROD');
		$PAYTM_DOMAIN = 'secure.paytm.in';
	}
	define('PAYTM_MERCHANT_WEBSITE', $paytm_website_url);
	define('PAYTM_MERCHANT_KEY', $paytm_merchant_key);
	define('PAYTM_MERCHANT_MID', $paytm_merchant_id);
	define('PAYTM_REFUND_URL', 'https://'.$PAYTM_DOMAIN.'/oltp/HANDLER_INTERNAL/REFUND');
	define('PAYTM_STATUS_QUERY_URL', 'https://'.$PAYTM_DOMAIN.'/oltp/HANDLER_INTERNAL/TXNSTATUS');
	define('PAYTM_STATUS_QUERY_NEW_URL', 'https://'.$PAYTM_DOMAIN.'/oltp/HANDLER_INTERNAL/getTxnStatus');
	define('PAYTM_TXN_URL', 'https://'.$PAYTM_DOMAIN.'/oltp-web/processTransaction');
	/** Paytm - define credentials end**/
	
	require(dirname(dirname(dirname(__FILE__))).'/objects/class_encdec_paytm.php');
	
	$paytm_return_url = $plugin_url.'/lib/paytm_success.php?bwid='.$bwid;
	$currency_code = get_option('appointment_currency'.'_'.$bwid); /* 'USD'; */
	
	$ORDER_ID = 'ct'.time();
	$CUST_ID = mt_rand(1000, 9999).'_'.$_POST['fname'].'_'.$_POST['lname'];
	$INDUSTRY_TYPE_ID = get_option('appointment_paytm_industryid'.'_'.$bwid);
	$CHANNEL_ID = get_option('appointment_paytm_channelid'.'_'.$bwid);
	
	// Create an array having all required parameters for creating checksum.
	$paramList["MID"] = PAYTM_MERCHANT_MID;
	$paramList["ORDER_ID"] = $ORDER_ID;
	$paramList["CUST_ID"] = $CUST_ID;
	$paramList["INDUSTRY_TYPE_ID"] = $INDUSTRY_TYPE_ID;
	$paramList["CHANNEL_ID"] = $CHANNEL_ID;
	$paramList["TXN_AMOUNT"] = $amt;
	$paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;
	$paramList["CALLBACK_URL"] = $paytm_return_url;
	
	$checkSum = ct_getChecksumFromArray($paramList,PAYTM_MERCHANT_KEY);
	
	$form_Arr = array();
	$form_Arr['PAYTM_TXN_URL'] = PAYTM_TXN_URL;
	$form_Arr['CHECKSUMHASH'] = $checkSum;
	$Extra_form_fields = '';
	foreach($paramList as $name => $value) {
		$Extra_form_fields .= '<input type="hidden" name="' . $name .'" value="' . $value . '">';
	}
	$form_Arr['Extra_form_fields'] = $Extra_form_fields;
	
	echo json_encode($form_Arr);die;