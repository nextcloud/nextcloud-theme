<?php
/*
 * Template Name: Case study full width (no form) template
 * Template Post Type: case_studies
 */

get_header(); ?>

<div class="wrapper">
	<?php
	//wp_enqueue_script('custom-nf-code');

	$ids = [];
if (have_posts()) :
	while (have_posts()) : the_post();
		$date = (string)get_the_date('F d, Y');
		$cat = get_the_category();
		$author_id = (int)get_the_author_meta('ID');
		$id = get_the_ID();
		if ($id !== false) {
			$ids[] = $id;
		}
		$custom_header_image = get_field('custom_header_image');

		?>
		<section class="single-hero-section <?php if ($custom_header_image) {
			echo 'custom_header_image';
		} ?>" style="<?php if ($custom_header_image) {
			echo 'background: url(' . $custom_header_image . ');';
		} ?>">
			<div class="container">

				<div class="row">
					<div class="col-12">
					</div>
				</div>


				<div class="row">
					<div class="col-lg-12">

						<div class="section-title text-center">
							<span class="whitepaper-label">
                            <?php
							echo __('Case study', 'nextcloud');
		?>
                            </span>
							<?php
		echo '<h1>' . get_the_title() . '</h1>';
		$sub_headline = get_field('case_study_sub-headline');
		if ($sub_headline) {
			echo '<h2>' . esc_html($sub_headline) . '</h2>';
		}
		?>
							
						</div>

					</div>

				
				</div>
			</div>
		</section>

		<section class="post-single-section">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-lg-12 col-md-12">
						<div class="text-block">
						<?php
						echo apply_filters('the_content', get_the_content());
		?>
						</div>
					</div>


				</div>
			</div>
		</section>

		<section class="related-posts-section">
			<div class="container">

				<div class="row">
					<div class="col-12">

						<div class="section-title">
							<h3> 
                            <?php
				echo __('Other case studies', 'nextcloud');
		?>
                            </h3>
						</div>

						<div class="row related-slider-old">
							<?php
		$my_wp_query = new WP_Query();
		/** @var WP_Post[] $onepost */
		$onepost = $my_wp_query->query([
			'post_type' => get_post_type(),
			'post__not_in' => $ids,
			'posts_per_page' => 3,
			'post_status' => 'publish',
			'orderby' => 'date',
			'order' => 'DESC',
			//'category__not_in'=> array(225, 226) //exclude Private category
		]);
		foreach ($onepost as $onepostsingle) {
			$post_id = $onepostsingle->ID;
			//$img = wp_get_attachment_url(get_post_thumbnail_id($post_id) ?: 0) ?: '';
			$title = $onepostsingle->post_title;
			$ex = $onepostsingle->post_excerpt;
			$link = (string)get_permalink($post_id);
			$featured_image = get_the_post_thumbnail($post_id, 'large', [ 'class' => 'feat_img' ]);

			echo '<div class="col-lg-4">';
			echo '<div class="post-box">';
			echo '<div class="post-img" style=""><a href="' . $link . '" title="' . $title . '">' . $featured_image . '</a></div>';
			echo '<div class="post-body">';
			echo '<h4><a href="' . $link . '" title="' . $title . '">' . $title . '</a></h4>';
			echo '<p>' . $ex . '</p>';
			echo '<a class="c-btn" href="' . $link . '">' . __('Download', 'nextcloud') . '</a>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
		wp_reset_query();
		?>
						</div>
					</div>
				</div>
			</div>
		</section>

		<?php
	endwhile; // End of the loop.
endif;
?>

</div>
<?php get_footer();
