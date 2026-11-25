<?php
/**
 * Sponsor wall: translatable flip-card wall for sponsors.
 *
 * Unlike iconboxes_carousel / box_repeater (which store all items as one
 * url-encoded JSON attribute), every sponsor is its own [nc_sponsor_card]
 * shortcode and the one-liner lives in the shortcode body, so Phrase/WPML
 * can pick it up for translation.
 *
 * [nc_sponsor_wall]
 *   [nc_sponsor_card image="123" tier="gold" name="Acme" activities="speaking,demo"]One-liner.[/nc_sponsor_card]
 * [/nc_sponsor_wall]
 */

/**
 * Tier slug => translated label.
 */
function nc_sponsor_tiers() {
	return [
		'platinum' => __('Platinum', 'nextcloud'),
		'gold' => __('Gold', 'nextcloud'),
		'silver' => __('Silver', 'nextcloud'),
	];
}

/**
 * Activity slug => translated pill label.
 */
function nc_sponsor_activities() {
	return [
		'speaking' => __('Speaking slot', 'nextcloud'),
		'demo' => __('Demo', 'nextcloud'),
		'guided_tour' => __('Guided tour', 'nextcloud'),
	];
}

add_action('vc_before_init', 'nc_sponsor_wall_vc_map');
function nc_sponsor_wall_vc_map() {

	if (class_exists('WPBakeryShortCodesContainer') && !class_exists('WPBakeryShortCode_Nc_Sponsor_Wall')) {
		class WPBakeryShortCode_Nc_Sponsor_Wall extends WPBakeryShortCodesContainer {
		}
	}
	if (class_exists('WPBakeryShortCode') && !class_exists('WPBakeryShortCode_Nc_Sponsor_Card')) {
		class WPBakeryShortCode_Nc_Sponsor_Card extends WPBakeryShortCode {
		}
	}

	vc_map([
		'name' => __('Sponsor wall', 'nextcloud'),
		'base' => 'nc_sponsor_wall',
		'category' => __('Repeater', 'nextcloud'),
		'as_parent' => ['only' => 'nc_sponsor_card'],
		'content_element' => true,
		'show_settings_on_create' => false,
		'is_container' => true,
		'js_view' => 'VcColumnView',
		'icon' => 'nc-wpb-default-icon', // see nc_sponsor_wall_editor_icon_css()
		'description' => __('Flip cards with sponsorship tier', 'nextcloud'),
		'params' => [
			[
				'type' => 'dropdown',
				'heading' => __('Cards per row (desktop)', 'nextcloud'),
				'description' => __('Maximum per row / visible in the carousel. Smaller screens show fewer, phones always 2.', 'nextcloud'),
				'param_name' => 'columns',
				'value' => ['2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6'],
				'std' => '3',
			],
			[
				'type' => 'checkbox',
				'heading' => __('Show as carousel', 'nextcloud'),
				'description' => __('Ticked: carousel with arrows on all screen sizes. Unticked: wall on all screen sizes.', 'nextcloud'),
				'param_name' => 'carousel',
				'value' => [__('Yes', 'nextcloud') => 'yes'],
			],
			[
				'type' => 'textfield',
				'heading' => esc_html__('Custom CSS classes', 'nextcloud'),
				'param_name' => 'custom_css',
				'value' => '',
			],
		],
	]);

	$tier_options = [esc_html__('None', 'nextcloud') => ''];
	foreach (nc_sponsor_tiers() as $slug => $label) {
		$tier_options[$label] = $slug;
	}

	$activity_options = [];
	foreach (nc_sponsor_activities() as $slug => $label) {
		$activity_options[$label] = $slug;
	}

	vc_map([
		'name' => __('Sponsor card', 'nextcloud'),
		'base' => 'nc_sponsor_card',
		'category' => __('Repeater', 'nextcloud'),
		'as_child' => ['only' => 'nc_sponsor_wall'],
		'params' => [
			[
				'type' => 'attach_image',
				'heading' => __('Sponsor logo', 'nextcloud'),
				'param_name' => 'image',
			],
			[
				'type' => 'textfield',
				'heading' => __('Sponsor name', 'nextcloud'),
				'param_name' => 'name',
			],
			[
				'type' => 'dropdown',
				'heading' => __('Sponsorship tier', 'nextcloud'),
				'param_name' => 'tier',
				'value' => $tier_options,
				'std' => '',
			],
			[
				'type' => 'textarea',
				'heading' => __('One-liner', 'nextcloud'),
				'description' => __('Short sentence shown on the back of the card.', 'nextcloud'),
				'param_name' => 'content',
			],
			[
				'type' => 'checkbox',
				'heading' => __('At the event', 'nextcloud'),
				'param_name' => 'activities',
				'value' => $activity_options,
			],
		],
	]);
}

