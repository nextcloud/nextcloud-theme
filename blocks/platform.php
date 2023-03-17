<?php
/*
 * Platform Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$subtext = get_field('subtext');
$text = get_field('text');
$link = get_field('link');
$img = get_field('image');
$video = get_field('video');
?>
<section class="platform-section" id="<?php echo $id; ?>">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="section-title">
					<?php
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
			<div class="col-lg-5">
				<div class="text-block">
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
			<div class="col-lg-7">
				<?php
				if (!empty($img)) {
					echo '<div class="image-block">';
					echo '<img src="' . $img . '" alt=""/>';
					echo '</div>';
				} elseif (!empty($video)) {
					echo '<div class="video-holder">';
					echo $video;
					echo '</div>';
				}
				?>
			</div>
		</div>
	</div>
</section>