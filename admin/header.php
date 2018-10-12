<?php
session_start();
global $current_user;
$appointment_sampledata =get_option('appointment_sample_dataids' . '_' . get_current_user_id());
$apt_currency_symbol = get_option('appointment_currency_symbol' . '_' . get_current_user_id());
	$user_sp = '';
	$user_sp_manager = '';
	$current_user = wp_get_current_user();
	$info = get_userdata( $current_user->ID );

	if(current_user_can('apt_staff') && !current_user_can('manage_options')) {
	  $user_sp = 'Y';
	}if(current_user_can('business_manager') && !current_user_can('manage_options')) {
	  $user_sp_manager = 'Y';
	}
	if ( class_exists( 'WooCommerce' ) && current_user_can('apt_staff') ) {
		$user_sp = 'Y';	
	}
	if ( class_exists( 'WooCommerce' ) && current_user_can('business_manager') ) {
		$user_sp_manager = 'Y';				
	}
	$plugin_url_for_ajax = plugins_url('',  dirname((__FILE__)));
	if(current_user_can('manage_options') || current_user_can('business_manager')){ 		
		if(get_option('appointment_multi_location' . '_' . get_current_user_id())=='E'){
			$location = new appointment_location();
			$location->business_owner_id = get_current_user_id();
			$location_sortby = get_option('appointment_location_sortby'.'_'.get_current_user_id() );
			$apt_locations = $location->readAll('','','');
			
			if(sizeof($apt_locations)==0 && isset($_GET['page']) && $_GET['page']!='location_submenu'){
					header('Location:'.site_url().'/wp-admin/admin.php?page=location_submenu');
				}
			$temp_locatio_name = array();
			if((!isset($_SESSION['apt_location']) || $_SESSION['apt_location']==0) && $_GET['page']!='location_submenu'){$_SESSION['apt_location'] = $apt_locations[0]->id; 
			header('Location:'.site_url().'/wp-admin/admin.php?page=location_submenu');
			}					
				
		}else{					
			if(!isset($_SESSION['apt_location']) || $_SESSION['apt_location']!=0){ $_SESSION['apt_location'] = 0; header('Location:'.site_url().'/wp-admin/admin.php?page=services_submenu');}					
		}
		}else{
		 if(get_option('appointment_multi_location' . '_' . get_current_user_id())=='E' && ($user_sp_manager=='Y' || $user_sp=='Y')){

			$currentuser_location = get_user_meta($current_user->ID,'staff_location');	
				if(!isset($_SESSION['apt_location']) || ($_SESSION['apt_location']!=$currentuser_location[0])){
					if($user_sp=='Y'){$_SESSION['apt_booking_filterstaff']=$current_user->ID;}else{unset($_SESSION['apt_booking_filterstaff']);}
					$_SESSION['apt_location'] = $currentuser_location[0];
					header('Location:'.site_url().'/wp-admin/admin.php?page=appointments_submenu');
				}
			}else{
				if(!isset($_SESSION['apt_location'])){$_SESSION['apt_location']=0;}
			}		
		
		}		
				
/* Service Validation Messages */				
	$categorytitle_err_msg = __('Please enter category title','apt');			
	$servicetitle_err_msg = __('Please enter service title','apt');			
	$servicedescription_err_msg = __('Please enter service description','apt');			
	$serviceprice_err_msg = __('Please enter service price','apt');			
	$servicepricedigit_err_msg = __('Please enter price in digits','apt');		
	$serviceofferpricegreater_err_msg = __('Offered price should be less then default price','apt');	
	$servicecategory_err_msg = __('Please select service category','apt');		
	$servicehrsrange_err_msg = __('Please enter minimum 1 hours maximum 23 hours','apt');		
	$servicemins_err_msg = __('Please enter value minimum 5 minutes','apt');		
	$serviceminsrange_err_msg = __('Please enter minimum 5 mintues maximum 59 mintues','apt');	
	$servicenumpatt_err_msg = __('Please enter value in digits only','apt');	
	
	/* Service Addon Validation Messages */					
	$serviceaddontitle_err_msg = __('Please enter addon title','apt');				
	$serviceaddonmaxqty_err_msg = __('Please enter valid max addon quantity','apt');			
	$serviceaddon_price_err_msg = __('Please enter addon price','apt');	
	$serviceaddon_validprice_err_msg = __('Please enter valid addon price','apt');	
	$serviceaddon_qty_err_msg = __('Please enter addon pricing quantity','apt');	
	$serviceaddon_validqty_err_msg = __('Please enter valid addon pricing quantity','apt');	

/* Location Validation Messages */	
	$locationtiitle_err_msg = __('Please enter location title','apt');			
	$locationemail_err_msg = __('Please enter email','apt');			
	$locationinvalidemail_err_msg = __('Please enter valid email','apt');			
	$locationphone_err_msg = __('Please enter phone','apt');			
	$locationvalidphone_err_msg = __('Please enter valid phone number','apt');			
	$locationinvalidphone_err_msg = __('Please enter valid phone','apt');			
	$locationaddress_err_msg = __('Please enter  address','apt');			
	$locationcity_err_msg = __('Please enter city','apt');	
	$locationstate_err_msg = __('Please enter state','apt');	
	$locationzip_err_msg = __('Please enter zip/postal code','apt');	
	$locationcountry_err_msg = __('Please enter country','apt');	
	
/* Staff Validation Messages */	
	$staffusername_err_msg = __('Please enter username','apt');	
	$staffusernameexist_err_msg = __('Username exist or not valid','apt');	
	$staffpassword_err_msg = __('Please enter password','apt');	
	$staffemail_err_msg = __('Please enter email','apt');	
	$staffemailexist_err_msg = __('Email exist or not valid','apt');	
	$stafffullname_err_msg = __('Please enter fullname','apt');	
	$staffselect_err_msg = __('Please select existing user','apt');	
	$staffvalidphone_err_msg = __('Please enter valid phone number.','apt');	
/* Coupon Validation Messages */
	$cuponcode_err_msg = __('Please enter promocode','apt');	
	$cuponvalue_err_msg = __('Please enter promocode value','apt');	
	$cuponvalueinvalid_err_msg = __('Please enter valid promocode value','apt');	
	$cuponlimit_err_msg = __('Please enter promocode limit','apt');	
	$cuponlimitinvalid_err_msg = __('Please enter valid promocode limit','apt');	
/* SMS Notifications Validation Messages */
	$twilliosid_err_msg = __('Please enter twillio account SID','apt');	
	$twillioauthtoken_err_msg = __('Please enter twillio auth token','apt');	
	$twilliosendernum_err_msg = __('Please enter twillio sender number','apt');	
	$twillioadminnum_err_msg = __('Please enter twillio admin account number','apt');	
	
	$plivosid_err_msg = __('Please enter plivo account SID','apt');	
	$plivoauthtoken_err_msg = __('Please enter plivo auth token','apt');	
	$plivosendernum_err_msg = __('Please enter plivo sender number','apt');	
	$plivoadminnum_err_msg = __('Please enter plivo admin account number','apt');
	
	$nexmoapi_err_msg = __('Please enter nexmo API','apt');	
	$nexmoapisecert_err_msg = __('Please enter nexmo API Secert','apt');	
	$nexmofromnum_err_msg = __('Please enter nexmo from number','apt');	
	$nexmoadminnum_err_msg = __('Please enter nexmo admin account number','apt');
			
	
