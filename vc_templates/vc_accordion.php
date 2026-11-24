<?php

/**
 * The template for displaying [vc_accordion] shortcode output of 'Accordion' element.
 *
 * This template can be overridden by copying it to yourtheme/vc_templates/vc_accordion.php.
 *
 * @see https://kb.wpbakery.com/docs/developers-how-tos/change-shortcodes-html-output
 *
 * @deprecated
 *
 * Shortcode attributes
 * @var $atts
 * @var $title
 * @var $el_class
 * @var $collapsible
 * @var $disable_keyboard
 * @var $active_tab
 * @var - $content shortcode content
 *  Shortcode class
 * @var WPBakeryShortCode_Vc_Accordion $this
 */

if (! defined('ABSPATH')) {
	die('-1');
}

$title = $el_class = $collapsible = $disable_keyboard = $active_tab = '';
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);

wp_enqueue_script('jquery-ui-accordion');
$el_class = $this->getExtraClass($el_class);
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_accordion wpb_content_element ' . $el_class . ' not-column-inherit', $this->settings['base'], $atts);

$output = '
	<div class="' . esc_attr($css_class) . '" data-collapsible="' . esc_attr($collapsible) . '" data-vc-disable-keydown="' . (esc_attr(($disable_keyboard === 'yes' ? 'true' : 'false'))) . '" data-active-tab="' . esc_attr($active_tab) . '">
		<div class="wpb_wrapper wpb_accordion_wrapper ui-accordion">
' . wpb_widget_title([
	'title' => $title,
	'extraclass' => 'wpb_accordion_heading',
]) . '
' . wpb_js_remove_wpautop($content) . '
		</div>
	</div>
';

return $output;
