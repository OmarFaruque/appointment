<?php 
session_start();
$root = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));
			if (file_exists($root.'/wp-load.php')) {
			require_once($root.'/wp-load.php');
}
if ( ! defined( 'ABSPATH' ) ) exit;  /* direct access prohibited  */

	$category = new appointment_category();
	$location = new appointment_location();
	$general = new appointment_general();
	$staff = new appointment_staff();
	$service = new appointment_service();
	$schedule = new appointment_schedule();
	$breaks = new appointment_schedule_breaks();
	$schedule_offdays = new appointment_schedule_offdays();
	$ssp = new appointment_service_schedule_price();
	$apt_image_upload= new appointment_image_upload();
	$apt_currency_symbol = get_option('appointment_currency_symbol'. '_' . get_current_user_id());
	
	$plugin_url_for_ajax = plugins_url('',dirname(dirname(__FILE__)));
	$interval= 15;
?>

<?php	
/* Remove Location Image */
if(isset($_POST['action'],$_POST['mediaid'],$_POST['mediapath']) && $_POST['action']=='delete_image'){
		$staff->id= $_POST['mediaid'];
		update_user_meta($_POST['mediaid'], 'staff_image','');
		unlink($root.'/wp-content/uploads'.$_POST['mediapath']);
}	
/* Make Staff Member as Manager */
if(isset($_POST['staff_action'],$_POST['staff_id'],$_POST['method']) && $_POST['staff_action']=='staff_as_manager' && $_POST['staff_id']!=''){
	$staff = new WP_User($_POST['staff_id']);
	if($_POST['method']=='add'){	
		$staff->add_cap('apt_manager');	
	}else{
		$staff->remove_cap('apt_manager');	
	}	
}		
/* Update Staff Schedule */	
if(isset($_POST['staff_action'],$_POST['staff_id']) && $_POST['staff_action']=='update_staff_schedule' && $_POST['staff_id']!=''){
		$schedule->provider_id = $_POST['staff_id'];
		$ins_update_status = $schedule->check_sechedule_exist_for_provider();
		$weekid=1;
		$weekday_id=1;
		$schedule_exist_st = '';
		/* Checking If Schedule Exist Or Not */
			if($_POST['staff_schedule_type']=='M'){ 
				$loopend = 35;
				if($ins_update_status==35){$schedule_exist_st='Y';}
			}else{
				if($ins_update_status==7){$schedule_exist_st='Y';}
				$loopend = 7;
			}
			
			if($schedule_exist_st==''){
				$schedule->provider_id = $_POST['staff_id'];
				$schedule->delete_staff_schedule();
			}
		 for($dl=1;$dl<=$loopend;$dl++){
			if($weekday_id>7){ $weekday_id=1;$weekid++;}
			
			$dayscheduleinfo = explode('##',$_POST['dayschdeule'][$dl-1]);
			$schedule->provider_id = $_POST['staff_id'];
			$schedule->weekday_id = $weekday_id;
			$schedule->week_id = $weekid;
			if($dayscheduleinfo[2]=='N'){
				$schedule->daystart_time=$dayscheduleinfo[0];
				$schedule->dayend_time=$dayscheduleinfo[1];;
				$schedule->off_day='';
			}else{
				$schedule->daystart_time='Y';
				$schedule->dayend_time='Y';
				$schedule->off_day='Y';
			}
			
			/* Inserting/Updating Staff Schedule */	
			if($schedule_exist_st=='Y'){
				$apt_schedule_run = $schedule->update();
			}else{				
				$apt_schedule_run = $schedule->create();
			} 
			$weekday_id++;
		} 		
}
/* Create New Staff Member */	
if(isset($_POST['staff_action'],$_POST['staff_id']) && $_POST['staff_action']=='update_staff_detail' && $_POST['staff_id']!=''){
		
		$StaffExistingImage = get_user_meta($_POST['staff_id'], 'staff_image');
		if(isset($StaffExistingImage[0]) && $StaffExistingImage[0]!='' && $StaffExistingImage[0]!=$_POST['staff_image']){
			unlink($root.'/wp-content/uplooct_all_staffads'.$_POST['staff_image']);
		}
		
		
		wp_update_user( array( 'ID' => $_POST['staff_id'], 'display_name' => filter_var($_POST['staff_name'], FILTER_SANITIZE_STRING) ) );
		update_user_meta($_POST['staff_id'], 'staff_phone',$_POST['staff_phone']);
		update_user_meta($_POST['staff_id'], 'staff_description',filter_var($_POST['staff_description'], FILTER_SANITIZE_STRING));
		update_user_meta($_POST['staff_id'], 'schedule_type',$_POST['staff_schedule_type']);
		update_user_meta($_POST['staff_id'], 'staff_image',$_POST['staff_image']);
		update_user_meta($_POST['staff_id'], 'staff_status',$_POST['staff_status']);
		update_user_meta($_POST['staff_id'], 'staff_timezone',$_POST['staff_timezone']);
		update_user_meta($_POST['staff_id'], 'staff_timezoneID',$_POST['staff_timezoneID']);
}	
if(isset($_POST['staff_action']) && $_POST['staff_action']=='create_staff'){	

		if(isset($_POST['usertype']) && $_POST['usertype']=='N'){
		$userdata = array(
					'user_login'    =>   filter_var($_POST['staff_username'], FILTER_SANITIZE_STRING),
					'user_email'    =>   filter_var($_POST['staff_email'], FILTER_SANITIZE_EMAIL),
					'user_pass'     =>   $_POST['staff_password'],
					'first_name'    =>   filter_var($_POST['staff_fullname'], FILTER_SANITIZE_STRING),
					'last_name'     =>   '',
					'nickname'      =>  '', /* used as middle name */
					'role' 			=> 'apt_staff'
					);
					
		$user_id = wp_insert_user( $userdata );
		}else{
		$user_id = $_POST['existing_userid'];
		}
		$user = new WP_User($user_id);
		$user->add_cap('apt_staff');

		/*All Existing meta*/
		$staff_location = get_user_meta( $user_id, 'staff_location', true );
		$staff_phone = get_user_meta( $user_id, 'staff_phone', true );
		$staff_description = get_user_meta( $user_id, 'staff_description', true );
		$schedule_type = get_user_meta( $user_id, 'schedule_type', true );
		$staff_image = get_user_meta( $user_id, 'staff_image', true );
		$staff_status = get_user_meta( $user_id, 'staff_status', true );
		$staff_timezone = get_user_meta( $user_id, 'staff_timezone', true );
		$staff_timezoneID = get_user_meta( $user_id, 'staff_timezoneID', true );
		$staff_bwid = get_user_meta( $user_id, 'staff_bwid', true );

		if($staff_location){update_usermeta( $user_id, 'staff_location', $_SESSION['apt_location']);}else{add_user_meta($user_id, 'staff_location',$_SESSION['apt_location']);}  
		if($staff_phone){update_usermeta( $user_id, 'staff_phone', '');}else{add_user_meta($user_id, 'staff_phone','');}  
		if($staff_description){update_usermeta( $user_id, 'staff_description', '');}else{add_user_meta($user_id, 'staff_description','');}  
		if($schedule_type){update_usermeta( $user_id, 'schedule_type', 'W');}else{add_user_meta($user_id, 'schedule_type','W');}  
		if($staff_image){update_usermeta( $user_id, 'staff_image', '');}else{add_user_meta($user_id, 'staff_image','');}  
		if($staff_status){update_usermeta( $user_id, 'staff_status', 'E');}else{add_user_meta($user_id, 'staff_status','E');}  
		if($staff_timezone){update_usermeta( $user_id, 'staff_timezone', '');}else{add_user_meta($user_id, 'staff_timezone','');}  
		if($staff_timezoneID){update_usermeta( $user_id, 'staff_timezoneID', '');}else{add_user_meta($user_id, 'staff_timezoneID','');}  
		if($staff_bwid){update_usermeta( $user_id, 'staff_bwid', get_current_user_id());}else{add_user_meta($user_id, 'staff_bwid', get_current_user_id());}  

		
		for($cs=1;$cs<=7;$cs++){
			$schedule->provider_id = $user_id;
			$schedule->weekday_id = $cs;
			$schedule->week_id = 1;
			$schedule->daystart_time='08:00:00';
			$schedule->dayend_time='17:00:00';
			$schedule->off_day='';
			$apt_schedule_run = $schedule->create();
		}
		
		$staff->location_id = $_SESSION['apt_location'];	
		$apt_all_staff = $staff->readAll_with_disables();	
		foreach($apt_all_staff as $apt_staff){ ?>
			<li class="staff-list br-2 omar" data-staff_id="<?php echo $apt_staff['id']; ?>" id="staff_detail_<?php echo $apt_staff['id']; ?>">
						<a href="javascript:void(0)" data-toggle="pill">
						<!--<span class="apt-staff-clone"><button class="btn btn-circle btn-success pull-right apt-clone-staff" data-pid="<?php //echo $apt_staff['id']; ?>" title="Clone Staff Member"><i class="fa fa-clone"></i></button></span>-->
						<span class="apt-staff-image"><img class="apt-staf-img-small" src="<?php if($apt_staff['image']==''){ echo $plugin_url_for_ajax.'/assets/images/staff.png';}else{
						echo site_url()."/wp-content/uploads".$apt_staff['image'];}?>" /></span>
						<span class="apt-staff-name f-letter-capitalize"><?php echo $apt_staff['staff_name']; ?></span>
						</a>
						<span class="apt-manager-star">
							<input <?php if(isset($apt_staff['caps']['apt_manager'])){ echo "checked='checked'"; } ?> type="checkbox" data-staff_id="<?php echo $apt_staff['id']; ?>" id="apt_staff_manager<?php echo $apt_staff['id']; ?>" class="apt-checkbox apt_staff_manager" />
							<label for="apt_staff_manager<?php echo $apt_staff['id']; ?>" title="Manager"><span><i class="fa fa-star"></i><br/ ><span class="apt-text"></span></span></label>
						</span>
			</li>
	<?php	}  
}

