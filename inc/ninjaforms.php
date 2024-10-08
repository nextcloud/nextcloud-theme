<?php

function get_string_between($string, $start, $end) {
	$string = ' ' . $string;
	$ini = strpos($string, $start);
	if ($ini == 0) {
		return '';
	}
	$ini += strlen($start);
	$len = strpos($string, $end, $ini) - $ini;
	return substr($string, $ini, $len);
}


//ninja forms filter emails - don't allow private or spam emails
function isDisposableEmail_private_list($email, $blocklist_path = null) {
	if (!$blocklist_path) {
		$blocklist_path = __DIR__ . '/disposable_email_blocklist_private.txt';
	}
	$disposable_domains = file($blocklist_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	$domain = mb_strtolower(explode('@', trim($email))[1]);
	return in_array($domain, $disposable_domains);
}

add_filter('ninja_forms_submit_data', 'nc_custom_ninja_forms_submit_data');
function nc_custom_ninja_forms_submit_data($form_data) {
	$form_id = $form_data['id'];

	foreach($form_data[ 'fields' ] as $field_id => $field) {
		if(str_contains($field[ 'key' ], 'email')) {
			$field_value = strtolower($form_data['fields'][$field_id]['value']);

			//check if valid format
			if (!filter_var($field_value, FILTER_VALIDATE_EMAIL)) {
				$form_data['errors']['fields'][$field_id] = 'Invalid email format!';
			}

			$validator = EmailValidation\EmailValidatorFactory::create($field_value);
			$arrayValidator = $validator->getValidationResults()->asArray();

			if(
				in_array($form_id, [
					1,// exclude Contact form
					30,// exclude Discuss your app form
					27,// exclude Newsletter form
					33, // exclude Contact Issue form
					68,// exclude Events newsletter form
					72,// exclude  Events lead collection form
					85,// exclude Hub announcements form
					95,// exclude Conference 2024 form
					96,// exclude call for proposals form for Conference 2024
					98,// exclude Test Newsletters form
					100,// exclude developer-webinar-registration
							
				])
			) {

				//basic forms validation
				if(
					!$arrayValidator['valid_mx_records'] ||
					$arrayValidator['disposable_email_provider']
					/* || !$arrayValidator['valid_host'] */
				) {
					$form_data['errors']['fields'][$field_id] = __('Please use a valid business email address. Did we make a mistake? <a href="/contact/" target="_blank" title="Contact us here">Contact us here</a>', 'nextcloud');
				}

			} elseif (
				in_array($form_id, [
					89 // unsubscribe form
				])
			) {

				if(!$arrayValidator['valid_mx_records']) {
					$form_data['errors']['fields'][$field_id] = __('Please use a valid business email address. Did we make a mistake? <a href="/contact/" target="_blank" title="Contact us here">Contact us here</a>', 'nextcloud');
				}

			} elseif (in_array($form_id, [
				90 // testing form
			])) {


				/*
				if( !$arrayValidator['valid_mx_records'] ){
					$form_data['errors']['fields'][$field_id] = __('The MX records are not valid.', 'nextcloud');
				}

				if( $arrayValidator['disposable_email_provider'] ){
					$form_data['errors']['fields'][$field_id] = __('Email is disposable.', 'nextcloud');
				}

				if( $arrayValidator['free_email_provider'] ){
					$form_data['errors']['fields'][$field_id] = __('The email is a free email provider', 'nextcloud');
				}

				if( isDisposableEmail_private_list($field_value) ){
					$form_data['errors']['fields'][$field_id] = __('Email is disposable (private list)', 'nextcloud');
				}

				$form_data['errors']['fields'][$field_id] = __('Please use a valid business email address. Did we make a mistake? <a href="/contact/" target="_blank" title="Contact us here">Contact us here</a>', 'nextcloud');
				*/

				if(
					!$arrayValidator['valid_mx_records'] ||
					$arrayValidator['disposable_email_provider']
					/* || !$arrayValidator['valid_host'] */
				) {
					$form_data['errors']['fields'][$field_id] = __('Please use a valid business email address. Did we make a mistake? <a href="/contact/" target="_blank" title="Contact us here">Contact us here</a>', 'nextcloud');
				}


			} else {
				//all checks enabled for all the other forms

						
				if(
					!$arrayValidator['valid_mx_records'] ||
					$arrayValidator['disposable_email_provider']
					|| $arrayValidator['free_email_provider']
					|| isDisposableEmail_private_list($field_value)
					// || !$arrayValidator['valid_host']
				) {
					$form_data['errors']['fields'][$field_id] = __('Please use a valid business email address. Did we make a mistake? <a href="/contact/" target="_blank" title="Contact us here">Contact us here</a>', 'nextcloud');
				}
						
						


			}

		}
	}

	return $form_data;
}




//save name, email and language on submitting the form
add_filter('ninja_forms_submit_data', 'nc_ninja_custom_save_cookies');
function nc_ninja_custom_save_cookies($form_data) {

	if (isset($_COOKIE['nc_cookie_banner'])) {
		$convenience_cookie_set = get_string_between($_COOKIE['nc_cookie_banner'], 'convenience\":', ',\"statistics');
	}

	if(isset($convenience_cookie_set)) {
		$form_fields = [];
		foreach($form_data[ 'fields' ] as $field_id => $field) {
		
			if(str_contains($field['key'], 'name') && !str_contains($field['key'], 'organization')) {
				$form_fields['nc_form_name'] = $field[ 'value' ];
			}

			if(str_contains($field['key'], 'email')) {
				$form_fields['nc_form_email'] = urlencode($field[ 'value' ]);
			}

			if(str_contains($field['key'], 'language')) {
				$form_fields['nc_form_lang'] = $field[ 'value' ];
			}

			if(str_contains($field['key'], 'phone')) {
				$form_fields['nc_form_phone'] = $field[ 'value' ];
			}
		}

		setcookie('nc_form_fields', base64_encode(json_encode($form_fields)), time() + (86400 * 30), "/");
	}


	return $form_data;
}


//pre-populating fields on display
//add_filter( 'ninja_forms_render_default_value', 'nc_change_nf_default_value', 10, 3 );
function nc_change_nf_default_value($default_value, $field_type, $field_settings) {
	if(isset($_COOKIE['nc_form_fields'])) {
		$nc_form_fields = json_decode(base64_decode($_COOKIE['nc_form_fields']), true);

		if(str_contains($field_settings['key'], 'name') && !str_contains($field_settings['key'], 'organization')) {
			if(isset($nc_form_fields['nc_form_name'])) {
				$default_value = $nc_form_fields['nc_form_name'];
			}
		}
		if(str_contains($field_settings['key'], 'email')) {
			if(isset($nc_form_fields['nc_form_email'])) {
				$default_value = urldecode($nc_form_fields['nc_form_email']);
			}
		}
		if(str_contains($field_settings['key'], 'phone')) {
			if(isset($nc_form_fields['nc_form_phone'])) {
				$default_value = $nc_form_fields['nc_form_phone'];
			}
		}
	}

	return $default_value;
}




/**
 * Update list options during form render. - Language list on whitepaper form
 *
 * @param array $options [ [ label, value, calc, (bool) selected, etc ], ... ]
 * @param array $settings
 */
add_filter('ninja_forms_render_options', function ($options, $settings) {


	if($settings['key'] == 'select_mailing_lists_checkboxes') {
		//echo "true!";
		foreach($options as &$option) {
			
			if(isset($_GET['list'])) {

				if($option[ 'value' ] == strip_tags($_GET['list'])) {
					$option[ 'selected' ] = true;
				}

			} elseif($option[ 'label' ] == 'Nextcloud news') {
				$option[ 'selected' ] = true;
			}
		}
	}
  
	return $options;
}, 10, 2);



//add custom ninja forms processing web hook - used to save contacts to newsletter
add_action('nc_ninja_forms_processing_save_to_newsletter', 'nc_ninja_forms_processing_save_to_newsletter_callback');
function nc_ninja_forms_processing_save_to_newsletter_callback($form_data) {
	
	if(isset($form_data) && isset($form_data[ 'form_id' ])) {
		$form_id = $form_data[ 'form_id' ];
	}
	
	$form_fields = $form_data[ 'fields' ];
	$email = '';
	$name = '';
	$phone = '';
	$mailing_list_ids = [7647]; // default ID 7647
	$subscribed = false;
	$select_mailing_lists_checkboxes = [];

	if(isset($form_id)) {
		if(
			$form_id == 27 // Newsletter form
			|| $form_id == 98 // Newsletter form TEST
			|| $form_id == 68  // Events newsletter form
			// ||  $form_id == 4 // whitepapers and case studies
			// ||  $form_id == 49 // Case study Meiji university
			|| $form_id == 66 // Get more information about event
			|| $form_id == 85 // Hub announcements form
		) {
			$subscribed = true; // automatically set as accepted for these forms
		} else {
			//for all the other forms
			//if($form_id == 66){ // for the events form, subscribe to the newsletters only if checkbox is selected
			$subscribed = false;
			//}
		}


		foreach($form_fields as $field) {

			if(str_contains($field['key'], 'keep_me_informed') || str_contains($field['key'], 'newsletter')) {
				// means users selected the newsletters checkbox
				if($field[ 'value' ] != 0) {
					$subscribed = true;
				}
			}

			if(str_contains($field['key'], 'name')) {
				$name = $field[ 'value' ];
			}

			if(
				str_contains($field['key'], 'email')
			) {
				$email = $field[ 'value' ];
			}


			//used for newsletter form
			if($field[ 'key' ] == 'select_mailing_lists_checkboxes') {
				$mailing_list_ids = $field[ 'value' ];
			}

			if(str_contains($field['key'], 'mailing_list_id')) {
				$mailing_list_ids[] = $field[ 'value' ];
			}
 

		}


	}
	

	if($subscribed) {
		$url = 'https://odoo.nextcloud.com';
		$db = 'nextcloud-crm-odoo-main-4730113';
		$username = "jos.poortvliet@nextcloud.com";
		$password = ODOO_API_KEY; // api key

		//logging in
		$common = ripcord::client("$url/xmlrpc/2/common");

		//authenticate
		$uid = $common->authenticate($db, $username, $password, []);

		$models = ripcord::client("$url/xmlrpc/2/object");
		$sub_id = $models->execute_kw($db, $uid, $password,
			'mailing.contact', 'create',
			[
				[
					'name' => $name,
					'email' => $email,
					'list_ids' => $mailing_list_ids
				]
			]
		);


		if($sub_id) {
			$args = [
				['id', '=', $sub_id ]
			];
			$kwargs = [
				'fields' => ['name', 'opt_out', 'email', 'subscription_list_ids', 'list_ids']
			];
			$subs = $models->execute_kw($db, $uid, $password, 'mailing.contact', 'search_read', [$args], $kwargs);
			$subscription_list_ids = $subs[0]['subscription_list_ids'];
	
			//update opt out status to enabled initially
			foreach($subscription_list_ids as $subscription_list_id) {
				$args = [
					['id', '=', $subscription_list_id]
				];
				$kwargs = [
					'fields' => ['contact_id', 'list_id', 'opt_out']
				];
				$subscription = $models->execute_kw($db, $uid, $password, 'mailing.contact.subscription', 'search_read', [$args], $kwargs);
	
				$list_id = $subscription[0]['list_id'][0];
				$subscription_id_single = $subscription[0]['id'];
				$opt_out = $subscription[0]['opt_out'];


				foreach($mailing_list_ids as $mailing_list_id) {
					if($list_id == $mailing_list_id && $opt_out == false) {
						$update_sub = $models->execute_kw($db, $uid, $password,
							'mailing.contact.subscription', 'update',
							[[$subscription_id_single], [
								'opt_out' => true
							]]
						);
					}
				}
			}


			//send the double opt in email
			$headers = [];
			// Override the default 'From' address if 'Force Sender Email' is not enabled
			$headers['From'] = 'Nextcloud <no-reply@e.nextcloud.com>';
			// Send the message as HTML

			add_filter('wp_mail_content_type', function ($content_type) {
				return 'text/html';
			});
			$headers['Content-Type'] = 'text/html; charset=ISO-8859-1';

			$lang = apply_filters('wpml_current_language', null);
			if($lang == 'en') {
				$curr_lang = "";
			} else {
				$curr_lang = apply_filters('wpml_current_language', null)."/";
			}
			

			$to = $email;
			$subject = __('Please confirm your subscription', 'nextcloud');
			$message = __('Hello', 'nextcloud').",<br />
            ".__('Please confirm your mailing subscription by clicking', 'nextcloud')."
             <a href='https://nextcloud.com/".$curr_lang."manage-subscription/?action=subscribe&sub=".$sub_id."&subs_ids=".implode(",", $subscription_list_ids)."'>".__('here', 'nextcloud')."</a>.<br />
            ".__('Or by accessing this link in your URL bar:', 'nextcloud')."
            https://nextcloud.com/".$curr_lang."/manage-subscription/?action=subscribe&sub=".$sub_id."&subs_ids=".implode(",", $subscription_list_ids)."<br />
            <br />
            ".__('If this wasn\'t requested by you, please ignore this email.', 'nextcloud')."
            <br />
            <br />
            ".__('Kind regards,', 'nextcloud')."
            <br />
            ".__('Nextcloud team.', 'nextcloud');


			// Send the email
			$response = wp_mail($to, $subject, $message, $headers);

		}

	}

	$form_settings = $form_data[ 'settings' ];
	$form_title = $form_data[ 'settings' ][ 'title' ];

}



//populate preferred language field with all languages in the world
add_filter('ninja_forms_render_options', function ($options, $settings) {
	
	//https://www.html-code-generator.com/php/array/languages-name-and-code
	$languages_list = [
		'en' => 'English',
		'es' => 'Spanish - español',
		'fr' => 'French - français',
		'it' => 'Italian - italiano',
		'pt' => 'Portuguese - português',
		'sv' => 'Swedish - svenska',
		'no' => 'Norwegian - norsk',
		'af' => 'Afrikaans',
		'sq' => 'Albanian - shqip',
		'am' => 'Amharic - አማርኛ',
		'ar' => 'Arabic - العربية',
		'an' => 'Aragonese - aragonés',
		'hy' => 'Armenian - հայերեն',
		'ast' => 'Asturian - asturianu',
		'az' => 'Azerbaijani - azərbaycan dili',
		'eu' => 'Basque - euskara',
		'be' => 'Belarusian - беларуская',
		'bn' => 'Bengali - বাংলা',
		'bs' => 'Bosnian - bosanski',
		'br' => 'Breton - brezhoneg',
		'bg' => 'Bulgarian - български',
		'ca' => 'Catalan - català',
		'ckb' => 'Central Kurdish - کوردی (دەستنوسی عەرەبی)',
		'zh' => 'Chinese - 中文',
		'zh-HK' => 'Chinese (Hong Kong) - 中文（香港）',
		'zh-CN' => 'Chinese (Simplified) - 中文（简体）',
		'zh-TW' => 'Chinese (Traditional) - 中文（繁體）',
		'co' => 'Corsican',
		'hr' => 'Croatian - hrvatski',
		'cs' => 'Czech - čeština',
		'da' => 'Danish - dansk',
		'nl' => 'Dutch - Nederlands',
		'en' => 'English',
		'en-AU' => 'English (Australia)',
		'en-CA' => 'English (Canada)',
		'en-IN' => 'English (India)',
		'en-NZ' => 'English (New Zealand)',
		'en-ZA' => 'English (South Africa)',
		'en-GB' => 'English (United Kingdom)',
		'en-US' => 'English (United States)',
		'eo' => 'Esperanto - esperanto',
		'et' => 'Estonian - eesti',
		'fo' => 'Faroese - føroyskt',
		'fil' => 'Filipino',
		'fi' => 'Finnish - suomi',
		'fr' => 'French - français',
		'fr-CA' => 'French (Canada) - français (Canada)',
		'fr-FR' => 'French (France) - français (France)',
		'fr-CH' => 'French (Switzerland) - français (Suisse)',
		'gl' => 'Galician - galego',
		'ka' => 'Georgian - ქართული',
		'de' => 'German - Deutsch',
		'de-AT' => 'German (Austria) - Deutsch (Österreich)',
		'de-DE' => 'German (Germany) - Deutsch (Deutschland)',
		'de-LI' => 'German (Liechtenstein) - Deutsch (Liechtenstein)',
		'de-CH' => 'German (Switzerland) - Deutsch (Schweiz)',
		'el' => 'Greek - Ελληνικά',
		'gn' => 'Guarani',
		'gu' => 'Gujarati - ગુજરાતી',
		'ha' => 'Hausa',
		'haw' => 'Hawaiian - ʻŌlelo Hawaiʻi',
		'he' => 'Hebrew - עברית',
		'hi' => 'Hindi - हिन्दी',
		'hu' => 'Hungarian - magyar',
		'is' => 'Icelandic - íslenska',
		'id' => 'Indonesian - Indonesia',
		'ia' => 'Interlingua',
		'ga' => 'Irish - Gaeilge',
		'it' => 'Italian - italiano',
		'it-IT' => 'Italian (Italy) - italiano (Italia)',
		'it-CH' => 'Italian (Switzerland) - italiano (Svizzera)',
		'ja' => 'Japanese - 日本語',
		'kn' => 'Kannada - ಕನ್ನಡ',
		'kk' => 'Kazakh - қазақ тілі',
		'km' => 'Khmer - ខ្មែរ',
		'ko' => 'Korean - 한국어',
		'ku' => 'Kurdish - Kurdî',
		'ky' => 'Kyrgyz - кыргызча',
		'lo' => 'Lao - ລາວ',
		'la' => 'Latin',
		'lv' => 'Latvian - latviešu',
		'ln' => 'Lingala - lingála',
		'lt' => 'Lithuanian - lietuvių',
		'mk' => 'Macedonian - македонски',
		'ms' => 'Malay - Bahasa Melayu',
		'ml' => 'Malayalam - മലയാളം',
		'mt' => 'Maltese - Malti',
		'mr' => 'Marathi - मराठी',
		'mn' => 'Mongolian - монгол',
		'ne' => 'Nepali - नेपाली',
		'no' => 'Norwegian - norsk',
		'nb' => 'Norwegian Bokmål - norsk bokmål',
		'nn' => 'Norwegian Nynorsk - nynorsk',
		'oc' => 'Occitan',
		'or' => 'Oriya - ଓଡ଼ିଆ',
		'om' => 'Oromo - Oromoo',
		'ps' => 'Pashto - پښتو',
		'fa' => 'Persian - فارسی',
		'pl' => 'Polish - polski',
		'pt' => 'Portuguese - português',
		'pt-BR' => 'Portuguese (Brazil) - português (Brasil)',
		'pt-PT' => 'Portuguese (Portugal) - português (Portugal)',
		'pa' => 'Punjabi - ਪੰਜਾਬੀ',
		'qu' => 'Quechua',
		'ro' => 'Romanian - română',
		'mo' => 'Romanian (Moldova) - română (Moldova)',
		'rm' => 'Romansh - rumantsch',
		'ru' => 'Russian - русский',
		'gd' => 'Scottish Gaelic',
		'sr' => 'Serbian - српски',
		'sh' => 'Serbo-Croatian - Srpskohrvatski',
		'sn' => 'Shona - chiShona',
		'sd' => 'Sindhi',
		'si' => 'Sinhala - සිංහල',
		'sk' => 'Slovak - slovenčina',
		'sl' => 'Slovenian - slovenščina',
		'so' => 'Somali - Soomaali',
		'st' => 'Southern Sotho',
		'es' => 'Spanish - español',
		'es-AR' => 'Spanish (Argentina) - español (Argentina)',
		'es-419' => 'Spanish (Latin America) - español (Latinoamérica)',
		'es-MX' => 'Spanish (Mexico) - español (México)',
		'es-ES' => 'Spanish (Spain) - español (España)',
		'es-US' => 'Spanish (United States) - español (Estados Unidos)',
		'su' => 'Sundanese',
		'sw' => 'Swahili - Kiswahili',
		'sv' => 'Swedish - svenska',
		'tg' => 'Tajik - тоҷикӣ',
		'ta' => 'Tamil - தமிழ்',
		'tt' => 'Tatar',
		'te' => 'Telugu - తెలుగు',
		'th' => 'Thai - ไทย',
		'ti' => 'Tigrinya - ትግርኛ',
		'to' => 'Tongan - lea fakatonga',
		'tr' => 'Turkish - Türkçe',
		'tk' => 'Turkmen',
		'tw' => 'Twi',
		'uk' => 'Ukrainian - українська',
		'ur' => 'Urdu - اردو',
		'ug' => 'Uyghur',
		'uz' => 'Uzbek - o‘zbek',
		'vi' => 'Vietnamese - Tiếng Việt',
		'wa' => 'Walloon - wa',
		'cy' => 'Welsh - Cymraeg',
		'fy' => 'Western Frisian',
		'xh' => 'Xhosa',
		'yi' => 'Yiddish',
		'yo' => 'Yoruba - Èdè Yorùbá',
		'zu' => 'Zulu - isiZulu'
	];

	if(str_contains($settings['key'], 'language')) {

		$options = [];
		$browser_lang = 'en'; // default browser language

		if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
			$browser_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		}

		$pref_lang = '';
		if(isset($_COOKIE['nc_form_fields'])) {
			$nc_form_fields = json_decode(base64_decode($_COOKIE['nc_form_fields']), true);
			if(isset($nc_form_fields['nc_form_lang'])) {
				$pref_lang = $nc_form_fields['nc_form_lang'];
			}
		} else {
			$pref_lang = $browser_lang;
		}


		foreach($languages_list as $code => $language) {
			$selected = false;

			if($pref_lang == $code) {
				$selected = true;
			}

			$options[] = [
				'label' => $language,
				'value' => $code,
				'calc' => 0,
				'selected' => $selected
			];

		}
		
	}
  
	return $options;
}, 10, 2);


