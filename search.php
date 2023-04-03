<?php
/*
 * The template for displaying search results.
 */

get_header();
?>
<div class="wrapper">
	<section class="single-hero-section" style="background-color: #1cafff;">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="section-title">
						<h1><?php _e('Search results for: '); ?><?php echo '<green>' . get_search_query() . '</green>'; ?></h1>
					</div>
				</div>
			</div>
	</section>
	<section class="blog-section">
		<div class="container">
			<div class="row">
				<?php
				if (have_posts()) {
					while (have_posts()) {
						the_post();

						get_template_part('inc/blog_loop_single');


					}
				} else {
					echo '<div class="col-12">';
					echo '<div class="not-found">';
					echo '<h3>No search results for: ' . get_search_query() . '</h3>';
					echo '</div>';
					echo '</div>';
				}
				?>
			</div>
		</div>
	</section>
</div>

<?php get_footer(); ?>
