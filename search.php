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

						$post_id = get_the_ID();
						//$img = wp_get_attachment_url(get_post_thumbnail_id($post_id) ?: 0) ?: '';
						$title = get_the_title();
						$post_excerpt = get_the_excerpt();
						$link = get_permalink();
						$featured_image = get_the_post_thumbnail($post_id, 'large', array( 'class' => 'feat_img' ));
						echo '<div class="col-lg-4 col-md-6 spacer news-container news-item" style="">';
						echo '<div class="post-box">';
						echo '<div class="post-img" style=""><a href="'.$link.'" title="'.$title.'">'.$featured_image.'</a></div>';
						echo '<div class="post-body">';
						echo '<h4><a href="'.$link.'" title="'.$title.'">' . $title . '</a></h4>';
						echo '<p>' . $post_excerpt . '</p>';
						echo '<a class="c-btn" href="' . $link . '">'.__('Read More', 'nextcloud').'</a>';
						echo '</div>';
						echo '</div>';
						echo '</div>';

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
