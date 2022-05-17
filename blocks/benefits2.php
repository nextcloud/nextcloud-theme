<?php
/*
 * Benefits Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$img = get_field('image');
?>
<section class="benefits2-section" id="<?php echo $id; ?>">
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
            <div class="col-lg-5">
                <?php
                if (!empty($img)) {
                    echo '<div class="image-block">';
                    echo '<img src="' . $img . '" alt=""/>';
                    echo '</div>';
                }
                ?>
            </div>
            <div class="col-lg-7">
                <?php
                if (have_rows('benefits_list')) {
                    echo '<div class="row benefits-slider">';
                    while (have_rows('benefits_list')) {
                        the_row();
                        $icon = get_sub_field('icon');
                        $header = get_sub_field('the_title');
                        $cont = get_sub_field('the_text');
                        echo '<div class="col-lg-6 spacer">';
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