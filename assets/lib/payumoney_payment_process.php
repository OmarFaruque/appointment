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
	
	$arr = array();
	$MERCHANT_KEY = get_option('appointment_payumoney_merchantkey'.'_'.$bwid);
	$arr['merchant_key'] = $MERCHANT_KEY;
	$SALT = get_option('appointment_payumoney_saltkey'.'_'.$bwid);
	$arr['salt'] = $SALT;
	$arr['amt'] = $amt;
	$arr['fname'] = $_POST['fname'];
	$arr['email'] = $_POST['username'];
	$arr['phone'] = $_POST['phone'];
	
	$txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
	$arr['txnid'] = $txnid;
	$productinfo = 'product description';
	$hash_string = $MERCHANT_KEY.'|'.$txnid.'|'.$amt.'|'.$productinfo.'|'.$_POST['fname'].'|'.$_POST['username'].'|||||||||||'.$SALT;

	$arr['hash'] = strtolower(hash('sha512', $hash_string));
	$arr['productinfo'] = $productinfo;
	$arr['payu_surl'] = $plugin_url."/lib/payumoney_success.php?bwid=".$bwid;
	$arr['payu_furl'] = $plugin_url."/lib/payumoney_failure.php";
	$arr['service_provider'] = "payu_paisa";
	
	echo json_encode($arr);die;
?>