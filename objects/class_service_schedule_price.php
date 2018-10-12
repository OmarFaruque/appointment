<?php
class appointment_service_schedule_price
{

    /* Object property Identity */
    public $id;
    /* Object property Provider's Identity */
    public $provider_id;
	 /* Object property Provider's Identity */
    public $service_id;
    /* Object property Week Identity */
    public $weekid;
    /* Object property Week Day Identity */
    public $weekdayid;
    /* Object property SSP Start Time */
    public $ssp_starttime;
    /* Object property SSP End Time */
    public $ssp_endtime; 
	/* Object property Offtime End Time */
    public $ssp_price;
   
   
	/**
     * create appointment schedule breakes table
     */ 
	function create_table() {
	global $wpdb;

	$table_name = $wpdb->prefix .'apt_service_schedule_price';
	
	if( $wpdb->get_var( "show tables like '{$table_name}'" ) != $table_name ) {		
	
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `provider_id` int(11) NOT NULL,
				  `service_id` int(11) NOT NULL,
				  `ssp_starttime` time NOT NULL,
				  `ssp_endtime` time NOT NULL,
				  `weekid` int(11) NOT NULL,
				  `weekdayid` int(11) NOT NULL,
				  `price` double NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
	dbDelta($sql);     
			}
	}
   
  
   
   /**     
	* create Service Schedule Price for provider      
	* return boolean Create - true, Error - false   
	*/
    function create()
    {
        global $wpdb;
        $result = $wpdb->query("INSERT INTO ".$wpdb->prefix."apt_service_schedule_price SET provider_id ='" . $this->provider_id . "',service_id ='" . $this->service_id . "',ssp_starttime ='" . $this->ssp_starttime . "', ssp_endtime = '" . $this->ssp_endtime . "', weekid = '" . $this->weekid . "', weekdayid ='" . $this->weekdayid . "', price = '" . $this->ssp_price . "'");
		
		$ssp_id = $wpdb->insert_id;
		return $ssp_id;
    }
	
	/**     
	* Update Break for provider's schedule      
	* return boolean Create - true, Error - false   
	*/
    function update()
    {
        global $wpdb;
        $result = $wpdb->query("Update ".$wpdb->prefix."apt_service_schedule_price SET  ssp_starttime ='" . $this->ssp_starttime . "', ssp_endtime = '" . $this->ssp_endtime . "',price = '" . $this->ssp_price . "' where id =".$this->id);
	  
		return $result;
    }
	
    /**     
	* Read Break from provider's schedule, for Specific week and weekday    
	* return object of Breaks   
	*/
    function readOne_ssp()
    {
        global $wpdb;
		
        $result = $wpdb->get_results("SELECT *	FROM ".$wpdb->prefix."apt_service_schedule_price	WHERE provider_id ='".$this->provider_id."' AND service_id = '".$this->service_id."' AND weekid='".$this->weekid ."' AND weekdayid='" . $this->weekdayid ."'");
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
        $results    = $wpdb->get_results("SELECT id,break_start,break_end FROM ".$wpdb->prefix."apt_schedule_breaks  WHERE provider_id='".$this->provider_id."' AND weekid='".$this->weekid ."' AND weekdayid='" . $this->weekdayid ."'");
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
    function delete()
    {
        global $wpdb;
        $result = $wpdb->query("delete FROM ".$wpdb->prefix."apt_service_schedule_price	WHERE id=".$this->id);
    }
    
}
?>