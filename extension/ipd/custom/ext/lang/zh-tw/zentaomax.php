<?php
$lang->custom->estimate           = '估算配置';
$lang->custom->estimateConfig     = '估算配置';
$lang->custom->estimateUnit       = '估算單位';
$lang->custom->estimateEfficiency = '生產率';
$lang->custom->estimateCost       = '單位人工成本';
$lang->custom->estimateHours      = '每日工時';
$lang->custom->estimateDays       = '每週工作天數';

$lang->custom->conceptOptions->estimateUnit = array();
$lang->custom->conceptOptions->estimateUnit['0'] = '工時(H)';
$lang->custom->conceptOptions->estimateUnit['1'] = '故事點(SP)';
$lang->custom->conceptOptions->estimateUnit['2'] = '功能點(FP)';

$lang->custom->baseline = new stdClass();
$lang->custom->baseline->fields['objectList'] = '評審對象';

$lang->custom->issue = new stdClass();
$lang->custom->issue->fields['typeList']     = '類型';
$lang->custom->issue->fields['severityList'] = '嚴重程度';
$lang->custom->issue->fields['priList']      = '優先順序';

$lang->custom->risk = new stdClass();
$lang->custom->risk->fields['sourceList']   = '來源';
$lang->custom->risk->fields['categoryList'] = '類型';

$lang->custom->opportunity = new stdClass();
$lang->custom->opportunity->fields['sourceList'] = '來源';
$lang->custom->opportunity->fields['typeList']   = '類型';

$lang->custom->nc = new stdClass();
$lang->custom->nc->fields['typeList']     = '分類';
$lang->custom->nc->fields['severityList'] = '嚴重程度';

$lang->custom->scrum->common = '敏捷' . $lang->projectCommon . '-%s';
$lang->custom->scrum->features['issue']       = '問題';
$lang->custom->scrum->features['risk']        = '風險';
$lang->custom->scrum->features['opportunity'] = '機會';
$lang->custom->scrum->features['process']     = '過程';
$lang->custom->scrum->features['auditplan']   = 'QA';
$lang->custom->scrum->features['meeting']     = '會議';
$lang->custom->scrum->features['measrecord']  = '度量';
