<?php
/**
 * 3rd party link checker
 *
 * Checks links of 3rd party plugins and themes regarding accessibility. 
 * If they are not the theme or plugin is considereed to be abandoned. 
 * The entries then are marked unpublished and marked with the tag 
 * <em>theme-abandoned</em> respectively <em>extension-abandoned</em>
 *
 * @package admin
 */

define('OFFSET_PATH', 3);
require_once(dirname(dirname(dirname(__FILE__))).'/zp-core/admin-globals.php');
//require_once(dirname(dirname(dirname(__FILE__))).'/zp-core/zp-extensions/zenpage/zenpage-template-functions.php');

admin_securityChecks(ADMIN_RIGHTS, currentRelativeURL());

if (!zp_loggedin(OVERVIEW_RIGHTS)) { // prevent nefarious access to this page.
	header('Location: ' . FULLWEBPATH . '/' . ZENFOLDER . '/admin.php?from=' . currentRelativeURL());
	exitZP();
}

$webpath = WEBPATH.'/'.ZENFOLDER.'/';

$zenphoto_tabs['overview']['subtabs']=array(gettext('3rd party link checker')=>'');
printAdminHeader('overview','3rd party link checker');


function checkThemesURLs() {
	global $_zp_gallery, $_zp_zenpage;
	//check themes
	$obj = new Album(NULL,'theme');
	$albums = $obj->getAlbums(0);
	$tags = array('theme-abandoned');
	?>
	<h3>3rd party themes marked as abandoned</h3>
	<ul>
	<?php
	foreach($albums as $album) {
		$theme = new Album(NULL,$album);
	  if(!$theme->hasTag('theme-officially-supported')) {
			$url = $theme->getLocation();
			if(!empty($url) && !$theme->hasTag('hosted_theme')) {
				if(!checkURL($url)) {
					//$theme->setTags($tags);
					//$theme->save();
					?>
					<li><?php echo $theme->getTitle(); ?></li>
					<?php
				}
			}
		} 
	}
	?>
	</ul>
	<?php
}	
 
function checkExtensionURLs() {
	global $_zp_gallery, $_zp_zenpage;
	//check extensions
	$obj = new ZenpageCategory('unsupported');
	$articles = $obj->getArticles('',NULL,true);
	$tags = array('extension-abandoned');
	?>
	<h3>3rd party themes marked as abandoned</h3>
	<ul>
	<?php
	foreach($articles as $article) {
		$extension = new ZenpageNews($article['titlelink']);
		$url = $extension->getCustomData();
		$cats = $extension->getCategories();
		if(!empty($url) && !in_array(array('unsupported-plugin-github','unsupported-misc-github'),$cats)) {
			if(!checkURL($url)) {
				$extension->setTags($tags);
				$extension->save();
				?>
				<li><?php echo $extension->getTitle(); ?></li>
				<?php
			}
		}
	} 
	?>
	</ul>
	<?php
}

/*
*@author Aram Kocharyan
*@link http://stackoverflow.com/questions/408405/easy-way-to-test-a-url-for-404-in-php
*/

function checkURL($url) {
	$handle = curl_init($url);
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);

	/* Get the HTML or whatever is linked in $url. */
	$response = curl_exec($handle);

	/* Check for 404 (file not found). */
	$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
	curl_close($handle);

	/* If the document has loaded successfully without any redirection or error */
	if($httpCode >= 200 && $httpCode < 300) {
		return true;
	} else {
		return false;
	}
}
?>
</head
<body>
<?php printLogoAndLinks(); ?>
<div id="main">
<span id="top">
<?php printTabs('home');?>
</span>
<div id="content">
<?php printSubtabs('3rd party link checker'); ?>
<div class="tabbox">

<h1><?php echo gettext("3rd party link checker"); ?></h1>
<p>
<?php echo gettext("Checks links of 3rd party plugins and themes regarding accessibility. If they are not the theme or plugin is assumed be have been abandoned. The entries then are marked unpublished and marked with the tag <em>theme-abandoned</em> respectively <em>extension-abandoned</em>"); ?>
</p>
<?php 
checkThemesURLs();
//checkExtensionURLs();


?>
<br clear="all" />

</div>
</div><!-- content -->
<?php printAdminFooter(); ?>
</div><!-- main -->
</body>
</html>