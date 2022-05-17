<?php
/*
 * Whitepaper Listing Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$subtext = get_field('subtext');
$studies = get_field('whitepaper_selection');
$case_form = get_field('whitepaper_form', 'options');
?>
<section class="whitepaper-section" id="<?php echo $id; ?>">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <?php
                    if (!empty($title)) {
                        echo '<h2>' . $title . '</h2>';
                    }
                    if (!empty($subtext)) {
                        echo wpautop($subtext);
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
        if ($studies) {
            echo '<div class="row">';
            echo '<div class="col-12">';
            echo '<div class="case-slider">';
            foreach ($studies as $study) {
                $img = wp_get_attachment_url(get_post_thumbnail_id($study->ID));
                $header = get_the_title($study->ID);
                $ex = get_the_excerpt($study->ID);
                $custom_field = get_field('field_name', $study->ID);
                echo '<div>';
                echo '<div class="post-holder">';
                echo '<div class="post-img" style="background-image:url(' . $img . ');"></div>';
                echo '<div class="post-body">';
                if (!empty($header)) {
                    echo '<h5 class="head">' . $header  . '</h5>';
                }
                if (!empty($ex)) {
                    echo '<p>' . $ex . '</p>';
                }
                echo '</div>';
                if (!empty($case_form)) {
                    echo '<div class="form-body">';
                    echo do_shortcode($case_form);
                    echo '</div>';
                }
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
</section>