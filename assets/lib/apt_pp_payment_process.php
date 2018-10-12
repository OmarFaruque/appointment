<?php 	
	
	$a = session_id();	if(empty($a)) session_start();
	
	//$root = dirname(dirname(dirname(dirname(dirname(__FILE__)))));		
	 $root = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));
	
	if (file_exists($root.'/wp-load.php')) {	
		require_once($root.'/wp-load.php');		
	}	

	/*Plugin author url */
	$bwid = $_SESSION['apt_detail']['bwid'];

	$plugin_url_for_ajax = plugins_url('',  dirname(dirname(__FILE__)));		
	$plugin_base_ralative_path =  dirname(dirname(dirname(__FILE__)));	
	//echo $plugin_base_ralative_path;
	$upload_dir_path= wp_upload_dir();	
	require($plugin_base_ralative_path.'/objects/class_pyapal_express_checkout.php');
				// setup API credentails 
	$api_username_cart = urlencode(get_option('appointment_paypal_api_username'.'_'.$bwid));
	$api_password_cart = urlencode(get_option('appointment_paypal_api_password'.'_'.$bwid));
	$api_signature_cart = urlencode(get_option('appointment_paypal_api_signature'.'_'.$bwid));
	
	$partialdeposite_status = get_option('appointment_partial_deposit_status'.'_'.$bwid);
	
	
	$version_cart = urlencode('109.0');
	$pp_return_url_cart = urlencode(plugins_url('',  __FILE__).'/apt_pp_payment_process.php');
	$pp_cancel_url_cart = urlencode(esc_url( home_url( '/' )));
	$currency_code_cart = get_option('appointment_currency'.'_'.$bwid); /* 'USD'; */
	$payment_action_cart = urlencode("SALE");
	$locale_code_cart = 'US';
	
	
	$company_logo = get_option('appointup_company_logo'.'_'.$bwid);
	if($company_logo!='') {				
	 /*	$thumb_image_name = explode("/",$company_logo);
		$site_logo = site_url()."/wp-content/uploads/".$thumb_image_name[1]."/".$thumb_image_name[2]."/th_".$thumb_image_name[3]; */
    $site_logo_cart = $upload_dir_path['baseurl'].$company_logo;
	}else{
	$site_logo_cart='';
	}
	$border_color_cart = '2285C6';
	$allow_note = 1;


	$p = new apt_paypal();
	
	/* declare classes */
	include_once($plugin_base_ralative_path.'/objects/class_clients.php');
	$apt_client_info = new appointment_clients();
	include_once($plugin_base_ralative_path.'/objects/class_booking.php');
	$apt_booking = new appointment_booking();
	include_once($plugin_base_ralative_path.'/objects/class_payments.php');
	$apt_payments = new appointment_payments();
	include_once($plugin_base_ralative_path.'/objects/class_email_templates.php');
	$apt_email_templates = new appointment_email_template();
	include_once($plugin_base_ralative_path.'/objects/class_sms_templates.php');
	$obj_sms_template = new appointment_sms_template();
	
	/* get value in variable */
	if(isset($_POST['action']) && $_POST['action'] == 'add_apt_new_user'){
		$_SESSION['preff_username'] = $_POST['preff_username'];
		$_SESSION['preff_password'] = $_POST['preff_password'];
		$_SESSION['first_name'] = $_POST['first_name'];
		$_SESSION['last_name'] = $_POST['last_name'];
		$_SESSION['user_email'] = $_POST['user_email'];
		$_SESSION['notes'] = $_POST['notes'];
		$_SESSION['phone'] = $_POST['phone'];
		$_SESSION['dynamic_field_add'] = $_POST['dynamic_field_add'];
	}else if(isset($_POST['action']) && $_POST['action'] == 'add_apt_exist_user'){
		global $current_user;
		get_currentuserinfo();
		$_SESSION['preff_username'] = $current_user->user_login;
		$_SESSION['user_email'] = $current_user->user_email;
		$_SESSION['first_name'] = $current_user->user_firstname;
		$_SESSION['last_name'] = $current_user->user_lastname;
		$_SESSION['current_user_id'] = $current_user->ID ;
		$_SESSION['notes'] = $_POST['notes'];
		$_SESSION['phone'] = $_POST['phone'];
		$_SESSION['dynamic_field_add'] = $_POST['dynamic_field_add'];
		//print_r($_SESSION['dynamic_field_add']);
	}
	
	$phone_number = $_SESSION['phone'];
	/* $preff_username = $_POST['preff_username'];
	$preff_password = $_POST['preff_password'];
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$user_email = $_POST['user_email'];
	$notes = $_POST['notes'];
	$extra_details = $_POST['dynamic_field_add']; */
	
	
	
	
	if(get_option('appointment_paypal_testing_mode'.'_'.$bwid)=='D'){
	 $p->mode = '';   					/* leave empty for 'Live' mode */
	 }else{ $p->mode = 'SANDBOX'; }
		
	
	//set basic name and value pairs for curl post
	$basic_NVP = array(
					'VERSION'=>$version_cart,
					'USER'=>$api_username_cart,
					'PWD'=>$api_password_cart,
					'SIGNATURE'=>$api_signature_cart,
					'RETURNURL'=>$pp_return_url_cart,
					'CANCELURL'=>$pp_cancel_url_cart,
					'PAYMENTREQUEST_0_CURRENCYCODE'=>$currency_code_cart,
					'NOSHIPPING'=>1,
					'PAYMENTREQUEST_0_PAYMENTACTION'=>$payment_action_cart,
					'LOCALECODE'=>$locale_code_cart,
					'CARTBORDERCOLOR'=>$border_color_cart,
					'LOGOIMG'=>$site_logo_cart,
					'ALLOWNOTE'=>1
				);  
	if(get_option('appointment_paypal_direct_cc_dc_payment'.'_'.$bwid)=='Y'){
		$basic_NVP['SOLUTIONTYPE']='Sole';
		$basic_NVP['LANDINGPAGE']='Billing';
	}			
	
	foreach($basic_NVP as $key => $value) {
	  $p->pv .= "&$key=$value";
	}

	$cart_item_counter_new=0;	
				if($partialdeposite_status=='E'){
					$temp_sub_total_val = $_SESSION['partial_amount_deposit'];
					$p->pv .= "&L_PAYMENTREQUEST_0_NAME0=Partial Payment for order";
					$p->pv .= "&L_PAYMENTREQUEST_0_DESC0=Partial payment for appointment order";	
					$p->pv .= "&L_PAYMENTREQUEST_0_AMT0=".number_format($temp_sub_total_val,2,".",',');			
					$p->pv .= "&L_PAYMENTREQUEST_0_QTY0=1";	
					$p->pv .= "&PAYMENTREQUEST_0_ITEMAMT=".number_format($temp_sub_total_val,2,".",',');
					$p->pv .= "&PAYMENTREQUEST_0_TAXAMT=0"; 
					$p->pv .= "&PAYMENTREQUEST_0_AMT=".number_format($temp_sub_total_val,2,".",',');
				}else{
				foreach($_SESSION['appointment_cart'] as $cartitem) {
							$service_title = $cartitem['service_title'];
							$book_time  = $cartitem['book_time_slot'];
							$booking_price = $cartitem['amount'];
							$price += $booking_price;
					$p->pv .= "&L_PAYMENTREQUEST_0_NAME$cart_item_counter_new=$service_title";
					$p->pv .= "&L_PAYMENTREQUEST_0_DESC$cart_item_counter_new=$book_time";		
					$p->pv .= "&L_PAYMENTREQUEST_0_AMT$cart_item_counter_new=".number_format($booking_price,2,".",',');		
					$p->pv .= "&L_PAYMENTREQUEST_0_QTY$cart_item_counter_new=1";			
					$cart_item_counter_new++;		
			    }			   			
			    if(isset($_SESSION['cart']['discount_amount'])&& $_SESSION['cart']['discount_amount']!=''){				   
			    $p->pv .= "&L_PAYMENTREQUEST_0_NAME$cart_item_counter_new='Discount'";					
			    $p->pv .= "&L_PAYMENTREQUEST_0_DESC$cart_item_counter_new='Discount'";							
			    $p->pv .= "&L_PAYMENTREQUEST_0_AMT$cart_item_counter_new=-".number_format($_SESSION['discount_amount'],2,".",',');
			    $p->pv .= "&L_PAYMENTREQUEST_0_QTY$cart_item_counter_new=1";									
				$temp_sub_total_val = $_SESSION['sub_total_amount']-$_SESSION['discount_amount']; 				
			    }else{
					$temp_sub_total_val = $price;					
				}
				// echo $temp_sub_total_val;
			    $p->pv .= "&PAYMENTREQUEST_0_ITEMAMT=".number_format($temp_sub_total_val,2,".",',');
				$p->pv .= "&PAYMENTREQUEST_0_TAXAMT=".number_format(0,2,".",',');
				$p->pv .= "&PAYMENTREQUEST_0_AMT=".number_format($temp_sub_total_val,2,".",',');	
				
				}

	$p->pp_method_name = 'SetExpressCheckout';  //method name using for API call



	if(!isset($_GET["token"])) {
	$response_array = $p->paypal_nvp_api_call();
	//Respond according to message we receive from Paypal
		if("SUCCESS" == strtoupper($response_array["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($response_array["ACK"]))
		{
				if(strtoupper($p->mode)=='SANDBOX') {
				  $p->mode = '.sandbox';
				}
				//Redirect user to PayPal store with Token received.
			 	$paypal_url ='https://www'.$p->mode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$response_array["TOKEN"].'';	
				
				echo "<script language=\"Javascript\">window.location='" . $paypal_url . "';</script>";
						 
		}else{
			//Show error message
			echo '<div style="color:red"><b>Error : </b>'.urldecode($response_array["L_LONGMESSAGE0"]).'</div>';
			echo '<pre>';
			print_r($response_array);
			echo '</pre>';
		}
	}	
	
	
	if(isset($_GET["token"]) && isset($_GET["PayerID"]))
	{
		//we will be using these two variables to execute the "DoExpressCheckoutPayment"
		//Note: we haven't received any payment yet.
		
		$token = $_GET["token"];
		$payer_id = $_GET["PayerID"];	
		$p->pv .= "&TOKEN=".urlencode($token)."&PAYERID=".urlencode($payer_id);
		$p->pp_method_name = 'DoExpressCheckoutPayment';  //method name using for API call
		$payment_response_array = $p->paypal_nvp_api_call(); 
		if("SUCCESS" == strtoupper($payment_response_array["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($payment_response_array["ACK"])) 
		{

		   //echo 'Your Transaction ID : '.urldecode($payment_response_array["PAYMENTINFO_0_TRANSACTIONID"]);	
		   $_SESSION['order_transaction_id'] = urldecode($payment_response_array["PAYMENTINFO_0_TRANSACTIONID"]);	   
		   /* $_SESSION['order_transaction_id'] = 11; */
		  // include($plugin_base_ralative_path.'/lib/product_lib.php');
		  
			if(is_user_logged_in()){
				/* Existing user */
				
				/* tables */
				$client_info_table = $wpdb->prefix .'apt_order_client_info';
				
				/* Get order id of user */
				$sql_id="SELECT max(order_id) as max FROM ".$client_info_table;
				$get_order_id=$wpdb->get_var($sql_id);
				if($get_order_id == 0){
					$order_id = 1000;
				}else{
				$order_id = $get_order_id + 1;
				}
				
				$user = new WP_User($_SESSION['current_user_id']);
				$user->add_cap('apt_client'); 
				$user->add_role('subscriber');
				
				/* insert data in order client table */
				$apt_client_info->order_id = $order_id;
				$apt_client_info->clientName = $_SESSION['first_name']." ".$_SESSION['last_name'];
				$apt_client_info->client_email = $_SESSION['user_email'];
				$apt_client_info->client_phone = "";
				$apt_client_info->client_personal_info = $_SESSION['dynamic_field_add'];
				
				$add_apt_client = $apt_client_info->add_client_info();
				if($add_apt_client){ /*echo "add client";*/ }else{ /*echo "not";*/ }
				
				/* Cart Sessions */
				$booking_details = "<table style='margin: 1em 0;width: 100%;overflow:hidden;background: #FFF;color: #024457;border-radius: 10px;'>
				  <tr style='border: 1px solid #D9E4E6;'>
					<th style='display: table-cell;text-align: center;padding: 1em;border: 1px solid #FFF;background-color: #167F92;color: #FFF;' data-th='Driver details'><span style='text-align: center;color:color: #FFF;'>Service Name</span></th>
					<th style='display: table-cell;text-align: center;padding: 1em;border: 1px solid #FFF;background-color: #167F92;color: #FFF;'>Start Time</th>
					<th style='display: table-cell;text-align: center;padding: 1em;border: 1px solid #FFF;background-color: #167F92;color: #FFF;'>End Time</th>
					<th style='display: table-cell;text-align: center;padding: 1em;border: 1px solid #FFF;background-color: #167F92;color: #FFF;'>Price</th>
				  </tr>";
				foreach($_SESSION['appointment_cart'] as $cart_item){
					$booking_total_amount += $cart_item['amount'];
					$apt_booking->location_id = $cart_item['location_id'];
					$apt_booking->order_id = $order_id;
					$apt_booking->client_id = $_SESSION['current_user_id'];
					$apt_booking->service_id = $cart_item['service_id'];
					$apt_booking->provider_id = $cart_item['staff_id'];
					$apt_booking->booking_price = $cart_item['amount'];
					$apt_booking->booking_datetime = $_POST['selected_date_time_from_cal'];
					$apt_booking->booking_endtime = date_i18n('Y-m-d H:i:s',strtotime("+".$cart_item['duration']." minutes", strtotime(date_i18n('Y-m-d',strtotime($_POST['selected_date_time_from_cal'])).' '.date_i18n('H:i:s',strtotime($_POST['selected_date_time_from_cal'])))));
					if(get_option('appointment_appointment_auto_confirm'.'_'.$bwid) == "E"){
						$apt_booking->booking_status = "C";
					}else{
						$apt_booking->booking_status = "A";
					}
					$apt_booking->notification = "0";
					$apt_booking->lastmodify = date_i18n('Y-m-d H:i:s');
					
					$add_booking = $apt_booking->add_bookings();
					
					$booking_date_start = $cart_item['book_time_slot'];
					$booking_date_end = date_i18n('Y-m-d H:i:s',strtotime("+".$cart_item['duration']." minutes", strtotime(date_i18n('Y-m-d',strtotime($cart_item['book_time_slot'])).' '.date_i18n('H:i:s',strtotime($cart_item['book_time_slot'])))));
					$service_name = $cart_item['service_title'];
					$price = get_option('appointment_currency_symbol'.'_'.$bwid)." ".$cart_item['amount'];
					$phone = $_SESSION['phone'];
					$payment_method = "Paypal";
					$service_name_sms .= $cart_item['service_title'].",";
					
					$booking_details .="<tr style='border: 1px solid #D9E4E6;'>
					<td style='display: table-cell;text-align: center;border-right: 1px solid #D9E4E6;padding: 1em;color: #024457;'>".$cart_item['service_title']."</td>
					<td style='display: table-cell;text-align: center;border-right: 1px solid #D9E4E6;padding: 1em;color: #024457;'>".$cart_item['book_time_slot']."</td>
					<td style='display: table-cell;text-align: center;border-right: 1px solid #D9E4E6;padding: 1em;color: #024457;'>".date_i18n('Y-m-d H:i:s',strtotime("+".$cart_item['duration']." minutes", strtotime(date_i18n('Y-m-d',strtotime($cart_item['book_time_slot'])).' '.date_i18n('H:i:s',strtotime($cart_item['book_time_slot'])))))."</td>
					<td style='display: table-cell;text-align: center;border-right: 1px solid #D9E4E6;padding: 1em;color: #024457;'>".get_option('appointment_currency_symbol'.'_'.$bwid)." ".$cart_item['amount']."</td>
				  </tr>";
					
				}
				
				$booking_details .= "</table>";
				
				/************ Booking SUCCESS *************/
				if($add_booking){
		
					/**************************Send mail to client***********************************/
					$get_company_name = get_option('appointment_company_name'.'_'.$bwid);
					$company_address = get_option('appointment_company_address'.'_'.$bwid);
					$company_city = get_option('appointment_company_city'.'_'.$bwid);
					$company_state = get_option('appointment_company_state'.'_'.$bwid);
					$company_zip = get_option('appointment_company_zip'.'_'.$bwid);
					$company_country = get_option('appointment_company_country'.'_'.$bwid);
					$company_phone = "-";
					$company_email = get_option('appointment_company_email'.'_'.$bwid);
					if(get_option('appointment_company_logo'.'_'.$bwid) == ''){ 
						$business_logo = $plugin_url.'/assets/images/company.png';
					}else{
						$business_logo = site_url()."/wp-content/uploads/".get_option('appointment_company_logo'.'_'.$bwid);	
					}
					$apt_email_templates->method = "A";
					$get_email_template = $apt_email_templates->get_emailtemplate_by_sending_method();
					foreach($get_email_template as $get_emails){
						/******************* Send Mail To Client ************************/
						if($get_emails->user_type == "C"){
						if($get_emails->email_template_name == "AC"){
							if($get_emails->email_template_status == "e"){
								/* Search Array */
								$searcharray = array('{{client_name}}','{{company_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{business_logo}}','{{booking_details}}','{{booking_date_start}}','{{booking_date_end}}','{{service_name}}','{{price}}','{{firstname}}','{{lastname}}','{{client_email}}','{{phone}}','{{payment_method}}');
								/* Replace Array */
								$replacearray = array($_SESSION['preff_username'],$get_company_name,$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$business_logo,$booking_details,$booking_date_start,$booking_date_end,$service_name,$price,$_SESSION['first_name'],$_SESSION['last_name'],$_SESSION['user_email'],$phone,$payment_method);

								if($get_emails->email_message == ''){
									$apt_emailcontent = $get_emails->default_message;
								}else{
									$apt_emailcontent = $get_emails->email_message;
								}
									
								$receiver_emails = $_SESSION['user_email'];
								$subject1 = $get_emails->email_subject;
								$message.= str_replace($searcharray,$replacearray,$apt_emailcontent);
								$headers  = "MIME-Version: 1.0\r\n";
								$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

								$receiver_emails = explode ( ',', $receiver_emails );

								foreach ( $receiver_emails as $to ) {
									$to = trim ( $to );
									if (wp_mail ( $to, $subject1, $message, $headers )) {	
										//echo "Mail Sent...";
									} else {
										//echo 'Error sending mail...';
									}
								} 
							}
						}
						}
						
						/******************* --**-- End Send Mail To Client --**-- ************************/
					}
					/******************* --**-- End Send Mail Client --**-- ************************/
					
					/******************* Send Mail To Admin ************************/
					foreach($get_email_template as $get_emails){
						if($get_emails->user_type == "AM"){
						if($get_emails->email_template_name == "AA"){
							if($get_emails->email_template_status == "e"){
							
								$appoinment_client_detail = "Client Name: ".$_SESSION['preff_username']." <br/> Client Email: ".$_SESSION['user_email']." <br/> Password: N/A";
								$admin_username = get_option('appointment_email_sender_name'.'_'.$bwid);
								
								/* Search Array */
								$searcharray = array('{{admin_name}}','{{client_name}}','{{company_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{business_logo}}','{{booking_details}}','{{booking_date_start}}','{{booking_date_end}}','{{service_name}}','{{price}}','{{firstname}}','{{lastname}}','{{client_email}}','{{phone}}','{{payment_method}}');
								/* Replace Array */
								$replacearray = array($admin_username,$_SESSION['preff_username'],$get_company_name,$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$business_logo,$booking_details,$booking_date_start,$booking_date_end,$service_name,$price,$_SESSION['first_name'],$_SESSION['last_name'],$_SESSION['user_email'],$phone,$payment_method);
								
								if($get_emails->email_message == ''){
									$apt_emailcontent = $get_emails->default_message;
								}else{
									$apt_emailcontent = $get_emails->email_message;
								}
								
								/* get admin email */
								$get_admin_email = get_option('appointment_email_sender_address'.'_'.$bwid);
								$receiver_new_emailsss = $get_admin_email;
								$subject1 = $get_emails->email_subject;
								$message = "";
								$message.= str_replace($searcharray,$replacearray,$apt_emailcontent);
								$headers  = "MIME-Version: 1.0\r\n";
								$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

								$receiver_new_emailsss = explode ( ',', $receiver_new_emailsss );

								foreach ( $receiver_new_emailsss as $to ) {
									$to = trim ( $to );
									if (wp_mail ( $to, $subject1, $message, $headers )) {	
										/*echo "Mail Sent to admin...";*/
									} else {
										/* echo 'Error sending mail...'; */
									}
								} 
							}
						}
						}			
					}
					/******************* Send Mail To Admin ************************/
					
					/******************* / sms sending code via Plivo / ************/
					
					
					if(get_option("appointment_sms_reminder_status".'_'.$bwid) == "Y"){
					include_once($plugin_base_ralative_path.'/objects/plivo.php');
					$plivo_sender_number = get_option('appointment_plivo_number'.'_'.$bwid);
					
					$booking_detail = $service_name_sms;
					
					
					
					
					if(get_option('appointment_sms_noti_plivo'.'_'.$bwid)=="E"){
						
						/******************* / sms sending code via Plivo to client / ************/
						if(get_option('appointment_plivo_client_sms_notification_status'.'_'.$bwid) == "E"){
							$searcharray = array('{{customer_name}}','{{booking_detail}}','{{company_name}}');
							$replacearray = array($_SESSION['preff_username'],$booking_detail,get_option("appointment_company_name".'_'.$bwid));
							$phone = $_SESSION['phone'];
							$auth_id = get_option('appointment_plivo_sid'.'_'.$bwid);
							$auth_token = get_option('appointment_plivo_auth_token'.'_'.$bwid);
							$p_client = new Plivo\RestAPI($auth_id, $auth_token, '', '');
							
							$template = $obj_sms_template->gettemplate_sms("C",'e','AC');
							
							if($template[0]->sms_template_status == "e"){
								if($template[0]->sms_message == "")
								{
									$message = strip_tags($template[0]->default_message);
								}
								else
								{
									$message = strip_tags($template[0]->sms_message);
								}
								$client_sms_body = str_replace($searcharray,$replacearray,$message);
								$params = array(
								'src' => $plivo_sender_number,
								'dst' => $phone,
								'text' => $client_sms_body,
								'method' => 'POST'
								);
								$response = $p_client->send_message($params);
							} 
						}
						/******************* / sms sending code via Plivo to Admin / ************/
						if(get_option('appointment_plivo_admin_sms_notification_status'.'_'.$bwid) == "E"){
								$admin_blog = get_bloginfo();
								$client_info = "Client Name ".$_SESSION['preff_username'];
								$searcharray = array('{{admin_manager_name}}','{{booking_detail}}','{{appoinment_client_detail}}','{{company_name}}');
								$replacearray = array($admin_blog,$booking_detail,$client_info,get_option("appointment_company_name".'_'.$bwid));
								$phone = get_option('appointment_plivo_admin_phone_no'.'_'.$bwid);
								$auth_id = get_option('appointment_plivo_sid'.'_'.$bwid);
								$auth_token = get_option('appointment_plivo_auth_token'.'_'.$bwid);
								$p_client = new Plivo\RestAPI($auth_id, $auth_token, '', '');
								
								$template = $obj_sms_template->gettemplate_sms("AM",'e','AA');
								
								if($template[0]->sms_template_status == "e"){
									if($template[0]->sms_message == "")
									{
										$message = strip_tags($template[0]->default_message);
									}
									else
									{
										$message = strip_tags($template[0]->sms_message);
									}
									
									$client_sms_body = str_replace($searcharray,$replacearray,$message);
									$params = array(
									'src' => $plivo_sender_number,
									'dst' => $phone,
									'text' => $client_sms_body,
									'method' => 'POST'
									);
									$response = $p_client->send_message($params);
								} 
							}
						}
					}
					/********************  / sms sending code in pilvo / ***********************/
					
					/************* / sms sending code in Twillo / *************/
					include_once($plugin_base_ralative_path.'/assets/twilio/Services/Twilio.php');
					if(get_option("appointment_sms_reminder_status".'_'.$bwid) == "Y")
					{
					$twillio_sender_number = get_option('appointment_twilio_number'.'_'.$bwid);
					
					
					
						/* Twillio Status */	
						if(get_option('appointment_sms_noti_twilio'.'_'.$bwid)=="E")
						{
							
						  /******************* / sms sending code via Twillio to client / ************/
						  if(get_option("appointment_twilio_client_sms_notification_status".'_'.$bwid) == "E")
						  {
							  $searcharray = array('{{customer_name}}','{{booking_detail}}','{{company_name}}','');
							  $replacearray = array($_SESSION['preff_username'],$booking_detail,get_option("appointment_company_name".'_'.$bwid));
							   $phone = $_SESSION['phone'];
							   $AccountSid = get_option('appointment_twilio_sid'.'_'.$bwid);
							   $AuthToken =  get_option('appointment_twilio_auth_token'.'_'.$bwid); 
							   $twilliosms_client = new Services_Twilio($AccountSid, $AuthToken);

							   $template = $obj_sms_template->gettemplate_sms("C",'e','AC');
							   if($template[0]->sms_template_status == "e") 
							   {
								  if($template[0]->sms_message == "")
									{
									 $message = strip_tags($template[0]->default_message);
									}
									else
									{
									 $message = strip_tags($template[0]->sms_message);
									}
									$client_sms_body = str_replace($searcharray,$replacearray,$message);
									
									$message = $twilliosms_client->account->messages->create(array(
									 "From" => $twillio_sender_number,
									 "To" => $phone,
									 "Body" => $client_sms_body));
								}
						  }
						
						/************************* Client sms End *******************************/
						 /******************* / sms sending code via Twillio to Admin / ************/
						 if(get_option("appointment_twilio_admin_sms_notification_status".'_'.$bwid) == "E"){
						   $admin_blog = get_bloginfo();
						   $client_info = "Client Name ".$_SESSION['preff_username'];
						   $searcharray = array('{{admin_manager_name}}','{{booking_detail}}','{{appoinment_client_detail}}','{{company_name}}','');
						   $replacearray = array($admin_blog,$booking_detail,$client_info,get_option("appointment_company_name".'_'.$bwid));  
						   $phone = get_option('appointment_twilio_admin_phone_no'.'_'.$bwid);
						   $AccountSid = get_option('appointment_twilio_sid'.'_'.$bwid);
						   $AuthToken =  get_option('appointment_twilio_auth_token'.'_'.$bwid); 
						   $twilliosms_client = new Services_Twilio($AccountSid, $AuthToken);

						   $template = $obj_sms_template->gettemplate_sms("AM",'e','AA');
						  
						   if($template[0]->sms_template_status == "e") {
								if($template[0]->sms_message == ""){
								 $message = strip_tags($template[0]->default_message);
								}
								else{
								 $message = strip_tags($template[0]->sms_message);
								}
								$client_sms_body = str_replace($searcharray,$replacearray,$message);
								
								$message = $twilliosms_client->account->messages->create(array(
								 "From" => $twillio_sender_number,
								 "To" => $phone,
								 "Body" => $client_sms_body));
							}
						}
					}
					}
					/************* / sms sending code in Twillo end / *************/
					echo "Booking success"; ?> 
					<script>window.location.href = '<?php echo site_url(); ?>/wp-content/plugins/appointment/frontend/apt_thankyou.php';</script>
				<?php	unset($_SESSION['appointment_cart']);
				}else{ /*echo "not success";*/ }
				
				/**************** Booking Code End *****************/
				
				/************** End ***************/
				/* insert data in payment table */
				$apt_payments->location_id = $_SESSION['selected_location_id'];
				$apt_payments->client_id = $_SESSION['current_user_id'];
				$apt_payments->order_id = $order_id;
				$apt_payments->payment_method = "paypal";
				$apt_payments->transaction_id = $_SESSION['order_transaction_id'];
				$apt_payments->amount = $booking_total_amount;
				if(isset($_SESSION['coupon_discount'])){
				$apt_payments->discount = $_SESSION['coupon_discount'];
				}
				if(isset($_SESSION['tax_amount'])){
					$apt_payments->taxes = $_SESSION['tax_amount'];
				}
				if(isset($_SESSION['partial_amount_deposit'])){
				$apt_payments->partial = $_SESSION['partial_amount_deposit'];
				}
				$final_net_total = ($booking_total_amount - $_SESSION['coupon_discount']) + $_SESSION['tax_amount'];
				$apt_payments->net_total = $final_net_total;
				$apt_payments->business_owner_id = $_SESSION['apt_detail']['bwid'];
				$add_payment = $apt_payments->add_payments();
				if($add_payment){ /*echo "Payment success";*/ }else{ /*echo "not done payment";*/ }
				
			}else{
				/* New User*/
				/* tables */
				$client_info_table = $wpdb->prefix .'apt_order_client_info';
				
				/* Get order id of user */
				$sql_id="SELECT max(order_id) as max FROM ".$client_info_table;
				$get_order_id=$wpdb->get_var($sql_id);
				if($get_order_id == 0){
					$order_id = 1000;
				}else{
				$order_id = $get_order_id + 1;
				}
				
				/* insert data in user table */
				if($user_status != 'guest_user'){
				$apt_user_info = array(
								'user_login'    =>   $_SESSION['preff_username'],
								'user_email'    =>   $_SESSION['user_email'],
								'user_pass'     =>   $_SESSION['preff_password'],
								'first_name'    =>   $_SESSION['first_name'],
								'last_name'     =>   $_SESSION['last_name'],
								'nickname'      =>  '',
								'role' => 'subscriber'
								);
	   
				$new_apt_user = wp_insert_user( $apt_user_info );
				$user = new WP_User($new_apt_user);
				$user->add_cap('read');
				$user->add_cap('apt_client');
				$user->add_role('apt_users');				
				$user_id = $new_apt_user;
				$user_login = $_SESSION['preff_username'];
				add_user_meta( $new_apt_user, 'apt_client_locations', $_SESSION['selected_location_id'] );
				
				/* Set cookie of user after booking */
				wp_set_current_user( $user_id, $user_login );
				wp_set_auth_cookie( $user_id );
				
				}else{
					$new_apt_user = "0";
				}
				
				/* insert data in order client table */
				$apt_client_info->order_id = $order_id;
				$apt_client_info->clientName = $_SESSION['first_name']." ".$_SESSION['last_name'];
				$apt_client_info->client_email = $_SESSION['user_email'];
				$apt_client_info->client_phone = "";
				$apt_client_info->client_personal_info = $_SESSION['extra_details'];
				
				$add_apt_client = $apt_client_info->add_client_info();
				if($add_apt_client){ /*echo "add client";*/ }else{ /*echo "not";*/ }
				
				/* insert data in bookings table */
				/* Cart Sessions */
				/* Cart Sessions */
				$booking_details = "<table style='margin: 1em 0;width: 100%;overflow:hidden;background: #FFF;color: #024457;border-radius: 10px;'>
				  <tr style='border: 1px solid #D9E4E6;'>
					<th style='display: table-cell;text-align: center;padding: 1em;border: 1px solid #FFF;background-color: #167F92;color: #FFF;' data-th='Driver details'><span style='text-align: center;color:color: #FFF;'>Service Name</span></th>
					<th style='display: table-cell;text-align: center;padding: 1em;border: 1px solid #FFF;background-color: #167F92;color: #FFF;'>Start Time</th>
					<th style='display: table-cell;text-align: center;padding: 1em;border: 1px solid #FFF;background-color: #167F92;color: #FFF;'>End Time</th>
					<th style='display: table-cell;text-align: center;padding: 1em;border: 1px solid #FFF;background-color: #167F92;color: #FFF;'>Price</th>
				  </tr>";
				foreach($_SESSION['appointment_cart'] as $cart_item){
					$booking_total_amount += $cart_item['amount'];
					$apt_booking->location_id = $cart_item['location_id'];
					$apt_booking->order_id = $order_id;
					$apt_booking->client_id = $new_apt_user;
					$apt_booking->service_id = $cart_item['service_id'];
					$apt_booking->provider_id = $cart_item['staff_id'];
					$apt_booking->booking_price = $cart_item['amount'];
					$apt_booking->booking_datetime = $_POST['selected_date_time_from_cal'];
					$apt_booking->booking_endtime = date_i18n('Y-m-d H:i:s',strtotime("+".$cart_item['duration']." minutes", strtotime(date_i18n('Y-m-d',strtotime($_POST['selected_date_time_from_cal'])).' '.date_i18n('H:i:s',strtotime($_POST['selected_date_time_from_cal'])))));
					if(get_option('appointment_appointment_auto_confirm'.'_'.$bwid) == "E"){
						$apt_booking->booking_status = "C";
					}else{
						$apt_booking->booking_status = "A";
					}
					$apt_booking->notification = "0";
					$apt_booking->lastmodify = date_i18n('Y-m-d H:i:s');
					
					$add_booking = $apt_booking->add_bookings();
					
					$booking_date_start = $cart_item['book_time_slot'];
					$booking_date_end = date_i18n('Y-m-d H:i:s',strtotime("+".$cart_item['duration']." minutes", strtotime(date_i18n('Y-m-d',strtotime($cart_item['book_time_slot'])).' '.date_i18n('H:i:s',strtotime($cart_item['book_time_slot'])))));
					$service_name = $cart_item['service_title'];
					$price = get_option('appointment_currency_symbol'.'_'.$bwid)." ".$cart_item['amount'];
					$phone = $_SESSION['phone'];
					$payment_method = "Paypal";
					$service_name_sms .= $cart_item['service_title'].",";
					
					$extra_details = unserialize($serialize_extra_details);
					$user_extra_info = get_user_meta($user_id,'apt_client_extra_details');
					//print_r($user_extra_info);
								 if($user_extra_info != '') { 
									foreach($user_extra_info as $extra_details){
										$unser_date = unserialize($extra_details);
										//print_r($unser_date);
										$sec_unser_data = unserialize($unser_date);
										foreach($sec_unser_data as $key=>$val){
											
										$booking_details .=	 "<div class='col-xs-12 np'><b> ".$key."</b> - ".$val."</div>";
										}
									}
								}
					$booking_details .="<tr style='border: 1px solid #D9E4E6;'>
					<td style='display: table-cell;text-align: center;border-right: 1px solid #D9E4E6;padding: 1em;color: #024457;'>".$cart_item['service_title']."</td>
					<td style='display: table-cell;text-align: center;border-right: 1px solid #D9E4E6;padding: 1em;color: #024457;'>".$cart_item['book_time_slot']."</td>
					<td style='display: table-cell;text-align: center;border-right: 1px solid #D9E4E6;padding: 1em;color: #024457;'>".date_i18n('Y-m-d H:i:s',strtotime("+".$cart_item['duration']." minutes", strtotime(date_i18n('Y-m-d',strtotime($cart_item['book_time_slot'])).' '.date_i18n('H:i:s',strtotime($cart_item['book_time_slot'])))))."</td>
					<td style='display: table-cell;text-align: center;border-right: 1px solid #D9E4E6;padding: 1em;color: #024457;'>".get_option('appointment_currency_symbol'.'_'.$bwid)." ".$cart_item['amount']."</td>
				  </tr>";/* print_r($booking_details); */
					
				}
				 /* $user_extra_info = get_user_meta($user_id,'apt_client_extra_details');
								 if($user_extra_info != '') { 
									foreach($user_extra_info as $user_extra_info2){
										$unser_date = unserialize($user_extra_info2);
										
										$sec_unser_data = unserialize($unser_date);
										foreach($sec_unser_data as $key=>$val){
											
										$booking_details .= "<tr style='border: 1px solid #D9E4E6;'>
											<td style='display: table-cell;text-align: center;border-right: 1px solid #D9E4E6;padding: 1em;color: #024457;'>".$key.":".$val."</td>";									
										}
									}
								}  */
				
				$booking_details .= "</table>";
				
				
				
				/**************** Booking code *****************/
				
				/************ Booking SUCCESS *************/
				if($add_booking){
		
					/**************************Send mail to client***********************************/
					$get_company_name = get_option('appointment_company_name'.'_'.$bwid);
					$company_address = get_option('appointment_company_address'.'_'.$bwid);
					$company_city = get_option('appointment_company_city'.'_'.$bwid);
					$company_state = get_option('appointment_company_state'.'_'.$bwid);
					$company_zip = get_option('appointment_company_zip'.'_'.$bwid);
					$company_country = get_option('appointment_company_country'.'_'.$bwid);
					$company_phone = "-";
					$company_email = get_option('appointment_company_email'.'_'.$bwid);
					if(get_option('appointment_company_logo'.'_'.$bwid) == ''){ 
						$business_logo = $plugin_url.'/assets/images/company.png';
					}else{
						$business_logo = site_url()."/wp-content/uploads/".get_option('appointment_company_logo'.'_'.$bwid);	
					}
					$apt_email_templates->method = "A";
					$get_email_template = $apt_email_templates->get_emailtemplate_by_sending_method();
					foreach($get_email_template as $get_emails){
						/******************* Send Mail To Client ************************/
						if($get_emails->user_type == "C"){
						if($get_emails->email_template_name == "AC"){
							if($get_emails->email_template_status == "e"){
								/* Search Array */
								$searcharray = array('{{client_name}}','{{company_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{business_logo}}','{{booking_details}}','{{booking_date_start}}','{{booking_date_end}}','{{service_name}}','{{price}}','{{firstname}}','{{lastname}}','{{client_email}}','{{phone}}','{{payment_method}}');
								/* Replace Array */
								$replacearray = array($_SESSION['preff_username'],$get_company_name,$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$business_logo,$booking_details,$booking_date_start,$booking_date_end,$service_name,$price,$_SESSION['first_name'],$_SESSION['last_name'],$_SESSION['user_email'],$phone,$payment_method);

								if($get_emails->email_message == ''){
									$apt_emailcontent = $get_emails->default_message;
								}else{
									$apt_emailcontent = $get_emails->email_message;
								}								
									
								$receiver_emails = $_SESSION['user_email'];
								$subject1 = $get_emails->email_subject;
								$message.= str_replace($searcharray,$replacearray,$apt_emailcontent);
								$headers  = "MIME-Version: 1.0\r\n";
								$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

								$receiver_emails = explode ( ',', $receiver_emails );
								/* echo "<pre>";print_r($message); */
								foreach ( $receiver_emails as $to ) {
									$to = trim ( $to );
									if (wp_mail ( $to, $subject1, $message, $headers )) {	
										//echo "Mail Sent...";
									} else {
										//echo 'Error sending mail...';
									}
								}
								/* exit;								 */
							}
						}
						}
						
						/******************* --**-- End Send Mail To Client --**-- ************************/
					}
					/******************* --**-- End Send Mail Client --**-- ************************/
					
					/******************* Send Mail To Admin ************************/
					foreach($get_email_template as $get_emails){
						if($get_emails->user_type == "AM"){
						if($get_emails->email_template_name == "AA"){
							if($get_emails->email_template_status == "e"){
							
								$appoinment_client_detail = "Client Name: ".$_SESSION['preff_username']." <br/> Client Email: ".$_SESSION['user_email']." <br/> Password: N/A";
								$admin_username = get_option('appointment_email_sender_name'.'_'.$bwid);
								
								/* Search Array */
								$searcharray = array('{{admin_name}}','{{client_name}}','{{company_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{business_logo}}','{{booking_details}}','{{booking_date_start}}','{{booking_date_end}}','{{service_name}}','{{price}}','{{firstname}}','{{lastname}}','{{client_email}}','{{phone}}','{{payment_method}}');
								/* Replace Array */
								$replacearray = array($admin_username,$_SESSION['preff_username'],$get_company_name,$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$business_logo,$booking_details,$booking_date_start,$booking_date_end,$service_name,$price,$_SESSION['first_name'],$_SESSION['last_name'],$_SESSION['user_email'],$phone,$payment_method);
								
								if($get_emails->email_message == ''){
									$apt_emailcontent = $get_emails->default_message;
								}else{
									$apt_emailcontent = $get_emails->email_message;
								}	
								
								/* get admin email */
								$get_admin_email = get_option('appointment_email_sender_address'.'_'.$bwid);
								$receiver_new_emailsss = $get_admin_email;
								$subject1 = $get_emails->email_subject;
								$message = "";
								$message.= str_replace($searcharray,$replacearray,$apt_emailcontent);
								$headers  = "MIME-Version: 1.0\r\n";
								$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

								$receiver_new_emailsss = explode ( ',', $receiver_new_emailsss );

								foreach ( $receiver_new_emailsss as $to ) {
									$to = trim ( $to );
									if (wp_mail ( $to, $subject1, $message, $headers )) {	
										/*echo "Mail Sent to admin...";*/
									} else {
										/* echo 'Error sending mail...'; */
									}
								} 
							}
						}
						}			
					}
					/******************* Send Mail To Admin ************************/
					
					/******************* / sms sending code via Plivo / ************/
					
					
					if(get_option("appointment_sms_reminder_status".'_'.$bwid) == "Y"){
					include_once($plugin_base_ralative_path.'/objects/plivo.php');
					$plivo_sender_number = get_option('appointment_plivo_number'.'_'.$bwid);
					
					$booking_detail = $service_name_sms;
					
					
					
					
					if(get_option('appointment_sms_noti_plivo'.'_'.$bwid)=="E"){
						
						/******************* / sms sending code via Plivo to client / ************/
						if(get_option('appointment_plivo_client_sms_notification_status'.'_'.$bwid) == "E"){
							$searcharray = array('{{customer_name}}','{{booking_detail}}','{{company_name}}');
							$replacearray = array($_SESSION['preff_username'],$booking_detail,get_option("appointment_company_name".'_'.$bwid));
							$phone = $_SESSION['phone'];
							$auth_id = get_option('appointment_plivo_sid'.'_'.$bwid);
							$auth_token = get_option('appointment_plivo_auth_token'.'_'.$bwid);
							$p_client = new Plivo\RestAPI($auth_id, $auth_token, '', '');
							
							$template = $obj_sms_template->gettemplate_sms("C",'e','AC');
							
							if($template[0]->sms_template_status == "e"){
								if($template[0]->sms_message == "")
								{
									$message = strip_tags($template[0]->default_message);
								}
								else
								{
									$message = strip_tags($template[0]->sms_message);
								}
								$client_sms_body = str_replace($searcharray,$replacearray,$message);
								$params = array(
								'src' => $plivo_sender_number,
								'dst' => $phone,
								'text' => $client_sms_body,
								'method' => 'POST'
								);
								$response = $p_client->send_message($params);
							} 
						}
						/******************* / sms sending code via Plivo to Admin / ************/
						if(get_option('appointment_plivo_admin_sms_notification_status'.'_'.$bwid) == "E"){
								$admin_blog = get_bloginfo();
								$client_info = "Client Name ".$_SESSION['preff_username'];
								$searcharray = array('{{admin_manager_name}}','{{booking_detail}}','{{appoinment_client_detail}}','{{company_name}}');
								$replacearray = array($admin_blog,$booking_detail,$client_info,get_option("appointment_company_name".'_'.$bwid));
								$phone = get_option('appointment_plivo_admin_phone_no'.'_'.$bwid);
								$auth_id = get_option('appointment_plivo_sid'.'_'.$bwid);
								$auth_token = get_option('appointment_plivo_auth_token'.'_'.$bwid);
								$p_client = new Plivo\RestAPI($auth_id, $auth_token, '', '');
								
								$template = $obj_sms_template->gettemplate_sms("AM",'e','AA');
								
								if($template[0]->sms_template_status == "e"){
									if($template[0]->sms_message == "")
									{
										$message = strip_tags($template[0]->default_message);
									}
									else
									{
										$message = strip_tags($template[0]->sms_message);
									}
									
									$client_sms_body = str_replace($searcharray,$replacearray,$message);
									$params = array(
									'src' => $plivo_sender_number,
									'dst' => $phone,
									'text' => $client_sms_body,
									'method' => 'POST'
									);
									$response = $p_client->send_message($params);
								} 
							}
						}
					}
					/********************  / sms sending code in pilvo / ***********************/
					
					/************* / sms sending code in Twillo / *************/
					include_once($plugin_base_ralative_path.'/assets/twilio/Services/Twilio.php');
					if(get_option("appointment_sms_reminder_status".'_'.$bwid) == "Y")
					{
					$twillio_sender_number = get_option('appointment_twilio_number'.'_'.$bwid);
					
					
					
						/* Twillio Status */	
						if(get_option('appointment_sms_noti_twilio'.'_'.$bwid)=="E")
						{
							
						  /******************* / sms sending code via Twillio to client / ************/
						  if(get_option("appointment_twilio_client_sms_notification_status".'_'.$bwid) == "E")
						  {
							  $searcharray = array('{{customer_name}}','{{booking_detail}}','{{company_name}}','');
							  $replacearray = array($_SESSION['preff_username'],$booking_detail,get_option("appointment_company_name".'_'.$bwid));
							   $phone = $_SESSION['phone'];
							   $AccountSid = get_option('appointment_twilio_sid'.'_'.$bwid);
							   $AuthToken =  get_option('appointment_twilio_auth_token'.'_'.$bwid); 
							   $twilliosms_client = new Services_Twilio($AccountSid, $AuthToken);

							   $template = $obj_sms_template->gettemplate_sms("C",'e','AC');
							   if($template[0]->sms_template_status == "e") 
							   {
								  if($template[0]->sms_message == "")
									{
									 $message = strip_tags($template[0]->default_message);
									}
									else
									{
									 $message = strip_tags($template[0]->sms_message);
									}
									$client_sms_body = str_replace($searcharray,$replacearray,$message);
									
									$message = $twilliosms_client->account->messages->create(array(
									 "From" => $twillio_sender_number,
									 "To" => $phone,
									 "Body" => $client_sms_body));
								}
						  }
						
						/************************* Client sms End *******************************/
						 /******************* / sms sending code via Twillio to Admin / ************/
						 if(get_option("appointment_twilio_admin_sms_notification_status".'_'.$bwid) == "E"){
						   $admin_blog = get_bloginfo();
						   $client_info = "Client Name ".$_SESSION['preff_username'];
						   $searcharray = array('{{admin_manager_name}}','{{booking_detail}}','{{appoinment_client_detail}}','{{company_name}}','');
						   $replacearray = array($admin_blog,$booking_detail,$client_info,get_option("appointment_company_name".'_'.$bwid));  
						   $phone = get_option('appointment_twilio_admin_phone_no'.'_'.$bwid);
						   $AccountSid = get_option('appointment_twilio_sid'.'_'.$bwid);
						   $AuthToken =  get_option('appointment_twilio_auth_token'.'_'.$bwid); 
						   $twilliosms_client = new Services_Twilio($AccountSid, $AuthToken);

						   $template = $obj_sms_template->gettemplate_sms("AM",'e','AA');
						  
						   if($template[0]->sms_template_status == "e") {
								if($template[0]->sms_message == ""){
								 $message = strip_tags($template[0]->default_message);
								}
								else{
								 $message = strip_tags($template[0]->sms_message);
								}
								$client_sms_body = str_replace($searcharray,$replacearray,$message);
								
								$message = $twilliosms_client->account->messages->create(array(
								 "From" => $twillio_sender_number,
								 "To" => $phone,
								 "Body" => $client_sms_body));
							}
						}
					}
					}
					/************* / sms sending code in Twillo end / *************/
					echo "Booking success"; 
					if($user_status != 'guest_user'){
					?> 
					<script>window.location.href = '<?php echo site_url(); ?>/wp-content/plugins/appointment/frontend/apt_thankyou.php';</script>
					<?php
					}else{
					?> 
					<script>window.location.href = '<?php echo site_url(); ?>';</script>
					<?php
					}
				unset($_SESSION['appointment_cart']);
				}else{ /*echo "not success";*/ }
				
				/**************** Booking Code End *****************/
				
				
				/* insert data in payment table */
				$apt_payments->location_id = $_SESSION['selected_location_id'];
				$apt_payments->client_id = $new_apt_user;
				$apt_payments->order_id = $order_id;
				$apt_payments->payment_method = "paypal";
				$apt_payments->transaction_id = $_SESSION['order_transaction_id'];
				$apt_payments->amount = $booking_total_amount;
				if(isset($_SESSION['coupon_discount'])){
				$apt_payments->discount = $_SESSION['coupon_discount'];
				}
				if(isset($_SESSION['tax_amount'])){
					$apt_payments->taxes = $_SESSION['tax_amount'];
				}
				if(isset($_SESSION['partial_amount_deposit'])){
				$apt_payments->partial = $_SESSION['partial_amount_deposit'];
				}
				$final_net_total = ($booking_total_amount - $_SESSION['coupon_discount']) + $_SESSION['tax_amount'];
				$apt_payments->net_total = $final_net_total;
				$apt_payments->business_owner_id = $_SESSION['apt_detail']['bwid'];
				$add_payment = $apt_payments->add_payments();
				if($add_payment){ echo "Payment success"; }else{ echo "not done payment"; }
				
			}
		  
		}
	}
?>