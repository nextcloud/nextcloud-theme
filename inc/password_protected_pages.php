<?php
/**
 * Gated (password-protected) content: /partner-hub/ and /company-week/.
 */

if (!defined('ABSPATH')) {
	exit;
}

/*
 * Password-protected sections:
 *   https://nextcloud.com/partner-hub/   (password: PARTNERS_HUB_PASSWORD in wp-config.php)
 *   https://nextcloud.com/company-week/  (password: CW_HUB_PASSWORD in wp-config.php)
 *
 * Protection is based on the page IDs (root page, all its descendants and
 * their WPML translations), not only on the request URL, so the content is
 * also hidden from the REST API, ?page_id= / ?p= URLs, site search, feeds,
 * oEmbed and the Yoast sitemap.
 */
function nc_protected_sections() {
	return array(
		'partner-hub'  => array(
			'constant'    => 'PARTNERS_HUB_PASSWORD',
			'session_key' => 'custom_access_granted',
		),
		'company-week' => array(
			'constant'    => 'CW_HUB_PASSWORD',
			'session_key' => 'cw_custom_access_granted',
		),
	);
}

/**
 * Map of protected page ID => section slug (roots, descendants, translations).
 */
function nc_protected_page_ids() {
	static $map = null;
	if ($map !== null) {
		return $map;
	}
	global $wpdb;
	$map = array();

	foreach (array_keys(nc_protected_sections()) as $slug) {
		// Root pages with this slug (any language; bypasses WPML query filters).
		$queue = array_map('intval', $wpdb->get_col($wpdb->prepare(
			"SELECT ID FROM {$wpdb->posts} WHERE post_type = 'page' AND post_name = %s AND post_parent = 0",
			$slug
		)));

		// Add WPML translations of the roots (they may have a different slug).
		foreach ($queue as $root_id) {
			$trid = apply_filters('wpml_element_trid', null, $root_id, 'post_page');
			if ($trid) {
				$translations = apply_filters('wpml_get_element_translations', array(), $trid, 'post_page');
				foreach ((array) $translations as $t) {
					if (!empty($t->element_id)) {
						$queue[] = (int) $t->element_id;
					}
				}
			}
		}

		// Walk all descendants.
		while ($queue) {
			$queue = array_values(array_diff(array_unique($queue), array_keys($map)));
			if (!$queue) {
				break;
			}
			foreach ($queue as $id) {
				$map[$id] = $slug;
			}
			$in    = implode(',', array_map('intval', $queue));
			$queue = array_map('intval', $wpdb->get_col(
				"SELECT ID FROM {$wpdb->posts} WHERE post_type = 'page' AND post_parent IN ($in)"
			));
		}
	}
	return $map;
}

/**
 * Section slug a post belongs to, or null if it is not protected.
 */
function nc_protected_section_for_post($post) {
	$post = get_post($post);
	if (!$post) {
		return null;
	}
	$map = nc_protected_page_ids();
	if (isset($map[$post->ID])) {
		return $map[$post->ID];
	}
	// Attachments uploaded to a protected page.
	if ($post->post_type === 'attachment' && $post->post_parent && isset($map[$post->post_parent])) {
		return $map[$post->post_parent];
	}
	return null;
}

/**
 * Logged-in users who can edit the post always bypass the gate.
 */
function nc_protected_user_can_bypass($post_id) {
	return is_user_logged_in() && current_user_can('edit_post', $post_id);
}

// Front-end gate.
function nc_protected_sections_gate() {
	$sections = nc_protected_sections();
	$section  = null;
	$postID   = get_queried_object_id();

	if (is_singular() && $postID) {
		$section = nc_protected_section_for_post($postID);
	}
	// Fallback on the URL as before (e.g. 404s / custom routes under the section).
	if (!$section) {
		$uri = strtolower(rawurldecode($_SERVER['REQUEST_URI'] ?? ''));
		foreach (array_keys($sections) as $slug) {
			if (strpos($uri, '/' . $slug . '/') !== false) {
				$section = $slug;
				break;
			}
		}
	}
	if (!$section) {
		return;
	}
	if ($postID && nc_protected_user_can_bypass($postID)) {
		return;
	}

	$config = $sections[$section];
	if (!defined($config['constant']) || (string) constant($config['constant']) === '') {
		wp_die('Password is not set.', '', array('response' => 503));
	}
	$correct_password = (string) constant($config['constant']);

	// Never let page caches (WP Rocket, proxies) store these pages.
	if (!defined('DONOTCACHEPAGE')) {
		define('DONOTCACHEPAGE', true);
	}
	nocache_headers();
	header('X-Robots-Tag: noindex, nofollow', true);

	// load awesome icons, for the menu
	wp_enqueue_style('vc_font_awesome_5');
	wp_enqueue_style('vc_font_awesome_5_shims');
	wp_enqueue_style('vc_font_awesome_6');
	wp_enqueue_style('vc_font_awesome_6_shims');

	if (!session_id()) {
		session_start();
	}

	// If the session variable is set, allow access
	if (!empty($_SESSION[$config['session_key']]) && $_SESSION[$config['session_key']] === true) {
		return;
	}

	$error = '';
	if (isset($_POST['custom_password'])) {
		if (hash_equals($correct_password, (string) wp_unslash($_POST['custom_password']))) {
			session_regenerate_id(true);
			$_SESSION[$config['session_key']] = true; // Grant access for the session
			wp_safe_redirect($postID ? get_permalink($postID) : home_url('/' . $section . '/'));
			exit;
		}
		$error = 'Incorrect password. Try again.';
	}

	status_header(401);

	// Don't leak the page through <head>: Yoast builds meta/og descriptions and
	// JSON-LD from the page content, and the title can reveal details too.
	add_filter('wpseo_frontend_presenters', '__return_empty_array', PHP_INT_MAX);
	add_filter('pre_get_document_title', function () {
		return 'Password protected - ' . get_bloginfo('name');
	}, PHP_INT_MAX);
	add_filter('wp_robots', 'wp_robots_no_robots');

	get_header();
	?>
		<div class="wrapper" id="main" data-permalink="<?php echo esc_url($postID ? get_permalink($postID) : ''); ?>">
		<form method="post" class="post-password-form">
			<?php if ($error) : ?><p style="color: red;"><?php echo esc_html($error); ?></p><?php endif; ?>
			<p>This content is password protected. To view it please enter your password below:</p>
			<input type="password" name="custom_password" placeholder="Enter Password" required>
			<button type="submit">Submit</button>
		</form>
		</div>
		<?php
	get_footer();

	exit; // Stop page from loading until the correct password is entered
}
add_action('template_redirect', 'nc_protected_sections_gate', 1);

