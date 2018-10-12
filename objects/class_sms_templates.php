<?php
class appointment_sms_template
{

    /* object properties */
    public $id;
	public $location_id;
    public $sms_subject;
    public $sms_template_name;
    public $sms_message;
    public $template_status;
    public $default_message;
    public $sms_parent_template;
    public $user_type;
    public $business_owner_id;
	
	  
	
	/**
     * create appointment sms template table
     */ 
	function create_table() {
	global $wpdb;

	$table_name = $wpdb->prefix .'apt_sms_templates';
	
	if( $wpdb->get_var( "show tables like '{$table_name}'" ) != $table_name ) {		
	
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `business_owner_id` int(11) NOT NULL,
					  `location_id` int(11) NOT NULL,
					  `sms_template_name` varchar(100) NOT NULL,
					  `sms_subject` varchar(100) NOT NULL,
					  `sms_message` text NOT NULL,
					  `default_message` text NOT NULL,
					  `sms_template_status` enum('e','d') NOT NULL,
					  `user_type` enum('AM','SP','C') NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
	
	
	dbDelta($sql);     
			}
	} 
	
	/* Constructor Function to set the default values */
    function appointment_create_sms_template()
    {
        global $wpdb;
        
        $appointment_sms_templates = array(
            /* Admin/Manager email template New Appointment Request Requires Approval */
            'New Appointment Request Requires Approval' => 'Hi {{admin_manager_name}}, <br /><br /> You have a new appointment request as follows,<br />{{booking_detail}}<br /> Please proceed with confirm or reject action. <br /><br />  Customer details as follows: <br /> {{appoinment_client_detail}} <br /><br /> Thank You, <br /> {{company_name}}',
           /* Admin/Manager email template New Appointment Approved */
            'Appointment Approved' => 'Hi {{admin_manager_name}}, <br /><br /> You have successfully confirmed the following appointment ,<br />{{booking_detail}}<br /><br />  Customer details as follows: <br /> {{appoinment_client_detail}} <br /><br /> Thank You, <br /> {{company_name}}',
           /* Admin/Manager email template Appointment Cancel By Customer */
            'Appointment Cancelled By Customer' => 'Hi {{admin_manager_name}}, <br /><br /> We are sorry that appointment request for <br />{{booking_detail}}<br /> has been Cancel by Client. <br /><br />  Cancellation reason: <br /> {{appointment_cancel_reason}} <br /><br />  Customer Detail: <br /> {{appoinment_client_detail}} <br /><br /> Thank You, <br /> {{company_name}}',
           /* Admin/Manager email template  Appointment Rejected */
            'Appointment Rejected' => 'Hi {{admin_manager_name}}, <br /><br /> You have successfully rejected the following appointment<br />{{booking_detail}} <br /><br /> Reject reason: <br /> {{appointment_reject_reason}} <br /><br />  Thank You, <br /> {{company_name}},',
           /* Admin/Manager email template Appointment Cancel by SP/admin */
            'Appointment Cancelled' => 'Hi {{admin_manager_name}}, <br /><br /> You have successfully cancelled the following appointment<br />{{booking_detail}} <br /><br /> Cancellation reason: <br /> {{appointment_cancel_reason}} <br /><br /> Thank You, <br /> {{company_name}}',
           /* Admin/Manager email template Appointment Marked As No Show */
            'Admin Appointment Marked As No Show' => 'Hi {{admin_manager_name}}, <br /><br />You have successfully mark the following appointment as no-show.<br />{{booking_detail}}<br /><br /> Customer Detail: <br /> {{appoinment_client_detail}} <br /><br /> Thank You, <br /> {{company_name}}',
           /* Admin/Manager email template Appointment Reminder */
            'Admin Appointment Reminder' => 'Hi {{admin_manager_name}}, <br /><br /> This is a reminder notification for following appointment<br />{{booking_detail}}  <br /><br /> Customer Detail: <br /> {{appoinment_client_detail}} <br /><br /> Thank You, <br /> {{company_name}}',
           /* Admin/Manager email template Appointment Completed */
            'Appointment Completed with client' => 'Hi {{admin_manager_name}}, <br /><br /> Your appointment with following details has been completed now.<br />{{booking_detail}}<br /><br />Customer Detail: <br /> {{appoinment_client_detail}} Thank You,<br /><br /> {{company_name}}',
		  /* Admin/Manager email template Appointment Rescheduled */
            'Appointment Rescheduled' => 'Hi {{admin_manager_name}}, <br /><br /> Your appointment with following details has been Rescheduled.<br />{{booking_detail}}<br /><br />Customer Detail: <br /> {{appoinment_client_detail}} Thank You,<br /><br /> {{company_name}}',
			
			
			
			
			/* Service Provider email template New Appointment Request Requires Approval */
            'SP New Appointment Request Requires Approval' => 'Hi {{service_provider_name}}, <br /><br /> You have a new appointment request as follows,<br />{{booking_detail}}<br /> Please proceed with confirm or reject action. <br /><br />  Customer details as follows: <br /> {{appoinment_client_detail}} <br /><br /> Thank You, <br /> {{company_name}}',
            /* Service Provider email template New Appointment Approved */
            'SP Appointment Approved' => 'Hi {{service_provider_name}}, <br /><br /> You have successfully confirmed the following appointment ,<br />{{booking_detail}}<br /><br />  Customer details as follows: <br /> {{appoinment_client_detail}} <br /><br /> Thank You, <br /> {{company_name}}',
            /* Service Provider email template Appointment Cancel By Customer */
            'SP Appointment Cancelled By Customer' => 'Hi {{service_provider_name}}, <br /><br /> We are sorry that appointment request for <br />{{booking_detail}}<br /> has been Cancel by Client. <br /><br />  Cancellation reason: <br /> {{appointment_cancel_reason}} <br /><br />  Customer Detail: <br /> {{appoinment_client_detail}} <br /><br /> Thank You, <br /> {{company_name}}',
            /* Service Provider email template  Appointment Rejected */
            'SP Appointment Rejected' => 'Hi {{service_provider_name}}, <br /><br /> You have successfully rejected the following appointment<br />{{booking_detail}} <br /><br /> Reject reason: <br /> {{appointment_reject_reason}} <br /><br />  Thank You, <br /> {{company_name}},',
            /* Service Provider email template Appointment Cancel by SP/admin */
            'SP Appointment Cancelled' => 'Hi {{service_provider_name}}, <br /><br /> You have successfully cancelled the following appointment<br />{{booking_detail}} <br /><br /> Cancellation reason: <br /> {{appointment_cancel_reason}} <br /><br /> Thank You, <br /> {{company_name}}',
            /* Service Provider email template Appointment Marked As No Show */
            'SP Admin Appointment Marked As No Show' => 'Hi {{service_provider_name}}, <br /><br />You have successfully mark the following appointment as no-show.<br />{{booking_detail}}<br /><br /> Customer Detail: <br /> {{appoinment_client_detail}} <br /><br /> Thank You, <br /> {{company_name}}',
            /* Service Provider email template Appointment Reminder */
            'SP Admin Appointment Reminder' => 'Hi {{service_provider_name}}, <br /><br /> This is a reminder notification for following appointment<br />{{booking_detail}}  <br /><br /> Customer Detail: <br /> {{appoinment_client_detail}} <br /><br /> Thank You, <br /> {{company_name}}',
            /* Service Provider email template Appointment Completed */
            'SP Appointment Completed with client' => 'Hi {{service_provider_name}}, <br /><br /> Your appointment with following details has been completed now.<br />{{booking_detail}}<br /><br />Customer Detail: <br /> {{appoinment_client_detail}} Thank You,<br /><br /> {{company_name}}',			
			/* Service Provider email template Appointment Rescheduled */
            'SP Appointment Rescheduled' => 'Hi {{service_provider_name}}, <br /><br /> Your appointment with following details has been Rescheduled now.<br />{{booking_detail}}<br /><br />Customer Detail: <br /> {{appoinment_client_detail}} Thank You,<br /><br /> {{company_name}}',
			
			
			
			/* customer email template appointment Request */
			'Appointment Request' => 'Hi {{customer_name}},<br /><br />You have successfully booked an appointment. Your appointment details are noted below:<br/><br/>{{booking_detail}}<br/><br/>Please note that your appointment is tentative and will be confirmed.<br/><br/>Regards,<br/>{{company_name}}',
			/* customer email template appointment Approved */
            'Appointment Approved by service provider' => 'Hi {{customer_name}},<br /><br />Your appointment for <br />{{booking_detail}}<br /> has been approved. <br /><br /> Thank You, <br /> {{company_name}}',
			/* customer email template appointment Cancel by customer */
            'Appointment Cancelled by you' => 'Hi {{customer_name}},<br /><br />Your appointment for  <br />{{booking_detail}}<br /> has been cancelled by you. <br /><br />             Cancellation reason: <br /> {{appointment_cancel_reason}} <br /><br />    Thank You, <br /> {{company_name}}',
            /* customer email template appointment Rejected */
            'Appointment Rejected by service provider' => 'Hi {{customer_name}},<br /><br />This is the confirmation that your appointment for <br />{{booking_detail}}<br /> has been rejected. <br /><br /> Rejection reason: <br /> {{appointment_reject_reason}} <br /><br />    Thank You, <br /> {{company_name}}',
            
            /* customer email template appointment Cancel by SP/Admin */
            'Appointment Cancelled by Service Provider' => 'Hi {{customer_name}},<br /><br />Your appointment for  <br />{{booking_detail}}<br /> has been cancelled by service provider. <br /><br />             Cancellation reason: <br /> {{appointment_cancel_reason}} <br /><br />    Thank You, <br /> {{company_name}}',
			 /* customer email template appointment Marked As No Show */
            'Appointment Marked As No Show' => 'Hi {{customer_name}}, <br /><br /> We are sorry that your appointment for <br />{{booking_detail}}<br /> has been marked as no-show. <br /><br /> Thank You, <br /> {{company_name}}',                    
            /* customer email template appointment Reminder */
            'Appointment Reminder' => 'Hi {{customer_name}}, <br /><br /> This is reminder notification for your appointment for <br />{{booking_detail}} <br /><br /> Thank You, <br /> {{company_name}}',
           /* customer email template appointment Completed */
            'Appointment Completed' => 'Hi {{customer_name}}, <br /><br /> Your appointment  for<br />{{booking_detail}}<br /> is completed. <br /><br />    Thank You, <br /><br /> {{company_name}}',    
			/* customer email template appointment Rescheduled */
            'Appointment Rescheduled By Service Provider' => 'Hi {{customer_name}}, <br /><br /> We are sorry that your appointment for <br />{{booking_detail}}<br /> has been Rescheduled. <br /><br /> Thank You, <br /> {{company_name}}'            
			
			
        );
        $return = $wpdb->get_results("SELECT * FROM  ".$wpdb->prefix."apt_sms_templates where business_owner_id=".get_current_user_id()."");
        if (sizeof($return) == 0) {           
        /* Inserting Email Template name in database */
		$sms_template_name_array=array('AA','CA','CCA','RA','CSA','MNA','RMA','COA','RSA','AS','CS','CCS','RS','CSS','MNS','RMS','COS','RSS','AC','CC','CCC','RC','CSC','MNC','RMC','COC','RSC');
            
           
            $arrCounter = 0;
            foreach ($appointment_sms_templates as $option_key => $option_value) {
                if($arrCounter<=8){
					$usertype='AM';
				}elseif($arrCounter>=9 && $arrCounter<=17){
					$usertype='SP';
				}elseif($arrCounter>=18 && $arrCounter<=26){
					$usertype='C';
				}							
				
                $return = $wpdb->query("INSERT INTO ".$wpdb->prefix."apt_sms_templates SET business_owner_id=".get_current_user_id().", sms_template_name ='" . $sms_template_name_array[$arrCounter] . "',sms_subject='" . str_replace('SP','',$option_key). "',default_message='" . $option_value . "',user_type='".$usertype."'");
                $arrCounter++;
            }
            
            
        }
       
    }
    
