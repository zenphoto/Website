<?php
/**
 * Lists the users of a site by last login.
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

$zenphoto_tabs['overview']['subtabs']=array(gettext('User activity')=>'');
printAdminHeader('overview','User activity');
?>
<link rel="stylesheet" href="<?php echo WEBPATH.'/'.ZENFOLDER; ?>/admin-statistics.css" type="text/css" media="screen" />
</head>

<body>
<?php printLogoAndLinks(); ?>
<div id="main">
<span id="top">
<?php printTabs('home');?>
</span>
<div id="content">
<?php //printSubtabs('User activity'); ?>
	<div class="tabbox">
		<h1>User activity</h1>
		<?php printUserActivity(); ?>
	</div>
</div><!-- content -->
<?php printAdminFooter(); ?>
</div><!-- main -->
</body>
<?php echo "</html>"; ?>