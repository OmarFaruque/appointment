<?php 
	include(dirname(__FILE__).'/header.php');
	$plugin_url_for_ajax = plugins_url('', dirname(__FILE__));
	
	/* Create Location */
	$location = new appointment_location();
	$location->business_owner_id = get_current_user_id();
	$apt_image_upload= new appointment_image_upload();
	$staff = new appointment_staff();
	
	/*********************Sample Date**************************/
	if(get_option('appointment_sample_status' . '_' . get_current_user_id())=='Y'){
	$root = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
			if (file_exists($root.'/wp-load.php')) {
			require_once($root.'/wp-load.php');
		}
	global $wpdb;
	
		$locationsinfo = array(array('location_title'=>'California','description'=>'California','email'=>'California@California.com','phone'=>'7739477310','address'=>'1625 E 75th St','city'=>'California','state'=>'Los Angles','zip'=>'60649','country'=>'USA'),array('location_title'=>'Singapore ','description'=>'Singapore','email'=>'Singapore@Singapore.com','phone'=>'8884081113','address'=>'514 S. MAGNOLIA ST.','city'=>'Rome','state'=>'Rome','zip'=>'32806','country'=>'Italy'));
		
		$staffsinfo = 	array(array('staff_name'=>'John','username'=>'john'.rand(10,1000),'email'=>'john@demo.com','description'=>'John staff description'),array('staff_name'=>'Johndoe','username'=>'johndoe'.rand(10,1000),'email'=>'Johndoe@demo.com','description'=>'Johndoe staff description'));
		
		$servicesinfo = array(array('service_title'=>'Cosmetic Dentistry','description'=>'Cosmetic dentistry is generally used to refer to any dental work that improves the appearance (though not necessarily the functionality) of teeth, gums and/or bite. It primarily focuses on improvement dental aesthetics in color, position, shape, size, alignment and overall smile appearance.'),array('service_title'=>'Routine Tooth Extractions','description'=>'Routine Extractions. There are instances when a tooth cannot be restored. Extensive decay as a result of chronic neglect or trauma that results in the inadvertent fracture of teeth are two leading causes for a tooth to be deemed non-salvageable.'));
		
		$addonsinfo = array(array('addon_title'=>'Teeth Whitening','price'=>'20','max_qty'=>5),array('addon_title'=>'Surgical tooth extractions','price'=>'100','max_qty'=>10));
		
		
		$categoriesinfo = array(array('category_title'=>' Cosmetic Dentistry'),array('category_title'=>'Routine Tooth Extractions'));
		
		
		$apt_clientinfo = array(array('client_name'=>'John Deo','client_email'=>'johndeo@example.com','client_phone'=>'+17567436945'),array('client_name'=>'John Martin','client_email'=>'johnmartin@example.com','client_phone'=>'+17567436949'));
	
		$locationsids = array();	
		$servicesids = array();	
		$categoriesids = array();	
		$staffsids = array();
		$bdclientids = array();
		$bookingsids = array();
		$paymentsids = array();
		$orderids = array();
		/*Adding Locations */
		foreach($locationsinfo as $locationinfo){
			if(get_option('appointment_multi_location' . '_' . get_current_user_id())=='E'){	
				$wpdb->query("insert into ".$wpdb->prefix."apt_locations set location_title='".$locationinfo['location_title']."',business_owner_id=".get_current_user_id().", description='".$locationinfo['description']."',email='".$locationinfo['email']."',phone='".$locationinfo['phone']."',address='".$locationinfo['address']."',city='".$locationinfo['city']."',state='".$locationinfo['state']."',zip='".$locationinfo['zip']."',country='".$locationinfo['country']."',status='E'");
				$locationsids[] = $wpdb->insert_id;
			}else{
				$locationsids[] = 0;
			}
		}	
		
		/* Adding Categories */
		$catecounter = 0;
		foreach($categoriesinfo as $categoryinfo){
						
			$wpdb->query("insert into ".$wpdb->prefix."apt_categories set business_owner_id=".get_current_user_id().", location_id='".$locationsids[$catecounter]."',category_title='".$categoryinfo['category_title']."'");
			$categoriesids[] =  $wpdb->insert_id;
			$catecounter++;
		}
		/* Add Staff Members */
		$staffcounter =0;
		foreach($staffsinfo as $staffinfo){
		$userdata = array('user_login'=>$staffinfo['username'],'user_email'=>$staffinfo['email'],'user_pass'=>$staffinfo['staff_name'],'first_name'=>$staffinfo['staff_name'],		'last_name'=>'','nickname'=>'','role'=>'subscriber');					
		$user_id = wp_insert_user($userdata);
		$staffsids[] = $user_id;	
		$user = new WP_User($user_id);
		$user->add_cap('apt_staff');
		add_user_meta($user_id, 'staff_location',$locationsids[$staffcounter]);
		add_user_meta($user_id, 'staff_phone','');
		add_user_meta($user_id, 'staff_description',$staffinfo['description']);
		add_user_meta($user_id, 'schedule_type','W');
		add_user_meta($user_id, 'staff_image','');
		add_user_meta($user_id, 'staff_status','E');
		add_user_meta($user_id, 'staff_timezone','');
		add_user_meta($user_id, 'staff_timezoneID','');
		add_user_meta($user_id, 'staff_bwid', get_current_user_id());
			/*Adding Provider Schedule */
			for($dayid=1;$dayid<=7;$dayid++){
				$wpdb->query("insert into ".$wpdb->prefix."apt_schedule set provider_id='".$user_id."',weekday_id='".$dayid."',daystart_time='08:00:00',dayend_time='17:00:00',week_id='1'");
			}
			
			$staffcounter++;		
		}
		
		/* Adding Services */
		$servcounter = 0;
		foreach($servicesinfo as $serviceinfo){
			$wpdb->query("insert into ".$wpdb->prefix."apt_services set location_id='".$locationsids[$servcounter]."',business_owner_id=".get_current_user_id().",color_tag='#".rand(100000,999999)."',service_title='".$serviceinfo['service_title']."',category_id='".$categoriesids[$servcounter]."',duration='30',amount='50',service_description='".$serviceinfo['description']."',service_status='Y'");
			$servicesids[] =  $wpdb->insert_id;			
			
			/*Link Service With Staff Member*/
			$wpdb->query("insert into ".$wpdb->prefix."apt_providers_services set provider_id='".$staffsids[$servcounter]."',service_id='".$servicesids[$servcounter]."'");
			
			/* Service Addons */
			$wpdb->query("INSERT INTO ".$wpdb->prefix."apt_services_addon (id,business_owner_id,service_id,addon_service_name,base_price,maxqty,image,multipleqty,status,position,predefine_image,predefine_image_title,location_id)values('',".get_current_user_id().",'".$servicesids[$servcounter]."','".$addonsinfo[$servcounter]['addon_title']."','".$addonsinfo[$servcounter]['price']."','".$addonsinfo[$servcounter]['max_qty']."','','Y','E','','','','".$locationsids[$servcounter]."')");
			
			
			
			$servcounter++;
		}
		
		/* Adding Clients */
		$clientcounter = 0;
		foreach($apt_clientinfo as $apt_clientsinfo){
			
			if($apt_clientsinfo['client_name'] == 'John Deo'){	
				/* Get Locations id */
				$query = "select * from ".$wpdb->prefix."apt_locations where email='California@California.com'";
				$res = $wpdb->get_results($query);
				/* Get service id */
				$query = "select * from ".$wpdb->prefix."apt_services where service_title='Cosmetic Dentistry'";
				$res_service = $wpdb->get_results($query);
				/* Get provider id */
				$query = "select * from ".$wpdb->prefix."apt_providers_services where service_id='".$res_service[0]->id."'";
				$res_provider = $wpdb->get_results($query);
				
				$bookdate1s = date_i18n('Y-m-d H:i:s');
				$bookdate1 = date_i18n('Y-m-d H:i:s',strtotime("+1 days", strtotime(date_i18n('Y-m-d',strtotime($bookdate1s)).' '.date_i18n('H:i:s',strtotime($bookdate1s)))));
				$bookend = date_i18n('Y-m-d H:i:s',strtotime("+30 minutes", strtotime(date_i18n('Y-m-d',strtotime($bookdate1)).' '.date_i18n('H:i:s',strtotime($bookdate1)))));
			}else{	
				/* Get Locations id */
				$query = "select * from ".$wpdb->prefix."apt_locations where email='Singapore@Singapore.com'";
	            $res = $wpdb->get_results($query);
				/* Get service id */
				$query = "select * from ".$wpdb->prefix."apt_services where service_title='Routine Tooth Extractions'";
				$res_service = $wpdb->get_results($query);
				
				/* Get provider id */
				$query = "select * from ".$wpdb->prefix."apt_providers_services where service_id='".$res_service[0]->id."'";
				$res_provider = $wpdb->get_results($query);
				
				$bookdate1 = date_i18n('Y-m-d H:i:s',strtotime("+1 month", strtotime(date_i18n('Y-m-d',strtotime($todaydate)).' '.date_i18n('H:i:s',$todaydate))));
				$bookend = date_i18n('Y-m-d H:i:s',strtotime("+30 minutes", strtotime(date_i18n('Y-m-d',strtotime($bookdate1)).' '.date_i18n('H:i:s',strtotime($bookdate1)))));
			}
			
			/* Get order id of user */
			$client_info_table = $wpdb->prefix .'apt_order_client_info';
			$sql_id="SELECT max(order_id) as max FROM ".$client_info_table;
			$get_order_id=$wpdb->get_var($sql_id);
			if($get_order_id == 0){
				$order_id = 1000;
			}else{
			$order_id = $get_order_id + 1;
			}
			
			$orderids[] =$order_id;
			$apt_user_info = array(
					'user_login'    =>   $apt_clientsinfo['client_name'],
					'user_email'    =>   $apt_clientsinfo['client_email'],
					'user_pass'     =>   '12345678',
					'first_name'    =>   $apt_clientsinfo['client_name'],
					'last_name'     =>   '',
					'nickname'      =>  '',
					'role' => 'subscriber'
					);	
	   
			$new_apt_user = wp_insert_user( $apt_user_info );
			$bdclientids[] =  $new_apt_user;
			$user = new WP_User($new_apt_user);
			$user->add_cap('read');
			$user->add_cap('apt_client'); 
			$user->add_role('apt_users');
			$user_id = $new_apt_user;
			$user_login = $preff_username;
			add_user_meta( $new_apt_user, 'apt_client_locations','#'.$res[0]->id.'#');
			
			
			
			
			
			
			
			
			for($i=0;$i<=6;$i++){
				
				$client_info_table = $wpdb->prefix .'apt_order_client_info';
				$sql_id="SELECT max(order_id) as max FROM ".$client_info_table;
				$get_order_id=$wpdb->get_var($sql_id);
				if($get_order_id == 0){
					$order_id = 1000;
				}else{
				$order_id = $get_order_id + 1;
				}
				if($i <= 2){
					$bookdate1s = date_i18n('Y-m-d H:i:s');
					$bookdate1 = date_i18n('Y-m-d H:i:s',strtotime("+1 days", strtotime(date_i18n('Y-m-d',strtotime($bookdate1s)).' '.date_i18n('H:i:s',strtotime($bookdate1s)))));
					$bookend = date_i18n('Y-m-d H:i:s',strtotime("+30 minutes", strtotime(date_i18n('Y-m-d',strtotime($bookdate1)).' '.date_i18n('H:i:s',strtotime($bookdate1)))));
				}else if($i <= 4){
					$bookdate1s = date_i18n('Y-m-d H:i:s');
					$bookdate1 = date_i18n('Y-m-d H:i:s',strtotime("+2 days", strtotime(date_i18n('Y-m-d',strtotime($bookdate1s)).' '.date_i18n('H:i:s',$bookdate1s))));
					$bookend = date_i18n('Y-m-d H:i:s',strtotime("+30 minutes", strtotime(date_i18n('Y-m-d',strtotime($bookdate1)).' '.date_i18n('H:i:s',strtotime($bookdate1)))));
				}else{
					$bookdate1s = date_i18n('Y-m-d H:i:s');
					$bookdate1 = date_i18n('Y-m-d H:i:s',strtotime("+3 days", strtotime(date_i18n('Y-m-d',strtotime($bookdate1s)).' '.date_i18n('H:i:s',$bookdate1s))));
					$bookend = date_i18n('Y-m-d H:i:s',strtotime("+30 minutes", strtotime(date_i18n('Y-m-d',strtotime($bookdate1)).' '.date_i18n('H:i:s',strtotime($bookdate1)))));
				}
				
				$query1="INSERT INTO ".$wpdb->prefix."apt_order_client_info (`id`, `business_owner_id`, `order_id`, `client_name`, `client_email`, `client_phone`, `client_personal_info`) VALUES ('', ".get_current_user_id().", '".$order_id."', '".$apt_clientsinfo['client_name']."', '".$apt_clientsinfo['client_email']."', '".$apt_clientsinfo['client_phone']."', '');";
				$add = $wpdb->query($query1);
				
				$query2 = "INSERT INTO ".$wpdb->prefix."apt_bookings (`id`, `location_id`, `order_id`, `business_owner_id`, `client_id`, `service_id`, `provider_id`, `booking_price`, `booking_datetime`, `booking_endtime`, `booking_status`, `reject_reason`, `cancel_reason`, `confirm_note`, `reschedule_note`, `reminder`, `notification`, `lastmodify`) VALUES ('', '".$res[0]->id."', '".$order_id."', ".get_current_user_id().", '".$user_id."', '".$res_service[0]->id."', '".$res_provider[0]->provider_id."', '50', '".$bookdate1."', '".$bookend."', 'A', '', '', '', '', '0', '0', NOW());";
				$add1 = $wpdb->query($query2);
				$bookingsids[] = $wpdb->insert_id;
				
				$query3 = "INSERT INTO ".$wpdb->prefix."apt_payments (`id`, `location_id`, `business_owner_id`, `client_id`, `order_id`, `payment_method`, `transaction_id`, `amount`, `discount`, `taxes`, `partial`, `net_total`, `lastmodify`) VALUES ('', '".$res[0]->id."', ".get_current_user_id().", '".$user_id."', '".$order_id."', 'pay_locally', '', '50', '0', '0', '0', '50', '')";
				$add2 = $wpdb->query($query3);
				$paymentsids[] = $wpdb->insert_id;
				
			}
			
			
			$clientcounter++;
		}
		
		
		$sampledataids = array('locationsids'=>implode(',',$locationsids),'servicesids'=>implode(',',$servicesids),'categoriesids'=>implode(',',$categoriesids),'staffsids'=>implode(',',$staffsids),'bdclientids'=>implode(',',$bdclientids),'bookingsids'=>implode(',',$bookingsids),'paymentsids'=>implode(',',$paymentsids),'orderids'=>implode(',',$orderids));
		add_option('appointment_sample_dataids' . '_' . get_current_user_id(),serialize($sampledataids));	
		update_option('appointment_sample_status' . '_' . get_current_user_id(),'N');
		$_SESSION['apt_location'] =0;
		header("Refresh:0");
	}
	/***********************************************/
	
	
	
