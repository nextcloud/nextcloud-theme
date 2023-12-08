<?php
function nc_blog_articles_load_more() {
	$paged = (isset($_POST['paged'])) ? $_POST['paged'] : 1;

	if(isset($_POST['post_type'])) {
		$post_type = $_POST['post_type'];
	} else {
		$post_type = 'post';
	}

	$ajaxposts = new WP_Query([
		'post_type' => array('post', 'event', 'podcast'),
		'posts_per_page' => 9,
		'post_status' => array('publish'),
		'orderby' => 'date',
		'tag__not_in' => array(269),
		'order' => 'DESC',
		'paged' => $paged,
		'ignore_sticky_posts' => 1,
		//'post__not_in' => get_option( 'sticky_posts' ), // ignore sticky posts
		'category__not_in' => array(226) //exclude Private category
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