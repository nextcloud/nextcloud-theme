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

<<<<<<< Updated upstream
=======
				$limit = $default_posts_per_page;

				$search_query = new WP_Query(array(
					'post_type' => array($post_type),
					'posts_per_page' => $default_posts_per_page,
					's' => get_search_query(),
					'post_status' => array('publish'),
					'tag__not_in' => array(269),
					'orderby' => 'date',
					'order' => 'DESC',
					'paged' => $paged,
					'category__not_in' => array(226) //exclude Private category
				));
				$count = $search_query->found_posts;

				if ($search_query->have_posts()) {
					

					while ($search_query->have_posts()) {
						$search_query->the_post();
						get_template_part('inc/blog_loop_single');
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
=======

			
			<?php 
			if ($count > $limit) {
				?>
			<div class="row loadNews_row">
				<div class="col-12">
					<div class="section-button">
						<button class="c-btn btn-main loadNews" data-post-type="<?php

						$post_type_search = '';
						
						if(isset($_GET['wpessid'])) {
							if($_GET['wpessid'] == 1612) {
								$post_type_search = 'post';
							} else if($_GET['wpessid'] == 125618) {
								$post_type_search = 'event';
							} else {
								$post_type_search = 'post';
							}
						}
						if(isset($post_type_search)) echo $post_type_search; ?>" data-count="<?php echo $count; ?>" data-limit="<?php echo $limit; ?>" data-search="true" data-category="" id="loadNews"><?php echo __('Load More','nextcloud'); ?></button>
					</div>
				</div>
			</div>
			<?php } ?>


>>>>>>> Stashed changes
		</div>
	</section>
</div>

<?php get_footer(); ?>
