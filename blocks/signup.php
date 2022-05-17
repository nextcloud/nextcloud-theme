<?php
/*
 * Sign Up Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$text = get_field('text');
?>
<section class="signup-section gr">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-12">
                <div class="text-block">
                    <?php
                    if (!empty($title)) {
                        echo '<h1>' . $title . '</h1>';
                    }
                    if (!empty($text)) {
                        echo wpautop($text);
                    }
                    ?>
                    <div class="form-block">
                        <form>
                            <div class="form-holder">
                                <div class="input-holder">
                                    <input type="email" placeholder="Enter Your Email" />
                                </div>
                                <div class="submit-holder">
                                    <input type="submit" value="Sign Up" />
                                </div>
                                <div class="check-holder">
                                    <input id="chk001" type="checkbox" name="terms" value="terms"><label for="chk001">I agree to the Terms of Service </label>
                                    <input id="chk002" type="checkbox" name="subscribe" value="subed"><label for="chk002">Subscribe to our newsletter</label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="image-block">
                    <?php
                    echo '<img src="' . get_stylesheet_directory_uri() . '/dist/img/placeholder.png" alt="placeholder"/>';
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>