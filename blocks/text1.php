<?php
/*
 * Text Block Template.
 */
$id = get_field('section_id');
$bg = get_field('background');
$title = get_field('title');
$text = get_field('text');
?>
<section class="text1-section" id="<?php echo $id; ?>" style="background-image:url(<?php echo $bg; ?>);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="text-block">
                    <?php
                    if (!empty($title)) {
                        echo '<h3>' . $title . '</h3>';
                    }
                    if (!empty($text)) {
                        echo wpautop($text);
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>