<?php
/*
 * Single Text Block Template.
 */
$id = get_field('section_id');
$text = get_field('text');
?>
<section class="singles-section" id="<?php echo $id; ?>">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-xl-6 col-lg-7">
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