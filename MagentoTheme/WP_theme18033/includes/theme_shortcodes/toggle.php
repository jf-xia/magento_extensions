<?php
/**
 * Toggle
 *
 */

function toggle_shortcode($atts, $content = null) {

    extract(shortcode_atts(
        array(
            'title' => 'This is your title'
    ), $atts));

    $output = '<div class="toggle">';
    $output .= '<a href="#" class="trigger"><span>+</span>'.$title.'</a>';
    $output .= '<div class="box">';
    $output .= do_shortcode($content);
    $output .= '</div><!-- .box (end) -->';
    $output .= '</div><!-- .toggle (end) -->';

    return $output;

}

add_shortcode('toggle', 'toggle_shortcode');
?>