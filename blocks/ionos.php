<?php
/*
 * Ionos Form Block Template.
 */
$id = get_field('section_id');
?>
<section class="ionos-form-section" id="<?php echo $id; ?>">
	<div class="container">
		<!--
		<div class="form-holder">
			<form id="orderform" name="orderform" method="post" action="">
				<div class="row justify-content-between">
					<div class="col-lg-6">
						<div class="text-holder">
							<h4>Personal data</h4>
							<p>Fill in the form below to receive an offer and contract from us and get started!</p>
						</div>
						<div class="input-holder">
							<input type="text" name="yourname" maxlength="60" size="60" placeholder="Your name *" onChange="doCalculation2()" />
						</div>
						<div class="input-holder">
							<input type="email" name="email" maxlength="80" size="60" placeholder="Your email *" onChange="doCalculation2()" />
						</div>
						<div class="input-holder">
							<input type="text" name="organization" maxlength="100" size="60" placeholder="Name of your organization *" onChange="doCalculation2()" />
						</div>
						<div class="input-holder">
							<input type="text" name="website" maxlength="100" size="60" placeholder="Website" />
						</div>
						<div class="input-holder">
							<input type="text" name="phone" maxlength="40" size="60" placeholder="Phone number *" onChange="doCalculation2()" />
						</div>
						<div class="input-holder">
							<input name="address" maxlength="1000" placeholder="Address *" onChange="doCalculation2()" />
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
						<div class="input-holder selection limited">
							<label>Number of users, at a price of 9.50 euro per user per month.</label>
							<select name="users" onChange="setUsers()">
								<?php
								//for ($i = 5; $i <= 200; $i++) {
?>
									<option value="<?php // echo $i;?>"><?php // echo $i;?></option>
								<?php
//}
?>
								<option value="201">more</option>
							</select>
						</div>
					</div>
					<div class="col-lg-5">
						<div class="form-inner">
							<h2 class="price">Price: <span id="totalprice">€ 47.50</span></h2>
							<h3>per month</h3>
							<h6>Optional features:</h6>
							<div class="check-holder">
								<input id="chk001" type="checkbox" name="outlook" value="outlook" onChange="doCalculation2()"><label for="chk001">Include our Outlook add-in (€ 7.20/user)</label>
							</div>
							<div class="input-holder">
								<textarea name="comments" maxlength="2000" cols="60" rows="8" placeholder="Write a note here..."></textarea>
							</div>
							<div class="check-holder">
								<input id="chk002" type="checkbox" name="terms" value="terms" onChange="doCalculation2()"><label for="chk002">I have read and agree to the terms and conditions </label>
							</div>
							<div class="submit-holder text-center">
								<input type="submit" name="submit" value=" Order Now " disabled="disabled" />
							</div>
							<span id="form-error">Some required fields are not filled.</span>
						</div>
						<div class="text-block">
							<h6>Notes:</h6>
							<ul class="bullet-list">
								<li>The subscription can be cancelled monthly.</li>
								<li>5GB storage per user is included. Additional storage is available with prices starting at Eur 2,00/month for 50GB.</li>
								<li>Multiple backup options available, depending on storage needs.</li>
								<li>Education and government pricing available with discounts up to 80%. Note this in the form and we will send you an offer.</li>
								<li>IONOS hosting is currently only available for customers in Europe.</li>
							</ul>
						</div>
					</div>
				</div>
				<input hidden type="checkbox" name="dollars" value="dollars" onChange="doCalculation()">
			</form>
		</div>
							-->
							<?php
							echo do_shortcode('[contact-form-7 id="16359" title="IONOS Form" html_name="orderform" html_id="orderform"]');
?>
	</div>
</section>