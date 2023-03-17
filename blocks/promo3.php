<?php
/*
 * Promo 3 Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$text = get_field('text');
$link = get_field('link');
?>
<section class="promo3-section" id="<?php echo $id; ?>">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="text-block">
					<?php
					if (!empty($title)) {
						echo '<h3>' . $title . '</h3>';
					}
					if (!empty($text)) {
						echo wpautop($text);
					}
					if ($link) {
						$link_url = $link['url'];
						$link_title = $link['title'];
						$link_target = $link['target'] ? $link['target'] : '_self';
						echo '<a class="c-btn btn-white" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>