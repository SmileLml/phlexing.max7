<?php
$lang->review->begin = '计划开始时间';

$lang->review->reviewPoint = new stdclass();
$lang->review->reviewPoint->titleList['TR1']  = 'TR1-产品需求和概念评审';
$lang->review->reviewPoint->titleList['TR2']  = 'TR2-需求分解和规格评审';
$lang->review->reviewPoint->titleList['TR3']  = 'TR3-总体方案评审';
$lang->review->reviewPoint->titleList['TR4']  = 'TR4-工程样机评审';
$lang->review->reviewPoint->titleList['TR4A'] = 'TR4A-设计样机评审';
$lang->review->reviewPoint->titleList['TR5']  = 'TR5-样机评审';
$lang->review->reviewPoint->titleList['TR6']  = 'TR6-小批量评审';
$lang->review->reviewPoint->titleList['CDCP'] = 'CDCP-概念决策评审';
$lang->review->reviewPoint->titleList['PDCP'] = 'PDCP-计划决策评审';
$lang->review->reviewPoint->titleList['ADCP'] = 'ADCP-可获得性决策评审';
$lang->review->reviewPoint->titleList['LDCP'] = 'LDCP-生命周期终止决策评审';

$lang->review->stageNotStartTip   = '该评审点所属阶段还未开始，暂不能发起评审';
$lang->review->prePointNotPassTip = '前一个评审点通过评审后，当前评审点才能发起评审';

$lang->review->errorLetter      = "计划完成时间应当不小于计划开始时间。";
$lang->review->errorBeginTip    = "计划开始时间应当大于阶段计划开始时间[%s]。";
$lang->review->errorDeadlineTip = "计划完成时间应当小于阶段计划完成时间[%s]。";
