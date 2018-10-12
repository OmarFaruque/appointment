<?php
class appointment_schedule
{
    

    /* Object property Identity */
    public $provider_id;
    /* Object property Week Day ID */
    public $weekday_id;
    /* Object Day Start Time */
    public $daystart_time;
    /* Object Day End Time */
    public $dayend_time;
    /* Object Off Day */
    public $off_day;
    /* Object Week ID */
    public $week_id;
    
    
	/**
     * create appointment schedule table
     */ 
	function create_table() {
	global $wpdb;

	$table_name = $wpdb->prefix .'apt_schedule';
	
	if( $wpdb->get_var( "show tables like '{$table_name}'" ) != $table_name ) {		
	
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `provider_id` int(11) NOT NULL,
					  `weekday_id` int(1) NOT NULL,
					  `daystart_time` time DEFAULT NULL,
					  `dayend_time` time DEFAULT NULL,
					  `off_day` enum('Y','N') NOT NULL DEFAULT 'N',
					  `week_id` int(1) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
	
	
	dbDelta($sql);     
			}
	}
   
	
	/**     
	* create Provider's Schedule      
	* @return boolean Create - true, Error - false   
	*/
    function create()
    {
        global $wpdb;
        //write query
        $daystart_time;
        $dayend_time;
        $result = $wpdb->query("INSERT INTO ".$wpdb->prefix."apt_schedule  SET provider_id ='" . $this->provider_id . "', weekday_id ='" . $this->weekday_id . "', daystart_time ='" . $this->daystart_time . "', dayend_time = '" . $this->dayend_time . "', off_day = '" . $this->off_day . "', week_id = '" . $this->week_id . "'");
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
   
   /**     
	* Read Provider's Schedule      
	* return object as schedule reocrds   
	*/
    function readAll()
    {
        global $wpdb;
        $queryString = "SELECT * FROM	".$wpdb->prefix."apt_schedule";
        $result      = $wpdb->get_results($queryString);
        return $result;
    }
	
    /**     
	* Read one record Schedule      
	*/
    function readOne()
    {
        global $wpdb;
        $row = $wpdb->get_results("SELECT
					weekday_id, daystart_time, dayend_time, off_day, week_id
				FROM ".$wpdb->prefix."apt_schedule	WHERE
				provider_id = " . $this->provider_id . " and weekday_id = " . $this->weekday_id . "
				LIMIT 0,1");
        if(isset($row[0]->weekday_id)){
			$this->weekday_id    = $row[0]->weekday_id;
			$this->daystart_time = $row[0]->daystart_time;
			$this->dayend_time   = $row[0]->dayend_time;
			$this->off_day       = $row[0]->off_day;
			$this->week_id       = $row[0]->week_id;
		}
    }
	
	/**     
	* Read one record Schedule      
	*/
    function readOne_new()
    {
        global $wpdb;
		
        $row = $wpdb->get_results("SELECT
					weekday_id, daystart_time, dayend_time, off_day, week_id
				FROM ".$wpdb->prefix."apt_schedule	WHERE
				provider_id = " . $this->provider_id . " and weekday_id = " . $this->weekday_id . " and week_id = " . $this->week_id . "
				LIMIT 0,1");
        if(isset($row[0]->weekday_id)){
			$this->weekday_id    = $row[0]->weekday_id;
			$this->daystart_time = $row[0]->daystart_time;
			$this->dayend_time   = $row[0]->dayend_time;
			$this->off_day       = $row[0]->off_day;
			$this->week_id       = $row[0]->week_id;
		}
    }
    
	/**		 
	* Read one record for Monthly Schedule Type		 
	*/
    function readOneMonthly()
    {
        global $wpdb;
        $row                 = $wpdb->get_results("SELECT weekday_id, daystart_time, dayend_time, off_day, week_id					FROM ".$wpdb->prefix."apt_schedule WHERE					provider_id = " . $this->provider_id . " and weekday_id = " . $this->weekday_id . " and week_id=" . $this->week_id . " 					LIMIT 0,1");
        $this->weekday_id    = $row[0]->weekday_id;
        $this->daystart_time = $row[0]->daystart_time;
        $this->dayend_time   = $row[0]->dayend_time;
        $this->off_day       = $row[0]->off_day;
        $this->week_id       = $row[0]->week_id;
    }
    
    /**		 
	* Read if the week day is off day in schedule		 
	* return Offday - true, working day - false		
	*/
    function get_offdays()
    {
        global $wpdb;
        $result = $wpdb->get_results("SELECT off_day FROM ".$wpdb->prefix."apt_schedule WHERE provider_id = " . $this->provider_id . " and weekday_id = '" . $this->weekday_id . "'");
        if (sizeof($result) > 0 && $result[0]->off_day == 'Y') {
            return true;
        } else {
            return false;
        }
    }
	
	function get_offdays_new()
    {
        global $wpdb;
        $result = $wpdb->get_results("SELECT off_day FROM ".$wpdb->prefix."apt_schedule WHERE provider_id = " . $this->provider_id . " and weekday_id = '" . $this->weekday_id . "' and week_id = '" . $this->week_id . "'");
        if (sizeof($result) > 0 && $result[0]->off_day == 'Y') {
            return true;
        } else {
            return false;
        }
    }
	
    /**		 
	* Check if Schedule exists for Particular Provider
	* return Offday - true, working day - false		
	*/
    function check_sechedule_exist_for_provider()
    {
        global $wpdb;
        $result = $wpdb->get_results("SELECT count(id) as ids
					FROM ".$wpdb->prefix."apt_schedule	WHERE
					provider_id = " . $this->provider_id . "
					LIMIT 0,1");
        return $result[0]->ids;
    }
    
	/**		 
	* Update Schedule		 
	* return Update - true, Error - false		
	*/
    function update()
    {
        global $wpdb;
        if ($this->daystart_time == 'Y') {
            $replaceStartTime = 'NULL';
            $replaceEndTime   = 'NULL';
        } else {
            $replaceStartTime = "'" . $this->daystart_time . "'";
            $replaceEndTime   = "'" . $this->dayend_time . "'";
        }
        $result = $wpdb->query("UPDATE ".$wpdb->prefix."apt_schedule SET daystart_time = " . $replaceStartTime . ",	dayend_time  = " . $replaceEndTime . ",	off_day  = '" . $this->off_day . "'
 WHERE provider_id = '" . $this->provider_id . "' and weekday_id = '" . $this->weekday_id . "' and week_id  = '" . $this->week_id . "'");
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    
    /**		 
	* Delete Schedule		 
	* return Delete - true, Error - false		
	*/
    function delete()
    {
        global $wpdb;
        $result = $wpdb->query("DELETE FROM ".$wpdb->prefix."apt_schedule WHERE id =" . $this->id);
        $result = $result;
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    
	/**		 
	* Get All Providers having with Saved Schedule 		 
	* return array with records		
	*/
    function get_providers_having_schedule()
    {
        global $wpdb;
        $return_array = array();
        $result       = $wpdb->get_results("SELECT	distinct(provider_id) FROM ".$wpdb->prefix."apt_schedule");
        foreach ($result as $providerid) {
            $return_array[] = $providerid->provider_id;
        }
        return $return_array;
    }
	
	/**		 
	* Get All Providers Current Week Working Hours 		 
	* return array with records		
	*/
    function get_provider_current_week_working_hrs()
    {
        global $wpdb;
        $result       = $wpdb->get_results("SELECT	* FROM ".$wpdb->prefix."apt_schedule where provider_id=".$this->provider_id." and week_id=".$this->week_id);
           return $result;
    }
	
	
	/* Delete Staff Schedule */
		function delete_staff_schedule(){
			global $wpdb;
			$result = $wpdb->query("Delete FROM ".$wpdb->prefix."apt_schedule where provider_id=".$this->provider_id);
			return $result;
		}
		
}
?>