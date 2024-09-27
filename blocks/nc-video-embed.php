<?php
/*
 * Nextcloud Video Embed Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$text = get_field('text');
$video = get_field('video_code');
$caption = get_field('caption');
?>
<section class="video-section nc-video-embed" id="<?php echo $id; ?>">

<?php
					if (!empty($title)) {
						echo '<h2>' . $title . '</h2>';
					}
					if (!empty($text)) {
						echo wpautop($text);
					}
                    
			if (!empty($video)) {
				?>
                <figure class="wp-block-embed is-type-video wp-block-embed-vimeo wp-embed-aspect-16-9 wp-has-aspect-ratio">
                    <div class="wp-block-embed__wrapper">
                        <?php
                        echo $video;
                        ?>
                    </div>
					<?php if (!empty($caption)) { ?>
						<div class="caption">
							<?php echo $caption; ?> 
						</div>
					<?php } ?>

                </figure>
                <?php
			}
			?>
		
	

</section>