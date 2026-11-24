<?php
/**
 * Branded emails
 *
 * Wraps every email WordPress sends (wp_mail) in the Nextcloud HTML template.
 * Replaces the "Email Templates" plugin (WPExperts.io).
 *
 * Location: wp-content/themes/nextcloud-theme/inc/branded-emails.php
 * Loaded from functions.php:
 *   require_once get_template_directory() . '/inc/branded-emails.php';
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------------------------------
 * 1. Design settings – the equivalent of the plugin's customizer panel
 * ---------------------------------------------------------------------- */
function nc_email_config() {
	return apply_filters( 'nc_email_config', [
		'width'       => 600,                 // "Email body size" (boxed)
		'radius'      => 5,                   // "Border radius"
		'outer_pad'   => 40,                  // "Padding top/bottom" around the box
		'shadow'      => '0 2px 4px rgba(0,0,0,.12)', // ignored by most clients, fine where supported
		'font'        => 'Helvetica, Arial, sans-serif',

		'outer_bg'    => '#F2F9FC',           // $veryLightBG – rgba(0,130,201,0.05) on white, as solid hex (Outlook ignores rgba)
		'header_bg'   => '#0082C9',           // Nextcloud blue
		'logo'        => 'https://nextcloud.com/c/uploads/2024/07/logo_nextcloud_white.png', // PNG, absolute URL – no SVG (Gmail/Outlook drop it)
		'logo_width'  => 140,
		'logo_alt'    => get_bloginfo( 'name' ),

		'body_bg'     => '#ffffff',
		'text'        => '#222222',
		'link'        => '#0082c9',
		'font_size'   => 16,

		'footer_bg'   => '#DBEEF7',           // $lightBG – rgba(0,130,201,0.14) on white, as solid hex
		'footer_text' => '#555555',
		'footer_size' => 12,
		'footer'      => '&copy; ' . gmdate( 'Y' ) . ' ' . esc_html( get_bloginfo( 'name' ) ),
	] );
}

/* -------------------------------------------------------------------------
 * 2. Hook into every outgoing email
 * ---------------------------------------------------------------------- */
add_filter( 'wp_mail', 'nc_email_wrap', 20 );

function nc_email_wrap( $args ) {
	nc_email_alt_body( '' );

	// Escape hatch: add_filter( 'nc_email_skip', fn( $skip, $args ) => ..., 10, 2 );
	if ( apply_filters( 'nc_email_skip', false, $args ) ) {
		return $args;
	}

	// Already a full HTML document (e.g. WooCommerce, newsletter tools)? Leave it alone.
	if ( preg_match( '/<html|<body/i', $args['message'] ) ) {
		return $args;
	}

	// Normalise headers to an array and find out if the mail is already HTML.
	$headers = $args['headers'];
	if ( ! is_array( $headers ) ) {
		$headers = $headers ? explode( "\n", str_replace( "\r\n", "\n", $headers ) ) : [];
	}

	$is_html = false;
	foreach ( $headers as $i => $header ) {
		if ( stripos( trim( $header ), 'content-type:' ) === 0 ) {
			$is_html = stripos( $header, 'text/html' ) !== false;
			unset( $headers[ $i ] );
		}
	}
	$headers[] = 'Content-Type: text/html; charset=UTF-8';

	// Many plugins (e.g. Broken Link Checker) don't set an HTML header. They switch to HTML via the
	// wp_mail_content_type filter instead, or just send HTML. Catch both, so their markup isn't escaped.
	if ( ! $is_html ) {
		$is_html = stripos( (string) apply_filters( 'wp_mail_content_type', 'text/plain' ), 'text/html' ) !== false
			|| nc_email_looks_like_html( $args['message'] );
	}

	// Plain-text fallback for clients/spam filters that like multipart mails.
	nc_email_alt_body( $is_html ? wp_strip_all_tags( $args['message'] ) : $args['message'] );

	$args['message'] = nc_email_build( $args['message'], $is_html, $args['subject'] );
	$args['headers'] = array_values( $headers );

	return $args;
}

/* Adds the plain-text part (AltBody) to the mail we just wrapped. */
add_action( 'phpmailer_init', function ( $phpmailer ) {
	$alt = nc_email_alt_body();
	if ( $alt ) {
		$phpmailer->AltBody = $alt;
	}
	nc_email_alt_body( '' );
} );

/* Real HTML tags only – core's "<https://…>" links in plain-text mails don't count. */
function nc_email_looks_like_html( $message ) {
	return (bool) preg_match( '#</?(p|br|a|div|span|table|tr|td|strong|b|em|i|ul|ol|li|h[1-6]|img)\b[^>]*>#i', $message );
}

function nc_email_alt_body( $set = null ) {
	static $alt = '';
	if ( null !== $set ) {
		$alt = $set;
	}
	return $alt;
}

/* -------------------------------------------------------------------------
 * 3. Turn the message into HTML and put it in the template
 * ---------------------------------------------------------------------- */