/**
 * WPBakery gives container elements (is_container) a different default icon than normal
 * elements. Point the sponsor wall back to the standard WPBakery icon so it matches the other blocks.
 */
add_action('admin_head', 'nc_sponsor_wall_editor_icon_css');
function nc_sponsor_wall_editor_icon_css() {
	if (!defined('WPB_PLUGIN_DIR')) {
		return;
	}
	$icon = plugins_url('assets/vc/logo/wpb-logo.svg', WPB_PLUGIN_DIR . '/js_composer.php');
	echo '<style>.vc_element-icon.nc-wpb-default-icon, .vc_element-icon.nc-wpb-default-icon[data-is-container=true] { background-image: url(' . esc_url($icon) . '); }</style>';
}

add_shortcode('nc_sponsor_wall', 'nc_sponsor_wall_funct');
function nc_sponsor_wall_funct($atts, $content = null) {
	$atts = shortcode_atts([
		'columns' => '3',
		'carousel' => '',
		'custom_css' => '',
	], $atts, 'nc_sponsor_wall');

	$columns = max(2, min(6, (int)$atts['columns']));
	$carousel = $atts['carousel'] === 'yes';

	return '<div class="iconbox_repeater">'
		. '<div class="iconboxes flip_on_hover sponsor_wall ' . ($carousel ? 'sponsor_wall_carousel ' : '') . esc_attr($atts['custom_css']) . '"'
		. ' style="--sponsor-cols: ' . $columns . ';" data-columns="' . $columns . '">'
		. do_shortcode($content)
		. '</div></div>'
		. nc_sponsor_wall_script();
}

/**
 * Tap / Enter / Space toggles the flip (needed on touch devices, where hover doesn't work).
 * Printed once per page, event delegation covers every wall.
 */
