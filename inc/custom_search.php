<?php

function nc_custom_search_form($form) {
	$form = '<form role="search" method="get" id="searchform" class="searchform" action="' . home_url('/') . '" >
	<div class="custom-form"><label class="screen-reader-text" for="s">' . __('Search:') . '</label>
    <input type="text" name="hp_field" value="" style="display:none !important" autocomplete="off" tabindex="-1">
	<input type="text" value="' . get_search_query() . '" placeholder="' . __('Search here..', 'nextcloud') . '" name="s" id="s" />';
	$form .= '<button type="submit" title="' . __('Search', 'nextcloud') . '" id="searchsubmit"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="#000000"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z" style="width: 20px;height: auto;"></path></svg></button>';
	$form .= '</div></form>';
	return $form;
}
add_filter('get_search_form', 'nc_custom_search_form', 40);

//Render the Gutenberg Search block (core/search) with the same custom form on the frontend
function nc_custom_search_block($block_content, $block) {
	return get_search_form(['echo' => false]);
}
add_filter('render_block_core/search', 'nc_custom_search_block', 10, 2);

//blocks search if honeypot field is set
function my_search_honeypot() {
	if (! is_admin() && is_search()) {
		if (! empty($_GET['hp_field'])) {
			wp_die(
				'Suspicious search detected.',
				'Search blocked',
				[ 'response' => 403 ]
			);
		}
	}
}
add_action('template_redirect', 'my_search_honeypot');

// Rate limiting by IP on search - Throttle “too many searches from the same IP” using a transient:
function my_rate_limit_search() {
	if (is_admin() || ! is_search()) {
		return;
	}

	$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
	$key = 'search_count_' . md5($ip);

	$count = (int)get_transient($key);
	$count++;

	// Store count per minute
	set_transient($key, $count, MINUTE_IN_SECONDS);

	// Allow e.g. up to 10 searches per minute per IP
	if ($count > 10) {
		wp_die(
			'Too many search requests from your IP. Please slow down.',
			'Too many requests',
			[ 'response' => 429 ]
		);
	}
}
add_action('template_redirect', 'my_rate_limit_search');




//Customize search results by ordering by post title and slug
/** Tiny singularizer (newsletters→newsletter, companies→company, boxes→box, cats→cat) */
if (! function_exists('my_singularize_term')) {
	function my_singularize_term(string $t): string {
		$t = mb_strtolower($t);
		if (mb_strlen($t) > 4 && preg_match('/ies$/u', $t)) {
			return preg_replace('/ies$/u', 'y', $t);
		}
		if (mb_strlen($t) > 3 && preg_match('/(xes|ses|zes|ches|shes)$/u', $t)) {
			return preg_replace('/es$/u', '', $t);
		}
		if (mb_strlen($t) > 3 && preg_match('/s$/u', $t)) {
			return preg_replace('/s$/u', '', $t);
		}
		return $t;
	}
}

//Add filter to include singular variants in the SQL search
/*
add_filter( 'posts_search', function( $search, WP_Query $q ) {
	if ( is_admin() || ! $q->is_main_query() || ! $q->is_search() ) return $search;

	// Guard: we only want to touch real SQL search fragments (string, no HTML)
	if ( ! is_string( $search ) ) return $search;
	if ( $search === '' ) return $search;
	if ( stripos( $search, '<form' ) !== false ) return $search; // <- prevents your exact symptom

	global $wpdb;

	$s_raw = trim( (string) $q->get( 's' ) );
	if ( $s_raw === '' ) return $search;

	// Build variants: original terms + singular terms
	$terms    = preg_split( '/\s+/', mb_strtolower( $s_raw ), -1, PREG_SPLIT_NO_EMPTY );
	$variants = [];
	foreach ( $terms as $t ) {
		$variants[] = $t;
		$sg = my_singularize_term( $t );
		if ( $sg !== $t ) $variants[] = $sg;
	}
	$variants = array_values( array_unique( array_filter( $variants ) ) );
	if ( empty( $variants ) ) return $search;

	// Add OR variants into the existing search clause
	$or = [];
	foreach ( $variants as $v ) {
		$like = '%' . $wpdb->esc_like( $v ) . '%';
		$or[] = $wpdb->prepare(
			"({$wpdb->posts}.post_title LIKE %s OR {$wpdb->posts}.post_name LIKE %s OR {$wpdb->posts}.post_excerpt LIKE %s)",
			$like, $like, $like
		);
	}

	return $search . ' AND (' . implode( ' OR ', $or ) . ')';
}, 20, 2 );
*/


