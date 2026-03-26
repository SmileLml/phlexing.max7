<?php
$lang->block->default['waterfallproject'][] = array('title' => '估算',                                             'module' => 'waterfallproject', 'code' => 'waterfallestimate',      'width' => '1');
$lang->block->default['waterfallproject'][] = array('title' => $lang->projectCommon . '周报',                      'module' => 'waterfallproject', 'code' => 'waterfallreport',        'width' => '2');
$lang->block->default['waterfallproject'][] = array('title' => '到目前为止' . $lang->projectCommon . '进展趋势图', 'module' => 'waterfallproject', 'code' => 'waterfallprogress',      'width' => '1');
$lang->block->default['waterfallproject'][] = array('title' => $lang->projectCommon . '问题',                      'module' => 'waterfallproject', 'code' => 'waterfallissue',         'width' => '2', 'params' => array('type' => 'all', 'count' => '15', 'orderBy' => 'id_desc'));
$lang->block->default['waterfallproject'][] = array('title' => $lang->projectCommon . '风险',                      'module' => 'waterfallproject', 'code' => 'waterfallrisk',          'width' => '2', 'params' => array('type' => 'all', 'count' => '15', 'orderBy' => 'id_desc'));

$lang->block->default['scrumproject'][] = array('title' => $lang->projectCommon . '问题', 'module' => 'scrumproject', 'code' => 'scrumissue', 'width' => '2', 'params' => array('type' => 'all', 'count' => '15', 'orderBy' => 'id_desc'));
$lang->block->default['scrumproject'][] = array('title' => $lang->projectCommon . '风险', 'module' => 'scrumproject', 'code' => 'scrumrisk',  'width' => '2', 'params' => array('type' => 'all', 'count' => '15', 'orderBy' => 'id_desc'));

$lang->block->modules['waterfallproject']->availableBlocks['waterfallestimate']      = "估算";
$lang->block->modules['waterfallproject']->availableBlocks['waterfallreport']        = "{$lang->projectCommon}周报";
$lang->block->modules['waterfallproject']->availableBlocks['waterfallprogress']      = "到目前为止{$lang->projectCommon}进展趋势图";
$lang->block->modules['waterfallproject']->availableBlocks['waterfallissue']         = "{$lang->projectCommon}问题";
$lang->block->modules['waterfallproject']->availableBlocks['waterfallrisk']          = "{$lang->projectCommon}风险";

$lang->block->modules['scrumproject']->availableBlocks['scrumissue'] = "{$lang->projectCommon}问题";
$lang->block->modules['scrumproject']->availableBlocks['scrumrisk']  = "{$lang->projectCommon}风险";

$lang->block->welcome->assignList['feedback']  = '反馈数';
$lang->block->welcome->assignList['issue']     = '问题数';
$lang->block->welcome->assignList['risk']      = '风险数';
$lang->block->welcome->assignList['auditplan'] = 'QA数';
$lang->block->welcome->assignList['ticket']    = '工单数';

$lang->block->welcome->reviewList['feedback'] = '反馈数';
$lang->block->welcome->reviewList['case']     = '用例数';
$lang->block->welcome->reviewList['line']     = '基线数';
