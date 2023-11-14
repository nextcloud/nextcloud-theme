<?php
function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

//ninja forms filter emails - don't allow private or spam emails
function isDisposableEmail($email, $blocklist_path = null) {
    if (!$blocklist_path) $blocklist_path = __DIR__ . '/disposable_email_blocklist.txt';
    $disposable_domains = file($blocklist_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $domain = mb_strtolower(explode('@', trim($email))[1]);
    return in_array($domain, $disposable_domains);
}

function isDisposableEmail_private_list($email, $blocklist_path = null) {
    if (!$blocklist_path) $blocklist_path = __DIR__ . '/disposable_email_blocklist_private.txt';
    $disposable_domains = file($blocklist_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $domain = mb_strtolower(explode('@', trim($email))[1]);
    return in_array($domain, $disposable_domains);
}

add_filter('ninja_forms_submit_data', 'nc_custom_ninja_forms_submit_data');
function nc_custom_ninja_forms_submit_data($form_data)
{   
    $form_id = $form_data['id'];

    foreach( $form_data[ 'fields' ] as $field_id => $field ) {
        if($form_id != 1 && $form_id != 30 && $form_id != 27 && $form_id != 33 && $form_id != 68 && $form_id != 72 ) {
            // exclude Contact form, Discuss your app form, Newsletter form, Contact Issue form, Events newsletter form, Events lead collection form

            if( str_contains($field[ 'key' ], 'email') ) {
                // email, corporate_email_1656608192369, email_1666338754229, business_email_1654165444607  - the other keys
                    $field_value = $form_data['fields'][$field_id]['value'];
                    if ( !filter_var( $field_value , FILTER_VALIDATE_EMAIL) ) {
                        $form_data['errors']['fields'][$field_id] = 'Invalid email format!';
                    }
                    if( isDisposableEmail($field_value) || isDisposableEmail_private_list($field_value) ){ // not for the Email field inside the Contact form
                        $form_data['errors']['fields'][$field_id] = 'Please use a valid business email';
                    }
            }
        }
  

    }

    return $form_data;
}




//save name, email and language on submitting the form
add_filter('ninja_forms_submit_data', 'nc_ninja_custom_save_cookies');
function nc_ninja_custom_save_cookies($form_data){   
    $convenience_cookie_set = get_string_between($_COOKIE['nc_cookie_banner'], 'convenience\":', ',\"statistics');
    if($convenience_cookie_set) {

        $form_fields = array();
        foreach( $form_data[ 'fields' ] as $field_id => $field ) {
        
            if( str_contains($field['key'], 'name') && !str_contains($field_settings['key'], 'organization') ){    
                $form_fields['nc_form_name'] = $field[ 'value' ];
            }

            if( str_contains($field['key'], 'email') ){
                $form_fields['nc_form_email'] = urlencode($field[ 'value' ]);
            }

            if(str_contains($field['key'], 'language')) {
                $form_fields['nc_form_lang'] = $field[ 'value' ];
            }

            if( str_contains($field['key'], 'phone') ){
                $form_fields['nc_form_phone'] = $field[ 'value' ];
            }
        }

        setcookie('nc_form_fields', base64_encode(json_encode($form_fields)), time() + (86400 * 30), "/");
    }


    return $form_data;
}


//pre-populating fields on display
add_filter( 'ninja_forms_render_default_value', 'nc_change_nf_default_value', 10, 3 );
function nc_change_nf_default_value( $default_value, $field_type, $field_settings ) {
    
    if(isset($_COOKIE['nc_form_fields'])){
        $nc_form_fields = json_decode(base64_decode($_COOKIE['nc_form_fields']), true);

        if( str_contains($field_settings['key'], 'name') && !str_contains($field_settings['key'], 'organization') ){
                if(isset($nc_form_fields['nc_form_name'])) {
                    $default_value = $nc_form_fields['nc_form_name'];
                }
        }
        if( str_contains($field_settings['key'], 'email') ){
                if(isset($nc_form_fields['nc_form_email'])) {
                    $default_value = urldecode($nc_form_fields['nc_form_email']);
                }
        }
        if( str_contains($field_settings['key'], 'phone') ){
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
add_filter( 'ninja_forms_render_options', function( $options, $settings ) {


    if($settings['key'] == 'select_mailing_lists_checkboxes') {
        //echo "true!";
        foreach( $options as &$option ) {
            
            if( isset($_GET['list']) ) {

                if($option[ 'value' ] == strip_tags($_GET['list']) ){
                    $option[ 'selected' ] = true;
                }

            } else if( $option[ 'label' ] == 'Nextcloud news' ){
                $option[ 'selected' ] = true;
            }
        }
    }
  
    return $options;
}, 10, 2 );



//add custom nija forms processing web hook - used to save contacts to newsletter
add_action( 'nc_ninja_forms_processing_save_to_newsletter', 'nc_ninja_forms_processing_save_to_newsletter_callback' );
function nc_ninja_forms_processing_save_to_newsletter_callback( $form_data ){
    
    if(isset($form_data) && isset($form_data[ 'form_id' ])) {
        $form_id       = $form_data[ 'form_id' ];
    }
    
    $form_fields   =  $form_data[ 'fields' ];

    $email = '';
    $name = '';
    $phone = '';
    $mailing_list_ids = array();
    $subscribed = false;

    $select_mailing_lists_checkboxes = array();


    if(isset($form_id)) {
            if(     $form_id == 27 // Newsletter form
                    ||  $form_id == 68  // Events newsletter form
                    ||  $form_id == 4 // whitepapers and case studies
                    ||  $form_id == 49 // Case study Meiji university
                    ||  $form_id == 66 // Get more information about event
                )
            {
                $subscribed = true; // automatically set as accepted for these forms

                if($form_id == 66){ // for the events form, subscribe to the newsletters only if checkbox is selected
                    $subscribed = false;
                }    

                foreach( $form_fields as $field ){

                    if( str_contains($field['key'], 'keep_me_informed') ){
                        // means users selected the newsletters checkbox
                        if($field[ 'value' ] != 0){
                            $subscribed = true;
                        }
                    }

                    if( str_contains($field['key'], 'name') ){
                        $name = $field[ 'value' ];
                    }

                    if( 
                        str_contains($field['key'], 'email')
                    ){
                        $email = $field[ 'value' ];
                    }

                    if( 
                        str_contains($field['key'], 'mailing_list_id')
                    ){
                        $mailing_list_ids[] = $field[ 'value' ];
                    }


                    if( 'select_mailing_lists_checkboxes' == $field[ 'key' ] ){
                        $mailing_list_ids = $field[ 'value' ];
                    }

                }
                
            }
    }
    

    if($subscribed) {
        $url = 'https://odoo.nextcloud.com';
        $db = 'nextcloud-crm-odoo-main-4730113';
        $username = "jos.poortvliet@nextcloud.com";
        $password = ODOO_API_KEY; // api key
        //$password = ''; // password

        //logging in
        $common = ripcord::client("$url/xmlrpc/2/common");

        //authenticate
        $uid = $common->authenticate($db, $username, $password, array());

                $models = ripcord::client("$url/xmlrpc/2/object");
                $sub_id = $models->execute_kw($db, $uid, $password,
                'mailing.contact', 'create',
                array(
                    array(
                        'name'=> $name,
                        'email'=> $email,
                        'list_ids' => $mailing_list_ids
                    )
                )
            );

         //debug
         //setcookie("nc_sub_id", $sub_id , time() + (86400 * 30), "/" );

    }

    $form_settings = $form_data[ 'settings' ];
    $form_title    = $form_data[ 'settings' ][ 'title' ];

}



//populate preferred language field with all languages in the world
add_filter( 'ninja_forms_render_options', function( $options, $settings ) {
    
    //https://www.html-code-generator.com/php/array/languages-name-and-code
    $languages_list = array(
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
    );

    if(str_contains($settings['key'], 'language')) {

        $options = [];
        if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $browser_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        }

        $pref_lang = '';
        if(isset($_COOKIE['nc_form_fields'])){
            $nc_form_fields = json_decode(base64_decode($_COOKIE['nc_form_fields']), true);
            if( isset($nc_form_fields['nc_form_lang'])){
                $pref_lang = $nc_form_fields['nc_form_lang'];
            }
        } else {
            $pref_lang = $browser_lang;
        }


        foreach($languages_list as $code => $language) {
            $selected = false;

            if($pref_lang == $code){
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
}, 10, 2 );


//populate event name field with names of event post types and Activity IDs
add_filter( 'ninja_forms_render_options', function( $options, $settings ) {

    $events_list = array();

    $args = array(
		'post_type' => 'event',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'tax_query' => array(
			array(
				'taxonomy' => 'event_categories',
				'field'    => 'slug',
				'terms'    => 'exhibition',
			),
		)
	);

	$events_query = new WP_Query($args);
    if ( $events_query->have_posts() ) {
		while ( $events_query->have_posts() ) {
			$events_query->the_post();

            $event_end_datetime = get_field('event_end_date_and_time', false, false);
            $now_timestamp = new DateTime("now");
            $event_end_datetime_timestamp = new DateTime($event_end_datetime);
            $interval = $now_timestamp->diff($event_end_datetime_timestamp);


			$activity_id = get_field('activity_id');
            if( isset($activity_id) && str_contains($activity_id, '-apt-'))
            {
                $activity_id = str_replace("-apt-", "-", $activity_id);
            }

			$title = get_the_title();
            if(get_field('event_short_title')) {
                $title = get_field('event_short_title');
            }

            
            if($interval->days < 30) {
                $events_list[$activity_id]=$title;
            }
            //$events_list[$activity_id]=$title;
            

		}
	} else {
		// no posts found
	}
	/* Restore original Post Data */
	wp_reset_postdata();

    if( $settings['key'] == 'event_name_1685534754887') {
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
}, 10, 2 );