<?php
/*
 * Benefits Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$text = get_field('text');
$link = get_field('link');
?>
<section class="benefits-section" id="<?php echo $id; ?>">
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
        <?php
        if (have_rows('benefits_list')) {
            echo '<div class="row benefits-slider">';
            while (have_rows('benefits_list')) {
                the_row();
                $icon = get_sub_field('icon');
                $header = get_sub_field('the_title');
                $cont = get_sub_field('the_text');
                echo '<div class="col-lg-4 spacer">';
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
        <div class="row justify-content-center">
            <?php
            if (!empty($text)) {
                echo '<div class="col-12">';
                echo '<div class="line"></div>';
                echo '</div>';
                echo '<div class="col-lg-9">';
                echo '<div class="text-block">';
                echo wpautop($text);
                echo '</div>';
                echo '</div>';
                echo '<div class="col-lg-3">';
            } else {
                echo '<div class="col-12 move-center">';
            }
            if ($link) {
                $link_url = $link['url'];
                $link_title = $link['title'];
                $link_target = $link['target'] ? $link['target'] : '_self';
                echo '<div class="button-block">';
                echo '<a class="c-btn btn-blue" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
                echo '</div>';
            }
            echo '</div>';
            ?>
        </div>
    </div>
</section>