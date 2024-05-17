<?php
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'functions.php' == basename($_SERVER['SCRIPT_FILENAME'])) {
	die('Error!');
}

require_once locate_template('vendor/autoload.php');
require_once locate_template('lib/signup.php');
//require_once locate_template('lib/ripcord.php');
require_once(get_stylesheet_directory() . '/lib/ripcord.php');
require_once(get_stylesheet_directory() . '/inc/shortcodes.php');
require_once(get_stylesheet_directory() . '/inc/loadmore.php');
require_once(get_stylesheet_directory() . '/inc/ninjaforms.php');
require_once(get_stylesheet_directory() . '/inc/acf_functions.php');
require_once(get_stylesheet_directory() . '/inc/custom_columns.php');


add_theme_support('post-thumbnails');

//Adding Post Feeds to the Header
add_theme_support( 'automatic-feed-links' );

//add custom code to the header based on the post meta of that specific page/post
add_action('wp_head', 'add_custom_code_header');
function add_custom_code_header(){
	echo get_post_meta(get_the_ID(), 'custom_header_code', true);
};

add_image_size('large', 1024, 576);
//add_filter('show_admin_bar', '__return_false');

/*
 * Sets up theme defaults and registers support for various WordPress features.
 */

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
	if ( 
		(is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'ninja_form'))
		|| in_array(get_post_type($post), array('case_studies','whitepapers', 'data_sheets', 'event'))
	) {
		wp_enqueue_style('selectizeStyle');
	}


	//js
	wp_enqueue_script('jquery', get_template_directory_uri() . '/dist/js/jquery-3.6.0.min.js', [], true);
	wp_enqueue_script('bootstrap', get_template_directory_uri() . '/dist/js/bootstrap.bundle.min.js', [], true);
	wp_enqueue_script('slick-js', get_template_directory_uri() . '/dist/js/slick.min.js', [], true);
	wp_enqueue_script('magnific-js', get_template_directory_uri() . '/dist/js/jquery.magnific-popup.min.js', [], true);
	wp_enqueue_script('sticky-sidebar-js', get_template_directory_uri() . '/dist/js/jquery.sticky-sidebar.min.js', [], true);
	wp_enqueue_script('owl-carousel-js', get_template_directory_uri() . '/dist/js/owl.carousel.min.js', [], true);

	wp_register_script('nc_register', get_template_directory_uri().'/dist/signup/js/nextcloud-register-main.js', [], 
	'1.2', true);
	
	wp_register_script('nc_loadmore', get_template_directory_uri() . '/dist/js/nc_loadmore.js', [], true);
	// Localize the script with new data
	$translation_array = array(
		'loading' => __( 'Loading...', 'nextcloud' ),
	);
	wp_localize_script( 'nc_loadmore', 'nc_loadmore_strings', $translation_array );
	// Enqueued script with localized data.
	wp_enqueue_script( 'nc_loadmore' );


	//enqueue js cookie
	//wp_enqueue_script('js_cookie', get_template_directory_uri() . '/dist/js/js.cookie.min.js', [], true);
	wp_register_script('typed', get_template_directory_uri() . '/dist/js/typed.min.js', [], false);
	wp_enqueue_script('typed');
	
	wp_enqueue_script('nc-cookie-banner', get_template_directory_uri() . '/dist/js/nc_cookies.js', [], true);
	
	//enqueue cookie banner script
	wp_register_script('cookie_banner_script', get_template_directory_uri() . '/dist/js/cookie_banner_script.js', [], true);
	$cookie_banner_strings = array(
		'allow_selection' => __('Allow selection', 'nextcloud'),
		'reject_all' => __('Reject all', 'nextcloud')
	);
	wp_localize_script( 'cookie_banner_script', 'cookie_banner_strings', $cookie_banner_strings );
	wp_enqueue_script( 'cookie_banner_script' );


	wp_register_script('intlTelInput_utils', get_template_directory_uri() . '/dist/js/utils.js', ['nf-front-end'], true);
	wp_register_script('intlTelInput', get_template_directory_uri() . '/dist/js/intlTelInput.min.js', ['nf-front-end', 'jquery'], true);
	if( 
		(is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'ninja_form'))
		|| in_array(get_post_type($post), array('case_studies','whitepapers', 'data_sheets'))
	){
		wp_enqueue_script('intlTelInput_utils');
		wp_enqueue_script('intlTelInput');
	}


	wp_register_script('selectize', get_template_directory_uri() . '/dist/js/select2.min.js', ['nf-front-end', 'jquery'], true);
	if ( 
		(is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'ninja_form'))
		|| in_array(get_post_type($post), array('case_studies','whitepapers', 'data_sheets', 'event'))
	) {
		wp_enqueue_script('selectize');
	}

	wp_register_script('custom-nf-code', get_template_directory_uri() . '/dist/js/custom-nf-code.js', ['nf-front-end', 'intlTelInput_utils'], true);
	wp_enqueue_script('custom-nf-code');
	
	
	wp_register_script('main', get_template_directory_uri() . '/dist/js/main.js', [], (string)filemtime(get_stylesheet_directory() . '/dist/js/main.js') ,true);
	// Localize the script with new data
	$main_js_strings_array = array(
		'see_more' => __( 'See more', 'nextcloud' ),
		'copied' => __( 'Copied!', 'nextcloud' ),
		'copy_url' => __( 'Copy URL', 'nextcloud' ),
		'see_less' => __( 'See less', 'nextcloud' ),
		'hide_past_events' => __('Hide past events','nextcloud'),
		'show_past_events' => __('Show past events','nextcloud')
	);
	wp_localize_script( 'main', 'main_js_strings', $main_js_strings_array );
	wp_enqueue_script( 'main' );



	if (is_singular() && comments_open() && get_option('thread_comments') ) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'file_scripts');

