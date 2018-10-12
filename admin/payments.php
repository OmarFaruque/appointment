<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
global $wpdb;
$page_title = "Payments";
include_once "header.php";
$plugin_url_for_ajax = plugins_url('',  dirname(__FILE__));
$general = new appointment_general();
$payments= new appointment_payments();
$order_info = new appointment_order();
$general->business_owner_id = get_current_user_id();

if(isset($_SESSION['apt_all_loc_payments']) && $_SESSION['apt_all_loc_payments']=='Y'){
$payments->location_id = 'All';
}else{
$payments->location_id = $_SESSION['apt_location'];
}
$payments->business_owner_id = get_current_user_id();
$all_payments=$payments->readAll();
?>
<div id="apt-payments" class="panel tab-content">
	<div class="panel panel-default">
		<div class="panel-body">
			<div id="" class="tab-pane fade in active">
				<form id="" name="" class="" method="post">
					
					<div class="col-md-4 col-sm-6 col-xs-12 col-lg-4 ">
						<label class="f-letter-capitalize custom-width custom-width-2"><?php echo __("Select payment option export details","apt");?></label>
							<div id="apt_reportrange" class="form-control custom-width custom-width-2 " >
								<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
								<span></span> <i class="fa fa-caret-down"></i>
							</div>
	
					</div>
					<div class="col-md-2 col-sm-2 col-xs-12 col-lg-2">
						<br />
						
						<button type="button" class="btn btn-info apt_payments_byrange" name="apt_payments_byrange"><?php echo __("Submit","apt");?></button>
					</div>
					<?php if(current_user_can('manage_options')){?>
					<div class="col-md-4 col-sm-4 col-xs-12 col-lg-4 pull-right">
					<br />
						<div class="apt-custom-checkbox pull-right">
							<ul class="apt-checkbox-list">
								<li>
									<input <?php if(isset($_SESSION['apt_all_loc_payments']) && $_SESSION['apt_all_loc_payments']=='Y'){ echo "checked='checked'"; } ?> type="checkbox" id="apt_all_locations_payments" />
									<label for="apt_all_locations_payments"><?php echo __("All Locations Payments","apt");?><span></span></label>
								</li>
							</ul>
						</div>
					</div>
					<?php } ?>
						
					<div class="mb-5" id="hr"></div>
					<div class="col-md-12 col-lg-12 col-sm-12">
					<div class="table-responsive"> 
						<table id="payments-details" class="display table table-striped table-bordered" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th><?php echo __("Client","apt");?></th>
									<th><?php echo __("Payment method","apt");?></th>
									<th><?php echo __("Total amount","apt");?></th>
									<th><?php echo __("Discount","apt");?></th>
									<th><?php echo __("Tax","apt");?></th>
									<th><?php echo __("Partial Amount","apt");?></th>
									<th><?php echo __("Net Total","apt");?></th>
								</tr>
							</thead>
							<tbody id="apt_payment_details">
						
							
								<?php foreach($all_payments as $payment){ 
									$order_info->order_id = $payment->order_id;
									$order_info->readOne_by_order_id();
								
								 ?>
								<tr>
									<td><?php echo $order_info->client_name;?></td>	
									<?php if($payment->payment_method == 'paypal') { ?>
										<td><?php echo __("Paypal","apt");?></td>
									<?php }
									else if($payment->payment_method == 'pay_locally') { ?>
										<td><?php echo __("Pay Locally","apt");?></td>
									<?php }
									else if($payment->payment_method == 'Free') { ?>
										<td><?php echo __("Free","apt");?></td>
									<?php }
									else if($payment->payment_method == 'stripe') { ?>
										<td><?php echo __("Stripe","apt");?></td>
									<?php }
									else if($payment->payment_method == 'authorizenet') { ?>
										<td><?php echo __("Authorize.Net","apt");?></td>
									<?php }
									else if($payment->payment_method == '2checkout') { ?>
										<td><?php echo __("2checkout","apt");?></td>
									<?php }
									else{ 
										echo '<td>&nbsp;</td>';
									}	 ?>		
									<td><?php echo $general->apt_price_format($payment->amount);?></td>
									<td><?php echo $general->apt_price_format($payment->discount);?></td>
									<td><?php echo $general->apt_price_format($payment->taxes);?></td>
									<td><?php echo $general->apt_price_format($payment->partial);?></td>
									<td><?php echo $general->apt_price_format($payment->net_total);?></td>
								</tr>	
								<?php }?>		
							</tbody>
						</table>	
					</div>	
					</div>	
				</form>	
			</div>
		</div>
	</div>		
</div>		
<?php 
	include_once "footer.php";
?>