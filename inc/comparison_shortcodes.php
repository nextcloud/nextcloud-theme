<?php
/**
 * Predefined comparison features.
 */
function nc_platform_comparison_features() {

	return [
		'main_features' => [
			__('Main features', 'nextcloud'),
			[
				'licensing' => __('Licensing', 'nextcloud'),
				'unlimited_storage_files' => __('Unlimited storage and amount of files', 'nextcloud'),
				'large_file_support' => __('Large file support', 'nextcloud'),
				'self_hosted_on_premises' => __('Self hosted / on premises', 'nextcloud'),
				'global_scalability' => __('Global scalability', 'nextcloud'),
			]
		],

		'clients' => [
			__('Clients', 'nextcloud'),
			[
				'mobile_clients' => __('Mobile clients', 'nextcloud'),
				'auto_upload' => __('Auto upload image/video/other files', 'nextcloud'),
				'desktop_clients' => __('Desktop clients', 'nextcloud'),
				//'lan_sync' => __('LAN synchronization', 'nextcloud'),
				'extensible_apps' => __('Extensible with apps', 'nextcloud'),
			]
		],

		'server_features' => [
			__('Server features', 'nextcloud'),
			[
				'unified_search' => __('Unified search', 'nextcloud'),
				'file_versioning' => __('File versioning', 'nextcloud'),
				'file_locking' => __('File locking / checkout', 'nextcloud'),
				'multiple_link_shares' => __('Multiple link shares', 'nextcloud'),
				'share_note' => __('Share note', 'nextcloud'),
				'dashboard' => __('Dashboard', 'nextcloud'),
				'file_preview' => __('View PDF, Images, Video, Gallery', 'nextcloud'),
				'integrated_chat' => __('Integrated Audio/Video/Text chat', 'nextcloud'),
				'integrated_groupware' => __('Integrated Groupware (Calendar/Contacts/Mail/Kanban)', 'nextcloud'),
				'mobile_calendar_contacts' => __('Mobile calendar/contact integration', 'nextcloud'),
				'online_office' => __('Online Office web / Mobile apps', 'nextcloud'),
				'knowledge_management' => __('Knowledge management', 'nextcloud'),
				'forms_surveys' => __('Forms and surveys', 'nextcloud'),
				'user_managed_groups' => __('User-managed groups', 'nextcloud'),
				'file_drop' => __('File Drop (customer file upload)', 'nextcloud'),
				'inter_server_sharing' => __('Inter-server sharing', 'nextcloud'),
				'workspace' => __('Workspace', 'nextcloud'),
			]
		],

		'accessibility' => [
			__('Accessibility', 'nextcloud'),
			[
				'keyboard_screen_reader' => __('Keyboard / screen reader support', 'nextcloud'),
				'wcag_22' => __('WCAG 2.2 support', 'nextcloud'),
				'dyslexia_font' => __('Dyslexia-friendly font', 'nextcloud'),
			]
		],

		'admin_features' => [
			__('Admin features', 'nextcloud'),
			[
				'acl' => __('Access control lists', 'nextcloud'),
				'workflow_automation' => __('Content workflow automation', 'nextcloud'),
				'approval_workflow' => __('Approval process workflow', 'nextcloud'),
				'document_signatures' => __('Cryptographic document signatures', 'nextcloud'),
				'script_execution' => __('Automatic script execution', 'nextcloud'),
				'impersonate_users' => __('Impersonate users', 'nextcloud'),
				'real_time_notifications' => __('Real-time notifications', 'nextcloud'),
				'monitoring_api' => __('Monitoring web/API interface', 'nextcloud'),
				'data_retention' => __('Data retention policy support', 'nextcloud'),
				'long_term_support' => __('Long term support (5–10 years)', 'nextcloud'),
				'branding' => __('Branding', 'nextcloud'),
			]
		],

		'compliance_features' => [
			__('Compliance features', 'nextcloud'),
			[
				'audit_trail' => __('Audit trail', 'nextcloud'),
				'imprint_privacy_links' => __('Imprint and privacy links', 'nextcloud'),
				'data_requests_deletion' => __('Built-in data requests / account deletion', 'nextcloud'),
				'terms_of_service' => __('Terms-of-service', 'nextcloud'),
			]
		],

		'security_features' => [
			__('Security features', 'nextcloud'),
			[
				'server_side_encryption' => __('Server-side encryption', 'nextcloud'),
				'end_to_end_encryption' => __('Client-side / end-to-end encryption', 'nextcloud'),
				'video_verification' => __('Video verification', 'nextcloud'),
				'brute_force_protection' => __('Brute force hacking protection', 'nextcloud'),
				'nist_password_policy' => __('NiST compliant password policy', 'nextcloud'),
				'csp_3' => __('Web UI secured with CSP 3.0', 'nextcloud'),
				'same_site_cookie' => __('Same-site cookie attribute', 'nextcloud'),
				'file_access_control' => __('File access control', 'nextcloud'),
				'app_access_rights' => __('App access rights', 'nextcloud'),
			]
		],

		'authentication' => [
			__('Authentication', 'nextcloud'),
			[
				'two_factor_auth' => __('Enforceable 2-factor authentication', 'nextcloud'),
				'suspicious_login_detection' => __('ML-based suspicious login detection', 'nextcloud'),
				'ldap_ad' => __('LDAP / AD', 'nextcloud'),
				'saml' => __('Native SAML', 'nextcloud'),
				'env_auth' => __('Auth via env variable', 'nextcloud'),
				'kerberos' => __('Kerberos', 'nextcloud'),
			]
		],

		'storage' => [
			__('Storage', 'nextcloud'),
			[
				'local_storage' => __('File storage (local / NFS)', 'nextcloud'),
				'object_storage' => __('Object storage (S3 / Swift)', 'nextcloud'),
				'cifs_windows' => __('CIFS / Windows shares', 'nextcloud'),
				'sharepoint' => __('SharePoint', 'nextcloud'),
				'extensible_storage' => __('Extensible storage', 'nextcloud'),
			]
		],
	];
}

/**
 * Build the param_group fields for each platform.
 */
