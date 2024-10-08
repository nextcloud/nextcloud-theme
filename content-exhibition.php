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
					
                
                    <div class="col-lg-2 col-md-12">
                    </div>



                    <div class="col-lg-8 col-md-12">
						<div class="text-block">

                        <?php //echo get_the_post_thumbnail( $post_id, 'full', array( 'class' => 'aligncenter feat_image' ) );?>


						<?php
						echo do_shortcode(apply_filters('the_content', get_the_content()));


if(get_post_meta(get_the_ID(), 'custom_ninja_form', true)) {
	//if custom shortcode is in the custom field
	$form = get_post_meta(get_the_ID(), 'custom_ninja_form', true);
} else {
	$form = "[ninja_form id='66']"; // use template-event-appointment-setting
}

echo "<div class='hidden'>";
echo do_shortcode('[nc_popup_form popup_id="event-reg-form-popup"]'.$form.'[/nc_popup_form]');
echo "</div>";
?>
						</div>
					</div>


                    <div class="col-lg-2 col-md-12">  
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
                                    <h2 style="text-align: center;"><?php echo get_field('speakers_section_title'); ?></h2>
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
                                                    <a class="" target="_blank" href="<?php the_sub_field('link'); ?>" title="<?php the_sub_field('name'); ?>">
                                                    <?php } ?>
                                                        <?php the_sub_field('name'); ?>
                                                    <?php if(get_sub_field('link')) { ?>
                                                    </a>
                                                    <?php } ?>

                                                    </h4>
                                                    <p class="position">
                                                        <?php the_sub_field('position'); ?>
                                                    </p>

                                                    <?php if(get_sub_field('keynote_speaker')) { ?>
                                                        <span class="keynote_speaker">
                                                            <?php echo __('Keynote speaker', 'nextcloud'); ?>
                                                        </span>
                                                    <?php } ?>

                                                </div>
                                            </div>

                                            
                                
                                            <div class="wpb_text_column wpb_content_element quote">
                                                <?php the_sub_field('description'); ?>
                                            </div>


                                            <?php if(get_sub_field('link')) { ?>
                                                <div class="open-popup-link textCenter">
                                                    
                                                    <a class="c-btn btn-main btn-small" href="#event-reg-form-popup" title="<?php echo __('Schedule a meeting', 'nextcloud'); ?>"><?php echo __('Schedule a meeting', 'nextcloud'); ?> <i class="vc_btn3-icon fas fa-angle-right"></i></a>
                                                
                                                    </div>
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

                                    <a class="a2a_button_x" href="http://x.com/share?text=<?php the_title(); ?>&url=<?php the_permalink();?>" title="X" rel="nofollow noopener" target="_blank"><span class="a2a_svg a2a_s__default a2a_s_x" style="background-color: transparent;"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 32 32" style="enable-background:new 0 0 32 32;" xml:space="preserve">
<path fill="#ffffff" class="st0" d="M18.3,14.1l9.1-10.3h-2.1l-7.9,8.9l-6.3-8.9H3.8l9.5,13.5L3.8,28.2H6l8.3-9.4l6.6,9.4h7.2 M6.8,5.4h3.3
	l15.2,21.2h-3.3"/>
</svg></span><span class="a2a_label">X</span></a>
                            
                            <a class="a2a_button_linkedin" href="https://www.linkedin.com/sharing/share-offsite/?url=<?php the_permalink();?>" title="LinkedIn" rel="nofollow noopener" target="_blank"><span class="a2a_svg a2a_s__default a2a_s_linkedin" style="background-color: transparent;"><svg focusable="false" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M6.227 12.61h4.19v13.48h-4.19V12.61zm2.095-6.7a2.43 2.43 0 010 4.86c-1.344 0-2.428-1.09-2.428-2.43s1.084-2.43 2.428-2.43m4.72 6.7h4.02v1.84h.058c.56-1.058 1.927-2.176 3.965-2.176 4.238 0 5.02 2.792 5.02 6.42v7.395h-4.183v-6.56c0-1.564-.03-3.574-2.178-3.574-2.18 0-2.514 1.7-2.514 3.46v6.668h-4.187V12.61z" fill="#ffffff"></path></svg></span><span class="a2a_label">LinkedIn</span></a>

                            <a class="a2a_button_mastodon" href="https://mastodonshare.com/?text=<?php the_title(); ?>&url=<?php the_permalink();?>" title="Mastodon" rel="nofollow noopener" target="_blank"><span class="a2a_svg a2a_s__default a2a_s_mastodon" style="background-color: transparent;">
                            
                            <svg focusable="false" aria-hidden="true" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 173.1 185.6" style="enable-background:new 0 0 173.1 185.6;" xml:space="preserve">
                            <path fill="#ffffff" class="st0" d="M155.2,67.5c0-31.9-20.9-41.3-20.9-41.3c-10.5-4.8-28.6-6.8-47.4-7h-0.5c-18.8,0.2-36.9,2.2-47.5,7.1
	c0,0-20.9,9.4-20.9,41.3c0,7.3-0.2,16,0.1,25.3c0.8,31.2,5.7,62,34.6,69.6c13.3,3.5,24.7,4.3,33.9,3.7c16.6-1,26.1-5.9,26.1-5.9
	l-0.6-12.1c0,0-11.9,3.7-25.3,3.3c-13.2-0.5-27.3-1.4-29.4-17.8c-0.2-1.4-0.3-2.9-0.3-4.6c0,0,13,3.2,29.6,4
	c10.1,0.5,19.6-0.6,29.2-1.7c18.5-2.2,34.5-13.6,36.5-23.9C155.5,91.1,155.2,67.5,155.2,67.5z M130.5,108.7h-15.3V71.2
	c0-7.9-3.3-11.9-10-11.9c-7.4,0-11,4.8-11,14.2V94H79V73.4c0-9.4-3.7-14.2-11-14.2c-6.7,0-10,4-10,11.9v37.5H42.6V70.1
	c0-7.9,2-14.2,6-18.8c4.2-4.7,9.6-7.1,16.4-7.1c7.8,0,13.8,3,17.7,9l3.8,6.4l3.8-6.4c3.9-6,9.8-9,17.7-9c6.7,0,12.2,2.4,16.4,7.1
	c4,4.7,6,10.9,6,18.8L130.5,108.7L130.5,108.7z"/>
                            </svg>
                            
                            </span><span class="a2a_label">Mastodon</span></a>

                         
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