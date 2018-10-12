

/* Front page Form Steps show/hide */
jQuery(document).ready(function () { 
	
	jQuery('#btn-more-bookings').on( "click", function() {
		jQuery('#apt_first_step').addClass('show-data');
		jQuery('#apt_first_step').removeClass('hide-data');        
		jQuery('#apt_second_step').addClass('hide-data');
		jQuery('#apt_second_step').removeClass('show-data');
		jQuery('#apt_third_step').addClass('hide-data');
		jQuery('#apt_third_step').removeClass('show-data');
	});
	jQuery('.apt-cart-items-count').on( "click", function() {
		jQuery('#apt_first_step').addClass('hide-data');
		jQuery('#apt_first_step').removeClass('show-data');        
		jQuery('#apt_second_step').addClass('show-data');
		jQuery('#apt_second_step').removeClass('hide-data');		
	});
	
});	
/* scroll to top when on second step */
jQuery(document).ready(function(){
	jQuery('#btn-second-step, #btn-more-bookings, .apt-cart-items-count, #btn-third-step').on('click',function(){
		jQuery('html, body').stop().animate({
			'scrollTop': jQuery('#apt-main').offset().top - 80
		}, 800, 'swing', function () {});
	});
});


jQuery(document).ready(function () { 
	if (jQuery("#apt").width() >= 380 && jQuery("#apt").width() < 600){
		jQuery( ".apt-main-left").addClass( "active-left-xs12" );
		jQuery( ".apt-main-right").addClass( "active-right-xs12" );
	}	
	if (jQuery("#apt").width() >= 601 && jQuery("#apt").width() < 850){
		jQuery( ".apt-main-left").addClass( "active-left-res75" );
		jQuery( ".apt-main-right").addClass( "active-right-res57" );
	}	
	
	if (!jQuery('.apt_remove_left_sidebar_class').hasClass("no-sidebar-right")) {
		jQuery('.apt_remove_left_sidebar_class').addClass('apt-asr');
	}
	if (!jQuery('.apt_remove_right_sidebar_class').hasClass("no-cart-item-sidebar")) {
		jQuery('.apt_remove_right_sidebar_class').addClass('apt-cis');
	}
});   



/* Booking summary delete extra service NS */
jQuery(document).ready(function () { 
	jQuery(document).on("click",".apt-delete-icon",function() {
		if(jQuery('.apt-es').hasClass('delete-toggle')){
			jQuery(".apt-es").removeClass('delete-toggle'); 
		}
		jQuery(this).parent(".apt-es").addClass('delete-toggle');
	});
	jQuery(document).on("click",".apt-delete-confirm",function() {
		jQuery(this).parent(".apt-es").slideUp();
	});
	
	/* Booking summary delete booking full list */
	jQuery(document).on("click",".apt-delete-booking",function() {
		if(jQuery('.booking-list').hasClass('delete-list')){
			jQuery(".booking-list").removeClass('delete-list'); 
		}
		jQuery(this).parent(".booking-list").addClass('delete-list');
	});
	jQuery(document).on("click",".apt-delete-booking-box",function() {
		jQuery(this).parent(".booking-list").slideUp();
	});
	
	/* Remove delete booking button on ESC key */
	jQuery( document ).on( 'keydown', function ( e ) {
		if ( e.keyCode === 27 )  {
			jQuery(".booking-list").removeClass('delete-list'); 
			jQuery(".apt-es").removeClass('delete-toggle'); 
		}
	});

	/* var elem = jQuery( '.sidebar-box' );
	jQuery( document ).on( 'click', function ( e ) {
		if (jQuery( e.target ).closest( elem ).length === 0 ) {
			jQuery(".booking-list").removeClass('delete-list'); 
			jQuery(".apt-es").removeClass('delete-toggle'); 
		}
	});  */
	
});
jQuery(document).ready(function() {
	jQuery('.apt-slots-count').tooltipster({
		animation: 'grow',
		delay: 10,
		side: 'top',
		theme: 'tooltipster-shadow',
		trigger: 'hover'
	});
});



/* custom dropdown show hide list */

jQuery(document).ready(function () { 
	
	
	
	/* Location */
	jQuery(document).on("click",".select-location",function() {
		jQuery(".service-selection").removeClass('clicked');
		jQuery(".service-dropdown").removeClass('bounceInUp');	
		jQuery(".staff-selection").removeClass('clicked');
		jQuery(".staff-dropdown").removeClass('bounceInUp');
		
		jQuery(".cus-location").addClass('focus');
		jQuery(".location-selection").toggleClass('clicked');
		jQuery(".location-dropdown").toggleClass('bounceInUp');	
		
	});
	jQuery(document).on("click",".select_location",function() {
		jQuery('#selected_location').html(jQuery(this).html());
		jQuery(".location-selection").removeClass('clicked');
		jQuery(".location-dropdown").removeClass('bounceInUp');		
	});
	/* select staff */
	jQuery(document).on("click",".select-staff",function() {
		jQuery(".service-selection").removeClass('clicked');
		jQuery(".service-dropdown").removeClass('bounceInUp');
		jQuery(".location-selection").removeClass('clicked');
		jQuery(".location-dropdown").removeClass('bounceInUp');	
		
		jQuery(".cus-select-staff").addClass('focus');
		jQuery(".staff-selection").toggleClass('clicked');
		jQuery(".staff-dropdown").toggleClass('bounceInUp');
	});
	jQuery(document).on("click",".select_staff",function() {
		jQuery(".staff-selection").removeClass('clicked');
		jQuery(".staff-dropdown").removeClass('bounceInUp');		
	});
	/* Service */
	jQuery(document).on("click",".select-custom",function() {
		jQuery(".staff-selection").removeClass('clicked');
		jQuery(".staff-dropdown").removeClass('bounceInUp');
		jQuery(".location-selection").removeClass('clicked');
		jQuery(".location-dropdown").removeClass('bounceInUp');
		
		jQuery(".cus-select").addClass('focus');	
		jQuery(".service-selection").toggleClass('clicked');
		jQuery(".service-dropdown").toggleClass('bounceInUp');
	});
	jQuery(document).on("click",".select_custom",function() {
		jQuery(".service-selection").removeClass('clicked');
		jQuery(".service-dropdown").removeClass('bounceInUp');		
	});
	jQuery(document).on('click','.apt-addon-ser',function(){
		var addonid = jQuery(this).data('addonid');
		jQuery('.apt-addon-count'+addonid).toggle();
		var value = jQuery(this).prop('checked');
	});
	/* Addon service counting */
	jQuery(function () {
		jQuery('#add').on('click',function(){
			var $qty=jQuery(this).closest('.apt-btn-group').find('.addon_qty');
			var currentVal = parseInt($qty.val());
			if (!isNaN(currentVal)) {
				$qty.val(currentVal + 1);
			}
		});
		jQuery('#minus').on('click',function(){
			var $qty=jQuery(this).closest('.apt-btn-group').find('.addon_qty');
			var currentVal = parseInt($qty.val());
			if (!isNaN(currentVal) && currentVal > 0) {
				$qty.val(currentVal - 1);
			}
		});
	});
});
/* Calendar click date to show slots */
jQuery(document).ready(function () { 
	/* user new and existing radio show hide fields */
	/* jQuery(document).on('click', '#apt-existing-user', function(){			
		jQuery('.existing-user-login').show( "blind", {direction: "vertical"}, 1000 );
		jQuery('.apt-new-user-area').hide( "blind", {direction: "vertical"}, 500 );
		
	});
	jQuery(document).on('click', '#apt-new-user', function(){			
		jQuery('.new-user-area').show( "blind", {direction: "vertical"}, 1000 );
		jQuery('.existing-user-login').hide( "blind", {direction: "vertical"}, 500 );
		
	});  */
	jQuery(document).on('click', '#apt-existing-user', function(){
		jQuery('.existing-user-login').show( "blind", {direction: "vertical"}, 700 );
		jQuery('.new-user-area').hide( "blind", {direction: "vertical"}, 300 );
		jQuery('.new-user-personal-detail-area').hide( "blind", {direction: "vertical"}, 300 );
	});
	jQuery(document).on('click', '#apt-new-user', function(){
		jQuery('.new-user-area').show( "blind", {direction: "vertical"}, 700 );
		jQuery('.existing-user-login').hide( "blind", {direction: "vertical"}, 300 );
		jQuery('.hide_new_user_login_details').show( "blind", {direction: "vertical"}, 300 );
		jQuery('.new-user-personal-detail-area').show( "blind", {direction: "vertical"}, 700 );
	}); 
	jQuery(document).on('click', '#apt-guest-user', function(){
		jQuery('.existing-user-login').hide( "blind", {direction: "vertical"}, 300 );
		jQuery('.hide_new_user_login_details').hide();
		jQuery('.new-user-personal-detail-area').show( "blind", {direction: "vertical"}, 700 );
	}); 
	jQuery(document).on('ready ajaxComplete', function(){
		jQuery("#apt-front-phone").intlTelInput({
		 /*   allowDropdown: false,
		   autoHideDialCode: false,
		   autoPlaceholder: false,
		   dropdownContainer: "body",
		   excludeCountries: ["us"],
		   geoIpLookup: function(callback) {
		     $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
		       var countryCode = (resp && resp.country) ? resp.country : "";
		       callback(countryCode);
		     });
		   },
		   initialCountry: "auto",
		   nationalMode: false,
		   numberType: "MOBILE",
		   onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
		   preferredCountries: ['cn', 'jp'], */
		   separateDialCode: true,
		  utilsScript: "utils.js"
		});
	});
	/* payment methods */
	jQuery(document).on('click','.payment_checkbox',function() {
		if(jQuery('#stripe-payments').is(':checked')) { jQuery('#stripe-payment-main').fadeIn("slow"); } else {
			 jQuery('#stripe-payment-main').fadeOut("slow");
		}		

	});
});

