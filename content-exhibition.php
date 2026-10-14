<?php 
        wp_enqueue_script('custom-nf-code');
        $my_current_lang = apply_filters( 'wpml_current_language', NULL );

        $post_date = (string)get_the_date('F d, Y');
		$date_format = get_option( 'date_format' ); // e.g. "F j, Y"

		$post_id = get_the_ID();
		$event_start_datetime = get_field('event_start_date_and_time', $post_id, false);
		$date_start_format = date_i18n($date_format, strtotime($event_start_datetime));

		$date_text = __('Event date','nextcloud');
        $cat = get_the_terms( get_the_ID(), 'event_categories' );

		$custom_header_image = get_field('custom_header_image');

        //date
        $date_format = get_option( 'date_format' ); // e.g. "F j, Y"
        $diff_days = 0;
        $event_start_datetime = get_field('event_start_date_and_time', $post_id, false);
        if($event_start_datetime) {
            $date_start = date_create($event_start_datetime);
            $date_start_dayName = date_i18n("l", strtotime($event_start_datetime));
            $date_start_format = date_i18n($date_format, strtotime($event_start_datetime));

            $time_start_format = date_format($date_start,"g:i a");
            $start_datetime = strtotime($event_start_datetime);
            $start_datetime2 = new DateTime('@' . $start_datetime);
            $start_day = gmdate("d", $start_datetime);
            $start_month = gmdate("F", $start_datetime);
            $start_month_intl = formatLanguage($start_datetime2, 'F', $my_current_lang);
        }
        

        $event_end_datetime = get_field('event_end_date_and_time', $post_id, false);
        if($event_end_datetime){
            $date_end = date_create($event_end_datetime);
            $date_end_format = date_format($date_end,"F j, Y");
            $end_datetime = strtotime($event_end_datetime);
            $end_datetime2 = new DateTime('@' . $end_datetime);
            $end_day = gmdate("d", $end_datetime);
            $end_month = gmdate("F", $end_datetime);
            $end_month_intl = formatLanguage($end_datetime2, 'F', $my_current_lang);
            $diff_days = $date_end->diff($date_start)->format("%a");
        }

?>


<?php
//if is event - webinar  
if ( get_post_type() == 'event' ) {
	//is event
	$post_id = get_the_ID();
	$event_start_datetime = get_field('event_start_date_and_time', $post_id, false);
	$date_start_format_c = date_i18n("c", strtotime($event_start_datetime));

    if($event_end_datetime){ 
        $date_end_format_c = date_i18n("c", strtotime($event_end_datetime));
    } else {
        $date_end_format_c = $date_start_format_c;
    }
	//$id = get_the_ID();

	$location_field = get_field('location'); // Example: "Berlin, Germany"
	if ( $location_field ) {
		$parts = explode( ',', $location_field );

		$city = isset( $parts[0] ) ? trim( $parts[0] ) : '';
		$country_name = isset( $parts[1] ) ? trim( $parts[1] ) : '';

		// Simple map of country names → ISO codes
		$country_codes = array(
			'Germany' => 'DE',
			'France' => 'FR',
			'United States' => 'US',
			'Italy' => 'IT',
			'Spain' => 'ES',
			// Add more as needed
		);

		$country_code = isset( $country_codes[$country_name] ) ? $country_codes[$country_name] : $country_name; // fallback to name
	}
	?> 

<script type="application/ld+json">
  {
  "@context": "https://schema.org",
  "@type": "Event",
  "name": "<?php echo get_the_title(); ?>",
  "startDate": "<?php echo $date_start_format_c; ?>",
  "endDate": "<?php echo $date_end_format_c; ?>",
  "eventAttendanceMode": "https://schema.org/OnlineEventAttendanceMode",
  "eventStatus": "https://schema.org/EventScheduled",
		"location": {
			"@type": "Place",
			"name": "Zukunftskongress",
			"address": {
			"@type": "PostalAddress",
			"addressLocality": "<?php echo esc_html( $city ); ?>",
  			"addressCountry": "<?php echo esc_html( $country_code ); ?>"
			}
		},
  <?php if ( has_post_thumbnail() ) { ?> 
  "image": [
    "<?php echo get_the_post_thumbnail_url( get_the_ID(), 'full' ); ?>"
  ],
  <?php } ?>
  "description": "<?php echo get_the_excerpt(); ?>",
  "organizer": {
    "@type": "Organization",
    "name": "Nextcloud GmbH",
    "url": "https://nextcloud.com"
  },
  "offers": {
    "@type": "Offer",
    "url": "<?php echo get_the_permalink(); ?>",
    "price": "0",
    "priceCurrency": "EUR",
    "availability": "https://schema.org/InStock"
  }
}
</script>
<?php 
}
?>



