<?php

/**
 * The template for displaying [vc_tt_pageable_section] shortcode output.
 *
 * This template can be overridden by copying it to yourtheme/vc_templates/vc_tt_pageable_section.php.
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
 * @var $el_id
 * @var $el_class
 * @var - $content shortcode content
 * @var WPBakeryShortCode_Vc_Tta_Section $this
 */
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
$this->resetVariables($atts, $content);
WPBakeryShortCode_Vc_Tta_Section::$self_count++;
WPBakeryShortCode_Vc_Tta_Section::$section_info[] = $atts;
$is_page_editable = vc_is_page_editable();

$output = '';
$wrapper_attributes = [];
if (! empty($atts['el_id'])) {
	$wrapper_attributes[] = 'id="' . esc_attr($atts['el_id']) . '"';
}
$output .= '<div ' . implode(' ', $wrapper_attributes) . ' class="' . esc_attr($this->getElementClasses()) . '"';
$output .= ' id="' . esc_attr($this->getTemplateVariable('tab_id')) . '"';
$output .= ' data-vc-content=".vc_tta-panel-body">';
$output .= '<div class="vc_tta-panel-body">';
if ($is_page_editable) {
	$output .= '<div data-js-panel-body>'; // fix for fe - shortcodes container, not required in b.e.
}
$output .= $this->getTemplateVariable('basic-heading');
$output .= $this->getTemplateVariable('content');
if ($is_page_editable) {
	$output .= '</div>';
}
$output .= '</div>';
$output .= '</div>';

return $output;
