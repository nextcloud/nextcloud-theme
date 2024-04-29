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
<<<<<<< Updated upstream
	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
	<?php
	wp_body_open();
	?>
	<div id="hidden_header_anchor"></div>
	<header class="" id="header"><?php //class with-vote-banner?>

		<?php //get_template_part("inc/banner-vote"); // when removing, remove also class from header?>
=======
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<?php
	wp_body_open();
	?>
	<div id="hidden_header_anchor"></div>
	<header class="<?php if (get_field('header_promo_activation', 'option') ) { echo "with-promo-banner"; }?>" id="header"><?php //class with-promo-banner ?>
		
		<a href="#main" class="skip"><?php echo __('Skip to main content','nextcloud'); ?></a>

		<?php
			get_template_part("inc/header-promo-banner");
		?>
>>>>>>> Stashed changes

		<div class="container" id="">
			<div class="row">
				<div class="col-12">
					<div class="header-holder">
						<div class="logo-holder">
							<?php
							if (get_custom_logo()) {
								the_custom_logo();
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