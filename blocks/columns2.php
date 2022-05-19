<?php
/*
 * Columns 2 Block Template.
 */
$id = get_field('section_id');
?>
<section class="columns2-section" id="<?php echo $id; ?>">
	<div class="container">
		<?php
		if (have_rows('column_content')) {
			$i = 1;
			while (have_rows('column_content')) {
				the_row();
				$col_img = get_sub_field('column_image');
				$col_vid = get_sub_field('column_video');
				$col_title = get_sub_field('column_title');
				$col_text = get_sub_field('column_text');
				$link = get_sub_field('column_link');
				echo '<div class="row spacer align-items-center">';
				if ($i % 2 == 0) {
					echo '<div class="col-lg-6 order-lg-1">';
				} else {
					echo '<div class="col-lg-6 order-lg-0">';
				}
				if (!empty($col_vid)) {
					echo '<div class="video-block">';
					echo $col_vid;
					echo '</div>';
				} elseif (!empty($col_img)) {
					echo '<div class="image-block">';
					echo '<img src="' . $col_img . '" alt=""/>';
					echo '</div>';
				}
				echo '</div>';
				if ($i % 2 == 0) {
					echo '<div class="col-lg-6 order-lg-0">';
				} else {
					echo '<div class="col-lg-6 order-lg-1">';
				}
				echo '<div class="text-block">';
				if (!empty($col_title)) {
					echo '<h3>' . $col_title . '</h3>';
				}
				if (!empty($col_vid)) {
					echo '<div class="mobile-media">';
					echo '<div class="video-block">';
					echo $col_vid;
					echo '</div>';
					echo '</div>';
				} elseif (!empty($col_img)) {
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
				echo '</div>';
				$i++;
			}
		}
		?>
	</div>
</section>