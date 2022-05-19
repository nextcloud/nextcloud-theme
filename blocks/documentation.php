<?php
/*
 * Documentation Block Template.
 */
$id = get_field('section_id');
?>
<section class="documentation-section" id="<?php echo $id; ?>">
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-4">
                <?php
				if (have_rows('doc_column')) {
					while (have_rows('doc_column')) {
						the_row();
						$dicon = get_sub_field('doc_icon');
						$dtitle = get_sub_field('doc_title');
						$dtext = get_sub_field('doc_text');
						echo '<div class="doc-block">';
						if (!empty($dicon)) {
							echo '<img src="' . $dicon . '" alt=""/>';
						}
						if (!empty($dtitle)) {
							echo '<h6>' . $dtitle . '</h6>';
						}
						if (!empty($dtext)) {
							echo '<p>' . $dtext . '</p>';
						}
						if (have_rows('doc_links')) {
							echo '<ul class="doc-links">';
							while (have_rows('doc_links')) {
								the_row();
								$dlink = get_sub_field('the_link');
								if ($dlink) {
									$link_url = $dlink['url'];
									$link_title = $dlink['title'];
									$link_target = $dlink['target'] ? $dlink['target'] : '_self';
									echo '<li><a href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a></li>';
								}
							}
							echo '</ul>';
						}
						echo '</div>';
					}
				}
				?>
            </div>
            <div class="col-xl-8 offset-xl-1 col-lg-8">
                <?php
				if (have_rows('help_column')) {
					echo '<div class="row">';
					while (have_rows('help_column')) {
						the_row();
						$hicon = get_sub_field('help_icon');
						$htitle = get_sub_field('help_title');
						$htext = get_sub_field('help_text');
						$hmoretext = get_sub_field('help_additional_text');
						$hlink = get_sub_field('help_button_link');
						echo '<div class="col-xl-5 col-lg-6">';
						echo '<div class="help-block">';
						if (!empty($hicon)) {
							echo '<img src="' . $hicon . '" alt=""/>';
						}
						if (!empty($htitle)) {
							echo '<h6>' . $htitle . '</h6>';
						}
						if (!empty($htext)) {
							echo '<p>' . $htext . '</p>';
						}
						if (have_rows('help_links')) {
							echo '<ul class="help-links">';
							while (have_rows('help_links')) {
								the_row();
								$slink = get_sub_field('the_link');
								$himg = get_sub_field('the_icon');
								if ($slink) {
									$link_url = $slink['url'];
									$link_title = $slink['title'];
									$link_target = $slink['target'] ? $slink['target'] : '_self';
									echo '<li><span style="background-image:url(' . $himg . ');"></span><a href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a></li>';
								}
							}
							echo '</ul>';
						}
						echo '</div>';
						echo '</div>';
						echo '<div class="col-xl-6 offset-xl-1 col-lg-6">';
						echo '<div class="help2-block">';
						if (!empty($hmoretext)) {
							echo wpautop($hmoretext);
						}
						echo '</div>';
						echo '</div>';
						echo '<div class="col-12">';
						if ($hlink) {
							$link_url = $hlink['url'];
							$link_title = $hlink['title'];
							$link_target = $hlink['target'] ? $hlink['target'] : '_self';
							echo '<div class="button-block">';
							echo '<a class="c-btn btn-white" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
							echo '</div>';
						}
						echo '</div>';
					}
					echo '</div>';
				}
				?>
            </div>
        </div>
    </div>
</section>