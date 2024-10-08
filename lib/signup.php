<?php

use GeoIp2\Database\Reader;
use Predis\Client;

require_once locate_template('config.php');

const USER_AGENT_CLIENT_ANDROID = '/^Mozilla\/5\.0 \(Android\) (ownCloud|Nextcloud)\-android.*$/';
const USER_AGENT_TALK_ANDROID = '/^Mozilla\/5\.0 \(Android\) Nextcloud\-Talk v.*$/';
const USER_AGENT_CLIENT_DESKTOP = '/^Mozilla\/5\.0 \([A-Za-z ]+\) (mirall|csyncoC)\/.*$/';
const USER_AGENT_CLIENT_IOS = '/^Mozilla\/5\.0 \(iOS\) (ownCloud|Nextcloud)\-iOS.*$/';
const USER_AGENT_TALK_IOS = '/^Mozilla\/5\.0 \(iOS\) Nextcloud\-Talk v.*$/';

add_action('rest_api_init', 'registration_register_routes');

$redis = new Client(REDIS);
$readerCity = new Reader(locate_template('dist/signup/assets/GeoLite2/GeoLite2-City.mmdb'));
$readerCountry = new Reader(locate_template('dist/signup/assets/GeoLite2/GeoLite2-Country.mmdb'));

// Get proper ip in case of reverse proxy
function whatismyip() {
	return $_SERVER['HTTP_CLIENT_IP']
		?? $_SERVER['HTTP_X_FORWARDED_FOR']
		?? $_SERVER['HTTP_X_FORWARDED']
		?? $_SERVER['HTTP_FORWARDED_FOR']
		?? $_SERVER['HTTP_FORWARDED']
		?? $_SERVER['REMOTE_ADDR']
		?? null;
}

function get_device() {
	$userAgents = [
		'Android' => USER_AGENT_CLIENT_ANDROID,
		'Android Talk' => USER_AGENT_TALK_ANDROID,
		'Desktop' => USER_AGENT_CLIENT_DESKTOP,
		'iOS' => USER_AGENT_CLIENT_IOS,
		'iOS Talk' => USER_AGENT_TALK_IOS
	];

	if (isset($_SERVER['HTTP_USER_AGENT'])) {
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		foreach ($userAgents as $name => $regex) {
			if (preg_match($regex, $userAgent)) {
				return $name;
			}
		}
		return 'Website';
	}
	return 'unknown';
}

function registration_register_routes(): void {
	// signup post method
	register_rest_route('signup', '/account', [
		'methods' => WP_REST_Server::CREATABLE,
		'callback' => 'request_account',
		'args' => [
			'id' => [
				'validate_callback' => function ($param) {
					return is_numeric($param);
				}
			],
			'email' => [
				'validate_callback' => function ($param) {
					return filter_var($param, FILTER_VALIDATE_EMAIL);
				}
			]
		]
	]);

	// providers json
	register_rest_route('signup', '/providers', [
		'methods' => WP_REST_Server::READABLE,
		'callback' => 'get_providers_list'
	]);

	// get statistics
	register_rest_route('signup', '/stats', [
		'methods' => WP_REST_Server::READABLE,
		'callback' => 'get_statistics',
		'args' => [
			'key' => [
				'required' => true,
				'validate_callback' => function ($key) {
					return strlen($key) === 32;
				}
			],
			'time' => [
				'validate_callback' => function ($time) {
					return is_numeric($time);
				}
			]
		]
	]);
}

