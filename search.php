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
						<h1><?php echo __('Search results for: ', 'nextcloud'); ?><?php echo '<green>' . esc_html(get_search_query(false)) . '</green>'; ?></h1>
					</div>
				</div>
			</div>
	</section>

	<section class="blog-section">
		<div class="container">
			
			<div class="row search-row">
				<div class="col-lg-4"></div>
				<?php
				global $wp_query;

// Search profile from ?search_profile= (or legacy ?wpessid= / ?custom_type=), see inc/search-profiles.php
$search_profile = function_exists('nc_search_requested_profile_key') ? nc_search_requested_profile_key() : '';

if (function_exists('nc_search_form')) {

	echo '<div class="col-lg-4">';
	echo '<div class="form-holder">';

	if ($search_profile !== '') {
		nc_search_form($search_profile);
	} else {
		//get_search_form();
		?>
						<form action="/" role="search" class="search-form" method="get">
							<label for="search">
								<span class="screen-reader-text">Search for:</span>    
								<input type="search" class="search-field " placeholder="Search …" value="<?php the_search_query(); ?>" name="s">
							</label>
							<input type="submit" class="search-submit " value="Search">
						</form>
						<?php
	}

	echo '</div>';
	echo '</div>';
}
?>
				<div class="col-lg-4"></div>
			</div>

			<div class="row row-list-blog">
				<?php
$default_posts_per_page = get_option('posts_per_page'); // should be 9
$limit = $default_posts_per_page;
//$count = $wp_query->get( 'filtered_post_count' );
$count = $wp_query->get('total_found_posts');

if (current_user_can('manage_options') && isset($wp_query->request)) {
	echo "<div class='hidden'>" . implode(',', (array)$wp_query->get('post_type')) . '</div>';
	echo "<div class='hidden'>Count: " . $count . '</div>';
	echo '<pre class="request hidden" style="white-space: pre-wrap;">' . esc_html($wp_query->request) . '</pre>';
}

/*
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
//wp_reset_postdata();
*/
?>
				<?php if (have_posts()) : ?>
						<?php while (have_posts()) : the_post(); ?>
						<?php get_template_part('inc/blog_loop_single'); ?> 
						<?php endwhile; ?>
					<?php else :

						echo '<div class="col-12">';
						echo '<div class="not-found">';
						echo '<h3 class="text-center">' . __('No search results for: ', 'nextcloud') . get_search_query() . '</h3>';
						echo '</div>';
						echo '</div>';

					endif; ?>

			</div>

			
			<?php
			if ($count > $limit) {
				$post_type_search = '';
				if ($search_profile !== '') {
					$post_type_search = nc_search_profiles()[$search_profile]['load_more_post_type'] ?? 'post';
				}
				?>
			<div class="row loadNews_row">
				<div class="col-12">
					<div class="section-button">
						<button class="c-btn btn-main loadNews" 
						data-post-type="<?php echo esc_attr($post_type_search); ?>" 
						data-search_profile="<?php echo esc_attr($search_profile); ?>" 
						data-count="<?php echo $count; ?>" data-limit="<?php echo $limit; ?>" 
						data-search="true" 
						data-search_query="<?php echo the_search_query(); ?>" 
						data-category="" id="loadNews"><?php echo __('Load more', 'nextcloud'); ?></button>
					</div>
				</div>
			</div>
			<?php } ?>


		</div>
	</section>

</div>

<?php get_footer(); ?>