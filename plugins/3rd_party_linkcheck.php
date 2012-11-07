<?php
/* Checks info/download links of 3rd party theme and extensions entry. For Zenphoto.org only.
 *
 * @package plugins
 */
$plugin_description = gettext('Checks info/download links of 3rd party theme and extensions entry. For Zenphoto.org only.');
$plugin_author = "Malte Müller (acrylian)";
$plugin_version = '1.4.1';
$option_interface = 'linkchecker';

$linkcheckinterval = getOption('zenphoto_linkcheck_interval');
$lastlinkcheck = getOption('zenphoto_linkcheck_last');
if(time()-$lastlinkcheck < $linkcheckinterval) {
	setOption('zenphoto_linkcheck_last',time());
	zp_register_filter('theme_head','checkThemesAndExtensionURLs');
}

class linkchecker {
	function linkchecker() {
		setOptionDefault('zenphoto_linkcheck_interval', 604800);
		setOptionDefault('zenphoto_linkcheck_last', time());
	}

	function getOptionsSupported() {
			return array(gettext('Linkcheck - interval') => array('key' => 'zenphoto_linkcheck_interval', 'type' => OPTION_TYPE_TEXTBOX, 'desc' => gettext("The interval the media should be changed. Default is 604800 (7 * 1 day (86400 seconds = 24 hrs * 60 min * 60 sec)."))
								);
	}
}

function checkThemesAndExtensionURLs() {
	global $_zp_gallery, $_zp_zenpage;
	//check themes
	$obj = new Album($_zp_gallery,'theme');
	$albums = $obj->getAlbums(0);
	$tags = array('theme-abandoned');
	foreach($albums as $album) {
		$theme = new Album($_zp_gallery,$album);
		if(!$theme->hasTag('theme-officially-supported')) {
			$url = $theme->getLocation();
			if(!empty($url) && $url != 'hostedtheme' && !$theme->hasTag('theme-abandoned')) {
				if(checkURL($url)) {
					$theme->setTags($tags);
					$theme->save();
				}
			}
		}
	}
	//check extensions
	$obj = new ZenpageCategory('unsupported');
	$articles = $obj->getArticles('',NULL,true);
	$tags = array('extension-abandoned');
	foreach($articles as $article) {
		$extension = new ZenpageNews($article);
		$url = $extension->getCustomData();
		if(!empty($url) && $url != 'hostedextension' && !$extension->hasTag('extension-abandoned')) {
			if(checkURL($url)) {
				$extension->setTags($tags);
				$extension->save();
			}
		}
	}
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
		return false;
	} else {
		return true;
	}
}

?>