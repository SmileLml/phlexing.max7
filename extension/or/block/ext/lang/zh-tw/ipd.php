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

$lang->block->availableBlocks['task'] = '調研任務';

$lang->block->summary = new stdclass();
$lang->block->summary->welcome    = '禪道已陪伴您%s： %s今日期待優秀的您來處理！';
$lang->block->summary->yesterday  = '<strong>昨日</strong>';
$lang->block->summary->noWork     = '您暫未處理調研任務，';
$lang->block->summary->finishTask = '完成了<a href="' .  helper::createLink('my', 'contribute', 'mode=task&type=finishedBy') . '" class="text-success">%s</a>個調研任務';
$lang->block->summary->fixBug     = '';

$lang->block->welcome->assignList = array();
$lang->block->welcome->assignList['demand']      = '需求池需求';
$lang->block->welcome->assignList['requirement'] = $lang->URCommon . '數';
$lang->block->welcome->assignList['task']        = '調研任務數';
$lang->block->welcome->assignList['feedback']    = '反饋數';
