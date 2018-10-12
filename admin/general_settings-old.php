<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// set page headers
$page_title = "Settings";
include_once "header.php";

// instantiate schedule object
$oct_settings = new octabook_settings();
$oct_email_templates = new octabook_email_template();
$oct_sms_templates = new octabook_sms_template();
$oct_coupons = new octabook_coupons();
$oct_coupons->location_id = $_SESSION['oct_location'];
$oct_settings->readAll();

$plugin_relative_path = plugin_dir_path(dirname(dirname(dirname(dirname(__FILE__)))));
$plugin_url_for_ajax = plugins_url('',dirname(__FILE__));
require_once dirname(dirname(__FILE__)).'/assets/GoogleCalendar/google-api-php-client/src/Google_Client.php';
$error = '';	
$img_error ='';

$upload_dir_path= wp_upload_dir();
$email_template_tags = array('{{company_name}}','{{service_name}}','{{service_provider_name}}','{{customer_name}}','{{client_address}}','{{client_city}}','{{client_zip}}','{{client_phone}}','{{client_email}}','{{client_gender}}','{{client_dateofbirth}}','{{client_age}}','{{client_skype}}','{{client_state}}','{{client_appointment_cancel_link}}','{{appointment_id}}','{{appointment_date}}','{{appointment_time}}','{{net_amount}}','{{discount_amount}}','{{payment_method}}','{{taxes_amount}}','{{partial_amount}}','{{provider_email}}','{{provider_phone}}', '{{provider_appointment_reject_link}}','{{provider_appointment_confirm_link}}','{{appointment_reject_reason}}','{{appointment_cancel_reason}}','{{appointment_confirm_note}}','{{appointment_reschedle_note}}','{{appointment_previous_date}}','{{appointment_previous_time}}','{{admin_manager_name}}','{{addons_details}}','{{location_title}}','{{location_description}}','{{location_email}}','{{location_phone}}','{{location_address}}','{{location_city}}','{{location_state}}','{{location_zip}}','{{location_country}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');    

$requestemail_template_tags = array('{{customer_name}}','{{service_provider_name}}','{{admin_manager_name}}','{{booking_details}}','{{appoinment_client_detail}}','{{company_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');    




$sms_template_tags = array('{{company_name}}','{{service_name}}','{{service_provider_name}}','{{customer_name}}','{{client_address}}','{{client_city}}','{{client_zip}}','{{client_phone}}','{{client_email}}','{{client_gender}}','{{client_dateofbirth}}','{{client_age}}','{{client_skype}}','{{client_state}}','{{client_appointment_cancel_link}}','{{appointment_id}}','{{appointment_date}}','{{appointment_time}}','{{net_amount}}','{{discount_amount}}','{{payment_method}}','{{taxes_amount}}','{{partial_amount}}','{{provider_email}}','{{provider_phone}}', '{{provider_appointment_reject_link}}','{{provider_appointment_confirm_link}}','{{appointment_reject_reason}}','{{appointment_cancel_reason}}','{{appointment_confirm_note}}','{{appointment_reschedle_note}}','{{appointment_previous_date}}','{{appointment_previous_time}}','{{admin_manager_name}}','{{addons_details}}','{{location_title}}','{{location_description}}','{{location_email}}','{{location_phone}}','{{location_address}}','{{location_city}}','{{location_state}}','{{location_zip}}','{{location_country}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');    


$sms_template_tags = array('{{customer_name}}','{{service_provider_name}}','{{admin_manager_name}}','{{booking_details}}','{{appoinment_client_detail}}','{{company_name}}','{{company_address}}','{{company_city}}','{{company_state}}','{{company_zip}}','{{company_country}}','{{company_phone}}','{{company_email}}','{{company_logo}}');

