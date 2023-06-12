<?php
/*
 * Single Quote 3 Block Template
 */
$id = get_field('section_id');

$img = get_field('image');
$size = 'full'; // (thumbnail, medium, large, full or custom size)


$quote = get_field('quote');
$name = get_field('name');
$pos = get_field('position');
$link = get_field('link');

if( isset( $block['data']['preview_image_help'] )  ) :    /* rendering in inserter preview  */
    echo '<img src="'. $block['data']['preview_image_help'] .'" style="width:100%; height:auto;">';

else : /* rendering in editor body */
?>

<section class="squote_style3_section" id="<?php echo $id; ?>">
	<div class="container">
		<div class="row align-items-center">

			<div class="col-lg-8 order-lg-2">
				<figure class="text-block">
                    <blockquote>
					<?php
					if (!empty($quote)) {
						echo '<div class="quote-block">';
						echo wpautop($quote);
						echo '</div>';
					}
					echo '<div class="rest">';
					if (!empty($name)) {
                        echo '<cite>';
						echo '<h6>' . $name . '</h6>';
                        echo '</cite>';
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
                    </blockquote>
				</figure>
			</div>

			<div class="order-lg-1 <?php echo empty($img) ? 'offset-lg-4 col-lg-2' : 'col-lg-4 ' ?>">
				<div class="image-block">
					<?php
					if (!empty($img)) {
						//echo '<img src="' . $img . '" alt=""/>';
                        echo wp_get_attachment_image( $img, $size );
					}
					?>
				</div>
			</div>

		</div>
	</div>
</section>

<?php endif; ?>