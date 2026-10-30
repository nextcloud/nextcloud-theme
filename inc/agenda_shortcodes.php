<?php
/**
 * Event Agenda (Translatable)
 * Parent/child version of [nc_event_agenda]: every row, track, session and participant is
 * its own shortcode with plain attributes + enclosed content, so translation connectors
 * (Phrase) can pick up times, titles, descriptions and speakers as separate segments.
 *
 * [nc_agenda compact="true"]
 *   [nc_agenda_table name="Main stage"]
 *     [nc_agenda_row time="09:00" type="keynote" title="..." description="..." speaker_name="..."]
 *       [nc_agenda_text]Rich text under the row description[/nc_agenda_text]
 *       [nc_agenda_participant image="123" name="..." position="..." speaker_type="..."]
 *       [nc_agenda_track title="..." description="..."]
 *         [nc_agenda_session title="..." speaker="..."]Session description[/nc_agenda_session]
 *       [/nc_agenda_track]
 *     [/nc_agenda_row]
 *   [/nc_agenda_table]
 *   [nc_agenda_table name="Side track"]...[/nc_agenda_table]
 * [/nc_agenda]
 *
 * With more than one table, every table header gets a dropdown to switch between them,
 * so side track / extra table IDs and names no longer have to be wired up by hand.
 */
function nc_agenda_row_types() {
	return [
		'line-separator' => __('Line separator', 'nextcloud'),
		'keynote' => __('Keynote', 'nextcloud'),
		'lightning_talk' => __('Lightning talk', 'nextcloud'),
		'panel_session' => __('Panel session', 'nextcloud'),
		'roundtable' => __('Roundtable', 'nextcloud'),
		'breakout_tracks' => __('Breakout tracks', 'nextcloud'),
		'successive_sessions' => __('Successive sessions', 'nextcloud'),
	];
}

