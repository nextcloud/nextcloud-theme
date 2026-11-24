<?php
/**
 * WPBakery Page Builder customisations: extra element params and output filters.
 *
 * Loaded from functions.php. Template overrides stay in vc_templates/ (WPBakery looks them up there)
 * and the backend JS stays in vc_extend/.
 */

// Nothing here makes sense without WPBakery, and vc_add_param() would be undefined.
if (!defined('WPB_VC_VERSION')) {
	return;
}


//add custom checkbox in the button elements in WP Bakery (Old Button vc_btn and the 9.1+ Button wpb_btn)
function add_aria_popup_param_to_button() {
	foreach (['vc_btn', 'wpb_btn'] as $target) {
		vc_add_param($target, [
			'type' => 'checkbox',
			'heading' => __('Add aria-popup', 'nextcloud'),
			'param_name' => 'aria_popup',
			'description' => __("Check to add aria-popup='true' to the button", 'nextcloud'),
			'value' => ['Yes' => 'true']
		]);
	}
}
add_action('vc_after_init', 'add_aria_popup_param_to_button');

//add custom link ID in the button elements in WP Bakery (Old Button vc_btn and the 9.1+ Button wpb_btn)
function add_link_id_param_to_button() {
	foreach (['vc_btn', 'wpb_btn'] as $target) {
		vc_add_param($target, [
			'type' => 'textfield',
			'heading' => __('Link ID', 'nextcloud'),
			'param_name' => 'link_id',
			'description' => __('Link id - opf the a tag', 'nextcloud'),
			'value' => ''
		]);
	}
}
add_action('vc_after_init', 'add_link_id_param_to_button');


//add custom checkbox in the toggle (FAQ) element in WP Bakery
function extend_vc_toggle_with_disable_option() {
	vc_add_param('vc_toggle', [
		'type' => 'checkbox',
		'heading' => __('Disable this toggle', 'nextcloud'),
		'param_name' => 'disable_toggle',
		'value' => [__('Yes', 'nextcloud') => 'yes'],
		'description' => __('Prevents this toggle from rendering on the frontend.', 'nextcloud'),
	]);
}
add_action('vc_after_init', 'extend_vc_toggle_with_disable_option');



//add custom checkbox in the toggle (FAQ) element in WP Bakery
function extend_vc_section_with_extra_div_option() {
	vc_add_param('vc_section', [
		'type' => 'textfield',
		'heading' => __('Custom CSS class', 'nextcloud'),
		'param_name' => 'custom_section_div_css',
		'value' => '',
		'description' => __('Custom section div CSS class, of the inner container', 'nextcloud'),
	]);

	vc_add_param('vc_section', [
		'type' => 'textfield',
		'heading' => __('Custom ID', 'nextcloud'),
		'param_name' => 'custom_section_div_id',
		'value' => '',
		'description' => __('Custom section div ID, of the inner container', 'nextcloud'),
	]);

	vc_add_param('vc_section', [
		'type' => 'textarea_raw_html',
		'heading' => __('Custom Code', 'nextcloud'),
		'param_name' => 'custom_html_code',
		'value' => '',
		'description' => __('', 'nextcloud'),
		'group' => 'Custom Settings'
	]);



}
add_action('vc_after_init', 'extend_vc_section_with_extra_div_option');



//extend WP Bakery accordion seciton
add_action('vc_before_init', 'extend_vc_accordion_section');
function extend_vc_accordion_section() {
	vc_add_param('vc_tta_section', [
		'type' => 'textfield',
		'heading' => __('Subheading', 'nextcloud'),
		'param_name' => 'subheading',
		'description' => __('Enter a subheading for this accordion section.'),
		'admin_label' => true,
		//'group' => 'Custom Settings',
	]);
}