function nc_platform_comparison_params() {

	$params = [
		[
			'type' => 'textfield',
			'heading' => __('Platform Name', 'nextcloud'),
			'param_name' => 'name',
			'admin_label' => true,
			'edit_field_class' => 'vc_col-xs-12 nc_pc_platform',
		],
		[
			'type' => 'attach_image',
			'heading' => __('Logo', 'nextcloud'),
			'param_name' => 'logo',
			'edit_field_class' => 'vc_col-xs-6 nc_pc_platform',
		],
		[
			'type' => 'textfield',
			'heading' => __('Custom CSS class', 'nextcloud'),
			'param_name' => 'custom_class',
			'edit_field_class' => 'vc_col-xs-6 nc_pc_platform',
			'description' => __('Optional class added to the logo image', 'nextcloud'),
		],
	];

	foreach (nc_platform_comparison_features() as $group_key => $group_array) {

		$group_label = isset($group_array[0]) ? $group_array[0] : '';
		$features = isset($group_array[1]) && is_array($group_array[1]) ? $group_array[1] : [];
		$group_param_key = sanitize_key(str_replace('-', '_', trim($group_key)));

		$params[] = [
			'type' => 'vc_raw_html',
			'heading' => $group_label,
			'holder' => 'div',
			'edit_field_class' => 'vc_col-xs-12 compare_group_section_title',
			'param_name' => $group_param_key . '_section_title',
			'value' => '',
		];

		foreach ($features as $feature_key => $feature_label) {

			$param_key = sanitize_key(str_replace('-', '_', trim($feature_key)));

			$params[] = [
				'type' => 'dropdown',
				'heading' => $feature_label,
				'edit_field_class' => 'vc_col-xs-12 platform_comparison_feature nc_pc_field',
				'param_name' => $param_key . '_status',
				'value' => [
					__('No', 'nextcloud') => 'no',
					__('Yes', 'nextcloud') => 'yes',
					__('Optional', 'nextcloud') => 'optional',
					__('Custom (only label shown)', 'nextcloud') => 'custom',
					__('Dollar sign', 'nextcloud') => 'dollar',
					__('Operating system', 'nextcloud') => 'operating_system',
					__('Multiple statuses', 'nextcloud') => 'multiple_icons'
				],
				'std' => 'no',
			];

			$params[] = [
				'type' => 'checkbox',
				'heading' => __('Operating system icons', 'nextcloud'),
				'param_name' => $param_key . '_os_icons',
				'edit_field_class' => 'vc_col-xs-12 nc_pc_field nc_pc_sub',
				'description' => __('', 'nextcloud'),
				'value' => [
					__('iOS/iPhone', 'nextcloud') => 'apple',
					__('Android', 'nextcloud') => 'android',
					__('Windows', 'nextcloud') => 'windows',
					__('Linux', 'nextcloud') => 'linux'
				],

				'dependency' => [
					'element' => $param_key . '_status',
					'value' => ['operating_system'],
				],
			];

			//multiple icons

			$params[] = [
				'type' => 'dropdown',
				'heading' => __('', 'nextcloud'),
				'param_name' => $param_key . '_multiple_icons_1',
				'value' => [
					__('Default', 'nextcloud') => '',
					__('Yes', 'nextcloud') => 'yes',
					__('No', 'nextcloud') => 'no',
					__('Dollar sign', 'nextcloud') => 'dollar'
				],
				'edit_field_class' => 'vc_col-xs-3 multiple_icons_item nc_pc_field',
				'dependency' => [
					'element' => $param_key . '_status',
					'value' => ['multiple_icons'],
				],
			];

			$params[] = [
				'type' => 'dropdown',
				'heading' => __('', 'nextcloud'),
				'param_name' => $param_key . '_multiple_icons_2',
				'value' => [
					__('Default', 'nextcloud') => '',
					__('Yes', 'nextcloud') => 'yes',
					__('No', 'nextcloud') => 'no',
					__('Dollar sign', 'nextcloud') => 'dollar'
				],
				'edit_field_class' => 'vc_col-xs-3 multiple_icons_item nc_pc_field',
				'dependency' => [
					'element' => $param_key . '_status',
					'value' => ['multiple_icons'],
				],
			];

			$params[] = [
				'type' => 'dropdown',
				'heading' => __('', 'nextcloud'),
				'param_name' => $param_key . '_multiple_icons_3',
				'value' => [
					__('Default', 'nextcloud') => '',
					__('Yes', 'nextcloud') => 'yes',
					__('No', 'nextcloud') => 'no',
					__('Dollar sign', 'nextcloud') => 'dollar'
				],
				'edit_field_class' => 'vc_col-xs-3 multiple_icons_item nc_pc_field',
				'dependency' => [
					'element' => $param_key . '_status',
					'value' => ['multiple_icons'],
				],
			];
			$params[] = [
				'type' => 'dropdown',
				'heading' => __('', 'nextcloud'),
				'param_name' => $param_key . '_multiple_icons_4',
				'value' => [
					__('Default', 'nextcloud') => '',
					__('Yes', 'nextcloud') => 'yes',
					__('No', 'nextcloud') => 'no',
					__('Dollar sign', 'nextcloud') => 'dollar'
				],
				'edit_field_class' => 'vc_col-xs-3 multiple_icons_item nc_pc_field',
				'dependency' => [
					'element' => $param_key . '_status',
					'value' => ['multiple_icons'],
				],
			];

			$params[] = [
				'type' => 'checkbox',
				'heading' => '',
				'edit_field_class' => 'vc_col-xs-3 multiple_icons_item nc_pc_field',
				'param_name' => $param_key . '_has_asterisk_1',
				'value' => [
					__('Has asterisk?', 'nextcloud') => 'yes',
				],
				'dependency' => [
					'element' => $param_key . '_status',
					'value' => ['multiple_icons'],
				],
			];
			$params[] = [
				'type' => 'checkbox',
				'heading' => '',
				'edit_field_class' => 'vc_col-xs-3 multiple_icons_item nc_pc_field',
				'param_name' => $param_key . '_has_asterisk_2',
				'value' => [
					__('Has asterisk?', 'nextcloud') => 'yes',
				],
				'dependency' => [
					'element' => $param_key . '_status',
					'value' => ['multiple_icons'],
				],
			];
			$params[] = [
				'type' => 'checkbox',
				'heading' => '',
				'edit_field_class' => 'vc_col-xs-3 multiple_icons_item nc_pc_field',
				'param_name' => $param_key . '_has_asterisk_3',
				'value' => [
					__('Has asterisk?', 'nextcloud') => 'yes',
				],
				'dependency' => [
					'element' => $param_key . '_status',
					'value' => ['multiple_icons'],
				],
			];
			$params[] = [
				'type' => 'checkbox',
				'heading' => '',
				'edit_field_class' => 'vc_col-xs-3 multiple_icons_item nc_pc_field',
				'param_name' => $param_key . '_has_asterisk_4',
				'value' => [
					__('Has asterisk?', 'nextcloud') => 'yes',
				],
				'dependency' => [
					'element' => $param_key . '_status',
					'value' => ['multiple_icons'],
				],
			];

			$params[] = [
				'type' => 'checkbox',
				'heading' => '',
				'edit_field_class' => 'vc_col-xs-12 nc_pc_field nc_pc_toggle',
				'param_name' => $param_key . '_has_label',
				'value' => [
					__('Custom label?', 'nextcloud') => 'yes',
				],
			];

			$params[] = [
				'type' => 'textfield',
				'heading' => __('Custom label', 'nextcloud'),
				'param_name' => $param_key . '_label',
				'edit_field_class' => 'vc_col-xs-12 nc_pc_field nc_pc_sub',
				'description' => __('Optional label, shown on hover', 'nextcloud'),
				'dependency' => [
					'element' => $param_key . '_has_label',
					'value' => ['yes'],
				],
			];

			$params[] = [
				'type' => 'checkbox',
				'heading' => '',
				'edit_field_class' => 'vc_col-xs-12 nc_pc_field nc_pc_toggle',
				'param_name' => $param_key . '_has_tooltip',
				'value' => [
					__('Has tooltip?', 'nextcloud') => 'yes',
				],
			];

			$params[] = [
				'type' => 'textfield',
				'heading' => __('Tooltip', 'nextcloud'),
				'param_name' => $param_key . '_tooltip',
				'edit_field_class' => 'vc_col-xs-12 nc_pc_field nc_pc_sub',
				'description' => __('Optional tooltip, shown on hover', 'nextcloud'),
				'dependency' => [
					'element' => $param_key . '_has_tooltip',
					'value' => ['yes'],
				],
			];

		}
	}

	return $params;
}

/**
 * Register WPBakery element.
 */
add_action('vc_before_init', 'nc_register_platform_comparison_shortcode');
function nc_register_platform_comparison_shortcode() {
	if (!function_exists('vc_map')) {
		return;
	}

	vc_map([
		'name' => __('Platforms comparison', 'nextcloud'),
		'base' => 'platforms_comparison',
		'class' => 'platforms_comparison_custom_shortcode',
		'icon' => 'icon-wpb-ui-tab-content',
		'category' => __('Nextcloud', 'nextcloud'),
		'description' => __('Comparison table with predefined features and repeatable platforms.', 'nextcloud'),
		'params' => [
			[
				'type' => 'param_group',
				'heading' => __('Platforms', 'nextcloud'),
				'param_name' => 'platforms',
				'params' => nc_platform_comparison_params(),
				'description' => __('Add one row per platform and set the status for each predefined feature.', 'nextcloud'),
			],
		],
	]);
}

function nc_render_platform_comparison_icon($status, $asterisk = false) {
	$output = '';

	switch ($status) {
		case 'yes':
			$output = '<span class="fa fa-check-circle" aria-hidden="true"></span>';
			break;

		case 'optional':
			$output = '<span class="fa fa-check-circle optional" aria-hidden="true"></span>';
			break;

		case 'dollar':
			$output = '<span class="fa fa-dollar" aria-hidden="true"></span>';
			break;

		case 'no':
			$output = '<span class="fa fa-times-circle" aria-hidden="true"></span>';
			break;
	}

	if ($asterisk) {
		$output .= '<span class="comparison_asterisk">*</span>';
	}

	return $output;
}

/**
 * Shortcode renderer.
 */
