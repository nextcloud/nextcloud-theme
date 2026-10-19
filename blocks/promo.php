<?php
/*
 * Promo Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$subtitle = get_field('subtitle');
$text = get_field('text');
$link = get_field('link');
$sec_link = get_field('secondary_link');
$wide = get_field('wide_section');
$image_icon = get_field('image_icon');
$bg_image = get_field('background_image');
$css_classes = get_field('custom_css_classes');
$expiration = get_field('expiration_date');
$show_promo = true;
if ($expiration) {
	$expiration_ts = strtotime($expiration);
	$now = time();
	if ($expiration_ts < $now) {
		$show_promo = false;
	}
}

if (isset($block['data']['preview_image_help'])) :    /* rendering in inserter preview  */
	echo '<img src="' . $block['data']['preview_image_help'] . '" style="width:100%; height:auto;">';
else : /* rendering in editor body */
	if ($show_promo) {
		?>
<section class="promo-section  <?php
if ($bg_image) {
	echo ' has_custom_bg_image ';
}
		if (isset($css_classes)) {
			echo $css_classes;
		}
		if ($wide) {
			echo ' full-width ';
		} ?>" id="<?php echo $id; ?>" style="<?php
if ($bg_image) {
	echo 'background-image: url(' . $bg_image . ');';
} ?>" data-expiration="<?php if ($expiration) {
	echo $expiration;
} ?>">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="text-block">
					<?php
					if (!empty($image_icon)) {
						echo '<div class="icon"><img src="' . $image_icon . '" alt="Nextcloud - ' . $title . '"/></div>';
					}

		if (!empty($title)) {
			echo '<h2 class="promo_title">' . $title . '</h2>';
		}
		if (!empty($subtitle)) {
			echo '<h5 class="promo_subtitle">' . $subtitle . '</h5>';
		}

		if (!empty($text)) {
			echo wpautop($text);
		}

		if ($link && $sec_link) {
			?>
						<div class="btns-inline-centered">
							<?php
			$link_url = $link['url'];
			$link_title = $link['title'];
			$link_target = $link['target'] ? $link['target'] : '_self';
			echo '<a class="c-btn btn-white" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
			$sec_link_url = $sec_link['url'];
			$sec_link_title = $sec_link['title'];
			$sec_link_target = $sec_link['target'] ? $sec_link['target'] : '_self';
			echo '<a class="c-btn btn-white-outline" href="' . esc_url($sec_link_url) . '" target="' . esc_attr($sec_link_target) . '">' . esc_html($sec_link_title) . '</a>';
			?>
						</div>
						<?php
		} else {
			if ($link) {
				$link_url = $link['url'];
				$link_title = $link['title'];
				$link_target = $link['target'] ? $link['target'] : '_self';
				echo '<a class="c-btn btn-white" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
			}
		}

		?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
	}
endif; ?>