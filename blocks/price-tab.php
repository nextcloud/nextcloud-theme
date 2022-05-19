<?php
/*
 * Pricing Tab Block Template.
 */
$id = get_field('section_id');
$title = get_field('title');
$subtext = get_field('subtext');
?>
<section class="price-tab-section" id="<?php echo $id; ?>">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <?php
					if (!empty($title)) {
						echo '<h2>' . $title . '</h2>';
					}
					if (!empty($subtext)) {
						echo $subtext;
					}
					?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="tabs-block">
                    <?php
					if (have_rows('plans')) {
						$i = 1;
						echo '<ul class="tab-buttons">';
						while (have_rows('plans')) {
							the_row();
							$plan = get_sub_field('plan_name');
							if ($i == 1) {
								echo '<li><button class="active" id="plan' . $i . '">' . $plan . '</button></li>';
							} else {
								echo '<li><button id="plan' . $i . '">' . $plan . '</button></li>';
							}
							$i++;
						}
						echo '</ul>';
					}
					if (have_rows('plans')) {
						$x = 1;
						while (have_rows('plans')) {
							the_row();
							$plan = get_sub_field('plan_name');
							$plan_desc = get_sub_field('plan_description');
							$link = get_sub_field('plan_link');
							echo '<div class="plan-holder box' . $x . '" data-plan="plan' . $x . '">';
							echo '<div class="plan-box">';
							echo '<div class="plan-head">';
							echo '<h3>' . $plan . '</h3>';
							echo '<p>' . $plan_desc . '</p>';
							echo '</div>';
							echo '<div class="plan-body">';
							if (have_rows('plan_feats')) {
								while (have_rows('plan_feats')) {
									the_row();
									$feat_title = get_sub_field('feat_title');
									echo '<div class="plan-part">';
									echo '<h4>' . $feat_title . '</h4>';
									if (have_rows('feat_list')) {
										echo '<ul>';
										while (have_rows('feat_list')) {
											the_row();
											$item = get_sub_field('list_item');
											$tr = get_sub_field('transparent');
											if ($tr) {
												echo '<li class="tr">' . $item . '</li>';
											} else {
												echo '<li>' . $item . '</li>';
											}
										}
										echo '</ul>';
										echo '</div>';
									}
								}
							}
							echo '<div class="plan-foot">';
							if ($link) {
								$link_url = $link['url'];
								$link_title = $link['title'];
								$link_target = $link['target'] ? $link['target'] : '_self';
								echo '<a class="c-btn btn-blue" href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '">' . esc_html($link_title) . '</a>';
							}
							echo '</div>';
							echo '</div>';
							echo '</div>';
							echo '</div>';
							$x++;
						}
					}
					?>
                </div>
            </div>
        </div>
    </div>
</section>