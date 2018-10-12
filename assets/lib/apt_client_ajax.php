<?php  
if(!session_id()) { @session_start(); }
$root = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));	
if (file_exists($root.'/wp-load.php')) {
	require_once($root.'/wp-load.php');	
}		

$plugin_url = plugins_url('',  dirname(__FILE__));
$base =   dirname(dirname(dirname(__FILE__)));
$uploaded_images_path = dirname(dirname(dirname(plugins_url( '/',dirname(__FILE__))))).'/uploads/';
$bwid = (isset($_POST['bwid']))?$_POST['bwid']:0;

$currency_symbol = get_option('appointment_currency_symbol'.'_'.$bwid);

include_once($base.'/objects/class_general.php');
include_once($base.'/objects/class_location.php');
include_once($base.'/objects/class_service.php');
include_once($base.'/objects/class_service_schedule_price.php');
include_once($base.'/objects/class_category.php');
include_once($base.'/objects/class_provider.php');
include_once($base.'/objects/class_front_appointment_first_step.php');
include_once($base.'/objects/class_coupons.php');
include_once($base.'/objects/class_clients.php');
include_once($base.'/objects/class_booking.php');
include_once($base.'/objects/class_payments.php');
include_once($base.'/objects/class_email_templates.php');

/* Get Provider Time Slots */
if(isset($_POST['action'],$_POST['selstaffid']) && $_POST['action']=='apt_get_provider_slots' && $_POST['selstaffid']!='')
{
	$bwid = (isset($_POST['bwid']))?$_POST['bwid']:get_user_meta( $_POST['selstaffid'], 'staff_bwid', true );
	$selecteddate = $_POST['seldate'];
	$selectedstaffid = $_POST['selstaffid'];


	$aptstaff = new appointment_staff();
	$aptstaff->id = $selectedstaffid;
	$provider_result = $aptstaff->readOne();
	
	$first_step = new appointment_first_step();
	$first_step->business_owner_id = $bwid;
	$time_interval = get_option('appointment_booking_time_interval'.'_'.$bwid);	
	$time_slots_schedule_type = strtolower($provider_result[0]['schedule_type']);
	$advance_bookingtime = get_option('appointment_minimum_advance_booking'.'_'.$bwid);
	$booking_paddingtime = get_option('appointment_booking_padding_time'.'_'.$bwid);
	
	$time_schedule = $first_step->get_day_time_slot_by_provider_id($selectedstaffid,$time_slots_schedule_type,$selecteddate,$time_interval); 
	$allofftime_counter = "";
	$allbreak_counter = 0;	
	$slot_counter = 0;
	
	$start_date = $selecteddate;
	
	/* Get Google Calendar Bookings of Provider */
	$providerTwoSync = 'Y';
	$providerCalenderBooking = array();
	if($providerTwoSync=='Y'){
		$curlevents = curl_init();
		curl_setopt_array($curlevents, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $plugin_url.'/GoogleCalendar/event.php?cdate='.$start_date.'&bwid='.$bwid,
			CURLOPT_FRESH_CONNECT =>true,
			CURLOPT_USERAGENT => 'Myspato'
		));
		$response = curl_exec($curlevents);    
		curl_close($curlevents);
		$provider_events = array();
		$provider_events = json_decode($response);

		$providerCalenderBooking = array();
		if(isset($provider_events)){
			foreach($provider_events as $providerevent){ 
				$startdate = date_i18n('Y-m-d', strtotime($providerevent->start)); 
				$starttime = date_i18n('H:i:s', strtotime($providerevent->start));
				$enddate = date_i18n('Y-m-d', strtotime($providerevent->end));
				$endtime = date_i18n('H:i:s', strtotime($providerevent->end));

				$GCslotstart = mktime(date_i18n('H',strtotime($starttime)),date_i18n('i',strtotime($starttime)),date_i18n('s',strtotime($starttime)),date_i18n('n',strtotime($startdate)),date_i18n('j',strtotime($startdate)),date_i18n('Y',strtotime($startdate))); 

				$GCslotend = mktime(date_i18n('H',strtotime($endtime)),date_i18n('i',strtotime($endtime)),date_i18n('s',strtotime($endtime)),date_i18n('n',strtotime($enddate)),date_i18n('j',strtotime($enddate)),date_i18n('Y',strtotime($enddate)));

				$providerCalenderBooking[] = array('start'=>$GCslotstart,'end'=>$GCslotend);
			}
		}
	}
	/*****************************************************************/
	/*****************************************************************/
	if($time_schedule['off_day']!=true  && isset($time_schedule['slots']) && sizeof($time_schedule['slots'])>0 && $allbreak_counter != sizeof($time_schedule['slots'])){
		foreach($time_schedule['slots']  as $slot) {
			$curreslotstr = strtotime(date_i18n('Y-m-d H:i:s',strtotime($start_date.' '.$slot)));
			$gccheck = 'N';
			/*Checking in GC booked Slots */
			if(sizeof($providerCalenderBooking)>0){
				for($i = 0; $i < sizeof($providerCalenderBooking); $i++) {
					if($curreslotstr >= $providerCalenderBooking[$i]['start'] && $curreslotstr < $providerCalenderBooking[$i]['end']){
						$providerCalenderBooking[$i]['start'];
						$gccheck = 'Y';
					}
				}
			}
			
			$ifbreak = 'N';
			/* Need to check if the appointment slot come under break time. */
			foreach($time_schedule['breaks'] as $daybreak) {
				if(strtotime($slot) >= strtotime($daybreak['break_start']) && strtotime($slot) < strtotime($daybreak['break_end'])) {
				   $ifbreak = 'Y';   
				}
			}
			/* if yes its break time then we will not show the time for booking  */
			if($ifbreak=='Y') { $allbreak_counter++; continue; } 
			
			$ifofftime = 'N';									
			foreach($time_schedule['offtimes'] as $offtime) {
				if(strtotime($selecteddate.' '.$slot) >= strtotime($offtime['offtime_start']) && strtotime($selecteddate.' '.$slot) < strtotime($offtime['offtime_end'])) {
				   $ifofftime = 'Y';
				}
			 }
			/* if yes its offtime time then we will not show the time for booking  */
			if($ifofftime=='Y') { $allofftime_counter++; continue; }
			
			
			$complete_time_slot = mktime(date('H',strtotime($slot)),date('i',strtotime($slot)),date('s',strtotime($slot)),date('n',strtotime($time_schedule['date'])),date('j',strtotime($time_schedule['date'])),date('Y',strtotime($time_schedule['date']))); 
						
			if(get_option('appointment_hide_booked_slot'.'_'.$bwid)=='E' && (in_array($complete_time_slot,$time_schedule['booked']) || $gccheck=='Y')) {
				continue;
			}
			
			
			if( (in_array($complete_time_slot,$time_schedule['booked']) || $gccheck=='Y') && (get_option('appointment_multiple_booking_sameslot'.'_'.$bwid)=='D') ) { ?>
				
			<?php
			} else {
				
			?>
				<option value="<?php echo date_i18n("H:i",strtotime($slot)); ?>"><?php echo date_i18n(get_option('time_format'),strtotime($slot)); ?></option>
			<?php 
			} $slot_counter++; 
		}
	}
}