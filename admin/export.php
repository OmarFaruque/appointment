<?php 
	include(dirname(__FILE__).'/header.php');
	$plugin_url_for_ajax = plugins_url('', dirname(__FILE__));
	
	/* Create Location */
	$location = new appointment_location();
	$category = new appointment_category();
	$staff = new appointment_staff();
	$service = new appointment_service();
	$general = new appointment_general();
	$bookings = new appointment_booking();
	$clients = new appointment_clients();
	$apt_multilocation = get_option('appointment_multi_location' . '_' . get_current_user_id());
	$general->business_owner_id = get_current_user_id();
	
	if(isset($_SESSION['apt_all_loc_export']) && $_SESSION['apt_all_loc_export']=='Y'){
		$apt_export_location = 'All';
	}else{
		$apt_export_location = $_SESSION['apt_location'];
	}
	/* Staff Filter Dropdown Content */
	$staff->location_id = $apt_export_location;
	$apt_all_staff = $staff->readAll_with_disables('Export');
	/* Service Filter Dropdown Content */
	$service->location_id = $apt_export_location;
	$service->business_owner_id = get_current_user_id();
	$apt_allservices = $service->readAll('Export');
	/* Get All Categories */
	$category->location_id = $apt_export_location;
	$category->business_owner_id = get_current_user_id();
	$all_categories = $category->readAll('Export');
	
	/* Read All Booking of Location */
	$bookings->location_id = $apt_export_location;
	$bookings->business_owner_id = get_current_user_id();
	$all_bookings = $bookings->readAll('','','','','Export');
	/* Get All Locations Info */
	$location->business_owner_id = get_current_user_id();
	$apt_locations = $location->readAll('','','');