add_shortcode('platforms_comparison', 'nc_render_platforms_comparison_shortcode');
function nc_platform_comparison_render_status_icon($status, $title = '', $asterisk = false) {
	$status = sanitize_key($status);

	switch ($status) {
		case 'yes':
			$html = '<div class="fa fa-check-circle" title="' . esc_attr($title) . '" aria-hidden="true"></div>';
			break;

		case 'optional':
			$html = '<div class="fa fa-check-circle optional" title="' . esc_attr($title) . '" aria-hidden="true"></div>';
			break;

		case 'dollar':
			$html = '<div class="fa fa-dollar-sign" title="' . esc_attr($title) . '" aria-hidden="true"></div>';
			break;

		case 'no':
		default:
			$html = '<div class="fa fa-times-circle" title="' . esc_attr($title) . '" aria-hidden="true"></div>';
			break;
	}

	if ($asterisk) {
		$html .= '<span class="comparison_asterisk">*</span>';
	}

	return $html;
}

function nc_platform_comparison_get_checkbox_values($value) {
	if (empty($value)) {
		return [];
	}

	if (is_array($value)) {
		return array_map('sanitize_key', $value);
	}

	return array_map('sanitize_key', array_map('trim', explode(',', $value)));
}

function nc_render_platforms_comparison_shortcode($atts) {
	$atts = shortcode_atts([
		'platforms' => '',
	], $atts, 'platforms_comparison');

	if (!function_exists('vc_param_group_parse_atts')) {
		return '';
	}

	$platforms = vc_param_group_parse_atts($atts['platforms']);
	$features = nc_platform_comparison_features();

	if (empty($platforms) || !is_array($platforms) || empty($features)) {
		return '';
	}

	$allowed_statuses = [
		'yes',
		'no',
		'optional',
		'custom',
		'dollar',
		'operating_system',
		'multiple_icons',
	];

	ob_start();
	?>
	<div class="comparison_table_container">

		<div class="filtering_buttons">
			<button class="reset" tabindex="0" type="reset">
				<?php echo esc_html__('Reset', 'nextcloud'); ?>
			</button>
			<button class="filter" tabindex="0" disabled>
				<?php echo esc_html__('Filter', 'nextcloud'); ?>
			</button>
		</div>

		<div class="table_move_arrows" style="display:none;">
			<a class="arrow arrow-left" href="#" aria-label="<?php echo esc_attr__('Scroll left', 'nextcloud'); ?>">
				<i class="fa fa-angle-left" aria-hidden="true"></i>
			</a>
			<a class="arrow arrow-right" href="#" aria-label="<?php echo esc_attr__('Scroll right', 'nextcloud'); ?>">
				<i class="fa fa-angle-right" aria-hidden="true"></i>
			</a>
		</div>

		<div class="comparison_table_container_inner">
			<table class="comparison_table">
				<thead>
					<tr class="platform_logos">
						<th scope="col"></th>

						<?php foreach ($platforms as $platform) : ?>
							<?php
							$name = !empty($platform['name']) ? $platform['name'] : '';
							$logo_id = !empty($platform['logo']) ? absint($platform['logo']) : 0;
							$custom_class = !empty($platform['custom_class']) ? sanitize_html_class($platform['custom_class']) : '';
							$logo_url = $logo_id ? wp_get_attachment_image_url($logo_id, 'full') : '';
							?>

							<th scope="col">
								<div class="check" tabindex="0"></div>

								<?php if (!empty($logo_url)) : ?>
									<img
										src="<?php echo esc_url($logo_url); ?>"
										alt="<?php echo esc_attr($name); ?>"
										class="<?php echo esc_attr($custom_class); ?>"
									/>
								<?php elseif (!empty($name)) : ?>
									<span class="platform_name">
										<?php echo esc_html($name); ?>
									</span>
								<?php endif; ?>
							</th>
						<?php endforeach; ?>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($features as $group_key => $group_array) : ?>
						<?php
						$group_label = isset($group_array[0]) ? $group_array[0] : '';
						$group_features = isset($group_array[1]) && is_array($group_array[1]) ? $group_array[1] : [];

						if (empty($group_features)) {
							continue;
						}
						?>

						<?php if ($group_key != 'main_features') { ?>
						<tr class="comparison_category comparison_category_<?php echo esc_attr(sanitize_html_class($group_key)); ?>">
							<td class="comparison_category_label">
								<?php echo esc_html($group_label); ?>
							</td>
							<td colspan="<?php echo esc_attr(count($platforms)); ?>"></td>
						</tr>
						<?php } ?>

						<?php foreach ($group_features as $feature_key => $feature_label) : ?>
							<?php $param_key = sanitize_key(str_replace('-', '_', trim($feature_key))); ?>

							<tr class="comparison_feature comparison_feature_<?php echo esc_attr(sanitize_html_class($param_key)); ?>">
								<td scope="row">
									<?php echo esc_html($feature_label); ?>
								</td>

								<?php foreach ($platforms as $platform) : ?>
									<?php
									$status = !empty($platform[$param_key . '_status'])
										? sanitize_key($platform[$param_key . '_status'])
										: 'no';

									if (!in_array($status, $allowed_statuses, true)) {
										$status = 'no';
									}

									$has_label = !empty($platform[$param_key . '_has_label'])
										&& $platform[$param_key . '_has_label'] === 'yes';

									$custom_label = !empty($platform[$param_key . '_label'])
										? trim($platform[$param_key . '_label'])
										: '';

									$has_tooltip = !empty($platform[$param_key . '_has_tooltip'])
										&& $platform[$param_key . '_has_tooltip'] === 'yes';

									$tooltip = !empty($platform[$param_key . '_tooltip'])
										? trim($platform[$param_key . '_tooltip'])
										: '';

									if ($has_label && $custom_label !== '') {
										$cell_label = $custom_label;
									} elseif ($status === 'yes') {
										$cell_label = __('Included', 'nextcloud');
									} elseif ($status === 'optional') {
										$cell_label = __('Optional', 'nextcloud');
									} elseif ($status === 'dollar') {
										$cell_label = __('Paid option', 'nextcloud');
									} elseif ($status === 'operating_system') {
										$cell_label = __('Available operating systems', 'nextcloud');
									} elseif ($status === 'multiple_icons') {
										$cell_label = __('Multiple options', 'nextcloud');
									} else {
										$cell_label = __('Not included', 'nextcloud');
									}

									if ($has_tooltip && $tooltip !== '') {
										$cell_label = $tooltip;
									}

									$td_classes = [
										'status',
										'status-' . sanitize_html_class($status),
									];

									if ($has_tooltip && $tooltip !== '') {
										$td_classes[] = 'has_tooltip';
									}
									?>

									<td class="<?php echo esc_attr(implode(' ', $td_classes)); ?>">

										<?php if (in_array($status, ['yes', 'optional', 'dollar', 'no'], true)) : ?>

											<?php echo nc_platform_comparison_render_status_icon($status, $cell_label); ?>
											<span class="screen-reader-text">
												<?php echo esc_html($cell_label); ?>
											</span>

										<?php elseif ($status === 'custom') : ?>

											<?php if ($custom_label !== '') : ?>
												<span class="comparison_custom_label">
													<?php echo esc_html($custom_label); ?>
												</span>
											<?php endif; ?>

										<?php elseif ($status === 'operating_system') : ?>

											<?php
											$os_icons = nc_platform_comparison_get_checkbox_values(
												isset($platform[$param_key . '_os_icons']) ? $platform[$param_key . '_os_icons'] : ''
											);

											$os_icon_map = [
												'apple' => ['class' => 'fa fa-apple', 'label' => __('iOS / iPhone', 'nextcloud')],
												'android' => ['class' => 'fa fa-android', 'label' => __('Android', 'nextcloud')],
												'windows' => ['class' => 'fa fa-windows', 'label' => __('Windows', 'nextcloud')],
												'linux' => ['class' => 'fa fa-linux', 'label' => __('Linux', 'nextcloud')],
											];
											?>

											<?php if (!empty($os_icons)) : ?>
												<div class="comparison_os_icons">
													<?php foreach ($os_icons as $os_icon) : ?>
														<?php if (isset($os_icon_map[$os_icon])) : ?>
															<span
																class="<?php echo esc_attr($os_icon_map[$os_icon]['class']); ?>"
																title="<?php echo esc_attr($cell_label); ?>"
																aria-hidden="true"
															></span>
														<?php endif; ?>
													<?php endforeach; ?>
												</div>

												<span class="screen-reader-text">
													<?php echo esc_html($cell_label); ?>
												</span>
											<?php else : ?>
												<?php echo nc_platform_comparison_render_status_icon('no', __('Not included', 'nextcloud')); ?>
												<span class="screen-reader-text">
													<?php echo esc_html__('Not included', 'nextcloud'); ?>
												</span>
											<?php endif; ?>

										<?php elseif ($status === 'multiple_icons') : ?>

											<div class="comparison_multiple_icons">
												<?php
												for ($i = 1; $i <= 4; $i++) {
													$icon_status = !empty($platform[$param_key . '_multiple_icons_' . $i])
														? sanitize_key($platform[$param_key . '_multiple_icons_' . $i])
														: '';

													if ($icon_status === '') {
														continue;
													}

													if (!in_array($icon_status, ['yes', 'no', 'dollar'], true)) {
														continue;
													}

													$has_asterisk = !empty($platform[$param_key . '_has_asterisk_' . $i])
														&& $platform[$param_key . '_has_asterisk_' . $i] === 'yes';

													$icon_label = $cell_label;

													echo '<span class="comparison_multiple_icon comparison_multiple_icon_' . esc_attr($i) . '">';
													echo nc_platform_comparison_render_status_icon($icon_status, $icon_label, $has_asterisk);
													echo '<span class="screen-reader-text">' . esc_html($icon_label) . '</span>';
													echo '</span>';
												}
											?>
											</div>

										<?php endif; ?>

										<?php if ($has_label && $custom_label !== '' && $status !== 'custom' && !$has_tooltip) : ?>
											<span class="block">
												<?php echo esc_html($custom_label); ?>
											</span>
										<?php endif; ?>

										<?php if ($has_tooltip && $tooltip !== '') : ?>
											<span class="tooltip">
												<?php echo esc_html($tooltip); ?>
											</span>
											<span class="show_tooltip">
												<?php echo esc_html__('See more', 'nextcloud'); ?>
											</span>
										<?php endif; ?>

									</td>
								<?php endforeach; ?>
							</tr>
						<?php endforeach; ?>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php

	return ob_get_clean();
}

