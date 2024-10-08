<?php
/*
 * Webinar Ninja form Promo Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$text = get_field('text');
$link = get_field('link');
$wide = get_field('wide_section');
$image_icon = get_field('image_icon');
$ninja_form = get_field('ninja_form');
$bg_image = get_field('background_image');
$open_popup_on_button_click = get_field('open_popup_on_button_click');
$popup_id = get_field('popup_id');
$popup_content = get_field('popup_content');
$custom_css_classes = get_field('custom_css_classes');

if(isset($block['data']['preview_image_help'])) :    /* rendering in inserter preview  */
	echo '<img src="'. $block['data']['preview_image_help'] .'" style="width:100%; height:auto;">';

else : /* rendering in editor body */
	?>
<section class="promo-section promo_webinar has_custom_bg_image <?php
if($wide) {
	echo " full-width ";
}
	if($custom_css_classes) {
		echo " ".$custom_css_classes." ";
	}
	?>" id="<?php if($id) {
		echo $id;
	} ?>" style="<?php
if($bg_image) {
	echo "background-image: url(".$bg_image.");";
} ?>">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="text-block <?php if($open_popup_on_button_click) {
					echo " open-popup-link";
				}?>">
					<?php
					if($image_icon) {
						echo '<div class="icon"><img src="'.$image_icon.'"></div>';
					}

	if (!empty($title)) {
		echo '<h2>' . $title . '</h2>';
	}
	if (!empty($text)) {
		echo wpautop($text);
	}

	if($ninja_form) {
		echo do_shortcode($ninja_form);
	}

	if ($link) {
		$link_url = $link['url'];
		$link_title = $link['title'];
		$link_target = $link['target'] ? $link['target'] : '_self';
		echo '<a class="c-btn btn-white" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
	}


	if($open_popup_on_button_click) {
		wp_enqueue_style('selectizeStyle');
		wp_enqueue_script('selectize');
		wp_enqueue_script('intlTelInput_utils');
		wp_enqueue_script('intlTelInput');
		?>
						<div id="<?php echo $popup_id; ?>" class="white-popup mfp-hide">
							<?php if($popup_content) {
								echo $popup_content;
							} ?>
						</div>
						<?php
	}

	?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>