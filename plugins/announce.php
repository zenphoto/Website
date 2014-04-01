<?php

/**
 * Use to send announcements to the Zenphoto announce group
 *
 * Only articles in the "announcements" category are enabled for this mailing. The currently logged in
 * administrator's name and e-mail are used as the "sender" of the mail.
 *
 * The content of the article is used for the body of the e-mail. Beginning and ending paragraph tags are
 * replaced by newlines. BR tags are also replaced by newlines.
 *
 * @package plugins
 * @author Stephen Billard (sbillard)
 * @subpackage zenphoto
 */
$plugin_is_filter = 9 | ADMIN_PLUGIN;
$plugin_description = gettext('Forward announcements to the zenphoto-announce Google group.');
$plugin_author = "Stephen Billard (sbillard)";

zp_register_filter('general_zenpage_utilities', 'zp_announce::checkbox');
zp_register_filter('save_article_custom_data', 'zp_announce::execute');
zp_register_filter('admin_head', 'zp_announce::head');

class zp_announce {

	static function head() {
		if (extensionEnabled('tweet_news')) {
			?>
			<script type="text/javascript">
				// <!-- <![CDATA[
				function tweet_tandem() {
					$('#announce_me').prop('checked', $('#tweet_me').prop('checked'));
				}
				$(window).load(function() {
					tweet_tandem();
					$("#tweet_me").click(function() {
						tweet_tandem();
					});
				});
				// ]]> -->
			</script>
			<?php

		}
	}

	static function checkbox($before, $object, $prefix = NULL) {
		if (get_class($object) == 'ZenpageNews' && $object->inNewsCategory('announcements')) {
			$before .= '<p class="checkbox">' . "\n" . '<label title="' . gettext("Send to the Google Zenphoto announce group.") . '">' . "\n" . '<input type="checkbox" name="announce_me' . $prefix . '" id="announce_me' . $prefix . '" value="1" /> <img src="' . WEBPATH . '/' . ZENFOLDER . '/images/icon_mail.png" alt="" /> ' . gettext('Announce') . "\n</label>\n</p>\n";
		}
		return $before;
	}

	static function execute($custom, $object) {
		global $_zp_current_admin_obj;
		if (isset($_POST['announce_me'])) {
			$content = $object->getContent();
			preg_match_all('|<a href="(.*?)".*>(.*)</a>|', $content, $matches);
			if (!empty($matches[0])) {
				foreach ($matches[0] as $key => $match) {
					if (!empty($match)) {
						$content = str_replace($match, $matches[2][$key] . ':' . $matches[1][$key], $content);
					}
				}
			}

			$content = str_replace("\n", '', $content);
			$content = str_replace("\r", '', $content);
			$content = str_replace("&nbsp;", ' ', $content);
			$content = preg_replace('|<[/]*p[^>]*>?|i', "\r\n", $content);
			$content = preg_replace('|<[/]*ul[^>]*>?|i', "\r\n", $content);
			$content = preg_replace('|<li[^>]*>?|i', " - ", $content);
			$content = preg_replace('|</li>?|i', "\r\n", $content);
			$content = preg_replace('|<br[^>]*/>?|i', "\r\n", $content);
			$content = html_entity_decode($content, ENT_QUOTES, 'ISO-8859-1');
			$content = trim(strip_tags($content), "\r\n");
			$content = str_replace('  ', ' ', $content);
			$result = zp_apply_filter('sendmail', '', array('zenphoto-announce' => 'zenphoto-announce@googlegroups.com'), strip_tags($object->getTitle()), $content, 'no-reply@zenphoto.org', 'The Zenphoto team', array(), NULL);
		}
		return $custom;
	}

}
?>