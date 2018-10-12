<?php
/*
Plugin Name: Appointment
Plugin URI: https://larasoftbd.com/
Description: Appointment is an online appointment booking wordpress plugin, your website visitor can see the available time for service provider and can book their appointment instantly,due its shopping cart feature one user can book multiple appointments at once. you can use this shortcode for booking page in frontend [appointment bwid="business_owner_id"].
Version: 1.1
Author: ronymaha
Author URI: https://larasoftbd.com/
*/


	if ( ! defined( 'ABSPATH' ) ) { exit; }
	define('APTDIR', plugin_dir_path( __FILE__ ));
	define('APTURL', plugin_dir_url( __FILE__ ));

	add_action('init', 'appointment_init');
	add_action( 'admin_enqueue_scripts', 'appointment_admin_scripts');
	add_action('admin_menu','appointment_admin_menu');
	add_filter('wp_head', 'viewport_meta_appointment');
	
	/* lower letters Capital Shortcode */
	add_shortcode('appointment','apt_front');
	add_shortcode('"appointment"','apt_front');
	add_shortcode("'appointment'",'apt_front');
	/* Capital letters Capital Shortcode */
	add_shortcode('Appointment','apt_front');
	add_shortcode('"Appointment"','apt_front');
	add_shortcode("'Appointment'",'apt_front');

	/* Customer Forntend Dashboard Area Shortcode */
	add_shortcode('appointment_client_appointments','appointment_client_frontend');
	add_shortcode("'appointment_client_appointments'",'appointment_client_frontend');
	add_shortcode('"appointment_client_appointments"','appointment_client_frontend');
	/* Multi Language */
	add_action( 'plugins_loaded', 'appointment_load_textdomain' );
	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'apt_action_links' );
	
	add_action('wp_ajax_check_username_bd','check_username_apt_callback');
	add_action('wp_ajax_check_email_bd','check_email_apt_callback');
	add_action('wp_ajax_check_generatecoupon_bd','check_generatecoupon_apt_callback');
	add_action( 'wp_ajax_nopriv_check_username_bd', 'check_username_apt_callback' );
	add_action( 'wp_ajax_nopriv_check_email_bd', 'check_email_apt_callback' );

	


	/* function view port meta in case its not defined */
	function viewport_meta_appointment() { 
		?>
		<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1" />
	
	<?php }
	
	/* set plugin textdomain */
	function appointment_load_textdomain() {
		$locale = apply_filters('plugin_locale', get_locale(),'apt');
		load_textdomain('apt', WP_LANG_DIR.'/apt-'.$locale.'.mo');
		load_plugin_textdomain( 'apt', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
	}

	
	/* plugin settings link */
	function apt_action_links( $links ) {
	  // $links[] = '<a href="'. get_admin_url(null, 'options-general.php?page=settings_submenu') .'">Settings</a>';
	   return $links;
	}
	/* appointment Admin Menu Icon */
	function appointment_adminmenu_icon(){
		echo '<style>li#toplevel_page_appointment_menu .dashicons-admin-generic:before {
			content: "" !important;	background: url("'.plugins_url("assets/images/menu.png",__FILE__).'")			no-repeat;		position: relative;	top: 7px;}			
			li#toplevel_page_verify .dashicons-admin-generic:before {
			content: "" !important;	background: url("'.plugins_url("assets/images/menu.png",__FILE__).'") no-repeat;		position: relative;	top: 7px;}
			li#toplevel_page_provider_submenu .dashicons-admin-generic:before {
			content: "" !important;	background: url("'.plugins_url("assets/images/menu.png",__FILE__).'") no-repeat;		position: relative;	top: 7px;}</style>';
	}
	
   /* Plugin init function 
   */	
   function appointment_init(){	 		 
   global $wpdb;	  	
		/* Check plugin updtes */
		include_once('objects/class_autoupdate.php');
		$wptuts_plugin_current_version = '1.1';
		$wptuts_plugin_remote_path = 'https://larasoftbd.com/appointment/update.php?cv='.$wptuts_plugin_current_version;
		$wptuts_plugin_slug = plugin_basename(__FILE__);
		new appointment_auto_update ($wptuts_plugin_current_version, $wptuts_plugin_remote_path, $wptuts_plugin_slug);
		/* Load appointment Admin Menu Icon */
		add_action( 'admin_print_scripts','appointment_adminmenu_icon');		
	  				 		 
		$host =  $_SERVER['HTTP_HOST'];		 
		$host_uri = $_SERVER['REQUEST_URI'];		 
		$cur_rul= $host.$host_uri;		 		 
	   if(isset($_SESSION['booking_home']) and $_SESSION['booking_home']!=''){			
		$redirect_url = $_SESSION['booking_home'];		 
	   } else {			
		$redirect_url = site_url();		 
	   }		 		 
	   
	   if(get_option('appointment_thankyou_page' . '_' . get_current_user_id() )==$cur_rul  || is_numeric(strpos($cur_rul,'ak-thankyou'))){   		   
		ob_start();
		echo '<script>setTimeout(function(){ window.location = "'.$redirect_url.'"; }, 5000);</script>';		 
	   }		 
		
					
			/* Thankyou page creation */
			
			$the_page_title = 'Thank you';
			$the_page_name = 'apt-thankyou';

			$the_page = get_page_by_title( $the_page_title );

				if ( ! $the_page ) {

				   /* Create post object */
				   $_p = array();
				   $_p['post_title'] = $the_page_title;
				   $_p['post_name'] = $the_page_name;
				   $_p['post_content'] = "
				   
				   <div class='th-wrapper'>
						<div class='th-div'>
						<span style='display:block;'>Thankyou! for booking appointment.<br/>You will be notified by email with details of appointment(s).</span><br/><br/>					
						</div>
				   </div>
				   
				   ";
				   $_p['post_status'] = 'publish';
				   $_p['post_type'] = 'page';
				   $_p['comment_status'] = 'closed';
				   $_p['ping_status'] = 'closed';
				   $_p['post_category'] = array(1); /* the default 'Uncatrgorised' */

				   /* Insert the post into the database */
				   $the_page_id = wp_insert_post( $_p );
			   
			}
			

			/* add data base tables */
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			
	
				/* include all objects files while loding */
				 include_once('objects/class_update.php');
				 include_once('objects/class_general.php');
				 include_once('objects/class_location.php');
				 include_once('objects/class_category.php');
				 include_once('objects/class_service.php');
				 include_once('objects/class_schedule.php');
				 include_once('objects/class_schedule_breaks.php');
				 include_once('objects/class_schedule_dayoffs.php');
				 include_once('objects/class_provider.php');
				 include_once('objects/class_settings.php');
				 include_once('objects/class_email_templates.php');
				 include_once('objects/class_sms_templates.php');
				 include_once('objects/class_booking.php');
				 include_once('objects/class_order.php');
				 include_once('objects/class_front_appointment_first_step.php');
				 include_once('objects/class_clients.php');
				 include_once('objects/class_payments.php');
				 include_once('objects/class_image_upload.php');
				 include_once('objects/class_reviews.php');
				 include_once('objects/class_coupons.php');
				 include_once('objects/class_email_template_settings.php');
				 include_once('objects/class_service_schedule_price.php');
				 include_once('objects/class_loyalty_points.php');
			
			
				/* Set default settings options via class constructor */
				$general = new appointment_general();
				$location = new appointment_location();
				$settings = new appointment_settings(); 			
				$email_templates = new appointment_email_template(); 
				$sms_templates = new appointment_sms_template();
				$service = new appointment_service();
				$category = new appointment_category();
				$coupon = new appointment_coupons();
				$bookings = new appointment_booking();
				$order_client_info = new appointment_order();
				$payments = new appointment_payments();
				$schedule_offdays = new appointment_schedule_offdays();
				$schedule = new appointment_schedule();
				$schedule_breaks = new appointment_schedule_breaks();
				$ssp = new appointment_service_schedule_price();
				$reviews = new appointment_reviews();
				$loyalty_points = new appointment_loyalty_points();
			
				
				
				$service_table_create = $service->create_table();
				$location_table_create = $location->create_table();
				$provider_service_table_create = $service->create_table_provider_service();
				$addons_booking_table_create = $service->create_table_addons_booking();
				$addons_service_table_create = $service->create_table_addons();
				$addon_pricing_table_create = $service->create_table_addon_pricing();
				$category_table_create = $category->create_table();
				$coupon_table_create = $coupon->create_table();
				$email_templates_table_create = $email_templates->create_table();
				$sms_templates_table_create = $sms_templates->create_table();
				$bookings_table_create = $bookings->create_table();
				$client_orderinfo_table_create = $order_client_info->create_table();
				$payments_table_create = $payments->create_table();
				$schedule_offdays_table_create = $schedule_offdays->create_table();
				$schedule_table_create = $schedule->create_table();
				$schedule_breaks_table_create = $schedule_breaks->create_table();
				$schedule_offtimes_table_create = $schedule_breaks->create_table_offtimes();
				$ssp_table_create = $ssp->create_table();
				$reviews_table_create = $reviews->create_table();
				$loyalty_points_table_create = $loyalty_points->create_table();
				$email_template_settings = new appointment_email_template_settings();				
				$tablecreation=array($service_table_create,$provider_service_table_create,$category_table_create,$coupon_table_create,$email_templates_table_create,$sms_templates_table_create,$bookings_table_create,$client_orderinfo_table_create,$payments_table_create,$schedule_offdays_table_create,$schedule_table_create,$schedule_breaks_table_create,$location_table_create,$schedule_offtimes_table_create,$ssp_table_create,$reviews_table_create,$loyalty_points_table_create,$addons_booking_table_create,$addons_service_table_create,$addon_pricing_table_create);
				if ( is_multisite()) {
	
						$current_blog = $wpdb->blogid;

						$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
						for($i=0;$i<sizeof($tablecreation);$i++){
						foreach ( $blog_ids as $blog_id) {
							switch_to_blog( $blog_id );
							$tablecreation[$i];
							restore_current_blog();
							}
						}
					} 
				
				$email_templates->business_owner_id 	= get_current_user_id();
				$sms_templates->business_owner_id 		= get_current_user_id();
				$email_templates_create 				= $email_templates->appointment_create_email_template();
				$sms_templates_create   				= $sms_templates->appointment_create_sms_template();
				$apt_update 							= new appointment_update();  /* update constructor */
	}
	
	/* Username checking  */	
	function check_username_apt_callback(){
	global $wpdb;

	if (validate_username($_POST['username']) && username_exists($_POST['username']) == Null && ctype_alnum($_POST['username']) ) {
    echo json_encode("true");
	} else {
		echo json_encode("Username is already exists or not alphanumeric.");
	}die();

	}	

	
	/* email checking  */	
	function check_email_apt_callback(){
	global $wpdb;
	if(isset($_POST['add_provider']) && $_POST['add_provider']=='yes'){
	$admin_email=get_option('admin_email');
	$cmp_result = strcmp($admin_email,$_POST['email']);

	if($cmp_result==0){ echo "true"; }else{
		if (!email_exists($_POST['email'])) {
		echo "true";
		} else {
			echo json_encode("Email already exists.");
		}die();
	}
	}else{
			if (!email_exists($_POST['email'])) {
			echo "true";
			} else {
				echo json_encode("Email already exists.");
			}die();
	}

	}
	/* Coupon checking  */	
	function check_generatecoupon_apt_callback(){
		global $wpdb;
		$coupon = new appointment_coupons();
			if(isset($_POST['update_coupon_code'],$_POST['apt_coupon_code'])){	
				if($_POST['update_coupon_code']!='ongenration'){
					if($_POST['apt_coupon_code']!=$_POST['update_coupon_code']){
							$coupon->coupon_code=$_POST['apt_coupon_code'];
							$coupon_info = $coupon->readOne();
						if(sizeof($coupon_info)>0){
							echo json_encode("Coupon code already exists.");
						}else{
							echo "true";
						}
						}else{
						echo "true";
						}
					}else{
						$coupon->coupon_code=$_POST['apt_coupon_code'];
							$coupon_info = $coupon->readOne();
							if(sizeof($coupon_info)>0){
							echo json_encode("Coupon code already exists.");
					}else{
						echo "true";
					}
				}
			}
		die();

	}

	 
	/* Admin Menu  */	
	function appointment_admin_menu(){
		/* WooCommerce condition */
		if ( class_exists( 'WooCommerce' )) {
			$cuser = wp_get_current_user();
			$cuser->add_cap('view_admin_dashboard');
		}
		if(current_user_can('apt_client') && !current_user_can('apt_provider') && !current_user_can('manage_options')) {
			
			add_menu_page('appointment','Appointment', 'apt_client','appointment_menu','apt_current_user_bookings','','80.01');
			
		} else {
			if(current_user_can('manage_options')) {
			add_menu_page('appointment','Appointment', 'manage_options','appointment_menu','appointment_settings_page','','80.01');
			}elseif(current_user_can('business_manager')){
			add_menu_page('appointment','Appointment', 'business_manager','appointment_menu','appointment_settings_page','','80.01');
			}else{
			add_menu_page('appointment','Appointment', 'apt_staff','provider_submenu','apt_provider','','80.001');
			}
		}
		/* adding submenu */
		if(current_user_can('apt_staff')) {		
			add_submenu_page(null,'Calender','Calender','apt_staff','appointments_submenu','apt_appointments');
			add_submenu_page(null,'Provider','Provider','apt_staff','provider_submenu','apt_provider');
			if(current_user_can('business_manager')){
			add_submenu_page(null,'Dashboard','Dashboard','business_manager','dashboard_submenu','apt_dashboard');
			add_submenu_page(null,'Provider','Provider','business_manager','provider_submenu','apt_provider');
			add_submenu_page(null,'Services','Services','business_manager','services_submenu','apt_services');
			add_submenu_page(null,'Service Addons','Service Addons','business_manager','service_addons','apt_service_addons');
			add_submenu_page(null,'Payments','Payments','business_manager','payments_submenu','apt_payments');
			add_submenu_page(null,'Clients','Clients','business_manager','clients_submenu','apt_clients');	
			add_submenu_page(null,'Export','Export','business_manager','export_submenu','apt_export');
			add_submenu_page(null,'Reviews','Reviews','business_manager','reviews_submenu','apt_reviews');
			}

			
		}else{
		add_submenu_page(null,'Calender','Calender','manage_options','appointments_submenu','apt_appointments');
		add_submenu_page(null,'Locations','Locations','manage_options','location_submenu','apt_locations');
		add_submenu_page(null,'Dashboard','Dashboard','manage_options','dashboard_submenu','apt_dashboard');
		add_submenu_page(null,'Provider','Provider','manage_options','provider_submenu','apt_provider');
		add_submenu_page(null,'Services','Services','manage_options','services_submenu','apt_services');
		add_submenu_page(null,'Service Addons','Service Addons','manage_options','service_addons','apt_service_addons');
		add_submenu_page(null,'Payments','Payments','manage_options','payments_submenu','apt_payments');
		add_submenu_page(null,'Settings','Settings','manage_options','settings_submenu','apt_settings');	
		add_submenu_page(null,'Clients','Clients','manage_options','clients_submenu','apt_clients');	
		add_submenu_page(null,'Export','Export','manage_options','export_submenu','apt_export');
		add_submenu_page(null,'Shortcode','Shortcode','manage_options','shortcode_submenu','apt_shortcode');
		add_submenu_page(null,'Reviews','Reviews','manage_options','reviews_submenu','apt_reviews');
		add_submenu_page(null,'Whats_New','Whats_New','manage_options','whats_new_submenu','apt_whats_new');
		add_submenu_page(null,'Forntend_Shortcode','Forntend_Shortcode','manage_options','frontend_shortcode_submenu','apt_front_shortcode');
		}
	}
	
	/* Admin Menu functions */
	function appointment_settings_page(){ include_once 'admin/dashboard.php';}
	function apt_provider(){ include_once 'admin/staff.php';	}
	function apt_appointments(){	include_once 'admin/calendar.php';	}
	function apt_dashboard(){	include_once 'admin/dashboard.php';	}
	function apt_locations(){	include_once 'admin/locations.php';	}	
	function apt_services(){	include_once 'admin/services.php'; }
	function apt_service_addons(){include_once 'admin/service_addons.php'; }
	function apt_settings(){	include_once 'admin/general_settings.php';}	
	function apt_payments(){	include_once 'admin/payments.php';}
	function apt_clients(){include_once 'admin/clients.php';	}	
	function apt_guest_clients(){include_once 'admin/list_guest_client.php'; }
	function apt_export(){include_once 'admin/export.php';}
	function apt_shortcode(){include_once 'admin/shortcode.php';}
	function apt_current_user_bookings() { include_once 'admin/client_dashboard.php';}
	function apt_invoice() {  include_once 'admin/download_invoice.php';}	
	function apt_sp_settings(){include_once 'admin/service_provider_settings.php';}
	function apt_reviews(){include_once 'admin/reviews.php';}
	function apt_whats_new(){include_once 'admin/appointment-welcome.php';}
	function apt_front_shortcode(){include_once 'admin/front_shortcode.php';}


	
	/* Shortcode Function */
	function apt_front($atts){
			
			wp_enqueue_script('jquery');
			
			wp_register_style('apt_frontend', plugins_url('assets/apt-frontend.css', __FILE__) );	
			wp_register_style('apt_responsive', plugins_url('assets/apt-responsive.css', __FILE__) );	
			wp_register_style('apt_common', plugins_url('assets/apt-common.css', __FILE__) );	
			wp_register_style('apt_reset_min', plugins_url('assets/apt-reset.min.css', __FILE__) );	
			wp_register_style('apt_jquery_ui_min', plugins_url('assets/jquery-ui.min.css', __FILE__) );	
			wp_register_style('apt_intlTelInput', plugins_url('assets/intlTelInput.css', __FILE__) );
			wp_register_style('apt_tooltipster', plugins_url('assets/tooltipster.bundle.min.css', __FILE__) );	
			wp_register_style('apt_tooltipster_sideTip_shadow', plugins_url('assets/tooltipster-sideTip-shadow.min.css', __FILE__) );
			wp_register_style('apt_simple_line_icons', plugins_url('assets/line-icons/simple-line-icons.css', __FILE__) );
			
			if(is_rtl()){
				wp_register_style('apt_rtl_css', plugins_url('assets/apt-rtl.css', __FILE__) );
				wp_enqueue_style('apt_rtl_css');
			}
			
			wp_enqueue_style('apt_frontend');
			wp_enqueue_style('apt_responsive');
			wp_enqueue_style('apt_common');
			wp_enqueue_style('apt_reset_min');
			wp_enqueue_style('apt_jquery_ui_min');
			wp_enqueue_style('apt_intlTelInput');
			wp_enqueue_style('apt_tooltipster');
			wp_enqueue_style('apt_tooltipster_sideTip_shadow');
			wp_enqueue_style('apt_simple_line_icons');
		
			wp_register_script('appointment_validate_js',plugins_url('assets/js/jquery.validate.min.js',  __FILE__) );
			wp_register_script('appointment_jquery_ui_js',plugins_url('assets/js/jquery-ui.min.js',  __FILE__) );
			wp_register_script('appointment_intlTelInput_js',plugins_url('assets/js/intlTelInput.js',  __FILE__) );
			wp_register_script('appointment_tooltipster_js',plugins_url('assets/js/tooltipster.bundle.min.js',  __FILE__) );
			wp_register_script('appointment_payment_js',plugins_url('assets/js/jquery.payment.min.js',  __FILE__) );
			wp_register_script('appointment_common_front_js',plugins_url('assets/js/common-front.js',  __FILE__) );
			
			wp_enqueue_script('appointment_validate_js');	
			wp_enqueue_script('appointment_jquery_ui_js');	
			wp_enqueue_script('appointment_intlTelInput_js');	
			wp_enqueue_script('appointment_tooltipster_js');	
			wp_enqueue_script('appointment_payment_js');	
			wp_enqueue_script('appointment_common_front_js');	
			include_once 'frontend/apt_firstep.php';
			$output = ob_get_clean();
			return $output;
		}
	
	 /* appointment Client Forntend Login And Appointment Section */
		function appointment_client_frontend($atts){


			wp_enqueue_script('jquery');
			wp_register_script('appointment_main_js',plugins_url('assets/js/apt-client-dashboard-front.js',  __FILE__) );
			wp_register_script('bootstrap_clientDB_min',plugins_url('assets/js/bootstrap.min.js',  __FILE__) );
						
			wp_register_script('dataTables_responsive_clientDB_min',plugins_url('assets/js/datatable/dataTables.responsive.min.js',  __FILE__) );
			
			
			wp_register_script('jquery_dataTables_clientDB_min',plugins_url( '/assets/js/datatable/jquery.dataTables.min.js',  __FILE__) );
			wp_register_script('dataTables_bootstrap_clientDB_min',plugins_url( '/assets/js/datatable/dataTables.bootstrap.min.js',  __FILE__) );
			wp_register_script('dataTables_buttons_clientDB_min',plugins_url( '/assets/js/datatable/dataTables.buttons.min.js',  __FILE__) );
			wp_register_script('jszip_clientDB_min',plugins_url( '/assets/js/datatable/jszip.min.js',  __FILE__) );
			wp_register_script('pdfmake_clientDB_min',plugins_url('/assets/js/datatable/pdfmake.min.js',  __FILE__) );
			wp_register_script('vfs_clientDB_fonts',plugins_url( '/assets/js/datatable/vfs_fonts.js',  __FILE__) );
			wp_register_script('buttons_html5_clientDB_min',plugins_url( '/assets/js/datatable/buttons.html5.min.js',  __FILE__) );
			
			
			
			
			wp_enqueue_script('appointment_main_js' );	
			wp_enqueue_script('bootstrap_clientDB_min' );	
			wp_enqueue_script('jquery_dataTables_clientDB_min' );	
			wp_enqueue_script('dataTables_responsive_clientDB_min' );	
			wp_enqueue_script('dataTables_bootstrap_clientDB_min' );
			wp_enqueue_script('dataTables_buttons_clientDB_min' );
			wp_enqueue_script('jszip_clientDB_min' );
			wp_enqueue_script('pdfmake_clientDB_min' );
			wp_enqueue_script('vfs_clientDB_fonts' );
			wp_enqueue_script('buttons_html5_clientDB_min' );
			
			wp_register_style('appointment_main_client_frontend', plugins_url('assets/apt-client-dashboard-front.css', __FILE__) );	
					
			wp_register_style('appointment_main_client_simple_line_icons', plugins_url('assets/line-icons/simple-line-icons.css', __FILE__) );
			wp_register_style('bootstarp_clientDB_min_css', plugins_url('assets/bootstrap/bootstrap.min.css', __FILE__) );
			wp_register_style('appointment_main_client_reset_min', plugins_url('assets/apt-reset.min.css', __FILE__) );
			
			
			wp_register_style('jquery_dataTables_clientDB_min', plugins_url('assets/jquery.dataTables.min.css', __FILE__) );
			wp_register_style('responsive_dataTables_clientDB_min', plugins_url('assets/responsive.dataTables.min.css', __FILE__) );
			wp_register_style('dataTables_bootstrap_clientDB_min', plugins_url('assets/dataTables.bootstrap.min.css', __FILE__) );
			wp_register_style('buttons_dataTables_clientDB_min', plugins_url('assets/buttons.dataTables.min.css', __FILE__) );
			if(is_rtl()){
				wp_register_style('apt_rtl_css', plugins_url('assets/apt-rtl.css', __FILE__) );
				wp_enqueue_style('apt_rtl_css');
			}			
			wp_enqueue_style('appointment_main_client_frontend' );
			wp_enqueue_style('appointment_main_client_simple_line_icons' );
			wp_enqueue_style('appointment_main_client_reset_min' );
			wp_enqueue_style('jquery_dataTables_clientDB_min' );
			wp_enqueue_style('responsive_dataTables_clientDB_min' );
			wp_enqueue_style('dataTables_bootstrap_clientDB_min' );
			wp_enqueue_style('buttons_dataTables_clientDB_min' );
			wp_enqueue_style('bootstarp_clientDB_min_css' );
			
			
			include_once 'admin/client_dashboard_front.php';
		}

	
	/* style n scripts for appointment admin panel */
	function appointment_admin_scripts($hook) {
		ob_start();
		
		global $submenu;
		global $wp_styles;
		$parent='';
		$apt_pages = array();	 
		if ( (is_array( $submenu ) && isset( $submenu[$parent] )) || $hook=='toplevel_page_appointment_menu' ) {
			$apt_pages[] = 'toplevel_page_appointment_menu';
			$apt_pages[] = 'toplevel_page_provider_submenu';
			$apt_pages[] = 'toplevel_page_verify';
			if(!empty($submenu)){
				foreach ($submenu[$parent] as $item) {	$apt_pages[] = 'admin_page_'.$item[2];}
			}
		}
		if( !in_array($hook,$apt_pages) )
		return;
		
		
		$appointment_plugin_url = plugins_url('',  __FILE__);
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-sortable');
		wp_register_script('jquery_ui_min',$appointment_plugin_url . '/assets/js/jquery-ui.min.js','','',true);
		wp_register_script('appointment_validate_js',plugins_url('assets/js/jquery.validate.min.js',  __FILE__) ,'','',true);
		wp_register_script('moment_min',$appointment_plugin_url . '/assets/js/moment.min.js','','',true);
		wp_register_script('apt_common_admin_jquery',$appointment_plugin_url . '/assets/js/apt-common-admin-jquery.js','','',true);
		wp_register_script('form_builder_min',$appointment_plugin_url . '/assets/js/form-builder.min.js','','',true);
		wp_register_script('form_render_min',$appointment_plugin_url . '/assets/js/form-render.min.js','','',true);
		wp_register_script('intlTelInput',$appointment_plugin_url . '/assets/js/intlTelInput.js','','',true);
		wp_register_script('bootstrap_min',$appointment_plugin_url . '/assets/js/bootstrap.min.js','','',true);
		wp_register_script('bootstrap_toggle_min',$appointment_plugin_url . '/assets/js/bootstrap-toggle.min.js','','',true);
		wp_register_script('bootstrap_select_min',$appointment_plugin_url . '/assets/js/bootstrap-select.min.js','','',true);
		wp_register_script('bootstrap_daterangepicker_js',$appointment_plugin_url . '/assets/js/daterangepicker.js','','',true);
		wp_register_script('chart',$appointment_plugin_url . '/assets/js/Chart.js','','',true);
		wp_register_script('jquery_minicolors_min',$appointment_plugin_url . '/assets/js/jquery.minicolors.min.js','','',true);
		wp_register_script('jquery_jcrop',$appointment_plugin_url . '/assets/js/jquery.Jcrop.min.js','','',true);
		wp_register_script('jquery_dataTables_min',$appointment_plugin_url . '/assets/js/datatable/jquery.dataTables.min.js','','',true);
		wp_register_script('dataTables_responsive_min',$appointment_plugin_url . '/assets/js/datatable/dataTables.responsive.min.js','','',true);
		wp_register_script('dataTables_bootstrap_min',$appointment_plugin_url . '/assets/js/datatable/dataTables.bootstrap.min.js','','',true);
		wp_register_script('dataTables_buttons_min',$appointment_plugin_url . '/assets/js/datatable/dataTables.buttons.min.js','','',true);
		wp_register_script('jszip_min',$appointment_plugin_url . '/assets/js/datatable/jszip.min.js','','',true);
		wp_register_script('pdfmake_min',$appointment_plugin_url . '/assets/js/datatable/pdfmake.min.js','','',true);
		wp_register_script('vfs_fonts',$appointment_plugin_url . '/assets/js/datatable/vfs_fonts.js','','',true);
		wp_register_script('buttons_html5_min',$appointment_plugin_url . '/assets/js/datatable/buttons.html5.min.js','','',true);
		wp_register_script('bootstrap_editable_min',$appointment_plugin_url . '/assets/js/bootstrap-editable.min.js','','',true);
		wp_register_script('pace_min',$appointment_plugin_url . '/assets/js/pace.min.js','','',true);
		
		if(is_rtl()){
			wp_register_style('apt_admin_rtl_bootstrap_css', plugins_url('assets/bootstrap/bootstrap-rtl.min.css', __FILE__) );
			wp_register_style('apt_admin_rtl_responsive_css', plugins_url('assets/apt-admin-rtl-responsive.css', __FILE__) );
			wp_enqueue_style('apt_admin_rtl_bootstrap_css');
			wp_enqueue_style('apt_admin_rtl_responsive_css');
			wp_register_style('apt_admin_rtl_css', plugins_url('assets/apt-admin-rtl.css', __FILE__) );
			wp_enqueue_style('apt_admin_rtl_css');
			wp_register_style('apt_main_rtl_css', plugins_url('assets/rtl.css', __FILE__) );
			wp_enqueue_style('apt_main_rtl_css');
		}
		wp_enqueue_script('jquery_ui_min');
		wp_enqueue_script('moment_min');
		wp_enqueue_script('appointment_validate_js');		
		wp_enqueue_script('form_builder_min');
		wp_enqueue_script('form_render_min');
		wp_enqueue_script('intlTelInput');
		wp_enqueue_script('bootstrap_min');
		wp_enqueue_script('bootstrap_toggle_min');
		wp_enqueue_script('bootstrap_select_min');
		wp_enqueue_script('bootstrap_daterangepicker_js');
		wp_enqueue_script('chart');
		wp_enqueue_script('jquery_minicolors_min');
		wp_enqueue_script('jquery_jcrop');
		wp_enqueue_script('jquery_dataTables_min');
		wp_enqueue_script('dataTables_responsive_min');
		wp_enqueue_script('dataTables_bootstrap_min');
		wp_enqueue_script('dataTables_buttons_min');
		wp_enqueue_script('jszip_min');
		wp_enqueue_script('pdfmake_min');
		wp_enqueue_script('vfs_fonts');
		wp_enqueue_script('buttons_html5_min');
		wp_enqueue_script('bootstrap_editable_min');
		wp_enqueue_script('apt_common_admin_jquery');
		wp_enqueue_script('pace_min');
		
		 
		
		
		wp_register_style( 'apt_admin_style',$appointment_plugin_url . '/assets/apt-admin-style.css');		
		wp_register_style( 'apt_admin_common', $appointment_plugin_url . '/assets/apt-admin-common.css' );
		wp_register_style( 'apt_admin_responsive',$appointment_plugin_url .'/assets/apt-admin-responsive.css' );
		wp_register_style( 'apt_admin_reset',$appointment_plugin_url .'/assets/apt-reset.min.css' );
		
		wp_register_style( 'bootstarp_min_css',$appointment_plugin_url . '/assets/bootstrap/bootstrap.min.css');
		wp_register_style( 'bootstrap_daterangepicker', $appointment_plugin_url . '/assets/daterangepicker.css' );
		wp_register_style('appointment_phone_codes', plugins_url('assets/intlTelInput.css', __FILE__) );
		wp_register_style('bootstrap_theme_min', plugins_url('assets/bootstrap/bootstrap-theme.min.css', __FILE__) );
		wp_register_style('bootstrap_toggle_min', plugins_url('assets/bootstrap/bootstrap-toggle.min.css', __FILE__) );
		wp_register_style('bootstrap_select_min', plugins_url('assets/bootstrap/bootstrap-select.min.css', __FILE__) );
		wp_register_style('jquery_jcrop', plugins_url('assets/jquery.Jcrop.min.css', __FILE__) );
		wp_register_style('jquery_minicolors', plugins_url('assets/jquery.minicolors.css', __FILE__) );
		wp_register_style('jquery_dataTables_min', plugins_url('assets/jquery.dataTables.min.css', __FILE__) );
		wp_register_style('responsive_dataTables_min', plugins_url('assets/responsive.dataTables.min.css', __FILE__) );
		wp_register_style('dataTables_bootstrap_min', plugins_url('assets/dataTables.bootstrap.min.css', __FILE__) );
		wp_register_style('buttons_dataTables_min', plugins_url('assets/buttons.dataTables.min.css', __FILE__) );
		wp_register_style('bootstrap_editable', plugins_url('assets/bootstrap-editable.css', __FILE__) );
		wp_register_style('jquery_ui_min', plugins_url('assets/jquery-ui.min.css', __FILE__) );
		wp_register_style('form_builder_min', plugins_url('assets/form-builder.min.css', __FILE__) );
		wp_register_style('form_render_min', plugins_url('assets/form-render.min.css', __FILE__) );
		wp_register_style('font_awesome_min', plugins_url('assets/font-awesome/css/font-awesome.min.css', __FILE__) );
		wp_register_style('simple_line_icons', plugins_url('assets/line-icons/simple-line-icons.css', __FILE__) );
			
		
		wp_enqueue_style( 'apt_admin_style' );
		wp_enqueue_style( 'apt_admin_common' );
		wp_enqueue_style( 'apt_admin_responsive' );
		wp_enqueue_style( 'apt_admin_reset' );
		wp_enqueue_style( 'bootstarp_min_css' );
		wp_enqueue_style( 'bootstrap_daterangepicker' );
		wp_enqueue_style( 'appointment_phone_codes' );
		wp_enqueue_style( 'bootstrap_theme_min' );
		wp_enqueue_style( 'bootstrap_toggle_min' );
		wp_enqueue_style( 'bootstrap_select_min' );
		wp_enqueue_style( 'jquery_jcrop' );
		wp_enqueue_style( 'jquery_minicolors' );
		wp_enqueue_style( 'jquery_dataTables_min' );
		wp_enqueue_style( 'responsive_dataTables_min' );
		wp_enqueue_style( 'dataTables_bootstrap_min' );
		wp_enqueue_style( 'buttons_dataTables_min' );
		wp_enqueue_style( 'bootstrap_editable' );
		wp_enqueue_style( 'jquery_ui_min' );
		wp_enqueue_style( 'form_builder_min' );
		wp_enqueue_style( 'form_render_min' );
		wp_enqueue_style( 'font_awesome_min' );
		wp_enqueue_style( 'simple_line_icons' );
		
	
		if ( 'admin_page_appointments_submenu' == $hook || 'toplevel_page_appointment_menu' == $hook ) {
			 wp_register_script('moment_min_js',$appointment_plugin_url . '/assets/js/moment.min.js');
			 wp_enqueue_script('moment_min_js');
			 wp_register_script('fc_min_js',$appointment_plugin_url . '/assets/js/fullcalendar.min.js');
			 wp_enqueue_script('fc_min_js');
			 wp_register_script('fc_language_js',$appointment_plugin_url . '/assets/js/lang-all.js');
			 wp_enqueue_script('fc_language_js');
			 wp_register_style( 'fc_min_css',$appointment_plugin_url . '/assets/fullcalendar.min.css');
			 wp_enqueue_style( 'fc_min_css' );
			
						
			if(current_user_can('manage_options') || current_user_can('apt_provider')) {
				
				wp_register_script('appointment_appointment_calendar',$appointment_plugin_url . '/assets/js/appointment_appointment_calendar.js');					
				wp_enqueue_script('appointment_appointment_calendar');
			 }
		}
			
		if('admin_page_clients_submenu'==$hook){
			/* wp_register_script('client_listing_modal_js',$appointment_plugin_url . '/assets/js/apt_client_listing_modal.js');
			wp_enqueue_script('client_listing_modal_js'); */
		}
	
		if('admin_page_appearance_submenu'==$hook || 'admin_page_add_service_submenu'==$hook || 'admin_page_update_service_submenu'==$hook) {
				wp_register_script('jscolor_js',$appointment_plugin_url . '/assets/js/jscolor.js');
				wp_enqueue_script('jscolor_js');
		}
	 
	}

	/* add new role staff_member */
	function apt_staff_role(){
		add_role('apt_staff','apt staff',['read' => true]);
	}
	add_action('admin_init', 'apt_staff_role');