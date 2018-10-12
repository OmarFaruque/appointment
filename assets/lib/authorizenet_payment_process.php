<?php 	
	session_start();
 $root = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));	
if (file_exists($root.'/wp-load.php')) {
		require_once($root.'/wp-load.php');	
	}	
	$base =   dirname(dirname(dirname(__FILE__)));
	
	foreach($_SESSION['appointment_cart'] as $cart_item){
	$booking_total_amount += $cart_item['amount'];
} 
$final_net_total = ($booking_total_amount - $_SESSION['coupon_discount']) + $_SESSION['tax_amount']; 
$bwid = $_SESSION['apt_detail']['bwid'];
	$partialdeposite_status = get_option('appointment_partial_deposit_status'.'_'.$bwid);
	if($partialdeposite_status=='E'){
		$amt = number_format($_SESSION['partial_amount_deposit'],2,".",',');
	}else{
		$amt = number_format($final_net_total,2,".",',');
	}
	
			require($base.'/assets/authorize.net/autoload.php');
			$response = null;
			
            define( 'AUTHORIZENET_API_LOGIN_ID',get_option('appointment_authorizenet_api_loginid'.'_'.$bwid));
            define( 'AUTHORIZENET_TRANSACTION_KEY',get_option('appointment_authorizenet_transaction_key'.'_'.$bwid)); 
			/* define( 'AUTHORIZENET_API_LOGIN_ID','93rPPjsZ6J6');
            define( 'AUTHORIZENET_TRANSACTION_KEY','7B6d9N89QG7tdq2d'); */
			if(get_option('appointment_authorizenet_testing_mode'.'_'.$bwid)=='E'){   
				define( 'AUTHORIZENET_SANDBOX',true); 
			}else{ 
				define( 'AUTHORIZENET_SANDBOX',false);
			}
			
			$expirydate = $_POST['cc_exp_month'].'/'.$_POST['cc_exp_year'];
            $sale             = new AuthorizeNetAIM();
            $sale->amount     = $amt;
            $sale->card_num   = $_POST['cc_card_num'];
            $sale->card_code  = $_POST['cc_card_code'];
            $sale->exp_date   = $expirydate;
            $sale->first_name = $_POST['first_name'];
            $sale->email      = $user_email;
            $sale->phone      = $_POST['phone'];
			
			
			$response = $sale->authorizeAndCapture();
            if ( $response->approved ) {				
				$return = array ( 'success' => true ,'error' =>'','transaction_id'=>$response->transaction_id);
				echo json_encode($return);die();
            } else {
                $return = array ('success' => false, 'error' => $response->error_message);
				echo json_encode($return);die();
            }
?>