// Add custom params to vc_icon
add_action('vc_after_init', function () {
	if (function_exists('vc_add_param')) {
		vc_add_param('vc_icon', [
			'type' => 'checkbox',
			'heading' => __('Use SVG instead of Font Icon', 'nextcloud'),
			'param_name' => 'use_svg',
			'value' => [__('Yes', 'nextcloud') => 'yes'],
			'description' => __('If checked, the SVG below will be rendered instead of the selected font icon.', 'nextcloud'),
			'weight' => 999, // push near end
		]);

		vc_add_param('vc_icon', [
			'type' => 'attach_image', // works for SVG if uploads are allowed
			'heading' => __('SVG file', 'nextcloud'),
			'param_name' => 'svg_id',
			'description' => __('Pick an SVG from the Media Library.', 'nextcloud'),
			'dependency' => ['element' => 'use_svg', 'value' => 'yes'],
			'weight' => 998,
		]);
	}
});



/**
 * Replace vc_icon output with inline SVG when requested.
 */
add_filter('vc_shortcode_output', function ($output, $obj, $atts) {
	if (!is_object($obj) || $obj->settings('base') !== 'vc_icon') {
		return $output;
	}

	$atts = shortcode_atts([
		'use_svg' => '',
		'svg_id' => '',
		'color' => '',   // WPBakery vc_icon color param (may be named 'color' or 'custom_color')
		'custom_color' => '',
		'size' => '',   // e.g. 'sm', 'md', 'lg' or pixel value depending on your setup
		'align' => '',   // 'left','center','right'
		'el_class' => '',
	], $atts, 'vc_icon');

	if ($atts['use_svg'] !== 'yes' || empty($atts['svg_id'])) {
		return $output;
	}

	$file_path = get_attached_file((int)$atts['svg_id']);
	if (!$file_path || !file_exists($file_path)) {
		return $output; // fall back gracefully
	}

	// Read SVG contents
	$svg = file_get_contents($file_path);

	// (Recommended) Sanitize the SVG if Safe SVG (or a similar sanitizer) is present
	if (class_exists('\enshrined\svgSanitize\Sanitizer')) {
		$sanitizer = new \enshrined\svgSanitize\Sanitizer();
		$svg = $sanitizer->sanitize($svg);
	} else {
		// Minimal fallback: strip script tags
		$svg = preg_replace('#<script.*?</script>#is', '', $svg);
	}

	// Build wrapper classes (match vc_icon’s wrapper style so spacing/alignment still work)
	$classes = ['vc_icon_element', 'vc_icon_element-svg'];
	if (!empty($atts['el_class'])) {
		$classes[] = $atts['el_class'];
	}
	if (!empty($atts['align'])) {
		$classes[] = 'vc_icon_element-align-' . sanitize_html_class($atts['align']);
	}

	// Style: try to pass size & color to the SVG. Many SVGs rely on fill; we attempt a non-destructive tweak.
	$inline_styles = [];
	// WPBakery’s 'custom_color' usually wins
	$color = $atts['custom_color'] ?: $atts['color'];
	if ($color && strpos($svg, 'currentColor') === false) {
		// If the SVG doesn’t use currentColor, try adding a fill on the root <svg>
		$svg = preg_replace('/<svg\b(?![^>]*\bfill=)/i', '<svg fill="' . esc_attr($color) . '"', $svg, 1);
		$inline_styles[] = 'color:' . esc_attr($color);
	} elseif ($color) {
		$inline_styles[] = 'color:' . esc_attr($color);
	}

	// Handle WPBakery size tokens or raw px
	// If it's a number, treat it as px; if token (sm/md/lg), map as you like:
	$size_css = '';
	if ($atts['size']) {
		if (is_numeric($atts['size'])) {
			$size_css = intval($atts['size']) . 'px';
		} else {
			// map tokens to px — adjust to taste
			$map = ['xs' => '16px','sm' => '20px','md' => '24px','lg' => '32px','xl' => '48px'];
			$size_css = isset($map[$atts['size']]) ? $map[$atts['size']] : '';
		}
		if ($size_css) {
			// Width/height on wrapper lets inline SVG scale uniformly if it has viewBox
			$inline_styles[] = 'width:' . $size_css;
			$inline_styles[] = 'height:' . $size_css;
		}
	}

	$style_attr = $inline_styles ? ' style="' . esc_attr(implode(';', $inline_styles)) . '"' : '';

	// Ensure SVG is responsive (if it has no width/height but does have viewBox, browsers scale fine)
	// Wrap similar to vc_icon structure so margins, alignment work
	$new_output = '<div class="' . esc_attr(implode(' ', $classes)) . '"' . $style_attr . '>';
	$new_output .= '  <div class="vc_icon_element-inner vc_icon_element-color-' . esc_attr($color ? 'custom' : 'default') . ' vc_icon_element-size-' . esc_attr($atts['size'] ? $atts['size'] : 'md') . '">';
	$new_output .= '    <span class="vc_icon_element-icon vc_icon_element-icon--svg" aria-hidden="true">';
	$new_output .= $svg;
	$new_output .= '    </span>';
	$new_output .= '  </div>';
	$new_output .= '</div>';

	return $new_output;
}, 10, 3);



