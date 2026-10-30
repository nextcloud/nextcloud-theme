<?php
//utils
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


//connect to Odoo
function getOdooConnection($type = null) {

	if ($type == 'staging') { //staging odoo
		$url = STAGING_ODOO_URL;
		$db = STAGING_ODOO_DB_NAME;
		$username = STAGING_ODOO_USERNAME;
		$password = STAGING_ODOO_API_KEY; // api key
	} else {
		$url = ODOO_URL;
		$db = ODOO_DB_NAME;
		$username = ODOO_USERNAME;
		$password = ODOO_API_KEY; // api key
	}

	return [$url, $db, $username, $password];
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

	if (str_contains($settings['key'], 'language')) {

		$options = [];

		$browser_lang = 'en'; // default browser language
		if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
			$browser_lang = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']);
			$browser_lang = str_replace('_', '-', $browser_lang);
			$browser_lang = strtolower(substr($browser_lang, 0, 2));
		}

		$pref_lang = $browser_lang;

		//if the nc_form_fields cookie is set, get the preferred language from there
		if (isset($_COOKIE['nc_form_fields'])) {
			$nc_form_fields = json_decode(base64_decode($_COOKIE['nc_form_fields']), true);
			if (isset($nc_form_fields['nc_form_lang'])) {
				$pref_lang = $nc_form_fields['nc_form_lang'];
			}
		}

		foreach ($languages_list as $code => $language) {
			$selected = false;
			if (
				strtolower($pref_lang) === strtolower($code) ||
				strtolower(substr($pref_lang, 0, 2)) === strtolower($code)
			) {
				$selected = true;
			}

			$options[] = [
				'label' => $language,
				'value' => $code,
				'calc' => '',
				'selected' => $selected
			];

		}
		
	}
  
	return $options;
}, 10, 2);



// Make dynamically generated language options valid during submission.
add_filter('ninja_forms_pre_validate_field_settings', function ($settings) {

	if (
		empty($settings['key']) ||
		! str_contains($settings['key'], 'language')
	) {
		return $settings;
	}

	/*
	 * Run the existing render-options filter so validation receives
	 * exactly the same options shown in the select field.
	 */
	$settings['options'] = apply_filters(
		'ninja_forms_render_options',
		$settings['options'] ?? [],
		$settings
	);

	return $settings;

}, 10, 1);





