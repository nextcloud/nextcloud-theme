<?php
//Nextcloud single video element
add_action('vc_before_init', 'wpb_nc_video_funct');
function wpb_nc_video_funct() {
	vc_map(
		[
			'name' => __('Nextcloud Video', 'nextcloud'), // Element name
			'base' => 'nc_video', // Element shortcode
			'class' => 'nc_video',
			'category' => __('Content', 'nextcloud'),
			'params' => [
				[
					'type' => 'attach_image',
					'holder' => 'img',
					'class' => '',
					'heading' => __('Video poster', 'nextcloud'),
					'param_name' => 'video_poster',
					'value' => __('', 'nextcloud'),
				],
				[
					'type' => 'textfield',
					'holder' => 'div',
					'class' => '',
					'admin_label' => true,
					'heading' => __('Video link mp4', 'nextcloud'),
					'param_name' => 'video_link_mp4',
					'value' => __('', 'nextcloud'),
				],
				[
					'type' => 'textfield',
					'holder' => 'div',
					'class' => '',
					'admin_label' => true,
					'heading' => __('Video link ogg', 'nextcloud'),
					'param_name' => 'video_link_ogg',
					'value' => __('', 'nextcloud'),
				],
				[
					'type' => 'checkbox',
					'heading' => esc_html__('Hide controls?', 'nextcloud'),
					'description' => esc_html__('', 'nextcloud'),
					'param_name' => 'hide_controls',
					'value' => '',
				],
				[
					'type' => 'textfield',
					'heading' => esc_html__('Custom CSS classes', 'nextcloud'),
					'description' => esc_html__('', 'nextcloud'),
					'param_name' => 'custom_css_classes',
					'value' => '',
				],
				[
					'type' => 'textfield',
					'holder' => 'div',
					'class' => '',
					'admin_label' => true,
					'heading' => __('Video width', 'nextcloud'),
					'param_name' => 'video_width',
					'value' => __('', 'nextcloud'),
				],
				[
					'type' => 'textfield',
					'holder' => 'div',
					'class' => '',
					'admin_label' => true,
					'heading' => __('Video height', 'nextcloud'),
					'param_name' => 'video_height',
					'value' => __('', 'nextcloud'),
				],

			]
		]
	);
}

add_shortcode('nc_video', 'nc_video_shortcode_funct');
function nc_video_shortcode_funct($atts) {
	ob_start();
	$a = shortcode_atts([
		'video_poster' => '',
		'video_link_mp4' => '',
		'video_link_ogg' => '',
		'hide_controls' => '',
		'custom_css_classes' => '',
		'video_width' => '',
		'video_height' => '',
	], $atts);

	$video_poster = $a['video_poster'];
	

	$video_link_mp4 = $a['video_link_mp4'];
	$video_link_ogg = $a['video_link_ogg'];
	$hide_controls = $a['hide_controls'];
	//echo "controls: ".$a['controls'];

	$custom_css_classes = $a['custom_css_classes'];
	$video_width = $a['video_width'];
	$video_height = $a['video_height'];
	?>
    <video id="nc_video" width="<?php if (isset($video_width)) {
    	echo $video_width;
    } ?>" height="<?php if (isset($video_height)) {
    	echo $video_height;
    } ?>" <?php if (!isset($a['hide_controls'])) {
    	echo 'controls="true"';
    }?> class="nc_video <?php if (isset($custom_css_classes)) {
    	echo $custom_css_classes;
    } ?>" autoplay="true" loop="true" muted="true" playsinline="true" <?php
	if (isset($a['video_poster'])) {
		echo 'poster="' . wp_get_attachment_image_url($video_poster, 'full') . '"';
	}?>>
        <source src="<?php if (isset($video_link_mp4)) {
        	echo $video_link_mp4;
        } ?>" type="video/mp4">
        <source src="<?php if (isset($video_link_ogg)) {
        	echo $video_link_ogg;
        } ?>" type="video/ogg">
        Your browser does not support the video tag.
    </video>
    <script>
    document.getElementById('nc_video').play();
    </script>
    <?php
	$result = ob_get_clean();
	return $result;
}
