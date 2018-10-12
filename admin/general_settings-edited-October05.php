<?php
//Changed ENABLE to ENABLED, and DISABLE to DISABLED
if ( ! defined( 'ABSPATH' ) ) exit;

// set page headers
$page_title = "Settings";
include_once "header.php";

// instantiate schedule object
$apt_settings = new appointment_settings();
$apt_email_templates = new appointment_email_template();
$apt_sms_templates = new appointment_sms_template();
$apt_coupons = new appointment_coupons();
$apt_coupons->location_id = $_SESSION['apt_location'];
$apt_coupons->business_owner_id = get_current_user_id();
$apt_settings->readAll();



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
<div class="panel apt-panel-default" id="apt-settings">
	<div class="apt-settings apt-left-menu col-md-2 col-sm-3 col-xs-12 col-lg-2">
		<ul class="nav nav-tab nav-stacked">				
			<li class="active"><a href="#company-details" class="top-company-details" data-toggle="pill"><i class="fa fa-building-o fa-2x"></i><br /><?php echo __("Company","apt");?></a></li>
			<li><a href="#general-setting" class="top-general-setting" data-toggle="pill"><i class="fa fa-cog fa-2x"></i><br /><?php echo __("General","apt");?></a></li>
			<li><a href="#appearance-setting" class="top-appearance-setting" data-toggle="pill"><i class="fa fa-tachometer fa-2x"></i><br /><?php echo __("Appearance ","apt");?></a></li>
			<li><a href="#payment-setting" class="top-payment-setting" data-toggle="pill"><i class="fa fa-money fa-2x"></i><br /><?php echo __("Payment ","apt");?></a></li>
			<li><a href="#email-setting" class="top-email-setting" data-toggle="pill"><i class="fa fa-paper-plane fa-2x"></i><br /><?php echo __("Email Notification","apt");?></a></li>
			<li><a href="#email-template" class="top-email-template" data-toggle="pill"><i class="fa fa-envelope fa-2x"></i><br /><?php echo __("Email Templates","apt");?></a></li>
			<li><a href="#sms-reminder" class="top-sms-reminder" data-toggle="pill"><i class="fa fa-mobile fa-2x"></i><br /><?php echo __("SMS Notification","apt");?></a></li>
			<li><a href="#sms-template" class="top-sms-template" data-toggle="pill"><i class="fa fa-envelope fa-2x"></i><br /><?php echo __("SMS Templates","apt");?></a></li>
			<li><a href="#custom-form-fields" class="top-custom-formfield" data-toggle="pill"><i class="fa fa-align-left fa-2x"></i><br /><?php echo __("Custom Form Fields","apt");?></a></li>
			<li><a href="#promocode" class="top-promocode" data-toggle="pill"><i class="fa fa-tag fa-2x"></i><br /><?php echo __("Promocode","apt");?></a></li>
			<li><a href="#google-calendar" class="top-promocode" data-toggle="pill"><i class="fa fa-calendar fa-2x"></i><br /><?php echo __("Google Calendar","apt");?></a></li>
		</ul>
	</div>
	<div class="apt-setting-details tab-content col-md-10 col-sm-9 col-lg-10 col-xs-12 np container-fluid">
		<div class="tab-content pr">
			<div class="company-details tab-pane active apt-toggle-abs" id="company-details">
				<form id="" method="post" type="" class="apt-company-details" >
					<div class="panel panel-default">
						<div class="panel-heading apt-top-right">
							<h1 class="panel-title"><?php echo __("Company Settings","apt");?> </h1>
						</div>
						<div class="panel-body">
							<table class="form-inline apt-common-table">
								<tbody>
									<tr>
										<td><label><?php echo __("Your Business Name","apt");?></label></td>
										<td>
											<div class="form-group">
												<input type="text" class="form-control" size="35" id="appointment_company_name" value="<?php echo $apt_settings->appointment_company_name; ?>" placeholder="<?php echo __("Your Company Name","apt");?>" />
											</div>	
											<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Company name is used for invoicing purpose.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Company Email","apt");?></label></td>
										<td>
											<div class="form-group">
												<input type="text" class="form-control" size="35" id="appointment_company_email" value="<?php echo $apt_settings->appointment_company_email; ?>" placeholder="<?php echo __("Your Company email","apt");?>" />
											</div>	
										</td>
									</tr>
									<!--country code-->
									<tr>
										<td><label><?php echo __("Default Country Code","apt");?></label></td>
										<td>
											<div class="form-group">
												<input style="width: 30.5% !important;" type="text" class="form-control custom-flag-space" size="35" id="appointment_company_country_code" value="<?php echo $apt_settings->appointment_company_country_code; ?>"
												
												placeholder="<?php echo __("","apt");?>" />
											</div>	
										</td>
									</tr>
									
									<tr>
										<td><label><?php echo __("Company Phone","apt");?></label></td>
										<td>
											<div class="input-group">
												<span class="input-group-addon" style="width: 43px;height: 30px;"><span class="company_country_code_value"><?php echo $apt_settings->appointment_company_country_code; ?></span></span>
												<input type="hidden" class="default_company_country_flag" value="" />
												<input style="width: 75%;" type="text" class="form-control" size="35" id="appointment_company_phone" value="<?php echo $apt_settings->appointment_company_phone; ?>" placeholder="<?php echo __("Company Phone","apt");?>" />
											</div>	
										</td>
									</tr>				
									<!-- country code -->
									
									<tr>
										<td><label><?php echo __("Company Address","apt");?></label></td>
									
										<td><div class="form-group">
											<div class="apt-col12"><textarea id="appointment_company_address" class="form-control" cols="44"><?php echo $apt_settings->appointment_company_address; ?></textarea></div>
											</div>
										</td>
									</tr>
									<tr>
										<td></td>
										<td><div class="form-group">
											<div class="apt-col6 apt-w-50">
												<input type="text" class="form-control" id="appointment_company_city" value="<?php echo $apt_settings->appointment_company_city; ?>" placeholder="<?php echo __("City","apt");?>" />
											</div>
											<div class="apt-col6 apt-w-50 float-right">
												<input type="text" class="form-control" id="appointment_company_state" value="<?php echo $apt_settings->appointment_company_state; ?>" placeholder="<?php echo __("State","apt");?>" />
											</div>
											</div>
										</td>
									</tr>
									<tr>
										<td></td>	
										<td><div class="form-group">	
											<div class="apt-col6 apt-w-50">
												<input type="text" class="form-control" id="appointment_company_zip" value="<?php echo $apt_settings->appointment_company_zip; ?>" placeholder="<?php echo __("Zip","apt");?>" />
											</div>	
											<div class="apt-col6 apt-w-50 float-right">
												<input type="text" class="form-control" id="appointment_company_country" value="<?php echo $apt_settings->appointment_company_country; ?>" placeholder="<?php echo __("Country","apt");?>" />
											</div>	
											</div>		
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Company Logo","apt");?></label></td>
										<td>
											<div class="form-group">
												<div class="apt-company-image-uploader">
													<img id="bdcslocimage" src="<?php if($apt_settings->appointment_company_logo==''){ echo $plugin_url_for_ajax.'/assets/images/company.png';}else{echo site_url()."/wp-content/uploads".$apt_settings->appointment_company_logo;	}?>" class="apt-company-image br-4" height="100" width="100">
													<label <?php if($apt_settings->appointment_company_logo==''){ echo "style='display:block'"; }else{ echo "style='display:none;'"; }?> for="apt-upload-imagebdcs" class="apt-company-img-icon-label">
														<i class="apt-camera-icon-common br-100 fa fa-camera"></i>
														<i class="pull-left fa fa-plus-circle fa-2x  custom-imageplus-icon"></i>
													</label>
													<input data-us="bdcs" class="hide apt-upload-images" type="file" name="" id="apt-upload-imagebdcs"  />
													
													<a id="apt-remove-company-imagebdcs" <?php if($apt_settings->appointment_company_logo!=''){ echo "style='display:block;'";}  ?> class="hide-div pull-left br-4 btn-danger apt-remove-company-img btn-xs apt_remove_image" rel="popover" data-placement='bottom' title="<?php echo __("Remove Image?","apt");?>"> <i class="fa fa-trash" title="<?php echo __("Remove company Image","apt");?>"></i></a>												
													<div style="display: none;" class="apt-popover" id="popover-apt-remove-company-imagebdcs">
														<div class="arrow"></div>
														<table class="form-horizontal" cellspacing="0">
															<tbody>
																<tr>
																	<td>
																		<a href="javascript:void(0)" value="Delete" data-mediapath="<?php echo $apt_settings->appointment_company_logo;?>" data-imgfieldid="bdcsuploadedimg"
																		class="btn btn-danger btn-sm apt_delete_companyimage"><?php echo __("Yes","apt");?></a>
																		<a href="javascript:void(0)" id="popover-apt-remove-company-imagebdcs" class="btn btn-default btn-sm close_delete_popup" href="javascript:void(0)"><?php echo __("Cancel","apt");?></a>
																	</td>
																</tr>
															</tbody>
														</table>
													</div>
												</div>
											</div>
											<div id="apt-image-upload-popupbdcs" class="apt-image-upload-popup modal fade" tabindex="-1" role="dialog">
										<div class="vertical-alignment-helper">
											<div class="modal-dialog modal-md vertical-align-center">
												<div class="modal-content" style="width:607px">
													<div class="modal-header">
														<div class="col-md-12 col-xs-12">
															<a data-us="bdcs" class="btn btn-success apt_upload_img" data-imageinputid="apt-upload-imagebdcs"><?php echo __("Crop & Save","apt");?></a>
															<button type="button" class="btn btn-default hidemodal" data-dismiss="modal" aria-hidden="true"><?php echo __("Cancel","apt");?></button>
														</div>	
													</div>
													<div class="modal-body">
														<img id="apt-preview-imgbdcs" />
													</div>
													<div class="modal-footer">
														<div class="col-md-12 np">
															<div class="col-md-4 col-xs-12">
																<label class="pull-left"><?php echo __("File size","apt");?></label> <input type="text" style="width:100%;" class="form-control" id="bdcsfilesize" name="filesize" />
															</div>	
															<div class="col-md-4 col-xs-12">	
																<label class="pull-left"><?php echo __("H","apt");?></label> <input type="text" style="width:100%;" class="form-control" id="bdcsh" name="h" /> 
															</div>
															<div class="col-md-4 col-xs-12">	
																<label class="pull-left"><?php echo __("W","apt");?></label> <input type="text" style="width:100%;" class="form-control" id="bdcsw" name="w" />
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
									<input name="companyimage" id="bdcsuploadedimg" type="hidden" value="<?php echo $apt_settings->appointment_company_logo;?>" />



											
											<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Company logo is used for invoicing purpose.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
								</tbody>
								
								<tfoot>
									<tr>
										<td></td>
										<td>
											<a href="javascript:void(0)" id="apt_save_company_settings" name="" class="btn btn-success" type="submit"><?php echo __("Save Setting","apt");?></a>
											<button type="reset" class="btn btn-default ml-30"><?php echo __("Default Setting","apt");?></button>
								
										</td>
									</tr>
								</tfoot>
							</table>	
						</div>
					</div>
				</form>
			</div>
			<!-- file upload preview -->
				<div class="apt-company-logo-popup-view">
					<div id="apt-image-upload-popup" class="apt-image-upload-popup modal fade" tabindex="-1" role="dialog">
						<div class="vertical-alignment-helper">
							<div class="modal-dialog modal-md vertical-align-center">
								<div class="modal-content">
									<div class="modal-header">
										<div class="col-md-12 col-xs-12">
											<button type="submit" class="btn btn-success"><?php echo __("Crop & Save","apt");?></button>
											<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true"><?php echo __("Cancel","apt");?></button>
										</div>	
									</div>
									<div class="modal-body">
										<img id="apt-preview-img" />
									</div>
									<div class="modal-footer">
										<div class="col-md-12 np">
											<div class="col-md-4 col-xs-12">
												<label class="pull-left"><?php echo __("File size","apt");?></label> <input type="text" class="form-control" id="filesize" name="filesize" />
											</div>	
											<div class="col-md-4 col-xs-12">	
												<label class="pull-left"><?php echo __("H","apt");?></label> <input type="text" class="form-control" id="h" name="h" /> 
											</div>
											<div class="col-md-4 col-xs-12">	
												<label class="pull-left"><?php echo __("W","apt");?></label> <input type="text" class="form-control" id="w" name="w" />
											</div>
										</div>

									</div>							
								</div>		
							</div>			
						</div>			
					</div>
				</div>
							
			<div class="tab-pane apt-toggle-abs" id="general-setting">
				<form id="" method="post" type="" class="apt-general-setting" >
					<div class="panel panel-default">
						<div class="panel-heading">
							<h1 class="panel-title"><?php echo __("General Settings","apt");?></h1>
						</div>
						<div class="panel-body">
							<table class="form-inline apt-common-table" >
								<tbody>	
									
									<tr>
										<td><label><?php echo __("Multi-Location","apt");?></label></td>
										<td>
											<div class="form-group">
												<label class="toggle-large" for="appointment_multi_location">
												
												<input <?php if($apt_settings->appointment_multi_location=='E') { echo ' checked '; }?> type="checkbox" id="appointment_multi_location" class="" name="ck"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
												</label>
											</div>
											<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Enable this option if you have multiple locations.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									
									
									<tr>
										<td><label><?php echo __("Zip Code Wise Booking","apt");?></label></td>
										<td>
											<div class="form-group">
												<label class="toggle-large" for="appointment_zipcode_booking">
												
												<input <?php if($apt_settings->appointment_zipcode_booking=='E') { echo ' checked '; }?> type="checkbox" id="appointment_zipcode_booking" class="" name="ck"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
												</label>
											</div>
											<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("You can get bookings by zip code.By enable this option","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									
									<tr id="appointment_booking_zipcodesetting" <?php if($apt_settings->appointment_zipcode_booking=='D'){?> class="hide-div" <?php } ?> >
									   <td><label><?php echo __("Zip Codes","apt");?></label></td>
									   <td>       
										<div class="form-group">
										 <label for="appointment_booking_zipcodes">
										  <textarea id="appointment_booking_zipcodes" class="form-control" cols="80" rows="6"><?php echo $apt_settings->appointment_booking_zipcodes; ?></textarea>
										 </label>
										</div>
										 <a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Add zip codes by comma separator value to provide booking in specific zip code areas.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
												
									   </td>
									</tr>
									<input type="hidden" value="<?php echo $apt_settings->appointment_booking_zipcodes; ?>" id="appointment_booking_zipcodes_hidd" />								
								
									<tr>
										<td><label><?php echo __("Time Interval","apt");?></label></td>
										<td>
											<div class="form-group">
												<select class="selectpicker" id="appointment_booking_time_interval" data-size="10"  style="display: none;">
												<option value=""><?php echo __("Set Booking Time Interval","apt");?></option>
												<option value="5" <?php if($apt_settings->appointment_booking_time_interval=='5') { echo ' selected '; }?>><?php echo __("5","apt");?> <?php echo __("Minutes","apt");?></option>
												<option value="10" <?php if($apt_settings->appointment_booking_time_interval=='10') { echo ' selected '; }?>><?php echo __("10","apt");?> <?php echo __("Minutes","apt");?></option>
												<option value="15" <?php if($apt_settings->appointment_booking_time_interval=='15') { echo ' selected '; }?>><?php echo __("15","apt");?> <?php echo __("Minutes","apt");?></option>
												<option value="20" <?php if($apt_settings->appointment_booking_time_interval=='20') { echo ' selected '; }?>><?php echo __("20","apt");?> <?php echo __("Minutes","apt");?></option>
												<option value="30" <?php if($apt_settings->appointment_booking_time_interval=='30') { echo ' selected '; }?>><?php echo __("30","apt");?> <?php echo __("Minutes","apt");?></option>
												<option value="45" <?php if($apt_settings->appointment_booking_time_interval=='45') { echo ' selected '; }?>><?php echo __("45","apt");?> <?php echo __("Minutes","apt");?></option>
												<option value="60" <?php if($apt_settings->appointment_booking_time_interval=='60') { echo ' selected '; }?>><?php echo __("1","apt");?> <?php echo __("Hour","apt");?></option>
												<option value="90" <?php if($apt_settings->appointment_booking_time_interval=='90') { echo ' selected '; }?>><?php echo __("1.5","apt");?> <?php echo __("Hours","apt");?></option>
												<option value="120" <?php if($apt_settings->appointment_booking_time_interval=='120') { echo ' selected '; }?>><?php echo __("2","apt");?> <?php echo __("Hours","apt");?></option>										<option value="180" <?php if($apt_settings->appointment_booking_time_interval=='180') { echo ' selected '; }?>><?php echo __("3","apt");?> <?php echo __("Hours","apt");?></option>										<option value="240" <?php if($apt_settings->appointment_booking_time_interval=='240') { echo ' selected '; }?>><?php echo __("4","apt");?> <?php echo __("Hours","apt");?></option>										<option value="300" <?php if($apt_settings->appointment_booking_time_interval=='300') { echo ' selected '; }?>><?php echo __("5","apt");?> <?php echo __("Hours","apt");?></option>										<option value="1439" <?php if($apt_settings->appointment_booking_time_interval=='1439') { echo ' selected '; }?>><?php echo __("1","apt");?> <?php echo __("Day","apt");?></option>
												</select>
											</div>	
											<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Time interval is helpful to show time difference between availability time slots.","apt"); ?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Minimum advance booking time","apt");?></label></td>
										<td>	
											<div class="form-group">
												<select class="selectpicker" id="appointment_minimum_advance_booking" data-size="10"  style="display: none;">
													<option value=""><?php echo __("Set Minimum advance booking time","apt");?></option>
													<option value="10" <?php if($apt_settings->appointment_minimum_advance_booking=='10') { echo ' selected '; }?>><?php echo __("10","apt");?> <?php echo __("Minutes","apt");?></option>
													<option value="20" <?php if($apt_settings->appointment_minimum_advance_booking=='20') { echo ' selected '; }?>><?php echo __("20","apt");?> <?php echo __("Minutes","apt");?></option>
													<option value="30" <?php if($apt_settings->appointment_minimum_advance_booking=='30') { echo ' selected '; }?>><?php echo __("30","apt");?> <?php echo __("Minutes","apt");?></option>
													<option value="60" <?php if($apt_settings->appointment_minimum_advance_booking=='60') { echo ' selected '; }?>><?php echo __("1","apt");?> <?php echo __("Hour","apt");?></option>
													<option value="120" <?php if($apt_settings->appointment_minimum_advance_booking=='120') { echo ' selected '; }?>><?php echo __("2","apt");?> <?php echo __("Hours","apt");?></option>
													<option value="180" <?php if($apt_settings->appointment_minimum_advance_booking=='180') { echo ' selected '; }?>><?php echo __("3","apt");?> <?php echo __("Hours","apt");?></option>
													<option value="240" <?php if($apt_settings->appointment_minimum_advance_booking=='240') { echo ' selected '; }?>><?php echo __("4","apt");?> <?php echo __("Hours","apt");?></option>
													<option value="300" <?php if($apt_settings->appointment_minimum_advance_booking=='300') { echo ' selected '; }?>><?php echo __("5","apt");?> <?php echo __("Hours","apt");?></option>
													<option value="360" <?php if($apt_settings->appointment_minimum_advance_booking=='360') { echo ' selected '; }?>><?php echo __("6","apt");?> <?php echo __("Hours","apt");?></option>
													<option value="420" <?php if($apt_settings->appointment_minimum_advance_booking=='420') { echo ' selected '; }?>><?php echo __("7","apt");?> <?php echo __("Hours","apt");?></option>
													<option value="480" <?php if($apt_settings->appointment_minimum_advance_booking=='480') { echo ' selected '; }?>><?php echo __("8","apt");?> <?php echo __("Hours","apt");?></option>
													<option value="720" <?php if($apt_settings->appointment_minimum_advance_booking=='720') { echo ' selected '; }?>><?php echo __("12","apt");?> <?php echo __("Hours","apt");?></option>
													<option value="1440" <?php if($apt_settings->appointment_minimum_advance_booking=='1440') { echo ' selected '; }?>><?php echo __("1","apt");?> <?php echo __("Day","apt");?></option>
													<option value="2880" <?php if($apt_settings->appointment_minimum_advance_booking=='2880') { echo ' selected '; }?>><?php echo __("2","apt");?> <?php echo __("Days","apt");?></option>
													<option value="4320" <?php if($apt_settings->appointment_minimum_advance_booking=='4320') { echo ' selected '; }?>><?php echo __("3","apt");?> <?php echo __("Days","apt");?></option>
													<option value="5760" <?php if($apt_settings->appointment_minimum_advance_booking=='5760') { echo ' selected '; }?>><?php echo __("4","apt");?> <?php echo __("Days","apt");?></option>
													<option value="7200" <?php if($apt_settings->appointment_minimum_advance_booking=='7200') { echo ' selected '; }?>><?php echo __("5","apt");?> <?php echo __("Day","apt");?></option>
													<option value="8640" <?php if($apt_settings->appointment_minimum_advance_booking=='8640') { echo ' selected '; }?>><?php echo __("6","apt");?> <?php echo __("Days","apt");?></option>
													<option value="10080" <?php if($apt_settings->appointment_minimum_advance_booking=='10080') { echo ' selected '; }?>><?php echo __("7","apt");?> <?php echo __("Days","apt");?></option>
												</select>
											</div>	
											<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Minimum advance booking time restrict client to book last minute booking, so that you should have sufficient time before appointment.","apt"); ?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Maximum advance booking time","apt");?></label></td>
										<td>
											<div class="form-group">
												<select id="appointment_maximum_advance_booking" class="selectpicker" data-size="10"  style="display: none;">
													<option value="1" <?php if($apt_settings->appointment_maximum_advance_booking==1) { echo ' selected '; } ?>  ><?php echo __("1","apt");?> <?php echo __("Month","apt");?></option>
													 <option value="2" <?php if($apt_settings->appointment_maximum_advance_booking==2) { echo ' selected '; } ?>  ><?php echo __("2","apt");?> <?php echo __("Months","apt");?></option>
													 <option value="3" <?php if($apt_settings->appointment_maximum_advance_booking==3) { echo ' selected '; } ?>  ><?php echo __("3","apt");?> <?php echo __("Months","apt");?></option>
													 <option value="4" <?php if($apt_settings->appointment_maximum_advance_booking==4) { echo ' selected '; } ?>  ><?php echo __("4","apt");?> <?php echo __("Months","apt");?></option>
													 <option value="5" <?php if($apt_settings->appointment_maximum_advance_booking==5) { echo ' selected '; } ?>  ><?php echo __("5","apt");?> <?php echo __("Months","apt");?></option>
													 <option value="6" <?php if($apt_settings->appointment_maximum_advance_booking==6) { echo ' selected '; } ?>  ><?php echo __("6","apt");?> <?php echo __("Months","apt");?></option>
													 <option value="7" <?php if($apt_settings->appointment_maximum_advance_booking==7) { echo ' selected '; } ?>  ><?php echo __("7","apt");?> <?php echo __("Months","apt");?></option>
													 <option value="8" <?php if($apt_settings->appointment_maximum_advance_booking==8) { echo ' selected '; } ?>  ><?php echo __("8","apt");?> <?php echo __("Months","apt");?></option>
													 <option value="9" <?php if($apt_settings->appointment_maximum_advance_booking==9) { echo ' selected '; } ?>  ><?php echo __("9","apt");?> <?php echo __("Months","apt");?></option>
													 <option value="10" <?php if($apt_settings->appointment_maximum_advance_booking==10) { echo ' selected '; } ?>  ><?php echo __("10","apt");?> <?php echo __("Months","apt");?></option>
													 <option value="11" <?php if($apt_settings->appointment_maximum_advance_booking==11) { echo ' selected '; } ?>  ><?php echo __("11","apt");?> <?php echo __("Months","apt");?></option>
													<option value="12" <?php if($apt_settings->appointment_maximum_advance_booking==12) { echo ' selected '; } ?>  ><?php echo __("1","apt");?> <?php echo __("year","apt");?></option>
													 <option value="24" <?php if($apt_settings->appointment_maximum_advance_booking==24) { echo ' selected '; } ?>  ><?php echo __("2","apt");?> <?php echo __("years","apt");?></option>
													 <option value="36" <?php if($apt_settings->appointment_maximum_advance_booking==36) { echo ' selected '; } ?>  ><?php echo __("3","apt");?> <?php echo __("years","apt");?></option>
												</select>
											</div>	
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Booking Padding Time","apt");?></label></td>
										<td>
											<div class="form-group">
												<select id="appointment_booking_padding_time" class="selectpicker" data-size="10"  style="display: none;">
													<option value=""><?php echo __("Set Booking Padding Time","apt");?></option>					
													<option value="10" <?php if($apt_settings->appointment_booking_padding_time=='10') { echo ' selected '; }?> ><?php echo __("10","apt");?> <?php echo __("Minutes","apt");?></option>
													<option value="20" <?php if($apt_settings->appointment_booking_padding_time=='20') { echo ' selected '; }?> ><?php echo __("20","apt");?> <?php echo __("Minutes","apt");?></option>
													<option value="30" <?php if($apt_settings->appointment_booking_padding_time=='30') { echo ' selected '; }?> ><?php echo __("30","apt");?> <?php echo __("Minutes","apt");?></option>
													<option value="45" <?php if($apt_settings->appointment_booking_padding_time=='45') { echo ' selected '; }?> ><?php echo __("45","apt");?> <?php echo __("Minutes","apt");?></option>
													<option value="60" <?php if($apt_settings->appointment_booking_padding_time=='60') { echo ' selected '; }?> ><?php echo __("60","apt");?> <?php echo __("Minutes","apt");?></option>
												</select>
											</div>	
											<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Booking Padding time is time span that you need after each appointment to get prepare or to take rest.","apt"); ?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Cancellation Buffer Time","apt");?></label></td>
										<td>
											<div class="form-group">
												<select id="appointment_cancellation_buffer_time" class="selectpicker" data-size="10"  style="display: none;">
													<option value=""><?php echo __("Set cancellation buffer time","apt");?></option>
													<option value="60" <?php if($apt_settings->appointment_cancellation_buffer_time=='60') { echo ' selected '; }?> ><?php echo __("1","apt");?> <?php echo __("Hour","apt");?></option>
													<option value="120" <?php if($apt_settings->appointment_cancellation_buffer_time=='120') { echo ' selected '; }?> ><?php echo __("2","apt");?> <?php echo __("Hours","apt");?></option>
													<option value="180" <?php if($apt_settings->appointment_cancellation_buffer_time=='180') { echo ' selected '; }?> ><?php echo __("3","apt");?> <?php echo __("Hours","apt");?></option>
													<option value="240" <?php if($apt_settings->appointment_cancellation_buffer_time=='240') { echo ' selected '; }?> ><?php echo __("4","apt");?> <?php echo __("Hours","apt");?></option>
													<option value="300" <?php if($apt_settings->appointment_cancellation_buffer_time=='300') { echo ' selected '; }?> ><?php echo __("5","apt");?> <?php echo __("Hours","apt");?></option>
													<option value="360" <?php if($apt_settings->appointment_cancellation_buffer_time=='360') { echo ' selected '; }?> ><?php echo __("6","apt");?> <?php echo __("Hours","apt");?></option>
													<option value="420" <?php if($apt_settings->appointment_cancellation_buffer_time=='420') { echo ' selected '; }?> ><?php echo __("7","apt");?> <?php echo __("Hours","apt");?></option>
													<option value="480" <?php if($apt_settings->appointment_cancellation_buffer_time=='480') { echo ' selected '; }?> ><?php echo __("8","apt");?> <?php echo __("Hours","apt");?></option>
													<option value="720" <?php if($apt_settings->appointment_cancellation_buffer_time=='720') { echo ' selected '; }?> ><?php echo __("12","apt");?> <?php echo __("Hours","apt");?></option>
													<option value="1440" <?php if($apt_settings->appointment_cancellation_buffer_time=='1440') { echo ' selected '; }?> ><?php echo __("24","apt");?> <?php echo __("Hours","apt");?></option>
												</select>
											</div>	
											<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Cancellation buffer helps service providers to avoid last minute cancellation by their clients. ","apt"); ?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Reschedule Buffer Time","apt");?></label></td>
										<td>
											<div class="form-group">
												<select id="appointment_reschedule_buffer_time" class="selectpicker" data-size="10"  style="display: none;">
													<option value=""><?php echo __("Set reschedule buffer time","apt");?></option>
													<option value="60" <?php if($apt_settings->appointment_reschedule_buffer_time=='60') { echo ' selected '; }?> ><?php echo __("1","apt");?> <?php echo __("Hour","apt");?></option>
													<option value="120" <?php if($apt_settings->appointment_reschedule_buffer_time=='120') { echo ' selected '; }?> ><?php echo __("2","apt");?> <?php echo __("Hours","apt");?></option>
													<option value="180" <?php if($apt_settings->appointment_reschedule_buffer_time=='180') { echo ' selected '; }?> ><?php echo __("3","apt");?> <?php echo __("Hours","apt");?></option>
													<option value="240" <?php if($apt_settings->appointment_reschedule_buffer_time=='240') { echo ' selected '; }?> ><?php echo __("4","apt");?> <?php echo __("Hours","apt");?></option>
													<option value="300" <?php if($apt_settings->appointment_reschedule_buffer_time=='300') { echo ' selected '; }?> ><?php echo __("5","apt");?> <?php echo __("Hours","apt");?></option>
													<option value="360" <?php if($apt_settings->appointment_reschedule_buffer_time=='360') { echo ' selected '; }?> ><?php echo __("6","apt");?> <?php echo __("Hours","apt");?></option>
													<option value="420" <?php if($apt_settings->appointment_reschedule_buffer_time=='420') { echo ' selected '; }?> ><?php echo __("7","apt");?> <?php echo __("Hours","apt");?></option>
													<option value="480" <?php if($apt_settings->appointment_reschedule_buffer_time=='480') { echo ' selected '; }?> ><?php echo __("8","apt");?> <?php echo __("Hours","apt");?></option>
													<option value="720" <?php if($apt_settings->appointment_reschedule_buffer_time=='720') { echo ' selected '; }?> ><?php echo __("12","apt");?> <?php echo __("Hours","apt");?></option>
													<option value="1440" <?php if($apt_settings->appointment_reschedule_buffer_time=='1440') { echo ' selected '; }?> ><?php echo __("24","apt");?> <?php echo __("Hours","apt");?></option>
												</select>
											</div>	
											<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Reschedule buffer helps service providers to avoid last minute reschedule by their clients. ","apt"); ?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Currency","apt");?></label></td>
										<td>
											<div class="form-group">
												<select id="appointment_currency" class="selectpicker form-control" data-live-search="true" data-live-search-placeholder="Search" data-actions-box="true"  >
													<option value=""><?php echo __("-- Select Currency --","apt");?></option>
												  <option value="ALL" <?php if($apt_settings->appointment_currency =='ALL' ){ echo ' selected '; }?>>Lek <?php echo "Albania Lek";?></option>
												  <option value="AED" <?php if($apt_settings->appointment_currency =='AED' ){ echo ' selected '; }?>>د.إ <?php echo "UAE Dirham";?></option>
												  <option value="AFN" <?php if($apt_settings->appointment_currency =='AFN' ){ echo ' selected '; }?>>؋ <?php echo "Afghanistan Afghani";?></option>
												  <option value="ARS" <?php if($apt_settings->appointment_currency =='ARS' ){ echo ' selected '; }?>>$ <?php echo "Argentina Peso";?></option>
												  <option value="ANG" <?php if($apt_settings->appointment_currency =='ANG' ){ echo ' selected '; }?>>NAƒ <?php echo "Neth Antilles Guilder";?></option>  
												  <option value="AWG" <?php if($apt_settings->appointment_currency =='AWG' ){ echo ' selected '; }?>>ƒ <?php echo "Aruba Guilder";?></option>
												  <option value="AUD" <?php if($apt_settings->appointment_currency =='AUD' ){ echo ' selected '; }?>>$ <?php echo "Australia Dollar";?></option>
												  <option value="AZN" <?php if($apt_settings->appointment_currency =='AZN' ){ echo ' selected '; }?>>ман <?php echo "Azerbaijan Manat";?></option>
												  <option value="BSD" <?php if($apt_settings->appointment_currency =='BSD' ){ echo ' selected '; }?>>$ <?php echo "Bahamas Dollar";?></option>
												  <option value="BBD" <?php if($apt_settings->appointment_currency =='BBD' ){ echo ' selected '; }?>>$ <?php echo "Barbados Dollar";?></option>
												  <option value="BYR" <?php if($apt_settings->appointment_currency =='BYR' ){ echo ' selected '; }?>>p <?php echo "Belarus Ruble";?></option>
												  <option value="BZD" <?php if($apt_settings->appointment_currency =='BZD' ){ echo ' selected '; }?>>BZ$ <?php echo "Belize Dollar";?></option>
												  <option value="BMD" <?php if($apt_settings->appointment_currency =='BMD' ){ echo ' selected '; }?>>$ <?php echo "Bermuda Dollar";?></option>					  
												  <option value="BOB" <?php if($apt_settings->appointment_currency =='BOB' ){ echo ' selected '; }?>>$b <?php echo "Bolivia	Boliviano";?></option>
												  <option value="BAM" <?php if($apt_settings->appointment_currency =='BAM' ){ echo ' selected '; }?>>KM <?php echo "Bosnia and Herzegovina Convertible Marka";?></option>
												  <option value="BWP" <?php if($apt_settings->appointment_currency =='BWP' ){ echo ' selected '; }?>>P <?php echo "Botswana Pula";?></option>
												  <option value="BGN" <?php if($apt_settings->appointment_currency =='BGN' ){ echo ' selected '; }?>>лв <?php echo "Bulgaria Lev";?></option>
												  <option value="BRL" <?php if($apt_settings->appointment_currency =='BRL' ){ echo ' selected '; }?>>R$ <?php echo "Brazil Real";?></option>
												  <option value="BND" <?php if($apt_settings->appointment_currency =='BND' ){ echo ' selected '; }?>>$ <?php echo "Brunei Darussalam Dollar";?></option>
												  
												  <option value="BDT" <?php if($apt_settings->appointment_currency =='BDT' ){ echo ' selected '; }?>>Tk <?php echo "Bangladesh Taka";?></option>
												  <option value="BIF" <?php if($apt_settings->appointment_currency =='BIF' ){ echo ' selected '; }?>>FBu <?php echo "Burundi Franc";?></option>
												  
												  <option value="CHF" <?php if($apt_settings->appointment_currency =='CHF' ){ echo ' selected '; }?>>CHF<?php echo "Swiss Franc";?></option>
												  
												  
												  <option value="KHR" <?php if($apt_settings->appointment_currency =='KHR' ){ echo ' selected '; }?>>៛  <?php echo "Cambodia Riel";?></option>
												  <option value="KMF" <?php if($apt_settings->appointment_currency =='KMF' ){ echo ' selected '; }?>>KMF <?php echo "Comoros Franc";?></option>
												  
												  <option value="CAD" <?php if($apt_settings->appointment_currency =='CAD' ){ echo ' selected '; }?>>$ <?php echo "Canada Dollar";?></option>
												  <option value="KYD" <?php if($apt_settings->appointment_currency =='KYD' ){ echo ' selected '; }?>>$ <?php echo "Cayman Dollar";?></option>
												  
												  <option value="CLP" <?php if($apt_settings->appointment_currency =='CLP' ){ echo ' selected '; }?>>$ <?php echo "Chile Peso";?></option>
												  <option value="CYN" <?php if($apt_settings->appointment_currency =='CYN' ){ echo ' selected '; }?>>¥ <?php echo "China Yuan Renminbi";?></option>
												  
												  <option value="CVE" <?php if($apt_settings->appointment_currency =='CVE' ){ echo ' selected '; }?>>Esc <?php echo "Cape Verde Escudo";?></option>
												  
												  <option value="COP" <?php if($apt_settings->appointment_currency =='COP' ){ echo ' selected '; }?>>$ <?php echo "Colombia Peso";?></option>
												  <option value="CRC" <?php if($apt_settings->appointment_currency =='CRC' ){ echo ' selected '; }?>>₡ <?php echo "Costa Rica Colon";?></option>
												  <option value="HRK" <?php if($apt_settings->appointment_currency =='HRK' ){ echo ' selected '; }?>>kn <?php echo "Croatia	Kuna";?></option>
												  <option value="CUP" <?php if($apt_settings->appointment_currency =='CUP' ){ echo ' selected '; }?>>₱ <?php echo "Cuba Peso";?></option>
												  <option value="CZK" <?php if($apt_settings->appointment_currency =='CZK' ){ echo ' selected '; }?>>Kč <?php echo "Czech Republic Koruna";?></option>
												 <option value="DKK" <?php if($apt_settings->appointment_currency =='DKK' ){ echo ' selected '; }?>>kr <?php echo "Denmark	Krone";?></option>
												 <option value="DOP" <?php if($apt_settings->appointment_currency =='DOP' ){ echo ' selected '; }?>>RD$ <?php echo "Dominican Republic Peso";?></option>
												 <option value="DJF" <?php if($apt_settings->appointment_currency =='DJF' ){ echo ' selected '; }?>>Fdj <?php echo "Djibouti Franc";?></option>
												 <option value="DZD" <?php if($apt_settings->appointment_currency =='DZD' ){ echo ' selected '; }?>>دج <?php echo "Algerian Dinar";?></option>
												 <option value="XCD" <?php if($apt_settings->appointment_currency =='XCD' ){ echo ' selected '; }?>>$  <?php echo "East Caribbean Dollar";?></option>
												 <option value="EGP" <?php if($apt_settings->appointment_currency =='EGP' ){ echo ' selected '; }?>>£ <?php echo "Egypt Pound";?></option>
												 <option value="ETB" <?php if($apt_settings->appointment_currency =='ETB' ){ echo ' selected '; }?>>Br <?php echo "Ethiopian Birr";?></option>
												 <option value="SVC" <?php if($apt_settings->appointment_currency =='SVC' ){ echo ' selected '; }?>>$  <?php echo "El Salvador Colon";?></option>
												 <option value="EEK" <?php if($apt_settings->appointment_currency =='EEK' ){ echo ' selected '; }?>>kr <?php echo "Estonia Kroon";?></option>
												 <option value="EUR" <?php if($apt_settings->appointment_currency =='EUR' ){ echo ' selected '; }?>>€  <?php echo "Euro Member Euro";?></option>
												 <option value="FKP" <?php if($apt_settings->appointment_currency =='FKP' ){ echo ' selected '; }?>>£ <?php echo "Falkland Islands Pound";?></option>
												 <option value="FJD" <?php if($apt_settings->appointment_currency =='FJD' ){ echo ' selected '; }?>>$  <?php echo "Fiji Dollar";?></option>
												 <option value="GHC" <?php if($apt_settings->appointment_currency =='GHC' ){ echo ' selected '; }?>>¢ <?php echo "Ghana Cedis";?></option>
												 <option value="GIP" <?php if($apt_settings->appointment_currency =='GIP' ){ echo ' selected '; }?>>£ <?php echo "Gibraltar Pound";?></option>
												 <option value="GMD" <?php if($apt_settings->appointment_currency =='GMD' ){ echo ' selected '; }?>>D <?php echo "Gambian Dalasi";?></option>
												 <option value="GNF" <?php if($apt_settings->appointment_currency =='GNF' ){ echo ' selected '; }?>>FG <?php echo "Guinea Franc";?></option>
												 <option value="GTQ" <?php if($apt_settings->appointment_currency =='GTQ' ){ echo ' selected '; }?>>Q <?php echo "Guatemala Quetzal";?></option>
												 <option value="GGP" <?php if($apt_settings->appointment_currency =='GGP' ){ echo ' selected '; }?>>£ <?php echo "Guernsey Pound";?></option>
												 <option value="GYD" <?php if($apt_settings->appointment_currency =='GYD' ){ echo ' selected '; }?>>$ <?php echo "Guyana Dollar";?></option>
											  <option value="HNL" <?php if($apt_settings->appointment_currency =='HNL' ){ echo ' selected '; }?>>L <?php echo "Honduras Lempira";?></option>
											  <option value="HKD" <?php if($apt_settings->appointment_currency =='HKD' ){ echo ' selected '; }?>>$ <?php echo "Hong Kong Dollar";?></option>
											  
											  <option value="HRK" <?php if($apt_settings->appointment_currency =='HRK' ){ echo ' selected '; }?>>kn <?php echo "Croatian Kuna";?></option>
											  <option value="HTG" <?php if($apt_settings->appointment_currency =='HTG' ){ echo ' selected '; }?>>G <?php echo "Haitian Gourde";?></option>
											  <option value="HUF" <?php if($apt_settings->appointment_currency =='HUF' ){ echo ' selected '; }?>>Ft <?php echo "Hungary	Forint";?></option>
											  <option value="ISK" <?php if($apt_settings->appointment_currency =='ISK' ){ echo ' selected '; }?>>kr <?php echo "Iceland	Krona";?></option>
											  <option value="INR" <?php if($apt_settings->appointment_currency =='INR' ){ echo ' selected '; }?>>Rs <?php echo "India Rupee";?></option>
											  <option value="IDR" <?php if($apt_settings->appointment_currency =='IDR' ){ echo ' selected '; }?>>Rp <?php echo "Indonesia Rupiah";?></option>
											  <option value="IRR" <?php if($apt_settings->appointment_currency =='IRR' ){ echo ' selected '; }?>>﷼ <?php echo "Iran Rial";?></option>
											  <option value="IMP" <?php if($apt_settings->appointment_currency =='IMP' ){ echo ' selected '; }?>>£ <?php echo "Isle of Man Pound";?></option>
											  <option value="ILS" <?php if($apt_settings->appointment_currency =='ILS' ){ echo ' selected '; }?>>₪ <?php echo "Israel Shekel";?></option>
											  <option value="JMD" <?php if($apt_settings->appointment_currency =='JMD' ){ echo ' selected '; }?>>J$ <?php echo "Jamaica Dollar";?></option>
											  <option value="JPY" <?php if($apt_settings->appointment_currency =='JPY' ){ echo ' selected '; }?>>¥ <?php echo "Japan Yen";?></option>
											  <option value="JEP" <?php if($apt_settings->appointment_currency =='JEP' ){ echo ' selected '; }?>>£ <?php echo "Jersey Pound";?></option>
											  <option value="KZT" <?php if($apt_settings->appointment_currency =='KZT' ){ echo ' selected '; }?>>лв <?php echo "Kazakhstan Tenge";?></option>
											  <option value="KPW" <?php if($apt_settings->appointment_currency =='KPW' ){ echo ' selected '; }?>>₩ <?php echo "Korea(North) Won";?></option>
											  <option value="KRW" <?php if($apt_settings->appointment_currency =='KRW' ){ echo ' selected '; }?>>₩ <?php echo "Korea(South) Won";?></option>
											  <option value="KGS" <?php if($apt_settings->appointment_currency =='KGS' ){ echo ' selected '; }?>>лв <?php echo "Kyrgyzstan Som";?></option>
											  <option value="KES" <?php if($apt_settings->appointment_currency =='KES' ){ echo ' selected '; }?>>KSh <?php echo "Kenyan Shilling";?></option>
												<option value="LAK" <?php if($apt_settings->appointment_currency =='LAK' ){ echo ' selected '; }?>>₭ <?php echo "Laos	Kip";?></option>
												<option value="LVL" <?php if($apt_settings->appointment_currency =='LVL' ){ echo ' selected '; }?>>Ls <?php echo "Latvia Lat";?></option>
												<option value="LBP" <?php if($apt_settings->appointment_currency =='LBP' ){ echo ' selected '; }?>>£ <?php echo "Lebanon Pound";?></option>
												<option value="LRD" <?php if($apt_settings->appointment_currency =='LRD' ){ echo ' selected '; }?>>$ <?php echo "Liberia Dollar";?></option>
												<option value="LTL" <?php if($apt_settings->appointment_currency =='LTL' ){ echo ' selected '; }?>>Lt <?php echo "Lithuania Litas";?></option>
												<option value="MKD" <?php if($apt_settings->appointment_currency =='MKD' ){ echo ' selected '; }?>>ден <?php echo "Macedonia Denar";?>	</option>
												<option value="MYR" <?php if($apt_settings->appointment_currency =='MYR' ){ echo ' selected '; }?>>RM <?php echo "Malaysia Ringgit";?></option>
												<option value="MUR" <?php if($apt_settings->appointment_currency =='MUR' ){ echo ' selected '; }?>>₨ <?php echo "Mauritius Rupee";?></option>
												<option value="MXN" <?php if($apt_settings->appointment_currency =='MXN' ){ echo ' selected '; }?>>$ <?php echo "Mexico Peso";?></option>
												<option value="MNT" <?php if($apt_settings->appointment_currency =='MNT' ){ echo ' selected '; }?>>₮ <?php echo "Mongolia Tughrik";?></option>
												<option value="MZN" <?php if($apt_settings->appointment_currency =='MZN' ){ echo ' selected '; }?>>MT <?php echo "Mozambique Metical";?></option>
												<option value="MAD" <?php if($apt_settings->appointment_currency =='MAD' ){ echo ' selected '; }?>>د.م. <?php echo "Moroccan Dirham";?></option>
												<option value="MDL" <?php if($apt_settings->appointment_currency =='MDL' ){ echo ' selected '; }?>>MDL <?php echo "Moldovan Leu";?></option>
												<option value="MOP" <?php if($apt_settings->appointment_currency =='MOP' ){ echo ' selected '; }?>>$ <?php echo "Macau Pataca";?></option>
												<option value="MRO" <?php if($apt_settings->appointment_currency =='MRO' ){ echo ' selected '; }?>>UM <?php echo "Mauritania Ougulya";?></option>
												<option value="MVR" <?php if($apt_settings->appointment_currency =='MVR' ){ echo ' selected '; }?>>Rf <?php echo "Maldives Rufiyaa";?></option>
												<option value="PGK" <?php if($apt_settings->appointment_currency =='PGK' ){ echo ' selected '; }?>>K <?php echo "Papua New Guinea Kina";?></option>
												<option value="NAD" <?php if($apt_settings->appointment_currency =='NAD' ){ echo ' selected '; }?>>$ <?php echo "Namibia Dollar";?></option>
												<option value="NPR" <?php if($apt_settings->appointment_currency =='NPR' ){ echo ' selected '; }?>>₨ <?php echo "Nepal Rupee";?></option>
												<option value="ANG" <?php if($apt_settings->appointment_currency =='ANG' ){ echo ' selected '; }?>>ƒ <?php echo "Netherlands Antilles Guilder";?></option>
												<option value="NZD" <?php if($apt_settings->appointment_currency =='NZD' ){ echo ' selected '; }?>>$ <?php echo "New Zealand Dollar";?></option>
												<option value="NIO" <?php if($apt_settings->appointment_currency =='NIO' ){ echo ' selected '; }?>>C$ <?php echo "Nicaragua Cordoba";?></option>
												<option value="NGN" <?php if($apt_settings->appointment_currency =='NGN' ){ echo ' selected '; }?>>₦ <?php echo "Nigeria Naira";?></option>
												<option value="NOK" <?php if($apt_settings->appointment_currency =='NOK' ){ echo ' selected '; }?>>kr <?php echo "Norway Krone";?></option>
												<option value="OMR" <?php if($apt_settings->appointment_currency =='OMR' ){ echo ' selected '; }?>>﷼ <?php echo "Oman Rial";?></option>
												<option value="MWK" <?php if($apt_settings->appointment_currency =='MWK' ){ echo ' selected '; }?>>MK <?php echo "Malawi Kwacha";?></option>
											<option value="PKR" <?php if($apt_settings->appointment_currency =='PKR' ){ echo ' selected '; }?>>₨ <?php echo "Pakistan Rupee";?></option>
											<option value="PAB" <?php if($apt_settings->appointment_currency =='PAB' ){ echo ' selected '; }?>>B/ <?php echo "Panama Balboa";?></option>
											<option value="PYG" <?php if($apt_settings->appointment_currency =='PYG' ){ echo ' selected '; }?>>Gs <?php echo "Paraguay Guarani";?></option>
											<option value="PEN" <?php if($apt_settings->appointment_currency =='PEN' ){ echo ' selected '; }?>>S/ <?php echo "Peru Nuevo Sol";?></option>
											<option value="PHP" <?php if($apt_settings->appointment_currency =='PHP' ){ echo ' selected '; }?>>₱ <?php echo "Philippines Peso";?></option>
											<option value="PLN" <?php if($apt_settings->appointment_currency =='PLN' ){ echo ' selected '; }?>>zł <?php echo "Poland Zloty";?></option>
											<option value="QAR" <?php if($apt_settings->appointment_currency =='QAR' ){ echo ' selected '; }?>>﷼ <?php echo "Qatar Riyal";?></option>
											<option value="RON" <?php if($apt_settings->appointment_currency =='RON' ){ echo ' selected '; }?>>lei <?php echo "Romania New Leu";?></option>
											<option value="RUB" <?php if($apt_settings->appointment_currency =='RUB' ){ echo ' selected '; }?>>руб <?php echo "Russia Ruble";?></option>
											<option value="SHP" <?php if($apt_settings->appointment_currency =='SHP' ){ echo ' selected '; }?>>£ <?php echo "Saint Helena Pound";?></option>
											<option value="SAR" <?php if($apt_settings->appointment_currency =='SAR' ){ echo ' selected '; }?>>﷼ <?php echo "Saudi Arabia	Riyal";?></option>
											<option value="RSD" <?php if($apt_settings->appointment_currency =='RSD' ){ echo ' selected '; }?>>Дин <?php echo "Serbia Dinar";?></option>
											<option value="SCR" <?php if($apt_settings->appointment_currency =='SCR' ){ echo ' selected '; }?>>₨ <?php echo "Seychelles Rupee";?></option>
											<option value="SGD" <?php if($apt_settings->appointment_currency =='SGD' ){ echo ' selected '; }?>>$ <?php echo "Singapore	Dollar";?></option>
											<option value="SBD" <?php if($apt_settings->appointment_currency =='SBD' ){ echo ' selected '; }?>>$ <?php echo "Solomon Islands Dollar";?></option>
											<option value="SOS" <?php if($apt_settings->appointment_currency =='SOS' ){ echo ' selected '; }?>>S <?php echo "Somalia Shilling";?></option>
											<option value="SLL" <?php if($apt_settings->appointment_currency =='SLL' ){ echo ' selected '; }?>>Le <?php echo "Sierra Leone Leone";?></option>
											<option value="STD" <?php if($apt_settings->appointment_currency =='STD' ){ echo ' selected '; }?>>Db <?php echo "Sao Tome Dobra";?></option>
											<option value="SZL" <?php if($apt_settings->appointment_currency =='SZL' ){ echo ' selected '; }?>>SZL <?php echo "Swaziland Lilageni";?></option>
											<option value="ZAR" <?php if($apt_settings->appointment_currency =='ZAR' ){ echo ' selected '; }?>>R <?php echo "South Africa Rand";?></option>
											<option value="LKR" <?php if($apt_settings->appointment_currency =='LKR' ){ echo ' selected '; }?>>₨ <?php echo "Sri Lanka Rupee";?></option>
											<option value="SEK" <?php if($apt_settings->appointment_currency =='SEK' ){ echo ' selected '; }?>>kr <?php echo "Sweden Krona";?></option>
											<option value="CHF" <?php if($apt_settings->appointment_currency =='CHF' ){ echo ' selected '; }?>>CHF <?php echo "Switzerland Franc";?> </option>
											<option value="SRD" <?php if($apt_settings->appointment_currency =='SRD' ){ echo ' selected '; }?>>$ <?php echo "Suriname Dollar";?></option>
											<option value="SYP" <?php if($apt_settings->appointment_currency =='SYP' ){ echo ' selected '; }?>>£ <?php echo "Syria	Pound";?></option>
											<option value="TWD" <?php if($apt_settings->appointment_currency =='TWD' ){ echo ' selected '; }?>>NT <?php echo "Taiwan New Dollar";?></option>
											<option value="THB" <?php if($apt_settings->appointment_currency =='THB' ){ echo ' selected '; }?>>฿ <?php echo "Thailand Baht";?></option>
											<option value="TOP" <?php if($apt_settings->appointment_currency =='TOP' ){ echo ' selected '; }?>>T$ <?php echo "Tonga Pa'ang";?></option>
											<option value="TZS" <?php if($apt_settings->appointment_currency =='TZS' ){ echo ' selected '; }?>>x <?php echo "Tanzanian Shilling";?></option>
											<option value="TTD" <?php if($apt_settings->appointment_currency =='TTD' ){ echo ' selected '; }?>>TTD <?php echo "Trinidad and Tobago Dollar";?></option>
											<option value="TRY" <?php if($apt_settings->appointment_currency =='TRY' ){ echo ' selected '; }?>>₤ <?php echo "Turkey Lira";?></option>
											<option value="TVD" <?php if($apt_settings->appointment_currency =='TVD' ){ echo ' selected '; }?>>$ <?php echo "Tuvalu Dollar";?></option>
											<option value="UAH" <?php if($apt_settings->appointment_currency =='UAH' ){ echo ' selected '; }?>>₴ <?php echo "Ukraine Hryvna";?></option>
											<option value="UGX" <?php if($apt_settings->appointment_currency =='UGX' ){ echo ' selected '; }?>>USh <?php echo "Ugandan Shilling";?></option>
											<option value="GBP" <?php if($apt_settings->appointment_currency =='GBP' ){ echo ' selected '; }?>>£ <?php echo "United Kingdom Pound";?></option>
											<option value="USD" <?php if($apt_settings->appointment_currency =='USD' ){ echo ' selected '; }?>>$ <?php echo "United States	Dollar";?></option>
											<option value="UYU" <?php if($apt_settings->appointment_currency =='UYU' ){ echo ' selected '; }?>>$U <?php echo "Uruguay Peso";?></option>
											<option value="UZS" <?php if($apt_settings->appointment_currency =='UZS' ){ echo ' selected '; }?>>лв <?php echo "Uzbekistan Som";?></option>
											<option value="VEF" <?php if($apt_settings->appointment_currency =='VEF' ){ echo ' selected '; }?>>Bs <?php echo "Venezuela Bolivar Fuerte";?></option>
											<option value="VND" <?php if($apt_settings->appointment_currency =='VND' ){ echo ' selected '; }?>>₫ <?php echo "Viet Nam Dong";?></option>
											<option value="VUV" <?php if($apt_settings->appointment_currency =='VUV' ){ echo ' selected '; }?>>Vt <?php echo "Vanuatu Vatu";?></option>
											<option value="XAF" <?php if($apt_settings->appointment_currency =='XAF' ){ echo ' selected '; }?>>BEAC <?php echo "CFA Franc (BEAC)";?></option>
											<option value="XOF" <?php if($apt_settings->appointment_currency =='XOF' ){ echo ' selected '; }?>>BCEAO <?php echo "CFA Franc (BCEAO)";?></option>
											<option value="XPF" <?php if($apt_settings->appointment_currency =='XPF' ){ echo ' selected '; }?>>F <?php echo "Pacific Franc";?></option>
											<option value="YER" <?php if($apt_settings->appointment_currency =='YER' ){ echo ' selected '; }?>>﷼ <?php echo "Yemen	Rial";?></option>
											<option value="WST" <?php if($apt_settings->appointment_currency =='WST' ){ echo ' selected '; }?>>WS$ <?php echo "Samoa Tala";?></option>
											<option value="ZAR" <?php if($apt_settings->appointment_currency =='ZAR' ){ echo ' selected '; }?>>R <?php echo "South African Rand";?></option>
											<option value="ZWD" <?php if($apt_settings->appointment_currency =='ZWD' ){ echo ' selected '; }?>>Z$ <?php echo "Zimbabwe Dollar";?></option>
												</select>
											</div>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Currency symbol position","apt");?></label></td>
										<td>
											<div class="form-group">
												<select id="appointment_currency_symbol_position" class="selectpicker" data-size="10"  style="display: none;">
													<option value="B"  <?php if($apt_settings->appointment_currency_symbol_position!='A') { echo " selected "; }?>  ><?php echo __("Before","apt");?>&nbsp;&nbsp;(e.g.&nbsp;<?php echo $apt_settings->appointment_currency_symbol;?>100)</option>
													<option value="A" <?php if($apt_settings->appointment_currency_symbol_position=='A') { echo " selected "; }?> ><?php echo __("After","apt");?>&nbsp;&nbsp;(e.g.&nbsp;100<?php echo $apt_settings->appointment_currency_symbol;?>)</option>
												</select>
											</div>	
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Price format decimal Places","apt");?></label></td>
										<td>
											<div class="form-group">
												<select id="appointment_price_format_decimal_places" class="selectpicker" data-size="10"  style="display: none;">
													<option value="0" <?php if($apt_settings->appointment_price_format_decimal_places=='0') { echo ' selected ';}?> ><?php echo __("0 (e.g.$100)","apt");?></option>
													<option value="1" <?php if($apt_settings->appointment_price_format_decimal_places=='1') { echo ' selected ';}?> ><?php echo __("1 (e.g.$100.0)","apt");?></option>
													<option value="2" <?php if($apt_settings->appointment_price_format_decimal_places=='2') { echo ' selected ';}?> ><?php echo __("2 (e.g.$100.00)","apt");?></option>
													<option value="3" <?php if($apt_settings->appointment_price_format_decimal_places=='3') { echo ' selected ';}?> ><?php echo __("3 (e.g.$100.000)","apt");?></option>
													<option value="4" <?php if($apt_settings->appointment_price_format_decimal_places=='4') { echo ' selected ';}?> ><?php echo __("4 (e.g.$100.0000)","apt");?></option>	
												</select>
											</div>	
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Price format comma separator","apt");?></label></td>
										<td>
											<div class="form-group">
												<select id="appointment_price_format_comma_separator"  class="selectpicker" data-size="10"  style="display: none;" >
												<option value="N" <?php if($apt_settings->appointment_price_format_comma_separator=='N') { echo ' selected ';}?> ><?php echo __("No","apt");?><?php echo __("(e.g. 1000.00)","apt");?> </option>
												<option value="Y" <?php if($apt_settings->appointment_price_format_comma_separator=='Y') { echo ' selected ';}?> ><?php echo __("Yes","apt");?> <?php echo __("(e.g. 1,000.00)","apt");?></option>						
												</select>
											</div>	
										</td>
									</tr>
									
									<tr>
										<td><label><?php echo __("Location sorting by","apt");?></label></td>
										<td>
											<div class="form-group">
												<select id="appointment_location_sortby" class="selectpicker" data-size="10"  style="display: none;">
												<option value="state" <?php if($apt_settings->appointment_location_sortby=='state') { echo ' selected ';}?> ><?php echo __("State","apt");?></option>
												<option value="city" <?php if($apt_settings->appointment_location_sortby=='city') { echo ' selected ';}?> ><?php echo __("City","apt");?></option>						
												</select>
											</div>	
										</td>
									</tr>
									
									<tr>
										<td><label><?php echo __("Tax/Vat","apt");?></label></td>
										<td>
											<div class="form-group">
												<label class="toggle-large" for="appointment_taxvat_status">
													<input type="checkbox" class="apt-toggle-sh" name="appointment_taxvat_status" id="appointment_taxvat_status" <?php if($apt_settings->appointment_taxvat_status=='E') { echo ' checked  '; }?>  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
												</label><div class="tool_mar_80">
												<a class="apt-tooltip-link pr-t0" href="#" data-toggle="tooltip" title="<?php echo __("If you are charging tax, please enable to specify the rate.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
												</div>
												<div class="<?php if($apt_settings->appointment_taxvat_status=='D') { echo "hide-div";}?> collapse_appointment_taxvat_status">
													<div class="apt-custom-radio">
														<ul class="apt-radio-list">
															<li>
																<input type="radio" id="tax-vat-percentage" class="apt-radio" <?php if($apt_settings->appointment_taxvat_type=='P') { echo ' checked="checked" '; }?>   name="appointment_taxvat_type" value="P" />
																<label for="tax-vat-percentage"><span></span><?php echo __("Percentage","apt");?></label>
															</li>
															<li>
																<input type="radio" id="tax-vat-flatfree" class="ak_radio" <?php if($apt_settings->appointment_taxvat_type=='F') { echo ' checked="checked" '; }?> name="appointment_taxvat_type" value="F" />
																<label for="tax-vat-flatfree"><span></span><?php echo __("Flat Fee","apt");?></label>
															</li>
															<li class="apt-tax-vat-input-container">
																<input type="text" class="form-control" id="appointment_taxvat_amount" value="<?php echo $apt_settings->appointment_taxvat_amount; ?>" size="3" maxlength="3" /><i  class="apt-tax-percent fa fa-percent <?php if($apt_settings->appointment_taxvat_type=='F') { echo 'hide-div '; }?>"></i>
															</li>
														</ul>	
													</div>
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Partial Deposit","apt");?></label></td>
										<td>
											<div class="form-group">
												<label class="toggle-large" for="appointment_partial_deposit_status">
												<input type="checkbox" class="apt-toggle-pd" <?php if($apt_settings->appointment_partial_deposit_status=='E') { echo ' checked="checked" '; }?> id="appointment_partial_deposit_status" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
												
												</label>
												<div class="tool_mar_80">
												<a class="apt-tooltip-link pr-t0" href="#" data-toggle="tooltip" title="<?php echo __("Partial payment option will help you to charge partial payment of total amount from client and remaining you can collect locally.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
												</div>
												<div id="apt_partial_depost_error" class="hide-div"><label class="error"><?php echo __("Please enable payment gateway first.","apt");?></label></div>
												<div class="<?php if($apt_settings->appointment_partial_deposit_status=='D') { echo "hide-div";}?> collapse_appointment_partial_deposit_status">
												<div class="apt-custom-radio">
													<ul class="apt-radio-list">
														<li>
															<input type="radio" id="partialdeposit-percentage" class="apt-radio" <?php if($apt_settings->appointment_partial_deposit_type=='P') { echo ' checked="checked" '; }?>   name="appointment_partial_deposit_type" value="P" />
															<label for="partialdeposit-percentage"><span></span><?php echo __("Percentage","apt");?></label>
														</li>
														<li>
															<input type="radio" id="partialdeposit-flatfree" class="apt_radio" <?php if($apt_settings->appointment_partial_deposit_type=='F') { echo ' checked="checked" '; }?> name="appointment_partial_deposit_type" value="F" />
															<label for="partialdeposit-flatfree"><span></span><?php echo __("Flat Fee","apt");?></label>
														</li>
														<li class="apt-tax-vat-input-container">
															<input type="text" class="form-control" id="appointment_partial_deposit_amount" value="<?php echo $apt_settings->appointment_partial_deposit_amount; ?>" size="3" maxlength="3" /><i  class="apt-partial-deposit-percent fa fa-percent <?php if($apt_settings->appointment_partial_deposit_type=='F') { echo 'hide-div '; }?>"></i>
														</li>
													</ul>
												</div>		
													 <br/><br/>
													<div>
													<label><?php echo __("Partial Deposit Message","apt");?></label>
													</div>
													<div>
													<textarea id="appointment_partial_deposit_message" class="form-control" row="4" cols="40"><?php echo $apt_settings->appointment_partial_deposit_message; ?></textarea>
													</div>
												</div>
											</div>
											
											<span id="apt-partial-depost_error" style="display:none;color:red;" ><?php echo __("Please Enable Payment Gateway","apt");?></span>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("'Thankyou Page' Url","apt");?></label></td>
										<td>
											<div class="form-group">
												<input id="appointment_thankyou_page" type="text" class="form-control" size="50" name="" value="<?php echo $apt_settings->appointment_thankyou_page; ?>" placeholder="Custom Thankyou page url" />
												<i><?php echo __("Default url is :","apt");?> <?php echo site_url();?>/apt-thankyou/</i>
											</div>	
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("'Thankyou Page' redirection time","apt");?></label></td>
										<td>
											<div class="form-group">
												<select class="selectpicker" data-size="10" id="appointment_thankyou_page_rdtime" name="appointment_thankyou_page_rdtime">
													<option value=''><?php echo __("Off","apt");?></option>
													<?php for($rdtimes=1;$rdtimes<=15;$rdtimes++) { ?>
													<option <?php if($apt_settings->appointment_thankyou_page_rdtime==($rdtimes*1000)){ echo 'selected="selected"'; }?> value="<?php echo $rdtimes*1000;?>"><?php if($rdtimes==1){ echo $rdtimes.__(" second","apt");}else{ echo $rdtimes.__(" seconds","apt");}?></option>
													<?php } for($rdtimem=1;$rdtimem<=15;$rdtimem++) { ?>
													<option <?php if($apt_settings->appointment_thankyou_page_rdtime==($rdtimem*60000)){ echo 'selected'; }?> value="<?php echo $rdtimem*60000;?>"><?php if($rdtimem==1){ echo $rdtimem . __(" minute","apt");}else{ echo $rdtimem . __(" minutes","apt");}?></option>
													<?php } ?>						
												</select>
											</div>	
										</td>
									</tr>
									
									<tr>
										<td><label><?php echo __("Allow multiple booking for same timeslot","apt");?></label></td>
										<td>
											<div class="form-group col-md-12 np">
												<label class="manage-right toggle-large" for="appointment_multiple_booking_sameslot">
													<input <?php if($apt_settings->appointment_multiple_booking_sameslot=='E') { echo ' checked="checked" '; }?> type="checkbox" class="apt-toggle-sh" id="appointment_multiple_booking_sameslot"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
												</label>
												<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Allow multiple appointment booking at same time slot, will allow you to show availability time slot even you have booking already for that time.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
												<div class="<?php if($apt_settings->appointment_multiple_booking_sameslot=='D') { echo "hide-div";}?> collapse_appointment_multiple_booking_sameslot col-md-9 col-sm-12 col-xs-12 npr pull-right">
													<span class="custom-form-width-auto apt-tax-vat-input-container">
													<label class="pull-left mr-10"><?php echo __("Maximum booking limit","apt");?></label>
														<select id="appointment_slot_max_booking_limit" class="selectpicker" data-size="10" data-width="100px"  style="display: none;">
														<option value="0" <?php if($apt_settings->appointment_slot_max_booking_limit==0) { echo ' selected ';}?> ><?php echo __("Unlimited","apt");?></option>
														<option value="1" <?php if($apt_settings->appointment_slot_max_booking_limit==1) { echo ' selected ';}?> ><?php echo __("1","apt");?></option>
														<option value="2" <?php if($apt_settings->appointment_slot_max_booking_limit==2) { echo ' selected ';}?> ><?php echo __("2","apt");?></option>
														<option value="3" <?php if($apt_settings->appointment_slot_max_booking_limit==3) { echo ' selected ';}?> ><?php echo __("3","apt");?></option>
														<option value="4" <?php if($apt_settings->appointment_slot_max_booking_limit==4) { echo ' selected ';}?> ><?php echo __("4","apt");?></option>
														<option value="5" <?php if($apt_settings->appointment_slot_max_booking_limit==5) { echo ' selected ';}?> ><?php echo __("5","apt");?></option>
														<option value="6" <?php if($apt_settings->appointment_slot_max_booking_limit==6) { echo ' selected ';}?> ><?php echo __("6","apt");?></option>
														<option value="7" <?php if($apt_settings->appointment_slot_max_booking_limit==7) { echo ' selected ';}?> ><?php echo __("7","apt");?></option>
														<option value="8" <?php if($apt_settings->appointment_slot_max_booking_limit==8) { echo ' selected ';}?> ><?php echo __("8","apt");?></option>
														<option value="9" <?php if($apt_settings->appointment_slot_max_booking_limit==9) { echo ' selected ';}?> ><?php echo __("9","apt");?></option>
														<option value="10" <?php if($apt_settings->appointment_slot_max_booking_limit==10) { echo ' selected ';}?> ><?php echo __("10","apt");?></option>
														</select>
													</div>
												
												
											</div>
											
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Appointment auto confirm","apt");?></label></td>
										<td>
											<div class="form-group">
												<label class="toggle-large" for="appointment_appointment_auto_confirm">
													<input <?php if($apt_settings->appointment_appointment_auto_confirm=='E') { echo ' checked="checked" '; }?> type="checkbox" id="appointment_appointment_auto_confirm" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
												</label>
											</div>
											<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("When Enabled, Appointment request from clients will be auto confirmed.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Allow day closing time overlap booking","apt");?></label></td>
										<td>
											<div class="form-group">
												<label class="toggle-large" for="appointment_dayclosing_overlap">
													<input <?php if($apt_settings->appointment_dayclosing_overlap=='E') { echo ' checked="checked" '; }?> type="checkbox" id="appointment_dayclosing_overlap"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
												</label>
											</div>
											<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("you want to allow booking even service during overlap the day closing time.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<!-- add new option -->
									<tr>
										<td><label><?php echo __("Display cart discription in frontside","apt");?></label></td>
										<td>
											<div class="form-group">
												<label class="toggle-large" for="booking_cart_description">
												
													<input <?php if($apt_settings->appointment_cart_description=='E') { echo ' checked="checked" '; }?> type="checkbox" id="appointment_cart_description"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
												</label>
											</div>
											<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("you want to allow display cart description in front side","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Date Format","apt");?></label></td>
										<td>
											<div class="form-group">
												<label class="toggle-large" for="appointment_datepicker_format">
													
												</label>
												<select name="appointment_datepicker_format" id="appointment_datepicker_format" class="selectpicker form-control" data-size="5" data-live-search="true"  data-actions-box="true" >
												
											<option value="d-m-Y" <?php if($apt_settings->appointment_datepicker_format=='d-m-Y'){echo 'selected';} ?>>dd-mm-yyyy (eg. <?php echo date('d-m-Y');?>)</option>
											<option value="j-m-Y" <?php if($apt_settings->appointment_datepicker_format=='j-m-Y'){echo 'selected';} ?>>d-mm-yyyy (eg. <?php echo date('j-n-Y');?>)</option>
											<option value="d-M-Y" <?php if($apt_settings->appointment_datepicker_format=='d-M-Y'){echo 'selected';} ?>>dd-m-yyyy (eg. <?php echo date('d-M-Y');?>)</option>
											<option value="d-F-Y" <?php if($apt_settings->appointment_datepicker_format=='d-F-Y'){echo 'selected';} ?>>dd-m-yyyy (eg. <?php echo date('d-F-Y');?>)</option>
											<option value="j-M-Y" <?php if($apt_settings->appointment_datepicker_format=='j-M-Y'){echo 'selected';} ?>>d-m-yyyy (eg. <?php echo date('j-M-Y');?>)</option>
											<option value="j-F-Y" <?php if($apt_settings->appointment_datepicker_format=='j-F-Y'){echo 'selected';} ?>>dd-m-yyyy (eg. <?php echo date('j-F-Y');?>)</option>
											<!-- With Slashes -->
											<option value="d/m/Y" <?php if($apt_settings->appointment_datepicker_format=='d/m/Y'){echo 'selected';} ?>>dd/mm/yyyy (eg. <?php echo date('d/m/Y');?>)</option>
											<option value="j/m/Y" <?php if($apt_settings->appointment_datepicker_format=='j/m/Y'){echo 'selected';} ?>>d/mm/yyyy (eg. <?php echo date('j/m/Y');?>)</option>
											<option value="d/M/Y" <?php if($apt_settings->appointment_datepicker_format=='d/M/Y'){echo 'selected';} ?>>dd/m/yyyy (eg. <?php echo date('d/M/Y');?>)</option>
											<option value="d/F/Y" <?php if($apt_settings->appointment_datepicker_format=='d/F/Y'){echo 'selected';} ?>>dd/M/yyyy (eg. <?php echo date('d/F/Y');?>)</option>
											<option value="j/M/Y" <?php if($apt_settings->appointment_datepicker_format=='j/M/Y'){echo 'selected';} ?>>d/m/yyyy (eg. <?php echo date('j/M/Y');?>)</option>
											<option value="j/F/Y" <?php if($apt_settings->appointment_datepicker_format=='j/F/Y'){echo 'selected';} ?>>d/M/yyyy (eg. <?php echo date('j/F/Y');?>)</option>
											<!-- Month Day Year Suffled -->
											<option value="m-d-Y"  <?php if($apt_settings->appointment_datepicker_format=='m-d-Y'){echo 'selected';} ?> >mm-dd-yyyy (eg. <?php echo date('m-d-Y');?>)</option>
											<option value="m-j-Y" <?php if($apt_settings->appointment_datepicker_format=='m-j-Y'){echo 'selected';} ?> >mm-d-yyyy (eg. <?php echo date('m-j-Y');?>)</option>
											<option value="M-d-Y" <?php if($apt_settings->appointment_datepicker_format=='M-d-Y'){echo 'selected';} ?>>m-dd-yyyy (eg. <?php echo date('M-d-Y');?>)</option>
											<option value="F-d-Y" <?php if($apt_settings->appointment_datepicker_format=='F-d-Y'){echo 'selected';} ?>>m-dd-yyyy (eg. <?php echo date('F-d-Y');?>)</option>
											<option value="M-j-Y" <?php if($apt_settings->appointment_datepicker_format=='M-j-Y'){echo 'selected';} ?>>m-d-yyyy (eg. <?php echo date('M-j-Y');?>)</option>
											<option value="F-j-Y" <?php if($apt_settings->appointment_datepicker_format=='F-j-Y'){echo 'selected';} ?>>m-dd-yyyy (eg. <?php echo date('F-j-Y');?>)</option>
											<!-- With Slashes -->
											<option value="m/d/Y" <?php if($apt_settings->appointment_datepicker_format=='m/d/Y'){echo 'selected';} ?>>mm/dd/yyyy (eg. <?php echo date('m/d/Y');?>)</option>
											<option value="m/j/Y" <?php if($apt_settings->appointment_datepicker_format=='m/j/Y'){echo 'selected';} ?>>mm/d/yyyy (eg. <?php echo date('m/j/Y');?>)</option>
											<option value="M/d/Y" <?php if($apt_settings->appointment_datepicker_format=='M/d/Y'){echo 'selected';} ?>>m/dd/yyyy (eg. <?php echo date('M/d/Y');?>)</option>
											<option value="F/d/Y" <?php if($apt_settings->appointment_datepicker_format=='F/d/Y'){echo 'selected';} ?>>m/dd/yyyy (eg. <?php echo date('F/d/Y');?>)</option>
											<option value="M/j/Y" <?php if($apt_settings->appointment_datepicker_format=='M/j/Y'){echo 'selected';} ?>>m/d/yyyy (eg. <?php echo date('M/j/Y');?>)</option>
											<option value="F/j/Y" <?php if($apt_settings->appointment_datepicker_format=='F/j/Y'){echo 'selected';} ?>>m/dd/yyyy (eg. <?php echo date('F/j/Y');?>)</option>
											<option value="j M,Y" <?php if($apt_settings->appointment_datepicker_format=='j M,Y'){echo 'selected';} ?>>dd m,yyyy (eg. <?php echo date('j M,Y');?>)</option>
											<option value="M j, Y" <?php if($apt_settings->appointment_datepicker_format=='M j, Y'){echo 'selected';} ?>>m dd,yyyy (eg. <?php echo date('M j, Y');?>)</option>
										</select>
											</div>
											<?php 
											/* <a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("you want to allow display cart description in front side","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a> */
											?>
										</td>
									</tr>
									<tr>
                                    <td><label><?php echo __("Cancellation Policy","apt");?></label></td>
                                    <td>
                                        <div class="form-group">
                                            <label class="toggle-large" for="appointment_cancelation_policy_status">
												<input type="checkbox" class="apt-toggle-sh" name="appointment_cancelation_policy_status" id="appointment_cancelation_policy_status" <?php if($apt_settings->appointment_cancelation_policy_status=='E') { echo ' checked  '; }?>  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
											</label>
											<div class="<?php if($apt_settings->appointment_cancelation_policy_status=='D') { echo "hide-div";} ?> collapse_appointment_cancelation_policy_status">
												<div class="apt-custom-radio">
                                                    <ul class="apt-radio-list np mb-15">
                                                        <li class="w100">
                                                            <label><?php echo __("Cancellation Policy Header","apt");?></label>
                                                            <input type="text" class="w100 form-control" id="appointment_cancelation_policy_header" name="appointment_cancelation_policy_header" value="<?php echo ($apt_settings->appointment_cancelation_policy_header);?>" />
                                                        </li>
                                                    </ul>
                                                </div>
                                                <label><?php echo __("Cancellation Policy Textarea","apt");?></label>
                                               <textarea class="form-control w100" id="appointment_cancelation_policy_text" name="appointment_cancelation_policy_text" row="4" cols="40"><?php echo ($apt_settings->appointment_cancelation_policy_text);?></textarea>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
								<tr>
                                    <td><label><?php echo __("Terms & Conditions","apt");?></label></td>
                                    <td>
                                        <div class="form-group">
                                        	<label class="toggle-large" for="appointment_allow_terms_and_conditions">
												<input type="checkbox" class="apt-toggle-sh" name="appointment_allow_terms_and_conditions" id="appointmentappointment_allow_terms_and_conditions" <?php if($apt_settings->appointment_allow_terms_and_conditions=='E') { echo ' checked  '; }?>  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
											</label>
											
                                            <div class="<?php if($apt_settings->appointment_allow_terms_and_conditions=='D') { echo "hide-div";}?> collapse_appointment_allow_terms_and_conditions">
                                                <div class="apt-custom-radio">
                                                    <ul class="apt-radio-list">
                                                        <li>
                                                            <label><?php echo __("Terms & Condition Link","apt");?></label>
                                                            <input type="text" class="form-control" size="50" id="appointment_allow_terms_and_conditions_url" name="appointment_allow_terms_and_conditions_url" value="<?php echo urldecode($apt_settings->appointment_allow_terms_and_conditions_url);?>" />
														</li>
                                                    </ul>
                                                </div>
                                            </div>
                                          
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label><?php echo __("Privacy Policy","apt");?></label></td>
                                    <td>
                                        <div class="form-group">
                                        	<label class="toggle-large" for="appointment_allow_privacy_policy">
												<input type="checkbox" class="apt-toggle-sh" name="appointment_allow_privacy_policy" id="appointment_allow_privacy_policy" <?php if($apt_settings->appointment_allow_privacy_policy=='E') { echo ' checked  '; }?>  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
											</label>
											
											<div class="<?php if($apt_settings->appointment_allow_privacy_policy=='D') { echo "hide-div";}?> collapse_appointment_allow_privacy_policy">
												<div class="apt-custom-radio">
                                                    <ul class="apt-radio-list">
                                                        <li class="apt-privacy-policy-li-width">
                                                            <?php echo __("Privacy Policy Link","apt");?>
                                                            <input type="text" class="form-control" size="50" id="appointment_allow_privacy_policy_url" name="appointment_allow_privacy_policy_url" value="<?php echo urldecode($apt_settings->appointment_allow_privacy_policy_url);?>" />
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
											<a id="apt_save_general_settings" name="" class="btn btn-success"><?php echo __("Save Setting","apt");?></a>
											<button type="reset" class="btn btn-default ml-30"><?php echo __("Default Setting","apt");?></button>
								
										</td>
									</tr>
								</tfoot>
							</table>
							
						</div>
					</div>
				</form>	
			</div>
			
			<div class="tab-pane apt-toggle-abs" id="appearance-setting">
				<form id="" method="post" type="" class="apt-appearance-settings" >
					<div class="panel panel-default">
						<div class="panel-heading">
							<h1 class="panel-title"><?php echo __("Appearance Settings","apt");?></h1>
						</div>
						<div class="panel-body">
							<table class="form-inline apt-common-table" >
								<tbody>
									<tr>
										<td><label> <?php echo __("Color Scheme","apt");?></label></td>
										<td>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 npl">
												<label><?php echo __("Primary Color","apt");?></label>
												<input type="text" id="appointment_primary_color" class="form-control demo" data-control="saturation" value="<?php echo $apt_settings->appointment_primary_color;?>" />
											</div>	
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 npl">
												<label><?php echo __("Secondary color","apt");?></label>
												<input type="text" id="appointment_secondary_color" class="form-control demo" data-control="saturation" value="<?php echo $apt_settings->appointment_secondary_color;?>" />
											</div>	
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 npl">
												<label><?php echo __("Text color","apt");?></label>
												<input type="text" id="appointment_text_color" class="form-control demo" data-control="saturation" value="<?php echo $apt_settings->appointment_text_color;?>" />
											</div>	
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 npl">
												<label><?php echo __("Text color on Bg","apt");?></label>
												<input type="text" id="appointment_bg_text_color" class="form-control demo" data-control="saturation" value="<?php echo $apt_settings->appointment_bg_text_color;?>" />
											</div>	
										</td>
									</tr>
									<tr>
										<td><label> <?php echo __("Admin Area Color Scheme","apt");?></label></td>
										<td>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 npl">	
												<label><?php echo __("Admin Primary Color","apt");?></label>
												<input type="text" id="appointment_admin_color_primary" class="form-control demo" data-control="saturation" value="<?php echo $apt_settings->appointment_admin_color_primary;?>" />
											</div>	
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 npl">	
												<label><?php echo __("Admin Secondary color","apt");?></label>
												<input type="text" id="appointment_admin_color_secondary" class="form-control demo" data-control="saturation" value="<?php echo $apt_settings->appointment_admin_color_secondary;?>" />
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 npl">	
												<label><?php echo __("Admin Text color","apt");?></label>
												<input type="text" id="appointment_admin_color_text" class="form-control demo" data-control="saturation" value="<?php echo $apt_settings->appointment_admin_color_text;?>" />
											</div>
											<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 npl">
												<label><?php echo __("Admin Text color on Bg","apt");?></label>
												<input type="text" id="appointment_admin_color_bg_text" class="form-control demo" data-control="saturation" value="<?php echo $apt_settings->appointment_admin_color_bg_text;?>" />
											</div>
										</td>
									</tr>
									<!--<tr>
										<td><label><?php echo __("Show service providers","apt");?></label></td>
										<td>
											<div class="form-group">
												<label for="appointment_show_provider">
													<input <?php if($apt_settings->appointment_show_provider=='E') { echo ' checked="checked" '; }?> type="checkbox" id="appointment_show_provider" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","apt");?>" data-off="<?php echo __("Off","apt");?>" data-onstyle="success" data-offstyle="default" />
												</label>
											</div>
											<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("you can hide service providers, if you think there is only one service provider you want to use.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>-->
									<tr>
										<td><label><?php echo __("Show providers avatars","apt");?></label></td>
										<td>
											<div class="form-group">
												<label for="appointment_show_provider_avatars">
													<input <?php if($apt_settings->appointment_show_provider_avatars=='E') { echo ' checked="checked" '; }?> type="checkbox" id="appointment_show_provider_avatars" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","apt");?>" data-off="<?php echo __("Off","apt");?>" data-onstyle="success" data-offstyle="default" />
												</label>
											</div>
											<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("This will show avatars of providers on front.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<!--<tr>
										<td><label><?php echo __("Show service dropdown","apt");?></label></td>
										<td>
											<div class="form-group">
												<label for="appointment_show_services">
													<input <?php if($apt_settings->appointment_show_services=='E') { echo ' checked="checked" '; }?> type="checkbox" id="appointment_show_services" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","apt");?>" data-off="<?php echo __("Off","apt");?>" data-onstyle="success" data-offstyle="default" />
												</label>
											</div>
											<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("It will enable/disable dropdown for service on front.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>-->
									<tr>
										<td><label><?php echo __("Show service description","apt");?></label></td>
										<td>
											<div class="form-group">
												<label for="appointment_show_service_desc">
													<input <?php if($apt_settings->appointment_show_service_desc=='E') { echo ' checked="checked" '; }?> type="checkbox" id="appointment_show_service_desc" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","apt");?>" data-off="<?php echo __("Off","apt");?>" data-onstyle="success" data-offstyle="default" />
												</label>
											</div>
											<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("It will enable descriptions for service on front.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Show coupons input on checkout","apt");?></label></td>
										<td>
											<div class="form-group">
												<label for="appointment_show_coupons">
													<input <?php if($apt_settings->appointment_show_coupons=='E') { echo ' checked="checked" '; }?> type="checkbox" id="appointment_show_coupons" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","apt");?>" data-off="<?php echo __("Off","apt");?>" data-onstyle="success" data-offstyle="default" />
												</label>
											</div>
											<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("You can show/hide coupon input on checkout form.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Hide faded already booked time slots","apt");?></label></td>
										<td>
											<div class="form-group">
												<label for="appointment_hide_booked_slot">
													<input <?php if($apt_settings->appointment_hide_booked_slot=='E') { echo ' checked="checked" '; }?> type="checkbox" id="appointment_hide_booked_slot" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","apt");?>" data-off="<?php echo __("Off","apt");?>" data-onstyle="success" data-offstyle="default" />
												</label>
											</div>
											<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("With this you can hide the already booked slots just to hide your bookings from your Competitors.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Guest user checkout","apt");?></label></td>
										<td>
											<div class="form-group">
												<label for="appointment_guest_user_checkout">
													<input <?php if($apt_settings->appointment_guest_user_checkout=='E') { echo ' checked="checked" '; }?> type="checkbox" id="appointment_guest_user_checkout" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","apt");?>" data-off="<?php echo __("Off","apt");?>" data-onstyle="success" data-offstyle="default" />
												</label>
											</div>
											<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("With this feature you can allow a visitor to book appointment without registration.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<!--<tr>
										<td><label><?php //echo __("Booking(s) Cart","apt");?></label></td>
										<td>
											<div class="form-group">
												<label for="appointment_cart">
													<input <?php //if($apt_settings->appointment_cart=='E') { echo ' checked="checked" '; }?> type="checkbox" id="appointment_cart" data-toggle="toggle" data-size="small" data-on="<?php //echo __("On","apt");?>" data-off="<?php //echo __("Off","apt");?>" data-onstyle="success" data-offstyle="default" />
												</label>
											</div>
											<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php //echo __("With this feature you can Enable/Disable cart.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>-->
									<!--<tr>
										<td><label><?php //echo __("Max cart item limit","apt");?></label></td>
										<td>							
											<div class="form-group">
												<select class="selectpicker" data-width="70" data-size="10" id="appointment_max_cartitem_limit" name="appointment_max_cartitem_limit"  >
													<?php //for($citem=1;$citem<=50;$citem++){ ?>
														<option <?php //if($apt_settings->appointment_max_cartitem_limit==$citem) { echo ' selected  '; }?> value="<?php //echo $citem;?>"><?php //echo $citem;?></option>
														<?php //} ?>
													</select>
												</div>
												<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php //echo __("With this feature you can set limit for cart items.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
											</td>									
										</td>
									</tr>-->
									<!-- reviews section new -->
									<tr>
										<td><label><?php echo __("Reviews","apt");?></label></td>
										<td>							
											<div class="form-group">
												<label for="appointment_reviews_status">
													<input <?php if($apt_settings->appointment_reviews_status=='E') { echo ' checked="checked" '; }?> type="checkbox" id="appointment_reviews_status" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","apt");?>" data-off="<?php echo __("Off","apt");?>" data-onstyle="success" data-offstyle="default" />
												</label>
											</div>
												<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("With this feature you can Enable/Disable reviews for clients.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																			
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Auto Confirm Reviews","apt");?></label></td>
										<td>							
											<div class="form-group">
												<label for="appointment_auto_confirm_reviews">
													<input <?php if($apt_settings->appointment_auto_confirm_reviews=='E') { echo ' checked="checked" '; }?> type="checkbox" id="appointment_auto_confirm_reviews" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","apt");?>" data-off="<?php echo __("Off","apt");?>" data-onstyle="success" data-offstyle="default" />
												</label>
											</div>
												<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("With this feature you can Auto confirm clients reivews. No need to confirm manually.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																			
										</td>
									</tr>
									<tr>
									   <td><label><?php echo __("Frontend Custom CSS","apt");?></label></td>
									   <td>       
										<div class="form-group">
										 <label for="appointment_frontend_custom_css">
										  <textarea id="appointment_frontend_custom_css" class="form-control" cols="80" rows="6"><?php echo $apt_settings->appointment_frontend_custom_css; ?></textarea>
										 </label>
										</div>
										 <a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("This custom css will apply on frontend","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
												
									   </td>
									</tr>
									
									<!-- custom loader -->
									<?php 
									/* <tr>
									   <td><label><?php echo __("Custom Frontend Loader","apt");?></label></td>
									   <td>       
										<div class="form-group">
										 <label for="appointment_frontend_loader">
										 
										  <input type="file" id="appointment_frontend_loader" class="form-control appointment_frontend_loader_file" value ="<?php echo $apt_settings->appointment_frontend_loader; ?>" >
										  <input type="button" class="btn button" value="Upload" id="but_upload">
										 </label>
										</div>
										 <a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("This custom loader in frontend side","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
												
									   </td>
										<td>
									   <img height="100px" width="100px"src="<?php echo $plugin_url_for_ajax; ?>/assets/images/<?php echo $apt_settings->appointment_frontend_loader; ?>" />
									   
										</td>
									</tr> */
									?>
								 </tbody>
								<tfoot>
									<tr>
										<td></td>
										<td>
											<a href="javascript:void(0)" id="apt_save_appearance_settings" name="" class="btn btn-success" type="submit"><?php echo __("Save Setting","apt");?></a>
											<button type="reset" class="btn btn-default ml-30"><?php echo __("Default Setting","apt");?></button>
								
										</td>
									</tr>
								</tfoot>
							</table>
							
						</div>
					</div>
				</form>
			</div>
			<div class="tab-pane apt-toggle-abs" id="payment-setting">
				<form id="" method="post" type="" class="apt-payment-settings" >
					<div class="panel panel-default">
						<div class="panel-heading">
							<h1 class="panel-title"><?php echo __("Payment Gateways","apt");?></h1>
						</div>
						<div class="panel-body">
							<div id="accordion" class="panel-group">
								<div class="panel panel-default apt-all-payments-main">
									<div class="panel-heading">
										<h4 class="panel-title">
											<span><?php echo __("All Payment Gateways","apt");?></span>
											<div class="apt-enable-disable-right pull-right">
												<label class="toggle-large" for="appointment_payment_gateways_status">
													<input type="checkbox" <?php if($apt_settings->appointment_payment_gateways_status=='E'){ echo 'checked="checked"';} ?> class="apt-toggle-sh" id="appointment_payment_gateways_status" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
												</label>
											</div>
											
										</h4>
									</div>
									
									
									<div id="collapseOne" class="<?php if($apt_settings->appointment_payment_gateways_status=='D'){ echo 'hide-div';} ?>  panel-collapse  collapse_appointment_payment_gateways_status">
										<div class="panel-body">
										
										<div class="alert alert-danger" style="display: none;">
											<a href="#" class="close" data-dismiss="alert">&times;</a>
											<strong><?php echo __("Warning!","apt");?></strong><?php echo __("Currency you have selected ( currency option ) is not supported by Stipe.","apt");?> 
										</div>
											<div id="accordion" class="panel-group">
												<div class="panel panel-default apt-payment-methods">
													<div class="panel-heading">
														<h4 class="panel-title">
															<span><?php echo __("Pay in Person","apt");?></span>
															<div class="apt-enable-disable-right pull-right">
																<label class="toggle-large" for="appointment_locally_payment_status">
																	<input type="checkbox" <?php if($apt_settings->appointment_locally_payment_status=='E'){ echo 'checked="checked"';} ?> class="apt-toggle-sh" id="appointment_locally_payment_status" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disable","apt");?>" data-onstyle="success" data-offstyle="danger" />
																</label>
															</div>
															
														</h4>
													</div>
												</div>
												
												<div class="panel panel-default apt-payment-methods">
													<div class="panel-heading">
														<h4 class="panel-title">
															<span><?php echo __("Paypal Express Checkout","apt");?>
															<img class="apt-img-payments apt-paypal" src="<?php echo $plugin_url_for_ajax; ?>/assets/images/paypal.png" />
															</span>
															<div class="apt-enable-disable-right pull-right">
																<label class="toggle-large" for="appointment_payment_method_Paypal">
																	<input <?php if($apt_settings->appointment_payment_method_Paypal=='E'){ echo 'checked="checked"';} ?> type="checkbox" class="apt-toggle-sh" id="appointment_payment_method_Paypal" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
																</label>
															</div>
															
														</h4>
													</div>
													<div id="collapseOne" class="<?php if($apt_settings->appointment_payment_method_Paypal=='D'){ echo 'hide-div';} ?> panel-collapse collapse_appointment_payment_method_Paypal">
														<div class="panel-body">
															<table class="form-inline apt-common-table">
																<tbody>
																	<tr>
																		<td><label><?php echo __("API Username","apt");?></label></td>
																		<td>
																			<div class="form-group apt-lgf">
																				<input type="text" class="form-control" id="appointment_paypal_api_username"  value="<?php echo $apt_settings->appointment_paypal_api_username ;?>" size="50" />
																			</div>	
																			<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Paypal API username can get easily from developer.paypal.com account.","apt");?>"><i class="fa fa-info-circle fa-lg lgf"></i></a>
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("API Password","apt");?></label></td>
																		<td>
																			<div class="form-group apt-lgf">
																				<input type="password" class="form-control" id="appointment_paypal_api_password" value="<?php echo $apt_settings->appointment_paypal_api_password ;?>" size="50" />
																			</div>	
																			<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Paypal API password can get easily from developer.paypal.com account.","apt");?>"><i class="fa fa-info-circle fa-lg lgf"></i></a>
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Signature","apt");?></label></td>
																		<td>
																			<div class="form-group apt-lgf">
																				<input type="text" class="form-control" id="appointment_paypal_api_signature" value="<?php echo $apt_settings->appointment_paypal_api_signature ;?>" size="50" />
																			</div>	
																			<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Paypal API Signature can get easily from developer.paypal.com account","apt");?>"><i class="fa fa-info-circle fa-lg lgf"></i></a>
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Paypal guest payment","apt");?></label></td>
																		<td>
																			<div class="form-group">
																				<label class="toggle-large" for="appointment_paypal_guest_checkout">
																					<input <?php if($apt_settings->appointment_paypal_guest_checkout=='E'){ echo 'checked="checked"';} ?> type="checkbox" class="apt-toggle-sh" id="appointment_paypal_guest_checkout" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
																				
																				</label>
																			</div>	
																			<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Let user pay through credit card without having Paypal account.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Test Mode","apt");?></label></td>
																		<td>
																			<div class="form-group">
																				<label class="toggle-large" for="appointment_paypal_testing_mode">
																					<input <?php if($apt_settings->appointment_paypal_testing_mode=='E'){ echo 'checked="checked"';} ?> type="checkbox" class="apt-toggle-sh" id="appointment_paypal_testing_mode" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
																				
																				</label>
																			</div>	
																			<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("You can enable Paypal test mode for sandbox account testing.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																		</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</div>
												</div>
												
												<div class="panel panel-default apt-payment-methods">
													<div class="panel-heading">
														<h4 class="panel-title">
															<span><?php echo __("Stripe Payment Form","apt");?>
															<img class="apt-img-payments apt-stripe" src="<?php echo $plugin_url_for_ajax; ?>/assets/images/stripe.jpg" />
															</span>
															<div class="apt-enable-disable-right pull-right">
																<label class="toggle-large" for="appointment_payment_method_Stripe">
																	<input <?php if($apt_settings->appointment_payment_method_Stripe=='E'){ echo 'checked="checked"';} ?> type="checkbox" class="apt-toggle-sh" id="appointment_payment_method_Stripe" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
																	
																	
																</label>
															</div>
														</h4>
													</div>
													<div id="collapseOne" class="<?php if($apt_settings->appointment_payment_method_Stripe=='D'){ echo 'hide-div';} ?> panel-collapse collapse_appointment_payment_method_Stripe">
														<div class="panel-body">
															<table class="form-inline apt-common-table">
																<tbody>
																	<tr>
																		<td><label><?php echo __("Secret Key","apt");?></label></td>
																		<td>
																			<div class="form-group">
																				<input type="text" class="form-control" id="appointment_stripe_secretKey" size="50" value="<?php echo $apt_settings->appointment_stripe_secretKey ;?>" />
																			</div>	
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Publishable Key","apt");?></label></td>
																		<td>
																			<div class="form-group">
																				<input type="password" class="form-control" id="appointment_stripe_publishableKey" value="<?php echo $apt_settings->appointment_stripe_publishableKey;?>" size="50" />
																			</div>	
																		</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</div>
												</div>
												<!-- Payumoney Start -->
												<div class="panel panel-default apt-payment-methods">
													<div class="panel-heading">
														<h4 class="panel-title">
															<span><?php echo __("Payumoney Payment Form","apt");?>
															<img class="apt-img-payments apt-stripe" src="<?php echo $plugin_url_for_ajax; ?>/assets/images/payumoney.jpg" />
															</span>
															<div class="apt-enable-disable-right pull-right">
																<label class="toggle-large" for="appointment_payment_method_payumoney">
																	<input <?php if($apt_settings->appointment_payment_method_Payumoney=='E'){ echo 'checked="checked"';} ?> type="checkbox" class="apt-toggle-sh" id="appointment_payment_method_Payumoney" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
																</label>
															</div>
														</h4>
													</div>
													<div id="collapseOne" class="<?php if($apt_settings->appointment_payment_method_Payumoney=='D'){ echo 'hide-div';} ?> panel-collapse collapse_appointment_payment_method_Payumoney">
														<div class="panel-body">
															<table class="form-inline apt-common-table">
																<tbody>
																	<tr>
																		<td><label><?php echo __("Merchant Key","apt");?></label></td>
																		<td>
																			<div class="form-group">
																				<input type="text" class="form-control" id="appointment_payumoney_merchantkey" size="50" value="<?php echo $apt_settings->appointment_payumoney_merchantkey ;?>" />
																			</div>	
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Salt Key","apt");?></label></td>
																		<td>
																			<div class="form-group">
																				<input type="password" class="form-control" id="appointment_payumoney_saltkey" value="<?php echo $apt_settings->appointment_payumoney_saltkey;?>" size="50" />
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
												<div class="panel panel-default apt-payment-methods">
													<div class="panel-heading">
														<h4 class="panel-title">
															<span><?php echo __("Paytm Payment Form","apt");?>
															<img class="apt-img-payments apt-paytm" src="<?php echo $plugin_url_for_ajax; ?>/assets/images/paytm.png" />
															</span>
															<div class="apt-enable-disable-right pull-right">
																<label class="toggle-large" for="appointment_payment_method_Paytm">
																	<input <?php if($apt_settings->appointment_payment_method_Paytm=='E'){ echo 'checked="checked"';} ?> type="checkbox" class="apt-toggle-sh" id="appointment_payment_method_Paytm" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
																</label>
															</div>
														</h4>
													</div>
													<div id="collapseOne" class="<?php if($apt_settings->appointment_payment_method_Paytm=='D'){ echo 'hide-div';} ?> panel-collapse collapse_appointment_payment_method_Paytm">
														<div class="panel-body">
															<table class="form-inline apt-common-table">
																<tbody>
																	<tr>
																		<td><label><?php echo __("Merchant Key","apt");?></label></td>
																		<td>
																			<div class="form-group">
																				<input type="text" class="form-control" id="appointment_paytm_merchantkey" size="50" value="<?php echo $apt_settings->appointment_paytm_merchantkey ;?>" />
																			</div>	
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Merchant Id","apt");?></label></td>
																		<td>
																			<div class="form-group">
																				<input type="text" class="form-control" id="appointment_paytm_merchantid" value="<?php echo $apt_settings->appointment_paytm_merchantid;?>" size="50" />
																			</div>	
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Merchant Website URL","apt");?></label></td>
																		<td>
																			<div class="form-group">
																				<input type="text" class="form-control" id="appointment_paytm_website" value="<?php echo $apt_settings->appointment_paytm_website;?>" size="50" />
																			</div>	
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Merchant Channel Id","apt");?></label></td>
																		<td>
																			<div class="form-group">
																				<input type="text" class="form-control" id="appointment_paytm_channelid" value="<?php echo $apt_settings->appointment_paytm_channelid;?>" size="50" />
																			</div>	
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Merchant Industry Type","apt");?></label></td>
																		<td>
																			<div class="form-group">
																				<input type="text" class="form-control" id="appointment_paytm_industryid" value="<?php echo $apt_settings->appointment_paytm_industryid;?>" size="50" />
																			</div>	
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Test Mode","apt");?></label></td>
																		<td>
																			<div class="form-group">
																				<label class="toggle-large" for="appointment_paytm_testing_mode">
																					<input <?php if($apt_settings->appointment_paytm_testing_mode=='E'){ echo 'checked="checked"';} ?> type="checkbox" class="apt-toggle-sh" id="appointment_paytm_testing_mode" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
																				</label>
																			</div>	
																			<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("You can enable paytm test mode for sandbox account testing.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																		</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</div>
												</div>
												<!-- Paytm End -->
												<?php /* <div class="panel panel-default apt-payment-methods">
													<div class="panel-heading">
														<h4 class="panel-title">
															<span><?php echo __("2Checkout Payment Form","apt");?>
															<img class="apt-img-payments apt-2checkout" src="<?php echo $plugin_url_for_ajax; ?>/assets/images/2checkout.png" />
															</span>
															<div class="apt-enable-disable-right pull-right">
																<label class="toggle-large" for=		"appointment_payment_method_2Checkout">
																	<input <?php if($apt_settings->appointment_payment_method_2Checkout=='E'){ echo 'checked="checked"';} ?> type="checkbox" class="apt-toggle-sh" id="appointment_payment_method_2Checkout" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
																	
																	
																</label>
															</div>
														</h4>
													</div>
													
													<div id="collapseOne" class="<?php if($apt_settings->appointment_payment_method_2Checkout=='D'){ echo 'hide-div';} ?> panel-collapse collapse_appointment_payment_method_2Checkout">
														<div class="panel-body">
															<table class="form-inline apt-common-table">
																<tbody>
																	<tr>
																		<td><label><?php echo __("Publishable Key","apt");?></label></td>
																		<td>
																			<div class="form-group">
																				<input type="text" class="form-control" id="appointment_2checkout_publishablekey" size="50" value="<?php echo $apt_settings->appointment_2checkout_publishablekey ;?>" />
																			</div>	
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Private Key","apt");?></label></td>
																		<td>
																			<div class="form-group">
																				<input type="password" class="form-control" id="appointment_2checkout_privateKey" value="<?php echo $apt_settings->appointment_2checkout_privateKey;?>" size="50" />
																			</div>	
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Seller ID","apt");?></label></td>
																		<td>
																			<div class="form-group">
																				<input type="text" class="form-control" id="appointment_2checkout_sellerid" size="50" value="<?php echo $apt_settings->appointment_2checkout_sellerid ;?>" />
																			</div>	
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Test Mode","apt");?></label></td>
																		<td>
																			<div class="form-group">
																				<label class="toggle-large" for="appointment_paypal_testing_mode">
																					<input <?php if($apt_settings->appointment_2checkout_testing_mode=='E'){ echo 'checked="checked"';} ?> type="checkbox" class="apt-toggle-sh" id="appointment_2checkout_testing_mode" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
																				
																				</label>
																			</div>	
																		</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</div>
												</div>
												<div class="panel panel-default apt-payment-methods">
													<div class="panel-heading">
														<h4 class="panel-title">
															<span><?php echo __("Authorize.Net Payment Form","apt");?>
															<img class="apt-img-payments apt-authorize" src="<?php echo $plugin_url_for_ajax; ?>/assets/images/authorize-net.png" />
															</span>
															<div class="apt-enable-disable-right pull-right">
																<label class="toggle-large" for=		"appointment_payment_method_Authorizenet">
																	<input <?php if($apt_settings->appointment_payment_method_Authorizenet=='E'){ echo 'checked="checked"';} ?> type="checkbox" class="apt-toggle-sh" id="appointment_payment_method_Authorizenet" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","apt");?>" data-off="<?php echo __("Disable","apt");?>" data-onstyle="success" data-offstyle="danger" />
																	
																	
																</label>
															</div>
														</h4>
													</div>
													<div id="collapseOne" class="<?php if($apt_settings->appointment_payment_method_Authorizenet=='D'){ echo 'hide-div';} ?> panel-collapse collapse_appointment_payment_method_Authorizenet">
														<div class="panel-body">
															<table class="form-inline apt-common-table">
																<tbody>
																	<tr>
																		<td><label><?php echo __("Api Login Id","apt");?></label></td>
																		<td>
																			<div class="form-group">
																				<input type="text" class="form-control" id="appointment_authorizenet_api_loginid" size="50" value="<?php echo $apt_settings->appointment_authorizenet_api_loginid ;?>" />
																			</div>	
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Transaction Key","apt");?></label></td>
																		<td>
																			<div class="form-group">
																				<input type="text" class="form-control" id="appointment_authorizenet_transaction_key" value="<?php echo $apt_settings->appointment_authorizenet_transaction_key;?>" size="50" />
																			</div>	
																		</td>
																	</tr>
																	<tr>
																		<td><label><?php echo __("Sandbox Mode","apt");?></label></td>
																		<td>
																			<div class="form-group">
																				<label class="toggle-large" for="appointment_authorizenet_testing_mode">
																					<input <?php if($apt_settings->appointment_authorizenet_testing_mode=='E'){ echo 'checked="checked"';} ?> type="checkbox" class="apt-toggle-sh" id="appointment_authorizenet_testing_mode" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","apt");?>" data-off="<?php echo __("Disable","apt");?>" data-onstyle="success" data-offstyle="danger" />
																				
																				</label>
																			</div>	
																			<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("You can enable authorizenet test mode for sandbox account testing.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
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
								<a id="apt_save_payment_settings" class="btn btn-success apt-btn-width mt-20 ml-10" type="submit"><?php echo __("Save Setting","apt");?></a>
								
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="tab-pane apt-toggle-abs" id="email-setting">
				<form id="" method="post" type="" class="apt-email-settings" >
					<div class="panel panel-default">
						<div class="panel-heading">
							<h1 class="panel-title"><?php echo __("Email Settings","apt");?></h1>
						</div>
						<div class="panel-body">
							
						<div class="panel-body">
							<table class="form-inline apt-common-table" >
								<tbody>
									<tr>
										<td><label><?php echo __("Admin Email Notifications","apt");?></label></td>
										<td>
											<div class="form-group">
												<label class="toggle-large" for="appointment_admin_email_notification_status">
													<input <?php if($apt_settings->appointment_admin_email_notification_status=='E'){ echo 'checked="checked"';} ?> type="checkbox" id="appointment_admin_email_notification_status" class="apt-toggle-sh"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
												</label>
											</div>
										</td>
									</tr>

									<tr>
										<td><label><?php echo __("Manager Email Notifications","apt");?></label></td>
										<td>
											<div class="form-group">
												<label class="toggle-large" for="appointment_manager_email_notification_status">
													<input  <?php if($apt_settings->appointment_manager_email_notification_status=='E'){ echo 'checked="checked"';} ?> type="checkbox" id="appointment_manager_email_notification_status" class="apt-toggle-sh"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
												</label>
											</div>
										</td>
									</tr>
									
									<tr>
										<td><label><?php echo __("Staff Member Email Notifications","apt");?></label></td>
										<td>
											<div class="form-group">
												<label class="toggle-large" for="appointment_service_provider_email_notification_status">
													<input <?php if($apt_settings->appointment_service_provider_email_notification_status=='E'){ echo 'checked="checked"';} ?> type="checkbox" id="appointment_service_provider_email_notification_status" class="apt-toggle-sh"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
												</label>
											</div>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Client Email Notifications","apt");?></label></td>
										<td>
											<div class="form-group">
												<label class="toggle-large" for="appointment_client_email_notification_status">
													<input <?php if($apt_settings->appointment_client_email_notification_status=='E'){ echo 'checked="checked"';} ?> type="checkbox" id="appointment_client_email_notification_status" class="apt-toggle-sh"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
												</label>
											</div>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Sender Name","apt");?></label></td>
										<td>
											<div class="form-group">
												<input type="text" value="<?php echo $apt_settings->appointment_email_sender_name;?>" class="form-control w-300" id="appointment_email_sender_name" />
											</div>
										</td>
									</tr>
									<tr>
										<td><label><?php echo __("Sender Email Address (Appointment Admin Email)","apt");?></label></td>
										<td>
											<div class="form-group">
												<input type="email" class="form-control w-300" id="appointment_email_sender_address" value="<?php echo $apt_settings->appointment_email_sender_address;?>" placeholder="admin@example.com" />
											</div>
										</td>
									</tr>
									<tr><td class="np"><hr /></td><td class="np"><hr /></td></tr>
									<td><label><?php echo __("Appointment Reminder Buffer","apt");?></label></td>
										<td>
											<div class="form-group">
												<select id="appointment_email_reminder_buffer" class="selectpicker" data-size="5" data-width="auto" >
													<option value=""><?php echo __("Set Email & SMS Reminder Buffer","apt");?></option>
													<option <?php if($apt_settings->appointment_email_reminder_buffer=='60'){ echo 'selected';} ?> value="60"><?php echo __("1 Hrs","apt");?></option>
													<option <?php if($apt_settings->appointment_email_reminder_buffer=='120'){ echo 'selected';} ?> value="120"><?php echo __("2 Hrs","apt");?></option>
													<option <?php if($apt_settings->appointment_email_reminder_buffer=='180'){ echo 'selected';} ?> value="180"><?php echo __("3 Hrs","apt");?></option>
													<option <?php if($apt_settings->appointment_email_reminder_buffer=='240'){ echo 'selected';} ?> value="240"><?php echo __("4 Hrs","apt");?></option>
													<option <?php if($apt_settings->appointment_email_reminder_buffer=='300'){ echo 'selected';} ?> value="300"><?php echo __("5 Hrs","apt");?></option>
													<option <?php if($apt_settings->appointment_email_reminder_buffer=='360'){ echo 'selected';} ?> value="360"><?php echo __("6 Hrs","apt");?></option>
													<option <?php if($apt_settings->appointment_email_reminder_buffer=='420'){ echo 'selected';} ?> value="420"><?php echo __("7 Hrs","apt");?></option>
													<option <?php if($apt_settings->appointment_email_reminder_buffer=='480'){ echo 'selected';} ?> value="480"><?php echo __("8 Hrs","apt");?></option>
													<option <?php if($apt_settings->appointment_email_reminder_buffer=='1440'){ echo 'selected';} ?> value="1440"><?php echo __("1 Day","apt");?></option>
												</select>
											</div>	
										</td>
									</tr>
									
									
								</tbody>
								<tfoot>
									<tr>
										<td></td>
										<td>
											<a href="javascript:void(0)" id="apt_save_email_settings" name="" class="btn btn-success" type="submit"><?php echo __("Save Setting","apt");?></a>
										</td>
									</tr>
								</tfoot>
							</table>
							
						</div>
							
						</div>
					</div>
				</form>
			</div>
			<div class="tab-pane apt-toggle-abs" id="email-template">
				<div class="panel panel-default wf-100">
					<div class="panel-heading">
						<h1 class="panel-title"><?php echo __("Email Template Settings","apt");?></h1>
					</div>
					<!-- Client email templates -->
					<ul class="nav nav-tabs nav-justified">
						<li class="active"><a data-toggle="tab" href="#client-email-template"><?php echo __("Client Email Templates","apt");?></a></li>
						<li><a data-toggle="tab" href="#service-provider-email-template"><?php echo __("Service Provider Email Template","apt");?></a></li>
						<li><a data-toggle="tab" href="#admin-manager-email-template"><?php echo __("Admin/Manager Email Template","apt");?></a></li>
						
					</ul>
					<div class="tab-content">
						<div id="client-email-template" class="tab-pane fade in active">
							<h3><?php echo __("Client Email Templates","apt");?></h3>
								<div id="accordion" class="panel-group">
									<?php $apt_email_templates->user_type='C';
									$apt_email_templates->business_owner_id = get_current_user_id();
									$AM_templates = $apt_email_templates->readall_by_usertype();
									foreach($AM_templates as $AM_template){ ?>
									
									<div class="panel panel-default apt-email-panel">
										<div class="panel-heading">
											<h4 class="panel-title">
												<div class="apt-col11">
													<div class="apt-yes-no-email-right pull-left">
														<label for="email_template_status<?php echo $AM_template->id;?>">
															<input <?php if($AM_template->email_template_status=='e'){echo "checked='checked'";} ?> class="apt_update_emailstatus" type="checkbox" id="email_template_status<?php echo $AM_template->id;?>" data-eid="<?php echo $AM_template->id;?>" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","apt");?>" data-off="<?php echo __("Off","apt");?>" data-onstyle="success" data-offstyle="default" />
															
														</label>
													</div>	
													<span id="email_subject_label<?php echo $AM_template->id;?>" class="apt-template-name"><?php echo $AM_template->email_subject;?></span>
												</div>	
												<div class="pull-right apt-col1">
													<div class="pull-right">
														<div class="apt-show-hide pull-right">
															<input type="checkbox" name="apt-show-hide" class="apt-show-hide-checkbox" id="<?php echo $AM_template->id;?>" >
															<label class="apt-show-hide-label" for="<?php echo $AM_template->id;?>"></label>
														</div>
													</div>
												</div>
											</h4>
										</div>
										<div class="panel-collapse collapse emailtemplatedetail emaildetail_<?php echo $AM_template->id;?>">
											<div class="panel-body">
												<div class="apt-email-temp-collapse-div col-md-12 col-lg-12 col-xs-12 np">
													<form id="" method="post" type="" class="slide-toggle" >
														<div class="col-md-8 col-sm-8 col-xs-12 form-group">
															<label><?php echo __("Email Subject","apt");?></label>
															<input type="text" class="form-control" name="email_subject<?php echo $AM_template->id;?>" value="<?php echo $AM_template->email_subject;?>" />
															<label><?php echo __("Email Content","apt");?></label>
															<?php
															if($AM_template->email_message!=''){
																
															$content=stripslashes_deep($AM_template->email_message);
															}else{
															$content=stripslashes_deep($AM_template->default_message);
															}
															$editorName=  'email_message'.$AM_template->id;
															$editorId ='email_editor'.$AM_template->id;
															wp_editor($content,$editorId, array('textarea_name'=>$editorName,'media_buttons'=>true, 'teeny'=>false, 'tinymce' => false,'editor_class'=>'ak_wp_editor','wpautop' => true,'tabindex' => '','tabfocus_elements' => ':prev,:next','dfw' => false,'quicktags' => true)); ?>
															
															<a data-eid="<?php echo $AM_template->id;?>" class="btn btn-success apt-btn-width pull-left cb ml-15 mt-20 apt_save_emailtemplate" type="submit"><?php echo __("Save Template","apt");?></a>
															
														</div>
														<div class="col-md-4 col-sm-4 col-xs-12">
															<div class="apt-email-content-tags">
																<b><?php echo __("Tags","apt");?> </b><br />
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
							<h3><?php echo __("Service Provider Email Template","apt");?></h3>
							<div id="accordion" class="panel-group">
									<?php $apt_email_templates->user_type='SP';
									$AM_templates = $apt_email_templates->readall_by_usertype();
									foreach($AM_templates as $AM_template){ ?>
									
									<div class="panel panel-default apt-email-panel">
										<div class="panel-heading">
											<h4 class="panel-title">
												<div class="apt-col11">
													<div class="apt-yes-no-email-right pull-left">
														<label for="email_template_status<?php echo $AM_template->id;?>">
															<input <?php if($AM_template->email_template_status=='e'){echo "checked='checked'";} ?> class="apt_update_emailstatus" type="checkbox" id="email_template_status<?php echo $AM_template->id;?>" data-eid="<?php echo $AM_template->id;?>" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","apt");?>" data-off="<?php echo __("Off","apt");?>" data-onstyle="success" data-offstyle="default" />
														</label>
													</div>
												
													<span id="email_subject_label<?php echo $AM_template->id;?>" class="apt-template-name"><?php echo $AM_template->email_subject;?></span>
														
												</div>	
												<div class="pull-right apt-col1">
													<div class="pull-right">
														<div class="apt-show-hide pull-right">
															<input type="checkbox" name="apt-show-hide" class="apt-show-hide-checkbox" id="<?php echo $AM_template->id;?>" ><!--Added Serivce Id-->
															<label class="apt-show-hide-label" for="<?php echo $AM_template->id;?>"></label>
														</div>
													</div>
												</div>
											</h4>
										</div>
										<div class="panel-collapse collapse emailtemplatedetail emaildetail_<?php echo $AM_template->id;?>">
											<div class="panel-body">
												<div class="apt-email-temp-collapse-div col-md-12 col-lg-12 col-xs-12 np">
													<form id="" method="post" type="" class="slide-toggle" >
														<div class="col-md-8 col-sm-8 col-xs-12 form-group">
															<label><?php echo __("Email Subject","apt");?></label>
															<input type="text" class="form-control" name="email_subject<?php echo $AM_template->id;?>" value="<?php echo $AM_template->email_subject;?>" />
															<label><?php echo __("Email Content","apt");?></label>
															<?php
															if($AM_template->email_message!=''){
															$content=stripslashes_deep($AM_template->email_message);
															}else{
															$content=stripslashes_deep($AM_template->default_message);
															}
															$editorName=  'email_message'.$AM_template->id;
															$editorId ='email_editor'.$AM_template->id;
															wp_editor($content,$editorId, array('textarea_name'=>$editorName,'media_buttons'=>true, 'teeny'=>false, 'tinymce' => false,'editor_class'=>'ak_wp_editor','wpautop' => true,'tabindex' => '','tabfocus_elements' => ':prev,:next','dfw' => false,'quicktags' => true)); ?>
															
															
															<a data-eid="<?php echo $AM_template->id;?>" class="btn btn-success apt-btn-width pull-left cb ml-15 mt-20 apt_save_emailtemplate" type="submit"><?php echo __("Save Template","apt");?></a>
														</div>
														<div class="col-md-4 col-sm-4 col-xs-12">
															<div class="apt-email-content-tags">
																<b><?php echo __("Tags","apt");?> </b><br />
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
							<h3><?php echo __("Admin/Manager Provider Email Template","apt");?></h3>
							<div id="accordion" class="panel-group">
									<?php $apt_email_templates->user_type='AM';
									$AM_templates = $apt_email_templates->readall_by_usertype();
									foreach($AM_templates as $AM_template){ ?>
									
									<div class="panel panel-default apt-email-panel">
										<div class="panel-heading">
											<h4 class="panel-title">
												<div class="apt-col11">
													<div class="apt-yes-no-email-right pull-left">
														<label for="email_template_status<?php echo $AM_template->id;?>">
															<input <?php if($AM_template->email_template_status=='e'){echo "checked='checked'";} ?> class="apt_update_emailstatus" type="checkbox" id="email_template_status<?php echo $AM_template->id;?>" data-eid="<?php echo $AM_template->id;?>" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","apt");?>" data-off="<?php echo __("Off","apt");?>" data-onstyle="success" data-offstyle="default" />
														</label>
													</div>
													
													<span id="email_subject_label<?php echo $AM_template->id;?>" class="apt-template-name"><?php echo $AM_template->email_subject;?></span>
														
												</div>	
												<div class="pull-right apt-col1">
													<div class="pull-right">
														<div class="apt-show-hide pull-right">
															<input type="checkbox" name="apt-show-hide" class="apt-show-hide-checkbox" id="<?php echo $AM_template->id;?>" ><!--Added Serivce Id-->
															<label class="apt-show-hide-label" for="<?php echo $AM_template->id;?>"></label>
														</div>
													</div>
												</div>
											</h4>
										</div>
										<div class="panel-collapse collapse emailtemplatedetail emaildetail_<?php echo $AM_template->id;?>">
											<div class="panel-body">
												<div class="apt-email-temp-collapse-div col-md-12 col-lg-12 col-xs-12 np">
													<form id="" method="post" type="" class="slide-toggle" >
														<div class="col-md-8 col-sm-8 col-xs-12 form-group">
															<label><?php echo __("Email Subject","apt");?></label>
															<input type="text" class="form-control" name="email_subject<?php echo $AM_template->id;?>" value="<?php echo $AM_template->email_subject;?>" />
															<label><?php echo __("Email Content","apt");?></label>
															<?php
															if($AM_template->email_message!=''){
															$content=stripslashes_deep($AM_template->email_message);
															}else{
															$content=stripslashes_deep($AM_template->default_message);
															}
															$editorName=  'email_message'.$AM_template->id;
															$editorId ='email_editor'.$AM_template->id;

															wp_editor($content,$editorId, array('textarea_name'=>$editorName,'media_buttons'=>true, 'teeny'=>false, 'tinymce' => false,'editor_class'=>'ak_wp_editor','wpautop' => true,'tabindex' => '','tabfocus_elements' => ':prev,:next','dfw' => false,'quicktags' => true)); ?>
															
															<a data-eid="<?php echo $AM_template->id;?>" class="btn btn-success apt-btn-width pull-left cb ml-15 mt-20 apt_save_emailtemplate" type="submit"><?php echo __("Save Template","apt");?></a>
														</div>
														<div class="col-md-4 col-sm-4 col-xs-12">
															<div class="apt-email-content-tags">
																<b><?php echo __("Tags","apt");?> </b><br />
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
			<div class="tab-pane apt-toggle-abs" id="sms-reminder">
				<form id="" method="post" type="" class="apt-sms-reminder" >
					<div class="panel panel-default">
						<div class="panel-heading">
							<h1 class="panel-title"><?php echo __("SMS Reminder","apt");?></h1>
						</div>
						<div class="panel-body np">
							<div id="accordion" class="panel-group apt-all-sms-main">
								<div class="panel panel-default apt-sms-gateway nb">
									<div class="panel-heading">
										<h4 class="panel-title">
											<span><?php echo __("SMS Service","apt");?></span>
											<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="It will send sms to service provider and client for appointment booking"><i class="fa fa-info-circle fa-lg"></i></a>
											<div class="apt-enable-disable-right pull-right">
												<label class="toggle-large" for="appointment_sms_reminder_status">
													<input <?php if($apt_settings->appointment_sms_reminder_status=='E'){echo "checked='checked'";} ?> type="checkbox" class="apt-toggle-sh" name="appointment_sms_reminder_status" id="appointment_sms_reminder_status"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
												</label>
											</div>
											
										</h4>
									</div>
									
									<div id="collapseOne" class="panel-collapse collapse collapse_appointment_sms_reminder_status hide-div" <?php if($apt_settings->appointment_sms_reminder_status=='E'){ echo 'style="display: block;"'; } ?>>
										<div class="panel-body">
											<div id="accordion" class="panel-group">
												<div class="panel panel-default apt-sms-gateway nb">
													<div class="panel-heading">
														<h4 class="panel-title">
															<span><?php echo __("Twilio SMS Gateway","apt");?><img class="apt-sms-gateway-img" src="<?php echo $plugin_url_for_ajax; ?>/assets/images/twilio-logo.png" />
															</span>
															<div class="apt-enable-disable-right pull-right">
																<label class="toggle-large" for="appointment_sms_noti_twilio">
																	<input <?php if($apt_settings->appointment_sms_noti_twilio=='E'){echo "checked='checked'";} ?> type="checkbox" class="apt-toggle-sh" id="appointment_sms_noti_twilio" name="appointment_sms_noti_twilio"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
																</label>
															</div>
														</h4>
													</div>
										
												<div id="collapseOne" class="panel-collapse collapse_appointment_sms_noti_twilio <?php if($apt_settings->appointment_sms_noti_twilio=='D'){ echo 'hide-div';} ?>">
													<div class="panel-body padding-15">
													<table class="form-inline table apt-common-table table-hover table-bordered table-striped">
														<tr><th colspan="3"><?php echo __("Twilio Account Settings","apt");?></th></tr>
														<tbody>
															<tr>
																<td><label><?php echo __("Account SID","apt");?></label></td>
																<td colspan="2">
																	<div class="form-group apt-lgf">
																		<input type="text" class="form-control" name="appointment_twilio_sid" id="appointment_twilio_sid" size="70" value="<?php echo $apt_settings->appointment_twilio_sid;?>"/>
																	</div>	
																	<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Available from within your Twilio Account.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																</td>
															</tr>
															<tr>
																<td><label><?php echo __("Auth Token","apt");?></label></td>
																<td colspan="2">
																	<div class="form-group apt-lgf">
																		<input type="password" class="form-control" name="appointment_twilio_auth_token"
																		id="appointment_twilio_auth_token" size="70" value="<?php echo $apt_settings->appointment_twilio_auth_token;?>" />
																	</div>	
																	<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Available from within your Twilio Account.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																</td>
															</tr>
															<tr>
																<td><label><?php echo __("Twilio Sender Number","apt");?></label></td>
																<td colspan="2">
																	<div class="form-group apt-lgf">
																		<input type="text" class="form-control" name="appointment_twilio_number" id="appointment_twilio_number" size="70" value="<?php echo $apt_settings->appointment_twilio_number;?>" />
																	</div>	
																	<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Must be a valid number associated with your Twilio account.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																</td>
															</tr>
															<tr>
																<td id="hr"></td><td id="hr"></td><td id="hr"></td>
															</tr>
														</tbody>
														
														<tbody>
														
														<th colspan="3"><?php echo __("Twilio SMS Settings","apt");?></th>
															<tr>
																<td><label><?php echo __("Send SMS to Service Provider","apt");?></label></td>
																<td colspan="2">
																	<div class="form-group">
																		<label class="toggle-large" for="appointment_twilio_service_provider_sms_notification_status">
																			<input <?php if($apt_settings->appointment_twilio_service_provider_sms_notification_status=='E'){echo "checked='checked'";} ?> type="checkbox" name="appointment_twilio_service_provider_sms_notification_status" id="appointment_twilio_service_provider_sms_notification_status"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
																		
																		</label>
																	</div>	
																	<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Enable or Disable, Send SMS to Service provider for appointment booking info.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																</td>
															</tr>
															<tr>
																<td><label><?php echo __("Send SMS to Client","apt");?></label></td>
																<td colspan="2">
																	<div class="form-group">
																		<label class="toggle-large" for="appointment_twilio_client_sms_notification_status">
																			<input <?php if($apt_settings->appointment_twilio_client_sms_notification_status=='E'){echo "checked='checked'";} ?> type="checkbox" name="appointment_twilio_client_sms_notification_status" id="appointment_twilio_client_sms_notification_status" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
																		</label>
																	</div>	
																	<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Enable or Disable, Send SMS to client for appointment booking info.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																</td>
															</tr>
															<tr>
																<td><label><?php echo __("Send SMS to Admin","apt");?></label></td>
																<td colspan="2">
																	<div class="form-group">
																		<label class="toggle-large" for="appointment_twilio_admin_sms_notification_status">
																			<input <?php if($apt_settings->appointment_twilio_admin_sms_notification_status=='E'){echo "checked='checked'";} ?> type="checkbox" name="appointment_twilio_admin_sms_notification_status" id="appointment_twilio_admin_sms_notification_status" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
																		
																		</label>
																	</div>	
																	<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Enable or Disable, Send SMS to Admin for appointment booking info.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																</td>
															</tr>
															<tr>
																<td><label><?php echo __("Admin Phone Number","apt");?></label></td>
																<td colspan="2">
																	<div class="input-group appointment_twillio_cd">
																		 <!--<span class="input-group-addon"><span class="">+1</span></span>-->
																		 <input type="text" class="form-control" name="appointment_twilio_admin_phone_no" id="appointment_twilio_admin_phone_no" value="<?php echo $apt_settings->appointment_twilio_admin_phone_no;?>" />
																		 <input type="hidden" id="appointment_twilio_ccode_alph" value="<?php echo $apt_settings->appointment_twilio_ccode_alph;?>" />
																		 <input type="hidden" id="appointment_twilio_ccode" value="<?php echo $apt_settings->appointment_twilio_ccode;?>" />
																		 
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
												<div class="panel panel-default apt-sms-gateway">
													<div class="panel-heading">
														<h4 class="panel-title">
															<span><?php echo __("Plivo SMS Gateway","apt");?><img class="apt-sms-gateway-img" src="<?php echo $plugin_url_for_ajax; ?>/assets/images/plivo-logo.png" />
															</span>
															<div class="apt-enable-disable-right pull-right">
																<label class="toggle-large" for="appointment_sms_noti_plivo">
																	<input <?php if($apt_settings->appointment_sms_noti_plivo=='E'){echo "checked='checked'";} ?> type="checkbox" class="apt-toggle-sh" id="appointment_sms_noti_plivo" name="appointment_sms_noti_plivo"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
																</label>
															</div>
														</h4>
													</div>
										
												<div id="collapseOne" class="panel-collapse collapse_appointment_sms_noti_plivo <?php if($apt_settings->appointment_sms_noti_plivo=='D'){ echo 'hide-div';} ?> ">
													<div class="panel-body padding-15">
														<table class="form-inline table apt-common-table table-hover table-bordered table-striped">
															<tr><th colspan="3"><?php echo __("Plivo Account Settings","apt");?></th></tr>
															<tbody>
																<tr>
																	<td><label><?php echo __("Account SID","apt");?></label></td>
																	<td colspan="2">
																		<div class="form-group apt-lgf">
																			<input type="text" class="form-control" name="appointment_plivo_sid" id="appointment_plivo_sid" size="70" value="<?php echo $apt_settings->appointment_plivo_sid;?>"/>
																		</div>	
																		<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Available from within your Plivo Account.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td><label><?php echo __("Auth Token","apt");?></label></td>
																	<td colspan="2">
																		<div class="form-group apt-lgf">
																			<input type="password" class="form-control" name="appointment_plivo_auth_token"
																			id="appointment_plivo_auth_token" size="70" value="<?php echo $apt_settings->appointment_plivo_auth_token; ?>" />
																		</div>	
																		<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Available from within your Plivo Account.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td><label><?php echo __("Plivo Sender Number","apt");?></label></td>
																	<td colspan="2">
																		<div class="form-group apt-lgf">
																			<input type="text" class="form-control" name="appointment_plivo_number" id="appointment_plivo_number" size="70" value="<?php echo $apt_settings->appointment_plivo_number;?>" />
																		</div>	
																		<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Must be a valid number associated with your Plivo account.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td id="hr"></td><td id="hr"></td><td id="hr"></td>
																</tr>
															</tbody>
															
															<tbody>
															
															<th colspan="3"><?php echo __("Plivo SMS Settings","apt");?></th>
																<tr>
																	<td><label><?php echo __("Send SMS to Service Provider","apt");?></label></td>
																	<td colspan="2">
																		<div class="form-group">
																			<label class="toggle-large" for="appointment_plivo_service_provider_sms_notification_status">
																				<input <?php if($apt_settings->appointment_plivo_service_provider_sms_notification_status=='E'){echo "checked='checked'";} ?> type="checkbox" name="appointment_plivo_service_provider_sms_notification_status" id="appointment_plivo_service_provider_sms_notification_status"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
																			</label>
																		</div>	
																		<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Enable or Disable, Send SMS to Service provider for appointment booking info.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td><label><?php echo __("Send SMS to Client","apt");?></label></td>
																	<td colspan="2">
																		<div class="form-group">
																			<label class="toggle-large" for="appointment_plivo_client_sms_notification_status">
																				<input <?php if($apt_settings->appointment_plivo_client_sms_notification_status=='E'){echo "checked='checked'";} ?> type="checkbox" name="appointment_plivo_client_sms_notification_status" id="appointment_plivo_client_sms_notification_status" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
																			</label>
																		</div>	
																		<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Enable or Disable, Send SMS to client for appointment booking info.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td><label><?php echo __("Send SMS to Admin","apt");?></label></td>
																	<td colspan="2">
																		<div class="form-group">
																			<label class="toggle-large" for="appointment_plivo_admin_sms_notification_status">
																				<input <?php if($apt_settings->appointment_plivo_admin_sms_notification_status=='E'){echo "checked='checked'";} ?> type="checkbox" name="appointment_plivo_admin_sms_notification_status" id="appointment_plivo_admin_sms_notification_status" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
																			</label>
																		</div>	
																		<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Enable or Disable, Send SMS to Admin for appointment booking info.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td><label><?php echo __("Admin Phone Number","apt");?></label></td>
																	<td colspan="2">
																		<div class="input-group appointment_plivo_cd">
																			<!--<span class="input-group-addon"><span class="">+1</span></span>-->
																			<input type="text" class="form-control" name="appointment_plivo_admin_phone_no" id="appointment_plivo_admin_phone_no" value="<?php echo $apt_settings->appointment_plivo_admin_phone_no;?>" />
																									
																			<input type="hidden" id="appointment_plivo_ccode_alph" value="<?php echo $apt_settings->appointment_plivo_ccode_alph;?>" />
																			<input type="hidden" id="appointment_plivo_ccode" value="<?php echo $apt_settings->appointment_plivo_ccode;?>" />
																			
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
												<div class="panel panel-default apt-sms-gateway">
													<div class="panel-heading">
														<h4 class="panel-title">
															<span><?php echo __("Nexmo SMS Gateway","apt");?><img class="apt-sms-gateway-img" src="<?php echo $plugin_url_for_ajax; ?>/assets/images/nexmo_logo.png" />
															</span>
															<div class="apt-enable-disable-right pull-right">
																<label class="toggle-large" for="appointment_sms_noti_nexmo">
																	<input <?php if($apt_settings->appointment_sms_noti_nexmo=='E'){echo "checked='checked'";} ?> type="checkbox" class="apt-toggle-sh" id="appointment_sms_noti_nexmo" name="appointment_sms_noti_nexmo"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
																</label>
															</div>
														</h4>
													</div>
										
												<div id="collapseOne" class="panel-collapse collapse_appointment_sms_noti_nexmo <?php if($apt_settings->appointment_sms_noti_nexmo=='D'){ echo 'hide-div';} ?> ">
													<div class="panel-body padding-15">
														<table class="form-inline table apt-common-table table-hover table-bordered table-striped">
															<tr><th colspan="3"><?php echo __("Nexmo Account Settings","apt");?></th></tr>
															<tbody>
																<tr>
																	<td><label><?php echo __("Nexmo API Key","apt");?></label></td>
																	<td colspan="2">
																		<div class="form-group apt-lgf">
																			<input type="text" class="form-control" name="appointment_nexmo_apikey" id="appointment_nexmo_apikey" size="70" value="<?php echo $apt_settings->appointment_nexmo_apikey;?>"/>
																		</div>	
																		<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Available from within your Nexmo Account.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td><label><?php echo __("Nexmo API Secret","apt");?></label></td>
																	<td colspan="2">
																		<div class="form-group apt-lgf">
																			<input type="password" class="form-control" name="appointment_nexmo_api_secret"
																			id="appointment_nexmo_api_secret" size="70" value="<?php echo $apt_settings->appointment_nexmo_api_secret; ?>" />
																		</div>	
																		<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Available from within your Nexmo Account.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td><label><?php echo __("Nexmo From","apt");?></label></td>
																	<td colspan="2">
																		<div class="form-group apt-lgf">
																			<input type="text" class="form-control" name="appointment_nexmo_form" id="appointment_nexmo_form" size="70" value="<?php echo $apt_settings->appointment_nexmo_form;?>" />
																		</div>	
																		<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Must be a valid number associated with your Nexmo account.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td id="hr"></td><td id="hr"></td><td id="hr"></td>
																</tr>
															</tbody>
															
															<tbody>
															
															
																<tr>
																	<td><label><?php echo __("Send SMS to Service Provider","apt");?></label></td>
																	<td colspan="2">
																		<div class="form-group">
																			<label class="toggle-large" for="appointment_nexmo_send_sms_sp_status">
																				<input <?php if($apt_settings->appointment_nexmo_send_sms_sp_status=='E'){echo "checked='checked'";} ?> type="checkbox" name="appointment_nexmo_send_sms_sp_status" id="appointment_nexmo_send_sms_sp_status"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
																			</label>
																		</div>	
																		<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Enable or Disable, Send SMS to Service provider for appointment booking info.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td><label><?php echo __("Nexmo Send Sms To Client Status","apt");?></label></td>
																	<td colspan="2">
																		<div class="form-group">
																			<label class="toggle-large" for="appointment_nexmo_send_sms_client_status">
																				<input <?php if($apt_settings->appointment_nexmo_send_sms_client_status=='E'){echo "checked='checked'";} ?> type="checkbox" name="appointment_nexmo_send_sms_client_status" id="appointment_nexmo_send_sms_client_status" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
																			</label>
																		</div>	
																		<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Enable or Disable, Send SMS to client for appointment booking info.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td><label><?php echo __("Nexmo Send Sms To admin Status","apt");?></label></td>
																	<td colspan="2">
																		<div class="form-group">
																			<label class="toggle-large" for="appointment_nexmo_send_sms_admin_status">
																				<input <?php if($apt_settings->appointment_nexmo_send_sms_admin_status=='E'){echo "checked='checked'";} ?> type="checkbox" name="appointment_nexmo_send_sms_admin_status" id="appointment_nexmo_send_sms_admin_status" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
																			</label>
																		</div>	
																		<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Enable or Disable, Send SMS to Admin for appointment booking info.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td><label><?php echo __("Nexmo Admin Phone Number","apt");?></label></td>
																	<td colspan="2">
																		<div class="input-group appointment_nexmo_cd">
																			<!--<span class="input-group-addon"><span class="">+1</span></span>-->
																			<input type="text" class="form-control" name="appointment_nexmo_admin_phone_no" id="appointment_nexmo_admin_phone_no" value="<?php echo $apt_settings->appointment_nexmo_admin_phone_no;?>" />
																			
																			<input type="hidden" id="appointment_nexmo_ccode_alph" value="<?php echo $apt_settings->appointment_nexmo_ccode_alph;?>" />
																			<input type="hidden" id="appointment_nexmo_ccode" value="<?php echo $apt_settings->appointment_nexmo_ccode;?>" />
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
												<div class="panel panel-default apt-sms-gateway">
													<div class="panel-heading">
														<h4 class="panel-title">
															<span><?php echo __("Textlocal SMS Gateway","apt");?><img class="apt-sms-gateway-img" src="<?php echo $plugin_url_for_ajax; ?>/assets/images/textlocal-logo.png" />
															</span>
															<div class="apt-enable-disable-right pull-right">
																<label class="toggle-large" for="appointment_sms_noti_textlocal">
																	<input <?php if($apt_settings->appointment_sms_noti_textlocal=='E'){echo "checked='checked'";} ?> type="checkbox" class="apt-toggle-sh" id="appointment_sms_noti_textlocal" name="appointment_sms_noti_textlocal"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
																</label>
															</div>
														</h4>
													</div>
										
												<div id="collapseOne" class="panel-collapse collapse_appointment_sms_noti_textlocal <?php if($apt_settings->appointment_sms_noti_textlocal=='D'){ echo 'hide-div';} ?> ">
													<div class="panel-body padding-15">
														<table class="form-inline table apt-common-table table-hover table-bordered table-striped">
															<tr><th colspan="3"><?php echo __("Textlocal Account Settings","apt");?></th></tr>
															<tbody>
																<tr>
																	<td><label><?php echo __("Account API Key","apt");?></label></td>
																	<td colspan="2">
																		<div class="form-group apt-lgf">
																			<input type="text" class="form-control" name="appointment_textlocal_apikey" id="appointment_textlocal_apikey" size="70" value="<?php echo $apt_settings->appointment_textlocal_apikey;?>"/>
																		</div>	
																		<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Available from within your Textlocal Account.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td><label><?php echo __("Sender Name","apt");?></label></td>
																	<td colspan="2">
																		<div class="form-group apt-lgf">
																			<input type="text" class="form-control" name="appointment_textlocal_sender"
																			id="appointment_textlocal_sender" size="70" value="<?php echo $apt_settings->appointment_textlocal_sender; ?>" />
																		</div>	
																		<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Available from within your Textlocal Account.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td id="hr"></td><td id="hr"></td><td id="hr"></td>
																</tr>
															</tbody>
															
															<tbody>
															
															<th colspan="3"><?php echo __("Textlocal SMS Settings","apt");?></th>
																<tr>
																	<td><label><?php echo __("Send SMS To Service Provider","apt");?></label></td>
																	<td colspan="2">
																		<div class="form-group">
																			<label class="toggle-large" for="appointment_textlocal_service_provider_sms_notification_status">
																				<input <?php if($apt_settings->appointment_textlocal_service_provider_sms_notification_status=='E'){echo "checked='checked'";} ?> type="checkbox" name="appointment_textlocal_service_provider_sms_notification_status" id="appointment_textlocal_service_provider_sms_notification_status"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
																			</label>
																		</div>	
																		<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Enable or Disable, Send SMS to Service Provider for appointment booking info.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td><label><?php echo __("Send SMS To Client","apt");?></label></td>
																	<td colspan="2">
																		<div class="form-group">
																			<label class="toggle-large" for="appointment_textlocal_client_sms_notification_status">
																				<input <?php if($apt_settings->appointment_textlocal_client_sms_notification_status=='E'){echo "checked='checked'";} ?> type="checkbox" name="appointment_textlocal_client_sms_notification_status" id="appointment_textlocal_client_sms_notification_status"  data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
																			</label>
																		</div>	
																		<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Enable or Disable, Send SMS to Client for appointment booking info.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td><label><?php echo __("Send SMS To Admin","apt");?></label></td>
																	<td colspan="2">
																		<div class="form-group">
																			<label class="toggle-large" for="appointment_textlocal_admin_sms_notification_status">
																				<input <?php if($apt_settings->appointment_textlocal_admin_sms_notification_status=='E'){echo "checked='checked'";} ?> type="checkbox" name="appointment_textlocal_admin_sms_notification_status" id="appointment_textlocal_admin_sms_notification_status" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
																			</label>
																		</div>	
																		<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Enable or Disable, Send SMS to admin for appointment booking info.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
																	</td>
																</tr>
																<tr>
																	<td><label><?php echo __("Admin Phone Number","apt");?></label></td>
																	<td colspan="2">
																		<div class="input-group appointment_textlocal_cd">
																			<!--<span class="input-group-addon"><span class="">+1</span></span>-->
																			<input type="text" class="form-control" name="appointment_textlocal_admin_phone_no" id="appointment_textlocal_admin_phone_no" value="<?php echo $apt_settings->appointment_textlocal_admin_phone_no;?>" />
																			<input type="hidden" id="appointment_textlocal_ccode_alph" value="<?php echo $apt_settings->appointment_textlocal_ccode_alph;?>" />
																			<input type="hidden" id="appointment_textlocal_ccode" value="<?php echo $apt_settings->appointment_textlocal_ccode;?>" />
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
									
								<a id="apt_update_smssettings" name="apt_update_smssettings" class="btn btn-success mt-10 ml-15" href="javascript:void(0)"><?php echo __("Save Setting","apt");?></a>
							</div>
						</div>
					</div>
				</form>
			</div>

			<div class="tab-pane apt-toggle-abs" id="sms-template">
				<div class="panel panel-default wf-100">
					<div class="panel-heading">
						<h1 class="panel-title"><?php echo __("SMS Template Settings","apt");?></h1>
					</div>
					<!-- Client sms templates -->
					<ul class="nav nav-tabs nav-justified">
						<li class="active"><a data-toggle="tab" href="#client-sms-template"><?php echo __("Client SMS Templates","apt");?></a></li>
						<li><a data-toggle="tab" href="#service-provider-sms-template"><?php echo __("Service Provider SMS Template","apt");?></a></li>
						<li><a data-toggle="tab" href="#admin-manager-sms-template"><?php echo __("Admin/Manager SMS Template","apt");?></a></li>
					</ul>
					<div class="tab-content">
						<div id="client-sms-template" class="tab-pane fade in active">
							<h3><?php echo __("Client SMS Templates","apt");?></h3>
								<div id="accordion" class="panel-group">
									<?php 
									$apt_sms_templates->user_type='C';
									$apt_sms_templates->business_owner_id = get_current_user_id();
									
									$AM_templates = $apt_sms_templates->readall_by_usertype();

									foreach($AM_templates as $AM_template){ ?>
									
									<div class="panel panel-default apt-sms-panel">
										<div class="panel-heading">
											<h4 class="panel-title">
												<div class="apt-col11">
													<div class="apt-yes-no-sms-right pull-left">
														<label for="sms_template_status<?php echo $AM_template->id;?>">
															<input <?php if($AM_template->sms_template_status=='e'){echo "checked='checked'";} ?> class="apt_update_smsstatus" type="checkbox" id="sms_template_status<?php echo $AM_template->id;?>" data-eid="<?php echo $AM_template->id;?>" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","apt");?>" data-off="<?php echo __("Off","apt");?>" data-onstyle="success" data-offstyle="default" />
														</label>
													</div>
													<span id="sms_subject_label<?php echo $AM_template->id;?>" class="apt-template-name"><?php echo $AM_template->sms_subject;?></span>
														
												</div>	
												<div class="pull-right apt-col1">
													<div class="pull-right">
														<div class="apt-show-hide pull-right">
															<input type="checkbox" name="apt-show-hide" class="apt-show-hide-checkbox" id="sms<?php echo $AM_template->id;?>" data-id="<?php echo $AM_template->id;?>">
															<label class="apt-show-hide-label" for="sms<?php echo $AM_template->id;?>"></label>
														</div>
													</div>
												</div>
											</h4>
										</div>
										<div class="panel-collapse collapse smstemplatedetail smsdetail_<?php echo $AM_template->id;?>">
											<div class="panel-body">
												<div class="apt-sms-temp-collapse-div col-md-12 col-lg-12 col-xs-12 np">
													<form id="" method="post" type="" class="slide-toggle" >
														<div class="col-md-8 col-sm-8 col-xs-12 form-group">
															<label><?php echo __("SMS Content","apt");?></label>
															<?php
															if($AM_template->sms_message!=''){
															$content=stripslashes_deep($AM_template->sms_message);
															}else{
															$content=stripslashes_deep($AM_template->default_message);
															}
															$editorName=  'sms_message'.$AM_template->id;
															$editorId ='sms_editor'.$AM_template->id;
															wp_editor($content,$editorId, array('textarea_name'=>$editorName,'media_buttons'=>true, 'teeny'=>false, 'tinymce' => false,'editor_class'=>'ak_wp_editor','wpautop' => true,'tabindex' => '','tabfocus_elements' => ':prev,:next','dfw' => false,'quicktags' => true)); ?>
															
															<a data-eid="<?php echo $AM_template->id;?>" class="btn btn-success apt-btn-width pull-left cb ml-15 mt-20 apt_save_smstemplate" type="submit"><?php echo __("Save Template","apt");?></a>
														</div>
														<div class="col-md-4 col-sm-4 col-xs-12">
															<div class="apt-sms-content-tags">
																<b><?php echo __("Tags","apt");?> </b><br />
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
							<h3><?php echo __("Service Provider SMS Template","apt");?></h3>
							<div id="accordion" class="panel-group">
									<?php $apt_sms_templates->user_type='SP';
									$AM_templates = $apt_sms_templates->readall_by_usertype();
									foreach($AM_templates as $AM_template){ ?>
									
									<div class="panel panel-default apt-sms-panel">
										<div class="panel-heading">
											<h4 class="panel-title">
												<div class="apt-col11">
													<div class="apt-yes-no-sms-right pull-left">
														<label for="sms_template_status<?php echo $AM_template->id;?>">
															<input <?php if($AM_template->sms_template_status=='e'){echo "checked='checked'";} ?> class="apt-toggle-input apt_update_smsstatus" type="checkbox" id="sms_template_status<?php echo $AM_template->id;?>" data-eid="<?php echo $AM_template->id;?>" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","apt");?>" data-off="<?php echo __("Off","apt");?>" data-onstyle="success" data-offstyle="default" />
														</label>
													</div>
													
													<span id="sms_subject_label<?php echo $AM_template->id;?>" class="apt-template-name"><?php echo $AM_template->sms_subject;?></span>
														
												</div>	
												<div class="pull-right apt-col1">
													<div class="pull-right">
														<div class="apt-show-hide pull-right">
															<input type="checkbox" name="apt-show-hide" class="apt-show-hide-checkbox" id="sms<?php echo $AM_template->id;?>"  data-id="<?php echo $AM_template->id;?>"><!--Added Serivce Id-->
															<label class="apt-show-hide-label" for="sms<?php echo $AM_template->id;?>"></label>
														</div>
													</div>
												</div>
											</h4>
										</div>
										<div class="panel-collapse collapse smstemplatedetail smsdetail_<?php echo $AM_template->id;?>">
											<div class="panel-body">
												<div class="apt-sms-temp-collapse-div col-md-12 col-lg-12 col-xs-12 np">
													<form id="" method="post" type="" class="slide-toggle" >
														<div class="col-md-8 col-sm-8 col-xs-12 form-group">
															<label><?php echo __("SMS Content","apt");?></label>
															<?php
															if($AM_template->sms_message!=''){
															$content=stripslashes_deep($AM_template->sms_message);
															}else{
															$content=stripslashes_deep($AM_template->default_message);
															}
															$editorName=  'sms_message'.$AM_template->id;
															$editorId ='sms_editor'.$AM_template->id;
															wp_editor($content,$editorId, array('textarea_name'=>$editorName,'media_buttons'=>true, 'teeny'=>false, 'tinymce' => false,'editor_class'=>'ak_wp_editor','wpautop' => true,'tabindex' => '','tabfocus_elements' => ':prev,:next','dfw' => false,'quicktags' => true)); ?>
															
															<a data-eid="<?php echo $AM_template->id;?>" class="btn btn-success apt-btn-width pull-left cb ml-15 mt-20 apt_save_smstemplate" type="submit"><?php echo __("Save Template","apt");?></a>
														</div>
														<div class="col-md-4 col-sm-4 col-xs-12">
															<div class="apt-sms-content-tags">
																<b><?php echo __("Tags","apt");?> </b><br />
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
							<h3><?php echo __("Admin/Manager Provider SMS Template","apt");?></h3>
							<div id="accordion" class="panel-group">
									<?php $apt_sms_templates->user_type='AM';
									$AM_templates = $apt_sms_templates->readall_by_usertype();
									foreach($AM_templates as $AM_template){ ?>
									
									<div class="panel panel-default apt-sms-panel">
										<div class="panel-heading">
											<h4 class="panel-title">
												<div class="apt-col11">
													<div class="apt-yes-no-sms-right pull-left">
														<label for="sms_template_status<?php echo $AM_template->id;?>">
															<input <?php if($AM_template->sms_template_status=='e'){echo "checked='checked'";} ?> class="apt-toggle-input apt_update_smsstatus" type="checkbox" id="sms_template_status<?php echo $AM_template->id;?>" data-eid="<?php echo $AM_template->id;?>" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","apt");?>" data-off="<?php echo __("Off","apt");?>" data-onstyle="success" data-offstyle="default" />
														</label>
													</div>
													<span id="sms_subject_label<?php echo $AM_template->id;?>" class="apt-template-name"><?php echo $AM_template->sms_subject;?></span>
														
												</div>	
												<div class="pull-right apt-col1">
													<div class="pull-right">
														<div class="apt-show-hide pull-right">
															<input type="checkbox" name="apt-show-hide" class="apt-show-hide-checkbox" id="sms<?php echo $AM_template->id;?>" data-id="<?php echo $AM_template->id;?>"><!--Added Serivce Id-->
															<label class="apt-show-hide-label" for="sms<?php echo $AM_template->id;?>"></label>
														</div>
													</div>
												</div>
											</h4>
										</div>
										<div class="panel-collapse collapse smstemplatedetail smsdetail_<?php echo $AM_template->id;?>">
											<div class="panel-body">
												<div class="apt-sms-temp-collapse-div col-md-12 col-lg-12 col-xs-12 np">
													<form id="" method="post" type="" class="slide-toggle" >
														<div class="col-md-8 col-sm-8 col-xs-12 form-group">
															<label><?php echo __("SMS Content","apt");?></label>
															<?php
															if($AM_template->sms_message!=''){
															$content=stripslashes_deep($AM_template->sms_message);
															}else{
															$content=stripslashes_deep($AM_template->default_message);
															}
															$editorName=  'sms_message'.$AM_template->id;
															$editorId ='sms_editor'.$AM_template->id;

															wp_editor($content,$editorId, array('textarea_name'=>$editorName,'media_buttons'=>true, 'teeny'=>false, 'tinymce' => false,'editor_class'=>'ak_wp_editor','wpautop' => true,'tabindex' => '','tabfocus_elements' => ':prev,:next','dfw' => false,'quicktags' => true)); ?>
															
															<a data-eid="<?php echo $AM_template->id;?>" class="btn btn-success apt-btn-width pull-left cb ml-15 mt-20 apt_save_smstemplate" type="submit"><?php echo __("Save Template","apt");?></a>
														</div>
														<div class="col-md-4 col-sm-4 col-xs-12">
															<div class="apt-sms-content-tags">
																<b><?php echo __("Tags","apt");?> </b><br />
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
			
			<div class="tab-pane apt-toggle-abs" id="labels">
				<form id="" method="post" type="" class="apt-labels-settings" >
					<div class="panel panel-default">
						<div class="panel-heading">
							<h1 class="panel-title"><?php echo __("Labels","apt");?></h1>
						</div>
						<div class="panel-body">
							<div class="table-responsive"> 
								<table class="form-inline apt-common-table table table-hover table-bordered table-striped">
									<tbody>
										<tr><th colspan="3"><?php echo __("Appointkart Frontend First Step Labels","apt");?></th></tr>
										<tr>
											<th><?php echo __("Original Label","apt");?></th>
											<th><?php echo __("Custom Label","apt");?></th>
										</tr>
										<tr>
											<td><?php echo __("Choose Service","apt");?></td>
											<td><div class="form-group">
												<input class="form-control" type="text" name="" value="<?php echo __("Choose Date,Time and Provider","apt");?>" />
												</div>
											</td>
										</tr>
										<tr>
											<td><?php echo __("Choose Service","apt");?></td>
											<td><div class="form-group">
												<input class="form-control" type="text" name="" value="<?php echo __("Choose Date,Time and Provider","apt");?>" />
												</div>
											</td>
										</tr>
										<tr><th colspan="3"><?php echo __("Appointkart Frontend First Step Labels","apt");?></th></tr>
										<tr>
											<td><?php echo __("Your Appointments","apt");?></td>
											<td><div class="form-group">
												<input class="form-control" type="text" name="" value="<?php echo __("Your Appointments","apt");?>" />
												</div>
											</td>
										</tr>
										<tr>
											<td><?php echo __("Total","apt");?></td>
											<td><div class="form-group">
												<input class="form-control" type="text" name="" value="<?php echo __("Total","apt");?>" />
												</div>
											</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											
											<td colspan="3">
												<button id="" name="" class="btn btn-success" type="submit"><?php echo __("Save Setting","apt");?></a>
												<button type="reset" class="btn btn-default ml-30"><?php echo __("Reset","apt");?></button>
									
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="tab-pane apt-toggle-abs" id="custom-form-fields">
				<div id="" class="apt-custom-form-fields" >
					<div class="panel panel-default">
						<div class="panel-heading">
							<h1 class="panel-title"><?php echo __("Custom Form Fields","apt");?></h1>
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
			<div class="tab-pane apt-toggle-abs" id="promocode">
				<div class="panel panel-default">
						<div class="panel-heading">
							<h1 class="panel-title"><?php echo __("Promocode","apt");?></h1>
						</div>
						<ul class="nav nav-tabs">
							<li class="apt_promocode_list active"><a data-toggle="tab" href="#apt_promocode_list"><?php echo __("Promocodes","apt");?></a></li>
							<li class="apt_addnew_promocode"><a data-toggle="tab" href="#apt_addnew_promocode"><?php echo __("Add New Promocode","apt");?></a></li>
							<li class="apt_update_promocode_tab"><a data-toggle="tab" class="apt-update-promocode hide-div" href="#apt_update_promocode"><?php echo __("Update Promocode","apt");?></a></li>
							
						</ul>
						<?php $apt_all_coupons = $apt_coupons->readAll();?>
						<div class="tab-content">							
							<div id="apt_promocode_list" class="tab-pane fade in active">			
								<h3><?php echo __("Promocodes list","apt");?></h3>
								<div class="table-responsive">
									<table id="apt-promocode-list" class="display table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th><?php echo __("Coupon Code","apt");?></th>
												<th><?php echo __("Coupon Expire On","apt");?></th>
												<th><?php echo __("Coupon Value","apt");?></th>
												<th><?php echo __("Coupon Limit","apt");?></th>
												<th><?php echo __("Coupon Used","apt");?></th>
												<th><?php echo __("Coupon Status","apt");?></th>
												<th><?php echo __("Actions","apt");?></th>
											</tr>
										</thead>
										<tbody id="coupon_list">
											<?php foreach($apt_all_coupons as $apt_coupons){ ?>
											<tr id="coupon_detail<?php echo $apt_coupons->id;?>">	
												<td><?php echo $apt_coupons->coupon_code;?></td>
												<td><?php echo date_i18n(get_option('appointment_datepicker_format'.'_'.get_current_user_id()),strtotime($apt_coupons->coupon_expires_on));?></td>
												<td><?php echo $apt_coupons->coupon_value;?></td>
												<td><?php echo $apt_coupons->coupon_limit;?></td>
												<td><?php echo $apt_coupons->coupon_used;?></td>
												<td>
													<label class="toggle-large apt-toggle-medium" for="promocode_status<?php echo $apt_coupons->id;?>">
													<input <?php if($apt_coupons->coupon_status=='E'){ echo "checked='checked'";} ?> data-cid="<?php echo $apt_coupons->id;?>" class="apt-toggle-medium-input apt_update_couponstatus" type="checkbox" id="promocode_status<?php echo $apt_coupons->id;?>" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enabled","apt");?>" data-off="<?php echo __("Disabled","apt");?>" data-onstyle="success" data-offstyle="danger" />
													</label>
												</td>
												<td>
													<a data-cid="<?php echo $apt_coupons->id;?>" href="javascript:void(0)" id="update_promocode<?php echo $apt_coupons->id;?>" class="btn-circle btn-info btn-xs apt_update_promocode" title="<?php echo __("Edit coupon code","apt");?>"><i class="fa fa-pencil-square-o"></i></a>
													
													<a data-poid="apt-popover-coupon<?php echo $apt_coupons->id; ?>" id="apt-delete-coupon<?php echo $apt_coupons->id; ?>" class="pull-right btn-circle btn-danger btn-sm apt-delete-popover" rel="popover" data-placement='bottom' title="<?php echo __("Delete this coupon?","apt");?>"> <i class="fa fa-trash" title="<?php echo __("Delete coupon","apt");?>"></i></a>
													<div class="apt-popover" id="apt-popover-coupon<?php echo $apt_coupons->id; ?>" style="display: none;">
														<div class="arrow"></div>
															<table class="form-horizontal" cellspacing="0">
																<tbody>
																	<tr>
																		<td>
																			<a data-id="<?php echo $apt_coupons->id; ?>" value="Delete" class="btn btn-danger btn-sm mr-10 apt_delete_coupon" type="submit"><?php echo __("Yes","apt");?></a>
																			<a data-poid="apt-popover-coupon<?php echo $apt_coupons->id; ?>" class="btn btn-default btn-sm apt-close-popover-delete" href="javascript:void(0)"><?php echo __("Cancel","apt");?></a>
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
							<div id="apt_addnew_promocode" class="tab-pane fade">
								<h3><?php echo __("Add New Promocode","apt");?></h3>
								<form id="apt_create_coupon_form" method="post" type="" class="" >
									<div class="table-responsive"> 
										<table class="form-inline apt-common-table">
											<tbody>
												<tr>
													<td><?php echo __("Coupon Code","apt");?></td>
													<td>
														<div class="form-group">
															<input id="apt_coupon_code" type="text" class="form-control" name="apt_coupon_code" value="" placeholder="<?php echo __("Your Coupon Code","apt");?>" />
														</div>
													</td>
												</tr>
												<tr>
													<td><?php echo __("Coupon Type","apt");?></td>
													<td>
														<div class="form-group">
															<select id="apt_coupon_type" name="apt_coupon_type" class="selectpicker" data-size="3"  style="display: none;">
																<option value="P"><?php echo __("Percentage","apt");?></option> 					
																<option value="F"><?php echo __("Flat","apt");?></option> 		
															</select>
														</div>
													</td>
												</tr>
												<tr>
													<td><?php echo __("Value","apt");?></td>
													<td>
														<div class="form-group">
															<input id="apt_coupon_value" type="text" class="form-control" name="apt_coupon_value" value="" placeholder="<?php echo __("Value","apt");?>" />
														</div>
														<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Coupon Value would be consider as percentage in percentage mode and in flat mode it will be consider as amount.No need to add percentage sign it will auto added.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
													</td>
												</tr>
												<tr>
													<td><?php echo __("Limit","apt");?></td>
													<td>
														<div class="form-group">
															<input id="apt_coupon_limit" type="text" class="form-control" name="apt_coupon_limit" value="" placeholder="<?php echo __("Coupon Limit","apt");?>" />
														</div>
														<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Coupon code will work for such limit","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
													</td>
												</tr>
												<tr>
													<td><?php echo __("Expiry Date","apt");?></td>
													<td>
														<div class="form-group input-group">
															<input name="apt_coupon_expiry" id="apt_coupon_expiry" class="form-control apt_coupon_expiry" data-provide="datepicker" value="<?php echo date_i18n('m/d/Y');?>" />
															<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
														</div>	
														<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Coupon code will work for such date","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
													</td>
												</tr>
											<tbody>
													<tr>
														<td></td>
														<td>
															<a href="javascript:void(0)" id="apt_create_coupon" name="apt_create_coupon" class="btn btn-success"><?php echo __("Create","apt");?></a>
														</td>
													</tr>
												</tbody>
											</tbody>
										
										</table>
									</div>	
								</form>
								
							</div>
							<div id="apt_update_promocode" class="tab-pane fade active">
								<h3><?php echo __("Update Promocode","apt");?></h3>									
									<div class="table-responsive"> 
									<?php foreach($apt_all_coupons as $apt_coupons){ ?>
									 <form id="apt_update_promocode_info<?php echo $apt_coupons->id;?>" method="post" type="" class="apt_update_promocode_info" >
										<table id="apt_coupon_update_info<?php echo $apt_coupons->id;?>" class="form-inline apt-common-table  apt_coupon_update_info hide-div">
										
											<tbody>								
												<tr>
													<td><?php echo __("Coupon Code","apt");?></td>
													<td>
														<div class="form-group">
															<input id="apt_uc_code<?php echo $apt_coupons->id;?>" type="text" class="form-control" name="apt_uc_code" value="<?php echo $apt_coupons->coupon_code;?>" placeholder="<?php echo __("Your Coupon Code","apt");?>" />
														</div>
													</td>
												</tr>
												<tr>
													<td><?php echo __("Coupon Type","apt");?></td>
													<td>
														<div class="form-group">
															<select id="apt_uc_type<?php echo $apt_coupons->id;?>" name="apt_uc_type" class="selectpicker" data-size="3"  style="display: none;">
																<option <?php if($apt_coupons->coupon_type=='P'){ echo "selected";} ?> value="P"><?php echo __("Percentage","apt");?></option> 					
																<option <?php if($apt_coupons->coupon_type=='F'){ echo "selected";} ?> value="F"><?php echo __("Flat","apt");?></option> 		
															</select>
														</div>
													</td>
												</tr>
												<tr>
													<td><?php echo __("Value","apt");?></td>
													<td>
														<div class="form-group">
															<input id="apt_uc_value<?php echo $apt_coupons->id;?>" type="text" class="form-control" name="apt_uc_value" value="<?php echo $apt_coupons->coupon_value;?>" placeholder="<?php echo __("Value","apt");?>" />
														</div>
														<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Coupon Value would be consider as percentage in percentage mode and in flat mode it will be consider as amount.No need to add percentage sign it will auto added.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
													</td>
												</tr>
												<tr>
													<td><?php echo __("Limit","apt");?></td>
													<td>
														<div class="form-group">
															<input id="apt_uc_limit<?php echo $apt_coupons->id;?>" type="text" class="form-control" name="apt_uc_limit" value="<?php echo $apt_coupons->coupon_limit;?>" placeholder="<?php echo __("Coupon Limit","apt");?>" />
														</div>
														<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Coupon code will work for such limit","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
													</td>
												</tr>
												<tr>
													<td><?php echo __("Expiry Date","apt");?></td>
													<td>
														<div  class="form-group input-group">
															<input id="apt_uc_expiry<?php echo $apt_coupons->id;?>" class="form-control apt_coupon_expiry" data-provide="datepicker" value="<?php echo date_i18n('m/d/Y',strtotime($apt_coupons->coupon_expires_on));?>"/>
															<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
														</div>	
														<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Coupon code will work for such date","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
													</td>
												</tr>
											<tbody>
													<tr>
														<td></td>
														<td>
															<a href="javascript:void(0)" id="<?php echo $apt_coupons->id;?>" name="" class="btn btn-success apt_update_coupon_info" ><?php echo __("Update","apt");?></a>
														</td>
													</tr>
													<input id="apt_uc_status<?php echo $apt_coupons->id;?>" type="hidden" name="apt_uc_status" value="<?php echo $apt_coupons->coupon_status;?>"/>
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
			<div class="tab-pane apt-toggle-abs" id="google-calendar">
				<?php
				$GcclientID = get_option('apt_gc_client_id'.'_'.get_current_user_id());
				$GcclientSecret = get_option('apt_gc_client_secret'.'_'.get_current_user_id());
				$GcEDvalue = get_option('apt_gc_status'.'_'.get_current_user_id());
				?>
				<form id="" method="post" type="" class="apt-google-calendar" >
					<div class="panel panel-default">
						<div class="panel-heading apt-top-right">
							<h1 class="panel-title"><?php echo __("Google Calendar","apt");?> </h1>
						</div>
						<div class="panel-body">
							<table class="form-inline apt-common-table">
								<tbody>
									<tr>
										<td><?php echo __("Add Appointments To Google Calendar","apt");?></td><td><input class="gc_enable_disable" data-on='<?php echo __("Yes","apt");?>' data-off='<?php echo __("No","apt");?>' data-onstyle="primary" data-offstyle="default" data-toggle="toggle" type="checkbox" name="appointup_gc_status" <?php if(get_option('apt_gc_status'.'_'.get_current_user_id()) == 'Y') { echo ' checked  '; } ?> /></td>
									</tr>
									<tr>
										<td><?php echo __("Google Calender Client ID","apt");?></td>
										<td>
											<div class="form-group">
												<input type="text" class="form-control" size="35" id="apt_gc_client_id" value="<?php echo get_option('apt_gc_client_id' . '_' . get_current_user_id()); ?>" placeholder="<?php echo __("Your Client ID","apt");?>" />
											</div>
										</td>
									</tr>
									<tr>
										<td><?php echo __("Google Calender Client Secret","apt");?></td>
										<td>
											<div class="form-group">
												<input type="text" class="form-control" size="35" id="apt_gc_client_secret" value="<?php echo get_option('apt_gc_client_secret' . '_'. get_current_user_id()); ?>" placeholder="<?php echo __("Your Client Secret ID","apt");?>" />
											</div>
										</td>
									</tr>
									<tr>
										<td><?php echo __("Google Calender Frontend URL","apt");?></td>
										<td>
											<div class="form-group">
												<input type="text" class="form-control" size="35" id="apt_gc_frontend_url" value="<?php echo get_option('apt_gc_frontend_url'.'_'.get_current_user_id()); ?>" placeholder="<?php echo __("Your Frontend URL","apt");?>" />
											</div>
										</td>
									</tr>
									<tr>
										<td><?php echo __("Google Calender Admin URL","apt");?></td>
										<td>
											<div class="form-group">
												<input type="text" class="form-control" size="35" id="apt_gc_admin_url" value="<?php echo get_option('apt_gc_admin_url'.'_'.get_current_user_id()); ?>" placeholder="<?php echo __("Your Admin URL","apt");?>" />
											</div>
										</td>
									</tr>
									<tr>
										<td><?php echo __("Two Way Sync","apt");?></td><td><input class="appointup_gc_twowaysync" data-on='<?php echo __("Yes","apt");?>' data-off='<?php echo __("No","apt");?>' data-onstyle="primary" data-offstyle="default"  data-toggle="toggle" type="checkbox" name="appointup_gc_twowaysync" <?php if(get_option('apt_gc_two_way_sync_status' . '_' . get_current_user_id()) == 'Y') { echo ' checked  '; } ?> /></td>
									</tr>
									
									<?php
									 if($GcclientID!='' &&	$GcclientSecret!='' &&	$GcEDvalue=='Y'){
										 $client = new Google_Client();
										 $client->setApplicationName('Apointment Google Calender');
										 $client->setClientId($GcclientID);
										 $client->setClientSecret($GcclientSecret);
										 $client->setRedirectUri(get_option('apt_gc_admin_url' . '_' . get_current_user_id()));
										 $client->setDeveloperKey($GcclientID);
										 $client->setScopes(array('https://www.googleapis.com/auth/userinfo.email','https://www.googleapis.com/auth/calendar','https://www.google.com/calendar/feeds/'));
										 $client->setAccessType('offline');
										 $client->setApprovalPrompt( 'force' );
										 
										 if(isset($_GET['GC_action']) && $_GET['GC_action']=='gcd'){
											$revokeaccesstoken = get_option('apt_gc_token' . '_' . get_current_user_id());
											$client->revokeToken($revokeaccesstoken);
											update_option('apt_gc_token' . '_' . get_current_user_id(), '');
											header('Location:'.site_url().'/wp-admin/admin.php?page=settings_submenu');
										 }
										 
										 
										 if(isset($_GET['code']) && $_GET['code']!=''){
											$access_token =  $client->authenticate($_GET['code']);
											update_option('apt_gc_token' . '_' . get_current_user_id(),$access_token);
											header('Location:'.site_url().'/wp-admin/admin.php?page=settings_submenu');
										 }
										 
										 $curlcalenders = curl_init();
										 curl_setopt_array($curlcalenders, array(
										  CURLOPT_RETURNTRANSFER => 1,
										  CURLOPT_URL => site_url().'/wp-content/plugins/appointment/assets/GoogleCalendar/callist.php?pid=0',
										  CURLOPT_FRESH_CONNECT =>true,
										  CURLOPT_USERAGENT => 'Appointment'
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
										<td><?php echo __("Select Calendar","apt");?></td>
										<td><select name="appointup_gc_id" class="appointup_gc_id"><?php	
										for($i=0;$i<count($calenders);$i++){
											foreach($calenders[$i] as $calinfo){
												$calenderInfo = explode('##==##',$calinfo);
												$selected='';
												if(get_option('apt_gc_id'.'_'.get_current_user_id())==$calenderInfo[1]){ $selected="selected";}
												echo "<option ".$selected." value='".$calenderInfo[1]."'>".$calenderInfo[0]."</option>";
											}
										}
										?></select> <a style="text-decoration:underline;color:#1E8CBE;" href="<?php echo site_url();?>/wp-admin/admin.php?page=settings_submenu&GC_action=gcd"><?php echo __("Disconnect","apt");?></a></td>
									</tr><?php
									}  } ?>
														
								</tbody>
								<tfoot>
									<tr>
										<td></td>
										<td>
											<a id="apt_save_gc_settings" name="" class="btn btn-success"><?php echo __("Save Setting","apt");?></a>
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
<?php if(get_option('appointment_payment_method_Paypal'.'_'.get_current_user_id())=='E' || get_option('appointment_payment_method_Stripe'.'_'.get_current_user_id())=='E' || get_option('appointment_payment_method_Authorizenet'.'_'.get_current_user_id())=='E'|| get_option('appointment_payment_method_2Checkout'.'_'.get_current_user_id())=='E') {
		$any_payment_method_enable = 'E';
} else {
		$any_payment_method_enable = 'D';
}
if(get_option('default_company_country_flag'.'_'.get_current_user_id()) != ''){
	$default_flag = get_option('default_company_country_flag'.'_'.get_current_user_id());
}else{
	$default_flag = "us";
}
?>	
<script>
var general_setting_pd_ed={"payment_gateway_status":"<?php echo $any_payment_method_enable; ?>"};
var general_settings_ajax_path={"ajax_path":"<?php echo $plugin_url_for_ajax; ?>"};
var general_settings_default_flag={"default_flag":"<?php echo $default_flag; ?>"};
</script>