<?php
if (!empty($_SERVER['SCRIPT_FILENAME']) && basename($_SERVER['SCRIPT_FILENAME']) == 'functions.php') {
	die('Error!');
}

require_once locate_template('vendor/autoload.php');
require_once locate_template('lib/signup.php');
require_once(get_stylesheet_directory() . '/lib/ripcord.php');
require_once(get_stylesheet_directory() . '/lib/ripcord-logger.php');
require_once(get_stylesheet_directory() . '/inc/shortcodes.php');
require_once(get_stylesheet_directory() . '/inc/loadmore.php');
require_once(get_stylesheet_directory() . '/inc/ninjaforms.php');
require_once(get_stylesheet_directory() . '/inc/acf_functions.php');
require_once(get_stylesheet_directory() . '/inc/custom_columns.php');
require_once(get_stylesheet_directory() . '/inc/search-profiles.php');
require_once(get_stylesheet_directory() . '/inc/custom_search.php');
require_once(get_stylesheet_directory() . '/inc/seo_snippets.php');
require_once(get_template_directory() . '/inc/branded-emails.php');
require_once(get_stylesheet_directory() . '/inc/wpbakery.php');
require_once(get_stylesheet_directory() . '/inc/password_protected_pages.php');


/*
 * Sets up theme defaults and registers support for various WordPress features.
*/
add_theme_support('post-thumbnails');
//Adding Post Feeds to the Header
add_theme_support('automatic-feed-links');
//add custom code to the header based on the post meta of that specific page/post
add_action('wp_head', 'add_custom_code_header');
function add_custom_code_header() {
	echo get_post_meta(get_the_ID(), 'custom_header_code', true);
};
add_image_size('large', 1024, 576);
//add_filter('show_admin_bar', '__return_false');
//Add excerpt support to pages
add_post_type_support('page', 'excerpt');



//registers and enqueues CSS and JS files for the backend
function nc_enqueue_file_scripts_admin($hook) {
	wp_register_style('jquery-ui', get_stylesheet_directory_uri() . '/dist/css/jquery-ui.css');
	wp_enqueue_style('jquery-ui');
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_style('awesome', get_stylesheet_directory_uri() . '/dist/awesome/css/all.css', [], (string)filemtime(get_stylesheet_directory() . '/dist/awesome/css/all.css'), 'all');
	
	wp_enqueue_style('admin-theme-style', get_stylesheet_directory_uri() . '/dist/css/admin-theme.css', [], (string)filemtime(get_stylesheet_directory() . '/dist/css/admin-theme.css'));
	if ($hook == 'post.php' || $hook == 'post-new.php') {
		// Open/close arrows for the event agenda element in the WPBakery backend editor
		wp_enqueue_script('nc-admin-agenda', get_stylesheet_directory_uri() . '/dist/js/admin-agenda.js', ['jquery'], (string)filemtime(get_stylesheet_directory() . '/dist/js/admin-agenda.js'), true);
	}
	
	//enqueue NF scripts too to avoid console errors when adding forms to the backend
	//wp_enqueue_script('selectize', get_template_directory_uri() . '/dist/js/select2.min.js', array());
	//wp_enqueue_script('custom-nf-code', get_template_directory_uri() . '/dist/js/custom-nf-code.js', array());
}
add_action('admin_enqueue_scripts', 'nc_enqueue_file_scripts_admin');