/* Delete Staff Member */
if(isset($_POST['staff_action'],$_POST['staff_id']) && $_POST['staff_action']=='delete_staff_member' && $_POST['staff_id']!=''){
		$staff->id = $_POST['staff_id'];
		$staff->delete();	
		$staff->location_id = $_SESSION['apt_location'];
		$all_existing_users = $staff->readAll_existing_users();
		$apt_all_staff = $staff->readAll_with_disables();
		$location_all_staff = $staff->countAll();
		
	?>
	<h3><?php echo __("Staff Members","apt");?> <span>(<?php echo $location_all_staff;?>)</span>
					<button id="apt-add-new-staff" class="pull-right btn btn-circle btn-info" rel="popover" data-placement='bottom' title="Add New Staff Member"> <i class="fa fa-user-plus"></i></button>
					
					
					<div id="popover-content-wrapper" style="display: none">
						<div class="arrow"></div>
					 <form id="apt_create_staff" method="post" action="">
					  <table class="form-horizontal" cellspacing="0">
						<tbody>
						<tr>
							<td>
							<div class="pull-right apt-custom-radio">
								<ul class="apt-radio-list">
									<li>
										<input type="radio" id="apt-new-user" class="apt-radio apt-new-usercl" name="staff-new-exist-user" value="N" />
										<label for="apt-new-user"><span></span><?php echo __("New User","apt");?></label>
									</li>
								</ul>
							</div>
							</td>
							<td>
								<div class="pull-right apt-custom-radio">
									<ul class="apt-radio-list">
										<li>
											<input type="radio" id="apt-existing-user" class="apt-radio apt-existing-usercl" name="staff-new-exist-user" value="E" />
										<label for="apt-existing-user"><span></span><?php echo __("Existing User","apt");?> </label>
										</li>

									</ul>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<label for="apt-select-user" class="apt-existing-user-data hide-div" ><?php echo __("User","apt");?></label>
							</td>
							<td><div class="apt-existing-user-data hide-div">	
								<select class="form-control" name="apt_selected_wpuser" id="apt-selected-wp-user">
									<option value=""><?php echo __("Select from WP users","apt");?></option>
									<?php foreach($all_existing_users as $single_existing_user){ ?> 
									<option value="<?php echo $single_existing_user->ID; ?>" ><?php echo $single_existing_user->display_name; ?></option> <?php } ?>
								</select>
								</div>
							</td>
						</tr>
						<tr class="form-field form-required">
							<td><label for=""  class="apt-new-user-data hide-div"><?php echo __("Username","apt");?></label></td>
							<td><div class="apt-new-user-data hide-div">	<input type="text" class="form-control" id="apt-staff-username" name="apt_newuser_username" required="required" /></div></td>
						</tr>
						<tr class="form-field form-required">
							<td><label for="apt-staff-password"  class="apt-new-user-data hide-div"><?php echo __("Password","apt");?></label></td>
							<td><div class="apt-new-user-data hide-div">	<input type="password" class="form-control" id="apt-staff-password" name="apt_newuser_password" required="required" /></div></td>
						</tr>
						<tr class="form-field form-required">
							<td><label class="apt-new-user-data hide-div" for="ab-newstaff-fullname"><?php echo __("Full name","apt");?></label></td>
							<td><input type="text" class="form-control apt-new-user-data hide-div" id="apt-staff-fullname" name="apt_newuser_fullname" required="required" /></td>
						</tr>
						<tr class="form-field form-required">
							<td><label class="apt-new-user-data hide-div" for="ab-newstaff-fullname"><?php echo __("Email","apt");?></label></td>
							<td><input type="email" class="form-control apt-new-user-data hide-div" id="apt-staff-email" name="apt_newuser_email" required="required" /></td>
						</tr>
						<tr>
							<td></td>
							<td>
								<a id="apt_create_staff_btn" value="Create Staff" class="btn btn-info" href="javascript:void(0)"><?php echo __("Create","apt");?></a>
								<a id="apt-close-popover-new-staff" class="btn btn-default" href="javascript:void(0)"><?php echo __("Cancel","apt");?></a>
							</td>
						</tr>
						</tbody>
					</table>
					</form>
					</div>
					
				</h3><!-- end popover -->
				
				<ul class="nav nav-tab nav-stacked apt-left-staff" id="apt-staff-sortable">
					<?php foreach($apt_all_staff as $apt_staff){ ?>
					<li class="staff-list br-2" data-staff_id="<?php echo $apt_staff['id']; ?>" id="staff_detail_<?php echo $apt_staff['id']; ?>">
						<a href="javascript:void(0)" data-toggle="pill">
						<!-- <span class="apt-staff-clone"><button class="btn btn-circle btn-success pull-right apt-clone-staff" data-pid="<?php echo $apt_staff['id']; ?>" title="<?php //echo __("Clone Staff Member","apt");?>"><i class="fa fa-clone"></i></button></span> -->
						<span class="apt-staff-image"><img class="apt-staf-img-small" src="<?php if($apt_staff['image']==''){ echo $plugin_url_for_ajax.'/assets/images/staff.png';}else{
						echo site_url()."/wp-content/uploads".$apt_staff['image'];}?>" /></span>
						<span class="apt-staff-name"><?php echo $apt_staff['staff_name']; ?></span>
						</a>
						<?php if(current_user_can('manage_options')){?>
						<span class="apt-manager-star">
							<input <?php if(isset($apt_staff['caps']['apt_manager'])){ echo "checked='checked'"; } ?> type="checkbox" data-staff_id="<?php echo $apt_staff['id']; ?>" id="apt_staff_manager<?php echo $apt_staff['id']; ?>" class="apt-checkbox apt_staff_manager" />
							<label for="apt_staff_manager<?php echo $apt_staff['id']; ?>" title="Manager"><span><i class="fa fa-star"></i><br/ ><span class="apt-text"></span></span></label>
						</span><?php } ?>
					</li>
					<?php } ?>
					

				</ul>
	<?php
}


/* Ajax Response Releated To Staff Service Schedule Price */
if(isset($_POST['staff_action'],$_POST['staff_id']) && $_POST['staff_action']=='add_service_schedule_price' && $_POST['staff_id']!=''){
			$ssp->provider_id = $_POST['staff_id'];
			$ssp->service_id = $_POST['service_id'];
			$ssp->ssp_starttime = '08:00:00';
			$ssp->ssp_endtime = '08:30:00';
			$ssp->weekid = $_POST['weekid'];
			$ssp->weekdayid = $_POST['dayid'];
			$ssp->ssp_price = $_POST['service_amount'];
			$ssp_id = $ssp->create();
			?>
					

			<li class="fullwidth bb1f0" id="apt_ssp_detail_<?php echo $ssp_id; ?>">
			<span class="col-sm-5 col-md-12 col-lg-5 col-xs-12 apt-staff-price-schedule np">
				<ul class="list-unstyled">
					<li>
						<select id="ssp_starttime_<?php echo $ssp_id; ?>" name="ssp_starttime_<?php echo $ssp_id; ?>" class="selectpicker ssp_starttime" data-sspid="<?php echo $ssp_id; ?>" data-size="10"  style="display: none;">
							<?php $min =0;
							while($min < 1440)
							{																	
							if($min==1440) {		
							$timeValue = date_i18n('G:i:s',mktime(0,$min-1,0,1,1,2015)); 						
							} else {				
							$timeValue = date_i18n('G:i:s',mktime(0,$min,0,1,1,2015)); 								}								
							$timetoprint = date_i18n('G:i:s',mktime(0,$min,0,1,1,2014)); ?>
							
							<option  value="<?php echo $timeValue; ?>" <?php if ( $timetoprint=='8:00:00'){ echo "selected";}?> ><?php echo date_i18n(get_option('time_format'),strtotime($timetoprint)); ?></option>
							<?php 								  
							  $min = $min+$interval;
							} ?>
						</select>
					  
						<span class="apt-price-hours-to"><?php echo __("to","apt");?>  </span>
						<select id="ssp_endtime_<?php echo $ssp_id; ?>" name="ssp_endtime_<?php echo $ssp_id; ?>" class="selectpicker" data-sspid="" data-size="10" style="display: none;">
							<?php $min =0;
							while($min < 1440)
							{																	
							if($min==1440) {		
							$timeValue = date_i18n('G:i:s',mktime(0,$min-1,0,1,1,2015)); 						
							}else{				
							$timeValue = date_i18n('G:i:s',mktime(0,$min,0,1,1,2015)); 								
							}								
							$timetoprint = date_i18n('G:i:s',mktime(0,$min,0,1,1,2014)); ?>																													
							<option  value="<?php echo $timeValue; ?>"  <?php if ( $timetoprint=='8:15:00'){ echo "selected";}?> ><?php echo date_i18n(get_option('time_format'),strtotime($timetoprint)); ?></option>
							<?php 								  
							  $min = $min+$interval;
							} ?>
						</select>
					</li>
				</ul>	
			</span>
			<span class="col-sm-7 col-md-12 col-lg-7 col-xs-12 npr">
				<table  class="apt-staff-common-table">
					<tbody>
						<tr class="col-lg-7 col-sm-6 col-xs-6 npr">
						<td class="col-xs-4"><?php echo __("Price","apt");?></td>
							<td class="col-xs-8"><div class="input-group"><span class="input-group-addon"><?php echo $apt_currency_symbol; ?></span><input type="text" id="ssp_price_<?php echo $ssp_id; ?>" class="form-control" value="<?php echo $_POST['service_amount']; ?>" placeholder="<?php echo __("$10","apt");?>" /></div></td>
						</tr>
						<tr class="col-lg-5 col-sm-6 col-xs-6 npr">
							<td class="col-xs-6"><a id="apt-delete-staff-price<?php echo $ssp_id; ?>" data-sspid="<?php echo $ssp_id; ?>" class="pull-right btn btn-circle btn-default delete_ssp_popover" rel="popover" data-placement='bottom' title="<?php echo __("Are You Sure?","apt");?>"> <i class="fa fa-trash"></i></a>
								<div id="popover-delete-price<?php echo $ssp_id; ?>" style="display: none;">
									<div class="arrow"></div>
									<table class="form-horizontal" cellspacing="0">
										<tbody>
											<tr>
												<td>
													<a id="<?php echo $ssp_id; ?>" value="Delete" class="btn btn-danger delete_ssp"><?php echo __("Yes","apt");?></a>
													<a id="apt-close-popover-delete-price<?php echo $ssp_id; ?>" class="btn btn-default cancel_ssp_delete" href="javascript:void(0)"><?php echo __("Cancel","apt");?></a>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</td>
							<td class="col-xs-6"><a href="javascript:void(0)" data-sspid="<?php echo $ssp_id; ?>" class="pull-right btn btn-circle btn-success update_ssp_detail" title="Save"> <i class="fa fa-save"></i></a></td>
						</tr>
						
					</tbody>
				</table>	
			</span>
		</li><?php
			
}
if(isset($_POST['staff_action'],$_POST['ssp_starttime']) && $_POST['staff_action']=='staff_get_ssp_end' && $_POST['ssp_starttime']!=''){
	$sspendtime = strtotime("+15 minutes", strtotime($_POST['ssp_starttime']));
	echo $ssp_endtime = date_i18n('G:i:s',$sspendtime);die();	
}

if(isset($_POST['staff_action'],$_POST['schedule_starttime']) && $_POST['staff_action']=='staff_get_schedule_end' && $_POST['schedule_starttime']!=''){
	$scheduleendtime = strtotime("+15 minutes", strtotime($_POST['schedule_starttime']));
	echo $schedule_endtime = date_i18n('G:i:s',$scheduleendtime);die();	
}

if(isset($_POST['staff_action'],$_POST['ssp_id']) && $_POST['staff_action']=='update_service_schedule_price' && $_POST['ssp_id']!=''){
	$ssp->id = $_POST['ssp_id'];	
	$ssp->ssp_starttime = $_POST['ssp_starttime'];	
	$ssp->ssp_endtime = $_POST['ssp_endtime'];	
	$ssp->ssp_price = $_POST['ssp_price'];	
	$ssp->update();
}
if(isset($_POST['staff_action'],$_POST['ssp_id']) && $_POST['staff_action']=='delete_ssp' && $_POST['ssp_id']!=''){
	$ssp->id = $_POST['ssp_id'];
	$ssp->delete();
}
if(isset($_POST['staff_action'],$_POST['staff_id']) && ($_POST['staff_action']=='link_service' || $_POST['staff_action']=='unlink_service') && $_POST['staff_id']!=''){
	$service->location_id = $_SESSION['apt_location'];
	$service->business_owner_id = $_POST['bwid'];
	$apt_services = $service->readAll();
	if($_POST['service_id']=='all'){
		
			foreach($apt_services as $apt_service){
				$service->provider_id = $_POST['staff_id'];
				$service->id = $apt_service->id;
				if($_POST['staff_action']=='link_service'){
				/* $service->unlink_service_providers(); */
				$service->link_service_providers();
				}else{
				$service->unlink_service_providers();
				}
			}
		}else{
			
			$service->provider_id = $_POST['staff_id'];
			$service->id = $_POST['service_id'];
			if($_POST['staff_action']=='link_service'){
			$service->link_service_providers();
			}else{
			$service->unlink_service_providers();
			}
	}
}


