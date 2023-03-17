<?php
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


		//$return .='<div class="images"><img src="'.$images[0].'" alt="'.$atts['title'].'"></div>';
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
		  					"admin_label" => true,
		  					"heading" => __("Link", "nextcloud"),
		  					"param_name" => "box_repeater_items_link",
		  					"value" => __("", "nextcloud"),
		  				),
		  			)
		  		),
		  	)
		  )
	  );
}

add_shortcode('box_repeater', 'box_repeater_funct');
function box_repeater_funct($atts) {
	ob_start();
	$atts = shortcode_atts(array(
		'box_repeater_heading' => '',
		'box_repeater_items' => '',
	), $atts, 'box_repeater');

	$heading = $atts['box_repeater_heading'];
	$items = vc_param_group_parse_atts($atts['box_repeater_items']); ?>
      <div class="box-repeater">

          <?php if ($items) { ?>
              <div class="box-repeater-items clients_carousel owl-carousel owl-theme">
                  <?php  foreach ($items as  $item) {
					
					if (isset($item['box_repeater_items_link'])) {
						$link = vc_build_link($item['box_repeater_items_link']);
					} ?>

                      <div class="client_item">
					  <div class="client_item_inner">

					  	<?php if (isset($item['box_repeater_items_link'])) { ?>
					  	<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" title="<?php echo $item['box_repeater_items_title']; ?>">
						<?php } ?>

						<?php echo wp_get_attachment_image($item['box_repeater_items_img'], 'full'); ?>

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
      <?php
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
			'video_thumb' => '',
			'video_url' => '',
			'title' => '',
			'subtitle' => '',
			'css_class' => ''
		), $atts);


	$css_class = $attributes['css_class'];

	$return = '<div class="nc_video_preview_box popup-video '.$css_class.'">';
	$image = wp_get_attachment_image($attributes['video_thumb'], 'large');
	$return .= '<a href="'.$attributes['video_url'].'" title="'.$attributes['title'].'" class="">';
	$return .= '<div class="overlay"></div>';
	$return .= $image;

	$return .= '<div class="video_text">
				<div class="icon"><i class="fas fa-play-circle"></i></div>
				<div class="title">'.$attributes['title'].'</div>
				<div class="subtitle">'.$attributes['subtitle'].'</div>
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
			'title' => '',
			'description' => '',
			'css_classes' => '',
			'see_more_label' => __('See more', 'nextcloud'),
		), $atts);

	$link = vc_build_link($attributes['link']);
	
	

	$return = '<div class="nc_iconbox '.$attributes['css_classes'].' ">';

	if ($attributes['image_icon']) {
		$icon = wp_get_attachment_image($attributes['image_icon'], 'medium');
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


	$desc = $attributes['description'];
	if( str_contains($attributes['css_classes'], 'key_code')) {
		$desc = "<code>".$attributes['description']."</code>";
	}

	$return .= '<div class="iconbox_container '.$custom_class.'">';

	$return .= '<div class="icon">'.$icon.'</div>
	<div class="title_and_description">
	<h4 class="title">'.$attributes['title'].'</h4>';

	if($desc) {
		$return .= '<div class="description">'.$desc.'</div>';
	}

	if($attributes['link'] && $link['url'] != '#no_scroll' ){
		$return .= '<span class="see_more">'.$attributes['see_more_label'].' <i class="fas fa-angle-right"></i></span>';
	}
	$return .= '</div>';


	$return .= '</div>';

	if($attributes['link'] && $link['url'] != '#no_scroll' ){
		$return .= '</a>';
	}else {
		$return .= '</div>';
	}

	
	$return .= '</div>';
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

	if ($attributes['event_image']) {
		$image = wp_get_attachment_image($attributes['event_image'], 'large', array( 'class' => 'feat_img' ));
	} else {
		$image = "";
	}
				
	$return .= '<a href="'.$link['url'].'" target="'.$link['target'].'" title="'.$link['title'].'" class="">';
	$return .= '<div class="image">'.$image.'</div>';


	$return .= '<div class="event_box_container">
				<h4 class="title">'.$attributes['title'].'</h4>

				<div class="event_meta">
				<div class="date"><i class="fas fa-calendar-alt"></i> '.$attributes['date'].'</div>
				<div class="venue"><i class="fas fa-map-marker-alt"></i> '.$attributes['venue'].'</div>
				</div>
				<div class="description">'.$attributes['description'].'</div>';
	
	
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
		  	)
		  )
	  );
}

