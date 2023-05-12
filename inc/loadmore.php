<?php
function nc_blog_articles_load_more() {
	$paged = (isset($_POST['paged'])) ? $_POST['paged'] : 1;

	$ajaxposts = new WP_Query([
		'post_type' => array('post'),
		'posts_per_page' => 9,
		'post_status' => array('publish'),
		'orderby' => 'date',
		'tag__not_in' => array(269),
		'order' => 'DESC',
		'paged' => $paged,
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
		$response = '';
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
add_action('wp_ajax_nc_term_load_more', 'nc_blog_terms_load_more');
add_action('wp_ajax_nopriv_nc_term_load_more', 'nc_blog_terms_load_more');