/* Ajax Response Releated To Breaks */
if(isset($_POST['staff_action'],$_POST['break_id']) && $_POST['staff_action']=='staff_update_break' && $_POST['break_id']!=''){
		
			$breaks->id = $_POST['break_id'];
			$breaks->break_start = $_POST['break_start'];
			$breaks->break_end = $_POST['break_end'];
			$break_id = $breaks->update();

}
if(isset($_POST['staff_action'],$_POST['break_start']) && $_POST['staff_action']=='staff_get_break_end' && $_POST['break_start']!=''){
	$endtime = strtotime("+15 minutes", strtotime($_POST['break_start']));
	echo $breakendtime = date_i18n('G:i:s',$endtime);die();	
}
if(isset($_POST['staff_action'],$_POST['break_id']) && $_POST['staff_action']=='staff_delete_break' && $_POST['break_id']!=''){
			$breaks->id = $_POST['break_id'];
			$break_id = $breaks->delete_break();
}

if(isset($_POST['staff_action'],$_POST['staff_id']) && $_POST['staff_action']=='staff_add_break' && $_POST['staff_id']!=''){
			
			$breaks->provider_id = $_POST['staff_id'];
			$breaks->week_id = $_POST['weekid'];
			$breaks->weekday_id = $_POST['dayid'];
			$breaks->break_start = '8:00:00';
			$breaks->break_end = '8:15:00';
			$break_id = $breaks->create();
			?>
			<li id="staff_break_<?php echo $break_id; ?>">
			<select id="staff_breakstart_<?php echo $break_id; ?>" name="staff_breakstart_<?php echo $break_id; ?>" data-bid="<?php echo $break_id; ?>" data-bv="start" class="selectpicker staff_schedule_break" data-size="10" style="display: none;">
				<?php $min =0;
				while($min < 1440)
				{																	
				if($min==1440) {		
				$timeValue = date_i18n('G:i:s',mktime(0,$min-1,0,1,1,2015)); 						
				} else {				
				$timeValue = date_i18n('G:i:s',mktime(0,$min,0,1,1,2015)); 								}								
				$timetoprint = date_i18n('G:i:s',mktime(0,$min,0,1,1,2014)); ?>
				
				<option  value="<?php echo $timeValue; ?>"  <?php if ( $timetoprint=='8:00:00'){ echo "selected";}?> ><?php echo date_i18n(get_option('time_format'),strtotime($timetoprint)); ?></option>
				<?php 								  
				  $min = $min+$interval;
				} ?>
				
		</select>
		<span class="apt-staff-hours-to"><?php echo __("to","apt");?>  </span>
			<select id="staff_breakend_<?php echo $break_id; ?>" name="staff_breakend_<?php echo $break_id; ?>" data-bid="<?php echo $break_id; ?>" data-bv="end" class="selectpicker staff_schedule_break" data-size="10" style="display: none;">
				<?php $min =0;
				while($min < 1440)
				{																	
				if($min==1440) {		
				$timeValue = date_i18n('G:i:s',mktime(0,$min-1,0,1,1,2015)); 						
				} else {				
				$timeValue = date_i18n('G:i:s',mktime(0,$min,0,1,1,2015)); 								}								
				$timetoprint = date_i18n('G:i:s',mktime(0,$min,0,1,1,2014)); ?>
				
				<option  value="<?php echo $timeValue; ?>"  <?php if ( $timetoprint=='8:15:00'){ echo "selected";}?> ><?php echo date_i18n(get_option('time_format'),strtotime($timetoprint)); ?></option>
				<?php 								  
				  $min = $min+$interval;
				} ?>
			</select>
					
		<button id="apt-delete-staff-break<?php echo $break_id; ?>" data-bid="<?php echo $break_id; ?>" class="pull-right btn btn-circle btn-danger staff_delete_break" rel="popover" data-placement='bottom' title="<?php echo __("Are You Sure?","apt");?>"> <i class="fa fa-trash"></i></button>
		<div id="popover-delete-breaks<?php echo $break_id; ?>" style="display: none;">
			<div class="arrow"></div>
			<table class="form-horizontal" cellspacing="0">
				<tbody>
					<tr>
						<td>
							<a href="javascript:void(0)" id="<?php echo $break_id; ?>" value="Delete" class="btn btn-danger delete_staff_break" ><?php echo __("Yes","apt");?></a>
							<button data-bid="<?php echo $break_id; ?>" id="apt-close-popover-delete-breaks<?php echo $break_id; ?>" class="btn btn-default close_break_del_popover" href="javascript:void(0)"><?php echo __("Cancel","apt");?></button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</li>
	<?php	
}
/** Ajax Response Releated To Staff Off Time Module **/
if(isset($_POST['staff_action'],$_POST['staff_id']) && $_POST['staff_action']=='add_staff_offtime' && $_POST['staff_id']!=''){
	$bwid = $_POST['bwid'];
	$breaks->provider_id = $_POST['staff_id'];
	$breaks->offtime_start = $_POST['offtime_start'];
	$breaks->offtime_end = $_POST['offtime_end'];
	$offtime_id = $breaks->create_offtime();

	$breaks->provider_id = $_POST['staff_id'];
	$apt_offtimes = $breaks->read_offtime();
	foreach($apt_offtimes as $offtime) { ?>
		<tr id="offtime_detail_<?php echo $offtime->id; ?>">
			<td> <?php echo date_i18n(get_option('appointment_datepicker_format'.'_'.$bwid),strtotime($offtime->offtime_start)); ?></td>
			<td><?php echo date_i18n(get_option('time_format'),strtotime($offtime->offtime_start)); ?></td>
			<td><?php echo date_i18n(get_option('appointment_datepicker_format'.'_'.$bwid),strtotime($offtime->offtime_end)); ?></td>
			<td><?php echo date_i18n(get_option('time_format'),strtotime($offtime->offtime_end)); ?></td>
			<td><a href="javascript:void(0)" data-otid="<?php echo $offtime->id; ?>" class='btn btn-danger left-margin delete_staff_offtime'><span class='glyphicon glyphicon-remove'></span></a></td>
		</tr>
	<?php }
}
if(isset($_POST['staff_action'],$_POST['offtime_id']) && $_POST['staff_action']=='delete_staff_offtime' && $_POST['offtime_id']!=''){
			$breaks->offtime_id = $_POST['offtime_id'];
			$breaks->delete_offtime();
}


