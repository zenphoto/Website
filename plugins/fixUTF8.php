<?php
/* fixes Zenphoto database entries
 *
 * @package plugins
 * @subpackage development
 */
$plugin_is_filter = 5|ADMIN_PLUGIN;
$plugin_description = gettext('Generates Doc file for filters.');
$plugin_author = "Stephen Billard (sbillard)";
$plugin_version = '1.4.3';

zp_register_filter('admin_utilities_buttons', 'FixUTF8_button');

function FixUTF8_button($buttons) {

	$buttons[] = array(
								'category'=>gettext('Development'),
								'enable'=>true,
								'button_text'=>gettext('FixUTF8'),
								'formname'=>'FixUTF8_button',
								'action'=>'?FixUTF8=gen',
								'icon'=>'images/pencil.png',
								'title'=>gettext('fix the database'),
								'alt'=>'',
								'hidden'=> '<input type="hidden" name="FixUTF8" value="gen" />',
								'rights'=> ADMIN_RIGHTS,
								'XSRFTag' => 'FixUTF8'
								);
	return $buttons;
}

require_once(SERVERPATH.'/'.USER_PLUGIN_FOLDER.'/fixUTF8/Encoding.php');

function recode($string) {
	$replacements = array(
												'Ã¼'	=>	'ü',
												'ÂŸ'	=>	'ü',
												'Ã˜'	=>	'Ø',
												'Â¿'	=>	'ø',
												'ÂŽ'	=>	'é',
												'â€”'	=>	'—',
												'â€™'	=>	"'",
												'â€œ'	=>	'“',
												'â€'	=>	'”',			//	probably was a closing quote
												'Ã«'	=>	'æ',
												'Â'		=>	''				//	??? Probably empty would work.
												);

//	$string = Encoding::fixUTF8($string);
	$string = strtr($string, $replacements);
	return $string;
}

function FixUTF8() {
	$actionable = array(prefix('albums'),prefix('images'),prefix('news'),prefix('pages'));
	foreach ($actionable as $table) {
		$sql = "SELECT * FROM $table";
		$result = query($sql);
		while ($row = db_fetch_assoc($result)) {
			$sql = '';
			$title = recode($row['title']);
			if ($title != $row['title']) {
				$sql .= " SET `title`=".db_quote($title);
			}
			if (array_key_exists('desc', $row)) {
				$desc =recode($row['desc']);
				if ($desc != $row['desc']) {
					if ($sql) {
						$sql .= ',';
					} else {
						$sql = ' SET';
					}
					$sql .= " `desc`=".db_quote($desc);
				}
			}
			if (array_key_exists('content', $row)) {
				$content = recode($row['content']);
				if ($content != $row['content']) {
					if ($sql) {
						$sql .= ',';
					} else {
						$sql = ' SET';
					}
					$sql .= " `content`=".db_quote($content);
				}
			}
			if ($sql) {
				$sql = "UPDATE $table".$sql.' WHERE `id`='.$row['id'];

debugLog($sql);

				query($sql);
			}
		}
		db_free_result($result);
	}
}

if (isset($_REQUEST['FixUTF8'])) {
	XSRFdefender('FixUTF8');
	FixUTF8();
}

?>