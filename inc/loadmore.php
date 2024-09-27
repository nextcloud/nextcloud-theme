<?php
//testing purposes
function nc_blog_articles_load_more_test(){
	$paged = (isset($_POST['paged'])) ? $_POST['paged'] : 1;
	$default_posts_per_page = get_option( 'posts_per_page' );

	if (class_exists('WPML_Display_As_Translated_Tax_Query')) {
		global $sitepress, $wpml_term_translations;
		$wpml_display_as_translated_tax = new WPML_Display_As_Translated_Tax_Query( $sitepress, $wpml_term_translations );
		$wpml_display_as_translated_tax->add_hooks();
	}
	

	//get all sticky events and exclude them in the main query 
	/*
	$sticky_events = array();
			$args_events = array(
				'post_type' => array('event'),
				'post_status' => 'publish',
				'meta_query' => array(
					array(
						'key' => 'sticky_event',
						'value' => true,
						'compare' => 'LIKE'
					)
				)
	);
	$events__query = new WP_Query($args_events);
	if ($events__query->have_posts()) {
		while ($events__query->have_posts()) {
			$events__query->the_post();
			$sticky_events[] = get_the_ID();
		}
		wp_reset_postdata();
	}
	*/


	$date_format = get_option( 'date_format' ); // e.g. "F j, Y"

	$ajaxposts = new WP_Query([
		'post_type' => array('post', /*'event', 'podcast'*/),
		'posts_per_page' => $default_posts_per_page,
		'post_status' => array('publish'),
		'orderby' => 'date',
		//'tag__not_in' => array(269),
		'order' => 'DESC',
		'paged' => $paged,
		//'category__not_in' => array(226), //exclude Private category
		'ignore_sticky_posts' => 1,
		//'post__not_in' => $sticky_events // ignore sticky events (custom field)
	]);

	$response = 'Page loaded: '.$paged;
	//$response .= 'query loaded: <pre>'.$ajaxposts->request.'</pre>';

	if ($ajaxposts->have_posts()) {
		while ($ajaxposts->have_posts()) : $ajaxposts->the_post();
		$post_id = get_the_ID();
		
		//get date
		$date = (string)get_the_date($date_format);
		if ( 'event' == get_post_type() ) {
			$cat = wp_get_object_terms( $post_id, 'event_categories', array() );
			$event_start_datetime = get_field('event_start_date_and_time', $post_id, false);
			if($event_start_datetime) {
				$date = date_i18n($date_format, strtotime($event_start_datetime));
			}
		}

		$response .= "<a href='".get_permalink()."'>".get_the_title()."</a> - ".$date;
		$my_post_language_details = apply_filters( 'wpml_post_language_details', NULL, $post_id);
		$response .= " - Lang: ".$my_post_language_details['language_code'];
		$response .= "<br><br>"; // testing purposes
		//$response .= get_template_part('inc/blog_loop_single');
		endwhile;
		wp_reset_postdata();
	} else {
		$response = '';
	}

	die($response);
}
add_action('wp_ajax_nc_load_more_test', 'nc_blog_articles_load_more_test');
add_action('wp_ajax_nopriv_nc_load_more_test', 'nc_blog_articles_load_more_test');








function nc_blog_articles_load_more() {
	$paged = (isset($_POST['paged'])) ? $_POST['paged'] : 1;
	$default_posts_per_page = get_option( 'posts_per_page' );

	if (class_exists('WPML_Display_As_Translated_Tax_Query')) {
		global $sitepress, $wpml_term_translations;
		$wpml_display_as_translated_tax = new WPML_Display_As_Translated_Tax_Query( $sitepress, $wpml_term_translations );
		$wpml_display_as_translated_tax->add_hooks();
	}

	//get all sticky events and exclude them in the main query 
	$sticky_events = array();
			$args_events = array(
				'post_type' => array('event'),
				'post_status' => 'publish',
				'meta_query' => array(
					array(
						'key' => 'sticky_event',
						'value' => true,
						'compare' => 'LIKE'
					)
				)
	);
	$events__query = new WP_Query($args_events);
	if ($events__query->have_posts()) {
		while ($events__query->have_posts()) {
			$events__query->the_post();
			$sticky_events[] = get_the_ID();
		}
	}

	$ajaxposts = new WP_Query([
		'post_type' => array('post', 'event', 'podcast'),
		'posts_per_page' => $default_posts_per_page,
		'post_status' => array('publish'),
		'orderby' => 'date',
		'tag__not_in' => array(269),
		'order' => 'DESC',
		'paged' => $paged,
		'category__not_in' => array(226), //exclude Private category
		'ignore_sticky_posts' => 1,
		'post__not_in' => $sticky_events // ignore sticky events (custom field)
	]);
  
	$response = '';
  
	if ($ajaxposts->have_posts()) {
		while ($ajaxposts->have_posts()) : $ajaxposts->the_post();
		//$response .= "<a href='".get_permalink()."'>".get_the_title()."</a><br><br>"; // testing purposes
		$response .= get_template_part('inc/blog_loop_single');
		endwhile;
	} else {
		$response = '';
	}

	wp_reset_postdata();
	die($response);
}
add_action('wp_ajax_nc_load_more', 'nc_blog_articles_load_more');
add_action('wp_ajax_nopriv_nc_load_more', 'nc_blog_articles_load_more');



