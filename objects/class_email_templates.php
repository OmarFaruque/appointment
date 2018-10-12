<?php
class appointment_email_template
{

    /* object properties */
    public $id;
	public $location_id;
    public $email_template_name;
    public $email_subject;
    public $email_message;
    public $template_status;
    public $default_message;
    public $method;
    public $email_parent_template;
	public $user_type;
	public $business_owner_id;
	
	
  public function __construct() {	
		global $wpdb;				
		
		$company_header = '<tr><td style="text-align:center;padding:5px 0px;">';						
		
		if(get_option("appointment_company_logo".'_'.$this->business_owner_id)!='') {			
		$upload_dir_path= wp_upload_dir();			
		$image_path= $upload_dir_path['baseurl'].get_option("appointment_company_logo".'_'.$this->business_owner_id);			
		$company_header .= '<img style="float:left;clear:both;margin-bottom:20px;" src="'.$image_path.'" />';		
		}					
		
		if (get_option("appointment_company_name".'_'.$this->business_owner_id)!='') {			 
		$company_header .= '<h2 style="font-size:20px;float:right;vertical-align:middle;line-height:60px;">'.get_option("appointment_company_name".'_'.$this->business_owner_id).'</h2>';		} 				
		
		if(get_option("appointment_company_logo".'_'.$this->business_owner_id)!='' || get_option("appointment_company_name".'_'.$this->business_owner_id)!='') {					$company_header .= '<hr style="clear:both;"></hr>';		}				
		
		$company_header .= '</td></tr>';
		
		
		
		$this->email_parent_template =' 
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title></title>
	<style>
	* {
		margin: 0;
		padding: 0;
		font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
		font-size: 100%;
		line-height: 1.6;
	}

	img {
		max-width: 100%;
	}

	body {
		-webkit-font-smoothing: antialiased;
		-webkit-text-size-adjust: none;
		width: 100% !important;				min-width:600px !important;
		height: 100%;
	}
	
	a {
		color: #348eda;
	}

	table.body-wrap {		/* width: 100%; */ 		padding: 20px;	   
	}

	table.body-wrap .container {
		border: 1px solid #f0f0f0;
	}
	
	
	.email-btn-primary, #email-btn-primary  {
		text-decoration: none;
		color: #FFF;
		background-color: #348eda;
		border: solid #348eda;
		border-width: 10px 30px;
		line-height: 1;
		font-weight: bold;
		margin-right: 10px;
		text-align: center;
		cursor: pointer;
		display: inline-block;
		border-radius: 10px;
	}

