<?php
/*
 * The template for displaying single podcasts
 */

get_header();
?>
<div class="wrapper">
	<?php
	$ids = [];
while (have_posts()) : the_post();
	$date = (string)get_the_date('F d, Y');
	$cat = get_the_category();
	$author_id = (int)get_the_author_meta('ID');
		
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
	if($cat) {
		echo '<ul>';
		foreach ($cat as $c) {
			if($c->term_id != 243 && $c->term_id != 241 && $c->term_id != 60 && $c->term_id != 1) { // exclude Uncategorized, sin categoria and Sin categorizar
				$category_link = get_category_link($c->term_id);
				echo '<a href="'.$category_link.'"> <li>' . $c->name . ' </li></a> ';
			}
		}
		echo '</ul>';
	}
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
						<?php get_template_part('inc/share-block'); ?>
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



		<?php
			// If comments are open or we have at least one comment, load up the comment template.
			if (comments_open() || get_comments_number()) :
				?>
		<section class="comments-section">
			<div class="container">
				
				<div class="row">
					<div class="col-12">
						<div class="single_post_comments">
							<?php
										global $post;
				comments_template();
				?>
						</div>
					</div>
				</div>
			</div>	
		</div>
		<?php endif; ?>


		<section class="related-posts-section">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="section-title">
							<h3><?php echo __('Other podcasts', 'nextcloud'); ?></h3>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<div class="related-slider">
							<?php get_template_part('inc/related_posts'); ?>
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