//populate event names Activity IDs on the Events lead collection form - https://nextcloud.com/events-lead-collection-form/
add_filter('ninja_forms_render_options', function ($options, $settings) {

	$events_list = [];

	$args = [
		'post_type' => 'event',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'tax_query' => [
			[
				'taxonomy' => 'event_categories',
				'field' => 'slug',
				'terms' => 'exhibition',
			],
		]
	];

	$events_query = new WP_Query($args);
	if ($events_query->have_posts()) {
		while ($events_query->have_posts()) {
			$events_query->the_post();

			$event_end_datetime = get_field('event_end_date_and_time', false, false);
			$now_timestamp = new DateTime("now");
			$event_end_datetime_timestamp = new DateTime($event_end_datetime);
			$interval = $now_timestamp->diff($event_end_datetime_timestamp);
			$interval_days = $interval->format('%R%a');

			$activity_id = get_field('activity_id');
			if(isset($activity_id) && str_contains($activity_id, '-apt-')) {
				$activity_id = str_replace("-apt-", "-", $activity_id);
			}

			$title = get_the_title();
			if(get_field('event_short_title')) {
				$title = get_field('event_short_title');
			}

			/*
			if($interval->days < 30) {
				$events_list[$activity_id]=$title;
			}
			*/

			if($interval_days >= 0) { // upcoming event
				$events_list[$activity_id] = $title;
			} else {
				//past event
				if($interval->days < 30) {
					$events_list[$activity_id] = $title;
				}
			}


		}
	} else {
		// no posts found
	}
	/* Restore original Post Data */
	wp_reset_postdata();

	if($settings['key'] == 'event_name_1685534754887') {
		//$options = []; //don't reset the options, so it takes also the manual values

		foreach($events_list as $id => $event_name) {
			$options[] = [
				'label' => $event_name,
				'value' => $id,
				'calc' => 0
			];
		}
	}
  
	return $options;
}, 10, 2);



