<?php
$lang->block->flowchart            = array();
$lang->block->flowchart['admin']   = array('管理員', '維護公司', '添加用戶', '維護權限');
$lang->block->flowchart['project'] = array('項目負責人', '創建項目', '維護團隊', "維護目標", '創建看板');
$lang->block->flowchart['dev']     = array('執行人員', '創建任務', '認領任務', '執行任務');

$lang->block->undone   = '未完成';
$lang->block->delaying = '即將到期';
$lang->block->delayed  = '已延期';

$lang->block->titleList['scrumlist'] = '看板列表';
$lang->block->titleList['sprint']    = '看板總覽';

$lang->block->myTask = '我的任務';

$lang->block->finishedTasks = '完成的任務數';

$lang->block->story = '目標';

$lang->block->storyCount = '目標數';

$lang->block->default['full']['my'][] = array('title' => '看板列表', 'module' => 'execution', 'code' => 'scrumlist', 'width' => '2', 'height' => '6', 'left' => '0', 'top' => '45', 'params' => array('type' => 'doing', 'orderBy' => 'id_desc', 'count' => '15'));

$lang->block->modules['kanbanproject'] = new stdclass();
$lang->block->modules['kanbanproject']->availableBlocks['scrumoverview']  = "{$lang->projectCommon}概況";
$lang->block->modules['kanbanproject']->availableBlocks['scrumlist']      = $lang->executionCommon . '列表';
$lang->block->modules['kanbanproject']->availableBlocks['sprint']         = $lang->executionCommon . '總覽';
$lang->block->modules['kanbanproject']->availableBlocks['projectdynamic'] = '最新動態';

$lang->block->modules['project'] = new stdclass();
$lang->block->modules['project']->availableBlocks['project'] = "{$lang->projectCommon}列表";

$lang->block->modules['execution'] = new stdclass();
$lang->block->modules['execution']->availableBlocks['statistic'] = $lang->execution->common . '統計';
$lang->block->modules['execution']->availableBlocks['overview']  = $lang->execution->common . '總覽';
$lang->block->modules['execution']->availableBlocks['list']      = $lang->execution->common . '列表';
$lang->block->modules['execution']->availableBlocks['task']      = '任務列表';

unset($lang->block->moduleList['product']);
unset($lang->block->moduleList['qa']);

$lang->block->welcome->assignList = array();
$lang->block->welcome->assignList['task'] = '任務數';

$lang->block->summary->welcome    = '禪道已陪伴您%s： %s今日期待優秀的您來處理！';
$lang->block->summary->yesterday  = '<strong>昨日</strong>';
$lang->block->summary->noWork     = '您暫未處理任務，';
$lang->block->summary->finishTask = '完成了<a href="' .  helper::createLink('my', 'contribute', 'mode=task&type=finishedBy') . '" class="text-success">%s</a>個任務';
$lang->block->summary->fixBug     = '';