add_filter('ninja_forms_submit_data', 'nc_custom_ninja_forms_submit_data');
function nc_custom_ninja_forms_submit_data($form_data) {
	$form_id = $form_data['id'];

	foreach ($form_data[ 'fields' ] as $field_id => $field) {
		if (str_contains($field[ 'key' ], 'email')) {
			$field_value = strtolower($form_data['fields'][$field_id]['value']);

			//check if valid format
			if (!filter_var($field_value, FILTER_VALIDATE_EMAIL)) {
				$form_data['errors']['fields'][$field_id] = 'Invalid email format!';
			}

			$validator = EmailValidation\EmailValidatorFactory::create($field_value);
			$arrayValidator = $validator->getValidationResults()->asArray();

			if (
				in_array($form_id, [
					1,// exclude Contact form
					30,// exclude Discuss your app form
					27,// exclude Newsletter form
					33, // exclude Contact Issue form
					68,// exclude Events newsletter form
					72,// exclude Events lead collection form
					85,// exclude Hub announcements form
					95,// exclude Conference 2024 form
					96,// exclude call for proposals form for Conference 2024
					98,// exclude Test Newsletters form
					100,// exclude developer-webinar-registration
					113,// exclude Summit payment form
					116,// exclude Conference announcements form
					118, // exclude Partners newsletter form
					123, // exclude Summit awards form
					124, // exclude Conference 2025 form
					125, // exclude Call for proposals conference 2025
					127, // exclude Conference 2025 registration - with payment
					132, // exclude Generic Enterprise day (Paris, Hague, etc) - With payment
					134, // exclude Hub announcements mailing list form
					135, // exclude Call for proposals Summit
					136, // exclude Unsubscribe form
					137, // exclude form Submit Meetup idea
					138, // exclude form Attend to the meetup
					141, // exclude short Newsletter form
					143, // exclude Hub release form for webinars
					151 // exclude form for chinese webinars - template-webinar-registration-cn
				])
			) {

				//basic forms validation
				if (
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

				if (!$arrayValidator['valid_mx_records']) {
					$form_data['errors']['fields'][$field_id] = __('Please use a valid business email address. Did we make a mistake? <a href="/contact/" target="_blank" title="Contact us here">Contact us here</a>', 'nextcloud');
				}

			} elseif (in_array($form_id, [
				90 // testing form
			])) {
						
				if (!$arrayValidator['valid_mx_records']) {
					$form_data['errors']['fields'][$field_id] = __('The MX records are not valid.', 'nextcloud');
				}

				/*
				if( $arrayValidator['disposable_email_provider'] ){
					$form_data['errors']['fields'][$field_id] = __('Email is disposable.', 'nextcloud');
				}

				if( $arrayValidator['free_email_provider'] ){
					$form_data['errors']['fields'][$field_id] = __('The email is a free email provider', 'nextcloud');
				}
				$form_data['errors']['fields'][$field_id] = __('Please use a valid business email address. Did we make a mistake? <a href="/contact/" target="_blank" title="Contact us here">Contact us here</a>', 'nextcloud');
				*/

				if (Domain_Blocklist::is_blocked($field_value)) {
					$form_data['errors']['fields'][$field_id] = __('Please use a valid business email address. Did we make a mistake? <a href="/contact/" target="_blank" title="Contact us here">Contact us here</a>', 'nextcloud');
				}
						
						
				/*
				if(
					!$arrayValidator['valid_mx_records'] ||
					$arrayValidator['disposable_email_provider']
					// || !$arrayValidator['valid_host']
					)
				{
					$form_data['errors']['fields'][$field_id] = __('Please use a valid business email address. Did we make a mistake? <a href="/contact/" target="_blank" title="Contact us here">Contact us here</a>', 'nextcloud');
				}
				*/



			} else {
				//all checks enabled for all the other forms

				if (
					!$arrayValidator['valid_mx_records'] ||
					$arrayValidator['disposable_email_provider']
					|| $arrayValidator['free_email_provider']
					// || !$arrayValidator['valid_host']
					|| Domain_Blocklist::is_blocked($field_value)
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

	if (isset($convenience_cookie_set)) {
		$form_fields = [];
		foreach ($form_data[ 'fields' ] as $field_id => $field) {
		
			if (str_contains($field['key'], 'name') && !str_contains($field['key'], 'organization')) {
				$form_fields['nc_form_name'] = $field[ 'value' ];
			}

			if (str_contains($field['key'], 'email')) {
				$form_fields['nc_form_email'] = urlencode($field[ 'value' ]);
			}

			if (str_contains($field['key'], 'language')) {
				$form_fields['nc_form_lang'] = $field[ 'value' ];
			}

			if (str_contains($field['key'], 'phone')) {
				$form_fields['nc_form_phone'] = $field[ 'value' ];
			}
		}

		//save cookie with form fields for 30 days
		setcookie('nc_form_fields', base64_encode(json_encode($form_fields)), time() + (86400 * 30), '/');
	}


	return $form_data;
}


//pre-populating fields on display
//add_filter( 'ninja_forms_render_default_value', 'nc_change_nf_default_value', 10, 3 );
function nc_change_nf_default_value($default_value, $field_type, $field_settings) {
	if (isset($_COOKIE['nc_form_fields'])) {
		$nc_form_fields = json_decode(base64_decode($_COOKIE['nc_form_fields']), true);

		if (str_contains($field_settings['key'], 'name') && !str_contains($field_settings['key'], 'organization')) {
			if (isset($nc_form_fields['nc_form_name'])) {
				$default_value = $nc_form_fields['nc_form_name'];
			}
		}
		if (str_contains($field_settings['key'], 'email')) {
			if (isset($nc_form_fields['nc_form_email'])) {
				$default_value = urldecode($nc_form_fields['nc_form_email']);
			}
		}
		if (str_contains($field_settings['key'], 'phone')) {
			if (isset($nc_form_fields['nc_form_phone'])) {
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

	if ($settings['key'] == 'select_mailing_lists_checkboxes') {
		//echo "true!";
		foreach ($options as &$option) {
			
			if (isset($_GET['list'])) {

				if ($option[ 'value' ] == strip_tags($_GET['list'])) {
					$option[ 'selected' ] = true;
				}

			} elseif ($option[ 'label' ] == 'Nextcloud news') {
				$option[ 'selected' ] = true;
			}
		}
	}
  
	return $options;
}, 10, 2);



// Dynamically add additional privacy policy text under the default privacy policy, if present, with its required checkbox
add_action('wp_footer', function () {

	$show_extra = get_field('additional_privacy_policy', get_queried_object_id());
	$extra_text = get_field('additional_privacy_policy_text', get_queried_object_id());

	if (! $show_extra || ! $extra_text) {
		return;
	}
	?>
    <script>
    jQuery(document).on('nfFormReady', function () {

        const fieldID = 1778;
        const field = document.querySelector('#nf-field-' + fieldID + '-container');

        if (!field) return;

        // Show field
        field.classList.remove('hidden');
        field.style.display = 'block';

        // Change label
        const label = field.querySelector('.nf-field-label label');
        if (label) {
            label.innerHTML = <?php echo wp_json_encode(wp_kses_post($extra_text)); ?>;
        }

        // Native browser required
        /*
        const checkbox = field.querySelector('input[type="checkbox"]');
        if (checkbox) {
            checkbox.required = true;
            checkbox.setAttribute('required', 'required');
            checkbox.setAttribute('aria-required', 'true');
        }

        // Ninja Forms model required
        if (typeof nfRadio !== 'undefined') {
            const fieldModel = nfRadio.channel('fields').request('get:field', fieldID);

            if (fieldModel) {
                fieldModel.set('required', true);
            }
        }
        */

        //console.log('Dynamic privacy policy field is now visible + required');

    });
    </script>
    <?php
});




//add custom ninja forms processing web hook - used to save contacts to newsletter
add_action('nc_ninja_forms_processing_save_to_newsletter', 'nc_ninja_forms_processing_save_to_newsletter_callback');
function nc_ninja_forms_processing_save_to_newsletter_callback($form_data) {
	
	if (isset($form_data) && isset($form_data[ 'form_id' ])) {
		$form_id = $form_data[ 'form_id' ];
		$form_title = $form_data[ 'settings' ][ 'title' ];
	}
	
	$page_url = '';
	$form_fields = $form_data[ 'fields' ];
	$email = '';
	$name = '';
	$phone = '';
	$mailing_list_ids = [7647]; // default ID 7647
	$subscribed = false;
	$select_mailing_lists_checkboxes = [];

	if (isset($form_id)) {
		if (
			$form_id == 27 // Newsletter form
			|| $form_id == 98 // Newsletter form TEST
			|| $form_id == 68  // Events newsletter form
			|| $form_id == 66 // Get more information about event
			|| $form_id == 85 // Hub announcements form
			|| $form_id == 116 // Conference announcements form
			|| $form_id == 118  // Partners newsletter form
			|| $form_id == 126  // ED 2025 DK newsletter form
			|| $form_id == 134  // Hub announcements newsletter form
			|| $form_id == 141  // Short newsletter form
		) {
			$subscribed = true; // automatically set as accepted for these forms
		} else {
			$subscribed = false;
		}


		foreach ($form_fields as $field) {

			if (str_contains($field['key'], 'keep_me_informed') || str_contains($field['key'], 'newsletter')) {
				// means user selected the newsletters checkbox
				if ($field[ 'value' ] != 0) {
					$subscribed = true;
				}
			}

			if (str_contains($field['key'], 'name')) {
				$name = $field[ 'value' ];
			}

			if (
				str_contains($field['key'], 'email')
			) {
				$email = $field[ 'value' ];
			}

			if (
				str_contains($field['key'], 'url')
			) {
				$page_url = $field[ 'value' ];
			}

			//used for newsletter form and conference LP form
			if ($field[ 'key' ] == 'select_mailing_lists_checkboxes') {
				if (is_array($field['value']) && ! empty($field['value'])) {
					$mailing_list_ids = array_merge($mailing_list_ids, $field['value']); //multiple
					$subscribed = true;
				}
			}

				
			//add to custom newsletter if brings kids
			if ($field[ 'key' ] == 'kids_age') {
				if ($field[ 'value' ] != '') {
					//add mailing list "Conference 2026 - Attendees with kids"
					$mailing_list_ids[] = 17711;
				}
					
			}


			if (str_contains($field['key'], 'mailing_list_id')) {
				$mailing_list_ids[] = $field[ 'value' ];
			}
 

		}


	}

	// Odoo expects integer list IDs - drop empty values (e.g. empty hidden mailing_list_id fields) and duplicates
	$mailing_list_ids = array_values(array_unique(array_filter(array_map('intval', (array)$mailing_list_ids))));

	if ($subscribed && is_email($email)) {

		[$url, $db, $username, $password] = getOdooConnection();
		$uid = ODOO_UID;

		$models = RipcordLogger::client("$url/xmlrpc/2/object", [
			'form_id' => $form_id ?? null,
			'form_name' => $form_title ?? null,
			'page_url' => $page_url,
		]);
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


		// On failure Odoo returns a fault array (faultCode/faultString) instead of the new record ID
		if (is_int($sub_id) && $sub_id > 0) {
			$args = [[['contact_id', '=', $sub_id]]];
			$kwargs = [
				'fields' => ['contact_id', 'list_id', 'opt_out'],
				'limit' => 10
			];
			$subscriptions = $models->execute_kw(
				$db, $uid, $password,
				'mailing.subscription', 'search_read',
				$args, $kwargs
			);

			$subscription_list_ids = [];
			$subscription_ids = [];

			if (! is_array($subscriptions) || isset($subscriptions['faultCode'])) {
				$subscriptions = [];
			}

			//foreach subscription, set Opt out field to true
			foreach ($subscriptions as $subscription) {
				if (! is_array($subscription) || ! isset($subscription['id'])) {
					continue;
				}
				$subscription_list_ids[] = is_array($subscription['list_id']) ? $subscription['list_id'][0] : $subscription['list_id'];
				$subscription_ids[] = $subscription['id'];

				// 'write' instead of 'update': update returns None, which Odoo can't send back over XML-RPC (fault)
				$update_sub = $models->execute_kw($db, $uid, $password,
					'mailing.subscription', 'write',
					[[$subscription['id']], [
						'opt_out' => true
					]]
				);

					
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
			if ($lang == 'en') {
				$curr_lang = '';
			} else {
				$curr_lang = apply_filters('wpml_current_language', null) . '/';
			}
			

			$to = $email;
			$subject = __('Please confirm your subscription', 'nextcloud');
			$message = __('Hello', 'nextcloud') . ',<br />';

			if (is_array($subscription_ids)) {
				$joined = implode(',', $subscription_ids);
			} else {
				$joined = (string)$subscription_ids; // fallback if it's a string or null
			}

			$reg_link = 'https://nextcloud.com/' . $curr_lang . 'manage-subscription/?action=subscribe&sub=' . $sub_id . '&subs_ids=' . $joined;
			$string_confirm = sprintf('Please confirm your mailing subscription by clicking <a href="%s">here</a>.', $reg_link);
			$message .= __($string_confirm, 'nextcloud');


			$message .= '<br />' . __('Or by accessing this link in your URL bar:', 'nextcloud') . $reg_link . '<br />
            <br />
            ' . __('If this wasn\'t requested by you, please ignore this email.', 'nextcloud') . '
            <br />
            <br />
            ' . __('Kind regards,', 'nextcloud') . '
            <br />
            ' . __('Nextcloud team.', 'nextcloud');

			// Send the email
			$response = wp_mail($to, $subject, $message, $headers);

		}

	}

	$form_settings = $form_data[ 'settings' ];
	$form_title = $form_data[ 'settings' ][ 'title' ];
}



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
			$now_timestamp = new DateTime('now');


			$interval_days = 0;
			if (isset($event_end_datetime)) {
				$event_end_datetime_timestamp = new DateTime($event_end_datetime);
				$interval = $now_timestamp->diff($event_end_datetime_timestamp);
				$interval_days = $interval->format('%R%a');
			}


			$activity_id = get_field('activity_id');
			if (isset($activity_id) && str_contains($activity_id, '-apt-')) {
				$activity_id = str_replace('-apt-', '-', $activity_id);
			}

			$title = get_the_title();
			if (get_field('event_short_title')) {
				$title = get_field('event_short_title');
			}

			/*
			if(isset($interval_days)) {
				if($interval_days >= 0 ) {
					//$events_list[$activity_id]=$title;
					$events_list[intval($interval_days)] = $title;
				}

				if($interval_days < 30 ) {
					//$events_list[$activity_id]=$title;
					$events_list[intval($interval_days)] = $title;
				}
			}
			*/
			
			if (isset($interval_days)) {
				if (abs($interval_days) >= 0 && abs($interval_days) < 30) {
					$events_list[$activity_id] = $title;
				}
			}
			


		}
	} else {
		// no posts found
	}
	/* Restore original Post Data */
	wp_reset_postdata();

	if ($settings['key'] == 'event_name_1685534754887') {
		//$options = []; //don't reset the options, so it takes also the manual values

		foreach ($events_list as $id => $event_name) {
			$options[] = [
				'label' => $event_name,
				'value' => $id,
				'calc' => 0
			];
		}
	}
  

	return $options;
}, 10, 2);




//register event attendees and send email for double-optin AFTER payment is successful - NOT WORKING; DISABLED FOR NOW
//add_action( 'ninja_forms_checkout_success', 'nc_ninja_forms_processing_save_attendees_payment_callback', 10, 2 );
function nc_ninja_forms_processing_save_attendees_payment_callback($form_id, $form_data) {

	$form_settings = $form_data[ 'settings' ];
	$form_title = $form_data[ 'settings' ][ 'title' ];


	if ($form_id != 127) {
		return;
	}  //  // Only for conference form - paypal integrated


	$name = '';
	$email = '';
	$phone = '';
	$company_name = '';
	$number_of_tickets = '';
	$ticket_type = '';
	$invoice_address = '';
	$dietary_requirements = '';
	$discount_code = '';
	$odoo_event_id = ''; // default Nextcloud Summit 2025

	//conference
	$when_do_you_plan_to_attend = '';
	$food_preferences = '';
	$how_do_you_contribute = '';
	$page_url = '';

	$form_fields = $form_data[ 'fields' ];
	foreach ($form_fields as $field) {

		if (str_contains($field['key'], 'odoo_event_id')) {
			$odoo_event_id = $field[ 'value' ];
		}
		if ($field['key'] == 'name') {
			$name = $field[ 'value' ];
		}
		if ($field['key'] == 'email') {
			$email = $field[ 'value' ];
		}
		if ($field['key'] == 'phone') {
			$phone = $field[ 'value' ];
		}

		if (str_contains($field['key'], 'url')) {
			$page_url = $field[ 'value' ];
		}

		if (str_contains($field['key'], 'number_of_tickets')) {
			$number_of_tickets = $field[ 'value' ];
		}
		if (str_contains($field['key'], 'ticket_type')) {
			$ticket_type = $field[ 'value' ];
		}

		if (str_contains($field['key'], 'company_name')) {
			$company_name = $field[ 'value' ];
		}
		if (str_contains($field['key'], 'invoice_address')) {
			$invoice_address .= $field[ 'value' ];
		}

		//street
		if (str_contains($field['key'], 'street')) {
			$invoice_address .= $field[ 'value' ];
			$invoice_address .= ', ';
		}
		//zip
		if (str_contains($field['key'], 'zip')) {
			$invoice_address .= $field[ 'value' ];
			$invoice_address .= ' ';
		}
		//city
		if (str_contains($field['key'], 'city')) {
			$invoice_address .= $field[ 'value' ];
			$invoice_address .= ', ';
		}
		//country
		if (str_contains($field['key'], 'country')) {
			$invoice_address .= $field[ 'value' ];
		}

		//country
		if (str_contains($field['key'], 'vat_number')) {
			$invoice_address .= ' - ';
			$invoice_address .= 'VAT: ' . $field[ 'value' ];
		}

		if (str_contains($field['key'], 'dietary_requirements')) {
			$dietary_requirements = $field[ 'value' ];
		}
		if (str_contains($field['key'], 'discount_code')) {
			$discount_code = $field[ 'value' ];
		}


		if (str_contains($field['key'], 'when_do_you_plan_to_attend')) {
			$when_do_you_plan_to_attend = $field[ 'value' ]; // answer_18
		}
		if (str_contains($field['key'], 'food_preferences')) {
			$food_preferences = $field[ 'value' ]; // answer_22
		}
		if (str_contains($field['key'], 'how_do_you_contribute')) {
			$how_do_you_contribute = $field[ 'value' ]; // answer_26
		}



	}

	[$url, $db, $username, $password] = getOdooConnection();

	$uid = ODOO_UID;
	$models = RipcordLogger::client("$url/xmlrpc/2/object");

	//Step 1: add attendee
	$attendee_id = $models->execute_kw($db, $uid, $password,
		'event.registration', 'create',
		[
			[
				'event_id' => $odoo_event_id,
				'name' => $name,
				'email' => $email,
				//'phone' => $phone, // weirdly enough, not working for some reason
				'x_studio_attendee_company' => $company_name,
				//'state' => 'draft'
			]
		]
	);


	if ($attendee_id) {
		//Step 2: add phone number
		$models->execute_kw(
			$db, $uid, $password,
			'event.registration', 'write',
			[[$attendee_id], ['phone' => $phone]]
		);


		//Step 3:  add answers
		$answers = [];
		if ($number_of_tickets != '') {
			$answers[] = ['question_id' => 53, 'value_text_box' => $number_of_tickets];
		}
		if ($ticket_type != '') {
			$answers[] = ['question_id' => 54, 'value_text_box' => $ticket_type];
		}
		//add invoice details
		$answers[] = ['question_id' => 50, 'value_text_box' => $invoice_address];
		if ($dietary_requirements != '') {
			$answers[] = ['question_id' => 51, 'value_text_box' => $dietary_requirements];
		}
		if ($discount_code != '') {
			$answers[] = ['question_id' => 52, 'value_text_box' => $discount_code];
		}


		if ($when_do_you_plan_to_attend != '') {
			$answers[] = ['question_id' => 68, 'value_text_box' => $when_do_you_plan_to_attend];
		}
		if ($food_preferences != '') {
			$answers[] = ['question_id' => 69, 'value_text_box' => $food_preferences];
		}
		if ($how_do_you_contribute != '') {
			$answers[] = ['question_id' => 70, 'value_text_box' => $how_do_you_contribute];
		}




		foreach ($answers as $answer) {
			$answer['registration_id'] = $attendee_id; // Link answer to attendee
			$answer_id = $models->execute_kw(
				$db, $uid, $password,
				'event.registration.answer', 'create',
				[$answer]
			);
		}



		// Step 4: set the attendee to draft - unconfirmed -> double opt-in
		$models->execute_kw(
			$db, $uid, $password,
			'event.registration', 'write',
			[[$attendee_id], ['state' => 'draft']]
		);





		if ($odoo_event_id != 32) { //if the LP is Hague, then don't send the double opt-in email
				

			// send the email for confirmation
			$headers = [];
			// Override the default 'From' address if 'Force Sender Email' is not enabled
			$headers['From'] = 'Nextcloud <no-reply@e.nextcloud.com>';
			// Send the message as HTML

			add_filter('wp_mail_content_type', function ($content_type) {
				return 'text/html';
			});
			$headers['Content-Type'] = 'text/html; charset=ISO-8859-1';

			$lang = apply_filters('wpml_current_language', null);
			if ($lang == 'en') {
				$curr_lang = '';
			} else {
				$curr_lang = apply_filters('wpml_current_language', null) . '/';
			}
				
			$to = $email;
			$subject = __('Please confirm your registration', 'nextcloud');
			$reg_link = 'https://nextcloud.com/' . $curr_lang . 'manage-registration/?action=register&aids=' . $attendee_id;
			$message = __('Hello', 'nextcloud') . ',<br />';

				
			$message .= __('Please confirm your event registration by clicking', 'nextcloud') . "
                <a href='" . $reg_link . "'>" . __('here', 'nextcloud') . '</a>.';
				
			$message .= '<br />' . __('Or by accessing this link in your URL bar:', 'nextcloud') . $reg_link . '<br />
                <br />
                ' . __('If this wasn\'t requested by you, please ignore this email.', 'nextcloud') . '
                <br />
                <br />
                ' . __('Kind regards,', 'nextcloud') . '
                <br />
                ' . __('Nextcloud team.', 'nextcloud');
			// Send the email
			$response = wp_mail($to, $subject, $message, $headers);

		}
	}
	// } //endif form_id = 127
}


//custom ninja forms processing web hook to add conference attendees
add_action('nc_ninja_forms_processing_save_attendees', 'nc_ninja_forms_processing_save_attendees_callback');
function nc_ninja_forms_processing_save_attendees_callback($form_data) {

	if (isset($form_data) && isset($form_data[ 'form_id' ])) {
		$form_id = $form_data[ 'form_id' ];
		$form_title = $form_data[ 'settings' ][ 'title' ];
	}

	$name = '';
	$email = '';
	$phone = '';
	$company_name = '';
	$number_of_tickets = '';
	$ticket_type = '';
	$invoice_address = '';
	$dietary_requirements = '';
	$discount_code = '';
	$discount_percentage = 0;
	$odoo_event_id = ''; // default Nextcloud Summit 2025
	$page_url = '';

	//conference
	$when_do_you_plan_to_attend = '';
	$food_preferences = '';
	$how_do_you_contribute = '';
	$kids_age = '';


	$form_fields = $form_data[ 'fields' ];
	foreach ($form_fields as $field) {

		if (str_contains($field['key'], 'odoo_event_id')) {
			$odoo_event_id = absint($field[ 'value' ]);
		}
		if ($field['key'] == 'name') {
			$name = $field[ 'value' ];
		}
		if ($field['key'] == 'email') {
			$email = $field[ 'value' ];
		}
		if ($field['key'] == 'phone') {
			$phone = $field[ 'value' ];
		}
		if (str_contains($field['key'], 'url')) {
			$page_url = $field[ 'value' ];
		}

		if (str_contains($field['key'], 'number_of_tickets')) {
			$number_of_tickets = $field[ 'value' ];
		}
		if (str_contains($field['key'], 'ticket_type')) {
			$ticket_type = $field[ 'value' ];
		}

		if (str_contains($field['key'], 'discount_percentage')) {
			// free tickets
			$discount_percentage = $field[ 'value' ];
		}

		if (str_contains($field['key'], 'company_name')) {
			$company_name = $field[ 'value' ];
		}
		if (str_contains($field['key'], 'invoice_address')) {
			$invoice_address .= $field[ 'value' ];
		}

		//street
		if (str_contains($field['key'], 'street')) {
			$invoice_address .= $field[ 'value' ];
			$invoice_address .= ', ';
		}
		//zip
		if (str_contains($field['key'], 'zip')) {
			$invoice_address .= $field[ 'value' ];
			$invoice_address .= ' ';
		}
		//city
		if (str_contains($field['key'], 'city')) {
			$invoice_address .= $field[ 'value' ];
			$invoice_address .= ', ';
		}
		//country
		if (str_contains($field['key'], 'country')) {
			$invoice_address .= $field[ 'value' ];
		}

		//country
		if (str_contains($field['key'], 'vat_number')) {
			$invoice_address .= ' - ';
			$invoice_address .= 'VAT: ' . $field[ 'value' ];
		}

		if (str_contains($field['key'], 'dietary_requirements')) {
			$dietary_requirements = $field[ 'value' ];
		}
		if (str_contains($field['key'], 'discount_code')) {
			$discount_code = $field[ 'value' ];
		}


		if (str_contains($field['key'], 'when_do_you_plan_to_attend')) {
			$when_do_you_plan_to_attend = $field[ 'value' ]; // answer_18
		}
		if (str_contains($field['key'], 'food_preferences')) {
			$food_preferences = $field[ 'value' ]; // answer_22
		}
		if (str_contains($field['key'], 'how_do_you_contribute')) {
			$how_do_you_contribute = $field[ 'value' ]; // answer_26
		}

		//kids age
		if ($field['key'] == 'kids_age') {
			if ($field[ 'value' ] != '') {
				$kids_age = $field[ 'value' ];
			}
		}




	}
		
	[$url, $db, $username, $password] = getOdooConnection();

	//authenticate
	$uid = ODOO_UID;
	$models = RipcordLogger::client("$url/xmlrpc/2/object");


	//Step 1: add attendee
	$attendee_id = $models->execute_kw($db, $uid, $password,
		'event.registration', 'create',
		[
			[
				'event_id' => $odoo_event_id,
				'name' => $name,
				'email' => $email,
				'phone' => $phone,
				'x_studio_attendee_company' => $company_name
			]
		]
	);


	if ($attendee_id) {


		//Step 2: add phone number
		/*
		$models->execute_kw(
			$db, $uid, $password,
			'event.registration', 'write',
			array(array($attendee_id), array('phone' => $phone))
		);
		*/


		//Step 3:  add answers
		$answers = [];
		if ($number_of_tickets != '') {
			$answers[] = ['question_id' => 53, 'value_text_box' => $number_of_tickets];
		}
		if ($ticket_type != '') {
			$answers[] = ['question_id' => 54, 'value_text_box' => $ticket_type];
		}
		//add invoice details
		$answers[] = ['question_id' => 50, 'value_text_box' => $invoice_address];
		if ($dietary_requirements != '') {
			$answers[] = ['question_id' => 51, 'value_text_box' => $dietary_requirements];
		}
		if ($discount_code != '') {
			$answers[] = ['question_id' => 52, 'value_text_box' => $discount_code];
		}


		if ($when_do_you_plan_to_attend != '') {
			$answers[] = ['question_id' => 68, 'value_text_box' => $when_do_you_plan_to_attend];
		}
		if ($food_preferences != '') {
			$answers[] = ['question_id' => 69, 'value_text_box' => $food_preferences];
		}
		if ($how_do_you_contribute != '') {
			$answers[] = ['question_id' => 70, 'value_text_box' => $how_do_you_contribute];
		}

		if ($kids_age != '') {
			$answers[] = ['question_id' => 270, 'value_text_box' => $kids_age];
		}

		foreach ($answers as $answer) {
			$answer['registration_id'] = $attendee_id; // Link answer to attendee
			$answer_id = $models->execute_kw(
				$db, $uid, $password,
				'event.registration.answer', 'create',
				[$answer]
			);
		}



		// Step 4: set the attendee to draft - unconfirmed -> double opt-in
		if ($discount_percentage == 100) {
			//if free tickets
			$models->execute_kw(
				$db, $uid, $password,
				'event.registration', 'write',
				[[$attendee_id], ['state' => 'draft']]
			);
		}





		if ($discount_percentage == 100) {
				
			// send the email for confirmation
			$headers = [];
			// Override the default 'From' address if 'Force Sender Email' is not enabled
			$headers['From'] = 'Nextcloud <no-reply@e.nextcloud.com>';
			// Send the message as HTML

			add_filter('wp_mail_content_type', function ($content_type) {
				return 'text/html';
			});
			$headers['Content-Type'] = 'text/html; charset=ISO-8859-1';

			$lang = apply_filters('wpml_current_language', null);
			if ($lang == 'en') {
				$curr_lang = '';
			} else {
				$curr_lang = apply_filters('wpml_current_language', null) . '/';
			}
				
			$to = $email;
			$subject = __('Please confirm your registration', 'nextcloud');
			$reg_link = 'https://nextcloud.com/' . $curr_lang . 'manage-registration/?action=register&aids=' . $attendee_id;
			$message = __('Hello', 'nextcloud') . ',<br />';

				
			$message .= __('Please confirm your event registration by clicking', 'nextcloud') . "
                <a href='" . $reg_link . "'>" . __('here', 'nextcloud') . '</a>.';
				
			$message .= '<br />' . __('Or by accessing this link in your URL bar:', 'nextcloud') . $reg_link . '<br />
                <br />
                ' . __('If this wasn\'t requested by you, please ignore this email.', 'nextcloud') . '
                <br />
                <br />
                ' . __('Kind regards,', 'nextcloud') . '
                <br />
                ' . __('Nextcloud team.', 'nextcloud');
			// Send the email
			$response = wp_mail($to, $subject, $message, $headers);
		}

	}
		

	$form_settings = $form_data[ 'settings' ];
	$form_title = $form_data[ 'settings' ][ 'title' ];
}



//populate Enterprise Day downloads form select with all presentations
add_filter('ninja_forms_render_options', function ($options, $settings) {
	$ed_presentations = [
	];

	
	if (have_rows('enterprise_day_slides', 'option')):
		while (have_rows('enterprise_day_slides', 'option')) :
			the_row();

			$ed_presentations[] = [
				'title' => get_sub_field('presentation_title'),
				'file_url' => get_sub_field('presentation_pdf'),
				'interests' => get_sub_field('presentation_interests')
			];

		endwhile;
	endif;
	
	
	//prefill the slides list select field
	if ($settings['key'] == 'select_slides_you_want_to_download_1714669386359') {
		//$options = []; //reset the options
		foreach ($ed_presentations as $id => $ed_presentation) {
			$options[] = [
				'label' => $ed_presentation['title'],
				'value' => preg_replace('/[ ,]/', '-', $ed_presentation['title']) //replaces spaces and commas with dashes
			];
		}
	}

	//prefill the slides URLs select field (hidden)
	if ($settings['key'] == 'all_slides_urls_1715151284630') {
		//$options = []; //reset the options
		foreach ($ed_presentations as $id => $ed_presentation) {
			$options[] = [
				'label' => $ed_presentation['file_url'],
				'value' => $ed_presentation['file_url']
			];
		}
	}

	//prefill the slides interests select field (hidden)
	if ($settings['key'] == 'all_slides_interests_1715151300664') {
		//$options = []; //reset the options
		foreach ($ed_presentations as $id => $ed_presentation) {
			$options[] = [
				'label' => $ed_presentation['interests'],
				'value' => str_replace(' ', '', $ed_presentation['interests']) //remove spaces
			];
		}
	}


  
	return $options;
}, 10, 2);





//custom ninja forms processing web hook to add conference attendees
add_action('nc_ninja_forms_processing_save_multiple_attendees', 'nc_ninja_forms_processing_save_multiple_attendees_callback');
function nc_ninja_forms_processing_save_multiple_attendees_callback($form_data) {

	if (isset($form_data) && isset($form_data[ 'form_id' ])) {
		$form_id = $form_data[ 'form_id' ];
		$form_title = $form_data[ 'settings' ][ 'title' ];
	}

	$name = '';
	$email = '';
	$phone = '';
	$company_name = '';
	$number_of_tickets = '';
	$ticket_type = '';
	$invoice_address = '';
	$dietary_requirements = '';
	$discount_code = '';
	$odoo_event_id = '';
	$discount_percentage = 0;
	$additional_attendees = [];
	$page_url = '';

	$form_fields = $form_data[ 'fields' ];
	foreach ($form_fields as $field) {

		if (str_contains($field['key'], 'odoo_event_id')) {
			$odoo_event_id = absint($field[ 'value' ]);
		}

		if ($field['key'] == 'name') {
			$name = $field['value'];
		}

		if ($field['key'] == 'email') {
			$email = $field[ 'value' ];
		}

		if ($field['key'] == 'phone') {
			$phone = $field[ 'value' ];
		}
		if (str_contains($field['key'], 'url')) {
			$page_url = $field[ 'value' ];
		}

		if (str_contains($field['key'], 'number_of_tickets')) {
			$number_of_tickets = $field[ 'value' ];
		}
		if (str_contains($field['key'], 'ticket_type')) {
			$ticket_type = $field[ 'value' ];
		}

		if (str_contains($field['key'], 'discount_percentage')) {
			// free tickets
			$discount_percentage = $field[ 'value' ];
		}

		if (str_contains($field['key'], 'additional_attendees')) {
			$additional_attendees = $field[ 'value' ];
		}

		if (str_contains($field['key'], 'company_name')) {
			$company_name = $field[ 'value' ];
		}
		if (str_contains($field['key'], 'invoice_address')) {
			$invoice_address .= $field[ 'value' ];
		}


		//street
		if (str_contains($field['key'], 'street')) {
			$invoice_address .= $field[ 'value' ];
			$invoice_address .= ', ';
		}
		//zip
		if (str_contains($field['key'], 'zip')) {
			$invoice_address .= $field[ 'value' ];
			$invoice_address .= ' ';
		}
		//city
		if (str_contains($field['key'], 'city')) {
			$invoice_address .= $field[ 'value' ];
			$invoice_address .= ', ';
		}
		//country
		if (str_contains($field['key'], 'country')) {
			$invoice_address .= $field[ 'value' ];
		}

		//country
		if (str_contains($field['key'], 'vat_number')) {
			if ($field[ 'value' ] != '') {
				$invoice_address .= ' - ';
				$invoice_address .= 'VAT: ' . $field[ 'value' ];
			}
		}

		if (str_contains($field['key'], 'dietary_requirements')) {
			$dietary_requirements = $field[ 'value' ];
		}
		if (str_contains($field['key'], 'discount_code')) {
			$discount_code = $field[ 'value' ];
		}

	}

	[$url, $db, $username, $password] = getOdooConnection();

	//authenticate
	$uid = ODOO_UID;
	$models = RipcordLogger::client("$url/xmlrpc/2/object");


	//Step 1: add attendee(s)
	$attendee_ids = [];
	//add main attendee
	$attendee_id = $models->execute_kw($db, $uid, $password,
		'event.registration', 'create',
		[
			[
				'event_id' => $odoo_event_id,
				'name' => $name,
				'email' => $email,
				'x_studio_attendee_company' => $company_name,
				'phone' => $phone
			]
		]
	);
	$attendee_ids[] = $attendee_id;


	if ($additional_attendees && $number_of_tickets > 1) {
		foreach ($additional_attendees as $additional_attendee) {
			$att_name = $additional_attendee['value'];

			$additional_attendee_id = $models->execute_kw($db, $uid, $password,
				'event.registration', 'create',
				[
					[
						'event_id' => $odoo_event_id,
						'name' => $att_name,
						'email' => $email,
						'x_studio_attendee_company' => $company_name,
						'phone' => $phone
					]
				]
			);
			$attendee_ids[] = $additional_attendee_id;
			
		}
	}

		


	if ($attendee_ids) {
		//Step 2: add phone number - NOT NECESSARY ANYMORE??
		/*
		if($phone) {
			foreach($attendee_ids as $attendee_id) {
				$models->execute_kw(
					$db, $uid, $password,
					'event.registration', 'write',
					array(array($attendee_id), array('phone' => $phone))
				);
			}
		}
		*/
			
			


		//Step 3:  add answers for all attendees
		$answers = [];
		if ($number_of_tickets != '') {
			$answers[] = ['question_id' => 53, 'value_text_box' => $number_of_tickets];
		}
		if ($ticket_type != '') {
			$answers[] = ['question_id' => 54, 'value_text_box' => $ticket_type];
		}
			
		//add invoice address
		$answers[] = ['question_id' => 50, 'value_text_box' => $invoice_address];
			
		// if($dietary_requirements != '') {
		//     $answers[] = array('question_id' => 51, 'value_text_box' => json_encode($additional_attendees));
		// }
			
		if ($discount_code != '') {
			$answers[] = ['question_id' => 52, 'value_text_box' => $discount_code];
		}

		foreach ($attendee_ids as $attendee_id) {
			foreach ($answers as $answer) {
				$answer['registration_id'] = $attendee_id; // Link answer to attendee
				$answer_id = $models->execute_kw(
					$db, $uid, $password,
					'event.registration.answer', 'create',
					[$answer]
				);
			}
		}
			
  
		// Step 4: set the attendee to draft - unconfirmed -> double opt-in
		if ($discount_percentage == 100) {
			//if free tickets
			foreach ($attendee_ids as $attendee_id) {
				$models->execute_kw(
					$db, $uid, $password,
					'event.registration', 'write',
					[[$attendee_id], ['state' => 'draft']]
				);
			}
		}


		if ($discount_percentage == 100) {
			// send the email for confirmation
			$headers = [];
			// Override the default 'From' address if 'Force Sender Email' is not enabled
			$headers['From'] = 'Nextcloud <no-reply@e.nextcloud.com>';
			// Send the message as HTML
			add_filter('wp_mail_content_type', function ($content_type) {
				return 'text/html';
			});
			$headers['Content-Type'] = 'text/html; charset=ISO-8859-1';

			//default
			$curr_lang = '';

			$lang = apply_filters('wpml_current_language', null);
			if ($lang == 'en') {
				$curr_lang = '';
			} else {
				$curr_lang = apply_filters('wpml_current_language', null) . '/';
			}
			$to = $email;

			$attendee_ids_as_text = '';
			if (is_array($attendee_ids)) {
				$attendee_ids_as_text = implode(',', $attendee_ids);
			}
			$subject = __('Please confirm your registration', 'nextcloud');
			$reg_link = 'https://nextcloud.com/' . $curr_lang . 'manage-registration/?action=register&aids=' . $attendee_ids_as_text;

				
			$message = __('Hello', 'nextcloud') . ',<br />';
			$message .= sprintf(
				__('Please confirm your event registration by clicking %s.', 'nextcloud'),
				"<a href='" . esc_url($reg_link) . "'>" . __('here', 'nextcloud') . '</a>'
			);

			$message .= '<br />
                ' . __('Or by accessing this link in your URL bar:', 'nextcloud') . $reg_link . '<br />
                <br />
                ' . __('If this wasn\'t requested by you, please ignore this email.', 'nextcloud') . '
                <br />
                <br />
                ' . __('Kind regards,', 'nextcloud') . '
                <br />
                ' . __('Nextcloud team.', 'nextcloud');

			// Send the email
			$response = wp_mail($to, $subject, $message, $headers);
		}

	}


	$form_settings = $form_data[ 'settings' ];
	$form_title = $form_data[ 'settings' ][ 'title' ];
}

