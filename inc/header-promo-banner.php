<?php 
//repeater fields 
    $rows = get_field('promo_items_repeater', 'option');
    if ($rows) {
?>
<div class="top-banner-vote">
    <div class="container">
        <div class="row">
            <div class="col-12">
            <div class="owl-carousel promo_banner_carousel">

            <?php  
                    foreach( $rows as $row ) {
                        $image = $row['promo_image'];
                        $promo_custom_html_code = $row['promo_custom_html_code'];
                        $promo_text = $row['promo_text'];
                        $link = $row['promo_link'];
                        $btn_label = $row['button_label'];
                        $video_popup = $row['video_popup'];
                        $custom_css_classes = $row['custom_css_classes'];
			?>
                <div class="item">
                    <div class="nc-awards-banner <?php if(isset($custom_css_classes)) echo $custom_css_classes; ?>">
                        <?php 
                            if( $image ) {
                        ?>
                        <div class="cloudcomputing-logo">
                            <a tabindex="-1" href="<?php if (isset($link)) {
                                echo $link; } ?>" title="<?php
                                if (isset($promo_text)) {
                                echo strip_tags($promo_text);
                                }
                            ?>" target="_blank">

                            <?php 
                            if (isset($promo_custom_html_code) && $promo_custom_html_code != '') {
                                echo $promo_custom_html_code;
                            }
                            else if( $image ) {
                                ?>
                                <img src="<?php echo esc_url($image['url']); ?>" alt="<?php
                                if (isset($promo_text)) {
                                    echo strip_tags($promo_text);
                                    }
                                ?>">
                                <?php
                            }
                            ?>
                            </a>
                        </div>
                        <?php } ?>


                        <div class="awards-text">
                            <a tabindex="-1" href="<?php if (isset($link)) { echo $link; } ?>" title="<?php 
                            if (isset($promo_text)) {
                                echo strip_tags($promo_text);
                                } ?>" target="_blank">
                                <?php if (isset($promo_text)) { echo $promo_text; } ?>
                            </a>
                        </div>

                        <div class="btn-awards <?php if(isset($video_popup) && !empty($video_popup) ) {
                            echo " popup-video "; 
                        }?>">
                            <a tabindex="-1" href="<?php if (isset($link)) {
                                echo $link; } ?>" title="<?php
                                if (isset($promo_text)) { 
                                    echo strip_tags($promo_text);
                                } ?>" target="_blank" class="c-btn btn-top btn-small btn-with-icon <?php if(isset($custom_css_classes)) echo $custom_css_classes; ?>">
                                <?php if (isset($btn_label) && $btn_label != '') {
                                    echo $btn_label; } else {
                                        echo __('Learn more','nextcloud'); 
                                    }
                                ?> <svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 24 24"><path class="st0" d="M12.6,12l-4.6-4.6,1.4-1.4,6,6-6,6-1.4-1.4,4.6-4.6Z"/></svg>
                            </a>
                        </div>

                    </div>
                </div>
            <?php 
                    }
            ?>


            <?php  
            /*
                if (get_field('header_promo_activation', 'option') ) {
			?>
                <div class="item">
                    <div class="nc-awards-banner">
                        <?php 
                            $image = get_field('header_promo_image', 'option');
                            $size = 'full'; // (thumbnail, medium, large, full or custom size)
                            if( $image ) {
                        ?>
                        <div class="cloudcomputing-logo">
                            <a tabindex="-1" href="<?php if (get_field('header_promo_button_link', 'option') ) {
                                echo get_field('header_promo_button_link', 'option'); } ?>" title="<?php
                                if (get_field('header_promo_text', 'option') ) {
                                echo strip_tags(get_field('header_promo_text', 'option'));
                                }
                            ?>" target="_blank">

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
                            <a tabindex="-1" href="<?php if (get_field('header_promo_button_link', 'option') ) { echo get_field('header_promo_button_link', 'option'); } ?>" title="<?php if (get_field('header_promo_text', 'option') ) {
                                echo strip_tags(get_field('header_promo_text', 'option'));
                                } ?>" target="_blank">
                                <?php if (get_field('header_promo_text', 'option') ) { echo get_field('header_promo_text', 'option'); } ?>
                            </a>
                        </div>

                        <div class="btn-awards">
                            <a tabindex="-1" href="<?php if (get_field('header_promo_button_link', 'option') ) {
                                echo get_field('header_promo_button_link', 'option'); } ?>" title="<?php
                                if (get_field('header_promo_text', 'option') ) { 
                                    echo strip_tags(get_field('header_promo_text', 'option'));
                                } ?>" target="_blank" class="c-btn btn-top btn-small btn-with-icon">
                                <?php if (get_field('header_promo_button_label', 'option') ) {
                                    echo get_field('header_promo_button_label', 'option'); } else {
                                        echo __('Learn more','nextcloud'); 
                                    }
                                ?> <i class="fa fa-angle-right right"></i>
                            </a>
                        </div>

                    </div>
                </div>
            <?php }*/ ?>

            <?php
                // The Query for pages
                $args_top_banner = array(
                    'post_type' => array('page', /*'post'*/),
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'meta_key' => 'top_banner_text',
                    'orderby' => 'date',
		            'order' => 'DESC'
                );
                $top_banner_query = new WP_Query( $args_top_banner );
                // The Loop
                if ( $top_banner_query->have_posts() ) {
                    while ( $top_banner_query->have_posts() ) {
                        $top_banner_query->the_post();
                            if(get_field('top_banner_text')) {
                        ?>
                        <div class="item">
                            <div class="nc-awards-banner">

                                <?php $image = get_field('top_banner_image');
                                if( !empty( $image ) ): ?>
                                <div class="cloudcomputing-logo">
                                    <a tabindex="-1" href="<?php echo get_permalink(); ?>" title="<?php echo get_the_title(); ?>" target="_blank">
                                        <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                                    </a>    
                                </div>
                                <?php endif; ?>

                                <div class="awards-text">
                                    <a tabindex="-1" href="<?php echo get_permalink(); ?>" title="<?php echo get_the_title(); ?>" target="_blank">
                                        <?php if (get_field('top_banner_text') ) { echo get_field('top_banner_text'); } ?>
                                    </a>
                                </div>
                                
                                <div class="btn-awards">
                                    <a tabindex="-1" href="<?php echo get_permalink(); ?>" title="<?php echo get_the_title(); ?>" target="_blank" class="c-btn btn-top btn-small btn-with-icon">
                                        <?php 
                                        if(get_field('button_label')) {
                                            echo get_field('button_label');
                                        } else {
                                            echo __('Learn more','nextcloud'); 
                                        }
                                        
                                        ?>
                                        <svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 24 24"><path class="st0" d="M12.6,12l-4.6-4.6,1.4-1.4,6,6-6,6-1.4-1.4,4.6-4.6Z"/></svg>
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


            <?php
                // The Query for posts
                $args_top_banner = array(
                    'post_type' => array(/* 'page', */ 'post'),
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'meta_key' => 'top_banner_text',
                    'orderby' => 'date',
		            'order' => 'DESC'
                );
                $top_banner_query = new WP_Query( $args_top_banner );
                // The Loop
                if ( $top_banner_query->have_posts() ) {
                    while ( $top_banner_query->have_posts() ) {
                        $top_banner_query->the_post();
                            if(get_field('top_banner_text')) {
                        ?>
                        <div class="item">
                            <div class="nc-awards-banner">

                                <?php $image = get_field('top_banner_image');
                                if( !empty( $image ) ): ?>
                                <div class="cloudcomputing-logo">
                                    <a tabindex="-1" href="<?php echo get_permalink(); ?>" title="<?php echo get_the_title(); ?>" target="_blank">
                                        <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                                    </a>    
                                </div>
                                <?php endif; ?>

                                <div class="awards-text">
                                    <a tabindex="-1" href="<?php echo get_permalink(); ?>" title="<?php echo get_the_title(); ?>" target="_blank">
                                        <?php if (get_field('top_banner_text') ) { echo get_field('top_banner_text'); } ?>
                                    </a>
                                </div>
                                
                                <div class="btn-awards">
                                    <a tabindex="-1" href="<?php echo get_permalink(); ?>" title="<?php echo get_the_title(); ?>" target="_blank" class="c-btn btn-top btn-small btn-with-icon">
                                        <?php 
                                        if(get_field('button_label')) {
                                            echo get_field('button_label');
                                        } else {
                                            echo __('Learn more','nextcloud'); 
                                        }
                                        
                                        ?>
                                        <svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 24 24"><path class="st0" d="M12.6,12l-4.6-4.6,1.4-1.4,6,6-6,6-1.4-1.4,4.6-4.6Z"/></svg>
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
                    var promo_banner_carousel = $('.promo_banner_carousel');

                    promo_banner_carousel.owlCarousel({
                        loop:true,
                        autoplayTimeout: 3000,
                        autoplay: true,
                        margin: 30,
                        dots: false,
                        nav:true,
                        items: 1,
                        onDragged: owl_stop_autoplay,
                        autoplayHoverPause:true
                    });


                    promo_banner_carousel.on('click', function(e) {
                        owl_stop_autoplay();
                    });

                    function owl_stop_autoplay() {
                        promo_banner_carousel.trigger('stop.owl.autoplay');
                    }
                });
            </script>

                    

            </div>
        </div>
    </div>
</div>
<?php 
    }
?>