//custom ninja forms processing web hook to add conference attendees
add_action('nc_ninja_forms_processing_save_attendees', 'nc_ninja_forms_processing_save_attendees_callback');
function nc_ninja_forms_processing_save_attendees_callback($form_data) {
	$conf_form_id = 95;

	if(isset($form_data) && isset($form_data[ 'form_id' ])) {
		$form_id = $form_data[ 'form_id' ];
	}

	if(isset($form_id) && $form_id == $conf_form_id) { // enable this only for the form ID 95 = Conference 2024 registration
		$name = '';
		$email = '';
		$phone = '';
		$attendance_date = '';
		$allergies = '';
		$contribution = '';
		$event_id = 27; //Nextcloud Conference 2024


		$form_fields = $form_data[ 'fields' ];
		foreach($form_fields as $field) {
			if(str_contains($field['key'], 'name')) {
				$name = $field[ 'value' ];
			}
			if(str_contains($field['key'], 'email')) {
				$email = $field[ 'value' ];
			}
			if(str_contains($field['key'], 'phone')) {
				$phone = $field[ 'value' ];
			}
			if(str_contains($field['key'], 'answer_18')) {
				$attendance_date = $field[ 'value' ];
			}
			if(str_contains($field['key'], 'answer_22')) {
				$allergies = $field[ 'value' ];
			}
			if(str_contains($field['key'], 'answer_26')) {
				$contribution = $field[ 'value' ];
			}
		}

		$url = 'https://odoo.nextcloud.com';
		$db = 'nextcloud-crm-odoo-main-4730113';
		$username = "jos.poortvliet@nextcloud.com";
		$password = ODOO_API_KEY; // api key
		//$password = ''; // password

		//logging in
		$common = ripcord::client("$url/xmlrpc/2/common");
		//authenticate
		$uid = $common->authenticate($db, $username, $password, []);
		$models = ripcord::client("$url/xmlrpc/2/object");




		/*
		$questions = $models->execute_kw($db, $uid, $password, 'event.question', 'search_read', array(array(array('event_id', '=', $event_id))), array('fields' => array('id', 'name')));
		// Capture answers from the form submission
		$answers = array();
		foreach ($questions as $question) {
			$field_id = 'field_' . $question['id']; // Assuming the field IDs in Ninja Forms correspond to question IDs in Odoo
			if (isset($form_data['fields'][$field_id])) {
				$answers[$question['id']] = $form_data['fields'][$field_id];
			}
		}
		*/



		$sub_id = $models->execute_kw($db, $uid, $password,
			'event.registration', 'create',
			[
				[
					'event_id' => $event_id,
					'name' => $name,
					'email' => $email,
					'phone' => $phone,
					/*'answer_11' => $attendance_date,
					'answer_15' => $allergies,
					'answer_19' => $contribution*/
					//'answers' => $answers
					'answers' => [
						'answer_18' => $attendance_date,
						'answer_22' => $allergies,
						'answer_26' => $contribution
					]
				]
			]
		);

		$form_settings = $form_data[ 'settings' ];
		$form_title = $form_data[ 'settings' ][ 'title' ];
	}

}





