<?php
$config->dimension->create = new stdclass();
$config->dimension->edit   = new stdclass();
$config->dimension->create->requiredFields = 'name,code';
$config->dimension->edit->requiredFields   = 'name,code';

$config->dimension->changeDimensionLink['screen-design']    = 'screen|browse|dimensionID=%s';
$config->dimension->changeDimensionLink['pivot-browse']     = 'pivot|browse|dimensionID=%s';
$config->dimension->changeDimensionLink['pivot-design']     = 'pivot|browse|dimensionID=%s';
$config->dimension->changeDimensionLink['chart-browse']     = 'chart|browse|dimensionID=%s';
$config->dimension->changeDimensionLink['chart-create']     = 'chart|create|dimensionID=%s';
$config->dimension->changeDimensionLink['chart-edit']       = 'chart|browse|dimensionID=%s';
$config->dimension->changeDimensionLink['chart-design']     = 'chart|browse|dimensionID=%s';
$config->dimension->changeDimensionLink['tree-browsegroup'] = 'tree|browsegroup|dimensionID=%s&groupID=0&type=%s';

global $lang;
$config->dimension->actionList = array();
$config->dimension->actionList['edit']['icon']        = 'edit';
$config->dimension->actionList['edit']['text']        = $lang->edit;
$config->dimension->actionList['edit']['hint']        = $lang->edit;
$config->dimension->actionList['edit']['data-toggle'] = 'modal';
$config->dimension->actionList['edit']['url']         = array('module' => 'dimension', 'method' => 'edit', 'params' => 'id={id}');

$config->dimension->actionList['delete']['icon']      = 'trash';
$config->dimension->actionList['delete']['text']      = $lang->delete;
$config->dimension->actionList['delete']['hint']      = $lang->delete;
$config->dimension->actionList['delete']['data-on']   = 'click';
$config->dimension->actionList['delete']['data-call'] = 'confirmDelete';

$config->dimension->dtable = new stdclass();
$config->dimension->dtable->fieldList['id']['title']    = $lang->idAB;
$config->dimension->dtable->fieldList['id']['type']     = 'id';
$config->dimension->dtable->fieldList['id']['sortType'] = false;

$config->dimension->dtable->fieldList['name']['title']    = $lang->dimension->name;
$config->dimension->dtable->fieldList['name']['type']     = 'title';
$config->dimension->dtable->fieldList['name']['width']    = '250';
$config->dimension->dtable->fieldList['name']['sortType'] = false;

$config->dimension->dtable->fieldList['code']['title'] = $lang->dimension->code;
$config->dimension->dtable->fieldList['code']['type']  = 'code';
$config->dimension->dtable->fieldList['code']['width'] = '250';

$config->dimension->dtable->fieldList['desc']['title'] = $lang->dimension->desc;
$config->dimension->dtable->fieldList['desc']['type']  = 'desc';

$config->dimension->dtable->fieldList['actions']['title'] = $lang->actions;
$config->dimension->dtable->fieldList['actions']['type']  = 'actions';
$config->dimension->dtable->fieldList['actions']['fixed'] = 'right';
$config->dimension->dtable->fieldList['actions']['list']  = $config->dimension->actionList;
$config->dimension->dtable->fieldList['actions']['menu']  = array('edit', 'delete');

$config->dimension->form = new stdclass();
$config->dimension->form->create = array();
$config->dimension->form->create['name'] = array('required' => true,  'type' => 'string', 'filter'  => 'trim');
$config->dimension->form->create['code'] = array('required' => true,  'type' => 'string', 'filter'  => 'trim');
$config->dimension->form->create['desc'] = array('required' => false, 'type' => 'string', 'default' => '');
