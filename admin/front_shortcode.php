<?php 
if ( ! defined( 'ABSPATH' ) ) exit;

include(dirname(__FILE__).'/header.php');
$plugin_url_for_ajax = plugins_url('',  dirname(__FILE__));
?>
	<div class="container"> 
		<div id="apt-appointment-welcome">
			<div class="apt-welcome-main col-md-12 col-sm-12">
				<h1><?php _e('Welcome to Appointment', 'apt'); ?></h1>
				<div class="apt-into-text">
					<?php _e('Thank you for choosing appointment Pro! You can use appointment booking page in frontend using the shortcode [appointment bwid="business_owner_id"]', 'apt'); ?>. 
				</div>
				<div class="apt-appointment-badge">
					<img src="<?php echo $plugin_url_for_ajax; ?>/assets/images/logo-appointment.png" />
				</div>
			</div>
			
			<div class="apt-welcome-main col-md-12 col-sm-12">
				<h1> How to create Front booking page of appointment</h1>
				<div class="apt-into-text">
					<h3> Step 1 </h3>
					To create front booking page first you have to go in page menu from admin panel. give title of the page which you want.
					
					<h3> Step 2 </h3>
					In Second step, we can use [appointment bwid="business_owner_id"] shortcode on page which we had created for front booking page. we can use html/text content box of page to add shortcode of appointment.
					
					<h3> Step 3 </h3>
					Once you added shortcode then you can save or update your front page and its look something like below
					
					<img style="width: 100%;" src="<?php echo $plugin_url_for_ajax; ?>/assets/images/front_shortcode_page.png" />
					
				</div>
			</div>
		</div>
	</div>

<?php
include(dirname(__FILE__).'/footer.php');
?>