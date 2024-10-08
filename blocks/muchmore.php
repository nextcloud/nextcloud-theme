<?php
/*
 * Much More Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$link = get_field('link');
?>
<section class="muchmore-section" id="<?php echo $id; ?>">
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
		<div class="row">
			<div class="col-12">
				<?php
				if (have_rows('slides')) {
					echo '<div class="more-slider">';
					while (have_rows('slides')) {
						the_row();
						$icon = get_sub_field('icon');
						$text = get_sub_field('text');
						echo '<div>';
						echo '<div class="slide-box">';
						if (!empty($icon)) {
							echo '<img src="' . $icon . '" alt=""/>';
						}
						if (!empty($text)) {
							echo wpautop($text);
						}

						echo '</div>';
						echo '</div>';
					}
					echo '</div>';
				}
?>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<div class="button-block">
					<?php
	if ($link) {
		$link_url = $link['url'];
		$link_title = $link['title'];
		$link_target = $link['target'] ? $link['target'] : '_self';
		echo '<a href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
	}
?>
				</div>
			</div>
		</div>
	</div>
</section>