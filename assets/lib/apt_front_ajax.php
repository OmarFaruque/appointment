<?php  
	if(!session_id()) { @session_start(); }
    $root = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));	
	if (file_exists($root.'/wp-load.php')) {
		require_once($root.'/wp-load.php');	
	}		
	
	$plugin_url = (isset($_POST['purl']))?$_POST['purl'].'/assets':plugins_url('',  dirname(__FILE__));
	$base =   dirname(dirname(dirname(__FILE__)));
	$uploaded_images_path = dirname(dirname(dirname(plugins_url( '/',dirname(__FILE__))))).'/uploads/';
	$currency_symbol = get_option('appointment_currency_symbol' . '_' . get_current_user_id());

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
	
	/* declare classes */
	$apt_general = new appointment_general();	
	$apt_location = new appointment_location();
	$apt_service = new appointment_service();
	$apt_service_schedule_price = new appointment_service_schedule_price();
	$apt_category = new appointment_category();
	$apt_staff = new appointment_staff();
	$first_step = new appointment_first_step();
	$apt_coupons = new appointment_coupons();
	$apt_client_info = new appointment_clients();	
	$apt_booking = new appointment_booking();
	$apt_payments = new appointment_payments();
	$apt_email_templates = new appointment_email_template();
	
/* Get Location By Zip Code */
if(isset($_POST['action'],$_POST['zipcode']) && $_POST['action']=='apt_get_location' && $_POST['zipcode']!='')
{

	$bwid = $_POST['bwid'];
	$areazipcodes = explode(',',get_option('appointment_booking_zipcodes'.'_'.$bwid));
	if(in_array($_POST['zipcode'],$areazipcodes)){
		echo 'found';
	}else{
		echo 'notfound';
	}	
}
 
/* Get Service By Location ID */
if(isset($_POST['action'],$_POST['location_id']) && $_POST['action']=='apt_get_location_services' && $_POST['location_id']!='')
{
	$apt_service->business_owner_id = $_POST['bwid'];
	$apt_service->location_id = $_POST['location_id'];
	$aptservices = $apt_service->readAll('');
	$services_categories = array();
	$location_services = array();
	foreach($aptservices as $aptservice){
		if(!in_array($aptservice->category_id, $services_categories)){
			$services_categories[] = $aptservice->category_id;
		}
		$location_services[] = $aptservice->id;
	}
	if(sizeof($services_categories)>0){
		foreach($services_categories as $services_category){		
			$apt_category->id = $services_category;
			$apt_category->readOne();
			?><li class="data-list service-category">
				<div class="apt-value" value=""><?php echo $apt_category->category_title; ?> </div>
			  </li>		
			<?php
			$apt_service->service_category = $services_category;
			$cat_services = $apt_service->readAll_category_services();
			
			foreach($cat_services as $cat_service){
				if(in_array($cat_service->id,$location_services)){
				 ?>
					<li class="data-list select_custom" data-sid="<?php echo $cat_service->id;?>">
						<div class="apt-value" ><?php echo $cat_service->service_title; ?></div>
					</li>
				 <?php
				}														
			}		
		}
	}else{ ?>
		<li class="data-list select_custom" data-sid="">
			<div class="apt-value apt-no-found"><?php echo __("No service found for this location.","apt");?></div>
		</li>
	<?php }
}


/* Get Service Detail by Service ID */
if(isset($_POST['action'],$_POST['sid']) && $_POST['action']=='apt_get_service_detail' && $_POST['sid']!='')
{ 
	
	$bwid = $_POST['bwid'];
	$serviceextinfo = array();
	$service_desc_status = get_option('appointment_show_service_desc'.'_'.$bwid);
	$service_description = '';
	
	/* Get Sevice Description If Enable */
	if($service_desc_status=='E'){	
			$apt_service->id = $_POST['sid'];	
			$apt_service->readOne('');
			$hours = '';
			$minutes = '';
			if(floor($apt_service->duration/60)!=0){ 
				$hours 	=  floor($apt_service->duration/60); 
				$hours .=  __("Hrs","apt");
			} 
			if($apt_service->duration%60 !=0){ 
				$minutes =  $apt_service->duration%60; 
				$minutes .=  __(" Mins","apt");
			
			}
			$offerclass = '';
			if($apt_service->offered_price!=''){ 
				$offerclass =  'td-line-through'; 
			}
			$offerpriceshow = '';
			if($apt_service->offered_price!=''){ 
				$offerpriceshow = '<h5 class="service-price np nm apt-sm-6 apt-md-3  apt-xs-12" title="Offer Price"><strong>'. __("Offered Price -","apt").'</strong><span class="apt-offered-price">'.$apt_general->apt_price_format($apt_service->offered_price).'</span></h5>';
			}
			
			
			$service_image = '';
			if($apt_service->image==''){ 
				$service_image =  $plugin_url.'/images/service.png';
			}else{
				$service_image =  $uploaded_images_path.$apt_service->image;
			}
			
				
			
			$service_description ='<div class="service-details-container fullwidth">
			<div class="apt-desc-header fullwidth">
			<a href="javascript:void(0)" id="close_service_details"><i class="icon-close-custom icons apt-close-desc"></i></a>
			<h5 class="service-duration np nm apt-sm-6 apt-md-4 apt-xs-12"><strong>'. __("Service duration - ","apt").'</strong><span class="apt-service-duration"><i class="icon-clock icons"></i>'.$hours.' '.$minutes.'</span></h5>
			<h5 class="service-price actual-price np nm apt-sm-6 apt-md-3 apt-xs-12" title="Actual Price"><strong>'.__("Service price - ","apt").'</strong><span class="apt-actual-price '.$offerclass.'">'. $apt_general->apt_price_format($apt_service->amount).'</span></h5>'.$offerpriceshow.'</div>
			<div class="apt-sm-12 apt-xs-12 np">
			<div class="apt-service-desc"><div class="apt-service-image-main pull-left"><img class="apt-service-img" src="'.$service_image.'" /></div><div class="apt-service-desc-p">'.$apt_service->service_description.'</div></div></div></div>';
	}

	/* Get Service Addons */
	$apt_service->selected_service_id = $_POST['sid'];
	$serviceaddons = $apt_service->get_all_addons();
	
	$serviceaddoninfo = '';
	if(sizeof($serviceaddons)>0){
		$serviceaddoninfo = '<h3 class="block-title"><i class="icon-puzzle icons fs-20"></i>'.__("Extra Services","apt").'</h3><div class="pr apt-sm-12 apt-xs-12 np"><div class="apt-extra-services-list apt-common-box"><ul class="addon-service-list fullwidth np">';
		
		foreach($serviceaddons as $serviceaddon){
			$addonimagepath = plugins_url( 'images/addon/sample.png',dirname(__FILE__));
			if(isset($serviceaddon->image) && $serviceaddon->image!=''){
				$uplodpathinfo = wp_upload_dir();
				$addonimagepath = $uplodpathinfo['baseurl'].$serviceaddon->image;			
			}
			$addonquantitybuttons = '';
			if(isset($serviceaddon->multipleqty) && $serviceaddon->multipleqty=='Y'){
				$addonquantitybuttons = '<div class="apt-addon-count apt-addon-count'.$serviceaddon->id.' border-c add_minus_button"><div class="apt-btn-group"><button data-addonmax="'.$serviceaddon->maxqty.'" data-addonid="'.$serviceaddon->id.'" id="minus'.$serviceaddon->maxqty.'" data-qtyaction="minus" class="minus apt-btn-left apt-small-btn apt_addonqty" type="button">-</button><input type="text" value="1" id="addonqty_'.$serviceaddon->id.'" class="apt-btn-text addon_qty" /><button data-addonmax="'.$serviceaddon->maxqty.'" data-addonid="'.$serviceaddon->id.'" id="add'.$serviceaddon->maxqty.'" data-qtyaction="add" class="add apt-btn-right pull-right apt-small-btn apt_addonqty" type="button">+</button></div></div>';		
			}
			
			
			$serviceaddoninfo .='<li class="apt-sm-6 apt-md-4 apt-lg-3 apt-xs-12 mb-15"><input type="checkbox" name="addon-checkbox'.$serviceaddon->id.'" data-saddonid="'.$serviceaddon->id.'" data-saddonmaxqty="'.$serviceaddon->multipleqty.'" class="addon-checkbox" id="apt-addon-'.$serviceaddon->id.'" /><label class="apt-addon-ser border-c" data-addonid="'.$serviceaddon->id.'" for="apt-addon-'.$serviceaddon->id.'"><span></span><div class="addon-price fullwidth">'.$apt_general->apt_price_format($serviceaddon->base_price).'</div><div class="apt-addon-img"><img src="'.$addonimagepath.'" /></div></label>'.$addonquantitybuttons.'<div class="addon-name fullwidth text-center">'.$serviceaddon->addon_service_name.'</div></li>';			
				
		}
		$serviceaddoninfo .= '</ul></div></div>';
	}
	$serviceextinfo['description'] = $service_description;
	$serviceextinfo['addonsinfo'] = $serviceaddoninfo;
	
	echo json_encode($serviceextinfo);
	
}

