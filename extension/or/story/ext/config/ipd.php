<?php
global $app;
$app->loadLang('demand');
$app->loadLang('requirement');
$config->story->dtable->defaultField = array('id', 'title', 'pri', 'roadmap', 'status', 'assignedTo', 'category', 'duration', 'BSA', 'actions');

$config->story->dtable->fieldList['stage']['statusMap'] = $lang->requirement->stageList;

unset($config->story->dtable->fieldList['SRS']);
unset($config->story->dtable->fieldList['actions']['actionsMap']['batchCreate']);
$config->story->dtable->fieldList['actions']['minWidth'] = 180;

$config->story->list->customBatchCreateFields = 'source,duration,BSA,estimate,review,keywords';
$config->story->custom->batchCreateFields     = 'module,spec,verify,review,%s';

$config->story->list->customBatchEditFields = 'source,duration,BSA,keywords,mailto';
$config->story->custom->batchEditFields     = '';

$config->story->form->create['BSA']      = array('type' => 'string', 'control' => 'select', 'required' => false, 'default' => '', 'options' => $lang->demand->bsaList);
$config->story->form->create['duration'] = array('type' => 'string', 'control' => 'select', 'required' => false, 'default' => '', 'options' => $lang->demand->durationList);
$config->story->form->create['roadmap']  = array('type' => 'int',    'control' => 'select', 'required' => false, 'default' => 0,  'options' => array());

$config->story->form->edit['BSA']      = array('type' => 'string', 'control' => 'select', 'required' => false, 'default' => '', 'options' => $lang->demand->bsaList);
$config->story->form->edit['duration'] = array('type' => 'string', 'control' => 'select', 'required' => false, 'default' => '', 'options' => $lang->demand->durationList);
$config->story->form->edit['roadmap']  = array('type' => 'int',    'control' => 'select', 'required' => false, 'default' => 0,  'options' => array());

//$batchCreateForm = array();
//foreach($config->story->form->batchCreate as $field => $option)
//{
//    $batchCreateFields[$field] = $option;
//    if($field == 'module') $batchCreateFields['roadmap'] = array('type' => 'int', 'width' => '200px', 'control' => 'picker', 'required' => false, 'default' => 0, 'options' => array());
//}
//$config->story->form->batchCreate = $batchCreateFields;

$config->story->form->batchCreate['BSA']      = array('ditto' => true, 'type' => 'string', 'control' => 'picker', 'required' => false, 'width' => '200px', 'default' => '', 'options' => $lang->demand->bsaList);
$config->story->form->batchCreate['duration'] = array('ditto' => true, 'type' => 'string', 'control' => 'picker', 'required' => false, 'width' => '200px', 'default' => '', 'options' => $lang->demand->durationList);
unset($config->story->form->batchCreate['plan']);

$config->story->form->batchEdit['BSA']      = array('type' => 'string', 'control' => 'picker', 'required' => false, 'width' => '200px', 'default' => '', 'options' => $lang->demand->bsaList);
$config->story->form->batchEdit['duration'] = array('type' => 'string', 'control' => 'picker', 'required' => false, 'width' => '200px', 'default' => '', 'options' => $lang->demand->durationList);
unset($config->story->form->batchEdit['plan']);