//register custom WPML string
do_action(
	'wpml_register_single_string',
	'nextcloud',
	'Please confirm your event registration by clicking %s.',
	'Please confirm your event registration by clicking %s.'
);




//temporary test to get questions and answers for a specific event attendee
function odoo_attendee_questions_shortcode() {
	ob_start();

	// Hardcoded Odoo attendee / event.registration ID.
	$attendee_id = 65819;

	// Odoo connection details.
	[$url, $db, $username, $password] = getOdooConnection();

	$uid = ODOO_UID;

	if (!$uid) {
		return '<p>Failed to authenticate with Odoo.</p>';
	}

	try {
		// Connect to the Odoo object endpoint.
		$models = RipcordLogger::client("$url/xmlrpc/2/object");

		$attendees = $models->execute_kw(
			$db,
			$uid,
			$password,
			'event.registration',
			'search_read',
			[
				[
					['id', '=', $attendee_id],
				],
			],
			[
				'fields' => [
					'id',
					'name',
					'email',
					'event_id',
				],
				'limit' => 1,
			]
		);

		if (empty($attendees)) {
			return sprintf(
				'<p>No attendee found with ID %d.</p>',
				$attendee_id
			);
		}

		$attendee = $attendees[0];


		$answer_fields = $models->execute_kw(
			$db,
			$uid,
			$password,
			'event.registration.answer',
			'fields_get',
			[],
			[
				'attributes' => ['string', 'type'],
			]
		);

		$fields_to_read = [
			'id',
			'question_id',
			'value_answer_id',
		];

		if (isset($answer_fields['value_text_box'])) {
			$fields_to_read[] = 'value_text_box';
		}

		if (isset($answer_fields['value_text'])) {
			$fields_to_read[] = 'value_text';
		}


		$answers = $models->execute_kw(
			$db,
			$uid,
			$password,
			'event.registration.answer',
			'search_read',
			[
				[
					['registration_id', '=', $attendee_id],
				],
			],
			[
				'fields' => $fields_to_read,
				'order' => 'id ASC',
			]
		);

		$attendee_name = !empty($attendee['name'])
			? $attendee['name']
			: 'Unknown attendee';

		$event_name = (
			!empty($attendee['event_id']) &&
			is_array($attendee['event_id'])
		)
			? $attendee['event_id'][1]
			: 'Unknown event';

		?>
        <div class="odoo-attendee-questions">

            <h3>
                <?php echo esc_html($attendee_name); ?>
            </h3>

            <p>
                <strong>Event:</strong>
                <?php echo esc_html($event_name); ?>
            </p>

            <?php if (!empty($attendee['email'])) : ?>
                <p>
                    <strong>Email:</strong>
                    <?php echo esc_html($attendee['email']); ?>
                </p>
            <?php endif; ?>

            <?php if (empty($answers)) : ?>

                <p>No question answers were found for this attendee.</p>

            <?php else : ?>

                <dl class="odoo-attendee-answer-list">

                    <?php foreach ($answers as $answer) : ?>

                        <?php
						$question_id = (
							!empty($answer['question_id']) &&
							is_array($answer['question_id'])
						)
							? (int)$answer['question_id'][0]
							: 0;

                    	$question = (
                    		!empty($answer['question_id']) &&
                    		is_array($answer['question_id'])
                    	)
                    		? $answer['question_id'][1]
                    		: 'Unknown question';

                    	$answer_value = '';

                    	// Multiple-choice / predefined answer.
                    	if (
                    		!empty($answer['value_answer_id']) &&
                    		is_array($answer['value_answer_id'])
                    	) {
                    		$answer_value = $answer['value_answer_id'][1];
                    	}

                    	// Free-text answer used by several Odoo versions.
                    	if (
                    		$answer_value === '' &&
                    		!empty($answer['value_text_box'])
                    	) {
                    		$answer_value = $answer['value_text_box'];
                    	}

                    	// Alternative free-text field.
                    	if (
                    		$answer_value === '' &&
                    		!empty($answer['value_text'])
                    	) {
                    		$answer_value = $answer['value_text'];
                    	}

                    	if ($answer_value === '') {
                    		$answer_value = 'No answer';
                    	}
                    	?>
                        <dt>
                            <strong>
                                <?php echo esc_html($question); ?>
                            </strong>

                            <?php if ($question_id) : ?>
                                <small>
                                    Question ID: <?php echo esc_html($question_id); ?>
                                </small>
                            <?php endif; ?>
                        </dt>

                        <dd>
                            <?php echo nl2br(esc_html($answer_value)); ?>
                        </dd>

                    <?php endforeach; ?>

                </dl>

            <?php endif; ?>

        </div>
        <?php

	} catch (Throwable $e) {

		error_log(
			'Odoo attendee questions error: ' . $e->getMessage()
		);

		echo '<p>Unable to retrieve the attendee questions from Odoo.</p>';

		// Temporarily enable this during debugging:
		// echo '<pre>' . esc_html($e->getMessage()) . '</pre>';
	}

	return ob_get_clean();
}

