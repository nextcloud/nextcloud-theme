<?php
/*
 * Solution Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$text = get_field('text');
$center = get_field('center');
?>
<section class="solution-section" id="<?php echo $id; ?>">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php
				if ($center) {
					echo '<div class="section-title text-center">';
				} else {
					echo '<div class="section-title">';
				}
				if (!empty($title)) {
					echo '<h2>' . $title . '</h2>';
				}
				if (!empty($text)) {
					echo wpautop($text);
				}
				echo '</div>';
				?>
            </div>
        </div>
        <div class="row justify-content-center">
            <?php
			if (have_rows('solution_list')) {
				$i = 1;
				while (have_rows('solution_list')) {
					the_row();
					$icon = get_sub_field('icon');
					$header = get_sub_field('the_title');
					$tline = get_sub_field('the_tagline');
					$cont = get_sub_field('the_text');
					$link = get_sub_field('link');
					echo '<div class="col-lg-4 spacer">';
					echo '<div class="item-box box-' . $i . '">';
					if (!empty($icon)) {
						echo '<img src="' . $icon . '" alt=""/>';
					}
					if (!empty($header)) {
						echo '<h4>' . $header . '</h4>';
					}
					if (!empty($tline)) {
						echo '<h6>' . $tline . '</h6>';
					}
					if (!empty($cont)) {
						echo wpautop($cont);
					}
					if ($link) {
						$link_url = $link['url'];
						$link_title = $link['title'];
						$link_target = $link['target'] ? $link['target'] : '_self';
						echo '<a class="c-btn btn-black" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
					}
					echo '</div>';
					echo '</div>';
					$i++;
					if ($i == 3) {
						$i = 1;
					}
				}
			}
			?>
        </div>
    </div>
</section>