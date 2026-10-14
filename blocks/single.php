<?php
/*
 * Single Post Content Block Template.
 */
$text = get_field('content');
$custom_css_class = get_field('custom_css_class');
?>
<section class="post-single-section">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="text-block <?php if(isset($custom_css_class)) echo $custom_css_class; ?>">
					<?php
					if (!empty($text)) {
						echo $text;
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>