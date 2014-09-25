<?php

//-----------------------------------------------------------------------------

namespace mycms;
require_once 'DB.php';
require_once('ajax.class.php');

//-----------------------------------------------------------------------------

define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

if (!IS_AJAX)
	return;

//-----------------------------------------------------------------------------

global $g;
if(!isset($_GET['operation'])) $_GET['operation'] = 'get';
$op  = $_GET['operation'];

$params = false;
if(isset($_POST['params']))
	$params = json_decode($_POST['params']);

$res = array(
	'status' => 'success', // success, error
	'message' => '',
	'query' => ''
);

//-----------------------------------------------------------------------------

if ('get' == $op && $params) {
	$ct = $params->content;
	$db = $g['content'][$ct];
	$w = '';

	if (isset($params->name)) {
		$delim = ' ';
		$fields = array();

		if (strpos($db->title_format, $delim) !== FALSE)
			$fields = explode($delim, $db->title_format);
		else
			$fields[] = $db->title_format;

		foreach ($fields as $f) {
			if (!empty($w))
				$w .= ' OR ';
			$w .= " $f LIKE '%" . $db->escape($params->name) . "%' ";
		}

	} else if (isset($params->id)) {
		$w .= " {$ct}_id = {$params->id} ";
	}


	$r = $db->view($display = 'teaser', $w, '', $limit = '0,5', $get_referenced_data = true);
	$res['count'] = $r['count'];
	$res['html'] = '';

	if (isset($params->list) && $params->list) {
		$g['smarty']->assign($ct, $r);
		$res['html'] = $g['smarty']->fetch("templates/{$ct}/{$params->display}_list.tpl");
	} else if ($res['count'] > 0) {
		$g['smarty']->assign($ct, $r['rows'][0]);
		$res['html'] = $g['smarty']->fetch("templates/{$ct}/display_{$params->display}.tpl");
	}

}

//-----------------------------------------------------------------------------

else if ('add-reference'  == $op && $params) {
	$db = $g['db'];
	$referer_order = 0;
	$referred_order = 0;

	$q = <<<SQL
		SELECT MAX({$params->referred_type}_order) as max_{$params->referred_type}_order
		FROM !!!{$params->referer_type}_{$params->referred_type}
		WHERE {$params->referer_type}_id ={$params->referer_id}
SQL;

	$r = $db->query($q);

	if ($r['count'] > 0) {
		$referred_order = $r['rows'][0]["max_{$params->referred_type}_order"];
		$referred_order = is_null($referred_order) ? 0 : $referred_order + 1;
	}

	$q = <<<SQL
		SELECT MAX({$params->referer_type}_order) as max_{$params->referer_type}_order
		FROM !!!{$params->referer_type}_{$params->referred_type}
		WHERE {$params->referred_type}_id ={$params->referred_id}
SQL;

	$r = $db->query($q);

	if ($r['count'] > 0) {
		$referer_order = $r['rows'][0]["max_{$params->referer_type}_order"];
		$referer_order = is_null($referer_order) ? 0 : $referer_order + 1;
	}

	$q = <<<SQL
		INSERT INTO !!!{$params->referer_type}_{$params->referred_type}
		({$params->referer_type}_id, {$params->referred_type}_id
		 , {$params->referer_type}_order, {$params->referred_type}_order)
		VALUES (
			{$params->referer_id},
			{$params->referred_id},
			{$referer_order},
			{$referred_order})
SQL;


	$r = $db->query($q);

	if ($r['error']) {
		// made no change to references
		$res['status'] = 'error';
		$res['message'] = $r['message'];
	} else
		$res['status'] = 'success';
}

//-----------------------------------------------------------------------------

else if ('remove-reference'  == $op && $params) {
	$db = $g['db'];
	$q = "DELETE FROM !!!{$params->referer_type}_{$params->referred_type}
		WHERE {$params->referer_type}_id = {$params->referer_id} AND
		{$params->referred_type}_id = {$params->referred_id}";
	$r = $db->query($q);
	if ($r['error']) {
		// made no change to references
		$res['status'] = 'error';
		$res['message'] = $r['message'];
	} else
		$res['status'] = 'success';
}

//-----------------------------------------------------------------------------

else if ('order-reference'  == $op && $params) {
	$db = $g['db'];
	foreach ($params->referred_orders as $referred_id => $referred_order) {
		$q = "UPDATE !!!{$params->referer_type}_{$params->referred_type}
			SET {$params->referred_type}_order = $referred_order
			WHERE {$params->referred_type}_id = $referred_id
			AND {$params->referer_type}_id = {$params->referer_id};";
		$r = $db->query($q);
		if ($r['error'])
			break;
	}

	if ($r['error']) {
		$res['status'] = 'error';
		$res['message'] = $r['message'];
	} else {
		$res['status'] = 'success';
		$res['message'] = $q;
	}
}

//-----------------------------------------------------------------------------

echo json_encode($res);
exit();