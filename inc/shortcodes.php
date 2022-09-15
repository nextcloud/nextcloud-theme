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




/*
class VcNextcloudBlockquote extends WPBakeryShortCode {
	public function __construct() {
		add_action('init', array( $this, 'create_shortcode' ), 999);
		add_shortcode('vc_soda_blockquote', array( $this, 'render_shortcode' ));
	}

	public function create_shortcode() {
		// Stop all if VC is not enabled
		if (!defined('WPB_VC_VERSION')) {
			return;
		}

		// Map blockquote with vc_map()
		vc_map(array(
			'name' => __('Blockquote', 'nextcloud'),
			'base' => 'vc_soda_blockquote',
			'description' => __('', 'nextcloud'),
			'category' => __('Nextcloud Custom Shortcodes', 'nextcloud'),
			'params' => array(

				array(
					"type" => "textarea_html",
					"holder" => "div",
					"class" => "",
					"heading" => __("Blockquote Content", 'sodawebmedia'),
					"param_name" => "content", // Important: Only one textarea_html param per content element allowed and it should have "content" as a "param_name"
					"value" => __("<p>I am test text block. Click edit button to change this text.</p>", 'sodawebmedia'),
					"description" => __("Enter content.", 'sodawebmedia')
				),

				array(
					'type' => 'textfield',
					'holder' => 'div',
					'heading' => __('Author Quote', 'sodawebmedia'),
					'param_name' => 'quote_author',
					'value' => __('', 'sodawebmedia'),
					'description' => __('Add Author Quote.', 'sodawebmedia'),
				),


				array(
					"type" => "vc_link",
					"class" => "",
					"heading" => __("Blockquote Cite", 'sodawebmedia'),
					"param_name" => "blockquote_cite",
					"description" => __("Add Citiation Link and Source Name", 'sodawebmedia'),
				),

				array(
					'type' => 'textfield',
					'heading' => __('Element ID', 'sodawebmedia'),
					'param_name' => 'element_id',
					'value' => __('', 'sodawebmedia'),
					'description' => __('Enter element ID (Note: make sure it is unique and valid).', 'sodawebmedia'),
					'group' => __('Extra', 'sodawebmedia'),
				),

				array(
					'type' => 'textfield',
					'heading' => __('Extra class name', 'sodawebmedia'),
					'param_name' => 'extra_class',
					'value' => __('', 'sodawebmedia'),
					'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'sodawebmedia'),
					'group' => __('Extra', 'sodawebmedia'),
				),
			),
		));
	}

	public function render_shortcode($atts, $content, $tag) {
		$atts = (shortcode_atts(array(
			'blockquote_cite' => '',
			'quote_author' => '',
			'extra_class' => '',
			'element_id' => ''
		), $atts));


		//Content
		$content = wpb_js_remove_wpautop($content, true);
		$quote_author = esc_html($atts['quote_author']);

		//Cite Link
		$blockquote_source = vc_build_link($atts['blockquote_cite']);
		$blockquote_title = esc_html($blockquote_source["title"]);
		$blockquote_url = esc_url($blockquote_source['url']);

		//Class and Id
		$extra_class = esc_attr($atts['extra_class']);
		$element_id = esc_attr($atts['element_id']);



		$output = '';
		$output .= '<div class="blockquote ' . $extra_class . '" id="' . $element_id . '" >';
		$output .= '<blockquote cite="' . $blockquote_url . '">';
		$output .= $content;
		$output .= '<footer>' . $quote_author . ' - <cite><a href="' . $blockquote_url . '">' . $blockquote_title . '</a></cite></footer>';
		$output .= '</blockquote>';
		$output .= '</div>';

		return $output;
	}
}
new VcNextcloudBlockquote();
*/


