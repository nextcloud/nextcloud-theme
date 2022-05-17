<?php
/*
 * Products Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$video = get_field('video');
?>
<section class="products-section" id="<?php echo $id; ?>">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="section-title">
                    <?php
                    if (!empty($title)) {
                        echo '<h2>' . $title . '</h2>';
                    }
                    ?>
                </div>
                <?php
                if (have_rows('products')) {
                    $i = 1;
                    echo '<div class="products-accordion">';
                    while (have_rows('products')) {
                        the_row();
                        $icon = get_sub_field('icon');
                        $header = get_sub_field('the_title');
                        $text = get_sub_field('text');
                        $link = get_sub_field('link');
                        if ($i == 1) {
                            echo '<div class="accordion-card active">';
                        } else {
                            echo '<div class="accordion-card">';
                        }
                        echo '<button class="card-btn">';
                        if (!empty($icon)) {
                            echo '<span class="icon" style="background-image:url(' . $icon . ');"></span>';
                        }
                        if (!empty($header)) {
                            echo '<span class="name">' . $header . '</span>';
                        }
                        echo '</button>';
                        if ($i == 1) {
                            echo '<div class="card-box" style="display:block">';
                        } else {
                            echo '<div class="card-box">';
                        }
                        if (!empty($text)) {
                            echo wpautop($text);
                        }
                        if ($link) {
                            $link_url = $link['url'];
                            $link_title = $link['title'];
                            $link_target = $link['target'] ? $link['target'] : '_self';
                            echo '<a href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
                        }
                        echo '</div>';
                        echo '</div>';
                        $i++;
                    }
                    echo '</div>';
                }
                ?>
            </div>
            <?php
            if (!empty($video)) {
                echo '<div class="col-lg-5 offset-lg-1">';
                echo '<div class="button-block">';
                echo '<button class="video-btn" data-bs-toggle="modal" data-bs-target="#productvideoModal">PLAY NOW</button>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</section>
<?php
if (!empty($video)) {
    ?>
    <div class="modal video-modal fade" id="productvideoModal" tabindex="-1" aria-labelledby="productvideoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="video-holder">
                        <?php echo $video; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>