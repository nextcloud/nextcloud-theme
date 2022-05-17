<?php
/*
 * Integration Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
?>
<section class="integration-section" id="<?php echo $id; ?>">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <?php
                    if (!empty($title)) {
                        echo '<h3>' . $title . '</h3>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <?php
            if (have_rows('integration_list')) {
                $i = 1;
                while (have_rows('integration_list')) {
                    the_row();
                    $icon = get_sub_field('icon');
                    $cont = get_sub_field('the_text');
                    echo '<div class="col-lg-4 spacer">';
                    echo '<div class="item-box box-' . $i . '">';
                    if (!empty($icon)) {
                        echo '<img src="' . $icon . '" alt=""/>';
                    }
                    if (!empty($cont)) {
                        echo wpautop($cont);
                    }
                    echo '</div>';
                    echo '</div>';
                    $i++;
                    if ($i == 3) {
                        $i = 1;
                    }
                }
            }
            ?>
        </div>
    </div>
</section>