//registers and enqueues CSS and JS files for the frontend
function file_scripts() {
	global $post;
	
	// Load our main stylesheet.
	wp_enqueue_style('style-bootstrap', get_stylesheet_directory_uri() . '/dist/css/bootstrap.min.css', [], (string)filemtime(get_stylesheet_directory() . '/dist/css/bootstrap.min.css'), 'all');
	wp_enqueue_style('css-slick', get_stylesheet_directory_uri() . '/dist/css/slick.css', [], (string)filemtime(get_stylesheet_directory() . '/dist/css/slick.css'), 'all');
	wp_enqueue_style('css-style', get_stylesheet_directory_uri() . '/dist/css/theme.min.css', [], (string)filemtime(get_stylesheet_directory() . '/dist/css/theme.min.css'), 'all');
	//wp_enqueue_style('awesome', get_stylesheet_directory_uri() . '/dist/awesome/css/all.css', [], (string)filemtime(get_stylesheet_directory() . '/dist/awesome/css/all.css'), 'all');
	wp_enqueue_style('style', get_stylesheet_directory_uri() . '/style.css', [], (string)filemtime(get_stylesheet_directory() . '/style.css'), 'all');

	wp_register_style('nc_register', get_stylesheet_directory_uri() . '/dist/signup/css/signup.css', [],
		'1.2', 'all');

	//searchable select
	wp_register_style('selectizeStyle', get_stylesheet_directory_uri() . '/dist/css/select2.min.css', [], '', 'all');
	if (isset($post) && isset($post->post_content)) { // added to avoid PHP Warning:  Attempt to read property "post_content" on null in /var/lib/wordpress/content/themes/nextcloud-theme/functions.php on line 68
		if (
			(is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'ninja_form'))
			|| in_array(get_post_type($post), ['case_studies','whitepapers', 'data_sheets', 'event'])
			|| str_contains($post->post_content, 'ninja')
		) {
			wp_enqueue_style('selectizeStyle');
		}
	}
	

	//js
	wp_enqueue_script('jquery', get_template_directory_uri() . '/dist/js/jquery-3.6.0.min.js', [], true);
	wp_enqueue_script('bootstrap', get_template_directory_uri() . '/dist/js/bootstrap.bundle.min.js', [], true);
	wp_enqueue_script('slick-js', get_template_directory_uri() . '/dist/js/slick.min.js', [], true);
	wp_enqueue_script('magnific-js', get_template_directory_uri() . '/dist/js/jquery.magnific-popup.min.js', [], true);
	wp_enqueue_script('sticky-sidebar-js', get_template_directory_uri() . '/dist/js/jquery.sticky-sidebar.min.js', [], true);
	wp_enqueue_script('owl-carousel-js', get_template_directory_uri() . '/dist/js/owl.carousel.min.js', [], true);

	wp_register_script('nc_register', get_template_directory_uri() . '/dist/signup/js/nextcloud-register-main.js', [],
		'1.2', true);
	
	wp_register_script('nc_loadmore', get_template_directory_uri() . '/dist/js/nc_loadmore.js', [], true);
	
	
	// Localize the script with new data
	$translation_array = [
		'loading' => __('Loading...', 'nextcloud'),
		'ajaxurl' => admin_url('admin-ajax.php')
	];
	wp_localize_script('nc_loadmore', 'nc_loadmore_strings', $translation_array);
	wp_enqueue_script('nc_loadmore');


	//enqueue js cookie
	//wp_enqueue_script('js_cookie', get_template_directory_uri() . '/dist/js/js.cookie.min.js', [], true);
	wp_register_script('typed', get_template_directory_uri() . '/dist/js/typed.min.js', [], false);
	wp_enqueue_script('typed');
	
	wp_enqueue_script('purify-js', get_template_directory_uri() . '/dist/js/purify.min.js', [], true);
	wp_enqueue_script('nc-cookie-banner', get_template_directory_uri() . '/dist/js/nc_cookies.js', ['purify-js'], true);
	
	//enqueue cookie banner script
	wp_register_script('cookie_banner_script', get_template_directory_uri() . '/dist/js/cookie_banner_script.js', ['jquery'], true);
	$cookie_banner_strings = [
		'allow_selection' => __('Allow selection', 'nextcloud'),
		'reject_all' => __('Reject all', 'nextcloud')
	];
	wp_localize_script('cookie_banner_script', 'cookie_banner_strings', $cookie_banner_strings);
	wp_enqueue_script('cookie_banner_script');



	if (wp_script_is('nf-front-end', 'registered')) {
		wp_register_script('intlTelInput_utils', get_template_directory_uri() . '/dist/js/utils.js', ['jquery', 'nf-front-end'], true);
		wp_register_script('intlTelInput', get_template_directory_uri() . '/dist/js/intlTelInput.min.js', ['jquery', 'nf-front-end'], true);
		if (
			(is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'ninja_form'))
			|| in_array(get_post_type($post), ['case_studies','whitepapers', 'data_sheets'])
		) {
			wp_enqueue_script('intlTelInput_utils');
			wp_enqueue_script('intlTelInput');
		}

		wp_register_script('selectize', get_template_directory_uri() . '/dist/js/select2.min.js', ['jquery', 'nf-front-end'], true);
		if (
			(is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'ninja_form'))
			|| in_array(get_post_type($post), ['case_studies','whitepapers', 'data_sheets', 'event'])
		) {
			wp_enqueue_script('selectize');
		}
	}


	if (wp_script_is('nf-front-end', 'registered')) {
		wp_register_script('custom-nf-code', get_template_directory_uri() . '/dist/js/custom-nf-code.js', ['jquery', 'nf-front-end', 'intlTelInput_utils', 'intlTelInput', 'selectize'], true);
		wp_enqueue_script('custom-nf-code');
	}
	
	
	
	wp_register_script('main', get_template_directory_uri() . '/dist/js/main.js', [], (string)filemtime(get_stylesheet_directory() . '/dist/js/main.js'), true);
	// Localize the script with new data
	$main_js_strings_array = [
		'all_label' => __('All', 'nextcloud'),
		'see_more' => __('See more', 'nextcloud'),
		'copied' => __('Copied!', 'nextcloud'),
		'copy_url' => __('Copy URL', 'nextcloud'),
		'events' => __('Events', 'nextcloud'),
		'see_less' => __('See less', 'nextcloud'),
		'hide_past_events' => __('Hide all events', 'nextcloud'),
		'show_past_events' => __('All events in', 'nextcloud'),
		'hide_older_years_events' => __('Hide previous years', 'nextcloud'),
		'show_older_years_events' => __('Show previous years', 'nextcloud')
	];
	wp_localize_script('main', 'main_js_strings', $main_js_strings_array);
	wp_enqueue_script('main');


	wp_register_script('critical_scripts', get_template_directory_uri() . '/dist/js/critical_scripts.js', [], true);
	wp_enqueue_script('critical_scripts');



	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'file_scripts');



