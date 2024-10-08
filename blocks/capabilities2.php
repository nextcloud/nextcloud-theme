<?php
/*
 * Capabilities 2 Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$text = get_field('text');
$link = get_field('link');

if(isset($block['data']['preview_image_help'])) :    /* rendering in inserter preview  */
	echo '<img src="'. $block['data']['preview_image_help'] .'" style="width:100%; height:auto;">';
else : /* rendering in editor body */
	?>
<section class="capabilities2-section" id="<?php echo $id; ?>">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="section-title">
					<?php
						if (!empty($title)) {
							echo '<h2>' . $title . '</h2>';
						}
	?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<?php
				if (have_rows('capabilities_list')) {
					echo '<ul class="capabilities-list">';
					while (have_rows('capabilities_list')) {
						the_row();
						$bullet = get_sub_field('list_item');
						echo '<li>' . $bullet . '</li>';
					}
					echo '</ul>';
				}
	?>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<?php
	if (have_rows('notation_list')) {
		echo '<ul class="notation-list">';
		while (have_rows('notation_list')) {
			the_row();
			$bullet = get_sub_field('list_item');
			echo '<li>' . $bullet . '</li>';
		}
		echo '</ul>';
	}
	?>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<div class="button-block">
					<?php
		if (!empty($text)) {
			echo wpautop($text);
		}
	if ($link) {
		$link_url = $link['url'];
		$link_title = $link['title'];
		$link_target = $link['target'] ? $link['target'] : '_self';
		echo '<a class="c-btn btn-blue" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
	}
	?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>