<?php
/*
 * Boxes Block Template.
 */
$id = get_field('section_id');
?>
<section class="providers-section" id="<?php echo $id; ?>">
    <div class="container">
        <div class="row">
            <?php
            if (have_rows('providers_list')) {
                while (have_rows('providers_list')) {
                    the_row();
                    $img = get_sub_field('icon');
                    $header = get_sub_field('the_title');
                    $cont = get_sub_field('the_text');
                    $link = get_sub_field('link');
                    echo '<div class="col-12 spacer">';
                    echo '<div class="item-box">';
                    echo '<div class="item-img">';
                    if (!empty($img)) {
                        echo '<img src="' . $img . '" alt=""/>';
                    }
                    echo '</div>';
                    echo '<div class="item-body">';
                    if (!empty($header)) {
                        echo '<h4>' . $header . '</h4>';
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
                }
            }
            ?>
        </div>
    </div>
</section>