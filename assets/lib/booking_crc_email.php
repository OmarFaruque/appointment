<?php 
   
	$root = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));	

	if (file_exists($root.'/wp-load.php')) {
		require_once($root.'/wp-load.php');	
	}
	 $pageURL = 'http';
     if (@$_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
     $pageURL .= "://";
     if ($_SERVER["SERVER_PORT"] != "80") {
      $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
     }else {
      $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
     }
	$get_values_from_url=explode('?',$pageURL);	
	$order_and_bookingstatus = explode('-',$get_values_from_url[1]);
	$info = base64_decode($order_and_bookingstatus[0]);
	$values = explode('-',$info); 
	$providerinfo=explode('+',base64_decode($order_and_bookingstatus[1]));
	$plugin_url_for_ajax = plugins_url('',dirname(__FILE__));
	?>
	<script src="<?php echo $plugin_url_for_ajax ?>/js/jquery-min.js"></script>
	<style>
	/* confirm reject booking from email notificaton */	
		.appointment_cr_container {width: 100%;height: 100%;background: #4C4C4C;text-align:center;}
		.appointment_cr_container .button_container {margin: 30px;}
		.appointment_cr_container .appointment_confirm label{margin: 0px 9px 9px 0px;}
		.appointment_cr_container .appointment_confirm textarea{height: 72px;width: 63%;padding: 0px;    border-radius: 3px;transition: all 0.3s ease-in-out 0s;outline: medium none;border: 1px solid #DDD;}
		.appointment_cr_container .appointment_confirm {background: none repeat scroll 0% 0% #F8F8F8;
		border-radius: 19px;border: 1px solid #008000;left: 33%;top: 33%;position: absolute;width: 500px;
		height: auto;} 
		.appointment_cr_container  .button_container .btn { font-size:13px; width:100px; height:30px; background:#49b2e1; color:#FFF;border:none;margin:0px 0px 0px 10px;border-radius:4px;padding:0px !important; }
	</style>
	<script type="text/javascript">
		var ob_cr_information = {"order_id":"<?php echo base64_decode($values[0]);?>","appointment_status":"<?php echo trim($values[1]);?>","provider_id":"<?php echo $providerinfo[0];?>","redirection_url":"<?php echo site_url();?>","plugin_path":"<?php echo $plugin_url_for_ajax;?>","booking_id":"<?php echo $providerinfo[1]; ?>"};
	</script>	
	<?php if(trim($values[1])!='' && trim($values[1])=='confirm'){ ?>
	<div class="appointment_cr_container">
	<div class="appointment_confirm">
	<div class="appointment_reject_content">
	<h3><?php echo __("Do you want to confirm this appointment?","apt");?></h3>
	<textarea type="text" id="confirm_note"></textarea>
	</div>
	<div class="button_container">
	<button class="btn" name="appointment_confirm_ok" id="confirm_ok"><?php echo __("OK","apt");?></button>
	<button class="btn" name="appointment_confirm_cancel" id="confirm_cancel" ><?php echo __("Cancel","apt");?></button>
	</div>
	</div>
	</div>

	<?php }elseif(trim($values[1])!='' && trim($values[1])=='reject'){ ?>
	<div class="appointment_cr_container">
	<div class="appointment_confirm">
	<div class="appointment_reject_content">
	<h3><?php echo __("Do you want to reject this appointment?","apt");?></h3>
	<label id="reject_error_label"><?php echo __("If Yes,Please Enter Reject Reason","apt");?><sup>*</sup></label>
	<textarea type="text" id="reject_reason"></textarea>
	</div>
	<div class="button_container">
	<button class="btn" name="appointment_reject_ok" id="reject_ok"><?php echo __("OK","apt");?></button>
	<button class="btn" name="appointment_reject_cancel" id="reject_cancel" ><?php echo __("Cancel","apt");?></button>
	</div>
	</div>
	<div>
	<?php } elseif(trim($values[1])!='' && trim($values[1])=='clientcancel'){ ?>
	<div class="appointment_cr_container">
	<div class="appointment_confirm">
	<div class="appointment_reject_content">
	<h3><?php echo __("Do you want to cancel this appointment?","apt");?></h3>
	<label id="cancel_error_label"><?php echo __("If Yes,Please Enter Cancel Reason","apt");?><sup>*</sup></label>
	<textarea type="text" id="cancel_reason"></textarea>
	</div>
	<div class="button_container">
	<button class="btn" name="appointment_cancel_ok" id="cancel_ok"><?php echo __("OK","apt");?></button>
	<button class="btn" name="appointment_cancel_cancel" id="cancel_cancel" ><?php echo __("Cancel","apt");?></button>
	</div>
	</div>
	<div>
	<?php } 
		
	?>
	<script>
		 /* appointment confirm link click function from email notification */
	 $('#confirm_ok').click( function(){		 
		 jQuery(this).attr('disabled','disabled');
		 $('.appointment_confirm').show();
		 var redirectionurl = ob_cr_information.redirection_url;
		 var ajaxurl=ob_cr_information.plugin_path;
		 var booking_id=ob_cr_information.booking_id;
		 var action_content = $('#confirm_note').val();
		 var getdata = {					
					booking_id:booking_id,
					method:'C',
					action_content:action_content,
					general_ajax_action:'c_r_cs_cc_appointment'
					}	
		
	 if(!jQuery(".appointment_confirm").is(':visible')){
			jQuery(".appointment_confirm").show();
	 }else{
		 $(this).html('<img disabled="disabled" src="'+ajaxurl+'/images/ajax-loader.gif" />');
	 
		$.ajax({
							url  : ajaxurl+"/lib/admin_general_ajax.php",
							type : 'POST',
							data : getdata,
							dataType : 'html',
							success  : function(response) {							
								alert("Thank You Booking Confirmed");
								window.location.href=redirectionurl;			
							},
							error: function (xhr, ajaxOptions, thrownError) {
							alert(xhr);
							}
					});
			}
		 
		 });
	 
	
	/* appointment confirm cancel link click function from email notification */
	$('#confirm_cancel').click( function(){
	  var redirectionurl = ob_cr_information.redirection_url;
	  window.location.href=redirectionurl;
	 });
	 
	 
	 
	 /* appointment reject link click function from email notification */
	 $('#reject_ok').click( function(){
		jQuery(this).attr('disabled','disabled');
	 $('.appointment_confirm').show();
	 var redirectionurl = ob_cr_information.redirection_url;	
	 var ajaxurl=ob_cr_information.plugin_path;
	 var booking_id=ob_cr_information.booking_id;
	 var action_content = $('#reject_reason').val();
	 var getdata = {
				booking_id:booking_id,
				method:'R',
				action_content:action_content,
				general_ajax_action:'c_r_cs_cc_appointment'
				};
	 if(reject_reason ==""){
	 $('#reject_error_label').css("color","red");
	 }else{
		  $(this).html('<img disabled="disabled" src="'+ajaxurl+'/images/ajax-loader.gif" />');
	 $.ajax({
						url  : ajaxurl+"/lib/admin_general_ajax.php",
						type : 'POST',
						data : getdata,
						dataType : 'html',
						success  : function(response) {
						 alert("Thank You Booking Rejected");
						 window.location.href=redirectionurl;
						},
						error: function (xhr, ajaxOptions, thrownError) {
						alert(xhr);
						}
				});
	 }
	 });
	  /* appointment reject cancel click function from email notification */
	 $('#reject_cancel').click( function(){
		 var redirectionurl = ob_cr_information.redirection_url;
		window.location.href=redirectionurl;
	 });
	 
	/* appointment cancel link click function from email notification */
	 $('#cancel_ok').click( function(){
	 $('.appointment_confirm').show();
	 var redirectionurl = ob_cr_information.redirection_url;
	 var ajaxurl=ob_cr_information.plugin_path;
	 var booking_id=ob_cr_information.booking_id;
	 var action_content = $('#cancel_reason').val();
	 var getdata = {
				booking_id:booking_id,
				method:'CC',
				action_content:action_content,
				general_ajax_action:'c_r_cs_cc_appointment'
				};
	 if(cancel_reason ==""){
	 $('#cancel_error_label').css("color","red");
	 }else{
		jQuery(this).attr('disabled','disabled');
		  $(this).html('<img disabled="disabled" src="'+ajaxurl+'/images/ajax-loader.gif" />');
	 $.ajax({
						url  : ajaxurl+"/lib/admin_general_ajax.php",
						type : 'POST',
						data : getdata,
						dataType : 'html',
						success  : function(response) {
						 alert("Thank You Booking Canceled");
						 window.location.href=redirectionurl;
						},
						error: function (xhr, ajaxOptions, thrownError) {
						alert(xhr);
						}
				});
	 }
	 });
	  /* appointment reject cancel click function from email notification */
	 $('#cancel_cancel').click( function(){
		 var redirectionurl = ob_cr_information.redirection_url;
		window.location.href=redirectionurl;
	 });
	</script>
	
	