<?php

/**
 * Add additional OG image meta tags for LinkedIn compatibility
 */
function nc_add_linkedin_og_image_meta() {

	if (! is_singular()) {
		return;
	}

	global $post;

	if (! has_post_thumbnail($post->ID)) {
		return;
	}

	$image_id = get_post_thumbnail_id($post->ID);
	$image_url = wp_get_attachment_image_url($image_id, 'full');

	if (! $image_url) {
		return;
	}

	// Ensure HTTPS
	$secure_url = set_url_scheme($image_url, 'https');

	echo "\n<!-- Additional OG image tags for LinkedIn -->\n";
	echo '<meta name="image" property="og:image" content="' . esc_url($image_url) . '">' . "\n";
	echo '<meta property="og:image:secure_url" content="' . esc_url($secure_url) . '">' . "\n";

}
add_action('wp_head', 'nc_add_linkedin_og_image_meta', 20);

//removes hreflang entries for languages where: The translation doesn’t exist or the translation is unpublished
add_filter('wpml_hreflangs', 'remove_untranslated_wpml_hreflangs');
function remove_untranslated_wpml_hreflangs($hreflangs) {
	$source_post_id = get_queried_object_id();

	if (! $source_post_id) {
		return $hreflangs;
	}

	foreach ($hreflangs as $lang_code => $hreflang) {
		$translated_post_id = apply_filters('wpml_object_id', $source_post_id, get_post_type($source_post_id), false, $lang_code);

		if (empty($translated_post_id) || get_post_status($translated_post_id) !== 'publish') {
			unset($hreflangs[ $lang_code ]);
		}
	}

	return $hreflangs;
}

// Remove canonical for untranslated pages
/*
add_filter( 'wpseo_canonical', 'remove_canonical_for_untranslated_pages' );
function remove_canonical_for_untranslated_pages( $canonical ) {
	if ( function_exists( 'icl_object_id' ) && function_exists( 'wpml_get_language_information' ) ) {
		global $post;

		// Get current language
		$current_lang = apply_filters( 'wpml_current_language', null );

		// Get the post ID in the default language
		$default_lang_post_id = apply_filters( 'wpml_object_id', $post->ID, get_post_type( $post->ID ), false, wpml_get_default_language() );

		// If current post is just a duplicate (i.e., not truly translated)
		if ( $post->ID === $default_lang_post_id && $current_lang !== wpml_get_default_language() ) {
			return false; // Removes the canonical tag
		}
	}
	return $canonical;
}
*/

//add title attribute to menu items
/*
add_filter( 'nav_menu_link_attributes', 'nc_custom_menu_adjustements', 10, 3 );
function nc_custom_menu_adjustements( $atts, $item, $args ) {

	//add aria-hidden on items with CSS class "first_item_hidden"
	if (in_array('cookies_preferences', $item->classes)) {
		$atts['aria-hidden'] = 'true';
	}

	return $atts;
}
*/

// fix Ahrefs issues: Canonical points to 4XX (canonical URL contains the translated slug but not the lang code)
/*
add_filter('wpseo_canonical', function($canonical) {
	if (is_singular('case_studies')) {
		$permalink = get_permalink();

		if (!empty($permalink)) {
			return $permalink;
		}
	}

	return $canonical;
});
*/

// fix hreflang for podcast post type: the french link does not contain the /fr/ part - not working anymore
/*
add_filter('wpseo_hreflang', function ($hreflangs) {
	if (!is_singular('podcast')) {
		return $hreflangs;
	}

	$id = get_the_ID();
	$post_type = get_post_type($id);

	$type = apply_filters('wpml_element_type', $post_type);

	$trid = apply_filters('wpml_element_trid', null, $id, $type);
	$translations = apply_filters('wpml_get_element_translations', null, $trid, $type);

	if (empty($translations)) {
		return $hreflangs;
	}

	foreach ($translations as $lang => $translation) {
		if (empty($translation->element_id)) {
			continue;
		}

		$url = apply_filters(
			'wpml_permalink',
			get_permalink($translation->element_id),
			$lang,
			true
		);

		if (!empty($url)) {
			$hreflangs[$lang] = $url;
		}
	}

	return $hreflangs;
}, 20);
*/