/* GET Service Providers */
if(isset($_POST['action'],$_POST['sid']) && $_POST['action']=='apt_get_service_providers' && $_POST['sid']!='')
{
	$bwid = $_POST['bwid'];
	$default_service_img = $plugin_url."/images/staff.png";
	$apt_staff->service_id = $_POST['sid'];
	$apt_staff->business_owner_id = $_POST['bwid'];


	$service_staffs = $apt_staff->read_staffs_by_service_id();
	
	$provider_avatar_view = get_option('appointment_show_provider_avatars'.'_'.$bwid);
	if($provider_avatar_view=='E'){ ?>
		<div class="apt-service-staff-list apt-common-box">
			<ul class="staff-list fullwidth np">
				<?php 
				if(sizeof($service_staffs)>0){
				$uplodpathinfo = wp_upload_dir();
				foreach($service_staffs as $aptstaff){ 
				$staffimagepath = plugins_url( 'images/provider/staff.png',dirname(__FILE__));
				if(isset($aptstaff['image']) && $aptstaff['image']!=''){	
					$staffimagepath = $uplodpathinfo['baseurl'].$aptstaff['image'];			
				}
													
				?>
				<li data-staffid="<?php echo $aptstaff['id'];?>" class="apt-staff-box apt-sm-4 apt-md-3 apt-lg-3 apt-xs-12 mb-15 omar">
					<input type="radio" name="provider_list" class="staff-radio" id="apt-staff-<?php echo $aptstaff['id'];?>" />
					<label class="apt-staff border-c" for="apt-staff-<?php echo $aptstaff['id'];?>">
						<span class="br-100"></span>
						<div class="apt-staff-img ">
							<img class="br-100" src="<?php echo $staffimagepath;?>" /> 
						</div>
						<div class="staff-name fullwidth text-center"><?php echo $aptstaff['staff_name'];?></div>
					</label>											
				</li>
				<?php }
			}else{ ?>
			<li class="apt-staff-box apt-sm-12 apt-md-12 apt-lg-12 apt-xs-12 mb-12">
				<span class="apt-error apt-show"><?php echo __("No provider found with enable status","apt");?></span>							
			</li>												
			<?php }			
			?>
		</ul>
	</div>
	<?php }else{ ?>
	<div id="cus-select-staff" class="cus-select-staff fullwidth custom-input nmt">
		<div class="common-selection-main staff-selection">
			<div class="selected-is select-staff" title="<?php echo __("Choose service provider","apt");?>">
				<div class="data-list" id="selected_custom_staff">
					<div class="apt-value"><?php echo __("Choose service provider","apt");?></div>
				</div>
			</div>
			<ul id="apt_staffs" class="common-data-dropdown staff-dropdown custom-dropdown">												
				<?php
				if(sizeof($service_staffs)>0){
					foreach($service_staffs as $aptstaff){ ?>
							<li class="data-list select_staff" data-staffid="<?php echo $aptstaff['id'];?>">
								<div class="apt-value" ><?php echo $aptstaff['staff_name']; ?></div>
							</li>
						 <?php														
					 }	
				}else{ ?>
				<li class="data-list select_custom" data-sid="">
					<div class="apt-value" ><?php echo __("No provider found with enable status","apt");?></div>
				</li>
				<?php } ?>															
			</ul>
		</div>
	</div>
	<i class="bottom-line"></i>		
	<?php } 
}
/* Get Provider Time Slots */
if(isset($_POST['action'],$_POST['selstaffid']) && $_POST['action']=='apt_get_provider_slots' && $_POST['selstaffid']!='')
{
	$bwid = $_POST['bwid'];
	$selecteddate = date_i18n('Y-m-d',$_POST['seldate']);
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
	$booking_dayclosing = get_option('appointment_dayclosing_overlap'.'_'.$bwid);

	
	
	$time_schedule = $first_step->get_day_time_slot_by_provider_id($selectedstaffid,$time_slots_schedule_type,$selecteddate,$time_interval);
	//$time_schedule = array('tst', 'test2');
	
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
			CURLOPT_USERAGENT => 'Appointment'
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
			/* echo $slot; */
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
			
			/* Check for the multiple booking sameslot Enable */
			if(get_option('appointment_multiple_booking_sameslot'.'_'.$bwid) == "D"){	
				if(get_option('appointment_hide_booked_slot'.'_'.$bwid)=='E' && (in_array($complete_time_slot,$time_schedule['booked']) || $gccheck=='Y')) {
					continue;
				}
			}
			$timestamp = strtotime(date_i18n(get_option('date_format'),strtotime($selecteddate))." ".date_i18n(get_option('time_format'),strtotime($slot)));
			$date = date("Y-m-d H:i:s", $timestamp);
			$date2 = substr( $date, 0, -1 );
			global $wpdb;
			$result = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."apt_bookings 
			Where booking_datetime='".$date2."' and business_owner_id='".$bwid."'");
			$counted = count($result);

			

			if(isset($time_schedule['booked']) && (in_array($complete_time_slot,$time_schedule['booked']) || $gccheck=='Y') && (get_option('appointment_multiple_booking_sameslot'.'_'.$bwid)=='D') ) {
				
			?>
				<li class="time-slot br-2 apt-booked" style="background-color: #808080;">
					<?php echo date_i18n(get_option('time_format'),strtotime($slot)); ?>
				</li>
			<?php
				}elseif(get_option('appointment_multiple_booking_sameslot'.'_'.$bwid)=='E' && $counted >= get_option('appointment_slot_max_booking_limit') && get_option('appointment_slot_max_booking_limit'.'_'.$bwid)>0){
				?>
				<li class="time-slot br-2 apt-booked" style="background-color: #808080;">
				<?php echo date_i18n(get_option('time_format'),strtotime($slot)); ?>
				</li>
				<?php
				}
			else {
				
			?>
				<li class="time-slot br-2 time_slotss apt_select_slot" data-slot_db_date="<?php echo date_i18n('Y-m-d',strtotime($selecteddate)); ?>" data-slot_db_time="<?php echo date_i18n("H:i:s",strtotime($slot)); ?>" data-displaydate="<?php echo date_i18n(get_option('date_format'),strtotime($selecteddate)); ?>" data-displaytime="<?php echo date_i18n(get_option('time_format'),strtotime($slot)); ?>" >
					<?php echo date_i18n(get_option('time_format'),strtotime($slot)); ?>
				</li>					
			<?php 
			} $slot_counter++; 
		} 	
	
		if($allbreak_counter == sizeof($time_schedule['slots']) && sizeof($time_schedule['slots'])!=0){ ?>
			<li class="time-slot br-2 apt-none-available fullwidth omar1"><?php echo "None of time slot available for "; echo date_i18n(get_option('date_format'),strtotime($time_schedule['date']))?><?php echo " Please check another dates";?></li>
		<?php }else if(isset($time_schedule['offtimes'],$time_schedule['slots']) && $allofftime_counter > sizeof($time_schedule['offtimes']) && sizeof($time_schedule['slots'])==$allofftime_counter){ ?>
			<li class="time-slot br-2 apt-none-available omar2"><?php echo "None of time slot available for "; echo date_i18n(get_option('date_format'),strtotime($time_schedule['date']))?><?php echo " Please check another dates";?></li>
		<?php }
		else if($gccheck == 'Y') {
			?>
			<li class="time-slot br-2 apt-none-available omar3"><?php echo "None of time slot available for "; echo date_i18n(get_option('date_format'),strtotime($time_schedule['date']))?><?php echo " Please check another dates";?></li>
		<?php } /* */
		
		} else {
			?>
			<li class="time-slot br-2 apt-none-available omar4"><?php echo "None of time slot available for "; echo date_i18n(get_option('date_format'),strtotime($time_schedule['date']))?><?php echo " Please check another dates";?></li>
		<?php }
}


