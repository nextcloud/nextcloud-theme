<?php
/*
 * The template for displaying Category/Tag pages
 */

get_header();
?>
<div class="wrapper">
	<section class="single-hero-section" style="background-color: #1cafff;">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="section-title">
						<h1>
							<?php
							the_archive_title();
?>
						</h1>
					</div>
				</div>
			</div>
	</section>
	<section class="blog-section">
		<div class="container">
			<div class="row row-list-blog">
				<?php
				global $wp_query;
$default_posts_per_page = get_option('posts_per_page'); // should be 9
$limit = $default_posts_per_page;
$count = $wp_query->found_posts;
				
				
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
add_query_arg('paged', $paged);
//add_query_arg('post_type', 'post');
				
if (have_posts()) : ?>
				<?php
	// Start the Loop
	while (have_posts()) : the_post();

		if (get_post_type() != 'acf-field-group') {

			$webinar = false;
			if (get_post_type() == 'event') {
				$cat = wp_get_object_terms(get_the_ID(), 'event_categories', []);
				if ($cat) {
					foreach ($cat as $c) {
						if ($c->term_id == 250) { // event_categories Webinars
							$webinar = true;
							break;
						}
					}
				}
			}
		
			if ($webinar) {
				get_template_part('inc/webinar_loop_single');
			} else {
				get_template_part('inc/blog_loop_single');
			}

		}

	endwhile;
endif;

$category = get_category(get_query_var('cat'));
if (isset($category)) {
	if (isset($category->cat_ID)) {
		$cat_id = $category->cat_ID;
	}
					
}

if (get_post_type() == 'event') {
	$cat_id = get_queried_object()->term_id;
}

?>
			</div>


			<?php
			if ($count > $limit) {
				?>
			<div class="row loadNews_row">
				<div class="col-12">
					<div class="section-button">
						<button class="c-btn btn-main loadNews" data-post-type="<?php echo get_post_type(); ?>" data-category="<?php
							if (isset($cat_id)) {
								echo $cat_id;
							} ?>" id="loadNews"><?php echo __('Load More', 'nextcloud'); ?></button>
					</div>
				</div>
			</div>
			<?php
			}
?>


		</div>
	</section>
</div>
<?php
get_footer();
