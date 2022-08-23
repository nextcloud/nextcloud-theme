<?php
/*
 * Page Hero Block Template.
 */
$title = get_field('title');
$text = get_field('text');
$link = get_field('link');
$bg_top = get_field('background_top');
$bg_bottom = get_field('background_bottom');
if (!empty($bg_top)) {
	echo '<section class="page-hero-section page-hero-top-section" style="background-image: url(' . $bg_top . ');">';
} else {
	echo '<section class="page-hero-section gr">';
}
?>
<div class="container">
	<div class="row">
		<div class="col-lg-9">
			<div class="section-title">
				<?php
				if (!empty($title)) {
					echo '<h1>' . $title . '</h1>';
				}
				if ($link) {
					$link_url = $link['url'];
					$link_title = $link['title'];
					$link_target = $link['target'] ? $link['target'] : '_self';
					echo '<a class="c-btn" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
				}
				?>
			</div>
		</div>
	</div>
</div>
</section>
<?php
if (!empty($bg_bottom)) {
					echo '<section class="page-hero-bottom-section" style="background-image: url(' . $bg_bottom . ');">';
					if (!empty($text)) { ?>
		<div class="container">
			<div class="row">
				<div class="col-lg-9">
					<div class="section-title">
						<?php echo '<h2>' . $text . '</h2>'; ?>
					</div>
				</div>
			</div>
		</div>
	<?php
	}
					echo '</section>';
				}
