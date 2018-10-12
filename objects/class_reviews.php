<?php
class appointment_reviews
{
    

    
	/* object properties */
    public $id;
    public $location_id;
    public $booking_id;
    public $provider_id;
    public $client_id;
    public $rating;
    public $description;
    public $status;
    public $lastmodify;
    public $business_owner_id;
 
    
	/**
     * create appointment Review table
     */ 
	function create_table() {
	global $wpdb;

	$table_name = $wpdb->prefix .'apt_reviews';
	
	if( $wpdb->get_var( "show tables like '{$table_name}'" ) != $table_name ) {		
	
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
              `location_id` int(11) NOT NULL,
              `business_owner_id` int(11) NOT NULL,
			  `booking_id` int(11) NOT NULL,
			  `provider_id` int(11) NOT NULL,
			  `client_id` int(11) NOT NULL,
			  `rating` varchar(3) NOT NULL,
			  `description` text NOT NULL,
			  `status` enum('A','P','R','H') NOT NULL COMMENT 'A=Active,P=Published,R=Rejected,H=Hidden',
			  `lastmodify` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
	
	
	dbDelta($sql);     
			}
	} 
	
	/**
	* Add Review
	* @return true - on success
	* @return false - on failure
	*/
    function create()
    {
        global $wpdb;
        $result = $wpdb->query("INSERT INTO  ".$wpdb->prefix."apt_reviews 
                SET location_id='".$this->location_id."',booking_id='".$this->booking_id."',provider_id ='" . $this->provider_id . "' ,client_id='" . $this->client_id . "',rating='".$this->rating."',description='" . $this->description."',status='". $this->status."'");
        
        return $result;
        
        
    }
	
	 /**
	* Read All Review
	* @return true - on success
	* @return false - on failure
	*/
	
    function readAll()
    {
        global $wpdb;
        $return = $wpdb->get_results("Select * From  ".$wpdb->prefix."apt_reviews where location_id='".$this->location_id."'");
        return ($return);
    }
	

	/**
	* Read one Review record
	* @return db record object
	*/
    function readOne()
    {
        global $wpdb;
        $return = $wpdb->get_results("Select booking_id,provider_id,client_id,rating,description,status From  ".$wpdb->prefix."apt_reviews  WHERE id='" . $this->id . "'");
        return ($return);
    }
	
	/**
	* Read update Review record
	* @return $return - true on success
	*/
	function update()
    {
        global $wpdb;
        
        $return = $wpdb->query("UPDATE  ".$wpdb->prefix."apt_reviews   SET rating ='" . $this->rating . "' ,description='" . $this->description . "',status='".$this->status."' WHERE id='".$this->id."'");
        
        if ($return) {
            return true;
        } else {
            return false;
        }
        
    }
	
	/**
	* Delete Review
	* @return $return - true on success
	*/
    function delete()
    {
        global $wpdb;
        $return = $wpdb->query("DELETE FROM  ".$wpdb->prefix."apt_reviews  WHERE id =" . $this->id);
    }	
	
	/**
	* Read one Review record
	* @return db record object
	*/
    function readOne_by_booking_id()
    {
        global $wpdb;
        $return = $wpdb->get_results("Select id,booking_id,provider_id,client_id,rating,description,status From  ".$wpdb->prefix."apt_reviews  WHERE booking_id='" . $this->booking_id . "'");
        return ($return);
    }
	
	/**
	* Read update Review Status-Publish/Hide
	* @return $return - true on success
	*/
	function update_review_status()
    {
        global $wpdb;
        
        $return = $wpdb->query("UPDATE  ".$wpdb->prefix."apt_reviews   SET status='".$this->status."' WHERE id='".$this->id."'");
        
        if ($return) {
            return true;
        } else {
            return false;
        }
        
    }
}
?>