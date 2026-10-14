<?php 
$post_date = (string)get_the_date('F d, Y');
		$date_format = get_option( 'date_format' ); // e.g. "F j, Y"

		$post_id = get_the_ID();
		$event_start_datetime = get_field('event_start_date_and_time', $post_id, false);
		$date_start_format = date_i18n($date_format, strtotime($event_start_datetime));

		$date_text = __('Webinar date','nextcloud');
        $cat = get_the_terms( get_the_ID(), 'event_categories' );

		$author_id = (int)get_the_author_meta('ID');
		$custom_header_image = get_field('custom_header_image');

		$id = get_the_ID();
		if ($id !== false) {
			$ids[] = $id;
		}
?>


<section class="single-hero-section event <?php if($custom_header_image) echo "custom_header_image"; ?>" style="<?php if($custom_header_image) echo "background: url(".$custom_header_image.");"; ?>">
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
								<?php echo $date_text; ?>
							</span>

							<?php
							echo '<p>' . $date_start_format . '</p>';
							?>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="cat-block">

							<span class="cat_label">
							<?php echo __('Category','nextcloud'); ?>	
							</span>

							<?php
							if($cat) {
								echo '<ul>';
								foreach ($cat as $c) {
									echo '<li class="">';
									$category_link = get_category_link($c->term_id);
									echo '<a href="'.$category_link.'">' . $c->name . '</a> ';
									echo '</li>';
								}
								echo '</ul>';
							} else {
								echo '<ul>';
								echo '<li class="">' .__('Events','nextcloud'). ' </li>';
								echo '</ul>';
							}
							?>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="author-block">
							<span class="author_label">
								<?php echo __('Author','nextcloud'); ?>	
							</span>

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
						?>
						</div>
					</div>
				</div>
			</div>
		</section>
		



		<section class="related-posts-section">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="section-title">
							<h3><?php echo __('Other events', 'nextcloud');?></h3>
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