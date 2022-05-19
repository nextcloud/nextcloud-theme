<?php
/*
 * Collaboration Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$text = get_field('text');
$link = get_field('link');
$logos = get_field('logos');
?>
<section class="collaboration-section" id="<?php echo $id; ?>">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="text-block">
					<?php
					if (!empty($title)) {
						echo '<h2>' . $title . '</h2>';
					}
					if (!empty($text)) {
						echo wpautop($text);
					}
					if (!empty($logos)) {
						echo '<ul class="logos-list">';
						foreach ($logos as $logo) {
							echo '<li>';
							echo '<img src="' . $logo . '" alt=""/>';
							echo '</li>';
						}
						echo '</ul>';
					}
					if ($link) {
						$link_url = $link['url'];
						$link_title = $link['title'];
						$link_target = $link['target'] ? $link['target'] : '_self';
						echo '<a class="c-btn btn-blue" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>