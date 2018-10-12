<?php 

/**
 * Class appointment Uninstall
 * Uninstalling appointment deletes user roles, tables, and options.
 *
 * @author      TeamBI
 */
 
	class appointment_uninstall{


		  /* remove all roles */
		  function remove_apt_roles() {
			   remove_role('apt_staff'); 
			   remove_role('apt_manager'); 
			   remove_role('apt_client');
		  }  
		  
		  
		  /* Remove database tables */
		  function remove_apt_mysql_tables() {			
				global $wpdb;				
				$wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix."apt_bookings" );
				$wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix."apt_categories" );
				$wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix."apt_coupons" );
				$wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix."apt_email_templates" );
				$wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix."apt_locations" );
				$wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix."apt_order_client_info" );
				$wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix."apt_payments" );
				$wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix."apt_providers_services" );
				$wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix."apt_schdeule_offtimes" );
				$wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix."apt_schedule" );
				$wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix."apt_schedule_breaks" );
				$wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix."apt_schedule_dayoffs" );
				$wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix."apt_services" );
				$wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix."apt_service_schedule_price");
				$wpdb->query( "DROP TABLE IF EXISTS ".$wpdb->prefix."apt_sms_templates");
		  }
		  /* remove wordpress options by appointment */
		  function remove_apt_wp_options() {
			global $wpdb;
			$wpdb->query("DELETE FROM ".$wpdb->prefix."options WHERE option_name LIKE 'appointment_%'");
		   }
		   
		   
		   /* remove appointment pages */
		  function remove_apt_wp_pages() {
				$pageTY = get_page_by_title( 'thankyou' );
				if($pageTY!=''){
					wp_trash_post($pageTY->ID);
				}
		  }		  
		 
	  
	}
?>