// === Add SVG icon params to WPBakery Button (vc_btn / vc_button) ===
add_action('vc_after_init', function () {

	if (!function_exists('vc_add_param')) {
		return;
	}

	$targets = ['vc_btn', 'vc_button', 'wpb_btn'];

	foreach ($targets as $target) {

		vc_add_param($target, [
			'type' => 'checkbox',
			'heading' => __('Use SVG icon', 'nextcloud'),
			'param_name' => 'use_svg_icon',
			'value' => [
				__('Yes', 'nextcloud') => 'yes',
			],
			'description' => __('Render an SVG from the Media Library instead of the built-in icon.', 'nextcloud'),
			'group' => __('Icon', 'js_composer'),
			//'weight'      => 999,
		]);

		vc_add_param($target, [
			'type' => 'attach_image',
			'heading' => __('SVG file', 'nextcloud'),
			'param_name' => 'svg_icon_id',
			'description' => __('Pick an SVG from the Media Library.', 'nextcloud'),
			'dependency' => [
				'element' => 'use_svg_icon',
				'value' => 'yes',
			],
			'group' => __('Icon', 'js_composer'),
			//'weight'      => 998,
		]);
	}
});


/**
 * Inject inline SVG into vc_btn / vc_button output when enabled.
 */
add_filter('vc_shortcode_output', function ($output, $obj, $atts) {
	if (!is_object($obj)) {
		return $output;
	}

	$base = $obj->settings('base');
	if ($base !== 'vc_btn' && $base !== 'vc_button') {
		return $output;
	}

	// Collect attributes
	$atts = shortcode_atts([
		'use_svg_icon' => '',
		'svg_icon_id' => '',
		'el_class' => '',
	], $atts, $base);

	if ($atts['use_svg_icon'] !== 'yes' || empty($atts['svg_icon_id'])) {
		return $output;
	}

	$file_path = get_attached_file((int)$atts['svg_icon_id']);
	if (!$file_path || !file_exists($file_path)) {
		return $output;
	}

	// Read SVG file
	$svg = file_get_contents($file_path);

	// Sanitize (Safe SVG plugin compatible)
	if (class_exists('\enshrined\svgSanitize\Sanitizer')) {
		$sanitizer = new \enshrined\svgSanitize\Sanitizer();
		$sanitizer->minify(false);
		$svg = $sanitizer->sanitize($svg);
	} else {
		// Minimal fallback: remove <script>
		$svg = preg_replace('#<script.*?</script>#is', '', $svg);
	}

	if (!$svg) {
		return $output;
	}

	// Ensure it inherits text color
	if (!preg_match('/<svg\b[^>]*\bfill=/i', $svg)) {
		$svg = preg_replace('/<svg\b/i', '<svg fill="currentColor"', $svg, 1);
	}

	// Build SVG wrapper
	$svg_html = '<span class="vc_btn3-icon vc_btn3-icon--svg" aria-hidden="true">';
	$svg_html .= $svg;
	$svg_html .= '</span>';

	// Remove any default <i> icon
	$output = preg_replace('/<i[^>]*\bvc_btn3-icon\b[^>]*>\s*<\/i>/i', '', $output);

	// Insert SVG after opening tag (<a> or <button>)
	$output = preg_replace('/(<(?:a|button)\b[^>]*\bvc_btn3\b[^>]*>)/i', '$1' . $svg_html, $output, 1);

	// Add marker class to button for optional styling
	$output = preg_replace_callback('/<(a|button)\b([^>]*)\bclass="([^"]*)"/i', function ($m) {
		$tag = $m[1];
		$attrs = $m[2];
		$classes = $m[3] . ' vc_btn3--has-svg';
		return "<{$tag}{$attrs} class=\"" . esc_attr($classes) . '"';
	}, $output, 1);

	return $output;
}, 10, 3);

