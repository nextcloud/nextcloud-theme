<?php
    $id = get_the_ID();
    if ($id !== false) {
        $ids[] = $id;
    }
    if(get_post_type() == 'event') {
            $terms = wp_get_post_terms( get_the_ID(), 'event_categories', array('fields' => 'ids') );
    } else {
            $terms = wp_get_post_categories(get_the_ID(), array('fields' => 'ids'));
    }

							$tags = wp_get_post_terms( get_queried_object_id(), 'post_tag', ['fields' => 'ids'] );
							//print_r($tags);

							$related_ids = array();
							

							//tags
							$related_posts_args_by_tags = array(
								'post_type' => get_post_type(),
								'post_status' => 'publish',
								'post__not_in' => $ids,
								'tax_query' => array(
									'relation' => 'AND',
									array(
										'taxonomy' => 'post_tag',
										'field'    => 'term_id',
										'terms'    => $tags, // include current post tags
										'operator' => 'IN'
									),
									array(
										'taxonomy' => 'post_tag',
										'field'    => 'term_id',
										'terms'    => array(269), // exclude unlisted tag
										'operator' => 'NOT IN'
									)
								)
							);
							$related_posts_by_tags_query = new WP_Query();
							$posts_by_tags = $related_posts_by_tags_query->query($related_posts_args_by_tags);
							if($posts_by_tags) {

								foreach ($posts_by_tags as $onepostsingle) {
									$related_ids[] = $onepostsingle->ID;
								}

							}else {
								//echo "no posts with those tags conditions";
							}


							//categories
							$term_tax = 'category';
							if(get_post_type() == 'event') {
								$term_tax = 'event_categories';
							}

							$related_posts_args_by_terms = array(
								'post_type' => get_post_type(),
								'post_status' => 'publish',
                                'post__not_in' => $ids,
								'tax_query' => array(
									'relation' => 'AND',
									array(
										'taxonomy' => $term_tax,
										'field'    => 'term_id',
										'terms'    => $terms,
										'operator' => 'IN',
									),
									array(
										'taxonomy' => $term_tax,
										'field'    => 'term_id',
										'terms'    => array(225, 226), // exclude Private categories
										'operator' => 'NOT IN',
									),
									array(
										'taxonomy' => 'post_tag',
										'field'    => 'term_id',
										'terms'    => array(269), // exclude unlisted tag
										'operator' => 'NOT IN'
									)
								)
							);
							$related_posts_by_terms_query = new WP_Query();
							$posts_by_terms = $related_posts_by_terms_query->query($related_posts_args_by_terms);
							if($posts_by_terms) {
								foreach ($posts_by_terms as $onepostsingle) {
									$related_ids[] = $onepostsingle->ID;
								}
							}else {
								//echo "no posts with those term conditions";
							}

							//print_r($related_ids);



							$my_wp_query = new WP_Query();
							$related_args = array(
								'post_type' => get_post_type(),
								//'post__not_in' => $ids,
								'post__in' => $related_ids,
								'posts_per_page' => 3,
								'post_status' => 'publish',
								'orderby' => 'post__in',
								//'orderby' => 'date',
								//'order' => 'DESC',
								//'category__not_in'=> , //exclude Private category
								//'tag__not_in' => array(269), // exclud unlisted tag
								//'category__in' => wp_get_post_categories(get_the_ID()),
						
							);

							$onepost = $my_wp_query->query($related_args);
                            if($onepost) {
                                foreach ($onepost as $onepostsingle) {
                                    $post_id = $onepostsingle->ID;
                                    $title = $onepostsingle->post_title;
                                    $post_excerpt = get_the_excerpt($post_id);
                                    $link = (string)get_permalink($post_id);
                                    $featured_image = get_the_post_thumbnail($post_id, 'large', array( 'class' => 'feat_img' ));

									$date_format = get_option('date_format');
                                    $date = (string)get_the_date($date_format, $post_id);
    
                                    if ( 'event' == get_post_type() ) {
                                        $cat = get_terms( array(
                                            'taxonomy' => 'event_categories'
                                        ) );
                                    } else {
                                        $cat = get_the_category($post_id);
                                    }
    
                                    $cats = '';
                                    if($cat) {
                                        foreach ($cat as $c) {
                                            if($c->term_id != 243) { // exclude Uncategorized
                                                $category_link = get_category_link($c->term_id);
                                                $cats .= '<a href="'.$category_link.'">' . $c->name . ' </a>';
                                            }
                                        }
                                    }
    
                                    echo '<div class="col-lg-4 col-md-6 spacer news-container news-item" style="">';
                                    echo '<div class="post-box">';
                                    echo '<div class="post-img" style=""><a href="'.$link.'" title="'.$title.'">';
									
									if($featured_image) {
										echo $featured_image;
									} else {
										echo '<img src="'.get_stylesheet_directory_uri().'/dist/img/nextcloud-default-featured-image.jpg" class="feat_img wp-post-image" alt="Nextcloud Blog - '.$title.'" />';
									}

									echo '</a></div>';
                                    echo '<div class="post-body">';
                                    echo '<ul class="post-meta"><li class="date"><i class="far fa-calendar-alt"></i>'.$date.'</li>';
									
									if($cats) {
										echo '<li class="categories"><i class="far fa-folder-open"></i>'.$cats.'</li>';
									}
									
									echo '</ul>';
                                    echo '<h4><a href="'.$link.'" title="'.$title.'">' . $title . '</a></h4>';
                                    echo '<p>' . $post_excerpt . '</p>';
                                    echo '<a class="c-btn" href="' . $link . '">'.__('Read More', 'nextcloud').'<i class="fas fa-angle-right"></i></a>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
    
    
                                }
                                wp_reset_query();

                            }else {
                                //echo "no posts found!";
                            }

							
							
							?>