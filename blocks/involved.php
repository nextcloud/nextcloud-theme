<?php
/*
 * Get Involved Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$icon = get_field('icon');
?>
<section class="involved-section" id="<?php echo $id; ?>">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <?php
					if (!empty($icon)) {
						echo '<img src="' . $icon . '" alt=""/>';
					}
					if (!empty($title)) {
						echo '<h6>' . $title . '</h6>';
					}
					?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <?php
				if (have_rows('build_column')) {
					while (have_rows('build_column')) {
						the_row();
						$text = get_sub_field('text');
						$blink = get_sub_field('daily_build_link');
						$vertext = get_sub_field('version_text');
						echo '<div class="text-block">';
						if (!empty($text)) {
							echo $text;
						}
						if ($blink) {
							$link_url = $blink['url'];
							$link_title = $blink['title'];
							$link_target = $blink['target'] ? $blink['target'] : '_self';
							echo '<a class="file-link" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
						}
						if (!empty($vertext)) {
							echo '<small>' . $vertext . '</small>';
						}
						if (have_rows('files_links')) {
							echo '<ul class="file-links">';
							while (have_rows('files_links')) {
								the_row();
								$flink = get_sub_field('the_link');
								if ($flink) {
									$link_url = $flink['url'];
									$link_title = $flink['title'];
									$link_target = $flink['target'] ? $flink['target'] : '_self';
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
            <div class="col-lg-6">
                <?php
				if (have_rows('test_clients_column')) {
					while (have_rows('test_clients_column')) {
						the_row();
						$text = get_sub_field('text');
						$textlink = get_sub_field('text_link');
						echo '<div class="text-block">';
						if (!empty($text)) {
							echo wpautop($text);
						}
						if (have_rows('client_links')) {
							echo '<ul class="client-links">';
							while (have_rows('client_links')) {
								the_row();
								$clink = get_sub_field('the_link');
								$himg = get_sub_field('the_icon');
								if ($clink) {
									$link_url = $clink['url'];
									$link_title = $clink['title'];
									$link_target = $clink['target'] ? $slink['target'] : '_self';
									echo '<li><span style="background-image:url(' . $himg . ');"></span><a href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a></li>';
								}
							}
							echo '</ul>';
						}
						if ($textlink) {
							$link_url = $textlink['url'];
							$link_title = $textlink['title'];
							$link_target = $textlink['target'] ? $textlink['target'] : '_self';
							echo '<a href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
						}
						echo '</div>';
					}
				}
				?>
            </div>
        </div>
    </div>
</section>