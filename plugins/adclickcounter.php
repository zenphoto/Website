<?php

/**
 * Ad counter for our site ads by month
 * 	
 * @author Malte Müller (acrylian)
 * @package plugins
 * @subpackage zenphoto
 */
$plugin_is_filter = 20 | ADMIN_PLUGIN | THEME_PLUGIN;
$plugin_description = gettext('Ad counter for our site ads by month.');
$plugin_author = "Malte Müller (acrylian)";
$plugin_version = '1.5';

zp_register_filter('admin_utilities_buttons', 'adclickCount::button');
zp_register_filter('theme_head', 'adclickCount::getJS');


if (isset($_GET['adlink']) && isset($_GET['adcategory'])) {
	$link = sanitize($_GET['adlink']);
	$cat = sanitize($_GET['adcategory']);
	$path = date('Y-m') . ' - ' . $cat . ' - ' . $link;
	// here check if this is a valid advertiser and no some spam request
	$itemcheck = adclickCount::getItem($path);
	if ($itemcheck) {
		adclickCount::update($path);
	} else {
		adclickCount::addItem($path);
	}
	exitZP();
}

class adclickCount {

	static function update($path) {
		$checkitem = query_single_row("SELECT `data` FROM " . prefix('plugin_storage') . " WHERE `aux` = " . db_quote($path) . " AND `type` = 'adclickcount'");
		if ($checkitem) {
			$downloadcount = $checkitem['data'] + 1;
			query("UPDATE " . prefix('plugin_storage') . " SET `data` = " . $downloadcount . ", `type` = 'adclickcount' WHERE `aux` = " . db_quote($path) . " AND `type` = 'adclickcount'");
		}
	}

	/**
	 * Adds a new download item to the database. For internal use.
	 * @param string $path Path of the download item
	 */
	static function addItem($path) {
		$checkitem = query_single_row("SELECT `data` FROM " . prefix('plugin_storage') . " WHERE `aux` = " . db_quote($path) . " AND `type` = 'adclickcount'");
		if (!$checkitem) {
			query("INSERT INTO " . prefix('plugin_storage') . " (`type`,`aux`,`data`) VALUES ('adclickcount'," . db_quote($path) . ",'1')");
		}
	}

	/**
	 * Gets the download items from all download items from the database. For internal use in the downloadList functions.
	 * @return array
	 */
	static function getList() {
		$downloaditems = query_full_array("SELECT id, `aux`, `data` FROM " . prefix('plugin_storage') . " WHERE `type` = 'adclickcount'");
		return $downloaditems;
	}

	/**
	 * Gets an aditem
	 * 
	 * @param type $path
	 * @return type
	 */
	static function getItem($path) {
		$downloaditem = query_single_row($sql = "SELECT id, `aux`, `data` FROM " . prefix('plugin_storage') . " WHERE `type` = 'adclickcount' AND `aux` = " . db_quote($path));
		return $downloaditem;
	}

	static function button($buttons) {
		$buttons[] = array(
				'category' => gettext('Info'),
				'enable' => true,
				'button_text' => gettext('Adcount statistics'),
				'formname' => 'adcount_button',
				'action' => WEBPATH . '/' . USER_PLUGIN_FOLDER . '/adclickcounter/adclickcounter_statistics.php',
				'icon' => WEBPATH . '/' . ZENFOLDER . '/images/bar_graph.png',
				'title' => gettext('Counts of ad clicks'),
				'alt' => '',
				'hidden' => '',
				'rights' => OVERVIEW_RIGHTS,
		);
		return $buttons;
	}

	static function getJS() {
		?>
		<script type="text/javascript">
			$(document).ready(function () {
				$('a.platinum-ad, a.palladium-ad, a.gold-ad,a.silver-ad,a.bronze-ad').click(function () {
					var adlink = $(this).attr('href');
					var adcat = $(this).attr('class'); // we have sometimes an extra class and need only the first
					$.ajax({
						data: {
							adlink: adlink,
							adcategory: adcat
						}
					})
				});
			});
		</script>
		<?php

	}

}
?>