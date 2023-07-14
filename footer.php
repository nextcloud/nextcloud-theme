<?php
/*
 * The template for displaying the footer
 */
$flogo = get_field('footer_logo', 'options');
?>
<footer>
	<div class="container">
		<div class="row">
			<div class="col-12">
				
			</div>
		</div>
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
				wp_nav_menu(array(
					'theme_location' => 'footer',
					'menu' => 'footer-menu',
					'menu_class' => 'footer-menu',
					'container_id' => 'menu-footer-menu-container',
				));
				?>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<div class="line"></div>
			</div>
		</div>
		<div class="row align-items-center">
			<div class="col-lg-6 order-lg-2">
				<div class="social-media-holder">
					<?php
					if (have_rows('social_media', 'options')):
						echo '<ul class="social-menu">';


						echo '<li><a id="cookies_preferences" title="'.__('Cookies preferences','nextcloud').'" href="#">'.__('Cookies preferences','nextcloud').'</a></li>';
						
						while (have_rows('social_media', 'options')): the_row();
							$socialicon = get_sub_field('social_media_icon');
							$sociallink = get_sub_field('social_media_link');
							$social_title = get_sub_field('social_media_title');
							$rel = '';
							if($social_title == 'Mastodon') {
								$rel = ' rel="me" ';
							}

							echo '<li><a '.$rel.' title="'.$social_title.'" href="' . $sociallink . '" target="_blank"><img src="' . $socialicon . '" alt="'.$social_title.'"/></a></li>';
						endwhile;
						echo '</ul>';
					endif;
					?>
				</div>
			</div>
			<div class="col-lg-6 order-lg-1">
				<div class="foot-text">
					<p>© <?php echo get_the_date('Y')." - ".date("Y"); ?> Nextcloud GmbH</p>
				</div>
			</div>
		</div>
	</div>
</footer>
<?php
/*
if (have_rows('popup_items', 'options')) {
						echo '<div class="modal custom-modal fade" id="trialModal" tabindex="-1" aria-labelledby="trialModalLabel" aria-hidden="true">';
						echo '<div class="modal-dialog modal-xl">';
						echo '<div class="modal-content">';
						echo '<div class="modal-header">';
						echo '<h3>Try Nextcloud</h3>';
						echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
						echo '</div>';
						echo '<div class="modal-body">';
						echo '<div class="wrap-modal-slider">';
						echo '<div class="modal-slider">';
						while (have_rows('popup_items', 'options')) {
							the_row();
							$popicon = get_sub_field('icon');
							$poptitle = get_sub_field('title');
							$poptext = get_sub_field('text');
							$poplink = get_sub_field('link');
							echo '<div>';
							echo '<div class="slider-box">';
							if (!empty($popicon)) {
								echo '<img src="' . $popicon . '" alt=""/>';
							}
							if (!empty($poptitle)) {
								echo '<h4>' . $poptitle . '</h4>';
							}
							if (!empty($poptext)) {
								echo wpautop($poptext);
							}
							if ($poplink) {
								$link_url = $poplink['url'];
								$link_title = $poplink['title'];
								$link_target = $poplink['target'] ? $poplink['target'] : '_self';
								echo '<a class="c-btn btn-blue" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
							}
							echo '</div>';
							echo '</div>';
						}
						echo '</div>';
						echo '</div>';
						echo '</div>';
						echo '</div>';
						echo '</div>';
						echo '</div>';
	}
*/
?>
<?php wp_footer(); ?>

<?php //get_template_part('inc/matomo'); //comment when done with cookie banner ?>

<a href="" title="Scroll Up" class="scroll_up" style=""><i class="fa fa-angle-up"></i></a>

</body>
</html>