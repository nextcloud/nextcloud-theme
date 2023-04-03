<?php
if (have_comments()) : ?>

    <div class="section-title text-center">
		<h3><?php echo __('Comments','nextcloud'); ?></h3>
	</div>

    <ul class="post-comments">
        <?php
            wp_list_comments(array(
                'style'       => 'ul',
                'short_ping'  => true,
				'callback' => 'nc_better_comments'
            ));
        ?>
    </ul>
<?php endif;