add_action('vc_before_init', 'nc_agenda_vc_map');
function nc_agenda_vc_map() {
	if (!class_exists('WPBakeryShortCodesContainer')) {
		return;
	}

	if (!class_exists('WPBakeryShortCode_Nc_Agenda')) {
		class WPBakeryShortCode_Nc_Agenda extends WPBakeryShortCodesContainer {
		}
	}
	if (!class_exists('WPBakeryShortCode_Nc_Agenda_Table')) {
		class WPBakeryShortCode_Nc_Agenda_Table extends WPBakeryShortCodesContainer {
		}
	}
	if (!class_exists('WPBakeryShortCode_Nc_Agenda_Row')) {
		class WPBakeryShortCode_Nc_Agenda_Row extends WPBakeryShortCodesContainer {
		}
	}
	if (!class_exists('WPBakeryShortCode_Nc_Agenda_Track')) {
		class WPBakeryShortCode_Nc_Agenda_Track extends WPBakeryShortCodesContainer {
		}
	}
	if (!class_exists('WPBakeryShortCode_Nc_Agenda_Session')) {
		class WPBakeryShortCode_Nc_Agenda_Session extends WPBakeryShortCode {
		}
	}
	if (!class_exists('WPBakeryShortCode_Nc_Agenda_Participant')) {
		class WPBakeryShortCode_Nc_Agenda_Participant extends WPBakeryShortCode {
		}
	}
	if (!class_exists('WPBakeryShortCode_Nc_Agenda_Text')) {
		class WPBakeryShortCode_Nc_Agenda_Text extends WPBakeryShortCode {
		}
	}

	vc_map([
		'name' => __('Nextcloud event agenda (Translatable)', 'nextcloud'),
		'base' => 'nc_agenda',
		'icon' => 'nc-agenda-icon fas fa-calendar-alt',
		'class' => 'nc_agenda_el',
		'category' => __('Custom Elements', 'nextcloud'),
		'description' => __('Add one table per stage/track. With more than one table a switcher is shown in the table header.', 'nextcloud'),
		'as_parent' => ['only' => 'nc_agenda_table'],
		'content_element' => true,
		// No 'is_container' => true: WPBakery hides such elements inside inner rows. as_parent is enough for nesting.
		'show_settings_on_create' => false,
		'js_view' => 'VcColumnView',
		'default_content' => '[nc_agenda_table name="' . __('Main stage', 'nextcloud') . '"][/nc_agenda_table]',
		'params' => [
			[
				'type' => 'textfield',
				'heading' => esc_html__('ID', 'nextcloud'),
				'param_name' => 'id',
				'description' => esc_html__('Optional. Also used as prefix for the table IDs.', 'nextcloud'),
			],
			[
				'type' => 'checkbox',
				'heading' => esc_html__('Compact table', 'nextcloud'),
				'param_name' => 'compact',
				'description' => esc_html__('Session and speaker are shown in one column.', 'nextcloud'),
				'value' => [esc_html__('Yes', 'nextcloud') => 'true'],
			],
			[
				'type' => 'textfield',
				'heading' => esc_html__('Custom CSS', 'nextcloud'),
				'param_name' => 'custom_css',
				'description' => esc_html__('Classes added to every table.', 'nextcloud'),
			],
		],
	]);

	vc_map([
		'name' => __('Agenda table', 'nextcloud'),
		'base' => 'nc_agenda_table',
		'icon' => 'nc-agenda-icon fas fa-table',
		'class' => 'nc_agenda_el',
		'category' => __('Custom Elements', 'nextcloud'),
		'as_child' => ['only' => 'nc_agenda'],
		'as_parent' => ['only' => 'nc_agenda_row'],
		'content_element' => true,
		'js_view' => 'VcColumnView',
		'params' => [
			[
				'type' => 'textfield',
				'heading' => esc_html__('Table name', 'nextcloud'),
				'param_name' => 'name',
				'admin_label' => true,
				'description' => esc_html__('Shown in the table switcher, e.g. "Main stage" or "Side track".', 'nextcloud'),
			],
			[
				'type' => 'textfield',
				'heading' => esc_html__('ID', 'nextcloud'),
				'param_name' => 'id',
				'description' => esc_html__('Optional. Generated automatically when empty.', 'nextcloud'),
			],
		],
	]);

	vc_map([
		'name' => __('Agenda row', 'nextcloud'),
		'base' => 'nc_agenda_row',
		'icon' => 'nc-agenda-icon fas fa-clock',
		'class' => 'nc_agenda_el',
		'category' => __('Custom Elements', 'nextcloud'),
		'as_child' => ['only' => 'nc_agenda_table'],
		'as_parent' => ['only' => 'nc_agenda_text,nc_agenda_participant,nc_agenda_track'],
		'content_element' => true,
		'js_view' => 'VcColumnView',
		'params' => [
			[
				'type' => 'textfield',
				'heading' => esc_html__('Time', 'nextcloud'),
				'param_name' => 'time',
				'admin_label' => true,
			],
			[
				'type' => 'checkbox',
				'heading' => esc_html__('Wide row?', 'nextcloud'),
				'param_name' => 'wide',
				'description' => esc_html__('(colspan=2 for the table row). Leave unchecked to show the speaker in a second column. Ignored for compact tables.', 'nextcloud'),
				'value' => [esc_html__('Yes', 'nextcloud') => 'true'],
			],
			[
				'type' => 'dropdown',
				'heading' => esc_html__('Type', 'nextcloud'),
				'param_name' => 'type',
				'value' => array_merge([esc_html__('Select type..', 'nextcloud') => ''], array_flip(nc_agenda_row_types())),
			],
			[
				'type' => 'checkbox',
				'heading' => esc_html__('Hide automatic track numbers', 'nextcloud'),
				'param_name' => 'hide_track_numbers',
				'dependency' => [
					'element' => 'type',
					'value' => 'breakout_tracks',
				],
				'value' => [esc_html__('Yes', 'nextcloud') => 'true'],
			],
			[
				'type' => 'attach_image',
				'heading' => esc_html__('Image', 'nextcloud'),
				'param_name' => 'image',
			],
			[
				'type' => 'textfield',
				'heading' => esc_html__('Title', 'nextcloud'),
				'param_name' => 'title',
				'admin_label' => true,
			],
			[
				'type' => 'textarea',
				'heading' => esc_html__('Description', 'nextcloud'),
				'param_name' => 'description',
				'description' => esc_html__('For formatted text or links, add an "Agenda rich text" element inside the row.', 'nextcloud'),
			],
			[
				'type' => 'attach_images',
				'heading' => esc_html__('Speaker image', 'nextcloud'),
				'param_name' => 'speaker_images',
				'group' => esc_html__('Speaker', 'nextcloud'),
			],
			[
				'type' => 'textfield',
				'heading' => esc_html__('Speaker name', 'nextcloud'),
				'param_name' => 'speaker_name',
				'group' => esc_html__('Speaker', 'nextcloud'),
			],
			[
				'type' => 'textarea',
				'heading' => esc_html__('Speaker description', 'nextcloud'),
				'param_name' => 'speaker_description',
				'group' => esc_html__('Speaker', 'nextcloud'),
			],
			[
				'type' => 'textfield',
				'heading' => esc_html__('Custom speaker type', 'nextcloud'),
				'param_name' => 'custom_speaker_type',
				'group' => esc_html__('Speaker', 'nextcloud'),
			],
		],
	]);

	vc_map([
		'name' => __('Agenda rich text', 'nextcloud'),
		'base' => 'nc_agenda_text',
		'icon' => 'nc-agenda-icon fas fa-align-left',
		'class' => 'nc_agenda_el',
		'category' => __('Custom Elements', 'nextcloud'),
		'description' => __('Formatted text shown under the row description.', 'nextcloud'),
		'as_child' => ['only' => 'nc_agenda_row'],
		'content_element' => true,
		'params' => [
			[
				'type' => 'textarea_html',
				'heading' => esc_html__('Text', 'nextcloud'),
				'param_name' => 'content',
				// Shows a preview of the text in the backend editor
				'holder' => 'div',
			],
		],
	]);

	vc_map([
		'name' => __('Agenda participant', 'nextcloud'),
		'base' => 'nc_agenda_participant',
		'icon' => 'nc-agenda-icon fas fa-user',
		'class' => 'nc_agenda_el',
		'category' => __('Custom Elements', 'nextcloud'),
		'as_child' => ['only' => 'nc_agenda_row'],
		'content_element' => true,
		'params' => [
			[
				'type' => 'attach_image',
				'heading' => esc_html__('Image', 'nextcloud'),
				'param_name' => 'image',
			],
			[
				'type' => 'textfield',
				'heading' => esc_html__('Name', 'nextcloud'),
				'param_name' => 'name',
				'admin_label' => true,
			],
			[
				'type' => 'textarea',
				'heading' => esc_html__('Position', 'nextcloud'),
				'param_name' => 'position',
			],
			[
				'type' => 'textfield',
				'heading' => esc_html__('Speaker type', 'nextcloud'),
				'param_name' => 'speaker_type',
			],
		],
	]);

	vc_map([
		'name' => __('Agenda track', 'nextcloud'),
		'base' => 'nc_agenda_track',
		'icon' => 'nc-agenda-icon fas fa-layer-group',
		'class' => 'nc_agenda_el',
		'category' => __('Custom Elements', 'nextcloud'),
		'as_child' => ['only' => 'nc_agenda_row'],
		'as_parent' => ['only' => 'nc_agenda_session'],
		'content_element' => true,
		'js_view' => 'VcColumnView',
		'params' => [
			[
				'type' => 'textfield',
				'heading' => esc_html__('Track title', 'nextcloud'),
				'param_name' => 'title',
				'admin_label' => true,
			],
			[
				'type' => 'textfield',
				'heading' => esc_html__('Track description', 'nextcloud'),
				'param_name' => 'description',
			],
			[
				'type' => 'dropdown',
				'heading' => esc_html__('Sessions display', 'nextcloud'),
				'param_name' => 'sessions_display',
				'description' => esc_html__('The dropdown is only shown when the track has more than one session.', 'nextcloud'),
				'value' => [
					esc_html__('Default sessions list', 'nextcloud') => '',
					esc_html__('Show sessions in dropdown', 'nextcloud') => 'dropdown',
				],
			],
		],
	]);

	vc_map([
		'name' => __('Agenda session', 'nextcloud'),
		'base' => 'nc_agenda_session',
		'icon' => 'nc-agenda-icon fas fa-microphone',
		'class' => 'nc_agenda_el',
		'category' => __('Custom Elements', 'nextcloud'),
		'as_child' => ['only' => 'nc_agenda_track'],
		'content_element' => true,
		'params' => [
			[
				'type' => 'textfield',
				'heading' => esc_html__('Session title', 'nextcloud'),
				'param_name' => 'title',
				'admin_label' => true,
			],
			[
				'type' => 'textarea',
				'heading' => esc_html__('Speaker', 'nextcloud'),
				'param_name' => 'speaker',
				'admin_label' => true,
			],
			[
				'type' => 'textarea_html',
				'heading' => esc_html__('Description', 'nextcloud'),
				'param_name' => 'content',
			],
		],
	]);
}

