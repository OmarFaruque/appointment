<?php 
	include(dirname(__FILE__).'/header.php');
	$plugin_url_for_ajax = plugins_url('', dirname(__FILE__));
	
	/* Create Location */
	$location = new appointment_location();
	$location->business_owner_id = get_current_user_id();
	$category = new appointment_category();
	$staff = new appointment_staff();
	$service = new appointment_service();
	$general = new appointment_general();
	$apt_image_upload= new appointment_image_upload();
	$apt_currency_symbol = get_option('appointment_currency_symbol' . '_' . get_current_user_id());
	/* Get All Enable Staff Members */
	$staff->location_id = $_SESSION['apt_location'];
	$apt_all_staff = $staff->readAll_with_disables();
	$service->business_owner_id = get_current_user_id();
if(isset($_POST['apt_create_service'])){	
	$service->color_tag = $_POST['color_tag'];
	$service->service_title = filter_var($_POST['service_title'], FILTER_SANITIZE_STRING);
	$service->service_description = filter_var($_POST['service_description'], FILTER_SANITIZE_STRING);
	$service->image = $_POST['service_image'];
	$service->service_category = $_POST['service_category'];
	$service->duration = ($_POST['service_duration_hrs']*60) + $_POST['service_duration_mins'];
	$service->amount = $_POST['service_price'];
	$service->offered_price = $_POST['offered_price'];
	$service->location_id = $_SESSION['apt_location'];
	$serice_id = $servicecreate = $service->create();
	/* Link Provider with Created Service */
	if(sizeof($apt_all_staff)>0){
		foreach($apt_all_staff as $apt_staff){
			if(isset($_POST['service_staff_c_all']) && $_POST['service_staff_c_all']=='on'){
				$service->provider_id = $apt_staff['id'];
				$service->id = $serice_id;
				$service->link_service_providers();
			}else{
				if(isset($_POST['service_staff_c_'.$apt_staff['id']]) && $_POST['service_staff_c_'.$apt_staff['id']]!=''){
					$service->provider_id = $apt_staff['id'];
					$service->id = $serice_id;
					$service->link_service_providers();
				}
			}
		}	
	}
	
}	
/* Get All Services */
$service->location_id = $_SESSION['apt_location'];
$apt_services = $service->readAll();
$all_services = $service->countAll();
/* Get All Locations */
$location_sortby = get_option('appointment_location_sortby' . '_' . get_current_user_id());
$apt_locations = $location->readAll('','','');
$temp_locatio_name = array();
/* Get All Categories */
$category->location_id = $_SESSION['apt_location'];
$category->business_owner_id = get_current_user_id();
$all_categories = $category->readAll();
	
