<?php 
if ( ! defined( 'ABSPATH' ) ) exit; ?>
<?php


$plugin_url = plugins_url('',  dirname(__FILE__));


echo "<style>
	#apt.apt-client .client_logout .clogout{
		color: ".get_option('appointment_bg_text_color' . '_' . $atts['bwid'] )." !important;
	}
	#apt .apt-button{
		color : ".get_option('appointment_bg_text_color' . '_' . $atts['bwid'])." !important;
		background-color: ".get_option('appointment_primary_color' . '_' . $atts['bwid'])." !important;
	}
	
	#apt .apt-button:hover,
	#apt.apt-client .client_top_bar{
		color: ".get_option('appointment_bg_text_color' . '_' . $atts['bwid'])." !important;
		background: ".get_option('appointment_secondary_color'.'_'.$atts['bwid'])." !important;
	}	
	#apt.apt-client #aptclient_list .list_wrapper .list_header{
		color: ".get_option('appointment_bg_text_color'.'_'.$atts['bwid'])." !important;
		background: ".get_option('appointment_primary_color'.'_'.$atts['bwid'])." !important;
	}
	#apt .apt-loader .apt-first{
		border: 3px solid ".get_option('appointment_bg_text_color'.'_'.$atts['bwid'])." !important;
	}
	#apt .apt-loader .apt-second{
		border: 3px solid ".get_option('appointment_primary_color'.'_'.$atts['bwid'])." !important;
	}
	#apt .apt-loader .apt-third{
		border: 3px solid ".get_option('appointment_secondary_color'.'_'.$atts['bwid'])." !important;
	}
	


</style>";


