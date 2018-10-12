<?php
class appointment_service
{

    
    /* object properties */
    public $id;
    public $location_id;
    public $color_tag;
    public $service_title;
    public $image;
    public $service_category;
    public $duration;
    public $amount;
    public $offered_price;
    public $service_description;
    public $provider_id;		
	public $service_status;
	public $position;
	public $addon_title;
	public $addon_price;
	public $addon_id;
	public $addon_location_id;
	public $addon_service_id;
	public $addon_update_id;
	public $selected_location;
	public $selected_service_id;
    public $status;
	public $qty;
	public $rules;
	public $rate;
	public $business_owner_id;
	public $service_id;
    
	
	/**
     * create appointment services table
     */ 
	function create_table() {
	global $wpdb;

	$table_name = $wpdb->prefix .'apt_services';
	
	if( $wpdb->get_var( "show tables like '{$table_name}'" ) != $table_name ) {		
	
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `location_id` int(11) NOT NULL,
					  `business_owner_id` int(11) NOT NULL,
					  `color_tag` varchar(7) NOT NULL,
					  `service_title` varchar(100) NOT NULL,
					  `image` varchar(500) NOT NULL,
					  `category_id` int(11) NOT NULL,
					  `duration` int(7) NOT NULL,
					  `amount` double DEFAULT NULL,
					  `offered_price` varchar(250) DEFAULT NULL,
					  `service_description` text NOT NULL,
					  `service_status` enum('Y','N') NOT NULL COMMENT 'Y=Service Enable,N=Service Disable',
					  `position` int(11) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
	
	
	dbDelta($sql);     
			}
	} 
	