// Rendering context of the agenda shortcodes: every container pushes a frame, renders its
// content and its children add their data to that frame instead of returning markup.
// The whole agenda is then rendered at once by [nc_agenda], which knows about every table.
$GLOBALS['nc_agenda_stack'] = [];

function nc_agenda_collect($content, $frame) {
	$GLOBALS['nc_agenda_stack'][] = $frame;
	do_shortcode((string)$content);
	return array_pop($GLOBALS['nc_agenda_stack']);
}

function nc_agenda_add($key, $item) {
	$top = count($GLOBALS['nc_agenda_stack']) - 1;
	if ($top < 0) {
		return false;
	}
	$GLOBALS['nc_agenda_stack'][$top][$key][] = $item;
	return true;
}

// Decode WPBakery's escaping of [ ] " inside attribute values
function nc_agenda_decode_atts($atts, $keys) {
	foreach ($keys as $key) {
		$atts[$key] = str_replace(['`{`', '`}`', '``'], ['[', ']', '"'], $atts[$key]);
	}
	return $atts;
}

function nc_agenda_rich_content($content) {
	if ($content === null || trim($content) === '') {
		return '';
	}
	return function_exists('wpb_js_remove_wpautop') ? wpb_js_remove_wpautop($content, true) : do_shortcode(wpautop($content));
}

