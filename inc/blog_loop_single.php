<?php

$post_id = get_the_ID();
$title = get_the_title();
$post_excerpt_full = get_the_excerpt();
$post_excerpt = word_count(get_the_excerpt(), '45');
$link = get_permalink();
$featured_image = get_the_post_thumbnail($post_id, 'large', [ 'class' => 'feat_img' ]);
$date_format = get_option('date_format'); // e.g. "F j, Y"
$date = (string)get_the_date($date_format);
$cat = get_the_category($post_id);


if (get_post_type() == 'event') {
	$cat = wp_get_object_terms($post_id, 'event_categories', []);
	$event_start_datetime = get_field('event_start_date_and_time', $post_id, false);
	if($event_start_datetime) {
		$date = date_i18n($date_format, strtotime($event_start_datetime));
	}
}

$webinar_recording = false;
if(get_post_type() == 'event' && get_post_meta($post_id, 'download_available', true)) {
	$webinar_recording = true;
}


$cats = '';
if($cat) {
	foreach ($cat as $c) {
		if($c->term_id != 243 && $c->term_id != 1) { // exclude Uncategorized
			$category_link = get_category_link($c->term_id);
			$cats .= '<a href="'.$category_link.'">' . $c->name . ' </a>';
		}
	}
} else {
	if (get_post_type() == 'event') {
		$cats = '<a href="https://nextcloud.com/events/">' . __('Events', 'nextcloud') . ' </a>';
	} elseif (get_post_type() == 'podcast') {
		$cats = '<a href="https://nextcloud.com/podcast/">' . __('Podcasts', 'nextcloud') . ' </a>';
	}
}

$meta_icon = "far fa-calendar-alt";
if($webinar_recording) {
	$meta_icon = "fa fa-video";
}


$webinar_language_details = apply_filters('wpml_post_language_details', null, $post_id);
$flag_path = "https://nextcloud.com/p/sitepress-multilingual-cms/res/flags/";
$flag_full_url = $flag_path.$webinar_language_details['language_code'].".png";


echo '<div class="col-lg-4 col-md-6 spacer news-container news-item" id="'.$post_id.'" style="">';
echo '<div class="post-box">';
echo '<div class="post-img" style=""><a href="'.$link.'" title="'.$title.'">'.$featured_image.'</a></div>';
echo '<div class="post-body">';
echo '<ul class="post-meta">';
if(get_post_type() != 'event') {
	echo '<li class="date"><i class="'.$meta_icon.'"></i>'.$date.'</li>';
}
echo '<li class="categories"><i class="far fa-folder-open"></i>'.$cats.'</li>';

if($webinar_recording) {
	echo "<li class='webinar_lang'>";
	echo '<img src="'.$flag_full_url.'" class="flag_icon">'.$webinar_language_details['display_name'];
	echo "</li>";
}
echo '</ul>';

echo '<h4><a href="'.$link.'" title="'.$title.'">' . $title . '</a></h4>';

/*
if('event' == get_post_type()){
	echo "<span class='webinar_lang'>";
	echo '<img src="'.$flag_full_url.'" class="flag_icon">'.$webinar_language_details['display_name'];
	echo "</span>";
}
*/

echo '<p>' . $post_excerpt . '</p>';
echo '<a class="c-btn" href="' . $link . '">'.__('Read more', 'nextcloud').'<i class="fas fa-angle-right"></i></a>';
echo '</div>';
echo '</div>';
echo '</div>';
