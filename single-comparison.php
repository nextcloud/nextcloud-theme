<?php
/*
 * The template for displaying single comparison
*/
get_header();
?>
<div class="wrapper" id="main">
<?php
while (have_posts()) : the_post();
	echo do_shortcode(apply_filters('the_content', get_the_content()));
endwhile; // End of the loop.
?>
</div>
<?php
get_footer();