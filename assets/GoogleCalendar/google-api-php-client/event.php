<?php
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] ); 
 	if( file_exists( $parse_uri[0] . 'wp-load.php' ) ){
		require_once( $parse_uri[0] . 'wp-load.php' );
	}
/* error_reporting(E_ALL);
ini_set('display_errors', 1); */

require_once 'google-api-php-client/src/Google_Client.php';
require_once 'google-api-php-client/src/contrib/Google_CalendarService.php';

$provider = new appointup_provider();
$appointupbj = new appointup_first_step();
$cdate = $_GET['cdate'];
	function extract_unit($string, $start, $end){
		$pos = stripos($string, $start);	 
		$str = substr($string, $pos);	 
		$str_two = substr($str, strlen($start));	 
		$second_pos = stripos($str_two, $end);	 
		$str_three = substr($str_two, 0, $second_pos);	 
		$unit = trim($str_three);	 
		return $unit;
	}

	
	
	

function get_adminevents(){
				
				$GcclientID = get_option('apt_gc_client_id');
				$GcclientSecret = get_option('apt_gc_client_secret');
				$GcEDvalue = get_option('apt_gc_status');
				
				
				$timeMin = date_i18n('Y-m-d').'T00:00:00Z';
				$calmaxdays = get_option('appointment_maximum_advance_booking');
				$maxdays=str_replace("D","",$calmaxdays);
				$timeMax = date_i18n('Y-m-d', strtotime('+'.$maxdays.' day', strtotime(date_i18n('Y-m-d')))).'T00:00:00Z';	
					$client = new Google_Client();
					$client->setApplicationName("Appointment Google Calender");
					$client->setClientId($GcclientID);
					$client->setClientSecret($GcclientSecret);
					$client->setRedirectUri(get_option('apt_gc_admin_url'));
					$client->setDeveloperKey($GcclientID);
					$client->setScopes( 'https://www.googleapis.com/auth/calendar' );
					$client->setAccessType('offline');
					$cal = new Google_CalendarService($client);	
					$accesstoken = json_decode(get_option('appointup_gc_google_data'));
					$client->setAccessToken(get_option('appointup_gc_google_data'));
			
					if (get_option('appointup_gc_google_data')) {
						if ($client->isAccessTokenExpired()) {
							$client->refreshToken($accesstoken->refresh_token);
						}
					}
					
					
					if ($client->getAccessToken()){
						$calendarId=get_option('appointup_gc_id');
						$allevents = $cal->events->listEvents($calendarId,array(
						'singleEvents' => true,
						'orderBy'      => 'startTime',
						'timeMin'      => $timeMin,
						'timeMax' 		   => $timeMax,
						'maxResults'   => 100,
            ) );	
				    }		
				return $allevents ;	
}

//$adminsevents = get_adminevents();
$eventinfo_admin = array();
/*	foreach($adminsevents['items'] as $admin_single_event){
				$eventtitleA = $admin_single_event['summary'];
				$eventdescription = $admin_single_event['description'];
				$servicenameA = extract_unit($admin_single_event['description'], 'Service =', 'Name =');
				$clientNameA = extract_unit($admin_single_event['description'], 'Name =', 'Email =');
				$clientEmailA = extract_unit($admin_single_event['description'], 'Email =', 'Phone =');
				$clientPhoneA = explode('Phone =',$admin_single_event['description']);
				$AclientPhone = '';
				if(isset($clientPhoneA[1])){	$AclientPhone = $clientPhoneA[1];		}
				$eventstartdtA = $admin_single_event['start']['dateTime'];
				$eventenddtA = $admin_single_event['end']['dateTime'];
			$eventinfo_admin[] =array('title'=>$eventtitleA,'service_name'=>$servicenameA,'client_name'=>$clientNameA,'client_email'=>$clientEmailA,'client_phone'=>$AclientPhone,'start'=>$eventstartdtA,'end'=>$eventenddtA,'event_description'=>$eventdescription,'timezone'=>$adminsevents['timeZone']);
	} */
