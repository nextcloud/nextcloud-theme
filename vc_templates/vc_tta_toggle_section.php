<?php

/**
 * The template for displaying [vc_tt_toggle_section] shortcode output.
 *
 * This template can be overridden by copying it to yourtheme/vc_templates/vc_tt_toggle_section.php.
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
 * @var - $content shortcode content
 * @var WPBakeryShortCode_Vc_Tta_Toggle_Section $this
 */
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
$this->resetVariables($atts, $content);
WPBakeryShortCode_Vc_Tta_Toggle_Section::$self_count++;
WPBakeryShortCode_Vc_Tta_Toggle_Section::$section_info[] = $atts;
$is_page_editable = vc_is_page_editable();

$output = '<div class="' . esc_attr($this->getElementClasses()) . '"';
$output .= ' id="' . esc_attr($this->getTemplateVariable('tab_id')) . '"';
$output .= ' data-vc-section-index="' . esc_attr($atts['section_index']) . '"';
$output .= ' data-vc-content=".vc_tta-panel-body">';
$output .= '<div class="vc_tta-panel-heading">';
$output .= $this->getTemplateVariable('heading');
// $output .= '<h4 class="custom-accordion-heading vc_tta-panel-title vc_tta-controls-icon-position-right" role="tab" id="custom-heading-' . esc_attr( $atts['tab_id'] ) . '">';
// $output .= '<a href="#'.esc_attr( $atts['tab_id'] ).'" data-vc-accordion="" data-vc-container=".vc_tta-container">';
// $output .= '<span class="vc_tta-title-text">'.esc_html( $atts['title'] ).'</span>';
// $output .= '</a>';
// $output .= '</h4>';
$output .= '</div>';
$output .= '<div class="vc_tta-panel-body">';
if ($is_page_editable) {
	$output .= '<div data-js-panel-body>'; // fix for fe - shortcodes container, not required in b.e.
}
$output .= $this->getTemplateVariable('content');
if ($is_page_editable) {
	$output .= '</div>';
}
$output .= '</div>';
$output .= '</div>';

return $output;
