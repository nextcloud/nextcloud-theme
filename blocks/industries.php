<?php
/*
 * Industries Content Block Template.
 */
$id = get_field('section_id');
$tagline = get_field('tagline');
$title = get_field('title');
$text = get_field('text');
?>
<section class="industries-section" id="<?php echo $id; ?>">
	<div class="container">
		<?php
		if (!empty($tagline)) {
			echo '<div class="row justify-content-center">';
			echo '<div class="col-lg-6">';
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
					echo '<div class="col-lg-4 col-md-6 spacer">';
					echo '<div class="item-box">';
					if (!empty($icon)) {
						echo '<img src="' . $icon . '" alt=""/>';
					}
					if (!empty($header)) {
						echo '<h4>' . $header . '</h4>';
					}
					if (!empty($desc)) {
						echo wpautop($desc);
					}
					echo '</div>';
					echo '</div>';
				}
			}
			?>
		</div>
	</div>
</section>