function request_account($request) {
	// redis rate limit
	global $redis;
	global $readerCountry;

	$limit = [
		'interval' => 300, // seconds
		'num_requests' => 10, // number of requests allowed per interval
		'user_ip' => whatismyip() // getting the user IP.
	];

	$rateId = "requests_count_{$limit['user_ip']}";
	$rateLimit = (int) $redis->get($rateId);
	if ($rateLimit + 1 > $limit['num_requests']) {
		$remainingTTL = $redis->ttl($rateId);
		if ($remainingTTL < 1) {
			$remainingTTL = $limit['interval'];
		}
		$minutes = max((int)round($remainingTTL / 60), 1);
		$text = "Please retry in $minutes minutes";
		if ($minutes === 1) {
			$text = "Please retry in 1 minute";
		}
		return new WP_Error('rate_limit_exceeded', 'Too many requests - ' . $text, ['status' => 429]);
	}

	$request = json_decode($request->get_body(), true);

	// verify data
	if (!array_key_exists('email', $request) || !array_key_exists('id', $request)) {
		return new WP_Error('rest_invalid_param', 'Invalid parameter(s)', ['status' => 400]);
	}

	// init vars
	$email = $request['email'];
	$providerId = intval($request['id']);

	$locationId = 0; //default
	if(isset($request['location'])) {
		$locationId = intval($request['location']);
	}
	
	$newsletter = array_key_exists('newsletter', $request);
	$subscribe = boolval($request['subscribe']);

	// get providers list && check provider id
	$json = json_decode(file_get_contents(PROVIDERS_FILE));
	if (!array_key_exists($providerId, $json)) {
		return new WP_Error('rest_invalid_param', 'Invalid parameter(s)', ['status' => 400]);
	}

	// init post request
	$provider = $json[$providerId];
	$url = $provider->locations[$locationId]->url . '/ocs/v2.php/account/request/' . $provider->locations[$locationId]->key;
	$data = [
		'headers' => [
			'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'
		],
		'body' => 'email=' . $email,
		'timeout' => 30
	];

	// request account && consume one rate token
	$post = wp_remote_post($url, $data);
	$ttl = $redis->ttl($rateId);
	$redis->set($rateId, $rateLimit + 1);
	$redis->expire($rateId, $ttl > 0 ? $ttl : $limit['interval']);


	if (!is_array($post) || !array_key_exists('response', $post)) {
		error_log('Provider did not returned 201: ' . json_encode($post));
		return new WP_Error('unknown_error', 'Something happened', ['status' => 400]);
	} elseif ($post['response']['code'] !== 201) {
		if ($post['response']['code'] === 400 && $post['response']['message'] === 'invalid mail address') {
			return new WP_Error('invalid_mail_address', 'invalid mail address', ['status' => 400]);
		}
		if ($post['response']['code'] === 400 && $post['response']['message'] === 'Bad Request') {
			$decodedBody = json_decode($post['body'], true);
			if ($decodedBody !== null &&
				isset($decodedBody['data']['message']) &&
				$decodedBody['data']['message'] === 'user already exists') {
				return new WP_Error('username_already_used', 'User already exists', ['status' => 400]);
			}
		}
		error_log('Provider did not returned 201: ' . json_encode($post));
		return new WP_Error('unknown_error', 'Something happened', ['status' => 400]);
	}

	$response = json_decode($post['body'])->data;

	if (!is_string($response->setPassword)) {
		return new WP_Error('rest_invalid_param', 'An unknown error occured', ['status' => 400]);
	}

	// SUCCESS let's continue
	if ($subscribe) {
		// don't do anything else even if it fails.
		// let's focus on the ux flow
		subscribe($email);
	}

	// store stats
	try {
		$country = $readerCountry->country(whatismyip())->country->isoCode;
	} catch (Exception $e) {
		$country = 'unknown';
	}
	try {
		$redis->set(time(), json_encode([
			'device' => get_device(),
			'country' => $country,
			'provider' => $provider->name
		]));
	} catch (Exception $e) {
		error_log($e->getMessage());
	}

	// return nc://url
	if (array_key_exists('ocsapi', $request) && $request['ocsapi'] === true) {
		return $response->setPassword . '/ocs';
	}

	return $response->setPassword;
}

function get_providers_list() {
	// get providers list
	$json = json_decode(file_get_contents(PROVIDERS_FILE));

	if (!is_array($json)) {
		return new WP_Error('unknown_error', 'Invalid provider file', ['status' => 400, 'json' => PROVIDERS_FILE]);
	}

	// obfuscate keys
	foreach ($json as $provider) {
		// safety fallback
		unset($provider->key);
		unset($provider->url);
		foreach ($provider->locations as $location) {
			unset($location->key);
			unset($location->url);
		}
	}

	return $json;
}

function subscribe($email) {
	$data = [
		'headers' => [
			'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'
		],
		'body' => 'login=' . NEWSLETTER_API_USER . '&password=' . NEWSLETTER_API_TOKEN,
		'timeout' => 5
	];

	// login
	$post = wp_remote_post(NEWSLETTER_API_URL . '&cmd=login', $data);

	// subscribe
	$data['body'] .= '&email=' . $email . '&lists=' . NEWSLETTER_ID;
	$post = wp_remote_post(NEWSLETTER_API_URL . '&cmd=subscribe', $data);

	return $post;
}

function get_statistics() {
	global $redis;
	if (isset($_GET['key']) && $_GET['key'] === PPP_KEY) {
		
		// select every proper timestamp ()
		// TODO: change the timestamp for May 18, 2033 @ 5:33:20 am 😂
		$keys = $redis->keys('1*');

		// filter out
		if ($_GET['time'] ?? false) {
			$keys = array_filter($keys, function ($time) {
				return $time > $_GET['time'];
			});
		}

		// no results
		if (count($keys) === 0) {
			return [];
		}

		set_time_limit(0);
		$data = array_reduce($keys, function ($array, $key) {
			global $redis;
			$array[$key] = json_decode($redis->get($key) ?? '');

			return $array;
		});

		return $data;
	}

	return new WP_Error('forbidden', 'Forbidden', ['status' => 403]);
}
