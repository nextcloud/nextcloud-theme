<?php
/*
 * Why Hub Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$text = get_field('text');
$img = get_field('image');
?>
<section class="whyhub-section" id="<?php echo $id; ?>">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-6">
				<div class="text-block">
					<?php
					if (!empty($title)) {
						echo '<h2>' . $title . '</h2>';
					}
if (!empty($text)) {
	echo wpautop($text);
}
?>
				</div>
			</div>
			 <?php
			if (!empty($img)) {
				echo '<div class="col-lg-6">';
				echo '<div class="image-block">';
				echo '<img src="' . $img . '" alt=""/>';
				echo '</div>';
				echo '</div>';
			}
?>
		</div>
	</div>
</section>