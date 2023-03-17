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
add_image_size('large', 1024, 576);
//add_filter('show_admin_bar', '__return_false');

/*
 * Sets up theme defaults and registers support for various WordPress features.
 */

function file_scripts() {

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
	wp_enqueue_script('nc_loadmore-js', get_template_directory_uri() . '/dist/js/nc_loadmore.js', [], true);
	//enqueue js cookie
	wp_enqueue_script('js_cookie', get_template_directory_uri() . '/dist/js/js.cookie.min.js', [], true);
	wp_enqueue_script('typed', get_template_directory_uri() . '/dist/js/typed.min.js', [], true);
	//wp_enqueue_script('dsgvo-video-embed', get_template_directory_uri() . '/dist/js/dsgvo-video-embed.min.js', [], true);
	wp_enqueue_script('intlTelInput', get_template_directory_uri() . '/dist/js/intlTelInput.min.js', [], true);
	//select searchable
	wp_enqueue_script('selectize', get_template_directory_uri() . '/dist/js/select2.min.js', [], true);
	wp_enqueue_script('custom-nf-code', get_template_directory_uri() . '/dist/js/custom-nf-code.js', ['nf-front-end'], true);

	


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