function nc_whitepapers_load_more() {
	$paged = (isset($_POST['paged'])) ? $_POST['paged'] : 1;

	$ajaxposts = new WP_Query([
		//'post_type' => $post_type,
		'post_type' => strip_tags($_POST['post_type']),
		'posts_per_page' => strip_tags($_POST['limit']),
		'post_status' => array('publish'),
		'orderby' => 'date',
		'tag__not_in' => array(269),
		'order' => 'DESC',
		'paged' => $paged
	]);
  
	$response = '';
  
	if ($ajaxposts->have_posts()) {
		while ($ajaxposts->have_posts()) : $ajaxposts->the_post();
		$response .= get_template_part('inc/whitepaper_loop_single');
		endwhile;
	} else {
		$response = '';
	}

	wp_reset_postdata();
	die($response);
}
add_action('wp_ajax_nc_whitepapers_load_more', 'nc_whitepapers_load_more');
add_action('wp_ajax_nopriv_nc_whitepapers_load_more', 'nc_whitepapers_load_more');



function nc_whitepaper_posts_load_more() {

	if (class_exists('WPML_Display_As_Translated_Tax_Query')) {
		global $sitepress, $wpml_term_translations;
		$wpml_display_as_translated_tax = new WPML_Display_As_Translated_Tax_Query( $sitepress, $wpml_term_translations );
		$wpml_display_as_translated_tax->add_hooks();
	}

	$paged = (isset($_POST['paged'])) ? $_POST['paged'] : 1;
	
	$whitepaper_taxonomy_id = 9; // whitepaper EN
	//$whitepaper_taxonomy_id = apply_filters( 'wpml_object_id', 9, 'category', TRUE  );

	$ajaxposts = new WP_Query([
		'post_type' => array('post'),
		//'suppress_filters' => true,
		
		'tax_query' => array(
			array(
				'taxonomy' => 'category',
				'field'    => 'term_id',
				'terms'    => $whitepaper_taxonomy_id
			)
		),
		
		'post_status' => 'publish',
		'posts_per_page' => $_POST['limit'],
		'orderby' => 'date',
		'tag__not_in' => array(269),
		'order' => 'DESC',
		'paged' => $paged
	]);


	$count = $ajaxposts->found_posts;
	//echo "Count: ".$count;
  
	$response = '';
  
	if ($ajaxposts->have_posts()) {
		while ($ajaxposts->have_posts()) : $ajaxposts->the_post();
		$response .= get_template_part('inc/whitepaper_posts_loop_single');
		endwhile;
	} else {
		//$response = '<div class="results hidden">'.print_r($ajaxposts->query_vars).'</div>';
	}

	wp_reset_postdata();
	die($response);
}
add_action('wp_ajax_nc_whitepaper_posts_load_more','nc_whitepaper_posts_load_more');
add_action('wp_ajax_nopriv_nc_whitepaper_posts_load_more','nc_whitepaper_posts_load_more');