add_shortcode(
	'odoo_attendee_questions',
	'odoo_attendee_questions_shortcode'
);




//custom ninja forms processing web hook to add conference attendees
add_action('nc_ninja_forms_processing_save_multiple_attendees_debug', 'nc_ninja_forms_processing_save_multiple_attendees_debug_callback');
function nc_ninja_forms_processing_save_multiple_attendees_debug_callback($form_data) {
	if (isset($form_data) && isset($form_data[ 'form_id' ])) {
		$form_id = $form_data[ 'form_id' ];
		$form_title = $form_data[ 'settings' ][ 'title' ];
	}

	$name = '';
	$email = '';
	$phone = '';
	$company_name = '';
	$number_of_tickets = '';
	$ticket_type = '';
	$invoice_address = '';
	$dietary_requirements = '';
	$discount_code = '';
	$odoo_event_id = '';
	$discount_percentage = 0;
	$additional_attendees = [];
	$page_url = '';

	$form_fields = $form_data[ 'fields' ];
	foreach ($form_fields as $field) {

		if (str_contains($field['key'], 'odoo_event_id')) {
			$odoo_event_id = absint($field[ 'value' ]);
		}
		if ($field['key'] == 'name') {
			$name = $field[ 'value' ];
		}
		if ($field['key'] == 'email') {
			$email = $field[ 'value' ];
		}
		if ($field['key'] == 'phone') {
			$phone = $field[ 'value' ];
		}
		if (str_contains($field['key'], 'url')) {
			$page_url = $field[ 'value' ];
		}

		if (str_contains($field['key'], 'number_of_tickets')) {
			$number_of_tickets = $field[ 'value' ];
		}
		if (str_contains($field['key'], 'ticket_type')) {
			$ticket_type = $field[ 'value' ];
		}

		if (str_contains($field['key'], 'discount_percentage')) {
			// free tickets
			$discount_percentage = $field[ 'value' ];
		}
			

		if (str_contains($field['key'], 'additional_attendees')) {
			$additional_attendees = $field[ 'value' ];
		}

		if (str_contains($field['key'], 'company_name')) {
			$company_name = $field[ 'value' ];
		}
		if (str_contains($field['key'], 'invoice_address')) {
			$invoice_address .= $field[ 'value' ];
		}


		//street
		if (str_contains($field['key'], 'street')) {
			$invoice_address .= $field[ 'value' ];
			$invoice_address .= ', ';
		}
		//zip
		if (str_contains($field['key'], 'zip')) {
			$invoice_address .= $field[ 'value' ];
			$invoice_address .= ' ';
		}
		//city
		if (str_contains($field['key'], 'city')) {
			$invoice_address .= $field[ 'value' ];
			$invoice_address .= ', ';
		}
		//country
		if (str_contains($field['key'], 'country')) {
			$invoice_address .= $field[ 'value' ];
		}

		//country
		if (str_contains($field['key'], 'vat_number')) {
			if ($field[ 'value' ] != '') {
				$invoice_address .= ' - ';
				$invoice_address .= 'VAT: ' . $field[ 'value' ];
			}
		}

		if (str_contains($field['key'], 'dietary_requirements')) {
			$dietary_requirements = $field[ 'value' ];
		}
		if (str_contains($field['key'], 'discount_code')) {
			$discount_code = $field[ 'value' ];
		}

	}

	[$url, $db, $username, $password] = getOdooConnection();
	//authenticate
	$uid = ODOO_UID;
	$models = RipcordLogger::client("$url/xmlrpc/2/object");


	//Step 1: add attendee(s)
	$attendee_ids = [];
	//add main attendee
	$attendee_id = $models->execute_kw($db, $uid, $password,
		'event.registration', 'create',
		[
			[
				'event_id' => $odoo_event_id,
				'name' => $name,
				'email' => $email,
				'x_studio_attendee_company' => $company_name,
				'phone' => $phone
			]
		]
	);
	$attendee_ids[] = $attendee_id;


	if ($additional_attendees && $number_of_tickets > 1) {
		foreach ($additional_attendees as $additional_attendee) {
			$att_name = $additional_attendee['value'];

			$additional_attendee_id = $models->execute_kw($db, $uid, $password,
				'event.registration', 'create',
				[
					[
						'event_id' => $odoo_event_id,
						'name' => $att_name,
						'email' => $email,
						'x_studio_attendee_company' => $company_name,
						'phone' => $phone
					]
				]
			);
			$attendee_ids[] = $additional_attendee_id;
			
		}
	}

		
	if ($attendee_ids) {



		//Step 2: add phone number - NOT NECESSARY ANYMORE??
		/*
		if($phone) {
			foreach($attendee_ids as $attendee_id) {
				$models->execute_kw(
					$db, $uid, $password,
					'event.registration', 'write',
					array(array($attendee_id), array('phone' => $phone))
				);
			}
		}
		*/
			
			


		//Step 3:  add answers for all attendees
		$answers = [];
		if ($number_of_tickets != '') {
			$answers[] = ['question_id' => 53, 'value_text_box' => $number_of_tickets];
		}
		if ($ticket_type != '') {
			$answers[] = ['question_id' => 54, 'value_text_box' => $ticket_type];
		}
			
		//add invoice address
		$answers[] = ['question_id' => 50, 'value_text_box' => $invoice_address];
			
		// if($dietary_requirements != '') {
		//     $answers[] = array('question_id' => 51, 'value_text_box' => json_encode($additional_attendees));
		// }
			
		if ($discount_code != '') {
			$answers[] = ['question_id' => 52, 'value_text_box' => $discount_code];
		}

		foreach ($attendee_ids as $attendee_id) {
			foreach ($answers as $answer) {
				$answer['registration_id'] = $attendee_id; // Link answer to attendee
				$answer_id = $models->execute_kw(
					$db, $uid, $password,
					'event.registration.answer', 'create',
					[$answer]
				);
			}
		}
			
  
		// Step 4: set the attendee to draft - unconfirmed -> double opt-in
		if ($discount_percentage == 100) {
			//if free tickets
			foreach ($attendee_ids as $attendee_id) {
				$models->execute_kw(
					$db, $uid, $password,
					'event.registration', 'write',
					[[$attendee_id], ['state' => 'draft']]
				);
			}
		}
			


		if ($discount_percentage == 100) {
			// send the email for confirmation only if the tickets were free / no payment

			$headers = [];
			// Override the default 'From' address if 'Force Sender Email' is not enabled
			$headers['From'] = 'Nextcloud <no-reply@e.nextcloud.com>';
			// Send the message as HTML
			add_filter('wp_mail_content_type', function ($content_type) {
				return 'text/html';
			});
			$headers['Content-Type'] = 'text/html; charset=ISO-8859-1';

			//default
			$curr_lang = '';

			$lang = apply_filters('wpml_current_language', null);
			if ($lang == 'en') {
				$curr_lang = '';
			} else {
				$curr_lang = apply_filters('wpml_current_language', null) . '/';
			}
			$to = $email;

			$attendee_ids_as_text = '';
			if (is_array($attendee_ids)) {
				$attendee_ids_as_text = implode(',', $attendee_ids);
			}
			$subject = __('Please confirm your registration', 'nextcloud');
			$reg_link = 'https://nextcloud.com/' . $curr_lang . 'manage-registration/?action=register&aids=' . $attendee_ids_as_text;

			
			$message = __('Hello', 'nextcloud') . ',<br />';
			$message .= sprintf(
				__('Please confirm your event registration by clicking %s.', 'nextcloud'),
				"<a href='" . esc_url($reg_link) . "'>" . __('here', 'nextcloud') . '</a>'
			);

			$message .= '<br />
            ' . __('Or by accessing this link in your URL bar:', 'nextcloud') . $reg_link . '<br />
            <br />
            ' . __('If this wasn\'t requested by you, please ignore this email.', 'nextcloud') . '
            <br />
            <br />
            ' . __('Kind regards,', 'nextcloud') . '
            <br />
            ' . __('Nextcloud team.', 'nextcloud');

			// Send the email
			$response = wp_mail($to, $subject, $message, $headers);
		}

	}

	$form_settings = $form_data[ 'settings' ];
	$form_title = $form_data[ 'settings' ][ 'title' ];
}



