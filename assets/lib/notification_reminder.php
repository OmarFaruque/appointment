<?php 
/* if access from public url */
if( $_SERVER['REMOTE_ADDR'] != $_SERVER['SERVER_ADDR'] ){
    die('access is not permitted');
}

	include_once(dirname(dirname(dirname(__FILE__))).'/objects/plivo.php');
	require_once dirname(dirname(dirname(__FILE__))).'/assets/Twilio/autoload.php'; 
	use Twilio\Rest\Client;

	$root = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));		
	if (file_exists($root.'/wp-load.php')) {		
		require_once($root.'/wp-load.php');		
	}

	/* require(dirname(dirname(__FILE__)).'/twilio/Services/Twilio.php'); */
	$AccountSid = get_option('appointment_twilio_sid');
	$AuthToken =  get_option('appointment_twilio_auth_token');
	/* $client = new Services_Twilio($AccountSid, $AuthToken); */
	
  function set_content_type() {
	return 'text/html';
  }
  
  $aptabookbj = new appointment_booking(); 
  $apt_service = new appointment_service();
  $apt_staff = new appointment_staff();   
  $email_template = new appointment_email_template();
  $msg_template = $email_template->email_parent_template;	
  $objorderclient = new appointment_order();  
  $objpayment = new appointment_payments();
  $apt_location = new appointment_location();  
  $obj_sms_template = new appointment_sms_template();
  
  
  $bookings = $aptabookbj->read_all_upcoming_bookings_reminder_notification();
  
  $currtime = date_i18n('Y-m-d H:i:s');   	
  $company_name = get_option('appointment_company_name');
  $company_address = get_option('appointment_company_address');
  $company_city = get_option('appointment_company_city');
  $company_state = get_option('appointment_company_state');
  $company_zip = get_option('appointment_company_zip');
  $company_country = get_option('appointment_company_country');
  $company_phone = get_option('appointment_company_country_code').get_option('appointment_company_phone');
  $company_email = get_option('appointment_company_email');
  $company_logo = $business_logo = site_url()."/wp-content/uploads/".get_option('appointment_company_logo');
  $sender_name = get_option('appointment_email_sender_name');
  $sender_email_address = get_option('appointment_email_sender_address');
  $headers = "From: $sender_name <$sender_email_address>" . "\r\n";
  
  foreach($bookings as $single_booking){
	  $service_start_time = date_i18n('Y-m-d H:i:s',strtotime($single_booking->booking_datetime));
	  $date1=strtotime($service_start_time);		
	  $date2=strtotime($currtime);		
	  $diff  = abs($date1 - $date2);		
	  $rem_minutes   = round($diff / 60);		
	  $reminder_buffer_time = get_option('appointment_email_reminder_buffer'); 		


	 if($reminder_buffer_time >= $rem_minutes){
				  
		$aptabookbj->reminder_buffer = $single_booking->reminder+1;
		$aptabookbj->booking_id=$single_booking->id;
		$aptabookbj->update_booking_reminder_buffer_status();
		$apt_service->id = $single_booking->service_id;                    
		$apt_staff->id = $single_booking->provider_id;                    
		$apt_service->readOne();                    
		$staffinfo = $apt_staff->readOne();                
		$datetime = explode(' ',$single_booking->booking_datetime);        
		
		$location_title = '';
		$location_description = '';
		$location_email = '';
		$location_phone = '';
		$location_address = '';
		$location_city = '';
		$location_state = '';
		$location_zip = '';
		$location_country = '';

		if($single_booking->location_id!=0 || $single_booking->location_id!=''){
			$apt_location->id = $single_booking->location_id;
			$locationinfo = $apt_location->readOne();
			if(sizeof($locationinfo)>0){
				$location_title = stripslashes_deep($locationinfo[0]->location_title);
				$location_description = stripslashes_deep($locationinfo[0]->description);
				$location_email = $locationinfo[0]->email;				
				$location_phone = $locationinfo[0]->phone;
				$location_address = stripslashes_deep($locationinfo[0]->address);
				$location_city = stripslashes_deep($locationinfo[0]->city);
				$location_state = stripslashes_deep($locationinfo[0]->state);
				$location_zip = stripslashes_deep($locationinfo[0]->zip);
				$location_country = stripslashes_deep($locationinfo[0]->country);
			}
		}
		
		
		$addons_detail = '';
		$addon_titles = '';
		$addon_prices = '';
		$addon_qty = '';
		$appointmentbj->order_id =  $single_booking->order_id;
		$serviceaddons_info = $appointmentbj->select_addonsby_orderidand_serviceid();	
		$totalserviceaddons = sizeof($serviceaddons_info);
		if($totalserviceaddons>0){
			$addoncounter = 1;
			foreach($serviceaddons_info as $serviceaddon_info){				
				$apt_service->addon_id = $serviceaddon_info->addons_service_id;
				$addon_info = $apt_service->readOne_addon();
				if($addoncounter==$totalserviceaddons){
					$addon_titles .= stripslashes_deep($addon_info[0]->addon_service_name); 
					$addon_prices .= $serviceaddon_info->addons_service_rat; 
					$addon_qty .= $serviceaddon_info->associate_service_d; 
				}else{
					$addon_titles .= stripslashes_deep($addon_info[0]->addon_service_name).','; 
					$addon_prices .= $serviceaddon_info->addons_service_rat.',';
					$addon_qty .= $serviceaddon_info->associate_service_d.',';
				}				
				$addoncounter++;
			}			
			$addons_detail .="<br/><span><strong>".__('Addon Tittle(s)','apt')."</strong>: ".$addon_titles."</span><br/><br/><span><strong>".__('Addon Price(s)','apt')."</strong>: ".$addon_prices."</span><br/><br/><span><strong>".__('Addon Quantity(s)','apt')."</strong>: ".$addon_qty."</span><br/><br/>";			
		}
				
		$objpayment->order_id = $single_booking->order_id;
		$objpayment->read_one_by_order_id();	
		
		$objorderclient->order_id = $single_booking->order_id;
		$objorderclient->readOne_by_order_id();		
		$client_name = ucwords($objorderclient->client_name);
		$client_email = $objorderclient->client_email;
		$client_phone = $objorderclient->client_phone;
		$client_personal_info  = unserialize($objorderclient->client_personal_info);
		$client_address = '';
		if(isset($client_personal_info['address1'])){
			 $client_address = $client_personal_info['address1'];
		}
		$client_city = '';
		if(isset($client_personal_info['city'])){
			 $client_city = $client_personal_info['city'];
		}
		$client_zip = '';
		if(isset($client_personal_info['zip'])){
			 $client_zip = $client_personal_info['zip'];
		}
		$client_gender = '';
		if(isset($client_personal_info['gender'])){
			 $client_gender = $client_personal_info['gender'];
		}
		$client_dateofbirth = '';
		if(isset($client_personal_info['dob'])){
			 $client_dateofbirth = $client_personal_info['dob'];
		}
		$client_age = '';
		if(isset($client_personal_info['age'])){
			 $client_age = $client_personal_info['age'];
		}
		$client_skype = '';
		if(isset($client_personal_info['skype'])){
			 $client_skype = $client_personal_info['skype'];
		}
		$client_notes = '';
		if(isset($client_personal_info['notes'])){
			 $client_notes = $client_personal_info['notes'];
		}
		$client_state = '';
		if(isset($client_personal_info['state'])){
			 $client_state = $client_personal_info['state'];
		}
		$client_ccode = '';
		if(isset($client_personal_info['ccode'])){
			 $client_ccode = $client_personal_info['ccode'];
		}
		 
	    			
		$booking_details = "<br/><span><strong>".__('For','apt')."</strong>: ".stripslashes_deep($apt_service->service_title)."</span><br/><br/>
								<span><strong>".__('With','apt')."</strong>: ".ucwords(stripslashes_deep($staffinfo[0]['staff_name']))."</span><br/><br/>
								<span><strong>".__('On','apt')."</strong>: ".date_i18n(get_option('date_format'),strtotime($single_booking->booking_datetime))."</span><br/><br/>
								<span><strong>".__('At','apt')."</strong>: ".date_i18n(get_option('time_format'),strtotime($single_booking->booking_datetime))."</span><br/>";						
	
	
		$client_full_detail='<br/>';
		if($client_name!=''){ 
			$client_full_detail .="<span><strong>".__('Client Name','apt')."</strong>: ".$client_name."</span><br/><br/>";
		}
		if($client_email!=''){ 
			$client_full_detail .="<span><strong>".__('Client Email','apt')."</strong>: ".$client_email."</span><br/><br/>";
		}	
		if($client_phone!=''){ 
			$client_full_detail .="<span><strong>".__('Client Phone','apt')."</strong>: ".$client_ccode.$client_phone."</span><br/><br/>";
		}	
		if($client_gender!=''){
			$client_full_detail .="<span><strong>".__('Gender','apt')."</strong>: ".$client_gender."</span><br/><br/>";
		}
		if($client_dateofbirth!=''){
			$client_full_detail .="<span><strong>".__('DOB','apt')."</strong>: ".$client_dateofbirth."</span><br/><br/>";
		}
		if($client_age!=''){
			$client_full_detail .="<span><strong>".__('Age','apt')."</strong>: ".$client_age."</span><br/><br/>";
		}
		if($user_phone!=''){
			$client_full_detail .="<span><strong>".__('Client Phone','apt')."</strong>: ".$user_phone."</span><br/><br/>";
		}
		if($client_address!=''){
			$client_full_detail .="<span><strong>".__('Address','apt')."</strong>: ".$client_address."</span><br/><br/>";
		}
		if($client_city!=''){
			$client_full_detail .="<span><strong>".__('City','apt')."</strong>: ".$client_city."</span><br/><br/>";
		}
		if($client_state!=''){
			$client_full_detail .="<span><strong>".__('State','apt')."</strong>: ".$client_state."</span><br/><br/>";
		}
		if($client_zip!=''){
			$client_full_detail .="<span><strong>".__('Zip','apt')."</strong>: ".$client_zip."</span><br/><br/>";
		}
		if($client_skype!=''){
			$client_full_detail .="<span><strong>".__('Skype','apt')."</strong>: ".$client_skype."</span><br/><br/>";
		}
		if($client_notes!=''){
			$client_full_detail .="<span><strong>".__('Notes','apt')."</strong>: ".$client_notes."</span><br/><br/>";
		}
	
		
		$search = array('{{company_name}}','{{service_name}}','{{service_provider_name}}','{{customer_name}}','{{client_address}}','{{client_city}}','{{client_zip}}','{{client_phone}}','{{client_email}}','{{client_gender}}','{{client_dateofbirth}}','{{client_age}}','{{client_skype}}','{{client_state}}','{{appointment_id}}','{{appointment_date}}','{{appointment_time}}','{{net_amount}}','{{discount_amount}}','{{payment_method}}','{{taxes_amount}}','{{partial_amount}}','{{provider_email}}','{{provider_phone}}','{{provider_appointment_reject_link}}','{{provider_appointment_confirm_link}}','{{appointment_reject_reason}}','{{appointment_cancel_reason}}','{{appointment_confirm_note}}','{{appointment_reschedle_note}}','{{appointment_previous_date}}','{{appointment_previous_time}}','{{admin_manager_name}}','{{client_appointment_cancel_link}}','{{booking_details}}','{{appoinment_client_detail}}','{{addons_details}}','{{location_title}}','{{location_description}}','{{location_email}}','{{location_phone}}','{{location_address}}','{{location_city}}','{{location_state}}','{{location_zip}}','{{location_country}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');      
				
		$replace_with = array($company_name,stripslashes_deep($apt_service->service_title),ucwords(stripslashes_deep($staffinfo[0]['staff_name'])),$client_name,$client_address,$client_city,$client_zip,$client_phone,$client_email,$client_gender,$client_dateofbirth,$client_age,$client_skype,$client_state,$single_booking->id,date_i18n(get_option('date_format'),strtotime($single_booking->booking_datetime)),date_i18n(get_option('time_format'),strtotime($single_booking->booking_datetime)),$objpayment->net_total,$objpayment->discount,$objpayment->payment_method,$objpayment->taxes,$objpayment->partial,$staffinfo[0]['phone'],'','','','',$single_booking->confirm_note,'','','',$sender_name,'',$booking_details,$client_full_detail,$addons_detail,$location_title,$location_description,$location_email,$location_phone,$location_address,$location_city,$location_state,$location_zip,$location_country,$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo); 
		
		/******************* Send Email Notification *********************/
		
		/* Send email to Client when booking is complete */	
		if(get_option('appointment_client_email_notification_status')=='E'){	
			$apt_clientemail_templates = new appointment_email_template();
			$msg_template = $apt_clientemail_templates->email_parent_template;	
			$apt_clientemail_templates->email_template_name = "RMC";
			$template_detail = $apt_clientemail_templates->readOne();        
			if($template_detail[0]->email_message!=''){            
				$email_content = $template_detail[0]->email_message;        
			}else{            
				$email_content = $template_detail[0]->default_message;        
			}        
			$email_subject = $template_detail[0]->email_subject;
			$email_client_message = '';
			/* Sending email to client when New Appointment request Sent */ 		  
			if($template_detail[0]->email_template_status=='e'){
				$email_client_message = str_replace($search,$replace_with,$email_content);	
				$email_client_message = str_replace('###msg_content###',$email_client_message,$msg_template);		
				add_filter( 'wp_mail_content_type', 'set_content_type' );				
				$status = wp_mail($client_email,$email_subject,$email_client_message,$headers);           
			}       
		}
		/* Send email to service provider when booking is complete */	
		if(get_option('appointment_service_provider_email_notification_status')=='E'){	
			$apt_staffemail_templates = new appointment_email_template();	
			$msg_template = $apt_staffemail_templates->email_parent_template;
			$apt_staffemail_templates->email_template_name = "RMS";  
			$template_detail = $apt_staffemail_templates->readOne();        
			if($template_detail[0]->email_message!=''){            
				$email_content = $template_detail[0]->email_message;        
			}else{            
				$email_content = $template_detail[0]->default_message;
			}        
			$email_subject = $template_detail[0]->email_subject;   
			$email_staff_message = '';
			
			if($template_detail[0]->email_template_status=='e'){								
				$email_staff_message = str_replace($search,$replace_with,$email_content);	
				$email_staff_message = str_replace('###msg_content###',$email_staff_message,$msg_template);		
				add_filter( 'wp_mail_content_type', 'set_content_type' );  				
				$status = wp_mail($staffinfo[0]['email'],$email_subject,$email_staff_message,$headers);  
			}
		}	
		/* Send email to Admin when booking is complete */
		if(get_option('appointment_admin_email_notification_status')=='E'){
			$apt_adminemail_templates = new appointment_email_template();
			$msg_template = $apt_adminemail_templates->email_parent_template;
			$apt_adminemail_templates->email_template_name = "RMA";  		
			$template_detail = $apt_adminemail_templates->readOne();        
			if($template_detail[0]->email_message!=''){            
				$email_content = $template_detail[0]->email_message;        
			}else{            
				$email_content = $template_detail[0]->default_message;
			}        
			$email_subject = $template_detail[0]->email_subject;
			$email_admin_message = '';				
			$email_admin_message = str_replace($search,$replace_with,$email_content);	
			$email_admin_message = str_replace('###msg_content###',$email_admin_message,$msg_template);
			add_filter( 'wp_mail_content_type', 'set_content_type' );		
			$status = wp_mail(get_option('appointment_email_sender_address'),$email_subject,$email_admin_message,$headers);	
		}
		/* Send Email Notification End Here */
		
		/******************* Send SMS Notification *********************/
		if(get_option("appointment_sms_reminder_status") == "E"){			
		
			/*******************  SMS sending code via Plivo  **************/
			if(get_option('appointment_sms_noti_plivo')=="E"){
				$plivo_sender_number = get_option('appointment_plivo_number');	
				$auth_sid = get_option('appointment_plivo_sid');
				$auth_token = get_option('appointment_plivo_auth_token');	
				/* Send SMS To Client */
				if(get_option('appointment_plivo_client_sms_notification_status') == "E"){				
					$p_client = new Plivo\RestAPI($auth_sid, $auth_token, '', '');					
					$template = $obj_sms_template->gettemplate_sms("C",'e','RMC');					
					if($template[0]->sms_template_status == "e" && $client_phone!=''){
						if($template[0]->sms_message == ""){
							$message = strip_tags($template[0]->default_message);
						}else{
							$message = strip_tags($template[0]->sms_message);
						}
						$client_sms_body = str_replace($search,$replace_with,$message);
						$clientparams = array(
						'src' => $plivo_sender_number,
						'dst' => $client_ccode.$client_phone,
						'text' => $client_sms_body,
						'method' => 'POST'
						);
						$response = $p_client->send_message($clientparams);
					} 
				}
				/* Send SMS To Staff */
				if(get_option('appointment_plivo_service_provider_sms_notification_status') == "E"){		
					$p_staff = new Plivo\RestAPI($auth_id, $auth_token, '', '');					
					$template = $obj_sms_template->gettemplate_sms("SP",'e','RMS');					
					if($template[0]->sms_template_status == "e" && $staffinfo[0]['phone']!=''){
						if($template[0]->sms_message == ""){
							$message = strip_tags($template[0]->default_message);
						}else{
							$message = strip_tags($template[0]->sms_message);
						}						
						$staff_sms_body = str_replace($search,$replace_with,$message);
						$staffparams = array(
						'src' => $plivo_sender_number,
						'dst' => $staffinfo[0]['phone'],
						'text' => $staff_sms_body,
						'method' => 'POST'
						);
						$response = $p_staff->send_message($staffparams);
					} 
				}
				/* Send SMS To Admin */
				if(get_option('appointment_plivo_admin_sms_notification_status') == "E"){					
					$p_admin = new Plivo\RestAPI($auth_id, $auth_token, '', '');					
					$template = $obj_sms_template->gettemplate_sms("AM",'e','RMA');					
					if($template[0]->sms_template_status == "e" && get_option('appointment_plivo_admin_phone_no')!=''){
						if($template[0]->sms_message == ""){
							$message = strip_tags($template[0]->default_message);
						}else{
							$message = strip_tags($template[0]->sms_message);
						}						
						$admin_sms_body = str_replace($search,$replace_with,$message);
						$adminparams = array(
						'src' => $plivo_sender_number,
						'dst' => get_option('appointment_plivo_ccode').get_option('appointment_plivo_admin_phone_no'),
						'text' => $admin_sms_body,
						'method' => 'POST'
						);
						$response = $p_admin->send_message($adminparams);
					} 
				}			
				
			}
			/* Plivo SMS Sending End Here */
			
			
			/*******************  SMS sending code via Twilio  **************/
			if(get_option('appointment_sms_noti_twilio')=="E"){
			   $twillio_sender_number = get_option('appointment_twilio_number');
			   $AccountSid = get_option('appointment_twilio_sid');
			   $AuthToken =  get_option('appointment_twilio_auth_token'); 
			   /* Send SMS To Client */
			   if(get_option('appointment_twilio_client_sms_notification_status') == "E"){
				$twilliosms_client = new Client($AccountSid, $AuthToken);
				$template = $obj_sms_template->gettemplate_sms("C",'e','RMC');					
					if($template[0]->sms_template_status == "e" && $client_phone!=''){
						if($template[0]->sms_message == ""){
							$message = strip_tags($template[0]->default_message);
						}else{
							$message = strip_tags($template[0]->sms_message);
						}
						$client_sms_body = str_replace($search,$replace_with,$message);		

						$twilliosms_client->messages->create(
							$client_ccode.$client_phone,
							array(
								'from' => $twillio_sender_number,
								'body' => $client_sms_body 
							)
						);
					}		
			   }
			   /* Send SMS To Staff */
			   if(get_option('appointment_twilio_service_provider_sms_notification_status') == "E"){		   
				$twilliosms_staff = new Client($AccountSid, $AuthToken);
				$template = $obj_sms_template->gettemplate_sms("SP",'e','RMS');					
					if($template[0]->sms_template_status == "e" && $staffinfo[0]['phone']!=''){
						if($template[0]->sms_message == ""){
							$message = strip_tags($template[0]->default_message);
						}else{
							$message = strip_tags($template[0]->sms_message);
						}
						$staff_sms_body = str_replace($search,$replace_with,$message);
						$twilliosms_staff->messages->create(
							$staffinfo[0]['phone'],
							array(
								'from' => $twillio_sender_number,
								'body' => $staff_sms_body 
							)
						);
					}		
			   }
			   /* Send SMS To Admin */
			   if(get_option('appointment_twilio_admin_sms_notification_status') == "E"){		   
				$twilliosms_admin = new Client($AccountSid, $AuthToken);
				$template = $obj_sms_template->gettemplate_sms("AM",'e','RMA');					
					if($template[0]->sms_template_status == "e" && get_option('appointment_twilio_admin_phone_no')!=''){
						if($template[0]->sms_message == ""){
							$message = strip_tags($template[0]->default_message);
						}else{
							$message = strip_tags($template[0]->sms_message);
						}
						$admin_sms_body = str_replace($search,$replace_with,$message);
						$twilliosms_admin->messages->create(
							get_option('appointment_twilio_ccode').get_option('appointment_twilio_admin_phone_no'),
							array(
								'from' => $twillio_sender_number,
								'body' => $admin_sms_body 
							)
						);
						
					}		
			   }				
			}
			/* Twilio SMS Sending End Here */
			
			/*******************  SMS sending code via Nexmo  **************/
			if(get_option('appointment_sms_noti_nexmo')=="E"){
			  include_once(dirname(dirname(dirname(__FILE__))).'/objects/class_nexmo.php');
			  $nexmo_client = new appointment_nexmo();
			  $nexmo_client->appointment_nexmo_apikey = get_option('appointment_nexmo_apikey');
			  $nexmo_client->appointment_nexmo_api_secret = get_option('appointment_nexmo_api_secret');
			  $nexmo_client->appointment_nexmo_form = get_option('appointment_nexmo_form');
			  /* Send SMS To Client */
			  if(get_option('appointment_nexmo_send_sms_client_status') == "E"){
				$template = $obj_sms_template->gettemplate_sms("C",'e','RMC');					
				 if($template[0]->sms_template_status == "e" && $client_phone!=''){
					if($template[0]->sms_message == ""){
							$message = strip_tags($template[0]->default_message);
						}else{
							$message = strip_tags($template[0]->sms_message);
						}
					$client_sms_body = str_replace($search,$replace_with,$message);
					$nexmo_client->send_nexmo_sms($client_ccode.$client_phone,$client_sms_body);
				}
			  }
			  /* Send SMS To Staff */
			  if(get_option('appointment_nexmo_send_sms_sp_status') == "E"){
				$template = $obj_sms_template->gettemplate_sms("SP",'e','RMS');					
					if($template[0]->sms_template_status == "e" && $staffinfo[0]['phone']!=''){
						if($template[0]->sms_message == ""){
							$message = strip_tags($template[0]->default_message);
						}else{
							$message = strip_tags($template[0]->sms_message);
						}
						$staff_sms_body = str_replace($search,$replace_with,$message);
						$nexmo_client->send_nexmo_sms($staffinfo[0]['phone'],$staff_sms_body);
					}
			  }
			  /* Send SMS To Admin */
			  if(get_option('appointment_nexmo_send_sms_admin_status') == "E"){
				$template = $obj_sms_template->gettemplate_sms("AM",'e','RMA');					
					if($template[0]->sms_template_status == "e" && get_option('appointment_nexmo_admin_phone_no')!=''){
						if($template[0]->sms_message == ""){
							$message = strip_tags($template[0]->default_message);
						}else{
							$message = strip_tags($template[0]->sms_message);
						}
						$admin_sms_body = str_replace($search,$replace_with,$message);
						$nexmo_client->send_nexmo_sms(get_option('appointment_nexmo_ccode').get_option('appointment_nexmo_admin_phone_no'),$admin_sms_body);
					}
			  }
			  
			}
			/* Nexmo SMS Sending End Here */
			
			/*******************  SMS sending code via TEXTLOCAL  **************/
			if(get_option('appointment_sms_noti_textlocal')=="E"){
			  $textlocal_apikey = get_option('appointment_textlocal_apikey');
			  $textlocal_sender = get_option('appointment_textlocal_sender');
			  /* Send SMS To Client */
			  if(get_option('appointment_textlocal_client_sms_notification_status') == "E"){
				$template = $obj_sms_template->gettemplate_sms("C",'e','RMC');					
				 if($template[0]->sms_template_status == "e" && $client_phone!=''){
					if($template[0]->sms_message == ""){
							$message = strip_tags($template[0]->default_message);
						}else{
							$message = strip_tags($template[0]->sms_message);
						}
					$client_sms_body = str_replace($search,$replace_with,$message);
					
					$textlocal_numbers = $client_ccode.$client_phone;
					$textlocal_sender = urlencode($textlocal_sender);
					$client_sms_body = rawurlencode($client_sms_body);
					
					$data = array('apikey' => $textlocal_apikey, 'numbers' => $textlocal_numbers, "sender" => $textlocal_sender, "message" => $client_sms_body);
					
					$ch = curl_init('https://api.textlocal.in/send/');
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$result = curl_exec($ch);
					curl_close($ch);
				}
			  }
			  /* Send SMS To Staff */
			  if(get_option('appointment_textlocal_service_provider_sms_notification_status') == "E"){
				$template = $obj_sms_template->gettemplate_sms("SP",'e','RMS');					
					if($template[0]->sms_template_status == "e" && $staffinfo[0]['phone']!=''){
						if($template[0]->sms_message == ""){
							$message = strip_tags($template[0]->default_message);
						}else{
							$message = strip_tags($template[0]->sms_message);
						}
						$staff_sms_body = str_replace($search,$replace_with,$message);
						
						$textlocal_numbers = $staffinfo[0]['phone'];
						$textlocal_sender = urlencode($textlocal_sender);
						$staff_sms_body = rawurlencode($staff_sms_body);
						
						$data = array('apikey' => $textlocal_apikey, 'numbers' => $textlocal_numbers, "sender" => $textlocal_sender, "message" => $staff_sms_body);
						
						$ch = curl_init('https://api.textlocal.in/send/');
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						$result = curl_exec($ch);
						curl_close($ch);
					}
			  }
			  /* Send SMS To Admin */
			  if(get_option('appointment_textlocal_admin_sms_notification_status') == "E"){
					$template = $obj_sms_template->gettemplate_sms("AM",'e','RMA');					
					if($template[0]->sms_template_status == "e" && get_option('appointment_textlocal_admin_phone_no')!=''){
						if($template[0]->sms_message == ""){
							$message = strip_tags($template[0]->default_message);
						}else{
							$message = strip_tags($template[0]->sms_message);
						}
						$admin_sms_body = str_replace($search,$replace_with,$message);
						
						$textlocal_numbers = get_option('appointment_textlocal_ccode').get_option('appointment_textlocal_admin_phone_no');
						$textlocal_sender = urlencode($textlocal_sender);
						$admin_sms_body = rawurlencode($admin_sms_body);
						
						$data = array('apikey' => $textlocal_apikey, 'numbers' => $textlocal_numbers, "sender" => $textlocal_sender, "message" => $admin_sms_body);
						
						$ch = curl_init('https://api.textlocal.in/send/');
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						$result = curl_exec($ch);
						curl_close($ch);
					}
			  }
			  
			}
			/* TEXTLOCAL SMS Sending End Here */			
		}
		/* Send SMS Notification End Here */
						
	 }	  
 }
?>