add_shortcode('nc_agenda', 'nc_agenda_funct');
function nc_agenda_funct($atts, $content = null) {
	$atts = shortcode_atts([
		'id' => '',
		'compact' => '',
		'custom_css' => '',
	], $atts, 'nc_agenda');

	$frame = nc_agenda_collect($content, ['tables' => []]);
	return nc_agenda_render($frame['tables'], $atts);
}

add_shortcode('nc_agenda_table', 'nc_agenda_table_funct');
function nc_agenda_table_funct($atts, $content = null) {
	$atts = shortcode_atts([
		'name' => '',
		'id' => '',
	], $atts, 'nc_agenda_table');
	$atts = nc_agenda_decode_atts($atts, ['name']);

	$frame = nc_agenda_collect($content, ['rows' => []]);
	$table = array_merge($atts, $frame);

	if (nc_agenda_add('tables', $table)) {
		return '';
	}
	// Used outside of [nc_agenda]
	return nc_agenda_render([$table], ['id' => '', 'compact' => '', 'custom_css' => '']);
}

add_shortcode('nc_agenda_row', 'nc_agenda_row_funct');
function nc_agenda_row_funct($atts, $content = null) {
	$atts = shortcode_atts([
		'time' => '',
		'wide' => '',
		'type' => '',
		'hide_track_numbers' => '',
		'image' => '',
		'title' => '',
		'description' => '',
		'speaker_images' => '',
		'speaker_name' => '',
		'speaker_description' => '',
		'custom_speaker_type' => '',
	], $atts, 'nc_agenda_row');
	$atts = nc_agenda_decode_atts($atts, ['time', 'title', 'description', 'speaker_name', 'speaker_description', 'custom_speaker_type']);

	$frame = nc_agenda_collect($content, ['texts' => [], 'participants' => [], 'tracks' => []]);
	nc_agenda_add('rows', array_merge($atts, $frame));
	return '';
}

add_shortcode('nc_agenda_text', 'nc_agenda_text_funct');
function nc_agenda_text_funct($atts, $content = null) {
	nc_agenda_add('texts', nc_agenda_rich_content($content));
	return '';
}

add_shortcode('nc_agenda_participant', 'nc_agenda_participant_funct');
function nc_agenda_participant_funct($atts, $content = null) {
	$atts = shortcode_atts([
		'image' => '',
		'name' => '',
		'position' => '',
		'speaker_type' => '',
	], $atts, 'nc_agenda_participant');

	nc_agenda_add('participants', nc_agenda_decode_atts($atts, ['name', 'position', 'speaker_type']));
	return '';
}

