<?php


//show next upcoming webinar in the navigation menu
function next_webinar_nav_func() {
	ob_start();
	$next_webinar_id = $post_id = get_next_webinar_id();

	if ($next_webinar_id) {
		$title = get_the_title($post_id);
		$post_excerpt_full = get_the_excerpt($post_id);
		//$post_excerpt = word_count(get_the_excerpt($post_id), '45');
		$link = get_permalink($post_id);
		$featured_image = get_the_post_thumbnail($post_id, 'large', [ 'class' => 'feat_img' ]);

		//$date_format = get_option( 'date_format' ); // e.g. "F j, Y"
		$my_current_lang = apply_filters('wpml_current_language', null);

		$date_format = 'F j';
		if ($my_current_lang != 'en') {
			$date_format = 'j F';
		}

		$date = (string)get_the_date($date_format);
	

		if (get_post_type() == 'event') {
			$cat = wp_get_object_terms($post_id, 'event_categories', []);
			$event_start_datetime = get_field('event_start_date_and_time', $post_id, false);
			if ($event_start_datetime) {
				$date = date_i18n($date_format, strtotime($event_start_datetime));
			}
		}
				
		echo '<div class="nav-preview-news-item next_webinar" id="' . $post_id . '" style="">';

		echo '<div class="post-box-shortcode-preview">';

		echo '<div class="post-box-shortcode-preview-inner">';
		echo '<div class="post-img" style="">';
		echo '<a href="' . $link . '?ref=top_megamenu_preview" title="' . $title . '">';
		echo $featured_image;
		echo '</a>';
		echo '</div>';

		echo '<div class="post-body">';
		echo '<a href="' . $link . '?ref=top_megamenu_preview" title="' . $title . '">';
		echo '<div class="shortcode_label">';
		echo __('Next webinar', 'nextcloud');
		echo '</div>';
		echo '<h4>' . $title . '</h4>';
					
		?>
					<?php
		$timezone = new DateTimeZone('Europe/Berlin');
		$event_start_datetime = get_field('event_start_date_and_time', $post_id, false);
		$event_start_unix = strtotime($event_start_datetime);
		$date = $date_start = date_create($event_start_datetime);

		$date_custom_datetime = new DateTime($event_start_datetime, new DateTimeZone('Europe/Berlin'));
		$time_start_format = $date_custom_datetime->format('g:i a');
		$date_custom_datetime->setTimezone(new DateTimeZone('America/New_York'));
		$time_start_format_us = $date_custom_datetime->format('g:i a');

		//get CET/CEST
		//$date_start_utc = date_format($date_start,"Z");
		$date_start_utc = wp_date('Z', $event_start_unix, $timezone);
		$utc_offset = $date_start_utc / 3600;
		$cet_cest = '(CET)';
		$edt_est = '(EST)';
		if ($utc_offset > 1) { // 2 = CEST, 1 = CET
			$cet_cest = '(CEST)';
			$edt_est = '(EDT)';
		}
		$my_current_lang = apply_filters('wpml_current_language', null);
		?>
					<ul class="meta_details">
						<li>
						<i class="far fa-calendar-alt"></i> <?php echo formatLanguage($date, $date_format, $my_current_lang); ?> @ <?php echo $time_start_format . ' ' . $cet_cest; ?>
						</li>
					</ul>
					<?php
		echo '</a>';
		echo '</div>'; //.post-body
		echo '</div>'; // .post-box-next-webinar-inner

		echo '<div class="see_all">';
		echo '<a href="/webinars/" title="">' . __('See all webinars', 'nextcloud') . '</a>';
		echo '</div>';

		echo '</div>'; //.post-box-next-webinar
		echo '</div>';
	} else {
		?>
		<a href="/webinars/"><img class="navcon" src="/wp-content/uploads/2024/10/NC-webinar-icon-blue.svg" alt="<?php echo __('Webinars', 'nextcloud'); ?>"><span><?php echo __('Webinars', 'nextcloud'); ?><small>
		<?php echo __('All upcoming webinars and recordings', 'nextcloud'); ?></small></span></a>
		<?php
	}
					

	return ob_get_clean();
}
add_shortcode('next_webinar_nav', 'next_webinar_nav_func');