/* see more instructions in service popup */
jQuery(document).ready(function() {
    jQuery(".show-more-toggler").click(function() {
		jQuery(".bullet-more").toggle( "blind", {direction: "vertical"}, 500);
        jQuery(".show-more-toggler").toggleClass('rotate');
    });
});


/*********************************************************************************************/
/********************************** APT Front JS Function ********************************** / 
/*********************************************************************************************/

/* Get Location by Zip Code/Postal Code If Multisite is Enabled */
jQuery(document).on('keyup','#apt_zip_code',function(event){
	var ajaxurl = aptmain_obj.plugin_path;
	var location_err_msg = aptmain_obj.location_err_msg;
	var location_search_msg = aptmain_obj.location_search_msg;
	var Choose_service_msg = aptmain_obj.Choose_service;
	var bwid = jQuery('input[name="bwid"]').val();
	
	var zipcode = jQuery('#apt_zip_code').val();	
	jQuery('#apt_selected_service').val(0);
	jQuery('#apt_selected_staff').val(0);
	jQuery('#apt_selected_location').val('X');
	jQuery('#apt_service_addons').html('');
	jQuery('#apt_service_addon_st').val('D');
	jQuery('#apt_selected_datetime').val('');
	jQuery('#apt_datetime_error').hide();
	jQuery('.apt-selected-date-view').addClass('apt-hide');
	
	if(zipcode!=''){
		jQuery('#apt .loader').show();	
		jQuery('#apt_location_success').hide();			
		jQuery('#close_service_details').trigger('click');	
		jQuery('#selected_custom .apt-value').html(Choose_service_msg);
		
		jQuery('#apt_location_error').html(location_search_msg);
		var postdata = {zipcode:zipcode,bwid:bwid,action:'apt_get_location'};		
		
		jQuery.ajax({
				type:"POST",
				async:false,
				url  : ajaxurl+"/assets/lib/apt_front_ajax.php",
				dataType : 'html',			
				data:postdata,
				success:function(response){	
					jQuery('#apt .loader').hide();
					if(jQuery.trim(response)=='notfound'){
						jQuery('#apt_location_success').hide();	
						jQuery('#apt_location_error').show();
						jQuery('#apt_location_error').html(location_err_msg);
					}else{	
						jQuery('#apt_selected_location').val(0);
						jQuery('#apt_location_success').show();						
						jQuery('#apt_location_error').hide();
						/* Get Services By Found Location */
						var location_id = 0;
						var servicedata = {location_id:location_id,bwid:bwid,action:'apt_get_location_services'};	
						jQuery.ajax({
							type:"POST",
							url  : ajaxurl+"/assets/lib/apt_front_ajax.php",
							dataType : 'html',			
							data:servicedata,
							success:function(response){					
								jQuery('#apt_services').html(response);								
							}
						});
					}				
				}
		});
	}
});

jQuery(document).on('click','.select_location',function(event){
	var ajaxurl = aptmain_obj.plugin_path;
	var location_err_msg = aptmain_obj.location_err_msg;
	var location_search_msg = aptmain_obj.location_search_msg;
	var Choose_service_msg = aptmain_obj.Choose_service;
	jQuery('#apt_location_error').hide();	
	jQuery('#close_service_details').trigger('click');	
	jQuery('#selected_custom .apt-value').html(Choose_service_msg);	
	
	jQuery('#apt_selected_service').val(0);
	jQuery('#apt_selected_staff').val(0);
	jQuery('#apt_selected_location').val('X');
	jQuery('#apt_service_addons').html('');
	jQuery('#apt_service_addon_st').val('D');
	jQuery('#apt_selected_datetime').val('');
	jQuery('#apt_datetime_error').hide();
	jQuery('.apt-selected-date-view').addClass('apt-hide');
	
	jQuery('#apt .loader').show();
	
	/* Get Services By Found Location */
	var location_id = jQuery(this).attr('value');
	var bwid = jQuery('input[name="bwid"]').val();
	
	jQuery('#apt_selected_location').val(location_id);	
	var servicedata = {location_id:location_id,bwid:bwid,action:'apt_get_location_services'};	
		jQuery.ajax({
			type:"POST",
			url  : ajaxurl+"/assets/lib/apt_front_ajax.php",
			dataType : 'html',			
			data:servicedata,
			success:function(response){					
				jQuery('#apt_services').html(response);	
				jQuery('#apt .loader').hide();	
			}
		});	
});


/* Hide Service Desciption On Click of Close */
jQuery(document).on("click","#close_service_details",function() {
		jQuery(".service-details").removeClass('apt-show');
		jQuery(".service-details").addClass('apt-hide');
		
});

