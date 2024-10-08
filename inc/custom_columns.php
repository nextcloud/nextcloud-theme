<?php

add_filter('manage_partner_posts_posts_columns', 'set_custom_edit_partner_posts_columns');
function set_custom_edit_partner_posts_columns($columns) {
	//unset( $columns['author'] );
	$columns['partner_level'] = __('Level', 'nextcloud');
	//$columns['partner_region'] = __( 'Region', 'nextcloud' );
	$columns['partner_services'] = __('Services', 'nextcloud');
	$columns['service_text'] = __('Service type', 'nextcloud');
	return $columns;
}

// Add the data to the custom columns for the book post type:
add_action('manage_partner_posts_posts_custom_column', 'partner_posts_custom_book_column', 10, 2);
function partner_posts_custom_book_column($column, $post_id) {
	switch ($column) {

		case 'partner_level':
			$partner_level = get_post_meta($post_id, 'partner_level', true);
			if(is_array($partner_level)) {
				echo implode(", ", $partner_level);
			} else {
				echo $partner_level;
			}
			break;


			/*
			case 'partner_region' :
				$region = get_post_meta( $post_id , 'region' , true );
				if(is_array($region)) {
					echo implode(", ", $region);
				} else {
					echo $region;
				}
				break;
			*/
			
		case 'partner_services':
			$services = get_post_meta($post_id, 'services', true);
			if(is_array($services)) {
				echo implode(", ", $services);
			} else {
				echo $services;
			}
			break;


		case 'service_text':
			echo get_post_meta($post_id, 'service_text', true);
			break;

			

			//break;
	}
}
