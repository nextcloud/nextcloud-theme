<?php

/*
 * The template for displaying 404 pages (not found)
 */

get_header();
?>
<section class="vc_section titlebar blueGradient"><div class="overlay-gradient"></div><div class="container"><div class="vc_row wpb_row vc_row-fluid vc_row-o-content-middle vc_row-flex"><div class="wpb_column vc_column_container vc_col-sm-6"><div class="vc_column-inner"><div class="wpb_wrapper">
	<div class="wpb_text_column wpb_content_element  page-title centerMobile">
		<div class="wpb_wrapper">
		</div>
	</div>

	<div class="wpb_text_column wpb_content_element  vc_custom_1671547201626 page-subtitle centerMobile">
		<div class="wpb_wrapper">
			<p></p>
		</div>
	</div>
</div></div></div><div class="wpb_column vc_column_container vc_col-sm-6">
	<div class="vc_column-inner">
	<div class="wpb_wrapper">

</div></div></div></div></div>
</section>


<section class="section-404">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-12">
				<div class="text-block">
					<h1><?php echo __('404 - Page not Found', 'nextcloud'); ?></h1>
					<a href="<?php echo site_url(); ?>" class="c-btn"><?php echo __('Back', 'nextcloud'); ?></a>
				</div>
			</div>
		</div>
	</div>
</section>

<?php

get_footer();
