<?php
/*
 * Template Name: Lazy Changelog Loader (5 per batch)
 */
get_header();
?>
<div class="wrapper" id="main">
	<?php
    if ( have_posts() ) :
	while (have_posts()) : the_post();
    global $post;

    $sections   = my_extract_changelog_sections( $post->ID );
    $always     = $sections['always'];
    $lazy       = $sections['lazy'];
    $batch_size = 5;
    ?>
    <div id="changelog-container">

    <div class="hidden sections">
        <?php //print_r($always); ?>
    </div>
    <?php
                // --- ALWAYS-ON SECTIONS ---
                /*
                if ( ! empty( $always ) ) {
                    foreach ( $always as $section_html ) {
                        echo $section_html; // already rendered HTML
                    }
                }

                // --- FIRST LAZY BATCH (up to 5 sections) ---
                if ( ! empty( $lazy ) ) {
                    $initial_count = min( $batch_size, count( $lazy ) );
                    for ( $i = 0; $i < $initial_count; $i++ ) {
                        echo $lazy[ $i ];
                    }
                } else {
                    // Fallback: if no lazy sections at all, just show page content
                    the_content();
                }
                */  
                
                echo do_shortcode(apply_filters('the_content', get_the_content()));
    ?>
    </div>
    <?php if ( ! empty( $lazy ) && count( $lazy ) > $batch_size ) : ?>
                <div id="changelog-sentinel"
                    data-post-id="<?php echo esc_attr( $post->ID ); ?>"
                    data-next-index="<?php echo esc_attr( $batch_size ); ?>"></div>
    <?php endif; ?>

	<?php endwhile;
    endif; // End of the loop.
	?>
</div>
<?php
get_footer();