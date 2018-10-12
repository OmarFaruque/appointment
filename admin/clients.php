<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
include_once "header.php";
global $wpdb;
$page_title = "Clients";

$plugin_url_for_ajax = plugins_url('',  dirname(__FILE__));
$user_ID = get_current_user_id();


$apt_bookings = new appointment_booking();
$clients = new appointment_clients();

if(isset($_SESSION['apt_all_loc_clients']) && $_SESSION['apt_all_loc_clients']=='Y'){
	$clients->location_id = 'All';
	$all_clients_info = $clients->get_registered_clients();

}else{

	$clients->location_id = $_SESSION['apt_location'];
	/* $all_clients_info = get_users( array( 'role' => 'apt_users' ,'meta_key' => 'apt_client_locations' ,'meta_value' => '#'.$_SESSION['apt_location'].'#')); */
	$all_clients_info = $clients->get_all_registered_clients_by_location_id($_SESSION['apt_location']);
}	

 
/** Code For Guest User **/
$all_guesuser_info = $clients->get_all_guest_users_orders();
?>
<div id="apt-customers-listing" class="panel tab-content">
	<div class="panel panel-default">
		<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#registered-customers-listing"><?php echo __("Registered Customers","apt");?></a></li>
			<li><a data-toggle="tab" href="#guest-customers-listing"><?php echo __("Guest Customers","apt");?></a></li>
			<?php if(current_user_can('manage_options')){ ?>
			<li class="pull-right">
				<div class="apt-custom-checkbox">
					<ul class="apt-checkbox-list">
						<li>
							<input <?php if(isset($_SESSION['apt_all_loc_clients']) && $_SESSION['apt_all_loc_clients']=='Y'){ echo "checked='checked'"; } ?> type="checkbox" id="apt_all_locations_customers" />
							<label for="apt_all_locations_customers"><?php echo __("All Locations Customers","apt");?><span></span></label>
						</li>
					</ul>
				</div>
			</li><?php } ?>
		</ul>
		<div class="tab-content">
			<div id="registered-customers-listing" class="tab-pane fade in active">
				<h3><?php echo __("Registered Customers","apt");?></h3>  <div class=""></div>
					<div id="accordion" class="panel-group">
						<table id="registered-client-table" class="display responsive nowrap table table-striped table-bordered" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th><?php echo __("Client Name","apt");?></th>
										<th><?php echo __("Email","apt");?></th>
										<th><?php echo __("Phone","apt");?></th>
										<th class="thd-w200"><?php echo __("Bookings","apt");?></th>
									</tr>
								</thead>
								<tbody id="apt_registered_list" >
									<?php 
										$apt_bookings = new appointment_booking(); 
										foreach($all_clients_info as $client_info){
												$apt_bookings->location_id=$_SESSION['apt_location'];
												$apt_bookings->client_id = $client_info->ID;
												$all_booking = $apt_bookings->get_client_all_bookings_by_client_id();
												
												if(sizeof($all_booking)>0){
													$allboking = sizeof($all_booking)-1;
													$clientlastoid = $all_booking[$allboking]->order_id;
													$clients->order_id = $clientlastoid;
													$order_client_info = $clients->get_client_info_by_order_id();
												}
											?>
												<tr id="client_detail<?php echo $client_info->ID; ?>">
													<td><?php echo __(stripslashes_deep($client_info->display_name),"apt");?></td>
													<td><?php echo __($client_info->user_email,"apt");?></td>
													<td><?php echo __((isset($client_info->client_phone))?$client_info->client_phone:'',"apt");?></td>
																
													
													
												<td class="apt-bookings-td">
													<a class="btn btn-primary apt_show_bookings " data-method="registered" data-client_id='<?php echo $client_info->ID; ?>' href="#registered-details" data-toggle="modal"><i class="icon-calendar icons icon-space"></i> <?php echo __("Bookings","apt");?><span class="badge"><?php echo sizeof($all_booking); ?></span></a>
																										
													<a data-poid="popover-delete-reg-client<?php echo $client_info->ID;?>" class="col-sm-offset-1 btn btn-danger apt-delete-popover " rel="popover" data-placement='bottom' title="<?php echo __("Delete this Client?","apt");?>"> <i class="fa fa-trash icon-space " title="<?php echo __("Delete Client","apt");?>"></i><?php echo __("Delete","apt");?></a>
													<div id="popover-delete-reg-client<?php echo $client_info->ID;?>" style="display: none;">
														<div class="arrow"></div>
														<table class="form-horizontal" cellspacing="0">
															<tbody>
																<tr>
																	<td>
																		<button data-method="registered" data-client_id="<?php echo $client_info->ID;?>" value="Delete" class="btn btn-danger btn-sm apt_delete_client" type="submit"><?php echo __("Yes","apt");?></button>
																		<button class="btn btn-default btn-sm apt-close-popover-delete" href="javascript:void(0)"><?php echo __("Cancel","apt");?></button>
																	</td>
																</tr>
															</tbody>
														</table>
													</div>											
													</td>
												</tr>
										   <?php  } ?>
								</tbody>
							</table>
						
						<div id="registered-details" class="modal fade booking-details-modal">
							<div class="modal-dialog modal-lg">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title"><?php echo __("Registered Customers Bookings","apt");?></h4>
									</div>
									<div class="modal-body">
										<div class="table-responsive"> 
										<table id="registered-client-booking-details"  class="display responsive table table-striped table-bordered" cellspacing="0" width="100%">
												<thead>
													<tr>
														<th style="width: 9px !important;"><?php echo __("#","apt");?></th>
														<th style="width: 48px !important;"><?php echo __("Client Name","apt");?></th>
														<th style="width: 67px !important;"><?php echo __("Service Provider","apt");?></th>
														<th style="width: 73px !important;"><?php echo __("Service","apt");?></th>
														<th style="width: 44px !important;"><?php echo __("Appt. Date","apt");?></th>
														<th style="width: 44px !important;"><?php echo __("Appt. Time","apt");?></th>
														<th style="width: 39px !important;"><?php echo __("Status","apt");?></th>
														<th style="width: 70px !important;"><?php echo __("Payment Method","apt");?></th>
														<th style="width: 257px !important;"><?php echo __("More Details","apt");?></th>
													</tr>
												</thead>
												<tbody id="apt_client_bookingsregistered"></tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				
			</div>
			<div id="guest-customers-listing" class="tab-pane fade">
				<h3><?php echo __("Guest Customers","apt");?></h3>
				<div id="accordion" class="panel-group">

					<table id="guest-client-table" class="display responsive table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
								
								<th><?php echo __("Client Name","apt");?></th>
								<th><?php echo __("Email","apt");?></th>
								<th><?php echo __("Phone","apt");?></th>
								<th class="thd-w200"><?php echo __("Bookings","apt");?></th>
								
							</tr>
						</thead>
						<tbody id="apt_guest_list">
							
							<?php foreach($all_guesuser_info as $client_info){						
									$apt_bookings->order_id = $client_info->order_id;				
									$all_booking=$apt_bookings->get_guest_users_booking_by_order_id();
								?>
									<tr id="client_detail<?php echo $client_info->order_id; ?>">
										<td><?php echo __(stripslashes_deep($client_info->client_name),"apt");?></td>
										<td><?php echo __($client_info->client_email,"apt");?></td>
										<td><?php echo __($client_info->client_phone,"apt");?></td>
																
										<td class="apt-bookings-td"> 
										<a class="btn btn-primary apt_show_bookings" data-method="guest" data-client_id='<?php echo $client_info->order_id; ?>' href="#guest-details" data-toggle="modal"><i class="icon-calendar icons icon-space"></i><?php echo __("Bookings","apt");?><span class="badge"><?php echo sizeof($all_booking); ?></span></a>
																										
										<a data-poid="popover-delete-guest-client<?php echo $client_info->order_id; ?>" class="col-sm-offset-1 btn btn-danger apt-delete-popover" rel="popover" data-placement='bottom' title="<?php echo __("Delete this Client?","apt");?>"> <i class="fa fa-trash icon-space " title="<?php echo __("Delete Client","apt");?>"></i><?php echo __("Delete","apt");?></a>
										
										<div id="popover-delete-guest-client<?php echo $client_info->order_id; ?>" style="display: none;">
											<div class="arrow"></div>
											<table class="form-horizontal" cellspacing="0">
												<tbody>
													<tr>
														<td>
															<button data-method="guest" data-client_id="<?php echo $client_info->order_id;?>" value="Delete" class="btn btn-danger btn-sm apt_delete_client" type="submit"><?php echo __("Yes","apt");?></button>
															<button class="btn btn-default btn-sm apt-close-popover-delete" href="javascript:void(0)"><?php echo __("Cancel","apt");?></button>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</td>									
									</tr>
									   <?php  } ?>
							</tbody>
					</table>
						
					<div id="guest-details" class="modal fade booking-details-modal">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title"><?php echo __("Guest Customers Bookings","apt");?></h4>
								</div>
								<div class="modal-body">
									<div class="table-responsive">
										<div class="table-responsive"> 
										<table id="guest-client-booking-details" class="display responsive table table-striped table-bordered" cellspacing="0" width="100%">
											<thead>
												<tr>
													<th style="width: 9px !important;"><?php echo __("#","apt");?></th>
													<th style="width: 48px !important;"><?php echo __("Client Name","apt");?></th>
													<th style="width: 67px !important;"><?php echo __("Service Provider","apt");?></th>
													<th style="width: 73px !important;"><?php echo __("Service","apt");?></th>
													<th style="width: 44px !important;"><?php echo __("Appt. Date","apt");?></th>
													<th style="width: 44px !important;"><?php echo __("Appt. Time","apt");?></th>
													<th style="width: 39px !important;"><?php echo __("Status","apt");?></th>
													<th style="width: 70px !important;"><?php echo __("Payment Method","apt");?></th>
													<th style="width: 257px !important;"><?php echo __("More Details","apt");?></th>
												</tr>
											</thead>
											<tbody id="apt_client_bookingsguest">
											</tbody>
										</table>
									</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>
<?php 
	include_once "footer.php";
?>
 <script type="text/javascript">
   var ob_client_listing = {"plugin_path":"<?php echo $plugin_url_for_ajax;?>",   "message_deleteclient":"<?php echo __("Booking(s) for this client will be deleted as well, Do you want to delete it?")?>",   "message_recdelete":"<?php echo __("Record deleted!")?>"   };
</script>