<?php

//-----------------------------------------------------------------------------

namespace mycms;
require_once 'DB.php';
require_once('admin.class.php');

//-----------------------------------------------------------------------------

global $g;
if(!isset($_GET['content'])) $_GET['content'] = 'people';
$g['template'] = 'people_admin';
$ct = strtolower($_GET['content']);
$err = false;

//-----------------------------------------------------------------------------

// Set main menu options
$menu = [
	['name' => 'People',       'url' => 'admin/people',       ],
	['name' => 'Research',     'url' => 'admin/research',     ],
	['name' => 'Publications', 'url' => 'admin/publication', ]
];
$g['smarty']->assign('menu', $menu);

//-----------------------------------------------------------------------------

function checkparams ($params) {
	if (array_key_exists('_isset', $params)) {
		foreach ($params['_isset'] as $v) {
			if (!isset($_GET[$v]))
				return false;
		}
		unset($params['_isset']);
	}

	foreach ($params as $k => $v) {
		if (!isset($_GET[$k]) || $_GET[$k] !== $v)
			return false;;
	}

	return true;
}

//-----------------------------------------------------------------------------

function validate ($type, &$v) {
	global $g;
	$val      = trim($v);
	$format = '';

	if (false !== strpos($type, ':')) {
		$format = substr($type, strpos($type, ':') + 1);
		$type   = substr($type, 0, strpos($type, ':'));
	}

	switch ($type) {
	  case 'int': {
	  	if (is_int($val))
	  		$val = intval($val);
		$format = preg_split("/,/", $format);
		if ((count($format) >= 1 && $val < $format[0]) ||
			(count($format) >= 2 && $val > $format[1]))
			$val = false;
		break;
	} case 'alphabetic': {
		break;
	} case 'alphabetic-utf8': {
		break;
	} case 'string': {
		break;
	} case 'email': {
		break;
	} case 'enum': {
		$format = preg_split("/,/", $format);
		if (!in_array(strtolower($val), $format))
			$val = false;
		break;
	} case 'date': {
		$val = date($format, strtotime($val));
		break;
	} default:
		$val = false;
		break;
	} //switch

	if (false === $val) {
			return false;
	} else {
		$v = $val;
		return true;
	}
}

//-----------------------------------------------------------------------------

function make_thumb($src, $dest, $desired_width) {

	/* read the source image */
	$source_image = imagecreatefromjpeg($src);
	$width = imagesx($source_image);
	$height = imagesy($source_image);

	/* find the "desired height" of this thumbnail, relative to the desired width  */
	$desired_height = floor($height * ($desired_width / $width));

	/* create a new, "virtual" image */
	$virtual_image = imagecreatetruecolor($desired_width, $desired_height);

	/* copy source image at a resized size */
	imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

	/* create the physical thumbnail image to its destination */
	imagejpeg($virtual_image, $dest);
}

//-----------------------------------------------------------------------------

function save_file ($ct, $ct_id, $file, $type) {
	if (empty($file['name']))
		return;

	global $g;
	$db = $g['content'][$type];
	$ext = end(explode('.', $file['name']));

	if (in_array($file["type"], $db->mime) &&
		in_array($ext, $db->ext) &&
		$file["size"] < $db->max_size) {

		if ($file["error"] > 0) {
			$g['error']->push($file["error"], 'error');
		} else {
			$type_filename = $type . '_filename';
			$db->$type_filename = "temp.$ext";
			$id = $db->insert();

			if (!file_exists("files/$ct"))
				mkdir("files/$ct");

			if (!file_exists("files/$ct/$type"))
				mkdir("files/$ct/$type");

			move_uploaded_file($file["tmp_name"], "files/$ct/$type/$id.$ext");

			$db->get($id);
			$db->image_filename = "$id.$ext";
			$db->update();
			$db_name = $db->database();
			$db->query("INSERT INTO `$db_name`.`{$db_name}_{$ct}_$type` (`{$type}_id` , `{$ct}_id` ) VALUES ('$id',  '$ct_id')");

			if ($type == 'image') {
				if (!file_exists("files/$ct/$type/thumb"))
					mkdir("files/$ct/$type/thumb");
				make_thumb("files/$ct/$type/$id.$ext", "files/$ct/$type/thumb/$id.$ext", 40);
			}
		}
	} else {
		$g['error']->push("{$file['name']} is not of supported types or exceeds max allowed size", 'error');
	}
}

