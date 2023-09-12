<?php
use GeoIp2\Database\Reader;

require_once locate_template('config.php');

try {
	$ip = whatismyip();
	$readerCity = new Reader(locate_template('dist/signup/assets/GeoLite2/GeoLite2-City.mmdb'));
	$location = $readerCity->city($ip)->location;
} catch (\Exception $e) {
	$location = false;
	$ip = null;
}

/**
 * Translation placeholder
 * !TODO Replace with proper translation methods
 */
// $l = new L10N('signup');
/*
function __($string) {
	return $string;
}
*/

$registerL10n = [
	'subscribe' => __('Subscribe to our newsletter', 'nextcloud'),
	'email' => __('Your email address', 'nextcloud'),
	'success' => __('Success! Redirecting you to the provider', 'nextcloud'),
	'error' => __('Error:', 'nextcloud'),
	'processing' => __('Creating your account', 'nextcloud'),
	'register' => __('Sign up', 'nextcloud'),
	'change' => __('change provider', 'nextcloud'),
	'close' => __('close', 'nextcloud'),
	'far' => __('Far far away', 'nextcloud'),
	'geterror' => __('Error while retrieving the providers list.', 'nextcloud'),
	'tos' => __('Terms of service', 'nextcloud'),
	'tosagree' => __('I agree to the %tos%', 'nextcloud'),
	'toserror' => __('Please agree to the terms of service', 'nextcloud')
];

$officialApps = [
	'files' => __('Files', 'nextcloud'),
	'calendar' => __('Calendar', 'nextcloud'),
	'contacts' => __('Contacts', 'nextcloud'),
	'spreed' => __('Talk' , 'nextcloud'),
	'mail' => __('Mail', 'nextcloud'),
	'tasks' => __('Tasks', 'nextcloud'),
	'notes' => __('Notes', 'nextcloud'),
	'news' => __('News', 'nextcloud'),
	'twofactor_totp' => __('Two-factor authentication', 'nextcloud'),
	'twofactor_u2f' => __('Two-factor U2F', 'nextcloud'),
	'gallery' => __('Gallery','nextcloud'),
	'photos' => __('Photos','nextcloud'),
	'collabora' => __('Collabora Online', 'nextcloud'),
	'onlyoffice' => __('Onlyoffice', 'nextcloud'),
	'deck' => __('Deck', 'nextcloud'),
	'fulltextsearch' => __('Full text search', 'nextcloud'),
	'mindmaps' => __('Mindmaps', 'nextcloud'),
	'passman' => __('Passman', 'nextcloud'),
	'drawio' => __('Draw.io', 'nextcloud'),
	'bookmarks' => __('Bookmarks', 'nextcloud'),
	'audioplayer' => __('Audioplayer', 'nextcloud'),
	'appointments' => __('Appointments', 'nextcloud')
];

$coreApps = ['files', 'calendar', 'contacts', 'spreed', 'mail', 'tasks', 'notes'];
?>
<link href="<?php echo get_template_directory_uri(); ?>/dist/signup/css/signup.css?v=3" rel="stylesheet">

<div class="background register-background">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<h1><?php echo __('Simple sign up', 'nextcloud');?></h1>
				<h3><?php echo __('Sign up now with a Nextcloud provider to get a free account!', 'nextcloud');?></h3>
			</div>
		</div>
	</div>
</div>

<section class="section--providers">
	<div id="register" class="container"
		data-ip="<?php echo htmlspecialchars($ip) ?>"
		data-ll="<?php echo htmlspecialchars(json_encode($location)) ?>"
		data-ocsapi="<?php echo array_key_exists('HTTP_OCS_APIREQUEST', $_SERVER) ?>"
		data-l10n="<?php echo htmlspecialchars(json_encode($registerL10n)) ?>"
		data-officialapps="<?php echo htmlspecialchars(json_encode($officialApps)) ?>"
		data-coreapps="<?php echo htmlspecialchars(json_encode($coreApps)) ?>">
	</div>
	<div id="register-details" class="container"><p></p></div>
</section>

<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/dist/signup/js/nextcloud-register-main.js"></script>