/* Get Service Detail On Select Of Service */
jQuery(document).on('click','#apt_services .select_custom',function(event){
	var ajaxurl = aptmain_obj.plugin_path;
	var sid = jQuery(this).data('sid');	
	var bwid = jQuery('input[name="bwid"]').val();		
	var multiloction_status = aptmain_obj.multilocation_status;
	var zipwise_status = aptmain_obj.zipwise_status;
	var selected_location = jQuery('#apt_selected_location').val();
	jQuery('#apt_service_addon_st').val('D');				
	jQuery('#apt_service_addons').html('');	
	jQuery('#apt_selected_datetime').val('');
	jQuery('#apt_datetime_error').hide();
	jQuery('.apt-selected-date-view').addClass('apt-hide');
	
	jQuery('#apt_service_error').hide();
	if(multiloction_status=='E' && selected_location=='X'){		
		jQuery('#apt_location_error').show();
		jQuery(".common-selection-main").removeClass('clicked');
		jQuery('html, body').stop().animate({
			'scrollTop': jQuery('#apt_location_error').offset().top - 80
		}, 800, 'swing', function () {});
		return false;
	}
	if(zipwise_status=='E' && selected_location=='X'){
		var Choose_zipcode_msg = aptmain_obj.Choose_zipcode;		
		jQuery('#apt_location_success').hide();
		jQuery('#apt_location_error').show();
		jQuery('#apt_location_error').html(Choose_zipcode_msg);
		jQuery(".common-selection-main").removeClass('clicked');
		
		jQuery('html, body').stop().animate({
			'scrollTop': jQuery('#apt_location_error').offset().top - 80
		}, 800, 'swing', function () {});
		return false;
	}	
	jQuery('#apt .loader').show();
	jQuery('#selected_custom').html(jQuery(this).html());	
		
	var servicedata = {sid:sid,bwid:bwid,action:'apt_get_service_detail'};
	jQuery('#apt_selected_service').val(sid);
	/* Get Services By Found Location */	
		jQuery.ajax({
			type:"POST",
			url  : ajaxurl+"/assets/lib/apt_front_ajax.php",
			dataType : 'html',			
			data:servicedata,
			success:function(response){	
				var service_details = jQuery.parseJSON(response);
				if(service_details.description!=''){
					jQuery('#apt_service_detail').html(service_details.description);				
					jQuery(".common-selection-main").removeClass('clicked');
					jQuery(".custom-dropdown").slideUp();
					jQuery(".service-details").removeClass('apt-hide');
					jQuery(".service-details").addClass('apt-show');
					if (jQuery("#apt").width() >= 600 && jQuery("#apt").width() < 800){
						jQuery( ".service-duration, .service-price" ).addClass( "active-xs-12" );
					}
				}
				if(service_details.addonsinfo!=''){
					jQuery('#apt_service_addon_st').val('E');				
					jQuery('#apt_service_addons').html(service_details.addonsinfo);				
					jQuery(".common-selection-main").removeClass('clicked');
					jQuery(".custom-dropdown").slideUp();
					jQuery("#apt_service_addons").removeClass('apt-hide');
					jQuery("#apt_service_addons").addClass('apt-show');
				}			
										
			}
		});
		
	/* Get Provider By Service Provider */	
	var servicestaffdata = {sid:sid,bwid:bwid,action:'apt_get_service_providers'};
		jQuery.ajax({
			type:"POST",
			url  : ajaxurl+"/assets/lib/apt_front_ajax.php",
			dataType : 'html',			
			data:servicestaffdata,
			success:function(response){	
				jQuery('#apt .loader').hide();
				jQuery('#apt_staff_info').html(response);
				if (jQuery("#apt").width() >= 600 && jQuery("#apt").width() < 800){
					jQuery( ".apt-staff-box" ).addClass( "active-sm-6" );
				}
			}
		});	
	
});


/* Select Staff */
jQuery(document).on('click','.apt-staff-box,#cus-select-staff .select_staff',function(event){
	
	jQuery('#apt_service_error').hide();
	jQuery('#apt_staff_error').hide();
	jQuery('#apt_staff_error').addClass('apt-hide');
	
	jQuery('#apt_selected_datetime').val('');
	jQuery('#apt_datetime_error').hide();
	jQuery('.apt-selected-date-view').addClass('apt-hide');
	
	
	jQuery(".service-selection").removeClass('clicked');
	jQuery(".service-dropdown").removeClass('bounceInUp');
	jQuery(".location-selection").removeClass('clicked');
	jQuery(".location-dropdown").removeClass('bounceInUp');	
	
	
	var selserviceid = jQuery('#apt_selected_service').val();	
	if(selserviceid==0){
		var Choose_service_msg = aptmain_obj.Choose_service;
		jQuery('#apt_service_error').html(Choose_service_msg);
		jQuery('#apt_service_error').show();
		jQuery('#apt_service_error').removeClass('apt-hide');
		jQuery('html, body').stop().animate({
			'scrollTop': jQuery('#apt_service_error').offset().top - 80
		}, 800, 'swing', function () {});
		return false;
	}
	
	var staffid = jQuery(this).data('staffid');
	jQuery('#apt_selected_staff').val(staffid);
	jQuery('#selected_custom_staff').html(jQuery(this).html());
	
});


/* Addon Quantity Increment/Decrement */
jQuery(document).on('click','.apt_addonqty', function() {
	var ajaxurl = aptmain_obj.plugin_path;
	var addon_id = jQuery(this).data('addonid');
	var addon_qty_action = jQuery(this).data('qtyaction');
	var addon_maxqty = jQuery(this).data('addonmax');	
	var currentqtyvalue = jQuery('#addonqty_'+addon_id).val();
	if(addon_qty_action=='minus'){
		if(parseInt(currentqtyvalue)>1){
			jQuery('#addonqty_'+addon_id).val(parseInt(currentqtyvalue)-1);
		}
	}else{
		if(parseInt(currentqtyvalue)<parseInt(addon_maxqty)){
			jQuery('#addonqty_'+addon_id).val(parseInt(currentqtyvalue)+1);
		}
	}
});
/* Show Provider Time Slot*/
jQuery(document).on('click','.apt-week,.by_default_today_selected', function() {
	if(jQuery(this).hasClass('inactive')){
		return false;
	}
	
	var ajaxurl = aptmain_obj.plugin_path;
	var bwid = jQuery('input[name="bwid"]').val();
	
	var selstaffid = jQuery('#apt_selected_staff').val();	
	if(selstaffid==0){
		jQuery('#apt_staff_error').show();
		jQuery('#apt_staff_error').removeClass('apt-hide');
		jQuery('html, body').stop().animate({
			'scrollTop': jQuery('#apt_staff_error').offset().top - 80
		}, 800, 'swing', function () {});
		return false;
	}else{
		jQuery('#apt .loader').show();
		//console.log('bwid: ' + bwid);
		var calrowid = jQuery(this).data('calrowid');
		var seldate = jQuery(this).data('seldate');
		var calenderdata = {selstaffid:selstaffid,bwid:bwid,seldate:seldate,action:'apt_get_provider_slots'};
		
		jQuery('.apt-week').each(function(){
			jQuery(this).removeClass('active');				
			
		});
		jQuery('.apt-show-time').each(function(){	
			jQuery(this).removeClass('shown');			
			jQuery(this).removeAttr('style');			
			
		});
		jQuery(this).addClass('active');		
		
		jQuery.ajax({
			type:"POST",
			url  : ajaxurl+"/assets/lib/apt_front_ajax.php",
			dataType : 'html',			
			data:calenderdata,
			success:function(response){	
				console.log(response);
				jQuery('#apt .loader').hide();
				jQuery('.curr_selected_row'+calrowid).addClass('shown');
				jQuery('.curr_selected_row'+calrowid).css('display','block');
				jQuery('.curr_selected_row'+calrowid+' .apt_day_slots').html(response);
				
			}
		});	
	}	
});

