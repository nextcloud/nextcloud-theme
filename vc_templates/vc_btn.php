<?php

/**
 * The template for displaying [vc_btn] shortcode output of 'Button' element.
 *
 * This template can be overridden by copying it to yourtheme/vc_templates/vc_btn.php.
 *
 * @see https://kb.wpbakery.com/docs/developers-how-tos/change-shortcodes-html-output
 *
 * @version 9.0
 *
 * @var array $atts
 * @var WPBakeryShortCode_Vc_Btn $this
 */

if (! defined('ABSPATH')) {
	die('-1');
}
$style = $shape = $size = $custom_background = $custom_text = $custom_border = $custom_hover_background = $custom_hover_text = $custom_hover_border = $align = $link = $title = $button_block = $el_class = $outline_custom_color = $outline_custom_hover_background = $outline_custom_hover_text = $add_icon = $i_align = $i_type = $i_icon_entypo = $i_icon_fontawesome = $i_icon_linecons = $i_icon_pixelicons = $i_icon_typicons = $css = $css_animation = $gradient_custom_color_1 = $gradient_custom_color_2 = $gradient_text_color = $custom_onclick = $custom_onclick_code = $a_href = $a_title = $a_target = $a_rel = '';
$styles = [];
$icon_wrapper = false;
$icon_html = false;
$attributes = [];

$atts = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);

/**
 * Extracted variables.
 *
 * @var string $style
 * @var string $shape
 * @var string $custom_background
 * @var string $custom_text
 * @var string $custom_border
 * @var string $custom_hover_background
 * @var string $custom_hover_text
 * @var string $custom_hover_border
 * @var string $size
 * @var string $align
 * @var string $link
 * @var string $title
 * @var string $button_block
 * @var string $el_id
 * @var string $el_class
 * @var string $outline_custom_color
 * @var string $outline_custom_hover_background
 * @var string $outline_custom_hover_text
 * @var string $add_icon
 * @var string $i_align
 * @var string $i_type
 * @var string $i_icon_fontawesome
 * @var string $i_icon_openiconic
 * @var string $i_icon_typicons
 * @var string $i_icon_entypo
 * @var string $i_icon_linecons
 * @var string $i_icon_pixelicons
 * @var string $css_animation
 * @var string $css
 * @var string $gradient_custom_color_1 ;
 * @var string $gradient_custom_color_2 ;
 * @var string $gradient_text_color ;
 * @var string $i_icon_ A dynamic icon variable (resolved via ${'i_icon_' . $i_type})
 * @var string $custom_onclick
 * @var string $custom_onclick_code
 */

// parse link.
$link = trim($link);
$link = ($link === '||') ? '' : $link;
$link = vc_build_link($link);
$use_link = false;
if (strlen($link['url']) > 0) {
	$use_link = true;
	$a_href = $link['url'];
	$a_href = apply_filters('vc_btn_a_href', $a_href);
	$a_title = $link['title'];
	$a_title = apply_filters('vc_btn_a_title', $a_title);
	$a_target = $link['target'];
	$a_rel = $link['rel'];
}

$wrapper_classes = [
	'vc_btn3-container',
	$this->getExtraClass($el_class),
	$this->getCSSAnimation($css_animation),
	'vc_btn3-' . $align,
];

$button_classes = [
	'vc_general',
	'vc_btn3',
	'vc_btn3-size-' . $size,
	'vc_btn3-shape-' . $shape,
	'vc_btn3-style-' . $style,
];

$button_html = wp_kses_post($title);

