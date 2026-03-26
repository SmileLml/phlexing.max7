<?php
$lang->custom->executionCommon = '看板';
$lang->custom->closedExecution = '已關閉' . $lang->custom->executionCommon;
$lang->custom->notice->readOnlyOfExecution = "禁止修改後，已關閉{$lang->custom->executionCommon}下的任務、日誌以及關聯目標都禁止修改。";

$lang->custom->moduleName['execution'] = $lang->custom->executionCommon;

$lang->custom->task = new stdClass();
$lang->custom->task->fields['required'] = $lang->custom->required;
$lang->custom->task->fields['priList']  = '優先順序';
$lang->custom->task->fields['typeList'] = '類型';

$lang->custom->story = new stdClass();
$lang->custom->story->fields['required']         = $lang->custom->required;
$lang->custom->story->fields['priList']          = '優先順序';
$lang->custom->story->fields['reasonList']       = '關閉原因';
$lang->custom->story->fields['statusList']       = '狀態';
$lang->custom->story->fields['reviewRules']      = '評審規則';
$lang->custom->story->fields['reviewResultList'] = '評審結果';
$lang->custom->story->fields['review']           = '評審流程';

$lang->custom->execution = new stdclass();

$lang->custom->system = array('required');