/* Select Time Slot*/
jQuery(document).on('click','.apt_select_slot', function() {
	var ajaxurl = aptmain_obj.plugin_path;
	var slotdate = jQuery(this).data('slot_db_date');
	var slottime = jQuery(this).data('slot_db_time');
	var displaydate = jQuery(this).data('displaydate');
	var displaytime = jQuery(this).data('displaytime');
	
	jQuery('#apt_datetime_error').hide();
	jQuery('.apt-selected-date-view').removeClass('apt-hide');
	jQuery('.time-slot').each(function(){
			jQuery(this).removeClass('apt-slot-selected');				
			
	});
	jQuery(this).addClass('apt-slot-selected');
	jQuery('.apt-selected-date-view').removeClass('apt-hide');
	jQuery('#apt_selected_datetime').val(slotdate+' '+slottime);
	jQuery('.apt-date-selected').html(displaydate);
	jQuery('.apt-time-selected').html(displaytime);
	jQuery('.apt-show-time').hide();

});
	
/* Goto Today */
jQuery(document).on('click','.today_btttn', function(){
	
	var calmonth = jQuery('.previous-date').data('curmonth');
	var calyear = jQuery('.previous-date').data('curyear');
	var bwid = $jQuery('input[name="bwid"]').val();
	
	var selmonth = jQuery(this).data('smonth');
	var selyear = jQuery(this).data('syear');
	
	if(selmonth==calmonth && calyear==selyear){
		jQuery('.by_default_today_selected').trigger('click');	
	}else{
		jQuery('#apt .loader').show();
		var ajaxurl = aptmain_obj.plugin_path;
		var calenderdata = {calmonth:calmonth,bwid:bwid,calyear:calyear,action:'apt_cal_next_prev'};
		jQuery.ajax({
			type:"POST",
			url  : ajaxurl+"/assets/lib/apt_front_ajax.php",
			dataType : 'html',			
			data:calenderdata,
			success:function(response){	
				jQuery('#apt .loader').hide();
				jQuery('.calendar-wrapper').html(response);
				jQuery('.by_default_today_selected').trigger('click');
			}
		});		
	}	
});

/* Get Calender Next Previous Month */
jQuery(document).on('click','.apt_month_change', function() {
	
	jQuery('#apt_selected_datetime').val('');
	jQuery('#apt_datetime_error').hide();
	jQuery('.apt-selected-date-view').addClass('apt-hide');
	var bwid = jQuery('input[name="bwid"]').val();
	
	var ajaxurl = aptmain_obj.plugin_path;
	var calmonth = jQuery(this).data('calmonth');
	var calyear = jQuery(this).data('calyear');
	var calenderdata = {calmonth:calmonth,bwid:bwid,calyear:calyear,action:'apt_cal_next_prev'};
	jQuery('#apt .loader').show();
	
	jQuery.ajax({
		type:"POST",
		url  : ajaxurl+"/assets/lib/apt_front_ajax.php",
		dataType : 'html',			
		data:calenderdata,
		success:function(response){				
			jQuery('.calendar-wrapper').html(response);
			jQuery('#apt .loader').hide();
		}
	});
});

/* Add Booking Into cart */
jQuery(document).on('click','#btn-second-step', function() {
	var selected_datetime = jQuery('#apt_selected_datetime').val();
	if(selected_datetime==0){
		jQuery('#apt_datetime_error').show();
		jQuery('html, body').stop().animate({
			'scrollTop': jQuery('#apt_datetime_error').offset().top - 80
		}, 800, 'swing', function () {});
		return false;
	}else{
		var ajaxurl = aptmain_obj.plugin_path;
		var selected_location = jQuery('#apt_selected_location').val();
		var selected_service = jQuery('#apt_selected_service').val();
		var selected_staff = jQuery('#apt_selected_staff').val();
		var service_addon_st = jQuery('#apt_service_addon_st').val();
		var selected_datetime = jQuery('#apt_selected_datetime').val();
		var bwid = jQuery('input[name="bwid"]').val();

		var serviceaddons = [];
		if(service_addon_st=='E'){
			jQuery('.addon-service-list li .addon-checkbox').each(function(){
				if(jQuery(this).is(':checked')){
					var maxqtyst = jQuery(this).data('saddonmaxqty');
					var saddonid = jQuery(this).data('saddonid');
					var maxqty = '0';
					if(maxqtyst=='Y'){
						var maxqty = jQuery('#addonqty_'+saddonid).val();	
					}
					serviceaddons.push({'addonid':saddonid,'maxqty':maxqty}); 
				}
			});
		}
		jQuery('#apt .loader').show();
		var cartitemdata = {bwid:bwid,selected_location:selected_location,selected_service:selected_service,selected_staff:selected_staff,service_addon_st:service_addon_st,selected_datetime:selected_datetime,serviceaddons:serviceaddons,action:'add_item_into_cart'};
	
		jQuery.ajax({
			type:"POST",
			url  : ajaxurl+"/assets/lib/apt_front_ajax.php",
			dataType : 'html',			
			data:cartitemdata,
			success:function(response){	
				jQuery('#apt_booking_sidebar').html(response);
				jQuery('#apt .loader').hide();
				jQuery('#apt_selected_location').val('X');
				jQuery('#apt_selected_service').val('0');
				jQuery('#apt_selected_staff').val('0');
				jQuery('#apt_service_addon_st').val('d');
				jQuery('#apt_selected_datetime').val('0');
				jQuery('#apt_first_step').removeClass('show-data');
				jQuery('#apt_first_step').addClass('hide-data');        
				jQuery('#apt_second_step').removeClass('hide-data');
				jQuery('#apt_second_step').addClass('show-data');
				jQuery('.apt_remove_left_sidebar_class').removeClass('no-sidebar-right');
				jQuery('.apt_remove_left_sidebar_class').addClass('apt-asr');
				jQuery('.apt_remove_right_sidebar_class').removeClass('no-cart-item-sidebar');
				jQuery('.apt_remove_right_sidebar_class').addClass('cart-item-sidebar');
				jQuery('.apt_remove_right_sidebar_class').addClass('apt-cis');
			}
		});		
	}
});

/* Remove Item From Cart */
jQuery(document).on('click','.apt_remove_item', function() {
	var ajaxurl = aptmain_obj.plugin_path;
	var cartitemid = jQuery(this).data('cartitemid');
	var bwid = jQuery('input[name="bwid"]').val();
	
	var deletecartitemdata = {cartitemid:cartitemid,bwid:bwid,action:'apt_delete_cart_item'};
	jQuery('#apt .loader').show();
		jQuery.ajax({
			type:"POST",
			url  : ajaxurl+"/assets/lib/apt_front_ajax.php",
			dataType : 'html',			
			data:deletecartitemdata,
			success:function(response){	
				jQuery.ajax({
					type:"POST",
					url  : ajaxurl+"/assets/lib/apt_front_ajax.php",
					dataType : 'html',			
					data:{ bwid:bwid,action:'refresh_sidebar'},
					success:function(response){
						jQuery('#apt_booking_sidebar').html(response);
						jQuery('#apt .loader').hide();
						if(jQuery('#apt_booking_summary').hasClass('apt_cart_item_not_exist')){
							jQuery('.apt_remove_left_sidebar_class').addClass('no-sidebar-right');
							jQuery('.apt_remove_left_sidebar_class').removeClass('apt-asr');
							jQuery('.apt_remove_right_sidebar_class').addClass('no-cart-item-sidebar');
							jQuery('.apt_remove_right_sidebar_class').removeClass('cart-item-sidebar');
							
							jQuery('#apt_second_step').removeClass('show-data');
							jQuery('#apt_second_step').addClass('hide-data');        
							jQuery('#apt_first_step').removeClass('hide-data');
							jQuery('#apt_first_step').addClass('show-data');
							jQuery('.select_location').trigger('click');
							jQuery('#apt_zip_code').trigger('keyup');
							jQuery('.apt-show-time').each(function(){ jQuery(this).hide(); });
							jQuery('html, body').stop().animate({
								'scrollTop': jQuery('#apt-main').offset().top - 80
							}, 800, 'swing', function () {});
						}
					}
				});	
			}
		});
	
	
});