// fix hreflang for podcast post type: the french link does not contain the /fr/ part
add_action('template_redirect', function () {
	if (!is_singular('podcast')) {
		return;
	}

	ob_start(function ($html) {
		$html = str_replace(
			'https://nextcloud.com/blog/magentacloud-t-systems-la-construction-dun-nextcloud-de-22-millions-dutilisateurs/',
			'https://nextcloud.com/fr/blog/magentacloud-t-systems-la-construction-dun-nextcloud-de-22-millions-dutilisateurs/',
			$html
		);

		$html = str_replace(
			'https://nextcloud.com/fr/blog/magentacloud-t-systems-building-a-2m-user-nextcloud/',
			'https://nextcloud.com/blog/magentacloud-t-systems-building-a-2m-user-nextcloud/',
			$html
		);

		return $html;
	});
}, 0);

//temporary debug
/*
add_action('wp_head', function () {

	if (!is_singular('case_studies')) {
		return;
	}

	global $sitepress;

	$post_id      = get_queried_object_id();
	$post_type    = get_post_type($post_id);
	$wpml_type    = 'post_' . $post_type;
	$current_lang = apply_filters('wpml_current_language', null);

	echo "\n<!-- CANONICAL DEBUG\n";
	echo "current_lang: " . esc_html($current_lang) . "\n";
	echo "post_id: " . esc_html($post_id) . "\n";
	echo "post_type: " . esc_html($post_type) . "\n";
	echo "permalink: " . esc_url(get_permalink($post_id)) . "\n";

	if ($sitepress) {
		$trid = $sitepress->get_element_trid($post_id, $wpml_type);
		echo "trid: " . esc_html($trid) . "\n";

		$translations = $sitepress->get_element_translations($trid, $wpml_type);

		foreach ($translations as $lang => $translation) {
			echo "translation[$lang]: ";
			echo "element_id=" . esc_html($translation->element_id) . " | ";
			echo "original=" . esc_html($translation->original ? 'yes' : 'no') . " | ";
			echo "post_title=" . esc_html(get_the_title($translation->element_id)) . " | ";
			echo "url=" . esc_url(get_permalink($translation->element_id)) . "\n";
		}
	} else {
		echo "sitepress: not available\n";
	}

	echo "END CANONICAL DEBUG -->\n";

}, 1);
*/

//Fix: If the content is English fallback content, then page should usually not self-canonicalize (for other languages other than default EN)
add_filter('wpseo_canonical', function ($canonical) {
	if (!is_singular('case_studies')) {
		return $canonical;
	}

	global $sitepress;

	if (!$sitepress) {
		return $canonical;
	}

	$current_lang = apply_filters('wpml_current_language', null);

	if ($current_lang === 'en') {
		return $canonical;
	}

	$post_id = get_queried_object_id();
	$post_type = 'post_' . get_post_type($post_id);

	$trid = $sitepress->get_element_trid($post_id, $post_type);

	if (!$trid) {
		return $canonical;
	}

	$translations = $sitepress->get_element_translations($trid, $post_type);

	if (
		isset($translations['en'])
		&& !empty($translations['en']->element_id)
	) {
		$en_post_id = (int)$translations['en']->element_id;

		// non-EN URL showing the EN post = fallback content
		if ((int)$post_id === $en_post_id) {

			$en_url = apply_filters(
				'wpml_permalink',
				get_permalink($en_post_id),
				'en',
				true
			);

			return $en_url ?: $canonical;
		}
	}

	return $canonical;
}, 999);

/**
 * Fix missing WPML language directories in podcast sitemap URLs - https://nextcloud.com/podcast-sitemap.xml
 */