/**
 * Platforms comparison (Translatable)
 * Parent/child version of [platforms_comparison]: every platform is its own [nc_platform_item]
 * shortcode with plain attributes, so translation connectors (Phrase) can pick up the
 * platform name, custom labels and tooltips as separate segments.
 *
 * [nc_platforms_comparison hide_filter_buttons="" hide_check=""]
 *   [nc_platform_item name="Nextcloud" logo="123" licensing_status="yes" licensing_has_tooltip="yes" licensing_tooltip="..."]
 * [/nc_platforms_comparison]
 */
add_action('vc_before_init', 'nc_platforms_comparison_vc_map');
function nc_platforms_comparison_vc_map() {
	if (!class_exists('WPBakeryShortCodesContainer')) {
		return;
	}

	if (!class_exists('WPBakeryShortCode_Nc_Platforms_Comparison')) {
		class WPBakeryShortCode_Nc_Platforms_Comparison extends WPBakeryShortCodesContainer {
		}
	}
	if (!class_exists('WPBakeryShortCode_Nc_Platform_Item')) {
		class WPBakeryShortCode_Nc_Platform_Item extends WPBakeryShortCode {
		}
	}

	vc_map([
		'name' => __('Platforms comparison (Translatable)', 'nextcloud'),
		'base' => 'nc_platforms_comparison',
		'class' => 'platforms_comparison_custom_shortcode',
		'icon' => 'icon-wpb-ui-tab-content',
		'category' => __('Nextcloud', 'nextcloud'),
		'description' => __('Comparison table with predefined features. Add one Platform item per column.', 'nextcloud'),
		'as_parent' => ['only' => 'nc_platform_item'],
		'content_element' => true,
		// No 'is_container' => true: WPBakery hides such elements inside inner rows. as_parent is enough for nesting.
		'show_settings_on_create' => false,
		'js_view' => 'VcColumnView',
		'params' => nc_platform_comparison_display_params(),
	]);

	vc_map([
		'name' => __('Platform Item', 'nextcloud'),
		'base' => 'nc_platform_item',
		'class' => 'platforms_comparison_custom_shortcode',
		'icon' => 'icon-wpb-ui-tab-content',
		'category' => __('Nextcloud', 'nextcloud'),
		'as_child' => ['only' => 'nc_platforms_comparison'],
		'content_element' => true,
		'params' => nc_platform_comparison_params(),
	]);
}

// Platforms collected by [nc_platform_item] while the parent [nc_platforms_comparison] runs.
$GLOBALS['nc_platforms_comparison_items'] = null;

add_shortcode('nc_platforms_comparison', 'nc_render_nc_platforms_comparison_shortcode');
function nc_render_nc_platforms_comparison_shortcode($atts, $content = null) {
	$atts = shortcode_atts([
		'hide_filter_buttons' => '',
		'hide_check' => '',
		'el_class' => '',
	], $atts, 'nc_platforms_comparison');

	// The table is column-based, so children don't print anything themselves:
	// they register their attributes here and the parent renders the whole table.
	$previous = $GLOBALS['nc_platforms_comparison_items'];
	$GLOBALS['nc_platforms_comparison_items'] = [];
	do_shortcode((string)$content);
	$platforms = $GLOBALS['nc_platforms_comparison_items'];
	$GLOBALS['nc_platforms_comparison_items'] = $previous;

	return nc_platform_comparison_render_table($platforms, [
		'hide_filter_buttons' => $atts['hide_filter_buttons'] !== '',
		'hide_check' => $atts['hide_check'] !== '',
		'el_class' => $atts['el_class'],
	]);
}

add_shortcode('nc_platform_item', 'nc_render_nc_platform_item_shortcode');
function nc_render_nc_platform_item_shortcode($atts) {
	if (is_array($GLOBALS['nc_platforms_comparison_items'])) {
		$GLOBALS['nc_platforms_comparison_items'][] = is_array($atts) ? $atts : [];
	}

	return '';
}

/**
 * Display options shared by [platforms_comparison] and [nc_platforms_comparison].
 */
function nc_platform_comparison_display_params() {
	return [
		[
			'type' => 'checkbox',
			'heading' => __('Hide Reset / Filter buttons', 'nextcloud'),
			'param_name' => 'hide_filter_buttons',
			'value' => [__('Yes', 'nextcloud') => 'yes'],
		],
		[
			'type' => 'checkbox',
			'heading' => __('Hide platform select checkboxes', 'nextcloud'),
			'param_name' => 'hide_check',
			'description' => __('Removes the selectable check above each platform logo.', 'nextcloud'),
			'value' => [__('Yes', 'nextcloud') => 'yes'],
		],
		[
			'type' => 'textfield',
			'heading' => __('Extra CSS class', 'nextcloud'),
			'param_name' => 'el_class',
			'description' => __('Added to the table container. Use "platforms_2" for a 2-platform table with 3 equal columns.', 'nextcloud'),
		],
	];
}

/**
 * Render the comparison table markup.
 *
 * @param array $platforms One array of platform fields per column.
 * @param array $options hide_filter_buttons (bool), hide_check (bool), el_class (string).
 */