//custom ninja forms processing web hook to manage unsubscription and send email with confirmation link
add_action('nc_ninja_forms_send_unsubscribe_confirmation', 'nc_ninja_forms_send_unsubscribe_confirmation_callback');
function nc_ninja_forms_send_unsubscribe_confirmation_callback($form_data) {
	if (isset($form_data) && isset($form_data[ 'form_id' ])) {
		$form_id = $form_data[ 'form_id' ];
		$form_title = $form_data[ 'settings' ][ 'title' ];
	}

	$form_fields = $form_data[ 'fields' ];
	$email = '';
	$email_field_id = '';
	$page_url = '';

	foreach ($form_fields as $field_id => $field) {
		if ($field['key'] == 'email') {
			$email_field_key = $field['key'];
			$email = $field[ 'value' ];
		}
		if (str_contains($field['key'], 'url')) {
			$page_url = $field[ 'value' ];
		}
	}

	//step 1 = get lead by email and add the token in a custom field
	[$url, $db, $username, $password] = getOdooConnection();

	//authenticate
	$uid = ODOO_UID;
	$models = RipcordLogger::client("$url/xmlrpc/2/object");

	$leads = $models->execute_kw(
		$db, $uid, $password,
		'crm.lead', 'search_read',
		[[['email_from', 'ilike', $email]]],
		[
			'fields' => ['id', 'email_from', 'partner_id']
		]
	);

	if ($leads) {
		//if leads exists, generate token; it will be assigned to all leads with this email
		$token = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8);

		//echo "Found lead with ID: ".$leads[0]['id'];
		//$leadId = $leads[0]['id'];
		
		foreach ($leads as $lead) {
			$leadId = $lead['id'];

			$models->execute_kw(
				$db, $uid, $password,
				'crm.lead', 'write',
				[
					[$leadId],
					[
						'x_studio_unsubscribe_token' => $token
					]
				]
			);
		}
		
		// step 2 = send only one email
		$headers = [];
		// Override the default 'From' address if 'Force Sender Email' is not enabled
		$headers['From'] = 'Nextcloud <no-reply@e.nextcloud.com>'; // gets ignored
		$headers['reply-to'] = 'Nextcloud <unsubscribe@nextcloud.com>';
		
		// Send the message as HTML
		add_filter('wp_mail_content_type', function ($content_type) {
			return 'text/html';
		});
		$headers['Content-Type'] = 'text/html; charset=ISO-8859-1';
						
		//default
		$curr_lang = '';
		$lang = apply_filters('wpml_current_language', null);
		
		if ($lang == 'en') {
			$curr_lang = '';
		} else {
			$curr_lang = apply_filters('wpml_current_language', null) . '/';
		}

		$to = $email;
		$subject = __('Please confirm your unsubscription', 'nextcloud');
		$unsubscribe_link = 'https://nextcloud.com/' . $curr_lang . 'manage-subscription/?action=unsubscribe&email=' . $email . '&token=' . $token . '&leadID=' . $leadId;

					
		$message = __('Hello', 'nextcloud') . ',<br />';
		$message .= __("We've received your request to unsubscribe from Nextcloud marketing emails. Please confirm your choice by clicking the link below. This ensures we respect your preferences and update our records correctly.", 'nextcloud');
		$message .= "<br /><br /><a href='" . esc_url($unsubscribe_link) . "'>" . __('Confirm unsubscription', 'nextcloud') . '</a>';

		$message .= '<br /><br />
                    ' . __('If the link is not working, please copy this link in your browser: ', 'nextcloud') . $unsubscribe_link . '<br />
                    <br />
                    ' . __("If this wasn't requested by you, please ignore this email.", 'nextcloud') . '
                    <br />
                    <br />
                    ' . __('Thank you for staying part of the Nextcloud community!', 'nextcloud') . '
                    <br />
                    ' . __('Your Nextcloud team.', 'nextcloud');

		// Send the email
		$response = wp_mail($to, $subject, $message, $headers);


	}

	return $form_data;
}