?>	
<div class="panel oct-panel-default" id="oct-settings">
	<div class="oct-settings oct-left-menu col-md-2 col-sm-3 col-xs-12 col-lg-2">
		<ul class="nav nav-tab nav-stacked">				
			<li class="active"><a href="#company-details" class="top-company-details" data-toggle="pill"><i class="fa fa-building-o fa-2x"></i><br /><?php echo __("Company","oct");?></a></li>
			<li><a href="#general-setting" class="top-general-setting" data-toggle="pill"><i class="fa fa-cog fa-2x"></i><br /><?php echo __("General","oct");?></a></li>
			<li><a href="#appearance-setting" class="top-appearance-setting" data-toggle="pill"><i class="fa fa-tachometer fa-2x"></i><br /><?php echo __("Appearance ","oct");?></a></li>
			<li><a href="#payment-setting" class="top-payment-setting" data-toggle="pill"><i class="fa fa-money fa-2x"></i><br /><?php echo __("Payment ","oct");?></a></li>
			<li><a href="#email-setting" class="top-email-setting" data-toggle="pill"><i class="fa fa-paper-plane fa-2x"></i><br /><?php echo __("Email Notification","oct");?></a></li>
			<li><a href="#email-template" class="top-email-template" data-toggle="pill"><i class="fa fa-envelope fa-2x"></i><br /><?php echo __("Email Templates","oct");?></a></li>
			<li><a href="#sms-reminder" class="top-sms-reminder" data-toggle="pill"><i class="fa fa-mobile fa-2x"></i><br /><?php echo __("SMS Notification","oct");?></a></li>
			<li><a href="#sms-template" class="top-sms-template" data-toggle="pill"><i class="fa fa-envelope fa-2x"></i><br /><?php echo __("SMS Templates","oct");?></a></li>
			<li><a href="#custom-form-fields" class="top-custom-formfield" data-toggle="pill"><i class="fa fa-align-left fa-2x"></i><br /><?php echo __("Custom Form Fields","oct");?></a></li>
			<li><a href="#promocode" class="top-promocode" data-toggle="pill"><i class="fa fa-tag fa-2x"></i><br /><?php echo __("Promocode","oct");?></a></li>
			<li><a href="#google-calendar" class="top-promocode" data-toggle="pill"><i class="fa fa-calendar fa-2x"></i><br /><?php echo __("Google Calendar","oct");?></a></li>
		</ul>
	</div>
	<div class="oct-setting-details tab-content col-md-10 col-sm-9 col-lg-10 col-xs-12 np container-fluid">
		<div class="tab-content pr">
			<div class="company-details tab-pane active oct-toggle-abs" id="company-details">
				<form id="" method="post" type="" class="oct-company-details" >
					<div class="panel panel-default">
						<div class="panel-heading oct-top-right">
							<h1 class="panel-title"><?php echo __("Company Settings","oct");?> </h1>
						</div>
						<div class="panel-body">
							<table class="form-inline oct-common-table">
								<tbody>
									<tr>
										<td><label><?php echo __("Your Business Name","oct");?></label></td>
										<td>
											<div class="form-group">
												<input type="text" class="form-control" size="35" id="octabook_company_name" value="<?php echo $oct_settings->octabook_company_name; ?>" placeholder="<?php echo __("Your Company Name","oct");?>" />
											</div>	
											<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Company name is used for invoicing purpose.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Company Email","oct");?></label></td>
										<td>
											<div class="form-group">
												<input type="text" class="form-control" size="35" id="octabook_company_email" value="<?php echo $oct_settings->octabook_company_email; ?>" placeholder="<?php echo __("Your Company email","oct");?>" />
											</div>	
										</td>
									</tr>
									<!--country code-->
									<tr>
										<td><label><?php echo __("Default Country Code","oct");?></label></td>
										<td>
											<div class="form-group">
												<input style="width: 30.5% !important;" type="text" class="form-control custom-flag-space" size="35" id="octabook_company_country_code" value="<?php echo $oct_settings->octabook_company_country_code; ?>"
												
												placeholder="<?php echo __("","oct");?>" />
											</div>	
										</td>
									</tr>
									
									<tr>
										<td><label><?php echo __("Company Phone","oct");?></label></td>
										<td>
											<div class="input-group">
												<span class="input-group-addon" style="width: 43px;height: 30px;"><span class="company_country_code_value"><?php echo $oct_settings->octabook_company_country_code; ?></span></span>
												<input type="hidden" class="default_company_country_flag" value="" />
												<input style="width: 75%;" type="text" class="form-control" size="35" id="octabook_company_phone" value="<?php echo $oct_settings->octabook_company_phone; ?>" placeholder="<?php echo __("Company Phone","oct");?>" />
											</div>	
										</td>
									</tr>
									
									<!-- country code -->
									
									<tr>
										<td><label><?php echo __("Company Address","oct");?></label></td>
									
										<td><div class="form-group">
											<div class="oct-col12"><textarea id="octabook_company_address" class="form-control" cols="44"><?php echo $oct_settings->octabook_company_address; ?></textarea></div>
											</div>
										</td>
									</tr>
									<tr>
										<td></td>
										<td><div class="form-group">
											<div class="oct-col6 oct-w-50">
												<input type="text" class="form-control" id="octabook_company_city" value="<?php echo $oct_settings->octabook_company_city; ?>" placeholder="<?php echo __("City","oct");?>" />
											</div>
											<div class="oct-col6 oct-w-50 float-right">
												<input type="text" class="form-control" id="octabook_company_state" value="<?php echo $oct_settings->octabook_company_state; ?>" placeholder="<?php echo __("State","oct");?>" />
											</div>
											</div>
										</td>
									</tr>
									<tr>
										<td></td>	
										<td><div class="form-group">	
											<div class="oct-col6 oct-w-50">
												<input type="text" class="form-control" id="octabook_company_zip" value="<?php echo $oct_settings->octabook_company_zip; ?>" placeholder="<?php echo __("Zip","oct");?>" />
											</div>	
											<div class="oct-col6 oct-w-50 float-right">
												<input type="text" class="form-control" id="octabook_company_country" value="<?php echo $oct_settings->octabook_company_country; ?>" placeholder="<?php echo __("Country","oct");?>" />
											</div>	
											</div>	
											
										</td>
									</tr>
									
									<tr>
										<td><label><?php echo __("Company Logo","oct");?></label></td>
										<td>
											<div class="form-group">
												<div class="oct-company-image-uploader">
													<img id="bdcslocimage" src="<?php if($oct_settings->octabook_company_logo==''){ echo $plugin_url_for_ajax.'/assets/images/company.png';}else{echo site_url()."/wp-content/uploads".$oct_settings->octabook_company_logo;	}?>" class="oct-company-image br-4" height="100" width="100">
													<label <?php if($oct_settings->octabook_company_logo==''){ echo "style='display:block'"; }else{ echo "style='display:none;'"; }?> for="oct-upload-imagebdcs" class="oct-company-img-icon-label">
														<i class="oct-camera-icon-common br-100 fa fa-camera"></i>
														<i class="pull-left fa fa-plus-circle fa-2x  custom-imageplus-icon"></i>
													</label>
													<input data-us="bdcs" class="hide oct-upload-images" type="file" name="" id="oct-upload-imagebdcs"  />
													
													<a id="oct-remove-company-imagebdcs" <?php if($oct_settings->octabook_company_logo!=''){ echo "style='display:block;'";}  ?> class="hide-div pull-left br-4 btn-danger oct-remove-company-img btn-xs oct_remove_image" rel="popover" data-placement='bottom' title="<?php echo __("Remove Image?","oct");?>"> <i class="fa fa-trash" title="<?php echo __("Remove company Image","oct");?>"></i></a>												
													<div style="display: none;" class="oct-popover" id="popover-oct-remove-company-imagebdcs">
														<div class="arrow"></div>
														<table class="form-horizontal" cellspacing="0">
															<tbody>
																<tr>
																	<td>
																		<a href="javascript:void(0)" value="Delete" data-mediapath="<?php echo $oct_settings->octabook_company_logo;?>" data-imgfieldid="bdcsuploadedimg"
																		class="btn btn-danger btn-sm oct_delete_companyimage"><?php echo __("Yes","oct");?></a>
																		<a href="javascript:void(0)" id="popover-oct-remove-company-imagebdcs" class="btn btn-default btn-sm close_delete_popup" href="javascript:void(0)"><?php echo __("Cancel","oct");?></a>
																	</td>
																</tr>
															</tbody>
														</table>
													</div>
												</div>
											</div>
											<div id="oct-image-upload-popupbdcs" class="oct-image-upload-popup modal fade" tabindex="-1" role="dialog">
										<div class="vertical-alignment-helper">
											<div class="modal-dialog modal-md vertical-align-center">
												<div class="modal-content" style="width:607px">
													<div class="modal-header">
														<div class="col-md-12 col-xs-12">
															<a data-us="bdcs" class="btn btn-success oct_upload_img" data-imageinputid="oct-upload-imagebdcs"><?php echo __("Crop & Save","oct");?></a>
															<button type="button" class="btn btn-default hidemodal" data-dismiss="modal" aria-hidden="true"><?php echo __("Cancel","oct");?></button>
														</div>	
													</div>
													<div class="modal-body">
														<img id="oct-preview-imgbdcs" />
													</div>
													<div class="modal-footer">
														<div class="col-md-12 np">
															<div class="col-md-4 col-xs-12">
																<label class="pull-left"><?php echo __("File size","oct");?></label> <input type="text" style="width:100%;" class="form-control" id="bdcsfilesize" name="filesize" />
															</div>	
															<div class="col-md-4 col-xs-12">	
																<label class="pull-left"><?php echo __("H","oct");?></label> <input type="text" style="width:100%;" class="form-control" id="bdcsh" name="h" /> 
															</div>
															<div class="col-md-4 col-xs-12">	
																<label class="pull-left"><?php echo __("W","oct");?></label> <input type="text" style="width:100%;" class="form-control" id="bdcsw" name="w" />
															</div>
															<input type="hidden" id="bdcsx1" name="x1" />
															 <input type="hidden" id="bdcsy1" name="y1" />
															<input type="hidden" id="bdcsx2" name="x2" />
															<input type="hidden" id="bdcsy2" name="y2" />
															<input id="bdcsbdimagetype" type="hidden" name="bdimagetype"/>
															<input type="hidden" id="bdcsbdimagename" name="bdimagename" value="" />
														</div>
													</div>							
												</div>		
											</div>			
										</div>			
									</div>
									<input name="companyimage" id="bdcsuploadedimg" type="hidden" value="<?php echo $oct_settings->octabook_company_logo;?>" />



											
											<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Company logo is used for invoicing purpose.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
								</tbody>
								
								<tfoot>
									<tr>
										<td></td>
										<td>
											<a href="javascript:void(0)" id="oct_save_company_settings" name="" class="btn btn-success" type="submit"><?php echo __("Save Setting","oct");?></a>
											<button type="reset" class="btn btn-default ml-30"><?php echo __("Default Setting","oct");?></button>
								
										</td>
									</tr>
								</tfoot>
							</table>	
						</div>
					</div>
				</form>
			</div>
			<!-- file upload preview -->
				<div class="oct-company-logo-popup-view">
					<div id="oct-image-upload-popup" class="oct-image-upload-popup modal fade" tabindex="-1" role="dialog">
						<div class="vertical-alignment-helper">
							<div class="modal-dialog modal-md vertical-align-center">
								<div class="modal-content">
									<div class="modal-header">
										<div class="col-md-12 col-xs-12">
											<button type="submit" class="btn btn-success"><?php echo __("Crop & Save","oct");?></button>
											<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true"><?php echo __("Cancel","oct");?></button>
										</div>	
									</div>
									<div class="modal-body">
										<img id="oct-preview-img" />
									</div>
									<div class="modal-footer">
										<div class="col-md-12 np">
											<div class="col-md-4 col-xs-12">
												<label class="pull-left"><?php echo __("File size","oct");?></label> <input type="text" class="form-control" id="filesize" name="filesize" />
											</div>	
											<div class="col-md-4 col-xs-12">	
												<label class="pull-left"><?php echo __("H","oct");?></label> <input type="text" class="form-control" id="h" name="h" /> 
											</div>
											<div class="col-md-4 col-xs-12">	
												<label class="pull-left"><?php echo __("W","oct");?></label> <input type="text" class="form-control" id="w" name="w" />
											</div>
										</div>

									</div>							
								</div>		
							</div>			
						</div>			
					</div>
				</div>
							
			<div class="tab-pane oct-toggle-abs" id="general-setting">
				<form id="" method="post" type="" class="oct-general-setting" >
					<div class="panel panel-default">
						<div class="panel-heading">
							<h1 class="panel-title"><?php echo __("General Settings","oct");?></h1>
						</div>
						<div class="panel-body">
							<table class="form-inline oct-common-table" >
								<tbody>	
									
									<tr>
										<td><label><?php echo __("Multi-Location","oct");?></label></td>
										<td>
											<div class="form-group">
												<label class="toggle-large" for="octabook_multi_location">
												
												<input <?php if($oct_settings->octabook_multi_location=='E') { echo ' checked '; }?> type="checkbox" id="octabook_multi_location" class="" name="ck"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
												</label>
											</div>
											<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Enable this option if you have multiple locations.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									
									
									<tr>
										<td><label><?php echo __("Zip Code Wise Booking","oct");?></label></td>
										<td>
											<div class="form-group">
												<label class="toggle-large" for="octabook_zipcode_booking">
												
												<input <?php if($oct_settings->octabook_zipcode_booking=='E') { echo ' checked '; }?> type="checkbox" id="octabook_zipcode_booking" class="" name="ck"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
												</label>
											</div>
											<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("You can get bookings by zip code.By enable this option","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									
									<tr id="octabook_booking_zipcodesetting" <?php if($oct_settings->octabook_zipcode_booking=='D'){?> class="hide-div" <?php } ?> >
									   <td><label><?php echo __("Zip Codes","oct");?></label></td>
									   <td>       
										<div class="form-group">
										 <label for="octabook_booking_zipcodes">
										  <textarea id="octabook_booking_zipcodes" class="form-control" cols="80" rows="6"><?php echo $oct_settings->octabook_booking_zipcodes; ?></textarea>
										 </label>
										</div>
										 <a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Add zip codes by comma separator value to provide booking in specific zip code areas.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
												
									   </td>
									</tr>
									<input type="hidden" value="<?php echo $oct_settings->octabook_booking_zipcodes; ?>" id="octabook_booking_zipcodes_hidd" />								
								
									<tr>
										<td><label><?php echo __("Time Interval","oct");?></label></td>
										<td>
											<div class="form-group">
												<select class="selectpicker" id="octabook_booking_time_interval" data-size="10"  style="display: none;">
												<option value=""><?php echo __("Set Booking Time Interval","oct");?></option>
												<option value="5" <?php if($oct_settings->octabook_booking_time_interval=='5') { echo ' selected '; }?>><?php echo __("5","oct");?> <?php echo __("Minutes","oct");?></option>
												<option value="10" <?php if($oct_settings->octabook_booking_time_interval=='10') { echo ' selected '; }?>><?php echo __("10","oct");?> <?php echo __("Minutes","oct");?></option>
												<option value="15" <?php if($oct_settings->octabook_booking_time_interval=='15') { echo ' selected '; }?>><?php echo __("15","oct");?> <?php echo __("Minutes","oct");?></option>
												<option value="20" <?php if($oct_settings->octabook_booking_time_interval=='20') { echo ' selected '; }?>><?php echo __("20","oct");?> <?php echo __("Minutes","oct");?></option>
												<option value="30" <?php if($oct_settings->octabook_booking_time_interval=='30') { echo ' selected '; }?>><?php echo __("30","oct");?> <?php echo __("Minutes","oct");?></option>
												<option value="45" <?php if($oct_settings->octabook_booking_time_interval=='45') { echo ' selected '; }?>><?php echo __("45","oct");?> <?php echo __("Minutes","oct");?></option>
												<option value="60" <?php if($oct_settings->octabook_booking_time_interval=='60') { echo ' selected '; }?>><?php echo __("1","oct");?> <?php echo __("Hour","oct");?></option>
												<option value="90" <?php if($oct_settings->octabook_booking_time_interval=='90') { echo ' selected '; }?>><?php echo __("1.5","oct");?> <?php echo __("Hours","oct");?></option>
												<option value="120" <?php if($oct_settings->octabook_booking_time_interval=='120') { echo ' selected '; }?>><?php echo __("2","oct");?> <?php echo __("Hours","oct");?></option>										<option value="180" <?php if($oct_settings->octabook_booking_time_interval=='180') { echo ' selected '; }?>><?php echo __("3","oct");?> <?php echo __("Hours","oct");?></option>										<option value="240" <?php if($oct_settings->octabook_booking_time_interval=='240') { echo ' selected '; }?>><?php echo __("4","oct");?> <?php echo __("Hours","oct");?></option>										<option value="300" <?php if($oct_settings->octabook_booking_time_interval=='300') { echo ' selected '; }?>><?php echo __("5","oct");?> <?php echo __("Hours","oct");?></option>										<option value="1439" <?php if($oct_settings->octabook_booking_time_interval=='1439') { echo ' selected '; }?>><?php echo __("1","oct");?> <?php echo __("Day","oct");?></option>
												</select>
											</div>	
											<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Time interval is helpful to show time difference between availability time slots.","oct"); ?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Minimum advance booking time","oct");?></label></td>
										<td>	
											<div class="form-group">
												<select class="selectpicker" id="octabook_minimum_advance_booking" data-size="10"  style="display: none;">
													<option value=""><?php echo __("Set Minimum advance booking time","oct");?></option>
													<option value="10" <?php if($oct_settings->octabook_minimum_advance_booking=='10') { echo ' selected '; }?>><?php echo __("10","oct");?> <?php echo __("Minutes","oct");?></option>
													<option value="20" <?php if($oct_settings->octabook_minimum_advance_booking=='20') { echo ' selected '; }?>><?php echo __("20","oct");?> <?php echo __("Minutes","oct");?></option>
													<option value="30" <?php if($oct_settings->octabook_minimum_advance_booking=='30') { echo ' selected '; }?>><?php echo __("30","oct");?> <?php echo __("Minutes","oct");?></option>
													<option value="60" <?php if($oct_settings->octabook_minimum_advance_booking=='60') { echo ' selected '; }?>><?php echo __("1","oct");?> <?php echo __("Hour","oct");?></option>
													<option value="120" <?php if($oct_settings->octabook_minimum_advance_booking=='120') { echo ' selected '; }?>><?php echo __("2","oct");?> <?php echo __("Hours","oct");?></option>
													<option value="180" <?php if($oct_settings->octabook_minimum_advance_booking=='180') { echo ' selected '; }?>><?php echo __("3","oct");?> <?php echo __("Hours","oct");?></option>
													<option value="240" <?php if($oct_settings->octabook_minimum_advance_booking=='240') { echo ' selected '; }?>><?php echo __("4","oct");?> <?php echo __("Hours","oct");?></option>
													<option value="300" <?php if($oct_settings->octabook_minimum_advance_booking=='300') { echo ' selected '; }?>><?php echo __("5","oct");?> <?php echo __("Hours","oct");?></option>
													<option value="360" <?php if($oct_settings->octabook_minimum_advance_booking=='360') { echo ' selected '; }?>><?php echo __("6","oct");?> <?php echo __("Hours","oct");?></option>
													<option value="420" <?php if($oct_settings->octabook_minimum_advance_booking=='420') { echo ' selected '; }?>><?php echo __("7","oct");?> <?php echo __("Hours","oct");?></option>
													<option value="480" <?php if($oct_settings->octabook_minimum_advance_booking=='480') { echo ' selected '; }?>><?php echo __("8","oct");?> <?php echo __("Hours","oct");?></option>
													<option value="720" <?php if($oct_settings->octabook_minimum_advance_booking=='720') { echo ' selected '; }?>><?php echo __("12","oct");?> <?php echo __("Hours","oct");?></option>
													<option value="1440" <?php if($oct_settings->octabook_minimum_advance_booking=='1440') { echo ' selected '; }?>><?php echo __("1","oct");?> <?php echo __("Day","oct");?></option>
													<option value="2880" <?php if($oct_settings->octabook_minimum_advance_booking=='2880') { echo ' selected '; }?>><?php echo __("2","oct");?> <?php echo __("Days","oct");?></option>
													<option value="4320" <?php if($oct_settings->octabook_minimum_advance_booking=='4320') { echo ' selected '; }?>><?php echo __("3","oct");?> <?php echo __("Days","oct");?></option>
													<option value="5760" <?php if($oct_settings->octabook_minimum_advance_booking=='5760') { echo ' selected '; }?>><?php echo __("4","oct");?> <?php echo __("Days","oct");?></option>
													<option value="7200" <?php if($oct_settings->octabook_minimum_advance_booking=='7200') { echo ' selected '; }?>><?php echo __("5","oct");?> <?php echo __("Day","oct");?></option>
													<option value="8640" <?php if($oct_settings->octabook_minimum_advance_booking=='8640') { echo ' selected '; }?>><?php echo __("6","oct");?> <?php echo __("Days","oct");?></option>
													<option value="10080" <?php if($oct_settings->octabook_minimum_advance_booking=='10080') { echo ' selected '; }?>><?php echo __("7","oct");?> <?php echo __("Days","oct");?></option>
												</select>
											</div>	
											<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Minimum advance booking time restrict client to book last minute booking, so that you should have sufficient time before appointment.","oct"); ?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Maximum advance booking time","oct");?></label></td>
										<td>
											<div class="form-group">
												<select id="octabook_maximum_advance_booking" class="selectpicker" data-size="10"  style="display: none;">
													<option value="1" <?php if($oct_settings->octabook_maximum_advance_booking==1) { echo ' selected '; } ?>  ><?php echo __("1","oct");?> <?php echo __("Month","oct");?></option>
													 <option value="2" <?php if($oct_settings->octabook_maximum_advance_booking==2) { echo ' selected '; } ?>  ><?php echo __("2","oct");?> <?php echo __("Months","oct");?></option>
													 <option value="3" <?php if($oct_settings->octabook_maximum_advance_booking==3) { echo ' selected '; } ?>  ><?php echo __("3","oct");?> <?php echo __("Months","oct");?></option>
													 <option value="4" <?php if($oct_settings->octabook_maximum_advance_booking==4) { echo ' selected '; } ?>  ><?php echo __("4","oct");?> <?php echo __("Months","oct");?></option>
													 <option value="5" <?php if($oct_settings->octabook_maximum_advance_booking==5) { echo ' selected '; } ?>  ><?php echo __("5","oct");?> <?php echo __("Months","oct");?></option>
													 <option value="6" <?php if($oct_settings->octabook_maximum_advance_booking==6) { echo ' selected '; } ?>  ><?php echo __("6","oct");?> <?php echo __("Months","oct");?></option>
													 <option value="7" <?php if($oct_settings->octabook_maximum_advance_booking==7) { echo ' selected '; } ?>  ><?php echo __("7","oct");?> <?php echo __("Months","oct");?></option>
													 <option value="8" <?php if($oct_settings->octabook_maximum_advance_booking==8) { echo ' selected '; } ?>  ><?php echo __("8","oct");?> <?php echo __("Months","oct");?></option>
													 <option value="9" <?php if($oct_settings->octabook_maximum_advance_booking==9) { echo ' selected '; } ?>  ><?php echo __("9","oct");?> <?php echo __("Months","oct");?></option>
													 <option value="10" <?php if($oct_settings->octabook_maximum_advance_booking==10) { echo ' selected '; } ?>  ><?php echo __("10","oct");?> <?php echo __("Months","oct");?></option>
													 <option value="11" <?php if($oct_settings->octabook_maximum_advance_booking==11) { echo ' selected '; } ?>  ><?php echo __("11","oct");?> <?php echo __("Months","oct");?></option>
													<option value="12" <?php if($oct_settings->octabook_maximum_advance_booking==12) { echo ' selected '; } ?>  ><?php echo __("1","oct");?> <?php echo __("year","oct");?></option>
													 <option value="24" <?php if($oct_settings->octabook_maximum_advance_booking==24) { echo ' selected '; } ?>  ><?php echo __("2","oct");?> <?php echo __("years","oct");?></option>
													 <option value="36" <?php if($oct_settings->octabook_maximum_advance_booking==36) { echo ' selected '; } ?>  ><?php echo __("3","oct");?> <?php echo __("years","oct");?></option>
												</select>
											</div>	
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Booking Padding Time","oct");?></label></td>
										<td>
											<div class="form-group">
												<select id="octabook_booking_padding_time" class="selectpicker" data-size="10"  style="display: none;">
													<option value=""><?php echo __("Set Booking Padding Time","oct");?></option>					
													<option value="10" <?php if($oct_settings->octabook_booking_padding_time=='10') { echo ' selected '; }?> ><?php echo __("10","oct");?> <?php echo __("Minutes","oct");?></option>
													<option value="20" <?php if($oct_settings->octabook_booking_padding_time=='20') { echo ' selected '; }?> ><?php echo __("20","oct");?> <?php echo __("Minutes","oct");?></option>
													<option value="30" <?php if($oct_settings->octabook_booking_padding_time=='30') { echo ' selected '; }?> ><?php echo __("30","oct");?> <?php echo __("Minutes","oct");?></option>
													<option value="45" <?php if($oct_settings->octabook_booking_padding_time=='45') { echo ' selected '; }?> ><?php echo __("45","oct");?> <?php echo __("Minutes","oct");?></option>
													<option value="60" <?php if($oct_settings->octabook_booking_padding_time=='60') { echo ' selected '; }?> ><?php echo __("60","oct");?> <?php echo __("Minutes","oct");?></option>
												</select>
											</div>	
											<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Booking Padding time is time span that you need after each appointment to get prepare or to take rest.","oct"); ?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Cancellation Buffer Time","oct");?></label></td>
										<td>
											<div class="form-group">
												<select id="octabook_cancellation_buffer_time" class="selectpicker" data-size="10"  style="display: none;">
													<option value=""><?php echo __("Set cancellation buffer time","oct");?></option>
													<option value="60" <?php if($oct_settings->octabook_cancellation_buffer_time=='60') { echo ' selected '; }?> ><?php echo __("1","oct");?> <?php echo __("Hour","oct");?></option>
													<option value="120" <?php if($oct_settings->octabook_cancellation_buffer_time=='120') { echo ' selected '; }?> ><?php echo __("2","oct");?> <?php echo __("Hours","oct");?></option>
													<option value="180" <?php if($oct_settings->octabook_cancellation_buffer_time=='180') { echo ' selected '; }?> ><?php echo __("3","oct");?> <?php echo __("Hours","oct");?></option>
													<option value="240" <?php if($oct_settings->octabook_cancellation_buffer_time=='240') { echo ' selected '; }?> ><?php echo __("4","oct");?> <?php echo __("Hours","oct");?></option>
													<option value="300" <?php if($oct_settings->octabook_cancellation_buffer_time=='300') { echo ' selected '; }?> ><?php echo __("5","oct");?> <?php echo __("Hours","oct");?></option>
													<option value="360" <?php if($oct_settings->octabook_cancellation_buffer_time=='360') { echo ' selected '; }?> ><?php echo __("6","oct");?> <?php echo __("Hours","oct");?></option>
													<option value="420" <?php if($oct_settings->octabook_cancellation_buffer_time=='420') { echo ' selected '; }?> ><?php echo __("7","oct");?> <?php echo __("Hours","oct");?></option>
													<option value="480" <?php if($oct_settings->octabook_cancellation_buffer_time=='480') { echo ' selected '; }?> ><?php echo __("8","oct");?> <?php echo __("Hours","oct");?></option>
													<option value="720" <?php if($oct_settings->octabook_cancellation_buffer_time=='720') { echo ' selected '; }?> ><?php echo __("12","oct");?> <?php echo __("Hours","oct");?></option>
													<option value="1440" <?php if($oct_settings->octabook_cancellation_buffer_time=='1440') { echo ' selected '; }?> ><?php echo __("24","oct");?> <?php echo __("Hours","oct");?></option>
												</select>
											</div>	
											<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Cancellation buffer helps service providers to avoid last minute cancellation by their clients. ","oct"); ?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Reschedule Buffer Time","oct");?></label></td>
										<td>
											<div class="form-group">
												<select id="octabook_reschedule_buffer_time" class="selectpicker" data-size="10"  style="display: none;">
													<option value=""><?php echo __("Set reschedule buffer time","oct");?></option>
													<option value="60" <?php if($oct_settings->octabook_reschedule_buffer_time=='60') { echo ' selected '; }?> ><?php echo __("1","oct");?> <?php echo __("Hour","oct");?></option>
													<option value="120" <?php if($oct_settings->octabook_reschedule_buffer_time=='120') { echo ' selected '; }?> ><?php echo __("2","oct");?> <?php echo __("Hours","oct");?></option>
													<option value="180" <?php if($oct_settings->octabook_reschedule_buffer_time=='180') { echo ' selected '; }?> ><?php echo __("3","oct");?> <?php echo __("Hours","oct");?></option>
													<option value="240" <?php if($oct_settings->octabook_reschedule_buffer_time=='240') { echo ' selected '; }?> ><?php echo __("4","oct");?> <?php echo __("Hours","oct");?></option>
													<option value="300" <?php if($oct_settings->octabook_reschedule_buffer_time=='300') { echo ' selected '; }?> ><?php echo __("5","oct");?> <?php echo __("Hours","oct");?></option>
													<option value="360" <?php if($oct_settings->octabook_reschedule_buffer_time=='360') { echo ' selected '; }?> ><?php echo __("6","oct");?> <?php echo __("Hours","oct");?></option>
													<option value="420" <?php if($oct_settings->octabook_reschedule_buffer_time=='420') { echo ' selected '; }?> ><?php echo __("7","oct");?> <?php echo __("Hours","oct");?></option>
													<option value="480" <?php if($oct_settings->octabook_reschedule_buffer_time=='480') { echo ' selected '; }?> ><?php echo __("8","oct");?> <?php echo __("Hours","oct");?></option>
													<option value="720" <?php if($oct_settings->octabook_reschedule_buffer_time=='720') { echo ' selected '; }?> ><?php echo __("12","oct");?> <?php echo __("Hours","oct");?></option>
													<option value="1440" <?php if($oct_settings->octabook_reschedule_buffer_time=='1440') { echo ' selected '; }?> ><?php echo __("24","oct");?> <?php echo __("Hours","oct");?></option>
												</select>
											</div>	
											<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Reschedule buffer helps service providers to avoid last minute reschedule by their clients. ","oct"); ?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Currency","oct");?></label></td>
										<td>
											<div class="form-group">
												<select id="octabook_currency" class="selectpicker form-control" data-live-search="true" data-live-search-placeholder="Search" data-actions-box="true"  >
													<option value=""><?php echo __("-- Select Currency --","oct");?></option>
												  <option value="ALL" <?php if($oct_settings->octabook_currency =='ALL' ){ echo ' selected '; }?>>Lek <?php echo "Albania Lek";?></option>
												  <option value="AED" <?php if($oct_settings->octabook_currency =='AED' ){ echo ' selected '; }?>>د.إ <?php echo "UAE Dirham";?></option>
												  <option value="AFN" <?php if($oct_settings->octabook_currency =='AFN' ){ echo ' selected '; }?>>؋ <?php echo "Afghanistan Afghani";?></option>
												  <option value="ARS" <?php if($oct_settings->octabook_currency =='ARS' ){ echo ' selected '; }?>>$ <?php echo "Argentina Peso";?></option>
												  <option value="ANG" <?php if($oct_settings->octabook_currency =='ANG' ){ echo ' selected '; }?>>NAƒ <?php echo "Neth Antilles Guilder";?></option>  
												  <option value="AWG" <?php if($oct_settings->octabook_currency =='AWG' ){ echo ' selected '; }?>>ƒ <?php echo "Aruba Guilder";?></option>
												  <option value="AUD" <?php if($oct_settings->octabook_currency =='AUD' ){ echo ' selected '; }?>>$ <?php echo "Australia Dollar";?></option>
												  <option value="AZN" <?php if($oct_settings->octabook_currency =='AZN' ){ echo ' selected '; }?>>ман <?php echo "Azerbaijan Manat";?></option>
												  <option value="BSD" <?php if($oct_settings->octabook_currency =='BSD' ){ echo ' selected '; }?>>$ <?php echo "Bahamas Dollar";?></option>
												  <option value="BBD" <?php if($oct_settings->octabook_currency =='BBD' ){ echo ' selected '; }?>>$ <?php echo "Barbados Dollar";?></option>
												  <option value="BYR" <?php if($oct_settings->octabook_currency =='BYR' ){ echo ' selected '; }?>>p <?php echo "Belarus Ruble";?></option>
												  <option value="BZD" <?php if($oct_settings->octabook_currency =='BZD' ){ echo ' selected '; }?>>BZ$ <?php echo "Belize Dollar";?></option>
												  <option value="BMD" <?php if($oct_settings->octabook_currency =='BMD' ){ echo ' selected '; }?>>$ <?php echo "Bermuda Dollar";?></option>					  
												  <option value="BOB" <?php if($oct_settings->octabook_currency =='BOB' ){ echo ' selected '; }?>>$b <?php echo "Bolivia	Boliviano";?></option>
												  <option value="BAM" <?php if($oct_settings->octabook_currency =='BAM' ){ echo ' selected '; }?>>KM <?php echo "Bosnia and Herzegovina Convertible Marka";?></option>
												  <option value="BWP" <?php if($oct_settings->octabook_currency =='BWP' ){ echo ' selected '; }?>>P <?php echo "Botswana Pula";?></option>
												  <option value="BGN" <?php if($oct_settings->octabook_currency =='BGN' ){ echo ' selected '; }?>>лв <?php echo "Bulgaria Lev";?></option>
												  <option value="BRL" <?php if($oct_settings->octabook_currency =='BRL' ){ echo ' selected '; }?>>R$ <?php echo "Brazil Real";?></option>
												  <option value="BND" <?php if($oct_settings->octabook_currency =='BND' ){ echo ' selected '; }?>>$ <?php echo "Brunei Darussalam Dollar";?></option>
												  
												  <option value="BDT" <?php if($oct_settings->octabook_currency =='BDT' ){ echo ' selected '; }?>>Tk <?php echo "Bangladesh Taka";?></option>
												  <option value="BIF" <?php if($oct_settings->octabook_currency =='BIF' ){ echo ' selected '; }?>>FBu <?php echo "Burundi Franc";?></option>
												  
												  <option value="CHF" <?php if($oct_settings->octabook_currency =='CHF' ){ echo ' selected '; }?>>CHF<?php echo "Swiss Franc";?></option>
												  
												  
												  <option value="KHR" <?php if($oct_settings->octabook_currency =='KHR' ){ echo ' selected '; }?>>៛  <?php echo "Cambodia Riel";?></option>
												  <option value="KMF" <?php if($oct_settings->octabook_currency =='KMF' ){ echo ' selected '; }?>>KMF <?php echo "Comoros Franc";?></option>
												  
												  <option value="CAD" <?php if($oct_settings->octabook_currency =='CAD' ){ echo ' selected '; }?>>$ <?php echo "Canada Dollar";?></option>
												  <option value="KYD" <?php if($oct_settings->octabook_currency =='KYD' ){ echo ' selected '; }?>>$ <?php echo "Cayman Dollar";?></option>
												  
												  <option value="CLP" <?php if($oct_settings->octabook_currency =='CLP' ){ echo ' selected '; }?>>$ <?php echo "Chile Peso";?></option>
												  <option value="CYN" <?php if($oct_settings->octabook_currency =='CYN' ){ echo ' selected '; }?>>¥ <?php echo "China Yuan Renminbi";?></option>
												  
												  <option value="CVE" <?php if($oct_settings->octabook_currency =='CVE' ){ echo ' selected '; }?>>Esc <?php echo "Cape Verde Escudo";?></option>
												  
												  <option value="COP" <?php if($oct_settings->octabook_currency =='COP' ){ echo ' selected '; }?>>$ <?php echo "Colombia Peso";?></option>
												  <option value="CRC" <?php if($oct_settings->octabook_currency =='CRC' ){ echo ' selected '; }?>>₡ <?php echo "Costa Rica Colon";?></option>
												  <option value="HRK" <?php if($oct_settings->octabook_currency =='HRK' ){ echo ' selected '; }?>>kn <?php echo "Croatia	Kuna";?></option>
												  <option value="CUP" <?php if($oct_settings->octabook_currency =='CUP' ){ echo ' selected '; }?>>₱ <?php echo "Cuba Peso";?></option>
												  <option value="CZK" <?php if($oct_settings->octabook_currency =='CZK' ){ echo ' selected '; }?>>Kč <?php echo "Czech Republic Koruna";?></option>
												 <option value="DKK" <?php if($oct_settings->octabook_currency =='DKK' ){ echo ' selected '; }?>>kr <?php echo "Denmark	Krone";?></option>
												 <option value="DOP" <?php if($oct_settings->octabook_currency =='DOP' ){ echo ' selected '; }?>>RD$ <?php echo "Dominican Republic Peso";?></option>
												 <option value="DJF" <?php if($oct_settings->octabook_currency =='DJF' ){ echo ' selected '; }?>>Fdj <?php echo "Djibouti Franc";?></option>
												 <option value="DZD" <?php if($oct_settings->octabook_currency =='DZD' ){ echo ' selected '; }?>>دج <?php echo "Algerian Dinar";?></option>
												 <option value="XCD" <?php if($oct_settings->octabook_currency =='XCD' ){ echo ' selected '; }?>>$  <?php echo "East Caribbean Dollar";?></option>
												 <option value="EGP" <?php if($oct_settings->octabook_currency =='EGP' ){ echo ' selected '; }?>>£ <?php echo "Egypt Pound";?></option>
												 <option value="ETB" <?php if($oct_settings->octabook_currency =='ETB' ){ echo ' selected '; }?>>Br <?php echo "Ethiopian Birr";?></option>
												 <option value="SVC" <?php if($oct_settings->octabook_currency =='SVC' ){ echo ' selected '; }?>>$  <?php echo "El Salvador Colon";?></option>
												 <option value="EEK" <?php if($oct_settings->octabook_currency =='EEK' ){ echo ' selected '; }?>>kr <?php echo "Estonia Kroon";?></option>
												 <option value="EUR" <?php if($oct_settings->octabook_currency =='EUR' ){ echo ' selected '; }?>>€  <?php echo "Euro Member Euro";?></option>
												 <option value="FKP" <?php if($oct_settings->octabook_currency =='FKP' ){ echo ' selected '; }?>>£ <?php echo "Falkland Islands Pound";?></option>
												 <option value="FJD" <?php if($oct_settings->octabook_currency =='FJD' ){ echo ' selected '; }?>>$  <?php echo "Fiji Dollar";?></option>
												 <option value="GHC" <?php if($oct_settings->octabook_currency =='GHC' ){ echo ' selected '; }?>>¢ <?php echo "Ghana Cedis";?></option>
												 <option value="GIP" <?php if($oct_settings->octabook_currency =='GIP' ){ echo ' selected '; }?>>£ <?php echo "Gibraltar Pound";?></option>
												 <option value="GMD" <?php if($oct_settings->octabook_currency =='GMD' ){ echo ' selected '; }?>>D <?php echo "Gambian Dalasi";?></option>
												 <option value="GNF" <?php if($oct_settings->octabook_currency =='GNF' ){ echo ' selected '; }?>>FG <?php echo "Guinea Franc";?></option>
												 <option value="GTQ" <?php if($oct_settings->octabook_currency =='GTQ' ){ echo ' selected '; }?>>Q <?php echo "Guatemala Quetzal";?></option>
												 <option value="GGP" <?php if($oct_settings->octabook_currency =='GGP' ){ echo ' selected '; }?>>£ <?php echo "Guernsey Pound";?></option>
												 <option value="GYD" <?php if($oct_settings->octabook_currency =='GYD' ){ echo ' selected '; }?>>$ <?php echo "Guyana Dollar";?></option>
											  <option value="HNL" <?php if($oct_settings->octabook_currency =='HNL' ){ echo ' selected '; }?>>L <?php echo "Honduras Lempira";?></option>
											  <option value="HKD" <?php if($oct_settings->octabook_currency =='HKD' ){ echo ' selected '; }?>>$ <?php echo "Hong Kong Dollar";?></option>
											  
											  <option value="HRK" <?php if($oct_settings->octabook_currency =='HRK' ){ echo ' selected '; }?>>kn <?php echo "Croatian Kuna";?></option>
											  <option value="HTG" <?php if($oct_settings->octabook_currency =='HTG' ){ echo ' selected '; }?>>G <?php echo "Haitian Gourde";?></option>
											  <option value="HUF" <?php if($oct_settings->octabook_currency =='HUF' ){ echo ' selected '; }?>>Ft <?php echo "Hungary	Forint";?></option>
											  <option value="ISK" <?php if($oct_settings->octabook_currency =='ISK' ){ echo ' selected '; }?>>kr <?php echo "Iceland	Krona";?></option>
											  <option value="INR" <?php if($oct_settings->octabook_currency =='INR' ){ echo ' selected '; }?>>Rs <?php echo "India Rupee";?></option>
											  <option value="IDR" <?php if($oct_settings->octabook_currency =='IDR' ){ echo ' selected '; }?>>Rp <?php echo "Indonesia Rupiah";?></option>
											  <option value="IRR" <?php if($oct_settings->octabook_currency =='IRR' ){ echo ' selected '; }?>>﷼ <?php echo "Iran Rial";?></option>
											  <option value="IMP" <?php if($oct_settings->octabook_currency =='IMP' ){ echo ' selected '; }?>>£ <?php echo "Isle of Man Pound";?></option>
											  <option value="ILS" <?php if($oct_settings->octabook_currency =='ILS' ){ echo ' selected '; }?>>₪ <?php echo "Israel Shekel";?></option>
											  <option value="JMD" <?php if($oct_settings->octabook_currency =='JMD' ){ echo ' selected '; }?>>J$ <?php echo "Jamaica Dollar";?></option>
											  <option value="JPY" <?php if($oct_settings->octabook_currency =='JPY' ){ echo ' selected '; }?>>¥ <?php echo "Japan Yen";?></option>
											  <option value="JEP" <?php if($oct_settings->octabook_currency =='JEP' ){ echo ' selected '; }?>>£ <?php echo "Jersey Pound";?></option>
											  <option value="KZT" <?php if($oct_settings->octabook_currency =='KZT' ){ echo ' selected '; }?>>лв <?php echo "Kazakhstan Tenge";?></option>
											  <option value="KPW" <?php if($oct_settings->octabook_currency =='KPW' ){ echo ' selected '; }?>>₩ <?php echo "Korea(North) Won";?></option>
											  <option value="KRW" <?php if($oct_settings->octabook_currency =='KRW' ){ echo ' selected '; }?>>₩ <?php echo "Korea(South) Won";?></option>
											  <option value="KGS" <?php if($oct_settings->octabook_currency =='KGS' ){ echo ' selected '; }?>>лв <?php echo "Kyrgyzstan Som";?></option>
											  <option value="KES" <?php if($oct_settings->octabook_currency =='KES' ){ echo ' selected '; }?>>KSh <?php echo "Kenyan Shilling";?></option>
												<option value="LAK" <?php if($oct_settings->octabook_currency =='LAK' ){ echo ' selected '; }?>>₭ <?php echo "Laos	Kip";?></option>
												<option value="LVL" <?php if($oct_settings->octabook_currency =='LVL' ){ echo ' selected '; }?>>Ls <?php echo "Latvia Lat";?></option>
												<option value="LBP" <?php if($oct_settings->octabook_currency =='LBP' ){ echo ' selected '; }?>>£ <?php echo "Lebanon Pound";?></option>
												<option value="LRD" <?php if($oct_settings->octabook_currency =='LRD' ){ echo ' selected '; }?>>$ <?php echo "Liberia Dollar";?></option>
												<option value="LTL" <?php if($oct_settings->octabook_currency =='LTL' ){ echo ' selected '; }?>>Lt <?php echo "Lithuania Litas";?></option>
												<option value="MKD" <?php if($oct_settings->octabook_currency =='MKD' ){ echo ' selected '; }?>>ден <?php echo "Macedonia Denar";?>	</option>
												<option value="MYR" <?php if($oct_settings->octabook_currency =='MYR' ){ echo ' selected '; }?>>RM <?php echo "Malaysia Ringgit";?></option>
												<option value="MUR" <?php if($oct_settings->octabook_currency =='MUR' ){ echo ' selected '; }?>>₨ <?php echo "Mauritius Rupee";?></option>
												<option value="MXN" <?php if($oct_settings->octabook_currency =='MXN' ){ echo ' selected '; }?>>$ <?php echo "Mexico Peso";?></option>
												<option value="MNT" <?php if($oct_settings->octabook_currency =='MNT' ){ echo ' selected '; }?>>₮ <?php echo "Mongolia Tughrik";?></option>
												<option value="MZN" <?php if($oct_settings->octabook_currency =='MZN' ){ echo ' selected '; }?>>MT <?php echo "Mozambique Metical";?></option>
												<option value="MAD" <?php if($oct_settings->octabook_currency =='MAD' ){ echo ' selected '; }?>>د.م. <?php echo "Moroccan Dirham";?></option>
												<option value="MDL" <?php if($oct_settings->octabook_currency =='MDL' ){ echo ' selected '; }?>>MDL <?php echo "Moldovan Leu";?></option>
												<option value="MOP" <?php if($oct_settings->octabook_currency =='MOP' ){ echo ' selected '; }?>>$ <?php echo "Macau Pataca";?></option>
												<option value="MRO" <?php if($oct_settings->octabook_currency =='MRO' ){ echo ' selected '; }?>>UM <?php echo "Mauritania Ougulya";?></option>
												<option value="MVR" <?php if($oct_settings->octabook_currency =='MVR' ){ echo ' selected '; }?>>Rf <?php echo "Maldives Rufiyaa";?></option>
												<option value="PGK" <?php if($oct_settings->octabook_currency =='PGK' ){ echo ' selected '; }?>>K <?php echo "Papua New Guinea Kina";?></option>
												<option value="NAD" <?php if($oct_settings->octabook_currency =='NAD' ){ echo ' selected '; }?>>$ <?php echo "Namibia Dollar";?></option>
												<option value="NPR" <?php if($oct_settings->octabook_currency =='NPR' ){ echo ' selected '; }?>>₨ <?php echo "Nepal Rupee";?></option>
												<option value="ANG" <?php if($oct_settings->octabook_currency =='ANG' ){ echo ' selected '; }?>>ƒ <?php echo "Netherlands Antilles Guilder";?></option>
												<option value="NZD" <?php if($oct_settings->octabook_currency =='NZD' ){ echo ' selected '; }?>>$ <?php echo "New Zealand Dollar";?></option>
												<option value="NIO" <?php if($oct_settings->octabook_currency =='NIO' ){ echo ' selected '; }?>>C$ <?php echo "Nicaragua Cordoba";?></option>
												<option value="NGN" <?php if($oct_settings->octabook_currency =='NGN' ){ echo ' selected '; }?>>₦ <?php echo "Nigeria Naira";?></option>
												<option value="NOK" <?php if($oct_settings->octabook_currency =='NOK' ){ echo ' selected '; }?>>kr <?php echo "Norway Krone";?></option>
												<option value="OMR" <?php if($oct_settings->octabook_currency =='OMR' ){ echo ' selected '; }?>>﷼ <?php echo "Oman Rial";?></option>
												<option value="MWK" <?php if($oct_settings->octabook_currency =='MWK' ){ echo ' selected '; }?>>MK <?php echo "Malawi Kwacha";?></option>
											<option value="PKR" <?php if($oct_settings->octabook_currency =='PKR' ){ echo ' selected '; }?>>₨ <?php echo "Pakistan Rupee";?></option>
											<option value="PAB" <?php if($oct_settings->octabook_currency =='PAB' ){ echo ' selected '; }?>>B/ <?php echo "Panama Balboa";?></option>
											<option value="PYG" <?php if($oct_settings->octabook_currency =='PYG' ){ echo ' selected '; }?>>Gs <?php echo "Paraguay Guarani";?></option>
											<option value="PEN" <?php if($oct_settings->octabook_currency =='PEN' ){ echo ' selected '; }?>>S/ <?php echo "Peru Nuevo Sol";?></option>
											<option value="PHP" <?php if($oct_settings->octabook_currency =='PHP' ){ echo ' selected '; }?>>₱ <?php echo "Philippines Peso";?></option>
											<option value="PLN" <?php if($oct_settings->octabook_currency =='PLN' ){ echo ' selected '; }?>>zł <?php echo "Poland Zloty";?></option>
											<option value="QAR" <?php if($oct_settings->octabook_currency =='QAR' ){ echo ' selected '; }?>>﷼ <?php echo "Qatar Riyal";?></option>
											<option value="RON" <?php if($oct_settings->octabook_currency =='RON' ){ echo ' selected '; }?>>lei <?php echo "Romania New Leu";?></option>
											<option value="RUB" <?php if($oct_settings->octabook_currency =='RUB' ){ echo ' selected '; }?>>руб <?php echo "Russia Ruble";?></option>
											<option value="SHP" <?php if($oct_settings->octabook_currency =='SHP' ){ echo ' selected '; }?>>£ <?php echo "Saint Helena Pound";?></option>
											<option value="SAR" <?php if($oct_settings->octabook_currency =='SAR' ){ echo ' selected '; }?>>﷼ <?php echo "Saudi Arabia	Riyal";?></option>
											<option value="RSD" <?php if($oct_settings->octabook_currency =='RSD' ){ echo ' selected '; }?>>Дин <?php echo "Serbia Dinar";?></option>
											<option value="SCR" <?php if($oct_settings->octabook_currency =='SCR' ){ echo ' selected '; }?>>₨ <?php echo "Seychelles Rupee";?></option>
											<option value="SGD" <?php if($oct_settings->octabook_currency =='SGD' ){ echo ' selected '; }?>>$ <?php echo "Singapore	Dollar";?></option>
											<option value="SBD" <?php if($oct_settings->octabook_currency =='SBD' ){ echo ' selected '; }?>>$ <?php echo "Solomon Islands Dollar";?></option>
											<option value="SOS" <?php if($oct_settings->octabook_currency =='SOS' ){ echo ' selected '; }?>>S <?php echo "Somalia Shilling";?></option>
											<option value="SLL" <?php if($oct_settings->octabook_currency =='SLL' ){ echo ' selected '; }?>>Le <?php echo "Sierra Leone Leone";?></option>
											<option value="STD" <?php if($oct_settings->octabook_currency =='STD' ){ echo ' selected '; }?>>Db <?php echo "Sao Tome Dobra";?></option>
											<option value="SZL" <?php if($oct_settings->octabook_currency =='SZL' ){ echo ' selected '; }?>>SZL <?php echo "Swaziland Lilageni";?></option>
											<option value="ZAR" <?php if($oct_settings->octabook_currency =='ZAR' ){ echo ' selected '; }?>>R <?php echo "South Africa Rand";?></option>
											<option value="LKR" <?php if($oct_settings->octabook_currency =='LKR' ){ echo ' selected '; }?>>₨ <?php echo "Sri Lanka Rupee";?></option>
											<option value="SEK" <?php if($oct_settings->octabook_currency =='SEK' ){ echo ' selected '; }?>>kr <?php echo "Sweden Krona";?></option>
											<option value="CHF" <?php if($oct_settings->octabook_currency =='CHF' ){ echo ' selected '; }?>>CHF <?php echo "Switzerland Franc";?> </option>
											<option value="SRD" <?php if($oct_settings->octabook_currency =='SRD' ){ echo ' selected '; }?>>$ <?php echo "Suriname Dollar";?></option>
											<option value="SYP" <?php if($oct_settings->octabook_currency =='SYP' ){ echo ' selected '; }?>>£ <?php echo "Syria	Pound";?></option>
											<option value="TWD" <?php if($oct_settings->octabook_currency =='TWD' ){ echo ' selected '; }?>>NT <?php echo "Taiwan New Dollar";?></option>
											<option value="THB" <?php if($oct_settings->octabook_currency =='THB' ){ echo ' selected '; }?>>฿ <?php echo "Thailand Baht";?></option>
											<option value="TOP" <?php if($oct_settings->octabook_currency =='TOP' ){ echo ' selected '; }?>>T$ <?php echo "Tonga Pa'ang";?></option>
											<option value="TZS" <?php if($oct_settings->octabook_currency =='TZS' ){ echo ' selected '; }?>>x <?php echo "Tanzanian Shilling";?></option>
											<option value="TTD" <?php if($oct_settings->octabook_currency =='TTD' ){ echo ' selected '; }?>>TTD <?php echo "Trinidad and Tobago Dollar";?></option>
											<option value="TRY" <?php if($oct_settings->octabook_currency =='TRY' ){ echo ' selected '; }?>>₤ <?php echo "Turkey Lira";?></option>
											<option value="TVD" <?php if($oct_settings->octabook_currency =='TVD' ){ echo ' selected '; }?>>$ <?php echo "Tuvalu Dollar";?></option>
											<option value="UAH" <?php if($oct_settings->octabook_currency =='UAH' ){ echo ' selected '; }?>>₴ <?php echo "Ukraine Hryvna";?></option>
											<option value="UGX" <?php if($oct_settings->octabook_currency =='UGX' ){ echo ' selected '; }?>>USh <?php echo "Ugandan Shilling";?></option>
											<option value="GBP" <?php if($oct_settings->octabook_currency =='GBP' ){ echo ' selected '; }?>>£ <?php echo "United Kingdom Pound";?></option>
											<option value="USD" <?php if($oct_settings->octabook_currency =='USD' ){ echo ' selected '; }?>>$ <?php echo "United States	Dollar";?></option>
											<option value="UYU" <?php if($oct_settings->octabook_currency =='UYU' ){ echo ' selected '; }?>>$U <?php echo "Uruguay Peso";?></option>
											<option value="UZS" <?php if($oct_settings->octabook_currency =='UZS' ){ echo ' selected '; }?>>лв <?php echo "Uzbekistan Som";?></option>
											<option value="VEF" <?php if($oct_settings->octabook_currency =='VEF' ){ echo ' selected '; }?>>Bs <?php echo "Venezuela Bolivar Fuerte";?></option>
											<option value="VND" <?php if($oct_settings->octabook_currency =='VND' ){ echo ' selected '; }?>>₫ <?php echo "Viet Nam Dong";?></option>
											<option value="VUV" <?php if($oct_settings->octabook_currency =='VUV' ){ echo ' selected '; }?>>Vt <?php echo "Vanuatu Vatu";?></option>
											<option value="XAF" <?php if($oct_settings->octabook_currency =='XAF' ){ echo ' selected '; }?>>BEAC <?php echo "CFA Franc (BEAC)";?></option>
											<option value="XOF" <?php if($oct_settings->octabook_currency =='XOF' ){ echo ' selected '; }?>>BCEAO <?php echo "CFA Franc (BCEAO)";?></option>
											<option value="XPF" <?php if($oct_settings->octabook_currency =='XPF' ){ echo ' selected '; }?>>F <?php echo "Pacific Franc";?></option>
											<option value="YER" <?php if($oct_settings->octabook_currency =='YER' ){ echo ' selected '; }?>>﷼ <?php echo "Yemen	Rial";?></option>
											<option value="WST" <?php if($oct_settings->octabook_currency =='WST' ){ echo ' selected '; }?>>WS$ <?php echo "Samoa Tala";?></option>
											<option value="ZAR" <?php if($oct_settings->octabook_currency =='ZAR' ){ echo ' selected '; }?>>R <?php echo "South African Rand";?></option>
											<option value="ZWD" <?php if($oct_settings->octabook_currency =='ZWD' ){ echo ' selected '; }?>>Z$ <?php echo "Zimbabwe Dollar";?></option>
												</select>
											</div>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Currency symbol position","oct");?></label></td>
										<td>
											<div class="form-group">
												<select id="octabook_currency_symbol_position" class="selectpicker" data-size="10"  style="display: none;">
													<option value="B"  <?php if($oct_settings->octabook_currency_symbol_position!='A') { echo " selected "; }?>  ><?php echo __("Before","oct");?>&nbsp;&nbsp;(e.g.&nbsp;<?php echo $oct_settings->octabook_currency_symbol;?>100)</option>
													<option value="A" <?php if($oct_settings->octabook_currency_symbol_position=='A') { echo " selected "; }?> ><?php echo __("After","oct");?>&nbsp;&nbsp;(e.g.&nbsp;100<?php echo $oct_settings->octabook_currency_symbol;?>)</option>
												</select>
											</div>	
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Price format decimal Places","oct");?></label></td>
										<td>
											<div class="form-group">
												<select id="octabook_price_format_decimal_places" class="selectpicker" data-size="10"  style="display: none;">
													<option value="0" <?php if($oct_settings->octabook_price_format_decimal_places=='0') { echo ' selected ';}?> ><?php echo __("0 (e.g.$100)","oct");?></option>
													<option value="1" <?php if($oct_settings->octabook_price_format_decimal_places=='1') { echo ' selected ';}?> ><?php echo __("1 (e.g.$100.0)","oct");?></option>
													<option value="2" <?php if($oct_settings->octabook_price_format_decimal_places=='2') { echo ' selected ';}?> ><?php echo __("2 (e.g.$100.00)","oct");?></option>
													<option value="3" <?php if($oct_settings->octabook_price_format_decimal_places=='3') { echo ' selected ';}?> ><?php echo __("3 (e.g.$100.000)","oct");?></option>
													<option value="4" <?php if($oct_settings->octabook_price_format_decimal_places=='4') { echo ' selected ';}?> ><?php echo __("4 (e.g.$100.0000)","oct");?></option>	
												</select>
											</div>	
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Price format comma separator","oct");?></label></td>
										<td>
											<div class="form-group">
												<select id="octabook_price_format_comma_separator"  class="selectpicker" data-size="10"  style="display: none;" >
												<option value="N" <?php if($oct_settings->octabook_price_format_comma_separator=='N') { echo ' selected ';}?> ><?php echo __("No","oct");?><?php echo __("(e.g. 1000.00)","oct");?> </option>
												<option value="Y" <?php if($oct_settings->octabook_price_format_comma_separator=='Y') { echo ' selected ';}?> ><?php echo __("Yes","oct");?> <?php echo __("(e.g. 1,000.00)","oct");?></option>						
												</select>
											</div>	
										</td>
									</tr>
									
									<tr>
										<td><label><?php echo __("Location sorting by","oct");?></label></td>
										<td>
											<div class="form-group">
												<select id="octabook_location_sortby" class="selectpicker" data-size="10"  style="display: none;">
												<option value="state" <?php if($oct_settings->octabook_location_sortby=='state') { echo ' selected ';}?> ><?php echo __("State","oct");?></option>
												<option value="city" <?php if($oct_settings->octabook_location_sortby=='city') { echo ' selected ';}?> ><?php echo __("City","oct");?></option>						
												</select>
											</div>	
										</td>
									</tr>
									
									<tr>
										<td><label><?php echo __("Tax/Vat","oct");?></label></td>
										<td>
											<div class="form-group">
												<label class="toggle-large" for="octabook_taxvat_status">
													<input type="checkbox" class="oct-toggle-sh" name="octabook_taxvat_status" id="octabook_taxvat_status" <?php if($oct_settings->octabook_taxvat_status=='E') { echo ' checked  '; }?>  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
												</label><div class="tool_mar_80">
												<a class="oct-tooltip-link pr-t0" href="#" data-toggle="tooltip" title="<?php echo __("If you are charging tax, please enable to specify the rate.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
												</div>
												<div class="<?php if($oct_settings->octabook_taxvat_status=='D') { echo "hide-div";}?> collapse_octabook_taxvat_status">
													<div class="oct-custom-radio">
														<ul class="oct-radio-list">
															<li>
																<input type="radio" id="tax-vat-percentage" class="oct-radio" <?php if($oct_settings->octabook_taxvat_type=='P') { echo ' checked="checked" '; }?>   name="octabook_taxvat_type" value="P" />
																<label for="tax-vat-percentage"><span></span><?php echo __("Percentage","oct");?></label>
															</li>
															<li>
																<input type="radio" id="tax-vat-flatfree" class="ak_radio" <?php if($oct_settings->octabook_taxvat_type=='F') { echo ' checked="checked" '; }?> name="octabook_taxvat_type" value="F" />
																<label for="tax-vat-flatfree"><span></span><?php echo __("Flat Fee","oct");?></label>
															</li>
															<li class="oct-tax-vat-input-container">
																<input type="text" class="form-control" id="octabook_taxvat_amount" value="<?php echo $oct_settings->octabook_taxvat_amount; ?>" size="3" maxlength="3" /><i  class="oct-tax-percent fa fa-percent <?php if($oct_settings->octabook_taxvat_type=='F') { echo 'hide-div '; }?>"></i>
															</li>
														</ul>	
													</div>
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Partial Deposit","oct");?></label></td>
										<td>
											<div class="form-group">
												<label class="toggle-large" for="octabook_partial_deposit_status">
												<input type="checkbox" class="oct-toggle-pd" <?php if($oct_settings->octabook_partial_deposit_status=='E') { echo ' checked="checked" '; }?> id="octabook_partial_deposit_status" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
												
												</label>
												<div class="tool_mar_80">
												<a class="oct-tooltip-link pr-t0" href="#" data-toggle="tooltip" title="<?php echo __("Partial payment option will help you to charge partial payment of total amount from client and remaining you can collect locally.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
												</div>
												<div id="oct_partial_depost_error" class="hide-div"><label class="error"><?php echo __("Please enable payment gateway first.","oct");?></label></div>
												<div class="<?php if($oct_settings->octabook_partial_deposit_status=='D') { echo "hide-div";}?> collapse_octabook_partial_deposit_status">
												<div class="oct-custom-radio">
													<ul class="oct-radio-list">
														<li>
															<input type="radio" id="partialdeposit-percentage" class="oct-radio" <?php if($oct_settings->octabook_partial_deposit_type=='P') { echo ' checked="checked" '; }?>   name="octabook_partial_deposit_type" value="P" />
															<label for="partialdeposit-percentage"><span></span><?php echo __("Percentage","oct");?></label>
														</li>
														<li>
															<input type="radio" id="partialdeposit-flatfree" class="oct_radio" <?php if($oct_settings->octabook_partial_deposit_type=='F') { echo ' checked="checked" '; }?> name="octabook_partial_deposit_type" value="F" />
															<label for="partialdeposit-flatfree"><span></span><?php echo __("Flat Fee","oct");?></label>
														</li>
														<li class="oct-tax-vat-input-container">
															<input type="text" class="form-control" id="octabook_partial_deposit_amount" value="<?php echo $oct_settings->octabook_partial_deposit_amount; ?>" size="3" maxlength="3" /><i  class="oct-partial-deposit-percent fa fa-percent <?php if($oct_settings->octabook_partial_deposit_type=='F') { echo 'hide-div '; }?>"></i>
														</li>
													</ul>
												</div>		
													 <br/><br/>
													<div>
													<label><?php echo __("Partial Deposit Message","oct");?></label>
													</div>
													<div>
													<textarea id="octabook_partial_deposit_message" class="form-control" row="4" cols="40"><?php echo $oct_settings->octabook_partial_deposit_message; ?></textarea>
													</div>
												</div>
											</div>
											
											<span id="oct-partial-depost_error" style="display:none;color:red;" ><?php echo __("Please Enable Payment Gateway","oct");?></span>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("'Thankyou Page' Url","oct");?></label></td>
										<td>
											<div class="form-group">
												<input id="octabook_thankyou_page" type="text" class="form-control" size="50" name="" value="<?php echo $oct_settings->octabook_thankyou_page; ?>" placeholder="Custom Thankyou page url" />
												<i><?php echo __("Default url is :","oct");?> <?php echo site_url();?>/oct-thankyou/</i>
											</div>	
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("'Thankyou Page' redirection time","oct");?></label></td>
										<td>
											<div class="form-group">
												<select class="selectpicker" data-size="10" id="octabook_thankyou_page_rdtime" name="octabook_thankyou_page_rdtime">
													<option value=''><?php echo __("Off","oct");?></option>
													<?php for($rdtimes=1;$rdtimes<=15;$rdtimes++) { ?>
													<option <?php if($oct_settings->octabook_thankyou_page_rdtime==($rdtimes*1000)){ echo 'selected="selected"'; }?> value="<?php echo $rdtimes*1000;?>"><?php if($rdtimes==1){ echo $rdtimes.__(" second","oct");}else{ echo $rdtimes.__(" seconds","oct");}?></option>
													<?php } for($rdtimem=1;$rdtimem<=15;$rdtimem++) { ?>
													<option <?php if($oct_settings->octabook_thankyou_page_rdtime==($rdtimem*60000)){ echo 'selected'; }?> value="<?php echo $rdtimem*60000;?>"><?php if($rdtimem==1){ echo $rdtimem . __(" minute","oct");}else{ echo $rdtimem . __(" minutes","oct");}?></option>
													<?php } ?>						
												</select>
											</div>	
										</td>
									</tr>
									
									<tr>
										<td><label><?php echo __("Allow multiple booking for same timeslot","oct");?></label></td>
										<td>
											<div class="form-group col-md-12 np">
												<label class="manage-right toggle-large" for="octabook_multiple_booking_sameslot">
													<input <?php if($oct_settings->octabook_multiple_booking_sameslot=='E') { echo ' checked="checked" '; }?> type="checkbox" class="oct-toggle-sh" id="octabook_multiple_booking_sameslot"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
												</label>
												<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Allow multiple appointment booking at same time slot, will allow you to show availability time slot even you have booking already for that time.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
												<div class="<?php if($oct_settings->octabook_multiple_booking_sameslot=='D') { echo "hide-div";}?> collapse_octabook_multiple_booking_sameslot col-md-9 col-sm-12 col-xs-12 npr pull-right">
													<span class="custom-form-width-auto oct-tax-vat-input-container">
													<label class="pull-left mr-10"><?php echo __("Maximum booking limit","oct");?></label>
														<select id="octabook_slot_max_booking_limit" class="selectpicker" data-size="10" data-width="100px"  style="display: none;">
														<option value="0" <?php if($oct_settings->octabook_slot_max_booking_limit==0) { echo ' selected ';}?> ><?php echo __("Unlimited","oct");?></option>
														<option value="1" <?php if($oct_settings->octabook_slot_max_booking_limit==1) { echo ' selected ';}?> ><?php echo __("1","oct");?></option>
														<option value="2" <?php if($oct_settings->octabook_slot_max_booking_limit==2) { echo ' selected ';}?> ><?php echo __("2","oct");?></option>
														<option value="3" <?php if($oct_settings->octabook_slot_max_booking_limit==3) { echo ' selected ';}?> ><?php echo __("3","oct");?></option>
														<option value="4" <?php if($oct_settings->octabook_slot_max_booking_limit==4) { echo ' selected ';}?> ><?php echo __("4","oct");?></option>
														<option value="5" <?php if($oct_settings->octabook_slot_max_booking_limit==5) { echo ' selected ';}?> ><?php echo __("5","oct");?></option>
														<option value="6" <?php if($oct_settings->octabook_slot_max_booking_limit==6) { echo ' selected ';}?> ><?php echo __("6","oct");?></option>
														<option value="7" <?php if($oct_settings->octabook_slot_max_booking_limit==7) { echo ' selected ';}?> ><?php echo __("7","oct");?></option>
														<option value="8" <?php if($oct_settings->octabook_slot_max_booking_limit==8) { echo ' selected ';}?> ><?php echo __("8","oct");?></option>
														<option value="9" <?php if($oct_settings->octabook_slot_max_booking_limit==9) { echo ' selected ';}?> ><?php echo __("9","oct");?></option>
														<option value="10" <?php if($oct_settings->octabook_slot_max_booking_limit==10) { echo ' selected ';}?> ><?php echo __("10","oct");?></option>
														</select>
													</div>
												
												
											</div>
											
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Appointment auto confirm","oct");?></label></td>
										<td>
											<div class="form-group">
												<label class="toggle-large" for="octabook_appointment_auto_confirm">
													<input <?php if($oct_settings->octabook_appointment_auto_confirm=='E') { echo ' checked="checked" '; }?> type="checkbox" id="octabook_appointment_auto_confirm" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
												</label>
											</div>
											<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("With Enable of this feature, Appointment request from clients will be auto confirmed.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Allow day closing time overlap booking","oct");?></label></td>
										<td>
											<div class="form-group">
												<label class="toggle-large" for="octabook_dayclosing_overlap">
													<input <?php if($oct_settings->octabook_dayclosing_overlap=='E') { echo ' checked="checked" '; }?> type="checkbox" id="octabook_dayclosing_overlap"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
												</label>
											</div>
											<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("you want to allow booking even service during overlap the day closing time.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<!-- add new option -->
									<tr>
										<td><label><?php echo __("Display cart discription in frontside","oct");?></label></td>
										<td>
											<div class="form-group">
												<label class="toggle-large" for="booking_cart_description">
												
													<input <?php if($oct_settings->booking_cart_description=='E') { echo ' checked="checked" '; }?> type="checkbox" id="booking_cart_description"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
												</label>
											</div>
											<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("you want to allow display cart description in front side","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Date Format","oct");?></label></td>
										<td>
											<div class="form-group">
												<label class="toggle-large" for="octabook_datepicker_format">
													
												</label>
												<select name="octabook_datepicker_format" id="octabook_datepicker_format" class="selectpicker form-control" data-size="5" data-live-search="true"  data-actions-box="true" >
												
											<option value="d-m-Y" <?php if($oct_settings->octabook_datepicker_format=='d-m-Y'){echo 'selected';} ?>>dd-mm-yyyy (eg. <?php echo date('d-m-Y');?>)</option>
											<option value="j-m-Y" <?php if($oct_settings->octabook_datepicker_format=='j-m-Y'){echo 'selected';} ?>>d-mm-yyyy (eg. <?php echo date('j-n-Y');?>)</option>
											<option value="d-M-Y" <?php if($oct_settings->octabook_datepicker_format=='d-M-Y'){echo 'selected';} ?>>dd-m-yyyy (eg. <?php echo date('d-M-Y');?>)</option>
											<option value="d-F-Y" <?php if($oct_settings->octabook_datepicker_format=='d-F-Y'){echo 'selected';} ?>>dd-m-yyyy (eg. <?php echo date('d-F-Y');?>)</option>
											<option value="j-M-Y" <?php if($oct_settings->octabook_datepicker_format=='j-M-Y'){echo 'selected';} ?>>d-m-yyyy (eg. <?php echo date('j-M-Y');?>)</option>
											<option value="j-F-Y" <?php if($oct_settings->octabook_datepicker_format=='j-F-Y'){echo 'selected';} ?>>dd-m-yyyy (eg. <?php echo date('j-F-Y');?>)</option>
											<!-- With Slashes -->
											<option value="d/m/Y" <?php if($oct_settings->octabook_datepicker_format=='d/m/Y'){echo 'selected';} ?>>dd/mm/yyyy (eg. <?php echo date('d/m/Y');?>)</option>
											<option value="j/m/Y" <?php if($oct_settings->octabook_datepicker_format=='j/m/Y'){echo 'selected';} ?>>d/mm/yyyy (eg. <?php echo date('j/m/Y');?>)</option>
											<option value="d/M/Y" <?php if($oct_settings->octabook_datepicker_format=='d/M/Y'){echo 'selected';} ?>>dd/m/yyyy (eg. <?php echo date('d/M/Y');?>)</option>
											<option value="d/F/Y" <?php if($oct_settings->octabook_datepicker_format=='d/F/Y'){echo 'selected';} ?>>dd/M/yyyy (eg. <?php echo date('d/F/Y');?>)</option>
											<option value="j/M/Y" <?php if($oct_settings->octabook_datepicker_format=='j/M/Y'){echo 'selected';} ?>>d/m/yyyy (eg. <?php echo date('j/M/Y');?>)</option>
											<option value="j/F/Y" <?php if($oct_settings->octabook_datepicker_format=='j/F/Y'){echo 'selected';} ?>>d/M/yyyy (eg. <?php echo date('j/F/Y');?>)</option>
											<!-- Month Day Year Suffled -->
											<option value="m-d-Y"  <?php if($oct_settings->octabook_datepicker_format=='m-d-Y'){echo 'selected';} ?> >mm-dd-yyyy (eg. <?php echo date('m-d-Y');?>)</option>
											<option value="m-j-Y" <?php if($oct_settings->octabook_datepicker_format=='m-j-Y'){echo 'selected';} ?> >mm-d-yyyy (eg. <?php echo date('m-j-Y');?>)</option>
											<option value="M-d-Y" <?php if($oct_settings->octabook_datepicker_format=='M-d-Y'){echo 'selected';} ?>>m-dd-yyyy (eg. <?php echo date('M-d-Y');?>)</option>
											<option value="F-d-Y" <?php if($oct_settings->octabook_datepicker_format=='F-d-Y'){echo 'selected';} ?>>m-dd-yyyy (eg. <?php echo date('F-d-Y');?>)</option>
											<option value="M-j-Y" <?php if($oct_settings->octabook_datepicker_format=='M-j-Y'){echo 'selected';} ?>>m-d-yyyy (eg. <?php echo date('M-j-Y');?>)</option>
											<option value="F-j-Y" <?php if($oct_settings->octabook_datepicker_format=='F-j-Y'){echo 'selected';} ?>>m-dd-yyyy (eg. <?php echo date('F-j-Y');?>)</option>
											<!-- With Slashes -->
											<option value="m/d/Y" <?php if($oct_settings->octabook_datepicker_format=='m/d/Y'){echo 'selected';} ?>>mm/dd/yyyy (eg. <?php echo date('m/d/Y');?>)</option>
											<option value="m/j/Y" <?php if($oct_settings->octabook_datepicker_format=='m/j/Y'){echo 'selected';} ?>>mm/d/yyyy (eg. <?php echo date('m/j/Y');?>)</option>
											<option value="M/d/Y" <?php if($oct_settings->octabook_datepicker_format=='M/d/Y'){echo 'selected';} ?>>m/dd/yyyy (eg. <?php echo date('M/d/Y');?>)</option>
											<option value="F/d/Y" <?php if($oct_settings->octabook_datepicker_format=='F/d/Y'){echo 'selected';} ?>>m/dd/yyyy (eg. <?php echo date('F/d/Y');?>)</option>
											<option value="M/j/Y" <?php if($oct_settings->octabook_datepicker_format=='M/j/Y'){echo 'selected';} ?>>m/d/yyyy (eg. <?php echo date('M/j/Y');?>)</option>
											<option value="F/j/Y" <?php if($oct_settings->octabook_datepicker_format=='F/j/Y'){echo 'selected';} ?>>m/dd/yyyy (eg. <?php echo date('F/j/Y');?>)</option>
											<option value="j M,Y" <?php if($oct_settings->octabook_datepicker_format=='j M,Y'){echo 'selected';} ?>>dd m,yyyy (eg. <?php echo date('j M,Y');?>)</option>
											<option value="M j, Y" <?php if($oct_settings->octabook_datepicker_format=='M j, Y'){echo 'selected';} ?>>m dd,yyyy (eg. <?php echo date('M j, Y');?>)</option>
										</select>
											</div>
											<?php 
											/* <a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("you want to allow display cart description in front side","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a> */
											?>
										</td>
									</tr>
									<tr>
                                    <td><label><?php echo __("Cancellation Policy","oct");?></label></td>
                                    <td>
                                        <div class="form-group">
                                            <label class="toggle-large" for="octabook_cancelation_policy_status">
												<input type="checkbox" class="oct-toggle-sh" name="octabook_cancelation_policy_status" id="octabook_cancelation_policy_status" <?php if($oct_settings->octabook_cancelation_policy_status=='E') { echo ' checked  '; }?>  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
											</label>
											<div class="<?php if($oct_settings->octabook_cancelation_policy_status=='D') { echo "hide-div";} ?> collapse_octabook_cancelation_policy_status">
												<div class="oct-custom-radio">
                                                    <ul class="oct-radio-list np mb-15">
                                                        <li class="w100">
                                                            <label><?php echo __("Cancellation Policy Header","oct");?></label>
                                                            <input type="text" class="w100 form-control" id="octabook_cancelation_policy_header" name="octabook_cancelation_policy_header" value="<?php echo ($oct_settings->octabook_cancelation_policy_header);?>" />
                                                        </li>
                                                    </ul>
                                                </div>
                                                <label><?php echo __("Cancellation Policy Textarea","oct");?></label>
                                               <textarea class="form-control w100" id="octabook_cancelation_policy_text" name="octabook_cancelation_policy_text" row="4" cols="40"><?php echo ($oct_settings->octabook_cancelation_policy_text);?></textarea>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
								<tr>
                                    <td><label><?php echo __("Terms & Conditions","oct");?></label></td>
                                    <td>
                                        <div class="form-group">
                                        	<label class="toggle-large" for="octabook_allow_terms_and_conditions">
												<input type="checkbox" class="oct-toggle-sh" name="octabook_allow_terms_and_conditions" id="octabook_allow_terms_and_conditions" <?php if($oct_settings->octabook_allow_terms_and_conditions=='E') { echo ' checked  '; }?>  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
											</label>
											
                                            <div class="<?php if($oct_settings->octabook_allow_terms_and_conditions=='D') { echo "hide-div";}?> collapse_octabook_allow_terms_and_conditions">
                                                <div class="oct-custom-radio">
                                                    <ul class="oct-radio-list">
                                                        <li>
                                                            <label><?php echo __("Terms & Condition Link","oct");?></label>
                                                            <input type="text" class="form-control" size="50" id="octabook_allow_terms_and_conditions_url" name="octabook_allow_terms_and_conditions_url" value="<?php echo urldecode($oct_settings->octabook_allow_terms_and_conditions_url);?>" />
														</li>
                                                    </ul>
                                                </div>
                                            </div>
                                          
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label><?php echo __("Privacy Policy","oct");?></label></td>
                                    <td>
                                        <div class="form-group">
                                        	<label class="toggle-large" for="octabook_allow_privacy_policy">
												<input type="checkbox" class="oct-toggle-sh" name="octabook_allow_privacy_policy" id="octabook_allow_privacy_policy" <?php if($oct_settings->octabook_allow_privacy_policy=='E') { echo ' checked  '; }?>  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
											</label>
											
											<div class="<?php if($oct_settings->octabook_allow_privacy_policy=='D') { echo "hide-div";}?> collapse_octabook_allow_privacy_policy">
												<div class="oct-custom-radio">
                                                    <ul class="oct-radio-list">
                                                        <li class="oct-privacy-policy-li-width">
                                                            <?php echo __("Privacy Policy Link","oct");?>
                                                            <input type="text" class="form-control" size="50" id="octabook_allow_privacy_policy_url" name="octabook_allow_privacy_policy_url" value="<?php echo urldecode($oct_settings->octabook_allow_privacy_policy_url);?>" />
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                         </div>
                                    </td>
                                </tr>
									
									
									
									<!-- end-->
									
									
								</tbody>
								<tfoot>
									<tr>
										<td></td>
										<td>
											<a id="oct_save_general_settings" name="" class="btn btn-success"><?php echo __("Save Setting","oct");?></a>
											<button type="reset" class="btn btn-default ml-30"><?php echo __("Default Setting","oct");?></button>
								
										</td>
									</tr>
								</tfoot>
							</table>
							
						</div>
					</div>
				</form>	
			</div>
			
			<div class="tab-pane oct-toggle-abs" id="appearance-setting">
				<form id="" method="post" type="" class="oct-appearance-settings" >
					<div class="panel panel-default">
						<div class="panel-heading">
							<h1 class="panel-title"><?php echo __("Appearance Settings","oct");?></h1>
						</div>
						<div class="panel-body">
							<table class="form-inline oct-common-table" >
								<tbody>
									<tr>
										<td><label> <?php echo __("Color Scheme","oct");?></label></td>
										<td>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 npl">
												<label><?php echo __("Primary Color","oct");?></label>
												<input type="text" id="octabook_primary_color" class="form-control demo" data-control="saturation" value="<?php echo $oct_settings->octabook_primary_color;?>" />
											</div>	
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 npl">
												<label><?php echo __("Secondary color","oct");?></label>
												<input type="text" id="octabook_secondary_color" class="form-control demo" data-control="saturation" value="<?php echo $oct_settings->octabook_secondary_color;?>" />
											</div>	
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 npl">
												<label><?php echo __("Text color","oct");?></label>
												<input type="text" id="octabook_text_color" class="form-control demo" data-control="saturation" value="<?php echo $oct_settings->octabook_text_color;?>" />
											</div>	
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 npl">
												<label><?php echo __("Text color on Bg","oct");?></label>
												<input type="text" id="octabook_bg_text_color" class="form-control demo" data-control="saturation" value="<?php echo $oct_settings->octabook_bg_text_color;?>" />
											</div>	
										</td>
									</tr>
									<tr>
										<td><label> <?php echo __("Admin Area Color Scheme","oct");?></label></td>
										<td>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 npl">	
												<label><?php echo __("Admin Primary Color","oct");?></label>
												<input type="text" id="octabook_admin_color_primary" class="form-control demo" data-control="saturation" value="<?php echo $oct_settings->octabook_admin_color_primary;?>" />
											</div>	
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 npl">	
												<label><?php echo __("Admin Secondary color","oct");?></label>
												<input type="text" id="octabook_admin_color_secondary" class="form-control demo" data-control="saturation" value="<?php echo $oct_settings->octabook_admin_color_secondary;?>" />
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 npl">	
												<label><?php echo __("Admin Text color","oct");?></label>
												<input type="text" id="octabook_admin_color_text" class="form-control demo" data-control="saturation" value="<?php echo $oct_settings->octabook_admin_color_text;?>" />
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 npl">
												<label><?php echo __("Admin Text color on Bg","oct");?></label>
												<input type="text" id="octabook_admin_color_bg_text" class="form-control demo" data-control="saturation" value="<?php echo $oct_settings->octabook_admin_color_bg_text;?>" />
											</div>
										</td>
									</tr>
									<!--<tr>
										<td><label><?php echo __("Show service providers","oct");?></label></td>
										<td>
											<div class="form-group">
												<label for="octabook_show_provider">
													<input <?php if($oct_settings->octabook_show_provider=='E') { echo ' checked="checked" '; }?> type="checkbox" id="octabook_show_provider" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","oct");?>" data-off="<?php echo __("Off","oct");?>" data-onstyle="success" data-offstyle="default" />
												</label>
											</div>
											<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("you can hide service providers, if you think there is only one service provider you want to use.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>-->
									<tr>
										<td><label><?php echo __("Show providers avatars","oct");?></label></td>
										<td>
											<div class="form-group">
												<label for="octabook_show_provider_avatars">
													<input <?php if($oct_settings->octabook_show_provider_avatars=='E') { echo ' checked="checked" '; }?> type="checkbox" id="octabook_show_provider_avatars" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","oct");?>" data-off="<?php echo __("Off","oct");?>" data-onstyle="success" data-offstyle="default" />
												</label>
											</div>
											<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("This will show avatars of providers on front.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<!--<tr>
										<td><label><?php echo __("Show service dropdown","oct");?></label></td>
										<td>
											<div class="form-group">
												<label for="octabook_show_services">
													<input <?php if($oct_settings->octabook_show_services=='E') { echo ' checked="checked" '; }?> type="checkbox" id="octabook_show_services" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","oct");?>" data-off="<?php echo __("Off","oct");?>" data-onstyle="success" data-offstyle="default" />
												</label>
											</div>
											<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("It will enable/disable dropdown for service on front.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>-->
									<tr>
										<td><label><?php echo __("Show service description","oct");?></label></td>
										<td>
											<div class="form-group">
												<label for="octabook_show_service_desc">
													<input <?php if($oct_settings->octabook_show_service_desc=='E') { echo ' checked="checked" '; }?> type="checkbox" id="octabook_show_service_desc" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","oct");?>" data-off="<?php echo __("Off","oct");?>" data-onstyle="success" data-offstyle="default" />
												</label>
											</div>
											<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("It will enable descriptions for service on front.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Show coupons input on checkout","oct");?></label></td>
										<td>
											<div class="form-group">
												<label for="octabook_show_coupons">
													<input <?php if($oct_settings->octabook_show_coupons=='E') { echo ' checked="checked" '; }?> type="checkbox" id="octabook_show_coupons" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","oct");?>" data-off="<?php echo __("Off","oct");?>" data-onstyle="success" data-offstyle="default" />
												</label>
											</div>
											<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("You can show/hide coupon input on checkout form.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Hide faded already booked time slots","oct");?></label></td>
										<td>
											<div class="form-group">
												<label for="octabook_hide_booked_slot">
													<input <?php if($oct_settings->octabook_hide_booked_slot=='E') { echo ' checked="checked" '; }?> type="checkbox" id="octabook_hide_booked_slot" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","oct");?>" data-off="<?php echo __("Off","oct");?>" data-onstyle="success" data-offstyle="default" />
												</label>
											</div>
											<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("With this you can hide the already booked slots just to hide your bookings from your Competitors.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Guest user checkout","oct");?></label></td>
										<td>
											<div class="form-group">
												<label for="octabook_guest_user_checkout">
													<input <?php if($oct_settings->octabook_guest_user_checkout=='E') { echo ' checked="checked" '; }?> type="checkbox" id="octabook_guest_user_checkout" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","oct");?>" data-off="<?php echo __("Off","oct");?>" data-onstyle="success" data-offstyle="default" />
												</label>
											</div>
											<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("With this feature you can allow a visitor to book appointment without registration.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<!--<tr>
										<td><label><?php //echo __("Booking(s) Cart","oct");?></label></td>
										<td>
											<div class="form-group">
												<label for="octabook_cart">
													<input <?php //if($oct_settings->octabook_cart=='E') { echo ' checked="checked" '; }?> type="checkbox" id="octabook_cart" data-toggle="toggle" data-size="small" data-on="<?php //echo __("On","oct");?>" data-off="<?php //echo __("Off","oct");?>" data-onstyle="success" data-offstyle="default" />
												</label>
											</div>
											<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php //echo __("With this feature you can Enable/Disable cart.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>-->
									<!--<tr>
										<td><label><?php //echo __("Max cart item limit","oct");?></label></td>
										<td>							
											<div class="form-group">
												<select class="selectpicker" data-width="70" data-size="10" id="octabook_max_cartitem_limit" name="octabook_max_cartitem_limit"  >
													<?php //for($citem=1;$citem<=50;$citem++){ ?>
														<option <?php //if($oct_settings->octabook_max_cartitem_limit==$citem) { echo ' selected  '; }?> value="<?php //echo $citem;?>"><?php //echo $citem;?></option>
														<?php //} ?>
													</select>
												</div>
												<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php //echo __("With this feature you can set limit for cart items.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
											</td>									
										</td>
									</tr>-->
									<!-- reviews section new -->
									<tr>
										<td><label><?php echo __("Reviews","oct");?></label></td>
										<td>							
											<div class="form-group">
												<label for="octabook_reviews_status">
													<input <?php if($oct_settings->octabook_reviews_status=='E') { echo ' checked="checked" '; }?> type="checkbox" id="octabook_reviews_status" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","oct");?>" data-off="<?php echo __("Off","oct");?>" data-onstyle="success" data-offstyle="default" />
												</label>
											</div>
												<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("With this feature you can Enable/Disable reviews for clients.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																			
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Auto Confirm Reviews","oct");?></label></td>
										<td>							
											<div class="form-group">
												<label for="octabook_auto_confirm_reviews">
													<input <?php if($oct_settings->octabook_auto_confirm_reviews=='E') { echo ' checked="checked" '; }?> type="checkbox" id="octabook_auto_confirm_reviews" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","oct");?>" data-off="<?php echo __("Off","oct");?>" data-onstyle="success" data-offstyle="default" />
												</label>
											</div>
												<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("With this feature you can Auto confirm clients reivews. No need to confirm manually.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																			
										</td>
									</tr>
									<tr>
									   <td><label><?php echo __("Frontend Custom CSS","oct");?></label></td>
									   <td>       
										<div class="form-group">
										 <label for="octabook_frontend_custom_css">
										  <textarea id="octabook_frontend_custom_css" class="form-control" cols="80" rows="6"><?php echo $oct_settings->octabook_frontend_custom_css; ?></textarea>
										 </label>
										</div>
										 <a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("This custom css will apply on frontend","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
												
									   </td>
									</tr>
									
									<!-- custom loader -->
									<?php 
									/* <tr>
									   <td><label><?php echo __("Custom Frontend Loader","oct");?></label></td>
									   <td>       
										<div class="form-group">
										 <label for="octabook_frontend_loader">
										 
										  <input type="file" id="octabook_frontend_loader" class="form-control octabook_frontend_loader_file" value ="<?php echo $oct_settings->octabook_frontend_loader; ?>" >
										  <input type="button" class="btn button" value="Upload" id="but_upload">
										 </label>
										</div>
										 <a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("This custom loader in frontend side","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
												
									   </td>
										<td>
									   <img height="100px" width="100px"src="<?php echo $plugin_url_for_ajax; ?>/assets/images/<?php echo $oct_settings->octabook_frontend_loader; ?>" />
									   
										</td>
									</tr> */
									?>
								 </tbody>
								<tfoot>
									<tr>
										<td></td>
										<td>
											<a href="javascript:void(0)" id="oct_save_appearance_settings" name="" class="btn btn-success" type="submit"><?php echo __("Save Setting","oct");?></a>
											<button type="reset" class="btn btn-default ml-30"><?php echo __("Default Setting","oct");?></button>
								
										</td>
									</tr>
								</tfoot>
							</table>
							
						</div>
					</div>
				</form>
			</div>
			<div class="tab-pane oct-toggle-abs" id="payment-setting">
				<form id="" method="post" type="" class="oct-payment-settings" >
					<div class="panel panel-default">
						<div class="panel-heading">
							<h1 class="panel-title"><?php echo __("Payment Gateways","oct");?></h1>
						</div>
						<div class="panel-body">
							<div id="accordion" class="panel-group">
								<div class="panel panel-default oct-all-payments-main">
									<div class="panel-heading">
										<h4 class="panel-title">
											<span><?php echo __("All Payment Gateways","oct");?></span>
											<div class="oct-enable-disable-right pull-right">
												<label class="toggle-large" for="octabook_payment_gateways_status">
													<input type="checkbox" <?php if($oct_settings->octabook_payment_gateways_status=='E'){ echo 'checked="checked"';} ?> class="oct-toggle-sh" id="octabook_payment_gateways_status" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
												</label>
											</div>
											
										</h4>
									</div>
									
									
									<div id="collapseOne" class="<?php if($oct_settings->octabook_payment_gateways_status=='D'){ echo 'hide-div';} ?>  panel-collapse  collapse_octabook_payment_gateways_status">
										<div class="panel-body">
										
										<div class="alert alert-danger" style="display: none;">
											<a href="#" class="close" data-dismiss="alert">&times;</a>
											<strong><?php echo __("Warning!","oct");?></strong><?php echo __("Currency you have selected ( currency option ) is not supported by Stipe.","oct");?> 
										</div>
											<div id="accordion" class="panel-group">
												<div class="panel panel-default oct-payment-methods">
													<div class="panel-heading">
														<h4 class="panel-title">
															<span><?php echo __("Pay in Person","oct");?></span>
															<div class="oct-enable-disable-right pull-right">
																<label class="toggle-large" for="octabook_locally_payment_status">
																	<input type="checkbox" <?php if($oct_settings->octabook_locally_payment_status=='E'){ echo 'checked="checked"';} ?> class="oct-toggle-sh" id="octabook_locally_payment_status" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
																</label>
															</div>
															
														</h4>
													</div>
												</div>
												
												<div class="panel panel-default oct-payment-methods">
													<div class="panel-heading">
														<h4 class="panel-title">
															<span><?php echo __("Paypal Express Checkout","oct");?>
															<img class="oct-img-payments oct-paypal" src="<?php echo $plugin_url_for_ajax; ?>/assets/images/paypal.png" />
															</span>
															<div class="oct-enable-disable-right pull-right">
																<label class="toggle-large" for="octabook_payment_method_Paypal">
																	<input <?php if($oct_settings->octabook_payment_method_Paypal=='E'){ echo 'checked="checked"';} ?> type="checkbox" class="oct-toggle-sh" id="octabook_payment_method_Paypal" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
																</label>
															</div>
															
														</h4>
													</div>
													<div id="collapseOne" class="<?php if($oct_settings->octabook_payment_method_Paypal=='D'){ echo 'hide-div';} ?> panel-collapse collapse_octabook_payment_method_Paypal">
														<div class="panel-body">
															<table class="form-inline oct-common-table">
																<tbody>
																	<tr>
																		<td><label><?php echo __("API Username","oct");?></label></td>
																		<td>
																			<div class="form-group oct-lgf">
																				<input type="text" class="form-control" id="octabook_paypal_api_username"  value="<?php echo $oct_settings->octabook_paypal_api_username ;?>" size="50" />
																			</div>	
																			<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Paypal API username can get easily from developer.paypal.com account.","oct");?>"><i class="fa fa-info-circle fa-lg lgf"></i></a>
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("API Password","oct");?></label></td>
																		<td>
																			<div class="form-group oct-lgf">
																				<input type="password" class="form-control" id="octabook_paypal_api_password" value="<?php echo $oct_settings->octabook_paypal_api_password ;?>" size="50" />
																			</div>	
																			<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Paypal API password can get easily from developer.paypal.com account.","oct");?>"><i class="fa fa-info-circle fa-lg lgf"></i></a>
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Signature","oct");?></label></td>
																		<td>
																			<div class="form-group oct-lgf">
																				<input type="text" class="form-control" id="octabook_paypal_api_signature" value="<?php echo $oct_settings->octabook_paypal_api_signature ;?>" size="50" />
																			</div>	
																			<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Paypal API Signature can get easily from developer.paypal.com account","oct");?>"><i class="fa fa-info-circle fa-lg lgf"></i></a>
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Paypal guest payment","oct");?></label></td>
																		<td>
																			<div class="form-group">
																				<label class="toggle-large" for="octabook_paypal_guest_checkout">
																					<input <?php if($oct_settings->octabook_paypal_guest_checkout=='E'){ echo 'checked="checked"';} ?> type="checkbox" class="oct-toggle-sh" id="octabook_paypal_guest_checkout" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
																				
																				</label>
																			</div>	
																			<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Let user pay through credit card without having Paypal account.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Test Mode","oct");?></label></td>
																		<td>
																			<div class="form-group">
																				<label class="toggle-large" for="octabook_paypal_testing_mode">
																					<input <?php if($oct_settings->octabook_paypal_testing_mode=='E'){ echo 'checked="checked"';} ?> type="checkbox" class="oct-toggle-sh" id="octabook_paypal_testing_mode" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
																				
																				</label>
																			</div>	
																			<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("You can enable Paypal test mode for sandbox account testing.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																		</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</div>
												</div>
												
												<div class="panel panel-default oct-payment-methods">
													<div class="panel-heading">
														<h4 class="panel-title">
															<span><?php echo __("Stripe Payment Form","oct");?>
															<img class="oct-img-payments oct-stripe" src="<?php echo $plugin_url_for_ajax; ?>/assets/images/stripe.jpg" />
															</span>
															<div class="oct-enable-disable-right pull-right">
																<label class="toggle-large" for=		"octabook_payment_method_Stripe">
																	<input <?php if($oct_settings->octabook_payment_method_Stripe=='E'){ echo 'checked="checked"';} ?> type="checkbox" class="oct-toggle-sh" id="octabook_payment_method_Stripe" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
																	
																	
																</label>
															</div>
														</h4>
													</div>
													<div id="collapseOne" class="<?php if($oct_settings->octabook_payment_method_Stripe=='D'){ echo 'hide-div';} ?> panel-collapse collapse_octabook_payment_method_Stripe">
														<div class="panel-body">
															<table class="form-inline oct-common-table">
																<tbody>
																	<tr>
																		<td><label><?php echo __("Secret Key","oct");?></label></td>
																		<td>
																			<div class="form-group">
																				<input type="text" class="form-control" id="octabook_stripe_secretKey" size="50" value="<?php echo $oct_settings->octabook_stripe_secretKey ;?>" />
																			</div>	
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Publishable Key","oct");?></label></td>
																		<td>
																			<div class="form-group">
																				<input type="password" class="form-control" id="octabook_stripe_publishableKey" value="<?php echo $oct_settings->octabook_stripe_publishableKey;?>" size="50" />
																			</div>	
																		</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</div>
												</div>
												<!-- Payumoney Start -->
												<div class="panel panel-default oct-payment-methods">
													<div class="panel-heading">
														<h4 class="panel-title">
															<span><?php echo __("Payumoney Payment Form","oct");?>
															<img class="oct-img-payments oct-stripe" src="<?php echo $plugin_url_for_ajax; ?>/assets/images/payumoney.jpg" />
															</span>
															<div class="oct-enable-disable-right pull-right">
																<label class="toggle-large" for=		"octabook_payment_method_payumoney">
																	<input <?php if($oct_settings->octabook_payment_method_Payumoney=='E'){ echo 'checked="checked"';} ?> type="checkbox" class="oct-toggle-sh" id="octabook_payment_method_Payumoney" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
																</label>
															</div>
														</h4>
													</div>
													<div id="collapseOne" class="<?php if($oct_settings->octabook_payment_method_Payumoney=='D'){ echo 'hide-div';} ?> panel-collapse collapse_octabook_payment_method_Payumoney">
														<div class="panel-body">
															<table class="form-inline oct-common-table">
																<tbody>
																	<tr>
																		<td><label><?php echo __("Merchant Key","oct");?></label></td>
																		<td>
																			<div class="form-group">
																				<input type="text" class="form-control" id="octabook_payumoney_merchantkey" size="50" value="<?php echo $oct_settings->octabook_payumoney_merchantkey ;?>" />
																			</div>	
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Salt Key","oct");?></label></td>
																		<td>
																			<div class="form-group">
																				<input type="password" class="form-control" id="octabook_payumoney_saltkey" value="<?php echo $oct_settings->octabook_payumoney_saltkey;?>" size="50" />
																			</div>	
																		</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</div>
												</div>
												<!-- Payumoney End -->
												<!-- Paytm Start -->
												<div class="panel panel-default oct-payment-methods">
													<div class="panel-heading">
														<h4 class="panel-title">
															<span><?php echo __("Paytm Payment Form","oct");?>
															<img class="oct-img-payments oct-paytm" src="<?php echo $plugin_url_for_ajax; ?>/assets/images/paytm.png" />
															</span>
															<div class="oct-enable-disable-right pull-right">
																<label class="toggle-large" for=		"octabook_payment_method_Paytm">
																	<input <?php if($oct_settings->octabook_payment_method_Paytm=='E'){ echo 'checked="checked"';} ?> type="checkbox" class="oct-toggle-sh" id="octabook_payment_method_Paytm" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
																</label>
															</div>
														</h4>
													</div>
													<div id="collapseOne" class="<?php if($oct_settings->octabook_payment_method_Paytm=='D'){ echo 'hide-div';} ?> panel-collapse collapse_octabook_payment_method_Paytm">
														<div class="panel-body">
															<table class="form-inline oct-common-table">
																<tbody>
																	<tr>
																		<td><label><?php echo __("Merchant Key","oct");?></label></td>
																		<td>
																			<div class="form-group">
																				<input type="text" class="form-control" id="octabook_paytm_merchantkey" size="50" value="<?php echo $oct_settings->octabook_paytm_merchantkey ;?>" />
																			</div>	
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Merchant Id","oct");?></label></td>
																		<td>
																			<div class="form-group">
																				<input type="text" class="form-control" id="octabook_paytm_merchantid" value="<?php echo $oct_settings->octabook_paytm_merchantid;?>" size="50" />
																			</div>	
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Merchant Website URL","oct");?></label></td>
																		<td>
																			<div class="form-group">
																				<input type="text" class="form-control" id="octabook_paytm_website" value="<?php echo $oct_settings->octabook_paytm_website;?>" size="50" />
																			</div>	
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Merchant Channel Id","oct");?></label></td>
																		<td>
																			<div class="form-group">
																				<input type="text" class="form-control" id="octabook_paytm_channelid" value="<?php echo $oct_settings->octabook_paytm_channelid;?>" size="50" />
																			</div>	
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Merchant Industry Type","oct");?></label></td>
																		<td>
																			<div class="form-group">
																				<input type="text" class="form-control" id="octabook_paytm_industryid" value="<?php echo $oct_settings->octabook_paytm_industryid;?>" size="50" />
																			</div>	
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Test Mode","oct");?></label></td>
																		<td>
																			<div class="form-group">
																				<label class="toggle-large" for="octabook_paytm_testing_mode">
																					<input <?php if($oct_settings->octabook_paytm_testing_mode=='E'){ echo 'checked="checked"';} ?> type="checkbox" class="oct-toggle-sh" id="octabook_paytm_testing_mode" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
																				</label>
																			</div>	
																			<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("You can enable paytm test mode for sandbox account testing.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																		</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</div>
												</div>
												<!-- Paytm End -->
												<?php /* <div class="panel panel-default oct-payment-methods">
													<div class="panel-heading">
														<h4 class="panel-title">
															<span><?php echo __("2Checkout Payment Form","oct");?>
															<img class="oct-img-payments oct-2checkout" src="<?php echo $plugin_url_for_ajax; ?>/assets/images/2checkout.png" />
															</span>
															<div class="oct-enable-disable-right pull-right">
																<label class="toggle-large" for=		"octabook_payment_method_2Checkout">
																	<input <?php if($oct_settings->octabook_payment_method_2Checkout=='E'){ echo 'checked="checked"';} ?> type="checkbox" class="oct-toggle-sh" id="octabook_payment_method_2Checkout" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
																	
																	
																</label>
															</div>
														</h4>
													</div>
													
													<div id="collapseOne" class="<?php if($oct_settings->octabook_payment_method_2Checkout=='D'){ echo 'hide-div';} ?> panel-collapse collapse_octabook_payment_method_2Checkout">
														<div class="panel-body">
															<table class="form-inline oct-common-table">
																<tbody>
																	<tr>
																		<td><label><?php echo __("Publishable Key","oct");?></label></td>
																		<td>
																			<div class="form-group">
																				<input type="text" class="form-control" id="octabook_2checkout_publishablekey" size="50" value="<?php echo $oct_settings->octabook_2checkout_publishablekey ;?>" />
																			</div>	
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Private Key","oct");?></label></td>
																		<td>
																			<div class="form-group">
																				<input type="password" class="form-control" id="octabook_2checkout_privateKey" value="<?php echo $oct_settings->octabook_2checkout_privateKey;?>" size="50" />
																			</div>	
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Seller ID","oct");?></label></td>
																		<td>
																			<div class="form-group">
																				<input type="text" class="form-control" id="octabook_2checkout_sellerid" size="50" value="<?php echo $oct_settings->octabook_2checkout_sellerid ;?>" />
																			</div>	
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Test Mode","oct");?></label></td>
																		<td>
																			<div class="form-group">
																				<label class="toggle-large" for="octabook_paypal_testing_mode">
																					<input <?php if($oct_settings->octabook_2checkout_testing_mode=='E'){ echo 'checked="checked"';} ?> type="checkbox" class="oct-toggle-sh" id="octabook_2checkout_testing_mode" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
																				
																				</label>
																			</div>	
																		</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</div>
												</div>
												<div class="panel panel-default oct-payment-methods">
													<div class="panel-heading">
														<h4 class="panel-title">
															<span><?php echo __("Authorize.Net Payment Form","oct");?>
															<img class="oct-img-payments oct-authorize" src="<?php echo $plugin_url_for_ajax; ?>/assets/images/authorize-net.png" />
															</span>
															<div class="oct-enable-disable-right pull-right">
																<label class="toggle-large" for=		"octabook_payment_method_Authorizenet">
																	<input <?php if($oct_settings->octabook_payment_method_Authorizenet=='E'){ echo 'checked="checked"';} ?> type="checkbox" class="oct-toggle-sh" id="octabook_payment_method_Authorizenet" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
																	
																	
																</label>
															</div>
														</h4>
													</div>
													<div id="collapseOne" class="<?php if($oct_settings->octabook_payment_method_Authorizenet=='D'){ echo 'hide-div';} ?> panel-collapse collapse_octabook_payment_method_Authorizenet">
														<div class="panel-body">
															<table class="form-inline oct-common-table">
																<tbody>
																	<tr>
																		<td><label><?php echo __("Api Login Id","oct");?></label></td>
																		<td>
																			<div class="form-group">
																				<input type="text" class="form-control" id="octabook_authorizenet_api_loginid" size="50" value="<?php echo $oct_settings->octabook_authorizenet_api_loginid ;?>" />
																			</div>	
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Transaction Key","oct");?></label></td>
																		<td>
																			<div class="form-group">
																				<input type="text" class="form-control" id="octabook_authorizenet_transaction_key" value="<?php echo $oct_settings->octabook_authorizenet_transaction_key;?>" size="50" />
																			</div>	
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Sandbox Mode","oct");?></label></td>
																		<td>
																			<div class="form-group">
																				<label class="toggle-large" for="octabook_authorizenet_testing_mode">
																					<input <?php if($oct_settings->octabook_authorizenet_testing_mode=='E'){ echo 'checked="checked"';} ?> type="checkbox" class="oct-toggle-sh" id="octabook_authorizenet_testing_mode" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
																				
																				</label>
																			</div>	
																			<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("You can enable authorizenet test mode for sandbox account testing.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																		</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</div>
												</div> */ ?>
												
											</div>
										</div>
									</div>
								</div>
								<a id="oct_save_payment_settings" class="btn btn-success oct-btn-width mt-20 ml-10" type="submit"><?php echo __("Save Setting","oct");?></a>
								
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="tab-pane oct-toggle-abs" id="email-setting">
				<form id="" method="post" type="" class="oct-email-settings" >
					<div class="panel panel-default">
						<div class="panel-heading">
							<h1 class="panel-title"><?php echo __("Email Settings","oct");?></h1>
						</div>
						<div class="panel-body">
							
						<div class="panel-body">
							<table class="form-inline oct-common-table" >
								<tbody>
									<tr>
										<td><label><?php echo __("Admin Email Notifications","oct");?></label></td>
										<td>
											<div class="form-group">
												<label class="toggle-large" for="octabook_admin_email_notification_status">
													<input <?php if($oct_settings->octabook_admin_email_notification_status=='E'){ echo 'checked="checked"';} ?> type="checkbox" id="octabook_admin_email_notification_status" class="oct-toggle-sh"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
												</label>
											</div>
										</td>
									</tr>

									<tr>
										<td><label><?php echo __("Manager Email Notifications","oct");?></label></td>
										<td>
											<div class="form-group">
												<label class="toggle-large" for="octabook_manager_email_notification_status">
													<input  <?php if($oct_settings->octabook_manager_email_notification_status=='E'){ echo 'checked="checked"';} ?> type="checkbox" id="octabook_manager_email_notification_status" class="oct-toggle-sh"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
												</label>
											</div>
										</td>
									</tr>
									
									<tr>
										<td><label><?php echo __("Staff Member Email Notifications","oct");?></label></td>
										<td>
											<div class="form-group">
												<label class="toggle-large" for="octabook_service_provider_email_notification_status">
													<input <?php if($oct_settings->octabook_service_provider_email_notification_status=='E'){ echo 'checked="checked"';} ?> type="checkbox" id="octabook_service_provider_email_notification_status" class="oct-toggle-sh"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
												</label>
											</div>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Client Email Notifications","oct");?></label></td>
										<td>
											<div class="form-group">
												<label class="toggle-large" for="octabook_client_email_notification_status">
													<input <?php if($oct_settings->octabook_client_email_notification_status=='E'){ echo 'checked="checked"';} ?> type="checkbox" id="octabook_client_email_notification_status" class="oct-toggle-sh"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
												</label>
											</div>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Sender Name","oct");?></label></td>
										<td>
											<div class="form-group">
												<input type="text" value="<?php echo $oct_settings->octabook_email_sender_name;?>" class="form-control w-300" id="octabook_email_sender_name" />
											</div>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Sender Email Address (octabook Admin Email)","oct");?></label></td>
										<td>
											<div class="form-group">
												<input type="email" class="form-control w-300" id="octabook_email_sender_address" value="<?php echo $oct_settings->octabook_email_sender_address;?>" placeholder="admin@example.com" />
											</div>
										</td>
									</tr>
									<tr><td class="np"><hr /></td><td class="np"><hr /></td></tr>
									<td><label><?php echo __("Appointment Reminder Buffer","oct");?></label></td>
										<td>
											<div class="form-group">
												<select id="octabook_email_reminder_buffer" class="selectpicker" data-size="5" data-width="auto" >
													<option value=""><?php echo __("Set Email & SMS Reminder Buffer","oct");?></option>
													<option <?php if($oct_settings->octabook_email_reminder_buffer=='60'){ echo 'selected';} ?> value="60"><?php echo __("1 Hrs","oct");?></option>
													<option <?php if($oct_settings->octabook_email_reminder_buffer=='120'){ echo 'selected';} ?> value="120"><?php echo __("2 Hrs","oct");?></option>
													<option <?php if($oct_settings->octabook_email_reminder_buffer=='180'){ echo 'selected';} ?> value="180"><?php echo __("3 Hrs","oct");?></option>
													<option <?php if($oct_settings->octabook_email_reminder_buffer=='240'){ echo 'selected';} ?> value="240"><?php echo __("4 Hrs","oct");?></option>
													<option <?php if($oct_settings->octabook_email_reminder_buffer=='300'){ echo 'selected';} ?> value="300"><?php echo __("5 Hrs","oct");?></option>
													<option <?php if($oct_settings->octabook_email_reminder_buffer=='360'){ echo 'selected';} ?> value="360"><?php echo __("6 Hrs","oct");?></option>
													<option <?php if($oct_settings->octabook_email_reminder_buffer=='420'){ echo 'selected';} ?> value="420"><?php echo __("7 Hrs","oct");?></option>
													<option <?php if($oct_settings->octabook_email_reminder_buffer=='480'){ echo 'selected';} ?> value="480"><?php echo __("8 Hrs","oct");?></option>
													<option <?php if($oct_settings->octabook_email_reminder_buffer=='1440'){ echo 'selected';} ?> value="1440"><?php echo __("1 Day","oct");?></option>
												</select>
											</div>	
										</td>
									</tr>
									
									
								</tbody>
								<tfoot>
									<tr>
										<td></td>
										<td>
											<a href="javascript:void(0)" id="oct_save_email_settings" name="" class="btn btn-success" type="submit"><?php echo __("Save Setting","oct");?></a>
										</td>
									</tr>
								</tfoot>
							</table>
							
						</div>
							
						</div>
					</div>
				</form>
			</div>
			<div class="tab-pane oct-toggle-abs" id="email-template">
				<div class="panel panel-default wf-100">
					<div class="panel-heading">
						<h1 class="panel-title"><?php echo __("Email Template Settings","oct");?></h1>
					</div>
					<!-- Client email templates -->
					<ul class="nav nav-tabs nav-justified">
						<li class="active"><a data-toggle="tab" href="#client-email-template"><?php echo __("Client Email Templates","oct");?></a></li>
						<li><a data-toggle="tab" href="#service-provider-email-template"><?php echo __("Service Provider Email Template","oct");?></a></li>
						<li><a data-toggle="tab" href="#admin-manager-email-template"><?php echo __("Admin/Manager Email Template","oct");?></a></li>
						
					</ul>
					<div class="tab-content">
						<div id="client-email-template" class="tab-pane fade in active">
							<h3><?php echo __("Client Email Templates","oct");?></h3>
								<div id="accordion" class="panel-group">
									<?php $oct_email_templates->user_type='C';
									$AM_templates = $oct_email_templates->readall_by_usertype();
									foreach($AM_templates as $AM_template){ ?>
									
									<div class="panel panel-default oct-email-panel">
										<div class="panel-heading">
											<h4 class="panel-title">
												<div class="oct-col11">
													<div class="oct-yes-no-email-right pull-left">
														<label for="email_template_status<?php echo $AM_template->id;?>">
															<input <?php if($AM_template->email_template_status=='e'){echo "checked='checked'";} ?> class="oct_update_emailstatus" type="checkbox" id="email_template_status<?php echo $AM_template->id;?>" data-eid="<?php echo $AM_template->id;?>" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","oct");?>" data-off="<?php echo __("Off","oct");?>" data-onstyle="success" data-offstyle="default" />
															
														</label>
													</div>	
													<span id="email_subject_label<?php echo $AM_template->id;?>" class="oct-template-name"><?php echo $AM_template->email_subject;?></span>
												</div>	
												<div class="pull-right oct-col1">
													<div class="pull-right">
														<div class="oct-show-hide pull-right">
															<input type="checkbox" name="oct-show-hide" class="oct-show-hide-checkbox" id="<?php echo $AM_template->id;?>" >
															<label class="oct-show-hide-label" for="<?php echo $AM_template->id;?>"></label>
														</div>
													</div>
												</div>
											</h4>
										</div>
										<div class="panel-collapse collapse emailtemplatedetail emaildetail_<?php echo $AM_template->id;?>">
											<div class="panel-body">
												<div class="oct-email-temp-collapse-div col-md-12 col-lg-12 col-xs-12 np">
													<form id="" method="post" type="" class="slide-toggle" >
														<div class="col-md-8 col-sm-8 col-xs-12 form-group">
															<label><?php echo __("Email Subject","oct");?></label>
															<input type="text" class="form-control" name="email_subject<?php echo $AM_template->id;?>" value="<?php echo $AM_template->email_subject;?>" />
															<label><?php echo __("Email Content","oct");?></label>
															<?php
															if($AM_template->email_message!=''){
																
															$content=stripslashes_deep($AM_template->email_message);
															}else{
															$content=stripslashes_deep($AM_template->default_message);
															}
															$editorName=  'email_message'.$AM_template->id;
															$editorId ='email_editor'.$AM_template->id;
															wp_editor($content,$editorId, array('textarea_name'=>$editorName,'media_buttons'=>true, 'teeny'=>false, 'tinymce' => false,'editor_class'=>'ak_wp_editor','wpautop' => true,'tabindex' => '','tabfocus_elements' => ':prev,:next','dfw' => false,'quicktags' => true)); ?>
															
															<a data-eid="<?php echo $AM_template->id;?>" class="btn btn-success oct-btn-width pull-left cb ml-15 mt-20 oct_save_emailtemplate" type="submit"><?php echo __("Save Template","oct");?></a>
															
														</div>
														<div class="col-md-4 col-sm-4 col-xs-12">
															<div class="oct-email-content-tags">
																<b><?php echo __("Tags","oct");?> </b><br />
																<?php 
																if($AM_template->email_template_name=='AC'){
																	$email_tags = $requestemail_template_tags;
																}else{
																	$email_tags = $email_template_tags;
																}
																
																foreach($email_tags as $tags){
																		
																		echo "<a data-eid='".$AM_template->id."' class='tags' data-value='".$tags."'>".$tags."</a><br/>";
																	} ?>
																<br />
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
						<div id="service-provider-email-template" class="tab-pane fade">
							<h3><?php echo __("Service Provider Email Template","oct");?></h3>
							<div id="accordion" class="panel-group">
									<?php $oct_email_templates->user_type='SP';
									$AM_templates = $oct_email_templates->readall_by_usertype();
									foreach($AM_templates as $AM_template){ ?>
									
									<div class="panel panel-default oct-email-panel">
										<div class="panel-heading">
											<h4 class="panel-title">
												<div class="oct-col11">
													<div class="oct-yes-no-email-right pull-left">
														<label for="email_template_status<?php echo $AM_template->id;?>">
															<input <?php if($AM_template->email_template_status=='e'){echo "checked='checked'";} ?> class="oct_update_emailstatus" type="checkbox" id="email_template_status<?php echo $AM_template->id;?>" data-eid="<?php echo $AM_template->id;?>" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","oct");?>" data-off="<?php echo __("Off","oct");?>" data-onstyle="success" data-offstyle="default" />
														</label>
													</div>
												
													<span id="email_subject_label<?php echo $AM_template->id;?>" class="oct-template-name"><?php echo $AM_template->email_subject;?></span>
														
												</div>	
												<div class="pull-right oct-col1">
													<div class="pull-right">
														<div class="oct-show-hide pull-right">
															<input type="checkbox" name="oct-show-hide" class="oct-show-hide-checkbox" id="<?php echo $AM_template->id;?>" ><!--Added Serivce Id-->
															<label class="oct-show-hide-label" for="<?php echo $AM_template->id;?>"></label>
														</div>
													</div>
												</div>
											</h4>
										</div>
										<div class="panel-collapse collapse emailtemplatedetail emaildetail_<?php echo $AM_template->id;?>">
											<div class="panel-body">
												<div class="oct-email-temp-collapse-div col-md-12 col-lg-12 col-xs-12 np">
													<form id="" method="post" type="" class="slide-toggle" >
														<div class="col-md-8 col-sm-8 col-xs-12 form-group">
															<label><?php echo __("Email Subject","oct");?></label>
															<input type="text" class="form-control" name="email_subject<?php echo $AM_template->id;?>" value="<?php echo $AM_template->email_subject;?>" />
															<label><?php echo __("Email Content","oct");?></label>
															<?php
															if($AM_template->email_message!=''){
															$content=stripslashes_deep($AM_template->email_message);
															}else{
															$content=stripslashes_deep($AM_template->default_message);
															}
															$editorName=  'email_message'.$AM_template->id;
															$editorId ='email_editor'.$AM_template->id;
															wp_editor($content,$editorId, array('textarea_name'=>$editorName,'media_buttons'=>true, 'teeny'=>false, 'tinymce' => false,'editor_class'=>'ak_wp_editor','wpautop' => true,'tabindex' => '','tabfocus_elements' => ':prev,:next','dfw' => false,'quicktags' => true)); ?>
															
															
															<a data-eid="<?php echo $AM_template->id;?>" class="btn btn-success oct-btn-width pull-left cb ml-15 mt-20 oct_save_emailtemplate" type="submit"><?php echo __("Save Template","oct");?></a>
														</div>
														<div class="col-md-4 col-sm-4 col-xs-12">
															<div class="oct-email-content-tags">
																<b><?php echo __("Tags","oct");?> </b><br />
																<?php 
																	if($AM_template->email_template_name=='AS'){
																	$email_tags = $requestemail_template_tags;
																	}else{
																		$email_tags = $email_template_tags;
																	}
																
																	foreach($email_tags as $tags){
																		
																		echo "<a data-eid='".$AM_template->id."' class='tags' data-value='".$tags."'>".$tags."</a><br/>";
																	} ?>
																<br />
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
						
						<div id="admin-manager-email-template" class="tab-pane fade">
							<h3><?php echo __("Admin/Manager Provider Email Template","oct");?></h3>
							<div id="accordion" class="panel-group">
									<?php $oct_email_templates->user_type='AM';
									$AM_templates = $oct_email_templates->readall_by_usertype();
									foreach($AM_templates as $AM_template){ ?>
									
									<div class="panel panel-default oct-email-panel">
										<div class="panel-heading">
											<h4 class="panel-title">
												<div class="oct-col11">
													<div class="oct-yes-no-email-right pull-left">
														<label for="email_template_status<?php echo $AM_template->id;?>">
															<input <?php if($AM_template->email_template_status=='e'){echo "checked='checked'";} ?> class="oct_update_emailstatus" type="checkbox" id="email_template_status<?php echo $AM_template->id;?>" data-eid="<?php echo $AM_template->id;?>" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","oct");?>" data-off="<?php echo __("Off","oct");?>" data-onstyle="success" data-offstyle="default" />
														</label>
													</div>
													
													<span id="email_subject_label<?php echo $AM_template->id;?>" class="oct-template-name"><?php echo $AM_template->email_subject;?></span>
														
												</div>	
												<div class="pull-right oct-col1">
													<div class="pull-right">
														<div class="oct-show-hide pull-right">
															<input type="checkbox" name="oct-show-hide" class="oct-show-hide-checkbox" id="<?php echo $AM_template->id;?>" ><!--Added Serivce Id-->
															<label class="oct-show-hide-label" for="<?php echo $AM_template->id;?>"></label>
														</div>
													</div>
												</div>
											</h4>
										</div>
										<div class="panel-collapse collapse emailtemplatedetail emaildetail_<?php echo $AM_template->id;?>">
											<div class="panel-body">
												<div class="oct-email-temp-collapse-div col-md-12 col-lg-12 col-xs-12 np">
													<form id="" method="post" type="" class="slide-toggle" >
														<div class="col-md-8 col-sm-8 col-xs-12 form-group">
															<label><?php echo __("Email Subject","oct");?></label>
															<input type="text" class="form-control" name="email_subject<?php echo $AM_template->id;?>" value="<?php echo $AM_template->email_subject;?>" />
															<label><?php echo __("Email Content","oct");?></label>
															<?php
															if($AM_template->email_message!=''){
															$content=stripslashes_deep($AM_template->email_message);
															}else{
															$content=stripslashes_deep($AM_template->default_message);
															}
															$editorName=  'email_message'.$AM_template->id;
															$editorId ='email_editor'.$AM_template->id;

															wp_editor($content,$editorId, array('textarea_name'=>$editorName,'media_buttons'=>true, 'teeny'=>false, 'tinymce' => false,'editor_class'=>'ak_wp_editor','wpautop' => true,'tabindex' => '','tabfocus_elements' => ':prev,:next','dfw' => false,'quicktags' => true)); ?>
															
															<a data-eid="<?php echo $AM_template->id;?>" class="btn btn-success oct-btn-width pull-left cb ml-15 mt-20 oct_save_emailtemplate" type="submit"><?php echo __("Save Template","oct");?></a>
														</div>
														<div class="col-md-4 col-sm-4 col-xs-12">
															<div class="oct-email-content-tags">
																<b><?php echo __("Tags","oct");?> </b><br />
																<?php 
																	if($AM_template->email_template_name=='AA'){
																	$email_tags = $requestemail_template_tags;
																	}else{
																		$email_tags = $email_template_tags;
																	}
																	foreach($email_tags as $tags){
																		
																		echo "<a data-eid='".$AM_template->id."' class='tags' data-value='".$tags."'>".$tags."</a><br/>";
																	} ?>
																<br />
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
				</div>
			</div>
			<!--twilio --> 
			<div class="tab-pane oct-toggle-abs" id="sms-reminder">
				<form id="" method="post" type="" class="oct-sms-reminder" >
					<div class="panel panel-default">
						<div class="panel-heading">
							<h1 class="panel-title"><?php echo __("SMS Reminder","oct");?></h1>
						</div>
						<div class="panel-body np">
							<div id="accordion" class="panel-group oct-all-sms-main">
								<div class="panel panel-default oct-sms-gateway nb">
									<div class="panel-heading">
										<h4 class="panel-title">
											<span><?php echo __("SMS Service","oct");?></span>
											<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="It will send sms to service provider and client for appointment booking"><i class="fa fa-info-circle fa-lg"></i></a>
											<div class="oct-enable-disable-right pull-right">
												<label class="toggle-large" for="octabook_sms_reminder_status">
													<input <?php if($oct_settings->octabook_sms_reminder_status=='E'){echo "checked='checked'";} ?> type="checkbox" class="oct-toggle-sh" name="octabook_sms_reminder_status" id="octabook_sms_reminder_status"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
												</label>
											</div>
											
										</h4>
									</div>
									
									<div id="collapseOne" class="panel-collapse collapse collapse_octabook_sms_reminder_status hide-div" <?php if($oct_settings->octabook_sms_reminder_status=='E'){ echo 'style="display: block;"'; } ?>>
										<div class="panel-body">
											<div id="accordion" class="panel-group">
												<div class="panel panel-default oct-sms-gateway nb">
													<div class="panel-heading">
														<h4 class="panel-title">
															<span><?php echo __("Twilio SMS Gateway","oct");?><img class="oct-sms-gateway-img" src="<?php echo $plugin_url_for_ajax; ?>/assets/images/twilio-logo.png" />
															</span>
															<div class="oct-enable-disable-right pull-right">
																<label class="toggle-large" for="octabook_sms_noti_twilio">
																	<input <?php if($oct_settings->octabook_sms_noti_twilio=='E'){echo "checked='checked'";} ?> type="checkbox" class="oct-toggle-sh" id="octabook_sms_noti_twilio" name="octabook_sms_noti_twilio"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
																</label>
															</div>
														</h4>
													</div>
										
												<div id="collapseOne" class="panel-collapse collapse_octabook_sms_noti_twilio <?php if($oct_settings->octabook_sms_noti_twilio=='D'){ echo 'hide-div';} ?>">
													<div class="panel-body padding-15">
													<table class="form-inline table oct-common-table table-hover table-bordered table-striped">
														<tr><th colspan="3"><?php echo __("Twilio Account Settings","oct");?></th></tr>
														<tbody>
															<tr>
																<td><label><?php echo __("Account SID","oct");?></label></td>
																<td colspan="2">
																	<div class="form-group oct-lgf">
																		<input type="text" class="form-control" name="octabook_twilio_sid" id="octabook_twilio_sid" size="70" value="<?php echo $oct_settings->octabook_twilio_sid;?>"/>
																	</div>	
																	<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Available from within your Twilio Account.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																</td>
															</tr>
															<tr>
																<td><label><?php echo __("Auth Token","oct");?></label></td>
																<td colspan="2">
																	<div class="form-group oct-lgf">
																		<input type="password" class="form-control" name="octabook_twilio_auth_token"
																		id="octabook_twilio_auth_token" size="70" value="<?php echo $oct_settings->octabook_twilio_auth_token;?>" />
																	</div>	
																	<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Available from within your Twilio Account.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																</td>
															</tr>
															<tr>
																<td><label><?php echo __("Twilio Sender Number","oct");?></label></td>
																<td colspan="2">
																	<div class="form-group oct-lgf">
																		<input type="text" class="form-control" name="octabook_twilio_number" id="octabook_twilio_number" size="70" value="<?php echo $oct_settings->octabook_twilio_number;?>" />
																	</div>	
																	<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Must be a valid number associated with your Twilio account.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																</td>
															</tr>
															<tr>
																<td id="hr"></td><td id="hr"></td><td id="hr"></td>
															</tr>
														</tbody>
														
														<tbody>
														
														<th colspan="3"><?php echo __("Twilio SMS Settings","oct");?></th>
															<tr>
																<td><label><?php echo __("Send SMS to Service Provider","oct");?></label></td>
																<td colspan="2">
																	<div class="form-group">
																		<label class="toggle-large" for="octabook_twilio_service_provider_sms_notification_status">
																			<input <?php if($oct_settings->octabook_twilio_service_provider_sms_notification_status=='E'){echo "checked='checked'";} ?> type="checkbox" name="octabook_twilio_service_provider_sms_notification_status" id="octabook_twilio_service_provider_sms_notification_status"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
																		
																		</label>
																	</div>	
																	<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Enable or Disable, Send SMS to Service provider for appointment booking info.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																</td>
															</tr>
															<tr>
																<td><label><?php echo __("Send SMS to Client","oct");?></label></td>
																<td colspan="2">
																	<div class="form-group">
																		<label class="toggle-large" for="octabook_twilio_client_sms_notification_status">
																			<input <?php if($oct_settings->octabook_twilio_client_sms_notification_status=='E'){echo "checked='checked'";} ?> type="checkbox" name="octabook_twilio_client_sms_notification_status" id="octabook_twilio_client_sms_notification_status" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
																		</label>
																	</div>	
																	<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Enable or Disable, Send SMS to client for appointment booking info.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																</td>
															</tr>
															<tr>
																<td><label><?php echo __("Send SMS to Admin","oct");?></label></td>
																<td colspan="2">
																	<div class="form-group">
																		<label class="toggle-large" for="octabook_twilio_admin_sms_notification_status">
																			<input <?php if($oct_settings->octabook_twilio_admin_sms_notification_status=='E'){echo "checked='checked'";} ?> type="checkbox" name="octabook_twilio_admin_sms_notification_status" id="octabook_twilio_admin_sms_notification_status" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
																		
																		</label>
																	</div>	
																	<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Enable or Disable, Send SMS to Admin for appointment booking info.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																</td>
															</tr>
															<tr>
																<td><label><?php echo __("Admin Phone Number","oct");?></label></td>
																<td colspan="2">
																	<div class="input-group octabook_twillio_cd">
																		 <!--<span class="input-group-addon"><span class="">+1</span></span>-->
																		 <input type="text" class="form-control" name="octabook_twilio_admin_phone_no" id="octabook_twilio_admin_phone_no" value="<?php echo $oct_settings->octabook_twilio_admin_phone_no;?>" />
																		 <input type="hidden" id="octabook_twilio_ccode_alph" value="<?php echo $oct_settings->octabook_twilio_ccode_alph;?>" />
																		 <input type="hidden" id="octabook_twilio_ccode" value="<?php echo $oct_settings->octabook_twilio_ccode;?>" />
																		 
																	</div>	
																</td>
															</tr>
															<tr>
																<td id="hr"></td><td id="hr"></td><td id="hr"></td>
															</tr>
														</tbody>
													</table>
												</div>	
												</div>	
												</div>
												
												<!-- Plivo Settings -->
												<div class="panel panel-default oct-sms-gateway">
													<div class="panel-heading">
														<h4 class="panel-title">
															<span><?php echo __("Plivo SMS Gateway","oct");?><img class="oct-sms-gateway-img" src="<?php echo $plugin_url_for_ajax; ?>/assets/images/plivo-logo.png" />
															</span>
															<div class="oct-enable-disable-right pull-right">
																<label class="toggle-large" for="octabook_sms_noti_plivo">
																	<input <?php if($oct_settings->octabook_sms_noti_plivo=='E'){echo "checked='checked'";} ?> type="checkbox" class="oct-toggle-sh" id="octabook_sms_noti_plivo" name="octabook_sms_noti_plivo"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
																</label>
															</div>
														</h4>
													</div>
										
												<div id="collapseOne" class="panel-collapse collapse_octabook_sms_noti_plivo <?php if($oct_settings->octabook_sms_noti_plivo=='D'){ echo 'hide-div';} ?> ">
													<div class="panel-body padding-15">
														<table class="form-inline table oct-common-table table-hover table-bordered table-striped">
															<tr><th colspan="3"><?php echo __("Plivo Account Settings","oct");?></th></tr>
															<tbody>
																<tr>
																	<td><label><?php echo __("Account SID","oct");?></label></td>
																	<td colspan="2">
																		<div class="form-group oct-lgf">
																			<input type="text" class="form-control" name="octabook_plivo_sid" id="octabook_plivo_sid" size="70" value="<?php echo $oct_settings->octabook_plivo_sid;?>"/>
																		</div>	
																		<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Available from within your Plivo Account.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td><label><?php echo __("Auth Token","oct");?></label></td>
																	<td colspan="2">
																		<div class="form-group oct-lgf">
																			<input type="password" class="form-control" name="octabook_plivo_auth_token"
																			id="octabook_plivo_auth_token" size="70" value="<?php echo $oct_settings->octabook_plivo_auth_token; ?>" />
																		</div>	
																		<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Available from within your Plivo Account.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td><label><?php echo __("Plivo Sender Number","oct");?></label></td>
																	<td colspan="2">
																		<div class="form-group oct-lgf">
																			<input type="text" class="form-control" name="octabook_plivo_number" id="octabook_plivo_number" size="70" value="<?php echo $oct_settings->octabook_plivo_number;?>" />
																		</div>	
																		<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Must be a valid number associated with your Plivo account.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td id="hr"></td><td id="hr"></td><td id="hr"></td>
																</tr>
															</tbody>
															
															<tbody>
															
															<th colspan="3"><?php echo __("Plivo SMS Settings","oct");?></th>
																<tr>
																	<td><label><?php echo __("Send SMS to Service Provider","oct");?></label></td>
																	<td colspan="2">
																		<div class="form-group">
																			<label class="toggle-large" for="octabook_plivo_service_provider_sms_notification_status">
																				<input <?php if($oct_settings->octabook_plivo_service_provider_sms_notification_status=='E'){echo "checked='checked'";} ?> type="checkbox" name="octabook_plivo_service_provider_sms_notification_status" id="octabook_plivo_service_provider_sms_notification_status"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
																			</label>
																		</div>	
																		<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Enable or Disable, Send SMS to Service provider for appointment booking info.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td><label><?php echo __("Send SMS to Client","oct");?></label></td>
																	<td colspan="2">
																		<div class="form-group">
																			<label class="toggle-large" for="octabook_plivo_client_sms_notification_status">
																				<input <?php if($oct_settings->octabook_plivo_client_sms_notification_status=='E'){echo "checked='checked'";} ?> type="checkbox" name="octabook_plivo_client_sms_notification_status" id="octabook_plivo_client_sms_notification_status" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
																			</label>
																		</div>	
																		<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Enable or Disable, Send SMS to client for appointment booking info.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td><label><?php echo __("Send SMS to Admin","oct");?></label></td>
																	<td colspan="2">
																		<div class="form-group">
																			<label class="toggle-large" for="octabook_plivo_admin_sms_notification_status">
																				<input <?php if($oct_settings->octabook_plivo_admin_sms_notification_status=='E'){echo "checked='checked'";} ?> type="checkbox" name="octabook_plivo_admin_sms_notification_status" id="octabook_plivo_admin_sms_notification_status" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
																			</label>
																		</div>	
																		<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Enable or Disable, Send SMS to Admin for appointment booking info.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td><label><?php echo __("Admin Phone Number","oct");?></label></td>
																	<td colspan="2">
																		<div class="input-group octabook_plivo_cd">
																			<!--<span class="input-group-addon"><span class="">+1</span></span>-->
																			<input type="text" class="form-control" name="octabook_plivo_admin_phone_no" id="octabook_plivo_admin_phone_no" value="<?php echo $oct_settings->octabook_plivo_admin_phone_no;?>" />
																									
																			<input type="hidden" id="octabook_plivo_ccode_alph" value="<?php echo $oct_settings->octabook_plivo_ccode_alph;?>" />
																			<input type="hidden" id="octabook_plivo_ccode" value="<?php echo $oct_settings->octabook_plivo_ccode;?>" />
																			
																		</div>	
																	</td>
																</tr>
																<tr>
																	<td id="hr"></td><td id="hr"></td><td id="hr"></td>
																</tr>
															</tbody>
														</table>
													</div>	
												</div>	
												</div>
											<!-- NEXMO  -->
												<div class="panel panel-default oct-sms-gateway">
													<div class="panel-heading">
														<h4 class="panel-title">
															<span><?php echo __("Nexmo SMS Gateway","oct");?><img class="oct-sms-gateway-img" src="<?php echo $plugin_url_for_ajax; ?>/assets/images/nexmo_logo.png" />
															</span>
															<div class="oct-enable-disable-right pull-right">
																<label class="toggle-large" for="octabook_sms_noti_nexmo">
																	<input <?php if($oct_settings->octabook_sms_noti_nexmo=='E'){echo "checked='checked'";} ?> type="checkbox" class="oct-toggle-sh" id="octabook_sms_noti_nexmo" name="octabook_sms_noti_nexmo"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
																</label>
															</div>
														</h4>
													</div>
										
												<div id="collapseOne" class="panel-collapse collapse_octabook_sms_noti_nexmo <?php if($oct_settings->octabook_sms_noti_nexmo=='D'){ echo 'hide-div';} ?> ">
													<div class="panel-body padding-15">
														<table class="form-inline table oct-common-table table-hover table-bordered table-striped">
															<tr><th colspan="3"><?php echo __("Nexmo Account Settings","oct");?></th></tr>
															<tbody>
																<tr>
																	<td><label><?php echo __("Nexmo API Key","oct");?></label></td>
																	<td colspan="2">
																		<div class="form-group oct-lgf">
																			<input type="text" class="form-control" name="octabook_nexmo_apikey" id="octabook_nexmo_apikey" size="70" value="<?php echo $oct_settings->octabook_nexmo_apikey;?>"/>
																		</div>	
																		<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Available from within your Nexmo Account.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td><label><?php echo __("Nexmo API Secret","oct");?></label></td>
																	<td colspan="2">
																		<div class="form-group oct-lgf">
																			<input type="password" class="form-control" name="octabook_nexmo_api_secret"
																			id="octabook_nexmo_api_secret" size="70" value="<?php echo $oct_settings->octabook_nexmo_api_secret; ?>" />
																		</div>	
																		<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Available from within your Nexmo Account.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td><label><?php echo __("Nexmo From","oct");?></label></td>
																	<td colspan="2">
																		<div class="form-group oct-lgf">
																			<input type="text" class="form-control" name="octabook_nexmo_form" id="octabook_nexmo_form" size="70" value="<?php echo $oct_settings->octabook_nexmo_form;?>" />
																		</div>	
																		<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Must be a valid number associated with your Nexmo account.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td id="hr"></td><td id="hr"></td><td id="hr"></td>
																</tr>
															</tbody>
															
															<tbody>
															
															
																<tr>
																	<td><label><?php echo __("Send SMS to Service Provider","oct");?></label></td>
																	<td colspan="2">
																		<div class="form-group">
																			<label class="toggle-large" for="octabook_nexmo_send_sms_sp_status">
																				<input <?php if($oct_settings->octabook_nexmo_send_sms_sp_status=='E'){echo "checked='checked'";} ?> type="checkbox" name="octabook_nexmo_send_sms_sp_status" id="octabook_nexmo_send_sms_sp_status"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
																			</label>
																		</div>	
																		<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Enable or Disable, Send SMS to Service provider for appointment booking info.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td><label><?php echo __("Nexmo Send Sms To Client Status","oct");?></label></td>
																	<td colspan="2">
																		<div class="form-group">
																			<label class="toggle-large" for="octabook_nexmo_send_sms_client_status">
																				<input <?php if($oct_settings->octabook_nexmo_send_sms_client_status=='E'){echo "checked='checked'";} ?> type="checkbox" name="octabook_nexmo_send_sms_client_status" id="octabook_nexmo_send_sms_client_status" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
																			</label>
																		</div>	
																		<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Enable or Disable, Send SMS to client for appointment booking info.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td><label><?php echo __("Nexmo Send Sms To admin Status","oct");?></label></td>
																	<td colspan="2">
																		<div class="form-group">
																			<label class="toggle-large" for="octabook_nexmo_send_sms_admin_status">
																				<input <?php if($oct_settings->octabook_nexmo_send_sms_admin_status=='E'){echo "checked='checked'";} ?> type="checkbox" name="octabook_nexmo_send_sms_admin_status" id="octabook_nexmo_send_sms_admin_status" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
																			</label>
																		</div>	
																		<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Enable or Disable, Send SMS to Admin for appointment booking info.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td><label><?php echo __("Nexmo Admin Phone Number","oct");?></label></td>
																	<td colspan="2">
																		<div class="input-group octabook_nexmo_cd">
																			<!--<span class="input-group-addon"><span class="">+1</span></span>-->
																			<input type="text" class="form-control" name="octabook_nexmo_admin_phone_no" id="octabook_nexmo_admin_phone_no" value="<?php echo $oct_settings->octabook_nexmo_admin_phone_no;?>" />
																			
																			<input type="hidden" id="octabook_nexmo_ccode_alph" value="<?php echo $oct_settings->octabook_nexmo_ccode_alph;?>" />
																			<input type="hidden" id="octabook_nexmo_ccode" value="<?php echo $oct_settings->octabook_nexmo_ccode;?>" />
																		</div>	
																	</td>
																</tr>
																<tr>
																	<td id="hr"></td><td id="hr"></td><td id="hr"></td>
																</tr>
															</tbody>
														</table>
													</div>	
												</div>	
												</div>
											<!-- nexmo end -->	
											<!-- Textlocal Settings -->
												<div class="panel panel-default oct-sms-gateway">
													<div class="panel-heading">
														<h4 class="panel-title">
															<span><?php echo __("Textlocal SMS Gateway","oct");?><img class="oct-sms-gateway-img" src="<?php echo $plugin_url_for_ajax; ?>/assets/images/textlocal-logo.png" />
															</span>
															<div class="oct-enable-disable-right pull-right">
																<label class="toggle-large" for="octabook_sms_noti_textlocal">
																	<input <?php if($oct_settings->octabook_sms_noti_textlocal=='E'){echo "checked='checked'";} ?> type="checkbox" class="oct-toggle-sh" id="octabook_sms_noti_textlocal" name="octabook_sms_noti_textlocal"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
																</label>
															</div>
														</h4>
													</div>
										
												<div id="collapseOne" class="panel-collapse collapse_octabook_sms_noti_textlocal <?php if($oct_settings->octabook_sms_noti_textlocal=='D'){ echo 'hide-div';} ?> ">
													<div class="panel-body padding-15">
														<table class="form-inline table oct-common-table table-hover table-bordered table-striped">
															<tr><th colspan="3"><?php echo __("Textlocal Account Settings","oct");?></th></tr>
															<tbody>
																<tr>
																	<td><label><?php echo __("Account API Key","oct");?></label></td>
																	<td colspan="2">
																		<div class="form-group oct-lgf">
																			<input type="text" class="form-control" name="octabook_textlocal_apikey" id="octabook_textlocal_apikey" size="70" value="<?php echo $oct_settings->octabook_textlocal_apikey;?>"/>
																		</div>	
																		<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Available from within your Textlocal Account.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td><label><?php echo __("Sender Name","oct");?></label></td>
																	<td colspan="2">
																		<div class="form-group oct-lgf">
																			<input type="text" class="form-control" name="octabook_textlocal_sender"
																			id="octabook_textlocal_sender" size="70" value="<?php echo $oct_settings->octabook_textlocal_sender; ?>" />
																		</div>	
																		<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Available from within your Textlocal Account.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td id="hr"></td><td id="hr"></td><td id="hr"></td>
																</tr>
															</tbody>
															
															<tbody>
															
															<th colspan="3"><?php echo __("Textlocal SMS Settings","oct");?></th>
																<tr>
																	<td><label><?php echo __("Send SMS To Service Provider","oct");?></label></td>
																	<td colspan="2">
																		<div class="form-group">
																			<label class="toggle-large" for="octabook_textlocal_service_provider_sms_notification_status">
																				<input <?php if($oct_settings->octabook_textlocal_service_provider_sms_notification_status=='E'){echo "checked='checked'";} ?> type="checkbox" name="octabook_textlocal_service_provider_sms_notification_status" id="octabook_textlocal_service_provider_sms_notification_status"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
																			</label>
																		</div>	
																		<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Enable or Disable, Send SMS to Service Provider for appointment booking info.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td><label><?php echo __("Send SMS To Client","oct");?></label></td>
																	<td colspan="2">
																		<div class="form-group">
																			<label class="toggle-large" for="octabook_textlocal_client_sms_notification_status">
																				<input <?php if($oct_settings->octabook_textlocal_client_sms_notification_status=='E'){echo "checked='checked'";} ?> type="checkbox" name="octabook_textlocal_client_sms_notification_status" id="octabook_textlocal_client_sms_notification_status"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
																			</label>
																		</div>	
																		<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Enable or Disable, Send SMS to Client for appointment booking info.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td><label><?php echo __("Send SMS To Admin","oct");?></label></td>
																	<td colspan="2">
																		<div class="form-group">
																			<label class="toggle-large" for="octabook_textlocal_admin_sms_notification_status">
																				<input <?php if($oct_settings->octabook_textlocal_admin_sms_notification_status=='E'){echo "checked='checked'";} ?> type="checkbox" name="octabook_textlocal_admin_sms_notification_status" id="octabook_textlocal_admin_sms_notification_status" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
																			</label>
																		</div>	
																		<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Enable or Disable, Send SMS to admin for appointment booking info.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td><label><?php echo __("Admin Phone Number","oct");?></label></td>
																	<td colspan="2">
																		<div class="input-group octabook_textlocal_cd">
																			<!--<span class="input-group-addon"><span class="">+1</span></span>-->
																			<input type="text" class="form-control" name="octabook_textlocal_admin_phone_no" id="octabook_textlocal_admin_phone_no" value="<?php echo $oct_settings->octabook_textlocal_admin_phone_no;?>" />
																			<input type="hidden" id="octabook_textlocal_ccode_alph" value="<?php echo $oct_settings->octabook_textlocal_ccode_alph;?>" />
																			<input type="hidden" id="octabook_textlocal_ccode" value="<?php echo $oct_settings->octabook_textlocal_ccode;?>" />
																		</div>	
																	</td>
																</tr>
																<tr>
																	<td id="hr"></td><td id="hr"></td><td id="hr"></td>
																</tr>
															</tbody>
														</table>
													</div>	
												</div>	
												</div>		
												<!--Textlocal End -->
											</div>
										</div>
									</div>
								</div>
									
								<a id="oct_update_smssettings" name="oct_update_smssettings" class="btn btn-success mt-10 ml-15" href="javascript:void(0)"><?php echo __("Save Setting","oct");?></a>
							</div>
						</div>
					</div>
				</form>
			</div>

			<div class="tab-pane oct-toggle-abs" id="sms-template">
				<div class="panel panel-default wf-100">
					<div class="panel-heading">
						<h1 class="panel-title"><?php echo __("SMS Template Settings","oct");?></h1>
					</div>
					<!-- Client sms templates -->
					<ul class="nav nav-tabs nav-justified">
						<li class="active"><a data-toggle="tab" href="#client-sms-template"><?php echo __("Client SMS Templates","oct");?></a></li>
						<li><a data-toggle="tab" href="#service-provider-sms-template"><?php echo __("Service Provider SMS Template","oct");?></a></li>
						<li><a data-toggle="tab" href="#admin-manager-sms-template"><?php echo __("Admin/Manager SMS Template","oct");?></a></li>
						
					</ul>
					<div class="tab-content">
						<div id="client-sms-template" class="tab-pane fade in active">
							<h3><?php echo __("Client SMS Templates","oct");?></h3>
								<div id="accordion" class="panel-group">
									<?php $oct_sms_templates->user_type='C';
									$AM_templates = $oct_sms_templates->readall_by_usertype();
									foreach($AM_templates as $AM_template){ ?>
									
									<div class="panel panel-default oct-sms-panel">
										<div class="panel-heading">
											<h4 class="panel-title">
												<div class="oct-col11">
													<div class="oct-yes-no-sms-right pull-left">
														<label for="sms_template_status<?php echo $AM_template->id;?>">
															<input <?php if($AM_template->sms_template_status=='e'){echo "checked='checked'";} ?> class="oct_update_smsstatus" type="checkbox" id="sms_template_status<?php echo $AM_template->id;?>" data-eid="<?php echo $AM_template->id;?>" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","oct");?>" data-off="<?php echo __("Off","oct");?>" data-onstyle="success" data-offstyle="default" />
														</label>
													</div>
													<span id="sms_subject_label<?php echo $AM_template->id;?>" class="oct-template-name"><?php echo $AM_template->sms_subject;?></span>
														
												</div>	
												<div class="pull-right oct-col1">
													<div class="pull-right">
														<div class="oct-show-hide pull-right">
															<input type="checkbox" name="oct-show-hide" class="oct-show-hide-checkbox" id="sms<?php echo $AM_template->id;?>" data-id="<?php echo $AM_template->id;?>">
															<label class="oct-show-hide-label" for="sms<?php echo $AM_template->id;?>"></label>
														</div>
													</div>
												</div>
											</h4>
										</div>
										<div class="panel-collapse collapse smstemplatedetail smsdetail_<?php echo $AM_template->id;?>">
											<div class="panel-body">
												<div class="oct-sms-temp-collapse-div col-md-12 col-lg-12 col-xs-12 np">
													<form id="" method="post" type="" class="slide-toggle" >
														<div class="col-md-8 col-sm-8 col-xs-12 form-group">
															<label><?php echo __("SMS Content","oct");?></label>
															<?php
															if($AM_template->sms_message!=''){
															$content=stripslashes_deep($AM_template->sms_message);
															}else{
															$content=stripslashes_deep($AM_template->default_message);
															}
															$editorName=  'sms_message'.$AM_template->id;
															$editorId ='sms_editor'.$AM_template->id;
															wp_editor($content,$editorId, array('textarea_name'=>$editorName,'media_buttons'=>true, 'teeny'=>false, 'tinymce' => false,'editor_class'=>'ak_wp_editor','wpautop' => true,'tabindex' => '','tabfocus_elements' => ':prev,:next','dfw' => false,'quicktags' => true)); ?>
															
															<a data-eid="<?php echo $AM_template->id;?>" class="btn btn-success oct-btn-width pull-left cb ml-15 mt-20 oct_save_smstemplate" type="submit"><?php echo __("Save Template","oct");?></a>
														</div>
														<div class="col-md-4 col-sm-4 col-xs-12">
															<div class="oct-sms-content-tags">
																<b><?php echo __("Tags","oct");?> </b><br />
																<?php foreach($sms_template_tags as $tags){
																		
																		echo "<a data-eid='".$AM_template->id."' class='tags' data-value='".$tags."'>".$tags."</a><br/>";
																	} ?>
																<br />
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
						<div id="service-provider-sms-template" class="tab-pane fade">
							<h3><?php echo __("Service Provider SMS Template","oct");?></h3>
							<div id="accordion" class="panel-group">
									<?php $oct_sms_templates->user_type='SP';
									$AM_templates = $oct_sms_templates->readall_by_usertype();
									foreach($AM_templates as $AM_template){ ?>
									
									<div class="panel panel-default oct-sms-panel">
										<div class="panel-heading">
											<h4 class="panel-title">
												<div class="oct-col11">
													<div class="oct-yes-no-sms-right pull-left">
														<label for="sms_template_status<?php echo $AM_template->id;?>">
															<input <?php if($AM_template->sms_template_status=='e'){echo "checked='checked'";} ?> class="oct-toggle-input oct_update_smsstatus" type="checkbox" id="sms_template_status<?php echo $AM_template->id;?>" data-eid="<?php echo $AM_template->id;?>" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","oct");?>" data-off="<?php echo __("Off","oct");?>" data-onstyle="success" data-offstyle="default" />
														</label>
													</div>
													
													<span id="sms_subject_label<?php echo $AM_template->id;?>" class="oct-template-name"><?php echo $AM_template->sms_subject;?></span>
														
												</div>	
												<div class="pull-right oct-col1">
													<div class="pull-right">
														<div class="oct-show-hide pull-right">
															<input type="checkbox" name="oct-show-hide" class="oct-show-hide-checkbox" id="sms<?php echo $AM_template->id;?>"  data-id="<?php echo $AM_template->id;?>"><!--Added Serivce Id-->
															<label class="oct-show-hide-label" for="sms<?php echo $AM_template->id;?>"></label>
														</div>
													</div>
												</div>
											</h4>
										</div>
										<div class="panel-collapse collapse smstemplatedetail smsdetail_<?php echo $AM_template->id;?>">
											<div class="panel-body">
												<div class="oct-sms-temp-collapse-div col-md-12 col-lg-12 col-xs-12 np">
													<form id="" method="post" type="" class="slide-toggle" >
														<div class="col-md-8 col-sm-8 col-xs-12 form-group">
															<label><?php echo __("SMS Content","oct");?></label>
															<?php
															if($AM_template->sms_message!=''){
															$content=stripslashes_deep($AM_template->sms_message);
															}else{
															$content=stripslashes_deep($AM_template->default_message);
															}
															$editorName=  'sms_message'.$AM_template->id;
															$editorId ='sms_editor'.$AM_template->id;
															wp_editor($content,$editorId, array('textarea_name'=>$editorName,'media_buttons'=>true, 'teeny'=>false, 'tinymce' => false,'editor_class'=>'ak_wp_editor','wpautop' => true,'tabindex' => '','tabfocus_elements' => ':prev,:next','dfw' => false,'quicktags' => true)); ?>
															
															<a data-eid="<?php echo $AM_template->id;?>" class="btn btn-success oct-btn-width pull-left cb ml-15 mt-20 oct_save_smstemplate" type="submit"><?php echo __("Save Template","oct");?></a>
														</div>
														<div class="col-md-4 col-sm-4 col-xs-12">
															<div class="oct-sms-content-tags">
																<b><?php echo __("Tags","oct");?> </b><br />
																<?php foreach($sms_template_tags as $tags){
																		
																		echo "<a data-eid='".$AM_template->id."' class='tags' data-value='".$tags."'>".$tags."</a><br/>";
																	} ?>
																<br />
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
						
						<div id="admin-manager-sms-template" class="tab-pane fade">
							<h3><?php echo __("Admin/Manager Provider SMS Template","oct");?></h3>
							<div id="accordion" class="panel-group">
									<?php $oct_sms_templates->user_type='AM';
									$AM_templates = $oct_sms_templates->readall_by_usertype();
									foreach($AM_templates as $AM_template){ ?>
									
									<div class="panel panel-default oct-sms-panel">
										<div class="panel-heading">
											<h4 class="panel-title">
												<div class="oct-col11">
													<div class="oct-yes-no-sms-right pull-left">
														<label for="sms_template_status<?php echo $AM_template->id;?>">
															<input <?php if($AM_template->sms_template_status=='e'){echo "checked='checked'";} ?> class="oct-toggle-input oct_update_smsstatus" type="checkbox" id="sms_template_status<?php echo $AM_template->id;?>" data-eid="<?php echo $AM_template->id;?>" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","oct");?>" data-off="<?php echo __("Off","oct");?>" data-onstyle="success" data-offstyle="default" />
														</label>
													</div>
													<span id="sms_subject_label<?php echo $AM_template->id;?>" class="oct-template-name"><?php echo $AM_template->sms_subject;?></span>
														
												</div>	
												<div class="pull-right oct-col1">
													<div class="pull-right">
														<div class="oct-show-hide pull-right">
															<input type="checkbox" name="oct-show-hide" class="oct-show-hide-checkbox" id="sms<?php echo $AM_template->id;?>" data-id="<?php echo $AM_template->id;?>"><!--Added Serivce Id-->
															<label class="oct-show-hide-label" for="sms<?php echo $AM_template->id;?>"></label>
														</div>
													</div>
												</div>
											</h4>
										</div>
										<div class="panel-collapse collapse smstemplatedetail smsdetail_<?php echo $AM_template->id;?>">
											<div class="panel-body">
												<div class="oct-sms-temp-collapse-div col-md-12 col-lg-12 col-xs-12 np">
													<form id="" method="post" type="" class="slide-toggle" >
														<div class="col-md-8 col-sm-8 col-xs-12 form-group">
															<label><?php echo __("SMS Content","oct");?></label>
															<?php
															if($AM_template->sms_message!=''){
															$content=stripslashes_deep($AM_template->sms_message);
															}else{
															$content=stripslashes_deep($AM_template->default_message);
															}
															$editorName=  'sms_message'.$AM_template->id;
															$editorId ='sms_editor'.$AM_template->id;

															wp_editor($content,$editorId, array('textarea_name'=>$editorName,'media_buttons'=>true, 'teeny'=>false, 'tinymce' => false,'editor_class'=>'ak_wp_editor','wpautop' => true,'tabindex' => '','tabfocus_elements' => ':prev,:next','dfw' => false,'quicktags' => true)); ?>
															
															<a data-eid="<?php echo $AM_template->id;?>" class="btn btn-success oct-btn-width pull-left cb ml-15 mt-20 oct_save_smstemplate" type="submit"><?php echo __("Save Template","oct");?></a>
														</div>
														<div class="col-md-4 col-sm-4 col-xs-12">
															<div class="oct-sms-content-tags">
																<b><?php echo __("Tags","oct");?> </b><br />
																<?php foreach($sms_template_tags as $tags){
																		
																		echo "<a data-eid='".$AM_template->id."' class='tags' data-value='".$tags."'>".$tags."</a><br/>";
																	} ?>
																<br />
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
				</div>
			</div>
			
			<div class="tab-pane oct-toggle-abs" id="labels">
				<form id="" method="post" type="" class="oct-labels-settings" >
					<div class="panel panel-default">
						<div class="panel-heading">
							<h1 class="panel-title"><?php echo __("Labels","oct");?></h1>
						</div>
						<div class="panel-body">
							<div class="table-responsive"> 
								<table class="form-inline oct-common-table table table-hover table-bordered table-striped">
									<tbody>
										<tr><th colspan="3"><?php echo __("Appointkart Frontend First Step Labels","oct");?></th></tr>
										<tr>
											<th><?php echo __("Original Label","oct");?></th>
											<th><?php echo __("Custom Label","oct");?></th>
										</tr>
										<tr>
											<td><?php echo __("Choose Service","oct");?></td>
											<td><div class="form-group">
												<input class="form-control" type="text" name="" value="<?php echo __("Choose Date,Time and Provider","oct");?>" />
												</div>
											</td>
										</tr>
										<tr>
											<td><?php echo __("Choose Service","oct");?></td>
											<td><div class="form-group">
												<input class="form-control" type="text" name="" value="<?php echo __("Choose Date,Time and Provider","oct");?>" />
												</div>
											</td>
										</tr>
										<tr><th colspan="3"><?php echo __("Appointkart Frontend First Step Labels","oct");?></th></tr>
										<tr>
											<td><?php echo __("Your Appointments","oct");?></td>
											<td><div class="form-group">
												<input class="form-control" type="text" name="" value="<?php echo __("Your Appointments","oct");?>" />
												</div>
											</td>
										</tr>
										<tr>
											<td><?php echo __("Total","oct");?></td>
											<td><div class="form-group">
												<input class="form-control" type="text" name="" value="<?php echo __("Total","oct");?>" />
												</div>
											</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											
											<td colspan="3">
												<button id="" name="" class="btn btn-success" type="submit"><?php echo __("Save Setting","oct");?></a>
												<button type="reset" class="btn btn-default ml-30"><?php echo __("Reset","oct");?></button>
									
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="tab-pane oct-toggle-abs" id="custom-form-fields">
				<div id="" class="oct-custom-form-fields" >
					<div class="panel panel-default">
						<div class="panel-heading">
							<h1 class="panel-title"><?php echo __("Custom Form Fields","oct");?></h1>
						</div>
						<div class="panel-body">
							<!--  <form action="">
								<textarea name="form-builder-template" id="form-builder-template" cols="30" rows="10"></textarea>
							  </form> -->
							  
								<div class="build-wrap"></div>
								<div class="render-wrap"></div>
								<!--<button id="edit-form">Edit Form</button>-->
								

						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane oct-toggle-abs" id="promocode">
				<div class="panel panel-default">
						<div class="panel-heading">
							<h1 class="panel-title"><?php echo __("Promocode","oct");?></h1>
						</div>
						<ul class="nav nav-tabs">
							<li class="oct_promocode_list active"><a data-toggle="tab" href="#oct_promocode_list"><?php echo __("Promocodes","oct");?></a></li>
							<li class="oct_addnew_promocode"><a data-toggle="tab" href="#oct_addnew_promocode"><?php echo __("Add New Promocode","oct");?></a></li>
							<li class="oct_update_promocode_tab"><a data-toggle="tab" class="oct-update-promocode hide-div" href="#oct_update_promocode"><?php echo __("Update Promocode","oct");?></a></li>
							
						</ul>
						<?php $oct_all_coupons = $oct_coupons->readAll();?>
						<div class="tab-content">							
							<div id="oct_promocode_list" class="tab-pane fade in active">			
								<h3><?php echo __("Promocodes list","oct");?></h3>
								<div class="table-responsive">
									<table id="oct-promocode-list" class="display table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th><?php echo __("Coupon Code","oct");?></th>
												<th><?php echo __("Coupon Expire On","oct");?></th>
												<th><?php echo __("Coupon Value","oct");?></th>
												<th><?php echo __("Coupon Limit","oct");?></th>
												<th><?php echo __("Coupon Used","oct");?></th>
												<th><?php echo __("Coupon Status","oct");?></th>
												<th><?php echo __("Actions","oct");?></th>
											</tr>
										</thead>
										<tbody id="coupon_list">
											<?php foreach($oct_all_coupons as $oct_coupons){ ?>
											<tr id="coupon_detail<?php echo $oct_coupons->id;?>">	
												<td><?php echo $oct_coupons->coupon_code;?></td>
												<td><?php echo date_i18n(get_option('octabook_datepicker_format'),strtotime($oct_coupons->coupon_expires_on));?></td>
												<td><?php echo $oct_coupons->coupon_value;?></td>
												<td><?php echo $oct_coupons->coupon_limit;?></td>
												<td><?php echo $oct_coupons->coupon_used;?></td>
												<td>
													<label class="toggle-large oct-toggle-medium" for="promocode_status<?php echo $oct_coupons->id;?>">
													<input <?php if($oct_coupons->coupon_status=='E'){ echo "checked='checked'";} ?> data-cid="<?php echo $oct_coupons->id;?>" class="oct-toggle-medium-input oct_update_couponstatus" type="checkbox" id="promocode_status<?php echo $oct_coupons->id;?>" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","oct");?>" data-off="<?php echo __("Disable","oct");?>" data-onstyle="success" data-offstyle="danger" />
													</label>
												</td>
												<td>
													<a data-cid="<?php echo $oct_coupons->id;?>" href="javascript:void(0)" id="update_promocode<?php echo $oct_coupons->id;?>" class="btn-circle btn-info btn-xs oct_update_promocode" title="<?php echo __("Edit coupon code","oct");?>"><i class="fa fa-pencil-square-o"></i></a>
													
													<a data-poid="oct-popover-coupon<?php echo $oct_coupons->id; ?>" id="oct-delete-coupon<?php echo $oct_coupons->id; ?>" class="pull-right btn-circle btn-danger btn-sm oct-delete-popover" rel="popover" data-placement='bottom' title="<?php echo __("Delete this coupon?","oct");?>"> <i class="fa fa-trash" title="<?php echo __("Delete coupon","oct");?>"></i></a>
													<div class="oct-popover" id="oct-popover-coupon<?php echo $oct_coupons->id; ?>" style="display: none;">
														<div class="arrow"></div>
															<table class="form-horizontal" cellspacing="0">
																<tbody>
																	<tr>
																		<td>
																			<a data-id="<?php echo $oct_coupons->id; ?>" value="Delete" class="btn btn-danger btn-sm mr-10 oct_delete_coupon" type="submit"><?php echo __("Yes","oct");?></a>
																			<a data-poid="oct-popover-coupon<?php echo $oct_coupons->id; ?>" class="btn btn-default btn-sm oct-close-popover-delete" href="javascript:void(0)"><?php echo __("Cancel","oct");?></a>
																			</td>
																	</tr>
																</tbody>
															</table>
														</div>
												</td>
											
											</tr>
											<?php } ?>		
										</tbody>
									</table>									
								</div>
						
							</div>
							<div id="oct_addnew_promocode" class="tab-pane fade">
								<h3><?php echo __("Add New Promocode","oct");?></h3>
								<form id="oct_create_coupon_form" method="post" type="" class="" >
									<div class="table-responsive"> 
										<table class="form-inline oct-common-table">
											<tbody>
												<tr>
													<td><?php echo __("Coupon Code","oct");?></td>
													<td>
														<div class="form-group">
															<input id="oct_coupon_code" type="text" class="form-control" name="oct_coupon_code" value="" placeholder="<?php echo __("Your Coupon Code","oct");?>" />
														</div>
													</td>
												</tr>
												<tr>
													<td><?php echo __("Coupon Type","oct");?></td>
													<td>
														<div class="form-group">
															<select id="oct_coupon_type" name="oct_coupon_type" class="selectpicker" data-size="3"  style="display: none;">
																<option value="P"><?php echo __("Percentage","oct");?></option> 					
																<option value="F"><?php echo __("Flat","oct");?></option> 		
															</select>
														</div>
													</td>
												</tr>
												<tr>
													<td><?php echo __("Value","oct");?></td>
													<td>
														<div class="form-group">
															<input id="oct_coupon_value" type="text" class="form-control" name="oct_coupon_value" value="" placeholder="<?php echo __("Value","oct");?>" />
														</div>
														<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Coupon Value would be consider as percentage in percentage mode and in flat mode it will be consider as amount.No need to add percentage sign it will auto added.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
													</td>
												</tr>
												<tr>
													<td><?php echo __("Limit","oct");?></td>
													<td>
														<div class="form-group">
															<input id="oct_coupon_limit" type="text" class="form-control" name="oct_coupon_limit" value="" placeholder="<?php echo __("Coupon Limit","oct");?>" />
														</div>
														<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Coupon code will work for such limit","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
													</td>
												</tr>
												<tr>
													<td><?php echo __("Expiry Date","oct");?></td>
													<td>
														<div class="form-group input-group">
															<input name="oct_coupon_expiry" id="oct_coupon_expiry" class="form-control oct_coupon_expiry" data-provide="datepicker" value="<?php echo date_i18n('m/d/Y');?>" />
															<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
														</div>	
														<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Coupon code will work for such date","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
													</td>
												</tr>
											<tbody>
													<tr>
														<td></td>
														<td>
															<a href="javascript:void(0)" id="oct_create_coupon" name="oct_create_coupon" class="btn btn-success"><?php echo __("Create","oct");?></a>
														</td>
													</tr>
												</tbody>
											</tbody>
										
										</table>
									</div>	
								</form>
								
							</div>
							<div id="oct_update_promocode" class="tab-pane fade active">
								<h3><?php echo __("Update Promocode","oct");?></h3>									
									<div class="table-responsive"> 
									<?php foreach($oct_all_coupons as $oct_coupons){ ?>
									 <form id="oct_update_promocode_info<?php echo $oct_coupons->id;?>" method="post" type="" class="oct_update_promocode_info" >
										<table id="oct_coupon_update_info<?php echo $oct_coupons->id;?>" class="form-inline oct-common-table  oct_coupon_update_info hide-div">
										
											<tbody>								
												<tr>
													<td><?php echo __("Coupon Code","oct");?></td>
													<td>
														<div class="form-group">
															<input id="oct_uc_code<?php echo $oct_coupons->id;?>" type="text" class="form-control" name="oct_uc_code" value="<?php echo $oct_coupons->coupon_code;?>" placeholder="<?php echo __("Your Coupon Code","oct");?>" />
														</div>
													</td>
												</tr>
												<tr>
													<td><?php echo __("Coupon Type","oct");?></td>
													<td>
														<div class="form-group">
															<select id="oct_uc_type<?php echo $oct_coupons->id;?>" name="oct_uc_type" class="selectpicker" data-size="3"  style="display: none;">
																<option <?php if($oct_coupons->coupon_type=='P'){ echo "selected";} ?> value="P"><?php echo __("Percentage","oct");?></option> 					
																<option <?php if($oct_coupons->coupon_type=='F'){ echo "selected";} ?> value="F"><?php echo __("Flat","oct");?></option> 		
															</select>
														</div>
													</td>
												</tr>
												<tr>
													<td><?php echo __("Value","oct");?></td>
													<td>
														<div class="form-group">
															<input id="oct_uc_value<?php echo $oct_coupons->id;?>" type="text" class="form-control" name="oct_uc_value" value="<?php echo $oct_coupons->coupon_value;?>" placeholder="<?php echo __("Value","oct");?>" />
														</div>
														<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Coupon Value would be consider as percentage in percentage mode and in flat mode it will be consider as amount.No need to add percentage sign it will auto added.","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
													</td>
												</tr>
												<tr>
													<td><?php echo __("Limit","oct");?></td>
													<td>
														<div class="form-group">
															<input id="oct_uc_limit<?php echo $oct_coupons->id;?>" type="text" class="form-control" name="oct_uc_limit" value="<?php echo $oct_coupons->coupon_limit;?>" placeholder="<?php echo __("Coupon Limit","oct");?>" />
														</div>
														<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Coupon code will work for such limit","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
													</td>
												</tr>
												<tr>
													<td><?php echo __("Expiry Date","oct");?></td>
													<td>
														<div  class="form-group input-group">
															<input id="oct_uc_expiry<?php echo $oct_coupons->id;?>" class="form-control oct_coupon_expiry" data-provide="datepicker" value="<?php echo date_i18n('m/d/Y',strtotime($oct_coupons->coupon_expires_on));?>"/>
															<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
														</div>	
														<a class="oct-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Coupon code will work for such date","oct");?>"><i class="fa fa-info-circle fa-lg"></i></a>
													</td>
												</tr>
											<tbody>
													<tr>
														<td></td>
														<td>
															<a href="javascript:void(0)" id="<?php echo $oct_coupons->id;?>" name="" class="btn btn-success oct_update_coupon_info" ><?php echo __("Update","oct");?></a>
														</td>
													</tr>
													<input id="oct_uc_status<?php echo $oct_coupons->id;?>" type="hidden" name="oct_uc_status" value="<?php echo $oct_coupons->coupon_status;?>"/>
												</tbody>									
											</tbody>
										</table>
									</form>
									<?php } ?>
										
									</div>								
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="tab-pane oct-toggle-abs" id="google-calendar">
				<?php
				$GcclientID = get_option('oct_gc_client_id');
				$GcclientSecret = get_option('oct_gc_client_secret');
				$GcEDvalue = get_option('oct_gc_status');
				?>
				<form id="" method="post" type="" class="oct-google-calendar" >
					<div class="panel panel-default">
						<div class="panel-heading oct-top-right">
							<h1 class="panel-title"><?php echo __("Google Calendar","oct");?> </h1>
						</div>
						<div class="panel-body">
							<table class="form-inline oct-common-table">
								<tbody>
									<tr>
										<td><?php echo __("Add Appointments To Google Calendar","oct");?></td><td><input class="gc_enable_disable" data-on='<?php echo __("Yes","oct");?>' data-off='<?php echo __("No","oct");?>' data-onstyle="primary" data-offstyle="default" data-toggle="toggle" type="checkbox" name="appointup_gc_status" <?php if(get_option('oct_gc_status') == 'Y') { echo ' checked  '; } ?> /></td>
									</tr>
									<tr>
										<td><?php echo __("Google Calender Client ID","oct");?></td>
										<td>
											<div class="form-group">
												<input type="text" class="form-control" size="35" id="oct_gc_client_id" value="<?php echo get_option('oct_gc_client_id'); ?>" placeholder="<?php echo __("Your Client ID","oct");?>" />
											</div>
										</td>
									</tr>
									<tr>
										<td><?php echo __("Google Calender Client Secret","oct");?></td>
										<td>
											<div class="form-group">
												<input type="text" class="form-control" size="35" id="oct_gc_client_secret" value="<?php echo get_option('oct_gc_client_secret'); ?>" placeholder="<?php echo __("Your Client Secret ID","oct");?>" />
											</div>
										</td>
									</tr>
									<tr>
										<td><?php echo __("Google Calender Frontend URL","oct");?></td>
										<td>
											<div class="form-group">
												<input type="text" class="form-control" size="35" id="oct_gc_frontend_url" value="<?php echo get_option('oct_gc_frontend_url'); ?>" placeholder="<?php echo __("Your Frontend URL","oct");?>" />
											</div>
										</td>
									</tr>
									<tr>
										<td><?php echo __("Google Calender Admin URL","oct");?></td>
										<td>
											<div class="form-group">
												<input type="text" class="form-control" size="35" id="oct_gc_admin_url" value="<?php echo get_option('oct_gc_admin_url'); ?>" placeholder="<?php echo __("Your Admin URL","oct");?>" />
											</div>
										</td>
									</tr>
									<tr>
										<td><?php echo __("Two Way Sync","oct");?></td><td><input class="appointup_gc_twowaysync" data-on='<?php echo __("Yes","oct");?>' data-off='<?php echo __("No","oct");?>' data-onstyle="primary" data-offstyle="default"  data-toggle="toggle" type="checkbox" name="appointup_gc_twowaysync" <?php if(get_option('oct_gc_two_way_sync_status') == 'Y') { echo ' checked  '; } ?> /></td>
									</tr>
									
									<?php
									 if($GcclientID!='' &&	$GcclientSecret!='' &&	$GcEDvalue=='Y'){
										 $client = new Google_Client();
										 $client->setApplicationName('OctaBook Google Calender');
										 $client->setClientId($GcclientID);
										 $client->setClientSecret($GcclientSecret);
										 $client->setRedirectUri(get_option('oct_gc_admin_url'));
										 $client->setDeveloperKey($GcclientID);
										 $client->setScopes(array('https://www.googleapis.com/auth/userinfo.email','https://www.googleapis.com/auth/calendar','https://www.google.com/calendar/feeds/'));
										 $client->setAccessType('offline');
										 $client->setApprovalPrompt( 'force' );
										 
										 if(isset($_GET['GC_action']) && $_GET['GC_action']=='gcd'){
											$revokeaccesstoken = get_option('oct_gc_token');
											$client->revokeToken($revokeaccesstoken);
											update_option('oct_gc_token', '');
											header('Location:'.site_url().'/wp-admin/admin.php?page=settings_submenu');
										 }
										 
										 
										 if(isset($_GET['code']) && $_GET['code']!=''){
											$access_token =  $client->authenticate($_GET['code']);
											update_option('oct_gc_token',$access_token);
											header('Location:'.site_url().'/wp-admin/admin.php?page=settings_submenu');
										 }
										 
										 $curlcalenders = curl_init();
										 curl_setopt_array($curlcalenders, array(
										  CURLOPT_RETURNTRANSFER => 1,
										  CURLOPT_URL => site_url().'/wp-content/plugins/octabook/assets/GoogleCalendar/callist.php?pid=0',
										  CURLOPT_FRESH_CONNECT =>true,
										  CURLOPT_USERAGENT => 'OctaBook'
										 ));
										 $response = curl_exec($curlcalenders);
										 
										 curl_close($curlcalenders);
										 if(isset($response)){
										  $calenders = json_decode($response);
										 }else{
										  $calenders = array();
										 }
									if(count($calenders)==0){
									?>
									<tr>
										<td></td>
										<td><?php	$authUrl = $client->createAuthUrl();
											print "<a class='verify_gc_account' style='color:#1E8CBE' href='javascript:void(0)' data-hreflink='$authUrl' data-provider_id=''>Verify Account</a>";?></td>
									</tr>
									<?php  }else{ ?>
									<tr>
										<td><?php echo __("Select Calendar","oct");?></td>
										<td><select name="appointup_gc_id" class="appointup_gc_id"><?php	
										for($i=0;$i<count($calenders);$i++){
											foreach($calenders[$i] as $calinfo){
												$calenderInfo = explode('##==##',$calinfo);
												$selected='';
												if(get_option('oct_gc_id')==$calenderInfo[1]){ $selected="selected";}
												echo "<option ".$selected." value='".$calenderInfo[1]."'>".$calenderInfo[0]."</option>";
											}
										}
										?></select> <a style="text-decoration:underline;color:#1E8CBE;" href="<?php echo site_url();?>/wp-admin/admin.php?page=settings_submenu&GC_action=gcd"><?php echo __("Disconnect","oct");?></a></td>
									</tr><?php
									}  } ?>
														
								</tbody>
								<tfoot>
									<tr>
										<td></td>
										<td>
											<a id="oct_save_gc_settings" name="" class="btn btn-success"><?php echo __("Save Setting","oct");?></a>
										</td>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php if(get_option('octabook_payment_method_Paypal')=='E' || get_option('octabook_payment_method_Stripe')=='E' || get_option('octabook_payment_method_Authorizenet')=='E'|| get_option('octabook_payment_method_2Checkout')=='E') {
		$any_payment_method_enable = 'E';
} else {
		$any_payment_method_enable = 'D';
}
if(get_option('default_company_country_flag') != ''){
	$default_flag = get_option('default_company_country_flag');
}else{
	$default_flag = "us";
}
?>	
<script>
var general_setting_pd_ed={"payment_gateway_status":"<?php echo $any_payment_method_enable; ?>"};
var general_settings_ajax_path={"ajax_path":"<?php echo $plugin_url_for_ajax; ?>"};
var general_settings_default_flag={"default_flag":"<?php echo $default_flag; ?>"};
</script>