/* Previous/Next Month Calendar */
if(isset($_POST['action'],$_POST['calmonth'],$_POST['calyear']) && $_POST['action']=='apt_cal_next_prev' && $_POST['calmonth']!='' && $_POST['calyear']!='')
{
	$bwid = $_POST['bwid'];
	$month= $_POST['calmonth'];
	$year= $_POST['calyear'];
	$currentdate = mktime(12, 0, 0,$month, 1,$year);	
	$calnextmonth = strtotime('+1 month',$currentdate);
	$calprevmonth=strtotime('-1 month', $currentdate);
	$apt_maxadvance_booktime = get_option('appointment_maximum_advance_booking'.'_'.$bwid);
	$calmaxdate = strtotime('+'.$apt_maxadvance_booktime.' month',strtotime(date_i18n("Y-m-d")));	
	$monthdays = date_i18n("t", $currentdate);
	$offset = date_i18n("w", $currentdate);
	$rows = 1;
	
	$prevmonthlink =  strtotime(date_i18n("Y-m-d",$currentdate));
	$currrmonthlink =  strtotime(date_i18n("Y-m-d"));
	?>
	<div class="calendar-header">
		<?php if($currrmonthlink < $prevmonthlink){ ?>
		<a class="previous-date apt_month_change" data-curmonth="<?php echo date_i18n('m');?>" data-curyear="<?php echo date_i18n('Y');?>" data-calyear="<?php echo date_i18n("Y", $calprevmonth); ?>" data-calmonth="<?php echo date_i18n("m", $calprevmonth); ?>" data-calaction="prev" href="javascript:void(0)"><i class="icon-arrow-left icons"></i></a>
		<?php }else{ ?>
			<a data-curmonth="<?php echo date_i18n('m');?>" data-curyear="<?php echo date_i18n('Y');?>" class="previous-date" href="javascript:void(0)"><i class="icon-arrow-left icons"></i></a>
		<?php } ?>
		<div class="calendar-title"><?php echo date_i18n('F',$currentdate); ?></div>		
		<div class="calendar-year"><?php echo date_i18n('Y',$currentdate); ?></div>
		
		<?php
		if(date_i18n('M',$calmaxdate) == date_i18n('M',$currentdate) && date_i18n('Y',$calmaxdate) == date_i18n('Y',$currentdate)){ ?>
		<a class="next-date" href="javascript:void(0)"><i class="icon-arrow-right icons"></i></a>
		<?php }else{ ?>
		<a class="next-date apt_month_change" data-calyear="<?php echo date_i18n("Y", $calnextmonth); ?>" data-calmonth="<?php echo date_i18n("m", $calnextmonth); ?>" data-calaction="next" href="javascript:void(0)"><i class="icon-arrow-right icons"></i></a>
		<?php } ?>	
	</div><!-- end calendar-header -->	
			<div class="calendar-body fullwidth">
				<div class="weekdays fullwidth">
					<div class="apt-day ">
						<span><?php echo __("Sun","apt");?></span>
					</div>												
					<div class="apt-day">
						<span><?php echo __("Mon","apt");?></span>
					</div>
					<div class="apt-day">
						<span><?php echo __("Tue","apt");?></span>
					</div>
					<div class="apt-day">
						<span><?php echo __("Wed","apt");?></span>
					</div>
					<div class="apt-day">
						<span><?php echo __("Thu","apt");?></span>
					</div>
					<div class="apt-day">
						<span><?php echo __("Fri","apt");?></span>
					</div>
					<div class="apt-day apt-last-day">
						<span><?php echo __("Sat","apt");?></span>
					</div>													
				</div><!-- end row -->
				
			<div class="dates">	
			<?php /*<div class="dates">*/
													  
			  for($i = 1; $i <= $offset; $i++)
			  {	?>
				<div class="apt-week inactive"></div>
			  <?php
			  } 
			  $rowtemparray = array();
			  $k = 0;
			  for($day = 1; $day <= $monthdays; $day++)
			  {
				$selected_dates = $day."-".$month."-".$year;
				$calsel_date = strtotime($selected_dates);
				$calcurr_date = strtotime(date_i18n('d-m-Y'));
				?>
				
				<?php
				if( ($day + $offset - 1) % 7 == 0 && $day != 1){
				   $k = $k+7;
				  ?>
				  </div> 
				  <!--<div class="apt-week"></div> -->
				  
				  <div class="apt-show-time curr_selected_row<?php echo $k;?>">
						<div class="time-slot-container">
							<div class="apt-slot-legends">
								<ul class="apt-legends-ul">
									<li><span class="apt-slot-legends-box apt-available-new"></span><?php echo __("Available","apt");?></li>
									<li><span class="apt-slot-legends-box apt-selected-new"></span><?php echo __("Selected","apt");?></li>
									<li><span class="apt-slot-legends-box apt-not-available-new"></span><?php echo __("Not Available","apt");?></li><br>
								</ul>
							</div>
							<ul class="list-inline time-slot-ul apt_day_slots"></ul>
						</div>
					</div>
				  <div class="dates">												  
				  <?php
				  $rows++;
				}		
				?>
				<div data-seldate="<?php echo $calsel_date;?>" data-calrowid="<?php if($day < 35){echo $k+7; }else{echo $k;} ?>"  class="apt-week <?php if($calsel_date==$calcurr_date){ echo 'by_default_today_selected';} if($calsel_date<$calcurr_date || $calsel_date>$calmaxdate){ echo 'inactive';} ?> apt-slots-count" title="20 Available"><a href="javascript:void(0)"><span><?php echo $day; ?></span></a></div> 
				<?php
			}
			
			/* while( ($day + $offset) <= $rows * 7)
			{
				?>
				<div class="apt-week "></div>									
				<?php
				 $day++;
			} */
			?>	
			</div>
			<div class="apt-show-time curr_selected_row<?php echo $k+7;?>">
				<div class="time-slot-container">
					<div class="apt-slot-legends">
						<ul class="apt-legends-ul">
							<li><span class="apt-slot-legends-box apt-available-new"></span><?php echo __("Available","apt");?></li>
							<li><span class="apt-slot-legends-box apt-selected-new"></span><?php echo __("Selected","apt");?></li>
							<li><span class="apt-slot-legends-box apt-not-available-new"></span><?php echo __("Not Available","apt");?></li><br>
						</ul>
					</div>
					<ul class="list-inline time-slot-ul apt_day_slots"></ul>
				</div>
			</div>
			<div class="today-date">
				<a class="apt-button apt-button  today_btttn apt-lg-offset-1" data-smonth="<?php echo $month;?>" data-syear="<?php echo $year;?>"><?php echo __("Today","apt");?></a>
				<div class="apt-selected-date-view apt-lg-pull-1 apt-hide">
					<span class="custom-check">
						<span class="add_date apt-date-selected"></span>
						<span class="add_time apt-time-selected"></span>
					</span>
				</div>
			</div>
			<!-- end calendar-wrapper -->
		</div>
	</div>
<?php	
}



