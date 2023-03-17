<?php
/*
 * Image Columns Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$subtext = get_field('subtext');
$center = get_field('center_content');
?>
<section class="img-cols-section" id="<?php echo $id; ?>">
	<div class="container">
		<?php
		if (!empty($title) || !empty($subtext)) {
			echo '<div class="row">';
			echo '<div class="col-12">';
			echo '<div class="section-title">';
			if (!empty($title)) {
				echo '<h2>' . $title . '</h2>';
			}
			if (!empty($subtext)) {
				echo wpautop($subtext);
			}
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
		?>
		<?php
		if (have_rows('column')) {
			echo '<div class="row">';
			$count = count(get_field('column'));
			while (have_rows('column')) {
				the_row();
				$img = get_sub_field('column_image');
				$text = get_sub_field('column_text');
				if ($count == '1') {
					echo '<div class="col-lg-12 spacer">';
				} elseif ($count == '2') {
					echo '<div class="col-lg-6 spacer">';
				} elseif ($count == '3') {
					echo '<div class="col-lg-4 spacer">';
				} else {
					echo '<div class="col-lg-3 spacer">';
				}
				if ($center) {
					echo '<div class="img-col-block center-it">';
				} else {
					echo '<div class="img-col-block">';
				}
				if (!empty($img)) {
					echo '<div class="the-img">';
					echo '<img src="' . $img . '" alt=""/>';
					echo '</div>';
				}
				if (!empty($text)) {
					echo $text;
				}
				echo '</div>';
				echo '</div>';
			}
			echo '</div>';
		}
		?>
	</div>
</section>