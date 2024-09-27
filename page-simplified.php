<?php
/**
* Template Name: Simplified page template
*/
get_header();
?>
<div class="wrapper wrapper_simplified" id="main">
	<?php
	$ids = [];
	while (have_posts()) : the_post();
		$date_format = get_option( 'date_format' );
		//$date = (string)get_the_date($date_format);
		//$cat = get_the_category();
		//$author_id = (int)get_the_author_meta('ID');
		$custom_header_image = get_field('custom_header_image');
	?>

		<section class="vc_section">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-lg-10 col-md-12">
						<div class="text-block">
						<?php
						echo do_shortcode(apply_filters('the_content', get_the_content()));
						?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php endwhile; // End of the loop. ?>

		
</div>
<?php
get_footer();
