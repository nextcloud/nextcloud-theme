<?php
/*
 * Promo 2 Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$playstore = get_field('playstore_link');
$appstore = get_field('appstore_link');
?>
<section class="promo2-section" id="<?php echo $id; ?>">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="text-block">
					<?php
					if (!empty($title)) {
						echo '<h2>' . $title . '</h2>';
					}
					echo '<div class="btn-block">';
					if ($appstore) {
						echo '<a class="app-btn apple-link" href="' . $appstore . '" target="_blank"></a>';
					}
					if ($playstore) {
						echo '<a class="app-btn google-link" href="' . $playstore . '" target="_blank"></a>';
					}
					echo '</div>';
					?>
				</div>
			</div>
		</div>
	</div>
</section>