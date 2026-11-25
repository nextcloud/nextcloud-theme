<?php

/**
 * Search profiles - replaces the "WP Extended Search" plugin.
 *
 * Each profile defines which post types and fields a search covers. A form selects
 * a profile with the hidden `search_profile` field. Old links using the plugin's
 * `wpessid` (and the theme's `custom_type`) parameter are mapped to profiles.
 */

defined('ABSPATH') || exit();

function nc_search_profiles() {
	return [
		// Used when a search has no profile (header search, Gutenberg search block, ...).
		// Mirrors the plugin's "Default" setting.
		'default' => [
			'post_types' => ['post', 'page', 'event'],
			'fields' => ['title', 'content', 'excerpt'],
		],
		'site' => [
			'legacy_id' => 13061,
			'post_types' => [
				'post', 'page', 'attachment', 'case_studies', 'whitepapers', 'data_sheets', 'press_releases',
				'event', 'recording', 'on-demand-webinar', 'podcast',
			],
			'fields' => ['title', 'content', 'excerpt'],
		],
		'blog' => [
			'legacy_id' => 1612,
			'post_types' => ['post', 'podcast'],
			'fields' => ['title', 'content', 'excerpt'],
			'load_more_post_type' => 'post',
		],
		'events' => [
			'legacy_id' => 125618,
			'post_types' => ['event'],
			'fields' => ['title', 'excerpt'],
			'load_more_post_type' => 'event',
		],
		'webinar_recordings' => [
			'legacy_id' => 200771,
			'post_types' => ['on-demand-webinar'],
			'fields' => ['title', 'excerpt'],
			'meta_keys' => ['download_available'],
			// Only webinars that have a recording.
			'meta_query' => [
				[
					'key' => 'download_available',
					'value' => '',
					'compare' => '!=',
				],
			],
			'load_more_post_type' => 'on-demand-webinar',
		],
		'case_studies' => [
			'legacy_id' => 202266,
			'post_types' => ['case_studies'],
			'fields' => ['title', 'excerpt'],
			'placeholder' => 'Search for case studies..',
		],
		'whitepapers' => [
			'legacy_id' => 202268,
			'post_types' => ['whitepapers'],
			'fields' => ['title', 'excerpt'],
			'placeholder' => 'Search for whitepapers..',
		],
		'data_sheets' => [
			'legacy_id' => 202281,
			'post_types' => ['data_sheets'],
			'fields' => ['title', 'excerpt'],
			'placeholder' => 'Search for data sheets..',
		],
	];
}

/**
 * Resolve a profile key from a profile key, a legacy plugin ID or a legacy `custom_type` value.
 */
function nc_search_profile_key($value) {
	$profiles = nc_search_profiles();
	$value = is_scalar($value) ? sanitize_key((string)$value) : '';

	if ($value === '') {
		return '';
	}
	if (isset($profiles[$value])) {
		return $value;
	}
	foreach ($profiles as $key => $profile) {
		if (isset($profile['legacy_id']) && (string)$profile['legacy_id'] === $value) {
			return $key;
		}
	}

	return '';
}

/**
 * Profile key requested by the current request ('' if none).
 */
function nc_search_requested_profile_key() {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended
	foreach (['search_profile', 'wpessid', 'custom_type'] as $param) {
		if (! empty($_REQUEST[$param])) {
			$key = nc_search_profile_key(wp_unslash($_REQUEST[$param]));
			if ($key !== '') {
				return $key;
			}
		}
	}
	// phpcs:enable

	return '';
}

/**
 * Profile applied to a query: explicit `search_profile` query var, then the request, then default.
 */
function nc_search_profile_for_query(WP_Query $query) {
	$profiles = nc_search_profiles();
	$key = nc_search_profile_key($query->get('search_profile'));

	if ($key === '') {
		$key = nc_search_requested_profile_key();
	}
	if ($key === '') {
		$key = 'default';
	}

	return $profiles[$key] + ['key' => $key];
}

/**
 * Whether a query should be handled by the search profiles.
 */
function nc_search_profiles_applies(WP_Query $query) {
	if (! $query->is_search() || $query->get('s') === '' || $query->get('suppress_filters') || $query->get('disable_search_profile')) {
		return false;
	}
	if (function_exists('is_bbpress') && is_bbpress()) {
		return false;
	}
	// Back-end screens use the core search. Front-end AJAX (load more) is handled.
	if (is_admin() && ! (wp_doing_ajax() && nc_search_is_frontend_ajax())) {
		return false;
	}
	// Only alter REST searches that explicitly ask for a profile.
	if (defined('REST_REQUEST') && REST_REQUEST) {
		return nc_search_requested_profile_key() !== '' || (bool)$query->get('search_profile');
	}

	return true;
}

function nc_search_is_frontend_ajax() {
	$referer = wp_get_raw_referer();

	return ! $referer || stripos($referer, admin_url()) !== 0;
}

/**
 * Set post types, status and meta query from the profile.
 */
