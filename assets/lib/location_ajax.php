<?php 
session_start();
$root = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));
			if (file_exists($root.'/wp-load.php')) {
			require_once($root.'/wp-load.php');
}
if ( ! defined( 'ABSPATH' ) ) exit;  /* direct access prohibited  */

	$location = new appointment_location();
	$staff = new appointment_staff();
	$plugin_url_for_ajax = plugins_url('',dirname(dirname(__FILE__)));
/* Remove Location Image */
if(isset($_POST['action'],$_POST['mediaid'],$_POST['mediapath']) && $_POST['action']=='delete_image'){
		$location->id= $_POST['mediaid'];
		$location->image=''; 
		$location->remove_location_image(); 	
		unlink($root.'/wp-content/uploads'.$_POST['mediapath']);
}
/*Sort Location Position*/
if(isset($_POST['position'],$_POST['location_action']) && $_POST['position']!='' && $_POST['location_action']=='sort_location_position'){
		parse_str($_POST['position'], $output);
		$order_counter=0;
		foreach ($output as $order_no){
			foreach($order_no as $order_value){			 
			  $location->position = $order_counter;
			  $location->id = $order_value;
			  $location->sort_location_position();
			$order_counter++;
			}
		}
}	
?>

	
<?php
	
/* Update Location Detail */		
if(isset($_POST['location_action'],$_POST['location_id']) && $_POST['location_action']=='update_location' && $_POST['location_id']!=''){
		$location->id= $_POST['location_id'];
		$locationInfo = $location->readOne(); 
		if($locationInfo[0]->image!='' && $locationInfo[0]->image !=$_POST['image']){
			unlink($root.'/wp-content/uploads'.$locationInfo[0]->image);
		}
		
		$location->location_title= filter_var($_POST['location_title'], FILTER_SANITIZE_STRING);
		$location->description= filter_var($_POST['description'], FILTER_SANITIZE_STRING);
		$location->image= $_POST['image'];
		$location->email= filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
		$location->phone= $_POST['phone'];
		$location->address= filter_var($_POST['address'], FILTER_SANITIZE_STRING);
		$location->city= filter_var($_POST['city'], FILTER_SANITIZE_STRING);
		$location->state= filter_var($_POST['state'], FILTER_SANITIZE_STRING);
		$location->zip= filter_var($_POST['zip'], FILTER_SANITIZE_STRING);
		$location->country= filter_var($_POST['country'], FILTER_SANITIZE_STRING);
		$location->update(); 
		$location_sortby = get_option('appointment_location_sortby');
		$apt_locations = $location->readAll('','','');
		$all_locations = $location->countAll();
		$temp_locatio_name = array();
		$all_city_state_array = array();
		foreach($apt_locations as $apt_location){ 
			if($location_sortby=='city'){$locationsort = $apt_location->city;}
			else{$locationsort = $apt_location->state;}
			$all_city_state_array[]=$locationsort;					 
		}
		
		?>
		
		<link rel="stylesheet" href="<?php echo $plugin_url_for_ajax; ?>/assets/bootstrap/bootstrap-toggle.min.css" type="text/css" media="all">
		<script src="<?php echo $plugin_url_for_ajax; ?>/assets/js/bootstrap-toggle.min.js" type="text/javascript" ></script>
	
			<ul class="nav nav-tab nav-stacked apt-left-locations">
				<li class="active apt-left-location-menu-li br-2 getsorted_locations" data-location_sortby="all">
				<span class="apt-location-sort-icon"><i class="fa fa-th"></i></span>
					<a href="javascript:void(0);" data-toggle="pill">
						<span class="apt-location-name"><?php echo __("All States/City","apt");?> (<?php echo $all_locations; ?>)</span>
					</a>
				</li>
			</ul>	
			<ul class="nav nav-tab nav-stacked apt-left-location" id="sortable-city-state">
				<?php foreach($apt_locations as $apt_location){ 
					if($location_sortby=='city'){ $locationsort = $apt_location->city;}else{ $locationsort = $apt_location->state;}
					
					if(!in_array($locationsort,$temp_locatio_name)){
						$temp_locatio_name[]=$locationsort;
						
						 $city_state_locations = array_count_values($all_city_state_array);
				
					?>
				<li class="active apt-left-location-menu-li br-2 getsorted_locations" data-location_sortby="<?php if($location_sortby=='city'){ echo $apt_location->city;}else{ echo $apt_location->state;} ?>" >
				<span class="apt-location-sort-icon"><i class="fa fa-th-list"></i></span>
					<a href="javascript:void(0);" data-toggle="pill">
						<span class="apt-location-name"><?php if($location_sortby=='city'){ echo $apt_location->city;}else{ echo $apt_location->state;} ?> (<?php echo $city_state_locations[$locationsort]; ?>)</span>
					</a>
				</li>	
			<?php } } ?>	
			</ul><?php	
		
}
	
