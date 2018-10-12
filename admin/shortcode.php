<?php 
/*
* Shortcode details
*/
include(dirname(__FILE__).'/header.php');
?>
<div class="shortcode_Wrap">
    <div class="shortcode-inner">
        <h3><?php _e('Shortcode', 'apt'); ?></h3>
        <hr/>
        <h5><?php _e('Appointment Shortcode'); ?></h5>
        <p><?php _e('Use <code>[appointment bwid='.get_current_user_id().']</code> shortcode in your page from admin or use <code>&lt;?php echo do_shortcode("[appointment bwid='.get_current_user_id().']"); ?&gt;</code> in your php template.', 'apt'); ?></p><hr/>
        
        <h5><?php _e('Appointment Client Dashboard Shortcode'); ?></h5>
        <p><?php _e('Use <code>[appointment_client_appointments bwid='.get_current_user_id().']</code> shortcode in your page from admin or use <code>&lt;?php echo do_shortcode("[appointment_client_appointments bwid='.get_current_user_id().']"); ?&gt;</code> in your php template.', 'apt'); ?></p><hr/>
        
    </div>
</div>