/**
 * Same custom options for the WPBakery 9.1+ Button (wpb_btn): aria-popup, Link ID and the inline SVG icon.
 * The Old Button gets the first two from vc_templates/vc_btn.php; wpb_btn has no attribute filter, so we edit its output.
 */
add_filter('vc_shortcode_output', function ($output, $obj, $atts) {
	if (!is_object($obj) || $obj->settings('base') !== 'wpb_btn') {
		return $output;
	}

	$atts = shortcode_atts([
		'aria_popup' => '',
		'link_id' => '',
		'use_svg_icon' => '',
		'svg_icon_id' => '',
		'i_align' => 'left',
	], $atts, 'wpb_btn');

	// Matches the opening <a> / <button> tag of the button itself.
	$open_tag = '/<(a|button)\b([^>]*\bclass="[^"]*\bwpb_btn\b[^"]*"[^>]*)>/i';

	$extra_attrs = '';
	if ($atts['aria_popup'] === 'true') {
		$extra_attrs .= ' aria-haspopup="true"';
	}
	if ($atts['link_id'] !== '') {
		$extra_attrs .= ' id="' . esc_attr($atts['link_id']) . '"';
	}
	if ($extra_attrs !== '') {
		$output = preg_replace_callback($open_tag, function ($m) use ($extra_attrs) {
			return '<' . $m[1] . $m[2] . $extra_attrs . '>';
		}, $output, 1);
	}

	if ($atts['use_svg_icon'] !== 'yes' || empty($atts['svg_icon_id'])) {
		return $output;
	}

	$file_path = get_attached_file((int)$atts['svg_icon_id']);
	if (!$file_path || !file_exists($file_path)) {
		return $output;
	}

	$svg = file_get_contents($file_path);

	// Sanitize (Safe SVG plugin compatible)
	if (class_exists('\enshrined\svgSanitize\Sanitizer')) {
		$sanitizer = new \enshrined\svgSanitize\Sanitizer();
		$sanitizer->minify(false);
		$svg = $sanitizer->sanitize($svg);
	} else {
		// Minimal fallback: remove <script>
		$svg = preg_replace('#<script.*?</script>#is', '', $svg);
	}

	if (!$svg) {
		return $output;
	}

	// Ensure it inherits text color
	if (!preg_match('/<svg\b[^>]*\bfill=/i', $svg)) {
		$svg = preg_replace('/<svg\b/i', '<svg fill="currentColor"', $svg, 1);
	}

	// vc_btn3-icon--svg is kept so the theme's existing icon colour rules apply to both buttons.
	$svg_html = '<span class="wpb_btn-icon wpb_btn-icon--svg vc_btn3-icon--svg" aria-hidden="true">' . $svg . '</span>';

	// Remove the built-in font icon (pixelicons wrap a <span> inside the <i>).
	$output = preg_replace('/<i[^>]*\bwpb_btn-icon\b[^>]*>.*?<\/i>/is', '', $output);

	// The button is a flex row, so the icon side follows the DOM order.
	if ($atts['i_align'] === 'right') {
		// Callback, not a replacement string: SVG markup could contain "$1" or backslashes.
		$output = preg_replace_callback('/<\/(?:a|button)>(?![\s\S]*<\/(?:a|button)>)/i', function ($m) use ($svg_html) {
			return $svg_html . $m[0];
		}, $output, 1);
	} else {
		$output = preg_replace_callback($open_tag, function ($m) use ($svg_html) {
			return $m[0] . $svg_html;
		}, $output, 1);
	}

	// Marker class for optional styling
	$output = preg_replace_callback($open_tag, function ($m) {
		$attrs = preg_replace('/\bclass="([^"]*)"/i', 'class="$1 wpb_btn--has-svg"', $m[2], 1);
		return '<' . $m[1] . $attrs . '>';
	}, $output, 1);

	return $output;
}, 10, 3);

