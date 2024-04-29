<?php
function nc_blog_articles_load_more() {
<<<<<<< Updated upstream
=======
	$paged = (isset($_POST['paged'])) ? $_POST['paged'] : 1;
	$default_posts_per_page = get_option( 'posts_per_page' );

	if (class_exists('WPML_Display_As_Translated_Tax_Query')) {
		global $sitepress, $wpml_term_translations;
		$wpml_display_as_translated_tax = new WPML_Display_As_Translated_Tax_Query( $sitepress, $wpml_term_translations );
		$wpml_display_as_translated_tax->add_hooks();
	}

	$ajaxposts = new WP_Query([
		'post_type' => array('post', 'event', 'podcast'),
		'posts_per_page' => $default_posts_per_page,
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
>>>>>>> Stashed changes
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