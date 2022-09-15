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
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				add_query_arg( 'paged', $paged );
				
				if (have_posts()) : ?>
				<?php
					// Start the Loop
					while (have_posts()) : the_post();

						/*
						$img = wp_get_attachment_url(get_post_thumbnail_id() ?: 0) ?: '';
						$title = get_the_title();
						$ex = get_the_excerpt();
						$link = get_permalink() ?: '';
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
						*/

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


					endwhile;
				endif;

				$category = get_category( get_query_var( 'cat' ) );
				$cat_id = $category->cat_ID;
				?>
			</div>

			<div class="row">
				<div class="col-12">
					<div class="section-button">
						<button class="c-btn btn-blue" data-category="<?php echo $cat_id; ?>" id="loadNews">Load More</button>
					</div>
				</div>
			</div>


		</div>
	</section>
</div>
<?php
get_footer();
