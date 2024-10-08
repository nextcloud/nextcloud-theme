<?php
/*
 * Unique Column Block Template.
 */
$id = get_field('section_id');
$img = get_field('image');
$title = get_field('title');
$text = get_field('text');
$bot_text = get_field('bottom_text');
$link = get_field('link');
?>
<section class="ucol-section" id="<?php echo $id; ?>">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-6 order-lg-2">
				<?php
				echo '<div class="text-block">';
if (!empty($title)) {
	echo '<h3>' . $title . '</h3>';
}
if (!empty($img)) {
	echo '<img class="mobile-media" src="' . $img . '" alt=""/>';
}
if (!empty($text)) {
	echo wpautop($text);
}
echo '</div>';
?>
			</div>
			<div class="col-lg-6 order-lg-1">
				<?php
if (!empty($img)) {
	echo '<div class="image-block">';
	echo '<img src="' . $img . '" alt=""/>';
	echo '</div>';
}
?>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<?php
echo '<div class="bottom-block">';
if (!empty($bot_text)) {
	echo wpautop($bot_text);
}
if (!empty($link)) {
	$link_url = $link['url'];
	$link_title = $link['title'];
	$link_target = $link['target'] ? $link['target'] : '_self';
	echo '<a class="c-btn btn-blue" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
}
echo '</div>';
?>
			</div>
		</div>
	</div>
</section>