function twentyseventeen_block_editor_styles() {
	// Block styles.
	wp_enqueue_style('twentyseventeen-block-editor-style', get_theme_file_uri('/dist/css/editor-blocks.css'));
}

add_action('enqueue_block_editor_assets', 'twentyseventeen_block_editor_styles');

add_theme_support('custom-logo', [
	'height' => 3000,
	'width' => 9999,
	'flex-height' => true,
]);

// This theme uses wp_nav_menu() in Two location.
register_nav_menus([
	'primary-menu' => __('Primary Menu', 'file'),
	'footer-menu' => __('Footer Menu', 'file'),
]);

add_theme_support('title-tag');

function arphabet_widgets_init() {
	register_sidebar([
		'name' => 'Search Widget',
		'id' => 'search-widget',
		'before_widget' => '<div class="search-widget-holder">',
		'after_widget' => '</div>',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
	]);

	register_sidebar(
		[
			'id' => 'footer-widget-area',
			'name' => esc_html__('Footer widget area', 'theme-domain'),
			'description' => esc_html__('A new widget area made for the footer', 'theme-domain'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-title-holder"><h3 class="widget-title">',
			'after_title' => '</h3></div>'
		]
	);
}
add_action('widgets_init', 'arphabet_widgets_init');


add_filter('wp_nav_menu_objects', 'my_wp_nav_menu_objects', 10, 2);
function my_wp_nav_menu_objects($items, $args) {
	// loop
	foreach ($items as &$item) {
		// vars
		$icon = get_field('icon', $item);
		$sub = get_field('subtext', $item);
		// add icon
		if ($icon && $sub) {
			$alt = $item->title;
			$item->title = '<img loading="eager" class="navcon" src="' . $icon . '" alt=""/><span>' . $item->title . '<small>' . $sub . '</small></span>';
		} elseif ($icon) {
			$item->title = '<img loading="eager" class="navcon" src="' . $icon . '" alt=""/><span>' . $item->title . '</span>';
		} elseif ($sub) {
			$item->title = '<span>' . $item->title . '<small>' . $sub . '</small></span>';
		}
	}
	// return
	return $items;
}



//add_action('wp_footer', 'wpml_floating_language_switcher');
function wpml_floating_language_switcher() {
	echo '<div class="wpml-floating-language-switcher">';
	//PHP action to display the language switcher (see https://wpml.org/documentation/getting-started-guide/language-setup/language-switcher-options/#using-php-actions)
	do_action('wpml_add_language_selector');
	echo '</div>';
}

function disable_wp_emojicons() {

	// all actions related to emojis
	remove_action('admin_print_styles', 'print_emoji_styles');
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');

	// filter to remove TinyMCE emojis
	//add_filter('tiny_mce_plugins', 'disable_emojicons_tinymce');
}
//add_action('init', 'disable_wp_emojicons');
//remove_action('wp_head', 'wp_resource_hints', 2);



//add code to header / footer
function nc_load_cookie_banner() {
	//load cookie banner if cookie is not saved
	//if(!isset($_COOKIE['nc_cookie_banner'])) {
	get_template_part('inc/cookie_banner');
	//}

	//load always
	//get_template_part('inc/matomo');
}
add_action('wp_footer', 'nc_load_cookie_banner', 0);


//add custom field containing custom header code in the wp_head
function nc_custom_header_code() {
	if (!function_exists('get_field')) {
		return;
	}

	if (get_field('custom_header_code', 'option')) {
		echo get_field('custom_header_code', 'option');
	}
};
add_action('wp_head', 'nc_custom_header_code');


//.htaccess is rewritten with language folder - https://wpml.org/errata/htaccess-is-rewritten-with-language-folder/
add_filter('mod_rewrite_rules', 'fix_rewritebase');
function fix_rewritebase($rules) {
	$home_root = parse_url(home_url());
	if (isset($home_root['path'])) {
		$home_root = trailingslashit($home_root['path']);
	} else {
		$home_root = '/';
	}
 
	$wpml_root = parse_url(get_option('home'));
	if (isset($wpml_root['path'])) {
		$wpml_root = trailingslashit($wpml_root['path']);
	} else {
		$wpml_root = '/';
	}
 
	$rules = str_replace("RewriteBase $home_root", "RewriteBase $wpml_root", $rules);
	$rules = str_replace("RewriteRule . $home_root", "RewriteRule . $wpml_root", $rules);
 
	return $rules;
}


//WP Rocket - preload feed immediately after purge - https://gist.github.com/DahmaniAdame/f6e3558f6628b51c6692b6dd6360d61b
function rocket_preload_page($pages_to_preload, $args) {
	foreach ($pages_to_preload as $page_to_preload) {
		wp_remote_get(esc_url_raw($page_to_preload), $args);
		sleep(3); // 3 seconds
	}
}

add_action('after_rocket_clean_home_feeds', function ($urls) {
	if (get_rocket_option('manual_preload') == 1) {
		$args = [];
		rocket_preload_page($urls, $args);
		if (get_rocket_option('do_caching_mobile_files') == 1) {
			$args['headers']['user-agent'] = 'Mozilla/5.0 (Linux; Android 8.0.0;) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Mobile Safari/537.36';
			// Preload mobile pages/posts.
			rocket_preload_page($urls, $args);
		}
	}
});


//add automatically the featured image as twitter:image meta tag in the wp_head
function nc_show_feat_image_as_twitter_image_tag() {
	if (has_post_thumbnail()) {
		echo '<meta name="twitter:image" content="' . wp_get_attachment_url(get_post_thumbnail_id()) . '" />';
	}
};
add_action('wp_head', 'nc_show_feat_image_as_twitter_image_tag');

//Allow editor role to edit the translations created by other users
add_filter('wpml_user_can_translate', function ($user_can_translate, $user) {
	if (in_array('editor', (array)$user->roles, true) && current_user_can('translate')) {
		return true;
	}
		
	return $user_can_translate;
}, 10, 2);


//Disable part of endpoints wordpress api for users
function nc_disable_rest_endpoints($endpoints) {
	if (isset($endpoints['/wp/v2/users'])) {
		unset($endpoints['/wp/v2/users']);
	}
	if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])) {
		unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
	}
	/*
	if ( isset( $endpoints['/oembed/1.0'] ) ) {
		unset( $endpoints['/oembed/1.0'] );
	}
	*/
	return $endpoints;
}
add_filter('rest_endpoints', 'nc_disable_rest_endpoints');


