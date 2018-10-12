<?php 
if ( ! defined( 'ABSPATH' ) ) exit;

include(dirname(__FILE__).'/header.php');
$plugin_url_for_ajax = plugins_url('',  dirname(__FILE__));

$staff = new appointment_staff();
$service = new appointment_service();
$category = new appointment_category();
$clients = new appointment_clients();

/* Get Service info */
$serviceprice='';
$serviceduration='';
$serviceduration_val='';
$servicecounter = 0;
$category->location_id = $_SESSION['apt_location'];
$category->business_owner_id = get_current_user_id();
$all_categories = $category->readAll();
/* Get Provider Info */
$staff->location_id = $_SESSION['apt_location'];
$apt_all_staff = $staff->readAll_with_disables();
/* Get Register Clients */
$clients->location_id = $_SESSION['apt_location'];
$all_clients_info = $clients->get_registered_clients();
?>
<script type="text/javascript">
    var calenderObj = {"plugin_path":"<?php echo $plugin_url_for_ajax;?>","ak_wp_lang":"<?php echo $ak_wplang[0]; ?>",'cal_first_day':"<?php echo get_option('start_of_week'); ?>",
	'time_format':"<?php echo $wpTimeFormat; ?>"};
</script>
	
<div id="apt-calendar-all omar">
	<div class="panel-body">
	<div class="apt-legends-main">
        <div class="apt-legends-inner col-md-12">
            <h4><?php echo __("Legends","apt");?>:</h4>
            <ul class="list-inline">
                <li><i class="fa fa-check txt-success icon-space"></i><?php echo __("Confirmed","apt");?></li>
                <li><i class="fa fa-pencil-square-o txt-info  icon-space"></i><?php echo __("Rescheduled","apt");?></li>
                <li><i class="fa fa-ban txt-danger icon-space"></i><?php echo __("Rejected","apt");?></li>
                <li><i class="fa fa-times txt-primary icon-space"></i><?php echo __("Cancelled By Client","apt");?></li>
                <li><i class="fa fa-info-circle txt-warning icon-space"></i><?php echo __("Pending","apt");?></li>
				<li><i class="fa fa-thumbs-o-up txt-success icon-space"></i><?php echo __("Appointment Completed","apt");?></li>
                <li><i class="fa fa-thumbs-o-down txt-danger icon-space"></i><?php echo __("Mark As No Show","apt");?></li>
           </ul>
        </div>
    </div>
	<hr id="hr" />
	<div class="apt-calendar-top-bar">
		<div class="col-md-4 col-sm-6 col-xs-12 col-lg-4 mb-10">
			<label class="custom-width"><?php echo __("Select Option To Show Bookings","apt");?></label>
			<div id="apt_reportrange" class="form-control custom-width" >
				<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
				<span></span> <i class="fa fa-caret-down"></i>
			</div>
			<input type="hidden" id="apt_booking_startdate" value="" />
			<input type="hidden" id="apt_booking_enddate" value="" />
		</div>
			
		<div class="col-md-3 col-sm-6 col-xs-6 col-lg-3 mb-10">
			<label><?php echo __("Select Service","apt");?></label><br />
		
			<select id="apt_booking_filterservice" class="selectpicker" data-size="10" style="display: none;" data-live-search="true">
				<option value=""><?php echo __("All Services","apt");?></option>
				<?php foreach($all_categories as $apt_category){ 
					  $service->service_category = $apt_category->id;
					  $apt_services = $service->readAll_category_services(); ?>
				<optgroup label="<?php echo $apt_category->category_title;?>"> 	
				<?php foreach($apt_services as $apt_service){ ?>							
					<option <?php if(isset($_SESSION['apt_booking_filterservice']) && $_SESSION['apt_booking_filterservice']==$apt_service->id){ echo "selected='selected'"; } ?> value="<?php echo $apt_service->id; ?>"><?php echo $apt_service->service_title; ?></option>
				 
				<?php } ?>
				</optgroup> 
				<?php } ?>
			</select>
		</div>
		<?php

		if($user_sp_manager=='Y' || current_user_can('manage_options')){ ?>
		<div class="col-md-3 col-sm-6 col-xs-6 col-lg-3 mb-10">		
			<label><?php echo __("Staff Member","apt");?></label><br />
			<select id="apt_booking_filterprovider" class="selectpicker mb-10" data-size="10" style="display: none;">
				<option value=""><?php echo __("All Service Provider","apt");?></option>
				<?php foreach($apt_all_staff as $apt_staff){ ?>
				<option <?php if(isset($_SESSION['apt_booking_filterstaff']) && $_SESSION['apt_booking_filterstaff']==$apt_staff['id']){ echo "selected='selected'"; } ?>  value="<?php echo $apt_staff['id']; ?>"><?php echo $apt_staff['staff_name']; ?></option>
				<?php } ?>
			</select>
		</div><?php } ?>
		<div class="col-md-2 col-sm-6 col-xs-12 col-lg-2 pull-right mb-10">
			<button type="button" id="apt_filter_appointments" class="form-group btn btn-info apt-btn-width apt-submit-btn mt-20" name=""><?php echo __("Submit","apt");?></button>
		</div>
	</div>
	
	</div>

	<div id="apt_calendar" class=""></div> 
    
	
	<div id="add-new-booking-details" class="modal fade show-popup" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="vertical-alignment-helper">
			<div class="modal-dialog modal-md vertical-align-center">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" id="manual_appt" aria-hidden="true">×</button>
						<h4 class="modal-title"><?php echo __("Book Manual Appointment","apt");?></h4>
					</div>
					<div class="modal-body">
						<ul class="nav nav-tabs">
							<li class="active" id="add_app_det"><a data-toggle="tab" href="#add-appointment-details"><?php echo __("Appointment Details","apt");?></a></li>
							<li id="add_cust_det"><a data-toggle="tab" href="#add-customer-details"><?php echo __("Customer Details","apt");?></a></li>
						</ul>
						<div class="tab-content">
							<div id="add-appointment-details" class="tab-pane fade in active">
							<form id="booking_appt_form">
								<table>
									<tbody>										
										
										
										<tr>
											<td><label><?php echo __("Provider","apt");?></label></td>
											<td>
												<div class="form-group">
													<select id="apt_booking_provider_manual" data-size="5" class="selectpicker form-control" data-live-search="true" data-live-search-placeholder="<?php echo __("Search","apt");?>" data-actions-box="true"  >
														<option value=""><?php _e('Select Staff', 'apt'); ?></option>
														<?php foreach($apt_all_staff as $apt_staff){ ?>
														<option value="<?php echo $apt_staff['id']; ?>"><?php echo $apt_staff['staff_name']; ?></option>
														<?php } ?>
													</select>
												</div>
											</td>
										</tr>
										<tr>	
											<td><label><?php echo __("Service","apt");?></label></td>
											<td>
												<div class="form-group">
													<select id="apt_booking_service_manual" data-size="5" class="selectpicker form-control" data-live-search="true" data-live-search-placeholder="<?php echo __("Search","apt");?>" data-actions-box="true"  >
														<?php foreach($all_categories as $apt_category){ 
														  $service->service_category = $apt_category->id;
														  $apt_services = $service->readAll_category_services(); ?>
															<optgroup label="<?php echo $apt_category->category_title;?>"> 	
															<?php foreach($apt_services as $apt_service){ 
															if($servicecounter==0){ 
															$serviceduration_val=$apt_service->duration; 
															$serviceprice=$apt_service->amount; 
															if(floor($apt_service->duration/60)!=0){ $serviceduration .= floor($apt_service->duration/60); $serviceduration .= __(" Hrs","apt"); } 
															if($apt_service->duration%60 !=0){  $serviceduration .= $apt_service->duration%60; $serviceduration .= __(" Mins","apt"); }
															
															} ?>							
																<option value="<?php echo $apt_service->id; ?>"><?php echo $apt_service->service_title; ?></option>
															 
															<?php $servicecounter++; } ?>
															</optgroup> 
															<?php } ?>
													</select>
												</div>
											</td>
										</tr>
										<tr>
									
											<td></td>
											<td>
												<div class="apt-col6 apt-w-50">
													<div class="form-control">
														<span><?php echo $apt_currency_symbol;?></span><span id="apt_service_price_manual"><?php echo $serviceprice;?></span>
													</div>
												</div>	
												<div class="apt-col6 apt-w-50 float-right">
													<div class="form-control">
														<i class="fa fa-clock-o"></i><span id="apt_service_duration_manual"><?php echo $serviceduration;?></span>
														<input type="hidden" id="apt_service_duration_val_manual" value="<?php echo $serviceduration_val;?>"/>
													</div>
												</div>
												
											</td>
										</tr>
										<tr>
											<td><label for="apt-service-duration"><?php echo __("Date & Time","apt");?></label></td>
											<td>
												<div class="apt-col6 apt-w-50">
													<input id="apt_booking_date_manual" class="form-control" data-provide="datepicker" value="<?php echo date_i18n('m/d/Y');?>"/>
												</div>
												<div class="apt-col6 apt-w-50 float-right">
													<select id="apt_booking_time_manual" class="selectpicker" data-size="5" style="display: none;" >
														<option value="08:00">08:00 AM</option>
														<option value="08:30">08:30 AM</option>
														<option value="09:00">09:00 AM</option>
														<option value="09:30">09:30 AM</option>
														<option value="10:00">10:00 AM</option>
														<option value="10:30">10:30 AM</option>
														<option value="11:00">11:00 AM</option>
														<option value="11:30">11:30 AM</option>
														<option value="12:00">12:00 PM</option>
														<option value="12:30">12:30 PM</option>
														<option value="13:00">01:00 PM</option>
														<option value="13:30">01:30 PM</option>
														<option value="14:00">02:00 PM</option>
														<option value="14:30">02:30 PM</option>
														<option value="15:00">03:00 PM</option>
														<option value="15:30">03:30 PM</option>
														<option value="16:00">04:00 PM</option>
														<option value="16:30">04:30 PM</option>
														<option value="17:00">05:00 PM</option>
														<option value="17:30">05:30 PM</option>
														<option value="18:00">06:00 PM</option>
														<option value="18:30">06:30 PM</option>
														<option value="19:00">07:00 PM</option>
														<option value="19:30">07:30 PM</option>
														<option value="20:00">08:00 PM</option>
													</select>
												</div>
												
											</td>
										</tr>
										<tr>
											<td colspan="2" style="text-align:center;font-weight: bold;display: none;">You have event for following time, still you want to do overlap booking?</td>
										</tr>
										<tr>
											<td><?php echo __("Notes","apt");?></td>
											<td><textarea class="form-control notes" id="apt_booking_note_manual" name="manual_notes"></textarea></td>
										</tr>
											
									</tbody>
								</table>
								
								<div class="modal-footer">
								<div class="apt-col12 apt-footer-popup-btn">
									<div class="col-xs-12 ta-c">
										<a data-toggle="tab" id="customer_add_new" href="#add-customer-details" name="submit" class="btn btn-success apt-next-add-booking" type="submit"><?php echo __("Continue","apt");?></a>
									</div>
								</div>
							</div>
							</form>	
							</div>
							
							
							<div id="add-customer-details" class="tab-pane fade">
								<div class="apt-search-customer-main" id="searchcustomerdiv">
									<div class="search-container">
										<select id="apt_booking_client_manual" class="selectpicker form-control" data-live-search="true" data-live-search-placeholder="<?php echo __("Search","apt");?>" data-actions-box="true"  >
											<option value=""><?php echo __("-- Select customer --","apt");?></option>
											<?php foreach($all_clients_info as $clients_info){ ?>
											<option value="<?php echo $clients_info->ID;?>"><?php echo $clients_info->display_name;?></option>
										<?php } ?>
										 </select>
										 <div class="apt-searching-customer" id="loading"><i class="fa fa-circle-o-notch fa-spin"></i><?php echo __("Please Wait...","apt");?></div>
									</div>
								</div>
								<hr id="hr" />
								<div class="new-customer-details" id="">
								
								
								<form id="manual_booking_form" action="" method="post">
									<table>
										<tbody>	
										<?php
										if(get_option('appointment_guest_user_checkout' . '_' . get_current_user_id())=='D'){ ?>
										<tr id="client_username">
											<td><?php echo __("Username","apt");?></td>
											<td><input type="text" class="form-control" name="apt_mb_username" id="apt_clientusername_manual" /></td>
										</tr>
										<tr id="client_password">
											<td><?php echo __("Password","apt");?></td>
											<td><input type="password" class="form-control client_pass" name="apt_mb_password" id="apt_clientpassword_manual" /></td>
										</tr>
										<?php } ?>
										
										<tr>
											<td><?php echo __("Name","apt");?></td>
											<td><input type="text" class="form-control client_display" id="apt_clientname_manual" name="apt_mb_clientname" placeholder="<?php echo __("Customer Name","apt");?>" /></td>
										</tr>
										<tr>
											<td><?php echo __("Email","apt");?></td>
											<td><input data-emailtype="N" type="email" class="form-control client_email_dis" name="apt_mb_clientemail" data-value="" id="apt_clientemail_manual" placeholder="andrew@example.com" /></td>
										</tr>
										<tr>
											<td><?php echo __("Phone","apt");?></td>
											<td><input type="tel" class="form-control client_phone_dis phone_number" id="apt_clientphone_manual" name="apt_mb_clientphone" /></td>
										</tr>
										<tr>
											<td><?php echo __("Address","apt");?></td>
											<td>
												<div class="apt-col12"><textarea id="apt_clientaddress_manual" class="form-control" name="apt_mb_clientaddress"></textarea></div>
											</td>
										</tr>
										<tr>
											<td></td>
											<td>
												<div class="apt-col6 apt-w-50">
													<input type="text" class="form-control" id="apt_clientcity_manual" name="apt_mb_clientcity" placeholder="<?php echo __("City","apt");?>" />
												</div>
												<div class="apt-col6 apt-w-50 float-right">
													<input type="text" class="form-control" id="apt_clientstate_manual" name="apt_mb_clientstate" placeholder="<?php echo __("State","apt");?>" />
												</div>
											</td>
										</tr>
										<tr>
											<td></td>	
											<td>	
												<div class="apt-col6 apt-w-50">
													<input type="text" class="form-control" id="apt_clientzip_manual" name="apt_mb_clientzip" placeholder="<?php echo __("Zip","apt");?>" />
												</div>	
												<div class="apt-col6 apt-w-50 float-right">
													<input type="text" class="form-control" id="apt_clientcountry_manual" name="apt_mb_clientcountry" placeholder="<?php echo __("Country","apt");?>" />
													
												</div>	
												
											</td>
										</tr>
										</tbody>
									</table>
									
									<div class="modal-footer">
										<div class="apt-col12 apt-footer-popup-btn">
											
											<div class="col-xs-12 ta-c">
												<a id="apt_book_manual_appointment" href="javascript:void(0)" class="btn btn-success"><?php echo __("Book Appointment","apt");?></a>
											</div>
										</div>
									</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div><!-- end details of booking -->
		
	</div>
<?php 
	include(dirname(__FILE__).'/footer.php');
?>