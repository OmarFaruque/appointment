/********************************/
/*****  header jquery  **********/
	/* Hide Success Message After 3 Seconds */
	function appointment_hide_success_msg(){
		setTimeout(function(){ jQuery('.mainheader_message_inner').hide(); }, 3000);
	}
		
	/*  scroll to active class service, location, setting  */
	jQuery(document).ready(function () {
		jQuery('.top-company-details, .top-general-setting, .top-appearance-setting, .top-payment-setting, .top-email-setting, .top-email-template, .top-sms-reminder, .top-sms-template, .top-custom-formfield, .top-promocode').on('click', function (e) {
			jQuery('html, body').stop().animate({
				'scrollTop': jQuery('.apt-setting-details').offset().top - 115
			}, 500, 'swing', function () {
			});
		});
	});
	
	/* menu auto hide in mobile when open a menu */
	 jQuery(document).on('click','.navbar-collapse.in',function(e) {
		if( jQuery(e.target).is('a') ) {
			jQuery(this).collapse('hide');
		}
	});
	
	jQuery(document).ready(function() {		
		var appointment_twilio_ccode_alph = header_object.appointment_twilio_ccode_alph;
		jQuery("#appointment_twilio_admin_phone_no").intlTelInput({initialCountry:appointment_twilio_ccode_alph});	
		jQuery(document).on("click",'.appointment_twillio_cd .country',function(){
			var ccode = jQuery(this).data('dial-code');
			var ccode_aplh = jQuery(this).data('country-code');
			jQuery('#appointment_twilio_ccode').val('+'+ccode);
			jQuery('#appointment_twilio_ccode_alph').val(ccode_aplh);
			var codeval = jQuery("#appointment_twilio_admin_phone_no").val();
			jQuery("#appointment_twilio_admin_phone_no").val(codeval.replace('+'+ccode,''));
		});
		
		
		
	});
	
	jQuery(document).ready(function() {
		var appointment_plivo_ccode_alph = header_object.appointment_plivo_ccode_alph;
		jQuery("#appointment_plivo_admin_phone_no").intlTelInput({initialCountry:appointment_plivo_ccode_alph});
		jQuery(document).on("click",'.appointment_plivo_cd .country',function(){
			var ccode = jQuery(this).data('dial-code');
			var ccode_aplh = jQuery(this).data('country-code');
			jQuery('#appointment_plivo_ccode').val('+'+ccode);
			jQuery('#appointment_plivo_ccode_alph').val(ccode_aplh);
			var codeval = jQuery("#appointment_plivo_admin_phone_no").val();
			jQuery("#appointment_plivo_admin_phone_no").val(codeval.replace('+'+ccode,''));
		});	
		
		var appointment_textlocal_ccode_alph = header_object.appointment_textlocal_ccode_alph;
		jQuery("#appointment_textlocal_admin_phone_no").intlTelInput({initialCountry:appointment_textlocal_ccode_alph});
		jQuery(document).on("click",'.appointment_textlocal_cd .country',function(){
			var ccode = jQuery(this).data('dial-code');
			var ccode_aplh = jQuery(this).data('country-code');
			jQuery('#appointment_textlocal_ccode').val('+'+ccode);
			jQuery('#appointment_textlocal_ccode_alph').val(ccode_aplh);
			var codeval = jQuery("#appointment_textlocal_admin_phone_no").val();
			jQuery("#appointment_textlocal_admin_phone_no").val(codeval.replace('+'+ccode,''));
		});	
		
	});
	jQuery(document).ready(function() {
		var appointment_nexmo_ccode_alph = header_object.appointment_nexmo_ccode_alph;
		jQuery("#appointment_nexmo_admin_phone_no").intlTelInput({initialCountry:appointment_nexmo_ccode_alph});
		jQuery(document).on("click",'.appointment_nexmo_cd .country',function(){
			var ccode = jQuery(this).data('dial-code');
			var ccode_aplh = jQuery(this).data('country-code');
			jQuery('#appointment_nexmo_ccode').val('+'+ccode);
			jQuery('#appointment_nexmo_ccode_alph').val(ccode_aplh);
			var codeval = jQuery("#appointment_nexmo_admin_phone_no").val();
			jQuery("#appointment_nexmo_admin_phone_no").val(codeval.replace('+'+ccode,''));
		});
	});
	jQuery(document).ready(function() {
		var appointment_textlocal_ccode_alph = header_object.appointment_textlocal_ccode_alph;
		jQuery("#appointment_textlocal_admin_phone_no").intlTelInput({initialCountry:appointment_textlocal_ccode_alph});
		jQuery(document).on("click",'.appointment_nexmo_cd .country',function(){
			var ccode = jQuery(this).data('dial-code');
			var ccode_aplh = jQuery(this).data('country-code');
			jQuery('#appointment_nexmo_ccode').val('+'+ccode);
			jQuery('#appointment_textlocal_ccode_alph').val(ccode_aplh);
			var codeval = jQuery("#appointment_textlocal_admin_phone_no").val();
			jQuery("#appointment_textlocal_admin_phone_no").val(codeval.replace('+'+ccode,''));
		});
	});
	
	jQuery(document).on("click",'.country',function(){
	var num_code=jQuery(this).data("dial-code");
	var country_code=jQuery(this).data("country-code");
	/* jQuery("#appointment_twilio_admin_phone_no").val('+'+num_code);
	jQuery("#appointment_plivo_admin_phone_no").val('+'+num_code);
	jQuery("#appointment_nexmo_admin_phone_no").val('+'+num_code); */
	jQuery(".company_country_code_value").html('');
	jQuery(".company_country_code_value").html('+'+num_code);
	jQuery(".company_country_code_value").html('+'+num_code);
	jQuery(".default_company_country_flag").val(country_code);
	
	});
	
	
	/* manage form fields min max counting */
	jQuery(function () {
		jQuery('.add').on('click',function(){
			var $qty=$(this).closest('.apt-min-max').find('.qty');
			var currentVal = parseInt($qty.val());
			if (!isNaN(currentVal)) {
				$qty.val(currentVal + 1);
			}
		});
		jQuery('.minus').on('click',function(){
			var $qty=$(this).closest('.apt-min-max').find('.qty');
			var currentVal = parseInt($qty.val());
			if (!isNaN(currentVal) && currentVal > 0) {
				$qty.val(currentVal - 1);
			}
		});
	});
	
	/* delete appointment in modal window */
	jQuery(document).bind('ready ajaxComplete', function(){
	  jQuery('#apt-delete-appointment').popover({ 
		html : true,
		content: function() {
		  return jQuery('#popover-delete-appointment').html();
		}
	  });
	});
	/* hide delete appointment in modal window */
	jQuery(document).on('click', '#apt-close-del-appointment', function(){			
		jQuery('.popover').fadeOut();
	});
	/* customer phone number in popup */
	jQuery(document).ready(function() {
		jQuery("#apt_client_phone").intlTelInput({
			utilsScript: "utils.js"
		}); 
		jQuery("#apt_clientphone_manual").intlTelInput({
			utilsScript: "utils.js"
		}); 
	});
	
	
	/* reject appointment reason popover */
jQuery(document).bind('ready ajaxComplete', function(){
	  jQuery('#apt-reject-appointment').popover({ 
		html : true,
		content: function() {
		  return jQuery('#popover-reject-appointment').html();
		}
	  });
	});
	/* hide add new service popover */
	jQuery(document).on('click', '#apt-close-reject-appointment', function(){			
		jQuery('.popover').fadeOut();
	});

	
	jQuery(document).on('click', '#apt-close-popover-delete-staff', function(){			
		jQuery('.popover').fadeOut();
	});
	
	



	
	

	
	/***************************************/
jQuery(document).bind('ready ajaxComplete', function(){
	  jQuery('#edit-apt-reject-appointment').popover({ 
		html : true,
		content: function() {
		  return jQuery('#edit-popover-reject-appointment').html();
		}
	  });
	});
	/* hide reject appointment reason popover */
	jQuery(document).on('click', '#edit-apt-close-reject-appointment', function(){			
		jQuery('.popover').fadeOut();
	});
	
	/* delete appointment in modal window */
jQuery(document).bind('ready ajaxComplete', function(){
	  jQuery('#edit-apt-delete-appointment').popover({ 
		html : true,
		content: function() {
		  return jQuery('#edit-popover-delete-appointment').html();
		}
	  });
	});
	/* hide delete appointment in modal window */
	jQuery(document).on('click', '#edit-apt-close-del-appointment', function(){			
		jQuery('.popover').fadeOut();
	});


/******* LOCATIONS list *******/

	/* add new location button and popover */
jQuery(document).bind('ready ajaxComplete', function(){
	  jQuery('#apt-add-new-city-state').popover({ 
		html : true,
		content: function() {
		  return jQuery('#popover-content-wrapper').html();
		}
	  });
	});
	/* hide location popover */
	jQuery(document).on('click', '#apt-close-popover-city-state', function(){			
		jQuery('.popover').fadeOut();
	});
jQuery(document).bind('ready ajaxComplete', function(){
	  jQuery('#apt-delete-location-city-state').popover({ 
		html : true,
		content: function() {
		  return jQuery('#popover-delete-location-city-state').html();
		}
	  });
	});
	/* hide delete  location popover */
	jQuery(document).on('click', '#apt-close-popover-location-city-state', function(){			
		jQuery('.popover').fadeOut();
	});
	
	jQuery(document).on('click', '#apt-add-new-location', function(){	
		jQuery( ".apt-show-hide-checkbox" ).trigger( "click" );	
		jQuery('.apt-add-new-location').fadeIn();
		jQuery('html, body').stop().animate({
		'scrollTop': jQuery('.apt-add-new-location').offset().top - 35
		}, 2000, 'swing', function () {});
		
	});
	
	jQuery(function() {
		jQuery( "#sortable-locations" ).sortable({ handle: '.fa-th-list' });
	}); 	
	
	jQuery(document).on('click', '#apt-close-popover-delete-location', function(){			
		jQuery('.popover').fadeOut();
	});
	
	
	/* locations toggle details */
	//jQuery(document).ajaxComplete(function() {
		jQuery(document).on('change','.apt-show-hide-checkbox',function() {
			 var toggle_id = jQuery(this).attr('id');
			 var sms_toggle_id = jQuery(this).data('id');
			 
			 jQuery('.service_detail').each(function(){
				if(!jQuery(this).hasClass('detail-id_'+toggle_id)){
					var service_edid = jQuery(this).attr('class').split('detail-id_');
					jQuery('#'+service_edid[1]).prop('checked',false);
					jQuery(this).fadeOut('slow');
				}
			 });
			  jQuery('.location_detail').each(function(){
				if(!jQuery(this).hasClass('detail-id_'+toggle_id)){				
					var location_edid = jQuery(this).attr('class').split('detail-id_');
					jQuery('#'+location_edid[1]).prop('checked',false);
					jQuery(this).fadeOut('slow');
				}
			 });
			  jQuery('.ssp_detail').each(function(){
				if(!jQuery(this).hasClass('detail-id_'+toggle_id)){				
					var location_edid = jQuery(this).attr('class').split('detail-id_');
					jQuery('#'+location_edid[1]).prop('checked',false);
					jQuery(this).fadeOut('slow');
				}
			 });
			 jQuery('.emailtemplatedetail').each(function(){
				if(!jQuery(this).hasClass('emaildetail_'+toggle_id)){
					var email_edid = jQuery(this).attr('class').split('emaildetail_');					
					jQuery('#'+email_edid[1]).prop('checked',false);
					jQuery(this).fadeOut('slow');
				}
			 });
			
			 jQuery('.smstemplatedetail').each(function(){
				if(!jQuery(this).hasClass('smsdetail_'+sms_toggle_id)){
					var sms_edid = jQuery(this).attr('class').split('smsdetail_');
					jQuery('#sms'+sms_edid[1]).prop('checked',false);
					jQuery(this).fadeOut('slow');
				}
			 });
			
				
			 jQuery('.emaildetail_'+toggle_id).toggle("blind", {direction: "vertical"}, 1000);
			 jQuery('.smsdetail_'+sms_toggle_id).toggle("blind", {direction: "vertical"}, 1000);
			 jQuery('.detail-id_'+toggle_id).toggle('slow');
		});
	//});
	/* location phone number 
	jQuery(document).ready(function() {
		jQuery("#location-phone-number").intlTelInput({
			utilsScript: "/utils.js"
		}); 
	});*/
	/* google map */
	 // This example adds a search box to a map, using the Google Place Autocomplete
      // feature. People can enter geographical searches. The search box will return a
      // pick list containing a mix of places and predicted search terms.

      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

      function initAutocomplete() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: -33.8688, lng: 151.2195},
          zoom: 13,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }

          // Clear out the old markers.
          markers.forEach(function(marker) {
            marker.setMap(null);
          });
          markers = [];

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {
            var icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
              map: map,
              icon: icon,
              title: place.name,
              position: place.geometry.location
            }));

            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }
          });
          map.fitBounds(bounds);
        });
      }

	/* color tag for service*/
jQuery(document).bind('ready ajaxComplete', function(){
            jQuery('.demo').each( function() {
                jQuery(this).minicolors({
                    control: jQuery(this).attr('data-control') || 'hue',
                    defaultValue: jQuery(this).attr('data-defaultValue') || '',
                    format: jQuery(this).attr('data-format') || 'hex',
                    keywords: jQuery(this).attr('data-keywords') || '',
                    inline: jQuery(this).attr('data-inline') === 'true',
                    letterCase: jQuery(this).attr('data-letterCase') || 'lowercase',
                    opacity: jQuery(this).attr('data-opacity'),
                    position: jQuery(this).attr('data-position') || 'bottom left',
                    change: function(value, opacity) {
                        if( !value ) return;
                        if( opacity ) value += ', ' + opacity;
                        if( typeof console === 'object' ) {
                            console.log(value);
                        }
                    },
                    theme: 'bootstrap'
                });

            });

        });

jQuery(document).bind('ready ajaxComplete', function(){
		jQuery('.selectpicker').selectpicker({
		    container: 'body'
	   });

		if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
			jQuery('.selectpicker').selectpicker('mobile');
		}
	});
	
jQuery(document).bind('ready ajaxComplete', function(){
    jQuery('.apt-show-hide-checkbox').change(function() {
        var toggle_id = jQuery(this).attr('id');
        jQuery('.mycollapse_'+toggle_id).toggle('slow');
	});

});
	
	

	
/**********************************/
/******   settings > General settings  *************/

  /* for toggle medium enable disable collapse */
jQuery(document).bind('ready', function(){
    jQuery('.apt-toggle-sh').change(function() {
		var toggle_id = jQuery(this).attr('id');
        jQuery('.collapse_'+toggle_id).slideToggle();
	});
	/************** Partial Deposit ****************/	
	jQuery('.apt-toggle-pd').change(function() {
		var toggle_id = jQuery(this).attr('id');
		if(toggle_id == 'appointment_partial_deposit_status'){
			var payment_gateway_st = general_setting_pd_ed.payment_gateway_status;
			if(payment_gateway_st=='D' ){
				jQuery('#apt_partial_depost_error').show();
				jQuery(this).attr('checked',false);
				jQuery(this).parent().prop('className','apt-toggle-pd toggle btn btn-danger btn-sm off');
			}else{
				jQuery('.collapse_'+toggle_id).toggle( "blind", {direction: "vertical"}, 1000 );
			}
        }else{
			jQuery('.collapse_'+toggle_id).toggle( "blind", {direction: "vertical"}, 1000 );
		}
	});
});

/*  phone number  */
jQuery(document).ready(function () {
  
	jQuery("#company_country_code").intlTelInput({
	numberType: "polite",
	autoPlaceholder: "off",
	utilsScript: "utils.js"
	});
	
});

	
jQuery(document).bind('ready', function(){
    jQuery('input[name="appointment_taxvat_type"]').click(function(){
        if(jQuery(this).attr("value")=="P"){
           jQuery(".apt-tax-percent").show();
         }
        if(jQuery(this).attr("value")=="F"){
           jQuery(".apt-tax-percent").hide();           
        }
        
		});
	 jQuery('input[name="appointment_partial_deposit_type"]').click(function(){
        if(jQuery(this).attr("value")=="P"){
           jQuery(".apt-partial-deposit-percent").show();
        }
        if(jQuery(this).attr("value")=="F"){
           jQuery(".apt-partial-deposit-percent").hide();
        }
        
		});	
		
	});
	jQuery(document).bind('ready ajaxComplete', function(){
		jQuery('[data-toggle="tooltip"]').tooltip({
			placement: 'bottom',
			trigger: 'hover'
		});
		jQuery('.apt-tooltip-link').each(function(){
			jQuery(this).attr('href','javascript:void(0)');
		});
	
	});
	 
	jQuery(document).ready(function () { 
		if (jQuery("#apt").width() >= 768 && jQuery("#apt").width() < 1024){
			jQuery('[data-toggle="tooltip"]').tooltip({'placement': 'left'});
		}	
		
	});   

	
	
	/******   settings > discount coupons  *************/
	
	/* delete promocode popover */
jQuery(document).bind('ready ajaxComplete', function(){
	  jQuery('#apt-delete-promocode').popover({ 
		html : true,
		content: function() {
		  return jQuery('#popover-delete-promocode').html();
		}
	  });
	});
	/* hide delete promocode popover */
	jQuery(document).on('click', '#apt-close-popover-delete-promocode', function(){			
		jQuery('.popover').fadeOut();
	});
	
	jQuery(document).on('click', '.apt-edit-coupon', function(){		
		jQuery('.apt-update-promocode').css('display','block');
	});
	
	
	/**********************************/
	/******   payments  page   *************/
		
	/* data table for export data with excel, csv, pdf */
jQuery(document).ready(function(){
		jQuery('#payments-details').DataTable( {
			dom: 'lfrtipB',
			
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5'
			]
		});
	});
jQuery(document).bind('ready ajaxComplete', function(){
		
		/* Hide Past Dates on Coupon Expiry datepicker */
		jQuery(".apt_coupon_expiry").datepicker({
                minDate: 0
        });
		jQuery("#apt_booking_datetime").datepicker({
                minDate: 0
        });
		
		jQuery('#apt-promocode-list').DataTable();
		responsive: true
	} );
	
	/**********************************/

	
	/* data table for export data with excel, csv, pdf */
	
	/* staff member information export details */
jQuery(document).ready(function(){
		jQuery('#staff-info-table').DataTable( {
			dom: 'lfrtipB',
			
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5'
			]
		});
	/* services information export details */
		jQuery('#services-info-table').DataTable( {
			dom: 'lfrtipB',
			
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5'
			]
		});
	/* category information export details  */	
		jQuery('#category-info-table').DataTable( {
			dom: 'lfrtipB',
			
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5'
			]
		});
	/* registered customers booking details page */
		jQuery('#registered-client-booking-details').DataTable( {
			dom: 'frtipB',
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5'
			]
		});
	/* registered customers listing page */
		jQuery('#registered-client-table').DataTable( {
			dom: 'lfrtipB',
			
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5'
			]
		});

	/* guest customers listing page */
		jQuery('#guest-client-table').DataTable( {
			dom: 'lfrtipB',
			
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5'
			]
		});
	/* guest customers booking details page */

		jQuery('#guest-client-booking-details').DataTable( {
			dom: 'frtipB',
			
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5'
			]
		});
	/* Location Data Table in Export */	
		jQuery('#location-info-table').DataTable( {
			dom: 'frtipB',
			
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5'
			]
		});
		
		/* reviews all table */	
		jQuery('#apt-published-reviews-table').DataTable( {
			dom: 'lfrtipB',
			
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5'
			]
		});
		jQuery('#apt-pending-reviews-table').DataTable( {
			dom: 'lfrtipB',
			
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5'
			]
		});
		jQuery('#apt-hidden-reviews-table').DataTable( {
			dom: 'lfrtipB',
			
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5'
			]
		});
		
		
		
	});
	
	/* dashboard chart */
	var doughnutData = [
		{
			value: 80,
			color:"#F7464A",
			highlight: "#FF5A5E",
			label: "<b>dsfkljfkdskfd<br />asaklfsdklfsdakl</b>"
		},
		{
			value: 50,
			color: "#46BFBD",
			highlight: "#5AD3D1",
			label: "Green"
		},
		{
			value: 100,
			color: "#FDB45C",
			highlight: "#FFC870",
			label: "Yellow"
		},
		{
			value: 40,
			color: "#949FB1",
			highlight: "#A8B3C5",
			label: "Grey"
		},
		{
			value: 120,
			color: "#4D5360",
			highlight: "#616774",
			label: "Dark Grey"
		}

	];

	/* Dashboard today and latest activity popup */
	jQuery(document).on('click','.apt-today-list',function(){
		jQuery('.modal').css('background','rgba(0,0,0,0.1)');
		
    });
	jQuery(document).on('click','.apt-activity-list',function(){
        jQuery('.modal').css('background','rgba(0,0,0,0.1)');
    });
	
	
	
	
/* custom form fields drag and drop */	
		
	/* jQuery(document).bind('ready ajaxComplete', function($){
		'use strict';
		var template = document.getElementById('form-builder-template'),
		  formContainer = document.getElementById('rendered-form'),
		  renderBtn = document.getElementById('render-form-button');
		jQuery(template).formBuilder();

		jQuery(renderBtn).click(function(e) {
		  e.preventDefault();
		  jQuery(template).formRender({
			container: jQuery(formContainer)
		  });
		});
	  });
	  */
	  
  
jQuery(document).ready(function($) {
  var buildWrap = document.querySelector('.build-wrap'),
    renderWrap = document.querySelector('.render-wrap'),
    editBtn = document.getElementById('edit-form'),
    formData = window.sessionStorage.getItem('formData'),	
	savedformdata = header_object.appointment_custom_formfields_val,
    editing = true,
    fbOptions = {
      dataType: 'json',
	  controlOrder: [
		'autocomplete',
		'button',
		'checkbox',
		'checkbox-group',
		'date',
		'file',
		'header',
		'hidden',
		'paragraph',
		'number',
		'radio-group',
		'select',
		'text',
		'textarea'
  ],
  disableFields: ['autocomplete','file','header','paragraph','button','hidden','date']
    };

  if(savedformdata!=''){
		fbOptions.formData = '['+savedformdata+']';
   }

  var toggleEdit = function() {
    document.body.classList.toggle('form-rendered', editing);
    editing = !editing;
  };

  var formBuilder = $(buildWrap).formBuilder(fbOptions).data('formBuilder');

  $('.form-builder-save').click(function() {
	   

	    var formdata = formBuilder.formData;
	    var ajax_url = general_settings_ajax_path.ajax_path;
	    var bwid = jQuery('input[name="bwid"]').val();
		var postdata = { formdata:formdata,bwid:bwid,general_ajax_action:'save_custom_form' }
		  jQuery.ajax({					
						url  : ajax_url+"/assets/lib/admin_general_ajax.php",					
						type : 'POST',					
						data : postdata,					
						dataType : 'html',					
						success  : function(response) {	
						},
						error: function (xhr, ajaxOptions, thrownError) {
						}
			});	
	  
    toggleEdit();
    $(renderWrap).formRender({
      dataType: 'json',
      formData: formBuilder.formData
    });
	
    window.sessionStorage.setItem('formData', JSON.stringify(formBuilder.formData));
  });

  /* editBtn.onclick = function() {
    toggleEdit();
  }; */
});	  
	  
/* close form-builder */	
	
	  
	  
/* image upload in services */	  
// convert bytes into friendly format
function bytesToSize(bytes) {
    var sizes = ['Bytes', 'KB', 'MB'];
    if (bytes == 0) return 'n/a';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
};

// check for selected crop region
function checkForm() {
    if (parseInt(jQuery('#w').val())) return true;
    jQuery('.error').html('Please select a crop region and then press Upload').show();
    return false;
};


// clear info by cropping (onRelease event handler)
function clearInfo() {
    jQuery('.info #w').val('');
    jQuery('.info #h').val('');
};

// Create variables (in this scope) to hold the Jcrop API and image size
var jcrop_api, boundx, boundy;


jQuery(document).on('change','.apt-upload-images',function(){
//function fileSelectHandler(uploadsection) {
	

	var uploadsection=jQuery(this).attr('id');
	var bdus = jQuery(this).data('us');
	var oFile = jQuery('#'+uploadsection)[0].files[0];
	jQuery('#'+bdus+'bdimagename').val(oFile.name);

	
	// check for image type (jpg and png are allowed)
    var rFilter = /^(image\/jpeg|image\/png)$/i;
    if (! rFilter.test(oFile.type)) {
        jQuery('.error').html('Please select a valid image file (jpg and png are allowed)').show();
        return;
    }

    /* check for file size
    if (oFile.size > 2500 * 5000) {  //if (oFile.size > 250 * 1024) {
        jQuery('.error').html('You have selected too big file, please select a one smaller image file').show();
        return;
    }*/

    // preview element
    var oImage = document.getElementById('apt-preview-img'+bdus);

    // prepare HTML5 FileReader
    var oReader = new FileReader();
        oReader.onload = function(e) {
        	
        // e.target.result contains the DataURL which we can use as a source of the image
        oImage.src = e.target.result;

        oImage.onload = function () { // onload event handler
        	
			// show image popup for image crop
			jQuery('#apt-image-upload-popup'+bdus).modal();

			
			
			/* display some basic image info*/
			var sResultFileSize = bytesToSize(oFile.size);
			jQuery('#'+bdus+'filesize').val(sResultFileSize);
			jQuery('#'+bdus+'bdimagetype').val(oFile.type);
			//jQuery('#filedim').val(oImage.naturalWidth + ' x ' + oImage.naturalHeight);

			// destroy Jcrop if it is existed
			if (typeof jcrop_api != 'undefined') {
				jcrop_api.destroy();
				jcrop_api = null;
				jQuery('#apt-preview-img'+bdus).width(oImage.naturalWidth);
				jQuery('#apt-preview-img'+bdus).height(oImage.naturalHeight);
			}

			setTimeout(function(){
				jQuery('#'+bdus+'w').val(oImage.naturalWidth);
				jQuery('#'+bdus+'h').val(oImage.naturalHeight);
				// initialize Jcrop
				jQuery('#apt-preview-img'+bdus).Jcrop({
					minSize: [32, 32], // min crop size
					/* aspectRatio : 1, */ // keep aspect ratio 1:1
					bgFade: true, // use fade effect
					bgOpacity: .3, // fade opacity
					//maxSize: [200, 200],
					boxWidth: 575,   //Maximum width you want for your bigger images
					boxHeight: 400,
					onChange: function(e){  jQuery('#'+bdus+'x1').val(e.x);
											jQuery('#'+bdus+'y1').val(e.y);
											jQuery('#'+bdus+'x2').val(e.x2);
											jQuery('#'+bdus+'y2').val(e.y2);
											jQuery('#'+bdus+'w').val(e.w);
											jQuery('#'+bdus+'h').val(e.h);} ,
					onSelect: function(e){ jQuery('#'+bdus+'x1').val(e.x);
											jQuery('#'+bdus+'y1').val(e.y);
											jQuery('#'+bdus+'x2').val(e.x2);
											jQuery('#'+bdus+'y2').val(e.y2);
											jQuery('#'+bdus+'w').val(e.w);
											jQuery('#'+bdus+'h').val(e.h);},
					onRelease: clearInfo
				}, function(){

					// use the Jcrop API to get the real image size
					var bounds = this.getBounds();
					boundx = bounds[0];
					boundy = bounds[1];

					// Store the Jcrop API in the jcrop_api variable
					jcrop_api = this;
				});
			},500);

        };
    };

    // read selected file as DataURL
    oReader.readAsDataURL(oFile);
});




	

	/* delete member image popover */
