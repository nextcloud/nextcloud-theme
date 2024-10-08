<?php
/*
 * The template for displaying single posts and pages.
 */

get_header();
?>
<div class="wrapper" id="main">
	<?php
	$ids = [];
while (have_posts()) : the_post();

	$date_format = get_option('date_format');
	$date = (string)get_the_date($date_format);

	$cat = get_the_category();
	$author_id = (int)get_the_author_meta('ID');
	$custom_header_image = get_field('custom_header_image');
	?>
		<section class="single-hero-section <?php if($custom_header_image) {
			echo "custom_header_image";
		} ?>" style="<?php if($custom_header_image) {
			echo "background: url(".$custom_header_image.");";
		} ?>">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="section-title">
							<?php
							echo '<h1>' . get_the_title() . '</h1>';
	?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section class="post-single-section">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-lg-8 col-md-12">
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
