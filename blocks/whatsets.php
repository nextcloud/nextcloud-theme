<?php
/*
 * What Sets Apart Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$text = get_field('text');
$img = get_field('image');
?>
<section class="whatsets-section" id="<?php echo $id; ?>">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="text-block">
                    <?php
                    if (!empty($title)) {
                        echo '<h2>' . $title . '</h2>';
                    }
                    if (!empty($text)) {
                        echo $text;
                    }
                    ?>
                </div>
            </div>
            <div class="col-lg-6">
                <?php
                if (!empty($img)) {
                    echo '<div class="image-block">';
                    echo '<img src="' . $img . '" alt=""/>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</section>