	/**
     * create addons services table
     */ 
	function create_table_addons() {
	global $wpdb;

	$table_name = $wpdb->prefix .'apt_services_addon';
	
	if( $wpdb->get_var( "show tables like '{$table_name}'" ) != $table_name ) {		
	
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_name."(
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`business_owner_id` int(11) NOT NULL,
			`service_id` int(11) NOT NULL,
			`addon_service_name` varchar(50) NOT NULL,
			`base_price` double NOT NULL,
			`maxqty` int(11) NOT NULL,
			`image` varchar(250) NOT NULL,
			`multipleqty` enum('Y','N') NOT NULL,
			`status` enum('E','D') NOT NULL DEFAULT 'E',
			`position` int(11) NOT NULL,
			`predefine_image` text NOT NULL,
			`predefine_image_title` text NOT NULL,
			`location_id` int(11) NOT NULL,
			PRIMARY KEY (`id`)
			)ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
	
	dbDelta($sql);     
			}
	}
	
	/**
     * create addons pricing rule table
     */ 
	function create_table_addon_pricing() {
	global $wpdb;

	$table_namesar = $wpdb->prefix .'apt_addon_service_rate';
	
	if( $wpdb->get_var( "show tables like '{$table_namesar}'" ) != $table_namesar ) {		
	
	$sqlsar = "CREATE TABLE IF NOT EXISTS ".$table_namesar." (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `business_owner_id` int(11) NOT NULL,
			  `addon_service_id` int(11) NOT NULL,
			  `unit` varchar(20) NOT NULL,
			  `rules` enum('E','G') NOT NULL,
			  `rate` double NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;";
	
	dbDelta($sqlsar);     
			}
	} 
	
	/**
     * create addons booking table
     */ 
	function create_table_addons_booking() {
	global $wpdb;

	$table_name = $wpdb->prefix .'apt_booking_addons';
	
	if( $wpdb->get_var( "show tables like '{$table_name}'" ) != $table_name ) {		
	
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_name."(
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`business_owner_id` int(11) NOT NULL,
			`order_id` bigint(2) NOT NULL,
			`service_id` int(11) NOT NULL,
			`addons_service_id`int(11) NOT NULL,
			`associate_service_d` text NOT NULL,
			`addons_service_rat` double NOT NULL,
			PRIMARY KEY (`id`)
			) ";
	
	dbDelta($sql);     
			}
	} 
	
	
	
	/**
     * create appointment services table
     */ 
	function create_table_provider_service() {
	global $wpdb;

	$table_name = $wpdb->prefix .'apt_providers_services';
	
	if( $wpdb->get_var( "show tables like '{$table_name}'" ) != $table_name ) {		
	
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `business_owner_id` int(11) NOT NULL,
					  `provider_id` int(11) NOT NULL,
					  `service_id` int(11) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
	
	
	dbDelta($sql);     
			}
	}
	
	/* Create New Service */
    function create()
    {
        global $wpdb;
        //write query
        $stmt = $wpdb->query("INSERT INTO  ".$wpdb->prefix."apt_services (
			id, 
			location_id, 
			business_owner_id,
			color_tag,service_title,
			image,
			category_id,
			duration,
			amount,
			offered_price,
			service_description,
			service_status,position) values(
			'',
			'".$this->location_id ."',
			'".$this->business_owner_id."',
			'".$this->color_tag ."',
			'".$this->service_title."',
			'".$this->image."',
			'".$this->service_category ."',
			'".$this->duration."',
			'".$this->amount."',
			'".$this->offered_price."',
			'".$this->service_description."','Y',0)");
				
		$serice_id = $wpdb->insert_id;
        return $serice_id;
    }
    
 	/* Get All Services */
    function readAll($reqpage='')
    {
        global $wpdb;
		if($reqpage=='Export' && $this->location_id=='All' && $this->location_id!='0'){
        $queryString = "SELECT * FROM ".$wpdb->prefix."apt_services where business_owner_id='".$this->business_owner_id."' ORDER BY	position ASC";
		}else{
		$queryString = "SELECT * FROM ".$wpdb->prefix."apt_services where business_owner_id='".$this->business_owner_id."' AND location_id='".$this->location_id."' ORDER BY position ASC";
		}
        $stmt = $wpdb->get_results($queryString); 
        return $stmt;
    }
	
	
	/* get business owner by server id */
	function get_businessowner_by_service_id(){
		global $wpdb;
		$serviceTbl = $wpdb->prefix . 'apt_services';
		$gbwids = $wpdb->get_row('SELECT `business_owner_id` FROM '.$serviceTbl.' WHERE `id`='.$this->service_id.'', OBJECT); 
		if($gbwids){
			return $gbwids->business_owner_id;
		}else{
			return 0;
		}
	}
	/* Update Service Detail */
	 function update()
    {
        global $wpdb;
        $stmt = $wpdb->query("UPDATE ".$wpdb->prefix."apt_services SET location_id='".$this->location_id."',color_tag ='" . $this->color_tag . "', service_title ='".$this->service_title . "',image='".$this->image."',category_id = '" . $this->service_category . "',duration = '" . $this->duration . "',amount  = '" . $this->amount . "',offered_price  = '" . $this->offered_price . "',service_description  = '" . $this->service_description . "' WHERE	id = " . $this->id);
		        
        // execute the query
        if ($stmt) {
            return true;
        } else {
            return false;
        }
    }
	
	/* Sort Service Position */
	function sort_service_position(){
		global $wpdb;
			 $stmt = $wpdb->query("UPDATE ".$wpdb->prefix."apt_services set position='".$this->position."' where id='".$this->id."'");
			 
			return $result;	
	}
	
	/* Count All Service */
    public function countAll()
    {
        
        global $wpdb;
        $stmt = $wpdb->get_results("SELECT id FROM  ".$wpdb->prefix."apt_services where business_owner_id='".$this->business_owner_id."' AND location_id='".$this->location_id."'");
        $num  = sizeof($stmt);
        return $num;
    }
	
	/* Get All Services By Category ID */
	function readAll_category_services(){
		global $wpdb;
        $stmt = $wpdb->get_results("SELECT * FROM  ".$wpdb->prefix."apt_services where business_owner_id='".$this->business_owner_id."' AND category_id =". $this->service_category." order by position asc");
        return $stmt;
	
	}
	
	/* Update Service Status */
	function update_service_status() {        
		global $wpdb;        
		$result = $wpdb->query("UPDATE  ".$wpdb->prefix."apt_services 	SET 	service_status ='" . $this->service_status . "'  	WHERE	id = " . $this->id );       
		if ($result) {            
			return true;        
			} else {            
			return false;        
		}    
	}
	
    function read_category_services()
    {
        global $wpdb;
        $stmt = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."apt_services WHERE category_id=" . $this->service_category . " AND service_status='Y' ORDER BY position ASC");
        return $stmt;
    }
	
	
    
    
    
    
    
    function readOne()
    {
        global $wpdb;
		$stmt = $wpdb->get_results("SELECT
						*
					FROM
						".$wpdb->prefix."apt_services 
					WHERE
						id =" . $this->id . " 
					LIMIT
						0,1");
        foreach ($stmt as $row) {
            $this->color_tag          = $row->color_tag;
            $this->location_id        = $row->location_id;
            $this->image    		  = $row->image;
            $this->service_title      = $row->service_title;
            $this->service_category   = $row->category_id;
            $this->duration           = $row->duration;
            $this->amount             = $row->amount;
            $this->offered_price      = $row->offered_price;
            $this->service_description = $row->service_description;
        }
    }
    
   
    
    /* delete the service */
    function delete()
    {
        global $wpdb;
        $stmt   = $wpdb->query("DELETE FROM ".$wpdb->prefix."apt_services  WHERE id =" . $this->id);
        $result = $stmt;
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    function get_service_description()
    {
        global $wpdb;
        $results                  = $wpdb->get_results("SELECT service_description FROM  ".$wpdb->prefix."apt_services  WHERE id =" . $this->id);
        $this->service_description = $results[0]->service_description;
    }
    
	
	/**
	* add providers for service ( provider / service relation) db table apt_providers_services 
	*/
    function link_service_providers()
    {
        global $wpdb;
		
        $stmt = $wpdb->query("INSERT INTO ".$wpdb->prefix."apt_providers_services SET  provider_id='" . $this->provider_id . "', service_id='" . $this->id . "'");

    }
    
	/** 
	* remove providers for service ( provider / service relation) db table apt_providers_services 
	*/
    function unlink_service_providers()
    {
        global $wpdb;
		
        $stmt = $wpdb->query("DELETE FROM ".$wpdb->prefix."apt_providers_services WHERE provider_id = '" . $this->provider_id . "' and service_id = '" . $this->id . "'");
	}
    
	/** 
	* Unlink service providers from a service by service ID
	*/
	function unlink_service_providers_by_service_id()
    {
        global $wpdb;
        $stmt = $wpdb->query("DELETE FROM ".$wpdb->prefix."apt_providers_services WHERE service_id = '" . $this->id . "'");
    }
	
	
	/** 
	* Check If any service is linked with any provider
	* @return true - yes, false- no
	*/
    function check_if_any_service_is_linked_with_any_provider()
    {
        global $wpdb;
        
        $res = $wpdb->get_results("select aps.provider_id as p_id from " . $wpdb->prefix . "users as u,".$wpdb->prefix."apt_providers_services as aps," . $wpdb->prefix . "usermeta as um 					where u.ID=aps.provider_id 					and u.ID=um.user_id					and um.meta_key='provider_status'					and um.meta_value='enable'");
        if (sizeof($res) > 0) {
            return true;
        } else {
            return false;
        }
    }			
	
			
	
	function readall_services_with_enable_status()    {        
		global $wpdb;        
		$queryString = "SELECT	id, color_tag, service_title, category_id, duration, amount,offered_price,service_description,service_status	FROM  ".$wpdb->prefix."apt_services 	WHERE	service_status='Y' and location_id='".$this->location_id."' ORDER BY service_title ASC LIMIT 0,1";		        
		$result = $wpdb->get_results($queryString);        
		return $result;   
		}

	function readall_services_of_provider()    {        

		global $wpdb;        
		$queryString = "SELECT	* FROM  ".$wpdb->prefix."apt_providers_services as s1 
		LEFT JOIN ".$wpdb->prefix."apt_services as s2  on (s2.id = s1.service_id)
		LEFT JOIN ".$wpdb->prefix."apt_categories as s3  on (s3.id = s2.category_id)
		WHERE	s1.provider_id=".$this->provider_id." and s2.service_status ='Y' order by s3.id asc";		        

		$result = $wpdb->get_results($queryString);        

		return $result;   
		}
	/* Remove Location Image */
	function remove_service_image()
    {
        global $wpdb;
        $stmt = $wpdb->query("UPDATE ".$wpdb->prefix."apt_services 	SET	image='".$this->image."' WHERE id = ".$this->id);       
        if ($stmt) {
            return true;
        } else {
            return false;
        }
    }

	/* Read Service Provider Link Status */	
	function service_provider_link_status(){
		global $wpdb;
        $stmt = $wpdb->get_results("SELECT * from ".$wpdb->prefix."apt_providers_services where provider_id = '" . $this->provider_id . "' and service_id = '" . $this->id . "'");       
        if ($stmt) {
            return 'Y';
        } else {
            return 'N';
        }	
	}
	function total_service_bookings(){
		global $wpdb;
		
		$stmt = $wpdb->get_results("SELECT count(id) as ids from ".$wpdb->prefix."apt_bookings where  service_id = '" . $this->id . "' and booking_datetime >= '".date_i18n('Y-m-d H:i:s')."'");   
		
		return $stmt[0]->ids;
	}
	
	function total_staff_services(){
		global $wpdb;
		
		$stmt = $wpdb->get_results("SELECT count(id) as ids from ".$wpdb->prefix."apt_bookings where  provider_id = '" . $this->provider_id . "'");   
		
		return $stmt[0]->ids;
	}
	function get_total_linked_staff_of_service(){
		global $wpdb;
		
		$stmt = $wpdb->get_results("SELECT count(id) as ids from ".$wpdb->prefix."apt_providers_services where  service_id = '" . $this->id . "'");   
		
		return $stmt[0]->ids;
	}
	
	
	function get_services_and_categories_having_services_by_location(){
		global $wpdb;
	
		$stmt = $wpdb->get_results("SELECT *,bds.id as sid FROM ".$wpdb->prefix."apt_services as bds join ".$wpdb->prefix."apt_categories as bdc on (bds.category_id=bdc.id) WHERE EXISTS( SELECT NULL FROM ".$wpdb->prefix."apt_services JOIN ".$wpdb->prefix."apt_categories ON ".$wpdb->prefix."apt_services.category_id=".$wpdb->prefix."apt_categories.id HAVING Count(distinct ".$wpdb->prefix."apt_services.category_id) > 0) and bds.location_id='".$this->location_id."' group by bds.id");
		
		return $stmt;
	}
	
	function check_service_in_location(){
		global $wpdb;
        $stmt = $wpdb->get_results("SELECT * FROM  ".$wpdb->prefix."apt_services where location_id='".$this->location_id."'");
        return $stmt;
	}
	
	function get_services(){
		global $wpdb;
		$stmt = $wpdb->get_results("SELECT
						*
					FROM
						".$wpdb->prefix."apt_services 
					WHERE
						id =" . $this->id . " 
					LIMIT
						0,1");
		
        return $stmt;
	}
	/******* for addon *******/
	function insert_addons()
	{
		global $wpdb;
		
		 $in_addons = $wpdb->query("INSERT INTO ".$wpdb->prefix."apt_services_addon (id,service_id,addon_service_name,base_price,maxqty,image,multipleqty,status,position,predefine_image,predefine_image_title,location_id)values('','".$this->addon_service_id."','".$this->addon_title ."','".$this->addon_price."','".$this->addon_maxqty_service."','".$this->image."','".$this->addon_multipleqty_status."','E','','','','".$this->addon_location_id."')");
	}
	function readAll_addons()
    {
        global $wpdb;
		$queryString = "SELECT * FROM ".$wpdb->prefix."apt_services_addon where location_id='".$this->location_id."' and service_id='".$this->id."' ORDER BY position ASC";
		$stmt = $wpdb->get_results($queryString); 
        return $stmt;
    } 
	/* ReadOne Addon Information */
	function readOne_addon()
    {
        global $wpdb;
		/* echo "SELECT * FROM ".$wpdb->prefix."apt_services_addon where id='".$this->addon_id."'"; */
		$queryString = "SELECT * FROM ".$wpdb->prefix."apt_services_addon where id='".$this->addon_id."'";
		$stmt = $wpdb->get_results($queryString); 
        return $stmt;
    }
	
	function addon_update()
    {
        global $wpdb;
		
		$update_addons = $wpdb->query("UPDATE ".$wpdb->prefix."apt_services_addon SET service_id = '".$this->addon_service_id . "',status = 'E',addon_service_name='".$this->addon_title."', base_price = '" . $this->addon_price . "',maxqty = '" . $this->addon_maxqty_service . "',image = '" . $this->image . "',multipleqty = '" . $this->addon_multipleqty_status . "',location_id = '" . $this->addon_location_id."'  WHERE	id = " . $this->addon_update_id);
       
    }
	
	function get_all_addons()
	{
		global $wpdb;
		
		$get_all_addons = $wpdb->get_results("SELECT * FROM  ".$wpdb->prefix."apt_services_addon where service_id = '".$this->selected_service_id."' AND status='E'");
		
        return $get_all_addons;
	}
	function addon_delete()
	{
		global $wpdb;
		$delete_addons   = $wpdb->query("DELETE FROM ".$wpdb->prefix."apt_services_addon  WHERE id =" . $this->id);
        $result = $delete_addons;
        if ($result) {
            return true;
        } else {
            return false;
        }
	}
	 public function insert_addonprice(){
		 global $wpdb;
		 
           $insert_addon_price = $wpdb->query("INSERT INTO ".$wpdb->prefix."apt_addon_service_rate (id,addon_service_id,unit,rules,rate)values('','".$this->addon_service_id."','".$this->unit ."','".$this->rules."','".$this->rate."')");
        }
		public function addon_qty_delete() {
			global $wpdb;
			$delete_qty_addons   = $wpdb->query("DELETE FROM ".$wpdb->prefix."apt_addon_service_rate  WHERE id =" . $this->id);
			$result = $delete_qty_addons;
		}
		public function readall_qty_addon() {
			global $wpdb;
			$select_qty_addon = "SELECT * FROM ".$wpdb->prefix."apt_addon_service_rate where addon_service_id='".$this->addon_service_id."'";
			$stmt = $wpdb->get_results($select_qty_addon); 
			return $stmt;
		}
		public function addon_qty_update(){
			global $wpdb;
			
		$update_addons_qty = $wpdb->query("UPDATE ".$wpdb->prefix."apt_addon_service_rate SET addon_service_id = '".$this->addon_service_id . "',unit = '".$this->unit . "',rules='".$this->rules."', rate = '" . $this->rate . "'  WHERE	id = " . $this->addon_update_id);
		}
		
		/* addon status update */
		function update_addon_status() {        
			global $wpdb;  

			$result = $wpdb->query("UPDATE  ".$wpdb->prefix."services_addon 	SET 	status ='" . $this->status . "'  	WHERE	id = " . $this->id );       
			if ($result) {            
				return true;        
				} else {            
				return false;        
			}    
		}
		
	
	}
?>