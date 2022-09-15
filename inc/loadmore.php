<?php
function nc_blog_articles_load_more() {
	$ajaxposts = new WP_Query([
		'post_type' => 'post',
		//'posts_per_page' => 6,
		'post_status' => 'publish',
		'orderby' => 'date',
		'order' => 'DESC',
		'paged' => $_POST['paged'],
	]);
  
	$response = '';
  
	if ($ajaxposts->have_posts()) {
		while ($ajaxposts->have_posts()) : $ajaxposts->the_post();
		//$response .= get_template_part('parts/card', 'publication');
		//$response .= get_the_title();
		$response .= get_template_part('inc/blog_loop_single');

		endwhile;
	} else {
		$response = '';
	}
  
	echo $response;
	exit;
}
add_action('wp_ajax_nc_load_more', 'nc_blog_articles_load_more');
add_action('wp_ajax_nopriv_nc_load_more', 'nc_blog_articles_load_more');



//nc_cat_load_more action for the categories
function nc_blog_articles_category_load_more() {
	$ajaxposts = new WP_Query([
		'post_type' => 'post',
		'cat' => $_POST['category'],
		'post_status' => 'publish',
		'orderby' => 'date',
		'order' => 'DESC',
		'paged' => $_POST['paged'],
	]);
  
	$response = '';
  
	if ($ajaxposts->have_posts()) {
		while ($ajaxposts->have_posts()) : $ajaxposts->the_post();
		//$response .= get_template_part('parts/card', 'publication');
		//$response .= get_the_title();
		$response .= get_template_part('inc/blog_loop_single');

		endwhile;
	} else {
		$response = '';
	}
  
	echo $response;
	exit;
}
add_action('wp_ajax_nc_cat_load_more', 'nc_blog_articles_category_load_more');
add_action('wp_ajax_nopriv_nc_cat_load_more', 'nc_blog_articles_category_load_more');