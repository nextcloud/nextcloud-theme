<div class="top-banner-vote">
    <div class="container">
        <div class="row">
            <div class="col-12">

            <div class="owl-carousel promo_banner_carousel">
                <div class="item">
                    <div class="nc-awards-banner">
                        <?php 
                            $image = get_field('header_promo_image', 'option');
                            $size = 'full'; // (thumbnail, medium, large, full or custom size)
                            if( $image ) {
                        ?>
                        <div class="cloudcomputing-logo">
                            <a href="<?php if (get_field('header_promo_button_link', 'option') ) {
                                echo get_field('header_promo_button_link', 'option'); } ?>" title="<?php
                                if (get_field('header_promo_text', 'option') ) {
                                echo strip_tags(get_field('header_promo_text', 'option'));
                                }
                            ?>" target="">

                            <?php 
                            if (get_field('header_promo_code', 'option') ) {
                                echo get_field('header_promo_code', 'option');
                            }
                            else if( $image ) {
                                ?>
                                <img src="<?php echo esc_url($image['url']); ?>" alt="<?php
                                if (get_field('header_promo_text', 'option') ) {
                                    echo strip_tags(get_field('header_promo_text', 'option'));
                                    }
                                ?>">
                                <?php
                            }
                            ?>
                            </a>
                        </div>
                        <?php } ?>


                        <div class="awards-text">
                            <a href="<?php if (get_field('header_promo_button_link', 'option') ) { echo get_field('header_promo_button_link', 'option'); } ?>" title="<?php if (get_field('header_promo_text', 'option') ) {
                                echo strip_tags(get_field('header_promo_text', 'option'));
                                } ?>" target="">
                                <?php if (get_field('header_promo_text', 'option') ) { echo get_field('header_promo_text', 'option'); } ?>
                            </a>
                        </div>

                        <div class="btn-awards">
                            <a href="<?php if (get_field('header_promo_button_link', 'option') ) {
                                echo get_field('header_promo_button_link', 'option'); } ?>" title="<?php
                                if (get_field('header_promo_text', 'option') ) { 
                                    echo strip_tags(get_field('header_promo_text', 'option'));
                                } ?>" target="" class="c-btn btn-top btn-small btn-with-icon">
                                <?php if (get_field('header_promo_button_label', 'option') ) {
                                    echo get_field('header_promo_button_label', 'option'); }
                                ?> <i class="fa fa-angle-right right"></i>
                            </a>
                        </div>

                    </div>
                </div>


                <?php
                // The Query
                $args_top_banner = array(
                    'post_type' => 'post',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'meta_key' => 'top_banner_image'
                );
                $top_banner_query = new WP_Query( $args_top_banner );

                // The Loop
                if ( $top_banner_query->have_posts() ) {
                    while ( $top_banner_query->have_posts() ) {
                        $top_banner_query->the_post();
                            if(get_field('top_banner_image')) {
                        ?>
                        <div class="item">
                            <div class="nc-awards-banner">
                                <div class="cloudcomputing-logo">
                                    <?php $image = get_field('top_banner_image');
                                    if( !empty( $image ) ): ?>
                                    <a href="<?php echo get_permalink(); ?>" title="<?php echo get_the_title(); ?>" target="">
                                        <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                                    </a>    
                                    <?php endif; ?>

                                </div>

                                <div class="awards-text">
                                    <a href="<?php echo get_permalink(); ?>" title="<?php echo get_the_title(); ?>" target="">
                                        <?php if (get_field('top_banner_text') ) { echo get_field('top_banner_text'); } ?>
                                    </a>
                                </div>
                                
                                <div class="btn-awards">
                                    <a href="<?php echo get_permalink(); ?>" title="<?php echo get_the_title(); ?>" target="" class="c-btn btn-top btn-small btn-with-icon">
                                        <?php echo __('Learn more','nextcloud'); ?>
                                        <i class="fa fa-angle-right right"></i>
                                    </a>
                                </div>


                            </div>
                        </div>
                        <?php
                            }
                    }
                }
                /* Restore original Post Data */
                wp_reset_postdata();
                ?>

            </div>
            <script>
                jQuery(document).ready(function ($) {
                    $('.promo_banner_carousel').owlCarousel({
                        loop:true,
                        autoplay: true,
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

                    

            </div>
        </div>
    </div>
</div>
