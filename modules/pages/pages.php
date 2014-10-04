<?php

//-----------------------------------------------------------------------------

namespace mycms;
require_once('pages.class.php');

//-----------------------------------------------------------------------------

global $g;
if(!isset($_GET['action']) || empty($_GET['action'])) $_GET['action'] = 'home';
$g['template'] = 'home';

//-----------------------------------------------------------------------------

// Set main menu options
$menu = array(
	array('name' => 'Home',          'url' => '',              ),
	array('name' => 'Research',    'url' => 'research',    ),
	array('name' => 'People',       'url' => 'people',       ),
	array('name' => 'Opportunities', 'url' => 'opportunities', ),
	array('name' => 'Publications',  'url' => 'publications',  ),
	array('name' => 'Resources',     'url' => 'resources',     ),
);


$g['smarty']->assign('menu', $menu);

$auth_menu_state = $g['user']['is_authenticated'] ? 'logout' : 'login';

// Set secondary menu options
$menu = array(
	array('name' => 'trac',           'url' => $g['trac_url'],),
	array('name' => $auth_menu_state, 'url' =>
		$auth_menu_state, 'user_id' => $g['user']['id']),
);

$g['smarty']->assign('menu_2', $menu);

//-----------------------------------------------------------------------------

switch($_GET['action']){

//-----------------------------------------------------------------------------

//TODO: after login it should continue on the current page not the homepage
case 'login':
	$g['auth']->authenticate();
	goto HOME;
case 'logout':
	$g['auth']->logout(system::genlink(''));
	goto HOME;

//-----------------------------------------------------------------------------

HOME:
case 'home': {
	$faculty = $g['content']['people']->view('teaser'
		, 'people.people_group = "faculty"');
	if (!$faculty['error'] && $faculty['count'] > 0)
		$g['smarty']->assign('faculty', $faculty);

	$research = $g['content']['research']->view('largeicon'
		, 'research.research_status = "active"'
		, 'research.research_priority DESC', '0,10');
	if (!$research['error'] && $research['count'] > 0)
		$g['smarty']->assign('research', $research);

	$publication = $g['content']['publication']->view('largeicon', ''
		, 'publication.publication_year DESC', '0,10');
	if (!$publication['error'] && $publication['count'] > 0)
		$g['smarty']->assign('publication', $publication);

	$g['smarty']->assign('selectedmenu', 'Home');
	$g['template'] = 'home';
} break;

//-----------------------------------------------------------------------------

case 'people': {
	if (isset($_GET['id']) && isset($_GET['details'])) {

		$id = $_GET['id'];
		$details = strtolower($_GET['details']);
		$g['smarty']->assign('selectedmenu', 'People');
		$refs = $g['content']['people']->displays['default'];

		if (array_key_exists($details, $refs)) {
			$ref_limits = $refs;

			// setting zero for the limit of all other referenced types
			// except for the $details
			foreach ($ref_limits as $r => &$v) {
				$v = 0;
			}
			unset($ref_limits[$details]);


			$ref_order = array();
			// todo: kind of hard coding
			if ('publication' == $details)
				$ref_order[$details] = $details . '_year DESC';

			$ppl = $g['content']['people']->view('default',
				"people.people_id = $id", '', 1
				, true, $ref_limits, $ref_order);

			if (!$ppl['error'] && $ppl['count'] > 0) {
				$p = $ppl['rows'][0];
				$g['smarty']->assign('page_l'
					, $g['content']['people']->get_title($p));
				$g['smarty']->assign('people', $p);
			}

			$g['template'] = 'people/all_' . $details;
		} else {
			// error
		}
	} else if (isset($_GET['id'])) {
		$id = $_GET['id'];
		$g['smarty']->assign('selectedmenu', 'People');
		$ppl = $g['content']['people']->view('default',
			"people.people_id = $id",
			'',
			1,
			true,
			array('research' => 5, 'publication' => 5));

		if (!$ppl['error'] && $ppl['count'] > 0) {
			$p = $ppl['rows'][0];
			$g['smarty']->assign('page_l'
				, $g['content']['people']->get_title($p));
			$g['smarty']->assign('people', $p);
			$g['template'] = 'people/display_default';
		}
	} else {
		$g['smarty']->assign('page', 'People');
		$g['smarty']->assign('selectedmenu', 'People');
		$ppl = $g['content']['people']->view('teaser', ''
			, 'people.people_group, people.people_firstname ASC', '');

		if (!$ppl['error'] && $ppl['count'] > 0)
			$g['smarty']->assign('people', $ppl);

		$g['template'] = 'people';
	}
} break;

//-----------------------------------------------------------------------------

case 'pages':
case 'courses':
case 'research':
case 'publications':
case 'opportunities':
case 'resources':
{
	$page = isset($_GET['id']) ? $_GET['id'] : 1;
	$ct = '';
	$priority = '';
	$display = 'teaser';

	switch ($_GET['action']) {
	case 'courses':
		$ct = 'course';
		$priority = "$ct.{$ct}_priority DESC";
		break;
	case 'research':
		$ct = 'research';
		$priority = "$ct.{$ct}_priority DESC";
		$display = 'largeicon';
		break;
	case 'publications':
		$ct = 'publication';
		$priority = "$ct.{$ct}_year DESC";
		$display = 'largeicon';
		break;
	case 'opportunities':
		$ct = 'opportunity';
		$priority = "$ct.{$ct}_priority DESC";
		break;
	case 'resources':
		$ct = 'resource';
		$priority = "$ct.{$ct}_priority DESC";
		break;
	}

	$menu_item = '';
	foreach ($menu as $v)
		if ($v['url'] == $ct) {
			$menu_item = $v['name'];
			break;
		}

	$g['smarty']->assign('page', $_GET['action']);
	if ('' != $menu_item)
		$g['smarty']->assign('selectedmenu', $menu_item);

	$res = $g['content'][$ct]->view($display, '', $priority, $page);

	if (!$res['error'] && $res['count'] > 0) {
		$g['smarty']->assign($_GET['action'], $res);
	}

	$g['template'] = $_GET['action'];
} break;

//-----------------------------------------------------------------------------

case 'course':
case 'page':
case 'research':
case 'resource':
case 'opportunity':
case 'publication':
{
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		$ct = $_GET['action'];

		$menu_item = '';
		foreach ($menu as $v)
			if ($v['url'] == $ct) {
				$menu_item = $v['name'];
				break;
			}

		if ('' != $menu_item)
			$g['smarty']->assign('selectedmenu', $menu_item);

		$r = $g['content'][$ct]->view('default', "$ct.{$ct}_id = $id");

		$g['smarty']->assign('page', $ct);
		if (!$r['error'] && $r['count'] > 0)
			$p = $r['rows'][0];
			$g['smarty']->assign('page_l', $g['content'][$ct]->get_title($p));
			$g['smarty']->assign($ct, $p);

		$g['template'] = "{$ct}/display_default";
	}
} break;

//-----------------------------------------------------------------------------

default: {
	$g['smarty']->assign('page', 'Error');
	$g['template'] = 'notfound';
}

//-----------------------------------------------------------------------------

}

//-----------------------------------------------------------------------------