/** 2) Prefer TITLE + SLUG in ORDER BY (runs late so it wins ordering) */
/*
add_filter( 'posts_clauses', function( $clauses, WP_Query $q ) {
	if ( is_admin() || ! $q->is_main_query() || ! $q->is_search() ) return $clauses;

	global $wpdb;

	$s_raw = (string) $q->get( 's' );
	$s     = trim( $s_raw );
	if ( $s === '' ) return $clauses;

	// Build list of variants (terms + singulars + phrase + singular phrase)
	$terms    = preg_split( '/\s+/', $s, -1, PREG_SPLIT_NO_EMPTY );
	$variants = [];
	foreach ( $terms as $t ) {
		$t = trim( $t );
		if ( $t === '' ) continue;
		$variants[] = mb_strtolower( $t );
		$sg = my_singularize_term( $t );
		if ( $sg !== '' && $sg !== mb_strtolower( $t ) ) $variants[] = $sg;
	}
	$phrase = mb_strtolower( $s );
	$variants[] = $phrase;
	$phrase_sg  = my_singularize_term( $phrase );
	if ( $phrase_sg !== '' && $phrase_sg !== $phrase ) $variants[] = $phrase_sg;

	$variants = array_values( array_unique( $variants ) );
	if ( empty( $variants ) ) return $clauses;

	// Build scoring across title and slug
	$slug_eq    = [];
	$title_eq   = [];
	$slug_pref  = [];
	$title_like = [];
	$slug_like  = [];
	$title_pos  = [];

	foreach ( $variants as $v ) {
		$slug_eq[]   = $wpdb->prepare( "CASE WHEN LOWER({$wpdb->posts}.post_name) = LOWER(%s) THEN 1 ELSE 0 END", $v );
		$title_eq[]  = $wpdb->prepare( "CASE WHEN LOWER({$wpdb->posts}.post_title) = LOWER(%s) THEN 1 ELSE 0 END", $v );
		$slug_pref[] = $wpdb->prepare( "CASE WHEN LOWER({$wpdb->posts}.post_name) LIKE LOWER(%s) THEN 1 ELSE 0 END", $v.'%' );

		$lk = '%' . $wpdb->esc_like( $v ) . '%';
		$title_like[] = $wpdb->prepare( "CASE WHEN {$wpdb->posts}.post_title LIKE %s THEN 1 ELSE 0 END", $lk );
		$slug_like[]  = $wpdb->prepare( "CASE WHEN {$wpdb->posts}.post_name  LIKE %s THEN 1 ELSE 0 END", $lk );

		$title_pos[]  = $wpdb->prepare(
			"CASE WHEN LOCATE(%s, LOWER({$wpdb->posts}.post_title)) > 0
				  THEN (1000 - LOCATE(%s, LOWER({$wpdb->posts}.post_title)))
				  ELSE 0 END",
			$v, $v
		);
	}

	// Weights
	$w_slug_eq   = 1200;
	$w_title_eq  = 1000;
	$w_slug_pref = 700;
	$w_title_lk  = 500;
	$w_slug_lk   = 350;
	$w_title_pos = 1;

	$score_sql = sprintf(
		'( (%s) * %d + (%s) * %d + (%s) * %d + (%s) * %d + (%s) * %d + (%s) * %d )',
		implode(' + ', $slug_eq)    ?: '0', $w_slug_eq,
		implode(' + ', $title_eq)   ?: '0', $w_title_eq,
		implode(' + ', $slug_pref)  ?: '0', $w_slug_pref,
		implode(' + ', $title_like) ?: '0', $w_title_lk,
		implode(' + ', $slug_like)  ?: '0', $w_slug_lk,
		implode(' + ', $title_pos)  ?: '0', $w_title_pos
	);

	$clauses['fields'] .= ",
		{$score_sql} AS _ts_boost,
		CHAR_LENGTH({$wpdb->posts}.post_title) AS _tlen,
		CHAR_LENGTH({$wpdb->posts}.post_name)  AS _slen";

	// Add to $clauses['fields'] along with _ts_boost:
	$clauses['fields'] .= ",
	LEAST(200, GREATEST(0, 200 - (TIMESTAMPDIFF(DAY, {$wpdb->posts}.post_date, NOW()) * (200/180)))) AS _fresh_boost
	";

	$existing = ! empty( $clauses['orderby'] ) ? ', ' . $clauses['orderby'] : '';
	//$clauses['orderby'] = "_ts_boost DESC, _tlen ASC, _slen ASC{$existing}";
	//$clauses['orderby'] = "_ts_boost DESC, {$wpdb->posts}.post_date DESC, _tlen ASC, _slen ASC{$existing}";

	//boost newer posts
	$clauses['orderby'] = "{$wpdb->posts}.post_date DESC, _ts_boost DESC, _tlen ASC, _slen ASC{$existing}";

	return $clauses;
}, 20000, 2 );
*/




