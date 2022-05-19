<?php
/*
 * Subscribe Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$text = get_field('text');
$form = get_field('form_shortcode');
?>
<section class="subs-section" id="<?php echo $id; ?>">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-9 col-lg-11">
                <div class="text-block">
                    <?php
					if (!empty($title)) {
						echo '<h3>' . $title . '</h3>';
					}
					if (!empty($text)) {
						echo wpautop($text);
					}
					if (!empty($form)) {
						echo '<div class="form-block">';
						echo do_shortcode($form);
						echo '</div>';
					}
					?>
                </div>
            </div>
        </div>
    </div>
</section>