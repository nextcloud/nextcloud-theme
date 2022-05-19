<?php
/*
 * Video 2 Block Template.
 */
$id = get_field('section_id');
$text = get_field('text');
$video = get_field('video_code');
?>
<section class="video2-section" id="<?php echo $id; ?>">
	<div class="container">
		<div class="row align-items-center">
			<?php
			if (!empty($video)) {
				echo '<div class="col-lg-7 order-lg-2">';
				echo '<div class="video-block">';
				echo $video;
				echo '</div>';
				echo '</div>';
			}
			?>
			<div class="col-lg-5 order-lg-1">
				<div class="text-block">
					<?php
					if (!empty($text)) {
						echo wpautop($text);
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>