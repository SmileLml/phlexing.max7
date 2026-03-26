<?php
$config->pivot->reuseDtableFields = array('name', 'title', 'type', 'link', 'map', 'statusMap', 'sortType');
$config->pivot->userTypeCols      = array('user', 'assign', 'avatarBtn');
$config->pivot->nameTypeCols      = array('name', 'title');
$config->pivot->stepOrder         = array('query', 'design', 'filter', 'drill', 'publish');

$config->pivot->designActions = array();
$config->pivot->designActions['query'] = array('design', 'query', 'queryFilter');
$config->pivot->designActions['design'] = array('table', 'settings');
$config->pivot->designActions['drill'] = array('table', 'drilling');
$config->pivot->designActions['filter'] = array('table', 'filters');
$config->pivot->designActions['publish'] = array('table', 'publish');

$config->pivot->actionLoadTarget = array();
$config->pivot->actionLoadTarget['queryFilter']     = '#queryFilterPanel';
$config->pivot->actionLoadTarget['addQueryFilter']  = '#queryFilterPanel';
$config->pivot->actionLoadTarget['settings']        = '.config-content';
$config->pivot->actionLoadTarget['addGroup']        = '#groupSetting';
$config->pivot->actionLoadTarget['deleteGroup']     = '#groupSetting';
$config->pivot->actionLoadTarget['addColumn']       = '#columnContainer';
$config->pivot->actionLoadTarget['deleteColumn']    = '#columnContainer';
$config->pivot->actionLoadTarget['changeOrigin']    = '#columnIndex%s';
$config->pivot->actionLoadTarget['changeSlice']     = '#columnIndex%s';
$config->pivot->actionLoadTarget['changeShowMode']  = '#columnIndex%s';
$config->pivot->actionLoadTarget['filters']         = '#filterIndex%s';
$config->pivot->actionLoadTarget['addFilter']       = '.config-content';
$config->pivot->actionLoadTarget['removeFilter']    = '.config-content';
$config->pivot->actionLoadTarget['summaryColumn']   = '#summary-column';

$config->pivot->actionLoadTarget['changeSqlBuilderStep']  = '#builderPanel';
$config->pivot->actionLoadTarget['changeBuilderTable']    = '#buildertable';
$config->pivot->actionLoadTarget['changeBuilderFunc']     = '#builderfunc';
$config->pivot->actionLoadTarget['changeBuilderWhere']    = '#builderwhere';
$config->pivot->actionLoadTarget['changeBuilderQuery']    = '#builderquery,#queryFilterContent';
$config->pivot->actionLoadTarget['changeBuilderGroup']    = '#buildergroup %s';
$config->pivot->actionLoadTarget['addJoinTable']          = '#buildertable';
$config->pivot->actionLoadTarget['addFunc']               = '#builderfunc';
$config->pivot->actionLoadTarget['addWhereGroup']         = '#builderwhere';
$config->pivot->actionLoadTarget['addWhereItem']          = '#builderwhere';
$config->pivot->actionLoadTarget['addBuilderQueryFilter'] = '#builderquery,#queryFilterContent';
$config->pivot->actionLoadTarget['setGroup']              = '#buildergroup';

$config->pivot->actionLoadTarget['addCondition']    = '#drillModal%s #queryConditionContent%s';
$config->pivot->actionLoadTarget['deleteCondition'] = '#drillModal%s #queryConditionContent%s';
$config->pivot->actionLoadTarget['changeObject']    = '#drillModal%s #queryConditionContent%s';
$config->pivot->actionLoadTarget['addDrill']        = '.config-content';
$config->pivot->actionLoadTarget['editDrill']       = '.config-content';
$config->pivot->actionLoadTarget['previewResult']   = '#drillModal%s #queryConditionContent%s';

$config->pivot->actionLoadTarget['saveInfo'] = '.config-content';

global $lang, $app;
$config->pivot->dtable->actionList = array();
$config->pivot->dtable->actionList['edit']['icon'] = 'edit';
$config->pivot->dtable->actionList['edit']['text'] = $lang->edit;
$config->pivot->dtable->actionList['edit']['hint'] = $lang->edit;
$config->pivot->dtable->actionList['edit']['data-toggle'] = 'modal';
$config->pivot->dtable->actionList['edit']['url']  = helper::createLink('pivot', 'edit', 'pivotID={id}');

