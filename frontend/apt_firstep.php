<?php 
if(!session_id()) { @session_start(); }
include_once(dirname(dirname(__FILE__)).'/objects/class_general.php');
include_once(dirname(dirname(__FILE__)).'/objects/class_location.php');
include_once(dirname(dirname(__FILE__)).'/objects/class_service.php');
include_once(dirname(dirname(__FILE__)).'/objects/class_service_schedule_price.php');
include_once(dirname(dirname(__FILE__)).'/objects/class_category.php');
include_once(dirname(dirname(__FILE__)).'/objects/class_provider.php');
include_once(dirname(dirname(__FILE__)).'/objects/class_front_appointment_first_step.php');

$plugin_url_for_ajax = plugins_url('', dirname(__FILE__));

$apt_mulitlocation_status = get_option('appointment_multi_location' . '_' . $atts['bwid']);
$apt_zipcode_booking_status = get_option('appointment_zipcode_booking' . '_' . $atts['bwid']);
$provider_avatar_view = get_option('appointment_show_provider_avatars' . '_' . $atts['bwid']);

$apt_general = new appointment_general();
$apt_location = new appointment_location();
$apt_service = new appointment_service();
$apt_service_schedule_price = new appointment_service_schedule_price();
$apt_category = new appointment_category();
$apt_staff = new appointment_staff();
$first_step = new appointment_first_step();

$onload_location_id = 0;
if($apt_mulitlocation_status=='E'){
	
	$apt_location->business_owner_id = $atts['bwid'];
	$locations = $apt_location->readAll_enable_locations();
	$counter = 0;
	foreach($locations as $location){
		if($counter==0){
			$onload_location_id = $location->id;
			break;
		}				
	}
}


$apt_service->location_id = $onload_location_id;
$aptservices = $apt_service->readAll('');
$services_categories = array();
$location_services = array();
foreach($aptservices as $aptservice){
	if(!in_array($aptservice->category_id, $services_categories)){
		$services_categories[] = $aptservice->category_id;
	}
	$location_services[] = $aptservice->id;
}


/* Load All Providers */
$apt_staff->location_id = $onload_location_id;
$apt_staff->business_owner_id = $atts['bwid'];
$aptstaffs = $apt_staff->readAll();

echo "<style>
	#apt .apt-button{
		color : ".get_option('appointment_bg_text_color' . '_' . $atts['bwid'])." !important;
		background-color: ".get_option('appointment_primary_color'.'_'.$atts['bwid'])." !important;
	}
	#apt .apt-extra-services-main ul.extra-services-items li i.icon-minus.icons{
		color : ".get_option('appointment_secondary_color' . '_' . $atts['bwid'])." !important;
	}
	#apt .apt-extra-services-main ul.extra-services-items li a.apt-delete-confirm,
	#apt aside#content-sidebar .sidebar-box .booking-list a.apt-delete-booking-box{
		color : ".get_option('appointment_bg_text_color' . '_' . $atts['bwid'])." !important;
		background: ".get_option('appointment_primary_color' . '_' . $atts['bwid'])." !important;
	}
	#apt .apt-extra-services-main ul.extra-services-items li a.apt-delete-confirm:hover,
	#apt aside#content-sidebar .sidebar-box .booking-list a.apt-delete-booking-box:hover{
		color : ".get_option('appointment_bg_text_color' . '_' . $atts['bwid'])." !important;
		background: ".get_option('appointment_secondary_color' . '_' . $atts['bwid'])." !important;
	}
	
	
	#apt .apt-button:hover{
		color : ".get_option('appointment_bg_text_color' . '_' . $atts['bwid'])." !important;
		background-color: ".get_option('appointment_secondary_color' . '_' . $atts['bwid'])." !important;
	}
	
	#apt .apt-link,
	#apt .apt-complete-booking-main .apt-link,
	#apt label span.apt-logged-in-user,
	#apt a.apt-logout-user b:hover,
	#apt .service-details.apt-show .apt-close-desc:hover{
		color : ".get_option('appointment_secondary_color' . '_' . $atts['bwid'])." !important;
	}
	#apt{
		color : ".get_option('appointment_text_color' . '_' . $atts['bwid'])." !important;
	}
	#apt h3.block-title{
		color : ".get_option('appointment_secondary_color' . '_' . $atts['bwid'])." !important;
	}
	
	#apt .apt-extra-services-list ul.addon-service-list li input[type='checkbox']:checked label span,
	#apt .apt-service-staff-list ul.staff-list li input[type='checkbox']:checked label span{
		color : ".get_option('appointment_bg_text_color' . '_' . $atts['bwid'])." !important;
	}	
	#apt .apt-addon-count .apt-btn-group .apt-btn-text{
		color : ".get_option('appointment_text_color' . '_' . $atts['bwid'])." !important;
	}
	#apt a.apt-logout-user b,
	#apt .apt-complete-booking-main .apt-link,
	#apt .apt-discount-coupons a.apt-apply-coupon.apt-link {
		color : ".get_option('appointment_primary_color' . '_' . $atts['bwid'])." !important;
	}
	#apt a,
	#apt .apt-button#btn-more-bookings,
	#apt aside#content-sidebar .sidebar-box .booking-list .provider-info .provider-title,
	#apt aside#content-sidebar .sidebar-box .booking-list .right-booking-details .price,
	#apt aside#content-sidebar .sidebar-box .booking-list .right-booking-details .common-style{
		color : ".get_option('appointment_text_color'.'_'.$atts['bwid'])." !important;
	}
	#apt i.bottom-line:after,
	#apt i.bottom-line:before,
	#apt i.icon-close-custom:hover:before,
	#apt i.icon-close-custom:hover:after{
		background: ".get_option('appointment_secondary_color'.'_'.$atts['bwid'])." !important;
	}	
	
	#apt .apt-booking-step{
		border-bottom-color: ".get_option('appointment_primary_color'.'_'.$atts['bwid'])." !important;
	}
	#apt .apt-booking-step ul li.active,
	#apt .apt-booking-step ul li span.sep.active {
		color: ".get_option('appointment_secondary_color'.'_'.$atts['bwid'])." !important;
	}
	#apt .apt-booking-step ul li {
		color: ".get_option('appointment_primary_color'.'_'.$atts['bwid'])." !important;
	}
	
	#apt .apt-loader .apt-first{
		border: 3px solid ".get_option('appointment_bg_text_color'.'_'.$atts['bwid'])." !important;
	}
	#apt .apt-loader .apt-second{
		border: 3px solid ".get_option('appointment_primary_color'.'_'.$atts['bwid'])." !important;
	}
	#apt .apt-loader .apt-third{
		border: 3px solid ".get_option('appointment_secondary_color'.'_'.$atts['bwid'])." !important;
	}
	#apt button{
		color : ".get_option('appointment_bg_text_color'.'_'.$atts['bwid'])." !important;
		background: ".get_option('appointment_primary_color'.'_'.$atts['bwid'])." !important;
	}
	#apt .apt-custom-radio ul.apt-radio-list label span{
		border-color: ".get_option('appointment_primary_color'.'_'.$atts['bwid'])." !important;
	}
	#apt .apt-title-header,
	#apt .apt-sidebar-header{
		color : ".get_option('appointment_bg_text_color'.'_'.$atts['bwid'])." !important;
		background: ".get_option('appointment_secondary_color'.'_'.$atts['bwid'])." !important;
		border-color: ".get_option('appointment_secondary_color'.'_'.$atts['bwid'])." !important;
	}
	#apt .calendar-header{
		background-color: ".get_option('appointment_primary_color'.'_'.$atts['bwid'])." !important;
	}
	#apt .calendar-header a.previous-date,
	#apt .calendar-header a.next-date {
		color : ".get_option('appointment_bg_text_color'.'_'.$atts['bwid'])." !important;
	}
	#apt .custom-checkbox input[type='checkbox']:checked + label .check-icon:before{
		border-left: 2px solid ".get_option('appointment_secondary_color'.'_'.$atts['bwid'])." !important;
		border-bottom: 2px solid ".get_option('appointment_secondary_color'.'_'.$atts['bwid'])." !important;
	}
	#apt .custom-checkbox input[type='checkbox']:checked + label .check-icon {
		border: 1px solid ".get_option('appointment_secondary_color'.'_'.$atts['bwid'])." !important;
	}
	#apt .today-date .apt-selected-date-view .custom-check:before{
		border-left: 2px solid ".get_option('appointment_secondary_color'.'_'.$atts['bwid'])." !important;
		border-bottom: 2px solid ".get_option('appointment_secondary_color'.'_'.$atts['bwid'])." !important;
	}
	#apt .calendar-body .dates .apt-week.by_default_today_selected,
	#apt .calendar-body .apt-show-time .time-slot-container ul li.time-slot,
	#apt .calendar-body .apt-show-time .time-slot-container .apt-slot-legends .apt-available-new{
		background: ".get_option('appointment_primary_color'.'_'.$atts['bwid'])." !important;
	}
	#apt button:hover{
		color: ".get_option('appointment_bg_text_color'.'_'.$atts['bwid'])." !important;
		background: ".get_option('appointment_secondary_color'.'_'.$atts['bwid'])." !important;
	}
	#apt a:hover,
	#apt .apt-button#btn-more-bookings:hover,
	#apt .apt-link:hover,
	#apt .apt-complete-booking-main .apt-link:hover,
	#apt .apt-discount-coupons a.apt-apply-coupon.apt-link:hover {
		color: ".get_option('appointment_secondary_color'.'_'.$atts['bwid'])." !important;
	}
	#apt .weekdays,
	#apt .calendar-wrapper .calendar-header a.next-date:hover,
	#apt .calendar-wrapper .calendar-header a.previous-date:hover,
	#apt .calendar-body .apt-week:hover{
		background: ".get_option('appointment_secondary_color'.'_'.$atts['bwid'])." !important;
	} 
	#apt .calendar-body .apt-week:hover span{
		color: ".get_option('appointment_bg_text_color'.'_'.$atts['bwid'])." !important;
	}
	#apt .calendar-body .apt-show-time .time-slot-container .apt-slot-legends .apt-selected-new {
		background: ".get_option('appointment_secondary_color'.'_'.$atts['bwid'])." !important;
	}	
	#apt .calendar-body .dates .apt-week.active {
		background-color: ".get_option('appointment_secondary_color'.'_'.$atts['bwid'])." !important;
		border-bottom-color: ".get_option('appointment_secondary_color'.'_'.$atts['bwid'])." !important;
	}
	#apt .calendar-body .apt-show-time .time-slot-container ul li.time-slot:hover,
	#apt .calendar-body .apt-show-time .time-slot-container ul li.time-slot.apt-booked,
	#apt .calendar-body .apt-show-time .time-slot-container ul li.time-slot.apt-slot-selected,
	#apt .calendar-body .apt-show-time.shown {
		background-color: ".get_option('appointment_secondary_color'.'_'.$atts['bwid'])." !important;
	}
	#apt aside#content-sidebar .sidebar-box .booking-list .right-booking-details .delete:hover,
	#apt .panel-login .panel-heading .col-xs-6.active{
		color: ".get_option('appointment_bg_text_color'.'_'.$atts['bwid'])." !important;
		background: ".get_option('appointment_primary_color'.'_'.$atts['bwid'])." !important;
	}
	#apt .apt-extra-services-list ul.addon-service-list li input[type='checkbox']:checked + label .addon-price {
		color: ".get_option('appointment_bg_text_color')." !important;
		background-color: ".get_option('appointment_secondary_color'.'_'.$atts['bwid'])." !important;
	}
	#apt .apt-extra-services-list ul.addon-service-list li input[type='checkbox']:checked + .apt-addon-ser,
	#apt .apt-service-staff-list ul.staff-list li input[type='radio']:checked + .apt-staff .apt-staff-img img {
		border-color: ".get_option('appointment_secondary_color'.'_'.$atts['bwid'])." !important;
		box-shadow: 0 0 2px 0px ".get_option('appointment_secondary_color'.'_'.$atts['bwid'])." !important;
	}
	#apt .apt-service-staff-list ul.staff-list li .apt-staff span{
		background-color: ".get_option('appointment_secondary_color'.'_'.$atts['bwid'])." !important;
	}
	#apt .apt-custom-radio ul.apt-radio-list input[type='radio']:checked + label span {
		border: 5px solid ".get_option('appointment_secondary_color'.'_'.$atts['bwid'])." !important;
	}
	#apt aside#content-sidebar .sidebar-box .booking-list .right-booking-details .delete,
	#apt #navbar .booking-steps > li.is-complete,
	#apt .apt-link{
		color: ".get_option('appointment_primary_color'.'_'.$atts['bwid'])." !important;
	}
	#apt aside#content-sidebar .sidebar-box .booking-list:hover .delete span:after{
		color: ".get_option('appointment_bg_text_color'.'_'.$atts['bwid'])." !important;
	}
	#apt aside#content-sidebar .sidebar-box .booking-list .right-booking-details .delete:hover{
		color: ".get_option('appointment_primary_color'.'_'.$atts['bwid'])." !important;
		background: ".get_option('appointment_secondary_color'.'_'.$atts['bwid'])." !important;
	}
	#apt aside#content-sidebar .sidebar-box .booking-list .delete span:before{
		border-right: 40px solid ".get_option('appointment_secondary_color'.'_'.$atts['bwid'])." !important;
	}
	#apt aside#content-sidebar .sidebar-box .booking-list:hover{
		border-color: ".get_option('appointment_secondary_color'.'_'.$atts['bwid'])." !important;
	}
	
	#apt .apt-main-right .apt-sidebar-header h3.header3 {
		color: ".get_option('appointment_bg_text_color'.'_'.$atts['bwid'])." !important;
	}	
	".get_option('appointment_frontend_custom_css' . '_' . $atts['bwid'])."
	</style>";
	
	if(is_rtl()){
		echo "<style>
			#apt aside#content-sidebar .sidebar-box .booking-list .delete span:before{
				border-left: 40px solid ".get_option('appointment_secondary_color'.'_'.$atts['bwid'])." !important;
				border-right: unset !important;
			}
		</style>";
	}
	if(is_rtl()){
		echo "<script type='text/javascript'>
		jQuery(document).ready(function(){
			jQuery('#apt').addClass('aptrtl');
		});
		</script>";
	}
	