//show last featured blog post in the navigation menu
function featured_blog_nav_func() {
	ob_start();

	global $wpdb;
	$sticky_ids = unserialize($wpdb->get_var("SELECT `option_value`, `option_id` FROM `$wpdb->options` WHERE `option_name` = 'sticky_posts'"));

	$args_featured = [
		'post_type' => 'post',
		'post_status' => 'publish',
		'post__in' => $sticky_ids,
		'orderby' => 'date',
		'order' => 'DESC'
	];

	$query_featured = new WP_Query($args_featured);
	if ($query_featured->have_posts()) {
		$counter = 0;
		while ($query_featured->have_posts() && $counter < 1) {
			$query_featured->the_post();
			$post_id = get_the_ID();
			$counter++;
		}
	}
	//echo '<span class="hidden">'.$post_id.'</span>';

	if ($post_id) {
		$title = get_the_title($post_id);
		$post_excerpt_full = get_the_excerpt($post_id);
		//$post_excerpt = word_count(get_the_excerpt($post_id), '45');
		$link = get_permalink($post_id);
		$featured_image = get_the_post_thumbnail($post_id, 'large', [ 'class' => 'feat_img' ]);
		$cat = get_the_category($post_id);

	
		echo '<div class="nav-preview-news-item featured_blog" id="' . $post_id . '" style="">';
		echo '<div class="post-box-shortcode-preview">';

		echo '<div class="post-box-shortcode-preview-inner">';
		echo '<div class="post-img" style="">';
		echo '<a href="' . $link . '?ref=top_megamenu_preview" title="' . $title . '">';
		echo $featured_image;
		echo '</a>';
		echo '</div>';

		echo '<div class="post-body">';
		echo '<a href="' . $link . '?ref=top_megamenu_preview" title="' . $title . '">';
		echo '<div class="shortcode_label">';
		echo __('Featured blog', 'nextcloud');
		echo '</div>';
		echo '<h4>' . $title . '</h4>';
		echo '</a>';
		?>
                    <ul class="meta_details">
						<li>
						<i class="far fa-folder-open"></i>
						<?php
			$category_list = join(', ', wp_list_pluck($cat, 'name'));
		echo wp_kses_post($category_list);
		?>
						</li>
					</ul>

                    <?php
					echo '</div>'; //.post-body
		echo '</div>'; // .post-box-featured-blog-inner

		//$blog_link = apply_filters( 'wpml_permalink', get_the_permalink( 56 ), 'nl' );

		echo '<div class="see_all">';
		echo '<a href="' . get_the_permalink(1609) . '" title="">' . __('All news and articles', 'nextcloud') . '</a>';
		echo '</div>';

		echo '</div>'; //.post-box-featured-blog
		echo '</div>';
	} else {
		?>
		<a href="<?php echo get_the_permalink(1609); ?>"><img class="navcon" src="/wp-content/uploads/2024/10/bullhorn-solid.svg" alt="<?php echo __('Blog', 'nextcloud'); ?>"><span><?php echo __('Blog', 'nextcloud'); ?><small>
		<?php echo __('Nextcloud news and articles', 'nextcloud'); ?></small></span></a>
		<?php
	}
					

	return ob_get_clean();
}
add_shortcode('featured_blog_nav', 'featured_blog_nav_func');




