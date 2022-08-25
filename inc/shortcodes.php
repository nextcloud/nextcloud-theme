<?php
function nc_links_carousel_func( $atts ) {
	$a = shortcode_atts( array(
		'ids' => ''
	), $atts );

	$ids = $a['ids'];
    if ($ids){
        $ids_array = explode(",", $ids);
        ob_start();
        ?>
        
        <?php 
        $args = array(
                'post__in' => $ids_array
        );
        // The Query
        $the_query = new WP_Query( $args );
        // The Loop
        if ( $the_query->have_posts() ) {
            ?>
            <div class="links_carousel owl-carousel owl-theme">
            <?php
            while ( $the_query->have_posts() ) { 
                $the_query->the_post();
                $first_20_chars = mb_substr(get_the_title(), 0, 20);
                echo '<div class="item"><a class="single_link" title="'.get_the_title().'" href="'.get_the_permalink().'" target="_blank">' . $first_20_chars . ' ...</a></div>';
            }
            ?>
            </div>
            <?php
        }
        /* Restore original Post Data */
        wp_reset_postdata();
        ?>
        <?php
        return ob_get_clean();
    }
}
add_shortcode( 'links_carousel', 'nc_links_carousel_func' );