function nc_platform_comparison_render_table($platforms, $options = []) {
	$features = nc_platform_comparison_features();
	$hide_filter_buttons = !empty($options['hide_filter_buttons']);
	$hide_check = !empty($options['hide_check']);

	$container_classes = ['comparison_table_container'];
	if (!empty($options['el_class'])) {
		foreach (preg_split('/\s+/', trim($options['el_class'])) as $extra_class) {
			$extra_class = sanitize_html_class($extra_class);
			if ($extra_class !== '') {
				$container_classes[] = $extra_class;
			}
		}
	}

	if (empty($platforms) || !is_array($platforms) || empty($features)) {
		return '';
	}

	$allowed_statuses = [
		'yes',
		'no',
		'optional',
		'custom',
		'dollar',
		'operating_system',
		'multiple_icons',
	];

	ob_start();
	?>
	<div class="<?php echo esc_attr(implode(' ', $container_classes)); ?>">

		<?php if (!$hide_filter_buttons) : ?>
		<div class="filtering_buttons">
			<button class="reset" tabindex="0" type="reset">
				<?php echo esc_html__('Reset', 'nextcloud'); ?>
			</button>
			<button class="filter" tabindex="0" disabled>
				<?php echo esc_html__('Filter', 'nextcloud'); ?>
			</button>
		</div>
		<?php endif; ?>

		<div class="table_move_arrows" style="display:none;">
			<a class="arrow arrow-left" href="#" aria-label="<?php echo esc_attr__('Scroll left', 'nextcloud'); ?>">
				<i class="fa fa-angle-left" aria-hidden="true"></i>
			</a>
			<a class="arrow arrow-right" href="#" aria-label="<?php echo esc_attr__('Scroll right', 'nextcloud'); ?>">
				<i class="fa fa-angle-right" aria-hidden="true"></i>
			</a>
		</div>

		<div class="comparison_table_container_inner">
			<table class="comparison_table">
				<thead>
					<tr class="platform_logos">
						<th scope="col"></th>

						<?php foreach ($platforms as $platform) : ?>
							<?php
							$name = !empty($platform['name']) ? $platform['name'] : '';
							$logo_id = !empty($platform['logo']) ? absint($platform['logo']) : 0;
							$custom_class = !empty($platform['custom_class']) ? sanitize_html_class($platform['custom_class']) : '';
							$logo_url = $logo_id ? wp_get_attachment_image_url($logo_id, 'full') : '';
							?>

							<th scope="col">
								<?php if (!$hide_check) : ?>
									<div class="check" tabindex="0"></div>
								<?php endif; ?>

								<?php if (!empty($logo_url)) : ?>
									<img
										src="<?php echo esc_url($logo_url); ?>"
										alt="<?php echo esc_attr($name); ?>"
										class="<?php echo esc_attr($custom_class); ?>"
									/>
								<?php elseif (!empty($name)) : ?>
									<span class="platform_name">
										<?php echo esc_html($name); ?>
									</span>
								<?php endif; ?>
							</th>
						<?php endforeach; ?>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($features as $group_key => $group_array) : ?>
						<?php
						$group_label = isset($group_array[0]) ? $group_array[0] : '';
						$group_features = isset($group_array[1]) && is_array($group_array[1]) ? $group_array[1] : [];

						if (empty($group_features)) {
							continue;
						}
						?>

						<?php if ($group_key != 'main_features') { ?>
						<tr class="comparison_category comparison_category_<?php echo esc_attr(sanitize_html_class($group_key)); ?>">
							<td class="comparison_category_label">
								<?php echo esc_html($group_label); ?>
							</td>
							<td colspan="<?php echo esc_attr(count($platforms)); ?>"></td>
						</tr>
						<?php } ?>

						<?php foreach ($group_features as $feature_key => $feature_label) : ?>
							<?php $param_key = sanitize_key(str_replace('-', '_', trim($feature_key))); ?>

							<tr class="comparison_feature comparison_feature_<?php echo esc_attr(sanitize_html_class($param_key)); ?>">
								<td scope="row">
									<?php echo esc_html($feature_label); ?>
								</td>

								<?php foreach ($platforms as $platform) : ?>
									<?php
									$status = !empty($platform[$param_key . '_status'])
										? sanitize_key($platform[$param_key . '_status'])
										: 'no';

									if (!in_array($status, $allowed_statuses, true)) {
										$status = 'no';
									}

									$has_label = !empty($platform[$param_key . '_has_label'])
										&& $platform[$param_key . '_has_label'] === 'yes';

									$custom_label = !empty($platform[$param_key . '_label'])
										? trim($platform[$param_key . '_label'])
										: '';

									$has_tooltip = !empty($platform[$param_key . '_has_tooltip'])
										&& $platform[$param_key . '_has_tooltip'] === 'yes';

									$tooltip = !empty($platform[$param_key . '_tooltip'])
										? trim($platform[$param_key . '_tooltip'])
										: '';

									if ($has_label && $custom_label !== '') {
										$cell_label = $custom_label;
									} elseif ($status === 'yes') {
										$cell_label = __('Included', 'nextcloud');
									} elseif ($status === 'optional') {
										$cell_label = __('Optional', 'nextcloud');
									} elseif ($status === 'dollar') {
										$cell_label = __('Paid option', 'nextcloud');
									} elseif ($status === 'operating_system') {
										$cell_label = __('Available operating systems', 'nextcloud');
									} elseif ($status === 'multiple_icons') {
										$cell_label = __('Multiple options', 'nextcloud');
									} else {
										$cell_label = __('Not included', 'nextcloud');
									}

									if ($has_tooltip && $tooltip !== '') {
										$cell_label = $tooltip;
									}

									$td_classes = [
										'status',
										'status-' . sanitize_html_class($status),
									];

									if ($has_tooltip && $tooltip !== '') {
										$td_classes[] = 'has_tooltip';
									}
									?>

									<td class="<?php echo esc_attr(implode(' ', $td_classes)); ?>">

										<?php if (in_array($status, ['yes', 'optional', 'dollar', 'no'], true)) : ?>

											<?php echo nc_platform_comparison_render_status_icon($status, $cell_label); ?>
											<span class="screen-reader-text">
												<?php echo esc_html($cell_label); ?>
											</span>

										<?php elseif ($status === 'custom') : ?>

											<?php if ($custom_label !== '') : ?>
												<span class="comparison_custom_label">
													<?php echo esc_html($custom_label); ?>
												</span>
											<?php endif; ?>

										<?php elseif ($status === 'operating_system') : ?>

											<?php
											$os_icons = nc_platform_comparison_get_checkbox_values(
												isset($platform[$param_key . '_os_icons']) ? $platform[$param_key . '_os_icons'] : ''
											);

											$os_icon_map = [
												'apple' => ['class' => 'fa fa-apple', 'label' => __('iOS / iPhone', 'nextcloud')],
												'android' => ['class' => 'fa fa-android', 'label' => __('Android', 'nextcloud')],
												'windows' => ['class' => 'fa fa-windows', 'label' => __('Windows', 'nextcloud')],
												'linux' => ['class' => 'fa fa-linux', 'label' => __('Linux', 'nextcloud')],
											];
											?>

											<?php if (!empty($os_icons)) : ?>
												<div class="comparison_os_icons">
													<?php foreach ($os_icons as $os_icon) : ?>
														<?php if (isset($os_icon_map[$os_icon])) : ?>
															<span
																class="<?php echo esc_attr($os_icon_map[$os_icon]['class']); ?>"
																title="<?php echo esc_attr($cell_label); ?>"
																aria-hidden="true"
															></span>
														<?php endif; ?>
													<?php endforeach; ?>
												</div>

												<span class="screen-reader-text">
													<?php echo esc_html($cell_label); ?>
												</span>
											<?php else : ?>
												<?php echo nc_platform_comparison_render_status_icon('no', __('Not included', 'nextcloud')); ?>
												<span class="screen-reader-text">
													<?php echo esc_html__('Not included', 'nextcloud'); ?>
												</span>
											<?php endif; ?>

										<?php elseif ($status === 'multiple_icons') : ?>

											<div class="comparison_multiple_icons">
												<?php
												for ($i = 1; $i <= 4; $i++) {
													$icon_status = !empty($platform[$param_key . '_multiple_icons_' . $i])
														? sanitize_key($platform[$param_key . '_multiple_icons_' . $i])
														: '';

													if ($icon_status === '') {
														continue;
													}

													if (!in_array($icon_status, ['yes', 'no', 'dollar'], true)) {
														continue;
													}

													$has_asterisk = !empty($platform[$param_key . '_has_asterisk_' . $i])
														&& $platform[$param_key . '_has_asterisk_' . $i] === 'yes';

													$icon_label = $cell_label;

													echo '<span class="comparison_multiple_icon comparison_multiple_icon_' . esc_attr($i) . '">';
													echo nc_platform_comparison_render_status_icon($icon_status, $icon_label, $has_asterisk);
													echo '<span class="screen-reader-text">' . esc_html($icon_label) . '</span>';
													echo '</span>';
												}
											?>
											</div>

										<?php endif; ?>

										<?php if ($has_label && $custom_label !== '' && $status !== 'custom' && !$has_tooltip) : ?>
											<span class="block">
												<?php echo esc_html($custom_label); ?>
											</span>
										<?php endif; ?>

										<?php if ($has_tooltip && $tooltip !== '') : ?>
											<span class="tooltip">
												<?php echo esc_html($tooltip); ?>
											</span>
											<span class="show_tooltip">
												<?php echo esc_html__('See more', 'nextcloud'); ?>
											</span>
										<?php endif; ?>

									</td>
								<?php endforeach; ?>
							</tr>
						<?php endforeach; ?>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php

	return ob_get_clean();
}

