<?php
$lang->custom->estimate           = '估算配置';
$lang->custom->estimateConfig     = '估算配置';
$lang->custom->estimateUnit       = '估算单位';
$lang->custom->estimateEfficiency = '生产率';
$lang->custom->estimateCost       = '单位人工成本';
$lang->custom->estimateHours      = '每日工时';
$lang->custom->estimateDays       = '每周工作天数';

$lang->custom->conceptOptions->estimateUnit = array();
$lang->custom->conceptOptions->estimateUnit['0'] = '工时(H)';
$lang->custom->conceptOptions->estimateUnit['1'] = '故事点(SP)';
$lang->custom->conceptOptions->estimateUnit['2'] = '功能点(FP)';

$lang->custom->baseline = new stdClass();
$lang->custom->baseline->fields['objectList'] = '评审对象';

$lang->custom->issue = new stdClass();
$lang->custom->issue->fields['typeList']     = '类型';
$lang->custom->issue->fields['severityList'] = '严重程度';
$lang->custom->issue->fields['priList']      = '优先级';

$lang->custom->risk = new stdClass();
$lang->custom->risk->fields['sourceList']   = '来源';
$lang->custom->risk->fields['categoryList'] = '类型';

$lang->custom->opportunity = new stdClass();
$lang->custom->opportunity->fields['sourceList'] = '来源';
$lang->custom->opportunity->fields['typeList']   = '类型';

$lang->custom->nc = new stdClass();
$lang->custom->nc->fields['typeList']     = '分类';
$lang->custom->nc->fields['severityList'] = '严重程度';

$lang->custom->scrum->common = '敏捷' . $lang->projectCommon . '-%s';
$lang->custom->scrum->features['issue']       = '问题';
$lang->custom->scrum->features['risk']        = '风险';
$lang->custom->scrum->features['opportunity'] = '机会';
$lang->custom->scrum->features['process']     = '过程';
$lang->custom->scrum->features['auditplan']   = 'QA';
$lang->custom->scrum->features['meeting']     = '会议';
$lang->custom->scrum->features['measrecord']  = '度量';
