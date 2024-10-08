<?php
/*
 * Jobs Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
?>
<section class="jobs-section" id="<?php echo $id; ?>">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="section-title">
					<?php
					if (!empty($title)) {
						echo '<h3>' . $title . '</h3>';
					}
?>
				</div>
			</div>
		</div>
		<div class="row">
			<?php
			if (have_rows('jobs')) {
				while (have_rows('jobs')) {
					the_row();
					$name = get_sub_field('job_title');
					$text = get_sub_field('job_text');
					$link = get_sub_field('job_link');
					echo '<div class="col-lg-4 spacer">';
					echo '<div class="job-box">';
					if (!empty($name)) {
						echo '<h4>' . $name . '</h4>';
					}
					if (!empty($text)) {
						echo wpautop($text);
					}
					if ($link) {
						$link_url = $link['url'];
						$link_title = $link['title'];
						$link_target = $link['target'] ? $link['target'] : '_self';
						echo '<a class="c-btn" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
					}
					echo '</div>';
					echo '</div>';
				}
			}
?>



		</div>
	</div>
</section>