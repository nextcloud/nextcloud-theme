<?php
/*
 * Industries Content Block Template.
 */
$id = get_field('section_id');
$tagline = get_field('tagline');
$title = get_field('title');
$icon = get_field('icon');
$text = get_field('text');
$columns = get_field('no_of_columns');
$css = get_field('custom_css_classes');

$col_class = '';
if ($columns == 4) {
	$col_class = 'col-lg-3';
} elseif ($columns == 3) {
	$col_class = 'col-lg-4';
} elseif ($columns == 2) {
	$col_class = 'col-lg-6';
} else {
	$col_class = 'col-lg-4';
}

if (isset($block['data']['preview_image_help'])) :    /* rendering in inserter preview  */
	echo '<img src="' . $block['data']['preview_image_help'] . '" style="width:100%; height:auto;">';
else : /* rendering in editor body */
	?>
<section class="industries-section <?php echo $css; ?>" id="<?php echo $id; ?>">
	<div class="container">
		<?php
			if (!empty($tagline)) {
				echo '<div class="row justify-content-center">';
				echo '<div class="col-lg-8">';
				echo '<div class="section-title">';
				echo '<p>' . $tagline . '</p>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
			}
	?>
		<div class="row">
			<div class="col-12">
				<div class="text-block">
					<?php
				if (!empty($icon)) {
					echo '<div class="icon"><img src="' . $icon['url'] . '" alt="Nextcloud - ' . $title . '"/></div>';
				}

	if (!empty($title)) {
		echo '<h2>' . $title . '</h2>';
	}
	if (!empty($text)) {
		echo wpautop($text);
	}
	?>
				</div>
			</div>
		</div>
		<div class="row">
			<?php
			if (have_rows('industries_list')) {
				while (have_rows('industries_list')) {
					the_row();
					$icon = get_sub_field('icon');
					$header = get_sub_field('the_title');
					$desc = get_sub_field('the_text');
					$link = get_sub_field('link');
					$custom_css = '';
					if (!empty($link)) {
						$custom_css = 'with-link';
					}

					echo '<div class="' . $col_class . ' col-md-6 spacer">';
					echo '<div class="item-box ' . $custom_css . '">';
					
					if (!empty($link)) {
						echo '<a href="' . $link['url'] . '" title="' . $link['title'] . '" target="' . $link['target'] . '">';
					}

					if (!empty($icon)) {
						echo '<img src="' . $icon . '" alt="Nextcloud - ' . $header . '"/>';
					}
					if (!empty($header)) {
						echo '<h4>' . $header . '</h4>';
					}
					if (!empty($desc)) {
						echo wpautop($desc);
					}

					if (!empty($link)) {

						$label = __('Learn more', 'nextcloud');
						if ($link['title'] != '') {
							$label = $link['title'];
						}
						echo '<span class="see_more">' . $label . ' <svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 24 24"><path class="st0" d="M12.6,12l-4.6-4.6,1.4-1.4,6,6-6,6-1.4-1.4,4.6-4.6Z"></path></svg></span>';
						
						echo '</a>';
					}

					echo '</div>';
					echo '</div>';
				}
			}
	?>
		</div>
	</div>
</section>
<?php endif; ?>