<?php
$output = $title = $alias = $el_class = '';
extract( shortcode_atts( array(
    'title' => '',
    'alias' => '',
    'el_class' => ''
), $atts ) );

$el_class = $this->getExtraClass($el_class);
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_sbslider_element wpb_content_element'.$el_class, $this->settings['base']);

$output .= '<div class="'.$css_class.'">';
$output .= wpb_widget_title(array('title' => $title, 'extraclass' => 'wpb_sbslider_heading'));
$output .= do_shortcode('[showbiz '.$alias.']');
$output .= '</div>'.$this->endBlockComment('wpb_revslider_element')."\n";

echo $output;