/*
class VcNextcloudCarousel extends WPBakeryShortCode {
	public function __construct() {
		add_action('init', array( $this, 'create_shortcode' ), 999);
		add_shortcode('vc_clients_carousel', array( $this, 'render_shortcode' ));
	}

	public function create_shortcode() {
		// Stop all if VC is not enabled
		if (!defined('WPB_VC_VERSION')) {
			return;
		}

		// Map blockquote with vc_map()
		vc_map(array(
			'name' => __('Clients Carousel', 'nextcloud'),
			'base' => 'vc_clients_carousel',
			'description' => __('', 'nextcloud'),
			'category' => __('Nextcloud Custom Shortcodes', 'nextcloud'),
			'params' => array(

				array(
					"type" => "textarea_html",
					"holder" => "div",
					"class" => "",
					"heading" => __("Number of items", 'nextcloud'),
					"param_name" => "nr_items", // Important: Only one textarea_html param per content element allowed and it should have "content" as a "param_name"
					"value" => __("<p>I am test text block. Click edit button to change this text.</p>", 'nextcloud'),
					"description" => __("Enter content.", 'nextcloud')
				),


				array(
					"type" => "attach_images",
					"holder" => "div",
					"class" => "",
					"heading" => __("Clients", 'nextcloud'),
					"param_name" => "client_images", // Important: Only one textarea_html param per content element allowed and it should have "content" as a "param_name"
					"value" => __("<p>I am test text block. Click edit button to change this text.</p>", 'nextcloud'),
					"description" => __("Select images.", 'nextcloud')
				),

				
			),
		));
	}

	public function render_shortcode($atts, $content, $tag) {
		$atts = (shortcode_atts(array(
			'blockquote_cite' => '',
			'quote_author' => '',
			'extra_class' => '',
			'element_id' => ''
		), $atts));


		//Content
		$content = wpb_js_remove_wpautop($content, true);
		$quote_author = esc_html($atts['quote_author']);

		//Cite Link
		$blockquote_source = vc_build_link($atts['blockquote_cite']);
		$blockquote_title = esc_html($blockquote_source["title"]);
		$blockquote_url = esc_url($blockquote_source['url']);

		//Class and Id
		$extra_class = esc_attr($atts['extra_class']);
		$element_id = esc_attr($atts['element_id']);



		$output = '';
		$output .= '<div class="blockquote ' . $extra_class . '" id="' . $element_id . '" >';
		$output .= '<blockquote cite="' . $blockquote_url . '">';
		$output .= $content;
		$output .= '<footer>' . $quote_author . ' - <cite><a href="' . $blockquote_url . '">' . $blockquote_title . '</a></cite></footer>';
		$output .= '</blockquote>';
		$output .= '</div>';

		return $output;
	}
}
new VcNextcloudCarousel();
*/



function nc_clients_carousel() {
    // Title
    vc_map(
        array(
            'name' => __( 'Clients' ),
            'base' => 'nc_clients_carousel_content',
            'category' => __( 'Carousel' ),
            'params' => array(


                array(
                "type"        => "attach_images",
                "heading"     => esc_html__( "Add Clients Images", "appcastle-core" ),
                "description" => esc_html__( "Add Clients Images", "appcastle-core" ),
                "param_name"  => "screenshots",
                "value"       => "",
                ),

            )
        )
    );
}
add_action( 'vc_before_init', 'nc_clients_carousel' );
function nc_clients_carousel_content_function( $atts, $content ) {
	$gallery = shortcode_atts(
		array(
			'screenshots'      =>  'screenshots',
		), $atts );
	
	 $image_ids = explode(',',$gallery['screenshots']);
	 $return = '
		<div class="clients_carousel owl-carousel owl-theme">';
		foreach( $image_ids as $image_id ){

				$image = wp_get_attachment_image( $image_id, 'thumbnail' );
	
				$return .= '<div class="item client_item">';
				$return .= $image;
				$return .= '</div>';


		//$return .='<div class="images"><img src="'.$images[0].'" alt="'.$atts['title'].'"></div>';
		
		}
		$return .='</div>';
	return $return;
}
add_shortcode( 'nc_clients_carousel_content', 'nc_clients_carousel_content_function' );




