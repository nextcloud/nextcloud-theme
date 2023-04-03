<?php
/*
 * Compliant Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$text = get_field('text');
$link = get_field('link');
$rows = get_field('content_boxes');
?>
<section class="compliant-section" id="<?php echo $id; ?>">
	<div class="container">
		<div class="row">
			<div class="col-xl-5 col-lg-6">
				<div class="text-block">
					<?php
					if (!empty($title)) {
						echo '<h2>' . $title . '</h2>';
					}
					if (!empty($text)) {
						echo wpautop($text);
					}
					if ($link) {
						$link_url = $link['url'];
						$link_title = $link['title'];
						$link_target = $link['target'] ? $link['target'] : '_self';
						echo '<a class="c-btn btn-blue" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
					}
					?>
				</div>
			</div>
			<div class="col-xl-5 offset-xl-2 col-lg-6">
				<div class="row">
					<div class="col-6">
						<?php
						if ($rows) {
							$first_row = $rows[0];
							if ($first_row) {
								$first_box_title = $first_row['box_title'];
								$first_box_link = $first_row['box_link'];
								echo '<div class="cont-box white-box box-1">';
								echo '<h4>' . $first_box_title . '</h4>';
								if ($first_box_link) {
									$first_link_url = $first_box_link['url'];
									$first_link_title = $first_box_link['title'];
									$first_link_target = $first_box_link['target'] ? $first_box_link['target'] : '_self';
									echo '<a class="round-btn" href="' . esc_url($first_link_url) . '" target="' . esc_attr($first_link_target) . '">' . esc_html($first_link_title) . '</a>';
								}
								echo '</div>';
							}
							$second_row = $rows[1];
							if ($second_row) {
								$second_box_title = $second_row['box_title'];
								$second_box_link = $second_row['box_link'];
								echo '<div class="cont-box dark-box box-2">';
								echo '<h4>' . $second_box_title . '</h4>';
								if ($second_box_link) {
									$second_link_url = $second_box_link['url'];
									$second_link_title = $second_box_link['title'];
									$second_link_target = $second_box_link['target'] ? $second_box_link['target'] : '_self';
									echo '<a class="round-btn" href="' . esc_url($second_link_url) . '" target="' . esc_attr($second_link_target) . '">' . esc_html($second_link_title) . '</a>';
								}
								echo '</div>';
							}
						}
						?>
					</div>
					<div class="col-6">
						<?php
						if ($rows) {
							$third_row = $rows[2];
							if ($third_row) {
								$third_box_title = $third_row['box_title'];
								$third_box_link = $third_row['box_link'];
								echo '<div class="cont-box dark-box box-3">';
								echo '<h4>' . $third_box_title . '</h4>';
								if ($third_box_link) {
									$third_link_url = $third_box_link['url'];
									$third_link_title = $third_box_link['title'];
									$third_link_target = $third_box_link['target'] ? $third_box_link['target'] : '_self';
									echo '<a class="round-btn" href="' . esc_url($third_link_url) . '" target="' . esc_attr($third_link_target) . '">' . esc_html($third_link_title) . '</a>';
								}
								echo '</div>';
							}
							$fourth_row = $rows[3];
							if ($fourth_row) {
								$fourth_box_title = $fourth_row['box_title'];
								$fourth_box_link = $fourth_row['box_link'];
								echo '<div class="cont-box white-box box-4">';
								echo '<h4>' . $fourth_box_title . '</h4>';
								if ($fourth_box_link) {
									$fourth_link_url = $fourth_box_link['url'];
									$fourth_link_title = $fourth_box_link['title'];
									$fourth_link_target = $fourth_box_link['target'] ? $fourth_box_link['target'] : '_self';
									echo '<a class="round-btn" href="' . esc_url($fourth_link_url) . '" target="' . esc_attr($fourth_link_target) . '">' . esc_html($fourth_link_title) . '</a>';
								}
								echo '</div>';
							}
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>