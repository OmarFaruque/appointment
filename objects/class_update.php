<?php 
class appointment_update {

	  public function __construct() {
	  
		  $root = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
		  
		  if (file_exists($root.'/wp-load.php')) {
		   require_once($root.'/wp-load.php');
		  }
		  global $wpdb; 	  
		  
			$version1_0 = get_option('appointment_version');
			if(!isset($version1_0)) {
					add_option('appointment_version','1.0');
			}
			
			
			$version1_1 = get_option('appointment_version');
			if($version1_1 < 1.1){
				add_option('appointment_cancelation_policy_status','D');
				add_option('appointment_cancelation_policy_header','');
				add_option('appointment_cancelation_policy_text','');
				add_option('appointment_allow_terms_and_conditions','D');
				add_option('appointment_allow_terms_and_conditions_url','');
				add_option('appointment_allow_privacy_policy','D');
				add_option('appointment_allow_privacy_policy_url','');
				update_option('appointment_version','1.1');
			}
			$version2_0 = get_option('appointment_version');
			if($version2_0 < 2.0){
				add_option('appointment_payment_method_Paytm','D');
				add_option('appointment_paytm_testing_mode','D');
				add_option('appointment_paytm_merchantkey','');
				add_option('appointment_paytm_merchantid','');
				add_option('appointment_paytm_website','');
				add_option('appointment_paytm_channelid','');
				add_option('appointment_paytm_industryid','');
				
				add_option('appointment_payment_method_Payumoney','D');
				add_option('appointment_payumoney_testing_mode','D');
				add_option('appointment_payumoney_merchantkey','');
				add_option('appointment_payumoney_saltkey','');
				
				add_option('appointment_textlocal_admin_sms_notification_status','D');
				add_option('appointment_textlocal_service_provider_sms_notification_status','D');
				add_option('appointment_textlocal_client_sms_notification_status','D');
				add_option('appointment_sms_noti_textlocal','D');
				add_option('appointment_textlocal_apikey','');
				add_option('appointment_textlocal_sender','');
				add_option('appointment_textlocal_ccode','+1');
				add_option('appointment_textlocal_ccode_alph','us');
				add_option('appointment_textlocal_admin_phone_no','');
				
				$wpdb->query("ALTER TABLE ".$wpdb->prefix."apt_payments CHANGE `payment_method` `payment_method` ENUM('paypal','pay_locally','Free','payumoney','paytm','stripe');");
				update_option('appointment_version','2.0');
			}
			$version2_1 = get_option('appointment_version');
			if($version2_1 < 2.1){
				update_option('appointment_version','2.1');
			}
	 }
}
?>