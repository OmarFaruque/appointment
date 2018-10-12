<?php 
include(dirname(__FILE__).'/header.php');
$plugin_url_for_ajax = plugins_url('', dirname(__FILE__));

	global $current_user;
	$current_user = wp_get_current_user();
	
	$location = new appointment_location();
	$location->business_owner_id = get_current_user_id();
	$category = new appointment_category();
	$staff = new appointment_staff();
	$service = new appointment_service();
	$general = new appointment_general();
	$bookings = new appointment_booking();
	$clients = new appointment_clients();
	$loyalty_points = new appointment_loyalty_points();
	$apt_multilocation = get_option('appointment_multi_location' . '_' . $current_user->ID);
	$general->business_owner_id = $current_user->ID;
	
	$curr_bal = 0;
	$loyalty_points->client_id =  $current_user->ID;
	$loyalty_points->get_client_balance();
	if(isset($loyalty_points->balance) && $loyalty_points->balance!=''){
		$curr_bal = $loyalty_points->balance;
	}	

$bookings->client_id=$current_user->ID;
$current_user_bookings=$bookings->get_distinct_bookings_of_client();	
$total_rows = sizeof($current_user_bookings);
if($total_rows > 0){ ?>
<div id="apt-user-appointments">

	<div class="panel-body">	
		<div class="tab-content">
			<h4 class="header4"><?php echo __("My Appointments","apt");?>
			<!-- <span class="pull-right header3"><?php //echo $curr_bal;?> : <?php //echo __("Loyalty Points","apt");?></span> --> <span><a class="btn btn-default" href="<?php echo site_url();?>">Back to site</a></span></h4>
		<form>
					<div class="table-responsive">
						<table id="user-profile-booking-table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th><?php echo __("Order #","apt");?></th>
									<th><?php echo __("Order Date","apt");?></th>
									<th><?php echo __("Order Time","apt");?></th>
									<th><?php echo __("Show All Bookings","apt");?></th>
									<th><?php echo __("Actions","apt");?></th>
								</tr>
							</thead>
							<tbody>
							<?php 
							
							for($i=0;$i<=$total_rows-1;$i++){	
									$bookings->client_id = $current_user->ID;
									$bookings->order_id = $current_user_bookings[$i]->order_id;
									$order_bookings = $bookings->get_client_bookings_by_order_id();
									?>								
										<tr data-oid="<?php echo ($current_user_bookings[$i]->order_id);?>">
										<td><?php echo __($current_user_bookings[$i]->order_id,"apt");?></td>
										<td><?php echo date_i18n(get_option('appointment_datepicker_format' . '_' . get_current_user_id()),strtotime($current_user_bookings[$i]->lastmodify));?></td>
										<td><?php echo date_i18n(get_option('time_format'),strtotime($current_user_bookings[$i]->lastmodify));?></td>
										<td>
										
											<a href="#user-booking-details" data-client_id="<?php echo $current_user->ID;?>" data-order_id="<?php echo ($current_user_bookings[$i]->order_id);?>" data-toggle="modal" data-target="#user-booking-details" class="apt-my-booking-user btn btn-info appointment_client_bookings"><i class="fa fa-eye icon-space"></i><?php echo __("My Bookings","apt");?> <span class="badge br-10"><?php echo sizeof($order_bookings);?></span></a>
										</td>	
										<td>
										
											<a href="<?php echo $plugin_url_for_ajax;?>/assets/lib/admin_general_ajax.php?general_ajax_action=client_download_invoice&order_id=<?php echo ($current_user_bookings[$i]->order_id);?>&client_id=<?php echo $current_user->ID;?>&key=<?php echo 'O'.base64_encode($current_user_bookings[$i]->order_id+1247);?>b" class="btn btn-primary"><i class="fa fa-download icon-space"></i><?php echo __("Download Invoice","apt");?></a>
										</td>
								</tr>
									<?php } ?>															
							</tbody>
						</table>
					</div>	
				<div id="user-booking-details" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
					<div>
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close apt_client_bookingclose" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title"><?php echo __("My Bookings","apt");?></h4>
								</div>
								<div class="modal-body">
									<div class="table-responsive">
										<table id="user-all-bookings-details" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
											<thead>
												<tr>
													<th><?php echo __("Order#","apt");?></th>
													<th><?php echo __("Provider","apt");?></th>
													<th><?php echo __("Service","apt");?></th>
													<th  width="155px;"><?php echo __("Booking Date & Time","apt");?></th>
													<th><?php echo __("Status","apt");?></th>
													<th><?php echo __("Status Note","apt");?></th>
													<th width="140px;"><?php echo __("Action","apt");?></th>
												</tr>
													
											</thead>											
											<tbody id="apt_client_orderbookings"></tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				
				</div>
				</form>
		</div>
	</div>
<?php }else{ ?> 
<div><?php echo __("No Appointment Found.","apt");?></div>
<?php 
}
	include(dirname(__FILE__).'/footer.php');
?>
