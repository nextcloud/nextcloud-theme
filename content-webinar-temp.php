<?php
wp_enqueue_script('custom-nf-code');
$my_current_lang = apply_filters('wpml_current_language', null);

$post_date = (string)get_the_date('F d, Y');
$date_format = get_option('date_format'); // e.g. "F j, Y"

$post_id = get_the_ID();
$event_start_datetime = get_field('event_start_date_and_time', $post_id, false);
$date_start_format = date_i18n($date_format, strtotime($event_start_datetime));

$date_text = __('Event date', 'nextcloud');
$cat = get_the_terms(get_the_ID(), 'event_categories');

$custom_header_image = get_field('custom_header_image');

//date
$date_format = get_option('date_format'); // e.g. "F j, Y"
$diff_days = 0;
$event_start_datetime = get_field('event_start_date_and_time', $post_id, false);
if($event_start_datetime) {
	$date_start = date_create($event_start_datetime);
	$date_start_dayName = date_i18n("l", strtotime($event_start_datetime));
	$date_start_format = date_i18n($date_format, strtotime($event_start_datetime));

	$time_start_format = date_format($date_start, "g:i a");
	$start_datetime = strtotime($event_start_datetime);
	$start_datetime2 = new DateTime('@' . $start_datetime);
	$start_day = gmdate("d", $start_datetime);
	$start_month = gmdate("F", $start_datetime);
	$start_month_intl = formatLanguage($start_datetime2, 'F', $my_current_lang);
}
		

$event_end_datetime = get_field('event_end_date_and_time', $post_id, false);
if($event_end_datetime) {
	$date_end = date_create($event_end_datetime);
	$date_end_format = date_format($date_end, "F j, Y");
	$end_datetime = strtotime($event_end_datetime);
	$end_datetime2 = new DateTime('@' . $end_datetime);
	$end_day = gmdate("d", $end_datetime);
	$end_month = gmdate("F", $end_datetime);
	$end_month_intl = formatLanguage($end_datetime2, 'F', $my_current_lang);
	$diff_days = $date_end->diff($date_start)->format("%a");
}

?>
<section class="single-hero-section <?php if($custom_header_image) {
	echo "custom_header_image";
} ?>" style="<?php if($custom_header_image) {
	echo "background: url(".$custom_header_image.");";
} ?>">
	<div class="container">
				<div class="row">
					<div class="col-lg-8 col-md-12 title_col">
                
						<div class="section-title">
                            <span class="event_label">
                                <?php if(get_field('event_short_title')) {
                                	echo get_field('event_short_title');
                                } else {
                                	echo __('Exhibition', 'nextcloud');
                                }
?>
                            </span>

							<?php
							echo '<h1>' . get_the_title() . '</h1>';
?>
						</div>


                        <div class="row event_info">

                            <div class="col-lg-12">
                                <ul class="event_blocks">

                                    <li class="date">
                                        <span class="label date_label">
                                            <?php echo $date_text; ?>
                                        </span>
                                        <?php
			echo '<p>';