/* Delete Location Permanently */		
if(isset($_POST['location_action'],$_POST['location_id']) && $_POST['location_action']=='delete_location' && $_POST['location_id']!=''){
		$location->id= $_POST['location_id'];
		$location->delete();
}		
if(isset($_POST['location_action']) && ($_POST['location_action']=='delete_location' || $_POST['location_action']=='sort_location_position')){	
		$bwid = $_POST['bwid'];	
		$location_sortby = get_option('appointment_location_sortby'.'_'.$bwid);
		$apt_locations = $location->readAll('','','');
		$all_locations = $location->countAll();
		$temp_locatio_name = array();
		$all_city_state_array = array();
		foreach($apt_locations as $apt_location){ 
			if($location_sortby=='city'){$locationsort = $apt_location->city;}
			else{$locationsort = $apt_location->state;}
			$all_city_state_array[]=$locationsort;					 
		}
		?>
		<ul class="nav nav-tab nav-stacked apt-left-locations">
				<li class="active apt-left-location-menu-li br-2 getsorted_locations" data-location_sortby="all">
				<span class="apt-location-sort-icon"><i class="fa fa-th"></i></span>
					<a href="javascript:void(0);" data-toggle="pill">
						<span class="apt-location-name"><?php echo __("All States/City","apt");?> (<?php echo $all_locations; ?>)</span>
					</a>
				</li>
			</ul>	
			<ul class="nav nav-tab nav-stacked apt-left-location" id="sortable-city-state">
				<?php foreach($apt_locations as $apt_location){ 
					if($location_sortby=='city'){ $locationsort = $apt_location->city;}else{ $locationsort = $apt_location->state;}
					
					if(!in_array($locationsort,$temp_locatio_name)){
						$temp_locatio_name[]=$locationsort;
						
						 $city_state_locations = array_count_values($all_city_state_array);
				
					?>
				<li class="active apt-left-location-menu-li br-2 getsorted_locations" data-location_sortby="<?php if($location_sortby=='city'){ echo $apt_location->city;}else{ echo $apt_location->state;} ?>" >
				<span class="apt-location-sort-icon"><i class="fa fa-th-list"></i></span>
					<a href="javascript:void(0);" data-toggle="pill">
						<span class="apt-location-name"><?php if($location_sortby=='city'){ echo $apt_location->city;}else{ echo $apt_location->state;} ?> (<?php echo $city_state_locations[$locationsort]; ?>)</span>
					</a>
				</li>	
			<?php } } ?>	
			</ul><?php			
}	
	
/* Update Location Status Enable/Disable */		
if(isset($_POST['location_action'],$_POST['location_id'],$_POST['location_status']) && $_POST['location_action']=='updatelocationstatus' && $_POST['location_id']!=''){
		$location->id= $_POST['location_id'];
		$location->status= $_POST['location_status'];
		$location->update_location_status(); 
}


