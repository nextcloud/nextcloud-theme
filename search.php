<?php
/*
 * The template for displaying search results.
 */

get_header();
?>
<div class="wrapper">
	<section class="single-hero-section" style="">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="section-title">
						<h1><?php echo __('Search results for: ', 'nextcloud'); ?><?php echo '<green>' . get_search_query() . '</green>'; ?></h1>
					</div>
				</div>
			</div>
	</section>

	<section class="blog-section">
		<div class="container">
			<div class="row search-row">
				<div class="col-lg-4"></div>
			<?php
			if (function_exists('wpes_search_form')) {
				$search_id = isset($_GET['wpessid']) ? $_GET['wpessid'] : 1612;

				echo '<div class="col-lg-4">';
				echo '<div class="form-holder">';
				wpes_search_form(array(
					'wpessid' => $search_id
				));
				echo '</div>';
				echo '</div>';
			}
			?>
			<div class="col-lg-4"></div>
			</div>
			<div class="row row-list-blog">
				<?php
				$default_posts_per_page = get_option( 'posts_per_page' ); // should be 9
				$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

				echo '<div class="post_type" style="display:none;">';
				print_r($post_type);
				echo "</div>";

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
					}
				} else {
					echo '<div class="col-12">';
					echo '<div class="not-found">';
					echo '<h3 class="text-center">'.__('No search results for: ', 'nextcloud') . get_search_query() . '</h3>';
					echo '</div>';
					echo '</div>';
				}
				// Restore original Post Data.
				wp_reset_postdata();
				?>
			</div>

			
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


		</div>
	</section>

</div>

<?php get_footer(); ?>
