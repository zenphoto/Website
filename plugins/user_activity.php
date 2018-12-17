<?php
/**
 * Lists the users of a site by last login.
 * 	
 * @author Malte Müller (acrylian)
 * @package plugins
 * @subpackage zenphoto
 */
$plugin_is_filter = 20 | ADMIN_PLUGIN | THEME_PLUGIN;
$plugin_description = gettext('Lists the users of a site by last login.');
$plugin_author = "Malte Müller (acrylian)";
$plugin_version = '1.4.5';

zp_register_filter('admin_utilities_buttons', 'userActivityButton');

function userActivityButton($buttons) {
	$buttons[] = array(
			'category' => gettext('Info'),
			'enable' => true,
			'button_text' => gettext('User activity'),
			'formname' => 'user_activity_button',
			'action' => FULLWEBPATH . '/' . USER_PLUGIN_FOLDER . '/user_activity/user_activity_list.php',
			'icon' => FULLWEBPATH . '/' . ZENFOLDER . '/images/bar_graph.png',
			'title' => gettext('Lists the users of a site by last login.'),
			'alt' => '',
			'hidden' => '',
			'rights' => OVERVIEW_RIGHTS,
	);
	return $buttons;
}

/**
 * Prints a table with a bar graph of the values.
 */
function printUserActivity() {
	global $_zp_authority, $_zp_current_admin_obj, $_zp_zenpage;
	// resort again since we want to see them by month
	$items = array(); //sortMultiArray($items, 'aux', true, true, false, true);
	$countlines = 0;
	if (empty($author)) {
		$author = $_zp_current_admin_obj->getUser();
	}
	if (zp_loggedin(MANAGE_ALL_PAGES_RIGHTS | MANAGE_ALL_NEWS_RIGHTS)) {
		$pages = array();
		$pages = $_zp_zenpage->getPages();
		$pages = sortMultiArray($pages, 'lastchange', true, true, false, true);
		$admins = array();
		$users = array();
		$admins = $_zp_authority->getAdministrators();
	}
	?>
	<table class='bordered'>
		<tr>
			<th><strong><?php echo gettext("No."); ?></strong></th>
			<th><strong><?php echo gettext("Title (titlelink)"); ?></strong></th>
			<th><strong><?php echo gettext("Last change by"); ?></strong></th>
			<th><strong><?php echo gettext("Name (user)"); ?></strong></th>
			<th><strong><?php echo gettext("Last logon"); ?></strong></th>
		</tr>
		<?php
		$count = '';
		foreach ($pages as $page) {
			foreach ($admins as $admin) {
				if ($admin['user'] == $page['titlelink']) {
					$count++;
					if ($countlines === 1) {
						$style = " style='background-color: #f4f4f4'"; // a little ugly but the already attached class for the table is so easiest overriden...
						$countlines = 0;
					} else {
						$style = "";
						$countlines++;
					}
					?>
					<tr class="statistic_wrapper">
						<td class="statistic_counter" <?php echo $style; ?>>
							<?php echo $count; ?>
						</td>

						<td class="statistic_title" <?php echo $style; ?> >
							<strong>
								<a href="<?php echo WEBPATH . '/' . ZENFOLDER . '/' . PLUGIN_FOLDER . "/zenpage/admin-edit.php?page&amp;titlelink=" . $page['titlelink']; ?>">
									<?php echo get_language_string($page['title']); ?> (<?php echo $page['titlelink']; ?>)
								</a>	
							</strong>
						</td>

						<td <?php echo $style; ?>>
							<?php echo $page['lastchange']; ?> by <?php echo $page['author']; ?>
						</td>

						<td <?php echo $style; ?>>
							<?php echo $admin['name']; ?> (<?php echo $admin['user']; ?>)	
						</td>

						<td<?php echo $style; ?>>
							<?php echo $admin['lastloggedin']; ?>	
						</td>

					</tr>
					<?php
				}
			}
		} // foreach end
		?>
	</table>
	<?php
}
?>