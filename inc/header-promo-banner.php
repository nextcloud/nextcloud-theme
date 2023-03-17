<div class="top-banner-vote">
    <div class="container">
        <div class="row">
            <div class="col-12">
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
        </div>
    </div>
</div>