//debug lead fields
function odoo_lead_debug_shortcode_callback() {
	//require_once('ripcord/ripcord.php');
	ob_start();
	global $wp;
	$page_id = get_the_ID();
	$page_url = home_url(add_query_arg([], $wp->request));

	// --- Odoo connection details ---
	[$url, $db, $username, $password] = getOdooConnection();

	$uid = ODOO_UID;
	if (!$uid) {
		return 'Failed to authenticate with Odoo.';
	}

	// Connect to object endpoint
	$models = RipcordLogger::client("$url/xmlrpc/2/object");
	$email = 'info@petermocanu.com';

	$leads = $models->execute_kw(
		$db, $uid, $password,
		'crm.lead', 'search_read',
		[[['email_from', 'ilike', $email]]],
		[
			'fields' => ['id', 'email_from', 'partner_id'],
			'limit' => 1
		]
	);
	if ($leads) {
		echo 'Found lead with ID: ' . $leads[0]['id'];

		$leadId = $leads[0]['id']; // you already have this
		$token = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8);

		$models->execute_kw(
			$db, $uid, $password,
			'crm.lead', 'write',
			[
				[$leadId],
				[
					'x_studio_unsubscribe_token' => $token
				]
			]
		);

		
		echo 'Token set to ' . $token;

	}
	


	return ob_get_clean();
}
add_shortcode('odoo_lead_debug', 'odoo_lead_debug_shortcode_callback');


