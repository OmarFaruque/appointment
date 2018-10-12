<?php 
class appointment_general {
	/*Business author id*/ 
	public $business_author_id;

	function apt_price_format($amount) {
		$return_price ='';
		
		if(get_option('appointment_currency_symbol_position'.'_'.$this->business_author_id)=='B') { $return_price .= '<i>'.get_option('appointment_currency_symbol'.'_'.$this->business_author_id).'</i>'; }		
			if(get_option('appointment_price_format_comma_separator')=='Y') { 
				$return_price .= number_format($amount,get_option('appointment_price_format_decimal_places'.'_'.$this->business_author_id),".",','); 
			} else {
				$return_price .= number_format($amount,get_option('appointment_price_format_decimal_places'.'_'.$this->business_author_id),".",''); 
			}		
		if(get_option('appointment_currency_symbol_position')=='A') { $return_price .= '<i>'.get_option('appointment_currency_symbol'.'_'.$this->business_author_id).'</i>'; }
							
		return $return_price;					
	}			
	function apt_price_format_for_pdf($amount) {		
		$return_price ='';		
		if(get_option('appointment_currency_symbol_position'.'_'.$this->business_author_id)=='B') { 
			$return_price .= iconv('UTF-8', 'windows-1252', get_option('appointment_currency_symbol'.'_'.$this->business_author_id)); 
		}					
		if(get_option('appointment_price_format_comma_separator'.'_'.$this->business_author_id)=='Y') 
		{ 				
			$return_price .= number_format($amount,get_option('appointment_price_format_decimal_places'.'_'.$this->business_author_id),".",','); 			
		} 
		else 
		{				
			$return_price .= number_format($amount,get_option('appointment_price_format_decimal_places'.'_'.$this->business_author_id),".",''); 			
		}						
		if(get_option('appointment_currency_symbol_position'.'_'.$this->business_author_id)=='A') {
		$return_price .= get_option('appointment_currency_symbol'.'_'.$this->business_author_id); 
		}									
		return $return_price;						
	}		
	
	function apt_price_format_without_currency_symbol($amount) {
		$return_price ='';
			if(get_option('appointment_price_format_comma_separator'.'_'.$this->business_author_id)=='Y') { 
				$return_price .= number_format($amount,get_option('appointment_price_format_decimal_places'.'_'.$this->business_author_id),".",','); 
			} else {
				$return_price .= number_format($amount,get_option('appointment_price_format_decimal_places'.'_'.$this->business_author_id),".",''); 
			}	
		return $return_price;	
	}
	
	function convertToHoursMins($time, $format = '%02d:%02d') {
    if ($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($format, $hours, $minutes);
	}

	
}