jQuery(document).bind('ready ajaxComplete', function(){
	  jQuery('#apt-remove-member-image').popover({ 
		html : true,
		content: function() {
		  return jQuery('#popover-apt-remove-member-image').html();
		}
	  });
	});
	/* hide delete member image popover */
	jQuery(document).on('click', '#apt-close-popover-member-image', function(){			
		jQuery('.popover').fadeOut();
	});
	
	/* delete customer image popover */
jQuery(document).bind('ready ajaxComplete', function(){
	  jQuery('#apt-remove-customer-image').popover({ 
		html : true,
		content: function() {
		  return jQuery('#popover-apt-remove-customer-image').html();
		}
	  });
	});
	/* hide delete customer image popover */
	jQuery(document).on('click', '#apt-close-popover-customer-image', function(){			
		jQuery('.popover').fadeOut();
	});
	
	/* delete new customer image popover */
jQuery(document).bind('ready ajaxComplete', function(){
	  jQuery('#apt-remove-new-customer-image').popover({ 
		html : true,
		content: function() {
		  return jQuery('#popover-apt-remove-new-customer-image').html();
		}
	  });
	});
	/* hide delete new customer image popover */
	jQuery(document).on('click', '#apt-close-popover-new-customer-image', function(){			
		jQuery('.popover').fadeOut();
	});
	
/* delete company logo popover */
jQuery(document).bind('ready ajaxComplete', function(){
	  jQuery('#apt-remove-company-logo').popover({ 
		html : true,
		content: function() {
		  return jQuery('#popover-apt-remove-company-logo').html();
		}
	  });
	});
	/* hide delete company logo popover */
	jQuery(document).on('click', '#apt-close-popover-company-logo', function(){			
		jQuery('.popover').fadeOut();
	});
	
	/* user new and existing radio show hide fields */
	jQuery(document).on('click', '.apt-existing-usercl', function(){		
		jQuery('.apt-existing-user-data').show('slow');
		jQuery('.apt-new-user-data').hide('slow');
		jQuery('#apt-staff-fullname-error').hide();
		jQuery('#apt-staff-email-error').hide();
		jQuery('#apt-staff-username-error').hide();
		jQuery('#apt-staff-password-error').hide();
	});
	jQuery(document).on('click', '.apt-new-usercl', function(){		
		jQuery('.apt-new-user-data').show('slow');
		jQuery('.apt-existing-user-data').hide('slow');
		jQuery('#apt-selected-wp-user-error').hide();

	});	  
		
jQuery("#apt-select-service :checkbox").on('click', function(){
		jQuery(this).closest('li').toggleClass("apt-checked");
});

	
	
/*	Admin Area Functionality Js Code Start */	 
jQuery(document).on('ready', function(){
		function cb(start, end) {
			jQuery('#apt_reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
		}
		cb(moment().subtract(29, 'days'), moment());

		jQuery('#apt_reportrange').daterangepicker({
			ranges: {
			   'Today': [moment(), moment()],
			   'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			   'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			   'Last 30 Days': [moment().subtract(29, 'days'), moment()],
			   'This Month': [moment().startOf('month'), moment().endOf('month')],
			   'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			}
		}, cb);
});

/*** All Staff Linking ON/OFF Code ***/
jQuery(document).on('change','.linkallstaff',function(){
	var service_id = jQuery(this).data('service_id');
	if(jQuery(this).is(":checked")){
			jQuery('.apt_all_staff'+service_id).each(function(){
			jQuery(this).prop('checked', true);			    
			jQuery(this).parent().prop('className','toggle btn btn-default on');
		}); 
	}else{
		var serv_id=jQuery(this).data('service_id');/*service id*/	
		jQuery('.apt_all_staff'+service_id).each(function(){
			jQuery(this).prop('checked', false);
			jQuery(this).parent().prop('className','toggle btn btn-default off');
		});
	}
});
/*** All Service Linking ON/OFF Code on Staff Service Schedule Price***/
jQuery(document).on('change','.linkallservices',function(){
	var staff_id = jQuery(this).data('staff_id');
	if(jQuery(this).is(":checked")){
			jQuery('.apt_all_service'+staff_id).each(function(){
			jQuery(this).prop('checked', true);
			jQuery(this).parent().prop('className','toggle btn btn-default on');
		}); 
	}else{
		var staff_id=jQuery(this).data('staff_id');/*service id*/	
		jQuery('.apt_all_service'+staff_id).each(function(){
			jQuery(this).prop('checked', false);
			jQuery(this).parent().prop('className','toggle btn btn-default off');
		});
	}
});


/** Add ShortTag into Email Template On Click of TagName **/
function insertAtCaret(areaId,text) {
    var txtarea = document.getElementById(areaId);
    var scrollPos = txtarea.scrollTop;
    var strPos = 0;
    var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ? 
        "ff" : (document.selection ? "ie" : false ) );
    if (br == "ie") { 
        txtarea.focus();
        var range = document.selection.createRange();
        range.moveStart ('character', -txtarea.value.length);
        strPos = range.text.length;
    }
    else if (br == "ff") strPos = txtarea.selectionStart;
    var front = (txtarea.value).substring(0,strPos);  
    var back = (txtarea.value).substring(strPos,txtarea.value.length); 
    txtarea.value=front+text+back;
    strPos = strPos + text.length;
    if (br == "ie") { 
        txtarea.focus();
        var range = document.selection.createRange();
        range.moveStart ('character', -txtarea.value.length);
        range.moveStart ('character', strPos);
        range.moveEnd ('character', 0);
        range.select();
    }
    else if (br == "ff") {
        txtarea.selectionStart = strPos;
        txtarea.selectionEnd = strPos;
        txtarea.focus();
    }
    txtarea.scrollTop = scrollPos;
}
jQuery(document).on('click','.tags',function(){
	var tag = jQuery(this).data('value');
	var email_id = jQuery(this).data('eid');
	insertAtCaret('email_editor'+email_id,tag);
	insertAtCaret('sms_editor'+email_id,tag);
	return false;
});


/***********************Location Jquery**********************/
/* On click Create Location Validate Form */
jQuery(document).on('click','#apt_create_location',function(){
		if(jQuery('#apt_create_location_cl').valid()){
			jQuery('#apt_create_location_cl').submit();
		}				
});						
						
/* Get Location By City/State */
jQuery(document).on('click','.getsorted_locations',function(){
		var ajax_url = locationObj.plugin_path;
		var sortingvalue = jQuery(this).data('location_sortby');
		var bwid = jQuery('input[name="bwid"]').val();
		var postdata = { sortingvalue:sortingvalue,
						 bwid:bwid,
						 location_action:'sortbylocations'						 
		}
		jQuery('.apt-loading-main').show();
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/location_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {	
							jQuery('.apt-loading-main').hide();
							jQuery('#sortable-locations').html(response);
							jQuery('.mainheader_message_inner').show();
							appointment_hide_success_msg();
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
		});	
		
});

/* Update Location Status */	
jQuery(document).on('change','.update_location_status',function(){
		var ajax_url = locationObj.plugin_path;
		var location_id = jQuery(this).data('id');
		if(jQuery(this).is(':checked')){
			var location_status = 'E';
		}else{
			var location_status =  'D';
		}
		jQuery('.apt-loading-main').show();
		var postdata = { location_id:location_id,
						 location_status:location_status,
						 location_action:'updatelocationstatus'						 
		}
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/location_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
					jQuery('.apt-loading-main').hide();
					jQuery('.mainheader_message_inner').show();
					appointment_hide_success_msg();
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
		});
});  

/* Delete Location Permanently */	
jQuery(document).on('click','.delete_location',function(){
		var ajax_url = locationObj.plugin_path;
		var location_id = jQuery(this).data('id');
		jQuery('.apt-loading-main').show();
		var postdata = { location_id:location_id,
						 location_action:'delete_location'						 
		}
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/location_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-locations-container').html(response);
						jQuery('.apt-loading-main').hide();
						jQuery('#location_detail_'+location_id).fadeOut('slow');
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
						
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
		});
});  

/* Update Location Detail */	
jQuery(document).on('click','.update_location',function(){
		var ajax_url = locationObj.plugin_path;
		var location_id = jQuery(this).data('location_id');
		if(!jQuery("#apt_update_location_"+location_id).valid()){
				return false;
		}		
		
		var location_title = jQuery('#apt-location-name'+location_id).val();
		var description = jQuery('#apt-location-desc'+location_id).val();
		var image = jQuery('#bdll'+location_id+'uploadedimg').val();
		var email = jQuery('#location-email'+location_id).val();
		var phone = jQuery('#location-phone-number'+location_id).val();
		var address = jQuery('#apt-location-address'+location_id).val();
		var city = jQuery('#apt-location-city'+location_id).val();
		var state = jQuery('#apt-location-state'+location_id).val();
		var zip = jQuery('#apt-location-zip'+location_id).val();
		var country = jQuery('#apt-location-country'+location_id).val();
		jQuery('.apt-loading-main').show();
		var postdata = { location_id:location_id,
						 location_title:location_title,						 
						 description:description,						 
						 image:image,						 
						 email:email,						 
						 phone:phone,						 
						 address:address,						 
						 city:city,						 
						 state:state,						 
						 zip:zip,						 
						 country:country,						 
						 location_action:'update_location'						 
		}
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/location_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();
						jQuery('.apt-locations-container').html(response);
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
						location.reload();
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
		});
}); 

/* Upload Create Location Image */
	jQuery(document).on("click",".apt_upload_img",function(e) {
		jQuery('.apt-loading-main').show();
		var ajax_url = header_object.plugin_path;
		var file_data =jQuery("#"+jQuery(this).data('imageinputid')).prop("files")[0];
		var formdata = new FormData();
		var bdus = jQuery(this).data('us');
		var img_w = jQuery('#'+bdus+'w').val();
		var img_h = jQuery('#'+bdus+'h').val();
		var img_x1 = jQuery('#'+bdus+'x1').val();
		var img_x2 = jQuery('#'+bdus+'x2').val();
		var img_y1 = jQuery('#'+bdus+'y1').val();
		var img_y2 = jQuery('#'+bdus+'y2').val();
		formdata.append("image",file_data);
		formdata.append("w",img_w);
		formdata.append("h",img_h);
		formdata.append("x1",img_x1);
		formdata.append("x2",img_x2);
		formdata.append("y1",img_y1);
		formdata.append("y2",img_y2);

		var siteurl = ajax_url.split('wp-content');

			jQuery.ajax({
				url: ajax_url+"/assets/lib/upload.php", 
				type: "POST",           
				data:formdata, 
                cache: false,
                contentType: false, 
				processData:false,        
				success: function(data){
					jQuery('.apt-loading-main').hide();
					jQuery('#'+bdus+'uploadedimg').val(data);
					jQuery('.hidemodal').trigger('click');
					jQuery('#'+bdus+'locimage').attr('src',siteurl[0]+'wp-content/uploads'+data);
					jQuery('#'+bdus+'addimage').attr('src',siteurl[0]+'wp-content/uploads'+data);
				}
			});
		});
		
/* Sort Location Position  */
jQuery(document).ready(function(){		
			jQuery("#sortable-locations").sortable({
				update : function(event,ui){
				var ajax_url = locationObj.plugin_path;
				var position = jQuery(this).sortable('serialize');
				var bwid = jQuery('input[name="bwid"]').val();
				
				var postdata = {
				position : position,
				bwid:bwid,
				location_action :'sort_location_position'
				};
				jQuery('.apt-loading-main').show();
				jQuery.ajax({
					url  : ajax_url+"/assets/lib/location_ajax.php",	
					type : 'POST',
					data : postdata,	
					success : function(response){
					jQuery('.apt-loading-main').hide();
					jQuery('.apt-locations-container').html(response);
					jQuery('.mainheader_message_inner').show();
					appointment_hide_success_msg();
					}
				});
				}
			});

	});	
						/***********************Location Jquery End Here **********************/	
						
							/*********************** Category Jquery **********************/
/** Add new category button and popover **/
jQuery(document).bind('ready ajaxComplete', function(){
	  jQuery('#apt-add-new-category').popover({ 
		html : true,
		content: function() {
		  return jQuery('#popover-content-wrapper').html();
		}
	  });
	});
/** Hide add new service popover **/
jQuery(document).on('click', '#apt-close-popover-new-service-category', function(){			
		jQuery('.popover').fadeOut();
});

/** Delete service category popover **/
jQuery(document).bind('ready ajaxComplete', function(){
	  jQuery('#apt-delete-service-category').popover({ 
		html : true,
		content: function() {		
		  return jQuery('#popover-delete-service-category').html();
		 
		}
	  });
	});
/** Hide delete service category popover **/
jQuery(document).on('click', '#apt-close-popover-delete-service-category', function(){			
		jQuery('.popover').fadeOut();
});		
							
/** Create Category **/
jQuery(document).on('click','.apt_create_category',function(){
	var ajax_url = serviceObj.plugin_path;
		var categorytitle_err_msg = admin_validation_err_msg.categorytitle_err_msg;
		var bwid=jQuery('input[name="bwid"]').val();
		jQuery('#apt_create_category').validate();
		jQuery("#apt_category_title").rules("add",{ required: true,remote: {
															url  : ajax_url+"/assets/lib/service_ajax.php",
															type: "POST",
															async: true,
															data: {
															title:function() {
																 return jQuery('#apt_category_title').val();
																},
																/* add_provider:'yes', */
																action:"check_category_title",
																bwid:bwid
															}
														},  messages: { required: categorytitle_err_msg , remote:"Category Title Already Exists!!!"}});
			if(!jQuery('#apt_create_category').valid()){
				return false;
		}
	
		
		var category_title = jQuery('#apt_category_title').val();
		var bwid = jQuery('input[name="bwid"]').val();
		jQuery('.apt-loading-main').show();
		var postdata = { category_title:category_title,
						 category_action:'create_category',
						 bwid:bwid						 
		}
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/category_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						//console.log(response);
						jQuery('.apt-loading-main').hide();
						jQuery('#apt_category_listing').html(response);
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
					jQuery.ajax({					
							url  : ajax_url+"/assets/lib/category_ajax.php",					
							type : 'POST',					
							data : {category_action:'read_category_dd_options', bwid:bwid},				
							dataType : 'html',					
							success  : function(response) {
								jQuery('#apt_service_categories').html(response);
								jQuery('#apt_service_categories').selectpicker('refresh');
								jQuery('.mainheader_message_inner').show();
								appointment_hide_success_msg();
								
							}
						});
										
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
		});
}); 
/* Sort Category Position  */
jQuery(document).ready(function(){		
			jQuery("#sortable-category-list").sortable({
				update : function(event,ui){
				var ajax_url = serviceObj.plugin_path;
				var position = jQuery(this).sortable('serialize');
				
				var postdata = {
				position : position,
				category_action :'sort_category_position'
				};
				jQuery('.apt-loading-main').show();
				jQuery.ajax({
					url  : ajax_url+"/assets/lib/category_ajax.php",	
					type : 'POST',
					data : postdata,	
					success : function(response){
					jQuery('.apt-loading-main').hide();
					jQuery('.mainheader_message_inner').show();
					appointment_hide_success_msg();
					}
				});
				}
			});

	});	
	
						/*********************** Category Jquery End Here **********************/	
						
						   /*********************** Services Jquery **********************/	
/** Add new service **/

jQuery(document).on('click', '#apt-add-new-service', function(){	
	jQuery( ".apt-show-hide-checkbox" ).trigger( "click" );		
	jQuery('.apt-add-new-service').fadeIn();
	jQuery('html, body').stop().animate({
	'scrollTop': jQuery('.apt-add-new-service').offset().top - 35
	}, 2000, 'swing', function () {});
});

/** Add new service addons **/

jQuery(document).on('click', '#apt-add-new-service-addons', function(){	
	jQuery( ".apt-show-hide-checkbox" ).trigger( "click" );		
	jQuery('.apt-add-new-service-addons').fadeIn();
	jQuery('html, body').stop().animate({
	'scrollTop': jQuery('.apt-add-new-service-addons').offset().top - 35
	}, 2000, 'swing', function () {});
});



jQuery(function() {
	jQuery( "#sortable-services" ).sortable({ handle: '.fa-th-list' });
	jQuery( "#sortable-service-list" ).sortable();
}); 												   
/** Delete service & location popover **/
jQuery(document).bind('ready ajaxComplete', function(){
		jQuery('.apt-delete-popover').popover({ 
				html : true,
				content: function() {
				  return jQuery('#'+jQuery(this).data('poid')).html();
				}
		});
});
/** Hide delete service & location popover **/
jQuery(document).on('click', '.apt-close-popover-delete', function(){			
	jQuery('.popover').fadeOut();
});						   

jQuery(document).on('click','.u_service_offeredprice,input[name="offered_price"]',function(){
	jQuery('label.error').each(function(){
		jQuery(this).hide();
	});
});		

/* Offered Price Should Less then Default On Save New Service */
jQuery(document).on('click','#apt_create_service',function(){	
	var serviceofferpricegreater_err_msg = admin_validation_err_msg.serviceofferpricegreater_err_msg;
	
	if(jQuery("#apt_create_service").valid()){
			
		var service_amount = jQuery('input[name="service_price"]').val();
		var service_offeredprice = jQuery('input[name="offered_price"]').val();
		jQuery('#offered_price-error').hide();
		if(service_offeredprice>=service_amount){
		jQuery('#offered_price-error').show();
		jQuery('#offered_price-error').text(serviceofferpricegreater_err_msg);
			return false;
		}
		
		
	}
	
});	   
						   
/* Update Service Detail */	
jQuery(document).on('click','.update_service',function(){
		var ajax_url = serviceObj.plugin_path;
		var serviceofferpricegreater_err_msg = admin_validation_err_msg.serviceofferpricegreater_err_msg;
		var service_id = jQuery(this).data('service_id');
				
		if(!jQuery("#apt_update_service_"+service_id).valid()){
				return false;
		}
		
		
		var color_tag = jQuery('#apt-service-color-tag'+service_id).val();
		var service_title = jQuery('#apt-service-title'+service_id).val();
		var image = jQuery('#bdls'+service_id+'uploadedimg').val();
		var service_description = jQuery('#apt-service-desc'+service_id).val();
		var service_category = jQuery('#apt-service-category'+service_id).val();
		var service_duration_hrs = jQuery('#apt-duration-hrs'+service_id).val();
		var service_duration_mins = jQuery('#apt-duration-mins'+service_id).val();
		var service_amount = jQuery('#apt-service-price'+service_id).val();
		var service_offeredprice = jQuery('#apt-service-offered-price'+service_id).val();
		jQuery('#apt-service-offered-price'+service_id+'-error').hide();
		if(service_offeredprice>=service_amount){
		jQuery('#apt-service-offered-price'+service_id+'-error').show();
		jQuery('#apt-service-offered-price'+service_id+'-error').text(serviceofferpricegreater_err_msg);
			return false;
		}		
		
		jQuery('.apt-loading-main').show();
		var postdata = { service_id:service_id,
						 color_tag:color_tag,						 
						 service_title:service_title,						 
						 image:image,						 
						 service_description:service_description,						 
						 service_category:service_category,						 
						 service_duration_hrs:service_duration_hrs,						 
						 service_duration_mins:service_duration_mins,						 
						 service_amount:service_amount,						 					 
						 service_offeredprice:service_offeredprice,				 					 
						 service_action:'update_service'						 
		}
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/service_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();
						jQuery('#apt_category_listing').html(response);
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
						location.reload();
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
		});
}); 	

/* Delete Service Permanently */	
jQuery(document).on('click','.delete_service',function(){
		var ajax_url = serviceObj.plugin_path;
		var service_id = jQuery(this).data('id');
		jQuery('.apt-loading-main').show();
		var postdata = { service_id:service_id,
						 service_action:'delete_service'						 
		}
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/service_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();	
						jQuery('#service_detail_'+service_id).fadeOut('slow');
						jQuery.ajax({					
									url  : ajax_url+"/assets/lib/category_ajax.php",			
									type : 'POST',					
									data : {category_action:'get_category_lsiting'},			
									dataType : 'html',					
									success  : function(response) {								
										jQuery('#apt_category_listing').html(response);
										jQuery('.mainheader_message_inner').show();
										appointment_hide_success_msg();
									}
						});						
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
		});
});  					

/* Delete Category */	
jQuery(document).on('click','#apt-delete-category',function(){
		var ajax_url = serviceObj.plugin_path;
		var category_id = jQuery('.apt_category_services.active').data('cid');
		var bwid = jQuery('input[name="bwid"]').val();
		if(jQuery(this).data('del')=='N'){		
			return false;
		}
		
		jQuery('.apt-loading-main').show();
		var postdata = { category_id:category_id,
						 bwid:bwid,
						 category_action:'delete_category'						 
		}
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/category_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();	
						jQuery('#apt-close-popover-delete-service-category').trigger('click');
						jQuery('#apt_category_listing').html(response);
						jQuery('.apt_category_all_service').trigger('click');
						jQuery('#category_detail_'+category_id).fadeOut('slow');
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
						
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
		});
}); 
/* Sort Service Position */
jQuery(document).ready(function(){		
			jQuery("#sortable-services").sortable({
				update : function(event,ui){
				var ajax_url = serviceObj.plugin_path;
				var position = jQuery(this).sortable('serialize');
				
				var postdata = {
				position : position,
				service_action :'sort_service_position'
				};
				jQuery('.apt-loading-main').show();
				jQuery.ajax({
					url  : ajax_url+"/assets/lib/service_ajax.php",	
					type : 'POST',
					data : postdata,	
					success : function(response){
					jQuery('.apt-loading-main').hide();
					jQuery('.mainheader_message_inner').show();
					appointment_hide_success_msg();
					}
				});
				}
			});

	});	

/* Get Category Services */	
jQuery(document).on('click','.apt_category_services, .apt_category_all_service',function(){
		var ajax_url = serviceObj.plugin_path;
		var category_id = jQuery(this).data('cid');
		var cate_services = jQuery(this).data('cs');
		jQuery('.apt-loading-main').show();
		var bwid = jQuery('input[name="bwid"]').val();
		var postdata = { category_id:category_id,
						 bwid:bwid,
						 service_action:'get_category_services'						 
		}
		var postdatacateg = { category_id:category_id,
						 category_action:'get_category_title'						 
		}
		
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/category_ajax.php",					
					type : 'POST',					
					data : postdatacateg,					
					dataType : 'html',					
					success  : function(response) {
						if(category_id=='all'){
						jQuery('#apt-category-delete-icon').hide();
						}
						jQuery('.popover').hide();
						jQuery('#delete_category_error').hide();
						jQuery('#delete_category_sucess').show();
						if(cate_services>0){
							jQuery('#delete_category_error').show();
							jQuery('#delete_category_sucess').hide();
						}
						jQuery('.apt-loading-main').hide();	
						jQuery('#apt-category-title').text(response);
						jQuery('#apt-category-delete-icon').show();
						jQuery('#apt-delete-category').attr('data-cid',category_id);
						
					
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
		});
		
		
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/service_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();	
						jQuery('#sortable-services').html(response);
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
		});
});  

/* Update Service Status */	
jQuery(document).on('change','.update_service_status',function(){
		var ajax_url = serviceObj.plugin_path;
		var service_id = jQuery(this).data('id');
		if(jQuery(this).is(':checked')){
			var service_status = 'Y';
		}else{
			var service_status =  'N';
		}
		jQuery('.apt-loading-main').show();
		var postdata = { service_id:service_id,
						 service_status:service_status,
						 service_action:'updateservicestatus'						 
		}
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/service_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
					jQuery('.apt-loading-main').hide();
					jQuery('.mainheader_message_inner').show();
					appointment_hide_success_msg();
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
		});
});  	

/* Link Service With Provider */
jQuery(document).on('change','.link_staff',function(){
	var staff_id = jQuery(this).val();
	var service_id = jQuery(this).data('service_id');
	var ajax_url = serviceObj.plugin_path;
	if(jQuery(this).is(":checked")){
		var service_action = 'link_staff';
	}else{
		var service_action = 'unlink_staff';
	}
	
	if(staff_id!='all' && jQuery('.linkallstaff').is(':checked')){
		jQuery('.linkallstaff').prop('checked',false);
	}

	var postdata = { service_id:service_id,
					 staff_id:staff_id,
					 service_action:service_action						 
		}
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/service_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
		});
});

							/***********************Service Jquery End Here **********************/	
						
							/*********************** Staff Jquery **********************/
/** Add new staff button and popover **/
jQuery(document).bind('ready ajaxComplete', function(){
	  jQuery('#apt-add-new-staff').popover({ 
		html : true,
		content: function() {
		  return jQuery('#popover-content-wrapper').html();
		}
	  });
});
/** Hide Create New Staff Popover **/	
jQuery(document).on('click', '#apt-close-popover-new-staff', function(){			
		jQuery('.popover').fadeOut();
});
/** delete staff member popover **/
jQuery(document).bind('ready ajaxComplete', function(){
	  jQuery('#apt-delete-staff-member').popover({ 
		html : true,
		content: function() {
		  return jQuery('#popover-delete-member').html();
		}
	  });
	});
/** Hide delete staff popover **/
	jQuery(document).on('click', '#apt-close-popover-delete-staff', function(){			
		jQuery('.popover').fadeOut();
	});
/** Sort StafF Members **/
jQuery(function() {
	jQuery( "#sortable" ).sortable();	
});
/** Staff Delete Breaks Popover**/
jQuery(document).bind('ready ajaxComplete', function(){
	jQuery('.staff_delete_break').popover({ 
		html : true,
		content: function() {
			var break_id = jQuery(this).data('bid');
			jQuery('.popover').each(function(){
				jQuery(this).fadeOut('slow');
			});
		  return jQuery('#popover-delete-breaks'+break_id).html();
		}
	});
});
/** hide delete staff popover **/
jQuery(document).on('click', '.close_break_del_popover', function(){
	jQuery('.popover').fadeOut('slow');
});
/** for staff off time **/
jQuery(document).bind('ready ajaxComplete', function(){
		function cb(start, end) {
			jQuery('#offtime-daterange span').html(start.format('MM/DD/YYYY h:mm A') + ' - ' + end.format('MM/DD/YYYY h:mm A'));
		}
		cb(moment().subtract(29, 'days'), moment());

		jQuery('#offtime-daterange').daterangepicker({
			timePicker: true,
			timePickerIncrement: 1,
			locale: {
				format: 'MM/DD/YYYY h:mm A'
			}
		}, cb);
	});	