//-----------------------------------------------------------------------------

function get_refrences($ct, $id) {
	global $g;
	$content = $g['content'][$ct];
	$dbn = $content->database();
	$res = [];
	foreach ($content->references as $ref) {
		$ref_db = $g['content'][$ref];
		$ref_db->query(
			"SELECT * FROM {$dbn}_{$ref} as $ref
			LEFT JOIN {$dbn}_{$ct}_{$ref} as {$ct}_$ref
			ON ($ref.{$ref}_id = {$ct}_$ref.{$ref}_id)
			WHERE {$ct}_{$ref}.{$ct}_id = $id");

		$ref_objs = [];
		while ($ref_db->fetch())
			$ref_objs[] = clone($ref_db);
		$res[$ref] = ['rows' => $ref_objs, 'count' => count($ref_objs)];
	}

	return $res;
}

//-----------------------------------------------------------------------------

$content = 'unknown';
if (array_key_exists($ct, $g['content'])) {
	$content = $g['content'][$ct];
}

//-----------------------------------------------------------------------------

if ('unknown' == $content) {
	$err = true;
	$g['error']->push("uknown content type: $ct", 'error');
}

//-----------------------------------------------------------------------------

else if (checkparams([
	'operation' => 'create'])) {
	$content->create();
}

//-----------------------------------------------------------------------------

else if (checkparams([
	'operation' => 'remove',
	'_isset'    => ['id']])) {
	$r = $content->get($_GET['id']);
	if ($r == 1) {
		$content->delete();
		$g['error']->push("1 $ct removed successfully");
	} else if ($r == 0) {
		$g['error']->push("No $ct found with id " . $_GET['id'], 'error');
	}
}

//-----------------------------------------------------------------------------

else if (checkparams([
	'operation' => 'edit',
	'_isset'    => ['id']])) {
	$r = $content->get($_GET['id']);
	if ($r == 1) {

		foreach ($_POST as $k => $v) {
			if (property_exists("\\mycms\\$ct", $k)) {
				if (validate($content->field_type[$k], $v))
					$content->$k = $v;
				else
					$g['error']->push("worng format($k ". $content->field_type[$k] . ") at $v");
			}
		}

		//$r = $content->validate();
		//if (true !== $r) {
		//	ob_start();
		//	var_dump($r);
		//	$r = ob_get_clean();
		//	$g['error']->push("An error occured while trying to update database $r");
		//} else
		{
			$res = $content->update();
			if (false === $res)
				$g['error']->push("An error occured while trying to update a $ct.", 'error');
			else
				$g['error']->push("$ct updated successfully.");
		}

		if (!empty($_FILES['image']['name']))
			save_file($ct, $_GET['id'], $_FILES['image'], 'image');

		$g['smarty']->assign($ct, $content);
		$g['smarty']->assign("refrences", get_refrences($ct, $_GET['id']));
	} else if ($r == 0) {
		$g['error']->push("No $ct found with id " . $_GET['id'], 'error');
	}
}

//-----------------------------------------------------------------------------

else if (checkparams([
	'operation' => 'view',
	'_isset'    => ['id']])) {
	$id = $_GET['id'];
	$r = $content->get($id);
	if ($r == 1) {
		$g['smarty']->assign($ct, $content);
		$g['smarty']->assign("refrences", get_refrences($ct, $id));
	} else if ($r == 0) {
		$g['error']->push("No $ct found with id " . $_GET['id'], 'error');
	}
}

//-----------------------------------------------------------------------------

if ($err) {
	$g['smarty']->assign('page', 'Error');
	$g['template'] = 'notfound';
}

//-----------------------------------------------------------------------------

else {
	$objs = $content->getall();
	$g['smarty']->assign('selectedmenu', $ct);
	$g['smarty']->assign($ct . '_list', $objs);
	$g['template'] = $ct . '_admin';
}

//-----------------------------------------------------------------------------
