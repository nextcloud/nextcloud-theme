<?php
/*
 * Ionos Form Block Template.
 */
$id = get_field('section_id');
?>
<section class="ionos-form-section" id="<?php echo $id; ?>">
	<div class="container">
		<div class="form-holder">
			<form id="orderform" name="orderform" method="post" action="">
				<div class="row justify-content-between">
					<div class="col-lg-6">
						<div class="text-holder">
							<h4>Personal data</h4>
							<p>Fill in the form below to receive an offer and contract from us and get started!</p>
						</div>
						<?php echo do_shortcode("[ninja_form id='7']"); ?>
					</div>
					<div class="col-lg-5">
						<div class="form-inner">
							<h2 class="price">Price: <span id="totalprice">€ 47.50</span></h2>
							<h3>per month</h3>
							<div class="submit-holder text-center">
								<input type="submit" name="submit" value=" Order Now " disabled="disabled" />
							</div>
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
	</div>
</section>