<?php
/*
 * Page Hero Block Template.
 */
$title = get_field('title');
$text = get_field('text');
$link = get_field('link');
$bg = get_field('background');
if (!empty($bg)) {
    echo '<section class="page-hero-section" style="background-image: url(' . $bg . ');">';
} else {
    echo '<section class="page-hero-section gr">';
}
?>
<div class="container">
    <div class="row">
        <div class="col-lg-9">
            <div class="section-title">
                <?php
                if (!empty($title)) {
                    echo '<h1>' . $title . '</h1>';
                }
                if (!empty($text)) {
                    echo '<p>' . $text . '</p>';
                }
                if ($link) {
                    $link_url = $link['url'];
                    $link_title = $link['title'];
                    $link_target = $link['target'] ? $link['target'] : '_self';
                    echo '<a class="c-btn" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
</section>