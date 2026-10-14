<?php
/*
 * Hub release changelog
 */
$id = get_field('section_id');
//$products = get_field('product'); // repeater
?>
<section class="hub-release-changelog" id="<?php echo $id; ?>">
	<div class="">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <?php 
                if( have_rows('product') ) {
                    while( have_rows('product') ) {
                        the_row();
                        $title = get_sub_field('title');
                        $product_image = get_sub_field('product_image');
                        $features = get_sub_field('features');
                        ?>
                        <div class="changelog-item">

                            <div class="changelog-item-title">
                                <?php if(isset($product_image)) { ?>
                                    <div class="item-image">
                                    <img class="" src="<?php echo $product_image; ?>" alt="">
                                    </div>
                                <?php } ?>
                                <?php if(isset($title)) echo '<div class="item-title">'.$title."</div>"; ?>
                            </div>
                            <?php if( have_rows('features') ){ 
                                $count = 0;
                                while(have_rows('features')) { 
                                    the_row();
                                    $count++;
                                }
                            ?>
                            <div class="changelog-item-features <?php if($count > 1) echo "truncated"; ?>">
                                <?php 
                                    echo "<ul class=''>";
                                    while(have_rows('features')) { 
                                        the_row();
                                        if(get_sub_field('feature') != '') {
                                            echo "<li>";
                                            echo get_sub_field('feature');
                                            echo "</li>";
                                        }
                                    }
                                    echo "</ul>";
                                ?>

                                <?php if($count > 1) { ?>
                                <a href="#" class="release-changelog-see-more-btn">
                                    <i class="fas fa-angle-down"></i>
                                </a>
                                <?php } ?>
                            </div>
                            <?php } ?>

                        </div>
                    <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</section>