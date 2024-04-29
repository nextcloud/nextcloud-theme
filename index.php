<?php
/*
 * The main template file
 */
get_header();
?>
<<<<<<< Updated upstream
<div class="wrapper">
=======
<div class="wrapper" id="main">
>>>>>>> Stashed changes
	<?php
	if (have_rows('page_navigation')) {
		echo '<ul class="page-nav">';
		while ((have_rows('page_navigation'))) {
			the_row();
			$name = get_sub_field('section_name');
			$anchor = get_sub_field('section_anchor');
			echo '<li><a href="' . $anchor . '">' . $name . '</a></li>';
		}
		echo '</ul>';
	}
	while (have_posts()) : the_post();
		echo do_shortcode(apply_filters('the_content', get_the_content()));
	endwhile; // End of the loop.
	?>
</div>
<?php
get_footer();
