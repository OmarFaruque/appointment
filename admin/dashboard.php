<?php 
	include(dirname(__FILE__).'/header.php');
	$plugin_url_for_ajax = plugins_url('', dirname(__FILE__));
	
	 /* Intialization of Class Object */
	$category = new appointment_category();
	$location = new appointment_location();
	$location->business_owner_id = get_current_user_id();
	$service = new appointment_service();
	$general = new appointment_general();
	$payments= new appointment_payments();
	$staff = new appointment_staff();
	$order_info = new appointment_order();
	$apt_bookings = new appointment_booking();
	$provider = new appointment_staff();
	$clients = new appointment_clients();
	$apt_bookings->location_id = $_SESSION['apt_location'];
	$todayAnalyatics = $apt_bookings->get_today_booking_and_earning();
	$weekstartdate = date_i18n('Y-m-d',strtotime('monday this week'));
	$weekenddatedate = date_i18n('Y-m-d',strtotime('sunday this week'));
	$weekAnalyatics = $apt_bookings->get_week_booking_and_earning($weekstartdate,$weekenddatedate);
	$yearAnalyatics = $apt_bookings->get_year_booking_and_earning();	
	$upcommingbookings = $apt_bookings->today_upcomming_appointments();
	$latestactivitybookings = $apt_bookings->get_booking_by_latest_activity();
	$pastquickactionbookings = $apt_bookings->get_past_pending_quickaction_bookings();	
	$apt_currency_symbol = get_option('appointment_currency_symbol' . '_' . get_current_user_id());

?>

