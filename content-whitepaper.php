<div class="wrapper">
	<?php
	wp_enqueue_script('custom-nf-code');

	$ids = [];
	if(have_posts()) :
	while (have_posts()) : the_post();
		$date = (string)get_the_date('F d, Y');
		$cat = get_the_category();
		$author_id = (int)get_the_author_meta('ID');
		$id = get_the_ID();
		if ($id !== false) {
			$ids[] = $id;
		}
	?>
		<section class="single-hero-section" style="">
			<div class="container">

				<div class="row">
					<div class="col-12">
						
					</div>
				</div>

				<div class="row">

					<div class="col-lg-8">

						<div class="section-title">
							<span class="whitepaper-label">
                            <?php
                            if(get_post_type()=='whitepapers'){
                                echo __('Whitepaper', 'nextcloud');
                            }else if(get_post_type()=='case_studies') {
                                echo __('Case study', 'nextcloud');
                            } else if(get_post_type()=='data_sheets'){
                                echo __('Data sheet', 'nextcloud');
                            }
                            ?>
                            </span>
							<?php
							echo '<h1>' . get_the_title() . '</h1>';
							?>
						</div>

					</div>

					<div class="col-4">
						
					</div>
				</div>
			</div>
		</section>

		<section class="post-single-section">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-lg-8 col-md-12 whitepaper_desc_col">
						<div class="text-block">
						<?php

						if(get_the_excerpt() && !get_the_content() ) {
							echo get_the_excerpt();
						} else {
							//echo get_the_content();
							echo apply_filters( 'the_content', get_the_content() );
						}
						?>
						</div>
					</div>


					<div class="col-lg-4 col-md-12 download_whitepaper_col">
						
						<div class="download_whitepaper_content">
							<h3>
							<?php
								if(get_post_type()=='whitepapers'){
									echo __('Download whitepaper', 'nextcloud');
								}else if(get_post_type()=='case_studies') {
									echo __('Download case study', 'nextcloud');
								} else if(get_post_type()=='data_sheets'){
									echo __('Download data sheet', 'nextcloud');
								}
							?>
							</h3>


							<?php 
							echo '<div class="form-body download_whitepaper_form">';
								if(get_post_meta(get_the_ID(), 'custom_ninja_form', true)) {
									//if custom shortcode is in the custom field
									echo do_shortcode(get_post_meta(get_the_ID(), 'custom_ninja_form', true));
								} else {
									echo do_shortcode("[ninja_form id='4']");
								}
							echo '</div>';
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
							<h3> 
                            <?php
                                if(get_post_type()=='whitepapers'){
                                    echo __('Other whitepapers', 'nextcloud');
                                }else if(get_post_type()=='case_studies') {
                                    echo __('Other case studies', 'nextcloud');
                                } else if(get_post_type()=='data_sheets'){
                                    echo __('Other data sheets', 'nextcloud');
                                }
                            ?>
                           
                            </h3>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<div class="related-slider">
							<?php
   

							$my_wp_query = new WP_Query();
							/** @var WP_Post[] $onepost */
							$onepost = $my_wp_query->query(array(
								'post_type' => get_post_type(),
								'post__not_in' => $ids,
								'posts_per_page' => 3,
								'post_status' => 'publish',
								'orderby' => 'date',
								'order' => 'DSC',
								//'category__not_in'=> array(225, 226) //exclude Private category
							));
							foreach ($onepost as $onepostsingle) {
								$post_id = $onepostsingle->ID;
								//$img = wp_get_attachment_url(get_post_thumbnail_id($post_id) ?: 0) ?: '';
								$title = $onepostsingle->post_title;
								$ex = $onepostsingle->post_excerpt;
								$link = (string)get_permalink($post_id);
								$featured_image = get_the_post_thumbnail($post_id, 'large', array( 'class' => 'feat_img' ));

								echo '<div>';
								echo '<div class="post-box">';
								//echo '<div class="post-img" style="background-image: url(' . $img . ');"></div>';
								echo '<div class="post-img" style=""><a href="'.$link.'" title="'.$title.'">'.$featured_image.'</a></div>';
								echo '<div class="post-body">';
								echo '<h4><a href="'.$link.'" title="'.$title.'">' . $title . '</a></h4>';
								echo '<p>' . $ex . '</p>';
								echo '<a class="c-btn" href="' . $link . '">'.__('Download', 'nextcloud').'</a>';
								echo '</div>';
								echo '</div>';
								echo '</div>';
							}
							wp_reset_query();
							?>
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

		<?php
		endwhile; // End of the loop. 
		endif; 
		?>

</div>