if(isset($_POST['location_title'])){		
	$location->location_title = filter_var($_POST['location_title'], FILTER_SANITIZE_STRING);
	$location->description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
	$location->email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
	$location->phone = $_POST['phone'];
	$location->address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
	$location->city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
	$location->state = filter_var($_POST['state'], FILTER_SANITIZE_STRING);
	$location->image = $_POST['locationimage'];
	$location->zip = filter_var($_POST['zip'], FILTER_SANITIZE_STRING);
	$location->country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
	$servicecreate = $location->create();
}	

/* Get All Locations */
$location_sortby = get_option('appointment_location_sortby' . '_' . get_current_user_id());

$apt_locations = $location->readAll('','','');
$all_locations = $location->countAll();
$temp_locatio_name = array();
$all_city_state_array = array();
foreach($apt_locations as $apt_location){ 
	if($location_sortby=='city'){$locationsort = $apt_location->city;}
	else{$locationsort = $apt_location->state;}
	$all_city_state_array[]=$locationsort;					 
}
?>
<div id="apt-locations-panel" class="panel tab-content table-fixed">
	<div class="apt-locations-list table-cell col-md-3 col-sm-3 col-xs-12 col-lg-3">
		<div class="apt-locations-container">
			<ul class="nav nav-tab nav-stacked apt-left-locations">
				<li class="active apt-left-location-menu-li br-2 getsorted_locations" data-location_sortby="all">
				<span class="apt-location-sort-icon"><i class="fa fa-th"></i></span>
					<a href="javascript:void(0);" data-toggle="pill">
						<span class="apt-location-name"><?php echo __("All States/City","apt");?> (<?php echo $all_locations; ?>)</span>
					</a>
				</li>
			</ul>	
			<ul class="nav nav-tab nav-stacked apt-left-location" id="sortable-city-state">
				<?php foreach($apt_locations as $apt_location){ 
					if($location_sortby=='city'){ $locationsort = $apt_location->city;}else{ $locationsort = $apt_location->state;}
					
					if(!in_array($locationsort,$temp_locatio_name)){
						$temp_locatio_name[]=$locationsort;
						
						 $city_state_locations = array_count_values($all_city_state_array);
				
					?>
				<li class="active apt-left-location-menu-li br-2 getsorted_locations" data-location_sortby="<?php if($location_sortby=='city'){ echo $apt_location->city;}else{ echo $apt_location->state;} ?>" >
				<span class="apt-location-sort-icon"><i class="fa fa-th-list"></i></span>
					<a href="javascript:void(0);" data-toggle="pill">
						<span class="apt-location-name"><?php if($location_sortby=='city'){ echo $apt_location->city;}else{ echo $apt_location->state;} ?> (<?php echo $city_state_locations[$locationsort]; ?>)</span>
					</a>
				</li>	
			<?php } } ?>	
			</ul>
		</div>	
	</div>
	<div class="panel-body table-cell col-md-9 col-sm-9 col-xs-12 col-lg-9">
		<div class="apt-location-details tab-content col-md-12 col-sm-12 col-lg-12 col-xs-12">
			<!-- right side common menu for location -->
			<div class="apt-location-top-header">
				<span class="apt-location-name pull-left"></span>
				<div class="pull-right">
					<table>
						<tbody>
							<tr>
								<td>
									<button id="apt-add-new-location" class="btn btn-success" value="Add New Location"><i class="fa fa-plus custom-icon-space"></i><?php echo __("Add New Location","apt");?></button>
								</td>
								
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div id="hr"></div>
			<div class="tab-pane active" id=""><!-- locations list -->
				<div class="tab-content apt-locations-details">
					<div id="accordion" class="panel-group">
						<ul class="nav nav-tab nav-stacked sortable-locations" id="sortable-locations" > <!-- sortable-locations -->
						<?php foreach($apt_locations as $apt_location){ 
							$staff->location_id = $apt_location->id;
							$staffcounts = $staff->total_location_providers();
							?>
							<li id="location_detail_<?php echo $apt_location->id; ?>"><div class="panel panel-default apt-location-panel" >
								<div class="panel-heading">
									<h4 class="panel-title">
										<div class="apt-col9">
											<i class="fa fa-th-list"></i>
											<span class="apt-location-title-name"><?php echo $apt_location->location_title; ?></span>
										</div>
										<div class="pull-right apt-col3">
												
											<div class="apt-col6">
												<label for="location-list-<?php echo $apt_location->id; ?>">
													<input type="checkbox" data-id="<?php echo $apt_location->id; ?>" class="update_location_status" <?php if($apt_location->status=='E'){echo 'checked'; } ?> id="location-list-<?php echo $apt_location->id; ?>" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","apt");?>" data-off="<?php echo __("Disable","apt");?>" data-onstyle="success" data-offstyle="danger" >
												</label>
											</div>
											<div class="pull-right">
												<div class="apt-col2 p-r">
													<a data-poid="apt-popover-location<?php echo $apt_location->id; ?>" id="apt-delete-location<?php echo $apt_location->id; ?>" class="pull-right btn-circle btn-danger btn-sm apt-delete-popover" rel="popover" data-placement='bottom' title="<?php echo __("Delete this location?","apt");?>"> <i class="fa fa-trash" title="<?php echo __("Delete location","apt");?>"></i></a>
													<div class="apt-popover" id="apt-popover-location<?php echo $apt_location->id; ?>" style="display: none;">
														<div class="arrow"></div>
															<table class="form-horizontal" cellspacing="0">
																<tbody>
																	<tr>
																		<td>
																		<?php if($staffcounts>0){?>
																			<span class="apt-popover-title"><?php echo __("Unable to delete location,having linked staff","apt");?></span>
																			<?php }else{?>				
																			<button data-id="<?php echo $apt_location->id; ?>" value="Delete" class="btn btn-danger btn-sm mr-10 delete_location" type="submit"><?php echo __("Yes","apt");?></button>
																			<button data-poid="apt-popover-location<?php echo $apt_location->id; ?>" class="btn btn-default btn-sm apt-close-popover-delete" href="javascript:void(0)"><?php echo __("Cancel","apt");?></button><?php } ?>
																		</td>
																	</tr>
																</tbody>
															</table>
													</div>
												</div>
												
											<div class="apt-show-hide pull-right">
													<input type="checkbox" name="apt-show-hide" class="apt-show-hide-checkbox" id="<?php echo $apt_location->id; ?>" >
													<label class="apt-show-hide-label" for="<?php echo $apt_location->id; ?>"></label>
												</div>
											</div>
										</div>
										
									</h4>
								</div>
								<div id="" class="location_detail panel-collapse collapse detail-id_<?php echo $apt_location->id; ?>">
									<div class="panel-body">
										<div class="apt-location-collapse-div col-sm-12 col-md-7 col-lg-7 col-xs-12">
											<form id="apt_update_location_<?php echo $apt_location->id;?>" method="post" type="" class="slide-toggle apt_update_location" >
												<table class="apt-create-location-table form-group-margin">
													<tbody>

														<tr>
															<td><label for="apt-location-name"><?php echo __("Location Title","apt");?></label></td>
															<td><div class="form-group"><input type="text" class="form-control" id="apt-location-name<?php echo $apt_location->id; ?>" value="<?php echo $apt_location->location_title; ?>" name="location_title" />
															</div>
															<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Location title is used to display in frontend for bookings.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
															</td>
															
														</tr>
														
														<tr>
															<td><label for="apt-location-desc"><?php echo __("Description","apt");?></label></td>
															<td><div class="form-group">
															<textarea id="apt-location-desc<?php echo $apt_location->id; ?>" class="form-control"><?php echo $apt_location->description; ?></textarea>
															</div>
															<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Location description is used for desribe about location.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
															</td>
														</tr>
														
														<tr>
															<td><label for="apt-location-image"><?php echo __("Image","apt");?></label></td>
															<td>
																<div class="apt-location-image-uploader">
																	<img id="bdll<?php echo $apt_location->id; ?>locimage" src="<?php if($apt_location->image==''){ echo $plugin_url_for_ajax.'/assets/images/location.png';}else{
																	echo site_url()."/wp-content/uploads".$apt_location->image;
																	}?>" class="apt-location-image br-100" height="100" width="100">
																	<label <?php if($apt_location->image==''){ echo "style='display:block'"; }else{ echo "style='display:none'"; } ?> for="apt-upload-imagebdll<?php echo $apt_location->id; ?>" class="apt-location-img-icon-label show_image_icon_add<?php echo $apt_location->id; ?>">
																		<i class="apt-camera-icon-common br-100 fa fa-camera"></i>
																		<i class="pull-left fa fa-plus-circle fa-2x"></i>
																	</label>
																	<input data-us="bdll<?php echo $apt_location->id; ?>" class="hide apt-upload-images" type="file" name="" id="apt-upload-imagebdll<?php echo $apt_location->id; ?>"  />
																	
																	<a id="apt-remove-location-imagebdll<?php echo $apt_location->id; ?>" <?php if($apt_location->image!=''){ echo "style='display:block;'";}  ?> class="pull-left br-4 btn-danger apt-remove-location-img btn-xs apt_remove_image" rel="popover" data-placement='bottom' title="Remove Image?"> <i class="fa fa-trash" title="Remove location Image"></i></a>
																	
																	
																	<div style="display: none;" class="apt-popover " id="popover-apt-remove-location-imagebdll<?php echo $apt_location->id; ?>">
																		<div class="arrow"></div>
																		<table class="form-horizontal" cellspacing="0">
																			<tbody>
																				<tr>
																					<td>
																						<a href="javascript:void(0)" id="" value="Delete" data-mediaid="<?php echo $apt_location->id; ?>" data-mediasection='location' data-mediapath="<?php echo $apt_location->image;?>" data-imgfieldid="bdll<?php echo $apt_location->id;?>uploadedimg"
																						class="btn btn-danger btn-sm apt_delete_image"><?php echo __("Yes","apt");?></a>
																						<a href="javascript:void(0)" id="popover-apt-remove-location-imagebdll<?php echo $apt_location->id; ?>" class="btn btn-default btn-sm close_delete_popup" href="javascript:void(0)"><?php echo __("Cancel","apt");?></a>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</div>
																</div>	
															<div id="apt-image-upload-popupbdll<?php echo $apt_location->id; ?>" class="apt-image-upload-popup modal fade" tabindex="-1" role="dialog">
																<div class="vertical-alignment-helper">
																	<div class="modal-dialog modal-md vertical-align-center">
																		<div class="modal-content">
																			<div class="modal-header">
																				<div class="col-md-12 col-xs-12">
																					<a data-us="bdll<?php echo $apt_location->id; ?>" class="btn btn-success apt_upload_img" data-imageinputid="apt-upload-imagebdll<?php echo $apt_location->id; ?>" ><?php echo __("Crop & Save","apt");?></a>
																					<button type="button" class="btn btn-default hidemodal" data-dismiss="modal" aria-hidden="true"><?php echo __("Cancel","apt");?></button>
																				</div>	
																			</div>
																			<div class="modal-body">
																				<img id="apt-preview-imgbdll<?php echo $apt_location->id; ?>" />
																			</div>
																			<div class="modal-footer">
																				<div class="col-md-12 np">
																					<div class="col-md-4 col-xs-12">
																						<label class="pull-left"><?php echo __("File size","apt");?></label> <input type="text" class="form-control" id="bdll<?php echo $apt_location->id; ?>filesize" name="filesize" />
																					</div>	
																					<div class="col-md-4 col-xs-12">	
																						<label class="pull-left"><?php echo __("H","apt");?></label> <input type="text" class="form-control" id="bdll<?php echo $apt_location->id; ?>h" name="h" /> 
																					</div>
																					<div class="col-md-4 col-xs-12">	
																						<label class="pull-left"><?php echo __("W","apt");?></label> <input type="text" class="form-control" id="bdll<?php echo $apt_location->id; ?>w" name="w" />
																					</div>
																					<input type="hidden" id="bdll<?php echo $apt_location->id; ?>x1" name="x1" />
																					 <input type="hidden" id="bdll<?php echo $apt_location->id; ?>y1" name="y1" />
																					<input type="hidden" id="bdll<?php echo $apt_location->id; ?>x2" name="x2" />
																					<input type="hidden" id="bdll<?php echo $apt_location->id; ?>y2" name="y2" />
																					<input id="bdll<?php echo $apt_location->id; ?>bdimagetype" type="hidden" name="bdimagetype"/>
																					<input type="hidden" id="bdll<?php echo $apt_location->id; ?>bdimagename" name="bdimagename" value="" />
																					</div>
																			</div>							
																		</div>		
																	</div>			
																</div>			
															</div>
															</td>
														<input name="image" id="bdll<?php echo $apt_location->id;?>uploadedimg" type="hidden" value="<?php echo $apt_location->image; ?>" />
														</tr>
																							
														<tr>
															<td><label for="location-email<?php echo $apt_location->id; ?>"><?php echo __("Email","apt");?></label></td>
															<td><div class="form-group"><input type="email" class="form-control" id="location-email<?php echo $apt_location->id; ?>" name="email" value="<?php echo $apt_location->email; ?>"/>
															</div>
															<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Location email is used for to identify your location for business.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
															
															</td>
														</tr>
														<tr>
															<td><label for="location-phone-number<?php echo $apt_location->id; ?>"><?php echo __("Phone","apt");?></label></td>
															<td><div class="form-group">
															<input type="tel" class="form-control" id="location-phone-number<?php echo $apt_location->id; ?>" name="phone" value="<?php echo $apt_location->phone; ?>" />
															</div>
															<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Location phone is used to find location easily.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
															</td>
														</tr>
														<tr>
														<td><?php echo __("Address","apt");?></td>
														<td>
															<div class="apt-col12"><textarea class="form-control" name="address" id="apt-location-address<?php echo $apt_location->id; ?>"><?php echo $apt_location->address; ?></textarea></div>
														</td>
													</tr>
													<tr>
														<td></td>
														<td>
															<div class="apt-col6 apt-w-50">
																<label><?php echo __("City","apt");?></label>
																<input type="text" class="form-control" id="apt-location-city<?php echo $apt_location->id; ?>" name="city" placeholder="City" value="<?php echo $apt_location->city; ?>" />
															</div>
															<div class="apt-col6 apt-w-50 float-right">
																<label><?php echo __("State","apt");?></label>
																<input type="text" class="form-control" id="apt-location-state<?php echo $apt_location->id; ?>" name="state" placeholder="State" value="<?php echo $apt_location->state; ?>" />
															</div>
														</td>
													</tr>
													<tr>
														<td></td>	
														<td>	
															<div class="apt-col6 apt-w-50">
																<label><?php echo __("Zip/Postal Code","apt");?></label>
																<input type="text" class="form-control" id="apt-location-zip<?php echo $apt_location->id; ?>" name="zip" placeholder="Zip" value="<?php echo $apt_location->zip; ?>" />
															</div>	
															<div class="apt-col6 apt-w-50 float-right">
																<label><?php echo __("Country","apt");?></label>
																<input type="text" class="form-control" id="apt-location-country<?php echo $apt_location->id; ?>" name="country" placeholder="Country" value="<?php echo $apt_location->country; ?>" />
															</div>	
															
														</td>
													</tr>
													</tbody>
												</table>
										</div>
										<?php /*<div class="col-sm-12 col-md-5 col-lg-5 col-xs-12">
											<div class="apt-location-map">
												<label><?php echo __("Map Location","apt");?></label>
												<input id="pac-input" class="controls" type="text" placeholder="Search Box">
												<div id="map"></div>
											</div>
										</div> */ ?>
										<div class="col-sm-12 col-md-7 col-lg-7 col-xs-12 mt-20 mb-20">		
											<a data-location_id="<?php echo $apt_location->id; ?>" name="" class="btn btn-success apt-btn-width col-sm-offset-2 update_location"><?php echo __("Save","apt");?></a>
											<button type="reset" class="btn btn-default  apt-btn-width ml-30"><?php echo __("Reset","apt");?></button>
										</div>	
										</form>
									</div>
								</div>
							</div>
							</li>
							<?php } ?>
														
							<li>
								<!-- add new service pop up -->
								<div class="panel panel-default apt-location-panel apt-add-new-location">
									<div class="panel-heading">
										<h4 class="panel-title">
											<div class="apt-col9">
												<span class="apt-location-title-name"><?php echo __("Add New Location","apt");?></span>
											</div>
											<div class="pull-right apt-col3">				
												<div class="pull-right">
													<div class="apt-show-hide pull-right">
														<input type="checkbox" name="apt-show-hide" checked="checked" class="apt-show-hide-checkbox" id="ladd" ><!--Added Serivce Id-->
														<label class="apt-show-hide-label" for="ladd"></label>
													</div>
												</div>
											</div>											
										</h4>
									</div>
									<div id="" class="location_detail panel-collapse collapse in detail_sp3 detail-id_ladd">
										<div class="panel-body">
										<form id="apt_create_location_cl" action="" method="post" class="slide-toggle" >
											<div class="apt-location-collapse-div col-sm-12 col-md-7 col-lg-7 col-xs-12">
													<table class="apt-create-location-table form-group-margin">
														<tbody>

															<tr>
																<td><label for="apt-location-name"><?php echo __("Location Title","apt");?></label></td>
																<td><div class="form-group">
																<input type="text" name="location_title" class="form-control" id="apt-location-name" />
																</div>
																<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Location title is used to display in frontend for bookings.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																</td>
															</tr>
															
															<tr>
																<td><label for="apt-location-desc"><?php echo __("Description","apt");?></label></td>
																<td>
																<div class="form-group">
																<textarea name="description" id="apt-location-desc" class="form-control"></textarea>
																</div>
																<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Location description is used for desribe about location.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																</td>
															</tr>
															<tr>
															<td><label for="apt-location-image"><?php echo __("Image","apt");?></label></td>
															<td>
																<div class="apt-location-image-uploader">
																	<img id="bdcllocimage" src="<?php echo $plugin_url_for_ajax; ?>/assets/images/location.png" class="apt-location-image br-100" height="100" width="100">
																	<label for="apt-upload-imagebdcl" class="apt-location-img-icon-label">
																		<i class="apt-camera-icon-common br-100 fa fa-camera"></i>
																		<i class="pull-left fa fa-plus-circle fa-2x"></i>
																	</label>
																	<input data-us="bdcl" class="hide apt-upload-images" type="file" name="" id="apt-upload-imagebdcl"  />
																	
																	<a id="apt-remove-location-imagebdcl" class="pull-left br-100 btn-danger apt-remove-location-img btn-xs" rel="popover" data-placement='bottom' title="<?php echo __("Remove Image?","apt");?>"> <i class="fa fa-trash" title="<?php echo __("Remove location Image","apt");?>"></i></a>
																	<div id="popover-apt-remove-location-imagebdcl" style="display: none;">
																		<div class="arrow"></div>
																		<table class="form-horizontal" cellspacing="0">
																			<tbody>
																				<tr>
																					<td>
																						<a href="javascript:void(0)" id="" value="Delete" class="btn btn-danger btn-sm" type="submit"><?php echo __("Yes","apt");?></a>
																						<a href="javascript:void(0)" id="apt-close-popover-location-imagebdcl" class="btn btn-default btn-sm" href="javascript:void(0)"><?php echo __("Cancel","apt");?></a>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</div><!-- end pop up -->
																</div>	
													<div id="apt-image-upload-popupbdcl" class="apt-image-upload-popup modal fade" tabindex="-1" role="dialog">
														<div class="vertical-alignment-helper">
															<div class="modal-dialog modal-md vertical-align-center">
																<div class="modal-content">
																	<div class="modal-header">
																		<div class="col-md-12 col-xs-12">
																			<a data-us="bdcl" class="btn btn-success apt_upload_img" data-imageinputid="apt-upload-imagebdcl"><?php echo __("Crop & Save","apt");?></a>
																			<button type="button" class="btn btn-default hidemodal" data-dismiss="modal" aria-hidden="true"><?php echo __("Cancel","apt");?></button>
																		</div>	
																	</div>
																	<div class="modal-body">
																		<img id="apt-preview-imgbdcl" />
																	</div>
																	<div class="modal-footer">
																		<div class="col-md-12 np">
																			<div class="col-md-4 col-xs-12">
																				<label class="pull-left"><?php echo __("File size","apt");?></label> <input type="text" class="form-control" id="bdclfilesize" name="filesize" />
																			</div>	
																			<div class="col-md-4 col-xs-12">	
																				<label class="pull-left"><?php echo __("H","apt");?></label> <input type="text" class="form-control" id="bdclh" name="h" /> 
																			</div>
																			<div class="col-md-4 col-xs-12">	
																				<label class="pull-left"><?php echo __("W","apt");?></label> <input type="text" class="form-control" id="bdclw" name="w" />
																			</div>
																			<input type="hidden" id="bdclx1" name="x1" />
																								 <input type="hidden" id="bdcly1" name="y1" />
																								<input type="hidden" id="bdclx2" name="x2" />
																								<input type="hidden" id="bdcly2" name="y2" />
																								<input id="bdclbdimagetype" type="hidden" name="bdimagetype"/>
																								<input type="hidden" id="bdclbdimagename" name="bdimagename" value="" />
																		</div>
																	</div>							
																</div>		
															</div>			
														</div>			
													</div>
														</td>
													<input name="locationimage" id="bdcluploadedimg" type="hidden" value="" />
													</tr>
															<tr>
																<td><label for="location-email"><?php echo __("Email","apt");?></label></td>
																<td><div class="form-group">
																<input type="email" class="form-control" id="location-email" name="email" />
																</div>
																<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Location email is used for to identify your location for business.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																</td>
															</tr>
															<tr>
																<td><label for="location-phone-number"><?php echo __("Phone","apt");?></label></td>
																<td>
																<div class="form-group">
																<input type="tel" class="form-control" id="location-phone-number" name="phone" />
																</div>
																<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Location phone is used to find location easily.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																</td>
															</tr>
															<tr>
															<td><?php echo __("Address","apt");?></td>
															<td>
																<div class="apt-col12"><textarea class="form-control" name="address"></textarea></div>
															</td>
														</tr>
														<tr>
															<td></td>
															<td>
																<div class="apt-col6 apt-w-50">
																	<label><?php echo __("City","apt");?></label>
																	<input type="text" class="form-control" id="" name="city" placeholder="City" />
																</div>
																<div class="apt-col6 apt-w-50 float-right">
																	<label><?php echo __("State","apt");?></label>
																	<input type="text" class="form-control" id="" name="state" placeholder="State" />
																</div>
															</td>
														</tr>
														<tr>
															<td></td>	
															<td>	
																<div class="apt-col6 apt-w-50">
																	<label><?php echo __("Zip/Postal Code","apt");?></label>
																	<input type="text" class="form-control" id="" name="zip" placeholder="Zip" />
																</div>	
																<div class="apt-col6 apt-w-50 float-right">
																	<label><?php echo __("Country","apt");?></label>
																	<input type="text" class="form-control" id="" name="country" placeholder="Country" />
																</div>	
																
															</td>
														</tr>
														</tbody>
													</table>
												
											</div>
											<?php /*<div class="col-sm-12 col-md-5 col-lg-5 col-xs-12">
												<div class="apt-location-map">
													<label><?php echo __("Map Location","apt");?></label>
													<input id="pac-input" class="controls" type="text" placeholder="Search Box">
													<div id="map"></div>
												</div>
											</div> */ ?>	
											<div class="col-sm-12 col-md-7 col-lg-7 col-xs-12 mt-20 mb-20">		
												<a href="javascript:void(0)" data-location_id="cl" id="apt_create_location" name="apt_create_location" class="btn btn-success apt-btn-width col-sm-offset-2"><?php echo __("Save","apt");?></a>
												<button type="reset" class="btn btn-default  apt-btn-width ml-30"><?php echo __("Reset","apt");?></button>
											</div>	
											
										</form>
										</div>
									</div>
								</div>
							</li>
						</ul>
					</div>	
				</div>
			</div>
		</div>
	</div>
</div>
<?php 
	include(dirname(__FILE__).'/footer.php');
?>
<script>
	var locationObj={"plugin_path":"<?php echo $plugin_url_for_ajax;?>"}
</script>