add_filter(
	'wpseo_xml_sitemap_post_url',
	function ($url, $post) {

		if (
			!($post instanceof WP_Post)
			|| $post->post_type !== 'podcast'
		) {
			return $url;
		}

		$language_details = apply_filters(
			'wpml_element_language_details',
			null,
			[
				'element_id' => $post->ID,
				'element_type' => 'post_' . $post->post_type,
			]
		);

		if (empty($language_details->language_code)) {
			return $url;
		}

		$language_code = sanitize_key(
			$language_details->language_code
		);

		// English is the default language and uses no directory.
		if ($language_code === 'en') {
			return $url;
		}

		$parsed_url = wp_parse_url($url);

		if (
			empty($parsed_url['scheme'])
			|| empty($parsed_url['host'])
			|| empty($parsed_url['path'])
		) {
			return $url;
		}

		// Already contains the correct language directory.
		if (
			preg_match(
				'#^/' . preg_quote($language_code, '#') . '(?:/|$)#',
				$parsed_url['path']
			)
		) {
			return $url;
		}

		$localized_path
			= '/' . $language_code . '/' . ltrim($parsed_url['path'], '/');

		$localized_url
			= $parsed_url['scheme']
			. '://'
			. $parsed_url['host']
			. $localized_path;

		if (!empty($parsed_url['query'])) {
			$localized_url .= '?' . $parsed_url['query'];
		}

		/*
		error_log(
			sprintf(
				'[PODCAST SITEMAP FIX] ID=%d | lang=%s | before=%s | after=%s',
				$post->ID,
				$language_code,
				$url,
				$localized_url
			)
		);
		*/

		return $localized_url;
	},
	9999,
	2
);

/**
 * Disable Yoast sitemap transient caching while testing.
 */
/*
add_filter(
	'wpseo_enable_xml_sitemap_transient_caching',
	'__return_false',
	9999
);
*/

/**
 * Fix hreflang URLs for podcast fallback pages.
 *
 * Example:
 * /es/blog/english-podcast/
 *
 * The post itself is EN, with a real FR translation.
 * WPML incorrectly prefixes both hreflang URLs with /es/.
 */
add_filter(
	'wpml_alternate_hreflang',
	function ($url, $language_code) {

		if (!is_singular('podcast')) {
			return $url;
		}

		$post_id = get_queried_object_id();
		$post_type = get_post_type($post_id);

		if (!$post_id || !$post_type || empty($language_code)) {
			return $url;
		}

		/*
		 * Find the actual post ID for the requested hreflang language.
		 *
		 * false means:
		 * do not return the original post when the translation is missing.
		 */
		$translation_id = apply_filters(
			'wpml_object_id',
			$post_id,
			$post_type,
			false,
			$language_code
		);

		/*
		 * Do not output a fake hreflang for a language
		 * that has no real translation.
		 */
		if (!$translation_id) {
			return '';
		}

		/*
		 * get_permalink() is affected by the current /es/ context,
		 * so temporarily remove any existing language prefix and
		 * explicitly rebuild the URL for the target language.
		 */
		$permalink = get_permalink($translation_id);

		if (!$permalink) {
			return $url;
		}

		$parsed_url = wp_parse_url($permalink);

		if (
			empty($parsed_url['scheme'])
			|| empty($parsed_url['host'])
			|| empty($parsed_url['path'])
		) {
			return $url;
		}

		$path = $parsed_url['path'];

		/*
		 * Remove an incorrectly inherited active-language prefix,
		 * such as /es/, /it/, /de/, etc.
		 */
		$active_languages = apply_filters(
			'wpml_active_languages',
			null,
			[
				'skip_missing' => 0,
			]
		);

		if (is_array($active_languages)) {
			foreach (array_keys($active_languages) as $active_code) {
				$path = preg_replace(
					'#^/' . preg_quote($active_code, '#') . '(?=/|$)#',
					'',
					$path
				);
			}
		}

		/*
		 * EN is the default language and does not use a directory.
		 */
		if ($language_code === 'en') {
			$localized_path = '/' . ltrim($path, '/');
		} else {
			$localized_path
				= '/' . $language_code . '/' . ltrim($path, '/');
		}

		return $parsed_url['scheme']
			. '://'
			. $parsed_url['host']
			. $localized_path;
	},
	9999,
	2
);

