<?php
if (!defined('WPINC')) {
	die;
}
/*
 * Header file for the theme.
 */
?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>

	<script nowprocket>
	document.addEventListener('click', function (e) {
	if (e.target.closest('.accept_cookie_btn')) {
		document.getElementById('cookie_banner')?.style.setProperty('display', 'none');
	}
	});
	</script>
	
</head>
<body <?php body_class('tenth_anniversary_top_logo'); ?>>
	<?php
	wp_body_open();
?>
	<div id="hidden_header_anchor"></div>
	<header class="<?php
if (
	//get_field('disable_top_banner') &&
	!is_page_template('page-simplified.php')
	&& get_current_template() != 'single-simplified.php'
	&& get_current_template() != 'single-on-demand-webinar.php'
) {
	echo 'with-promo-banner';
}
if (
	is_page_template('page-simplified.php')
	|| get_current_template() == 'single-simplified.php'
	|| get_current_template() == 'single-on-demand-webinar.php'
) {
	echo ' simplified scrolled ';
}
?>" id="header">
		<a href="#main" class="skip"><?php echo __('Skip to main content', 'nextcloud'); ?></a>

		<?php
if (
	!is_page_template('page-simplified.php')
	&& get_current_template() != 'single-simplified.php'
	&& get_current_template() != 'single-on-demand-webinar.php'
) {

	if (function_exists('get_field')) {
		if (!get_field('disable_top_banner')) {
			get_template_part('inc/header-promo-banner');
		}
	}

}
?>

		<div class="container <?php echo get_current_template(); ?>" data-disabled-top-banner="<?php
//echo get_field('disable_top_banner');?>" id="">
			<div class="row">
				<div class="col-12">
					<div class="header-holder">
						<div class="logo-holder">
							<?php
if (function_exists('get_field') && get_field('custom_logo')) {
	echo '<a href="#" class="custom-logo-link">';
	echo '<img class="custom-logo" src="' . get_field('custom_logo') . '">';
	echo '</a>';
} else {

	$custom_header_logo_svg = '';
	if (function_exists('get_field')) {
		$custom_header_logo_svg = get_field('custom_header_logo_svg', 'option');
	}

	if (isset($custom_header_logo_svg)) {
		echo $custom_header_logo_svg;
	} else {
		if (get_custom_logo()) {
			the_custom_logo();
		}
	}
}

?>
						</div>
						<div class="phone-menu">
							<div class="bar1"></div>
							<div class="bar2"></div>
							<div class="bar3"></div>
						</div>
						<div class="header-items">
							<?php

$menu_name = 'primary-menu';
if (function_exists('get_field')) {
	if (get_field('custom_menu_name')) {
		$menu_name = get_field('custom_menu_name');
	}
}

if (
	(is_page_template('page-simplified.php')
	|| get_current_template() == 'single-simplified.php'
	|| get_post_type() == 'on-demand-webinar')
	&& !is_search()
) {
	$menu_name = 'simplified-menu';
}

$args = [
	'theme_location' => 'primary',
	'menu' => $menu_name,
	'container' => 'nav',
	'container_aria_label' => 'Main',
	'menu_class' => 'primary-menu',
	'container_id' => 'menu-primary-menu-container',
	'walker' => new NC_Custom_Walker_Nav_Menu(),
];

wp_nav_menu($args);
?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>