$eventinfo = array();
function get_providerevents($provider_id,$calendarId,$provider_access_token,$cdate){
				switch_to_blog(1);
						$GcclientID = get_option('appointup_gc_client_id');
						$GcclientSecret = get_option('appointup_gc_client_secret');
						$GcEDvalue = get_option('appointup_gc_status');
				restore_current_blog();
				
				
				
				$timeMin = $cdate.'T00:00:00Z';
				$calmaxdays = get_option('appointup_maximum_advance_booking');
				$maxdays=str_replace("D","",$calmaxdays);
				//$timeMax = date_i18n('Y-m-d', strtotime('+'.$maxdays.' day', strtotime(date_i18n('Y-m-d')))).'T00:00:00Z';
				$timeMax = date_i18n('Y-m-d', strtotime('+1 day', strtotime($cdate))).'T00:00:00Z';
					
					
					
					$clientP = new Google_Client();
					$clientP->setApplicationName("Appointkart Google Calender");
					$clientP->setClientId($GcclientID);
					$clientP->setClientSecret($GcclientSecret);
					$clientP->setRedirectUri('https://coachleads.com/wp-admin/admin.php?page=google_connect');
					$clientP->setDeveloperKey($GcclientID);
					$clientP->setScopes('https://www.googleapis.com/auth/calendar');
					$clientP->setAccessType('offline');
					$calP = new Google_CalendarService($clientP);	
					
				
					$clientP->setAccessToken($provider_access_token);
					$accesstoken = json_decode($provider_access_token);
					
					if ($provider_access_token) {
						if ($clientP->isAccessTokenExpired()) {
							$clientP->refreshToken($accesstoken->refresh_token);
						}
					}
					if ($clientP->getAccessToken()){
						$allevents_provider = $calP->events->listEvents($calendarId,array(
						'singleEvents' => true,
						'orderBy'      => 'startTime',
						'timeMin'      => $timeMin,
						'timeMax' 	   => $timeMax,
						'maxResults'   => 100,
						));
				    }		
					
				return $allevents_provider;	
}


$all_providers = $provider->readAll();