/** Ajax Response Releated To Staff Offdays **/
if(isset($_POST['staff_action'],$_POST['staff_id']) && $_POST['staff_action']=='staff_add_offdays' && $_POST['staff_id']!=''){
		$schedule_offdays->provider_id = $_POST['staff_id'];
		if(isset($_POST['off_year_month'])) {
			$schedule_offdays->off_year_month = $_POST['off_year_month'];
			$schedule_offdays->create_monthoff();
		}else{ 
			$schedule_offdays->off_date = $_POST['off_date'];
			$schedule_offdays->create(); 
		} 			
} 
if(isset($_POST['staff_action'],$_POST['staff_id']) && $_POST['staff_action']=='staff_delete_offdays' && $_POST['staff_id']!=''){
		$schedule_offdays->provider_id = $_POST['staff_id'];
		if(isset($_POST['off_year_month'])) {
			$schedule_offdays->off_year_month = $_POST['off_year_month'];
			$schedule_offdays->delete_monthoff();
	    }else{ 
		   $schedule_offdays->off_date = $_POST['off_date'];
		   $schedule_offdays->delete_offday();
		} 			
} 
if(isset($_POST['staff_action'],$_POST['staff_id']) && $_POST['staff_action']=='get_staff_right' && $_POST['staff_id']!=''){
	$bwid = $_POST['bwid'];
	$staff->id = $_POST['staff_id'];
	$apt_all_staff = $staff->readOne();
	/* Get All Services */
	$service->location_id = $_SESSION['apt_location'];
	$service->business_owner_id = get_current_user_id();
	$apt_services = $service->readAll();
	
	$schedule->provider_id = $_POST['staff_id'];
	$ins_update_status = $schedule->check_sechedule_exist_for_provider();
	
	
	if($apt_all_staff[0]['schedule_type']=='M'){$wl_end=5;}else{$wl_end=1;}
	$service->provider_id = $apt_all_staff[0]['id'];
	?>	
			<div class="apt-staff-top-header">
				<span class="apt-staff-member-name pull-left f-letter-capitalize" data-staff_id="<?php echo $apt_all_staff[0]['id']; ?>"><?php echo $apt_all_staff[0]['staff_name']; ?></span>
				
				<button id="apt-delete-staff-member" class="pull-right btn btn-circle btn-danger" rel="popover" data-placement='bottom' title="<?php echo __("Services","apt");?>Delete Member?"> <i class="fa fa-trash"></i></button>
				
				
				<div id="popover-delete-member" style="display: none;">
					<div class="arrow"></div>
					<?php if($service->total_staff_services()>0){?>
						<span><?php echo __("Unable to delete staff,having linked services","apt");?> </span>
					<?php }else{?>
					<table class="form-horizontal" cellspacing="0">
						<tbody>
							<tr>
								<td>
									<button data-staff_id="<?php echo $apt_all_staff[0]['id']; ?>" id="delete_staff" value="Delete" class="btn btn-danger" type="submit"><?php echo __("Yes","apt");?></button>
									<button id="apt-close-popover-delete-staff" class="btn btn-default" href="javascript:void(0)"><?php echo __("Cancel","apt");?></button>
								</td>
							</tr>
						</tbody>
					</table><?php } ?>
				</div>
						
			</div>
			<hr id="hr" />
			<ul class="nav nav-tabs nav-justified apt-staff-right-menu">
				<li class="active"><a href="#member-details" data-toggle="tab"><?php echo __("Details","apt");?></a></li>
				<li><a href="#member-services" data-toggle="tab"><?php echo __("Services","apt");?></a></li>
				<li><a href="#member-availabilty" data-toggle="tab"><?php echo __("Availabilty","apt");?></a></li>
				<li><a href="#member-addbreaks" data-toggle="tab"><?php echo __("Add Breaks","apt");?></a></li>
				<li><a href="#member-offtime" data-toggle="tab"><?php echo __("Off Time","apt");?></a></li>
				<li><a href="#member-offdays" data-toggle="tab"><?php echo __("Off Days","apt");?></a></li>
			</ul>
			
			
			<div class="tab-pane active" id="demo-andrew"><!-- first staff nmember -->
			
				<div class="container-fluid tab-content apt-staff-right-details">
					
					<div class="tab-pane active col-lg-12 col-md-12 col-sm-12 col-xs-12 member-details" id="member-details">
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
							<div class="apt-member-image-uploader">
						
								<img id="bdsdu<?php echo $apt_all_staff[0]['id']; ?>locimage" src="<?php if($apt_all_staff[0]['image']==''){ echo $plugin_url_for_ajax.'/assets/images/staff.png';}else{
								echo site_url()."/wp-content/uploads".$apt_all_staff[0]['image'];
								}?>" class="apt-staff-image br-100" height="100" width="100">
											<label for="apt-upload-imagebdsdu<?php echo $apt_all_staff[0]['id']; ?>" <?php if($apt_all_staff[0]['image']==''){ echo "style='display:block'"; }else{ echo "style='display:none'"; } ?> class="apt-staff-img-icon-label show_image_icon_add<?php echo $apt_all_staff[0]['id']; ?>">
												<i class="apt-camera-icon-common br-100 fa fa-camera"></i>
												<i class="pull-left fa fa-plus-circle fa-2x"></i>
											</label>
											<input data-us="bdsdu<?php echo $apt_all_staff[0]['id']; ?>" class="hide apt-upload-images" type="file" name="" id="apt-upload-imagebdsdu<?php echo $apt_all_staff[0]['id']; ?>"  />
											
											<a id="apt-remove-staff-imagebdsdu<?php echo $apt_all_staff[0]['id']; ?>" <?php if($apt_all_staff[0]['image']==''){ echo "style='display:none;'";}  ?> class="pull-left br-100 btn-danger apt-remove-staff-img btn-xs apt_remove_image" rel="popover" data-placement='bottom' title="<?php echo __("Remove Image?","apt");?>"> <i class="fa fa-trash" title="<?php echo __("Remove Staff Image","apt");?>"></i></a>
											<div style="display: none;" class="apt-popover br-5" id="popover-apt-remove-staff-imagebdsdu<?php echo $apt_all_staff[0]['id']; ?>">
											<span class="apt-popover-title"><?php echo __("Delete Image","apt");?></span>
												<span class="apt-popover-content">
													<div class="apt-arrow"></div>
													<a href="javascript:void(0)" value="Delete" data-mediaid="<?php echo $apt_all_staff[0]['id']; ?>" data-mediasection='staff' data-mediapath="<?php echo $apt_all_staff[0]['image'];?>" data-imgfieldid="bdsdu<?php echo $apt_all_staff[0]['id']; ?>uploadedimg"	
													class="btn btn-danger btn-sm apt_delete_image"><?php echo __("Yes","apt");?></a>
													<a href="javascript:void(0)" id="popover-apt-remove-staff-imagebdsdu<?php echo $apt_all_staff[0]['id']; ?>" class="btn btn-default btn-sm close_delete_popup" href="javascript:void(0)"><?php echo __("Cancel","apt");?></a>
											</span>
											</div>
					
							</div>	
							<div id="apt-image-upload-popupbdsdu<?php echo $apt_all_staff[0]['id']; ?>" class="apt-image-upload-popup modal fade" tabindex="-1" role="dialog">
											<div class="vertical-alignment-helper">
												<div class="modal-dialog modal-md vertical-align-center">
													<div class="modal-content">
														<div class="modal-header">
															<div class="col-md-12 col-xs-12">
																<a data-us="bdsdu<?php echo $apt_all_staff[0]['id']; ?>" class="btn btn-success apt_upload_img" data-imageinputid="apt-upload-imagebdsdu<?php echo $apt_all_staff[0]['id']; ?>" ><?php echo __("Crop & Save","apt");?></a>
																<button type="button" class="btn btn-default hidemodal" data-dismiss="modal" aria-hidden="true"><?php echo __("Cancel","apt");?></button>
															</div>	
														</div>
														<div class="modal-body">
															<img id="apt-preview-imgbdsdu<?php echo $apt_all_staff[0]['id']; ?>" />
														</div>
														<div class="modal-footer">
															<div class="col-md-12 np">
																<div class="col-md-4 col-xs-12">
																	<label class="pull-left"><?php echo __("File size","apt");?></label> <input type="text" class="form-control" id="bdsdu<?php echo $apt_all_staff[0]['id']; ?>filesize" name="filesize" />
																</div>	
																<div class="col-md-4 col-xs-12">	
																	<label class="pull-left"><?php echo __("H","apt");?></label> <input type="text" class="form-control" id="bdsdu<?php echo $apt_all_staff[0]['id']; ?>h" name="h" /> 
																</div>
																<div class="col-md-4 col-xs-12">	
																	<label class="pull-left"><?php echo __("W","apt");?></label> <input type="text" class="form-control" id="bdsdu<?php echo $apt_all_staff[0]['id']; ?>w" name="w" />
																</div>
																<input type="hidden" id="bdsdu<?php echo $apt_all_staff[0]['id']; ?>x1" name="x1" />
																 <input type="hidden" id="bdsdu<?php echo $apt_all_staff[0]['id']; ?>y1" name="y1" />
																<input type="hidden" id="bdsdu<?php echo $apt_all_staff[0]['id']; ?>x2" name="x2" />
																<input type="hidden" id="bdsdu<?php echo $apt_all_staff[0]['id']; ?>y2" name="y2" />
																<input id="bdsdu<?php echo $apt_all_staff[0]['id']; ?>bdimagetype" type="hidden" name="bdimagetype"/>
																<input type="hidden" id="bdsdu<?php echo $apt_all_staff[0]['id']; ?>bdimagename" name="bdimagename" value="" />
																</div>
														</div>							
													</div>		
												</div>			
											</div>			
										</div>

							<input name="image" id="bdsdu<?php echo $apt_all_staff[0]['id'];?>uploadedimg" type="hidden" value="<?php echo $apt_all_staff[0]['image'];?>" />
						</div>
					
						<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
						<form action="post" id="staff_personal_detail<?php echo $apt_all_staff[0]['id'];?>" class="staff_personal_detail">
							<table class="apt-staff-common-table">
								<tbody>
									<tr>
										<td><label for="apt-member-name"><?php echo __("User Name","apt");?></label></td>
										<td><input type="text" readonly value="<?php echo $apt_all_staff[0]['username']; ?>" class="form-control" id="apt-member-name" /></td>
									</tr>
									<tr>
										<td><label for="staff_name_<?php echo $apt_all_staff[0]['id']; ?>"><?php echo __("Full Name","apt");?></label></td>
										<td><input type="text" class="form-control" id="staff_name_<?php echo $apt_all_staff[0]['id']; ?>" value="<?php echo $apt_all_staff[0]['staff_name']; ?>"/></td>
									</tr>
									
									<tr>
										<td><label for="staff_description_<?php echo $apt_all_staff[0]['id']; ?>"><?php echo __("Desc","apt");?></label></td>
										<td><textarea class="form-control" id="staff_description_<?php echo $apt_all_staff[0]['id']; ?>"><?php echo $apt_all_staff[0]['description']; ?></textarea></td>
									</tr>
									<tr>
										<td><label for="phone-number"><?php echo __("Phone","apt");?></label></td>
										<td><input type="tel" class="form-control staff_phone_number" id="staff_phone_<?php echo $apt_all_staff[0]['id']; ?>" value="<?php echo $apt_all_staff[0]['phone']; ?>" name="staff_phone" />
										</td>
									</tr>
									
									<tr>
										<td><label for="staff_timezone_<?php echo $apt_all_staff[0]['id']; ?>"><?php echo __("Time Zone","apt");?></label></td>
										<td>
											<select class="selectpicker" id="staff_timezone_<?php echo $apt_all_staff[0]['id']; ?>" data-size="10" style="display: none;">
												<option <?php if($apt_all_staff[0]['timezoneID'] == '1'){ echo "selected"; } ?> timeZoneId="1" gmtAdjustment="GMT-12:00" useDaylightTime="0" value="-12">(GMT-12:00) International Date Line West</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '2'){ echo "selected"; } ?> timeZoneId="2" gmtAdjustment="GMT-11:00" useDaylightTime="0" value="-11">(GMT-11:00) Midway Island, Samoa</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '3'){ echo "selected"; } ?> timeZoneId="3" gmtAdjustment="GMT-10:00" useDaylightTime="0" value="-10">(GMT-10:00) Hawaii</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '4'){ echo "selected"; } ?> timeZoneId="4" gmtAdjustment="GMT-09:00" useDaylightTime="1" value="-9">(GMT-09:00) Alaska</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '5'){ echo "selected"; } ?> timeZoneId="5" gmtAdjustment="GMT-08:00" useDaylightTime="1" value="-8">(GMT-08:00) Pacific Time (US & Canada)</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '6'){ echo "selected"; } ?> timeZoneId="6" gmtAdjustment="GMT-08:00" useDaylightTime="1" value="-8">(GMT-08:00) Tijuana, Baja California</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '7'){ echo "selected"; } ?> timeZoneId="7" gmtAdjustment="GMT-07:00" useDaylightTime="0" value="-7">(GMT-07:00) Arizona</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '8'){ echo "selected"; } ?> timeZoneId="8" gmtAdjustment="GMT-07:00" useDaylightTime="1" value="-7">(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '9'){ echo "selected"; } ?> timeZoneId="9" gmtAdjustment="GMT-07:00" useDaylightTime="1" value="-7">(GMT-07:00) Mountain Time (US & Canada)</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '10'){ echo "selected"; } ?> timeZoneId="10" gmtAdjustment="GMT-06:00" useDaylightTime="0" value="-6">(GMT-06:00) Central America</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '11'){ echo "selected"; } ?> timeZoneId="11" gmtAdjustment="GMT-06:00" useDaylightTime="1" value="-6">(GMT-06:00) Central Time (US & Canada)</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '12'){ echo "selected"; } ?> timeZoneId="12" gmtAdjustment="GMT-06:00" useDaylightTime="1" value="-6">(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '13'){ echo "selected"; } ?> timeZoneId="13" gmtAdjustment="GMT-06:00" useDaylightTime="0" value="-6">(GMT-06:00) Saskatchewan</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '14'){ echo "selected"; } ?> timeZoneId="14" gmtAdjustment="GMT-05:00" useDaylightTime="0" value="-5">(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '15'){ echo "selected"; } ?> timeZoneId="15" gmtAdjustment="GMT-05:00" useDaylightTime="1" value="-5">(GMT-05:00) Eastern Time (US & Canada)</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '16'){ echo "selected"; } ?> timeZoneId="16" gmtAdjustment="GMT-05:00" useDaylightTime="1" value="-5">(GMT-05:00) Indiana (East)</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '17'){ echo "selected"; } ?> timeZoneId="17" gmtAdjustment="GMT-04:00" useDaylightTime="1" value="-4">(GMT-04:00) Atlantic Time (Canada)</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '18'){ echo "selected"; } ?> timeZoneId="18" gmtAdjustment="GMT-04:00" useDaylightTime="0" value="-4">(GMT-04:00) Caracas, La Paz</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '19'){ echo "selected"; } ?> timeZoneId="19" gmtAdjustment="GMT-04:00" useDaylightTime="0" value="-4">(GMT-04:00) Manaus</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '20'){ echo "selected"; } ?> timeZoneId="20" gmtAdjustment="GMT-04:00" useDaylightTime="1" value="-4">(GMT-04:00) Santiago</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '21'){ echo "selected"; } ?> timeZoneId="21" gmtAdjustment="GMT-03:30" useDaylightTime="1" value="-3.5">(GMT-03:30) Newfoundland</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '22'){ echo "selected"; } ?> timeZoneId="22" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3">(GMT-03:00) Brasilia</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '23'){ echo "selected"; } ?> timeZoneId="23" gmtAdjustment="GMT-03:00" useDaylightTime="0" value="-3">(GMT-03:00) Buenos Aires, Georgetown</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '24'){ echo "selected"; } ?> timeZoneId="24" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3">(GMT-03:00) Greenland</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '25'){ echo "selected"; } ?> timeZoneId="25" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3">(GMT-03:00) Montevideo</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '26'){ echo "selected"; } ?> timeZoneId="26" gmtAdjustment="GMT-02:00" useDaylightTime="1" value="-2">(GMT-02:00) Mid-Atlantic</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '27'){ echo "selected"; } ?> timeZoneId="27" gmtAdjustment="GMT-01:00" useDaylightTime="0" value="-1">(GMT-01:00) Cape Verde Is.</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '28'){ echo "selected"; } ?> timeZoneId="28" gmtAdjustment="GMT-01:00" useDaylightTime="1" value="-1">(GMT-01:00) Azores</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '29'){ echo "selected"; } ?> timeZoneId="29" gmtAdjustment="GMT+00:00" useDaylightTime="0" value="0">(GMT+00:00) Casablanca, Monrovia, Reykjavik</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '30'){ echo "selected"; } ?> timeZoneId="30" gmtAdjustment="GMT+00:00" useDaylightTime="1" value="0">(GMT+00:00) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '31'){ echo "selected"; } ?> timeZoneId="31" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '32'){ echo "selected"; } ?> timeZoneId="32" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '33'){ echo "selected"; } ?> timeZoneId="33" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '34'){ echo "selected"; } ?> timeZoneId="34" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '35'){ echo "selected"; } ?> timeZoneId="35" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) West Central Africa</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '36'){ echo "selected"; } ?> timeZoneId="36" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Amman</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '37'){ echo "selected"; } ?> timeZoneId="37" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Athens, Bucharest, Istanbul</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '38'){ echo "selected"; } ?> timeZoneId="38" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Beirut</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '39'){ echo "selected"; } ?> timeZoneId="39" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Cairo</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '40'){ echo "selected"; } ?> timeZoneId="40" gmtAdjustment="GMT+02:00" useDaylightTime="0" value="2">(GMT+02:00) Harare, Pretoria</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '41'){ echo "selected"; } ?> timeZoneId="41" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '42'){ echo "selected"; } ?> timeZoneId="42" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Jerusalem</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '43'){ echo "selected"; } ?> timeZoneId="43" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Minsk</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '44'){ echo "selected"; } ?> timeZoneId="44" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Windhoek</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '45'){ echo "selected"; } ?> timeZoneId="45" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="3">(GMT+03:00) Kuwait, Riyadh, Baghdad</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '46'){ echo "selected"; } ?> timeZoneId="46" gmtAdjustment="GMT+03:00" useDaylightTime="1" value="3">(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '47'){ echo "selected"; } ?> timeZoneId="47" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="3">(GMT+03:00) Nairobi</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '48'){ echo "selected"; } ?> timeZoneId="48" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="3">(GMT+03:00) Tbilisi</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '49'){ echo "selected"; } ?> timeZoneId="49" gmtAdjustment="GMT+03:30" useDaylightTime="1" value="3.5">(GMT+03:30) Tehran</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '50'){ echo "selected"; } ?> timeZoneId="50" gmtAdjustment="GMT+04:00" useDaylightTime="0" value="4">(GMT+04:00) Abu Dhabi, Muscat</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '51'){ echo "selected"; } ?> timeZoneId="51" gmtAdjustment="GMT+04:00" useDaylightTime="1" value="4">(GMT+04:00) Baku</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '52'){ echo "selected"; } ?> timeZoneId="52" gmtAdjustment="GMT+04:00" useDaylightTime="1" value="4">(GMT+04:00) Yerevan</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '53'){ echo "selected"; } ?> timeZoneId="53" gmtAdjustment="GMT+04:30" useDaylightTime="0" value="4.5">(GMT+04:30) Kabul</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '54'){ echo "selected"; } ?> timeZoneId="54" gmtAdjustment="GMT+05:00" useDaylightTime="1" value="5">(GMT+05:00) Yekaterinburg</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '55'){ echo "selected"; } ?> timeZoneId="55" gmtAdjustment="GMT+05:00" useDaylightTime="0" value="5">(GMT+05:00) Islamabad, Karachi, Tashkent</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '56'){ echo "selected"; } ?> timeZoneId="56" gmtAdjustment="GMT+05:30" useDaylightTime="0" value="5.5">(GMT+05:30) Sri Jayawardenapura</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '57'){ echo "selected"; } ?> timeZoneId="57" gmtAdjustment="GMT+05:30" useDaylightTime="0" value="5.5">(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '58'){ echo "selected"; } ?> timeZoneId="58" gmtAdjustment="GMT+05:45" useDaylightTime="0" value="5.75">(GMT+05:45) Kathmandu</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '59'){ echo "selected"; } ?> timeZoneId="59" gmtAdjustment="GMT+06:00" useDaylightTime="1" value="6">(GMT+06:00) Almaty, Novosibirsk</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '60'){ echo "selected"; } ?> timeZoneId="60" gmtAdjustment="GMT+06:00" useDaylightTime="0" value="6">(GMT+06:00) Astana, Dhaka</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '61'){ echo "selected"; } ?> timeZoneId="61" gmtAdjustment="GMT+06:30" useDaylightTime="0" value="6.5">(GMT+06:30) Yangon (Rangoon)</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '62'){ echo "selected"; } ?> timeZoneId="62" gmtAdjustment="GMT+07:00" useDaylightTime="0" value="7">(GMT+07:00) Bangkok, Hanoi, Jakarta</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '63'){ echo "selected"; } ?> timeZoneId="63" gmtAdjustment="GMT+07:00" useDaylightTime="1" value="7">(GMT+07:00) Krasnoyarsk</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '64'){ echo "selected"; } ?> timeZoneId="64" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '65'){ echo "selected"; } ?> timeZoneId="65" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Kuala Lumpur, Singapore</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '66'){ echo "selected"; } ?> timeZoneId="66" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Irkutsk, Ulaan Bataar</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '67'){ echo "selected"; } ?> timeZoneId="67" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Perth</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '68'){ echo "selected"; } ?> timeZoneId="68" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Taipei</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '69'){ echo "selected"; } ?> timeZoneId="69" gmtAdjustment="GMT+09:00" useDaylightTime="0" value="9">(GMT+09:00) Osaka, Sapporo, Tokyo</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '70'){ echo "selected"; } ?> timeZoneId="70" gmtAdjustment="GMT+09:00" useDaylightTime="0" value="9">(GMT+09:00) Seoul</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '71'){ echo "selected"; } ?> timeZoneId="71" gmtAdjustment="GMT+09:00" useDaylightTime="1" value="9">(GMT+09:00) Yakutsk</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '72'){ echo "selected"; } ?> timeZoneId="72" gmtAdjustment="GMT+09:30" useDaylightTime="0" value="9.5">(GMT+09:30) Adelaide</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '73'){ echo "selected"; } ?> timeZoneId="73" gmtAdjustment="GMT+09:30" useDaylightTime="0" value="9.5">(GMT+09:30) Darwin</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '74'){ echo "selected"; } ?> timeZoneId="74" gmtAdjustment="GMT+10:00" useDaylightTime="0" value="10">(GMT+10:00) Brisbane</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '75'){ echo "selected"; } ?> timeZoneId="75" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="10">(GMT+10:00) Canberra, Melbourne, Sydney</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '76'){ echo "selected"; } ?> timeZoneId="76" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="10">(GMT+10:00) Hobart</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '77'){ echo "selected"; } ?> timeZoneId="77" gmtAdjustment="GMT+10:00" useDaylightTime="0" value="10">(GMT+10:00) Guam, Port Moresby</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '78'){ echo "selected"; } ?> timeZoneId="78" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="10">(GMT+10:00) Vladivostok</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '79'){ echo "selected"; } ?> timeZoneId="79" gmtAdjustment="GMT+11:00" useDaylightTime="1" value="11">(GMT+11:00) Magadan, Solomon Is., New Caledonia</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '80'){ echo "selected"; } ?> timeZoneId="80" gmtAdjustment="GMT+12:00" useDaylightTime="1" value="12">(GMT+12:00) Auckland, Wellington</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '81'){ echo "selected"; } ?> timeZoneId="81" gmtAdjustment="GMT+12:00" useDaylightTime="0" value="12">(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>
												<option <?php if($apt_all_staff[0]['timezoneID'] == '82'){ echo "selected"; } ?> timeZoneId="82" gmtAdjustment="GMT+13:00" useDaylightTime="0" value="13">(GMT+13:00) Nuku'alofa</option>
											</select>
										</td>
										
									</tr>
									
									<tr>
										<td><label for="phone-number"><?php echo __("Schedule Type","apt");?></label></td>
										<td>
											<label for="staff_schedule_<?php echo $apt_all_staff[0]['id']; ?>">
												<input <?php if($apt_all_staff[0]['schedule_type']=='M'){ echo "checked";} ?>  type="checkbox" id="staff_schedule_<?php echo $apt_all_staff[0]['id']; ?>" data-toggle="toggle" data-size="small" data-on="<?php echo __("Monthly","apt");?>" data-off="<?php echo __("Weekly","apt");?>" data-onstyle="info" data-offstyle="warning" />	
											</label>
											<input type="hidden" id="curr_staff_schedule_<?php echo $apt_all_staff[0]['id']; ?>" value="<?php echo $apt_all_staff[0]['schedule_type']; ?>" />
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Enable Booking","apt");?></label></td>
										<td>
											<label for="staff_status_<?php echo $apt_all_staff[0]['id']; ?>">
												<input <?php if($apt_all_staff[0]['status']=='E'){ echo "checked";} ?> type="checkbox" id="staff_status_<?php echo $apt_all_staff[0]['id']; ?>" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","apt");?>" data-off="<?php echo __("Off","apt");?>" data-onstyle="success" data-offstyle="default" />
											</label>
										</td>
									</tr>
									<tr>
										<td></td>
										<td><a data-staff_id="<?php echo $apt_all_staff[0]['id']; ?>" href="javascript:void(0)" class="btn btn-success apt-btn-width update_staff_detail"><?php echo __("Save","apt");?></a>
										
									</tr>
								</tbody>
							</table>
							</form>	
						</div>

							
					</div>
					<?php
					/* Get Staff Services */
					$service->provider_id = $apt_all_staff[0]['id'];
					$apt_staff_services = $service->readall_services_of_provider();
					$staffservces = array();
					foreach($apt_staff_services as $staffservice){$staffservces[]=$staffservice->service_id;}
					?>
					<div class="tab-pane apt-services-list col-lg-12 col-md-12 col-sm-12 col-xs-12 member-services" id="member-services">
						<div class="tab-content">
							<div class="panel panel-default">
								<h4 class="apt-right-header"><?php echo __("Services provided by","apt");?> <strong><?php echo $apt_all_staff[0]['staff_name']; ?> (<span data-total_service="<?php echo sizeof($apt_services);?>" class="staff_servicecount_<?php echo $apt_all_staff[0]['id']; ?>"><?php echo sizeof($staffservces);?></span>)</strong></h4>
									<div id="accordion" class="panel-group" role="tablist" >
										<div class="panel panel-default apt-staff-service-panel">
											<div class="panel-heading" role="tab" >
												<h4 class="panel-title">
													<label class="pull-left mr-10 toggle-large" for="all-services">
														<input data-staff_id="<?php echo $apt_all_staff[0]['id']; ?>" <?php if(sizeof($staffservces)==sizeof($apt_services)){ echo"checked";}?> class="link_service linkallservices" value="all" type="checkbox" id="all-services" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","apt");?>" data-off="<?php echo __("Disable","apt");?>" data-onstyle="success" data-offstyle="danger" />
														
													</label>
													<span><?php echo __("All Services","apt");?></span>
												</h4>
											</div>
										</div>
									</div>	
									<?php 
									
									foreach($apt_services as $apt_service){ ?>
									<div id="accordion" class="panel-group" role="tablist">
										<div class="panel panel-default apt-staff-service-panel">
											<div class="panel-heading">
												<h4 class="panel-title">
													<label class="pull-left mr-10" for="staff-service<?php echo $apt_service->id;?><?php echo $apt_all_staff[0]['id']; ?>">
														<input class="link_service apt_all_service<?php echo $apt_all_staff[0]['id']; ?>" <?php if(in_array($apt_service->id,$staffservces)){ echo "checked";}?> value="<?php echo $apt_service->id; ?>" data-staff_id="<?php echo $apt_all_staff[0]['id']; ?>"  type="checkbox" id="staff-service<?php echo $apt_service->id;?><?php echo $apt_all_staff[0]['id']; ?>" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","apt");?>" data-off="<?php echo __("Off","apt");?>" data-onstyle="success" data-offstyle="default" />
													
														
													</label>
													<span><?php echo $apt_service->service_title; ?></span>
													
													<span class="pull-right">
														<span class="apt-service-time-member"><?php if(floor($apt_service->duration/60)!=0){ echo floor($apt_service->duration/60); echo __(" Hrs","apt"); } ?>  <?php  if($apt_service->duration%60 !=0){ echo $apt_service->duration%60; echo __(" Mins","apt");} ?>
														</span>
														<span class="apt-service-price-member"><?php echo $apt_currency_symbol;?><?php
															if($apt_service->offered_price != "" ){
															echo $apt_service->offered_price;
														}else{ echo $apt_service->amount; }?></span>
														
														<div class="apt-show-hide">
															<input type="checkbox" name="apt-show-hide" class="apt-show-hide-checkbox" id="ssp<?php echo $apt_service->id;?>" >
															<label class="apt-show-hide-label" for="ssp<?php echo $apt_service->id;?>"></label>
														</div>
														
													</span>	
												</h4>
											</div>
											<div  class="ssp_detail panel-collapse collapse detail-id_ssp<?php echo $apt_service->id;?>">
												<div class="panel-body">
													<form id="" method="post" type="" class="slide-toggle" >
														<div id="staff-service1" class="panel-collapse collapse in">
															<div class="panel-body">
																<div class="apt-provider-custom-price-menu">
																	<ul class="nav nav-pills">
																		<?php
																		if($apt_all_staff[0]['schedule_type']=='M'){
																			$week_name=array(__('First Week ','apt'),__('Second Week ','apt'),__('Third Week ','apt'),__('Fourth Week ','apt'),__('Fifth Week ','apt'));
																		}else{
																			$week_name=array(__('Week ','apt'));
																		}
																		for($tab=1;$tab<=$wl_end;$tab++) { ?>
																		<li <?php if($tab==1) { echo " class='active' "; } else { }  ?>><a href="#staffdayprice<?php echo $tab; ?><?php echo $apt_service->id;?>" data-toggle="tab"><?php echo $week_name[$tab-1]; echo __("Price","apt");?></a></li>
																		<?php } ?>	
																	</ul>
																</div>	
																<div class="apt-staff-price-rules">
																	<div class="tab-content">	
																	<?php 	for($w=1;$w<=$wl_end;$w++) { ?>
																		<div class="tab-pane <?php if($w==1){ echo "active";} ?>" id="staffdayprice<?php echo $w; ?><?php echo $apt_service->id;?>"><!-- first week price scheduling -->
																		<div class="panel panel-default">
																			<div class="panel-body">
																			<h4 class="apt-right-header"><?php echo __($week_name[$w-1]." time scheduling of","apt");?> <strong><?php echo $apt_all_staff[0]['staff_name']; ?></strong></h4>
																				<ul class="list-unstyled" id="apt-staff-price">
																					<?php 	$day_name=array(__('Monday','apt'),__('Tuesday','apt'),__('Wednesday','apt'),__('Thursday','apt'),__('Friday','apt'),__('Saturday','apt'),__('Sunday','apt'));
																					for($i=1;$i<=7;$i++) {
																						$ssp->provider_id = $apt_all_staff[0]['id'];
																						$ssp->service_id = $apt_service->id;
																						$ssp->weekid = $w;
																						$ssp->weekdayid = $i;
																						$apt_ssp_info = $ssp->readOne_ssp();
																					?>
																					<li class="active">
																						<div class="col-sm-12 col-md-4 col-lg-4 col-xs-12 np top5">
																							<span class="col-sm-7 col-md-7 col-lg-7 col-xs-12 apt-day-name"><?php echo $day_name[$i-1];?></span>
																							<span class="col-sm-5 col-md-5 col-lg-5 col-xs-12">
																								<a class="btn btn-small btn-success apt-small-br-btn apt_add_ssp" data-serviceamout="<?php echo $apt_service->amount; ?>"  data-weekid="<?php echo $w;?>" data-dayid="<?php echo $i;?>" data-serviceid="<?php echo $apt_service->id; ?>" data-staffid="<?php echo $apt_all_staff[0]['id']; ?>" data-mainid="<?php echo $w;?>_<?php echo $i;?>"><?php echo __("Add price","apt");?></a>
																							</span>	
																						</div>	
																						<div class="col-sm-8 col-md-8 col-lg-8 col-xs-12">
																							<ul class="apt-price-row pull-left list-unstyled" id="apt_ssp_<?php echo $apt_service->id;?>_<?php echo $w;?>_<?php echo $i;?>">
																								<?php foreach($apt_ssp_info as $apt_ssp){?>
																								<li class="fullwidth bb1f0" id="apt_ssp_detail_<?php echo $apt_ssp->id; ?>">
																										<span class="col-sm-6 col-md-12 col-lg-5 col-xs-12 apt-staff-price-schedule np">
																											<ul class="list-unstyled">
																												<li>
																													<select id="ssp_starttime_<?php echo $apt_ssp->id; ?>" name="ssp_starttime_<?php echo $apt_ssp->id; ?>" class="selectpicker ssp_starttime" data-sspid="<?php echo $apt_ssp->id; ?>" data-size="10"  style="display: none;">
																														<?php $min =0;
																														while($min < 1440)
																														{																	
																														if($min==1440) {		
																														$timeValue = date_i18n('G:i:s',mktime(0,$min-1,0,1,1,2015)); 						
																														} else {				
																														$timeValue = date_i18n('G:i:s',mktime(0,$min,0,1,1,2015)); 								}								
																														$timetoprint = date_i18n('G:i:s',mktime(0,$min,0,1,1,2014)); ?>
																														
																														<option  value="<?php echo $timeValue; ?>" <?php if ( $timetoprint==date_i18n('G:i:s',strtotime($apt_ssp->ssp_starttime))){ echo "selected";}?> ><?php echo date_i18n(get_option('time_format'),strtotime($timetoprint)); ?></option>
																														<?php 								  
																														  $min = $min+$interval;
																														} ?>
																													</select>
																												  
																													<span class="apt-price-hours-to"> <?php echo __("to","apt");?> </span>
																													<select id="ssp_endtime_<?php echo $apt_ssp->id; ?>" name="ssp_endtime_<?php echo $apt_ssp->id; ?>" class="selectpicker" data-sspid="" data-size="10" style="display: none;">
																														<?php $min =0;
																														while($min < 1440)
																														{																	
																														if($min==1440) {		
																														$timeValue = date_i18n('G:i:s',mktime(0,$min-1,0,1,1,2015)); 						
																														}else{				
																														$timeValue = date_i18n('G:i:s',mktime(0,$min,0,1,1,2015)); 								
																														}								
																														$timetoprint = date_i18n('G:i:s',mktime(0,$min,0,1,1,2014)); ?>																													
																														<option  value="<?php echo $timeValue; ?>"  <?php if ( $timetoprint==date_i18n('G:i:s',strtotime($apt_ssp->ssp_endtime))){ echo "selected";}?> ><?php echo date_i18n(get_option('time_format'),strtotime($timetoprint)); ?></option>
																														<?php 								  
																														  $min = $min+$interval;
																														} ?>
																													</select>
																												</li>
																											</ul>	
																										</span>
																										<span class="col-sm-6 col-md-12 col-lg-7 col-xs-12 npr">
																											<table  class="apt-staff-common-table">
																												<tbody>
																													<tr class="col-lg-7 col-sm-6 col-xs-6 npr">
																													<td class="col-xs-4"><?php echo __("Price","apt");?></td>
																														<td class="col-xs-8"><div class="input-group"><span class="input-group-addon"><?php echo $apt_currency_symbol; ?></span><input type="text" id="ssp_price_<?php echo $apt_ssp->id; ?>" class="form-control" value="<?php echo $apt_ssp->price; ?>" placeholder="<?php echo __("$10","apt");?>" /></div></td>
																													</tr>
																													<tr class="col-lg-5 col-sm-6 col-xs-6 npr">
																														<td><a href="javascript:void(0)" id="apt-delete-staff-price<?php echo $apt_ssp->id; ?>" data-sspid="<?php echo $apt_ssp->id; ?>" class="pull-right btn btn-circle btn-default delete_ssp_popover" rel="popover" data-placement='bottom' title="Are You Sure?"> <i class="fa fa-trash"></i></a>
																															<div id="popover-delete-price<?php echo $apt_ssp->id; ?>" style="display: none;">
																																<div class="arrow"></div>
																																<table class="form-horizontal" cellspacing="0">
																																	<tbody>
																																		<tr>
																																			<td>
																																				<a id="<?php echo $apt_ssp->id; ?>" value="Delete" class="btn btn-danger delete_ssp" ><?php echo __("Yes","apt");?></a>
																																				<a id="apt-close-popover-delete-price<?php echo $apt_ssp->id; ?>" class="btn btn-default cancel_ssp_delete" href="javascript:void(0)"><?php echo __("Cancel","apt");?></a>
																																			</td>
																																		</tr>
																																	</tbody>
																																</table>
																															</div>
																														</td>
																														<td><a href="javascript:void(0)" data-sspid="<?php echo $apt_ssp->id; ?>" class="pull-right btn btn-circle btn-success update_ssp_detail" title="Save"> <i class="fa fa-save"></i></a></td>																								</tr>
																													
																												</tbody>
																											</table>	
																										</span>
																									</li>																			
																								<?php }?>																					
																							</ul>	
																						</div>	
																					</li>
																					<?php } ?>
																					
																				</ul>
																			
																			</div>
																		</div>
																		</div><!-- first week price end -->
																		<?php } ?>
																		
																		
																	</div>
																</div>
															</div>
														</div>
														
													</form>
												</div>
											</div>
										</div>
										
									</div>
									<?php } ?>	
									
							</div>
						</div>
						
					</div>
					
					<div class="tab-pane member-availabilty" id="member-availabilty">
					<form id="" method="POST" >
						<div class="panel panel-default">
							
							<div class="col-sm-3 col-md-3 col-lg-3 col-xs-12 apt-weeks-schedule-menu">
								<ul class="nav nav-pills nav-stacked">
								<?php
								if($apt_all_staff[0]['schedule_type']=='M'){
									$week_name=array(__('First Week ','apt'),__('Second Week ','apt'),__('Third Week ','apt'),__('Fourth Week ','apt'),__('Fifth Week ','apt'));
								}else{
									$week_name=array(__('This Week','apt'));
								}
								for($tab=1;$tab<=$wl_end;$tab++) { ?>
								<li <?php if($tab==1) { echo " class='active' "; } else { }  ?>><a href="#tab<?php echo $tab; ?>" data-toggle="tab"><?php echo $week_name[$tab-1];?></a></li>
								<?php } ?>
								</ul>
							</div>	
							
							<div class="col-sm-9 col-md-9 col-lg-9 col-xs-12">
							<hr id="vr" />
								<div class="tab-content">	
									<?php 	for($w=1;$w<=$wl_end;$w++) { ?>
									<div class="tab-pane <?php if($w==1){ echo "active";} ?>" id="tab<?php echo $w; ?>">							
										<div class="panel panel-default">
											<div class="panel-body">
											<h4 class="apt-right-header"><?php echo $week_name[$w-1].__(" time scheduling of","apt");?> <strong><?php echo $apt_all_staff[0]['staff_name']; ?></strong></h4>
												<ul class="list-unstyled" id="apt-staff-timing">
												    <?php 	$day_name=array(__('Monday','apt'),__('Tuesday','apt'),__('Wednesday','apt'),__('Thursday','apt'),__('Friday','apt'),__('Saturday','apt'),__('Sunday','apt'));
													for($i=1;$i<=7;$i++) {
													/* Get selected Provider Time Schedule */
													$schedule->week_id = $w; 
													$schedule->provider_id = $apt_all_staff[0]['id']; 
													$schedule->weekday_id = $i; 
													$schedule->readOne_new(); ?>	
													<li class="active">
														<span class="col-sm-3 col-md-3 col-lg-3 col-xs-12 apt-day-name"><?php echo $day_name[$i-1];?></span>
														<span class="col-sm-2 col-md-2 col-lg-2 col-xs-12">
															<label class="apt-col2" for="off_day_<?php echo $w;?>_<?php echo $i;?>">
																<input class="staff_dayoff"  <?php if(!$schedule->get_offdays_new()){ echo " checked "; }?>  type="checkbox"  name="off_day_[<?php echo $w;?>][<?php echo $i;?>]" id="off_day_<?php echo $w;?>_<?php echo $i;?>" data-mainid="<?php echo $w;?>_<?php echo $i;?>" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","apt");?>" data-off="<?php echo __("Off","apt");?>" data-onstyle="info" data-offstyle="default" />

															</label>
														</span>	
														<span <?php if($schedule->get_offdays_new()){ echo " style='display:none;' "; }?> class="col-sm-7 col-md-7 col-lg-7 col-xs-12 apt-staff-time-schedule" id="staff_st_et_<?php echo $w;?>_<?php echo $i;?>">
															<div class="pull-right">
																<select name="start_time[<?php echo $w;?>][<?php echo $i;?>]" id="start_time_<?php echo $w;?>_<?php echo $i;?>" data-mainid="<?php echo $w;?>_<?php echo $i;?>" class="selectpicker schedule_day_start_time" data-size="10"  style="display: none;">
																	<?php $min =0;
																			while($min < 1440)
																			{																	
																			if($min==1440) {		
																			$timeValue = date_i18n('G:i:s',mktime(0,$min-1,0,1,1,2015)); 						
																			} else {				
																			$timeValue = date_i18n('G:i:s',mktime(0,$min,0,1,1,2015)); 								}								
																			$timetoprint = date_i18n('G:i:s',mktime(0,$min,0,1,1,2014)); 
																			
																			$timetocmp = date_i18n('H:i:s',mktime(0,$min,0,1,1,2014));
																			
																			?>
																			
																			<option  value="<?php echo $timeValue; ?>"  <?php if('Y'!=$schedule->get_offdays()  && strtotime($schedule->daystart_time)==strtotime($timetocmp)){ echo "selected='selected'"; } ?> ><?php echo date_i18n(get_option('time_format'),strtotime($timetoprint)); ?></option>
																			<?php 								  
																			  $min = $min+$interval;
																			} ?>
																</select>
															  
																<span class="apt-staff-hours-to"> <?php echo __("to","apt");?> </span>
																<select name="end_time[<?php echo $w;?>][<?php echo $i;?>]"  id="end_time_<?php echo $w;?>_<?php echo $i;?>" class="selectpicker" data-size="10"  style="display: none;">
																	<?php $min =0;
																		$counter=0;
																		while($min < 1440)
																		{				
																		if($min==1440) {						
																		$timeValue = date_i18n('G:i:s',mktime(0,$min-1,0,1,1,2015)); 				
																		} else {				
																		$timeValue = date_i18n('G:i:s',mktime(0,$min,0,1,1,2015)); 					
																		}								
																		$timetoprint = date_i18n('G:i:s',mktime(0,$min,0,1,1,2015));

																		$timetocmp = date_i18n('H:i:s',mktime(0,$min,0,1,1,2014));
																		
																		?>
																		   <option  value="<?php echo $timeValue; ?>" <?php if(strtotime($schedule->dayend_time)==strtotime($timetocmp)){ echo "selected='selected'"; } ?>><?php echo date_i18n(get_option('time_format'),strtotime($timetoprint)); ?></option>
																		  <?php 
																		  $timinglisting[$counter]['value'] = $timetoprint;
																		  $timinglisting[$counter]['text'] =  date_i18n('h:i A',strtotime($timetoprint));
																		  $counter++;
																		  $min = $min+$interval; 
																		} ?>
																</select>
															</div> 
														</span>
													</li>
													<?php } ?>												
												</ul>
											</div>
										</div>
									</div>
									<?php } ?>
													
									
									
									
								</div>	
							</div>	
							
						</div>
						<table class="apt-staff-common-table">
							<tbody>
								<tr>
									<td></td>
									<td>
										<a href="javascript:void(0)" data-st="<?php echo $apt_all_staff[0]['schedule_type']; ?>" id="<?php echo $apt_all_staff[0]['id']; ?>" value="" name="update_staff_schedule" class="btn btn-success apt-btn-width col-xs-offset-3 update_staff_schedule"><?php echo __("Save Setting","apt");?></a>
									</td>
								</tr>
							</tbody>
						</table>
					</form>
					</div>
					
					<div class="tab-pane member-addbreaks" id="member-addbreaks">
						<div class="panel panel-default">
							<div class="panel-body">
								<div class="col-sm-3 col-md-3 col-lg-3 col-xs-12 apt-weeks-breaks-menu">
								<ul class="nav nav-pills nav-stacked">
									<?php
								if($apt_all_staff[0]['schedule_type']=='M'){
									$week_name=array(__('First Week Breaks','apt'),__('Second Week Breaks','apt'),__('Third Week Breaks','apt'),__('Fourth Week Breaks','apt'),__('Fifth Week Breaks','apt'));
								}else{
									$week_name=array(__('This Week Breaks','apt'));
								}
								for($tab=1;$tab<=$wl_end;$tab++) { ?>
								<li <?php if($tab==1) { echo " class='active' "; } else { }  ?>><a href="#tabbreak<?php echo $tab; ?>" data-toggle="tab"><?php echo $week_name[$tab-1];?></a></li>
								<?php } ?>
								</ul>
								</div>	
							
								<div class="col-sm-9 col-md-9 col-lg-9 col-xs-12 apt-weeks-breaks-details">
									<div class="tab-content">
										<?php 	for($w=1;$w<=$wl_end;$w++) { ?>
									<div class="tab-pane <?php if($w==1){ echo "active";} ?>" id="tabbreak<?php echo $w; ?>">
											<div class="panel panel-default">
												<div class="panel-body">
												<h4 class="apt-right-header"><?php echo __($week_name[$w-1]." of","apt");?> <strong><?php echo $apt_all_staff[0]['staff_name']; ?></strong></h4>
													<ul class="list-unstyled" id="apt-staff-breaks">
														 <?php 	$day_name=array(__('Monday','apt'),__('Tuesday','apt'),__('Wednesday','apt'),__('Thursday','apt'),__('Friday','apt'),__('Saturday','apt'),__('Sunday','apt'));
															for($i=1;$i<=7;$i++) {
															/* Get selected Provider Time Schedule */
															$breaks->week_id = $w; 
															$breaks->provider_id = $apt_all_staff[0]['id']; 
															$breaks->weekday_id = $i; 
															$all_day_breaks = $breaks->read_day_breaks(); ?>
														<li class="active">
															<span class="col-sm-5 col-md-3 col-lg-3 col-xs-12 apt-day-name"><?php echo $day_name[$i-1];?></span>
															<span class="col-sm-5 col-md-2 col-lg-2 col-xs-12">
																<a id="apt-add-staff-breaks" data-staff_id="<?php echo $apt_all_staff[0]['id']; ?>" data-weekid="<?php echo $w;?>" data-dayid="<?php echo $i;?>" class="btn btn-small btn-success apt-small-br-btn staff_add_break"><?php echo __("Add Break","apt");?></a>
															</span>	
															<span class="col-sm-12 col-md-7 col-lg-7 col-xs-12 apt-staff-breaks-schedule">
																<ul class="list-unstyled" id="apt_staff_breaks_<?php echo $w;?>_<?php echo $i;?>">
																<?php if(sizeof($all_day_breaks)>0){
																	foreach($all_day_breaks as $day_break){ ?>
																			<li id="staff_break_<?php echo $day_break['break_id']; ?>">
																				<select id="staff_breakstart_<?php echo $day_break['break_id']; ?>" data-bid="<?php echo $day_break['break_id']; ?>" data-bv="start" class="selectpicker staff_schedule_break" data-size="10" style="display: none;">
																					<?php $min =0;
																					while($min < 1440)
																					{																	
																					if($min==1440) {		
																					$timeValue = date_i18n('G:i:s',mktime(0,$min-1,0,1,1,2015)); 						
																					} else {				
																					$timeValue = date_i18n('G:i:s',mktime(0,$min,0,1,1,2015)); 								}								
																					$timetoprint = date_i18n('G:i:s',mktime(0,$min,0,1,1,2014)); ?>
																					
																					<option  value="<?php echo $timeValue; ?>"  <?php if ( $timetoprint==date_i18n('G:i:s',strtotime($day_break['break_start']))){ echo "selected";}?> ><?php echo date_i18n(get_option('time_format'),strtotime($timetoprint)); ?></option>
																					<?php 								  
																					  $min = $min+$interval;
																					} ?>
																					
																			</select>
																			<span class="apt-staff-hours-to"> <?php echo __("to","apt");?> </span>
																				<select id="staff_breakend_<?php echo $day_break['break_id']; ?>" name="staff_breakend_<?php echo $day_break['break_id']; ?>" data-bid="<?php echo $day_break['break_id']; ?>" data-bv="end" class="selectpicker staff_schedule_break" data-size="10" style="display: none;">
																					<?php $min =0;
																					while($min < 1440)
																					{																	
																					if($min==1440) {		
																					$timeValue = date_i18n('G:i:s',mktime(0,$min-1,0,1,1,2015)); 						
																					} else {				
																					$timeValue = date_i18n('G:i:s',mktime(0,$min,0,1,1,2015)); 								}								
																					$timetoprint = date_i18n('G:i:s',mktime(0,$min,0,1,1,2014)); ?>
																					
																					<option  value="<?php echo $timeValue; ?>"  <?php if ( $timetoprint==date_i18n('G:i:s',strtotime($day_break['break_end']))){ echo "selected";}?> ><?php echo date_i18n(get_option('time_format'),strtotime($timetoprint)); ?></option>
																					<?php 								  
																					  $min = $min+$interval;
																					} ?>
																				</select>
																			<!-- <input type="hidden" id="staff_breakend_<?php //echo $day_break['break_id']; ?>" value=""/>-->			
																			<button id="apt-delete-staff-break<?php echo $day_break['break_id']; ?>"  data-bid="<?php echo $day_break['break_id']; ?>"class="pull-right btn btn-circle btn-danger staff_delete_break" rel="popover" data-placement='bottom' title="<?php echo __("Are You Sure?","apt");?>"> <i class="fa fa-trash"></i></button>
																			<div id="popover-delete-breaks<?php echo $day_break['break_id']; ?>" style="display: none;">
																				<div class="arrow"></div>
																				<table class="form-horizontal" cellspacing="0">
																					<tbody>
																						<tr>
																							<td>
																								<a href="javascript:void(0)" id="<?php echo $day_break['break_id']; ?>" value="Delete" class="btn btn-danger delete_staff_break" ><?php echo __("Yes","apt");?></a>
																								<button data-bid="<?php echo $day_break['break_id']; ?>" id="apt-close-popover-delete-breaks<?php echo $day_break['break_id']; ?>" class="btn btn-default close_break_del_popover" href="javascript:void(0)"><?php echo __("Cancel","apt");?></button>
																							</td>
																						</tr>
																					</tbody>
																				</table>
																			</div>
																		</li>
																	<?php }
																}?>	
																</ul>	
															</span>
														</li>
													<?php } ?>	
													</ul>
												</div>
											</div>
										</div>
										<?php } ?>						
													
									</div><!-- end tab content main right -->
								</div>
							</div>
						</div>
					</div>
					
					
					<?php 
					$breaks->provider_id = $apt_all_staff[0]['id'];
					$apt_offtimes = $breaks->read_offtime();
					?>
					<div class="tab-pane member-offtime" id="member-offtime">
						<div class="panel panel-default">
							<div class="panel-body">
								<div class="apt-member-offtime-inner">
								<h3> <?php echo __("Off Times for","apt");?> <b><?php echo $apt_all_staff[0]['staff_name']; ?></b>  <?php echo __("provider","apt");?></h3>
									<div class="col-md-6 col-sm-7 col-xs-12 col-lg-6 mb-10">
										<label> <?php echo __("Add new off time","apt");?></label>
										<div id="offtime-daterange" class="form-control"  >
											<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
											<span></span> <i class="fa fa-caret-down"></i>
										</div>
									</div>
									<div class="col-md-2 col-sm-4 col-xs-12 col-lg-2">
										<a href="javascript:void(0)" class="form-group btn btn-info mt-20 add_staff_offtime" data-sid="<?php echo $apt_all_staff[0]['id']; ?>" name=""><?php echo __("Add Break","apt");?></a>
									</div>
									
								</div>
								<div class="apt-staff-member-offtime-list-main">
									<div class="table-responsive"> 
										<table id="apt-staff-member-offtime-list" class="apt-staff-member-offtime-list table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
											 <thead>
												<tr>
													<th><?php echo __("Start Date","apt");?></th>
													<th><?php echo __("Start Time","apt");?></th>
													<th><?php echo __("End Date","apt");?></th>
													<th><?php echo __("End Time","apt");?></th>
													<th><?php echo __("Action","apt");?></th>
												</tr>
											</thead>
											 <tbody id="staff_offtimes">
												<?php foreach($apt_offtimes as $offtime) { ?>
												<tr id="offtime_detail_<?php echo $offtime->id; ?>">
													<td> <?php echo date_i18n(get_option('appointment_datepicker_format'.'_'.$bwid),strtotime($offtime->offtime_start)); ?></td>
													<td><?php echo date_i18n(get_option('time_format'),strtotime($offtime->offtime_start)); ?></td>
													<td><?php echo date_i18n(get_option('appointment_datepicker_format'.'_'.$bwid),strtotime($offtime->offtime_end)); ?></td>
													<td><?php echo date_i18n(get_option('time_format'),strtotime($offtime->offtime_end)); ?></td>
													<td><a href="javascript:void(0)" data-staffid="<?php echo $apt_all_staff[0]['id'];?>" data-otid="<?php echo $offtime->id; ?>" class='btn btn-danger left-margin delete_staff_offtime'><span class='glyphicon glyphicon-remove'></span></a></td>
												</tr>
											<?php } ?>	
											</tbody>
											
											
										</table>
									</div>
								</div>
								
							</div>
						</div>
					</div>
					
					<div class="tab-pane member-offdays" id="member-offdays">
						<div class="panel panel-default">
							<div class="panel-body">
							<input type="hidden" value="<?php echo $apt_all_staff[0]['id']; ?>" id="staff_offdays_id" />
							<?php
							/* Get Offdays Information */
							$schedule_offdays->provider_id = $apt_all_staff[0]['id'];
							$all_off_days = $schedule_offdays->read_all_offs_by_provider();
						  
							if(sizeof($all_off_days)!=0) {
							  foreach($all_off_days as $trun){
								$arr_all_off_day [] = $trun->off_date;
							  }
							}
							
							
							$year_arr = array(date('Y'),date('Y')+1);
							$month_num=date('n');

							if(isset($_GET['y']) && in_array($_GET['y'],$year_arr)) {
							 $year = $_GET['y'];
							} else {
							 $year=date('Y');
							}

							$nextYear = date('Y')+1;
							$date=date('d');
							$month=array(__('January','apt'),__('February','apt'),__('March','apt'),__('April','apt'),__('May','apt'),__('June','apt'),__('July','apt'),__('August','apt'),__('September','apt'),__('October','apt'),__('November','apt'),__('December','apt'));


							echo '<table class="offdaystable">';
							echo '<th colspan=4 align=center><div style="margin-top:10px;">'.__('Provider Name','apt').': '.$apt_all_staff[0]['staff_name'].'<span style="float:right;">'.date('Y').'</span></div></th>';

							for ($reihe=1; $reihe<=4; $reihe++) {
								echo '<tr>';
								for ($spalte=1; $spalte<=3; $spalte++) {
									$this_month=($reihe-1)*3+$spalte;
									$erster=date('w',mktime(0,0,0,$this_month,1,$year));
									$insgesamt=date('t',mktime(0,0,0,$this_month,1,$year));
									if($erster==0) $erster=7;
									echo '<td class="col-md-4 col-sm-4 col-lg-4 col-xs-12">';
									echo '<table align="center" class="table table-bordered table-striped monthtable">';?>
									<th colspan="7" align="center"><?php echo $month[$this_month-1];?>
									
									<div class="pull-right">
										<div class="apt-custom-checkbox">
											<ul class="apt-checkbox-list">
												<li>
													<input type="checkbox" class="fullmonthoff" id="<?php echo $year.'-'.$this_month;?>" <?php  $schedule_offdays->off_year_month=$year.'-'.$this_month;
													if($schedule_offdays->check_full_month_off()==true) { echo " checked "; }  ?> />
													<label for="<?php echo $year.'-'.$this_month;?>"><?php echo __("Full Month","apt");?><span class="ml5r0"></span></label>
												</li>
											</ul>
										</div>
									</div>
									</th>
									<?php 
									echo '<tr><td><b>M</b></td><td><b>T</b></td>';
									echo '<td><b>W</b></td><td><b>T</b></td>';
									echo '<td><b>F</b></td><td class="sat"><b>S</b></td>';
									echo '<td class="sun"><b>S</b></td></tr>';
									echo '<tr class="dateline selmonth_'.$year.'-'.$this_month.'"><br>';
									$i=1;
									while ($i<$erster) {
										echo '<td> </td>';
										$i++;
									}
									$i=1;
									while ($i<=$insgesamt) {
										$rest=($i+$erster-1)%7;
										
										$cal_cur_date =  $year."-".sprintf('%02d', $this_month)."-".sprintf('%02d', $i);
										 
								
										
										if (($i==$date) && ($this_month==$month_num)) {
											
											if(isset($arr_all_off_day)  && in_array($cal_cur_date, $arr_all_off_day)) { 
											  echo '<td  id="'.$year.'-'.$this_month.'-'.$i.'"  class="selectedDate RR"  align=center>';
											} else {
											  echo '<td  id="'.$year.'-'.$this_month.'-'.$i.'"  class="date_single RR"  align=center>';
											}
										
										} else {
											if(isset($arr_all_off_day)  &&  in_array($cal_cur_date, $arr_all_off_day)) { 
											  echo '<td  id="'.$year.'-'.$this_month.'-'.$i.'"  class="selectedDate RR"  align=center>';
											} else {
											   echo '<td  id="'.$year.'-'.$this_month.'-'.$i.'" class="date_single RR"  align=center>';
											}
										}
										
										
										
										if (($i==$date) && ($this_month==$month_num)) {
											echo '<span style="color:#3d3d3d;">'.$i.'</span>';
										}	else if ($rest==6) {
											echo '<span   style="color:#0000cc;">'.$i.'</span>';
										} else if ($rest==0) {
											echo '<span  style="color:#cc0000;">'.$i.'</span>';
										} else {
											echo $i;
										}
										echo "</td>\n";
										if ($rest==0) echo "</tr>\n<tr class='dateline selmonth_".$year."-".$this_month."'>\n";
										$i++;
									}
									echo '</tr>';
									echo '</table>';
									echo '</td>';
								}
								echo '</tr>';
							}

							echo '</table>';
							?>
							</div>
						</div>
					</div>
				
				</div><!-- end first -->
			</div
	
	
