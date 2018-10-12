<?php
class appointment_settings{
	
   /** Appearance Settings **/
   public $appointment_primary_color;
   public $appointment_secondary_color;
   public $appointment_text_color;
   public $appointment_bg_text_color;  
   public $appointment_admin_color_primary;  
   public $appointment_admin_color_secondary;  
   public $appointment_admin_color_text;  
   public $appointment_admin_color_bg_text;  
   public $appointment_guest_user_checkout;    
   public $appointment_show_provider;
   public $appointment_show_provider_avatars;
   public $appointment_show_services;
   public $appointment_show_service_desc;
   public $appointment_show_coupons;
   public $appointment_hide_booked_slot;
   public $appointment_cart;
   public $appointment_max_cartitem_limit;
   public $appointment_reviews_status;
   public $appointment_auto_confirm_reviews;
   public $appointment_frontend_custom_css;
   
   public $appointment_countrycodes_withflags;    
   public $appointment_default_country_short_code;
   public $appointment_thankyou_page;
   public $appointment_thankyou_page_rdtime;
   public $appointment_frontend_loader;
   
 
   
   /* appointment General Settings */
   public $appointment_multi_location;
   public $appointment_zipcode_booking;
   public $appointment_booking_zipcodes;
   public $appointment_booking_time_interval;
   public $appointment_minimum_advance_booking;
   public $appointment_maximum_advance_booking;
   public $appointment_booking_padding_time;
   public $appointment_cancellation_buffer_time;
   public $appointment_reschedule_buffer_time;
   public $appointment_currency;
   public $appointment_currency_symbol;
   public $appointment_currency_symbol_position;
   public $appointment_price_format_decimal_places;
   public $appointment_price_format_comma_separator;      
   public $appointment_multiple_booking_sameslot;  	
   public $appointment_slot_max_booking_limit;
   public $appointment_appointment_auto_confirm;
   public $appointment_dayclosing_overlap;   
   public $appointment_client_as_wordpress_user_role;
   public $appointment_taxvat_status;
   public $appointment_taxvat_type;
   public $appointment_taxvat_amount;
   public $appointment_pd_type;
   public $appointment_partial_deposit_amount;
   public $appointment_partial_deposit_type;
   public $appointment_partial_deposit_status;
   public $appointment_partial_deposit_message;
   public $appointment_location_sortby;
   public $booking_cart_description;
   public $appointment_datepicker_format;
   public $appointment_cancelation_policy_status;
   public $appointment_cancelation_policy_header;
   public $appointment_cancelation_policy_text;
   public $appointment_allow_terms_and_conditions;
   public $appointment_allow_terms_and_conditions_url;
   public $appointment_allow_privacy_policy;
   public $apt_privacy_policy_url;
	
    /*Company Settings*/
	public $appointment_company_name;
	public $appointment_company_email;
	public $appointment_company_address;
	public $appointment_company_city;
	public $appointment_company_state;
	public $appointment_company_zip;
	public $appointment_company_country;
	public $appointment_company_logo;
	public $appointment_company_country_code;
	public $appointment_company_phone;
	public $default_company_country_flag;
	
  
	/* Payment Settings */
	public $appointment_payment_gateways_status; 
	public $appointment_locally_payment_status;  
    public $appointment_payment_method_Paypal;
    public $appointment_payment_method_Stripe;
	public $appointment_payment_method_2Checkout;
	public $appointment_payment_method_Authorizenet;   
   /* Paypal */
   public $appointment_paypal_title;
   public $appointment_paypal_direct_cc_dc_payment;
   public $appointment_paypal_description;
   public $appointment_paypal_merchant_email;
   public $appointment_paypal_api_username;
   public $appointment_paypal_api_password;
   public $appointment_paypal_api_signature;
   public $appointment_paypal_testing_mode; 
   public $appointment_paypal_guest_checkout;   
   /* Stripe */
   public $appointment_stripe_secretKey;
   public $appointment_stripe_publishableKey; 
   /* Authorize.Net */
   public $appointment_authorizenet_title;
   public $appointment_authorizenet_desc;
   public $appointment_authorizenet_api_loginid;
   public $appointment_authorizenet_transaction_key;
   public $appointment_authorizenet_testing_mode;
    /* 2Checkout */
	public $appointment_2checkout_publishablekey;
	public $appointment_2checkout_privateKey;
	public $appointment_2checkout_sellerid;
	public $appointment_2checkout_testing_mode;
	
	/* Payumoney */
	public $appointment_payumoney_testing_mode;
	public $appointment_payment_method_Payumoney;
	public $appointment_payumoney_merchantkey;
	public $appointment_payumoney_saltkey;
	
