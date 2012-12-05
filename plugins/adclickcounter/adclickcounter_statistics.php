<?php
/**
 * Ad click Statistics
 *
 * This plugin shows statistical graphs and info about your gallery\'s ad click counts (zenphoto.org special plugin)
 *
 * This plugin is dependent on the css of the gallery_statistics utility plugin!
 *
 * @package admin
 */

define('OFFSET_PATH', 3);
require_once(dirname(dirname(dirname(__FILE__))).'/zp-core/admin-globals.php');

admin_securityChecks(ADMIN_RIGHTS, currentRelativeURL());

if (!zp_loggedin(OVERVIEW_RIGHTS)) { // prevent nefarious access to this page.
	header('Location: ' . FULLWEBPATH . '/' . ZENFOLDER . '/admin.php?from=' . currentRelativeURL());
	exitZP();
}

$webpath = WEBPATH.'/'.ZENFOLDER.'/';

$zenphoto_tabs['overview']['subtabs']=array(gettext('Ad click statistics')=>'');
printAdminHeader('overview','Ad click statistics');
?>
<link rel="stylesheet" href="<?php echo WEBPATH.'/'.ZENFOLDER; ?>/admin-statistics.css" type="text/css" media="screen" />
<?php
/**
 * Prints a table with a bar graph of the values.
 */
function printBarGraph() {
	//$limit = $from_number.",".$to_number;
	$bargraphmaxsize = 60;
	$maxvalue = 0;
	$items = query_full_array("SELECT `aux`,`data` FROM ".prefix('plugin_storage')." WHERE `type` = 'adclickcount' AND `data` != 0 ORDER BY `data` DESC");
	$items = sortMultiArray($items, 'data', true, true, false, true);
	if($items) {
		$maxvalue = $items[0]['data'];
		$no_statistic_message = "";
	} else {
		$no_statistic_message = "<tr><td><em>".gettext("No statistic available")."</em></td><td></td><td></td><td></td></tr>";
	}
	// resort again since we want to see them by month
	$items = sortMultiArray($items, 'aux', true, true, false, true);
	$countlines = 0;
	echo "<table class='bordered'>";
	echo "<tr><th colspan='3'><strong>".gettext("Most clicked ad links - Ordered by month")."</strong>";
	echo "</th></tr>";
	$count = '';
	echo $no_statistic_message;
	foreach ($items as $item) {
		if($item['data'] != 0) {
			$count++;
			$barsize = round($item['data'] / $maxvalue * $bargraphmaxsize);
			$value = $item['data'];

			// counter to have a gray background of every second line
			if($countlines === 1) {
				$style = " style='background-color: #f4f4f4'";	// a little ugly but the already attached class for the table is so easiest overriden...
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
echo '</head>';
?>

<body>
<?php printLogoAndLinks(); ?>
<div id="main">
<span id="top">
<?php printTabs('home');?>
</span>
<div id="content">
<?php printSubtabs('Ad click counter'); ?>
<div class="tabbox">
<?php

	if(isset($_GET['removealladclickcounts'])) {
		XSRFdefender('removealladclickcounts');
		$sql = "DELETE FROM ".prefix('plugin_storage').' WHERE `type`="adclickcount"';
		query($sql);
		echo '<p class="messagebox fade-message">'.gettext('All ad click counts entries cleared from the database').'</p>';
	}
	?>
<h1><?php echo gettext("Ad Click Statistics"); ?></h1>
<p><?php echo gettext("Shows statistical graphs and info about the ad click counts."); ?></p>

<?php
if(!getOption('zp_plugin_adclickcounter')) {
	echo '<strong>'.gettext('The adclickcounter plugin is not active').'</strong>';
} else {
	?>
	<p class="buttons"><a href="?removealladclickcounts&amp;XSRFToken=<?php echo getXSRFToken('removealladclickcounts')?>"><?php echo gettext('Clear all ad click counts from database'); ?></a></p><br clear="all" />
	<br clear="all" /><br />
	<?php
	printBarGraph();
}
?>

</div>
</div><!-- content -->
<?php printAdminFooter(); ?>
</div><!-- main -->
</body>
<?php echo "</html>"; ?>