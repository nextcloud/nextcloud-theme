<?php

$sol_id = $args['sol_id'];

$name = get_the_title($sol_id);
$level = get_field('partner_level', $sol_id);
$servs = get_field('services', $sol_id);
$country = get_field('region', $sol_id);

if (is_array($country)) {
	$country = implode(',', $country);
}

$logo = get_field('logo', $sol_id);
$text = get_field('text', $sol_id);
$service_text = get_field('service_text', $sol_id);
$website_link = get_field('website_link', $sol_id);

echo '<div class="partner-col" id="partner-' . $sol_id . '" data-type="' . $level;
foreach ($servs as $s) {
	echo ' ' . $s;
}
echo '" data-country="' . $country . '">';
echo '<div class="partner-box">';

//level - do not show for Technology providers
if ($level && get_post_meta($sol_id, 'service_text', true) != 'Technology Partner') {
	echo '<div class="certificate-line ' . $level . '">';
	echo __($level, 'nextcloud') . ' partner';
	echo '</div>';
}

echo '<div class="partner-logo">';
if (!empty($logo)) {
	echo '<img src="' . $logo['url'] . '" alt="' . $logo['alt'] . '" title="' . $logo['title'] . '" />';
}
echo '</div>';
echo '<div class="partner-text truncated">';
echo '<h4>' . $name . '</h4>';
if (!empty($text)) {
	echo wpautop($text);
}
echo '</div>';
echo '<div class="see_more">' . __('See more', 'nextcloud') . '</div>';

echo '<ul class="partner-info">';
if (!empty($service_text)) {
	echo '<li class="service_text">' . $service_text . '</li>';
}
if (!empty($website_link)) {
	echo '<li class="website_link"><a href="' . $website_link . '" target="_blank">' . __('Go to website', 'nextcloud') . '</a></li>';
}
echo '</ul>';
echo '</div>';
echo '</div>';
