<?php
/*
 * Text Block Template.
 */
$id = get_field('section_id');
$text = get_field('text');
?>
<section class="footnote-section" id="<?php echo $id; ?>">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
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