//show latest featured event in the navigation menu
function latest_featured_event_nav_func() {
	ob_start();

	//get all sticky events
	$args_events = [
		'post_type' => ['event'],
		'post_status' => 'publish',
		'meta_query' => [
			[
				'key' => 'sticky_event',
				'value' => true,
				'compare' => 'LIKE'
			]
		],
		'orderby' => 'date',
		'order' => 'DESC'
	];

	$events__query = new WP_Query($args_events);
	if ($events__query->have_posts()) {
		$counter = 0;
		while ($events__query->have_posts() && $counter < 1) {
			$events__query->the_post();
			$post_id = get_the_ID();
			$counter++;
		}
	}

	if ($post_id) {
		$title = get_the_title($post_id);
		$post_excerpt_full = get_the_excerpt($post_id);
		//$post_excerpt = word_count(get_the_excerpt($post_id), '45');
		$link = get_permalink($post_id);
		$featured_image = get_the_post_thumbnail($post_id, 'large', [ 'class' => 'feat_img' ]);

		$my_current_lang = apply_filters('wpml_current_language', null);

		$date_format = 'F j, Y';
		if ($my_current_lang != 'en') {
			$date_format = 'j F Y';
		}

		$date = (string)get_the_date($date_format);
		$cat = get_the_category($post_id);

		if (get_post_type() == 'event') {
			$cat = wp_get_object_terms($post_id, 'event_categories', []);
			$event_start_datetime = get_field('event_start_date_and_time', $post_id, false);
			if ($event_start_datetime) {
				$date = date_i18n($date_format, strtotime($event_start_datetime));
			}
		}
		echo '<div class="nav-preview-news-item featured_event" id="' . $post_id . '" style="">';
		echo '<div class="post-box-shortcode-preview">';
		echo '<div class="post-box-shortcode-preview-inner">';
		echo '<div class="post-img" style="">';
		echo '<a href="' . $link . '?ref=top_megamenu_preview" title="' . $title . '">';
		echo $featured_image;
		echo '</a>';
		echo '</div>';

		echo '<div class="post-body">';
		echo '<a href="' . $link . '?ref=top_megamenu_preview" title="' . $title . '">';
		echo '<div class="shortcode_label">';
		echo __('Featured event', 'nextcloud');
		echo '</div>';
		echo '<h4>' . $title . '</h4>';
		echo '</a>';

		$timezone = new DateTimeZone('Europe/Berlin');
		$event_start_datetime = get_field('event_start_date_and_time', $post_id, false);
		$event_start_unix = strtotime($event_start_datetime);
		$date = $date_start = date_create($event_start_datetime);

		$date_custom_datetime = new DateTime($event_start_datetime, new DateTimeZone('Europe/Berlin'));
		$time_start_format = $date_custom_datetime->format('g:i a');
		$date_custom_datetime->setTimezone(new DateTimeZone('America/New_York'));
		$time_start_format_us = $date_custom_datetime->format('g:i a');
		?>
					<ul class="meta_details">
						<li>
						<i class="far fa-calendar-alt"></i> <?php echo formatLanguage($date, $date_format, $my_current_lang); ?>
						</li>
					</ul>

                    <?php
		echo '</div>'; //.post-body
		echo '</div>'; // .post-box-featured-blog-inner

		echo '<div class="see_all">';
		echo '<a href="/events/" title="">' . __('See all events', 'nextcloud') . '</a>';
		echo '</div>';

		echo '</div>'; //.post-box-featured-blog
		echo '</div>';
	} else {
		?>
		<a href="/events/"><img class="navcon" src="/wp-content/uploads/2024/10/NC-events-icon.svg" alt="<?php echo __('Events', 'nextcloud'); ?>"><span><?php echo __('Events', 'nextcloud'); ?><small>
		<?php echo __('All in-person and virtual Nextcloud events', 'nextcloud'); ?></small></span></a>
		<?php
	}
					

	return ob_get_clean();
}
add_shortcode('latest_featured_event_nav', 'latest_featured_event_nav_func');





//show latest featured podcast in the navigation menu
function featured_podcast_nav_func() {
	ob_start();

	//get all sticky podcasts
	$args = [
		'post_type' => ['podcast'],
		'post_status' => 'publish',
		'meta_query' => [
			[
				'key' => 'sticky_episode',
				'value' => true,
				'compare' => 'LIKE'
			]
		],
		'orderby' => 'date',
		'order' => 'DESC'
	];

	$query = new WP_Query($args);
	if ($query->have_posts()) {
		$counter = 0;
		while ($query->have_posts() && $counter < 1) {
			$query->the_post();
			$post_id = get_the_ID();
			$counter++;
		}
	}

	if ($post_id) {
		$title = get_the_title($post_id);
		$post_excerpt_full = get_the_excerpt($post_id);
		//$post_excerpt = word_count(get_the_excerpt($post_id), '45');
		$link = get_permalink($post_id);
		$featured_image = get_the_post_thumbnail($post_id, 'large', [ 'class' => 'feat_img' ]);
	
		echo '<div class="nav-preview-news-item featured_podcast" id="' . $post_id . '" style="">';
		echo '<div class="post-box-shortcode-preview">';

		echo '<div class="post-box-shortcode-preview-inner">';
		echo '<div class="post-img" style="">';
		echo '<a href="' . $link . '?ref=top_megamenu_preview" title="' . $title . '">';
		echo $featured_image;
		echo '</a>';
		echo '</div>';

		echo '<div class="post-body">';
		echo '<a href="' . $link . '?ref=top_megamenu_preview" title="' . $title . '">';
		echo '<div class="shortcode_label">';
		echo __('Featured podcast episode', 'nextcloud');
		echo '</div>';
		echo '<h4>' . $title . '</h4>';
		echo '</a>';

		echo '</div>'; //.post-body
		echo '</div>'; // .post-box-featured-blog-inner

		echo '<div class="see_all">';
		echo '<a href="/podcast/" title="">' . __('See all episodes', 'nextcloud') . '</a>';
		echo '</div>';

		echo '</div>'; //.post-box-featured-blog
		echo '</div>';
	} else {
		?>
		<a href="/podcast/"><img class="navcon" src="/wp-content/uploads/2024/10/NC-podcast-icon.svg" alt="<?php echo __('Podcast', 'nextcloud'); ?>"><span><?php echo __('Podcast', 'nextcloud'); ?><small>
		<?php echo __('Latest Nextcloud Podcast episodes', 'nextcloud'); ?></small></span></a>
		<?php
	}
					

	return ob_get_clean();
}
add_shortcode('featured_podcast_nav', 'featured_podcast_nav_func');




