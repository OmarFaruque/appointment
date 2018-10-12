<?php 
session_start();
$root = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));

if (file_exists($root.'/wp-load.php')) {
	require_once($root.'/wp-load.php');
}
if ( ! defined( 'ABSPATH' ) ) exit;  /* direct access prohibited  */

	$category = new appointment_category();
	$apt_location = new appointment_location();
	$location = new appointment_location();
	$apt_service = new appointment_service();
	$service = new appointment_service();
	$general = new appointment_general();
	$payments= new appointment_payments();
	$staff = new appointment_staff();
	$apt_staff = new appointment_staff();
	$order_info = new appointment_order();
	$apt_booking = new appointment_booking();
	$apt_bookings = new appointment_booking();
	$provider = new appointment_staff();
	$clients = new appointment_clients();
	$coupons = new appointment_coupons();
	$reviews = new appointment_reviews();
	$email_template = new appointment_email_template();
	$loyalty_points = new appointment_loyalty_points();
	include_once(dirname(dirname(dirname(__FILE__))).'/objects/class_sms_templates.php');
	$obj_sms_template = new appointment_sms_template();
	
	$plugin_url_for_ajax = plugins_url('',dirname(dirname(__FILE__)));

	
	
	
	/* Email Content Type Header */ 
	function set_content_type() {			
		return 'text/html';		
	}
	
	
	function update_google_cal_event($calendarId,$provider_access_token,$eventid,$date,$start,$end,$providerTZ,$GcclientID,$GcclientSecret,$GcEDvalue,$bwid){
		$clientP = new Google_Client();
		$clientP->setApplicationName("Appointment Google Calender");
		$clientP->setClientId($GcclientID);
		$clientP->setClientSecret($GcclientSecret);
		$clientP->setRedirectUri(get_option('apt_gc_admin_url'.'_'.$bwid));
		$clientP->setDeveloperKey($GcclientID);
		$clientP->setScopes('https://www.googleapis.com/auth/calendar');
		$clientP->setAccessType('offline');
		$calP = new Google_CalendarService($clientP); 

		$clientP->setAccessToken($provider_access_token);
		$accesstoken = json_decode($provider_access_token);
		if($provider_access_token){
			if ($clientP->isAccessTokenExpired()) {
				$clientP->refreshToken($accesstoken->refresh_token);
			}
		}
		if ($clientP->getAccessToken()){
			$eventP = new Google_Event();      
			$startTP = new Google_EventDateTime();
			$endTP = new Google_EventDateTime();
			$eventd = $calP->events->get($calendarId,$eventid);
			$location = '';
			$summary = $eventd['summary'];
			$colorid = $eventd['colorId'];
			$description = $eventd['description']; 
			$startTP->setTimeZone($providerTZ);
			$startTP->setDateTime($date."T".$start);
			$endTP->setTimeZone($providerTZ);
			$endTP->setDateTime($date."T".$end);
			$eventP->setStart($startTP);
			$eventP->setEnd($endTP);
			$eventP->setSummary($summary);
			$eventP->setColorId($colorid);
			$eventP->setLocation($location);
			$eventP->setDescription($description); 

			$updatedEvent = $calP->events->update($calendarId,$eventid,$eventP);
			if(isset($updatedEvent)){
				return $updatedEvent;
			}else{
				return '';
			}
		}
	}
/* Set Location Session */	
if(isset($_POST['general_ajax_action'],$_POST['location_id']) && $_POST['general_ajax_action']=='set_location_session' && $_POST['location_id']!='' ){
	$_SESSION['apt_location'] = $_POST['location_id'];
}	
/* Get Payments By Range */
if(isset($_POST['general_ajax_action']) && $_POST['general_ajax_action']=='get_payments_byrange'){
	$payments->location_id = $_SESSION['apt_location'];
	$payments->business_owner_id = $_POST['bwid'];
	$general->business_owner_id = $_POST['bwid'];
	$all_payments=$payments->get_payments_byrange($_POST['payment_start'].' 00:00:00',$_POST['payment_end'].' 23:59:59');
	
	foreach($all_payments as $payment){ 
				$order_info->order_id = $payment->order_id;
				$order_info->readOne_by_order_id();
			 ?>
			<tr>
				<td><?php echo $order_info->client_name;?></td>	
				<?php if($payment->payment_method == 'paypal') { ?>
					<td><?php echo __("Paypal","apt");?></td>
				<?php }
				else if($payment->payment_method == 'pay_locally') { ?>
					<td><?php echo __("Pay Locally","apt");?></td>
				<?php }
				else if($payment->payment_method == 'Free') { ?>
					<td><?php echo __("Free","apt");?></td>
				<?php }
				else if($payment->payment_method == 'stripe') { ?>
					<td><?php echo __("Stripe","apt");?></td>
				<?php }
				else if($payment->payment_method == 'authorizenet') { ?>
					<td><?php echo __("Authorize.Net","apt");?></td>
				<?php }
				else if($payment->payment_method == 'payumoney') { ?>
					<td><?php echo __("Payumoney","apt");?></td>
				<?php }
				else if($payment->payment_method == 'paytm') { ?>
					<td><?php echo __("Paytm","apt");?></td>
				<?php }
				else{ 
					echo '<td>&nbsp;</td>';
				}	 ?>		
				<td><?php echo $general->apt_price_format($payment->amount);?></td>
				<td><?php echo $general->apt_price_format($payment->discount);?></td>
				<td><?php echo $general->apt_price_format($payment->taxes);?></td>
				<td><?php echo $general->apt_price_format($payment->partial);?></td>
				<td><?php echo $general->apt_price_format($payment->net_total);?></td>
			</tr>	
			<?php }	
	
}	

/** Get Registerd User All Bookings **/
if(isset($_POST['general_ajax_action'],$_POST['method']) && $_POST['general_ajax_action']=='get_client_bookings' && $_POST['method']=='registered'){
					
								
								$apt_bookings->client_id=$_POST['listing_client_id'];	
								$apt_bookings->location_id=$_SESSION['apt_location'];	
								$order_ids=$apt_bookings->get_order_ids_by_client_id();	
								
								foreach($order_ids as $order_id){	
								
									$clients->order_id=$order_id->order_id;
									$stmt[]= $clients->get_client_info_by_order_id();
								}
								$apt_bookings->client_id=$_POST['listing_client_id'];
								$apt_bookings->location_id=$_SESSION['apt_location'];
								$bookings=$apt_bookings->get_client_all_bookings_by_client_id();
								$client_totoal_bookings=sizeof($bookings);

								for($i=0;$i<=$client_totoal_bookings-1;$i++){
									
									$provider->id=$bookings[$i]->provider_id;
									$staff_info = $provider->readOne();
								
									$service->id=$bookings[$i]->service_id;
									$service->readOne(); 
									$client_other_detail=unserialize($stmt[$i][0]->client_personal_info);
									
									
									$payments->order_id = $bookings[$i]->order_id;
									$payments->read_one_by_order_id(); 
		 
									?>

									<tr>
									<td><?php echo __($bookings[$i]->order_id,"apt");?></td>
									<td><?php echo __(stripslashes_deep($stmt[$i][0]->client_name),"apt");?></td>
									<td><?php echo __(stripslashes_deep($staff_info[0]['staff_name']),"apt");?></td>
									<td><?php echo __(stripslashes_deep($service->service_title),"apt");?></td>
									<td><?php echo __(date_i18n(get_option('appointment_datepicker_format'.'_'.$bwid),strtotime($bookings[$i]->booking_datetime)),"apt");?></td>
									<td><?php echo __(date_i18n(get_option('time_format'),strtotime($bookings[$i]->booking_datetime)),"apt");?></td>
									
									<td>
									
									 <?php 
									 if($bookings[$i]->booking_status=='C'){  echo __('Confirmed',"apt"); }
									 if($bookings[$i]->booking_status=='R'){  echo __('Rejected',"apt"); }
									 if($bookings[$i]->booking_status=='CC'){  echo __('Cancelled by client',"apt"); }
									 if($bookings[$i]->booking_status=='A' || $bookings[$i]->booking_status=='' ){  
										echo __('Active',"apt"); }
									 if($bookings[$i]->booking_status=='CS'){ echo __('Cancelled by service provider',"apt");}
									 if($bookings[$i]->booking_status=='CO'){ echo __('Completed',"apt"); $booking_st = ''; }
									 if($bookings[$i]->booking_status=='MN'){ echo __('Marked as No-Show',"apt"); } ?>				
												</td>
									<?php if($payments->payment_method == 'paypal') { ?>
									<td><?php echo __("Paypal","apt");?></td>
									<?php }
									else if($payments->payment_method == 'pay_locally') { ?>
										<td><?php echo __("Pay Locally","apt");?></td>
									<?php }
									else if($payments->payment_method == 'Free') { ?>
										<td><?php echo __("Free","apt");?></td>
									<?php }
									else if($payments->payment_method == 'stripe') { ?>
										<td><?php echo __("Stripe","apt");?></td>
									<?php }
									else if($payments->payment_method == 'authorizenet') { ?>
										<td><?php echo __("Authorize .Net","apt");?></td>
									<?php }
									else if($payments->payment_method == 'payumoney') { ?>
										<td><?php echo __("Payumoney","apt");?></td>
									<?php }
									else if($payments->payment_method == 'paytm') { ?>
										<td><?php echo __("Paytm","apt");?></td>
									<?php }
									else{ 
										echo '<td>&nbsp;</td>';
									} ?>	

								<td>
								
								<?php if($client_other_detail['address']!='') { ?>
								<div class="col-xs-12 np"><b><?php
								echo __("Address","apt");?></b> - <?php echo __($client_other_detail['address'],"apt"); 
								?></div><?php
								} ?>
								
								<?php if($client_other_detail['gender']!='') { ?>
								<div class="col-xs-12 np"><b><?php
								echo __("Gender","apt");?></b> - <?php echo __($client_other_detail['gender'],"apt"); 
								?></div><?php
								} ?>
								
								<?php if($client_other_detail['phone1']!='') { ?>
								<div class="col-xs-12 np"><b><?php
								echo __("Phone","apt");?></b> - <?php echo __($client_other_detail['ccode'].' '.$client_other_detail['phone1'],"apt"); 
								?></div><?php
								} ?>
								
								<?php if($client_other_detail['age']!='') { ?>
								<div class="col-xs-12 np"><b><?php
								echo __("Age","apt");?></b> - <?php echo __($client_other_detail['age'],"apt"); 
								?></div><?php
								} ?>
				
								<?php if($client_other_detail['dob']!='') { ?>
								<div class="col-xs-12 np"><b><?php
								echo __("DOB","apt");?></b> - <?php echo __($client_other_detail['dob'],"apt"); 
								?></div><?php
								} ?>
								<?php if($client_other_detail['zip']!='') { 
								?>
								<div class="col-xs-12 np"><b><?php
								echo __("Zip","apt");?></b> - <?php echo __($client_other_detail['zip'],"apt"); 
								?></div><?php
								} ?>
								<?php if(stripslashes_deep($client_other_detail['city']!='')) { ?>
								<div class="col-xs-12 np"><b><?php
								echo __("City","apt");?></b> - <?php echo __(stripslashes_deep($client_other_detail['city']),"apt"); 
								?></div><?php
								}?>
								<?php if($client_other_detail['skype']!='') { ?>
								<div class="col-xs-12 np"><b><?php
									echo __("Skype id","apt");?></b> - <?php echo __($client_other_detail['skype'],"apt"); 
								?></div><?php	
								}?>
								<?php if($client_other_detail['notes']!='') { ?>
								<div class="col-xs-12 np"><b><?php
									echo __("Notes","apt");?></b> - <?php echo __($client_other_detail['notes'],"apt"); 
								?></div><?php	
								}
								$user_extra_info = get_user_meta($_POST['listing_client_id'],'apt_client_extra_details');
								 if($user_extra_info != '') { 
									foreach($user_extra_info as $user_extra_info2){
										$unser_date = unserialize($user_extra_info2);
										
										$sec_unser_data = unserialize($unser_date);
										foreach($sec_unser_data as $key=>$val){
											?>
												<div class="col-xs-12 np"><b><?php echo $key;?></b> - <?php echo $val; 
												?></div><?php									
										}
									}
								}
									?>
									 
								
								
								<?php if($client_other_detail['phone1']=='' && $client_other_detail['age']=='' && $client_other_detail['dob']=='' && $client_other_detail['zip']==''&& $client_other_detail['city']==''&&$client_other_detail['skype']=='' && $client_other_detail['notes']=='' && $client_other_detail['address']=='' && $client_other_detail['gender']=='') {  echo "-"; } ?>
								
								</td>
									</tr>
								<?php }

}
/** Delete Registered Client & releated Info **/
if (isset($_POST['general_ajax_action'],$_POST['delete_id']) && $_POST['delete_id'] != '' && $_POST['general_ajax_action']=='delete_registered_client') {
		$clientlocations = explode(',',get_usermeta($_POST['delete_id'],'apt_client_locations'));
			foreach($clientlocations as $arrkey => $arrvalue){
				if($arrvalue=='#'.$_SESSION['apt_location'].'#'){
					$client_deleted_loc = $arrkey;
				}		
			}
		 $apt_bookings->client_id = $_POST['delete_id'];
		 $apt_bookings->location_id = $_SESSION['apt_location'];
         $all_booking = $apt_bookings->get_client_all_bookings_by_client_id();
			foreach($all_booking as $client_info){
				$clientlastoid = $client_info->order_id;
				$payments->order_id = $clientlastoid; 
				$payments->delete_payments_by_order_id();
				$order_info->order_id = $clientlastoid; ;   
				$order_info->delete_order_client_info_by_order_id();
				$apt_bookings->order_id = $clientlastoid;   
				$apt_bookings->delete_users_booking_by_order_id();
		}
		if(sizeof($clientlocations)==1){
			$clients->id = $_POST['delete_id'];
			$clients->delete_register_users_booking_by_id();   
		}else{
			unset($clientlocations[$client_deleted_loc]);
			update_usermeta($_POST['delete_id'],'apt_client_locations',implode(',',$clientlocations));
		}
	
}


/** Get Guest Client Bookings **/
if(isset($_POST['general_ajax_action'],$_POST['method']) && $_POST['general_ajax_action']=='get_client_bookings' && $_POST['method']=='guest'){
					$order_info->order_id = $_POST['listing_client_id'];
					$guesuser_order_details = $order_info->get_guest_users_record_with_order_id();
					foreach($guesuser_order_details as $guesuser_order_detail) {			
									$provider->id=$guesuser_order_detail->provider_id;
									$staff_info = $provider->readOne();
								
									$service->id=$guesuser_order_detail->service_id;
									$service->readOne(); 
									$client_other_detail=unserialize($guesuser_order_detail->client_personal_info);
									$payments->order_id = $guesuser_order_detail->order_id;
									$payments->read_one_by_order_id(); 
									?>
						<tr>
									<td><?php echo __($guesuser_order_detail->order_id,"apt");?></td>
									<td><?php echo __(stripslashes_deep($guesuser_order_detail->client_name),"apt");?></td>
									<td><?php echo __(stripslashes_deep($staff_info[0]['staff_name']),"apt");?></td>
									<td><?php echo __(stripslashes_deep($service->service_title),"apt");?></td>
									
									<td><?php echo __(date_i18n(get_option('appointment_datepicker_format'),strtotime($guesuser_order_detail->booking_datetime)),"apt");?></td>
									<td><?php echo __(date_i18n(get_option('time_format'),strtotime($guesuser_order_detail->booking_datetime)),"apt");?></td>
									
									<td>
										
									 <?php 
									 if($guesuser_order_detail->booking_status=='C'){  echo __('Confirmed',"apt"); }
									 if($guesuser_order_detail->booking_status=='R'){  echo __('Rejected',"apt"); }
									 if($guesuser_order_detail->booking_status=='CC'){  echo __('Cancelled by client',"apt"); }
									 if($guesuser_order_detail->booking_status=='A' || $guesuser_order_detail->booking_status=='' ){  echo __('Active',"apt"); }
									 if($guesuser_order_detail->booking_status=='CS'){ echo __('Cancelled by service provider',"apt");}
									 if($guesuser_order_detail->booking_status=='CO'){ echo __('Completed',"apt"); $booking_st = ''; }
									 if($guesuser_order_detail->booking_status=='MN'){  echo __('Marked as No-Show',"apt"); } ?>				
									</td>
									<?php if($payments->payment_method == 'paypal') { ?>
									<td><?php echo __("Paypal","apt");?></td>
									<?php }
									elseif($payments->payment_method == 'pay_locally') { ?>
										<td><?php echo __("Pay Locally","apt");?></td>
									<?php }
									elseif($payments->payment_method == 'Free') { ?>
										<td><?php echo __("Free","apt");?></td>
									<?php }
									elseif($payments->payment_method == 'stripe') { ?>
										<td><?php echo __("Stripe","apt");?></td>
									<?php }
									else if($payments->payment_method == 'authorizenet') { ?>
										<td><?php echo __("Authorize .Net","apt");?></td>
									<?php }
									else{ 
										echo '<td>&nbsp;</td>';
									} ?>
									<td>
									<?php /* if($client_other_detail['address']!='') { ?>
									<div class="col-xs-12 np"><b><?php
									echo __("Address","apt");?></b> - <?php echo __($client_other_detail['address'],"apt"); 
									?></div><?php
									} ?>
									
									<?php if($client_other_detail['gender']!='') { ?>
									<div class="col-xs-12 np"><b><?php
									echo __("Gender","apt");?></b> - <?php echo __($client_other_detail['gender'],"apt"); 
									?></div><?php
									} ?>
									
									<?php if($client_other_detail['phone1']!='') { ?>
									<div class="col-xs-12 np"><b><?php
									echo __("Phone","apt");?></b> - <?php echo __($client_other_detail['ccode'].' '.$client_other_detail['phone1'],"apt"); 
									?></div><?php
									} ?>
									
									<?php if($client_other_detail['age']!='') { ?>
									<div class="col-xs-12 np"><b><?php
									echo __("Age","apt");?></b> - <?php echo __($client_other_detail['age'],"apt"); 
									?></div><?php
									} ?>
					
									<?php if($client_other_detail['dob']!='') { ?>
									<div class="col-xs-12 np"><b><?php
									echo __("DOB","apt");?></b> - <?php echo __($client_other_detail['dob'],"apt"); 
									?></div><?php
									} ?>
									<?php if($client_other_detail['zip']!='') { 
									?>
									<div class="col-xs-12 np"><b><?php
									echo __("Zip","apt");?></b> - <?php echo __($client_other_detail['zip'],"apt"); 
									?></div><?php
									} ?>
									<?php if(stripslashes_deep($client_other_detail['city']!='')) { ?>
									<div class="col-xs-12 np"><b><?php
									echo __("City","apt");?></b> - <?php echo __(stripslashes_deep($client_other_detail['city']),"apt"); 
									?></div><?php
									}?>
									<?php if($client_other_detail['skype']!='') { ?>
									<div class="col-xs-12 np"><b><?php
										echo __("Skype id","apt");?></b> - <?php echo __($client_other_detail['skype'],"apt"); 
									?></div><?php
									}?>
									<?php if($client_other_detail['notes']!='') { ?>
									<div class="col-xs-12 np"><b><?php
										echo __("Notes","apt");?></b> - <?php echo __($client_other_detail['notes'],"apt"); 
									?></div><?php	
									} */?>
									<?php 
									$user_extra_info = $wpdb->get_results("SELECT *  FROM  ".$wpdb->prefix."apt_order_client_info  WHERE order_id =".$_POST['listing_client_id']);
									  if($user_extra_info != '') { 
									 foreach($user_extra_info as $user_extra_info2){
									   $unser_date = unserialize($user_extra_info2->client_personal_info);
									  foreach($unser_date as $key=>$val){
									   if($key == 'ccode' || $key == 'dob' || $key == 'zip' || $key == 'skype' || $key == 'age'){
									   ?>
										<?php 
									   }else{
										?>
										<div class="col-xs-12 np"><b><?php echo $key;?></b> - <?php echo $val; 
										?></div><?php
									   }  
									  } 
									 }
									} 
									?>
									<?php if($client_other_detail['phone1']=='' && $client_other_detail['age']=='' && $client_other_detail['dob']=='' && $client_other_detail['zip']==''&& $client_other_detail['city']==''&&$client_other_detail['skype']=='' && $client_other_detail['notes']=='' && $client_other_detail['address']=='' && $client_other_detail['gender']=='') {  echo "-"; } ?>							
							</td>
					</tr>
		<?php }
}

/** Delete Guest User Info & Releated Data like-Bookings,payments.order client info **/
if (isset($_POST['general_ajax_action'],$_POST['delete_id']) && $_POST['delete_id'] != '' && $_POST['general_ajax_action']=='delete_guest_client') {
		$payments->order_id = $_POST['delete_id']; 
		$payments->delete_payments_by_order_id();
		$order_info->order_id = $_POST['delete_id'] ;   
		$order_info->delete_order_client_info_by_order_id();
		$apt_bookings->order_id =$_POST['delete_id'];   
		$apt_bookings->delete_users_booking_by_order_id();
}

/** Get All Locations Customers Registered/Guest **/
if(isset($_POST['general_ajax_action']) && $_POST['general_ajax_action']=='get_all_locations_customers'){
	if(isset($_POST['alc']) && $_POST['alc']=='Y'){
		$_SESSION['apt_all_loc_clients'] = 'Y';
	}else{
		unset($_SESSION['apt_all_loc_clients']);
	}
}
/** Get All Locations Payments **/
if(isset($_POST['general_ajax_action']) && $_POST['general_ajax_action']=='get_all_locations_payments'){
	if(isset($_POST['alp']) && $_POST['alp']=='Y'){
		$_SESSION['apt_all_loc_payments'] = 'Y';
	}else{
		unset($_SESSION['apt_all_loc_payments']);
	}
}

/** Get Export Filtered Bookings Detail **/
if(isset($_POST['general_ajax_action']) && $_POST['general_ajax_action']=='filtered_bookings'){
	$general->business_owner_id = $_POST['bwid'];
	/* Read All Booking of Location */
	if(isset($_SESSION['apt_all_loc_export']) && $_SESSION['apt_all_loc_export']=='Y'){
		$apt_bookings->location_id = 'All';
	}else{
		$apt_bookings->location_id = $_SESSION['apt_location'];
	}
	//$apt_bookings->location_id = $_SESSION['apt_location'];
	$all_bookings = $apt_bookings->readAll($_POST['booking_start'],$_POST['booking_end'],$_POST['booking_service'],$_POST['booking_staff'],'Export');
	foreach($all_bookings as $single_booking){ 
			/* Staff Info */
			$staff->id=$single_booking->provider_id;
			$staff_info = $staff->readOne();
			/* Service Info */										
			$service->id=$single_booking->service_id;
			$service->readOne(); 
			/* Client Info */	
			$clients->order_id=$single_booking->order_id;
			$client_info = $clients->get_client_info_by_order_id();
										
			?>
		<tr>	
			<td><?php echo $single_booking->order_id;?></td>
			<td><?php echo __(stripslashes_deep($service->service_title),"apt");?></td>
			<td><?php echo __(stripslashes_deep($staff_info[0]['staff_name']),"apt");?></td>
			<td><?php echo __(date_i18n(get_option('appointment_datepicker_format'.'_'.$bwid),strtotime($single_booking->booking_datetime)),"apt");?></td>
			<td><?php echo __(date_i18n(get_option('time_format'),strtotime($single_booking->booking_datetime)),"apt");?> <?php echo __('to',"apt");?> <?php echo __(date_i18n(get_option('time_format'),strtotime($single_booking->booking_endtime)),"apt");?></td>
			<td><?php echo $general->apt_price_format($single_booking->booking_price);?></td>
			<td><?php echo __(stripslashes_deep($client_info[0]->client_name),"apt");?></td>
			<td><?php if($client_info[0]->client_phone!=''){ echo $client_info[0]->client_phone;} else{echo '-';} ?></td>
			<td><?php if($single_booking->booking_status=='C'){  echo __('Confirmed',"apt"); }
			if($single_booking->booking_status=='R'){  echo __('Rejected',"apt"); }
			if($single_booking->booking_status=='CC'){  echo __('Cancelled by client',"apt"); }
			if($single_booking->booking_status=='A' || $single_booking->booking_status=='' ){ echo __('Active',"apt"); }
			if($single_booking->booking_status=='CS'){ echo __('Cancelled by service provider',"apt");}
			if($single_booking->booking_status=='CO'){ echo __('Completed',"apt"); $booking_st = ''; }
			if($single_booking->booking_status=='MN'){ echo __('Marked as No-Show',"apt"); } ?></td>
		</tr>
<?php }	
}
/** Get All Locations Export Data **/
if(isset($_POST['general_ajax_action']) && $_POST['general_ajax_action']=='get_all_exportdata'){
	if(isset($_POST['aled']) && $_POST['aled']=='Y'){
		$_SESSION['apt_all_loc_export'] = 'Y';
	}else{
		unset($_SESSION['apt_all_loc_export']);
	}
}

