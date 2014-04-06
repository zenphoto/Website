<?php
/* fixes Zenphoto database entries
 *
 * @package plugins
 * @subpackage development
 */
$plugin_is_filter = 5|ADMIN_PLUGIN;
$plugin_description = gettext('Fixes UTF8 character coding in the database.');
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

$replacements = array(
		'Ã¼'	=>	'ü',
		'ÂŸ'	=>	'ü',
		'Ã˜'	=>	'Ø',
		'Â¿'	=>	'ø',
		'ÂŽ'	=>	'é',
		'â€”'	=>	'—',
		'â€™'	=>	"'",
		'â€œ'	=>	'“',
		'â€'	=>	'”',
		'Ã«'	=>	'æ'
);

function FixUTF8_recode($string) {
	global $replacements;
	$string = strtr($string, $replacements);
	$string = \ForceUTF8\Encoding::fixUTF8($string);
	$string = str_replace('Â', '', $string);	//	final cleanup
	return $string;
}

function FixUTF8() {

	$tables = array(prefix('albums'),prefix('images'),prefix('news'),prefix('pages'));
	$fields = array('title','desc','content');

	foreach ($tables as $table) {
		$sql = "SELECT * FROM $table";
		$result = query($sql);
		while ($row = db_fetch_assoc($result)) {
			$sql = '';
			foreach ($fields as $field) {
				if (array_key_exists($field, $row)) {
					$data = FixUTF8_recode($row[$field]);
					if ($data != $row[$field]) {
						if ($sql) {
							$sql .= ',';
						} else {
							$sql = 'SET';
						}
						$sql .= " `$field`=".db_quote($data);
					}
				}
			}
			if ($sql) {
				$sql = "UPDATE $table ".$sql.' WHERE `id`='.$row['id'];
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
	$_GET['report'] = 'fixUTF8 has completed';
}

?>