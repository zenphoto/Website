<?php
/* Generates "news" articles for plugins
 *
 * @package plugins
 */
$plugin_is_filter = 5|ADMIN_PLUGIN;
$plugin_description = gettext('Generates news articles for supported plugins.');
$plugin_author = "Stephen Billard (sbillard)";
$plugin_version = '1.4.3';
$_plugin_excludes = array();

require_once(SERVERPATH.'/'.ZENFOLDER.'/pluginDoc.php');

function pluginNews_button($buttons) {
	if (isset($_REQUEST['pluginNews'])) {
		XSRFdefender('pluginNews');
		processPlugins();
	}
	$buttons[] = array(
								'category'=>gettext('development'),
								'enable'=>true,
								'button_text'=>gettext('Plugin Articles'),
								'formname'=>'pluginNews_button',
								'action'=>'?pluginNews=gen',
								'icon'=>'images/add.png',
								'title'=>gettext('Generate plugin articles'),
								'alt'=>'',
								'hidden'=> '<input type="hidden" name="pluginNews" value="gen" />',
								'rights'=> ADMIN_RIGHTS,
								'XSRFTag' => 'pluginNews'
								);
	return $buttons;
}

function parseAuthor($line) {
	global $tags;
	preg_match_all('|(.*?)\((.+?)\)[, ]*|', $line, $matches);
	$authors = $matches[2];
	foreach ($authors as $key=>$author) {
		$authors[$key] = 'author_'.$author;
	}
	$tags = array_merge($tags,$authors);
	return $matches;
}

function processPlugins() {
	global $plugin_author;
	global $tags;
	$curdir = getcwd();
	$basepath = SERVERPATH."/".ZENFOLDER.'/'.PLUGIN_FOLDER.'/';
	chdir($basepath);
	$filelist = safe_glob('*.php');
	foreach ($filelist as $file) {
		global $_plugin_excludes;
		$titlelink = stripSuffix(filesystemToInternal($file));
		if (in_array($titlelink, $_plugin_excludes)) {
			continue;
		}
		$pluginStream = file_get_contents($basepath.$file);
		$plugin_news = new ZenpageNews($titlelink);
		$author = $plugin_description = $plugin_author = $plugin_version = '';
		$tags = array();
		if ($str = isolate('$plugin_description', $pluginStream)) {
			if (false === eval($str)) {
				$plugin_description = gettext('<strong>Error parsing <em>plugin_description</em> string!</strong>.');
			}
		}
		if ($str = isolate('$plugin_author', $pluginStream)) {
			if (false === eval($str)) {
				$plugin_author = gettext('<strong>Error parsing <em>plugin_author</em> string!</strong>.');
			}
		}
		if ($str = isolate('$plugin_version', $pluginStream)) {
			if (false === eval($str)) {
				$plugin_version = ' '.gettext('<strong>Error parsing <em>plugin_version</em> string!</strong>.');
			}
		}
		$desc = '';
		$author = parseAuthor($plugin_author);
		if ($author) {
			$contributors = array();
			foreach ($author[0] as $key=>$an_author) {
				$contributor = trim($author[1][$key]);
				if (!empty($an_author[2])) {
					$contributor .= ' (<em>'.$author[2][$key].'</em>)';
				}
				$contributors[] = $contributor;
			}
			$author = implode(', ',$contributors);
			$desc .= '<p class="plugin_author">Developed by '.$author.'</p>';
		} else {
			$authors = stripSuffix(basename(__FILE__));
		}
		$desc .= '<div class="plugin_description">'.$plugin_description.'</div>';

		$i = strpos($pluginStream, '/*');
		$j = strpos($pluginStream, '*/');
		if ($i !== false && $j !== false) {
			$commentBlock = substr($pluginStream, $i+2, $j-$i-2);
			$desc .= processCommentBlock($commentBlock);
		}


		if ($plugin_news->loaded) {
			$tags = array_unique(array_merge($plugin_news->getTags(), $tags));
			$categories = array();
			$catlist = $plugin_news->getCategories();
			foreach ($catlist as $category) {
				$categories[] = $category['titlelink'];
			}
		} else {
			$plugin_news->setShow(0);
			$plugin_news->setDateTime(date('Y-m-d H:i:s'),filemtime($file));
			$plugin_news->setTitle($titlelink);
			$categories = array('officially-supported','extensions');
		}
		$plugin_news->setAuthor(strip_tags($author));
		$plugin_news->setContent($desc);
		$plugin_news->setCustomData("http://www.zenphoto.org/documentation/plugins/_".PLUGIN_FOLDER."---".$titlelink.".html");
		$plugin_news->save();
		$plugin_news->setTags($tags);
		$plugin_news->setCategories($categories);
		$plugin_news->save();
	}
	chdir($curdir);

}

zp_register_filter('admin_utilities_buttons', 'pluginNews_button');

?>