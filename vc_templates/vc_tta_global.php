<?php

/**
 * The template for displaying [vc_tt_global] shortcode output.
 *
 * This template can be overridden by copying it to yourtheme/vc_templates/vc_tta_global.php.
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
 * @var $el_class
 * @var $el_id
 * @var WPBakeryShortCode_Vc_Tta_Accordion|WPBakeryShortCode_Vc_Tta_Tabs|WPBakeryShortCode_Vc_Tta_Tour|WPBakeryShortCode_Vc_Tta_Pageable $this
 */
$el_class = $css = $css_animation = '';
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
$this->resetVariables($atts, $content);
extract($atts);

$this->setGlobalTtaInfo();

$this->enqueueTtaStyles();
$this->enqueueTtaScript();

// It is required to be before tabs-list-top/left/bottom/right for tabs/tours.
$prepare_content = $this->getTemplateVariable('content');

$class_to_filter = $this->getTtaGeneralClasses();
$class_to_filter .= vc_shortcode_custom_css_class($css, ' ') . $this->getCSSAnimation($css_animation);
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts);

$output = '<div ' . $this->getWrapperAttributes() . '>';
$output .= $this->getTemplateVariable('title');
$output .= '<div class="' . esc_attr($css_class) . '">';
$output .= $this->getTemplateVariable('tabs-list-top');
$output .= $this->getTemplateVariable('tabs-list-left');
$output .= '<div class="vc_tta-panels-container">';
$output .= $this->getTemplateVariable('pagination-top');
$output .= '<div class="vc_tta-panels">';
$output .= $prepare_content;
$output .= '</div>';
$output .= $this->getTemplateVariable('pagination-bottom');
$output .= '</div>';
$output .= $this->getTemplateVariable('tabs-list-bottom');
$output .= $this->getTemplateVariable('tabs-list-right');
$output .= '</div>';
$output .= '</div>';

return $output;
