<?php
/**
 * Alert boxes
 */

//alert
function shortcode_alert($args, $content) {
	return '<div class="alert-box error-box">'.do_shortcode($content).'</div>';
}

add_shortcode('alert', 'shortcode_alert');

//approved
function shortcode_approved($args, $content) {
	return '<div class="alert-box approved-box">'.do_shortcode($content).'</div>';
}

add_shortcode('approved', 'shortcode_approved');

//attention
function shortcode_attention($args, $content) {
	return '<div class="alert-box attention-box">'.do_shortcode($content).'</div>';
}

add_shortcode('attention', 'shortcode_attention');

//notice
function shortcode_notice($args, $content) {
	return '<div class="alert-box notice-box">'.do_shortcode($content).'</div>';
}

add_shortcode('notice', 'shortcode_notice');
?>