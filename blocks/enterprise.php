<?php
/*
 * Enterprise Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$text = get_field('text');
$subtext = get_field('subtext');
?>
<section class="enterprise-section" id="<?php echo $id; ?>">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="section-title">
					<?php
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
		<?php
		if (have_rows('enterprise_list')) {
			$i = 1;
			echo '<div class="enterprise-slider">';
			while (have_rows('enterprise_list')) {
				the_row();
				$icon = get_sub_field('icon');
				$header = get_sub_field('the_title');
				$cont = get_sub_field('the_text');
				echo '<div>';
				echo '<div class="item-box box-' . $i . '">';
				if (!empty($icon)) {
					echo '<img src="' . $icon . '" alt=""/>';
				}
				if (!empty($header)) {
					echo '<h4>' . $header . '</h4>';
				}
				if (!empty($cont)) {
					echo wpautop($cont);
				}
				echo '</div>';
				echo '</div>';
				$i++;
				if ($i == 3) {
					$i = 1;
				}
			}
			echo '</div>';
		}
?>
		<?php
if (!empty($subtext)) {
	echo '<div class="row">';
	echo '<div class="col-12">';
	echo '<div class="text-block">';
	echo wpautop($subtext);
	echo '</div>';
	echo '</div>';
	echo '</div>';
}
?>
	</div>
</section>