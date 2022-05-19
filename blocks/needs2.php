<?php
/*
 * Needs 2 Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$text = get_field('text');
?>
<section class="needs2-section" id="<?php echo $id; ?>">
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
        <div class="row">
            <div class="col-12">
                <?php
				if (have_rows('needs_list')) {
					echo '<div class="needs-slider">';
					while (have_rows('needs_list')) {
						the_row();
						$icon = get_sub_field('icon');
						$header = get_sub_field('the_title');
						$desc = get_sub_field('the_text');
						echo '<div>';
						echo '<div class="slide-box">';
						if (!empty($icon)) {
							echo '<img src="' . $icon . '" alt=""/>';
						}
						if (!empty($header)) {
							echo '<h4>' . $header . '</h4>';
						}
						if (!empty($desc)) {
							echo wpautop($desc);
						}
						echo '</div>';
						echo '</div>';
					}
					echo '</div>';
				}
				?>
            </div>
        </div>
    </div>
</section>