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
			echo '<h3>';

			if(isset($_GET['webinars'])) {

				if( strip_tags($_GET['webinars']) == 'upcoming') {
					echo __('Upcoming webinars','nextcloud');
				} else {
					echo __('Webinar recordings','nextcloud');
				}
				
			} else {
				echo $title;
			}
			
			echo '</h3>';
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
			$default_posts_per_page = get_option( 'posts_per_page' );

			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			// The Query
			$args = array(
				//'post_type' => array('post', 'event'),
				'posts_per_page' => $default_posts_per_page,
				'post_status' => 'publish',
				'orderby' => 'date',
				//'order' => 'DESC',
				'paged=' . $paged,
				'tag__not_in' => array(269),
				'category__not_in'=> array(225, 226) //exclude Private category
			);
			date_default_timezone_set('Europe/Berlin');
			$current_date_time = date('Y-m-d H:i:s', time());


			if( isset($_GET['webinars'])) {

				$args['post_type'] = array('event');
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'event_categories',
						'field'    => 'slug',
						'terms'    => 'webinars',
					)
				);

				if( strip_tags($_GET['webinars']) == 'upcoming'){
					
					$args['meta_query'] = array(
						array(
							'key' => 'event_start_date_and_time',
							'value'   => $current_date_time,
							'compare' => '>=',
							'type'	=> 'DATETIME'
						),
					);
					$args['order'] = 'DESC';

				} else if ( strip_tags($_GET['webinars']) == 'past') {

					$args['meta_query'] = array(
						'relation' => 'AND',
						
						array(
							'key' => 'event_start_date_and_time',
							'value'   => $current_date_time,
							'compare' => '<',
							'type'	=> 'DATETIME'
						),
						
			
						array(
							'key'     => 'download_available',
							'value'	  => '',
							'compare' => '!=',
						),
						
						
					);
					$args['order'] = 'ASC';

				}


			}
			else {
				$args['post_type'] = array('post', 'event');
				$args['order'] = 'DESC';
			}

			
			$the_query = new WP_Query($args);
			$count = $the_query->found_posts;

			// The Loop
			if ($the_query->have_posts()) {
				while ($the_query->have_posts()) {
					$the_query->the_post();
					
					get_template_part('inc/blog_loop_single');
				}
			} else {
				// no posts found
				?>

			<div class="col-12">
				<div class="section-button">
					<h3><?php echo __('No posts found.','nextcloud'); ?></h3>
				</div>
			</div>

			<?php
			}
			/* Restore original Post Data */
			wp_reset_postdata();

			?>
		</div>

		<?php if($count > $default_posts_per_page) { ?>
		<div class="row">
			<div class="col-12">
				<div class="section-button">
					<button class="c-btn btn-main" id="loadNews"><?php echo __('Load More','nextcloud'); ?></button>
				</div>
			</div>
		</div>
		<?php } ?>

	</div>
</section>
