<?php
/*
 * The template for displaying single events.
*/
get_header();

while (have_posts()) : the_post();
?>
<div id="event-<?php echo get_the_ID(); ?>" class="wrapper wrapper_single_event <?php 
	if( has_term('exhibition', 'event_categories') 
	//&& !has_block('ninja-forms/form')
	)
	{ echo " exhibition ";  }
	?>">
	<?php
		if( has_term('webinars', 'event_categories') ){
			get_template_part('content-webinar');
		} else {
			get_template_part('content-exhibition');
		}
	?>
</div>
<?php
endwhile; // End of the loop. 

get_footer();