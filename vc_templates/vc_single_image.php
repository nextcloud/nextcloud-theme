<?php
/**
 * The template for displaying [vc_single_image] shortcode output of 'Single image' element.
 *
 * This template overrides yourtheme/vc_templates/vc_single_image.php.
 *
 * @see https://kb.wpbakery.com/docs/developers-how-tos/change-shortcodes-html-output
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 *
 * @var array $atts
 * Shortcode class
 * @var WPBakeryShortCode_Vc_Single_image $this
 */
$init_atts = $atts;
$title = $source = $image = $custom_src = $onclick = $img_size = $external_img_size = $caption = $img_link_large = $link = $alignment = $el_class = $el_id = $css_animation = $style = $external_style = $border_color = $css = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

/**
 * Extracted values.
 *
 * @var string $title
 * @var string $source
 * @var string $image
 * @var string $custom_src
 * @var string $onclick
 * @var string $img_size
 * @var string $external_img_size
 * @var string $caption
 * @var string|null $img_link_large
 * @var string $link
 * @var string $alignment
 * @var string $el_class
 * @var string $el_id
 * @var string $css_animation
 * @var string $style
 * @var string $external_style
 * @var string $border_color
 * @var string $css
 * @var string $external_border_color
 * @var string $img_id
 * @var string $add_caption
 */

$default_src = vc_asset_url( 'vc/no_image.png' );

// backward compatibility. since 4.6.
if ( empty( $onclick ) && isset( $img_link_large ) && 'yes' === $img_link_large ) {
	$onclick = 'img_link_large';
} elseif ( empty( $atts['onclick'] ) && ( ! isset( $atts['img_link_large'] ) || 'yes' !== $atts['img_link_large'] ) ) {
	$onclick = 'custom_link';
}

if ( 'external_link' === $source ) {
	$style = $external_style;
	$border_color = $external_border_color;
}

$border_color_style = '';
$is_outline_style = 0 === strpos( $style, 'vc_box_outline' );
$is_border_style = $is_outline_style || 0 === strpos( $style, 'vc_box_border' );
if ( ! $is_border_style ) {
	$border_color = '';
} else {
	if ( '' === $border_color ) {
		$border_color = '#EBEBEB';
	}
	if ( preg_match( '/^(#|rgb|hsl)/i', $border_color ) ) {
		$border_property = $is_outline_style ? 'border-color' : 'background-color';
		$border_color_style = $border_property . ':' . esc_attr( $border_color ) . ';';
		$border_color = '';
	} else {
		$border_color = ' vc_box_border_' . $border_color;
	}
}

$img = false;

switch ( $source ) {
	case 'media_library':
		$image_data = json_decode( $image, true );
		if ( is_array( $image_data ) ) {
			$image = array_key_first( $image_data );

			$parts = [];
			foreach ( array_values( $image_data )[0] as $key => $value ) {
				$parts[] = $key . ':' . rawurlencode( $value );
			}
			$link = implode( '|', $parts );
		}

		// no break here cos we process then the same way how we process feature image.
	case 'featured_image':
		if ( 'featured_image' === $source ) {
			$post_id = get_the_ID();
			if ( $post_id && has_post_thumbnail( $post_id ) ) {
				$img_id = get_post_thumbnail_id( $post_id );
			} else {
				$img_id = 0;
			}
		} else {
			$img_id = preg_replace( '/[^\d]/', '', $image );
		}

		// set rectangular.
		if ( preg_match( '/_circle_2$/', $style ) ) {
			$style = preg_replace( '/_circle_2$/', '_circle', $style );
			$img_size = $this->getImageSquareSize( $img_id, $img_size );
		}

		if ( ! $img_size ) {
			$img_size = 'medium';
		}

		$img = wpb_getImageBySize( [
			'attach_id' => $img_id,
			'thumb_size' => strtolower( $img_size ),
			'class' => 'vc_single_image-img',
		] );

		// Get the extra attribute we added via vc_add_param
		$img_inline_css = isset( $atts['img_inline_css'] ) ? trim( $atts['img_inline_css'] ) : '';

		if ( ! empty( $img_inline_css ) && $img ) {
			// Make sure it ends with a semicolon for safety
			if ( substr( $img_inline_css, -1 ) !== ';' ) {
				$img_inline_css .= ';';
			}

			// Add style attribute to the <img> in $img['thumbnail']
			// This preserves any existing attributes/classes on the <img>
			$img['thumbnail'] = preg_replace(
				'/<img([^>]*?)>/i',
				'<img$1 style="' . esc_attr( $img_inline_css ) . '">',
				$img['thumbnail']
			);
		}

		// don't show placeholder in public version if post doesn't have featured image.
		if ( 'featured_image' === $source ) {
			if ( ! $img && 'page' === vc_manager()->mode() ) {
				return;
			}
		}

		break;

	case 'external_link':
		$dimensions = vc_extract_dimensions( $external_img_size );
		$hwstring = $dimensions ? image_hwstring( $dimensions[0], $dimensions[1] ) : '';

		$custom_src = $custom_src ? $custom_src : $default_src;
		$attributes = [
			'src' => esc_url( $custom_src ),
			'class' => 'vc_single_image-img',
		];
		$attributes = vc_add_lazy_loading_attribute( $attributes );

		$img = [
			'thumbnail' => '<img ' . $hwstring . ' ' . vc_stringify_attributes( $attributes ) . '" />',
		];
		break;

	default:
		$img = false;
}

