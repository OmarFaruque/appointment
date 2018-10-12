<?php 
class appointment_order {    


	/* Object property Order Identity */
	public $order_id;
	
	/* Object property Client Name */
	public $client_name;
	
	/* Object property Client Email */
    public $client_email;
	
	/* Object property Client Phone Number */
    public $client_phone;
	
	/* Object property Personal Info */
	public $client_personal_info;
	
	/* Business owner id */ 
	public $business_owner_id;

 	
	
	
	 /**
     * create appointment client order info table
     */ 
	function create_table() {
	global $wpdb;

	$table_name = $wpdb->prefix .'apt_order_client_info';
	
	if( $wpdb->get_var( "show tables like '{$table_name}'" ) != $table_name ) {		
	
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `business_owner_id` int(11) NOT NULL,
				  `order_id` int(11) NOT NULL,
				  `client_name` varchar(255) NOT NULL,
				  `client_email` varchar(100) NOT NULL,
				  `client_phone` varchar(12) NOT NULL,
				  `client_personal_info` text NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1" ;
	
	
	dbDelta($sql);     
			}
	} 
	
	/**
     * Read One Record by Order ID
     */
	function readOne_by_order_id(){
	
		global $wpdb;
		$stmt = $wpdb->get_results("SELECT	* FROM   ".$wpdb->prefix."apt_order_client_info	WHERE order_id =".$this->order_id."
		LIMIT	
		0,1");				 	
		foreach($stmt as $row){			
		  $this->order_id = $row->order_id;			
		  $this->client_name = $row->client_name;			
		  $this->client_email = $row->client_email;			
		  $this->client_phone = $row->client_phone;			
		  $this->client_personal_info = $row->client_personal_info;			
		 }
		
	}	
	/* Get All Guest User Orders */
	function get_all_guest_users_orders($page='', $from_record_num='', $records_per_page='', $orderby="") {		
		global $wpdb;
		$queryString = "SELECT * FROM ".$wpdb->prefix."apt_bookings as b left join ".$wpdb->prefix."apt_order_client_info as o on (b.order_id = o.order_id) where b.client_id=0 group by b.order_id ";
				
		if($orderby!='') {
				$queryString .= " Order by b.$orderby ";
			}	
			
			$queryString .= "LIMIT {$from_record_num}, {$records_per_page}";		
			
							
				
				$stmt = $wpdb->get_results($queryString);
		return $stmt;		
	}
	/* Count Guest Users*/
	function countAll_guest_users() {		
		global $wpdb;
		$stmt = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."apt_bookings as b left join ".$wpdb->prefix."apt_order_client_info as o on (b.order_id = o.order_id) where b.client_id=0 group by b.order_id");
				
		return sizeof($stmt);		
	}
	


	/* Guest User Info By  Order Id */
	function get_guest_users_record_with_order_id() {
		global $wpdb;
		$stmt = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."apt_bookings as b left join ".$wpdb->prefix."apt_order_client_info as o on (b.order_id = o.order_id) where b.client_id=0 and b.order_id=".$this->order_id);				
		return $stmt;		
	}			

	/*Get author id using order ID*/
	function get_author_id_by_order_id(){
		global $wpdb;
		$thisTable = $wpdb->prefix . 'apt_order_client_info';
		$getAuthorID = $wpdb->get_row('SELECT `business_owner_id` FROM '.$thisTable.' WHERE order_id='.$this->order_id.'', OBJECT);
		return $getAuthorID->business_owner_id;
	}
	/* Delete Guest User By Order ID */
	function delete_order_client_info_by_order_id() {			
	global $wpdb;								
		$result = $wpdb->query("delete FROM  ".$wpdb->prefix."apt_order_client_info Where order_id=".$this->order_id );	
									 							 				
			return $result;	
	}
	
}
?>