/* Remove Service Addon From Cart Item */
jQuery(document).on('click','.apt_remove_addon', function() {
	var ajaxurl = aptmain_obj.plugin_path;
	var cartitemid = jQuery(this).data('cartitemid');
	var addonid = jQuery(this).data('addonid');
	var bwid = jQuery('input[name="bwid"]').val();
	
	var deletecartaddondata = {addonid:addonid,bwid:bwid,cartitemid:cartitemid,action:'apt_delete_addon'};
	jQuery('#apt .loader').show();
		jQuery.ajax({
			type:"POST",
			url  : ajaxurl+"/assets/lib/apt_front_ajax.php",
			dataType : 'html',			
			data:deletecartaddondata,
			success:function(response){	
				 jQuery.ajax({
					type:"POST",
					url  : ajaxurl+"/assets/lib/apt_front_ajax.php",
					dataType : 'html',			
					data:{ bwid:bwid,action:'refresh_sidebar'},
					success:function(response){	
						jQuery('#apt_booking_sidebar').html(response);
						jQuery('#apt .loader').hide();
						if(jQuery('#apt_booking_summary').hasClass('apt_cart_item_not_exist')){
							jQuery('.apt_remove_left_sidebar_class').addClass('no-sidebar-right');
							jQuery('.apt_remove_left_sidebar_class').removeClass('apt-asr');
							jQuery('.apt_remove_right_sidebar_class').addClass('no-cart-item-sidebar');
							jQuery('.apt_remove_right_sidebar_class').removeClass('cart-item-sidebar');
							jQuery('.apt_remove_right_sidebar_class').removeClass('apt-cis');

							jQuery('#apt_second_step').removeClass('show-data');
							jQuery('#apt_second_step').addClass('hide-data');        
							jQuery('#apt_first_step').removeClass('hide-data');
							jQuery('#apt_first_step').addClass('show-data');
							jQuery('.select_location').trigger('click');
							jQuery('#apt_zip_code').trigger('keyup');
							jQuery('.apt-show-time').each(function(){ jQuery(this).hide(); });
							jQuery('html, body').stop().animate({
								'scrollTop': jQuery('#apt-main').offset().top - 80
							}, 800, 'swing', function () {});
						}

					}
				});	 
			}
		});
	
	
});
/* Apply/Reverse Coupon */
jQuery(document).on('click','#remove_applied_coupon,#apt_apply_coupon', function() {
		var ajaxurl = aptmain_obj.plugin_path;	
		var couponaction = jQuery(this).data('action');	
		var selected_location = jQuery('#apt_selected_location').val();
		var bwid = jQuery('input[name="bwid"]').val();
		jQuery('.apt_promocode_error').hide();	
		if(selected_location=='X'){
			var selected_location = 0;
		}
		
		
		if(couponaction=='apply'){
			var coupon_code = jQuery('#apt-coupon').val();
			if(coupon_code==''){
				jQuery('.apt_promocode_error').show();	
			}
			var coupondata = {selected_location:selected_location,coupon_code:coupon_code,couponaction:'apply',action:'apt_coupon_ar'};
		}else{
			var coupondata = {selected_location:selected_location,couponaction:'reverse',action:'apt_coupon_ar'};
		}
		jQuery('#apt .loader').show();
	
		jQuery.ajax({
			type:"POST",
			url  : ajaxurl+"/assets/lib/apt_front_ajax.php",
			dataType : 'html',			
			data:coupondata,
			success:function(response){
				jQuery('#apt .loader').hide();	
				/* If coupon applied */
				if(couponaction=='apply'){
					if(response=='ok'){
						jQuery.ajax({
							type:"POST",
							url  : ajaxurl+"/assets/lib/apt_front_ajax.php",
							dataType : 'html',			
							data:{ bwid:bwid,action:'refresh_sidebar'},
							success:function(response){	
								jQuery('#apt_booking_sidebar').html(response);
								if(jQuery('#apt_booking_summary').hasClass('apt_cart_item_not_exist')){
									jQuery('.apt_remove_left_sidebar_class').addClass('no-sidebar-right');
									jQuery('.apt_remove_left_sidebar_class').removeClass('apt-asr');
									jQuery('.apt_remove_right_sidebar_class').addClass('no-cart-item-sidebar');
									jQuery('.apt_remove_right_sidebar_class').removeClass('cart-item-sidebar');
									jQuery('.apt_remove_right_sidebar_class').removeClass('apt-cis');
								}

							}
						});	
					}else{
						jQuery('.apt_promocode_error').show();	
					}					
				/* If coupon reversed */
				}else{
					jQuery.ajax({
							type:"POST",
							url  : ajaxurl+"/assets/lib/apt_front_ajax.php",
							dataType : 'html',			
							data:{ action:'refresh_sidebar'},
							success:function(response){	
								jQuery('#apt_booking_sidebar').html(response);
								if(jQuery('#apt_booking_summary').hasClass('apt_cart_item_not_exist')){
									jQuery('.apt_remove_left_sidebar_class').addClass('no-sidebar-right');
									jQuery('.apt_remove_left_sidebar_class').removeClass('apt-asr');
									jQuery('.apt_remove_right_sidebar_class').addClass('no-cart-item-sidebar');
									jQuery('.apt_remove_right_sidebar_class').removeClass('cart-item-sidebar');
									jQuery('.apt_remove_right_sidebar_class').removeClass('apt-cis');
								}
							}
						});
				}								
			}
		});	
});
jQuery(document).on('click','#btn-more-bookings', function() {
	jQuery('#apt_second_step').removeClass('show-data');
	jQuery('#apt_second_step').addClass('hide-data');        
	jQuery('#apt_first_step').removeClass('hide-data');
	jQuery('#apt_first_step').addClass('show-data');
	/* jQuery('.select_location').trigger('click');
	jQuery('#apt_zip_code').trigger('keyup'); */
	jQuery('.apt-show-time').each(function(){ jQuery(this).hide(); });
		
	jQuery('.apt-selected-date-view').removeClass('apt-show');	
	jQuery('.apt-selected-date-view').addClass('apt-hide');	
	
	var Choose_location = aptmain_obj.Choose_location;
	jQuery('#selected_location .apt-value').html(Choose_location);
	jQuery('#apt_service_addons').html('');
	jQuery('#close_service_details').trigger('click');
	
	var Choose_provider = aptmain_obj.Choose_provider;
	jQuery('#selected_custom_staff .apt-value').html(Choose_provider);
	jQuery('.staff-radio').each(function(){
		jQuery(this).attr('checked',false);
	});
	
	
	var Choose_service = aptmain_obj.Choose_service;
	jQuery('#selected_custom .apt-value').html(Choose_service);
		
	jQuery('#apt_selected_location').val('X');
	jQuery('#apt_selected_service').val('0');
	jQuery('#apt_selected_staff').val('0');
	jQuery('#apt_service_addon_st').val('d');
	jQuery('#apt_selected_datetime').val('0');
				
});



