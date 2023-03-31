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
function t($string) {
	return $string;
}

$registerL10n = [
	'subscribe' => t('Subscribe to our newsletter'),
	'email' => t('Your email address'),
	'success' => t('Success! Redirecting you to the provider'),
	'error' => t('Error:'),
	'processing' => t('Creating your account'),
	'register' => t('Sign up'),
	'change' => t('change provider'),
	'close' => t('close'),
	'far' => t('Far far away'),
	'geterror' => t('Error while retrieving the providers list.'),
	'tos' => t('Terms of service'),
	'tosagree' => t('I agree to the %tos%'),
	'toserror' => t('Please agree to the terms of service')
];

$officialApps = [
	'files' => t('Files'),
	'calendar' => t('Calendar'),
	'contacts' => t('Contacts'),
	'spreed' => t('Talk'),
	'mail' => t('Mail'),
	'tasks' => t('Tasks'),
	'notes' => t('Notes'),
	'news' => t('News'),
	'twofactor_totp' => t('Two-factor authentication'),
	'twofactor_u2f' => t('Two-factor U2F'),
	'gallery' => t('Gallery'),
	'photos' => t('Photos'),
	'collabora' => t('Collabora Online'),
	'onlyoffice' => t('Onlyoffice'),
	'deck' => t('Deck'),
	'fulltextsearch' => t('Full text search'),
	'mindmaps' => t('Mindmaps'),
	'passman' => t('Passman'),
	'drawio' => t('Draw.io'),
	'bookmarks' => t('Bookmarks'),
	'audioplayer' => t('Audioplayer'),
	'appointments' => t('Appointments')
];

$coreApps = ['files', 'calendar', 'contacts', 'spreed', 'mail', 'tasks', 'notes'];
?>
<link href="<?php echo get_template_directory_uri(); ?>/dist/signup/css/signup.css?v=3" rel="stylesheet">

<div class="background register-background">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<h2><?php echo t('Simple sign up');?></h2>
				<h3><?php echo t('Sign up now with a Nextcloud provider to get a free account!');?></h3>
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