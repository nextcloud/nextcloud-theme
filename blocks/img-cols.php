<?php
/*
 * Image Columns Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$subtext = get_field('subtext');
$center = get_field('center_content');
$custom_css = get_field('custom_css_classes');
?>
<section class="img-cols-section <?php if(isset($custom_css)) echo $custom_css; ?>" id="<?php echo $id; ?>">
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
				$img_id = get_sub_field('column_image');
				$img2 = wp_get_attachment_image( $img_id, 'full', "", array( "class" => "img-responsive" ) );
				$img_url = wp_get_attachment_image_url( $img_id, 'full' );
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
				if (!empty($img_id)) {
					echo '<div class="the-img imageRoundShadow">';
					echo '<a href="'.$img_url.'" class="popup-screenshot-gal" title="">'.$img2.'</a>';
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