jQuery(document).bind('ready ajaxComplete', function(){
		jQuery('#apt-staff-member-offtime-list').DataTable({
			destroy: true,
			searching: false,
			dom: 'frtipB',
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5'
			]
		});
});
/** Staff Service Schedule Price Delete **/
jQuery(document).bind('ready ajaxComplete', function(){
	  jQuery('.delete_ssp_popover').popover({ 
		html : true,
		content: function() {
			var sspid= jQuery(this).data('sspid');
			jQuery('.popover').each(function(){
				jQuery(this).fadeOut('slow');
			});
		  return jQuery('#popover-delete-price'+sspid).html();
		}
	  });
jQuery(document).on('click', '.cancel_ssp_delete', function(){	
		jQuery('.popover').fadeOut('slow');
      });
});					
/* Make/Unmake Staff Member As Manager -- Add/Remove Manager Cap **/	
jQuery(document).on('change','.apt_staff_manager',function(){
		jQuery('.apt-loading-main').show();
		var ajax_url = header_object.plugin_path;
		var staff_id = jQuery(this).data('staff_id');	
		if(jQuery(this).prop('checked')==true){ var method ='add';}else{ var method='remove';}	
			var postdata = { staff_id:staff_id,
							 method:method,
							 staff_action:'staff_as_manager'						 
			}
			 jQuery.ajax({					
						url  : ajax_url+"/assets/lib/staff_ajax.php",					
						type : 'POST',					
						data : postdata,					
						dataType : 'html',					
						success  : function(response) {
						jQuery('.apt-loading-main').hide();
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
					}
			});
	}); 				
/* Get Selected Info Click from Left Side */
jQuery(document).on('click','.staff-list',function(){
		var ajax_url = header_object.plugin_path;
		var staff_id = jQuery(this).data('staff_id');
		var rso_staff = jQuery('.apt-staff-member-name').data('staff_id');
		var bwid = jQuery('input[name="bwid"]').val();
		if(staff_id!=rso_staff){
			jQuery('.apt-loading-main').show();
			
			var postdata = { staff_id:staff_id,
							 bwid:bwid,
							 staff_action:'get_staff_right'						 
			}
			jQuery.ajax({					
						url  : ajax_url+"/assets/lib/staff_ajax.php",					
						type : 'POST',					
						data : postdata,					
						dataType : 'html',					
						success  : function(response) {
							jQuery('.apt-loading-main').hide();
							jQuery('.apt-staff-details').html(response);
						},
						error: function (xhr, ajaxOptions, thrownError) {
						}
			});
		}
});
							
/* Create Staff Member */	
jQuery(document).on('click','#apt_create_staff_btn',function(){
		var ajax_url = header_object.plugin_path;
		var staffusername_err_msg = admin_validation_err_msg.staffusername_err_msg;	
		var staffusernameexist_err_msg = admin_validation_err_msg.staffusernameexist_err_msg;	
		var staffpassword_err_msg = admin_validation_err_msg.staffpassword_err_msg;
		var staffemail_err_msg = admin_validation_err_msg.staffemail_err_msg;
		var staffemailexist_err_msg = admin_validation_err_msg.staffemailexist_err_msg;
		var stafffullname_err_msg = admin_validation_err_msg.stafffullname_err_msg;
		var staffselect_err_msg = admin_validation_err_msg.staffselect_err_msg;
		var siteurl = header_object.site_url;
	
		jQuery('#apt_create_staff').validate({
					rules: {
						apt_newuser_username: {
													required: true,
													remote: {
															url  : ajax_url+"/assets/lib/front_ajax.php",
															type: "POST",
															async: true,
															data: {
															username:function() {
																 return jQuery('input[name="apt_newuser_username"]').val();
																},
																/* add_provider:'yes', */
																action:"check_username"
															}
														}
							},
						apt_newuser_password: {
													required: true
							},
						apt_newuser_fullname: {
													required: true,
							},
						apt_newuser_email: {
													required: true,
													remote: {
														url: siteurl+"/wp-admin/admin-ajax.php",
														type: "POST",
														async: true,
														data: {
														email:function() {
															return jQuery('input[name="apt_newuser_email"]').val();
															},		
															action:'check_email_bd'
														}
													},
													customemail:true
							},	
						apt_selected_wpuser : { required :true
							},	
						},
					messages: {
								apt_newuser_username: { required: staffusername_err_msg }, 
								apt_newuser_password: { required: staffpassword_err_msg }, 
								apt_newuser_fullname: { required: stafffullname_err_msg }, 
								apt_newuser_email: { required: staffemail_err_msg , customemail: staffemailexist_err_msg},
								apt_selected_wpuser: {required: staffselect_err_msg}
						}
				});
		
		var ajax_url = header_object.plugin_path;
		
		if(jQuery('#apt_create_staff').valid()){
		var usertype = jQuery('.apt-new-usercl:checked').val();
		var existing_userid = jQuery('#apt-selected-wp-user').val();
		var staff_username = jQuery('#apt-staff-username').val();
		var staff_password = jQuery('#apt-staff-password').val();
		var staff_location = jQuery('#apt-staff-location').val();
		var staff_fullname = jQuery('#apt-staff-fullname').val();
		var staff_email = jQuery('#apt-staff-email').val();
		jQuery('.apt-loading-main').show();
		
		var postdata = { usertype:usertype,
						 existing_userid:existing_userid,
						 staff_username:staff_username,
						 staff_password:staff_password,
						 staff_location:staff_location,
						 staff_fullname:staff_fullname,
						 staff_email:staff_email,
						 staff_action:'create_staff'						 
		}
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/staff_ajax.php",					
					type : 'POST',					
					data : postdata,					
					/* dataType : 'html',	 */				
					success  : function(response) {
						jQuery('.apt-loading-main').hide();
						jQuery('#apt-close-popover-new-staff').trigger('click');
						jQuery('#apt-staff-sortable').html(response);
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
						location.reload();
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
		});
		}
});  							
/* Validate Staff Phone */
/* Update Location Validation */
jQuery(document).bind('ready ajaxComplete', function(){

	var staffvalidphone_err_msg = admin_validation_err_msg.staffvalidphone_err_msg;
		jQuery.validator.addMethod("numeric_pattern", function(value, element) {
	return this.optional(element) || /^(?=.*[0-9])[- +()0-9]+$/.test(value);
	}, staffvalidphone_err_msg);

		jQuery('.staff_personal_detail').each(function(){
				jQuery(this).validate({
					rules: {						
						staff_phone: {
								numeric_pattern:true
							},					
						},
						messages: {								
								staff_phone: { numeric_pattern:staffvalidphone_err_msg}
							}
				});
		});			
});

		
/* Update Staff Member Detail*/	
jQuery(document).on('click','.update_staff_detail',function(){
		var ajax_url = header_object.plugin_path;
		var site_url = header_object.site_url;
		var staff_id = jQuery(this).data('staff_id');
		
		if(!jQuery("#staff_personal_detail"+staff_id).valid()){
				return false;
		}	
				
		
		var staff_name = jQuery('#staff_name_'+staff_id).val();
		var staff_description = jQuery('#staff_description_'+staff_id).val();
		var staff_image = jQuery('#bdsdu'+staff_id+'uploadedimg').val();
		var staff_phone = jQuery('#staff_phone_'+staff_id).val();
		var staff_timezone = jQuery('#staff_timezone_'+staff_id).val();
		var staff_timezoneID = jQuery('#staff_timezone_'+staff_id+' option:selected').attr('timezoneid');
		var existing_st = jQuery('#curr_staff_schedule_'+staff_id).val();
		if(jQuery('#staff_schedule_'+staff_id).is(':checked')){
		var staff_schedule_type = 'M';	}else{var staff_schedule_type = 'W';	}		
		if(jQuery('#staff_status_'+staff_id).is(':checked')){	var staff_status = 'E';	}else{var staff_status = 'D';}
		jQuery('.apt-loading-main').show();
		
		var postdata = { staff_id:staff_id,
						 staff_name:staff_name,
						 staff_description:staff_description,
						 staff_image:staff_image,
						 staff_phone:staff_phone,
						 staff_timezone:staff_timezone,
						 staff_timezoneID:staff_timezoneID,
						 staff_schedule_type:staff_schedule_type,
						 staff_status:staff_status,
						 staff_action:'update_staff_detail'						 
		}
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/staff_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();
						if(existing_st!=staff_schedule_type){location.reload();}
						if(jQuery('#bdsdu'+staff_id+'uploadedimg').val()!=''){
							var newimgpath = site_url+'/wp-content/uploads'+jQuery('#bdsdu'+staff_id+'uploadedimg').val();		
							jQuery('#staff_detail_'+staff_id+' img').removeAttr('src');
							jQuery('#staff_detail_'+staff_id+' img').attr('src',newimgpath);
						}
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
		});
});  

/* Staff Add Service Pricing */
jQuery(document).on('click','.apt_add_ssp',function(){
		var ajax_url = header_object.plugin_path;
		var staff_id = jQuery(this).data('staffid');
		var service_id = jQuery(this).data('serviceid');
		var service_amount = jQuery(this).data('serviceamout');
		var weekdayid = jQuery(this).data('mainid');
		var weekid = jQuery(this).data('weekid');
		var dayid = jQuery(this).data('dayid');

		//jQuery('.apt-loading-main').show();
		var postdata = { staff_id:staff_id,
						 service_id:service_id,
						 weekid:weekid,
						 dayid:dayid,
						 service_amount:service_amount,
						 staff_action:'add_service_schedule_price'						 
		}
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/staff_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('#apt_ssp_'+service_id+'_'+weekdayid).append(response);
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
		});
});
/* Staff Service Schedule EndTime */
jQuery(document).on('change','.ssp_starttime',function(){
		if(jQuery(this).hasClass('selectpicker')){
		var ajax_url = header_object.plugin_path;
		var ssp_id = jQuery(this).data('sspid');
		var ssp_starttime = jQuery(this).val();
		
			var postdata = { 
						 ssp_starttime:ssp_starttime,
						 staff_action:'staff_get_ssp_end'						 
			}
			 jQuery.ajax({					
						url  : ajax_url+"/assets/lib/staff_ajax.php",					
						type : 'POST',					
						data : postdata,					
						dataType : 'html',					
						success  : function(response) {
							jQuery('[name="ssp_endtime_'+ssp_id+'"] option').each(function() { 
									ssp_options = new Date('2016/01/07 '+jQuery(this).val());
									ssp_edoptions = new Date('2016/01/07 '+response);
									jQuery(this).show();
									
									if(ssp_options.getTime()< ssp_edoptions.getTime()){jQuery(this).hide();}
									if(jQuery(this).val() == response){ jQuery(this).attr('selected', 'selected');} 
								});
							
							jQuery('#ssp_endtime_'+ssp_id).selectpicker('refresh');
							jQuery('#ssp_endtime_'+ssp_id).selectpicker('val',response);
						}
			});
		}
	}); 
/* Update Staff Service Schedule Price */
jQuery(document).on('click','.update_ssp_detail',function(){
		var ajax_url = header_object.plugin_path;
		var ssp_id = jQuery(this).data('sspid');
		var ssp_starttime = jQuery('#ssp_starttime_'+ssp_id).val();
		var ssp_endtime = jQuery('#ssp_endtime_'+ssp_id).val();
		var ssp_price = jQuery('#ssp_price_'+ssp_id).val();

		jQuery('.apt-loading-main').show();
		var postdata = { ssp_id:ssp_id,
						 ssp_starttime:ssp_starttime,
						 ssp_endtime:ssp_endtime,
						 ssp_price:ssp_price,
						 staff_action:'update_service_schedule_price'						 
		}
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/staff_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
		});
});
/*Delete Service Schedule Price*/
jQuery(document).on('click','.delete_ssp',function(){
		var ajax_url = header_object.plugin_path;
		var ssp_id = jQuery(this).attr('id');
		jQuery('.apt-loading-main').show();
		var postdata = { ssp_id:ssp_id,
						 staff_action:'delete_ssp'						 
		}
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/staff_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();
						jQuery('#apt_ssp_detail_'+ssp_id).fadeOut('slow');
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
						
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
		});
}); 
/* Link Provider With Service -on Service Schedule Price Module */
jQuery(document).on('change','.link_service',function(){
	var service_id = jQuery(this).val();
	var staff_id = jQuery(this).data('staff_id');
	var bwid = jQuery('input[name="bwid"]').val();
	var ajax_url = header_object.plugin_path;
	var currentstaff_services = jQuery('.staff_servicecount_'+staff_id).text();
	var total_services = jQuery('.staff_servicecount_'+staff_id).data('total_service');
	if(jQuery(this).is(":checked")){
		var staff_action = 'link_service';
		if(service_id=='all'){
			jQuery('.staff_servicecount_'+staff_id).text(total_services);
		}else{
			jQuery('.staff_servicecount_'+staff_id).text(parseInt(currentstaff_services)+parseInt(1));
		}
	}else{
		var staff_action = 'unlink_service';
		if(service_id=='all'){
			jQuery('.staff_servicecount_'+staff_id).text(0);
		}else{
			jQuery('.staff_servicecount_'+staff_id).text(parseInt(currentstaff_services)-parseInt(1));
		}
	}
	if(service_id!='all' && jQuery('.linkallservices').is(':checked')){
		jQuery('.linkallservices').prop('checked',false);
	}
	
	jQuery('.apt-loading-main').show();
	var postdata = { service_id:service_id,
					 staff_id:staff_id,
					 staff_action:staff_action,
					 bwid:bwid						 
		}
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/staff_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
		});
});




/* On Select Day Off Show/Hide Start/End Time Dropdowns */
jQuery(document).on('change','.staff_dayoff',function(){
	var wd_id = jQuery(this).data('mainid');
	if(jQuery(this).is(':checked')){
		jQuery('#staff_st_et_'+wd_id).show("blind", {direction: "vertical"}, 1000 );
	}else{
		jQuery('#staff_st_et_'+wd_id).hide("blind", {direction: "vertical"}, 500 );
	}
});

/* Update Staff Availability Schedule*/	
jQuery(document).on('click','.update_staff_schedule',function(){
		var ajax_url = header_object.plugin_path;
		var staff_id = jQuery(this).attr('id');
		var dayschdeule = [];
		var staff_schedule_type = jQuery(this).data('st');
		if(staff_schedule_type=='W'){
			for(d=1;d<=7;d++){
				var day_starttime = jQuery('#start_time_1_'+d).val();
				var day_endtime = jQuery('#end_time_1_'+d).val();
							
				if(jQuery('#off_day_1_'+d).is(':checked')){var off_day = 'N';}else{var off_day = 'Y';}
				var dayinfo = day_starttime+'##'+day_endtime+'##'+off_day;
				dayschdeule.push(dayinfo);
			}
		}else{
			for(w=1;w<=5;w++){
				for(d=1;d<=7;d++){
					var day_starttime = jQuery('#start_time_'+w+'_'+d).val();;
					var day_endtime = jQuery('#end_time_'+w+'_'+d).val();;
					if(jQuery('#off_day_'+w+'_'+d).is(':checked')){var off_day = 'N';}else{var off_day = 'Y';}
					var dayinfo = day_starttime+'##'+day_endtime+'##'+off_day;
					dayschdeule.push(dayinfo);
				}
			}	
		}
		
		
		
		var staff_schedule_type = jQuery(this).data('st');		
		
		jQuery('.apt-loading-main').show();
		
		 var postdata = { staff_id:staff_id,
						  staff_schedule_type:staff_schedule_type,
						  dayschdeule:dayschdeule,
						  staff_action:'update_staff_schedule'						 
		}
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/staff_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();
						//jQuery('#apt-staff-sortable').html(response);
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
		});
});  

/* Staff Add Schedule Break */	
jQuery(document).on('click','.staff_add_break',function(){
		var ajax_url = header_object.plugin_path;
		var staff_id = jQuery(this).data('staff_id');
		var weekid = jQuery(this).data('weekid');
		var dayid = jQuery(this).data('dayid');
		var postdata = { staff_id:staff_id,
						 weekid:weekid,
						 dayid:dayid,
						 staff_action:'staff_add_break'						 
		}
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/staff_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('#apt_staff_breaks_'+weekid+'_'+dayid).append(response);
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
		});
}); 
/* Staff Update Schedule Break */	
jQuery(document).on('change','.staff_schedule_break',function(){
		var ajax_url = header_object.plugin_path;
		var break_id = jQuery(this).data('bid');
		var break_ut = jQuery(this).data('bv');
		var break_start = jQuery('#staff_breakstart_'+break_id).val();
		var break_end = jQuery('#staff_breakend_'+break_id).val();
		if(break_ut=='start'){
			var postdata = { 
						 break_start:break_start,
						 staff_action:'staff_get_break_end'						 
			}
			jQuery.ajax({					
						url  : ajax_url+"/assets/lib/staff_ajax.php",					
						type : 'POST',					
						data : postdata,					
						dataType : 'html',					
						success  : function(response) {
						
							jQuery('[name="staff_breakend_'+break_id+'"] option').each(function() { 
								options = new Date('2016/01/07 '+jQuery(this).val());
								edoptions = new Date('2016/01/07 '+response);
								jQuery(this).show();
								if(options.getTime()< edoptions.getTime()){jQuery(this).hide();}
								if(jQuery(this).val() == response){ jQuery(this).attr('selected', 'selected');} 
							});
							
							jQuery('#staff_breakend_'+break_id).selectpicker('refresh');
							jQuery('#staff_breakend_'+break_id).selectpicker('val',response);
							var postdata = { break_id:break_id,
									 break_start:break_start,
									 break_end:response,
									 staff_action:'staff_update_break'						 
									}
							jQuery.ajax({					
								url  : ajax_url+"/assets/lib/staff_ajax.php",					
								type : 'POST',					
								data : postdata,					
								dataType : 'html',					
								success  : function(response) {
									jQuery('.mainheader_message_inner').show();
									appointment_hide_success_msg();
								}
								});	
							}
						});
		
		}else{
		
			var postdata = { break_id:break_id,
							 break_start:break_start,
							 break_end:break_end,
							 staff_action:'staff_update_break'						 
								}
			jQuery.ajax({					
				url  : ajax_url+"/assets/lib/staff_ajax.php",					
				type : 'POST',					
				data : postdata,					
				dataType : 'html',					
				success  : function(response) {
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
				},
				error: function (xhr, ajaxOptions, thrownError) {
				}
				});	
			
		
		
		
		}
		
	
}); 	

/*Delete Staff Break */
jQuery(document).on('click','.delete_staff_break',function(){
		var ajax_url = header_object.plugin_path;
		var break_id = jQuery(this).attr('id');
		jQuery('.apt-loading-main').show();
		var postdata = { break_id:break_id,
						 staff_action:'staff_delete_break'						 
		}
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/staff_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();
						jQuery('#staff_break_'+break_id).fadeOut('slow');
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
					
						
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
		});
}); 

/* Staff Add Offtime  */
jQuery(document).on('click','.add_staff_offtime',function(){
		var ajax_url = header_object.plugin_path;
		var staff_id = jQuery(this).data('sid');
		var offtime_start=jQuery('#offtime-daterange').data('daterangepicker').startDate.format('YYYY-MM-DD h:mm:ss');
		var offtime_end=jQuery('#offtime-daterange').data('daterangepicker').endDate.format('YYYY-MM-DD h:mm:ss');
		var bwid = jQuery('input[name="bwid"]').val();
		jQuery('.apt-loading-main').show();
		var postdata = { staff_id:staff_id,
						 offtime_start:offtime_start,
						 offtime_end:offtime_end,
						 bwid:bwid,
						 staff_action:'add_staff_offtime'						 
		}
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/staff_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {							
						var otpostdata = { staff_id:staff_id,
						 staff_action:'refresh_staff_offtimes'						 
						}
						jQuery.ajax({					
							url  : ajax_url+"/assets/lib/staff_ajax.php",					
							type : 'POST',					
							data : otpostdata,					
							dataType : 'html',					
							success  : function(otresponse) {
								jQuery('.apt-loading-main').hide();
								jQuery('.apt-staff-member-offtime-list-main').html(otresponse);
								jQuery('.mainheader_message_inner').show();
								appointment_hide_success_msg();
							},
							error: function (xhr, ajaxOptions, thrownError) {
							}
						});						
					},
					error: function (xhr, ajaxOptions, thrownError) {
				}
		});
});
/* Staff Delete Offtime */
jQuery(document).on('click','.delete_staff_offtime',function(){
		var ajax_url = header_object.plugin_path;
		var staffid = jQuery(this).data('staffid');
		var offtime_id = jQuery(this).data('otid');
		var bwid = jQuery('input[name="bwid"]').val();
		jQuery('.apt-loading-main').show();
		var postdata = { offtime_id:offtime_id,
						 staff_action:'delete_staff_offtime'						 
		}
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/staff_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						var otpostdata = { staff_id:staffid,
						 staff_action:'refresh_staff_offtimes'						 
						}
						jQuery.ajax({					
							url  : ajax_url+"/assets/lib/staff_ajax.php",					
							type : 'POST',					
							data : otpostdata,					
							dataType : 'html',					
							success  : function(otresponse) {
								jQuery('.apt-loading-main').hide();
								jQuery('.apt-staff-member-offtime-list-main').html(otresponse);
								jQuery('.mainheader_message_inner').show();
								appointment_hide_success_msg();
							},
							error: function (xhr, ajaxOptions, thrownError) {
							}
						});					
						
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
		});
});

/* Staff Offdays Full Month Off/On */
jQuery(document).on("click",".fullmonthoff",function(){
		var off_year_month = jQuery(this).attr('id');	
		var staff_id = jQuery('#staff_offdays_id').val();
		var ajaxurl = header_object.plugin_path;
		var postdata = {
				staff_id:staff_id,
				off_year_month:off_year_month,
				staff_action:'staff_add_offdays'
		}
		if(jQuery(this).is(':checked')) {
			
			jQuery(".selmonth_"+off_year_month+" td.RR").addClass("selectedDate");
				
				jQuery.ajax({
						url  : ajaxurl+"/assets/lib/staff_ajax.php",
						type : 'POST',
						data : postdata,
						dataType : 'html',
						success  : function(response) {
							jQuery('.mainheader_message_inner').show();
							appointment_hide_success_msg();
						},
						error: function (xhr, ajaxOptions, thrownError) {
						}
				});
			
		}else {
			jQuery(".selmonth_"+off_year_month+" td.RR").removeClass("selectedDate");
			jQuery(".selmonth_"+off_year_month+" td.RR").toggleClass("date_single");
			var postdata = {
				staff_id:staff_id,
				off_year_month:off_year_month,
				staff_action:'staff_delete_offdays'
			}
			jQuery.ajax({
						url  : ajaxurl+"/assets/lib/staff_ajax.php",
						type : 'POST',
						data : postdata,
						dataType : 'html',
						success  : function(response) {
							jQuery('.mainheader_message_inner').show();
							appointment_hide_success_msg();
						},
						error: function (xhr, ajaxOptions, thrownError) {
						}
				});
		}
		
});
/* Add/Remove Offdays jQuery */
 jQuery(document).ready(function(){
	jQuery(document).on("click",".dateline td.RR",function(){
	  var off_date = jQuery(this).attr('id');
      jQuery(this).toggleClass("selectedDate");
	  var str = jQuery(this).attr('class');
	  if (str.toLowerCase().indexOf("selecteddate") >= 0) {
				var staff_id = jQuery('#staff_offdays_id').val();
				var ajaxurl = header_object.plugin_path;
				var postdata = {
				staff_id:staff_id,
				off_date:off_date,
				staff_action:'staff_add_offdays'
				}

				jQuery.ajax({
						url  : ajaxurl+"/assets/lib/staff_ajax.php",
						type : 'POST',
						data : postdata,
						dataType : 'html',
						success  : function(response) {
							jQuery('.mainheader_message_inner').show();
							appointment_hide_success_msg();
						},
						error: function (xhr, ajaxOptions, thrownError) {
						}
				});
	  }else{
				var staff_id = jQuery('#staff_offdays_id').val();
				var ajaxurl = header_object.plugin_path;
				
				var postdata = {
				staff_id:staff_id,
				off_date:off_date,
				staff_action:'staff_delete_offdays'
				}

				jQuery.ajax({
						url  : ajaxurl+"/assets/lib/staff_ajax.php",
						type : 'POST',
						data : postdata,
						dataType : 'html',
						success  : function(response) {
							jQuery('.mainheader_message_inner').show();
							appointment_hide_success_msg();
						},
						error: function (xhr, ajaxOptions, thrownError) {
						}
				});
	  } 
});	
jQuery('.selectedDate').click(function(){
	   jQuery(this).toggleClass("date_single");
	});
});
/* Onchange Staff Schedule Start Time */
/* Staff Service Schedule EndTime */
jQuery(document).on('change','.schedule_day_start_time',function(){
		if(jQuery(this).hasClass('selectpicker')){
		var ajax_url = header_object.plugin_path;
		var schedule_id = jQuery(this).data('mainid').split('_');
		var schedule_starttime = jQuery(this).val();
		
			var postdata = { 
					schedule_starttime:schedule_starttime,
					staff_action:'staff_get_schedule_end'						 
			}
			 jQuery.ajax({					
						url  : ajax_url+"/assets/lib/staff_ajax.php",					
						type : 'POST',					
						data : postdata,					
						dataType : 'html',					
						success  : function(response) {
							jQuery('[name="end_time_['+schedule_id[0]+']_['+schedule_id[1]+'"] option').each(function() { 
									schedule_options = new Date('2016/01/07 '+jQuery(this).val());
									schedule_edoptions = new Date('2016/01/07 '+response);
									jQuery(this).show();
									
									if(schedule_options.getTime()< schedule_edoptions.getTime()){jQuery(this).hide();}
									if(jQuery(this).val() == response){ jQuery(this).attr('selected', 'selected');} 
								});
							
							jQuery('#end_time_'+schedule_id[0]+'_'+schedule_id[1]).selectpicker('refresh');
							jQuery('#end_time_'+schedule_id[0]+'_'+schedule_id[1]).selectpicker('val',response);
						}
			});
		}
}); 
/* Delete Staff Member */
jQuery(document).on('click','#delete_staff',function(){
		var ajax_url = header_object.plugin_path;
		var staff_id = jQuery(this).data('staff_id');
		jQuery('.apt-loading-main').show();
		var postdata = { staff_id:staff_id,
						 staff_action:'delete_staff_member'						 
		}
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/staff_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();						
						//jQuery('#offtime_detail_'+offtime_id).fadeOut('slow');
						jQuery('.apt-staff-container').html(response);
						jQuery('#apt-staff-sortable li').first().addClass("active");
						jQuery('#apt-staff-sortable li').first().trigger("click");
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
						window.location.reload();
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
		});
});	
	



							/***********************Staff Jquery End Here **********************/	
						
							/*********************** General Admin Ajax Jquery **********************/

/*Set Selected Location Session */
jQuery(document).on('click','.apt_selected_location',function(){
		var ajax_url = header_object.plugin_path;
		var location_id = jQuery('select[name="apt_selected_location"]').val();
		jQuery('.apt-loading-main').show();
		var postdata = { location_id:location_id,
						 general_ajax_action:'set_location_session'						 
		}
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/admin_general_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();
						location.reload();
						
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
		});
}); 


/** Delete service popover **/
jQuery(document).bind('ready ajaxComplete', function(){
		jQuery('.apt_remove_image').popover({ 
				html : true,
				content: function() {				
				  return jQuery('#popover-'+jQuery(this).attr('id')).html();
				}
		});
});
/** Hide delete service popover **/
jQuery(document).on('click', '.close_delete_popup', function(){			
	jQuery('.popover').fadeOut();
});	
	
	