//Remove the REST API endpoint - disabled as it created an issue where embedding on backend was not showing
//remove_action('rest_api_init', 'wp_oembed_register_route');

// Turn off oEmbed auto discovery.
//add_filter( 'embed_oembed_discover', '__return_false' );

//Don't filter oEmbed results.
//remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);



//Exclude "unlisted" tag from archive query
function exclude_posts($query) {
	if ($query->is_archive()) {
		$query->set('tag__not_in', [ 269 ]);
	}
}
add_action('pre_get_posts', 'exclude_posts');


/**
 * Add a title tag to the logo
 */
function my_custom_logo() {
	// The logo
	$custom_logo_id = get_theme_mod('custom_logo');

	// If has logo
	if ($custom_logo_id) {
		// Attr
		$custom_logo_attr = [
			'class' => 'custom-logo',
			'itemprop' => 'logo',
		];

		// Image alt
		$image_alt = get_post_meta($custom_logo_id, '_wp_attachment_image_alt', true);
		if (empty($image_alt)) {
			$custom_logo_attr['alt'] = get_bloginfo('name', 'display');
		}

		// Get the image
		$html = sprintf('<a href="%1$s" class="custom-logo-link" rel="home" itemprop="url" title="%2$s">%3$s</a>',
			esc_url(home_url('/')),
			esc_html(get_bloginfo('name')),
			wp_get_attachment_image($custom_logo_id, 'full', false, $custom_logo_attr)
		);

	}

	// If no logo is set but we're in the Customizer, leave a placeholder (needed for the live preview).
	elseif (is_customize_preview()) {
		$html = sprintf('<a href="%1$s" class="custom-logo-link" style="display:none;"><img class="custom-logo"/></a>',
			esc_url(home_url('/'))
		);
	}

	// Return
	return $html;

}
add_filter('get_custom_logo', 'my_custom_logo');


function word_count($string, $limit) {
	$words = explode(' ', $string);
	$etc = '';
	if (count($words) > $limit) {
		$etc = '...';
	}
	return implode(' ', array_slice($words, 0, $limit)) . $etc;
}

//extend the default nonce expiration time for public preview plugin
add_filter('ppp_nonce_life', 'my_nonce_life');
function my_nonce_life() {
	return 30 * 86400; // 30 days
}


//Translating values in “urlencoded” shortcodes in WPML
add_filter('wpml_pb_shortcode_encode', 'wpml_pb_shortcode_encode_urlencoded_json', 10, 3);
function wpml_pb_shortcode_encode_urlencoded_json($string, $encoding, $original_string) {
	if ($encoding === 'urlencoded_json') {
		$output = [];
		foreach ($original_string as $combined_key => $value) {
			$parts = explode('_', $combined_key);
			$i = array_pop($parts);
			$key = implode('_', $parts);
			if ($key === 'link') {
				$value = wpmlsupp_11368_decode_encode_vc_link($value, 'encode');
			}
			$output[ $i ][ $key ] = $value;
		}
		$string = urlencode(json_encode($output));
	}
	return $string;
}

add_filter('wpml_pb_shortcode_decode', 'wpml_pb_shortcode_decode_urlencoded_json', 10, 3);
function wpml_pb_shortcode_decode_urlencoded_json($string, $encoding, $original_string) {
	if ($encoding === 'urlencoded_json') {
		$rows = json_decode(urldecode($original_string), true);
		$string = [];
		foreach ($rows as $i => $row) {
			foreach ($row as $key => $value) {
				if (in_array($key, [ 'text', 'title', 'quote', 'position', 'description', 'link', 'btn_text', 'label', 'value', 'vc_link', 'url' ])) {
					if ($key === 'link') { //convert link to shortcode.
						$value = wpmlsupp_11368_decode_encode_vc_link($value);
					}
					$string[ $key . '_' . $i ] = [ 'value' => $value, 'translate' => true ];
				} else {
					$string[ $key . '_' . $i ] = [ 'value' => $value, 'translate' => false ];
				}
			}
		}
	}
	return $string;
}

