<?php
/*
 * Blog Listing Block Template.
 */
$title = get_field('title');
?>
<section class="blog-section">
	<div class="container">
		<?php
		echo '<div class="row justify-content-between align-items-center">';
		if (!empty($title)) {
			echo '<div class="col-lg-6">';
			echo '<div class="section-title">';
			echo '<h3>' . $title . '</h3>';
			echo '</div>';
			echo '</div>';
		}
		if (function_exists('wpes_search_form')) {
			echo '<div class="col-lg-4">';
			echo '<div class="form-holder">';
			wpes_search_form(array(
				'wpessid' => 1612
			));
			echo '</div>';
			echo '</div>';
		}
		echo '</div>';
		?>
		<div class="row row-list-blog">
			<?php

			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			// The Query
			$args = array(
				'post_type' => 'post',
				//'posts_per_page' => -1,
				'post_status' => 'publish',
				'orderby' => 'date',
				'order' => 'DESC',
				'paged=' . $paged
			);
			
			$the_query = new WP_Query( $args );
			
			// The Loop
			if ( $the_query->have_posts() ) {
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					
					get_template_part('inc/blog_loop_single');

				}
			} else {
				// no posts found
			}
			/* Restore original Post Data */
			wp_reset_postdata();



			/*
			$my_wp_query = new WP_Query();
			$onepost = $my_wp_query->query(array(
				'post_type' => 'post',
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'orderby' => 'date',
				'order' => 'DSC',
			));

			foreach ($onepost as $onepostsingle) {
				$post_id = $onepostsingle->ID;
				$img = wp_get_attachment_url(get_post_thumbnail_id($post_id) ?: 0) ?: '';
				$title = $onepostsingle->post_title;
				$post_excerpt = $onepostsingle->post_excerpt;
				$link = get_permalink($post_id) ?: '';
				$featured_image = get_the_post_thumbnail( $post_id, 'large', array( 'class' => 'feat_img' ) );

				echo '<div class="col-lg-4 col-md-6 spacer news-container news-item" style="display: none;">';
				echo '<div class="post-box">';
				//echo '<div class="post-img" style="background-image: url(' . $img . ');"></div>';
				echo '<div class="post-img" style=""><a href="'.$link.'" title="'.$title.'">'.$featured_image.'</a></div>';
				echo '<div class="post-body">';
				echo '<h4><a href="'.$link.'" title="'.$title.'">' . $title . '</a></h4>';
				echo '<p>' . $post_excerpt . '</p>';
				echo '<a class="c-btn" href="' . $link . '">Read More</a>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
			}
			wp_reset_query();
			*/

			?>
		</div>
		<div class="row">
			<div class="col-12">
				<div class="section-button">
					<button class="c-btn btn-blue" id="loadNews">Load More</button>
				</div>
			</div>
		</div>
	</div>
</section>
