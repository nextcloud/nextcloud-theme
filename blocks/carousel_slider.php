<?php
/*
 * Carousel Slider block template
 */
$id = get_field('id');
$autoplay_options = get_field_object('autoplay');
$autoplay = $autoplay_options['value'];
if (!$autoplay) {
	$autoplay = 'false';
}

$boxed_layout_options = get_field_object('boxed_layout');
$boxed_layout = $boxed_layout_options['value'];
$num_of_items = 6;

if($boxed_layout) {
	$num_of_items = 4;
}


if(isset($block['data']['preview_image_help'])) :    /* rendering in inserter preview  */
	echo '<img src="'. $block['data']['preview_image_help'] .'" style="width:100%; height:auto;">';

else : /* rendering in editor body */
	?>
<section class="carousel-slider-section" id="<?php echo $id; ?>">

<?php if($boxed_layout) { ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-12">
<?php } ?>
            <?php if(have_rows('carousel_item')): ?>
            <div class="box-repeater">
                <div class="box-repeater-items post_clients_carousel owl-carousel owl-theme">
                <?php while(have_rows('carousel_item')): the_row();
                	$image = get_sub_field('image');
                	$title = get_sub_field('title');
                	$link = get_sub_field('link');
                	?>
                        <div class="client_item">
                            <div class="client_item_inner">

                                <?php if (isset($link) && $link != '') { ?>
                                <a href="<?php echo $link; ?>" target="_blank" title="<?php if(isset($title)) {
                                	echo $title;
                                } ?>">
                                <?php } ?>

                                <?php
									echo wp_get_attachment_image($image, 'full');
                	?>

                                <?php if (isset($link) && $link != '') { ?>
                                </a>
                                <?php } ?>

                                </div>
                        </div>                 
                <?php endwhile; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if($boxed_layout) { ?>
                    </div>
                </div>
            </div>
            <?php } ?>
</section>
<script>
    jQuery(document).ready(function ($) {
    $('.post_clients_carousel').owlCarousel({
            loop:true,
            autoplay: true,
            margin:30,
            dots: false,
            nav:true,
            lazyLoad:true,
            stagePadding: 15,
            responsive:{
                0:{
                    items:1
                },
                300:{
                    items:2
                },
                600:{
                    items:3
                },
                800:{
                    items:<?php echo $num_of_items; ?>
                },
                1000:{
                    items:<?php echo $num_of_items; ?>
                }
            }
    });
});
</script>
<?php endif; ?>