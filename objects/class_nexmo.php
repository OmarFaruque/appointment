<?php 

  class appointment_nexmo{  
	 var $appointment_nexmo_apikey; 	 
	 var $appointment_nexmo_api_secret; 
	 var $appointment_nexmo_form; 
     
	 public function send_nexmo_sms($phone,$appointment_nexmo_text) {
		 $nexmo_api_key=$this->appointment_nexmo_apikey;
		 $appointment_nexmo_api_secret=$this->appointment_nexmo_api_secret;
		 $appointment_nexmo_form=$this->appointment_nexmo_form;
		 $queryinfo = array('api_key' => $nexmo_api_key, 'api_secret' => $appointment_nexmo_api_secret, 'to' => $phone, 'from' => $appointment_nexmo_form, 'text' => $appointment_nexmo_text);
		$url = 'https://rest.nexmo.com/sms/json?' . http_build_query($queryinfo);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		return $response;
	 } 
  }
?>