foreach($all_providers as $single_provider){
		$provider_id = $single_provider['id'];
		$provider_gc_status = get_user_meta($provider_id,'appointup_gc_status');
		$provider_gc_id = get_user_meta($provider_id,'appointup_gc_id');
		/* $provider_gc_client_id = get_user_meta($provider_id,'appointup_gc_client_id');
		$provider_gc_client_secret = get_user_meta($provider_id,'appointup_gc_client_secret'); */
		$provider_gc_data = get_user_meta($provider_id,'appointup_gc_google_data');
		$provider_fetch_gc_ids = array();
		$provider_fetch_gc_id = get_user_meta($provider_id,'appointup_fetch_gc_id');
		if(isset($provider_fetch_gc_id[0]) && $provider_fetch_gc_id[0]!=''){
			$provider_fetch_gc_ids	= explode(',',$provider_fetch_gc_id[0]);						
		}
	if($provider_gc_status[0]=='Y' && $provider_gc_data[0]!=''){
		if(sizeof($provider_fetch_gc_ids)>0){
			foreach($provider_fetch_gc_ids as $provider_fetch_gc_idss){		
				
				$provider_events  =	get_providerevents($provider_id,$provider_fetch_gc_idss,$provider_gc_data[0],$cdate);
				
				
				if(sizeof($provider_events)>0){
					foreach($provider_events['items'] as $provider_single_event){
						
						if(isset($provider_single_event['start']['timezone'])){
						$eventtimezone = $provider_single_event['start']['timezone'];	
						}elseif(isset($provider_single_event['timezone'])){
						$eventtimezone = $provider_single_event['timezone'];	
						}else{
						$eventtimezone = $provider_events['timeZone'];	
						}
						$gcslotstart = $provider_single_event['start']['dateTime'];
						$gcslotend = $provider_single_event['end']['dateTime'];
						
						if(isset($eventtimezone) && $eventtimezone!=''){	
							$providertzinfo = get_user_meta($provider_id,'provider_timezone');
							if(isset($providertzinfo[0])){
								$provider_timezone = $providertzinfo[0];
							}else{
								$provider_timezone = get_option('timezone_string');
							}
							$offset= $appointupbj->get_timezone_offset($provider_timezone,$eventtimezone);
							$timediff = $offset/3600;
							
							$stt=new DateTime($provider_single_event['start']['dateTime']);
							$ett=new DateTime($provider_single_event['end']['dateTime']);
							$startdate = $stt->format('Y-m-d'); 
							$starttime = $stt->format('H:i:s'); 
							$enddate = $ett->format('Y-m-d'); 
							$endtime = $ett->format('H:i:s'); 
							$gceventstartdt = $startdate.' '.$starttime;
							$gceventenddt = $enddate.' '.$endtime;		
																					
							if(is_numeric(strpos($timediff,'-'))){
								$timezonehrs = str_replace('-','',$timediff)*60;
								$gcslotstart = date_i18n('Y-m-d H:i:s',strtotime('+'.$timezonehrs.' minutes',strtotime($gceventstartdt)));
								$gcslotend = date_i18n('Y-m-d H:i:s',strtotime('+'.$timezonehrs.' minutes',strtotime($gceventenddt)));
							}else{
								$timezonehrs = str_replace('+','',$timediff)*60;
								$gcslotstart = date_i18n('Y-m-d H:i:s',strtotime('-'.$timezonehrs.' minutes',strtotime($gceventstartdt)));
								$gcslotend = date_i18n('Y-m-d H:i:s',strtotime('-'.$timezonehrs.' minutes',strtotime($gceventenddt)));						
							}							
						}
												
						$eventtitle = $provider_single_event['summary'];
						/*$eventdescription = $provider_single_event['description'];
						$servicename = extract_unit($provider_single_event['description'], 'Service =', 'Name =');
						$clientName = extract_unit($provider_single_event['description'], 'Name =', 'Email =');
						$clientEmail = extract_unit($provider_single_event['description'], 'Email =', 'Phone =');
						$clientPhone = explode('Phone =',$provider_single_event['description']);
						$SPclientPhone = '';
						if(isset($clientPhone[1])){	$SPclientPhone = $clientPhone[1];		}*/
						
						if(!isset($provider_single_event['transparency'])){
							/* $eventinfo[$provider_id][] = array('title'=>$eventtitle,'start'=>$eventstartdt,'end'=>$eventenddt,'timezone'=>$provider_events['timeZone']); */
							$eventinfo[$provider_id][] = array('title'=>$eventtitle,'start'=>$gcslotstart,'end'=>$gcslotend,'timezone'=>$eventtimezone);
						}					
					}
				}
			}
		}else{
			$provider_events  =	get_providerevents($provider_id,$provider_gc_id[0],$provider_gc_data[0],$cdate);
			if(sizeof($provider_events)>0){
				
				foreach($provider_events['items'] as $provider_single_event){
					
					if(isset($provider_single_event['start']['timezone'])){
					$eventtimezone = $provider_single_event['start']['timezone'];	
					}elseif(isset($provider_single_event['timezone'])){
					$eventtimezone = $provider_single_event['timezone'];	
					}else{
					$eventtimezone = $provider_events['timeZone'];	
					}
					$gcslotstart = $provider_single_event['start']['dateTime'];
					$gcslotend = $provider_single_event['end']['dateTime'];
					
					
					if(isset($eventtimezone) && $eventtimezone!=''){	
						$providertzinfo = get_user_meta($provider_id,'provider_timezone');
						if(isset($providertzinfo[0])){
							$provider_timezone = $providertzinfo[0];
						}else{
							$provider_timezone = get_option('timezone_string');
						}
						$offset= $appointupbj->get_timezone_offset($provider_timezone,$eventtimezone);
						$timediff = $offset/3600;
						
						$stt=new DateTime($provider_single_event['start']['dateTime']);
						$ett=new DateTime($provider_single_event['end']['dateTime']);
						$startdate = $stt->format('Y-m-d'); 
						$starttime = $stt->format('H:i:s'); 
						$enddate = $ett->format('Y-m-d'); 
						$endtime = $ett->format('H:i:s'); 
						$gceventstartdt = $startdate.' '.$starttime;
						$gceventenddt = $enddate.' '.$endtime;		
																				
						if(is_numeric(strpos($timediff,'-'))){
							$timezonehrs = str_replace('-','',$timediff)*60;
							$gcslotstart = date_i18n('Y-m-d H:i:s',strtotime('+'.$timezonehrs.' minutes',strtotime($gceventstartdt)));
							$gcslotend = date_i18n('Y-m-d H:i:s',strtotime('+'.$timezonehrs.' minutes',strtotime($gceventenddt)));
						}else{
							$timezonehrs = str_replace('+','',$timediff)*60;
							$gcslotstart = date_i18n('Y-m-d H:i:s',strtotime('-'.$timezonehrs.' minutes',strtotime($gceventstartdt)));
							$gcslotend = date_i18n('Y-m-d H:i:s',strtotime('-'.$timezonehrs.' minutes',strtotime($gceventenddt)));						
						}							
					}
									
					$eventtitle = $provider_single_event['summary'];
					/*$eventdescription = $provider_single_event['description'];
					$servicename = extract_unit($provider_single_event['description'], 'Service =', 'Name =');
					$clientName = extract_unit($provider_single_event['description'], 'Name =', 'Email =');
					$clientEmail = extract_unit($provider_single_event['description'], 'Email =', 'Phone =');
					$clientPhone = explode('Phone =',$provider_single_event['description']);
					$SPclientPhone = '';
					if(isset($clientPhone[1])){	$SPclientPhone = $clientPhone[1];		}*/
					$eventstartdt = $provider_single_event['start']['dateTime'];
					$eventenddt = $provider_single_event['end']['dateTime'];
					if(!isset($provider_single_event['transparency'])){
						$eventinfo[$provider_id][] = array('title'=>$eventtitle,'start'=>$gcslotstart,'end'=>$gcslotend,'timezone'=>$eventtimezone);						
					}
				}
			}
		}
	
	}
		
}
	$adminevents = json_encode($eventinfo_admin);
	$providerevents = json_encode($eventinfo);

echo  $adminevents.'==##==##'.$providerevents;
?>