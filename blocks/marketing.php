<?php
/*
 * Marketing Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$text = get_field('text');
?>
<section class="marketing-section gr" id="<?php echo $id; ?>">
	<div class="container">
		<div class="row align-items-center justify-content-between">
			<div class="col-lg-6">
				<div class="section-title">
					<?php
					if (!empty($title)) {
						echo '<h1>' . $title . '</h1>';
					}
					if (!empty($text)) {
						echo wpautop($text);
					}
					?>
				</div>
			</div>
			<div class="col-lg-5">
				<div class="form-block">
					<div class="text-block">
						<p>If you'd like to promote your app with a post on our blog or even look for ways to monetize an app together with us, contact us now!</p>
					</div>
					<!--
					<div class="form-holder">
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
								<textarea name="comments" maxlength="2000" cols="60" rows="8" placeholder="Your message..."></textarea>
							</div>
							<div class="submit-holder center">
								<input class="" type="submit" value="Submit">
							</div>
						</form>
					</div>
-->
					<?php echo do_shortcode('[contact-form-7 id="16379" title="App Marketing"]'); ?>
				</div>
			</div>
		</div>
	</div>
</section>