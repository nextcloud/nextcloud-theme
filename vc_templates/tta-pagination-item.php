<?php
/**
 * Template for pagination item of Tabbed-Toggles-Accordions elements.
 *
 * @since 8.3
 * @var string $classes
 * @var int $current
 * @var array $section
 */

if (! defined('ABSPATH')) {
	die('-1');
}
?>

<li class="<?php echo esc_attr($classes); ?>" data-vc-tab>
	<a aria-label="<?php esc_attr_e('Pagination Item', 'js_composer'); ?> <?php echo esc_attr($current); ?>" href="#<?php echo esc_attr($section['tab_id']); ?>" class="vc_pagination-trigger" data-vc-tabs data-vc-container=".vc_tta"></a>
</li>
