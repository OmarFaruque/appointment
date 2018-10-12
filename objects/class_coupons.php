<?php
class appointment_coupons
{
    

    
	/* object properties */
    public $id;
    public $location_id;
    public $coupon_code;
    public $coupon_type;
    public $coupon_value;
    public $coupon_limit;
    public $coupon_createddate;
    public $coupon_expirydate;
    public $coupon_status;
    public $coupon_used;
    public $coupon_counter;
    public $lastmodify;
    public $service_id;
    public $business_owner_id;
	
    
	/**
     * create appointment coupons table
     */ 
	function create_table() {
	global $wpdb;

	$table_name = $wpdb->prefix .'apt_coupons';
	
	if( $wpdb->get_var( "show tables like '{$table_name}'" ) != $table_name ) {		
	
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `business_owner_id` int(11) NOT NULL,
			  `location_id` int(11) NOT NULL,
			  `coupon_code` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
			  `coupon_type` enum('P','F') COLLATE utf8_unicode_ci NOT NULL COMMENT 'A=Percentage, C=Flat',
			  `coupon_value` float DEFAULT NULL,
			  `coupon_limit` int(5) NOT NULL,
			  `coupon_used` int(5) NOT NULL,
			  `coupon_expires_on` datetime NOT NULL,
			  `lastmodify` datetime NOT NULL,
			  `coupon_status` enum('E','D') COLLATE utf8_unicode_ci NOT NULL COMMENT 'E=Enable,D=Disable',
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;";
	
	
	dbDelta($sql);     
			}
	} 
	
	/**
	* Generate coupon
	* @return true - on success
	* @return false - on failure
	*/
    function create()
    {
        global $wpdb;
        $return = $wpdb->query("INSERT INTO ".$wpdb->prefix."apt_coupons 
                SET business_owner_id=".$this->business_owner_id.", location_id='".$this->location_id."',coupon_code ='" . $this->coupon_code . "' ,coupon_type='" . $this->coupon_type . "',coupon_value=" . $this->coupon_value . ",coupon_limit=" . $this->coupon_limit . ",coupon_expires_on='" . $this->coupon_expirydate . "',coupon_status='E',lastmodify='".date_i18n('Y-m-d H:i:s')."'");
        $coupon_id = $wpdb->insert_id;
        return $coupon_id;
        
        
    }
	
	/**
	* Read All coupon
	* @return true - on success
	* @return false - on failure
	*/
	
    function readAll()
    {
        global $wpdb;
        $return = $wpdb->get_results("Select * From  ".$wpdb->prefix."apt_coupons where business_owner_id=".$this->business_owner_id." and location_id='".$this->location_id."'");
        return ($return);
    }
	

	/**
	* Read one coupon record
	* @return db record object
	*/
    function readOne()
    {
        global $wpdb;
        $return = $wpdb->get_results("Select coupon_type,coupon_value,coupon_limit,coupon_expires_on,service_id,coupon_status,coupon_used From  ".$wpdb->prefix."apt_coupons  WHERE coupon_code='" . $this->coupon_code . "'");
        return ($return);
    }
	
	/**
	* Updated on coupon used
	* @return $return - true on success
	*/
    function Update_coupon_used()
    {
        global $wpdb;
        $return = $wpdb->query("UPDATE  ".$wpdb->prefix."apt_coupons 	SET coupon_used=" . $this->coupon_used . " 	 	 WHERE coupon_code='" . $this->coupon_code . "'");
        return ($return);
    }
	
	/**
	* Updated coupon status by coupon id
	* @return $return - true on success
	*/
    function Update_coupon_status_by_coupon_id()
    {
        global $wpdb;
        $return = $wpdb->query("UPDATE  ".$wpdb->prefix."apt_coupons  SET coupon_status='" . $this->coupon_status . "' WHERE id='" . $this->id . "'");
        return ($return);
    }
	
	/**
	* Delete coupon
	* @return $return - true on success
	*/
    function delete()
    {
        global $wpdb;
        $return = $wpdb->query("DELETE FROM  ".$wpdb->prefix."apt_coupons  WHERE id =" . $this->id);
    }
	
	/**
	* Read one coupon record by id
	* @return $return - true on success
	*/
    function readOne_by_coupon_id()
    {
        global $wpdb;
        $return = $wpdb->get_results("Select * From  ".$wpdb->prefix."apt_coupons  WHERE id='" . $this->id . "'");
        return ($return);
    }
    
	/**
	* Read update coupon record
	* @return $return - true on success
	*/
	function update()
    {
        global $wpdb;
        
        $return = $wpdb->query("UPDATE  ".$wpdb->prefix."apt_coupons   SET coupon_code ='" . $this->coupon_code . "' ,coupon_type='" . $this->coupon_type . "',coupon_value=" . $this->coupon_value . ",coupon_limit=" . $this->coupon_limit . ",coupon_expires_on='" . $this->coupon_expirydate . "' WHERE id=" . $this->id . "");
        
        if ($return) {
            return true;
        } else {
            return false;
        }
        
    }
	
	
	/**
	* Check Coupon Exist Or Not
	* @return true - on success
	* @return false - on failure
	*/
	
    function apt_check_applied_coupon()
    {
		
        global $wpdb;
        $return = $wpdb->get_results("Select * From  ".$wpdb->prefix."apt_coupons where coupon_code='".$this->coupon_code."' and coupon_status='E' and coupon_limit<>coupon_used and coupon_expires_on >= '".date_i18n('Y-m-d')."'");
        return ($return);
    }
	
	/**
	* Updated on coupon used Value
	* @return $return - true on success
	*/
    function apt_update_coupon_used()
    {
        global $wpdb;
        $return = $wpdb->query("UPDATE  ".$wpdb->prefix."apt_coupons SET coupon_used=" . $this->coupon_used . " 	 	 WHERE id='" . $this->id . "'");
        return ($return);
    }
	
	
}
?>