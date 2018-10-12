<?php 
class appointment_staff{
	
    /* Object property Identity */
    public $id;
	
	/* Object property Location ID */
    public $location_id;
	
	/* Object property Service Provider Name */
    public $staffName;
   
    /* Object property Service Provider Image */
    public $image;
   
	/* Object property Service Provider Email */
    public $email;
	
	/* Object property Service Provider Phone */
	public $phone;
	
	/* Object property Service Provider Schedule Type */
	public $schedule_type;
	
	/* Object property Service Provider Status */
	public $status;
	
	/* Object property Service Identity */
	public $service_id;

	/* Business owner ID */
	public $business_owner_id;
  
	
	 /**
     * Read All Providers 
     * @param $page for pagination
     * @param $from_record_num form record for pagination
     * @param $records_per_page records per page limit for pagination
     * @param $selectedArr array having some staffs ids
     * return array service staffs results 
     */
	function readAll($page='', $from_record_num='', $records_per_page='',$selectedArr=array()){
		
				global $wpdb;
				$return_array = array();

				if($from_record_num!='' && $records_per_page!=''){

				$limit = 'LIMIT '.$from_record_num.', '.$records_per_page;
				}else{
				$limit = '';
				}
			
			
			$all_service_staffs = $wpdb->get_results("SELECT  * from ".$wpdb->prefix."users as u 
			left join ".$wpdb->prefix."usermeta um1 on u.ID = um1.user_id 
			left join ".$wpdb->prefix."usermeta um2 on u.ID = um2.user_id 
			left join ".$wpdb->prefix."usermeta um3 on u.ID = um3.user_id 
			WHERE um1.meta_key ='".$wpdb->prefix."capabilities' AND um1.meta_value LIKE '%apt_staff%' AND um2.meta_key ='staff_location' AND um2.meta_value='".$this->location_id."' AND um3.meta_key = 'staff_bwid' AND um3.meta_value='".$this->business_owner_id."' group by u.ID ".$limit);		
			foreach($all_service_staffs as $row) {
				if(get_user_meta($row->ID, 'staff_status',true)!='E') { continue; }		
									
					if(isset($selectedArr) && sizeof($selectedArr)>0) {
								if(!in_array($row->ID,$selectedArr)) {
									
						 continue;
						}
					}
					$staff_data =  array();						
					$staff_data['id'] = $row->ID;
					$staff_data['staff_name'] = $row->display_name;						
					$staff_data['email'] = $row->user_email;						
					$staff_data['phone'] = get_user_meta($row->ID, 'staff_phone',true);						
					$staff_data['description'] = get_user_meta($row->ID, 'staff_description',true);					
					$staff_data['location_id'] = get_user_meta($row->ID, 'staff_location',true);
					$staff_data['schedule_type'] = get_user_meta($row->ID, 'schedule_type',true);
					$staff_data['image'] = get_user_meta($row->ID, 'staff_image',true);	
					$staff_data['timezone'] = get_user_meta($row->ID, 'staff_timezone',true);	
					$staff_data['timezoneID'] = get_user_meta($row->ID, 'staff_timezoneID',true);	
					$staff_data['status'] = get_user_meta($row->ID, 'staff_status',true);	
					$staff_data['caps'] = get_user_meta($row->ID,$wpdb->prefix.'capabilities',true);	
					$return_array[] = $staff_data;			   
			}														
			return $return_array;
	 }
		
	
	/**
     * Read All Providers Ignoring Disabled 
     * @param $page for pagination
     * @param $from_record_num form record for pagination
     * @param $records_per_page records per page limit for pagination
     * @param $selectedArr array having some staffs ids
     * return array service staffs results 
     */	
	 function readAll_with_disables($reqpage='',$selectedArr=array()){
			global $wpdb;
			$return_array = array();
			
			if($this->location_id=='All' && $reqpage=='Export' && $this->location_id!='0'){
			$all_service_staffs = $wpdb->get_results("SELECT  * from ".$wpdb->prefix."users as u 
			left join ".$wpdb->prefix."usermeta um1 on u.ID = um1.user_id 
			WHERE um1.meta_key ='".$wpdb->prefix."capabilities' AND um1.meta_value LIKE '%apt_staff%' group by u.ID"); 
			}else{
			$all_service_staffs = $wpdb->get_results("SELECT  * from ".$wpdb->prefix."users as u 
			left join ".$wpdb->prefix."usermeta um1 on u.ID = um1.user_id 
			left join ".$wpdb->prefix."usermeta um2 on u.ID = um2.user_id 
			WHERE um1.meta_key ='".$wpdb->prefix."capabilities' AND um1.meta_value LIKE '%apt_staff%' AND um2.meta_key ='staff_location' AND um2.meta_value='".$this->location_id."' group by u.ID"); 
			}	
				foreach($all_service_staffs as $row) {		
						if(isset($selectedArr) && sizeof($selectedArr)>0) {
							if(!in_array($row->ID,$selectedArr)) {
							 continue;
							}
						}
						
						$staff_data =  array();						
						$staff_data['id'] = $row->ID;
						$staff_data['staff_name'] = $row->display_name;						
						$staff_data['username'] = $row->user_login;						
						$staff_data['email'] = $row->user_email;										
						$staff_data['phone'] = get_user_meta($row->ID, 'staff_phone',true);						
						$staff_data['description'] = get_user_meta($row->ID, 'staff_description',true);	
						$staff_data['location_id'] = get_user_meta($row->ID, 'staff_location',true);
						$staff_data['schedule_type'] = get_user_meta($row->ID, 'schedule_type',true);
						$staff_data['image'] = get_user_meta($row->ID, 'staff_image',true);	
						$staff_data['timezone'] = get_user_meta($row->ID, 'staff_timezone',true);	
						$staff_data['timezoneID'] = get_user_meta($row->ID, 'staff_timezoneID',true);	
						$staff_data['status'] = get_user_meta($row->ID, 'staff_status',true);
						$staff_data['caps'] = get_user_meta($row->ID,$wpdb->prefix.'capabilities',true);						
						$return_array[] = $staff_data;			   
				}														
				return $return_array;
		
	 }

		/**
		 * Count All Service Providers
		 */	
		function countAll(){
			global $wpdb;
			$all_service_staffs = $wpdb->get_results("SELECT  * from ".$wpdb->prefix."users as u 
			left join ".$wpdb->prefix."usermeta um1 on u.ID = um1.user_id 
			left join ".$wpdb->prefix."usermeta um2 on u.ID = um2.user_id 
			WHERE um1.meta_key ='".$wpdb->prefix."capabilities' AND um1.meta_value LIKE '%apt_staff%' AND um2.meta_key ='staff_location' AND um2.meta_value='".$this->location_id."' group by u.ID"); 
								
			return sizeof($all_service_staffs);
		}

		
		/**
		 * Read one record Service Providers 
		*/	
		function readOne(){
			global $wpdb;
			$user = get_user_by( 'id', $this->id );	
			
			
			$staff_data =  array();						
			if($user):
			$staff_data['id'] = $user->data->ID;
			$staff_data['staff_name'] = $user->data->display_name;						
			$staff_data['username'] = $user->data->user_login;						
			$staff_data['email'] = $user->data->user_email;										
			$staff_data['phone'] = get_user_meta($user->data->ID, 'staff_phone',true);						
			$staff_data['description'] = get_user_meta($user->data->ID, 'staff_description',true);	
			$staff_data['location_id'] = get_user_meta($user->data->ID, 'staff_location',true);
			$staff_data['schedule_type'] = get_user_meta($user->data->ID, 'schedule_type',true);
			$staff_data['image'] = get_user_meta($user->data->ID, 'staff_image',true);	
			$staff_data['timezone'] = get_user_meta($user->data->ID, 'staff_timezone',true);	
			$staff_data['timezoneID'] = get_user_meta($user->data->ID, 'staff_timezoneID',true);	
			$staff_data['status'] = get_user_meta($user->data->ID, 'staff_status',true);	
			$staff_data['caps'] = get_user_meta($user->data->ID,$wpdb->prefix.'capabilities',true);
			endif;
			$return_array[] = $staff_data;			   
			
			return $return_array;
			
			
			
			
			
			/*$this->email = $user->data->user_email;						
			
			
			/* $this->email = $user->data->user_email; *//*						
			$this->phone = get_user_meta($user->data->ID, 'staff_phone',true);						
			$this->schedule_type = get_user_meta($user->data->ID, 'schedule_type',true);						
			$this->image = get_user_meta($user->data->ID, 'staff_image',true);
			$this->status = get_user_meta($user->data->ID, 'staff_status',true);*/
		   
		}
		
		
		/**
		 * Read one record Service Provider
		 * @return Update-true, Error-false
		*/	
		function update(){	
			global $wpdb;
			update_user_meta( $this->id, 'staff_phone', $this->phone);			 
			update_user_meta( $this->id, 'schedule_type', $this->schedule_type);			 
			update_user_meta( $this->id, 'staff_image', $this->image);				     
			update_user_meta( $this->id, 'display_name', $this->staffName);			 
			update_user_meta( $this->id, 'user_email', $this->email);			 			
			update_user_meta( $this->id, 'staff_status', $this->status);			 			
			$user_update_check = wp_update_user( array( 'ID' => $this->id, 
			'user_email' => $this->email, 'display_name' =>$this->staffName ) );	
			
			if ( is_wp_error( $user_update_check ) ) {				 
					return false;			
			} else {				
				return true;			 
			}
		}
		
		
		/**
		 * Delete Service Provider
		 * @return true-Delete,false-Error
		*/	
		function delete(){
			global $wpdb;
			$staff_id=$this->id;	
			$user = new WP_User( $staff_id );
			$user->remove_cap( 'apt_staff' );
		}
		
		
		/**
		 * Change Status of Service Provider
		*/
		function change_status() {
			global $wpdb;
		   update_user_meta( $this->id, 'staff_status', $this->status);			
		}
	

		/**
		 * Change Schedule Type
		*/
		function change_schedule_type() {
			global $wpdb;
		 update_user_meta( $this->id, 'schedule_type', $this->schedule_type);
		}
		
		
			
		/**
		 * Providers of a Service only enable ones
		 * Return Array resutls
		*/
		function read_staffs_by_service_id() {
		  global $wpdb;
		  $return_array = array();
		 //and um.meta_key = 'staff_bwid' and um.meta_value='".$this->business_owner_id."'
		 //um.meta_key ='".$wpdb->prefix."capabilities' and um.meta_value LIKE '%apt_staff%'
		 $results = $wpdb->get_results("select * from ".$wpdb->prefix."users as u,  ".$wpdb->prefix."usermeta as um
			where u.ID in(select provider_id from ".$wpdb->prefix."apt_providers_services where service_id='".$this->service_id."')  and um.user_id=u.ID and um.meta_key = 'staff_bwid' and um.meta_value='".$this->business_owner_id."' group by u.ID"); 
		  		  

			if(sizeof($results) > 0) {
			   foreach($results as $result){
				  $pst = get_user_meta($result->ID, 'staff_status',true);
					if($pst!='E'){
					continue;
					}
				 $temparry = array();
			     $temparry['id']= $result->ID;
				 $temparry['staff_name'] = $result->display_name;						
				 $temparry['username'] = $result->user_login;						
				 $temparry['email'] = $result->user_email;										
				 $temparry['phone'] = get_user_meta($result->ID, 'staff_phone',true);						
				 $temparry['description'] = get_user_meta($result->ID, 'staff_description',true);	
				 $temparry['location_id'] = get_user_meta($result->ID, 'staff_location',true);
				 $temparry['schedule_type'] = get_user_meta($result->ID, 'schedule_type',true);
				 $temparry['image'] = get_user_meta($result->ID, 'staff_image',true);	
				 $temparry['timezone'] = get_user_meta($result->ID, 'staff_timezone',true);	
				 $temparry['timezoneID'] = get_user_meta($result->ID, 'staff_timezoneID',true);	
				 $temparry['status'] = get_user_meta($result->ID, 'staff_status',true);	
				 $temparry['caps'] = get_user_meta($result->ID,$wpdb->prefix.'capabilities',true);
				 $return_array[] = $temparry;
			   }			   
			}
  		   return $return_array;
		}
		
		 /* function readAll_existing_users(){
			global $wpdb;
			$all_existing_users = $wpdb->get_results("SELECT  * from ".$wpdb->prefix."users as u 
			left join ".$wpdb->prefix."usermeta um1 on u.ID = um1.user_id 
			WHERE um1.meta_key ='".$wpdb->prefix."capabilities' AND um1.meta_value NOT LIKE '%administrator%' group by u.ID"); 
							
			return $all_existing_users;
		
		}  */
		
		
		function readAll_existing_users(){
			global $wpdb;
			$all_existing_users = $wpdb->get_results("SELECT A.* FROM ( SELECT u.* from ".$wpdb->prefix."users as u, ".$wpdb->prefix."usermeta as um1 WHERE u.ID = um1.user_id AND um1.meta_key = '".$wpdb->prefix."capabilities' AND um1.meta_value NOT LIKE '%apt_staff%' AND um1.meta_value NOT LIKE '%administrator%' group by u.ID) as A GROUP by A.ID");
			
			return $all_existing_users;
		
		}
		
		
		
		/* Count Location Services */
		function total_location_providers(){
			global $wpdb;
			$all_staffs = $wpdb->get_results("SELECT  * from ".$wpdb->prefix."users as u 
			left join ".$wpdb->prefix."usermeta um1 on u.ID = um1.user_id 
			left join ".$wpdb->prefix."usermeta um2 on u.ID = um2.user_id 
			WHERE um1.meta_key ='".$wpdb->prefix."capabilities' AND um1.meta_value LIKE '%apt_staff%' AND um2.meta_key ='staff_location' AND um2.meta_value='".$this->location_id."' group by u.ID"); 
				$locationstaff =0;
				foreach($all_staffs as $row) {		
					if(get_user_meta($row->ID, 'staff_location',true)== $this->location_id){
						$locationstaff++;
					} 
				}							
			return $locationstaff;
		}
		
	 /**
     * Read All Managers
     */	
	 function readAll_managers(){
			global $wpdb;
			$return_array = array();
						
			$all_service_staffs = $wpdb->get_results("SELECT  * from ".$wpdb->prefix."users as u 
			left join ".$wpdb->prefix."usermeta um1 on u.ID = um1.user_id 
			left join ".$wpdb->prefix."usermeta um2 on u.ID = um2.user_id 
			WHERE um1.meta_key ='".$wpdb->prefix."capabilities' AND um1.meta_value LIKE '%apt_manager%' AND um2.meta_key ='staff_location' AND um2.meta_value='".$this->location_id."' group by u.ID"); 
			
				foreach($all_service_staffs as $row) {									
						$staff_data =  array();						
						$staff_data['id'] = $row->ID;
						$staff_data['staff_name'] = $row->display_name;						
						$staff_data['username'] = $row->user_login;						
						$staff_data['email'] = $row->user_email;										
						$staff_data['phone'] = get_user_meta($row->ID, 'staff_phone',true);				
						$staff_data['description'] = get_user_meta($row->ID, 'staff_description',true);	
						$staff_data['location_id'] = get_user_meta($row->ID, 'staff_location',true);
						$staff_data['schedule_type'] = get_user_meta($row->ID, 'schedule_type',true);
						$staff_data['image'] = get_user_meta($row->ID, 'staff_image',true);	
						$staff_data['timezone'] = get_user_meta($row->ID, 'staff_timezone',true);	
						$staff_data['timezoneID'] = get_user_meta($row->ID, 'staff_timezoneID',true);	
						$staff_data['status'] = get_user_meta($row->ID, 'staff_status',true);
						$staff_data['caps'] = get_user_meta($row->ID,$wpdb->prefix.'capabilities',true);						
					$return_array[] = $staff_data;			   
				}														
				return $return_array;
	 }
	 
	function check_service_link_staff(){
		global $wpdb;
		$stmt = $wpdb->get_results("SELECT * FROM  ".$wpdb->prefix."apt_providers_services where service_id='".$this->service_id."'");
		return $stmt;
	}
	
	
		function C_readOne($id){
			global $wpdb;
			$arr = array();
			$user = get_user_by( 'id', $id );
			$arr['name'] = $user->data->display_name;
			$arr['email'] = $user->data->user_email;
			$arr['phone'] = get_user_meta($id, 'apt_client_phone',true);
			return $arr;
		}
		
}
?>