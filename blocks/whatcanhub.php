<?php
/*
 * What Can Hub Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$text = get_field('text');
?>
<section class="whatcanhub-section" id="<?php echo $id; ?>">
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
		<?php
		if (have_rows('item_list')) {
			echo '<div class="row">';
			while (have_rows('item_list')) {
				the_row();
				$header = get_sub_field('the_title');
				$cont = get_sub_field('the_text');
				$link = get_sub_field('link');
				echo '<div class="col-lg-6 spacer">';
				echo '<div class="item-box">';
				if (!empty($header)) {
					echo '<h4>' . $header . '</h4>';
				}
				if (!empty($cont)) {
					echo wpautop($cont);
				}
				if ($link) {
					$link_url = $link['url'];
					$link_title = $link['title'];
					$link_target = $link['target'] ? $link['target'] : '_self';
					echo '<a href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
				}
				echo '</div>';
				echo '</div>';
			}
			echo '</div>';
		}
		?>
	</div>
</section>