<?php
/*
 * Contact Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$text = get_field('text');
?>
<section class="contact-section gr" id="<?php echo $id; ?>">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="text-block">
                    <?php
					if (!empty($title)) {
						echo '<h1>' . $title . '</h1>';
					}
					if (!empty($text)) {
						echo wpautop($text);
					}
					if (have_rows('links')) {
						echo '<ul class="ext-links">';
						while (have_rows('links')) {
							the_row();
							$link = get_sub_field('link');
							if ($link) {
								$link_url = $link['url'];
								$link_title = $link['title'];
								$link_target = $link['target'] ? $link['target'] : '_self';
								echo '<li><a class="ext-link" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a></li>';
							}
						}
						echo '</ul>';
					}
					?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-block">
                    <h4>Contact form</h4>
                    <!-- <div class="form-holder">
                        <form name="contact" method="post" action="">
                            <div class="input-holder">
                                <input type="text" name="yourname" maxlength="60" size="60" placeholder="Your name *" />
                            </div>
                            <div class="input-holder">
                                <input type="email" name="email" maxlength="80" size="60" placeholder="Your email *" />
                            </div>
                            <div class="input-holder">
                                <input type="text" name="organization" maxlength="100" size="60" placeholder="Name of your organization" />
                            </div>
                            <div class="input-holder">
                                <input type="text" name="phone" maxlength="40" size="60" placeholder="Phone number" />
                            </div>
                            <div class="input-holder selection hide-first">
                                <select id="foundnextcloud" name="foundnextcloud">
                                    <option value="default">How did you learn about us?</option>
                                    <option value="empty">Rather not say</option>
                                    <option value="search">Search engine</option>
                                    <option value="news">In the news</option>
                                    <option value="recommendation">It was recommended to me</option>
                                    <option value="usemyself">I use it privately</option>
                                    <option value="advert">I saw it in an advertisement</option>
                                    <option value="other">other</option>
                                </select>
                            </div>
                            <div class="input-holder">
                                <textarea name="comments" maxlength="2000" cols="60" rows="8" placeholder="Your message..."></textarea>
                            </div>
                            <div class="check-holder center white-text">
                                <input type="checkbox" id="gdprcheck" name="gdprcheck" value="gdprchecked"><label for="gdprcheck">I agree to the <a href="#">Terms of Service</a></label>
                            </div>
                            <div class="submit-holder center">
                                <input class="" type="submit" value="Submit">
                            </div>
                        </form>
                    </div> -->
					<?php echo do_shortcode('[contact-form-7 id="15865" title="Contact new hip"]');  ?>
                    <h6 class="info-text">Support questions through this form will get ignored.</h6>
                </div>
            </div>
        </div>
    </div>
</section>