<?php
/*
 * FAQ Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
?>
<section class="faq-section" id="<?php echo $id; ?>">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="faq-block">
                    <?php
                    if (have_rows('faq')):
                        echo '<div class="text-block">';
                        if (!empty($title)) {
                            echo '<h2>' . $title . '</h2>';
                        }
                        echo '</div>';
                        echo '<div class="accord-flex">';
                        $count = count(get_field('faq'));
                        $half = ceil($count / 2);
                        $i = 0;
                        while (have_rows('faq')) : the_row();
                            $q = get_sub_field('question');
                            $a = get_sub_field('answer');
                            if ($i == 0) {
                                echo '<div class="accords" id="accordion">';
                            } else if ($i == $half) {
                                echo '</div>';
                                echo '<div class="accords accord2" id="accordion2">';
                            }
                            echo '<div class="card">';
                            echo '<div class="card-header" id="heading' . $i . '">';
                            echo '<button class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapse' . $i . '" aria-expanded="false" aria-controls="collapse' . $i . '">';
                            echo $q;
                            echo '</button>';
                            echo '</div>';
                            if ($i < $half) {
                                echo '<div id="collapse' . $i . '" class="collapse" aria-labelledby="heading' . $i . '" data-bs-parent="#accordion">';
                            } else {
                                echo '<div id="collapse' . $i . '" class="collapse" aria-labelledby="heading' . $i . '" data-bs-parent="#accordion2">';
                            }
                            echo '<div class="card-body">';
                            echo wpautop($a);
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            if ($i == $count - 1) {
                                echo '</div>';
                            }
                            $i++;
                        endwhile;
                        echo '</div>';
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>