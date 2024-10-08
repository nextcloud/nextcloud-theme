<?php
/*
 * Get Started Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$text = get_field('text');
?>
<section class="get-started-section" id="<?php echo $id; ?>">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-10">
				<div class="text-block">
					<?php
					if (!empty($title)) {
						echo '<h3>' . $title . '</h3>';
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