//custom ninja forms processing web hook to add conference attendees
add_action('nc_ninja_forms_processing_save_multiple_attendees_debug_staging_odoo', 'nc_ninja_forms_processing_save_multiple_attendees_debug_staging_odoo_callback');
function nc_ninja_forms_processing_save_multiple_attendees_debug_staging_odoo_callback($form_data) {
	if (isset($form_data) && isset($form_data[ 'form_id' ])) {
		$form_id = $form_data[ 'form_id' ];
		$form_title = $form_data[ 'settings' ][ 'title' ];
	}

	$name = '';
	$email = '';
	$phone = '';
	$company_name = '';
	$number_of_tickets = '';
	$ticket_type = '';
	$invoice_address = '';
	$dietary_requirements = '';
	$discount_code = '';
	$odoo_event_id = '';
	$discount_percentage = 0;
	$additional_attendees = [];
	$page_url = '';

	$form_fields = $form_data[ 'fields' ];
	foreach ($form_fields as $field) {

		if (str_contains($field['key'], 'odoo_event_id')) {
			$odoo_event_id = absint($field[ 'value' ]);
		}
		if ($field['key'] == 'name') {
			$name = $field[ 'value' ];
		}
		if ($field['key'] == 'email') {
			$email = $field[ 'value' ];
		}
		if ($field['key'] == 'phone') {
			$phone = $field[ 'value' ];
		}
		if (str_contains($field['key'], 'url')) {
			$page_url = $field[ 'value' ];
		}

		if (str_contains($field['key'], 'number_of_tickets')) {
			$number_of_tickets = $field[ 'value' ];
		}
		if (str_contains($field['key'], 'ticket_type')) {
			$ticket_type = $field[ 'value' ];
		}

		if (str_contains($field['key'], 'discount_percentage')) {
			// free tickets
			$discount_percentage = $field[ 'value' ];
		}
			

		if (str_contains($field['key'], 'additional_attendees')) {
			$additional_attendees = $field[ 'value' ];
		}

		if (str_contains($field['key'], 'company_name')) {
			$company_name = $field[ 'value' ];
		}
		if (str_contains($field['key'], 'invoice_address')) {
			$invoice_address .= $field[ 'value' ];
		}


		//street
		if (str_contains($field['key'], 'street')) {
			$invoice_address .= $field[ 'value' ];
			$invoice_address .= ', ';
		}
		//zip
		if (str_contains($field['key'], 'zip')) {
			$invoice_address .= $field[ 'value' ];
			$invoice_address .= ' ';
		}
		//city
		if (str_contains($field['key'], 'city')) {
			$invoice_address .= $field[ 'value' ];
			$invoice_address .= ', ';
		}
		//country
		if (str_contains($field['key'], 'country')) {
			$invoice_address .= $field[ 'value' ];
		}

		//country
		if (str_contains($field['key'], 'vat_number')) {
			if ($field[ 'value' ] != '') {
				$invoice_address .= ' - ';
				$invoice_address .= 'VAT: ' . $field[ 'value' ];
			}
		}

		if (str_contains($field['key'], 'dietary_requirements')) {
			$dietary_requirements = $field[ 'value' ];
		}
		if (str_contains($field['key'], 'discount_code')) {
			$discount_code = $field[ 'value' ];
		}

	}

	//staging odoo
	[$url, $db, $username, $password] = getOdooConnection('staging');

	//authenticate
	$uid = ODOO_UID;
	$models = RipcordLogger::client("$url/xmlrpc/2/object");


	//Step 1: add attendee(s)
	$attendee_ids = [];
	//add main attendee
	$attendee_id = $models->execute_kw($db, $uid, $password,
		'event.registration', 'create',
		[
			[
				'event_id' => $odoo_event_id,
				'name' => $name,
				'email' => $email,
				'x_studio_attendee_company' => $company_name,
				'phone' => $phone
			]
		]
	);
	$attendee_ids[] = $attendee_id;


	if ($additional_attendees && $number_of_tickets > 1) {
		foreach ($additional_attendees as $additional_attendee) {
			$att_name = $additional_attendee['value'];

			$additional_attendee_id = $models->execute_kw($db, $uid, $password,
				'event.registration', 'create',
				[
					[
						'event_id' => $odoo_event_id,
						'name' => $att_name,
						'email' => $email,
						'x_studio_attendee_company' => $company_name,
						'phone' => $phone
					]
				]
			);
			$attendee_ids[] = $additional_attendee_id;
			
		}
	}

		
	if ($attendee_ids) {

		//Step 3:  add answers for all attendees
		$answers = [];
		if ($number_of_tickets != '') {
			$answers[] = ['question_id' => 53, 'value_text_box' => $number_of_tickets];
		}
		if ($ticket_type != '') {
			$answers[] = ['question_id' => 54, 'value_text_box' => $ticket_type];
		}
			
		//add invoice address
		$answers[] = ['question_id' => 50, 'value_text_box' => $invoice_address];
			
		// if($dietary_requirements != '') {
		//     $answers[] = array('question_id' => 51, 'value_text_box' => json_encode($additional_attendees));
		// }
			
		if ($discount_code != '') {
			$answers[] = ['question_id' => 52, 'value_text_box' => $discount_code];
		}

		foreach ($attendee_ids as $attendee_id) {
			foreach ($answers as $answer) {
				$answer['registration_id'] = $attendee_id; // Link answer to attendee
				$answer_id = $models->execute_kw(
					$db, $uid, $password,
					'event.registration.answer', 'create',
					[$answer]
				);
			}
		}
			
  
		// Step 4: set the attendee to draft - unconfirmed -> double opt-in
		if ($discount_percentage == 100) {
			//if free tickets
			foreach ($attendee_ids as $attendee_id) {
				$models->execute_kw(
					$db, $uid, $password,
					'event.registration', 'write',
					[[$attendee_id], ['state' => 'draft']]
				);
			}
		}
			


		if ($discount_percentage == 100) {
			// send the email for confirmation only if the tickets were free / no payment
			$headers = [];
			// Override the default 'From' address if 'Force Sender Email' is not enabled
			$headers['From'] = 'Nextcloud <no-reply@e.nextcloud.com>';
			// Send the message as HTML
			add_filter('wp_mail_content_type', function ($content_type) {
				return 'text/html';
			});
			$headers['Content-Type'] = 'text/html; charset=ISO-8859-1';

			//default
			$curr_lang = '';

			$lang = apply_filters('wpml_current_language', null);
			if ($lang == 'en') {
				$curr_lang = '';
			} else {
				$curr_lang = apply_filters('wpml_current_language', null) . '/';
			}
			$to = $email;

			$attendee_ids_as_text = '';
			if (is_array($attendee_ids)) {
				$attendee_ids_as_text = implode(',', $attendee_ids);
			}
			$subject = __('Please confirm your registration', 'nextcloud');
			$reg_link = 'https://nextcloud.com/' . $curr_lang . 'manage-registration/?action=register&aids=' . $attendee_ids_as_text;

				
			$message = __('Hello', 'nextcloud') . ',<br />';
			$message .= sprintf(
				__('Please confirm your event registration by clicking %s.', 'nextcloud'),
				"<a href='" . esc_url($reg_link) . "'>" . __('here', 'nextcloud') . '</a>'
			);

			$message .= '<br />
                ' . __('Or by accessing this link in your URL bar:', 'nextcloud') . $reg_link . '<br />
                <br />
                ' . __('If this wasn\'t requested by you, please ignore this email.', 'nextcloud') . '
                <br />
                <br />
                ' . __('Kind regards,', 'nextcloud') . '
                <br />
                ' . __('Nextcloud team.', 'nextcloud');

			// Send the email
			$response = wp_mail($to, $subject, $message, $headers);
		}

	}

	$form_settings = $form_data[ 'settings' ];
	$form_title = $form_data[ 'settings' ][ 'title' ];
}

// custom ninja forms processing web hook to add Odoo leads
add_action('nc_ninja_forms_add_leads_odoo_staging', 'nc_ninja_forms_add_leads_odoo_staging_callback');
function nc_ninja_forms_add_leads_odoo_staging_callback($form_data) {
	if (isset($form_data) && isset($form_data[ 'form_id' ])) {
		$form_id = $form_data[ 'form_id' ];
		$form_title = $form_data[ 'settings' ][ 'title' ];
	}

	$activity_id = 'DEFAULT_ACT_ID';
	$name = '';
	$email = '';
	$phone = '';
	$company_name = '';
	$page_url = '';

	//get fields
	$form_fields = $form_data[ 'fields' ];
	foreach ($form_fields as $field) {

		if (str_contains($field[ 'key' ], 'activity_id')) {
			$activity_id = 'Ninja Form - (' . $field[ 'value' ] . ')';
		}
		if ($field['key'] == 'name') {
			$name = $field[ 'value' ];
		}
		if ($field['key'] == 'email') {
			$email = $field[ 'value' ];
		}
		if (str_contains($field[ 'key' ], 'phone')) {
			$phone = $field[ 'value' ];
		}
		if (str_contains($field[ 'key' ], 'company')) {
			$company_name = $field[ 'value' ];
		}
		if (str_contains($field['key'], 'url')) {
			$page_url = $field[ 'value' ];
		}
	}

	[$url, $db, $username, $password] = getOdooConnection('staging');

	//authenticate
	$uid = ODOO_UID;
	$models = RipcordLogger::client("$url/xmlrpc/2/object");

	// 1) Search existing campaign
	$source_id = 0;

	$ids = $models->execute_kw(
		$db, $uid, $password,
		'utm.source', 'search',
		[[['name', '=', $activity_id]]],
		['limit' => 1]
	);

	if (!empty($ids)) {
		$source_id = (int)$ids[0];
	} else {
		// 2) Create if missing
		$source_id = $models->execute_kw(
			$db, $uid, $password,
			'utm.source', 'create',
			[[
				'name' => $activity_id,
			]]
		);
	}

	$lead_id = $models->execute_kw(
		$db, $uid, $password,
		'crm.lead', 'create',
		[[
			'source_id' => $source_id,
			'name' => $name,
			'contact_name' => $name,
			'partner_name' => $company_name,
			'email_from' => $email,
			'phone' => $phone,
		]]
	);


	$form_settings = $form_data[ 'settings' ];
	$form_title = $form_data[ 'settings' ][ 'title' ];
}




// custom ninja forms processing web hook to add Odoo leads
add_action('nc_ninja_forms_add_leads_odoo_new_account_temp', 'nc_ninja_forms_add_leads_odoo_new_account_temp_callback');
function nc_ninja_forms_add_leads_odoo_new_account_temp_callback($form_data) {
	if (isset($form_data) && isset($form_data[ 'form_id' ])) {
		$form_id = $form_data[ 'form_id' ];
		$form_title = $form_data[ 'settings' ][ 'title' ];
	}

	$activity_id = 'DEFAULT_ACT_ID';
	$name = '';
	$email = '';
	$phone = '';
	$company_name = '';
	$page_url = '';

	//get fields
	$form_fields = $form_data[ 'fields' ];
	foreach ($form_fields as $field) {

		if (str_contains($field[ 'key' ], 'activity_id')) {
			$activity_id = 'Ninja Form - (' . $field[ 'value' ] . ')';
		}
		if ($field['key'] == 'name') {
			$name = $field[ 'value' ];
		}
		if ($field['key'] == 'email') {
			$email = $field[ 'value' ];
		}
		if (str_contains($field[ 'key' ], 'phone')) {
			$phone = $field[ 'value' ];
		}
		if (str_contains($field[ 'key' ], 'company')) {
			$company_name = $field[ 'value' ];
		}
		if (str_contains($field['key'], 'url')) {
			$page_url = $field[ 'value' ];
		}
	}


	[$url, $db, $username, $password] = getOdooConnection();

	//authenticate
	$uid = ODOO_UID;
	$models = RipcordLogger::client("$url/xmlrpc/2/object");


	// 1) Search existing campaign
	$source_id = 0;

	$ids = $models->execute_kw(
		$db, $uid, $password,
		'utm.source', 'search',
		[[['name', '=', $activity_id]]],
		['limit' => 1]
	);

	if (!empty($ids)) {
		$source_id = (int)$ids[0];
	} else {
		// 2) Create if missing
		$source_id = $models->execute_kw(
			$db, $uid, $password,
			'utm.source', 'create',
			[[
				'name' => $activity_id,
			]]
		);
	}

	$lead_id = $models->execute_kw(
		$db, $uid, $password,
		'crm.lead', 'create',
		[[
			'source_id' => $source_id,
			'name' => $name,
			'contact_name' => $name,
			'partner_name' => $company_name,
			'email_from' => $email,
			'phone' => $phone,
		]]
	);


	$form_settings = $form_data[ 'settings' ];
	$form_title = $form_data[ 'settings' ][ 'title' ];
}