/**
 * Talk platforms comparison.
 *
 * - [talk_platforms_comparison platforms="…"]  old element (one urlencoded param_group attribute), kept for existing pages.
 * - [nc_talk_platforms_comparison] + [nc_talk_platform_item …]  translatable parent/child version.
 *
 * Both render through nc_talk_platform_comparison_render_table().
 */

/**
 * Predefined Talk comparison features: key => label.
 * The key is also the status attribute name, so never rename a key (existing content uses it).
 */
function nc_talk_platform_comparison_features() {
	return [
		'hostable_on_premises' => __('Hostable on-premises', 'nextcloud'),
		'open_source' => __('Open source', 'nextcloud'),
		'desktop_client' => __('Desktop client', 'nextcloud'),
		'mobile_client' => __('Mobile client', 'nextcloud'),
		'phone_dial_in' => __('Phone dial-in', 'nextcloud'),
		'phone_dial_out' => __('Phone dial-out', 'nextcloud'),
		'breakout_rooms' => __('Breakout rooms', 'nextcloud'),
		'stream_to_youtube' => __('Stream to YouTube', 'nextcloud'),
		'search_messages' => __('Search messages', 'nextcloud'),
		'filter_conversations' => __('Filter conversations', 'nextcloud'),
		'ai_assistant_features' => __('AI Assistant features', 'nextcloud'),
		'note_taking_editing' => __('Note-taking/editing', 'nextcloud'),
		'office_document_editing' => __('Office document editing', 'nextcloud'),
		'public_office_document_editing' => __('Public office document editing (edit via public link)', 'nextcloud'),
		'accessibility_features' => __('Accessibility features (screen reader support, dyslectic font, high contrast mode)', 'nextcloud'),
		'federation' => __('Federation', 'nextcloud'),
		'push_notifications' => __('Push notifications', 'nextcloud'),
		'together_mode' => __('Together mode', 'nextcloud'),
		'message_reminders' => __('Message reminders', 'nextcloud'),
		'silent_messages' => __('Silent messages', 'nextcloud'),
		'call_recording' => __('Call recording', 'nextcloud'),
		'moderation_lobby' => __('Moderation & lobby', 'nextcloud'),
		'voting_polls' => __('Voting/polls', 'nextcloud'),
		'background_blur_custom_backgrounds' => __('Background blur & custom backgrounds', 'nextcloud'),
		'rich_interactive_widgets' => __('Rich interactive widgets', 'nextcloud'),
		'view_time_zones_local_time' => __('View time zones or local time of colleagues', 'nextcloud'),
		'outlook_integration_send_invitations' => __('Outlook integration to send invitations', 'nextcloud'),
		'note_to_self' => __('Note to self', 'nextcloud'),
		'filters_in_video_calls' => __('Filters in video calls', 'nextcloud'),
		'quick_slash_commands' => __('Quick slash commands', 'nextcloud'),
		'bots' => __('Bots', 'nextcloud'),
		'threads' => __('Threads', 'nextcloud'),
		'format_messages' => __('Format messages', 'nextcloud'),
		'chat_reactions_emojis' => __('Chat reactions (emojis)', 'nextcloud'),
		'animated_reactions_in_call' => __('Animated reactions in call', 'nextcloud'),
		'message_drafts' => __('Message drafts', 'nextcloud'),
		'waiting_music' => __('Waiting music', 'nextcloud'),
		'generate_transcript' => __('Generate transcript', 'nextcloud'),
		'live_captioning' => __('Live captioning', 'nextcloud'),
		'live_translations' => __('Live translations', 'nextcloud'),
		'integration_with_calendar_app' => __('Integration with calendar app', 'nextcloud'),
		'sidebar_view_in_calls' => __('Sidebar view in calls', 'nextcloud'),
		'profile_cards' => __('Profile cards', 'nextcloud'),
		'anonymous_access' => __('Anonymous access', 'nextcloud'),
		'guest_accounts' => __('Guest accounts', 'nextcloud'),
		'user_status' => __('User status', 'nextcloud'),
		'scheduled_messages' => __('Scheduled messages', 'nextcloud'),
	];
}

/**
 * Short code per feature, used for the translatable attributes of [nc_talk_platform_item]:
 * "{short}_lbl" (custom label) and "{short}_tip" (tooltip).
 *
 * Phrase (memsource-connector) stores attribute names in a varchar(20) and matches them without
 * a word boundary, so every short code must be ≤ 16 chars, contain no "_", and must not end with
 * another short code. Re-check the attribute names against these rules after changing this list.
 * Never change an existing short code: content saved with it would lose its label/tooltip.
 */
function nc_talk_platform_comparison_short_keys() {
	return [
		'hostable_on_premises' => 'onprem',
		'open_source' => 'oss',
		'desktop_client' => 'desktop',
		'mobile_client' => 'mobile',
		'phone_dial_in' => 'dialin',
		'phone_dial_out' => 'dialout',
		'breakout_rooms' => 'breakout',
		'stream_to_youtube' => 'youtube',
		'search_messages' => 'msgsearch',
		'filter_conversations' => 'convfilter',
		'ai_assistant_features' => 'ai',
		'note_taking_editing' => 'notes',
		'office_document_editing' => 'office',
		'public_office_document_editing' => 'publink',
		'accessibility_features' => 'a11y',
		'federation' => 'federation',
		'push_notifications' => 'push',
		'together_mode' => 'together',
		'message_reminders' => 'reminders',
		'silent_messages' => 'silent',
		'call_recording' => 'recording',
		'moderation_lobby' => 'lobby',
		'voting_polls' => 'polls',
		'background_blur_custom_backgrounds' => 'bgblur',
		'rich_interactive_widgets' => 'widgets',
		'view_time_zones_local_time' => 'timezones',
		'outlook_integration_send_invitations' => 'outlook',
		'note_to_self' => 'notetoself',
		'filters_in_video_calls' => 'videofilters',
		'quick_slash_commands' => 'slashcmds',
		'bots' => 'bots',
		'threads' => 'threads',
		'format_messages' => 'formatting',
		'chat_reactions_emojis' => 'reactions',
		'animated_reactions_in_call' => 'animreact',
		'message_drafts' => 'drafts',
		'waiting_music' => 'music',
		'generate_transcript' => 'transcript',
		'live_captioning' => 'captions',
		'live_translations' => 'translations',
		'integration_with_calendar_app' => 'calendar',
		'sidebar_view_in_calls' => 'sidebar',
		'profile_cards' => 'profilecards',
		'anonymous_access' => 'anonymous',
		'guest_accounts' => 'guests',
		'user_status' => 'userstatus',
		'scheduled_messages' => 'scheduled',
	];
}

/**
 * Translatable attribute names of [nc_talk_platform_item] (the list to enter in Phrase → Shortcodes).
 */
function nc_talk_platform_item_translatable_attributes() {
	$names = ['name'];
	foreach (nc_talk_platform_comparison_short_keys() as $short) {
		$names[] = $short . '_lbl';
		$names[] = $short . '_tip';
	}
	return $names;
}

/**
 * Status options shared by both elements.
 */
function nc_talk_platform_comparison_status_options() {
	return [
		__('No', 'nextcloud') => 'no',
		__('Yes', 'nextcloud') => 'yes',
		__('Optional', 'nextcloud') => 'optional',
		__('Limited info', 'nextcloud') => 'limited-info',
	];
}

/**
 * Old element: param_group fields for each platform.
 */
