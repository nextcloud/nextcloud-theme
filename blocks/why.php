<?php
/*
 * Why Nextcloud Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$subtext = get_field('subtext');
?>
<section class="why-section" id="<?php echo $id; ?>">
	<div class="container">
		<div class="row">
			<div class="col-lg-6">
				<div class="text-block">
					<?php
					if (!empty($title)) {
						echo '<h2>' . $title . '</h2>';
					}
if (!empty($subtext)) {
	echo '<h3>' . $subtext . '</h3>';
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
			echo '<a class="c-btn btn-main ' . $type . '" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
		}
	}
}
?>
				</div>
			</div>
			<?php
			if (have_rows('reasons')) {
				while (have_rows('reasons')) {
					the_row();
					$icon = get_sub_field('icon');
					$header = get_sub_field('box_title');
					$text = get_sub_field('text');
					echo '<div class="col-lg-6">';
					echo '<div class="text-block">';
					if (!empty($icon)) {
						echo '<img src="' . $icon . '" alt=""/>';
					}
					if (!empty($header)) {
						echo '<h4>' . $header . '</h4>';
					}
					if (!empty($text)) {
						echo wpautop($text);
					}
					echo '</div>';
					echo '</div>';
				}
			}
?>
		</div>
	</div>
</section>