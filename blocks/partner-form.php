<?php
/*
 * Partner Form Block Template.
 */
$id = get_field('section_id');
$text = get_field('text');
?>
<section class="partner-section" id="<?php echo $id; ?>">
    <div class="container">
        <?php
		if (!empty($text)) {
			echo '<div class="row justify-content-center">';
			echo '<div class="col-lg-8">';
			echo '<div class="section-title">';
			echo wpautop($text);
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
		?>
        <div class="row justify-content-center">
            <div class="col-lg-6">
				<?php echo do_shortcode('[contact-form-7 id="16229" title="Partner program"]');  ?>
                <!--<div class="form-block">
                    <div class="form-holder">
                        <form name="contact" method="post" action="">
                            <div class="input-holder">
                                <input type="text" name="yourname" maxlength="60" size="60" placeholder="Your name *" />
                            </div>
                            <div class="input-holder">
                                <input type="email" name="email" maxlength="80" size="60" placeholder="Corporate email *" />
                            </div>
                            <div class="input-holder">
                                <input type="text" name="organization" maxlength="100" size="60" placeholder="Name of your organization" />
                            </div>
                            <div class="input-holder">
                                <input type="text" name="employees" maxlength="100" size="60" placeholder="How many employees do you have" />
                            </div>
                            <div class="input-holder">
                                <input type="text" name="role" maxlength="100" size="60" placeholder="Your job title" />
                            </div>
                            <div class="input-holder">
                                <input type="text" name="phone" maxlength="40" size="60" placeholder="Phone number" />
                            </div>
                            <div class="input-holder selection">
                                <label>Which option best describes your business model?</label>
                                <select name="businessmodel">
                                    <option value="software-development">Software developer - you want to integrate Nextcloud with your product.</option>
                                    <option value="reseller">Reseller - you want to set up and manage Nextcloud Enterprise instances for your customers.</option>
                                    <option value="hosting-provider">Hosting Provider - you want to host Nextcloud Enterprise instances for your customers.</option>
                                    <option value="hardware-manufacturing">Hardware Manufacturer - you want to sell hardware with Nextcloud Enterprise pre-installed.</option>
                                    <option value="distribution">Distribution - you want to resell Nextcloud Enterprise to your partners.</option>
                                </select>
                            </div>
                            <div class="input-holder selection">
                                <label>Who is your target customer?</label>
                                <select name="customers">
                                    <option value="b2b">Organizations and enterprises.</option>
                                    <option value="b2c">Private/home users.</option>
                                    <option value="both">Both.</option>
                                </select>
                            </div>
                            <div class="input-holder selection">
                                <label>Where do you primarily offer your services?</label>
                                <select name="geography">
                                    <option value="africa">Africa</option>
                                    <option value="Asia">Asia</option>
                                    <option value="Pacific">Australia and Pacific</option>
                                    <option value="South-Americas">Central and South Americas</option>
                                    <option value="East-Europe">Eastern Europe and Russia</option>
                                    <option value="Middle-East">Middle east</option>
                                    <option value="Central-Europe">Rest of Europe</option>
                                    <option value="USA-Canada">USA and Canada</option>
                                </select>
                            </div>
                            <div class="input-holder selection">
                                <label>How much experience do you have already with Nextcloud deployments?</label>
                                <select name="experience">
                                    <option value="None">None</option>
                                    <option value="Basic">We did one or two business or a few dozen private customer setups</option>
                                    <option value="Significant">We have dozens of businesses or hundreds of private users already</option>
                                </select>
                            </div>
                            <div class="input-holder">
                                <textarea name="comments" maxlength="2000" cols="80" rows="8" placeholder="What makes your offering special?"></textarea>
                            </div>
                            <div class="check-holder">
                                <input type="checkbox" id="gdprcheck" name="gdprcheck" value="gdprchecked"><label for="gdprcheck">I agree with the Nextcloud privacy policy and understand my data will be processed so Nextcloud or its partners can reach out to me.</label>
                            </div>
                            <div class="submit-holder center">
                                <input class="" type="submit" value="Submit inquiry">
                            </div>
                        </form>
                    </div>-->
                </div>
            </div>
        </div>
    </div>
</section>