/*** Common Code For Remove Image **/
jQuery(document).on('click','.apt_delete_image',function(){
		var ajax_url = header_object.plugin_path;
		var site_url = header_object.site_url;
		var defaultmedia = header_object.defaultmedia;		
		var mediaid = jQuery(this).data('mediaid');
		var mediapath = jQuery(this).data('mediapath');
		var mediasection = jQuery(this).data('mediasection');
		var defaultmedia_fullpath = defaultmedia+mediasection+'.png';
		var imagefieldid = jQuery(this).data('imgfieldid');
				
		jQuery('.apt-loading-main').show();
		jQuery('.close_delete_popup').trigger('click');
		var postdata = { mediaid:mediaid,
						 mediapath:mediapath,
						 action:'delete_image' }
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/"+mediasection+"_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {						
						jQuery('.apt-loading-main').hide();
						jQuery('#'+imagefieldid).val('');
						jQuery('#bdls'+mediaid+'locimage').attr('src',defaultmedia_fullpath);
						jQuery('#bdll'+mediaid+'locimage').attr('src',defaultmedia_fullpath);
						jQuery('#bdsdu'+mediaid+'locimage').attr('src',defaultmedia_fullpath);
						/* jQuery('.apt-'+mediasection+'-image').attr('src',defaultmedia_fullpath); */						
						jQuery('.apt_remove_image').hide();
						jQuery('#apt-remove-service-imagebdls'+mediaid).hide();
						jQuery('#staff_detail_'+mediaid+' img').attr('src',ajax_url+'/assets/images/'+mediasection+'.png');
						
						jQuery('#bdscad'+mediaid+'addimage').attr('src',ajax_url+'/assets/images/addon.png');
						jQuery('#bdscad'+mediaid+'uploadedimg').val('');
						jQuery('.show_image_icon_add'+mediaid).css('display','block');
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
		});
}); 

/************************************ Admin Panel Validations ********************************/
	/********************** Location Form Validations ****************************/
jQuery.validator.addMethod('customemail', function (value, element) {
			return this.optional(element) || /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/.test(value);
											
});	
	
	/* Create Location Validate */
//jQuery(document).ready(function(){
jQuery(document).bind('ready ajaxComplete', function(){
	var ajax_url = header_object.plugin_path;
	var locationtiitle_err_msg = admin_validation_err_msg.locationtiitle_err_msg;	
	var locationemail_err_msg = admin_validation_err_msg.locationemail_err_msg;	
	var locationinvalidemail_err_msg = admin_validation_err_msg.locationinvalidemail_err_msg;
	var locationphone_err_msg = admin_validation_err_msg.locationphone_err_msg;
	var locationvalidphone_err_msg = admin_validation_err_msg.locationvalidphone_err_msg;
	var locationinvalidphone_err_msg = admin_validation_err_msg.locationinvalidphone_err_msg;
	var locationaddress_err_msg = admin_validation_err_msg.locationaddress_err_msg;
	var locationcity_err_msg = admin_validation_err_msg.locationcity_err_msg;
	var locationstate_err_msg = admin_validation_err_msg.locationstate_err_msg;
	var locationzip_err_msg = admin_validation_err_msg.locationzip_err_msg;
	var locationcountry_err_msg = admin_validation_err_msg.locationcountry_err_msg;
	
	jQuery.validator.addMethod("numeric_pattern", function(value, element) {
	return this.optional(element) || /^(?=.*[0-9])[- +()0-9]+$/.test(value);
	}, locationvalidphone_err_msg);
	
	
	jQuery('#apt_create_location_cl').validate({
					rules: {
						location_title: {
							required: true,
							remote: {
									url  : ajax_url+"/assets/lib/service_ajax.php",
									type: "POST",
									async: true,
									data: {
									title:function() {
										 return jQuery('#apt-location-name').val();
										},
										action:"check_location_title"
									}
								}
							},
						email: {
													required: true
							},
						phone: {
													required: true,
													numeric_pattern:true,
							},
						address: {
													required: true
							},
						city: {
													required: true
							},	
						state: {
													required: true
							},
						zip: {
													required: true
							},
						country: {
													required: true
							},						
						},
					messages: {
								location_title: { required:locationtiitle_err_msg , remote: "Location Title Already exist!!!" }, 
								email: { required:locationemail_err_msg }, 
								phone: { required:locationphone_err_msg,numeric_pattern:locationvalidphone_err_msg},
								address: { required:locationaddress_err_msg }, 
								city: { required:locationcity_err_msg }, 
								state: { required:locationstate_err_msg }, 
								zip: { required:locationzip_err_msg }, 
								country: { required:locationcountry_err_msg } 
								
						}
				});
});

/* Update Location Validation */
jQuery(document).bind('ready ajaxComplete', function(){
	var locationtiitle_err_msg = admin_validation_err_msg.locationtiitle_err_msg;	
	var locationemail_err_msg = admin_validation_err_msg.locationemail_err_msg;	
	var locationinvalidemail_err_msg = admin_validation_err_msg.locationinvalidemail_err_msg;
	var locationphone_err_msg = admin_validation_err_msg.locationphone_err_msg;
	var locationvalidphone_err_msg = admin_validation_err_msg.locationvalidphone_err_msg;
	var locationinvalidphone_err_msg = admin_validation_err_msg.locationinvalidphone_err_msg;
	var locationaddress_err_msg = admin_validation_err_msg.locationaddress_err_msg;
	var locationcity_err_msg = admin_validation_err_msg.locationcity_err_msg;
	var locationstate_err_msg = admin_validation_err_msg.locationstate_err_msg;
	var locationzip_err_msg = admin_validation_err_msg.locationzip_err_msg;
	var locationcountry_err_msg = admin_validation_err_msg.locationcountry_err_msg;
	jQuery.validator.addMethod("numeric_pattern", function(value, element) {
	return this.optional(element) || /^(?=.*[0-9])[- +()0-9]+$/.test(value);
	}, locationvalidphone_err_msg);

		jQuery('.apt_update_location').each(function(){
				jQuery(this).validate({
					rules: {
						location_title: {
													required: true
							},
						email: {
													required: true
							},
						phone: {
													required: true,
													numeric_pattern:true,
							},
						address: {
													required: true
							},
						city: {
													required: true
							},	
						state: {
													required: true
							},
						zip: {
													required: true
							},
						country: {
													required: true
							},						
						},
					messages: {
								location_title: { required:locationtiitle_err_msg }, 
								email: { required:locationemail_err_msg }, 
								phone: { required:locationphone_err_msg,numeric_pattern:locationvalidphone_err_msg},
								address: { required:locationaddress_err_msg }, 
								city: { required:locationcity_err_msg }, 
								state: { required:locationstate_err_msg }, 
								zip: { required:locationzip_err_msg }, 
								country: { required:locationcountry_err_msg } 
								
						}
				});
		});			
});


	
	
	
	/********************** Service Form Validations ****************************/
jQuery(document).bind('ready ajaxComplete', function(){
	var ajax_url = header_object.plugin_path;	
	var servicetitle_err_msg = admin_validation_err_msg.servicetitle_err_msg;	
	var servicedescription_err_msg = admin_validation_err_msg.servicedescription_err_msg;	
	var serviceprice_err_msg = admin_validation_err_msg.serviceprice_err_msg;
	var servicepricedigit_err_msg = admin_validation_err_msg.servicepricedigit_err_msg;
	var servicecategory_err_msg = admin_validation_err_msg.servicecategory_err_msg;
	var servicehrsrange_err_msg = admin_validation_err_msg.servicehrsrange_err_msg;
	var serviceminsrange_err_msg = admin_validation_err_msg.serviceminsrange_err_msg;
	var servicemins_err_msg = admin_validation_err_msg.servicemins_err_msg;
	var servicenumpatt_err_msg = admin_validation_err_msg.servicenumpatt_err_msg;
	
	jQuery.validator.addMethod("numeric_pattern", function(value, element) {
	return this.optional(element) || /^[0-9]\d*(\.\d+)?$/.test(value);
	}, "Enter Only Alphabets");
	
	jQuery('#apt_create_service').validate({
		rules: {
			service_title: {
										required: true,
										remote: {
											url: ajax_url+"/assets/lib/service_ajax.php",
											type: "POST",
											async: true,
											data: {
											title:function() {
												return jQuery('#apt-service-title').val();
												},		
												action:'check_service_title'
											}
										},
				},
			service_description: {
										required: true
				},
			service_price: {
										required: true,
										number:true
				},	
		    offered_price: {
										number:true
				},
			service_category: {
										required:true
				},	
				
		   service_duration_hrs:{		numeric_pattern:true,
										range:function(element){ 
													 if (parseInt(jQuery("#service_duration_mins").val()) > 0){return [0,23];
													 }else {return [0,24];}
												}
										},	
		   service_duration_mins:{		required:function(element){
													if (parseInt(jQuery("#service_duration_hrs").val()) > 0){return false;
													}else { return true;
													}
										},			
										numeric_pattern:true,
										range:function(element){
												if (parseInt(jQuery("#service_duration_hrs").val()) > 0){return [0,59];}else {return [5,59];}
												}
										}					
	
			
			},
		messages: {
					service_title: { required:servicetitle_err_msg , remote : "Service Title Already exist!!!" }, 
					service_description: { required:servicedescription_err_msg }, 
					service_price: { required:serviceprice_err_msg , number:servicepricedigit_err_msg},
					offered_price: { number:servicepricedigit_err_msg},
					service_category: {required:servicecategory_err_msg},
					service_duration_hrs: {numeric_pattern:servicenumpatt_err_msg,range:servicehrsrange_err_msg},
					service_duration_mins:{required:servicemins_err_msg,numeric_pattern:servicenumpatt_err_msg,range:serviceminsrange_err_msg}
			}
	});
});			
/* Update Service Validation */
jQuery(document).bind('ready ajaxComplete', function(){
	var servicetitle_err_msg = admin_validation_err_msg.servicetitle_err_msg;	
	var servicedescription_err_msg = admin_validation_err_msg.servicedescription_err_msg;	
	var serviceprice_err_msg = admin_validation_err_msg.serviceprice_err_msg;
	var servicepricedigit_err_msg = admin_validation_err_msg.servicepricedigit_err_msg;
	var servicecategory_err_msg = admin_validation_err_msg.servicecategory_err_msg;
	var servicehrsrange_err_msg = admin_validation_err_msg.servicehrsrange_err_msg;
	var serviceminsrange_err_msg = admin_validation_err_msg.serviceminsrange_err_msg;
	var servicemins_err_msg = admin_validation_err_msg.servicemins_err_msg;
	var servicenumpatt_err_msg = admin_validation_err_msg.servicenumpatt_err_msg;
	
		jQuery('.apt_update_service').each(function(){
		var service_id = jQuery(this).data('sid');
				jQuery(this).validate({
					rules: {
						u_service_title: {
													required: true
							},
						u_service_desc: {
													required: true
							},
						u_service_price: {
													required: true,
													number:true
							},
						u_service_offeredprice: {
													number:true
						},	
						service_category: {
													required:true
							},	
						u_duration_hrs:{		numeric_pattern:true,
													range:function(element){ 
																 if (parseInt(jQuery('#apt-duration-mins'+service_id).val()) > 0){return [0,23];
																 }else {return [0,24];}
															}
													},	
						u_duration_mins:{		required:function(element){
															if (parseInt(jQuery('#apt-duration-hrs'+service_id).val()) > 0){return false;
															}else { return true;
															}
													},			
													numeric_pattern:true,
													range:function(element){
															if (parseInt(jQuery('#apt-duration-hrs'+service_id).val()) > 0){return [0,59];}else {return [5,59];}
													}
										}	
						},
					messages: {
								u_service_title: { required:servicetitle_err_msg }, 
								u_service_desc: { required:servicedescription_err_msg }, 
								u_service_price: { required:serviceprice_err_msg , number:servicepricedigit_err_msg},
								u_service_offeredprice: { number:servicepricedigit_err_msg},
								service_category: {required:servicecategory_err_msg},
								u_duration_hrs: {numeric_pattern:servicenumpatt_err_msg,range:servicehrsrange_err_msg},
								u_duration_mins:{required:servicemins_err_msg,numeric_pattern:servicenumpatt_err_msg,range:serviceminsrange_err_msg}
						}
				});
		});			
});

					/*********************** Settings Jquery **********************/
/****************************************************************************************************************
/* Partial Deposit Enable/Disable According To Payment Methods*/ 


 /* multilocation  #### */
 jQuery(document).ready(function(){
    jQuery("input:checkbox[name=ck]").change(function(){
	if(jQuery('#appointment_multi_location').prop('checked')==true){ var appointment_multi_location ='E';}
	else{
	var appointment_multi_location='D'
	jQuery('.ml-popup').addClass('is-visible');
	alert("Are you sure you want to disable it, This option will remove all the services during multiple locations setup.");
	;}
	
	});
});

 



/* Update/Save General Settings */
jQuery(document).on('click','#apt_save_general_settings',function(){
		jQuery('.apt-loading-main').show();
		var ajax_url = header_object.plugin_path;	
        var multilocation_st = header_object.multilocation_st;		
		if(jQuery('#appointment_taxvat_status').prop('checked')==true){ var appointment_taxvat_status ='E';}else{ var appointment_taxvat_status='D';}
		if(jQuery('#appointment_partial_deposit_status').prop('checked')==true){ var appointment_partial_deposit_status ='E';}else{ var appointment_partial_deposit_status='D';}
		if(jQuery('#appointment_multiple_booking_sameslot').prop('checked')==true){ var appointment_multiple_booking_sameslot ='E';}else{ var appointment_multiple_booking_sameslot='D';}
		if(jQuery('#appointment_appointment_auto_confirm').prop('checked')==true){ var appointment_appointment_auto_confirm ='E';}else{ var appointment_appointment_auto_confirm='D';}
		if(jQuery('#appointment_dayclosing_overlap').prop('checked')==true){ var appointment_dayclosing_overlap ='E';}else{ var appointment_dayclosing_overlap='D';}
		if(jQuery('#appointment_multi_location').prop('checked')==true){ var appointment_multi_location ='E';}else{ var appointment_multi_location='D';}
		
		if(jQuery('#booking_cart_description').prop('checked')==true){ var booking_cart_description ='E';}else{ var booking_cart_description='D';}
	
		if(jQuery('#appointment_cancelation_policy_status').prop('checked')==true){ var appointment_cancelation_policy_status ='E';}else{ var appointment_cancelation_policy_status='D';}
		
		if(jQuery('#appointment_allow_terms_and_conditions').prop('checked')==true){ var appointment_allow_terms_and_conditions ='E';}else{ var appointment_allow_terms_and_conditions='D';}
		
		if(jQuery('#appointment_allow_privacy_policy').prop('checked')==true){ var appointment_allow_privacy_policy ='E';}else{ var appointment_allow_privacy_policy='D';}
			
		if(jQuery('#appointment_zipcode_booking').prop('checked')==true){ 
			var appointment_zipcode_booking ='E';
			var appointment_booking_zipcodes_val = jQuery('#appointment_booking_zipcodes').val();
		}else{ 
			var appointment_zipcode_booking='D';
			var appointment_booking_zipcodes_val = jQuery('#appointment_booking_zipcodes_hidd').val();
		}

		
		var postdata = { appointment_booking_time_interval:jQuery('#appointment_booking_time_interval').val(),
						 appointment_multi_location:appointment_multi_location,
						 appointment_zipcode_booking:appointment_zipcode_booking,
						 appointment_booking_zipcodes:appointment_booking_zipcodes_val,
						 appointment_minimum_advance_booking:jQuery('#appointment_minimum_advance_booking').val(),
						 appointment_maximum_advance_booking:jQuery('#appointment_maximum_advance_booking').val(),
						 appointment_booking_padding_time:jQuery('#appointment_booking_padding_time').val(),
						 appointment_cancellation_buffer_time:jQuery('#appointment_cancellation_buffer_time').val(),
						 appointment_reschedule_buffer_time:jQuery('#appointment_reschedule_buffer_time').val(),
						 appointment_currency:jQuery('#appointment_currency').val(),
						 appointment_currency_symbol_position:jQuery('#appointment_currency_symbol_position').val(),
						 appointment_price_format_decimal_places:jQuery('#appointment_price_format_decimal_places').val(),
						 appointment_price_format_comma_separator:jQuery('#appointment_price_format_comma_separator').val(),
						 appointment_location_sortby:jQuery('#appointment_location_sortby').val(),
						 appointment_taxvat_status:appointment_taxvat_status,
						 appointment_taxvat_amount:jQuery('#appointment_taxvat_amount').val(),
						 appointment_taxvat_type:jQuery('input[name="appointment_taxvat_type"]:checked').val(),
						 appointment_partial_deposit_status:appointment_partial_deposit_status,
						 appointment_partial_deposit_type:jQuery('input[name="appointment_partial_deposit_type"]:checked').val(),
						 appointment_partial_deposit_amount:jQuery('#appointment_partial_deposit_amount').val(),
						 appointment_partial_deposit_message:jQuery('#appointment_partial_deposit_message').val(),
						 appointment_thankyou_page:jQuery('#appointment_thankyou_page').val(),
						 appointment_thankyou_page_rdtime:jQuery('#appointment_thankyou_page_rdtime').val(),
						 appointment_multiple_booking_sameslot:appointment_multiple_booking_sameslot,
						 appointment_slot_max_booking_limit:jQuery('#appointment_slot_max_booking_limit').val(),
						 appointment_appointment_auto_confirm:appointment_appointment_auto_confirm,
						 appointment_dayclosing_overlap:appointment_dayclosing_overlap,
						 booking_cart_description:booking_cart_description,
						 appointment_datepicker_format:jQuery('#appointment_datepicker_format').val(),
						 
						 appointment_cancelation_policy_status:appointment_cancelation_policy_status,
						 appointment_cancelation_policy_header:jQuery('#appointment_cancelation_policy_header').val(),	
						 appointment_cancelation_policy_text:jQuery('#appointment_cancelation_policy_text').val(),
						 
						 appointment_allow_terms_and_conditions:appointment_allow_terms_and_conditions,
						 appointment_allow_terms_and_conditions_url:jQuery('#appointment_allow_terms_and_conditions_url').val(),
						 
						 appointment_allow_privacy_policy:appointment_allow_privacy_policy,
						 appointment_allow_privacy_policy_url:jQuery('#appointment_allow_privacy_policy_url').val(),
						 
						 setting_action:'update_settings'						 
		} 
			jQuery.ajax({					
					url  : ajax_url+"/assets/lib/setting_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();	
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
						if(multilocation_st!=appointment_multi_location){
							window.location.reload();
						}	
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
			});
});	
/* Zipcode Wise Booking Enable/Disable */
jQuery(document).on('change','#appointment_zipcode_booking',function(){
	jQuery('#appointment_booking_zipcodesetting').toggle('slow');
	if(jQuery(this).prop('checked')==true){
		if(jQuery('#appointment_multi_location').prop('checked')==true){
			jQuery('#appointment_multi_location').attr('checked',false);			    
			jQuery('#appointment_multi_location').parent().prop('className','toggle btn btn-default off');	
		}	
	}
	
});
jQuery(document).on('change','#appointment_multi_location',function(){
	if(jQuery(this).prop('checked')==true){
		if(jQuery('#appointment_zipcode_booking').prop('checked')==true){
			jQuery('#appointment_booking_zipcodesetting').toggle('slow');
			jQuery('#appointment_zipcode_booking').attr('checked',false);			    
			jQuery('#appointment_zipcode_booking').parent().prop('className','toggle btn btn-default off');	
		}	
	}
	
});
	
/* Update/Save Company Settings */
jQuery(document).on('click','#apt_save_company_settings',function(){
		jQuery('.apt-loading-main').show();
		
		

		var ajax_url = header_object.plugin_path;	
		var postdata = { appointment_company_name:jQuery('#appointment_company_name').val(),				
						 appointment_company_email:jQuery('#appointment_company_email').val(),
						 appointment_company_address:jQuery('#appointment_company_address').val(),
						 appointment_company_city:jQuery('#appointment_company_city').val(),
						 appointment_company_state:jQuery('#appointment_company_state').val(),
						 appointment_company_zip:jQuery('#appointment_company_zip').val(),
						 appointment_company_country:jQuery('#appointment_company_country').val(),
						 appointment_company_logo:jQuery('#bdcsuploadedimg').val(),
						 appointment_company_country_code:jQuery('#appointment_company_country_code').val(),
						 appointment_company_phone:jQuery('#appointment_company_phone').val(),
						 default_company_country_flag:jQuery('.default_company_country_flag').val(),
						 
						 setting_action:'update_settings'						 
		}


			jQuery.ajax({					
					url  : ajax_url+"/assets/lib/setting_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
						 location.reload(); 
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
			});
});	

/** Delete Company Image **/
/*** Common Code For Remove Image **/
jQuery(document).on('click','.apt_delete_companyimage',function(){
		var ajax_url = header_object.plugin_path;
		var site_url = header_object.site_url;
		var defaultmedia = header_object.defaultmedia;		
		var mediapath = jQuery(this).data('mediapath');
		var defaultmedia_fullpath = defaultmedia+'company.png';
		jQuery('.apt-loading-main').show();
		jQuery('.close_delete_popup').trigger('click');
		var postdata = { mediapath:mediapath,
						 setting_action:'delete_company_image' }
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/setting_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {						
						jQuery('.apt-loading-main').hide();
						jQuery('#bdcsuploadedimg').val('');
						jQuery('#bdcslocimage').attr('src',defaultmedia_fullpath);
						jQuery('.apt_remove_image').hide();	
						location.reload();
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
		});
});
/* Update/Save Appearance Settings */
jQuery(document).on('click','#apt_save_appearance_settings',function(){
		jQuery('.apt-loading-main').show();
		var ajax_url = header_object.plugin_path;		
		var reviews_st = header_object.reviews_st;
		var bwid = jQuery('input[name="bwid"]').val();		
		if(jQuery('#appointment_show_provider').prop('checked')==true){ var appointment_show_provider ='E';}else{ var appointment_show_provider='D';}
		if(jQuery('#appointment_show_provider_avatars').prop('checked')==true){ var appointment_show_provider_avatars ='E';}else{ var appointment_show_provider_avatars='D';}
		if(jQuery('#appointment_show_services').prop('checked')==true){ var appointment_show_services ='E';}else{ var appointment_show_services='D';}
		if(jQuery('#appointment_show_service_desc').prop('checked')==true){ var appointment_show_service_desc ='E';}else{ var appointment_show_service_desc='D';}
		if(jQuery('#appointment_show_coupons').prop('checked')==true){ var appointment_show_coupons ='E';}else{ var appointment_show_coupons='D';}
		if(jQuery('#appointment_hide_booked_slot').prop('checked')==true){ var appointment_hide_booked_slot ='E';}else{ var appointment_hide_booked_slot='D';}
		if(jQuery('#appointment_guest_user_checkout').prop('checked')==true){ var appointment_guest_user_checkout ='E';}else{ var appointment_guest_user_checkout='D';}
		if(jQuery('#appointment_cart').prop('checked')==true){ var appointment_cart ='E';}else{ var appointment_cart='D';}
		if(jQuery('#appointment_reviews_status').prop('checked')==true){ var appointment_reviews_status ='E';}else{ var appointment_reviews_status='D';}
		if(jQuery('#appointment_auto_confirm_reviews').prop('checked')==true){ var appointment_auto_confirm_reviews ='E';}else{ var appointment_auto_confirm_reviews='D';}
		var appointment_frontend_custom_css = jQuery('#appointment_frontend_custom_css').val();
		
		var postdata = { appointment_primary_color:jQuery('#appointment_primary_color').val(),
						 appointment_secondary_color:jQuery('#appointment_secondary_color').val(),
						 appointment_text_color:jQuery('#appointment_text_color').val(),
						 appointment_bg_text_color:jQuery('#appointment_bg_text_color').val(),
						 appointment_admin_color_primary:jQuery('#appointment_admin_color_primary').val(),		 
						 appointment_admin_color_secondary:jQuery('#appointment_admin_color_secondary').val(),		 
						 appointment_admin_color_text:jQuery('#appointment_admin_color_text').val(),
						 appointment_admin_color_bg_text:jQuery('#appointment_admin_color_bg_text').val(),		 
						 appointment_show_provider:appointment_show_provider,						 
						 appointment_show_provider_avatars:appointment_show_provider_avatars,		 
						 appointment_show_services:appointment_show_services,						 
						 appointment_show_service_desc:appointment_show_service_desc,				 
						 appointment_show_coupons:appointment_show_coupons,						 
						 appointment_hide_booked_slot:appointment_hide_booked_slot,				 
						 appointment_guest_user_checkout:appointment_guest_user_checkout,		 
						 appointment_cart:appointment_cart,						 
						 appointment_max_cartitem_limit:jQuery('#appointment_max_cartitem_limit').val(),
						 appointment_reviews_status:appointment_reviews_status,					 
						 appointment_auto_confirm_reviews:appointment_auto_confirm_reviews,
						 appointmentappointment_frontend_custom_css:appointment_frontend_custom_css,
						 bwid:bwid,	
						 appointment_frontend_loader:jQuery('#appointment_frontend_loader').val(),					 
						 setting_action:'update_settings'						 
		}
			jQuery.ajax({					
					url  : ajax_url+"/assets/lib/setting_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
						if(reviews_st!=appointment_reviews_status){window.location.reload();}
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
			});
});	
/* Update/Save Payment Settings */
jQuery(document).on('click','#apt_save_payment_settings',function(){
		jQuery('.apt-loading-main').show();
		var ajax_url = header_object.plugin_path;		
		if(jQuery('#appointment_payment_gateways_status').prop('checked')==true){ var appointment_payment_gateways_status ='E';}else{ var appointment_payment_gateways_status='D';}
		if(jQuery('#appointment_locally_payment_status').prop('checked')==true){ var appointment_locally_payment_status ='E';}else{ var appointment_locally_payment_status='D';}
		if(jQuery('#appointment_payment_method_Paypal').prop('checked')==true){ var appointment_payment_method_Paypal ='E';}else{ var appointment_payment_method_Paypal='D';}
		if(jQuery('#appointment_paypal_guest_checkout').prop('checked')==true){ var appointment_paypal_guest_checkout ='E';}else{ var appointment_paypal_guest_checkout='D';}
		if(jQuery('#appointment_paypal_testing_mode').prop('checked')==true){ var appointment_paypal_testing_mode ='E';}else{ var appointment_paypal_testing_mode='D';}
		if(jQuery('#appointment_payment_method_Stripe').prop('checked')==true){ var appointment_payment_method_Stripe ='E';}else{ var appointment_payment_method_Stripe='D';}
		if(jQuery('#appointment_payment_method_Authorizenet').prop('checked')==true){ var appointment_payment_method_Authorizenet ='E';}else{ var appointment_payment_method_Authorizenet='D';}
		if(jQuery('#appointment_authorizenet_testing_mode').prop('checked')==true){ var appointment_authorizenet_testing_mode ='E';}else{ var appointment_authorizenet_testing_mode='D';}
		if(jQuery('#appointment_payment_method_2Checkout').prop('checked')==true){ var appointment_payment_method_2Checkout ='E';}else{ var appointment_payment_method_2Checkout='D';}
		if(jQuery('#appointment_2checkout_testing_mode').prop('checked')==true){ var appointment_2checkout_testing_mode ='E';}else{ var appointment_2checkout_testing_mode='D';}
		if(jQuery('#appointment_payment_method_Payumoney').prop('checked')==true){ var appointment_payment_method_Payumoney ='E';}else{ var appointment_payment_method_Payumoney='D';}
		if(jQuery('#appointment_payment_method_Paytm').prop('checked')==true){ var appointment_payment_method_Paytm ='E';}else{ var appointment_payment_method_Paytm='D';}
		if(jQuery('#appointment_paytm_testing_mode').prop('checked')==true){ var appointment_paytm_testing_mode ='E';}else{ var appointment_paytm_testing_mode='D';}
		
		
		var postdata = { appointment_payment_gateways_status:appointment_payment_gateways_status,
						 appointment_locally_payment_status:appointment_locally_payment_status,
						 appointment_payment_method_Paypal:appointment_payment_method_Paypal,
						 appointment_paypal_guest_checkout:appointment_paypal_guest_checkout,
						 appointment_paypal_testing_mode:appointment_paypal_testing_mode,
						 appointment_payment_method_Stripe:appointment_payment_method_Stripe,
						 appointment_payment_method_Authorizenet:appointment_payment_method_Authorizenet,
						 appointment_authorizenet_testing_mode:appointment_authorizenet_testing_mode,
						 appointment_payment_method_2Checkout:appointment_payment_method_2Checkout,
						 appointment_payment_method_Payumoney:appointment_payment_method_Payumoney,
						 appointment_payment_method_Paytm:appointment_payment_method_Paytm,
						 appointment_paytm_testing_mode:appointment_paytm_testing_mode,
						 appointment_2checkout_testing_mode:appointment_2checkout_testing_mode,
						 appointment_paypal_api_username:jQuery('#appointment_paypal_api_username').val(),
						 appointment_paypal_api_password:jQuery('#appointment_paypal_api_password').val(),
						 appointment_paypal_api_signature:jQuery('#appointment_paypal_api_signature').val(),
						 appointment_stripe_secretKey:jQuery('#appointment_stripe_secretKey').val(),
						 appointment_stripe_publishableKey:jQuery('#appointment_stripe_publishableKey').val(),
						 appointment_authorizenet_api_loginid:jQuery('#appointment_authorizenet_api_loginid').val(),
						 appointment_authorizenet_transaction_key:jQuery('#appointment_authorizenet_transaction_key').val(),
						 appointment_authorizenet_transaction_key:jQuery('#appointment_authorizenet_transaction_key').val(),
						 appointment_2checkout_publishablekey:jQuery('#appointment_2checkout_publishablekey').val(),
						 appointment_2checkout_privateKey:jQuery('#appointment_2checkout_privateKey').val(),
						 appointment_2checkout_sellerid:jQuery('#appointment_2checkout_sellerid').val(),
						 appointment_payumoney_merchantkey:jQuery('#appointment_payumoney_merchantkey').val(),
						 appointment_payumoney_saltkey:jQuery('#appointment_payumoney_saltkey').val(),
						 appointment_paytm_merchantkey:jQuery('#appointment_paytm_merchantkey').val(),
						 appointment_paytm_merchantid:jQuery('#appointment_paytm_merchantid').val(),
						 appointment_paytm_website:jQuery('#appointment_paytm_website').val(),
						 appointment_paytm_channelid:jQuery('#appointment_paytm_channelid').val(),
						 appointment_paytm_industryid:jQuery('#appointment_paytm_industryid').val(),
						 setting_action:'update_settings'						 
		}
			jQuery.ajax({					
					url  : ajax_url+"/assets/lib/setting_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();	
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();						
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
			});
});	
/* Update/Save Email Settings */
jQuery(document).on('click','#apt_save_email_settings',function(){
		jQuery('.apt-loading-main').show();
		var ajax_url = header_object.plugin_path;		
		if(jQuery('#appointment_admin_email_notification_status').prop('checked')==true){ var appointment_admin_email_notification_status ='E';}else{ var appointment_admin_email_notification_status='D';}
		if(jQuery('#appointment_manager_email_notification_status').prop('checked')==true){ var appointment_manager_email_notification_status ='E';}else{ var appointment_manager_email_notification_status='D';}
		if(jQuery('#appointment_service_provider_email_notification_status').prop('checked')==true){ var appointment_service_provider_email_notification_status ='E';}else{ var appointment_service_provider_email_notification_status='D';}
		if(jQuery('#appointment_client_email_notification_status').prop('checked')==true){ var appointment_client_email_notification_status ='E';}else{ var appointment_client_email_notification_status='D';}
						
		var postdata = { appointment_admin_email_notification_status:appointment_admin_email_notification_status,
						 appointment_manager_email_notification_status:appointment_manager_email_notification_status,
						 appointment_service_provider_email_notification_status:appointment_service_provider_email_notification_status,
						 appointment_client_email_notification_status:appointment_client_email_notification_status,
						 appointment_email_sender_address:jQuery('#appointment_email_sender_address').val(),
						 appointment_email_sender_name:jQuery('#appointment_email_sender_name').val(),
						 appointment_email_reminder_buffer:jQuery('#appointment_email_reminder_buffer').val(),
						 setting_action:'update_settings'						 
		}
			jQuery.ajax({					
					url  : ajax_url+"/assets/lib/setting_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();		
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
			});
});	
/* Update Email Template **/
jQuery(document).on('click','.apt_save_emailtemplate',function(){
		jQuery('.apt-loading-main').show();
		var ajax_url = header_object.plugin_path;		
		var template_id = jQuery(this).data('eid');		
		var email_subject = jQuery('input[name="email_subject'+template_id+'"]').val();
		var email_message = jQuery('textarea[name="email_message'+template_id+'"]').val();
		jQuery('#email_subject_label'+template_id).text(email_subject);
		var postdata = { template_id:template_id,
						 email_subject:email_subject,
						 email_message:email_message,
						 setting_action:'update_emailtemplate'						 
		}
			jQuery.ajax({					
					url  : ajax_url+"/assets/lib/setting_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();		
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
			});
});	