function wpmlsupp_11368_decode_encode_vc_link($value, $type = 'decode') {
	if (!function_exists('vc_build_link')) {
		return $value;
	}

	if ($type === 'decode') {
		$str = '[fakeshortcode ';
		$link_arr = vc_build_link($value);
		foreach ($link_arr as $key => $value) {
			$str .= $key . '="' . $value . '" ';
		}
		$str .= ']';
	} elseif ($type === 'encode') {
		$str = '';
		$value = str_replace(['[fakeshortcode', ']'], ['',''], $value);
		$value = shortcode_parse_atts($value);
		$pipe = '';
		foreach ($value as $key => $val) {
			$str .= !empty($val) ? $pipe . $key . ':' . rawurlencode($val) : '';
			$pipe = '|';
		}
	}

	return $str;
}


//dequeue podlove styles and scripts
function dequeue_unused_style_scripts() {
	wp_dequeue_style('podlove-frontend-css');
	wp_dequeue_style('podlove-admin-font');
	wp_dequeue_script('podlove-web-player-player');
	wp_dequeue_script('podlove-web-player-player-cache');

	//discourse plugin
	wp_dequeue_style('comment_styles');
	wp_dequeue_script('load_comments_js');

	//reusable block plugin
	wp_dequeue_style('reusablec-block-css');

	//wp bakery font awesome
	//wp_dequeue_style('vc_font_awesome_5_shims');
	//wp_dequeue_style('vc_font_awesome_5');
}
add_action('wp_enqueue_scripts', 'dequeue_unused_style_scripts', 999);

function enqueue_unused_style_scripts() {
	if (is_single()) {
		if (get_post_type() == 'podcast') {
			wp_enqueue_style('podlove-frontend-css');
			wp_enqueue_style('podlove-admin-font');
			wp_enqueue_script('podlove-web-player-player');
			wp_enqueue_script('podlove-web-player-player-cache');
		}

		if (get_post_type() == 'post') {
			wp_enqueue_style('comment_styles');
			wp_enqueue_script('load_comments_js');
		}
	}

	wp_enqueue_style('vc_font_awesome_5');
	wp_enqueue_style('vc_font_awesome_5_shims');

	wp_enqueue_style('vc_font_awesome_6');
	wp_enqueue_style('vc_font_awesome_6_shims');

}
add_action('wp_enqueue_scripts', 'enqueue_unused_style_scripts', 999);

//exclude specific category from RSS feed
function nc_custom_rss_filter($query) {
	if ($query->is_feed) {
		$query->set('cat', '-226'); //Exclude category "Private"
	}
	return $query;
}
add_filter('pre_get_posts', 'nc_custom_rss_filter');


// Unset the X-Mailer header
function remove_phpmailer_x_mailer_header($phpmailer) {
	// Check if PHPMailer class exists
	$phpmailer->XMailer = ' ';
	//$phpmailer->addCustomHeader('X-Nextcloud', 'Action works');
}
add_action('phpmailer_init', 'remove_phpmailer_x_mailer_header');


//alter search query when searching for webinar recordings
function webinar_recordings_search_filter($query) {
	if ($query->is_search && !is_admin()) {

		if (isset($_GET['custom_type'])) {

			if ($_GET['custom_type'] == 'webinar_recordings') {
				//date_default_timezone_set('Europe/Berlin');
				//$current_date_time = date('Y-m-d H:i:s', time());
				
				/*
				$query->set('tax_query', array(
					array(
						'taxonomy' => 'event_categories',
						'field'    => 'slug',
						'terms'    => 'webinars',
					)
				));
				*/
	
				
				$query->set('meta_query', [
					'relation' => 'AND',
					[
						'key' => 'download_available',
						'value' => '',
						'compare' => '!=',
					]
				]);
				$query->set('post_type', 'on-demand-webinar');


			} elseif (in_array($_GET['custom_type'], ['case_studies', 'whitepapers','data_sheets'])) {
				$query->set('post_type', strip_tags($_GET['custom_type']));
			}
		}

	}
}
add_action('pre_get_posts', 'webinar_recordings_search_filter', 99);


// Removes anything that looks like a shortcode. This will remove anything in the post that
// occurs inside of brackets `[...]`.
add_filter('wp_discourse_excerpt', 'nc_discourse_forum_remove_ninja_form_shortcodes');
function nc_discourse_forum_remove_ninja_form_shortcodes($excerpt) {
	$excerpt = preg_replace('/\[ninja.*\]/', '', $excerpt);
	return $excerpt;
}


//add global variable "current_theme_template" used to check post template
add_filter('template_include', 'var_template_include', 1000);
function var_template_include($t) {
	$GLOBALS['current_theme_template'] = basename($t);
	return $t;
}
//define get_current_template used to check the post template
function get_current_template($echo = false) {
	if (!isset($GLOBALS['current_theme_template'])) {
		return false;
	}
	if ($echo) {
		echo $GLOBALS['current_theme_template'];
	} else {
		return $GLOBALS['current_theme_template'];
	}
}


//overwrite email templates for the wordpress-email-templates plugin - https://github.com/wpexpertsio/wordpress-email-templates
/*
	add_filter('mailtpl/customizer_template', function(){
	return get_stylesheet_directory() . '/inc/email-templates/admin/templates/default.php';
});
*/


