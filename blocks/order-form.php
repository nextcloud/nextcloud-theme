<?php
/*
 * Order Form Block Template.
 */
$id = get_field('section_id');
?>
<section class="order-form-section" id="<?php echo $id; ?>">
	<div class="container">
		<!--
		<div class="form-holder">
			<form id="orderform" name="orderform" method="post" action="">
				<div class="row justify-content-between">
					<div class="col-lg-6">
						<div class="text-holder">
							<h4>Personal data</h4>
							<p>Fill in the form below to receive a contract and invoice from us to get started!</p>
						</div>
						<div class="input-holder">
							<input type="text" name="yourname" maxlength="60" size="60" placeholder="Your name *" onChange="doCalculation()" />
						</div>
						<div class="input-holder">
							<input type="email" name="email" maxlength="80" size="60" placeholder="Your email *" onChange="doCalculation()" />
						</div>
						<div class="input-holder">
							<input type="text" name="organization" maxlength="100" size="60" placeholder="Name of your organization *" onChange="doCalculation()" />
						</div>
						<div class="input-holder">
							<input type="text" name="website" maxlength="100" size="60" placeholder="Website" />
						</div>
						<div class="input-holder">
							<input type="text" name="phone" maxlength="40" size="60" placeholder="Phone number *" onChange="doCalculation()" />
						</div>
						<div class="input-holder">
							<input name="address" maxlength="1000" placeholder="Address *" onChange="doCalculation()" />
						</div>
						<div class="input-holder">
							<input name="billing" maxlength="1000" placeholder="Billing address (if different from abode address)" />
						</div>
						<div class="input-holder">
							<input type="text" name="vat" maxlength="60" size="60" placeholder="VAT ID (Europe only)">
						</div>
						<div class="text-holder">
							<h4>Your order</h4>
							<p>Fill in the form below to receive a contract and invoice from us to get started!</p>
						</div>
						<div class="input-holder selection">
							<label>Number of seats</label>
							<select name="users" onChange="setUsers()">
								<option value="100">100</option>
								<option value="150">150</option>
								<option value="200">200</option>
								<option value="250">250</option>
							</select>
						</div>
						<div class="input-holder selection">
							<label>Which Nextcloud Support Subscription are you interested in?</label>
							<select id="edition" name="edition" onChange="doCalculation()">
								<option default value="basic">Basic</option>
								<option value="standard">Standard</option>
							</select>
						</div>
						<div class="text-input">
							<p>Our Basic subscription offers email support with a 3 day response time, standard offers business hours phone support with a 2 day response time. <a href="#">See details on pricing</a> or <a href="#">ask a quote from our sales team</a> for the premium subscription.</p>
						</div>
						<div class="input-holder selection">
							<label>Length of contract (paid in advance)</label>
							<select name="duration" onChange="doCalculation()">
								<option default value="1">One year</option>
								<option value="2">2 years (2nd year 10% discount)</option>
								<option value="3">3 years (3rd year 15% discount)</option>
							</select>
						</div>
						<div class="input-holder selection">
							<label>Is your organization active in education, part of government, or charitable?</label>
							<select name="edugov" onChange="doCalculation()">
								<option default value="no">none</option>
								<option value="edu">Education</option>
								<option value="gov">Government</option>
								<option value="charity">Charitable</option>
							</select>
						</div>
					</div>
					<div class="col-lg-5">
						<div class="form-inner">
							<h2 class="price">Price: <span id="totalprice">€ 3570</span></h2>
							<h3><span id="peruserprice">€ 2.98</span> user/month</h3>
							<h6>Optional features:</h6>
							<div class="check-holder">
								<input disabled id="chk001" type="checkbox" name="outlook" value="outlook" onChange="doCalculation()"><label for="chk001">Include our Outlook add-in (€ 7.20/user)</label>
							</div>
							<div class="check-holder">
								<input disabled id="collaboraCheck" type="checkbox" name="collaboraCheck" value="collaboraCheck" onChange="doCalculation()"><label for="collaboraCheck">Include Collabora Online (€ 17/user for the first 100, € 16/user after that)</label>
							</div>
							<div class="check-holder">
								<input disabled id="onlyofficeCheck" type="checkbox" name="onlyofficeCheck" value="onlyofficeCheck" onChange="doCalculation()"><label for="onlyofficeCheck">Include ONLYOFFICE (€ 935 for the first 250 users)</label>
							</div>
							<div class="input-holder">
								<textarea name="comments" maxlength="2000" cols="60" rows="8" placeholder="Write a note here..."></textarea>
							</div>
							<div class="check-holder">
								<input id="chk002" type="checkbox" name="terms" value="terms" onChange="doCalculation()"><label for="chk002">I have read and agree to the terms and conditions </label>
							</div>
							<div class="submit-holder text-center">
								<input type="submit" name="submit" value=" Order Now " disabled="disabled" />
							</div>
							<span id="form-error">Some required fields are not filled.</span>
						</div>
					</div>
				</div>
				<input hidden type="checkbox" name="dollars" value="dollars" onChange="doCalculation()">
			</form>
		</div>
-->
		<?php
		echo do_shortcode('[contact-form-7 id="17099" title="Order Nextcloud" html_name="orderform" html_id="orderform"]');
?>
	</div>
</section>