<?php
/*
 * Single Post Content Block Template.
 */
$text = get_field('content');
?>
<section class="post-single-section">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="text-block">
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