//populate Enterprise Day downloads form select with all presentations
add_filter('ninja_forms_render_options', function ($options, $settings) {
	$ed_presentations = [
	];

	
	if(have_rows('enterprise_day_slides', 'option')):
		while(have_rows('enterprise_day_slides', 'option')) :
			the_row();

			$ed_presentations[] = [
				'title' => get_sub_field('presentation_title'),
				'file_url' => get_sub_field('presentation_pdf'),
				'interests' => get_sub_field('presentation_interests')
			];

		endwhile;
	endif;
	
	
	//prefill the slides list select field
	if($settings['key'] == 'select_slides_you_want_to_download_1714669386359') {
		//$options = []; //reset the options
		foreach($ed_presentations as $id => $ed_presentation) {
			$options[] = [
				'label' => $ed_presentation['title'],
				'value' => preg_replace('/[ ,]/', '-', $ed_presentation['title']) //replaces spaces and commas with dashes
			];
		}
	}

	//prefill the slides URLs select field (hidden)
	if($settings['key'] == 'all_slides_urls_1715151284630') {
		//$options = []; //reset the options
		foreach($ed_presentations as $id => $ed_presentation) {
			$options[] = [
				'label' => $ed_presentation['file_url'],
				'value' => $ed_presentation['file_url']
			];
		}
	}

	//prefill the slides interests select field (hidden)
	if($settings['key'] == 'all_slides_interests_1715151300664') {
		//$options = []; //reset the options
		foreach($ed_presentations as $id => $ed_presentation) {
			$options[] = [
				'label' => $ed_presentation['interests'],
				'value' => str_replace(' ', '', $ed_presentation['interests']) //remove spaces
			];
		}
	}


  
	return $options;
}, 10, 2);