<section class="single-hero-section <?php if($custom_header_image) echo "custom_header_image"; ?>" style="<?php if($custom_header_image) echo "background: url(".$custom_header_image.");"; ?>">
	<div class="container">
				<div class="row">
					<div class="col-lg-9 col-md-12 title_col">
                
						<div class="section-title">
                            <span class="event_label">
                                <?php if(get_field('event_short_title')) {
                                    echo get_field('event_short_title');
                                } else {
                                    echo __('Exhibition','nextcloud');
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

                                        <svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 24 24">
                                        <path fill="#ffffff" class="st0" d="M5,22c-.55,0-1.021-.196-1.413-.588-.392-.392-.588-.863-.588-1.413V6c0-.55.196-1.021.588-1.413.392-.392.863-.588,1.413-.588h1V2h2v2h8V2h2v2h1c.55,0,1.021.196,1.413.588s.588.863.588,1.413v14c0,.55-.196,1.021-.588,1.413s-.863.588-1.413.588H5ZM5,20h14v-10H5v10ZM5,8h14v-2H5v2ZM5,8v-2,2ZM12,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288c.192.192.288.429.288.713s-.096.521-.288.713c-.192.192-.429.288-.713.288ZM8,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713-.429.288-.713.288ZM16,14c-.283,0-.521-.096-.713-.288s-.288-.429-.288-.713.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713-.429.288-.713.288ZM12,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288c.192.192.288.429.288.713s-.096.521-.288.713c-.192.192-.429.288-.713.288ZM8,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713c-.192.192-.429.288-.713.288ZM16,18c-.283,0-.521-.096-.713-.288-.192-.192-.288-.429-.288-.713s.096-.521.288-.713.429-.288.713-.288.521.096.713.288.288.429.288.713-.096.521-.288.713c-.192.192-.429.288-.713.288Z"/>
                                        </svg>

                                        <span class="label date_label">
                                            <?php echo $date_text; ?>
                                        </span>
                                        <?php
                                        echo '<p>';
                                        //echo $date_start_format;
                                        if( $diff_days > 0 ) {
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
                                                }else {
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
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="#0082c9"><path fill="#ffffff" d="M480-480q33 0 56.5-23.5T560-560q0-33-23.5-56.5T480-640q-33 0-56.5 23.5T400-560q0 33 23.5 56.5T480-480Zm0 294q122-112 181-203.5T720-552q0-109-69.5-178.5T480-800q-101 0-170.5 69.5T240-552q0 71 59 162.5T480-186Zm0 106Q319-217 239.5-334.5T160-552q0-150 96.5-239T480-880q127 0 223.5 89T800-552q0 100-79.5 217.5T480-80Zm0-480Z"></path></svg>

                                        <span class="label location_label">
                                            <?php echo __('Location','nextcloud'); ?>
                                        </span>
                                        <p>
                                        <?php echo get_field('location', $post_id, false); ?>
                                        </p>
                                    </li>
                                    <?php } ?>

                                    <?php if(get_field('booth', $post_id, false)) { ?>
                                    <li class="booth">
                                        <!-- <i class="fas fa-users"></i> --> 
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="#ffffff"><path d="M200-246q54-53 125.5-83.5T480-360q83 0 154.5 30.5T760-246v-514H200v514Zm280-194q58 0 99-41t41-99q0-58-41-99t-99-41q-58 0-99 41t-41 99q0 58 41 99t99 41ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm69-80h422q-44-39-99.5-59.5T480-280q-56 0-112.5 20.5T269-200Zm211-320q-25 0-42.5-17.5T420-580q0-25 17.5-42.5T480-640q25 0 42.5 17.5T540-580q0 25-17.5 42.5T480-520Zm0 17Z"/></svg>

                                        <span class="label booth_label">
                                            <?php echo __('Booth','nextcloud'); ?>
                                        </span>
                                        <p>
                                        <?php echo get_field('booth', $post_id, false); ?>
                                        </p>
                                    </li>
                                    <?php } ?>


                                    <?php if(get_field('keynote_presentation', $post_id, false)) { ?>
                                    <li class="keynote">
                                        <svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 24 24">
                                        <path fill="#ffffff" class="st0" d="M12,14c-.8,0-1.5-.3-2.1-.9s-.9-1.3-.9-2.1v-6c0-.8.3-1.5.9-2.1s1.3-.9,2.1-.9,1.5.3,2.1.9.9,1.3.9,2.1v6c0,.8-.3,1.5-.9,2.1s-1.3.9-2.1.9ZM11,21v-3.1c-1.7-.2-3.2-1-4.3-2.3-1.1-1.3-1.7-2.9-1.7-4.6h2c0,1.4.5,2.6,1.5,3.5s2.2,1.5,3.5,1.5,2.6-.5,3.5-1.5,1.5-2.2,1.5-3.5h2c0,1.8-.6,3.3-1.7,4.6s-2.6,2.1-4.3,2.3v3.1h-2ZM12,12c.3,0,.5,0,.7-.3.2-.2.3-.4.3-.7v-6c0-.3,0-.5-.3-.7-.2-.2-.4-.3-.7-.3s-.5,0-.7.3-.3.4-.3.7v6c0,.3,0,.5.3.7s.4.3.7.3Z"/>
                                        </svg>
                                        <span class="label keynote_label">
                                            <?php echo __('Keynote','nextcloud'); ?>
                                        </span>
                                        <p>
                                        <?php 
                                        $keynote_presentation = get_field('keynote_presentation', $post_id, false);
                                        $keynote_presentation_date = date_create($keynote_presentation);
                                        $keynote_presentation_date_format = date_i18n("F j, Y", strtotime($keynote_presentation));
                                        $keynote_presentation_time = date_format($keynote_presentation_date,"g:i a");
                                        echo $keynote_presentation_date_format." @ ".$keynote_presentation_time;
                                        ?>
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

						<?php

						echo do_shortcode(apply_filters('the_content', get_the_content()));

                        if(get_post_meta(get_the_ID(), 'custom_ninja_form', true)) {
                            //if custom shortcode is in the custom field
                            $form = get_post_meta(get_the_ID(), 'custom_ninja_form', true);
                        } else {
                            $form = "[ninja_form id='66']"; // use template-event-appointment-setting
                        }

                        $custom_form_heading = __('Schedule a meeting with our team at the','nextcloud').' '.get_field('event_short_title');
                        if(get_field('custom_form_heading') != '') {
                            $custom_form_heading = get_field('custom_form_heading');
                        }

                   
                        if(!get_field('disable_popup_form')) {
                            echo "<div data-disable='' class='hidden'>";
                            echo do_shortcode('[nc_popup_form popup_id="event-reg-form-popup"]<h4 class="text-center">'.$custom_form_heading.'</h4>'.$form.'[/nc_popup_form]');
                            echo "</div>";
                        }
                        


						?>
						</div>
					</div>


                    <div class="col-lg-2 col-md-12">  
                    </div>


				</div>
	</div>
</section>
		


<?php if( get_field('event_speakers') ): 
    $numrows = count( get_field( 'event_speakers' ) );
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
                            <div class="quotes_carousel event_speakers_carousel owl-carousel owl-theme" data-items-desktop="<?php if($numrows < 4) {
                                echo $numrows;
                            } else {
                                echo "4";
                            }
                            ?>">
                  
                                <?php while( the_repeater_field('event_speakers') ): ?>
                                    <div class="case_study">
                                        <div class="vc_column-inner">
                        
                                            <?php 
                                            if(get_sub_field('image')) { 
                                                $image = get_sub_field('image');
                                                if( !empty( $image ) ): 

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
                                            } else {
                                                ?> 
                                                <div class="wpb_single_image wpb_content_element vc_align_left image_top">
                                                <figure>
                                                <img src="/c/themes/nextcloud-theme/dist/img/person.jpg" alt="" />		
                                                </figure>
                                                </div>
                                                <?php 
                                            }
                                            ?>

                                            <div class="organization <?php if(!get_sub_field('keynote_speaker')) { echo " calendar ";  }?> textCenter ">
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


                                  
                                            <div class="open-popup-link textCenter">
                                                    
                                                    <?php if(!get_field('disable_popup_form')) { ?>
                                                    <a class="c-btn btn-main btn-small" href="#event-reg-form-popup" title="<?php echo __('Schedule a meeting', 'nextcloud'); ?>"><?php echo __('Schedule a meeting', 'nextcloud'); ?> <i class="vc_btn3-icon fas fa-angle-right"></i></a>
                                                    <?php } ?>
                                                
                                            </div>
                                     


                                            
                                            
                                        </div>
                                    </div>
                                <?php endwhile; ?> 

                            </div>
                        </div>


                        <script>
                                    jQuery(document).ready(function ($) {
                                        var items_desktop = $('.event_speakers_carousel').data('items-desktop');

                                        $('.event_speakers_carousel').owlCarousel({
                                            loop:false,
                                            autoplay: false,
                                            margin:25,
                                            dots: true,
                                            nav:true,
                                            responsive:{
                                                0:{
                                                    items:1
                                                },
                                                800:{
                                                    items:2
                                                },
                                                1000:{
                                                    items: 3
                                                },
                                                1300:{
                                                    items: items_desktop
                                                }
                                            },
                                        });

                                    });
                        </script>


                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>





<?php 
if( !get_field('hide_sessions') && get_field('sessions') ): 
    //$numrows = count( get_field( 'event_speakers' ) );
    ?>
<section id="sessions" class="vc_section nc_default_section sessions_section full-width">
    <div class="overlay-gradient"></div>
    <div class="container">
        <div class="vc_row wpb_row vc_row-fluid">
            <div class="wpb_column vc_column_container vc_col-sm-12" id="">
                    <div class="vc_column-inner">
                        <div class="wpb_wrapper">
	
                            <div class="wpb_text_column wpb_content_element">
                                <div class="wpb_wrapper">
                                    <h2 style="text-align: center;">
                                    <i class="fas fa-comments"></i> 
                                    <?php echo __('Sessions', 'nextcloud');  ?></h2>
                                </div>
                            </div>

                            <div class="vc_separator wpb_content_element vc_separator_align_center vc_sep_width_20 vc_sep_border_width_2 vc_sep_pos_align_center vc_separator_no_text wpb_content_element  wpb_content_element nc_separator_line"><span class="vc_sep_holder vc_sep_holder_l"><span class="vc_sep_line"></span></span><span class="vc_sep_holder vc_sep_holder_r"><span class="vc_sep_line"></span></span>
                            </div>


                        
                        <div class="sessions">
                                <?php while( the_repeater_field('sessions') ): ?>
                                <div class="row event_session">

                                    <div class="col-sm-4" id="">
                                        <div class="vc_column-inner">
                                            <?php 
                                            if(get_sub_field('image')) { 
                                                $image = get_sub_field('image');
                                                if( !empty( $image ) ): 
                                                    //$size = 'full';
                                                    //$thumb = $image['sizes'][ $size ];
                                            ?>
                                            <div class="wpb_single_image wpb_content_element vc_align_left session_image">
                                                <figure>
                                                <img src="<?php echo esc_attr($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                                                </figure>
                                            </div>
                                            <?php 
                                                endif;
                                            }
                                            ?>
                                        </div>
                                    </div>
                                        
                                    <div class="col-sm-8" id="">
                                        <div class="vc_column-inner event_session_infos">

                                                <div class="wpb_wrapper">
                                                    <?php if(get_sub_field('session_type')) { ?>
                                                        <span class="session_type <?php echo get_sub_field('session_type'); ?>">
                                                            <?php echo get_sub_field('session_type') ?>
                                                        </span>
                                                    <?php } ?>

                                                    <h4>
                                                        <?php the_sub_field('session_name'); ?>
                                                    </h4>

                                                    <ul class="ul_session_infos">

                                                        <?php if(get_sub_field('speaker')) { ?>
                                                        <li class="session_speaker">
                                                        <i class="fas fa-microphone"></i>
                                                        <span class="label">Speaker: </span>
                                                        <?php the_sub_field('speaker'); ?>
                                                        </li>
                                                        <?php } ?>



                                                        <li class="date">
                                                        <i class="fas fa-calendar"></i>
                                                        <span class="label">Time: </span>
                                                        <?php 
                                                        $session_datetime = get_sub_field('session_date_and_time', false);                    
                                                        $keynote_presentation_date = date_create($session_datetime);
                                                        $keynote_presentation_date_format = date_i18n("F j, Y", strtotime($session_datetime));
                                                        $keynote_presentation_time = date_format($keynote_presentation_date,"g:i a");
                                                        echo $keynote_presentation_date_format." @ ".$keynote_presentation_time;
                                                        
                                                        ?>
                                                        </li>


                                                        <?php if(get_sub_field('session_room')) { ?>
                                                        <li class="room">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                        <span class="label">Room: </span>
                                                        <?php the_sub_field('session_room'); ?>
                                                        </li>
                                                        <?php } ?>

                                                    </ul>
          
                                                </div>


                                                <div class="wpb_text_column wpb_content_element session_desc">
                                                    <?php the_sub_field('session_description'); ?>
                                                </div>

                                                <?php if(get_sub_field('link')) { ?>
                                                        <a href="<?php the_sub_field('link'); ?>" target="_blank" class="learn_more">
                                                        <i class="fas fa-external-link-alt"></i>
                                                        <?php echo __('Learn more','nextcloud'); ?>
                                                        </a>
                                                <?php } ?>

                                            </div>
                                        </div>

                                    </div> <!-- .row event_session --> 
                                <?php endwhile; ?>
                            </div> <!-- .sessions --> 


                        </div>
                    </div>
                </div>
            </div> <!-- .vc_row -->
        </div> <!-- .container -->
</section>
<?php endif; ?>





<section class="social_media_sharing">
   <div class="share-block event">
	<a class="a2a_button_mastodon" href="https://mastodonshare.com/?text=<?php the_title(); ?>&url=<?php the_permalink();?>" title="Mastodon" rel="nofollow noopener" target="_blank"><span class="a2a_svg a2a_s__default a2a_s_mastodon" style="background-color: transparent;"><svg focusable="false" aria-hidden="true" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 173.1 185.6" style="enable-background:new 0 0 173.1 185.6;" xml:space="preserve">
                            <path fill="#ffffff" class="st0" d="M155.2,67.5c0-31.9-20.9-41.3-20.9-41.3c-10.5-4.8-28.6-6.8-47.4-7h-0.5c-18.8,0.2-36.9,2.2-47.5,7.1
							c0,0-20.9,9.4-20.9,41.3c0,7.3-0.2,16,0.1,25.3c0.8,31.2,5.7,62,34.6,69.6c13.3,3.5,24.7,4.3,33.9,3.7c16.6-1,26.1-5.9,26.1-5.9
							l-0.6-12.1c0,0-11.9,3.7-25.3,3.3c-13.2-0.5-27.3-1.4-29.4-17.8c-0.2-1.4-0.3-2.9-0.3-4.6c0,0,13,3.2,29.6,4
							c10.1,0.5,19.6-0.6,29.2-1.7c18.5-2.2,34.5-13.6,36.5-23.9C155.5,91.1,155.2,67.5,155.2,67.5z M130.5,108.7h-15.3V71.2
							c0-7.9-3.3-11.9-10-11.9c-7.4,0-11,4.8-11,14.2V94H79V73.4c0-9.4-3.7-14.2-11-14.2c-6.7,0-10,4-10,11.9v37.5H42.6V70.1
							c0-7.9,2-14.2,6-18.8c4.2-4.7,9.6-7.1,16.4-7.1c7.8,0,13.8,3,17.7,9l3.8,6.4l3.8-6.4c3.9-6,9.8-9,17.7-9c6.7,0,12.2,2.4,16.4,7.1
							c4,4.7,6,10.9,6,18.8L130.5,108.7L130.5,108.7z"></path>
                            </svg></span><span class="a2a_label">Mastodon</span></a>


	<a class="a2a_button_bs" href="<?php echo "https://bsky.app/intent/compose?text=".get_the_title()." ".get_the_permalink(); ?>" title="Bluesky" rel="nofollow noopener" target="_blank"><span class="a2a_svg a2a_s__default a2a_s_x" style="background-color: transparent;">
								<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 32 32">
							<path fill="#ffffff" class="st0" d="M29,3.1c-2.4-1.2-5.4.9-6.3,1.5-2.5,1.9-5.1,5.1-6.7,7.7-1.6-2.6-4.2-5.8-6.7-7.7h0c-.9-.7-3.9-2.7-6.3-1.5-.9.4-1.9,1.4-1.9,3.8,0,.8.5,7.1.8,8.3.5,1.9,1.8,3.4,3.5,4.2-.5.5-.9,1.1-1.1,1.8-.3,1-.5,3.1,1.8,5.4,2.1,2.1,4.1,3,6,2.6s3.1-2,4-3.5c.8,1.5,2.1,3.1,4,3.5.3.1.7.1,1,.1,1.6,0,3.3-.9,5.1-2.7,2.3-2.4,2.1-4.4,1.8-5.4-.2-.7-.6-1.3-1.1-1.8,1.7-.8,2.9-2.3,3.5-4.2.3-1.2.8-7.5.8-8.3-.3-2.4-1.3-3.4-2.2-3.8ZM27.2,14.4c-.9,3.3-5,2.8-5.9,2.7l-.5,3h0c2.6.4,3.8,1.4,4.1,2.1.3.9-.5,1.9-1.1,2.4-.9.9-2.2,2-3.2,1.7-.8-.2-1.9-1.1-3-4.5,0-.1-.1-.2-.1-.3v-.9l-1-.2c-.1,0-.3-.1-.5-.1s-.4,0-.5.1l-1,.2v.9c0,.1-.1.2-.1.3-1.2,3.4-2.3,4.3-3,4.5-1,.2-2.3-.8-3.2-1.7-.6-.6-1.4-1.6-1.1-2.4.2-.7,1.4-1.7,4.1-2.1h0l-.5-3h0c-.8.1-4.9.7-5.9-2.7-.2-.8-.7-6.7-.7-7.5s.2-1.1.3-1.2c.3-.1,1.7.2,3.1,1.3h0c2.8,2.1,6,6.7,7.1,9l1.4,2.8,1.4-2.8c1.1-2.3,4.3-6.8,7.1-9,1.4-1.1,2.8-1.4,3.1-1.2.1.1.3.4.3,1.2,0,.7-.5,6.6-.7,7.4Z"/>
							</svg>
	</span><span class="a2a_label">Bluesky</span></a>


	<a class="a2a_button_linkedin" href="https://www.linkedin.com/sharing/share-offsite/?url=<?php the_permalink();?>" title="LinkedIn" rel="nofollow noopener" target="_blank"><span class="a2a_svg a2a_s__default a2a_s_linkedin" style="background-color: transparent;">
		<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 32 32">
		<g fill="#fff" id="Layer_11" data-name="Layer_1" focusable="false">
			<path class="st0" d="M3.179,11.553h5.497v17.683H3.179V11.553ZM5.928,2.764c1.761,0,3.188,1.428,3.188,3.188s-1.428,3.188-3.188,3.188-3.185-1.43-3.185-3.188,1.422-3.188,3.185-3.188M12.12,11.553h5.274v2.414h.076c.735-1.388,2.528-2.854,5.201-2.854,5.56,0,6.586,3.663,6.586,8.422v9.701h-5.487v-8.606c0-2.051-.039-4.688-2.857-4.688-2.86,0-3.298,2.23-3.298,4.539v8.747h-5.493V11.553h0Z"/>
		</g>
		</svg>
	</span><span class="a2a_label">LinkedIn</span></a>


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
						<div class="related-slider owl-carousel">
							<?php get_template_part('inc/related_posts'); ?>
						</div>
					</div>
				</div>
			</div>
		</section>