	/**
	* Read All SMS Templates
	* @return $return - db records objects
	*/
    function readAll()
    {
        global $wpdb;
        $return = $wpdb->get_results("SELECT * FROM  ".$wpdb->prefix."apt_sms_templates where business_owner_id=".$this->business_owner_id."");
        return $return;
    }
    
	/**
	* Read One SMS Template record
	* @return $return - db records objects
	*/
    function readOne()
    {
        global $wpdb;
        $return = $wpdb->get_results("SELECT sms_message,sms_subject,sms_template_status,default_message FROM  ".$wpdb->prefix."apt_sms_templates  WHERE  sms_template_name='" . $this->sms_template_name . "'");
        return $return;
    }
    
	
	/**
	* Update SMS Template subject
	* @return $return - true on success,false on failure
	*/
	function update_template_subject_message()
    {
        global $wpdb;
        $return = $wpdb->query("UPDATE  ".$wpdb->prefix."apt_sms_templates  SET  sms_message  = '" . $this->sms_message . "' WHERE id  = '" . $this->id . "'");				
        if ($return) {
            return true;
        } else {
            return false;
        }
    }
    
	/**
	* Update template status
	* @return $return - true on success, false on failure
	*/
    function update_template_status()
    {
        global $wpdb;
			
        $return = $wpdb->query("UPDATE  ".$wpdb->prefix."apt_sms_templates  SET sms_template_status  = '".$this->template_status."' WHERE id  = '".$this->id."'");
        if ($return) {
            return true;
        } else {
            return false;
        }
    }
	
	/** 
	ReadAll Email Templates By User Type 
	**/
	function readall_by_usertype(){
		global $wpdb;
		global $wpdb;
        $result = $wpdb->get_results("SELECT * FROM  ".$wpdb->prefix."apt_sms_templates where user_type='".$this->user_type."' and business_owner_id=".$this->business_owner_id."");
        return $result;	
	}
	
	 function gettemplate_sms($usertype,$status,$template_name){
	  global $wpdb;
	  $return = $wpdb->get_results("SELECT 
	  sms_message,
	  sms_subject,
	  default_message,
	  sms_template_status FROM ".$wpdb->prefix."apt_sms_templates WHERE user_type='".$usertype."' and sms_template_name='".$template_name."'");
			return $return;
	 }
    
}
?>