/* Object Content For Appointment Calender */
$language = get_locale();
$ak_wplang = explode('_',$language);
$wpTimeFormatorg = get_option('time_format'); 
$arr = str_split($wpTimeFormatorg);
$slashcounter = 0;
$wpTimeFormat='';
	foreach($arr as $singlechar){
		if($singlechar=='\\'){
			$slashcounter=1;
			$wpTimeFormat .="[";
			continue;
		}elseif($slashcounter!=1 && ($singlechar=='g' || $singlechar=='G' || $singlechar=='i')){
			if($singlechar=='g'){ $wpTimeFormat .='h'; }
			if($singlechar=='G'){ $wpTimeFormat .='H'; }
			if($singlechar=='i'){ $wpTimeFormat .='mm'; }
		}elseif($slashcounter==1){
			$wpTimeFormat .=$singlechar."]";
			$slashcounter=0;
		}else{
			$wpTimeFormat .=$singlechar;  
		}
   } 	
	
/* Existing Custom Form Fields */ 
$appointment_custom_formfields = json_decode(stripslashes(get_option('appointment_custom_form' . '_' . get_current_user_id())),true); 
$appointment_custom_formfields_val = '';
$totallength = ($appointment_custom_formfields)?sizeof($appointment_custom_formfields):0;
if($totallength>0){
	$lengthcounter = 1;
	foreach($appointment_custom_formfields as $appointment_custom_formfield){
		if($totallength==$lengthcounter){
			$appointment_custom_formfields_val .= json_encode($appointment_custom_formfield);
		}else{
			$appointment_custom_formfields_val .= json_encode($appointment_custom_formfield).',';
		}
		$lengthcounter++;
	} 
}	
	
	
?>	

<script>
var header_object ={'plugin_path':'<?php echo $plugin_url_for_ajax; ?>','site_url':'<?php echo site_url();?>','defaultmedia':'<?php echo $plugin_url_for_ajax.'/assets/images/';?>','ak_wp_lang':'<?php echo $ak_wplang[0]; ?>','cal_first_day':'<?php echo get_option('start_of_week'); ?>','time_format':'<?php echo $wpTimeFormat; ?>','mb_status':'<?php echo get_option('appointment_guest_user_checkout' . '_' . get_current_user_id()); ?>','multilocation_st':'<?php echo get_option('appointment_multi_location' . '_' . get_current_user_id());?>','reviews_st':'<?php echo get_option('appointment_reviews_status' . '_' . get_current_user_id());?>','full_cal_defaultdate':'<?php echo date_i18n('Y-m-d');?>','appointment_plivo_ccode_alph':'<?php echo get_option('appointment_plivo_ccode_alph' . '_' . get_current_user_id());?>','appointment_twilio_ccode_alph':'<?php echo get_option('appointment_twilio_ccode_alph' . '_' . get_current_user_id());?>','appointment_nexmo_ccode_alph':'<?php echo get_option('appointment_nexmo_ccode_alph' . '_' . get_current_user_id());?>','appointment_textlocal_ccode_alph':'<?php echo get_option('appointment_textlocal_ccode_alph' . '_' . get_current_user_id());?>','appointment_custom_formfields_val':'<?php echo $appointment_custom_formfields_val;?>'};

var admin_validation_err_msg = {'categorytitle_err_msg':'<?php echo $categorytitle_err_msg;?>',
								'servicetitle_err_msg':'<?php echo $servicetitle_err_msg;?>',
								'servicedescription_err_msg':'<?php echo $servicedescription_err_msg;?>',
								'serviceprice_err_msg':'<?php echo $serviceprice_err_msg;?>',
								'servicepricedigit_err_msg':'<?php echo $servicepricedigit_err_msg;?>',
								'serviceofferpricegreater_err_msg':'<?php echo $serviceofferpricegreater_err_msg;?>',
								'servicecategory_err_msg':'<?php echo $servicecategory_err_msg;?>',
								'servicehrsrange_err_msg':'<?php echo $servicehrsrange_err_msg;?>',
								'serviceminsrange_err_msg':'<?php echo $serviceminsrange_err_msg;?>',
								'servicemins_err_msg':'<?php echo $servicemins_err_msg;?>',
								'servicenumpatt_err_msg':'<?php echo $servicenumpatt_err_msg;?>',
								'serviceaddontitle_err_msg':'<?php echo $serviceaddontitle_err_msg;?>',
								'serviceaddonmaxqty_err_msg':'<?php echo $serviceaddonmaxqty_err_msg;?>',
								'serviceaddon_price_err_msg':'<?php echo $serviceaddon_price_err_msg;?>',
								'serviceaddon_validprice_err_msg':'<?php echo $serviceaddon_validprice_err_msg;?>',
								'serviceaddon_qty_err_msg':'<?php echo $serviceaddon_qty_err_msg;?>',
								'serviceaddon_validqty_err_msg':'<?php echo $serviceaddon_validqty_err_msg;?>',								
								
								'locationtiitle_err_msg':'<?php echo $locationtiitle_err_msg;?>',
								'locationemail_err_msg':'<?php echo	$locationemail_err_msg;?>',
								'locationinvalidemail_err_msg':'<?php echo $locationinvalidemail_err_msg;?>', 	
								'locationphone_err_msg':'<?php echo	$locationphone_err_msg;?>',	
								'locationvalidphone_err_msg':'<?php echo	$locationvalidphone_err_msg;?>',	
								'locationinvalidphone_err_msg':'<?php echo $locationinvalidphone_err_msg;?>',		
								'locationaddress_err_msg':'<?php echo $locationaddress_err_msg;?>', 	
								'locationcity_err_msg':'<?php echo $locationcity_err_msg;?>', 
								'locationstate_err_msg':'<?php echo $locationstate_err_msg;?>',
								'locationzip_err_msg':'<?php echo $locationzip_err_msg;?>',
								'locationcountry_err_msg':'<?php echo $locationcountry_err_msg;?>',
								
								'staffusername_err_msg':'<?php echo $staffusername_err_msg;?>',
								'staffusernameexist_err_msg':'<?php echo $staffusernameexist_err_msg;?>',
								'staffpassword_err_msg':'<?php echo $staffpassword_err_msg;?>',
								'staffemail_err_msg':'<?php echo $staffemail_err_msg;?>',
								'staffemailexist_err_msg':'<?php echo $staffemailexist_err_msg;?>',
								'stafffullname_err_msg':'<?php echo $stafffullname_err_msg;?>',
								'staffselect_err_msg':'<?php echo $staffselect_err_msg; ?>',
								'staffvalidphone_err_msg':'<?php echo $staffvalidphone_err_msg; ?>',
								
								'cuponcode_err_msg':'<?php echo $cuponcode_err_msg; ?>',
								'cuponvalue_err_msg':'<?php echo $cuponvalue_err_msg; ?>',
								'cuponvalueinvalid_err_msg':'<?php echo $cuponvalueinvalid_err_msg; ?>',
								'cuponlimit_err_msg':'<?php echo $cuponlimit_err_msg; ?>',
								'cuponlimitinvalid_err_msg':'<?php echo $cuponlimitinvalid_err_msg; ?>',
								
								'twilliosid_err_msg':'<?php echo $twilliosid_err_msg; ?>',
								'twillioauthtoken_err_msg':'<?php echo $twillioauthtoken_err_msg; ?>',
								'twilliosendernum_err_msg':'<?php echo $twilliosendernum_err_msg; ?>',
								'twillioadminnum_err_msg':'<?php echo $twillioadminnum_err_msg; ?>',
								
								'plivosid_err_msg':'<?php echo $plivosid_err_msg; ?>',
								'plivoauthtoken_err_msg':'<?php echo $plivoauthtoken_err_msg; ?>',
								'plivosendernum_err_msg':'<?php echo $plivosendernum_err_msg; ?>',
								'plivoadminnum_err_msg':'<?php echo $plivoadminnum_err_msg; ?>',
								
								'nexmoapi_err_msg':'<?php echo $nexmoapi_err_msg; ?>',
								'nexmoapisecert_err_msg':'<?php echo $nexmoapisecert_err_msg; ?>',
								'nexmofromnum_err_msg':'<?php echo $nexmofromnum_err_msg; ?>',
								'nexmoadminnum_err_msg':'<?php echo $nexmoadminnum_err_msg; ?>'
								
			
}
var appearance_setting = {"default_country_code":"<?php echo get_option('appointment_default_country_short_code' . '_' . get_current_user_id()); ?>"};	
/* var formoptions = { "formhtml": }; */
</script>	
	
