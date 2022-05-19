<?php
/*
 * Trial Form Block Template.
 */
$id = get_field('section_id');
?>
<section class="trial-section" id="<?php echo $id; ?>">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                
				<?php echo do_shortcode('[contact-form-7 id="16083" title="Trial form hip"]');  ?>
            </div>
        </div>
    </div>
</section>