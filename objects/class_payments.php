<?php

class appointment_payments{
	

    /* Object property Identity */
    public $id;
	
	/* Object property Location Identity */
	public $location_id;
	
	/* Object property Client Identity */
	public $client_id;
	
	/* Object property Order Identity */
	public $order_id;
	
	/* Object property Payment Method */
    public $payment_method;
	
	/* Object property Transaction Id */
	public $transaction_id;
	
	/* Object property Amount */
	public $amount;
	
	/* Object property Taxes */
	public $taxes;	
	
	public $discount;
	public $partial;
	public $net_total;
	
	/* Object property Paypal Currency */
	public $pp_currency;
	
	/* Object property Stripe Currency */
	public $stripe_currency;

	/* Business owner id */
	public $business_owner_id;
	
	
	
	
	 /**
     * create appointment payment table
     */ 
	function create_table() {
	global $wpdb;

	$table_name = $wpdb->prefix .'apt_payments';
	
	if( $wpdb->get_var( "show tables like '{$table_name}'" ) != $table_name ) {		
	
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `location_id` int(11) NOT NULL,
			  `business_owner_id` int(11) NOT NULL,
			  `client_id` int(11) NOT NULL,
			  `order_id` int(11) NOT NULL,
			  `payment_method` enum('paypal','pay_locally','Free','payumoney','paytm','stripe') COLLATE utf8_unicode_ci DEFAULT NULL,
			  `transaction_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
			  `amount` DOUBLE NOT NULL,
			  `discount` DOUBLE NOT NULL,
			  `taxes` DOUBLE NOT NULL,
			  `partial` DOUBLE NOT NULL,
			  `net_total` DOUBLE NOT NULL,
			  `lastmodify` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;";
	
	
	dbDelta($sql);     
			}
	} 


	/**
     * Read All Payments 
     * @param $page for pagination
     * @param $from_record_num form record for pagination
     * @param $records_per_page records per page limit for pagination
     * @return object payment records 
     */
	function readAll(){
			global $wpdb;
			
			if($this->location_id=='All' && $this->location_id!='0'){
				$result = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."apt_payments where business_owner_id='".$this->business_owner_id."' order by id desc");
			}else{
				$result = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."apt_payments where location_id=".$this->location_id." and business_owner_id='".$this->business_owner_id."' order by id desc");
			}
			return $result;
	}
	
	function add_payments(){
		global $wpdb;
		
		$stmt = $wpdb->query("INSERT INTO ".$wpdb->prefix."apt_payments(`id`, `location_id`, `business_owner_id`, `client_id`, `order_id`, `payment_method`, `transaction_id`, `amount`, `discount`, `taxes`, `partial`, `net_total`) VALUES ('','".$this->location_id."','".$this->business_owner_id."','".$this->client_id."','".$this->order_id."','".$this->payment_method."','".$this->transaction_id."','".$this->amount."','".$this->discount."','".$this->taxes."','".$this->partial."','".$this->net_total."')");
		return $stmt;
		
	}
	
	 /**
     * countAll payment records
     * @return integer number of records 
     */
	function countAll(){

			global $wpdb;

			$stmt = $wpdb->get_results("SELECT id FROM ".$wpdb->prefix."apt_payments");
	
			$num =sizeof($stmt);

			 return $num;
		}	
		
		
		
	 /**
     * Read One Payment record by order id
     * @return array payment records 
     */	
	function read_one_by_order_id(){

			global $wpdb;
			$queryString ="SELECT 

			* 

			FROM

			".$wpdb->prefix."apt_payments

			WHERE order_id='".$this->order_id."'";


			$stmt = $wpdb->get_results($queryString);

			foreach($stmt as $result){

			$this->amount = $result->amount;
			$this->taxes = $result->taxes;
			$this->discount = $result->discount;
			$this->partial = $result->partial;
			$this->net_total = $result->net_total;
			$this->payment_method = $result->payment_method;
			}

			return $stmt;


	}
			
	/**
     * Check if current currency support Paypal or not
     * @return Yes-true,No-false
     */	
	function check_paypal_currency() {

	    $pp_currency_array = array('AUD','BRL','CAD','CZK','DKK','EUR','HKD','HUF','ILS','JPY','MYR','NOK','NZD','PHP','PLN','GBP','SGD','SEK','CHF','TWD','THB','TRY','USD');
		
		if(in_array($this->pp_currency,$pp_currency_array)) {
		  return true;
		} else {
		  return false;
		}

	}	
	
	/**
     * Check if current currency support stripe or not
     * @return Yes-true,No-false
     */	
	function check_stripe_currency() {

	    $stripe_currency_array = array('AED','ALL','ANG','ARS','AUD','AWG','BBD','BDT','BIF','BMD','BND','BOB','BRL','BSD','BWP','BZD','CAD','CHF','CLP','CNY','COP','CRC','CVE','CZK','DJF','DKK','DOP','DZD','EGP','ETB','EUR','FJD','FKP','GBP','GIP','GMD','GNF','GTQ','GYD','HKD','HNL','HRK','HTG','HUF','IDR','ILS','INR','ISK','JMD','JPY','KES','KHR','KMF','KRW','KYD','KZT','LAK','LBP','LKR','LRD','MAD','MDL','MNT','MOP','MRO','MUR','MVR','MWK','MXN','MYR','NAD','NGN','NIO','NOK','NPR','NZD','PAB','PEN','PGK','PHP','PKR','PLN','PYG','QAR','RUB','SAR','SBD','SCR','SEK','SGD','SHP','SLL','SOS','STD','SVC','SZL','THB','TOP','TTD','TWD','TZS','UAH','UGX','USD','UYU','UZS','VND','VUV','WST','XAF','XOF','XPF','YER','ZAR','AFN','AMD','AOA','AZN','BAM','BGN','GEL','KGS','LSL','MGA','MKD','MZN','RON','RSD','RWF','SRD','TJS','TRY','XCD','ZMW');
		
		if(in_array($this->stripe_currency,$stripe_currency_array)) {
		  return true;
		} else {
		  return false;
		}

	}	
	
	
	
	
	function delete_payments_by_order_id() {		
	global $wpdb;		  		  
	$result = $wpdb->query("delete from ".$wpdb->prefix."apt_payments where order_id=".$this->order_id);
			  		  return $result;
	}

	function get_payments_byrange($startdate,$enddate){
	global $wpdb;	
	$result = $wpdb->get_results("select * from ".$wpdb->prefix."apt_payments where business_owner_id='".$this->business_owner_id."' and lastmodify >= '$startdate' AND lastmodify <= '$enddate' and location_id = ".$this->location_id);
	 return $result;
	
	}
		
		
}