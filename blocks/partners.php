<?php
/*
 * Partners Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$link = get_field('partner_link');
$subtext = get_field('subtext');
$cert = get_field('certificate_text');
$logos = get_field('certificate_logos');
$solution = get_field('solution_providers');
$technology = get_field('technology_partners');
?>
<section class="section-intro">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<div class="section-title">

					<?php
				if (!empty($title)) {
					echo '<h4>' . $title . '</h4>';
				}
				if ($link) {
					$link_url = $link['url'];
					$link_title = $link['title'];
					$link_target = $link['target'] ? $link['target'] : '_self';
					echo '<a class="c-btn btn-blue" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
				}
				?>
			</div>
			</div>
		</div>
	</div>
</section>
<section class="partners-section" id="<?php echo $id; ?>">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="tabs-navigation">
					<ul class="custom-partner-tabs">
						<li>
							<button class="tab-link active" id="solution-tab">Solution providers</button>
						</li>
						<li>
							<button class="tab-link" id="technology-tab">Technology Partners</button>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="desc-text">
					<?php
					if (!empty($subtext)) {
						echo wpautop($subtext);
					}
					?>
				</div>
				<div class="text-block">
					<?php
					if (!empty($cert)) {
						echo '<p>' . $cert . '</p>';
					}
					?>
				</div>
				<?php
				if ($logos) {
					echo '<ul class="logos">';

					foreach ($logos as $lg) {
						echo '<li><img src="' . $lg . '" alt=""/></li>';
					}
					echo '</ul>';
				}
				?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="filters-holder">
					<div class="input-outer">
						<div class="input-holder selection">
							<span class="label">Services</span>
							<div class="inner">
								<input id="services" type="text" value="All services" readonly="readonly" data-value="" placeholder="All services" />
							</div>
						</div>
						<ul class="select-list check-list">
							<li>
								<input type="checkbox" name="servi" value="all-dev" id="shk00" /><label for="shk00">All services</label>
							</li>
							<li>
								<input type="checkbox" name="servi" value="host-own" id="shk01" /><label for="shk01">Hosting in own data center</label>
							</li>
							<li>
								<input type="checkbox" name="servi" value="host-rend" id="shk02" /><label for="shk02">Hosting in rend data center</label>
							</li>
							<li>
								<input type="checkbox" name="servi" value="archi" id="shk03" /><label for="shk03">Architecture consulting</label>
							</li>
							<li>
								<input type="checkbox" name="servi" value="hardware" id="shk04" /><label for="shk04">Hardware development</label>
							</li>
							<li>
								<input type="checkbox" name="servi" value="app" id="shk05" /><label for="shk05">App development</label>
							</li>
							<li>
								<input type="checkbox" name="servi" value="custom" id="shk06" /><label for="shk06">Custom integrations</label>
							</li>
							<li>
								<input type="checkbox" name="servi" value="train" id="shk07" /><label for="shk07">Trainings</label>
							</li>
							<li>
								<input type="checkbox" name="servi" value="on-site" id="shk08" /><label for="shk08">On-site management</label>
							</li>
							<li>
								<input type="checkbox" name="servi" value="resell" id="shk09" /><label for="shk09">Reselling</label>
							</li>
						</ul>
					</div>
					<div class="input-outer">
						<div class="input-holder selection">
							<span class="label">Partner level</span>
							<div class="inner">
								<input id="certificates" type="text" value="All levels" readonly="readonly" data-value="all-cert" />
							</div>
						</div>
						<ul class="select-list cert-list">
							<li data-certificate="all-cert">All levels</li>
							<li data-certificate="platinum">Platinum</li>
							<li data-certificate="gold">Gold</li>
							<li data-certificate="silver">Silver</li>
							<li data-certificate="bronze">Bronze</li>
						</ul>
					</div>
					<div class="input-outer">
						<div class="input-holder selection">
							<span class="label">Show</span>
							<div class="inner">
								<input id="country" type="text" value="All" readonly="readonly" data-value="all-comp" placeholder="All" />
							</div>
						</div>
						<ul class="select-list check-list">
							<li>
								<input type="checkbox" name="country" value="all-comp" id="chk01" /><label for="chk01">All</label>
							</li>
							<li>
								<input type="checkbox" name="country" value="eu" id="chk02" /><label for="chk02">EU</label>
							</li>
							<li>
								<input type="checkbox" name="country" value="us" id="chk03" /><label for="chk03">USA</label>
							</li>
							<li>
								<input type="checkbox" name="country" value="latin" id="chk04" /><label for="chk04">Latin America</label>
							</li>
							<li>
								<input type="checkbox" name="country" value="usac" id="chk05" /><label for="chk05">USA & Canada</label>
							</li>
							<li>
								<input type="checkbox" name="country" value="russ" id="chk06" /><label for="chk06">Russia</label>
							</li>
							<li>
								<input type="checkbox" name="country" value="asia" id="chk07" /><label for="chk07">Asia & Pacific</label>
							</li>
							<li>
								<input type="checkbox" name="country" value="usasia" id="chk08" /><label for="chk08">USA, Canada, Asia & Pacific</label>
							</li>
							<li>
								<input type="checkbox" name="country" value="euasia" id="chk9" /><label for="chk9">EU, Middle East, North Africa, Asia & Pacific</label>
							</li>
							<li>
								<input type="checkbox" name="country" value="eulatin" id="chk10" /><label for="chk10">EU, Middle East, North Africa, USA, Canada & Latin America</label>
							</li>
							<li>
								<input type="checkbox" name="country" value="middle" id="chk11" /><label for="chk11">Middle East & North Africa</label>
							</li>
							<li>
								<input type="checkbox" name="country" value="dach" id="chk12" /><label for="chk12">DACH</label>
							</li>
							<li>
								<input type="checkbox" name="country" value="other" id="chk12" /><label for="chk12">Other</label>
							</li>
						</ul>
					</div>
					<div class="input-outer">
						<div class="search-holder">
							<input type="text" placeholder="Search" id="filtersearch" />
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="custom-partner-tab-content">
					<?php
					if ($solution) {
						?>
						<div class="custom-tab-panel active" data-panel="solution-tab">
							<div class="partners-holder">
								<?php
								foreach ($solution as $sol) {
									$name = get_the_title($sol->ID);
									$level = get_field('partner_level', $sol->ID);
									$servs = get_field('services', $sol->ID);
									$country = get_field('region', $sol->ID);
									$logo = get_field('logo', $sol->ID);
									$text = get_field('text', $sol->ID);
									$service_text = get_field('service_text', $sol->ID);
									$website_link = get_field('website_link', $sol->ID);

									echo '<div class="partner-col" data-type="' . $level;
									foreach ($servs as $s) {
										echo ' ' . $s;
									}
									echo '" data-country="' . $country . '">';
									echo '<div class="partner-box">';
									echo '<div class="certificate-line ' . $level . '">';
									echo $level . ' Partner';
									echo '</div>';
									echo '<div class="partner-logo">';
									if (!empty($logo)) {
										echo '<img src="' . $logo["url"] . '" alt="' . $logo["alt"] . '" title="' . $logo["title"] . '" />';
									}
									echo '</div>';
									echo '<div class="partner-text">';
									echo '<h4>' . $name . '</h4>';
									if (!empty($text)) {
										echo wpautop($text);
									}
									echo '</div>';
									echo '<ul class="partner-info">';
									if (!empty($service_text)) {
										echo '<li>' . $service_text . '</li>';
									}
									if (!empty($website_link)) {
										echo '<li><a href="' . $website_link . '" target="_blank">Go to website</a></li>';
									}
									echo '</ul>';
									echo '</div>';
									echo '</div>';
								} ?>
							</div>
						</div>
					<?php
					}
					if ($technology) {
						?>
						<div class="custom-tab-panel" data-panel="technology-tab">
							<div class="partners-holder">
								<?php
								foreach ($technology as $teh) {
									$name = get_the_title($teh->ID);
									$logo = get_field('logo', $teh->ID);
									$text = get_field('text', $teh->ID);
									$service_text = get_field('service_text', $teh->ID);
									$website_link = get_field('website_link', $teh->ID);
									echo '<div class="partner2-col">';
									echo '<div class="partner-box">';
									echo '<div class="partner-logo">';
									if (!empty($logo)) {
										echo '<img src="' . $logo["url"] . '" alt="' . $logo["alt"] . '" title="' . $logo["title"] . '" />';
									}
									echo '</div>';
									echo '<div class="partner-text">';
									echo '<h4>' . $name . '</h4>';
									if (!empty($text)) {
										echo wpautop($text);
									}
									echo '</div>';
									echo '<ul class="partner-info">';
									if (!empty($service_text)) {
										echo '<li>' . $service_text . '</li>';
									}
									if (!empty($website_link)) {
										echo '<li><a href="' . $website_link . '" target="_blank">Go to website</a></li>';
									}
									echo '</ul>';
									echo '</div>';
									echo '</div>';
								} ?>
							</div>
						</div>
					<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>