function nc_blog_search_load_more() {
	$paged = (isset($_POST['paged'])) ? $_POST['paged'] : 1;

	if(isset($_POST['post_type'])) {
		$post_type = $_POST['post_type'];
	} else {
		$post_type = array('post', 'event', 'podcast');
	}

	$ajaxposts = new WP_Query([
		'post_type' => $post_type,
		'posts_per_page' => 9,
		's' => get_search_query(), //$_GET['s'],
		'post_status' => array('publish'),
		'tag__not_in' => array(269),
		'orderby' => 'date',
		'order' => 'DESC',
		'paged' => $paged,
		'category__not_in' => array(226) //exclude Private category
	]);
  
	$response = '';
	if ($ajaxposts->have_posts()) {
		while ($ajaxposts->have_posts()) : $ajaxposts->the_post();
		$response .= get_template_part('inc/blog_loop_single');
		endwhile;
	} else {
		$response = '';
	}

	wp_reset_postdata();
	die($response);
}
add_action('wp_ajax_nc_search_load_more', 'nc_blog_search_load_more');
add_action('wp_ajax_nopriv_nc_search_load_more', 'nc_blog_search_load_more');




//nc_cat_load_more action for the categories
function nc_blog_articles_category_load_more() {
	$ajaxposts = new WP_Query([
		'post_type' => 'post',
		'cat' => $_POST['category'],
		'post_status' => 'publish',
		'orderby' => 'date',
		'order' => 'DESC',
		'tag__not_in' => array(269),
		//'paged' => $_POST['paged'],
		'paged' => max( 1, $_POST['paged'] ),
		'category__not_in'=> array(225, 226) //exclude Private category
	]);
  
	$response = '';
  
	if ($ajaxposts->have_posts()) {
		while ($ajaxposts->have_posts()) : $ajaxposts->the_post();
		//$response .= get_template_part('parts/card', 'publication');
		//$response .= get_the_title();
		$response .= get_template_part('inc/blog_loop_single');

		endwhile;
	} else {
		$response = 'No other posts found.';
	}
  
	echo $response;
	exit;
}
add_action('wp_ajax_nc_cat_load_more', 'nc_blog_articles_category_load_more');
add_action('wp_ajax_nopriv_nc_cat_load_more', 'nc_blog_articles_category_load_more');



function nc_blog_terms_load_more() {
	$ajaxposts = new WP_Query([
		'post_type' => $_POST['post_type'],

		'tax_query' => array(
			array(
				'taxonomy' => 'event_categories',
				'field'    => 'term_id',
				'terms'    => $_POST['category']
			),
		),

		'tag__not_in' => array(269),
		'post_status' => 'publish',
		'orderby' => 'date',
		'order' => 'DESC',
		'paged' => max( 1, $_POST['paged'] )
	]);
  
	$response = '';
  
	if ($ajaxposts->have_posts()) {
		while ($ajaxposts->have_posts()) : $ajaxposts->the_post();
		$response .= get_template_part('inc/blog_loop_single');
		endwhile;
	} else {
		$response = '';
	}
  
	echo $response;
	exit;
}
add_action('wp_ajax_nc_term_load_more', 'nc_blog_terms_load_more');
add_action('wp_ajax_nopriv_nc_term_load_more', 'nc_blog_terms_load_more');


//action for loading past webinars
function nc_past_webinars_load_more() {
	$current_date_time = date('Y-m-d H:i:s', time());

	$ajaxposts = new WP_Query([
		'post_type' => 'event',

		//load only webinars
		'tax_query' => array(
			array(
				'taxonomy' => 'event_categories',
				'field'    => 'slug',
				'terms'    => 'webinars',
			),
		),
		
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key' => 'event_start_date_and_time',
				'value'   => $current_date_time,
				'compare' => '<',
				'type'	=> 'DATETIME'
			),
			array(
				'key'     => 'download_available',
				'value'	  => '',
				'compare' => '!=',
			),
		),
		

		'tag__not_in' => array(269),
		'post_status' => 'publish',
		'orderby' => 'meta_value',
		'order' => 'DESC',
		'paged' => max( 1, $_POST['paged'] )
	]);
  

	$response = '';
	if ($ajaxposts->have_posts()) {
		while ($ajaxposts->have_posts()) : $ajaxposts->the_post();
			$response .= get_template_part('inc/blog_loop_single');
		endwhile;
	} else {
		$response = '';
	}
  
	echo $response;
	exit;
}
add_action('wp_ajax_nc_past_webinars_load_more', 'nc_past_webinars_load_more');
add_action('wp_ajax_nopriv_nc_past_webinars_load_more', 'nc_past_webinars_load_more');