<?php
/*
 * Simple Slider block template
 */
$id = get_field('section_id');
$autoplay_options = get_field_object('autoplay');
$autoplay = $autoplay_options['value'];
if (!$autoplay) $autoplay = 'false';


if( isset( $block['data']['preview_image_help'] )  ) :    /* rendering in inserter preview  */
    echo '<img src="'. $block['data']['preview_image_help'] .'" style="width:100%; height:auto;">';

else : /* rendering in editor body */
?>
<section class="simple-slider-section" id="<?php echo $id; ?>">
	<div class="">
        <?php 
        $images = get_field('gallery_images');
        $size = 'full'; // (thumbnail, medium, large, full or custom size)
        if( $images ): ?>
            <div class="owl-carousel simple_slider_slideshow" id="">
                <?php foreach( $images as $image_id ): ?>
                    <div class="gallery_item" id="<?php echo $image_id; ?>">
                        <a href="<?php echo wp_get_attachment_image_url( $image_id, $size ); ?>" class="simple_slider_slideshow_link">
                            <?php echo wp_get_attachment_image( $image_id, $size ); ?>
                        </a>
                        <?php if(wp_get_attachment_caption($image_id)) {
                                echo '<div class="caption">'.wp_get_attachment_caption($image_id).'</div>';
                        }?>
                    </div>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
	</div>
</section>

<script>
    jQuery(document).ready(function ($) {
        $('.simple_slider_slideshow').owlCarousel({
            loop:true,
            stagePadding: 50,

            autoplay: <?php echo $autoplay; ?>,
            margin:10,
            dots: false,
            nav:true,
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:1
                },
                800:{
                    items:1
                },
                1000:{
                    items:1
                }
            }
        });
    });
</script>
<?php endif; ?>