<?php
/*
 * Benefits Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$text = get_field('text');
$link = get_field('link');
?>
<section class="benefits3-section" id="<?php echo $id; ?>">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <?php
                    if (!empty($title)) {
                        echo '<h2>' . $title . '</h2>';
                    }
                    if (!empty($text)) {
                        echo wpautop($text);
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <?php
                if (have_rows('benefits_list')) {
                    echo '<div class="benefits2-slider">';
                    while (have_rows('benefits_list')) {
                        the_row();
                        $icon = get_sub_field('icon');
                        $header = get_sub_field('the_title');
                        $cont = get_sub_field('the_text');
                        echo '<div>';
                        echo '<div class="slide-box">';
                        if (!empty($icon)) {
                            echo '<img src="' . $icon . '" alt=""/>';
                        }
                        if (!empty($header)) {
                            echo '<h4>' . $header . '</h4>';
                        }
                        if (!empty($cont)) {
                            echo wpautop($cont);
                        }
                        echo '</div>';
                        echo '</div>';
                    }
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</section>