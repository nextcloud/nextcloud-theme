<?php
/*
 * Video 3 Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$text = get_field('text');
?>
<section class="video4-section" id="<?php echo $id; ?>">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
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
        <div class="row justify-content-center">
            <?php
			if (have_rows('videos')) {
				while (have_rows('videos')) {
					the_row();
					$video = get_sub_field('video');
					if (!empty($video)) {
						echo '<div class="col-lg-6 spacer">';
						echo '<div class="video-block">';
						echo $video;
						echo '</div>';
						echo '</div>';
					}
				}
			}
			?>
        </div>
    </div>
</section>