	.email-btn-secondary, #email-btn-secondary {
		text-decoration: none;
		color: #FFF;
		background-color: red;
		border: solid red;
		border-width: 10px 30px;
		line-height: 1;
		font-weight: bold;
		margin-right: 10px;
		text-align: center;
		cursor: pointer;
		display: inline-block;
		border-radius: 10px;
	}

	p, ul, ol, p span {
		margin-bottom: 10px;
		font-weight: normal;
		font-size: 14px;
	}	p span strong {		min-width: 150px;	}

	ul li, ol li {
		margin-left: 5px;
		list-style-position: inside;
	}

	.container {
		display: block!important;
		max-width: 600px!important;
		margin: 0 auto!important; /* makes it centered */
		clear: both!important;
	}

	.body-wrap .container {
		padding: 20px;
	}

	.content {
		max-width: 600px;
		margin: 0 auto;
		display: block;
	}

	.content table {
		width: 100%;
	}
	</style>
	</head>

	<body bgcolor="#'.get_option("appointment_appearance_primary_color".'_'.$this->business_owner_id).'">
	<table class="body-wrap" bgcolor="#'.get_option("appointment_appearance_primary_color".'_'.$this->business_owner_id).'" style="padding:15px; border-radius:10px;"    >
		<tr>
			<td></td>
			<td class="container" bgcolor="#FFFFFF" style="background-color:#FFFFFF !important;border-radius:10px;padding:10px 15px;">
				<div class="content">
					<table style="width:100% !important;">		'.$company_header.'					
						<tr>
							<td>
								<p>###msg_content###</p>
							</td>
						</tr>
					</table>
				</div>
			</td>
			<td></td>
		</tr>
	</table>
	</body>
	</html>';
}

    
	
	/**
     * create appointment email template table
     */ 
	function create_table() {
	global $wpdb;

	$table_name = $wpdb->prefix .'apt_email_templates';
	
	if( $wpdb->get_var( "show tables like '{$table_name}'" ) != $table_name ) {		
	
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `business_owner_id` int(11) NOT NULL,
					  `location_id` int(11) NOT NULL,
					  `email_template_name` varchar(100) NOT NULL,
					  `email_subject` varchar(200) NOT NULL,
					  `email_message` text NOT NULL,
					  `default_message` text NOT NULL,
					  `email_template_status` enum('e','d') NOT NULL,
					  `user_type` enum('AM','SP','C') NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
	
	
	dbDelta($sql);     
			}
	} 
	
	/* Constructor Function to set the default values */
    function appointment_create_email_template()
    {
        global $wpdb;
        
        $appointment_email_templates = array(
           /* Admin/Manager email template New Appointment Request Requires Approval */
            'New Appointment Request Requires Approval' => 'Hi {{admin_manager_name}}, <br /><br /> You have a new appointment request as follows,<br />{{booking_details}}<br /> Please proceed with confirm or reject action. <br /><br />  Customer details as follows: <br /> {{appoinment_client_detail}} <br /><br /> Thank You, <br /> {{company_name}}',
           /* Admin/Manager email template New Appointment Approved */
            'Appointment Approved' => 'Hi {{admin_manager_name}}, <br /><br /> You have successfully confirmed the following appointment ,<br />{{booking_details}}<br /><br />  Customer details as follows: <br /> {{appoinment_client_detail}} <br /><br /> Thank You, <br /> {{company_name}}',
           /* Admin/Manager email template Appointment Cancel By Customer */
            'Appointment Cancelled By Customer' => 'Hi {{admin_manager_name}}, <br /><br /> We are sorry that appointment request for <br />{{booking_details}}<br /> has been Cancel by Client. <br /><br />  Cancellation reason: <br /> {{appointment_cancel_reason}} <br /><br />  Customer Detail: <br /> {{appoinment_client_detail}} <br /><br /> Thank You, <br /> {{company_name}}',
           /* Admin/Manager email template  Appointment Rejected */
            'Appointment Rejected' => 'Hi {{admin_manager_name}}, <br /><br /> You have successfully rejected the following appointment<br />{{booking_details}} <br /><br /> Reject reason: <br /> {{appointment_reject_reason}} <br /><br />  Thank You, <br /> {{company_name}},',
           /* Admin/Manager email template Appointment Cancel by SP/admin */
            'Appointment Cancelled' => 'Hi {{admin_manager_name}}, <br /><br /> You have successfully cancelled the following appointment<br />{{booking_details}} <br /><br /> Cancellation reason: <br /> {{appointment_cancel_reason}} <br /><br /> Thank You, <br /> {{company_name}}',
           /* Admin/Manager email template Appointment Marked As No Show */
            'Admin Appointment Marked As No Show' => 'Hi {{admin_manager_name}}, <br /><br />You have successfully mark the following appointment as no-show.<br />{{booking_details}}<br /><br /> Customer Detail: <br /> {{appoinment_client_detail}} <br /><br /> Thank You, <br /> {{company_name}}',
           /* Admin/Manager email template Appointment Reminder */
            'Admin Appointment Reminder' => 'Hi {{admin_manager_name}}, <br /><br /> This is a reminder notification for following appointment<br />{{booking_details}}  <br /><br /> Customer Detail: <br /> {{appoinment_client_detail}} <br /><br /> Thank You, <br /> {{company_name}}',
           /* Admin/Manager email template Appointment Completed */
            'Appointment Completed with client' => 'Hi {{admin_manager_name}}, <br /><br /> Your appointment with following details has been completed now.<br />{{booking_details}}<br /><br />Customer Detail: <br /> {{appoinment_client_detail}} Thank You,<br /><br /> {{company_name}}',
		  /* Admin/Manager email template Appointment Rescheduled */
            'Appointment Rescheduled' => 'Hi {{admin_manager_name}}, <br /><br /> Your appointment with following details has been Rescheduled.<br />{{booking_details}}<br /><br />Customer Detail: <br /> {{appoinment_client_detail}} Thank You,<br /><br /> {{company_name}}',
			
			
			
			
			/* Service Provider email template New Appointment Request Requires Approval */
            'SP New Appointment Request Requires Approval' => 'Hi {{service_provider_name}}, <br /><br /> You have a new appointment request as follows,<br />{{booking_details}}<br /> Please proceed with confirm or reject action. <br /><br />  Customer details as follows: <br /> {{appoinment_client_detail}} <br /><br /> Thank You, <br /> {{company_name}}',
            /* Service Provider email template New Appointment Approved */
            'SP Appointment Approved' => 'Hi {{service_provider_name}}, <br /><br /> You have successfully confirmed the following appointment ,<br />{{booking_details}}<br /><br />  Customer details as follows: <br /> {{appoinment_client_detail}} <br /><br /> Thank You, <br /> {{company_name}}',
            /* Service Provider email template Appointment Cancel By Customer */
            'SP Appointment Cancelled By Customer' => 'Hi {{service_provider_name}}, <br /><br /> We are sorry that appointment request for <br />{{booking_details}}<br /> has been Cancel by Client. <br /><br />  Cancellation reason: <br /> {{appointment_cancel_reason}} <br /><br />  Customer Detail: <br /> {{appoinment_client_detail}} <br /><br /> Thank You, <br /> {{company_name}}',
            /* Service Provider email template  Appointment Rejected */
            'SP Appointment Rejected' => 'Hi {{service_provider_name}}, <br /><br /> You have successfully rejected the following appointment<br />{{booking_details}} <br /><br /> Reject reason: <br /> {{appointment_reject_reason}} <br /><br />  Thank You, <br /> {{company_name}},',
            /* Service Provider email template Appointment Cancel by SP/admin */
            'SP Appointment Cancelled' => 'Hi {{service_provider_name}}, <br /><br /> You have successfully cancelled the following appointment<br />{{booking_details}} <br /><br /> Cancellation reason: <br /> {{appointment_cancel_reason}} <br /><br /> Thank You, <br /> {{company_name}}',
            /* Service Provider email template Appointment Marked As No Show */
            'SP Admin Appointment Marked As No Show' => 'Hi {{service_provider_name}}, <br /><br />You have successfully mark the following appointment as no-show.<br />{{booking_details}}<br /><br /> Customer Detail: <br /> {{appoinment_client_detail}} <br /><br /> Thank You, <br /> {{company_name}}',
            /* Service Provider email template Appointment Reminder */
            'SP Admin Appointment Reminder' => 'Hi {{service_provider_name}}, <br /><br /> This is a reminder notification for following appointment<br />{{booking_details}}  <br /><br /> Customer Detail: <br /> {{appoinment_client_detail}} <br /><br /> Thank You, <br /> {{company_name}}',
            /* Service Provider email template Appointment Completed */
            'SP Appointment Completed with client' => 'Hi {{service_provider_name}}, <br /><br /> Your appointment with following details has been completed now.<br />{{booking_details}}<br /><br />Customer Detail: <br /> {{appoinment_client_detail}} Thank You,<br /><br /> {{company_name}}',			
			/* Service Provider email template Appointment Rescheduled */
            'SP Appointment Rescheduled' => 'Hi {{service_provider_name}}, <br /><br /> Your appointment with following details has been Rescheduled now.<br />{{booking_details}}<br /><br />Customer Detail: <br /> {{appoinment_client_detail}} Thank You,<br /><br /> {{company_name}}',
			
			
			
			/* customer email template appointment Request */
			'Appointment Request' => 'Hi {{customer_name}},<br /><br />You have successfully booked an appointment. Your appointment details are noted below:<br/><br/>{{booking_details}}<br/><br/>Please note that your appointment is tentative and will be confirmed.<br/><br/>Regards,<br/>{{company_name}}',
			/* customer email template appointment Approved */
            'Appointment Approved by service provider' => 'Hi {{customer_name}},<br /><br />Your appointment for <br />{{booking_details}}<br /> has been approved. <br /><br /> Thank You, <br /> {{company_name}}',
			/* customer email template appointment Cancel by customer */
            'Appointment Cancelled by you' => 'Hi {{customer_name}},<br /><br />Your appointment for  <br />{{booking_details}}<br /> has been cancelled by you. <br /><br />             Cancellation reason: <br /> {{appointment_cancel_reason}} <br /><br />    Thank You, <br /> {{company_name}}',
            /* customer email template appointment Rejected */
            'Appointment Rejected by service provider' => 'Hi {{customer_name}},<br /><br />This is the confirmation that your appointment for <br />{{booking_details}}<br /> has been rejected. <br /><br /> Rejection reason: <br /> {{appointment_reject_reason}} <br /><br />    Thank You, <br /> {{company_name}}',
            
            /* customer email template appointment Cancel by SP/Admin */
            'Appointment Cancelled by Service Provider' => 'Hi {{customer_name}},<br /><br />Your appointment for  <br />{{booking_details}}<br /> has been cancelled by service provider. <br /><br />             Cancellation reason: <br /> {{appointment_cancel_reason}} <br /><br />    Thank You, <br /> {{company_name}}',
			 /* customer email template appointment Marked As No Show */
            'Appointment Marked As No Show' => 'Hi {{customer_name}}, <br /><br /> We are sorry that your appointment for <br />{{booking_details}}<br /> has been marked as no-show. <br /><br /> Thank You, <br /> {{company_name}}',                    
            /* customer email template appointment Reminder */
            'Appointment Reminder' => 'Hi {{customer_name}}, <br /><br /> This is reminder notification for your appointment for <br />{{booking_details}} <br /><br /> Thank You, <br /> {{company_name}}',
           /* customer email template appointment Completed */
            'Appointment Completed' => 'Hi {{customer_name}}, <br /><br /> Your appointment  for<br />{{booking_details}}<br /> is completed. <br /><br />    Thank You, <br /><br /> {{company_name}}',    
			/* customer email template appointment Rescheduled */
            'Appointment Rescheduled By Service Provider' => 'Hi {{customer_name}}, <br /><br /> We are sorry that your appointment for <br />{{booking_details}}<br /> has been Rescheduled. <br /><br /> Thank You, <br /> {{company_name}}'  
			
			
        );
        $return = $wpdb->get_results("SELECT * FROM  ".$wpdb->prefix."apt_email_templates where business_owner_id=".get_current_user_id()."");
        if (sizeof($return) == 0) {           
        /* Inserting Email Template name in database */
        $email_template_name_array=array('AA','CA','CCA','RA','CSA','MNA','RMA','COA','RSA','AS','CS','CCS','RS','CSS','MNS','RMS','COS','RSS','AC','CC','CCC','RC','CSC','MNC','RMC','COC','RSC');
            
           
            $arrCounter = 0;
            foreach ($appointment_email_templates as $option_key => $option_value) {
                if($arrCounter<=8){
					$usertype='AM';
				}elseif($arrCounter>=9 && $arrCounter<=17){
					$usertype='SP';
				}elseif($arrCounter>=18 && $arrCounter<=26){
					$usertype='C';
				}							
								
                $return = $wpdb->query("INSERT INTO ".$wpdb->prefix."apt_email_templates SET business_owner_id=".get_current_user_id().", email_template_name ='" . $email_template_name_array[$arrCounter] . "',default_message='" . $option_value . "',email_subject='" . str_replace('SP','',$option_key). "',user_type='".$usertype."'");
                $arrCounter++;
            }
            
            
        }
       
    }
    
	/**
	* Read All Email Templates
	* @return $return - db records objects
	*/
    function readAll()
    {
        global $wpdb;
        $return = $wpdb->get_results("SELECT * FROM  ".$wpdb->prefix."apt_email_templates");
        return $return;
    }
    
	/**
	* Read One Email Template record
	* @return $return - db records objects
	*/
    function readOne()
    {
        global $wpdb;
        $return = $wpdb->get_results("SELECT email_subject,email_message,email_template_status,default_message FROM  ".$wpdb->prefix."apt_email_templates  WHERE  email_template_name='" . $this->email_template_name . "'");
        return $return;
    }
    
	
	/**
	* Update Email Template subject
	* @return $return - true on success,false on failure
	*/
	function update_template_subject_message()
    {
        global $wpdb;
        $return = $wpdb->query("UPDATE  ".$wpdb->prefix."apt_email_templates  SET email_subject  = '" . $this->email_subject . "', 
		email_message  = '" . $this->email_message . "' WHERE id  = '" . $this->id . "'");				
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
        $return = $wpdb->query("UPDATE  ".$wpdb->prefix."apt_email_templates  SET email_template_status  = '".$this->template_status."' WHERE id  = '".$this->id."'");
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
        $result = $wpdb->get_results("SELECT * FROM  ".$wpdb->prefix."apt_email_templates where business_owner_id=".$this->business_owner_id." and user_type='".$this->user_type."'");
        return $result;	
	}
	
	/** 
	ReadAll Email Templates By User Type 
	**/
	function get_emailtemplate_by_sending_method(){
		global $wpdb;
		
        $result = $wpdb->get_results("SELECT * FROM  ".$wpdb->prefix."apt_email_templates where email_template_name='".$this->method."A' or email_template_name='".$this->method."S' or email_template_name='".$this->method."C'");
        return $result;	
	}
	
    
}
?>