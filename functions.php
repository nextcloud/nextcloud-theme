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
	wp_enqueue_style('awesome', get_stylesheet_directory_uri() . '/dist/awesome/css/all.css', [], (string)filemtime(get_stylesheet_directory() . '/dist/awesome/css/all.css'), 'all');
	//wp_enqueue_style('dsgvo-video-embed', get_stylesheet_directory_uri() . '/dist/css/dsgvo-video-embed.min.css', [], (string)filemtime(get_stylesheet_directory() . '/dist/css/dsgvo-video-embed.min.css'), 'all');
	wp_enqueue_style('style', get_stylesheet_directory_uri() . '/style.css', [], (string)filemtime(get_stylesheet_directory() . '/style.css'), 'all');
	//searchable select
	wp_enqueue_style('selectizeStyle', get_stylesheet_directory_uri() . '/dist/css/select2.min.css', [], '', 'all');

	//js
	wp_enqueue_script('jquery', get_template_directory_uri() . '/dist/js/jquery-3.6.0.min.js', [], true);
	wp_enqueue_script('bootstrap', get_template_directory_uri() . '/dist/js/bootstrap.bundle.min.js', [], true);
	wp_enqueue_script('slick-js', get_template_directory_uri() . '/dist/js/slick.min.js', [], true);
	wp_enqueue_script('magnific-js', get_template_directory_uri() . '/dist/js/jquery.magnific-popup.min.js', [], true);
	wp_enqueue_script('sticky-sidebar-js', get_template_directory_uri() . '/dist/js/jquery.sticky-sidebar.min.js', [], true);
	wp_enqueue_script('owl-carousel-js', get_template_directory_uri() . '/dist/js/owl.carousel.min.js', [], true);
	
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
	wp_enqueue_script('typed', get_template_directory_uri() . '/dist/js/typed.min.js', [], true);
	//wp_enqueue_script('dsgvo-video-embed', get_template_directory_uri() . '/dist/js/dsgvo-video-embed.min.js', [], true);
	wp_enqueue_script('intlTelInput', get_template_directory_uri() . '/dist/js/intlTelInput.min.js', [], true);
	//select searchable
	wp_enqueue_script('selectize', get_template_directory_uri() . '/dist/js/select2.min.js', [], true);
	wp_register_script('custom-nf-code', get_template_directory_uri() . '/dist/js/custom-nf-code.js', ['nf-front-end'], true);
	wp_enqueue_script('nc-cookie-banner', get_template_directory_uri() . '/dist/js/nc_cookies.js', [], true);
	
	
    /*if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'ninja_form')) {*/
        wp_enqueue_script('custom-nf-code');
    /*}*/
	
	wp_enqueue_script('main', get_template_directory_uri() . '/dist/js/main.js', [], true);

	if (is_singular() && comments_open() && get_option('thread_comments') ) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'file_scripts');

function nc_enqueue_file_scripts_admin( $hook ) {
	wp_register_style( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css' );
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
    return 5 * 86400;
}