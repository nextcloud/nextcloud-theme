<?php
/*
 * Industries Content Block Template.
 */
$id = get_field('section_id');
$tagline = get_field('tagline');
$title = get_field('title');
$text = get_field('text');
$columns = get_field('no_of_columns');
$css = get_field('custom_css_classes');

$col_class = '';
if ($columns == 4) {
	$col_class = 'col-lg-3';
} else if ($columns == 3) {
	$col_class = 'col-lg-4';
} 
else if ($columns == 2) {
	$col_class = 'col-lg-6';
} 
else {
	$col_class = 'col-lg-4';
}

if( isset( $block['data']['preview_image_help'] )  ) :    /* rendering in inserter preview  */
    echo '<img src="'. $block['data']['preview_image_help'] .'" style="width:100%; height:auto;">';
else : /* rendering in editor body */
?>
<section class="industries-section <?php echo $css; ?>" id="<?php echo $id; ?>">
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
					echo '<div class="'.$col_class.' col-md-6 spacer">';
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
<?php endif; ?>