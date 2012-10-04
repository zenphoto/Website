<?php
/**
 * Ad counter for our site ads by month
 *	
 * @author Malte Müller (acrylian)
 * @package plugins
 */
$plugin_is_filter = 20|ADMIN_PLUGIN|THEME_PLUGIN;
$plugin_description = gettext('Ad counter for our site ads by month.');
$plugin_author = "Malte Müller (acrylian)";
$plugin_version = '1.4.3';

zp_register_filter('admin_utilities_buttons', 'adCountButton');
zp_register_filter('theme_head','adCountJS');

 	
if(isset($_GET['link']) && isset($_GET['cat'])) {
	$link = sanitize($_GET['link']);
	$cat = sanitize($_GET['cat']);
	$path = date('Y-m').' - '.$cat.' - '.$link;
	$itemcheck = getAdCountItem($path);
	if($itemcheck) {
		updateAdCount($path);	
	} else {
		addAdCountItem($path);
	}
}


function updateAdCount($path) {
		$checkitem = query_single_row("SELECT `data` FROM ".prefix('plugin_storage')." WHERE `aux` = ".db_quote($path)." AND `type` = 'adclickcount'");
		if($checkitem) {
			$downloadcount = $checkitem['data']+1;
			query("UPDATE ".prefix('plugin_storage')." SET `data` = ".$downloadcount.", `type` = 'adclickcount' WHERE `aux` = ".db_quote($path)." AND `type` = 'adclickcount'");
		}
	}
	
	
	function adCountButton($buttons) {
		$buttons[] = array(
									'category'=>gettext('Info'),
									'enable'=>true,
									'button_text'=>gettext('Adcount statistics'),
									'formname'=>'adcount_button',
									'action'=>WEBPATH.'/'.USER_PLUGIN_FOLDER.'/adclickcounter/adclickcounter_statistics.php',
									'icon'=> WEBPATH.'/'.ZENFOLDER.'/images/bar_graph.png',
									'title'=>gettext('Counts of ad clicks'),
									'alt'=>'',
									'hidden'=> '',
									'rights'=> OVERVIEW_RIGHTS,
									);
		return $buttons;
	}
	
	
	function adCountJS() {
	?>
	<script type="text/javascript">
   	$(document).ready(function(){
				$('a.platinum-ad, a.palladium-ad, a.gold-ad,a.silver-ad,a.bronze-ad').click(function() {
  				var adlink = $(this).attr('href');
  				var adcat = $(this).attr('class'); // we have sometimes an extra class and need only the first
  				$.ajax({
  					data: { 
  						link: adlink, 
  						cat: adcat 
  					}
					})
  			});
  		});
  	</script>
  	<?php
	}


	/**
	 * Adds a new download item to the database. For internal use.
	 * @param string $path Path of the download item
	 */
	function addAdCountItem($path) {
		$checkitem = query_single_row("SELECT `data` FROM ".prefix('plugin_storage')." WHERE `aux` = ".db_quote($path)." AND `type` = 'adclickcount'");
		if(!$checkitem) {
			query("INSERT INTO ".prefix('plugin_storage')." (`type`,`aux`,`data`) VALUES ('adclickcount',".db_quote($path).",'1')");
		}
	}

	/**Gets the download items from all download items from the database. For internal use in the downloadList functions.
	 * @return array
	 */
		function getAdCountList() {
		$downloaditems = query_full_array("SELECT id, `aux`, `data` FROM ".prefix('plugin_storage')." WHERE `type` = 'adclickcount'");
		return $downloaditems;
	}
	
	function getAdCountItem($file) {
		$downloaditem = query_single_row($sql = "SELECT id, `aux`, `data` FROM ".prefix('plugin_storage')." WHERE `type` = 'adclickcount' AND `aux` = ".db_quote($file));
		return $downloaditem;
	}
	



?>