add_shortcode('nc_agenda_track', 'nc_agenda_track_funct');
function nc_agenda_track_funct($atts, $content = null) {
	$atts = shortcode_atts([
		'title' => '',
		'description' => '',
		'sessions_display' => '',
	], $atts, 'nc_agenda_track');
	$atts = nc_agenda_decode_atts($atts, ['title', 'description']);

	$frame = nc_agenda_collect($content, ['sessions' => []]);
	nc_agenda_add('tracks', array_merge($atts, $frame));
	return '';
}

add_shortcode('nc_agenda_session', 'nc_agenda_session_funct');
function nc_agenda_session_funct($atts, $content = null) {
	$atts = shortcode_atts([
		'title' => '',
		'speaker' => '',
	], $atts, 'nc_agenda_session');
	$atts = nc_agenda_decode_atts($atts, ['title', 'speaker']);
	$atts['description'] = nc_agenda_rich_content($content);

	nc_agenda_add('sessions', $atts);
	return '';
}

function nc_agenda_render($tables, $atts) {
	if (empty($tables)) {
		return '';
	}

	$compact = !empty($atts['compact']);
	$id_prefix = $atts['id'] !== '' ? $atts['id'] : uniqid('nc_agenda_');

	// Give every table an ID and a name for the switcher
	foreach ($tables as $key => &$table) {
		if ($table['id'] === '') {
			$table['id'] = $id_prefix . '-' . ($key + 1);
		}
		if ($table['name'] === '') {
			$table['name'] = $key === 0 ? __('Main stage', 'nextcloud') : sprintf(__('Table %d', 'nextcloud'), $key + 1);
		}
	}
	unset($table);

	$has_switcher = count($tables) > 1;

	ob_start();
	?>
	<div class="nc_agenda" <?php if ($atts['id'] !== '') { echo 'id="' . esc_attr($atts['id']) . '"'; } ?>>
	<?php foreach ($tables as $key => $table) {
		$table_classes = 'nc_event_agenda ' . $atts['custom_css'];
		if ($compact) {
			$table_classes .= ' compact_table';
		}
		if ($key > 0) {
			$table_classes .= ' hidden';
		}

		$switcher = '';
		if ($has_switcher) {
			// autocomplete="off": stop browsers restoring another table's option on back navigation
			$switcher = '<select name="table_switch" class="table_switch" autocomplete="off" aria-label="' . esc_attr__('Switch agenda', 'nextcloud') . '">';
			foreach ($tables as $option) {
				$switcher .= '<option value="' . esc_attr($option['id']) . '"' . selected($option['id'], $table['id'], false) . '>' . esc_html($option['name']) . '</option>';
			}
			$switcher .= '</select>';
		}
		$switcher_class = $has_switcher ? 'with_sidetrack_switcher' : '';
		?>
		<table id="<?php echo esc_attr($table['id']); ?>" class="<?php echo esc_attr($table_classes); ?>">
			<thead>
			<tr>
				<td class="time"><?php echo __('Time', 'nextcloud'); ?></td>
				<?php if ($compact) { ?>
					<td colspan="2" class="topic_speaker <?php echo $switcher_class; ?>"><?php echo __('Session and speaker', 'nextcloud'); ?><?php echo $switcher; ?></td>
				<?php } else { ?>
					<td class="topic topic_speaker <?php echo $switcher_class; ?>"><?php echo __('Session', 'nextcloud'); ?><?php echo $switcher; ?></td>
					<td class="speaker"><?php echo __('Speaker', 'nextcloud'); ?></td>
				<?php } ?>
			</tr>
			</thead>
			<tbody>
				<?php
				foreach ($table['rows'] as $row_key => $row) {
					echo nc_agenda_render_row($row, $table['id'] . '-' . ($row_key + 1), $compact);
				}
				?>
			</tbody>
		</table>
	<?php } ?>
	</div>
	<?php
	return ob_get_clean();
}