add_shortcode('features_carousel', 'features_carousel_funct');
function features_carousel_funct($atts) {
	ob_start();
	$atts = shortcode_atts(array(
		'box_repeater_heading' => '',
		'box_repeater_items' => '',
	), $atts, 'box_repeater');

	//$heading = $atts['box_repeater_heading'];
	$items = vc_param_group_parse_atts($atts['box_repeater_items']); ?>
      <div class="box-repeater">

          <?php if ($items) { ?>
              <div class="features_carousel owl-carousel owl-theme">
                  <?php  foreach ($items as  $item) {
					
					//print_r($item['box_repeater_items_link']);
					if (isset($item['link'])) {
						$link = vc_build_link($item['link']);
					}

					$custom_css = $item['custom_css'];
					$no_overlay = $item['no_overlay'];

					?>
                      <div class="feature_item <?php echo $custom_css; ?>">

					  	<?php if ( isset($item['link']) && !isset($item['video_url']) ) { ?>
					  	<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" title="<?php echo $item['title']; ?>">
						<?php } ?>


						<div class="feature_image">
							<?php if ( isset($item['video_url']) && !isset($no_overlay) ) { ?>
								<a href="<?php echo $item['video_url']; ?>" title="<?php echo $item['title']; ?>" class="popup-video">
							<?php } ?>

							<?php if ( isset($item['video_url']) && !isset($no_overlay) ) { ?>
								<div class="play-icon"><i class="fas fa-play-circle"></i></div>
								<div class="overlay"></div>
							<?php } ?>

							<?php //if no video url nor Link
								if ( !isset($item['video_url']) && !isset($item['link']) && !isset($no_overlay)  ) { ?>
								<a href="<?php echo wp_get_attachment_image_url($item['feature_image'], 'full'); ?>" title="<?php echo $item['title']; ?>" class="popup-screenshot">
								<div class="screenshot-icon"><i class="fas fa-expand-arrows-alt"></i></div>
								<div class="overlay-screenshot"></div>
								<?php 
								}
							?>
							
							<?php echo wp_get_attachment_image($item['feature_image'], 'large'); ?>

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
							<div class="description"><?php echo $item['description']; ?></div>
							
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
	} ?>
              </div>
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

	//$heading = $atts['box_repeater_heading'];
	$image = wp_get_attachment_image($atts['static_image'], 'large');
	$items = vc_param_group_parse_atts($atts['box_repeater_items']); ?>
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










//Repeater Features Carousel
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
	), $atts, 'box_repeater');

	//$heading = $atts['box_repeater_heading'];
	$items = vc_param_group_parse_atts($atts['box_repeater_items']); ?>
      <div class="box-repeater">

          <?php if ($items) { ?>
			<div class="case_studies">
              <div class="testimonials_carousel owl-carousel owl-theme">
                  <?php  foreach ($items as  $item) {
					
					//print_r($item['box_repeater_items_link']);
					if ($item['link']) {
						$link = vc_build_link($item['link']);
					}
					?>
                      <div class="case_study testimonial">
						<div class="vc_column-inner <?php if ( !$item['link']  ) { echo "no_button"; } ?>">
							<div class="wpb_wrapper">
								
							
								<div class="wpb_single_image wpb_content_element vc_align_left image_top">
									<figure class="wpb_wrapper vc_figure">
										<?php if ($item['link'] ) { ?>
										<a href="<?php echo $link['url']; ?>" title="<?php echo $item['title']; ?>" target="<?php echo $link['target']; ?>" class="vc_single_image-wrapper   vc_box_border_grey">
										<?php } ?>

											<?php echo wp_get_attachment_image($item['image'], 'full'); ?>

										<?php if ($item['link'] ) { ?>
										</a>
										<?php } ?>

									</figure>
								</div>

								<div class="wpb_text_column wpb_content_element organization testimonial_quote">
									<div class="wpb_wrapper">
										<h4><?php echo $item['title']; ?></h4>
									</div>
								</div>
								
								<?php if($item['quote']) { ?>
								<div class="wpb_text_column wpb_content_element quote">
									<div class="wpb_wrapper">
										<p><?php echo $item['quote']; ?></p>
									</div>
								</div>
								<?php } ?>


								<?php if ( $item['link']  ) { ?>
								<div class="vc_btn3-container  btn-main btn-small vc_btn3-center">
									<a class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-rounded vc_btn3-style-modern vc_btn3-icon-right vc_btn3-color-grey" href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" title="<?php echo $item['title']; ?>">Learn more <i class="vc_btn3-icon fas fa-angle-right"></i>
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

	//$heading = $atts['box_repeater_heading'];
	$items = vc_param_group_parse_atts($atts['box_repeater_items']); ?>
      <div class="box-repeater">

          <?php if ($items) { ?>
			<div id="timeline-content">
				<ul class="timeline">
                  <?php  foreach ($items as  $item) { ?>
	
					<li class="event" data-date="<?php echo $item['date']; ?>">
					<div class="arrow-left"></div>
					<h3><?php echo $item['title']; ?></h3>
					<p><?php echo $item['description']; ?></p>
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
							"type" => "vc_link",
							"holder" => "div",
							"class" => "",
							"admin_label" => true,
							"heading" => __("Link", "nextcloud"),
							"param_name" => "link",
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
		//'columns' => ''
	), $atts, 'box_repeater');

	$items = vc_param_group_parse_atts($atts['box_repeater_items']); ?>
          <?php if ($items) { ?>
			<div class="vc_row wpb_row vc_row-fluid vc_column-gap-20 vc_row-o-equal-height vc_row-flex media_coverage_posts">
                  <?php  foreach ($items as  $item) {
					if ($item['link']) {
						$link = vc_build_link($item['link']);
					}
					?>
					<div class="wpb_column vc_column_container <?php echo $atts['css_classes']; ?> media_coverage_post">
						<div class="vc_column-inner">

								<div class="wpb_single_image wpb_content_element vc_align_left image">
									<figure class="wpb_wrapper vc_figure">
										<?php if ($item['link'] ) { ?>
										<a href="<?php echo $link['url']; ?>" title="<?php echo $item['title']; ?>" target="<?php echo $link['target']; ?>" class="">
										<?php } ?>
											<?php echo wp_get_attachment_image($item['image'], 'full'); ?>
										<?php if ($item['link'] ) { ?>
										</a>
										<?php } ?>
									</figure>
								</div>

								<?php if($item['title']) { ?>
								<div class="link">
								<?php } ?>
								<?php if ($item['link'] ) { ?>
								<a href="<?php echo $link['url']; ?>" title="<?php echo $item['title']; ?>" target="<?php echo $link['target']; ?>" class="">
								<?php } ?>
								<?php echo $item['title']; ?>
								<?php if ($item['link'] ) { ?>
								</a>
								<?php } ?>
								<?php if($item['title']) { ?>
								</div>
								<?php } ?>

						</div>
				  	</div>
				<?php } ?>
			</div>
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

	$items = vc_param_group_parse_atts($atts['box_repeater_items']); ?>
          <?php if ($items) { ?>
			<div class="vc_row wpb_row vc_row-fluid vc_column-gap-20 vc_row-o-equal-height vc_row-flex logo_resources">
                  <?php  foreach ($items as  $item) {
					?>
					<div class="wpb_column vc_column_container <?php echo $atts['css_classes']; ?>">
						<div class="logo_resource">

						
						<div class="vc_column-inner">
									
								<div class="image_preview <?php if ($item['custom_class']) echo $item['custom_class']; ?>">
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
	), $atts );
	$year = $a['year'];

	// The Query
	$first_ids = get_posts( array(
		'fields'         => 'ids',
		'category_name' => 'pressrelease',
		//'category' => 19,
		//'category' => apply_filters( 'wpml_object_id', 19, 'category', TRUE  ),
		'post_type' => array('post'),
		'post_status' => array('publish'),
		'date_query' => array(
			array(
				'year' => $year
			)
		),
		'posts_per_page' => '-1',
		'orderby'    => 'date',
		'order'      => 'DESC',
		'suppress_filters' => false
	));

	$second_ids = get_posts( array(
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
		//'suppress_filters' => 0
	));

	//$the_query = new WP_Query( $args );
	$post_ids = array_merge( $first_ids, $second_ids);

	$the_query = new WP_Query(array(
		'post_type' => 'any',
		'post__in'  => $post_ids, 
		'orderby'   => 'date', 
		'order'     => 'DESC',
		'posts_per_page' => '-1'
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
						<i class="fas fa-newspaper"></i>
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

	$desc = $attributes['description'];

	$return .= '<div class="iconbox_container '.$custom_class.'">
				<div class="icon">'.$icon.'</div>
				<h4 class="title">'.$attributes['title'].'</h4>
				<div class="description">'.$desc.'</div>';


	$return .= '</div></a>';

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
			'pantone' => '',
			'angle' => '',
			'css_classes' => ''
		), $atts);

	$return = '<div class="nc_colorbox '.$attributes['css_classes'].' "><div class="colorbox_inner">';

	if($attributes['title']){
		$return .= '<h4 class="title">'.$attributes['title'].'</h4>';
	}

	if($attributes['description']){
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


function nc_get_whitepaper_slug(){
	global $post;
	return $post->post_name;
}
add_shortcode('nc_slug', 'nc_get_whitepaper_slug');


function nc_get_get_actitiy_id(){
	global $post;
	return get_post_meta($post->ID, 'activity_id' , true);
}
add_shortcode('nc_activity_id', 'nc_get_get_actitiy_id');

function nc_get_webinar_video_url(){
	global $post;
	return get_post_meta($post->ID, 'webinar_video_url' , true);
}
add_shortcode('webinar_video_url', 'nc_get_webinar_video_url');


function nc_typed_text_func($atts){
	ob_start();
	?>

	<span class="typed_text" id="typed"></span>

	<script>
	var options = {
		strings: ['Password', 'Nudes', 'Diary', 'Photos', 'Data', 'Chatlogs'],
		typeSpeed: 40,
		backSpeed: 40,
		fadeOut: true,
		loop: true
	};

	var typed = new Typed('#typed', options);
	</script>
	
	<?php
	return ob_get_clean();
}
add_shortcode('nc_typed', 'nc_typed_text_func');



function webinars_list_func($atts){
	$a = shortcode_atts( array(
		'type' => '',
	), $atts );
	$type = $a['type'];

	ob_start();

	date_default_timezone_set('Europe/Berlin');
	$current_date_time = date('Y-m-d H:i:s', time());
	//echo $current_date_time;


	$args = array(
		'post_type' => 'event',
		'post_status' => 'publish',
		'posts_per_page' => '3',
		'tag__not_in' => array(269),
		'tax_query' => array(
			array(
				'taxonomy' => 'event_categories',
				'field'    => 'slug',
				'terms'    => 'webinars',
			),
		),

	);
	
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
		//$args['order'] = 'ASC';

	}

	$the_query = new WP_Query($args);
	//print_r($the_query);
	//echo $the_query->request;
	$count = $the_query->found_posts;
	//echo $count;

	// The Loop
	if ( $the_query->have_posts() ) {
		echo '<table class="table events_table webinars table-striped '.$type.'">
		<thead>
		<tr>
		<th>'.__('Webinar', 'nextcloud').'</th>
		<th>'.__('Date', 'nextcloud').'</th>
		<th>'.__('Time', 'nextcloud').'</th>
		</tr>
		</thead>		
		<tbody>';

		while ( $the_query->have_posts() ) {
			$the_query->the_post();

			//echo get_field('event_start_date_and_time');

			$event_start_datetime = get_field('event_start_date_and_time', false, false);
			$date = date_create($event_start_datetime);

			$event_end_datetime = get_field('event_end_date_and_time', false, false);
			if($event_end_datetime){
				$date_end = date_create($event_end_datetime);
			}
			?>
			<tr>
			<td><a class="hyperlink" title="<?php the_title(); ?>" href="<?php
			if(get_field('blog_post_url')){
				echo get_field('blog_post_url');
			} else {
				the_permalink();
			}
			?> " target="" rel="noopener"><?php the_title(); ?></a></td>
			<td>
			<?php 
			$date_format = get_option('date_format');
			echo date_format($date, $date_format);
			?>
			</td>
			<td>
			<?php 
			echo date_format($date,"g:i a"); ?> (CET)
			</td>
			</tr>

			

			<?php
		}
		echo '</tbody>
		</table>';

		if($count > 3) {
			$my_current_lang = apply_filters( 'wpml_current_language', NULL );
			// will return http://domain.com/de/contact
			$blog_wpml_permalink = apply_filters( 'wpml_permalink', '/blog', $my_current_lang ); 

			if($type == 'upcoming'){
			?>
			<div class="vc_btn3-container  btn-main btn-small vc_btn3-center"><a class="<?php echo $blog_wpml_permalink; ?> vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-rounded vc_btn3-style-modern vc_btn3-icon-right vc_btn3-color-grey" title="" target="" href="/<?php echo $my_current_lang; ?>/blog/?webinars=upcoming"><?php echo __('All upcoming webinars','nextcloud'); ?><i class="vc_btn3-icon fas fa-angle-right"></i></a></div>
			<?php
			}
			else {
			?>
			<div class="vc_btn3-container  btn-main btn-small vc_btn3-center"><a class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-rounded vc_btn3-style-modern vc_btn3-icon-right vc_btn3-color-grey" title="" target="" href="/blog/?webinars=past"><?php echo __('All recordings','nextcloud'); ?><i class="vc_btn3-icon fas fa-angle-right"></i></a></div>
			<?php
			}
		}

	} else {
		// no posts found
		if($type == 'upcoming'){
			echo "<h4 class='no_events_found'>".__('No upcoming webinars found.','nextcloud')."</h4>";
		}
		else {
			echo "<h4 class='no_events_found'>".__('No recordings found.','nextcloud')."</h4>";
		}
	}
	/* Restore original Post Data */
	wp_reset_postdata();
	?>
	
	
	<?php
	return ob_get_clean();
}
add_shortcode('webinars', 'webinars_list_func');





function events_list_func($atts){
	$a = shortcode_atts( array(
		'year' => '',
	), $atts );
	$year = $a['year'];

	ob_start();

	date_default_timezone_set('Europe/Berlin');
	$current_date_time = date('Y-m-d H:i:s', time());
	$current_year = date('Y', time());
	//echo $current_date_time;


	$args = array(
		'post_type' => 'event',
		'post_status' => 'publish',
		'posts_per_page' => '-1',

		'tax_query' => array(
			array(
				'taxonomy' => 'event_categories',
				'field'    => 'slug',
				'terms'    => array('webinars'),
				'operator' => 'NOT IN'
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
			//$post_type = get_post_type();

			$event_start_datetime = get_field('event_start_date_and_time', false, false);
			$date = date_create($event_start_datetime);

			/*
			$event_start_datetime = get_field('event_start_date_and_time');
			$date = DateTime::createFromFormat('d/m/Y H:i', $event_start_datetime);
			$time = DateTime::createFromFormat('d/m/Y H:i', $event_start_datetime);
			*/


			$event_end_datetime = get_field('event_end_date_and_time', false, false);
			if($event_end_datetime){
				//$date_end = DateTime::createFromFormat('d/m/Y H:i', $event_end_datetime);
				$date_end = date_create($event_end_datetime);
			}
			

			?>

			<tr>
			<td><a class="hyperlink" title="<?php the_title(); ?>" href="<?php
			if(get_field('blog_post_url')){
				echo get_field('blog_post_url');
			} else {
				the_permalink();
			}
			?> " target="_blank" rel="noopener"><?php the_title(); ?></a></td>
			
			<td>
				<?php echo get_field('location'); ?>
			</td>

			<td>
			<?php 
			$date_format = get_option('date_format');
			echo date_format($date,$date_format);
			if($event_end_datetime){
				echo " - ".date_format($date_end,$date_format);
			}
			//echo $date->format('F j, Y'); ?>
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


function webinar_details_func($atts){
	ob_start();
	$post_id = get_the_ID();

	$event_start_datetime = get_field('event_start_date_and_time', $post_id, false);
	$test_date = get_field('event_start_date_and_time');


	$date_start = date_create($event_start_datetime);

	$date_start_format = date_i18n("l, F j, Y", strtotime($event_start_datetime));

	$time_start_format = date_format($date_start,"g:i a");
	$start_datetime = strtotime($event_start_datetime);

	$event_end_datetime = get_field('event_end_date_and_time', $post_id, false);
	if($event_end_datetime){
		$date_end = date_create($event_end_datetime);
		$date_end_format = date_format($date_end,"F j, Y");
		$end_datetime = strtotime($event_end_datetime);
	}

?>
	<ul class="">
	<?php if($event_start_datetime) { ?>
		<li><?php echo __('Date:','nextcloud')." ".$date_start_format;
		?></li>
		<li><?php echo __('Time:','nextcloud')." ".$time_start_format; ?> (CET)</li>

		<?php if($event_end_datetime){ ?>
			<li><?php echo __('Duration:','nextcloud'); ?><?php 
				$diff = ($end_datetime - $start_datetime) / 60;
				echo " ".$diff." ".__('Minutes', 'nextcloud');
				 ?>
		</li>
		<?php } ?>

	<?php } ?>
	</ul>
<?php
return ob_get_clean();
}
add_shortcode('webinar_details', 'webinar_details_func');


//create custom date time field for the wp bakery custom shortcods
vc_add_shortcode_param( 'datetime', 'nc_wpb_datetime_settings_field', get_template_directory_uri().'/vc_extend/datetime.js' );
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
		'box_repeater_items' => '',
	), $atts, 'box_repeater');

	$items = vc_param_group_parse_atts($atts['box_repeater_items']);
	$year = $atts['year'];

	$events = array();

	$timezone = new DateTimeZone('Europe/Berlin');
	//$timezone = new DateTimeZone('EST');
	?>
          <?php
		  if ($items) {
			foreach ($items as  $item) {

					if (isset($item['link'])) {
						$link = vc_build_link($item['link']);
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

					$events[] = array(
						'title' => $item['title'],
						'location' => isset($item['location']) ? $item['location'] : '',
						'date_start' => $date_start_timestamp,
						'date_end' => $date_end_timestamp,
						'link' => isset($item['link']) ? $link['url'] : '',
						'target' => isset($item['link']) ? $link['target'] : '',
					);		
	
			}		
        }
		
		

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
		);
		
		$the_query = new WP_Query($args);
		if ( $the_query->have_posts() ) {
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
				}
				

				$events[] = array(
					'title' => get_the_title(),
					'location' => get_field('location'),
					'date_start' => $date_start_timestamp,
					'date_end' => $date_end_timestamp,
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

			array_multisort($sortArray[$orderby],SORT_DESC,$events);
		}




		
		/*
		echo "<pre>";
		print_r($events);
		echo "</pre>";
		*/
		

		if($events) {
			echo '<table class="table events_table events table-striped">';
				echo '<thead>';
				echo '<tr>
				<th>'.__('Event', 'nextcloud').'</th>
				<th>'.__('Location', 'nextcloud').'</th>
				<th>'.__('Date', 'nextcloud').'</th>
				</tr>';
				echo '</thead>';
				echo '<tbody>';

			foreach ($events as  $event) {
				?>
				<tr>
						<td><a href="<?php echo $event['link']; ?>" title="<?php echo $event['title']; ?>" target="<?php echo $event['target']; ?>" class="">
							<?php echo $event['title']; ?>
						</a></td>
						<td><?php echo $event['location']; ?></td>
						<td><?php 
						if($event['date_start']) {

							$start_month = gmdate("F", $event['date_start']);

							if($event['date_end']) {

								$end_month = gmdate("F", $event['date_end']);
								$start_day = gmdate("j", $event['date_start']);
								$end_day = gmdate("j", $event['date_end']);

								if($start_month == $end_month){ //if is the same month

									echo $start_month." ".$start_day."-".$end_day.", ";
									echo gmdate("Y", $event['date_start']);

								} else {
									//different months

									echo $start_month." ".$start_day."-".$end_month." ".$end_day.", ";
									echo gmdate("Y", $event['date_start']);

								}


							} else {
								echo gmdate("F j, Y", $event['date_start']);
							}
							
						}
						?>
						<?php
						/*
						if($event['date_end']) {
							echo gmdate("F j, Y", $event['date_end']);
						}
						*/
						?>
					</td>
				</tr>

				<?php
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

					<span class="comment-date"><?php printf(/* translators: 1: date and time(s). */ esc_html__('%1$s at %2$s' , '5balloons_theme'), get_comment_date(),  get_comment_time()) ?></span>


					<div class="comment-block">
						<div class="comment-arrow"></div>
							<?php if ($comment->comment_approved == '0') : ?>
								<em><?php esc_html_e('Your comment is awaiting moderation.','5balloons_theme') ?></em>
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
	<li class="comment <?php echo $even ? 'even' : 'odd'; ?> depth-1">
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

        <div class="respond comment-respond">
            <h4 id="reply-title" class="comment-reply-title">
                <?php echo __('Continue the discussion at', 'nextcloud'); ?>
                <a target="_blank" href="{topic_url}">
                    {discourse_url_name}
                </a>
            </h4>
        </div>
    </div>
    <?php
    $output = ob_get_clean();

    return $output;
}
add_filter( 'discourse_replies_html', 'my_namespace_remove_participants_html' );



//publish to discourse only posts in default language - english
function nc_custom_publish_to_discourse( $force_publish, $post_id, $post ) {
	//$post_id = $post->ID;
	$my_post_language_details = apply_filters( 'wpml_post_language_details', NULL, $post_id );
	return ($my_post_language_details['language_code'] == 'en');
  }
add_filter( 'wpdc_publish_after_save', 'nc_custom_publish_to_discourse', 10, 3 );



add_shortcode('whitepapers_list', 'whitepapers_list_func');
function whitepapers_list_func($atts){
	ob_start();

	$a = shortcode_atts( array(
		'type' => '',
	), $atts );
	$type = $a['type'];

	$args = array(
		'post_type' => $type,
		'post_status' => 'publish',
		'posts_per_page' => '-1',
		'orderby' => 'date',
		'order' => 'DESC',
		'tag__not_in' => array(269) // exclude unlisted tag
	);
	

	$the_query = new WP_Query($args);
	// The Loop
	if ( $the_query->have_posts() ) {
		echo '<div class="row">';
		echo '<div class="col-12">';
		echo '<div class="case-slider">';
		echo '<div class="row">';

		while ( $the_query->have_posts() ) {
			$the_query->the_post();

				//get_template_part('inc/blog_loop_single');

				$post_id = get_the_ID();
				$link = get_the_permalink($post_id);
				$featured_image = get_the_post_thumbnail($post_id, 'large', array( 'class' => 'feat_img' ));

				$img = wp_get_attachment_url(get_post_thumbnail_id($post_id) ?: 0) ?: '';
				$header = get_the_title($post_id);
				$ex = get_the_excerpt($post_id);
				//$custom_field = get_field('field_name', $post_id);
				//$att = get_field('attachment', $study->ID);
				//$str = substr($att, 38);
				echo '<div class="col-lg-4 mb-3">';
				echo '<div class="post-holder case_study" data-file="">';
				//echo '<div class="post-img" style="background-image:url(' . $img . ');"></div>';
				echo '<div class="post-img" style=""><a href="'.$link.'" title="'.$header.'">'.$featured_image.'</a></div>';

				echo '<div class="post-body">';
				if (!empty($header)) {
					echo '<h5 class="head"><a href="'.$link.'">' . $header  . '</a></h5>';
				}
				if (!empty($ex)) {
					echo '<p>' . $ex . '</p>';
				}


				echo '<div class="btn_container">';
				echo '<a href="'.get_the_permalink($post_id).'" class="c-btn btn-top btn-small btn_see_case_study">'.__('Download','nextcloud').'</a>';
				echo '</div>';


				echo '</div>';
				echo '</div>';
				echo '</div>';

		}


		echo '</div>';
		echo '</div>';
		echo '</div>';
		echo '</div>';

	}
	wp_reset_postdata();
	

	$result = ob_get_clean();
	return $result;
}



add_shortcode('whitepapers_posts', 'whitepapers_posts_func');
function whitepapers_posts_func($atts){
	ob_start();

	$a = shortcode_atts( array(
		'type' => '',
	), $atts );

	$args = array(
		'post_type' => 'post',
		'category_name' => 'whitepaper',
		'post_status' => 'publish',
		'posts_per_page' => '-1',
		'orderby' => 'date',
		'order' => 'DESC'
	);

	$the_query = new WP_Query($args);
	if ( $the_query->have_posts() ) {
		echo '<div class="row">';
		while ( $the_query->have_posts() ) {
			$the_query->the_post();

			$post_id = get_the_ID();

			$img = wp_get_attachment_url(get_post_thumbnail_id($post_id) ?: 0) ?: '';
				$title = get_the_title();
				$date = (string)get_the_date('F d, Y', $post_id);
				$cat = get_the_category($post_id);
				$link = get_permalink($post_id) ?: '';
				//$author_id = (int)$onepostsingle->post_author;
				$author_id = get_the_author_meta( 'ID' );
				$featured_image = get_the_post_thumbnail($post_id, 'large', array( 'class' => 'feat_img' ));

				echo '<div class="col-lg-4 col-md-6 spacer">';

				echo '<div class="post-box">';

				/*
				if($featured_image) {
					?>
					<div class="post-img">
					<a href="<?php echo $link; ?>" title="<?php echo $title; ?>">
						<?php echo $featured_image; ?>
					</a>
					</div>
					<?php
				}
				*/


				echo '<div class="paper-box">';
				echo '<ul class="cats">';
				echo '<li>posted in </li>';
				foreach ($cat as $c) {
					//	$category_link = get_category_link($c->term_id);
					echo '<li>' . $c->cat_name . ', </li>';
				}
				echo '<li>by ' . get_the_author_meta('display_name', $author_id) . '</li>';
				echo '</ul>';
				echo '<h4><a title="'.$title.'" href="'.$link.'">' . $title . '</a></h4>';
				echo '<ul class="info">';
				echo '<li>' . $date . '</li>';
				echo '<li><a class="c-btn" title="'.__('Download', 'nextcloud').'" href="' . $link . '">'.__('Download', 'nextcloud').'</a></li>';
				echo '</ul>';
				echo '</div>';
				echo '</div>';

				echo '</div>';

		}
	}
	wp_reset_postdata();


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
		  				),

						array(
							"type" => "textfield",
							"heading" => esc_html__("Custom CSS Classes", "nextcloud"),
							"description" => esc_html__("", "nextcloud"),
							"param_name" => "custom_css",
							"value" => "",
						)


		  			)
		  		),
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
	), $atts, 'box_repeater');

	//$heading = $atts['box_repeater_heading'];
	$items = vc_param_group_parse_atts($atts['box_repeater_items']); ?>
      <div class="case_studies">

          <?php if ($items) { ?>
              <div class="quotes_carousel owl-carousel owl-theme">
                  <?php  foreach ($items as  $item) {
					
					//print_r($item['box_repeater_items_link']);
					if (isset($item['link'])) {
						$link = vc_build_link($item['link']);
					}

					$custom_css = $item['custom_css'];
					?>

						<div class="case_study <?php echo $custom_css; ?>">
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


							<div class="wpb_text_column wpb_content_element  quote"><?php echo $item['description']; ?></div>


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



/*
add_shortcode('team_member_infos', 'team_member_infos_funct');
function team_member_infos_funct($atts) {
	ob_start();
	//global $post;
	//id = $post->ID;
	$id = get_the_ID();
	$img = wp_get_attachment_url(get_post_thumbnail_id($id) ?: 0) ?: '';

	$header = get_the_title($id);
	$bio = get_field('biography', $id);
	$pos = get_field('position', $id);
	$desc = get_field('position_description', $id);
	$social = get_field('social_links', $id);
?>
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
				echo '<img src="' . $icon . '" alt=""/>';
				echo '</a>';
				echo '</li>';
			}
			echo '</ul>';
		}
		
		?>
		</div>
	</div>
<?php
	$result = ob_get_clean();
	return $result;
}
*/



/*
//Adding Custom Shortcode to Grid Builder
add_filter( 'vc_grid_item_shortcodes', 'my_module_add_grid_shortcodes' );
function my_module_add_grid_shortcodes( $shortcodes ) {
 $shortcodes['vc_custom_post_meta'] = array(
  'name' => __( 'My Custom Post meta', 'my-text-domain' ),
  'base' => 'vc_custom_post_meta',
  'category' => __( 'Content', 'my-text-domain' ),
  'description' => __( 'Show custom post meta', 'my-text-domain' ),
  'post_type' => Vc_Grid_Item_Editor::postType(),
 );
 
 return $shortcodes;
}
// output function
add_shortcode( 'vc_custom_post_meta', 'vc_custom_post_meta_render' );
function vc_custom_post_meta_render($atts, $content, $tag) {
 return '{{ custom_meta }}';
}
  
add_filter( 'vc_gitem_template_attribute_custom_meta', 'vc_gitem_template_attribute_custom_meta ', 10, 2 );
function vc_gitem_template_attribute_custom_meta( $value, $data ) {

 extract( array_merge( array(
  'post' => null,
  'data' => '',
 ), $data ) );
 return $post->ID;

 //return var_export( get_post_meta( $post->ID, 'biography' ), true );
}
*/





/*
//create custom date time field for the wp bakery custom shortcods
vc_add_shortcode_param( 'post_select', 'nc_wpb_post_select_settings_field', get_template_directory_uri().'/vc_extend/datetime.js' );
function nc_wpb_post_select_settings_field( $settings, $value ) {
	$js = '<script>!function($) {
		//jquery code

	}(window.jQuery);</script>';

 return $js.'<div class="post_select_field">'
 .'<input name="' . esc_attr( $settings['param_name'] ) . '" class="post_select wpb_vc_param_value wpb-textinput ' .
 esc_attr( $settings['param_name'] ) . ' ' .
 esc_attr( $settings['type'] ) . '_field" type="text" value="' . esc_attr( $value ) . '" />' .
 '</div>'; // This is html markup that will be outputted in content elements edit form
}
*/


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
		'css_classes' => ''
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
					'terms' => $team_members_tag)
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
<div class="col-lg-4 col-md-6 spacer <?php echo $atts['css_classes']; ?>">
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
				echo '<img src="' . $icon . '" alt=""/>';
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
					//"type" => "post_select",
					"type" => "textarea",
					"heading" => esc_html__("Title", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "title",
					"value" => "",
					)
			)
		  )
	  );
}
add_shortcode('blog_list', 'blog_list_shortcode_funct');
function blog_list_shortcode_funct($atts) {
	ob_start();
	$atts = shortcode_atts(array(
		'title' => '',
	), $atts);
	$title = $atts['title'];
?>
<section class="blog-section">
	<div class="container">
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
			}
			
			echo '</h3>';
			echo '</div>';
			echo '</div>';
		}
		if (function_exists('wpes_search_form')) {
			echo '<div class="col-lg-4">';
			echo '<div class="form-holder">';
			wpes_search_form(array(
				'wpessid' => 1612
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
			// The Query
			$args = array(
				//'post_type' => array('post', 'event'),
				'posts_per_page' => $default_posts_per_page,
				'post_status' => 'publish',
				'orderby' => 'date',
				//'order' => 'DESC',
				'paged=' . $paged,
				'tag__not_in' => array(269), // exclude unlisted tag
				'category__not_in'=> array(225, 226) //exclude Private category
			);
			date_default_timezone_set('Europe/Berlin');
			$current_date_time = date('Y-m-d H:i:s', time());


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
				$args['post_type'] = array('post', 'event');
				$args['order'] = 'DESC';
			}

			
			$the_query = new WP_Query($args);
			$count = $the_query->found_posts;

			// The Loop
			if ($the_query->have_posts()) {
				while ($the_query->have_posts()) {
					$the_query->the_post();
					
					get_template_part('inc/blog_loop_single');
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
		<div class="row">
			<div class="col-12">
				<div class="section-button">
					<button class="c-btn btn-main" id="loadNews"><?php echo __('Load More','nextcloud'); ?></button>
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
					)

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
		'box_repeater_items' => ''
	), $atts);

	$title = $atts['title'];
	$items = vc_param_group_parse_atts($atts['box_repeater_items']); // logos
	$link = vc_build_link($atts['link']);
	$subtext = $atts['subtext'];
	$cert = $atts['cert_text'];
	//$logos = $atts['box_repeater_items'];
	//$solution = get_field('solution_providers');
	//$technology = get_field('technology_partners');

	$args_sol = array(
		'post_type' => 'partner_posts',
		'post_status' => 'publish',
		'posts_per_page' => '-1',
		'orderby' => 'date',
		'order' => 'DESC',
		'meta_query' => array(
			array(
				'key'   => 'service_text',
				'value' => 'Technology Partner',
				'compare'   => '!='
			)
		)
	  );
	$solution = get_posts( $args_sol );

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
	$technology = get_posts( $args_tech );
	

?>
<section class="section-intro">
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
	</div>
</section>
<section class="partners-section" id="">
	<div class="container">

		<div class="row">
			<div class="col-lg-12">
				<div class="tabs-navigation">
					<ul class="custom-partner-tabs">
						<li>
							<button class="tab-link active" id="solution-tab"><?php echo __("Solution providers","nextcloud"); ?></button>
						</li>
						<li>
							<button class="tab-link" id="technology-tab"><?php echo __("Technology Partners","nextcloud"); ?></button>
						</li>
					</ul>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-12">
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
						echo '<li>';
						echo wp_get_attachment_image($item['cert_logo'], 'large');
						echo '</li>';
					}
					echo '</ul>';
				}
				?>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="filters-holder">
					<div class="input-outer" id="service_select">
						<div class="input-holder selection">
							<span class="label"><?php echo __("Services","nextcloud"); ?></span>
							<div class="inner">
								<input id="services" type="text" value="All services" readonly="readonly" data-value="" placeholder="All services" />
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
					<div class="input-outer">
						<div class="input-holder selection">
							<span class="label"><?php echo __("Partner level","nextcloud"); ?></span>
							<div class="inner">
								<input id="certificates" type="text" value="All levels" readonly="readonly" data-value="all-cert" />
							</div>
						</div>
						<ul class="select-list cert-list">
							<li data-certificate="all-cert"><?php echo __("All levels","nextcloud"); ?></li>
							<li data-certificate="platinum">Platinum</li>
							<li data-certificate="gold">Gold</li>
							<li data-certificate="silver">Silver</li>
							<li data-certificate="bronze">Bronze</li>
						</ul>
					</div>
					<div class="input-outer" id="country_select">
						<div class="input-holder selection">
							<span class="label">Country</span>
							<div class="inner">
								<input id="country" type="text" value="All" readonly="readonly" data-value="all-comp" placeholder="All" />
							</div>
						</div>
						<ul class="select-list check-list region_select_list">
							<li>
								<input type="checkbox" name="country" value="all-comp" id="chk01" /><label for="chk01">All</label>
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
							<input type="text" placeholder="Search" id="filtersearch" />
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
					if ($solution) {
						?>
						<div class="custom-tab-panel active" data-panel="solution-tab">
							<div class="partners-holder">
								<?php
								foreach ($solution as $sol) {
									$name = get_the_title($sol->ID);
									$level = get_field('partner_level', $sol->ID);
									$servs = get_field('services', $sol->ID);
									$country = get_field('region', $sol->ID);
									
									if(is_array($country)) {
										$country = implode(",", $country);
									}

									$logo = get_field('logo', $sol->ID);
									$text = get_field('text', $sol->ID);
									$service_text = get_field('service_text', $sol->ID);
									$website_link = get_field('website_link', $sol->ID);

									echo '<div class="partner-col" id="partner-'.$sol->ID.'" data-type="' . $level;
									foreach ($servs as $s) {
										echo ' ' . $s;
									}
									echo '" data-country="' . $country . '">';
									echo '<div class="partner-box">';
									echo '<div class="certificate-line ' . $level . '">';
									echo $level . ' Partner';
									echo '</div>';
									echo '<div class="partner-logo">';
									if (!empty($logo)) {
										echo '<img src="' . $logo["url"] . '" alt="' . $logo["alt"] . '" title="' . $logo["title"] . '" />';
									}
									echo '</div>';
									echo '<div class="partner-text">';
									echo '<h4>' . $name . '</h4>';
									if (!empty($text)) {
										echo wpautop($text);
									}
									echo '</div>';
									echo '<ul class="partner-info">';
									if (!empty($service_text)) {
										echo '<li class="service_text">' . $service_text . '</li>';
									}
									if (!empty($website_link)) {
										echo '<li class="website_link"><a href="' . $website_link . '" target="_blank">'.__('Go to website','nextcloud').'</a></li>';
									}
									echo '</ul>';
									echo '</div>';
									echo '</div>';
								} ?>
							</div>
						</div>
					<?php
					} else {
						?>
						<h3 class="no_partner_found text-center" style="display: none;"><?php echo __('No partner found.','nextcloud'); ?></h3>
					<?php
					}


					if ($technology) {
						?>
						<div class="custom-tab-panel" data-panel="technology-tab">
							<div class="partners-holder">
								<?php
								foreach ($technology as $teh) {
									$name = get_the_title($teh->ID);
									$logo = get_field('logo', $teh->ID);
									$text = get_field('text', $teh->ID);
									$service_text = get_field('service_text', $teh->ID);
									$website_link = get_field('website_link', $teh->ID);
									echo '<div class="partner2-col">';
									echo '<div class="partner-box">';
									echo '<div class="partner-logo">';
									if (!empty($logo)) {
										echo '<img src="' . $logo["url"] . '" alt="' . $logo["alt"] . '" title="' . $logo["title"] . '" />';
									}
									echo '</div>';
									echo '<div class="partner-text">';
									echo '<h4>' . $name . '</h4>';
									if (!empty($text)) {
										echo wpautop($text);
									}
									echo '</div>';
									echo '<ul class="partner-info">';
									if (!empty($service_text)) {
										echo '<li class="service_text">' . $service_text . '</li>';
									}
									if (!empty($website_link)) {
										echo '<li class="website_link"><a href="' . $website_link . '" target="_blank">Go to website</a></li>';
									}
									echo '</ul>';
									echo '</div>';
									echo '</div>';
								} ?>
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
<?php
$result = ob_get_clean();
return $result;
}