// === Add SVG icon params to WPBakery TTA Section (accordion tab) ===
add_action('vc_after_init', function () {
	if (!function_exists('vc_add_param')) {
		return;
	}

	vc_add_param('vc_tta_section', [
		'type' => 'checkbox',
		'heading' => __('Use SVG icon', 'nextcloud'),
		'param_name' => 'use_svg_icon',
		'value' => [__('Yes', 'nextcloud') => 'yes'],
		'description' => __('Render an SVG from the Media Library in the section header instead of the built-in icon.', 'nextcloud'),
		'weight' => 999,
	]);

	vc_add_param('vc_tta_section', [
		'type' => 'attach_image',
		'heading' => __('SVG file', 'nextcloud'),
		'param_name' => 'svg_icon_id',
		'description' => __('Pick an SVG from the Media Library.', 'nextcloud'),
		'dependency' => ['element' => 'use_svg_icon', 'value' => 'yes'],
		'weight' => 998,
	]);
});


/**
 * Render the sanitized inline SVG markup for a TTA (Tabs/Accordion) section icon.
 * Shared by vc_templates/vc_tta_section.php (mobile/accordion heading) and the
 * desktop tabs-nav filter below, so both headings render the same icon.
 */
function nc_tta_get_svg_icon_html($attachment_id) {
	$file_path = get_attached_file((int)$attachment_id);
	if (!$file_path || !file_exists($file_path)) {
		return '';
	}

	$svg = file_get_contents($file_path);
	// Sanitize (Safe SVG compatible)
	$svg = preg_replace('#<script.*?</script>#is', '', $svg);

	// Normalize fills so it inherits header color (fixes Illustrator .st0 fills)
	$svg = preg_replace('#<style\b[^>]*>.*?</style>#is', '', $svg);
	$svg = preg_replace('/\bfill\s*:\s*#[0-9a-fA-F]{3,8}\b/', 'fill:currentColor', $svg);
	$svg = preg_replace('/\bfill\s*=\s*"#[0-9a-fA-F]{3,8}"/', 'fill="currentColor"', $svg);
	if (!preg_match('/<svg\b[^>]*\bfill=/i', $svg)) {
		$svg = preg_replace('/<svg\b/i', '<svg fill="currentColor"', $svg, 1);
	}

	return '<span class="vc_tta-icon vc_tta-icon--svg" aria-hidden="true">' . $svg . '</span>';
}

/**
 * WPBakery builds the desktop "Tabs" nav (ul.vc_tta-tabs-list) from
 * WPBakeryShortCode_Vc_Tta_Tabs::getParamTabsList(), which is separate from
 * the vc_tta_section.php template - that template's heading only becomes
 * visible once the tabs collapse into an accordion on mobile. Because of
 * that, our svg_icon_id/use_svg_icon fields (read only in the section
 * template) never reached the desktop nav. Rebuild that nav here so both
 * views use the same icon.
 */