function nc_agenda_render_row($row, $row_id, $compact) {
	$type = $row['type'];
	$types = nc_agenda_row_types();
	$colspan = ($compact || !empty($row['wide'])) ? 2 : 1;
	$num_tracks = count($row['tracks']);

	ob_start();
	?>
	<tr>
		<td><?php echo wp_kses_post($row['time']); ?></td>
		<td colspan="<?php echo $colspan; ?>">
			<?php
			if ($type !== '' && $type !== 'successive_sessions' && isset($types[$type])) {
				echo '<span class="event_row_type ' . esc_attr($type) . '">' . esc_html($types[$type]) . '</span>';
			}

			if ($row['title'] !== '') {
				echo "<p class='table_row_title'>" . wp_kses_post($row['title']);
				if ($num_tracks > 3) {
					echo '<span class="scroll_help_text">' . __('You can scroll horizontally to see more items', 'nextcloud') . '</span>';
				}
				echo '</p>';
			}

			if ($row['image'] !== '') {
				echo '<div class="agenda_row_desc_container">';
				echo '<div class="agenda_row_feat_image"><img src="' . esc_url(wp_get_attachment_image_url($row['image'], 'full')) . '" alt="" /></div>';
			}

			echo '<div class="agenda_row_description">';
			if ($row['description'] !== '') {
				echo '<p>' . wp_kses_post($row['description']) . '</p>';
			}
			foreach ($row['texts'] as $text) {
				echo '<div class="extra_description">' . $text . '</div>';
			}
			echo '</div>'; //.agenda_row_description

			if ($row['image'] !== '') {
				echo '</div>'; //.agenda_row_desc_container
			}

			if ($num_tracks > 0) {
				echo nc_agenda_render_tracks($row, $row_id);
			}

			if ($compact) {
				echo nc_agenda_render_speakers($row);
			}
			?>
		</td>
		<?php if ($colspan == 1) { ?>
			<td><?php echo nc_agenda_render_speakers($row); ?></td>
		<?php } ?>
	</tr>
	<?php
	return ob_get_clean();
}

function nc_agenda_render_tracks($row, $row_id) {
	$type = $row['type'];
	$successive = $type === 'successive_sessions';
	$show_track_numbers = $type === 'breakout_tracks' && empty($row['hide_track_numbers']);
	$num_tracks = count($row['tracks']);
	$col_class = $num_tracks == 2 ? '6' : '4';
	$scroll_class = $num_tracks > 3 ? 'scroll' : '';

	ob_start();

	if ($num_tracks > 3) {
		echo '<div class="scroll-buttons">
		<i id="scroll-left" class="fas fa-angle-left"></i>
		<i id="scroll-right" class="fas fa-angle-right"></i>
		</div>';
	}

	echo '<div class="' . ($successive ? 'row ' : '') . 'inner_tracks_row ' . $scroll_class . '">';

	foreach ($row['tracks'] as $track_key => $track) {
		$track_no = $track_key + 1;

		if ($track['title'] === '' && !$successive) {
			continue;
		}

		echo $successive ? '<div class="col-lg-12">' : '<div class="custom-col-' . $col_class . '">';
		echo '<div class="inner_track ' . esc_attr($type) . '">';

		if ($show_track_numbers) {
			echo '<span class="track_label">' . sprintf(__('Track %d', 'nextcloud'), $track_no) . '</span>';
		}
		if ($track['title'] !== '') {
			echo "<p class='track_title'>" . wp_kses_post($track['title']) . '</p>';
		}
		if ($track['description'] !== '') {
			echo "<p class='track_description'>" . wp_kses_post($track['description']) . '</p>';
		}

		$sessions = $track['sessions'];
		$has_session_titles = count(array_filter(array_column($sessions, 'title'))) > 0;

		if (!empty($sessions) && ($has_session_titles || $successive)) {
			echo '<div class="track_sessions">';

			if ($track['sessions_display'] === 'dropdown' && count($sessions) > 1) {
				// Pick the session from a dropdown
				echo '<select name="tracks_dropdown" class="tracks_dropdown">';
				foreach ($sessions as $session_key => $session) {
					$session_no = $track_no . '.' . ($session_key + 1);
					echo '<option value="' . esc_attr($row_id . '-session-' . $track_no . '-' . ($session_key + 1)) . '">' . sprintf(__('Session %s', 'nextcloud'), $session_no) . ': ' . esc_html($session['title']) . '</option>';
				}
				echo '</select>';

				foreach ($sessions as $session_key => $session) {
					$session_no = $track_no . '.' . ($session_key + 1);
					echo '<div id="' . esc_attr($row_id . '-session-' . $track_no . '-' . ($session_key + 1)) . '" class="track_session ' . ($session_key === 0 ? 'show' : 'hidden') . '">';
					echo '<span class="session_label">' . sprintf(__('Session %s', 'nextcloud'), $session_no) . '</span>';
					if ($session['speaker'] !== '') {
						echo "<div class='session_speaker'>" . __('Speaker:', 'nextcloud') . ' ' . wp_kses_post($session['speaker']) . '</div>';
					}
					if ($session['title'] !== '') {
						echo "<p class='session_title'><b>" . __('Title:', 'nextcloud') . '</b> ' . wp_kses_post($session['title']) . '</p>';
					}
					if ($session['description'] !== '') {
						echo "<div class='session_description'>" . $session['description'] . '</div>';
					}
					echo '</div>';
				}
			} else {
				foreach ($sessions as $session_key => $session) {
					echo '<div class="track_session">';
					if ($successive) {
						echo '<span class="session_label">' . sprintf(__('Story %d', 'nextcloud'), $session_key + 1) . '</span>';
					} else {
						echo '<span class="session_label">' . sprintf(__('Session %s', 'nextcloud'), $track_no . '.' . ($session_key + 1)) . '</span>';
					}
					if ($session['title'] !== '') {
						echo "<p class='session_title'>" . wp_kses_post($session['title']) . '</p>';
					}
					if ($session['speaker'] !== '') {
						echo "<div class='session_speaker'>" . __('Speaker:', 'nextcloud') . ' ' . wp_kses_post($session['speaker']) . '</div>';
					}
					if ($session['description'] !== '') {
						echo "<div class='session_description'>" . $session['description'] . '</div>';
					}
					echo '</div>';
				}
			}

			echo '</div>'; //.track_sessions
		}

		echo '</div>'; //.inner_track
		echo '</div>';
	}

	echo '</div>'; //.inner_tracks_row

	return ob_get_clean();
}

