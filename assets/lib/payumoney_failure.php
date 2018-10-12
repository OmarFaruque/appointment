<?php
if(!session_id()) { @session_start(); }
    $root = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));	
	if (file_exists($root.'/wp-load.php')) {
		require_once($root.'/wp-load.php');	
	}		
	
	$plugin_url = plugins_url('',  dirname(__FILE__));
	$base =   dirname(dirname(dirname(__FILE__)));
$status=$_POST["status"];
$firstname=$_POST["firstname"];
$amount=$_POST["amount"];
$txnid=$_POST["txnid"];

$posted_hash=$_POST["hash"];
$key=$_POST["key"];
$productinfo=$_POST["productinfo"];
$email=$_POST["email"];
$salt=get_option('bookdice_payumoney_saltkey');

If (isset($_POST["additionalCharges"])) {
       $additionalCharges=$_POST["additionalCharges"];
        $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
        
                  }
	else {	  

        $retHashSeq = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;

         }
		 $hash = hash("sha512", $retHashSeq);
  
       if ($hash != $posted_hash) {
	       echo "Invalid Transaction. Please try again";
		   }
	   else {

         echo "<h3>Your order status is ". $status .".</h3>";
         echo "<h4>Your transaction id for this transaction is ".$txnid.". You may try making the payment by clicking the link below.</h4>";
          
		 } 
?>
<!--Please enter your website homepagge URL -->
<p><a href="<?php echo site_url(); ?>"> Try Again</a></p>