function nc_enqueue_file_scripts_admin( $hook ) {
	wp_register_style( 'jquery-ui', get_stylesheet_directory_uri().'/dist/css/jquery-ui.css' );
	wp_enqueue_style( 'jquery-ui' ); 
    wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_style('awesome', get_stylesheet_directory_uri() . '/dist/awesome/css/all.css', [], (string)filemtime(get_stylesheet_directory() . '/dist/awesome/css/all.css'), 'all');

	wp_enqueue_style( 'admin-theme-style', get_stylesheet_directory_uri().'/dist/css/admin-theme.css' );
}
add_action( 'admin_enqueue_scripts', 'nc_enqueue_file_scripts_admin' );



function twentyseventeen_block_editor_styles() {
	// Block styles.
	wp_enqueue_style('twentyseventeen-block-editor-style', get_theme_file_uri('/dist/css/editor-blocks.css'));
}

add_action('enqueue_block_editor_assets', 'twentyseventeen_block_editor_styles');

add_theme_support('custom-logo', array(
	'height' => 3000,
	'width' => 9999,
	'flex-height' => true,
));

// This theme uses wp_nav_menu() in Two location.
register_nav_menus(array(
	'primary-menu' => __('Primary Menu', 'file'),
	'footer-menu' => __('Footer Menu', 'file'),
));

add_theme_support('title-tag');

