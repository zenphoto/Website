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
	adclickCount::printBarGraph();
}
?>
</div>
</div><!-- content -->
<?php printAdminFooter(); ?>
</div><!-- main -->
</body>
<?php echo "</html>"; ?>