?>
<div id="apt-services-panel" class="panel tab-content table-fixed">
	
		<div class="apt-service-list table-cell col-md-3 col-sm-3 col-xs-12 col-lg-3">
			<div class="apt-service-container" id="apt_category_listing">
				<h3><?php echo __("All Categories","apt");?> <span>(<?php echo sizeof($all_categories);?>)</span>
					<button id="apt-add-new-category" class="pull-right btn btn-circle btn-info" rel="popover" data-placement='bottom' title="<?php echo __("Add New Category","apt");?>"><i class="fa fa-th-large icon-space"></i><?php echo __("Category","apt");?></button>
					
					
					<div id="popover-content-wrapper" style="display: none">
					<div class="arrow"></div>
					<form id="apt_create_category" action="" method="post">
					<table class="form-horizontal" cellspacing="0">
						<tbody>
							<tr class="form-field form-required">
								<!-- <td><label for="ab-newstaff-fullname"><?php //echo __("Name","apt");?> </label></td> -->
								<td><input type="text" class="form-control" id="apt_category_title" name="apt_category_title"  value=""/></td>
							</tr>
							<tr>
								<td>
									<a id="" class="btn btn-info apt_create_category" href="javascript:void(0)"><?php echo __("Create","apt");?></a>
									<a id="apt-close-popover-new-service-category" class="btn btn-default" href="javascript:void(0)"><?php echo __("Cancel","apt");?></a>
								</td>
							</tr>
						</tbody>
					</table>
					</form>
					</div>
					
				</h3><!-- end popover -->
				<ul class="nav nav-tab nav-stacked apt-left-services">
					<li class="apt-left-service-menu-li br-2 apt_category_services apt_category_all_service f-letter-capitalize " data-cid="all">
					<span class="apt-service-sort-icon"><i class="fa fa-th"></i></span>
						<a href="javascript:void(0)" data-toggle="pill">
							<span class="apt-service-name"><?php echo __("All Services","apt");?> (<?php echo $all_services; ?>)</span>
						</a>
					</li>
				</ul>	
				<ul class="nav nav-tab nav-stacked apt-left-service" id="sortable-category-list">
					<?php
					foreach($all_categories as $apt_category){ 
						$service->service_category = $apt_category->id;
						$apt_services = $service->readAll_category_services();
						?>
						<li data-cid="<?php echo $apt_category->id;?>" class="apt-left-service-menu-li br-2 apt_category_services  f-letter-capitalize" data-cs="<?php echo sizeof($apt_services);?>" id="category_detail_<?php echo $apt_category->id;?>">
						<span class="apt-service-sort-icon"><i class="fa fa-th-list"></i></span>
							<a href="javascript:void(0)" data-toggle="pill">
								<span class="apt-service-name"><?php echo $apt_category->category_title;?> (<?php echo sizeof($apt_services);?>)</span>
							</a>
							
						</li>
						<?php
						/* if(sizeof($apt_services) == 0){
							?>
							<span class="apt-delete-null-category pull-right" style="margin-top: -33px; cursor: pointer;" data-cid="<?php echo $apt_category->id;?>"><i class="fa fa-trash" style="font-size:20px; margin-top: -33px;" aria-hidden="true"></i></span>
							<?php
						} */
						?>
						<?php
						}
					?>
				</ul>	
			</div>	
		</div>
	<div class="panel-body table-cell col-md-9 col-sm-9 col-xs-12 col-lg-9">
		<div class="apt-service-details tab-content col-md-12 col-sm-12 col-lg-12 col-xs-12">
			<!-- right side common menu for service -->
			<div class="apt-service-top-header">
				<span class="apt-service-service-name pull-left" id="apt-category-title"></span>
				
				<div class="pull-right">
					<table>
						<tbody>
							<tr>
								<td>
									<button id="apt-add-new-service" class="btn btn-success" value="add new service"><i class="fa fa-plus icon-space "></i><?php echo __("Add Service","apt");?></button>
								</td>
							
							<td id="apt-category-delete-icon" style="display:none;">
									<button id="apt-delete-service-category" class="pull-right btn btn-circle btn-danger" rel="popover" data-placement='bottom' title="<?php echo __("Delete service category?","apt");?>"> <i class="fa fa-trash icon-space"></i><?php echo __("Delete Category","apt");?></button>
								
									
									<div id="popover-delete-service-category" style="display: none;">
										<span class="hide-div" id="delete_category_error"><?php echo __("Unable to delete category,having services","apt");?></span>
										<span id="delete_category_sucess">
										<div class="arrow"></div>
										<table class="form-horizontal" cellspacing="0">
											<tbody>												
												<tr>
													<td>
														<a href="javascript:void(0);" id="apt-delete-category" value="Delete" class="btn btn-danger btn-sm"><?php echo __("Yes","apt");?></a>
														<button id="apt-close-popover-delete-service-category" class="btn btn-default btn-sm" href="javascript:void(0)"><?php echo __("Cancel","apt");?></button>
													</td>
												</tr>
											</tbody>
										</table>
										</span>
									</div>
								
								</td>
							</tr>
						</tbody>
					</table>
					
			</div>
				
						
			</div>
			<div id="hr"></div>

			<div class="tab-pane active" id=""><!-- services list -->
				<div class="tab-content apt-services-right-details">
					<div class="tab-pane active col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div id="accordion" class="panel-group">
						<ul class="nav nav-tab nav-stacked" id="sortable-services" > <!-- sortable-services -->
							<?php foreach($apt_services as $apt_service){ 
								$service->id = $apt_service->id;	?>
							<li id="service_detail_<?php echo $apt_service->id; ?>" class="panel panel-default apt-services-panel" >
								<div class="panel-heading">
									<h4 class="panel-title">
										<div class="col-lg-5 col-sm-12 col-xs-12 np">
											<div class="pull-left">
												<i class="fa fa-th-list"></i><span class="badge" style="background-color:<?php echo $apt_service->color_tag; ?>" title="Service color badge"></span>
											</div>	
											<span class="custom-width-auto apt-service-title-name f-letter-capitalize"><?php echo $apt_service->service_title; ?></span>
										</div>
										<div class="col-lg-7 col-sm-12 col-xs-12 np">
											<div class="col-lg-3 col-sm-3 col-xs-6 np">
												<span class="apt-service-time-main"><i class="fa fa-clock-o icon-space "></i><?php if(floor($apt_service->duration/60)!=0){ echo floor($apt_service->duration/60); echo __(" Hrs","apt"); } ?>  <?php  if($apt_service->duration%60 !=0){ echo $apt_service->duration%60; echo __(" Mins","apt");} ?></span>
											</div>
											<div class="col-lg-2 col-sm-2 col-xs-6 np">
												<span class="apt-service-price-main"><span><?php echo $apt_currency_symbol;?></span><?php if($apt_service->offered_price != ""){
													echo $apt_service->offered_price;
												}else{echo $apt_service->amount; } ?></span>
											</div>	
											<div class="col-lg-2 col-sm-2 col-xs-4 np">
												<label for="sevice-endis-<?php echo $apt_service->id; ?>">
													<input data-id="<?php echo $apt_service->id; ?>" type="checkbox" class="update_service_status" id="sevice-endis-<?php echo $apt_service->id; ?>" <?php if($apt_service->service_status=='Y'){echo 'checked'; } ?> data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","apt");?>" data-off="<?php echo __("Disable","apt");?>" data-onstyle="success" data-offstyle="danger" >
												</label>
											</div>
											
											<div class="col-lg-2 col-sm-2 col-xs-4 npr rnp">
											<!--addons btn -->
											
											<a href="?page=service_addons&sid=<?php echo $apt_service->id; ?>" class="btn btn-info btn-sm manage-addons-btn"><i class="fa fa-puzzle-piece icon-space" aria-hidden="true"></i><?php echo __("Addons","apt");?></a>
											
											
											</div>
											
											<div class="pull-right">
												<div class="col-lg-2 col-sm-1 col-xs-4 np">
												<a data-poid="apt-popover-delete-service<?php echo $apt_service->id; ?>" id="apt-delete-service<?php echo $apt_service->id; ?>" class="pull-right btn-circle btn-danger btn-sm apt-delete-popover" rel="popover" data-placement='bottom' title="<?php echo __("Delete this service?","apt");?>"><i class="fa fa-trash" title="<?php echo __("Delete Service","apt");?>"></i></a>
													<div class="apt-popover" id="apt-popover-delete-service<?php echo $apt_service->id; ?>" style="display: none;">
														<div class="arrow"></div>
														<table class="form-horizontal" cellspacing="0">
															<tbody>
																<tr>
																	<td>
																		<?php if($service->total_service_bookings()>0){?>
																		<span class="apt-popover-title"><?php echo __("Unable to delete service,having bookings","apt");?></span>
																		<?php }else{?>		
																		<button data-id="<?php echo $apt_service->id; ?>" value="Delete" class="btn btn-danger btn-sm mr-10 delete_service" type="submit"><?php echo __("Yes","apt");?></button>
																		<button data-poid="apt-popover-service<?php echo $apt_service->id; ?>" class="btn btn-default btn-sm apt-close-popover-delete" href="javascript:void(0)"><?php echo __("Cancel","apt");?></button><?php } ?>
																	</td>
																</tr>
															</tbody>
														</table>
													</div>
												</div>											
												<div class="apt-show-hide pull-right">
													<input type="checkbox" name="apt-show-hide" class="apt-show-hide-checkbox " id="<?php echo $apt_service->id; ?>" ><!--Added Serivce Id-->
													<label class="apt-show-hide-label" for="<?php echo $apt_service->id; ?>"></label>
												</div>
											</div>
										</div>
										
									</h4>
								</div>
								<div id="" class="service_detail panel-collapse collapse detail-id_<?php echo $apt_service->id; ?>">
									<div class="panel-body">
										<div class="apt-service-collapse-div col-sm-7 col-md-7 col-lg-7 col-xs-12">
											<form data-sid="<?php echo $apt_service->id; ?>" id="apt_update_service_<?php echo $apt_service->id; ?>" method="post" type="" class="slide-toggle apt_update_service" >
												<table class="apt-create-service-table">
													<tbody>
														<tr>
															<td><label for="apt-service-color-tag<?php echo $apt_service->id; ?>"><?php echo __("Color Tag","apt");?></label></td>
															<td><input type="text" id="apt-service-color-tag<?php echo $apt_service->id; ?>" class="form-control demo" data-control="saturation" value="<?php echo $apt_service->color_tag; ?>"></td>
														</tr>
														<tr>
															<td><label for="apt-service-title<?php echo $apt_service->id; ?>"><?php echo __("Service Title","apt");?></label></td>
															<td><input type="text" name="u_service_title" class="form-control" id="apt-service-title<?php echo $apt_service->id; ?>" value="<?php echo $apt_service->service_title; ?>" /></td>
														</tr>
														
														<tr>
															<td><label for="apt-service-desc<?php echo $apt_service->id; ?>"><?php echo __("Service Description","apt");?></label></td>
															<td><textarea name="u_service_desc" id="apt-service-desc<?php echo $apt_service->id; ?>" class="form-control"><?php echo $apt_service->service_description; ?></textarea></td>
														</tr>
														<tr>
															<td><label for="apt-service-desc"><?php echo __("Service Image","apt");?></label></td>
															<td>
																<div class="apt-service-image-uploader">
																	<img id="bdls<?php echo $apt_service->id; ?>locimage" src="<?php if($apt_service->image==''){ echo $plugin_url_for_ajax.'/assets/images/service.png';}else{
																	echo site_url()."/wp-content/uploads".$apt_service->image;
																	}?>" class="apt-service-image br-100" height="100" width="100">
																	
																	<label <?php if($apt_service->image==''){ echo "style='display:block'"; }else{ echo "style='display:none'"; } ?> for="apt-upload-imagebdls<?php echo $apt_service->id; ?>" class="apt-service-img-icon-label show_image_icon_add<?php echo $apt_service->id; ?>">
																		<i class="apt-camera-icon-common br-100 fa fa-camera"></i>
																		<i class="pull-left fa fa-plus-circle fa-2x"></i>
																	</label>
																	<input data-us="bdls<?php echo $apt_service->id; ?>" class="hide apt-upload-images" type="file" name="" id="apt-upload-imagebdls<?php echo $apt_service->id; ?>"  />
																	<a id="apt-remove-service-imagebdls<?php echo $apt_service->id; ?>" <?php if($apt_service->image!=''){ echo "style='display:block;'";}  ?> class="pull-left br-100 btn-danger apt-remove-service-img btn-xs apt_remove_image" rel="popover" data-placement='bottom' title="<?php echo __("Remove Image?","apt");?>"> <i class="fa fa-trash" title="<?php echo __("Remove Service Image","apt");?>"></i></a>
																	
																	
																	
																	<div id="popover-apt-remove-service-imagebdls<?php echo $apt_service->id; ?>" style="display: none;">
																		<div class="arrow"></div>
																		<table class="form-horizontal" cellspacing="0">
																			<tbody>
																				<tr>
																					<td>
																						<a href="javascript:void(0)" value="Delete" data-mediaid="<?php echo $apt_service->id; ?>" data-mediasection='service' data-mediapath="<?php echo $apt_service->image;?>" data-imgfieldid="bdls<?php echo $apt_service->id;?>uploadedimg" class="btn btn-danger btn-sm apt_delete_image"><?php echo __("Yes","apt");?></a>
																						<a href="javascript:void(0)" id="popover-apt-remove-service-imagebdls<?php echo $apt_service->id; ?>" class="btn btn-default btn-sm close_delete_popup" href="javascript:void(0)"><?php echo __("Cancel","apt");?></a>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</div>
																</div>	
											<div id="apt-image-upload-popupbdls<?php echo $apt_service->id; ?>" class="apt-image-upload-popup modal fade" tabindex="-1" role="dialog">
												<div class="vertical-alignment-helper">
													<div class="modal-dialog modal-md vertical-align-center">
														<div class="modal-content">
															<div class="modal-header">
																<div class="col-md-12 col-xs-12">
																	<a data-us="bdls<?php echo $apt_service->id; ?>" class="btn btn-success apt_upload_img" data-imageinputid="apt-upload-imagebdls<?php echo $apt_service->id; ?>" ><?php echo __("Crop & Save","apt");?></a>
																	<button type="button" class="btn btn-default hidemodal" data-dismiss="modal" aria-hidden="true"><?php echo __("Cancel","apt");?></button>
																</div>	
															</div>
															<div class="modal-body">
																<img id="apt-preview-imgbdls<?php echo $apt_service->id; ?>" />
															</div>
															<div class="modal-footer">
																<div class="col-md-12 np">
																	<div class="col-md-4 col-xs-12">
																		<label class="pull-left"><?php echo __("File size","apt");?></label> <input type="text" class="form-control" id="bdls<?php echo $apt_service->id; ?>filesize" name="filesize" />
																	</div>	
																	<div class="col-md-4 col-xs-12">	
																		<label class="pull-left"><?php echo __("H","apt");?></label> <input type="text" class="form-control" id="bdls<?php echo $apt_service->id; ?>h" name="h" /> 
																	</div>
																	<div class="col-md-4 col-xs-12">	
																		<label class="pull-left"><?php echo __("W","apt");?></label> <input type="text" class="form-control" id="bdls<?php echo $apt_service->id; ?>w" name="w" />
																	</div>
																	<input type="hidden" id="bdls<?php echo $apt_service->id; ?>x1" name="x1" />
																	 <input type="hidden" id="bdls<?php echo $apt_service->id; ?>y1" name="y1" />
																	<input type="hidden" id="bdls<?php echo $apt_service->id; ?>x2" name="x2" />
																	<input type="hidden" id="bdls<?php echo $apt_service->id; ?>y2" name="y2" />
																	<input id="bdls<?php echo $apt_service->id; ?>bdimagetype" type="hidden" name="bdimagetype"/>
																	<input type="hidden" id="bdls<?php echo $apt_service->id; ?>bdimagename" name="bdimagename" value="" />
																	</div>
															</div>							
														</div>		
													</div>			
												</div>			
											</div>
											</td>
										<input name="image" id="bdls<?php echo $apt_service->id;?>uploadedimg" type="hidden" value="<?php echo $apt_service->image;?>" />
														</tr>
														
														<tr>
														
															<td><label for="apt-service-category<?php echo $apt_service->id; ?>"><?php echo __("Select Category","apt");?></label></td>
															<td>
																<div class="form-group">
																  <select id="apt-service-category<?php echo $apt_service->id; ?>" class="selectpicker form-control" data-live-search="true" data-live-search-placeholder="Search" data-actions-box="true"  >
																		<?php foreach($all_categories as $apt_category){ ?>
																		<option <?php if($apt_service->category_id==$apt_category->id){ echo "selected";}?> value="<?php echo $apt_category->id;?>"><?php echo $apt_category->category_title;?></option>
																	<?php } ?>
																</select>
																</div>
															</td>
														</tr>										
														
														<tr>
															<td><label for="apt-service-duration<?php echo $apt_service->id; ?>"><?php echo __("Duration","apt");?></label></td>
															
															<td>	
																<div class="form-inline">
																	<div class="input-group">
																		<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
																		<input class="form-control" placeholder="00" size="2" maxlength="2" id="apt-duration-hrs<?php echo $apt_service->id; ?>" name="u_duration_hrs" value="<?php echo floor($apt_service->duration/60);?>" type="text">
																		<span class="input-group-addon"><?php echo __("Hours","apt");?></span>
																	</div>
																	<div class="input-group">

																		<input class="form-control" placeholder="05" size="2" maxlength="2" id="apt-duration-mins<?php echo $apt_service->id; ?>" value="<?php echo $apt_service->duration%60;?>" name="u_duration_mins" type="text">
																		<span class="input-group-addon"><?php echo __("Minutes","apt");?></span>
																	</div>
																<label id="apt-duration-hrs<?php echo $apt_service->id; ?>-error" class="error" for="apt-duration-hrs<?php echo $apt_service->id; ?>" style="display:none"></label>
																<label id="apt-duration-mins<?php echo $apt_service->id; ?>-error" class="error" for="apt-duration-mins<?php echo $apt_service->id; ?>" style="display:none"></label>
																</div>	
															</td>								
														</tr>
														<tr>
															<td><label for="apt-service-price<?php echo $apt_service->id; ?>"><?php echo __("Default Price","apt");?></label></td>
															<td>
																<div class="input-group">
																	<span class="input-group-addon"><?php echo $apt_currency_symbol;?></span>
																	<input name="u_service_price" id="apt-service-price<?php echo $apt_service->id; ?>" type="text" class="form-control" placeholder="<?php echo __("US Dollar","apt");?>" value="<?php echo $apt_service->amount; ?>">
																</div>	
																<label id="apt-service-price<?php echo $apt_service->id; ?>-error" class="error" for="apt-service-price<?php echo $apt_service->id;?>" style="display:none"></label>
															</td>
														</tr>
														<tr>
															<td><label for="apt-service-price<?php echo $apt_service->id; ?>"><?php echo __("Offered Price","apt");?></label></td>
															<td>
																<div class="input-group">
																	<span class="input-group-addon"><?php echo $apt_currency_symbol;?></span>
																	<input name="u_service_offeredprice" id="apt-service-offered-price<?php echo $apt_service->id; ?>" type="text" class="form-control" placeholder="<?php echo __("US Dollar","apt");?>" value="<?php echo $apt_service->offered_price; ?>">
																</div>	
																<label id="apt-service-offered-price<?php echo $apt_service->id; ?>-error" class="error" for="apt-service-offered-price<?php echo $apt_service->id; ?>" style="display:none"></label>
															</td>
														</tr>
														
														
													</tbody>
												</table>
											
										</div>
										
										<?php if(sizeof($apt_all_staff)>0){  
											$service->id = $apt_service->id;?>
										<div class="col-sm-5 col-md-5 col-lg-5 col-xs-12">
											<h6 class="apt-right-header"><?php echo __("Who provide these Services","apt");?></h6>
											<ul class="list-unstyled" id="apt-select-staff-member">
												<li class="active">
													<div class="apt-col12">
														
														<label class="pull-left mr-10 toggle-large" for="all-staff-member<?php echo 'all'.$apt_service->id; ?>">
															<input data-service_id="<?php echo $apt_service->id; ?>"  <?php if($service->get_total_linked_staff_of_service()==sizeof($apt_all_staff)){ echo "checked='checked'";} ?> class="link_staff linkallstaff" type="checkbox" id="all-staff-member<?php echo 'all'.$apt_service->id; ?>" value="all" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","apt");?>" data-off="<?php echo __("Disable","apt");?>" data-onstyle="success" data-offstyle="danger" />
															
															
														</label>
														<span class="apt-service-provider-list"><?php echo __("All Staff Members","apt");?></span>
													</div>
												</li>
											</ul>
											<ul class="list-unstyled" id="apt-select-staff-member">		
											<?php foreach($apt_all_staff as $apt_staff){ 
													$service->id = $apt_service->id;
													$service->provider_id = $apt_staff['id'];
													
												?>	
												<li class="active">
													<div class="apt-col12">							
														<label class="pull-left mr-10" for="staff-member<?php echo $apt_staff['id'].$apt_service->id; ?>">
															
															<input type="checkbox" data-service_id="<?php echo $apt_service->id; ?>" class="link_staff apt_all_staff<?php echo $apt_service->id; ?>" <?php if($service->service_provider_link_status()=="Y"){ echo "checked";} ?> value="<?php echo $apt_staff['id']; ?>" id="staff-member<?php echo $apt_staff['id'].$apt_service->id; ?>" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","apt");?>" data-off="<?php echo __("Off","apt");?>" data-onstyle="success" data-offstyle="default" />
															
															
															
															
														</label>
														<span class="apt-service-provider-list"><?php echo $apt_staff['staff_name']; ?></span>
													</div>
												</li>
												<?php } ?>
											</ul>		

										</div>	
									<?php } ?>	
									
										<table class="col-sm-7 col-md-7 col-lg-7 col-xs-12 mt-20 mb-20">
											<tbody>
												<tr>
													<td></td>
													<td>
														<a href="javascript:void(0);" data-service_id="<?php echo $apt_service->id; ?>" name="" class="btn btn-success apt-btn-width col-md-offset-4 update_service" type="submit"><?php echo __("Save","apt");?></a>
														<button type="reset" class="btn btn-default apt-btn-width ml-30"><?php echo __("Reset","apt");?></button>
													</td>
												</tr>
											</tbody>
										</table>
										
										</form>
									</div>
								</div>
							
							</li>
							<?php } ?>
							<!-- add new service pop up -->
							<li>
							<div class="panel panel-default apt-services-panel apt-add-new-service">
								<div class="panel-heading">
									<h4 class="panel-title">
										<div class="apt-col6">
											<span class="apt-service-title-name"><?php echo __("Add New Service","apt");?></span>		
										</div>
										<div class="pull-right apt-col6">					
											<div class="pull-right">
													<div class="apt-show-hide pull-right">
													<input type="checkbox" name="apt-show-hide" checked="checked" class="apt-show-hide-checkbox" id="addservice" ><!--Added Serivce Id-->
													<label class="apt-show-hide-label" for="addservice"></label>
												</div>
											</div>
										</div>										
									</h4>
								</div>
								<div id="" class="service_detail panel-collapse collapse in detail-id_addservice">
									<div class="panel-body">
										<div class="apt-service-collapse-div col-sm-7 col-md-7 col-lg-7 col-xs-12">
											<form id="apt_create_service" method="post" type="" class="slide-toggle" >
												<table class="apt-create-service-table">
													<tbody>
														<tr>
															<td><label for="apt-service-color-tag"><?php echo __("Color Tag","apt");?></label></td>
															<td><input type="text" id="apt-service-color-tag" class="form-control demo" name="color_tag" data-control="saturation" value="#35add2"></td>
														</tr>
														<tr>
															<td><label for="apt-service-title"><?php echo __("Service Title","apt");?></label></td>
															<td><input type="text" name="service_title" class="form-control" id="apt-service-title" /></td>
														</tr>
														
														<tr>
															<td><label for="apt-service-desc"><?php echo __("Service Description","apt");?></label></td>
															<td><textarea id="apt-service-desc" name="service_description" class="form-control"></textarea></td>
														</tr>
														<tr>
															<td><label for="apt-service-desc"><?php echo __("Service Image","apt");?></label></td>
															<td>
																<div class="apt-service-image-uploader">
																	<img id="bdcslocimage" src="<?php echo $plugin_url_for_ajax; ?>/assets/images/service.png" class="apt-service-image br-100" height="100" width="100">
																	<label for="apt-upload-imagebdcs" class="apt-service-img-icon-label">
																		<i class="apt-camera-icon-common br-100 fa fa-camera"></i>
																		<i class="pull-left fa fa-plus-circle fa-2x"></i>
																	</label>
																	<input data-us="bdcs" class="hide apt-upload-images" type="file" name="" id="apt-upload-imagebdcs"  />
																	
																	<a style="display: none;" id="apt-remove-service-imagebdcs" class="pull-left br-100 btn-danger apt-remove-service-img btn-xs" rel="popover" data-placement='bottom' title="<?php echo __("Remove Image?","apt");?>"> <i class="fa fa-trash" title="<?php echo __("Remove service Image","apt");?>"></i></a>
																	<div id="popover-apt-remove-service-imagebdcs" style="display: none;">
																		<div class="arrow"></div>
																		<table class="form-horizontal" cellspacing="0">
																			<tbody>
																				<tr>
																					<td>
																						<a href="javascript:void(0)" id="" value="Delete" class="btn btn-danger btn-sm" type="submit"><?php echo __("Yes","apt");?></a>
																						<a href="javascript:void(0)" id="apt-close-popover-service-imagebdcs" class="btn btn-default btn-sm" href="javascript:void(0)"><?php echo __("Cancel","apt");?></a>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</div><!-- end pop up -->
																</div>
										<div id="apt-image-upload-popupbdcs" class="apt-image-upload-popup modal fade" tabindex="-1" role="dialog">
											<div class="vertical-alignment-helper">
												<div class="modal-dialog modal-md vertical-align-center">
													<div class="modal-content">
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
																	<label class="pull-left"><?php echo __("File size","apt");?></label> <input type="text" class="form-control" id="bdcsfilesize" name="filesize" />
																</div>	
																<div class="col-md-4 col-xs-12">	
																	<label class="pull-left"><?php echo __("H","apt");?></label> <input type="text" class="form-control" id="bdcsh" name="h" /> 
																</div>
																<div class="col-md-4 col-xs-12">	
																	<label class="pull-left"><?php echo __("W","apt");?></label> <input type="text" class="form-control" id="bdcsw" name="w" />
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
										<input name="service_image" id="bdcsuploadedimg" type="hidden" value="" />						
															</td>
														</tr>
														<tr>
															<td><label for="apt-service-desc"><?php echo __("Select Category","apt");?></label></td>
															<td>
																<div class="form-group">
																  <select id="apt_service_categories" class="selectpicker form-control" name="service_category" data-live-search="true" data-live-search-placeholder="Search" data-actions-box="true"  >
																  <?php foreach($all_categories as $apt_category){ ?>
																		<option value="<?php echo $apt_category->id;?>"><?php echo $apt_category->category_title;?></option>
																	<?php } ?>	
																</select>
																</div>
															</td>
														</tr>
												
														<tr>
															<td><label for="apt-service-duration"><?php echo __("Duration","apt");?></label></td>
															<td>	
																<div class="form-inline">
																	<div class="input-group">
																		<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
																		<input class="form-control" placeholder="00" size="2" maxlength="2" id="service_duration_hrs" name="service_duration_hrs" type="text">
																		<span class="input-group-addon"><?php echo __("Hours","apt");?></span>
																	</div>
																<div class="input-group">

																	<input class="form-control" placeholder="05" size="2" maxlength="2" id="service_duration_mins" name="service_duration_mins" type="text">
																	<span class="input-group-addon"><?php echo __("Minutes","apt");?></span>
																</div>
																<label id="service_duration_mins-error" class="error" for="service_duration_mins" style="display:none;"></label>
																<label id="service_duration_hrs-error" class="error" for="service_duration_hrs" style="display:none;"></label>
																</div>									
															</td>	
														</tr>
														<tr>
															<td><label for="apt-service-price"><?php echo __("Default Price","apt");?></label></td>
															<td>
																<div class="input-group">
																	<span class="input-group-addon"><?php echo $apt_currency_symbol;?></span>
																	<input type="text" name="service_price" class="form-control" placeholder="<?php echo __("US Dollar","apt");?>">
																</div>	
																<label id="service_price-error" class="error" for="service_price" style="display:none;"></label>
															</td>
														</tr>
														<tr>
															<td><label for="apt-service-price"><?php echo __("Offered Price","apt");?></label></td>
															<td>
																<div class="input-group">
																	<span class="input-group-addon"><?php echo $apt_currency_symbol;?></span>
																	<input type="text" name="offered_price" class="form-control" placeholder="<?php echo __("US Dollar","apt");?>">
																</div>	
																<label id="offered_price-error" class="error" for="offered_price" style="display:none;"></label>
															</td>
														</tr>
														
													</tbody>
												</table>
											
										</div>
										<?php if(sizeof($apt_all_staff)>0){?>
										<div class="col-sm-5 col-md-5 col-lg-5 col-xs-12">
											<h6 class="apt-right-header"><?php echo __("Who provide these Services","apt");?></h6>
											<ul class="list-unstyled" id="apt-select-staff-member">
												<li class="active">
													<div class="apt-col12">
														
														<label class="toggle-large" for="all-staff-member-c">
															<input type="checkbox" id="all-staff-member-c" data-service_id='' name="service_staff_c_all" class="linkallstaff" data-toggle="toggle" data-size="small" data-on="<?php echo __("Enable","apt");?>" data-off="<?php echo __("Disable","apt");?>" data-onstyle="success" data-offstyle="danger" />
														</label>
														
														
														<span><?php echo __("All Staff Members","apt");?></span>
													</div>
												</li>
											</ul>
											<ul class="list-unstyled" id="apt-select-staff-member">		
												<?php foreach($apt_all_staff as $apt_staff){ ?>	
												<li class="active">
													<div class="apt-col12">
														
														<label for="staff-member-c<?php echo $apt_staff['id']; ?>">
															<input type="checkbox" name="service_staff_c_<?php echo $apt_staff['id']; ?>" class="apt_all_staff" id="staff-member-c<?php echo $apt_staff['id']; ?>" value="<?php echo $apt_staff['id']; ?>" data-toggle="toggle" data-size="small" data-on="<?php echo __("On","apt");?>" data-off="<?php echo __("Off","apt");?>" data-onstyle="success" data-offstyle="default" />
															
														</label>
														<span class="apt-service-provider-list"><?php echo $apt_staff['staff_name']; ?></span>
													</div>
												</li>
												<?php } ?>
											</ul>		

										</div>
										<?php } ?>
										<table class="col-sm-7 col-md-7 col-lg-7 col-xs-12 mt-20 mb-20">
											<tbody>
												<tr>
													<td></td>
													<td>
														<button id="apt_create_service" name="apt_create_service" class="btn btn-success apt-btn-width col-md-offset-4" type="submit"><?php echo __("Save","apt");?></button>
														<button type="reset" class="btn btn-default apt-btn-width ml-30"><?php echo __("Reset","apt");?></button>
													</td>
												</tr>
											</tbody>
										</table>
										
										
										</form>									
									</div>
								</div>
							</div>
							</li>
							
							</ul>
						</div>	
					</div>
				</div>
			</div>
			
		</div>
			
	</div>
</div>
<?php 
	include(dirname(__FILE__).'/footer.php');
?>
<script>
	var serviceObj={"plugin_path":"<?php echo $plugin_url_for_ajax;?>"}
</script>