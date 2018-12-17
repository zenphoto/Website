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
$plugin_version = '1.5.2';

zp_register_filter('admin_utilities_buttons', 'adclickCount::button');
zp_register_filter('theme_head', 'adclickCount::getJS');

if (isset($_GET['adlink']) && isset($_GET['adcategory'])) {
	$adclick = new adclickCount();
	// here check if this is a valid advertiser and no some spam request
	if($adclick->link) {
		$adclick->addItem();
		header('location: ' . $adclick->link, true, 301);
	} 
	exitZP();
} 
/**
 * Class for handling the clickcount and validation
 */
class adclickCount {
	
	public $link = null;
	public $link_unvalidated = null;
	public $link_for_db = null;
	public $sponsorcats = array();
	
	function __construct() {
		$get = sanitize($_GET);
		
		//we need this in the real ad url without the array index name
		$adlink = trim($get['adlink']);
		unset($get['adlink']);
		
		//we only need this internally but not in the real ad url
		$adcategory = trim($get['adcategory']);
		unset($get['adcategory']);
		
		$sponsors = newAlbum('sponsors', true, true);
		$this->sponsorcats = $sponsors->getAlbums(0, null, null, true, true);
		//debuglogVar($this->sponsorcats);
		//check if this ad category exists actually
		if($this->sponsorcats && in_array('sponsors/' . $adcategory, $this->sponsorcats)) {
			//build the actual ad url from the GET data
			$this->link_unvalidated = $adlink;
			if(!empty($get)) {
				$this->link_unvalidated .= '&' . build_query($get);
			}
			//debuglog('link unvalidated: ' . $this->link_unvalidated);
			//validate that the url we got is actually a real ad url we want to redirect, otherwise nothing will happen
			$this->validatePath();
			//debuglog('link: ' . $this->link);
		}
	}

	/**
	 * Validates on construction if the path set is really one of your stored ad urls
	 * Sets the propery $isvalid so add/getItem() will not be executed if this fails.
	 * 
	 * To compare for validation the the real ad url must be stored in each ad image's location field.
	 */
	function validatePath() {
		if(!is_null($this->link_unvalidated) && $this->sponsorcats) { // to be sure we got one…
			$adlinks = array();
			foreach($this->sponsorcats as $sponsorcat) {
				$adcategory = newAlbum($sponsorcat, true, true);
				if($adcategory->getNumImages() != 0) {
					$ads = $adcategory->getImages(0);
					foreach($ads as $ad) {
						$adobj = newImage($adcategory, $ad, true);
						$adlink = trim($adobj->getLocation());
						if(!empty($adlink)) {
							$adlinks[] = $adlink;
						}
					}
				}
			}
			//debuglogVar($adlinks);
			if(in_array($this->link_unvalidated, $adlinks)) {
				$this->link = $this->link_unvalidated;
				$this->link_for_db = date('Y-m') . ' - ' . $this->link;
			}
		}
	}

	/**
	 * Adds a new item if not existing already or otherwise updates an item to the database. For internal use.
	 */
	function addItem() {
		if ($this->link) {
			$checkitem = query_single_row("SELECT `data` FROM " . prefix('plugin_storage') . " WHERE `aux` = " . db_quote($this->link_for_db) . " AND `type` = 'adclickcount'");
			if ($checkitem) {
				$downloadcount = $checkitem['data'] + 1;
				$success = query("UPDATE " . prefix('plugin_storage') . " SET `data` = " . $downloadcount . ", `type` = 'adclickcount' WHERE `aux` = " . db_quote($this->link_for_db) . " AND `type` = 'adclickcount'");
			} else {	
				$success =query("INSERT INTO " . prefix('plugin_storage') . " (`type`,`aux`,`data`) VALUES ('adclickcount'," . db_quote($this->link_for_db) . ",'1')");
			}
			return $success;
		}
		return false;
	}

	/**
	 * Gets an aditem
	 * 
	 * @return array
	 */
	function getItem() {
		if ($this->link) {
			$downloaditem = query_single_row($sql = "SELECT id, `aux`, `data` FROM " . prefix('plugin_storage') . " WHERE `type` = 'adclickcount' AND `aux` = " . db_quote($this->link_for_dbh));
			return $downloaditem;
		}
		return false;
	}
	

	/**
	 * Gets the download items from all download items from the database. For internal use in the downloadList functions.
	 * @return array
	 */
	static function getList() {
		$downloaditems = query_full_array("SELECT id, `aux`, `data` FROM " . prefix('plugin_storage') . " WHERE `type` = 'adclickcount'");
		return $downloaditems;
	}

	static function button($buttons) {
		$buttons[] = array(
				'category' => gettext('Info'),
				'enable' => true,
				'button_text' => gettext('Adcount statistics'),
				'formname' => 'adcount_button',
				'action' => FULLWEBPATH . '/' . USER_PLUGIN_FOLDER . '/adclickcounter/adclickcounter_statistics.php',
				'icon' => FULLWEBPATH . '/' . ZENFOLDER . '/images/bar_graph.png',
				'title' => gettext('Counts of ad clicks'),
				'alt' => '',
				'hidden' => '',
				'rights' => OVERVIEW_RIGHTS,
		);
		return $buttons;
	}

	static function getJS() {
		return;
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
	
	static function printBarGraph() {
		//$limit = $from_number.",".$to_number;
		$bargraphmaxsize = 90;
		$maxvalue = 0;
		$items = query_full_array("SELECT `aux`,`data` FROM " . prefix('plugin_storage') . " WHERE `type` = 'adclickcount' AND `data` != 0 ORDER BY `data` DESC");
		$items = sortMultiArray($items, 'data', true, true, false, true);
		if ($items) {
			$first = reset($items);
			$maxvalue = $first['data'];
			$no_statistic_message = "";
		} else {
			$no_statistic_message = "<tr><td><em>" . gettext("No statistic available") . "</em></td><td></td><td></td><td></td></tr>";
		}
		// resort again since we want to see them by month
		$items = sortMultiArray($items, 'aux', true, true, false, true);
		$countlines = 0;
		echo "<table class='bordered'>";
		echo "<tr><th colspan='3'><strong>" . gettext("Most clicked ad links - Ordered by month") . "</strong>";
		echo "</th></tr>";
		$count = '';
		
		echo $no_statistic_message;
		foreach ($items as $item) {
			if ($item['data'] != 0) {
				$count++;
				$barsize = round($item['data'] / $maxvalue * $bargraphmaxsize);
				$value = $item['data'];

				// counter to have a gray background of every second line
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
							<td class="statistic_title" <?php echo $style; ?>>
							<strong>
				<?php echo html_encode($item['aux']); ?>
							</strong>
							</td>
							<td class="statistic_graphwrap" <?php echo $style; ?>>
							<div class="statistic_bargraph" style="width: <?php echo $barsize; ?>%"></div>
							<div class="statistic_value"><?php echo $value; ?></div>
							</td>
							</tr>
				<?php
			} // if value != 0
		} // foreach end
				?>
				</table>
		<?php
	}

}
?>