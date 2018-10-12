<?php
$root = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));
if (file_exists($root . '/wp-load.php')) {
    require_once($root . '/wp-load.php');
}
$username = $_POST['username'];
if (validate_username($username) && username_exists($username) == Null) {
    echo "true";
} else {
    echo "false";
}
?>