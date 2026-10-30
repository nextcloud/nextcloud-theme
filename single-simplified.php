<?php
/*
 * Template Name: Simplified Article
 * Template Post Type: post, page, event, recording, on-demand-webinar
 */

get_header();
?>
<div class="wrapper" id="main">
	<?php
	$ids = [];
while (have_posts()) : the_post();

	//if is event - webinar
	if (get_post_type() == 'event') {
		//is event
		$post_id = get_the_ID();

		$event_start_datetime = get_field('event_start_date_and_time', $post_id, false);
		$event_end_datetime = get_field('event_end_date_and_time', $post_id, false);
		$date_start_format = date_i18n('c', strtotime($event_start_datetime));

		if ($event_end_datetime) {
			$date_end_format = date_i18n('c', strtotime($event_end_datetime));
		} else {
			$date_end_format = $date_start_format;
		}

	
		$location_field = get_field('location'); // Example: "Berlin, Germany"
		if ($location_field) {
			$parts = explode(',', $location_field);

			$city = isset($parts[0]) ? trim($parts[0]) : '';
			$country_name = isset($parts[1]) ? trim($parts[1]) : '';

			// Simple map of country names → ISO codes
			$country_codes = [
				'Germany' => 'DE',
				'France' => 'FR',
				'United States' => 'US',
				'Italy' => 'IT',
				'Spain' => 'ES',
				// Add more as needed
			];

			$country_code = isset($country_codes[$country_name]) ? $country_codes[$country_name] : $country_name; // fallback to name
		}
		?> 

<script type="application/ld+json">
  {
  "@context": "https://schema.org",
  "@type": "Event",
  "name": "<?php echo get_the_title(); ?>",
  "startDate": "<?php echo $date_start_format; ?>",
  "endDate": "<?php echo $date_end_format; ?>",
  "eventAttendanceMode": "https://schema.org/OnlineEventAttendanceMode",
  "eventStatus": "https://schema.org/EventScheduled",
  	<?php if (has_term('webinars', 'event_categories')) { ?>
		"location": {
			"@type": "VirtualLocation",
			"url": "<?php echo get_the_permalink(); ?>"
		},
	<?php } elseif (has_term('exhibition', 'event_categories')) { ?>
		"location": {
			"@type": "Place",
			"name": "Zukunftskongress",
			"address": {
			"@type": "PostalAddress",
			"addressLocality": "<?php echo esc_html($city); ?>",
  			"addressCountry": "<?php echo esc_html($country_code); ?>"
			}
		},
<?php } ?>
  <?php if (has_post_thumbnail()) { ?> 
  "image": [
    "<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>"
  ],
  <?php } ?>
  "description": "<?php echo get_the_excerpt(); ?>",
  "organizer": {
    "@type": "Organization",
    "name": "Nextcloud GmbH",
    "url": "https://nextcloud.com"
  },
  "offers": {
    "@type": "Offer",
    "url": "<?php echo get_the_permalink(); ?>",
    "price": "0",
    "priceCurrency": "EUR",
    "availability": "https://schema.org/InStock"
  }
}
</script>
<?php
	}
	?>


		<section class="post-single-section">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-lg-10 col-md-12">
						<div class="text-block">
						<?php
							echo do_shortcode(apply_filters('the_content', get_the_content()));
	?>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php
endwhile; // End of the loop.
?>
</div>
<?php
get_footer();
