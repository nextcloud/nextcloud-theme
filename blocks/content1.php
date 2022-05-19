<?php
/*
 * Content 1 Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$subtext = get_field('subtext');
$image = get_field('image');
$subtitle = get_field('subtitle');
$text = get_field('text');
?>
<section class="content1-section" id="<?php echo $id; ?>">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <?php
					if (!empty($title)) {
						echo '<h2>' . $title . '</h2>';
					}
					if (!empty($subtext)) {
						echo wpautop($subtext);
					}
					?>
                </div>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-6 order-lg-2">
                <?php
				if (!empty($image)) {
					echo '<div class="image-block">';
					echo '<img src="' . $image . '" alt=""/>';
					echo '</div>';
				}
				?>
            </div>
            <div class="col-lg-6 order-lg-1">
                <div class="text-block">
                    <?php
					if (!empty($subtitle)) {
						echo '<h3>' . $subtitle . '</h3>';
					}
					if (!empty($text)) {
						echo wpautop($text);
					}
					if (have_rows('links')) {
						while (have_rows('links')) {
							the_row();
							$link = get_sub_field('link');
							$type = get_sub_field('button_type');
							if ($link) {
								$link_url = $link['url'];
								$link_title = $link['title'];
								$link_target = $link['target'] ? $link['target'] : '_self';
								echo '<a class="c-btn ' . $type . '" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
							}
						}
					}
					?>
                </div>
            </div>
        </div>
    </div>
</section>