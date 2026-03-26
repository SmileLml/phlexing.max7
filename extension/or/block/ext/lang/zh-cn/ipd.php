<?php
unset($lang->block->moduleList['project']);
unset($lang->block->moduleList['execution']);
unset($lang->block->moduleList['qa']);

$myBlock = array('welcome_welcome', 'assigntome_assigntome', 'guide_guide', 'product_overview');
foreach($lang->block->default['full']['my'] as $index => $block)
{
    if(!in_array("{$block['module']}_{$block['code']}", $myBlock)) unset($lang->block->default['full']['my'][$index]);
}

foreach($lang->block->default['doc'] as $index => $block)
{
    if(in_array("{$block['module']}_{$block['code']}", array('doc_projectdoc'))) unset($lang->block->default['doc'][$index]);
}
unset($lang->block->modules['doc']->availableBlocks['projectdoc']);

unset($lang->block->guideTabs['systemMode']);
unset($lang->block->guideTabs['preference']);

$lang->block->availableBlocks['task'] = '调研任务';

$lang->block->summary = new stdclass();
$lang->block->summary->welcome    = '禅道已陪伴您%s： %s今日期待优秀的您来处理！';
$lang->block->summary->yesterday  = '<strong>昨日</strong>';
$lang->block->summary->noWork     = '您暂未处理调研任务，';
$lang->block->summary->finishTask = '完成了<a href="' .  helper::createLink('my', 'contribute', 'mode=task&type=finishedBy') . '" class="text-success">%s</a>个调研任务';
$lang->block->summary->fixBug     = '';

$lang->block->welcome->assignList = array();
$lang->block->welcome->assignList['demand']      = '需求池需求';
$lang->block->welcome->assignList['requirement'] = $lang->URCommon . '数';
$lang->block->welcome->assignList['task']        = '调研任务数';
$lang->block->welcome->assignList['feedback']    = '反馈数';