//echo $date_start_format;
if($diff_days > 0) {
	//multiple days
	if($my_current_lang == 'en') {
		if($start_month != $end_month) {
			//different months
			echo $start_month_intl." ".$start_day." - ".$end_month_intl." ".$end_day.", ".gmdate("Y", $start_datetime);
		} else {
			echo $start_month_intl." ".$start_day." - ".$end_day.", ".gmdate("Y", $start_datetime);
		}
	} else {
		//other languages
		if($start_month != $end_month) {
			echo $start_day." ".$start_month_intl." - ".$end_day." ".$end_month_intl.", ".gmdate("Y", $start_datetime);
		} else {
			echo $start_day." - ".$end_day." ".$start_month_intl.", ".gmdate("Y", $start_datetime);
		}
												
	}
} else {
	//single day
	echo $date_start_dayName." ".$date_start_format;
}
echo '</p>';
?>
                                    </li>

                                    <?php if(get_field('location', $post_id, false)) { ?>
                                    <li class="location">
                                        <i class="fas fa-map-marker-alt"></i>

                                        <span class="label location_label">
                                            <?php echo __('Location', 'nextcloud'); ?>
                                        </span>
                                        <p>
                                        <?php echo get_field('location', $post_id, false); ?>
                                        </p>
                                    </li>
                                    <?php } ?>

                                    <?php if(get_field('booth', $post_id, false)) { ?>
                                    <li class="booth">
                                        <i class="fas fa-users"></i>
                                        <span class="label booth_label">
                                            <?php echo __('Booth', 'nextcloud'); ?>
                                        </span>
                                        <p>
                                        <?php echo get_field('booth', $post_id, false); ?>
                                        </p>
                                    </li>
                                    <?php } ?>

                                </ul>
                                
                            </div>


                        </div>



					</div>

				</div>

	</div>
</section>

<section class="post-single-section">
	<div class="container">
				<div class="row justify-content-center">
					
                
                    <div class="col-lg-8 col-md-12 event_content_col">

						<div class="text-block">
						<?php
						echo do_shortcode(apply_filters('the_content', get_the_content()));
?>
						</div>

					</div>


                    <div class="col-lg-4 col-md-12 event_reg_col">
                        <div id="event_reg_content" class="event_reg_content">
                            <h3>
                                <?php echo __('Register to schedule a meeting with our team', 'nextcloud'); ?>
                            </h3>
                        
                            <?php
	echo '<div class="form-body event_reg_form">';
if(get_post_meta(get_the_ID(), 'custom_ninja_form', true)) {
	//if custom shortcode is in the custom field
	echo do_shortcode(get_post_meta(get_the_ID(), 'custom_ninja_form', true));
} else {
	echo do_shortcode("[ninja_form id='76']");
}
echo '</div>';
?>
                        </div>    
                    </div>


				</div>
	</div>
</section>
		


<?php if(get_field('event_speakers')):
	$numrows = count(get_field('event_speakers'));
	?>
<section id="speakers" class="vc_section nc_default_section speakers_section lightBG full-width">
    <div class="overlay-gradient"></div>
    <div class="container">
        <div class="vc_row wpb_row vc_row-fluid">
            <div class="wpb_column vc_column_container vc_col-sm-12" id="">
                    <div class="vc_column-inner">
                        <div class="wpb_wrapper">
	
                            <div class="wpb_text_column wpb_content_element">
                                <div class="wpb_wrapper">
                                    <h2 style="text-align: center;">Schedule your meeting with us!</h2>
                                </div>
                            </div>


                        <div class="case_studies avatars speakers event_speakers">
                            <div class="quotes_carousel owl-carousel owl-theme" data-items-desktop="<?php echo $numrows; ?>">
                  
                                <?php while(the_repeater_field('event_speakers')): ?>
                                    <div class="case_study">
                                        <div class="vc_column-inner">
                        
                                            <?php
											if(get_sub_field('image')) {
												$image = get_sub_field('image');
												if(!empty($image)):

													$size = 'thumbnail';
													$thumb = $image['sizes'][ $size ];
													?>
                                            <div class="wpb_single_image wpb_content_element vc_align_left image_top">
                                                <figure>
                                                <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />		
                                                </figure>
                                            </div>
                                            <?php
												endif;
											}
                                	?>

                                            <div class="organization <?php if(!get_sub_field('keynote_speaker')) {
                                            	echo " calendar ";
                                            }?>">
                                                <div class="wpb_wrapper">
                                                    <h4>
                                                        <?php if(get_sub_field('link')) { ?>
                                                            <a href="<?php the_sub_field('link'); ?>" target="_blank" title="<?php the_sub_field('name'); ?>">
                                                        <?php } ?>
                                                        <?php the_sub_field('name'); ?>
                                                        <?php if(get_sub_field('link')) { ?>
                                                            </a>
                                                        <?php } ?>
                                                    </h4>
                                                    <p class="position">
                                                        <?php the_sub_field('position'); ?>
                                                    </p>
                                                </div>
                                            </div>

                                            
                                
                                            <div class="wpb_text_column wpb_content_element quote">
                                                <?php the_sub_field('description'); ?>
                                            </div>

                                            <?php if(get_sub_field('keynote_speaker')) { ?>
                                                <span class="keynote_speaker">
                                                    <?php echo __('Keynote speaker at this event', 'nextcloud'); ?>
                                                </span>
                                            <?php } ?>
                                            
                                        </div>
                                    </div>
                                <?php endwhile; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>