<?php echo '<style>
#apt #apt-top-nav .navbar .nav > li > a{
	color: '.get_option('appointment_admin_color_text' . '_' . get_current_user_id()).' !important;
}
/* Primary Color */
#apt #apt-main-navigation{
	background: '.get_option('appointment_admin_color_primary' . '_' . get_current_user_id()).' !important;
}
#apt .loader .apt-second{
	border: 3px solid '.get_option('appointment_admin_color_primary' . '_' . get_current_user_id()).' !important;
		border-bottom-color: transparent !important;
}
#apt .apt-notification-main .notification-header #apt-close-notifications:hover{
	background-color: '.get_option('appointment_admin_color_primary' . '_' . get_current_user_id()).' !important;
}
#apt .tooltip-arrow{
	border-right-color: '.get_option('appointment_admin_color_primary' . '_' . get_current_user_id()).' !important;
}

/* calendar page */
#apt .fc-toolbar {
	border-top: 1px solid '.get_option('appointment_admin_color_primary' . '_' . get_current_user_id()).' !important;
	border-left: 1px solid '.get_option('appointment_admin_color_primary' . '_' . get_current_user_id()).' !important;
	border-right: 1px solid '.get_option('appointment_admin_color_primary' . '_' . get_current_user_id()).' !important;
}
#apt .fc-toolbar {
	background-color: '.get_option('appointment_admin_color_primary' . '_' . get_current_user_id()).' !important;
}
#apt #apt-dashboard .apt-dash-icon.today{
	background-color: '.get_option('appointment_admin_color_primary' . '_' . get_current_user_id()).' !important;
	color: '.get_option('appointment_admin_color_bg_text' . '_' . get_current_user_id()).' !important;
}
#apt .apt-notification-main .notification-header a.apt-clear-all{
	color: '.get_option('appointment_admin_color_bg_text' . '_' . get_current_user_id()).' !important;
}
/* Secondary color */
#apt #apt-top-nav .navbar .nav > li > a:hover,
#apt #apt-top-nav .navbar .nav > .active > a,
#apt #apt-top-nav .navbar .nav > .active > a:focus{
	background: '.get_option('appointment_admin_color_secondary' . '_' . get_current_user_id()).' !important;
}
#apt a#apt-notifications i.icon-bell.apt-pulse.apt-new-booking{
	color: '.get_option('appointment_admin_color_secondary' . '_' . get_current_user_id()).' !important;
}
#apt .loader .apt-third{
	border: 3px solid '.get_option('appointment_admin_color_secondary' . '_' . get_current_user_id()).' !important;
		border-top-color: transparent !important; 
}
#apt  #apt-main-navigation .navbar .nav.apt-nav-tab li:before,
#apt  #apt-main-navigation .navbar .nav.apt-nav-tab li:after{
	border-left-color: '.get_option('appointment_admin_color_secondary' . '_' . get_current_user_id()).' !important;
	border-right-color: '.get_option('appointment_admin_color_secondary' . '_' . get_current_user_id()).' !important;
}	

#apt a#apt-notifications:hover i.fa-angle-down,
#apt .fc button:hover,
#apt button.fc-today-button:hover{
	color: '.get_option('appointment_admin_color_secondary' . '_' . get_current_user_id()).' !important;
}


/* admin color bg text  and  Secondary color */
#apt #apt-dashboard .apt-dash-icon.this-year,
#apt .apt-notification-main .notification-header{
	background-color: '.get_option('appointment_admin_color_secondary' . '_' . get_current_user_id()).' !important;
	color: '.get_option('appointment_admin_color_bg_text' . '_' . get_current_user_id()).' !important;
}

#apt #apt-top-nav .navbar .nav > .active > a,
#apt #apt-top-nav .navbar .nav > .active > a:focus{
	background-color: '.get_option('appointment_admin_color_secondary' . '_' . get_current_user_id()).' !important;
	color: '.get_option('appointment_admin_color_bg_text' . '_' . get_current_user_id()).' !important;
}
#apt #apt-main-navigation .navbar .nav.apt-nav-tab > li > a:hover,
#apt #apt-main-navigation .navbar .nav.apt-nav-tab > .active > a,
#apt #apt-main-navigation .navbar .nav.apt-nav-tab > .active > a:focus{
	background-color: '.get_option('appointment_admin_color_secondary' . '_' . get_current_user_id()).' !important;
	color: '.get_option('appointment_admin_color_bg_text' . '_' . get_current_user_id()).' !important;
}

/* admin color bg text */
#apt #apt-main-navigation .navbar .nav > li > a{
	color: '.get_option('appointment_admin_color_bg_text' . '_' . get_current_user_id()).' !important;
}

#apt .fc button,
#apt .apt-notification-main .notification-header #apt-close-notifications{
	color: '.get_option('appointment_admin_color_bg_text' . '_' . get_current_user_id()).' !important;
}

#apt .loader .apt-first{
	border: 3px solid '.get_option('appointment_admin_color_bg_text' . '_' . get_current_user_id()).' !important;
		border-right-color: transparent !important;
}


/* Desktops and laptops ----------- */
@media only screen  and (min-width : 768px) and (max-width : 1250px) {
	#apt #apt-main-navigation .navbar{
		background-color: '.get_option('appointment_admin_color_primary' . '_' . get_current_user_id()).' !important;
	}
	
	#apt #apt-main-navigation .navbar-header,
	#apt #apt-main-navigation .navbar .nav.apt-nav-tab > .active > a,
	#apt #apt-main-navigation .navbar .nav.user-nav-bar > .active > a,
	#apt #apt-main-navigation .navbar .nav.apt-nav-tab > li > a:hover,
	#apt #apt-main-navigation .navbar .nav.user-nav-bar > li > a:hover {
		color: '.get_option('appointment_admin_color_secondary' . '_' . get_current_user_id()).' !important;
		background-color: unset !important;
	}
		
}