/** 3) After-query cleaner: drop results where the keyword occurs ONLY inside VC button shortcodes */
add_filter('the_posts', function (array $posts, WP_Query $q) {
	if (is_admin() || ! $q->is_search()) {
		return $posts;
	}
	if (empty($posts)) {
		return $posts;
	}

	$needle = mb_strtolower(trim((string)$q->get('s')));
	if ($needle === '') {
		return $posts;
	}

	$needles = array_unique(array_filter([
		$needle,
		my_singularize_term($needle),
	]));

	$filtered = [];

	foreach ($posts as $p) {
		$title_raw = mb_strtolower(trim((string)get_the_title($p)));
		$slug_raw = mb_strtolower(trim((string)$p->post_name));
		$excerpt_raw = mb_strtolower(trim((string)wp_strip_all_tags(get_the_excerpt($p))));
		// normalize (singularize) the fields too
		$title_sg = my_singularize_term($title_raw);
		$slug_sg = my_singularize_term($slug_raw);
		$excerpt_sg = my_singularize_term($excerpt_raw);
		$hits_core = false;

		foreach ($needles as $n) {
			if ($n === '') {
				continue;
			}

			// also normalize the needle (just in case)
			$n_sg = my_singularize_term($n);

			// Exact OR contained match in EITHER direction, using raw + singular forms
			if (
				$title_raw === $n || $title_raw === $n_sg || $title_sg === $n || $title_sg === $n_sg ||
				mb_strpos($title_raw, $n) !== false || mb_strpos($title_raw, $n_sg) !== false ||
				mb_strpos($n, $title_raw) !== false || mb_strpos($n_sg, $title_raw) !== false ||

				$slug_raw === $n || $slug_raw === $n_sg || $slug_sg === $n || $slug_sg === $n_sg ||
				mb_strpos($slug_raw, $n) !== false || mb_strpos($slug_raw, $n_sg) !== false ||
				mb_strpos($n, $slug_raw) !== false || mb_strpos($n_sg, $slug_raw) !== false ||

				mb_strpos($excerpt_raw, $n) !== false || mb_strpos($excerpt_raw, $n_sg) !== false ||
				mb_strpos($excerpt_sg, $n) !== false || mb_strpos($excerpt_sg, $n_sg) !== false
			) {
				$hits_core = true;
				break;
			}
		}

		if ($hits_core) {
			$filtered[] = $p;
			continue;
		}

		// 2) Otherwise: allow content match ONLY if it's in a heading (h1-h6)
		$content_raw = (string)$p->post_content;

		// Remove VC buttons etc. if you want (optional):
		// $content_raw = preg_replace( '/\[(vc_btn|vc_button)\b[^\]]*\]/i', ' ', $content_raw );

		// Render shortcodes so headings generated by builders become <h*> in HTML
		//$content_html = do_shortcode( $content_raw );
		ob_start();
		$content_html = do_shortcode($content_raw);
		$leaked = ob_get_clean(); // anything echoed by shortcodes gets trapped here

		// Extract headings text
		preg_match_all('/<h[1-6][^>]*>(.*?)<\/h[1-6]>/is', $content_html, $hm);
		$headings_text = '';
		if (! empty($hm[1])) {
			$headings_text = mb_strtolower(wp_strip_all_tags(implode(' ', $hm[1])));
		}

		$hits_heading = false;
		foreach ($needles as $n) {
			if ($n !== '' && $headings_text !== '' && mb_strpos($headings_text, $n) !== false) {
				$hits_heading = true;
				break;
			}
		}

		if ($hits_heading) {
			$filtered[] = $p;
		}
		// else: drop it
	}

	// Optional: store counts for debugging (note: pagination totals won't auto-adjust)
	$q->set('filtered_post_count', count($filtered));
	$q->set('total_found_posts', (int)$q->found_posts);

	return $filtered;
}, 9999, 2);