/* Get Calender Upcomming Appointments */
if(isset($_GET['general_ajax_action']) && $_GET['general_ajax_action']=='get_upcoming_appointments'){
		//$current_user = wp_get_current_user();
		//$info = get_userdata($current_user->ID);
		$bwid = $_GET['bwid'];
		$apt_bookings->location_id = (current_user_can('apt_staff'))?get_user_meta(get_current_user_id(), 'staff_location', true):$_SESSION['apt_location'];
		$apt_bookings->business_owner_id = $bwid;
		
		
		$start_date= '';
		$end_date = '';
		$service_id = '';
		$provider_id = ''; 
		if(isset($_SESSION['apt_booking_filtersd'],$_SESSION['apt_booking_filtered']) && $_SESSION['apt_booking_filtersd']!='' && $_SESSION['apt_booking_filtered']!=''){
			
			$start_date = $_SESSION['apt_booking_filtersd'];
			$end_date = $_SESSION['apt_booking_filtered'];
		}
		if(isset($_SESSION['apt_booking_filterstaff']) && $_SESSION['apt_booking_filterstaff']!=''){
			$provider_id = $_SESSION['apt_booking_filterstaff'];
		}
		if(isset($_SESSION['apt_booking_filterservice']) && $_SESSION['apt_booking_filterservice']!=''){
			$service_id = $_SESSION['apt_booking_filterservice'];
		}
		
		if(($start_date!='' && $end_date!='') || $service_id!='' || $provider_id!=''){
		$all_upcoming_appointments = $apt_bookings->readAll($start_date,$end_date,$service_id,$provider_id);	
		}else{
		$all_upcoming_appointments = $apt_bookings->read_all_upcoming_bookings();		
		
		}
		$appointment_array_for_cal = array();
		foreach( $all_upcoming_appointments as $app) {
		$appointment_id  = $app->id;
		$booking_status =$app->booking_status;
		$service_start_time =  date_i18n('Y-m-d H:i:s',strtotime($app->booking_datetime));	
		$service_end_time = date_i18n('Y-m-d H:i:s',strtotime($app->booking_endtime)); 
		
		
		
		
		$apt_bookings->booking_id = $appointment_id;		
		$apt_bookings->readOne_by_booking_id();  
		
		$order_info->order_id = $apt_bookings->order_id;	
		$order_info->readOne_by_order_id(); 		
		$customer_phone = $order_info->client_phone;
		$customer_name = ucfirst($order_info->client_name);
		$customer_name = iconv('UTF-8','UTF-8',$customer_name);
		$customer_name = $customer_name;
		
		
		$service->id = $app->service_id;
		$service->readOne();		
		$serviceTitle = stripslashes_deep($service->service_title);
		$serviceTitle = iconv('UTF-8','UTF-8',$serviceTitle);
		$serviceTitle = ucfirst($serviceTitle);
		$color_tag = $service->color_tag;
	
		
		$provider->id=$apt_bookings->provider_id;
		$staff_info = $provider->readOne();		
		$provider_name = (isset($staff_info[0]['staff_name']))?ucfirst($staff_info[0]['staff_name']):'';
		$provider_name = iconv('UTF-8','UTF-8',$provider_name);
		$provider_name = $provider_name;
		$provider_email = (isset($staff_info[0]['email']))?$staff_info[0]['email']:'';		
		$provider_phone = (isset($staff_info[0]['phone']))?$staff_info[0]['phone']:'';	
		$appointment_array_for_cal[]= array(
						"id"=>"$appointment_id",
						"color_tag"=>"$color_tag",
						"title"=>"$serviceTitle",
						"start"=>"$service_start_time",
						"end"=>"$service_end_time",
						"event_status"=>"$booking_status",
						
						"provider"=>"$provider_name",
						"provider_email"=>"$provider_email",
						"provider_phone"=>"$provider_phone",
						"client_name"=>"$customer_name",
						"client_phone"=>"$customer_phone"					
						);
   }   
   
$json_encoded_string_for_cal  =  json_encode($appointment_array_for_cal);
//$json_encoded_string_for_cal = json_encode(array('message' => 'success'));
echo $json_encoded_string_for_cal;
die();
}