/* Update Email Template Status */
jQuery(document).on('change','.apt_update_emailstatus',function(){
		jQuery('.apt-loading-main').show();
		var ajax_url = header_object.plugin_path;		
		var template_id = jQuery(this).data('eid');
		if(jQuery(this).prop('checked')==true){ var email_status ='e';}else{ var email_status='d';}		
		var postdata = { template_id:template_id,
						 email_status:email_status,
						 setting_action:'update_emailtemplate_status'						 
		}
			jQuery.ajax({					
					url  : ajax_url+"/assets/lib/setting_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();	
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();						
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
			});
});


/* Update/Save SMS Settings */
jQuery(document).on('click','#apt_update_smssettings',function(){
	
	
		if(jQuery('#appointment_sms_noti_twilio').prop('checked')==true){ var appointment_sms_noti_twilio ='E';}else{ var appointment_sms_noti_twilio='D';}
		if(jQuery('#appointment_twilio_admin_sms_notification_status').prop('checked')==true){ var appointment_twilio_admin_sms_notification_status ='E';}else{ var appointment_twilio_admin_sms_notification_status='D';}
		
		if(jQuery('#appointment_sms_noti_plivo').prop('checked')==true){ var appointment_sms_noti_plivo ='E';}else{ var appointment_sms_noti_plivo='D';}		
		if(jQuery('#appointment_plivo_admin_sms_notification_status').prop('checked')==true){ var appointment_plivo_admin_sms_notification_status ='E';}else{ var appointment_plivo_admin_sms_notification_status='D';}
		
		if(jQuery('#appointment_sms_noti_nexmo').prop('checked')==true){ var appointment_sms_noti_nexmo ='E';}else{ var appointment_sms_noti_nexmo='D';}
		if(jQuery('#appointment_nexmo_send_sms_admin_status').prop('checked')==true){ var appointment_nexmo_send_sms_admin_status ='E';}else{ var appointment_nexmo_send_sms_admin_status='D';}
		
		if(jQuery('#appointment_sms_noti_textlocal').prop('checked')==true){ var appointment_sms_noti_textlocal ='E';}else{ var appointment_sms_noti_textlocal='D';}
		if(jQuery('#appointment_textlocal_admin_sms_notification_status').prop('checked')==true){ var appointment_textlocal_admin_sms_notification_status ='E';}else{ var appointment_textlocal_admin_sms_notification_status='D';}
		
		/* Validate SMS Notification Settings Form */
		var twilliosid_err_msg = admin_validation_err_msg.twilliosid_err_msg;	
		var twillioauthtoken_err_msg = admin_validation_err_msg.twillioauthtoken_err_msg;	
		var twilliosendernum_err_msg = admin_validation_err_msg.twilliosendernum_err_msg;	
		var twillioadminnum_err_msg = admin_validation_err_msg.twillioadminnum_err_msg;	
		
		var plivosid_err_msg = admin_validation_err_msg.plivosid_err_msg;	
		var plivoauthtoken_err_msg = admin_validation_err_msg.plivoauthtoken_err_msg;	
		var plivosendernum_err_msg = admin_validation_err_msg.plivosendernum_err_msg;	
		var plivoadminnum_err_msg = admin_validation_err_msg.plivoadminnum_err_msg;	
		
		var nexmoapi_err_msg = admin_validation_err_msg.nexmoapi_err_msg;	
		var nexmoapisecert_err_msg = admin_validation_err_msg.nexmoapisecert_err_msg;	
		var nexmofromnum_err_msg = admin_validation_err_msg.nexmofromnum_err_msg;	
		var nexmoadminnum_err_msg = admin_validation_err_msg.nexmoadminnum_err_msg;
		
		if(appointment_sms_noti_twilio=="E"){	
			jQuery(".apt-sms-reminder").validate();
				jQuery("#appointment_twilio_sid").rules("add", { required: true,messages: { required: twilliosid_err_msg}});
				jQuery("#appointment_twilio_auth_token").rules("add", { required: true,messages: { required: twillioauthtoken_err_msg}});
				jQuery("#appointment_twilio_number").rules("add", { required: true,messages: { required: twilliosendernum_err_msg}});
				if(appointment_twilio_admin_sms_notification_status=="E"){	
				jQuery("#appointment_twilio_admin_phone_no").rules("add", { required: true,messages: { required: twillioadminnum_err_msg}});
			}
		}		
		
		if(appointment_sms_noti_plivo=="E"){	
			jQuery(".apt-sms-reminder").validate();
				jQuery("#appointment_plivo_sid").rules("add", { required: true,messages: { required: plivosid_err_msg}});
				jQuery("#appointment_plivo_auth_token").rules("add", { required: true,messages: { required: plivoauthtoken_err_msg}});
				jQuery("#appointment_plivo_number").rules("add", { required: true,messages: { required: plivosendernum_err_msg}});
				if(appointment_plivo_admin_sms_notification_status=="E"){	
				jQuery("#appointment_plivo_admin_phone_no").rules("add", { required: true,messages: { required: plivoadminnum_err_msg}});
			}
		}
		
		if(appointment_sms_noti_nexmo=="E"){	
			jQuery(".apt-sms-reminder").validate();
				jQuery("#appointment_nexmo_apikey").rules("add", { required: true,messages: { required: nexmoapi_err_msg}});
				jQuery("#appointment_nexmo_api_secret").rules("add", { required: true,messages: { required: nexmoapisecert_err_msg}});
				jQuery("#appointment_nexmo_form").rules("add", { required: true,messages: { required: nexmofromnum_err_msg}});
				if(appointment_nexmo_send_sms_admin_status=="E"){	
				jQuery("#appointment_nexmo_admin_phone_no").rules("add", { required: true,messages: { required: nexmoadminnum_err_msg}});
			}
		}
		/* Validate SMS Notification Settings Form End */ 	
	
	
	if(jQuery('.apt-sms-reminder').valid()) {
		
		jQuery('.apt-loading-main').show(); 
		var ajax_url = header_object.plugin_path;	
		
		if(jQuery('#appointment_sms_reminder_status').prop('checked')==true){ var appointment_sms_reminder_status ='E';}else{ var appointment_sms_reminder_status='D';}
		/* Twillio */
		if(jQuery('#appointment_twilio_client_sms_notification_status').prop('checked')==true){ var appointment_twilio_client_sms_notification_status ='E';}else{ var appointment_twilio_client_sms_notification_status='D';}
		if(jQuery('#appointment_twilio_service_provider_sms_notification_status').prop('checked')==true){ var appointment_twilio_service_provider_sms_notification_status ='E';}else{ var appointment_twilio_service_provider_sms_notification_status='D';}		
		
		/* Plivo */		
		if(jQuery('#appointment_plivo_service_provider_sms_notification_status').prop('checked')==true){ var appointment_plivo_service_provider_sms_notification_status ='E';}else{ var appointment_plivo_service_provider_sms_notification_status='D';}
		if(jQuery('#appointment_plivo_client_sms_notification_status').prop('checked')==true){ var appointment_plivo_client_sms_notification_status ='E';}else{ var appointment_plivo_client_sms_notification_status='D';}		
		
		/* Nexmo */		
		if(jQuery('#appointment_nexmo_send_sms_client_status').prop('checked')==true){ var appointment_nexmo_send_sms_client_status ='E';}else{ var appointment_nexmo_send_sms_client_status='D';}
		if(jQuery('#appointment_nexmo_send_sms_sp_status').prop('checked')==true){ var appointment_nexmo_send_sms_sp_status ='E';}else{ var appointment_nexmo_send_sms_sp_status='D';}
	
		/* Textlocal */
		if(jQuery('#appointment_textlocal_service_provider_sms_notification_status').prop('checked')==true){ var appointment_textlocal_service_provider_sms_notification_status ='E';}else{ var appointment_textlocal_service_provider_sms_notification_status='D';}
		if(jQuery('#appointment_textlocal_client_sms_notification_status').prop('checked')==true){ var appointment_textlocal_client_sms_notification_status ='E';}else{ var appointment_textlocal_client_sms_notification_status='D';}
		if(jQuery('#appointment_textlocal_admin_sms_notification_status').prop('checked')==true){ var appointment_textlocal_admin_sms_notification_status ='E';}else{ var appointment_textlocal_admin_sms_notification_status='D';}	
		
		var postdata = {
						appointment_sms_reminder_status:appointment_sms_reminder_status,
						/* Twillio */
						appointment_sms_noti_twilio:appointment_sms_noti_twilio,
						appointment_twilio_number:jQuery('#appointment_twilio_number').val(),
						appointment_twilio_sid:jQuery('#appointment_twilio_sid').val(),
						appointment_twilio_auth_token:jQuery('#appointment_twilio_auth_token').val(),
						appointment_twilio_client_sms_notification_status:appointment_twilio_client_sms_notification_status,
						appointment_twilio_service_provider_sms_notification_status:appointment_twilio_service_provider_sms_notification_status,
						appointment_twilio_admin_sms_notification_status:appointment_twilio_admin_sms_notification_status,
						appointment_twilio_admin_phone_no:jQuery('#appointment_twilio_admin_phone_no').val(),
						appointment_twilio_ccode:jQuery('#appointment_twilio_ccode').val(),
						appointment_twilio_ccode_alph:jQuery('#appointment_twilio_ccode_alph').val(),
						/* Plivo */
						appointment_sms_noti_plivo:appointment_sms_noti_plivo,
						appointment_plivo_number:jQuery('#appointment_plivo_number').val(),
						appointment_plivo_sid:jQuery('#appointment_plivo_sid').val(),
						appointment_plivo_auth_token:jQuery('#appointment_plivo_auth_token').val(),
						appointment_plivo_service_provider_sms_notification_status:appointment_plivo_service_provider_sms_notification_status,
						appointment_plivo_client_sms_notification_status:appointment_plivo_client_sms_notification_status,
						appointment_plivo_admin_sms_notification_status:appointment_plivo_admin_sms_notification_status,
						appointment_plivo_admin_phone_no:jQuery('#appointment_plivo_admin_phone_no').val(),
						appointment_plivo_ccode:jQuery('#appointment_plivo_ccode').val(),
						appointment_plivo_ccode_alph:jQuery('#appointment_plivo_ccode_alph').val(),
						/* Nexmo */
						appointment_sms_noti_nexmo:appointment_sms_noti_nexmo,
						appointment_nexmo_apikey:jQuery('#appointment_nexmo_apikey').val(),
						appointment_nexmo_api_secret:jQuery('#appointment_nexmo_api_secret').val(),
						appointment_nexmo_form:jQuery('#appointment_nexmo_form').val(),
						appointment_nexmo_send_sms_client_status:appointment_nexmo_send_sms_client_status,
						appointment_nexmo_send_sms_sp_status:appointment_nexmo_send_sms_sp_status,
						appointment_nexmo_send_sms_admin_status:appointment_nexmo_send_sms_admin_status,
						appointment_nexmo_admin_phone_no:jQuery('#appointment_nexmo_admin_phone_no').val(),
						appointment_nexmo_ccode:jQuery('#appointment_nexmo_ccode').val(),
						appointment_nexmo_ccode_alph:jQuery('#appointment_nexmo_ccode_alph').val(),
						/* Textlocal */
						appointment_sms_noti_textlocal:appointment_sms_noti_textlocal,
						appointment_textlocal_apikey:jQuery('#appointment_textlocal_apikey').val(),
						appointment_textlocal_sender:jQuery('#appointment_textlocal_sender').val(),
						appointment_textlocal_service_provider_sms_notification_status:appointment_textlocal_service_provider_sms_notification_status,
						appointment_textlocal_client_sms_notification_status:appointment_textlocal_client_sms_notification_status,
						appointment_textlocal_admin_sms_notification_status:appointment_textlocal_admin_sms_notification_status,
						appointment_textlocal_admin_phone_no:jQuery('#appointment_textlocal_admin_phone_no').val(),
						appointment_textlocal_ccode:jQuery('#appointment_textlocal_ccode').val(),
						appointment_textlocal_ccode_alph:jQuery('#appointment_textlocal_ccode_alph').val(),
						
						setting_action:'update_settings'						 
		}
		
			jQuery.ajax({					
					url  : ajax_url+"/assets/lib/setting_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();
						jQuery('.mainheader_message_inner').show();	
						appointment_hide_success_msg();	
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
			});
	}		
});	

/* Update/Save SMS Settings */
jQuery(document).on('click','#apt_save_sms_settings',function(){
		jQuery('.apt-loading-main').show();

		var ajax_url = header_object.plugin_path;
		if(jQuery('#appointment_admin_sms_notification_status').prop('checked')==true){ var appointment_admin_sms_notification_status ='E';}else{ var appointment_admin_sms_notification_status='D';}
		if(jQuery('#appointment_service_provider_sms_notification_status').prop('checked')==true){ var appointment_service_provider_sms_notification_status ='E';}else{ var appointment_service_provider_sms_notification_status='D';}
		if(jQuery('#appointment_client_sms_notification_status').prop('checked')==true){ var appointment_client_sms_notification_status ='E';}else{ var appointment_client_sms_notification_status='D';}
						
		var postdata = { appointment_admin_sms_notification_status:appointment_admin_sms_notification_status,
						 appointment_service_provider_sms_notification_status:appointment_service_provider_sms_notification_status,
						 appointment_client_sms_notification_status:appointment_client_sms_notification_status,
						 appointment_sms_sender_address:jQuery('#appointment_sms_sender_address').val(),
						 appointment_sms_sender_name:jQuery('#appointment_sms_sender_name').val(),
						 appointment_sms_reminder_buffer:jQuery('#appointment_sms_reminder_buffer').val(),
						 setting_action:'update_settings'						 
		}

		
		/* var postdata = { appointment_twilio_admin_sms_notification_status:appointment_twilio_admin_sms_notification_status,
						 appointment_twilio_service_provider_sms_notification_status:appointment_twilio_service_provider_sms_notification_status,
						 appointment_twilio_client_sms_notification_status:appointment_twilio_client_sms_notification_status,
						 
						 appointment_plivo_admin_sms_notification_status:appointment_plivo_admin_sms_notification_status,
						 appointment_plivo_service_provider_sms_notification_status:appointment_plivo_service_provider_sms_notification_status,
						 appointment_plivo_client_sms_notification_status:appointment_plivo_client_sms_notification_status,
						 
						 
						 appointment_sms_sender_address:jQuery('#appointment_sms_sender_address').val(),
						 appointment_sms_sender_name:jQuery('#appointment_sms_sender_name').val(),
						 appointment_sms_reminder_buffer:jQuery('#appointment_sms_reminder_buffer').val(),
						 setting_action:'update_settings'						 
		} */
		
			jQuery.ajax({					
					url  : ajax_url+"/assets/lib/setting_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();	
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
			});
});	
/* Update SMS Template **/
jQuery(document).on('click','.apt_save_smstemplate',function(){
		jQuery('.apt-loading-main').show();
		var ajax_url = header_object.plugin_path;		
		var template_id = jQuery(this).data('eid');		
		var sms_message = jQuery('textarea[name="sms_message'+template_id+'"]').val();
		var postdata = { template_id:template_id,
						 sms_message:sms_message,
						 setting_action:'update_smstemplate'						 
		}
			jQuery.ajax({					
					url  : ajax_url+"/assets/lib/setting_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();	
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
			});
});	

/* Update SMS Template Status */
jQuery(document).on('change','.apt_update_smsstatus',function(){
		jQuery('.apt-loading-main').show();
		var ajax_url = header_object.plugin_path;		
		var template_id = jQuery(this).data('eid');
		if(jQuery(this).prop('checked')==true){ var sms_status ='e';}else{ var sms_status='d';}		
		var postdata = { template_id:template_id,
						 sms_status:sms_status,
						 setting_action:'update_smstemplate_status'						 
		}
			jQuery.ajax({					
					url  : ajax_url+"/assets/lib/setting_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();	
						jQuery('.mainheader_message_inner').show();	
						appointment_hide_success_msg();
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
			});
});




/* Create Coupons */
jQuery(document).on('click','#apt_create_coupon',function(){
		if(!jQuery('#apt_create_coupon_form').valid()){
			return false;
		}
		jQuery('.apt-loading-main').show();
		var ajax_url = header_object.plugin_path;		
		var coupon_code = jQuery('#apt_coupon_code').val();
		var coupon_type = jQuery('#apt_coupon_type').val();
		var coupon_value = jQuery('#apt_coupon_value').val();
		var coupon_limit = jQuery('#apt_coupon_limit').val();
		var coupon_expiry = jQuery("#apt_coupon_expiry").val();
		var bwid 	= jQuery('input[name="bwid"]').val();
		var postdata = { coupon_code:coupon_code,
						 coupon_type:coupon_type,
						 coupon_value:coupon_value,
						 coupon_limit:coupon_limit,
						 coupon_expiry:coupon_expiry,
						 bwid:bwid,
						 setting_action:'create_coupon'						 
		}
			jQuery.ajax({					
					url  : ajax_url+"/assets/lib/setting_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						
						//jQuery('a#apt_promocode_list').trigger('click');
						jQuery('.apt-loading-main').hide();	
						jQuery('#apt_coupon_code').val('');
						jQuery('#apt_coupon_type').val('');
						jQuery('#apt_coupon_value').val('');
						jQuery('#apt_coupon_limit').val('');
						jQuery("#apt_coupon_expiry").val('');
						jQuery('.apt_promocode_list').addClass('active');
						jQuery('#apt_promocode_list').addClass('active in');
						jQuery('.apt_addnew_promocode').removeClass('active');
						jQuery('#apt_addnew_promocode').removeClass('active in');
						jQuery('#coupon_list .odd').hide();
						jQuery('#coupon_list').append(response);
						jQuery('.mainheader_message_inner').show();	
						appointment_hide_success_msg();						
						location.reload();						
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
			});
});

/* Update Coupon Status */
jQuery(document).on('change','.apt_update_couponstatus',function(){
		jQuery('.apt-loading-main').show();
		var ajax_url = header_object.plugin_path;		
		var coupon_id = jQuery(this).data('cid');
		if(jQuery(this).prop('checked')==true){ var coupon_status ='e';}else{ var coupon_status='d';}		
		var postdata = { coupon_id:coupon_id,
						 coupon_status:coupon_status,
						 setting_action:'update_coupon_status'						 
		}
			jQuery.ajax({					
					url  : ajax_url+"/assets/lib/setting_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();		
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
			});
});
/* Delete Coupon */
jQuery(document).on('click','.apt_delete_coupon',function(){
		jQuery('.apt-loading-main').show();
		var ajax_url = header_object.plugin_path;		
		var coupon_id = jQuery(this).data('id');
		var postdata = { coupon_id:coupon_id,
						 setting_action:'delete_coupon'						 
		}
			jQuery.ajax({					
					url  : ajax_url+"/assets/lib/setting_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();	
						jQuery('#coupon_detail'+coupon_id).fadeOut('slow');
						jQuery('#coupon_detail'+coupon_id).remove();
						if(jQuery('#coupon_list tr').size()==0){
							jQuery('#coupon_list').append('<tr class="odd"><td class="dataTables_empty" colspan="5" valign="top">No data available in table</td></tr>');	
						}
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
			});
});
/* Update Coupon Information */
jQuery(document).on('click','.apt_update_promocode',function(){
		var coupon_id= jQuery(this).data('cid');	
		jQuery('.apt_promocode_list').removeClass('active');
		jQuery('#apt_promocode_list').removeClass('active in');
		jQuery('.apt_update_promocode_tab a').removeClass('hide-div');
		jQuery('.apt_update_promocode_tab').addClass('active');
		jQuery('#apt_update_promocode').addClass('active in');
		jQuery('.apt_coupon_update_info').each(function(){
			jQuery(this).addClass('hide-div');
		});
		jQuery('#apt_coupon_update_info'+coupon_id).removeClass('hide-div');
});
jQuery(document).on('click','.apt_update_coupon_info',function(){
		var coupon_id = jQuery(this).attr('id');
		var bwid = jQuery('input[name="bwid"]').val();
		if(!jQuery('#apt_update_promocode_info'+coupon_id).valid()){
			return false;
		}
		jQuery('.apt-loading-main').show();
		var ajax_url = header_object.plugin_path;
		var coupon_code = jQuery('#apt_uc_code'+coupon_id).val();
		var coupon_type = jQuery('#apt_uc_type'+coupon_id).val();
		var coupon_value = jQuery('#apt_uc_value'+coupon_id).val();
		var coupon_limit = jQuery('#apt_uc_limit'+coupon_id).val();
		var coupon_expiry = jQuery('#apt_uc_expiry'+coupon_id).val();
		var coupon_status = jQuery('#apt_uc_status'+coupon_id).val();
		var postdata = { coupon_id:coupon_id,
						 coupon_code:coupon_code,
						 coupon_type:coupon_type,
						 coupon_value:coupon_value,
						 coupon_limit:coupon_limit,
						 coupon_expiry:coupon_expiry,
						 coupon_status:coupon_status,
						 bwid:bwid,
						 setting_action:'update_coupon'						 
		}
			jQuery.ajax({					
					url  : ajax_url+"/assets/lib/setting_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();	
						jQuery('.apt_promocode_list').addClass('active');
						jQuery('#apt_promocode_list').addClass('active in');
						jQuery('.apt_update_promocode_tab').removeClass('active');
						jQuery('.apt_update_promocode_tab a').addClass('hide-div');
						jQuery('#apt_update_promocode').removeClass('active in');
						jQuery('#coupon_detail'+coupon_id).remove();
						jQuery('#coupon_list').append(response);
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
												
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
			});
});
/* Coupon Form validations */
jQuery(document).on('ready ajaxComplete', function(){
		var cuponcode_err_msg = admin_validation_err_msg.cuponcode_err_msg;	
		var cuponvalue_err_msg = admin_validation_err_msg.cuponvalue_err_msg;	
		var cuponvalueinvalid_err_msg = admin_validation_err_msg.cuponvalueinvalid_err_msg;	
		var cuponlimit_err_msg = admin_validation_err_msg.cuponlimit_err_msg;	
		var cuponlimitinvalid_err_msg = admin_validation_err_msg.cuponlimitinvalid_err_msg;	
	/** Add New Coupon Validations **/
	jQuery('#apt_create_coupon_form').validate({
			rules:{
				apt_coupon_code:{required:true},
				apt_coupon_value:{required:true,number:true},
				apt_coupon_limit:{required:true,number:true},
			},
		messages:{	apt_coupon_code:{required:cuponcode_err_msg},
					apt_coupon_value:{required:cuponvalue_err_msg,number:cuponvalueinvalid_err_msg},apt_coupon_limit:{required:cuponlimit_err_msg,number:cuponlimitinvalid_err_msg}
				}
		});
	/** Update Coupon Validations **/
	jQuery('.apt_update_promocode_info').each(function(){
		jQuery(this).validate({
			rules:{
				apt_uc_code:{required:true},
				apt_uc_value:{required:true,number:true},
				apt_uc_limit:{required:true,number:true},
			},
		messages:{	apt_uc_code:{required:cuponcode_err_msg},
					apt_uc_value:{required:cuponvalue_err_msg,number:cuponvalueinvalid_err_msg},apt_uc_limit:{required:cuponlimit_err_msg,number:cuponlimitinvalid_err_msg}
				}
		});	
	});		
});

					/***********************Settings Jquery End Here **********************/

