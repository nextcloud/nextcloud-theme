<?php

if (!empty($_SERVER['SCRIPT_FILENAME']) && 'functions.php' == basename($_SERVER['SCRIPT_FILENAME'])) {
	die('Error!');
}

require_once locate_template('vendor/autoload.php');
require_once locate_template('lib/signup.php');
require_once(get_stylesheet_directory() . '/inc/shortcodes.php');


add_theme_support('post-thumbnails');
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
	wp_enqueue_style('style', get_stylesheet_directory_uri() . '/style.css', [], (string)filemtime(get_stylesheet_directory() . '/style.css'), 'all');
	wp_enqueue_script('jquery', get_template_directory_uri() . '/dist/js/jquery-3.6.0.min.js', [], true);
	wp_enqueue_script('bootstrap', get_template_directory_uri() . '/dist/js/bootstrap.bundle.min.js', [], true);
	wp_enqueue_script('slick-js', get_template_directory_uri() . '/dist/js/slick.min.js', [], true);

	wp_enqueue_script('magnific-js', get_template_directory_uri() . '/dist/js/jquery.magnific-popup.min.js', [], true);
	wp_enqueue_script('sticky-sidebar-js', get_template_directory_uri() . '/dist/js/jquery.sticky-sidebar.min.js', [], true);
	wp_enqueue_script('owl-carousel-js', get_template_directory_uri() . '/dist/js/owl.carousel.min.js', [], true);

	wp_enqueue_script('main', get_template_directory_uri() . '/dist/js/main.js', [], true);
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
}

add_action('widgets_init', 'arphabet_widgets_init');

function my_acf_op_init() {

	// Check function exists.
	if (function_exists('acf_add_options_page')) {
		// Add parent.
		$parent = acf_add_options_page(array(
			'page_title' => __('Theme General Settings'),
			'menu_title' => __('Theme Settings'),
			'redirect' => false,
		));
	}
}

add_action('acf/init', 'my_acf_op_init');

function my_theme_block_category($categories, $post) {
	return array_merge(
		$categories,
		array(
			array(
				'slug' => 'theme-blocks',
				'title' => __('Theme Blocks', 'theme-blocks'),
			),
		)
	);
}

add_filter('block_categories', 'my_theme_block_category', 10, 2);

