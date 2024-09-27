<?php
/*
 * Content Cards Block Template.
 */
$id = get_field('section_id');
?>
<section class="cards-section" id="<?php echo $id; ?>">
	<div class="container">
		<?php
		if (have_rows('card_group')) {
			while (have_rows('card_group')) {
				the_row();

				#instructions-server
				$gid = get_sub_field('card_id');
				$gicon = get_sub_field('group_icon');
				$gtitle = get_sub_field('group_title');
				$gtext = get_sub_field('group_text');

				echo '<div class="row spacer" id="'.$gid.'">';
				echo '<div class="col-lg-3">';
				echo '<div class="group-block">';
				if (!empty($gicon)) {
					echo '<img src="' . $gicon . '" alt=""/>';
				}
				if (!empty($gtitle)) {
					echo '<h6>' . $gtitle . '</h6>';
				}
				if (!empty($gtext)) {
					echo wpautop($gtext);
				}
				echo '</div>';
				echo '</div>';
				echo '<div class="col-lg-8 offset-lg-1">';
				if (have_rows('cards')) {
					while (have_rows('cards')) {
						the_row();
						$card_title = get_sub_field('card_title');
						$card_icon = get_sub_field('card_icon');
						$card_new_label = get_sub_field('new_label');

						echo '<div class="card-block">';
						echo '<div class="card-head">';
						if (!empty($card_icon)) {
							echo '<img src="' . $card_icon . '" alt=""/>';
						}
						if (!empty($card_title)) {
							echo '<h6>' . $card_title . '</h6>';
						}
						if (!empty($card_new_label)) {
							echo '<span class="new_label">'.__('New', 'nextcloud').'</span>';
						}

						echo '</div>';
						echo '<div class="card-main">';
						if (have_rows('card_content')) {
							while (have_rows('card_content')) {
								the_row();
								if (get_row_layout() == 'text_content') {
									$text = get_sub_field('text');
									echo '<div class="text-row">';
									echo $text;
									echo '</div>';
								} elseif (get_row_layout() == 'button') {
									$type = get_sub_field('button_type');
									$link = get_sub_field('link');
									if ($link) {
										$link_url = $link['url'];
										$link_title = $link['title'];
										$link_target = $link['target'] ? $link['target'] : '_self';


										//echo '<div class="card-buttons">';

										if ($type == 'light') {
											echo '<a class="a-btn btn-light" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
										} elseif ($type == 'dark') {
											echo '<a class="a-btn btn-dark" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
										} elseif ($type == 'windows') {
											echo '<a class="a-btn btn-light winOS" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">
											<i class="fab fa-windows"></i>' . esc_html($link_title) .'</a>';
										} elseif ($type == 'apple') {
											echo '<a class="a-btn btn-light macOS" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">
											<i class="fab fa-apple"></i>' . esc_html($link_title) . '</a>';
										} elseif ($type == 'linux') {
											echo '<a class="a-btn btn-light unixOS" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">
											<i class="fab fa-linux"></i>' . esc_html($link_title) . '</a>';
										} elseif ($type == 'docx') {
											echo '<a class="a-btn btn-docx" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">
											<i class="fas fa-book"></i>' . esc_html($link_title) . '</a>';
										} elseif ($type == 'source') {
											echo '<a class="a-btn btn-source" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">
											<i class="fas fa-code-branch"></i>' . esc_html($link_title) . '</a>';
										} elseif ($type == 'playstore') {
											echo '<a class="i-btn playstore" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '"></a>';
										} elseif ($type == 'droid') {
											echo '<a class="i-btn f-droid" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '"></a>';
										} elseif ($type == 'appstore') {
											echo '<a class="i-btn appstore" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '"></a>';
										} elseif ($type == 'android') {
											echo '<a class="a-btn btn-android" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">
											<i class="fab fa-android"></i>' . esc_html($link_title) . '</a>';
										} elseif ($type == 'ios') {
											echo '<a class="a-btn btn-ios" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">
											<i class="fab fa-apple"></i>' . esc_html($link_title) . '</a>';
										} else {
										}

										//echo '</div>';
									}
								} else {
								}
							}
						}
						echo '</div>';
						echo '</div>';
					}
				}
				echo '</div>';
				echo '</div>';
			}
		}
		?>
	</div>
</section>