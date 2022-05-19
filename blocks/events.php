<?php
/*
 * Events Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$text = get_field('text');
$link = get_field('link');
?>
<section class="events-section gr" id="' . $id . '">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
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
        <?php
		if (have_rows('events')) {
			echo '<div class="row justify-content-center">';
			while (have_rows('events')) {
				the_row();
				$img = get_sub_field('event_image');
				$lk = get_sub_field('event_link');
				echo '<div class="col-lg-3 col-md-6 spacer">';
				echo '<div class="event-box">';
				if (!empty($lk)) {
					echo '<a href="' . $lk . '" target="_blank">';
					echo '<div class="event-img" style="background-image:url(' . $img . ');"></div>';
					echo '</a>';
				}
				echo '</div>';
				echo '</div>';
			}
			echo '</div>';
		}
		?>
        <?php
		if ($link) {
			$link_url = $link['url'];
			$link_title = $link['title'];
			$link_target = $link['target'] ? $link['target'] : '_self';
			echo '<div class="row">';
			echo '<div class="col-12">';
			echo '<div class="button-block">';
			echo '<a class="c-btn btn-white" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
		?>
    </div>
</section>