function nc_talk_platform_comparison_platform_params() {
	$params = [
		[
			'type' => 'textfield',
			'heading' => __('Platform Name', 'nextcloud'),
			'param_name' => 'name',
			'admin_label' => true,
		],
		[
			'type' => 'attach_image',
			'heading' => __('Logo', 'nextcloud'),
			'param_name' => 'logo',
		],
		[
			'type' => 'textfield',
			'heading' => __('Custom CSS class', 'nextcloud'),
			'param_name' => 'custom_class',
			'description' => __('Optional class added to the logo image', 'nextcloud'),
		]
	];

	foreach (nc_talk_platform_comparison_features() as $key => $label) {
		$params[] = [
			'type' => 'dropdown',
			'heading' => $label,
			'param_name' => $key,
			'value' => nc_talk_platform_comparison_status_options(),
			'std' => 'yes',
		];

		$params[] = [
			'type' => 'checkbox',
			'heading' => '',
			'param_name' => $key . '_has_label',
			'value' => [
				__('Custom label?', 'nextcloud') => 'yes',
			],
		];

		$params[] = [
			'type' => 'textfield',
			'heading' => __('Custom label', 'nextcloud'),
			'param_name' => $key . '_label',
			'description' => __('Optional label, shown on hover', 'nextcloud'),
			'dependency' => [
				'element' => $key . '_has_label',
				'value' => ['yes'],
			],
		];

		$params[] = [
			'type' => 'checkbox',
			'heading' => '',
			'param_name' => $key . '_has_tooltip',
			'value' => [
				__('Has tooltip?', 'nextcloud') => 'yes',
			],
		];

		$params[] = [
			'type' => 'textfield',
			'heading' => __('Tooltip', 'nextcloud'),
			'param_name' => $key . '_tooltip',
			'description' => __('Optional tooltip, shown on hover', 'nextcloud'),
			'dependency' => [
				'element' => $key . '_has_tooltip',
				'value' => ['yes'],
			],
		];
	}

	return $params;
}

/**
 * New element: fields of one [nc_talk_platform_item].
 * Status / checkbox fields keep the long feature key; label and tooltip use the short code.
 */
function nc_talk_platform_item_params() {
	$params = [
		[
			'type' => 'textfield',
			'heading' => __('Platform Name', 'nextcloud'),
			'param_name' => 'name',
			'admin_label' => true,
			'edit_field_class' => 'vc_col-xs-12 nc_pc_platform',
		],
		[
			'type' => 'attach_image',
			'heading' => __('Logo', 'nextcloud'),
			'param_name' => 'logo',
			'edit_field_class' => 'vc_col-xs-6 nc_pc_platform',
		],
		[
			'type' => 'textfield',
			'heading' => __('Custom CSS class', 'nextcloud'),
			'param_name' => 'custom_class',
			'edit_field_class' => 'vc_col-xs-6 nc_pc_platform',
			'description' => __('Optional class added to the logo image', 'nextcloud'),
		],
	];

	$short_keys = nc_talk_platform_comparison_short_keys();

	foreach (nc_talk_platform_comparison_features() as $key => $label) {
		$short = $short_keys[$key];

		$params[] = [
			'type' => 'dropdown',
			'heading' => $label,
			'param_name' => $key,
			'edit_field_class' => 'vc_col-xs-12 platform_comparison_feature nc_pc_field',
			'value' => nc_talk_platform_comparison_status_options(),
			// Same as the renderer: a missing status is "no", so the editor and the page always agree.
			'std' => 'no',
		];

		$params[] = [
			'type' => 'checkbox',
			'heading' => '',
			'param_name' => $key . '_has_label',
			'edit_field_class' => 'vc_col-xs-12 nc_pc_field nc_pc_toggle',
			'value' => [
				__('Custom label?', 'nextcloud') => 'yes',
			],
		];

		$params[] = [
			'type' => 'textfield',
			'heading' => __('Custom label', 'nextcloud'),
			'param_name' => $short . '_lbl',
			'edit_field_class' => 'vc_col-xs-12 nc_pc_field nc_pc_sub',
			'description' => __('Optional label, shown on hover', 'nextcloud'),
			'dependency' => [
				'element' => $key . '_has_label',
				'value' => ['yes'],
			],
		];

		$params[] = [
			'type' => 'checkbox',
			'heading' => '',
			'param_name' => $key . '_has_tooltip',
			'edit_field_class' => 'vc_col-xs-12 nc_pc_field nc_pc_toggle',
			'value' => [
				__('Has tooltip?', 'nextcloud') => 'yes',
			],
		];

		$params[] = [
			'type' => 'textfield',
			'heading' => __('Tooltip', 'nextcloud'),
			'param_name' => $short . '_tip',
			'edit_field_class' => 'vc_col-xs-12 nc_pc_field nc_pc_sub',
			'description' => __('Optional tooltip, shown on hover', 'nextcloud'),
			'dependency' => [
				'element' => $key . '_has_tooltip',
				'value' => ['yes'],
			],
		];
	}

	return $params;
}

/**
 * Map [nc_talk_platform_item] attributes to the platform array the renderer reads
 * (same keys as one row of the old param_group). Attributes that are missing stay missing.
 */
function nc_talk_platform_item_to_platform($atts) {
	$atts = is_array($atts) ? $atts : [];
	$platform = [];

	foreach (['name', 'logo', 'custom_class'] as $field) {
		if (isset($atts[$field])) {
			$platform[$field] = $atts[$field];
		}
	}

	foreach (nc_talk_platform_comparison_short_keys() as $key => $short) {
		$map = [
			$key => $key,
			$key . '_has_label' => $key . '_has_label',
			$short . '_lbl' => $key . '_label',
			$key . '_has_tooltip' => $key . '_has_tooltip',
			$short . '_tip' => $key . '_tooltip',
		];
		foreach ($map as $from => $to) {
			if (isset($atts[$from])) {
				$platform[$to] = $atts[$from];
			}
		}
	}

	return $platform;
}

/**
 * Old element: register + render.
 */
add_action('vc_before_init', 'nc_register_talk_platform_comparison_shortcode');
function nc_register_talk_platform_comparison_shortcode() {
	if (!function_exists('vc_map')) {
		return;
	}

	vc_map([
		'name' => __('Talk platforms Comparison', 'nextcloud'),
		'base' => 'talk_platforms_comparison',
		'icon' => 'icon-wpb-ui-tab-content',
		'category' => __('Nextcloud', 'nextcloud'),
		'description' => __('Comparison table with predefined features and repeatable platforms.', 'nextcloud'),
		'params' => [
			[
				'type' => 'param_group',
				'heading' => __('Platforms', 'nextcloud'),
				'param_name' => 'platforms',
				'params' => nc_talk_platform_comparison_platform_params(),
				'description' => __('Add one row per platform and set the status for each predefined feature.', 'nextcloud'),
			],
		],
	]);
}

add_shortcode('talk_platforms_comparison', 'nc_render_talk_platforms_comparison_shortcode');
function nc_render_talk_platforms_comparison_shortcode($atts) {
	$atts = shortcode_atts([
		'platforms' => '',
	], $atts, 'talk_platforms_comparison');

	if (!function_exists('vc_param_group_parse_atts')) {
		return '';
	}

	$platforms = vc_param_group_parse_atts($atts['platforms']);

	return nc_talk_platform_comparison_render_table($platforms);
}

/**
 * Talk platforms comparison (Translatable)
 * Parent/child version of [talk_platforms_comparison]: every platform is its own [nc_talk_platform_item]
 * with plain attributes, so Phrase can pick up the platform name, custom labels and tooltips.
 *
 * [nc_talk_platforms_comparison hide_filter_buttons="" hide_check="" el_class=""]
 *   [nc_talk_platform_item name="Nextcloud Talk" logo="123" open_source="yes" oss_lbl="…" open_source_has_label="yes"]
 * [/nc_talk_platforms_comparison]
 */
