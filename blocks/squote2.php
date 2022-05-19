<?php
/*
 * Single Quote 2 Block Template.
 */
$id = get_field('section_id');
$quote = get_field('quote');
$img = get_field('image');
$text = get_field('text');
$pos = get_field('position');
$link = get_field('link');
?>
<section class="squote2-section" id="<?php echo $id; ?>">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 order-lg-2">
                <div class="text-block">
                    <?php
					if (!empty($quote)) {
						echo '<div class="quote-block">';
						echo wpautop($quote);
						echo '</div>';
					}
					echo '<div class="rest">';
					if (!empty($img)) {
						echo '<img src="' . $img . '" alt=""/>';
					}
					echo '</div>';
					?>
                </div>
            </div>
            <div class="col-lg-5 order-lg-1">
                <div class="round-block">
                    <div class="round-inner">
                        <?php
						if (!empty($text)) {
							echo '<p>' . $text . '</p>';
						}
						if ($link) {
							$link_url = $link['url'];
							$link_title = $link['title'];
							$link_target = $link['target'] ? $link['target'] : '_self';
							echo '<a class="c-btn btn-black" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
						}
						?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>