<section class="social_media_sharing">
    <div class="share-block event"> 
        <a class="a2a_button_facebook" href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&t=<?php the_title(); ?>" title="Facebook" rel="nofollow noopener" target="_blank">
                                        <span class="a2a_svg a2a_s__default a2a_s_facebook" style="background-color: transparent;"><svg focusable="false" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path fill="#ffffff" d="M17.78 27.5V17.008h3.522l.527-4.09h-4.05v-2.61c0-1.182.33-1.99 2.023-1.99h2.166V4.66c-.375-.05-1.66-.16-3.155-.16-3.123 0-5.26 1.905-5.26 5.405v3.016h-3.53v4.09h3.53V27.5h4.223z"></path></svg></span><span class="a2a_label">Facebook</span>
                                    </a>
                            <a class="a2a_button_twitter" href="http://twitter.com/share?text=<?php the_title(); ?>&url=<?php the_permalink();?>" title="Twitter" rel="nofollow noopener" target="_blank"><span class="a2a_svg a2a_s__default a2a_s_twitter" style="background-color: transparent;"><svg focusable="false" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path fill="#ffffff" d="M28 8.557a9.913 9.913 0 01-2.828.775 4.93 4.93 0 002.166-2.725 9.738 9.738 0 01-3.13 1.194 4.92 4.92 0 00-3.593-1.55 4.924 4.924 0 00-4.794 6.049c-4.09-.21-7.72-2.17-10.15-5.15a4.942 4.942 0 00-.665 2.477c0 1.71.87 3.214 2.19 4.1a4.968 4.968 0 01-2.23-.616v.06c0 2.39 1.7 4.38 3.952 4.83-.414.115-.85.174-1.297.174-.318 0-.626-.03-.928-.086a4.935 4.935 0 004.6 3.42 9.893 9.893 0 01-6.114 2.107c-.398 0-.79-.023-1.175-.068a13.953 13.953 0 007.55 2.213c9.056 0 14.01-7.507 14.01-14.013 0-.213-.005-.426-.015-.637.96-.695 1.795-1.56 2.455-2.55z"></path></svg></span><span class="a2a_label">Twitter</span></a>
                            <a class="a2a_button_linkedin" href="https://www.linkedin.com/sharing/share-offsite/?url=<?php the_permalink();?>" title="LinkedIn" rel="nofollow noopener" target="_blank"><span class="a2a_svg a2a_s__default a2a_s_linkedin" style="background-color: transparent;"><svg focusable="false" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M6.227 12.61h4.19v13.48h-4.19V12.61zm2.095-6.7a2.43 2.43 0 010 4.86c-1.344 0-2.428-1.09-2.428-2.43s1.084-2.43 2.428-2.43m4.72 6.7h4.02v1.84h.058c.56-1.058 1.927-2.176 3.965-2.176 4.238 0 5.02 2.792 5.02 6.42v7.395h-4.183v-6.56c0-1.564-.03-3.574-2.178-3.574-2.18 0-2.514 1.7-2.514 3.46v6.668h-4.187V12.61z" fill="#ffffff"></path></svg></span><span class="a2a_label">LinkedIn</span></a>
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