/******************************** Payments Sextion Jquery ****************************************/
/* Get Payments By Selected Range */
jQuery(document).on('click','.apt_payments_byrange',function(){
		jQuery('.apt-loading-main').show();
		var ajax_url = header_object.plugin_path;
		var payment_table = jQuery('#payments-details').DataTable();
		var payment_start = jQuery('#apt_reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
		var payment_end = jQuery('#apt_reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');
		var bwid = jQuery('input[name="bwid"]').val();
		var postdata = { payment_start:payment_start,
						 payment_end:payment_end,
						 bwid:bwid,
						 general_ajax_action:'get_payments_byrange'						 
		}
			jQuery.ajax({					
					url  : ajax_url+"/assets/lib/admin_general_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						payment_table.destroy();
						
						jQuery('.apt-loading-main').hide();	
						jQuery('#apt_payment_details').html(response);	
						jQuery('#payments-details').DataTable({
							dom: 'lfrtipB',							
							buttons: ['excelHtml5','pdfHtml5']
						});						
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
			});
});			 
/* Get All Locations Customer Registered/Guest */
jQuery(document).on('change','#apt_all_locations_payments',function(){
		jQuery('.apt-loading-main').show();
		var ajax_url = header_object.plugin_path;
		var bwid = jQuery('input[name="bwid"]').val();		
		if(jQuery(this).prop('checked')==true){ var alp ='Y';}else{ var alp='N';}	
		var postdata = { alp:alp,
						 general_ajax_action:'get_all_locations_payments',
						 bwid:bwid
		}
		jQuery.ajax({					
				url  : ajax_url+"/assets/lib/admin_general_ajax.php",					
				type : 'POST',					
				data : postdata,					
				dataType : 'html',					
				success  : function(response) {
					jQuery('.apt-loading-main').hide();
					window.location.reload();
				},
				error: function (xhr, ajaxOptions, thrownError) {
				}
		});
});   
   
			 /*********************** Payments Jquery End Here **********************/
			 
/******************************** Clients  Jquery End Here ****************************************/
/* Show Client Bookings for Registerd & Guest User Modal Content Both */
 jQuery(document).on('click','.apt_show_bookings',function(){
			jQuery('.apt-loading-main').show();
			var ajax_url = header_object.plugin_path;			
			var client_id = jQuery(this).attr('data-client_id');
			var method = jQuery(this).attr('data-method');
			var client_bookings = jQuery('#'+method+'-client-booking-details').DataTable();
			var postdata =  { 
				method:method,
				listing_client_id:client_id,
				general_ajax_action:'get_client_bookings'
			}
			jQuery.ajax({
				url  : ajax_url+"/assets/lib/admin_general_ajax.php",
				type : 'POST',
				data : postdata,
				dataType : 'html',
				success  : function(response) {
					client_bookings.destroy();
					jQuery('.apt-loading-main').hide();	
					jQuery('#apt_client_bookings'+method).html(response);
					jQuery('#'+method+'-client-booking-details').DataTable( {
						dom: 'frtipB',
						buttons: [
							'copyHtml5',
							'excelHtml5',
							'csvHtml5',
							'pdfHtml5'
						]
					});
					
				}	
			});
	 });	
	 
/* Delete Registered/Guest User & User Info Like Bookings,Payments,Order Client Info */   
 jQuery(document).on('click','.apt_delete_client' , function () {				
		jQuery('.apt-loading-main').show();
		var ajax_url = header_object.plugin_path;		
		var client_id = jQuery(this).data('client_id');		
		var method = jQuery(this).data('method');		
		var postdata =  { delete_id:client_id,
						  method:method,
						  general_ajax_action:'delete_'+method+'_client'						
		}		
		jQuery.ajax({				
			url  : ajax_url+"/assets/lib/admin_general_ajax.php",				
			type : 'POST',				
			data : postdata,				
			dataType : 'html',				
			success  : function(response) {	
				var otpostdata = { user_type:method,
				 general_ajax_action:'refresh_register_client_datatable'						 
				}
				jQuery.ajax({					
					url  : ajax_url+"/assets/lib/admin_general_ajax.php",					
					type : 'POST',					
					data : otpostdata,					
					dataType : 'html',					
					success  : function(otresponse) {
						jQuery('.apt-loading-main').hide();
						if(method=='registered'){
							jQuery('#registered-customers-listing').html(otresponse);
							jQuery('#registered-client-table').DataTable({
								dom: 'lfrtipB',								
								buttons: [
									'copyHtml5',
									'excelHtml5',
									'csvHtml5',
									'pdfHtml5'
								]
							});
						}
						if(method=='guest'){
							jQuery('#guest-customers-listing').html(otresponse);
							jQuery('#guest-client-table').DataTable({						
							    dom: 'lfrtipB',								
								buttons: [
									'copyHtml5',
									'excelHtml5',
									'csvHtml5',
									'pdfHtml5'
								]
							});
						}						
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
					},
					error: function (xhr, ajaxOptions, thrownError) {
					}
				});							
			}				
		});							 				
}); 

/* Get All Locations Customer Registered/Guest */
jQuery(document).on('change','#apt_all_locations_customers',function(){
		jQuery('.apt-loading-main').show();
		var ajax_url = header_object.plugin_path;		
		if(jQuery(this).prop('checked')==true){ var alc ='Y';}else{ var alc='N';}	
		var postdata = { alc:alc,
						 general_ajax_action:'get_all_locations_customers'						 
		}
		jQuery.ajax({					
				url  : ajax_url+"/assets/lib/admin_general_ajax.php",					
				type : 'POST',					
				data : postdata,					
				dataType : 'html',					
				success  : function(response) {
					jQuery('.apt-loading-main').hide();
					window.location.reload();
				},
				error: function (xhr, ajaxOptions, thrownError) {
				}
		});
});


			 /*********************** Clients Jquery End Here **********************/
/********************************** Export Section Jquery ************************************/
	 
/* Get Filtered Bookings */ 
jQuery(document).on('click','.ranges li,.range_inputs .applyBtn' , function () {	
	jQuery('#apt_booking_startdate').val(jQuery('#apt_reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD'));
	jQuery('#apt_booking_enddate').val(jQuery('#apt_reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD'));
	
});
jQuery(document).ready(function(){
		jQuery('#apt_export_bookings').DataTable( {
			dom: 'lfrtipB',
			
			buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5'
			]
		});
	});  
 jQuery(document).on('click','#apt_filtered_bookings' , function () {				
		jQuery('.apt-loading-main').show();
		var ajax_url = header_object.plugin_path;	
		var booking_start = jQuery('#apt_booking_startdate').val();
		var booking_end = jQuery('#apt_booking_enddate').val();
		var booking_service = jQuery('#apt_booking_service').val();
		var booking_staff = jQuery('#apt_booking_staff').val();
		var export_bookings = jQuery('#apt_export_bookings').DataTable();
		var bwid = jQuery('input[name="bwid"]').val();
		var method = jQuery(this).data('method');		
		var postdata =  { booking_start:booking_start,
						  booking_end:booking_end,
						  booking_service:booking_service,
						  booking_staff:booking_staff,
						  general_ajax_action:'filtered_bookings',
						  bwid:bwid
		}		
		jQuery.ajax({				
			url  : ajax_url+"/assets/lib/admin_general_ajax.php",				
			type : 'POST',				
			data : postdata,				
			dataType : 'html',				
			success  : function(response) {	
				jQuery('.apt-loading-main').hide();
				jQuery('#apt_booking_startdate').val('');
				jQuery('#apt_booking_enddate').val('');
				export_bookings.destroy();
					jQuery('#apt_export_bookings_data').html(response);
					jQuery('#apt_export_bookings').DataTable({
						dom: 'frtipB',
						buttons: [
							'copyHtml5',
							'excelHtml5',
							'csvHtml5',
							'pdfHtml5'
						]
					});								
			}				
		});							 				
}); 
/* Get Filtered Staff In Export Section */
jQuery(document).on('change','#apt_staff_filter' , function () {	
		jQuery('.apt-loading-main').show();
		var staff_name = jQuery(this).val();
		jQuery('#staff-info-table_filter input').val(staff_name);
		jQuery('#staff-info-table_filter input').keyup();
		jQuery('.apt-loading-main').hide();
});
/* Get Filtered Service In Export Section */
jQuery(document).on('change','#apt_service_filter' , function () {	
		jQuery('.apt-loading-main').show();
		var service_name = jQuery(this).val();
		jQuery('#services-info-table_filter input').val(service_name);
		jQuery('#services-info-table_filter input').keyup();
		jQuery('.apt-loading-main').hide();
});
/* Get Filtered Categories In Export Section */
jQuery(document).on('change','#apt_category_filter' , function () {	
		jQuery('.apt-loading-main').show();
		var service_name = jQuery(this).val();
		jQuery('#category-info-table_filter input').val(service_name);
		jQuery('#category-info-table_filter input').keyup();
		jQuery('.apt-loading-main').hide();
});
/* Get Filtered Locations In Export Section */
jQuery(document).on('change','#apt_location_filter' , function () {	
		jQuery('.apt-loading-main').show();
		var service_name = jQuery(this).val();
		jQuery('#location-info-table_filter input').val(service_name);
		jQuery('#location-info-table_filter input').keyup();
		jQuery('.apt-loading-main').hide();
});
/* Get All Locations Export Data */
jQuery(document).on('change','#apt_all_exportdata',function(){
		jQuery('.apt-loading-main').show();
		var ajax_url = header_object.plugin_path;		
		if(jQuery(this).prop('checked')==true){ var aled ='Y';}else{ var aled='N';}	
		var postdata = { aled:aled,
						 general_ajax_action:'get_all_exportdata'						 
		}
		jQuery.ajax({					
				url  : ajax_url+"/assets/lib/admin_general_ajax.php",					
				type : 'POST',					
				data : postdata,					
				dataType : 'html',					
				success  : function(response) {
					jQuery('.apt-loading-main').hide();
					window.location.reload();
				},
				error: function (xhr, ajaxOptions, thrownError) {
				}
		});
});
			/*********************** Export Jquery End Here **********************/
/******************************* Appointments Section Jquery *********************************/
	
/* Show Booking Detail Modal On click Event */
jQuery(document).on('ready', function(){
		jQuery(".fc-day-grid-event").click(function(){
			jQuery("#booking-details").css('display','block');
		});
		jQuery(document).on('click','.add-new-customer-cal',function(){
				jQuery('#add_app_det').removeClass('active');	
				jQuery('#add_cust_det').addClass("active");
		});
		jQuery(document).on('click','#customer_add_new',function(){
				jQuery('#add_app_det').removeClass('active');	
				jQuery('#add_cust_det').addClass("active");
		});
});
/* Show Reschedule Booking modal On Booking Details Modal */
jQuery(document).on('click','#edit-booking-details',function(){
	jQuery('#edit-booking-details-view').modal();
	jQuery('#apt_booking_datetime').trigger('change');
});
jQuery(document).bind('ready ajaxComplete', function(){
	  /* Show Confirm Appointment Popover **/
		 jQuery('#apt-confirm-appointment-cal-popup').popover({ 
			html : true,
			content: function() {
			  return jQuery('#popover-confirm-appointment-cal-popup').html();
			}
		  });
	  /* Hide Confirm Appointment Popover */
		jQuery(document).on('click', '#apt-close-confirm-appointment-cal-popup', function(){		
			jQuery('.popover').fadeOut();
		});
	  /* Show Reject Appointment Popover **/
	  jQuery('#apt-reject-appointment-cal-popup').popover({ 
		html : true,
		content: function() {
		  return jQuery('#popover-reject-appointment-cal-popup').html();
		}
	  });
	 /* Hide Reject Appointment Popover */
		jQuery(document).on('click', '#apt-close-reject-appointment-cal-popup', function(){		
			jQuery('.popover').fadeOut();
		});	
	
	 /* Show Cancel Appointment Popover **/
	 jQuery('#apt-cancel-appointment-cal-popup').popover({ 
		html : true,
		content: function() {
		  return jQuery('#popover-cancel-appointment-cal-popup').html();
		}
	  });
	 /* Hide Cancel Appointment Popover */
		jQuery(document).on('click', '#apt-close-cancel-appointment-cal-popup', function(){		
			jQuery('.popover').fadeOut();
		});	
	 /* Show Delete Appointment Popover **/
	 jQuery('#apt-delete-appointment-cal-popup').popover({ 
		html : true,
		content: function() {
		  return jQuery('#popover-delete-appointment-cal-popup').html();
		}
	 });
	/* Hide Delete Appointment Popover */
	jQuery(document).on('click', '#apt-close-del-appointment-cal-popup', function(){			
		jQuery('.popover').fadeOut();
	});
	
	/* Show Delete Past Booking Popover */
		jQuery('.apt_delete_past_booking').popover({ 
				html : true,
				content: function() {
					var booking_id = jQuery(this).data('id');
					jQuery('.popover').each(function(){
						jQuery(this).fadeOut('slow');
					});
				  return jQuery('#popover-'+booking_id).html();
				}
		});

		/* Hide Delete Past Booking popover */
		jQuery(document).on('click', '.apt_close_delete_booking_popover', function(){
			jQuery('.popover').fadeOut('slow');
		});
	
});
/* Render Booking Calender And Events On Calender */	
jQuery(document).ready(function() {
	
	if(jQuery('#apt_calendar').length){
		
	var ajax_url = header_object.plugin_path;
	var cal_first_day = header_object.cal_first_day;	 
	var ak_wp_lang = header_object.ak_wp_lang;
	var default_date = header_object.full_cal_defaultdate;
	var bwid = jQuery('input[name="bwid"]').val();
	var d = new Date();
	var month = d.getMonth()+1;
	var day = d.getDate();
	var curdate = d.getFullYear() + '/' +
	(month<10 ? '0' : '') + month + '/' +
	(day<10 ? '0' : '') + day;
	jQuery('#apt_calendar').fullCalendar({
		header: {
			left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
		},
		lang: ak_wp_lang,
		refetch: false,
		firstDay: cal_first_day,
		eventLimit: 7, 
		defaultDate:default_date,
		editable: true,
		/*initialize: function(){
			window.setTimeout(function(){
				jQuery('#calendar').find('.fc-toolbar > .fc-center > div > h2').empty().append("<input type='text' class='datepicker_full' />");
				},0); 
			
		},
		viewRender: function(view, element){		
			window.setTimeout(function(){
				jQuery('#calendar').find('.fc-toolbar > .fc-center > div > h2').empty().append("<input type='text' class='datepicker_full' />");
				},0); 
				
		},	
		eventClick:  function(event, jsEvent, view) {
			jQuery('.modalBody').html(event.description);
			jQuery('#booking-details').attr('href',event.url);
			jQuery('#booking-details-calendar').modal();
			jQuery('.service-html').html(event.servicetitle);
		},	*/
		dayClick:  function(date, jsEvent, view) {
			var d =  new Date(date);
			var tdate = new Date();
			tdate.setDate(tdate.getDate() - 1);
			var curr_date = d.getDate();
			var curr_month = d.getMonth()+1;
			var curr_year = d.getFullYear();
			var newdateis=  curr_month+"/"+curr_date+"/"+curr_year;						
			var dataString={newdateis:newdateis,action:'add_manual_booking_popup'};
			if(d < tdate){
				jQuery('.mainheader_message_fail').show();
				jQuery('.mainheader_message_inner_fail').css('display','inline');	
				jQuery('#apt_sucess_message_fail').text('Not allowed booking in past date');
				jQuery('.mainheader_message_fail').fadeOut(6000);
				return false;			
			}else{
				jQuery('#add-new-booking-details').modal();
				jQuery('#apt_booking_date_manual').val(newdateis);
			}
	 
			/*jQuery.ajax({
					type:"POST",
					url:ajax_url+"/assets/lib/admin_general_ajax.php",
					data:dataString,
					success:function(response){
					jQuery('.show-popup').html(response);
					jQuery('#add-new-booking-details').addClass("in");
					jQuery('#add-new-booking-details').css("display","block");
					jQuery('.manual-booking-modal-bg-backdrop').css("display","block");
					}
			});*/
		
        },
		setHeight: function(height, isAuto) {},		
		eventRender: function (event, element) {
				//console.log(event);
				var event_st = event.event_status;
			
                if(event_st=='C'){
					element.find('.fc-title').hide();
					element.find('.fc-time').hide();
					element.find('.fc-title').before(jQuery("<i class='fa fa-check txt-success' title='Confirmed'></i>"));              
					element.find('.fc-title').after(jQuery("<div><i class='omar-clock fa fa-clock-o'></i>"+event.start.format(header_object.time_format)+" to "+event.end.format(header_object.time_format)+"</div><div>"+event.title+"</div> <div>"+event.provider+"</div><div>"+event.provider_email+"</div><div>"+event.provider_phone+"</div><div><hr id='hr' /></div><div>"+event.client_name+"</div><div>"+event.client_phone+"</div>")); 	
                }				
				if(event_st=='R'){
					element.find('.fc-title').hide();
					element.find('.fc-time').hide();
					element.find('.fc-title').before(jQuery("<i class='fa fa-ban txt-danger' title='Rejected'></i>"));           
					element.find('.fc-title').after(jQuery("<div><i class='omar-clock fa fa-clock-o'></i>"+event.start.format(header_object.time_format)+" to "+event.end.format(header_object.time_format)+"</div><div>"+event.title+"</div> <div>"+event.provider+"</div><div>"+event.provider_email+"</div><div>"+event.provider_phone+"</div><div><hr id='hr' /></div><div>"+event.client_name+"</div><div>"+event.client_phone+"</div>"));
                }
                if(event_st=='CC'){
					element.find('.fc-title').hide();
					element.find('.fc-time').hide();
					element.find('.fc-title').before(jQuery("<i class='fa fa-times txt-primary' title='Cancelled by client'></i>"));     
					element.find('.fc-title').after(jQuery("<div><i class='omar-clock fa fa-clock-o'></i>"+event.start.format(header_object.time_format)+" to "+event.end.format(header_object.time_format)+"</div><div>"+event.title+"</div> <div>"+event.provider+"</div><div>"+event.provider_email+"</div><div>"+event.provider_phone+"</div><div><hr id='hr' /></div><div>"+event.client_name+"</div><div>"+event.client_phone+"</div>"));
                }
				if(event_st=='A' || event_st=='' ){
					element.find('.fc-title').hide();
					element.find('.fc-time').hide();
					element.find('.fc-title').before(jQuery("<i class='fa fa-info-circle txt-warning' title='Pending'></i>"));               
					element.find('.fc-title').after(jQuery("<div><i class='omar-clock fa fa-clock-o'></i>"+event.start.format(header_object.time_format)+" to "+event.end.format(header_object.time_format)+"</div><div>"+event.title+"</div> <div>"+event.provider+"</div><div>"+event.provider_email+"</div><div>"+event.provider_phone+"</div><div><hr id='hr' /></div><div>"+event.client_name+"</div><div>"+event.client_phone+"</div>"));
                }				
				if(event_st=='CS'){
					element.find('.fc-title').hide();
					element.find('.fc-time').hide();
					element.find('.fc-title').before(jQuery("<i class='fa fa-times-circle-o txt-info' title='Cancelled by service provider'></i>"));               
					element.find('.fc-title').after(jQuery("<div><i class='omar-clock fa fa-clock-o'></i>"+event.start.format(header_object.time_format)+" to "+event.end.format(header_object.time_format)+"</div><div>"+event.title+"</div> <div>"+event.provider+"</div><div>"+event.provider_email+"</div><div>"+event.provider_phone+"</div><div><hr id='hr' /></div><div>"+event.client_name+"</div><div>"+event.client_phone+"</div>"));
                }
                if(event_st=='CO'){
					element.find('.fc-title').hide();
					element.find('.fc-time').hide();
					element.find('.fc-title').before(jQuery("<i class='fa fa-thumbs-o-up txt-success' title='Appointment completed'></i>"));
					element.find('.fc-title').after(jQuery("<div><i class='omar-clock fa fa-clock-o'></i>"+event.start.format(header_object.time_format)+" to "+event.end.format(header_object.time_format)+"</div><div>"+event.title+"</div> <div>"+event.provider+"</div><div>"+event.provider_email+"</div><div>"+event.provider_phone+"</div><div><hr id='hr' /></div><div>"+event.client_name+"</div><div>"+event.client_phone+"</div>"));
                }
                if(event_st=='MN'){
					element.find('.fc-title').hide();
					element.find('.fc-time').hide();
					element.find('.fc-title').before(jQuery("<i class='fa fa-thumbs-o-down txt-danger' title='Appointment marked as no show'></i> "));
					element.find('.fc-title').after(jQuery("<div><i class='omar-clock fa fa-clock-o'></i>"+event.start.format(header_object.time_format)+" to "+event.end.format(header_object.time_format)+"</div><div>"+event.title+"</div> <div>"+event.provider+"</div><div>"+event.provider_email+"</div><div>"+event.provider_phone+"</div><div><hr id='hr' /></div><div>"+event.client_name+"</div><div>"+event.client_phone+"</div>"));
                }				
				if(event_st=='RS'){
					element.find('.fc-title').hide();
					element.find('.fc-time').hide();
					element.find('.fc-title').before(jQuery("<i class='fa fa-pencil-square-o txt-info' title='Rescheduled'></i>"));                             
					element.find('.fc-title').after(jQuery("<div><i class='omar-clock fa fa-clock-o'></i>"+event.start.format(header_object.time_format)+" to "+event.end.format(header_object.time_format)+"</div><div>"+event.title+"</div> <div>"+event.provider+"</div><div>"+event.provider_email+"</div><div>"+event.provider_phone+"</div><div><hr id='hr' /></div><div>"+event.client_name+"</div><div>"+event.client_phone+"</div>"));
                }
			element.css('background',event.color_tag);
			element.css('border-color',event.color_tag);
			element.attr('href', 'javascript:void(0);');
			element.click(function() {
                            jQuery("#reject_reason_txt").val('');
                            var bwid = jQuery('input[name="bwid"]').val();
                            var appointment_id = event.id;
                            var getdata =  {
                                appointment_id:appointment_id,
								general_ajax_action:'get_appointment_detail',
								bwid:bwid
							};
                            jQuery.ajax({                               
                                    type : 'POST',                                   
                                    url  : ajax_url+"/assets/lib/admin_general_ajax.php",
                                    data : getdata,
                                    dataType : 'html',
                                    success  : function(response) {							
                                    var app_details = jQuery.parseJSON(response); 
											/** Booking Detail Modal Content **/
											jQuery('#apt_confirm_btn').show();
											jQuery('#apt_reject_btn').show();
											jQuery('#apt_cancel_btn').show();
											jQuery('#apt_reschedule_btn').show();
                                            jQuery('#booking-details').modal();
                                            jQuery(".apt_servicetitle").html(app_details.service_title);
											if(app_details.booking_status=="Active"){
											jQuery(".apt-booking-status").html("<i class='fa fa-info-circle txt-warning' title='Pending'><em>Pending</em></i>");
											jQuery('#apt_cancel_btn').hide();
											}else if(app_details.booking_status=="Confirm"){
											jQuery(".apt-booking-status").html('<i class="fa fa-check txt-success" title="Confirmed"><em>Confirmed</em></i>');
											jQuery('#apt_confirm_btn').hide();
											jQuery('#apt_reject_btn').hide();
											}else if(app_details.booking_status=="Reject"){
											jQuery(".apt-booking-status").html('<i class="fa fa-ban txt-danger" title="Rejected"><em>Rejected</em></i>');
											jQuery('#apt_confirm_btn').hide();
											jQuery('#apt_reject_btn').hide();
											jQuery('#apt_cancel_btn').hide();
											}else if(app_details.booking_status=="Rescheduled"){
											jQuery(".apt-booking-status").html("<i class='fa fa-pencil-square-o txt-info' title='Rescheduled'><em>Rescheduled</em></i>");
											jQuery('#apt_confirm_btn').hide();
											jQuery('#apt_reject_btn').hide();
											}else if(app_details.booking_status=="Cancel By Client"){
											jQuery(".apt-booking-status").html("<i class='fa fa-times txt-primary' title='Cancelled by client'><em>Cancel By Client</em></i>");
											jQuery('#apt_confirm_btn').hide();
											jQuery('#apt_reject_btn').hide();
											jQuery('#apt_cancel_btn').hide();
											jQuery('#apt_reschedule_btn').hide();
											}else if(app_details.booking_status=="Cancel By Service Provider"){
											jQuery(".apt-booking-status").html("<i class='fa fa-times-circle-o txt-info' title='Cancelled by service provider'><em>Cancelled by Service Provider</em></i>");
											jQuery('#apt_confirm_btn').hide();
											jQuery('#apt_reject_btn').hide();
											jQuery('#apt_cancel_btn').hide();
											jQuery('#apt_reschedule_btn').hide();
											}else if(app_details.booking_status=="Completed"){
											jQuery(".apt-booking-status").html("<i class='fa fa-thumbs-o-down txt-danger' title='Completed'><em>Completed</em></i>");
											jQuery('#apt_confirm_btn').hide();
											jQuery('#apt_reject_btn').hide();
											jQuery('#apt_cancel_btn').hide();
											jQuery('#apt_reschedule_btn').hide();
											}else{
											 jQuery(".apt-booking-status").html("<i class='fa fa-thumbs-o-down txt-danger' title='Appointment marked as no show'><em>Appointment Marked as no show</em></i>");
											 jQuery('#apt_confirm_btn').hide();
											 jQuery('#apt_reject_btn').hide();
											 jQuery('#apt_cancel_btn').hide();
											 jQuery('#apt_reschedule_btn').hide();
											}
											
											jQuery(".apt_booking_datetime").html('<span><i class="fa fa-calendar"></i>'+app_details.appointment_startdate+'  <i class="fa fa-clock-o ml-10"></i>'+app_details.appointment_starttime+' to '+app_details.appointment_endtime+'</span>');
											jQuery(".calendar_providername").html(app_details.provider_name);									
                                            jQuery(".price").html(app_details.service_price);
                                            jQuery(".duration").html(app_details.service_duration);
                                            jQuery(".client_name").html(app_details.client_name);
                                            jQuery(".client_email").html(app_details.client_email);
                                            jQuery(".client_phone").html(app_details.client_phone);
											jQuery(".client_notes").html(app_details.client_notes);
											jQuery(".client_payment").html(app_details.payment_type);
											jQuery('#apt_booking_confirm').attr('data-booking_id',app_details.id);
											jQuery('#apt_booking_reject').attr('data-booking_id',app_details.id);
											jQuery('#apt_booking_delete').attr('data-booking_id',app_details.id);
											jQuery('#apt_booking_cancel').attr('data-booking_id',app_details.id);
											/** End Booking Detail Modal **/
											/** Booking Edit Modal Content **/                 
											jQuery('#apt_booking_provider').html("<option value='"+app_details.provider_id+"'>"+app_details.provider_name+"</option>");	
											jQuery('#apt_booking_provider').selectpicker('refresh');
											jQuery('#apt_booking_service').html("<option value='"+app_details.service_id+"'>"+app_details.service_title+"</option>");	
											jQuery('#apt_booking_service').selectpicker('refresh');
											jQuery('#apt_service_price').text(app_details.service_price);
											jQuery('#apt_service_duration_val').val(app_details.service_duration);
											jQuery('#apt_service_duration').text(app_details.service_duration_string);
											jQuery('#apt_booking_datetime').val(app_details.booking_date);
											jQuery('#apt_booking_rsnotes').val(app_details.reschedule_note);
											jQuery('#apt_client_name').val(app_details.client_name);
											jQuery('#apt_client_email').val(app_details.client_email);
											jQuery('#apt_client_phone').val(app_details.client_phone);
											if(app_details.client_ccode != null){
												jQuery('#apt_client_phone').val(app_details.client_ccode+''+app_details.client_phone);
											}											
											jQuery('#apt_client_address').val(app_details.client_address);
											jQuery('#apt_client_city').val(app_details.client_city);
											jQuery('#apt_client_state').val(app_details.client_state);
											jQuery('#apt_client_zip').val(app_details.client_zip);
											jQuery('#apt_client_country').val(app_details.client_country);
											jQuery('#apt_reschedule_booking').attr('data-booking_id',app_details.id);				
											/** End Booking Reschedule Modal **/
                                    }
                                });

                    });  
		},
		events: ajax_url+"/assets/lib/admin_general_ajax.php?general_ajax_action=get_upcoming_appointments&bwid="+bwid	
	});
	}
});		
/** Get Services By Staff - Manual Booking **/
jQuery(document).on('change','#apt_booking_provider_manual',function(){
		var ajax_url = header_object.plugin_path;		
		var staff_id = jQuery(this).val();
		var postdata = { staff_id:staff_id,
						 general_ajax_action:'get_services_by_staff'						 
		}
		jQuery.ajax({					
				url  : ajax_url+"/assets/lib/admin_general_ajax.php",					
				type : 'POST',					
				data : postdata,					
				dataType : 'html',					
				success  : function(response) {
					console.log(response);
					jQuery('#apt_booking_service_manual').html(response);
					jQuery('#apt_booking_service_manual').selectpicker('refresh');
					jQuery('#apt_booking_service_manual').trigger('change');
				},
				error: function (xhr, ajaxOptions, thrownError) {
				}
		});
});
/** Get Services Duration,Price,Schedule on change service - Manual Booking **/	
jQuery(document).on('change','#apt_booking_service_manual',function(){
		var ajax_url = header_object.plugin_path;		
		var service_id = jQuery(this).val();
		
		var postdata = { service_id:service_id,
						 general_ajax_action:'get_services_info'						 
		}
		if(service_id){
		jQuery.ajax({					
				url  : ajax_url+"/assets/lib/admin_general_ajax.php",					
				type : 'POST',					
				data : postdata,					
				dataType : 'html',					
				success  : function(response) {
					var service_info = jQuery.parseJSON(response); 
					jQuery('#apt_service_price_manual').text(service_info.service_price);
					jQuery('#apt_service_duration_manual').text(service_info.service_duration);
					jQuery('#apt_service_duration_val_manual').val(service_info.service_duration_val);
				}
		});
		}else{
			jQuery('#apt_service_price_manual').text('');
			jQuery('#apt_service_duration_manual').text('');
			jQuery('#apt_service_duration_val_manual').val('');
		}
});		