function nc_agenda_render_speakers($row) {
	$html = '';
	$image_ids = $row['speaker_images'] !== '' ? explode(',', $row['speaker_images']) : [];
	$has_speaker = !empty($image_ids) || $row['speaker_name'] !== '' || $row['speaker_description'] !== '' || $row['custom_speaker_type'] !== '';

	if ($has_speaker) {
		$num_speakers = count($image_ids);
		$html .= '<div class="event_speaker' . ($num_speakers > 1 ? ' multiple_speakers' : '') . '"' . ($num_speakers ? ' data-speakers="' . $num_speakers . '"' : '') . '>';

		if ($num_speakers) {
			$html .= '<div class="event_speaker_img">';
			foreach ($image_ids as $image_id) {
				$html .= '<div class="speaker_img_holder"><img src="' . esc_url(wp_get_attachment_image_url($image_id, 'full')) . '" class="speaker_image" alt="' . esc_attr(wp_strip_all_tags($row['speaker_name'])) . '" /></div>';
			}
			$html .= '</div>';
		}

		$html .= '<div class="event_speaker_title">';
		if ($row['custom_speaker_type'] !== '') {
			$html .= "<div class='speaker_type'>" . wp_kses_post($row['custom_speaker_type']) . '</div>';
		}
		if ($row['speaker_name'] !== '') {
			$html .= "<p class='speaker_name'>" . wp_kses_post($row['speaker_name']) . '</p>';
		}
		if ($row['speaker_description'] !== '') {
			$html .= "<p class='speaker_description'>" . wp_kses_post($row['speaker_description']) . '</p>';
		}
		$html .= '</div>';
		$html .= '</div>';
	}

	if (!empty($row['participants'])) {
		$html .= '<div class="participants event_speaker multiple_speakers" data-speakers="' . count($row['participants']) . '">';
		foreach ($row['participants'] as $participant) {
			$html .= '<div class="event_speaker_container event_speaker_img">';
			if ($participant['image'] !== '') {
				$html .= '<div class="speaker_img_holder"><img src="' . esc_url(wp_get_attachment_image_url($participant['image'], 'full')) . '" class="speaker_image" alt="' . esc_attr(wp_strip_all_tags($participant['name'])) . '" /></div>';
			}
			if ($participant['name'] !== '') {
				$html .= '<div class="name">' . wp_kses_post($participant['name']) . '</div>';
			}
			if ($participant['position'] !== '') {
				$html .= '<div class="position">' . wp_kses_post($participant['position']) . '</div>';
			}
			if ($participant['speaker_type'] !== '') {
				$html .= '<div class="speaker_type">' . wp_kses_post($participant['speaker_type']) . '</div>';
			}
			$html .= '</div>';
		}
		$html .= '</div>';
	}

	return $html;
}