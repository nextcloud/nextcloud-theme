<?php

add_filter('manage_partner_posts_posts_columns', 'set_custom_edit_partner_posts_columns');
function set_custom_edit_partner_posts_columns($columns) {
	//unset( $columns['author'] );
	$columns['partner_level'] = __('Level', 'nextcloud');
	$columns['partner_region'] = __('Region', 'nextcloud');
	$columns['partner_services'] = __('Services', 'nextcloud');
	$columns['service_type'] = __('Service type', 'nextcloud');
	return $columns;
}

// Add the data to the custom columns for the book post type:
add_action('manage_partner_posts_posts_custom_column', 'partner_posts_custom_book_column', 10, 2);
function partner_posts_custom_book_column($column, $post_id) {
	switch ($column) {
		case 'partner_level':
			$partner_level = get_post_meta($post_id, 'partner_level', true);
			if (is_array($partner_level)) {
				echo implode(', ', $partner_level);
			} else {
				echo $partner_level;
			}
			break;

		case 'partner_region':
			$region = get_post_meta($post_id, 'region', true);
			if (is_array($region)) {
				echo implode(', ', $region);
			} else {
				echo $region;
			}
			break;

		case 'partner_services':
			$services = get_post_meta($post_id, 'services', true);
			if (is_array($services)) {
				echo implode(', ', $services);
			} else {
				echo $services;
			}
			break;

		case 'service_type':
			// Load field settings and values.
			$service_types_field = get_field_object('service_type');
			$service_types = $service_types_field['value'];
			// Display labels.
			if ($service_types):
				$numItems = count($service_types);
				$i = 0;
				foreach ($service_types as $key => $service_type):
					echo $service_types_field['choices'][ $service_type ];
					if (++$i != $numItems) {
						echo ', ';
					}
				endforeach;
			endif;
			break;

			//break;
	}
}

/**
 * 1) Mark the columns as sortable in the Partners list.
 *    Your column keys are: 'service_type' and 'partner_level'
 */
add_filter('manage_edit-partner_posts_sortable_columns', function ($sortable_columns) {
	$sortable_columns['service_type'] = 'service_type';      // A–Z by service_type meta
	$sortable_columns['partner_level'] = 'partner_level';     // custom order via FIELD()
	return $sortable_columns;
});

/**
 * 2) Apply sort for Service type (simple meta sort).
 */
add_action('pre_get_posts', function ($query) {
	if (!is_admin() || !$query->is_main_query()) {
		return;
	}
	if ($query->get('post_type') !== 'partner_posts') {
		return;
	}

	if ($query->get('orderby') === 'service_type') {
		$query->set('meta_key', 'service_type');
		$query->set('meta_type', 'CHAR');
		$query->set('orderby', 'meta_value'); // use 'meta_value_num' if numeric
		// leave $query->get('order') (ASC/DESC) as chosen by the user
	}
});

/**
 * 3) Apply custom order for Level using FIELD() (Platinum > Gold > Silver > Bronze).
 *    Also pushes items with no partner_level to the end.
 */
add_filter('posts_clauses', function ($clauses, $query) {
	if (!is_admin() || !$query->is_main_query()) {
		return $clauses;
	}
	if ($query->get('post_type') !== 'partner_posts') {
		return $clauses;
	}
	if ($query->get('orderby') !== 'partner_level') {
		return $clauses;
	}

	global $wpdb;

	// Define the desired order
	$levels = ['Platinum','Gold','Silver','Bronze'];
	$levels_escaped = array_map('esc_sql', $levels);
	$field_expr = "FIELD(pl.meta_value, '" . implode("','", $levels_escaped) . "')";

	// Respect ASC/DESC from the UI; ASC = Platinum→Bronze, DESC = Bronze→Platinum
	$direction = (strtoupper($query->get('order')) === 'DESC') ? 'DESC' : 'ASC';

	// Join the meta once for partner_level
	$clauses['join'] .= $wpdb->prepare(
		" LEFT JOIN {$wpdb->postmeta} pl
          ON {$wpdb->posts}.ID = pl.post_id AND pl.meta_key = %s ",
		'partner_level'
	);

	// Order: custom FIELD (with empties last), then menu_order, then title
	// COALESCE(NULLIF(FIELD(...),0), 999) pushes posts without a level to the end.
	$orderby
		= "COALESCE(NULLIF($field_expr, 0), 999) $direction, "
		. "{$wpdb->posts}.menu_order ASC, {$wpdb->posts}.post_title ASC";

	$clauses['orderby'] = $orderby;

	return $clauses;
}, 10, 2);

