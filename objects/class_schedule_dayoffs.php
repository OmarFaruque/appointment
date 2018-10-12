<?php
class appointment_schedule_offdays
{
 
	/* Object property Identity */
    public $id;
    /* Object property Provider Identity */
    public $provider_id;
    /* Object property Off day ( holiday date ) */
    public $off_date;

	public $off_year_month;
	

   
   
   /**
     * create appointment schedule offdays table
     */ 
	function create_table() {
	global $wpdb;

	$table_name = $wpdb->prefix .'apt_schedule_dayoffs';

	if( $wpdb->get_var( "show tables like '".$table_name."'" ) != $table_name ) {		
	
	$sql =	"CREATE TABLE IF NOT EXISTS ".$table_name." (
			 `id` int(11) NOT NULL AUTO_INCREMENT,
			 `provider_id` int(11) NOT NULL,
			 `off_date` date NOT NULL,
			 `lastmodify` varchar(200) NOT NULL,  
			 `status` int(1) NOT NULL,
			 PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
	
	
	dbDelta($sql);     
			}
	}
   
   
   /**     
	* create holiday (off day) for provider's schedule  
	* return boolean Create - true, Error - false    
	*/
    function create()
    {
        global $wpdb;
        $result = $wpdb->query("INSERT INTO ".$wpdb->prefix."apt_schedule_dayoffs  SET provider_id ='" . $this->provider_id . "', off_date ='" . $this->off_date . "'");
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
	
	/**
	* add full month off 
	* return boolean Create - true, Error - false 
	*/
	function create_monthoff() {
		 global $wpdb;
        
		$ym= explode("-",$this->off_year_month);
		$year = $ym[0];
		$month = $ym[1];
		$month_size='';
		if($month==1 || $month==3  || $month==5|| $month==7|| $month==8|| $month==10|| $month==31) { $month_size=31; }
		if($month==4 || $month==6  || $month==9|| $month==11) { $month_size=30; }
		if($month==2) { if($year%4==0) {$month_size=29; } else { $month_size=28; }}
		
		for($i=1;$i<=$month_size;$i++) {
			$offdate = $this->off_year_month.'-'.$i;
			$result = $wpdb->query("INSERT INTO ".$wpdb->prefix."apt_schedule_dayoffs  SET provider_id ='" . $this->provider_id . "', off_date ='" . $offdate . "'");
		}
        		
		if ($result) {
            return true;
        } else {
            return false;
        }
	}
	
	function delete_monthoff() {
		 global $wpdb;
        
		$ym= explode("-",$this->off_year_month);
		$year = $ym[0];
		$month = $ym[1];
		$month_size='';
		if($month==1 || $month==3  || $month==5|| $month==7|| $month==8|| $month==10|| $month==31) { $month_size=31; }
		if($month==4 || $month==6  || $month==9|| $month==11) { $month_size=30; }
		if($month==2) { if($year%4==0) {$month_size=29; } else { $month_size=28; }}
		
		
		for($i=1;$i<=$month_size;$i++) {
			$offdate = $this->off_year_month.'-'.$i;
			 $result = $wpdb->get_results("delete FROM  ".$wpdb->prefix."apt_schedule_dayoffs WHERE	provider_id = '" . $this->provider_id . "' and off_date = '" . $offdate . "'");
		}
        		
		if ($result) {
            return true;
        } else {
            return false;
        }
	}
	
	function check_full_month_off() {
		
		global $wpdb;
        
		$ym= explode("-",$this->off_year_month);
		$year = $ym[0];
		$month = $ym[1];
		$fullMonthSelected =  true;		
		$month_size='';
		if($month==1 || $month==3  || $month==5|| $month==7|| $month==8|| $month==10|| $month==12) { $month_size=31; }
		if($month==4 || $month==6  || $month==9|| $month==11) { $month_size=30; }
		if($month==2) { if($year%4==0) {$month_size=29; } else { $month_size=28; }}
		echo "Here";
		for($i=1;$i<=$month_size;$i++) {
			$offdate = $this->off_year_month.'-'.$i;
			
			$result = $wpdb->get_results("select id FROM  ".$wpdb->prefix."apt_schedule_dayoffs WHERE	provider_id = '" . $this->provider_id . "' and off_date = '" . $offdate . "'");
			 if(sizeof($result)>0) { 
				
			 } else {
				 $fullMonthSelected = false;
			 }
			 
		}

		return $fullMonthSelected;
		
	}
	
	
	
	
	
    
	/**     
	* Read all Holidays of a Provider's schedule 
	* return list of off days    
	*/
    function read_all_offs_by_provider()
    {
        global $wpdb;
        $result = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."apt_schedule_dayoffs  WHERE provider_id = " . $this->provider_id . " order by off_date DESC	");
        return $result;
    }
    
	/**		 
	* Delete off day 		
	*/
    function delete_offday()
    {
        global $wpdb;
        $result = $wpdb->get_results("delete FROM  ".$wpdb->prefix."apt_schedule_dayoffs WHERE	provider_id = '" . $this->provider_id . "' and off_date = '" . $this->off_date . "'");
    }
}
?>