/* iPads (portrait and landscape) ----------- */
@media only screen and (min-width : 768px) and (max-width : 1024px) {
	#apt #apt-main-navigation .navbar-header,
	#apt #apt-main-navigation .navbar .nav.apt-nav-tab > li > a:hover,
	#apt #apt-main-navigation .navbar .nav.user-nav-bar > li > a:hover {
		color: '.get_option('appointment_admin_color_secondary' . '_' . get_current_user_id()).' !important;
	}
	
}
/* iPads (landscape) ----------- */
@media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : landscape) {
	#apt #apt-main-navigation .navbar .nav.apt-nav-tab > li > a:hover,
	#apt #apt-main-navigation .navbar .nav.user-nav-bar > li > a:hover {
		background-color: '.get_option('appointment_admin_color_secondary').' ;
		color: '.get_option('appointment_admin_color_text' . '_' . get_current_user_id()).' !important;
	}

}
/* iPads (portrait) ----------- */
@media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : portrait) {
	#apt #apt-top-nav .navbar-header,
	#apt #apt-main-navigation .navbar-header,
	#apt #apt-main-navigation .navbar .nav.apt-nav-tab > .active > a,
	#apt #apt-main-navigation .navbar .nav.user-nav-bar > .active > a,
	#apt #apt-top-nav .navbar .nav > .active > a:focus,
	#apt #apt-top-nav .navbar-nav > li > a:hover,
	#apt #apt-main-navigation .navbar .nav.apt-nav-tab > li > a:hover,
	#apt #apt-main-navigation .navbar .nav.user-nav-bar > li > a:hover {
		color: '.get_option('appointment_admin_color_secondary' . '_' . get_current_user_id()).' !important;
	}
	#apt #apt-main-navigation .navbar .nav.apt-nav-tab > .active > a,
	#apt #apt-main-navigation .navbar .nav.user-nav-bar > .active > a,
	#apt #apt-top-nav .navbar .nav > .active > a:focus{
		background: unset !important;
	}
}	
/********** iPad 3 **********/
@media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : landscape) and (-webkit-min-device-pixel-ratio : 2) {
	#apt #apt-main-navigation .navbar .nav.apt-nav-tab > li > a:hover,
	#apt #apt-main-navigation .navbar .nav.user-nav-bar > li > a:hover {
		background-color: '.get_option('appointment_admin_color_secondary' . '_' . get_current_user_id()).' ;
		color: '.get_option('appointment_admin_color_text' . '_' . get_current_user_id()).' !important;
	}
}
@media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : portrait) and (-webkit-min-device-pixel-ratio : 2) {	
	#apt #apt-top-nav .navbar-header,
	#apt #apt-main-navigation .navbar-header,
	#apt #apt-main-navigation .navbar .nav.apt-nav-tab > .active > a,
	#apt #apt-main-navigation .navbar .nav.user-nav-bar > .active > a,
	#apt #apt-top-nav .navbar .nav > .active > a:focus,
	#apt #apt-top-nav .navbar-nav > li > a:hover,
	#apt #apt-main-navigation .navbar .nav.apt-nav-tab > li > a:hover,
	#apt #apt-main-navigation .navbar .nav.user-nav-bar > li > a:hover {
		color: '.get_option('appointment_admin_color_secondary' . '_' . get_current_user_id()).' !important;
	}
	#apt #apt-main-navigation .navbar .nav.apt-nav-tab > .active > a,
	#apt #apt-main-navigation .navbar .nav.user-nav-bar > .active > a,
	#apt #apt-top-nav .navbar .nav > .active > a:focus{
		background: unset !important;
	}
}
/* Smartphones (landscape) ----------- */
@media only screen and (max-width: 767px) {
	#apt #apt-main-navigation .navbar{
		background-color: '.get_option('appointment_admin_color_primary' . '_' . get_current_user_id()).' !important;
	}
	
	#apt #apt-top-nav .navbar-header,
	#apt #apt-main-navigation .navbar-header,
	#apt #apt-main-navigation .navbar .nav.apt-nav-tab > .active > a,
	#apt #apt-main-navigation .navbar .nav.user-nav-bar > .active > a,
	#apt #apt-top-nav .navbar .nav > .active > a:focus,
	#apt #apt-top-nav .navbar-nav > li > a:hover,
	#apt #apt-main-navigation .navbar .nav.apt-nav-tab > li > a:hover,
	#apt #apt-main-navigation .navbar .nav.user-nav-bar > li > a:hover {
		color: '.get_option('appointment_admin_color_secondary' . '_' . get_current_user_id()).' !important;
		background-color: unset !important;
	}
	/*
	#apt #apt-main-navigation .navbar .nav.apt-nav-tab > .active > a,
	#apt #apt-main-navigation .navbar .nav.user-nav-bar > .active > a,
	#apt #apt-top-nav .navbar .nav > .active > a:focus{
		background: unset !important;
	}
	*/
	
}	
/* Smartphones (portrait and landscape) ----------- */
@media only screen and (min-width : 320px) and (max-width : 480px) {
	
	#apt #apt-top-nav .navbar-header,
	#apt #apt-main-navigation .navbar-header,
	#apt #apt-main-navigation .navbar .nav.apt-nav-tab > .active > a,
	#apt #apt-main-navigation .navbar .nav.user-nav-bar > .active > a,
	#apt #apt-top-nav .navbar .nav > .active > a:focus,
	#apt #apt-top-nav .navbar-nav > li > a:hover,
	#apt #apt-main-navigation .navbar .nav.apt-nav-tab > li > a:hover,
	#apt #apt-main-navigation .navbar .nav.user-nav-bar > li > a:hover {
		color: '.get_option('appointment_admin_color_secondary' . '_' . get_current_user_id()).' !important;
		background-color: unset !important;
	}
	/*
	#apt #apt-main-navigation .navbar .nav.apt-nav-tab > .active > a,
	#apt #apt-main-navigation .navbar .nav.user-nav-bar > .active > a,
	#apt #apt-top-nav .navbar .nav > .active > a:focus{
		background: unset !important;
	}
	*/
}
</style>' ;

if(is_rtl()){
	echo "<script type='text/javascript'>
	jQuery(document).ready(function(){
		jQuery('#apt').addClass('aptdbrtl');
	});
	
	</script>";	
}




 
?>

<!-- main wrapper -->
<div class="apt-wrapper" id="apt">
<!-- all alerts, success messages -->
<div class="apt-alert-msg-show-main mainheader_message">		
	<div class="apt-all-alert-messags alert alert-success mainheader_message_inner">
		<a href="#" class="close" data-dismiss="alert">&times;</a>
		<strong><?php echo __("Success!","apt");?></strong> <span id="apt_sucess_message"><?php echo __("Updated successfully","apt");?></span>
	</div>
