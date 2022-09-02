<?php
$post_id = get_the_ID();
//$img = wp_get_attachment_url(get_post_thumbnail_id($post_id) ?: 0) ?: '';
$title = get_the_title();
$post_excerpt = get_the_excerpt();
$link = get_permalink();
$featured_image = get_the_post_thumbnail( $post_id, 'large', array( 'class' => 'feat_img' ) );

                    echo '<div class="col-lg-4 col-md-6 spacer news-container news-item" style="">';
					echo '<div class="post-box">';
					echo '<div class="post-img" style=""><a href="'.$link.'" title="'.$title.'">'.$featured_image.'</a></div>';
					echo '<div class="post-body">';
					echo '<h4><a href="'.$link.'" title="'.$title.'">' . $title . '</a></h4>';
					echo '<p>' . $post_excerpt . '</p>';
					echo '<a class="c-btn" href="' . $link . '">'.__('Read More','nextcloud').'</a>';
					echo '</div>';
					echo '</div>';
					echo '</div>';