/** Get Single Booking Detail **/
if(isset($_POST['general_ajax_action'],$_POST['appointment_id']) && $_POST['general_ajax_action']=='get_appointment_detail' && $_POST['appointment_id']!=''){
	
	$apt_bookings->booking_id=$_POST['appointment_id']; 
	$apt_bookings->readOne_by_booking_id();
   
    /*Get Service Name*/
	$service->id= $apt_bookings->service_id;
	$service->service_id = $service->id;
	$bwid = $service->get_businessowner_by_service_id();
	$service->readone();
	$service_title=stripslashes_deep($service->service_title);
	$service_id=$apt_bookings->service_id;
	$service_color=$service->color_tag;
	$service_price=$service->amount;	
	$service_duration=$service->duration;
	$servicedurationstrinng = '';
	if(floor($service->duration/60)!=0){ $servicedurationstrinng .= floor($service->duration/60); $servicedurationstrinng .= __(" Hrs","apt"); } 
	if($service->duration%60 !=0){  $servicedurationstrinng .= $service->duration%60; $servicedurationstrinng .= __(" Mins","apt"); }

    /*Get Client Name*/
	$clients->order_id=$apt_bookings->order_id;
	$client_info = $clients->get_client_info_by_order_id();
	$clientname= $client_info[0]->client_name;
	$clientemail=$client_info[0]->client_email;
	$clientphone=$client_info[0]->client_phone;
	$client_personal_info=unserialize($client_info[0]->client_personal_info);
	
	$client_notes = '';
	if(isset($client_personal_info['notes'])){
		$client_notes = $client_personal_info['notes'];
	}
	
	$client_address = '';
	if(isset($client_personal_info['address'])){
		$client_address = $client_personal_info['address'];
	}
	$client_city = '';
	if(isset($client_personal_info['city'])){
		$client_city=$client_personal_info['city'];
	}
	$client_state = '';
	if(isset($client_personal_info['state'])){
		$client_state=$client_personal_info['state'];
	}
	$client_zip = '';
	if(isset($client_personal_info['zip'])){
		$client_zip=$client_personal_info['zip'];
	}
	
	$client_country = '';
	if(isset($client_personal_info['country'])){
		$client_country=$client_personal_info['country'];
	}
	
	$client_ccode = '';
	if(isset($client_personal_info['ccode'])){
		$client_ccode=$client_personal_info['ccode'];
	}
	
	
	
	
    /*Get Provider Name*/
	$staff->id=$apt_bookings->provider_id;
	$staff_info = $staff->readOne();   
	$provider_name = ucfirst($staff_info[0]['staff_name']);   
	$provider_id = $apt_bookings->provider_id;   
    /*Get Payment Method*/   
	$payments->order_id = $apt_bookings->order_id;
	$payments->read_one_by_order_id();	
	$payments->payment_method;
	if($payments->payment_method == 'paypal') { $pay_type = __('Paypal','apt'); }
	elseif($payments->payment_method == 'pay_locally') { $pay_type = __('Pay Locally','apt'); }
	elseif($payments->payment_method == 'Free') {  $pay_type = __('Free','apt');}
	elseif($payments->payment_method == 'stripe'){ $pay_type = __('Stripe','apt'); }
	elseif($payments->payment_method =='authorizenet'){$pay_type = __('Authorize .Net','apt');}
	else{$pay_type = '-';} 
	
	if($apt_bookings->booking_status=='A' || $apt_bookings->booking_status==''){
		$bookingstatus =  __('Active','apt');
	}elseif($apt_bookings->booking_status=='C'){
		$bookingstatus = __("Confirm",'apt');
	}elseif($apt_bookings->booking_status=='R'){
		$bookingstatus = __("Reject",'apt');
	}elseif($apt_bookings->booking_status=='RS'){
		$bookingstatus = __("Rescheduled",'apt');
	}elseif($apt_bookings->booking_status=='CC'){
		$bookingstatus =  __("Cancel By Client",'apt');
	}elseif($apt_bookings->booking_status=='CS'){
		$bookingstatus = __("Cancel By Service Provider",'apt');
	}elseif($apt_bookings->booking_status=='CO'){
		$bookingstatus =  __("Completed",'apt');
	}else{
		$apt_bookings->booking_status=='MN';
		$bookingstatus =  __("Mark As No Show",'apt');
   }
	
       
       $appointment_detail = array();     
       $appointment_detail['id']= $apt_bookings->booking_id;
       $appointment_detail['service_title']= $service_title;
       $appointment_details['booking_price']=$apt_bookings->booking_price;   
       $appointment_detail['appointment_startdate']= date_i18n(get_option('appointment_datepicker_format' . '_' . $bwid),strtotime($apt_bookings->booking_datetime));
       $appointment_detail['appointment_starttime']= date_i18n(get_option('time_format'),strtotime($apt_bookings->booking_datetime));
	   $appointment_detail['appointment_endate']= date_i18n(get_option('appointment_datepicker_format' . '_' . $bwid),strtotime($apt_bookings->booking_endtime));
       $appointment_detail['appointment_endtime']= date_i18n(get_option('time_format'),strtotime($apt_bookings->booking_endtime));
	   
	   $appointment_detail['booking_date']=date_i18n('m/d/Y',strtotime($apt_bookings->booking_datetime));
	   $appointment_detail['sel_date']=date_i18n('Y-m-d',strtotime($apt_bookings->booking_datetime));
	   $appointment_detail['booking_status']=$bookingstatus;
       $appointment_detail['provider_id']= $provider_id;
       $appointment_detail['provider_name']= $provider_name;
		
       $appointment_detail['service_id']=$service_id;
       $appointment_detail['service_price']=$service_price;
	   $appointment_detail['service_duration']=$service_duration;
	   $appointment_detail['service_duration_string']=$servicedurationstrinng;
       $appointment_detail['reject_reason']= $apt_bookings->reject_reason;
       $appointment_detail['cancel_reason']= $apt_bookings->cancel_reason;
       $appointment_detail['confirm_note']= $apt_bookings->confirm_note;
       $appointment_detail['reschedule_note']= $apt_bookings->reschedule_note;
       $appointment_detail['payment_type']=$pay_type;
	   $appointment_detail['client_name']=$clientname;
       $appointment_detail['client_phone']= $clientphone;
       $appointment_detail['client_email']= $clientemail;
	   $appointment_detail['client_address']=$client_address;
	   $appointment_detail['client_notes']=$client_notes;
	   $appointment_detail['client_city']=$client_city;
	   $appointment_detail['client_zip']=$client_zip;
	   $appointment_detail['client_country']=$client_country;
	   $appointment_detail['client_ccode']=$client_ccode;
	   $appointment_detail['client_state']=$client_state;
	   $appointment_detail['bwid']=$bwid;

       echo json_encode($appointment_detail);die();
}
/** Get Services By Staff -- Rescheduled/Manual Booking **/
if(isset($_POST['general_ajax_action'],$_POST['staff_id']) && $_POST['general_ajax_action']=='get_services_by_staff' && $_POST['staff_id']!=''){
	$service->provider_id = $_POST['staff_id'];
	$apt_providerservices = $service->readall_services_of_provider();
	$prevcateid = '';
	$temp_cate = array();
	foreach($apt_providerservices as $providerservice){
		if($prevcateid != $providerservice->category_id && $prevcateid !=''){	
			$prevcateid =$providerservice->category_id;
			echo  '</optgroup> ';							
		}
		if(!in_array($providerservice->category_id,$temp_cate)){
			$temp_cate[]= $providerservice->category_id;		
			echo '<optgroup label="'.$providerservice->category_title.'">';
		}			
		echo '<option value="'.$providerservice->service_id.'">'.$providerservice->service_title.'</option>';						
	}
}
/* Get Service Info By Service id */
if(isset($_POST['general_ajax_action'],$_POST['service_id']) && $_POST['general_ajax_action']=='get_services_info' && $_POST['service_id']!=''){
	$service->id= $_POST['service_id'];
	$service->readone();
	$servicedurationstrinng = '';
	if(floor($service->duration/60)!=0){ $servicedurationstrinng .= floor($service->duration/60); $servicedurationstrinng .= __(" Hrs","apt"); } 
	if($service->duration%60 !=0){  $servicedurationstrinng .= $service->duration%60; $servicedurationstrinng .= __(" Mins","apt"); }
	
	$service_info = array();     
    $service_info['service_price']= $service->amount;
    $service_info['service_duration']= $servicedurationstrinng;
    $service_info['service_duration_val']= $service->duration;
	echo json_encode($service_info);die();
}
/* Reschedule Appointment */
if(isset($_POST['general_ajax_action'],$_POST['booking_id']) && $_POST['general_ajax_action']=='reschedule_appointment' && $_POST['booking_id']!=''){
	$apt_bookings->booking_id = $_POST['booking_id']; 
    $apt_bookings->readOne_by_booking_id();
    $bwid = $_POST['bwid'];
	$bookingold_startdt = date_i18n(get_option('appointment_datepicker_format' . '_'. $bwid),strtotime($apt_bookings->booking_datetime));
    $bookingold_enddt = date_i18n(get_option('time_format'),strtotime($apt_bookings->booking_datetime));
	$gcevent_id = $apt_bookings->gc_event_id;
	/***********************Calendar code start****************************/
	
	if(isset($gcevent_id) && $gcevent_id!='') {
		$provider_gc_id = get_option('apt_gc_id' . '_' . $bwid);
		$provider_gc_data = get_option('apt_gc_token' . '_' . $bwid);
		$GcclientID = get_option('apt_gc_client_id' . '_' . $bwid);
		$GcclientSecret = get_option('apt_gc_client_secret' . '_' . $bwid);
		$GcEDvalue = get_option('apt_gc_status' . '_' . $bwid);
		if($provider_gc_id!='' && $provider_gc_data!=''){
			if(get_option('timezone_string') != ''){
				$providerTZ = get_option('timezone_string');
			}else{
				$gmt_offset = get_option('gmt_offset');
				$hr_minute = explode('.', $gmt_offset);
				if (isset($hr_minute[1])) {
					if ($hr_mint[1] == '5') {
						$gmt_offset = $hr_mint[0].'.30';
					}else{
						$gmt_offset = $hr_mint[0].'.45';
					}
				}else{
					$gmt_offset = $hr_mint[0];
				}
				$seconds = $gmt_offset * 60 * 60;
				$get_tz = timezone_name_from_abbr('', $seconds, 1);
				if($get_tz === false){ $get_tz = timezone_name_from_abbr('', $seconds, 0); }
				$providerTZ = $get_tz;
			}
			
			$date =date_i18n('Y-m-d',strtotime($_POST['booking_date']));
			$start = date_i18n('H:i:s',strtotime($_POST['booking_time']));
			
			$event_endtime = date_i18n('H:i:s',strtotime("+".$_POST['service_duration']." minutes", strtotime(date_i18n('Y-m-d',strtotime($_POST['booking_date'])).' '.date_i18n('H:i:s',strtotime($_POST['booking_time'])))));
			$end = $event_endtime;

			require_once dirname(dirname(dirname(__FILE__)))."/assets/GoogleCalendar/google-api-php-client/src/Google_Client.php";
			require_once dirname(dirname(dirname(__FILE__)))."/assets/GoogleCalendar/google-api-php-client/src/contrib/Google_CalendarService.php";

			$provider_events  = update_google_cal_event($provider_gc_id,$provider_gc_data,$gcevent_id,$date,$start,$end,$providerTZ,$GcclientID,$GcclientSecret,$GcEDvalue,$bwid);
		}
	}
	/***********************Calendar code end******************************/
	
	
	$apt_bookings->booking_datetime = date_i18n('Y-m-d',strtotime($_POST['booking_date'])).' '.date_i18n('H:i:s',strtotime($_POST['booking_time']));
	$apt_bookings->booking_endtime = date_i18n('Y-m-d H:i:s',strtotime("+".$_POST['service_duration']." minutes", strtotime(date_i18n('Y-m-d',strtotime($_POST['booking_date'])).' '.date_i18n('H:i:s',strtotime($_POST['booking_time'])))));	
	$apt_bookings->reschedule_note = $_POST['reschedule_note'];
	$apt_bookings->booking_status = 'RS';
	$apt_bookings->reschedule_appointment();

	
	/******************* Send MAIL and SMS code START *********************/
	
	$C_ES_template_statuss = 'RSC';
	$S_ES_template_statuss = 'RSS';
	$A_ES_template_statuss = 'RSA';
	
	$apt_booking->booking_id=$_POST['booking_id'];        
	$client_bookings= $apt_booking->get_all_bookings_by_b_id();		
	$sender_name = get_option('appointment_email_sender_name' . '_'. $bwid);		
	$sender_email_address = get_option('appointment_email_sender_address' . '_'. $bwid);		
	$headers = "From: $sender_name <$sender_email_address>" . "\r\n";
	
	$company_name = get_option('appointment_company_name' . '_'. $bwid);
	$company_address = get_option('appointment_company_address' . '_'. $bwid);
	$company_city = get_option('appointment_company_city' . '_'. $bwid);
	$company_state = get_option('appointment_company_state' . '_'. $bwid);
	$company_zip = get_option('appointment_company_zip' . '_'. $bwid);
	$company_country = get_option('appointment_company_country' . '_'. $bwid);
	$company_phone = get_option('appointment_company_country_code' . '_'. $bwid).get_option('appointment_company_phone' . '_'. $bwid);
	$company_email = get_option('appointment_company_email' . '_'. $bwid);
	$company_logo = $business_logo = site_url()."/wp-content/uploads/".get_option('appointment_company_logo' . '_'. $bwid);
	
	/* main loop for content and mail start here */           
	$booking_counter = 1; 
	$booking_counter_txt = '';
	$booking_details = '';
	$booking_details_sms = '';
	
	foreach($client_bookings as $single_booking){
		$client_detailss = $apt_staff->C_readOne($single_booking->client_id);
		$client_name = $client_detailss['name'];
		$preff_username = $client_detailss['email'];
		$user_phone = $client_detailss['phone'];
		$next_order_id = $single_booking->order_id;
		$apt_service->id = $single_booking->service_id;
		$apt_staff->id = $single_booking->provider_id;                    
		$apt_service->readOne();                    
		$staffinfo = $apt_staff->readOne();   
		$location_details = '';
		if($single_booking->location_id!=0 || $single_booking->location_id!=''){
			$apt_location->id = $single_booking->location_id;
			$locationinfo = $apt_location->readOne();
			if(sizeof($locationinfo)>0){
				$location_details .= "<br/><span><strong>".__('Location','apt')."</strong>: ".stripslashes_deep($locationinfo[0]->location_title)."</span><br/><br/><span><strong>".__('Location Address','apt')."</strong>: ".stripslashes_deep($locationinfo[0]->address)."</span><br/><br/>";
			}
		}
		
		$addons_detail = '';
		$addon_titles = '';
		$addon_prices = '';
		$addon_qty = '';
		$apt_booking->order_id =  $single_booking->order_id;
		$serviceaddons_info = $apt_booking->select_addonsby_orderidand_serviceid();	
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
		
		$datetime = explode(' ',$single_booking->booking_datetime);        
		$booking_id=base64_encode($next_order_id);        
		$encoded_cinfo_sp=base64_encode($booking_id."-confirm");        
		$appoint_confirm_link_sp = plugins_url('',dirname(__FILE__))."/lib/booking_crc_email.php?".$encoded_cinfo_sp;                        
		$encoded_rinfo_sp=base64_encode($booking_id."-reject");        
		$appoint_reject_link_sp =plugins_url('',dirname(__FILE__))."/lib/booking_crc_email.php?".$encoded_rinfo_sp; 

		/*Client Cancel Link */
		$encoded_cinfo_client=base64_encode($booking_id."-clientcancel");        

		$appoint_cancel_link_client =plugins_url('',dirname(__FILE__))."/lib/booking_crc_email.php?".$encoded_cinfo_client;
		
		if(isset($_SESSION['booking_type']) || get_option('appointment_auto_confirm_appointment'.'_'.$bwid)=='Y' ){
		$confirm_link_sp='';
		}else{
		$confirm_link_sp="<a style='text-decoration: none;color: #FFF;background-color: #348eda;	border: solid #348eda;border-width: 10px 30px; line-height: 1;	font-weight: bold;margin-right: 10px;text-align: center;cursor: pointer;display: inline-block; border-radius: 10px;'  id='email-btn-primary' class='email-btn-primary' href='".$appoint_confirm_link_sp."-".base64_encode($single_booking->provider_id."+".$single_booking->id)."' >".__('Confirm','apt')."</a>";     
		}		
		$reject_link_sp ="<a style='text-decoration: none;color: #FFF;background-color: red;border: solid red;border-width: 10px 30px;line-height: 1;font-weight: bold;margin-right: 10px;text-align: center;cursor: pointer;display: inline-block;border-radius: 10px;'  id='email-btn-secondary' class='email-btn-secondary' href='".$appoint_reject_link_sp."-".base64_encode($single_booking->provider_id."+".$single_booking->id)."' >".__('Reject','apt')."</a>";

		$cancel_link_client ="<a style='text-decoration: none;color: #FFF;background-color: red;border: solid red;border-width: 10px 30px;line-height: 1;font-weight: bold;margin-right: 10px;text-align: center;cursor: pointer;display: inline-block;border-radius: 10px;'  id='email-btn-secondary' class='email-btn-secondary' href='".$appoint_cancel_link_client."-".base64_encode($single_booking->client_id."+".$single_booking->id)."' >".__('Cancel','apt')."</a>";
		
		$booking_details .= $location_details."<br/><span><strong>".__('For','apt')."</strong>: ".stripslashes_deep($apt_service->service_title)."</span><br/><br/>
								<span><strong>".__('With','apt')."</strong>: ".ucwords(stripslashes_deep($staffinfo[0]['staff_name']))."</span><br/><br/>
								<span><strong>".__('On','apt')."</strong>: ".date_i18n(get_option('date_format'),strtotime($datetime[0]))."</span><br/><br/>
								<span><strong>".__('At','apt')."</strong>: ".date_i18n(get_option('time_format'),strtotime($datetime[1]))."</span><br/><br/>
								<span>".$cancel_link_client."</span><br/>".$addons_detail;		

		$booking_details_sms .= ' With :'.ucwords(stripslashes_deep($staffinfo[0]['staff_name'])).' On : '.date_i18n(get_option('date_format'),strtotime($datetime[0])).' At : '.date_i18n(get_option('time_format'),strtotime($datetime[1])).' For: '.$apt_service->service_title.' ';
		
		if(sizeof($client_bookings) > 1) {
			$booking_counter_txt = "#".$booking_counter."<br/>";
		}
		
		$arr_providers_booking_details[$staffinfo[0]['id']][] = $location_details."<br/>".$booking_counter_txt."
		<span><strong>".__('For','apt')."</strong> :".stripslashes_deep($apt_service->service_title)."</span><br/><br/>
								<span><strong>".__('With','apt')."</strong> :".ucwords(stripslashes_deep($staffinfo[0]['staff_name']))."</span><br/><br/>
								<span><strong>".__('On','apt')."</strong> :".date_i18n(get_option('date_format'),strtotime($datetime[0]))."</span><br/><br/>
								<span><strong>".__('At','apt')."</strong> :".date_i18n(get_option('time_format'),strtotime($datetime[1]))."</span><br/><br/>".$addons_detail."<span>".$confirm_link_sp."</span>	<span>".$reject_link_sp."</span><br/>";
		
		
		$booking_counter++;
	}
	/* Mail content loop end here */

	/* Send Email To Client */
	if(get_option('appointment_client_email_notification_status'.'_'.$bwid)=='E'){	
		$apt_clientemail_templates = new appointment_email_template();
		$msg_template = $apt_clientemail_templates->email_parent_template;	
		$apt_clientemail_templates->email_template_name = $C_ES_template_statuss;
		
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
			$search = array('{{customer_name}}','{{booking_details}}','{{company_name}}','{{service_provider_name}}','{{admin_manager_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');      
			$replace_with = array($client_name,$booking_details,$company_name,'','',$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);
			
			$email_client_message = str_replace($search,$replace_with,$email_content);	
			$email_client_message = str_replace('###msg_content###',$email_client_message,$msg_template);		
			add_filter( 'wp_mail_content_type', 'set_content_type' );
			$status = wp_mail($preff_username,$email_subject,$email_client_message,$headers);           
		}       
	}
	/* Send email to admin/service provider when booking is complete */
	$client_full_detail='<br/>';
	$sms_client_full_detail=' ';
	if(ucwords($client_name)!=''){ 
		$client_full_detail .= "<span><strong>".__('Client Name','apt')."</strong>: ".ucwords($client_name)."</span><br/><br/>";
		$sms_client_full_detail .= __('Client Name','apt').": ".ucwords($client_name)." ";
	}if($preff_username!=''){ 
		$client_full_detail .= "<span><strong>".__('Client Email','apt')."</strong>: ".$preff_username."</span><br/><br/>";
		$sms_client_full_detail .= __('Client Email','apt').": ".$preff_username." ";
	}	
	if($user_gender!=''){
		if($user_gender == "M"){
			$gender_display = "Male";
		}else{
			$gender_display = "Female";
		}
		$client_full_detail .="<span><strong>".__('Gender','apt')."</strong>: ".$gender_display."</span><br/><br/>";
		$sms_client_full_detail .= __('Gender','apt').": ".$gender_display." ";
	}
	
	if($user_phone!=''){
		$client_full_detail .="<span><strong>".__('Client Phone','apt')."</strong>: ".$user_phone."</span><br/><br/>";
		$sms_client_full_detail .= __('Client Phone','apt').": ".$user_phone." ";
	}
	if($user_address!=''){
		$client_full_detail .="<span><strong>".__('Address','apt')."</strong>: ".$user_address."</span><br/><br/>";
		$sms_client_full_detail .= __('Address','apt').": ".$user_address." ";
	}
	if($user_city!=''){
		$client_full_detail .="<span><strong>".__('City','apt')."</strong>: ".$user_city."</span><br/><br/>";
		$sms_client_full_detail .= __('City','apt').": ".$user_city." ";
	}
	if($user_state!=''){
		$client_full_detail .="<span><strong>".__('State','apt')."</strong>: ".$user_state."</span><br/><br/>";
		$sms_client_full_detail .= __('State','apt').": ".$user_state." ";
	}
	if($user_notes!=''){
		$client_full_detail .="<span><strong>".__('Notes','apt')."</strong>: ".$user_notes."</span><br/><br/>";
		$sms_client_full_detail .= __('Notes','apt').": ".$user_notes." ";
	}
	$client_detail= $client_full_detail;
	$sms_client_detail= $sms_client_full_detail;
	
	/* Send Email To Staff */
	if(get_option('appointment_service_provider_email_notification_status'.'_'.$bwid)=='E'){	
		$apt_staffemail_templates = new appointment_email_template();	
		$msg_template = $apt_staffemail_templates->email_parent_template;
		$apt_staffemail_templates->email_template_name = $S_ES_template_statuss;
		$template_detail = $apt_staffemail_templates->readOne();        
		if($template_detail[0]->email_message!=''){            
			$email_content = $template_detail[0]->email_message;        
		}else{            
			$email_content = $template_detail[0]->default_message;
		}        
		$email_subject = $template_detail[0]->email_subject;   
		$email_staff_message = '';
		
		if($template_detail[0]->email_template_status=='e'){
			foreach($arr_providers_booking_details as $provider_id => $bookingstrarr){
				$apt_staff->id = $provider_id;
				$staffinfo = $apt_staff->readOne();
				
				$strtoprint = "";
				foreach ($bookingstrarr as $bookingsss) {
					$strtoprint .= $bookingsss;
				}
				$sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);
				$search = array('{{admin_manager_name}}','{{service_provider_name}}','{{booking_details}}','{{appoinment_client_detail}}','{{company_name}}','{{customer_name}}','{{admin_manager_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');
				
				$replace_with = array($sender_name,$staffinfo[0]['staff_name'],$client_detail,$company_name,'','',$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);

				$email_staff_message = str_replace($search,$replace_with,$email_content);
				$email_staff_message = str_replace('###msg_content###',$email_staff_message,$msg_template);
				add_filter( 'wp_mail_content_type', 'set_content_type' );
				$status = wp_mail($staffinfo[0]['email'],$email_subject,$email_staff_message,$headers);
			}
		}
	}
	/* Send Email To Admin */	
	$arr_admin_booking_detail='';        
	foreach($arr_providers_booking_details as $provider_id => $bookingstrarr){
			for($i=0;$i<sizeof($bookingstrarr);$i++){                
				$arr_admin_booking_detail .=$bookingstrarr[$i];               
			}
	}	
	$arr_admin_bookingfulldetail=$arr_admin_booking_detail;
	
	if(get_option('appointment_admin_email_notification_status'.'_'.$bwid)=='E'){
		$apt_adminemail_templates = new appointment_email_template();
		$msg_template = $apt_adminemail_templates->email_parent_template;
		$apt_adminemail_templates->email_template_name = $A_ES_template_statuss;
		$template_detail = $apt_adminemail_templates->readOne();        
		if($template_detail[0]->email_message!=''){            
			$email_content = $template_detail[0]->email_message;        
		}else{            
			$email_content = $template_detail[0]->default_message;
		}        
		$email_subject = $template_detail[0]->email_subject;
		$email_admin_message = '';
		$admin_sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);

		$search = array('{{admin_manager_name}}','{{booking_details}}','{{appoinment_client_detail}}','{{company_name}}','{{customer_name}}','{{service_provider_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');
		
		$replace_with = array($admin_sender_name,$company_name,$arr_admin_bookingfulldetail,$client_detail,$company_name,'','',$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);
		$email_admin_message = str_replace($search,$replace_with,$email_content);
		$email_admin_message = str_replace('###msg_content###',$email_admin_message,$msg_template);
		add_filter( 'wp_mail_content_type', 'set_content_type' );		
		$status = wp_mail(get_option('appointment_email_sender_address'.'_'.$bwid),$email_subject,$email_admin_message,$headers); 	
	}
	/******************* Send SMS code START *********************/

	if(get_option("appointment_sms_reminder_status".'_'.$bwid) == "E"){
		/** TWILIO **/
		if(get_option("appointment_sms_noti_twilio".'_'.$bwid) == "E"){
			$obj_sms_template = new appointment_sms_template();
			$twillio_sender_number = get_option('appointment_twilio_number'.'_'.$bwid);
			$AccountSid = get_option('appointment_twilio_sid'.'_'.$bwid);
			$AuthToken =  get_option('appointment_twilio_auth_token'.'_'.$bwid); 
			
			/* Send SMS To Client */
			if(get_option('appointment_twilio_client_sms_notification_status'.'_'.$bwid) == "E"){
				$twilliosms_client = new Client($AccountSid, $AuthToken);
				$template1 = $obj_sms_template->gettemplate_sms("C",'e',$C_ES_template_statuss);
				if($template1[0]->sms_template_status == "e" && $user_phone!=''){
					if($template1[0]->sms_message == ""){
						$message = strip_tags($template1[0]->default_message);
					}else{
						$message = strip_tags($template1[0]->sms_message);
					}
					$sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);
					$search = array('{{customer_name}}','{{booking_details}}','{{booking_detail}}','{{company_name}}','{{service_provider_name}}','{{admin_manager_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');
					$replace_with = array($client_name,$booking_details_sms,$booking_details_sms,$company_name,'',$sender_name,$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);
					$client_sms_body = str_replace($search,$replace_with,$message);
					$twilliosms_client->messages->create(
						$user_phone,
						array(
							'from' => $twillio_sender_number,
							'body' => $client_sms_body 
						)
					);
				}
			}
			/* Send SMS To Staff */
			if(get_option('appointment_twilio_service_provider_sms_notification_status'.'_'.$bwid) == "E"){
				$twilliosms_staff = new Client($AccountSid, $AuthToken);
				foreach($arr_providers_booking_details as $provider_id => $bookingstrarr){
					$apt_staff->id = $provider_id;
					$staffinfo = $apt_staff->readOne();
					$template = $obj_sms_template->gettemplate_sms("SP",'e',$S_ES_template_statuss);
					
					if($template[0]->sms_template_status == "e" && $staffinfo[0]['phone']!=''){
						if($template[0]->sms_message == ""){
							$message = strip_tags($template[0]->default_message);
						}else{
							$message = strip_tags($template[0]->sms_message);
						}
						$sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);
						$search = array('{{admin_manager_name}}','{{service_provider_name}}','{{booking_details}}','{{booking_detail}}','{{appoinment_client_detail}}','{{company_name}}','{{customer_name}}','{{admin_manager_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');
					
						$replace_with = array($sender_name,$staffinfo[0]['staff_name'],$booking_details_sms,$booking_details_sms,$sms_client_detail,$company_name,'','',$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);

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
			}
			/* Send SMS To Admin */
			if(get_option('appointment_twilio_admin_sms_notification_status'.'_'.$bwid) == "E"){		   
				$twilliosms_admin = new Client($AccountSid, $AuthToken);
				$template = $obj_sms_template->gettemplate_sms("AM",'e',$A_ES_template_statuss);					
				if($template[0]->sms_template_status == "e" && get_option('appointment_twilio_admin_phone_no'.'_'.$bwid)!=''){
					if($template[0]->sms_message == ""){
						$message = strip_tags($template[0]->default_message);
					}else{
						$message = strip_tags($template[0]->sms_message);
					}
					$sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);
					$search = array('{{admin_manager_name}}','{{service_provider_name}}','{{booking_details}}','{{booking_detail}}','{{appoinment_client_detail}}','{{company_name}}','{{customer_name}}','{{admin_manager_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');
					
					$replace_with = array($sender_name,$staffinfo[0]['staff_name'],$booking_details_sms,$booking_details_sms,$sms_client_detail,$company_name,'','',$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);
					
					$admin_sms_body = str_replace($search,$replace_with,$message);	

					$twilliosms_staff->messages->create(
						get_option('appointment_twilio_ccode'.'_'.$bwid).get_option('appointment_twilio_admin_phone_no'.'_'.$bwid),
						array(
							'from' => $twillio_sender_number,
							'body' => $admin_sms_body 
						)
					);
				}
			}
		}
				
		/** PLIVO **/
		if(get_option("appointment_sms_noti_plivo".'_'.$bwid) == "E"){
			$obj_sms_template = new appointment_sms_template();
			$plivo_sender_number = get_option('appointment_plivo_number'.'_'.$bwid);	
			$auth_sid = get_option('appointment_plivo_sid'.'_'.$bwid);
			$auth_token = get_option('appointment_plivo_auth_token'.'_'.$bwid);
			
			/* Send SMS To Client */
			if(get_option('appointment_plivo_client_sms_notification_status'.'_'.$bwid) == "E"){
				$p_client = new Plivo\RestAPI($auth_sid, $auth_token, '', '');
				$template1 = $obj_sms_template->gettemplate_sms("C",'e',$C_ES_template_statuss);
				if($template1[0]->sms_template_status == "e" && $user_phone!=''){
					if($template1[0]->sms_message == ""){
						$message = strip_tags($template1[0]->default_message);
					}else{
						$message = strip_tags($template1[0]->sms_message);
					}
					$sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);
					$search = array('{{customer_name}}','{{booking_details}}','{{booking_detail}}','{{company_name}}','{{service_provider_name}}','{{admin_manager_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');
					$replace_with = array($client_name,$booking_details_sms,$booking_details_sms,$company_name,'',$sender_name,$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);
					$client_sms_body = str_replace($search,$replace_with,$message);
					
					$clientparams = array(
						'src' => $plivo_sender_number,
						'dst' => $user_phone,
						'text' => $client_sms_body,
						'method' => 'POST'
					);
					$response = $p_client->send_message($clientparams);
				}
			}
			/* Send SMS To Staff */
			if(get_option('appointment_plivo_service_provider_sms_notification_status'.'_'.$bwid) == "E"){
				$p_staff = new Plivo\RestAPI($auth_id, $auth_token, '', '');
				foreach($arr_providers_booking_details as $provider_id => $bookingstrarr){
					$apt_staff->id = $provider_id;
					$staffinfo = $apt_staff->readOne();
					$template = $obj_sms_template->gettemplate_sms("SP",'e',$S_ES_template_statuss);
					
					if($template[0]->sms_template_status == "e" && $staffinfo[0]['phone']!=''){
						if($template[0]->sms_message == ""){
							$message = strip_tags($template[0]->default_message);
						}else{
							$message = strip_tags($template[0]->sms_message);
						}
						$sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);
						$search = array('{{admin_manager_name}}','{{service_provider_name}}','{{booking_details}}','{{booking_detail}}','{{appoinment_client_detail}}','{{company_name}}','{{customer_name}}','{{admin_manager_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');
					
						$replace_with = array($sender_name,$staffinfo[0]['staff_name'],$booking_details_sms,$booking_details_sms,$sms_client_detail,$company_name,'','',$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);

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
			}
			/* Send SMS To Admin */
			if(get_option('appointment_plivo_admin_sms_notification_status'.'_'.$bwid) == "E"){		   
				$twilliosms_admin = new Client($AccountSid, $AuthToken);
				$template = $obj_sms_template->gettemplate_sms("AM",'e',$A_ES_template_statuss);					
				if($template[0]->sms_template_status == "e" && get_option('appointment_plivo_admin_phone_no'.'_'.$bwid)!=''){
					if($template[0]->sms_message == ""){
						$message = strip_tags($template[0]->default_message);
					}else{
						$message = strip_tags($template[0]->sms_message);
					}
					$sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);
					$search = array('{{admin_manager_name}}','{{service_provider_name}}','{{booking_details}}','{{booking_detail}}','{{appoinment_client_detail}}','{{company_name}}','{{customer_name}}','{{admin_manager_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');
					
					$replace_with = array($sender_name,$staffinfo[0]['staff_name'],$booking_details_sms,$booking_details_sms,$sms_client_detail,$company_name,'','',$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);
					
					$admin_sms_body = str_replace($search,$replace_with,$message);	

					$adminparams = array(
						'src' => $plivo_sender_number,
						'dst' => get_option('appointment_plivo_ccode'.'_'.$bwid).get_option('appointment_plivo_admin_phone_no'.'_'.$bwid),
						'text' => $admin_sms_body,
						'method' => 'POST'
						);
					$response = $p_admin->send_message($adminparams);
				}
			}
		}
		
		/** NEXMO **/
		if(get_option("appointment_sms_noti_nexmo".'_'.$bwid) == "E"){
			$obj_sms_template = new appointment_sms_template();
			include_once(dirname(dirname(dirname(__FILE__))).'/objects/class_nexmo.php');
			$nexmo_client = new appointment_nexmo();
			$nexmo_client->appointment_nexmo_apikey = get_option('appointment_nexmo_apikey'.'_'.$bwid);
			$nexmo_client->appointment_nexmo_api_secret = get_option('appointment_nexmo_api_secret'.'_'.$bwid);
			$nexmo_client->appointment_nexmo_form = get_option('appointment_nexmo_form'.'_'.$bwid);
			
			/* Send SMS To Client */
			if(get_option('appointment_nexmo_send_sms_client_status'.'_'.$bwid) == "E"){
				$template1 = $obj_sms_template->gettemplate_sms("C",'e',$C_ES_template_statuss);
				if($template1[0]->sms_template_status == "e" && $user_phone!=''){
					if($template1[0]->sms_message == ""){
						$message = strip_tags($template1[0]->default_message);
					}else{
						$message = strip_tags($template1[0]->sms_message);
					}
					$sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);
					$search = array('{{customer_name}}','{{booking_details}}','{{booking_detail}}','{{company_name}}','{{service_provider_name}}','{{admin_manager_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');
					$replace_with = array($client_name,$booking_details_sms,$booking_details_sms,$company_name,'',$sender_name,$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);
					$client_sms_body = str_replace($search,$replace_with,$message);
					$res = $nexmo_client->send_nexmo_sms($user_phone,$client_sms_body);
				}
			}
			/* Send SMS To Staff */
			if(get_option('appointment_nexmo_send_sms_sp_status'.'_'.$bwid) == "E"){
				foreach($arr_providers_booking_details as $provider_id => $bookingstrarr){
					$apt_staff->id = $provider_id;
					$staffinfo = $apt_staff->readOne();
					$template = $obj_sms_template->gettemplate_sms("SP",'e',$S_ES_template_statuss);
					
					if($template[0]->sms_template_status == "e" && $staffinfo[0]['phone']!=''){
						if($template[0]->sms_message == ""){
							$message = strip_tags($template[0]->default_message);
						}else{
							$message = strip_tags($template[0]->sms_message);
						}
						$sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);
						$search = array('{{admin_manager_name}}','{{service_provider_name}}','{{booking_details}}','{{booking_detail}}','{{appoinment_client_detail}}','{{company_name}}','{{customer_name}}','{{admin_manager_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');
					
						$replace_with = array($sender_name,$staffinfo[0]['staff_name'],$booking_details_sms,$booking_details_sms,$sms_client_detail,$company_name,'','',$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);

						$staff_sms_body = str_replace($search,$replace_with,$message);
						$nexmo_client->send_nexmo_sms($staffinfo[0]['phone'],$staff_sms_body);
					}
				}
			}
			/* Send SMS To Admin */
			if(get_option('appointment_nexmo_send_sms_admin_status'.'_'.$bwid) == "E"){
				$template = $obj_sms_template->gettemplate_sms("AM",'e',$A_ES_template_statuss);					
				if($template[0]->sms_template_status == "e" && get_option('appointment_nexmo_admin_phone_no'.'_'.$bwid)!=''){
					if($template[0]->sms_message == ""){
						$message = strip_tags($template[0]->default_message);
					}else{
						$message = strip_tags($template[0]->sms_message);
					}
					$sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);
					$search = array('{{admin_manager_name}}','{{service_provider_name}}','{{booking_details}}','{{booking_detail}}','{{appoinment_client_detail}}','{{company_name}}','{{customer_name}}','{{admin_manager_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');
					
					$replace_with = array($sender_name,$staffinfo[0]['staff_name'],$booking_details_sms,$booking_details_sms,$sms_client_detail,$company_name,'','',$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);
					
					$admin_sms_body = str_replace($search,$replace_with,$message);
					$nexmo_client->send_nexmo_sms(get_option('appointment_nexmo_ccode'.'_'.$bwid).get_option('appointment_nexmo_admin_phone_no'.'_'.$bwid),$admin_sms_body);
				}
			}
		}
		
		/** TEXTLOCAL **/
		if(get_option("appointment_sms_noti_textlocal".'_'.$bwid) == "E"){
			$obj_sms_template = new appointment_sms_template();
			$textlocal_apikey = get_option('appointment_textlocal_apikey'.'_'.$bwid);
			$textlocal_sender = get_option('appointment_textlocal_sender'.'_'.$bwid);
			
			/* Send SMS To Client */
			if(get_option('appointment_textlocal_client_sms_notification_status'.'_'.$bwid) == "E"){
				$template1 = $obj_sms_template->gettemplate_sms("C",'e',$C_ES_template_statuss);
				if($template1[0]->sms_template_status == "e" && $user_phone!=''){
					if($template1[0]->sms_message == ""){
						$message = strip_tags($template1[0]->default_message);
					}else{
						$message = strip_tags($template1[0]->sms_message);
					}
					$sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);
					$search = array('{{customer_name}}','{{booking_details}}','{{booking_detail}}','{{company_name}}','{{service_provider_name}}','{{admin_manager_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');
					$replace_with = array($client_name,$booking_details_sms,$booking_details_sms,$company_name,'',$sender_name,$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);
					$client_sms_body = str_replace($search,$replace_with,$message);
					
					$textlocal_numbers = $user_phone;
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
			if(get_option('appointment_textlocal_service_provider_sms_notification_status'.'_'.$bwid) == "E"){
				foreach($arr_providers_booking_details as $provider_id => $bookingstrarr){
					$apt_staff->id = $provider_id;
					$staffinfo = $apt_staff->readOne();
					$template = $obj_sms_template->gettemplate_sms("SP",'e',$S_ES_template_statuss);
					
					if($template[0]->sms_template_status == "e" && $staffinfo[0]['phone']!=''){
						if($template[0]->sms_message == ""){
							$message = strip_tags($template[0]->default_message);
						}else{
							$message = strip_tags($template[0]->sms_message);
						}
						$sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);
						$search = array('{{admin_manager_name}}','{{service_provider_name}}','{{booking_details}}','{{booking_detail}}','{{appoinment_client_detail}}','{{company_name}}','{{customer_name}}','{{admin_manager_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');
					
						$replace_with = array($sender_name,$staffinfo[0]['staff_name'],$booking_details_sms,$booking_details_sms,$sms_client_detail,$company_name,'','',$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);

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
			}
			/* Send SMS To Admin */
			if(get_option('appointment_textlocal_admin_sms_notification_status'.'_'.$bwid) == "E"){
				$template = $obj_sms_template->gettemplate_sms("AM",'e',$A_ES_template_statuss);					
				if($template[0]->sms_template_status == "e" && get_option('appointment_textlocal_admin_phone_no'.'_'.$bwid)!=''){
					if($template[0]->sms_message == ""){
						$message = strip_tags($template[0]->default_message);
					}else{
						$message = strip_tags($template[0]->sms_message);
					}
					$sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);
					$search = array('{{admin_manager_name}}','{{service_provider_name}}','{{booking_details}}','{{booking_detail}}','{{appoinment_client_detail}}','{{company_name}}','{{customer_name}}','{{admin_manager_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');
					
					$replace_with = array($sender_name,$staffinfo[0]['staff_name'],$booking_details_sms,$booking_details_sms,$sms_client_detail,$company_name,'','',$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);
					
					$admin_sms_body = str_replace($search,$replace_with,$message);
					
					$textlocal_numbers = get_option('appointment_textlocal_ccode'.'_'.$bwid).get_option('appointment_textlocal_admin_phone_no'.'_'.$bwid);
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
	}
	/******************* Send SMS code END *********************/
	
	/******************* Send MAIL and SMS code END *********************/
	
	
}
/* Get Register Client Information for Manual Booking Popup */
if(isset($_POST['general_ajax_action'],$_POST['client_id']) && $_POST['general_ajax_action']=='get_client_info'){
	$apt_bookings->client_id = $_POST['client_id'];
	$apt_bookings->get_register_client_last_order_id();
	/*Get Client Name*/
	$clients->order_id=$apt_bookings->order_id;
	$client_info = $clients->get_client_info_by_order_id();
	$clientinfo = array();
	$clientinfo['client_name']= $client_info[0]->client_name;
	$clientinfo['client_email']=$client_info[0]->client_email;
	$client_personal_info=unserialize($client_info[0]->client_personal_info);
	print_r($client_personal_info);
	/* $client_personal_info['ccode'] */
	$clientinfo['client_phone']= $client_info[0]->client_phone;
	if(isset($client_personal_info['notes'])){
		$clientinfo['client_notes'] = $client_personal_info['notes'];
	}else{
		$clientinfo['client_notes'] = '';
	}
	
	if(isset($client_personal_info['address'])){
		$clientinfo['client_address'] = $client_personal_info['address'];
	}else{
		$clientinfo['client_address'] = '';
	}
	
	if(isset($client_personal_info['city'])){
		$clientinfo['client_city'] = $client_personal_info['city'];
	}else{
		$clientinfo['client_city'] = '';
	}
	
	if(isset($client_personal_info['state'])){
		$clientinfo['client_state']= $client_personal_info['state'];		
	}else{
		$clientinfo['client_state'] = '';
	}
	
	if(isset($client_personal_info['zip'])){
		$clientinfo['client_zip']=$client_personal_info['zip'];		
	}else{
		$clientinfo['client_zip'] = '';
	}
	
	if(isset($client_personal_info['country'])){
		$clientinfo['client_country']=$client_personal_info['country'];	
	}else{
		$clientinfo['client_country'] = '';
	}
	
	if(isset($client_personal_info['ccode'])){
		$clientinfo['client_ccode']=$client_personal_info['ccode'];	
	}else{
		$clientinfo['client_ccode'] = '';
	}	
	
	
	echo json_encode($clientinfo);die();	
}
/* Filter Appointments On Appointup Page */
if(isset($_POST['general_ajax_action']) && $_POST['general_ajax_action']=='filter_appointments'){
	if($_POST['startdate']!=''){$_SESSION['apt_booking_filtersd'] = $_POST['startdate'];}else{ if(isset($_SESSION['apt_booking_filtersd'])){ unset($_SESSION['apt_booking_filtersd']); }}
	if($_POST['enddate']!=''){$_SESSION['apt_booking_filtered'] = $_POST['enddate'];}else{ if(isset($_SESSION['apt_booking_filtered'])){ unset($_SESSION['apt_booking_filtered']); }}
	if($_POST['staff_id']!=''){$_SESSION['apt_booking_filterstaff'] = $_POST['staff_id'];}else{ if(isset($_SESSION['apt_booking_filterstaff'])){ unset($_SESSION['apt_booking_filterstaff']); }}
	if($_POST['service_id']!=''){$_SESSION['apt_booking_filterservice'] = $_POST['service_id'];}else{ if(isset($_SESSION['apt_booking_filterservice'])){ unset($_SESSION['apt_booking_filterservice']); }}
}
/* Confirm,Reject,Cancel Appointment From Appointment Calender **/
if(isset($_POST['general_ajax_action'],$_POST['booking_id'],$_POST['method']) && $_POST['general_ajax_action']=='c_r_cs_cc_appointment' && $_POST['booking_id']!=''){
	$booking_id=$_POST['booking_id'];
	$booking_method=$_POST['method'];
	$bwid = $_POST['bwid'];
	
	/* Update Booking Status */
	if($booking_method=='C'){
	$apt_bookings->confirm_note = $_POST['action_content'];
	}
	if($booking_method=='R'){	
	$apt_bookings->reject_reason = $_POST['action_content'];
	}
	if($booking_method=='CS' || $booking_method=='CC'){				
	$apt_bookings->cancel_reason = $_POST['action_content'];
	}
	$apt_bookings->booking_id = $booking_id;
	$apt_bookings->booking_status = $booking_method;
	$apt_bookings->update_booking_status_by_id();
	
	/* Get booking-details */
	$apt_bookings->booking_id = $booking_id; 
	$apt_bookings->readOne_by_booking_id();

	$booking_date_start = $apt_bookings->booking_datetime;
	$booking_date_end = $apt_bookings->booking_endtime;
	$price = $apt_bookings->booking_price;
	
	$gc_event_id = $apt_bookings->gc_event_id;
	/** delete event google calendar code **/
	if(isset($gc_event_id) && $gc_event_id != ''){
		$curldeleteevent = curl_init();
		curl_setopt_array($curldeleteevent, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $plugin_url_for_ajax.'/assets/GoogleCalendar/deleteevent.php?eid='.$gc_event_id.'&pid=0&bwid=' . $bwid,
			CURLOPT_FRESH_CONNECT =>true,
			CURLOPT_USERAGENT => 'Appointment'
		));
		$respdelete = curl_exec($curldeleteevent);
		curl_close($curldeleteevent); 
	}
	/** delete event google calendar code **/
	
	/******************* Send MAIL and SMS code START *********************/
	
	if($booking_method=='C'){
		$C_ES_template_statuss = 'CC';
		$S_ES_template_statuss = 'CS';
		$A_ES_template_statuss = 'CA';
	}
	if($booking_method=='R'){
		$C_ES_template_statuss = 'RC';
		$S_ES_template_statuss = 'RS';
		$A_ES_template_statuss = 'RA';
	}
	if($booking_method=='CS'){
		$C_ES_template_statuss = 'CSC';
		$S_ES_template_statuss = 'CSS';
		$A_ES_template_statuss = 'CCA';
	}
	if($booking_method=='CC'){
		$C_ES_template_statuss = 'CCC';
		$S_ES_template_statuss = 'CCS';
		$A_ES_template_statuss = 'CSA';
	}
	
	
	$apt_booking->booking_id=$_POST['booking_id'];        
	$client_bookings= $apt_booking->get_all_bookings_by_b_id();		
	$sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);		
	$sender_email_address = get_option('appointment_email_sender_address'.'_'.$bwid);		
	$headers = "From: $sender_name <$sender_email_address>" . "\r\n";
	
	$company_name = get_option('appointment_company_name'.'_'.$bwid);
	$company_address = get_option('appointment_company_address'.'_'.$bwid);
	$company_city = get_option('appointment_company_city'.'_'.$bwid);
	$company_state = get_option('appointment_company_state'.'_'.$bwid);
	$company_zip = get_option('appointment_company_zip'.'_'.$bwid);
	$company_country = get_option('appointment_company_country'.'_'.$bwid);
	$company_phone = get_option('appointment_company_country_code'.'_'.$bwid).get_option('appointment_company_phone'.'_'.$bwid);
	$company_email = get_option('appointment_company_email'.'_'.$bwid);
	$company_logo = $business_logo = site_url()."/wp-content/uploads/".get_option('appointment_company_logo'.'_'.$bwid);
	
	/* main loop for content and mail start here */           
	$booking_counter = 1; 
	$booking_counter_txt = '';
	$booking_details = '';
	$booking_details_sms = '';
	
	foreach($client_bookings as $single_booking){
		$client_detailss = $apt_staff->C_readOne($single_booking->client_id);
		$client_name = $client_detailss['name'];
		$preff_username = $client_detailss['email'];
		$user_phone = $client_detailss['phone'];
		$next_order_id = $single_booking->order_id;
		$apt_service->id = $single_booking->service_id;
		$apt_staff->id = $single_booking->provider_id;                    
		$apt_service->readOne();                    
		$staffinfo = $apt_staff->readOne();   
		$location_details = '';
		if($single_booking->location_id!=0 || $single_booking->location_id!=''){
			$apt_location->id = $single_booking->location_id;
			$locationinfo = $apt_location->readOne();
			if(sizeof($locationinfo)>0){
				$location_details .= "<br/><span><strong>".__('Location','apt')."</strong>: ".stripslashes_deep($locationinfo[0]->location_title)."</span><br/><br/><span><strong>".__('Location Address','apt')."</strong>: ".stripslashes_deep($locationinfo[0]->address)."</span><br/><br/>";
			}
		}
		
		$addons_detail = '';
		$addon_titles = '';
		$addon_prices = '';
		$addon_qty = '';
		$apt_booking->order_id =  $single_booking->order_id;
		$serviceaddons_info = $apt_booking->select_addonsby_orderidand_serviceid();	
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
		
		$datetime = explode(' ',$single_booking->booking_datetime);        
		$booking_id=base64_encode($next_order_id);        
		$encoded_cinfo_sp=base64_encode($booking_id."-confirm");        
		$appoint_confirm_link_sp = plugins_url('',dirname(__FILE__))."/lib/booking_crc_email.php?".$encoded_cinfo_sp;                        
		$encoded_rinfo_sp=base64_encode($booking_id."-reject");        
		$appoint_reject_link_sp =plugins_url('',dirname(__FILE__))."/lib/booking_crc_email.php?".$encoded_rinfo_sp; 

		/*Client Cancel Link */
		$encoded_cinfo_client=base64_encode($booking_id."-clientcancel");        

		$appoint_cancel_link_client =plugins_url('',dirname(__FILE__))."/lib/booking_crc_email.php?".$encoded_cinfo_client;
		
		if(isset($_SESSION['booking_type']) || get_option('appointment_auto_confirm_appointment'.'_'.$bwid)=='Y' ){
		$confirm_link_sp='';
		}else{
		$confirm_link_sp="<a style='text-decoration: none;color: #FFF;background-color: #348eda;	border: solid #348eda;border-width: 10px 30px; line-height: 1;	font-weight: bold;margin-right: 10px;text-align: center;cursor: pointer;display: inline-block; border-radius: 10px;'  id='email-btn-primary' class='email-btn-primary' href='".$appoint_confirm_link_sp."-".base64_encode($single_booking->provider_id."+".$single_booking->id)."' >".__('Confirm','apt')."</a>";     
		}		
		$reject_link_sp ="<a style='text-decoration: none;color: #FFF;background-color: red;border: solid red;border-width: 10px 30px;line-height: 1;font-weight: bold;margin-right: 10px;text-align: center;cursor: pointer;display: inline-block;border-radius: 10px;'  id='email-btn-secondary' class='email-btn-secondary' href='".$appoint_reject_link_sp."-".base64_encode($single_booking->provider_id."+".$single_booking->id)."' >".__('Reject','apt')."</a>";

		$cancel_link_client ="<a style='text-decoration: none;color: #FFF;background-color: red;border: solid red;border-width: 10px 30px;line-height: 1;font-weight: bold;margin-right: 10px;text-align: center;cursor: pointer;display: inline-block;border-radius: 10px;'  id='email-btn-secondary' class='email-btn-secondary' href='".$appoint_cancel_link_client."-".base64_encode($single_booking->client_id."+".$single_booking->id)."' >".__('Cancel','apt')."</a>";
		

		
		$booking_details .= $location_details."<br/><span><strong>".__('For','apt')."</strong>: ".stripslashes_deep($apt_service->service_title)."</span><br/><br/>
								<span><strong>".__('With','apt')."</strong>: ".ucwords(stripslashes_deep($staffinfo[0]['staff_name']))."</span><br/><br/>
								<span><strong>".__('On','apt')."</strong>: ".date_i18n(get_option('date_format'),strtotime($datetime[0]))."</span><br/><br/>
								<span><strong>".__('At','apt')."</strong>: ".date_i18n(get_option('time_format'),strtotime($datetime[1]))."</span><br/><br/>
								<span>".$cancel_link_client."</span><br/>".$addons_detail;		

		$booking_details_sms .= ' With :'.ucwords(stripslashes_deep($staffinfo[0]['staff_name'])).' On : '.date_i18n(get_option('date_format'),strtotime($datetime[0])).' At : '.date_i18n(get_option('time_format'),strtotime($datetime[1])).' For: '.$apt_service->service_title.' ';
		
		if(sizeof($client_bookings) > 1) {
			$booking_counter_txt = "#".$booking_counter."<br/>";
		}
		
		$arr_providers_booking_details[$staffinfo[0]['id']][] = $location_details."<br/>".$booking_counter_txt."
		<span><strong>".__('For','apt')."</strong> :".stripslashes_deep($apt_service->service_title)."</span><br/><br/>
								<span><strong>".__('With','apt')."</strong> :".ucwords(stripslashes_deep($staffinfo[0]['staff_name']))."</span><br/><br/>
								<span><strong>".__('On','apt')."</strong> :".date_i18n(get_option('date_format'),strtotime($datetime[0]))."</span><br/><br/>
								<span><strong>".__('At','apt')."</strong> :".date_i18n(get_option('time_format'),strtotime($datetime[1]))."</span><br/><br/>".$addons_detail."<span>".$confirm_link_sp."</span>	<span>".$reject_link_sp."</span><br/>";
		
		
		$booking_counter++;
	}
	/* Mail content loop end here */

	/* Send Email To Client */
	if(get_option('appointment_client_email_notification_status'.'_'.$bwid)=='E'){	
		$apt_clientemail_templates = new appointment_email_template();
		$msg_template = $apt_clientemail_templates->email_parent_template;	
		$apt_clientemail_templates->email_template_name = $C_ES_template_statuss;
		
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
			$search = array('{{customer_name}}','{{booking_details}}','{{company_name}}','{{service_provider_name}}','{{admin_manager_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');      
			$replace_with = array($client_name,$booking_details,$company_name,'','',$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);
			
			$email_client_message = str_replace($search,$replace_with,$email_content);	
			$email_client_message = str_replace('###msg_content###',$email_client_message,$msg_template);		
			add_filter( 'wp_mail_content_type', 'set_content_type' );
			$status = wp_mail($preff_username,$email_subject,$email_client_message,$headers);           
		}       
	}
	/* Send email to admin/service provider when booking is complete */
	$client_full_detail='<br/>';
	$sms_client_full_detail=' ';
	if(ucwords($client_name)!=''){ 
		$client_full_detail .= "<span><strong>".__('Client Name','apt')."</strong>: ".ucwords($client_name)."</span><br/><br/>";
		$sms_client_full_detail .= __('Client Name','apt').": ".ucwords($client_name)." ";
	}if($preff_username!=''){ 
		$client_full_detail .= "<span><strong>".__('Client Email','apt')."</strong>: ".$preff_username."</span><br/><br/>";
		$sms_client_full_detail .= __('Client Email','apt').": ".$preff_username." ";
	}	
	if($user_gender!=''){
		if($user_gender == "M"){
			$gender_display = "Male";
		}else{
			$gender_display = "Female";
		}
		$client_full_detail .="<span><strong>".__('Gender','apt')."</strong>: ".$gender_display."</span><br/><br/>";
		$sms_client_full_detail .= __('Gender','apt').": ".$gender_display." ";
	}
	
	if($user_phone!=''){
		$client_full_detail .="<span><strong>".__('Client Phone','apt')."</strong>: ".$user_phone."</span><br/><br/>";
		$sms_client_full_detail .= __('Client Phone','apt').": ".$user_phone." ";
	}
	if($user_address!=''){
		$client_full_detail .="<span><strong>".__('Address','apt')."</strong>: ".$user_address."</span><br/><br/>";
		$sms_client_full_detail .= __('Address','apt').": ".$user_address." ";
	}
	if($user_city!=''){
		$client_full_detail .="<span><strong>".__('City','apt')."</strong>: ".$user_city."</span><br/><br/>";
		$sms_client_full_detail .= __('City','apt').": ".$user_city." ";
	}
	if($user_state!=''){
		$client_full_detail .="<span><strong>".__('State','apt')."</strong>: ".$user_state."</span><br/><br/>";
		$sms_client_full_detail .= __('State','apt').": ".$user_state." ";
	}
	if($user_notes!=''){
		$client_full_detail .="<span><strong>".__('Notes','apt')."</strong>: ".$user_notes."</span><br/><br/>";
		$sms_client_full_detail .= __('Notes','apt').": ".$user_notes." ";
	}
	$client_detail= $client_full_detail;
	$sms_client_detail= $sms_client_full_detail;
	
	/* Send Email To Staff */
	if(get_option('appointment_service_provider_email_notification_status'.'_'.$bwid)=='E'){	
		$apt_staffemail_templates = new appointment_email_template();	
		$msg_template = $apt_staffemail_templates->email_parent_template;
		$apt_staffemail_templates->email_template_name = $S_ES_template_statuss;
		$template_detail = $apt_staffemail_templates->readOne();        
		if($template_detail[0]->email_message!=''){            
			$email_content = $template_detail[0]->email_message;        
		}else{            
			$email_content = $template_detail[0]->default_message;
		}        
		$email_subject = $template_detail[0]->email_subject;   
		$email_staff_message = '';
		
		if($template_detail[0]->email_template_status=='e'){
			foreach($arr_providers_booking_details as $provider_id => $bookingstrarr){
				$apt_staff->id = $provider_id;
				$staffinfo = $apt_staff->readOne();
				
				$strtoprint = "";
				foreach ($bookingstrarr as $bookingsss) {
					$strtoprint .= $bookingsss;
				}
				$sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);
				$search = array('{{admin_manager_name}}','{{service_provider_name}}','{{booking_details}}','{{appoinment_client_detail}}','{{company_name}}','{{customer_name}}','{{admin_manager_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');
				
				$replace_with = array($sender_name,$staffinfo[0]['staff_name'],$client_detail,$company_name,'','',$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);

				$email_staff_message = str_replace($search,$replace_with,$email_content);
				$email_staff_message = str_replace('###msg_content###',$email_staff_message,$msg_template);
				add_filter( 'wp_mail_content_type', 'set_content_type' );
				$status = wp_mail($staffinfo[0]['email'],$email_subject,$email_staff_message,$headers);
			}
		}
	}
	/* Send Email To Admin */	
	$arr_admin_booking_detail='';        
	foreach($arr_providers_booking_details as $provider_id => $bookingstrarr){
			for($i=0;$i<sizeof($bookingstrarr);$i++){                
				$arr_admin_booking_detail .=$bookingstrarr[$i];               
			}
	}	
	$arr_admin_bookingfulldetail=$arr_admin_booking_detail;
	
	if(get_option('appointment_admin_email_notification_status'.'_'.$bwid)=='E'){
		$apt_adminemail_templates = new appointment_email_template();
		$msg_template = $apt_adminemail_templates->email_parent_template;
		$apt_adminemail_templates->email_template_name = $A_ES_template_statuss;
		$template_detail = $apt_adminemail_templates->readOne();        
		if($template_detail[0]->email_message!=''){            
			$email_content = $template_detail[0]->email_message;        
		}else{            
			$email_content = $template_detail[0]->default_message;
		}        
		$email_subject = $template_detail[0]->email_subject;
		$email_admin_message = '';
		$admin_sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);

		$search = array('{{admin_manager_name}}','{{booking_details}}','{{appoinment_client_detail}}','{{company_name}}','{{customer_name}}','{{service_provider_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');
		
		$replace_with = array($admin_sender_name,$company_name,$arr_admin_bookingfulldetail,$client_detail,$company_name,'','',$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);
		$email_admin_message = str_replace($search,$replace_with,$email_content);
		$email_admin_message = str_replace('###msg_content###',$email_admin_message,$msg_template);
		add_filter( 'wp_mail_content_type', 'set_content_type' );		
		$status = wp_mail(get_option('appointment_email_sender_address'.'_'.$bwid),$email_subject,$email_admin_message,$headers); 	
	}
	/******************* Send SMS code START *********************/

	if(get_option("appointment_sms_reminder_status".'_'.$bwid) == "E"){
		/** TWILIO **/
		if(get_option("appointment_sms_noti_twilio".'_'.$bwid) == "E"){
			$obj_sms_template = new appointment_sms_template();
			$twillio_sender_number = get_option('appointment_twilio_number'.'_'.$bwid);
			$AccountSid = get_option('appointment_twilio_sid'.'_'.$bwid);
			$AuthToken =  get_option('appointment_twilio_auth_token'.'_'.$bwid); 
			
			/* Send SMS To Client */
			if(get_option('appointment_twilio_client_sms_notification_status'.'_'.$bwid) == "E"){
				$twilliosms_client = new Client($AccountSid, $AuthToken);
				$template1 = $obj_sms_template->gettemplate_sms("C",'e',$C_ES_template_statuss);
				if($template1[0]->sms_template_status == "e" && $user_phone!=''){
					if($template1[0]->sms_message == ""){
						$message = strip_tags($template1[0]->default_message);
					}else{
						$message = strip_tags($template1[0]->sms_message);
					}
					$sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);
					$search = array('{{customer_name}}','{{booking_details}}','{{booking_detail}}','{{company_name}}','{{service_provider_name}}','{{admin_manager_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');
					$replace_with = array($client_name,$booking_details_sms,$booking_details_sms,$company_name,'',$sender_name,$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);
					$client_sms_body = str_replace($search,$replace_with,$message);
					$twilliosms_client->messages->create(
						$user_phone,
						array(
							'from' => $twillio_sender_number,
							'body' => $client_sms_body 
						)
					);
				}
			}
			/* Send SMS To Staff */
			if(get_option('appointment_twilio_service_provider_sms_notification_status'.'_'.$bwid) == "E"){
				$twilliosms_staff = new Client($AccountSid, $AuthToken);
				foreach($arr_providers_booking_details as $provider_id => $bookingstrarr){
					$apt_staff->id = $provider_id;
					$staffinfo = $apt_staff->readOne();
					$template = $obj_sms_template->gettemplate_sms("SP",'e',$S_ES_template_statuss);
					
					if($template[0]->sms_template_status == "e" && $staffinfo[0]['phone']!=''){
						if($template[0]->sms_message == ""){
							$message = strip_tags($template[0]->default_message);
						}else{
							$message = strip_tags($template[0]->sms_message);
						}
						$sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);
						$search = array('{{admin_manager_name}}','{{service_provider_name}}','{{booking_details}}','{{booking_detail}}','{{appoinment_client_detail}}','{{company_name}}','{{customer_name}}','{{admin_manager_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');
					
						$replace_with = array($sender_name,$staffinfo[0]['staff_name'],$booking_details_sms,$booking_details_sms,$sms_client_detail,$company_name,'','',$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);

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
			}
			/* Send SMS To Admin */
			if(get_option('appointment_twilio_admin_sms_notification_status'.'_'.$bwid) == "E"){		   
				$twilliosms_admin = new Client($AccountSid, $AuthToken);
				$template = $obj_sms_template->gettemplate_sms("AM",'e',$A_ES_template_statuss);					
				if($template[0]->sms_template_status == "e" && get_option('appointment_twilio_admin_phone_no'.'_'.$bwid)!=''){
					if($template[0]->sms_message == ""){
						$message = strip_tags($template[0]->default_message);
					}else{
						$message = strip_tags($template[0]->sms_message);
					}
					$sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);
					$search = array('{{admin_manager_name}}','{{service_provider_name}}','{{booking_details}}','{{booking_detail}}','{{appoinment_client_detail}}','{{company_name}}','{{customer_name}}','{{admin_manager_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');
					
					$replace_with = array($sender_name,$staffinfo[0]['staff_name'],$booking_details_sms,$booking_details_sms,$sms_client_detail,$company_name,'','',$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);
					
					$admin_sms_body = str_replace($search,$replace_with,$message);	

					$twilliosms_staff->messages->create(
						get_option('appointment_twilio_ccode'.'_'.$bwid).get_option('appointment_twilio_admin_phone_no'.'_'.$bwid),
						array(
							'from' => $twillio_sender_number,
							'body' => $admin_sms_body 
						)
					);
				}
			}
		}
				
		/** PLIVO **/
		if(get_option("appointment_sms_noti_plivo".'_'.$bwid) == "E"){
			$obj_sms_template = new appointment_sms_template();
			$plivo_sender_number = get_option('appointment_plivo_number'.'_'.$bwid);	
			$auth_sid = get_option('appointment_plivo_sid'.'_'.$bwid);
			$auth_token = get_option('appointment_plivo_auth_token'.'_'.$bwid);
			
			/* Send SMS To Client */
			if(get_option('appointment_plivo_client_sms_notification_status'.'_'.$bwid) == "E"){
				$p_client = new Plivo\RestAPI($auth_sid, $auth_token, '', '');
				$template1 = $obj_sms_template->gettemplate_sms("C",'e',$C_ES_template_statuss);
				if($template1[0]->sms_template_status == "e" && $user_phone!=''){
					if($template1[0]->sms_message == ""){
						$message = strip_tags($template1[0]->default_message);
					}else{
						$message = strip_tags($template1[0]->sms_message);
					}
					$sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);
					$search = array('{{customer_name}}','{{booking_details}}','{{booking_detail}}','{{company_name}}','{{service_provider_name}}','{{admin_manager_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');
					$replace_with = array($client_name,$booking_details_sms,$booking_details_sms,$company_name,'',$sender_name,$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);
					$client_sms_body = str_replace($search,$replace_with,$message);
					
					$clientparams = array(
						'src' => $plivo_sender_number,
						'dst' => $user_phone,
						'text' => $client_sms_body,
						'method' => 'POST'
					);
					$response = $p_client->send_message($clientparams);
				}
			}
			/* Send SMS To Staff */
			if(get_option('appointment_plivo_service_provider_sms_notification_status'.'_'.$bwid) == "E"){
				$p_staff = new Plivo\RestAPI($auth_id, $auth_token, '', '');
				foreach($arr_providers_booking_details as $provider_id => $bookingstrarr){
					$apt_staff->id = $provider_id;
					$staffinfo = $apt_staff->readOne();
					$template = $obj_sms_template->gettemplate_sms("SP",'e',$S_ES_template_statuss);
					
					if($template[0]->sms_template_status == "e" && $staffinfo[0]['phone']!=''){
						if($template[0]->sms_message == ""){
							$message = strip_tags($template[0]->default_message);
						}else{
							$message = strip_tags($template[0]->sms_message);
						}
						$sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);
						$search = array('{{admin_manager_name}}','{{service_provider_name}}','{{booking_details}}','{{booking_detail}}','{{appoinment_client_detail}}','{{company_name}}','{{customer_name}}','{{admin_manager_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');
					
						$replace_with = array($sender_name,$staffinfo[0]['staff_name'],$booking_details_sms,$booking_details_sms,$sms_client_detail,$company_name,'','',$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);

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
			}
			/* Send SMS To Admin */
			if(get_option('appointment_plivo_admin_sms_notification_status'.'_'.$bwid) == "E"){		   
				$twilliosms_admin = new Client($AccountSid, $AuthToken);
				$template = $obj_sms_template->gettemplate_sms("AM",'e',$A_ES_template_statuss);					
				if($template[0]->sms_template_status == "e" && get_option('appointment_plivo_admin_phone_no'.'_'.$bwid)!=''){
					if($template[0]->sms_message == ""){
						$message = strip_tags($template[0]->default_message);
					}else{
						$message = strip_tags($template[0]->sms_message);
					}
					$sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);
					$search = array('{{admin_manager_name}}','{{service_provider_name}}','{{booking_details}}','{{booking_detail}}','{{appoinment_client_detail}}','{{company_name}}','{{customer_name}}','{{admin_manager_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');
					
					$replace_with = array($sender_name,$staffinfo[0]['staff_name'],$booking_details_sms,$booking_details_sms,$sms_client_detail,$company_name,'','',$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);
					
					$admin_sms_body = str_replace($search,$replace_with,$message);	

					$adminparams = array(
						'src' => $plivo_sender_number,
						'dst' => get_option('appointment_plivo_ccode'.'_'.$bwid).get_option('appointment_plivo_admin_phone_no'.'_'.$bwid),
						'text' => $admin_sms_body,
						'method' => 'POST'
						);
					$response = $p_admin->send_message($adminparams);
				}
			}
		}
		
		/** NEXMO **/
		if(get_option("appointment_sms_noti_nexmo".'_'.$bwid) == "E"){
			$obj_sms_template = new appointment_sms_template();
			include_once(dirname(dirname(dirname(__FILE__))).'/objects/class_nexmo.php');
			$nexmo_client = new appointment_nexmo();
			$nexmo_client->appointment_nexmo_apikey = get_option('appointment_nexmo_apikey'.'_'.$bwid);
			$nexmo_client->appointment_nexmo_api_secret = get_option('appointment_nexmo_api_secret'.'_'.$bwid);
			$nexmo_client->appointment_nexmo_form = get_option('appointment_nexmo_form'.'_'.$bwid);
			
			/* Send SMS To Client */
			if(get_option('appointment_nexmo_send_sms_client_status'.'_'.$bwid) == "E"){
				$template1 = $obj_sms_template->gettemplate_sms("C",'e',$C_ES_template_statuss);
				if($template1[0]->sms_template_status == "e" && $user_phone!=''){
					if($template1[0]->sms_message == ""){
						$message = strip_tags($template1[0]->default_message);
					}else{
						$message = strip_tags($template1[0]->sms_message);
					}
					$sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);
					$search = array('{{customer_name}}','{{booking_details}}','{{booking_detail}}','{{company_name}}','{{service_provider_name}}','{{admin_manager_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');
					$replace_with = array($client_name,$booking_details_sms,$booking_details_sms,$company_name,'',$sender_name,$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);
					$client_sms_body = str_replace($search,$replace_with,$message);
					$res = $nexmo_client->send_nexmo_sms($user_phone,$client_sms_body);
				}
			}
			/* Send SMS To Staff */
			if(get_option('appointment_nexmo_send_sms_sp_status'.'_'.$bwid) == "E"){
				foreach($arr_providers_booking_details as $provider_id => $bookingstrarr){
					$apt_staff->id = $provider_id;
					$staffinfo = $apt_staff->readOne();
					$template = $obj_sms_template->gettemplate_sms("SP",'e',$S_ES_template_statuss);
					
					if($template[0]->sms_template_status == "e" && $staffinfo[0]['phone']!=''){
						if($template[0]->sms_message == ""){
							$message = strip_tags($template[0]->default_message);
						}else{
							$message = strip_tags($template[0]->sms_message);
						}
						$sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);
						$search = array('{{admin_manager_name}}','{{service_provider_name}}','{{booking_details}}','{{booking_detail}}','{{appoinment_client_detail}}','{{company_name}}','{{customer_name}}','{{admin_manager_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');
					
						$replace_with = array($sender_name,$staffinfo[0]['staff_name'],$booking_details_sms,$booking_details_sms,$sms_client_detail,$company_name,'','',$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);

						$staff_sms_body = str_replace($search,$replace_with,$message);
						$nexmo_client->send_nexmo_sms($staffinfo[0]['phone'],$staff_sms_body);
					}
				}
			}
			/* Send SMS To Admin */
			if(get_option('appointment_nexmo_send_sms_admin_status'.'_'.$bwid) == "E"){
				$template = $obj_sms_template->gettemplate_sms("AM",'e',$A_ES_template_statuss);					
				if($template[0]->sms_template_status == "e" && get_option('appointment_nexmo_admin_phone_no'.'_'.$bwid)!=''){
					if($template[0]->sms_message == ""){
						$message = strip_tags($template[0]->default_message);
					}else{
						$message = strip_tags($template[0]->sms_message);
					}
					$sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);
					$search = array('{{admin_manager_name}}','{{service_provider_name}}','{{booking_details}}','{{booking_detail}}','{{appoinment_client_detail}}','{{company_name}}','{{customer_name}}','{{admin_manager_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');
					
					$replace_with = array($sender_name,$staffinfo[0]['staff_name'],$booking_details_sms,$booking_details_sms,$sms_client_detail,$company_name,'','',$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);
					
					$admin_sms_body = str_replace($search,$replace_with,$message);
					$nexmo_client->send_nexmo_sms(get_option('appointment_nexmo_ccode'.'_'.$bwid).get_option('appointment_nexmo_admin_phone_no'.'_'.$bwid),$admin_sms_body);
				}
			}
		}
		
		/** TEXTLOCAL **/
		if(get_option("appointment_sms_noti_textlocal".'_'.$bwid) == "E"){
			$obj_sms_template = new appointment_sms_template();
			$textlocal_apikey = get_option('appointment_textlocal_apikey'.'_'.$bwid);
			$textlocal_sender = get_option('appointment_textlocal_sender'.'_'.$bwid);
			
			/* Send SMS To Client */
			if(get_option('appointment_textlocal_client_sms_notification_status') == "E"){
				$template1 = $obj_sms_template->gettemplate_sms("C",'e',$C_ES_template_statuss);
				if($template1[0]->sms_template_status == "e" && $user_phone!=''){
					if($template1[0]->sms_message == ""){
						$message = strip_tags($template1[0]->default_message);
					}else{
						$message = strip_tags($template1[0]->sms_message);
					}
					$sender_name = get_option('appointment_email_sender_name');
					$search = array('{{customer_name}}','{{booking_details}}','{{booking_detail}}','{{company_name}}','{{service_provider_name}}','{{admin_manager_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');
					$replace_with = array($client_name,$booking_details_sms,$booking_details_sms,$company_name,'',$sender_name,$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);
					$client_sms_body = str_replace($search,$replace_with,$message);
					
					$textlocal_numbers = $user_phone;
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
			if(get_option('appointment_textlocal_service_provider_sms_notification_status'.'_'.$bwid) == "E"){
				foreach($arr_providers_booking_details as $provider_id => $bookingstrarr){
					$apt_staff->id = $provider_id;
					$staffinfo = $apt_staff->readOne();
					$template = $obj_sms_template->gettemplate_sms("SP",'e',$S_ES_template_statuss);
					
					if($template[0]->sms_template_status == "e" && $staffinfo[0]['phone']!=''){
						if($template[0]->sms_message == ""){
							$message = strip_tags($template[0]->default_message);
						}else{
							$message = strip_tags($template[0]->sms_message);
						}
						$sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);
						$search = array('{{admin_manager_name}}','{{service_provider_name}}','{{booking_details}}','{{booking_detail}}','{{appoinment_client_detail}}','{{company_name}}','{{customer_name}}','{{admin_manager_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');
					
						$replace_with = array($sender_name,$staffinfo[0]['staff_name'],$booking_details_sms,$booking_details_sms,$sms_client_detail,$company_name,'','',$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);

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
			}
			/* Send SMS To Admin */
			if(get_option('appointment_textlocal_admin_sms_notification_status') == "E"){
				$template = $obj_sms_template->gettemplate_sms("AM",'e',$A_ES_template_statuss);					
				if($template[0]->sms_template_status == "e" && get_option('appointment_textlocal_admin_phone_no'.'_'.$bwid)!=''){
					if($template[0]->sms_message == ""){
						$message = strip_tags($template[0]->default_message);
					}else{
						$message = strip_tags($template[0]->sms_message);
					}
					$sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);
					$search = array('{{admin_manager_name}}','{{service_provider_name}}','{{booking_details}}','{{booking_detail}}','{{appoinment_client_detail}}','{{company_name}}','{{customer_name}}','{{admin_manager_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');
					
					$replace_with = array($sender_name,$staffinfo[0]['staff_name'],$booking_details_sms,$booking_details_sms,$sms_client_detail,$company_name,'','',$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo);
					
					$admin_sms_body = str_replace($search,$replace_with,$message);
					
					$textlocal_numbers = get_option('appointment_textlocal_ccode'.'_'.$bwid).get_option('appointment_textlocal_admin_phone_no'.'_'.$bwid);
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
	}
	/******************* Send SMS code END *********************/
	
	/******************* Send MAIL and SMS code END *********************/
}
/* Delete Appointment,Order Payment,Order Client Info */
if(isset($_POST['general_ajax_action'],$_POST['booking_id']) && $_POST['general_ajax_action']=='delete_appointment' && $_POST['booking_id']!=''){
	$bwid = $_POST['bwid'];
	$apt_bookings->booking_id = $_POST['booking_id'];
	$apt_bookings->readOne_by_booking_id();
	$apt_bookings->ordrer_id =  $apt_bookings->order_id;
	$order_all_bookings = $apt_bookings->get_all_bookings_by_order_id();
	$gc_event_id = $apt_bookings->gc_event_id;
	
	/** delete event google calendar code **/
	if(isset($gc_event_id) && $gc_event_id != ''){
		$curldeleteevent = curl_init();
		curl_setopt_array($curldeleteevent, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => $plugin_url_for_ajax.'/assets/GoogleCalendar/deleteevent.php?eid='.$gc_event_id.'&pid=0&bwid=' . $bwid,
		CURLOPT_FRESH_CONNECT =>true,
		CURLOPT_USERAGENT => 'Appointment'
		));
		$respdelete = curl_exec($curldeleteevent);
		curl_close($curldeleteevent); 
	}
	/** delete event google calendar code **/	
	if(sizeof($order_all_bookings)<=1){			 	
		 $order_info->order_id = $apt_bookings->order_id;   
		 $order_info->delete_order_client_info_by_order_id();
		 $payments->order_id = $apt_bookings->order_id;  
		 $payments->delete_payments_by_order_id();	
	}
	$apt_bookings->booking_id = $_POST['booking_id'];
	$apt_bookings->delete_booking();
	
}
if(isset($_POST['general_ajax_action']) && $_POST['general_ajax_action']=='book_manual_appointment'){	
		$bwid = $_POST['bwid'];
		$apt_bookings->get_last_order_id();
		$last_order_id = $apt_bookings->last_order_id;
		if($last_order_id=='') {
			$last_order_id = 1000;
		}
		$next_order_id = $last_order_id +1;		
		$booking_datetime= date_i18n('Y-m-d H:i:s',strtotime($_POST['booking_date'].' '.$_POST['booking_time']));
		$booking_endtime= date_i18n('Y-m-d H:i:s',strtotime("+".$_POST['service_duration']." minutes", strtotime(date_i18n('Y-m-d',strtotime($_POST['booking_date'])).' '.date_i18n('H:i:s',strtotime($_POST['booking_time'])))));
		$booking_status = 'A';
		if(get_option('appointment_appointment_auto_confirm'.'_'.$bwid)=='E'){
			$booking_status = 'C';
		}
		$taxvat = 0;
		if(get_option('appointment_taxvat_status'.'_'.$bwid)=='E'){
			if(get_option('appointment_taxvat_type'.'_'.$bwid)=='P'){ $taxvat = $_POST['service_price']*get_option('appointment_taxvat_amount'.'_'.$bwid)/100;}
			if(get_option('appointment_taxvat_type'.'_'.$bwid)=='F'){ $taxvat = $_POST['service_price']+get_option('appointment_taxvat_amount'.'_'.$bwid);}
		}
		$booking_price = $taxvat+$_POST['service_price'];
		
		
		if(get_option('appointment_guest_user_checkout'.'_'.$bwid)=='E' && $_POST['client_id']==''){
			$client_id = 0;
		}elseif($_POST['client_id']!=''){
			$client_id = $_POST['client_id'];			
		}else{
			$client_data = array('ID' => '','user_pass' => $_POST['client_password'],'user_login' => $_POST['client_username'],'display_name' => $_POST['client_name'],'first_name' => $_POST['client_name'],'last_name' => $_POST['client_name'],'user_email' => $_POST['client_email'],'role' => 'subscriber' );
			$client_id = wp_insert_user( $client_data );
			$user = new WP_User($client_id);
			$user->add_cap('apt_client');
			add_user_meta($client_id, 'apt_client_locations','#'.$_SESSION['apt_location'].'#');
		}
		$ccode = '';
		$client_personal_info_array = array("phone1"=>$_POST['client_phone'],"address"=>$_POST['client_address'],"zip"=>$_POST['client_zip'],"city"=>$_POST['client_city'],"skype"=>'',"notes"=>$_POST['booking_note'],"age"=>'',"dob"=>'',"ccode"=>$ccode,"gender"=>'',"state"=>$_POST['client_state'],"country"=>$_POST['client_country']);
		$client_otherinfo = serialize($client_personal_info_array);
		
		/* Adding Appointments Into Google Calendar START */
	if (!function_exists('appointment_addevent_googlecalender_provider')) {
		function appointment_addevent_googlecalender_provider($provider_id,$provider_gc_id,$gc_token,$summary,$location,$description,$event_color,$date,$start,$end,$GcclientID,$GcclientSecret,$GcEDvalue,$providerTZ,$bwid){
			require_once dirname(dirname(dirname(__FILE__)))."/assets/GoogleCalendar/google-api-php-client/src/Google_Client.php";
			require_once dirname(dirname(dirname(__FILE__)))."/assets/GoogleCalendar/google-api-php-client/src/contrib/Google_CalendarService.php";

			$clientP = new Google_Client();
			$clientP->setApplicationName("Appointment Google Calender");
			$clientP->setClientId($GcclientID);
			$clientP->setClientSecret($GcclientSecret);   
			$clientP->setRedirectUri(get_option('apt_gc_frontend_url'.'_'.$bwid));
			$clientP->setDeveloperKey($GcclientID);
			$clientP->setScopes( 'https://www.googleapis.com/auth/calendar' );
			$clientP->setAccessType('offline');

			$calP = new Google_CalendarService($clientP);

			$clientP->setAccessToken($gc_token);
			$accesstoken = json_decode($gc_token);  

			if ($gc_token) {
				if ($clientP->isAccessTokenExpired()) {
					$clientP->refreshToken($accesstoken->refresh_token);
				}
			}
			if ($clientP->getAccessToken()){
				$startTP = new Google_EventDateTime();
				$endTP = new Google_EventDateTime();
				$eventP = new Google_Event();
				$calendarId = $provider_gc_id;
				$startTP->setTimeZone($providerTZ);
				$startTP->setDateTime($date."T".$start);
				$endTP->setTimeZone($providerTZ);
				$endTP->setDateTime($date."T".$end);
				$eventP->setSummary($summary);
				$eventP->setColorId($event_color);
				$eventP->setLocation($location);
				$eventP->setDescription($description);
				$eventP->setStart($startTP);
				$eventP->setEnd($endTP);

				$insert = $calP->events->insert($provider_gc_id,$eventP);
			}

			if(isset($insert)){
				return $insert;
			}else{
				return '';
			}  
		}
	}
	
	$GcclientID =  get_option('apt_gc_client_id'.'_'.$bwid);
	$GcclientSecret = get_option('apt_gc_client_secret'.'_'.$bwid);
	$GcEDvalue = get_option('apt_gc_status'.'_'.$bwid);
		
	$service = new appointment_service();
	$service->id=$_POST['service_id'];
	$serviceInfo = $service->readOne();
	$service_title = $service->service_title;
	
	$gc_token = get_option('apt_gc_token'.'_'.$bwid);
	$summary = $service_title."-".$_POST['client_name'];
	$description = 'Service='.$service_title.', Name='.$_POST['client_name'].', Email='.$_POST['client_email'].', Phone='.$_POST['client_phone'];
	$event_color = '9';
	
	$date = date_i18n('Y-m-d', strtotime($booking_datetime));
	$start = date_i18n('H:i:s', strtotime($booking_datetime));
	$end = date_i18n('H:i:s', strtotime($booking_datetime));
	if(get_option('timezone_string') != ''){
		$providerTZ = get_option('timezone_string');
	}else{
		$gmt_offset = get_option('gmt_offset');
		$hr_minute = explode('.', $gmt_offset);
		if (isset($hr_minute[1])) {
			if ($hr_minute[1] == '5') {
				$gmt_offset = $hr_minute[0].'.30';
			}else{
				$gmt_offset = $hr_minute[0].'.45';
			}
		}else{
			$gmt_offset = $hr_minute[0];
		}
		$seconds = $gmt_offset * 60 * 60;
		$get_tz = timezone_name_from_abbr('', $seconds, 1);
		if($get_tz === false){ $get_tz = timezone_name_from_abbr('', $seconds, 0); }
		$providerTZ = $get_tz;
	}
	$provider_gc_id = get_option('apt_gc_id'.'_'.$bwid);
	$provider_id = '';
	if($gc_token != '' && $GcEDvalue == 'Y' && $GcclientID!='' && $GcclientSecret!=''){
		$event_Status = appointment_addevent_googlecalender_provider($provider_id,$provider_gc_id,$gc_token,$summary,$location,$description,$event_color,$date,$start,$end,$GcclientID,$GcclientSecret,$GcEDvalue,$providerTZ,$bwid);
		 $gc_event_id = $event_Status['id'];
	}else{
		 $gc_event_id = '';
	}
	
	global $wpdb;
	$wpdb->query("insert into ".$wpdb->prefix."apt_bookings set location_id='".$_SESSION['apt_location']."',order_id='".$next_order_id."',business_owner_id=".$bwid.",client_id='".$client_id."',service_id='".$_POST['service_id']."',provider_id='".$_POST['provider_id']."',booking_price='".$booking_price."',booking_datetime='".$booking_datetime."',booking_endtime='".$booking_endtime."',booking_status='".$booking_status."',lastmodify='".date_i18n('Y-m-d H:i:s')."',gc_event_id='".$gc_event_id."'");
	/*  Adding Appointments Into Google Calendar END  */
		
		
		
		
	$booking_id = $wpdb->insert_id;
	$wpdb->query("insert into ".$wpdb->prefix."apt_payments set location_id='".$_SESSION['apt_location']."',business_owner_id=".$bwid.",order_id='".$next_order_id."',client_id='".$client_id."',payment_method='pay_locally',amount='".$_POST['service_price']."',taxes='".$taxvat."', 	net_total='".$booking_price."',lastmodify='".date_i18n('Y-m-d H:i:s')."'");
	
	$wpdb->query("insert into ".$wpdb->prefix."apt_order_client_info set business_owner_id=".$bwid.", order_id='".$next_order_id."',client_name='".$_POST['client_name']."',client_email='".$_POST['client_email']."',client_phone='".$_POST['client_phone']."',client_personal_info='".$client_otherinfo."'");
		
	echo $booking_id; die();
		
}
/** Sending Email For Booking Actions **/
if(isset($_POST['booking_id'],$_POST['method']) && $_POST['booking_id']!='' && $_POST['method']!=''){	
	/* Get booking-details */
	$company_name = get_option('appointment_company_name'.'_'.$bwid);
	$company_address = get_option('appointment_company_address'.'_'.$bwid);
	$company_city = get_option('appointment_company_city'.'_'.$bwid);
	$company_state = get_option('appointment_company_state'.'_'.$bwid);
	$company_zip = get_option('appointment_company_zip'.'_'.$bwid);
	$company_country = get_option('appointment_company_country'.'_'.$bwid);
	$company_phone = get_option('appointment_company_country_code'.'_'.$bwid).get_option('appointment_company_phone'.'_'.$bwid);
	$company_email = get_option('appointment_company_email'.'_'.$bwid);
	$company_logo = $business_logo = site_url()."/wp-content/uploads/".get_option('appointment_company_logo'.'_'.$bwid);
	$sender_name = get_option('appointment_email_sender_name'.'_'.$bwid);
	$sender_email_address = get_option('appointment_email_sender_address'.'_'.$bwid);
	$headers = "From: $sender_name <$sender_email_address>" . "\r\n";
	
	$booking_method = $_POST['method'];
	$booking_id		= $_POST['booking_id'];
	
	$apt_bookings->booking_id = $booking_id; 
	$apt_bookings->readOne_by_booking_id();

	$booking_datetime = $apt_bookings->booking_datetime;
	$booking_reject_reason = $apt_bookings->reject_reason;
	$booking_cancel_reason = $apt_bookings->cancel_reason;
	$booking_confirm_note = $apt_bookings->confirm_note;
	$booking_order_id = $apt_bookings->order_id;
	
	
	
	
	$booking_date_start = $apt_bookings->booking_datetime;
	$booking_date_end = $apt_bookings->booking_endtime;
	$price = $apt_bookings->booking_price;
							
	$service->id = $apt_bookings->service_id;                    
	$provider->id = $apt_bookings->provider_id;                    
	$service->readOne();                    
	$staffinfo = $provider->readOne();
	
	
	
	
	$location_title = '';
	$location_description = '';
	$location_email = '';
	$location_phone = '';
	$location_address = '';
	$location_city = '';
	$location_state = '';
	$location_zip = '';
	$location_country = '';

	if($apt_bookings->location_id!=0 || $apt_bookings->location_id!=''){
		$location->id = $apt_bookings->location_id;
		$locationinfo = $location->readOne();
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
	$apt_bookings->order_id =  $apt_bookings->order_id;
	$serviceaddons_info = $apt_bookings->select_addonsby_orderidand_serviceid();	
	$totalserviceaddons = sizeof($serviceaddons_info);
	if($totalserviceaddons>0){
		$addoncounter = 1;
		foreach($serviceaddons_info as $serviceaddon_info){				
			$service->addon_id = $serviceaddon_info->addons_service_id;
			$addon_info = $service->readOne_addon();
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
	
	$payments->order_id = $apt_bookings->order_id;
	$payments->read_one_by_order_id();	
	
	/* Refund/Add Loyalty Points ON Cancel/Reject/Cancel BY Client Booking */
	if(($booking_method=='R' || $booking_method=='CS' || $booking_method=='CC') && $payments->payment_method != 'pay_locally'){
		$curr_bal = 0;
		$loyalty_points->client_id =  $apt_bookings->client_id;
		$loyalty_points->get_client_balance();
		if(isset($loyalty_points->balance) && $loyalty_points->balance!=''){
			$curr_bal = $loyalty_points->balance;
		}	
		$apt_bookings->order_id = $apt_bookings->order_id;
		$totalorder_bookings = $apt_bookings->count_order_bookings();
		$refund_points = $payments->net_total/$totalorder_bookings;
		$loyalty_points->booking_id = $apt_bookings->booking_id;
		$loyalty_points->client_id = $apt_bookings->client_id;
		$loyalty_points->credit = $refund_points;
		$loyalty_points->balance =$curr_bal + $refund_points;
		$loyalty_points->debit = 0;
		$loyalty_points->credit_debit_loyalty_points();
	}
	
		
	$order_info->order_id = $apt_bookings->order_id;
	$order_info->readOne_by_order_id();		
	$client_name = ucwords($order_info->client_name);
	$client_email = $order_info->client_email;
	$client_phone = $order_info->client_phone;
	$client_personal_info  = unserialize($order_info->client_personal_info);
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
	
	
	$booking_details = "<br/><span><strong>".__('For','apt')."</strong>: ".stripslashes_deep($service->service_title)."</span><br/><br/>
						<span><strong>".__('With','apt')."</strong>: ".ucwords(stripslashes_deep($staffinfo[0]['staff_name']))."</span><br/><br/>
						<span><strong>".__('On','apt')."</strong>: ".date_i18n(get_option('date_format'),strtotime($apt_bookings->booking_datetime))."</span><br/><br/>
						<span><strong>".__('At','apt')."</strong>: ".date_i18n(get_option('time_format'),strtotime($apt_bookings->booking_datetime))."</span><br/>";						


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
		if($client_gender == "M"){
			$gender_display = "Male";
		}else{
			$gender_display = "Female";
		}
		$client_full_detail .="<span><strong>".__('Gender','apt')."</strong>: ".$gender_display."</span><br/><br/>";
	}
	if($client_dateofbirth!=''){
		$client_full_detail .="<span><strong>".__('DOB','apt')."</strong>: ".$client_dateofbirth."</span><br/><br/>";
	}
	if($client_age!=''){
		$client_full_detail .="<span><strong>".__('Age','apt')."</strong>: ".$client_age."</span><br/><br/>";
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
	
    /* Confirm Link */        
	$appoint_confirm_link_sp =plugins_url('',dirname(__FILE__))."/lib/booking_crc_email.php?".base64_encode(base64_encode($apt_bookings->order_id)."-confirm");  
	/* Reject Link */     
	$appoint_reject_link_sp =plugins_url('',dirname(__FILE__))."/lib/booking_crc_email.php?".base64_encode(base64_encode($apt_bookings->order_id)."-reject");
	/* Client Cancel Link */
	$appoint_cancel_link_client =plugins_url('',dirname(__FILE__))."/lib/booking_crc_email.php?".base64_encode(base64_encode($apt_bookings->order_id)."-clientcancel");
	
	if(get_option('appointment_auto_confirm_appointment'.'_'.$bwid)=='Y' ){
	$confirm_link_sp='';
	}else{
	$confirm_link_sp="<a style='text-decoration: none;color: #FFF;background-color: #348eda;	border: solid #348eda;border-width: 10px 30px; line-height: 1;	font-weight: bold;margin-right: 10px;text-align: center;cursor: pointer;display: inline-block; border-radius: 10px;'  id='email-btn-primary' class='email-btn-primary' href='".$appoint_confirm_link_sp."-".base64_encode($apt_bookings->provider_id."+".$booking_id)."' >".__('Confirm','apt')."</a>";     
	}		
	$reject_link_sp ="<a style='text-decoration: none;color: #FFF;background-color: red;border: solid red;border-width: 10px 30px;line-height: 1;font-weight: bold;margin-right: 10px;text-align: center;cursor: pointer;display: inline-block;border-radius: 10px;'  id='email-btn-secondary' class='email-btn-secondary' href='".$appoint_reject_link_sp."-".base64_encode($apt_bookings->provider_id."+".$booking_id)."' >".__('Reject','apt')."</a>";

	$cancel_link_client ="<a style='text-decoration: none;color: #FFF;background-color: red;border: solid red;border-width: 10px 30px;line-height: 1;font-weight: bold;margin-right: 10px;text-align: center;cursor: pointer;display: inline-block;border-radius: 10px;'  id='email-btn-secondary' class='email-btn-secondary' href='".$appoint_cancel_link_client."-".base64_encode($apt_bookings->client_id."+".$booking_id)."' >".__('Cancel','apt')."</a>";



	
		
	$search = array('{{company_name}}','{{service_name}}','{{service_provider_name}}','{{customer_name}}','{{client_address}}','{{client_city}}','{{client_zip}}','{{client_phone}}','{{client_email}}','{{client_gender}}','{{client_dateofbirth}}','{{client_age}}','{{client_skype}}','{{client_state}}','{{appointment_id}}','{{appointment_date}}','{{appointment_time}}','{{net_amount}}','{{discount_amount}}','{{payment_method}}','{{taxes_amount}}','{{partial_amount}}','{{provider_email}}','{{provider_phone}}','{{provider_appointment_reject_link}}','{{provider_appointment_confirm_link}}','{{appointment_reject_reason}}','{{appointment_cancel_reason}}','{{appointment_confirm_note}}','{{appointment_reschedle_note}}','{{appointment_previous_date}}','{{appointment_previous_time}}','{{admin_manager_name}}','{{client_appointment_cancel_link}}','{{booking_details}}','{{appoinment_client_detail}}','{{addons_details}}','{{location_title}}','{{location_description}}','{{location_email}}','{{location_phone}}','{{location_address}}','{{location_city}}','{{location_state}}','{{location_zip}}','{{location_country}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');      
			
				
	$replace_with = array($company_name,stripslashes_deep($service->service_title),ucwords(stripslashes_deep($staffinfo[0]['staff_name'])),$client_name,$client_address,$client_city,$client_zip,$client_phone,$client_email,$client_gender,$client_dateofbirth,$client_age,$client_skype,$client_state,$booking_id,date_i18n(get_option('date_format'),strtotime($booking_datetime)),date_i18n(get_option('time_format'),strtotime($booking_datetime)),$payments->net_total,$payments->discount,$payments->payment_method,$payments->taxes,$payments->partial,$staffinfo[0]['email'],$staffinfo[0]['phone'],$reject_link_sp,$confirm_link_sp,$booking_reject_reason,$booking_cancel_reason,$booking_confirm_note,'','','',$sender_name,$cancel_link_client,$booking_details,$client_full_detail,$addons_detail,$location_title,$location_description,$location_email,$location_phone,$location_address,$location_city,$location_state,$location_zip,$location_country,$company_address,$company_city,$company_state,$company_zip,$company_country,$company_phone,$company_email,$company_logo); 
		
	
	/******************* Send Email Notification *********************/
	
	/* Send email to Client when booking is complete */	
	if(get_option('appointment_client_email_notification_status'.'_'.$bwid)=='E'){	
		$apt_clientemail_templates = new appointment_email_template();
		$msg_template = $apt_clientemail_templates->email_parent_template;	
		$apt_clientemail_templates->email_template_name = $booking_method."C";
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
		$apt_staffemail_templates->email_template_name = $booking_method."S";  
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
	if(get_option('appointment_admin_email_notification_status'.'_'.$bwid)=='E'){
		$apt_adminemail_templates = new appointment_email_template();
		$msg_template = $apt_adminemail_templates->email_parent_template;
		$apt_adminemail_templates->email_template_name = $booking_method."A";  		
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
		$status = wp_mail(get_option('appointment_email_sender_address'.'_'.$bwid),$email_subject,$email_admin_message,$headers);	
	}
	/* Send Email Notification End Here */
	
	/******************* Send SMS Notification *********************/
	if(get_option("appointment_sms_reminder_status".'_'.$bwid) == "E"){			
	
		/*******************  SMS sending code via Plivo  **************/
		if(get_option('appointment_sms_noti_plivo'.'_'.$bwid)=="E"){
			
			include_once(dirname(dirname(dirname(__FILE__))).'/objects/plivo.php');
			$plivo_sender_number = get_option('appointment_plivo_number'.'_'.$bwid);	
			$auth_sid = get_option('appointment_plivo_sid'.'_'.$bwid);
			$auth_token = get_option('appointment_plivo_auth_token'.'_'.$bwid);	
			/* Send SMS To Client */
			if(get_option('appointment_plivo_client_sms_notification_status'.'_'.$bwid) == "E"){				
				$p_client = new Plivo\RestAPI($auth_sid, $auth_token, '', '');					
				$template = $obj_sms_template->gettemplate_sms("C",'e',$booking_method.'C');					
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
			if(get_option('appointment_plivo_service_provider_sms_notification_status'.'_'.$bwid) == "E"){		
				$p_staff = new Plivo\RestAPI($auth_id, $auth_token, '', '');					
				$template = $obj_sms_template->gettemplate_sms("SP",'e',$booking_method.'S');					
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
			if(get_option('appointment_plivo_admin_sms_notification_status'.'_'.$bwid) == "E"){					
				$p_admin = new Plivo\RestAPI($auth_id, $auth_token, '', '');					
				$template = $obj_sms_template->gettemplate_sms("AM",'e',$booking_method.'A');					
				if($template[0]->sms_template_status == "e" && get_option('appointment_plivo_admin_phone_no'.'_'.$bwid)!=''){
					if($template[0]->sms_message == ""){
						$message = strip_tags($template[0]->default_message);
					}else{
						$message = strip_tags($template[0]->sms_message);
					}						
					$admin_sms_body = str_replace($search,$replace_with,$message);
					$adminparams = array(
					'src' => $plivo_sender_number,
					'dst' => get_option('appointment_plivo_ccode'.'_'.$bwid).get_option('appointment_plivo_admin_phone_no'.'_'.$bwid),
					'text' => $admin_sms_body,
					'method' => 'POST'
					);
					$response = $p_admin->send_message($adminparams);
				} 
			}			
			
		}
		/* Plivo SMS Sending End Here */
		
		
		/*******************  SMS sending code via Twilio  **************/
		if(get_option('appointment_sms_noti_twilio'.'_'.$bwid)=="E"){
		   include_once(dirname(dirname(dirname(__FILE__))).'/assets/twilio/Services/Twilio.php');
		   $twillio_sender_number = get_option('appointment_twilio_number'.'_'.$bwid);
		   $AccountSid = get_option('appointment_twilio_sid'.'_'.$bwid);
		   $AuthToken =  get_option('appointment_twilio_auth_token'.'_'.$bwid); 
		   /* Send SMS To Client */
		   if(get_option('appointment_twilio_client_sms_notification_status'.'_'.$bwid) == "E"){				   
			$twilliosms_client = new Services_Twilio($AccountSid, $AuthToken);
			$template = $obj_sms_template->gettemplate_sms("C",'e',$booking_method.'C');					
				if($template[0]->sms_template_status == "e" && $client_phone!=''){
					if($template[0]->sms_message == ""){
						$message = strip_tags($template[0]->default_message);
					}else{
						$message = strip_tags($template[0]->sms_message);
					}
					$client_sms_body = str_replace($search,$replace_with,$message);					
					$message = $twilliosms_client->account->messages->create(array(
					 "From" => $twillio_sender_number,
					 "To" => $client_ccode.$client_phone,
					 "Body" => $client_sms_body));
				}		
		   }
		   /* Send SMS To Staff */
		   if(get_option('appointment_twilio_service_provider_sms_notification_status'.'_'.$bwid) == "E"){		   
			$twilliosms_staff = new Services_Twilio($AccountSid, $AuthToken);
			$template = $obj_sms_template->gettemplate_sms("SP",'e',$booking_method.'S');					
				if($template[0]->sms_template_status == "e" && $staffinfo[0]['phone']!=''){
					if($template[0]->sms_message == ""){
						$message = strip_tags($template[0]->default_message);
					}else{
						$message = strip_tags($template[0]->sms_message);
					}
					$staff_sms_body = str_replace($search,$replace_with,$message);					
					$message = $twilliosms_staff->account->messages->create(array(
					 "From" => $twillio_sender_number,
					 "To" => $staffinfo[0]['phone'],
					 "Body" => $staff_sms_body));
				}		
		   }
		   /* Send SMS To Admin */
		   if(get_option('appointment_twilio_admin_sms_notification_status'.'_'.$bwid) == "E"){		   
			$twilliosms_admin = new Services_Twilio($AccountSid, $AuthToken);
			$template = $obj_sms_template->gettemplate_sms("AM",'e',$booking_method.'A');					
				if($template[0]->sms_template_status == "e" && get_option('appointment_twilio_admin_phone_no'.'_'.$bwid)!=''){
					if($template[0]->sms_message == ""){
						$message = strip_tags($template[0]->default_message);
					}else{
						$message = strip_tags($template[0]->sms_message);
					}
					$staff_sms_body = str_replace($search,$replace_with,$message);					
					$message = $twilliosms_admin->account->messages->create(array(
					 "From" => $twillio_sender_number,
					 "To" => get_option('appointment_twilio_ccode'.'_'.$bwid).get_option('appointment_twilio_admin_phone_no'.'_'.$bwid),
					 "Body" => $staff_sms_body));
					
				}		
		   }				
		}
		/* Twilio SMS Sending End Here */
		
		/*******************  SMS sending code via Neximo  **************/
		if(get_option('appointment_sms_noti_nexmo'.'_'.$bwid)=="E"){
		  include_once(dirname(dirname(dirname(__FILE__))).'/objects/class_nexmo.php');
		  $nexmo_client = new appointment_nexmo();
		  $nexmo_client->appointment_nexmo_apikey = get_option('appointment_nexmo_apikey'.'_'.$bwid);
		  $nexmo_client->appointment_nexmo_api_secret = get_option('appointment_nexmo_api_secret'.'_'.$bwid);
		  $nexmo_client->appointment_nexmo_form = get_option('appointment_nexmo_form'.'_'.$bwid);
		  /* Send SMS To Client */
		  if(get_option('appointment_nexmo_send_sms_client_status'.'_'.$bwid) == "E"){
			$template = $obj_sms_template->gettemplate_sms("C",'e',$booking_method.'C');					
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
		  if(get_option('appointment_nexmo_send_sms_sp_status'.'_'.$bwid) == "E"){
			$template = $obj_sms_template->gettemplate_sms("SP",'e',$booking_method.'S');					
				if($template[0]->sms_template_status == "e" && $staffinfo[0]['phone']!=''){
					if($template[0]->sms_message == ""){
						$message = strip_tags($template[0]->default_message);
					}else{
						$message = strip_tags($template[0]->sms_message);
					}
					$staff_sms_body = str_replace($search,$replace_with,$message);
					$nexmo_client->send_nexmo_sms($staffinfo[0]['phone'],$client_sms_body);
				}
		  }
		  /* Send SMS To Admin */
		  if(get_option('appointment_nexmo_send_sms_admin_status'.'_'.$bwid) == "E"){
			$template = $obj_sms_template->gettemplate_sms("AM",'e',$booking_method.'A');					
				if($template[0]->sms_template_status == "e" && get_option('appointment_nexmo_admin_phone_no')!=''){
					if($template[0]->sms_message == ""){
						$message = strip_tags($template[0]->default_message);
					}else{
						$message = strip_tags($template[0]->sms_message);
					}
					$staff_sms_body = str_replace($search,$replace_with,$message);
					$nexmo_client->send_nexmo_sms(get_option('appointment_nexmo_ccode'.'_'.$bwid).get_option('appointment_nexmo_admin_phone_no'.'_'.$bwid),$client_sms_body);
				}
		  }
		  
		}
		/* Nexmo SMS Sending End Here */
		/* Textlocal SMS sending Start */
		if(get_option('appointment_sms_noti_textlocal'.'_'.$bwid)=="E"){
			
			$textlocal_api_key = get_option('appointment_textlocal_apikey'.'_'.$bwid);
			$textlocal_sender = get_option('appointment_textlocal_sender'.'_'.$bwid);
			$client_phone = get_option('appointment_textlocal_admin_phone_no'.'_'.$bwid);
		  /* Send SMS To Client */
		  if(get_option('appointment_textlocal_client_sms_notification_status'.'_'.$bwid) == "E"){
			 
			$template = $obj_sms_template->gettemplate_sms("C",'e',$booking_method.'C');
				
				if($template[0]->sms_template_status == "e" && $client_phone!=''){
					if($template[0]->sms_message == "")
					{
						$message = strip_tags($template[0]->default_message);
					}
					else
					{
						$message = strip_tags($template[0]->sms_message);
					}
			}
			$message = str_replace($searcharray,$replacearray,$message);
			$data = 'apikey=' . $textlocal_api_key . '&phone=' . $client_phone . "&sender=" . $textlocal_sender . "&message=" . $message;
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			curl_close($ch);
		  }
		  /* Send SMS To Staff 
		  if(get_option('appointment_nexmo_send_sms_sp_status'.'_'.$bwid) == "E"){
			$template = $obj_sms_template->gettemplate_sms("SP",'e',$booking_method.'S');					
				if($template[0]->sms_template_status == "e" && $staffinfo[0]['phone']!=''){
					if($template[0]->sms_message == ""){
						$message = strip_tags($template[0]->default_message);
					}else{
						$message = strip_tags($template[0]->sms_message);
					}
					$staff_sms_body = str_replace($search,$replace_with,$message);
					$nexmo_client->send_nexmo_sms($staffinfo[0]['phone'],$client_sms_body);
				}
		  }*/
		  /* Send SMS To Admin */
		  if(get_option('appointment_textlocal_admin_sms_notification_status'.'_'.$bwid) == "E"){
			 
			$textlocal_api_key = get_option('appointment_textlocal_apikey'.'_'.$bwid);
			$textlocal_sender = get_option('appointment_textlocal_sender'.'_'.$bwid);
			$client_phone = get_option('appointment_textlocal_ccode'.'_'.$bwid).get_option('appointment_textlocal_admin_phone_no'.'_'.$bwid);
			$template = $obj_sms_template->gettemplate_sms("AM",'e',$booking_method.'A');					
				if($template[0]->sms_template_status == "e" && $client_phone!=''){
				if($template[0]->sms_message == "")
					{
						$message = strip_tags($template[0]->default_message);
					}
				else
					{
						$message = strip_tags($template[0]->sms_message);
					}
					
					$data = 'apikey=' . $textlocal_api_key . '&phone=' . $client_phone . "&sender=" . $textlocal_sender . "&message=" . $message;
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$result = curl_exec($ch);
					curl_close($ch);
					
				}
		  }
		  
		}
	}
	/* Send SMS Notification End Here */	
}
/** Get Service Chart Analytics Info **/
if(isset($_POST['general_ajax_action']) && $_POST['general_ajax_action']=='view_chart_analytics'){	
	global $current_user;
	$current_user = wp_get_current_user();
	$info = get_userdata( $current_user->ID );
	$service->business_owner_id = $_POST['bwid'];
	/* Coupon Detail */
	$coupons->location_id = $_SESSION['apt_location'];
	$coupons->business_owner_id = $_POST['bwid'];
	$couponsinfo = $coupons->readAll();
	if(isset($info->caps['administrator']) || isset($info->caps['business_manager'])){
	/* Service Detail */
	$service->location_id= $_SESSION['apt_location'];
	$servicesInfo =  $service->readAll();
	/* Staff Detail */
	$staff->location_id =$_SESSION['apt_location'];
	$staffsinfo = $staff->readAll_with_disables();   
	
	}else{
	/* Service Detail */
	$service->provider_id=  $current_user->ID;
	$servicesInfo =  $service->readall_services_of_provider();
	/* Staff Detail */
	$staff->id =  $current_user->ID;
	$staffsinfo = $staff->readOne();   
	}
	
	$chart_data_array = array();
	if(isset($_POST['method']) && $_POST['method']=='service'){
		foreach($servicesInfo as $serviceInfo){
			$apt_bookings->service_id = $serviceInfo->id;
			$totalbookings = $apt_bookings->readall_bookings_by_service_id();
			if($totalbookings>0){
				$chart_data_array[]=array(
						"value"=>$totalbookings,
						"color"=>"$serviceInfo->color_tag",
						"label"=>"$serviceInfo->service_title"
				);
			}
		}
	}
	if(isset($_POST['method']) && $_POST['method']=='provider'){
		foreach($staffsinfo as $staff_info){
		$apt_bookings->provider_id = $staff_info['id'];
		$totalbookings = $apt_bookings->readall_bookings_by_provider_id();
			if($totalbookings>0){
				$chart_data_array[]=array(
						"value"=>$totalbookings,
						"color"=>"#".mt_rand(100000, 999999),
						"label"=>ucfirst($staff_info['staff_name'])
					);
			}
		}
	}
	if(isset($_POST['method']) && $_POST['method']=='coupon'){
		foreach($couponsinfo as $couponinfo){
			if($couponinfo->coupon_used>0){
				$chart_data_array[]=array(
					"value"=>"$couponinfo->coupon_used",
					"color"=>"#".mt_rand(100000, 999999),
					"label"=>"$couponinfo->coupon_code"
				);
			}	
		}	
	}
	$json_chart_data =  json_encode($chart_data_array);	
		echo $json_chart_data;die();
}
/** Get Notification Count **/
if(isset($_POST['general_ajax_action']) && $_POST['general_ajax_action']=='get_notification_count'){
		if(isset($_SESSION['apt_location'])){
		$apt_bookings->location_id = $_SESSION['apt_location'];
			if($apt_bookings->get_notifications_count()>0){
				echo '<span id="apt-notification-top" class="get_notification_rem">'.$apt_bookings->get_notifications_count().'</span>';
			}
		}
}	
/** Get Notification Bookings **/
if(isset($_POST['general_ajax_action']) && $_POST['general_ajax_action']=='get_notification_bookings'){
		$apt_bookings->location_id = $_SESSION['apt_location'];
		$notificationbookings = $apt_bookings->get_notifications_bookings();


		function time_elapsed_string($datetime, $full = false) {
			$now = new DateTime;
			$ago = new DateTime($datetime);
			$diff = $now->diff($ago);

			$diff->w = floor($diff->d / 7);
			$diff->d -= $diff->w * 7;

			$string = array(
				'y' => 'year',
				'm' => 'month',
				'w' => 'week',
				'd' => 'day',
				'h' => 'hour',
				'i' => 'minute',
				's' => 'second',
			);
			foreach ($string as $k => &$v) {
				if ($diff->$k) {
					$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
				} else {
					unset($string[$k]);
				}
			}

			if (!$full) $string = array_slice($string, 0, 1);
			return $string ? implode(', ', $string) . ' ago' : 'just now';
		}
		if(sizeof($notificationbookings)>0){			
			foreach($notificationbookings as $notificationbooking){								
					$service->id= $notificationbooking->service_id;
					$service->readone();
					$service_title=stripslashes_deep($service->service_title);
					$servicedurationstrinng = '';
					if(floor($service->duration/60)!=0){ $servicedurationstrinng .= floor($service->duration/60); $servicedurationstrinng .= __(" Hrs","apt"); } 
					if($service->duration%60 !=0){  $servicedurationstrinng .= $service->duration%60; $servicedurationstrinng .= __(" Mins","apt"); }
					$staff->id=$notificationbooking->provider_id;
					$staff_info = $staff->readOne();   


					$provider_name = (count($staff_info[0]) > 0)?ucfirst($staff_info[0]['staff_name']):'';				
					$clients->order_id=$notificationbooking->order_id;
					$client_info = $clients->get_client_info_by_order_id();
					$clientname= $client_info[0]->client_name;
					
					
					/* $seconds = strtotime(date_i18n('Y-m-d H:i:s')) - strtotime(date_i18n('Y-m-d H:i:s',strtotime($notificationbooking->lastmodify)));
					$minutes = round($seconds / 60 );
					$hours = round($seconds / 3600);
					$days = round($seconds / 86400 );
					$weeks = round($seconds / 604800);
					$months = round($seconds / 2600640 );
					$years = round($seconds / 31207680 ); */
					/* Seconds */
					/* if($seconds <= 60){
						$timeago = $seconds.__(" seconds ago","apt");
					} */
					/* Minutes */
					/* else if($minutes <60){
						$timeago = $minutes.__(" minutes ago","apt");
					} */
					/* Hours */
					/* else if($hours <=24){
						if($hours==1 || $minutes==60){
							$timeago = __("an hour ago","apt");
						}else{
							$timeago = $hours.__(" hours ago","apt");
						}
					} */
					/* Days */
					/* else if($days <= 7){
						if($days==1){
							$timeago = __("yesterday","apt");
						}else{
							$timeago = $days.__(" days ago","apt");
						}
					} */
					/* Weeks */
					/* else if($weeks <= 4.3){
						if($weeks==1){
							$timeago = __("a week ago","apt");
						}else{
							$timeago = $weeks.__(" weeks ago","apt");
						}
					} */
					/* Months */
					/* else if($months <=12){
						if($months==1){
							$timeago = __("a months ago","apt");
						}else{
							$timeago = $months.__(" months ago","apt");
						}
					} */
					/* Years */
					/* else{
						if($years==1){
							$timeago = __("1 years ago","apt");
						}else{
							$timeago = $years.__(" years ago","apt");
						}
					} */ ?>					
					<li class="apt-today-list" id="apt_notification<?php echo $notificationbooking->id;?>" data-bookingid = '<?php echo $notificationbooking->id;?>' data-toggle="modal" data-target="#booking-details">
								<div class="list-inner">
						<span class="booking-text"><?php if($notificationbooking->booking_status=='A' || $notificationbooking->booking_status==''){
							echo '<span class="apt-label btn-info br-2">'.__('Active','apt').'</span>';
							}elseif($notificationbooking->booking_status=='C'){
								echo '<span class="apt-label btn-success br-2">'.__('Confirm','apt').'</span>';
							}elseif($notificationbooking->booking_status=='R'){
								echo '<span class="apt-label btn-danger br-2 ">'.__('Reject','apt').'</span>';
							}elseif($notificationbooking->booking_status=='RS'){
								echo '<span class="apt-label btn-primary br-2">'.__('Rescheduled','apt').'</span>';
							}elseif($notificationbooking->booking_status=='CC'){
								echo '<span class="apt-label btn-default br-2">'.__('Cancel By Client','apt').'</span>';
							}elseif($notificationbooking->booking_status=='CS'){
								echo '<span class="apt-label btn-default br-2">'.__('Cancel By Service Provider','apt').'</span>';
							}elseif($notificationbooking->booking_status=='CO'){
								echo '<span class="apt-label btn-success br-2">'.__('Completed','apt').'</span>';
							}else{
								echo '<span class="apt-label btn-danger br-2">'.__('Mark As No Show','apt').'</span>';
							} ?><span class="apt-noti-text">  <?php echo $clientname;?> <?php echo __('for a','apt');?> <?php echo __(stripslashes_deep($service_title),"apt");?> <?php echo __('on','apt');?> <?php echo date_i18n('d-M-Y',strtotime($notificationbooking->booking_datetime)); ?> <?php echo __('@','apt');?> <?php echo date_i18n(get_option('time_format'),strtotime($notificationbooking->booking_datetime)); ?> <?php echo __('with','apt');?><b> <?php echo __(stripslashes_deep($provider_name),"apt");?></b></span></span>
						<span class="booking-time">
						<?php echo time_elapsed_string($notificationbooking->lastmodify); //echo $timeago;?> </span><a data-booking_id="<?php echo $notificationbooking->id;?>" class="pull-right apt-mark-read apt_unread_notification" href="javascript:void(0);"><?php echo __("mark as read","apt");?></a> 
						</div>
					</li>					
				<?php									
			}			
		}else{ ?>
			<div class="list-inner apt-no-notification">
				<i class="fa fa-clock-o fa-3x"></i>
				<div class="booking-text">
					<h4><?php echo __('No notification found','apt');?></h4>
				</div>
			
			</div>
			<?php 		
		}
}	
/** Mark Notification As Readed **/
if(isset($_POST['general_ajax_action'],$_POST['booking_id']) && $_POST['general_ajax_action']=='remove_notifications_bookings' && $_POST['booking_id']!=''){
		$apt_bookings->location_id = $_SESSION['apt_location'];
		$apt_bookings->booking_id = $_POST['booking_id'];
		$apt_bookings->remove_notifications_bookings();
}
/** Client Area Order Bookings Detail **/
if(isset($_POST['general_ajax_action'],$_POST['order_id'],$_POST['client_id']) && $_POST['general_ajax_action']=='get_client_order_bookings' && $_POST['order_id']!='' && $_POST['client_id']!=''){
		$apt_bookings->client_id = $_POST['client_id'];
		$apt_bookings->order_id = $_POST['order_id'];
		
		$clientorderbookings = $apt_bookings->get_client_bookings_by_order_id();
		
		foreach($clientorderbookings as $clientorderbooking){								
					
					$service->id= $clientorderbooking->service_id;
					$service->service_id = $service->id;
					$bwid = $service->get_businessowner_by_service_id();
					$service->readone();
					$service_title=stripslashes_deep($service->service_title);
					$servicedurationstrinng = '';
					if(floor($service->duration/60)!=0){ $servicedurationstrinng .= floor($service->duration/60); $servicedurationstrinng .= __(" Hrs","apt"); } 
					if($service->duration%60 !=0){  $servicedurationstrinng .= $service->duration%60; $servicedurationstrinng .= __(" Mins","apt"); }
					$staff->id=$clientorderbooking->provider_id;
					$staff_info = $staff->readOne();   
					$provider_name = ucfirst($staff_info[0]['staff_name']);				
					
					if($clientorderbooking->booking_status=='A' || $clientorderbooking->booking_status==''){
						$bookingstatus =  __('Active','apt');
						$statusNote = '-';
					}elseif($clientorderbooking->booking_status=='C'){
						$bookingstatus = __("Confirm",'apt');
						$statusNote = $clientorderbooking->confirm_note;
					}elseif($clientorderbooking->booking_status=='R'){
						$bookingstatus = __("Reject",'apt');
						$statusNote = $clientorderbooking->reject_reason;
					}elseif($clientorderbooking->booking_status=='RS'){
						$bookingstatus = __("Rescheduled",'apt');
						$statusNote = $clientorderbooking->reschedule_note;
					}elseif($clientorderbooking->booking_status=='CC'){
						$bookingstatus =  __("Cancel By Client",'apt');
						$statusNote = $clientorderbooking->cancel_reason;
					}elseif($clientorderbooking->booking_status=='CS'){
						$bookingstatus = __("Cancel By Service Provider",'apt');
						$statusNote = $clientorderbooking->cancel_reason;
					}elseif($clientorderbooking->booking_status=='CO'){
						$bookingstatus =  __("Completed",'apt');
						$statusNote = '-';
					}else{					
						$bookingstatus =  __("Mark As No Show",'apt');
						$statusNote = '-';
					}
					
					/* Cancelation Buffer Calculation */
					if(strtotime(date_i18n('Y-m-d',strtotime($clientorderbooking->booking_datetime)))>=strtotime(date_i18n('Y-m-d'))){
					$booking_dt = strtotime(date_i18n('Y-m-d H:i:s',strtotime($clientorderbooking->booking_datetime)));
					}else{
					$booking_dt = strtotime(date_i18n('Y-m-d H:i:s'));
					}
					$curr_dt = strtotime(date_i18n('Y-m-d H:i:s'));
					 $remaining_mins  = round(abs($booking_dt - $curr_dt)/60);
					//$remaining_mins   = round($diff / 60);
										
					
			?>
			
			
			
			
				<tr>				
					<td><?php echo $clientorderbooking->order_id;?></td>
					<td><?php echo $provider_name;?></td>
					<td><?php echo $service_title;?></td>
					<td><?php echo date_i18n(get_option('appointment_datepicker_format'.'_'.$bwid),strtotime($clientorderbooking->booking_datetime)); ?> <?php echo date_i18n(get_option('time_format'),strtotime($clientorderbooking->booking_datetime)); ?></td>
					<td><?php echo $bookingstatus;?></td>
					<td><?php echo $statusNote;?></td>
					<td>
					<?php if($remaining_mins > get_option('appointment_reschedule_buffer_time'.'_'.$bwid) ){ ?>
					<a href="javascript:void(0);" data-bookingid="<?php echo $clientorderbooking->id;?>" data-toggle="modal" data-target="#edit-booking-details-view" class=" btn btn-success apt-today-list" title="<?php echo __("Reschedule","apt"); ?>"><i class="fa fa-repeat"></i></a><?php } ?>
					
					<?php if($remaining_mins > get_option('appointment_cancellation_buffer_time'.'_'.$bwid)  && ($clientorderbooking->booking_status=='C' || $clientorderbooking->booking_status=='A')){ ?>
					<a id="cancel-appointment-cal-popup<?php echo $clientorderbooking->id;?>" class="btn apt-small-btn btn-danger apt_client_cancel_appointmentpopup" rel="popover" data-placement='bottom' title="<?php echo __("Cancel reason?","apt");?>"><i class="fa fa-ban"></i></a>
					
					<div id="popover-cancel-appointment-cal-popup<?php echo $clientorderbooking->id;?>" style="display: none;">
						<div class="arrow"></div>
						<table class="form-horizontal" cellspacing="0">
							<tbody>
								<tr>
									<td><textarea class="form-control" id="apt_booking_cancelnote<?php echo $clientorderbooking->id;?>" name="" placeholder="<?php echo __("Appointment Cancel Reason","apt");?>" required="required" ></textarea></td>
								</tr>
								<tr>
									<td>
										<a href="javascript:void(0);" id="apt_booking_cancel" data-booking_id="<?php echo $clientorderbooking->id;?>" data-method='CC'  data-sp='Y' value="Cancel By Client" class="btn btn-danger btn-sm apt_crc_appointment" type="submit"><?php echo __("Ok","apt");?></a>
										<a class="btn btn-default btn-sm apt_cancel_clientcancel" href="javascript:void(0)"><?php echo __("Cancel","apt");?></a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>	<?php } ?>	
					<?php if(get_option('appointment_reviews_status'.'_'.$bwid)=='E'){ 
						/* Get Booking Review */
						$reviews->booking_id = $clientorderbooking->id;
						$reviewinfo = $reviews->readOne_by_booking_id();	?>
					<a id="client-review-popup<?php echo $clientorderbooking->id;?>" class="btn btn-info apt-add-review-client-btn" rel="popover" data-placement='bottom'  title="<?php echo __("Add Review","apt");?>"><i class="fa fa-star"></i></a>
						<div id="popover-client-review-popup<?php echo $clientorderbooking->id;?>" style="display: none;">
						<div class="arrow"></div>
						<table class="form-horizontal" cellspacing="0">
							<tbody>
								<tr>
									<td>
										<fieldset class="rating">
										  <input <?php if(isset($reviewinfo[0]->rating) && $reviewinfo[0]->rating=='5'){ echo "checked='checked'";} ?> type="radio" id="star5<?php echo $clientorderbooking->id;?>" name="appointment_rating<?php echo $clientorderbooking->id;?>" value="5" /><label class="full" for="star5<?php echo $clientorderbooking->id;?>" title="<?php echo __("Awesome - 5 stars","apt");?>"></label>
										  <input <?php if(isset($reviewinfo[0]->rating) && $reviewinfo[0]->rating=='4.5'){ echo "checked='checked'";} ?> type="radio" id="star4half<?php echo $clientorderbooking->id;?>" name="appointment_rating<?php echo $clientorderbooking->id;?>" value="4.5" /><label class="half" for="star4half<?php echo $clientorderbooking->id;?>" title="<?php echo __("Pretty good - 4.5 stars","apt");?>"></label>
										  <input <?php if(isset($reviewinfo[0]->rating) && $reviewinfo[0]->rating=='4'){ echo "checked='checked'";} ?> type="radio" id="star4<?php echo $clientorderbooking->id;?>" name="appointment_rating<?php echo $clientorderbooking->id;?>" value="4" /><label class="full" for="star4<?php echo $clientorderbooking->id;?>" title="<?php echo __("Pretty good - 4 stars","apt");?>"></label>
										  <input <?php if(isset($reviewinfo[0]->rating) && $reviewinfo[0]->rating=='3.5'){ echo "checked='checked'";} ?> type="radio" id="star3half<?php echo $clientorderbooking->id;?>" name="appointment_rating<?php echo $clientorderbooking->id;?>" value="3.5" /><label class="half" for="star3half<?php echo $clientorderbooking->id;?>" title="<?php echo __("Meh - 3.5 stars","apt");?>"></label>
										  <input <?php if(isset($reviewinfo[0]->rating) && $reviewinfo[0]->rating=='3'){ echo "checked='checked'";} ?> type="radio" id="star3<?php echo $clientorderbooking->id;?>" name="appointment_rating<?php echo $clientorderbooking->id;?>" value="3" /><label class="full" for="star3<?php echo $clientorderbooking->id;?>" title="<?php echo __("Meh - 3 stars","apt");?>"></label>
										  <input <?php if(isset($reviewinfo[0]->rating) && $reviewinfo[0]->rating=='2.5'){ echo "checked='checked'";} ?> type="radio" id="star2half<?php echo $clientorderbooking->id;?>" name="appointment_rating<?php echo $clientorderbooking->id;?>" value="2.5" /><label class="half" for="star2half<?php echo $clientorderbooking->id;?>" title="<?php echo __("Kinda bad - 2.5 stars","apt");?>"></label>
										  <input <?php if(isset($reviewinfo[0]->rating) && $reviewinfo[0]->rating=='2'){ echo "checked='checked'";} ?> type="radio" id="star2<?php echo $clientorderbooking->id;?>" name="appointment_rating<?php echo $clientorderbooking->id;?>" value="2" /><label class="full" for="star2<?php echo $clientorderbooking->id;?>" title="<?php echo __("Kinda bad - 2 stars","apt");?>"></label>
										  <input <?php if(isset($reviewinfo[0]->rating) && $reviewinfo[0]->rating=='1.5'){ echo "checked='checked'";} ?> type="radio" id="star1half<?php echo $clientorderbooking->id;?>" name="appointment_rating<?php echo $clientorderbooking->id;?>" value="1.5" /><label class="half" for="star1half<?php echo $clientorderbooking->id;?>" title="<?php echo __("Meh - 1.5 stars","apt");?>"></label>
										  <input <?php if(isset($reviewinfo[0]->rating) && $reviewinfo[0]->rating=='1'){ echo "checked='checked'";} ?> type="radio" id="star1<?php echo $clientorderbooking->id;?>" name="appointment_rating<?php echo $clientorderbooking->id;?>" value="1" /><label class="full" for="star1<?php echo $clientorderbooking->id;?>" title="<?php echo __("Sucks big time - 1 star","apt");?>"></label>
										  <input <?php if(isset($reviewinfo[0]->rating) && $reviewinfo[0]->rating=='0.5'){ echo "checked='checked'";} ?> type="radio" id="starhalf<?php echo $clientorderbooking->id;?>" name="appointment_rating<?php echo $clientorderbooking->id;?>" value="0.5" /><label class="half" for="starhalf<?php echo $clientorderbooking->id;?>" title="<?php echo __("Sucks big time - 0.5 stars","apt");?>"></label>
										 
										</fieldset>
									</td>
								</tr>
								<tr>
									<td>
										<label><?php echo __("Write Review","apt");?></label>
										<textarea id="appointment_review_desc<?php echo $clientorderbooking->id;?>" class="review-textarea form-control"><?php if(isset($reviewinfo[0]->description)){ echo $reviewinfo[0]->description;} ?></textarea>
									</td>
								</tr>
								<tr>
									<td>
										<a href="javascript:void(0);" id="apt_booking_submitreview" data-booking_id="<?php echo $clientorderbooking->id;?>" data-method='<?php if(isset($reviewinfo[0]->description,$reviewinfo[0]->rating)){echo "U";}else{ echo "C";} ?>'  data-pid="<?php echo $clientorderbooking->provider_id;?>"  data-cid="<?php echo $clientorderbooking->client_id;?>" data-review_id="<?php if(isset($reviewinfo[0]->description,$reviewinfo[0]->rating)){echo $reviewinfo[0]->id;}else{ echo "0";} ?>"  class="btn btn-success"><?php if(isset($reviewinfo[0]->description,$reviewinfo[0]->rating)){ echo __("Update Review","apt"); }else{ echo __("Submit Review","apt"); } ?></a>
										
										<a class="btn btn-default apt_cancel_review_pop" href="javascript:void(0)"><?php echo __("Cancel","apt");?></a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>	
					<?php } ?>	
						
						
						
					</td>
				</tr>	
		<?php }
}
/* Create/Update Client Review */
if(isset($_POST['general_ajax_action'],$_POST['review_booking_id'],$_POST['client_id'],$_POST['provider_id']) && $_POST['general_ajax_action']=='appointment_clientreview' && $_POST['review_booking_id']!='' && $_POST['client_id']!='' && $_POST['provider_id']!=''){
		if($_POST['method']=='C'){
		$apt_bookings->booking_id = $_POST['review_booking_id'];
		$apt_bookings->readOne_by_booking_id();
		$reviews->location_id = $apt_bookings->location_id;
		$reviews->booking_id = $_POST['review_booking_id'];
		$reviews->provider_id = $_POST['provider_id'];
		$reviews->client_id = $_POST['client_id'];
		}
		$reviews->status = 'A';
		$reviews->rating = $_POST['rating'];
		$reviews->description = $_POST['description'];
		if($_POST['method']=='C'){
			$reviews->create();
		}else{
			$reviews->id = $_POST['review_id'];
			$reviews->update();
		}
}


/** Download Client Invoice--From Client Dashboard **/
if(isset($_GET['general_ajax_action'],$_GET['order_id'],$_GET['client_id'],$_GET['key']) && $_GET['general_ajax_action']=='client_download_invoice' && $_GET['order_id']!='' && $_GET['client_id']!='' && $_GET['key']!=''){
	
	$keystring =  substr($_GET['key'], 1, -1);
	$decodedkey = base64_decode($keystring);
	$general->business_owner_id = get_current_user_id();
	$validatekey = $decodedkey-1247;
	if($validatekey!=$_GET['order_id']){
		echo __("Invalid key supplied.","apt"); die();
	}
	
	include(dirname(dirname(dirname(__FILE__))).'/assets/pdf/tfpdf/tfpdf.php');
	
	$apt_bookings->order_id = $_GET['order_id'];
	$apt_bookings->client_id = $_GET['client_id'];
	$clientorderbookings = $apt_bookings->get_client_bookings_by_order_id();
	$order_info->order_id = $_GET['order_id'];
	$bwid = $order_info->get_author_id_by_order_id();
	
		
					
	$invoice_number = strtoupper(date_i18n('M',strtotime($clientorderbookings[0]->lastmodify))).'-'.$_GET['client_id'];
	$invoice_date = date_i18n(get_option('appointment_datepicker_format'.'_'.$bwid),strtotime($clientorderbookings[0]->lastmodify));	
	
	/*Client info*/
	$order_info->order_id = $_GET['order_id'];
	$order_info->readOne_by_order_id();
	$client_personal_info=unserialize($order_info->client_personal_info);

	/*Payment Info */
	$payments->order_id = $_GET['order_id'];
	$payments->read_one_by_order_id();	
	$payments->payment_method;
	if($payments->payment_method == 'paypal') { $pay_type = __('Paypal','apt'); }
	elseif($payments->payment_method == 'pay_locally') { $pay_type = __('Pay Locally','apt'); }
	elseif($payments->payment_method == 'Free') {  $pay_type = __('Free','apt');}
	elseif($payments->payment_method == 'stripe'){ $pay_type = __('Stripe','apt'); }
	elseif($payments->payment_method =='authorizenet'){$pay_type = __('Authorize .Net','apt');}
	else{$pay_type = '-';} 
	$net_amount = $general->apt_price_format_for_pdf($payments->net_total);
	$discount = $general->apt_price_format_for_pdf($payments->discount);
	$taxes = $general->apt_price_format_for_pdf($payments->taxes);
	$partial = $general->apt_price_format_for_pdf($payments->partial);
	
	
	$backgroundimage=$plugin_url_for_ajax."/assets/images/client_inv.jpg";
	if(get_option('appointment_company_logo'.'_'.$bwid)==''){
		$companylogo=$plugin_url_for_ajax."/assets/images/company.png";
	}else{
		$companylogo=site_url()."/wp-content/uploads".get_option('appointment_company_logo'.'_'.$bwid);	
	}
	
	$pdf = new tFPDF();
	$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
	$pdf->SetFont('DejaVu','',14);
	$pdf->SetMargins(0,0);
	$pdf->SetTopMargin(0);
	$pdf->SetAutoPageBreak(true,0);
	$pdf->AddPage();
	$pdf->SetFillColor(242,242,242);
    $pdf->SetTextColor(102,103,102);
    $pdf->SetDrawColor(128,255,0);
    $pdf->SetLineWidth(0);
   
	$pdf->Cell(210,297,'',0,1,'C',true);
	$pdf->Image($backgroundimage,0,0,210);
	
	$pdf->Image($companylogo,20,15,20); 
	$pdf->SetFont('DejaVu','',9);
	$pdf->Text(130,12,get_option('appointment_company_name'.'_'.$bwid));
	$pdf->Text(130,17,get_option('appointment_company_address' . '_' .$bwid ));
	$pdf->Text(130,22,get_option('appointment_company_city' . '_' .$bwid ).",".get_option('appointment_company_state' . '_' .$bwid));
	$pdf->Text(130,27,get_option('appointment_company_country' . '_' .$bwid ));
	$pdf->Text(130,30,get_option('appointment_company_phone' . '_' .$bwid));
	$pdf->Text(130,33,get_option('appointment_company_email' . '_' .$bwid));
	
	$pdf->SetFont('DejaVu','',15);
	$pdf->Text(21,56,__("INVOICE TO:","apt"));
	
	$pdf->SetFont('DejaVu','',12);
	$pdf->Text(21,63,ucwords($order_info->client_name));
	
	$pdf->SetFont('DejaVu','',8);
	if(isset($client_personal_info['address'])){
	$pdf->Text(21,68,$client_personal_info['address']);
	}
	if(isset($client_personal_info['city'],$client_personal_info['state'])){
	$pdf->Text(21,72,$client_personal_info['city'].",".$client_personal_info['state']);
	}
	if(isset($client_personal_info['country'])){
	$pdf->Text(21,76,$client_personal_info['country']);	
	}
	$pdf->Text(30,82,$order_info->client_phone);
	$pdf->Text(30,88,$order_info->client_email);
	
	$pdf->SetFont('DejaVu','',28);
	$pdf->SetTextColor(0,0,0);
	$pdf->Text(95,62,__("INVOICE #","apt").strtoupper(date_i18n('M',strtotime($clientorderbookings[0]->lastmodify)))."-".sprintf("%04d",$_GET['order_id']));


	$pdf->SetFont('DejaVu','',7);
	$pdf->SetTextColor(102,103,102);
	$pdf->Text(109 - $pdf->GetStringWidth(__("Invoice Date","apt"))/2,77,__("Invoice Date","apt"));
	$pdf->Text(182 - $pdf->GetStringWidth(__("Payment Method","apt"))/2,77,__("Payment Method","apt"));
	
	$pdf->SetFont('DejaVu','',10);
   
	$pdf->Text(109 - $pdf->GetStringWidth(date_i18n(get_option('appointment_datepicker_format' . '_' . $bwid),strtotime($clientorderbookings[0]->lastmodify)))/2,82,date_i18n(get_option('appointment_datepicker_format' . '_' . $bwid),strtotime($clientorderbookings[0]->lastmodify)));
	$pdf->Text(181 - $pdf->GetStringWidth(strtoupper($pay_type))/2,82,strtoupper($pay_type));
	$pdf->SetFont('DejaVu','',13.5);
	$pdf->Text(20,107,__("Description","apt"));
	$pdf->Text(60,107,__("Duration","apt"));
	$pdf->Text(90,107,__("Provider","apt"));
	$pdf->Text(120,107,__("Date","apt"));
	$pdf->Text(150,107,__("Time","apt"));
	$pdf->Text(180,107,__("Price","apt"));
	
	$pdf->SetFont('DejaVu','',8);
	
	$addondetails_startpoint = 120;
	foreach($clientorderbookings as $clientorderbooking){								
		$service->id= $clientorderbooking->service_id;
		$service->readone();	
		$servicedurationstrinng = '';
		if(floor($service->duration/60)!=0){ $servicedurationstrinng .= floor($service->duration/60); $servicedurationstrinng .= __(" Hrs","apt"); } 
		if($service->duration%60 !=0){  $servicedurationstrinng .= $service->duration%60; $servicedurationstrinng .= __(" Mins","apt"); }
		$staff->id=$clientorderbooking->provider_id;
		$staff_info = $staff->readOne();   
	  
	$pdf->Text(20,$addondetails_startpoint,$service->service_title);
	 $pdf->Text(60,$addondetails_startpoint,$servicedurationstrinng);
	$pdf->Text(90,$addondetails_startpoint,ucfirst($staff_info[0]['staff_name']));
	$pdf->Text(120,$addondetails_startpoint,date_i18n('M d,Y',strtotime($clientorderbooking->booking_datetime)));
	$pdf->Text(150,$addondetails_startpoint,date_i18n(get_option('time_format'),strtotime($clientorderbooking->booking_datetime)).'-'.date_i18n(get_option('time_format'),strtotime($clientorderbooking->booking_endtime)));
		
	/* $pdf->Text(180,$addondetails_startpoint,iconv("UTF-8", "windows-1252",get_option('appointment_currency_symbol'.'_'.$bwid)).$clientorderbooking->booking_price); */
	$pdf->Text(180,$addondetails_startpoint,iconv("UTF-8", "windows-1252",$general->apt_price_format_for_pdf($clientorderbooking->booking_price)));
	$addondetails_startpoint=$addondetails_startpoint+5;
	}
	
	
	$pdf->SetFont('DejaVu','',8);
	$pdf->Text(145,170,__("Total","apt"));
	$pdf->Text(145,175,__("Tax","apt"));
	$pdf->Text(145,180,__("Discount","apt"));	   
	   
	$pdf->SetFont('DejaVu','',8);
	/* $pdf->Text(180,170,iconv("UTF-8", "windows-1252",get_option('appointment_currency_symbol'.'_'.$bwid)).$payments->amount);
	$pdf->Text(180,175,iconv("UTF-8", "windows-1252",get_option('appointment_currency_symbol'.'_'.$bwid)).$payments->taxes);
	$pdf->Text(180,180,iconv("UTF-8", "windows-1252",get_option('appointment_currency_symbol'.'_'.$bwid)).$payments->discount); */
	$pdf->Text(180,170,iconv("UTF-8", "windows-1252",$general->apt_price_format_for_pdf($payments->amount)));
	$pdf->Text(180,175,iconv("UTF-8", "windows-1252",$general->apt_price_format_for_pdf($payments->taxes)));
	$pdf->Text(180,180,iconv("UTF-8", "windows-1252",$general->apt_price_format_for_pdf($payments->discount)));
	/* $pdf->Text(170,185,$payment_net_amount); */
	
	$pdf->SetFont('DejaVu','',15);
	$pdf->SetTextColor(0,0,0);
	$pdf->Text(140,193,__("TOTAL:","apt"));
	$pdf->Text(175,193,iconv("UTF-8", "windows-1252",$general->apt_price_format_for_pdf($payments->net_total)));

	$pdf->SetFont('DejaVu','',12);
	$pdf->SetTextColor(102,103,102);
	
	$pdf->SetFont('DejaVu','',14);
	$pdf->Text(23,195,__("THANK YOU FOR YOUR BUSINESS!","apt"));

	$pdf->SetFont('DejaVu','',14);
	$pdf->Text(145,210,__("For ","apt").get_option('appointment_company_name'.'_'.$bwid));
	
	$pdf->SetFont('DejaVu','',8);
	$pdf->Text(153,225,__("Company Director","apt"));

	$pdf->Output("#".$invoice_number.".pdf","D");
			/*
	ob_start();
	header('Content-type: application/pdf');
	$pdf->Output('','I');
	ob_end_flush(); */
}

/* appointment Add/Remove Sample Data */
if(isset($_REQUEST['general_ajax_action'],$_REQUEST['method']) && $_REQUEST['general_ajax_action']=='appointment_sampledata' && $_REQUEST['method']!=''){
	$bwid = $_POST['bwid'];
	if($_REQUEST['method']=='Add'){
		$locationsinfo = array(array('location_title'=>'California','description'=>'California','email'=>'California@California.com','phone'=>'7739477310','address'=>'1625 E 75th St','city'=>'California','state'=>'Los Angles','zip'=>'60649','country'=>'USA'),array('location_title'=>'Singapore ','description'=>'Singapore','email'=>'Singapore@Singapore.com','phone'=>'8884081113','address'=>'514 S. MAGNOLIA ST.','city'=>'Rome','state'=>'Rome','zip'=>'32806','country'=>'Italy'));
		
		$staffsinfo = 	array(array('staff_name'=>'John','username'=>'john'.rand(10,1000),'email'=>'john@demo.com','description'=>'John staff description'),array('staff_name'=>'Johndoe','username'=>'johndoe'.rand(10,1000),'email'=>'Johndoe@demo.com','description'=>'Johndoe staff description'));
		
		$servicesinfo = array(array('service_title'=>'Cosmetic Dentistry','description'=>'Cosmetic dentistry is generally used to refer to any dental work that improves the appearance (though not necessarily the functionality) of teeth, gums and/or bite. It primarily focuses on improvement dental aesthetics in color, position, shape, size, alignment and overall smile appearance.'),array('service_title'=>'Routine Tooth Extractions','description'=>'Routine Extractions. There are instances when a tooth cannot be restored. Extensive decay as a result of chronic neglect or trauma that results in the inadvertent fracture of teeth are two leading causes for a tooth to be deemed non-salvageable.'));
		
		$addonsinfo = array(array('addon_title'=>'Teeth Whitening','price'=>'20','max_qty'=>5),array('addon_title'=>'Surgical tooth extractions','price'=>'100','max_qty'=>10));
		
		$categoriesinfo = array(array('category_title'=>' Cosmetic Dentistry'),array('category_title'=>'Routine Tooth Extractions'));
		
		
		$apt_clientinfo = array(array('client_name'=>'John Deo','client_email'=>'johndeo@example.com','client_phone'=>'+17567436945'),array('client_name'=>'John Martin','client_email'=>'johnmartin@example.com','client_phone'=>'+17567436949'));
	
		$locationsids = array();	
		$servicesids = array();	
		$categoriesids = array();	
		$staffsids = array();
		$bdclientids = array();
		$bookingsids = array();
		$paymentsids = array();
		$orderids = array();
		/*Adding Locations */
		foreach($locationsinfo as $locationinfo){
			if(get_option('appointment_multi_location'.'_'.$bwid)=='E'){	
				$wpdb->query("insert into ".$wpdb->prefix."apt_locations set location_title='".$locationinfo['location_title']."',business_owner_id=".$bwid.",description='".$locationinfo['description']."',email='".$locationinfo['email']."',phone='".$locationinfo['phone']."',address='".$locationinfo['address']."',city='".$locationinfo['city']."',state='".$locationinfo['state']."',zip='".$locationinfo['zip']."',country='".$locationinfo['country']."',status='E'");
				$locationsids[] = $wpdb->insert_id;
			}else{
				$locationsids[] = 0;
			}
		}	
		
		/* Adding Categories */
		$catecounter = 0;
		foreach($categoriesinfo as $categoryinfo){		
			$wpdb->query("insert into ".$wpdb->prefix."apt_categories set business_owner_id='".$bwid."', location_id='".$locationsids[$catecounter]."',category_title='".$categoryinfo['category_title']."'");
			$categoriesids[] =  $wpdb->insert_id;
			$catecounter++;
		}
		/* Add Staff Members */
		$staffcounter =0;
		foreach($staffsinfo as $staffinfo){
		$userdata = array('user_login'=>$staffinfo['username'],'user_email'=>$staffinfo['email'],'user_pass'=>$staffinfo['staff_name'],'first_name'=>$staffinfo['staff_name'],		'last_name'=>'','nickname'=>'','role'=>'subscriber');					
		$user_id = wp_insert_user($userdata);
		$staffsids[] = $user_id;	
		$user = new WP_User($user_id);
		$user->add_cap('apt_staff');
		add_user_meta($user_id, 'staff_location',$locationsids[$staffcounter]);
		add_user_meta($user_id, 'staff_phone','');
		add_user_meta($user_id, 'staff_description',$staffinfo['description']);
		add_user_meta($user_id, 'schedule_type','W');
		add_user_meta($user_id, 'staff_image','');
		add_user_meta($user_id, 'staff_status','E');
		add_user_meta($user_id, 'staff_timezone','');
		add_user_meta($user_id, 'staff_timezoneID','');
		add_user_meta($user_id, 'staff_bwid',$bwid);
		
		
			/*Adding Provider Schedule */
			for($dayid=1;$dayid<=7;$dayid++){
				$wpdb->query("insert into ".$wpdb->prefix."apt_schedule set provider_id='".$user_id."',weekday_id='".$dayid."',daystart_time='08:00:00',dayend_time='17:00:00',week_id='1'");
			}
			
			$staffcounter++;		
		}
		
		/* Adding Services */
		$servcounter = 0;
		foreach($servicesinfo as $serviceinfo){
			$wpdb->query("insert into ".$wpdb->prefix."apt_services set location_id='".$locationsids[$servcounter]."',business_owner_id='".$bwid."',color_tag='#".rand(100000,999999)."',service_title='".$serviceinfo['service_title']."',category_id='".$categoriesids[$servcounter]."',duration='30',amount='50',service_description='".$serviceinfo['description']."',service_status='Y'");
			$servicesids[] =  $wpdb->insert_id;			
			
			/*Link Service With Staff Member*/
			$wpdb->query("insert into ".$wpdb->prefix."apt_providers_services set business_owner_id='".$bwid."', provider_id='".$staffsids[$servcounter]."',service_id='".$servicesids[$servcounter]."'");
			
			/* Service Addons */
			$wpdb->query("INSERT INTO ".$wpdb->prefix."apt_services_addon (id,business_owner_id,service_id,addon_service_name,base_price,maxqty,image,multipleqty,status,position,predefine_image,predefine_image_title,location_id)values('','".$bwid."','".$servicesids[$servcounter]."','".$addonsinfo[$servcounter]['addon_title']."','".$addonsinfo[$servcounter]['price']."','".$addonsinfo[$servcounter]['max_qty']."','','Y','E','','','','".$locationsids[$servcounter]."')");
			
			
			$servcounter++;
		}
		
		/* Adding Clients */
		$clientcounter = 0;
		foreach($apt_clientinfo as $apt_clientsinfo){
			
			if($apt_clientsinfo['client_name'] == 'John Deo'){	
				/* Get Locations id */
				$query = "select * from ".$wpdb->prefix."apt_locations where email='California@California.com' and business_owner_id=".$bwid."";
				$res = $wpdb->get_results($query);
				/* Get service id */
				$query = "select * from ".$wpdb->prefix."apt_services where service_title='Cosmetic Dentistry' and business_owner_id=".$bwid."";
				$res_service = $wpdb->get_results($query);
				/* Get provider id */
				$query = "select * from ".$wpdb->prefix."apt_providers_services where service_id='".$res_service[0]->id."' and business_owner_id=".$bwid."";
				$res_provider = $wpdb->get_results($query);
				
				$bookdate1s = date_i18n('Y-m-d H:i:s');
				$bookdate1 = date_i18n('Y-m-d H:i:s',strtotime("+1 days", strtotime(date_i18n('Y-m-d',strtotime($bookdate1s)).' '.date_i18n('H:i:s',strtotime($bookdate1s)))));
				$bookend = date_i18n('Y-m-d H:i:s',strtotime("+30 minutes", strtotime(date_i18n('Y-m-d',strtotime($bookdate1)).' '.date_i18n('H:i:s',strtotime($bookdate1)))));
			}else{	
				/* Get Locations id */
				$query = "select * from ".$wpdb->prefix."apt_locations where email='Singapore@Singapore.com' and business_owner_id=".$bwid."";
	            $res = $wpdb->get_results($query);
				/* Get service id */
				$query = "select * from ".$wpdb->prefix."apt_services where service_title='Routine Tooth Extractions' and business_owner_id=".$bwid."";
				$res_service = $wpdb->get_results($query);
				
				/* Get provider id */
				$query = "select * from ".$wpdb->prefix."apt_providers_services where service_id='".$res_service[0]->id."' and business_owner_id=".$bwid."";
				$res_provider = $wpdb->get_results($query);
				
				$bookdate1 = date_i18n('Y-m-d H:i:s',strtotime("+1 month", strtotime(date_i18n('Y-m-d',strtotime($todaydate)).' '.date_i18n('H:i:s',$todaydate))));
				$bookend = date_i18n('Y-m-d H:i:s',strtotime("+30 minutes", strtotime(date_i18n('Y-m-d',strtotime($bookdate1)).' '.date_i18n('H:i:s',strtotime($bookdate1)))));
			}
			
			/* Get order id of user */
			$client_info_table = $wpdb->prefix .'apt_order_client_info';
			$sql_id="SELECT max(order_id) as max FROM ".$client_info_table;
			$get_order_id=$wpdb->get_var($sql_id);
			if($get_order_id == 0){
				$order_id = 1000;
			}else{
			$order_id = $get_order_id + 1;
			}
			
			$orderids[] =$order_id;
			$apt_user_info = array(
					'user_login'    =>   $apt_clientsinfo['client_name'],
					'user_email'    =>   $apt_clientsinfo['client_email'],
					'user_pass'     =>   '12345678',
					'first_name'    =>   $apt_clientsinfo['client_name'],
					'last_name'     =>   '',
					'nickname'      =>  '',
					'role' => 'subscriber'
					);	
	   
			$new_apt_user = wp_insert_user( $apt_user_info );
			$bdclientids[] =  $new_apt_user;
			$user = new WP_User($new_apt_user);
			$user->add_cap('read');
			$user->add_cap('apt_client'); 
			$user->add_role('apt_users');
			$user_id = $new_apt_user;
			$user_login = $preff_username;
			add_user_meta( $new_apt_user, 'apt_client_locations','#'.$res[0]->id.'#');
			
			
			
			$query1="INSERT INTO ".$wpdb->prefix."apt_order_client_info (`id`, `business_owner_id`, `order_id`, `client_name`, `client_email`, `client_phone`, `client_personal_info`) VALUES ('', '".$bwid."', '".$order_id."', '".$apt_clientsinfo['client_name']."', '".$apt_clientsinfo['client_email']."', '".$apt_clientsinfo['client_phone']."', '');";
			$add = $wpdb->query($query1);
			if($add){
				echo "addedd client";
			}else{
				echo "not client";
			}
			
			for($i=0;$i<=6;$i++){
				/* Get order id of user */
			$client_info_table = $wpdb->prefix .'apt_order_client_info';
			$sql_id="SELECT max(order_id) as max FROM ".$client_info_table;
			$get_order_id=$wpdb->get_var($sql_id);
			if($get_order_id == 0){
				$order_id = 1000;
			}else{
			$order_id = $get_order_id + 1;
			}
				if($i <= 2){
					$bookdate1s = date_i18n('Y-m-d H:i:s');
					$bookdate1 = date_i18n('Y-m-d H:i:s',strtotime("+1 days", strtotime(date_i18n('Y-m-d',strtotime($bookdate1s)).' '.date_i18n('H:i:s',strtotime($bookdate1s)))));
					$bookend = date_i18n('Y-m-d H:i:s',strtotime("+30 minutes", strtotime(date_i18n('Y-m-d',strtotime($bookdate1)).' '.date_i18n('H:i:s',strtotime($bookdate1)))));
				}else if($i <= 4){
					$bookdate1s = date_i18n('Y-m-d H:i:s');
					$bookdate1 = date_i18n('Y-m-d H:i:s',strtotime("+2 days", strtotime(date_i18n('Y-m-d',strtotime($bookdate1s)).' '.date_i18n('H:i:s',$bookdate1s))));
					$bookend = date_i18n('Y-m-d H:i:s',strtotime("+30 minutes", strtotime(date_i18n('Y-m-d',strtotime($bookdate1)).' '.date_i18n('H:i:s',strtotime($bookdate1)))));
				}else{
					$bookdate1s = date_i18n('Y-m-d H:i:s');
					$bookdate1 = date_i18n('Y-m-d H:i:s',strtotime("+3 days", strtotime(date_i18n('Y-m-d',strtotime($bookdate1s)).' '.date_i18n('H:i:s',$bookdate1s))));
					$bookend = date_i18n('Y-m-d H:i:s',strtotime("+30 minutes", strtotime(date_i18n('Y-m-d',strtotime($bookdate1)).' '.date_i18n('H:i:s',strtotime($bookdate1)))));
				}
				
				$query1="INSERT INTO ".$wpdb->prefix."apt_order_client_info (`id`, `business_owner_id`, `order_id`, `client_name`, `client_email`, `client_phone`, `client_personal_info`) VALUES ('', '".$bwid."', '".$order_id."', '".$apt_clientsinfo['client_name']."', '".$apt_clientsinfo['client_email']."', '".$apt_clientsinfo['client_phone']."', '');";
			$add = $wpdb->query($query1);
			if($add){
				echo "addedd client";
			}else{
				echo "not client";
			}
				
				$query2 = "INSERT INTO ".$wpdb->prefix."apt_bookings (`id`, `location_id`, `order_id`, `business_owner_id`, `client_id`, `service_id`, `provider_id`, `booking_price`, `booking_datetime`, `booking_endtime`, `booking_status`, `reject_reason`, `cancel_reason`, `confirm_note`, `reschedule_note`, `reminder`, `notification`, `lastmodify`) VALUES ('', '".$res[0]->id."', '".$order_id."', '".$bwid."', '".$user_id."', '".$res_service[0]->id."', '".$res_provider[0]->provider_id."', '50', '".$bookdate1."', '".$bookend."', 'A', '', '', '', '', '0', '0', NOW());";
				$add1 = $wpdb->query($query2);
				$bookingsids[] = $wpdb->insert_id;
				
				$query3 = "INSERT INTO ".$wpdb->prefix."apt_payments (`id`, `location_id`, `business_owner_id`, `client_id`, `order_id`, `payment_method`, `transaction_id`, `amount`, `discount`, `taxes`, `partial`, `net_total`, `lastmodify`) VALUES ('', '".$res[0]->id."', '".$bwid."', '".$user_id."', '".$order_id."', 'pay_locally', '', '50', '0', '0', '0', '50', '')";
				$add2 = $wpdb->query($query3);
				$paymentsids[] = $wpdb->insert_id;
				
			}
			
			
			$clientcounter++;
		}
		
		
		$sampledataids = array('locationsids'=>implode(',',$locationsids),'servicesids'=>implode(',',$servicesids),'categoriesids'=>implode(',',$categoriesids),'staffsids'=>implode(',',$staffsids),'bdclientids'=>implode(',',$bdclientids),'bookingsids'=>implode(',',$bookingsids),'paymentsids'=>implode(',',$paymentsids),'orderids'=>implode(',',$orderids));
		add_option('appointment_sample_dataids' . '_' . $bwid,serialize($sampledataids));	
		update_option('appointment_sample_status' . '_' . $bwid,'N');
		$_SESSION['apt_location'] =0;
			
	}else{ 
	
	/* Remove Sample Data */
		$sampledata_info = unserialize(get_option('appointment_sample_dataids'.'_'.$bwid));
		$locationsids = explode(",",$sampledata_info['locationsids']);
		$categoriesids = explode(",",$sampledata_info['categoriesids']);
		$servicesids = explode(",",$sampledata_info['servicesids']);
		$staffsids = explode(",",$sampledata_info['staffsids']);
		$bdclientids = explode(",",$sampledata_info['bdclientids']);
		$bookingsids = explode(",",$sampledata_info['bookingsids']);
		$paymentsids = explode(",",$sampledata_info['paymentsids']);
		$orderids = explode(",",$sampledata_info['orderids']);
		/* Delete Sample Locations */
		foreach($locationsids as $location_id){
			$wpdb->query("Delete from ".$wpdb->prefix."apt_locations where id='".$location_id."'");
		}
		/* Delete Sample Categories */
		foreach($categoriesids as $category_id){
			$wpdb->query("Delete from ".$wpdb->prefix."apt_categories where id='".$category_id."'");
		}
		/* Delete Sample Services */
		foreach($servicesids as $service_id){
			$wpdb->query("Delete from ".$wpdb->prefix."apt_services where id='".$service_id."'");
			/* Delete Sample Service & Provider Releation */
			$wpdb->query("Delete from ".$wpdb->prefix."apt_providers_services where service_id='".$service_id."'");
			/* Delete Sample Service Addons */
			$wpdb->query("Delete from ".$wpdb->prefix."apt_services_addon where service_id='".$service_id."'");
		}
		/* Delete Sample Staff */
		foreach($staffsids as $staff_id){
			$wpdb->query("Delete from ".$wpdb->prefix."users where ID='".$staff_id."'");
			/* Delete Staff Meta */
			$wpdb->query("Delete from ".$wpdb->prefix."usermeta where user_id='".$staff_id."'");
			/* Delete Staff Schedule */
			$wpdb->query("Delete from ".$wpdb->prefix."apt_schedule where provider_id='".$staff_id."'");
		}
		
		/* Delete Sample Staff */
		foreach($bdclientids as $client_id){
			$wpdb->query("Delete from ".$wpdb->prefix."users where ID='".$client_id."'");
			/* Delete Staff Meta */
			$wpdb->query("Delete from ".$wpdb->prefix."usermeta where user_id='".$client_id."'");
		}
		
		/* Delete Sample Staff */
		foreach($bookingsids as $booking_id){
			$wpdb->query("Delete from ".$wpdb->prefix."apt_bookings where id='".$booking_id."'");
		}		
		/* Delete Sample Payments */
		foreach($paymentsids as $payments_id){
			$wpdb->query("Delete from ".$wpdb->prefix."apt_payments where id='".$payments_id."'");
		}
		/* Order ID */
		foreach($orderids as $order_id){
			$wpdb->query("Delete from ".$wpdb->prefix."apt_order_client_info where order_id='".$order_id."'");
		}
		
		delete_option('appointment_sample_dataids' . '_'.$bwid);
		/* update_option('appointment_sample_status','N'); */
		$check_for_location = $wpdb->get_results("select  from ".$wpdb->prefix."apt_locations");
		 /*  if(sizeof($check_for_location)==0){
		   update_option('appointment_sample_status','Y');
		  }else{
		   update_option('appointment_sample_status','N');
		  } */
	}
	
}

/* appointment Publish/Hide/Delete Review */
if(isset($_POST['general_ajax_action'],$_POST['method'],$_POST['review_id']) && $_POST['general_ajax_action']=='publish_hide_delete_review' && $_POST['method']!='' && $_POST['review_id']!=''){
	if($_POST['method']=='delete'){		
		$reviews->id = $_POST['review_id'];
		$reviews->delete();
	}else{
		$reviews->status = $_POST['method'];
		$reviews->id = $_POST['review_id'];
		$reviews->update_review_status();	
	}	
}

if(isset($_POST['general_ajax_action']) && $_POST['general_ajax_action']=='save_custom_form'){
   $bwid = $_POST['bwid'];
   update_option('appointment_custom_form'.'_'.$bwid,$_POST['formdata']);
}
/* Client Dashboard Login */
if(isset($_POST['general_ajax_action'],$_POST['username'],$_POST['password']) && $_POST['general_ajax_action']=='client_dashboard_login'){
   $creds                  = array();
	$creds['user_login']    = $_POST['username'];
	$creds['user_password'] = $_POST['password'];
	$creds['remember']      = true;
	$user                   = wp_signon($creds, false);
	if (is_wp_error($user)) {
		echo __("Invalid Username or Password.", "ak");
	} else {
		echo '1';die();
	}
}

if(isset($_POST['general_ajax_action'],$_POST['user_type']) && $_POST['general_ajax_action']=='refresh_register_client_datatable' && $_POST['user_type']=='registered'){ 

	if(isset($_SESSION['apt_all_loc_clients']) && $_SESSION['apt_all_loc_clients']=='Y'){
		$clients->location_id = 'All';
		$all_clients_info = $clients->get_registered_clients();
	}else{
		$clients->location_id = $_SESSION['apt_location'];
		$all_clients_info = get_users( array( 'role' => 'apt_users' ,'meta_key' => 'apt_client_locations' ,'meta_value' => '#'.$_SESSION['apt_location'].'#'));
	}
?>
<h3><?php echo __("Registered Customers","apt");?></h3>  <div class=""></div>
	<div id="accordion" class="panel-group">
		<table id="registered-client-table" class="display responsive nowrap table table-striped table-bordered" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th><?php echo __("Client Name","apt");?></th>
						<th><?php echo __("Email","apt");?></th>
						<th><?php echo __("Phone","apt");?></th>
						<th class="thd-w200"><?php echo __("Bookings","apt");?></th>
					</tr>
				</thead>
				<tbody id="apt_registered_list" >
					<?php 
						$apt_bookings = new appointment_booking(); 
						foreach($all_clients_info as $client_info){
								$apt_bookings->location_id=$_SESSION['apt_location'];
								$apt_bookings->client_id = $client_info->ID;
								$all_booking = $apt_bookings->get_client_all_bookings_by_client_id();
								
								if(sizeof($all_booking)>0){
									$allboking = sizeof($all_booking)-1;
									$clientlastoid = $all_booking[$allboking]->order_id;
									$clients->order_id = $clientlastoid;
									$order_client_info = $clients->get_client_info_by_order_id();
								}
							?>
								<tr id="client_detail<?php echo $client_info->ID; ?>">
									<td><?php echo __(stripslashes_deep($client_info->display_name),"apt");?></td>
									<td><?php echo __($client_info->user_email,"apt");?></td>
									<td><?php echo __($client_info->client_phone,"apt");?></td>
												
									
									
								<td class="apt-bookings-td">
									<a class="btn btn-primary apt_show_bookings " data-method="registered" data-client_id='<?php echo $client_info->ID; ?>' href="#registered-details" data-toggle="modal"><i class="icon-calendar icons icon-space"></i> <?php echo __("Bookings","apt");?><span class="badge"><?php echo sizeof($all_booking); ?></span></a>
																						
									<a data-poid="popover-delete-reg-client<?php echo $client_info->ID;?>" class="col-sm-offset-1 btn btn-danger apt-delete-popover " rel="popover" data-placement='bottom' title="<?php echo __("Delete this Client?","apt");?>"> <i class="fa fa-trash icon-space " title="<?php echo __("Delete Client","apt");?>"></i><?php echo __("Delete","apt");?></a>
									<div id="popover-delete-reg-client<?php echo $client_info->ID;?>" style="display: none;">
										<div class="arrow"></div>
										<table class="form-horizontal" cellspacing="0">
											<tbody>
												<tr>
													<td>
														<button data-method="registered" data-client_id="<?php echo $client_info->ID;?>" value="Delete" class="btn btn-danger btn-sm apt_delete_client" type="submit"><?php echo __("Yes","apt");?></button>
														<button class="btn btn-default btn-sm apt-close-popover-delete" href="javascript:void(0)"><?php echo __("Cancel","apt");?></button>
													</td>
												</tr>
											</tbody>
										</table>
									</div>											
									</td>
								</tr>
						   <?php  } ?>
				</tbody>
			</table>
		
		<div id="registered-details" class="modal fade booking-details-modal">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title"><?php echo __("Registered Customers Bookings","apt");?></h4>
					</div>
					<div class="modal-body">
						<div class="table-responsive"> 
						<table id="registered-client-booking-details"  class="display responsive table table-striped table-bordered" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th style="width: 9px !important;"><?php echo __("#","apt");?></th>
										<th style="width: 48px !important;"><?php echo __("Client Name","apt");?></th>
										<th style="width: 67px !important;"><?php echo __("Service Provider","apt");?></th>
										<th style="width: 73px !important;"><?php echo __("Service","apt");?></th>
										<th style="width: 44px !important;"><?php echo __("Appt. Date","apt");?></th>
										<th style="width: 44px !important;"><?php echo __("Appt. Time","apt");?></th>
										<th style="width: 39px !important;"><?php echo __("Status","apt");?></th>
										<th style="width: 70px !important;"><?php echo __("Payment Method","apt");?></th>
										<th style="width: 257px !important;"><?php echo __("More Details","apt");?></th>
									</tr>
								</thead>
								<tbody id="apt_client_bookingsregistered"></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
<?php 
}

if(isset($_POST['general_ajax_action'],$_POST['user_type']) && $_POST['general_ajax_action']=='refresh_register_client_datatable' && $_POST['user_type']=='guest'){
	
	
	if(isset($_SESSION['apt_all_loc_clients']) && $_SESSION['apt_all_loc_clients']=='Y'){
		$clients->location_id = 'All';
		
	}else{
		$clients->location_id = $_SESSION['apt_location'];		
	}
	$all_guesuser_info = $clients->get_all_guest_users_orders();
?>
<h3><?php echo __("Guest Customers","apt");?></h3>
				<div id="accordion" class="panel-group">

					<table id="guest-client-table" class="display responsive table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
								
								<th><?php echo __("Client Name","apt");?></th>
								<th><?php echo __("Email","apt");?></th>
								<th><?php echo __("Phone","apt");?></th>
								<th class="thd-w200"><?php echo __("Bookings","apt");?></th>
								
							</tr>
						</thead>
						<tbody id="apt_guest_list">
							
							<?php foreach($all_guesuser_info as $client_info){							
									$apt_bookings->order_id = $client_info->order_id;				
									$all_booking=$apt_bookings->get_guest_users_booking_by_order_id();
								?>
									<tr id="client_detail<?php echo $client_info->order_id; ?>">
										<td><?php echo __(stripslashes_deep($client_info->client_name),"apt");?></td>
										<td><?php echo __($client_info->client_email,"apt");?></td>
										<td><?php echo __($client_info->client_phone,"apt");?></td>
																
										<td class="apt-bookings-td"> 
										<a class="btn btn-primary apt_show_bookings" data-method="guest" data-client_id='<?php echo $client_info->order_id; ?>' href="#guest-details" data-toggle="modal"><i class="icon-calendar icons icon-space"></i><?php echo __("Bookings","apt");?><span class="badge"><?php echo sizeof($all_booking); ?></span></a>
																										
										<a data-poid="popover-delete-guest-client<?php echo $client_info->order_id; ?>" class="col-sm-offset-1 btn btn-danger apt-delete-popover" rel="popover" data-placement='bottom' title="<?php echo __("Delete this Client?","apt");?>"> <i class="fa fa-trash icon-space " title="<?php echo __("Delete Client","apt");?>"></i><?php echo __("Delete","apt");?></a>
										
										<div id="popover-delete-guest-client<?php echo $client_info->order_id; ?>" style="display: none;">
											<div class="arrow"></div>
											<table class="form-horizontal" cellspacing="0">
												<tbody>
													<tr>
														<td>
															<button data-method="guest" data-client_id="<?php echo $client_info->order_id;?>" value="Delete" class="btn btn-danger btn-sm apt_delete_client" type="submit"><?php echo __("Yes","apt");?></button>
															<button class="btn btn-default btn-sm apt-close-popover-delete" href="javascript:void(0)"><?php echo __("Cancel","apt");?></button>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</td>									
									</tr>
									   <?php  } ?>
							</tbody>
					</table>
						
					<div id="guest-details" class="modal fade booking-details-modal">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title"><?php echo __("Guest Customers Bookings","apt");?></h4>
								</div>
								<div class="modal-body">
									<div class="table-responsive">
										<div class="table-responsive"> 
										<table id="guest-client-booking-details" class="display responsive table table-striped table-bordered" cellspacing="0" width="100%">
											<thead>
												<tr>
													<th style="width: 9px !important;"><?php echo __("#","apt");?></th>
													<th style="width: 48px !important;"><?php echo __("Client Name","apt");?></th>
													<th style="width: 67px !important;"><?php echo __("Service Provider","apt");?></th>
													<th style="width: 73px !important;"><?php echo __("Service","apt");?></th>
													<th style="width: 44px !important;"><?php echo __("Appt. Date","apt");?></th>
													<th style="width: 44px !important;"><?php echo __("Appt. Time","apt");?></th>
													<th style="width: 39px !important;"><?php echo __("Status","apt");?></th>
													<th style="width: 70px !important;"><?php echo __("Payment Method","apt");?></th>
													<th style="width: 257px !important;"><?php echo __("More Details","apt");?></th>
												</tr>
											</thead>
											<tbody id="apt_client_bookingsguest">
											</tbody>
										</table>
									</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> <?php
}