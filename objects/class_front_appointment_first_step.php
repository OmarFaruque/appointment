<?php
class appointment_first_step
{
	/**     
	* Get Week of the month from date	 
	* @param date that need be checked	 
	* @return week number     
	*/
    public $business_owner_id;
    function get_week_of_month_by_date($date)
    {		
        $dmy    = explode('-', $date);
        $weekid = ceil(($dmy[2] + date_i18n("w", mktime(0, 0, 0, $dmy[1], 1, $dmy[0]))) / 7);
        if ($weekid == 6) {
            $idweek = $weekid - 1;
        } else {
            $idweek = $weekid;
        }	
        return $idweek;
    }
    
	/**     
	* print time slots	 
	* @param Day ID	 
	* @param Week ID	 
	* @param Time Interval default 10	 
	* @param Time provider ID	 
	* @return array time slots result     
	*/
    function appointment_time_slots($day_id = 1, $week_id = 1, $time_interval = 10, $provider_id)
    {
        global $wpdb;
		
        $results = array();
        $row     = $wpdb->get_results("SELECT daystart_time,dayend_time 
				FROM ".$wpdb->prefix."apt_schedule  WHERE weekday_id=" . $day_id . " 
				AND week_id=" . $week_id . " AND provider_id=" . $provider_id);										
        foreach ($row as $res) {
            $results['daystart_time'] = $res->daystart_time;
            $results['dayend_time']   = $res->dayend_time;
        }
		return $results;
    }
    
	
	function check_off_day($date, $provider_id)
    {
        global $wpdb;
		
        $row = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."apt_schedule_dayoffs WHERE provider_id=" . $provider_id . " AND off_date='" . $date . "'");
        if (sizeof($row) > 0) {
            return true;
        } else {
            return false;
        }
    }
    
	
	function get_day_breaks($week_id, $day_id, $provider_schedule_type, $provider_id)
    {
        global $wpdb;
        $return_arr = array();
		
		if ($provider_schedule_type == 'W') {
            $results = $wpdb->get_results("SELECT break_start, break_end FROM ".$wpdb->prefix."apt_schedule_breaks WHERE provider_id=" . $provider_id . " AND week_id='1' AND weekday_id='" . $day_id . "'");
        } else {
            $results = $wpdb->get_results("SELECT break_start, break_end FROM ".$wpdb->prefix."apt_schedule_breaks WHERE provider_id=" . $provider_id . " AND week_id='" . $week_id . "' 	AND weekday_id='" . $day_id . "'");
        }
        $counter = 0;
        foreach ($results as $row) {
            $return_arr[$counter]['break_start'] = $row->break_start;
            $return_arr[$counter]['break_end']   = $row->break_end;
            $counter++;
        }
		return $return_arr;
    }
    
	
	function get_already_booked_slots($provider_id, $selected_date)
    {
        global $wpdb;
        $return_arr           = array();
		$selected_date = date('Y-m-d',strtotime($selected_date));
        $results              = $wpdb->get_results("SELECT b.`booking_datetime`,s.`duration` FROM ".$wpdb->prefix."apt_bookings as b,".$wpdb->prefix."apt_services as s WHERE b.provider_id=" . $provider_id . " AND CAST(b.booking_datetime AS date) ='" . $selected_date . "' AND s.id = b.service_id AND (b.booking_status='A' OR b.booking_status='C')");
        $cur_time_interval    = get_option('appointment_booking_time_interval'.'_'.$this->business_owner_id);
        $booking_padding_time = get_option('appointment_booking_padding_time'.'_'.$this->business_owner_id);
        foreach ($results as $row) {
            $return_arr[]     = strtotime($row->booking_datetime);
            /* creating a temprory storage variable for booked datetime */
            $loop_tmp_storage = strtotime($row->booking_datetime);
            if ($row->duration > $cur_time_interval) {
                $times_greater = ceil($row->duration / $cur_time_interval - 1);
                for ($tg = 1; $tg <= $times_greater; $tg++) {
                    $return_arr[]     = strtotime("+$cur_time_interval minutes", $loop_tmp_storage);
                    $loop_tmp_storage = strtotime("+$cur_time_interval minutes", $loop_tmp_storage);
                }
            }
            if ($booking_padding_time != '') {
                $times_greater = ceil(($booking_padding_time-1) / $cur_time_interval);
                for ($tg = 1; $tg <= $times_greater; $tg++) {
                    $return_arr[]     = strtotime("+$cur_time_interval minutes", $loop_tmp_storage);
                    $loop_tmp_storage = strtotime("+$cur_time_interval minutes", $loop_tmp_storage);
                }
            }
        }
        return $return_arr;
    }
	
