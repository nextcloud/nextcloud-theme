<?php

/**
 * The template for displaying [vc_tt_section] shortcode output.
 *
 * This template can be overridden by copying it to yourtheme/vc_templates/vc_tt_section.php.
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
 * @var WPBakeryShortCode_Vc_Tta_Section $this
 */
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
$this->resetVariables($atts, $content);
WPBakeryShortCode_Vc_Tta_Section::$self_count++;
WPBakeryShortCode_Vc_Tta_Section::$section_info[] = $atts;
$is_page_editable = vc_is_page_editable();
$tab_id = esc_attr($this->getTemplateVariable('tab_id'));

$output = '<div class="' . esc_attr($this->getElementClasses()) . '"';
$output .= ' id="' . esc_attr($this->getTemplateVariable('tab_id')) . '"';
$output .= ' data-vc-content=".vc_tta-panel-body">';
$output .= '<div class="vc_tta-panel-heading">';
//$output .= $this->getTemplateVariable( 'heading' );
$icon = $atts['i_icon_fontawesome'];
$subheading = $atts['subheading'];

//custom functionality - Add SVG image
$svg_html = '';
if ($atts['use_svg_icon'] == 'yes' && !empty($atts['svg_icon_id'])) {
	$svg_html = nc_tta_get_svg_icon_html($atts['svg_icon_id']);
}

$output .= '<h4 class="custom-accordion-heading vc_tta-panel-title vc_tta-controls-icon-position-right ' . esc_attr($atts['el_class']) . '" role="tab" id="custom-heading-' . esc_attr($atts['tab_id']) . '">';
$output .= '<button class="tab_link_button" id="tab_link_' . $tab_id . '" href="#' . esc_attr($atts['tab_id']) . '" aria-expanded="" data-vc-accordion="" data-vc-container=".vc_tta-container" aria-controls="' . $tab_id . '-body">';

if ($atts['use_svg_icon'] == 'yes' && $svg_html) {
	$output .= $svg_html;
} elseif ($icon) {
	$output .= '<i class="vc_tta-icon fas ' . $icon . '"></i>';
}

$output .= '<div class="vc_tta-title-text">' . esc_html($atts['title']);
if ($subheading) {
	$output .= '<span class="subheading">' . $subheading . '</span>';
}
$output .= '</div>';
$output .= '<i class="vc_tta-controls-icon vc_tta-controls-icon-chevron"></i>';
$output .= '</button>';
$output .= '</h4>';

$output .= '</div>';
$output .= '<div class="vc_tta-panel-body" id="' . $tab_id . '-body" role="region" aria-labelledby="tab_link_' . $tab_id . '">';
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