?>

<script src="https://js.stripe.com/v2/" type="text/javascript"></script>
<script>
var apt_stripeObj = { 'pubkey': '<?php echo get_option('appointment_stripe_publishableKey'.'_'.$atts['bwid']); ?>'};
</script>
 <div class="apt-wrapper"  id="apt"> <!-- main wrapper -->
<div class="loader">
	<div class="apt-loader">
		<span class="apt-first"></span>
		<span class="apt-second"></span>
		<span class="apt-third"></span>
	</div>
</div>
	<div class="containerd">
		<div class="apt-main-wrapper">
<section>
	<main id="apt-main">	
		<!-- main inner content -->
		<section class="apt-display-middle apt-main-left apt-md-8 apt-sm-7 apt-xs-12 np pull-left <?php if(!isset($_SESSION['apt_cart_item']) || (isset($_SESSION['apt_cart_item']) && sizeof($_SESSION['apt_cart_item'])==0)){ echo 'no-sidebar-right'; } ?> apt_remove_left_sidebar_class">
			<div class="apt-main-inner fullwidth">
			<div class="hide-data visible cb apt_login_form_check_validate" id="apt_second_step">
				<div class="apt-second-step form-inner visible" >
		
					<div class="apt-booking-step" data-current="2">
						<ul class="apt-list-inline nm">
							<li id="first"> <?php echo __("Service, Staff and Time","apt");?> <span class="sep"><i class="icon-arrow-right icons"></i></span></li>
							<li class="active" id="second" > <?php echo __("Info and Checkout","apt");?> <span class="sep"><i class="icon-arrow-right icons"></i></span></li>
							<li id="third"> <?php echo __("Done","apt");?></li>
							
						</ul>
					</div>
					<div class="apt-md-12 apt-lg-12 apt-sm-12 apt-xs-12 pull-left">
						<h3 class="block-title"><i class="icon-user icons fs-15"></i> <?php echo __("User Information","apt");?></h3>
						<?php
						$current_user = wp_get_current_user();
						$current_user_id = $current_user->ID ;
						$current_user_meta = get_user_meta($current_user_id);
						?>
						<div class="existing-user-success-login-message row fullwidth" <?php if(!is_user_logged_in()){ echo 'style="display:none;"'; } ?>>
							<div class="apt-form-row apt-md-12 apt-lg-12 apt-sm-12 apt-xs-12">
								<label class="apt-relative custom  mb-30"><?php echo __("You are logged in as","apt");?> <b><span class="apt-logged-in-user" id="logged_in_user_name"><?php if($current_user->user_firstname != ''){ echo $current_user->user_firstname; } if($current_user->user_lastname != ''){ echo ' '.$current_user->user_lastname; } ?></span></b>  <a href="javascript:void(0);" class="apt-logout-user" id="apt_log_out_user"><b><?php echo __("Logout","apt");?></b></a></label> 
							</div>
						</div>
						<div class="common-inner apt-info-input">
						    <div class="apt-main-inner" id="user-login">
								<div class="user-login-main apt-form-row fullwidth" <?php if(is_user_logged_in()){ echo 'style="display:none;"'; } ?>>
									<div class="apt-custom-radio apt-user-login-radio fullwidth">
										<ul class="apt-radio-list">	
											<li class="apt-first-radio apt-md-6 apt-lg-6 apt-sm-12 apt-xs-12">
												<input id="apt-existing-user" class="input-radio existing-user new_and_existing_user_radio_btn" name="user-selection" value="Existing User" type="radio" <?php if(is_user_logged_in()){ echo 'checked="checked"'; } ?> />
												<label class="apt-relative" for="apt-existing-user"><span></span><?php echo __("Existing User","apt");?></label>
											</li>
											<li class="apt-second-radio apt-md-6 apt-lg-6 apt-sm-12 apt-xs-12">
												<input id="apt-new-user" class="input-radio new-user new_and_existing_user_radio_btn" name="user-selection" value="New User" type="radio" <?php if(!is_user_logged_in()){ echo 'checked="checked"'; } ?> />
												<label class="apt-relative" for="apt-new-user"><span></span><?php echo __("New User","apt");?></label>
											</li>
											<?php
											if(get_option('appointment_guest_user_checkout' . '_' . $atts['bwid']) == "E"){
												?>
												<li class="apt-third-radio apt-md-6 apt-lg-6 apt-sm-12 apt-xs-12">
													<input id="apt-guest-user" class="input-radio guest-user new_and_existing_user_radio_btn" name="user-selection" value="Guest User" type="radio" />
													<label class="apt-relative" for="apt-guest-user"><span></span><?php echo __("Guest User","apt"); ?></label>
												</li>
												<?php 
											} ?>
										</ul>
									</div>
									<!-- user login form --> 
									<form method="post" id="apt_login_form_check_validate">
										<div class="existing-user-login row fullwidth">
											<div class="apt-form-row apt-md-6 apt-lg-6 apt-sm-12 apt-xs-12">
												<div class="pr">
													<input type="text" class="custom-input" name="apt_existing_login_username_input" id="apt_existing_login_username" />
													<label class="custom"><?php echo __("Email","apt");?></label>
													<i class="bottom-line"></i>
												</div> 
												<label class="apt-relative apt-error"><?php echo __("Please enter Email","apt");?></label>
											</div>
											<div class="apt-form-row apt-md-6 apt-lg-6 apt-sm-12 apt-xs-12">
												<div class="pr">
													<input type="password" name="apt_existing_login_password_input" class="custom-input" id="apt_existing_login_password" />
													<label class="custom"><?php echo __("Password","apt");?></label>
													<i class="bottom-line"></i>
												</div> 
												
												<label class="apt-relative apt-error"><?php echo __("Please enter Password","apt");?></label>
											</div>
											<label id="invalid_un_pwd" style="color: red;top: 55px;bottom: 15px;font-size: 14px;display:none"><?php echo __("Invalid Email or Password","apt");?></label>
											<div class="apt-xs-12 apt-md-12 apt-form-row apt-fw">
												<a href="javascript:void(0);" id="apt_existing_login_btn" class="apt-button nm float-left" title="Login account"><i class="fa fa-lock"></i> <?php echo __("Login","apt");?></a>
												<span class="apt-forget-pass">
													<a href="<?php echo home_url();?>/wp-login.php?action=lostpassword" class="apt-link" title="Forget Password"><?php echo __("Forget Password","apt");?></a>
												</span>
											</div>
										</div>
									</form>
								</div>
							</div> 
							<div class="apt-main-inner" id="new-user">
								<form method="post" id="apt_newuser_form_validate">
									<!-- new user fields -->
									<!-- new user area content prefered username and password -->
									<div class="new-user-area row hide_new_user_login_details" <?php if(is_user_logged_in()){ echo 'style="display:none;"'; } ?>>
										<div class="apt-form-row apt-md-6 apt-lg-6 apt-sm-12 apt-xs-12">
											<div class="pr">
												<input type="text" class="custom-input" name="new_user_preferred_username" id="new_user_preferred_username" value="<?php if($current_user->user_email != ''){ echo $current_user->user_email; } ?>" />
												<label class="custom"><?php echo __("Preferred Email","apt");?></label>
												<i class="bottom-line"></i>
											</div> 
											<label class="apt-relative apt-error"><?php echo __("Please enter Preferred Email","apt");?></label>
										</div>
										<div class="apt-form-row apt-md-6 apt-lg-6 apt-sm-12 apt-xs-12">
											<div class="pr">
												<input type="password" class="custom-input" name="new_user_preferred_password" id="new_user_preferred_password" value="<?php if($current_user->user_pass != ''){ echo $current_user->user_pass; } ?>" />
												<label class="custom"><?php echo __("Preferred Password","apt");?></label>
												<i class="bottom-line"></i>
											</div> 
											<label class="apt-relative apt-error"><?php echo __("Please enter Preferred Password","apt");?></label>
										</div>
									</div>
									<!-- common inputs -->
									<div class="row apt-common-inputs fullwidth new-user-personal-detail-area">
										<div class="apt-form-row apt-md-6 apt-lg-6 apt-sm-12 apt-xs-12">
											<div class="pr">
												<input type="text" class="custom-input" name="new_user_firstname" id="new_user_firstname" value="<?php if($current_user->user_firstname != ''){ echo $current_user->user_firstname; } ?>" />
												<label class="custom"><?php echo __("First Name","apt");?></label>
												<i class="bottom-line"></i>
											</div> 
											<label class="apt-relative apt-error"><?php echo __("Please enter First Name","apt");?></label>
										</div>
										<div class="apt-form-row apt-md-6 apt-lg-6 apt-sm-12 apt-xs-12">
											<div class="pr">
												<input type="text" class="custom-input" name="new_user_lastname" id="new_user_lastname" value="<?php if($current_user->user_lastname != ''){ echo $current_user->user_lastname; } ?>" />
												<label class="custom"><?php echo __("Last Name","apt");?></label>
												<i class="bottom-line"></i>
											</div> 
											<label class="apt-relative apt-error"><?php echo __("Please enter Last Name","apt");?></label>
										</div>
										<div class="apt-form-row apt-md-6 apt-lg-6 apt-sm-12 apt-xs-12">
											<div class="pr">
												<input type="text" type="tel" name="apt-phone" class="custom-input apt-phone-input" value="<?php if(isset($current_user_meta['apt_client_phone']) && $current_user_meta['apt_client_phone'][0] != ''){ echo $current_user_meta['apt_client_phone'][0]; } ?>" data-ccode="<?php if(isset($current_user_meta['apt_client_ccode']) && $current_user_meta['apt_client_ccode'][0] != ''){ echo $current_user_meta['apt_client_ccode'][0]; } ?>" id="apt-front-phone" />
												<label class="custom apt-phone-label"><?php echo __("Phone number","apt");?></label>
												<i class="bottom-line"></i>
											</div> 
											<label class="apt-relative apt-error"><?php echo __("Please enter Phone number","apt");?></label>
											<label id="apt-front-phone-error" class="error" for="apt-front-phone" ><?php echo __("Please Enter Only Numeric value","apt");?></label>
										</div>
										<div class="apt-form-row apt-md-6 apt-lg-6 apt-sm-12 apt-xs-12">
											<div class="fullwidth">
												<label class="apt-relative"><?php echo __("Gender","apt");?></label>
												<div class="apt-custom-radio">
													<ul class="apt-radio-list">	
														<li class="apt-first-radio apt-md-6 apt-lg-6 apt-sm-12 apt-xs-12">
															<input id="apt-male" class="input-radio new_user_gender" name="apt-gender" <?php if(isset($current_user_meta['apt_client_gender']) && $current_user_meta['apt_client_gender'][0] != '' && $current_user_meta['apt_client_gender'][0] == 'M'){ echo 'checked="checked"'; } ?> type="radio" value="M" />
															<label for="apt-male" class="apt-relative"><span></span><?php echo __("Male","apt");?></label>
														</li>
														<li class="apt-second-radio apt-md-6 apt-lg-6 apt-sm-12 apt-xs-12">
															<input id="apt-female" class="input-radio new_user_gender" name="apt-gender" <?php if(isset($current_user_meta['apt_client_gender']) && $current_user_meta['apt_client_gender'][0] != '' && $current_user_meta['apt_client_gender'][0] == 'F'){ echo 'checked="checked"'; } ?> type="radio" value="F" />
															<label for="apt-female" class="apt-relative"><span></span><?php echo __("Female","apt");?></label>
														</li>
													</ul>
												</div>
											</div> 											
										</div>
										<div class="apt-form-row apt-sm-12 apt-xs-12 apt-fw">
											<div class="pr">
												<input type="text" class="custom-input" name="new_user_street_address" id="new_user_street_address" value="<?php if(isset($current_user_meta['apt_client_address']) && $current_user_meta['apt_client_address'][0] != ''){ echo $current_user_meta['apt_client_address'][0]; } ?>" />
												<label class="custom"><?php echo __("Street Address","apt");?></label>
												<i class="bottom-line"></i>
											</div> 
											<label class="apt-relative apt-error"><?php echo __("Please enter Street Address","apt");?></label>
										</div>
										<div class="apt-form-row apt-md-6 apt-lg-6 apt-sm-12 apt-xs-12">
											<div class="pr">
												<input type="text" class="custom-input" name="new_user_city" id="new_user_city" value="<?php if(isset($current_user_meta['apt_client_city']) && $current_user_meta['apt_client_city'][0] != ''){ echo $current_user_meta['apt_client_city'][0]; } ?>" />
												<label class="custom"><?php echo __("Town/City","apt");?></label>
												<i class="bottom-line"></i>
											</div> 
											<label class="apt-relative apt-error"><?php echo __("Please enter Town/City","apt");?></label>
										</div>
										<div class="apt-form-row apt-md-6 apt-lg-6 apt-sm-12 apt-xs-12">
											<div class="pr">
												<input type="text" class="custom-input" name="new_user_state" id="new_user_state" value="<?php if(isset($current_user_meta['apt_client_state']) && $current_user_meta['apt_client_state'][0] != ''){ echo $current_user_meta['apt_client_state'][0]; } ?>" />
												<label class="custom"><?php echo __("State","apt");?></label>
												<i class="bottom-line"></i>
											</div> 
											<label class="apt-relative apt-error"><?php echo __("Please enter State","apt");?></label>
										</div>
										<div class="apt-form-row apt-sm-12 apt-xs-12 apt-fw">
											<div class="pr">
												<textarea class="custom-input" name="new_user_notes" id="new_user_notes"><?php if(isset($current_user_meta['apt_client_notes']) && $current_user_meta['apt_client_notes'][0] != ''){ echo $current_user_meta['apt_client_notes'][0]; } ?></textarea>
												<label class="custom"><?php echo __("Special Notes","apt");?></label>
												<i class="bottom-line"></i>
											</div> 
											<label class="apt-relative apt-error"><?php echo __("Please enter Special Notes","apt");?></label>
										</div>
										<!-----Custom form fields start------>
										<div class="apt-form-builder-form-fields">
											<?php
											if(get_option('appointment_custom_form' . '_' . $atts['bwid']) != FALSE){
												$apt_formfields  = json_decode(stripslashes(get_option('appointment_custom_form' . '_' . $atts['bwid'])),true);
												if(sizeof($apt_formfields) > 0){
													foreach($apt_formfields as $apt_formfield) {
														if(isset($apt_formfield['type']) && $apt_formfield['type']=='radio-group') {
															if(isset($apt_formfield['required']) && $apt_formfield['required'] == true){
																$req_field = 'Y';
															}else{
																$req_field = 'N';
															}
															?>
															<div class="apt-form-row apt-md-12 apt-lg-12 apt-sm-12 apt-xs-12">
																<div class="fullwidth">
																	<label class="apt-relative"><?php echo __($apt_formfield['label'],"apt");?></label>
																	<div class="apt-custom-radio">
																		<ul class="apt-radio-list">
																			<?php
																			if(isset($apt_formfield['values']) && sizeof($apt_formfield['values'])>0){
																				foreach($apt_formfield['values'] as $singleInput) {
																					?>
																					<li class="apt-sm-6 apt-md-4 apt-lg-4 apt-xs-12">
																						<input data-fieldname="radio_group"  data-fieldlabel="<?php echo $apt_formfield['label']; ?>" id="<?php echo $singleInput['value']; ?>" class="input-radio get_custom_field <?php echo $apt_formfield['className']; ?>" name="<?php echo $apt_formfield['name']; ?>" value="<?php echo $singleInput['value']; ?>" data-required="<?php echo $req_field; ?>" <?php if(isset($singleInput['selected']) && $singleInput['selected']){ echo 'checked="checked"'; } ?> type="radio" />
																						<label for="<?php echo $singleInput['value']; ?>" class="apt-relative"><span></span><?php echo __($singleInput['label'],"apt");?></label>
																					</li>
																					<?php
																				}
																			}
																			?>
																		</ul>
																	</div>
																</div>
															</div>
															<?php
														}
														if(isset($apt_formfield['type']) && $apt_formfield['type']=='checkbox-group') {
															if(isset($apt_formfield['required']) && $apt_formfield['required'] == true){
																$req_field = 'Y';
															}else{
																$req_field = 'N';
															}
															?>
															<div class="apt-form-row apt-sm-12 apt-xs-12 apt-fw">
																<div class="pr">
																	<label class="apt-relative"><?php echo __($apt_formfield['label'],"apt");?></label>
																	<div class="apt-custom-checkbox">
																		<ul class="custom-checkbox-list">
																			<?php
																			if(isset($apt_formfield['values']) && sizeof($apt_formfield['values'])>0){
																				foreach($apt_formfield['values'] as $singleInput) {
																					?>
																					<li class="custom-checkbox ccb-absolute apt-sm-6 apt-md-4 apt-lg-4 apt-xs-12">
																						<input id="<?php echo $singleInput['value']; ?>" name="<?php echo $apt_formfield['name']; ?>" <?php if(isset($singleInput['selected']) && $singleInput['selected']){ echo 'checked'; } ?> class="input-checkbox get_custom_field <?php echo $apt_formfield['className']; ?>" data-required="<?php echo $req_field; ?>" data-fieldlabel="<?php echo $apt_formfield['label']; ?>" value="<?php echo $singleInput['value']; ?>" type="checkbox"  />
																						<label for="<?php echo $singleInput['value']; ?>" class="apt-relative"><span class="check-icon"></span><span class="label-text"><?php echo __($singleInput['label'],"apt");?></span></label>
																					</li>
																					<?php
																				}
																			}
																			?>
																		</ul>
																	</div>
																</div>
															</div>
															<?php
														}												   
														if(isset($apt_formfield['type']) && $apt_formfield['type']=='checkbox') {
															if(isset($apt_formfield['required']) && $apt_formfield['required'] == true){
																$req_field = 'Y';
															}else{
																$req_field = 'N';
															}
															?>
															<div class="apt-form-row apt-sm-12 apt-xs-12 apt-fw">
																<div class="pr">
																	<label class="apt-relative"><?php echo __($apt_formfield['label'],"apt");?></label>
																	<div class="apt-custom-checkbox">
																		<ul class="custom-checkbox-list">
																			<li class="custom-checkbox ccb-absolute apt-sm-6 apt-md-4 apt-lg-4 apt-xs-12">
																				<input id="<?php if(isset($apt_formfield['name']) && $apt_formfield['name'] != ""){ echo $apt_formfield['name']; } ?>" name="<?php if(isset($apt_formfield['name']) && $apt_formfield['name'] != ""){ echo $apt_formfield['name']; } ?>" class="input-checkbox get_custom_field <?php if(isset($apt_formfield['className']) && $apt_formfield['className'] != ""){ echo $apt_formfield['className']; } ?>" data-required="<?php echo $req_field; ?>" data-fieldlabel="<?php if(isset($apt_formfield['label']) && $apt_formfield['label'] != ""){ echo $apt_formfield['label']; } ?>" value="<?php if(isset($apt_formfield['value']) && $apt_formfield['value'] != ""){ echo $apt_formfield['value']; } ?>" type="checkbox" />
																				<label for="<?php echo $apt_formfield['name']; ?>" class="apt-relative"><span class="check-icon"></span><span class="label-text"><?php if(isset($apt_formfield['label']) && $apt_formfield['label'] != ""){ echo __($apt_formfield['label'],"apt"); } ?></span></label>
																			</li>
																		</ul>
																	</div>
																</div>
															</div>
															<?php
														}
														if(isset($apt_formfield['type']) && $apt_formfield['type']=='text') {
															if(isset($apt_formfield['required']) && $apt_formfield['required'] == true){
																$req_field = 'Y';
															}else{
																$req_field = 'N';
															}
															?>
															<div class="apt-form-row apt-sm-12 apt-xs-12 apt-fw">
																<div class="pr">
																	<input type="<?php echo $apt_formfield['subtype']; ?>" data-required="<?php echo $req_field; ?>" data-fieldlabel="<?php echo $apt_formfield['label']; ?>" class="custom-input get_custom_field <?php echo $apt_formfield['className']; ?>" name="<?php echo $apt_formfield['name']; ?>" id="<?php echo $apt_formfield['name']; ?>" />
																	<label class="custom"><?php echo __($apt_formfield['label'],"apt");?></label>
																	<i class="bottom-line"></i>
																</div> 
																<label class="apt-relative apt-error"><?php echo __("Please enter ".$apt_formfield['label'],"apt");?></label>
															</div>
															<?php
														}
														if(isset($apt_formfield['type']) && $apt_formfield['type']=='number') {
															if(isset($apt_formfield['required']) && $apt_formfield['required'] == true){
																$req_field = 'Y';
															}else{
																$req_field = 'N';
															}
															?>
															<div class="apt-form-row apt-sm-12 apt-xs-12 apt-fw">
																<div class="pr">
																	<input type="number" data-required="<?php echo $req_field; ?>" data-fieldlabel="<?php echo $apt_formfield['label']; ?>" class="custom-input get_custom_field <?php echo $apt_formfield['className']; ?>" name="<?php echo $apt_formfield['name']; ?>" id="<?php echo $apt_formfield['name']; ?>" />
																	<label class="custom"><?php echo __($apt_formfield['label'],"apt");?></label>
																	<i class="bottom-line"></i>
																</div> 
																<label class="apt-relative apt-error"><?php echo __("Please enter ".$apt_formfield['label'],"apt");?></label>
															</div>
															<?php
														}
														if(isset($apt_formfield['type']) && $apt_formfield['type']=='select') {
															if(isset($apt_formfield['required']) && $apt_formfield['required'] == true){
																$req_field = 'Y';
															}else{
																$req_field = 'N';
															}
															?>
															<div class="apt-form-row apt-sm-12 apt-xs-12 apt-fw">
																<div class="pr">
																	<label class="apt-relative"><?php echo __($apt_formfield['label'],"apt");?></label>
																	<select class="aptcust-select get_custom_field <?php echo $apt_formfield['className']; ?>" data-required="<?php echo $req_field; ?>" data-fieldlabel="<?php echo $apt_formfield['label']; ?>">
																		<?php
																		if(isset($apt_formfield['values']) && sizeof($apt_formfield['values'])>0){
																			foreach($apt_formfield['values'] as $singleInput) {
																				?>
																				<option value="<?php echo $singleInput['value']; ?>" id="<?php echo $apt_formfield['name']; ?>" name="<?php echo $apt_formfield['name']; ?>"><?php echo $singleInput['value']; ?></option>
																				<?php
																			}
																		}
																		?>
																	</select>				
																</div>
															</div>
															<?php
														}
														if(isset($apt_formfield['type']) && $apt_formfield['type']=='textarea') {
															if(isset($apt_formfield['required']) && $apt_formfield['required'] == true){
																$req_field = 'Y';
															}else{
																$req_field = 'N';
															}
															?>
															<div class="apt-form-row apt-sm-12 apt-xs-12 apt-fw">
																<div class="pr">
																	<textarea data-required="<?php echo $req_field; ?>" data-fieldlabel="<?php echo $apt_formfield['label']; ?>" class="custom-input get_custom_field <?php echo $apt_formfield['className']; ?>" name="<?php echo $apt_formfield['name']; ?>" id="<?php echo $apt_formfield['name']; ?>" ></textarea>
																	<label class="custom"><?php echo __($apt_formfield['label'],"apt");?></label>
																	<i class="bottom-line"></i>
																</div> 
																<label class="apt-relative apt-error"><?php echo __("Please enter ".$apt_formfield['label'],"apt");?></label>
															</div>
															<?php
														}
													}
												}
											}
											?>
										</div>	
										<!-----Custom form fields end------>
									</div>
								</form>
							</div><!-- apt main inner end -->	
						</div>
						<?php if(get_option('appointment_payment_gateways_status'.'_'.$atts['bwid'])=='E'){ ?>
						<div class="common-inner">
							<h3 class="block-title"><i class="icon-wallet icons fs-15"></i> <?php echo __("Payment Methods","apt");?></h3>
							<div class="apt-custom-radio" id="apt-payments">
								<ul class="apt-radio-list payment_checkbox">
									<?php if(get_option('appointment_payment_gateways_status'.'_'.$atts['bwid'])=='D'){ ?>
										<input type="hidden" id="pay-locally" class="input-radio apt_payment_method" checked="checked"  name="apt-payment-options" value="pay_locally" />
									<?php } ?>									
									<?php if(get_option('appointment_payment_gateways_status'.'_'.$atts['bwid'])=='E' && get_option('appointment_locally_payment_status'.'_'.$atts['bwid'])=='E'){ ?>
									<li class="apt-pay-locally apt-lg-4 apt-md-4 apt-sm-6 apt-xs-12 np">
										<input type="radio" id="pay-locally" class="input-radio apt_payment_method" checked="checked"  name="apt-payment-options" value="pay_locally" />
										<label for="pay-locally" class="apt-relative"><span></span><?php echo __("I will pay locally","apt");?></label>
									</li>
									<?php } ?>
									<?php if(get_option('appointment_payment_gateways_status'.'_'.$atts['bwid'])=='E' && get_option('appointment_payment_method_Paypal'.'_'.$atts['bwid'])=='E'){ ?>
									<li class="apt-paypal-payments apt-lg-4 apt-md-4 apt-sm-6 apt-xs-12 np">
										<input type="radio" id="paypal" checked="checked" class="input-radio apt_payment_method" name="apt-payment-options" value="paypal" />
										<label for="paypal" class="apt-relative"><span></span><?php echo __("Paypal","apt");?> <img class="apt-paypal-image" src="<?php echo plugins_url().'/appointment/assets/images/paypal.png';?>" title="Paypal" /></label>
									</li><?php } ?>
									<?php if(get_option('appointment_payment_gateways_status'.'_'.$atts['bwid'])=='E' && get_option('appointment_payment_method_Stripe'.'_'.$atts['bwid'])=='E'){ ?>
									<li class="apt-stripe-payments apt-lg-4 apt-md-4 apt-sm-6 apt-xs-12 np">
										<input type="radio" id="stripe-payments" checked="checked" class="input-radio apt_payment_method" name="apt-payment-options" value="stripe" />
										<label for="stripe-payments" class="apt-relative"><span></span><?php echo __("Pay with card now","apt");?> <i class="icon-credit-card icons"></i></label>
									</li><?php } ?>
									<?php if(get_option('appointment_payment_gateways_status' . '_' . $atts['bwid'])=='E' && get_option('appointment_payment_method_Payumoney' . '_' . $atts['bwid'])=='E'){ ?>
									<li class="apt-payumoney-payments apt-lg-4 apt-md-4 apt-sm-6 apt-xs-12 np">
										<input type="radio" id="payumoney-payments" checked="checked" class="input-radio apt_payment_method" name="apt-payment-options" value="payumoney" />
										<label for="payumoney-payments" class="apt-relative"><span></span><?php echo __("Pay with payumoney","apt");?> <i class="icon-credit-card icons"></i></label>
									</li><?php } ?>
									<?php if(get_option('appointment_payment_gateways_status' . '_' . $atts['bwid'])=='E' && get_option('appointment_payment_method_Paytm' . '_' . $atts['bwid'])=='E'){ ?>
									<li class="apt-payumoney-payments apt-lg-4 apt-md-4 apt-sm-6 apt-xs-12 np">
										<input type="radio" id="paytm-payments" checked="checked" class="input-radio apt_payment_method" name="apt-payment-options" value="paytm" />
										<label for="paytm-payments" class="apt-relative"><span></span><?php echo __("Pay with paytm","apt");?> <i class="icon-credit-card icons"></i></label>
									</li><?php } ?>
								</ul>
								<label class="apt-relative apt-error payment_error_msg"><?php echo __("Select Atleast one payment method","apt");?></label>
							</div>
							
							<?php if(get_option('appointment_payment_method_Stripe' . '_' . $atts['bwid'])=='E'){ ?>
							<!-- stripe payment card inputs -->
							<div class="apt-stripe-wrapper" id="stripe-payment-main">
								<div class="apt-stripe-card">
									<div class="stripe-header">
										<div class="card-header"><?php echo __("Card Details","apt");?></div>
										<img class="card-sample-img pull-right" src="<?php echo plugins_url().'/appointment/assets/images/cards/cards.png' ?>" />
									</div>
									<label class="show_card_payment_error apt-relative apt-error"><?php echo __("Card number is invalid","apt");?> </label>
									<div class="card-input-container pr apt-sm-10 apt-md-9 apt-xs-12 res-nplr">
										<div class="apt-form-row apt-xs-12 np">
										<label class="apt-relative fs-13"><?php echo __("Card number","apt");?></label>
											<div class="apt-card-number-main">
												<i class="icon-credit-card icons"></i>
												<input type="text" id="card-number" name="apt-town-city" class="cc-number input-card custom-input" type="tel" maxlength="20" size="20" placeholder="XXXX XXXX XXXX XXXX" />			
												<span class="card-type" aria-hidden="true"></span>	
												<i class="bottom-line"></i>
											</div>
										</div>
										<div class="apt-form-row apt-md-7 apt-sm-8 apt-xs-12 res-npr npl">
											<label class="apt-relative fs-13"><?php echo __("Expiry (MM/YYYY)","apt");?></label>
											<div class="expiry-month-year apt-xs-5 np pr">
												<i class="icon-calendar icons"></i>
												<input type="tel" id="card-expiry" class="cc-exp-month expiry-month nmt custom-input" maxlength="2" placeholder="MM" />
												<em class="card-mon-year-separator"></em>
												<i class="bottom-line"></i>
											</div>
											<div class="expiry-month-year expiry_year apt-sm-5 apt-xs-6 npr pr pull-right">
												<input type="tel" class="cc-exp-year expiry-year nmt custom-input" maxlength="4" placeholder="YYYY" /> 
												<i class="bottom-line"></i>
											</div>
										</div>
										<div class="cvc-code apt-form-row apt-md-5 apt-sm-4 apt-xs-12 res-npl npr">
										<label class="apt-relative fs-13"><?php echo __("CVC","apt");?></label>
											<div class="apt-cvc-code-main pr">
												<i class="icon-lock icons"></i>
												<input id="cvc-code" type="password" size="4" maxlength="4" class="cc-cvc cvc-code apt-cvc-code custom-input" />
												<div class="card-cvc-clue pull-right">
													<img class="cvc-image-hint" src="<?php echo plugins_url().'/appointment/assets/images/apt-cvv.png';?>" /><?php echo __("The last 3 digit printed on the signature panel on the back of your credit card.","apt");?> 
												</div>
												<i class="bottom-line"></i>
											</div>
										</div>
									</div>
									<div class="apt-form-row pull-right apt-md-3 apt-sm-2 np res-hidden secure-img">
										<img src="<?php echo plugins_url().'/appointment/assets/images/apt-secure.png' ?>" />
									</div>
									
								</div>
							</div>
							<?php } ?>
						</div>
						<?php } ?>
						
						<?php if(get_option('appointment_allow_terms_and_conditions' . '_' . $atts['bwid'])=='E' || get_option('appointment_allow_privacy_policy' . '_' . $atts['bwid'])=='E' || get_option('appointment_cancelation_policy_status' . '_' . $atts['bwid'])=='E'){ ?>
						<div class="apt-complete-booking-main cb fullwidth  mt-20">
							<?php if(get_option('appointment_cancelation_policy_status' . '_' . $atts['bwid'])=='E'){ ?>
							<div class="apt-complete-booking apt-md-12 np">
								<h5 class="apt-cancel-booking"><?php echo __("Cancellation Policy","apt");?></h5>
								<div class="apt-cancel-policy">
									<p><?php echo get_option('appointment_cancelation_policy_header' . '_' . $atts['bwid']);?>
									<span class="show-more-toggler apt-link"><?php echo __("Show More","apt");?></span></p>
									<ul class="bullet-more" style="display: none;">
										<li><?php echo get_option('appointment_cancelation_policy_text' . '_' . $atts['bwid']);?></li>
									</ul>
								</div>
							</div>
							<?php } ?>
							<?php if(get_option('appointment_allow_terms_and_conditions'.'_'.$atts['bwid'])=='E' || get_option('appointment_allow_privacy_policy'.'_'.$atts['bwid'])=='E'){ ?>							
							<div class="apt-terms-agree apt-md-12 apt-xs-12 mt-20 mb-20 cb np">
								<div class="apt-custom-checkbox">
									<ul class="custom-checkbox-list">
										<li class="custom-checkbox ccb-absolute fullwidth apt-termcondition-area">
											<input name="apt-accept-conditions" class="input-radio" id="apt-accept-conditions" type="checkbox">
											<label for="apt-accept-conditions" class="apt-relative">
												<span class="check-icon"></span><span class="label-text"> <?php echo __("I have read and accepted the","apt");?>
												<?php if(get_option('appointment_allow_terms_and_conditions'.'_'.$atts['bwid'])=='E'){ ?>
												<a href="<?php echo urldecode(get_option('appointment_allow_terms_and_conditions_url'.'_'.$atts['bwid']));?>" target="_blank" class="apt-link"><?php echo __("Terms &amp; Conditions","apt");?></a><?php } ?> 
												<?php if(get_option('appointment_allow_terms_and_conditions'.'_'.$atts['bwid'])=='E' && get_option('appointment_allow_privacy_policy'.'_'.$atts['bwid'])=='E'){ ?>
												<?php echo __("and","apt");?>
												<?php } ?>
												<?php if(get_option('appointment_allow_privacy_policy'.'_'.$atts['bwid'])=='E'){ ?> 
												<a href="<?php echo urldecode(get_option('appointment_allow_privacy_policy_url'.'_'.$atts['bwid']));?>" target="_blank" class="apt-link"><?php echo __("Privacy Policy","apt");?></a><?php } ?>.</span>
											</label>
										</li>
									</ul>
								</div>
								<label class="apt-relative apt-error apt_terms_and_condition_error"><?php echo __("Please Accept term & conditions.","apt");?></label>
							</div>
							<?php } ?>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>	
			
			<div class="hide-data visible cb" id="apt_third_step">
				<div class="apt-third-step form-inner visible" >
					<div class="apt-booking-step" data-current="3">
						<ul class="apt-list-inline nm">
							<li id="first"><?php echo __("Service, Staff and Time","apt");?><span class="sep"><i class="icon-arrow-right icons"></i></span></li>
							<li id="second" ><?php echo __("Info and Checkout","apt");?><span class="sep"><i class="icon-arrow-right icons"></i></span></li>
							<li class="active" id="third"><?php echo __("Done","apt");?></li>					
						</ul>
					</div>
					<div class="apt-md-12 apt-lg-12 apt-sm-12 apt-xs-12 pull-left">
						<!-- <h3>3. Done</h3> -->
						<div class="common-inner">
							<div class="booking-thankyou">
								<h1 class="header1" style="color:#e6ac00;"><?php echo __("Congratulations","apt");?></h1>
								<h3 class="header3"><?php echo __("Your request was successful.","apt");?></h3>
								<p class="thankyou-text"><?php echo __("You will be notified with details of appointment(s).","apt");?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="visible cb" id="apt_first_step" >
				

				<div class="apt-first-step form-inner visible" >
					<input type="hidden" name="apt_selected_location" id="apt_selected_location" value="X" />	
					<input type="hidden" name="apt_selected_service" id="apt_selected_service" value="0" />	
					<input type="hidden" name="apt_selected_staff" id="apt_selected_staff" value="0" />	
					<input type="hidden" name="apt_service_addon_st" id="apt_service_addon_st" value="D" />	
					<input type="hidden" name="apt_selected_datetime" id="apt_selected_datetime" value="0" />	
					<input type="hidden" name="bwid" id="bwid" value="<?php echo (isset($atts['bwid']) && $atts['bwid'] != '')?$atts['bwid']:'0'; ?>" />	

					<div class="apt-booking-step" data-current="1">
						<ul class="apt-list-inline nm">
							<li class="active" id="first"><?php echo __("Service, Staff and Time","apt");?><span class="sep"><i class="icon-arrow-right icons"></i></span></li>
							<li id="second"><?php echo __("Info and Checkout","apt");?><span class="sep"><i class="icon-arrow-right icons"></i></span></li>
							<li id="third"><?php echo __("Done","apt");?></li>
						</ul>
					</div>
					
					<div class="apt-form-common apt-md-12 apt-lg-12 apt-sm-12 apt-xs-12 pull-left">
						<div class="common-inner">
						<?php if($apt_zipcode_booking_status=='E'){ ?>						
							<div class="pr apt-md-12 apt-lg-12 apt-sm-12 apt-xs-12 np">
								<div class="apt-form-row fullwidth">
									<h3 class="block-title"><i class="icon-location-pin icons fs-20"></i><?php echo __("Where would you like us to provide service?","apt");?></h3>
									<div class="pr apt-sm-12 apt-xs-12 apt-xs-12 np">
										<div class="pr">
											<input type="text" class="custom-input" id="apt_zip_code" />
											<label class="custom"><?php echo __("Your area code or zip code","apt");?></label>
											<i class="bottom-line"></i>
										</div> 
										<span id="apt_location_error" class="apt-error"><?php echo __("Please enter area code or zip code","apt");?></span>
										<span id="apt_location_success" class="apt-success"><?php echo __("We cover your location area","apt");?></span>
									</div> 
								</div> 
							</div>	
						<?php } ?>
						<?php if($apt_mulitlocation_status=='E'){ ?>
							<div class="pr apt-md-12 apt-lg-12 apt-sm-12 apt-xs-12 np">
							<div class="apt-form-row fullwidth">
								<h3 class="block-title"><i class="icon-location-pin icons fs-15"></i> <?php echo __("Choose Location","apt");?></h3>
								<span id="apt_location_error" class="apt-error"><?php echo __("Please select location","apt");?></span>
								<div class="pr apt-md-12 apt-lg-12 apt-sm-12 apt-xs-12 np">
									<div id="cus-select1" class="cus-location fullwidth custom-input nmt">
										<div class="common-selection-main location-selection">
											<div class="selected-is select-location" title="Choose Your Selection">
												<div class="data-list" id="selected_location">
													<div class="apt-value"><?php echo __("Please choose location","apt");?></div>
												</div>
											</div>
											<ul class="common-data-dropdown location-dropdown custom-dropdown">
												<?php 
												foreach($locations as $location){ ?>
												<li class="data-list select_location" value="<?php echo $location->id;?>">
													<div class="apt-value" ><?php echo $location->location_title;?></div>
												</li>
												<?php } ?>
											</ul>
										</div>
									</div>
									<i class="bottom-line"></i>
								</div>	
							</div> 
							</div> 
						<?php } ?>
 


							
							<div class="apt-form-row fullwidth"> <!-- start select service -->
								<h3 class="block-title"><i class="icon-grid icons fs-20"></i> <?php echo __("Please Select A Service","apt");?></h3>
								<span id="apt_service_error" class="apt-error apt-hide"><?php echo __("Please check service area.","apt");?></span>
								<div class="pr apt-md-12 apt-lg-12 apt-sm-12 apt-xs-12 np">
									<div id="cus-select1" class="cus-select fullwidth custom-input nmt">
										<div class="common-selection-main service-selection">
											<div class="selected-is select-custom" title="<?php echo __("Choose Your Selection","apt");?>">
												<div class="data-list" id="selected_custom">
													<div class="apt-value"><?php echo __("Please choose service","apt");?></div>
												</div>
											</div>
											<ul id="apt_services" class="common-data-dropdown service-dropdown custom-dropdown">												
												<?php
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
													<div class="apt-value" ><?php echo __("No service found for this location.","apt");?></div>
												</li>
												<?php } ?>															
											</ul>
										</div>
									</div>
									<i class="bottom-line"></i>
								</div> 
								
								<div id="apt_service_detail" class="pr apt-sm-12 apt-xs-12 np apt-hide service-details">
								</div>
							</div>  <!-- end select service -->
							
							<!-- Service Addons Container -->
							<div id="apt_service_addons" class="apt-form-row fullwidth apt-hide"></div> 
							<!-- End Service Addons Container -->
							
							
							
							<div class="apt-form-row fullwidth"> <!-- Select staff start -->
								<h3 class="block-title"><i class="icon-user icons fs-20"></i><?php echo __("Please Select A Service Provider","apt");?></h3>
								<div class="pr apt-sm-12 apt-xs-12 np" id="apt_staff_info">
									<?php if($provider_avatar_view=='E'){ ?>
									<div class="apt-service-staff-list apt-common-box">
										<ul class="staff-list fullwidth np">
											<?php 
											if(sizeof($aptstaffs)>0){
												$uplodpathinfo = wp_upload_dir();
												foreach($aptstaffs as $aptstaff){ 
												$staffimagepath = plugins_url( 'assets/images/provider/staff.png',dirname(__FILE__));
												if(isset($aptstaff['image']) && $aptstaff['image']!=''){	
													$staffimagepath = $uplodpathinfo['baseurl'].$aptstaff['image'];			
												}
																					
												?>
												<li data-staffid="<?php echo $aptstaff['id'];?>" class="apt-staff-box apt-sm-6 apt-md-3 apt-lg-3 apt-xs-12 mb-15">
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
											<?php }	?>
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
												if(sizeof($aptstaffs)>0){
													foreach($aptstaffs as $aptstaff){ ?>
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
									<?php } ?>									
								</div>
								<span id="apt_staff_error" class="apt-error"><?php echo __("Please choose service provider","apt");?></span> 
							</div> <!-- Select staff end -->
							
							<div class="apt-form-row fullwidth"><!-- Calendar start -->	
								<h3 class="block-title"><i class="icon-calendar icons fs-20"></i><?php echo __("Please Select A Date & Time","apt");?></h3>
								<?php 		
								$month= date_i18n('m');
								$year= date_i18n('Y');
								$currentdate = mktime(12, 0, 0,$month, 1,$year);	
								/* $currentdate = strtotime(date_i18n('Y-m-d')); */
								$calnextmonth = strtotime('+1 month',$currentdate);
								$calprevmonth=strtotime('-1 month', $currentdate);
								$apt_maxadvance_booktime = get_option('appointment_maximum_advance_booking'.'_'.$atts['bwid']);
								$calmaxdate = strtotime('+'.$apt_maxadvance_booktime.' month',$currentdate);	
								$monthdays = date_i18n("t", $currentdate);
								$offset = date_i18n("w", $currentdate);
								$rows = 1;		
								$prevmonthlink =  strtotime(date("Y-m-d",$currentdate));
								$currrmonthlink =  strtotime(date("Y-m-d"));
								?>					
								
								<div class="pr apt-sm-12 apt-xs-12 apt-datetime-seleapt-main np">
									<div class="apt-datetime-select">
										<div class="calendar-wrapper">
											<div class="calendar-header">
												<?php if($currrmonthlink < $prevmonthlink){ ?>
												<a class="previous-date apt_month_change" data-curmonth="<?php echo date_i18n('m');?>" data-curyear="<?php echo date_i18n('Y');?>"  data-calyear="<?php echo date("Y", $calprevmonth); ?>" data-calmonth="<?php echo date("m", $calprevmonth); ?>" data-calaction="prev" href="javascript:void(0)"><i class="icon-arrow-left icons"></i></a>
												<?php }else{ ?>
													<a data-curmonth="<?php echo date_i18n('m');?>" data-curyear="<?php echo date_i18n('Y');?>" class="previous-date" href="javascript:void(0)"><i class="icon-arrow-left icons"></i></a>
												<?php } ?>
												<div class="calendar-title"><?php echo date_i18n('F'); ?></div>		
												<div class="calendar-year"><?php echo date_i18n('Y'); ?></div>
												
												<?php
												if(date('M',$calmaxdate) == date('M',$currentdate) && date('Y',$calmaxdate) == date('Y',$currentdate)){ ?>
												<a class="next-date" href="javascript:void(0)"><i class="icon-arrow-right icons"></i></a>
												<?php }else{?>
												<a class="next-date apt_month_change" data-calyear="<?php echo date("Y", $calnextmonth); ?>" data-calmonth="<?php echo date("m", $calnextmonth); ?>" data-calaction="next" href="javascript:void(0)"><i class="icon-arrow-right icons"></i></a>
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
												<div data-seldate="<?php echo $calsel_date;?>" data-calrowid="<?php if($day < 35){echo $k+7; }else{echo $k;} ?>"  class="apt-week <?php if($calsel_date==$calcurr_date){ echo 'by_default_today_selected';} if($calsel_date<$calcurr_date){ echo 'inactive';} ?>"><a href="javascript:void(0)"><span><?php echo $day; ?></span></a></div> 
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
								</div>								
							</div> 								
						</div><!-- Calendar end -->
						<span id="apt_datetime_error" class="apt-error"><?php echo __("Please select date & time","apt");?></span>	
						<div class="apt-button-container text-right fullwidth">
							<a class="apt-button btn-x-medium" id="btn-second-step" href="javascript:void(0)"><i class="icon-arrow-left icons apt-rtl-icon"></i><?php echo __("Next","apt");?><i class="icon-arrow-right icons apt-ltr-icon"></i></a>
						</div>
					</div>
				</div>
			</div>		
		</div>
		</section> <!-- main view content end here -->
		
		<aside id="content-sidebar" class="apt-display-middle apt-main-right apt-md-4 apt-sm-5 apt-lg-4 apt-xs-12 np pull-right <?php echo (isset($_SESSION['apt_cart_item']) && sizeof($_SESSION['apt_cart_item'])>0)?'cart-item-sidebar':'no-cart-item-sidebar'; ?> apt_remove_right_sidebar_class">
			<div id="apt_booking_sidebar" class="content-summary" data-cartitems="<?php echo (isset($_SESSION['apt_cart_item']) && sizeof($_SESSION['apt_cart_item'])>0)?count($_SESSION['apt_cart_item']):'0'; ?>">
				<?php 
				if(isset($_SESSION['apt_cart_item']) && sizeof($_SESSION['apt_cart_item'])>0)
				{				
				$appointment_show_coupons = get_option('appointment_show_coupons'.'_'.$atts['bwid']);
				$apt_taxvat_status = get_option('appointment_taxvat_status'.'_'.$atts['bwid']);
				$apt_partial_deposit_status = get_option('appointment_partial_deposit_status'.'_'.$atts['bwid']);
				
				?>
				<div class="apt-sidebar-header">
					<h3 class="header3"><?php echo __("Booking Summary","apt");?><div class="apt-cart-items-count"> <i class="icon-bag icons fs-22 pull-right pr"><span class="apt_badge"><?php echo sizeof($_SESSION['apt_cart_item']);?></span></i></div></h3>
				</div>
				<div id="apt_booking_summary" class="sidebar-box">					
					<?php 	
					
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
						
						
						
						/* Booking Summary HTML */
						$apt_booking_summary = '<div class="booking-list br-3 fullwidth">
						<a class="apt-delete-booking-box apt_remove_item" data-cartitemid="'.$cartitem_id.'" href="javascript:void(0)">'.__("Delete","apt").'</a>
						<div class="right-booking-details apt-md-12 apt-sm-12 apt-xs-12 np pull-left">';
						
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
								$addon_id = $selectedaddon['addonid'];
								$addon_qty = $selectedaddon['maxqty'];
								$apt_service->addon_id = $addon_id;
								$apt_addoninfo = $apt_service->readOne_addon();
								$addon_price = $apt_addoninfo[0]->base_price;
								
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
						$apt_booking_summary .='<div class="common-style date fullwidth"><i class="icon-calendar icons"></i>'.date_i18n(get_option('appointment_datepicker_format'.'_'.$atts['bwid']),$selected_datetime).'</div>	<div class="common-style time fullwidth"><i class="icon-clock icons"></i>'.date_i18n(get_option('time_format'),$service_starttime).' '.__("to","apt").' '.date_i18n(get_option('time_format'),$service_endtime).'</div>';
						
						
						/* Booking Item Total Price */
						$total_item_price = $service_amount+$service_addon_total;
						$apt_booking_summary .='<div class="price last-item fullwidth">
													<div class="apt-xs-8 np apt-left-text">'.__("Item Price","apt").'</div>
													<div class="apt-xs-4 np apt-right-text text-right service-price">'.$apt_general->apt_price_format($total_item_price).'</div>
												</div>';
						
						$apt_booking_summary .= '</div>
											<div class="delete pull-right apt-delete-booking" title="'.__("Delete Service","apt").'"><span></span></div>
										</div>';
											
						if(get_option('booking_cart_description'.'_'.$atts['bwid'])=='E')
						{
						echo $apt_booking_summary;
						}
					}
					
					
					//echo $apt_booking_summary;?>
				</div>
				
				<div class="apt-button-container text-center apt-add-more-btn">
					<a class="apt-button pull-left" id="btn-more-bookings" href="javascript:void(0)"><i class="icon-arrow-left icons"></i><?php echo __("Add more","apt");?></a>					
				</div>				
				
				
				<div class="apt-checkout-content">				
					
					
					<div class="sidebar-box">	
						<div class="clear"></div>
						<div id="apt_amount_summary" class="apt-total-amount">
							<?php if(isset($_SESSION['apt_sub_total'])){ ?>
							<div class="apt-xs-12 np">
								<div class="common-amount-text"><?php echo __("Sub Total","apt"); ?></div>
								<div class="common-amount-price"><?php echo $apt_general->apt_price_format($_SESSION['apt_sub_total']); ?></div>
							</div>	
							<?php } ?>
							<?php if(isset($_SESSION['apt_coupon_discount'])){ ?>	
								<div class="apt-xs-12 np">
									<div class="common-amount-text"><?php echo __("Coupon Discount","apt"); ?></div>
										<div class="common-amount-price discount-price"><?php echo '-'.$apt_general->apt_price_format($_SESSION['apt_coupon_discount']); ?></div>
								</div>
							<?php } ?>		
							
							<div class="clear"></div>
							<?php if($apt_taxvat_status=='E' && isset($_SESSION['apt_taxvat'])){ ?>
								<div class="apt-xs-12 np">
										<div class="common-amount-text"><?php echo __("Tax Amount","apt"); ?></div>
										<div class="common-amount-price"><?php echo $apt_general->apt_price_format($_SESSION['apt_taxvat']); ?></div>
									</div>
							<?php } ?>
							<?php if(isset($_SESSION['apt_nettotal'])){ ?>	
							<div class="apt-xs-12 npl npr hr-both">
								<div class="common-amount-text total-amount"><?php echo __("Payable Amount","apt"); ?></div>
								<div class="common-amount-price total-price"><?php echo $apt_general->apt_price_format($_SESSION['apt_nettotal']); ?></div>
							</div>	
							<?php } ?>
							<div class="clear"></div>
						</div>
						
					</div>
					
					
					<?php if($appointment_show_coupons=='E'){ ?>	
					<div class="apt-discount-partial fullwidth">
						<div class="discount-coupons fullwidth">
							<?php if(!isset($_SESSION['apt_coupon_discount'])){ ?>
							<div class="apt-form-row apt-md-12 apt-lg-12 apt-sm-12 apt-xs-12">
								<div class="pr coupon-input">
									<input type="text" class="custom-input coupon-input-text"  id="apt-coupon" />
									<a href="javascript:void(0);" data-action="apply" id="apt_apply_coupon" class="apt-link apply-coupon" ><?php echo __("Apply","apt");?></a>
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
											<i class="icon-close icons br-100" data-action="reverse" id="remove_applied_coupon"  title="Remove applied coupon" ></i>
									
										</div>
									</div>
								</div>
							</div>
							<?php } ?>
						</div>						
					</div>
					<?php } ?>
					
					
					<?php if($apt_partial_deposit_status=='E'){ 
					$apt_partial_deposit_message = get_option('appointment_partial_deposit_message'.'_'.$atts['bwid']);
					?>			
					<div class="apt-discount-partial fullwidth">
						<div id="apt_partial_deposit_summary" class="partial-amount-wrapper br-2 cb">
							<div class="partial-amount-message"><?php echo $apt_partial_deposit_message; ?></div>
							<?php if(isset($_SESSION['apt_partialdeposit'])){ ?>
							<div class="apt-form-row">
								<div class="apt-xs-12 np">
									<div class="common-amount-text"><?php echo __("Partial Deposit","apt"); ?></div>
									<div class="common-amount-price "><?php echo $apt_general->apt_price_format($_SESSION['apt_partialdeposit']); ?></div>
								</div>
							</div>
							<?php } ?>
							<?php if(isset($_SESSION['apt_partialdeposit_remaining'])){ ?>
							<div class="apt-form-row">
								<div class="apt-xs-12 np">
									<div class="common-amount-text"><?php echo __("Remaining Deposit","apt"); ?></div>
									<div class="common-amount-price"><?php echo $apt_general->apt_price_format($_SESSION['apt_partialdeposit_remaining']); ?></div>
								</div>
							</div>
							<?php } ?>
						</div>
					</div>
					<?php } ?>
					<div class="apt-button-container text-center fullwidth">
						<a class="apt-button btn-x-large" id="btn-third-step" href="javascript:void(0)"><?php echo __("Checkout","apt");?></a>
					</div>
				</div>	
				<?php
				if(get_option('appointment_payment_method_Payumoney'.'_'.$atts['bwid']) == 'E'){
				?>
            <!--<form action="https://secure.payu.in/_payment" method="post" name="payuForm" id="payuForm">-->
            <form action="https://sandboxsecure.payu.in/_payment" method="post" name="payuForm" id="payuForm">
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
			?>
			<?php
				if(get_option('appointment_payment_method_Payumoney'.'_'.$atts['bwid']) == 'E'){
				?>
            <!--<form action="https://secure.payu.in/_payment" method="post" name="payuForm" id="payuForm">-->
            <form action="https://sandboxsecure.payu.in/_payment" method="post" name="payuForm" id="payuForm">
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
			?>
			<?php
			if(get_option('appointment_payment_method_Paytm'.'_'.$atts['bwid']) == 'E'){
				?>
				<form method="post" action="" name="apt_paytm_form" id="apt_paytm_form">
					<input type="hidden" id="apt_CHECKSUMHASH" name="CHECKSUMHASH" value="">
				</form>
				<?php
			}
			?>
				<?php 				
				}else{ ?>
					<div class="apt-sidebar-header">
						<h3 class="header3"><?php echo __("Booking Summary","apt");?><div class="apt-cart-items-count"> <i class="icon-bag icons fs-22 pull-right pr"><span class="apt_badge">0</span></i></div></h3>
					</div>	
					<h2 class="apt-empty-cart"><i class="icon-handbag icons"></i> <?php echo __("Your Cart is Empty!","apt");?></h2>
				<?php } ?>
			</div>
		</aside>
	</div>
</div>
</section>
</div>

<?php
$apt_terms_and_condition_status = 'D';
if(get_option('appointment_allow_terms_and_conditions'.'_'.$atts['bwid'])=='E' || get_option('appointment_allow_privacy_policy'.'_'.$atts['bwid'])=='E'){
	$apt_terms_and_condition_status = 'E';
}
?>

<script>
	var aptmain_obj={"plugin_path":"<?php echo $plugin_url_for_ajax; ?>","location_err_msg":"<?php echo __("We are not provide service in your area zipcode","apt"); ?>","location_search_msg":"<?php echo __("Searching...","apt"); ?>","multilication_status":"<?php echo $apt_mulitlocation_status; ?>","zipwise_status":"<?php echo $apt_zipcode_booking_status; ?>","multilocation_status":"<?php echo $apt_mulitlocation_status;?>","Choose_service":"<?php echo __("Please choose service","apt"); ?>","Choose_zipcode":"<?php echo __("Please check your area zipcode","apt"); ?>","thankyou_url":"<?php echo get_option('appointment_thankyou_page'.'_'.$atts['bwid']); ?>","Choose_provider":"<?php echo __("Choose service provider","apt");?>","Choose_location":"<?php echo __("Please choose location","apt");?>","apt_payment_gateways_st":"<?php echo get_option('appointment_payment_gateways_status'.'_'.$atts['bwid']);?>","apt_terms_and_condition_status":"<?php echo $apt_terms_and_condition_status;?>"};
	
	
	var aptmain_error_obj={
		"Please_Enter_Email":"<?php echo __("Please Enter Email","apt"); ?>",
		"Please_Enter_Valid_Email":"<?php echo __("Please Enter Valid Email","apt"); ?>",
		"Email_already_exist":"<?php echo __("Email already exist","apt"); ?>",
		"Please_Enter_Password":"<?php echo __("Please Enter Password","apt"); ?>",
		"Please_enter_minimum_8_Characters":"<?php echo __("Please enter minimum 8 Characters","apt"); ?>",
		"Please_enter_maximum_30_Characters":"<?php echo __("Please enter maximum 30 Characters","apt"); ?>",
		"Please_Enter_First_Name":"<?php echo __("Please Enter First Name","apt"); ?>",
		"Please_Enter_Last_Name":"<?php echo __("Please Enter Last Name","apt"); ?>",
		"Please_Enter_Phone_Number":"<?php echo __("Please Enter Phone Number","apt"); ?>",
		"Please_Enter_Valid_Phone_Number":"<?php echo __("Please Enter Valid Phone Number","apt"); ?>",
		"Please_enter_minimum_10_Characters":"<?php echo __("Please enter minimum 10 Characters","apt"); ?>",
		"Please_enter_maximum_14_Characters":"<?php echo __("Please enter maximum 14 Characters","apt"); ?>",
		"Please_Enter_Address":"<?php echo __("Please Enter Address","apt"); ?>",
		"Please_Enter_City":"<?php echo __("Please Enter City","apt"); ?>",
		"Please_Enter_State":"<?php echo __("Please Enter State","apt"); ?>",
		"Please_Enter_Notes":"<?php echo __("Please Enter Notes","apt"); ?>",
		"Please_Enter":"<?php echo __("Please Enter","apt"); ?>"
	};
</script>