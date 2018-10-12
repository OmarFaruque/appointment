<?php 
	include(dirname(__FILE__).'/header.php');
	
	$plugin_url_for_ajax = plugins_url('', dirname(__FILE__));
	
	
	/* Create Location */ 
	$location = new appointment_location();
	$location->business_owner_id = $bwidFDB;
	$category = new appointment_category();
	$service = new appointment_service();
	$general = new appointment_general();
	$staff = new appointment_staff();
	$schedule = new appointment_schedule();
	$breaks = new appointment_schedule_breaks();
	$schedule_offdays = new appointment_schedule_offdays();
	$ssp = new appointment_service_schedule_price();
	$apt_image_upload= new appointment_image_upload();

	$general->business_owner_id = $bwidFDB;

/* Get All Services */
$service->location_id = $_SESSION['apt_location'];
$service->business_owner_id = $bwidFDB;
$apt_services = $service->readAll();
	
/* Get All WP Users */
$staff->location_id = $_SESSION['apt_location'];
$all_existing_users = $staff->readAll_existing_users();
$apt_all_staff = $staff->readAll_with_disables();
$location_all_staff = $staff->countAll();
/* Get All Locations */
$location_sortby = get_option('appointment_location_sortby' . '_' . $bwidFDB);
$apt_locations = $location->readAll('','','');
$temp_locatio_name = array();
$interval = 15;
$currstaff_key = 0;
$apt_currency_symbol = get_option('appointment_currency_symbol' . '_' . $bwidFDB);
$user_sp='';
$user_sp_manager='';
if(current_user_can('apt_staff') && !current_user_can('manage_options')) {
	$user_sp = 'Y';
}if(current_user_can('apt_manager') && !current_user_can('manage_options')) {
	$user_sp_manager = 'Y';
}
?>
<div id="apt-staff-panel" class="panel tab-content">
	
		<div class="apt-staff-list col-md-3 col-sm-3 col-xs-12 col-lg-3">
			<div class="apt-staff-container">
				<h3><?php echo __("Staff Members","apt");?><span>(<?php echo $location_all_staff;?>)</span>
					<?php if($user_sp!='Y' && $user_sp_manager!='Y'){ ?>
					<button id="apt-add-new-staff" class="pull-right btn btn-circle btn-info" rel="popover" data-placement='bottom' title="<?php echo __("Add New Staff Member","apt");?>"> <i class="fa fa-user-plus custom-icon-space"></i><?php echo __("Add","apt");?></button>
					<?php } ?>
					
					
					<div id="popover-content-wrapper" style="display: none">
						<div class="arrow"></div>
					 <form id="apt_create_staff" method="post">
					  <table class="form-horizontal" cellspacing="0">
						<tbody>
						<tr>
							<td width="110px">
							<div class="pull-right apt-custom-radio">
								<ul class="custom-staff-width apt-radio-list ">
									<li>
										<input type="radio" id="apt-new-user" class="apt-radio apt-new-usercl" name="staff-new-exist-user" value="N" />
										<label for="apt-new-user"><span></span><?php echo __("New User","apt");?></label>
									</li>
								</ul>
							</div>
							</td>
							<td>
								<div class="pull-left apt-custom-radio">
									<ul class="apt-radio-list">
										<li>
											<input type="radio" checked="checked" id="apt-existing-user" class="apt-radio apt-existing-usercl" name="staff-new-exist-user" value="E" />
										<label for="apt-existing-user"><span></span><?php echo __("Existing User","apt");?></label>
										</li>
									</ul>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<label for="apt-select-user" class="apt-existing-user-data" ><?php echo __("User","apt");?></label>
							</td>
							<td><div class="apt-existing-user-data">	
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
							<td><div class="apt-new-user-data hide-div">	<input type="text" class="form-control" id="apt-staff-username" name="apt_newuser_username"  /></div></td>
						</tr>
						<tr class="form-field form-required" >
							<td><label for="apt-staff-password"  class="apt-new-user-data hide-div"><?php echo __("Password","apt");?></label></td>
							<td><div class="apt-new-user-data hide-div">	<input type="password" class="form-control" id="apt-staff-password" value=""  name="apt_newuser_password"  /></div></td>
						</tr>
						<tr class="form-field form-required">
							<td><label class="apt-new-user-data hide-div" for="ab-newstaff-fullname"><?php echo __("Full name","apt");?></label></td>
							<td><input type="text" class="form-control apt-new-user-data hide-div" id="apt-staff-fullname" name="apt_newuser_fullname" value=""  /></td>
						</tr>
						<tr class="form-field form-required">
							<td><label class="apt-new-user-data hide-div" for="ab-newstaff-fullname"><?php echo __("Email","apt");?></label></td>
							<td><input type="email" class="form-control apt-new-user-data hide-div" id="apt-staff-email" name="apt_newuser_email" value=""   /></td>
						</tr>
						<tr>
							<td></td>
							<td>
								<a id="apt_create_staff_btn" value="Create Staff" class="btn btn-info" href="javascript:void(0);"><?php echo __("Create","apt");?></a>
								<a id="apt-close-popover-new-staff" class="btn btn-default" href="javascript:void(0)"><?php echo __("Cancel","apt");?></a>
							</td>
						</tr>
						</tbody>
					</table>
					</form>
					</div>
					
				</h3><!-- end popover -->
				
				<ul class="nav nav-tab nav-stacked apt-left-staff" id="apt-staff-sortable">
					<?php foreach($apt_all_staff  as $apt_staffkey => $apt_staff){ 
					
					
					if($user_sp_manager=='' && $user_sp=='Y' && $apt_staff['id']!=$current_user->ID){continue;}
					if($user_sp_manager=='' && $user_sp=='Y'){
						$currstaff_key = $apt_staffkey;
					}
			
					?>
					
					
					<li class="staff-list br-2" data-staff_id="<?php echo $apt_staff['id']; ?>" id="staff_detail_<?php echo $apt_staff['id']; ?>">
						<a href="javascript:void(0)" data-toggle="pill">
						<!-- <span class="apt-staff-clone"><button class="btn btn-circle btn-success pull-right apt-clone-staff" data-pid="<?php //echo $apt_staff['id']; ?>" title="<?php //echo __("Reset","apt");?>Clone Staff Member"><i class="fa fa-clone"></i></button></span> -->
						<span class="apt-staff-image"><img class="apt-staf-img-small" src="<?php if($apt_staff['image']==''){ echo $plugin_url_for_ajax.'/assets/images/staff.png';}else{
						echo site_url()."/wp-content/uploads".$apt_staff['image'];}?>" /></span>
						<span class="apt-staff-name f-letter-capitalize"><?php echo $apt_staff['staff_name']; ?></span>
						</a>
						<?php if(current_user_can('manage_options')){?>
						<span class="apt-manager-star">
							<input <?php if(isset($apt_staff['caps']['apt_manager'])){ echo "checked='checked'"; } ?> type="checkbox" data-staff_id="<?php echo $apt_staff['id']; ?>" id="apt_staff_manager<?php echo $apt_staff['id']; ?>" class="apt-checkbox apt_staff_manager" />
							<label for="apt_staff_manager<?php echo $apt_staff['id']; ?>" title="<?php echo __("Manager","apt");?>"><span><i class="fa fa-star"></i><br/ ><span class="apt-text"></span></span></label>
						</span><?php }?>
					</li>
					<?php } ?>
				</ul>
			</div>	
		</div>
	
	<div class="panel-body">
		<div class="apt-staff-details tab-content col-md-9 col-sm-9 col-lg-9 col-xs-12">
			<!-- right side common menu for staff -->
			<?php if(isset($apt_all_staff[$currstaff_key])){
					$service->provider_id = $apt_all_staff[$currstaff_key]['id'];
					if($apt_all_staff[$currstaff_key]['schedule_type']=='M'){$wl_end=5;}else{$wl_end=1;}
										
					$schedule->provider_id = $apt_all_staff[$currstaff_key]['id'];
					$ins_update_status = $schedule->check_sechedule_exist_for_provider();
		
			?>	
			<div class="apt-staff-top-header">
				<span class="apt-staff-member-name pull-left f-letter-capitalize" data-staff_id="<?php echo $apt_all_staff[$currstaff_key]['id']; ?>"><?php echo $apt_all_staff[$currstaff_key]['staff_name']; ?></span>
				
				<?php if($user_sp!='Y' && $user_sp_manager!='Y'){ ?>
				<button id="apt-delete-staff-member" class="pull-right btn btn-circle btn-danger" rel="popover" data-placement='bottom' title="<?php echo __("Delete Member?","apt");?>"> <i class="fa fa-trash"></i></button><?php } ?>
				
				
				<div id="popover-delete-member" style="display: none;">
					<div class="arrow"></div>
					<?php if($service->total_staff_services()>0){?>
						<span><?php echo __("Unable to delete staff,having linked services","apt");?></span>
					<?php }else{?>
					<table class="form-horizontal" cellspacing="0">
						<tbody>
							<tr>
								<td>
									<button data-staff_id="<?php echo $apt_all_staff[$currstaff_key]['id']; ?>" id="delete_staff" value="Delete" class="btn btn-danger" type="submit"><?php echo __("Yes","apt");?></button>
									<button id="apt-close-popover-delete-staff" class="btn btn-default" href="javascript:void(0)"><?php echo __("Cancel","apt");?></button>
								</td>
							</tr>
						</tbody>
					</table>
					<?php }?>
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
			
				<div class="omar container-fluid tab-content apt-staff-right-details">
					
					<div class="tab-pane active col-lg-12 col-md-12 col-sm-12 col-xs-12 member-details" id="member-details">
						
						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
							<div class="apt-member-image-uploader">
						
								<img id="bdsdu<?php echo $apt_all_staff[$currstaff_key]['id']; ?>locimage" src="<?php if($apt_all_staff[$currstaff_key]['image']==''){ echo $plugin_url_for_ajax.'/assets/images/staff.png';}else{
								echo site_url()."/wp-content/uploads".$apt_all_staff[$currstaff_key]['image'];
								}?>" class="apt-staff-image br-100" height="100" width="100">
									<label for="apt-upload-imagebdsdu<?php echo $apt_all_staff[$currstaff_key]['id']; ?>" <?php if($apt_all_staff[$currstaff_key]['image']==''){ echo "style='display:block'"; }else{ echo "style='display:none'"; } ?> class="apt-staff-img-icon-label show_image_icon_add<?php echo $apt_all_staff[$currstaff_key]['id']; ?>">
										<i class="apt-camera-icon-common br-100 fa fa-camera"></i>
										<i class="pull-left fa fa-plus-circle fa-2x"></i>
									</label>
									<input data-us="bdsdu<?php echo $apt_all_staff[$currstaff_key]['id']; ?>" class="hide apt-upload-images" type="file" name="" id="apt-upload-imagebdsdu<?php echo $apt_all_staff[$currstaff_key]['id']; ?>"  />
									
									
									<a id="apt-remove-staff-imagebdsdu<?php echo $apt_all_staff[$currstaff_key]['id']; ?>" <?php if($apt_all_staff[$currstaff_key]['image']==''){ echo "style='display:none;'";}  ?> class="pull-left br-100 btn-danger apt-remove-staff-img btn-xs apt_remove_image" rel="popover" data-placement='bottom' title="<?php echo __("Remove Image?","apt");?>"> <i class="fa fa-trash" title="<?php echo __("Remove Staff Image","apt");?>"></i></a>
									<div style="display: none;" class="apt-popover br-5" id="popover-apt-remove-staff-imagebdsdu<?php echo $apt_all_staff[$currstaff_key]['id']; ?>">
									<span class="apt-popover-title"><?php echo __("Delete Image","apt");?></span>
										<span class="apt-popover-content">
											<div class="apt-arrow"></div>
											<a href="javascript:void(0)" value="Delete" data-mediaid="<?php echo $apt_all_staff[$currstaff_key]['id']; ?>" data-mediasection='staff' data-mediapath="<?php echo $apt_all_staff[$currstaff_key]['image'];?>" data-imgfieldid="bdsdu<?php echo $apt_all_staff[$currstaff_key]['id']; ?>uploadedimg"	
											class="btn btn-danger btn-sm apt_delete_image"><?php echo __("Yes","apt");?></a>
											<a href="javascript:void(0)" id="popover-apt-remove-staff-imagebdsdu<?php echo $apt_all_staff[$currstaff_key]['id']; ?>" class="btn btn-default btn-sm close_delete_popup" href="javascript:void(0)"><?php echo __("Cancel","apt");?></a>
										</span>
									</div><!-- end pop up -->
							</div>	
							<div id="apt-image-upload-popupbdsdu<?php echo $apt_all_staff[$currstaff_key]['id']; ?>" class="apt-image-upload-popup modal fade" tabindex="-1" role="dialog">
								<div class="vertical-alignment-helper">
									<div class="modal-dialog modal-md vertical-align-center">
										<div class="modal-content">
											<div class="modal-header">
												<div class="col-md-12 col-xs-12">
													<a data-us="bdsdu<?php echo $apt_all_staff[$currstaff_key]['id']; ?>" class="btn btn-success apt_upload_img" data-imageinputid="apt-upload-imagebdsdu<?php echo $apt_all_staff[$currstaff_key]['id']; ?>" ><?php echo __("Crop & Save","apt");?></a>
													<button type="button" class="btn btn-default hidemodal" data-dismiss="modal" aria-hidden="true"><?php echo __("Cancel","apt");?></button>
												</div>	
											</div>
											<div class="modal-body">
												<img id="apt-preview-imgbdsdu<?php echo $apt_all_staff[$currstaff_key]['id']; ?>" />
											</div>
											<div class="modal-footer">
												<div class="col-md-12 np">
													<div class="col-md-4 col-xs-12">
														<label class="pull-left"><?php echo __("File size","apt");?></label> <input type="text" class="form-control" id="bdsdu<?php echo $apt_all_staff[$currstaff_key]['id']; ?>filesize" name="filesize" />
													</div>	
													<div class="col-md-4 col-xs-12">	
														<label class="pull-left"><?php echo __("H","apt");?></label> <input type="text" class="form-control" id="bdsdu<?php echo $apt_all_staff[$currstaff_key]['id']; ?>h" name="h" /> 
													</div>
													<div class="col-md-4 col-xs-12">	
														<label class="pull-left"><?php echo __("W","apt");?></label> <input type="text" class="form-control" id="bdsdu<?php echo $apt_all_staff[$currstaff_key]['id']; ?>w" name="w" />
													</div>
													<input type="hidden" id="bdsdu<?php echo $apt_all_staff[$currstaff_key]['id']; ?>x1" name="x1" />
													 <input type="hidden" id="bdsdu<?php echo $apt_all_staff[$currstaff_key]['id']; ?>y1" name="y1" />
													<input type="hidden" id="bdsdu<?php echo $apt_all_staff[$currstaff_key]['id']; ?>x2" name="x2" />
													<input type="hidden" id="bdsdu<?php echo $apt_all_staff[$currstaff_key]['id']; ?>y2" name="y2" />
													<input id="bdsdu<?php echo $apt_all_staff[$currstaff_key]['id']; ?>bdimagetype" type="hidden" name="bdimagetype"/>
													<input type="hidden" id="bdsdu<?php echo $apt_all_staff[$currstaff_key]['id']; ?>bdimagename" name="bdimagename" value="" />
													</div>
											</div>							
										</div>		
									</div>			
								</div>			
							</div>

							<input name="image" id="bdsdu<?php echo $apt_all_staff[$currstaff_key]['id'];?>uploadedimg" type="hidden" value="<?php echo $apt_all_staff[$currstaff_key]['image'];?>" />
						</div>
					
						<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
							<form action="post" id="staff_personal_detail<?php echo $apt_all_staff[$currstaff_key]['id'];?>" class="staff_personal_detail">
							<table class="apt-staff-common-table">
								<tbody>
									<tr>
										<td><label for="apt-member-name"><?php echo __("User Name","apt");?></label></td>
										<td><input type="text" readonly value="<?php echo $apt_all_staff[$currstaff_key]['username']; ?>" class="form-control" id="apt-member-name" /></td>
									</tr>
									<tr>
										<td><label for="staff_name_<?php echo $apt_all_staff[$currstaff_key]['id']; ?>"><?php echo __("Full Name","apt");?></label></td>
										<td><input type="text" class="form-control" id="staff_name_<?php echo $apt_all_staff[$currstaff_key]['id']; ?>" value="<?php echo $apt_all_staff[$currstaff_key]['staff_name']; ?>"/></td>
									</tr>
									
									<tr>
										<td><label for="staff_description_<?php echo $apt_all_staff[$currstaff_key]['id']; ?>"><?php echo __("Desc","apt");?></label></td>
										<td><textarea class="form-control" id="staff_description_<?php echo $apt_all_staff[$currstaff_key]['id']; ?>"><?php echo $apt_all_staff[$currstaff_key]['description']; ?></textarea></td>
									</tr>
									<tr>
										<td><label for="phone-number"><?php echo __("Phone","apt");?></label></td>
										<td><input type="tel" class="form-control staff_phone_number" id="staff_phone_<?php echo $apt_all_staff[$currstaff_key]['id']; ?>" value="<?php echo $apt_all_staff[$currstaff_key]['phone']; ?>" name="staff_phone" /></td>
									</tr>
									
									<tr>
										<td><label for="staff_timezone_<?php echo $apt_all_staff[$currstaff_key]['id']; ?>"><?php echo __("Time Zone","apt"); ?></label></td>
										<td>
											<select class="selectpicker" id="staff_timezone_<?php echo $apt_all_staff[$currstaff_key]['id']; ?>" data-size="10" style="display: none;">
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '1'){ echo "selected"; } ?> timeZoneId="1" gmtAdjustment="GMT-12:00" useDaylightTime="0" value="-12">(GMT-12:00) International Date Line West</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '2'){ echo "selected"; } ?> timeZoneId="2" gmtAdjustment="GMT-11:00" useDaylightTime="0" value="-11">(GMT-11:00) Midway Island, Samoa</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '3'){ echo "selected"; } ?> timeZoneId="3" gmtAdjustment="GMT-10:00" useDaylightTime="0" value="-10">(GMT-10:00) Hawaii</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '4'){ echo "selected"; } ?> timeZoneId="4" gmtAdjustment="GMT-09:00" useDaylightTime="1" value="-9">(GMT-09:00) Alaska</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '5'){ echo "selected"; } ?> timeZoneId="5" gmtAdjustment="GMT-08:00" useDaylightTime="1" value="-8">(GMT-08:00) Pacific Time (US & Canada)</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '6'){ echo "selected"; } ?> timeZoneId="6" gmtAdjustment="GMT-08:00" useDaylightTime="1" value="-8">(GMT-08:00) Tijuana, Baja California</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '7'){ echo "selected"; } ?> timeZoneId="7" gmtAdjustment="GMT-07:00" useDaylightTime="0" value="-7">(GMT-07:00) Arizona</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '8'){ echo "selected"; } ?> timeZoneId="8" gmtAdjustment="GMT-07:00" useDaylightTime="1" value="-7">(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '9'){ echo "selected"; } ?> timeZoneId="9" gmtAdjustment="GMT-07:00" useDaylightTime="1" value="-7">(GMT-07:00) Mountain Time (US & Canada)</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '10'){ echo "selected"; } ?> timeZoneId="10" gmtAdjustment="GMT-06:00" useDaylightTime="0" value="-6">(GMT-06:00) Central America</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '11'){ echo "selected"; } ?> timeZoneId="11" gmtAdjustment="GMT-06:00" useDaylightTime="1" value="-6">(GMT-06:00) Central Time (US & Canada)</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '12'){ echo "selected"; } ?> timeZoneId="12" gmtAdjustment="GMT-06:00" useDaylightTime="1" value="-6">(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '13'){ echo "selected"; } ?> timeZoneId="13" gmtAdjustment="GMT-06:00" useDaylightTime="0" value="-6">(GMT-06:00) Saskatchewan</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '14'){ echo "selected"; } ?> timeZoneId="14" gmtAdjustment="GMT-05:00" useDaylightTime="0" value="-5">(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '15'){ echo "selected"; } ?> timeZoneId="15" gmtAdjustment="GMT-05:00" useDaylightTime="1" value="-5">(GMT-05:00) Eastern Time (US & Canada)</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '16'){ echo "selected"; } ?> timeZoneId="16" gmtAdjustment="GMT-05:00" useDaylightTime="1" value="-5">(GMT-05:00) Indiana (East)</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '17'){ echo "selected"; } ?> timeZoneId="17" gmtAdjustment="GMT-04:00" useDaylightTime="1" value="-4">(GMT-04:00) Atlantic Time (Canada)</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '18'){ echo "selected"; } ?> timeZoneId="18" gmtAdjustment="GMT-04:00" useDaylightTime="0" value="-4">(GMT-04:00) Caracas, La Paz</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '19'){ echo "selected"; } ?> timeZoneId="19" gmtAdjustment="GMT-04:00" useDaylightTime="0" value="-4">(GMT-04:00) Manaus</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '20'){ echo "selected"; } ?> timeZoneId="20" gmtAdjustment="GMT-04:00" useDaylightTime="1" value="-4">(GMT-04:00) Santiago</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '21'){ echo "selected"; } ?> timeZoneId="21" gmtAdjustment="GMT-03:30" useDaylightTime="1" value="-3.5">(GMT-03:30) Newfoundland</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '22'){ echo "selected"; } ?> timeZoneId="22" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3">(GMT-03:00) Brasilia</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '23'){ echo "selected"; } ?> timeZoneId="23" gmtAdjustment="GMT-03:00" useDaylightTime="0" value="-3">(GMT-03:00) Buenos Aires, Georgetown</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '24'){ echo "selected"; } ?> timeZoneId="24" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3">(GMT-03:00) Greenland</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '25'){ echo "selected"; } ?> timeZoneId="25" gmtAdjustment="GMT-03:00" useDaylightTime="1" value="-3">(GMT-03:00) Montevideo</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '26'){ echo "selected"; } ?> timeZoneId="26" gmtAdjustment="GMT-02:00" useDaylightTime="1" value="-2">(GMT-02:00) Mid-Atlantic</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '27'){ echo "selected"; } ?> timeZoneId="27" gmtAdjustment="GMT-01:00" useDaylightTime="0" value="-1">(GMT-01:00) Cape Verde Is.</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '28'){ echo "selected"; } ?> timeZoneId="28" gmtAdjustment="GMT-01:00" useDaylightTime="1" value="-1">(GMT-01:00) Azores</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '29'){ echo "selected"; } ?> timeZoneId="29" gmtAdjustment="GMT+00:00" useDaylightTime="0" value="0">(GMT+00:00) Casablanca, Monrovia, Reykjavik</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '30'){ echo "selected"; } ?> timeZoneId="30" gmtAdjustment="GMT+00:00" useDaylightTime="1" value="0">(GMT+00:00) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '31'){ echo "selected"; } ?> timeZoneId="31" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '32'){ echo "selected"; } ?> timeZoneId="32" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '33'){ echo "selected"; } ?> timeZoneId="33" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '34'){ echo "selected"; } ?> timeZoneId="34" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '35'){ echo "selected"; } ?> timeZoneId="35" gmtAdjustment="GMT+01:00" useDaylightTime="1" value="1">(GMT+01:00) West Central Africa</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '36'){ echo "selected"; } ?> timeZoneId="36" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Amman</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '37'){ echo "selected"; } ?> timeZoneId="37" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Athens, Bucharest, Istanbul</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '38'){ echo "selected"; } ?> timeZoneId="38" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Beirut</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '39'){ echo "selected"; } ?> timeZoneId="39" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Cairo</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '40'){ echo "selected"; } ?> timeZoneId="40" gmtAdjustment="GMT+02:00" useDaylightTime="0" value="2">(GMT+02:00) Harare, Pretoria</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '41'){ echo "selected"; } ?> timeZoneId="41" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '42'){ echo "selected"; } ?> timeZoneId="42" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Jerusalem</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '43'){ echo "selected"; } ?> timeZoneId="43" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Minsk</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '44'){ echo "selected"; } ?> timeZoneId="44" gmtAdjustment="GMT+02:00" useDaylightTime="1" value="2">(GMT+02:00) Windhoek</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '45'){ echo "selected"; } ?> timeZoneId="45" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="3">(GMT+03:00) Kuwait, Riyadh, Baghdad</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '46'){ echo "selected"; } ?> timeZoneId="46" gmtAdjustment="GMT+03:00" useDaylightTime="1" value="3">(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '47'){ echo "selected"; } ?> timeZoneId="47" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="3">(GMT+03:00) Nairobi</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '48'){ echo "selected"; } ?> timeZoneId="48" gmtAdjustment="GMT+03:00" useDaylightTime="0" value="3">(GMT+03:00) Tbilisi</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '49'){ echo "selected"; } ?> timeZoneId="49" gmtAdjustment="GMT+03:30" useDaylightTime="1" value="3.5">(GMT+03:30) Tehran</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '50'){ echo "selected"; } ?> timeZoneId="50" gmtAdjustment="GMT+04:00" useDaylightTime="0" value="4">(GMT+04:00) Abu Dhabi, Muscat</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '51'){ echo "selected"; } ?> timeZoneId="51" gmtAdjustment="GMT+04:00" useDaylightTime="1" value="4">(GMT+04:00) Baku</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '52'){ echo "selected"; } ?> timeZoneId="52" gmtAdjustment="GMT+04:00" useDaylightTime="1" value="4">(GMT+04:00) Yerevan</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '53'){ echo "selected"; } ?> timeZoneId="53" gmtAdjustment="GMT+04:30" useDaylightTime="0" value="4.5">(GMT+04:30) Kabul</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '54'){ echo "selected"; } ?> timeZoneId="54" gmtAdjustment="GMT+05:00" useDaylightTime="1" value="5">(GMT+05:00) Yekaterinburg</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '55'){ echo "selected"; } ?> timeZoneId="55" gmtAdjustment="GMT+05:00" useDaylightTime="0" value="5">(GMT+05:00) Islamabad, Karachi, Tashkent</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '56'){ echo "selected"; } ?> timeZoneId="56" gmtAdjustment="GMT+05:30" useDaylightTime="0" value="5.5">(GMT+05:30) Sri Jayawardenapura</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '57'){ echo "selected"; } ?> timeZoneId="57" gmtAdjustment="GMT+05:30" useDaylightTime="0" value="5.5">(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '58'){ echo "selected"; } ?> timeZoneId="58" gmtAdjustment="GMT+05:45" useDaylightTime="0" value="5.75">(GMT+05:45) Kathmandu</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '59'){ echo "selected"; } ?> timeZoneId="59" gmtAdjustment="GMT+06:00" useDaylightTime="1" value="6">(GMT+06:00) Almaty, Novosibirsk</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '60'){ echo "selected"; } ?> timeZoneId="60" gmtAdjustment="GMT+06:00" useDaylightTime="0" value="6">(GMT+06:00) Astana, Dhaka</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '61'){ echo "selected"; } ?> timeZoneId="61" gmtAdjustment="GMT+06:30" useDaylightTime="0" value="6.5">(GMT+06:30) Yangon (Rangoon)</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '62'){ echo "selected"; } ?> timeZoneId="62" gmtAdjustment="GMT+07:00" useDaylightTime="0" value="7">(GMT+07:00) Bangkok, Hanoi, Jakarta</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '63'){ echo "selected"; } ?> timeZoneId="63" gmtAdjustment="GMT+07:00" useDaylightTime="1" value="7">(GMT+07:00) Krasnoyarsk</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '64'){ echo "selected"; } ?> timeZoneId="64" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '65'){ echo "selected"; } ?> timeZoneId="65" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Kuala Lumpur, Singapore</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '66'){ echo "selected"; } ?> timeZoneId="66" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Irkutsk, Ulaan Bataar</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '67'){ echo "selected"; } ?> timeZoneId="67" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Perth</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '68'){ echo "selected"; } ?> timeZoneId="68" gmtAdjustment="GMT+08:00" useDaylightTime="0" value="8">(GMT+08:00) Taipei</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '69'){ echo "selected"; } ?> timeZoneId="69" gmtAdjustment="GMT+09:00" useDaylightTime="0" value="9">(GMT+09:00) Osaka, Sapporo, Tokyo</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '70'){ echo "selected"; } ?> timeZoneId="70" gmtAdjustment="GMT+09:00" useDaylightTime="0" value="9">(GMT+09:00) Seoul</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '71'){ echo "selected"; } ?> timeZoneId="71" gmtAdjustment="GMT+09:00" useDaylightTime="1" value="9">(GMT+09:00) Yakutsk</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '72'){ echo "selected"; } ?> timeZoneId="72" gmtAdjustment="GMT+09:30" useDaylightTime="0" value="9.5">(GMT+09:30) Adelaide</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '73'){ echo "selected"; } ?> timeZoneId="73" gmtAdjustment="GMT+09:30" useDaylightTime="0" value="9.5">(GMT+09:30) Darwin</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '74'){ echo "selected"; } ?> timeZoneId="74" gmtAdjustment="GMT+10:00" useDaylightTime="0" value="10">(GMT+10:00) Brisbane</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '75'){ echo "selected"; } ?> timeZoneId="75" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="10">(GMT+10:00) Canberra, Melbourne, Sydney</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '76'){ echo "selected"; } ?> timeZoneId="76" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="10">(GMT+10:00) Hobart</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '77'){ echo "selected"; } ?> timeZoneId="77" gmtAdjustment="GMT+10:00" useDaylightTime="0" value="10">(GMT+10:00) Guam, Port Moresby</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '78'){ echo "selected"; } ?> timeZoneId="78" gmtAdjustment="GMT+10:00" useDaylightTime="1" value="10">(GMT+10:00) Vladivostok</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '79'){ echo "selected"; } ?> timeZoneId="79" gmtAdjustment="GMT+11:00" useDaylightTime="1" value="11">(GMT+11:00) Magadan, Solomon Is., New Caledonia</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '80'){ echo "selected"; } ?> timeZoneId="80" gmtAdjustment="GMT+12:00" useDaylightTime="1" value="12">(GMT+12:00) Auckland, Wellington</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '81'){ echo "selected"; } ?> timeZoneId="81" gmtAdjustment="GMT+12:00" useDaylightTime="0" value="12">(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>
												<option <?php if($apt_all_staff[$currstaff_key]['timezoneID'] == '82'){ echo "selected"; } ?> timeZoneId="82" gmtAdjustment="GMT+13:00" useDaylightTime="0" value="13">(GMT+13:00) Nuku'alofa</option>
											</select>
										</td>
										
									</tr>
									
									<tr>
										<td><label for="phone-number"><?php echo __("Schedule Type","apt");?></label></td>
										<td>
											<label for="staff_schedule_<?php echo $apt_all_staff[$currstaff_key]['id']; ?>">
												<input type="checkbox" <?php if($apt_all_staff[$currstaff_key]['schedule_type']=='M'){ echo "checked";} ?> id="staff_schedule_<?php echo $apt_all_staff[$currstaff_key]['id']; ?>"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Monthly","apt");?>" data-off="<?php echo __("Weekly","apt");?>" data-onstyle="info" data-offstyle="warning" >
											</label>
											<input type="hidden" id="curr_staff_schedule_<?php echo $apt_all_staff[$currstaff_key]['id']; ?>" value="<?php echo $apt_all_staff[$currstaff_key]['schedule_type']; ?>" />
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Enable Booking","apt");?></label></td>
										<td>
											<label for="staff_status_<?php echo $apt_all_staff[$currstaff_key]['id']; ?>">
												<input type="checkbox" <?php if($apt_all_staff[$currstaff_key]['status']=='E'){ echo "checked";} ?> id="staff_status_<?php echo $apt_all_staff[$currstaff_key]['id']; ?>" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","apt");?>" data-off="<?php echo __("Off","apt");?>" data-onstyle="success" data-offstyle="default" >
											</label>
										</td>
									</tr>
									<tr>
										<td></td>
										<td><a data-staff_id="<?php echo $apt_all_staff[$currstaff_key]['id']; ?>" href="javascript:void(0)" class="btn btn-success apt-btn-width update_staff_detail"><?php echo __("Save","apt");?></a>
									</tr>
								</tbody>
							</table>
							</form>	
						</div>

							
					</div>
					<?php
					/* Get Staff Services */
					$service->provider_id = $apt_all_staff[$currstaff_key]['id'];
					$apt_staff_services = $service->readall_services_of_provider();
					$staffservces = array();
					foreach($apt_staff_services as $staffservice){$staffservces[]=$staffservice->service_id;}
					?>
					<div class="tab-pane apt-services-list col-lg-12 col-md-12 col-sm-12 col-xs-12 member-services" id="member-services">
						<div class="tab-content">
							<div class="panel panel-default">
								<h4 class="apt-right-header"><?php echo __("Services provided by","apt");?> <strong><?php echo $apt_all_staff[$currstaff_key]['staff_name']; ?> (<span data-total_service="<?php echo sizeof($apt_services);?>" class="staff_servicecount_<?php echo $apt_all_staff[$currstaff_key]['id']; ?>"><?php echo sizeof($staffservces);?></span>)</strong></h4>
									<div id="accordion" class="panel-group" role="tablist" >
										<div class="panel panel-default apt-staff-service-panel">
											<div class="panel-heading" role="tab" >
												<h4 class="panel-title">
													<label for="all-services">
														<input type="checkbox" data-staff_id="<?php echo $apt_all_staff[$currstaff_key]['id']; ?>" <?php if(sizeof($staffservces)==sizeof($apt_services)){ echo"checked";}?> class="link_service linkallservices" value="all" id="all-services" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","apt");?>" data-off="<?php echo __("Off","apt");?>" data-onstyle="success" data-offstyle="default" >
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
													<label for="staff-service<?php echo $apt_service->id;?><?php echo $apt_all_staff[$currstaff_key]['id']; ?>">
														<input type="checkbox" class="link_service apt_all_service<?php echo $apt_all_staff[$currstaff_key]['id']; ?>" <?php if(in_array($apt_service->id,$staffservces)){ echo "checked";}?> value="<?php echo $apt_service->id; ?>" data-staff_id="<?php echo $apt_all_staff[$currstaff_key]['id']; ?>"  id="staff-service<?php echo $apt_service->id;?><?php echo $apt_all_staff[$currstaff_key]['id']; ?>" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","apt");?>" data-off="<?php echo __("Off","apt");?>" data-onstyle="success" data-offstyle="default" >
													</label>
													<span><?php echo $apt_service->service_title; ?></span>
													
													<span class="pull-right">
														<span class="apt-service-time-member"><?php  $durationformat = $general->convertToHoursMins($apt_service->duration,'%02dh %02dm');
														if(is_numeric(strpos($durationformat,'00h'))){
														echo str_replace('00h','',$durationformat).'in';}
														elseif(is_numeric(strpos($durationformat,'00m'))){ echo str_replace('00m','',$durationformat);}else{
														echo $durationformat;
														} ?></span>
														<span class="apt-service-price-member"><?php echo $apt_currency_symbol; ?><?php if($apt_service->offered_price != ""){
															echo $apt_service->offered_price;
														}else{ echo $apt_service->amount; }?></span>
														
														<div class="apt-show-hide">
															<input type="checkbox" name="apt-show-hide" class="apt-show-hide-checkbox ssp-show-hide-checkbox" id="ssp<?php echo $apt_service->id;?>" >
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
																		if($apt_all_staff[$currstaff_key]['schedule_type']=='M'){
																			$week_name=array(__('First Week','apt'),__('Second Week','apt'),__('Third Week ','apt'),__('Fourth Week ','apt'),__('Fifth Week ','apt'));
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
																			<h4 class="apt-right-header"><?php echo $week_name[$w-1].__(" time scheduling of","apt");?> <strong><?php echo $apt_all_staff[$currstaff_key]['staff_name']; ?></strong></h4>
																				<ul class="list-unstyled" id="apt-staff-price">
																					<?php 	$day_name=array(__('Monday','apt'),__('Tuesday','apt'),__('Wednesday','apt'),__('Thursday','apt'),__('Friday','apt'),__('Saturday','apt'),__('Sunday','apt'));
																					for($i=1;$i<=7;$i++) {
																						$ssp->provider_id = $apt_all_staff[$currstaff_key]['id'];
																						$ssp->service_id = $apt_service->id;
																						$ssp->weekid = $w;
																						$ssp->weekdayid = $i;
																						$apt_ssp_info = $ssp->readOne_ssp();
																					?>
																					<li class="active">
																						<div class="col-sm-12 col-md-4 col-lg-4 col-xs-12 np top5">
																							<span class="col-sm-6 col-md-7 col-lg-7 col-xs-6 apt-day-name"><?php echo $day_name[$i-1];?></span>
																							<span class="col-sm-6 col-md-5 col-lg-5 col-xs-6">
																								<a class="btn btn-small btn-success apt-small-br-btn apt_add_ssp" data-serviceamout="<?php echo $apt_service->amount; ?>"  data-weekid="<?php echo $w;?>" data-dayid="<?php echo $i;?>" data-serviceid="<?php echo $apt_service->id; ?>" data-staffid="<?php echo $apt_all_staff[$currstaff_key]['id']; ?>" data-mainid="<?php echo $w;?>_<?php echo $i;?>"><?php echo __("Add price","apt");?></a>
																							</span>	
																						</div>	
																						<div class="col-sm-12 col-md-8 col-lg-8 col-xs-12">
																							<ul class="apt-price-row pull-left list-unstyled" id="apt_ssp_<?php echo $apt_service->id;?>_<?php echo $w;?>_<?php echo $i;?>">
																								<?php foreach($apt_ssp_info as $apt_ssp){?>
																								<li class="fullwidth bb1f0" id="apt_ssp_detail_<?php echo $apt_ssp->id; ?>">
																										<span class="col-sm-5 col-md-12 col-lg-5 col-xs-12 apt-staff-price-schedule np">
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
																												  
																													<span class="apt-price-hours-to"><?php echo __("to","apt");?></span>
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
																										<span class="col-sm-7 col-md-12 col-lg-7 col-xs-12 npr">
																											<table  class="apt-staff-common-table">
																												<tbody>
																													<tr class="col-lg-7 col-sm-6 col-xs-6 npr">
																													<td class="col-xs-4"><?php echo __("Price","apt");?></td>
																														<td class="col-xs-8"><div class="input-group"><span class="input-group-addon"><?php echo $apt_currency_symbol; ?></span><input type="text" id="ssp_price_<?php echo $apt_ssp->id; ?>" class="form-control" value="<?php echo $apt_ssp->price; ?>" placeholder="<?php echo __("$10","apt");?>" /></div></td>
																													</tr>
																													<tr class="col-lg-5 col-sm-6 col-xs-6 npr">
																														<td class="col-xs-6"><a href="javascript:void(0)" id="apt-delete-staff-price<?php echo $apt_ssp->id; ?>" data-sspid="<?php echo $apt_ssp->id; ?>" class="pull-right btn btn-circle btn-default delete_ssp_popover" rel="popover" data-placement='bottom' title="<?php echo __("Are You Sure?","apt");?>"> <i class="fa fa-trash"></i></a>
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
																														<td class="col-xs-6"><a href="javascript:void(0)" data-sspid="<?php echo $apt_ssp->id; ?>" class="pull-right btn btn-circle btn-success update_ssp_detail" title="Save"> <i class="fa fa-save"></i></a></td>																								</tr>
																													
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
								if($apt_all_staff[$currstaff_key]['schedule_type']=='M'){
									$week_name=array(__('First Week','apt'),__('Second Week','apt'),__('Third Week','apt'),__('Fourth Week','apt'),__('Fifth Week','apt'));
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
											<h4 class="apt-right-header"><?php echo $week_name[$w-1].__(" time scheduling of","apt");?> <strong><?php echo $apt_all_staff[$currstaff_key]['staff_name']; ?></strong></h4>
												<ul class="list-unstyled" id="apt-staff-timing">
												    <?php 	$day_name=array(__('Monday','apt'),__('Tuesday','apt'),__('Wednesday','apt'),__('Thursday','apt'),__('Friday','apt'),__('Saturday','apt'),__('Sunday','apt'));
													for($i=1;$i<=7;$i++) {
													/* Get selected Provider Time Schedule */
													$schedule->week_id = $w; 
													$schedule->provider_id = $apt_all_staff[$currstaff_key]['id']; 
													$schedule->weekday_id = $i; 
													$schedule->readOne_new(); ?>	
													<li class="active">
														<span class="col-sm-3 col-md-3 col-lg-3 col-xs-6 apt-day-name"><?php echo $day_name[$i-1];?></span>
														<span class="col-sm-2 col-md-2 col-lg-2 col-xs-6">
															<label class="apt-col2" for="off_day_<?php echo $w;?>_<?php echo $i;?>">
																<input type="checkbox" class="staff_dayoff"  <?php if(!$schedule->get_offdays_new()){ echo " checked "; }?> name="off_day_[<?php echo $w;?>][<?php echo $i;?>]" id="off_day_<?php echo $w;?>_<?php echo $i;?>" data-mainid="<?php echo $w;?>_<?php echo $i;?>" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","apt");?>" data-off="<?php echo __("Off","apt");?>" data-onstyle="success" data-offstyle="default" />
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
																		   <option  value="<?php echo $timeValue; ?>" <?php if(strtotime($schedule->dayend_time)==strtotime($timetocmp)){ echo "selected"; }?>><?php echo date_i18n(get_option('time_format'),strtotime($timetoprint)); ?></option>
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
										<a href="javascript:void(0)" data-st="<?php echo $apt_all_staff[$currstaff_key]['schedule_type']; ?>" id="<?php echo $apt_all_staff[$currstaff_key]['id']; ?>" value="" name="update_staff_schedule" class="btn btn-success apt-btn-width col-xs-offset-3 update_staff_schedule"><?php echo __("Save Setting","apt");?></a>
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
								if($apt_all_staff[$currstaff_key]['schedule_type']=='M'){
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
												<h4 class="apt-right-header"><?php echo $week_name[$w-1].__(" of","apt");?> <strong><?php echo $apt_all_staff[$currstaff_key]['staff_name']; ?></strong></h4>
													<ul class="list-unstyled" id="apt-staff-breaks">
														 <?php 	$day_name=array(__('Monday','apt'),__('Tuesday','apt'),__('Wednesday','apt'),__('Thursday','apt'),__('Friday','apt'),__('Saturday','apt'),__('Sunday','apt'));
															for($i=1;$i<=7;$i++) {
															/* Get selected Provider Time Schedule */
															$breaks->week_id = $w; 
															$breaks->provider_id = $apt_all_staff[$currstaff_key]['id']; 
															$breaks->weekday_id = $i; 
															$all_day_breaks = $breaks->read_day_breaks(); ?>
														<li class="active">
															<span class="col-sm-5 col-md-3 col-lg-3 col-xs-6 apt-day-name"><?php echo $day_name[$i-1];?></span>
															<span class="col-sm-5 col-md-2 col-lg-2 col-xs-6">
																<a id="apt-add-staff-breaks" data-staff_id="<?php echo $apt_all_staff[$currstaff_key]['id']; ?>" data-weekid="<?php echo $w;?>" data-dayid="<?php echo $i;?>" class="btn btn-small btn-success apt-small-br-btn staff_add_break"><?php echo __("Add Break","apt");?></a>
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
																			<!-- <input type="hidden" id="staff_breakend_<?php echo $day_break['break_id']; ?>" value=""/>-->			
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
					$breaks->provider_id = $apt_all_staff[$currstaff_key]['id'];
					$apt_offtimes = $breaks->read_offtime();
					?>
					<div class="tab-pane member-offtime" id="member-offtime">
						<div class="panel panel-default">
							<div class="panel-body">
								<div class="apt-member-offtime-inner">
								<h3><?php echo __("Off Times for","apt");?> <b><?php echo $apt_all_staff[$currstaff_key]['staff_name']; ?></b></h3>
									<div class="col-md-6 col-sm-7 col-xs-12 col-lg-6 mb-10">
										<label><?php echo __("Add new off time","apt");?></label>
										<div id="offtime-daterange" class="form-control"  >
											<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
											<span></span> <i class="fa fa-caret-down"></i>
										</div>
									</div>
									<div class="col-md-2 col-sm-4 col-xs-12 col-lg-2">
										<a href="javascript:void(0)" class="form-group btn btn-info mt-20 add_staff_offtime" data-sid="<?php echo $apt_all_staff[$currstaff_key]['id']; ?>" name=""> <?php echo __("Add Break","apt");?></a>
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
													<td> <?php echo date_i18n(get_option('appointment_datepicker_format' . '_'. $bwidFDB),strtotime($offtime->offtime_start)); ?></td>
													<td><?php echo date_i18n(get_option('time_format'),strtotime($offtime->offtime_start)); ?></td>
													<td><?php echo date_i18n(get_option('appointment_datepicker_format' . '_'. $bwidFDB),strtotime($offtime->offtime_end)); ?></td>
													<td><?php echo date_i18n(get_option('time_format'),strtotime($offtime->offtime_end)); ?></td>
													<td><a href="javascript:void(0)" data-staffid="<?php echo $apt_all_staff[$currstaff_key]['id'];?>"  data-otid="<?php echo $offtime->id; ?>" class='btn btn-danger left-margin delete_staff_offtime'><span class='glyphicon glyphicon-remove'></span></a></td>
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
							<input type="hidden" value="<?php echo $apt_all_staff[$currstaff_key]['id']; ?>" id="staff_offdays_id" />
							<?php
							/* Get Offdays Information */
							$schedule_offdays->provider_id = $apt_all_staff[$currstaff_key]['id'];
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
							echo '<th colspan=4 align=center><div style="margin-top:10px;">'.__('Provider Name','apt').': <b>'.$apt_all_staff[$currstaff_key]['staff_name'].'</b><span style="float:right;">'.date('Y').'</span></div></th>';

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
													<input type="checkbox" class="fullmonthoff" id="<?php echo $year.'-'.$this_month;?>" <?php  $schedule_offdays->off_year_month=$year.'-'.$this_month;	if($schedule_offdays->check_full_month_off()==true) { echo " checked "; }  ?> />
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
			</div>
			<?php }else{
				echo __("No Staff Found","apt");
			} ?>

		</div>
		
	</div>
</div>
<?php 
	include(dirname(__FILE__).'/footer.php');
?>
<script>
	var staffObj={"plugin_path":"<?php echo $plugin_url_for_ajax;?>"}
</script>