//Get Featured Image from WordPress REST API
add_action('rest_api_init', 'register_rest_images');
function register_rest_images() {
	register_rest_field(['post', 'event'],
		'featured_media_url',
		[
			'get_callback' => 'get_rest_featured_image',
			'update_callback' => null,
			'schema' => null,
		]
	);
}
function get_rest_featured_image($object, $field_name, $request) {
	if ($object['featured_media']) {
		$img = wp_get_attachment_image_src($object['featured_media'], 'app-thumb');
		return $img[0];
	}
	return false;
}

//add WPML language to body classes
function nc_add_language_to_body_classes($classes) {
	$lang = apply_filters('wpml_current_language', null);
	if ($lang) {
		$classes[] = 'lang-' . $lang;  // This will add a class like "lang-en" for English, "lang-fr" for French, etc.
	}
	return $classes;
}
add_filter('body_class', 'nc_add_language_to_body_classes');


/** * Enable shortcodes for menu navigation. */
if (! has_filter('wp_nav_menu', 'do_shortcode')) {
	add_filter('wp_nav_menu', 'shortcode_unautop');
	add_filter('wp_nav_menu', 'do_shortcode', 11);
}


add_filter('body_class', 'custom_body_class');
/**
 * Add custom field body class(es) to the body classes.
 *
 * It accepts values from a per-page custom field, and only outputs when viewing a singular static Page.
 *
 * @param array $classes Existing body classes.
 * @return array Amended body classes.
 */
function custom_body_class(array $classes) {
	$new_class = get_post_meta(get_the_ID(), 'page_body_class', true);
	if ($new_class) {
		$classes[] = $new_class;
	}
	return $classes;
}


// WP Discourse: Prevent posts without discourse_post_id from being re-published unless you explicitly want that
function nc_disable_discourse_publish_for_older_posts($force_publish, $post_id, $post) {
	// Don't auto-publish if there's no discourse_post_id and the post is older than X days
	$force_publish = true;
	if (!get_post_meta($post->ID, 'discourse_post_id', true)) {
		$published_date = strtotime($post->post_date);
		$days_old = (time() - $published_date) / (60 * 60 * 24);
		if ($days_old > 7) {
			return false;
		}
	}

	//check the post language
	$my_post_language_details = apply_filters('wpml_post_language_details', null, $post_id);
	if ($my_post_language_details['language_code'] != 'en') {
		return false;
	}

	return $force_publish;
}
add_filter('wpdc_publish_after_save', 'nc_disable_discourse_publish_for_older_posts', 10, 3);


/*
//publish to discourse only posts in default language - English
function nc_custom_publish_to_discourse( $force_publish, $post_id, $post ) {
	//$post_id = $post->ID;
	$my_post_language_details = apply_filters( 'wpml_post_language_details', NULL, $post_id );
	return ($my_post_language_details['language_code'] == 'en');
  }
add_filter( 'wpdc_publish_after_save', 'nc_custom_publish_to_discourse', 10, 3 );
*/

// Add custom column header for having a Discourse publishing overview
add_filter('manage_post_posts_columns', function ($columns) {
	$columns['discourse_topic'] = 'Discourse';
	return $columns;
});

// Populate the column with content using ACF
add_action('manage_post_posts_custom_column', function ($column_name, $post_id) {

	if (!function_exists('get_field')) {
		return;
	}
	
	if ($column_name === 'discourse_topic') {
		$permalink = get_field('discourse_permalink', $post_id);
		if ($permalink) {
			echo '<a href="' . esc_url($permalink) . '" target="_blank">View Topic</a>';
		} else {
			echo '<span style="color:#888;">Not linked</span>';
		}
	}
}, 10, 2);



class NC_Custom_Walker_Nav_Menu extends Walker_Nav_Menu {

	// 🔹 Start sub-menu
	public function start_lvl(&$output, $depth = 0, $args = []) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"sub-menu\">\n";
	}

	// 🔹 End sub-menu
	public function end_lvl(&$output, $depth = 0, $args = []) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}

	// 🔹 Start each <li>
	public function start_el(&$output, $item, $depth = 0, $args = [], $id = 0) {
		$classes = empty($item->classes) ? [] : (array)$item->classes;
		$class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
		$class_attr = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

		

		$output .= "<li$class_attr>";

		// Check for shortcode item
		if (in_array('link_w_shortcode', $classes)) {
			//$output .= do_shortcode($item->title);
			$output .= '<div class="shortcode-wrapper">' . do_shortcode($item->title) . '</div>';
		} else {
			// Set up link attributes
			$atts = [
				'title' => !empty($item->attr_title) ? $item->attr_title : '',
				'target' => !empty($item->target) ? $item->target : '',
				'rel' => !empty($item->xfn) ? $item->xfn : '',
				'href' => !empty($item->url) ? $item->url : '',
			];

			// Apply your existing filter for aria-hidden, etc.
			$atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args);

			// Convert attributes to string
			$attributes = '';
			foreach ($atts as $attr => $value) {
				if (!empty($value)) {
					$attributes .= ' ' . esc_attr($attr) . '="' . esc_attr($value) . '"';
				}
			}

			// Filter title in case it's modified by wp_nav_menu_objects (your ACF fields etc.)
			$title = apply_filters('the_title', $item->title, $item->ID);

			// Output link
			//hide first empty link; it is used only for structure
			if (in_array('first_item_hidden', $classes)) {
				//$output .= $title;
			} else {
				$output .= '<a' . $attributes . '>';
				$output .= $title;
				$output .= '</a>';
			}

			
		}
	}

	// 🔹 Close each <li>
	public function end_el(&$output, $item, $depth = 0, $args = []) {
		$output .= "</li>\n";
	}
}



