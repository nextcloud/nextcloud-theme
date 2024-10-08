<?php
/*
 * The template for displaying press releases
 */

get_header();
?>
<div class="wrapper">
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
				<div class="row">
					<div class="col-lg-4">
						<div class="date-block">
							<span class="date_label">
								<?php echo __('Post date', 'nextcloud'); ?>
							</span>
							<?php
	echo '<p>' . $date . '</p>';
	?>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="cat-block">

							<span class="cat_label">
							<?php echo __('Categories', 'nextcloud'); ?>
							</span>

                            <ul>
                                <li>
                                <a href="https://nextcloud.com/press/" title="<?php echo __('Press releases', 'nextcloud'); ?>"><?php echo __('Press releases', 'nextcloud'); ?></a>
                                </li>
                            </ul>
							
						</div>
					</div>
					<div class="col-lg-4">
						<div class="author-block">

							<span class="author_label">
								<?php echo __('Author', 'nextcloud'); ?>	
							</span>

							<?php
	echo '<p>' . get_the_author_meta('display_name', $author_id) . '</p>';
	?>
						</div>
					</div>
					<div class="col-12">
						<div class="share-block">
							<a class="a2a_button_facebook" href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&t=<?php the_title(); ?>" title="Facebook" rel="nofollow noopener" target="_blank">
								<span class="a2a_svg a2a_s__default a2a_s_facebook" style="background-color: transparent;"><svg focusable="false" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path fill="#ffffff" d="M17.78 27.5V17.008h3.522l.527-4.09h-4.05v-2.61c0-1.182.33-1.99 2.023-1.99h2.166V4.66c-.375-.05-1.66-.16-3.155-.16-3.123 0-5.26 1.905-5.26 5.405v3.016h-3.53v4.09h3.53V27.5h4.223z"></path></svg></span><span class="a2a_label">Facebook</span>
							</a>
							<a class="a2a_button_twitter" href="http://twitter.com/share?text=<?php the_title(); ?>&url=<?php the_permalink();?>" title="Twitter" rel="nofollow noopener" target="_blank"><span class="a2a_svg a2a_s__default a2a_s_twitter" style="background-color: transparent;"><svg focusable="false" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path fill="#ffffff" d="M28 8.557a9.913 9.913 0 01-2.828.775 4.93 4.93 0 002.166-2.725 9.738 9.738 0 01-3.13 1.194 4.92 4.92 0 00-3.593-1.55 4.924 4.924 0 00-4.794 6.049c-4.09-.21-7.72-2.17-10.15-5.15a4.942 4.942 0 00-.665 2.477c0 1.71.87 3.214 2.19 4.1a4.968 4.968 0 01-2.23-.616v.06c0 2.39 1.7 4.38 3.952 4.83-.414.115-.85.174-1.297.174-.318 0-.626-.03-.928-.086a4.935 4.935 0 004.6 3.42 9.893 9.893 0 01-6.114 2.107c-.398 0-.79-.023-1.175-.068a13.953 13.953 0 007.55 2.213c9.056 0 14.01-7.507 14.01-14.013 0-.213-.005-.426-.015-.637.96-.695 1.795-1.56 2.455-2.55z"></path></svg></span><span class="a2a_label">Twitter</span></a>
							<a class="a2a_button_linkedin" href="https://www.linkedin.com/sharing/share-offsite/?url=<?php the_permalink();?>" title="LinkedIn" rel="nofollow noopener" target="_blank"><span class="a2a_svg a2a_s__default a2a_s_linkedin" style="background-color: transparent;"><svg focusable="false" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M6.227 12.61h4.19v13.48h-4.19V12.61zm2.095-6.7a2.43 2.43 0 010 4.86c-1.344 0-2.428-1.09-2.428-2.43s1.084-2.43 2.428-2.43m4.72 6.7h4.02v1.84h.058c.56-1.058 1.927-2.176 3.965-2.176 4.238 0 5.02 2.792 5.02 6.42v7.395h-4.183v-6.56c0-1.564-.03-3.574-2.178-3.574-2.18 0-2.514 1.7-2.514 3.46v6.668h-4.187V12.61z" fill="#ffffff"></path></svg></span><span class="a2a_label">LinkedIn</span></a>
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
							<h3><?php echo __('Other posts', 'nextcloud'); ?></h3>
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
