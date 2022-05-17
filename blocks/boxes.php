<?php
/*
 * Boxes Block Template.
 */
$id = get_field('section_id');
?>
<section class="boxes-section" id="<?php echo $id; ?>">
    <div class="container">
        <div class="row">
            <?php
            if (have_rows('solution_list')) {
                $i = 1;
                while (have_rows('solution_list')) {
                    the_row();
                    $img = get_sub_field('image');
                    $header = get_sub_field('the_title');
                    $tline = get_sub_field('the_tagline');
                    $cont = get_sub_field('the_text');
                    $link = get_sub_field('link');
                    echo '<div class="col-lg-4 spacer">';
                    echo '<div class="item-box box-' . $i . '">';
                    if (!empty($img)) {
                        echo '<div class="item-img" style="background-image:url(' . $img . ');"></div>';
                    }
                    echo '<div class="item-body">';
                    if (!empty($header)) {
                        echo '<h4>' . $header . '</h4>';
                    }
                    if (!empty($tline)) {
                        echo '<h6>' . $tline . '</h6>';
                    }
                    if (!empty($cont)) {
                        echo wpautop($cont);
                    }
                    if ($link) {
                        $link_url = $link['url'];
                        $link_title = $link['title'];
                        $link_target = $link['target'] ? $link['target'] : '_self';
                        echo '<a class="c-btn btn-black" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
                    }
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    $i++;
                }
            }
            ?>
        </div>
    </div>
</section>