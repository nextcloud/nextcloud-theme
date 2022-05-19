<?php
/*
 * Whitepaper Blog Posts Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$subtext = get_field('subtext');
?>
<section class="whitepaper-list-section">
    <div class="container">
        <?php
		echo '<div class="row">';
		echo '<div class="col-12">';
		echo '<div class="section-title">';
		if (!empty($title)) {
			echo '<h2>' . $title . '</h2>';
		}
		if (!empty($subtext)) {
			echo '<p>' . $subtext . '</p>';
		}
		echo '</div>';
		echo '</div>';
		echo '</div>';
		?>
        <div class="row">
            <?php
			$my_wp_query = new WP_Query();
			$onepost = $my_wp_query->query(array(
				'post_type' => 'post',
				'category_name' => 'whitepaper',
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'orderby' => 'date',
				'order' => 'DSC',
			));
			foreach ($onepost as $onepostsingle) {
				$img = wp_get_attachment_url(get_post_thumbnail_id($onepostsingle->ID));
				$title = $onepostsingle->post_title;
				$date = get_the_date('F d, Y', $onepostsingle->ID);
				$cat = get_the_category($onepostsingle->ID);
				$link = get_permalink($onepostsingle->ID);
				$author_id = $onepostsingle->post_author;
				echo '<div class="col-lg-4 col-md-6 spacer">';
				echo '<div class="paper-box">';
				echo '<ul class="cats">';
				echo '<li>posted in </li>';
				foreach ($cat as $c) {
					//    $category_link = get_category_link($c->term_id);
					echo '<li>' . $c->cat_name . ', </li>';
				}
				echo '<li>by ' . get_the_author_meta('display_name', $author_id) . '</li>';
				echo '</ul>';
				echo '<h4>' . $title . '</h4>';
				echo '<ul class="info">';
				echo '<li>' . $date . '</li>';
				echo '<li><a class="c-btn" href="' . $link . '">Read More</a></li>';
				echo '</ul>';
				echo '</div>';
				echo '</div>';
			}
			wp_reset_query();
			?>
        </div>
    </div>
</section>