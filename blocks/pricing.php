<?php
/*
 * Pricing Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$subtitle = get_field('subtitle');
$text = get_field('text');
$price = get_field('pricing_text');
$desc = get_field('description_text');
$link = get_field('link');
?>
<section class="pricing-section" id="<?php echo $id; ?>">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <?php
					if (!empty($title)) {
						echo '<h2>' . $title . '</h2>';
					}
					if (!empty($subtitle)) {
						echo '<h5>' . $subtitle . '</h5>';
					}
					if (!empty($text)) {
						echo wpautop($text);
					}
					?>
                </div>
            </div>
        </div>
        <div class="row align-items-center justify-content-between">
            <div class="col-lg-5 order-lg-1">
                <div class="pricing-block">
                    <?php
					if (!empty($price)) {
						echo wpautop($price);
					}
					?>
                </div>
            </div>
            <div class="col-lg-12 order-lg-3">
                <?php
				if ($link) {
					$link_url = $link['url'];
					$link_title = $link['title'];
					$link_target = $link['target'] ? $link['target'] : '_self';
					echo '<div class="button-block">';
					echo '<a class="c-btn btn-black" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
					echo '</div>';
				}
				?>
            </div>
            <div class="col-lg-5 order-lg-2">
                <div class="text-block">
                    <?php
					if (!empty($desc)) {
						echo wpautop($desc);
					}
					?>
                </div>
            </div>
        </div>
    </div>
</section>