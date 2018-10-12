<?php
class appointment_booking{    
    
	
	/* Object property booking Identity */
	public $booking_id;	
	
	/* Object property Location ID */
	public $location_id;	
	
	/* Object property order Identity */
	public $order_id;	

	/* Object property business_owner_id */ 
	public $business_owner_id;
	
	/* Object property client Identity */
	public $client_id;	
	
	/* Object property service Identity */
	public $service_id;

	/* Object property provider Identity */	
	public $provider_id;	
	
	/* Object property booking datetime */
	public $booking_datetime;	
	
		/* Object property booking datetime */
	public $booking_endtime;
	
	/* Object property booking status */
	public $booking_status;		
	
	/* Object property order Identity */
	public $last_order_id;
	
	/* Object property reject reason string */
	public $reject_reason;
	
	/* Object property cancel reason string */
	public $cancel_reason;
	
	/* Object property Confirm Note string */
	public $confirm_note;
	
	/* Object property Reschedule Note string */
	public $reschedule_note;
	
	/* Object property last modify date*/
	public $lastmodify;
	
	/* Object property reminder buffer */
	public $reminder_buffer;
	
	public $gc_event_id;
	 
	/* Object property startdate */
	public $start_date;
	 
	/* Object property enddate */
	public $end_date; 
	/* Addons */ 
	public $addons_order_id; 
	public $add_service_id; 
	public $addons_service_id; 
	public $addons_amount; 
	public $addons_id; 
	public $associate_service_id; 
	 /**
     * create appointment bookings table
     */ 
	function create_table() {
	global $wpdb;

	$table_name = $wpdb->prefix .'apt_bookings';
	
	if( $wpdb->get_var( "show tables like '{$table_name}'" ) != $table_name ) {		
	
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `location_id` int(11) NOT NULL,
				  `order_id` int(11) NOT NULL,
				  `business_owner_id` int(11) NOT NULL,
				  `client_id` int(11) NOT NULL,
				  `service_id` int(11) NOT NULL,
				  `provider_id` int(11) NOT NULL,
				  `booking_price` double NOT NULL,
				  `booking_datetime` varchar(18) COLLATE utf8_unicode_ci NOT NULL,
				  `booking_endtime` varchar(18) COLLATE utf8_unicode_ci NOT NULL,
				  `booking_status` enum('A','C','R','CC','CS','CO','MN','RS') COLLATE utf8_unicode_ci NOT NULL COMMENT 'A=active, C=Confirm, R=Reject, CC=Cancel by Client, CS=Cancel by service provider,CO=Completed,MN=MARK AS NOSHOW,RS=Rescheduled',
				  `reject_reason` text COLLATE utf8_unicode_ci NOT NULL,
				  `cancel_reason` text COLLATE utf8_unicode_ci NOT NULL,
				  `confirm_note` text COLLATE utf8_unicode_ci NOT NULL,
				  `reschedule_note` text COLLATE utf8_unicode_ci NOT NULL,
				  `reminder` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0=Email Not Sent,1=Email Sent',
				  `notification` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0=Unread,1=Read',
				  `lastmodify`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  `gc_event_id` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;";
	
	
	dbDelta($sql);     
			}
	} 

	 function add_bookings(){
		 global $wpdb;		
		
		$result = $wpdb->query("INSERT INTO ".$wpdb->prefix."apt_bookings (`id`, `location_id`, `order_id`, `business_owner_id`, `client_id`, `service_id`, `provider_id`, `booking_price`, `booking_datetime`, `booking_endtime`, `booking_status`, `reject_reason`, `cancel_reason`, `confirm_note`, `reschedule_note`, `reminder`, `notification`, `lastmodify`, `gc_event_id`) VALUES ('','".$this->location_id."','".$this->order_id."', '".$this->business_owner_id."', '".$this->client_id."','".$this->service_id."','".$this->provider_id."','".$this->booking_price."','".$this->booking_datetime."','".$this->booking_endtime."','".$this->booking_status."','".$this->reject_reason."','".$this->cancel_reason."','".$this->confirm_note."','".$this->reschedule_note."','".$this->reminder."','".$this->notification."','".$this->lastmodify."','".$this->gc_event_id."')"); 		
			
			return $result; 
	 }
	 
	 
	  /**
     * Read all upcoming (future appointments) For Reminder Email & SMS Notification
     *
     * @return records as object
     */
	function read_all_upcoming_bookings_reminder_notification(){      	
			global $wpdb;
			
						
			$result = $wpdb->get_results("SELECT *  FROM  ".$wpdb->prefix."apt_bookings  WHERE booking_datetime > '".date_i18n('Y-m-d H:i:s')."' and  reminder='0' and booking_status='C'"); 		
		
			return $result;   
	} 
	 
	 
	 
