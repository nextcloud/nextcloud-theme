<?php
/*
 * Text 2 Block Template.
 */
$id = get_field('section_id');
$tagline = get_field('tagline');
$title = get_field('title');
$subtext = get_field('subtext');
$content = get_field('content');
?>
<section class="text2-section" id="<?php echo $id; ?>">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="section-title">
					<?php
					if (!empty($tagline)) {
						echo '<h6>' . $tagline . '</h6>';
					}
if (!empty($title)) {
	echo '<h2>' . $title . '</h2>';
}
if (!empty($subtext)) {
	echo '<p>' . $subtext . '</p>';
}
?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<div class="text-block">
					<?php
echo $content;
?>
				</div>
			</div>
		</div>
	</div>
</section>