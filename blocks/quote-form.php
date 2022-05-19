<?php
/*
 * Get Quote Form Block Template.
 */
$id = get_field('section_id');
?>
<section class="quote-section" id="<?php echo $id; ?>">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-6">
				<!--<div class="form-block">
					<div class="text-center">
						<h2>Secure your data</h2>
						<p>Your answers to the following questions will help us better understand your project and give a realistic quote.</p>
					</div>
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
								<input type="text" name="phone" maxlength="40" size="60" placeholder="Phone number" />
							</div>
							<div class="input-holder selection">
								<label>How did you learn about us?</label>
								<select id="foundnextcloud" name="foundnextcloud">
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
								<input type="text" name="users" maxlength="80" cols="40" placeholder="Estimated # of users over next 12 months" />
							</div>
							<div class="input-holder">
								<textarea name="SLA" maxlength="2000" cols="60" rows="8" placeholder="What kind of response time do you require?"></textarea>
							</div>
							<h2 class="text-center">Your needs</h2>
							<div class="radio-holder">
								<span class="label">Would you require assistance to set up the service or to design/review the architecture?</span>
								<input type="radio" name="need-setup-help" id="nsh1" value="unsure" checked/><label for="nsh1">Not sure</label>
								<input type="radio" name="need-setup-help" id="nsh2" value="yes" /><label for="nsh2">Yes</label>
								<input type="radio" name="need-setup-help" id="nsh3" value="no" /><label for="nsh3">No</label>
							</div>
							<div class="radio-holder">
								<span class="label">Would you need integrated, secure audio/video chat and web conferencing?</span>
								<input type="radio" name="webconferencing" id="wcr1" value="unsure" checked/><label for="wcr1">Not sure</label>
								<input type="radio" name="webconferencing" id="wcr2" value="yes" /><label for="wcr2">Yes</label>
								<input type="radio" name="webconferencing" id="wcr3" value="no" /><label for="wcr3">No</label>
							</div>
							<div class="input-holder selection">
								<label>Would you need online document collaboration?</label>
								<select name="collabora">
									<option value="unsure">Not sure</option>
									<option value="Collabora">Collabora Online</option>
									<option value="OnlyOffice">OnlyOffice</option>
									<option value="Hancom">Hancom Office</option>
									<option value="Microsoft">Microsoft Office Online Server</option>
									<option value="no">No</option>
								</select>
							</div>
							<div class="radio-holder">
								<span class="label">Would you need our Outlook integration?</span>
								<input type="radio" name="outlook" id="out1" value="unsure" checked/><label for="out1">Not sure</label>
								<input type="radio" name="outlook" id="out2" value="yes" /><label for="out2">Yes</label>
								<input type="radio" name="outlook" id="out3" value="no" /><label for="out3">No</label>
							</div>
							<div class="input-holder">
								<textarea name="comments" maxlength="2000" cols="80" rows="8" placeholder="Your message..."></textarea>
							</div>
							<div class="check-holder">
								<input type="checkbox" id="gdprcheck" name="gdprcheck" value="gdprchecked"><label for="gdprcheck">I agree with the Nextcloud privacy policy and understand my data will be processed so Nextcloud or its partners can reach out to me.</label>
							</div>
							<div class="submit-holder center">
								<input class="" type="submit" value="Submit inquiry">
							</div>
						</form>
					</div>
				</div>-->
				<?php echo do_shortcode('[contact-form-7 id="16243" title="Get a qoute"]');  ?>
			</div>
		</div>
	</div>
</section>