	 /**
     * Read all upcoming (future appointments)
     *
     * @return records as object
     */
	function read_all_upcoming_bookings(){      	
		global $wpdb;
		$filterstring = '';
		
		if($this->location_id=='All' && $this->location_id!='0'){			
		}else{
		$filterstring = ' and location_id='.$this->location_id;
		}
		$result = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."apt_bookings WHERE business_owner_id=".$this->business_owner_id." AND booking_datetime > '".date_i18n('Y-m-d H:i:s')."' $filterstring"); 		
	
		return $result;   
	} 

	/**
     * Read all upcoming by provider ID
     *
     * @return records as object
     */
	function read_all_upcoming_bookings_by_provider_id(){      	
		
		global $wpdb;		
		$result = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."apt_bookings WHERE booking_datetime > '".date_i18n('Y-m-d H:i:s')."' and provider_id='".$this->provider_id."'"); 
		
		
		
		return $result;   
	}    	

	/**
     * Read one record of booking
     */
   function readOne_by_booking_id(){
		global $wpdb;
		$result = $wpdb->get_results("SELECT * FROM  ".$wpdb->prefix."apt_bookings 	WHERE id =".$this->booking_id."	LIMIT	0,1");
		
		foreach($result as $row){			
		  $this->booking_id = $row->id;			
		  $this->order_id = $row->order_id;			
		  $this->client_id = $row->client_id;			
		  $this->location_id = $row->location_id;			
		  $this->service_id = $row->service_id;			
		  $this->provider_id = $row->provider_id;			
		  $this->booking_datetime = $row->booking_datetime;			
		  $this->booking_endtime = $row->booking_endtime;			
		  $this->booking_status = $row->booking_status;		
		  $this->reject_reason = $row->reject_reason;		
		  $this->cancel_reason = $row->cancel_reason;		
		  $this->confirm_note = $row->confirm_note;		
		  $this->reschedule_note = $row->reschedule_note;		
		  $this->booking_price = $row->booking_price;		
		  $this->gc_event_id = $row->gc_event_id;
		 }
	}

	/**
     * Read one record of booking by order ID
     */
	function readOne_by_booking_order_id(){
					global $wpdb;
					$result = $wpdb->get_results("SELECT	* FROM  ".$wpdb->prefix."apt_bookings  WHERE order_id =".$this->order_id."
					LIMIT	
					0,1");

					foreach($result as $row){	
					  $this->booking_id = $row->id;			
					  $this->order_id = $row->order_id;			
					  $this->client_id = $row->client_id;			
					  $this->location_id = $row->location_id;			
					  $this->service_id = $row->service_id;			
					  $this->provider_id = $row->provider_id;			
					  $this->booking_datetime = $row->booking_datetime;			
					  $this->booking_endtime = $row->booking_endtime;			
					  $this->booking_status = $row->booking_status;		
					  $this->reject_reason = $row->reject_reason;		
					  $this->cancel_reason = $row->cancel_reason;		
					  $this->confirm_note = $row->confirm_note;		
					  $this->reschedule_note = $row->reschedule_note;		
					  $this->booking_price = $row->booking_price;	
					}
		}


	/**
     * Fetch last order ID
     */
	function get_last_order_id(){
		global $wpdb;
		$result = $wpdb->get_results("SELECT	order_id	FROM		
		".$wpdb->prefix."apt_bookings 
		order by order_id DESC	LIMIT 0,1");
	 
		foreach($result as $row){
			$this->last_order_id = $row->order_id;
		}
	}


	/**
     * Fetch orders by client ID
	 * @param $result - db record object
    */
	function get_order_ids_by_client_id(){
		global $wpdb;
		$result = $wpdb->get_results("SELECT	order_id FROM	".$wpdb->prefix."apt_bookings 
		Where client_id=".$this->client_id." and location_id=".$this->location_id);
		return $result;
	}


	/**
     * Fetch all bookings by client ID
    */
	function get_client_all_bookings_by_client_id(){
		global $wpdb;
		$result = $wpdb->get_results("SELECT	* FROM	".$wpdb->prefix."apt_bookings 
		Where client_id=".$this->client_id." and location_id=".$this->location_id);
	
		return $result;
	}


	/**
     * Fetch all bookings
	 * @param $startdate - Startdate
	 * @param $enddate - Enddate
	 * @return $result - db records object
    */
	function readAll($startdate='',$enddate='',$service_id='',$provider_id='',$reqpage=''){
				global $wpdb;
				//$filterquery = " where business_owner_id=".get_current_user_id()."";
				$filterquery = "";
				if($this->location_id=='All' && $reqpage=='Export' && $this->location_id!='0'){
					$filterquery .= " where location_id<>0";
				}else{
					$filterquery .= " where location_id=".$this->location_id;
				}	
				if($startdate!='' && $enddate!=''){
					$filterquery .= " and booking_datetime >= '$startdate 00:00:01' AND booking_datetime <= '$enddate 23:59:59'";
				}
				if($service_id!=''){
					$filterquery .= " and service_id='$service_id'";
				}
				if($provider_id!=''){
					$filterquery .= " and provider_id='$provider_id'";
				}
				$filterquery .= " and business_owner_id=".$this->business_owner_id."";	

				$result = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."apt_bookings $filterquery");
					
			return $result;
		
		 }

	/**
     * Get client orders with booking status
	 * @return $result - db records object
    */
	function get_distinct_bookings_of_client(){
		global $wpdb;
		$result = $wpdb->get_results("SELECT order_id,lastmodify,booking_status FROM  ".$wpdb->prefix."apt_bookings  
		Where client_id=".$this->client_id." GROUP BY order_id ORDER BY lastmodify DESC" );

		return $result;
	}

	
	/**
     * Fetch client bookings by Order id
	 * @return $result - db records object
    */
	function get_client_bookings_by_order_id(){
			global $wpdb;
			$result = $wpdb->get_results("SELECT * FROM  ".$wpdb->prefix."apt_bookings 
			Where client_id=".$this->client_id." AND order_id=".$this->order_id );
			return $result;
		}
		

	
	/**
     * Update booking status by booking ID
	 * @return $result - db records object
    */
	function update_booking_status_by_id(){
					global $wpdb;
					if($this->reject_reason!=''){
					$result = $wpdb->get_results("UPDATE  ".$wpdb->prefix."apt_bookings 
					SET booking_status='".$this->booking_status."',reject_reason='".$this->reject_reason."',notification='0',lastmodify='".date_i18n('Y-m-d H:i:s')."' WHERE id=".$this->booking_id );	
					}elseif($this->cancel_reason!=''){
					$result = $wpdb->get_results("UPDATE  ".$wpdb->prefix."apt_bookings 
					SET booking_status='".$this->booking_status."',cancel_reason='".$this->cancel_reason."',notification='0',lastmodify='".date_i18n('Y-m-d H:i:s')."' WHERE id=".$this->booking_id );	
					}else{
					$result = $wpdb->get_results("UPDATE  ".$wpdb->prefix."apt_bookings 
					SET booking_status='".$this->booking_status."',confirm_note='".$this->confirm_note."',notification='0',lastmodify='".date_i18n('Y-m-d H:i:s')."' WHERE id=".$this->booking_id );		
					}
					return $result;
	}

	
	
	/**
     * Update booking status by order ID
	 * @return $result - db records object
    */
	function update_booking_status_by_order_id_pid_bid(){
				global $wpdb;
				$queryString = "UPDATE  ".$wpdb->prefix."apt_bookings 
				SET booking_status='".$this->booking_status."',reject_reason='".$this->reject_reason."' ,notification='0',lastmodify='".date_i18n('Y-m-d H:i:s')."' WHERE order_id=".$this->order_id." AND provider_id=".$this->provider_id." AND id=".$this->booking_id;

				$result= $wpdb->query($queryString);

				return $result;
		}
		
	
	/**
	 * Get all bookings by Order ID
	 * @return $result - db records object
	*/
	 function get_all_bookings_by_order_id(){
				global $wpdb;
				$result = $wpdb->get_results("SELECT * FROM  ".$wpdb->prefix."apt_bookings 
				Where order_id=".$this->order_id );

				return $result;
		}
	/**
	 * Get all bookings by Booking ID
	 * @return $result - db records object
	*/
	function get_all_bookings_by_b_id(){
		global $wpdb;
		$result = $wpdb->get_results("SELECT * FROM  ".$wpdb->prefix."apt_bookings 
		Where id=".$this->booking_id );

		return $result;
	}
	
	/**
	 * Get all bookings by Order ID
	 * @return $result - db records object
	*/
	 function count_order_bookings(){
				global $wpdb;
				$result = $wpdb->get_results("SELECT count(id) as bookings FROM  ".$wpdb->prefix."apt_bookings 
				Where order_id=".$this->order_id );

				return $result[0]->bookings;
		}
	
	
	/**
	 * Update booking status by Client ID
	 * @return $result - db records object
	*/
	function update_booking_status_by_client(){
		global $wpdb;
		$queryString = "UPDATE  ".$wpdb->prefix."apt_bookings 
		SET booking_status='".$this->booking_status."',cancel_reason='".$this->cancel_reason."',notification='0',lastmodify='".date_i18n('Y-m-d H:i:s')."' WHERE order_id=".$this->order_id." AND client_id=".$this->client_id." AND id=".$this->booking_id;

		$result= $wpdb->query($queryString);
		return $result;
		}
		
		
	 /**
	 * Fetch all past booking appointments 
	 * @return $result - db records object
	*/
	 function read_all_past_bookings(){      	
			global $wpdb;	
			$result = $wpdb->get_results("SELECT	
			*  FROM  ".$wpdb->prefix."apt_bookings  WHERE
			booking_datetime < '".date_i18n('Y-m-d H:i:s')."'	
			"); 

		return $result;   
		}
		
		/**
		 * Fetch action pending bookings form past dates
		 * @return $result - db records object
		*/
		function read_all_past_bookings_action_pending(){      	
			global $wpdb;	
				$result = $wpdb->get_results("SELECT	
				*  FROM  ".$wpdb->prefix."apt_bookings  WHERE
				booking_datetime < '".date_i18n('Y-m-d H:i:s')."' AND booking_status='A' OR booking_datetime < '".date_i18n('Y-m-d H:i:s')."' AND booking_status='C'
				"); 
				return $result;   
			}
		
		/**
		 * Fetch action pending bookings for future dates
		 * @return $result - db records object
		*/
		function read_all_upcomming_bookings_action_pending(){      	
				global $wpdb;	
				$result = $wpdb->get_results("SELECT	
				*  FROM  ".$wpdb->prefix."apt_bookings  WHERE
				booking_datetime > '".date_i18n('Y-m-d H:i:s')."' AND booking_status='A'"); 
				return $result;   
		}

		

		/**
		 * Update appointment reminder email Status
		 * @return $result - db records object
		*/
		function update_booking_reminder_buffer_status(){
				global $wpdb;
				$queryString = "UPDATE  ".$wpdb->prefix."apt_bookings 
				SET reminder='".$this->reminder_buffer."' WHERE id=".$this->booking_id;

				$result= $wpdb->query($queryString);

				return $result;
		}
		

		/**
		 * Fetch all past booking by provider ID
		 * @return $result - db records object
		*/
		function read_all_past_bookings_by_provider_id(){      	
				global $wpdb;	
				$result = $wpdb->get_results("SELECT	
				*  FROM  ".$wpdb->prefix."apt_bookings  WHERE
				booking_datetime < '".date_i18n('Y-m-d H:i:s')."' and provider_id='".$this->provider_id."'	
				"); 

				return $result;   
			} 

		/**
		 * Fetch all past appointments by prodiver ID having pending actions 
		 * @return $result - db records object
		*/
		function read_all_past_bookings_action_pending_by_provider_id(){      	
				global $wpdb;	
				$result = $wpdb->get_results("SELECT	
				*  FROM  ".$wpdb->prefix."apt_bookings  WHERE
				provider_id=".$this->provider_id." AND  booking_datetime < '".date_i18n('Y-m-d H:i:s')."' AND booking_status='A' OR  provider_id=".$this->provider_id." AND  booking_datetime < '".date_i18n('Y-m-d H:i:s')."' AND booking_status='C'"); 
				return $result;   
			}
			
		/**
		 * Fetch all upcoming appointments by prodiver ID having pending actions 
		 * @return $result - db records object
		*/
		function read_all_upcomming_bookings_action_pending_by_provider_id(){      	
				global $wpdb;	
				$result = $wpdb->get_results("SELECT	
				*  FROM  ".$wpdb->prefix."apt_bookings  WHERE
				booking_datetime > '".date_i18n('Y-m-d H:i:s')."' AND booking_status='A' AND provider_id=".$this->provider_id.""); 
				return $result;   
			}					
			
		
		function delete_booking() {			
			global $wpdb;								
			$result = $wpdb->get_results("delete from ".$wpdb->prefix."apt_bookings  WHERE id=".$this->booking_id); 		
		}					
		
		function get_guest_users_booking_by_order_id() {
		
				global $wpdb;		
				$result = $wpdb->get_results("SELECT * FROM  ".$wpdb->prefix."apt_bookings 			Where order_id=".$this->order_id );		
			return $result;		
			
		}				
		
		function delete_guest_users_booking_by_order_id() {	
		
				global $wpdb;		
				$result = $wpdb->get_results("delete FROM  ".$wpdb->prefix."apt_bookings 			Where order_id=".$this->order_id );						
			return $result;			
			
		}
		
	   function delete_users_booking_by_order_id(){
			global $wpdb;
			$result = $wpdb->query("delete from ".$wpdb->prefix."apt_bookings where order_id=".$this->order_id);
			return $result;
		}
		
		function reschedule_appointment(){
				global $wpdb;
				$queryString = "UPDATE  ".$wpdb->prefix."apt_bookings 
				SET booking_datetime='".$this->booking_datetime."',booking_endtime='".$this->booking_endtime."',booking_status='RS',reschedule_note='".$this->reschedule_note."',reminder='0',notification='0',lastmodify='".date_i18n('Y-m-d H:i:s')."' WHERE id=".$this->booking_id;

				$result= $wpdb->query($queryString);

				return $result;
		}
		function get_register_client_last_order_id(){
			global $wpdb;
		
			$result = $wpdb->get_results("select order_id from ".$wpdb->prefix."apt_bookings where client_id=".$this->client_id." order by id desc limit 0,1");
			
			$this->order_id = $result[0]->order_id;			
		
		}
		function get_today_booking_and_earning(){
			global $wpdb;
			$sQLQ = "select count(b.id) as bookings,SUM(p.net_total) as earning 
				from ".$wpdb->prefix."apt_bookings as b,".$wpdb->prefix."apt_payments as p 
				where  b.location_id='".$this->location_id."' and p.order_id=b.order_id 
				and b.business_owner_id = ".get_current_user_id()."
				and b.lastmodify like '".date_i18n('Y-m-d')."%'";
			$result = $wpdb->get_results($sQLQ);
			
			return $result;
		}
		function get_week_booking_and_earning($weekstartdate,$weekenddatedate){
			global $wpdb;
			$QryB = "select count(b.id) as bookings,SUM(p.net_total) as earning 
			from ".$wpdb->prefix."apt_bookings as b,".$wpdb->prefix."apt_payments as p 
			where  b.location_id='".$this->location_id."' 
			and p.order_id=b.order_id 
			and b.business_owner_id = ".get_current_user_id()." 
			and b.lastmodify >='".$weekstartdate." 00:00:01' 
			and b.lastmodify <='".$weekenddatedate." 23:59:59'";
			$result = $wpdb->get_results($QryB);

	
			
			return $result;
		}
		function get_year_booking_and_earning(){
			global $wpdb;
			$QryS = "select count(b.id) as bookings,SUM(p.net_total) as earning 
			from ".$wpdb->prefix."apt_bookings as b,".$wpdb->prefix."apt_payments as p 
			where b.location_id='".$this->location_id."' and p.order_id=b.order_id 
			and b.business_owner_id = ".get_current_user_id()." 
			and b.lastmodify like'".date_i18n('Y-')."%'";
			$result = $wpdb->get_results($QryS);
			
	
			return $result;
		}
		function today_upcomming_appointments(){
			global $wpdb;
			$result = $wpdb->get_results("select * from ".$wpdb->prefix."apt_bookings where booking_datetime >='".date_i18n('Y-m-d H:i:s')."' and booking_datetime <='".date_i18n('Y-m-d')." 23:59:59' and location_id='".$this->location_id."' and booking_status='C'");
			
			return $result;
		}
		function readall_bookings_by_service_id(){
			global $wpdb;
			$result = $wpdb->get_results("select count(id) as bookings from ".$wpdb->prefix."apt_bookings where service_id ='".$this->service_id."'");
			
			return $result[0]->bookings;	
		}
		function readall_bookings_by_provider_id(){
			global $wpdb;
			$result = $wpdb->get_results("select count(id) as bookings from ".$wpdb->prefix."apt_bookings where provider_id ='".$this->provider_id."'");
			
			return $result[0]->bookings;	
		}
		function get_booking_by_latest_activity(){
			global $wpdb;
			
			$result = $wpdb->get_results("select * from ".$wpdb->prefix."apt_bookings where location_id ='".$this->location_id."' and business_owner_id=".get_current_user_id()." order by lastmodify desc limit 0,10");
			
			return $result;
		}
	
		function get_past_pending_quickaction_bookings(){
			global $wpdb;
			
			$result = $wpdb->get_results("select * from ".$wpdb->prefix."apt_bookings where location_id ='".$this->location_id."' and booking_status not in('CO','MN') and booking_datetime < '".date_i18n('Y-m-d H:i:s')."' order by booking_datetime desc");
			
			return $result;
		}
	
		
		function get_notifications_count(){
			global $wpdb;			
				$result = $wpdb->get_results("select count(id) as notifications from ".$wpdb->prefix."apt_bookings where location_id ='".$this->location_id."' and notification='0'");			
			return $result[0]->notifications;		
		}
		function get_notifications_bookings(){
			global $wpdb;			
			$result = $wpdb->get_results("select * from ".$wpdb->prefix."apt_bookings where location_id ='".$this->location_id."' and notification='0' order by lastmodify desc");			
			return $result;		
		}
		function remove_notifications_bookings(){
			global $wpdb;	
			if($this->booking_id=='All'){				
				$result = $wpdb->query("update ".$wpdb->prefix."apt_bookings set notification='1' where location_id='".$this->location_id."'");
			}else{
				$result = $wpdb->query("update ".$wpdb->prefix."apt_bookings set notification='1' where id='".$this->booking_id."'");		
			}			
			return $result;		
		}
		/*** insert booking addons ***/
		function insert_booking_addons()
		{
		 global $wpdb;
		 $insert_addons = $wpdb->query("INSERT INTO ".$wpdb->prefix."apt_booking_addons (`id`, `order_id`, `service_id`, `addons_service_id`, `associate_service_d`, `addons_service_rat`)values('','".$this->order_id."','".$this->service_id ."','".$this->addons_service_id."','".$this->associate_service_id."','".$this->addons_amount."')");
		}
		
		function select_addonsby_orderidand_serviceid()
		{
			global $wpdb;
			/* echo "select * from ".$wpdb->prefix."apt_booking_addons where order_id ='".$this->order_id."'";  */
			$result = $wpdb->get_results("select * from ".$wpdb->prefix."apt_booking_addons where order_id ='".$this->order_id."'");
			
			return $result;
			
		}
		
		function select_addonsby_addons_serviceid()
		{
			global $wpdb;
			$result = $wpdb->get_results("select addon_service_name from ".$wpdb->prefix."apt_services_addon where id ='".$this->addons_id."'");
			return $result;
		}
		
}
?>