<?php
/*
 * Needs Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$text = get_field('text');
?>
<section class="needs-section" id="<?php echo $id; ?>">
	<div class="container">
		<div class="row">
			<div class="col-12">
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
		<div class="row">
			<div class="col-12">
				<?php
				if (have_rows('needs_list')) {
					$i = 1;
					echo '<div class="needs-slider">';
					while (have_rows('needs_list')) {
						the_row();
						$icon = get_sub_field('icon');
						$header = get_sub_field('the_title');
						$link = get_sub_field('link');
						echo '<div>';
						echo '<div class="slide-box box-' . $i . '">';
						if (!empty($icon)) {
							echo '<img src="' . $icon . '" alt=""/>';
						}
						if (!empty($header)) {
							echo '<h4>' . $header . '</h4>';
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
					echo '</div>';
				}
?>
			</div>
		</div>
	</div>
</section>