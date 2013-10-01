<?php
/**
 * Makes the download list for Zenphoto releases
 *
 * This plugin replaces the function for the codeblock on the "older-versions-archive" page of the site.
 * It is seeded with the links for the releases up to February 11, 2013.
 *
 * The codeblock#1 code is:
 *
 * <code>
 * <?php if(class_exists("releaseList")) {
 * global $releaseList;
 * $releaseList->listDownloads();
 * } ?>
 * </code>
 *
 * The content of the page should read
 * <pre>
 * <p>Here you can download older versions of Zenphoto.
 * Generally we recommend to only use the latest as we are only able to provide minimal
 * support for older versions.</p>
 * </pre>
 *
 * When a new release is tagged it needs to be added to the plugin's list. If the release is a
 * support release all that needs be done is add the release number. A news article will automatically
 * be created for the support release. It will not be tweeted or announced. It is my personal
 * view that we should only announce the feature releases. If someone wants to know of a support
 * release they should subscribe to the RSS or simply use the <var>check_for_update</var> plugin
 * in their installation.
 *
 * The plugin presumes that a new release with only three digits is a feature release so it does not
 * create a news article. It is the releaser's responsibility to create and publish that article.
 *
 * @package plugins
 * @subpackage zenphoto
 *
 */
$plugin_is_filter = 5 | ADMIN_PLUGIN | THEME_PLUGIN;
$plugin_description = gettext("Release download list.");
$plugin_author = "Stephen Billard (sbillard)";

$option_interface = 'releaseList';


if (OFFSET_PATH) {
	require_once(SERVERPATH . '/' . ZENFOLDER . '/' . PLUGIN_FOLDER . '/rss.php');
} else {
	$releaseList = new releaseList();
}

if (!defined('NEWS_POSITION_NORMAL'))
	define('NEWS_POSITION_NORMAL', 0);
if (!defined('NEWS_POSITION_STICK_TO_TOP'))
	define('NEWS_POSITION_STICK_TO_TOP', 9);

class releaseList {

	var $releaseList = array();
	var $latest = NULL;

	function __construct() {
		setOptionDefault('releaseList_text', '<p>Zenphoto %s is a bugfix release. Multiple minor errors are corrected.</p>' .
						'<p>As usual we recommend all users upgrade for the latest updates and fixes. ' .
						'For more detailed info about the fixes please review the <a href="https://github.com/zenphoto/zenphoto/issues">GitHub issues</a> list.</p>');
		$resource = query('SELECT * FROM ' . prefix('plugin_storage') . ' WHERE `type`="releaseList" ORDER BY `aux`');
		if ($resource) {
			while ($row = db_fetch_assoc($resource)) {
				$this->releaseList[$row['aux']] = $row['data'];
			}
			db_free_result($resource);
		}
		if (empty($this->releaseList)) {
			$this->releaseList = unserialize(
							'a:37:{s:7:"1.4.3.5";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:7:"1.4.3.4";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:7:"1.4.3.3";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:7:"1.4.3.2";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:7:"1.4.3.1";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:5:"1.4.3";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:7:"1.4.2.4";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:7:"1.4.2.3";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:7:"1.4.2.2";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:7:"1.4.2.1";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:5:"1.4.2";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:7:"1.4.1.6";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:7:"1.4.1.5";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:7:"1.4.1.4";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:7:"1.4.1.3";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:7:"1.4.1.2";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:7:"1.4.1.1";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:5:"1.4.1";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:7:"1.4.0.4";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:7:"1.4.0.3";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:7:"1.4.0.2";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:7:"1.4.0.1";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:3:"1.4";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:7:"1.3.1.2";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:7:"1.3.1.1";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:5:"1.3.1";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:3:"1.3";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:5:"1.2.9";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:5:"1.2.8";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:5:"1.2.7";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:5:"1.2.6";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:5:"1.2.5";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:5:"1.2.4";s:69:"<a href="//zenphoto.googlecode.com/files/zenphoto-%1$s.%2$s">%3$s</a>";s:8:"1.4.4.1b";s:76:"<a href="//github.com/zenphoto/zenphoto/archive/zenphoto-%1$s.%2$s">%3$s</a>";s:8:"1.4.4.1a";s:76:"<a href="//github.com/zenphoto/zenphoto/archive/zenphoto-%1$s.%2$s">%3$s</a>";s:7:"1.4.4.1";s:76:"<a href="//github.com/zenphoto/zenphoto/archive/zenphoto-%1$s.%2$s">%3$s</a>";s:5:"1.4.4";s:76:"<a href="//github.com/zenphoto/zenphoto/archive/zenphoto-%1$s.%2$s">%3$s</a>";}'
			);
			foreach ($this->releaseList as $key => $release) {
				query('INSERT INTO ' . prefix('plugin_storage') . ' (`type`, `aux`,`data`) VALUES ("releaseList",' . db_quote($key) . ',' . db_quote($release) . ')');
			}
		}
		$list = $this->releaseList;
		krsort($list);
		$list = array_keys($list);
		$this->latest = array_shift($list);
		setOptionDefault('releaseList_current', $this->latest);
	}

