<?php
include get_template_directory() . '/inc/nc_video_block.php';
include get_template_directory() . '/inc/nc_videos_block.php';
include get_template_directory() . '/inc/nav_shortcodes.php';

// WPBakery's textarea_raw_html params are stored base64+urlencoded. Since updating
// WPBakery to 9.0, re-saving a page runs the decoded HTML through an extra
// htmlentities pass each time, so the payload can be entity-encoded more than once.
// Decode repeatedly until it stops changing so this self-heals regardless of how
// many extra passes got baked in.
function nc_decode_raw_html_param($value) {
	$decoded = rawurldecode(base64_decode($value));
	do {
		$previous = $decoded;
		$decoded = html_entity_decode($previous, ENT_QUOTES | ENT_HTML5, 'UTF-8');
	} while ($decoded !== $previous);
	return $decoded;
}

// Re-encode a value in the canonical, zero-extra-entity-layers form, regardless of
// how many entity-encoding passes it currently has baked in.
function nc_reencode_raw_html_param($value) {
	return base64_encode(rawurlencode(nc_decode_raw_html_param($value)));
}

// The WPBakery 9.0 raw_html field bug (see nc_decode_raw_html_param above) bakes the
// extra entity-encoding layer into post_content itself, so it also corrupts what's
// shown back in the WPBakery editor on the next visit and compounds on every re-save.
// Normalize the two textarea_raw_html fields we use back to their canonical encoding
// whenever a post is saved, so the editor always shows clean HTML and the layers never
// have a chance to stack up.
add_filter('content_save_pre', 'nc_normalize_wpbakery_raw_html_fields');
function nc_normalize_wpbakery_raw_html_fields($content) {
	if (strpos($content, 'nc_app_iconbox') !== false) {
		$content = preg_replace_callback(
			'/(\[nc_app_iconbox\b[^\]]*\bextra_description=")([^"]*)(")/',
			function ($m) {
				return $m[1] . nc_reencode_raw_html_param($m[2]) . $m[3];
			},
			$content
		);
	}

	if (strpos($content, 'nc_event_agenda') !== false) {
		$content = preg_replace_callback(
			'/(\[nc_event_agenda\b[^\]]*\btable_rows=")([^"]*)(")/',
			function ($m) {
				$rows = json_decode(rawurldecode($m[2]), true);
				if (!is_array($rows)) {
					return $m[0];
				}
				foreach ($rows as &$row) {
					if (!empty($row['extra_description'])) {
						$row['extra_description'] = nc_reencode_raw_html_param($row['extra_description']);
					}
				}
				return $m[1] . rawurlencode(wp_json_encode($rows)) . $m[3];
			},
			$content
		);
	}

	return $content;
}


function get_webinar_info($data, $post_id = 0) {

			if($post_id == 0) {
				$post_id = get_the_ID();
			}
			
			//$timezone = new DateTimeZone('Europe/Berlin');
			$timezone = wp_timezone(); // Get WordPress timezone object
			
			$my_current_lang = apply_filters( 'wpml_current_language', NULL );

			$date_format = "F j";
			if($my_current_lang != 'en'){
				$date_format = "j F";
			}
			$webinar_recording = false;
			if('event' == get_post_type() && get_post_meta($post_id, 'download_available', true) ){
				$webinar_recording = true;
			}

			$cat = wp_get_object_terms( $post_id, 'event_categories', array() );
			$event_start_datetime = get_field('event_start_date_and_time', $post_id, false);
			$event_start_unix = strtotime($event_start_datetime);

			$date = $date_start = date_create($event_start_datetime);
			$date_formatted = date_format($date, $date_format);
			$date_custom_datetime = new DateTime($event_start_datetime, $timezone );
			$time_start_format = $date_custom_datetime->format('g:i a');
			$date_custom_datetime->setTimezone(new DateTimeZone('America/New_York'));
			$time_start_format_us = $date_custom_datetime->format('g:i a');
					
			//get CET/CEST
			$date_start_utc = wp_date("Z", $event_start_unix, $timezone );
			$utc_offset = $date_start_utc / 3600;
			$cet_cest = "(CET)";
			$edt_est = "(EST)";
			if($utc_offset>1) // 2 = CEST, 1 = CET
			{
				$cet_cest = "(CEST)";
				$edt_est = "(EDT)";
			}

			$test_edt_est = wp_date('T'); // Get timezone abbreviation (EDT/EST)


			$cats = '';
			/*
			if($cat) {
				foreach ($cat as $c) {
					if($c->term_id != 243 && $c->term_id != 1 ) { // exclude Uncategorized
						$category_link = get_category_link($c->term_id);
						$cats .= '<a href="'.$category_link.'">' . $c->name . ' </a>';
					}
				}
			}
			*/
			$original_ID = icl_object_id( 200761, 'page', false, 'en' );
			$cats .= '<a href="'.get_the_permalink($original_ID).'">' . get_the_title($original_ID) . ' </a>';


			$meta_icon = "far fa-calendar-alt";
			if($webinar_recording){
				$meta_icon = "fa fa-video";
			}
			$webinar_language_details = apply_filters( 'wpml_post_language_details', NULL, $post_id );
			$flag_path = "https://nextcloud.com/p/sitepress-multilingual-cms/res/flags/";
			$flag_full_url = $flag_path.$webinar_language_details['language_code'].".svg";

			$return = ""; 

			if($data == "webinar_cats_lang_flag") {

				//svg folder open
				return '<li class="categories"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="#0082c9"><path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h240l80 80h320q33 0 56.5 23.5T880-640H447l-80-80H160v480l96-320h684L837-217q-8 26-29.5 41.5T760-160H160Zm84-80h516l72-240H316l-72 240Zm0 0 72-240-72 240Zm-84-400v-80 80Z"></path></svg>'.$cats.'</li><li class="webinar_lang"><img src="'.$flag_full_url.'" class="flag_icon">'.$webinar_language_details['display_name'].'</li>';

			} else if ($data == "webinar_datetime_and_lang_flag") {
				
				//svg calendar
				return '<li><svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 24 24" style="">
				<path class="st0" d="M5,22c-.55,0-1.021-.196-1.413-.588-.392-.392-.588-.863-.588-1.413V6c0-.55.196-1.021.588-1.413.392-.392.863-.588,1.413-.588h1V2h2v2h8V2h2v2h1c.55,0,1.021.196,1.413.588s.588.863.588,1.413v14c0,.55-.196,1.021-.588,1.413s-.863.588-1.413.588H5ZM5,20h14v-10H5v10ZM5,8h14v-2H5v2ZM5,8v-2,2ZM12,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288c.192.192.288.429.288.713s-.096.521-.288.713c-.192.192-.429.288-.713.288ZM8,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713-.429.288-.713.288ZM16,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713-.429.288-.713.288ZM12,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288c.192.192.288.429.288.713s-.096.521-.288.713c-.192.192-.429.288-.713.288ZM8,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713c-.192.192-.429.288-.713.288ZM16,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713c-.192.192-.429.288-.713.288Z"></path>
				</svg>'.formatLanguage($date, $date_format, $my_current_lang).' @ '.$time_start_format.' '.$cet_cest.'<span class="edt_time"> / '.$time_start_format_us.' '.$edt_est.'</span></li><li class="webinar_lang"><img src="'.$flag_full_url.'" class="flag_icon">'.$webinar_language_details['display_name'].'</li>';

			}
			else if ($data == "webinar_date_time") {
				return '<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 24 24" style="">
				<path class="st0" d="M5,22c-.55,0-1.021-.196-1.413-.588-.392-.392-.588-.863-.588-1.413V6c0-.55.196-1.021.588-1.413.392-.392.863-.588,1.413-.588h1V2h2v2h8V2h2v2h1c.55,0,1.021.196,1.413.588s.588.863.588,1.413v14c0,.55-.196,1.021-.588,1.413s-.863.588-1.413.588H5ZM5,20h14v-10H5v10ZM5,8h14v-2H5v2ZM5,8v-2,2ZM12,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288c.192.192.288.429.288.713s-.096.521-.288.713c-.192.192-.429.288-.713.288ZM8,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713-.429.288-.713.288ZM16,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713-.429.288-.713.288ZM12,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288c.192.192.288.429.288.713s-.096.521-.288.713c-.192.192-.429.288-.713.288ZM8,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713c-.192.192-.429.288-.713.288ZM16,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713c-.192.192-.429.288-.713.288Z"></path>
				</svg>'.formatLanguage($date, $date_format, $my_current_lang).' @ '.$time_start_format.' '.$cet_cest.'<span class="edt_time"> / '.$time_start_format_us.' '.$edt_est.'</span>';

			} else {
				return; 
			}

}



//get ID of the next webinar 
function get_next_webinar_id() {
	$timezone = wp_timezone(); // Get WordPress timezone object
	$current_date_time = wp_date("Y-m-d H:i:s", null, $timezone );
	$my_current_lang = apply_filters( 'wpml_current_language', NULL );

	$args = array(
			'post_type' => 'event',
			'post_status' => 'publish',
			//'posts_per_page' => 1,
			'tag__not_in' => array(269),
			'tax_query' => array(
				array(
					'taxonomy' => 'event_categories',
					'field'    => 'slug',
					'terms'    => 'webinars',
				),
			),
			'meta_query' => array(
				array(
					'key' => 'event_start_date_and_time',
					'value'   => $current_date_time,
					'compare' => '>=',
					'type'	=> 'DATETIME'
				),
			),
			'orderby' => 'meta_value',
			'order' => 'ASC'
	);

	$next_webinar_localized = 0;
	$next_webinar_default = 0;
	
	$the_query = new WP_Query($args);
	if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();

					//default
					$webinar_lang = 'en';


					$webinar_language_details = apply_filters( 'wpml_post_language_details', NULL, get_the_ID() );
					if(isset($webinar_language_details)) {
						$webinar_lang = $webinar_language_details['language_code'];
					}


					if($webinar_lang == $my_current_lang) {
							$next_webinar_localized = get_the_ID();
							break;
						} else {
							if($next_webinar_default == 0) {
								$next_webinar_default = get_the_ID();
							}
					}
					

					
			}			
	}
	wp_reset_postdata();

	if($next_webinar_localized == 0) {
		return $next_webinar_default;
	} else {
		return $next_webinar_localized;
	}

}


//get ID of the next event LP
function get_next_event_id($type) {
	$timezone = wp_timezone(); // Get WordPress timezone object
	$current_date_time = wp_date("Y-m-d H:i:s", null, $timezone );

	if($type == "enterprise_day") {
		$args = array(
				'post_type' => 'page',
				'post_status' => 'publish',
				'posts_per_page' => 1,
				'tag' => 'enterprise-day',
				'meta_query' => array(
					array(
						'key' => 'event_start_date_and_time',
						'value'   => $current_date_time,
						'compare' => '>=',
						'type'	=> 'DATETIME'
					),
				),
				'orderby' => 'meta_value',
				'order' => 'ASC'
		);
	} else {
		$args = array(
				'post_type' => 'event',
				'post_status' => 'publish',
				'posts_per_page' => 1,
				'tag__not_in' => array(269),
				'tax_query' => array(
					array(
						'taxonomy' => 'event_categories',
						'field'    => 'slug',
						'terms'    => 'exhibition',
					),
				),
				'meta_query' => array(
					array(
						'key' => 'event_start_date_and_time',
						'value'   => $current_date_time,
						'compare' => '>=',
						'type'	=> 'DATETIME'
					),
				),
				'orderby' => 'meta_value',
				'order' => 'ASC'
		);
	}
	
	$the_query = new WP_Query($args);
	if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
					$next_id = get_the_ID();
			}			
	}
	wp_reset_postdata();
	return $next_id;
}



function formatLanguage(DateTime $dt,string $format,string $language = 'en') : string {
    $curTz = $dt->getTimezone();
    if($curTz->getName() === 'Z'){
      //INTL don't know Z
      $curTz = new DateTimeZone('UTC');
    }

    $formatPattern = strtr($format,array(
        'D' => '{#1}',
        'l' => '{#2}',
        'M' => '{#3}',
        'F' => '{#4}',
      ));
      $strDate = $dt->format($formatPattern);
      $regEx = '~\{#\d\}~';
      while(preg_match($regEx,$strDate,$match)) {
        $IntlFormat = strtr($match[0],array(
          '{#1}' => 'E',
          '{#2}' => 'EEEE',
          '{#3}' => 'MMM',
          '{#4}' => 'MMMM',
        ));
        $fmt = datefmt_create( $language ,IntlDateFormatter::FULL, IntlDateFormatter::FULL,
        $curTz, IntlDateFormatter::GREGORIAN, $IntlFormat);
        $replace = $fmt ? datefmt_format( $fmt ,$dt) : "???";
        $strDate = str_replace($match[0], $replace, $strDate);
      }

    return $strDate;
}

function get_event_date($date_start_timestamp, $date_end_timestamp = null){
	if($date_start_timestamp) {

		$start_day = gmdate("d", $date_start_timestamp);
		$start_datetime = new DateTime('@' . $date_start_timestamp);


		$my_current_lang = apply_filters( 'wpml_current_language', NULL );
		$date_format = get_option('date_format');

		
		$start_month = gmdate("F", $date_start_timestamp);
		$start_month_intl = formatLanguage($start_datetime, 'F', $my_current_lang);

		if( $date_end_timestamp && ( $start_day != gmdate("d", $date_end_timestamp)) ) {
			$end_day = gmdate("d", $date_end_timestamp);

			$end_datetime = new DateTime('@' . $date_end_timestamp);

			$end_month = gmdate("F", $date_end_timestamp);
			$end_month_intl = formatLanguage($end_datetime, 'F', $my_current_lang);

			$start_day = gmdate("j", $date_start_timestamp);
			$end_day = gmdate("j", $date_end_timestamp);
			
			if($start_month == $end_month){ //if is the same month

				if($my_current_lang == 'en') {
					echo $start_month_intl." ".$start_day."-".$end_day.", ";
				} else {
					echo $start_day."-".$end_day." ".$start_month_intl." ";
				}

				echo gmdate("Y", $date_end_timestamp);

			} else { //different months

				if($my_current_lang == 'en') {
					echo $start_month_intl." ".$start_day."-".$end_month_intl." ".$end_day.", ";
				}else {
					echo $start_day." ".$start_month_intl."-".$end_day." ".$end_month_intl." ";
				}
				
				echo gmdate("Y", $date_end_timestamp);
			}


		} else {
			echo formatLanguage($start_datetime, $date_format, $my_current_lang);
		}
		
	}

}


//get the full URL, used in the forms
function nc_get_full_url(){
	global $wp;
	$return = home_url(add_query_arg(array(), $wp->request));
	if( isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']!='' ){
		$return .= "?".strip_tags($_SERVER['QUERY_STRING']);
	}
	return $return;
}
add_shortcode('fullURL', 'nc_get_full_url');


//get UTM parameters, used in the forms
function nc_get_utm_parameter( $atts ) {
    $atts = shortcode_atts(
        array(
            'field' => '',
        ),
        $atts,
        'get_utm_parameter'
    );

    $field  = sanitize_key( $atts['field'] );
    $return = '';

    // 1. URL always first priority
    if ( isset( $_GET[ $field ] ) ) {
        $return = sanitize_text_field( wp_unslash( $_GET[ $field ] ) );
    } else if ( empty( $return ) && isset( $_COOKIE['nc_utm_parameters'] ) ) {
		// 2. Cookie fallback
		
        $raw_cookie = wp_unslash( $_COOKIE['nc_utm_parameters'] );

        // Convert JSON cookie string to array
        $params = json_decode( $raw_cookie, true );

        if ( is_array( $params ) && isset( $params[ $field ] ) ) {
            $return = sanitize_text_field( $params[ $field ] );
        }
    }

    return $return;
}
add_shortcode( 'get_utm_parameter', 'nc_get_utm_parameter' );



function nc_links_carousel_func($atts) {
	$a = shortcode_atts(array(
		'ids' => ''
	), $atts);

	$ids = $a['ids'];
	if ($ids) {
		$ids_array = explode(",", $ids);
		ob_start(); ?>
        
        <?php
		$args = array(
			'post__in' => $ids_array
		);
		// The Query
		$the_query = new WP_Query($args);
		// The Loop
		if ($the_query->have_posts()) {
			?>
            <div class="links_carousel owl-carousel owl-theme">
            <?php
			while ($the_query->have_posts()) {
				$the_query->the_post();
				$first_20_chars = mb_substr(get_the_title(), 0, 20);
				echo '<div class="item"><a class="single_link" title="'.get_the_title().'" href="'.get_the_permalink().'" target="_blank">' . $first_20_chars . ' ...</a></div>';
			} ?>
            </div>
            <?php
		}
		/* Restore original Post Data */
		wp_reset_postdata(); ?>
        <?php
		return ob_get_clean();
	}
}
add_shortcode('links_carousel', 'nc_links_carousel_func');



function nc_clients_carousel() {
	// Title
	vc_map(
		array(
			'name' => __('Clients'),
			'base' => 'nc_clients_carousel_content',
			'category' => __('Carousel'),
			'params' => array(


				array(
					"type" => "attach_images",
					"heading" => esc_html__("Add Clients Images", "appcastle-core"),
					"description" => esc_html__("Add Clients Images", "appcastle-core"),
					"param_name" => "screenshots",
					"value" => "",
				),

			)
		)
	);
}
add_action('vc_before_init', 'nc_clients_carousel');
function nc_clients_carousel_content_function($atts, $content) {
	$gallery = shortcode_atts(
		array(
			'screenshots' => 'screenshots',
		), $atts);
	
	$image_ids = explode(',', $gallery['screenshots']);
	$return = '
		<div class="clients_carousel owl-carousel owl-theme">';
	foreach ($image_ids as $image_id) {
		$image = wp_get_attachment_image($image_id, 'thumbnail');
	
		$return .= '<div class="item client_item">';
		$return .= $image;
		$return .= '</div>';

	}
	$return .= '</div>';
	return $return;
}
add_shortcode('nc_clients_carousel_content', 'nc_clients_carousel_content_function');




//Repeater Clients Carousel
add_action('vc_before_init', 'box_repeater_items_funct');
function box_repeater_items_funct() {
	vc_map(
		  array(
		  	"name" => __("Box Repeater", "nextcloud"), // Element name
		  	"base" => "box_repeater", // Element shortcode
		  	"class" => "box-repeater",
		  	"category" => __('Repeater', 'nextcloud'),
		  	'params' => array(

				array(
					"type" => "textfield",
					"heading" => esc_html__("ID", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "id",
					"value" => "",
				),

				array(
					"type" => "textfield",
					"heading" => esc_html__("Custom CSS classes", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "custom_css_classes",
					"value" => "",
				),

		  		array(
		  			'type' => 'param_group',
		  			'param_name' => 'box_repeater_items',
		  			'params' => array(
		  				array(
		  					"type" => "attach_image",
		  					"holder" => "img",
		  					"class" => "",
		  					"heading" => __("Client Logo", "nextcloud"),
		  					"param_name" => "box_repeater_items_img",
		  					"value" => __("", "nextcloud"),
		  				),
		  				array(
		  					"type" => "textfield",
		  					"holder" => "div",
		  					"class" => "",
		  					"admin_label" => true,
		  					"heading" => __("Client name", "nextcloud"),
		  					"param_name" => "box_repeater_items_title",
		  					"value" => __("", "nextcloud"),
		  				),
		  				array(
		  					"type" => "vc_link",
		  					"holder" => "div",
		  					"class" => "client_link",
		  					"admin_label" => false,
		  					"heading" => __("Link", "nextcloud"),
		  					"param_name" => "box_repeater_items_link",
		  					"value" => __("", "nextcloud"),
		  				),
						array(
		  					"type" => "textfield",
		  					"holder" => "div",
		  					"class" => "",
		  					"admin_label" => false,
		  					"heading" => __("CSS class", "nextcloud"),
		  					"param_name" => "box_repeater_items_css",
		  					"value" => __("", "nextcloud"),
		  				),
		  			)
		  		),

				array(
					"type" => "textarea",
					"heading" => esc_html__("Custom code (Settings for the carousel)", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "custom_code",
					"value" => "",
				),

				/*
				array(
					"type" => "checkbox",
					"heading" => esc_html__("Disable carousel", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "disable_carousel",
					"value" => "",
				),
				*/
				
		  	)
		  )
	  );
}

add_shortcode('box_repeater', 'box_repeater_funct');
function box_repeater_funct($atts) {
	ob_start();
	$atts = shortcode_atts(array(
		'id' => '',
		'custom_css_classes' => '',
		'box_repeater_items' => '',
		'custom_code' => ''
		//'disable_carousel' => ''
	), $atts, 'box_repeater');

	$id = $atts['id'];
	$custom_css_classes = $atts['custom_css_classes'];
	$custom_code = strip_tags($atts['custom_code']);

	/*
	$disable_carousel = false;
	if(isset($atts['disable_carousel']) && $atts['disable_carousel'] != false ) {
		$disable_carousel = true;
	}
	*/


	if(function_exists('vc_param_group_parse_atts')){
	$items = vc_param_group_parse_atts($atts['box_repeater_items']);
	}
	?>
      <div class="box-repeater">
          <?php if ($items) { ?>
              <div id="<?php if(isset($id)) echo $id; ?>" class="box-repeater-items clients_carousel owl-carousel owl-theme <?php if(isset($custom_css_classes)) echo $custom_css_classes; ?>">
                  <?php  foreach ($items as  $item) {
					
					if (isset($item['box_repeater_items_link'])) {
						$link = vc_build_link($item['box_repeater_items_link']);
					} ?>

                      <div class="client_item <?php if (isset($item['box_repeater_items_css'])) { echo $item['box_repeater_items_css']; } ?>">
					  <div class="client_item_inner">

					  	<?php if (isset($item['box_repeater_items_link'])) { ?>
					  	<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" title="<?php echo $item['box_repeater_items_title']; ?>">
						<?php } ?>

						<?php 
						if(isset($item['box_repeater_items_img']) && wp_get_attachment_image($item['box_repeater_items_img'], 'full')) {
							echo wp_get_attachment_image($item['box_repeater_items_img'], 'full'); 
						} else {
							echo "<h5 class='title'>".$item['box_repeater_items_title']."</h5>";
						}
						?>

						<?php if (isset($item['box_repeater_items_link'])) { ?>
				  		</a>
						<?php } ?>

						</div>
                      </div>
                  <?php
				} ?>
              </div>
          <?php } ?>

      </div>

	<?php if ($custom_code) { ?>
		<script>
		jQuery(document).ready(function ($) {
			$('#<?php echo $id; ?>').owlCarousel({
					<?php echo $custom_code; ?>
			});
		});
		</script>
      <?php
	}

	  $result = ob_get_clean();
	return $result;
}




// shortcode video Element
function nc_vc_video_preview_custom() {
	// Title
	vc_map(
		array(
			'name' => __('Video Preview'),
			'base' => 'nc_video_preview',
			'category' => __('Content', 'nextcloud'),
			'params' => array(

				array(
					"type" => "textfield",
					"heading" => esc_html__("ID", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "id",
					"value" => "",
				),

				array(
					"type" => "attach_image",
					"holder" => "div",
					"class" => "",
					"heading" => __("Video Thumbnail", "nextcloud"),
					"param_name" => "video_thumb",
					"value" => __("", "nextcloud"),
				),

				
				array(
					"type" => "textfield",
					"heading" => esc_html__("Video URL", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "video_url",
					"value" => "",
				),

				array(
					"type" => "textfield",
					"heading" => esc_html__("Title", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "title",
					"value" => "",
				),

				array(
					"type" => "textfield",
					"heading" => esc_html__("Subtitle", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "subtitle",
					"value" => "",
				),


				array(
					"type" => "textfield",
					"heading" => esc_html__("Custom CSS class", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "css_class",
					"value" => "",
				)
				
				

			)
		)
	);
}

add_action('vc_before_init', 'nc_vc_video_preview_custom');
function nc_video_preview_function($atts, $content) {
	$attributes = shortcode_atts(
		array(
			'id' => '',
			'video_thumb' => '',
			'video_url' => '',
			'title' => '',
			'subtitle' => '',
			'css_class' => ''
		), $atts);

	$css_class = '';
	if(isset($attributes['css_class'])) {
		$css_class = $attributes['css_class'];
	}	
	$id = '';
	if(isset($attributes['id'])) {
		$id = $attributes['id'];
	}

	$return = '<div id="'.$id.'" class="nc_video_preview_box popup-video '.$css_class.'">';
	$image = wp_get_attachment_image($attributes['video_thumb'], 'large');
	$return .= '<a href="'.$attributes['video_url'].'" title="'.$attributes['title'].'" class="">';
	$return .= '<div class="overlay"></div>';
	$return .= $image;

	$return .= '<div class="video_text">
				<div class="icon">
				<svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" id="" version="1.1" viewBox="0 0 24 24">
				<path class="st0" d="M9.5,16.5l7-4.5-7-4.5v9ZM12,22c-1.383,0-2.683-.263-3.9-.788s-2.275-1.238-3.175-2.138-1.613-1.958-2.138-3.175-.788-2.517-.788-3.9.263-2.683.788-3.9,1.238-2.275,2.138-3.175,1.958-1.613,3.175-2.138,2.517-.788,3.9-.788,2.683.263,3.9.788,2.275,1.238,3.175,2.138,1.613,1.958,2.138,3.175.788,2.517.788,3.9-.263,2.683-.788,3.9-1.238,2.275-2.138,3.175-1.958,1.613-3.175,2.138-2.517.788-3.9.788ZM12,20c2.233,0,4.125-.775,5.675-2.325s2.325-3.442,2.325-5.675-.775-4.125-2.325-5.675-3.442-2.325-5.675-2.325-4.125.775-5.675,2.325-2.325,3.442-2.325,5.675.775,4.125,2.325,5.675,3.442,2.325,5.675,2.325Z"></path>
				</svg>
				</div>
				<div class="video_desc">
				<div class="title">'.$attributes['title'].'</div>
				<div class="subtitle">'.$attributes['subtitle'].'</div>
				</div>
				</div>';
				

	$return .= '</a>';
	$return .= '</div>';
	return $return;
}
add_shortcode('nc_video_preview', 'nc_video_preview_function');


// shortcode Iconbox
function nc_iconbox_element_custom() {
	// Title
	vc_map(
		array(
			'name' => __('Iconbox'),
			'base' => 'nc_iconbox',
			'category' => __('Content', 'nextcloud'),
			'params' => array(
				
				array(
					"type" => "iconpicker",
					"holder" => "div",
					"class" => "",
					"heading" => __("Icon", "nextcloud"),
					"param_name" => "icon",
					"value" => __("", "nextcloud"),
				),


				array(
					"type" => "attach_image",
					"holder" => "img",
					"class" => "",
					"heading" => __("Image Icon", "nextcloud"),
					"param_name" => "image_icon",
					"value" => __("", "nextcloud"),
				),

				array(
					"type" => "vc_link",
					"holder" => "div",
					"class" => "iconbox_link",
					//"admin_label" => false,
					"heading" => __("Link", "nextcloud"),
					"param_name" => "link",
					"value" => __("", "nextcloud"),
				),


				//top_title
				array(
					"type" => "textfield",
					"heading" => esc_html__("Top title", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "top_title",
					"admin_label" => true,
					"value" => "",
				),

				array(
					"type" => "textfield",
					"heading" => esc_html__("Title", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "title",
					"admin_label" => true,
					"value" => "",
				),

				array(
					"type" => "textfield",
					"heading" => esc_html__("Subtitle", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "subtitle",
					"admin_label" => true,
					"value" => "",
				),

				array(
					"type" => "textarea",
					"heading" => esc_html__("Description", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "description",
					"admin_label" => true,
					"value" => "",
				),

				array(
					"type" => "attach_images",
					"holder" => "div",
					"class" => "attached_images",
					"heading" => __("Attached images", "nextcloud"),
					"param_name" => "attached_images",
					"value" => __("", "nextcloud"),
				),

				array(
					"type" => "textfield",
					"heading" => esc_html__("Custom CSS Classes", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "css_classes",
					"value" => "",
				),


				array(
					"type" => "textfield",
					"heading" => esc_html__("See more label", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "see_more_label",
					"value" => "",
				),
				
				

			)
		)
	);
}
add_action('vc_before_init', 'nc_iconbox_element_custom');

function nc_iconbox_function($atts, $content) {
	$attributes = shortcode_atts(
		array(
			'icon' => '',
			'image_icon' => '',
			'link' => '',
			'top_title' => '',
			'title' => '',
			'subtitle' => '',
			'description' => '',
			'attached_images' => '',
			'css_classes' => '',
			'see_more_label' => '',
		), $atts);
	$link = vc_build_link($attributes['link']);

	//remove_filter('the_content', 'wptexturize');

	if($attributes['see_more_label'] != ''){
		$see_more_label = $attributes['see_more_label'];
	} else {
		$see_more_label = __('See more', 'nextcloud');
	}

	$return = '<div class="nc_iconbox '.$attributes['css_classes'].' ">';

	if ($attributes['image_icon']) {
		$icon = wp_get_attachment_image($attributes['image_icon'], 'large');
	} elseif ($attributes['icon']) {
		$icon = '<i class="'.$attributes['icon'].'"></i>';
	} else {
		$icon = "";
	}
	

	if($attributes['link'] && $link['url'] != '#no_scroll' ){
		$return .= '<a href="'.$link['url'].'" target="'.$link['target'].'" title="'.$link['title'].'" class="">';
	}else {
		//$return .= '<a href="#no_scroll" class="no_scroll">';
		$return .= '<div class="link_replacement no_scroll">';
	}
	
	$custom_class= '';
	if(!$attributes['link'] || $link['url'] == '#no_scroll' ){
		$custom_class= ' no-link';
	}


	if(isset($attributes['description'])) {
		$desc = $attributes['description'];
		if( str_contains($attributes['css_classes'], 'key_code')) {
			$desc = "<code>".$attributes['description']."</code>";
		}
	}
	
	$return .= '<div class="iconbox_container '.$custom_class.'">';
	$return .= '<div class="icon">';
	
	if ($attributes['attached_images'] != '') {
		$attached_images_ids = explode(',',$attributes['attached_images']);
		$return .= '<div class="attached_images">';
		foreach($attached_images_ids as $attached_images_id) {
			$return .= '<div class="img_holder"><img src="'.wp_get_attachment_image_url($attached_images_id, 'full').'" class="attached_image" /></div>';
		}
		$return .= '</div>';
	} else {
		$return .= $icon;
	}
	
	$title = htmlspecialchars_decode($attributes['title']);

	$return .= '</div>
	<div class="title_and_description">';

	if(isset($attributes['top_title']) && $attributes['top_title'] != '' ){
		$return .= '<span class="top_title">'.$attributes['top_title'].'</span>';
	}

	$return .='<h4 class="title">'.wp_kses_post($title).'</h4>';

	if(isset($attributes['subtitle']) && $attributes['subtitle'] != '' ){
		$return .= '<h5 class="subtitle">'.$attributes['subtitle'].'</h5>';
	}

	if($desc) {
		$return .= '<div class="description">'.$desc.'</div>';
	}

	if($attributes['link'] && $link['url'] != '#no_scroll' ){
		$return .= '<span class="see_more">'.$see_more_label.' <svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 24 24"><path class="st0" d="M12.6,12l-4.6-4.6,1.4-1.4,6,6-6,6-1.4-1.4,4.6-4.6Z"/></svg></span>';
	}
	$return .= '</div>';


	$return .= '</div>';

	if($attributes['link'] && $link['url'] != '#no_scroll' ){
		$return .= '</a>';
	}else {
		$return .= '</div>';
	}

	
	$return .= '</div>';

	//add_filter('the_content', 'wptexturize');
	return $return;
}
add_shortcode('nc_iconbox', 'nc_iconbox_function');




// shortcode Eventbox
function nc_eventbox_element_custom() {
	// Title
	vc_map(
		array(
			'name' => __('Eventbox'),
			'base' => 'nc_eventbox',
			'category' => __('Content', 'nextcloud'),
			'params' => array(

				array(
					"type" => "attach_image",
					"holder" => "img",
					"class" => "",
					"heading" => __("Event Image", "nextcloud"),
					"param_name" => "event_image",
					"value" => __("", "nextcloud"),
				),

				array(
					"type" => "vc_link",
					"holder" => "div",
					"class" => "iconbox_link",
					"admin_label" => true,
					"heading" => __("Link", "nextcloud"),
					"param_name" => "link",
					"value" => __("", "nextcloud"),
				),


				array(
					"type" => "textfield",
					"heading" => esc_html__("Date", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "date",
					"value" => "",
				),

				array(
					"type" => "textfield",
					"heading" => esc_html__("Venue", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "venue",
					"value" => "",
				),


				array(
					"type" => "textfield",
					"heading" => esc_html__("Event Title", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "title",
					"value" => "",
				),

				array(
					"type" => "textarea",
					"heading" => esc_html__("Description", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "description",
					"value" => "",
				),

				array(
					"type" => "textfield",
					"heading" => esc_html__("Button label", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "btn_label",
					"value" => "",
				),

				array(
					"type" => "textfield",
					"heading" => esc_html__("Custom CSS Classes", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "css_classes",
					"value" => "",
				),
				
				

			)
		)
	);
}
add_action('vc_before_init', 'nc_eventbox_element_custom');

function nc_eventbox_function($atts, $content) {
	$attributes = shortcode_atts(
		array(
			'event_image' => '',
			'link' => '',
			'date' => '',
			'venue' => '',
			'title' => '',
			'description' => '',
			'btn_label' => __('See more', 'nextcloud'),
			'css_classes' => '',
		), $atts);

	$link = vc_build_link($attributes['link']);
	$return = '<div class="nc_eventbox '.$attributes['css_classes'].'">';

	if (isset($attributes['event_image'])) {
		$image = wp_get_attachment_image($attributes['event_image'], 'large', array( 'class' => 'feat_img' ));
	} else {
		$image = "";
	}
				
	$return .= '<a href="'.$link['url'].'" target="'.$link['target'].'" title="'.$link['title'].'" class="">';
	$return .= '<div class="image">'.$image.'</div>';


	$return .= '<div class="event_box_container">';

	if(isset($attributes['title'])){
		$return .= '<h4 class="title">'.$attributes['title'].'</h4>';
	}
				
	$return .= '<div class="event_meta">
				<div class="date"><i class="fas fa-calendar-alt"></i> '.$attributes['date'].'</div>
				<div class="venue"><i class="fas fa-map-marker-alt"></i> '.$attributes['venue'].'</div>
	</div>';

	if(isset($attributes['description'])) {
		$return .= '<div class="description">'.$attributes['description'].'</div>';
	}
	
	
	
	if($link)
		$return .= '<span class="btn see_more">'.$attributes['btn_label'].' <i class="fas fa-angle-right"></i></span>';		

	$return .= '</div></a>';

	$return .= '</div>';
	return $return;
}
add_shortcode('nc_eventbox', 'nc_eventbox_function');


//Repeater Features Carousel
add_action('vc_before_init', 'features_repeater_items_funct');
function features_repeater_items_funct() {
	vc_map(
		  array(
		  	"name" => __("Features Carousel", "nextcloud"), // Element name
		  	"base" => "features_carousel", // Element shortcode
		  	"class" => "features_repeater",
		  	"category" => __('Carousel', 'nextcloud'),
		  	'params' => array(

				array(
					"type" => "textfield",
					"heading" => esc_html__("ID", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "id",
					"value" => "",
				),

				array(
					"type" => "textfield",
					"heading" => esc_html__("Custom items number", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "custom_items_no",
					"value" => "",
				),

				array(
					"type" => "checkbox",
					"heading" => esc_html__("Gallery?", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "gallery",
					"value" => "",
				),

		  		array(
		  			'type' => 'param_group',
		  			'param_name' => 'box_repeater_items',
		  			'params' => array(
		  				array(
		  					"type" => "attach_image",
		  					"holder" => "img",
		  					"class" => "",
		  					"heading" => __("Feature Image", "nextcloud"),
		  					"param_name" => "feature_image",
		  					"value" => __("", "nextcloud"),
		  				),
		  				array(
		  					"type" => "textfield",
		  					"holder" => "div",
		  					"class" => "",
		  					"admin_label" => true,
		  					"heading" => __("Feature Title", "nextcloud"),
		  					"param_name" => "title",
		  					"value" => __("", "nextcloud"),
		  				),

						array(
							"type" => "textarea",
							"heading" => esc_html__("Description", "nextcloud"),
							"description" => esc_html__("", "nextcloud"),
							"param_name" => "description",
							"value" => "",
						),

		  				array(
		  					"type" => "vc_link",
		  					"holder" => "div",
		  					"class" => "client_link",
		  					"admin_label" => true,
		  					"heading" => __("Link", "nextcloud"),
		  					"param_name" => "link",
		  					"value" => __("", "nextcloud"),
		  				),

						array(
							"type" => "textfield",
							"heading" => esc_html__("Video URL", "nextcloud"),
							"description" => esc_html__("", "nextcloud"),
							"param_name" => "video_url",
							"value" => "",
						),

						array(
							"type" => "textfield",
							"heading" => esc_html__("Custom CSS Classes", "nextcloud"),
							"description" => esc_html__("", "nextcloud"),
							"param_name" => "custom_css",
							"value" => "",
						),

						array(
							"type" => "checkbox",
							"heading" => esc_html__("No Overlay", "nextcloud"),
							"description" => esc_html__("", "nextcloud"),
							"param_name" => "no_overlay",
							"value" => "",
						),

						array(
							"type" => "checkbox",
							"heading" => esc_html__("Custom label", "nextcloud"),
							"description" => esc_html__("", "nextcloud"),
							"param_name" => "custom_label",
							"value" => array(
								esc_html__("Yes", "nextcloud") => "yes" 
							)
						),

						array(
							"type" => "textfield",
							"heading" => esc_html__("Label", "nextcloud"),
							"description" => esc_html__("", "nextcloud"),
							"param_name" => "label",
							"dependency" => array(
								"element" => "custom_label",
								"value" => "yes"
							),
							"value" => "",
						),

						array(
							"type" => "dropdown",
							"holder" => "div",
							"class" => "",
							"admin_label" => false,
							"heading" => __("Style", "nextcloud"),
							"param_name" => "label_style",
							"dependency" => array(
								"element" => "custom_label",
								"value" => "yes"
							),
							"value" => array(
								__("White", "nextcloud") => "white"
							),
							"std" => "white"
						)

		  			)
		  		),
		  	)
		  )
	  );
}

add_shortcode('features_carousel', 'features_carousel_funct');
function features_carousel_funct($atts) {
	ob_start();
	$atts = shortcode_atts(array(
		'box_repeater_heading' => '',
		'id' => '',
		'gallery' => '',
		'custom_items_no' => '',
		'box_repeater_items' => '',
	), $atts, 'box_repeater');

	$id = "features_carousel";
	if(isset($atts['id']) && $atts['id'] != '') {
		$id = $atts['id'];
	}

	$gallery = false;
	if(isset($atts['gallery']) && $atts['gallery'] != false ) {
		$gallery = true;
	}

	if(isset($atts['custom_items_no'])) {
		$custom_items_no = $atts['custom_items_no'];
	}

	if(function_exists('vc_param_group_parse_atts')){
		$items = vc_param_group_parse_atts($atts['box_repeater_items']);
	}
	
	?>
      <div class="box-repeater <?php if($gallery) { echo " gallery "; }?>">
          <?php if ($items) { ?>
              <div id="<?php echo $id; ?>" class="features_carousel owl-carousel owl-theme">
                  <?php  foreach ($items as  $item) {
				
					if (isset($item['link'])) {
						$link = vc_build_link($item['link']);
					}
					?>
                      <div class="feature_item <?php if(isset($item['custom_css'])) { echo $item['custom_css']; }; ?>">

					  	<?php if ( isset($item['link']) && !isset($item['video_url']) ) { ?>
					  		<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" title="<?php echo $item['title']; ?>">
						<?php } ?>

						<div class="feature_image">
							<?php if ( isset($item['video_url']) && !isset($item['no_overlay']) ) { ?>
								<a href="<?php echo $item['video_url']; ?>" title="<?php echo $item['title']; ?>" class="popup-video" data-type="video">
							<?php } ?>		

							<?php if ( isset($item['video_url']) && !isset($item['no_overlay']) ) { ?>
								<div class="play-icon">
								<svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" id="" version="1.1" viewBox="0 0 24 24">
								<path class="st0" d="M9.5,16.5l7-4.5-7-4.5v9ZM12,22c-1.383,0-2.683-.263-3.9-.788s-2.275-1.238-3.175-2.138-1.613-1.958-2.138-3.175-.788-2.517-.788-3.9.263-2.683.788-3.9,1.238-2.275,2.138-3.175,1.958-1.613,3.175-2.138,2.517-.788,3.9-.788,2.683.263,3.9.788,2.275,1.238,3.175,2.138,1.613,1.958,2.138,3.175.788,2.517.788,3.9-.263,2.683-.788,3.9-1.238,2.275-2.138,3.175-1.958,1.613-3.175,2.138-2.517.788-3.9.788ZM12,20c2.233,0,4.125-.775,5.675-2.325s2.325-3.442,2.325-5.675-.775-4.125-2.325-5.675-3.442-2.325-5.675-2.325-4.125.775-5.675,2.325-2.325,3.442-2.325,5.675.775,4.125,2.325,5.675,3.442,2.325,5.675,2.325Z"></path>
								</svg>
								
							</div>
								<div class="overlay"></div>
							<?php } ?>

							<?php //if no video url nor Link
								if ( !isset($item['video_url']) && !isset($item['link']) && !isset($item['no_overlay'])  ) { ?>
								<a href="<?php echo wp_get_attachment_image_url($item['feature_image'], 'full'); ?>" title="<?php echo $item['title']; ?>" class="popup-screenshot" data-type="image">
								<div class="screenshot-icon">

									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill=""><path d="M120-120v-240h80v104l124-124 56 56-124 124h104v80H120Zm480 0v-80h104L580-324l56-56 124 124v-104h80v240H600ZM324-580 200-704v104h-80v-240h240v80H256l124 124-56 56Zm312 0-56-56 124-124H600v-80h240v240h-80v-104L636-580ZM480-400q-33 0-56.5-23.5T400-480q0-33 23.5-56.5T480-560q33 0 56.5 23.5T560-480q0 33-23.5 56.5T480-400Z"/></svg>

								</div>
								<div class="overlay-screenshot"></div>
								<?php 
								}
							?>

							<?php if(isset($item['custom_label'])) { ?>
							<div class="custom_label <?php if(isset($item['label_style'])) { echo $item['label_style']; } ?>">
								<?php if(isset($item['label'])) { echo $item['label']; } ?>
							</div>
							<?php } ?>

							
							<?php echo wp_get_attachment_image($item['feature_image'], 'large'); ?>

							<?php //if no video url nor Link
								if ( !isset($item['video_url']) && !isset($item['link']) && !isset($item['no_overlay'])  ) { ?>
								</a>
							<?php } ?>


							<?php if (isset($item['video_url']) && !isset($no_overlay) ) { ?>
							</a>
							<?php } ?>
						</div>


						<?php if (isset($item['link']) && !isset($item['video_url']) ) { ?>
				  		</a>
						<?php } ?>
						

						<?php if(!$gallery) { ?>
						<div class="feature_inner <?php if (isset($item['link'])) { echo " with_link"; } ?>">
							<h3 class="title">
							<?php if (isset($item['link'])) { ?>
							<a href="<?php echo $link['url']; ?>" title="<?php echo $item['title']; ?>" class="">
							<?php } ?><?php echo $item['title']; ?>
							<?php if (isset($item['link'])) { ?>
							</a>
							<?php } ?>
							</h3>

							<?php if(isset($item['description'])) { ?>
							<div class="description"><?php echo $item['description']; ?></div>
							<?php } ?>
							
							<?php if (isset($item['link'])) { ?>
							<span class="read_more">
							<?php if (isset($item['link'])) { ?>
									<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" title="<?php echo $item['title']; ?>" class="">
								<?php } ?>
								<?php 
								if(isset($link['title'])){
									echo $link['title'];
								}
								else {
									echo __('Read more', 'nextcloud'); 
								}
								?> <i class="fas fa-angle-right"></i>
								<?php if ($item['link']) { ?>
									</a>
								<?php } ?>
							</span>
							<?php } ?>
						</div>
						<?php } ?>


                      	</div>
                  <?php } ?>
              </div>

			  <script>
				jQuery(document).ready(function ($) {
						$('#<?php echo $id; ?>').owlCarousel({
							loop:false,
							autoplay: false,
							margin:30,
							dots: false,
							nav:true,
							navText : ['<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 14.299 22.409"><path fill="#fff" class="st0" d="M11.25,21.205L1.25,11.205,11.25,1.205l1.8,1.8L4.85,11.205l8.2,8.2-1.8,1.8h0Z"/></svg>','<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 14.299 22.409"><path fill="#fff" class="st0" d="M3.05,21.205l-1.8-1.8,8.2-8.2L1.25,3.005l1.8-1.8,10,10L3.05,21.205h0Z"/></svg>'],
							responsive:{
								0:{
									items:1
								},
								600:{
									items:2
								},
								1000:{
									items: <?php 
									if($custom_items_no) {
										echo $custom_items_no;
									}else {
										echo "3";
									}?>
								}
							},
							onInitialized: function (event) {
								// Remove role="presentation" from arrows
								$('.owl-prev, .owl-next').removeAttr('role');
								$('.owl-prev, .owl-next').attr('tabindex', 0);

								// Move .owl-nav before the .owl-stage-outer (which contains the items)
								const $owl = $(event.target);
								const $nav = $owl.find('.owl-nav');
								const $stageOuter = $owl.find('.owl-stage-outer');
								$nav.insertBefore($stageOuter);
							}
						});
				});
			</script>

          <?php } ?>

      </div>
      <?php
	  $result = ob_get_clean();
	return $result;
}


//Repeater Hub Circle
add_action('vc_before_init', 'hub_circle_items_funct');
function hub_circle_items_funct() {
	vc_map(
		  array(
		  	"name" => __("Hub Circle", "nextcloud"), // Element name
		  	"base" => "hub_circle", // Element shortcode
		  	"class" => "hub_circle",
		  	"category" => __('Carousel', 'nextcloud'),
		  	'params' => array(

				array(
					"type" => "attach_image",
					"holder" => "div",
					"class" => "",
					"heading" => __("Static middle image", "nextcloud"),
					"param_name" => "static_image",
					"value" => __("", "nextcloud"),
				),


		  		array(
		  			'type' => 'param_group',
		  			'param_name' => 'box_repeater_items',
		  			'params' => array(
		  				array(
		  					"type" => "attach_image",
		  					"holder" => "img",
		  					"class" => "",
		  					"heading" => __("Image", "nextcloud"),
		  					"param_name" => "image",
		  					"value" => __("", "nextcloud"),
		  				),
		  				array(
		  					"type" => "textfield",
		  					"holder" => "div",
		  					"class" => "",
		  					"admin_label" => true,
		  					"heading" => __("Title", "nextcloud"),
		  					"param_name" => "title",
		  					"value" => __("", "nextcloud"),
		  				)


		  			)
		  		),
		  	)
		  )
	  );
}

add_shortcode('hub_circle', 'hub_circle_funct');
function hub_circle_funct($atts) {
	ob_start();
	$atts = shortcode_atts(array(
		'static_image' => '',
		'box_repeater_items' => '',
	), $atts, 'box_repeater');

	$image = wp_get_attachment_image($atts['static_image'], 'large');
	
	if(function_exists('vc_param_group_parse_atts')){
		$items = vc_param_group_parse_atts($atts['box_repeater_items']);
	}
	
	?>
      <div class="hub_circle">

          <?php if ($items) { ?>
              <div class="hub_circle_inner">


				<div class="img_static">
				<?php echo $image; ?>
				</div>


				<div class="floating_icons">
                <?php
				 	$count = count($items);
					$deg = 360 / $count;
					$i = 0;
				  	foreach ($items as  $item) {
					$class = "deg".($deg*$i);
					?>
                    <div class="floating_icon <?php echo $class; ?>">

					  	<?php echo wp_get_attachment_image($item['image'], 'full'); ?>
						
						<h5 class="title">
						<?php echo $item['title']; ?>
						</h5>

                    </div>
                <?php 
					$i++;
				} ?>
				</div>


              </div>
          <?php } ?>

      </div>
      <?php
	  $result = ob_get_clean();
	return $result;
}










//Repeater Testimonials Carousel
add_action('vc_before_init', 'testimonials_repeater_items_funct');
function testimonials_repeater_items_funct() {
	vc_map(
		  array(
		  	"name" => __("Testimonials Carousel", "nextcloud"), // Element name
		  	"base" => "testimonials_carousel", // Element shortcode
		  	"class" => "testimonials_carousel",
		  	"category" => __('Carousel', 'nextcloud'),
		  	'params' => array(

		  		array(
		  			'type' => 'param_group',
		  			'param_name' => 'box_repeater_items',
		  			'params' => array(
		  				array(
		  					"type" => "attach_image",
		  					"holder" => "img",
		  					"class" => "",
		  					"heading" => __("Testimonial Image", "nextcloud"),
		  					"param_name" => "image",
		  					"value" => __("", "nextcloud"),
		  				),
		  				array(
		  					"type" => "textfield",
		  					"holder" => "div",
		  					"class" => "",
		  					"admin_label" => true,
		  					"heading" => __("Testimonial Title", "nextcloud"),
		  					"param_name" => "title",
		  					"value" => __("", "nextcloud"),
		  				),

						array(
							"type" => "textfield",
							"holder" => "div",
							"class" => "",
							"admin_label" => true,
							"heading" => __("Position", "nextcloud"),
							"param_name" => "position",
							"value" => __("", "nextcloud"),
						),

						array(
							"type" => "textarea",
							"heading" => esc_html__("Quote", "nextcloud"),
							"description" => esc_html__("", "nextcloud"),
							"param_name" => "quote",
							"value" => "",
						),

		  				array(
		  					"type" => "vc_link",
		  					"holder" => "div",
		  					"class" => "client_link",
		  					"admin_label" => true,
		  					"heading" => __("Link", "nextcloud"),
		  					"param_name" => "link",
		  					"value" => __("", "nextcloud"),
		  				)


		  			)
		  		),

				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"admin_label" => true,
					"heading" => __("Custom CSS classes", "nextcloud"),
					"param_name" => "custom_css",
					"value" => __("", "nextcloud"),
				)

		  	)
		  )
	  );
}


add_shortcode('testimonials_carousel', 'testimonials_carousel_funct');
function testimonials_carousel_funct($atts) {
	ob_start();
	$atts = shortcode_atts(array(
		'box_repeater_heading' => '',
		'box_repeater_items' => '',
		'custom_css' => ''
	), $atts, 'box_repeater');

	if(function_exists('vc_param_group_parse_atts')){
		$items = vc_param_group_parse_atts($atts['box_repeater_items']);
	}
	?>
      <div class="box-repeater">
          <?php if ($items) { ?>
			<div class="case_studies <?php echo $atts['custom_css']; ?>">
              <div class="testimonials_carousel owl-carousel owl-theme">
                  <?php  foreach ($items as  $item) {
					
					$item_link = false;

					if ( isset($item['link']) ) {
						$item_link = true;
						$link = vc_build_link($item['link']);
					}
					?>
                      <div class="case_study testimonial">
						<div class="vc_column-inner <?php if ( !$item_link  ) { echo "no_button"; } ?>">
							<div class="wpb_wrapper">
								
							
								<div class="wpb_single_image wpb_content_element vc_align_left image_top">
									<figure class="wpb_wrapper vc_figure">
										<?php if ( $item_link ) { ?>
										<a href="<?php echo $link['url']; ?>" title="<?php echo $item['title']; ?>" target="<?php echo $link['target']; ?>" class="vc_single_image-wrapper   vc_box_border_grey">
										<?php } ?>

											<?php echo wp_get_attachment_image($item['image'], 'full'); ?>

										<?php if ( $item_link ) { ?>
										</a>
										<?php } ?>

									</figure>
								</div>

								<div class="wpb_text_column wpb_content_element organization testimonial_quote">
									<div class="wpb_wrapper">
										<h4><?php echo $item['title']; ?></h4>
										<?php if (isset($item['position'])) {
											echo "<span class='position'>";
											echo $item['position'];
											echo "</span>";
										} ?>
									</div>
								</div>
								
								<?php if( isset($item['quote']) ) { ?>
								<div class="wpb_text_column wpb_content_element quote">
									<div class="wpb_wrapper">
										<p><?php echo $item['quote']; ?></p>
									</div>
								</div>
								<?php }
								
								if ( $item_link  ) { ?>
								<div class="vc_btn3-container  btn-main btn-small vc_btn3-center">
									<a class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-rounded vc_btn3-style-modern vc_btn3-icon-right vc_btn3-color-grey" href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" title="<?php echo $item['title']; ?>"><?php echo __('Learn more','nextcloud'); ?> <i class="vc_btn3-icon fas fa-angle-right"></i>
									</a>
								</div>
								<?php } ?>

							</div>
						</div>
					</div>

				<?php } ?>
              </div>
			</div>
        <?php } ?>

      </div>
      <?php
	  $result = ob_get_clean();
	return $result;
}



//Repeater Testimonials Carousel
add_action('vc_before_init', 'iconboxes_repeater_items_funct');
function iconboxes_repeater_items_funct() {
	vc_map(
		  array(
		  	"name" => __("Iconboxes Carousel", "nextcloud"), // Element name
		  	"base" => "iconboxes_carousel", // Element shortcode
		  	"class" => "iconboxes_carousel",
		  	"category" => __('Carousel', 'nextcloud'),
		  	'params' => array(


				array(
					"type" => "textfield",
					"heading" => esc_html__("ID", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "id",
					"value" => "",
				),


		  		array(
		  			'type' => 'param_group',
		  			'param_name' => 'box_repeater_items',
		  			'params' => array(

						array(
							"type" => "iconpicker",
							"holder" => "icon",
							"class" => "",
							"heading" => __("Icon", "nextcloud"),
							"param_name" => "icon",
							"value" => __("", "nextcloud"),
						),

		  				array(
		  					"type" => "attach_image",
		  					"holder" => "img",
		  					"class" => "",
		  					"heading" => __("Icon Image", "nextcloud"),
		  					"param_name" => "image",
		  					"value" => __("", "nextcloud"),
		  				),


						array(
		  					"type" => "textfield",
		  					"holder" => "div",
		  					"class" => "",
		  					"admin_label" => true,
		  					"heading" => __("Top title", "nextcloud"),
		  					"param_name" => "top_title",
		  					"value" => __("", "nextcloud"),
		  				),


		  				array(
		  					"type" => "textfield",
		  					"holder" => "div",
		  					"class" => "",
		  					"admin_label" => true,
		  					"heading" => __("Title", "nextcloud"),
		  					"param_name" => "title",
		  					"value" => __("", "nextcloud"),
		  				),

						array(
							"type" => "textarea",
							"heading" => esc_html__("Description", "nextcloud"),
							"description" => esc_html__("", "nextcloud"),
							"param_name" => "description",
							"value" => "",
						),

		  				array(
		  					"type" => "vc_link",
		  					"holder" => "div",
		  					"class" => "client_link",
		  					"admin_label" => false,
		  					"heading" => __("Link", "nextcloud"),
		  					"param_name" => "link",
		  					"value" => __("", "nextcloud"),
						),
						array(
		  					"type" => "checkbox",
		  					"holder" => "div",
		  					"class" => "link_label",
		  					"admin_label" => true,
		  					"heading" => __("Link label?", "nextcloud"),
		  					"param_name" => "link_label",
		  					"value" => __("", "nextcloud"),
		  				),

						array(
		  					"type" => "textfield",
		  					"holder" => "div",
		  					"class" => "",
		  					"admin_label" => true,
		  					"heading" => __("Custom CSS classes", "nextcloud"),
		  					"param_name" => "css_classes",
		  					"value" => __("", "nextcloud"),
		  				),


		  			)
		  		),

				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"admin_label" => true,
					"heading" => __("Custom CSS classes", "nextcloud"),
					"param_name" => "custom_css",
					"value" => __("", "nextcloud"),
				),

				array(
					"type" => "checkbox",
					"heading" => esc_html__("Disable carousel", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "disable_carousel",
					"value" => array( 
						esc_html__("Yes", "nextcloud") => "yes" 
					)
				),
				
				array(
					"type" => "textfield",
					"heading" => esc_html__("Custom slider items", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "custom_slider_items",
					"value" => "",
					"dependency" => array(
						"element" => "disable_carousel",
						"is_empty" => true,
					)
				),

				array(
					"type" => "textarea_raw_html",
					"heading" => esc_html__("Custom code for carousel", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "custom_code",
					"value" => "",
					"dependency" => array(
						"element" => "disable_carousel",
						"is_empty" => true,
					)
				),


				array(
						'type' => 'dropdown',
						'heading' => 'Type',
						'param_name' => 'type',
						'value' => array(
							'Default' => 'default',
							'Flip on hover' => 'flip_on_hover'
						),
						"std" => "default", // default selected value (optional)
				),

			

				

		  	)
		  )
	  );
}

add_shortcode('iconboxes_carousel', 'iconboxes_carousel_funct');
function iconboxes_carousel_funct($atts) {
	ob_start();
	$atts = shortcode_atts(array(
		'id' => '',
		'box_repeater_heading' => '',
		'box_repeater_items' => '',
		'custom_css' => '',
		'custom_slider_items' => '',
		'disable_carousel' => '',
		'type' => '',
		'custom_code' => ''
	), $atts, 'box_repeater');

	$id = $atts['id'];
	if(function_exists('vc_param_group_parse_atts')){
		$items = vc_param_group_parse_atts($atts['box_repeater_items']);
	}
	$custom_slider_items = $atts['custom_slider_items'];
	$type = $atts['type'];
	$custom_code = $atts['custom_code'];

	$disable_carousel = false;
	if( isset($atts['disable_carousel']) && $atts['disable_carousel'] == true ) {
		$disable_carousel = true;
	}

	?>
      <div class="iconbox_repeater" data-disable_carousel="<?php echo $disable_carousel; ?>">
          <?php if ($items) { ?>
			<div class="iconboxes <?php 
			if($type == "flip_on_hover") echo " flip_on_hover ";
			echo $atts['custom_css']; ?>">
              
			<?php if(!$disable_carousel) { ?>
			<div id="<?php echo $id; ?>" class="iconboxes_carousel owl-carousel owl-theme" data-items-desktop="<?php if(isset($custom_slider_items)) echo $custom_slider_items; ?>">
			<?php } ?>

                  <?php  foreach ($items as  $item) {
					$has_link = false;
					if ( isset($item['link']) ) {
						$has_link = true;
						$link = vc_build_link($item['link']);
					}

					$css_classes = '';
					if ( isset($item['css_classes']) ) {
						$css_classes = $item['css_classes'];
					}
					?>
                      <div class="nc_iconbox iconbox feature_iconbox <?php echo $css_classes; ?>">
					  	<?php 
						if($has_link) { 
						?>
						<a class="<?php if($type == "flip_on_hover") echo " flip-card "; ?>" href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" title="<?php 
						if(isset($item['title'])) echo $item['title'];
						?>" >
						<?php  } else { ?> 
						<div class="link_replacement no_scroll <?php if($type == "flip_on_hover") echo " flip-card "; ?>">
						<?php } ?>

							<div class="iconbox_container <?php 
							if(!isset($item['title'])) echo " no_title"; 
							if(!$has_link) { echo " no_link "; }
							if(isset($item['link_label'])) { echo " with_label "; }
							if($type == "flip_on_hover") echo " flip-card-inner ";
							?>">
								
								<?php if($type == "flip_on_hover") { ?>
									<div class="flip-card-front">
								<?php } ?>
									

								<?php if ( isset($item['icon']) ) { ?>									
									<div class="icon">
									<i class="<?php echo $item['icon']; ?>"></i>
									</div>
								<?php } ?>

								<?php if ( isset($item['image']) ) { ?>
								<div class="image-icon">
									<div class="vc_single_image-wrapper">
									<?php echo wp_get_attachment_image($item['image'], 'large'); ?>
									</div>
								</div>
								<?php } ?>

								<?php if($type == "flip_on_hover") { ?>
									</div> <!-- flip-card-front --> 
								<?php } ?>


								<?php if($type == "flip_on_hover") { ?>
									<div class="flip-card-back">
								<?php } ?>


								<div class="top_title">
									<span><?php if(isset($item['top_title'])) {
										echo $item['top_title'];
									} ?></span>
								</div>


								<?php if(isset($item['title'])) { ?>
								<div class="title">
									<h4><?php echo $item['title']; ?></h4>
								</div>
								<?php } ?>
								
								<?php if( isset($item['description']) ) { ?>
								<div class="description">
									<?php echo $item['description']; ?>
								</div>
								<?php } ?>

								<?php 
								if(isset($item['link_label'])) {
									?>
									<span class="see_more"><?php 
									if(isset($link['title'])) {
										echo $link['title'];
									} else {
										echo __('See more','nextcloud');
									}
									?> <svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 24 24"><path class="st0" d="M12.6,12l-4.6-4.6,1.4-1.4,6,6-6,6-1.4-1.4,4.6-4.6Z"></path></svg></span>
									<?php
									}
								?>

								<?php if($type == "flip_on_hover") { ?>
									</div> <!-- flip-card-back --> 
								<?php } ?>



							</div>
									
							<?php if($has_link) { ?>
								</a>
							<?php  } else { 
								?> 
								</div>
							<?php } ?>
						
					</div>

				<?php } ?>
			
				<?php if(!$disable_carousel) { ?>
              	</div>
			  	<?php } ?> 

			</div>
        <?php } ?>

      </div>
		<?php
	 		if ($custom_code) { 
				$decoded = base64_decode($custom_code);
				?>
			<script>
			jQuery(document).ready(function ($) {
				$('#<?php echo $id; ?>').owlCarousel({
						<?php echo rawurldecode($decoded); ?>
				});
			});
			</script>
			<?php
			}

	$result = ob_get_clean();
	return $result;
}




//Repeater History Timeline
add_action('vc_before_init', 'timeline_repeater_items_funct');
function timeline_repeater_items_funct() {
	vc_map(
		  array(
		  	"name" => __("History Timeline", "nextcloud"), // Element name
		  	"base" => "history_timeline", // Element shortcode
		  	"class" => "history_timeline",
		  	"category" => __('Repeater', 'nextcloud'),
		  	'params' => array(

		  		array(
		  			'type' => 'param_group',
		  			'param_name' => 'box_repeater_items',
		  			'params' => array(
						

						array(
							"type" => "textfield",
							"holder" => "div",
							"class" => "",
							"admin_label" => true,
							"heading" => __("Date", "nextcloud"),
							"param_name" => "date",
							"value" => __("", "nextcloud"),
						),

		  				array(
		  					"type" => "textfield",
		  					"holder" => "div",
		  					"class" => "",
		  					"admin_label" => true,
		  					"heading" => __("Title", "nextcloud"),
		  					"param_name" => "title",
		  					"value" => __("", "nextcloud"),
		  				),

						array(
							"type" => "textarea_raw_html",
							"heading" => esc_html__("Description", "nextcloud"),
							"description" => esc_html__("", "nextcloud"),
							"param_name" => "description",
							"value" => "",
						),




		  			)
		  		),
		  	)
		  )
	  );
}

add_shortcode('history_timeline', 'history_timeline_funct');
function history_timeline_funct($atts) {
	ob_start();
	$atts = shortcode_atts(array(
		'box_repeater_items' => '',
	), $atts, 'box_repeater');

	if(function_exists('vc_param_group_parse_atts')){
		$items = vc_param_group_parse_atts($atts['box_repeater_items']);
	}
	?>
      <div class="box-repeater">

          <?php if ($items) { ?>
			<div id="timeline-content">
				<ul class="timeline">
                  <?php  foreach ($items as  $item) { ?>
					<li class="event" data-date="<?php echo $item['date']; ?>">
					<div class="arrow-left"></div>
					<h3><?php echo $item['title']; ?></h3>
					<?php if(isset($item['description'])) { ?>
					<p><?php echo $item['description']; ?></p>
					<?php } ?>
					</li>
				<?php } ?>
                </ul>
			</div>
        <?php } ?>

      </div>
      <?php
	  $result = ob_get_clean();
	return $result;
}




//Repeater Media Coverage
add_action('vc_before_init', 'media_coverage_items_funct');
function media_coverage_items_funct() {
	vc_map(
		  array(
		  	"name" => __("Media Coverage", "nextcloud"), // Element name
		  	"base" => "media_coverage", // Element shortcode
		  	"class" => "media_coverage",
		  	"category" => __('Repeater', 'nextcloud'),
		  	'params' => array(

				array(
					"type" => "textfield",
					"heading" => esc_html__("Custom CSS Classes", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "css_classes",
					"value" => "",
				),

				array(
					"type" => "checkbox",
					"heading" => esc_html__("Searchable", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "searchable",
					"value" => ""
				),

				array(
					"type" => "textfield",
					"heading" => esc_html__("Max items", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "max_items",
					"value" => "",
				),

				array(
						'type' => 'dropdown',
						'heading' => 'Type',
						'param_name' => 'type',
						'value' => array(
							'Load more' => 'load_more',
							'Carousel' => 'carousel'
						),
						"std" => "load_more",
				),

		  		array(
		  			'type' => 'param_group',
		  			'param_name' => 'box_repeater_items',
		  			'params' => array(
						array(
							"type" => "attach_image",
							"holder" => "img",
							"class" => "",
							"heading" => __("Image", "nextcloud"),
							"param_name" => "image",
							"value" => __("", "nextcloud"),
						),

		  				array(
		  					"type" => "textarea",
		  					"holder" => "div",
		  					"class" => "",
		  					"admin_label" => true,
		  					"heading" => __("Title", "nextcloud"),
		  					"param_name" => "title",
		  					"value" => __("", "nextcloud"),
		  				),


						array(
		  					"type" => "textfield",
		  					"holder" => "div",
		  					"class" => "",
		  					"heading" => __("Label", "nextcloud"),
		  					"param_name" => "label",
		  					"value" => __("", "nextcloud"),
		  				),


						array(
		  					"type" => "colorpicker",
		  					"holder" => "div",
		  					"class" => "",
		  					"heading" => __("BG Color", "nextcloud"),
		  					"param_name" => "bg_color",
		  					"value" => '#fff', //Default White color
		  				),


						array(
							"type" => "vc_link",
							"holder" => "div",
							"class" => "",
							"admin_label" => false,
							"heading" => __("Link", "nextcloud"),
							"param_name" => "link",
							"value" => __("", "nextcloud"),
						),


						array(
							"type" => "textfield",
							"holder" => "div",
							"class" => "",
							"admin_label" => false,
							"heading" => __("Rating", "nextcloud"),
							"param_name" => "rating",
							"value" => __("", "nextcloud"),
						)


		  			)
		  		)
		  	)
		  )
	  );
}

add_shortcode('media_coverage', 'media_coverage_funct');
function media_coverage_funct($atts) {
	ob_start();
	$atts = shortcode_atts(array(
		'box_repeater_items' => '',
		'css_classes' => 'vc_col-sm-6 vc_col-lg-3 vc_col-md-3',
		'searchable' => false,
		'type' => '',
		'max_items' => 0
		//'columns' => ''
	), $atts, 'box_repeater');
	
	if(function_exists('vc_param_group_parse_atts')){
		$items = vc_param_group_parse_atts($atts['box_repeater_items']);
	}

	$max_items = 0;
	if(isset($atts['max_items'])) {
		$max_items = $atts['max_items']; 
	}

	$searchable = false;
	if(isset($atts['searchable']) && $atts['searchable'] == true ) {
		$searchable = true;
	}

	if(isset($atts['type'])) {
		$type = $atts['type'];
	}
	?>
          <?php if ($items) { ?>

		  	<?php if( $type === 'carousel') { ?>
				<div class="media_coverage_carousel owl-carousel owl-theme">
			<?php } ?>


			<div data-type="<?php echo $type; ?>" class="row media_coverage_posts">

                  <?php 
				  foreach ($items as  $index => $item) {
				  	/*
				  	if( $type === 'carousel') {
						echo '<div class="media_coverage_carousel_item">';
					}
					*/

					$item_link = false;
					if ( isset($item['link']) ) {
						$item_link = true;
						$link = vc_build_link($item['link']);
					}
					?>

					<div id="media-id-<?php echo $index; ?>" class="<?php 
					if ( isset($item['label']) ) {
						echo "label-".$item['label'];
					}

					if( $type != 'carousel' && $max_items!= 0 && $max_items < $index+1) {
						echo " hidden ";
					}

					if(isset($item['rating'])) { echo " with_stars "; }
					echo $atts['css_classes']; ?> media_coverage_post">
						<div class="vc_column-inner">
								<div class="image">
									<figure class="" style="<?php 
									if ( isset($item['bg_color']) ) {
										echo "background-color:".$item['bg_color'];
									}
									?>">
										<?php if ( $item_link ) { ?>
										<a href="<?php echo $link['url']; ?>" title="<?php if(isset($item['title'])) echo $item['title']; ?>" target="<?php echo $link['target']; ?>" class="">
										<?php } ?>
											<?php echo wp_get_attachment_image($item['image'], 'full'); ?>
										<?php if ( $item_link ) { ?>
										</a>
										<?php } ?>
									</figure>
								</div>

								<?php if(isset($item['title'])) { ?>
								<div class="link">
								<?php } ?>
								<?php if ( $item_link ) { ?>
								<a href="<?php echo $link['url']; ?>" title="<?php if(isset($item['title'])) {echo $item['title'];} ?>" target="<?php echo $link['target']; ?>" class="">
								<?php } ?>
								<?php if(isset($item['title'])) {
									echo $item['title'];
								} ?>
								<?php if ( $item_link ) { ?>
								</a>
								<?php } ?>
								<?php if(isset($item['title'])) { ?>
								</div>
								<?php } ?>


								<?php if(isset($item['rating'])) { ?>
									<div class="rating_container">
									<span class="rating_stars">
										<?php 
										$rating = str_replace(',', '.', $item['rating']);
										$rating_int = floor($rating);
										$rating_decimals = $rating - $rating_int;

										for( $i=0; $i<$rating_int; $i++) { ?>
											<i class="fas fa-star"></i>
										<?php } 
										if($rating_decimals > 0) { ?>
										<i class="fas fa-star-half-alt"></i>
										<?php
										}
										?>
									</span>	
									<span class="rating"><?php echo $rating; ?></span>
									</div>
								<?php } ?>

						</div>
				  	</div>

					<?php 
					
					if( $type === 'carousel' && $max_items!= 0 && 
						($index+1) % $max_items === 0
						) { ?>
						</div> <!-- close .media_coverage_posts item -->
						<div data-type="<?php echo $type; ?>" class="row media_coverage_posts"> <!-- open carousel item -->
					<?php } 
					
					?>


				<?php } ?>
			</div>

			<?php if( $type === 'carousel') { ?>
					</div> <!-- close carousel -->

					<script>
					jQuery(document).ready(function($) {
						jQuery('.media_coverage_carousel').owlCarousel({
							loop:false,
							autoplay: false,
							margin: 15,
							dots: true,
							nav: false,
							responsive:{
								0:{
									items:1
								},
								800:{
									items:1
								},
								1000:{
									items: 1
								}
							}
						});
					});
				</script>

			<?php } ?>

			<?php
			if( $type != 'carousel' && $max_items!= 0 && $max_items < count($items)) {
				?>
				<div class="section-button text-center" style="margin-top: 2rem;">
					<button class="c-btn btn-main load_media_coverage" id="load_media_coverage" data-count="<?php echo count($items); ?>" data-limit="<?php echo $max_items; ?>" data-post-type="">
					<?php echo __('Load more','nextcloud'); ?></button>
				</div>
				<script>
					jQuery(document).ready(function($) {
						$('#load_media_coverage').click(function(e){
							e.preventDefault();
							$('.media_coverage_post.hidden').removeClass("hidden");
							$(this).hide();
						});
					});
				</script>
				<?php
			}
			?>

        <?php } ?>
      <?php
	  $result = ob_get_clean();
	return $result;
}



/*
function test_shortcode_pagination_func() {
	//header("Content-Type: text/html");

	$ajaxposts = new WP_Query([
		'post_type' => 'post',
		'posts_per_page' => 9,
		'post_status' => 'publish',
		'orderby' => 'date',
		'order' => 'DESC',
		'paged' => 2,
		'category__not_in' => array(226) //exclude Private category
	]);
  
	$response = '';
  
	if ($ajaxposts->have_posts()) {
		$response .= "<div class='row row-list-blog'>";
		while ($ajaxposts->have_posts()) : $ajaxposts->the_post();
		//$response .= get_template_part('parts/card', 'publication');
		//$response .= get_the_title();
		
		$response .= get_template_part('inc/blog_loop_single');

		endwhile;
		$response .= "</div>";
	} else {
		$response = '';
	}

	wp_reset_postdata();
  
	//echo $response;
	//exit;
	
	die($response);
}
add_shortcode('test_shortcode_pagination', 'test_shortcode_pagination_func');
*/



//Repeater Logo Resources
add_action('vc_before_init', 'logo_resources_items_funct');
function logo_resources_items_funct() {
	vc_map(
		  array(
		  	"name" => __("Logo resources", "nextcloud"), // Element name
		  	"base" => "logo_resources", // Element shortcode
		  	"class" => "logo_resources",
		  	"category" => __('Repeater', 'nextcloud'),
		  	'params' => array(

				array(
					"type" => "textfield",
					"heading" => esc_html__("Custom CSS Classes", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "css_classes",
					"value" => "",
				),

		  		array(
		  			'type' => 'param_group',
		  			'param_name' => 'box_repeater_items',
		  			'params' => array(

						array(
							"type" => "attach_image",
							"holder" => "img",
							"class" => "",
							"heading" => __("Resource SVG", "nextcloud"),
							"param_name" => "image_svg",
							"value" => __("", "nextcloud"),
						),


						array(
							"type" => "attach_image",
							"holder" => "img",
							"class" => "",
							"heading" => __("Resource PNG", "nextcloud"),
							"param_name" => "image_png",
							"value" => __("", "nextcloud"),
						),

		  				array(
		  					"type" => "textarea",
		  					"holder" => "div",
		  					"class" => "",
		  					"admin_label" => true,
		  					"heading" => __("Title", "nextcloud"),
		  					"param_name" => "title",
		  					"value" => __("", "nextcloud"),
						),

						array(
							"type" => "textfield",
							"heading" => esc_html__("Custom CSS Classes", "nextcloud"),
							"description" => esc_html__("", "nextcloud"),
							"param_name" => "custom_class",
							"value" => "",
						)

		  			)
		  		)
		  	)
		  )
	  );
}

add_shortcode('logo_resources', 'logo_resources_funct');
function logo_resources_funct($atts) {
	ob_start();
	$atts = shortcode_atts(array(
		'box_repeater_items' => '',
		'css_classes' => 'vc_col-sm-6 vc_col-lg-3 vc_col-md-3',
		//'columns' => ''
	), $atts, 'box_repeater');
	
	if(function_exists('vc_param_group_parse_atts')){
		$items = vc_param_group_parse_atts($atts['box_repeater_items']);
	}
	?>
          <?php if ($items) { ?>
			<div class="vc_row wpb_row vc_row-fluid vc_column-gap-20 vc_row-o-equal-height vc_row-flex logo_resources">
                  <?php  foreach ($items as  $item) {
					?>
					<div class="wpb_column vc_column_container <?php echo $atts['css_classes']; ?>">
						<div class="logo_resource">

						
						<div class="vc_column-inner">
									
								<div class="image_preview <?php if ( isset($item['custom_class'])) echo $item['custom_class']; ?>">
									<?php echo wp_get_attachment_image($item['image_svg'], 'full'); ?>
								</div>
				  				

								<div class="infos">

										<div class="title">
											<?php echo $item['title']; ?>
										</div>

										<div class="links image_download">
											<?php if ($item['image_svg']) { ?>
											<a target="_blank" href="<?php echo wp_get_attachment_image_url($item['image_svg'], 'full'); ?>">SVG</a>
											<?php } ?>

											<?php if ($item['image_png']) { ?>
											<a target="_blank" href="<?php echo wp_get_attachment_image_url($item['image_png'], 'full'); ?>?original">PNG</a>
											<?php } ?>
										</div>

								</div>

								
								
		

							</div>
						</div>
				  	</div>
				<?php } ?>
			</div>
        <?php } ?>
      <?php
	  $result = ob_get_clean();
	return $result;
}




add_shortcode('press_releases', 'press_releases_funct');
function press_releases_funct($atts) {
	ob_start();

	$a = shortcode_atts( array(
		'year' => '',
		'blogs' => false
	), $atts );
	$year = $a['year'];
	$blogs = $a['blogs'];
	

	/*
	if (class_exists('WPML_Display_As_Translated_Tax_Query')) {
		global $sitepress, $wpml_term_translations;
		$wpml_display_as_translated_tax = new WPML_Display_As_Translated_Tax_Query( $sitepress, $wpml_term_translations );
		$wpml_display_as_translated_tax->add_hooks();
	}
	*/


	// The Query
	$press_posts_ids = array();
	
	/*
	// Get the default category ID for 'pressrelease'
	$default_category_id = 19; // Default category ID for 'pressrelease'
	// Get the translated category ID for 'pressrelease' in the current language
	$category_id = wpml_object_id($default_category_id, 'category', true);
	*/


	if($blogs) {
		$press_posts_ids = get_posts( array(
			'fields'        => 'ids',
			'category' 		=> 19, //pressrelease
			'post_type' 	=> array('post'),
			'post_status' 	=> array('publish'),
			'date_query' 	=> array(
				array(
					'year' => $year
				)
			),
			'posts_per_page' 	=> '-1',
			'orderby'    		=> 'date',
			'order'      		=> 'DESC',
			//'suppress_filters'    => true
		));
	}

	
	echo '<div id="press_posts_ids" class="hidden">';
	echo '<pre>';
	print_r($press_posts_ids);
	echo '</pre>';
	echo '</div>';
	
	

	$press_releases_ids = get_posts( array(
		'fields'         => 'ids',
		'post_type' => array('press_releases'),
		'post_status' => array('publish'),
		'date_query' => array(
			array(
				'year' => $year
			)
		),
		'posts_per_page' => '-1',
		'orderby'    => 'date',
		'order'      => 'DESC',
		//'suppress_filters'    => true
	));

	
	/*
	echo '<div id="press_releases_ids" class="hidden">';
	echo '<pre>';
	print_r($press_releases_ids);
	echo '</pre>';
	echo '</div>';
	*/
	

	$post_ids = array_merge( $press_posts_ids, $press_releases_ids);

	/*
	echo '<div id="post_ids" class="hidden">';
	echo '<pre>';
	print_r($post_ids);
	echo '</pre>';
	echo '</div>';
	*/


	$the_query = new WP_Query(array(
		'post_type' => 'any',
		'post__in'  => $post_ids,
		'orderby'   => 'date', 
		'order'     => 'DESC',
		'posts_per_page' => '-1',
		'ignore_sticky_posts' => true,
		'tag__not_in' => array(269)
		//'suppress_filters'    => true
	));

	// The Loop
	if ( $the_query->have_posts() ) {
		echo '<ul class="press_releases">';
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$post_type = get_post_type();
			?>
			<li><a target="" title="<?php the_title(); ?>" href="<?php the_permalink(); ?>">
				<div class="icon">
					<?php if ($post_type == 'press_releases') { ?>
						<!-- <i class="fas fa-newspaper"></i> -->
						<svg id="" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 24 24">
						<path class="st0" d="M4,21c-.6,0-1-.2-1.4-.6-.4-.4-.6-.9-.6-1.4V5c0-.6.2-1,.6-1.4s.9-.6,1.4-.6h16c.6,0,1,.2,1.4.6s.6.9.6,1.4v14c0,.6-.2,1-.6,1.4-.4.4-.9.6-1.4.6H4ZM4,19h16V5H4v14ZM6,17h12v-2H6v2ZM6,13h4v-6h-4v6ZM12,13h6v-2h-6v2ZM12,9h6v-2h-6v2ZM4,19V5v14Z"/>
						</svg>
					<?php } else { ?>
						<i class="fas fa-bullhorn"></i>
					<?php } ?>
				</div>

				<div class="type">
					<?php if ($post_type == 'press_releases') { 
						echo __("Press release","nextcloud");
					}else {
						echo __("Blog post","nextcloud");
					}
					?>
				</div>

				<div class="date">
				<?php echo get_the_date(get_option('date_format')); ?>
				</div>
				<div class="title">
					<?php the_title(); ?>
				</div>

				</a>
			</li>
			<?php
		}
		echo '</ul>';
	} else {
		// no posts found
	}
	/* Restore original Post Data */
	wp_reset_postdata();
	?>

		
    
	<?php
	$result = ob_get_clean();
	return $result;
}





// shortcode App Iconbox 
function nc_app_iconbox_element_custom() {
	// Title
	vc_map(
		array(
			'name' => __('App Iconbox'),
			'base' => 'nc_app_iconbox',
			'category' => __('Content', 'nextcloud'),
			'params' => array(
				
				array(
					"type" => "iconpicker",
					"holder" => "div",
					"class" => "",
					"heading" => __("Icon", "nextcloud"),
					"param_name" => "icon",
					"value" => __("", "nextcloud"),
					"admin_label" => false
				),

				array(
					"type" => "attach_image",
					"holder" => "img",
					"class" => "",
					"heading" => __("Image Icon", "nextcloud"),
					"param_name" => "image_icon",
					"value" => __("", "nextcloud"),
					"admin_label" => false
				),

				array(
					"type" => "vc_link",
					"holder" => "div",
					"class" => "iconbox_link",
					"heading" => __("Link", "nextcloud"),
					"param_name" => "link",
					"value" => __("", "nextcloud"),
					"admin_label" => false
				),

				array(
					"type" => "textfield",
					"heading" => esc_html__("Title", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "title",
					"admin_label" => true,
					"value" => "",
				),

				array(
					"type" => "textarea",
					"heading" => esc_html__("Description", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "description",
					"admin_label" => true,
					"value" => "",
				),

				array(
					"type" => "textarea_raw_html",
					"heading" => esc_html__("Extra description", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "extra_description",
					"admin_label" => true,
					"value" => "",
				),
				

				array(
					"type" => "textfield",
					"heading" => esc_html__("Custom CSS Classes", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "css_classes",
					"value" => "",
					"admin_label" => false
				)

			)
		)
	);
}
add_action('vc_before_init', 'nc_app_iconbox_element_custom');

function nc_app_iconbox_function($atts, $content) {
	$attributes = shortcode_atts(
		array(
			'icon' => '',
			'image_icon' => '',
			'link' => '',
			'title' => '',
			'description' => '',
			'extra_description' => '',
			'css_classes' => ''
		), $atts);

	$link = vc_build_link($attributes['link']);

	$return = '<div class="nc_app_iconbox '.$attributes['css_classes'].' ">';

	if ($attributes['image_icon']) {
		$icon = wp_get_attachment_image($attributes['image_icon'], 'medium');
	} elseif ($attributes['icon']) {
		$icon = '<i class="'.$attributes['icon'].'"></i>';
	} else {
		$icon = "";
	}
	

	if($attributes['link'] && $link['url'] != '#no_scroll' ){
		$return .= '<a href="'.$link['url'].'" target="'.$link['target'].'" title="'.$link['title'].'" class="app_link">';
	}else {
		$return .= '<a href="#no_scroll" class="no_scroll">';
	}
	
	$custom_class= '';
	if(!$attributes['link'] || $link['url'] == '#no_scroll' ){
		$custom_class= ' no-link';
	}

	

	$return .= '<div class="iconbox_container '.$custom_class.'">
				<div class="icon">'.$icon.'</div>
				<div class="text">
				<h4 class="title">'.$attributes['title'].'</h4>';

	if(isset($attributes['description'])){
		$desc = $attributes['description'];
		$return .= '<div class="description">'.$desc.'</div>';
	}

	if(isset($attributes['extra_description'])) {
		$return .= '<div class="description extra_description">'.nc_decode_raw_html_param($attributes['extra_description']).'</div>';
	}

	

	$return .= '</div></div></a>';

	$return .= '</div>';
	return $return;
}
add_shortcode('nc_app_iconbox', 'nc_app_iconbox_function');


function nc_color_hex_func($atts){
	$a = shortcode_atts(
		array(
			'code' => '',
	), $atts);

	return '<span title="Click to copy" class="nc_color copy_color" data-color="'.$a['code'].'" style="--codeColor: '.$a['code'].';">'.$a['code'].'</span>';

}
add_shortcode('nc_color', 'nc_color_hex_func');



// shortcode App Iconbox 
function nc_colorbox_element_custom() {
	// Title
	vc_map(
		array(
			'name' => __('Colorbox'),
			'base' => 'nc_colorbox',
			'category' => __('Content', 'nextcloud'),
			'params' => array(

				array(
					"type" => "textfield",
					"heading" => esc_html__("Title", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "title",
					"admin_label" => true,
					"value" => "",
				),

				array(
					"type" => "textarea",
					"heading" => esc_html__("Description", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "description",
					"admin_label" => true,
					"value" => "",
				),


				array(
					"type" => "textarea",
					"heading" => esc_html__("HEX", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "hex",
					"admin_label" => true,
					"value" => "",
				),

				array(
					"type" => "textarea",
					"heading" => esc_html__("RGB", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "rgb",
					"admin_label" => true,
					"value" => "",
				),


				array(
					"type" => "textarea",
					"heading" => esc_html__("CMYK", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "cmyk",
					"admin_label" => true,
					"value" => "",
				),


				array(
					"type" => "textarea",
					"heading" => esc_html__("HSL", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "hsl",
					"admin_label" => true,
					"value" => "",
				),


				array(
					"type" => "textarea",
					"heading" => esc_html__("Pantone", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "pantone",
					"admin_label" => true,
					"value" => "",
				),


				array(
					"type" => "textarea",
					"heading" => esc_html__("Angle", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "angle",
					"admin_label" => true,
					"value" => "",
				),


				array(
					"type" => "textfield",
					"heading" => esc_html__("Custom CSS Classes", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "css_classes",
					"value" => "",
					"admin_label" => false
				)

			)
		)
	);
}
add_action('vc_before_init', 'nc_colorbox_element_custom');

function nc_colorbox_function($atts, $content) {
	$attributes = shortcode_atts(
		array(
			'title' => '',
			'description' => '',
			'hex' => '',
			'rgb' => '',
			'cmyk' => '',
			'hsl' => '',
			'pantone' => '',
			'angle' => '',
			'css_classes' => ''
		), $atts);

	$return = '<div class="nc_colorbox '.$attributes['css_classes'].' "><div class="colorbox_inner">';

	if($attributes['title']){
		$return .= '<div class="title">'.$attributes['title'].'</div>';
	}

	if(isset($attributes['description']) && $attributes['description']!= '' ){
		$return .= '<p class="desc">'.$attributes['description'].'</p>';
	}

	$return .= '<ul class="codes">';

	if($attributes['hex']){
		$return .= '<li><span class="code_title">HEX</span>'.$attributes['hex'].'</li>';
	}
	if($attributes['rgb']){
		$return .= '<li><span class="code_title">RGB</span>'.$attributes['rgb'].'</li>';
	}
	if($attributes['cmyk']){
		$return .= '<li><span class="code_title">CMYK</span>'.$attributes['cmyk'].'</li>';
	}
	if($attributes['hsl']){
		$return .= '<li><span class="code_title">HSL</span>'.$attributes['hsl'].'</li>';
	}
	if($attributes['pantone']){
		$return .= '<li><span class="code_title">Pantone</span>'.$attributes['pantone'].'</li>';
	}
	if($attributes['angle']){
		$return .= '<li><span class="code_title">Angle</span>'.$attributes['angle'].'</li>';
	}
	
	$return .= '</ul>';
	

	$return .= '</div></div>';
	return $return;
}
add_shortcode('nc_colorbox', 'nc_colorbox_function');


function nc_get_whitepaper_attachment($atts){
	global $post;
	if(get_post_meta($post->ID, 'attachment', true)) {
		return wp_get_attachment_url(get_post_meta($post->ID, 'attachment', true));
	}
	return;
}
add_shortcode('nc_attachment', 'nc_get_whitepaper_attachment');


function nc_get_whitepaper_type($atts){
	global $post;
	$type='';

	if(get_post_type()=='whitepapers'){
		$type = __('whitepaper', 'nextcloud');
	}else if(get_post_type()=='case_studies') {
		$type = __('case study', 'nextcloud');
	} else if(get_post_type()=='data_sheets'){
		$type = __('data sheet', 'nextcloud');
	}
	return $type;
}
add_shortcode('whitepaper_type', 'nc_get_whitepaper_type');


/**
 * Is early-bird pricing currently active for a given event post?
 *
 * Shared by the `dynamic_event_ticket_price` and `is_early_bird_active`
 * branches of nc_get_field_func() so the date/deadline logic lives in one
 * place.
 *
 * @param int $post_id
 * @return bool
 */
function nc_is_early_bird_active($post_id) {
  if (!get_post_meta($post_id, 'enable_early_bird_tickets', true)) {
    return false;
  }

  //get now date
  $timezone = wp_timezone(); // Get WordPress timezone object
  $now = new DateTime("now", $timezone);
  $offset = $timezone->getOffset($now);
  $local_timestamp = time() + $offset;

  //get group
  $early_bird_infos = get_field('early_bird_tickets_info', $post_id);
  $deadline = $early_bird_infos['early_bird_ticket_price_end_date'];
  $date_deadline = DateTime::createFromFormat("d/m/Y g:i a", $deadline);
  if (!$date_deadline) {
    return false;
  }
  $timestamp_deadline = $date_deadline->getTimestamp();

  return $timestamp_deadline > $local_timestamp;
}


function nc_get_field_func($atts){
	global $post;

	$attributes = shortcode_atts(
		array(
			'field' => ''
		), $atts);
	$return = "";

	if(isset($attributes['field'])) {

		//due date used for invoices
		if($attributes['field'] == 'due_date') {

			$date = strtotime('+2 weeks');
			$return = date('d/m/Y', $date);

		} else if( $attributes['field'] == 'dynamic_event_ticket_price') {

			if(nc_is_early_bird_active($post->ID)) {
				$early_bird_infos = get_field('early_bird_tickets_info');
				$return = $early_bird_infos['early_bird_tickets_price'];
			} else {
				//early bird not enabled, or deadline reached — normal price
				$return = get_post_meta($post->ID, 'event_ticket_price' , true);
			}

		}
		// Used to drive the Pretix "_reduced" item selection (NC PayPal
		// Payment Gate plugin) via a hidden Ninja Forms field — see that
		// plugin's README §4.1.4. Returns "1" when early-bird pricing is
		// currently active for this page's event, empty otherwise.
		else if( $attributes['field'] == 'is_early_bird_active') {

			$return = nc_is_early_bird_active($post->ID) ? '1' : '';

		}
		else {
			if (get_post_meta($post->ID, $attributes['field'] , true) != ''){
				$return = get_post_meta($post->ID, $attributes['field'] , true);
			}
		}



	}

	return $return;
}
add_shortcode('nc_get_field', 'nc_get_field_func');


function nc_get_whitepaper_slug(){
	global $post;
	return $post->post_name;
}
add_shortcode('nc_slug', 'nc_get_whitepaper_slug');


function nc_get_get_actitiy_id(){
	global $post;
	$return = "missingID-".$post->ID; // get slug as default

	if(get_post_meta($post->ID, 'activity_id' , true) != ''){
		$return = get_post_meta($post->ID, 'activity_id' , true);
	}

	return $return;
}
add_shortcode('nc_activity_id', 'nc_get_get_actitiy_id');

function nc_get_webinar_video_url(){
	global $post;
	return get_post_meta($post->ID, 'webinar_video_url' , true);
}
add_shortcode('webinar_video_url', 'nc_get_webinar_video_url');



/*
function nc_typed_text_func($atts){
	//wp_enqueue_script('typed');
	$attributes = shortcode_atts(
		array(
			'id' => 'typed',
			'text' => '',
			'simple' => '',
			'loop' => true,
			'fadeOut' => "false",
			'backDelay' => '',
			'typeSpeed' => 40,
			'backSpeed' => 40
		), $atts);

	$id = $attributes['id'];
	$text = $attributes['text'];
	$simple = $attributes['simple'];
	$loop = $attributes['loop'];
	$fadeOut = $attributes['fadeOut'];
	$backDelay = $attributes['backDelay'];
	$typeSpeed = $attributes['typeSpeed'];
	$backSpeed = $attributes['backSpeed'];

	$words = explode(',', $text);

	$text_as_array = '[';
	foreach($words as $key => $word) {
		$text_as_array .= "'".$word."'";
		$next_key = $key + 1;
		if($next_key != count($words)) {
			$text_as_array .= ", ";
		}
	}
	$text_as_array .= ']';

	ob_start();
	?>
	<span class="typed_text <?php if($simple) echo " simple "; ?>" id="<?php echo $id; ?>"></span>
	<script id="typed_custom_js">
	var options = {
		strings: <?php echo $text_as_array; ?>,
		typeSpeed: <?php echo $typeSpeed; ?>,
		backSpeed: <?php echo $backSpeed; ?>,
		smartBackspace: true,
		<?php if($backDelay) {echo "backDelay: ".$backDelay.",";}?>
		fadeOut: <?php echo $fadeOut; ?>,
		loop: <?php echo $loop; ?>
	};
	var typed = new Typed('#<?php echo $id; ?>', options);
	</script>
	<?php
	return ob_get_clean();
}
add_shortcode('nc_typed', 'nc_typed_text_func');
*/
function nc_typed_text_func($atts) {

	$attributes = shortcode_atts(
		array(
			'id'         => '',
			'text'       => '',
			'simple'     => '',
			'loop'       => 'true',
			'fadeOut'    => 'false',
			'backDelay'  => 700,
			'typeSpeed'  => 40,
			'backSpeed'  => 40,
		),
		$atts,
		'nc_typed'
	);

	// Generate a unique ID unless one was explicitly provided.
	$id = !empty($attributes['id'])
		? sanitize_html_class($attributes['id'])
		: wp_unique_id('typed_');

	$words = array_filter(
		array_map(
			'trim',
			explode(',', $attributes['text'])
		),
		'strlen'
	);

	if (empty($words)) {
		return '';
	}

	$options = array(
		'strings'        => array_values($words),
		'typeSpeed'      => (int) $attributes['typeSpeed'],
		'backSpeed'      => (int) $attributes['backSpeed'],
		'backDelay'      => (int) $attributes['backDelay'],
		'smartBackspace' => true,

		'fadeOut'        => filter_var(
			$attributes['fadeOut'],
			FILTER_VALIDATE_BOOLEAN
		),

		'loop'           => filter_var(
			$attributes['loop'],
			FILTER_VALIDATE_BOOLEAN
		),
	);

	ob_start();
	?>
	<span
		id="<?php echo esc_attr($id); ?>"
		class="typed_text<?php echo !empty($attributes['simple']) ? ' simple' : ''; ?>"
	></span>

	<script>
		document.addEventListener('DOMContentLoaded', function () {
			new Typed(
				<?php echo wp_json_encode('#' . $id); ?>,
				<?php echo wp_json_encode($options); ?>
			);
		});
	</script>
	<?php

	return ob_get_clean();
}

add_shortcode('nc_typed', 'nc_typed_text_func');


//webinars list used on /events page
function webinars_list_func($atts){
	$a = shortcode_atts( array(
		'type' => '',
		'th_title' => '',
		'exclude_next' => false,
		'limit' => 3,
		'btn' => '',
		'flags' => false,
		'include_drafts' => false,
		'include_all_languages' => false
	), $atts );
	$type = $a['type'];

	$th_title = __('Webinar', 'nextcloud');
	if(isset($a['th_title']) && $a['th_title']!='' ) {
		$th_title = $a['th_title'];
	}

	$exclude_next = $a['exclude_next'];
	$limit = $a['limit'];
	$flags = (bool)$a['flags'];
	$include_drafts = (bool)$a['include_drafts'];
	$include_all_languages = (bool)$a['include_all_languages'];
	$my_current_lang = apply_filters( 'wpml_current_language', NULL );


	if($a['btn'] === 'false') {
		$btn = false;
	} else {
		$btn = true;
	}
	
	ob_start();
	$timezone = wp_timezone(); // Get WordPress timezone object
	$timezone_us = new DateTimeZone('America/New_York');

	$current_date_time = wp_date("Y-m-d H:i:s", null, $timezone );
	$post_statuses = array('publish');
	if($include_drafts) {
		$post_statuses[] = 'draft';
	}

	$args = array(
		'post_type' => 'event',
		'post_status' => $post_statuses,
		'posts_per_page' => $limit,
		'tag__not_in' => array(269),
		'tax_query' => array(
			array(
				'taxonomy' => 'event_categories',
				'field'    => 'slug',
				'terms'    => 'webinars',
			),
		),
	);
	
	if($exclude_next) {
		$args['post__not_in'] = array(get_next_webinar_id());
	}

	//show all languages
	if($include_all_languages) {
		$args['suppress_filters'] = true;
	}

	if($type == 'upcoming') {
		$args['orderby'] = 'meta_value';
		$args['order'] = 'ASC';

		$args['meta_query'] = array(
			array(
				'key' => 'event_start_date_and_time',
				'value'   => $current_date_time,
				'compare' => '>=',
				'type'	=> 'DATETIME'
			),
		);
	}
	else if($type == 'past') {
		$args['orderby'] = 'meta_value';
		$args['order'] = 'DESC';

		$args['meta_query'] = array(
			'relation' => 'AND',
			
			array(
				'key' => 'event_start_date_and_time',
				'value'   => $current_date_time,
				'compare' => '<',
				'type'	=> 'DATETIME'
			),
			

			array(
				'key'     => 'download_available',
				'value'	  => '',
				'compare' => '!=',
			),
			
			
		);
	}



	if($include_all_languages) {
		$same_lang_posts_ids = array();
		$tr_posts_ids = array();
		$base_query = new WP_Query($args);
		if ( $base_query->have_posts() ) {
			while($base_query->have_posts()) {
				$base_query->the_post();
				$webinar_language_details = apply_filters( 'wpml_post_language_details', NULL, get_the_ID() );
				$webinar_lang_code = $webinar_language_details['language_code'];

				if( $webinar_lang_code == $my_current_lang) {
					// to be shown first
					$same_lang_posts_ids[] = get_the_ID();
					//echo "Same lang: ".get_the_ID().": ".get_the_title()."<br><br>";
				} else {
					// to be shown after
					$tr_posts_ids[] = get_the_ID();
					//echo "Different lang: ".get_the_ID().": ".get_the_title()."<br><br>";
				}
			}
		}
		$all_lang_posts = array_merge($same_lang_posts_ids, $tr_posts_ids);
		//print_r($all_lang_posts);

		$args_new = array(
			'post_type' => 'event',
			'post_status' => array('publish'),
			'posts_per_page' => -1,
			'post__in' => $all_lang_posts,
			'orderby' => 'post__in',
			'suppress_filters' => true
		);

		$the_query = new WP_Query($args_new);
	} else {
		$the_query = new WP_Query($args);
	}

	
	$count = $the_query->found_posts;
	// The Loop
	if ( $the_query->have_posts() ) {
		echo '<table class="table events_table webinars table-striped '.$type.'">
		<thead>
		<tr>
		<th>'.$th_title.'</th>
		<th>'.__('Date and time', 'nextcloud').'</th>
		</tr>
		</thead>		
		<tbody>';

		while ( $the_query->have_posts() ) {
			$the_query->the_post();

			$event_start_datetime = get_field('event_start_date_and_time', false, false);
			$date = $date_start = date_create($event_start_datetime);

			$event_end_datetime = get_field('event_end_date_and_time', false, false);
			if($event_end_datetime){
				$date_end = date_create($event_end_datetime);
			}
			?>
			<tr>
			<td>
			<?php if($flags) {
					$webinar_language_details = apply_filters( 'wpml_post_language_details', NULL, get_the_ID() );
					$webinar_lang_value = $webinar_language_details['language_code'];
					$webinar_lang_name = $webinar_language_details['display_name'];

					if(!empty(get_field('custom_language'))) {
						$webinar_lang = get_field( 'custom_language' );
						if($webinar_lang['label'] != 'default') {
							$webinar_lang_value = $webinar_lang['value'];
							$webinar_lang_name = $webinar_lang['label'];
						}
					}

					$flag_path = "https://nextcloud.com/p/sitepress-multilingual-cms/res/flags/";
					?>
					<span class="webinar_lang" data-custom_lang="<?php echo $webinar_lang_value; ?>">
					<?php 
					$flag_full_url = $flag_path.$webinar_lang_value.".svg";
					echo '<img src="'.$flag_full_url.'" class="flag_icon">'.$webinar_lang_name; ?></span>
			<?php } ?>

			<a class="hyperlink" title="<?php the_title(); ?>" href="<?php
			if(get_field('blog_post_url')){
				echo get_field('blog_post_url');
			} else {
				the_permalink();
			}
			?> " target="" rel="noopener"><?php the_title(); ?></a></td>
			
			
			<td>
			<?php 
			//$date_format = get_option('date_format');
			$date_format = "F j";
			if($my_current_lang != 'en'){
				$date_format = "j F";
			}
			$date_formatted = date_format($date, $date_format);
			//get CET/CEST
			$event_start_unix = strtotime($event_start_datetime);
			$date_custom_datetime = new DateTime($event_start_datetime, $timezone);
			$time_start_format = $date_custom_datetime->format('g:i a');
			$date_custom_datetime->setTimezone($timezone_us);
			$time_start_format_us = $date_custom_datetime->format('g:i a');
			//$my_current_lang = apply_filters( 'wpml_current_language', NULL );
			$cet_cest = wp_date('T', $event_start_unix, $timezone); // Get timezone abbreviation (CET/CEST)
			$edt_est = wp_date('T', $event_start_unix, $timezone_us); // Get timezone abbreviation (EDT/EST)
			echo '<span data-cet="'.$cet_cest.'" data-edt="'.$edt_est.'">';
			echo formatLanguage($date, $date_format, $my_current_lang);
			echo " @ ".date_format($date,"g:i a")." ".$cet_cest;
			?>
			<span class="edt_time"><?php echo " / ".$time_start_format_us." ".$edt_est; ?></span>
			</span>
			</td>
			</tr>
			<?php
		}
		echo '</tbody>
		</table>';

		//echo $btn;
		if($btn) {
			if($count > $limit) {
				$my_current_lang = apply_filters( 'wpml_current_language', NULL );
				$blog_wpml_permalink = apply_filters( 'wpml_permalink', '/blog', $my_current_lang ); 
	
				if($type == 'upcoming'){
				?>
				<div class="vc_btn3-container  btn-main btn-small vc_btn3-center"><a class="<?php echo $blog_wpml_permalink; ?> vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-rounded vc_btn3-style-modern vc_btn3-icon-right vc_btn3-color-grey" title="" target="" href="/<?php echo $my_current_lang; ?>/blog/?webinars=upcoming"><?php echo __('All upcoming webinars','nextcloud'); ?><i class="vc_btn3-icon fas fa-angle-right"></i></a></div>
				<?php
				}
				else {
				?>
				<div class="vc_btn3-container  btn-main btn-small vc_btn3-center"><a class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-rounded vc_btn3-style-modern vc_btn3-icon-right vc_btn3-color-grey" title="" target="" href="https://nextcloud.com/webinars/"><?php echo __('All recordings','nextcloud'); ?><i class="vc_btn3-icon fas fa-angle-right"></i></a></div>
				<?php
				}
			}
		}

	} else {
		// no posts found
		if($type == 'upcoming'){
			echo "<h4 class='no_events_found'>".__('No upcoming webinars found.','nextcloud')."</h4>";
			//show newsletter card 
			echo "<h5 class='text-center'>".__('Never miss a webinar, sign up for our events newsletter.','nextcloud')."</h5>";
			echo '<div class="vc_btn3-container btn-main btn-small vc_btn3-center vc_do_btn"><a class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-rounded vc_btn3-style-modern vc_btn3-icon-left vc_btn3-color-grey vc_btn3--has-svg" href="https://nextcloud.com/newsletter/?list=7597" title="'.__('Events newsletter','nextcloud').'"><span class="vc_btn3-icon vc_btn3-icon--svg" aria-hidden="true"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" id="Layer_1" version="1.1" viewBox="0 0 24 24">
			<path class="st0" d="M4,20c-.6,0-1-.2-1.4-.6s-.6-.9-.6-1.4V6c0-.6.2-1,.6-1.4s.9-.6,1.4-.6h16c.6,0,1,.2,1.4.6s.6.9.6,1.4v12c0,.6-.2,1-.6,1.4s-.9.6-1.4.6H4ZM12,13L4,8v10h16V8l-8,5ZM12,11l8-5H4l8,5ZM4,8v-2,12V8Z"></path>
			</svg></span> '.__('Events newsletter','nextcloud').'</a></div>';

		}
		else {
			echo "<h4 class='no_events_found'>".__('No recordings found.','nextcloud')."</h4>";
		}
	}
	/* Restore original Post Data */
	wp_reset_postdata();
	return ob_get_clean();
}
add_shortcode('webinars', 'webinars_list_func');


//show next webinar used on /events page
function next_webinar_func(){
	ob_start();
	$next_webinar_id = $post_id = get_next_webinar_id();
	$flag_full_url = '';
	$display_name = 'English';

	$webinar_language_details = apply_filters( 'wpml_post_language_details', NULL, $next_webinar_id );
	if(is_array($webinar_language_details)) {
		$flag_path = "https://nextcloud.com/p/sitepress-multilingual-cms/res/flags/";
		$flag_full_url = $flag_path.$webinar_language_details['language_code'].".svg";
		$webinar_lang_value = $webinar_language_details['language_code'];
		$display_name = $webinar_language_details['display_name'];
	}
	
	if(!empty(get_field('custom_language', $next_webinar_id))) {
						$webinar_lang = get_field( 'custom_language', $next_webinar_id);
						if($webinar_lang['label'] != 'default') {
							$webinar_lang_value = $webinar_lang['value'];
							$flag_full_url = $flag_path.$webinar_lang_value.".svg";
							$display_name = $webinar_lang['label'];
						}
					}


	$title = get_the_title($post_id);
	$post_excerpt_full = get_the_excerpt($post_id);
	$post_excerpt = word_count(get_the_excerpt($post_id), '45');
	$link = get_permalink($post_id);
	$featured_image = get_the_post_thumbnail($post_id, 'large', array( 'class' => 'feat_img' ));

	//$date_format = get_option( 'date_format' ); // e.g. "F j, Y"
	$my_current_lang = apply_filters( 'wpml_current_language', null );

	$date_format = "F j";
	if($my_current_lang != 'en'){
		$date_format = "j F";
	}

	$date = (string)get_the_date($date_format);
	$cat = get_the_category($post_id);

	if ( 'event' == get_post_type() ) {
		$cat = wp_get_object_terms( $post_id, 'event_categories', array() );
		$event_start_datetime = get_field('event_start_date_and_time', $post_id, false);
		if($event_start_datetime) {
			$date = date_i18n($date_format, strtotime($event_start_datetime));
		}
	}

	$cats = '';
	if($cat) {
		foreach ($cat as $c) {
			if($c->term_id != 243 && $c->term_id != 1 ) { // exclude Uncategorized
				$category_link = get_category_link($c->term_id);
				$cats .= '<a href="'.$category_link.'">' . $c->name . ' </a>';
			}
		}
	}
					echo '<div class="spacer news-container next-webinar news-item" id="'.$next_webinar_id.'" style="">';
					echo '<div class="post-box">';
					echo '<div class="post-img" style=""><a href="'.$link.'" title="'.$title.'">';
					?>
					<div class="coming_next">
						<?php echo __('Coming soon','nextcloud');?>
					</div>
					<?php
					echo $featured_image.'</a></div>';
					echo '<div class="post-body">';
					?>
	

					<?php
					echo '<h4><a href="'.$link.'" title="'.$title.'">'; 
					echo $title . '</a></h4>';
					?>
					<?php
					$timezone = wp_timezone(); // Get WordPress timezone object
					$timezone_us = new DateTimeZone('America/New_York');

					$event_start_datetime = get_field('event_start_date_and_time', $post_id, false);
					$event_start_unix = strtotime($event_start_datetime);
					$date = $date_start = date_create($event_start_datetime);


					$date_custom_datetime = new DateTime($event_start_datetime, $timezone);
					$time_start_format = $date_custom_datetime->format('g:i a');
					$date_custom_datetime->setTimezone($timezone_us);
					$time_start_format_us = $date_custom_datetime->format('g:i a');

					//get CET/CEST
					$cet_cest = wp_date('T', $event_start_unix, $timezone); // Get timezone abbreviation (CET/CEST)
					$edt_est = wp_date('T', $event_start_unix, $timezone_us); // Get timezone abbreviation (EDT/EST)

					$my_current_lang = apply_filters( 'wpml_current_language', NULL );
					?>

					<ul class="webinar_details">
						<li class="date with_svg">
							<svg fill="#0082c9" xmlns="http://www.w3.org/2000/svg" id="Layer_1" version="1.1" viewBox="0 0 24 24">
							<path class="st0" d="M5,22c-.55,0-1.021-.196-1.413-.588-.392-.392-.588-.863-.588-1.413V6c0-.55.196-1.021.588-1.413.392-.392.863-.588,1.413-.588h1V2h2v2h8V2h2v2h1c.55,0,1.021.196,1.413.588s.588.863.588,1.413v14c0,.55-.196,1.021-.588,1.413s-.863.588-1.413.588H5ZM5,20h14v-10H5v10ZM5,8h14v-2H5v2ZM5,8v-2,2ZM12,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288c.192.192.288.429.288.713s-.096.521-.288.713c-.192.192-.429.288-.713.288ZM8,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713-.429.288-.713.288ZM16,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713-.429.288-.713.288ZM12,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288c.192.192.288.429.288.713s-.096.521-.288.713c-.192.192-.429.288-.713.288ZM8,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713c-.192.192-.429.288-.713.288ZM16,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713c-.192.192-.429.288-.713.288Z"></path>
							</svg>

						 <?php echo formatLanguage($date, $date_format, $my_current_lang); ?> @ <?php echo $time_start_format." ".$cet_cest; ?> 
						<span class="edt_time"><?php echo " / ".$time_start_format_us." ".$edt_est; ?></span>

						</li>
						<li class="webinar_lang">
							<span class="">
							<?php
							echo '<img src="'.$flag_full_url.'" class="flag_icon">'.$display_name; ?>
							</span>
						</li>

					</ul>
					
					<div class="webinar_excerpt"><?php echo $post_excerpt; ?></div>
					
					<?php
					echo '<a class="c-btn" title="'.__('Register for the webinar', 'nextcloud').'" href="' . $link . '">'.__('Register for the webinar', 'nextcloud').'<i class="fas fa-angle-right"></i></a>';
					echo '</div>';
					echo '</div>';
					echo '</div>';

return ob_get_clean();
}
add_shortcode('next_webinar', 'next_webinar_func');


//show next event used on /events page
function next_event_preview_func($atts){
	$a = shortcode_atts( array(
		'type' => 'events'
	), $atts );
	$type = $a['type'];

	ob_start();
	$my_current_lang = apply_filters( 'wpml_current_language', null );
	$timezone = wp_timezone(); // Get WordPress timezone object

	$post_id = get_next_event_id($type);

	$title = get_the_title($post_id);
	$post_excerpt_full = get_the_excerpt($post_id);
	$post_excerpt = word_count(get_the_excerpt($post_id), '45');
	$link = get_permalink($post_id);
	$featured_image = get_the_post_thumbnail($post_id, 'large', array( 'class' => 'feat_img' ));
	$date_format = get_option('date_format');
	//$date = (string)get_the_date($date_format);
	//$cat = get_the_category($post_id);
	$event_start_datetime = get_field('event_start_date_and_time', $post_id, false);
	$date = $date_start = date_create($event_start_datetime);
	$event_start_unix = strtotime($event_start_datetime);

	$event_end_datetime = get_field('event_end_date_and_time', $post_id, false);
	if($event_end_datetime){
		$date_end = date_create($event_end_datetime);
		$event_end_unix = strtotime($event_end_datetime);
	}

					echo '<div class="spacer news-container next-webinar news-item" id="'.$post_id.'" style="">';
					echo '<div class="post-box">';
					echo '<div class="post-img" style=""><a href="'.$link.'" title="'.$title.'">'; ?>

					<div class="coming_next">
						<?php echo __('Coming soon','nextcloud');?>
					</div>

					<?php echo $featured_image.'</a></div>';
					echo '<div class="post-body">';
					echo '<h4><a href="'.$link.'" title="'.$title.'">'; 
					echo $title . '</a></h4>';
					?>
					<ul class="webinar_details event_details">
						<li class="with_svg">
						<svg fill="#0082c9" xmlns="http://www.w3.org/2000/svg" id="Layer_1" version="1.1" viewBox="0 0 24 24">
							<path class="st0" d="M5,22c-.55,0-1.021-.196-1.413-.588-.392-.392-.588-.863-.588-1.413V6c0-.55.196-1.021.588-1.413.392-.392.863-.588,1.413-.588h1V2h2v2h8V2h2v2h1c.55,0,1.021.196,1.413.588s.588.863.588,1.413v14c0,.55-.196,1.021-.588,1.413s-.863.588-1.413.588H5ZM5,20h14v-10H5v10ZM5,8h14v-2H5v2ZM5,8v-2,2ZM12,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288c.192.192.288.429.288.713s-.096.521-.288.713c-.192.192-.429.288-.713.288ZM8,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713-.429.288-.713.288ZM16,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713-.429.288-.713.288ZM12,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288c.192.192.288.429.288.713s-.096.521-.288.713c-.192.192-.429.288-.713.288ZM8,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713c-.192.192-.429.288-.713.288ZM16,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713c-.192.192-.429.288-.713.288Z"></path>
						</svg>

						<?php
						echo get_event_date($event_start_unix, $event_end_unix);
						?>
						</li>

						<li class="with_svg">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="#0082c9"><path d="M480-480q33 0 56.5-23.5T560-560q0-33-23.5-56.5T480-640q-33 0-56.5 23.5T400-560q0 33 23.5 56.5T480-480Zm0 294q122-112 181-203.5T720-552q0-109-69.5-178.5T480-800q-101 0-170.5 69.5T240-552q0 71 59 162.5T480-186Zm0 106Q319-217 239.5-334.5T160-552q0-150 96.5-239T480-880q127 0 223.5 89T800-552q0 100-79.5 217.5T480-80Zm0-480Z"/></svg>
							<?php echo get_field('location', $post_id, false); ?>
						</li>

					</ul>
					
					<div class="webinar_excerpt"><?php echo $post_excerpt; ?></div>
					
					<?php
					if($type == "enterprise_day") {
						echo '<a class="c-btn" title="'.$title.'" href="' . $link . '">'.__('Register now', 'nextcloud').'<i class="fas fa-angle-right"></i></a>';
					} else {
						echo '<a class="c-btn" title="'.$title.'" href="' . $link . '">'.__('Read more', 'nextcloud').'<i class="fas fa-angle-right"></i></a>';
					}

					
					echo '</div>';
					echo '</div>';
					echo '</div>';

return ob_get_clean();
}
add_shortcode('next_event_preview', 'next_event_preview_func');








function events_list_func($atts){
	$a = shortcode_atts( array(
		'year' => '',
	), $atts );
	$year = $a['year'];

	ob_start();
	//date_default_timezone_set('Europe/Berlin');
	//$current_date_time = date('Y-m-d H:i:s', time());
	//$current_year = date('Y', time());
	//echo $current_date_time;


	$args = array(
		'post_type' => 'event',
		'post_status' => 'publish',
		'posts_per_page' => '-1',
		

		'tax_query' => array(
			//'relation' => 'AND',

			array(
				'taxonomy' => 'event_categories',
				'field'    => 'slug',
				'terms'    => array('webinars'),
				'operator' => 'NOT IN'
			)

			/*
			array(
				'taxonomy' => 'post_tag',
				'terms'    => 269,
				'operator' => 'NOT IN'
			)
			*/

		),

		'meta_query' => array(

			array(
				'key' => 'event_start_date_and_time',
				'value'   => array( $year.'-01-01 00:00:00', $year.'-12-31 00:00:00' ),
				'type'    => 'DATETIME',
				'compare' => 'BETWEEN',
			),
		),
		

	);
	

	$the_query = new WP_Query($args);
	//print_r($the_query);
	//echo $the_query->request;


	// The Loop
	if ( $the_query->have_posts() ) {
		echo '<table class="table events_table events table-striped">';
		
		echo '<thead>';
		echo '<tr>
		<th>'.__('Event', 'nextcloud').'</th>
		<th>'.__('Location', 'nextcloud').'</th>
		<th>'.__('Date', 'nextcloud').'</th>
		</tr>';
		echo '</thead>';
		echo '<tbody>';

		while ( $the_query->have_posts() ) {
			$the_query->the_post();

			$event_start_datetime = get_field('event_start_date_and_time', false, false);
			$date = date_create($event_start_datetime);

			$event_end_datetime = get_field('event_end_date_and_time', false, false);
			if($event_end_datetime){
				$date_end = date_create($event_end_datetime);
			}
			?>
			<tr data-id="<?php echo get_the_ID(); ?>">
			<td><a class="hyperlink" title="<?php the_title(); ?>" href="<?php
			if(get_field('blog_post_url')){
				echo get_field('blog_post_url');
			} else {
				the_permalink();
			}
			?> " target="_blank" rel="noopener">
			<?php 
			if(get_field('event_short_title')) {
				echo get_field('event_short_title');
			} else {
				the_title();
			}
			?></a></td>
			
			<td>
				<?php echo get_field('location'); ?>
			</td>

			<td>
			<?php
			$date_format = get_option('date_format');
			$my_current_lang = apply_filters( 'wpml_current_language', NULL );

			//echo date_format($date,$date_format);
			echo formatLanguage($date, $date_format, $my_current_lang);

			if($event_end_datetime){
				echo date_format($date_end,$date_format);
			}

			?>
			</td>
			</tr>
			<?php
		}
		echo '</tbody>
		</table>';

	} else {

		echo "<h4 class='no_events_found'>".__('No events found.','nextcloud')."</h4>";

	}
	/* Restore original Post Data */
	wp_reset_postdata();
	?>
	
	
	<?php
	return ob_get_clean();
}
add_shortcode('events', 'events_list_func');


//print date and time for the webinars - to be used inside the email confirmation
add_shortcode('webinar_datetime_email', 'webinar_date_time_email_func');
function webinar_date_time_email_func($atts){
	$a = shortcode_atts( array(
		'lang' => '',
	), $atts );
	$lang = $a['lang'];
	$post_id = get_the_ID();

	if(isset($lang) && $lang != '') {
		$my_current_lang = $lang;
	} else {
		$my_current_lang = apply_filters( 'wpml_current_language', NULL );
	}

	$webinar_datetime = '';

	$date_format = get_option( 'date_format' ); // e.g. "F j, Y"
	$diff_days = 0;
	$event_start_datetime = get_field('event_start_date_and_time', $post_id, false);

	if($event_start_datetime) {
		$timezone = wp_timezone(); // Get WordPress timezone object
		$timezone_us = new DateTimeZone('America/New_York');

		$event_start_unix = strtotime($event_start_datetime);
		$date_start = date_create($event_start_datetime);
		$date_start_dayName = date_i18n("l", $event_start_unix);
		$date_start_format = date_i18n($date_format, $event_start_unix);

		$time_start_format = date_format($date_start,"g A");
		//$start_datetime = strtotime($event_start_datetime);
		$start_datetime2 = new DateTime('@' . $event_start_unix);
		//$start_day = gmdate("d", $event_start_unix);
		$start_day = gmdate("j", $event_start_unix);


		$start_day_suffix = "";
		if($my_current_lang == 'en' || $my_current_lang == 'de') {
			$start_day_suffix = gmdate("S", $event_start_unix);
		}

		//$date_start_dayName = gmdate("l", $event_start_unix);
		$start_month = gmdate("F", $event_start_unix);
		$start_month_intl = formatLanguage($start_datetime2, 'F', $my_current_lang);

		//get CET/CEST
		$cet_cest = wp_date('T', $event_start_unix, $timezone); // Get timezone abbreviation (CET/CEST)
        $timezone_abbr = $cet_cest;
		if($my_current_lang == 'de') {
			// Map English abbreviations to German ones
			$abbr_map = [
				'CET'  => 'MEZ',  // Mitteleuropäische Zeit
				'CEST' => 'MESZ', // Mitteleuropäische Sommerzeit
			];
			// Use mapping if available, fallback to original
			$timezone_abbr = $abbr_map[$cet_cest] ?? $cet_cest;
		}

        //get (EDT/EST)
		$edt_est = wp_date('T', $event_start_unix, $timezone_us); // Get timezone abbreviation (EDT/EST)


		//get time
		$date_custom_datetime = new DateTime($event_start_datetime, $timezone);

		if($lang == 'jp') {
			$date_custom_datetime->setTimezone(new DateTimeZone('Asia/Tokyo'));
			//$date_custom_datetime = new DateTime($event_start_datetime, new DateTimeZone('Asia/Tokyo'));
		}

		$time_start_format = $date_custom_datetime->format('g:i a');
        if($my_current_lang == 'de') {
			$time_start_format = $date_custom_datetime->format('H:i')." Uhr";
		}

		$date_custom_datetime->setTimezone($timezone_us);
		$time_start_format_us = $date_custom_datetime->format('g:i a');

		if($my_current_lang == 'en') {
			$webinar_datetime = $date_start_dayName.', '.$start_month_intl.' '.$start_day.$start_day_suffix.' '.__("at","nextcloud").' '.$time_start_format;
		} else {
			//other languages 
			$webinar_datetime = $date_start_dayName.', '.$start_day.$start_day_suffix.' '.$start_month_intl.' '.__("at","nextcloud").' '.$time_start_format;
		}
		


		$webinar_datetime .= " ";
		//$webinar_datetime .= " Lang:".$my_current_lang;


		if($lang == 'jp') {
			$webinar_datetime .= "(JST)";
		} else {
			$webinar_datetime .= $cet_cest;
			$webinar_datetime .= ' / '.$time_start_format_us.' '.$edt_est;
		}
		

	}

	return $webinar_datetime;
}




//get webinar / event details like time and duration
function webinar_details_func($atts){
	$a = shortcode_atts( array(
		'lang' => '',
	), $atts );
	$lang = $a['lang'];

	ob_start();
	$post_id = get_the_ID();
	$timezone = wp_timezone(); // Get WordPress timezone object
	$timezone_us = new DateTimeZone('America/New_York');
	
	if(isset($lang) && $lang != '') {
		$my_current_lang = $lang;
	} else {
		$my_current_lang = apply_filters( 'wpml_current_language', NULL );
	}

	$date_format = get_option( 'date_format' ); // e.g. "F j, Y"
	$diff_days = 0;
	$event_start_datetime = get_field('event_start_date_and_time', $post_id, false);

	if($event_start_datetime) {
		$event_start_unix = strtotime($event_start_datetime);
		$date_start = wp_date("Y-m-d H:i:s", $event_start_unix, $timezone );
		$date_start_dayName = date_i18n("l", $event_start_unix);
		$date_start_format = date_i18n($date_format, $event_start_unix);

		$start_datetime2 = new DateTime('@' . $event_start_unix);
		$start_day = gmdate("d", $event_start_unix);
		$start_day_week = gmdate("N", $event_start_unix);
		$start_month = gmdate("F", $event_start_unix);
		$start_month_intl = formatLanguage($start_datetime2, 'F', $my_current_lang);

		//get CET/CEST
		$cet_cest = wp_date('T', $event_start_unix, $timezone); // Get timezone abbreviation (CET/CEST)
		$timezone_abbr = $cet_cest;

		if($my_current_lang == 'de') {
			// Map English abbreviations to German ones
			$abbr_map = [
				'CET'  => 'MEZ',  // Mitteleuropäische Zeit
				'CEST' => 'MESZ', // Mitteleuropäische Sommerzeit
			];
			// Use mapping if available, fallback to original
			$timezone_abbr = $abbr_map[$cet_cest] ?? $cet_cest;
		}

		$edt_est = wp_date('T', $event_start_unix, $timezone_us); // Get timezone abbreviation (EDT/EST)

		//get EU time
		$date_custom_datetime = new DateTime($event_start_datetime, $timezone);
		$time_start_format = $date_custom_datetime->format('g:i a');
		if($my_current_lang == 'de') {
			$time_start_format = $date_custom_datetime->format('H:i')." Uhr";
		}

		//get US time
		$date_custom_datetime->setTimezone($timezone_us);
		$time_start_format_us = $date_custom_datetime->format('g:i a');
	}
	

	$event_end_datetime = get_field('event_end_date_and_time', $post_id, false);
	if($event_end_datetime){
		$event_end_unix = strtotime($event_end_datetime);
		$date_end = wp_date("Y-m-d H:i:s", $event_end_unix, $timezone );
		$end_datetime2 = new DateTime('@' . $event_end_unix);
		$end_day = gmdate("d", $event_end_unix);
		$end_month = gmdate("F", $event_end_unix);
		$end_month_intl = formatLanguage($end_datetime2, 'F', $my_current_lang);

		$diff_days = 0;
		$diff_days = (new DateTime($event_end_datetime))->diff(new DateTime($event_start_datetime))->days;
	}

	$event_passed = false; 
	if($event_end_unix < time()) {
		$event_passed = true; 
	}

 	if( $event_start_datetime && !$event_passed ) { ?>
		<ul class="webinar_details" data-event-passed="<?php echo $event_passed; ?>" data-lang="<?php echo $my_current_lang; ?>">
		<li><?php 
		echo "<b>".__('Date:','nextcloud')."</b> ";
		if( $diff_days > 0 ) {
			//multiple days
			if($my_current_lang == 'en') {
				if($start_month != $end_month) {
					//different months
					echo $start_month_intl." ".$start_day." - ".$end_month_intl." ".$end_day.", ".gmdate("Y", $start_datetime);
				} else {
					echo $start_month_intl." ".$start_day." - ".$end_day.", ".gmdate("Y", $start_datetime);
				}
			} else {
				//other languages
				if($start_month != $end_month) {
					echo $start_day." ".$start_month_intl." - ".$end_day." ".$end_month_intl.", ".gmdate("Y", $start_datetime);
				}else {
					echo $start_day." - ".$end_day." ".$start_month_intl.", ".gmdate("Y", $start_datetime);
				}
				
			}
		} else {
			//single day
			echo $date_start_dayName." ".$date_start_format;
		}
		?></li>
		<li><?php echo "<b>".__('Time:','nextcloud')."</b> ".$time_start_format." ".$timezone_abbr; 
		echo " / <span class='edt_time'>".$time_start_format_us." ".$edt_est."</span>";
		?>
		</li>

		<?php if($event_end_datetime){ ?>
			<li><?php 
				echo "<b>".__('Duration:','nextcloud')."</b>"; 
				$diff_mins = ($event_end_unix - $event_start_unix) / 60; // divided by 60 seconds, get minutes

				if($diff_mins <= 60) {
					echo " ".$diff_mins." ".__('minutes', 'nextcloud');
				} else {
					$diff_hours = ($event_end_unix - $event_start_unix) / 3600;

					if($diff_hours >= 24) {
						//$diff_hours_day = $diff_hours -  ($diff_days * 24);
						$diff_days_int = ceil($diff_hours/24);
						echo " ".$diff_days_int." ";
						if($end_day == $start_day) {
							echo __('day', 'nextcloud');
						}
						else {	
							echo __('days', 'nextcloud');
						}
						
					} else {
						echo " ".$diff_hours." ".__('hours', 'nextcloud');
					}			
				}

				?>
		</li>
		<?php } ?>
	</ul>
	<?php } else { 
		if(get_post_type() == 'on-demand-webinar') { ?>
		<div class="nc_warning_note with_icon">
			<?php 
			echo __('Webinar has passed and the recording is being prepared. Register to receive it.', 'nextcloud'); 
			?>
		</div>
	<?php 
		}
	} 
	?>
<?php
	return ob_get_clean();
}
add_shortcode('webinar_details', 'webinar_details_func');





function event_details_func($atts){
	$a = shortcode_atts( array(
		'lang' => '',
	), $atts );

	ob_start();
	$post_id = get_the_ID();

	$my_current_lang = apply_filters( 'wpml_current_language', NULL );
	if(isset($a['lang']) && $a['lang'] != '') {
		$my_current_lang = $a['lang'];
	}

	
	//$date_format = get_option( 'date_format' ); // e.g. "F j, Y"
	$date_format = "jS F";

	$diff_days = 0;
	$event_start_datetime = get_field('event_start_date_and_time', $post_id, false);
	if($event_start_datetime) {
		$date_start = date_create($event_start_datetime);
		$date_start_dayName = date_i18n("l", strtotime($event_start_datetime));
		$date_start_format = date_i18n($date_format, strtotime($event_start_datetime));

		$time_start_format = date_format($date_start,"g:i a");
		$start_datetime = strtotime($event_start_datetime);
		$start_datetime2 = new DateTime('@' . $start_datetime);
		$start_day = gmdate("d", $start_datetime);
		$start_day_week = gmdate("N", $start_datetime);
		$start_month = gmdate("F", $start_datetime);
		$start_month_intl = formatLanguage($start_datetime2, 'F', $my_current_lang);

		//get CET/CEST
		$date_start_utc = date_format($date_start,"Z");
		$utc_offset = $date_start_utc / 3600;
		$cet_cest = "(CET)";
		$edt_est = "(EST)";
		if($utc_offset>1) // 2 = CEST, 1 = CET
		{
			$cet_cest = "(CEST)";
			$edt_est = "(EDT)";
		}	

		//get time
		$date_custom_datetime = new DateTime($event_start_datetime, new DateTimeZone('Europe/Berlin'));
		$time_start_format = $date_custom_datetime->format('g:i a');
		$date_custom_datetime->setTimezone(new DateTimeZone('America/New_York'));
		$time_start_format_us = $date_custom_datetime->format('g:i a');
	}
	

	$event_end_datetime = get_field('event_end_date_and_time', $post_id, false);
	if($event_end_datetime){
		$date_end = date_create($event_end_datetime);
		$date_end_format = date_format($date_end,"F j, Y");
		$end_datetime = strtotime($event_end_datetime);
		$end_datetime2 = new DateTime('@' . $end_datetime);
		$end_day = gmdate("d", $end_datetime);
		$end_month = gmdate("F", $end_datetime);
		$end_month_intl = formatLanguage($end_datetime2, 'F', $my_current_lang);
		$diff_days = $date_end->diff($date_start)->format("%a");
	}

?>
	<ul class="webinar_details event_details">
	<?php if($event_start_datetime) { ?>
		<li class="date" lang="<?php echo $my_current_lang; ?>"><?php 
		echo "<b>".__('Date:','nextcloud')."</b> ";

		if( $diff_days > 0 ) {
			//multiple days
			if($my_current_lang == 'en') {
				if($start_month != $end_month) {
					//different months
					echo $start_month_intl." ".$start_day." - ".$end_month_intl." ".$end_day.", ".gmdate("Y", $start_datetime);
				} else {
					//same month
					echo $start_month_intl." ".$start_day." - ".$end_day.", ".gmdate("Y", $start_datetime);
				}
			} else {
				//other languages
				if($start_month != $end_month) {
					echo $start_day." ".$start_month_intl." - ".$end_day." ".$end_month_intl.", ".gmdate("Y", $start_datetime);
				}else {
					echo $start_day." - ".$end_day." ".$start_month_intl.", ".gmdate("Y", $start_datetime);
				}
				
			}
		} else {
			//single day
			echo $date_start_dayName." ".$date_start_format;
		}
		?></li>
		<?php } ?>


		<?php 
		if (get_field('keynote_presentation', $post_id, false)) { 
		?>
		<li class="keynote"><?php echo "<b>".__('Keynote:','nextcloud')."</b> ";

		$keynote_presentation = get_field('keynote_presentation', $post_id, false);
		$keynote_presentation_date = date_create($keynote_presentation);
		$keynote_presentation_date_format = date_i18n("F j, Y", strtotime($keynote_presentation));
		$keynote_presentation_time = date_format($keynote_presentation_date,"g:i a");
		echo $keynote_presentation_date_format." @ ".$keynote_presentation_time;
		
		?>
		</li>
		<?php } ?>

		<li class="location"><?php echo "<b>".__('Location:','nextcloud')."</b> ";
		echo get_field('location', $post_id, false);
		?>
		</li>

		<?php 
		$booth = get_field('booth', $post_id, false);
		if($booth){ ?>
			<li class="booth"><?php 
				echo "<b>".__('Booth:','nextcloud')."</b> ";
				echo $booth;
				?>
		</li>
		<?php } ?>
	
	</ul>
<?php
	return ob_get_clean();

}
add_shortcode('event_details', 'event_details_func');


//create custom date time field for the wp bakery custom shortcods
if(function_exists('vc_add_shortcode_param')) {
	
	function nc_wpb_datetime_settings_field( $settings, $value ) {
		$js = '<script>!function($) {
			$( ".datepicker" ).datepicker({
				dateFormat : "dd/mm/yy"
			});
		}(window.jQuery);</script>';

	return $js.'<div class="datetime_field">'
	.'<input name="' . esc_attr( $settings['param_name'] ) . '" class="datepicker wpb_vc_param_value wpb-textinput ' .
	esc_attr( $settings['param_name'] ) . ' ' .
	esc_attr( $settings['type'] ) . '_field" type="text" value="' . esc_attr( $value ) . '" />' .
	'</div>'; // This is html markup that will be outputted in content elements edit form
	}

	vc_add_shortcode_param( 'datetime', 'nc_wpb_datetime_settings_field', get_template_directory_uri().'/vc_extend/datetime.js' );
	
}


//Event list repeater
add_action('vc_before_init', 'events_list_repeater_items_funct');
function events_list_repeater_items_funct() {
	vc_map(
		  array(
		  	"name" => __("Events list", "nextcloud"), // Element name
		  	"base" => "events_list", // Element shortcode
		  	"class" => "events_list",
		  	"category" => __('Repeater', 'nextcloud'),
		  	'params' => array(

				array(
					"type" => "textfield",
					"heading" => esc_html__("Year", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "year",
					"value" => "",
				),

				array(
						'type' => 'dropdown',
						'heading' => 'Type',
						'param_name' => 'type',
						'value' => array(
							'Events' => 'events',
							'Enterprise Days' => 'enterprise_days',
							'Meetups' => 'meetups'
						),
				),

		  		array(
		  			'type' => 'param_group',
		  			'param_name' => 'box_repeater_items',
		  			'params' => array(
	
		  				array(
		  					"type" => "textfield",
		  					"holder" => "div",
		  					"class" => "",
		  					"admin_label" => true,
		  					"heading" => __("Event Title", "nextcloud"),
		  					"param_name" => "title",
		  					"value" => __("", "nextcloud"),
		  				),

						array(
							"type" => "textfield",
							"heading" => esc_html__("Location", "nextcloud"),
							"description" => esc_html__("", "nextcloud"),
							"param_name" => "location",
							"value" => "",
						),

						array(
							"type" => "datetime",
							"heading" => esc_html__("Starting date", "nextcloud"),
							"description" => esc_html__("", "nextcloud"),
							"param_name" => "date_start",
							"value" => "",
						),

						array(
							"type" => "datetime",
							"heading" => esc_html__("Ending date", "nextcloud"),
							"description" => esc_html__("", "nextcloud"),
							"param_name" => "date_end",
							"value" => "",
						),

						array(
							"type" => "textfield",
							"heading" => esc_html__("Approximate event date", "nextcloud"),
							"description" => esc_html__("", "nextcloud"),
							"param_name" => "approximate_event_date",
							"value" => "",
						),
						

		  				array(
		  					"type" => "vc_link",
		  					"holder" => "div",
		  					"class" => "link",
		  					"admin_label" => true,
		  					"heading" => __("Link", "nextcloud"),
		  					"param_name" => "link",
		  					"value" => __("", "nextcloud"),
		  				)


		  			)
		  		),
		  	)
		  )
	  );
}

add_shortcode('events_list', 'events_list_funct');
function events_list_funct($atts) {
	ob_start();
	$atts = shortcode_atts(array(
		'year' => '',
		'type' => 'events',
		'box_repeater_items' => '',
	), $atts, 'box_repeater');

	if(function_exists('vc_param_group_parse_atts')){
		$items = vc_param_group_parse_atts($atts['box_repeater_items']);
	}

	$year = $atts['year'];
	$type = $atts['type'];

	$events = array();
	$timezone = new DateTimeZone('Europe/Berlin');
	?>
    <?php
		if ($items) {
			foreach ($items as  $item) {

					if (isset($item['link'])) {
						$link = vc_build_link($item['link']);
						$link_debug = vc_build_link($item['link']);
					}

					$date_start_timestamp = '';
					if(isset($item['date_start'])){
					$date_start = DateTime::createFromFormat('d/m/Y', $item['date_start'], $timezone);
					$date_start_timestamp = $date_start->getTimestamp();
					}

					$date_end_timestamp = '';
					if(isset($item['date_end'])){
						$date_end = DateTime::createFromFormat('d/m/Y', $item['date_end'], $timezone);
						$date_end_timestamp = $date_end->getTimestamp();
					}

					$approximate_event_date = '';
					if(isset($item['approximate_event_date'])) {
						$approximate_event_date = $item['approximate_event_date'];
					}

					//get manually added events
					$events[] = array(
						'title' => isset($item['title']) ? $item['title'] : '',
						'location' => isset($item['location']) ? $item['location'] : '',
						'date_start' => $date_start_timestamp,
						'date_end' => $date_end_timestamp,
						'approximate_event_date' => $approximate_event_date,
						'link' => isset($item['link']) ? $link['url'] : '',
						'link_debug' => isset($link_debug) ? $link_debug : '',
						'target' => isset($item['link']) ? $link['target'] : '',
					);		
	
			}		
        }
		
		
		//echo $type; 
		if($type == "enterprise_days") {
			//get only Enterprise Days
			$args = array(
				'post_type' => 'page',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'tag' => 'enterprise-day',
				'meta_query' => array(
					array(
						'key' => 'event_start_date_and_time',
						'value'   => array( $year.'-01-01 00:00:00', $year.'-12-31 00:00:00' ),
						'type'    => 'DATETIME',
						'compare' => 'BETWEEN',
					),
				)
			);

		} else {
			//add events custom post type
			$args = array(
				'post_type' => 'event',
				'post_status' => 'publish',
				'posts_per_page' => '-1',
				'tax_query' => array(
					array(
						'taxonomy' => 'event_categories',
						'field'    => 'slug',
						'terms'    => array('webinars'),
						'operator' => 'NOT IN',
					),
				),
				'meta_query' => array(
					array(
						'key' => 'event_start_date_and_time',
						'value'   => array( $year.'-01-01 00:00:00', $year.'-12-31 00:00:00' ),
						'type'    => 'DATETIME',
						'compare' => 'BETWEEN',
					),
				),
				'tag__not_in' => array(269) // exclude unlisted tag
			);
		}

		
		$the_query = new WP_Query($args);
		if ( $the_query->have_posts() && $type != "meetups" ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();

				$event_start_datetime = get_field('event_start_date_and_time', false, false);
				//echo $event_start_datetime;
				$event_end_datetime = get_field('event_end_date_and_time', false, false);
				//echo $event_end_datetime;


				if($event_start_datetime){
					$date_start = DateTime::createFromFormat('Y-m-d H:i:s', $event_start_datetime, $timezone);
					$date_start_timestamp = $date_start->getTimestamp();
				}
				
				if($event_end_datetime){
					$date_end = DateTime::createFromFormat('Y-m-d H:i:s', $event_end_datetime, $timezone);
					$date_end_timestamp = $date_end->getTimestamp();
				} else {
					$date_end_timestamp = $date_start_timestamp; // set the same day; usually these are 1 day events
				}
				

				$events[] = array(
					'id' => get_the_ID(),
					'title' => get_the_title(),
					'short_title' => get_field('event_short_title'),
					'location' => get_field('location'),
					'date_start' => $date_start_timestamp,
					'date_end' => $date_end_timestamp,
					'approximate_event_date' => get_field('approximate_event_date'),
					'link' => get_field('blog_post_url') ? get_field('blog_post_url') : get_the_permalink(),
					'target' => get_field('blog_post_url') ? '_blank' : '',
				);	


			}
		}
		/* Restore original Post Data */
		wp_reset_postdata();




		//order by date
		if($events) {

			$sortArray = array();
			$approxSort = array();

			foreach($events as $event){
				foreach($event as $key => $value){
					if(!isset($sortArray[$key])){
						$sortArray[$key] = array();
					}
					$sortArray[$key][] = $value;
				}

				// 0 = normal events first, 1 = approximate dates last
				$approxSort[] = !empty($event['approximate_event_date']) ? 1 : 0;
			}

			$orderby = "date_start";

			if($year >= date("Y")) {
				array_multisort(
					$approxSort, SORT_ASC,      // approximate dates last
					$sortArray[$orderby], SORT_ASC,
					$events
				);
			} else {
				array_multisort(
					$approxSort, SORT_ASC,      // approximate dates last
					$sortArray[$orderby], SORT_DESC,
					$events
				);
			}


			/*
			$sortArray = array(); 
			foreach($events as $event){
				foreach($event as $key=>$value){
					if(!isset($sortArray[$key])){
						$sortArray[$key] = array();
					}
					$sortArray[$key][] = $value;
				}
			}
			$orderby = "date_start"; //change this to whatever key you want from the array 
			//change this to whatever key you want from the array
			if($year >= date("Y")) {
				//if year is the current year
				array_multisort($sortArray[$orderby],SORT_ASC,$events);
			} else {
				//year is past
				array_multisort($sortArray[$orderby],SORT_DESC,$events);
			}
			*/



			//get the next event
			$now = new DateTime();
			$now_timestamp = $now->getTimestamp();   
			$past_events = array();
			foreach($events as $id => $event){
				foreach($event as $key=>$value){

					if($key == 'date_end') {

						if($value) {
							$diff = $now_timestamp - $value;
							$days_diff = round($diff / (60 * 60 * 24));
						}

						if($year == date("Y")) {
							if($days_diff > 0) {
								//means it is passed
								array_push($past_events, $event);
								unset($events[$id]);
							}
						}
					}
				}
			}
		
		$events_label = __('Event', 'nextcloud');
		$extra_css_class = "";
		if($type == "enterprise_days") {
			$extra_css_class = "enterprise_days";
		}

		echo '<table class="table events_table '.$extra_css_class.' events table-striped">';
				echo '<thead>';
				echo '<tr>';
				if($type == "enterprise_days") {
					$events_label = __('Enterprise Day', 'nextcloud');
				} else if($type == "meetups") {
					$events_label = __('Meetup', 'nextcloud');
				}
				echo '<th>'.$events_label.'</th>
				<th>'.__('Date', 'nextcloud').'</th>
				<th>'.__('Location', 'nextcloud').'</th>
				</tr>';
				echo '</thead>';
				echo '<tbody>';


			$current_lang = apply_filters( 'wpml_current_language', null );
    		$default_lang = apply_filters( 'wpml_default_language', null );
			$flags = [
				"🇳🇱" => ["netherlands", "hague"],
				"🇨🇭" => ["bern", "switzerland"],
				"🇦🇹" => ["austria", "vienna"],
				"🇪🇸" => ["barcelona", "madrid"],
				"🇫🇷" => ["paris", "france"],
				"🇩🇰" => ["copenhagen", "denmark"],
				"🇩🇪" => ["germany", "munich"]
			];

			foreach ($events as  $event) {

				$title = ""; 
				isset($event['short_title']) ? $title = $event['short_title'] : $title = $event['title'];
				?>
				<tr data-date-end="<?php echo $event['date_end']; ?>">
						<td>

							<a data-lang="<?php echo $current_lang; ?>" data-link="<?php 
							echo apply_filters( 'wpml_permalink', $event['link'], $current_lang ); 
							//echo $event['link'];
							?>" href="<?php 
							
								if( isset($event['link']) ) { 

									if($type == "meetups") {
										echo "#register_meetup";
									} else {
										echo apply_filters( 'wpml_permalink', $event['link'], $current_lang ); 
									}
									
								} else 
								{ 
									echo "#";
								} 
								?>" title="<?php echo $title; ?>" target="<?php if($type != "meetups") {
									echo "_blank";
								} ?>" 
								class="<?php 
								if($type == "meetups") {
									echo " open-popup-link-a meetup_link ";
								}
								?>">

								<?php 
								if($type == "enterprise_days") {

									$location = $event['location'];
									foreach ($flags as $flag => $keywords) {
										foreach ($keywords as $word) {
											if (stripos($location, $word) !== false) {
												echo $flag;
												break 2; // stop both loops once matched
											}
										}
									}
								}
								?>
								<?php echo $title; ?>
							</a>

							<?php 
							if($type == "meetups" && isset($event['link']) && $event['link'] != '' ) {
								?>
								<div class="meetup_see_more">
								<a class="see_more" href="<?php echo $event['link']; ?>" target="_blank">
									<span>
									<?php echo __('More about this meetup','nextcloud'); ?> </span>
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
										<path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h560v-280h80v280q0 33-23.5 56.5T760-120H200Zm188-212-56-56 372-372H560v-80h280v280h-80v-144L388-332Z"></path>
									</svg>
								</a>
								</div>
								<?php 
							}
							?>
							

						</td>
						<td><?php 
						if( isset($event['approximate_event_date']) && $event['approximate_event_date'] != '' ) {
							echo $event['approximate_event_date'];
						}
						else {
							echo get_event_date($event['date_start'], $event['date_end']);
						}
						?>
						</td>
						<td><?php echo $event['location']; ?></td>
				</tr>

				<?php
			}

			//list past events
			if($past_events && $type != "enterprise_days" ) {

				$sortArray2 = array(); 
				foreach($past_events as $event){
					foreach($event as $key=>$value){
						if(!isset($sortArray2[$key])){
							$sortArray2[$key] = array();
						}
						$sortArray2[$key][] = $value;
					}
				}
				$orderby = "date_start"; //change this to whatever key you want from the array 
				//change this to whatever key you want from the array
				array_multisort($sortArray2[$orderby],SORT_DESC,$past_events);
				?>

				<tr class="tr_btn_past_events" style="text-align: center;">
				<td colspan="3">
					<a href="#" class="open_past_events" data-year="<?php echo $year; ?>">
						<span>
						<?php 
						if($type == "enterprise_days") {
							echo __('All Enterprise Days in','nextcloud')." ".$year;
						} else if($type == "meetups") {
							echo __('All meetups in','nextcloud')." ".$year;
						}
						else {
							echo __('All events in','nextcloud')." ".$year;
						}
						?>
						</span>
						<i class="fas fa-angle-down"></i>
					</a>
				</td>
				</tr>

				<?php
				foreach ($past_events as  $event) {
					?>
					<tr class="past_events" style="display: none; ">
					<td>
								<a href="<?php if( $event['link'] ) { echo $event['link']; } else { echo "#"; } ?>" title="<?php echo $event['title']; ?>" target="<?php 
								if($event['target']) {
									echo $event['target'];
								} ?>" class="">

								<?php 
								if($type == "enterprise_days") {

									$location = $event['location'];
									foreach ($flags as $flag => $keywords) {
										foreach ($keywords as $word) {
											if (stripos($location, $word) !== false) {
												echo $flag;
												break 2; // stop both loops once matched
											}
										}
									}
								}
								?>

								<?php if(isset($event['short_title']) && $event['short_title']!='') {
									echo $event['short_title'];
								} else {
									echo $event['title'];
								} ?>
								</a>
							</td>
							<td>
							<?php echo get_event_date($event['date_start'], $event['date_end']); ?></td>
							<td>
								<?php echo $event['location']; ?>
							</td>
					</tr>
					<?php
				}


			}
			echo '</tbody>';
			echo '</table>';
		}
		?>
      <?php
	  $result = ob_get_clean();
	return $result;
}


if( ! function_exists( 'nc_better_comments' ) ):
	function nc_better_comments($comment, $args, $depth) {
		?>
	   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
		<div class="comment_inner">
		<div class="comment_infos">
			<div class="img-thumbnail d-none d-sm-block">
				<?php echo get_avatar($comment, $size='80' ); ?>
			</div>


			<div class="comment_meta">
					<span class="comment-by">
								<strong><?php echo get_comment_author() ?></strong>
								<span class="reply_btn float-right">
									<?php
									comment_reply_link($args);
									?>
									<!--
									<span><a href="#"><i class="fa fa-reply"></i></a></span>
									-->

								</span>
					</span>

					<span class="comment-date"><?php printf(/* translators: 1: date and time(s). */ esc_html__('%1$s at %2$s' , 'nextcloud'), get_comment_date(),  get_comment_time()) ?></span>


					<div class="comment-block">
						<div class="comment-arrow"></div>
							<?php if ($comment->comment_approved == '0') : ?>
								<em><?php esc_html_e('Your comment is awaiting moderation.','nextcloud') ?></em>
								<br />
							<?php endif; ?>
							
						<div class="comment_text"><?php comment_text() ?></div>
						
					</div>
			</div>
		</div>
		</div>
	
	<?php
			}
endif;



// Customizes the comment_html template.
function my_wpd_comment_html( $input ) {
	ob_start();
	?>
	<li class="comment depth-1">
		<article class="comment-body comment_inner ">

		<div class="comment_infos">

				<div class="img-thumbnail">
						<img alt="Avatar for {username}" src="{avatar_url}" class="avatar avatar-80 photo"
							height="80"
							width="80">
				</div>

				<div class="comment_meta">
						<div class="comment-author vcard">
							
							<b class="fn"><a href="{topic_url}" target="_blank" rel="external"
											class="url">{username}</a></b>
							<span class="says screen-reader-text"><?php echo esc_html( 'says:', 'wp-discourse' ); ?></span><!-- screen reader text -->
						</div>
						<div class="comment-metadata">
							<time datetime="{comment_created_at}">{comment_created_at}</time>
						</div>


						<div class="comment-content comment-block">{comment_body}</div>

				</div>
		</div>
			

		</article>
	</li>
	<?php
	$output = ob_get_clean();
return $output;
}
// Hook into the function from the plugin's code.
add_filter(  'discourse_comment_html', 'my_wpd_comment_html' );



//This removes the ‘participants’ section from the replies_html template.
function my_namespace_remove_participants_html( $input ) {
    ob_start();
    ?>
    <div id="comments" class="comments-area discourse-comments-area">
        <ul class="comment-list post-comments">{comments}</ul>  
    </div>
    <?php
    $output = ob_get_clean();

    return $output;
}
add_filter( 'discourse_replies_html', 'my_namespace_remove_participants_html' );


add_shortcode('whitepapers_list', 'whitepapers_list_func');
function whitepapers_list_func($atts){
	ob_start();
	$default_posts_per_page = get_option( 'posts_per_page' );

	$a = shortcode_atts( array(
		'type' => '',
		'limit' => -1
	), $atts );
	$type = $a['type'];
	$limit = $a['limit'];

	$args = array(
		'post_type' => $type,
		'post_status' => 'publish',
		'posts_per_page' => $limit,
		'orderby' => 'date',
		'order' => 'DESC',
		'tag__not_in' => array(269) // exclude unlisted tag
	);
	

	$the_query = new WP_Query($args);
	$count = $the_query->found_posts;

	// The Loop
	if ( $the_query->have_posts() ) {
		
		echo '<div class="row row-list-blog" data-count="'.$count.'" data-limit="'.$limit.'">';

		while ( $the_query->have_posts() ) {
			$the_query->the_post();
				get_template_part('inc/whitepaper_loop_single');
		}
		echo '</div>';

	}
	wp_reset_postdata();

	if( $limit > 0 && ($limit < $count) ) { ?>
		<div class="row loadNews_row">
			<div class="col-12">
				<div class="section-button">
					<button class="c-btn btn-main loadNews" id="loadNews_<?php echo $type; ?>" data-count="<?php echo $count; ?>" data-limit="<?php echo $limit; ?>" data-post-type="<?php echo $type; ?>">
					<?php echo __('Load more','nextcloud'); ?></button>
				</div>
			</div>
		</div>
	<?php }
	
	$result = ob_get_clean();
	return $result;
}



add_shortcode('whitepapers_posts', 'whitepapers_posts_func');
function whitepapers_posts_func($atts){
	ob_start();

	$a = shortcode_atts( array(
		'type' => 'whitepaper_posts',
		'limit' => -1,
		'include_podcasts' => false
	), $atts );

	$limit = $a['limit'];
	$type = $a['type'];
	$include_podcasts = $a['include_podcasts'];

	$ppp   = (int) $limit;                      // posts per page
	$paged = max(1, (int) get_query_var('paged'));
	$offset = ($paged - 1) * $ppp;
	$whitepaper_taxonomy_id = apply_filters( 'wpml_object_id', 9, 'category', TRUE  );
	$success_story_taxonomy_id = apply_filters( 'wpml_object_id', 418, 'category', TRUE  );

	// get first the blog posts with the Whitepaper and Success story categories
	$q_posts = new WP_Query([
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'category__in'   => [$whitepaper_taxonomy_id, $success_story_taxonomy_id],
		'posts_per_page' => -1,
		'orderby' => 'date',
		'order' => 'DESC'
	]);
	$all_posts = $q_posts->posts;


	
	if($include_podcasts) {
		//get also the podcasts, tagged with Case story tag
		$q_podcasts = new WP_Query([
			'post_type'      => array('podcast'),
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'tax_query'      => [[
				'taxonomy' => 'post_tag',
				'field'    => 'term_id',
				'terms'    => [466],
			]],
			'orderby' => 'date',
			'order' => 'DESC'
		]);

		// Merge + sort by date DESC
		//$all_posts = $q_podcasts->posts;
		$all_posts = array_merge($q_posts->posts, $q_podcasts->posts);
		usort($all_posts, function ($a, $b) {
			return strcmp($b->post_date, $a->post_date);
		});

	}


	$count = count($all_posts);
	$items = array_slice($all_posts, $offset, $ppp);

	if (!empty($items)) {
		echo '<div class="row row-list-blog">';
		foreach ($items as $post_obj) {
			//$post_obj = get_post( $post_id );  // convert ID → WP_Post object
			if ( $post_obj ) {
					$old_post = $GLOBALS['post'] ?? null;
					$GLOBALS['post'] = $post_obj;   // switch context
					setup_postdata( $post_obj );
					get_template_part('inc/blog_loop_single');
					wp_reset_postdata();
					$GLOBALS['post'] = $old_post;// restore
			}
		}
		echo '</div>';
	}
	wp_reset_postdata();


	/*
	else {
		$args = array(
			'post_type' => array('post'),
			'tax_query' => array(
				array(
					'taxonomy' => 'category',
					'field'		=> 'id',
					'terms'		=> array($whitepaper_taxonomy_id, $success_story_taxonomy_id),
				)
			),
			'post_status' => 'publish',
			'posts_per_page' => $limit,
			'orderby' => 'date',
			'order' => 'DESC'
		);
		$the_query = new WP_Query($args);
		$count = $the_query->found_posts;

		
		if ( $the_query->have_posts() ) {
			echo '<div class="row row-list-blog">';
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
					//get_template_part('inc/whitepaper_posts_loop_single');
					get_template_part('inc/blog_loop_single');
			}
			echo '</div>';
		}
		wp_reset_postdata();
	}*/



	if( $limit > 0 && ($limit < $count) ) { ?>
		<div class="row loadNews_row">
			<div class="col-12">
				<div class="section-button">
					<button class="c-btn btn-main loadNews" data-podcasts="<?php echo $include_podcasts; ?>" id="loadNews_<?php echo $type; ?>" data-count="<?php echo $count; ?>" data-limit="<?php echo $limit; ?>" data-post-type="<?php echo $type; ?>" >
					<?php echo __('Load more','nextcloud'); ?></button>
				</div>
			</div>
		</div>
	<?php }


	$result = ob_get_clean();
	return $result;
}




//Repeater Quotes Carousel
add_action('vc_before_init', 'quotes_repeater_items_funct');
function quotes_repeater_items_funct() {
	vc_map(
		  array(
		  	"name" => __("Quotes Carousel", "nextcloud"), // Element name
		  	"base" => "quotes_carousel", // Element shortcode
		  	"class" => "quotes_carousel",
		  	"category" => __('Carousel', 'nextcloud'),
		  	'params' => array(

		  		array(
		  			'type' => 'param_group',
		  			'param_name' => 'box_repeater_items',
		  			'params' => array(
		  				array(
		  					"type" => "attach_image",
		  					"holder" => "img",
		  					"class" => "",
		  					"heading" => __("Feature Image", "nextcloud"),
		  					"param_name" => "feature_image",
		  					"value" => __("", "nextcloud"),
		  				),
		  				array(
		  					"type" => "textfield",
		  					"holder" => "div",
		  					"class" => "",
		  					"admin_label" => true,
		  					"heading" => __("Quote Title", "nextcloud"),
		  					"param_name" => "title",
		  					"value" => __("", "nextcloud"),
		  				),

						array(
							"type" => "textarea",
							"heading" => esc_html__("Description", "nextcloud"),
							"description" => esc_html__("", "nextcloud"),
							"param_name" => "description",
							"value" => "",
						),

		  				array(
		  					"type" => "vc_link",
		  					"holder" => "div",
		  					"class" => "client_link",
		  					"admin_label" => true,
		  					"heading" => __("Link", "nextcloud"),
		  					"param_name" => "link",
		  					"value" => __("", "nextcloud"),
		  				)

		  			)
		  		),

				array(
					"type" => "textfield",
					"heading" => esc_html__("Custom CSS Classes", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "custom_css",
					"value" => "",
				)

		  	)
		  )
	  );
}

add_shortcode('quotes_carousel', 'quotes_carousel_funct');
function quotes_carousel_funct($atts) {
	ob_start();
	$atts = shortcode_atts(array(
		'box_repeater_heading' => '',
		'box_repeater_items' => '',
		'custom_css' => ''
	), $atts, 'box_repeater');

	$custom_css = $atts['custom_css'];

	if(function_exists('vc_param_group_parse_atts')){
		$items = vc_param_group_parse_atts($atts['box_repeater_items']);
	}
	?>
      <div class="case_studies">

          <?php if ($items) { ?>
              <div class="quotes_carousel owl-carousel owl-theme <?php if(isset($custom_css)) echo $custom_css; ?>">
                  <?php  foreach ($items as  $item) {
					
					//print_r($item['box_repeater_items_link']);
					if (isset($item['link'])) {
						$link = vc_build_link($item['link']);
					}

					$custom_css_item = '';
					if(isset( $item['custom_css'] )) {
						$custom_css_item = $item['custom_css'];
					}
					?>

						<div class="case_study <?php echo $custom_css_item; ?>">
							<div class="vc_column-inner">
					
							<div class="wpb_single_image wpb_content_element vc_align_left image_top">
								<figure>

								<?php if (isset($item['link'])) { ?>
									<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" title="<?php echo $item['title']; ?>" class="vc_single_image-wrapper   vc_box_border_grey">
								<?php } ?>
									
								<?php echo wp_get_attachment_image($item['feature_image'], 'large'); ?>

								<?php if ($item['link']) { ?>
									</a>
								<?php } ?>


								
								</figure>
							</div>
							

							<div class="organization">
								<div class="wpb_wrapper">
									<h4>
										<?php echo $item['title']; ?>
									</h4>
								</div>
								
							</div>

							<?php if(isset($item['description'])) { ?>
							<div class="wpb_text_column wpb_content_element  quote"><?php echo $item['description']; ?></div>
							<?php } ?>

							<?php if (isset($item['link'])) { ?>
							<div class="vc_btn3-container  btn-main btn-small vc_btn3-center">
								<?php if (isset($item['link'])) { ?>
									<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" title="<?php echo $item['title']; ?>" class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-rounded vc_btn3-style-modern vc_btn3-icon-right vc_btn3-color-grey">
								<?php } ?>
								<?php 
								echo __('Learn more', 'nextcloud'); 
								?> <i class="vc_btn3-icon fas fa-angle-right"></i>
								<?php if ($item['link']) { ?>
									</a>
								<?php } ?>
							</div>
							<?php } ?>




							</div>
						</div>
					

             
                  <?php
					} ?>
              </div>


				<script>
					//jQuery(document).ready(function ($) {
						jQuery('.quotes_carousel').owlCarousel({
							loop:false,
							autoplay: false,
							margin:15,
							dots: false,
							nav:true,
							navText : ['<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 14.299 22.409"><path fill="#0082c9" class="st0" d="M11.25,21.205L1.25,11.205,11.25,1.205l1.8,1.8L4.85,11.205l8.2,8.2-1.8,1.8h0Z"/></svg>','<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 14.299 22.409"><path fill="#0082c9" class="st0" d="M3.05,21.205l-1.8-1.8,8.2-8.2L1.25,3.005l1.8-1.8,10,10L3.05,21.205h0Z"/></svg>'],
							responsive:{
								0:{
									items:1
								},
								800:{
									items:2
								},
								1000:{
									items: 3
								}
							}
						});
					//}); 

					
				</script>

          <?php } ?>

      </div>
      <?php
	  $result = ob_get_clean();
	return $result;
}



add_shortcode('sector_selected', 'sector_selected_funct');
function sector_selected_funct($atts) {
	ob_start();
	if(isset($_GET['sector'])) {
?>
<script>
jQuery( document ).ready( function( $ ) {
	jQuery(document).on( 'nfFormReady', function( e, layoutView ) {
		var selected_sector = '<?php echo strip_tags($_GET['sector']); ?>';
		//console.log(selected_sector);
		jQuery('.nc_images_radio_buttons input[type=radio]').each(function(){
			if(jQuery(this).val() == selected_sector){
				jQuery(this).addClass('nf-checked');
				jQuery(this).next('label').addClass('nf-checked-label');
				jQuery(this).trigger('click');
			}
		});
		var sector_section = '#<?php echo strip_tags($_GET['sector']); ?>-row';
		//console.log(sector_section);
		jQuery('.special_pricing').removeClass('hidden');
		jQuery('.special_pricing').find(sector_section).removeClass('hidden');
	});
});
</script>
<?php
	}
$result = ob_get_clean();
return $result;
}




add_shortcode('plan_selected', 'plan_selected_funct');
function plan_selected_funct($atts) {
	ob_start();
	if(isset($_GET['plan'])) {
?>
<script>
jQuery( document ).ready( function( $ ) {
	jQuery(document).on( 'nfFormReady', function( e, layoutView ) {
		var selected_plan = '<?php echo strip_tags($_GET['plan']); ?>';
		//console.log(selected_sector);
		jQuery('.nc_images_radio_buttons.plan input[type=radio]').each(function(){
			if(jQuery(this).val() == selected_plan){
				jQuery(this).addClass('nf-checked');
				jQuery(this).next('label').addClass('nf-checked-label');
				jQuery(this).trigger('click');
			}
		});
	});
});
</script>
<?php
	}
$result = ob_get_clean();
return $result;
}



add_shortcode('product_selected', 'product_selected_funct');
function product_selected_funct($atts) {
	$a = shortcode_atts( array(
		'form' => 'instant-trial' // default for Instant trial
	), $atts );

	ob_start();
	if(isset($_GET['product'])) {
?>
	<script>
	jQuery( document ).ready( function( $ ) {
		jQuery(document).on( 'nfFormReady', function( e, layoutView ) {
			var selected_products = '<?php echo strip_tags($_GET['product']); ?>';
			//console.log(selected_sector);
			var selected_products_array = selected_products.split(',');

			$(selected_products_array).each(function( index ) {
				var curr_product = selected_products_array[index];

				<?php if($a['form'] == 'instant-trial') { ?>
					jQuery('.nc_images_radio_buttons.product input[type=checkbox]').each(function(){
						if(jQuery(this).val() == curr_product){
							jQuery(this).addClass('nf-checked');
							jQuery(this).next('label').addClass('nf-checked-label');
							jQuery(this).trigger('click');
						}
					});
				<?php }
				else if ($a['form'] == 'poc') { ?>
				var curr_product_label = "Nextcloud " + curr_product;
					jQuery('.nc_products_checkboxes input[type=checkbox]').each(function(){
						if(jQuery(this).val() == curr_product_label){
							jQuery(this).addClass('nf-checked');
							jQuery(this).next('label').addClass('nf-checked-label');
							jQuery(this).trigger('click');
						}
					});
				<?php } ?>
			});
		});
	});
	</script>
<?php
}
$result = ob_get_clean();
return $result;
}



//Event list repeater
add_action('vc_before_init', 'nc_element_team_select_funct');
function nc_element_team_select_funct() {
	vc_map(
		  array(
		  	"name" => __("Team members list", "nextcloud"), // Element name
		  	"base" => "team_members_list", // Element shortcode
		  	"class" => "team_members_list",
			"category" => __('Content', 'nextcloud'),
			//'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
  			//'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
		  	'params' => array(
					array(
					//"type" => "post_select",
					"type" => "textarea",
					"heading" => esc_html__("Team members", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "team_members",
					"value" => "",
					),

					array(
						"type" => "textfield",
						"heading" => esc_html__("Team members Tag", "nextcloud"),
						"description" => esc_html__("", "nextcloud"),
						"param_name" => "team_members_tag",
						"value" => "",
					),

					//custom CSS class
					array(
						"type" => "textfield",
						"heading" => esc_html__("Custom CSS Classes", "nextcloud"),
						"description" => esc_html__("", "nextcloud"),
						"param_name" => "css_classes",
						"value" => "",
					),
			)
		  )
	  );
}
add_shortcode('team_members_list', 'team_members_list_funct');
function team_members_list_funct($atts) {
	ob_start();
	$atts = shortcode_atts(array(
		'team_members' => '',
		'team_members_tag' => '',
		'css_classes' => 'col-lg-4 col-md-6'
	), $atts);

	$items = $atts['team_members'];
	$team_members_tag = $atts['team_members_tag'];

	if($items == 'all') {
		$team = new WP_Query( array (
			'post_type'	=> 'team_members',
			'post_status ' => 'publish',
			'posts_per_page' => -1
		));
		$items_arr = wp_list_pluck( $team->posts, 'ID' );

	} else if ($team_members_tag) {
		// if tag is selected
		//echo $team_members_tag;

		$team = new WP_Query( array (
			'post_type'	=> 'team_members',
			'post_status ' => 'publish',
			'posts_per_page' => -1,
			//'tag' => $team_members_tag,
			'suppress_filters' => 1,
			'tax_query' => array(
				array( 
					'taxonomy' => 'post_tag', 
					'field' => 'name', 
					'terms' => $team_members_tag
					)
				),
			'orderby' => 'menu_order',
			'order' => 'ASC'
		));
		$items_arr = wp_list_pluck( $team->posts, 'ID' );

	}
	else {
		$items_arr = preg_split ("/\,/", $items);
	}

	if($items_arr) {
		?>

		<section class="team-section" id="">
		<div class="container">
		<div class="row">

	<?php
	foreach ($items_arr as &$item) {
		$id = $item;
		//echo $id;
		$img = wp_get_attachment_url(get_post_thumbnail_id($id) ?: 0) ?: '';
		$header = get_the_title($id);
		$bio = get_field('biography', $id);
		$pos = get_field('position', $id);
		$desc = get_field('position_description', $id);
		$social = get_field('social_links', $id);

?>
<div class="spacer <?php echo $atts['css_classes']; ?>">
<div class="member-holder">
	<?php
	if (!empty($img)) {
		echo '<div class="member-img" style="background-image:url(' . $img . ');"></div>';
	} else {
		echo '<div class="member-img" style="background-image:url(' . get_stylesheet_directory_uri() . '/dist/img/person.jpg);"></div>';
	}
	?>
	<div class="member-body">
		<?php
		if (!empty($header)) {
			echo '<h4>' . $header . '</h4>';
		}
		if (!empty($pos)) {
			echo '<h5>' . $pos . '</h5>';
		}
		if (!empty($desc)) {
			echo '<h6>' . $desc . '</h6>';
		}
		if (!empty($bio)) {
			echo wpautop($bio);
		}
		
		if ($social) {
			echo '<ul>';
			foreach ($social as $sm) {
				$icon = $sm['social_media_icon'];
				$link = $sm['social_media_link'];
				echo '<li>';
				echo '<a target="_blank" href="' . $link . '">';
				echo '<img src="' . $icon . '" alt="Social media"/>';
				echo '</a>';
				echo '</li>';
			}
			echo '</ul>';
		}
		
		?>
	</div>
</div>
</div>
<?php
}
?>
		</div>
	</div>
</section>
<?php
}
$result = ob_get_clean();
return $result;
}


//featured blogs carousel shortcode
add_action('vc_before_init', 'nc_custom_blogs_list_carousel_funct');
function nc_custom_blogs_list_carousel_funct() {
	vc_map(
		  array(
		  	"name" => __("Custom blogs list carousel", "nextcloud"), // Element name
		  	"base" => "custom_blogs_list", // Element shortcode
		  	"class" => "custom_blogs_list",
			"category" => __('Content', 'nextcloud'),
		  	'params' => array(

					array(
						"type" => "textfield",
						"heading" => esc_html__("ID", "nextcloud"),
						"description" => esc_html__("", "nextcloud"),
						"param_name" => "id",
						"value" => "",
					),

					array(
						"type" => "textfield",
						"heading" => esc_html__("Title", "nextcloud"),
						"description" => esc_html__("", "nextcloud"),
						"param_name" => "title",
						"value" => "",
					),

					array(
						"type" => "textfield",
						"heading" => esc_html__("Post type", "nextcloud"),
						"description" => esc_html__("", "nextcloud"),
						"param_name" => "post_type",
						"value" => "",
					),


					array(
						"type" => "textfield",
						"heading" => esc_html__("IDs separated by comma", "nextcloud"),
						"description" => esc_html__("", "nextcloud"),
						"param_name" => "post_ids",
						"value" => "",
					),

					array(
						"type" => "textfield",
						"heading" => esc_html__("Fallback IDs separated by comma", "nextcloud"),
						"description" => esc_html__("When there are no results.", "nextcloud"),
						"param_name" => "fallback_post_ids",
						"value" => "",
					),

					array(
						"type" => "textfield",
						"heading" => esc_html__("Tag", "nextcloud"),
						"description" => esc_html__("", "nextcloud"),
						"param_name" => "tag",
						"value" => "",
					),

					array(
						"type" => "textfield",
						"heading" => esc_html__("Custom items number", "nextcloud"),
						"description" => esc_html__("", "nextcloud"),
						"param_name" => "custom_items_no",
						"value" => "",
					),

					array(
						'type' => 'dropdown',
						'heading' => 'Style',
						'param_name' => 'style',
						'value' => array(
							'Style 1' => 'style_1',
							'Style 2' => 'style_2'
						),
					),
			)
		  )
	  );
}
add_shortcode('custom_blogs_list', 'custom_blogs_list_shortcode_funct');
function custom_blogs_list_shortcode_funct($atts) {
	ob_start();
	$atts = shortcode_atts(array(
		'id' => 'featured_blog_posts',
		'title' => '',
		'post_type' => 'post',
		'post_ids' => '',
		'fallback_post_ids' => '',
		'tag' => '',
		'custom_items_no' => '',
		'style' => ''
	), $atts);

	$id = $atts['id'];
	$title = $atts['title'];
	$post_types = explode(",", $atts['post_type']);
	$post_ids = $atts['post_ids'];
	$fallback_post_ids = $atts['fallback_post_ids'];
	$tag = $atts['tag'];
	$custom_items_no = $atts['custom_items_no'];
	$style = $atts['style'];
?>
<section class="blog-section featured_blogs <?php echo $style; ?>">
	<div class=""><?php //.container ?>
		<?php
		$post_ids = explode(",", $post_ids);
		$date_format = get_option( 'date_format' ); // e.g. "F j, Y"


		$args_custom = array(
			'post_type' => $post_types,
			'post_status' => 'publish',
			'ignore_sticky_posts' => true,
			'posts_per_page' => -1
		);
		
		if( str_contains($atts['post_type'], 'webinar')) {
			$args_custom['tax_query'] = array(
					array(
						'taxonomy' => 'event_categories',
						'field'    => 'slug',
						'terms'    => 'exhibition',
						'operator' => 'NOT IN'
					)
			);
		}

		if($tag) {
			$tags_array = explode(',', $tag);
			$tags_in = array();
			foreach($tags_array as $tag_single) {
				$tag_term = get_term_by('name', $tag_single, 'post_tag');
				$tags_in[] = $tag_term->term_id; 
			}
			//echo '<p class="hidden tags">'.print_r($tags_array).'</p>';

			$args_custom['tag__in'] = $tags_in;
			$args_custom['orderby'] = 'date';
			$args_custom['order'] = 'DESC';
		}
		else if($post_ids) {
			$args_custom['post__in'] = $post_ids;
		}

		if($custom_items_no) {
			//$args_custom['posts_per_page'] = $custom_items_no;
		}
		$query_custom = new WP_Query($args_custom);
		?>
		<div id="<?php echo $id; ?>" class="owl-carousel featured_blog_posts <?php echo implode(" ", $post_types); ?> <?php echo $style; ?>">
			<?php
			if ($query_custom->have_posts()) {
				while ($query_custom->have_posts()) {
					$query_custom->the_post();
					$post_id = get_the_ID();
					$curr_post_type = get_post_type();
					?>

					<div class="item">
						<?php 
						$title = get_the_title($post_id);
						$post_excerpt = get_the_excerpt($post_id);
						$link = get_permalink($post_id);
						$featured_image = get_the_post_thumbnail($post_id, 'large', array( 'class' => 'feat_img' ));
						if(!$featured_image) {
							$featured_image = wp_get_attachment_image(350226, 'large', false, array('class' => 'feat_img'));
						}
						$date = (string)get_the_date($date_format, $post_id);
						$cats = '';
						$cat = get_the_terms( $post_id, 'category' );

						if ( $cat && ! is_wp_error( $cat ) ) {
								foreach ($cat as $c) {
									if($c->term_id != 243) { // exclude Uncategorized
										$category_link = get_category_link($c->term_id);
										$cats .= '<a href="'.$category_link.'">' . $c->name . ' </a>';
									}
								}
							} else {
								$cats = '<a href="https://nextcloud.com/events/">' . __('Events','nextcloud') . ' </a>';
							}

							echo '<div class="post-box">';
							echo '<div class="post-img" style=""><a href="'.$link.'" title="'.$title.'">'.$featured_image.'</a></div>';
							echo '<div class="post-body">';

						
						if($style != 'style_2') {
							echo '<ul class="post-meta">';
							//if ( in_array("event", $post_type) || in_array("on-demand-webinar", $post_type)  ) {
							if( $curr_post_type == 'event' || $curr_post_type == 'on-demand-webinar' ) {
								//for webinars / events 
								echo get_webinar_info('webinar_cats_lang_flag');
							} else {
								//for blog posts
								echo '<li><svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 24 24" style="">
								<path class="st0" d="M5,22c-.55,0-1.021-.196-1.413-.588-.392-.392-.588-.863-.588-1.413V6c0-.55.196-1.021.588-1.413.392-.392.863-.588,1.413-.588h1V2h2v2h8V2h2v2h1c.55,0,1.021.196,1.413.588s.588.863.588,1.413v14c0,.55-.196,1.021-.588,1.413s-.863.588-1.413.588H5ZM5,20h14v-10H5v10ZM5,8h14v-2H5v2ZM5,8v-2,2ZM12,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288c.192.192.288.429.288.713s-.096.521-.288.713c-.192.192-.429.288-.713.288ZM8,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713-.429.288-.713.288ZM16,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713-.429.288-.713.288ZM12,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288c.192.192.288.429.288.713s-.096.521-.288.713c-.192.192-.429.288-.713.288ZM8,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713c-.192.192-.429.288-.713.288ZM16,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713c-.192.192-.429.288-.713.288Z"></path>
								</svg>'.$date.'</li>';
							}
							echo '</ul>';
						}

						if($style == 'style_2') {
							echo '<div class="label announcements">'.__('Announcements','nextcloud').'</div>';
							echo '<h3><a href="'.$link.'" title="'.$title.'">' . $title . '</a></h3>';
						} else {
							echo '<h4><a href="'.$link.'" title="'.$title.'">' . $title . '</a></h4>';
						}


						if($style == 'style_2') {
							echo '<div class="excerpt">'.get_the_excerpt().'</div>';

							echo '<a class="c-btn btn-main" href="'.$link.'" title="'.$title.'">' . __('Read more','nextcloud') . '</a>';
						}


						if($curr_post_type == 'event') { ?> 
							<ul class="webinar_details">
								<li>
									<?php echo get_webinar_info('webinar_date_time'); ?>
								</li>
							</ul>
						<?php 	
						}

						echo '</div>';
						echo '</div>';		
						?>
					</div>

				<?php 
				}
			} else {
				//fallback, if the post type is webinar
				if( str_contains($atts['post_type'], 'webinar')) {
					$fallback_post_ids_arr = explode(",", $fallback_post_ids);

					foreach($fallback_post_ids_arr as $post_id) {
						$post = get_post($post_id);
						$curr_post_type = get_post_type($post);
						?>

							<div class="item">
							<?php 
							$title = get_the_title($post_id);
							$post_excerpt = get_the_excerpt($post_id);
							$link = get_permalink($post_id);
							$featured_image = get_the_post_thumbnail($post_id, 'large', array( 'class' => 'feat_img' ));
							if(!$featured_image) {
								$featured_image = wp_get_attachment_image(350226, 'large', false, array('class' => 'feat_img'));
							}
							$date = (string)get_the_date($date_format, $post_id);

							echo '<div class="post-box">';
							echo '<div class="post-img" style=""><a href="'.$link.'" title="'.$title.'">'.$featured_image.'</a></div>';
							echo '<div class="post-body">';

							
							if($style != 'style_2') {
								echo '<ul class="post-meta">';
								//if ( in_array("event", $post_type) || in_array("on-demand-webinar", $post_type)  ) {
								if( $curr_post_type == 'event' || $curr_post_type == 'on-demand-webinar' ) {
									//for webinars / events 
									echo get_webinar_info('webinar_cats_lang_flag', $post_id);
								} else {
									//for blog posts
									echo '<li><svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 24 24" style="">
									<path class="st0" d="M5,22c-.55,0-1.021-.196-1.413-.588-.392-.392-.588-.863-.588-1.413V6c0-.55.196-1.021.588-1.413.392-.392.863-.588,1.413-.588h1V2h2v2h8V2h2v2h1c.55,0,1.021.196,1.413.588s.588.863.588,1.413v14c0,.55-.196,1.021-.588,1.413s-.863.588-1.413.588H5ZM5,20h14v-10H5v10ZM5,8h14v-2H5v2ZM5,8v-2,2ZM12,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288c.192.192.288.429.288.713s-.096.521-.288.713c-.192.192-.429.288-.713.288ZM8,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713-.429.288-.713.288ZM16,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713-.429.288-.713.288ZM12,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288c.192.192.288.429.288.713s-.096.521-.288.713c-.192.192-.429.288-.713.288ZM8,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713c-.192.192-.429.288-.713.288ZM16,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713c-.192.192-.429.288-.713.288Z"></path>
									</svg>'.$date.'</li>';
								}
								echo '</ul>';
							}

							echo '<h4><a href="'.$link.'" title="'.$title.'">' . $title . '</a></h4>';

							if($curr_post_type == 'event') { ?> 
								<ul class="webinar_details">
									<li>
										<?php echo get_webinar_info('webinar_date_time', $post_id); ?>
									</li>
								</ul>
							<?php 	
							}
							echo '</div>';
							echo '</div>';		
							?>
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
					$('#<?php echo $id; ?>').owlCarousel({
						loop:false,
						autoplay: false,
						margin:22,
						dots: false,
						nav:true,
						responsive:{
							0:{
								items:1
							},
							300:{
								items:<?php if($style != 'style_2') { echo "2";} else { echo "1"; } ?>
							},
							600:{
								items:<?php if($style != 'style_2') { echo "3";} else { echo "1"; } ?>
							},
							1000:{
								items: <?php 
								if($style == 'style_2'){
									echo "1";
								}
								else if($custom_items_no) {
									echo $custom_items_no;
								}else {
									echo "5";
								}?>
							}
						}
					});
			});
		</script>
	</div>
</section>	
<?php 
	$result = ob_get_clean();
	return $result;
}




//featured blogs carousel shortcode
add_action('vc_before_init', 'nc_featured_blogs_carousel_funct');
function nc_featured_blogs_carousel_funct() {
	vc_map(
		  array(
		  	"name" => __("Featured Blogs carousel", "nextcloud"), // Element name
		  	"base" => "featured_blogs", // Element shortcode
		  	"class" => "featured_blogs",
			"category" => __('Content', 'nextcloud'),
		  	'params' => array(
					array(
						"type" => "textfield",
						"heading" => esc_html__("Title", "nextcloud"),
						"description" => esc_html__("", "nextcloud"),
						"param_name" => "title",
						"value" => "",
					)
			)
		  )
	  );
}
add_shortcode('featured_blogs', 'featured_blogs_shortcode_funct');
function featured_blogs_shortcode_funct($atts) {
	ob_start();
	$atts = shortcode_atts(array(
		'title' => '',
	), $atts);
	$title = $atts['title'];

	if(!isset($_GET['webinars'])) {
?>
<section class="blog-section featured_blogs">
	<div class=""><?php //.container ?>
		<?php
		echo '<div class="row justify-content-between align-items-center">';
		if (!empty($title)) {
			echo '<div class="col-lg-6">';
			echo '<div class="section-title">';
			echo '<h3>';
			echo $title;
			echo '</h3>';
			echo '</div>';
			echo '</div>';
		}
		echo "</div>";

		global $wpdb;
		
		//$sticky_ids = unserialize($wpdb->get_var( "SELECT `option_value`, `option_id` FROM `$wpdb->options` WHERE `option_name` = 'sticky_posts'" )); // taking also posts not sticky

		$date_format = get_option( 'date_format' ); // e.g. "F j, Y"


		// 1) Get sticky IDs from the DEFAULT language (EN)
		$sticky_ids_en = [];

			if (function_exists('wpml_get_default_language')) {
				$default_lang = wpml_get_default_language(); // e.g. 'en'
				$current_lang = apply_filters('wpml_current_language', null);

				// Temporarily switch to default language to read option in that context
				do_action('wpml_switch_language', $default_lang);
				$sticky_ids_en = get_option('sticky_posts', []);
				do_action('wpml_switch_language', $current_lang);
			} else {
				// Fallback if WPML isn't available
				$sticky_ids_en = get_option('sticky_posts', []);
			}

			// 2) For each EN sticky post, use translation in current language if it exists; else keep EN
		$current_lang = function_exists('apply_filters')
				? apply_filters('wpml_current_language', null)
				: null;

		$sticky_ids_show = array_values(array_unique(array_map(function ($en_id) use ($current_lang) {
				$translated_id = apply_filters('wpml_object_id', $en_id, 'post', false, $current_lang);
				return $translated_id ? (int) $translated_id : (int) $en_id;
		}, $sticky_ids_en)));



		/*
		echo '<div class="original_sticky_ids" style="display: none;">';
			if (!empty($sticky_ids_show)) {

				// 3) Query: newest -> oldest
				$query = new WP_Query([
					'post_type'           => 'post',
					'post__in'            => $sticky_ids_show,
					'orderby'             => 'date',
					'order'               => 'DESC',
					'ignore_sticky_posts' => 1,
					'posts_per_page'      => -1,

					// Important with WPML so translated IDs resolve properly
					'suppress_filters'    => false,
				]);

				if ($query->have_posts()) {
					while ($query->have_posts()) {
						$query->the_post();
						echo "<a href='".get_the_permalink()."'>".get_the_ID().": ".get_the_title() . ' - ' . get_the_date() . '</a><br>';
					}
					wp_reset_postdata();
				}
			}
		echo '</div>';
		*/
		


		/*
		$array_query_sticky = array();
		$args = array(
			'post_type' => 'post',
			'post_status' => 'publish',
			//'post__in' => $sticky_ids,
			'post__in' => $sticky_ids_show
		);
		$main_query = new WP_Query($args);
		if ($main_query->have_posts()) {
				while ($main_query->have_posts()) {
					$main_query->the_post();

					//if(is_sticky(get_the_ID())) {
						$array_query_sticky[] = get_the_ID();
					//}
					
				}
		}
		*/


		$sticky_posts_with_date = array();
		if (!empty($sticky_ids_show)) {
			foreach($sticky_ids_show as $sticky_post_id) {
				if(get_post_status($sticky_post_id) == 'publish') {
					if( !in_array($sticky_post_id, $sticky_posts_with_date) ) {
						$sticky_posts_with_date[] = array("id"=>$sticky_post_id, "date" => get_the_date('Y-m-d G:i:s', $sticky_post_id));
					}
				}
			}
		}

		//get all sticky events
		$args_events = array(
			'post_type' => array('event'),
			'post_status' => 'publish',
			'meta_query' => array(
				array(
					'key' => 'sticky_event',
					'value' => true,
					'compare' => 'LIKE'
				)
			)
		);
		$events__query = new WP_Query($args_events);
		if ($events__query->have_posts()) {
				while ($events__query->have_posts()) {
					$events__query->the_post();
					$sticky_posts_with_date[] = array(
						"id" => get_the_ID(), 
						"date" => get_the_date('Y-m-d G:i:s', get_the_ID()));
				}
		}


		//get all sticky podcasts episodes
		$args_podcast = array(
			'post_type' => array('podcast'),
			'post_status' => 'publish',
			'meta_query' => array(
				array(
					'key' => 'sticky_episode',
					'value' => true,
					'compare' => 'LIKE'
				)
			)
		);
		$podcast__query = new WP_Query($args_podcast);
		if ($podcast__query->have_posts()) {
				while ($podcast__query->have_posts()) {
					$podcast__query->the_post();

					$sticky_posts_with_date[] = array("id" => get_the_ID(), "date" => get_the_date('Y-m-d G:i:s', get_the_ID()));

				}
		}


		//sort sticky posts array
		define('CUSTOM_DATE_FORMAT', 'Y-m-d G:i:s');
		usort(
			$sticky_posts_with_date,
			function($a, $b) {
				$date1 = DateTime::createFromFormat(CUSTOM_DATE_FORMAT, $a['date']);
				$date2 = DateTime::createFromFormat(CUSTOM_DATE_FORMAT, $b['date']);
				return $date1 < $date2;
			}
		);

		//limit posts to 5
		$sticky_posts_with_date_first_5 = array_slice($sticky_posts_with_date, 0, 5);

		if($sticky_posts_with_date) {
			?>
			<div id="featured_blog_posts" class="owl-carousel featured_blog_posts" data-ids="">
			<?php
			foreach($sticky_posts_with_date_first_5 as $sticky_posts_with_date_single) {
				//if(is_sticky($sticky_posts_with_date_single['id'])) {
				?>
				<div class="item" data-sticky="<?php echo is_sticky($sticky_posts_with_date_single['id']); ?>">
						<?php 
						$post_id =  $sticky_posts_with_date_single['id'];
						$title = get_the_title($post_id);
						$post_excerpt = get_the_excerpt($post_id);
						$link = get_permalink($post_id);
						$featured_image = get_the_post_thumbnail($post_id, 'large', array( 'class' => 'feat_img' ));
						$date = (string)get_the_date($date_format, $post_id);

						if ( 'event' == get_post_type() ) {
							$cat = wp_get_object_terms( $post_id, 'event_categories', array() );
						} else {
							$cat = get_the_category($post_id);
						}

						$cats = '';
						if($cat) {
							foreach ($cat as $c) {
								if($c->term_id != 243) { // exclude Uncategorized
									$category_link = get_category_link($c->term_id);
									$cats .= '<a href="'.$category_link.'">' . $c->name . ' </a>';
								}
							}
						} else {
							$cats = '<a href="https://nextcloud.com/events/">' . __('Events','nextcloud') . ' </a>';
						}

						echo '<div class="post-box">';
						echo '<div class="post-img" style=""><a href="'.$link.'" title="'.$title.'">'.$featured_image.'</a></div>';
						echo '<div class="post-body">';
						echo '<ul class="post-meta"><li class="date">
						<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 24 24" style="">
						<path class="st0" d="M5,22c-.55,0-1.021-.196-1.413-.588-.392-.392-.588-.863-.588-1.413V6c0-.55.196-1.021.588-1.413.392-.392.863-.588,1.413-.588h1V2h2v2h8V2h2v2h1c.55,0,1.021.196,1.413.588s.588.863.588,1.413v14c0,.55-.196,1.021-.588,1.413s-.863.588-1.413.588H5ZM5,20h14v-10H5v10ZM5,8h14v-2H5v2ZM5,8v-2,2ZM12,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288c.192.192.288.429.288.713s-.096.521-.288.713c-.192.192-.429.288-.713.288ZM8,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713-.429.288-.713.288ZM16,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713-.429.288-.713.288ZM12,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288c.192.192.288.429.288.713s-.096.521-.288.713c-.192.192-.429.288-.713.288ZM8,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713c-.192.192-.429.288-.713.288ZM16,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713c-.192.192-.429.288-.713.288Z"></path>
						</svg>'.$date.'</li>';
						echo '<h4><a href="'.$link.'" title="'.$title.'">' . $title . '</a></h4>';
						echo '</div>';
						echo '</div>';		
						?>
					</div>
				<?php
					//} //endif
				} //end foreach
				?>
			</div>
			<?php
		} // endif
		?>
		<script>
			jQuery(document).ready(function ($) {
					$('#featured_blog_posts').owlCarousel({
						loop:false,
						autoplay: false,
						margin:22,
						dots: false,
						nav:true,
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
							1000:{
								items:5
							}
						}
					});
			});
		</script>
	</div>
</section>	
<?php 
	}
	$result = ob_get_clean();
	return $result;
}


//blog list shortcode
add_action('vc_before_init', 'nc_blog_list_funct');
function nc_blog_list_funct() {
	vc_map(
		  array(
		  	"name" => __("Blog list", "nextcloud"), // Element name
		  	"base" => "blog_list", // Element shortcode
		  	"class" => "blog_list",
			"category" => __('Content', 'nextcloud'),
		  	'params' => array(
					array(
						"type" => "textfield",
						"heading" => esc_html__("Title", "nextcloud"),
						"description" => esc_html__("", "nextcloud"),
						"param_name" => "title",
						"value" => "",
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Type", "nextcloud"),
						"description" => esc_html__("", "nextcloud"),
						"param_name" => "type",
						"value" => "",
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("ID", "nextcloud"),
						"description" => esc_html__("", "nextcloud"),
						"param_name" => "id",
						"value" => "",
					),
					array(
						"type" => "checkbox",
						"heading" => esc_html__("RSS feed", "nextcloud"),
						"description" => esc_html__("", "nextcloud"),
						"param_name" => "rss_feed",
						"value" => "1",
					)
			)
		  )
	  );
}
add_shortcode('blog_list', 'blog_list_shortcode_funct');
function blog_list_shortcode_funct($atts) {
	ob_start();
	$type = '';

	$atts = shortcode_atts(array(
		'title' => '',
		'type' => 'post',
		'id' => '',
		'rss_feed' => ''
	), $atts);
	$title = $atts['title'];
	$type = $atts['type'];
	$id = $atts['id'];
	$rss_feed = $atts['rss_feed'];

	if($type=='event' && isset($_GET['webinars']) ){
	}
	else {
?>
<section class="blog-section" id="<?php echo $id; ?>">
	<div class=""><?php //.container ?>
		<?php
		echo '<div class="row justify-content-between align-items-center">';
		if (!empty($title)) {
			echo '<div class="col-lg-6">';
			echo '<div class="section-title">';
			echo '<h3>';

			if(isset($_GET['webinars'])) {

				if( strip_tags($_GET['webinars']) == 'upcoming') {
					echo __('Upcoming webinars','nextcloud');
				} else {
					echo __('Webinar recordings','nextcloud');
				}
				
			} else {
				echo $title;

				if($rss_feed) {
					echo '<a class="rss_feed" href="'.get_site_url().'/feed" title="'.__('RSS Feed','nextcloud').'" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="#0082c9"><path d="M200-120q-33 0-56.5-23.5T120-200q0-33 23.5-56.5T200-280q33 0 56.5 23.5T280-200q0 33-23.5 56.5T200-120Zm480 0q0-117-44-218.5T516-516q-76-76-177.5-120T120-680v-120q142 0 265 53t216 146q93 93 146 216t53 265H680Zm-240 0q0-67-25-124.5T346-346q-44-44-101.5-69T120-440v-120q92 0 171.5 34.5T431-431q60 60 94.5 139.5T560-120H440Z"/></svg></a>';	
				}
			}
			
			echo '</h3>';
			echo '</div>';
			echo '</div>';
		}
		if (function_exists('wpes_search_form')) {
			
			if($type == 'event') {
				$search_id = 125618;
			} else {
				$search_id = 1612;
			}

			echo '<div class="col-lg-4">';
			echo '<div class="form-holder">';
			wpes_search_form(array(
				'wpessid' => $search_id
			));
			echo '</div>';
			echo '</div>';
		}
		echo '</div>';
		?>
		<div class="row row-list-blog">
			<?php

			$default_posts_per_page = get_option( 'posts_per_page' );
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;


			//get all sticky events and exclude them in the main query 
			$sticky_events = array();
			$args_events = array(
				'post_type' => array('event'),
				'post_status' => 'publish',
				'meta_query' => array(
					array(
						'key' => 'sticky_event',
						'value' => true,
						'compare' => 'LIKE'
					)
				)
			);
			$events__query = new WP_Query($args_events);
			if ($events__query->have_posts()) {
					while ($events__query->have_posts()) {
						$events__query->the_post();
						$sticky_events[] = get_the_ID();
					}
			}
			//print_r($sticky_events);




			$limit = $default_posts_per_page;

			// The main query
			$args = array(
				'posts_per_page' => $default_posts_per_page,
				'post_status' => 'publish',
				'orderby' => 'date',
				'paged=' => $paged,
				'ignore_sticky_posts' => 1,
				//'post__not_in' => $sticky_events, // ignore sticky events (custom field)
				'tag__not_in' => array(269), // exclude unlisted tag
				'category__not_in'=> array(225, 226) //exclude Private category
			);
			//date_default_timezone_set('Europe/Berlin');
			$current_date_time = date('Y-m-d H:i:s', time());


			//if webinars parameter is present
			if( isset($_GET['webinars'])) {

				$args['post_type'] = array('event');
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'event_categories',
						'field'    => 'slug',
						'terms'    => 'webinars',
					)
				);

				if( strip_tags($_GET['webinars']) == 'upcoming'){
					
					$args['meta_query'] = array(
						array(
							'key' => 'event_start_date_and_time',
							'value'   => $current_date_time,
							'compare' => '>=',
							'type'	=> 'DATETIME'
						),
					);
					//$args['order'] = 'ASC';

					$args['orderby'] = 'meta_value';
					$args['order'] = 'ASC';

				} else if ( strip_tags($_GET['webinars']) == 'past') {

					$args['meta_query'] = array(
						'relation' => 'AND',
						
						array(
							'key' => 'event_start_date_and_time',
							'value'   => $current_date_time,
							'compare' => '<',
							'type'	=> 'DATETIME'
						),
						
			
						array(
							'key'     => 'download_available',
							'value'	  => '',
							'compare' => '!=',
						),
						
						
					);
					$args['orderby'] = 'meta_value';
					$args['order'] = 'DESC';

				}


			}
			else {
				//$args['post_type'] = array( $type );
				$args['post_type'] = array( 'post', 'podcast');
				$args['order'] = 'DESC';
			}

			
			$the_query = new WP_Query($args);
			$count = $the_query->found_posts;
			//print_r($the_query);


			// The Loop
			if ($the_query->have_posts()) {
				while ($the_query->have_posts()) {
					$the_query->the_post();

					$webinar = false;
					if ( 'event' == get_post_type() ) {
						$cat = wp_get_object_terms( get_the_ID(), 'event_categories', array() );
						if($cat) {
							foreach ($cat as $c) {
								if($c->term_id == 250 ) { // event_categories Webinars
									$webinar = true;
									break;
								}
							}
						}
					}


					if($webinar) {
						get_template_part('inc/webinar_loop_single');
					} else {
						get_template_part('inc/blog_loop_single');
					}

				}
			} else {
				// no posts found
			?>

			<div class="col-12">
				<div class="section-button">
					<h3><?php echo __('No posts found.','nextcloud'); ?></h3>
				</div>
			</div>

			<?php
			}
			/* Restore original Post Data */
			wp_reset_postdata();

			?>
		</div>

		<?php if($count > $default_posts_per_page) { ?>
		<div class="row loadNews_row">
			<div class="col-12">
				<div class="section-button">
					<button class="c-btn btn-main loadNews" id="loadNews_<?php echo $type; ?>" data-count="<?php echo $count; ?>" data-limit="<?php echo $limit; ?>" data-post-type="<?php 
					if ( isset($_GET['webinars']) && strip_tags($_GET['webinars']) == 'past') { echo 'past_webinars'; }
					?>">
					<?php 
					echo __('Load more','nextcloud');
					?></button>
				</div>
			</div>
		</div>
		<?php } ?>

	</div>
</section>
<?php
	}
	$result = ob_get_clean();
	return $result;
}




add_shortcode('job_position', 'job_position_funct');
function job_position_funct($atts) {
	ob_start();

	if(isset($_GET['position'])) {
?>
<script>
jQuery( document ).ready( function( $ ) {

		var selected_position = '<?php echo strip_tags($_GET['position']); ?>';

		if(selected_position) {
			var selected_position_el = '#<?php echo strip_tags($_GET['position']); ?>';
			jQuery('#openpositions').find(selected_position_el).addClass('vc_toggle_active');

			//smooth scroll
			var id = selected_position_el;
			var $id = jQuery(id);
			if ($id.length === 0) {
				return;
			}
			var pos = $id.offset().top-100;
			// animated top scrolling
			$('body, html').animate({scrollTop: pos}, 800);


		}
	
});
</script>
<?php
	}
$result = ob_get_clean();
return $result;
}





//partners search block
add_action('vc_before_init', 'nc_partners_search_funct');
function nc_partners_search_funct() {
	vc_map(
		  array(
		  	"name" => __("Partners search", "nextcloud"), // Element name
		  	"base" => "partners_search", // Element shortcode
		  	"class" => "partners_search",
			"category" => __('Content', 'nextcloud'),
		  	'params' => array(

					//title
					array(
					"type" => "textfield",
					"heading" => esc_html__("Title", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "title",
					"value" => "",
					),

					//button
					array(
						"type" => "vc_link",
						"holder" => "div",
						"class" => "link",
						"admin_label" => true,
						"heading" => __("Link", "nextcloud"),
						"param_name" => "link",
						"value" => __("", "nextcloud"),
					),

					//Subtext
					array(
						"type" => "textarea",
						"heading" => esc_html__("Subtext", "nextcloud"),
						"description" => esc_html__("", "nextcloud"),
						"param_name" => "subtext",
						"value" => "",
					),


					//Certificate text
					array(
						"type" => "textfield",
						"heading" => esc_html__("Certificate text", "nextcloud"),
						"description" => esc_html__("", "nextcloud"),
						"param_name" => "cert_text",
						"value" => "",
					),

					//repeater logos
					array(
						'type' => 'param_group',
						'param_name' => 'box_repeater_items',
						'params' => array(
						  
						  array(
							  "type" => "attach_image",
							  "holder" => "img",
							  "class" => "",
							  "heading" => __("Certificate Logo", "nextcloud"),
							  "param_name" => "cert_logo",
							  "value" => __("", "nextcloud"),
						  )
						)
					),


					array(
						"type" => "checkbox",
						"heading" => esc_html__("Show resellers?", "nextcloud"),
						"description" => esc_html__("", "nextcloud"),
						"param_name" => "show_resellers",
						"value" => "",
					),

					array(
					'type' => 'dropdown',
					'heading' => 'Ordering',
					'param_name' => 'ordering',
					'value' => array(
						'By menu order' => 'menu_order',
						'By level' => 'level'
					)
				),



			)
		  )
	  );
}

add_shortcode('partners_search', 'partners_search_shortcode_funct');
function partners_search_shortcode_funct($atts) {
	ob_start();
	$atts = shortcode_atts(array(
		'title' => '',
		'subtext' => '',
		'link' => '',
		'cert_text' => '',
		'box_repeater_items' => '',
		'show_resellers' => '',
		'ordering' => ''
	), $atts);

	global $wpdb;

	$title = $atts['title'];
	$show_resellers = $atts['show_resellers'];
	$ordering = $atts['ordering'];

	if(function_exists('vc_param_group_parse_atts')){
		$items = vc_param_group_parse_atts($atts['box_repeater_items']);
	}

	$link = vc_build_link($atts['link']);
	$subtext = $atts['subtext'];
	$cert = $atts['cert_text'];
	//$logos = $atts['box_repeater_items'];
	//$solution = get_field('solution_providers');
	//$technology = get_field('technology_partners');


	// Service providers query 
	$args_service_providers = array(
		'post_type' => 'partner_posts',
		'post_status' => 'publish',
		'posts_per_page' => '-1',
		//'orderby' => 'date',
		//'orderby' => 'menu_order',
		'order' => 'DESC',
		'meta_query' => array(
			array(
				'key'   => 'service_text',
				'value' => 'Technology Partner',
				'compare'   => '!='
			)
		)
	);
	if($ordering != "level") {
		$args_service_providers['orderby'] = 'menu_order';
		$service_providers = get_posts( $args_service_providers );
	} else {

		$service_providers = $wpdb->get_results( "
			SELECT p.*
			FROM {$wpdb->posts} p
			INNER JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = 'partner_level'
			INNER JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id AND pm2.meta_key = 'service_type'
			WHERE p.post_type = 'partner_posts'
				AND p.post_status = 'publish'
				AND pm2.meta_value LIKE '%Service provider%'
			ORDER BY
				FIELD(pm1.meta_value, 'Platinum', 'Gold', 'Silver', 'Bronze'),
				p.menu_order ASC
		" );


	}
	

	

	//Technology providers query 
	$args_tech = array(
		'post_type' => 'partner_posts',
		'post_status' => 'publish',
		'posts_per_page' => '-1',
		'orderby' => 'date',
		'order' => 'DESC',
		'meta_query' => array(
			array(
				'key'   => 'service_text',
				'value' => 'Technology Partner',
				'compare'   => '='
			)
		)
	  );
	$technology_providers = get_posts( $args_tech );


	//Resellers query 
	if($show_resellers) {
		
		$resellers = $wpdb->get_results( "
			SELECT p.*
			FROM {$wpdb->posts} p
			INNER JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = 'partner_level'
			INNER JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id AND pm2.meta_key = 'service_type'
			WHERE p.post_type = 'partner_posts'
				AND p.post_status = 'publish'
				AND ( pm2.meta_value LIKE '%\"Reseller\"%' OR pm2.meta_value LIKE '%\"Distributor\"%' )
			ORDER BY
				FIELD(pm1.meta_value, 'Platinum', 'Gold', 'Silver', 'Bronze'),
				p.menu_order ASC
		" );

	}
	

?>
<section class="text-center">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<div class="section-title">
					<?php
					if (!empty($title)) {
						echo '<h4>' . $title . '</h4>';
					}
					if ($link) {
						$link_url = $link['url'];
						$link_title = $link['title'];
						$link_target = $link['target'] ? $link['target'] : '_self';
						echo '<a class="c-btn btn-blue" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
					}
					?>
				</div>
			</div>
		</div>


		<div class="row mt-5 justify-content-center">
			<div class="col-lg-8">
				<div class="desc-text">
					<?php
					if (!empty($subtext)) {
						echo wpautop($subtext);
					}
					?>
				</div>
				<div class="text-block">
					<?php
					if (!empty($cert)) {
						echo '<p>' . $cert . '</p>';
					}
					?>
				</div>
				<?php
				if ($items) {
					echo '<ul class="logos">';
					foreach ($items as $item) {
						if(isset($item['cert_logo'])) {
							echo '<li>';
							echo wp_get_attachment_image($item['cert_logo'], 'large');
							echo '</li>';
						}
						
					}
					echo '</ul>';
				}
				?>
			</div>
		</div>



	</div>
</section>
<section class="partners-section" id="">
	<div class="container">

		<div class="row">
			<div class="col-lg-12">
				<div class="tabs-navigation">
					<ul class="custom-partner-tabs">
						<li>
							<button class="tab-link active" id="solution-tab"><?php echo __("Service providers","nextcloud"); ?></button>
						</li>
						<?php if($show_resellers) { ?>
						<li>
							<button class="tab-link" id="resellers-tab"><?php echo __("Resellers","nextcloud"); ?></button>
						</li>
						<?php } ?>
						<li>
							<button class="tab-link" id="technology-tab"><?php echo __("Technology providers","nextcloud"); ?></button>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="filters-holder">


					<div class="input-outer" id="service_select" style="display:none">
						<div class="input-holder selection">
							<span class="label"><?php echo __("Services","nextcloud"); ?></span>
							<div class="inner">
								<input id="services" type="text" value="All services" readonly="readonly" data-value="" placeholder="<?php echo __("All services","nextcloud"); ?>" />
							</div>
						</div>
						<ul class="select-list check-list">
							<li>
								<input type="checkbox" name="servi" value="all-dev" id="shk00" /><label for="shk00"><?php echo __("All services","nextcloud"); ?></label>
							</li>
							<li>
								<input type="checkbox" name="servi" value="host-own" id="shk01" /><label for="shk01">
								<?php echo __("Dedicated data center","nextcloud"); ?>	
								</label>
							</li>
							<li>
								<input type="checkbox" name="servi" value="host-rend" id="shk02" /><label for="shk02">
								<?php echo __("Shared data center","nextcloud"); ?>
								</label>
							</li>

							<li>
								<input type="checkbox" name="servi" value="host-home" id="shk02_1" /><label for="shk02_1">
								<?php echo __("Hosting for home users","nextcloud"); ?>
								</label>
							</li>

							<li>
								<input type="checkbox" name="servi" value="archi" id="shk03" /><label for="shk03">
								<?php echo __("Architecture consulting","nextcloud"); ?>
								</label>
							</li>
							<li>
								<input type="checkbox" name="servi" value="hardware" id="shk04" /><label for="shk04">
								<?php echo __("Hardware development","nextcloud"); ?>
								</label>
							</li>
							<li>
								<input type="checkbox" name="servi" value="app" id="shk05" /><label for="shk05">
								<?php echo __("App development","nextcloud"); ?>
								</label>
							</li>
							<li>
								<input type="checkbox" name="servi" value="custom" id="shk06" /><label for="shk06">
								<?php echo __("Custom integrations","nextcloud"); ?>
								</label>
							</li>
							<li>
								<input type="checkbox" name="servi" value="train" id="shk07" /><label for="shk07">
								<?php echo __("Trainings","nextcloud"); ?>
								</label>
							</li>
							<li>
								<input type="checkbox" name="servi" value="on-site" id="shk08" /><label for="shk08">
								<?php echo __("On-site management","nextcloud"); ?>
								</label>
							</li>
							<li>
								<input type="checkbox" name="servi" value="resell" id="shk09" /><label for="shk09">
								<?php echo __("Reselling","nextcloud"); ?>
								</label>
							</li>
						</ul>
					</div>


					<div class="input-outer" style="display:none">
						<div class="input-holder selection">
							<span class="label"><?php echo __("Partner level","nextcloud"); ?></span>
							<div class="inner">
								<input id="certificates" type="text" value="All levels" readonly="readonly" data-value="all-cert" />
							</div>
						</div>
						<ul class="select-list cert-list">
							<li data-certificate="all-cert"><?php echo __("All levels","nextcloud"); ?></li>
							<li data-certificate="platinum"><?php echo __('Platinum','nextcloud'); ?></li>
							<li data-certificate="gold">Gold</li>
							<li data-certificate="silver"><?php echo __('Silver','nextcloud'); ?></li>
							<li data-certificate="bronze">Bronze</li>
						</ul>
					</div>


					<div class="input-outer" id="country_select" style="">
						<div class="input-holder selection">
							<span class="label"><?php echo __("Country","nextcloud"); ?></span>
							<div class="inner">
								<input id="country" type="text" value="All" readonly="readonly" data-value="all-comp" placeholder="<?php echo __("All","nextcloud"); ?>" />
							</div>
						</div>
						<ul class="select-list check-list region_select_list">
							<li>
								<input type="checkbox" name="country" value="all-comp" id="chk01" /><label for="chk01"><?php echo __("All","nextcloud"); ?></label>
							</li>

							<?php
							$continents = get_countries('all');

							foreach($continents as $key => $array_value) {
								?>
								<li class="continent parent <?php echo $key; ?>">
								<input type="checkbox" name="country" value="<?php echo $key; ?>" id="<?php echo $key; ?>" /><label for="<?php echo $key; ?>"><?php echo $key; ?></label>


								<?php if($array_value) {
									echo '<ul class="children_countries" style="display: none;">';
									foreach($array_value as $value) {
									?>
										<li class="country <?php echo $value; ?>">
											<input type="checkbox" name="country" value="<?php echo $value; ?>" id="<?php echo $value; ?>" /><label for="<?php echo $value; ?>"><?php echo $value; ?></label>
										</li>
								<?php 
									}
									echo "</ul>";
								}
								?>
								</li>
								<?php
							}
							?>
						</ul>
					</div>


					<div class="input-outer">
						<div class="search-holder">
							<input type="text" placeholder="<?php echo __("Search","nextcloud"); ?>" id="filtersearch" />
						</div>
					</div>
					
				</div>
			</div>
		</div>
</div>



<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="custom-partner-tab-content">
					<?php
					if ($service_providers) {
						?>
						<div class="custom-tab-panel active" data-panel="solution-tab">
							<div class="partners-holder">
								<?php
								foreach ($service_providers as $sol) {
									get_template_part('inc/partner_box', null, array('sol_id' => $sol->ID));
								}
								?>
							</div>
						</div>
					<?php
					} else {
						?>
						<h3 class="no_partner_found text-center" style="display: none;"><?php echo __('No service providers found.','nextcloud'); ?></h3>
					<?php
					}

					if($show_resellers) { ?>
						<div class="custom-tab-panel" data-panel="resellers-tab">
							<div class="partners-holder">
								<?php
								foreach ($resellers as $sol) {
									get_template_part('inc/partner_box', null, array('sol_id' => $sol->ID));
									//var_dump($resellers);
								}
								?>
							</div>
						</div>
					<?php 
					}

					if ($technology_providers) {
						?>
						<div class="custom-tab-panel" data-panel="technology-tab">
							<div class="partners-holder">
								<?php
								foreach ($technology_providers as $sol) {
									get_template_part('inc/partner_box', null, array('sol_id' => $sol->ID));
								}
								?>
							</div>
						</div>
					<?php
					}
					else {
						?>
						<h3 class="no_partner_found text-center" style="display: none;"><?php echo __('No partner found.','nextcloud'); ?></h3>
					<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>


<script>
jQuery(document).ready(function () {

	function resetFilter() {
		jQuery('#services').val('<?php echo __('All services','nextcloud'); ?>');
		jQuery('#services').data('value', 'all-dev');
		jQuery('#certificates').val('<?php echo __('All levels','nextcloud'); ?>');
		jQuery('#certificates').data('value', 'all-cert');
		jQuery('#country').val('<?php echo __('All','nextcloud'); ?>');
		jQuery('#country').data('value', 'all-comp');
		jQuery('input[type="checkbox"]').each(function () {
			jQuery(this).prop('checked', false);
		});
	}


	var filter1 = 'all-dev';
    var filter2 = 'all-cert';
    var filter3 = 'all-comp';

    jQuery('#services').val('<?php echo __('All services','nextcloud'); ?>');
    jQuery('#certificates').val('<?php echo __('All levels','nextcloud'); ?>');
    jQuery('#country').val('<?php echo __('All','nextcloud'); ?>');

    jQuery('input[type="checkbox"]').each(function () {
        jQuery(this).prop('checked', false);
    });

    jQuery('#filtersearch').val('');

    jQuery('.tab-link').click(function () {
        var Panel = jQuery(this).attr('id');
        jQuery('.tab-link').removeClass('active');
        jQuery(this).addClass('active');
        jQuery('.custom-tab-panel').removeClass('active');
        jQuery('[data-panel="' + Panel + '"]').addClass('active');
        resetFilter();
        jQuery('#filtersearch').val('');
        jQuery('.partner-col').show();
        if (jQuery('#technology-tab').hasClass('active')) {
            jQuery('.filters-holder').slideUp();
        } else {
            jQuery('.filters-holder').slideDown();
        }
    });

    jQuery('.selection').click(function () {
        jQuery(this).toggleClass('active');
        jQuery('.selection').not(this).removeClass('active');
        var select = jQuery(this).next('.select-list');
        jQuery('.select-list').not(select).slideUp();
        select.slideToggle();
        select.focus();
    });

    jQuery('input[name="servi"]').change(function () {
        var textArray = '';
        var valArray = [];
        var count = jQuery('input[name="servi"]:checked').length;
        var i = 1;


        var selected_cb = jQuery(this).val();
        if(selected_cb == 'all-dev') {
            textArray = '<?php echo __('All services','nextcloud'); ?>';
            valArray.push(selected_cb);

            jQuery('input[name="servi"]').prop('checked', false);
            jQuery('input[name="servi"][id="shk00"]').prop('checked', true);
            

        } else {

            //deselect All option when everything else is checked
            jQuery('input[name="servi"][id="shk00"]').prop('checked', false);

            jQuery('input[name="servi"]:checked').each(function () {
                var labeltext = jQuery(this).next('label').text();
                var labeltext2 = jQuery.trim( labeltext );
                var valuefilter = jQuery(this).val();
                
                valArray.push(valuefilter);
                if (count == i) {
                    textArray += labeltext2;
                } else {
                    textArray += labeltext2 + ', ';
                }
                i++;

                //console.log("textArray: "+textArray);
            });

        }

        jQuery('#services').val(textArray);
        jQuery('#services').data('value', valArray);

        iniFilter();


        //close select menu when changing service input checkboxes
        jQuery(this).closest('.selection').toggleClass('active');
        jQuery(this).closest('.select-list').slideUp();
        //jQuery(this).closest('.select-list').focus();

    });


    //close menu selection when clicked outside
    jQuery('ul.select-list').blur(function() {
        //console.log('select list blurred');
        //if(jQuery(this).css('display') == 'block') {
            jQuery(this).slideUp();
        //}
    });


    //close country select div when clicked outside of it
    var country_select = jQuery('#country_select');
    var region_select_list = country_select.find('.region_select_list');
    jQuery(document).click(function (event) {
        if (!country_select.is(event.target) && country_select.has(event.target).length === 0) {      
            //console.log('clicking outside the div');
            jQuery('.selection.active').removeClass('active');
            region_select_list.slideUp();
        }
    });


    //service_select
    var service_select = jQuery('#service_select');
    var service_select_list = service_select.find('.select-list');
    jQuery(document).click(function (event) {
        if (!service_select.is(event.target) && service_select.has(event.target).length === 0) {      
            //console.log('clicking outside the div');
            jQuery('.selection.active').removeClass('active');
            service_select_list.slideUp();
        }
    });
    


    jQuery('.cert-list li').click(function () {
        var filter2 = jQuery(this).data('certificate');
        jQuery(this).parent('.select-list').slideUp();
        jQuery(this).closest('.input-outer').find('.selection').removeClass('active');
        var value1 = jQuery(this).text();
        jQuery('#certificates').val(value1);
        jQuery('#certificates').data('value', filter2);
        iniFilter();
    });


	//update Country search box when checkboxes are selected / unselected
	var countries_array = [];
	var textArray = '';
    jQuery('input[name="country"]').change(function () {
		var parent = false; 
        //var values_selected = '';
        var count = jQuery('input[name="country"]:checked').length;
		//console.log("Count: "+count);
        var selected_cb = jQuery(this).val();
		//console.log("Changed: "+selected_cb);

		if(jQuery(this).parent('li').hasClass('parent')) {
			parent = true;
		}
		if(parent == true) {
			console.log("Parent selected: "+selected_cb);
		}
		

        if(selected_cb == 'all-comp') {
			textArray = ''; //reset
			countries_array = []; // reset

            textArray = '<?php echo __('All','nextcloud'); ?>';
            jQuery('input[name="country"]').prop('checked', false);
            jQuery('input[name="country"][id="chk01"]').prop('checked', true);

        } else {
            //deselect All option when everything else is checked
            jQuery('input[name="country"][id="chk01"]').prop('checked', false);
			textArray = textArray.replace('<?php echo __('All','nextcloud'); ?>', '');

			var valuefilter = jQuery(this).val();
            //console.log("valuefilter: "+valuefilter);

			if (jQuery(this).is(':checked')) {
				//increase
				console.log(valuefilter + " selected.");
				
				if(parent == true) {
					countries_array = [];   // now empty
				}

				countries_array.push(valuefilter);
				textArray = countries_array.join(', ');

			} else {
				//decrease
				console.log(valuefilter + " removed.");

				countries_array = countries_array.filter(item => item !== valuefilter);
				textArray = countries_array.join(', ');
			}

			//console.log(countries_array);
        }


        jQuery('#country').val(textArray);
		//jQuery('#country').attr('value', textArray); // not really needed
		jQuery('#country').attr('data-value', textArray);
		//console.log(jQuery('#country').data('value'));

        iniFilter();
    });



    jQuery.extend(jQuery.expr[":"], {
        "containsIN": function (elem, i, match, array) {
            return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
        }
    });

    jQuery('#filtersearch').keyup(function () {
        iniFilter();
    });

});
</script>
<?php
$result = ob_get_clean();
return $result;
}




//Countdown element
/*
add_action('vc_before_init', 'wpb_nc_countdown_funct');
function wpb_nc_countdown_funct() {
	vc_map(
		  array(
		  	"name" => __("Nextcloud Countdown", "nextcloud"), // Element name
		  	"base" => "countdown", // Element shortcode
		  	"class" => "nc_countdown",
		  	"category" => __('Content', 'nextcloud'),
		  	'params' => array(


				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html__("ID", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "id",
					"value" => "",
				),

				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html__("Heading", "nextcloud"),
					"description" => esc_html__("For normal tickets", "nextcloud"),
					"param_name" => "heading",
					"value" => "",
				),

				array(
					"type" => "datetime",
					"heading" => esc_html__("Date", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "date",
					"value" => "",
				),

				array(
					"type" => "datetime",
					"heading" => esc_html__("Early bird end", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "early_bird_end",
					"value" => "",
				),

				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html__("Heading for early bird", "nextcloud"),
					"description" => esc_html__("For early bird tickets", "nextcloud"),
					"param_name" => "early_bird_heading",
					"value" => "",
				),

				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html__("Custom CSS classes", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "custom_class",
					"value" => "",
				),

			)
		  )
	  );
}
*/

//shortcode countdown for events
add_shortcode('countdown', 'countdown_shortcode_funct');
function countdown_shortcode_funct($atts) {
	ob_start();
	$atts = shortcode_atts(array(
		'id' => 'countdown',
		'heading' => '',
		'early_bird_heading' => '',
		'date' => '',
		'early_bird_end' => '',
		'custom_class' => ''
	), $atts);
	$id = $atts['id'];
	$heading = $atts['heading'];
	$early_bird_heading = $atts['early_bird_heading'];
	$date = $atts['date'];
	$early_bird_end = $atts['early_bird_end'];
	$default_format = "F j, Y H:i:s";


	if ( ! empty($early_bird_end) && ! empty($early_bird_heading) ) {
		$tz  = wp_timezone();
		$now = new DateTime('now', $tz);

		$deadline = DateTime::createFromFormat($default_format, $early_bird_end, $tz);

		// If parsing failed, createFromFormat returns false
		if ( $deadline instanceof DateTime ) {
			if ( $deadline > $now ) {
				$date    = $early_bird_end;
				$heading = $early_bird_heading;
			}
		}
	}


	$custom_class = $atts['custom_class'];
?>		

		<?php if($heading != '') { ?>
		<div class="wpb_text_column wpb_content_element">
		<h3 style="text-align: center;"><?php 
		echo $heading;
		?></h3>
		</div>
		<?php } ?>

		<div id="<?php echo $id; ?>" class="countdown <?php echo $custom_class; ?>"></div>
		<script>
		// Set the date we're counting down to
		var countDownDate = new Date("<?php echo $date; ?>").getTime();
		// Update the count down every 1 second
		var x = setInterval(function() {

		// Get today's date and time
		var now = new Date().getTime();
			
		// Find the distance between now and the count down date
		var distance = countDownDate - now;
			
		// Time calculations for days, hours, minutes and seconds
		var days = Math.floor(distance / (1000 * 60 * 60 * 24));
		var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		var seconds = Math.floor((distance % (1000 * 60)) / 1000);

		
		if(days == 1){
			var days_label = "<?php echo __('day', 'nextcloud'); ?>";
		} else {
			var days_label = "<?php echo __('days', 'nextcloud'); ?>";
		}

		if(hours == 1){
			var hours_label = "<?php echo __('hour', 'nextcloud'); ?>";
		} else {
			var hours_label = "<?php echo __('hours', 'nextcloud'); ?>";
		}

		if(minutes == 1){
			var minutes_label = "<?php echo __('minute', 'nextcloud'); ?>";
		} else {
			var minutes_label = "<?php echo __('minutes', 'nextcloud'); ?>";
		}

		if(seconds == 1){
			var seconds_label = "<?php echo __('second', 'nextcloud'); ?>";
		} else {
			var seconds_label = "<?php echo __('seconds', 'nextcloud'); ?>";
		}
		
			
		// Output the result in an element with id="demo"
		var full_content = '';
		var days_div = "<div class='days'>" + days + "<span class=''>"+days_label+"</span></div>";
		
		if(days != 0){
			full_content = full_content + days_div;
		}
		
		full_content = full_content + "<div class='hours'>" +hours + "<span class=''>"+hours_label+"</span></div>" + "<div class='min'>" + minutes + "<span class=''>"+minutes_label+"</span></div>" + "<div class='sec'>" + seconds + "<span class=''>"+seconds_label+"</span></div>";

		document.getElementById("<?php echo $id; ?>").innerHTML = full_content;
			
		// If the count down is over, write some text 
		if (distance < 0) {
			clearInterval(x);
			document.getElementById("<?php echo $id; ?>").innerHTML = " - ";
		}
		}, 1000);
		</script>
		<?php
		$result = ob_get_clean();
		return $result;
}



//home page graphic carousel
add_action('vc_before_init', 'nc_homepage_carousel_funct');
function nc_homepage_carousel_funct() {
	vc_map(
		array(
			"name" => __("Homepage carousel", "nextcloud"), // Element name
			"base" => "homepage_carousel", // Element shortcode
			"class" => "homepage_carousel",
		  	"category" => __('Content', 'nextcloud'),
			//'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/custom_element_params.js'),
			'params' => array(
				//repeater images mobile
				array(
					"type" => "attach_images",
					"holder" => "img",
					"class" => "",
					"heading" => __("Mobile images", "nextcloud"),
					"param_name" => "mobile_images",
					"value" => __("", "nextcloud"),
					"admin_label" => false
				),
				//repeater images desktop
				array(
					"type" => "attach_images",
					"holder" => "img",
					"class" => "",
					"heading" => __("Desktop images", "nextcloud"),
					"param_name" => "desktop_images",
					"value" => __("", "nextcloud"),
					"admin_label" => false
				),

				array(
					"type" => "textfield",
					"heading" => esc_html__("Custom CSS Classes", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "custom_css",
					"value" => "",
				),

				array(
					"type" => "vc_link",
					"heading" => esc_html__("Custom Link", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "link",
					"value" => "",
				),

				array(
					"type" => "textfield",
					"heading" => esc_html__("Autoplay Time", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "autoplay_timeout",
					"value" => "",
				)


			),
			//'custom_markup' => '<div class="vc_custom-element-container">Mobile: "{{ params.mobile_images }}", Desktop: "{{ params.desktop_images }}"</div>'
			'custom_markup' => '<div class="vc_custom-element-container">Custom screenshots for mobile and desktop</div>'
		)
  	);
}

add_shortcode('homepage_carousel', 'nc_homepage_carousel_shortcode_funct');
function nc_homepage_carousel_shortcode_funct($atts) {
	ob_start();

	$atts = shortcode_atts(array(
		'mobile_images' => '',
		'desktop_images' => '',
		'custom_css' => '',
		'link' => '',
		'autoplay_timeout' => ''
	), $atts);

	$mobile_items = $atts['mobile_images'];
	$mobile_image_ids = explode(',',$mobile_items);
	$desktop_items = $atts['desktop_images'];
	$desktop_image_ids = explode(',',$desktop_items);
	$link = vc_build_link($atts['link']);
	$autoplay_timeout = $atts['autoplay_timeout'];
?>
<div class="nc-home-graphic <?php echo $atts['custom_css']; ?>" data-timeout="<?php echo $autoplay_timeout; ?>">

<?php if($link['url']) {
				echo '<a href="'.$link['url'].'" title="'.$link['title'].'" target="'.$link['target'].'">';
			}
?>

<div class="desktop-slider-container">
  <div class="dm-device">
	<div class="device light">
	  <div class="screen">
		<div id="desktop_slider" class="slider owl-carousel owl-theme">

			<?php
			$i = 1;
			foreach( $desktop_image_ids as $desktop_image_id ){
				$attachment_image = wp_get_attachment_image_src( $desktop_image_id, 'full' );
				echo '<div class="slider__item slider__item--'.$i.'" style="background-image: url('.$attachment_image[0].');"></div>';
				$i++;
			}
			?>	

		</div>
	  </div>
	</div>
  </div>
</div>

<div class="mobile-slider-container">
  <div class="dm-device">
	<div class="device light">
	  <div class="screen">
		<div id="mobile_slider" class="slider owl-carousel owl-theme">

			<?php
			$j = 1;
			foreach( $mobile_image_ids as $mobile_image_id ){
				$attachment_image = wp_get_attachment_image_src( $mobile_image_id, 'full' );
				echo '<div class="slider__item slider__item--'.$j.'" style="background-image: url('.$attachment_image[0].');"></div>';
				$j++;
			}
			?>

		</div>
	  </div>
	</div>
  </div>
</div> 

<?php if($link['url']) {
				echo '</a>';
			}
?>


</div>
<script>
	var mobile_slider = jQuery('#mobile_slider');

	mobile_slider.owlCarousel({
	loop:true,
	autoplay: 1000,
	autoplayTimeout: <?php if(isset($autoplay_timeout) && $atts['autoplay_timeout']!='' ) {
		echo $autoplay_timeout;
	} else {
		echo "4000";
	}
	?>,
	margin:0,
	dots: false,
	nav:false,
	responsive:{
		0:{
			items:1
		},
		600:{
			items:1
		},
		1000:{
			items:1
		}
	},
	//onDragged: owl_stop_autoplay,
	autoplayHoverPause:true
	});

	var owl_desktop_slider = jQuery('#desktop_slider');

	owl_desktop_slider.owlCarousel({
		loop:true,
		autoplay: 1000,
		autoplayTimeout: <?php if(isset($autoplay_timeout) && $atts['autoplay_timeout']!='' ) {
			echo $autoplay_timeout;
		} else {
			echo "4000";
		}
		?>,
		margin:0,
		dots: false,
		nav:false,
		responsive:{
			0:{
				items:1
			},
			600:{
				items:1
			},
			1000:{
				items:1
			}
		},
		//onDragged: owl_stop_autoplay,
		autoplayHoverPause:true,

		/*
		onInitialized: function(e){
		var activeItemIndex = e.item.index;
		console.log("active item: "+activeItemIndex);
		if(activeItemIndex == 3){
				owl_desktop_slider.trigger('stop.owl.autoplay');
			setTimeout(function () { 
				owl_desktop_slider.trigger('play.owl.autoplay')}, 10000)
		}
		}
		*/
		/*
		onChange: function(e){
			owl_desktop_slider.trigger('play.owl.autoplay');
		}
		*/


	});

	//delay of 2sec between mobile and desktop slider
	var autoplayDelay = 2000;
	if (autoplayDelay) {
		owl_desktop_slider.trigger('stop.owl.autoplay');
		
		setTimeout(function() {
			owl_desktop_slider.trigger('play.owl.autoplay');
		}, autoplayDelay);
	}


</script>
<?php
$result = ob_get_clean();
return $result;
}





//Single Quote element
add_action('vc_before_init', 'wpb_nc_single_quote_funct');
function wpb_nc_single_quote_funct() {
    vc_map(
        array(
            "name" => __("Single quote", "nextcloud"),
            "base" => "single_quote",
            "class" => "single_quote",
            "category" => __('Content', 'nextcloud'),
            'params' => array(
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => __("Quote style", "nextcloud"),
                    "param_name" => "quote_style",
                    "value" => array(
                        __("Quote with image", "nextcloud") => "with_image",
                        __("Text only", "nextcloud") => "text_only"
                    ),
                    "std" => "with_image"
                ),
                array(
                    "type" => "attach_image",
                    "holder" => "img",
                    "class" => "",
                    "heading" => __("Quote Image", "nextcloud"),
                    "param_name" => "featured_image",
                    "value" => __("", "nextcloud"),
                    "dependency" => array(
                        "element" => "quote_style",
                        "value" => "with_image"
                    )
                ),
                array(
                    "type" => "textarea",
                    "heading" => esc_html__("Quote text", "nextcloud"),
                    "description" => esc_html__("", "nextcloud"),
                    "param_name" => "description",
                    "value" => "",
                ),
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => __("Quote Title/Person name", "nextcloud"),
                    "param_name" => "title",
                    "value" => __("", "nextcloud"),
                ),
                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => __("Person position", "nextcloud"),
                    "param_name" => "position",
                    "value" => __("", "nextcloud"),
                ),
                array(
                    "type" => "vc_link",
                    "heading" => esc_html__("Link", "nextcloud"),
                    "description" => esc_html__("", "nextcloud"),
                    "param_name" => "link",
                    "value" => "",
                ),
                array(
                    "type" => "textfield",
                    "heading" => esc_html__("Custom CSS Classes", "nextcloud"),
                    "description" => esc_html__("", "nextcloud"),
                    "param_name" => "custom_css",
                    "value" => "",
                )
            )
        )
    );
}

add_shortcode('single_quote', 'single_quote_funct');
function single_quote_funct($atts) {
    ob_start();
    $a = shortcode_atts(array(
        'quote_style' => 'with_image',
        'featured_image' => '',
        'title' => '',
        'position' => '',
        'description' => '',
        'link' => '',
        'custom_css' => ''
    ), $atts);

    $quote_style = $a['quote_style'];
    $featured_image = $a['featured_image'];
    $title = $a['title'];
    $position = $a['position'];
    $description = isset($a['description']) ? $a['description'] : '';
    $link = vc_build_link($a['link']);
    $custom_css = $a['custom_css'];

    if($quote_style == 'text_only') {
        // Text only style output
        ?>
        <div class="nc_single_quote_no_image <?php echo $custom_css; ?>">

            <div class="quote">
                <?php echo $description; ?>
            </div>

            <?php if($title || $position) { ?>
            <div class="citation">
                <?php if($title) { ?>
                <div class="quote_title">
                    <h4><?php echo $title; ?></h4>
                </div>
                <?php } ?>

                <?php if($position) { ?>
                <div class="position">
                    <?php echo $position; ?>
                </div>
                <?php } ?>
            </div>
            <?php } ?>

            <?php if(isset($a['link']) && $link['url'] != '' ){ ?>
            <div class="quote_btn vc_btn3-container btn-no-bg btn_learn_more vc_btn3-left vc_do_btn">
                <a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" title="<?php if(isset($link['title'])) echo $link['title']; ?>" class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-rounded vc_btn3-style-modern vc_btn3-icon-right vc_btn3-color-grey">
                <?php 
                if(isset($link['title']) && $link['title'] != '') {
                    echo $link['title'];
                } else {
                    echo __('Learn more','nextcloud');
                }
                ?> <i class="vc_btn3-icon fas fa-angle-right"></i>
                </a>
            </div>
            <?php } ?>

        </div>
        <?php
    } else {
        // Quote with image style output (original)
        ?>
        <div class="nc_single_quote <?php echo $custom_css; ?>">

            <div class="quote_image">
                <img src="<?php echo wp_get_attachment_image_url($featured_image, 'full'); ?>" alt="Quote - <?php echo $title; ?>">	
            </div>

            <div class="title_position">
                <div class="quote_title">
                    <h4>
                        <?php echo $title; ?>
                    </h4>
                </div>

                <?php if($position) { ?>
                <div class="position">
                    <?php echo $position; ?>
                </div>
                <?php }?>
            </div>

            <div class="quote">
                <?php echo $description; ?>
            </div>

            <?php if(isset($a['link']) && $link['url'] != '' ){ ?>
            <div class="quote_btn vc_btn3-container btn-no-bg btn_learn_more vc_btn3-left vc_do_btn">
                <a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" title="<?php if(isset($link['title'])) echo $link['title']; ?>" class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-rounded vc_btn3-style-modern vc_btn3-icon-right vc_btn3-color-grey">
                <?php 
                if(isset($link['title']) && $link['title'] != '') {
                    echo $link['title'];
                } else {
                    echo __('Learn more','nextcloud');
                }
                ?> <i class="vc_btn3-icon fas fa-angle-right"></i>
                </a>
            </div>
            <?php } ?>

        </div>
        <?php
    }

    $result = ob_get_clean();
    return $result;
}



/*
//Single Quote element
add_action('vc_before_init', 'wpb_nc_single_quote_funct');
function wpb_nc_single_quote_funct() {
	vc_map(
		  array(
		  	"name" => __("Single quote", "nextcloud"), // Element name
		  	"base" => "single_quote", // Element shortcode
		  	"class" => "single_quote",
		  	"category" => __('Content', 'nextcloud'),
		  	'params' => array(
				array(
					"type" => "attach_image",
					"holder" => "img",
					"class" => "",
					"heading" => __("Quote Image", "nextcloud"),
					"param_name" => "featured_image",
					"value" => __("", "nextcloud"),
				),
				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"admin_label" => true,
					"heading" => __("Quote Title/Person name", "nextcloud"),
					"param_name" => "title",
					"value" => __("", "nextcloud"),
				),

				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "",
					"admin_label" => true,
					"heading" => __("Person position", "nextcloud"),
					"param_name" => "position",
					"value" => __("", "nextcloud"),
				),

				array(
					"type" => "textarea",
					"heading" => esc_html__("Quote text", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "description",
					"value" => "",
				),


				array(
					"type" => "vc_link",
					"heading" => esc_html__("Link", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "link",
					"value" => "",
				),

				array(
					"type" => "textfield",
					"heading" => esc_html__("Custom CSS Classes", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "custom_css",
					"value" => "",
				)

			)
		  )
	  );
}

add_shortcode('single_quote', 'single_quote_funct');
function single_quote_funct($atts) {
	ob_start();
	$a = shortcode_atts(array(
		'featured_image' => '',
		'title' => '',
		'position' => '',
		'description' => '',
		'link' => '',
		'custom_css' => ''
	), $atts);

	$featured_image = $a['featured_image'];
	$title = $a['title'];
	$position = $a['position'];

	$description = '';
	if(isset($a['description'])) {
		$description = $a['description'];
	}
	
	$link = vc_build_link($a['link']);
	$custom_css = $a['custom_css'];
	?>
    <div class="nc_single_quote <?php echo $custom_css; ?>">

							<div class="quote_image">
								<img src="<?php echo wp_get_attachment_image_url($featured_image, 'full'); ?>" alt="Quote - <?php echo $title; ?>">	
							</div>

							<div class="title_position">
								<div class="quote_title">
									<h4>
										<?php echo $title; ?>
									</h4>
								</div>

								<?php if($position) { ?>
								<div class="position">
									<?php echo $position; ?>
								</div>
								<?php }?>
							</div>


							<div class="quote">
								<?php echo $description; ?>
							</div>

							<?php if(isset($a['link']) && $link['url'] != '' ){ ?>
								<div class="quote_btn vc_btn3-container  btn-no-bg btn_learn_more vc_btn3-left vc_do_btn">
									<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" title="<?php if(isset($link['title'])) echo $link['title']; ?>" class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-rounded vc_btn3-style-modern vc_btn3-icon-right vc_btn3-color-grey">
									<?php 
									if(isset($link['title']) && $link['title'] != '') {
										echo $link['title'];
									} else {
										echo __('Learn more','nextcloud');
									}
									?> <i class="vc_btn3-icon fas fa-angle-right"></i>
									</a>
								</div>
							<?php } ?>
					
	
    </div>
    <?php
	$result = ob_get_clean();
	return $result;
}
*/


// Single Quote No Image element
add_action('vc_before_init', 'wpb_nc_single_quote_no_image_funct');
function wpb_nc_single_quote_no_image_funct() {
    vc_map(
        array(
            "name" => __("Single quote no image", "nextcloud"),
            "base" => "single_quote_no_image",
            "class" => "single_quote_no_image",
            "category" => __('Content', 'nextcloud'),
            'params' => array(
                array(
                    "type" => "textarea",
                    "heading" => esc_html__("Quote text", "nextcloud"),
                    "description" => esc_html__("", "nextcloud"),
                    "param_name" => "description",
                    "value" => "",
                ),

                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => __("Person name", "nextcloud"),
                    "param_name" => "title",
                    "value" => __("", "nextcloud"),
                ),

                array(
                    "type" => "textfield",
                    "holder" => "div",
                    "class" => "",
                    "admin_label" => true,
                    "heading" => __("Person position", "nextcloud"),
                    "param_name" => "position",
                    "value" => __("", "nextcloud"),
                ),

                array(
                    "type" => "vc_link",
                    "heading" => esc_html__("Link", "nextcloud"),
                    "description" => esc_html__("", "nextcloud"),
                    "param_name" => "link",
                    "value" => "",
                ),

                array(
                    "type" => "textfield",
                    "heading" => esc_html__("Custom CSS Classes", "nextcloud"),
                    "description" => esc_html__("", "nextcloud"),
                    "param_name" => "custom_css",
                    "value" => "",
                )
            )
        )
    );
}

add_shortcode('single_quote_no_image', 'single_quote_no_image_funct');
function single_quote_no_image_funct($atts) {
    ob_start();
    $a = shortcode_atts(array(
        'title' => '',
        'position' => '',
        'description' => '',
        'link' => '',
        'custom_css' => ''
    ), $atts);

    $title = $a['title'];
    $position = $a['position'];
    $description = isset($a['description']) ? $a['description'] : '';
    $link = vc_build_link($a['link']);
    $custom_css = $a['custom_css'];
    ?>
    <div class="nc_single_quote_no_image <?php echo $custom_css; ?>">

        <div class="quote">
            <?php echo $description; ?>
        </div>

        <?php if($title || $position) { ?>
        <div class="citation">
            <?php if($title) { ?>
            <div class="quote_title">
                <h4><?php echo $title; ?></h4>
            </div>
            <?php } ?>

            <?php if($position) { ?>
            <div class="position">
                <?php echo $position; ?>
            </div>
            <?php } ?>
        </div>
        <?php } ?>

        <?php if(isset($a['link']) && $link['url'] != '' ){ ?>
        <div class="quote_btn vc_btn3-container btn-no-bg btn_learn_more vc_btn3-left vc_do_btn">
            <a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" title="<?php if(isset($link['title'])) echo $link['title']; ?>" class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-rounded vc_btn3-style-modern vc_btn3-icon-right vc_btn3-color-grey">
            <?php 
            if(isset($link['title']) && $link['title'] != '') {
                echo $link['title'];
            } else {
                echo __('Learn more','nextcloud');
            }
            ?> <i class="vc_btn3-icon fas fa-angle-right"></i>
            </a>
        </div>
        <?php } ?>

    </div>
    <?php
    $result = ob_get_clean();
    return $result;
}






//Repeater single quotes carousel
add_action('vc_before_init', 'vc_single_quotes_carousel_funct');
function vc_single_quotes_carousel_funct() {
	vc_map(
		  array(
		  	"name" => __("Single quotes carousel", "nextcloud"), // Element name
		  	"base" => "single_quotes_carousel", // Element shortcode
		  	"class" => "single_quotes_carousel",
		  	"category" => __('Carousel', 'nextcloud'),
		  	'params' => array(

		  		array(
		  			'type' => 'param_group',
		  			'param_name' => 'box_repeater_items',
		  			'params' => array(
		  				array(
		  					"type" => "attach_image",
		  					"holder" => "img",
		  					"class" => "",
		  					"heading" => __("Image", "nextcloud"),
		  					"param_name" => "image",
		  					"value" => __("", "nextcloud"),
		  				),
		  				array(
		  					"type" => "textfield",
		  					"holder" => "div",
		  					"class" => "",
		  					"admin_label" => true,
		  					"heading" => __("Name", "nextcloud"),
		  					"param_name" => "title",
		  					"value" => __("", "nextcloud"),
		  				),

                          array(
                            "type" => "textfield",
                            "holder" => "div",
                            "class" => "",
                            "admin_label" => true,
                            "heading" => __("Position", "nextcloud"),
                            "param_name" => "position",
                            "value" => __("", "nextcloud"),
                        ),

						array(
							"type" => "textarea",
							"heading" => esc_html__("Quote", "nextcloud"),
							"description" => esc_html__("", "nextcloud"),
							"param_name" => "quote",
							"value" => "",
						),

		  				array(
		  					"type" => "vc_link",
		  					"holder" => "div",
		  					"class" => "client_link",
		  					"admin_label" => true,
		  					"heading" => __("Link", "nextcloud"),
		  					"param_name" => "link",
		  					"value" => __("", "nextcloud"),
		  				)

		  			)
		  		),

				array(
					"type" => "textfield",
					"heading" => esc_html__("Custom CSS Classes", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "custom_css",
					"value" => "",
				)

		  	)
		  )
	  );
}

add_shortcode('single_quotes_carousel', 'single_quotes_carousel_funct');
function single_quotes_carousel_funct($atts) {
	ob_start();
	$atts = shortcode_atts(array(
		'box_repeater_heading' => '',
		'box_repeater_items' => '',
		'custom_css' => ''
	), $atts, 'box_repeater');

	$custom_css = $atts['custom_css'];
	if(function_exists('vc_param_group_parse_atts')){
		$items = vc_param_group_parse_atts($atts['box_repeater_items']);
	}
	?>
        <?php if ($items) { ?>
              <div class="single_quotes_carousel owl-carousel owl-theme">
                  <?php  foreach ($items as $item) {
                    $image = $item['image'];
                    $title = $item['title'];
                    $quote = $item['quote'];

					if (isset($item['link'])) {
						$link = vc_build_link($item['link']);
					}
					//$custom_css = $item['custom_css'];
					?>

                    <div class="nc_single_quote <?php if(isset($custom_css)) echo $custom_css; ?>">
                    <div class="quote_image">
                        <img src="<?php echo wp_get_attachment_image_url($image, 'full'); ?>" alt="Quote - <?php echo $title; ?>">	
                    </div>

                    <div class="title_position">
                        <div class="quote_title">
                            <h4>
                                <?php echo $title; ?>
                            </h4>
                        </div>

                        <?php if(isset($item['position'])) { ?>
                        <div class="position">
                            <?php echo $item['position']; ?>
                        </div>
                        <?php }?>
                    </div>

                    <div class="quote">
                        <?php echo $quote; ?>
                    </div>

                    <?php if (isset($item['link'])) { ?>
							<div class="vc_btn3-container  btn-main btn-small vc_btn3-center">
								<?php if (isset($item['link'])) { ?>
									<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" title="<?php echo $item['title']; ?>" class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-rounded vc_btn3-style-modern vc_btn3-icon-right vc_btn3-color-grey">
								<?php } ?>
								<?php 
								echo __('Learn more', 'nextcloud'); 
								?> <i class="vc_btn3-icon fas fa-angle-right"></i>
								<?php if ($item['link']) { ?>
									</a>
								<?php } ?>
							</div>
					<?php } ?>

                    </div>

            <?php } // foreach ?>
        <?php } // if ?>

      </div>
      <?php
	  $result = ob_get_clean();
	return $result;
}







add_shortcode('previous_episodes_podcast', 'previous_episodes_podcast_funct');
function previous_episodes_podcast_funct($atts) {
	ob_start();
	/*
	$a = shortcode_atts(array(
		'featured_image' => ''
	), $atts);
	*/
?>
<div class="row whitepaper-list-section">
			<?php
			$my_wp_query = new WP_Query();

			$onepost = $my_wp_query->query(array(
				'post_type' => array('post'),
				'category_name' => 'podcast',
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'orderby' => 'date',
				'order' => 'DSC',
			));

			$podcast_wp_query = new WP_Query();
			$onepodcast = $podcast_wp_query->query(array(
				'post_type' => array('podcast'),
				//'category_name' => 'podcast',
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'orderby' => 'date',
				'order' => 'DSC',
			));

			//$all_podcasts = array_merge($onepost, $onepodcast);


			foreach ($onepodcast as $onepostsingle) {
				
				//get_template_part('inc/blog_loop_single');

				
				$featured_image = get_the_post_thumbnail($onepostsingle->ID, 'large', array( 'class' => 'feat_img' ));
				$title = $onepostsingle->post_title;
				$date = (string)get_the_date('F d, Y', $onepostsingle->ID);
				$cat = get_the_category($onepostsingle->ID);
				$link = get_permalink($onepostsingle->ID) ?: '';
				$author_id = (int)$onepostsingle->post_author;
				echo '<div class="col-lg-4 col-md-6 spacer">';
				echo '<div class="post-box">';
				
				if($featured_image) {
					?>
					<div class="post-img">
					<a href="<?php echo $link; ?>" title="<?php echo $title; ?>">
						<?php echo $featured_image; ?>
					</a>
					</div>
					<?php
				}
				

				echo '<div class="paper-box">';
				echo '<ul class="cats">';
				//echo '<li>'.__('Posted in','nextcloud').' </li>';
				echo '<li>'.__('Posted by ','nextcloud'). get_the_author_meta('display_name', $author_id) . '</li>';
				echo '</ul>';
				echo '<h4><a href="' . $link . '">' . $title . '</a></h4>';
				echo '<ul class="info">';
				echo '<li>' . $date . '</li>';
				echo '<li><a class="c-btn" href="' . $link . '">'.__('Read more','nextcloud').'</a></li>';
				echo '</ul>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
				


			}
			wp_reset_query();
			?>
		</div>
<?php
	$result = ob_get_clean();
	return $result;
}


// shortcode to get all the tags associated with the current post/page
function nc_get_post_tags(){
	//global $post;
	$tags = '';
	$posttags = get_the_tags();
	if ($posttags) {
		foreach($posttags as $tag) {
			//$tags .= $tag->name.',';
			$tag_names[] = $tag->name;
		}
		$tags = implode( ',', $tag_names );
	}
	return $tags;
}
add_shortcode('nc_tags', 'nc_get_post_tags');


//shortcode to get all the event interests which are saved in a custom field "interests"
function nc_get_event_interests(){
	$interests = '';
	$event_interests = get_post_meta(get_the_ID(), 'interests', true);

	if ($event_interests) {
		$interests = implode( ',', $event_interests);
	}

	return $interests;
}
add_shortcode('nc_event_interests', 'nc_get_event_interests');


//New version of Nextcloud Section - used mostly on Changelog page
add_action('vc_before_init', 'wpb_nc_version_funct');
function wpb_nc_version_funct() {
	vc_map(
		  array(
		  	"name" => __("Nextcloud Version section", "nextcloud"), // Element name
		  	"base" => "nc_version_section", // Element shortcode
		  	"class" => "nc_version_section",
		  	"category" => __('Content', 'nextcloud'),
		  	'params' => array(

				array(
					"type" => "textfield",
					"holder" => "version",
					"class" => "",
					"admin_label" => true,
					"heading" => __("Version", "nextcloud"),
					"param_name" => "version",
					"value" => __("", "nextcloud"),
				),

				array(
					"type" => "datetime",
					"heading" => esc_html__("Version date", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "date",
					"value" => "",
				),

				array(
					"type" => "textarea_html",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html__("Changelog list", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "content",
					"value" => "",
				),

				array(
					"type" => "vc_link",
					"holder" => "div",
					"class" => "",
					"admin_label" => true,
					"heading" => __("Blog post link", "nextcloud"),
					"param_name" => "link",
					"value" => __("", "nextcloud"),
				),

				//withdrawn
				array(
					"type" => "checkbox",
					"holder" => "div",
					"class" => "",
					"admin_label" => true,
					"heading" => __("Withdrawn", "nextcloud"),
					"param_name" => "withdrawn",
					"value" => __("", "nextcloud"),
				),

			)
		  )
	  );
}

add_shortcode('nc_version_section', 'nc_version_section_funct');
function nc_version_section_funct($atts,  $content = null) {
	ob_start();
	$a = shortcode_atts(array(
		'version' => '',
		'date' => '',
		'content' => '',
		'link' => '',
		'withdrawn' => ''

	), $atts);

	$version = $a['version'];
	$id = str_replace(".", "-", $version);
	$date = $a['date'];
	$content = wpb_js_remove_wpautop($content, true);
	$link = vc_build_link($a['link']);
	$withdrawn = $a['withdrawn'];

	?>
	<div class="nc_version <?php if($withdrawn) echo 'withdrawn'; ?>">
		
		<div class="row">

			<div class="col-lg-6 nc_version_column">
				<div class="version-infos" id="version-fixed-<?php echo $id; ?>">
					<div class="version_info__inner">
					<p class="version_date"><i class="fas fa-calendar-alt"></i> <?php 
					$originalDate = str_replace("/", "-", $date);
					echo date("F j, Y", strtotime($originalDate));
					?></p>

					<h3 class="version_name" id="<?php echo $id; ?>">
					<span class="copy_id" style="" title="Copy URL of this version">#</span>
					<?php echo __('Version','nextcloud')." ".$version; ?>
					<?php if($withdrawn) echo '<span class="withdrawn">('.__('Withdrawn','nextcloud').')</span>'; ?>
					</h3>

					<div class="download">
						<div class="download_links">
							<h4><?php echo __('Download:','nextcloud'); ?></h4>
							<a target="_blank" href="https://download.nextcloud.com/server/releases/nextcloud-<?php echo $version; ?>.tar.bz2">nextcloud-<?php echo $version; ?>.tar.bz2</a>
							<?php echo __('or','nextcloud'); ?> <a target="_blank" href="https://download.nextcloud.com/server/releases/nextcloud-<?php echo $version; ?>.zip">nextcloud-<?php echo $version; ?>.zip</a>
							</div>


							<div class="integrity_links">
							<h5 class="check_integrity_title">
							<?php echo __('Check the file integrity with:','nextcloud'); ?>
							</h5>

							<div class="integrity_block">
							<span class="integrity_name">MD5:</span>
							<a target="_blank" href="https://download.nextcloud.com/server/releases/nextcloud-<?php echo $version; ?>.tar.bz2.md5">nextcloud-<?php echo $version; ?>.tar.bz2.md5</a>
							<?php echo __('or','nextcloud'); ?> <a target="_blank" href="https://download.nextcloud.com/server/releases/nextcloud-<?php echo $version; ?>.zip.md5">nextcloud-<?php echo $version; ?>.zip.md5</a>
							</div>

							<div class="integrity_block">
							<span class="integrity_name">SHA256:</span>
							<a target="_blank" href="https://download.nextcloud.com/server/releases/nextcloud-<?php echo $version; ?>.tar.bz2.sha256">nextcloud-<?php echo $version; ?>.tar.bz2.sha256</a>
							<?php echo __('or','nextcloud'); ?> <a target="_blank" href="https://download.nextcloud.com/server/releases/nextcloud-<?php echo $version; ?>.zip.sha256">nextcloud-<?php echo $version; ?>.zip.sha256</a>
							</div>


							<div class="integrity_block">
							<span class="integrity_name">SHA512:</span>
							<a target="_blank" href="https://download.nextcloud.com/server/releases/nextcloud-<?php echo $version; ?>.tar.bz2.sha512">nextcloud-<?php echo $version; ?>.tar.bz2.sha512</a>
							<?php echo __('or','nextcloud'); ?> <a target="_blank" href="https://download.nextcloud.com/server/releases/nextcloud-<?php echo $version; ?>.zip.sha512">nextcloud-<?php echo $version; ?>.zip.sha512</a>
							</div>
							

							<div class="integrity_block">
							<span class="integrity_name">PGP (<a target="_blank" href="https://nextcloud.com/nextcloud.asc">Key</a>):</span>
							<a target="_blank" href="https://download.nextcloud.com/server/releases/nextcloud-<?php echo $version; ?>.tar.bz2.asc">nextcloud-<?php echo $version; ?>.tar.bz2.asc</a>
							<?php echo __('or','nextcloud'); ?> <a target="_blank" href="https://download.nextcloud.com/server/releases/nextcloud-<?php echo $version; ?>.zip.asc">nextcloud-<?php echo $version; ?>.zip.asc</a>
							</div>

						</div>
					</div>

							<?php if($link['url']) {
								?>
								<div class="vc_btn3-container  btn-main btn-small vc_btn3-inline"><a class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-rounded vc_btn3-style-modern vc_btn3-icon-right vc_btn3-color-grey" href="<?php echo $link['url']; ?>" title="<?php echo __('Read our announcement blog for all the the details', 'nextcloud'); ?>" target="_blank">
								<?php echo __('Read our announcement blog for all the the details', 'nextcloud'); ?> <i class="vc_btn3-icon fas fa-angle-right"></i></a>
								</div>
								<?php
							}?>
					
					</div>


				</div>

			</div>

			<div class="col-lg-6">
				<?php 
				if ($content) { ?>
				<h4><?php echo __('Changes:','nextcloud'); ?></h4>
				<?php	
					echo '<div class="changelog_list">'.$content.'</div>';
				}
				?>
			</div>

		</div>

	</div>
	<?php
	$result = ob_get_clean();
	return $result;
}

function nc_simple_slider() {
	// Title
	vc_map(
		array(
			'name' => __('Simple slider'),
			'base' => 'nc_simple_slider_content',
			'category' => __('Carousel'),
			'params' => array(


				array(
					"type" => "attach_images",
					"heading" => esc_html__("Add Images", "nextcloud"),
					"description" => esc_html__("Add Images", "nextcloud"),
					"param_name" => "screenshots",
					"value" => "",
				),

				array(
					"type" => "checkbox",
					"heading" => esc_html__("Autoplay", "nextcloud"),
					"description" => esc_html__("Autoplay", "nextcloud"),
					"param_name" => "autoplay",
					"value" => "",
				),
				array(
					"type" => "checkbox",
					"heading" => esc_html__("Remove stage padding?", "nextcloud"),
					"description" => '',
					"param_name" => "remove_stage_padding",
					"value" => "",
				),

				array(
					"type" => "checkbox",
					"heading" => esc_html__("Force 16/9 aspect ratio", "nextcloud"),
					"description" => '',
					"param_name" => "force_16_9_aspect_ratio",
					"value" => "",
				),

				array(
					"type" => "checkbox",
					"heading" => esc_html__("Loop", "nextcloud"),
					"description" => '',
					"param_name" => "loop",
					"value" => "",
				),

				array(
					"type" => "checkbox",
					"heading" => esc_html__("Show arrows", "nextcloud"),
					"description" => '',
					"param_name" => "show_arrows",
					'value'       => array(
						esc_html__( 'Yes', 'nextcloud' ) => 'yes',
					),
					'std'         => 'yes',
				),

				array(
					"type" => "checkbox",
					"heading" => esc_html__("Show dots", "nextcloud"),
					"description" => '',
					"param_name" => "show_dots",
					"value" => "",
				),

				array(
					"type" => "textfield",
					"heading" => esc_html__("Custom CSS", "nextcloud"),
					"description" => esc_html__("Custom CSS classes", "nextcloud"),
					"param_name" => "custom_css",
					"value" => "",
				)

			)
		)
	);
}
add_action('vc_before_init', 'nc_simple_slider');

function nc_simple_slider_content_function($atts, $content) {
	ob_start();
	$gallery = shortcode_atts(
		array(
			'screenshots' => 'screenshots',
			'autoplay' => "false",
			'remove_stage_padding' => false,
			'force_16_9_aspect_ratio' => false,
			'loop' => false,
			'show_arrows' => true,
			'show_dots' => false,
			'custom_css' => ''
		), $atts);

	$image_ids = explode(',', $gallery['screenshots']);
	if($gallery['autoplay']) {
		//$autoplay = "true";
		$autoplay = $gallery['autoplay'];
	}
	
	$removeStagePadding = $gallery['remove_stage_padding'];
	$force_16_9_aspect_ratio = $gallery['force_16_9_aspect_ratio'];
	$loop = $gallery['loop'];
	$show_arrows = $gallery['show_arrows'];
	$show_dots = $gallery['show_dots'];

	$custom_css = $gallery['custom_css'];
	$size='full';

	?>
	<div data-autoplay="<?php echo $autoplay; ?>" class="owl-carousel simple_slider_slideshow <?php 
	//echo $gallery['removeStagePadding']; 
	if($force_16_9_aspect_ratio) {
		echo " force_16_9_aspect_ratio ";
	}
	if($removeStagePadding) {
		echo " noOffset ";
	} ?> <?php echo $custom_css; ?>" id="">
	<?php
		foreach ($image_ids as $image_id) {
		$image = wp_get_attachment_image($image_id, 'full');
		echo '<div class="item gallery_item">';
		//echo $image;
		$image_title = get_the_title($image_id);
		?>
			<a href="<?php echo wp_get_attachment_image_url( $image_id, $size ); ?>" class="simple_slider_slideshow_link" title="<?php echo $image_title; ?>">
                <?php echo wp_get_attachment_image( $image_id, $size ); ?>
            </a>
            <?php if(wp_get_attachment_caption($image_id)) {
                echo '<div class="caption">'.wp_get_attachment_caption($image_id).'</div>';
            }
		echo '</div>';
		}
	?>
	</div>
	<script>
    jQuery(document).ready(function ($) {
		var stagepadding = 50;
		var loop = false; 
		var nav = true;
		var dots = false; 
		
		<?php 
		if($removeStagePadding) {
			echo " stagepadding = 0; ";
		}
		if($loop) {
			echo " loop = true; ";
		}

		if(!$show_arrows) {
			echo " nav = false; ";
		}
		if($show_dots) {
			echo " dots = true; ";
		}
		?>


		var owl_simple_slider = $('.simple_slider_slideshow');
        owl_simple_slider.owlCarousel({
            loop: loop,
            stagePadding: stagepadding,
            //autoWidth:true,
            autoplay: <?php echo $autoplay; ?>,
            margin:10,
            dots: dots,
            nav: nav,
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
			onInitialized: function (event) {
				// Remove role="presentation" from arrows
				$('.owl-prev, .owl-next').removeAttr('role');
				$('.owl-prev, .owl-next').attr('tabindex', 0);
			},
			onDragged: owl_stop_autoplay,
            autoplayHoverPause:true
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
            //owl_simple_slider.trigger('stop.owl.autoplay');
        });

        function owl_stop_autoplay() {
            //console.log('autoplay stopped.');
            owl_simple_slider.trigger('stop.owl.autoplay');
        }

    });
	</script>
	<?php
	$result = ob_get_clean();
	return $result;
}
add_shortcode('nc_simple_slider_content', 'nc_simple_slider_content_function');



add_shortcode('nc_filter_features', 'nc_filter_features_funct');
function nc_filter_features_funct($atts,  $content = null) {
	ob_start();
?>
<div class="features-filters-holder-inner">
	<div class="search-holder">
		<input type="text" placeholder="<?php echo __('Quick search..','nextcloud'); ?>" id="features_filter">
	</div>
</div>
<div class="list_tags" id="list_tags">
</div>
<script>
	jQuery(document).ready(function ($) {
		$.expr[":"].contains = $.expr.createPseudo(function(arg) {
			return function( elem ) {
				return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
			};
		});

		function unique(list) {
			var result = [];
			$.each(list, function(i, e) {
				if ($.inArray(e, result) == -1) result.push(e);
			});
			return result;
		}

		function filterFeatures() {
			var searched_term = $('#features_filter').val();
			$('.nc_feature').each(function(){
				var contains_search_term = false;

				if(searched_term != '') {
					if ($(this).children(':contains("'+searched_term+'")').length > 0) {
						$(this).show();
					} else {
						$(this).hide();
					}
				} else {
					$(this).show();
				}

			});
		}

		function dynamicSort(property) {
			var sortOrder = 1;

			if(property[0] === "-") {
				sortOrder = -1;
				property = property.substr(1);
			}

			return function (a,b) {
				if(sortOrder == -1){
					return b[property].localeCompare(a[property]);
				}else{
					return a[property].localeCompare(b[property]);
				}        
			}
		}
		

		function filterTags(array_tags){
			$('.nc_feature').each(function(){
					var contains_search_term = false;
					var this_section = $(this);
					var show_this_section = true;

					if(array_tags) {
						//console.log("if test");
						$.each( array_tags, function( key, value ) {

							var feat_tags = this_section.find('.feature_tags');
							if (feat_tags.children(':contains("'+value+'")').length > 0) {
								//console.log("it contains the value!")
								//this_section.show();
							} else {
								//console.log("it does not contain the value!")
								//this_section.hide();
								show_this_section = false;
							}
						});


						if(show_this_section) {
							this_section.show();
						} else {
							this_section.hide();
						}

					} else {
						//console.log("else: show section");
						this_section.show();
					}

				});
		}

		var tags = '';
		var tags_array;
		var tags_array_total = [];
		$('.nc_feature').each(function(){
			if( $(this).find('.feature_tags').text()!='' ) {
				var tag = $(this).find('.feature_tags').text().trim();
				//console.log("tag:"+tag);

				if (tag.indexOf(',') > -1) {
					tags_array = tag.split(",");
					//add it to the main array
					for (var i = 0; i < tags_array.length; ++i) {
						//
						if(tags_array[i] != ''){
							var tag_trimmed = tags_array[i].replace(/^\s+/, "");
							tags_array_total.push(tag_trimmed);
						}
						
					}
				} else {
					if(tag != ''){
						var tag_trimmed = tag.replace(/^\s+/, "");
						tags_array_total.push(tag_trimmed);
					}
					
				}
				
			}
		});

		if(tags_array_total) {
			//console.log(tags_array_total);
			tags_array_total = unique(tags_array_total); //remove duplicates
			tags_array_total = tags_array_total.sort(); //sort alphabetically

			var i;
			for (i = 0; i < tags_array_total.length; ++i) {
				if(tags_array_total[i]!=''){
					$('#list_tags').append('<a href="#" class="link_tag" title="Filter '+tags_array_total[i]+' tag" data-tag="'+tags_array_total[i]+'">'+tags_array_total[i]+'</a>');
				}
				
			}	
		}
		

		var searched_tags_text = '';
		var searched_tags_URL = '';
		var array_tags = [];
		var url= document.location.href;
		console.log("Original URL: "+url); 

		const queryString = window.location.search;
		//console.log("queryString: "+queryString);


		//filter already if URL contains filter
		if(queryString != '') {
			const urlParams = new URLSearchParams(queryString);
			//console.log("urlParams: "+urlParams);

			if(urlParams && urlParams.get('filter')) {
				//filter found
				//console.log('filter found!!');

				const filters = urlParams.get('filter').replace(/-/g, ' ');
				var filters_arr = filters.split(",");
				//console.log(filters_arr);
				array_tags = filters_arr;

				$.each( filters_arr, function( key, value ) {
					//value = value.toTitleCase();
					$('.list_tags a').each(function(){
						if( $(this).text() == value ) {
							$(this).addClass('active');	
						}
					});
				});
				
				//filter tags
				filterTags(filters_arr);

				//populate the search field
				$('#features_filter').val(filters);
			}

			
		}



		var adjustURLwithFilters = function(searched_tags_URL){
			console.log("searched_tags_URL: "+searched_tags_URL);

			//if(queryString != '' ) {
				//if URL contains others parameters

				if(searched_tags_URL != '') {
					if(queryString.indexOf('filter')) {
						//if the other parameters contain "filter"
						window.history.replaceState(null, null, "?filter="+searched_tags_URL);
					} else {
						window.history.replaceState(null, null, "&filter="+searched_tags_URL);
					}
					
				} else {
					//remove also the empty filter
					window.history.pushState(null, null, url.split("?")[0]);
				}

			/*
			} else {
				if(searched_tags_URL != '') {
					window.history.replaceState(null, null, "?filter="+searched_tags_URL);
				} else {
					//remove also the empty filter
					window.history.pushState(null, null, url.split("?")[0]);
				}
			}
				*/

			
			
		}

		$('.list_tags a').on('click', function(e){
			e.preventDefault();	

			if($(this).hasClass( "active" )){
				//remove this tag
				//console.log("searched_tags_text: "+searched_tags_text);

				//$(this).siblings().removeClass('active');
				$(this).removeClass('active');	

				//filterFeatures();
				//console.log("deactivate this button and re-do search");
				var this_tag = $(this).html();

				//remove this tag from the array_tags
				array_tags = $.grep(array_tags, function(value) {
					return value != this_tag;
				});

				searched_tags_text = array_tags.join(", ");
				searched_tags_URL = array_tags.join(",").replace(/\s+/g, '-');

				$('#features_filter').val(searched_tags_text);
				
				//console.log(JSON.stringify(array_tags));
				filterTags(array_tags);

				//add filter to URL
				adjustURLwithFilters(searched_tags_URL);

			}else {
				//add this tag
				array_tags.push($(this).html());
				searched_tags_text = array_tags.join(", ");
				searched_tags_URL = array_tags.join(",").replace(/\s+/g, '-');

				$(this).addClass('active');
				$('#features_filter').val(searched_tags_text);

				//console.log(JSON.stringify(array_tags));
				filterTags(array_tags);

				//add filter to URL
				adjustURLwithFilters(searched_tags_URL);
			}

		})

		$('#features_filter').keyup(filterFeatures);
	});
</script>
<?php
	$result = ob_get_clean();
	return $result;
}


//Feature section
add_action('vc_before_init', 'wpb_nc_feature_funct');
function wpb_nc_feature_funct() {
	vc_map(
		  array(
		  	"name" => __("Nextcloud Feature", "nextcloud"), // Element name
		  	"base" => "nc_feature", // Element shortcode
		  	"class" => "nc_single_feature",
		  	"category" => __('Content', 'nextcloud'),
		  	'params' => array(

				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "feature_id",
					"admin_label" => false,
					"heading" => __("ID", "nextcloud"),
					"param_name" => "id",
					"value" => __("", "nextcloud"),
				),


				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "feature_tags",
					"admin_label" => false,
					"heading" => __("Tags", "nextcloud"),
					"param_name" => "group",
					"value" => __("", "nextcloud"),
				),


				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "feature_title",
					"admin_label" => false,
					"heading" => __("Title", "nextcloud"),
					"param_name" => "title",
					"value" => __("", "nextcloud"),
				),

				array(
					"type" => "textfield",
					"holder" => "div",
					"class" => "feature_subtitle",
					"admin_label" => false,
					"heading" => __("Subtitle", "nextcloud"),
					"param_name" => "subtitle",
					"value" => __("", "nextcloud"),
				),


				array(
					"type" => "attach_image",
					"holder" => "img",
					"class" => "feature_img",
					"heading" => __("Image", "nextcloud"),
					"param_name" => "image",
					"value" => __("", "nextcloud"),
					"admin_label" => false
				),


				array(
					"type" => "textarea",
					"holder" => "div",
					"class" => "feature_video",
					"heading" => esc_html__("Video Embed URL", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "video_url",
					"value" => "",
				),

				array(
					"type" => "textarea",
					"holder" => "div",
					"class" => "feature_desc",
					"heading" => esc_html__("Description", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "description",
					"value" => "",
				),

				array(
					"type" => "vc_link",
					"holder" => "div",
					"class" => "feature_link",
					"admin_label" => false,
					"heading" => __("Feature link", "nextcloud"),
					"param_name" => "link",
					"value" => __("", "nextcloud"),
				),


				array(
					'type' => 'param_group',
					'param_name' => 'box_repeater_items',
					'params' => array(
						array(
							"type" => "attach_image",
							"holder" => "img",
							"class" => "",
							"heading" => __("Feature Image", "nextcloud"),
							"param_name" => "feature_image",
							"value" => __("", "nextcloud"),
						),
						array(
							"type" => "textfield",
							"holder" => "div",
							"class" => "",
							"admin_label" => true,
							"heading" => __("Feature Title", "nextcloud"),
							"param_name" => "title",
							"value" => __("", "nextcloud"),
						),

					  array(
						  "type" => "textarea",
						  "heading" => esc_html__("Description", "nextcloud"),
						  "description" => esc_html__("", "nextcloud"),
						  "param_name" => "description",
						  "value" => "",
					  ),

						array(
							"type" => "vc_link",
							"holder" => "div",
							"class" => "client_link",
							"admin_label" => true,
							"heading" => __("Link", "nextcloud"),
							"param_name" => "link",
							"value" => __("", "nextcloud"),
						),

					  array(
						  "type" => "textfield",
						  "heading" => esc_html__("Video URL", "nextcloud"),
						  "description" => esc_html__("", "nextcloud"),
						  "param_name" => "video_url",
						  "value" => "",
					  ),

					  array(
						  "type" => "textfield",
						  "heading" => esc_html__("Custom CSS Classes", "nextcloud"),
						  "description" => esc_html__("", "nextcloud"),
						  "param_name" => "custom_css",
						  "value" => "",
					  ),

					  array(
						  "type" => "checkbox",
						  "heading" => esc_html__("No Overlay", "nextcloud"),
						  "description" => esc_html__("", "nextcloud"),
						  "param_name" => "no_overlay",
						  "value" => "",
					  ),

					)
				),

				array(
					"type" => "textarea_html",
					"holder" => "div",
					"class" => "",
					"heading" => esc_html__("Other features", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "content",
					"value" => "",
				),

			)
		  )
	  );
}
add_shortcode('nc_feature', 'nc_feature_funct');
function nc_feature_funct($atts,  $content = null) {
	ob_start();
	$a = shortcode_atts(array(
		'id' => '',
		'group' => '',
		'title' => '',
		'subtitle' => '',
		'description' => '',
		'image' => '',
		'video_url' => '',
		'link' => '',
		'box_repeater_items'=> '',
		'content' => ''
	), $atts);

	$id = $a['id'];
	$title = $a['title'];
	$subtitle = $a['subtitle'];
	$group = $a['group'];
	$description = $a['description'];
	$video_url = $a['video_url'];
	$content = wpb_js_remove_wpautop($content, true);

	if($a['image']) {
		$image = wp_get_attachment_image($a['image'], 'large');
		$image_url= wp_get_attachment_image_url($a['image'], 'full');
	} else {
		$image = wp_get_attachment_image(350226, 'large');
		$image_url= wp_get_attachment_image_url(350226, 'full');
	}

	if(function_exists('vc_param_group_parse_atts')){
		$items = vc_param_group_parse_atts($a['box_repeater_items']);
		$link = vc_build_link($a['link']);
	}

?>
<section class="nc_feature" id="<?php echo $id; ?>">
<div class="container">
<div class="vc_row wpb_row vc_row-fluid vc_column-gap-30 vc_row-o-content-middle vc_row-flex row-feature">

	<div class="wpb_column vc_column_container vc_col-sm-4">
			<div class="vc_column-inner">
				<div class="wpb_wrapper">

					<?php if( $a['video_url']!='' && !$image_url ) { 
						?>
						<div class="video-block vimeo_container">
							<iframe src="<?php echo $video_url; ?>" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen="" title="" frameborder="0"></iframe>
						</div>
						<?php
					} else if ( $a['video_url']!='' && $image_url ) { ?>
						
						<div class="wpb_single_image wpb_content_element vc_align_center feature_main_image">
							<figure class="wpb_wrapper vc_figure">
								<div class="vc_single_image-wrapper vc_box_border_grey">
									<a class="popup-video" href="<?php echo $video_url; ?>" title="<?php echo $link['title']; ?>">
										<div class="play-icon">
										<svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" id="" version="1.1" viewBox="0 0 24 24">
				<path class="st0" d="M9.5,16.5l7-4.5-7-4.5v9ZM12,22c-1.383,0-2.683-.263-3.9-.788s-2.275-1.238-3.175-2.138-1.613-1.958-2.138-3.175-.788-2.517-.788-3.9.263-2.683.788-3.9,1.238-2.275,2.138-3.175,1.958-1.613,3.175-2.138,2.517-.788,3.9-.788,2.683.263,3.9.788,2.275,1.238,3.175,2.138,1.613,1.958,2.138,3.175.788,2.517.788,3.9-.263,2.683-.788,3.9-1.238,2.275-2.138,3.175-1.958,1.613-3.175,2.138-2.517.788-3.9.788ZM12,20c2.233,0,4.125-.775,5.675-2.325s2.325-3.442,2.325-5.675-.775-4.125-2.325-5.675-3.442-2.325-5.675-2.325-4.125.775-5.675,2.325-2.325,3.442-2.325,5.675.775,4.125,2.325,5.675,3.442,2.325,5.675,2.325Z"></path>
				</svg>

										</div>
										<div class="overlay"></div>
										<?php echo $image; ?>
									</a>
								</div>
							</figure>
						</div>

					<?php } else { ?>
						<div class="wpb_single_image wpb_content_element vc_align_center feature_main_image">
							<figure class="wpb_wrapper vc_figure">
								<div class="vc_single_image-wrapper vc_box_border_grey">
									<?php if ($link['url']) { ?>
										<a href="<?php echo $link['url']; ?>" title="<?php echo $link['title']; ?>">
									<?php } else { ?>
										<a class="popup-screenshot" href="<?php echo $image_url; ?>" title="<?php echo $link['title']; ?>">
									<?php } ?>
										<?php echo $image; ?>
									</a>
								</div>
							</figure>
						</div>
					<?php } ?>

			</div>
		</div>
	</div>


	<div class="wpb_column vc_column_container vc_col-sm-8 ">

		<div class="vc_column-inner copy_element_link copy_feature">
			<div class="wpb_wrapper">
		
				<div class="wpb_text_column wpb_content_element nc_text_as_separator feature_tags">
					<div class="wpb_wrapper">
						<p>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="#0082c9"><path d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h440q19 0 36 8.5t28 23.5l216 288-216 288q-11 15-28 23.5t-36 8.5H160Zm0-80h440l180-240-180-240H160v480Zm220-240Z"/></svg>	
						<?php echo $group; ?></p>
					</div>
				</div>

				<div class="wpb_text_column wpb_content_element nc-section-title">
					<div class="wpb_wrapper">
						<h2><?php echo $title; ?></h2>
					</div>
				</div>

				<?php if( $subtitle) { ?>
				<div class="wpb_text_column wpb_content_element nc-section-subtitle">
					<div class="wpb_wrapper">
						<h3 class="subtitle"><?php echo $subtitle; ?></h3>
					</div>
				</div>
				<?php } ?>

				<div class="wpb_text_column wpb_content_element">
					<div class="wpb_wrapper">
						<?php echo $description; ?>
					</div>
				</div>

				<?php if ($link['url']) { ?>
					<div class="vc_btn3-container btn-main btn-small vc_btn3-inline">
						<a class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-rounded vc_btn3-style-modern vc_btn3-icon-right vc_btn3-color-grey" href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" title="<?php echo $link['title']; ?>"><?php
						if(isset($link['title']) && $link['title']!= '' ) {
							echo $link['title'];
						} else {
							echo __('Learn more', 'nextcloud');
						}
						?> <i class="vc_btn3-icon fas fa-angle-right"></i></a>
					</div>
				<?php } ?>

			</div>
		</div>
	</div>
</div>

	
	<?php if ($items && isset($items[0]['title']) ) { ?>
			<div class="vc_row wpb_row vc_row-fluid vc_column-gap-30 vc_row-o-content-middle vc_row-flex carousel-features row_features_carousel">

				<h4 class="features_carousel_title"><?php echo __('Other related features:','nextcloud'); ?></h4>

              	<div id="features_carousel_<?php echo $id; ?>" class="features_carousel_page owl-carousel owl-theme">
            	<?php  foreach ($items as $item) {
				if(isset($item['title'])) {

					if (isset($item['link'])) {
						$link = vc_build_link($item['link']);
					}
					?>
                      <div class="feature_item <?php if(isset($item['custom_css'])) { echo $item['custom_css']; }; ?>">

					  	<?php if ( isset($item['link']) && !isset($item['video_url']) ) { ?>
					  	<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" title="<?php echo $item['title']; ?>">
						<?php } ?>


						<div class="feature_image">
							<?php if ( isset($item['video_url']) && !isset($item['no_overlay']) ) { ?>
								<a href="<?php echo $item['video_url']; ?>" title="<?php echo $item['title']; ?>" class="popup-video">
							<?php } ?>

							<?php if ( isset($item['video_url']) && !isset($item['no_overlay']) ) { ?>
								<div class="play-icon"><i class="fas fa-play-circle"></i></div>
								<div class="overlay"></div>
							<?php } ?>

							<?php //if no video url nor Link
								if ( !isset($item['video_url']) && !isset($item['link']) && !isset($item['no_overlay'])  ) { ?>
								<a href="<?php echo wp_get_attachment_image_url($item['feature_image'], 'full'); ?>" title="<?php echo $item['title']; ?>" class="popup-screenshot">
								<div class="screenshot-icon"><i class="fas fa-expand-arrows-alt"></i></div>
								<div class="overlay-screenshot"></div>
								<?php 
								}
							?>
							
							<?php if(isset($item['feature_image'])) {
								echo wp_get_attachment_image($item['feature_image'], 'large');
							} ?>

							<?php //if no video url nor Link
								if ( !isset($item['video_url']) && !isset($item['link']) ) { ?>
								</a>
							<?php } ?>


							<?php if (isset($item['video_url']) && !isset($no_overlay) ) { ?>
							</a>
							<?php } ?>

						</div>
						

						<div class="feature_inner <?php if (isset($item['link'])) { echo " with_link"; } ?>">
							
							<h5 class="title">
							<?php if (isset($item['link'])) { ?>
								<a href="<?php echo $link['url']; ?>" title="<?php echo $item['title']; ?>" class="">
							<?php } ?>
							<?php echo $item['title']; ?>
							<?php if (isset($item['link'])) { ?>
								</a>
							<?php } ?>
							</h5>

							<?php if(isset($item['description'])) { ?>
							<div class="description"><?php echo $item['description']; ?></div>
							<?php } ?>

							<?php if (isset($item['link'])) { ?>
							<span class="read_more">
							<?php if (isset($item['link'])) { ?>
									<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" title="<?php echo $item['title']; ?>" class="">
								<?php } ?>
								<?php 
								if(isset($link['title'])){
									echo $link['title'];
								}
								else {
									echo __('Read more', 'nextcloud'); 
								}
								?> <i class="fas fa-angle-right"></i>
								<?php if ($item['link']) { ?>
									</a>
								<?php } ?>
							</span>
							<?php } ?>

						</div>


						<?php if (isset($item['link']) && !isset($item['video_url']) ) { ?>
				  		</a>
						<?php } ?>


                      	</div>
                  <?php 
				  }
				} ?>
              </div>
			  </div>

			  <script>
				jQuery(document).ready(function ($) {
					$('.features_carousel_page').owlCarousel({
						loop:false,
						autoplay: false,
						margin:30,
						dots: false,
						nav:true,
						responsive:{
							0:{
								items:1
							},
							600:{
								items:2
							},
							1000:{
								items:3
							}
						}
					});
				});
			  </script>
    <?php } ?>
	<?php if($content) { ?>
	<div class="vc_row wpb_row vc_row-fluid vc_column-gap-30 vc_row-o-content-middle vc_row-flex other-features-list">
		<div class="wpb_column vc_column_container vc_col-sm-12">
			<div class="vc_column-inner">
				<div class="wpb_wrapper">
					<?php echo $content; ?>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>

</div>
</section>
<?php
	$result = ob_get_clean();
	return $result;
}


function get_post_id_func() {
	return get_the_id();
}
add_shortcode('get_post_id','get_post_id_func');


function cl_acf_set_language() {
	return acf_get_setting('default_language');
}

function get_global_option($name) {
	  add_filter('acf/settings/current_language', 'cl_acf_set_language', 100);
	  $option = get_field($name, 'option');
	  remove_filter('acf/settings/current_language', 'cl_acf_set_language', 100);
	  return $option;
}


function get_clients_link_func($atts){
	$a = shortcode_atts( array(
		'os' => '', //default windows
		'href' => false
	), $atts );

	$version = '';
	$url_base = '';
	if (get_field('clients_download_version', 'option') ) {
		$version = get_global_option('clients_download_version');
	}
	if (get_global_option('clients_download_url_base')) {
		$url_base = get_global_option('clients_download_url_base');
	} else {
		$url_base = 'https://github.com/nextcloud-releases/desktop/releases/latest/download/';
	}


	$return = $a['os'];

	switch($a['os']) {
		case "win":

			if($a['href']){
				$return = $url_base.'Nextcloud-'.$version.'-x64.msi';
			} else {
				$return = '<a class="a-btn btn-light winOS" href="'.$url_base.'Nextcloud-'.$version.'-x64.msi" target="_blank">
			<i class="fab fa-windows"></i>Windows 10/11 (64 bit)</a>';
			}
			break;

		case "mac":
			if($a['href']){
				$return = $url_base.'Nextcloud-'.$version.'.pkg';
			} else {
			$return = '<a class="a-btn btn-light macOS" href="'.$url_base.'Nextcloud-'.$version.'.pkg" target="_blank">
			<i class="fab fa-apple"></i>macOS 12+ (64 bit, universal)</a>';
			}
			break;

		case "mac-vfs":
			$return = '<a class="a-btn btn-light macOS" href="'.$url_base.'Nextcloud-'.$version.'-macOS-vfs.pkg" target="_blank" rel="noopener">
			<i class="fab fa-apple"></i>macOS Virtual files 12+ (64 bit, universal)</a>';
			break;
		
		case "linux":
			$return = '<a class="a-btn btn-light unixOS" href="'.$url_base.'Nextcloud-'.$version.'-x86_64.AppImage" target="_blank">
			<i class="fab fa-linux"></i>Linux AppImage</a>';
			break;
		
		default:
			$return = "";
			break;
	}

	return $return;

}
add_shortcode('get_clients_link','get_clients_link_func');


//webinars list shortcode
add_action('vc_before_init', 'nc_webinars_list_funct');
function nc_webinars_list_funct() {
	vc_map(
		  array(
		  	"name" => __("Webinar list", "nextcloud"), // Element name
		  	"base" => "webinars_list", // Element shortcode
		  	"class" => "webinars_list",
			"category" => __('Content', 'nextcloud'),
		  	'params' => array(
					array(
						"type" => "textfield",
						"heading" => esc_html__("Title", "nextcloud"),
						"description" => esc_html__("", "nextcloud"),
						"param_name" => "title",
						"value" => "",
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("ID", "nextcloud"),
						"description" => esc_html__("", "nextcloud"),
						"param_name" => "id",
						"value" => "",
					),
					array(
						"type" => "checkbox",
						"heading" => esc_html__("Upcoming?", "nextcloud"),
						"description" => esc_html__("", "nextcloud"),
						"param_name" => "upcoming",
						"value" => "",
					),
					array(
						"type" => "checkbox",
						"heading" => esc_html__("Include all languages?", "nextcloud"),
						"description" => esc_html__("Order first same language items, then translated.", "nextcloud"),
						"param_name" => "include_all_languages",
						"value" => "",
					),
					array(
						"type" => "checkbox",
						"heading" => esc_html__("Show filters", "nextcloud"),
						"description" => esc_html__("", "nextcloud"),
						"param_name" => "show_filters",
						"value" => "",
					)
			)
		)
	);
}

add_shortcode('webinars_list', 'webinars_list_shortcode_funct');
function webinars_list_shortcode_funct($atts) {
	ob_start();
	$type = '';

	$atts = shortcode_atts(array(
		'title' => '',
		'id' => '',
		'upcoming' => false,
		'include_all_languages' => false,
		'show_filters' => false
	), $atts);
	$title = $atts['title'];
	$id = $atts['id'];
	$upcoming = $atts['upcoming'];
	$include_all_languages = $atts['include_all_languages'];
	$show_filters = $atts['show_filters'];
?>
<section class="blog-section webinars-upcoming-<?php if(isset($upcoming)) echo $upcoming; ?>" id="<?php if(isset($id)) echo $id; ?>">
	<div class=""><?php //.container ?>
		<?php
		echo '<div class="row justify-content-between align-items-center">';
		
		if (!empty($title)) {
			echo '<div class="col-lg-6">';
			echo '<div class="section-title">';
			echo '<h3>';
			echo $title;
			echo '</h3>';
			echo '</div>';
			echo '</div>';
		}

		if ( !$upcoming && function_exists('wpes_search_form')) {
			//Webinar recordings search
			$search_id = 200771;
			echo '<div class="col-lg-4">';
			echo '<div class="form-holder">';

			$form = '<form role="search" method="get" id="searchform" class="search-form" action="' . home_url('/') . '" >
			<div class="custom-form">
			<label>
				<span class="screen-reader-text">'.__('Search:').'</span>
				<input type="search" class="search-field " placeholder="'.__('Search here..', 'nextcloud').'" value="'.get_search_query().'" name="s">
			</label>
			<input type="submit" class="search-submit" title="'.__('Search', 'nextcloud').'" id="search-submit" />
			<input type="hidden" value="'.$search_id.'" name="wpessid" />';
			$form .= '<input type="hidden" value="webinar_recordings" name="custom_type" />';
			$form .= '</div></form>';
			echo $form;

			echo '</div>';
			echo '</div>';
		}
		echo '</div>';
		?>

		<?php if($show_filters) { ?>
		<div class="row row-webinars-filters">
			<div class="col-lg-12">
				<div class="webinar_list_tags">
				<?php 
				$post_type = 'on-demand-webinar';
				$tags = get_terms( array(
					'taxonomy'   => 'post_tag',
					'hide_empty' => false,// set to false if you want to include unused tags
					'exclude'    => array( 269, 45, 240, 400 ),
					'object_ids' => get_posts( array(
						'post_type'      => $post_type,
						'posts_per_page' => -1,
						'fields'         => 'ids',
						)
					)
				) );

				if ( ! empty( $tags ) && ! is_wp_error( $tags ) ) {
					echo '<a data-tag_id="0" class="webinar_tag active reset" href="#">' .__('Show all','nextcloud'). '</a>';
					foreach ( $tags as $tag ) {
						echo '<a data-tag_id="'.$tag->term_id.'" class="webinar_tag" href="#">' . esc_html( $tag->name ) . '</a>';
					}
				}
				?>
				</div>
			</div> 
		</div>
		<?php } ?>

		<div class="row row-list-blog">
			<?php
			$default_posts_per_page = get_option( 'posts_per_page' );
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			$limit = $default_posts_per_page;
			$timezone = new DateTimeZone('Europe/Berlin');
			$current_date_time = wp_date("Y-m-d H:i:s", null, $timezone );
			$my_current_lang = apply_filters( 'wpml_current_language', null );

			// The Query
			$args = array(
				'posts_per_page' => $default_posts_per_page,
				'post_status' => 'publish',
				'orderby' => 'date',
				'paged=' => $paged,
				'tag__not_in' => array(269), // exclude unlisted tag
				'category__not_in'=> array(225, 226) //exclude Private category
			);
			$args['orderby'] = 'meta_value';
            

			if($upcoming) {
				//upcoming webinars
				$args['post_type'] = array('event');

				$args['tax_query'] = array(
						array(
							'taxonomy' => 'event_categories',
							'field'    => 'slug',
							'terms'    => 'webinars',
						)
				);

				$args['meta_query'] = array(
					array(
						'key' => 'event_start_date_and_time',
						'value'   => $current_date_time,
						'compare' => '>=',
						'type'	=> 'DATETIME'
					)
				);
				$args['order'] = 'ASC';

				if($include_all_languages) {
					$args['suppress_filters'] = true;
					$args['posts_per_page'] = -1;
				}


			} else {
				//past webinars = recordings
				$args['post_type'] = array('on-demand-webinar');

				$args['meta_query'] = array(
					'relation' => 'AND',
					
					array(
						'key' => 'event_start_date_and_time',
						'value'   => $current_date_time,
						'compare' => '<',
						'type'	=> 'DATETIME'
					),
		
					array(
						'key'     => 'download_available',
						'value'	  => '',
						'compare' => '!=',
					),
					
				);
				$args['order'] = 'DESC';
				if($include_all_languages) {
					$args['suppress_filters'] = true;
					$args['posts_per_page'] = -1;
				}

			}


			if($include_all_languages) {
				$same_lang_posts_ids = array();
				$tr_posts_ids = array();
				$base_query = new WP_Query($args);
				if ( $base_query->have_posts() ) {
					//$i = 1;
					//echo "<p>Total: ".$base_query->found_posts."</p>";

					while($base_query->have_posts()) {
						$base_query->the_post();
						$webinar_language_details = apply_filters( 'wpml_post_language_details', NULL, get_the_ID() );
						$webinar_lang_code = $webinar_language_details['language_code'];
						
						//echo '<p>';
						//echo '<img style="max-width: 40px;" src="https://nextcloud.com/p/sitepress-multilingual-cms/res/flags/'.$webinar_lang_code.'.svg" /> ';

						if( $webinar_lang_code == $my_current_lang) {
							// to be shown first
							$same_lang_posts_ids[] = get_the_ID();
							//echo "<b>".$i."</b> (Same lang) ".get_the_ID().": <a href='".get_the_permalink()."'>".get_the_title()."</a>";
							//$i++;
						} else {
							// to be shown after
							$tr_posts_ids[] = get_the_ID();
							//echo "(Different lang) ".get_the_ID().": <a href='".get_the_permalink()."'>".get_the_title()."</a>";
						}
						//echo '</p>';

					}
				}
				//merge the 2 arrays
				$all_lang_posts = array_merge($same_lang_posts_ids, $tr_posts_ids);
				//print_r($all_lang_posts);

				$args_new = array(
						//'post_type' => 'event',
						'post_status' => array('publish'),
						//'posts_per_page' => -1,
						'post__in' => $all_lang_posts,
						'orderby' => 'post__in',
						'suppress_filters' => true,
						'tag__not_in' => array(269), // exclude unlisted tag
						'category__not_in'=> array(225, 226) //exclude Private category
				);

				if($upcoming) {
					$args_new['post_type'] = 'event';
				} else {
					$args_new['post_type'] = 'on-demand-webinar';
				}
				// limit to 9 items 
				$args_new['posts_per_page'] = $default_posts_per_page;


				$the_query = new WP_Query($args_new);
			} else {
				//main query
				$the_query = new WP_Query($args);
			}

			$count = $the_query->found_posts;

			// The Loop
			if ($the_query->have_posts()) {
				while ($the_query->have_posts()) {
					$the_query->the_post();
					get_template_part('inc/webinar_loop_single');
				}
			} else {
				// no posts found
			?>
			<div class="col-12">
				<div class="section-button">
					<h3><?php echo __('No webinars found.','nextcloud'); ?></h3>
				</div>
			</div>
			<?php
			}
			/* Restore original Post Data */
			wp_reset_postdata();
			?>
		</div>
		<?php 
		//show Load more button for the recordings
		if (!$upcoming) {
			
			if(!isset($type)) {
				$type = "on-demand-webinar";
			}
		?>
		<div class="row loadNews_row">
			<div class="col-12">
				<div class="section-button">
					<button class="c-btn btn-main loadNews" id="loadNews_<?php echo $type; ?>" data-post-type="past_webinars" data-count="<?php echo $count; ?>" <?php if($include_all_languages) { 
						echo "data-include_all_lang='true'";
						echo " data-recordings_ids=".implode(',', $all_lang_posts);
					}?> data-limit="<?php echo $limit; ?>">
					<?php 
					echo __('Load more','nextcloud');
					?></button>
				</div>
			</div>
		</div>
		<?php } ?>
	

	</div>
</section>
<?php
	$result = ob_get_clean();
	return $result;
}

add_shortcode('date_diff', 'date_diff_shortcode_funct');
function date_diff_shortcode_funct($atts) {
	$atts = shortcode_atts(array(
		'until' => ''
	), $atts);
	$until = $atts['until'];

	$date_curr = date_create(date("Y/m/d H:i:s"));
	$date_until = date_create($until);
	$diff = date_diff($date_curr,$date_until);
	$days = $diff->format("%a ");
	$more = __(" more ", 'nextcloud');

	if( ICL_LANGUAGE_CODE == 'en' ){
		$return = $days.$more;
	} else {
		$return = $more.$days;
	}
	
	if($diff->days < 2) {
		$return .= __(" day", 'nextcloud');

		if($diff->days==0) {
			$hours = $diff->format("%h");
			$hours_label = __(" hours", 'nextcloud');
			
			if( ICL_LANGUAGE_CODE == 'en' ){
				$return = $hours.$more.$hours_label;
			} else {
				$return = $more.$hours.$hours_label;
			}
		}

	} else {
		$return .= __(" days", 'nextcloud');
	}

	return $return;
}



add_shortcode('custom_search_form', 'custom_search_form_funct');
function custom_search_form_funct($atts) {
	$atts = shortcode_atts(array(
		'id' => '',
		'post_type' => 'case_studies'
	), $atts);
	

	switch($atts['post_type']){
		case "case_studies":
			$search_term = 'Search for case studies..';
			break;

		case "whitepapers":
			$search_term = 'Search for whitepapers..';
			break;

		case "data_sheets":
			$search_term = 'Search for data sheets..';
			break;

		default:
			$search_term = 'Search here..';
			break;
	}

	$return = '';

	if (function_exists('wpes_search_form')) {
			
		//Webinar recordings search
		$search_id = $atts['id'];

		$return = '<div class="form-holder">';
		$return .= '<form role="search" method="get" id="searchform" class="search-form" action="' . home_url('/') . '" >
		<div class="custom-form">
		<label>
			<span class="screen-reader-text">'.__('Search:').'</span>
			<input type="search" class="search-field " placeholder="'.__($search_term, 'nextcloud').'" value="'.get_search_query().'" name="s">
		</label>
		<input type="submit" class="search-submit" title="'.__('Search', 'nextcloud').'" id="search-submit" />
		<input type="hidden" value="'.$search_id.'" name="wpessid" />';
		$return .= '<input type="hidden" value="'.$atts['post_type'].'" name="custom_type" />';
		$return .= '</div></form>';
		//echo $form;
		$return .= '</div>';

	}

	echo $return;
}



//popup shortcode
add_action('vc_before_init', 'nc_popup_form_shortcode_funct');
function nc_popup_form_shortcode_funct() {
	vc_map(
		  array(
		  	"name" => __("Popup form", "nextcloud"), // Element name
		  	"base" => "nc_popup_form", // Element shortcode
		  	"class" => "nc_popup_form",
			"category" => __('Content', 'nextcloud'),
		  	'params' => array(
					array(
						"type" => "textfield",
						"heading" => esc_html__("Popup ID", "nextcloud"),
						"description" => esc_html__("", "nextcloud"),
						"param_name" => "popup_id",
						"value" => "",
					),
					array(
						"type" => "textfield",
						"heading" => esc_html__("Ninja Form ID", "nextcloud"),
						"description" => esc_html__("", "nextcloud"),
						"param_name" => "form_id",
						"value" => "",
					),
					array(
						"type" => "textarea_html",
						"heading" => esc_html__("Content before the form", "nextcloud"),
						"description" => esc_html__("", "nextcloud"),
						"param_name" => "content",
						"value" => "",
					)
			)
		  )
	  );
}
// popup form
add_shortcode('nc_popup_form', 'nc_popup_form_funct');
function nc_popup_form_funct($atts, $content = null) {
	$atts = shortcode_atts(array(
		//'btn_id' => '',
		'popup_id'	=> '',
		'form_id' => '',
		'content' => ''
	), $atts);
	wp_enqueue_script('intlTelInput_utils');
	wp_enqueue_script('intlTelInput');

	ob_start();

	//$btn_id = $atts['btn_id'];
	$popup_id = $atts['popup_id'];
	$form_id = $atts['form_id'];
	$content = wpb_js_remove_wpautop($content, true);
	?>
	<div id="<?php echo $popup_id; ?>" class="white-popup mfp-hide">

	<?php if($content) {
		echo $content;
	}?>
	<?php //echo do_shortcode('[ninja_form id='.$form_id.']'); ?>
	</div>

	<?php
	$result = ob_get_clean();
	return $result;
}


//sign up form
add_shortcode('signup_form', 'signup_form_shortcode_funct');
function signup_form_shortcode_funct($atts) {
	ob_start();
	wp_enqueue_script('nc_register');
	wp_enqueue_style('nc_register');

	require_once locate_template('config.php');
	
	try {
		$ip = whatismyip();
		$readerCity = new GeoIp2\Database\Reader(locate_template('dist/signup/assets/GeoLite2/GeoLite2-City.mmdb'));

		$location = $readerCity->city($ip)->location;
	} catch (\Exception $e) {
		$location = false;
		$ip = null;
	}
	//print_r($location);
	
	$registerL10n = [
		'subscribe' => __('Subscribe to our newsletter', 'nextcloud'),
		'email' => __('Your email address', 'nextcloud'),
		'success' => __('Success! Redirecting you to the provider', 'nextcloud'),
		'error' => __('Error:', 'nextcloud'),
		'processing' => __('Creating your account', 'nextcloud'),
		'register' => __('Sign up', 'nextcloud'),
		'change' => __('change provider', 'nextcloud'),
		'close' => __('close', 'nextcloud'),
		'far' => __('Far far away', 'nextcloud'),
		'geterror' => __('Error while retrieving the providers list.', 'nextcloud'),
		'tos' => __('Terms of service', 'nextcloud'),
		'tosagree' => __('I agree to the %tos%', 'nextcloud'),
		'toserror' => __('Please agree to the terms of service', 'nextcloud')
	];
	
	$officialApps = [
		'files' => __('Files', 'nextcloud'),
		'calendar' => __('Calendar', 'nextcloud'),
		'contacts' => __('Contacts', 'nextcloud'),
		'spreed' => __('Talk' , 'nextcloud'),
		'mail' => __('Mail', 'nextcloud'),
		'tasks' => __('Tasks', 'nextcloud'),
		'notes' => __('Notes', 'nextcloud'),
		'news' => __('News', 'nextcloud'),
		'twofactor_totp' => __('Two-factor authentication', 'nextcloud'),
		'twofactor_u2f' => __('Two-factor U2F', 'nextcloud'),
		'gallery' => __('Gallery','nextcloud'),
		'photos' => __('Photos','nextcloud'),
		'collabora' => __('Collabora Online', 'nextcloud'),
		'onlyoffice' => __('Onlyoffice', 'nextcloud'),
		'deck' => __('Deck', 'nextcloud'),
		'fulltextsearch' => __('Full text search', 'nextcloud'),
		'mindmaps' => __('Mindmaps', 'nextcloud'),
		'passman' => __('Passman', 'nextcloud'),
		'drawio' => __('Draw.io', 'nextcloud'),
		'bookmarks' => __('Bookmarks', 'nextcloud'),
		'audioplayer' => __('Audioplayer', 'nextcloud'),
		'appointments' => __('Appointments', 'nextcloud')
	];
	
	$coreApps = ['files', 'calendar', 'contacts', 'spreed', 'mail', 'tasks', 'notes'];
	?>

	<section class="section--providers">
		<div id="register" class="container"
			data-ip="<?php echo htmlspecialchars($ip) ?>"
			data-ll="<?php echo htmlspecialchars(json_encode($location)) ?>"
			data-ocsapi="<?php echo array_key_exists('HTTP_OCS_APIREQUEST', $_SERVER) ?>"
			data-l10n="<?php echo htmlspecialchars(json_encode($registerL10n)) ?>"
			data-officialapps="<?php echo htmlspecialchars(json_encode($officialApps)) ?>"
			data-coreapps="<?php echo htmlspecialchars(json_encode($coreApps)) ?>"
			data-flow="<?php echo htmlspecialchars($_GET['flow'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" >
		</div>
		<div id="register-details" class="container"><p></p></div>
	</section>


<?php
	$result = ob_get_clean();
	return $result;
}





//Repeater Quotes Carousel
add_action('vc_before_init', 'speakers_carousel_wpb_funct');
function speakers_carousel_wpb_funct() {
	vc_map(
		  array(
		  	"name" => __("Speakers Carousel", "nextcloud"), // Element name
		  	"base" => "speakers_carousel", // Element shortcode
		  	"class" => "speakers_carousel",
		  	"category" => __('Carousel', 'nextcloud'),
		  	'params' => array(

				array(
					"type" => "textfield",
					"heading" => esc_html__("ID", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "id",
					"value" => "",
				),


		  		array(
		  			'type' => 'param_group',
		  			'param_name' => 'box_repeater_items',
		  			'params' => array(
		  				array(
		  					"type" => "attach_image",
		  					"holder" => "img",
		  					"class" => "",
		  					"heading" => __("Feature Image", "nextcloud"),
		  					"param_name" => "feature_image",
		  					"value" => __("", "nextcloud"),
		  				),
		  				array(
		  					"type" => "textfield",
		  					"holder" => "div",
		  					"class" => "",
		  					"admin_label" => true,
		  					"heading" => __("Speaker name", "nextcloud"),
		  					"param_name" => "name",
		  					"value" => __("", "nextcloud"),
		  				),

						  array(
							"type" => "textfield",
							"holder" => "div",
							"class" => "",
							"admin_label" => true,
							"heading" => __("Speaker position", "nextcloud"),
							"param_name" => "position",
							"value" => __("", "nextcloud"),
						),

						array(
							"type" => "textarea",
							"heading" => esc_html__("Description", "nextcloud"),
							"description" => esc_html__("", "nextcloud"),
							"param_name" => "description",
							"value" => "",
						),

		  				array(
		  					"type" => "vc_link",
		  					"holder" => "div",
		  					"class" => "client_link",
		  					"admin_label" => true,
		  					"heading" => __("Link", "nextcloud"),
		  					"param_name" => "link",
		  					"value" => __("", "nextcloud"),
						),

						array(
							"type" => "textfield",
							"heading" => esc_html__("Custom CSS", "nextcloud"),
							"description" => esc_html__("", "nextcloud"),
							"param_name" => "item_custom_css",
							"value" => "",
						)

		  			)
		  		),

				
				array(
					"type" => "textfield",
					"heading" => esc_html__("Custom CSS Classes", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "custom_css",
					"value" => "",
				),

				array(
					"type" => "textfield",
					"heading" => esc_html__("Custom slider items", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "custom_slider_items",
					"value" => "",
				),

				array(
					'type' => 'dropdown',
					'heading' => 'Style',
					'param_name' => 'style',
					'value' => array(
						'Style 1' => 'style_1',
						'Style 2' => 'style_2',
						'Style 3' => 'style_3'
					),
				),

				array(
					"type" => "checkbox",
					"heading" => esc_html__("Limit text", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "limit_text",
					"value" => "",
				),

				array(
					"type" => "textarea",
					"heading" => esc_html__("Custom code for carousel", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "custom_code",
					"value" => "",
				),
				

		  	)
		  )
	  );
}

add_shortcode('speakers_carousel', 'speakers_carousel_funct');
function speakers_carousel_funct($atts) {
	ob_start();
	$atts = shortcode_atts(array(
		'id' => '',
		'box_repeater_heading' => '',
		'box_repeater_items' => '',
		'custom_css' => '',
		'custom_slider_items' => '',
		'style' => '',
		'limit_text' => '',
		'custom_code' => ''
	), $atts, 'box_repeater');

	if(function_exists('vc_param_group_parse_atts')){
		$items = vc_param_group_parse_atts($atts['box_repeater_items']);
	}
	
	$id = $atts['id'];
	$custom_css = $atts['custom_css'];
	$custom_slider_items = $atts['custom_slider_items'];
	$style = $atts['style'];
	$custom_code = $atts['custom_code'];

	if($style == "style_3") { 
		//wp_enqueue_style("vc_animate");
		wp_enqueue_style(
            'wpb-animations',
            plugins_url('/assets/lib/vendor/node_modules/animate.css/animate.min.css', WPB_PLUGIN_FILE),
            array(),
            WPB_VC_VERSION
        );
	}

	?>
      <div class="case_studies avatars speakers <?php echo $custom_css; ?> <?php
	  if($style != 'style_1') echo " style_2 "; 
	  if($style == 'style_3') echo " style_3 ";
	  ?>">

          <?php if ($items) { ?>
              <div id="<?php if(isset($atts['id'])) echo $id; ?>" class="quotes_carousel <?php if($style != "style_2") echo " owl_quotes_carousel "; ?> owl-carousel owl-theme" data-items-desktop="<?php if(isset($custom_slider_items)) echo $custom_slider_items; ?>">
                  <?php  
				  $item_index = 1;
				  foreach ($items as  $item) {
					$item_custom_css ='';
					$delay = 0.5 * $item_index;
					$item_index++;

					if (isset($item['link'])) {
						$link = vc_build_link($item['link']);
					}

					if(isset($item['item_custom_css'])) {
						$item_custom_css = $item['item_custom_css'];
					}
					?>

						<div class="case_study <?php echo $item_custom_css; 
						if($style == "style_3") { 
							echo " wpb_animate_when_almost_visible fadeInRight nc_animated ";
						}
						?>" style="<?php if($style == "style_3") {
							echo "animation-delay: ".$delay."s;"; 
						}?>">
							<div class="vc_column-inner">
					
							<div class="wpb_single_image wpb_content_element vc_align_left image_top">
								<figure>

								<?php if (isset($item['link'])) { ?>
									<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" title="<?php echo $item['title']; ?>" class="vc_single_image-wrapper   vc_box_border_grey">
								<?php } ?>
									
								<?php echo wp_get_attachment_image($item['feature_image'], 'large'); ?>

								<?php if (isset($item['link'])) { ?>
									</a>
								<?php } ?>
								
								</figure>
							</div>
							

							<div class="organization">
								<div class="wpb_wrapper">
									<?php if (isset($item['name'])) { ?>
									<h4>
										<?php echo $item['name']; ?>
									</h4>
									<?php } ?>

									<?php if (isset($item['position'])) { ?>
									<p class="position">
										<?php echo $item['position']; ?>
									</p>
									<?php } ?>
								</div>
								
							</div>

							
							<?php if(isset($item['description'])) { ?>
							<div class="wpb_text_column wpb_content_element quote <?php 
							if(isset($atts['limit_text']) && $atts['limit_text'] ) { echo " limit_text "; } ?>">
							
							<span><?php echo $item['description']; ?></span>

							<?php if(isset($atts['limit_text']) && $atts['limit_text'] ) { ?>
								<div class="read_more"><?php echo __('Read more','nextcloud'); ?></div>
							<?php } ?>


							</div>


							
							
							

							<?php } ?>

							<?php if (isset($item['link'])) { ?>
							<div class="vc_btn3-container  btn-main btn-small vc_btn3-center">
								<?php if (isset($item['link'])) { ?>
									<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" title="<?php echo $item['title']; ?>" class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-rounded vc_btn3-style-modern vc_btn3-icon-right vc_btn3-color-grey">
								<?php } ?>
								<?php 
								echo __('Learn more', 'nextcloud'); 
								?> <i class="vc_btn3-icon fas fa-angle-right"></i>
								<?php if ($item['link']) { ?>
									</a>
								<?php } ?>
							</div>
							<?php } ?>

							</div>
						</div>

                  <?php
					} ?>
              </div>
          <?php } ?>

      </div>
      <?php
		if($style == "style_2") {
			?> 
			<script>
			jQuery(document).ready(function ($) {
				$('#<?php echo $id; ?>').owlCarousel({
					loop:false,
					autoplay: false,
					margin:25,
					dots: true,
					nav:true,
					//onChanged: quotes_carousel_callback,
					onInitialized : quotes_carousel_callback_initialized,
					navText : ['<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 14.299 22.409"><path fill="#0082c9" class="st0" d="M11.25,21.205L1.25,11.205,11.25,1.205l1.8,1.8L4.85,11.205l8.2,8.2-1.8,1.8h0Z"/></svg>','<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 14.299 22.409"><path fill="#0082c9" class="st0" d="M3.05,21.205l-1.8-1.8,8.2-8.2L1.25,3.005l1.8-1.8,10,10L3.05,21.205h0Z"/></svg>'],
					responsive:{
						0:{
							items:1
						},
						800:{
							items:2
						},
						1000:{
							items: 1
						}
					},
					//autoWidth:true,
					//autoHeight: true
				});

				var quotes_carousel = $('#<?php echo $id; ?>');

				function quotes_carousel_callback_initialized() {
					$('#<?php echo $id; ?> .owl-item').each(function(i){
						var index = $(this).index();
						$(this).data('owl-index', index);
					});
				}


				$('#<?php echo $id; ?> .owl-item').click(function(){
					var index = $('#<?php echo $id; ?> .owl-item').index(this);
					console.log("owlIndex: "+index);
					$(this).siblings().removeClass('is-active');
					$(this).addClass('is-active');
					//var index = $('.resources-carousel-nav__item').index(this);
					quotes_carousel.trigger('to.owl.carousel', [index, 500, true]);
				});
			});
			</script>

		<?php
		}



		if($style == "style_3") {
			?> 
			<script>
			jQuery(document).ready(function ($) {
				$('#<?php echo $id; ?>').owlCarousel({
					loop:false,
					autoplay: false,
					margin:25,
					dots: true,
					autoplayTimeout: 3000,
					nav:true,
					onChanged: quotes_carousel_onChanged_callback,
					onInitialized : quotes_carousel_callback_initialized_2,
					onDragged: owl_stop_autoplay,
					navText : ['<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 14.299 22.409"><path fill="#0082c9" class="st0" d="M11.25,21.205L1.25,11.205,11.25,1.205l1.8,1.8L4.85,11.205l8.2,8.2-1.8,1.8h0Z"/></svg>','<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 14.299 22.409"><path fill="#0082c9" class="st0" d="M3.05,21.205l-1.8-1.8,8.2-8.2L1.25,3.005l1.8-1.8,10,10L3.05,21.205h0Z"/></svg>'],
                    autoplayHoverPause:true,
					responsive:{
						0:{
							items:1
						},
						800:{
							items:2
						},
						1000:{
							items: <?php if(isset($custom_slider_items)) echo $custom_slider_items; ?>
						}
					},
					//autoWidth:true,
					//autoHeight: true
				});

				var quotes_carousel = $('#<?php echo $id; ?>');

				var stopped = false;  
				quotes_carousel.on('click', function(e) {
                    stopped = true;    
					owl_stop_autoplay();
                });

                function owl_stop_autoplay() {
                    quotes_carousel.trigger('stop.owl.autoplay');
                }

				function quotes_carousel_callback_initialized_2() {
					$('#<?php echo $id; ?> .owl-item').each(function(i){
						var index = $(this).index();
						$(this).data('owl-index', index);
					});

					$('#<?php echo $id; ?> .owl-item').first().addClass('is-active');
				}


				function quotes_carousel_onChanged_callback(event) {
					var index      = event.item.index;
					var element   = event.target; 
					//console.log("Item changed to: "+index);


					/*
					if(clicked_item != 0) {
						var quotes_carousel = $('#<?php echo $id; ?>');
						quotes_carousel.trigger('to.owl.carousel', [clicked_item, 500, true]);
					}
					*/
					
					var $items = $(event.target).find('.owl-item');
					// Remove the class from all items
					//$items.removeClass('is-active');
					

					$('#<?php echo $id; ?> .owl-item').removeClass('is-active');

					// Get all active items
					var $activeItems = $items.filter('.active');
					// Add the class to the first active item
					//$activeItems.first().addClass('is-active');
					
					var hasActive = false;
					$items.each(function(){
						if( $(this).hasClass('is-active')) {
							hasActive = true;
						}
					});

					if(!hasActive) {
						$('#<?php echo $id; ?> .owl-item').eq(index).addClass('is-active');
					}


					/*
					//last item showed? go to first item
					//var carousel = event.relatedTarget; // owlCarousel instance
					var currentIndex = event.item.index; // current first visible item
					var totalItems = event.item.count;   // total items
					var visibleItems = event.page.size;  // how many items are visible

					// Check if we're on the last page
					if (currentIndex >= totalItems - visibleItems) {
						move_slider_visible_items(event);	
					}
					*/
				}


				function move_slider_visible_items(event) {
					var currentIndex = event.item.index;
					console.log("currentIndex is: "+currentIndex);
					var totalItems = event.item.count;
					var visibleItems = event.page.size;

					var quotes_carousel = $('#<?php echo $id; ?>');
					
					if(!stopped){
						setTimeout(() => {
							//quotes_carousel.trigger('to.owl.carousel', [0, 500, true]);
						}, 3000);
					}
					

					setInterval(function () {
						/*
						if(totalItems <= currentIndex) {
							quotes_carousel.trigger('to.owl.carousel', [0, 500, true]);
							
						} else {
							currentIndex++;
						}
						*/

					}, 3000);
					
				}

				$('#<?php echo $id; ?> .owl-item .read_more').click(function(){
					$(this).parents('.quote').toggleClass('text_expanded');
					$(this).toggleText('<?php echo __('Read more','nextcloud'); ?>','<?php echo __('Read less','nextcloud'); ?>');
				});



				$('#<?php echo $id; ?> .owl-item').click(function(){
					var index = $('#<?php echo $id; ?> .owl-item').index(this);
					console.log("Element index: "+index+" was clicked!");
					
					//$(this).addClass('is-active');

					owl_stop_autoplay();
					//quotes_carousel_onChanged_callback(event, index);
					quotes_carousel.trigger('to.owl.carousel', [index, 500, true]);


					$(this).siblings().removeClass('is-active');
					$('#<?php echo $id; ?> .owl-item').eq(index).addClass('is-active');
					
				});


			});
			</script>

		<?php
		}



		
		if ($custom_code) { ?>
			<script>
			jQuery(document).ready(function ($) {
				$('#<?php echo $id; ?>').owlCarousel({
						<?php echo strip_tags($custom_code, '<br>'); ?>
				});
			});
			</script>
		<?php
		}

	  $result = ob_get_clean();
	return $result;
}



add_shortcode('nc_price', 'nc_get_price_func');
function nc_get_price_func($atts){
	$atts = shortcode_atts(array(
		'users' => '100',
		'plan' => 'basic',
		'currency' => '€'
	), $atts);

	$users = $atts['users'];
	$plan = $atts['plan'];
	$currency = $atts['currency'];
	$price_100 = '';
	$price_200 = '';


	if( have_rows('plans_prices', 'option') ):
		while( have_rows('plans_prices', 'option') ) : 
			the_row();

			if( 100 == get_sub_field('users')) {
				switch($plan) {
					case "basic":
						$price_100 = get_sub_field('basic');
						break;
					case "standard":
						$price_100 = get_sub_field('standard');
						break;
					case "premium":
						$price_100 = get_sub_field('premium');
						break;
					case "ultimate":
						$price_100 = get_sub_field('ultimate');
						break;
				}
			}

			if(200 == get_sub_field('users')) {
				switch($plan) {
					case "basic":
						$price_200 = get_sub_field('basic');
						break;
					case "standard":
						$price_200 = get_sub_field('standard');
						break;
					case "premium":
						$price_200 = get_sub_field('premium');
						break;
					case "ultimate":
						$price_200 = get_sub_field('ultimate');
						break;
				}
			}

		endwhile;
	endif;

	if($price_100){
		$price_100_m = $price_100/12;
		$price_100_m_rounded = number_format((float)$price_100_m, 2, '.', '');
	}

	if($price_200){
		$price_200_m = $price_200/12;
		$price_200_m_rounded = number_format((float)$price_200_m, 2, '.', '');
	}
	
	$return = '<span class="nc_price 100_users" data-yearly="'.$price_100.'" data-monthly="'.$price_100_m_rounded.'">'.$price_100.'</span>';
	$return .= '<span class="nc_price 200_users hidden" data-yearly="'.$price_200.'" data-monthly="'.$price_200_m_rounded.'">'.$price_200.'</span>';
	$return .= '<span class="price_request_offer hidden">'.__('Please request an offer.','nextcloud').'</span>';
	$return .= '<div class="price_meta"><span class="currency">'.$currency.' /'.__('user','nextcloud').'</span></div>';
	return $return;
	
}


add_shortcode('nc_test_mailing_list', 'nc_get_odoo_subs');
function nc_get_odoo_subs(){
	global $wp;
	$page_id = get_the_ID();
	$page_url = home_url(add_query_arg(array(), $wp->request));


		[$url, $db, $username, $password] = getOdooConnection();

        //authenticate
        $uid = ODOO_UID;
        $models = RipcordLogger::client("$url/xmlrpc/2/object");
		
		$args = [];

		$kwargs = [
			'limit' => 300, 
			'order' => 'email desc',
			'fields' => ['name', 'opt_out', 'email', 'subscription_list_ids', 'list_ids']
		];
        $subs = $models->execute_kw($db, $uid, $password,
                'mailing.contact', 'search_read', $args, $kwargs
        );

		/*
		$subs = $models->execute_kw($db, $uid, $password,
                'mailing.contact.subscription', 'search_read', $args, $kwargs
        );
		*/
		//$subs = $models->execute_kw($db, $uid, $password, 'mailing.contact', 'fields_get', array(), array('attributes' => array('string', 'help', 'type')));


		$sub_id = 2746334;
		$args = [
			/*['email', 'ilike', 'petermoc'],*/
			['id', '=', $sub_id ]
		];
		$kwargs = [
			'order' => 'email desc', 
			'limit' => 20,
			'fields' => ['name', 'opt_out', 'email', 'subscription_list_ids', 'list_ids']
		];
		$subs = $models->execute_kw($db, $uid, $password, 'mailing.contact', 'search_read', [$args], $kwargs);
		echo "<pre>" . print_r($subs, true) . "</pre>";

		$subscription_list_ids = $subs[0]['subscription_list_ids'];
		//print_r($subscription_list_ids);


		//update opt out status to enabled initially
		//$list_ids, $sub_id = array();

		foreach($subscription_list_ids as $subscription_id){
			//$subscription_id = 3709205;
			$args = [
				['id', '=', $subscription_id]
			];
			$kwargs = [
				'fields' => ['contact_id', 'list_id', 'opt_out']
			];
			$subscription = $models->execute_kw($db, $uid, $password, 'mailing.contact.subscription', 'search_read', [$args], $kwargs);
			echo "<pre>".print_r($subscription, true)."</pre>"; 

			$list_id = $subscription[0]['list_id'][0];
			echo "List ID: ".$list_id;
			$sub_id = $subscription[0]['id'];
			echo "Sub ID: ".$sub_id;
			$opt_out = $subscription[0]['opt_out'];
			echo "Opt out? ".$opt_out;
			

			//$list_ids = $subscription[0]['list_id'][0];
			//$sub_ids = $subscription[0]['id'];
			
			if($opt_out == false /*&& $list_id == 12314*/ ) {
				/*
				$update_sub = $models->execute_kw($db, $uid, $password,
						'mailing.contact.subscription', 'update',
						array(array($sub_id), array(
							'opt_out' => true
						))
				);
				*/
			}
			
		}

}



add_shortcode('nc_mailing_list_manage_subscription','nc_mailing_list_manage_subscription_func');
function nc_mailing_list_manage_subscription_func(){
	

	if(isset($_GET['action'])) {
		global $wp;
		$page_url = home_url(add_query_arg(array(), $wp->request));
		
		
		[$url, $db, $username, $password] = getOdooConnection();

		//authenticate
		$uid = ODOO_UID;
		$models = RipcordLogger::client("$url/xmlrpc/2/object");

		$action = strip_tags($_GET['action']);


			if($action == 'subscribe') {

				if(isset($_GET['name'])) {
					$name = strip_tags($_GET['name']) ? strip_tags($_GET['name']) : '' ;
				}

				if(isset($_GET['sub']) && isset($_GET['subs_ids']) ){
					$sub_id = strip_tags($_GET['sub']);
					//$list_ids = explode(",", strip_tags($_GET['subs_ids']));
					$subs_ids = explode(",", strip_tags($_GET['subs_ids']));

						foreach($subs_ids as $subs_id){
							$subs_id = (int) $subs_id;
							$ok = $models->execute_kw(
								$db, $uid, $password,
								'mailing.subscription', 'write',
								[ [ $subs_id ], ['opt_out' => false] ]
							);		
						}

				} else {
					echo __('No email detected.','nextcloud');
				}

				echo '<div class="nc_success_message_alt">'.__('Thanks! You were subscribed successfully!','nextcloud').'</div>';
	
			} else if($action == 'unsubscribe') {
				//unsubscribe process here

				if(isset($_GET['token'])) {
					$token = strip_tags($_GET['token']) ? strip_tags($_GET['token']) : '' ;
				}
				if(isset($_GET['leadID'])) {
					$leadID = strip_tags($_GET['leadID']) ? strip_tags($_GET['leadID']) : '' ;
				}

				//get lead details unsubscribe token
				$leads = $models->execute_kw(
					$db, $uid, $password,
					'crm.lead', 'search_read',
					[[['id', '=', $leadID]]],
					[
						'fields' => ['id', 'email_from', 'x_studio_unsubscribe_token'],
						'limit'  => 1
					]
				);
				//print_r($leads[0]);
				$lead = $leads[0];
				//echo $lead['id'];
				//echo $lead['x_studio_unsubscribe_token'];


				if(isset($leadID) && isset($token)) {
					if($lead['x_studio_unsubscribe_token'] == $token && $lead['id'] == $leadID) {
						//check successful

						//delete lead
						/*
						$result = $models->execute_kw(
							$db, $uid, $password,
							'crm.lead', 'unlink',
							[
								[$lead['id']]
							]
						);
						*/

						$lead_email = $lead['email_from'];
						//get all leads with this email 
						$found_leads = $models->execute_kw(
							$db, $uid, $password,
							'crm.lead', 'search_read',
							[[['email_from', 'ilike', $lead_email]]],
							[
								'fields' => ['id', 'email_from', 'partner_id']
							]
						);
						
						

						if($found_leads) {
							//found all leads
							//tag lead as "Unsubscribe"
							$tag_id = 123;

							foreach($found_leads as $found_lead){
								$found_leadId = $found_lead['id'];
								$result = $models->execute_kw(
									$db, $uid, $password,
									'crm.lead', 'write',
									[
										[$found_leadId],
										[
											'tag_ids' => [[4, $tag_id]]
										]
									]
								);


							}

						}



						/*
						$result = $models->execute_kw(
							$db, $uid, $password,
							'crm.lead', 'write',
							[
								[$lead['id']],
								[
									'tag_ids' => [[4, $tag_id]]
								]
							]
						);
						*/

						if($result) {
							echo '<div class="nc_success_message_alt">'.__('You were unsubscribed successfully!','nextcloud').'</div>';
						} else {
							echo "Lead not deleted.";
						}
						
					} else {
						echo '<div class="">Token not valid.</div>';
					}
				}
				
				

			}
		
	} else {
		echo __('No action detected.','nextcloud');
	}
}



add_shortcode('title', 'nc_get_title');
function nc_get_title() {
	return get_the_title();
}


add_shortcode('providers_list', 'providers_list_shortcode_funct');
function providers_list_shortcode_funct($atts) {
	ob_start();
	$atts = shortcode_atts(array(
		'type' => 'all',
		'service_type' => '',
		'see_all_link' => '',
		'see_all_label' => '',
		'orderby' => ''
	), $atts);
	$type = $atts['type'];
	$service_type = $atts['service_type'];
	$orderby = $atts['orderby'];
	$see_all_link = $atts['see_all_link'];
	

	if($atts['see_all_label']) {
		$see_all_label = $atts['see_all_label'];
	} else {
		$see_all_label = __('See all providers','nextcloud');
	}
?>
		<?php
		$args = array(
			'post_type' => array('partner_posts'),
			'post_status' => 'publish',
			'posts_per_page' => '10'
		);

		if($type == 'all') {
			$args['meta_query'] = array(
				'relation' => 'OR',

				array(
					'key' => 'services',
					'value' => 'host-home',
					'compare'	=> 'LIKE'
				),
				
				array(
					'key'     => 'services',
					'value'	  => 'host-enterprise',
					'compare' => 'LIKE',
				),
				
			);

			$args['orderby'] = 'rand';
			///$args['order'] = 'DESC';

		} else {
			$args['meta_query'] = array(
				array(
					'key' => 'services',
					'value' => $type,
					'compare' => 'LIKE'
				)
			);
			$args['orderby'] = $orderby;
			$args['order'] = 'DESC';
		}

		if($service_type) {
			$args['meta_query'] = array(
				array(
					'key' => 'service_type',
					'value' => $service_type,
					'compare' => 'LIKE'
				)
			);
			$args['orderby'] = $orderby;
			$args['order'] = 'DESC';
		}


		$providers_query = new WP_Query($args);
		if ($providers_query->have_posts()) { ?>
		<div id="providers_carousel" class="owl-carousel providers_carousel">
		<?php
				while ($providers_query->have_posts()) {
					$providers_query->the_post();
					
					//echo get_the_ID();
					?>
					<div class="item provider">
						<?php 
						$post_id =  get_the_ID();
						
						$name = get_the_title();
						$level = get_field('partner_level');
						$servs = get_field('services');
						$country = get_field('region');

						if(is_array($country)) {
							$country = implode(",", $country);
						}

						$logo = get_field('logo');
						$text = get_field('text');
						$service_text = get_field('service_text');
						$website_link = get_field('website_link');
						//$provider_type = get_field('provider_type');

									echo '<div class="partner-col" id="partner-'.$post_id.'" data-type="' . $level;
									foreach ($servs as $s) {
										echo ' ' . $s;
									}
									echo '" data-country="' . $country . '">';

									if (!empty($website_link)) {
										echo '<a href="' . $website_link . '" target="_blank" title="'.$name.'">';
									}

									echo '<div class="partner-box">';

									if($level){
										echo '<div class="certificate-line ' . $level . '">';
										echo __($level,'nextcloud');
										echo '</div>';
									}

									echo '<div class="partner-logo">';
									if (!empty($logo)) {
										echo '<img src="' . $logo["url"] . '" alt="' . $logo["alt"] . '" title="' .$name. '" />';
									}
									echo '</div>';

									echo '<div class="partner-text">';
									echo '<h4>' . $name . '</h4>';
									if (!empty($text)) {
										echo "<p class='desc'>";
										echo strip_tags($text);
										echo "</p>";
									}
									echo '</div>';
									

									if($type == 'all') {
										echo "<div class='provider_type'>";
										if(in_array("host-home", $servs)) {
											echo "<span>".__('Home users','nextcloud')."</span>";
										}
										if(in_array("host-enterprise", $servs)) {
											echo "<span>".__('Enterprises','nextcloud')."</span>";
										}
										echo "</div>";
									}

									
									echo '</div>';
									if (!empty($website_link)) {
										echo '</a>';
									}

									echo '</div>';
						?>
					</div>


					<?php

				} // endwhile
		

				if (!empty($see_all_link) && $see_all_link != '' ) {
				echo '<div class="item provider">';
				echo '<div class="partner-col see_all_providers" id="" data-type="">';

				echo '<a href="' . $see_all_link . '" target="_blank" title="'.$see_all_label.'">';
				
				echo '<div class="partner-box">';
				echo '<div class="partner-text">';
				echo '<h4>' .$see_all_label. '</h4>';
				echo '</div>';
				echo '</div>';

				echo '</a>';

				echo '</div>';
				echo '</div>';
				}
		
		?>
		</div>
		<?php
		} // endif
		?>
		<script>
			jQuery(document).ready(function ($) {
					$('.providers_carousel').owlCarousel({
						loop:false,
						autoplay: false,
						margin:22,
						dots: false,
						nav:true,
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
							1000:{
								items:5
							}
						}
					});
			});
		</script>
<?php 
	$result = ob_get_clean();
	return $result;
}






// shortcode text with icon
function nc_text_with_icon_element_custom() {
	// Title
	vc_map(
		array(
			'name' => __('Text block with icon'),
			'base' => 'nc_text_with_icon',
			'category' => __('Content', 'nextcloud'),
			'params' => array(
				
				array(
					"type" => "iconpicker",
					"holder" => "div",
					"class" => "",
					"heading" => __("Icon", "nextcloud"),
					"param_name" => "icon",
					"value" => __("", "nextcloud"),
				),


				array(
					"type" => "attach_image",
					"holder" => "img",
					"class" => "",
					"heading" => __("Image Icon", "nextcloud"),
					"param_name" => "image_icon",
					"value" => __("", "nextcloud"),
				),

				array(
					"type" => "textarea_html",
					"holder" => "div",
					"heading" => esc_html__("Description", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "content",
					"value" => "",
				),

				array(
					"type" => "textfield",
					"heading" => esc_html__("Custom CSS Classes", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "css_classes",
					"value" => "",
				),

			)
		)
	);
}
add_action('vc_before_init', 'nc_text_with_icon_element_custom');
function nc_text_with_icon_function($atts, $content) {
	$attributes = shortcode_atts(
		array(
			'icon' => '',
			'image_icon' => '',
			'content' => '',
			'css_classes' => ''
		), $atts);
	$content = wpb_js_remove_wpautop($content, true);

	if ($attributes['image_icon']) {
		$icon = wp_get_attachment_image($attributes['image_icon'], 'medium');
	} elseif ($attributes['icon']) {
		$icon = '<i class="'.$attributes['icon'].'"></i>';
	} else {
		$icon = "";
	}
	
	$css_classes = "";
	if(isset($attributes['css_classes'])){
		$css_classes = $attributes['css_classes'];
	}

	$return = '<div class="wpb_text_column wpb_content_element nc_text_with_icon '.$css_classes.'"><div class="wpb_wrapper">';
	$return .= '<div class="nc_text_icon_container">';
	$return .= '<div class="icon">'.$icon.'</div>';

	if($content) {
		$return .= '<div class="nc_text">';
		$return .= $content;
		$return .= '</div>';
	}

	$return .= '</div></div></div>';

	return $return;
}
add_shortcode('nc_text_with_icon', 'nc_text_with_icon_function');





//Nextcloud Animation repeater element
add_action('vc_before_init', 'wpb_nc_animation_funct');
function wpb_nc_animation_funct() {
	vc_map(
		  array(
		  	"name" => __("Nextcloud Animation", "nextcloud"), // Element name
		  	"base" => "nc_animation", // Element shortcode
		  	"class" => "nc_animation",
		  	"category" => __('Content', 'nextcloud'),
		  	'params' => array(

                array(
					"type" => "textfield",
					"heading" => esc_html__("ID", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "id",
					"value" => "",
				),

				array(
					"type" => "textfield",
					"heading" => esc_html__("Custom CSS classes", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "custom_css_classes",
					"value" => "",
				),

				array(
					"type" => "textarea",
					"heading" => esc_html__("Custom CSS styles", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "animation_custom_css_style",
					"value" => "",
				),

                array(
                    'type' => 'param_group',
                    'param_name' => 'images_repeater_items',
                    'params' => array(
                        
                        array(
                            "type" => "attach_image",
                            "holder" => "img",
                            "class" => "",
                            "heading" => __("Image", "nextcloud"),
                            "param_name" => "image",
                            "value" => __("", "nextcloud"),
                        ),

						array(
                            "type" => "textfield",
                            "holder" => "div",
                            "class" => "",
                            "admin_label" => false,
                            "heading" => __("Video link", "nextcloud"),
                            "param_name" => "video_link",
                            "value" => __("", "nextcloud"),
                        ),


						array(
                            "type" => "attach_image",
                            "holder" => "img",
                            "class" => "",
                            "heading" => __("Video poster", "nextcloud"),
                            "param_name" => "poster_image",
                            "value" => __("", "nextcloud"),
                        ),


                        array(
                            "type" => "textfield",
                            "holder" => "div",
                            "class" => "",
                            "admin_label" => true,
                            "heading" => __("Custom CSS classes", "nextcloud"),
                            "param_name" => "custom_css_classes",
                            "value" => __("", "nextcloud"),
                        ),

                        array(
                            "type" => "textarea",
                            "heading" => esc_html__("Custom CSS styles", "nextcloud"),
                            "description" => esc_html__("", "nextcloud"),
                            "param_name" => "custom_css_style",
                            "value" => "",
						),
						
						array(
                            "type" => "textarea_raw_html",
                            "heading" => esc_html__("Custom SVG code", "nextcloud"),
                            "description" => esc_html__("", "nextcloud"),
                            "param_name" => "custom_svg_code",
                            "value" => "",
						),
						
						array(
                            "type" => "checkbox",
                            "heading" => esc_html__("Clickable", "nextcloud"),
                            "description" => esc_html__("", "nextcloud"),
                            "param_name" => "clickable_image",
                            "value" => "",
						),

						array(
                            "type" => "vc_link",
                            "heading" => esc_html__("Custom link", "nextcloud"),
                            "description" => esc_html__("", "nextcloud"),
                            "param_name" => "custom_link",
                            "value" => "",
						)
                    

                    )
                ),



				

			)
		  )
	  );
}


add_shortcode('nc_animation', 'nc_animation_shortcode_funct');
function nc_animation_shortcode_funct($atts) {
	ob_start();

    $a = shortcode_atts(array(
		'id' => '',
		'custom_css_classes' => '',
		'animation_custom_css_style' => '',
		'images_repeater_items' => '',
	), $atts);


    $id = $a['id']; 
    $custom_css_classes_anim = $a['custom_css_classes'];
	$animation_custom_css_style = $a['animation_custom_css_style']; 

    if(function_exists('vc_param_group_parse_atts')){
        $items = vc_param_group_parse_atts($atts['images_repeater_items']);
    }

	wp_enqueue_script( 'vc_waypoints' );
    wp_enqueue_style( 'vc_animate-css' );
	
    if($items) {
        ?>
        <div id="<?php if(isset($id)) echo $id; ?>" class="nc_animation <?php if(isset($custom_css_classes_anim)) echo $custom_css_classes_anim; ?>" style="<?php if(isset($animation_custom_css_style)) 
		echo str_replace("<br>", "", $animation_custom_css_style); ?>">
        <?php
		$i = 1; 
        foreach ($items as $item) {

			if(isset($item['image'])) {
				$image = $item['image'];
			}
            
			if(isset($item['custom_css_classes'])) {
				$custom_css_classes = $item['custom_css_classes'];
			}

			if(isset($item['custom_css_style'])) {
				$custom_css_style = $item['custom_css_style'];
			}
			
			if(isset($item['video_link'])) {
				$video_link = $item['video_link'];
			}
			if(isset($item['poster_image'])) {
				$poster_image = $item['poster_image'];
			}

			if(isset($item['custom_link'])) {
				$link = vc_build_link($item['custom_link']);

			}
    	?>
            <div class="anim_element <?php if(isset($custom_css_classes)) echo $custom_css_classes; ?>" style="<?php if(isset($custom_css_style)) echo $custom_css_style; ?>
				z-index: <?php echo $i; ?>">
			<?php 
			if(isset($item['image'])) {

				if(isset($item['clickable_image'])) {
					echo '<a href="'.wp_get_attachment_image_url($item['image'], 'full').'" class="popup-screenshot-gal">';
				}

				if(isset($item['custom_link'])) {
					echo '<a href="'.$link['url'].'" target="'.$link['target'].'" class="">';
				}

			?>
				<img src="<?php 
					echo wp_get_attachment_image_url($item['image'], 'full');
				?>" class="" style="<?php 
				if(isset($custom_css_editor)) {
					echo $custom_css_editor;
				}
				?>">
			<?php
				if(isset($item['clickable_image']) || isset($item['custom_link'] )) {
					echo '</a>';
				}

			}
			?>

			<?php 
			if(isset($item['video_link'])) {
			?>
				<video id="" tabindex="-1" width="" height="" class="" loop autoplay="true" muted="true" playsinline="true" poster="<?php 
				if(isset($poster_image))
				echo wp_get_attachment_image_url($poster_image, 'full'); ?>" style="">
                <source src="<?php echo $video_link; ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
			<?php
			}
			?>

			<?php 
			if(isset($item['custom_svg_code'])) {
				echo rawurldecode( base64_decode( $item['custom_svg_code'] ) );
			}
			?>

			</div>
			
    <?php
		$i++;
        }
        ?>
        </div>
    <?php
    }

	$result = ob_get_clean();
	return $result;
}







// shortcode text with icon
function nc_custom_button_element() {
	// Title
	vc_map(
		array(
			'name' => __('Custom button'),
			'base' => 'nc_custom_button',
			'category' => __('Content', 'nextcloud'),
			'params' => array(
				
				array(
					"type" => "iconpicker",
					"holder" => "div",
					"class" => "",
					"heading" => __("Icon", "nextcloud"),
					"param_name" => "icon",
					"value" => __("", "nextcloud"),
				),

				array(
					"type" => "attach_image",
					"holder" => "img",
					"class" => "",
					"heading" => __("Image Icon", "nextcloud"),
					"param_name" => "image_icon",
					"value" => __("", "nextcloud"),
				),

				array(
					"type" => "textfield",
					"holder" => "div",
					"heading" => esc_html__("Button label", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "btn_label",
					"value" => "",
				),

				array(
					"type" => "vc_link",
					"holder" => "div",
					"heading" => esc_html__("Main link", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "main_link",
					"value" => "",
				),

				array(
					"type" => "textfield",
					"holder" => "div",
					"heading" => esc_html__("Custom link", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "main_custom_url",
					"value" => "",
				),

				array(
                    'type' => 'param_group',
                    'param_name' => 'repeater_links',
					'heading' => esc_html__('Additional links', "nextcloud"),
                    'params' => array(
						

						array(
							"type" => "textfield",
							"holder" => "div",
							"heading" => esc_html__("Link label", "nextcloud"),
							"description" => esc_html__("", "nextcloud"),
							"param_name" => "link_label",
							"value" => "",
						),
                        array(
                            "type" => "vc_link",
                            "holder" => "div",
                            "class" => "",
                            "heading" => __("Link", "nextcloud"),
                            "param_name" => "link",
                            "value" => __("", "nextcloud"),
                        ),
						array(
							"type" => "textfield",
							"holder" => "div",
							"heading" => esc_html__("Custom link", "nextcloud"),
							"description" => esc_html__("", "nextcloud"),
							"param_name" => "custom_link",
							"value" => "",
						),

					)
				),

				array(
					"type" => "textfield",
					"heading" => esc_html__("Custom CSS Classes", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "css_classes",
					"value" => "",
				),

			)
		)
	);
}
add_action('vc_before_init', 'nc_custom_button_element');
function nc_custom_button_function($atts, $content) {
	$attributes = shortcode_atts(
		array(
			'icon' => '',
			'image_icon' => '',
			'btn_label' => '',
			'main_link' => '',
			'main_custom_url' => '',
			'repeater_links' => '',
			'css_classes' => ''
		), $atts);
	
	if(function_exists('vc_param_group_parse_atts')){
		$items = vc_param_group_parse_atts($attributes['repeater_links']);
	}

	$version = '';
	$url_base = '';
	if (get_field('clients_download_version', 'option') ) {
		$version = get_global_option('clients_download_version');
	}
	if (get_global_option('clients_download_url_base')) {
		$url_base = get_global_option('clients_download_url_base');
	} else {
		$url_base = 'https://github.com/nextcloud-releases/desktop/releases/download/';
	}
	
	if ($attributes['image_icon']) {
		$icon = wp_get_attachment_image($attributes['image_icon'], 'small', '', array('class' => 'btn_icon'));
	} elseif ($attributes['icon']) {
		$icon = '<i class="vc_btn3-icon '.$attributes['icon'].'"></i>';
	} else {
		$icon = "";
	}

	$btn_label = '';
	if(isset($attributes['btn_label'])){
		$btn_label = $attributes['btn_label'];
	}

	$target = "_self";
	if (isset($attributes['main_link'])) {
		$main_link = vc_build_link($attributes['main_link']);
		$main_link_url = $main_link['url'];
		$target = $main_link['target'];
		$title = $main_link['title'];
	}

	if(isset($attributes['main_custom_url'])) {
		switch($attributes['main_custom_url']){
			case "get_clients_link_win":
				$main_link_url = $url_base."v".$version."/Nextcloud-".$version."-x64.msi";
				break;
			case "get_clients_link_mac":
				$main_link_url = $url_base."v".$version."/Nextcloud-".$version.".pkg";
				break;
			case "get_clients_link_linux":
				$main_link_url = $url_base."v".$version."/Nextcloud-".$version."-x86_64.AppImage";
				break;
			default:
				$main_link = vc_build_link($attributes['main_link']);
				$main_link_url = $main_link['url'];
				break;
		}
	}

	
	$css_classes = "";
	if(isset($attributes['css_classes'])){
		$css_classes = $attributes['css_classes'];
	}

	$return = '';

	if($items && isset($items[0]['custom_link']) ) {
		$return .= '<div class="btn-dropdown-container">';
	}

	$return .= '<div class="nc_custom_btn vc_btn3-container '.$css_classes.' vc_btn3-inline vc_do_btn"><a class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-rounded vc_btn3-style-modern vc_btn3-icon-left vc_btn3-color-grey" href="'.$main_link_url.'" title="" target="'.$target.'">'.$icon.' '.$btn_label.'</a>';
	
	if($items && isset($items[0]['custom_link']) ) {
			$return .= '<i class="open_dropdown fas fa-angle-down"></i>';
	}

	$return .= '</div>';

	if($items) {
		$return .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
		foreach ($items as $item) {
			if(isset($item['custom_link'])) {

				switch($item['custom_link']){
						case "get_clients_link_win":
							$item_main_link_url = $url_base."v".$version."/Nextcloud-".$version."-x64.msi";
							break;
						case "get_clients_link_mac":
							$item_main_link_url = $url_base."v".$version."/Nextcloud-".$version.".pkg";
							break;
						case "get_clients_link_linux":
							$item_main_link_url = $url_base."v".$version."/Nextcloud-".$version."-x86_64.AppImage";
							break;
						default:
							$item_main_link_url = "";
							break;
				}

				$return .= '<a class="dropdown-item" target="_blank" href="'.$item_main_link_url.'">'.$item['link_label'].'</a>';
			} else if (isset($item['link'])){
				$link = vc_build_link($item['link']);
				$return .= '<a class="dropdown-item" target="'.$link['target'].'" href="'.$link['url'].'">'.$item['link_label'].'</a>';
			}
		}

		$return .= '</div>';
	}

	if($items && isset($items[0]['custom_link']) ) {
		$return .= '</div>'; 
	}

	return $return;
}
add_shortcode('nc_custom_button', 'nc_custom_button_function');



// Custom Webinar Title Element for WPBakery
add_action('vc_before_init', 'webinar_element_intialize');
function webinar_element_intialize() {
    vc_map(array(
        'name' => 'Webinar Title',
        'base' => 'webinar_element',
        'category' => 'Custom Elements',
        'params' => array(
            array(
                'type' => 'textfield',
                'holder' => 'h1',
                'class' => 'webinar-title',
                'heading' => 'Webinar Title',
                'param_name' => 'title',
                'description' => 'Enter the webinar title',
                'admin_label' => true,
                'required' => true // Required field
            ),
            array(
                'type' => 'textfield',
                'holder' => 'h2',
                'class' => 'webinar-subtitle',
                'heading' => 'Webinar Subtitle',
                'param_name' => 'subtitle',
                'description' => 'Enter the webinar subtitle (optional)'
                // No required parameter - field is optional
            ),
            array(
                'type' => 'dropdown',
                'heading' => 'Webinar Type',
                'param_name' => 'webinar_type',
                'value' => array(
                    'Live Webinar' => 'live-webinar',
                    'Recording' => 'webinar-recording',
                ),
				//'std' => 'live-webinar', // Your default value
                'required' => true // Required field
            )
        )
    ));
}

// Shortcode Callback
add_shortcode('webinar_element', 'webinar_element_shortcode');
function webinar_element_shortcode($atts) {
    $a = shortcode_atts(array(
        'title' => '',
        'subtitle' => '',
        'webinar_type' => 'live-webinar',
    ), $atts);

	$output = '';

	$title = $a['title'];
	$subtitle = $a['subtitle'];
	$webinar_type = $a['webinar_type'];

    $output .= '<div class="webinar-element webinar-title ' . esc_attr($webinar_type) . '">';
    $output .= '<h1 class="webinar-title">' . esc_html($title) . '</h1>';
    
    // Only output subtitle if it's not empty
    if (!empty($subtitle)) {
        $output .= '<h2 class="webinar-subtitle">' . esc_html($subtitle) . '</h2>';
    }
    
    $output .= '</div>';
    return $output;
}


// Custom Speakers Element for WPBakery
add_action('vc_before_init', 'speakers_element_initialize');
function speakers_element_initialize() {
    vc_map(array(
        'name' => 'Speakers List',
        'base' => 'speakers_element',
        'category' => 'Custom Elements',
        'params' => array(
            array(
                'type' => 'param_group',
                'heading' => 'Speakers',
                'param_name' => 'speakers',
                'params' => array(
                    array(
                        'type' => 'attach_image',
                        'heading' => 'Photo',
                        'param_name' => 'photo',
                        'required' => true
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => 'Name',
                        'param_name' => 'name',
                        'admin_label' => true,
                        'required' => true
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => 'Role',
                        'param_name' => 'role',
                        'required' => true
					),
					array(
                        'type' => 'textfield',
                        'heading' => 'Email',
                        'param_name' => 'email',
                        'required' => false
                    )
                )
            )
        )
    ));
}

// Shortcode Callback
add_shortcode('speakers_element', 'speakers_element_shortcode');
function speakers_element_shortcode($atts) {
    $atts = vc_map_get_attributes('speakers_element', $atts);
    $speakers = vc_param_group_parse_atts($atts['speakers']);
    
    $output = '<div class="webinar-speakers-list">';
    
    if (!empty($speakers)) {
        foreach ($speakers as $speaker) {
            if (!empty($speaker['photo']) && !empty($speaker['name']) && !empty($speaker['role'])) {
                $output .= '<div class="speaker-item">';
                
                // Photo
                $image = wp_get_attachment_image_src($speaker['photo'], 'full');
                if ($image) {
                    $output .= '<div class="speaker-photo">';
                    $output .= '<img src="' . esc_url($image[0]) . '" alt="' . esc_attr($speaker['name']) . '">';
                    $output .= '</div>';
                }
                
                // Name and Role
                $output .= '<div class="speaker-info">';
                $output .= '<h3 class="speaker-name">' . esc_html($speaker['name']) . '</h3>';
                $output .= '<div class="speaker-role">' . esc_html($speaker['role']) . '</div>';
				if(isset($speaker['email'])) {
					$output .= '<div class="speaker-email"><a href="mailto:'.esc_html($speaker['email']).'">'.esc_html($speaker['email']).'</a></div>';
				}
                $output .= '</div>'; // Close speaker-info
                
                $output .= '</div>'; // Close speaker-item
            }
        }
    }
    
    $output .= '</div>'; // Close speakers-list
    
    return $output;
}

//add dataguard shortcode
function nc_dataguard_script($atts) {
	$a = shortcode_atts(array(
		'lang' => 'en'
	), $atts);

	$lang = $a['lang'];
	ob_start(); 
	if($lang=="en") {
	?>
    	<span id="dg-root" data-option="3" data-text="Send data subject request" data-id="Bc7oDAHRi7YWg7n1xmLKd51B" data-lang="<?php echo $lang; ?>"></span><script async defer src="https://dataguard.azureedge.net/website/dsr/dist/dsr.min.js"></script>
		<a href="#no_scroll" onclick="showPopup()" class="open-dg-iframe no_scroll"><?php echo __('here','nextcloud'); ?></a>
	<?php } else if ($lang == "de") { ?>
		<span id="dg-root" data-option="3" data-text="Betroffenenanfrage stellen" data-id="Bc7oDAHRi7YWg7n1xmLKd51B" data-lang="<?php echo $lang; ?>"></span><script async defer src="https://dataguard.azureedge.net/website/dsr/dist/dsr.min.js"></script>
		<a href="#no_scroll" onclick="showPopup()" class="open-dg-iframe no_scroll"><?php echo __('here','nextcloud'); ?></a>
    <?php
	}
	?>
	<style>
			#dg-root {
				display: none;
			}
			iframe#dg-btn {
				display: none; 
			}
		</style>
		<script>
			function showPopup() {
				var dg_popup = document.getElementById("dg-popup");
				if (window.getComputedStyle(dg_popup).display === "none") {
					document.getElementById('dg-root').style.display = "inline-block";
					document.getElementById('dg-popup').style.display = "block";
				} else {
					document.getElementById('dg-root').style.display = "none";
				}
				
			}
		</script>

	<?php
	return ob_get_clean();
}
add_shortcode('dataguard', 'nc_dataguard_script');


//add dataguard shortcode
function nc_general_option_func($atts) {
	$a = shortcode_atts(array(
		'field' => ''
	), $atts);

	$my_current_lang = apply_filters( 'wpml_current_language', null );
	$return = "";
	//$timezone = new DateTimeZone('Europe/Berlin');

	if(isset($a['field'])) {
		if($a['field'] == "server_latest_release_date"){
			$date = DateTime::createFromFormat("d/m/Y", get_global_option($a['field']));
			//$date = date_create(get_global_option($a['field']));

			switch($my_current_lang){
				case "en":
					//$return .= $date->format("F j, Y");
					$return .= formatLanguage($date, 'F j, Y', $my_current_lang);
					break;
				case "de":
					//$return .= $date->format("j. F Y");
					$return .= formatLanguage($date, 'j. F Y', $my_current_lang);
					break;
				default:
					//$return .= $date->format("j F Y");
					$return .= formatLanguage($date, 'j F Y', $my_current_lang);
					break; 
			}
			
			
				
		} else {
			$return .= get_global_option($a['field']);
		}
	}
	
	return $return;
}
add_shortcode('nc_general_option', 'nc_general_option_func');



// Custom Agenda Element for WPBakery
add_action('vc_before_init', 'agenda_element_initialize');
function agenda_element_initialize() {
    vc_map(array(
        'name' => 'Nextcloud event agenda',
        'base' => 'nc_event_agenda',
        'category' => 'Custom Elements',
        'params' => array(

			array(
            	'type' => 'textfield',
                'heading' => 'ID',
                'param_name' => 'id',
                'admin_label' => true,
            ),
			
			array(
            	'type' => 'textfield',
                'heading' => 'Table name',
                'param_name' => 'name',
                'admin_label' => true,
            ),

            array(
                'type' => 'param_group',
                'heading' => 'Table rows',
                'param_name' => 'table_rows',
                'params' => array(
					array(
                        'type' => 'textfield',
                        'heading' => 'Time',
                        'param_name' => 'time',
                        'required' => true,
						'admin_label' => true
                    ),

					array(
                        'type' => 'checkbox',
                        'heading' => 'Wide row?',
                        'param_name' => 'colspan_2',
						"description" => __("(colspan=2 for the table row). Uncheck this to show the speaker/moderator, which is shown as second column for the row.", "nextcloud" ),
                    ),

					array(
                        'type' => 'dropdown',
                        'heading' => 'Type',
                        'param_name' => 'type',
						'value' => array(
							'Select type..' => '',
							'Line separator' => 'line-separator',
							'Keynote' => 'keynote',
							'Lightning talk' => 'lightning_talk',
							'Panel session' => 'panel_session',
							'Roundtable' => 'roundtable',
							'Breakout tracks' => 'breakout_tracks',
							'Successive sessions' => 'successive_sessions'
						),
                    ),

					array(
						'type' => 'checkbox',
						'heading' => 'Hide automatic track numbers',
						'param_name' => 'hide_automatic_track_numbers',
						'dependency' => array(
							'element' => 'type',
							'value'   => 'breakout_tracks',
						),
						'value' => array(
							'Yes' => 'true',
						)
					),

                    array(
                        'type' => 'attach_image',
                        'heading' => 'Image',
                        'param_name' => 'image',
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => 'Title',
                        'param_name' => 'title',
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'textarea',
                        'heading' => 'Description',
                        'param_name' => 'description'
					),
					

					array(
                        'type' => 'textarea_raw_html',
                        'heading' => 'Extra description',
                        'param_name' => 'extra_description',
						"description" => __("Extra description which accepts html. It will go under the row description.", "nextcloud" ),
					),

					array(
						'type' => 'param_group',
						'heading' => 'Inner tracks',
						'param_name' => 'inner_tracks',
						'params' => array(
							array(
								'type' => 'textfield',
								'heading' => 'Track title',
								'param_name' => 'track_title',
								'admin_label' => true,
							),

							array(
								'type' => 'textfield',
								'heading' => 'Track description',
								'param_name' => 'track_description',
								'admin_label' => false,
							),

							array(
								'type' => 'param_group',
								'heading' => 'Track sessions',
								'param_name' => 'track_sessions',
								'params' => array(
									array(
										'type' => 'textfield',
										'heading' => 'Session title',
										'param_name' => 'session_title',
										'admin_label' => true,
									),
									array(
										'type' => 'textarea',
										'heading' => 'Description',
										'param_name' => 'session_description'
									),
									array(
										'type' => 'textarea',
										'heading' => 'Speaker',
										'param_name' => 'session_speaker'
									),
								)
							)

						)
					),

					array(
                        'type' => 'attach_images',
                        'heading' => 'Speaker image',
                        'param_name' => 'speaker_images',
                    ),

					array(
						'type' => 'textfield',
						'heading' => 'Speaker name',
						'param_name' => 'speaker_name'
					),

					array(
						'type' => 'textarea',
						'heading' => 'Speaker description',
						'param_name' => 'speaker_description'
					),

					
					array(
						'type' => 'textfield',
						'heading' => 'Custom speaker type',
						'param_name' => 'custom_speaker_type'
					),
					

					array(
						'type' => 'param_group',
						'heading' => 'Other participants',
						'param_name' => 'participants',
						'params' => array(

							array(
								'type' => 'attach_image',
								'heading' => 'Image',
								'param_name' => 'image',
							),

							array(
								'type' => 'textfield',
								'heading' => 'Name',
								'param_name' => 'name',
								'admin_label' => true,
							),

							array(
								'type' => 'textarea',
								'heading' => 'Position',
								'param_name' => 'position'
							),

							array(
								'type' => 'textfield',
								'heading' => 'Speaker type',
								'param_name' => 'speaker_type'
							),

						)
					),



					
                )
            ),
			
			array(
                'type' => 'checkbox',
                'heading' => 'Compact table',
                'param_name' => 'compact_table',
				"description" => __("", "nextcloud" ),
				'value' => array(
					'Yes' => 'true',
				)
            ),

			array(
                'type' => 'checkbox',
                'heading' => 'Table with sidetrack',
                'param_name' => 'sidetrack',
				"description" => __("", "nextcloud" ),
				'value' => array(
					'Yes' => 'true',
				)
            ),

			array(
                'type' => 'textfield',
                'heading' => 'ID of the side track table',
                'param_name' => 'sidetrack_table_id',
				"description" => __("", "nextcloud" ),
				'dependency' => array(
					'element' => 'sidetrack',
					'value'   => 'true',
				)
            ),

			array(
                'type' => 'textfield',
                'heading' => 'Side track name',
                'param_name' => 'sidetrack_name',
				"description" => __("", "nextcloud" ),
				'dependency' => array(
					'element' => 'sidetrack',
					'value'   => 'true',
				)
            ),


			array(
                'type' => 'checkbox',
                'heading' => 'Extra table?',
                'param_name' => 'extra_table',
				"description" => __("", "nextcloud" ),
				'value' => array(
					'Yes' => 'true',
				)
            ),

			array(
                'type' => 'textfield',
                'heading' => 'ID of the extra table',
                'param_name' => 'extra_table_id',
				"description" => __("", "nextcloud" ),
				'dependency' => array(
					'element' => 'extra_table',
					'value'   => 'true',
				)
            ),

			array(
                'type' => 'textfield',
                'heading' => 'Extra table name',
                'param_name' => 'extra_table_name',
				"description" => __("", "nextcloud" ),
				'dependency' => array(
					'element' => 'extra_table',
					'value'   => 'true',
				)
            ),


			array(
            	'type' => 'textfield',
                'heading' => 'Custom CSS',
                'param_name' => 'custom_css',
                'admin_label' => false,
            )
        )
    ));
}

// Shortcode Callback
add_shortcode('nc_event_agenda', 'nc_event_agenda_shortcode');
function nc_event_agenda_shortcode($atts) {
	$atts = shortcode_atts([
		'id' => '',
		'name' => '',
		'table_rows' => '',
		'compact_table' => '',
		'sidetrack' => '',
		'sidetrack_table_id' => '',
		'sidetrack_name' => '',
		'extra_table' => '',
		'extra_table_id' => '',
		'extra_table_name' => '',
		'custom_css' => ''
	], $atts);

    //$atts = vc_map_get_attributes('table_rows', $atts);
    //$table_rows = vc_param_group_parse_atts($atts['table_rows']);
	$table_rows = !empty($atts['table_rows'])
    ? vc_param_group_parse_atts($atts['table_rows'])
    : [];
	
	$custom_css = "";
	if(isset($atts['custom_css'])) {
		$custom_css = $atts['custom_css'];
	}

	$id = "";
	if(isset($atts['id'])) {
		$id = $atts['id'];
	}

	ob_start(); 
	?>
	<table id="<?php echo $id; ?>" class="nc_event_agenda <?php echo $custom_css; 
	if(!empty($atts['compact_table'])) {
		echo " compact_table ";
	}
	?>">

	<thead>
	<tr>
	<td class="time"><?php echo __('Time','nextcloud'); ?></td>

	<?php if(!empty($atts['compact_table'])) { ?>
		<td colspan="2" class="topic_speaker <?php if(!empty($atts['sidetrack'])) { echo "with_sidetrack_switcher"; } ?>"><?php echo __('Session and speaker','nextcloud'); ?>
	
			<?php if(!empty($atts['sidetrack'])) { ?>
				<select name="table_switch" id="" class="table_switch">
					<option value="main_stage"><?php 
					if(isset($atts['name']) && $atts['name'] != '') {
						echo $atts['name'];
					} else {
						echo __('Main stage', 'nextcloud');
					}
					?></option>
					<option value="<?php if(isset($atts['sidetrack_table_id'])) echo $atts['sidetrack_table_id']; ?>">
						<?php 
						if(isset($atts['sidetrack_name']) && $atts['sidetrack_name'] != '') {
							echo $atts['sidetrack_name'];
						} else {
							echo __('Side track', 'nextcloud');
						}
						 ?>
					</option>

					<?php if(!empty($atts['extra_table'])) { ?>
						<option value="<?php if(isset($atts['extra_table_id'])) echo $atts['extra_table_id']; ?>"><?php 
						if(isset($atts['extra_table_name']) && $atts['extra_table_name'] != '') {
							echo $atts['extra_table_name'];
						} else {
							echo __('Extra table', 'nextcloud');
						}
						
						?></option>
					<?php } ?>
					
				</select>
			<?php } ?>
		</td>
	<?php } else { ?>
		<td class="topic"><?php echo __('Session','nextcloud'); ?></td>
		<td class="speaker"><?php echo __('Speaker','nextcloud'); ?></td>
	<?php } ?>
	
	</tr>
	</thead>


	<tbody>
	<?php
    if (!empty($table_rows)) {
        foreach ($table_rows as $table_row) {

			$table_row_type = "";
			if(isset($table_row['type'])) {
				$table_row_type = $table_row['type'];
			}

			echo "<tr>";

			echo "<td>";
            if (!empty($table_row['time'])) {
                echo $table_row['time'];
            }
			echo "</td>";


			$colspan = 1;
			if(!empty($table_row['colspan_2']) ) {
				$colspan = 2;
			}
			if(!empty($atts['compact_table'])) {
				$colspan = 2;	
			}

			echo "<td colspan='".$colspan."'>";

			if ( !empty($table_row_type) && $table_row_type != 'successive_sessions' ) {
                echo "<span class='event_row_type ".$table_row_type."'>".str_replace('_', ' ', $table_row_type)."</span>";
            }

			
            if (!empty($table_row['title'])) {
                echo "<p class='table_row_title'>".$table_row['title'];

				if (!empty($table_row['inner_tracks'])) {
					$inner_tracks = $table_row['inner_tracks'];
					$inner_tracks = vc_param_group_parse_atts($table_row['inner_tracks']);
					$num_tracks = count($inner_tracks);
					if($num_tracks > 3) {
						echo '<span class="scroll_help_text">'.__('You can scroll horizontally to see more items','nextcloud').'</span>';
					}
				}

				echo "</p>";
            }

	
			if(isset($table_row['image'])) {
				echo '<div class="agenda_row_desc_container">';
				echo '<div class="agenda_row_feat_image"><img src="'.wp_get_attachment_image_url($table_row['image'], 'full').'" class="" /></div>';
			}

			echo '<div class="agenda_row_description">';
			if (!empty($table_row['description'])) {
                echo '<p>'.$table_row['description'].'</p>';
            }

			if (!empty($table_row['extra_description'])) {
				$extra_description = nc_decode_raw_html_param($table_row['extra_description']);
				echo '<div class="extra_description">' . $extra_description . '</div>';
			}

			echo '</div>'; //.agenda_row_description

			if(isset($table_row['image'])) {
				echo '</div>'; //.agenda_row_desc_container
			}


			if (!empty($table_row['inner_tracks'])) {
				$inner_tracks = $table_row['inner_tracks'];
				//print_r($inner_tracks);
				$inner_tracks = vc_param_group_parse_atts($table_row['inner_tracks']);
				$num_tracks = count($inner_tracks);
				//echo $num_tracks; 
				$col_class = ""; 
				
				$col_class = "4";
				if($num_tracks == 2) {
					$col_class = "6";
				}
				
				$scroll_class = "";
				if($num_tracks > 3) {
					$scroll_class = "scroll";

					echo '<div class="scroll-buttons">
					<i id="scroll-left" class="fas fa-angle-left"></i>
					<i id="scroll-right" class="fas fa-angle-right"></i>
					</div>';
				}
				

				if( $table_row_type == 'successive_sessions') {
					echo '<div class="row inner_tracks_row '.$scroll_class.'">';
				} else {
					echo '<div class="inner_tracks_row '.$scroll_class.'">';
				}
				

				foreach($inner_tracks as $inner_track_key => $inner_track) {
					$inner_track_key_next = $inner_track_key + 1; 


					if(isset($inner_track['track_title']) || ( $table_row_type == 'successive_sessions' ) ){
					
						if($table_row_type == 'successive_sessions') {
							echo '<div class="col-lg-12">';
						} else {
							echo '<div class="custom-col-'.$col_class.'">';
						}

						echo '<div class="inner_track '.$table_row_type.'">';

						//if ( $table_row_type != 'successive_sessions') {
							
							if(!$table_row['hide_automatic_track_numbers']) {
								echo '<span class="track_label">Track '.$inner_track_key_next.'</span>';	
							}

							echo "<p class='track_title'>".$inner_track['track_title']."</p>"; 
						//}
						
						if(isset($inner_track['track_description'])){
							echo "<p class='track_description'>".$inner_track['track_description']."</p>"; 
						}
						

						
						if(!empty($inner_track['track_sessions']) ) {
							
							$track_sessions = vc_param_group_parse_atts($inner_track['track_sessions']);
							
						
							$first_track_title = '';
							foreach($track_sessions as $track_session_key => $track_session) {
								if(isset($track_session['session_title'])) {
									$first_track_title = $track_session['session_title'];
								}
							}

							if($first_track_title != '' || $table_row_type == 'successive_sessions' ) {
								echo '<div class="track_sessions">';

								if ( $table_row_type == 'breakout_tracks' && count($track_sessions) > 1 ) {
									//show it in a select compact mode 
									?>
									<select name="tracks_dropdown" class="tracks_dropdown">
											<?php
											foreach($track_sessions as $track_session_key => $track_session) {
												$track_session_key_next = $track_session_key + 1; 
												echo '<option value="session-'.$inner_track_key_next.'-'.$track_session_key_next.'">Session '.$inner_track_key_next.'.'.$track_session_key_next.': '.$track_session['session_title'].'</option>';
											}
											?>
									</select>

									<?php 
										foreach($track_sessions as $track_session_key => $track_session) {
											$track_session_key_next = $track_session_key + 1; 
											$custom_session_class = $track_session_key_next == 1 ? "show" : "hidden";

											echo '<div id="session-'.$inner_track_key_next.'-'.$track_session_key_next.'" class="track_session '.$custom_session_class.'">';
											
											echo '<span class="session_label">Session '.$inner_track_key_next.'.'.$track_session_key_next.'</span>';
											
											if(!empty($track_session['session_speaker'])) {
												echo "<div class='session_speaker'>Speaker: ".$track_session['session_speaker']."</div>";
											}

											

											if(!empty($track_session['session_title'])) {
												echo "<p class='session_title'><b>Title: </b>".$track_session['session_title']."</p>";
											}
											if(!empty($track_session['session_description'])) {
												echo "<div class='session_description'>".$track_session['session_description']."</div>";
											}

											echo "</div>";
										}
									?>

								<?php } else {
									foreach($track_sessions as $track_session_key => $track_session) {
										$track_session_key_next = $track_session_key + 1; 

										echo '<div class="track_session">';

										if($table_row['type'] == 'successive_sessions') {
											echo '<span class="session_label">Story '.$track_session_key_next.'</span>';
										} else {
											echo '<span class="session_label">Session '.$inner_track_key_next.'.'.$track_session_key_next.'</span>';
										}
										

										if(!empty($track_session['session_title'])) {
											echo "<p class='session_title'>".$track_session['session_title']."</p>";
										}

										if(!empty($track_session['session_speaker'])) {
											echo "<div class='session_speaker'>Speaker: ".$track_session['session_speaker']."</div>";
										}

										if(!empty($track_session['session_description'])) {

											echo "<div class='session_description'>".$track_session['session_description']."</div>";
										}

										echo "</div>";
									}
								}


								echo "</div>";


							}

							

						}

						echo "</div>";
						echo "</div>";
					}

				}
				

				echo "</div>";
				

			}
			

			//if is compact, show the speaker 
			if(!empty($atts['compact_table'])) {
				if (!empty($table_row['speaker_images'])) {
					$attached_images_ids = explode(',',$table_row['speaker_images']);
					$num_speakers = count($attached_images_ids);
					$multiple_speakers_class = "";
					if($num_speakers > 1) {
						$multiple_speakers_class = "multiple_speakers"; 
					}
					echo '<div class="event_speaker '.$multiple_speakers_class.'" data-speakers="'.$num_speakers.'">';
					echo '<div class="event_speaker_img">';
					
					foreach($attached_images_ids as $attached_images_id) {
						echo '<div class="speaker_img_holder"><img src="'.wp_get_attachment_image_url($attached_images_id, 'full').'" class="speaker_image" /></div>';
					}

					echo "</div>";
				} else {
					echo '<div class="event_speaker">';
				}
				
				if(!empty($table_row['speaker_name'])) {
					echo '<div class="event_speaker_title">';
				}
				

				if (!empty($table_row['custom_speaker_type'])) {
					echo "<div class='speaker_type'>".$table_row['custom_speaker_type']."</div>";
				}
				if (!empty($table_row['speaker_name'])) {
					echo "<p class='speaker_name'>".$table_row['speaker_name']."</p>";
				}
				if (!empty($table_row['speaker_description'])) {
					echo "<p class='speaker_description'>".$table_row['speaker_description']."</p>";
				}

				echo "</div>";
				echo "</div>";

				if (!empty($table_row['participants'])) {
					$participants = vc_param_group_parse_atts($table_row['participants']);
					$num_speakers = count($participants);
					
					echo '<div class="participants event_speaker multiple_speakers" data-speakers="'.$num_speakers.'">';
					foreach($participants as $participant_key => $participant) {

						if(isset($participant['image'])){
							$attached_images_id = $participant['image'];
							echo '<div class="event_speaker_container event_speaker_img">';
							echo '<div class="speaker_img_holder"><img src="'.wp_get_attachment_image_url($attached_images_id, 'full').'" class="speaker_image" /></div>';

							if(isset($participant['name'])){
								echo '<div class="name">'.$participant['name'].'</div>';
							}
							if(isset($participant['position'])){
								echo '<div class="position">'.$participant['position'].'</div>';
							}
							if(isset($participant['speaker_type'])){
								echo '<div class="speaker_type">'.$participant['speaker_type'].'</div>';
							}

							echo '</div>';
						}

						
					}
					echo "</div>";
				}
			}

			echo "</td>";
			
	
			if($colspan == 1) {
				//show second td only if colspan is not set
				echo "<td>";

				if (!empty($table_row['speaker_images'])) {
					$attached_images_ids = explode(',',$table_row['speaker_images']);
					$num_speakers = count($attached_images_ids);
					$multiple_speakers_class = "";
					if($num_speakers > 1) {
						$multiple_speakers_class = "multiple_speakers"; 
					}
					echo '<div class="event_speaker '.$multiple_speakers_class.'" data-speakers="'.$num_speakers.'">';
					echo '<div class="event_speaker_img">';
					
					foreach($attached_images_ids as $attached_images_id) {
						echo '<div class="speaker_img_holder"><img src="'.wp_get_attachment_image_url($attached_images_id, 'full').'" class="speaker_image" /></div>';
					}

					echo "</div>";
				} else {
					echo '<div class="event_speaker">';
				}

				echo '<div class="event_speaker_title">';

				if (!empty($table_row['custom_speaker_type'])) {
					echo "<div class='speaker_type'>".$table_row['custom_speaker_type']."</div>";
				}

				if (!empty($table_row['speaker_name'])) {
					echo "<p class='speaker_name'>".$table_row['speaker_name']."</p>";
				}
				if (!empty($table_row['speaker_description'])) {
					echo "<p class='speaker_description'>".$table_row['speaker_description']."</p>";
				}

				echo "</div>";
				echo "</div>";

				if (!empty($table_row['participants'])) {
					$participants = vc_param_group_parse_atts($table_row['participants']);
					$num_speakers = count($participants);
					
					echo '<div class="participants event_speaker multiple_speakers" data-speakers="'.$num_speakers.'">';
					foreach($participants as $participant_key => $participant) {

						if(isset($participant['image'])){
							$attached_images_id = $participant['image'];
							echo '<div class="event_speaker_container event_speaker_img">';
							echo '<div class="speaker_img_holder"><img src="'.wp_get_attachment_image_url($attached_images_id, 'full').'" class="speaker_image" /></div>';

							if(isset($participant['name'])){
								echo '<div class="name">'.$participant['name'].'</div>';
							}
							if(isset($participant['position'])){
								echo '<div class="position">'.$participant['position'].'</div>';
							}
							if(isset($participant['speaker_type'])){
								echo '<div class="speaker_type">'.$participant['speaker_type'].'</div>';
							}

							echo '</div>';
						}

						
					}
					echo "</div>";
				}


				echo "</td>";
			}

			
			echo "</tr>";
        }
    }
    ?>

	</tbody>
	</table>

	<?php 
	$result = ob_get_clean();
	return $result;
}






add_shortcode('nc_odoo_confirm_registration','nc_odoo_confirm_registration_fallback');
function nc_odoo_confirm_registration_fallback(){
	$action = strip_tags($_GET['action']); // action=register
	if($action == "register") {
		global $wp;
		$page_url = home_url(add_query_arg(array(), $wp->request));

		[$url, $db, $username, $password] = getOdooConnection();

		//authenticate
		$uid = ODOO_UID;
		$models = RipcordLogger::client("$url/xmlrpc/2/object");
		
		if(isset($_GET['aids'])) {
			$attendee_ids = explode(',', strip_tags($_GET['aids']));
			//print_r($attendee_ids);
			
			foreach($attendee_ids as $attendee_id) {
				//echo "Attendee ID: ".$attendee_id."<br>";
				$models->execute_kw(
					$db, $uid, $password,
					'event.registration', 'write',
					array(array(intval($attendee_id)), array('state' => 'open')) // set the attendee to Confirmed
				);
			}

			echo '<div class="marginAuto nc_success_message_alt">'.__('Thanks! You were registered successfully!','nextcloud').'</div>';

		}
		

	} else {
		echo "No action set.";
	}
}



add_shortcode('nc_odoo_debug_attendee','nc_odoo_debug_attendee_fallback');
function nc_odoo_debug_attendee_fallback(){
	global $wp;
	$page_url = home_url(add_query_arg(array(), $wp->request));

	[$url, $db, $username, $password] = getOdooConnection();

	//authenticate
	$uid = ODOO_UID;
	$models = RipcordLogger::client("$url/xmlrpc/2/object");


	$specific_event_id = 39;
	$attendee_id = 63091; // Peter test


	/*
	// Fetch all fields for this attendee
	$attendee_data = $models->execute_kw(
		$db, $uid, $password,
		'event.registration', 'read',
		array(array($registration_id)) // Pass the ID as a list
	);
	// Print attendee data
	echo "<pre>";
	print_r($attendee_data);
	echo "</pre>";

	//Fetch Answer Records Linked to This Registration
	$answer_ids = $models->execute_kw(
		$db, $uid, $password,
		'event.registration.answer', 'search',
		array(array(array('registration_id', '=', $registration_id))) // Filter by attendee
	);
	echo "<pre>";
	//print_r($answer_ids);
	echo "</pre>";



	//show Answer IDs
	if (!empty($answer_ids)) {
		//get fields - working
		$fields = $models->execute_kw(
			$db, $uid, $password,
			'event.registration.answer', 'fields_get',
			array(),
			array('attributes' => array('string', 'type'))
		);
		echo "<pre>";
		//print_r($fields);
		echo "</pre>";
		
		//get question IDs and info based on answer IDs from this specific attendee
		$answers = $models->execute_kw(
			$db, $uid, $password,
			'event.registration.answer', 'read',
			array($answer_ids), // Fetch all answer records
			array('fields' => array('registration_id', 'question_id', 'value_answer_id', 'value_text_box', 'display_name'))
		);
		echo "<pre>";
		//print_r($answers);
		echo "</pre>";
	} else {
		echo "No answers found for this registration.";
	}
	*/

	/*		// show questions IDs
			$odoo_event_id = 30;
			$questions = $models->execute_kw($db, $uid, $password, 'event.question', 'search_read', array(array(array('event_id', '=', $odoo_event_id))), array('fields' => array('id', 'name')));
			echo "<pre>";
			print_r($questions);
			echo "</pre>";
	*/


	$attendees = $models->execute_kw(
			$db, $uid, $password,
			'event.registration', 'read',
			array(array(intval($attendee_id)))
	);
	$attendee = $attendees[0];
	//var_dump($attendee);
	$barcode_url = '';

	if(isset($_GET['bc'])) {
		$barcode_url = strip_tags($_GET['bc']);
	}

    if (empty($attendee)) {
        return '<p class="nc_alert_message">Attendee not found.</p>';
    }

    // Validate that attendee is registered for the correct event
    if (empty($attendee['event_id']) || $attendee['event_id'][0] != $specific_event_id) {
        return '<p class="nc_alert_message">Attendee not registered for this event.</p>';
    }

    // Prepare the output
	$attendee_type = esc_html($attendee['x_studio_attendee_type_1']); 
	$attendee_type_class = strtolower(str_replace(' ', '_', $attendee_type));
	$barcode = $attendee['barcode'];
	echo "Barcode: ".$barcode."<br><br>";

	print_r($attendee);

}


function shortcode_show_current_template() {
    global $template;
    $return = '<div class="show_template hidden" style="">' .
               'Template file: <strong>' . basename($template) . '</strong>'; 
			   
			   $return .= '<pre>';
			   $return .= get_post_meta(get_the_ID(), '_wp_page_template', true);
			   $return .= '</pre>';
	$return .= '</div>';
    return $return;
}
add_shortcode('show_template', 'shortcode_show_current_template');


//use Endroid\QrCode\Builder\Builder;
function summit_print_attendee_infos_shortcode() {
	ob_start();

	global $wp;
	$page_url = home_url(add_query_arg(array(), $wp->request));
    // Settings
    $specific_event_id = 39; // 39 = Summit official event ID
	//$specific_event_id = 31; // 31 = Testing event ID

    // Check if attendee_id is in URL
    if (!isset($_GET['attendee_id'])) {
        return '<p>No attendee ID provided.</p>';
    }
	if (!isset($_GET['action'])) {
        return '<p>No action provided.</p>';
    }

	[$url, $db, $username, $password] = getOdooConnection();

    $attendee_id = intval($_GET['attendee_id']); //get ID as integer 

    $uid = ODOO_UID;
    if (!$uid) {
        return '<p>Authentication failed.</p>';
    }
    // Connect to the object endpoint
    $models = RipcordLogger::client("$url/xmlrpc/2/object");

	if(isset($_GET['action'])){
		$action = strip_tags($_GET['action']);

		if($action == "show_badge") {
			//show badge


    // Read the attendee record
	/*
    $attendees = $models->execute_kw($db, $uid, $password,
        'event.registration','read',
        array(array($attendee_id)),
        array('fields' => array('name', 'company_name', 'event_id'))
    );
	*/

	$attendees = $models->execute_kw(
			$db, $uid, $password,
			'event.registration', 'read',
			array(array(intval($attendee_id)))
	);
	$attendee = $attendees[0];
	//var_dump($attendee);
	$barcode_url = '';

	if(isset($_GET['bc'])) {
		$barcode_url = strip_tags($_GET['bc']);
	}

    if (empty($attendee)) {
        return '<p class="nc_alert_message">Attendee not found.</p>';
    }

    // Validate that attendee is registered for the correct event
    if (empty($attendee['event_id']) || $attendee['event_id'][0] != $specific_event_id) {
        return '<p class="nc_alert_message">Attendee not registered for this event.</p>';
    }

    // Prepare the output
	$attendee_type = esc_html($attendee['x_studio_attendee_type_1']); 
	$attendee_type_class = strtolower(str_replace(' ', '_', $attendee_type));
	$barcode = $attendee['barcode'];

	if($barcode != $barcode_url){
		return '<p class="nc_alert_message">Barcode not found.</p>';
	}
	
	?>
	<div class="attendee_container">
	<div class="attendee_info_shortcode summit type_<?php echo $attendee_type_class; ?>">
		
		<div>
			<div class="logo">
				<img src="https://nextcloud.com/c/uploads/2025/01/NC-Summit-logo-white-1.svg" alt="">
			</div>


			<div class="name_container">
			<div class="name"><?php echo esc_html($attendee['name']); ?></div>
			<div class="company"><?php echo esc_html($attendee['x_studio_attendee_company']); ?></div>
			</div>	
			
			<div class="qr_code">

				<div class="img_container">

						<div class="confirm_qr">
						<?php
						$link = "https://nextcloud.com/summit-2025-badge/?attendee_id=$attendee_id&action=confirm";
						//echo "QR code from this link: ".$link; 
						$result = Endroid\QrCode\Builder\Builder::create()
							->data($link)
							->size(300)
							->margin(10)
							->build();
						$base64 = base64_encode($result->getString());
						?><img src="data:image/png;base64,<?= $base64 ?>" alt="Badge">
						</div>
						

						<div class="agenda_qr">
						<?php
						$link_agenda = "https://nextcloud.com/summit/#agenda";
						//echo "QR code from this link: ".$link; 
						$result = Endroid\QrCode\Builder\Builder::create()
							->data($link_agenda)
							->size(300)
							->margin(10)
							->build();
						$base64 = base64_encode($result->getString());
						?><img src="data:image/png;base64,<?= $base64 ?>" alt="Agenda">
						<span class="label">Scan the agenda here</span>
						</div>

				</div>

			
			</div>


			<div class="attendee_type"><?php
			$attendee_type_string = "Attendee"; // default fallback

			switch($attendee_type) {
				case "Employee": 
					$attendee_type_string = "Team";
					break;
				case "Press Contact": 
					$attendee_type_string = "Press";
					break;
				case "Sponsor": 
					$attendee_type_string = $attendee_type;
					break;
				case "Speaker": 
					$attendee_type_string = $attendee_type;
					break;
				default:
					$attendee_type_string = "Attendee";
					break;
			}

			echo $attendee_type_string;
			?></div>

		</div>
		
		

			

	</div>
	</div>
	<script>
		jQuery(document).ready(function ($) {
			$('.attendee_info_shortcode').clone().addClass('cloned').appendTo('.attendee_container');
		});
	</script>
	<?php
	} else if($action == "confirm") {
		// set the attendee to "Attended"
		if (is_user_logged_in()) { //if logged in, enable scanning
			
			$attendees = $models->execute_kw(
					$db, $uid, $password,
					'event.registration', 'read',
					array(array(intval($attendee_id)))
			);
			$attendee = $attendees[0];

					//set attendee as "attending"
			$attended = $models->execute_kw(
				$db, $uid, $password,
				'event.registration', 'write',
				array(array(intval($attendee_id)), array('state' => 'done')) // set the attendee to Attended
			);

			if($attended) {
				echo '<p class="nc_success_message_alt">Attendee '.esc_html($attendee['name']).' is set as attending.</p>';
			}

		} else {
			echo "<p class='nc_alert_message'>Only logged in users are able to scan.</p>";
		}

				
		}
	} else {
		echo "<p class='nc_alert_message'>Action not recognized.</p>";
	}


    //return $output;
	$result = ob_get_clean();
	return $result;
}

add_shortcode('summit_print_attendee_infos', 'summit_print_attendee_infos_shortcode');

//custom WordPress shortcode [br] that adds a <br> (line break) element
function br_shortcode() {
    return '<br />';
}
add_shortcode('br', 'br_shortcode');



//get browser language - used for debugging for now 
function browser_language_shortcode_php() {
    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        // Extract the language part from the header
        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 5); // e.g., "en-US"
    } else {
        $lang = 'unknown';
    }

    return esc_html($lang);
}
add_shortcode('browser_language', 'browser_language_shortcode_php');


//automatically opens up the magnific popup once the nf_resume parameter is found in the URL 
function nf_resume_popup_shortcode($atts) {
    // Extract the ID attribute from the shortcode
    $atts = shortcode_atts([
        'id' => 'conf-form-popup' // default ID if none provided
    ], $atts);

    $popup_id = esc_js($atts['id']);

    ob_start();
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            function getUrlParameter(name) {
                name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                var results = regex.exec(window.location.search);
                return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
            }

            if (getUrlParameter('nf_resume')) {
                // Ensure the element exists before initializing
                if ($('#<?php echo $popup_id; ?>').length) {
                    $.magnificPopup.open({
                        items: {
                            src: '#<?php echo $popup_id; ?>'
                        },
                        type: 'inline',
                        midClick: true
                    });
                }
            }
        });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('nf_resume_popup', 'nf_resume_popup_shortcode');



//Repeater Images Carousel
add_action('vc_before_init', 'images_carousel_wpb_funct');
function images_carousel_wpb_funct() {
	vc_map(
		  array(
		  	"name" => __("Simple images carousel", "nextcloud"), // Element name
		  	"base" => "images_carousel", // Element shortcode
		  	"class" => "images_carousel",
		  	"category" => __('Carousel', 'nextcloud'),
		  	'params' => array(

				array(
					"type" => "textfield",
					"heading" => esc_html__("ID", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "id",
					"value" => "",
				),


		  		array(
		  			'type' => 'param_group',
		  			'param_name' => 'box_repeater_items',
		  			'params' => array(
		  				array(
		  					"type" => "attach_image",
		  					"holder" => "img",
		  					"class" => "",
		  					"heading" => __("Image", "nextcloud"),
		  					"param_name" => "image",
		  					"value" => __("", "nextcloud"),
		  				),
		  				array(
		  					"type" => "vc_link",
		  					"holder" => "div",
		  					"class" => "client_link",
		  					"admin_label" => true,
		  					"heading" => __("Link", "nextcloud"),
		  					"param_name" => "link",
		  					"value" => __("", "nextcloud"),
						)

		  			)
		  		),

				array(
					"type" => "textfield",
					"heading" => esc_html__("Custom CSS Classes", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "custom_css",
					"value" => "",
				),
				array(
					"type" => "textfield",
					"heading" => esc_html__("Custom slider items", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "custom_slider_items",
					"value" => "",
				),
				array(
					"type" => "textarea_raw_html",
					"heading" => esc_html__("Custom code for carousel", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "custom_code",
					"value" => "",
				)
		  	)
		  )
	  );
}

add_shortcode('images_carousel', 'simple_images_carousel_funct');
function simple_images_carousel_funct($atts) {
	ob_start();
	$atts = shortcode_atts(array(
		'id' => '',
		'box_repeater_items' => '',
		'custom_css' => '',
		'custom_slider_items' => '',
		'custom_code' => ''
	), $atts, 'box_repeater');

	if(function_exists('vc_param_group_parse_atts')){
		$items = vc_param_group_parse_atts($atts['box_repeater_items']);
	}
	
	$id = $atts['id'];
	$custom_css = $atts['custom_css'];
	$custom_slider_items = $atts['custom_slider_items'];
	//$custom_code = $atts['custom_code'];
	$custom_code = rawurldecode( base64_decode( $atts['custom_code'] ) );
	?>
      <div class="simple_images">

          <?php if ($items) { ?>
              <div id="<?php if(isset($atts['id'])) echo $id; ?>" class="simple_images_carousel owl-carousel owl-theme <?php echo $custom_css; ?>" data-items-desktop="<?php if(isset($custom_slider_items)) echo $custom_slider_items; ?>">
                  <?php  
				  $item_index = 1;
				  foreach ($items as  $item) {
					$item_custom_css ='';
					$item_index++;

					if (isset($item['link'])) {
						$link = vc_build_link($item['link']);
					}

					if(isset($item['item_custom_css'])) {
						$item_custom_css = $item['item_custom_css'];
					}
					?>

						<div class="single_image <?php echo $item_custom_css; ?>" style="">
								<figure>
								<?php if (isset($item['link'])) { ?>
									<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" title="<?php echo $item['title']; ?>" class="vc_single_image-wrapper   vc_box_border_grey">
									<?php echo wp_get_attachment_image($item['image'], 'large', '', array( "class" => "rounded boxShadow") ); ?>
									</a>
								<?php } else { ?>
									<a href="<?php echo wp_get_attachment_image_url($item['image'], 'full'); ?>" target="" title="" class="vc_single_image-wrapper  vc_box_border_grey popup-screenshot-gal">
									<?php echo wp_get_attachment_image($item['image'], 'large', '', array( "class" => "rounded boxShadow") ); ?>
									</a>
								<?php } ?>
								</figure>
						</div>

                  <?php } //end foreach ?>
          <?php } ?>

      </div> <!-- .single_images --> 
      <?php
		if ($custom_code && $id) { ?>
			<script>
			jQuery(document).ready(function ($) {
				$('#<?php echo $id; ?>').owlCarousel({
					<?php 
						echo $custom_code;
					?>
				});
			});
			</script>
		<?php
		}

	  $result = ob_get_clean();
	return $result;
}





// Grid images
add_action('vc_before_init', 'images_grid_wpb_funct');
function images_grid_wpb_funct() {
	vc_map(
		  array(
		  	"name" => __("Grid images", "nextcloud"), // Element name
		  	"base" => "grid_images", // Element shortcode
		  	"class" => "grid_images",
		  	"category" => __('Images', 'nextcloud'),
		  	'params' => array(

				array(
					"type" => "textfield",
					"heading" => esc_html__("ID", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "id",
					"value" => "",
				),

		  		array(
		  					"type" => "attach_images",
		  					"holder" => "img",
		  					"class" => "",
		  					"heading" => __("Images", "nextcloud"),
		  					"param_name" => "images",
		  					"value" => __("", "nextcloud"),
		  		),

				array(
					"type" => "textfield",
					"heading" => esc_html__("Custom CSS Classes", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "custom_css",
					"value" => "",
				)
				
		  	)
		  )
	  );
}

add_shortcode('grid_images', 'grid_images_funct');
function grid_images_funct($atts) {
	ob_start();
	$atts = shortcode_atts(array(
		'id' => '',
		'images' => '',
		'custom_css' => ''
	), $atts, 'box_repeater');


	$id = $atts['id'];
	$images = $atts['images'];
	$images_ids = explode(',',$images);

	$custom_css = $atts['custom_css'];
	?>
      <div class="nc_grid_images <?php echo $custom_css; ?>">
          <?php if ($images_ids) { ?>
                  <?php  
				  foreach ($images_ids as  $image_id) { 
					$attachment_image = wp_get_attachment_image_src( $image_id, 'medium' );
					?>
						<div class="grid_image <?php echo $item_custom_css; ?>" style="">
							<figure>
								<a class="popup-screenshot" href="<?php echo wp_get_attachment_image_src( $image_id, 'full' )[0]; ?>"><img src="<?php echo $attachment_image[0]; ?>" alt=""></a>
							</figure>
						</div>

                  <?php } //end foreach ?>
          <?php } ?>

      </div>
      <?php
	  $result = ob_get_clean();
	return $result;
}



/**
 * Shortcode: [wpml_fallback_events]
 *
 * Shows posts in the current WPML language first, then English fallbacks,
 * all ordered by an event datetime meta field (>= now by default).
 *
 * Attributes:
 * - post_type:        default "post"
 * - meta_key:         default "event_start_date_and_time"
 * - fallback_lang:    default "en"
 * - posts_per_page:   default -1 (all)
 * - order:            default "ASC"
 * - date_from:        default current_time('mysql') (override with "YYYY-mm-dd HH:ii:ss")
 *
 * Example:
 *   [wpml_fallback_events post_type="event" meta_key="event_start_date_and_time"]
 */
add_shortcode('wpml_fallback_events', function ($atts) {
    $atts = shortcode_atts([
        'post_type'       => 'event',
        'meta_key'        => 'event_start_date_and_time',
        'fallback_lang'   => 'en',
        'posts_per_page'  => -1,
        'order'           => 'ASC',
        'date_from'       => '',
    ], $atts, 'wpml_fallback_events');

    // Basic sanitization
    $post_type      = sanitize_key($atts['post_type']);
    $meta_key       = sanitize_key($atts['meta_key']);
    $fallback_lang  = preg_replace('~[^a-z\-]~i', '', $atts['fallback_lang']);
    $posts_per_page = intval($atts['posts_per_page']);
    $order          = strtoupper($atts['order']) === 'DESC' ? 'DESC' : 'ASC';
    $current_dt     = $atts['date_from'] !== '' ? sanitize_text_field($atts['date_from']) : wp_date("Y-m-d H:i:s", null, $timezone );;

    // Current language via WPML (falls back gracefully if WPML is missing)
    $current_lang = defined('ICL_LANGUAGE_CODE')
        ? ICL_LANGUAGE_CODE
        : apply_filters('wpml_current_language', null);



    // If WPML truly isn't present, just do a single query and return.
    if (empty($current_lang)) {
		//$args['post_type'] = array('event');
        $args = [
            'post_type'        => $post_type,
            'posts_per_page'   => $posts_per_page,
            'suppress_filters' => false,
            'meta_query'       => [[
                'key'     => $meta_key,
                'value'   => $current_dt,
                'compare' => '>=',
                'type'    => 'DATETIME',
            ]],
			'tax_query'       => [[
                'taxonomy'     => 'event_categories',
                'field'   => 'slug',
                'terms' => 'webinars'
            ]],
            'meta_key'  => $meta_key,
            'orderby'   => 'meta_value',
            'order'     => $order,
        ];
        $q = new WP_Query($args);

        ob_start();
        if ($q->have_posts()) {
            echo '<div class="wpml-fallback-events">';
            while ($q->have_posts()) { $q->the_post();
                $pid  = get_the_ID();
                $when = get_post_meta($pid, $meta_key, true);
                echo '<article class="event-item">';
                echo '<h4 class="event-title"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h4>';
                if ($when) {
                    echo '<p class="event-when">' . esc_html($when) . '</p>';
                }
                echo '</article>';
            }
            echo '</div>';
            wp_reset_postdata();
        }
        return ob_get_clean();
    }

    // With WPML:
    $element_type = "post_{$post_type}";

    $base_args = [
		'post_status' => array('publish'),
        'post_type'        => $post_type,
        'posts_per_page'   => $posts_per_page,
        'suppress_filters' => false, // allow WPML to apply 'lang'
        'meta_query'       => [[
            'key'     => $meta_key,
            'value'   => $current_dt,
            'compare' => '>=',
            'type'    => 'DATETIME',
        ]],
		'tax_query'       => [[
                'taxonomy'     => 'event_categories',
                'field'   => 'slug',
                'terms' => 'webinars'
        ]],
        'meta_key'  => $meta_key,
        'orderby'   => 'meta_value',
        'order'     => $order,
        // tip: add more constraints here if you need (tax_query, etc.)
    ];

    // 1) Current-language query
    $args_current         = $base_args;
    $args_current['lang'] = $current_lang;

    $q_current = new WP_Query($args_current);

    $fallback_en_ids = [];

    // We’ll render current-language posts and collect EN IDs to exclude later.
    ob_start();
    echo '<div class="wpml-fallback-events">';

    if ($q_current->have_posts()) {
        echo '<h3 class="event-group-title">' . esc_html(strtoupper($current_lang)) . '</h3>';
        while ($q_current->have_posts()) {
            $q_current->the_post();
            $pid  = get_the_ID();
            $when = get_post_meta($pid, $meta_key, true);

            // Resolve the English counterpart for exclusion in the fallback query
            $en_id = apply_filters('wpml_object_id', $pid, $post_type, false, $fallback_lang);
			//$en_id = apply_filters('wpml_object_id', $pid, $post_type, false, $current_lang);
			//echo $en_id;

			$webinar_language_details = apply_filters( 'wpml_post_language_details', NULL, $pid );
			$webinar_lang = $webinar_language_details['language_code'];
			if(get_field('custom_language')) {
				$webinar_lang = get_field('custom_language');
			}

            if (  
				/*
				($en_id && 'publish' === get_post_status( $en_id )) //translated post and translation is live
				&& 
				*/
				($webinar_lang != $current_lang)
				) 
				{
                	$fallback_en_ids[] = (int) $en_id;
					//echo "Put $en_id in fallback array";
            } else {
					$fallback_translations_ids[] = (int) $en_id;
					//echo "Show $en_id here, it is in the current language";
				 	echo '<article class="event-item is-current">';
				 	echo '<img style="max-width: 40px;" src="https://nextcloud.com/p/sitepress-multilingual-cms/res/flags/'.$webinar_lang.'.svg" />';
					echo '<h4 class="event-title"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h4>';
					if ($when) {
						echo '<p class="event-when">' . esc_html($when) . '</p>';
					}
					echo '</article>';
			}

           
        }
        wp_reset_postdata();
    }
	//print_r($fallback_en_ids);



    // 2) English fallback query (excluding English posts whose group already has a current-language post)
    $args_fallback = $base_args;
    //$args_fallback['lang']  = $fallback_lang;
    if (!empty($fallback_en_ids)) {
        $args_fallback['post__in'] = array_unique(array_map('intval', $fallback_en_ids));
    }
	if (!empty($fallback_translations_ids)) {
        $args_fallback['post__not_in'] = array_unique(array_map('intval', $fallback_translations_ids));
    }


    $q_fallback = new WP_Query($args_fallback);

    if ($q_fallback->have_posts()) {
        echo '<h3 class="event-group-title">' . esc_html(strtoupper($fallback_lang)) . ' <span class="event-badge">(fallback)</span></h3>';
        while ($q_fallback->have_posts()) {
            $q_fallback->the_post();
            $pid  = get_the_ID();
            $when = get_post_meta($pid, $meta_key, true);
			$webinar_language_details = apply_filters( 'wpml_post_language_details', NULL, $pid );
			$webinar_lang = $webinar_language_details['language_code'];
			if(get_field('custom_language')) {
				$webinar_lang = get_field('custom_language');
			}

            echo '<article class="event-item is-fallback">';
			echo '<img style="max-width: 40px;" src="https://nextcloud.com/p/sitepress-multilingual-cms/res/flags/'.$webinar_lang.'.svg" />';
            echo '<h4 class="event-title"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h4>';
            if ($when) {
                echo '<p class="event-when">' . esc_html($when) . '</p>';
            }
            echo '</article>';
        }
        wp_reset_postdata();
    }

    echo '</div>';

    // Minimal inline styles (optional)
    echo '<style>
        .wpml-fallback-events { display:grid; gap:1rem; }
        .wpml-fallback-events .event-group-title { margin:1rem 0 0.25rem; font-weight:700; }
        .wpml-fallback-events .event-badge { font-weight:400; font-size:0.9em; opacity:0.7; }
        .wpml-fallback-events .event-item { padding:0.6rem 0; border-bottom:1px solid #eee; }
        .wpml-fallback-events .event-item:last-child { border-bottom:0; }
        .wpml-fallback-events .event-item.is-fallback { opacity:0.9; }
        .wpml-fallback-events .event-title { margin:0 0 0.25rem; }
        .wpml-fallback-events .event-when { margin:0; font-size:0.9em; opacity:0.8; }
    </style>';

    return ob_get_clean();
});



/**
 * Shortcode: [webinars_other_langs]
 *
 * Checks if there are any upcoming "webinars" events in languages other than EN.
 * If yes, echoes a custom text.
 *
 * Attributes:
 * - custom_text:   The text to echo if found (default: "Webinars are available in other languages.")
 * - exclude_lang:  Language code to exclude (default: "en")
 * - date_from:     Start datetime, default current_time('mysql') e.g. "2025-01-01 00:00:00"
 *
 * Usage:
 *   [webinars_other_langs custom_text="See our webinars in other languages →"]
 */
add_shortcode('webinars_other_langs', function ($atts) {
    $atts = shortcode_atts([
        'exclude_lang' => 'en',
        'date_from'    => '',
    ], $atts, 'webinars_other_langs');

    if ( ! function_exists('icl_object_id') && ! has_filter('wpml_active_languages') ) {
        return '<p>WPML not active.</p>';
    }

    $exclude_lang = preg_replace('~[^a-z\-]~i', '', $atts['exclude_lang']);
    $current_dt   = $atts['date_from'] !== '' ? sanitize_text_field($atts['date_from']) : current_time('mysql');

    $langs = apply_filters('wpml_active_languages', null, ['skip_missing' => 0]);
    if (empty($langs) || !is_array($langs)) {
        return '<p>No active languages found.</p>';
    }

    $prev_lang = apply_filters('wpml_current_language', null);
    $base_term = get_term_by('slug', 'webinars', 'event_categories');

    $output = '<div class="webinars-other-langs">';
    $found_any = false;
	$found_lang_labels = [];

    foreach ($langs as $code => $info) {
        if (strtolower($code) === strtolower($exclude_lang)) {
            continue;
        }

        // Switch to target language
        do_action('wpml_switch_language', $code);

        // Translate the "webinars" term to this language and use term_id
        $term_id_for_lang = null;
        if ($base_term && ! is_wp_error($base_term)) {
            $translated_term_id = apply_filters(
                'wpml_object_id',
                (int) $base_term->term_id,
                'event_categories',
                true,
                $code
            );
            if ($translated_term_id) {
                $term_id_for_lang = (int) $translated_term_id;
            }
        }
        if (empty($term_id_for_lang)) {
            continue;
        }

        // Query in this language context
        $args = [
            'post_type'        => 'event',
            'post_status'      => 'publish',
            'posts_per_page'   => -1,
            'fields'           => 'ids',
            'suppress_filters' => false,
            'meta_query'       => [[
                'key'     => 'event_start_date_and_time',
                'value'   => $current_dt,
                'compare' => '>=',
                'type'    => 'DATETIME',
            ]],
            'tax_query'        => [[
                'taxonomy' => 'event_categories',
                'field'    => 'term_id',
                'terms'    => [$term_id_for_lang],
            ]],
            'meta_key'   => 'event_start_date_and_time',
            'orderby'    => 'meta_value',
            'order'      => 'ASC',
        ];

        $q = new WP_Query($args);

        // HARD FILTER: keep only posts whose actual language == $code
        $strict_ids = [];

        if (!empty($q->posts)) {
			
            $element_type = 'post_event';
            foreach ($q->posts as $pid) {
                $lang_code = apply_filters('wpml_element_language_code', null, [
                    'element_id'   => (int) $pid,
                    'element_type' => $element_type,
                ]);
                if ($lang_code === $code) {
                    $strict_ids[] = (int) $pid;
                }
            }
			
        }

        if (!empty($strict_ids)) {
            $found_any = true;
            $lang_label = $info['native_name'] ?: $info['translated_name'] ?: strtoupper($code);
			$found_lang_labels[$code] = $lang_label;

			/*
			$output .= '<ul>';
            foreach ($strict_ids as $pid) {
                $output .= sprintf(
                    '<li><strong>ID:</strong> %d — <a href="%s">%s</a></li>',
                    $pid,
                    esc_url(get_permalink($pid)),
                    esc_html(get_the_title($pid))
                );
            }
            $output .= '</ul>';
			*/
        }

        wp_reset_postdata();
    }

    // Restore original language
    if ($prev_lang) {
        do_action('wpml_switch_language', $prev_lang);
    }

	if($found_any) {
		//$output .= '<p>';
		$output .= "<p class=''>".__("Make sure you don't miss our localized webinars: ", "nextcloud")."</p>";
		$current_url = get_permalink();
		foreach($found_lang_labels as $code => $found_lang_label) {
			$translated_url  = apply_filters( 'wpml_permalink', $current_url, $code);
			$output .= '<a class="lang_code" data-lang="'.$code.'" href="'.esc_url( $translated_url ).'#virtual-events">';
			$output .= '<img style="" src="https://nextcloud.com/p/sitepress-multilingual-cms/res/flags/'.$code.'.svg" />';
			$output .= $found_lang_label.'</a>';
		}
		//$output .= '</p>';
	} else {
		/*
		$output .= '<p>';
		$output .= __("No webinars found in other languages.", "nextcloud");
		$output .= '</p>';
		*/
    }

    $output .= '</div>';

    return $output;
});




// Shortcode printing the date and location of an event
// WPBakery (Visual Composer) element registration
if ( function_exists( 'vc_map' ) ) {
    add_action( 'vc_before_init', 'my_event_info_vc_element' );
    function my_event_info_vc_element() {
        vc_map( array(
            'name'        => __( 'Event Info', 'nextcloud' ),
            'base'        => 'event_info',
            'category'    => __( 'Content', 'nextcloud' ),
            'description' => __( 'Displays date and location with icons.', 'nextcloud' ),
            //'icon'        => 'dashicons-calendar-alt',
            'params'      => array(
                array(
                    'type'        => 'datetime',
                    'heading'     => __( 'Date', 'nextcloud' ),
                    'param_name'  => 'date',
                    'description' => __( '', 'nextcloud' ),
					"admin_label" => true,
                ),

				array(
                    'type'        => 'checkbox',
                    'heading'     => __( 'Manual date?', 'nextcloud' ),
                    'param_name'  => 'manual_date',
                    'description' => __( '', 'nextcloud' ),
					"admin_label" => false,
					'value'       => array( __( 'Yes', 'nextcloud' ) => 'yes' )
                ),

				array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Manual date and time', 'nextcloud' ),
                    'param_name'  => 'manual_date_time',
                    'description' => __( '', 'nextcloud' ),
					"admin_label" => false,
					"dependency" => array(
                        "element" => "manual_date",
                        "value" => "yes"
                    )
                ),

                array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Location', 'nextcloud' ),
                    'param_name'  => 'location',
                    'description' => __( 'Example: Munich, Germany', 'nextcloud' ),
					"admin_label" => true,
                ),
				array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Custom CSS classes', 'nextcloud' ),
                    'param_name'  => 'custom_class',
                    'description' => __( '', 'nextcloud' ),
                )

            ),
        ) );
    }
}

// Shortcode callback
function my_event_info_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'date'     => '', // expected format: YYYY-MM-DD
            'location' => '',
			'manual_date' => '',
			'manual_date_time' => '',
			'custom_class' => ''
        ),
        $atts,
        'event_info'
    );
	$my_current_lang = apply_filters( 'wpml_current_language', NULL );
	$timezone = new DateTimeZone('Europe/Berlin');
	
	$html = "";

	/*
    if ( empty( $atts['date'] ) || empty( $atts['location'] ) ) {
        return '';
    }
	*/

    // Try to parse the date
    //$date_start = DateTime::createFromFormat('d/m/Y', $atts['date'], $timezone);
	$date_formatted = "";

	// $html .= '<div class="hidden">';
	// $html .= $atts['manual_date_time'];
	// $html .= '</div>';

	if ( isset( $atts['manual_date_time'] ) && $atts['manual_date_time'] != '' ) {
		$date_formatted = $atts['manual_date_time'];
	}
	else if ( isset( $atts['date'] ) && $atts['date'] != '' ) {
		$date_formatted = $atts['date'];
		$date_format = "F jS, Y";

		if($my_current_lang != 'en'){
			$date_format = "j F Y";
		}

		$event_date = date_create($atts['date']);
		$date_from_format = DateTime::createFromFormat('d/m/Y', $atts['date'], $timezone);
		$date_formatted = formatLanguage($date_from_format, $date_format, $my_current_lang);
    }

	$html .= '<div class="wpb_content_element event_date custom_shortcode '.$atts['custom_class'].'">';
    $html .= '<h5>';
    $html .= '<svg fill="white" xmlns="http://www.w3.org/2000/svg" id="Layer_1" version="1.1" viewBox="0 0 24 24">
	<path class="st0" d="M5,22c-.55,0-1.021-.196-1.413-.588-.392-.392-.588-.863-.588-1.413V6c0-.55.196-1.021.588-1.413.392-.392.863-.588,1.413-.588h1V2h2v2h8V2h2v2h1c.55,0,1.021.196,1.413.588s.588.863.588,1.413v14c0,.55-.196,1.021-.588,1.413s-.863.588-1.413.588H5ZM5,20h14v-10H5v10ZM5,8h14v-2H5v2ZM5,8v-2,2ZM12,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288c.192.192.288.429.288.713s-.096.521-.288.713c-.192.192-.429.288-.713.288ZM8,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713-.429.288-.713.288ZM16,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713-.429.288-.713.288ZM12,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288c.192.192.288.429.288.713s-.096.521-.288.713c-.192.192-.429.288-.713.288ZM8,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713c-.192.192-.429.288-.713.288ZM16,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713c-.192.192-.429.288-.713.288Z"></path>
	</svg>';
    $html .= '<span>'.esc_html( $date_formatted ) . '</span> ';

	if(str_contains( $atts['custom_class'], 'with_br')) {
		$html .= "<br />";
	}

	if(isset($atts['location']) && $atts['location'] != '') {
		$html .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="#0082c9"><path d="M480-480q33 0 56.5-23.5T560-560q0-33-23.5-56.5T480-640q-33 0-56.5 23.5T400-560q0 33 23.5 56.5T480-480Zm0 294q122-112 181-203.5T720-552q0-109-69.5-178.5T480-800q-101 0-170.5 69.5T240-552q0 71 59 162.5T480-186Zm0 106Q319-217 239.5-334.5T160-552q0-150 96.5-239T480-880q127 0 223.5 89T800-552q0 100-79.5 217.5T480-80Zm0-480Z"></path></svg>';
    	$html .= '<span>'.esc_html( $atts['location'] ).'</span>';
	}
    
    $html .= '</h5>';
	$html .= '</div>';

    return $html;
}
add_shortcode( 'event_info', 'my_event_info_shortcode' );




// WPBakery (Visual Composer) element registration
if ( function_exists( 'vc_map' ) ) {
    add_action( 'vc_before_init', 'content_grid_box_custom_vc_element' );
    function content_grid_box_custom_vc_element() {
        vc_map( array(
            'name'        => __( 'Content grid box custom', 'nextcloud' ),
            'base'        => 'content_grid_box_custom',
            'category'    => __( 'Content', 'nextcloud' ),
            'description' => __( 'Repeater with heading, subtitle and optional image.', 'nextcloud' ),
            //'icon'        => 'dashicons-images-alt2',
            'params'      => array(
                array(
                    'type'        => 'param_group',
                    'heading'     => __( 'Items', 'nextcloud' ),
                    'param_name'  => 'items',
                    'description' => __( 'Add multiple items with heading, subtitle, and optional image.', 'nextcloud' ),
                    'params'      => array(
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Heading', 'nextcloud' ),
                            'param_name'  => 'heading',
                        ),
                        array(
                            'type'        => 'textfield',
                            'heading'     => __( 'Subtitle', 'nextcloud' ),
                            'param_name'  => 'subtitle',
                        ),
                        array(
                            'type'        => 'attach_image',
                            'heading'     => __( 'Image', 'nextcloud' ),
                            'param_name'  => 'image',
                            'description' => __( 'Select an image from the media library. If set, only the image will be shown for this item.', 'nextcloud' ),
                        ),
                    ),
                ),
				array(
                    'type'        => 'checkbox',
                    'heading'     => __( 'Open image lightbox on click', 'nextcloud' ),
                    'param_name'  => 'images_lightbox',
            	),
				array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Number of rows', 'nextcloud' ),
                    'param_name'  => 'rows',
            	),
				array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Custom CSS classes', 'nextcloud' ),
                    'param_name'  => 'custom_css',
            	),
				array(
                    'type'        => 'textfield',
                    'heading'     => __( 'Custom heading (Default h3)', 'nextcloud' ),
                    'param_name'  => 'custom_heading',
            	)

            ),
        ) );
    }
}

// Shortcode callback
function content_grid_box_custom_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'items' => '', // WPBakery param_group
			'images_lightbox' => '',
			'rows' => '',
			'custom_css' => '',
			'custom_heading' => 'h3'
        ),
        $atts,
        'my_repeater_items'
    );

    // Parse the param_group attribute from WPBakery
    $items = array();
    if ( function_exists( 'vc_param_group_parse_atts' ) ) {
        $items = vc_param_group_parse_atts( $atts['items'] );
    }
	$images_lightbox = $atts['images_lightbox'];

	$custom_css = '';
	if(isset($atts['custom_css'])){
		$custom_css = $atts['custom_css'];
	}

	$custom_heading = '';
	if(isset($atts['custom_heading'])){
		$custom_heading = $atts['custom_heading'];
	}

    if ( empty( $items ) || ! is_array( $items ) ) {
        return '';
    }

    ob_start();
    echo '<div class="nc_custom_grid my-repeater-items-wrapper '.$custom_css.'">';

    foreach ( $items as $item ) {
        $heading  = isset( $item['heading'] ) ? $item['heading'] : '';
        $subtitle = isset( $item['subtitle'] ) ? $item['subtitle'] : '';
        $image_id = isset( $item['image'] ) ? intval( $item['image'] ) : 0;
		

        // If image is selected: output only the image
        if ( $image_id ) {
            echo '<div class="custom_grid_item has-image">';
			if($images_lightbox) {
				echo '<a class="popup-screenshot" href="'.wp_get_attachment_image_url( $image_id, 'full' ).'">';
			}
            echo wp_get_attachment_image( $image_id, 'large' );
			if($images_lightbox) {
				echo '</a>';
			}
            echo '</div>';
        } else {
            // No image: output div with heading + subtitle
            if ( $heading !== '' || $subtitle !== '' ) {
                echo '<div class="custom_grid_item no-image">';
				echo '<div class="item_content">';
                if ( $heading !== '' ) {
                    echo '<'.$custom_heading.'>' . esc_html( $heading ) . '</'.$custom_heading.'>';
                }
                if ( $subtitle !== '' ) {
                    echo '<p>' . esc_html( $subtitle ) . '</p>';
                }
				echo '</div>';
                echo '</div>';
            }
        }
    }

    echo '</div>';

    return ob_get_clean();
}
add_shortcode( 'content_grid_box_custom', 'content_grid_box_custom_shortcode' );

//add custom search input text field to filter icon boxes
function filter_iconboxes_custom_shortcode($atts) {
	$a = shortcode_atts( array(
		'destination' => '',
		'label' => '',
		'id' => '',
		'inner_item_class' => 'nc_iconbox',
		'carousel_items_type' => ''
	), $atts );
	ob_start();

	if(isset($a['inner_item_class'])) {
		$inner_item_class = $a['inner_item_class'];
	}

	if(isset($a['carousel_items_type'])) {
		$carousel_items_type = $a['carousel_items_type'];
	}
	
	?>
	<div class="filter_iconboxes">
	<div class="input-outer">
		<div class="search-holder">
			<input type="text" data-destination="<?php if(isset($a['destination'])) {
				echo $a['destination'];
			} ?>" placeholder="<?php 
			if(isset($a['label'])) {
				echo $a['label'];
			}
			?>" id="<?php 
			if(isset($a['id'])) {
				echo $a['id'];
			} ?>">
		</div>
	</div>
	</div>
	<script>
		jQuery(document).ready(function () {
			var $carousel_items_type = '<?php echo $carousel_items_type; ?>';

			jQuery('#<?php echo $a['id']; ?>').keyup(function () {
				var curr_value = jQuery(this).val().toLowerCase();
				//console.log("curr_value: "+curr_value);

				jQuery('.<?php echo $a['destination']; ?> .<?php echo $inner_item_class; ?>').each(function () {
					var text = jQuery(this).text().trim().toLowerCase();
					//console.log("text: "+text);

					if (text.indexOf(curr_value) === -1) {

						if($carousel_items_type == 'multiple') {
							//console.log("Multiple.");
							jQuery(this).hide();

							/*
							if(!jQuery(this).parents('.owl-item').hasClass('active')) {
								jQuery(this).parents('.owl-item').hide();
							}
							*/

						} else {

							if(jQuery(this).parents('.owl-item').length) {
								jQuery(this).parents('.owl-item').hide();
							} else {
								jQuery(this).hide();
							}

						}

						
						
					} else {
						//found, is showing 

						if($carousel_items_type == 'multiple') {
							//console.log("Multiple.");
							jQuery(this).show();


							//cloning and showing it in the active item
							// remove old clones
    						jQuery(this).parents('.owl-stage').find('.owl-item.active .cloned').remove();

							var id = jQuery(this).attr('id');
							if(!jQuery(this).parents('.owl-stage').find('.owl-item.active').find('#'+id).length) {
								if(!jQuery(this).hasClass('cloned')) {
									if(!jQuery(this).parents('.owl-item').hasClass('active')) {
										//console.log("cloning..");

										

										var cloned = jQuery(this).clone();
										cloned.addClass('cloned');
										//console.log(cloned);

										jQuery(this).parents('.owl-stage').find('.owl-item.active .media_coverage_posts').append(cloned);
									}
								}
							}
							

							
						} else {

							if(jQuery(this).parents('.owl-item').length) {
								jQuery(this).parents('.owl-item').show();
							} else {
								jQuery(this).show();
							}

						}

						
					}


				});
			});
		});
	</script>
<?php
	return ob_get_clean();
}
add_shortcode( 'filter_iconboxes', 'filter_iconboxes_custom_shortcode' );


//add custom select field to filter tutorials
function filter_tutorials_custom_shortcode($atts) {
	$a = shortcode_atts( array(
		'destination' => ''
	), $atts );
	ob_start();

	if(isset($a['inner_item_class'])) {
		$inner_item_class = $a['inner_item_class'];
	}
	
	?>
	<div class="filter_tutorials filter_iconboxes">
	<div class="input-outer">
		<div class="search-holder">
			<select name="filter_tutorials" id="filter_tutorials">
				<option value="all"><?php echo __('Show all tutorials','nextcloud'); ?></option>
				<option value="development_environment_tutorial"><?php echo __('Setting up a development environment','nextcloud'); ?></option>
				<option value="php_apps_tutorial"><?php echo __('Tutorials Php apps','nextcloud'); ?></option>
				<option value="app_api_apps_tutorial"><?php echo __('Tutorials AppAPI apps','nextcloud'); ?></option>
			</select>
		</div>
	</div>
	</div>
	<script>
		jQuery(document).ready(function () {

			jQuery('.development_environment_tutorials_desc_row').hide(); 

			jQuery('#filter_tutorials').change(function () {
				var curr_value = jQuery(this).val();
				console.log(curr_value);
				

				if(curr_value == 'all') {
					jQuery('.app_tutorials_row .nc_iconbox.tutorial').each(function(){
						//show all
						jQuery(this).show();
						jQuery('.development_environment_tutorials_desc_row').hide(); 
					});
				} else {

					jQuery('.app_tutorials_row .nc_iconbox.tutorial').each(function () {
					var thisClass = jQuery(this).attr('class');
					console.log('Classes: '+thisClass);

					if (thisClass.indexOf(curr_value) === -1) {
						//not found, hide
						jQuery(this).hide();

						if(curr_value == 'development_environment_tutorial') {
							jQuery('.development_environment_tutorials_desc_row').show(); 
						} else {
							jQuery('.development_environment_tutorials_desc_row').hide(); 
						}

					} else {
						jQuery(this).show();
					}
				});
				}

				

			});
		});
	</script>
<?php
	return ob_get_clean();
}
add_shortcode( 'filter_tutorials', 'filter_tutorials_custom_shortcode' );



//WPBakery: Meetup element + shortcode
add_action('vc_before_init', function () {

  // If WPBakery isn't active, bail.
  if ( ! function_exists('vc_map') ) return;

  vc_map([
    'name'        => __('Meetup', 'nextcloud'),
    'base'        => 'meetup',
    'icon'        => 'icon-wpb-images-stack', // optional
    'category'    => __('Content', 'nextcloud'),
    'description' => __('Meetup card with image, text, date and link', 'nextcloud'),
    'params'      => [
      [
        'type'        => 'attach_image',
        'heading'     => __('Image', ''),
        'param_name'  => 'image_id',
        'description' => __('Select an image for the meetup.', ''),
      ],
      [
        'type'       => 'textfield',
        'heading'    => __('Headline', ''),
        'param_name' => 'headline',
        'admin_label'=> true,
      ],
      [
        // This creates the “Text editor” content area in the element.
        'type'        => 'textarea_html',
        'heading'     => __('Text', ''),
        'param_name'  => 'content',
      ],
      [
        // Uses your existing custom VC param type "datetime"
        'type'       => 'datetime',
        'heading'    => __('Meetup date', ''),
        'param_name' => 'meetup_date',
        'description'=> __('Pick the meetup date/time.', ''),
      ],

	  [
        // Uses your existing custom VC param type "datetime"
        'type'       => 'textfield',
        'heading'    => __('Custom meetup date', ''),
        'param_name' => 'meetup_date_custom',
        'description'=> __('Meetup date/time.', ''),
      ],

		[
        // Uses your existing custom VC param type "datetime"
        'type'       => 'textfield',
        'heading'    => __('Meetup location', ''),
        'param_name' => 'meetup_location',
        'description'=> __('Location of the meetup', ''),
      	],
	  
      [
        'type'       => 'vc_link',
        'heading'    => __('Link', ''),
        'param_name' => 'link',
      ],
    ],
  ]);
});

/**
 * 2) Shortcode output
 */
add_shortcode('meetup', function ($atts, $content = null) {

  $atts = shortcode_atts([
    'image_id'    => '',
    'headline'    => '',
    'meetup_date' => '',
	'meetup_date_custom' => '',
	'meetup_location' => '',
    'link'        => '',
  ], $atts, 'meetup');

  $headline    = sanitize_text_field($atts['headline']);
  $meetup_date = sanitize_text_field($atts['meetup_date']); // keep as string; format as you like
  $meetup_date_custom    = sanitize_text_field($atts['meetup_date_custom']);
  $meetup_location    = sanitize_text_field($atts['meetup_location']);

  $content     = wpautop( do_shortcode( wp_kses_post($content) ) );

  // Image HTML
  $img_html = '';
  $image_id = absint($atts['image_id']);
  if ( $image_id ) {
    $img_html = wp_get_attachment_image($image_id, 'large', false, [
      'class' => 'meetup__image',
      'loading' => 'lazy',
    ]);
  }

  // Link parsing from vc_link
  $link_data = function_exists('vc_build_link') ? vc_build_link($atts['link']) : [];
  $url    = !empty($link_data['url'])    ? esc_url($link_data['url']) : '';
  $title  = !empty($link_data['title'])  ? esc_html($link_data['title']) : '';
  $target = !empty($link_data['target']) ? esc_attr($link_data['target']) : '';
  $rel    = !empty($link_data['rel'])    ? esc_attr($link_data['rel']) : '';

  // Button label fallback
  if ( $url && $title === '' ) {
    $title = __('Attend this meetup', 'nextcloud');
  }

  ob_start(); ?>

    <div class="nc_meetup">
      <?php if ( $img_html ): ?>
        <div class="meetup_cover"><?php echo $img_html; ?></div>
      <?php endif; ?>

      <div class="meetup_container">
        <?php if ( $headline ): ?>
          <h4 class="meetup__headline"><?php echo esc_html($headline); ?></h4>
        <?php endif; ?>

        <?php if ( $meetup_date || $meetup_date_custom || $meetup_location): ?>
			<div class="meetup_details">

			<ul class="webinar_details event_details">
						<li class="with_svg">
						<svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" version="1.1" viewBox="0 0 24 24">
							<path class="st0" d="M5,22c-.55,0-1.021-.196-1.413-.588-.392-.392-.588-.863-.588-1.413V6c0-.55.196-1.021.588-1.413.392-.392.863-.588,1.413-.588h1V2h2v2h8V2h2v2h1c.55,0,1.021.196,1.413.588s.588.863.588,1.413v14c0,.55-.196,1.021-.588,1.413s-.863.588-1.413.588H5ZM5,20h14v-10H5v10ZM5,8h14v-2H5v2ZM5,8v-2,2ZM12,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288c.192.192.288.429.288.713s-.096.521-.288.713c-.192.192-.429.288-.713.288ZM8,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713-.429.288-.713.288ZM16,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713-.429.288-.713.288ZM12,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288c.192.192.288.429.288.713s-.096.521-.288.713c-.192.192-.429.288-.713.288ZM8,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713c-.192.192-.429.288-.713.288ZM16,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713c-.192.192-.429.288-.713.288Z"></path>
						</svg>
							<?php 
							if($meetup_date_custom) {
								echo $meetup_date_custom;
							} else {
								echo esc_html($meetup_date);
							}
							?>				
						</li>

						<li class="with_svg">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M480-480q33 0 56.5-23.5T560-560q0-33-23.5-56.5T480-640q-33 0-56.5 23.5T400-560q0 33 23.5 56.5T480-480Zm0 294q122-112 181-203.5T720-552q0-109-69.5-178.5T480-800q-101 0-170.5 69.5T240-552q0 71 59 162.5T480-186Zm0 106Q319-217 239.5-334.5T160-552q0-150 96.5-239T480-880q127 0 223.5 89T800-552q0 100-79.5 217.5T480-80Zm0-480Z"></path></svg>
							<?php echo esc_html($meetup_location); ?>		
						</li>

					</ul>

          </div>
        <?php endif; ?>

        <?php if ( trim(wp_strip_all_tags($content)) !== '' ): ?>
          <div class="meetup__content"><?php echo $content; ?></div>
        <?php endif; ?>

        <?php if ( $url ): ?>
          <div class="meetup__links">

		  <div class="open-popup-link">
			<a class="register c-btn" href="#register_meetup">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
			<path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h357l-80 80H200v560h560v-278l80-80v358q0 33-23.5 56.5T760-120H200Zm280-360ZM360-360v-170l367-367q12-12 27-18t30-6q16 0 30.5 6t26.5 18l56 57q11 12 17 26.5t6 29.5q0 15-5.5 29.5T897-728L530-360H360Zm481-424-56-56 56 56ZM440-440h56l232-232-28-28-29-28-231 231v57Zm260-260-29-28 29 28 28 28-28-28Z"></path>
			</svg>
			<span>
			<?php echo $title; ?>
			</span>
			</a>
		  </div>
            
			<div class="btn btn_see_more">
			<a class="see_more"
               href="<?php echo $url; ?>"
               <?php echo $target ? 'target="'.$target.'"' : ''; ?>
               <?php echo $rel ? 'rel="'.$rel.'"' : ''; ?>>
				<span>
				<?php echo __('More about this meetup','nextcloud'); ?>
				</span>
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
					<path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h560v-280h80v280q0 33-23.5 56.5T760-120H200Zm188-212-56-56 372-372H560v-80h280v280h-80v-144L388-332Z"></path>
				</svg>
            </a>
			</div>
		  	
          </div>
        <?php endif; ?>
      </div>
    </div>
  <?php
  return ob_get_clean();
});


//get pricing from settings page using a shortcode
add_shortcode('nc_price', function ($atts) {
	$atts = shortcode_atts([
		'product' => '',
		'users'   => '',
		'plan'    => 'standard',
	], $atts);

	if (!$atts['product'] || $atts['users'] === '') {
		return '';
	}

	// WPML current language (fallback to constant if needed)
	$lang = function_exists('apply_filters')
		? apply_filters('wpml_current_language', null)
		: null;

	if (!$lang && defined('ICL_LANGUAGE_CODE')) {
		$lang = ICL_LANGUAGE_CODE;
	}
	$lang = strtolower((string) $lang);

	$field = ($atts['product'] === 'files')
		? 'files_plans_prices'
		: (($atts['product'] === 'talk') ? 'talk_plans_prices' : '');

	if (!$field) {
		return '';
	}

	$rows = get_field($field, 'option'); // repeater rows as array
	if (empty($rows) || !is_array($rows)) {
		return '';
	}

	$plan  = trim(strtolower((string) $atts['plan']));
	$users = (string) $atts['users'];

	foreach ($rows as $row) {
		if (!isset($row['users'])) continue;

		if ((string) $row['users'] === $users) {
			$value = isset($row[$plan]) ? (string) $row[$plan] : '';
			// Replace dot with comma in German
			if ($lang === 'de') {
				$value = str_replace('.', ',', $value);
			}
			return $value;
		}
	}

	return '';
});



/**
 * WPBakery Timeline Carousel Element
 */
function wpb_timeline_carousel_shortcode($atts) {
	$atts = shortcode_atts(array(
		'el_id'    => '',
		'el_class' => '',
		'events'   => '',
		'custom_items_no' => ''
	), $atts, 'wpb_timeline_carousel');

	$el_id    = sanitize_html_class($atts['el_id']); // IDs can contain more than class chars, but this is safe. If you want looser, see note below.
	$el_class = sanitize_text_field($atts['el_class']);

	$events = array();
	if (!empty($atts['events'])) {
		$events = vc_param_group_parse_atts($atts['events']);
		if (!is_array($events)) $events = array();
	}
	
	$custom_items_no = 3; // set default
	if(isset($atts['custom_items_no']) && $atts['custom_items_no'] != '' ) {
		$custom_items_no = $atts['custom_items_no'];
	}

	ob_start();
	?>
	<div id="<?php echo esc_attr($el_id); ?>" class="<?php echo esc_attr($el_class); ?>">
		<div class="timeline timeline-carousel owl-carousel owl-theme">
			<?php foreach ($events as $event) :
				$year       = isset($event['year']) ? trim($event['year']) : '';
				$title      = isset($event['title']) ? $event['title'] : '';
				$content = isset($event['content']) ? base64_decode($event['content']) : '';
				$custom_css = isset($event['custom_css']) ? trim($event['custom_css']) : '';
				$image_id = !empty($event['image_id']) ? (int) $event['image_id'] : 0;

				?>
				<div class="event <?php echo $custom_css; ?> " data-date="<?php echo esc_html($year); ?>">
					<?php if ($year !== '') : ?>
						<span class="t_year"><?php echo esc_html($year); ?></span>
					<?php endif; ?>

					<?php
					if ($image_id) {
						/*
						echo '<div class="event-image left_img">';
						echo wp_get_attachment_image($image_id, 'large'); // or 'thumbnail', 'large', 'full'
						echo '</div>';
						*/

						//echo '<div class="event_inner">';
                		echo wp_get_attachment_image($image_id, 'large');
					} ?>

					<?php if ($title !== '') : ?>
						<h3><?php echo esc_html($title); ?></h3>
					<?php endif; ?>

					<?php if ($content !== '') : ?>
						<p><?php 
							echo do_shortcode(rawurldecode($content));
							?></p>
					<?php endif; ?>

					<?php
					if ($image_id) {
						//echo '</div>';
					} ?>

				</div>
			<?php endforeach; ?>
		</div>
	</div>

	<script>
    jQuery(document).ready(function ($) {
		$('.timeline-carousel').owlCarousel({
							loop:false,
							autoplay: false,
							margin:30,
							dots: false,
							nav:true,
							responsive:{
								0:{
									items:1
								},
								600:{
									items:2
								},
								1000:{
									items: <?php echo $custom_items_no; ?>
								}
							},
							navText : ['<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 14.299 22.409"><path fill="#0082c9" class="st0" d="M11.25,21.205L1.25,11.205,11.25,1.205l1.8,1.8L4.85,11.205l8.2,8.2-1.8,1.8h0Z"/></svg>','<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 14.299 22.409"><path fill="#0082c9" class="st0" d="M3.05,21.205l-1.8-1.8,8.2-8.2L1.25,3.005l1.8-1.8,10,10L3.05,21.205h0Z"/></svg>'],
							onInitialized: function (event) {
								// Remove role="presentation" from arrows
								$('.owl-prev, .owl-next').removeAttr('role');
								$('.owl-prev, .owl-next').attr('tabindex', 0);

								// Move .owl-nav before the .owl-stage-outer (which contains the items)
								const $owl = $(event.target);
								const $nav = $owl.find('.owl-nav');
								const $stageOuter = $owl.find('.owl-stage-outer');
								$nav.insertBefore($stageOuter);
							}
						});
		});
	</script>

	<?php
	return ob_get_clean();
}
add_shortcode('wpb_timeline_carousel', 'wpb_timeline_carousel_shortcode');

/**
 * Register WPBakery element
 */
function nc_timeline_carousel_vc_map() {
	if (!defined('WPB_VC_VERSION')) return;

	vc_map(array(
		'name'        => __('Timeline Carousel', 'nextcloud'),
		'base'        => 'wpb_timeline_carousel',
		'category'    => __('Content', 'js_composer'),
		'icon'        => 'icon-wpb-ui-accordion',
		'description' => __('Horizontal timeline with repeater events', 'nextcloud'),
		'params'      => array(
			array(
				'type'        => 'textfield',
				'heading'     => __('Element ID', 'nextcloud'),
				'param_name'  => 'el_id',
				'value'       => '',
				'description' => __('Wrapper ID attribute (e.g. timeline-content).', 'nextcloud'),
			),
			array(
				'type'        => 'textfield',
				'heading'     => __('Extra class name', 'nextcloud'),
				'param_name'  => 'el_class',
				'value'       => '',
				'description' => __('Add custom CSS classes for the wrapper.', 'nextcloud'),
			),
			array(
				'type'       => 'param_group',
				'heading'    => __('Events', 'nextcloud'),
				'param_name' => 'events',
				'params'     => array(
					array(
						'type'        => 'textfield',
						'heading'     => __('Year', 'nextcloud'),
						'param_name'  => 'year',
						'value'       => '',
						'admin_label' => true,
						'description' => __('Shown in <span class="t_year">', 'nextcloud'),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __('Title', 'nextcloud'),
						'param_name' => 'title',
						'admin_label' => true,
						'value'      => '',
					),
					array(
						'type'        => 'textarea_raw_html',
						'heading'     => __('Content (HTML allowed)', 'nextcloud'),
						'param_name'  => 'content',
						'value'       => '',
						'description' => __('This will be placed inside the <p> tag.', 'nextcloud'),
					),

					array(
						'type'        => 'attach_image',
						'heading'     => __('Image', 'nextcloud'),
						'param_name'  => 'image_id',
						'description' => __('Select an image for this event.', 'nextcloud'),
					),

					array(
						'type'        => 'textfield',
						'heading'     => __('Custom CSS', 'nextcloud'),
						'param_name'  => 'custom_css',
						'value'       => '',
						'description' => __('', 'nextcloud'),
					),
				),
			),

			array(
				'type'        => 'textfield',
				'heading'     => __('Custom items number', 'nextcloud'),
				'param_name'  => 'custom_items_no',
				'value'       => '',
				'description' => __('', 'nextcloud'),
			),

		),
	));
}
add_action('vc_before_init', 'nc_timeline_carousel_vc_map');


/**
 * Shortcode Disclaimer content
*/
function wpb_content_block_shortcode($atts, $content = null) {
	$atts = shortcode_atts(array(
		'el_id'    => '',
		'el_class' => '',
	), $atts, 'nc_disclaimer_content_block');

	$el_id    = trim($atts['el_id']);
	$el_class = trim($atts['el_class']);

	$attr_id = $el_id !== '' ? ' id="' . esc_attr($el_id) . '"' : '';
	$attr_class = $el_class !== '' ? ' class="' . esc_attr($el_class) . '"' : '';

	// WPBakery stores textarea_html content in $content (not in $atts)
	$output = '<div' . $attr_id . $attr_class . '>';
	$output .= do_shortcode($content);
	$output .= '</div>';

	return $output;
}
add_shortcode('nc_disclaimer_content_block', 'wpb_content_block_shortcode');

/**
 * Register WPBakery element
 */
add_action('vc_before_init', function () {
	if (!defined('WPB_VC_VERSION')) return;

	vc_map(array(
		'name'        => __('Disclaimer content Block', 'nextcloud'),
		'base'        => 'nc_disclaimer_content_block',
		'category'    => __('Content', 'js_composer'),
		'icon'        => 'icon-wpb-ui-text',
		'description' => __('Wrapper with ID/class and WYSIWYG content', 'nextcloud'),
		'params'      => array(
			array(
				'type'        => 'textfield',
				'heading'     => __('Element ID', 'nextcloud'),
				'param_name'  => 'el_id',
				'admin_label' => true,
				'description' => __('Optional. Example: my-section', 'nextcloud'),
			),
			array(
				'type'        => 'textfield',
				'heading'     => __('Extra class name', 'nextcloud'),
				'param_name'  => 'el_class',
				'admin_label' => true,
				'description' => __('Optional. Example: my-custom-class', 'nextcloud'),
			),
			array(
				'type'        => 'textarea_html',
				'heading'     => __('Content', 'nextcloud'),
				'param_name'  => 'content', // IMPORTANT: must be "content"
				'value'       => '',
			),
		),
	));
});



/**
 * Predefined Talk comparison features.
*/
function nc_talk_platform_comparison_features() {
	return array(
		'hostable_on_premises'                  => __('Hostable on-premises', 'nextcloud'),
		'open_source'                           => __('Open source', 'nextcloud'),
		'desktop_client'                        => __('Desktop client', 'nextcloud'),
		'mobile_client'                         => __('Mobile client', 'nextcloud'),
		'phone_dial_in'                         => __('Phone dial-in', 'nextcloud'),
		'phone_dial_out'                        => __('Phone dial-out', 'nextcloud'),
		'breakout_rooms'                        => __('Breakout rooms', 'nextcloud'),
		'stream_to_youtube'                     => __('Stream to YouTube', 'nextcloud'),
		'search_messages'                       => __('Search messages', 'nextcloud'),
		'filter_conversations'                  => __('Filter conversations', 'nextcloud'),
		'ai_assistant_features'                 => __('AI Assistant features', 'nextcloud'),
		'note_taking_editing'                   => __('Note-taking/editing', 'nextcloud'),
		'office_document_editing'               => __('Office document editing', 'nextcloud'),
		'public_office_document_editing'        => __('Public office document editing (edit via public link)', 'nextcloud'),
		'accessibility_features'                => __('Accessibility features (screen reader support, dyslectic font, high contrast mode)', 'nextcloud'),
		'federation'                            => __('Federation', 'nextcloud'),
		'push_notifications'                    => __('Push notifications', 'nextcloud'),
		'together_mode'                         => __('Together mode', 'nextcloud'),
		'message_reminders'                     => __('Message reminders', 'nextcloud'),
		'silent_messages'                       => __('Silent messages', 'nextcloud'),
		'call_recording'                        => __('Call recording', 'nextcloud'),
		'moderation_lobby'                      => __('Moderation & lobby', 'nextcloud'),
		'voting_polls'                          => __('Voting/polls', 'nextcloud'),
		'background_blur_custom_backgrounds'    => __('Background blur & custom backgrounds', 'nextcloud'),
		'rich_interactive_widgets'              => __('Rich interactive widgets', 'nextcloud'),
		'view_time_zones_local_time'            => __('View time zones or local time of colleagues', 'nextcloud'),
		'outlook_integration_send_invitations'  => __('Outlook integration to send invitations', 'nextcloud'),
		'note_to_self'                          => __('Note to self', 'nextcloud'),
		'filters_in_video_calls'                => __('Filters in video calls', 'nextcloud'),
		'quick_slash_commands'                  => __('Quick slash commands', 'nextcloud'),
		'bots'                                  => __('Bots', 'nextcloud'),
		'threads'                               => __('Threads', 'nextcloud'),
		'format_messages'                       => __('Format messages', 'nextcloud'),
		'chat_reactions_emojis'                 => __('Chat reactions (emojis)', 'nextcloud'),
		'animated_reactions_in_call'            => __('Animated reactions in call', 'nextcloud'),
		'message_drafts'                        => __('Message drafts', 'nextcloud'),
		'waiting_music'                         => __('Waiting music', 'nextcloud'),
		'generate_transcript'                   => __('Generate transcript', 'nextcloud'),
		'live_captioning'                       => __('Live captioning', 'nextcloud'),
		'live_translations'                     => __('Live translations', 'nextcloud'),
		'integration_with_calendar_app'         => __('Integration with calendar app', 'nextcloud'),
		'sidebar_view_in_calls'                 => __('Sidebar view in calls', 'nextcloud'),
		'profile_cards'                         => __('Profile cards', 'nextcloud'),
		'anonymous_access'                      => __('Anonymous access', 'nextcloud'),
		'guest_accounts'                        => __('Guest accounts', 'nextcloud'),
		'user_status'                           => __('User status', 'nextcloud'),
		'scheduled_messages'                    => __('Scheduled messages', 'nextcloud'),
	);
}

/**
 * Build the param_group fields for each platform.
 */
function nc_talk_platform_comparison_platform_params() {
	$params = array(
		array(
			'type'        => 'textfield',
			'heading'     => __('Platform Name', 'nextcloud'),
			'param_name'  => 'name',
			'admin_label' => true,
		),
		array(
			'type'       => 'attach_image',
			'heading'    => __('Logo', 'nextcloud'),
			'param_name' => 'logo',
		),
		array(
			'type'        => 'textfield',
			'heading'     => __('Custom CSS class', 'nextcloud'),
			'param_name'  => 'custom_class',
			'description' => __('Optional class added to the logo image', 'nextcloud'),
		)
	);

	foreach (nc_talk_platform_comparison_features() as $key => $label) {
		$params[] = array(
			'type'       => 'dropdown',
			'heading'    => $label,
			'param_name' => $key,
			'value'      => array(
				__('No', 'nextcloud')            => 'no',
				__('Yes', 'nextcloud')           => 'yes',
				__('Optional', 'nextcloud')       => 'optional',
				__('Limited info', 'nextcloud') => 'limited-info'
			),
			'std' => 'yes',
		);

		$params[] = array(
			'type'       => 'checkbox',
			'heading'    => '',
			'param_name' => $key . '_has_label',
			'value'      => array(
				__('Custom label?', 'nextcloud') => 'yes',
			),
		);

		$params[] = array(
			'type'        => 'textfield',
			'heading'     => __('Custom label', 'nextcloud'),
			'param_name'  => $key . '_label',
			'description' => __('Optional label, shown on hover', 'nextcloud'),
			'dependency'  => array(
				'element' => $key . '_has_label',
				'value'   => array('yes'),
			),
		);


		$params[] = array(
			'type'       => 'checkbox',
			'heading'    => '',
			'param_name' => $key . '_has_tooltip',
			'value'      => array(
				__('Has tooltip?', 'nextcloud') => 'yes',
			),
		);

		$params[] = array(
			'type'        => 'textfield',
			'heading'     => __('Tooltip', 'nextcloud'),
			'param_name'  => $key . '_tooltip',
			'description' => __('Optional tooltip, shown on hover', 'nextcloud'),
			'dependency'  => array(
				'element' => $key . '_has_tooltip',
				'value'   => array('yes'),
			),
		);



	}

	return $params;
}

/**
 * Register WPBakery element.
 */
add_action('vc_before_init', 'nc_register_talk_platform_comparison_shortcode');
function nc_register_talk_platform_comparison_shortcode() {
	if (!function_exists('vc_map')) {
		return;
	}

	vc_map(array(
		'name'        => __('Talk platforms Comparison', 'nextcloud'),
		'base'        => 'talk_platforms_comparison',
		'icon'        => 'icon-wpb-ui-tab-content',
		'category'    => __('Nextcloud', 'nextcloud'),
		'description' => __('Comparison table with predefined features and repeatable platforms.', 'nextcloud'),
		'params'      => array(
			array(
				'type'        => 'param_group',
				'heading'     => __('Platforms', 'nextcloud'),
				'param_name'  => 'platforms',
				'params'      => nc_talk_platform_comparison_platform_params(),
				'description' => __('Add one row per platform and set the status for each predefined feature.', 'nextcloud'),
			),
		),
	));
}

/**
 * Shortcode renderer.
 */
add_shortcode('talk_platforms_comparison', 'nc_render_talk_platforms_comparison_shortcode');
function nc_render_talk_platforms_comparison_shortcode($atts) {
	$atts = shortcode_atts(array(
		'platforms' => '',
	), $atts, 'talk_platforms_comparison');

	if (!function_exists('vc_param_group_parse_atts')) {
		return '';
	}

	$platforms = vc_param_group_parse_atts($atts['platforms']);
	$features  = nc_talk_platform_comparison_features();

	if (empty($platforms) || empty($features) || !is_array($platforms)) {
		return '';
	}

	ob_start();
	?>
	<div class="comparison_table_container">

		<div class="filtering_buttons">
			<button class="reset" tabindex="0" type="reset"><?php echo esc_html__('Reset', 'nextcloud'); ?></button>
			<button class="filter" tabindex="0" disabled="true"><?php echo esc_html__('Filter', 'nextcloud'); ?></button>
		</div>

		<div class="table_move_arrows" style="display:none;">
			<a href="#" class="no-scroll arrow arrow-left"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
			<a href="#" class="no-scroll arrow arrow-right"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
		</div>

		<div class="comparison_table_container_inner">
			<table class="comparison_table">
				<thead>
					<tr class="platform_logos">
						<th></th>
						<?php foreach ($platforms as $platform) : ?>
							<?php
							$name    = isset($platform['name']) ? $platform['name'] : '';
							$logo_id = isset($platform['logo']) ? absint($platform['logo']) : 0;
							$custom_class = isset($platform['custom_class']) ? $platform['custom_class'] : '';

							?>
							<th class="">
								<div class="check" tabindex="0"></div>
								<?php
								if ($logo_id) {
									$logo_url = wp_get_attachment_image_url($logo_id, 'full');
									echo '<img alt="' . esc_attr($name) . '" src="' . esc_url($logo_url) . '" class="' . esc_attr($custom_class) . '" />';
								}
								?>
							</th>
						<?php endforeach; ?>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($features as $feature_key => $feature_label) : ?>
						<tr>
							<td class=""><?php echo esc_html($feature_label); ?></td>

							<?php foreach ($platforms as $platform) : ?>
								<?php
								$status           = isset($platform[$feature_key]) ? $platform[$feature_key] : 'no';
								$has_custom_label = !empty($platform[$feature_key . '_has_label']);
								$custom_label     = isset($platform[$feature_key . '_label']) ? trim($platform[$feature_key . '_label']) : '';
								$has_tooltip = !empty($platform[$feature_key . '_has_tooltip']);
								$tooltip     = isset($platform[$feature_key . '_tooltip']) ? trim($platform[$feature_key . '_tooltip']) : '';

								echo '<td>';

								switch ($status) {
									case 'yes':
										?>
										<div class="fa fa-check-circle" title="<?php echo esc_attr__('Included', 'nextcloud'); ?>" aria-hidden="true"></div>
										<span class="screen-reader-text"><?php echo esc_html__('Included', 'nextcloud'); ?></span>
										<?php
										break;

									case 'optional':
										?>
										<div class="fa fa-check-circle optional" title="title="<?php echo esc_attr($has_custom_label && '' !== $custom_label ? $custom_label : __('Optional', 'nextcloud')); ?>" aria-hidden="true"></div>
										<span class="screen-reader-text">
											<?php echo esc_attr($has_custom_label && '' !== $custom_label ? $custom_label : __('Optional', 'nextcloud')); ?>
										</span>
										<?php
										break;

									case 'limited-info':
										?>
										<?php echo esc_attr($has_custom_label && '' !== $custom_label ? $custom_label : __('Limited info', 'nextcloud')); ?>
										<?php
									break;

									case 'no':
									default:
										?>
										<div class="fa fa-times-circle" title="<?php echo esc_attr__('Not included', 'nextcloud'); ?>" aria-hidden="true"></div>
										<span class="screen-reader-text"><?php echo esc_html__('Not included', 'nextcloud'); ?></span>
										<?php
										break;
								}


										if ($has_custom_label && '' !== $custom_label) : ?>
											<span class="block"><?php echo esc_html($custom_label); ?></span>
										<?php endif; ?>
										
										<?php if ($has_tooltip && '' !== $tooltip) : ?>
											<span class="tooltip"><?php echo esc_html($tooltip); ?></span>
											<span class="show_tooltip"><?php echo esc_html__('See more', 'nextcloud'); ?></span>
										<?php endif;

								echo '</td>';
								?>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php

	return ob_get_clean();
}


function my_test_shortcode($atts) {
    ob_start();
    // Your PHP logic here
    //echo "Current time: " . date("Y-m-d H:i:s");

	$browser_lang = 'en'; // default browser language
    if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $browser_lang = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']);
            $browser_lang = str_replace('_', '-', $browser_lang);
			$browser_lang  = strtolower(substr($browser_lang, 0, 2));
    }
    
	echo "Lang: ".$browser_lang;



    return ob_get_clean();
}
add_shortcode('my_test', 'my_test_shortcode');



/**
 * Predefined comparison features.
*/
function nc_platform_comparison_features() {

	return array(

		'main_features' => array(
			__('Main features', 'nextcloud'),
			array(
				'licensing' => __('Licensing', 'nextcloud'),
				'unlimited_storage_files' => __('Unlimited storage and amount of files', 'nextcloud'),
				'large_file_support' => __('Large file support', 'nextcloud'),
				'self_hosted_on_premises' => __('Self hosted / on premises', 'nextcloud'),
				'global_scalability' => __('Global scalability', 'nextcloud'),
			)
		),

		'clients' => array(
			__('Clients', 'nextcloud'),
			array(
				'mobile_clients' => __('Mobile clients', 'nextcloud'),
				'auto_upload' => __('Auto upload image/video/other files', 'nextcloud'),
				'desktop_clients' => __('Desktop clients', 'nextcloud'),
				'lan_sync' => __('LAN synchronization', 'nextcloud'),
				'extensible_apps' => __('Extensible with apps', 'nextcloud'),
			)
		),

		'server_features' => array(
			__('Server features', 'nextcloud'),
			array(
				'unified_search' => __('Unified search', 'nextcloud'),
				'file_versioning' => __('File versioning', 'nextcloud'),
				'file_locking' => __('File locking / checkout', 'nextcloud'),
				'multiple_link_shares' => __('Multiple link shares', 'nextcloud'),
				'share_note' => __('Share note', 'nextcloud'),
				'dashboard' => __('Dashboard', 'nextcloud'),
				'file_preview' => __('View PDF, Images, Video, Gallery', 'nextcloud'),
				'integrated_chat' => __('Integrated Audio/Video/Text chat', 'nextcloud'),
				'integrated_groupware' => __('Integrated Groupware (Calendar/Contacts/Mail/Kanban)', 'nextcloud'),
				'mobile_calendar_contacts' => __('Mobile calendar/contact integration', 'nextcloud'),
				'online_office' => __('Online Office web / Mobile apps', 'nextcloud'),
				'knowledge_management' => __('Knowledge management', 'nextcloud'),
				'forms_surveys' => __('Forms and surveys', 'nextcloud'),
				'user_managed_groups' => __('User-managed groups', 'nextcloud'),
				'file_drop' => __('File Drop (customer file upload)', 'nextcloud'),
				'inter_server_sharing' => __('Inter-server sharing', 'nextcloud'),
				'workspace' => __('Workspace', 'nextcloud'),
			)
		),

		'accessibility' => array(
			__('Accessibility', 'nextcloud'),
			array(
				'keyboard_screen_reader' => __('Keyboard / screen reader support', 'nextcloud'),
				'wcag_22' => __('WCAG 2.2 support', 'nextcloud'),
				'dyslexia_font' => __('Dyslexia-friendly font', 'nextcloud'),
			)
		),

		'admin_features' => array(
			__('Admin features', 'nextcloud'),
			array(
				'acl' => __('Access control lists', 'nextcloud'),
				'workflow_automation' => __('Content workflow automation', 'nextcloud'),
				'approval_workflow' => __('Approval process workflow', 'nextcloud'),
				'document_signatures' => __('Cryptographic document signatures', 'nextcloud'),
				'script_execution' => __('Automatic script execution', 'nextcloud'),
				'impersonate_users' => __('Impersonate users', 'nextcloud'),
				'real_time_notifications' => __('Real-time notifications', 'nextcloud'),
				'monitoring_api' => __('Monitoring web/API interface', 'nextcloud'),
				'data_retention' => __('Data retention policy support', 'nextcloud'),
				'long_term_support' => __('Long term support (5–10 years)', 'nextcloud'),
				'branding' => __('Branding', 'nextcloud'),
			)
		),

		'compliance_features' => array(
			__('Compliance features', 'nextcloud'),
			array(
				'audit_trail' => __('Audit trail', 'nextcloud'),
				'imprint_privacy_links' => __('Imprint and privacy links', 'nextcloud'),
				'data_requests_deletion' => __('Built-in data requests / account deletion', 'nextcloud'),
				'terms_of_service' => __('Terms-of-service', 'nextcloud'),
			)
		),

		'security_features' => array(
			__('Security features', 'nextcloud'),
			array(
				'server_side_encryption' => __('Server-side encryption', 'nextcloud'),
				'end_to_end_encryption' => __('Client-side / end-to-end encryption', 'nextcloud'),
				'video_verification' => __('Video verification', 'nextcloud'),
				'brute_force_protection' => __('Brute force hacking protection', 'nextcloud'),
				'nist_password_policy' => __('NiST compliant password policy', 'nextcloud'),
				'csp_3' => __('Web UI secured with CSP 3.0', 'nextcloud'),
				'same_site_cookie' => __('Same-site cookie attribute', 'nextcloud'),
				'file_access_control' => __('File access control', 'nextcloud'),
				'app_access_rights' => __('App access rights', 'nextcloud'),
			)
		),

		'authentication' => array(
			__('Authentication', 'nextcloud'),
			array(
				'two_factor_auth' => __('Enforceable 2-factor authentication', 'nextcloud'),
				'suspicious_login_detection' => __('ML-based suspicious login detection', 'nextcloud'),
				'ldap_ad' => __('LDAP / AD', 'nextcloud'),
				'saml' => __('Native SAML', 'nextcloud'),
				'env_auth' => __('Auth via env variable', 'nextcloud'),
				'kerberos' => __('Kerberos', 'nextcloud'),
			)
		),

		'storage' => array(
			__('Storage', 'nextcloud'),
			array(
				'local_storage' => __('File storage (local / NFS)', 'nextcloud'),
				'object_storage' => __('Object storage (S3 / Swift)', 'nextcloud'),
				'cifs_windows' => __('CIFS / Windows shares', 'nextcloud'),
				'sharepoint' => __('SharePoint', 'nextcloud'),
				'extensible_storage' => __('Extensible storage', 'nextcloud'),
			)
		),

	);
}

/**
 * Build the param_group fields for each platform.
 */
function nc_platform_comparison_params() {

	$params = array(
		array(
			'type'        => 'textfield',
			'heading'     => __('Platform Name', 'nextcloud'),
			'param_name'  => 'name',
			'admin_label' => true,
		),
		array(
			'type'       => 'attach_image',
			'heading'    => __('Logo', 'nextcloud'),
			'param_name' => 'logo',
		),
		array(
			'type'        => 'textfield',
			'heading'     => __('Custom CSS class', 'nextcloud'),
			'param_name'  => 'custom_class',
			'description' => __('Optional class added to the logo image', 'nextcloud'),
		),
	);

	foreach (nc_platform_comparison_features() as $group_key => $group_array) {

		$group_label = isset($group_array[0]) ? $group_array[0] : '';
		$features    = isset($group_array[1]) && is_array($group_array[1]) ? $group_array[1] : array();
		$group_param_key = sanitize_key(str_replace('-', '_', trim($group_key)));

		
		$params[] = array(
			'type'       => 'vc_raw_html',
			'heading'    => $group_label,
			'holder' => 'div',
			'edit_field_class' => 'vc_col-xs-12 compare_group_section_title',
			'param_name' => $group_param_key. '_section_title',
			'value'      => '',
		);
		

		foreach ($features as $feature_key => $feature_label) {

			$param_key = sanitize_key(str_replace('-', '_', trim($feature_key)));

			$params[] = array(
				'type'       => 'dropdown',
				'heading'    => $feature_label,
				'edit_field_class' => 'vc_col-xs-12 platform_comparison_feature',
				'param_name' => $param_key . '_status',
				'value'      => array(
					__('No', 'nextcloud')           => 'no',
					__('Yes', 'nextcloud')          => 'yes',
					__('Optional', 'nextcloud')     => 'optional',
					__('Custom (only label shown)', 'nextcloud') => 'custom',
					__('Dollar sign', 'nextcloud') => 'dollar',
					__('Operating system', 'nextcloud') => 'operating_system',
					__('Multiple statuses', 'nextcloud') => 'multiple_icons'
				),
				'std' => 'no',
			);
			

			$params[] = array(
				'type'        => 'checkbox',
				'heading'     => __('Operating system icons', 'nextcloud'),
				'param_name'  => $param_key . '_os_icons',
				'description' => __('', 'nextcloud'),
				'value'      => array(
					__('iOS/iPhone', 'nextcloud') => 'apple',
					__('Android', 'nextcloud') => 'android',
					__('Windows', 'nextcloud') => 'windows',
					__('Linux', 'nextcloud') => 'linux'
				),

				'dependency'  => array(
					'element' => $param_key . '_status',
					'value'   => array('operating_system'),
				),
			);


			//multiple icons 

			$params[] = array(
				'type'        => 'dropdown',
				'heading'     => __('', 'nextcloud'),
				'param_name'  => $param_key . '_multiple_icons_1',
				'value'      => array(
					__('Default', 'nextcloud') => '',
					__('Yes', 'nextcloud') => 'yes',
					__('No', 'nextcloud') => 'no',
					__('Dollar sign', 'nextcloud') => 'dollar'
				),
				'edit_field_class' => 'vc_col-xs-3 multiple_icons_item',
				'dependency'  => array(
					'element' => $param_key . '_status',
					'value'   => array('multiple_icons'),
				),
			);

			$params[] = array(
				'type'        => 'dropdown',
				'heading'     => __('', 'nextcloud'),
				'param_name'  => $param_key . '_multiple_icons_2',
				'value'      => array(
					__('Default', 'nextcloud') => '',
					__('Yes', 'nextcloud') => 'yes',
					__('No', 'nextcloud') => 'no',
					__('Dollar sign', 'nextcloud') => 'dollar'
				),
				'edit_field_class' => 'vc_col-xs-3 multiple_icons_item',
				'dependency'  => array(
					'element' => $param_key . '_status',
					'value'   => array('multiple_icons'),
				),
			);


			$params[] = array(
				'type'        => 'dropdown',
				'heading'     => __('', 'nextcloud'),
				'param_name'  => $param_key . '_multiple_icons_3',
				'value'      => array(
					__('Default', 'nextcloud') => '',
					__('Yes', 'nextcloud') => 'yes',
					__('No', 'nextcloud') => 'no',
					__('Dollar sign', 'nextcloud') => 'dollar'
				),
				'edit_field_class' => 'vc_col-xs-3 multiple_icons_item',
				'dependency'  => array(
					'element' => $param_key . '_status',
					'value'   => array('multiple_icons'),
				),
			);
			$params[] = array(
				'type'        => 'dropdown',
				'heading'     => __('', 'nextcloud'),
				'param_name'  => $param_key . '_multiple_icons_4',
				'value'      => array(
					__('Default', 'nextcloud') => '',
					__('Yes', 'nextcloud') => 'yes',
					__('No', 'nextcloud') => 'no',
					__('Dollar sign', 'nextcloud') => 'dollar'
				),
				'edit_field_class' => 'vc_col-xs-3 multiple_icons_item',
				'dependency'  => array(
					'element' => $param_key . '_status',
					'value'   => array('multiple_icons'),
				),
			);

			$params[] = array(
				'type'       => 'checkbox',
				'heading'    => '',
				'edit_field_class' => 'vc_col-xs-3 multiple_icons_item',
				'param_name' => $param_key . '_has_asterisk_1',
				'value'      => array(
					__('Has asterisk?', 'nextcloud') => 'yes',
				),
				'dependency'  => array(
					'element' => $param_key . '_status',
					'value'   => array('multiple_icons'),
				),
			);
			$params[] = array(
				'type'       => 'checkbox',
				'heading'    => '',
				'edit_field_class' => 'vc_col-xs-3 multiple_icons_item',
				'param_name' => $param_key . '_has_asterisk_2',
				'value'      => array(
					__('Has asterisk?', 'nextcloud') => 'yes',
				),
				'dependency'  => array(
					'element' => $param_key . '_status',
					'value'   => array('multiple_icons'),
				),
			);
			$params[] = array(
				'type'       => 'checkbox',
				'heading'    => '',
				'edit_field_class' => 'vc_col-xs-3 multiple_icons_item',
				'param_name' => $param_key . '_has_asterisk_3',
				'value'      => array(
					__('Has asterisk?', 'nextcloud') => 'yes',
				),
				'dependency'  => array(
					'element' => $param_key . '_status',
					'value'   => array('multiple_icons'),
				),
			);
			$params[] = array(
				'type'       => 'checkbox',
				'heading'    => '',
				'edit_field_class' => 'vc_col-xs-3 multiple_icons_item',
				'param_name' => $param_key . '_has_asterisk_4',
				'value'      => array(
					__('Has asterisk?', 'nextcloud') => 'yes',
				),
				'dependency'  => array(
					'element' => $param_key . '_status',
					'value'   => array('multiple_icons'),
				),
			);


			$params[] = array(
				'type'       => 'checkbox',
				'heading'    => '',
				//'edit_field_class' => 'vc_col-xs-3 custom_checkbox',
				'param_name' => $param_key . '_has_label',
				'value'      => array(
					__('Custom label?', 'nextcloud') => 'yes',
				),
			);

			$params[] = array(
				'type'        => 'textfield',
				'heading'     => __('Custom label', 'nextcloud'),
				'param_name'  => $param_key . '_label',
				'description' => __('Optional label, shown on hover', 'nextcloud'),
				'dependency'  => array(
					'element' => $param_key . '_has_label',
					'value'   => array('yes'),
				),
			);


			$params[] = array(
				'type'       => 'checkbox',
				'heading'    => '',
				//'edit_field_class' => 'vc_col-xs-3 custom_checkbox',
				'param_name' => $param_key . '_has_tooltip',
				'value'      => array(
					__('Has tooltip?', 'nextcloud') => 'yes',
				),
			);

			$params[] = array(
				'type'        => 'textfield',
				'heading'     => __('Tooltip', 'nextcloud'),
				'param_name'  => $param_key . '_tooltip',
				'description' => __('Optional tooltip, shown on hover', 'nextcloud'),
				'dependency'  => array(
					'element' => $param_key . '_has_tooltip',
					'value'   => array('yes'),
				),
			);



		}
	}

	return $params;
}

/**
 * Register WPBakery element.
 */
add_action('vc_before_init', 'nc_register_platform_comparison_shortcode');
function nc_register_platform_comparison_shortcode() {
	if (!function_exists('vc_map')) {
		return;
	}

	vc_map(array(
		'name'        => __('Platforms comparison', 'nextcloud'),
		'base'        => 'platforms_comparison',
		'class'		  => 'platforms_comparison_custom_shortcode',
		'icon'        => 'icon-wpb-ui-tab-content',
		'category'    => __('Nextcloud', 'nextcloud'),
		'description' => __('Comparison table with predefined features and repeatable platforms.', 'nextcloud'),
		'params'      => array(
			array(
				'type'        => 'param_group',
				'heading'     => __('Platforms', 'nextcloud'),
				'param_name'  => 'platforms',
				'params'      => nc_platform_comparison_params(),
				'description' => __('Add one row per platform and set the status for each predefined feature.', 'nextcloud'),
			),
		),
	));
}


function nc_render_platform_comparison_icon($status, $asterisk = false) {
	$output = '';

	switch ($status) {
		case 'yes':
			$output = '<span class="fa fa-check-circle" aria-hidden="true"></span>';
			break;

		case 'optional':
			$output = '<span class="fa fa-check-circle optional" aria-hidden="true"></span>';
			break;

		case 'dollar':
			$output = '<span class="fa fa-dollar" aria-hidden="true"></span>';
			break;

		case 'no':
			$output = '<span class="fa fa-times-circle" aria-hidden="true"></span>';
			break;
	}

	if ($asterisk) {
		$output .= '<span class="comparison_asterisk">*</span>';
	}

	return $output;
}

/**
 * Shortcode renderer.
*/
add_shortcode('platforms_comparison', 'nc_render_platforms_comparison_shortcode');
function nc_platform_comparison_render_status_icon($status, $title = '', $asterisk = false) {
	$status = sanitize_key($status);

	switch ($status) {
		case 'yes':
			$html = '<div class="fa fa-check-circle" title="' . esc_attr($title) . '" aria-hidden="true"></div>';
			break;

		case 'optional':
			$html = '<div class="fa fa-check-circle optional" title="' . esc_attr($title) . '" aria-hidden="true"></div>';
			break;

		case 'dollar':
			$html = '<div class="fa fa-dollar-sign" title="' . esc_attr($title) . '" aria-hidden="true"></div>';
			break;

		case 'no':
		default:
			$html = '<div class="fa fa-times-circle" title="' . esc_attr($title) . '" aria-hidden="true"></div>';
			break;
	}

	if ($asterisk) {
		$html .= '<span class="comparison_asterisk">*</span>';
	}

	return $html;
}

function nc_platform_comparison_get_checkbox_values($value) {
	if (empty($value)) {
		return array();
	}

	if (is_array($value)) {
		return array_map('sanitize_key', $value);
	}

	return array_map('sanitize_key', array_map('trim', explode(',', $value)));
}

function nc_render_platforms_comparison_shortcode($atts) {
	$atts = shortcode_atts(array(
		'platforms' => '',
	), $atts, 'platforms_comparison');

	if (!function_exists('vc_param_group_parse_atts')) {
		return '';
	}

	$platforms = vc_param_group_parse_atts($atts['platforms']);
	$features  = nc_platform_comparison_features();

	if (empty($platforms) || !is_array($platforms) || empty($features)) {
		return '';
	}

	$allowed_statuses = array(
		'yes',
		'no',
		'optional',
		'custom',
		'dollar',
		'operating_system',
		'multiple_icons',
	);

	ob_start();
	?>
	<div class="comparison_table_container">

		<div class="filtering_buttons">
			<button class="reset" tabindex="0" type="reset">
				<?php echo esc_html__('Reset', 'nextcloud'); ?>
			</button>
			<button class="filter" tabindex="0" disabled>
				<?php echo esc_html__('Filter', 'nextcloud'); ?>
			</button>
		</div>

		<div class="table_move_arrows" style="display:none;">
			<a class="arrow arrow-left" href="#" aria-label="<?php echo esc_attr__('Scroll left', 'nextcloud'); ?>">
				<i class="fa fa-angle-left" aria-hidden="true"></i>
			</a>
			<a class="arrow arrow-right" href="#" aria-label="<?php echo esc_attr__('Scroll right', 'nextcloud'); ?>">
				<i class="fa fa-angle-right" aria-hidden="true"></i>
			</a>
		</div>

		<div class="comparison_table_container_inner">
			<table class="comparison_table">
				<thead>
					<tr class="platform_logos">
						<th scope="col"></th>

						<?php foreach ($platforms as $platform) : ?>
							<?php
							$name         = !empty($platform['name']) ? $platform['name'] : '';
							$logo_id      = !empty($platform['logo']) ? absint($platform['logo']) : 0;
							$custom_class = !empty($platform['custom_class']) ? sanitize_html_class($platform['custom_class']) : '';
							$logo_url     = $logo_id ? wp_get_attachment_image_url($logo_id, 'full') : '';
							?>

							<th scope="col">
								<div class="check" tabindex="0"></div>

								<?php if (!empty($logo_url)) : ?>
									<img
										src="<?php echo esc_url($logo_url); ?>"
										alt="<?php echo esc_attr($name); ?>"
										class="<?php echo esc_attr($custom_class); ?>"
									/>
								<?php elseif (!empty($name)) : ?>
									<span class="platform_name">
										<?php echo esc_html($name); ?>
									</span>
								<?php endif; ?>
							</th>
						<?php endforeach; ?>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($features as $group_key => $group_array) : ?>
						<?php
						$group_label    = isset($group_array[0]) ? $group_array[0] : '';
						$group_features = isset($group_array[1]) && is_array($group_array[1]) ? $group_array[1] : array();

						if (empty($group_features)) {
							continue;
						}
						?>

						<?php if ($group_key != 'main_features') { ?>
						<tr class="comparison_category comparison_category_<?php echo esc_attr(sanitize_html_class($group_key)); ?>">
							<td class="comparison_category_label">
								<?php echo esc_html($group_label); ?>
							</td>
							<td colspan="<?php echo esc_attr(count($platforms)); ?>"></td>
						</tr>
						<?php } ?>

						<?php foreach ($group_features as $feature_key => $feature_label) : ?>
							<?php $param_key = sanitize_key(str_replace('-', '_', trim($feature_key))); ?>

							<tr class="comparison_feature comparison_feature_<?php echo esc_attr(sanitize_html_class($param_key)); ?>">
								<td scope="row">
									<?php echo esc_html($feature_label); ?>
								</td>

								<?php foreach ($platforms as $platform) : ?>
									<?php
									$status = !empty($platform[$param_key . '_status'])
										? sanitize_key($platform[$param_key . '_status'])
										: 'no';

									if (!in_array($status, $allowed_statuses, true)) {
										$status = 'no';
									}

									$has_label = !empty($platform[$param_key . '_has_label'])
										&& $platform[$param_key . '_has_label'] === 'yes';

									$custom_label = !empty($platform[$param_key . '_label'])
										? trim($platform[$param_key . '_label'])
										: '';

									$has_tooltip = !empty($platform[$param_key . '_has_tooltip'])
										&& $platform[$param_key . '_has_tooltip'] === 'yes';

									$tooltip = !empty($platform[$param_key . '_tooltip'])
										? trim($platform[$param_key . '_tooltip'])
										: '';

									if ($has_label && $custom_label !== '') {
										$cell_label = $custom_label;
									} elseif ($status === 'yes') {
										$cell_label = __('Included', 'nextcloud');
									} elseif ($status === 'optional') {
										$cell_label = __('Optional', 'nextcloud');
									} elseif ($status === 'dollar') {
										$cell_label = __('Paid option', 'nextcloud');
									} elseif ($status === 'operating_system') {
										$cell_label = __('Available operating systems', 'nextcloud');
									} elseif ($status === 'multiple_icons') {
										$cell_label = __('Multiple options', 'nextcloud');
									} else {
										$cell_label = __('Not included', 'nextcloud');
									}

									if ($has_tooltip && $tooltip !== '') {
										$cell_label = $tooltip;
									}

									$td_classes = array(
										'status',
										'status-' . sanitize_html_class($status),
									);

									if ($has_tooltip && $tooltip !== '') {
										$td_classes[] = 'has_tooltip';
									}
									?>

									<td class="<?php echo esc_attr(implode(' ', $td_classes)); ?>">

										<?php if (in_array($status, array('yes', 'optional', 'dollar', 'no'), true)) : ?>

											<?php echo nc_platform_comparison_render_status_icon($status, $cell_label); ?>
											<span class="screen-reader-text">
												<?php echo esc_html($cell_label); ?>
											</span>

										<?php elseif ($status === 'custom') : ?>

											<?php if ($custom_label !== '') : ?>
												<span class="comparison_custom_label">
													<?php echo esc_html($custom_label); ?>
												</span>
											<?php endif; ?>

										<?php elseif ($status === 'operating_system') : ?>

											<?php
											$os_icons = nc_platform_comparison_get_checkbox_values(
												isset($platform[$param_key . '_os_icons']) ? $platform[$param_key . '_os_icons'] : ''
											);

											$os_icon_map = array(
												'apple'   => array('class' => 'fa fa-apple', 'label' => __('iOS / iPhone', 'nextcloud')),
												'android' => array('class' => 'fa fa-android', 'label' => __('Android', 'nextcloud')),
												'windows' => array('class' => 'fa fa-windows', 'label' => __('Windows', 'nextcloud')),
												'linux'   => array('class' => 'fa fa-linux', 'label' => __('Linux', 'nextcloud')),
											);
											?>

											<?php if (!empty($os_icons)) : ?>
												<div class="comparison_os_icons">
													<?php foreach ($os_icons as $os_icon) : ?>
														<?php if (isset($os_icon_map[$os_icon])) : ?>
															<span
																class="<?php echo esc_attr($os_icon_map[$os_icon]['class']); ?>"
																title="<?php echo esc_attr($cell_label); ?>"
																aria-hidden="true"
															></span>
														<?php endif; ?>
													<?php endforeach; ?>
												</div>

												<span class="screen-reader-text">
													<?php echo esc_html($cell_label); ?>
												</span>
											<?php else : ?>
												<?php echo nc_platform_comparison_render_status_icon('no', __('Not included', 'nextcloud')); ?>
												<span class="screen-reader-text">
													<?php echo esc_html__('Not included', 'nextcloud'); ?>
												</span>
											<?php endif; ?>

										<?php elseif ($status === 'multiple_icons') : ?>

											<div class="comparison_multiple_icons">
												<?php
												for ($i = 1; $i <= 4; $i++) {
													$icon_status = !empty($platform[$param_key . '_multiple_icons_' . $i])
														? sanitize_key($platform[$param_key . '_multiple_icons_' . $i])
														: '';

													if ($icon_status === '') {
														continue;
													}

													if (!in_array($icon_status, array('yes', 'no', 'dollar'), true)) {
														continue;
													}

													$has_asterisk = !empty($platform[$param_key . '_has_asterisk_' . $i])
														&& $platform[$param_key . '_has_asterisk_' . $i] === 'yes';

													$icon_label = $cell_label;

													echo '<span class="comparison_multiple_icon comparison_multiple_icon_' . esc_attr($i) . '">';
													echo nc_platform_comparison_render_status_icon($icon_status, $icon_label, $has_asterisk);
													echo '<span class="screen-reader-text">' . esc_html($icon_label) . '</span>';
													echo '</span>';
												}
												?>
											</div>

										<?php endif; ?>

										<?php if ($has_label && $custom_label !== '' && $status !== 'custom' && !$has_tooltip) : ?>
											<span class="block">
												<?php echo esc_html($custom_label); ?>
											</span>
										<?php endif; ?>

										<?php if ($has_tooltip && $tooltip !== '') : ?>
											<span class="tooltip">
												<?php echo esc_html($tooltip); ?>
											</span>
											<span class="show_tooltip">
												<?php echo esc_html__('See more', 'nextcloud'); ?>
											</span>
										<?php endif; ?>

									</td>
								<?php endforeach; ?>
							</tr>
						<?php endforeach; ?>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php

	return ob_get_clean();
}