/********Code For Register booking complete and login and logout***************/
jQuery(document).ready(function(){
	var errObj = aptmain_error_obj;
	jQuery('#apt_login_form_check_validate').validate({
		rules:{
			'apt_existing_login_username_input':{required:true,email:true},
			'apt_existing_login_password_input':{required:true,minlength:8,maxlength:30},
		},
		messages:{
			'apt_existing_login_username_input':{required : errObj.Please_Enter_Email, email : errObj.Please_Enter_Valid_Email},
			'apt_existing_login_password_input':{required : errObj.Please_Enter_Password,minlength:errObj.Please_enter_minimum_8_Characters, maxlength:errObj.Please_enter_maximum_30_Characters},
			
		}
	});
});
jQuery(document).on('click','#apt_existing_login_btn', function() {
	if(jQuery('#apt_login_form_check_validate').valid()){
		jQuery('#apt .loader').show();
		var ajaxurl = aptmain_obj.plugin_path;
		var uname = jQuery('#apt_existing_login_username').val();
		var pwd = jQuery('#apt_existing_login_password').val();
		var dataString = { 'uname':uname, 'pwd':pwd, 'action':'get_existing_user_data' };
		jQuery.ajax({
			type:"POST",
			url  : ajaxurl+"/assets/lib/apt_front_ajax.php",
			dataType : 'html',
			data:dataString,
			success:function(response){
				jQuery('#apt .loader').hide();
				if(jQuery.trim(response) != "Invalid Username or Password"){
					var getdata = jQuery.parseJSON(response);
					jQuery('.user-login-main').hide();
					jQuery('.existing-user-login').hide();
					jQuery('.existing-user-success-login-message').show();
					jQuery('.new-user-personal-detail-area').show();
					jQuery("#invalid_un_pwd").css("display","none");
					jQuery('.hide_new_user_login_details').hide();
					jQuery('#logged_in_user_name').html(getdata.first_name+" "+getdata.last_name);
					
					jQuery('#new_user_firstname').addClass("focus");
					jQuery('#new_user_lastname').addClass("focus");
					jQuery('#apt-front-phone').addClass("focus");
					jQuery('#new_user_street_address').addClass("focus");
					jQuery('#new_user_city').addClass("focus");
					jQuery('#new_user_state').addClass("focus");
					jQuery('#new_user_notes').addClass("focus");
					
					jQuery('#new_user_preferred_password').val(getdata.password);
					jQuery('#new_user_preferred_username').val(getdata.user_email);
					jQuery('#new_user_firstname').val(getdata.first_name);
					jQuery('#new_user_lastname').val(getdata.last_name);
					/* jQuery('#apt-front-phone').val(getdata.phone); */
					jQuery('#apt-front-phone').intlTelInput("setNumber", getdata.phone);
					
					jQuery('#apt-front-phone').attr('data-ccode',getdata.ccode);
					jQuery('#new_user_street_address').val(getdata.address);
					jQuery('#new_user_city').val(getdata.city);
					jQuery('#new_user_state').val(getdata.state);
					jQuery('#new_user_notes').val(getdata.notes);
					
					if(getdata.gender == 'M'){
						jQuery('#apt-male').prop('checked',true);
					}else{
						jQuery('#apt-female').prop('checked',true);
					}
					
					jQuery('.error').each(function(){
						jQuery(this).hide();
					});
				}else{
					jQuery("#invalid_un_pwd").css("display","block");
				}
			}
		});
	}
});


/* Validate Card Fields */
jQuery(document).ready(function() {
	jQuery('input.cc-number').payment('formatCardNumber');
	jQuery('input.cc-cvc').payment('formatCardCVC');
	jQuery('input.cc-exp-month').payment('restrictNumeric');
	jQuery('input.cc-exp-year').payment('restrictNumeric');

});

jQuery(document).on( "click",'.apt-termcondition-area',function() {
		jQuery('.apt_terms_and_condition_error').hide();
});