//repeater test
add_action('vc_before_init', 'box_repeater_items_funct');
function box_repeater_items_funct() {
      vc_map(
          array(
                "name" => __("Box Repeater", "nextcloud"), // Element name
                  "base" => "box_repeater", // Element shortcode
                  "class" => "box-repeater",
                  "category" => __('Repeater', 'nextcloud'),
                  'params' => array(

					/*
                      array(
                          "type" => "textfield",
                          "holder" => "div",
                          "class" => "",
                          "admin_label" => true,
                          "heading" => __("Heading", "my-text-domain"),
                          "param_name" => "box_repeater_heading",
                          "value" => __("", "my-text-domain"),
                          "description" => __('Add heading here', "my-text-domain")
                      ),
					*/

                      array(
                        'type' => 'param_group',
                        'param_name' => 'box_repeater_items',
                        'params' => array(
                           array(
                              "type" => "attach_image",
                              "holder" => "img",
                              "class" => "",
                              "heading" => __( "Client Logo", "nextcloud" ),
                              "param_name" => "box_repeater_items_img",
                              "value" => __( "", "nextcloud" ),
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
          'box_repeater_heading'=>'',
          'box_repeater_items' =>'',
      ), $atts, 'box_repeater');

      $heading = $atts['box_repeater_heading'];  
      $items = vc_param_group_parse_atts($atts['box_repeater_items']);

      ?>
      <div class="box-repeater">

          <?php if($items) { ?>
              <div class="box-repeater-items clients_carousel owl-carousel owl-theme">
                  <?php  foreach ($items as  $item) { 
					
					//print_r($item['box_repeater_items_link']);
					if($item['box_repeater_items_link']){
						$link = vc_build_link($item['box_repeater_items_link']);
					}
		 			?>
                      <div class="client_item">
					  <div class="client_item_inner">
					  	<?php if($item['box_repeater_items_link']) { ?>
					  	<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>" title="<?php echo $item['box_repeater_items_title']; ?>">
						<?php } ?>

						<?php echo wp_get_attachment_image($item['box_repeater_items_img'], 'full'); ?>

						<?php if($item['box_repeater_items_link']) { ?>
				  		</a>
						<?php } ?>
						</div>
                      </div>
                  <?php } ?>
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
            'name' => __( 'Video Preview' ),
            'base' => 'nc_video_preview',
            'category' => __('Content', 'nextcloud'),
            'params' => array(

				array(
					"type" => "attach_image",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Video Thumbnail", "nextcloud" ),
					"param_name" => "video_thumb",
					"value" => __( "", "nextcloud" ),
				),

				
                array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Video URL", "nextcloud" ),
                "description" => esc_html__( "", "nextcloud" ),
                "param_name"  => "video_url",
                "value"       => "",
                ),

				array(
					"type"        => "textfield",
					"heading"     => esc_html__( "Title", "nextcloud" ),
					"description" => esc_html__( "", "nextcloud" ),
					"param_name"  => "title",
					"value"       => "",
				),

				array(
					"type"        => "textfield",
					"heading"     => esc_html__( "Subtitle", "nextcloud" ),
					"description" => esc_html__( "", "nextcloud" ),
					"param_name"  => "subtitle",
					"value"       => "",
				)
				
				

            )
        )
    );
}

add_action( 'vc_before_init', 'nc_vc_video_preview_custom' );
function nc_video_preview_function( $atts, $content ) {
	$attributes = shortcode_atts(
		array(
			'video_thumb' => '',
			'video_url'      =>  '',
			'title' => '',
			'subtitle' => ''
		), $atts );

	 $return = '<div class="nc_video_preview_box popup-video">';
				$image = wp_get_attachment_image( $attributes['video_thumb'], 'large' );
				$return .= '<a href="'.$attributes['video_url'].'" title="'.$attributes['title'].'" class="">';
				$return .= '<div class="overlay"></div>';
				$return .= $image;

				$return .= '<div class="video_text">
				<div class="icon"><i class="fas fa-play-circle"></i></div>
				<div class="title">'.$attributes['title'].'</div>
				<div class="subtitle">'.$attributes['subtitle'].'</div>
				</div>';
				

				$return .= '</a>';
		$return .='</div>';
	return $return;
}
add_shortcode( 'nc_video_preview', 'nc_video_preview_function' );






// shortcode Iconbox
function nc_iconbox_element_custom() {
    // Title
    vc_map(
        array(
            'name' => __( 'Iconbox' ),
            'base' => 'nc_iconbox',
            'category' => __('Content', 'nextcloud'),
            'params' => array(
				
				array(
					"type" => "iconpicker",
					"holder" => "div",
					"class" => "",
					"heading" => __( "Icon", "nextcloud" ),
					"param_name" => "icon",
					"value" => __( "", "nextcloud" ),
				),


				array(
					"type" => "attach_image",
					"holder" => "img",
					"class" => "",
					"heading" => __( "Image Icon", "nextcloud" ),
					"param_name" => "image_icon",
					"value" => __( "", "nextcloud" ),
				),

				
				/*
                array(
                "type"        => "textfield",
                "heading"     => esc_html__( "Link", "nextcloud" ),
                "description" => esc_html__( "", "nextcloud" ),
                "param_name"  => "link",
                "value"       => "",
                ),*/

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
					"type"        => "textfield",
					"heading"     => esc_html__( "Title", "nextcloud" ),
					"description" => esc_html__( "", "nextcloud" ),
					"param_name"  => "title",
					"value"       => "",
				),

				array(
					"type"        => "textarea",
					"heading"     => esc_html__( "Description", "nextcloud" ),
					"description" => esc_html__( "", "nextcloud" ),
					"param_name"  => "description",
					"value"       => "",
				),

				array(
					"type"        => "textfield",
					"heading"     => esc_html__( "Custom CSS Classes", "nextcloud" ),
					"description" => esc_html__( "", "nextcloud" ),
					"param_name"  => "css_classes",
					"value"       => "",
				),
				
				

            )
        )
    );
}
add_action( 'vc_before_init', 'nc_iconbox_element_custom' );

function nc_iconbox_function( $atts, $content ) {
	$attributes = shortcode_atts(
		array(
			'icon' => '',
			'image_icon' => '',
			'link'      =>  '',
			'title' => '',
			'description' => '',
			'css_classes' => '',
		), $atts );

		$link = vc_build_link($attributes['link']);

	 	$return = '<div class="nc_iconbox '.$attributes['css_classes'].'">';

		if($attributes['image_icon']){
			$icon = wp_get_attachment_image( $attributes['image_icon'], 'medium' );
		} else if($attributes['icon']) {
			$icon = '<i class="'.$attributes['icon'].'"></i>'; 
		} else {
			$icon = "";
		}
				
				$return .= '<a href="'.$link['url'].'" target="'.$link['target'].'" title="'.$link['title'].'" class="">';

				$return .= '<div class="iconbox_container">
				<div class="icon">'.$icon.'</div>
				<h4 class="title">'.$attributes['title'].'</h4>
				<div class="description">'.$attributes['description'].'</div>
				<span class="see_more">See more <i class="fas fa-angle-right"></i></span>
				</div>';
				

				$return .= '</a>';
		$return .='</div>';
	return $return;
}
add_shortcode( 'nc_iconbox', 'nc_iconbox_function' );




// shortcode Eventbox
function nc_eventbox_element_custom() {
    // Title
    vc_map(
        array(
            'name' => __( 'Eventbox' ),
            'base' => 'nc_eventbox',
            'category' => __('Content', 'nextcloud'),
            'params' => array(

				array(
					"type" => "attach_image",
					"holder" => "img",
					"class" => "",
					"heading" => __( "Event Image", "nextcloud" ),
					"param_name" => "event_image",
					"value" => __( "", "nextcloud" ),
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
					"type"        => "textfield",
					"heading"     => esc_html__( "Date", "nextcloud" ),
					"description" => esc_html__( "", "nextcloud" ),
					"param_name"  => "date",
					"value"       => "",
				),

				array(
					"type"        => "textfield",
					"heading"     => esc_html__( "Venue", "nextcloud" ),
					"description" => esc_html__( "", "nextcloud" ),
					"param_name"  => "venue",
					"value"       => "",
				),


				array(
					"type"        => "textfield",
					"heading"     => esc_html__( "Event Title", "nextcloud" ),
					"description" => esc_html__( "", "nextcloud" ),
					"param_name"  => "title",
					"value"       => "",
				),

				array(
					"type"        => "textarea",
					"heading"     => esc_html__( "Description", "nextcloud" ),
					"description" => esc_html__( "", "nextcloud" ),
					"param_name"  => "description",
					"value"       => "",
				),

				array(
					"type"        => "textfield",
					"heading"     => esc_html__( "Button label", "nextcloud" ),
					"description" => esc_html__( "", "nextcloud" ),
					"param_name"  => "btn_label",
					"value"       => "",
				),

				array(
					"type"        => "textfield",
					"heading"     => esc_html__( "Custom CSS Classes", "nextcloud" ),
					"description" => esc_html__( "", "nextcloud" ),
					"param_name"  => "css_classes",
					"value"       => "",
				),
				
				

            )
        )
    );
}
add_action( 'vc_before_init', 'nc_eventbox_element_custom' );

function nc_eventbox_function( $atts, $content ) {
	$attributes = shortcode_atts(
		array(
			'event_image' => '',
			'link'      =>  '',
			'date' => '',
			'venue' => '',
			'title' => '',
			'description' => '',
			'btn_label' => 'See more',
			'css_classes' => '',
		), $atts );

		$link = vc_build_link($attributes['link']);
	 	$return = '<div class="nc_eventbox '.$attributes['css_classes'].'">';

		if($attributes['event_image']){
			$image = wp_get_attachment_image( $attributes['event_image'], 'large', array( 'class' => 'feat_img' ) );
		}else{
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


				<div class="description">'.$attributes['description'].'</div>
				<span class="btn see_more">'.$attributes['btn_label'].' <i class="fas fa-angle-right"></i></span>
				</div>';
				

				$return .= '</a>';

		$return .='</div>';
	return $return;
}
add_shortcode( 'nc_eventbox', 'nc_eventbox_function' );