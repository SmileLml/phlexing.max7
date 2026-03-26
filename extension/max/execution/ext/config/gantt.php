<?php
$config->execution->custom->customGanttFields = 'id,branch,assignedTo,progress,begin,realBegan,deadline,realEnd,duration,estimate,consumed,left,delay,delayDays,openedBy,finishedBy';

$config->execution->ganttCustom = new stdclass();
$config->execution->ganttCustom->ganttFields = 'assignedTo,progress,begin,deadline,duration';

$config->execution->relation = new stdclass();

$config->execution->relation->actionList = array();
$config->execution->relation->actionList['edit']['icon']        = 'edit';
$config->execution->relation->actionList['edit']['text']        = $lang->execution->editRelation;
$config->execution->relation->actionList['edit']['hint']        = $lang->execution->editRelation;
$config->execution->relation->actionList['edit']['url']         = array('module' => 'execution', 'method' => 'editRelation', 'params' => 'relationID={id}');
$config->execution->relation->actionList['edit']['data-toggle'] = 'modal';

$config->execution->relation->actionList['delete']['icon']         = 'trash';
$config->execution->relation->actionList['delete']['text']         = $lang->execution->deleteRelation;
$config->execution->relation->actionList['delete']['hint']         = $lang->execution->deleteRelation;
$config->execution->relation->actionList['delete']['url']          = array('module' => 'execution', 'method' => 'deleteRelation', 'params' => 'relationID={id}');
$config->execution->relation->actionList['delete']['class']        = 'ajax-submit';
$config->execution->relation->actionList['delete']['data-confirm'] = array('message' => $this->lang->execution->gantt->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');

$config->execution->relation->dtable = new stdclass();
$config->execution->relation->dtable->fieldList['id']['title']    = $lang->execution->gantt->id;
$config->execution->relation->dtable->fieldList['id']['type']     = 'checkID';
$config->execution->relation->dtable->fieldList['id']['checkbox'] = true;
$config->execution->relation->dtable->fieldList['id']['sortType'] = false;

$config->execution->relation->dtable->fieldList['pretask']['title'] = $lang->execution->gantt->pretask;
$config->execution->relation->dtable->fieldList['pretask']['type']  = 'category';
$config->execution->relation->dtable->fieldList['pretask']['map']   = array();
$config->execution->relation->dtable->fieldList['pretask']['align'] = 'left';
$config->execution->relation->dtable->fieldList['pretask']['show']  = true;

$config->execution->relation->dtable->fieldList['condition']['title'] = $lang->execution->gantt->condition;
$config->execution->relation->dtable->fieldList['condition']['type']  = 'category';
$config->execution->relation->dtable->fieldList['condition']['map']   = $lang->execution->gantt->preTaskStatus;
$config->execution->relation->dtable->fieldList['condition']['width'] = '150';
$config->execution->relation->dtable->fieldList['condition']['flex']  = false;
$config->execution->relation->dtable->fieldList['condition']['show']  = true;

$config->execution->relation->dtable->fieldList['task']['title'] = $lang->execution->gantt->task;
$config->execution->relation->dtable->fieldList['task']['type']  = 'category';
$config->execution->relation->dtable->fieldList['task']['map']   = array();
$config->execution->relation->dtable->fieldList['task']['align'] = 'left';
$config->execution->relation->dtable->fieldList['task']['show']  = true;

$config->execution->relation->dtable->fieldList['action']['title'] = $lang->execution->gantt->condition;
$config->execution->relation->dtable->fieldList['action']['type']  = 'category';
$config->execution->relation->dtable->fieldList['action']['map']   = $lang->execution->gantt->taskActions;
$config->execution->relation->dtable->fieldList['action']['width'] = '150';
$config->execution->relation->dtable->fieldList['action']['flex']  = false;
$config->execution->relation->dtable->fieldList['action']['show']  = true;

$config->execution->relation->dtable->fieldList['type']['title'] = $lang->execution->gantt->type;
$config->execution->relation->dtable->fieldList['type']['type']  = 'category';
$config->execution->relation->dtable->fieldList['type']['map']   = $lang->execution->relation->typeList;
$config->execution->relation->dtable->fieldList['type']['width'] = '150';
$config->execution->relation->dtable->fieldList['type']['flex']  = false;
$config->execution->relation->dtable->fieldList['type']['show']  = true;

$config->execution->relation->dtable->fieldList['actions']['type']  = 'actions';
$config->execution->relation->dtable->fieldList['actions']['width'] = '60px';
$config->execution->relation->dtable->fieldList['actions']['list']  = $config->execution->relation->actionList;
$config->execution->relation->dtable->fieldList['actions']['menu']  = array('edit', 'delete');
