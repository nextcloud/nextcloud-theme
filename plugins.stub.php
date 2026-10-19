<?php

/**
 * Psalm stubs for third-party plugins the theme integrates with.
 * Only used for static analysis, never loaded by WordPress.
 */

// WPBakery Page Builder
function vc_add_param(string $shortcode, array $attributes): void {
}

function vc_is_page_editable(): bool {
}

class WPBakeryShortCode_Vc_Tta_Section {
	/** @var array<int, array<string, mixed>> */
	public static $section_info = [];
}

// WP Rocket
/**
 * @param mixed $default
 * @return mixed
 */
function get_rocket_option(string $option, $default = false) {
}