</div>	
<div class="apt-alert-msg-show-main mainheader_message_fail">		
	<div class="apt-all-alert-messags alert alert-danger mainheader_message_inner_fail">
		<a href="#" class="close" data-dismiss="alert">&times;</a>
		<strong><?php echo __("Failed!","apt");?></strong> <span id="apt_sucess_message_fail"><?php echo __("Updated successfully","apt");?></span>
	</div>
</div>	
<!-- loader -->
<div class="apt-loading-main" >
	<div class="loader">
		<span class="apt-first"></span>
		<span class="apt-second"></span>
		<span class="apt-third"></span>
	</div>
</div>

	<header class="apt-header">
	<?php if(!isset($current_user->caps['apt_client']) || isset($current_user->caps['administrator'])){ ?>
		<div id="apt-top-nav" class="navbar-inner">
            <nav role="navigation" class="navbar">
                <!-- Brand and toggle get grouped for better mobile display -->
				<div class="containerd">
                <div class="navbar-header">
                    <button type="button" data-target="#navbarCollapsetop" data-toggle="collapse" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                        <i class="fa fa-cog"></i>
                    </button>
                    <a href="<?php echo site_url(); ?>" class="navbar-brand"><img src="<?php echo $plugin_url_for_ajax; ?>/assets/images/AP-logo.png" /></a>
				</div>
				<?php
				$bwidFDB = (current_user_can('business_manager') || current_user_can('manage_options'))?get_current_user_id():get_user_meta(get_current_user_id(), 'staff_bwid', true);
				?>
                <input type="hidden" name="bwid" value="<?php echo $bwidFDB; ?>" />
                <!-- Collection of nav links and other content for toggling -->
                <div id="navbarCollapsetop" class="collapse navbar-collapse">
				
					<!--<ul class="nav navbar-nav">
						<li><a href="?page=frontend_shortcode_submenu" class="btn btn-link btn-no-bg">How to use shortcode?</a></li>
					</ul> 
					<ul class="nav navbar-nav">
						<li><a href="?page=whats_new_submenu" class="btn btn-link btn-no-bg">Version <?php //echo get_option('appointment_version');?></a></li>
					</ul>-->
					<?php if($user_sp_manager=='Y' || current_user_can('manage_options') || current_user_can('business_manager')){?>
						<ul class="nav navbar-nav navbar-right">
							<?php if(get_option('appointment_multi_location' . '_'. get_current_user_id())=='E' && current_user_can('manage_options') || current_user_can('business_manager')){ ?>
							<li><label ><?php echo __("Locations","apt");?></label>
								<select name="apt_selected_location" class="selectpicker apt_selected_location" data-size="10" style="display: none;">
								<?php foreach($apt_locations as $apt_location){ ?>
										<option value="<?php echo $apt_location->id; ?>" <?php if($apt_location->id==$_SESSION['apt_location']){ echo "selected";} ?>><?php echo $apt_location->location_title; ?></option>
								<?php  }  ?>
								</select>
							</li><?php } ?>
							<?php
							$booking = new appointment_booking();
							?>
							<li><a id="apt-notifications" class="btn btn-link btn-no-bg" href="javascript:void(0);">
								<i class="icon-bell"></i>
								<span class="total_notification noti_color apt_notification_count" id="apt-notification-top"></span>
								<i class="fa fa-angle-down"></i></a>
							</li>
										
						</ul> <?php } ?>
                   
                </div>
				</div>
            </nav>
        </div><!-- top bar end here -->		


		<!-- recent notifications listing -->
		<div class="apt-overlay-notification"></div>
		<div id="apt-notification-container">
			<div class="apt-notifications-inner">
				<div class="apt-notification-main">
					<div class="apt-notification-main">
						<h4 class="notification-header"><a data-booking_id="All" href="javascript:void(0)" class="btn btn-link pull-left apt-clear-all apt_unread_notification"><?php echo __("Clear All","apt");?></a><?php echo __("Booking notifications","apt");?>
						<a id="apt-close-notifications" class="pull-right" href="javascript:void(0);" title="<?php echo __("Close Notifications","apt");?>"><i>×</i></a></h4>
						<div class="apt-recent-booking-container">
							<ul class="apt-recent-booking-list ">
								<div class="apt-load-bar">
									  <div class="apt-bar"></div>
									  <div class="apt-bar"></div>
									  <div class="apt-bar"></div>
								</div>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- end recent notifications -->
		
		<div id="apt-main-navigation" class="navbar-inner">
			<nav role="navigation" class="navbar">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
						<span class="sr-only"><?php echo __("Toggle navigation","apt");?></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<!-- <a href="apt-admin1.html" class="navbar-brand"><img src="images/logo-appointment.png" /></a> -->
				</div>
				<!-- Collection of nav links and other content for toggling -->
				<div id="navbarCollapse" class="collapse navbar-collapse np">
		<?php  /* if(isset($current_user->caps['apt_client'])){?>	
			<ul class="nav navbar-nav">
			<?php if(is_rtl()){  ?>			
			<li class="<?php if($_GET['page']=='client_settings'){ echo 'active'; } ?>"><a href="?page=appointment_menu"><i class="fa fa-cog"></i> <br /> <?php echo __('Settings',"apt"); ?></a></li>
			
			<li class="<?php if($_GET['page']=='appointment_menu'){ echo 'active'; } ?>"><a href="?page=appointment_menu"><i class="fa fa-calendar"></i><br /> <?php echo __('My Appointments',"apt"); ?></a></li>
			
			<?php }else{ ?>
			<li class="<?php if($_GET['page']=='appointment_menu'){ echo 'active'; } ?>"><a href="?page=appointment_menu"><i class="fa fa-calendar"></i> <br /><?php echo __('My Appointments',"apt"); ?></a></li>
			<li class="<?php if($_GET['page']=='client_settings'){ echo 'active'; } ?>"><a href="?page=appointment_menu"><i class="fa fa-cog"></i> <br /><?php echo __('Settings',"apt"); ?></a></li>		
			
		<?php }	?></ul>
		<?php}else */
		
		if($user_sp=='Y' && $user_sp_manager=='') {	?>
		<ul class="nav navbar-nav apt-nav-tab pl-20 lara-1">
		<?php if(is_rtl()){  ?>

			<li class="<?php if($_GET['page']=='appointments_submenu' || $_GET['page']=='appointment_menu'){ echo 'active'; } ?>"><a href="?page=appointments_submenu"><i class="fa fa-calendar"></i><span> <?php echo __('Appointments',"apt"); ?></span></a></li>
			
			<li class="<?php if($_GET['page']=='provider_submenu'){ echo 'active'; } ?>"><a href="?page=sp_settings_submenu"><i class="fa fa-cog"></i><span> <?php echo __('Settings',"apt"); ?></span></a></li> 
		<?php }else{ ?>
			<li class="<?php if($_GET['page']=='provider_submenu'){ echo 'active'; } ?>"><a href="?page=provider_submenu"><i class="fa fa-cog"></i><span> <?php echo __('Settings',"apt"); ?></span></a></li>
			
			<li class="<?php if($_GET['page']=='appointments_submenu' || $_GET['page']=='appointment_menu'){ echo 'active'; } ?>"><a href="?page=appointments_submenu"><i class="fa fa-calendar custom-staff-tab-width"> </i><span><?php echo __('Appointments',"apt"); ?></span></a></li>
		<?php } ?>
	</ul>
	<?php } else { ?>
	<ul class="nav navbar-nav apt-nav-tab pl-20 lara-2"> 
		<?php if(is_rtl()){ 
		global $wpdb;		
		if(current_user_can('manage_options') || current_user_can('business_manager')){			
			$query="select count(id) as count_ser from ".$wpdb->prefix."apt_services";
			$res_service_count = $wpdb->get_var($query);
			if(isset($appointment_sampledata) && $appointment_sampledata!=''){?>
				<li ><a id="appointment_sampledata" data-method="Remove" href="javascript:void(0)"><i class="fa fa-remove"></i><span><?php echo __('Remove Sample Data',"apt"); ?></span></a></li>
			<?php }else{
				if($res_service_count == 0){
				?>
				<li ><a id="appointment_sampledata" data-method="Add"  href="javascript:void(0)"><i class="fa fa-download"></i><span> <?php echo __('Sample Data',"apt"); ?></span></a></li>		
				<?php 
				}
			}
		}	?>
		
		
		<li class="<?php if($_GET['page']=='export_submenu'){ echo 'active'; } ?>"><a href="?page=export_submenu"><i class="fa fa-file-pdf-o"></i><span><?php echo __('Export',"apt"); ?></span></a></li>
		<?php if(current_user_can('manage_options') || current_user_can('business_manager') ){ ?>
		<li class="<?php if($_GET['page']=='settings_submenu'){ echo 'active'; } ?>"><a href="?page=settings_submenu"><i class="fa fa-cog"></i><span><?php echo __('Settings',"apt"); ?></span></a></li>
		<?php }?>
		<?php if(get_option('appointment_reviews_status' . '_' . get_current_user_id())=='E'){?>
		<li class="<?php if($_GET['page']=='reviews_submenu'){ echo 'active'; } ?>"><a href="?page=reviews_submenu"><i class="fa fa-star"></i><span> <?php echo __('Reviews',"apt"); ?></span></a></li><?php } ?>
		
		<li class="<?php  if($_GET['page']=='payments_submenu'){ echo 'active'; } ?>"><a href="?page=payments_submenu"><i class="fa fa-money"></i><span> <?php echo __('Payments',"apt"); ?></span></a></li>
		
		<li class="<?php if($_GET['page']=='clients_submenu' || $_GET['page']=='guest_clients_submenu'){ echo 'active'; } ?>"><a href="?page=clients_submenu"><i class="fa fa-users"></i><span> <?php echo __('Customers',"apt"); ?></span></a></li>
		
		<li class="<?php if($_GET['page']=='provider_submenu'){ echo 'active'; } ?>"><a href="?page=provider_submenu"><i class="icon-user icons"></i><span> <?php echo __('Staff',"apt"); ?></span></a></li>
		
		<li class="<?php if($_GET['page']=='services_submenu' || $_GET['page']=='service_addons'){ echo 'active'; } ?>"><a href="?page=services_submenu"><i class="fa fa-tasks"></i><span> <?php echo __('Services',"apt"); ?></span></a></li>
		
		
		<?php if(get_option('appointment_multi_location' . '_' . get_current_user_id())=='E' && current_user_can('manage_options') || current_user_can('business_manager')){ ?>
		<li class="<?php if($_GET['page']=='location_submenu'){ echo 'active'; } ?>"><a href="?page=location_submenu"><i class="icon-location-pin icons"></i><span> <?php echo __('Locations',"apt"); ?> </span></a></li>
		<?php } ?>
		
		<li class="<?php if($_GET['page']=='appointments_submenu'){ echo 'active'; } ?>"><a href="?page=appointments_submenu"><i class="fa fa-calendar"></i><span> <?php echo __('Appointments',"apt"); ?></span></a></li>
		
		<li class="<?php if($_GET['page']=='dashboard_submenu'){ echo 'active'; } ?>"><a href="?page=dashboard_submenu"><i class="icon-speedometer icons"></i><span> <?php echo __('Dashboard',"apt"); ?></span></a></li>
	
		<?php }else{ ?>
		 
		<li class="<?php if($_GET['page']=='dashboard_submenu'){ echo 'active'; } ?>"><a href="?page=dashboard_submenu"><i class="icon-speedometer icons"></i><span> <?php echo __('Dashboard',"apt"); ?></span></a></li>
		
		<li class="<?php if($_GET['page']=='appointments_submenu'){ echo 'active'; } ?>"><a href="?page=appointments_submenu"><i class="fa fa-calendar"></i><span> <?php echo __('Appointments',"apt"); ?></span></a></li>
		
		<?php if(get_option('appointment_multi_location' . '_' . get_current_user_id())=='E' && current_user_can('manage_options') || current_user_can('business_manager')){ ?>
		<li class="<?php if($_GET['page']=='location_submenu'){ echo 'active'; } ?>"><a href="?page=location_submenu"><i class="icon-location-pin icons"></i><span> <?php echo __('Locations',"apt"); ?> </span></a></li><?php } ?>
		
		<li class="<?php if($_GET['page']=='services_submenu' || $_GET['page']=='service_addons'){ echo 'active'; } ?>"><a href="?page=services_submenu"><i class="fa fa-tasks"></i><span> <?php echo __('Services',"apt"); ?></span></a></li>
		
		
		<li class="<?php if($_GET['page']=='provider_submenu'){ echo 'active'; } ?>"><a href="?page=provider_submenu"><i class="icon-user icons"></i><span> <?php echo __('Staff',"apt"); ?></span></a></li>
		
		
		<li class="<?php if($_GET['page']=='clients_submenu' ){ echo 'active'; } ?>"><a href="?page=clients_submenu"><i class="fa fa-users"></i><span> <?php echo __('Customers',"apt"); ?></span></a></li>
		
		<li class="<?php  if($_GET['page']=='payments_submenu'){ echo 'active'; } ?>"><a href="?page=payments_submenu"><i class="fa fa-money"></i><span> <?php echo __('Payments',"apt"); ?></span></a></li>
		<?php if(current_user_can('manage_options') || current_user_can('business_manager')){ ?>
		<li class="<?php if($_GET['page']=='settings_submenu'){ echo 'active'; } ?>"><a href="?page=settings_submenu"><i class="fa fa-cog"></i><span> <?php echo __('Settings',"apt"); ?></span></a></li>
		<?php } ?>
		<?php if(get_option('appointment_reviews_status' . '_' . get_current_user_id())=='E'){?>
			<li class="<?php if($_GET['page']=='reviews_submenu'){ echo 'active'; } ?>"><a href="?page=reviews_submenu"><i class="fa fa-star"></i><span> <?php echo __('Reviews',"apt"); ?></span></a></li>
		<?php } ?>
		<li class="<?php if($_GET['page']=='export_submenu'){ echo 'active'; } ?>"><a href="?page=export_submenu"><i class="fa fa-file-pdf-o"></i><span> <?php echo __('Export',"apt"); ?></span></a></li>
		
		
		<?php if(current_user_can('business_manager') || current_user_can('manage_options')){ ?>
		<li class="<?php if($_GET['page']=='shortcode_submenu'){ echo 'active'; } ?>"><a href="?page=shortcode_submenu"><i class="fa fa-code"></i><span> <?php echo __('Shortcode',"apt"); ?></span></a></li>
		<?php  } ?>
		
		
		
		<?php 
			global $wpdb;
			if(current_user_can('business_manager') || current_user_can('manage_options')){
				$query="select count(id) as count_ser from ".$wpdb->prefix."apt_services";
				$res_service_count = $wpdb->get_var($query);
			
				if(isset($appointment_sampledata) && $appointment_sampledata!=''){
				?>
				<li ><a class="cd-popup-trigger" href="javascript:void(0)"><i class="fa fa-remove"></i><span> <?php echo __('Remove Sample Data',"apt"); ?></span></a></li>
				<?php }else{
					if($res_service_count == 0){
					?>
					<li ><a id="appointment_sampledata" data-method="Add"  href="javascript:void(0)"><i class="fa fa-download"></i><span> <?php echo __('Sample Data',"apt"); ?></span></a></li>		
					<?php 
					}
				}
			}
		} ?>
	</ul>
		<?php } ?>
		
				</div>	
			</nav>
		</div> <!-- top bar end here -->			
		<?php } ?>
		<!-- Alert Box For Remove Sample Data -->
		<div class="cd-popup" role="alert" style="z-index: 999;">
			<div class="cd-popup-container">
				<p><?php echo __("Are you sure you want to delete Sample data, It will remove all data related to sample data?","apt");?></p>
				<ul class="cd-buttons">
					<li><a id="appointment_sampledata" data-method="Remove" href="#0"><?php echo __("Yes","apt");?></a></li>
					<li><a class="remove_popup_sample_data" href="#0"><?php echo __("No","apt");?></a></li>
				</ul>
				<a href="#0" class="cd-popup-close img-replace"></a>
			</div> <!-- cd-popup-container -->
		</div> <!-- cd-popup -->
		
		<!-- Alert Box For Remove Sample Data -->
				<!-- show pop details click on appointment from listing -->
		<div id="booking-details" class="modal fade booking-details-calendar" tabindex="-1" role="dialog" aria-hidden="true"> <!-- modal pop up start -->
		<div class="vertical-alignment-helper">
			<div class="modal-dialog modal-md vertical-align-center">
				<div class="modal-content">
					<div class="modal-header">
					
						<button type="button" class="close close_booking_detail_modal" data-dismiss="modal" aria-hidden="true">×</button>
						<h4 class="modal-title" style="margin-top: 10px; margin-bottom: 0;"><?php echo __("Booking Details","apt");?> </h4>
						<ul class="apt-booking-date-time">
							<li class="apt-second-child apt_booking_datetime"></li>
						</ul>
						
					</div>
					<div class="modal-body">
						<ul class="list-unstyled apt-cal-booking-details">
							<li>
								<label><?php echo __("Booking Status","apt");?></label>
								<div class="apt-booking-status"><span class="badge animated pulse span-scroll" style="background-color: #31bf57;"></span></div>
							</li>
							
							<li>
								<label><?php echo __("Service","apt");?></label>
								<span class="apt_servicetitle span-scroll span_indent"> </span>
							</li>
							<li>
								<label><?php echo __("Provider","apt");?></label>
								<span class="calendar_providername span-scroll span_indent"></span>
							</li>
							<li>
								<label><?php echo __("Price","apt");?></label>
								<span class="span-scroll span_indent"><span class=""><?php echo $apt_currency_symbol;?></span><span class="price"> </span></span>
							</li>
							
							<li><h5 class="apt-customer-details-hr" style="font-weight: 600;"><?php echo __("Customer","apt");?></h5>
							</li>
							<li>
								<label><?php echo __("Name","apt");?></label>
								<span class="client_name span-scroll span_indent"></span>
							</li>
							<li>
								<label><?php echo __("Email","apt");?></label>
								<span class="client_email span-scroll span_indent"></span>
							</li>
							<li>
								<label><?php echo __("Phone","apt");?></label>
								<span class="client_phone span-scroll span_indent"></span>
							</li>
							<li>
								<label><?php echo __("Payment","apt");?></label>
								<span class="client_payment span-scroll span_indent"></span>
							</li>
							<li>
								<label><?php echo __("Notes","apt");?></label>
								<span class="client_notes span-scroll span_indent"></span>
							</li>
								
							
						</ul>
					</div>
					<div class="modal-footer">
						<div class="apt-col12 apt-footer-popup-btn">
							<div id="apt_reschedule_btn" class="col-md-6 col-sm-6 np">
								<a class="btn btn-info" id="edit-booking-details" href="javascript:void(0)" data-target="edit-booking-details-view" data-toggle="modal" aria-hidden="true"><?php echo __("Update Appointment","apt");?> <i class="fa fa-angle-double-right"></i></a>				
							</div>
							
							<span id="apt_confirm_btn" class="col-md-2 col-sm-2 col-xs-4 np apt-w-32">
								<a id="apt-confirm-appointment-cal-popup" class="btn btn-link apt-small-btn" rel="popover" data-placement='top' title="Confirm note"><i class="fa fa-thumbs-up fa-2x"></i><br /><?php echo __("Confirm","apt");?></a>	
								<div id="popover-confirm-appointment-cal-popup" style="display: none;">
									<div class="arrow"></div>
									<table class="form-horizontal" cellspacing="0">
										<tbody>
											<tr>
												<td><textarea class="form-control" id="apt_booking_confirmnote" name="" placeholder="<?php echo __("Appointment Confirm Note","apt");?>" required="required" ></textarea></td>
											</tr>
											<tr>
												<td>
													<button id="apt_booking_confirm" data-method='C' value="Delete" class="btn btn-success btn-sm apt_crc_appointment" type="submit"><?php echo __("Confirm","apt");?></button>
													<button id="apt-close-confirm-appointment-cal-popup" class="btn btn-default btn-sm" href="javascript:void(0)"><?php echo __("Cancel","apt");?></button>
												</td>
											</tr>
										</tbody>
									</table>
								</div><!-- end pop up -->
							</span>	
							<span id="apt_reject_btn" class="col-md-2 col-sm-2 col-xs-4 np apt-w-32">
								<a id="apt-reject-appointment-cal-popup" class="btn btn-link apt-small-btn" rel="popover" data-placement='top' title="<?php echo __("Reject reason?","apt");?>"><i class="fa fa-thumbs-o-down fa-2x"></i><br /><?php echo __("Reject","apt");?></a>
								
								<div id="popover-reject-appointment-cal-popup" style="display: none;">
									<div class="arrow"></div>
									<table class="form-horizontal" cellspacing="0">
										<tbody>
											<tr>
												<td><textarea class="form-control" id="apt_booking_rejectnote" name="" placeholder="<?php echo __("Appointment Reject Reason","apt");?>" required="required" ></textarea></td>
											</tr>
											<tr>
												<td>
													<button id="apt_booking_reject" data-method='R' value="Appointment Rejected By Service Provider" class="btn btn-danger btn-sm apt_crc_appointment" type="submit"><?php echo __("Reject","apt");?></button>
													<button id="apt-close-reject-appointment-cal-popup" class="btn btn-default btn-sm" href="javascript:void(0)"><?php echo __("Cancel","apt");?></button>
												</td>
											</tr>
										</tbody>
									</table>
								</div><!-- end pop up -->
							</span>	
							<span id="apt_cancel_btn" class="col-md-2 col-sm-2 col-xs-4 np apt-w-32">
								<a id="apt-cancel-appointment-cal-popup" class="btn btn-link apt-small-btn" rel="popover" data-placement='top' title="<?php echo __("Cancel reason?","apt");?>"><i class="fa fa-thumbs-o-down fa-2x"></i><br /><?php echo __("Cancel","apt");?></a>
								
								<div id="popover-cancel-appointment-cal-popup" style="display: none;">
									<div class="arrow"></div>
									<table class="form-horizontal" cellspacing="0">
										<tbody>
											<tr>
												<td><textarea class="form-control" id="apt_booking_cancelnote" name="" placeholder="<?php echo __("Appointment Cancel Reason","apt");?>" required="required" ></textarea></td>
											</tr>
											<tr>
												<td>
													<button id="apt_booking_cancel" data-method='CS'  value="Cancel By Service Provider" class="btn btn-success btn-sm apt_crc_appointment" type="submit"><?php echo __("Ok","apt");?></button>
													<button id="apt-close-reject-appointment-cal-popup" class="btn btn-default btn-sm" href="javascript:void(0)"><?php echo __("Cancel","apt");?></button>
												</td>
											</tr>
										</tbody>
									</table>
								</div><!-- end pop up -->
							</span>	
							
							<span id="apt_delete_btn" class="col-md-2 col-sm-2 col-xs-4 np apt-w-32">
								<a id="apt-delete-appointment-cal-popup" class="btn btn-link apt-small-btn" rel="popover" data-placement='top' title="<?php echo __("Delete this appointment?","apt");?>"><i class="fa fa-trash-o fa-2x"></i><br /> <?php echo __("Delete","apt");?></a>
							
							<div id="popover-delete-appointment-cal-popup" style="display: none;">
								<div class="arrow"></div>
								<table class="form-horizontal" cellspacing="0">
									<tbody>
										<tr>
											<td>
												<button id="apt_booking_delete"  value="Delete" class="btn btn-danger btn-sm" type="submit"><?php echo __("Delete","apt");?></button>
												<button id="apt-close-del-appointment-cal-popup" class="btn btn-default btn-sm" href="javascript:void(0)"><?php echo __("Cancel","apt");?></button>
											</td>
										</tr>
									</tbody>
								</table>
							</div><!-- end pop up -->
						  </span>	
						</div>
					</div>
				</div>
			</div>
		
		</div></div><!-- end details of booking -->
		
		<div id="edit-booking-details-view" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-md">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h4 class="modal-title"><?php echo __("Appointment Details","apt");?></h4>
					</div>
					<div class="modal-body">
						<ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#edit-appointment-details"><?php echo __("Appointment Details","apt");?></a></li>
							<!-- <li><a data-toggle="tab" href="#edit-customer-details">Customer Details</a></li> -->
						</ul>
						<div class="tab-content">
							<div id="edit-appointment-details" class="tab-pane fade in active">
								<table>
									<tbody>
										<tr>
											<td><label><?php echo __("Provider","apt");?></label></td>
											<td>
												<div class="form-group">
													<select disabled="disabled" id="apt_booking_provider" class="selectpicker form-control" data-size="5" data-live-search="true" data-live-search-placeholder="Search" data-actions-box="true"  >						
													</select>
												</div>
											</td>
										</tr>
										
										<tr>
											<td><label><?php echo __("Service","apt");?></label></td>
											<td>
												<div class="form-group">
													<select disabled="disabled" data-size="5" id="apt_booking_service" class="selectpicker form-control" data-live-search="true" data-live-search-placeholder="Search" data-actions-box="true"  >					
													</select>
												</div>
											</td>
										</tr>
										<tr>
											<td></td>
											<td>
												<div class="apt-col6 apt-w-50">
													<div class="form-control">
														<span><?php echo $apt_currency_symbol;?></span><span id="apt_service_price"></span>
													</div>
												</div>	
												<div class="apt-col6 apt-w-50 float-right">
													<div class="form-control">
														<i class="fa fa-clock-o"></i><span id="apt_service_duration"></span>
														<input type="hidden" id="apt_service_duration_val" value=""/>
													</div>
												</div>
												
											</td>
										</tr>
										<tr>
											<td><label for="apt-service-duration"><?php echo __("Date & Time","apt");?></label></td>
											<td>
												<div class="apt-col6 apt-w-50">
													<input class="form-control" placeholder="Select Date" data-provide="datepicker" data-sel_date="" data-selstaffid="" id="apt_booking_datetime" value='' />
												</div>
												<div class="apt-col6 apt-w-50 float-right">
													<select id="apt_booking_time" class="selectpicker" data-size="5" style="display: none;" >
														
													</select>
												</div>
												
											</td>
										</tr>
										<tr>
											<td><?php echo __("Reschedule Note","apt");?></td>
											<td><textarea id="apt_booking_rsnotes" class="form-control"></textarea></td>
										</tr>
											
									</tbody>
								</table>
								
							</div>
							
							<!--<div id="edit-customer-details" class="tab-pane fade">
								<table>
									<tbody>
										<tr>
											<td>Name</td>
											<td><input type="text" class="form-control" id="apt_client_name" name="apt_client_name" placeholder="Customer Name" /></td>
										</tr>
										<tr>
											<td>Email</td>
											<td><input type="email" class="form-control" name="apt_client_email" id="apt_client_email" placeholder="andrew@example.com" /></td>
										</tr>
										<tr>
											<td>Phone</td>
											<td><input type="tel" class="form-control" id="apt_client_phone" name="apt_client_phone" /></td>
										</tr>
										<tr>
											<td>Address</td>
											<td>
												<div class="apt-col12"><textarea name="apt_client_address" id="apt_client_address" class="form-control"></textarea></div>
											</td>
										</tr>
										<tr>
											<td></td>
											<td>
												<div class="apt-col6 apt-w-50">
													<input type="text" class="form-control" id="apt_client_city" name="apt_client_city" placeholder="City" />
												</div>
												<div class="apt-col6 apt-w-50 float-right">
													<input type="text" class="form-control" id="apt_client_state" name="apt_client_state" placeholder="State" />
												</div>
											</td>
										</tr>
										<tr>
											<td></td>	
											<td>	
												<div class="apt-col6 apt-w-50">
													<input type="text" class="form-control" id="apt_client_zip" name="apt_client_zip" placeholder="Zip" />
												</div>	
												<div class="apt-col6 apt-w-50 float-right">
													<input type="text" class="form-control" id="apt_client_country" name="apt_client_country" placeholder="Country" />
												</div>	
												
											</td>
										</tr>
										
									</tbody>
								</table>	
							
							</div> -->
							
						</div>
					</div>
					<div class="modal-footer">
						<div class="apt-col12 apt-footer-popup-btn">
							<div class="apt-col6">
								<button type="button" id="apt_reschedule_booking" class="btn btn-info"><?php echo __("Reschedule Appointment","apt");?></button>
							</div>						
						</div>
					</div>
				</div>
			</div>
		</div><!-- end details of booking -->
	</header>