<?php 

}
/* Refetch Provider Offtime on Add Delete */
if(isset($_POST['staff_action'],$_POST['staff_id']) && $_POST['staff_action']=='refresh_staff_offtimes' && $_POST['staff_id']!=''){ 
	$bwid = $_POST['bwid'];
	$breaks->provider_id = $_POST['staff_id'];
	$apt_offtimes = $breaks->read_offtime(); ?>	
	<div class="table-responsive"> 
		<table id="apt-staff-member-offtime-list" class="apt-staff-member-offtime-list table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
			 <thead>
				<tr>
					<th><?php echo __("Start Date","apt");?></th>
					<th><?php echo __("Start Time","apt");?></th>
					<th><?php echo __("End Date","apt");?></th>
					<th><?php echo __("End Time","apt");?></th>
					<th><?php echo __("Action","apt");?></th>
				</tr>
			</thead>
			 <tbody id="staff_offtimes">
				<?php foreach($apt_offtimes as $offtime) { ?>
				<tr id="offtime_detail_<?php echo $offtime->id; ?>">
					<td> <?php echo date_i18n(get_option('appointment_datepicker_format'.'_'.$bwid),strtotime($offtime->offtime_start)); ?></td>
					<td><?php echo date_i18n(get_option('time_format'),strtotime($offtime->offtime_start)); ?></td>
					<td><?php echo date_i18n(get_option('appointment_datepicker_format'.'_'.$bwid),strtotime($offtime->offtime_end)); ?></td>
					<td><?php echo date_i18n(get_option('time_format'),strtotime($offtime->offtime_end)); ?></td>
					<td><a href="javascript:void(0)" data-staffid="<?php echo $_POST['staff_id'];?>" data-otid="<?php echo $offtime->id; ?>" class='btn btn-danger left-margin delete_staff_offtime'><span class='glyphicon glyphicon-remove'></span></a></td>
				</tr>
			<?php } ?>	
			</tbody>
			
			
		</table>
	</div>	
<?php 
} 