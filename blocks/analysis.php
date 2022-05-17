<?php
/*
 * Analysis Block Template.
 */
$id = get_field('section_id');
$toptext = get_field('top_text');
$title = get_field('title');
$text = get_field('text');
$form = get_field('form_shortcode');
$quote = get_field('quote');
$name = get_field('name');
$pos = get_field('position');
$link = get_field('link');
?>
<section class="analysis-section" id="<?php echo $id; ?>">
    <div class="container">
        <?php
        if (!empty($toptext)) {
            echo '<div class="row justify-content-center">';
            echo '<div class="col-lg-6">';
            echo '<div class="section-title">';
            echo '<p>' . $toptext . '</p>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        ?>
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="text-block">
                    <?php
                    if (!empty($title)) {
                        echo '<h2>' . $title . '</h2>';
                    }
                    if (!empty($text)) {
                        echo wpautop($text);
                    }
                    if (!empty($form)) {
                        echo '<div class="form-box">';
                        echo do_shortcode($form);
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="quote-circle">
                    <div class="quote-inner">
                        <?php
                        if (!empty($quote)) {
                            echo '<div class="quote-block">';
                            echo wpautop($quote);
                            echo '</div>';
                        }
                        echo '<div class="rest">';
                        if (!empty($name)) {
                            echo '<h6>' . $name . '</h6>';
                        }
                        if (!empty($pos)) {
                            echo '<span>' . $pos . '</span>';
                        }
                        if ($link) {
                            $link_url = $link['url'];
                            $link_title = $link['title'];
                            $link_target = $link['target'] ? $link['target'] : '_self';
                            echo '<a href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
                        }
                        echo '</div>';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>