add_filter('vc-tta-get-params-tabs-list', function ($html, $atts, $content, $tabs_obj) {
	if (vc_is_page_editable() || empty(WPBakeryShortCode_Vc_Tta_Section::$section_info)) {
		return $html;
	}

	$active_section = method_exists($tabs_obj, 'getActiveSection') ? $tabs_obj->getActiveSection($atts, false) : null;
	$active_class = isset($tabs_obj->activeClass) ? $tabs_obj->activeClass : 'vc_active';

	$allowed_tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p'];
	$heading_tag = strtolower(trim((string)($atts['section_title_tag'] ?? '')));
	if (!in_array($heading_tag, $allowed_tags, true)) {
		$heading_tag = 'h4';
	}

	$html = ['<div class="vc_tta-tabs-container">', '<ul class="vc_tta-tabs-list" role="tablist">'];

	foreach (WPBakeryShortCode_Vc_Tta_Section::$section_info as $nth => $section) {
		$classes = ['vc_tta-tab'];
		if (($nth + 1) === $active_section) {
			$classes[] = $active_class;
		}

		$title = '<span class="vc_tta-title-text">' . wp_kses_post((string)$section['title']) . '</span>';

		$icon_html = '';
		if (!empty($section['use_svg_icon']) && $section['use_svg_icon'] === 'yes' && !empty($section['svg_icon_id'])) {
			$icon_html = nc_tta_get_svg_icon_html($section['svg_icon_id']);
		} elseif (!empty($section['add_icon']) && $section['add_icon'] === 'true' && method_exists($tabs_obj, 'constructIcon')) {
			$icon_html = $tabs_obj->constructIcon($section);
		}

		if ($icon_html) {
			$title = (!empty($section['i_position']) && $section['i_position'] === 'left') ? $icon_html . $title : $title . $icon_html;
		}

		$a_html = '<a href="#' . esc_attr($section['tab_id']) . '" data-vc-tabs data-vc-container=".vc_tta" role="tab" aria-selected="false" id="' . esc_attr('tab-' . $section['tab_id']) . '">' . $title . '</a>';
		$a_html = '<' . $heading_tag . ' class="vc_tta-panel-title">' . $a_html . '</' . $heading_tag . '>';
		$html[] = '<li class="' . implode(' ', $classes) . '" data-vc-tab role="presentation">' . $a_html . '</li>';
	}

	$html[] = '</ul>';
	$html[] = '</div>';

	return $html;
}, 10, 4);


// === Add SVG icon params to WPBakery Message Box ===
add_action('vc_after_init', function () {
	if (!function_exists('vc_add_param')) {
		return;
	}

	// Support common bases (varies by VC version/theme)
	$targets = ['vc_message', 'vc_message_box'];

	foreach ($targets as $target) {
		vc_add_param($target, [
			'type' => 'checkbox',
			'heading' => __('Use SVG icon', 'nextcloud'),
			'param_name' => 'use_svg_icon',
			'value' => [__('Yes', 'nextcloud') => 'yes'],
			'description' => __('Render an SVG from the Media Library instead of the built-in icon.', 'nextcloud'),
			'weight' => 999,
		]);

		vc_add_param($target, [
			'type' => 'attach_image',
			'heading' => __('SVG file', 'nextcloud'),
			'param_name' => 'svg_icon_id',
			'description' => __('Pick an SVG from the Media Library.', 'nextcloud'),
			'dependency' => ['element' => 'use_svg_icon', 'value' => 'yes'],
			'weight' => 998,
		]);
	}
});


/**
 * Inject inline SVG into vc_message / vc_message_box when enabled.
 */
