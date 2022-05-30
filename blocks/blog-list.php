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
		<div class="row">
			<?php
			$my_wp_query = new WP_Query();
			/** @var WP_Post[] $onepost */
			$onepost = $my_wp_query->query(array(
				'post_type' => 'post',
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'orderby' => 'date',
				'order' => 'DSC',
			));
			foreach ($onepost as $onepostsingle) {
				$img = wp_get_attachment_url(get_post_thumbnail_id($onepostsingle->ID) ?: 0) ?: '';
				$title = $onepostsingle->post_title;
				$ex = $onepostsingle->post_excerpt;
				$link = get_permalink($onepostsingle->ID) ?: '';
				echo '<div class="col-lg-4 col-md-6 spacer news-container news-item" style="display: none;">';
				echo '<div class="post-box">';
				echo '<div class="post-img" style="background-image: url(' . $img . ');"></div>';
				echo '<div class="post-body">';
				echo '<h4>' . $title . '</h4>';
				echo '<p>' . $ex . '</p>';
				echo '<a class="c-btn" href="' . $link . '">Read More</a>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
			}
			wp_reset_query();
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