/**
 * Admin dropdown filters for Partners: Service type (ACF checkbox) + Partner level.
 */
add_action('restrict_manage_posts', function ($post_type) {
	if ($post_type !== 'partner_posts') {
		return;
	}

	// --- Service type (ACF Checkbox) ---
	$current_service = isset($_GET['service_type'])
		? sanitize_text_field(wp_unslash($_GET['service_type'])) : '';

	// Get choices from ACF field (works in admin if ACF is active)
	$choices = [];
	if (function_exists('get_field_object')) {
		// Prefer field KEY if you know it (e.g. 'field_123abc'); name also works in most setups
		$field_obj = get_field_object('service_type');
		if ($field_obj && !empty($field_obj['choices']) && is_array($field_obj['choices'])) {
			$choices = $field_obj['choices']; // ['value' => 'Label', ...]
		}
	}

	echo '<label for="filter-by-service-type" class="screen-reader-text">Filter by Service type</label>';
	echo '<select name="service_type" id="filter-by-service-type">';
	echo '<option value="">All Service types</option>';
	foreach ($choices as $value => $label) {
		printf(
			'<option value="%1$s" %2$s>%3$s</option>',
			esc_attr($value),
			selected($current_service, $value, false),
			esc_html($label ?: $value)
		);
	}
	echo '</select>';

	// --- Partner level (fixed known order) ---
	$levels = ['Platinum','Gold','Silver','Bronze'];
	$current_level = isset($_GET['partner_level'])
		? sanitize_text_field(wp_unslash($_GET['partner_level'])) : '';

	echo '<label for="filter-by-partner-level" class="screen-reader-text">Filter by Partner level</label>';
	echo '<select name="partner_level" id="filter-by-partner-level">';
	echo '<option value="">All Levels</option>';
	foreach ($levels as $lvl) {
		printf('<option value="%1$s" %2$s>%1$s</option>',
			esc_attr($lvl),
			selected($current_level, $lvl, false)
		);
	}
	printf('<option value="__none" %s>%s</option>',
		selected($current_level, '__none', false),
		esc_html__('No level', 'nextcloud')
	);
	echo '</select>';
});

/**
 * Apply filters to the query.
 * - Service type: ACF checkbox stores a serialized array, so match with LIKE on "value"
 * - Partner level: exact match or "no level" special option
 */
add_action('pre_get_posts', function ($query) {
	if (!is_admin() || !$query->is_main_query()) {
		return;
	}
	if ($query->get('post_type') !== 'partner_posts') {
		return;
	}

	$meta_query = (array)$query->get('meta_query');

	// Service type (ACF checkbox)
	if (!empty($_GET['service_type'])) {
		$service_value = sanitize_text_field(wp_unslash($_GET['service_type']));
		// wrap in quotes to avoid partial matches in the serialized array
		$meta_query[] = [
			'key' => 'service_type',
			'value' => '"' . $service_value . '"',
			'compare' => 'LIKE',
		];
	}

	// Partner level
	if (isset($_GET['partner_level']) && $_GET['partner_level'] !== '') {
		$partner_level = sanitize_text_field(wp_unslash($_GET['partner_level']));
		if ($partner_level === '__none') {
			$meta_query[] = [
				'relation' => 'OR',
				[
					'key' => 'partner_level',
					'compare' => 'NOT EXISTS',
				],
				[
					'key' => 'partner_level',
					'value' => '',
					'compare' => '=',
				],
			];
		} else {
			$meta_query[] = [
				'key' => 'partner_level',
				'value' => $partner_level,
				'compare' => '=',
			];
		}
	}

	if ($meta_query) {
		$query->set('meta_query', $meta_query);
	}
});