/** Reschedule Appointment **/	
jQuery(document).on('click','#apt_reschedule_booking',function(){
		jQuery('.apt-loading-main').show();
		var ajax_url = header_object.plugin_path;		
		var booking_id = jQuery(this).data('booking_id');
		var booking_date = jQuery('#apt_booking_datetime').val();
		var booking_time = jQuery('#apt_booking_time').val();
		var reschedule_note = jQuery('#apt_booking_rsnotes').val();
		var service_duration = jQuery('#apt_service_duration_val').val();
		var bwid = jQuery('input[name="bwid"]').val();
		var postdata = { booking_id:booking_id,
						 booking_date:booking_date,
						 booking_time:booking_time,
						 service_duration:service_duration,
						 reschedule_note:reschedule_note,
						 method:'RS',
						 general_ajax_action:'reschedule_appointment',
						 bwid:bwid
		}
		jQuery.ajax({					
				url  : ajax_url+"/assets/lib/admin_general_ajax.php",					
				type : 'POST',					
				data : postdata,					
				dataType : 'html',					
				success  : function(response) {
				jQuery('.apt-loading-main').hide();
				jQuery('.mainheader_message_inner').show();
				appointment_hide_success_msg();
				window.location.reload();
				}
		});
});		
/** Confirm,Reject,Cancel Appointment **/
jQuery(document).on('click','.apt_crc_appointment',function(){
	jQuery('.apt-loading-main').show();
	var ajax_url = header_object.plugin_path;		
	var booking_id = jQuery(this).data('booking_id');
	var method = jQuery(this).data('method');
	var bwid = jQuery('input[name="bwid"]').val();
	if(method=='C'){
		var action_content = jQuery('#apt_booking_confirmnote').val();
	}else if(method=='R'){
		var action_content = jQuery('#apt_booking_rejectnote').val();
	}else{
		if(jQuery(this).data('sp')=='Y'){
			var action_content = jQuery('#apt_booking_cancelnote'+booking_id).val();
		}else{
			var action_content = jQuery('#apt_booking_cancelnote').val();
		}
	}
	var postdata = { booking_id:booking_id,
					 method:method,
					 action_content:action_content,
					 general_ajax_action:'c_r_cs_cc_appointment',
					 bwid:bwid						 
	}
	jQuery.ajax({
		url  : ajax_url+"/assets/lib/admin_general_ajax.php",
		type : 'POST',
		data : postdata,
		dataType : 'html',
		success  : function(response) {
			jQuery('.apt-loading-main').hide();
			jQuery('.mainheader_message_inner').show();
			appointment_hide_success_msg();
			window.location.reload();
		}
	});
});	
/** Delete Appointment **/
jQuery(document).on('click','#apt_booking_delete',function(){
		jQuery('.apt-loading-main').show();
		var ajax_url = header_object.plugin_path;		
		var booking_id = jQuery(this).data('booking_id');
		var bwid = jQuery('input[name="bwid"]').val();
		var postdata = { booking_id:booking_id,
						 general_ajax_action:'delete_appointment',
						 bwid:bwid
		}
		jQuery.ajax({					
				url  : ajax_url+"/assets/lib/admin_general_ajax.php",					
				type : 'POST',					
				data : postdata,					
				dataType : 'html',					
				success  : function(response) {
				jQuery('.apt-loading-main').hide();
				jQuery('.mainheader_message_inner').show();
				appointment_hide_success_msg();
				window.location.reload();
				}
		});
});	
/** Filter Appointment **/
jQuery(document).on('click','#apt_filter_appointments',function(){
		jQuery('.apt-loading-main').show();		
		var ajax_url = header_object.plugin_path;
		var startdate = jQuery('#apt_booking_startdate').val();
		var enddate = jQuery('#apt_booking_enddate').val();
		var staff_id = jQuery('#apt_booking_filterprovider').val();
		var service_id = jQuery('#apt_booking_filterservice').val();
		
		var postdata = { startdate:startdate,
						 enddate:enddate,
						 staff_id:staff_id,
						 service_id:service_id,
						 general_ajax_action:'filter_appointments'						 
		}
		jQuery.ajax({					
				url  : ajax_url+"/assets/lib/admin_general_ajax.php",					
				type : 'POST',					
				data : postdata,					
				dataType : 'html',					
				success  : function(response) {
				jQuery('.apt-loading-main').hide();
				window.location.reload();
				}
		});
});
/** Get Registerd Client Information - Manual Booking **/	
jQuery(document).on('change','#apt_booking_client_manual',function(){
		var ajax_url = header_object.plugin_path;		
		var client_id = jQuery(this).val();		
		jQuery('.apt-searching-customer').show();
		jQuery('#client_username').show('slow');
		jQuery('#client_password').show('slow');
		jQuery('#apt_clientname_manual').val('');
		jQuery('#apt_clientemail_manual').val('');
		jQuery('#apt_clientemail_manual').keyup();
		jQuery('#apt_clientemail_manual').keydown();
		jQuery('#apt_clientphone_manual').val('');
		jQuery('#apt_clientpassword_manual').val('');
		jQuery('#apt_clientaddress_manual').val('');
		jQuery('#apt_clientcity_manual').val('');
		jQuery('#apt_clientstate_manual').val('');
		jQuery('#apt_clientzip_manual').val('');
		jQuery('#apt_clientcountry_manual').val('');
		jQuery("#manual_booking_form").valid();
		if(client_id!=''){
		jQuery('#apt_clientname_manual-error').hide();	
		jQuery('#apt_clientemail_manual-error').hide();	
		jQuery('.apt-loading-main').show();		
		jQuery('#client_username').hide('slow');
		jQuery('#client_password').hide('slow');
		
			var postdata = { client_id:client_id,
							 general_ajax_action:'get_client_info'						 
			}
			jQuery.ajax({					
					url  : ajax_url+"/assets/lib/admin_general_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						jQuery('.apt-loading-main').hide();	
						var client_info = jQuery.parseJSON(response);								
						jQuery('#apt_clientname_manual').val(client_info.client_name);
						jQuery('#apt_clientname_manual').keyup();
						jQuery('#apt_clientemail_manual').val(client_info.client_email);	
						jQuery('#apt_clientemail_manual').keyup();						
						jQuery('#apt_clientphone_manual').val(client_info.client_phone);
						jQuery('#apt_clientphone_manual').keyup();
						jQuery('#apt_clientpassword_manual').val(client_info.service_price);
						jQuery('#apt_clientaddress_manual').val(client_info.client_address);
						jQuery('#apt_clientcity_manual').val(client_info.client_city);
						jQuery('#apt_clientstate_manual').val(client_info.client_state);
						jQuery('#apt_clientzip_manual').val(client_info.client_zip);
						jQuery('#apt_clientcountry_manual').val(client_info.client_country);
						jQuery("#manual_booking_form").valid();
					}
			});
		}
		jQuery('.apt-searching-customer').hide();
});	
/** Book Appointment Manually Form Validations **/
jQuery(document).bind('ready ajaxComplete', function(){
	var staffusername_err_msg = admin_validation_err_msg.staffusername_err_msg;	
		var staffusernameexist_err_msg = admin_validation_err_msg.staffusernameexist_err_msg;	
		var staffpassword_err_msg = admin_validation_err_msg.staffpassword_err_msg;
		var staffemail_err_msg = admin_validation_err_msg.staffemail_err_msg;
		var staffemailexist_err_msg = admin_validation_err_msg.staffemailexist_err_msg;
		var stafffullname_err_msg = admin_validation_err_msg.stafffullname_err_msg;
		var staffselect_err_msg = admin_validation_err_msg.staffselect_err_msg;
		var siteurl = header_object.site_url;
		/* Validaing Manual Booking Form */
		jQuery('#manual_booking_form').validate({
					rules: {
						apt_mb_username: {
													required: true,
													remote: {
															url: siteurl+"/wp-admin/admin-ajax.php",
															type: "POST",
															async: true,
															data: {
															username:function() {
																 return jQuery('input[name="apt_mb_username"]').val();
																},
																action:'check_username_bd'
															}
														}
							},
						apt_mb_password: {
													required: true
							},
						apt_mb_clientname: {
													required: true,
							},
						apt_mb_clientemail: {
													required: true,
													remote: {
													
															url: siteurl+"/wp-admin/admin-ajax.php",
															type: "POST",
															async: true,
															data: {
															email:function() {								
																if(jQuery('#apt_booking_client_manual').val()==''){
																	return jQuery('#apt_clientemail_manual').val();
																	}else{ 
																		return true;
																	}
																},					
																action:'check_email_bd'
															}		
														},
													customemail:true
							},	
	
						},
					messages: {
								apt_mb_username: { required: staffusername_err_msg }, 
								apt_mb_password: { required: staffpassword_err_msg }, 
								apt_mb_clientname: { required: stafffullname_err_msg }, 
								apt_mb_clientemail: { required: staffemail_err_msg , customemail: staffemailexist_err_msg},
						}
						});
});
/** Validation End Here **/
/** Book Appointment Manually **/
jQuery(document).on('click','#apt_book_manual_appointment',function(){
	
		
		if(jQuery('#manual_booking_form').valid()){
				var ajax_url = header_object.plugin_path;
				jQuery('.apt-loading-main').show();		
				var provider_id = jQuery('#apt_booking_provider_manual').val();
				var service_id = jQuery('#apt_booking_service_manual').val();
				var service_duration = jQuery('#apt_service_duration_val_manual').val();
				var service_price = jQuery('#apt_service_price_manual').text();
				var booking_date = jQuery('#apt_booking_date_manual').val();
				var booking_time = jQuery('#apt_booking_time_manual').val();
				var booking_note = jQuery('#apt_booking_note_manual').val();
				var client_id = jQuery('#apt_booking_client_manual').val();
				var client_name = jQuery('#apt_clientname_manual').val();
				var client_email = jQuery('#apt_clientemail_manual').val();
				var client_phone = jQuery('#apt_clientphone_manual').val();
				var client_username = jQuery('#apt_clientusername_manual').val();
				var client_password = jQuery('#apt_clientpassword_manual').val();
				var client_address = jQuery('#apt_clientaddress_manual').val();
				var client_city = jQuery('#apt_clientcity_manual').val();
				var client_state = jQuery('#apt_clientstate_manual').val();
				var client_zip = jQuery('#apt_clientzip_manual').val();
				var client_country = jQuery('#apt_clientcountry_manual').val();
				var bwid = jQuery('input[name="bwid"]').val();
				
				
				var postdata = { provider_id:provider_id,
								 service_id:service_id,
								 service_duration:service_duration,
								 service_price:service_price,
								 booking_date:booking_date,
								 booking_time:booking_time,
								 booking_note:booking_note,
								 client_id:client_id,
								 client_name:client_name,
								 client_email:client_email,
								 client_phone:client_phone,
								 client_username:client_username,
								 client_password:client_password,
								 client_address:client_address,
								 client_city:client_city,
								 client_state:client_state,
								 client_zip:client_zip,
								 client_country:client_country,
								 general_ajax_action:'book_manual_appointment',
								 bwid:bwid
				}
				jQuery.ajax({					
						url  : ajax_url+"/assets/lib/admin_general_ajax.php",					
						type : 'POST',					
						data : postdata,					
						dataType : 'html',					
						success  : function(response) {
							var booking_id = response;					
							if(header_object.mb_status=='E'){
								var method = 'C';
							}else{
								var method = 'A';
							}
							var emaildata = { booking_id:booking_id,
											  method:method,
								 }
								jQuery.ajax({					
									url  : ajax_url+"/assets/lib/admin_general_ajax.php",					
									type : 'POST',					
									data : emaildata,					
									dataType : 'html',					
									success  : function(response) {
										jQuery('.apt-loading-main').hide();
										jQuery('.mainheader_message_inner').show();
										appointment_hide_success_msg();
										window.location.reload();
									}
								});
						}			
				});
		}
});
/************************************** Dashboard Section Jquery ************************************/

jQuery(document).on('click','.apt-today-list',function(){

		var ajax_url = header_object.plugin_path;
		jQuery('.apt-loading-main').show();		
		jQuery('.apt_client_bookingclose').trigger('click');
		jQuery( "#apt-close-confirm-appointment-cal-popup" ).trigger( "click" );
		jQuery( "#apt-close-reject-appointment-cal-popup" ).trigger( "click" );
		jQuery( "#apt-close-del-appointment-cal-popup" ).trigger( "click" );
		
		var appointment_id = jQuery(this).data('bookingid');
		var getdata =  {
				appointment_id:appointment_id,
				general_ajax_action:'get_appointment_detail'	
			};
			jQuery.ajax({                               
					type : 'POST',                                   
					url  : ajax_url+"/assets/lib/admin_general_ajax.php",
					data : getdata,
					dataType : 'html',
					success  : function(response) {
					var app_details = jQuery.parseJSON(response);
						
						/** Booking Detail Modal Content **/
						jQuery('#apt_confirm_btn').show();
						jQuery('#apt_reject_btn').show();
						jQuery('#apt_cancel_btn').show();
						jQuery('#apt_reschedule_btn').show();
						jQuery('#booking-details-calendar').modal();
						jQuery(".apt_servicetitle").html(app_details.service_title);
						if(app_details.booking_status=="Active"){
						jQuery(".apt-booking-status").html("<i class='fa fa-info-circle txt-warning' title='Pending'><em>Pending</em></i>");
						jQuery('#apt_cancel_btn').hide();
						}else if(app_details.booking_status=="Confirm"){
						jQuery(".apt-booking-status").html('<i class="fa fa-check txt-success" title="Confirmed"><em>Confirmed</em></i>');
						jQuery('#apt_confirm_btn').hide();
						jQuery('#apt_reject_btn').hide();
						}else if(app_details.booking_status=="Reject"){
						jQuery(".apt-booking-status").html('<i class="fa fa-ban txt-danger" title="Rejected"><em>Rejected</em></i>');
						jQuery('#apt_confirm_btn').hide();
						jQuery('#apt_reject_btn').hide();
						jQuery('#apt_cancel_btn').hide();
						}else if(app_details.booking_status=="Rescheduled"){
						jQuery(".apt-booking-status").html("<i class='fa fa-pencil-square-o txt-info' title='Rescheduled'><em>Rescheduled</em></i>");
						jQuery('#apt_confirm_btn').hide();
						jQuery('#apt_reject_btn').hide();
						}else if(app_details.booking_status=="Cancel By Client"){
						jQuery(".apt-booking-status").html("<i class='fa fa-times txt-primary' title='Cancelled by client'><em>Cancel By Client</em></i>");
						jQuery('#apt_confirm_btn').hide();
						jQuery('#apt_reject_btn').hide();
						jQuery('#apt_cancel_btn').hide();
						jQuery('#apt_reschedule_btn').hide();
						}else if(app_details.booking_status=="Cancel By Service Provider"){
						jQuery(".apt-booking-status").html("<i class='fa fa-times-circle-o txt-info' title='Cancelled by service provider'><em>Cancelled by Service Provider</em></i>");
						jQuery('#apt_confirm_btn').hide();
						jQuery('#apt_reject_btn').hide();
						jQuery('#apt_cancel_btn').hide();
						jQuery('#apt_reschedule_btn').hide();
						}else if(app_details.booking_status=="Completed"){
						jQuery(".apt-booking-status").html("<i class='fa fa-thumbs-o-down txt-danger' title='Completed'><em>Completed</em></i>");
						jQuery('#apt_confirm_btn').hide();
						jQuery('#apt_reject_btn').hide();
						jQuery('#apt_cancel_btn').hide();
						jQuery('#apt_reschedule_btn').hide();
						}else{
						 jQuery(".apt-booking-status").html("<i class='fa fa-thumbs-o-down txt-danger' title='Appointment marked as no show'><em>Appointment Marked as no show</em></i>");
						 jQuery('#apt_confirm_btn').hide();
						 jQuery('#apt_reject_btn').hide();
						 jQuery('#apt_cancel_btn').hide();
						 jQuery('#apt_reschedule_btn').hide();
						}
						
						jQuery(".apt_booking_datetime").html('<span><i class="fa fa-calendar"></i>'+app_details.appointment_startdate+'  <i class="fa fa-clock-o ml-10"></i>'+app_details.appointment_starttime+' to '+app_details.appointment_endtime+'</span>');
						jQuery(".calendar_providername").html(app_details.provider_name);									
						jQuery(".price").html(app_details.service_price);
						jQuery(".duration").html(app_details.service_duration);
						jQuery(".client_name").html(app_details.client_name);
						jQuery(".client_email").html(app_details.client_email);
						jQuery(".client_phone").html(app_details.client_phone);
						jQuery(".client_notes").html(app_details.client_notes);
						jQuery(".client_payment").html(app_details.payment_type);
						jQuery('#apt_booking_confirm').attr('data-booking_id',app_details.id);
						jQuery('#apt_booking_reject').attr('data-booking_id',app_details.id);
						jQuery('#apt_booking_delete').attr('data-booking_id',app_details.id);
						jQuery('#apt_booking_cancel').attr('data-booking_id',app_details.id);
						/** End Booking Detail Modal **//*
						/** Booking Edit Modal Content **/                 
						jQuery('#apt_booking_provider').html("<option value='"+app_details.provider_id+"'>"+app_details.provider_name+"</option>");	
						jQuery('#apt_booking_provider').selectpicker('refresh');
						jQuery('#apt_booking_service').html("<option value='"+app_details.service_id+"'>"+app_details.service_title+"</option>");	
						jQuery('#apt_booking_service').selectpicker('refresh');
					
						jQuery('#apt_service_price').text(app_details.service_price);
						jQuery('#apt_service_duration_val').val(app_details.service_duration);
						jQuery('#apt_service_duration').text(app_details.service_duration_string);
						jQuery('#apt_booking_datetime').val(app_details.booking_date);
						jQuery('#apt_booking_rsnotes').val(app_details.reschedule_note);
						jQuery('#apt_client_name').val(app_details.client_name);
						jQuery('#apt_client_email').val(app_details.client_email);
						jQuery('#apt_client_phone').val(app_details.client_phone);
						if(app_details.client_ccode != null){
							jQuery('#apt_client_phone').val(app_details.client_ccode+''+app_details.client_phone);
						}											
						jQuery('#apt_client_address').val(app_details.client_address);
						jQuery('#apt_client_city').val(app_details.client_city);
						jQuery('#apt_client_state').val(app_details.client_state);
						jQuery('#apt_client_zip').val(app_details.client_zip);
						jQuery('#apt_client_country').val(app_details.client_country);
						jQuery('#apt_reschedule_booking').attr('data-booking_id',app_details.id);				
						/** End Booking Reschedule Modal **/
						
						jQuery('#apt_booking_datetime').attr('data-sel_date', app_details.sel_date);
						jQuery('#apt_booking_datetime').attr('data-selstaffid', app_details.provider_id);
						var getslots_data = {
							'seldate':app_details.sel_date,
							'selstaffid':app_details.provider_id,
							'bwid':app_details.bwid,
							'action':'apt_get_provider_slots'
						};
						jQuery.ajax({
							type : 'POST',
							url  : ajax_url+"/assets/lib/apt_client_ajax.php",
							data : getslots_data,
							dataType : 'html',
							success  : function(res) {
								jQuery('#apt_booking_time').html(res);
								jQuery('#apt_booking_time').selectpicker('refresh');
								jQuery('.apt-loading-main').hide();
							}
						});
					}
				});
});

/* Service View,Provider View,Coupon View Analytics */
jQuery(document).ready(function(){
	jQuery('.apt_service_chart').trigger('click');
});
jQuery(document).on('click','.apt_view_chart_analytics',function(){
		var ajax_url = header_object.plugin_path;
		var bwid = jQuery('input[name="bwid"]').val();
		var method = jQuery(this).data('method');
		var chartid = document.getElementById("chart-area-"+method).getContext("2d");
		jQuery('.apt_nodata_'+method).hide();
		jQuery('.chart_view_content').each(function(){
			if(jQuery(this).attr('id')== method+'-view-tab'){
				jQuery(this).show();
			}else{
				jQuery(this).hide();
			}
		});
		var postdata={ 
					method:method,
					bwid:bwid,
					general_ajax_action:'view_chart_analytics'
					};
		
		jQuery.ajax({
				url  : ajax_url+"/assets/lib/admin_general_ajax.php",
				type : 'POST',					
				data : postdata,					
				dataType : 'html',					
				success  : function(response) {
					
					if(response=='[]'){
						jQuery('.apt_nodata_'+method).show();
						jQuery('#chart-area-'+method).hide();
					}else{
						jQuery('#chart-area-'+method).show();
						var jsondata=jQuery.parseJSON(response);	
						window.myDoughnut = new Chart(chartid).Doughnut(jsondata,{responsive : true
						});
					}
				}
		});		
	});
	
/* Update Notification Count
jQuery(document).ready(function(){
	function get_notification_count(){	
		var ajax_url = header_object.plugin_path;	
		var postdata = { 
						  general_ajax_action:"get_notification_count"
					     };
		jQuery.ajax({
				url  : ajax_url+"/assets/lib/admin_general_ajax.php",
				type : 'POST',					
				data : postdata,					
				dataType : 'html',					
				success  : function(response) {
					jQuery('.apt_notification_count').html(response);
					if(jQuery('.get_notification_rem').html() >= 1){
						jQuery('.icon-bell').addClass('apt-new-booking apt-pulse');
					}else{
						jQuery('.icon-bell').removeClass('apt-new-booking apt-pulse');
					}
				}
		   });
		}   
		get_notification_count();
		setInterval(function(){
			get_notification_count();
	    },10000);
});	 */

/* Update Notification Count */
jQuery(document).ready(function(){
	
		var ajax_url = header_object.plugin_path;	
		var postdata = { 
						  general_ajax_action:"get_notification_count"
					     };
		jQuery.ajax({
				url  : ajax_url+"/assets/lib/admin_general_ajax.php",
				type : 'POST',					
				data : postdata,					
				dataType : 'html',					
				success  : function(response) {
					jQuery('.apt_notification_count').html(response);
					if(jQuery('.get_notification_rem').html() >= 1){
						jQuery('.icon-bell').addClass('apt-new-booking apt-pulse');
					}else{
						jQuery('.icon-bell').removeClass('apt-new-booking apt-pulse');
					}
				}
		   });
		/* }   
		get_notification_count();
		setInterval(function(){
			get_notification_count();
	    },10000); */
});	


