<?php
//Nextcloud Videos repeater element
add_action('vc_before_init', 'wpb_nc_videos_funct');
function wpb_nc_videos_funct() {
	vc_map(
		  array(
		  	"name" => __("Nextcloud Videos repeater", "nextcloud"),
		  	"base" => "nc_videos", //Element shortcode
		  	"class" => "nc_videos",
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
					"type" => "vc_link",
					"heading" => esc_html__("Link", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "link",
					"value" => "",
				),

                //tabindex="-1"
                array(
					"type" => "checkbox",
					"heading" => esc_html__("Remove tabindex?", "nextcloud"),
					"description" => esc_html__("", "nextcloud"),
					"param_name" => "remove_tab_index",
					"value" => "",
				),

                array(
                    'type' => 'param_group',
                    'param_name' => 'videos_repeater_items',
                    'params' => array(

                        array(
                            "type" => "dropdown",
                            "holder" => "div",
                            "class" => "",
                            "admin_label" => true,
                            "heading" => __("Element type", "nextcloud"),
                            "param_name" => "element_type",
                            "value" => array(
                                __("Video", "nextcloud") => "video",
                                __("Image", "nextcloud") => "image"
                            ),
                            "std" => "video"
                        ),

                        array(
                            "type" => "attach_image",
                            "holder" => "img",
                            "class" => "",
                            "heading" => __("Image", "nextcloud"),
                            "param_name" => "image",
                            "value" => __("", "nextcloud"),
                            "dependency" => array(
                                "element" => "element_type",
                                "value" => "image"
                            )
                        ),
                        
                        array(
                            "type" => "attach_image",
                            "holder" => "img",
                            "class" => "",
                            "heading" => __("Video poster", "nextcloud"),
                            "param_name" => "video_poster",
                            "value" => __("", "nextcloud"),
                            "dependency" => array(
                                "element" => "element_type",
                                "value" => "video"
                            )
                        ),

                        array(
                            "type" => "textfield",
                            "holder" => "div",
                            "class" => "",
                            "admin_label" => true,
                            "heading" => __("Video link mp4", "nextcloud"),
                            "param_name" => "video_link_mp4",
                            "value" => __("", "nextcloud"),
                            "dependency" => array(
                                "element" => "element_type",
                                "value" => "video"
                            )
                        ),
                        array(
                            "type" => "textfield",
                            "holder" => "div",
                            "class" => "",
                            "admin_label" => true,
                            "heading" => __("Video link ogg", "nextcloud"),
                            "param_name" => "video_link_ogg",
                            "value" => __("", "nextcloud"),
                            "dependency" => array(
                                "element" => "element_type",
                                "value" => "video"
                            )
                        ),

                        array(
                            "type" => "textfield",
                            "holder" => "div",
                            "class" => "",
                            "admin_label" => true,
                            "heading" => __("Video link webm", "nextcloud"),
                            "param_name" => "video_link_webm",
                            "value" => __("", "nextcloud"),
                            "dependency" => array(
                                "element" => "element_type",
                                "value" => "video"
                            )
                        ),

                        array(
                            "type" => "checkbox",
                            "heading" => esc_html__("Hide controls?", "nextcloud"),
                            "description" => esc_html__("", "nextcloud"),
                            "param_name" => "hide_controls",
                            "value" => "",
                            "dependency" => array(
                                "element" => "element_type",
                                "value" => "video"
                            )
                        ),

                        array(
                            "type" => "checkbox",
                            "heading" => esc_html__("Loop", "nextcloud"),
                            "description" => esc_html__("", "nextcloud"),
                            "param_name" => "loop",
                            "value" => "",
                            "dependency" => array(
                                "element" => "element_type",
                                "value" => "video"
                            )
                        ),

                        array(
                            "type" => "textfield",
                            "holder" => "div",
                            "class" => "",
                            "admin_label" => true,
                            "heading" => __("Video width", "nextcloud"),
                            "param_name" => "video_width",
                            "value" => __("", "nextcloud"),
                            "dependency" => array(
                                "element" => "element_type",
                                "value" => "video"
                            )
                        ),
                        array(
                            "type" => "textfield",
                            "holder" => "div",
                            "class" => "",
                            "admin_label" => true,
                            "heading" => __("Video height", "nextcloud"),
                            "param_name" => "video_height",
                            "value" => __("", "nextcloud"),
                            "dependency" => array(
                                "element" => "element_type",
                                "value" => "video"
                            )
                        ),

                        array(
                            "type" => "textfield",
                            "holder" => "div",
                            "class" => "",
                            "admin_label" => true,
                            "heading" => __("Delay", "nextcloud"),
                            "param_name" => "delay",
                            "value" => __("", "nextcloud"),
                        ),

                        array(
                            "type" => "textfield",
                            "heading" => esc_html__("Custom CSS classes", "nextcloud"),
                            "description" => esc_html__("", "nextcloud"),
                            "param_name" => "custom_css_classes",
                            "value" => "",
                        )

                    )
                )

			)
		  )
	  );
}

