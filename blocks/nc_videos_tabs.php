<?php
/*
 * NC Videos tab view Content Block Template.
 */
$id = get_field('section_id');
$css = get_field('custom_css_classes');

if (isset($block['data']['preview_image_help'])) :    /* rendering in inserter preview  */
	echo '<img src="' . $block['data']['preview_image_help'] . '" style="width:100%; height:auto;">';
else : /* rendering in editor body */
	?>
<section class="nc_videos_tabs_section <?php echo $css; ?>" id="<?php echo $id; ?>">
	<div class="">
		<div class="row swap-on-tablet">
			<div class="col-lg-6">
				<div class="nc_videos_tabs">

                    <div class="vc_tta-container" data-vc-action="collapseAll">
                                        <div class="vc_general vc_tta vc_tta-accordion vc_tta-color-white vc_tta-style-modern vc_tta-shape-rounded vc_tta-o-shape-group vc_tta-gap-10 vc_tta-controls-align-default vc_tta-o-all-clickable nextcloud-hub-accordion">

                                            <div class="vc_tta-panels-container">
                                                <div class="vc_tta-panels">

					<?php
								if (have_rows('videos_list')) {
									$i = 1;
									while (have_rows('videos_list')) {
										the_row();

										$id = get_sub_field('id');
										$icon = get_sub_field('icon');
										//$title = str_replace('<br>', '\n', get_sub_field( 'title' ));
										$title = str_replace('\n', '<br>', get_sub_field('title'));
										$description = get_sub_field('description');
										$video_url = get_sub_field('video_url');
										$video_preview_image = get_sub_field('video_preview_image');
										$link = get_sub_field('link');

										$custom_css = '';
										?>


                                        <div class="vc_tta-panel product_tab release_video_tab <?php
											if ($i == 1) {
												echo ' vc_active ';
											}
										echo $id; ?>"
                                                        id="<?php echo $id; ?>"
                                                        data-vc-content=".vc_tta-panel-body"
                                                        style="">

                                                        <div class="vc_tta-panel-heading">
                                                            <h4 class="custom-accordion-heading vc_tta-panel-title vc_tta-controls-icon-position-right product_tab release_video_tab <?php echo $id; ?>"
                                                                role="tab"
                                                                id="custom-heading-spotlight">

                                                                <button class="tab_link_button"
                                                                        id="<?php echo $id; ?>"
                                                                        href="#<?php echo $id; ?>"
                                                                        aria-expanded=""
                                                                        data-vc-accordion=""
                                                                        data-vc-container=".vc_tta-container"
                                                                        aria-controls="<?php echo $id; ?>-body">

                                                                    <span class="vc_tta-icon vc_tta-icon--svg" aria-hidden="true">
                                                                    </span>

                                                                    <div class="vc_tta-title-text">
                                                                        <?php echo $title; ?>
                                                                    </div>

                                                                    <i class="vc_tta-controls-icon vc_tta-controls-icon-chevron"></i>
                                                                </button>
                                                            </h4>
                                                        </div>

                                                        <div class="vc_tta-panel-body"
                                                            id="<?php echo $id; ?>-body"
                                                            role="region"
                                                            aria-labelledby="">

                                                            <div class="wpb_text_column wpb_content_element">
                                                                <div class="wpb_wrapper">
                                                                    <p>
                                                                        <?php echo $description; ?>
                                                                    </p>
                                                                </div>
                                                            </div>


                                                            <?php if (isset($link)) {?>
                                                            <div class="btn-main btn-small popup-video">
                                                                <a class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-rounded vc_btn3-style-modern vc_btn3-icon-left vc_btn3-color-grey vc_btn3--has-svg"
                                                                href="<?php echo $link['url']; ?>"
                                                                title="<?php echo $link['title']; ?>"
                                                                aria-haspopup="true">

                                                                    <span class="vc_btn3-icon vc_btn3-icon--svg" aria-hidden="true">
                                                                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" id="Layer_1" version="1.1" viewBox="0 0 24 24">
                                                                        <path class="st0" d="M9.5,16.5l7-4.5-7-4.5v9ZM12,22c-1.383,0-2.683-.263-3.9-.788s-2.275-1.238-3.175-2.138-1.613-1.958-2.138-3.175-.788-2.517-.788-3.9.263-2.683.788-3.9,1.238-2.275,2.138-3.175,1.958-1.613,3.175-2.138,2.517-.788,3.9-.788,2.683.263,3.9.788,2.275,1.238,3.175,2.138,1.613,1.958,2.138,3.175.788,2.517.788,3.9-.263,2.683-.788,3.9-1.238,2.275-2.138,3.175-1.958,1.613-3.175,2.138-2.517.788-3.9.788ZM12,20c2.233,0,4.125-.775,5.675-2.325s2.325-3.442,2.325-5.675-.775-4.125-2.325-5.675-3.442-2.325-5.675-2.325-4.125.775-5.675,2.325-2.325,3.442-2.325,5.675.775,4.125,2.325,5.675,3.442,2.325,5.675,2.325Z"></path>
                                                                        </svg>
                                                                    </span>
                                                                    <?php echo $link['title']; ?>
                                                                </a>
                                                            </div>
                                                            <?php } ?>


                                                        </div>
                                                    </div>

                                    <?php
									$i++;
									}
								}
	?> 
                    </div>
                                            </div>
                                        </div>
                                    </div>

				</div>
			</div>

            <div class="col-lg-6 nextcloud_release_overview">
                <div class="vc_column-inner">
                <?php
				if (have_rows('videos_list')) {
					while (have_rows('videos_list')) {
						the_row();

						$id = get_sub_field('id');
						$icon = get_sub_field('icon');
						$title = str_replace('\n', '', get_sub_field('title'));
						$description = get_sub_field('description');
						$video_url = get_sub_field('video_url');
						$video_preview_image = get_sub_field('video_preview_image');
						$link = get_sub_field('link');
						$custom_css = '';

						echo '<div id="' . $id . '-preview" class="nc_video_preview_box popup-video release_video">';
						?>
                        
                        <a href="<?php if (!empty($video_url)) {
                        	echo $video_url;
                        }?>" title="<?php echo $title; ?>" class="">
                            <div class="overlay"></div>
                            <?php
                        	if (!empty($video_preview_image)) {
                        		echo '<img src="' . $video_preview_image['url'] . '" alt="Nextcloud - ' . $title . '"/>';
                        	}
						?>

                            <div class="video_text">
                                <div class="icon">
                                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" id="" version="1.1" viewBox="0 0 24 24">
                                    <path class="st0" d="M9.5,16.5l7-4.5-7-4.5v9ZM12,22c-1.383,0-2.683-.263-3.9-.788s-2.275-1.238-3.175-2.138-1.613-1.958-2.138-3.175-.788-2.517-.788-3.9.263-2.683.788-3.9,1.238-2.275,2.138-3.175,1.958-1.613,3.175-2.138,2.517-.788,3.9-.788,2.683.263,3.9.788,2.275,1.238,3.175,2.138,1.613,1.958,2.138,3.175.788,2.517.788,3.9-.263,2.683-.788,3.9-1.238,2.275-2.138,3.175-1.958,1.613-3.175,2.138-2.517.788-3.9.788ZM12,20c2.233,0,4.125-.775,5.675-2.325s2.325-3.442,2.325-5.675-.775-4.125-2.325-5.675-3.442-2.325-5.675-2.325-4.125.775-5.675,2.325-2.325,3.442-2.325,5.675.775,4.125,2.325,5.675,3.442,2.325,5.675,2.325Z"></path>
                                    </svg>
                                </div>

                                <div class="video_desc">
                                    <div class="title"><?php echo $title; ?></div>
                                </div>
                            </div>
                        </a>

                    <?php
						echo '</div>'; // nc_video_preview_box
					}
				}
	?>
                </div>
            </div> <!-- .col --> 
		</div> <!-- .row --> 
	</div>
</section>
<?php endif; ?>