function nc_search_profiles_pre_get_posts(WP_Query $query) {
	if (! nc_search_profiles_applies($query)) {
		return;
	}

	$profile = nc_search_profile_for_query($query);
	$post_types = $profile['post_types'];

	// `?post_type=x` narrows the search to one of the profile's post types.
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$requested_type = isset($_GET['post_type']) && is_string($_GET['post_type']) ? sanitize_key($_GET['post_type']) : '';
	if ($requested_type !== '' && in_array($requested_type, $post_types, true)) {
		$post_types = [$requested_type];
	}
	$query->set('post_type', $post_types);

	if (in_array('attachment', $post_types, true)) {
		if (is_user_logged_in()) {
			$query->set('post_status', ['publish', 'inherit', 'private']);
			$query->set('perm', 'readable');
		} else {
			$query->set('post_status', ['publish', 'inherit']);
		}
	}

	if (! empty($profile['meta_query'])) {
		$meta_query = is_array($query->get('meta_query')) ? $query->get('meta_query') : [];
		$meta_query[] = $profile['meta_query'];
		$query->set('meta_query', $meta_query);
	}
}
// Runs after WP Extended Search (priority 500) so the profiles win while the plugin is still active.
add_action('pre_get_posts', 'nc_search_profiles_pre_get_posts', 600);

/**
 * Replace the core search SQL with a LIKE search over the profile's fields.
 * Every field is OR-ed, every search term is AND-ed (same as the plugin).
 */
function nc_search_profiles_posts_search($search, WP_Query $query) {
	if (! nc_search_profiles_applies($query)) {
		return $search;
	}

	global $wpdb;

	$profile = nc_search_profile_for_query($query);
	$fields = $profile['fields'];
	$meta_keys = $profile['meta_keys'] ?? [];
	$columns = [
		'title' => "{$wpdb->posts}.post_title",
		'content' => "{$wpdb->posts}.post_content",
		'excerpt' => "{$wpdb->posts}.post_excerpt",
	];

	$clauses = [];
	foreach ((array)$query->get('search_terms') as $term) {
		$like = '%' . $wpdb->esc_like($term) . '%';
		$or = [];

		foreach ($fields as $field) {
			$or[] = $wpdb->prepare("({$columns[$field]} LIKE %s)", $like);
		}
		foreach ($meta_keys as $meta_key) {
			$or[] = $wpdb->prepare(
				"EXISTS (SELECT 1 FROM {$wpdb->postmeta} ncpm WHERE ncpm.post_id = {$wpdb->posts}.ID AND ncpm.meta_key = %s AND ncpm.meta_value LIKE %s)",
				$meta_key,
				$like
			);
		}

		if ($or) {
			$clauses[] = '(' . implode(' OR ', $or) . ')';
		}
	}

	if (! $clauses) {
		return $search;
	}

	$search = ' AND (' . implode(' AND ', $clauses) . ') ';
	if (! is_user_logged_in()) {
		$search .= " AND ({$wpdb->posts}.post_password = '') ";
	}

	return $search;
}
add_filter('posts_search', 'nc_search_profiles_posts_search', 600, 2);

/**
 * Hidden field that sends the profile with a search form.
 */
function nc_search_profile_field($profile_key) {
	$profile_key = nc_search_profile_key($profile_key);

	if ($profile_key === '' || $profile_key === 'default') {
		return '';
	}

	return '<input type="hidden" value="' . esc_attr($profile_key) . '" name="search_profile" />';
}

/**
 * Search form for a profile (same markup the plugin rendered).
 */
function nc_search_form($profile_key, $echo = true) {
	$profile_key = nc_search_profile_key($profile_key);
	$id_attr = $profile_key !== '' ? ' id="nc-search-form-' . esc_attr($profile_key) . '"' : '';

	$form = '<form' . $id_attr . ' role="search" method="get" class="search-form" action="' . esc_url(home_url('/')) . '">
		<label>
			<span class="screen-reader-text">' . _x('Search for:', 'label') . '</span>
			<input type="search" class="search-field" placeholder="' . esc_attr_x('Search &hellip;', 'placeholder') . '" value="' . get_search_query() . '" name="s" />
		</label>
		<input type="submit" class="search-submit" value="' . esc_attr_x('Search', 'submit button') . '" />'
		. nc_search_profile_field($profile_key)
		. '</form>';

	if ($echo) {
		echo $form;
	}

	return $form;
}

/**
 * [wpes_search_form wpessid="1612"] - the plugin's shortcode, kept so existing content keeps working.
 * `wpessid` accepts a legacy plugin ID or a profile key, e.g. [wpes_search_form wpessid="blog"].
 */
function nc_wpes_search_form_shortcode($atts) {
	$atts = shortcode_atts([
		'wpessid' => '',
		'submit_button_label' => _x('Search', 'submit button'),
		'input_box_placeholder' => _x('Search &hellip;', 'placeholder'),
		'search_form_css_class' => '',
		'search_button_css_class' => '',
		'search_input_css_class' => '',
		'aria_label' => '',
	], $atts, 'wpes_search_form');

	return nc_search_form($atts['wpessid'], false, $atts);
}
// Registered on `init` after the plugin (priority 10), so this one is used while the plugin is still active.
add_action('init', function () {
	add_shortcode('wpes_search_form', 'nc_wpes_search_form_shortcode');
}, 20);