?>
<div id="apt-export-details" class="panel tab-content">
	<div class="panel panel-default">
		<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#booking-info-export"><?php echo __("Booking Information","apt");?></a></li>
			<li><a data-toggle="tab" href="#staff-info-export"><?php echo __("Staff Information","apt");?></a></li>
			<li><a data-toggle="tab" href="#services-info-export"><?php echo __("Services Information","apt");?></a></li>
			<li><a data-toggle="tab" href="#category-info-export"><?php echo __("Category Information","apt");?></a></li>
			<?php if($apt_multilocation=='E'){ ?>
			<?php if(current_user_can('manage_options')){?>
			<li><a data-toggle="tab" href="#location-info-export"><?php echo __("Locations Information","apt");?></a></li><?php } ?><?php } ?>
			<?php if(current_user_can('manage_options')){?>
			<li class="pull-right">
				<div class="apt-custom-checkbox">
					<ul class="apt-checkbox-list">
						<li>
							<input <?php if(isset($_SESSION['apt_all_loc_export']) && $_SESSION['apt_all_loc_export']=='Y'){ echo "checked='checked'"; } ?> type="checkbox" id="apt_all_exportdata" />
							<label for="apt_all_exportdata"><?php echo __("All Locations Export","apt");?> <span></span></label>
						</li>
					</ul>
				</div>
			</li><?php } ?>			
		</ul>
		
		<div class="tab-content">
			<!-- booking infomation export -->
			<div id="booking-info-export" class="tab-pane fade in active">
				<h3><?php echo __("Booking Information","apt");?></h3>
				<div id="accordion" class="panel-group">
					
					<form id="" name="" class="" method="post">
						
						<div class="col-md-4 col-sm-6 col-xs-12 col-lg-4 mb-10">
							<label><?php echo __("Select option to show bookings","apt");?></label>
							<div id="apt_reportrange" class="form-control" >
								<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
								<span></span> <i class="fa fa-caret-down"></i>
							</div>
							<input type="hidden" id="apt_booking_startdate" value="" />
							<input type="hidden" id="apt_booking_enddate" value="" />
							
						</div>
							
						<div class="col-md-3 col-sm-6 col-xs-6 col-lg-3 mb-10">
							<label><?php echo __("Select Service","apt");?></label><br />
						
							<select id="apt_booking_service" class="selectpicker" data-size="10" style="display: none;" data-live-search="true">
								<option value=""><?php echo __("All Services","apt");?></option>
								<?php foreach($all_categories as $apt_category){ 
									  $service->service_category = $apt_category->id;
									  $apt_services = $service->readAll_category_services(); ?>
								<optgroup label="<?php echo $apt_category->category_title;?>"> 	
								<?php foreach($apt_services as $apt_service){ ?>							
									<option value="<?php echo $apt_service->id; ?>"><?php echo $apt_service->service_title; ?></option>
								 
								<?php } ?>
								</optgroup> 
								<?php } ?>
							</select>
						</div>
						<div class="col-md-3 col-sm-6 col-xs-6 col-lg-3 mb-10">		
							<label><?php echo __("Staff member","apt");?></label><br />
							<select id="apt_booking_staff" class="selectpicker mb-10" data-size="10" style="display: none;">
								<option value=""><?php echo __("All staff members","apt");?></option>
								<?php foreach($apt_all_staff as $apt_staff){ ?>
								<option value="<?php echo $apt_staff['id']; ?>"><?php echo $apt_staff['staff_name']; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-md-2 col-sm-6 col-xs-12 col-lg-2 mb-10">
							<button type="button" id="apt_filtered_bookings" class="form-group btn btn-info apt-btn-width apt-submit-btn mt-20" name=""><?php echo __("Submit","apt");?></button>
						</div>
						<hr id="hr" />
						<div class="table-responsive">
							<table id="apt_export_bookings" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
								<thead>
									<tr>	
										<th><?php echo __("#","apt");?></th>
										<th><?php echo __("Service","apt");?></th>
										<th><?php echo __("Provider","apt");?></th>
										<th><?php echo __("App. Date","apt");?></th>
										<th><?php echo __("App. Time","apt");?></th>
										<th><?php echo __("App. Price","apt");?></th>
										<th><?php echo __("Customer","apt");?></th>
										<th><?php echo __("Phone","apt");?></th>
										<th><?php echo __("Status","apt");?></th>
									</tr>
								</thead>
								<tbody id="apt_export_bookings_data">
									<?php foreach($all_bookings as $single_booking){ 
										/* Staff Info */
										$staff->id=$single_booking->provider_id;
										$staff_info = $staff->readOne();
										/* Service Info */										
										$service->id=$single_booking->service_id;
										$service->readOne(); 
										/* Client Info */	
										$clients->order_id=$single_booking->order_id;
										$client_info = $clients->get_client_info_by_order_id();
																	
										?>
									<tr>	
										<td><?php echo $single_booking->order_id;?></td>
										<td><?php echo __(stripslashes_deep($service->service_title),"apt");?></td>
										<td><?php echo (isset($staff_info[0]['staff_name']))?__(stripslashes_deep($staff_info[0]['staff_name']),"apt"):'';?></td>
										<td><?php echo __(date_i18n(get_option('appointment_datepicker_format' . '_' . get_current_user_id()),strtotime($single_booking->booking_datetime)),"apt");?></td>
										<td><?php echo __(date_i18n(get_option('time_format'),strtotime($single_booking->booking_datetime)),"apt");?> <?php echo __('to',"apt");?> <?php echo __(date_i18n(get_option('time_format'),strtotime($single_booking->booking_endtime)),"apt");?></td>
										<td><?php echo $general->apt_price_format($single_booking->booking_price);?></td>
										<td><?php echo __(stripslashes_deep($client_info[0]->client_name),"apt");?></td>
										<td><?php if($client_info[0]->client_phone!=''){ echo $client_info[0]->client_phone;} else{echo '-';} ?></td>
										<td><?php if($single_booking->booking_status=='C'){  echo __('Confirmed',"apt"); }
										if($single_booking->booking_status=='R'){  echo __('Rejected',"apt"); }
										if($single_booking->booking_status=='CC'){  echo __('Cancelled by client',"apt"); }
										if($single_booking->booking_status=='A' || $single_booking->booking_status=='' ){ echo __('Active',"apt"); }
										if($single_booking->booking_status=='CS'){ echo __('Cancelled by service provider',"apt");}
										if($single_booking->booking_status=='CO'){ echo __('Completed',"apt"); $booking_st = ''; }
										if($single_booking->booking_status=='MN'){ echo __('Marked as No-Show',"apt"); }
										if($single_booking->booking_status=='RS'){ echo __('Rescheduled',"apt"); }										?></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>	
						</div>	
					</form>	
				</div>
			</div>
			<!-- service provicer information export -->
			<div id="staff-info-export" class="tab-pane fade">
				<h3><?php echo __("Staff Information","apt");?></h3>
				<div id="accordion" class="panel-group">
					
					<form id="" name="" class="" method="post">
						<div class="col-md-3 col-sm-6 col-xs-6 col-lg-3 mb-10">
							<label><?php echo __("Select Staff to export","apt");?></label><br />
						
							<select id="apt_staff_filter" class="selectpicker" data-size="10" style="display: none;" data-live-search="true">
								<option value=""><?php echo __("All staff members","apt");?></option>
								<?php foreach($apt_all_staff as $apt_staff){ ?>
								<option value="<?php echo $apt_staff['staff_name']; ?>"><?php echo $apt_staff['staff_name']; ?></option>
								<?php } ?>
							</select>
						</div>
						<hr id="hr" />
						<div class="table-responsive">
							<table id="staff-info-table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
								<thead>
									<tr>	
										<th><?php echo __("#","apt");?></th>
										<th><?php echo __("Name","apt");?></th>
										<th><?php echo __("Email","apt");?></th>
										<th><?php echo __("Phone","apt");?></th>
										<th><?php echo __("Schedule Type","apt");?></th>
									</tr>
								</thead>
								<tbody>
								<?php foreach($apt_all_staff as $apt_staff){ ?>
									<tr>
										<td><?php echo $apt_staff['id']; ?></td>
										<td><?php echo $apt_staff['staff_name']; ?></td>
										<td><?php echo $apt_staff['email']; ?></td>
										<td><?php echo $apt_staff['phone']; ?></td>
										<td><?php if($apt_staff['schedule_type']=='W'){ echo __('Weekly',"apt"); }else{ echo __('Monthly',"apt"); }  ?></td>
									</tr>	
								<?php } ?>	
								</tbody>
							</table>
						</div>	
					</form>	
				</div>
			</div>
			<!-- services  infomation export -->
			<div id="services-info-export" class="tab-pane fade">
				<h3><?php echo __("Services Information","apt");?></h3>
				<div id="accordion" class="panel-group">
					<form id="" name="" class="" method="post">
						<div class="col-md-3 col-sm-6 col-xs-6 col-lg-3 mb-10">		
							<label><?php echo __("Select service to export","apt");?></label><br />
							<select id="apt_service_filter" class="selectpicker mb-10" data-size="10" style="display: none;">
								<option value=""><?php echo __("All services","apt");?></option>
								<?php foreach($all_categories as $apt_category){ 
									  $service->service_category = $apt_category->id;
									  $apt_services = $service->readAll_category_services(); ?>
								<optgroup label="<?php echo $apt_category->category_title;?>"> 	
								<?php foreach($apt_services as $apt_service){ ?>							
									<option value="<?php echo $apt_service->service_title; ?>"><?php echo $apt_service->service_title; ?></option>
								 
								<?php } ?>
								</optgroup> 
								<?php }?>
							</select>
						</div>
						<hr id="hr" />
						<div class="table-responsive">
							<table id="services-info-table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
								<thead>
									<tr>	
										<th><?php echo __("#","apt");?></th>
										<th><?php echo __("Service Title","apt");?></th>
										<th><?php echo __("Service Category","apt");?></th>
										<th><?php echo __("Duration","apt");?></th>
										<th><?php echo __("Price","apt");?></th>
										<th><?php echo __("Offered Price","apt");?></th>
										<th><?php echo __("Description","apt");?></th>
										
									</tr>
								</thead>
								<tbody>
								<?php foreach($apt_allservices as $apt_singleservice){
											$category->id=$apt_singleservice->category_id;
											$category->readOne(); ?>
									<tr>	
										<td><?php echo $apt_singleservice->id;?></td>
										<td><?php echo $apt_singleservice->service_title;?></td>
										<td><?php echo $category->category_title;?></td>
										<td><?php if(floor($apt_singleservice->duration/60)!=0){ echo floor($apt_singleservice->duration/60); echo __(" Hours","apt"); } ?>  <?php  if($apt_singleservice->duration%60 !=0){ echo $apt_singleservice->duration%60; echo __(" Mintues","apt");} ?></td>
										<td><?php echo $general->apt_price_format($apt_singleservice->amount);?></td>
										<td><?php if($apt_singleservice->offered_price>0){ echo $general->apt_price_format($apt_singleservice->offered_price);}else{ echo '-';}?></td>
										<td><?php echo $apt_singleservice->service_description;?></td>
									</tr>
								<?php } ?>		
								</tbody>
							</table>	
						</div>	
					</form>	
				</div>
			</div>
			<!-- category infomation export -->
			<div id="category-info-export" class="tab-pane fade">
				<div id="accordion" class="panel-group">
					<form id="" name="" class="" method="post">
						<div class="col-md-3 col-sm-6 col-xs-6 col-lg-3 mb-10">
							<label><?php echo __("Select Category to export","apt");?></label><br />
						
							<select id="apt_category_filter" class="selectpicker" data-size="10" style="display: none;" data-live-search="true">
								<option value=""><?php echo __("All Categories members","apt");?></option>
								<?php  foreach($all_categories as $apt_category){  ?>
								<option value="<?php echo $apt_category->category_title;?>"><?php echo $apt_category->category_title;?></option>
								<?php } ?>
							</select>
						</div>
						<hr id="hr" />
						<div class="table-responsive">
							<table id="category-info-table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
								<thead>
									<tr>	
										<th><?php echo __("#","apt");?></th>
										<th><?php echo __("Category Title","apt");?></th>
									</tr>
								</thead>
								<tbody>
								<?php foreach($all_categories as $apt_category){ ?>
									<tr>
										<td><?php echo $apt_category->id;?></td>
										<td><?php echo $apt_category->category_title;?></td>
									</tr>
								<?php } ?>					
								</tbody>
							</table>
						</div>	
					</form>	
				</div>
			</div>
			<!-- Locations infomation export -->
			<?php if($apt_multilocation=='E' && current_user_can('manage_options')){ ?>
			<div id="location-info-export" class="tab-pane fade">
				<div id="accordion" class="panel-group">
					<form id="" name="" class="" method="post">
						<div class="col-md-3 col-sm-6 col-xs-6 col-lg-3 mb-10">
							<label><?php echo __("Select Location to export","apt");?></label><br />
						
							<select id="apt_location_filter" class="selectpicker" data-size="10" style="display: none;" data-live-search="true">
								<option value=""><?php echo __("All Locations members","apt");?></option>
								<?php foreach($apt_locations as $apt_location){  ?> 
								<option value="<?php echo $apt_location->location_title;?>"><?php echo $apt_location->location_title;?></option>
								<?php } ?>
							</select>
						</div>
						<hr id="hr" />
						<div class="table-responsive">
							<table id="location-info-table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
								<thead>
									<tr>	
										<th><?php echo __("#","apt");?></th>
										<th><?php echo __("Title","apt");?></th>
										<th><?php echo __("Description","apt");?></th>
										<th><?php echo __("Email","apt");?></th>
										<th><?php echo __("Phone","apt");?></th>
										<th><?php echo __("Address","apt");?></th>
										<th><?php echo __("City","apt");?></th>
										<th><?php echo __("Zip","apt");?></th>
										<th><?php echo __("State","apt");?></th>
										<th><?php echo __("Country","apt");?></th>
									</tr>
								</thead>
								<tbody>
								<?php foreach($apt_locations as $apt_location){  ?>
									<tr>
										<td><?php echo $apt_location->id;?></td>
										<td><?php echo $apt_location->location_title;?></td>
										<td><?php echo $apt_location->description;?></td>
										<td><?php echo $apt_location->email;?></td>
										<td><?php echo $apt_location->phone;?></td>
										<td><?php echo $apt_location->address;?></td>
										<td><?php echo $apt_location->city;?></td>
										<td><?php echo $apt_location->zip;?></td>
										<td><?php echo $apt_location->state;?></td>
										<td><?php echo $apt_location->country;?></td>
									</tr>
								<?php } ?>					
								</tbody>
							</table>
						</div>	
					</form>	
				</div>
			</div>
			<?php } ?>
			
		</div>
	</div>	
</div>	


 
		
<?php 
	include(dirname(__FILE__).'/footer.php');
?>
