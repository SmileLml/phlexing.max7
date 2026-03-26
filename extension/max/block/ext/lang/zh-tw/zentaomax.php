<?php
$lang->block->default['waterfallproject'][] = array('title' => '估算',                                             'module' => 'waterfallproject', 'code' => 'waterfallestimate',      'width' => '1');
$lang->block->default['waterfallproject'][] = array('title' => $lang->projectCommon . '周報',                      'module' => 'waterfallproject', 'code' => 'waterfallreport',        'width' => '2');
$lang->block->default['waterfallproject'][] = array('title' => '到目前為止' . $lang->projectCommon . '進展趨勢圖', 'module' => 'waterfallproject', 'code' => 'waterfallprogress',      'width' => '1');
$lang->block->default['waterfallproject'][] = array('title' => $lang->projectCommon . '問題',                      'module' => 'waterfallproject', 'code' => 'waterfallissue',         'width' => '2', 'params' => array('type' => 'all', 'count' => '15', 'orderBy' => 'id_desc'));
$lang->block->default['waterfallproject'][] = array('title' => $lang->projectCommon . '風險',                      'module' => 'waterfallproject', 'code' => 'waterfallrisk',          'width' => '2', 'params' => array('type' => 'all', 'count' => '15', 'orderBy' => 'id_desc'));

$lang->block->default['scrumproject'][] = array('title' => $lang->projectCommon . '問題', 'module' => 'scrumproject', 'code' => 'scrumissue', 'width' => '2', 'params' => array('type' => 'all', 'count' => '15', 'orderBy' => 'id_desc'));
$lang->block->default['scrumproject'][] = array('title' => $lang->projectCommon . '風險', 'module' => 'scrumproject', 'code' => 'scrumrisk',  'width' => '2', 'params' => array('type' => 'all', 'count' => '15', 'orderBy' => 'id_desc'));

$lang->block->modules['waterfallproject']->availableBlocks['waterfallestimate']      = "估算";
$lang->block->modules['waterfallproject']->availableBlocks['waterfallreport']        = "{$lang->projectCommon}周報";
$lang->block->modules['waterfallproject']->availableBlocks['waterfallprogress']      = "到目前為止{$lang->projectCommon}進展趨勢圖";
$lang->block->modules['waterfallproject']->availableBlocks['waterfallissue']         = "{$lang->projectCommon}問題";
$lang->block->modules['waterfallproject']->availableBlocks['waterfallrisk']          = "{$lang->projectCommon}風險";

$lang->block->modules['scrumproject']->availableBlocks['scrumissue'] = "{$lang->projectCommon}問題";
$lang->block->modules['scrumproject']->availableBlocks['scrumrisk']  = "{$lang->projectCommon}風險";

$lang->block->welcome->assignList['feedback']  = '反饋數';
$lang->block->welcome->assignList['issue']     = '問題數';
$lang->block->welcome->assignList['risk']      = '風險數';
$lang->block->welcome->assignList['auditplan'] = 'QA數';
$lang->block->welcome->assignList['ticket']    = '工單數';

$lang->block->welcome->reviewList['feedback'] = '反饋數';
$lang->block->welcome->reviewList['case']     = '用例數';
$lang->block->welcome->reviewList['line']     = '基線數';