//enable tags for pages
function enable_tags_for_pages() {
	register_taxonomy_for_object_type('post_tag', 'page');
}
add_action('init', 'enable_tags_for_pages');


//deactivate Automatic Lazy rendering - https://docs.wp-rocket.me/article/1835-automatic-lazy-rendering
add_filter('rocket_lrc_optimization', '__return_false', 999);



// Filter URLs not to be altered by the Sticky slugs in WPML
add_filter('wpml_sl_blacklist_requests', function ($blacklist, $sitepress) {
	// Exact slug(s)
	$blacklist[] = 'enterprise-day-copenhagen-2025';
	// Or a regex for a pattern of event slugs
	$blacklist[] = '/^enterprise-day-.+$/';
	// Or a section path
	$blacklist[] = 'fr/enterprise-day-copenhagen-2025/';
	$blacklist[] = 'it/enterprise-day';
	$blacklist[] = '/^nextcloud-enterprise-day-.+$/';

	return $blacklist;
}, 10, 2);



//add referrerpolicy to iframe embed in WP - to avoid Error 153: Video player configuration error
add_filter('embed_oembed_html', function ($html) {
	if (strpos($html, 'youtube.com') !== false) {
		if (strpos($html, 'referrerpolicy=') === false) {
			$html = str_replace('<iframe', '<iframe referrerpolicy="strict-origin-when-cross-origin"', $html);
		}
	}
	return $html;
}, 10, 3);




// Helper function to extract sections on Changelog unsupported page
function my_extract_changelog_sections($post_id) {
	$post = get_post($post_id);
	if (! $post) {
		return [
			'always' => [],
			'lazy' => [],
		];
	}

	// Render full HTML once (shortcodes, blocks, etc.)
	$html = do_shortcode(apply_filters('the_content', $post->post_content));

	// Grab all <section ... id="something" ...>...</section>
	$pattern = '/<section[^>]*id=[\'"]([^\'"]+)[\'"][^>]*>.*?<\/section>/is';
	preg_match_all($pattern, $html, $matches);

	if (empty($matches[0])) {
		return [
			'always' => [],
			'lazy' => [],
		];
	}

	$sections_html = $matches[0]; // full <section>...</section>
	$ids = $matches[1]; // captured IDs

	$always = [];
	$lazy = [];

	foreach ($sections_html as $i => $section_html) {
		$id = $ids[ $i ];

		// --- ALWAYS-ON SECTIONS ---
		/*
		// Has both vc_section and titlebar classes?
		$has_titlebar_class = preg_match(
			'/class=[\'"][^\'"]*\bvc_section\b[^\'"]*\btitlebar\b[^\'"]*[\'"]/i',
			$section_html
		);
		// Specific ID that must always be visible
		$is_compliant = ( $id === 'compliant_by_design_section' );
		if ( $has_titlebar_class || $is_compliant ) {
			$always[] = $section_html;
			continue;
		}
		*/

		// --- LAZY-LOAD SECTIONS ---

		$is_latest = preg_match('/^latest\d+$/i', $id);
		$is_hub = preg_match('/^hub\d+_release$/i', $id);

		if ($is_latest || $is_hub) {
			$lazy[] = $section_html;
		} else {
			$always[] = $section_html;
		}

		// Everything else is ignored for lazy loading.
		// It will either:
		// - be outside <section> entirely, or
		// - be some VC wrapper that you don’t care about.
	}

	return [
		'always' => $always,
		'lazy' => $lazy,
	];
}

//Enqueue the JS related to the page changelog lazy loading
function my_changelog_scripts() {
	if (! is_page_template('page-changelog-unsupported.php')) {
		return;
	}

	wp_enqueue_script(
		'changelog-lazy',
		get_stylesheet_directory_uri() . '/dist/js/changelog-lazy.js',
		[ 'jquery' ],
		'1.0',
		true
	);

	wp_localize_script(
		'changelog-lazy',
		'ChangelogAjax',
		[
			'ajax' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('my_changelog_nonce'),
		]
	);
}
add_action('wp_enqueue_scripts', 'my_changelog_scripts');



//set parent page while still draft
add_filter('page_attributes_dropdown_pages_args', 'my_attributes_dropdown_pages_args', 1, 1);
function my_attributes_dropdown_pages_args($dropdown_args) {
	$dropdown_args['post_status'] = ['publish','draft'];
	return $dropdown_args;
}

//try to solve cURL error 28: Resolving timed out after 5000 milliseconds
add_filter('http_request_timeout', function ($timeout) { return 60; });


/**
 * Add custom field to each menu item in the admin.
 */