/* Set Item Into Cart */
if((isset($_POST['action'],$_POST['selected_location'],$_POST['selected_service'],$_POST['selected_staff'],$_POST['service_addon_st'],$_POST['selected_datetime']) && $_POST['action']=='add_item_into_cart' && $_POST['selected_location']!='' && $_POST['selected_service']!='' && $_POST['selected_staff']!='' && $_POST['selected_datetime']!='') || (isset($_POST['action']) && $_POST['action']=='refresh_sidebar'))
{	
	$bwid = $_POST['bwid'];
	$apt_booking_summary = '';
if($_POST['action']!='refresh_sidebar'){
	$itemrandom_number = rand(1000, 9999);
	/* Booking Summary HTML */
	$apt_booking_summary = '<div class="booking-list br-3 fullwidth">
						<a class="apt-delete-booking-box apt_remove_item" data-cartitemid="'.$itemrandom_number.'" href="javascript:void(0)">'.__("Delete","apt").'</a>
						<div class="right-booking-details apt-md-12 apt-sm-12 apt-xs-12 np pull-left">';
	
	/* POST Data Variables */	
	//apt_booking_summary;
	$apt_booking_summary .= 'test in front ajax <br/>';
	
	$apt_booking_summary . json_encode($_POST) . '<br/>';
	
	
	$locationid = $_POST['selected_location'];
	$serviceid = $_POST['selected_service'];
	$staffid = $_POST['selected_staff'];
	$selected_datetime = strtotime($_POST['selected_datetime']);
	$service_addon_st = $_POST['service_addon_st'];
	$total_price = 0;
	$service_amount = 0;
	$apt_mulitlocation_status = get_option('appointment_multi_location'.'_'.$bwid);
	//$bwid = $_POST['bwid']; 
	/* Get Location Information If Enabled */
	if(($locationid!=0 || $locationid!='X') && $apt_mulitlocation_status=='E'){
		$apt_location->id = $locationid;
		$apt_locationinfo = $apt_location->readOne();
		$apt_booking_summary .= '<div class="common-style location-title fullwidth"><i class="icon-location-pin icons"></i>'.$apt_locationinfo[0]->address.', '.$apt_locationinfo[0]->city.' '.$apt_locationinfo[0]->state.' '.$apt_locationinfo[0]->zip.','.$apt_locationinfo[0]->country.' </div>';
		
	}
	
	/* Get Service Info */
	$apt_service->id = $serviceid;
	$apt_service->readOne();
	$service_title = $apt_service->service_title;
	$service_duration = $apt_service->duration;
	$service_amount = $apt_service->amount;
	if($apt_service->offered_price!=''){
		$service_amount = $apt_service->offered_price;
	}
	$service_starttime = $selected_datetime;
	$service_endtime = strtotime('+'.$service_duration.' minutes',$selected_datetime);
	
	
	/* Get Selected Provider Information */
	$apt_staff->id = $staffid;
	$staff_info = $apt_staff->readOne();
	
	
	/* Check If Service Slot Specific Price is Enabled */
	if($staff_info[0]["schedule_type"]=='W'){	
	$weekid = 1;	
	}else{
	
	$weekid = $first_step->get_week_of_month_by_date(date_i18n('Y-m-d',$selected_datetime));
	}
	$weekdayid = date_i18n('N',$selected_datetime);
	$apt_service_schedule_price->provider_id = $staffid;
	$apt_service_schedule_price->service_id = $serviceid;
	$apt_service_schedule_price->weekid = $weekid;
	$apt_service_schedule_price->weekdayid = $weekdayid;
	$serviceprice_infos = $apt_service_schedule_price->readOne_ssp();

	if(sizeof($serviceprice_infos)>0){
		foreach($serviceprice_infos as $serviceprice_info){
			$ssp_starttime = $serviceprice_info->ssp_starttime;
			$ssp_startend = $serviceprice_info->ssp_endtime;
			if(strtotime(date_i18n('H:i:s',$service_starttime)) >= strtotime($ssp_starttime) && strtotime(date_i18n('H:i:s',$service_endtime)) <= strtotime($ssp_startend)){
				$service_amount = $serviceprice_info->price;
			}		
		}
	}
	
	/* Service Booking Summary */
	$apt_booking_summary .= '<div class="common-style fullwidth">
								<i class="icon-settings icons"></i><div class="apt-xs-9 np apt-left-text service-title">'.$service_title.'</div>
								<div class="apt-xs-3 np apt-right-text text-right service-price">'.$service_amount.$apt_general->apt_price_format($service_amount).'</div>
							</div>';
	
	
	
	
	
	/* If Selected Service Addon is Enabled Get Selected Addons Information */
	$addon_price = 0;
	$service_addon_total = 0;
	$apt_selected_service_addons = '';
	$eachaddonprice = array();
	$service_addons = array();
	if($_POST['service_addon_st']=='E'){
	  if(isset($_POST['serviceaddons']) && sizeof($_POST['serviceaddons'])>0){
		foreach($_POST['serviceaddons'] as $selectedaddon){
			$addon_id = $selectedaddon['addonid'];
			$addon_qty = $selectedaddon['maxqty'];
			$apt_service->addon_id = $addon_id;
			$apt_addoninfo = $apt_service->readOne_addon();
			$addon_price = $apt_addoninfo[0]-> 	base_price;
			
			if($apt_addoninfo[0]->multipleqty=='Y'){
				$apt_service->addon_service_id = $addon_id;
				$get_addonpricingrules = $apt_service->readall_qty_addon();	
				if(sizeof($get_addonpricingrules)>0){
					foreach($get_addonpricingrules as $get_addonpricingrule){
						if($get_addonpricingrule->rules=='E' && $get_addonpricingrule->unit==$addon_qty){
							$addon_price = $get_addonpricingrule->rate;
						}elseif($get_addonpricingrule->rules=='G' && $get_addonpricingrule->unit<=$addon_qty){
								$addon_price = $get_addonpricingrule->rate;							
						}
					}
				}
			}
			
			$eachaddonprice[] = array('addonid'=>$addon_id,'addon_price'=>$addon_price); 
			$service_addons[] = array('addonid'=>$addon_id,'maxqty'=>$addon_qty); 
			$service_addon_total = $addon_qty*$addon_price;
			/* $service_addon_total += $addon_price; */			
			$apt_selected_service_addons .= '<li class="apt-es">
											<i class="icon-minus icons apt-delete-icon"></i><div class="apt-xs-9 np apt-left-text service-title">'.$apt_addoninfo[0]->addon_service_name.'</div><div class="apt-xs-3 np apt-right-text text-right service-price">'.$apt_general->apt_price_format($addon_price).'</div>
											<a data-cartitemid="'.$itemrandom_number.'" data-addonid="'.$addon_id.'" class="apt-delete-confirm apt_remove_addon" href="javascript:void(0)">'.__("Delete","apt").'</a>
										</li>';			
		}
	}	
	$apt_booking_summary .= '<div class="common-style fullwidth">
								<i class="icon-settings icons"></i><div class="apt-xs-9 np apt-left-text service-title">'.__("Extra Services","apt").'</div>
								<div class="apt-xs-3 np apt-right-text text-right service-price">'.$apt_general->apt_price_format($service_addon_total).'</div>								
								<div class="apt-extra-services-main mb-5 fullwidth">
									<ul class="extra-services-items fullwidth">';	
									
	$apt_booking_summary .= $apt_selected_service_addons;			
		
	$apt_booking_summary .='</ul>
								</div>
							</div>';		
	}
		
		
	
	/* Display Staff Information */
	$apt_booking_summary .='<div class="common-style date fullwidth"><i class="icon-user icons"></i>'.$staff_info[0]["staff_name"].'</div>';
	
	/* Booking Date & Time Information */
	$apt_booking_summary .='<div class="common-style date fullwidth"><i class="icon-calendar icons"></i>'.date_i18n(get_option('date_format'),$selected_datetime).'</div>	<div class="common-style time fullwidth"><i class="icon-clock icons"></i>'.date_i18n(get_option('time_format'),$service_starttime).' '.__("to","apt").' '.date_i18n(get_option('time_format'),$service_endtime).'</div>';
	
	/* Booking Item Total Price */
	$total_item_price = $service_amount+$service_addon_total;
	$apt_booking_summary .='<div class="price last-item fullwidth">
								<div class="apt-xs-8 np apt-left-text">'.__("Item Price","apt").'</div>
								<div class="apt-xs-4 np apt-right-text text-right service-price">'.$apt_general->apt_price_format($total_item_price).'</div>
							</div>';
	
	$apt_booking_summary .= '</div>
						<div class="delete pull-right apt-delete-booking" title="'.__("Delete Service","apt").'"><span></span></div>
					</div>';
					
	

	
		
	
	/* Set Session OF Cart Item */		
	$apt_cart_item = array();
	$apt_cart_item = array(
		'id'=>$itemrandom_number,
		'selected_location'=>$locationid,
		'selected_service'=>$serviceid,
		'selected_staff'=>$staffid,
		'selected_datetime'=>$selected_datetime,
		'selected_enddatetime'=>$service_endtime,
		'total_price'=>$total_item_price,
		'service_price'=>$service_amount,
		'total_addon_price'=>$service_addon_total,
		'each_addon_price'=>$eachaddonprice,
		'service_addon_status'=>$_POST['service_addon_st'],
		'service_addons'=>$service_addons,
		'bwid' => (isset($bwid))?$bwid:''
	);
	
	$_SESSION['apt_cart_item'][$itemrandom_number] = serialize($apt_cart_item); 
	
	if(isset($_SESSION['apt_sub_total'])){
		 $_SESSION['apt_sub_total'] = $_SESSION['apt_sub_total']+$total_item_price;
	}else{
		$_SESSION['apt_sub_total'] = $total_item_price;
	}
}		
	
	

	$apt_amount_summary = '';
	$apt_partial_deposit_summary = '';
	$apt_partial_deposit_status = get_option('appointment_partial_deposit_status'.'_'.$bwid);
	$apt_taxvat_status = get_option('appointment_taxvat_status'.'_'.$bwid);
	if(isset($_SESSION['apt_sub_total'])){
		
		/* if($_POST['action']!='refresh_sidebar'){ */		
			/* Tax Wat Information */
			$apt_taxvat = 0;		
			if($apt_taxvat_status=='E'){
				$apt_taxvat_type = get_option('appointment_taxvat_type'.'_'.$bwid);
				$apt_taxvat_amount = get_option('appointment_taxvat_amount'.'_'.$bwid);		
				if($apt_taxvat_type=='P'){
					if($apt_taxvat_amount!=''){
						$apt_taxvat = $_SESSION['apt_sub_total']*$apt_taxvat_amount/100;
					}
				}else{
					if($apt_taxvat_amount!=''){
						$apt_taxvat = $apt_taxvat_amount;
					}	
				}
				
			}	
			$_SESSION['apt_taxvat'] = $apt_taxvat;	
			
			
			/* Partial Deposit Information */
			$apt_partialdeposit = 0;
			$apt_partialdeposit_remaining = 0;		
			if($apt_partial_deposit_status=='E'){
				$apt_partial_deposit_type = get_option('appointment_partial_deposit_type'.'_'.$bwid);
				$apt_partial_deposit_amount = get_option('appointment_partial_deposit_amount'.'_'.$bwid);		
				if($apt_taxvat_type=='P'){
					if($apt_partial_deposit_amount!=''){
						$apt_partialdeposit = ($_SESSION['apt_sub_total']+$_SESSION['apt_taxvat'])*$apt_partial_deposit_amount/100;
						$apt_partialdeposit_remaining = $_SESSION['apt_sub_total']+$_SESSION['apt_taxvat']-$apt_partialdeposit;
					}
				}else{
					if($apt_partial_deposit_amount!=''){
						$apt_partialdeposit = $apt_partial_deposit_amount;
						$apt_partialdeposit_remaining = $_SESSION['apt_sub_total']+$_SESSION['apt_taxvat']-$apt_partialdeposit;
					}	
				}
				
			}
			
			$_SESSION['apt_partialdeposit'] = $apt_partialdeposit;  
			$_SESSION['apt_partialdeposit_remaining'] = $apt_partialdeposit_remaining;  
			/* $_SESSION['apt_nettotal'] = $apt_taxvat+$_SESSION['apt_sub_total']; */
			
		/* } */
		
		$subtotalamount = $_SESSION['apt_sub_total'];
		$_SESSION['apt_nettotal'] = $apt_taxvat+$_SESSION['apt_sub_total'];
		if(isset($_SESSION['apt_coupon_discount'])){ 
			/* $subtotalamount = $_SESSION['apt_sub_total']+$_SESSION['apt_coupon_discount']; */
			$subtotalamount = $_SESSION['apt_sub_total'];
			$_SESSION['apt_nettotal'] = $_SESSION['apt_sub_total']-$_SESSION['apt_coupon_discount'];
		}
		
		
		$apt_amount_summary .= '<div class="apt-xs-12 np">
									<div class="common-amount-text">'.__("Sub Total","apt").'</div>
									<div class="common-amount-price">'.$apt_general->apt_price_format($subtotalamount).'</div>
								</div>													
								<div class="clear"></div>';
		
		if(isset($_SESSION['apt_coupon_discount'])){ 	
		$apt_amount_summary .='<div class="apt-xs-12 np">
									<div class="common-amount-text">'.__("Coupon Discount","apt").'</div>
									<div class="common-amount-price discount-price">-'.$apt_general->apt_price_format($_SESSION['apt_coupon_discount']).'</div>
								</div>';
		}	
		
		if($apt_taxvat_status=='E'){
			$apt_amount_summary .= '<div class="apt-xs-12 np">
									<div class="common-amount-text">'.__("Tax Amount","apt").'</div>
									<div class="common-amount-price">'.$apt_general->apt_price_format($_SESSION['apt_taxvat']).'</div>
								</div>';
		}
			
		$apt_amount_summary .= '<div class="apt-xs-12 npl npr hr-both">
									<div class="common-amount-text total-amount">'.__("Payable Amount","apt").'</div>
									<div class="common-amount-price total-price">'.$apt_general->apt_price_format($_SESSION['apt_nettotal']).'</div>
								</div>';
		
		
		$apt_partial_deposit_summary = '';
		if($apt_partial_deposit_status=='E'){
		$apt_partial_deposit_message = get_option('appointment_partial_deposit_message'.'_'.$bwid);
		$apt_partial_deposit_summary = '<div class="partial-amount-message">'.$apt_partial_deposit_message.'</div>
								<div class="apt-form-row">
									<div class="apt-xs-12 np">
										<div class="common-amount-text">'.__("Partial Deposit","apt").'</div>
										<div class="common-amount-price ">'.$apt_general->apt_price_format($_SESSION['apt_partialdeposit']).'</div>
									</div>
								</div>
								<div class="apt-form-row">
									<div class="apt-xs-12 np">
										<div class="common-amount-text">'.__("Remaining Deposit","apt").'</div>
										<div class="common-amount-price">'.$apt_general->apt_price_format($_SESSION['apt_partialdeposit_remaining']).'</div>
									</div>
								</div>';	
		}
	
	}

	$appointment_show_coupons = get_option('appointment_show_coupons'.'_'.$bwid);
	
	?>
	<div class="apt-sidebar-header">
		<h3 class="header3"><?php echo __("Booking Summary","apt");?><div class="apt-cart-items-count"> <i class="icon-bag icons fs-22 pull-right pr"><span class="apt_badge"><?php if(isset($_SESSION['apt_cart_item']) && sizeof($_SESSION['apt_cart_item'])>0){ echo sizeof($_SESSION['apt_cart_item']); }else{ echo '0'; } ?></span></i></div></h3>
	</div>
	<div id="apt_booking_summary" class="omar sidebar-box <?php if(isset($_SESSION['apt_cart_item']) && sizeof($_SESSION['apt_cart_item'])>0){ echo 'apt_cart_item_exist'; }else{ echo 'apt_cart_item_not_exist'; } ?>">
		<?php 
		
		/* Loop Existing Cart Items */
		if(isset($_SESSION['apt_cart_item']) && sizeof($_SESSION['apt_cart_item'])>0){


			foreach($_SESSION['apt_cart_item'] as $cart_item_detail){
				$cart_item = unserialize($cart_item_detail);
				
				/* POST Data Variables */	
				$locationid = $cart_item['selected_location'];
				$serviceid = $cart_item['selected_service'];
				$staffid = $cart_item['selected_staff'];
				$selected_datetime = $cart_item['selected_datetime'];
				$cartitem_id = $cart_item['id'];
				$service_addon_st = $cart_item['service_addon_status'];
				$service_addons = $cart_item['service_addons'];
				$total_price = 0;			
				$service_amount = 0;
				$bwid = $cart_item['bwid'];			
							
				/* Booking Summary HTML */
				$apt_booking_summary = '<div class="booking-list br-3 fullwidth">
				<a class="apt-delete-booking-box apt_remove_item" data-cartitemid="'.$cartitem_id.'" href="javascript:void(0)">'.__("Delete","apt").'</a>
				<div class="right-booking-details apt-md-12 apt-sm-12 apt-xs-12 np pull-left">';
				
				
				$apt_mulitlocation_status = get_option('appointment_multi_location'.'_'.$bwid);
				/* Get Location Information If Enabled */
				if(($locationid!=0 || $locationid!='X') && $apt_mulitlocation_status=='E'){
					$apt_location->id = $locationid;
					$apt_locationinfo = $apt_location->readOne();
					$apt_booking_summary .= '<div class="common-style location-title fullwidth"><i class="icon-location-pin icons"></i>'.$apt_locationinfo[0]->address.', '.$apt_locationinfo[0]->city.' '.$apt_locationinfo[0]->state.' '.$apt_locationinfo[0]->zip.','.$apt_locationinfo[0]->country.' </div>';
					
				}
				/* Get Service Info */
				$apt_service->id = $serviceid;
				$apt_service->readOne();
				$service_title = $apt_service->service_title;
				$service_duration = $apt_service->duration;
				$service_amount = $apt_service->amount;
				if($apt_service->offered_price!=''){
					$service_amount = $apt_service->offered_price;
				}
				$service_starttime = $selected_datetime;
				$service_endtime = strtotime('+'.$service_duration.' minutes',$selected_datetime);	
				
				
				/* Get Selected Provider Information */
				$apt_staff->id = $staffid;
				$staff_info = $apt_staff->readOne();
				
				
				/* Check If Service Slot Specific Price is Enabled */
				if($staff_info[0]["schedule_type"]=='W'){	
				$weekid = 1;	
				}else{
				$weekid = $first_step->get_week_of_month_by_date(date_i18n('Y-m-d',$selected_datetime));
				}
				$weekdayid = date_i18n('N',$selected_datetime);
				$apt_service_schedule_price->provider_id = $staffid;
				$apt_service_schedule_price->service_id = $serviceid;
				$apt_service_schedule_price->weekid = $weekid;
				$apt_service_schedule_price->weekdayid = $weekdayid;
				$serviceprice_infos = $apt_service_schedule_price->readOne_ssp();
				if(sizeof($serviceprice_infos)>0){
					foreach($serviceprice_infos as $serviceprice_info){
						$ssp_starttime = $serviceprice_info->ssp_starttime;
						$ssp_startend = $serviceprice_info->ssp_endtime;
						if(strtotime(date_i18n('H:i:s',$service_starttime)) >= strtotime($ssp_starttime) && strtotime(date_i18n('H:i:s',$service_endtime)) <= strtotime($ssp_startend)){
							$service_amount = $serviceprice_info->price;
						}		
					}
				}
				/* Service Booking Summary */
					$apt_booking_summary .= '<div class="common-style fullwidth">
						<i class="icon-settings icons"></i><div class="apt-xs-9 np apt-left-text service-title">'.$service_title.'</div>
						<div class="apt-xs-3 np apt-right-text text-right service-price">'.$apt_general->apt_price_format($service_amount).'</div>
					</div>';
				
				/* If Selected Service Addon is Enabled Get Selected Addons Information */
				$addon_price = 0;
				$service_addon_total = 0;
				$apt_selected_service_addons = '';
				$eachaddonprice = array();
				if($service_addon_st=='E' && sizeof($service_addons)>0){		
					foreach($service_addons as $selectedaddon){
						//echo "<pre>"; print_r($selectedaddon);
						$addon_id = $selectedaddon['addonid'];
						$addon_qty = $selectedaddon['maxqty'];
						$apt_service->addon_id = $addon_id;
						$apt_addoninfo = $apt_service->readOne_addon();
						$addon_price = $apt_addoninfo[0]->base_price;
						//print_r($addon_price);
						if($apt_addoninfo[0]->multipleqty=='Y'){
							$apt_service->addon_service_id = $addon_id;
							$get_addonpricingrules = $apt_service->readall_qty_addon();	
							// print_r($get_addonpricingrules); 
							if(sizeof($get_addonpricingrules)>0){
								foreach($get_addonpricingrules as $get_addonpricingrule){
									if($get_addonpricingrule->rules=='E' && $get_addonpricingrule->unit==$addon_qty){
										$addon_price = $get_addonpricingrule->rate;
										  //print_r($addon_price);  
									}elseif($get_addonpricingrule->rules=='G' && $get_addonpricingrule->unit<=$addon_qty){
											$addon_price = $get_addonpricingrule->rate;								
									}
								}
							}
						}
						$eachaddonprice[] = array('addonid'=>$addon_id,'addon_price'=>$addon_price); 
						$service_addon_total = $addon_qty*$addon_price;	 
						$apt_selected_service_addons .= '<li class="apt-es">
														<i class="icon-minus icons apt-delete-icon"></i><div class="apt-xs-9 np apt-left-text service-title">'.$apt_addoninfo[0]->addon_service_name.'</div><div class="apt-xs-3 np apt-right-text text-right service-price">'.$apt_general->apt_price_format($addon_price).'</div>
														<a data-cartitemid="'.$cartitem_id.'" data-addonid="'.$addon_id.'" class="apt-delete-confirm apt_remove_addon" href="javascript:void(0)">'.__("Delete","apt").'</a>
													</li>';			
					}
					
				$apt_booking_summary .= '<div class="common-style fullwidth">
											<i class="icon-settings icons"></i><div class="apt-xs-9 np apt-left-text service-title">'.__("Extra Services","apt").'</div>
											<div class="apt-xs-3 np apt-right-text text-right service-price">'.$apt_general->apt_price_format($service_addon_total).'</div>								
											<div class="apt-extra-services-main mb-5 fullwidth">
												<ul class="extra-services-items fullwidth">';	
												
				$apt_booking_summary .= $apt_selected_service_addons;			
					
				$apt_booking_summary .='</ul>
											</div>
										</div>';		
				}
				
				/* Display Staff Information */
				$apt_booking_summary .='<div class="common-style date fullwidth"><i class="icon-user icons"></i>'.$staff_info[0]["staff_name"].'</div>';
				
				/* Booking Date & Time Information */
				$apt_booking_summary .='<div class="common-style date fullwidth"><i class="icon-calendar icons"></i>'.date_i18n(get_option('date_format'),$selected_datetime).'</div>	<div class="common-style time fullwidth"><i class="icon-clock icons"></i>'.date_i18n(get_option('time_format'),$service_starttime).' '.__("to","apt").' '.date_i18n(get_option('time_format'),$service_endtime).'</div>';
				
				
				/* Booking Item Total Price */
				$total_item_price = $service_amount+$service_addon_total;
				$apt_booking_summary .='<div class="price last-item fullwidth">
											<div class="apt-xs-8 np apt-left-text">'.__("Item Price","apt").'</div>
											<div class="apt-xs-4 np apt-right-text text-right service-price">'.$apt_general->apt_price_format($total_item_price).'</div>
										</div>';
				
				$apt_booking_summary .= '</div>
									<div class="delete pull-right apt-delete-booking" title="'.__("Delete Service","apt").'"><span></span></div>
								</div>';			
				
				if(get_option('booking_cart_description')=='E')
					{
					echo $apt_booking_summary;
					}
			}				
		}else { ?>
			<h2 class="apt-empty-cart"><i class="icon-handbag icons"></i> <?php echo __("Your Cart is Empty!","apt");   ?></h2> <?php die(); ?>
			
		<?php } ?>				
	</div>
	
	<div class="apt-button-container text-center apt-add-more-btn">
		<a class="apt-button pull-left" id="btn-more-bookings" href="javascript:void(0)"><i class="icon-arrow-left icons"></i><?php echo __("Add more","apt");?></a>					
	</div>				
	
	
	<div class="apt-checkout-content">				
		
		
		<div class="sidebar-box">	
			<div class="clear"></div>
			<div id="apt_amount_summary" class="apt-total-amount">
				<?php echo $apt_amount_summary;?>
				<div class="clear"></div>
			</div>
			
		</div>
		
		<?php if($appointment_show_coupons=='E'){ ?>	
		<div class="apt-discount-partial fullwidth">
			<div class="discount-coupons fullwidth">
				<?php if(!isset($_SESSION['apt_coupon_discount'])){ ?>
				<div class="apt-form-row apt-md-12 apt-lg-12 apt-sm-12 apt-xs-12 np">
					<div class="pr coupon-input">
						<input type="text" class="custom-input coupon-input-text" id="apt-coupon" />
						<a href="javascript:void(0);" id="apt_apply_coupon" data-action="apply" class="apt-link apply-coupon" ><?php echo __("Apply","apt");?></a>
						<label class="custom apt-coupon-label"><?php echo __("Have a Promocode?","apt");?></label>
						<i class="bottom-line"></i>
					</div> 
					<span class="apt-error apt_promocode_error"><?php echo __("Invalid Coupon code","apt");?></span>
				</div>
				<?php } ?>
				
				<!-- display coupon -->
				<?php if(isset($_SESSION['apt_coupon_discount'])){ ?>
				<div class="display-coupon-code">
					<div class="apt-form-row fullwidth">	
						<div class="apt-xs-7">
							<label class="apt-relative apt_promocode_success"><?php echo __("Applied Promocode","apt");?></label>
						</div>
						<div class="apt-xs-5 pull-right">
							<div class="coupon-value-main">
								<span class="coupon-value br-2 "><?php echo $_SESSION['apt_coupon_code'];?></span>
								<i class="icon-close icons br-100"  data-action="reverse" id="remove_applied_coupon"  title="Remove applied coupon" ></i>
						
							</div>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>						
		</div>
		<?php } ?>
		
		<?php if($apt_partial_deposit_status=='E'){ ?>			
		<div class="apt-discount-partial fullwidth">
			<div id="apt_partial_deposit_summary" class="partial-amount-wrapper br-2 cb">
				<?php echo $apt_partial_deposit_summary;?>
			</div>
		</div>
		<?php } ?>
		<div class="apt-button-container text-center fullwidth">
			<a class="apt-button btn-x-large omar" id="btn-third-step" href="javascript:void(0)"><?php echo __("Checkout","apt");?></a>
		</div>
	</div>
	<?php
				if(get_option('appointment_payment_method_Payumoney'.'_'.$bwid) == 'E'){
				?>
            <!--<form action="https://secure.payu.in/_payment" method="post" name="payuForm" id="payuForm">-->
            <form action="https://sandboxsecure.payu.in/_payment" method="post" class="omar" name="payuForm" id="payuForm">
				<input type="hidden" name="key" id="payu_key" value="" />
				<input type="hidden" name="hash" id="payu_hash" value=""/>
				<input type="hidden" name="txnid" id="payu_txnid" value="" />
				<input type="hidden" name="amount" id="payu_amount" value="" />
				<input type="hidden" name="firstname" id="payu_fname" value="" />
				<input type="hidden" name="email" id="payu_email" value="" />
				<input type="hidden" name="phone" id="payu_phone" value="" />
				<input type="hidden" name="productinfo" id="payu_productinfo" value="" />
				<input type="hidden" name="surl" id="payu_surl" value="" />
				<input type="hidden" name="furl" id="payu_furl" value="" />
				<input type="hidden" name="service_provider" id="payu_service_provider" value="" />
			</form>
			
<?php	
}
	if(get_option('appointment_payment_method_Paytm'.'_'.$bwid) == 'E'){
		?>
		<form method="post" action="" name="apt_paytm_form" id="apt_paytm_form">
			<input type="hidden" id="apt_CHECKSUMHASH" name="CHECKSUMHASH" value="">
		</form>
		<?php
	}

}

			?>
<?php
/* Delete Cart Item */
if(isset($_POST['action'],$_POST['cartitemid']) && $_POST['action']=='apt_delete_cart_item' && $_POST['cartitemid']!='' && isset($_SESSION['apt_cart_item'])){
	
	$bwid = $_POST['bwid'];
	$cartitem_id = $_POST['cartitemid'];
	$deleteitem_info  = unserialize($_SESSION['apt_cart_item'][$cartitem_id]);
	$_SESSION['apt_sub_total'] = $_SESSION['apt_sub_total']-$deleteitem_info['total_price'];
	
	/* Tax Wat Information */
	$apt_taxvat = 0;
	$apt_taxvat_status = get_option('appointment_taxvat_status'.'_'.$bwid);
	if($apt_taxvat_status=='E'){
		$apt_taxvat_type = get_option('appointment_taxvat_type'.'_'.$bwid);
		$apt_taxvat_amount = get_option('appointment_taxvat_amount'.'_'.$bwid);		
		if($apt_taxvat_type=='P'){
			if($apt_taxvat_amount!=''){
				$apt_taxvat = $_SESSION['apt_sub_total']*$apt_taxvat_amount/100;
			}
		}else{
			if($apt_taxvat_amount!=''){
				$apt_taxvat = $apt_taxvat_amount;
			}	
		}
		
	}	
	$_SESSION['apt_taxvat'] = $apt_taxvat;	
	
	
	/* Partial Deposit Information */
	$apt_partialdeposit = 0;
	$apt_partialdeposit_remaining = 0;
	$apt_partial_deposit_status = get_option('appointment_partial_deposit_status'.'_'.$bwid);
	if($apt_partial_deposit_status=='E'){
		$apt_partial_deposit_type = get_option('appointment_partial_deposit_type'.'_'.$bwid);
		$apt_partial_deposit_amount = get_option('appointment_partial_deposit_amount'.'_'.$bwid);		
		if($apt_taxvat_type=='P'){
			if($apt_partial_deposit_amount!=''){
				$apt_partialdeposit = ($_SESSION['apt_sub_total']+$_SESSION['apt_taxvat'])*$apt_partial_deposit_amount/100;
				$apt_partialdeposit_remaining = $_SESSION['apt_sub_total']+$_SESSION['apt_taxvat']-$apt_partialdeposit;
			}
		}else{
			if($apt_partial_deposit_amount!=''){
				$apt_partialdeposit = $apt_partial_deposit_amount;
				$apt_partialdeposit_remaining = $_SESSION['apt_sub_total']+$_SESSION['apt_taxvat']-$apt_partialdeposit;
			}	
		}
		
	}
	
	$_SESSION['apt_partialdeposit'] = $apt_partialdeposit;  
	$_SESSION['apt_partialdeposit_remaining'] = $apt_partialdeposit_remaining;  
	$_SESSION['apt_nettotal'] = $apt_taxvat+$_SESSION['apt_sub_total'];	
	/* $_SESSION['service_addon_total']=$service_addon_total; */
	unset($_SESSION['apt_cart_item'][$cartitem_id]);
	
	if(isset($_SESSION['apt_cart_item']) && sizeof($_SESSION['apt_cart_item'])==0){	
		unset($_SESSION['apt_partialdeposit']);
		unset($_SESSION['apt_partialdeposit_remaining']);
		unset($_SESSION['apt_nettotal']);
		unset($_SESSION['apt_taxvat']);
		unset($_SESSION['apt_sub_total']);
		unset($_SESSION['service_addon_total']);
		unset($_SESSION['apt_coupon_id']);
		unset($_SESSION['apt_coupon_code']);
		unset($_SESSION['apt_coupon_discount']);
	}	
}

/* Delete Addon From Cart Item Item */
if(isset($_POST['action'],$_POST['addonid'],$_POST['cartitemid']) && $_POST['action']=='apt_delete_addon' && $_POST['cartitemid']!='' && $_POST['addonid']!=''){
	$bwid = $_POST['bwid'];
	$cartitem_id = $_POST['cartitemid'];
	$deleteitem_info  = unserialize($_SESSION['apt_cart_item'][$cartitem_id]);
	
	$addondelete_itemtotal = $deleteitem_info['total_price'];
	$removeaddonprice = 0;
	if(sizeof($deleteitem_info['each_addon_price'])>0){
		$removeaddonpri = 0;
		$removeaddon_qty = 0;
		foreach($deleteitem_info['each_addon_price'] as $addonarraykey => $addonprices){
			if($addonprices['addonid']==$_POST['addonid'] && $addonprices['addon_price']!=''){
				$removeaddonpri = $addonprices['addon_price'];
			}
			if($addonprices['addonid']==$_POST['addonid']){
				unset($deleteitem_info['each_addon_price'][$addonarraykey]);
			}
		}
		
		foreach($deleteitem_info['service_addons'] as $saddonarraykey => $serviceaddons){
			if($serviceaddons['addonid']==$_POST['addonid']){
				$removeaddon_qty = $serviceaddons['maxqty'];
				unset($deleteitem_info['service_addons'][$saddonarraykey]);
			}
		}
		
		$removeaddonprice = $removeaddon_qty * $removeaddonpri;
		$addondelete_itemtotal = $deleteitem_info['total_price']-$removeaddonprice;
		
	}

	$_SESSION['apt_cart_item'][$cartitem_id] = serialize(array('id'=>$deleteitem_info['id'],'selected_location'=>$deleteitem_info['selected_location'],'selected_service'=>$deleteitem_info['selected_service'],'selected_staff'=>$deleteitem_info['selected_staff'],'selected_datetime'=>$deleteitem_info['selected_datetime'],'selected_enddatetime'=>$deleteitem_info['selected_enddatetime'],'total_price'=>$addondelete_itemtotal,'service_price'=>$deleteitem_info['service_price'],'total_addon_price'=>$deleteitem_info['total_addon_price'],'each_addon_price'=>$deleteitem_info['each_addon_price'],'service_addon_status'=>$deleteitem_info['service_addon_status'],'service_addons'=>$deleteitem_info['service_addons']));
	
	$_SESSION['apt_sub_total'] = $_SESSION['apt_sub_total']-$removeaddonprice;
	/* Tax Wat Information */
	$apt_taxvat = 0;
	$apt_taxvat_status = get_option('appointment_taxvat_status'.'_'.$bwid);
	if($apt_taxvat_status=='E'){
		$apt_taxvat_type = get_option('appointment_taxvat_type'.'_'.$bwid);
		$apt_taxvat_amount = get_option('appointment_taxvat_amount'.'_'.$bwid);		
		if($apt_taxvat_type=='P'){
			if($apt_taxvat_amount!=''){
				$apt_taxvat = $_SESSION['apt_sub_total']*$apt_taxvat_amount/100;
			}
		}else{
			if($apt_taxvat_amount!=''){
				$apt_taxvat = $apt_taxvat_amount;
			}	
		}
		
	}	
	$_SESSION['apt_taxvat'] = $apt_taxvat;	
	
	
	/* Partial Deposit Information */
	$apt_partialdeposit = 0;
	$apt_partialdeposit_remaining = 0;
	$apt_partial_deposit_status = get_option('appointment_partial_deposit_status'.'_'.$bwid);
	if($apt_partial_deposit_status=='E'){
		$apt_partial_deposit_type = get_option('appointment_partial_deposit_type'.'_'.$bwid);
		$apt_partial_deposit_amount = get_option('appointment_partial_deposit_amount'.'_'.$bwid);		
		if($apt_taxvat_type=='P'){
			if($apt_partial_deposit_amount!=''){
				$apt_partialdeposit = ($_SESSION['apt_sub_total']+$_SESSION['apt_taxvat'])*$apt_partial_deposit_amount/100;
				$apt_partialdeposit_remaining = $_SESSION['apt_sub_total']+$_SESSION['apt_taxvat']-$apt_partialdeposit;
			}
		}else{
			if($apt_partial_deposit_amount!=''){
				$apt_partialdeposit = $apt_partial_deposit_amount;
				$apt_partialdeposit_remaining = $_SESSION['apt_sub_total']+$_SESSION['apt_taxvat']-$apt_partialdeposit;
			}	
		}
		
	}	
	$_SESSION['apt_partialdeposit'] = $apt_partialdeposit;  
	$_SESSION['apt_partialdeposit_remaining'] = $apt_partialdeposit_remaining;  
	$_SESSION['apt_nettotal'] = $apt_taxvat+$_SESSION['apt_sub_total'];	
}

/* Apply/Reverse Coupon */
if(isset($_POST['action'],$_POST['couponaction']) && $_POST['action']=='apt_coupon_ar'){
	
	
	if($_POST['couponaction']=='apply'){
		$couponcode = $_POST['coupon_code'];
		$apt_coupons->coupon_code = $couponcode;
		$checkcouponinfos = $apt_coupons->apt_check_applied_coupon();
		
		$bookinglocations = array();
		if(isset($_SESSION['apt_cart_item']) && sizeof($_SESSION['apt_cart_item'])>0){
			foreach($_SESSION['apt_cart_item'] as $cart_item_detail){
				$cart_item = unserialize($cart_item_detail);
				$bookinglocations[] = $cart_item['selected_location'];
				
			}	
		}
		$couponcodelocations = array();
		if(sizeof($checkcouponinfos)>0){
			foreach($checkcouponinfos as $checkcouponinfo){
				$couponcodelocations[] = $checkcouponinfo->location_id;
				
			}
			
		}
		$couponexistance = array_intersect($couponcodelocations,$bookinglocations);
	
		if(sizeof($couponexistance)>0){
			$couponcunter = 0;
			$coupon_location = '';
			$coupon_id = '';
			$coupon_type = '';
			$coupon_value = 0;
			$coupon_used = 0;
			foreach($couponexistance as $couponexistances){
				if($couponcunter==0){
					$coupon_location = $couponexistances;					
				}
				$couponcunter++;
			}	
			foreach($checkcouponinfos as $coupon_detail){
				if($coupon_location==$coupon_detail->location_id){
						$coupon_id = $coupon_detail->id;
						$coupon_type = $coupon_detail->coupon_type;
						$coupon_value = $coupon_detail->coupon_value;
						$coupon_used = $coupon_detail->coupon_used;
				}
			}
			if($coupon_id!='' && $coupon_location!=''){
				if($coupon_type=='P'){				
					$coupon_discount = $_SESSION['apt_sub_total']*$coupon_value/100;	
					$discountedsubtotal = $_SESSION['apt_sub_total']-$coupon_discount;
					if($discountedsubtotal<0){						
						$_SESSION['apt_coupon_discount'] = $_SESSION['apt_sub_total'];
						$_SESSION['apt_nettotal'] = 0;
					}else{
						$_SESSION['apt_nettotal'] = $_SESSION['apt_sub_total']-$coupon_discount;
						$_SESSION['apt_coupon_discount'] = $coupon_discount;	
					}					
					$_SESSION['apt_coupon_id'] = $coupon_id;
					$_SESSION['apt_coupon_code'] = $couponcode;					
					
				}else{	
					
					$discountedsubtotal = $_SESSION['apt_sub_total']-$coupon_value;
					if($discountedsubtotal<0){
						$_SESSION['apt_coupon_discount'] = $_SESSION['apt_sub_total'];
						$_SESSION['apt_nettotal'] = 0;
					}else{
						$_SESSION['apt_nettotal'] = $_SESSION['apt_sub_total']-$coupon_value;
						$_SESSION['apt_coupon_discount'] = $coupon_value;
					}					
					$_SESSION['apt_coupon_id'] = $coupon_id;
					$_SESSION['apt_coupon_code'] = $couponcode;
					
				}
				$apt_coupons->id = $coupon_id;
				$apt_coupons->coupon_used = $coupon_used+1;
				$checkcouponinfos = $apt_coupons->apt_update_coupon_used();
				echo 'ok';die();
			}else{
				echo 'error1';die();
			}	
		}else{
			echo 'error2';die();
		}
	/* Reverse Coupon Code */ 	
	}else{
		if(isset($_SESSION['apt_coupon_id']) && $_SESSION['apt_coupon_id']!=''){
			$apt_coupons->id = $_SESSION['apt_coupon_id'];
			$checkcouponinfos = $apt_coupons->readOne_by_coupon_id();	
			$coupon_id = $checkcouponinfos[0]->id;
			$coupon_used = $checkcouponinfos[0]->coupon_used;
			
			$apt_coupons->id = $coupon_id;
			$apt_coupons->coupon_used = $coupon_used-1;
			$checkcouponinfos = $apt_coupons->apt_update_coupon_used();
				
			$_SESSION['apt_nettotal'] = $_SESSION['apt_sub_total']+$_SESSION['apt_coupon_discount'];
			unset($_SESSION['apt_coupon_id']);
			unset($_SESSION['apt_coupon_code']);
			unset($_SESSION['apt_coupon_discount']);
		}
	}	
} 


/* register , login and booking complete code here START */
if(isset($_POST['action']) && $_POST['action']=='check_existing_username'){
	$email =$_POST['email'];
	$exists = email_exists( $email );
	if (!email_exists($email)) {
		echo "true";
	} else {
		echo "false";
	}
}
if(isset($_POST['action']) && $_POST['action']=='get_existing_user_data'){
	$loginemail = $_POST['uname'];
	$loginpass = $_POST['pwd'];
	
	$user = get_user_by( 'email', $loginemail );
	if ( $user && wp_check_password( $loginpass, $user->data->user_pass, $user->ID) ){
		$user_id = $user->data->ID;
		$user_login = $user->data->user_login;
		wp_set_current_user( $user_id, $user_login );
		wp_set_auth_cookie( $user_id );
		
		$current_user = wp_get_current_user();
		$current_user_name = $current_user->user_login;
		$current_user_email = $current_user->user_email;
		$firstname = $current_user->user_firstname;
		$user_pass = $current_user->user_pass;
		$lastname = $current_user->user_lastname;
		$current_user_id = $current_user->ID ;
		
		$_SESSION['client_apt_name'] = $current_user_name;
		$_SESSION['client_apt_email'] = $current_user_email;
		$_SESSION['client_first_name'] = $firstname;
		$_SESSION['client_last_name'] = $lastname;
		$_SESSION['client_apt_ID'] = $current_user_id;
		
		$current_user_meta = get_user_meta($current_user_id);
		
		$get_data_1  = array("gender"=>$current_user_meta['apt_client_gender'][0] ,"user_id"=>$current_user_id ,"user_email"=>$current_user_email,"password"=>$user_pass,"first_name"=>$firstname,"last_name"=>$lastname,"phone"=>$current_user_meta['apt_client_phone'][0],"address"=>$current_user_meta['apt_client_address'][0],"city"=>$current_user_meta['apt_client_city'][0],"state"=>$current_user_meta['apt_client_state'][0],"notes"=>$current_user_meta['apt_client_notes'][0],"ccode"=>$current_user_meta['apt_client_ccode'][0]);
		
		$get_data_2 = unserialize(unserialize($current_user_meta['apt_client_extra_details'][0]));
	
		$get_data = array_merge($get_data_1, unserialize($get_data_2));
		
		echo $get_userdetails = json_encode($get_data);
	}else{
	   echo "Invalid Username or Password";
	}
}
if(isset($_POST['action']) && $_POST['action']=='apt_logout_user'){
	wp_logout();
	unset($_SESSION['client_apt_name']);
	unset($_SESSION['client_apt_email']);
	unset($_SESSION['client_first_name']);
	unset($_SESSION['client_last_name']);
	unset($_SESSION['client_apt_ID']);
}

if(isset($_POST['action']) && $_POST['action']=='apt_booking_complete'){
	$preff_username 	= $_POST['username'];
	$preff_password 	= $_POST['pwd'];
	$first_name 		= $_POST['fname'];
	$last_name 			= $_POST['lname'];
	$user_phone 		= $_POST['phone'];
	$user_gender 		= $_POST['gender'];
	$user_address 		= $_POST['address'];
	$user_city 			= $_POST['city'];
	$user_state 		= $_POST['state'];
	$user_notes 		= $_POST['notes'];
	$user_ccode 		= $_POST['ccode'];
	$username 			= $_POST['fname'].rand(0,999);
	$bwid 				= $_POST['bwid']; 

	//echo 'test omar bwid: ' . $bwid . '</br>';
	
	if(isset($_POST['dynamic_field_add'])){
		$extra_details = $_POST['dynamic_field_add'];
	}else{
		$extra_details = array();
	}
	$serialize_extra_details = serialize($extra_details);
	
	$stripe_trans_id = '';
	if($_POST['payment_method'] == 'stripe'){
		if (isset($_POST['st_token']) && $_POST['st_token']!='' && $_SESSION['apt_nettotal']!=0) {
			require_once($base.'/assets/stripe/Stripe.php');
			$partialdeposite_status = get_option('appointment_partial_deposit_status'.'_'.$bwid);
			if($partialdeposite_status=='E'){
				$stripe_amt = number_format($_SESSION['apt_partialdeposit'],2,".",',');
			}else{
				$stripe_amt = number_format($_SESSION['apt_nettotal'],2,".",',');
			}
			
			Stripe::setApiKey(get_option("appointment_stripe_secretKey".'_'.$bwid));
			$error = '';
			$success = '';
			try { 
				$striperesponse = Stripe_Charge::create(array("amount" => round($stripe_amt*100),
									"currency" => get_option('appointment_currency'.'_'.$bwid),
									"card" => $_POST['st_token'],
									"description"=>$first_name.' , '.$preff_username
									));
				$stripe_trans_id = 	$striperesponse->id;
			}
			catch (Exception $e) {
				$error = $e->getMessage();
				echo $error;die;
			}
		}
	}
	
	if(isset($_SESSION['apt_coupon_discount']) && $_SESSION['apt_coupon_discount'] != 'undefined' && $_SESSION['apt_coupon_discount'] != '' ){
		$total_discount = @number_format($_SESSION['apt_coupon_discount'],2,".",',');
	}else{
		$total_discount = 0;
	}
	
	$apt_detail = array(
		'preff_username' => $preff_username, 
		'preff_password' => $preff_password, 
		'first_name' => $first_name, 
		'last_name' => $last_name, 
		'user_phone' => $user_phone, 
		'user_gender' => $user_gender, 
		'user_address' => $user_address, 
		'user_city' => $user_city, 
		'user_state' => $user_state, 
		'user_notes' => $user_notes, 
		'user_ccode' => $user_ccode, 
		'serialize_extra_details' => $serialize_extra_details, 
		'apt_user_type' => $_POST['apt_user_type'], 
		'payment_method' => $_POST['payment_method'], 
		'username' => $username, 
		'bwid' => $_POST['bwid'],
		'discount' => @number_format($total_discount, 2, ".", ','));
	
	$_SESSION['apt_detail']=$apt_detail;
	
	/*paypal payment method*/
	if($_POST['payment_method'] == 'paypal'){
		header('location:'.$plugin_url.'/lib/paypal_payment_process.php');
		exit(0);
	}
	/*Stripe payment method*/
	if($_POST['payment_method'] == 'stripe'){
		$_SESSION['apt_detail']['stripe_trans_id'] = $stripe_trans_id;
		header('location:'.$plugin_url.'/lib/apt_front_booking_complete.php');
		exit(0);
	}
	/*Pay Locally payment method*/
	if($_POST['payment_method'] == 'pay_locally'){
		header('location:'.$plugin_url.'/lib/apt_front_booking_complete.php');
		exit(0);
	}
}
/* register , login and booking complete code here END */