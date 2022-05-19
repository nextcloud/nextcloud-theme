<?php
use GeoIp2\Database\Reader;

require_once locate_template('config.php');

try {
	$readerCity = new Reader(locate_template('dist/signup/assets/GeoLite2/GeoLite2-City.mmdb'));
	$location = $readerCity->city(whatismyip())->location;
} catch (\Exception $e) {
	$location = false;
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
		data-ll="<?php echo htmlspecialchars(json_encode($location)) ?>"
		data-ocsapi="<?php echo array_key_exists('HTTP_OCS_APIREQUEST', $_SERVER) ?>"
		data-l10n="<?php echo htmlspecialchars(json_encode($registerL10n)) ?>"
		data-officialapps="<?php echo htmlspecialchars(json_encode($officialApps)) ?>"
		data-coreapps="<?php echo htmlspecialchars(json_encode($coreApps)) ?>">
	</div>
	<div id="register-details" class="container"><p></p></div>
</section>

<section class="section--disclaimer">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<h3 class="section--paragraph__title"><?php echo t('Providers'); ?></h3>
				<p class="section--paragraph"><?php echo t('While we have done our best in choosing quality providers, we can not take any responsibility for their services.'); ?><br>
				<p class="section--paragraph"><?php echo t('If there are any issues with your account, please note that we do NOT record any of your information on our side so we can not help you. Please contact the provider you signed up with.'); ?></p>
				<p class="section--paragraph"><?php echo t('Free services are of course paid somewhere by someone. The listed Nextcloud providers all give a free account with 2 to 5 GB storage but offer larger storage and more options like a dedicated setup for a fee, which funds the free accounts. Consider paying for your account, it helps them provide this service!'); ?></p>
				<p><a class="button button--white button--arrow button--large" href="https://help.nextcloud.com/c/hosting" target="_blank"><?php echo t('Give feedback on providers'); ?></a></p>
				</p>
				<h3 class="section--paragraph__title"><?php echo t('Privacy'); ?></h3>
				<p class="section--paragraph"><?php echo t('By proceeding, you agree with our privacy policy. In short, we only handle the minimum amount of user data for the purpose of enabling the provider to create a minimal account and do not store any personally identifiable data.'); ?></p>
				<p><a href="<?php echo home_url('privacy') ?>" target="_blank" class="button button--white button--arrow button--large"><?php echo t('Nextcloud privacy policy'); ?></a></p>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/dist/signup/js/nextcloud-register-main.js"></script>