function nc_email_build( $message, $is_html, $subject = '' ) {
	if ( ! $is_html ) {
		// Core wraps links in <…> (e.g. password reset) – those would vanish as "tags".
		$message = preg_replace( '#<(https?://[^>\s]+)>#i', '$1', $message );
		$message = wpautop( make_clickable( esc_html( $message ) ) );
	}

	// Long URLs shown as link text (e.g. password reset) get <wbr> break points
	// so they wrap in every client – Gmail, Apple Mail and Outlook ignore overflow-wrap.
	// Only the visible text changes; the href stays untouched.
	$message = preg_replace_callback(
		'#(<a\s[^>]*>)(https?://[^<]{40,})(</a>)#i',
		function ( $m ) {
			// Break after single slashes, ?, = and whole &amp; entities (never inside an entity or "//").
			return $m[1] . preg_replace( '#(&amp;|[?=]|(?<!/)/(?!/))#', '$1<wbr>', $m[2] ) . $m[3];
		},
		$message
	);

	return nc_email_template( $message, $subject );
}

function nc_email_template( $content, $subject ) {
	$c = nc_email_config();
	ob_start();
	?>
<!doctype html>
<html lang="<?php echo esc_attr( get_bloginfo( 'language' ) ); ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
<title><?php echo esc_html( $subject ); ?></title>
<style>
	a { color: <?php echo esc_attr( $c['link'] ); ?>; }
	.nc-content p { margin: 0 0 16px; }
	.nc-content a { word-break: break-all; } /* long URLs (e.g. password reset) wrap instead of forcing horizontal scroll */
	.nc-content h1, .nc-content h2, .nc-content h3 { margin: 24px 0 12px; line-height: 1.3; }
	@media (max-width: <?php echo (int) $c['width'] + 20; ?>px) {
		.nc-box { width: 100% !important; border-radius: 0 !important; }
		.nc-pad { padding-left: 20px !important; padding-right: 20px !important; }
	}
</style>
</head>
<body style="margin:0;padding:0;width:100%;background:<?php echo esc_attr( $c['outer_bg'] ); ?>;-webkit-text-size-adjust:100%;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="<?php echo esc_attr( $c['outer_bg'] ); ?>" style="background:<?php echo esc_attr( $c['outer_bg'] ); ?>;">
	<tr>
		<td align="center" style="padding:<?php echo (int) $c['outer_pad']; ?>px 10px;">

			<!--[if mso]><table role="presentation" width="<?php echo (int) $c['width']; ?>" cellpadding="0" cellspacing="0" border="0"><tr><td><![endif]-->
			<!-- Fluid box: 100% wide on small screens, capped at the max width – no horizontal scrolling -->
			<table role="presentation" class="nc-box" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="<?php echo esc_attr( $c['body_bg'] ); ?>"
				style="width:100%;max-width:<?php echo (int) $c['width']; ?>px;background:<?php echo esc_attr( $c['body_bg'] ); ?>;border-radius:<?php echo (int) $c['radius']; ?>px;overflow:hidden;box-shadow:<?php echo esc_attr( $c['shadow'] ); ?>;">

				<!-- Header -->
				<tr>
					<td align="center" bgcolor="<?php echo esc_attr( $c['header_bg'] ); ?>" style="background:<?php echo esc_attr( $c['header_bg'] ); ?>;padding:32px 20px;">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="text-decoration:none;">
							<img src="<?php echo esc_url( $c['logo'] ); ?>" width="<?php echo (int) $c['logo_width']; ?>" alt="<?php echo esc_attr( $c['logo_alt'] ); ?>"
								style="display:block;border:0;width:<?php echo (int) $c['logo_width']; ?>px;max-width:100%;height:auto;color:#ffffff;font-family:<?php echo esc_attr( $c['font'] ); ?>;font-size:20px;">
						</a>
					</td>
				</tr>

				<!-- Body -->
				<tr>
					<td class="nc-pad nc-content" style="padding:32px 40px;font-family:<?php echo esc_attr( $c['font'] ); ?>;font-size:<?php echo (int) $c['font_size']; ?>px;line-height:1.5;color:<?php echo esc_attr( $c['text'] ); ?>;word-break:break-word;overflow-wrap:anywhere;">
						<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in nc_email_build() or trusted HTML from the sender ?>
					</td>
				</tr>

				<!-- Footer -->
				<tr>
					<td align="center" class="nc-pad" bgcolor="<?php echo esc_attr( $c['footer_bg'] ); ?>" style="background:<?php echo esc_attr( $c['footer_bg'] ); ?>;padding:20px 40px;font-family:<?php echo esc_attr( $c['font'] ); ?>;font-size:<?php echo (int) $c['footer_size']; ?>px;line-height:1.5;color:<?php echo esc_attr( $c['footer_text'] ); ?>;">
						<?php echo wp_kses_post( $c['footer'] ); ?>
					</td>
				</tr>

			</table>
			<!--[if mso]></td></tr></table><![endif]-->

		</td>
	</tr>
</table>
</body>
</html>
	<?php
	return ob_get_clean();
}

/* -------------------------------------------------------------------------
 * 4. Browser preview for admins:
 *    /wp-admin/admin-post.php?action=nc_email_preview
 * ---------------------------------------------------------------------- */
add_action( 'admin_post_nc_email_preview', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Not allowed.' );
	}

	$sample = "Hi there,\n\n"
		. "This is a preview of the branded email template. Plain-text emails from WordPress core get paragraphs and clickable links automatically.\n\n"
		. "Example link (as core sends it):\n<" . wp_login_url() . "?action=rp&key=abc123>\n\n"
		. "Thanks,\nThe " . get_bloginfo( 'name' ) . " team";

	echo nc_email_build( $sample, false, 'Email preview' ); // phpcs:ignore
	exit;
} );