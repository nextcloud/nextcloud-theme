<?php
/*
 * Simple Slider block template
 */
$id = get_field('id');
$custom_class = get_field('custom_css_classes');
$autoplay_options = get_field_object('autoplay');
            $autoplay = is_array($autoplay_options)
                ? count($autoplay_options['value']) ? $autoplay = "true" : $autoplay = "false"
                : $autoplay = "false";
?>
<?php
if( isset( $block['data']['preview_image_help'] )  ) :    /* rendering in inserter preview  */
    echo '<img src="'. $block['data']['preview_image_help'] .'" style="width:100%; height:auto;">';

else : /* rendering in editor body */
?>
<section class="simple-slider-section <?php echo $custom_class; ?>" id="<?php echo $id; ?>">
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
        var owl_simple_slider = $('.simple_slider_slideshow');
        owl_simple_slider.owlCarousel({
            loop: false,
            stagePadding: 50,
            autoplay: <?php echo $autoplay; ?>,
            margin:10,
            dots: false,
            nav:true,
            navText : ['<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 14.299 22.409"><path fill="#0082c9" class="st0" d="M11.25,21.205L1.25,11.205,11.25,1.205l1.8,1.8L4.85,11.205l8.2,8.2-1.8,1.8h0Z"/></svg>','<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 14.299 22.409"><path fill="#0082c9" class="st0" d="M3.05,21.205l-1.8-1.8,8.2-8.2L1.25,3.005l1.8-1.8,10,10L3.05,21.205h0Z"/></svg>'],
            
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
            },
            onDragged: owl_stop_autoplay,
            autoplayHoverPause:true,
            onInitialized: function (event) {
				// Remove role="presentation" from arrows
				$('.owl-prev, .owl-next').removeAttr('role');
				$('.owl-prev, .owl-next').attr('tabindex', 0);
			}
        });

        // Assign original indices to real (non-cloned) items
		owl_simple_slider.find('.owl-item:not(.cloned)').each(function (i) {
			$(this).attr('data-original-index', i);
		});

		// Focus handling for links, buttons, inputs, etc.
		owl_simple_slider.on('focusin', '.owl-item a, .owl-item button, .owl-item input', function () {
			const $focusedItem = $(this).closest('.owl-item');

			if (!$focusedItem.length || $focusedItem.hasClass('cloned')) {
			// Try to find the real item (not a clone)
			const realIndex = $focusedItem.data('original-index');
			if (typeof realIndex !== 'undefined') {
				owl_simple_slider.trigger('to.owl.carousel', [realIndex, 300, true]);
			}
			return;
			}

			const index = $focusedItem.data('original-index');
			if (typeof index !== 'undefined') {
			owl_simple_slider.trigger('to.owl.carousel', [index, 300, true]);
			}
		});

        owl_simple_slider.on('click', function(e) {
            owl_stop_autoplay();
        });
        function owl_stop_autoplay() {
            //console.log('autoplay stopped.');
            owl_simple_slider.trigger('stop.owl.autoplay');
        }

    });
</script>
<?php endif; ?>