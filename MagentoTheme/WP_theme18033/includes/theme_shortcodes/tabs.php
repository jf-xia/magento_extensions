<?php
/**
 * Tabs
 *
 */

function tabs_shortcode($atts, $content = null) {

    $output = '<div class="tabs">';
    $output .= '<div class="tab-menu">';
    $output .= '<ul>';

    //Build tab menu
    $numTabs = count($atts);

    for($i = 1; $i <= $numTabs; $i++){
        $output .= '<li><a href="#tab'.$i.'">'.$atts['tab'.$i].'</a></li>';
    }

    $output .= '</ul>';
    $output .= '<div class="clear"></div>';
    $output .= '</div><!-- .tab-menu (end) -->';
    $output .= '<div class="tab-wrapper">';

    //Build content of tabs
    $tabContent = do_shortcode($content);
    $find = array();
    $replace = array();
    foreach($atts as $key => $value){
        $find[] = '['.$key.']';
        $find[] = '[/'.$key.']';
        $replace[] = '<div id="'.$key.'" class="tab">';
        $replace[] = '</div><!-- .tab (end) -->';
    }

    $tabContent = str_replace($find, $replace, $tabContent);

    $output .= $tabContent;

    $output .= '</div><!-- .tab-wrapper (end) -->';
    $output .= '</div><!-- .tabs (end) -->';

    return $output;

}

add_shortcode('tabs', 'tabs_shortcode');
?>