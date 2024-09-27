<?php
/*
 * Template Name: Simplified Article
 * Template Post Type: post, page, event
 */

get_header();
?>
<div class="wrapper" id="main">
	<?php
	$ids = [];
	while (have_posts()) : the_post();
	?>
		<section class="post-single-section">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-lg-10 col-md-12">
						<div class="text-block">
						<?php
						echo do_shortcode(apply_filters('the_content', get_the_content()));
						endwhile; // End of the loop.
						?>
						</div>
					</div>
				</div>
			</div>
		</section>
</div>
<?php
get_footer();