jQuery(document).on( "click",'#btn-third-step',function() {
	jQuery('.apt_terms_and_condition_error').hide();
	var errObj = aptmain_error_obj;
	var ajaxurl = aptmain_obj.plugin_path;
	var thankyou_url = aptmain_obj.thankyou_url;
	var apt_payment_gateways_st = aptmain_obj.apt_payment_gateways_st;
	var apt_terms_and_condition_status = aptmain_obj.apt_terms_and_condition_status;
	var currstep = jQuery('.apt-booking-step').data('current');
	var terms_condition = jQuery("#apt-accept-conditions").prop("checked");
    
	if(!jQuery('#apt_second_step').hasClass('show-data')){
		jQuery('#apt_first_step').removeClass('show-data');
		jQuery('#apt_first_step').addClass('hide-data');        
		jQuery('#apt_second_step').removeClass('hide-data');
		jQuery('#apt_second_step').addClass('show-data');	
		return false;
	}	
	
	if(apt_terms_and_condition_status=='E' && terms_condition !== true){  
		jQuery('.apt_terms_and_condition_error').show();
		jQuery('html, body').stop().animate({
								'scrollTop': jQuery('.apt_terms_and_condition_error').offset().top - 80
						}, 800, 'swing', function () {});		
		
		return false; 
	}
	
	jQuery.validator.addMethod("pattern_phone", function(value, element) {
        return this.optional(element) || /^[0-9+]*$/.test(value);
    }, "Enter Only Numerics");
	
	jQuery('#apt_newuser_form_validate').validate({
		rules:{
			'new_user_preferred_username':{required:true, email:true, remote: {
								url  : ajaxurl+"/assets/lib/apt_front_ajax.php",
								type: "POST",
								async: false,
								data: {
									email: function(){ return jQuery("#new_user_preferred_username").val(); },
									action:"check_existing_username"
								}
							}},
			'new_user_preferred_password':{required:true,minlength:8,maxlength:30},
			'new_user_firstname':{required:true},
			'new_user_lastname':{required:true},
			'apt-phone':{required:true,pattern_phone:true,minlength:10,maxlength:14},
			'new_user_street_address':{required:true},
			'new_user_city':{required:true},
			'new_user_state':{required:true},
			'new_user_notes':{required:true},
		},
		messages:{
			'new_user_preferred_username':{required : errObj.Please_Enter_Email, email : errObj.Please_Enter_Valid_Email, remote : errObj.Email_already_exist},
			'new_user_preferred_password':{required : errObj.Please_Enter_Password,minlength:errObj.Please_enter_minimum_8_Characters, maxlength:errObj.Please_enter_maximum_30_Characters},
			'new_user_firstname':{required : errObj.Please_Enter_First_Name},
			'new_user_lastname':{required : errObj.Please_Enter_Last_Name},
			'apt-phone':{required : errObj.Please_Enter_Phone_Number,pattern_phone : errObj.Please_Enter_Valid_Phone_Number,minlength:errObj.Please_enter_minimum_10_Characters, maxlength:errObj.Please_enter_maximum_14_Characters},
			'new_user_street_address':{required : errObj.Please_Enter_Address},
			'new_user_city':{required : errObj.Please_Enter_City},
			'new_user_state':{required : errObj.Please_Enter_State},
			'new_user_notes':{required : errObj.Please_Enter_Notes},
		}
	});
	
	jQuery('.get_custom_field').each(function(){
		var name_field = jQuery(this).attr('name');
		var required_field = jQuery(this).data('required');
		var fieldlabel = jQuery(this).data('fieldlabel')
		if(required_field == "Y"){
			jQuery(this).rules("add",{ required : true, messages : { required : errObj.Please_Enter+" "+fieldlabel+""}});
		}
	});
	
	if(jQuery('#apt_newuser_form_validate').valid()){
		jQuery('#apt .loader').show();
		jQuery('.apt_terms_and_condition_error').hide();
		var username = jQuery('#new_user_preferred_username').val();
		if(apt_payment_gateways_st=='E'){			
			var payment_method = jQuery('.apt_payment_method:checked').val();
		}else{
			var payment_method = 'pay_locally';	
		}
		var pwd = jQuery('#new_user_preferred_password').val();
		var fname = jQuery('#new_user_firstname').val();
		var lname = jQuery('#new_user_lastname').val();
		var phone = jQuery('#apt-front-phone').val();
		var address = jQuery('#new_user_street_address').val();
		var city = jQuery('#new_user_city').val();
		var state = jQuery('#new_user_state').val();
		var notes = jQuery('#new_user_notes').val();
		var check_status = jQuery('.new_and_existing_user_radio_btn').prop('checked');
		var check_statuss = jQuery('.new_and_existing_user_radio_btn:checked').val();
		var check_gender = jQuery('.new_user_gender').prop('checked');
		var ccode = jQuery('#apt-front-phone').data('ccode');
		var bwid = jQuery('input#bwid').val();

		
		
		if(check_statuss == 'Guest User'){
			var apt_user_type = 'guest';
		}else if(check_statuss == 'Existing User'){
			var apt_user_type = 'existing';
			if(jQuery('#apt_existing_login_btn').is(':visible')){
				jQuery('#invalid_un_pwd').show();
				jQuery('html, body').stop().animate({
						'scrollTop': jQuery('#invalid_un_pwd').offset().top - 80
				}, 800, 'swing', function () {});
				jQuery('#apt .loader').hide();
				return false;
			}			
		}else{
			var apt_user_type = 'new';
		}
		
		if(check_gender){
			var gender = 'M';
		}else{
			var gender = 'F';
		}
		
		var dynamic_field_add = {};
		jQuery('.get_custom_field').each(function(){
			if(jQuery(this).data('fieldname') == "radio_group"){
				dynamic_field_add[jQuery(this).data('fieldlabel')] = jQuery('.get_custom_field:checked').val();
			}else{
				dynamic_field_add[jQuery(this).data('fieldlabel')] = jQuery(this).val();
			}
		});
		
		var dataString = { 'purl':ajaxurl,'username':username, 'pwd':pwd, 'fname':fname, 'lname':lname, 'phone':phone, 'address':address, 'city':city, 'state':state, 'notes':notes, 'apt_user_type':apt_user_type, 'payment_method':payment_method, 'gender':gender, 'dynamic_field_add':dynamic_field_add, 'ccode':ccode, 'bwid':bwid, 'action':'apt_booking_complete' };
		
		if(payment_method == 'stripe'){
			var stripe_pubkey = apt_stripeObj.pubkey;
			Stripe.setPublishableKey(stripe_pubkey);
			var stripeResponseHandler = function(status, response) {							
				if (response.error) {
					/* Show the errors on the form*/
					jQuery('#apt .loader').hide();
					jQuery('.show_card_payment_error').show();
					jQuery('.show_card_payment_error').text(response.error.message);
					jQuery('html, body').stop().animate({
						'scrollTop': jQuery('.show_card_payment_error').offset().top - 80
					}, 800, 'swing', function () {});
					
				} else {
					/* token contains id, last4, and card type*/
					var token = response.id;					
					function waitForElement(){ 
						if(typeof token !== "undefined" && token != ''){
							var st_token = token;									
							dataString['st_token'] = st_token;
							jQuery.ajax({
								type:"POST",
								url  : ajaxurl+"/assets/lib/apt_front_ajax.php",
								dataType : 'html',
								data:dataString,
								success:function(response){	
									//console.log('id: ' + bwid);
									//console(response);					
									jQuery.ajax({
										type:"POST",
										url  : ajaxurl+"/assets/lib/apt_front_ajax.php",
										dataType : 'html',			
										data:{ bwid:bwid,action:'refresh_sidebar'},
										success:function(response){
											jQuery('#apt .loader').hide();	
											jQuery('#apt_booking_sidebar').html(response);
											if(thankyou_url!=''){
												window.location.href = thankyou_url;
											}
											jQuery('#apt_first_step').removeClass('show-data');
											jQuery('#apt_first_step').addClass('hide-data');
											jQuery('#apt_second_step').removeClass('show-data');
											jQuery('#apt_second_step').addClass('hide-data');
											jQuery('#apt_third_step').removeClass('hide-data');
											jQuery('#apt_third_step').addClass('show-data');
											jQuery('.apt_remove_left_sidebar_class').addClass('no-sidebar-right');
											jQuery('.apt_remove_left_sidebar_class').removeClass('apt-asr');
											jQuery('.apt_remove_right_sidebar_class').addClass('no-cart-item-sidebar');
											jQuery('.apt_remove_right_sidebar_class').removeClass('cart-item-sidebar');
											jQuery('.apt_remove_right_sidebar_class').removeClass('apt-cis');
										}
									});
								}
							});
						} else{ 
							setTimeout(function(){ waitForElement(); },2000); 
						} 
					}
					waitForElement();
				}
			};
			/*Disable the submit button to prevent repeated clicks*/
			Stripe.card.createToken({
				number: jQuery('#card-number').val(),
				cvc: jQuery('#cvc-code').val(),
				exp_month: jQuery('#card-expiry').val(),
				exp_year: jQuery('.cc-exp-year').val()
			}, stripeResponseHandler); 
		} else if(payment_method == "payumoney"){
			jQuery.ajax({
				type:"POST",
				url  : ajaxurl+"/assets/lib/apt_front_ajax.php",
				dataType : 'html',
				data:dataString,
				success:function(response){
					jQuery.ajax({
						type:"POST",
						url  : ajaxurl+"/assets/lib/payumoney_payment_process.php",
						data:dataString,
						success:function(response){
							var pm = jQuery.parseJSON(response);
							jQuery("#payu_key").val(pm.merchant_key);
							jQuery("#payu_hash").val(pm.hash);
							jQuery("#payu_txnid").val(pm.txnid);
							jQuery("#payu_amount").val(pm.amt);
							jQuery("#payu_fname").val(pm.fname);
							jQuery("#payu_email").val(pm.email);
							jQuery("#payu_phone").val(pm.phone);
							jQuery("#payu_productinfo").val(pm.productinfo);
							jQuery("#payu_surl").val(pm.payu_surl);
							jQuery("#payu_furl").val(pm.payu_furl); 
							jQuery("#payu_service_provider").val(pm.service_provider);
							jQuery("#payuForm").submit();
						}
					});
				}
			});
			} else if(payment_method == 'paytm'){
				jQuery.ajax({
					type:"POST",
					url  : ajaxurl+"/assets/lib/apt_front_ajax.php",
					dataType : 'html',
					data:dataString,
					success:function(response){
						jQuery('#apt .loader').show();
						jQuery.ajax({
							type:"POST",
							url  : ajaxurl+"/assets/lib/paytm_payment_process.php",
							data:dataString,
							success:function(response){
								var response_detail = jQuery.parseJSON(response);
								jQuery('#apt_paytm_form').attr('action',response_detail.PAYTM_TXN_URL);
								jQuery('#apt_CHECKSUMHASH').val(response_detail.CHECKSUMHASH);
								jQuery('#apt_paytm_form').append(response_detail.Extra_form_fields);
								jQuery("#apt_paytm_form").submit();
							}
						});
					}
				});
			}else if(payment_method == 'paypal'){
			jQuery.ajax({
				type:"POST",
				url  : ajaxurl+"/assets/lib/apt_front_ajax.php",
				dataType : 'html',
				data:dataString,
				success:function(response){
					var response_detail = jQuery.parseJSON(response);
					if(response_detail.status=='error'){
						jQuery('.payment_error_msg').show();
						jQuery('.payment_error_msg').text(response_detail.value);
					}else{
						window.location = response_detail.value;
					}
					if(response=='OK'){
						jQuery.ajax({
							type:"POST",
							url  : ajaxurl+"/assets/lib/apt_front_ajax.php",
							dataType : 'html',
							data:{ bwid:bwid,action:'refresh_sidebar'},
							success:function(response){
								jQuery('#apt .loader').hide();	
								jQuery('#apt_booking_sidebar').html(response);
								jQuery('#apt_first_step').removeClass('show-data');
								jQuery('#apt_first_step').addClass('hide-data');
								jQuery('#apt_second_step').removeClass('show-data');
								jQuery('#apt_second_step').addClass('hide-data');
								jQuery('#apt_third_step').removeClass('hide-data');
								jQuery('#apt_third_step').addClass('show-data');
								jQuery('.apt_remove_left_sidebar_class').addClass('no-sidebar-right');
								jQuery('.apt_remove_left_sidebar_class').removeClass('apt-asr');
								jQuery('.apt_remove_right_sidebar_class').addClass('no-cart-item-sidebar');
								jQuery('.apt_remove_right_sidebar_class').removeClass('cart-item-sidebar');
								jQuery('.apt_remove_right_sidebar_class').removeClass('apt-cis');
							}
						});
					}
				}
			});
		}else{
			
			jQuery.ajax({
				type:"POST",
				url  : ajaxurl+"/assets/lib/apt_front_ajax.php",
				dataType : 'html',
				data:dataString,
				success:function(response){	
				//	console.log(response);
					jQuery.ajax({
						type:"POST",
						url  : ajaxurl+"/assets/lib/apt_front_ajax.php",
						dataType : 'html',			
						data:{ bwid:bwid,action:'refresh_sidebar'},
						success:function(response){
							jQuery('#apt .loader').hide();	
							if(thankyou_url!=''){
								//window.location.href = thankyou_url;
							}
							jQuery('#apt_booking_sidebar').html(response);
							jQuery('#apt_first_step').removeClass('show-data');
							jQuery('#apt_first_step').addClass('hide-data');
							jQuery('#apt_second_step').removeClass('show-data');
							jQuery('#apt_second_step').addClass('hide-data');
							jQuery('#apt_third_step').removeClass('hide-data');
							jQuery('#apt_third_step').addClass('show-data');
							jQuery('.apt_remove_left_sidebar_class').addClass('no-sidebar-right');
							jQuery('.apt_remove_left_sidebar_class').removeClass('apt-asr');
							jQuery('.apt_remove_right_sidebar_class').addClass('no-cart-item-sidebar');
							jQuery('.apt_remove_right_sidebar_class').removeClass('cart-item-sidebar');
							jQuery('.apt_remove_right_sidebar_class').removeClass('apt-cis');
						}
					});
				}
			});
		}
	}
});

