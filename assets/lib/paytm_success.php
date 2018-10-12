<?php
if(!session_id()) { @session_start(); }
    $root = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));	
	if (file_exists($root.'/wp-load.php')) {
		require_once($root.'/wp-load.php');	
	}		
	
	$plugin_url = plugins_url('',  dirname(__FILE__));
	$base =   dirname(dirname(dirname(__FILE__)));

if (isset($_POST["STATUS"]) && $_POST["STATUS"] == "TXN_SUCCESS") {
	$transaction_id = $_REQUEST['TXNID'];
	$bwid = $_GET['bwid'];
	?>
	<style>
	#apt .apt-loader .apt-first{
		border: 3px solid <?php echo get_option('appointment_bg_text_color'.'_'.$bwid); ?> !important;
	}
	#apt .apt-loader .apt-second{
		border: 3px solid <?php echo get_option('appointment_primary_color'.'_'.$bwid); ?> !important;
	}
	#apt .apt-loader .apt-third{
		border: 3px solid <?php echo get_option('appointment_secondary_color'.'_'.$bwid); ?> !important;
	}
	</style>
	<div class="loader">
		<div class="apt-loader">
			<span class="apt-first"></span>
			<span class="apt-second"></span>
			<span class="apt-third"></span>
		</div>
	</div>
    <script src="<?php echo $plugin_url; ?>/js/jquery-2.1.4.min.js" type="text/javascript"></script>
	<script>
	function ct_checkout_at_booking_complete(trans_id){
		jQuery('.loader').show();
		jQuery.ajax({
			type:'POST',
			url:"<?php echo $plugin_url; ?>/lib/apt_front_booking_complete.php",
			data:{ transaction_id:trans_id },
			success:function(response){
				window.location.href=response;
			}
		});
	}
	ct_checkout_at_booking_complete('<?php echo $transaction_id; ?>');
	</script>
	<?php
}
else {
	echo "<h4>Transaction status is failure. You may try making the payment by clicking the link below.</h4><p><a href='".site_url()."/appointment;'> Try Again</a></p>";
	
}