add_action('vc_before_init', 'nc_talk_platforms_comparison_vc_map');
function nc_talk_platforms_comparison_vc_map() {
	if (!class_exists('WPBakeryShortCodesContainer')) {
		return;
	}

	if (!class_exists('WPBakeryShortCode_Nc_Talk_Platforms_Comparison')) {
		class WPBakeryShortCode_Nc_Talk_Platforms_Comparison extends WPBakeryShortCodesContainer {
		}
	}
	if (!class_exists('WPBakeryShortCode_Nc_Talk_Platform_Item')) {
		class WPBakeryShortCode_Nc_Talk_Platform_Item extends WPBakeryShortCode {
		}
	}

	vc_map([
		'name' => __('Talk platforms comparison (Translatable)', 'nextcloud'),
		'base' => 'nc_talk_platforms_comparison',
		'class' => 'platforms_comparison_custom_shortcode',
		'icon' => 'icon-wpb-ui-tab-content',
		'category' => __('Nextcloud', 'nextcloud'),
		'description' => __('Talk comparison table with predefined features. Add one Talk platform item per column.', 'nextcloud'),
		'as_parent' => ['only' => 'nc_talk_platform_item'],
		'content_element' => true,
		// No 'is_container' => true: WPBakery hides such elements inside inner rows. as_parent is enough for nesting.
		'show_settings_on_create' => false,
		'js_view' => 'VcColumnView',
		'params' => nc_platform_comparison_display_params(),
	]);

	vc_map([
		'name' => __('Talk platform item', 'nextcloud'),
		'base' => 'nc_talk_platform_item',
		'class' => 'platforms_comparison_custom_shortcode',
		'icon' => 'icon-wpb-ui-tab-content',
		'category' => __('Nextcloud', 'nextcloud'),
		'as_child' => ['only' => 'nc_talk_platforms_comparison'],
		'content_element' => true,
		'params' => nc_talk_platform_item_params(),
	]);
}

// Platforms collected by [nc_talk_platform_item] while the parent [nc_talk_platforms_comparison] runs.
$GLOBALS['nc_talk_platforms_comparison_items'] = null;

add_shortcode('nc_talk_platforms_comparison', 'nc_render_nc_talk_platforms_comparison_shortcode');
function nc_render_nc_talk_platforms_comparison_shortcode($atts, $content = null) {
	$atts = shortcode_atts([
		'hide_filter_buttons' => '',
		'hide_check' => '',
		'el_class' => '',
	], $atts, 'nc_talk_platforms_comparison');

	// The table is column-based, so children don't print anything themselves:
	// they register their attributes here and the parent renders the whole table.
	$previous = $GLOBALS['nc_talk_platforms_comparison_items'];
	$GLOBALS['nc_talk_platforms_comparison_items'] = [];
	do_shortcode((string)$content);
	$items = $GLOBALS['nc_talk_platforms_comparison_items'];
	$GLOBALS['nc_talk_platforms_comparison_items'] = $previous;

	return nc_talk_platform_comparison_render_table(
		array_map('nc_talk_platform_item_to_platform', $items),
		[
			'hide_filter_buttons' => $atts['hide_filter_buttons'] !== '',
			'hide_check' => $atts['hide_check'] !== '',
			'el_class' => $atts['el_class'],
		]
	);
}

add_shortcode('nc_talk_platform_item', 'nc_render_nc_talk_platform_item_shortcode');
function nc_render_nc_talk_platform_item_shortcode($atts) {
	if (is_array($GLOBALS['nc_talk_platforms_comparison_items'])) {
		$GLOBALS['nc_talk_platforms_comparison_items'][] = is_array($atts) ? $atts : [];
	}

	return '';
}

/**
 * Render the Talk comparison table.
 *
 * @param array $platforms One array per column, keyed like a row of the old param_group
 *                         ({key}, {key}_has_label, {key}_label, {key}_has_tooltip, {key}_tooltip).
 * @param array $options hide_filter_buttons (bool), hide_check (bool), el_class (string).
 */
function nc_talk_platform_comparison_render_table($platforms, $options = []) {
	$features = nc_talk_platform_comparison_features();

	if (empty($platforms) || !is_array($platforms) || empty($features)) {
		return '';
	}

	$hide_filter_buttons = !empty($options['hide_filter_buttons']);
	$hide_check = !empty($options['hide_check']);

	$container_classes = ['comparison_table_container'];
	if (!empty($options['el_class'])) {
		foreach (preg_split('/\s+/', trim($options['el_class'])) as $extra_class) {
			$extra_class = sanitize_html_class($extra_class);
			if ($extra_class !== '') {
				$container_classes[] = $extra_class;
			}
		}
	}

	ob_start();
	?>
	<div class="<?php echo esc_attr(implode(' ', $container_classes)); ?>">

		<?php if (!$hide_filter_buttons) : ?>
		<div class="filtering_buttons">
			<button class="reset" tabindex="0" type="reset"><?php echo esc_html__('Reset', 'nextcloud'); ?></button>
			<button class="filter" tabindex="0" disabled="true"><?php echo esc_html__('Filter', 'nextcloud'); ?></button>
		</div>
		<?php endif; ?>

		<div class="table_move_arrows" style="display:none;">
			<a href="#" class="no-scroll arrow arrow-left"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
			<a href="#" class="no-scroll arrow arrow-right"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
		</div>

		<div class="comparison_table_container_inner">
			<table class="comparison_table">
				<thead>
					<tr class="platform_logos">
						<th></th>
						<?php foreach ($platforms as $platform) : ?>
							<?php
							$name = isset($platform['name']) ? $platform['name'] : '';
							$logo_id = isset($platform['logo']) ? absint($platform['logo']) : 0;
							$custom_class = isset($platform['custom_class']) ? $platform['custom_class'] : '';
							$logo_url = $logo_id ? wp_get_attachment_image_url($logo_id, 'full') : '';
							?>
							<th class="">
								<?php if (!$hide_check) : ?>
									<div class="check" tabindex="0"></div>
								<?php endif; ?>
								<?php if ($logo_url) : ?>
									<img alt="<?php echo esc_attr($name); ?>" src="<?php echo esc_url($logo_url); ?>" class="<?php echo esc_attr($custom_class); ?>" />
								<?php elseif ($name !== '') : ?>
									<span class="platform_name"><?php echo esc_html($name); ?></span>
								<?php endif; ?>
							</th>
						<?php endforeach; ?>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($features as $feature_key => $feature_label) : ?>
						<tr>
							<td class=""><?php echo esc_html($feature_label); ?></td>

							<?php foreach ($platforms as $platform) : ?>
								<?php
								// A missing status is "no" (same as the old element, whatever the editor default).
								$status = isset($platform[$feature_key]) ? $platform[$feature_key] : 'no';
								$has_custom_label = !empty($platform[$feature_key . '_has_label']);
								$custom_label = isset($platform[$feature_key . '_label']) ? trim($platform[$feature_key . '_label']) : '';
								$has_tooltip = !empty($platform[$feature_key . '_has_tooltip']);
								$tooltip = isset($platform[$feature_key . '_tooltip']) ? trim($platform[$feature_key . '_tooltip']) : '';
								$show_label = $has_custom_label && $custom_label !== '';

								echo '<td>';

								switch ($status) {
									case 'yes':
										?>
										<div class="fa fa-check-circle" title="<?php echo esc_attr__('Included', 'nextcloud'); ?>" aria-hidden="true"></div>
										<span class="screen-reader-text"><?php echo esc_html__('Included', 'nextcloud'); ?></span>
										<?php
										break;

									case 'optional':
										$optional_label = $show_label ? $custom_label : __('Optional', 'nextcloud');
										?>
										<div class="fa fa-check-circle optional" title="<?php echo esc_attr($optional_label); ?>" aria-hidden="true"></div>
										<span class="screen-reader-text"><?php echo esc_html($optional_label); ?></span>
										<?php
										break;

									case 'limited-info':
										echo esc_html($show_label ? $custom_label : __('Limited info', 'nextcloud'));
										break;

									case 'no':
									default:
										?>
										<div class="fa fa-times-circle" title="<?php echo esc_attr__('Not included', 'nextcloud'); ?>" aria-hidden="true"></div>
										<span class="screen-reader-text"><?php echo esc_html__('Not included', 'nextcloud'); ?></span>
										<?php
										break;
								}

								if ($show_label) : ?>
									<span class="block"><?php echo esc_html($custom_label); ?></span>
								<?php endif;

								if ($has_tooltip && $tooltip !== '') : ?>
									<span class="tooltip"><?php echo esc_html($tooltip); ?></span>
									<span class="show_tooltip"><?php echo esc_html__('See more', 'nextcloud'); ?></span>
								<?php endif;

								echo '</td>';
								?>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php

	return ob_get_clean();
}