//temporary debug WPML language
/*
add_action('wp_head', function () {

	if (
		!is_singular('podcast') ||
		apply_filters('wpml_current_language', null) !== 'es' ||
		(int) get_queried_object_id() !== 203279
	) {
		return;
	}

	global $wp_filter;

	$hooks_to_check = [
		'post_type_link',
		'post_link',
		'home_url',
		'site_url',
		'wpml_permalink',
	];

	foreach ($hooks_to_check as $hook_name) {

		error_log(sprintf(
			'[HOOK DEBUG] ===== %s =====',
			$hook_name
		));

		if (empty($wp_filter[$hook_name])) {
			error_log('[HOOK DEBUG] No callbacks');
			continue;
		}

		$callbacks = $wp_filter[$hook_name]->callbacks ?? [];

		foreach ($callbacks as $priority => $items) {
			foreach ($items as $item) {

				$function = $item['function'] ?? null;
				$name     = 'unknown';

				if (is_string($function)) {
					$name = $function;
				} elseif (
					is_array($function) &&
					isset($function[0], $function[1])
				) {
					$class = is_object($function[0])
						? get_class($function[0])
						: $function[0];

					$name = $class . '::' . $function[1];
				} elseif ($function instanceof Closure) {
					$reflection = new ReflectionFunction($function);

					$name = sprintf(
						'Closure in %s:%d',
						$reflection->getFileName(),
						$reflection->getStartLine()
					);
				}

				error_log(sprintf(
					'[HOOK DEBUG] priority=%s | callback=%s',
					$priority,
					$name
				));
			}
		}
	}
});
*/

/**
 * Fix WPML language-switcher URLs on podcast pages.
 */
add_filter(
	'icl_ls_languages',
	function ($languages) {

		if (
			! is_singular('podcast')
			|| ! is_array($languages)
		) {
			return $languages;
		}

		$post_id = get_queried_object_id();

		if (! $post_id) {
			return $languages;
		}

		/*
		 * Actual URL currently opened in the browser.
		 *
		 * This avoids get_permalink(), home_url() and Podlove permalink
		 * filters, which may incorrectly return the English URL.
		 */
		$network_url = network_home_url('/');
		$scheme = wp_parse_url($network_url, PHP_URL_SCHEME);
		$host = wp_parse_url($network_url, PHP_URL_HOST);
		$request_uri = strtok(
			wp_unslash($_SERVER['REQUEST_URI'] ?? ''),
			'?'
		);

		$current_url = sprintf(
			'%s://%s%s',
			$scheme ?: 'https',
			$host,
			'/' . ltrim($request_uri, '/')
		);

		foreach ($languages as $language_code => &$language) {

			/*
			 * The selected language must always link to the page
			 * currently being viewed.
			 *
			 * Do this before wpml_object_id because WPML/Podlove may
			 * incorrectly resolve the current podcast to the EN URL.
			 */
			if (! empty($language['active'])) {
				$language['url'] = $current_url;
				continue;
			}

			$translation_id = apply_filters(
				'wpml_object_id',
				$post_id,
				'podcast',
				false,
				$language_code
			);

			/*
			 * No genuine translation exists.
			 * Keep WPML's existing fallback URL.
			 */
			if (! $translation_id) {
				continue;
			}

			$translation_details = apply_filters(
				'wpml_element_language_details',
				null,
				[
					'element_id' => $translation_id,
					'element_type' => 'post_podcast',
				]
			);

			if (empty($translation_details->language_code)) {
				continue;
			}

			$actual_language = sanitize_key(
				$translation_details->language_code
			);

			$post_slug = get_post_field(
				'post_name',
				$translation_id
			);

			if (! $post_slug) {
				continue;
			}

			/*
			 * Podlove podcast URL structure:
			 *
			 * EN: /blog/post-slug/
			 * DE: /de/blog/post-slug/
			 * FR: /fr/blog/post-slug/
			 */
			if ($actual_language === 'en') {

				$correct_url = sprintf(
					'%s/blog/%s/',
					untrailingslashit(network_home_url()),
					rawurlencode($post_slug)
				);

			} else {

				$correct_url = sprintf(
					'%s/%s/blog/%s/',
					untrailingslashit(network_home_url()),
					rawurlencode($actual_language),
					rawurlencode($post_slug)
				);
			}

			/*
			 * Remove any language directory inherited from WPML's
			 * network_home_url() filtering.
			 */
			$correct_url = preg_replace(
				'#^(https?://nextcloud\.com)/(?:[a-z]{2}(?:-[a-z]{2})?/)+#i',
				'$1/',
				$correct_url
			);

			$language['url'] = $correct_url;
		}

		unset($language);

		return $languages;
	},
	9999
);
