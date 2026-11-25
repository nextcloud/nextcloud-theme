<?php

$post_id = get_the_ID();
$title = get_the_title();
$post_excerpt_full = get_the_excerpt();
$post_excerpt = word_count(get_the_excerpt(), '45');

if (has_excerpt()) {
	$post_excerpt = word_count(get_the_excerpt(), '45');
} else {
	// Get full content as HTML (run blocks/shortcodes)
	$html = apply_filters('the_content', get_the_content(null, false));

	// Strip script/style just in case
	$html = preg_replace('#<(script|style)[^>]*>.*?</\1>#si', '', $html);

	//strip WP shortcodes
	$html = strip_shortcodes($html);

	// Grab the first <p>…</p>
	if (preg_match('#<p\b[^>]*>.*?</p>#si', $html, $m)) {
		$post_excerpt = esc_html(wp_trim_words(wp_strip_all_tags($html), 45, '…'));
	}
}

$link = get_permalink();
$featured_image = get_the_post_thumbnail($post_id, 'large', [ 'class' => 'feat_img' ]);
if (!$featured_image) {
	$featured_image = wp_get_attachment_image(350226, 'large', false, ['class' => 'feat_img']);
}

$date_format = get_option('date_format'); // e.g. "F j, Y"
$date = (string)get_the_date($date_format);
$cat = get_the_category($post_id);

if (get_post_type() == 'event') {
	$cat = wp_get_object_terms($post_id, 'event_categories', []);
	$event_start_datetime = get_field('event_start_date_and_time', $post_id, false);
	if ($event_start_datetime) {
		$date = date_i18n($date_format, strtotime($event_start_datetime));
	}
}

$webinar_recording = false;
if (get_post_type() == 'recording') {
	$webinar_recording = true;
}

$cats = '';
if ($cat) {
	$numItems = count($cat);
	$i = 1;
	foreach ($cat as $c) {
		if ($c->term_id != 243 && $c->term_id != 1) { // exclude Uncategorized
			$category_link = get_category_link($c->term_id);
			$cats .= '<a data-i="' . $i . '" data-numitems="' . $numItems . '" href="' . $category_link . '">' . $c->name . ' </a>';
		}
		/*
		if(++$i !== $numItems ) {
			$cats .= ", ";
		}
		*/
	}
} else {
	if (get_post_type() == 'event') {
		$cats = '<a href="https://nextcloud.com/events/">' . __('Events', 'nextcloud') . ' </a>';
	} elseif (get_post_type() == 'podcast') {
		$cats = '<a href="https://nextcloud.com/podcast/">' . __('Podcasts', 'nextcloud') . ' </a>';
	} elseif (get_post_type() == 'press_releases') {
		$cats = '<a href="https://nextcloud.com/press/">' . __('Press release', 'nextcloud') . ' </a>';
	}
}

//$meta_icon = "far fa-calendar-alt";
$meta_icon = '<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 24 24" style="">
<path class="st0" d="M5,22c-.55,0-1.021-.196-1.413-.588-.392-.392-.588-.863-.588-1.413V6c0-.55.196-1.021.588-1.413.392-.392.863-.588,1.413-.588h1V2h2v2h8V2h2v2h1c.55,0,1.021.196,1.413.588s.588.863.588,1.413v14c0,.55-.196,1.021-.588,1.413s-.863.588-1.413.588H5ZM5,20h14v-10H5v10ZM5,8h14v-2H5v2ZM5,8v-2,2ZM12,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288c.192.192.288.429.288.713s-.096.521-.288.713c-.192.192-.429.288-.713.288ZM8,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713-.429.288-.713.288ZM16,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713-.429.288-.713.288ZM12,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288c.192.192.288.429.288.713s-.096.521-.288.713c-.192.192-.429.288-.713.288ZM8,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713c-.192.192-.429.288-.713.288ZM16,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713c-.192.192-.429.288-.713.288Z"></path>
</svg>';

if ($webinar_recording) {
	//$meta_icon = "fa fa-video";
	$meta_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="#0082c9"><path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h480q33 0 56.5 23.5T720-720v180l160-160v440L720-420v180q0 33-23.5 56.5T640-160H160Zm0-80h480v-480H160v480Zm0 0v-480 480Z"/></svg>';
}

$webinar_language_details = apply_filters('wpml_post_language_details', null, $post_id);
$flag_path = 'https://nextcloud.com/p/sitepress-multilingual-cms/res/flags/';
$flag_full_url = $flag_path . $webinar_language_details['language_code'] . '.png';

echo '<div class="col-lg-4 col-md-6 spacer news-container news-item" id="' . $post_id . '" style="">';
echo '<div class="post-box">';
echo '<div class="post-img" style=""><a data-link="' . $link . '" href="' . $link . '" title="' . $title . '">' . $featured_image . '</a></div>';
echo '<div class="post-body">';

if (get_post_type() != 'on-demand-webinar') {

	echo '<ul class="post-meta">';
	if (get_post_type() != 'event') {
		echo '<li class="date">' . $meta_icon . $date . '</li>';
	}
	if ($webinar_recording) {
		echo '<li class="date">' . $meta_icon . __('Recording', 'nextcloud') . '</li>';
	}

	if (get_post_type() != 'page') {
		echo '<li class="categories"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="#0082c9"><path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h240l80 80h320q33 0 56.5 23.5T880-640H447l-80-80H160v480l96-320h684L837-217q-8 26-29.5 41.5T760-160H160Zm84-80h516l72-240H316l-72 240Zm0 0 72-240-72 240Zm-84-400v-80 80Z"/></svg>' . $cats . '</li>';
	}

	if ($webinar_recording) {
		echo "<li class='webinar_lang'>";
		echo '<img src="' . $flag_full_url . '" class="flag_icon">' . $webinar_language_details['display_name'];
		echo '</li>';
	}
	echo '</ul>';

}

echo '<h4><a href="' . $link . '" title="' . $title . '">' . $title . '</a></h4>';
echo '<p>' . $post_excerpt . '</p>';
echo '<a class="c-btn read-more arrow-right" href="' . $link . '">' . __('Read more', 'nextcloud') . '</a>';
echo '</div>';
echo '</div>';
echo '</div>';
