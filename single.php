<?php
/*
 * The template for displaying single posts and pages.
 */

get_header();
?>
<div class="wrapper">
	<?php
	while (have_posts()) : the_post();
		$date = get_the_date('F d, Y');
		$cat = get_the_category($the_post);
		$id = get_the_ID();
		$author_id = get_the_author_meta('ID');
	?>
		<section class="single-hero-section" style="background-color: #1cafff;">
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
				<div class="row">
					<div class="col-lg-4">
						<div class="date-block">
							<?php
							echo '<p>' . $date . '</p>';
							?>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="cat-block">
							<?php
							echo '<ul>';
							foreach ($cat as $c) {
								//	$category_link = get_category_link($c->term_id);
								echo '<li>' . $c->cat_name . '</li>';
							}
							echo '</ul>';
							?>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="author-block">
							<?php
							echo '<p>' . get_the_author_meta('display_name', $author_id) . '</p>';
							?>
						</div>
					</div>
					<div class="col-12">
						<div class="share-block">
							<?php
							echo do_shortcode('[addtoany]');
							?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section class="post-single-section">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
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
		<section class="related-posts-section">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="section-title">
							<h3>Other Posts</h3>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<div class="related-slider">
							<?php
							$my_wp_query = new WP_Query();
							$onepost = $my_wp_query->query(array(
								'post_type' => 'post',
								'post__not_in' => array($id),
								'posts_per_page' => 3,
								'post_status' => 'publish',
								'orderby' => 'date',
								'order' => 'DSC',
							));
							foreach ($onepost as $onepostsingle) {
								$img = wp_get_attachment_url(get_post_thumbnail_id($onepostsingle->ID));
								$title = $onepostsingle->post_title;
								$ex = $onepostsingle->post_excerpt;
								$link = get_permalink($onepostsingle->ID);
								echo '<div>';
								echo '<div class="post-box">';
								echo '<div class="post-img" style="background-image: url(' . $img . ');"></div>';
								echo '<div class="post-body">';
								echo '<h4>' . $title . '</h4>';
								echo '<p>' . $ex . '</p>';
								echo '<a class="c-btn" href="' . $link . '">Read More</a>';
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
		$forum = get_field('footer_text', 'options');
		$link = get_field('footer_link', 'options');
		if (!empty($forum)) {
			?>
			<section class="get-started-section">
				<div class="container">
					<div class="row justify-content-center">
						<div class="col-lg-10">
							<div class="text-block">
								<?php
								echo '<h3>' . $forum . '</h3>';
			if ($link) {
				$link_url = $link['url'];
				$link_title = $link['title'];
				$link_target = $link['target'] ? $link['target'] : '_self';
				echo '<a class="c-btn btn-white" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
			} ?>
							</div>
						</div>
					</div>
				</div>
			</section>
		<?php
		}
		?>
</div>
<?php
get_footer();
