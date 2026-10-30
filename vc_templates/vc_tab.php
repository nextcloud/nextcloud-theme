<?php

/**
 * The template for displaying [vc_tab] shortcode output.
 *
 * This template can be overridden by copying it to yourtheme/vc_templates/vc_tab.php.
 *
 * @see https://kb.wpbakery.com/docs/developers-how-tos/change-shortcodes-html-output
 */

if (! defined('ABSPATH')) {
	die('-1');
}

/**
 * Shortcode attributes
 *
 * @var $atts
 * @var $tab_id
 * @var $title
 * @var - $content shortcode content
 * Shortcode class
 * @var WPBakeryShortCode_Vc_Tab $this
 */
$tab_id = $title = '';
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);

wp_enqueue_script('jquery_ui_tabs_rotate');

$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_tab ui-tabs-panel wpb_ui-tabs-hide vc_clearfix', $this->settings['base'], $atts);

$output = '
	<div id="tab-' . (empty($tab_id) ? sanitize_title($title) : esc_attr($tab_id)) . '" class="' . esc_attr($css_class) . '">
		' . ((trim($content) === '') ? esc_html__('Empty tab. Edit page to add content here.', 'js_composer') : wpb_js_remove_wpautop($content)) . '
	</div>
';

return $output;