<div class="panel apt-panel-default" id="apt-dashboard">
	<div class="panel-body">
		<ul class="nav nav-tab apt-top-menus-stats">
			<li class="active col-lg-4 col-md-4 col-sm-4 col-xs-12 mb-10">
				<a href="#apt-today-stats" data-toggle="pill">
					<div class="apt-title-amount-stats">
						<div class="apt-icon-booking-stats pull-left">
							<div class="apt-dash-icon today">
							<i class="fa fa-calendar-check-o" aria-hidden="true"></i>
							</div>
							<div class="apt-dash-details of-h">
								<span class="apt-stats-title"><?php echo __('Bookings',"appointment"); ?></span>
								<span class="apt-stats-counting"><?php echo $todayAnalyatics[0]->bookings;?></span>
							</div>
						</div>
						<h4 class="apt-dash-header pull-right"><?php echo __('Today',"appointment"); ?></h4>
					</div>
					<div class="apt-stats-total">
						<span class="apt-currency-stats"><?php echo $apt_currency_symbol;?></span><?php echo number_format($todayAnalyatics[0]->earning,get_option('appointment_price_format_decimal_places' . '_' . get_current_user_id()),".",',');?>
					</div>
				</a>
			</li>
			<li class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mb-10">
				<a href="#apt-this-week-stats" data-toggle="pill">
					<div class="apt-title-amount-stats">
						<div class="apt-icon-booking-stats pull-left">
							<div class="apt-dash-icon this-week">
							<i class="fa fa-calendar-plus-o" aria-hidden="true"></i>
							</div>
							<div class="apt-dash-details of-h">
								<span class="apt-stats-title omar2"><?php echo __('Bookings',"apt"); ?></span>
								<span class="apt-stats-counting"><?php echo $weekAnalyatics[0]->bookings;?></span>
							</div>
						</div>
						<h4 class="apt-dash-header pull-right"><?php echo __('This Week',"apt"); ?></h4>
					</div>
					<div class="apt-stats-total pull-right">
						<span class="apt-currency-stats"><?php echo $apt_currency_symbol;?></span><?php echo number_format($weekAnalyatics[0]->earning,get_option('appointment_price_format_decimal_places' . '_' . get_current_user_id()),".",',');?>
					</div>
						
				</a>
			</li>
			<li class="col-lg-4 col-md-4 col-sm-4 col-xs-12 mb-10">
				<a href="#apt-this-year-stats" data-toggle="pill">
					<div class="apt-title-amount-stats">
						<div class="apt-icon-booking-stats pull-left">
							<div class="apt-dash-icon this-year">
							<i class="icon-calendar icons"></i>
								<!--<i class="icon-diamond"></i>-->
							</div>
							<div class="apt-dash-details of-h">
								<span class="apt-stats-title omar3"><?php echo __('Bookings',"appointment"); ?></span>
								<span class="apt-stats-counting"><?php echo $yearAnalyatics[0]->bookings;?></span>
							</div>
						</div>
						<h4 class="apt-dash-header pull-right"><?php echo __('This Year',"appointment"); ?></h4>
						
					</div>
					<div class="apt-stats-total pull-right">
						<span class="apt-currency-stats"><?php echo $apt_currency_symbol;?></span><?php echo number_format($yearAnalyatics[0]->earning,get_option('appointment_price_format_decimal_places' . '_' . get_current_user_id()),".",',');?>
					</div>
				</a>
			</li>
		</ul>
		<div class="panel-body">	
			<div class="col-md-6 col-lg-6 rnp">
				<div class="tab-content  b-shadow h-450 of-h">
					<div class="tab-pane active" id="apt-today-stats">	
						<div class="apt-left-menu-stats">
							<ul class="nav nav-tabs">
								<li class="active apt-col4 apt_view_chart_analytics apt_service_chart" data-method="service"><a href="#service-view-tab" data-toggle="pill" ><?php echo __('Services View',"apt"); ?></a></li>
								<li class="apt-col4 apt_view_chart_analytics" data-method="provider"><a href="#provider-view-tab" data-toggle="pill"><?php echo __('Provider View',"apt"); ?></a></li>
								<li class="apt-col4 apt_view_chart_analytics" data-method="coupon"><a href="#coupon-view-tab" data-toggle="pill"><?php echo __('Coupons View',"apt"); ?></a></li>
							</ul>
						</div>
						<div id="service-view-tab" class=" tab-pane fade in active ta-c col-md-12 col-sm-12 col-lg-12 col-xs-12 mt-20 mb-20 chart_view_content">
							<canvas id="chart-area-service" style="display:none"  class="apt-today-canvas"></canvas>
							<div class="apt_nodata_service apt-no-booking-chart">
								<i class="fa fa-cogs fa-3x"></i>
								<h3><?php echo __('No service data available',"apt"); ?> </h3>		
							</div>
							
							
						</div>
						<div id="provider-view-tab" class="tab-pane fade ta-c col-md-12 col-sm-12 col-lg-12 col-xs-12 mt-20 mb-20 chart_view_content">
							<canvas id="chart-area-provider" class="apt-provider-canvas"></canvas>
							<div class="apt_nodata_provider apt-no-booking-chart">
								<i class="fa fa-user fa-3x"></i>
								<h3><?php echo __('No provider data available',"apt"); ?> </h3>		
							</div>
							
						</div>
						<div id="coupon-view-tab" class="tab-pane fade ta-c col-md-12 col-sm-12 col-lg-12 col-xs-12 mt-20 mb-20 chart_view_content">
							<canvas id="chart-area-coupon" class="apt-coupon-canvas" ></canvas>
							<div class="apt_nodata_coupon apt-no-booking-chart">
								<i class="fa fa-tags fa-3x"></i>
								<h3><?php echo __('No Coupon Data Available',"apt"); ?> </h3>		
							</div>
							
						</div>
					</div>
				</div>
			</div><!-- end chart -->
	<!-- new Todays comming appointments -->
	<div class="apt-today-bookings col-md-6 col-lg-6 rnp">
		<div class="panel panel-default h-450 br-2 b-shadow">
			<div class="panel-heading bg-success"><?php echo __("Today's next Appointments","apt"); ?></div>
			<hr id="hr" />
			<div class="panel-body">
				<?php if(sizeof($upcommingbookings)==0){ ?>
				<div class="apt-no-today-booking-message">
					<i class="fa fa-clock-o fa-3x"></i>
					<h3><?php echo __('No Appointments Today',"apt"); ?></h3>		
				</div>
				<?php }else{ ?>
				<div class="apt-today-bookings-main pl-10 pr-10">
					<div class="apt-today-booking-list">
						<?php foreach($upcommingbookings as $upcommingbooking){
								
								$service->id= $upcommingbooking->service_id;
								$service->readone();
								$service_title=stripslashes_deep($service->service_title);
								$servicedurationstrinng = '';
								if(floor($service->duration/60)!=0){ $servicedurationstrinng .= floor($service->duration/60); $servicedurationstrinng .= __(" Hrs","apt"); } 
								if($service->duration%60 !=0){  $servicedurationstrinng .= $service->duration%60; $servicedurationstrinng .= __(" Mins","apt"); }
								$staff->id=$upcommingbooking->provider_id;
								$staff_info = $staff->readOne();   
								$provider_name = ucfirst($staff_info[0]['staff_name']);				
								$clients->order_id=$upcommingbooking->order_id;
								$client_info = $clients->get_client_info_by_order_id();
								$clientname= $client_info[0]->client_name;
									
								
						?>
						<div class="apt-today-list col-md-12" data-bookingid = '<?php echo $upcommingbooking->id;?>' data-toggle="modal" data-target="#booking-details">
							<div class="apt-today-left">
								<strong><?php echo date_i18n(get_option('time_format'),strtotime($upcommingbooking->booking_datetime)); ?></strong>
									
							</div>
							<div class="apt-today-center col-md-6 col-sm-6 col-xs-6 np mr-5">
								<span class="apt-service-text"><?php echo __(stripslashes_deep($service_title),"apt");?></span>
								<div class="mt-5 mb-5">
									<span class="mr-5"><?php echo __('With','apt');?> <?php echo __(stripslashes_deep($provider_name),"apt");?></span>
									
								</div>
							</div>
							<div class="apt-today-right col-md-3 col-xs-3 np">
								<span class="mt-5 mb-5 col-md-12  col-sm-12 col-xs-6 np"><i class="icon-clock"></i><?php echo $servicedurationstrinng; ?></span>
								<div class="mt-5 mb-5 col-md-12 col-sm-12 col-xs-6 np">
									<span class="mr-5"><i class="icon-user"></i><?php echo $clientname;?></span>
								</div>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<!-- booking activity -->
	<div class="apt-bookings-activity col-md-6 col-lg-6 rnp">
		<div class="panel panel-default h-450 br-2 b-shadow">
			<div class="panel-heading  bg-info"><?php echo __('Latest Bookings Activity',"apt"); ?></div>
			<hr id="hr" />
			<div class="panel-body">
				<?php if(sizeof($latestactivitybookings)==0){ ?>
				<div class="apt-no-today-booking-message">
					<i class="fa fa-clock-o fa-3x"></i>
					<h3><?php echo __('No latest booking',"apt"); ?> </h3>		
				</div>
				<?php }else{ ?>
				<div class="apt-latest-activity pl-10 pr-10 omar1">
					<div class="apt-bookings-activity-list">
					
						<?php foreach($latestactivitybookings as $latestactivity){
								
								$service->id= $latestactivity->service_id;
								$service->readone();
								$service_title=stripslashes_deep($service->service_title);
								$servicedurationstrinng = '';
								if(floor($service->duration/60)!=0){ $servicedurationstrinng .= floor($service->duration/60); $servicedurationstrinng .= __(" Hrs","apt"); } 
								if($service->duration%60 !=0){  $servicedurationstrinng .= $service->duration%60; $servicedurationstrinng .= __(" Mins","apt"); }
								$staff->id=$latestactivity->provider_id;
								$staff_info = $staff->readOne();  
								
								
								$provider_name = (isset($staff_info[0]['staff_name']))?ucfirst($staff_info[0]['staff_name']):'';				
								$clients->order_id=$latestactivity->order_id;
								$client_info = $clients->get_client_info_by_order_id();
								$clientname= $client_info[0]->client_name;
									
								
						?>
						<div class="apt-today-list apt-activity-list col-md-12" data-bookingid = '<?php echo $latestactivity->id;?>' data-toggle="modal" data-target="#booking-details">
							
							<div class="col-md-8 col-sm-8 col-xs-12 np ofh"><span class="apt-service-text"><?php echo __(stripslashes_deep($service_title),"apt");?> <?php echo __('With','apt');?> <b> <?php echo __(stripslashes_deep($provider_name),"apt");?></b></span></div>
							
							<div class="col-md-4 col-sm-4 col-xs-12 ta-r np">
								<?php if($latestactivity->booking_status=='A' || $latestactivity->booking_status==''){
								echo '<span class="apt-label btn-info br-2">'.__('Active','apt').'</span>';
								}elseif($latestactivity->booking_status=='C'){
									echo '<span class="apt-label btn-success br-2">'.__('Confirm','apt').'</span>';
								}elseif($latestactivity->booking_status=='R'){
									echo '<span class="apt-label btn-danger br-2">'.__('Reject','apt').'</span>';
								}elseif($latestactivity->booking_status=='RS'){
									echo '<span class="apt-label btn-primary br-2">'.__('Rescheduled','apt').'</span>';
								}elseif($latestactivity->booking_status=='CC'){
									echo '<span class="apt-label btn-default br-2">'.__('Cancel By Client','apt').'</span>';
								}elseif($latestactivity->booking_status=='CS'){
									echo '<span class="apt-label btn-default br-2">'.__('Cancel By Service Provider','apt').'</span>';
								}elseif($latestactivity->booking_status=='CO'){
									echo '<span class="apt-label btn-success br-2">'.__('Completed','apt').'</span>';
								}else{
									echo '<span class="apt-label btn-default br-2">'.__('Mark As No Show','apt').'</span>';
								} ?>
							
							
							</div>
							<div class="mt-5 mb-5 col-md-12 col-sm-12 np">
							
								<span class="col-md-3 col-sm-3 col-lg-3 col-xs-12 np ofh"><?php echo __('On','apt');?> <?php echo date_i18n('l',strtotime($latestactivity->booking_datetime)); ?></span>
								<span class="col-md-6 col-sm-5 col-lg-5 col-xs-12 np ofh"><i class="icon-calendar icon-space"></i><?php echo date_i18n('d,M Y',strtotime($latestactivity->booking_datetime)); ?> <?php echo __('at','apt');?>  <?php echo date_i18n(get_option('time_format'),strtotime($latestactivity->booking_datetime)); ?>
								<span class="col-sm-12 col-xs-12 np ofh"><i class="icon-clock icon-space "></i><?php echo $servicedurationstrinng;?></span>
								</span>
								<span class="col-md-3 col-sm-4 col-lg-4 col-xs-12 np ofh"><i class="icon-user icon-space"></i><?php echo $clientname;?></span>
								
							</div>
							
						</div>
						<?php } ?>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<!-- booking activity -->
	<div class="apt-bookings-activity col-md-6 col-lg-6 rnp">
		<div class="panel panel-default h-450 br-2 b-shadow">
			<div class="panel-heading  bg-primary"><?php echo __('Past Bookings Quick Actions',"apt"); ?></div>
			<hr id="hr" />
			<div class="panel-body">
				<?php if(sizeof($pastquickactionbookings)==0){ ?>
				<div class="apt-no-today-booking-message">
					<i class="fa fa-clock-o fa-3x"></i>
					<h3><?php echo __('No Past Booking For Quick Action',"apt"); ?> </h3>		
				</div>
				<?php }else{ ?>
				<div class="apt-latest-activity pl-10 pr-10 omar2">
					<div class="apt-bookings-activity-list">
					
						<?php foreach($pastquickactionbookings as $pastquickactionbooking){
								
								$service->id= $pastquickactionbooking->service_id;
								$service->readone();
								$service_title=stripslashes_deep($service->service_title);
								$servicedurationstrinng = '';
								if(floor($service->duration/60)!=0){ $servicedurationstrinng .= floor($service->duration/60); $servicedurationstrinng .= __(" Hrs","apt"); } 
								if($service->duration%60 !=0){  $servicedurationstrinng .= $service->duration%60; $servicedurationstrinng .= __(" Mins","apt"); }
								$staff->id=$pastquickactionbooking->provider_id;
								$staff_info = $staff->readOne();   
								$provider_name = (isset($staff_info[0]['staff_name']))?ucfirst($staff_info[0]['staff_name']):'';				
								$clients->order_id=$pastquickactionbooking->order_id;
								$client_info = $clients->get_client_info_by_order_id();
								$clientname= $client_info[0]->client_name;
									
								
						?>
						
						<div class="apt-today-list-actions">
							<div class="apt-past-booking-button col-sm-12 col-xs-12 np">
								<div class="btn-group btn-group-xs pull-right">
								   <button data-booking_id="<?php echo $pastquickactionbooking->id;?>" data-method="CO" type="button" class="mr-5 btn btn-success apt_crc_appointment"><?php echo __('Completed','apt');?></button>
								   <button data-booking_id="<?php echo $pastquickactionbooking->id;?>" data-method="MN" type="button" class="mr-5 btn btn-warning apt_crc_appointment"><?php echo __('No Show','apt');?> </button>
								   <button type="button" data-id="apt-delete-past-booking<?php echo $pastquickactionbooking->id;?>" class="mr-5 btn btn-danger apt_delete_past_booking" rel="popover" data-placement='bottom' title="Delete Booking?"><?php echo __('Delete','apt');?></button>
								   
								   <div id="popover-apt-delete-past-booking<?php echo $pastquickactionbooking->id;?>" style="display: none;">
										<div class="arrow"></div>
										<table class="form-horizontal" cellspacing="0">
											<tbody>												
												<tr>
													<td>
														<button id="apt_booking_delete" data-booking_id="<?php echo $pastquickactionbooking->id;?>" value="Delete" class="btn btn-danger btn-sm" type="submit"><?php echo __('Yes','apt');?></button>
														<button class="btn btn-default btn-sm apt_close_delete_booking_popover" href="javascript:void(0)"><?php echo __('Cancel','apt');?></button>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								   
								   
							   </div>
							</div>
						
						
							<div class="apt-today-list apt-activity-list col-md-12" data-bookingid = '<?php echo $pastquickactionbooking->id;?>' data-toggle="modal" data-target="#booking-details">
								
								<div class="col-md-6 col-sm-12 col-xs-12 np">
								<?php if($pastquickactionbooking->booking_status=='A' || $pastquickactionbooking->booking_status==''){
								echo '<span class="apt-label btn-info br-2">'.__('Active','apt').'</span>';
								}elseif($pastquickactionbooking->booking_status=='C'){
									echo '<span class="apt-label btn-success br-2">'.__('Confirm','apt').'</span>';
								}elseif($pastquickactionbooking->booking_status=='R'){
									echo '<span class="apt-label btn-danger br-2">'.__('Reject','apt').'</span>';
								}elseif($pastquickactionbooking->booking_status=='RS'){
									echo '<span class="apt-label btn-primary br-2">'.__('Rescheduled','apt').'</span>';
								}elseif($pastquickactionbooking->booking_status=='CC'){
									echo '<span class="apt-label btn-default br-2">'.__('Cancel By Client','apt').'</span>';
								}elseif($pastquickactionbooking->booking_status=='CS'){
									echo '<span class="apt-label btn-default br-2">'.__('Cancel By Service Provider','apt').'</span>';
								}elseif($pastquickactionbooking->booking_status=='CO'){
									echo '<span class="apt-label btn-success br-2">'.__('Completed','apt').'</span>';
								}else{
									echo '<span class="apt-label btn-danger br-2">'.__('Mark As No Show','apt').'</span>';
								} ?>
								
								
								</div>
								
								<div class="col-md-12 col-sm-12 col-xs-12 np mt-5 mb-5 ofh"><span class="apt-service-text"><?php echo __(stripslashes_deep($service_title),"apt");?> <?php echo __('With','apt');?> <b> <?php echo __(stripslashes_deep($provider_name),"apt");?></b></span></div>
									
								<div class="mt-5 mb-5 col-md-12 col-sm-12 np">
									<span class="col-md-3 col-sm-3 col-lg-3 col-xs-12 np ofh"><?php echo __('On','apt');?> <?php echo date_i18n('l',strtotime($pastquickactionbooking->booking_datetime)); ?></span>
									<span class="col-md-6 col-sm-5 col-lg-5 col-xs-12 np ofh"><i class="icon-calendar icon-space"></i><?php echo date_i18n('d,M Y',strtotime($pastquickactionbooking->booking_datetime)); ?> <?php echo __('at','apt');?>  <?php echo date_i18n(get_option('time_format'),strtotime($pastquickactionbooking->booking_datetime)); ?>
									<span class="col-sm-12 col-xs-12 np ofh"><i class="icon-clock icon-space"></i><?php echo $servicedurationstrinng;?></span>
									</span>
									<span class="col-md-3 col-sm-4 col-lg-4 col-xs-12 np ofh"><i class="icon-user icon-space"></i><?php echo $clientname;?></span>
								</div>
							</div>
						</div>	
						<?php } ?>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	
	
</div>
</div>

<?php 
	include('footer.php');	
?>