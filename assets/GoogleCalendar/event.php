<?php
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] ); 

if( file_exists( $parse_uri[0] . 'wp-load.php' ) ){
	require_once( $parse_uri[0] . 'wp-load.php' );
}

require_once 'google-api-php-client/src/Google_Client.php';
require_once 'google-api-php-client/src/contrib/Google_CalendarService.php';

$cdate = $_GET['cdate'];
$bwid = $_GET['bwid'];
$eventinfo = array();

function get_providerevents($calendarId,$provider_access_token,$cdate,$GcclientID,$GcclientSecret,$GcEDvalue,$bwid){
	
	$timeMin = $cdate.'T00:00:00Z';
	$timeMax = date_i18n('Y-m-d', strtotime('+1 day', strtotime($cdate))).'T00:00:00Z';
		
	$clientP = new Google_Client();
	$clientP->setApplicationName("Appointment Google Calender");
	$clientP->setClientId($GcclientID);
	$clientP->setClientSecret($GcclientSecret);
	$clientP->setRedirectUri(get_option('apt_gc_frontend_url'.'_'.$bwid));
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


$GcEDvalue = get_option('apt_gc_status'.'_'.$bwid);
$provider_gc_data = get_option('apt_gc_token'.'_'.$bwid);

if($GcEDvalue == 'Y' && $provider_gc_data != ''){
	$GcclientID = get_option('apt_gc_client_id'.'_'.$bwid);
	$GcclientSecret = get_option('apt_gc_client_secret'.'_'.$bwid);
	$provider_gc_id = get_option('apt_gc_id'.'_'.$bwid);
	$provider_gc_data = get_option('apt_gc_token'.'_'.$bwid);
	$provider_events  =	get_providerevents($provider_gc_id,$provider_gc_data,$cdate,$GcclientID,$GcclientSecret,$GcEDvalue,$bwid);
	
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
				$stt=new DateTime($provider_single_event['start']['dateTime']);
				$ett=new DateTime($provider_single_event['end']['dateTime']);
				$startdate = $stt->format('Y-m-d');
				$starttime = $stt->format('H:i:s');
				$enddate = $ett->format('Y-m-d');
				$endtime = $ett->format('H:i:s');
				$gceventstartdt = $startdate.' '.$starttime;
				$gceventenddt = $enddate.' '.$endtime;
				$gcslotstart = date_i18n('Y-m-d H:i:s', strtotime($gceventstartdt));
				$gcslotend = date_i18n('Y-m-d H:i:s', strtotime($gceventenddt));
			}
									
			$eventtitle = $provider_single_event['summary'];
			
			if(!isset($provider_single_event['transparency'])){
				$eventinfo[] = array('title'=>$eventtitle,'start'=>$gcslotstart,'end'=>$gcslotend,'timezone'=>$eventtimezone);
			}
		}
	}
}
$providerevents = json_encode($eventinfo);

echo $providerevents;
?>