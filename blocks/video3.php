<?php
/*
 * Video 3 Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$text = get_field('text');
$link = get_field('link');
$video = get_field('video');
?>
<section class="video3-section" id="<?php echo $id; ?>">
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
if ($link) {
	$link_url = $link['url'];
	$link_title = $link['title'];
	$link_target = $link['target'] ? $link['target'] : '_self';
	echo '<a class="c-btn btn-black" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
}
?>
				</div>
			</div>
		</div>
		<div class="row justify-content-center">
			<?php
			if (!empty($video)) {
				echo '<div class="col-lg-6">';
				echo '<div class="video-block">';
				echo $video;
				echo '</div>';
				echo '</div>';
			}
?>
		</div>
	</div>
</section>