/* - Ninja Form action to add attendees to Pretix - */
add_action('nc_ninja_forms_processing_attendees_test_pretix', 'nc_ninja_forms_processing_attendees_test_pretix_callback');
function nc_ninja_forms_processing_attendees_test_pretix_callback($form_data) {

	$config = [
		'base_url' => 'https://pretix.eu/',
		'organizer' => 'nextcloud',
		'event' => 'testsummit',
		'api_token' => PRETIX_API_TOKEN,
		'locale' => 'en',
	];


	// set defaults
	$name = '';
	$email = '';
	$company = '';
	$ticket_type = '';
	$number_of_tickets = 1;
	$additional_attendees = [];
	$street = '';
	$zipcode = '';
	$city = '';
	$country = 'DE'; // default
	$vat_id = '';
	$is_business = true;


	$form_fields = $form_data[ 'fields' ];
	foreach ($form_fields as $field) {

		if ($field['key'] == 'name' && $field[ 'value' ] != '') {
			$name = $field[ 'value' ];
		}
		if ($field['key'] == 'email' && $field[ 'value' ] != '') {
			$email = $field[ 'value' ];
		}
		if (str_contains($field['key'], 'ticket_type') && $field[ 'value' ] != '') {
			$ticket_type = $field[ 'value' ];
		}

		//number_of_tickets
		if ($field['key'] == 'number_of_tickets' && $field[ 'value' ] != '') {
			$number_of_tickets = $field[ 'value' ];
		}

		if (str_contains($field['key'], 'additional_attendees')) {
			$additional_attendees = $field[ 'value' ];
		}

		if (str_contains($field['key'], 'discount_percentage') && $field[ 'value' ] != '') {
			// free tickets
			$discount_percentage = $field[ 'value' ];
		}
		if (str_contains($field['key'], 'company_name') && $field[ 'value' ] != '') {
			$company = $field[ 'value' ];
		}
		//street
		if (str_contains($field['key'], 'street') && $field[ 'value' ] != '') {
			$street = $field[ 'value' ];
		}
		//zip
		if (str_contains($field['key'], 'zip') && $field[ 'value' ] != '') {
			$zipcode = $field[ 'value' ];
		}
		//city
		if (str_contains($field['key'], 'city') && $field[ 'value' ] != '') {
			$city = $field[ 'value' ];
		}
		//country
		if (str_contains($field['key'], 'country') && $field[ 'value' ] != '') {
			$country = $field[ 'value' ];
		}

		//VAT ID
		if (str_contains($field['key'], 'vat_number') && $field[ 'value' ] != '') {
			$vat_id = $field[ 'value' ];
		}
		if (str_contains($field['key'], 'discount_code') && $field[ 'value' ] != '') {
			$discount_code = $field[ 'value' ];
		}
	}
	
	$price = '0.00';
	//hardcode tickets IDs from Pretix
	if ($ticket_type == 'Partner tickets') {
		$item_id = 998431; // partners ticket
	} elseif ($ticket_type == 'Public sector') {
		$item_id = 989238; // public sector ticket
	} else {
		$item_id = 989237; // regular ticket
		$price = '350.00';
	}


	$positions = [];
	/**
	 * First/main attendee
	 */
	$positions[] = [
		'item' => (int)$item_id,
		'attendee_name' => $name,
		'attendee_email' => $email,
		'company' => $company,
		'street' => $street,
		'zipcode' => $zipcode,
		'city' => $city,
		'country' => $country,
		//'price'          => $price
	];


	if ($additional_attendees && $number_of_tickets > 1) {
		foreach ($additional_attendees as $additional_attendee) {
			$additional_name = $additional_attendee['value'];
			if (empty($additional_name)) {
				continue;
			}
				
			$positions[] = [
				'item' => (int)$item_id,
				'attendee_name' => $additional_name,
				'attendee_email' => $email,
				'company' => $company,
				'street' => $street,
				'zipcode' => $zipcode,
				'city' => $city,
				'country' => $country,
				//'price'          => $price
			];

		}
	}


	$endpoint = trailingslashit($config['base_url']) . 'api/v1/organizers/' . rawurlencode($config['organizer']) . '/events/' . rawurlencode($config['event']) . '/orders/';

	$payload = [
		'status' => 'p', // p = paid, n = pending
		'email' => $email,
		'locale' => $config['locale'],
		'sales_channel' => 'web',
		//'testmode'      => true, // use this if you want to delete the orders later
		'send_email' => true,
		'invoice_address' => [
			'name' => $name,
			'company' => $company,
			'is_business' => $is_business,
			'street' => $street,
			'zipcode' => $zipcode,
			'city' => $city,
			'country' => $country,
			'vat_id' => $vat_id,
		],
		'positions' => $positions
	];

	$response = wp_remote_post(
		$endpoint,
		[
			'timeout' => 20,
			'headers' => [
				'Authorization' => 'Token ' . $config['api_token'],
				'Content-Type' => 'application/json',
				'Accept' => 'application/json',
			],
			'body' => wp_json_encode($payload),
		]
	);

	if (is_wp_error($response)) {
		error_log('Pretix sync failed: ' . $response->get_error_message());
		return;
	}

	$status_code = wp_remote_retrieve_response_code($response);
	$body = wp_remote_retrieve_body($response);
	$data = json_decode($body, true);

	if ($status_code < 200 || $status_code >= 300) {
		error_log('Pretix sync failed. HTTP ' . $status_code . ' Body: ' . $body);
		return;
	}

	$order_code = ! empty($data['code']) ? $data['code'] : '';
	error_log('Pretix sync success. Order code: ' . $order_code);

}



/* - Ninja Form action to add attendees to Pretix - */
add_action('nc_ninja_forms_processing_summit_attendees_pretix', 'nc_ninja_forms_processing_summit_attendees_pretix_callback');
function nc_ninja_forms_processing_summit_attendees_pretix_callback($form_data) {

	$config = [
		'base_url' => 'https://pretix.eu/',
		'organizer' => 'nextcloud',
		'event' => 'summit-2026',
		'api_token' => PRETIX_API_TOKEN,
		'locale' => 'en',
	];

	// set defaults
	$name = '';
	$email = '';
	$company = '';
	$ticket_type = '';
	$number_of_tickets = 1;
	$additional_attendees = [];
	$street = '';
	$zipcode = '';
	$city = '';
	$country = 'DE'; // default
	$vat_id = '';
	$is_business = true;


	$form_fields = $form_data[ 'fields' ];
	foreach ($form_fields as $field) {

		if ($field['key'] == 'name' && $field[ 'value' ] != '') {
			$name = $field[ 'value' ];
		}
		if ($field['key'] == 'email' && $field[ 'value' ] != '') {
			$email = $field[ 'value' ];
		}
		if (str_contains($field['key'], 'ticket_type') && $field[ 'value' ] != '') {
			$ticket_type = $field[ 'value' ];
		}

		//number_of_tickets
		if ($field['key'] == 'number_of_tickets' && $field[ 'value' ] != '') {
			$number_of_tickets = $field[ 'value' ];
		}

		if (str_contains($field['key'], 'additional_attendees')) {
			$additional_attendees = $field[ 'value' ];
		}

		if (str_contains($field['key'], 'discount_percentage') && $field[ 'value' ] != '') {
			// free tickets
			$discount_percentage = $field[ 'value' ];
		}
		if (str_contains($field['key'], 'company_name') && $field[ 'value' ] != '') {
			$company = $field[ 'value' ];
		}
		//street
		if (str_contains($field['key'], 'street') && $field[ 'value' ] != '') {
			$street = $field[ 'value' ];
		}
		//zip
		if (str_contains($field['key'], 'zip') && $field[ 'value' ] != '') {
			$zipcode = $field[ 'value' ];
		}
		//city
		if (str_contains($field['key'], 'city') && $field[ 'value' ] != '') {
			$city = $field[ 'value' ];
		}
		//country
		if (str_contains($field['key'], 'country') && $field[ 'value' ] != '') {
			$country = $field[ 'value' ];
		}

		//VAT ID
		if (str_contains($field['key'], 'vat_number') && $field[ 'value' ] != '') {
			$vat_id = $field[ 'value' ];
		}
		if (str_contains($field['key'], 'discount_code') && $field[ 'value' ] != '') {
			$discount_code = $field[ 'value' ];
		}
	}
	
	$price = '0.00';
	//hardcode tickets IDs from Pretix
	if ($ticket_type == 'Partner tickets') {
		$item_id = 1051184; // partners ticket
	} elseif ($ticket_type == 'Public sector') {
		$item_id = 1050856; // public sector ticket
	} else {
		$item_id = 1050855; // regular ticket
		$price = '350.00';
	}


	$positions = [];
	/**
	 * First/main attendee
	 */
	$positions[] = [
		'item' => (int)$item_id,
		'attendee_name' => $name,
		'attendee_email' => $email,
		'company' => $company,
		'street' => $street,
		'zipcode' => $zipcode,
		'city' => $city,
		'country' => $country,
		//'price'          => $price
	];


	if ($additional_attendees && $number_of_tickets > 1) {
		foreach ($additional_attendees as $additional_attendee) {
			$additional_name = $additional_attendee['value'];
			if (empty($additional_name)) {
				continue;
			}
				
			$positions[] = [
				'item' => (int)$item_id,
				'attendee_name' => $additional_name,
				'attendee_email' => $email,
				'company' => $company,
				'street' => $street,
				'zipcode' => $zipcode,
				'city' => $city,
				'country' => $country,
				//'price'          => $price
			];

		}
	}


	$endpoint = trailingslashit($config['base_url']) . 'api/v1/organizers/' . rawurlencode($config['organizer']) . '/events/' . rawurlencode($config['event']) . '/orders/';

	$payload = [
		'status' => 'n', // p = paid, n = pending
		'email' => $email,
		'locale' => $config['locale'],
		'sales_channel' => 'web',
		//'testmode'      => true, // use this if you want to delete the orders later
		'send_email' => false,
		'invoice_address' => [
			'name' => $name,
			'company' => $company,
			'is_business' => $is_business,
			'street' => $street,
			'zipcode' => $zipcode,
			'city' => $city,
			'country' => $country,
			'vat_id' => $vat_id,
		],
		'positions' => $positions
	];

	$response = wp_remote_post(
		$endpoint,
		[
			'timeout' => 20,
			'headers' => [
				'Authorization' => 'Token ' . $config['api_token'],
				'Content-Type' => 'application/json',
				'Accept' => 'application/json',
			],
			'body' => wp_json_encode($payload),
		]
	);

	if (is_wp_error($response)) {
		error_log('Pretix sync failed: ' . $response->get_error_message());
		return;
	}

	$status_code = wp_remote_retrieve_response_code($response);
	$body = wp_remote_retrieve_body($response);
	$data = json_decode($body, true);

	if ($status_code < 200 || $status_code >= 300) {
		error_log('Pretix sync failed. HTTP ' . $status_code . ' Body: ' . $body);
		return;
	}

	$order_code = ! empty($data['code']) ? $data['code'] : '';
	error_log('Pretix sync success. Order code: ' . $order_code);


	//set order as paid
	$mark_paid_endpoint = trailingslashit($config['base_url'])
		. 'api/v1/organizers/' . rawurlencode($config['organizer'])
		. '/events/' . rawurlencode($config['event'])
		. '/orders/' . rawurlencode($order_code)
		. '/mark_paid/';

	wp_remote_post(
		$mark_paid_endpoint,
		[
			'headers' => [
				'Authorization' => 'Token ' . $config['api_token'],
				'Content-Type' => 'application/json',
				'Accept' => 'application/json',
			],

			'body' => wp_json_encode([
				'send_email' => true,
			]),
		]
	);

}
