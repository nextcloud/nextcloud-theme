<?php
/*
 * Content 2 Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$text = get_field('text');
$image = get_field('image');
$img_text = get_field('image_subtext');
?>
<section class="content2-section" id="<?php echo $id; ?>">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-6">
				<div class="text-block">
					<?php
					if (!empty($title)) {
						echo '<h3>' . $title . '</h3>';
					}
if (!empty($text)) {
	echo wpautop($text);
}
?>
				</div>
			</div>
			<div class="col-lg-6">
				<?php
				if (!empty($image)) {
					echo '<div class="image-block">';
					echo '<img src="' . $image . '" alt=""/>';
					if (!empty($img_text)) {
						echo '<p>' . $img_text . '</p>';
					}
					echo '</div>';
				}
?>
			</div>
		</div>
	</div>
</section>