<?php
class appointment_schedule_breaks
{

    /* Object property Identity */
    public $id;
    /* Object property Provider's Identity */
    public $provider_id;
    /* Object property Week Identity */
    public $week_id;
    /* Object property Week Day Identity */
    public $weekday_id;
    /* Object property Break Start Time */
    public $break_start;
    /* Object property Break End Time */
    public $break_end; 
	/* Object property Offtime Identity */
    public $offtime_id;
	/* Object property Offtime Start Time */
    public $offtime_start;
	/* Object property Offtime End Time */
    public $offtime_end;
   
   
	/**
     * create appointment schedule breakes table
     */ 
	function create_table() {
	global $wpdb;

	$table_name = $wpdb->prefix .'apt_schedule_breaks';
	
	if( $wpdb->get_var( "show tables like '{$table_name}'" ) != $table_name ) {		
	
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `provider_id` int(11) NOT NULL,
				  `weekday_id` int(1) NOT NULL,
				  `week_id` int(1) NOT NULL,
				  `break_start` time NOT NULL,
				  `break_end` time NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
	
	
	dbDelta($sql);     
			}
	}
   
	/**
     * create appointment schedule offtimes table
     */ 
	function create_table_offtimes() {
	global $wpdb;

	$table_name = $wpdb->prefix .'apt_schdeule_offtimes';
	
	if( $wpdb->get_var( "show tables like '{$table_name}'" ) != $table_name ) {		
	
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `provider_id` int(11) NOT NULL,
				  `offtime_start` datetime NOT NULL,
				  `offtime_end` datetime NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
	
	dbDelta($sql);     
			}
	}
   
   
   /**     
	* create Break for provider's schedule      
	* return boolean Create - true, Error - false   
	*/
    function create()
    {
        global $wpdb;
        $result = $wpdb->query("INSERT INTO ".$wpdb->prefix."apt_schedule_breaks SET provider_id ='" . $this->provider_id . "', weekday_id ='" . $this->weekday_id . "', break_start ='" . $this->break_start . "', break_end = '" . $this->break_end . "', week_id = '" . $this->week_id . "'");
	   $break_id = $wpdb->insert_id;
		return $break_id;
    }
	
	/**     
	* Update Break for provider's schedule      
	* return boolean Create - true, Error - false   
	*/
    function update()
    {
        global $wpdb;
        $result = $wpdb->query("Update ".$wpdb->prefix."apt_schedule_breaks SET  break_start ='" . $this->break_start . "', break_end = '" . $this->break_end . "' where id =".$this->id);
	  
		return $result;
    }
	
    /**     
	* Read Break from provider's schedule, for Specific week and weekday    
	* return object of Breaks   
	*/
    function read_breakinfo()
    {
        global $wpdb;
        $result = $wpdb->get_results("SELECT *	FROM ".$wpdb->prefix."apt_schedule_breaks	WHERE provider_id = " . $this->provider_id . " and week_id = " . $this->week_id . " and weekday_id= " . $this->weekday_id);
        return $result;
    }
	
    /**     
	* Read Break from provider's schedule, for Specific week and weekday 
	* @return $return_arr array of their break start and ending time    
	*/
    function read_day_breaks()
    {
        global $wpdb;
        $return_arr = array();
        $results    = $wpdb->get_results("SELECT id,break_start,break_end FROM ".$wpdb->prefix."apt_schedule_breaks  WHERE provider_id=" . $this->provider_id . " 	AND week_id='" . $this->week_id . "' AND weekday_id='" . $this->weekday_id . "'");
        $counter    = 0;
        foreach ($results as $row) {
            $return_arr[$counter]['break_id'] = $row->id;
            $return_arr[$counter]['break_start'] = $row->break_start;
            $return_arr[$counter]['break_end']   = $row->break_end;
            $counter++;
        }
        return $return_arr;
    }
	
    /**     
	* Delete a break from schedule of provider 
	*/
    function delete_break()
    {
        global $wpdb;
        $result = $wpdb->query("delete FROM ".$wpdb->prefix."apt_schedule_breaks	WHERE id=" . $this->id );
    }
    
	
/********************************** Schedule Offtime Module Functions************************************/
	 /**     
	* create Offtime for provider's schedule      
	* return Offtime id  
	*/
    function create_offtime()
    {
        global $wpdb;
        $result = $wpdb->query("INSERT INTO ".$wpdb->prefix."apt_schdeule_offtimes SET provider_id ='" . $this->provider_id . "', offtime_start ='" . $this->offtime_start . "', offtime_end ='" . $this->offtime_end . "'");
		$offtime_id = $wpdb->insert_id;
		return $offtime_id;
    }
		
	/**     
	* Read Offtime from provider's schedule, for Specific week and weekday    
	* return object of Breaks   
	*/
    function read_offtime()
    {
        global $wpdb;
        $result = $wpdb->get_results("SELECT *	FROM ".$wpdb->prefix."apt_schdeule_offtimes	WHERE provider_id = " . $this->provider_id);
        return $result;
    }
	
	 /**     
	* Delete a Offtime from schedule of provider 
	*/
    function delete_offtime()
    {
        global $wpdb;
        $result = $wpdb->query("delete FROM ".$wpdb->prefix."apt_schdeule_offtimes	WHERE id=" . $this->offtime_id );
    }
	
	/**     
	* Read Offtime from provider's schedule, for Specific week and weekday 
	* @return $return_arr array of their offtime start and offtime time    
	*/
    function read_day_offtimes()
    {
        global $wpdb;
        $return_arr = array();
        $results    = $wpdb->get_results("SELECT id,offtime_start,offtime_end FROM ".$wpdb->prefix."apt_schdeule_offtimes  WHERE provider_id=" . $this->provider_id);
        $counter    = 0;
        foreach ($results as $row) {
            $return_arr[$counter]['offtime_id'] = $row->id;
            $return_arr[$counter]['offtime_start'] = $row->offtime_start;
            $return_arr[$counter]['offtime_end']   = $row->offtime_end;
            $counter++;
        }
        return $return_arr;
    }
}
?>