if ( ! $img ) {
	$img = [
		'thumbnail' => '<img class="vc_img-placeholder vc_single_image-img" src="' . esc_url( $default_src ) . '" />',
	];
}

$el_class = $this->getExtraClass( $el_class );

// backward compatibility.
if ( vc_has_class( 'prettyphoto', $el_class ) ) {
	$onclick = 'link_image';
}

// backward compatibility.
if ( in_array( $link, [
	'none',
	'link_no',
], true ) ) {
	$link = '';
}

$a_attrs = [];

switch ( $onclick ) {
	case 'img_link_large':
		if ( 'external_link' === $source ) {
			$link = $custom_src;
		} else {
			$link = wp_get_attachment_image_src( $img_id, 'large' );
			if ( isset( $link[0] ) ) {
				$link = $link[0];
			}
		}

		break;

	case 'link_image':
		wp_enqueue_script( 'lightbox2' );
		wp_enqueue_style( 'lightbox2' );

		$a_attrs['class'] = '';
		$a_attrs['data-lightbox'] = 'lightbox[rel-' . get_the_ID() . '-' . wp_rand() . ']';

		// backward compatibility.
		if ( ! vc_has_class( 'prettyphoto', $el_class ) && 'external_link' === $source ) {
			$link = $custom_src;
		} elseif ( ! vc_has_class( 'prettyphoto', $el_class ) ) {
			$link = wp_get_attachment_image_src( $img_id, 'large' );
			$link = $link[0];
		}

		break;

	case 'zoom':
		wp_enqueue_script( 'vc_image_zoom' );

		if ( 'external_link' === $source ) {
			$large_img_src = $custom_src;
		} else {
			$large_img_src = wp_get_attachment_image_src( $img_id, 'large' );
			if ( $large_img_src ) {
				$large_img_src = $large_img_src[0];
			}
		}

		$img['thumbnail'] = str_replace( '<img ', '<img data-vc-zoom="' . esc_url( $large_img_src ) . '" ', $img['thumbnail'] );

		break;
}

// backward compatibility.
if ( vc_has_class( 'prettyphoto', $el_class ) ) {
	$el_class = vc_remove_class( 'prettyphoto', $el_class );
}

$wrapper_class = 'vc_single_image-wrapper ' . esc_attr( $style ) . ' ' . esc_attr( $border_color );
$wrapper_inline_style = $border_color_style;

if ( $link ) {
	// B.C for href param type conversion since 9.0.
	$link_par_list = [ 'url:', 'target:', 'rel:', 'title:' ];
	$is_link_param_type = preg_match( '/' . implode( '|', $link_par_list ) . '/', $link );

	if ( ! $is_link_param_type ) {
		$link = 'url:' . rawurlencode( $link );

		if ( ! empty( $init_atts['img_link_target'] ) && '_blank' === $init_atts['img_link_target'] ) {
			$link .= '|target:_blank';
		}
	}

	$wpb_link = vc_build_link( $link );
	$wpb_link['title'] = get_the_title( $image );

	if ( ! empty( $a_attrs['class'] ) ) {
		$wrapper_class .= ' ' . $a_attrs['class'];
	}
	unset( $a_attrs['class'] );

	if ( '' !== $wrapper_inline_style ) {
		$a_attrs['style'] = $wrapper_inline_style;
	}
	$html = vc_get_template( 'partials/element-link.php', [
		'a_attrs' => $a_attrs,
		'class' => $wrapper_class,
		'text' => $img['thumbnail'],
		'wpb_link' => $wpb_link,
	] );
} else {
	$style_attr = '' !== $wrapper_inline_style ? ' style="' . esc_attr( $wrapper_inline_style ) . '"' : '';
	$html = '<div class="' . $wrapper_class . '"' . $style_attr . '>' . $img['thumbnail'] . '</div>';
}

$settings = $this->getSettings();
$element_class = empty( $settings['element_default_class'] ) ? '' : $settings['element_default_class'];
$class_to_filter = 'wpb_single_image wpb_content_element vc_align_' . $alignment . ' ' . esc_attr( $element_class ) . $this->getCSSAnimation( $css_animation );
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $settings['base'], $atts );

if ( in_array( $source, [ 'media_library', 'featured_image' ], true ) && 'yes' === $add_caption ) {
	$img_id = apply_filters( 'wpml_object_id', $img_id, 'attachment', true );
	$caption = wp_get_attachment_caption( $img_id );
} else {
	if ( 'external_link' === $source ) {
		$add_caption = 'yes';
	}
}

if ( 'yes' === $add_caption && '' !== $caption ) {
	$html .= '<figcaption class="vc_figure-caption">' . wp_kses_post( $caption ) . '</figcaption>';
}
$wrapper_attributes = [];
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
$output = '
	<div ' . implode( ' ', $wrapper_attributes ) . ' class="' . esc_attr( trim( $css_class ) ) . '">
		' . wpb_widget_title( [
	'title' => $title,
	'extraclass' => 'wpb_singleimage_heading',
] ) . '
		<figure class="wpb_wrapper vc_figure">
			' . $html . '
		</figure>
	</div>
';

return $output;
