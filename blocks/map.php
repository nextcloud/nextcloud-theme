<?php
/*
 * Map Block Template.
 */
$id = get_field('section_id');
$map = get_field('map');
?>
<section class="map-section" id="<?php echo $id; ?>">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="map-block">
                    <?php
					if (!empty($map)) {
						echo '<img src="' . $map . '" alt=""/>';
					}
					if (have_rows('locations')) {
						echo '<ul>';
						while (have_rows('locations')) {
							the_row();
							$text = get_sub_field('location_text');
							echo '<li>';
							echo wpautop($text);
							echo '</li>';
						}
						echo '</ul>';
					}
					?>
                </div>
            </div>
        </div>
    </div>
</section>