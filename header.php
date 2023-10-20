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
</head>
<body <?php body_class(); ?>>
	<?php
	wp_body_open();
	?>
	<div id="hidden_header_anchor"></div>
	<header class="<?php if (get_field('header_promo_activation', 'option') ) { echo "with-promo-banner"; }?>" id="header"><?php //class with-promo-banner ?>

		<?php
		if (get_field('header_promo_activation', 'option') ) {
			get_template_part("inc/header-promo-banner");
		}
		?>

		<div class="container" id="">
			<div class="row">
				<div class="col-12">
					<div class="header-holder">
						<div class="logo-holder">
							<?php
							$custom_header_logo_svg = get_field('custom_header_logo_svg', 'option');
							if($custom_header_logo_svg){
								echo $custom_header_logo_svg;
							}else {
								if (get_custom_logo()) {
									the_custom_logo();
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
							wp_nav_menu(array(
								'theme_location' => 'primary',
								'menu' => 'primary-menu',
								'menu_class' => 'primary-menu',
								'container_id' => 'menu-primary-menu-container',
							));
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>