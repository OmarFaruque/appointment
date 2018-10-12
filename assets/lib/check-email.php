<?php
$root = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));
if (file_exists($root . '/wp-load.php')) {
    require_once($root . '/wp-load.php');
}
if (!defined('ABSPATH'))
    exit;
/* direct access prohibited  */
$email = $_POST['email'];
if (!email_exists($email)) {
    echo "true";
} else {
    echo "false";
}
?>