/* Get Location By City/State */	
if(isset($_POST['location_action'],$_POST['sortingvalue']) && $_POST['location_action']=='sortbylocations'){
		$bwid = $_POST['bwid'];
		$location->business_owner_id = $bwid;
		if($_POST['sortingvalue']=='all'){
			$sortedlocations = $location->readAll();
		}else{
			$location->sortingvalue= $_POST['sortingvalue'];
			$sortedlocations = $location->get_sorted_location();
		}		
		
		foreach($sortedlocations as  $apt_location){
			$staff->location_id = $apt_location->id;
			$staffcounts = $staff->total_location_providers();		
		?>
		<li id="location_detail_<?php echo $apt_location->id; ?>"><div class="panel panel-default apt-location-panel" >
			<div class="panel-heading">
				<h4 class="panel-title">
					<div class="apt-col9">
						<i class="fa fa-th-list"></i>
						<span class="apt-location-title-name"><?php echo $apt_location->location_title; ?></span>
					</div>
					<div class="pull-right apt-col3">
							
						<div class="apt-col6">
							<label for="location-list-<?php echo $apt_location->id; ?>">
								<input type="checkbox" data-id="<?php echo $apt_location->id; ?>" class="update_location_status" <?php if($apt_location->status=='E'){echo 'checked'; } ?> id="location-list-<?php echo $apt_location->id; ?>" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","apt");?>" data-off="<?php echo __("Disable","apt");?>" data-onstyle="success" data-offstyle="danger" >
							
							
						<!--	<input data-id="<?php //echo $apt_location->id; ?>" class="apt-toggle-medium-input update_location_status" type="checkbox" <?php //if($apt_location->status=='E'){echo 'checked'; } ?> id="location-list-<?php //echo $apt_location->id; ?>" />
							<span class="apt-toggle-medium-label" data-enable="<?php //echo __("Enable","apt");?>" data-disable="<?php //echo __("Disable","apt");?>"></span> 
							<span class="apt-toggle-medium-handle"></span>  -->
							</label>
						</div>
						<div class="pull-right">
							<div class="apt-col2 p-r">
								<a data-poid="apt-popover-location<?php echo $apt_location->id; ?>" id="apt-delete-location<?php echo $apt_location->id; ?>" class="pull-right btn-circle btn-danger btn-sm apt-delete-popover" rel="popover" data-placement='bottom' title="<?php echo __("Delete this location?","apt");?>"> <i class="fa fa-trash" title="<?php echo __("Delete location","apt");?>"></i></a>
								<div class="apt-popover" id="apt-popover-location<?php echo $apt_location->id; ?>" style="display: none;">
									<div class="arrow"></div>
										<table class="form-horizontal" cellspacing="0">
											<tbody>
												<tr>
													<td>
													<?php if($staffcounts>0){?>
														<span class="apt-popover-title"><?php echo __("Unable to delete location,having linked staff","apt");?></span>
														<?php }else{?>				
														<button data-id="<?php echo $apt_location->id; ?>" value="Delete" class="btn btn-danger btn-sm mr-10 delete_location" type="submit"><?php echo __("Yes","apt");?></button>
														<button data-poid="apt-popover-location<?php echo $apt_location->id; ?>" class="btn btn-default btn-sm apt-close-popover-delete" href="javascript:void(0)"><?php echo __("Cancel","apt");?></button><?php } ?>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							<div class="apt-show-hide pull-right">
								<input type="checkbox" name="apt-show-hide" class="apt-show-hide-checkbox" id="<?php echo $apt_location->id; ?>" >
								<label class="apt-show-hide-label" for="<?php echo $apt_location->id; ?>"></label>
							</div>
						</div>
					</div>
					
				</h4>
			</div>
			<div id="" class="location_detail panel-collapse collapse detail-id_<?php echo $apt_location->id; ?>">
				<div class="panel-body">
					<div class="apt-location-collapse-div col-sm-12 col-md-7 col-lg-7 col-xs-12">
						<form id="apt_update_location_<?php echo $apt_location->id;?>" method="post" class="slide-toggle apt_update_location">
							<table class="apt-create-location-table form-group-margin">
								<tbody>

									<tr>
										<td><label for="apt-location-name"><?php echo __("Location Title","apt");?></label></td>
										<td><div class="form-group">
										<input type="text" class="form-control" name="location_title" id="apt-location-name<?php echo $apt_location->id; ?>" value="<?php echo $apt_location->location_title; ?>" />
										</div>
										<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Location title is used to display in frontend for bookings.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									
									<tr>
										<td><label for="apt-location-desc"><?php echo __("Description","apt");?></label></td>
										<td><div class="form-group">
										
										<textarea id="apt-location-desc<?php echo $apt_location->id; ?>" class="form-control"><?php echo $apt_location->description; ?></textarea>
										</div>
										<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Location description is used for desribe about location.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									
									<tr>
										<td><label for="apt-location-image"><?php echo __("Image","apt");?></label></td>
										<td>
											<div class="apt-location-image-uploader">
												<img id="bdll<?php echo $apt_location->id; ?>locimage" src="<?php if($apt_location->image==''){ echo $plugin_url_for_ajax.'/assets/images/location.png';}else{
												echo site_url()."/wp-content/uploads".$apt_location->image;
												}?>" class="apt-location-image br-100" height="100" width="100">
												<label <?php if($apt_location->image==''){ echo "style='display:block'"; }else{ echo "style='display:none'"; } ?> for="apt-upload-imagebdll<?php echo $apt_location->id; ?>" class="apt-location-img-icon-label show_image_icon_add<?php echo $apt_location->id; ?>">
													<i class="apt-camera-icon-common br-100 fa fa-camera"></i>
													<i class="pull-left fa fa-plus-circle fa-2x"></i>
												</label>
												<input data-us="bdll<?php echo $apt_location->id; ?>" class="hide apt-upload-images" type="file" name="" id="apt-upload-imagebdll<?php echo $apt_location->id; ?>"  />
												
												<a id="apt-remove-location-imagebdll<?php echo $apt_location->id; ?>" <?php if($apt_location->image!=''){ echo "style='display:block;'";}  ?> class="pull-left br-100 btn-danger apt-remove-location-img btn-xs apt_remove_image" rel="popover" data-placement='bottom' title="<?php echo __("Remove Image?","apt");?>"> <i class="fa fa-trash" title="<?php echo __("Remove location Image","apt");?>"></i></a>
												<div style="display: none;" class="apt-popover br-5" id="popover-apt-remove-location-imagebdll<?php echo $apt_location->id; ?>">
													<div class="arrow"></div>
													<table class="form-horizontal" cellspacing="0">
														<tbody>
															<tr>
																<td>
																	<a href="javascript:void(0)" id="" value="Delete" data-mediaid="<?php echo $apt_location->id; ?>" data-mediasection='location' data-mediapath="<?php echo $apt_location->image;?>" data-imgfieldid="bdll<?php echo $apt_location->id;?>uploadedimg"
																	class="btn btn-danger btn-sm apt_delete_image"><?php echo __("Yes","apt");?></a>
																	<a href="javascript:void(0)" id="popover-apt-remove-location-imagebdll<?php echo $apt_location->id; ?>" class="btn btn-default btn-sm close_delete_popup" href="javascript:void(0)"><?php echo __("Cancel","apt");?></a>
																</td>
															</tr>
														</tbody>
													</table>
											</div>
											</div>	
						<div id="apt-image-upload-popupbdll<?php echo $apt_location->id; ?>" class="apt-image-upload-popup modal fade" tabindex="-1" role="dialog">
						<div class="vertical-alignment-helper">
							<div class="modal-dialog modal-md vertical-align-center">
								<div class="modal-content">
									<div class="modal-header">
										<div class="col-md-12 col-xs-12">
											<a data-us="bdll<?php echo $apt_location->id; ?>" class="btn btn-success apt_upload_img" data-imageinputid="apt-upload-imagebdll<?php echo $apt_location->id; ?>" ><?php echo __("Crop & Save","apt");?></a>
											<button type="button" class="btn btn-default hidemodal" data-dismiss="modal" aria-hidden="true"><?php echo __("Cancel","apt");?></button>
										</div>	
									</div>
									<div class="modal-body">
										<img id="apt-preview-imgbdll<?php echo $apt_location->id; ?>" />
									</div>
									<div class="modal-footer">
										<div class="col-md-12 np">
											<div class="col-md-4 col-xs-12">
												<label class="pull-left"><?php echo __("File size","apt");?></label> <input type="text" class="form-control" id="bdll<?php echo $apt_location->id; ?>filesize" name="filesize" />
											</div>	
											<div class="col-md-4 col-xs-12">	
												<label class="pull-left"><?php echo __("H","apt");?></label> <input type="text" class="form-control" id="bdll<?php echo $apt_location->id; ?>h" name="h" /> 
											</div>
											<div class="col-md-4 col-xs-12">	
												<label class="pull-left"><?php echo __("W","apt");?></label> <input type="text" class="form-control" id="bdll<?php echo $apt_location->id; ?>w" name="w" />
											</div>
											<input type="hidden" id="bdll<?php echo $apt_location->id; ?>x1" name="x1" />
											 <input type="hidden" id="bdll<?php echo $apt_location->id; ?>y1" name="y1" />
											<input type="hidden" id="bdll<?php echo $apt_location->id; ?>x2" name="x2" />
											<input type="hidden" id="bdll<?php echo $apt_location->id; ?>y2" name="y2" />
											<input id="bdll<?php echo $apt_location->id; ?>bdimagetype" type="hidden" name="bdimagetype"/>
											<input type="hidden" id="bdll<?php echo $apt_location->id; ?>bdimagename" name="bdimagename" value="" />
											</div>
									</div>							
								</div>		
							</div>			
						</div>			
					</div>
						</td>
					<input name="image" id="bdll<?php echo $apt_location->id;?>uploadedimg" type="hidden" value="<?php echo $apt_location->image; ?>" />
					</tr>
									
									
									<!-- <tr>
										<td><label for="apt-location-image"><?php //echo __("Image","apt");?></label></td>
										<td><input type="file" /></td>
										<input type="hidden" id="apt-location-image<?php //echo $apt_location->id; ?>" value=""/>
									</tr> -->
									
									<tr>
										<td><label for="location-email<?php echo $apt_location->id; ?>"><?php echo __("Email","apt");?></label></td>
										<td>
										<div class="form-group">
										<input type="email" class="form-control" id="location-email<?php echo $apt_location->id; ?>" name="email" value="<?php echo $apt_location->email; ?>"/>
										</div>
										<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Location email is used for to identify your location for business.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<tr>
										<td><label for="location-phone-number<?php echo $apt_location->id; ?>"><?php echo __("Phone","apt");?></label></td>
										<td>
										<div class="form-group">
										<input type="tel" class="form-control" id="location-phone-number<?php echo $apt_location->id; ?>" name="phone" value="<?php echo $apt_location->phone; ?>" />
										</div>
										<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Location phone is used to find location easily.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<tr>
									<td><?php echo __("Address","apt");?></td>
									<td>
										<div class="apt-col12"><textarea class="form-control"  name="address" id="apt-location-address<?php echo $apt_location->id; ?>"><?php echo $apt_location->address; ?></textarea></div>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<div class="apt-col6 apt-w-50">
											<label><?php echo __("City","apt");?></label>
											<input type="text" class="form-control" id="apt-location-city<?php echo $apt_location->id; ?>" name="city" placeholder="City" value="<?php echo $apt_location->city; ?>" />
										</div>
										<div class="apt-col6 apt-w-50 float-right">
											<label><?php echo __("State","apt");?></label>
											<input type="text" class="form-control" id="apt-location-state<?php echo $apt_location->id; ?>" name="state" placeholder="State" value="<?php echo $apt_location->state; ?>" />
										</div>
									</td>
								</tr>
								<tr>
									<td></td>	
									<td>	
										<div class="apt-col6 apt-w-50">
											<label><?php echo __("Zip/Postal Code","apt");?></label>
											<input type="text" class="form-control" id="apt-location-zip<?php echo $apt_location->id; ?>" name="zip" placeholder="Zip" value="<?php echo $apt_location->zip; ?>" />
										</div>	
										<div class="apt-col6 apt-w-50 float-right">
											<label><?php echo __("Country","apt");?></label>
											<input type="text" class="form-control" id="apt-location-country<?php echo $apt_location->id; ?>" name="country" placeholder="Country" value="<?php echo $apt_location->country; ?>" />
										</div>	
										
									</td>
								</tr>
								</tbody>
							</table>
					</div>
					<!-- 
					<div class="col-sm-12 col-md-5 col-lg-5 col-xs-12">
						<div class="apt-location-map">
							<label><?php //echo __("Map Location","apt");?></label>
							<input id="pac-input" class="controls" type="text" placeholder="Search Box">
							<div id="map"></div>
						</div>
					</div>
					-->
					<div class="col-sm-12 col-md-7 col-lg-7 col-xs-12 mt-20 mb-20">		
						<a data-location_id="<?php echo $apt_location->id; ?>" name="" class="btn btn-success apt-btn-width col-sm-offset-2 update_location"><?php echo __("Save","apt");?></a>
						<button type="reset" class="btn btn-default  apt-btn-width ml-30"><?php echo __("Reset","apt");?></button>
					</div>	
					</form>
				</div>
			</div>
		</div>
		</li>
		<?php } ?>
														
		<li>
			<!-- add new service pop up -->
			<div class="panel panel-default apt-location-panel apt-add-new-location">
				<div class="panel-heading">
					<h4 class="panel-title">
						<div class="apt-col9">
							<span class="apt-location-title-name"><?php echo __("Add New Location","apt");?></span>
						</div>
						<div class="pull-right apt-col3">				
							<div class="pull-right">
								<div class="apt-show-hide pull-right">
									<input type="checkbox" name="apt-show-hide" checked="checked" class="apt-show-hide-checkbox" id="ladd" ><!--Added Serivce Id-->
									<label class="apt-show-hide-label" for="ladd"></label>
								</div>
							</div>
						</div>											
					</h4>
				</div>
				<div id="" class="location_detail panel-collapse collapse in detail_sp3 detail-id_ladd">
					<div class="panel-body">
					<form id="apt_create_location_cl" action="" method="post" class="slide-toggle" >
						<div class="apt-location-collapse-div col-sm-12 col-md-7 col-lg-7 col-xs-12">
							<table class="apt-create-location-table form-group-margin">
								<tbody>

									<tr>
										<td><label for="apt-location-name"><?php echo __("Location Title","apt");?></label></td>
										<td>
										<div class="form-group">
										<input type="text" name="location_title" class="form-control" id="apt-location-name" />
										</div>
										<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Location title is used to display in frontend for bookings.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										
										</td>
									</tr>
									
									<tr>
										<td><label for="apt-location-desc"><?php echo __("Description","apt");?></label></td>
										<td>
										<div class="form-group">
										<textarea name="description" id="apt-location-desc" class="form-control"></textarea>
										</div>
										<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Location description is used for desribe about location.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<tr>
										<td><label for="apt-location-image"><?php echo __("Image","apt");?></label></td>
										<td>
											<div class="apt-location-image-uploader">
												<img id="bdcllocimage" src="<?php echo $plugin_url_for_ajax; ?>/assets/images/location.png" class="apt-location-image br-100" height="100" width="100">
												<label for="apt-upload-imagebdcl" class="apt-location-img-icon-label">
													<i class="apt-camera-icon-common br-100 fa fa-camera"></i>
													<i class="pull-left fa fa-plus-circle fa-2x"></i>
												</label>
												<input data-us="bdcl" class="hide apt-upload-images" type="file" name="" id="apt-upload-imagebdcl"  />
												
												<a id="apt-remove-location-imagebdcl" class="pull-left br-100 btn-danger apt-remove-location-img btn-xs" rel="popover" data-placement='bottom' title="<?php echo __("Remove Image?","apt");?>"> <i class="fa fa-trash" title="<?php echo __("Remove location Image","apt");?>"></i></a>
												<div id="popover-apt-remove-location-imagebdcl" style="display: none;">
													<div class="arrow"></div>
													<table class="form-horizontal" cellspacing="0">
														<tbody>
															<tr>
																<td>
																	<a href="javascript:void(0)" id="" value="Delete" class="btn btn-danger btn-sm" type="submit"><?php echo __("Yes","apt");?></a>
																	<a href="javascript:void(0)" id="apt-close-popover-location-imagebdcl" class="btn btn-default btn-sm" href="javascript:void(0)"><?php echo __("Cancel","apt");?></a>
																</td>
															</tr>
														</tbody>
													</table>
												</div><!-- end pop up -->
											</div>	
											<div id="apt-image-upload-popupbdcl" class="apt-image-upload-popup modal fade" tabindex="-1" role="dialog">
												<div class="vertical-alignment-helper">
													<div class="modal-dialog modal-md vertical-align-center">
														<div class="modal-content">
															<div class="modal-header">
																<div class="col-md-12 col-xs-12">
																	<a data-us="bdcl" class="btn btn-success apt_upload_img" data-imageinputid="apt-upload-imagebdcl"><?php echo __("Crop & Save","apt");?></a>
																	<button type="button" class="btn btn-default hidemodal" data-dismiss="modal" aria-hidden="true"><?php echo __("Cancel","apt");?></button>
																</div>	
															</div>
															<div class="modal-body">
																<img id="apt-preview-imgbdcl" />
															</div>
															<div class="modal-footer">
																<div class="col-md-12 np">
																	<div class="col-md-4 col-xs-12">
																		<label class="pull-left"><?php echo __("File size","apt");?></label> <input type="text" class="form-control" id="bdclfilesize" name="filesize" />
																	</div>	
																	<div class="col-md-4 col-xs-12">	
																		<label class="pull-left"><?php echo __("H","apt");?></label> <input type="text" class="form-control" id="bdclh" name="h" /> 
																	</div>
																	<div class="col-md-4 col-xs-12">	
																		<label class="pull-left"><?php echo __("W","apt");?></label> <input type="text" class="form-control" id="bdclw" name="w" />
																	</div>
																	<input type="hidden" id="bdclx1" name="x1" />
																						 <input type="hidden" id="bdcly1" name="y1" />
																						<input type="hidden" id="bdclx2" name="x2" />
																						<input type="hidden" id="bdcly2" name="y2" />
																						<input id="bdclbdimagetype" type="hidden" name="bdimagetype"/>
																						<input type="hidden" id="bdclbdimagename" name="bdimagename" value="" />
																</div>
															</div>							
														</div>		
													</div>			
												</div>			
											</div>
										</td>
										<input name="locationimage" id="bdcluploadedimg" type="hidden" value="" />
									</tr>
									<tr>
										<td><label for="location-email"><?php echo __("Email","apt");?></label></td>
										<td>
										<div class="form-group">
										<input type="email" class="form-control" id="location-email" name="email" />
										</div>
										<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Location email is used for to identify your location for business.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<tr>
										<td><label for="location-phone-number"><?php echo __("Phone","apt");?></label></td>
										<td>
										<div class="form-group">
										<input type="tel" class="form-control" id="location-phone-number" name="phone" />
										</div>
										<a class="apt-tooltip-link" href="#" data-toggle="tooltip" title="<?php echo __("Location phone is used to find location easily.","apt");?>"><i class="fa fa-info-circle fa-lg"></i></a>
										</td>
									</tr>
									<tr>
									<td><?php echo __("Address","apt");?></td>
									<td>
										<div class="apt-col12"><textarea class="form-control" name="address"></textarea></div>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<div class="apt-col6 apt-w-50">
											<label><?php echo __("City","apt");?></label>
											<input type="text" class="form-control" id="" name="city" placeholder="City" />
										</div>
										<div class="apt-col6 apt-w-50 float-right">
											<label><?php echo __("State","apt");?></label>
											<input type="text" class="form-control" id="" name="state" placeholder="State" />
										</div>
									</td>
								</tr>
								<tr>
									<td></td>	
									<td>	
										<div class="apt-col6 apt-w-50">
											<label><?php echo __("Zip/Postal Code","apt");?></label>
											<input type="text" class="form-control" id="" name="zip" placeholder="Zip" />
										</div>	
										<div class="apt-col6 apt-w-50 float-right">
											<label><?php echo __("Country","apt");?></label>
											<input type="text" class="form-control" id="" name="country" placeholder="Country" />
										</div>	
										
									</td>
								</tr>
								</tbody>
							</table>
							
						</div>
						<!-- 
						<div class="col-sm-12 col-md-5 col-lg-5 col-xs-12">
							<div class="apt-location-map">
								<label><?php //echo __("Map Location","apt");?></label>
								<input id="pac-input" class="controls" type="text" placeholder="Search Box">
								<div id="map"></div>
							</div>
						</div>	
						-->
						<div class="col-sm-12 col-md-7 col-lg-7 col-xs-12 mt-20 mb-20">		
							<a href="javascript:void(0)" data-location_id="cl" id="apt_create_location" name="apt_create_location" class="btn btn-success apt-btn-width col-sm-offset-2"><?php echo __("Save","apt");?></a>
							<button type="reset" class="btn btn-default  apt-btn-width ml-30"><?php echo __("Reset","apt");?></button>
						</div>	
						
					</form>
					</div>
				</div>
			</div>
		</li>
<?php }	
				