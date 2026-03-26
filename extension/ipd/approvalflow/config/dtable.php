<?php
global $lang;

$config->approvalflow->dtable = new stdclass();
$config->approvalflow->dtable->role   = new stdclass();
$config->approvalflow->dtable->browse = new stdclass();

$config->approvalflow->dtable->role->fieldList['id']['title']    = $lang->idAB;
$config->approvalflow->dtable->role->fieldList['id']['type']     = 'id';
$config->approvalflow->dtable->role->fieldList['id']['sortType'] = false;
$config->approvalflow->dtable->role->fieldList['id']['checkbox'] = true;
$config->approvalflow->dtable->role->fieldList['id']['required'] = true;

$config->approvalflow->dtable->role->fieldList['name']['title']    = $lang->approvalflow->role->name;
$config->approvalflow->dtable->role->fieldList['name']['type']     = 'name';
$config->approvalflow->dtable->role->fieldList['name']['required'] = true;
$config->approvalflow->dtable->role->fieldList['name']['minWidth'] = '356';

$config->approvalflow->dtable->role->fieldList['code']['title']    = $lang->approvalflow->role->code;
$config->approvalflow->dtable->role->fieldList['code']['type']     = 'text';
$config->approvalflow->dtable->role->fieldList['code']['required'] = true;

$config->approvalflow->dtable->role->fieldList['member']['title']    = $lang->approvalflow->role->member;
$config->approvalflow->dtable->role->fieldList['member']['type']     = 'text';
$config->approvalflow->dtable->role->fieldList['member']['required'] = true;

$config->approvalflow->dtable->role->fieldList['desc']['title']    = $lang->approvalflow->role->desc;
$config->approvalflow->dtable->role->fieldList['desc']['type']     = 'text';
$config->approvalflow->dtable->role->fieldList['desc']['required'] = true;

$config->approvalflow->dtable->role->fieldList['actions']['title']    = $lang->actions;
$config->approvalflow->dtable->role->fieldList['actions']['type']     = 'actions';
$config->approvalflow->dtable->role->fieldList['actions']['required'] = true;
$config->approvalflow->dtable->role->fieldList['actions']['fixed']    = 'right';
$config->approvalflow->dtable->role->fieldList['actions']['list']     = $config->approvalflow->actionList;
$config->approvalflow->dtable->role->fieldList['actions']['menu']     = array('editrole', 'deleterole');

$config->approvalflow->dtable->browse->fieldList['id']['title']    = $lang->idAB;
$config->approvalflow->dtable->browse->fieldList['id']['type']     = 'id';
$config->approvalflow->dtable->browse->fieldList['id']['sortType'] = false;
$config->approvalflow->dtable->browse->fieldList['id']['checkbox'] = true;
$config->approvalflow->dtable->browse->fieldList['id']['required'] = true;

$config->approvalflow->dtable->browse->fieldList['name']['title']    = $lang->approvalflow->name;
$config->approvalflow->dtable->browse->fieldList['name']['type']     = 'name';
$config->approvalflow->dtable->browse->fieldList['name']['link']     = array('url' => array('module' => 'approvalflow', 'method' => 'view', 'params' => 'id={id}'));
$config->approvalflow->dtable->browse->fieldList['name']['required'] = true;
$config->approvalflow->dtable->browse->fieldList['name']['minWidth'] = '356';

$config->approvalflow->dtable->browse->fieldList['desc']['title']    = $lang->approvalflow->desc;
$config->approvalflow->dtable->browse->fieldList['desc']['type']     = 'text';
$config->approvalflow->dtable->browse->fieldList['desc']['required'] = true;

$config->approvalflow->dtable->browse->fieldList['workflow']['title'] = $lang->approvalflow->workflow;
$config->approvalflow->dtable->browse->fieldList['workflow']['type']  = 'text';

$config->approvalflow->dtable->browse->fieldList['createdBy']['title']    = $lang->approvalflow->createdBy;
$config->approvalflow->dtable->browse->fieldList['createdBy']['type']     = 'user';
$config->approvalflow->dtable->browse->fieldList['createdBy']['sortType'] = false;
$config->approvalflow->dtable->browse->fieldList['createdBy']['required'] = true;

$config->approvalflow->dtable->browse->fieldList['createdDate']['title']    = $lang->approvalflow->createdDate;
$config->approvalflow->dtable->browse->fieldList['createdDate']['type']     = 'date';
$config->approvalflow->dtable->browse->fieldList['createdDate']['sortType'] = false;
$config->approvalflow->dtable->browse->fieldList['createdDate']['required'] = true;

$config->approvalflow->dtable->browse->fieldList['actions']['title']    = $lang->actions;
$config->approvalflow->dtable->browse->fieldList['actions']['type']     = 'actions';
$config->approvalflow->dtable->browse->fieldList['actions']['required'] = true;
$config->approvalflow->dtable->browse->fieldList['actions']['fixed']    = 'right';
$config->approvalflow->dtable->browse->fieldList['actions']['list']     = $config->approvalflow->actionList;
$config->approvalflow->dtable->browse->fieldList['actions']['menu']     = array('edit', 'design', 'delete');