// Keep protected pages out of front-end search results and feeds.
function nc_protected_sections_exclude_from_queries($query) {
	if (is_admin() || !$query->is_main_query()) {
		return;
	}
	if ($query->is_search() || $query->is_feed()) {
		$ids = array_keys(nc_protected_page_ids());
		if ($ids) {
			$query->set('post__not_in', array_merge((array) $query->get('post__not_in'), $ids));
		}
	}
}
add_action('pre_get_posts', 'nc_protected_sections_exclude_from_queries');

// REST API: hide protected pages (and their attachments) from collections and /wp/v2/search ...
function nc_protected_sections_rest_query($args, $request) {
	if (current_user_can('edit_pages')) {
		return $args;
	}
	$ids = array_keys(nc_protected_page_ids());
	if (!$ids) {
		return $args;
	}
	// WP_Query ignores *__not_in when the matching *__in is set (?include=, ?parent=),
	// so the protected IDs must be removed from the *__in lists as well.
	$is_attachment = (($args['post_type'] ?? '') === 'attachment');
	$in_key        = $is_attachment ? 'post_parent__in' : 'post__in';
	$not_in_key    = $is_attachment ? 'post_parent__not_in' : 'post__not_in';

	$args[$not_in_key] = array_merge((array) ($args[$not_in_key] ?? array()), $ids);
	if (!empty($args[$in_key])) {
		$args[$in_key] = array_values(array_diff(array_map('intval', (array) $args[$in_key]), $ids));
		if (!$args[$in_key]) {
			$args[$in_key] = array(0); // nothing left: match nothing
		}
	}
	return $args;
}
add_filter('rest_page_query', 'nc_protected_sections_rest_query', 10, 2);
add_filter('rest_attachment_query', 'nc_protected_sections_rest_query', 10, 2);
add_filter('rest_post_search_query', 'nc_protected_sections_rest_query', 10, 2);

// ... refuse single-item routes (/wp/v2/pages/<id>, /wp/v2/media/<id>, their sub-routes)
// before the controller runs ...
function nc_protected_sections_rest_before_callbacks($response, $handler, $request) {
	if (is_wp_error($response)) {
		return $response;
	}
	if (preg_match('#^/wp/v2/(?:pages|media)/(\d+)#', $request->get_route(), $m)) {
		$post_id = (int) $m[1];
		if (nc_protected_section_for_post($post_id) && !nc_protected_user_can_bypass($post_id)) {
			return new WP_Error('rest_forbidden', __('Sorry, you are not allowed to do that.'), array('status' => rest_authorization_required_code()));
		}
	}
	return $response;
}
add_filter('rest_request_before_callbacks', 'nc_protected_sections_rest_before_callbacks', 10, 3);

// ... and as a last resort strip the data wherever else a protected item gets prepared
// (_embed, other routes). Runs last and returns a response object, not a WP_Error,
// so Yoast/WPML/ACF filters and collection responses don't fatal.
function nc_protected_sections_rest_prepare($response, $post, $request) {
	if (nc_protected_section_for_post($post) && !nc_protected_user_can_bypass($post->ID)) {
		return new WP_REST_Response(array(
			'id'   => (int) $post->ID,
			'code' => 'rest_forbidden',
		), rest_authorization_required_code());
	}
	return $response;
}
add_filter('rest_prepare_page', 'nc_protected_sections_rest_prepare', PHP_INT_MAX, 3);
add_filter('rest_prepare_attachment', 'nc_protected_sections_rest_prepare', PHP_INT_MAX, 3);

// oEmbed: do not expose protected pages.
function nc_protected_sections_oembed($data, $post) {
	if (nc_protected_section_for_post($post) && !nc_protected_user_can_bypass($post->ID)) {
		return false;
	}
	return $data;
}
// Run last: Yoast adds title/description/thumbnail at priority 10 and would refill the data.
add_filter('oembed_response_data', 'nc_protected_sections_oembed', PHP_INT_MAX, 2);

// Yoast / core sitemaps: keep protected pages out.
add_filter('wpseo_exclude_from_sitemap_by_post_ids', function ($ids) {
	return array_merge((array) $ids, array_keys(nc_protected_page_ids()));
});
add_filter('wp_sitemaps_posts_query_args', function ($args, $post_type) {
	if ($post_type === 'page') {
		$args['post__not_in'] = array_merge((array) ($args['post__not_in'] ?? array()), array_keys(nc_protected_page_ids()));
	}
	return $args;
}, 10, 2);