add_filter('vc_shortcode_output', function ($output, $obj, $atts) {
	if (!is_object($obj)) {
		return $output;
	}

	$base = $obj->settings('base');
	if ($base !== 'vc_message' && $base !== 'vc_message_box') {
		return $output;
	}

	// Gather attributes
	$atts = shortcode_atts([
		'use_svg_icon' => '',
		'svg_icon_id' => '',
		'el_class' => '',
	], $atts, $base);

	if ($atts['use_svg_icon'] !== 'yes' || empty($atts['svg_icon_id'])) {
		return $output;
	}

	$file_path = get_attached_file((int)$atts['svg_icon_id']);
	if (!$file_path || !file_exists($file_path)) {
		return $output; // graceful fallback
	}

	// Read SVG
	$svg = file_get_contents($file_path);

	// Sanitize (Safe SVG compatible)
	if (class_exists('\enshrined\svgSanitize\Sanitizer')) {
		$sanitizer = new \enshrined\svgSanitize\Sanitizer();
		$sanitizer->minify(false);
		$svg = $sanitizer->sanitize($svg);
	} else {
		// Minimal fallback
		$svg = preg_replace('#<script.*?</script>#is', '', $svg);
	}
	if (!$svg) {
		return $output;
	}

	// Normalize fills so it inherits message color (fixes Illustrator .st0 fills)
	$svg = preg_replace('#<style\b[^>]*>.*?</style>#is', '', $svg);                      // drop embedded <style>
	$svg = preg_replace('/\bfill\s*:\s*#[0-9a-fA-F]{3,8}\b/', 'fill:currentColor', $svg);   // style="...fill:#fff"
	$svg = preg_replace('/\bfill\s*=\s*"#[0-9a-fA-F]{3,8}"/', 'fill="currentColor"', $svg); // fill="#fff"
	if (!preg_match('/<svg\b[^>]*\bfill=/i', $svg)) {
		$svg = preg_replace('/<svg\b/i', '<svg fill="currentColor"', $svg, 1);
	}

	// Our icon wrapper
	$svg_html = '<div class="vc_message_box-icon vc_message_box-icon--svg" aria-hidden="true">';
	$svg_html .= $svg;
	$svg_html .= '</div>';

	// Remove WPBakery's default icon container (varies slightly by theme/VC version)
	$output = preg_replace('/<div[^>]*\bvc_message_box-icon\b[^>]*>.*?<\/div>/is', '', $output);
	$output = preg_replace('/<i[^>]*\bvc_message\-icon\b[^>]*>\s*<\/i>/i', '', $output);

	// Insert our SVG icon right after the opening wrapper with class "vc_message_box"
	$output = preg_replace(
		'/(<div\b[^>]*\bvc_message_box\b[^>]*>)/i',
		'$1' . $svg_html,
		$output,
		1
	);

	// Add marker class to wrapper (optional, handy for CSS)
	$output = preg_replace_callback('/<div\b([^>]*)\bclass="([^"]*\bvc_message_box\b[^"]*)"/i', function ($m) {
		$attrs = $m[1];
		$classes = $m[2] . ' vc_message_box--has-svg';
		return '<div' . $attrs . ' class="' . esc_attr($classes) . '"';
	}, $output, 1);

	return $output;
}, 10, 3);


// Add inline CSS field to Single Image element
add_action('vc_after_init', 'nextcloud_add_inline_css_to_single_image');
function nextcloud_add_inline_css_to_single_image() {
	if (! function_exists('vc_add_param')) {
		return;
	}

	vc_add_param('vc_single_image', [
		'type' => 'textarea',
		'heading' => __('Inline image CSS', 'nextcloud'),
		'param_name' => 'img_inline_css',
		'group' => __('Custom', 'nextcloud'), // this creates a "Custom" tab
		'description' => __('CSS rules applied directly to the <img> tag. Example: border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,.2);', 'nextcloud'),
	]);
}