add_shortcode('nc_videos', 'nc_videos_shortcode_funct');
function nc_videos_shortcode_funct($atts) {
	ob_start();

    $a = shortcode_atts(array(
		'id' => '',
		'custom_css_classes' => '',
        'link' => '',
        'remove_tab_index' => '',
		'videos_repeater_items' => '',
		//'custom_code' => ''
	), $atts);
    $id = $a['id']; 
    $custom_css_classes_videos = $a['custom_css_classes']; 
    $link = vc_build_link($a['link']);
    $remove_tab_index = $a['remove_tab_index'];
    if($remove_tab_index) {
        $remove_tab_index = "-1";
    }

    if(function_exists('vc_param_group_parse_atts')){
        $items = vc_param_group_parse_atts($atts['videos_repeater_items']);
    }
	
    if($items) {

        if(isset($link) && $link['url'] != '' ) {
            echo '<a href="'.$link['url'].'" tabindex="'.$remove_tab_index.'" target="'.$link['target'].'" title="'.$link['title'].'">';
        }
        ?>
        <div class="nc_videos_repeater <?php if(isset($custom_css_classes_videos)) echo $custom_css_classes_videos; ?>">
        <?php
        foreach ($items as $item) {
            
            if(isset($item['video_poster'])) {
                $video_poster = $item['video_poster'];
            }

            if(isset($item['video_link_mp4'])) {
                $video_link_mp4 = $item['video_link_mp4'];
            }

            if(isset($item['video_link_ogg'])) {
                $video_link_ogg = $item['video_link_ogg'];
            }

            if(isset($item['video_link_webm'])) {
                $video_link_webm = $item['video_link_webm'];
            }

            if(isset($item['hide_controls'])) {
                $hide_controls = $item['hide_controls'];
            }

            if(isset($item['loop'])) {
                $loop = $item['loop'];
            }
            if(isset($item['video_width'])) {
                $video_width = $item['video_width'];
            }
            if(isset($item['video_height'])) {
                $video_height = $item['video_height'];
            }
            if(isset($item['custom_css_classes'])) {
                $custom_css_classes = $item['custom_css_classes'];
            }
            if(isset($item['delay'])) {
                $delay = $item['delay'];
            } 


            if( !isset($item['element_type']) || $item['element_type'] == 'video') {
            // echo '<div class="hidden">';
            // print_r($item['element_type']);
            // echo '</div>';
            ?>
            <video id="nc_video" tabindex="-1" width="<?php if(isset($video_width)) echo $video_width; ?>" height="<?php if(isset($video_height)) echo $video_height; ?>" <?php if(!isset($item['hide_controls'])) { echo 'controls="true"'; }?> class="nc_video <?php if(isset($custom_css_classes)) echo $custom_css_classes; ?>" autoplay="true" <?php if(isset($loop)) {
             echo 'loop="true"';   
            }
            ?> muted="true" playsinline="true" <?php 
            if(isset($item['video_poster'])) { 
                echo 'poster="'.wp_get_attachment_image_url($video_poster, 'full').'"';
            }?> style="<?php if($delay) {
                echo 'animation-delay: '.$delay.'s;';
            } ?>">
                <source src="<?php if(isset($video_link_mp4)) echo $video_link_mp4; ?>" type="video/mp4">

                <?php if(isset($video_link_ogg)) { ?>
                    <source src="<?php echo $video_link_ogg; ?>" type="video/ogg">
                <?php } ?>
                
                <?php if(isset($video_link_webm)) { ?>
                    <source src="<?php echo $video_link_webm; ?>" type="video/webm">  
                <?php } ?>
                Your browser does not support the video tag.
            </video>
            <script>
            document.getElementById('nc_video').play();
            </script>
            <?php
            
            } else {

                if(isset($item['image'])) {
                    ?>
                    <div class="nc_video <?php if(isset($custom_css_classes)) echo $custom_css_classes; ?>">
                        <img src="<?php echo wp_get_attachment_image_url($item['image'], 'full'); ?>" class="" alt="">
                    </div>
                    
                    <?php
                }

            }


        } // end foreach
        ?>
        </div>
    <?php
        if(isset($link) && $link['url'] != '' ) {
            echo '</a>';
        }

    }

	$result = ob_get_clean();
	return $result;
}