if (trim($title) === '') {
	$button_classes[] = 'vc_btn3-o-empty';
	$button_html = '<span class="vc_btn3-placeholder">&nbsp;</span>';
}
// inline B.C state prior 9.0.
if ($button_block === 'true' && $align !== 'inline') {
	$button_classes[] = 'vc_btn3-block';
}
if ($add_icon === 'true') {
	$button_classes[] = 'vc_btn3-icon-' . $i_align;
	vc_icon_element_fonts_enqueue($i_type);

	if (isset(${'i_icon_' . $i_type})) {
		if ($i_type === 'pixelicons') {
			$icon_wrapper = true;
		}
		$icon_class = ${'i_icon_' . $i_type};
	} else {
		$icon_class = 'fa fa-adjust';
	}

	if ($icon_wrapper) {
		$icon_html = '<i class="vc_btn3-icon"><span class="vc_btn3-icon-inner ' . esc_attr($icon_class) . '"></span></i>';
	} else {
		$icon_html = '<i class="vc_btn3-icon ' . esc_attr($icon_class) . '"></i>';
	}

	if ($i_align === 'left') {
		$button_html = $icon_html . ' ' . $button_html;
	} else {
		$button_html .= ' ' . $icon_html;
	}
}
$output = '';
if ($style === 'outline-custom') {
	if ($outline_custom_color) {
		$styles[] = vc_get_css_color('border-color', $outline_custom_color);
		$styles[] = vc_get_css_color('color', $outline_custom_color);
		$attributes[] = 'onmouseleave="this.style.borderColor=\'' . esc_attr($outline_custom_color) . '\'; this.style.backgroundColor=\'transparent\'; this.style.color=\'' . esc_attr($outline_custom_color) . '\'"';
	} else {
		$attributes[] = 'onmouseleave="this.style.borderColor=\'\'; this.style.backgroundColor=\'transparent\'; this.style.color=\'\'"';
	}

	$onmouseenter = [];
	if ($outline_custom_hover_background) {
		$onmouseenter[] = 'this.style.borderColor=\'' . esc_attr($outline_custom_hover_background) . '\';';
		$onmouseenter[] = 'this.style.backgroundColor=\'' . esc_attr($outline_custom_hover_background) . '\';';
	}
	if ($outline_custom_hover_text) {
		$onmouseenter[] = 'this.style.color=\'' . esc_attr($outline_custom_hover_text) . '\';';
	}
	if ($onmouseenter) {
		$attributes[] = 'onmouseenter="' . implode(' ', $onmouseenter) . '"';
	}

	if (! $outline_custom_color && ! $outline_custom_hover_background && ! $outline_custom_hover_text) {
		$button_classes[] = 'vc_btn3-color-inverse';

		foreach ($button_classes as $k => $v) {
			if ($v === 'vc_btn3-style-outline-custom') {
				unset($button_classes[ $k ]);
				break;
			}
		}
		$button_classes[] = 'vc_btn3-style-outline';
	}
} elseif ($style === '3d') {
	if ($custom_background) {
		$styles[] = vc_get_css_color('background-color', $custom_background);
	}
	if ($custom_text) {
		$styles[] = vc_get_css_color('color', $custom_text);
	}
	if ($custom_border) {
		$shadow_widths = [
			'xs' => 3,
			'sm' => 4,
			'md' => 5,
			'lg' => 5,
		];
		$shadow_width = $shadow_widths[ $size ] ?? 5;
		$shadow_width_hover = 2;
		$top_offset = $shadow_width - $shadow_width_hover;
		$styles[] = 'box-shadow: 0 ' . $shadow_width . 'px 0 ' . esc_attr($custom_border);
		$attributes[] = 'onmouseenter="this.style.top=\'' . $top_offset . 'px\'; this.style.boxShadow=\'0 ' . $shadow_width_hover . 'px 0 ' . esc_attr($custom_border) . '\';"';
		$attributes[] = 'onmouseleave="this.style.top=\'0\'; this.style.boxShadow=\'0 ' . $shadow_width . 'px 0 ' . esc_attr($custom_border) . '\';"';
	}
} elseif ($style === 'gradient-custom') {
	$button_text_color = $gradient_text_color;

	$gradient_css = [];
	$gradient_css[] = 'color: ' . $button_text_color;
	$gradient_css[] = 'border: none';
	$gradient_css[] = 'background-color: ' . $gradient_custom_color_1;
	$gradient_css[] = 'background-image: -webkit-linear-gradient(left, ' . $gradient_custom_color_1 . ' 0%, ' . $gradient_custom_color_2 . ' 50%,' . $gradient_custom_color_1 . ' 100%)';
	$gradient_css[] = 'background-image: linear-gradient(to right, ' . $gradient_custom_color_1 . ' 0%, ' . $gradient_custom_color_2 . ' 50%,' . $gradient_custom_color_1 . ' 100%)';
	$gradient_css[] = '-webkit-transition: all .2s ease-in-out';
	$gradient_css[] = 'transition: all .2s ease-in-out';
	$gradient_css[] = 'background-size: 200% 100%';

	// hover css.
	$gradient_css_hover = [];
	$gradient_css_hover[] = 'color: ' . $button_text_color;
	$gradient_css_hover[] = 'background-color: ' . $gradient_custom_color_2;
	$gradient_css_hover[] = 'border: none';
	$gradient_css_hover[] = 'background-position: 100% 0';

	$uid = uniqid();
	$first_tag = 'style';
	$output .= '<' . $first_tag . '>button.vc_btn3-style-' . esc_attr($style) . '.vc_btn-gradient-btn-' . esc_attr($uid) . ':hover{' . esc_attr(implode(';', $gradient_css_hover)) . ';}</' . $first_tag . '>';
	$output .= '<' . $first_tag . '>button.vc_btn3-style-' . esc_attr($style) . '.vc_btn-gradient-btn-' . esc_attr($uid) . '{' . esc_attr(implode(';', $gradient_css)) . ';}</' . $first_tag . '>';
	$button_classes[] = 'vc_btn-gradient-btn-' . $uid;
	$attributes[] = 'data-vc-gradient-1="' . esc_attr($gradient_custom_color_1) . '"';
	$attributes[] = 'data-vc-gradient-2="' . esc_attr($gradient_custom_color_2) . '"';
} else {
	if ($custom_background && $style !== 'outline') {
		$styles[] = vc_get_css_color('background-color', $custom_background);
	}

	if ($custom_text) {
		$styles[] = vc_get_css_color('color', $custom_text);
	}

	if ($custom_border) {
		$styles[] = vc_get_css_color('border-color', $custom_border);
	}

	$onmouseenter = [];
	$onmouseleave = [];

	if ($custom_hover_background) {
		$onmouseenter[] = 'this.style.backgroundColor=\'' . esc_attr($custom_hover_background) . '\';';
		$mouseleave_bg = ($style === 'outline') ? 'transparent' : esc_attr($custom_background);
		$onmouseleave[] = 'this.style.backgroundColor=\'' . $mouseleave_bg . '\';';
	}
	if ($custom_hover_text) {
		$onmouseenter[] = 'this.style.color=\'' . esc_attr($custom_hover_text) . '\';';
		$onmouseleave[] = 'this.style.color=\'' . esc_attr($custom_text) . '\';';
	}
	if ($custom_hover_border) {
		$onmouseenter[] = 'this.style.borderColor=\'' . esc_attr($custom_hover_border) . '\';';
		$onmouseleave[] = 'this.style.borderColor=\'' . esc_attr($custom_border) . '\';';
	}

	if ($onmouseenter) {
		$attributes[] = 'onmouseenter="' . implode(' ', $onmouseenter) . '"';
		$attributes[] = 'onmouseleave="' . implode(' ', $onmouseleave) . '"';
	}
}

