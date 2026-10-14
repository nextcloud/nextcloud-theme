<?php
/*
 * The template for displaying the footer
 */

if (function_exists('get_field')) {
	$flogo = get_field('footer_logo', 'options');
}
?>
<footer class="<?php
	if (
		is_page_template('page-simplified.php')
		|| get_current_template() == 'single-simplified.php'
		|| get_current_template() == 'single-simplified.php'
	) {
		echo ' footer-simplified ';
	} ?>">
	<div class="container">

		<div class="row">
			<div class="col-12">
			</div>
		</div>

		<?php if (
			!is_page_template('page-simplified.php')
			&& get_current_template() != 'single-simplified.php'
			&& get_current_template() != 'single-on-demand-webinar.php'
		) { ?>

		
		<div class="row">
			<div class="col-lg-4">
			<div class="footer-logo">
			<?php if (is_active_sidebar('footer-widget-area')) : ?>
					<?php dynamic_sidebar('footer-widget-area'); ?>
			<?php endif; ?>
			</div>

			</div>

			<div class="col-lg-8">
				<?php
			wp_nav_menu([
				'theme_location' => 'footer',
				'menu' => 'footer-menu',
				'menu_class' => 'footer-menu',
				'container_id' => 'menu-footer-menu-container',
			]);
			?>
			</div>
		</div>

		<div class="row">
			<div class="col-12">
				<div class="line"></div>
			</div>
		</div>
		<?php } ?>



		
		<div class="row align-items-center">
			<div class="col-lg-8 order-lg-2">
				<div class="social-media-holder">
					<?php
				if (function_exists('get_field')) {
					if (have_rows('social_media', 'options')):
						echo '<ul class="social-menu">';

						echo '<li><a aria-expanded="false" aria-haspopup="true" aria-controls="cookie_banner" class="cookies_preferences_btn" id="cookies_preferences" title="' . __('Cookies preferences', 'nextcloud') . '" href="#">' . __('Cookies preferences', 'nextcloud') . '</a></li>';

						if (
							is_page_template('page-simplified.php')
							|| get_current_template() == 'single-simplified.php'
							|| get_current_template() == 'single-on-demand-webinar.php'
						) {
							echo '<li><a target="_blank" href="' . get_permalink(2371) . '">' . get_the_title(2371) . '</a></li>';
						}

						if (!is_page_template('page-simplified.php')
						&& get_current_template() != 'single-simplified.php') {
							while (have_rows('social_media', 'options')): the_row();
								$socialicon = get_sub_field('social_media_icon');
								$sociallink = get_sub_field('social_media_link');
								$social_title = get_sub_field('social_media_title');
								$rel = '';
								if ($social_title == 'Mastodon') {
									$rel = ' rel="me" ';
								}

								echo '<li><a ' . $rel . ' title="' . $social_title . '" href="' . $sociallink . '" target="_blank"><img src="' . $socialicon . '" alt="' . $social_title . '"/></a></li>';
							endwhile;
						}

						echo '</ul>';
					endif;
				}
?>
				</div>
			</div>
			<div class="col-lg-4 order-lg-1">
				<div class="foot-text">
					<p>© <?php echo '2016 - ' . date('Y'); ?> Nextcloud GmbH</p>
				</div>
			</div>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
<a href="" title="Scroll Up" class="scroll_up" style=""><i class="fa fa-angle-up"></i></a>


<?php if (is_single() && get_post_type() == 'post') : ?>
	<!-- Reading Progress Bar -->
  	<div id="reading-progress-bar"></div>
<?php endif; ?>

</body>
</html>