/* Get Notification Bookings */
jQuery(document).ready(function () {
	jQuery(document).on('click','#apt-notifications',function(){
		jQuery('#apt-notification-container').removeAttr('style');	
		/* jQuery("#apt-notification-container").show( "blind", {direction: "vertical"}, 500 ); */
		var ajax_url = header_object.plugin_path;	
		var postdata = { 
						  general_ajax_action:"get_notification_bookings"
						 };
		jQuery.ajax({
			url  : ajax_url+"/assets/lib/admin_general_ajax.php",
			type : 'POST',					
			data : postdata,					
			dataType : 'html',					
			success  : function(response) {
				jQuery('.apt-recent-booking-list').html(response);
			}
	   });
		jQuery(".apt-notifications-inner").addClass("visible");  
			
	});
	jQuery("#apt-close-notifications").click(function () {
        jQuery(".apt-notifications-inner").removeClass("visible");
	});
						
	jQuery( document ).on( 'keydown', function ( e ) {
		if ( e.keyCode === 27 ) {
			 jQuery(".apt-notifications-inner").removeClass("visible");
		}
	});
});
/* Make Notification/All Notifications Readed */
jQuery(document).on('click','.apt_unread_notification',function(){
		jQuery('.apt-loading-main').show();
		var ajax_url = header_object.plugin_path;	
		var booking_id = jQuery(this).data('booking_id');
		var postdata = { 
						  booking_id:booking_id,
						  general_ajax_action:"remove_notifications_bookings"
					     };
		jQuery.ajax({
				url  : ajax_url+"/assets/lib/admin_general_ajax.php",
				type : 'POST',					
				data : postdata,					
				dataType : 'html',					
				success  : function(response){
					if(booking_id=='All'){
							jQuery('#apt-notifications').trigger('click');
							jQuery('.apt_notification_count').html('');
					}else{
							jQuery('#apt_notification'+booking_id).fadeOut('slow');
							if(parseInt(jQuery('#apt-notification-top').text())-1 ==0){
								jQuery('.apt_notification_count').html('');
							}else{	
								jQuery('#apt-notification-top').text(parseInt(jQuery('#apt-notification-top').text())-1);
							}
					}
					jQuery('.apt-loading-main').hide();	
					jQuery('.mainheader_message_inner').show();
					appointment_hide_success_msg();
				}
		   });
});
/*appointment Add/Remove Sample Data*/
jQuery(document).on('click','#appointment_sampledata',function(){
		var ajax_url = header_object.plugin_path;	
		jQuery('.apt-loading-main').show();		
		var method = jQuery(this).data('method');
		var bwid = jQuery('input[name="bwid"]').val();
		
		var postdata = {  method:method,
						  bwid:bwid,
						  general_ajax_action:"appointment_sampledata"
					     };
		jQuery.ajax({
				url  : ajax_url+"/assets/lib/admin_general_ajax.php",
				type : 'POST',					
				data : postdata,					
				dataType : 'html',					
				success  : function(response) {
					jQuery('.apt-loading-main').hide();
					jQuery('.mainheader_message_inner').show();
					appointment_hide_success_msg();
					window.location.reload();
				}
		   });
});




/********************************* Client Admin Area jQuery ******************************************/
/*****************************************************************************************************/
/* Client Cancel Appointment Popover */
	jQuery(document).bind('ready ajaxComplete', function(){
	  jQuery('.apt_client_cancel_appointmentpopup').popover({ 
		html : true,
		content: function() {
		  return jQuery('#popover-'+jQuery(this).attr('id')).html();
		}
	  });
	});
	/* hide delete appointment in modal window */
	jQuery(document).on('click', '.apt_cancel_clientcancel', function(){			
		jQuery('.popover').fadeOut();
	});
	
	
	
	/* review  Popover */
	jQuery(document).bind('ready ajaxComplete', function(){
	  jQuery('.apt-add-review-client-btn').popover({ 
		html : true,
		content: function() {
		  return jQuery('#popover-'+jQuery(this).attr('id')).html();
		}
	  });
	});
	/* hide review in modal window */
	jQuery(document).on('click', '.apt_cancel_review_pop', function(){			
		jQuery('.popover').fadeOut();
	});
	
	/* delete review  Popover */
	jQuery(document).bind('ready ajaxComplete', function(){
	  jQuery('.apt-delete-review').popover({ 
		html : true,
		content: function() {
		  return jQuery('#popover-'+jQuery(this).attr('id')).html();
		}
	  });
	});
	/* hide review in modal window */
	jQuery(document).on('click', '.apt-close-popover-apt-delete-review', function(){			
		jQuery('.popover').fadeOut();
	});
	
	
	
	

/* Get Client Order Bookings */
jQuery(document).on('click','.appointment_client_bookings',function(){
		var ajax_url = header_object.plugin_path;	
		jQuery('.apt-loading-main').show();		
		var client_id = jQuery(this).data('client_id');
		var order_id = jQuery(this).data('order_id');
		//var bwid = jQuery('input[name="bwid"]').val();
		var postdata = {  order_id:order_id,
						  client_id:client_id,
						  general_ajax_action:"get_client_order_bookings"
					     };
		jQuery.ajax({
				url  : ajax_url+"/assets/lib/admin_general_ajax.php",
				type : 'POST',					
				data : postdata,					
				dataType : 'html',					
				success  : function(response) {
				jQuery('.apt-loading-main').hide();		

					jQuery('#apt_client_orderbookings').html(response);
				}
		   });
});

/*appointment Add/Update Review */
jQuery(document).on('click','#apt_booking_submitreview',function(){
		var ajax_url = header_object.plugin_path;	
		jQuery('.apt-loading-main').show();		
		var rating = '0';
		var booking_id = jQuery(this).data('booking_id');
		var provider_id = jQuery(this).data('pid');
		var client_id = jQuery(this).data('cid');
		var method = jQuery(this).data('method');
		var review_id = jQuery(this).data('review_id');
		if(jQuery('input[name="appointment_rating'+booking_id+'"]').is(':checked')){
		var rating = jQuery('input[name="appointment_rating'+booking_id+'"]:checked').val();
		}
		var description = jQuery('#appointment_review_desc'+booking_id).val();

		var postdata = {  review_booking_id:booking_id,
						  provider_id:provider_id,
						  client_id:client_id,
						  method:method,
						  review_id:review_id,
						  rating:rating,
						  description:description,
						  general_ajax_action:"appointment_clientreview"
					     };
		jQuery.ajax({
				url  : ajax_url+"/assets/lib/admin_general_ajax.php",
				type : 'POST',					
				data : postdata,					
				dataType : 'html',					
				success  : function(response) {
					jQuery('.apt-loading-main').hide();	
					jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
				}
		   });
});
/********************************* Review Admin Area jQuery ******************************************/
/*****************************************************************************************************/
/* Publish,hide,Delete review */
jQuery(document).on('click','.apt_review_phd',function(){
		var ajax_url = header_object.plugin_path;	
		jQuery('.apt-loading-main').show();		
		var review_id = jQuery(this).data('review_id');
		var method = jQuery(this).data('method');
		var postdata = {  review_id:review_id,
						  method:method,
						  general_ajax_action:"publish_hide_delete_review"
					     };
		jQuery.ajax({
				url  : ajax_url+"/assets/lib/admin_general_ajax.php",
				type : 'POST',					
				data : postdata,					
				dataType : 'html',					
				success  : function(response) {
				jQuery('.apt-loading-main').hide();	
				jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
					window.location.reload();
				}
		   });
});

/* Toggle Code */	
jQuery(document).ready(function(){
 jQuery("[data-toggle='toggle']").bootstrapToggle('destroy')                 
    jQuery("[data-toggle='toggle']").bootstrapToggle();
});
jQuery(document).ajaxComplete(function(){
 jQuery("[data-toggle='toggle']").bootstrapToggle('destroy')                 
    jQuery("[data-toggle='toggle']").bootstrapToggle();
});

/* Below Code For popup remove sample data */
jQuery(document).ready(function($){
	//open popup
	jQuery('.cd-popup-trigger').on('click', function(event){
		event.preventDefault();
		jQuery('.cd-popup').addClass('is-visible');
	});
	
	//close popup
	jQuery('.cd-popup').on('click', function(event){
		if( jQuery(event.target).is('.cd-popup-close') || jQuery(event.target).is('.cd-popup') ) {
			event.preventDefault();
			jQuery(this).removeClass('is-visible');
		}
	});
	//close popup when clicking the esc keyboard button
	jQuery(document).keyup(function(event){
    	if(event.which=='27'){
    		jQuery('.cd-popup').removeClass('is-visible');
	    }
    });
	
	jQuery(document).on('click','.remove_popup_sample_data',function(){
		jQuery('.cd-popup').removeClass('is-visible');
	});
});

 /* Add */
 /* service */
  // jQuery(document).on('click','.apt-service-details',function(){
   // jQuery('.popover').hide();
// }); 

/* staff */
/*  jQuery(document).on('click','.apt-staff-details',function(){
  jQuery('#apt-close-popover-new-staff').trigger('click');
 }); 
 */


/* jQuery(document).on('click','.apt-delete-null-category',function(){
	if (confirm("Are you sure you want to delete this category?") == true) {
		var ajax_url = serviceObj.plugin_path;
		var category_id = jQuery(this).data('cid');
		jQuery('.apt-loading-main').show();
		var dataString = { category_id:category_id, category_action:'delete_blank_category' };
		jQuery.ajax({					
			url  : ajax_url+"/assets/lib/category_ajax.php",					
			type : 'POST',					
			data : dataString,					
			success  : function(response) {
				location.reload();
			}
		});
	}
}); */

jQuery(document).bind('ready ajaxComplete',function(){
	jQuery("#appointment_company_country_code").intlTelInput();
	jQuery(".staff_phone_number").intlTelInput({
		numberType: "polite",
		autoPlaceholder: "off",
		utilsScript: "utils.js"
	});
	 /* jQuery("#apt-phone").intlTelInput({'initialCountry':'nz'}); */
	
});

/* loader GIF upload */
 jQuery(document).ready(function(){

            jQuery(".appointment_frontend_loader_file").click(function(){

                var fd = new FormData();
                var files = jQuery('#file')[0].files[0];
                fd.append('file',files);
                jQuery.ajax({
                    url:ajax_url+"/assets/lib/admin_general_ajax.php",
                    type:'post',
                    data:fd,
                    contentType: false,
                    processData: false,
                    success:function(response){
                        if(response != 0){
                            jQuery("#img").attr("src",response);
                        }
                    },
                    error:function(response){
                        alert('error : ' + JSON.stringify(response));
                    }
                });
            });
        });

	/********************** Service Addon Form Validations ****************************/
jQuery(document).bind('ready ajaxComplete', function(){
	var ajax_url = header_object.plugin_path;	
	var serviceaddontitle_err_msg = admin_validation_err_msg.serviceaddontitle_err_msg;	
	var serviceaddonmaxqty_err_msg = admin_validation_err_msg.serviceaddonmaxqty_err_msg;	
	var serviceaddon_price_err_msg = admin_validation_err_msg.serviceaddon_price_err_msg;	
	var serviceaddon_validprice_err_msg = admin_validation_err_msg.serviceaddon_validprice_err_msg;
	
	jQuery.validator.addMethod("numeric_pattern", function(value, element) {
	return this.optional(element) || /^(?=.*[0-9])[- +()0-9]+$/.test(value);
	}, serviceaddon_validprice_err_msg);
	
	jQuery('#apt_create_service_addon').validate({
		rules: {
			service_title: {
										required: true,

				},
			
		    service_addons_price: {			
										required: true,
										number:true
				},
			service_maxqty: {			
										numeric_pattern:true
				}	
			
			},
		messages: {
					service_title: { required:serviceaddontitle_err_msg }, 					
					service_addons_price: { required:serviceaddon_price_err_msg , number:serviceaddon_validprice_err_msg},
					service_maxqty: {numeric_pattern:serviceaddonmaxqty_err_msg}
					
			}
	});
});			
/* Update Service Addon Validation */
jQuery(document).bind('ready ajaxComplete', function(){
	var serviceaddontitle_err_msg = admin_validation_err_msg.serviceaddontitle_err_msg;	
	var serviceaddonmaxqty_err_msg = admin_validation_err_msg.serviceaddonmaxqty_err_msg;
	var serviceaddon_price_err_msg = admin_validation_err_msg.serviceaddon_price_err_msg;	
	var serviceaddon_validprice_err_msg = admin_validation_err_msg.serviceaddon_validprice_err_msg;
	
	
	jQuery.validator.addMethod("numeric_pattern", function(value, element) {
	return this.optional(element) || /^(?=.*[0-9])[- +()0-9]+$/.test(value);
	}, serviceaddon_validprice_err_msg);
	
		jQuery('.apt_update_service_addon').each(function(){
				jQuery(this).validate({
					rules: {
						u_service_title: {
													required: true,

							},
						
						u_service_price: {			
													required: true,
													number:true
							},
						service_maxqty: {			
													numeric_pattern:true
							}	
						
						},
					messages: {
								u_service_title: { required:serviceaddontitle_err_msg }, 					
								u_service_price: { required:serviceaddon_price_err_msg , number:serviceaddon_validprice_err_msg},
								service_maxqty: {numeric_pattern:serviceaddonmaxqty_err_msg}
								
						}
				});
		});			
});		

/* Update Pricing Rule */
jQuery(document).bind('ready ajaxComplete', function(){	
	var serviceaddon_price_err_msg = admin_validation_err_msg.serviceaddon_price_err_msg;	
	var serviceaddon_validprice_err_msg = admin_validation_err_msg.serviceaddon_validprice_err_msg;
	var serviceaddon_qty_err_msg = admin_validation_err_msg.serviceaddon_qty_err_msg;	
	var serviceaddon_validqty_err_msg = admin_validation_err_msg.serviceaddon_validqty_err_msg;	
	
	jQuery.validator.addMethod("numeric_pattern", function(value, element) {
	return this.optional(element) || /^(?=.*[0-9])[- +()0-9]+$/.test(value);
	}, serviceaddon_validqty_err_msg);	
		
		jQuery('.service_addon_pricing').each(function(){					
				jQuery(this).validate({
					rules: {
						myedtpriceaddon: {
													required: true,
													number:true

							},
						
						txtedtqtyaddons: {			
													required: true,
													numeric_pattern:true
							}	
						
						},
					messages: {
								myedtpriceaddon: { required:serviceaddon_price_err_msg , number:serviceaddon_validprice_err_msg }, 					
								txtedtqtyaddons: { required:serviceaddon_qty_err_msg , numeric_pattern:serviceaddon_validqty_err_msg}
								
						}
				});
		});			
});				
	

/* Add Pricing Rule */
jQuery(document).bind('ready ajaxComplete', function(){	
	var serviceaddon_price_err_msg = admin_validation_err_msg.serviceaddon_price_err_msg;	
	var serviceaddon_validprice_err_msg = admin_validation_err_msg.serviceaddon_validprice_err_msg;
	var serviceaddon_qty_err_msg = admin_validation_err_msg.serviceaddon_qty_err_msg;	
	var serviceaddon_validqty_err_msg = admin_validation_err_msg.serviceaddon_validqty_err_msg;	
	
	jQuery.validator.addMethod("numeric_pattern", function(value, element) {
	return this.optional(element) || /^(?=.*[0-9])[- +()0-9]+$/.test(value);
	}, serviceaddon_validqty_err_msg);	
	
	jQuery('.add_addon_pricing').each(function(){
						
				jQuery(this).validate({
					rules: {
						mynewsspriceaddon: {
													required: true,
													number:true

							},
						
						mynewssqtyaddon: {			
													required: true,
													numeric_pattern:true
							}	
						
						},
					messages: {
								mynewsspriceaddon: { required:serviceaddon_price_err_msg , number:serviceaddon_validprice_err_msg }, 					
								mynewssqtyaddon: { required:serviceaddon_qty_err_msg , numeric_pattern:serviceaddon_validqty_err_msg}
								
						}
				});
		});			
});
	
/*** insert addons***/
 jQuery(document).on('click','#apt_create_service_addons',function(){
	if(jQuery('#apt_create_service_addon').valid()){
			jQuery('.apt-loading-main').show();
			var ajax_url = header_object.plugin_path;
			 var addon_title = jQuery('#apt-service-addons-title').val();
			 var addon_price = jQuery('#service_addons_price').val();
			 var addon_service_id = jQuery('#addon_service_id').val();
			 var addon_img_service = jQuery('#bdscaduploadedimg').val();
			 var addon_maxqty_service = jQuery('.maxqty').val();
			 			
			if (jQuery('.addon_multipleqty').prop("checked") == true) {
				var multipleqty_for_addon = 'Y';
			}
			else {
				var multipleqty_for_addon = 'N';
			}
			
			  var addons_data = { 
							   addon_service_id : addon_service_id,	
							   addon_title : addon_title,
							   addon_price : addon_price,
							   addon_img_service : addon_img_service,
							   addon_maxqty_service : addon_maxqty_service,
							   addon_multipleqty_status : multipleqty_for_addon,
							  action : "addons_inst_data"
								 };
			 
			 jQuery.ajax({
				type : 'post',
				data : addons_data,
				url : ajax_url+"/assets/lib/service_ajax.php",
				success : function(res){
					jQuery('.apt-loading-main').hide();
					jQuery('.mainheader_message_inner').show();
					appointment_hide_success_msg();
					location.reload();
					
				}
			});  
	}
	 
 });
 /* Delete Addons Permanently */	
jQuery(document).on('click','.delete_addon',function(){
		var ajax_url = header_object.plugin_path;
		var addon_service_id = jQuery(this).data('id');
		jQuery('.apt-loading-main').show();
		var postdata = { addon_service_id:addon_service_id,
						 action:'delete_addons'						 
		}
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/service_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {
						location.reload();
						jQuery('.apt-locations-container').html(response);
						jQuery('.apt-loading-main').hide();
						//jQuery('#location_detail_'+addon_service_id).fadeOut('slow');
						
					}
		});
});
 /* Delete Addons 
 jQuery(document).bind('ready ajaxComplete', function(){
		jQuery('.apt-delete-popover').popover({ 
				html : true,
				content: function() {
				  return jQuery('#'+jQuery(this).data('poid')).html();
				}
		});
});
/** Hide delete service & location popover 
jQuery(document).on('click', '.apt-close-popover-delete', function(){			
	jQuery('.popover').fadeOut();
});*/
 
 /*** update addons status***/
 jQuery(document).on('change','.update_service_addon_status',function(){
		jQuery('.apt-loading-main').show();	
		var ajax_url = header_object.plugin_path;			
		var addons_update_id = jQuery(this).data("id");
				
		var addon_title = jQuery('#apt-service-title'+addons_update_id).val();
		var addon_price = jQuery('#apt-service-price'+addons_update_id).val();
		var addon_service_id = jQuery('#addon_service_id').val();
		if(jQuery(this).is(':checked')){
			var status = 'E';
		}else{
			var status =  'D';
		}
	  var addons_data = { 
					   addons_update_id : addons_update_id,	
					   addon_title : addon_title,
					   addon_price : addon_price,
					   status : status,
					   addon_service_id : addon_service_id,
					   action : "addons_update_status"
					     };
	 jQuery.ajax({
        type : 'post',
        data : addons_data,
        url : ajax_url+"/assets/lib/service_ajax.php",
        success : function(res){
			jQuery('.apt-loading-main').hide();	
			jQuery('.mainheader_message_inner').show();
			appointment_hide_success_msg();
			
        } 
    });  
	 
	 
 });
 /*Update Addon*/
 jQuery(document).on('click','.update_service_addon',function(){
		
			var addons_update_id = jQuery(this).data("service_id");		
			if(!jQuery("#apt_update_service_addon_"+addons_update_id).valid()){
					return false;
			}
			jQuery('.apt-loading-main').show();	
			var ajax_url = header_object.plugin_path;	
			
			
			var addon_image = jQuery('#bdscad'+addons_update_id+'uploadedimg').val();
			var addon_title = jQuery('#apt-service-title'+addons_update_id).val();
			var addon_price = jQuery('#apt-service-price'+addons_update_id).val();
			var addon_maxqty_service = jQuery('.maxqty'+addons_update_id).val();
			 if (jQuery('.addon_multipleqty'+addons_update_id).prop("checked") == true) {
					var multipleqty_for_addon = 'Y';
				}
				else {
					var multipleqty_for_addon = 'N';
				}
			var addon_service_id = jQuery('#addon_service_id').val();
			var addons_data = { 
						   addons_update_id : addons_update_id,	
						   addon_image : addon_image,
						   addon_title : addon_title,
						   addon_price : addon_price,
						   addon_maxqty_service : addon_maxqty_service,
						   addon_multipleqty_status : multipleqty_for_addon,
						   addon_service_id : addon_service_id,
						   action : "addons_update"
							 };
		 
		 jQuery.ajax({
			type : 'post',
			data : addons_data,
			url : ajax_url+"/assets/lib/service_ajax.php",
			success : function(res){
				jQuery('.apt-loading-main').hide();
				jQuery('.mainheader_message_inner').show();
				appointment_hide_success_msg();
				location.reload();
			} 
		});  
 });
 /* SAVE QTY RULE BUTTON */
jQuery(document).on('click', '.myloadedbtnsave_addons', function () {
	var ajax_url = header_object.plugin_path;
    var editid = jQuery(this).data('id');
		
    var id = jQuery(this).data('addon_service_id');
	
	if(!jQuery("#myedtform_addonunits"+editid).valid()){
			return false;
	}
	
    var qty = jQuery('.myloadedqty_addons' + editid).val();
    var rules = jQuery('.myloadedrules_addons' + editid).val();
    var price = jQuery('.myloadedprice_addons' + editid).val();
	jQuery('.apt-loading-main').show();	
    var addon_updated_qty = {addon_service_id : id, addon_update_id : editid, unit : qty, rules : rules, rate : price,action : "addon_update_qty"}
	jQuery.ajax({
        type: 'post',
        data: addon_updated_qty,
        url: ajax_url + "/assets/lib/service_ajax.php",
        success: function (res) {
            jQuery('.apt-loading-main').hide();	
			jQuery('.mainheader_message_inner').show();
			appointment_hide_success_msg();
        }
    });
});

/* ADD NEW QTY RULE */
jQuery(document).on('click', '.mybtnaddnewqty_addon', function () {
	var ajax_url = header_object.plugin_path;
    var id = jQuery(this).data('id');
			
	if(!jQuery("#mynewaddedform_addonunits"+id).valid()){
			return false;
	}
	
	var qty = jQuery('.mynewqty_addons' + id).val();
	var rules = jQuery('.mynewrules_addons' + id).val();
    var price = jQuery('.mynewprice_addons' + id).val();	
	
	jQuery('.apt-loading-main').show();	
	var addons_qty_data = {addon_service_id : id,unit : qty,rules : rules,rate : price,action : "add_new_qty"}
	jQuery.ajax({
        type : 'post',
        data : addons_qty_data,
        url : ajax_url+"/assets/lib/service_ajax.php",
        success : function(res){			
			var rulerefreshdata = {addon_id:id,action:'get_addon_pricing_rules'}
			jQuery.ajax({
				type : 'post',
				data : rulerefreshdata,
				url : ajax_url+"/assets/lib/service_ajax.php",
				success : function(res){
					jQuery('.myaddonspricebyqty'+id).html(res);
					jQuery('.apt-loading-main').hide();
					jQuery('.mainheader_message_inner').show();
					appointment_hide_success_msg();	
				}
			});			
        }
    });  
   
 });
 /* Delete Rule */
 jQuery(document).on('click', '.myloadedbtndelete_addons', function () {
	 var ajax_url = header_object.plugin_path;
	 var deleteid = jQuery(this).data('id');
	 
		jQuery('.apt-loading-main').show();
		var postdata = { addon_service_id:deleteid,
						 action:'delete_addons_qty'						 
		}
		jQuery.ajax({					
					url  : ajax_url+"/assets/lib/service_ajax.php",					
					type : 'POST',					
					data : postdata,					
					dataType : 'html',					
					success  : function(response) {						
						jQuery('.myaddon-qty_price_row'+deleteid).hide('slow');
						jQuery('.apt-loading-main').hide();	
						jQuery('.mainheader_message_inner').show();
						appointment_hide_success_msg();
													
					}
		});
 });

 
jQuery(document).on('click','.verify_gc_account',function () {
	var redirect_link = jQuery(this).data('hreflink');
	window.open(redirect_link, '_self');
});

function ob_formatDate(date) {
	var d = new Date(date),
	month = '' + (d.getMonth() + 1),
	day = '' + d.getDate(),
	year = d.getFullYear();

	if (month.length < 2) month = '0' + month;
	if (day.length < 2) day = '0' + day;

	return [year, month, day].join('-');
}

jQuery(document).on('change','#apt_booking_datetime',function () {
	jQuery('.apt-loading-main').show();
	var ajax_url = header_object.plugin_path;
	var selected_date = jQuery(this).val();
	
	
	var sel_date = ob_formatDate(selected_date);
	/* var provider_id = jQuery(this).data('selstaffid'); */
	var provider_id_1 = jQuery('#edit-booking-details-view #apt_booking_provider').val();
	var provider_id = (typeof provider_id_1 != 'undefined')?provider_id_1:jQuery(this).data('selstaffid');
	
	jQuery(this).attr('data-sel_date', sel_date);
	var getslots_data = {
		'seldate':sel_date,
		'selstaffid':provider_id,
		'action':'apt_get_provider_slots'
	};
	jQuery.ajax({
		type : 'POST',
		url  : ajax_url+"/assets/lib/apt_client_ajax.php",
		data : getslots_data,
		dataType : 'html',
		success  : function(res) {
			console.log(res);
			jQuery('#apt_booking_time').html(res);
			jQuery('#apt_booking_time').selectpicker('refresh');
			jQuery('.apt-loading-main').hide();
		
		}
	});
});

/* Save GC Settings Start */

jQuery(document).on('click','#apt_save_gc_settings',function () {
	jQuery('.apt-loading-main').show();
	var ajax_url = header_object.plugin_path;
	var appointup_gc_id = jQuery('.appointup_gc_id').val();
	var apt_gc_client_id = jQuery('#apt_gc_client_id').val();
	var apt_gc_client_secret = jQuery('#apt_gc_client_secret').val();
	var apt_gc_frontend_url = jQuery('#apt_gc_frontend_url').val();
	var apt_gc_admin_url = jQuery('#apt_gc_admin_url').val();
	var bwid = jQuery('input[name="bwid"]').val();
	if(jQuery('.gc_enable_disable').prop('checked')==true){
		var gc_enable_disable ='Y';
	} else{ 
		var gc_enable_disable='N';
	}
	if(jQuery('.appointup_gc_twowaysync').prop('checked')==true){
		var appointup_gc_twowaysync ='Y';
	} else{ 
		var appointup_gc_twowaysync='N';
	}
	var datastring = {
		'gc_enable_disable' : gc_enable_disable,
		'apt_gc_client_id' : apt_gc_client_id,
		'apt_gc_client_secret' : apt_gc_client_secret,
		'apt_gc_frontend_url' : apt_gc_frontend_url,
		'apt_gc_admin_url' : apt_gc_admin_url,
		'appointup_gc_twowaysync' : appointup_gc_twowaysync,
		'appointup_gc_id' : appointup_gc_id,
		'bwid':bwid,
		'GC_settings' : 1
	};
	jQuery.ajax({
		type : 'POST',
		url  : ajax_url+"/assets/lib/setting_ajax.php",
		data : datastring,
		dataType : 'html',
		success  : function(res) {
			jQuery('.apt-loading-main').hide();
			jQuery('.mainheader_message_inner').show();
			appointment_hide_success_msg();
		}
	});
});

/* Save GC Settings End */



jQuery(document).ready(function(){
	jQuery('#user-profile-booking-table').DataTable();
	jQuery('#user-all-bookings-details').DataTable();
	
});