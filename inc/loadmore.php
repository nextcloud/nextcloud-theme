<?php

//testing purposes
function nc_blog_articles_load_more_test() {
	$paged = (isset($_POST['paged'])) ? $_POST['paged'] : 1;
	$default_posts_per_page = get_option('posts_per_page');

	if (class_exists('WPML_Display_As_Translated_Tax_Query')) {
		global $sitepress, $wpml_term_translations;
		$wpml_display_as_translated_tax = new WPML_Display_As_Translated_Tax_Query($sitepress, $wpml_term_translations);
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

	$date_format = get_option('date_format'); // e.g. "F j, Y"

	$ajaxposts = new WP_Query([
		'post_type' => ['post', /*'event', 'podcast'*/],
		'posts_per_page' => $default_posts_per_page,
		'post_status' => ['publish'],
		'orderby' => 'date',
		//'tag__not_in' => array(269),
		'order' => 'DESC',
		'paged' => $paged,
		//'category__not_in' => array(226), //exclude Private category
		'ignore_sticky_posts' => 1,
		//'post__not_in' => $sticky_events // ignore sticky events (custom field)
	]);

	$response = 'Page loaded: ' . $paged;
	//$response .= 'query loaded: <pre>'.$ajaxposts->request.'</pre>';

	if ($ajaxposts->have_posts()) {
		while ($ajaxposts->have_posts()) : $ajaxposts->the_post();
			$post_id = get_the_ID();

			//get date
			$date = (string)get_the_date($date_format);
			if (get_post_type() == 'event') {
				$cat = wp_get_object_terms($post_id, 'event_categories', []);
				$event_start_datetime = get_field('event_start_date_and_time', $post_id, false);
				if ($event_start_datetime) {
					$date = date_i18n($date_format, strtotime($event_start_datetime));
				}
			}

			$response .= "<a href='" . get_permalink() . "'>" . get_the_title() . '</a> - ' . $date;
			$my_post_language_details = apply_filters('wpml_post_language_details', null, $post_id);
			$response .= ' - Lang: ' . $my_post_language_details['language_code'];
			$response .= '<br><br>'; // testing purposes
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

//load more items on the blog page
function nc_blog_articles_load_more() {
	$paged = (isset($_POST['paged'])) ? $_POST['paged'] : 1;
	$default_posts_per_page = get_option('posts_per_page');

	if (class_exists('WPML_Display_As_Translated_Tax_Query')) {
		global $sitepress, $wpml_term_translations;
		$wpml_display_as_translated_tax = new WPML_Display_As_Translated_Tax_Query($sitepress, $wpml_term_translations);
		$wpml_display_as_translated_tax->add_hooks();
	}

	$ajaxposts = new WP_Query([
		'post_type' => ['post', 'podcast'],
		'posts_per_page' => $default_posts_per_page,
		'post_status' => ['publish'],
		'orderby' => 'date',
		'tag__not_in' => [269],
		'order' => 'DESC',
		'paged' => $paged,
		'category__not_in' => [226], //exclude Private category
		'ignore_sticky_posts' => 1,
		//'post__not_in' => $sticky_events // ignore sticky events (custom field)
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

	/*
	if (class_exists('WPML_Display_As_Translated_Tax_Query')) {
		global $sitepress, $wpml_term_translations;
		$wpml_display_as_translated_tax = new WPML_Display_As_Translated_Tax_Query( $sitepress, $wpml_term_translations );
		$wpml_display_as_translated_tax->add_hooks();
	}
	*/

	$ajaxposts = new WP_Query([
		//'suppress_filters' => false,
		'post_type' => strip_tags($_POST['post_type']),
		'posts_per_page' => strip_tags($_POST['limit']),
		'post_status' => ['publish'],
		'orderby' => 'date',
		'tag__not_in' => [269],
		'order' => 'DESC',
		'paged' => $paged
	]);

	$response = '';

	if ($ajaxposts->have_posts()) {
		while ($ajaxposts->have_posts()) : $ajaxposts->the_post();
			$response .= get_template_part('inc/whitepaper_loop_single');
		endwhile;
	} else {
		$response = 'Posts not found.';
	}

	wp_reset_postdata();
	die($response);
}
add_action('wp_ajax_nc_whitepapers_load_more', 'nc_whitepapers_load_more');
add_action('wp_ajax_nopriv_nc_whitepapers_load_more', 'nc_whitepapers_load_more');

//Whitepaper posts load more
function nc_whitepaper_posts_load_more() {
	if (class_exists('WPML_Display_As_Translated_Tax_Query')) {
		global $sitepress, $wpml_term_translations;
		$wpml_display_as_translated_tax = new WPML_Display_As_Translated_Tax_Query($sitepress, $wpml_term_translations);
		$wpml_display_as_translated_tax->add_hooks();
	}
	$paged = (isset($_POST['paged'])) ? $_POST['paged'] : 1;

	$whitepaper_taxonomy_id = apply_filters('wpml_object_id', 9, 'category', true);
	$success_story_taxonomy_id = apply_filters('wpml_object_id', 418, 'category', true);
	//$whitepaper_taxonomy_id = 9; // whitepaper EN
	//$whitepaper_taxonomy_id = apply_filters( 'wpml_object_id', 9, 'category', TRUE  );

	$ajaxposts = new WP_Query([
		'post_type' => ['post'],
		'tax_query' => [
			[
				'taxonomy' => 'category',
				'field' => 'term_id',
				'terms' => [$whitepaper_taxonomy_id, $success_story_taxonomy_id]
			]
		],
		'post_status' => 'publish',
		//'posts_per_page' => $_POST['limit'],
		'posts_per_page' => -1,
		'orderby' => 'date',
		'tag__not_in' => [269],
		'order' => 'DESC',
		//'paged' => $paged
	]);
	$all_posts = $ajaxposts->posts;

	if ($_POST['podcasts']) {
		//add podcasts

		//get also the podcasts, tagged with Case story tag
		$q_podcasts = new WP_Query([
			'post_type' => ['podcast'],
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'tax_query' => [[
				'taxonomy' => 'post_tag',
				'field' => 'term_id',
				'terms' => [466],
			]],
			'orderby' => 'date',
			'order' => 'DESC'
		]);

		// Merge + sort by date DESC
		//$all_posts = $q_podcasts->posts;
		$all_posts = array_merge($ajaxposts->posts, $q_podcasts->posts);
		usort($all_posts, function ($a, $b) {
			return strcmp($b->post_date, $a->post_date);
		});

	}

	$count = count($all_posts);
	$offset = ($paged - 1) * $_POST['limit'];
	$items = array_slice($all_posts, $offset, $_POST['limit']);
	$response = '';

	if (!empty($items)) {
		foreach ($items as $post_obj) {
			if ($post_obj) {
				$old_post = $GLOBALS['post'] ?? null;
				$GLOBALS['post'] = $post_obj;   // switch context
				setup_postdata($post_obj);
				$response .= get_template_part('inc/blog_loop_single');
				//$response .= ' - ';
				wp_reset_postdata();
				$GLOBALS['post'] = $old_post;// restore
			}
		}
	}
	wp_reset_postdata();

	die($response);
}
add_action('wp_ajax_nc_whitepaper_posts_load_more', 'nc_whitepaper_posts_load_more');
add_action('wp_ajax_nopriv_nc_whitepaper_posts_load_more', 'nc_whitepaper_posts_load_more');

function nc_blog_search_load_more() {
	$paged = (isset($_POST['paged'])) ? $_POST['paged'] : 1;

	if (isset($_POST['post_type'])) {
		$post_type = $_POST['post_type'];
	} else {
		$post_type = ['post', 'event', 'page'];
	}
	$search_query = $_POST['search_query'];

	$ajaxposts = new WP_Query([
		'post_type' => $post_type,
		'posts_per_page' => get_option('posts_per_page'),
		's' => $search_query,
		'post_status' => ['publish'],
		'tag__not_in' => [269],
		'orderby' => 'date',
		'order' => 'DESC',
		'paged' => $paged,
		'category__not_in' => [226] //exclude Private category
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
		'tag__not_in' => [269],
		//'paged' => $_POST['paged'],
		'paged' => max(1, $_POST['paged']),
		'category__not_in' => [225, 226] //exclude Private category
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

		'tax_query' => [
			[
				'taxonomy' => 'event_categories',
				'field' => 'term_id',
				'terms' => $_POST['category']
			],
		],

		'tag__not_in' => [269],
		'post_status' => 'publish',
		'orderby' => 'date',
		'order' => 'DESC',
		'paged' => max(1, $_POST['paged'])
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
	//recordings
	$current_date_time = date('Y-m-d H:i:s', time());
	$default_posts_per_page = get_option('posts_per_page');

	$include_all_lang = false;
	if (isset($_POST['include_all_lang'])) {
		$include_all_lang = $_POST['include_all_lang'];
	}

	$args_base = [
		'post_type' => 'on-demand-webinar',
		'meta_query' => [
			'relation' => 'AND',
			[
				'key' => 'event_start_date_and_time',
				'value' => $current_date_time,
				'compare' => '<',
				'type' => 'DATETIME'
			],
			[
				'key' => 'download_available',
				'value' => '',
				'compare' => '!=',
			],
		],

		'tag__not_in' => [269], // exclude unlisted tag
		'category__not_in' => [225, 226], //exclude Private category
		'post_status' => 'publish',
		'orderby' => 'meta_value',
		'order' => 'DESC',
		//'paged' => max( 1, $_POST['paged'] )
	];

	if ($include_all_lang) {
		//echo "Paged: ".$_POST['paged']." ";
		//echo "Posts per page: ".$default_posts_per_page." ";

		$args_base['suppress_filters'] = true;
		//$args_base['posts_per_page'] = -1;
		//$args_base['posts_per_page'] = $default_posts_per_page;
		$args_base['order'] = 'ASC';

		if ($_POST['recordings_ids']) {
			$recordings_ids = explode(',', $_POST['recordings_ids']);
			//array_slice(array, offset, length)
			$offset = ($_POST['paged'] - 1) * $default_posts_per_page;
			//echo "Offset: ".$offset;
			$recordings_ids_sliced = array_slice($recordings_ids, $offset, $default_posts_per_page);
			$args_base['post__in'] = $recordings_ids_sliced;
			$args_base['orderby'] = 'post__in';
			//print_r($recordings_ids_sliced);
		}
	}

	$ajaxposts = new WP_Query($args_base);
	$response = '';
	if ($ajaxposts->have_posts()) {
		while ($ajaxposts->have_posts()) : $ajaxposts->the_post();
			$response .= get_template_part('inc/webinar_loop_single');
		endwhile;
	} else {
		$response = '';
	}

	echo $response;
	exit;
}
add_action('wp_ajax_nc_past_webinars_load_more', 'nc_past_webinars_load_more');
add_action('wp_ajax_nopriv_nc_past_webinars_load_more', 'nc_past_webinars_load_more');

//action for loading past webinars
function nc_webinar_recordings_load_tagged() {
	$current_date_time = date('Y-m-d H:i:s', time());
	$default_posts_per_page = get_option('posts_per_page');

	$include_all_lang = false;
	if (isset($_POST['include_all_lang'])) {
		$include_all_lang = $_POST['include_all_lang'];
	}
	$tag_id = $_POST['tag_id'];

	$args_base = [
		'post_type' => 'on-demand-webinar',
		'meta_query' => [
			'relation' => 'AND',
			[
				'key' => 'event_start_date_and_time',
				'value' => $current_date_time,
				'compare' => '<',
				'type' => 'DATETIME'
			],
			[
				'key' => 'download_available',
				'value' => '',
				'compare' => '!=',
			],
		],

		'tag__in' => $tag_id, // exclude unlisted tag
		'category__not_in' => [225, 226], //exclude Private category
		'post_status' => 'publish',
		'orderby' => 'meta_value',
		'order' => 'DESC',
		//'paged' => max( 1, $_POST['paged'] )
	];

	if ($include_all_lang) {
		$args_base['suppress_filters'] = true;
		$args_base['posts_per_page'] = -1;
		$args_base['order'] = 'ASC';
	}

	$ajaxposts = new WP_Query($args_base);
	$response = '';
	if ($ajaxposts->have_posts()) {
		while ($ajaxposts->have_posts()) : $ajaxposts->the_post();
			$response .= get_template_part('inc/webinar_loop_single');
		endwhile;
	} else {
		$response = '';
	}

	echo $response;
	exit;
}
add_action('wp_ajax_nc_webinar_recordings_load_tagged', 'nc_webinar_recordings_load_tagged');
add_action('wp_ajax_nopriv_nc_webinar_recordings_load_tagged', 'nc_webinar_recordings_load_tagged');

//AJAX load more functionality on Changelog unsupported - https://nextcloud.com/changelog-unsupported/
function my_changelog_load_sections_batch() {
	check_ajax_referer('my_changelog_nonce', 'nonce');

	$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
	$startIndex = isset($_POST['startIndex']) ? intval($_POST['startIndex']) : 0;
	$batch_size = 5;

	$post = get_post($post_id);
	if (! $post) {
		wp_send_json_error([ 'message' => 'Post not found' ]);
	}

	$sections = my_extract_changelog_sections($post->post_content);
	$total = count($sections);

	if ($total === 0 || $startIndex >= $total) {
		wp_send_json_success([
			'html' => '',
			'has_more' => false,
		]);
	}

	$html = '';
	$end = min($startIndex + $batch_size, $total);

	for ($i = $startIndex; $i < $end; $i++) {
		$html .= apply_filters('the_content', $sections[ $i ]);
	}

	$nextIndex = $end;
	$has_more = $nextIndex < $total;

	wp_send_json_success([
		'html' => $html,
		'has_more' => $has_more,
		'nextIndex' => $nextIndex,
	]);
}

add_action('wp_ajax_my_changelog_load_sections_batch', 'my_changelog_load_sections_batch');
add_action('wp_ajax_nopriv_my_changelog_load_sections_batch', 'my_changelog_load_sections_batch');
