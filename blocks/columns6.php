<?php
/*
 * Columns 6 Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$invert = get_field('invert_columns');
?>
<section class="columns6-section" id="<?php echo $id; ?>">
    <div class="container">
        <?php
		if (!empty($title)) {
			echo '<div class="row">';
			echo '<div class="col-12">';
			echo '<div class="section-title">';
			echo '<h2>' . $title . '</h2>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
		if (have_rows('column_content')) {
			if ($invert) {
				$i = 1;
			} else {
				$i = 0;
			}
			while (have_rows('column_content')) {
				the_row();
				$col_title = get_sub_field('column_title');
				$col_text = get_sub_field('column_text');
				$link = get_sub_field('column_link');
				echo '<div class="row spacer align-items-center">';
				if ($i % 2 == 0) {
					echo '<div class="col-lg-6 order-lg-1">';
				} else {
					echo '<div class="col-lg-6 order-lg-0">';
				}
				if (have_rows('column_media')) {
					echo '<div class="col-slider">';
					while (have_rows('column_media')) {
						the_row();
						$choice = get_sub_field('media_type');
						$mtext = get_sub_field('media_text');
						$col_vid = get_sub_field('column_video');
						$col_img = get_sub_field('column_image');
						echo '<div>';
						if (!empty($mtext)) {
							echo '<h6>' . $mtext . '</h6>';
						}
						if ($choice == 'video') {
							echo '<div class="video-block">';
							echo $col_vid;
							echo '</div>';
						} elseif ($choice == 'image') {
							echo '<div class="image-block">';
							echo '<img src="' . $col_img . '" alt=""/>';
							echo '</div>';
						}
						echo '</div>';
					}
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
				echo '<div class="mobile-media">';
				if (have_rows('column_media')) {
					echo '<div class="col-slider">';
					while (have_rows('column_media')) {
						the_row();
						$choice = get_sub_field('media_type');
						$mtext = get_sub_field('media_text');
						$col_vid = get_sub_field('column_video');
						$col_img = get_sub_field('column_image');
						echo '<div>';
						if (!empty($mtext)) {
							echo '<h6>' . $mtext . '</h6>';
						}
						if ($choice == 'video') {
							echo '<div class="video-block">';
							echo $col_vid;
							echo '</div>';
						} elseif ($choice == 'image') {
							echo '<div class="image-block">';
							echo '<img src="' . $col_img . '" alt=""/>';
							echo '</div>';
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
					echo '<a class="c-btn btn-white" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
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