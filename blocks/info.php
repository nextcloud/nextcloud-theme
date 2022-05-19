<?php
/*
 * Info Block Template.
 */
$id = get_field('section_id');
$text = get_field('text');
?>
<section class="info-section" id="<?php echo $id; ?>">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="text-block gr">
					<?php
					echo '<img src="' . get_stylesheet_directory_uri() . '/dist/img/info_circle2.svg" alt=""/>';
					if (!empty($text)) {
						echo wpautop($text);
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>