<?php
/*
 * Single Quote Block Template.
 */
$id = get_field('section_id');
$img = get_field('image');
$quote = get_field('quote');
$name = get_field('name');
$pos = get_field('position');
$link = get_field('link');
?>
<section class="squote-section" id="<?php echo $id; ?>">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-6 order-lg-2">
				<div class="text-block">
					<?php
					if (!empty($quote)) {
						echo '<div class="quote-block">';
						echo wpautop($quote);
						echo '</div>';
					}
					echo '<div class="rest">';
					if (!empty($name)) {
						echo '<h6>' . $name . '</h6>';
					}
					if (!empty($pos)) {
						echo '<span>' . $pos . '</span>';
					}
					if ($link) {
						$link_url = $link['url'];
						$link_title = $link['title'];
						$link_target = $link['target'] ? $link['target'] : '_self';
						echo '<a href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
					}
					echo '</div>';
					?>
				</div>
			</div>
			<div class="order-lg-1 <?php echo empty($img) ? 'offset-lg-4 col-lg-2' : 'col-lg-6 ' ?>">
				<div class="image-block">
					<?php
					if (!empty($img)) {
						echo '<img src="' . $img . '" alt=""/>';
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>