$config->pivot->dtable->actionList['design']['icon'] = 'design';
$config->pivot->dtable->actionList['design']['text'] = $lang->pivot->design;
$config->pivot->dtable->actionList['design']['hint'] = $lang->pivot->design;
$config->pivot->dtable->actionList['design']['url']  = helper::createLink('pivot', 'design', 'pivotID={id}');

$config->pivot->dtable->actionList['delete']['icon']         = 'trash';
$config->pivot->dtable->actionList['delete']['text']         = $lang->delete;
$config->pivot->dtable->actionList['delete']['hint']         = $lang->delete;
$config->pivot->dtable->actionList['delete']['url']          = helper::createLink('pivot', 'delete', 'pivotID={id}&from=browse');
$config->pivot->dtable->actionList['delete']['className']    = 'ajax-submit';
$config->pivot->dtable->actionList['delete']['data-confirm'] = array('message' => $lang->pivot->deleteTip, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->pivot->dtable->actionList['delete']['notInModal']   = true;

$config->pivot->dtable->definition = new stdclass();
$config->pivot->dtable->definition->fieldList = array();
$config->pivot->dtable->definition->fieldList['id']['title']    = $lang->idAB;
$config->pivot->dtable->definition->fieldList['id']['type']     = 'checkID';
$config->pivot->dtable->definition->fieldList['id']['flex']     = '1';
$config->pivot->dtable->definition->fieldList['id']['sortType'] = true;
$config->pivot->dtable->definition->fieldList['id']['required'] = true;
$config->pivot->dtable->definition->fieldList['id']['group']    = 1;

$config->pivot->dtable->definition->fieldList['name']['title']    = $lang->pivot->name;
$config->pivot->dtable->definition->fieldList['name']['type']     = 'title';
$config->pivot->dtable->definition->fieldList['name']['link']     = array('module' => 'pivot', 'method' => 'design', 'params' => 'pivotID={id}');
$config->pivot->dtable->definition->fieldList['name']['required'] = true;
$config->pivot->dtable->definition->fieldList['name']['group']    = 1;

$config->pivot->dtable->definition->fieldList['createdBy']['title']    = $lang->openedByAB;
$config->pivot->dtable->definition->fieldList['createdBy']['type']     = 'user';
$config->pivot->dtable->definition->fieldList['createdBy']['minWidth'] = '100';
$config->pivot->dtable->definition->fieldList['createdBy']['required'] = true;
$config->pivot->dtable->definition->fieldList['createdBy']['group']    = 2;

$config->pivot->dtable->definition->fieldList['group']['title']    = $lang->pivot->group;
$config->pivot->dtable->definition->fieldList['group']['type']     = 'text';
$config->pivot->dtable->definition->fieldList['group']['required'] = true;
$config->pivot->dtable->definition->fieldList['group']['group']    = 2;

$config->pivot->dtable->definition->fieldList['version']['title']    = $lang->pivot->version;
$config->pivot->dtable->definition->fieldList['version']['type']     = 'text';
$config->pivot->dtable->definition->fieldList['version']['required'] = false;
$config->pivot->dtable->definition->fieldList['version']['sortType'] = true;
$config->pivot->dtable->definition->fieldList['version']['group']    = 2;

$config->pivot->dtable->definition->fieldList['desc']['title']    = $lang->pivot->desc;
$config->pivot->dtable->definition->fieldList['desc']['type']     = 'text';
$config->pivot->dtable->definition->fieldList['desc']['minWidth'] = '400';
$config->pivot->dtable->definition->fieldList['desc']['required'] = false;
$config->pivot->dtable->definition->fieldList['desc']['group']    = 2;

$config->pivot->dtable->definition->fieldList['actions']['name']     = 'actions';
$config->pivot->dtable->definition->fieldList['actions']['title']    = $lang->actions;
$config->pivot->dtable->definition->fieldList['actions']['list']     = $config->pivot->dtable->actionList;
$config->pivot->dtable->definition->fieldList['actions']['type']     = 'actions';
$config->pivot->dtable->definition->fieldList['actions']['fixed']    = true;
$config->pivot->dtable->definition->fieldList['actions']['menu']     = array('edit', 'design', 'delete');
