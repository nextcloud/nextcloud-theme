<?php
add_filter( 'manage_partner_posts_posts_columns', 'set_custom_edit_partner_posts_columns' );
function set_custom_edit_partner_posts_columns($columns) {
    //unset( $columns['author'] );
    $columns['partner_region'] = __( 'Region', 'nextcloud' );
    $columns['partner_services'] = __( 'Services', 'nextcloud' );
    return $columns;
}

// Add the data to the custom columns for the book post type:
add_action( 'manage_partner_posts_posts_custom_column' , 'partner_posts_custom_book_column', 10, 2 );
    function partner_posts_custom_book_column( $column, $post_id ) {
        switch ( $column ) {

            case 'partner_region' :
                $region = get_post_meta( $post_id , 'region' , true );
                if(is_array($region)) {
                    echo implode(", ", $region);
                } else {
                    echo $region;
                }
            
            case 'partner_services' :
                    $services = get_post_meta( $post_id , 'services' , true );

                    if(is_array($services)) {
                        echo implode(", ", $services);
                    } else {
                        echo $services;
                    }


            break;
        }
}