	function getOptionsSupported() {
		$option = getOption('releaseList_current');
		if ($option && $option != $this->latest) {
			query('INSERT INTO ' . prefix('plugin_storage') . ' (`type`, `aux`,`data`) VALUES ("releaseList",' . db_quote($option) . ',' . db_quote('<a href="//github.com/zenphoto/zenphoto/archive/zenphoto-%1$s.%2$s">%3$s</a>') . ')');
			setOption('releaseList_current', $option);
			$rel = explode('.', $option);
			if (count($rel) > 3) {
				// it is a support release, handle the news posting
				$catobj = new ZenpageCategory('release', false);
				$articles = $catobj->getArticles(0, 'all', false, 'date', 'desc', 1);
				if ($articles) {
					$article = array_shift($articles);
					$last = new ZenpageNews($article['titlelink']);
					$last->setSticky(NEWS_POSITION_NORMAL);
					$last->save();
				}
				$date = date('Y-m-d H:i:s');
				$content = sprintf(get_language_string(getOption('releaseList_text')), $option);

				$article = new ZenpageNews('zenphoto-' . $option, true);
				$article->setTitle('Zenphoto ' . $option);
				if (!$article->getContent()) {
					$article->setContent($content);
				}
				$article->setShow(1);
				$article->setDateTime($date);
				$article->setAuthor('releaseList');
				$article->setLastchange($date);
				$article->setLastchangeauthor('releaseList');
				$article->setSticky(NEWS_POSITION_STICK_TO_TOP);
				$article->setCategories(array('release', 'changelog', 'news', 'announcements'));
				$article->save();

				// announce the release
				if (class_exists('zp_announce')) {
					$_POST['announce_me'] = true;
					zp_announce::execute(NULL, $article);
				}
				if (class_exists('tweet')) {
					$_POST['tweet_me'] = true;
					tweet::tweeterZenpageExecute(NULL, $article);
				}

				//	clear the caches
				$rss = new RSS();
				$rss->clearCache();
				Gallery::clearCache();
			}
		}
		$options = array(gettext('Add release') => array('key'		 => 'releaseList_current', 'type'	 => OPTION_TYPE_TEXTBOX,
										'order'	 => 1,
										'desc'	 => gettext('Enter the release number of the download set to be added.')),
						gettext('News text')	 => array('key'		 => 'releaseList_text', 'type'	 => OPTION_TYPE_TEXTAREA,
										'order'	 => 1,
										'desc'	 => gettext('Enter the text to be used for support release articles. Feature release articles are not created by the plugin.'))
		);
		return $options;
	}

	function handleOption($option, $currentValue) {

	}

	function listDownloads() {
		$list = $this->releaseList;
		krsort($list);
		?>
		<ol class="downloadList">
			<?php
			foreach ($list as $release => $link) {
				$zip = sprintf($link, $release, 'zip', 'ZIP');
				$tar = sprintf($link, $release, 'tar.gz', 'TAR.GZ');
				?>
				<li>Zenphoto <?php echo $release; ?>
					<ol class="downloadLink">
						<li><?php echo $zip; ?></li>
						<li><?php echo $tar; ?></li>
					</ol>
				</li>
				<?php
			}
			?>
		</ol>
		<?php
	}

}
?>
