<?php
class appointment_loyalty_points
{
    

    
	/* object properties */
    public $id;
    public $client_id;
    public $booking_id;
    public $balance;
    public $debit;
    public $credit;
    public $lastmodify;
 
    
	/**
     * create appointment Review table
     */ 
	function create_table() {
	global $wpdb;

	$table_name = $wpdb->prefix .'apt_loyalty_points';
	
	if( $wpdb->get_var( "show tables like '{$table_name}'" ) != $table_name ) {		
	
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `client_id` int(11) NOT NULL,
			  `booking_id` int(11) NOT NULL,
			  `balance` int(11) NOT NULL,
			  `debit` int(11) NOT NULL,
			  `credit` varchar(3) NOT NULL,
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
    function credit_debit_loyalty_points()
    {
        global $wpdb;
        $result = $wpdb->query("INSERT INTO  ".$wpdb->prefix."apt_loyalty_points 
                SET client_id='".$this->client_id."',booking_id='".$this->booking_id."',balance ='" . $this->balance . "' ,debit='" . $this->debit . "',credit='".$this->credit."'");
        
        return $result;
        
        
    }
	
	

	/**
	* Read one Review record
	* @return db record object
	*/
    function get_client_balance()
    {
        global $wpdb;
		
        $result = $wpdb->get_results("Select * From  ".$wpdb->prefix."apt_loyalty_points  WHERE client_id='" . $this->client_id . "' order by id desc limit 0,1");
		
		foreach($result as $row){			
			  $this->id = $row->id;			
			  $this->client_id = $row->client_id;			
			  $this->booking_id = $row->booking_id;			
			  $this->balance = $row->balance;			
			  $this->debit = $row->debit;			
			  $this->credit = $row->credit;			
			  $this->lastmodify = $row->lastmodify;			
		 }
		
      
    }
	
	/**
	* Read update Review record
	* @return $return - true on success
	*/
	function update()
    {
        global $wpdb;
        
        $return = $wpdb->query("UPDATE  ".$wpdb->prefix."apt_loyalty_points   SET rating ='" . $this->rating . "' ,description='" . $this->description . "',status='".$this->status."' WHERE id='".$this->id."'");
        
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
        $return = $wpdb->query("DELETE FROM  ".$wpdb->prefix."apt_loyalty_points  WHERE id =" . $this->id);
    }	
	
	/**
	* Read one Review record
	* @return db record object
	*/
    function readOne_by_booking_id()
    {
        global $wpdb;
        $return = $wpdb->get_results("Select id,booking_id,provider_id,client_id,rating,description,status From  ".$wpdb->prefix."apt_loyalty_points  WHERE booking_id='" . $this->booking_id . "'");
        return ($return);
    }
	
}
?>