<?php
/*
 * Self Hosting Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
?>
<section class="hosting-section hosting2" id="<?php echo $id; ?>">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <?php
					if (!empty($title)) {
						echo '<h2>' . $title . '</h2>';
					}
					?>
                </div>
            </div>
        </div>
        <?php
		if (have_rows('columns')) {
			echo '<div class="row">';
			while (have_rows('columns')) {
				the_row();
				$header = get_sub_field('the_title');
				$cont = get_sub_field('the_text');
				$link = get_sub_field('link');
				echo '<div class="col-lg-6 spacer">';
				echo '<div class="text-block">';
				if (!empty($cont)) {
					echo wpautop($cont);
				}
				if ($link) {
					$link_url = $link['url'];
					$link_title = $link['title'];
					$link_target = $link['target'] ? $link['target'] : '_self';
					echo '<a class="c-btn btn-blue" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
				}
				echo '</div>';
				echo '</div>';
			}
			echo '</div>';
		}
		?>
    </div>
</section>