function nc_sponsor_wall_script() {
	static $printed = false;
	if ($printed) {
		return '';
	}
	$printed = true;

	return <<<'HTML'
<script>
(function () {
	// Tap / Enter / Space flips a card
	function toggle(card) {
		var flipped = card.classList.toggle('is-flipped');
		card.setAttribute('aria-pressed', flipped ? 'true' : 'false');
	}
	document.addEventListener('click', function (e) {
		var card = e.target.closest('.sponsor_wall .flip-card');
		if (card) { toggle(card); }
	});
	document.addEventListener('keydown', function (e) {
		if (e.key !== 'Enter' && e.key !== ' ') { return; }
		var card = e.target.closest && e.target.closest('.sponsor_wall .flip-card');
		if (card) { e.preventDefault(); toggle(card); }
	});

	// "Show as carousel" ticked: Owl carousel on all screens (same arrows as .iconboxes_carousel in main.js)
	var arrowPrev = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14.299 22.409" aria-hidden="true"><path fill="#0082c9" d="M11.25,21.205L1.25,11.205,11.25,1.205l1.8,1.8L4.85,11.205l8.2,8.2-1.8,1.8h0Z"/></svg>';
	var arrowNext = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14.299 22.409" aria-hidden="true"><path fill="#0082c9" d="M3.05,21.205l-1.8-1.8,8.2-8.2L1.25,3.005l1.8-1.8,10,10L3.05,21.205h0Z"/></svg>';

	function initSponsorCarousels() {
		var jq = window.jQuery;
		if (!jq || !jq.fn.owlCarousel) { return; }

		jq('.iconboxes.sponsor_wall_carousel').not('.owl-loaded').each(function () {
			var wall = jq(this);
			var cols = parseInt(wall.data('columns'), 10) || 3;
			// same steps as the wall: phones 2, tablet max 3, desktop = chosen value
			// autoplay like the box repeater (.clients_carousel); without loop it rewinds to the start after the last card
			wall.addClass('owl-carousel owl-theme').owlCarousel({
				margin: 15,
				loop: false,
				autoplay: true,
				autoplayTimeout: 5000,
				autoplayHoverPause: true,
				dots: false,
				nav: true,
				navText: [arrowPrev, arrowNext],
				// max cards per screen width, so the square cards stay roughly >= 200px (same idea as the wall's 210px minimum)
				responsive: {
					0: { items: 1 },
					360: { items: 2 },
					768: { items: Math.min(cols, 3) },
					992: { items: Math.min(cols, 4) },
					1200: { items: Math.min(cols, 5) },
					1400: { items: cols }
				}
			});

			// stop autoplay once someone interacts (flips a card / swipes), so the card doesn't slide away while reading
			wall.on('click dragged.owl.carousel', function () {
				wall.trigger('stop.owl.autoplay');
			});
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initSponsorCarousels);
	} else {
		initSponsorCarousels();
	}
})();
</script>
HTML;
}

add_shortcode('nc_sponsor_card', 'nc_sponsor_card_funct');
function nc_sponsor_card_funct($atts, $content = null) {
	$atts = shortcode_atts([
		'image' => '',
		'name' => '',
		'tier' => '',
		'activities' => '',
	], $atts, 'nc_sponsor_card');

	$tiers = nc_sponsor_tiers();
	$tier = isset($tiers[$atts['tier']]) ? $atts['tier'] : '';
	$activities = nc_sponsor_activities();

	ob_start();
	?>
	<div class="nc_iconbox iconbox feature_iconbox sponsor_card <?php if ($tier) {
		echo 'sponsor_tier_' . esc_attr($tier);
	} ?>">
		<div class="link_replacement no_scroll flip-card" role="button" tabindex="0" aria-pressed="false" aria-label="<?php echo esc_attr($atts['name']); ?>">

			<div class="iconbox_container flip-card-inner no_link">

				<div class="flip-card-front">
					<?php if ($tier) { ?>
					<span class="sponsor_tier"><?php echo esc_html($tiers[$tier]); ?></span>
					<?php } ?>

					<?php if ($atts['image']) { ?>
					<div class="image-icon">
						<div class="vc_single_image-wrapper">
							<?php echo wp_get_attachment_image($atts['image'], 'large', false, ['alt' => esc_attr($atts['name'])]); ?>
						</div>
					</div>
					<?php } else { ?>
					<h4 class="sponsor_name_front"><?php echo esc_html($atts['name']); ?></h4>
					<?php } ?>
				</div>

				<div class="flip-card-back">
					<?php if ($atts['name']) { ?>
					<div class="title"><h4><?php echo esc_html($atts['name']); ?></h4></div>
					<?php } ?>

					<?php if (trim((string)$content) !== '') { ?>
					<div class="description"><?php echo wp_kses_post(trim($content)); ?></div>
					<?php } ?>

					<?php
					$selected = array_filter(array_map('trim', explode(',', $atts['activities'])));
	if ($selected) { ?>
					<ul class="sponsor_pills">
						<?php foreach ($selected as $slug) {
							if (isset($activities[$slug])) { ?>
							<li><?php echo esc_html($activities[$slug]); ?></li>
						<?php }
							} ?>
					</ul>
					<?php } ?>
				</div>

			</div>

		</div>
	</div>
	<?php
	return ob_get_clean();
}