function arphabet_widgets_init() {
	register_sidebar(array(
		'name' => 'Search Widget',
		'id' => 'search-widget',
		'before_widget' => '<div class="search-widget-holder">',
		'after_widget' => '</div>',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
	));

	register_sidebar(
		array(
			'id' => 'footer-widget-area',
			'name' => esc_html__('Footer widget area', 'theme-domain'),
			'description' => esc_html__('A new widget area made for the footer', 'theme-domain'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="widget-title-holder"><h3 class="widget-title">',
			'after_title' => '</h3></div>'
		)
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
			$item->title = '<img class="navcon" src="' . $icon . '" alt="'.$item->title.'"/><span>' . $item->title . '<small>' . $sub . '</small></span>';
		} elseif ($icon) {
			$item->title = '<img class="navcon" src="' . $icon . '" alt="'.$item->title.'"/><span>' . $item->title . '</span>';
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


function nc_custom_search_form($form) {
	$form = '<form role="search" method="get" id="searchform" class="searchform" action="' . home_url('/') . '" >
	  <div class="custom-form"><label class="screen-reader-text" for="s">' . __('Search:') . '</label>
	  <input type="text" value="'.get_search_query().'" placeholder="'.__('Search here..', 'nextcloud').'" name="s" id="s" />';
	$form .= '<button type="submit" title="'.__('Search', 'nextcloud').'" id="searchsubmit"><i class="fas fa-search"></i></button>';
	$form .= '</div></form>';
	return $form;
}
add_filter('get_search_form', 'nc_custom_search_form', 40);

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
function nc_custom_header_code(){
	if(get_field('custom_header_code', 'option')) {
		echo get_field('custom_header_code', 'option');
	}
};
add_action('wp_head', 'nc_custom_header_code');


//.htaccess is rewritten with language folder - https://wpml.org/errata/htaccess-is-rewritten-with-language-folder/
add_filter('mod_rewrite_rules', 'fix_rewritebase');
function fix_rewritebase($rules){
    $home_root = parse_url(home_url());
    if ( isset( $home_root['path'] ) ) {
        $home_root = trailingslashit($home_root['path']);
    } else {
        $home_root = '/';
    }
 
    $wpml_root = parse_url(get_option('home'));
    if ( isset( $wpml_root['path'] ) ) {
        $wpml_root = trailingslashit($wpml_root['path']);
    } else {
        $wpml_root = '/';
    }
 
    $rules = str_replace("RewriteBase $home_root", "RewriteBase $wpml_root", $rules);
    $rules = str_replace("RewriteRule . $home_root", "RewriteRule . $wpml_root", $rules);
 
    return $rules;
}


//WP Rocket - preload feed immediately after purge - https://gist.github.com/DahmaniAdame/f6e3558f6628b51c6692b6dd6360d61b
function rocket_preload_page($pages_to_preload, $args)
{
    foreach ($pages_to_preload as $page_to_preload) {
        wp_remote_get(esc_url_raw($page_to_preload), $args);
        sleep(3); // 3 seconds
    }
}

add_action('after_rocket_clean_home_feeds', function ($urls) {
    if (1 == get_rocket_option('manual_preload')) {
        $args = array();
        rocket_preload_page($urls, $args);
        if (1 == get_rocket_option('do_caching_mobile_files')) {
            $args['headers']['user-agent']     = 'Mozilla/5.0 (Linux; Android 8.0.0;) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Mobile Safari/537.36';
            // Preload mobile pages/posts.
            rocket_preload_page($urls, $args);
        }
    }
});


//add automatically the featured image as twitter:image meta tag in the wp_head
function nc_show_feat_image_as_twitter_image_tag(){
	if(  has_post_thumbnail() ) {
		echo '<meta name="twitter:image" content="'.wp_get_attachment_url( get_post_thumbnail_id() ).'" />';
	}
};
add_action('wp_head', 'nc_show_feat_image_as_twitter_image_tag');

//Allow editor role to edit the translations created by other users
add_filter('wpml_user_can_translate', function ($user_can_translate, $user){
    if (in_array('editor', (array) $user->roles, true) && current_user_can('translate')) {
        return true;
    }
        
    return $user_can_translate;
}, 10, 2);


//Disable part of endpoints wordpress api for users
function smntcs_rest_endpoints( $endpoints ) {
	if ( isset( $endpoints['/wp/v2/users'] ) ) {
		unset( $endpoints['/wp/v2/users'] );
	}
	if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
		unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
	}

	return $endpoints;
}
add_filter( 'rest_endpoints', 'smntcs_rest_endpoints' );

//Exclude "unlisted" tag from archive query
function exclude_posts( $query ) { 
    if ( $query->is_archive() ) {  
        $query->set( 'tag__not_in', array( 269 ) ); 
    } 
}
add_action( 'pre_get_posts', 'exclude_posts' );



/**
 * Add a title tag to the logo
 */
function my_custom_logo() {
	// The logo
    $custom_logo_id = get_theme_mod( 'custom_logo' );

    // If has logo
    if ( $custom_logo_id ) {
    	// Attr
	    $custom_logo_attr = array(
			'class'    => 'custom-logo',
			'itemprop' => 'logo',
		);

		// Image alt
		$image_alt = get_post_meta( $custom_logo_id, '_wp_attachment_image_alt', true );
		if ( empty( $image_alt ) ) {
			$custom_logo_attr['alt'] = get_bloginfo( 'name', 'display' );
		}

	    // Get the image
	    $html = sprintf( '<a href="%1$s" class="custom-logo-link" rel="home" itemprop="url" title="%2$s">%3$s</a>',
			esc_url( home_url( '/' ) ),
			esc_html( get_bloginfo( 'name' ) ),
			wp_get_attachment_image( $custom_logo_id, 'full', false, $custom_logo_attr )
		);

	}

	// If no logo is set but we're in the Customizer, leave a placeholder (needed for the live preview).
	elseif ( is_customize_preview() ) {
		$html = sprintf( '<a href="%1$s" class="custom-logo-link" style="display:none;"><img class="custom-logo"/></a>',
			esc_url( home_url( '/' ) )
		);
	}

	// Return
    return $html; 
	
}
add_filter( 'get_custom_logo', 'my_custom_logo' );


function word_count($string, $limit) {
	$words = explode(' ', $string);
	$etc = '';
	if(count($words) > $limit) {
		$etc = '...';
	}
	return implode(' ', array_slice($words, 0, $limit)).$etc;
}

//extend the default nonce expiration time for public preview plugin 
add_filter( 'ppp_nonce_life', 'my_nonce_life' );
function my_nonce_life() {
    return 30 * 86400; // 30 days
}


//Translating values in “urlencoded” shortcodes in WPML
add_filter( 'wpml_pb_shortcode_encode', 'wpml_pb_shortcode_encode_urlencoded_json', 10, 3 );
function wpml_pb_shortcode_encode_urlencoded_json( $string, $encoding, $original_string ) {
    if ( 'urlencoded_json' === $encoding ) {
        $output = array();
        foreach ( $original_string as $combined_key => $value ) {
            $parts = explode( '_', $combined_key );
            $i = array_pop( $parts );
            $key = implode( '_', $parts );
			if ($key === 'link') {
				$value = wpmlsupp_11368_decode_encode_vc_link($value, 'encode');
			}
            $output[ $i ][ $key ] = $value;
        }
        $string = urlencode( json_encode( $output ) );
    }
    return $string;
}
 
add_filter( 'wpml_pb_shortcode_decode', 'wpml_pb_shortcode_decode_urlencoded_json', 10, 3 );
function wpml_pb_shortcode_decode_urlencoded_json( $string, $encoding, $original_string ) {
    if ( 'urlencoded_json' === $encoding ) {
        $rows = json_decode( urldecode( $original_string ), true );
        $string = array();
        foreach ( $rows as $i => $row ) {
            foreach ( $row as $key => $value ) {
				if ( in_array( $key, array( 'text', 'title', 'description', 'link', 'btn_text', 'label', 'value', 'vc_link', 'url' ) ) ) {
                    if ($key === 'link') { //convert link to shortcode.
						$value = wpmlsupp_11368_decode_encode_vc_link($value);
					}
					$string[ $key . '_' . $i ] = array( 'value' => $value, 'translate' => true );
                } else {
                    $string[ $key . '_' . $i ] = array( 'value' => $value, 'translate' => false );
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
		foreach($link_arr as $key => $value) {
			$str .= $key . '="' . $value . '" ';
		}
		$str .= ']';
	} else if ($type === 'encode') {
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
function dequeue_unused_style_scripts(){
    wp_dequeue_style( 'podlove-frontend-css' );
	wp_dequeue_style( 'podlove-admin-font' );
	wp_dequeue_script( 'podlove-web-player-player');
	wp_dequeue_script( 'podlove-web-player-player-cache');

	//discourse plugin
	wp_dequeue_style( 'comment_styles' );
	wp_dequeue_script( 'load_comments_js');

	//reusable block plugin
	wp_dequeue_style('reusablec-block-css');

	//wp bakery font awesome
	wp_dequeue_style('vc_font_awesome_5_shims');
	wp_dequeue_style('vc_font_awesome_5');
}
add_action( 'wp_enqueue_scripts', 'dequeue_unused_style_scripts', 999 );

function enqueue_unused_style_scripts(){
	if(is_single()) {
		if(get_post_type()=='podcast') {
			wp_enqueue_style( 'podlove-frontend-css' );
			wp_enqueue_style( 'podlove-admin-font' );
			wp_enqueue_script( 'podlove-web-player-player');
			wp_enqueue_script( 'podlove-web-player-player-cache');
		}

		if(get_post_type()=='post') {
			wp_enqueue_style( 'comment_styles' );
			wp_enqueue_script( 'load_comments_js');
		}
	}

	wp_enqueue_style('vc_font_awesome_5');
	wp_enqueue_style('vc_font_awesome_5_shims');
}
add_action( 'wp_enqueue_scripts', 'enqueue_unused_style_scripts', 999 );

//exclude specific category from RSS feed
function nc_custom_rss_filter($query) {
	if ($query->is_feed) {
	$query->set('cat','-226'); //Put category ID - here it is : 7
	}
	return $query;
	}
add_filter('pre_get_posts','nc_custom_rss_filter');


// Unset the X-Mailer header
function remove_phpmailer_x_mailer_header($phpmailer) {
    // Check if PHPMailer class exists
        $phpmailer->XMailer = ' ';
		//$phpmailer->addCustomHeader('X-Nextcloud', 'Action works');
}
add_action('phpmailer_init', 'remove_phpmailer_x_mailer_header');


//alter search query when searching for webinar recordings
function webinar_recordings_search_filter($query) {
    if ( $query->is_search && !is_admin() ) {
		if($_GET['custom_type'] == 'webinar_recordings') {
		date_default_timezone_set('Europe/Berlin');
		$current_date_time = date('Y-m-d H:i:s', time());
		
		$query->set('tax_query', array(
			array(
				'taxonomy' => 'event_categories',
				'field'    => 'slug',
				'terms'    => 'webinars',
			)
		));

        $query->set('meta_query', array(
			'relation' => 'AND',
                array(
                    'key'     => 'download_available',
                    'value'	  => '',
                    'compare' => '!=',
                )
		));
    } else if ( in_array($_GET['custom_type'], array('case_studies', 'whitepapers','data_sheets')) )
	{
		$query->set('post_type', strip_tags($_GET['custom_type']));
	}
    }
}
add_action('pre_get_posts','webinar_recordings_search_filter', 99);


// Removes anything that looks like a shortcode. This will remove anything in the post that
// occurs inside of brackets `[...]`.
add_filter( 'wp_discourse_excerpt', 'nc_discourse_forum_remove_ninja_form_shortcodes' );
function nc_discourse_forum_remove_ninja_form_shortcodes( $excerpt ) {
    $excerpt = preg_replace( '/\[ninja.*\]/', '', $excerpt );
    return $excerpt;
}