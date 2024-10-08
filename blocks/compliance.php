<?php
/*
 * Compliance Slider Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$text = get_field('text');
?>
<section class="compliance-section" id="<?php echo $id; ?>">
	<div class="container">
		<?php
		if (!empty($title) || !empty($text)) {
			echo '<div class="row">';
			echo '<div class="col-lg-6">';
			echo '<div class="section-title">';
			if (!empty($title)) {
				echo '<h2>' . $title . '</h2>';
			}
			if (!empty($text)) {
				echo wpautop($text);
			}
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
if (have_rows('slides')) {
	echo '<div class="row">';
	echo '<div class="col-12">';
	echo '<div class="coll-slider">';
	while (have_rows('slides')) {
		the_row();
		$invert = get_sub_field('invert_columns');
		$col_img = get_sub_field('slide_image');
		$addtext = get_sub_field('additional_text');
		$col_title = get_sub_field('slide_title');
		$col_text = get_sub_field('slide_text');
		$link = get_sub_field('slide_link');
		echo '<div>';
		echo '<div class="row align-items-center">';
		if ($invert) {
			echo '<div class="col-lg-6 order-lg-0">';
		} else {
			echo '<div class="col-lg-6 order-lg-1">';
		}
		echo '<div class="text-block">';
		if (!empty($col_title)) {
			echo '<h3>' . $col_title . '</h3>';
		}
		if (!empty($col_img)) {
			echo '<div class="mobile-media">';
			echo '<div class="image-block">';
			echo '<img src="' . $col_img . '" alt=""/>';
			echo '</div>';
			echo '</div>';
		}
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
		if ($invert) {
			echo '<div class="col-lg-6 order-lg-1">';
		} else {
			echo '<div class="col-lg-6 order-lg-0">';
		}
		if (!empty($col_img)) {
			echo '<div class="image-block">';
			echo '<img src="' . $col_img . '" alt=""/>';
			echo '</div>';
		}
		if (!empty($addtext)) {
			echo '<div class="text-block">';
			echo wpautop($addtext);
			echo '</div>';
		}
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}
	echo '</div>';
	echo '</div>';
	echo '</div>';
}
?>
	</div>
</section>