<?php
$post_id = get_the_ID();
$title = get_the_title();
$post_excerpt_full = get_the_excerpt();
$post_excerpt = word_count(get_the_excerpt(), '45');
$link = get_permalink();
$featured_image = get_the_post_thumbnail($post_id, 'large', array( 'class' => 'feat_img' ));
$timezone = new DateTimeZone('Europe/Berlin');

$my_current_lang = apply_filters( 'wpml_current_language', NULL );
$date_format = "F j";
if($my_current_lang != 'en'){
	$date_format = "j F";
}

$webinar_recording = false;
if('event' == get_post_type() && get_post_meta($post_id, 'download_available', true) ){
	$webinar_recording = true;
}

$cat = wp_get_object_terms( $post_id, 'event_categories', array() );
$event_start_datetime = get_field('event_start_date_and_time', $post_id, false);
$event_start_unix = strtotime($event_start_datetime);

$date = $date_start = date_create($event_start_datetime);
$date_formatted = date_format($date, $date_format);
$date_custom_datetime = new DateTime($event_start_datetime, $timezone );
$time_start_format = $date_custom_datetime->format('g:i a');
$date_custom_datetime->setTimezone(new DateTimeZone('America/New_York'));
$time_start_format_us = $date_custom_datetime->format('g:i a');
		
//get CET/CEST
//$date_start_utc = date_format($date_start,"Z");
$date_start_utc = wp_date("Z", $event_start_unix, $timezone );
$utc_offset = $date_start_utc / 3600;
$cet_cest = "(CET)";
$edt_est = "(EST)";
if($utc_offset>1) // 2 = CEST, 1 = CET
{
	$cet_cest = "(CEST)";
	$edt_est = "(EDT)";
}

$cats = '';
if($cat) {
	foreach ($cat as $c) {
		if($c->term_id != 243 && $c->term_id != 1 ) { // exclude Uncategorized
			$category_link = get_category_link($c->term_id);
			$cats .= '<a href="'.$category_link.'">' . $c->name . ' </a>';
		}
	}
}

$meta_icon = "far fa-calendar-alt";
if($webinar_recording){
	$meta_icon = "fa fa-video";
}
$webinar_language_details = apply_filters( 'wpml_post_language_details', NULL, $post_id );
$flag_path = "https://nextcloud.com/p/sitepress-multilingual-cms/res/flags/";
$flag_full_url = $flag_path.$webinar_language_details['language_code'].".png";


echo '<div class="col-lg-4 col-md-6 spacer news-container news-item" id="'.$post_id.'" style="">';
echo '<div class="post-box">';
echo '<div class="post-img" style=""><a href="'.$link.'" title="'.$title.'">'.$featured_image.'</a></div>';
echo '<div class="post-body">';

echo '<ul class="post-meta">';

/*
echo '<li class="date"><i class="'.$meta_icon.'"></i>'; 
echo $date;
if(!$webinar_recording){
    echo " @ ".$time;
}
echo '</li>';
*/


echo '<li class="categories"><i class="far fa-folder-open"></i>'.$cats.'</li>';

echo "<li class='webinar_lang'>";
echo '<img src="'.$flag_full_url.'" class="flag_icon">'.$webinar_language_details['display_name'];
echo "</li>";


echo '</ul>';
echo '<h4><a href="'.$link.'" title="'.$title.'">' . $title . '</a></h4>';

?>
<ul class="webinar_details">
	<li>
		<i class="far fa-calendar-alt"></i> <?php echo formatLanguage($date, $date_format, $my_current_lang); ?> @ <?php echo $time_start_format." ".$cet_cest; ?> 
		<span class="edt_time"><?php echo " / ".$time_start_format_us." ".$edt_est; ?></span>
	</li>
</ul>
<?php
echo '<p>' . $post_excerpt . '</p>';

echo '<a class="c-btn" title="'.__('Register for the webinar', 'nextcloud').'" href="' . $link . '">'.__('Register for the webinar', 'nextcloud').'<i class="fas fa-angle-right"></i></a>';
echo '</div>';
echo '</div>';
echo '</div>';