function register_acf_block_types() {
	// register a Home Hero Block
	acf_register_block_type(array(
		'name' => 'home-hero-block',
		'title' => __('Home Hero Block'),
		'description' => __('Home Hero Block'),
		'render_template' => 'blocks/home-hero.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'desktop',
		'keywords' => array('home', 'hero'),
	));
	// register a Page Hero Block
	acf_register_block_type(array(
		'name' => 'page-hero-block',
		'title' => __('Page Hero Block'),
		'description' => __('Page Hero Block'),
		'render_template' => 'blocks/page-hero.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'desktop',
		'keywords' => array('page', 'hero'),
	));
	// register a Page Hero Block Background
	acf_register_block_type(array(
		'name' => 'page-hero-block-background',
		'title' => __('Page Hero Block Background'),
		'description' => __('Page Hero Block Background'),
		'render_template' => 'blocks/page-hero-background.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'desktop',
		'keywords' => array('page', 'hero-background'),
	));
	// register a Collaboration Block
	acf_register_block_type(array(
		'name' => 'collaboration-block',
		'title' => __('Collaboration Block'),
		'description' => __('Collaboration Block'),
		'render_template' => 'blocks/collaboration.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'admin-site-alt2',
		'keywords' => array('collaboration', 'content'),
	));
	// register a Why Nextcloud Block
	acf_register_block_type(array(
		'name' => 'why-block',
		'title' => __('Why Nextcloud Block'),
		'description' => __('Why Nextcloud Block'),
		'render_template' => 'blocks/why.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('why', 'content'),
	));
	// register a Needs Block
	acf_register_block_type(array(
		'name' => 'needs-block',
		'title' => __('Needs Block'),
		'description' => __('Needs Block'),
		'render_template' => 'blocks/needs.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('needs', 'content'),
	));
	// register a Needs 2 Block
	acf_register_block_type(array(
		'name' => 'needs2-block',
		'title' => __('Needs 2 Block'),
		'description' => __('Needs 2 Block'),
		'render_template' => 'blocks/needs2.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('needs', 'content'),
	));
	// register a Products Block
	acf_register_block_type(array(
		'name' => 'products-block',
		'title' => __('Products Block'),
		'description' => __('Products Block'),
		'render_template' => 'blocks/products.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('products', 'content'),
	));
	// register a Columns Block
	acf_register_block_type(array(
		'name' => 'columns-block',
		'title' => __('Columns Block'),
		'description' => __('Columns Block'),
		'render_template' => 'blocks/columns.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('columns', 'content'),
	));
	// register a Columns 2 Block
	acf_register_block_type(array(
		'name' => 'columns2-block',
		'title' => __('Columns 2 Block'),
		'description' => __('Columns 2 Block'),
		'render_template' => 'blocks/columns2.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('columns', 'content'),
	));
	// register a Columns 3 Block
	acf_register_block_type(array(
		'name' => 'columns3-block',
		'title' => __('Columns 3 Block'),
		'description' => __('Columns 3 Block'),
		'render_template' => 'blocks/columns3.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('columns', 'content'),
	));
	// register a Columns 4 Block
	acf_register_block_type(array(
		'name' => 'columns4-block',
		'title' => __('Columns 4 Block'),
		'description' => __('Columns 4 Block'),
		'render_template' => 'blocks/columns4.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('columns', 'content'),
	));
	// register a Columns 5 Block
	acf_register_block_type(array(
		'name' => 'columns5-block',
		'title' => __('Columns 5 Block'),
		'description' => __('Columns 5 Block'),
		'render_template' => 'blocks/columns5.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('columns', 'content'),
	));
	// register a Columns 6 Block
	acf_register_block_type(array(
		'name' => 'columns6-block',
		'title' => __('Columns 6 Block'),
		'description' => __('Columns 6 Block'),
		'render_template' => 'blocks/columns6.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('columns', 'content'),
	));
	// register a Compliant Block
	acf_register_block_type(array(
		'name' => 'compliant-block',
		'title' => __('Compliant Block'),
		'description' => __('Compliant Block'),
		'render_template' => 'blocks/compliant.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('compliant', 'content'),
	));
	// register a Promo Block
	acf_register_block_type(array(
		'name' => 'promo-block',
		'title' => __('Promo Block'),
		'description' => __('Promo Block'),
		'render_template' => 'blocks/promo.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('promo', 'content'),
	));
	// register a Promo 2 Block
	acf_register_block_type(array(
		'name' => 'promo2-block',
		'title' => __('Promo 2 Block'),
		'description' => __('Promo 2 Block'),
		'render_template' => 'blocks/promo2.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('promo', 'content'),
	));
	// register a Promo 3 Block
	acf_register_block_type(array(
		'name' => 'promo3-block',
		'title' => __('Promo 3 Block'),
		'description' => __('Promo 3 Block'),
		'render_template' => 'blocks/promo3.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('promo', 'content'),
	));
	// register a More About Block
	acf_register_block_type(array(
		'name' => 'more-about-block',
		'title' => __('More About Block'),
		'description' => __('More About Block'),
		'render_template' => 'blocks/more-about.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('about', 'content'),
	));
	// register a Subscribe Block
	acf_register_block_type(array(
		'name' => 'subs-block',
		'title' => __('Subscribe Block'),
		'description' => __('Subscribe Block'),
		'render_template' => 'blocks/subs.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'email-alt2',
		'keywords' => array('subscribe', 'content'),
	));
	// register a Subscribe 2 Block
	acf_register_block_type(array(
		'name' => 'subs2-block',
		'title' => __('Subscribe 2 Block'),
		'description' => __('Subscribe 2 Block'),
		'render_template' => 'blocks/subs2.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'email-alt2',
		'keywords' => array('subscribe', 'content'),
	));
	// register a Video Block
	acf_register_block_type(array(
		'name' => 'video-block',
		'title' => __('Video Block'),
		'description' => __('Video Block'),
		'render_template' => 'blocks/video.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'video-alt3',
		'keywords' => array('video', 'content'),
	));
	// register a Video 2 Block
	acf_register_block_type(array(
		'name' => 'video2-block',
		'title' => __('Video 2 Block'),
		'description' => __('Video 2 Block'),
		'render_template' => 'blocks/video2.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'video-alt3',
		'keywords' => array('video', 'content'),
	));
	// register a Video 3 Block
	acf_register_block_type(array(
		'name' => 'video3-block',
		'title' => __('Video 3 Block'),
		'description' => __('Video 3 Block'),
		'render_template' => 'blocks/video3.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'video-alt3',
		'keywords' => array('video', 'content'),
	));
	// register a Video 4 Block
	acf_register_block_type(array(
		'name' => 'video4-block',
		'title' => __('Video 4 Block'),
		'description' => __('Video 4 Block'),
		'render_template' => 'blocks/video4.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'video-alt3',
		'keywords' => array('video', 'content'),
	));
	// register a Solution Block
	acf_register_block_type(array(
		'name' => 'solution-block',
		'title' => __('Solution Block'),
		'description' => __('Solution Block'),
		'render_template' => 'blocks/solution.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('solution', 'content'),
	));
	// register a Integration Block
	acf_register_block_type(array(
		'name' => 'integration-block',
		'title' => __('Integration Block'),
		'description' => __('Integration Block'),
		'render_template' => 'blocks/integration.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('integration', 'content'),
	));
	// register a Why Hub Block
	acf_register_block_type(array(
		'name' => 'whyhub-block',
		'title' => __('Why Hub Block'),
		'description' => __('Why Hub Block'),
		'render_template' => 'blocks/whyhub.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('why hub', 'content'),
	));
	// register a Capabilities Block
	acf_register_block_type(array(
		'name' => 'capabilities-block',
		'title' => __('Capabilities Block'),
		'description' => __('Capabilities Block'),
		'render_template' => 'blocks/capabilities.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('capabilities', 'content'),
	));
	// register a Capabilities 2 Block
	acf_register_block_type(array(
		'name' => 'capabilities2-block',
		'title' => __('Capabilities 2 Block'),
		'description' => __('Capabilities 2 Block'),
		'render_template' => 'blocks/capabilities2.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('capabilities', 'content'),
	));
	// register a Collaboration Block
	acf_register_block_type(array(
		'name' => 'collaboration2-block',
		'title' => __('Collaboration 2 Block'),
		'description' => __('Collaboration 2 Block'),
		'render_template' => 'blocks/collaboration2.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'admin-site-alt2',
		'keywords' => array('collaboration', 'content'),
	));
	// register a Collaboration Slider Block
	acf_register_block_type(array(
		'name' => 'collaboration3-block',
		'title' => __('Collaboration Slider Block'),
		'description' => __('Collaboration Slider Block'),
		'render_template' => 'blocks/collaboration3.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('collaboration', 'slider', 'content'),
	));
	// register a What Can Hub Block
	acf_register_block_type(array(
		'name' => 'whatcanhub-block',
		'title' => __('What Can Hub Block'),
		'description' => __('What Can Hub Block'),
		'render_template' => 'blocks/whatcanhub.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('what can hub', 'content'),
	));
	// register a Self Hosting Block
	acf_register_block_type(array(
		'name' => 'hosting-block',
		'title' => __('Self Hosting Block'),
		'description' => __('Self Hosting Block'),
		'render_template' => 'blocks/hosting.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('hosting', 'content'),
	));
	// register a Self Hosting Block
	acf_register_block_type(array(
		'name' => 'hosting2-block',
		'title' => __('Self Hosting 2 Block'),
		'description' => __('Self Hosting 2 Block'),
		'render_template' => 'blocks/hosting2.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('hosting', 'content'),
	));
	// register a Extend Block
	acf_register_block_type(array(
		'name' => 'extend-block',
		'title' => __('Extend Block'),
		'description' => __('Extend Block'),
		'render_template' => 'blocks/extend.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('extend', 'content'),
	));
	// register a Benefits Block
	acf_register_block_type(array(
		'name' => 'benefits-block',
		'title' => __('Benefits Block'),
		'description' => __('Benefits Block'),
		'render_template' => 'blocks/benefits.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('benefits', 'content'),
	));
	// register a Benefits 2 Block
	acf_register_block_type(array(
		'name' => 'benefits2-block',
		'title' => __('Benefits 2 Block'),
		'description' => __('Benefits 2 Block'),
		'render_template' => 'blocks/benefits2.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('benefits', 'content'),
	));
	// register a Benefits 3 Block
	acf_register_block_type(array(
		'name' => 'benefits3-block',
		'title' => __('Benefits 3 Block'),
		'description' => __('Benefits 3 Block'),
		'render_template' => 'blocks/benefits3.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('benefits', 'content'),
	));
	// register a What Sets Apart Block
	acf_register_block_type(array(
		'name' => 'whatsets-block',
		'title' => __('What Sets Apart Block'),
		'description' => __('What Sets Apart Block'),
		'render_template' => 'blocks/whatsets.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('what sets apart', 'content'),
	));
	// register a Much More Block
	acf_register_block_type(array(
		'name' => 'muchmore-block',
		'title' => __('Much More Block'),
		'description' => __('Much More Block'),
		'render_template' => 'blocks/muchmore.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('much more', 'content'),
	));
	// register a Unique Column Block
	acf_register_block_type(array(
		'name' => 'ucol-block',
		'title' => __('Unique Column Block'),
		'description' => __('Unique Column Block'),
		'render_template' => 'blocks/ucol.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('unique column', 'content'),
	));
	// register a Compliance Slider Block
	acf_register_block_type(array(
		'name' => 'compliance-block',
		'title' => __('Compliance Slider Block'),
		'description' => __('Compliance Slider Block'),
		'render_template' => 'blocks/compliance.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('compliance', 'content'),
	));
	// register a Single Quote Block
	acf_register_block_type(array(
		'name' => 'squote-block',
		'title' => __('Single Quote Block'),
		'description' => __('Single Quote Block'),
		'render_template' => 'blocks/squote.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('quote', 'content'),
	));
	// register a Single Quote Block
	acf_register_block_type(array(
		'name' => 'squote2-block',
		'title' => __('Single Quote 2 Block'),
		'description' => __('Single Quote 2 Block'),
		'render_template' => 'blocks/squote2.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('quote', 'content'),
	));
	// register a Platform Block
	acf_register_block_type(array(
		'name' => 'platform-block',
		'title' => __('Platform Block'),
		'description' => __('Platform Block'),
		'render_template' => 'blocks/platform.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('platform', 'content'),
	));
	// register a Enterprise Block
	acf_register_block_type(array(
		'name' => 'enterprise-block',
		'title' => __('Enterprise Block'),
		'description' => __('Enterprise Block'),
		'render_template' => 'blocks/enterprise.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('enterprise', 'content'),
	));
	// register a Pricing Block
	acf_register_block_type(array(
		'name' => 'pricing-block',
		'title' => __('Pricing Block'),
		'description' => __('Pricing Block'),
		'render_template' => 'blocks/pricing.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('pricing', 'content'),
	));
	// register a Get Started Block
	acf_register_block_type(array(
		'name' => 'started-block',
		'title' => __('Get Started Block'),
		'description' => __('Get Started Block'),
		'render_template' => 'blocks/get-started.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('get started', 'content'),
	));
	// register a Single Text Block
	acf_register_block_type(array(
		'name' => 'singles-block',
		'title' => __('Single Text Block'),
		'description' => __('Single Text Block'),
		'render_template' => 'blocks/singles.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('text', 'content'),
	));
	// register a Content 1 Block
	acf_register_block_type(array(
		'name' => 'content1-block',
		'title' => __('Content 1 Block'),
		'description' => __('Content 1 Block'),
		'render_template' => 'blocks/content1.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('text', 'content'),
	));
	// register a Content 2 Block
	acf_register_block_type(array(
		'name' => 'content2-block',
		'title' => __('Content 2 Block'),
		'description' => __('Content 2 Block'),
		'render_template' => 'blocks/content2.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('text', 'content'),
	));
	// register a Documentation Block
	acf_register_block_type(array(
		'name' => 'documentation-block',
		'title' => __('Documentation Block'),
		'description' => __('Documentation Block'),
		'render_template' => 'blocks/documentation.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('documentation', 'content'),
	));
	// register a Get Involved Block
	acf_register_block_type(array(
		'name' => 'involved-block',
		'title' => __('Get Involved Block'),
		'description' => __('Get Involved Block'),
		'render_template' => 'blocks/involved.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('involved', 'content'),
	));
	// register a Content Cards Block
	acf_register_block_type(array(
		'name' => 'cards-block',
		'title' => __('Content Cards Block'),
		'description' => __('Content Cards Block'),
		'render_template' => 'blocks/cards.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('cards', 'content'),
	));
	// register a Contact Block
	acf_register_block_type(array(
		'name' => 'contact-block',
		'title' => __('Contact Block'),
		'description' => __('Contact Block'),
		'render_template' => 'blocks/contact.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('contact', 'content'),
	));
	// register a Contact Block
	acf_register_block_type(array(
		'name' => 'map-block',
		'title' => __('Map Block'),
		'description' => __('Map Block'),
		'render_template' => 'blocks/map.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('map', 'content'),
	));
	// register a Get Quote Form Block
	acf_register_block_type(array(
		'name' => 'quote-form-block',
		'title' => __('Get Quote Form Block'),
		'description' => __('Get Quote Form Block'),
		'render_template' => 'blocks/quote-form.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('quote', 'content'),
	));
	// register a Info Block
	acf_register_block_type(array(
		'name' => 'info-block',
		'title' => __('Info Block'),
		'description' => __('Info Block'),
		'render_template' => 'blocks/info.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('info', 'content'),
	));
	// register a Get Quote Form Block
	acf_register_block_type(array(
		'name' => 'trial-form-block',
		'title' => __('Trial Form Block'),
		'description' => __('Trial Form Block'),
		'render_template' => 'blocks/trial-form.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('trial', 'content'),
	));
	// register a Partner Form Block
	acf_register_block_type(array(
		'name' => 'partner-form-block',
		'title' => __('Partner Form Block'),
		'description' => __('Partner Form Block'),
		'render_template' => 'blocks/partner-form.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('partner', 'content'),
	));
	// register a Marketing Block
	acf_register_block_type(array(
		'name' => 'marketing-block',
		'title' => __('Marketing Block'),
		'description' => __('Marketing Block'),
		'render_template' => 'blocks/marketing.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('marketing', 'content'),
	));
	// register a Text Block
	acf_register_block_type(array(
		'name' => 'text1-block',
		'title' => __('Text Block'),
		'description' => __('Text Block'),
		'render_template' => 'blocks/text1.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('text', 'content'),
	));
	// register a Text 2 Block
	acf_register_block_type(array(
		'name' => 'text2-block',
		'title' => __('Text 2 Block'),
		'description' => __('Text 2 Block'),
		'render_template' => 'blocks/text2.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('text', 'content'),
	));
	// register a Order Form Block
	acf_register_block_type(array(
		'name' => 'order-form-block',
		'title' => __('Order Form Block'),
		'description' => __('Order Form Block'),
		'render_template' => 'blocks/order-form.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('order', 'content'),
	));
	// register a Boxes Block
	acf_register_block_type(array(
		'name' => 'boxes-block',
		'title' => __('Boxes Block'),
		'description' => __('Boxes Block'),
		'render_template' => 'blocks/boxes.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('boxes', 'content'),
	));
	// register a Ionos Form Block
	acf_register_block_type(array(
		'name' => 'ionos-block',
		'title' => __('Ionos Form Block'),
		'description' => __('Ionos Form Block'),
		'render_template' => 'blocks/ionos.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('ionos', 'content'),
	));
	// register a Ionos Form Block
	acf_register_block_type(array(
		'name' => 'ionos-form-block',
		'title' => __('Ionos Form Block New'),
		'description' => __('Ionos Form Block Ninja Forms'),
		'render_template' => 'blocks/ionos-form.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('ionos', 'content'),
	));
	// register a Sign Up Form Block
	acf_register_block_type(array(
		'name' => 'signup-block',
		'title' => __('Sign Up Form Block'),
		'description' => __('Sign Up Form Block'),
		'render_template' => 'blocks/signup.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('signup', 'content'),
	));
	// register a Providers Block
	acf_register_block_type(array(
		'name' => 'providers-block',
		'title' => __('Providers Block'),
		'description' => __('Providers Block'),
		'render_template' => 'blocks/providers.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('providers', 'content'),
	));
	// register a Industries Content Block
	acf_register_block_type(array(
		'name' => 'industries-block',
		'title' => __('Industries Content Block'),
		'description' => __('Industries Content Block'),
		'render_template' => 'blocks/industries.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('industries', 'content'),
	));
	// register a Analysis Block
	acf_register_block_type(array(
		'name' => 'analysis-block',
		'title' => __('Analysis Block'),
		'description' => __('Analysis Block'),
		'render_template' => 'blocks/analysis.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('analysis', 'content'),
	));
	// register a Case Study Listing Block
	acf_register_block_type(array(
		'name' => 'case-study-block',
		'title' => __('Case Study Listing Block'),
		'description' => __('Case Study Listing Block'),
		'render_template' => 'blocks/case-study.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('case study', 'content'),
	));
	// register a Whitepaper Listing Block
	acf_register_block_type(array(
		'name' => 'whitepaper-block',
		'title' => __('Whitepaper Listing Block'),
		'description' => __('Whitepaper Listing Block'),
		'render_template' => 'blocks/whitepaper.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('whitepaper', 'content'),
	));
	// register a Data Sheet Listing Block
	acf_register_block_type(array(
		'name' => 'data-sheet-block',
		'title' => __('Data Sheet Listing Block'),
		'description' => __('Data Sheet Listing Block'),
		'render_template' => 'blocks/data-sheet.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('data sheet', 'content'),
	));
	// register a Team Listing Block
	acf_register_block_type(array(
		'name' => 'team-list-block',
		'title' => __('Team Listing Block'),
		'description' => __('Team Listing Block'),
		'render_template' => 'blocks/team-list.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('team', 'content'),
	));
	// register a Blog Listing Block
	acf_register_block_type(array(
		'name' => 'blog-list-block',
		'title' => __('Blog Listing Block'),
		'description' => __('Blog Listing Block'),
		'render_template' => 'blocks/blog-list.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('blog', 'content'),
	));
	// register a FAQ Block
	acf_register_block_type(array(
		'name' => 'faq-block',
		'title' => __('FAQ Block'),
		'description' => __('FAQ Block'),
		'render_template' => 'blocks/faq.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('faq', 'content'),
	));
	// register a Jobs Block
	acf_register_block_type(array(
		'name' => 'jobs-block',
		'title' => __('Jobs Block'),
		'description' => __('Jobs Block'),
		'render_template' => 'blocks/jobs.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('jobs', 'content'),
	));
	// register a Single Post Content Block
	acf_register_block_type(array(
		'name' => 'single-block',
		'title' => __('Single Post Content Block'),
		'description' => __('Single Post Content Block'),
		'render_template' => 'blocks/single.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('single', 'content'),
	));
	// register a Whitepaper Blog Posts Block
	acf_register_block_type(array(
		'name' => 'white-post-block',
		'title' => __('Whitepaper Blog Posts Block'),
		'description' => __('Whitepaper Blog Posts Block'),
		'render_template' => 'blocks/whitepaper-list.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('whitepaper', 'content'),
	));
	// register a Image Columns Block
	acf_register_block_type(array(
		'name' => 'img-cols-block',
		'title' => __('Image Columns Block'),
		'description' => __('Image Columns Block'),
		'render_template' => 'blocks/img-cols.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('image', 'content'),
	));
	// register a Get Started 2 Block
	acf_register_block_type(array(
		'name' => 'started2-block',
		'title' => __('Get Started 2 Block'),
		'description' => __('Get Started 2 Block'),
		'render_template' => 'blocks/get-started2.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('get started', 'content'),
	));
	// register a Events Block
	acf_register_block_type(array(
		'name' => 'events-block',
		'title' => __('Events Block'),
		'description' => __('Events Block'),
		'render_template' => 'blocks/events.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('events', 'content'),
	));
	// register a Counter Block
	acf_register_block_type(array(
		'name' => 'counter-block',
		'title' => __('Counter Block'),
		'description' => __('Counter Block'),
		'render_template' => 'blocks/counter.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('counter', 'content'),
	));
	// register a Pricing Tabs Block
	acf_register_block_type(array(
		'name' => 'price-tab-block',
		'title' => __('Pricing Tabs Block'),
		'description' => __('Pricing Tabs Block'),
		'render_template' => 'blocks/price-tab.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('pricing', 'content'),
	));
	// register a Footnote Block
	acf_register_block_type(array(
		'name' => 'footnote-block',
		'title' => __('Footnote Block'),
		'description' => __('Footnote Block'),
		'render_template' => 'blocks/footnote.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('footnote', 'content'),
	));
	// register a Testimonial Block
	acf_register_block_type(array(
		'name' => 'testimonial-block',
		'title' => __('Testimonial Block'),
		'description' => __('Testimonial Block'),
		'render_template' => 'blocks/testimonial.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('testimonial', 'content'),
	));
	// register a Partners Block
	acf_register_block_type(array(
		'name' => 'partners-block',
		'title' => __('Partners Block'),
		'description' => __('Partners Block'),
		'render_template' => 'blocks/partners.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('partners', 'content'),
	));
	// register a Podcast Block
	acf_register_block_type(array(
		'name' => 'podcast-block',
		'title' => __('Podcast Block'),
		'description' => __('Podcast Block'),
		'render_template' => 'blocks/podcast.php',
		'category' => 'theme-blocks',
		'mode' => 'edit',
		'icon' => 'welcome-write-blog',
		'keywords' => array('podcast', 'content'),
	));
}

// Check if function exists and hook into setup.

if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'register_acf_block_types');
}


add_filter('wp_nav_menu_objects', 'my_wp_nav_menu_objects', 10, 2);

function my_wp_nav_menu_objects($items, $args) {
	// loop
	foreach ($items as &$item) {
		// vars
		$icon = get_field('icon', $item);
		$sub = get_field('subtext', $item);
		// add icon
		if ($icon && $sub) {
			$item->title = '<img class="navcon" src="' . $icon . '" alt=""/><span>' . $item->title . '<small>' . $sub . '</small></span>';
		} elseif ($icon) {
			$item->title = '<img class="navcon" src="' . $icon . '" alt=""/><span>' . $item->title . '</span>';
		} elseif ($sub) {
			$item->title = '<span>' . $item->title . '<small>' . $sub . '</small></span>';
		}
	}
	// return
	return $items;
}

add_action('wp_footer', 'wpml_floating_language_switcher');

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
	add_filter('tiny_mce_plugins', 'disable_emojicons_tinymce');
}
add_action('init', 'disable_wp_emojicons');
remove_action('wp_head', 'wp_resource_hints', 2);
