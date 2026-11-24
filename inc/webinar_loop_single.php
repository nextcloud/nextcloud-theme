<?php
$post_id = get_the_ID();
$title = get_the_title();
$post_excerpt_full = get_the_excerpt();
$post_excerpt = word_count(get_the_excerpt(), '45');
$link = get_permalink();
$featured_image = get_the_post_thumbnail($post_id, 'large', [ 'class' => 'feat_img' ]);
if (!$featured_image) {
	$featured_image = wp_get_attachment_image(350226, 'large', false, ['class' => 'feat_img']);
}
$timezone = wp_timezone(); // Get WordPress timezone object
$timezone_us = new DateTimeZone('America/New_York');

$my_current_lang = apply_filters('wpml_current_language', null);
$date_format = 'F j';
if ($my_current_lang != 'en') {
	$date_format = 'j F';
}

$webinar_recording = false;
if (get_post_type() == 'on-demand-webinar' && get_post_meta($post_id, 'download_available', true)) {
	$webinar_recording = true;
}

$cat = wp_get_object_terms($post_id, 'event_categories', []);
$event_start_datetime = get_field('event_start_date_and_time', $post_id, false);
$event_start_unix = strtotime($event_start_datetime);

$date = $date_start = date_create($event_start_datetime);
$date_start_format = date_i18n($date_format, $event_start_unix);

//$date_formatted = date_format($date, $date_format);
$date_custom_datetime = new DateTime($event_start_datetime, $timezone);
$time_start_format = $date_custom_datetime->format('g:i a');
if ($my_current_lang == 'de') {
	$time_start_format = $date_custom_datetime->format('H:i') . ' Uhr';
}

$date_custom_datetime->setTimezone(new DateTimeZone('America/New_York'));
$time_start_format_us = $date_custom_datetime->format('g:i a');

//get CET/CEST
$cet_cest = wp_date('T', $event_start_unix, $timezone); // Get timezone abbreviation (CET/CEST)
$timezone_abbr = $cet_cest;
if ($my_current_lang == 'de') {
	// Map English abbreviations to German ones
	$abbr_map = [
		'CET' => 'MEZ',  // Mitteleuropäische Zeit
		'CEST' => 'MESZ', // Mitteleuropäische Sommerzeit
	];
	// Use mapping if available, fallback to original
	$timezone_abbr = $abbr_map[$cet_cest] ?? $cet_cest;
}
$edt_est = wp_date('T', $event_start_unix, $timezone_us); // Get timezone abbreviation (EDT/EST)
$cats = '';

// Get the post ID of original post

$original_ID = icl_object_id(200761, 'page', false, 'en');
$cats .= '<a href="' . get_the_permalink($original_ID) . '">' . get_the_title($original_ID) . ' </a>';

/*
$meta_icon = "far fa-calendar-alt";
if($webinar_recording){
	$meta_icon = "fa fa-video";
}
*/
$webinar_language_details = apply_filters('wpml_post_language_details', null, $post_id);
$flag_path = 'https://nextcloud.com/p/sitepress-multilingual-cms/res/flags/';
$flag_full_url = $flag_path . $webinar_language_details['language_code'] . '.png';
$display_name = $webinar_language_details['display_name'];

if (!empty(get_field('custom_language'))) {
	$webinar_lang = get_field('custom_language');
	if ($webinar_lang['label'] != 'default') {
		$webinar_lang_value = $webinar_lang['value'];
		$flag_full_url = $flag_path . $webinar_lang_value . '.svg';
		$display_name = $webinar_lang['label'];
	}
}


echo '<div class="col-lg-4 col-md-6 spacer news-container news-item" id="' . $post_id . '" style="">';
echo '<div class="post-box">';
echo '<div class="post-img" style=""><a href="' . $link . '" title="' . $title . '">' . $featured_image . '</a></div>';
echo '<div class="post-body">';

//if(!$webinar_recording) { // if is upcoming webinar
echo '<ul class="post-meta">';
echo '<li class="categories"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="#0082c9"><path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h240l80 80h320q33 0 56.5 23.5T880-640H447l-80-80H160v480l96-320h684L837-217q-8 26-29.5 41.5T760-160H160Zm84-80h516l72-240H316l-72 240Zm0 0 72-240-72 240Zm-84-400v-80 80Z"/></svg>' . $cats . '</li>';
echo "<li class='webinar_lang'>";
echo '<img src="' . $flag_full_url . '" class="flag_icon">' . $display_name;
echo '</li>';
echo '</ul>';
//}
echo '<h4><a href="' . $link . '" title="' . $title . '">' . $title . '</a></h4>';


if (!$webinar_recording) { // if is upcoming webinar
	?>
<ul class="webinar_details">
	<li>
		<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 24 24" style="">
<path class="st0" d="M5,22c-.55,0-1.021-.196-1.413-.588-.392-.392-.588-.863-.588-1.413V6c0-.55.196-1.021.588-1.413.392-.392.863-.588,1.413-.588h1V2h2v2h8V2h2v2h1c.55,0,1.021.196,1.413.588s.588.863.588,1.413v14c0,.55-.196,1.021-.588,1.413s-.863.588-1.413.588H5ZM5,20h14v-10H5v10ZM5,8h14v-2H5v2ZM5,8v-2,2ZM12,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288c.192.192.288.429.288.713s-.096.521-.288.713c-.192.192-.429.288-.713.288ZM8,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713-.429.288-.713.288ZM16,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713-.429.288-.713.288ZM12,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288c.192.192.288.429.288.713s-.096.521-.288.713c-.192.192-.429.288-.713.288ZM8,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713c-.192.192-.429.288-.713.288ZM16,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713c-.192.192-.429.288-.713.288Z"></path>
</svg><?php
			echo $date_start_format; ?> @ <?php echo $time_start_format . ' ' . $timezone_abbr; ?> 
		<span class="edt_time"><?php echo ' / ' . $time_start_format_us . ' ' . $edt_est; ?></span>
	</li>
</ul>
<?php
}

echo '<p>' . $post_excerpt . '</p>';

$btn_label = __('Register for the webinar', 'nextcloud');
if ($webinar_recording) {
	$btn_label = __('Watch webinar', 'nextcloud');
}

echo '<a class="c-btn" title="' . $btn_label . '" href="' . $link . '">' . $btn_label . '<i class="fas fa-angle-right"></i></a>';
echo '</div>';
echo '</div>';
echo '</div>';