add_action('wp_nav_menu_item_custom_fields', function ($item_id, $item, $depth, $args) {
	$value = get_post_meta($item_id, '_menu_item_link_id', true);
	?>
	<p class="description description-wide">
		<label for="edit-menu-item-link-id-<?php echo esc_attr($item_id); ?>">
			<?php esc_html_e('Link ID (adds id="" to the <a> tag)', 'textdomain'); ?><br>
			<input
				type="text"
				id="edit-menu-item-link-id-<?php echo esc_attr($item_id); ?>"
				class="widefat code edit-menu-item-custom"
				name="menu-item-link-id[<?php echo esc_attr($item_id); ?>]"
				value="<?php echo esc_attr($value); ?>"
				placeholder="e.g. nav-contact"
			>
		</label>
	</p>
	<?php
}, 10, 4);


/**
 * Save custom menu item field.
 */
add_action('wp_update_nav_menu_item', function ($menu_id, $menu_item_db_id, $args) {

	if (isset($_POST['menu-item-link-id'][ $menu_item_db_id ])) {
		$raw = wp_unslash($_POST['menu-item-link-id'][ $menu_item_db_id ]);

		// Make it a valid HTML id-ish string (letters/numbers/_-)
		$value = sanitize_key($raw);

		if ($value === '') {
			delete_post_meta($menu_item_db_id, '_menu_item_link_id');
		} else {
			update_post_meta($menu_item_db_id, '_menu_item_link_id', $value);
		}
	}

}, 10, 3);


/**
 * Add the saved ID onto the menu link (<a>).
 */
add_filter('nav_menu_link_attributes', function ($atts, $item, $args) {
	$link_id = get_post_meta($item->ID, '_menu_item_link_id', true);

	if (! empty($link_id)) {
		$atts['id'] = $link_id;
	}

	return $atts;
}, 10, 3);

/**
 * Event type meta tag for Matomo (webinar / in-person)
 */
add_action( 'wp_head', 'nc_event_type_meta', 1 );

function nc_event_type_meta() {
    if ( ! is_singular( 'event' ) ) {
        return;
    }

    $room_url = get_post_meta( get_the_ID(), 'webinar_talk_room_url', true );
    $type     = trim( (string) $room_url ) !== '' ? 'webinar' : 'in-person';

    printf(
        '<meta name="nc-event-type" content="%s">' . "\n",
        esc_attr( $type )
    );
}

/**
 * Matomo Tag Manager container, loaded high in <head>.
 * Staging uses its own container (WP_ENVIRONMENT_TYPE in wp-config.php),
 * production needs no constant. Local loads nothing.
 */
add_action( 'wp_head', 'nc_matomo_container', 5 );

function nc_matomo_container() {
    $env = wp_get_environment_type();

    if ( 'local' === $env ) {
        return;
    }

    $container = ( 'staging' === $env ) ? 'C4ozLJsR' : 'wlmEL4w2';
    ?>
<!-- Matomo Tag Manager -->
<script id="matomo_script">
  var _mtm = window._mtm = window._mtm || [];
  _mtm.push({'mtm.startTime': (new Date().getTime()), 'event': 'mtm.Start'});
  (function() {
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.async=true;
    g.id='matomo_js';
    g.src='https://cdn.matomo.cloud/nextcloud.matomo.cloud/container_<?php echo esc_js( $container ); ?>.js';
    s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Matomo Tag Manager -->
    <?php
}

//clean up the ToC links, to remove the eventual emojis
add_action('wp_enqueue_scripts', function () {
	// Ensure a script handle exists to attach inline JS to.
	// You can attach to 'jquery' if you know it's loaded, but this is safer.
	$handle = 'ez-toc-emoji-fix';
	wp_register_script($handle, '', [], null, true);
	wp_enqueue_script($handle);

	$js = <<<'JS'
document.addEventListener('DOMContentLoaded', () => {
  const makeSafe = (str) => {
    const safe = str
      .normalize('NFKD')
      .replace(/[^\w-]+/g, '-')
      .replace(/-+/g, '-')
      .replace(/^-|-$/g, '')
      .toLowerCase();
    return safe || 'section';
  };

  const used = new Set();

  const unique = (base) => {
    let out = base, i = 2;
    while (used.has(out) || document.getElementById(out)) out = `${base}-${i++}`;
    used.add(out);
    return out;
  };

  document.querySelectorAll('a.ez-toc-link[href^="#"]').forEach(a => {
    const raw = a.getAttribute('href').slice(1);   // may contain %F0%9F...
    let decoded = raw;
    try { decoded = decodeURIComponent(raw); } catch (e) {}

    const safeBase = makeSafe(decoded);

    // Only act if emoji / non-safe chars likely present OR decoded differs
    if (safeBase && (decoded !== raw || /[^a-zA-Z0-9_-]/.test(decoded))) {
      const safe = unique(safeBase);

      // Update the target heading id
      const target =
        document.getElementById(raw) ||
        document.getElementById(decoded);

      if (target) target.id = safe;

      // Update the TOC link
      a.setAttribute('href', '#' + safe);
    }
  });
});
JS;

	wp_add_inline_script($handle, $js, 'after');
}, 20);