	/* Get Provider offtimes **/
	function get_provider_offtime($provider_id)
	{
       $return_arr = array();
         global $wpdb;
		$result=$wpdb->get_results("SELECT offtime_start,offtime_end FROM ".$wpdb->prefix."apt_schdeule_offtimes where provider_id = '".$provider_id."'");
		
		$counter = 0;
		foreach($result as $offtimes){
			$return_arr[$counter]['offtime_start'] = $offtimes->offtime_start;
			$return_arr[$counter]['offtime_end']   = $offtimes->offtime_end;
			$counter++;
		}
        return $return_arr;
    }
	
    /* A new function for new design */
    function get_day_time_slot_by_provider_id($provider_id, $provider_schedule_type = 'w', $cal_starting_date = '', $time_interval = 30)
    {

        
        $day_time_slots = array();
        
		/* showing time schedule for ONE DAY ONLY days */
        /* Get Week number of month for starting date (between 1 to 5) */
        if ($provider_schedule_type == 'w') {
            $week_id = 1;
        } else {
            $week_id = $this->get_week_of_month_by_date(date_i18n('Y-m-d', strtotime($cal_starting_date)));
        }
    
		/* if calendar starting date is missing then it will take starting date to current date */
        if ($cal_starting_date == '') {
            $day_id                 = date_i18n('N', strtotime(date_i18n('Y-m-d')));
            
			/*  add Date as heading of the day column */
            $day_time_slots['date'] = date_i18n('Y-m-d', strtotime(date_i18n('Y-m-d')));
        } else {
            $day_id                 = date_i18n('N', strtotime($cal_starting_date));
           
			/* add Date as heading of the day column */
            $day_time_slots['date'] = date_i18n('Y-m-d', strtotime($cal_starting_date));
        }
        
		/* check if the day is off day */
        $day_time_slots['off_day'] = $this->check_off_day($day_time_slots['date'], $provider_id);
       
		/* function return day start time and day end time of given provider */
        $time_intervals            = $this->appointment_time_slots($day_id, $week_id, $time_interval, $provider_id);


    
        /* calculating starting and end time of day into mintues */		
		if(isset($time_intervals['daystart_time'],$time_intervals['dayend_time'])){		
           // echo 'inside day start <br/>';
        $min_day_start_time        = (date_i18n('G', strtotime($time_intervals['daystart_time'])) * 60) + date_i18n('i',strtotime($time_intervals['daystart_time']));
        $min_day_end_time          = (date_i18n('G', strtotime($time_intervals['dayend_time'])) * 60) + date_i18n('i',strtotime($time_intervals['dayend_time']));
        $starting_min              = $min_day_start_time;
        /* check if selected date is today  if yes calculate current time's min to avoid past booking */
        $today                     = false;
        $conditional_min_mins      = 0;
        if (strtotime($day_time_slots['date']) == strtotime(date_i18n('Y-m-d'))) {
            $today                = true;
            /* total mins of current time */
            $conditional_min_mins = date_i18n('G') * 60 + date_i18n('i') ;
        } else {
            $today = false;
        }
        /* add minimum advance booking mins with starting mins for slots */
		$advance_bookingtime = get_option('appointment_minimum_advance_booking'.'_'.$this->business_owner_id);
		if(get_option('appointment_minimum_advance_booking'.'_'.$this->business_owner_id)<1440){
			$conditional_min_mins += $advance_bookingtime;
		}
		
		/*************New Added********************/
		/*********************************/
		$min_advnce_allow='Y';
		$advancemins='N';
		if($advance_bookingtime>=1440){
			$advancemins='Y';
			$currdatestr = strtotime(date('Y-m-d H:i:s'));
			$withadncebooktime = strtotime("+$advance_bookingtime minutes", $currdatestr);
			$withadncebookdate = date('Y-m-d',strtotime("+$advance_bookingtime minutes", $currdatestr));
			$daystarttimeofdate = strtotime(date($withadncebookdate.' '.$time_intervals['daystart_time']));
			$withadncetime = date('H:i:s',$withadncebooktime);
							
			if(strtotime($cal_starting_date)>strtotime($withadncebookdate)){
				$withadncetime = $time_intervals['daystart_time'];
			}
			if(strtotime($cal_starting_date)>strtotime($withadncebookdate)){

				if($withadncebooktime<$daystarttimeofdate){
					$min_day_start_time = (date('G', strtotime($time_intervals['daystart_time'])) * 60) + date('i',strtotime($time_intervals['daystart_time']));								
						$min_advnce_allow='Y';					
				}else{
				
					$min_day_start_time = (date('G', strtotime($withadncetime)) * 60) + date('i',strtotime($withadncetime));						
					if($min_day_start_time%$time_interval!=0){
						$extraminsadd =  $time_interval-($min_day_start_time%$time_interval);
						$min_day_start_time = $min_day_start_time+$extraminsadd;
					}
				
					$min_advnce_allow='Y';
				}
			}else{
				$min_advnce_allow='N';
			}
		}
		/*************New Added End********************/
		/*********************************/
		
		/* check offtimes of the day */
        $day_time_slots['offtimes'] = $this->get_provider_offtime($provider_id);
		/* check breaks of the day */
        $day_time_slots['breaks'] = $this->get_day_breaks($week_id, $day_id, $provider_schedule_type, $provider_id);
        /* check already booked timeslots */
        $day_time_slots['booked'] = $this->get_already_booked_slots($provider_id, $cal_starting_date);
		
        /* Converting time into slots based on given daystart time and dayend time */

 
        if ($time_intervals['daystart_time'] != '' && $time_intervals['dayend_time'] != '' && $min_advnce_allow=='Y') {
			
            while ($starting_min < $min_day_end_time) {
                if ($today) {
                    if ($starting_min > $conditional_min_mins) {
                        $day_time_slots['slots'][] = date_i18n('G:i:s', mktime(0, $starting_min, 0, 1, 1, date_i18n('Y')));
                    }
                } else {
                   $day_time_slots['slots'][] = date_i18n('G:i:s', mktime(0, $starting_min, 0, 1, 1, date_i18n('Y')));
                }
               $starting_min = $starting_min + $time_interval;
			  
            }
        } else {
            $day_time_slots['slots'] = array();
        }		
		
		  /* check Overlap booking option Enable then display timeslots */
		  if(get_option('appointment_dayclosing_overlap'.'_'.$this->business_owner_id) == "E"){
		   $day_time_slots['slots'][] = date_i18n('G:i:s', mktime(0, $starting_min, 0, 1, 1, date_i18n('Y')));
		  }
		}
		
		
		
        return $day_time_slots;
    }
    /* end of function */
	
	
	
	/* Function To get booking of Slot */
	function get_bookings_of_slot($datetime,$provider_id){
		global $wpdb;
		$result = $wpdb->get_results("select * from ".$wpdb->prefix."apt_bookings where provider_id=".$provider_id." and booking_datetime like '%".$datetime."%'");

		return $result;
	}
}
?>