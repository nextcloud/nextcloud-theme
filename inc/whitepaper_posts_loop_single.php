<?php
$post_id = get_the_ID();
				$img = wp_get_attachment_url(get_post_thumbnail_id($post_id) ?: 0) ?: '';
				$title = get_the_title();
				$date = (string)get_the_date('F d, Y', $post_id);
				$cat = get_the_category($post_id);
				$link = get_permalink($post_id) ?: '';
				$author_id = get_the_author_meta( 'ID' );
				$featured_image = get_the_post_thumbnail($post_id, 'large', array( 'class' => 'feat_img' ));

				echo '<div class="col-lg-4 col-md-6 spacer">';
				echo '<div class="post-box">';
				echo '<div class="paper-box">';
				echo '<h4><a title="'.$title.'" href="'.$link.'">' . $title . '</a></h4>';
				echo '<ul class="cats">';
				echo '<li>'.__('Posted in','nextcloud').' </li>';
				foreach ($cat as $c) {
					echo '<li>' . $c->cat_name . ', </li>';
				}
				echo '<li>'.__('by','nextcloud').' ' . get_the_author_meta('display_name', $author_id) . '</li>';
				echo '</ul>';
				echo '<ul class="info">';
				echo '<li>' . $date . '</li>';
				echo '<li><a class="c-btn" title="'.__('Read more', 'nextcloud').'" href="' . $link . '">'.__('Read more', 'nextcloud').'</a></li>';
				echo '</ul>';
				echo '</div>';
				echo '</div>';
				echo '</div>';