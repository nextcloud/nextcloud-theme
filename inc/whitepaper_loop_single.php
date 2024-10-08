<?php

$post_id = get_the_ID();
$link = get_the_permalink($post_id);
$featured_image = get_the_post_thumbnail($post_id, 'large', [ 'class' => 'feat_img' ]);

$img = wp_get_attachment_url(get_post_thumbnail_id($post_id) ?: 0) ?: '';
$header = get_the_title($post_id);
$ex = get_the_excerpt($post_id);
echo '<div class="col-lg-4 mb-3">';
echo '<div class="post-holder case_study" data-file="">';
echo '<div class="post-img" style=""><a href="'.$link.'" title="'.$header.'">'.$featured_image.'</a></div>';

echo '<div class="post-body">';
if (!empty($header)) {
	echo '<h5 class="head"><a href="'.$link.'">' . $header  . '</a></h5>';
}
if (!empty($ex)) {
	echo '<p>' . $ex . '</p>';
}

echo '<div class="btn_container">';
echo '<a href="'.get_the_permalink($post_id).'" class="c-btn btn-top btn-small btn_see_case_study">'.__('Download', 'nextcloud').'</a>';
echo '</div>';

echo '</div>';
echo '</div>';
echo '</div>';