if ($styles) {
	$attributes[] = 'style="' . esc_attr(implode(' ', $styles)) . '"';
}

$settings = $this->getSettings();
$element_class = empty($settings['element_default_class']) ? '' : $settings['element_default_class'];
$class_to_filter = implode(' ', array_filter($wrapper_classes));
$class_to_filter .= vc_shortcode_custom_css_class($css, ' ') . ' ' . $element_class;
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $settings['base'], $atts);

if ($button_classes) {
	$button_classes = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode(' ', array_filter($button_classes)), $settings['base'], $atts);
	$attributes[] = 'class="' . trim(esc_attr($button_classes)) . '"';
}

if ($use_link) {
	$attributes[] = 'href="' . esc_url(trim($a_href)) . '"';
	$attributes[] = 'title="' . esc_attr(trim($a_title)) . '"';
	if (! empty($a_target)) {
		$attributes[] = 'target="' . esc_attr(trim($a_target)) . '"';
	}
	if (! empty($a_rel)) {
		$attributes[] = 'rel="' . esc_attr(trim($a_rel)) . '"';
	}
}

if (! empty($custom_onclick) && $custom_onclick_code) {
	$attributes[] = 'onclick="' . esc_attr($custom_onclick_code) . '"';
}


//custom additions
//added custom functionality, to print the aria popup attribute
$aria_popup = isset($atts['aria_popup']) ? $atts['aria_popup'] : '';
if ($aria_popup === 'true') {
	$attributes[] = 'aria-haspopup="true"';
}

//added custom functionality, to print the aria popup attribute
$link_id = isset($atts['link_id']) ? $atts['link_id'] : '';
if ($link_id != '') {
	$attributes[] = 'id="' . $link_id . '"';
}


$attributes = implode(' ', $attributes);

$output .= '<div class="' . esc_attr(trim($css_class)) . '"' . (! empty($el_id) ? ' id="' . esc_attr($el_id) . '"' : '') . ' >';

if ($use_link) {
	$output .= '<a ' . $attributes . '>' . $button_html . '</a>';
} else {
	$output .= '<button ' . $attributes . '>' . $button_html . '</button>';
}

$output .= '</div>';

return $output;