//show latest featured data sheet in the navigation menu
function featured_data_sheet_nav_func() {
	ob_start();

	//get all sticky podcasts
	$args = [
		'post_type' => ['data_sheets'],
		'post_status' => 'publish',
		'meta_query' => [
			[
				'key' => 'sticky',
				'value' => true,
				'compare' => 'LIKE'
			]
		],
		'orderby' => 'date',
		'order' => 'DESC'
	];

	$query = new WP_Query($args);
	if ($query->have_posts()) {
		$counter = 0;
		while ($query->have_posts() && $counter < 1) {
			$query->the_post();
			$post_id = get_the_ID();
			$counter++;
		}
	}

	if ($post_id) {
		$title = get_the_title($post_id);
		$post_excerpt_full = get_the_excerpt($post_id);
		//$post_excerpt = word_count(get_the_excerpt($post_id), '45');
		$link = get_permalink($post_id);
		$featured_image = get_the_post_thumbnail($post_id, 'large', [ 'class' => 'feat_img' ]);

		echo '<div class="nav-preview-news-item featured_data_sheet" id="' . $post_id . '" style="">';
		echo '<div class="post-box-shortcode-preview">';

		echo '<div class="post-box-shortcode-preview-inner">';
		echo '<div class="post-img" style="">';
		echo '<a href="' . $link . '?ref=top_megamenu_preview" title="' . $title . '">';
		echo $featured_image;
		echo '</a>';
		echo '</div>';

		echo '<div class="post-body">';
		echo '<a href="' . $link . '?ref=top_megamenu_preview" title="' . $title . '">';
		echo '<div class="shortcode_label">';
		echo __('Featured data sheet', 'nextcloud');
		echo '</div>';
		echo '<h4>' . $title . '</h4>';
		echo '</a>';

		echo '<div class="see_all">';
		echo '<a href="/whitepapers/#data-sheets/" title="">' . __('See all data sheets', 'nextcloud') . '</a>';
		echo '</div>';

		echo '</div>'; //.post-body
		echo '</div>'; // .post-box-featured-blog-inner

					
					
		echo '</div>'; //.post-box-featured-blog
		echo '</div>';
	} else {
		?>
		<a href="/podcast/"><img class="navcon" src="/wp-content/uploads/2024/10/data-sheets-icon-blue.svg" alt="<?php echo __('Data sheets', 'nextcloud'); ?>"><span><?php echo __('Data sheets', 'nextcloud'); ?><small>
		<?php echo __('See all data sheets', 'nextcloud'); ?></small></span></a>
		<?php
	}
					

	return ob_get_clean();
}
add_shortcode('featured_data_sheet_nav', 'featured_data_sheet_nav_func');


//show selected item in the navigation menu
function featured_selected_item_nav_func() {
	ob_start();

	if (get_field('navigation_preview', 'option') && get_field('navigation_preview', 'option') != 0) {
		echo '<div class="featured_label">' . __('Featured', 'nextcloud') . '</div>';
		echo do_shortcode('[' . get_field('navigation_preview', 'option') . ']');
	}
	return ob_get_clean();
}
add_shortcode('featured_nav_item', 'featured_selected_item_nav_func');
