<?php
class appointment_location
{

    
    /* object properties */
    public $id;
    public $location_title;
    public $description;
    public $image;
    public $email;
    public $phone;
    public $address;
    public $city;		
	public $state;
	public $zip;
	public $country;
	public $sortingvalue;
	public $status;
    public $position;
    public $business_owner_id;
    
    
	
	/**
     * create appointment services table
     */ 
	function create_table() {
	global $wpdb;

	$table_name = $wpdb->prefix .'apt_locations';
	
	if( $wpdb->get_var( "show tables like '{$table_name}'" ) != $table_name ) {		
	
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
                      `location_title` varchar(100) NOT NULL,
                      `business_owner_id` int(11) NOT NULL,
					  `description` varchar(500) NOT NULL,
					  `image` varchar(200) NOT NULL,
					  `email` varchar(100) NOT NULL,
					  `phone` varchar(100) NOT NULL,
					  `address` varchar(500) NOT NULL,
					  `city` varchar(100) NOT NULL,
					  `state` varchar(100) NOT NULL,
					  `zip` varchar(100) NOT NULL,
					  `country` varchar(100) NOT NULL,
					  `status` enum('E','D') NOT NULL COMMENT 'E=''Enable'',D=''Disable''',
					  `position` int(10) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
	
	
	dbDelta($sql);     
			}
	} 
	
		
	/* Create Location */
    function create()
    {
        global $wpdb;
        $stmt = $wpdb->query("INSERT INTO ".$wpdb->prefix."apt_locations (`id`, `location_title`, `business_owner_id`, `description`, `image`, `email`, `phone`, `address`, `city`, `state`, `zip`, `country`, `status`, `position`) values('','".$this->location_title."', '".get_current_user_id()."' ,'".$this->description."','".$this->image."','".$this->email."','".$this->phone."','".$this->address."','".$this->city."','".$this->state."','".$this->zip."','".$this->country."','E',0)");
	
				
        if ($stmt) {
            return true;
        } else {
            return false;
        }
    }
        
	/* Read All Location */
    function readAll()
    {
        global $wpdb;
        $queryString = "SELECT * FROM ".$wpdb->prefix."apt_locations where business_owner_id=".$this->business_owner_id." order by position ASC ";
                
        $stmt = $wpdb->get_results($queryString);
        
        return $stmt;
    }
    /* Read All Location */
    function readAll_enable_locations()
    {
        global $wpdb;
        $queryString = "SELECT * FROM ".$wpdb->prefix."apt_locations where business_owner_id='".$this->business_owner_id."' AND status='E' order by position ASC ";
                
        $stmt = $wpdb->get_results($queryString);
        
        return $stmt;
    }
	
	/* Get Sorted Locations Onlclick location city/state */
	function get_sorted_location(){
		global $wpdb;
		$queryString = "SELECT * FROM ".$wpdb->prefix."apt_locations";
		
		if(get_option('appointment_location_sortby'.'_'.$this->business_owner_id)=='city'){ 
			$queryString .=" where city='".$this->sortingvalue."'";
		}else{ 
			$queryString .=" where state='".$this->sortingvalue."'";
		}
		$queryString .=" and business_owner_id='".$this->business_owner_id."' order by position ASC";
		
		   $response= $wpdb->get_results($queryString);
        return $response;
	
	}
	
	/* Update Location Status Enable/Disbale */
	function update_location_status(){
		 global $wpdb;
        $response = $wpdb->query("update ".$wpdb->prefix."apt_locations set status='".$this->status."' where id=".$this->id);
        return $response;	
	
	
	}
	
	/* Delete Location*/
    function delete()
    {
        global $wpdb;
        $stmt   = $wpdb->query("DELETE FROM ".$wpdb->prefix."apt_locations  WHERE id =" . $this->id);
        $result = $stmt;
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
	
	/* Update Location Detail */
	function update()
    {
        global $wpdb;
        $stmt = $wpdb->query("UPDATE ".$wpdb->prefix."apt_locations 	SET	location_title='".$this->location_title."', description='".$this->description."',image='".$this->image."',email='".$this->email."',phone='".$this->phone."',address='".$this->address."',city='".$this->city."',state='".$this->state."',zip='".$this->zip."',country='".$this->country."' WHERE id = ".$this->id);
       
        if ($stmt) {
            return true;
        } else {
            return false;
        }
    }
	/* Sort Location Position */
	function sort_location_position(){
		global $wpdb;
			 $stmt = $wpdb->query("UPDATE ".$wpdb->prefix."apt_locations set position='".$this->position."' where id='".$this->id."'");
			 
			return $stmt;	
	}
	
    /* Count Locations */
    public function countAll()
    {
        
        global $wpdb;
        $stmt = $wpdb->get_results("SELECT id FROM ".$wpdb->prefix."apt_locations WHERE business_owner_id=".get_current_user_id()."");
        $num  = sizeof($stmt);
        return $num;
    }
	
	/* Get Single Location Detail */
	public function readOne()
    {   
        global $wpdb;
        $stmt = $wpdb->get_results("SELECT * FROM  ".$wpdb->prefix."apt_locations where id='".$this->id."'");
        return $stmt;
    }
	/* Remove Location Image */
	function remove_location_image()
    {
        global $wpdb;
        $stmt = $wpdb->query("UPDATE ".$wpdb->prefix."apt_locations SET	image='".$this->image."' WHERE id = ".$this->id);       
        if ($stmt) {
            return true;
        } else {
            return false;
        }
    }
	
		
	}
?>