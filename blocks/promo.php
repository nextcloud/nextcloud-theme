<?php
/*
 * Promo Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$text = get_field('text');
$link = get_field('link');
$wide = get_field('wide_section');
$image_icon = get_field('image_icon');
$bg_image = get_field('background_image');

if( isset( $block['data']['preview_image_help'] )  ) :    /* rendering in inserter preview  */
    echo '<img src="'. $block['data']['preview_image_help'] .'" style="width:100%; height:auto;">';

else : /* rendering in editor body */
?>
<section class="promo-section has_custom_bg_image <?php if($wide) echo "full-width"; ?>" id="<?php echo $id; ?>" style="<?php
if($bg_image) {
	echo "background-image: url(".$bg_image.");";
} ?>">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="text-block">
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
<?php endif; ?>