jQuery(document).on( "click", '#apt_log_out_user', function() {
	var ajaxurl = aptmain_obj.plugin_path;
	var dataString = { 'action':'apt_logout_user' };
	jQuery('#apt .loader').show();
	jQuery.ajax({
		type:"POST",
		url  : ajaxurl+"/assets/lib/apt_front_ajax.php",
		dataType : 'html',
		data:dataString,
		success:function(response){
			jQuery('#apt .loader').hide();
			jQuery('.user-login-main').show();
			jQuery('.user-login-main').show();
			jQuery('.existing-user-success-login-message').hide();
			jQuery('#apt-new-user').trigger('click');
			
			jQuery(".apt-main-left label.custom").removeClass('focus'); 
			jQuery(".apt-main-left .custom-input").removeClass('focus'); 
			jQuery('#new_user_preferred_password').val('');
			jQuery('#new_user_preferred_username').val('');
			jQuery('#new_user_firstname').val('');
			jQuery('#new_user_lastname').val('');
			jQuery('#apt-front-phone').val('');
			jQuery('#new_user_street_address').val('');
			jQuery('#new_user_city').val('');
			jQuery('#new_user_state').val('');
			jQuery('#new_user_notes').val('');
			
			jQuery('#apt-male').prop('checked',true);
			
		}
	});
});

/* Display Country Code on click flag on phone*/
jQuery(window).load(function(){
	if(jQuery("#apt-front-phone").data('ccode') != ''){
		jQuery('.country').removeClass('active');
		jQuery('.country').each(function(){
			if('+'+jQuery(this).data("dial-code") == jQuery("#apt-front-phone").data('ccode')){
				jQuery(this).addClass('active');
				var get_phoneno = jQuery(this).val();
				jQuery('#apt-front-phone').intlTelInput("setNumber", '+'+jQuery(this).data("dial-code")+''+get_phoneno);
			}
		});	
	}else{
		var country_code=jQuery('.country.active').data("dial-code");
		if(country_code === undefined){
			country_code = '1';
		}
		var get_phoneno = jQuery('#apt-front-phone').val();
		if(get_phoneno == ''){
			jQuery('#apt-front-phone').intlTelInput("setNumber", '+'+country_code);
		}
		jQuery("#apt-front-phone").attr('data-ccode','+'+country_code);
	}
});
jQuery(document).on('click','.country',function() {
	var country_code=jQuery(this).data("dial-code");
	var get_phoneno = jQuery('#apt-front-phone').val();
	jQuery('#apt-front-phone').intlTelInput("setNumber", '+'+country_code);
	jQuery("#apt-front-phone").attr('data-ccode','+'+country_code);
});

/* On focus transform label */
jQuery(document).ready(function () { 
	function checkForInput(element) {
	  /* element is passed to the function ^ */
		if(jQuery(element).hasClass('apt-phone-input')){
			var $label = jQuery('.apt-phone-label'); 
		}else{
			var $label = jQuery(element).siblings('label'); 
		}
				
		if (jQuery(element).val().length > 0) {
			$label.addClass('focus');
			jQuery(this).addClass( "focus" );
		} else {
			$label.removeClass('focus');
			jQuery(this).removeClass( "focus" );
		}
		/* user login then show the label at top */
		if (jQuery('.custom-input').val().length > 0) {
			jQuery(".apt-main-left label.custom").addClass('focus'); 
		}else{
			jQuery('.label.custom').removeClass('focus');
		}
		/* user login then show the label at top */
		if (jQuery('#apt-front-phone').val().length > 0) {
			jQuery("label.apt-phone-label").addClass('focus'); 
		}else{
			jQuery('#apt-front-phone').removeClass('focus');
		}
		
	}	
	
	/* The lines below are executed on page load */
	jQuery('.custom-input').each(function() {
		checkForInput(this);	
		if (jQuery(this).val().length > 0) {
			jQuery(this).addClass('focus'); 
		}else{
			jQuery(this).removeClass('focus'); 
		}
		
	});

	 /* The lines below (inside) are executed on change & keyup */
	jQuery('.custom-input').on('change keyup', function() {
		checkForInput(this);  
		jQuery(this).addClass( "focus" );	
	});
});