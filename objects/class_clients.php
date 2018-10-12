<?php
class appointment_clients{
	
	
    /* object properties */
    public $id;
	public $location_id;
	public $order_id;
    public $clientName;
    public $client_email;
	public $client_phone;
	public $client_personal_info;
	
	
	 /**
	 * Read All Clients records 
	 * @param $page- page number for pagination
	 * @param $from_record_num - record number from
	 * @param $records_per_page - page records limit
	 * @param $result - db record object
	 */
	 
	 function add_client_info(){
		 global $wpdb;

			$queryString ="INSERT INTO ".$wpdb->prefix."apt_order_client_info (`id`, `order_id`, `client_name`, `client_email`, `client_phone`, `client_personal_info`) VALUES ('', '".$this->order_id."', '".$this->clientName."', '".$this->client_email."', '".$this->client_phone."', '".$this->client_personal_info."')";
			$stmt = $wpdb->query($queryString);
		
			return $stmt;
	 }
	 
	 function readAll(){
			
			global $wpdb;

			$queryString ="SELECT  *  FROM  ".$wpdb->prefix."apt_order_client_info";
			$stmt = $wpdb->get_results($queryString);
		
			return $stmt;

		}
		
	
		function countAll(){
			global $wpdb;
			$results = $wpdb->get_results("SELECT  * from ".$wpdb->prefix."users as u,".$wpdb->prefix."usermeta as um WHERE um.meta_key ='".$wpdb->prefix."capabilities' AND um.meta_value LIKE '%apt_client%' AND u.ID=um.user_id");
			
			return ($results)?sizeof($results):0;
			
		}
	
		
		
	function get_client_info_by_order_id(){
			
			global $wpdb;

			$queryString ="SELECT

						*

					FROM

						".$wpdb->prefix."apt_order_client_info

					WHERE order_id=".$this->order_id;
					
			$stmt = $wpdb->get_results($queryString);

			return $stmt;

		}
		
	function get_registered_clients(){
		global $wpdb;
		
		if($this->location_id=='All' && $this->location_id!='0'){
			$results = $wpdb->get_results("SELECT  * from ".$wpdb->prefix."users as u 
			left join ".$wpdb->prefix."usermeta um1 on u.ID = um1.user_id  
			WHERE um1.meta_key ='".$wpdb->prefix."capabilities' AND um1.meta_value LIKE '%apt_client%' order by u.ID desc");
			
		}else{
			
			$results = $wpdb->get_results("SELECT  * from ".$wpdb->prefix."users as u 
			left join ".$wpdb->prefix."usermeta um1 on u.ID = um1.user_id  
			left join ".$wpdb->prefix."usermeta um2 on u.ID = um2.user_id WHERE um1.meta_key ='".$wpdb->prefix."capabilities' AND um1.meta_value LIKE '%apt_client%' AND um2.meta_key = 'apt_client_locations' AND um2.meta_value like '%#".$this->location_id."#%' order by u.ID desc");
		}
		
		

		return ($results)?$results:array();
	}		
	
	function get_existing_client_last_orderinfo(){
		global $wpdb;

		$results = $wpdb->get_results("SELECT  * from ".$wpdb->prefix."apt_order_client_info where client_email like '%".$this->client_email."%' order by id desc limit 0,1");

		return $results;
	
	}
	
	function delete_register_users_booking_by_id(){			
			global $wpdb;
			$result = $wpdb->get_results("delete t1,t2 FROM  ".$wpdb->base_prefix."users	as t1, ".$wpdb->base_prefix."usermeta  as t2 Where t1.ID=".$this->id." and t2.user_id=".$this->id );
			return $result;
	}

	function get_all_guest_users_orders() {		
		global $wpdb;
		
		if($this->location_id=='All' && $this->location_id!='0'){
			$result = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."apt_bookings as b left join ".$wpdb->prefix."apt_order_client_info as o on (b.order_id = o.order_id) where b.client_id=0 group by b.order_id order by b.order_id desc");
		}else{
			$result = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."apt_bookings as b left join ".$wpdb->prefix."apt_order_client_info as o on (b.order_id = o.order_id) where b.client_id=0 and b.location_id=".$this->location_id." group by b.order_id order by b.order_id desc");
		}
		return $result;		
	}
	
	
	function get_all_registered_clients_by_location_id($location_id){
		global $wpdb;
		
		$arr = array();
		$res = $wpdb->get_results("SELECT client_id from ".$wpdb->prefix."apt_bookings WHERE location_id ='".$location_id."' group by client_id");
		foreach($res as $val){
			array_push($arr, $val->client_id);
		}
		$implodedArr = implode(',', $arr);
		$results = (count($arr)>0)?$wpdb->get_results("SELECT u.* from ".$wpdb->prefix."users as u, ".$wpdb->prefix."usermeta as um1 WHERE um1.meta_key ='".$wpdb->prefix."capabilities' AND um1.meta_value LIKE '%apt_client%' AND u.ID IN(".$implodedArr.") group by u.ID order by u.ID desc"):array();
		return $results;
	}
}