//shortcode countdown for events
add_shortcode('countdown', 'countdown_shortcode_funct');
function countdown_shortcode_funct($atts) {
	ob_start();
	$atts = shortcode_atts(array(
		'date' => '',
		'id' => 'countdown'
	), $atts);
	$date = $atts['date'];
	$id = $atts['id'];
?>
<div id="<?php echo $id; ?>" class="countdown"></div>
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
    
  // Output the result in an element with id="demo"
  document.getElementById("<?php echo $id; ?>").innerHTML = "<div class='days'>" + days + "<span class=''>days</span></div>" + "<div class='hours'>" +hours + "<span class=''>hours</span></div>" + "<div class='min'>" + minutes + "<span class=''>minutes</span></div>" + "<div class='sec'>" + seconds + "<span class=''>seconds</span></div>";
    
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
		'desktop_images' => ''
	), $atts);

	$mobile_items = $atts['mobile_images'];
	$mobile_image_ids = explode(',',$mobile_items);

	$desktop_items = $atts['desktop_images'];
	$desktop_image_ids = explode(',',$desktop_items);

    
?>
<div class="nc-home-graphic">

<div class="desktop-slider-container">
  <div class="dm-device">
	<div class="device light">
	  <div class="screen">
		<div id="desktop_slider" class="slider owl-carousel owl-theme">

			<?php
			$i = 1;
			foreach( $desktop_image_ids as $desktop_image_id ){
				$attachment_image = wp_get_attachment_image_src( $desktop_image_id, 'full' );
				//$alt = trim( strip_tags( get_post_meta( $mobile_image_id, '_wp_attachment_image_alt', true ) ) );
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

</div>

<script>
jQuery('#mobile_slider').owlCarousel({
loop:true,
autoplay: 1000,
autoplayTimeout: 4000,
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
}
});

var owl_desktop_slider = jQuery('#desktop_slider').owlCarousel({
loop:true,
autoplay: 1000,
autoplayTimeout: 4000,
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
}
});

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
		'custom_css' => ''
	), $atts);

	$featured_image = $a['featured_image'];
	$title = $a['title'];
	$position = $a['position'];
	$description = $a['description'];
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
				//$img = wp_get_attachment_url(get_post_thumbnail_id($onepostsingle->ID) ?: 0) ?: '';
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
				echo '<li>'._('Posten in','nextcloud').' </li>';
				foreach ($cat as $c) {
					//	$category_link = get_category_link($c->term_id);
					echo '<li>' . $c->cat_name . ', </li>';
				}
				echo '<li>by ' . get_the_author_meta('display_name', $author_id) . '</li>';
				echo '</ul>';
				echo '<h4><a href="' . $link . '">' . $title . '</a></h4>';
				echo '<ul class="info">';
				echo '<li>' . $date . '</li>';
				echo '<li><a class="c-btn" href="' . $link . '">Read More</a></li>';
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