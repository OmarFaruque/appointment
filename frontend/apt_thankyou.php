<?php
$root_dir = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
if (file_exists($root_dir.'/wp-load.php')) {
		require_once($root_dir.'/wp-load.php');	
	}
$plugin_cart_url = plugins_url('',  dirname(__FILE__));	
$plugin_redirect_url = site_url();
?>
<style>
	html{
		
		-webkit-font-smoothing: antialiased !important;
		text-shadow: 1px 1px 1px rgba(0,0,0,0.004);
	}
	#ct  { 	
		font-family: 'Raleway', sans-serif;
		/*font-family: 'Montserrat', sans-serif;*/
		font-weight: normal;
		font-size: 13px;
		line-height: 24px;
	}	
	.ct-wrapper{width: 100%;
		margin: 0px;
		padding: 0px;
		float: left;
		display: block;
		min-height: 100%;
		position: relative;
	}
	#ct .ct-container {
		width: 1170px;
		margin: 0px auto;
		position: relative;
		clear: both;
	}
	#ct .booking-tankyou{
		width: 40%;
		text-align: center;
		padding: 40px 30px;
		-webkit-border-radius: 4px;
		-moz-border-radius: 4px;
		-o-border-radius: 4px;
		-ms-border-radius: 4px;
		border-radius: 4px;
		box-shadow: 0px 1px 8px #aeaeae;
		color: #373854;
		position : fixed;
		top: 50% !important;
		left: 50% !important;
		-webkit-transform: translate(-50%, -50%);
		-moz-transform: translate(-50%, -50%);
		-ms-transform: translate(-50%, -50%);
		transform: translate(-50%, -50%);
	}
	
		
	#ct h1.header1{
		font-size: 34px;
		font-weight: normal;
		color: inherit;
		margin:0;
		padding: 10px 0px 15px;
		
	}
	#ct p.thankyou-text{
		font-size: 18px;
		font-weight: normal;
		color: inherit;
		margin:0;
		line-height: 1.2;
	}

	#ct h3.header3{
		font-size: 24px;
		font-weight: normal;
		color: inherit;
		margin:0;
		padding: 5px 0px 8px;
	}
	
	
	
/* Responsive design */	
/* Desktops and laptops ----------- */
@media only screen and (min-width:1025px) and (max-width:1270px) {
	#ct .ct-container{width: 900px;}
	#bt .booking-tankyou {width: 50%;}
}	

/*----*****---- << iPads (portrait and landscape)  >> ----*****----*/	
 @media only screen and (min-width:768px) and (max-width:1024px) {
	#ct .ct-container{width: 720px;}	
	#ct .booking-tankyou {width: 60%;}
}	


@media only screen and (max-width:767px) {
	#ct .ct-container{width: 460px;}
	#ct .booking-tankyou{width: 75%;padding: 30px 20px;}
	#ct h1.header1 {font-size: 28px;}
	#ct h3.header3 {font-size: 18px;}	
	#ct p.thankyou-text {font-size: 15px;}
}
/* Smartphones (portrait and landscape) ----------- */
@media only screen and (min-width: 320px) and (max-width: 480px) {
	#ct .ct-container{width: 300px;}
	#ct .booking-tankyou{width: 86%;padding: 20px 10px;}
	#ct h1.header1 {font-size: 24px;}
	#ct h3.header3 {font-size: 16px;}
	#ct p.thankyou-text {font-size: 13px;}
}



/* Mobile Portrait Size to Mobile Landscape Size (devices and browsers) */
@media only screen and (max-width: 319px) {
	#ct .ct-container{width: 250px;}
	#ct .booking-tankyou{width: 86%;padding: 20px 10px;}
	#ct h1.header1 {font-size: 24px;}
	#ct h3.header3 {font-size: 16px;}
	#ct p.thankyou-text {font-size: 13px;}
}
</style>
<div id="ct" class="ct-wrapper">
	<div class="ct-container">
		<div class="booking-tankyou">
            <h1 class="header1"><?php echo __("Congratulations!","apt");?></h1>
            <h3 class="header3"><?php echo __("Your payment was successful.","apt");?></h3>
            <p class="thankyou-text"><?php echo __("You will be notified with details of appointment(s).","apt");?></p>
		</div>
	</div>
</div>
<!--<div class="container4" >
<p>
<span>Thankyou! for booking appointment.</span>
<span>You will be notified with details of appointment(s).</span></p>
</div>-->
<script type="text/javascript">
var cart_page_url = "<?php echo $plugin_redirect_url; ?>/wp-admin/admin.php?page=appointment_menu";
var timer = <?php echo get_option('appointment_thankyou_page_rdtime' . '_' . get_current_user_id()); ?>; //seconds
   website = cart_page_url;
   function delayer() {
       window.location = website;
   }
   setTimeout('delayer()', timer);
</script>