if(is_user_logged_in()){
global $current_user;
 $current_user = wp_get_current_user();

$booking = new appointment_booking();


$booking->client_id = $current_user->ID;
$current_user_bookings = $booking->get_distinct_bookings_of_client();

if(sizeof($current_user_bookings) > 0) {
?>
<input type="hidden" name="bwid" value="<?php echo $atts['bwid']; ?>" />			
<div id="apt" class="apt-wrapper apt-client">
<div class="loader">
	<!-- <div class="loader"><?php //echo __("Loading...","apt");?></div> -->
	<div class="apt-loader">
		<span class="apt-first"></span>
		<span class="apt-second"></span>
		<span class="apt-third"></span>
	</div>
</div>
	<div class="client_top_bar">
		<div class="apt_clients_header">
			<span class="client_name apt-sm-9 np"><?php echo $current_user->display_name; ?></span>
			<span class="client_logout apt-sm-3  text-right np"><a href="<?php echo wp_logout_url(home_url()); ?>" class="clogout"><i class="icon-power icons icon-space"></i><?php echo __('Logout','apt');?></a></span>
		
		</div>
	</div>
	<div class="apt_clients_inner">
		<h3 class="apt-xs-12"><?php echo __('Appointment Details','apt');?></h3>	
		<form type="" method="POST" action="" id="aptclient_list" >
			<div class="table-responsive">
				<table id="client_dashboard_table" class="table table-striped table-bordered" cellspacing="0" style="width:99%">
					<thead>
						<tr>
							<th><?php echo __("Order #","apt");?></th>
							<th><?php echo __("Provider Name","apt");?></th>
							<th><?php echo __("Service","apt");?></th>
							<th><?php echo __("Date","apt");?></th>
							<th><?php echo __("Time","apt");?></th>
							<th><?php echo __("Status","apt");?></th>
							<th class="thd-w180"><?php echo __("Action","apt");?></th>
						</tr>
					</thead><tbody> <?php 
				foreach($current_user_bookings as $curr_user_booking){
				
					$apt_bookings = new appointment_booking();
                    $provider = new appointment_staff();
                    $service = new appointment_service();
                    $apt_bookings->client_id = $current_user->ID;
					$apt_bookings->order_id = $curr_user_booking->order_id;
                    $order_bookings=$apt_bookings->get_client_bookings_by_order_id();										                  
                    foreach($order_bookings as $client_bookings){      
					
					$booking_dt = date_i18n('Y-m-d H:i:s',strtotime($client_bookings->booking_datetime));
					$curr_dt = date_i18n('Y-m-d H:i:s');
					$date1=strtotime($booking_dt);
					$date2=strtotime($curr_dt);
					$cancelationtime=get_option('appointment_cancellation_buffer_time' . '_' . $atts['bwid']);
					$diff  = abs($date1 - $date2);
					$remaining_mins   = round($diff / 60);
					
					
					
					if($cancelationtime > $remaining_mins){
					$cancelation_buffer="disabled='disabled'";
					$cancelation_buffer_msg="<a data-toggle='tooltip' class='tooltipLink' 
					data-original-title='You are now unable to cancel appointment'><span class='glyphicon glyphicon-exclamation-sign' style='color:red'></span></a>";
					}else{
						$cancelation_buffer="";
						$cancelation_buffer_msg="";
					}


					
                    $provider->id=$client_bookings->provider_id;
                    $staffinfo = $provider->readOne();            
                    $service->id=$client_bookings->service_id;
                    $service->readOne();
                    if($client_bookings->booking_status=='A' || $client_bookings->booking_status==''){
                        $status= "<span style='color:#46B64A;font-weight:bold;'>Active</span>";
                    }elseif($client_bookings->booking_status=='C'){
                        $status= "<span style='color:#46B64A;font-weight:bold;'>Confirmed</span>";
                    }
                    elseif($client_bookings->booking_status=='R'){
                        $status= "<span style='color:#EE403F;font-weight:bold;'>Rejected</span>";
                    }elseif($client_bookings->booking_status=='CC' || $client_bookings->booking_status=='CS'){
                        $status= "<span style='color:#EE403F;font-weight:bold;'>Cancelled</span>";
                    }
                    if($client_bookings->booking_status=='CC' || $client_bookings->booking_status=='CS' || $client_bookings->booking_status=='R'){ $btnview="disabled=disabled";}else{$btnview="";}
                    ?>
				
					<tr>
						<td class="apt_cl_order_data"><?php echo __($client_bookings->order_id,"apt");?></td>
						<td class="apt_cl_provider_data"><?php echo (isset($staffinfo[0]['staff_name']))? __(stripslashes_deep($staffinfo[0]['staff_name']),"apt"):'';?></td>
						<td class="apt_cl_service_data"><?php echo __(stripslashes_deep($service->service_title),"apt");?></td>
						<td class="apt_cl_provider_data"><?php echo __(date_i18n(get_option('date_format'),strtotime($client_bookings->booking_datetime)),"apt");?></td>
						<td class="apt_cl_provider_data"><?php echo __(date_i18n(get_option('time_format'),strtotime($client_bookings->booking_datetime)),"apt");?></td>
						<td class="apt_cl_status_data" id='st<?php echo $client_bookings->id; ?>'><?php echo __($status,"apt");?></td>	
						<td>
						<?php if($cancelation_buffer=='' && $btnview=='' ){ ?>
						
						<a id="apt-cancel-book<?php echo $client_bookings->id; ?>" class="apt-cancel-book-popover apt-button btn-x-small apt_cl_button apt_client_cancel" data-poid="apt-popover-cancel-book<?php echo $client_bookings->id; ?>" rel="popover" data-placement='bottom' title="<?php echo __("Cancel reason?","apt");?>"><?php echo __("Cancel","apt");?></a>
						
						<div id="apt-popover-cancel-book<?php echo $client_bookings->id; ?>" style="display: none;">
							<div class="arrow"></div>
							<table class="form-horizontal" cellspacing="0">
								<tbody>
									<tr>
										<td><textarea class="form-control cancel_reason_input_txt nm" id="cancel_reason_txt<?php echo $client_bookings->id;?>" name="" placeholder="<?php echo __("Appointment Cancel Reason","apt");?>"></textarea></td>
									</tr>
									<tr>
										<td>
											<button data-curr_client_bookingid="<?php echo $client_bookings->id;?>" id="apt_booking_cancel" data-method='CS'  value="Cancel By Service Provider" class="btn btn-success btn-sm apt_client_save_cancel_reason" type="submit"><?php echo __("Save","apt");?></button>
											
											<a id="apt-close-cancel-appointment-cd-popup" class="btn btn-default btn-sm" href="javascript:void(0)"><?php echo __("Cancel","apt");?></a>
										</td>
									</tr>
								</tbody>
							</table>
						</div><!-- end pop up -->
						
						<?php } ?>
						
						<a href="<?php echo $plugin_url;?>/assets/lib/admin_general_ajax.php?general_ajax_action=client_download_invoice&order_id=<?php echo $curr_user_booking->order_id;?>&client_id=<?php echo $current_user->ID; ?>&key=<?php echo 'O'.base64_encode($curr_user_booking->order_id+1247);?>b" class='apt-button btn-x-small apt_cl_button'><i class="icon-cloud-download icons icon-space"></i><?php echo __("Invoice","apt");?></a>
						</td>
					</tr>
				
				   <?php
                    }
				} ?>
				</tbody>
				</table>
			</div>
			
							
			</div>
		</form>
	</div>
</div>
<?php }else{

	echo __('No Appointment Found','apt');
} }else{
	?>
	<div id="apt" class="apt-wrapper apt-client apt-client-login">
	<div class="loader">
		<!-- <div class="loader"><?php //echo __("Loading...","apt");?></div> -->
		<div class="apt-loader">
			<span class="apt-first"></span>
			<span class="apt-second"></span>
			<span class="apt-third"></span>
		</div>
	</div>
		<div class="apt_clients_inner">
			<div class="apt_client_login_main ">
				<div class="apt_login_inner">
					<h4 class="apt_login_p"><?php echo __('You Must Login to check your appointments','apt'); ?></h4>
					<div class="apt_form_row">
						<label for="apt_client_username_l"><?php echo __('UserName','apt');?></label>
						<input type="text" class="form-control" id="apt_client_username_l" name="apt_client_username" value="" />
						<label id="client_login_username-error"  class="error" ></label>
					</div>
					<div class="apt_form_row">
						<label for="apt_client_password_l"><?php echo __('Password','apt');?></label>
						<input type="password" class="form-control" id="apt_client_password_l" name="apt_client_password" value=""/>
						<label id="client_login_password-error" class="error" ></label>
					</div>
					<div class="apt_form_row">
						<label id="client_login-error" class="error" ></label>
					</div>
					<div class="apt_form_row">
						<button class="apt_client_login apt-button nm"><?php echo __('Login','apt');?> </button> <a target="_blank" class="apt_forgot_pass apt-link" href="<?php echo home_url();?>/wp-login.php?action=lostpassword"><?php echo __('Forgot Password?','apt');?></a>
					</div>
				</div>
			</div>
	</div>
</div>
<?php
} ?>
 <script type="text/javascript">
    var objs_booking_list = {"plugin_path":"<?php echo $plugin_url;?>"};
	var appearance_setting = {"default_country_code":"<?php echo get_option('appointment_default_country_short_code' . '_' . get_current_user_id()); ?>"};
</script>
<div id="category_manage_modal"> 
</div>