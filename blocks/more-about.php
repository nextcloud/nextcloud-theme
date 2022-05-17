<?php
/*
 * More About Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
?>
<section class="more-about-section" id="<?php echo $id; ?>">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <?php
                    if (!empty($title)) {
                        echo '<h2>' . $title . '</h2>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="row">
            <?php
            if (have_rows('more_boxes')) {
                $i = 1;
                while (have_rows('more_boxes')) {
                    the_row();
                    $the_title = get_sub_field('box_title');
                    $the_text = get_sub_field('box_text');
                    $the_img = get_sub_field('box_image');
                    $the_video = get_sub_field('box_video');
                    echo '<div class="col-lg-6">';
                    echo '<div class="box">';
                    echo '<div class="box-img" style="background-image:url(' . $the_img . ');"></div>';
                    echo '<div class="box-body">';
                    if (!empty($the_video)) {
                        echo '<button class="v-btn" data-bs-toggle="modal" data-bs-target="#videoModal' . $i . '"></button>';
                        $i++;
                    }
                    if (!empty($the_title)) {
                        echo '<h5>' . $the_title . '</h5>';
                    }
                    if (!empty($the_text)) {
                        echo wpautop($the_text);
                    }
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>
</section>
<?php
if (have_rows('more_boxes')) {
    $x = 1;
    while (have_rows('more_boxes')) {
        the_row();
        $the_video = get_sub_field('box_video');
        if (!empty($the_video)) {
            ?>
            <div class="modal video-modal fade" id="videoModal<?php echo $x; ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="video-holder">
                                <?php echo $the_video; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $x++;
        }
    }
}
?>