	/* Paytm */
	public $appointment_paytm_testing_mode;
	public $appointment_payment_method_Paytm;
	public $appointment_paytm_merchantkey;
	public $appointment_paytm_merchantid;
	public $appointment_paytm_website;
	public $appointment_paytm_industryid;
	public $appointment_paytm_channelid;
	
	/* Email Settings */
	public $appointment_email_sender_name;
	public $appointment_email_sender_address;
	public $appointment_admin_email_notification_status;
	public $appointment_manager_email_notification_status;
	public $appointment_service_provider_email_notification_status;
	public $appointment_client_email_notification_status;
	public $appointment_email_reminder_buffer;	
	
   /* SMS Reminder Settings */
   public $appointment_sms_reminder_status;
   
   public $appointment_sms_noti_twilio;
   public $appointment_twilio_sid;
   public $appointment_twilio_auth_token;
   public $appointment_twilio_number;
   public $appointment_twilio_client_sms_notification_status;
   public $appointment_twilio_service_provider_sms_notification_status;
   public $appointment_twilio_admin_sms_notification_status;
   public $appointment_twilio_admin_phone_no;
   public $appointment_twilio_ccode;
   public $appointment_twilio_ccode_alph;

   
   public $appointment_sms_noti_plivo;
   public $appointment_plivo_number;
   public $appointment_plivo_sid;
   public $appointment_plivo_auth_token;
   public $appointment_plivo_service_provider_sms_notification_status;
   public $appointment_plivo_client_sms_notification_status;
   public $appointment_plivo_admin_sms_notification_status;
   public $appointment_plivo_admin_phone_no;
   public $appointment_plivo_ccode;
   public $appointment_plivo_ccode_alph;
   
   
   public $appointment_sms_noti_nexmo;
   public $appointment_nexmo_apikey;
   public $appointment_nexmo_api_secret;
   public $appointment_nexmo_form;
   public $appointment_nexmo_send_sms_client_status;
   public $appointment_nexmo_send_sms_sp_status;
   public $appointment_nexmo_send_sms_admin_status;
   public $appointment_nexmo_admin_phone_no;
   public $appointment_nexmo_ccode;
   public $appointment_nexmo_ccode_alph;
   public $appointment_sms_noti_textlocal;
   public $appointment_textlocal_apikey;
   public $appointment_textlocal_sender;
   public $appointment_textlocal_service_provider_sms_notification_status;
   public $appointment_textlocal_client_sms_notification_status;
   public $appointment_textlocal_admin_sms_notification_status;
   public $appointment_textlocal_admin_phone_no;
   public $appointment_textlocal_ccode;
   public $appointment_textlocal_ccode_alph;
   
   

   
   /* Constructor Function to set the default values */
  public function __construct() {
			
			if(!get_option('appointment_booking_time_interval'.'_'.get_current_user_id())) {
					$admin_email = get_option('admin_email');
					$appointment_options = array(					
					   /* Appearance Settings */
					   'appointment_primary_color'=>'#232833',
					   'appointment_secondary_color'=>'#f43166',
					   'appointment_text_color'=>'#3d3d3d',
					   'appointment_bg_text_color'=>'#FFFFFF',  
					   'appointment_admin_color_primary'=>'#232833',					   
					   'appointment_admin_color_secondary'=>'#f43166',					   
					   'appointment_admin_color_text'=>'#3d3d3d',					   
					   'appointment_admin_color_bg_text'=>'#FFFFFF',					   
					   'appointment_firststep_indications'=>'E',
					   'appointment_datepicker_format'=>'m-d-Y',					  					 
					   'appointment_guest_user_checkout'=>'E',
					   'appointment_single_column_view'=>'D',	
					   'appointment_timeslots_legends'=>'E',
					   'appointment_countrycodes_withflags'=>'E',
					   'appointment_default_country_short_code'=>'us',
					   
										
						'appointment_show_provider'=>'E',
						'appointment_show_provider_avatars'=>'E',
						'appointment_show_services'=>'E',
						'appointment_show_service_desc'=>'E',
						'appointment_show_coupons'=>'E',
						'appointment_hide_booked_slot'=>'E',
						'appointment_cart'=>'E',
						'appointment_max_cartitem_limit'=>'5',
						'appointment_reviews_status'=>'D',
						'appointment_auto_confirm_reviews'=>'D',
						'appointment_frontend_custom_css'=>'',
						'appointment_frontend_loader'=>'',
						
						/* Default General Settings */
						'appointment_multi_location'=>'E',
						'appointment_zipcode_booking'=>'D',
						'appointment_booking_zipcodes'=>'',
						'appointment_booking_time_interval'=>'30',
						'appointment_minimum_advance_booking'=>'360',
						'appointment_maximum_advance_booking'=>'6',
						'appointment_booking_padding_time'=>'',
						'appointment_cancellation_buffer_time'=>'',
						'appointment_reschedule_buffer_time'=>'',
						'appointment_currency'=>'USD',
						'appointment_currency_symbol'=>'$',
						'appointment_currency_symbol_position'=>'B',
						'appointment_price_format_decimal_places'=>'2',						
						'appointment_price_format_comma_separator'=>'N',						
						'appointment_multiple_booking_sameslot'=>'E',									
						'appointment_slot_max_booking_limit'=>'0',						
						'appointment_appointment_auto_confirm'=>'D',
						'appointment_dayclosing_overlap'=>'D',
						'appointment_thankyou_page'=>'',
						'appointment_thankyou_page_rdtime'=>'5000',
						'appointment_main_container_background'=>'transparent linear-gradient(to right, #EDEDED 0%, rgba(237, 237, 237, 0.72) 50%, #F6F6F6 50%, #F6F6F6 100%) repeat scroll 0% 0% !important',
						'appointment_client_as_wordpress_user_role'=>'appointment_client',
						'appointment_taxvat_status'=>'D',
						'appointment_taxvat_type'=>'P',
						'appointment_taxvat_amount'=>'',
						'appointment_pd_type'=>'P',
						'appointment_partial_deposit_amount'=>'',
						'appointment_partial_deposit_type'=>'P',
						'appointment_partial_deposit_status'=>'D',
						'appointment_partial_deposit_message'=>'You only need to pay a deposit to confirm your booking. The remaining amount needs to be paid on arrival.',
						'appointment_location_sortby'=>'state',
						'booking_cart_description'=>'E',
						'appointment_cancelation_policy_status'=>'D',
						'appointment_cancelation_policy_header'=>'',
						'appointment_cancelation_policy_text'=>'',
						'appointment_allow_terms_and_conditions'=>'D',
						'appointment_allow_terms_and_conditions_url'=>'',
						'appointment_allow_privacy_policy'=>'D',
						'appointment_allow_privacy_policy_url'=>'',
						
												
						/* Default Company Settings */
						'appointment_company_name'=>'',
						'appointment_company_email'=>'',
						'appointment_company_address'=>'',
						'appointment_company_city'=>'',
						'appointment_company_state'=>'',
						'appointment_company_zip'=>'',
						'appointment_company_country'=>'',
						'appointment_company_logo'=>'',
						'appointment_company_country_code'=>'+1',
						'appointment_company_phone'=>'',
						'default_company_country_flag'=>'us',

						
						/* Payment Settings */
						'appointment_payment_method_Paypal'=>'D', 
						'appointment_payment_method_Stripe'=>'D',						
						'appointment_payment_method_Authorizenet'=>'D',
						'appointment_locally_payment_status'=>'E',
						'appointment_payment_gateways_status'=>'D',	
						'appointment_payment_method_2Checkout'=>'D',
						'appointment_payment_method_Paytm'=>'D',
						
						/* Paypal */
						'appointment_paypal_direct_cc_dc_payment'=>'N',
						'appointment_paypal_title'=>'Paypal',
						'appointment_paypal_description'=>'you can pay with your credit card if you don\'t have a paypal account',
						'appointment_paypal_merchant_email'=>'you@youremail.com',
						'appointment_paypal_testing_mode'=>'D',						
						'appointment_paypal_guest_checkout'=>'D',						
						'appointment_paypal_api_username'=>'',
						'appointment_paypal_api_password'=>'',
						'appointment_paypal_api_signature'=>'',						
						/* Stripe */
						'appointment_stripe_secretKey'=>'',
						'appointment_stripe_publishableKey'=>'',		
						/* Authorize.Net */
						'appointment_authorizenet_title'=>'Authorize.Net',
						'appointment_authorizenet_desc'=>'',
						'appointment_authorizenet_api_loginid'=>'',
						'appointment_authorizenet_transaction_key'=>'',
						'appointment_authorizenet_testing_mode'=>'D',
						/* 2Checkout */
						'appointment_2checkout_publishablekey'=>'',
						'appointment_2checkout_privateKey'=>'',
						'appointment_2checkout_sellerid'=>'',
						'appointment_2checkout_testing_mode'=>'D',
						/* Payumoney */
						'appointment_payment_method_Payumoney'=>'D',
						'appointment_payumoney_merchantkey'=>'',
						'appointment_payumoney_saltkey'=>'',
						'appointment_payumoney_testing_mode'=>'D',
						
						/* Paytm */
						'appointment_payment_method_Paytm'=>'D',
						'appointment_paytm_merchantkey'=>'',
						'appointment_paytm_merchantid'=>'',
						'appointment_paytm_website'=>'',
						'appointment_paytm_channelid'=>'',
						'appointment_paytm_industryid'=>'',
						'appointment_paytm_testing_mode'=>'D',
						
						/* Email Settings */ 
						'appointment_email_sender_name'=>'',
						'appointment_email_sender_address'=>$admin_email,
						'appointment_admin_email_notification_status'=>'E',
						'appointment_manager_email_notification_status'=>'E',
						'appointment_service_provider_email_notification_status'=>'E',
						'appointment_client_email_notification_status'=>'E',
						'appointment_email_reminder_buffer'=>'',
						/* SMS Reminder Settings */
						'appointment_sms_reminder_status'=>'D',
						
						'appointment_sms_noti_twilio'=>'D',
						'appointment_twilio_number'=>'',
						'appointment_twilio_sid'=>'',
						'appointment_twilio_auth_token'=>'',
						'appointment_twilio_client_sms_notification_status'=>'D',
						'appointment_twilio_service_provider_sms_notification_status'=>'D',
						'appointment_twilio_admin_sms_notification_status'=>'D',
						'appointment_twilio_admin_phone_no'=>'',
						'appointment_twilio_ccode'=>'+1',
						'appointment_twilio_ccode_alph'=>'us',
			
						'appointment_sms_noti_plivo'=>'D',
						'appointment_plivo_number'=>'',
						'appointment_plivo_sid'=>'',
						'appointment_plivo_auth_token'=>'',
						'appointment_plivo_service_provider_sms_notification_status'=>'D',
						'appointment_plivo_client_sms_notification_status'=>'D',
						'appointment_plivo_admin_sms_notification_status'=>'D',
						'appointment_plivo_admin_phone_no'=>'',
						'appointment_plivo_ccode'=>'+1',
						'appointment_plivo_ccode_alph'=>'us',

						'appointment_sms_noti_nexmo'=>'D',
						'appointment_nexmo_apikey'=>'',
						'appointment_nexmo_api_secret'=>'',
						'appointment_nexmo_form'=>'',
						'appointment_nexmo_send_sms_client_status'=>'D',
						'appointment_nexmo_send_sms_sp_status'=>'D',
						'appointment_nexmo_send_sms_admin_status'=>'D',
						'appointment_nexmo_admin_phone_no'=>'',
						'appointment_nexmo_ccode'=>'+1',
						
						'appointment_sms_noti_textlocal'=>'D',
						'appointment_textlocal_apikey'=>'',
						'appointment_textlocal_sender'=>'',
						'appointment_textlocal_service_provider_sms_notification_status'=>'D',
						'appointment_textlocal_client_sms_notification_status'=>'D',
						'appointment_textlocal_admin_sms_notification_status'=>'D',
						'appointment_textlocal_admin_phone_no'=>'',
						'appointment_textlocal_ccode'=>'+1',
						'appointment_textlocal_ccode_alph'=>'us',
						
						/* GC Settings */
						
						'apt_gc_status'=>'N',
						'apt_gc_two_way_sync_status'=>'N',
						'apt_gc_token'=>'',
						'apt_gc_id'=>'',
						'apt_gc_client_id'=>'',
						'apt_gc_client_secret'=>'',
						'apt_gc_frontend_url'=>'',
						'apt_gc_admin_url'=>''
						
					);	
						
					foreach($appointment_options as $option_key => $option_value) {
						add_option($option_key . '_' . get_current_user_id(),$option_value);
					}
			
			}
   
   }
   

	
	/** ReadAll Settings **/
	function readAll(){
	
			/** Default Appearance Settings **/
			$this->appointment_primary_color = get_option('appointment_primary_color' . '_' . get_current_user_id());
			$this->appointment_secondary_color = get_option('appointment_secondary_color' . '_' . get_current_user_id());
			$this->appointment_text_color = get_option('appointment_text_color' . '_' . get_current_user_id());
			$this->appointment_bg_text_color = get_option('appointment_bg_text_color' . '_' . get_current_user_id());					
			$this->appointment_admin_color_primary = get_option('appointment_admin_color_primary' . '_' . get_current_user_id());
			$this->appointment_admin_color_secondary = get_option('appointment_admin_color_secondary' . '_' . get_current_user_id());
			$this->appointment_admin_color_text = get_option('appointment_admin_color_text' . '_' . get_current_user_id());
			$this->appointment_admin_color_bg_text = get_option('appointment_admin_color_bg_text' . '_' . get_current_user_id());
			$this->appointment_guest_user_checkout = get_option('appointment_guest_user_checkout' . '_' . get_current_user_id());				
			$this->appointment_show_provider = get_option('appointment_show_provider' . '_' . get_current_user_id());
			$this->appointment_show_provider_avatars = get_option('appointment_show_provider_avatars' . '_' . get_current_user_id());
			$this->appointment_show_services = get_option('appointment_show_services' . '_' . get_current_user_id());
			$this->appointment_show_service_desc = get_option('appointment_show_service_desc' . '_' . get_current_user_id());
			$this->appointment_show_coupons = get_option('appointment_show_coupons' . '_' . get_current_user_id());				
			$this->appointment_hide_booked_slot = get_option('appointment_hide_booked_slot' . '_' . get_current_user_id());
			
			$this->appointment_countrycodes_withflags = get_option('appointment_countrycodes_withflags' . '_' . get_current_user_id());				
			$this->appointment_default_country_short_code = get_option('appointment_default_country_short_code' . '_' . get_current_user_id());	
			$this->appointment_cart = get_option('appointment_cart' . '_' . get_current_user_id());	
			$this->appointment_max_cartitem_limit = get_option('appointment_max_cartitem_limit' . '_' . get_current_user_id());	
			$this->appointment_reviews_status = get_option('appointment_reviews_status' . '_' . get_current_user_id());	
			$this->appointment_auto_confirm_reviews = get_option('appointment_auto_confirm_reviews' . '_' . get_current_user_id());	
			$this->appointment_frontend_custom_css = get_option('appointment_frontend_custom_css' . '_' . get_current_user_id());	
			$this->appointment_frontend_loader = get_option('appointment_frontend_loader' . '_' . get_current_user_id());	
			/*** End ***/
			
			/* General Settings */
			$this->appointment_multi_location = get_option('appointment_multi_location' . '_' . get_current_user_id());
			$this->appointment_zipcode_booking = get_option('appointment_zipcode_booking' . '_' . get_current_user_id());
			$this->appointment_booking_zipcodes = get_option('appointment_booking_zipcodes' . '_' . get_current_user_id());
			$this->appointment_booking_time_interval = get_option('appointment_booking_time_interval' . '_' . get_current_user_id());
			$this->appointment_minimum_advance_booking = get_option('appointment_minimum_advance_booking' . '_' . get_current_user_id());
			$this->appointment_maximum_advance_booking = get_option('appointment_maximum_advance_booking' . '_' . get_current_user_id());
			$this->appointment_booking_padding_time = get_option('appointment_booking_padding_time' . '_' . get_current_user_id());
			$this->appointment_cancellation_buffer_time = get_option('appointment_cancellation_buffer_time' . '_' . get_current_user_id());
			$this->appointment_reschedule_buffer_time = get_option('appointment_reschedule_buffer_time' . '_' . get_current_user_id());
			$this->appointment_currency = get_option('appointment_currency' . '_' . get_current_user_id());
			$this->appointment_currency_symbol = get_option('appointment_currency_symbol' . '_' . get_current_user_id());
			$this->appointment_currency_symbol_position = get_option('appointment_currency_symbol_position' . '_' . get_current_user_id());
			$this->appointment_price_format_decimal_places = get_option('appointment_price_format_decimal_places' . '_' . get_current_user_id());
			$this->appointment_price_format_comma_separator = get_option('appointment_price_format_comma_separator' . '_' . get_current_user_id());
			$this->appointment_multiple_booking_sameslot = get_option('appointment_multiple_booking_sameslot' . '_' . get_current_user_id());	
			$this->appointment_slot_max_booking_limit = get_option('appointment_slot_max_booking_limit' . '_' . get_current_user_id());
			$this->appointment_appointment_auto_confirm = get_option('appointment_appointment_auto_confirm' . '_' . get_current_user_id());
			$this->appointment_dayclosing_overlap = get_option('appointment_dayclosing_overlap' . '_' . get_current_user_id());
			$this->appointment_thankyou_page = get_option('appointment_thankyou_page' . '_' . get_current_user_id());
			$this->appointment_thankyou_page_rdtime = get_option('appointment_thankyou_page_rdtime' . '_' . get_current_user_id());
			$this->appointment_main_container_background = get_option('appointment_main_container_background' . '_' . get_current_user_id());	
			$this->appointment_taxvat_status = get_option('appointment_taxvat_status' . '_' . get_current_user_id());
			$this->appointment_pd_type = get_option('appointment_pd_type' . '_' . get_current_user_id());
			$this->appointment_partial_deposit_amount = get_option('appointment_partial_deposit_amount' . '_' . get_current_user_id());
			$this->appointment_partial_deposit_type= get_option('appointment_partial_deposit_type' . '_' . get_current_user_id());
			$this->appointment_partial_deposit_status= get_option('appointment_partial_deposit_status' . '_' . get_current_user_id());
			$this->appointment_partial_deposit_message= get_option('appointment_partial_deposit_message' . '_' . get_current_user_id());
			$this->appointment_taxvat_type = get_option('appointment_taxvat_type' . '_' . get_current_user_id());
			$this->appointment_taxvat_amount = get_option('appointment_taxvat_amount' . '_' . get_current_user_id());
			$this->appointment_location_sortby = get_option('appointment_location_sortby' . '_' . get_current_user_id());
			$this->booking_cart_description = get_option('booking_cart_description' . '_' . get_current_user_id());
			$this->appointment_datepicker_format = get_option('appointment_datepicker_format' . '_' . get_current_user_id());
			$this->appointment_cancelation_policy_status = get_option('appointment_cancelation_policy_status' . '_' . get_current_user_id());
			$this->appointment_cancelation_policy_header = get_option('appointment_cancelation_policy_header' . '_' . get_current_user_id());
			$this->appointment_cancelation_policy_text = get_option('appointment_cancelation_policy_text' . '_' . get_current_user_id());
			$this->appointment_allow_terms_and_conditions = get_option('appointment_allow_terms_and_conditions' . '_' . get_current_user_id());
			$this->appointment_allow_terms_and_conditions_url = get_option('appointment_allow_terms_and_conditions_url' . '_' . get_current_user_id());
			$this->appointment_allow_privacy_policy = get_option('appointment_allow_privacy_policy' . '_' . get_current_user_id());
			$this->appointment_allow_privacy_policy_url = get_option('appointment_allow_privacy_policy_url' . '_' . get_current_user_id());
						
			/*** End ***/
				
			/** Company Settings **/
			$this->appointment_company_name = get_option('appointment_company_name' . '_' . get_current_user_id());
			$this->appointment_company_email = get_option('appointment_company_email' . '_' . get_current_user_id());
			$this->appointment_company_address = get_option('appointment_company_address' . '_' . get_current_user_id());
			$this->appointment_company_city = get_option('appointment_company_city' . '_' . get_current_user_id());
			$this->appointment_company_state = get_option('appointment_company_state' . '_' . get_current_user_id());
			$this->appointment_company_zip = get_option('appointment_company_zip' . '_' . get_current_user_id());
			$this->appointment_company_country = get_option('appointment_company_country' . '_' . get_current_user_id());
			$this->appointment_company_logo = get_option('appointment_company_logo' . '_' . get_current_user_id());
			$this->appointment_company_country_code = get_option('appointment_company_country_code' . '_' . get_current_user_id());
			$this->appointment_company_phone = get_option('appointment_company_phone' . '_' . get_current_user_id());
			$this->default_company_country_flag = get_option('default_company_country_flag' . '_' . get_current_user_id());
			/*** End ***/
				
			/** Payment Settings **/
			$this->appointment_payment_method_Paypal = get_option('appointment_payment_method_Paypal' . '_' . get_current_user_id());
			$this->appointment_payment_method_Stripe = get_option('appointment_payment_method_Stripe' . '_' . get_current_user_id());
			$this->appointment_payment_method_Authorizenet = get_option('appointment_payment_method_Authorizenet' . '_' . get_current_user_id());
			$this->appointment_payment_method_2Checkout = get_option('appointment_payment_method_2Checkout' . '_' . get_current_user_id());
			$this->appointment_locally_payment_status= get_option('appointment_locally_payment_status' . '_' . get_current_user_id());
			$this->appointment_payment_gateways_status= get_option('appointment_payment_gateways_status' . '_' . get_current_user_id());			
			$this->appointment_payment_method_Payumoney= get_option('appointment_payment_method_Payumoney' . '_' . get_current_user_id());			
			$this->appointment_payment_method_Paytm= get_option('appointment_payment_method_Paytm' . '_' . get_current_user_id());			
			//Paypal
			$this->appointment_paypal_direct_cc_dc_payment = get_option('appointment_paypal_direct_cc_dc_payment' . '_' . get_current_user_id());
			$this->appointment_paypal_title = get_option('appointment_paypal_title' . '_' . get_current_user_id());
			$this->appointment_paypal_description = get_option('appointment_paypal_description' . '_' . get_current_user_id());
			$this->appointment_paypal_merchant_email = get_option('appointment_paypal_merchant_email' . '_' . get_current_user_id());
			$this->appointment_paypal_testing_mode = get_option('appointment_paypal_testing_mode' . '_' . get_current_user_id());
			$this->appointment_paypal_guest_checkout = get_option('appointment_paypal_guest_checkout' . '_' . get_current_user_id());			
			$this->appointment_paypal_api_username = get_option('appointment_paypal_api_username' . '_' . get_current_user_id());
			$this->appointment_paypal_api_password = get_option('appointment_paypal_api_password' . '_' . get_current_user_id());
			$this->appointment_paypal_api_signature = get_option('appointment_paypal_api_signature' . '_' . get_current_user_id());		
			//Stripe
			$this->appointment_stripe_secretKey =  get_option('appointment_stripe_secretKey' . '_' . get_current_user_id());
			$this->appointment_stripe_publishableKey =  get_option('appointment_stripe_publishableKey' . '_' . get_current_user_id());			
			//Authorize.Net
			$this->appointment_authorizenet_title = get_option('appointment_authorizenet_title' . '_' . get_current_user_id());
			$this->appointment_authorizenet_desc = get_option('appointment_authorizenet_desc' . '_' . get_current_user_id());
			$this->appointment_authorizenet_api_loginid = get_option('appointment_authorizenet_api_loginid' . '_' . get_current_user_id());
			$this->appointment_authorizenet_transaction_key = get_option('appointment_authorizenet_transaction_key' . '_' . get_current_user_id());
			$this->appointment_authorizenet_testing_mode = get_option('appointment_authorizenet_testing_mode' . '_' . get_current_user_id());
			/* 2Checkout */		
			$this->appointment_2checkout_publishablekey = get_option('appointment_2checkout_publishablekey' . '_' . get_current_user_id());
			$this->appointment_2checkout_privateKey = get_option('appointment_2checkout_privateKey' . '_' . get_current_user_id());
			$this->appointment_2checkout_sellerid = get_option('appointment_2checkout_sellerid' . '_' . get_current_user_id());
			$this->appointment_2checkout_testing_mode = get_option('appointment_2checkout_testing_mode' . '_' . get_current_user_id());
			/* Payumoney */
			$this->appointment_payumoney_merchantkey = get_option('appointment_payumoney_merchantkey' . '_' . get_current_user_id());
			$this->appointment_payumoney_saltkey = get_option('appointment_payumoney_saltkey' . '_' . get_current_user_id());
			$this->appointment_payumoney_testing_mode = get_option('appointment_payumoney_testing_mode' . '_' . get_current_user_id());
			/* Paytm */
			$this->appointment_paytm_merchantkey = get_option('appointment_paytm_merchantkey' . '_' . get_current_user_id());
			$this->appointment_paytm_merchantid = get_option('appointment_paytm_merchantid' . '_' . get_current_user_id());
			$this->appointment_paytm_website = get_option('appointment_paytm_website' . '_' . get_current_user_id());
			$this->appointment_paytm_channelid = get_option('appointment_paytm_channelid' . '_' . get_current_user_id());
			$this->appointment_paytm_industryid = get_option('appointment_paytm_industryid' . '_' . get_current_user_id());
			$this->appointment_paytm_testing_mode = get_option('appointment_paytm_testing_mode' . '_' . get_current_user_id());

			/* Email Settings */
			$this->appointment_admin_eamil_address = get_option('appointment_admin_eamil_address' . '_' . get_current_user_id());
			$this->appointment_email_sender_name = get_option('appointment_email_sender_name' . '_' . get_current_user_id());
			$this->appointment_email_sender_address = get_option('appointment_email_sender_address' . '_' . get_current_user_id());
			$this->appointment_admin_email_notification_status = get_option('appointment_admin_email_notification_status' . '_' . get_current_user_id());
			$this->appointment_manager_email_notification_status = get_option('appointment_manager_email_notification_status' . '_' . get_current_user_id());			
			$this->appointment_service_provider_email_notification_status = get_option('appointment_service_provider_email_notification_status' . '_' . get_current_user_id());
			$this->appointment_client_email_notification_status = get_option('appointment_client_email_notification_status' . '_' . get_current_user_id());
			$this->appointment_email_reminder_buffer = get_option('appointment_email_reminder_buffer' . '_' . get_current_user_id()); 
			
		
			/* Social Login Settings */
			$this->appointment_fb_social_login_status = get_option('appointment_fb_social_login_status' . '_' . get_current_user_id());
			$this->appointment_fb_appid = get_option('appointment_fb_appid' . '_' . get_current_user_id());
			$this->appointment_fb_appsecret = get_option('appointment_fb_appsecret' . '_' . get_current_user_id());
			
			/* SMS Reminder Settings */
			$this->appointment_sms_reminder_status = get_option('appointment_sms_reminder_status' . '_' . get_current_user_id());

			$this->appointment_sms_noti_twilio = get_option('appointment_sms_noti_twilio' . '_' . get_current_user_id());
			$this->appointment_twilio_number = get_option('appointment_twilio_number' . '_' . get_current_user_id());
			$this->appointment_twilio_sid = get_option('appointment_twilio_sid' . '_' . get_current_user_id());
			$this->appointment_twilio_auth_token = get_option('appointment_twilio_auth_token' . '_' . get_current_user_id());
			$this->appointment_twilio_client_sms_notification_status = get_option('appointment_twilio_client_sms_notification_status' . '_' . get_current_user_id());
			$this->appointment_twilio_service_provider_sms_notification_status = get_option('appointment_twilio_service_provider_sms_notification_status' . '_' . get_current_user_id());
			$this->appointment_twilio_admin_sms_notification_status = get_option('appointment_twilio_admin_sms_notification_status' . '_' . get_current_user_id());
			$this->appointment_twilio_admin_phone_no = get_option('appointment_twilio_admin_phone_no' . '_' . get_current_user_id());
			$this->appointment_twilio_ccode = get_option('appointment_twilio_ccode' . '_' . get_current_user_id());
			$this->appointment_twilio_ccode_alph = get_option('appointment_twilio_ccode_alph' . '_' . get_current_user_id());
						
			$this->appointment_sms_noti_plivo = get_option('appointment_sms_noti_plivo' . '_' . get_current_user_id());
			$this->appointment_plivo_number = get_option('appointment_plivo_number' . '_' . get_current_user_id());
			$this->appointment_plivo_sid = get_option('appointment_plivo_sid' . '_' . get_current_user_id());
			$this->appointment_plivo_auth_token = get_option('appointment_plivo_auth_token' . '_' . get_current_user_id());
			$this->appointment_plivo_service_provider_sms_notification_status = get_option('appointment_plivo_service_provider_sms_notification_status' . '_' . get_current_user_id());
			$this->appointment_plivo_client_sms_notification_status = get_option('appointment_plivo_client_sms_notification_status' . '_' . get_current_user_id());
			$this->appointment_plivo_admin_sms_notification_status = get_option('appointment_plivo_admin_sms_notification_status' . '_' . get_current_user_id());
			$this->appointment_plivo_admin_phone_no = get_option('appointment_plivo_admin_phone_no' . '_' . get_current_user_id());
			$this->appointment_plivo_ccode = get_option('appointment_plivo_ccode' . '_' . get_current_user_id());
			$this->appointment_plivo_ccode_alph = get_option('appointment_plivo_ccode_alph' . '_' . get_current_user_id());

			$this->appointment_sms_noti_nexmo = get_option('appointment_sms_noti_nexmo' . '_' . get_current_user_id());
			$this->appointment_nexmo_apikey = get_option('appointment_nexmo_apikey' . '_' . get_current_user_id());
			$this->appointment_nexmo_api_secret = get_option('appointment_nexmo_api_secret' . '_' . get_current_user_id());
			$this->appointment_nexmo_form = get_option('appointment_nexmo_form' . '_' . get_current_user_id());
			$this->appointment_nexmo_send_sms_client_status = get_option('appointment_nexmo_send_sms_client_status' . '_' . get_current_user_id());
			$this->appointment_nexmo_send_sms_sp_status = get_option('appointment_nexmo_send_sms_sp_status' . '_' . get_current_user_id());
			$this->appointment_nexmo_send_sms_admin_status = get_option('appointment_nexmo_send_sms_admin_status' . '_' . get_current_user_id());
			$this->appointment_nexmo_admin_phone_no = get_option('appointment_nexmo_admin_phone_no' . '_' . get_current_user_id());
			$this->appointment_nexmo_ccode = get_option('appointment_nexmo_ccode' . '_' . get_current_user_id());
			$this->appointment_nexmo_ccode_alph = get_option('appointment_nexmo_ccode_alph' . '_' . get_current_user_id());
			
			$this->appointment_sms_noti_textlocal = get_option('appointment_sms_noti_textlocal' . '_' . get_current_user_id());
			$this->appointment_textlocal_apikey = get_option('appointment_textlocal_apikey' . '_' . get_current_user_id());
			$this->appointment_textlocal_sender = get_option('appointment_textlocal_sender' . '_' . get_current_user_id());
			$this->appointment_textlocal_service_provider_sms_notification_status = get_option('appointment_textlocal_service_provider_sms_notification_status' . '_' . get_current_user_id());
			$this->appointment_textlocal_client_sms_notification_status = get_option('appointment_textlocal_client_sms_notification_status' . '_' . get_current_user_id());
			$this->appointment_textlocal_admin_sms_notification_status = get_option('appointment_textlocal_admin_sms_notification_status' . '_' . get_current_user_id());
			$this->appointment_textlocal_admin_phone_no = get_option('appointment_textlocal_admin_phone_no' . '_' . get_current_user_id());
			$this->appointment_textlocal_ccode = get_option('appointment_textlocal_ccode' . '_' . get_current_user_id());
			$this->appointment_textlocal_ccode_alph = get_option('appointment_textlocal_ccode_alph' . '_' . get_current_user_id());
			
			
	
	}

}
?>