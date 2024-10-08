<?php
/*
 * Video Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$text = get_field('text');
$video = get_field('video_code');
?>
<section class="video-section" id="<?php echo $id; ?>">
	<div class="container">
		<div class="row">
			<?php
			if (!empty($video)) {
				echo '<div class="col-lg-6 order-lg-2">';
				echo '<div class="video-block">';
				echo $video;
				echo '</div>';
				echo '</div>';
			}
?>
			<div class="col-lg-6 order-lg-1">
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
		</div>
	</div>
</section>