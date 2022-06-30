<?php
/*
 * Contact Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$text = get_field('text');
$playground = str_contains(get_permalink() ?: '', 'playground');
?>
<section class="contact-section gr" id="<?php echo $id; ?>">
	<div class="container">
		<div class="row">
			<div class="col-lg-6">
				<div class="text-block">
					<?php
					if (!empty($title)) {
						echo '<h1>' . $title . '</h1>';
					}
					if (!empty($text)) {
						echo wpautop($text);
					}
					if (have_rows('links')) {
						echo '<ul class="ext-links">';
						while (have_rows('links')) {
							the_row();
							$link = get_sub_field('link');
							if ($link) {
								$link_url = $link['url'];
								$link_title = $link['title'];
								$link_target = $link['target'] ? $link['target'] : '_self';
								echo '<li><a class="ext-link" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a></li>';
							}
						}
						echo '</ul>';
					}
					?>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="form-block">
					<h4>Contact form</h4>
					<?php echo do_shortcode("[ninja_form id='1']"); ?>
					<h6 class="info-text">Support questions through this form will get ignored.</h6>
				</div>
			</div>
		</div>
	</div>
</section>
