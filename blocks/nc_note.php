<?php
/*
 * Note text Block Template.
 */
$id = get_field('section_id');
$text = get_field('text');
$image_id = get_field('image');
$link = get_field('link');
?>
<section class="nc-note-section" id="<?php echo $id; ?>">
	<div class="">
		<div class="row">
			<?php if ($image_id) { ?>
				<div class="col-lg-9">
			<?php } else { ?>
				<div class="col-lg-12">
			<?php } ?>
				<div class="text-block">
					<?php
					if (!empty($text)) {
						echo wpautop($text);
					}
					?>
				</div>
			</div>

			<?php if($image_id) { ?>
				<div class="col-lg-3">
					<div class="img_container">
				<?php
					if($link) {
						echo '<a href="'.$link.'" target="_blank">';
					}

                	echo wp_get_attachment_image( $image_id, 'full' );

					if($link) {
						echo '</a>';
					}	
                ?>
					</div>
				</div>
			<?php } ?>


		</div>
	</div>
</section>