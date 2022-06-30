<?php
/*
 * Case Study Listing Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$subtext = get_field('subtext');
$studies = get_field('case_study_selection');
?>
<section class="case-study-section" id="<?php echo $id; ?>">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="section-title">
					<?php
					if (!empty($title)) {
						echo '<h2>' . $title . '</h2>';
					}
					if (!empty($subtext)) {
						echo wpautop($subtext);
					}
					?>
				</div>
			</div>
		</div>
		<?php
		if ($studies) {
			echo '<div class="row">';
			echo '<div class="col-12">';
			echo '<div class="case-slider">';
			echo '<div class="row">';
			foreach ($studies as $study) {
				$img = wp_get_attachment_url(get_post_thumbnail_id($study->ID) ?: 0) ?: '';
				$header = get_the_title($study->ID);
				$ex = get_the_excerpt($study->ID);
				$custom_field = get_field('field_name', $study->ID);
				$att = get_field('attachment', $study->ID);
				$str = substr($att, 38);
				echo '<div class="col-lg-4 mb-3">';
				echo '<div class="post-holder" data-file="' . $att . '">';
				echo '<div class="post-img" style="background-image:url(' . $img . ');"></div>';
				echo '<div class="post-body">';
				if (!empty($header)) {
					echo '<h5 class="head">' . $header  . '</h5>';
				}
				if (!empty($ex)) {
					echo '<p>' . $ex . '</p>';
				}
				echo '</div>';
				echo '<div class="form-body">';
				echo do_shortcode("[ninja_form id='4']");
				echo '</div>';
				echo '</div>';
				echo '</div>';
			}
			echo '</div>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
		?>
	</div>
</section>
