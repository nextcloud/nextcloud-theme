<?php
/*
 * Columns 4 Block Template (no repeater).
 */
$id = get_field('section_id');
$tagline = get_field('tagline');
$title = get_field('title');
?>
<section class="columns4-section" id="<?php echo $id; ?>">
	<div class="container">
		<?php
		if (!empty($title)) {
			echo '<div class="row">';
			echo '<div class="col-12">';
			echo '<div class="section-title">';
			if (!empty($tagline)) {
				echo '<h6>' . $tagline . '</h6>';
			}
			echo '<h2>' . $title . '</h2>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}

$col_vid = get_field('column_video');
$col_img = get_field('column_images');
$deco = get_field('disable_image_shadow');
$col_title = get_field('column_title');
$col_text = get_field('column_text');
$link = get_field('column_link');

echo '<div class="row spacer align-items-center">';
echo '<div class="col-lg-6 order-lg-1">';

if (!empty($col_vid)) {
	echo '<div class="video-block">';
	echo $col_vid;
	echo '</div>';
} elseif (!empty($col_img)) {
	echo '<div class="image-slider">';
	foreach ($col_img as $img) {
		echo '<div>';
		if (!empty($img['caption'])) {
			echo '<p class="caption">' . $img['caption'] . '</p>';
		}
		if ($deco) {
			echo '<img class="no-shd" src="' . $img['url'] . '" alt=""/>';
		} else {
			echo '<img src="' . $img['url'] . '" alt=""/>';
		}
		echo '</div>';
	}
	echo '</div>';
}
echo '</div>';
				
echo '<div class="col-lg-6 order-lg-0">';
echo '<div class="text-block">';
if (!empty($col_title)) {
	echo '<h3>' . $col_title . '</h3>';
}
echo '<div class="mobile-media">';
if (!empty($col_vid)) {
	echo '<div class="video-block">';
	echo $col_vid;
	echo '</div>';
} elseif (!empty($col_img)) {
	echo '<div class="image-slider">';
	foreach ($col_img as $img) {
		echo '<div>';
		if ($deco) {
			echo '<img class="no-shd" src="' . $img['url'] . '" alt=""/>';
		} else {
			echo '<img src="' . $img['url'] . '" alt=""/>';
		}
		echo '</div>';
	}
	echo '</div>';
}
echo '</div>';
if (!empty($col_text)) {
	echo wpautop($col_text);
}
if (!empty($link)) {
	$link_url = $link['url'];
	$link_title = $link['title'];
	$link_target = $link['target'] ? $link['target'] : '_self';
	echo '<a class="c-btn btn-blue" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
}
echo '</div>';
echo '</div>';
echo '</div>';
				
?>
	</div>
</section>