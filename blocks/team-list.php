<?php
/*
 * Team Listing Block Template.
 */
$id = get_field('section_id');
$text = get_field('text');
?>
<section class="team-section" id="<?php echo $id; ?>">
	<div class="container">
		<?php
		if (!empty($text)) {
			echo '<div class="row">';
			echo '<div class="col-12">';
			echo '<div class="section-title">';
			echo wpautop($text);
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
if (have_rows('team_sector')) {
	while (have_rows('team_sector')) {
		the_row();
		$title = get_sub_field('sector_title');
		$members = get_sub_field('members');
		if (!empty($title)) {
			echo '<div class="row">';
			echo '<div class="col-12">';
			echo '<div class="text-block">';
			echo '<h2>' . $title . '</h2>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
		if ($members) {
			echo '<div class="row">';
			foreach ($members as $member) {
				$img = wp_get_attachment_url(get_post_thumbnail_id($member->ID) ?: 0) ?: '';
				$header = get_the_title($member->ID);
				$bio = get_field('biography', $member->ID);
				$pos = get_field('position', $member->ID);
				$desc = get_field('position_description', $member->ID);
				$social = get_field('social_links', $member->ID);
				echo '<div class="col-lg-4 col-md-6 spacer">';
				echo '<div class="member-holder">';
				if (!empty($img)) {
					echo '<div class="member-img" style="background-image:url(' . $img . ');"></div>';
				} else {
					echo '<div class="member-img" style="background-image:url(' . get_stylesheet_directory_uri() . '/dist/img/person.jpg);"></div>';
				}

				echo '<div class="member-body">';
				if (!empty($header)) {
					echo '<h4>' . $header . '</h4>';
				}
				if (!empty($pos)) {
					echo '<h5>' . $pos . '</h5>';
				}
				if (!empty($desc)) {
					echo '<h6>' . $desc . '</h6>';
				}
				if (!empty($bio)) {
					echo wpautop($bio);
				}
				if ($social) {
					echo '<ul>';
					foreach ($social as $sm) {
						$icon = $sm['social_media_icon'];
						$link = $sm['social_media_link'];
						echo '<li>';
						echo '<a target="_blank" href="' . $link . '">';
						echo '<img src="' . $icon . '" alt=""/>';
						echo '</a>';
						echo '</li>';
					}
					echo '</ul>';
				}
				echo '</div>';
				echo '</div>';
				echo '</div>';
			}
			echo '</div>';
		}
	}
}
?>
	</div>
</section>
