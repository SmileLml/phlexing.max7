<?php
global $lang;
unset($lang->execution->featureBar['all']['undone']);
unset($lang->execution->featureBar['all']['wait']);
unset($lang->execution->featureBar['all']['suspended']);

$lang->execution->createKanban    = '創建看板';
$lang->execution->noExecution     = "暫時沒有看板。";
$lang->execution->importTask      = '轉入任務';
$lang->execution->batchCreateTask = '批量創建任務';
$lang->execution->linkStory       = "關聯{$lang->SRCommon}";
$lang->execution->closedExecution = '已關閉的看板';

$lang->execution->kanbanGroup['default']    = '預設方式';
$lang->execution->kanbanGroup['story']      = '目標';
$lang->execution->kanbanGroup['module']     = '所屬目錄';
$lang->execution->kanbanGroup['pri']        = '優先順序';
$lang->execution->kanbanGroup['assignedTo'] = '指派人';

$lang->execution->icons['kanban']    = 'kanban';
$lang->execution->icons['task']      = 'list';
$lang->execution->icons['calendar']  = 'calendar';
$lang->execution->icons['gantt']     = 'lane';
$lang->execution->icons['tree']      = 'treemap';
$lang->execution->icons['grouptask'] = 'sitemap';

$lang->execution->aclList['private'] = "私有（團隊成員和{$lang->projectCommon}負責人可訪問）";

$lang->execution->common = "{$lang->projectCommon}看板";
