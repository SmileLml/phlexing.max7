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

$lang->block->availableBlocks['task'] = 'Research Task';

$lang->block->summary = new stdclass();
$lang->block->summary->welcome    = 'Zentao has been with you for %s: ';
$lang->block->summary->yesterday  = '<strong>Yesterday</strong>';
$lang->block->summary->noWork     = 'You have not yet processed research task,';
$lang->block->summary->finishTask = 'finished <a href="' . helper::createLink('my', 'contribute', 'mode=task&type=finishedBy') . '" class="text-success">%s</a> research task';
$lang->block->summary->fixBug     = '';

$lang->block->welcome->assignList = array();
$lang->block->welcome->assignList['demand']      = 'Demand';
$lang->block->welcome->assignList['requirement'] = $lang->URCommon;
$lang->block->welcome->assignList['task']        = 'Research Task';
$lang->block->welcome->assignList['feedback']    = 'Feedback';
