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
			<div class="row">
				<?php if (have_posts()) : ?>
				<?php
					// Start the Loop
					while (have_posts()) : the_post();
						$img = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
						$title = get_the_title();
						$ex = get_the_excerpt();
						$link = get_permalink($post->ID);
						echo '<div class="col-lg-4 col-md-6 spacer">';
						echo '<div class="post-box">';
						echo '<div class="post-img" style="background-image: url(' . $img . ');"></div>';
						echo '<div class="post-body">';
						echo '<h4>' . $title . '</h4>';
						echo '<p>' . $ex . '</p>';
						echo '<a class="c-btn" href="' . $link . '">Read More</a>';
						echo '</div>';
						echo '</div>';
						echo '</div>';
					endwhile;
				endif;
				?>
			</div>
		</div>
	</section>
</div>
<?php
get_footer();
