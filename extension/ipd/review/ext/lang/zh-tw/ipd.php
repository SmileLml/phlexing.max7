<?php
$lang->review->begin = '計劃開始時間';

$lang->review->reviewPoint = new stdclass();
$lang->review->reviewPoint->titleList['TR1']  = 'TR1-產品需求和概念評審';
$lang->review->reviewPoint->titleList['TR2']  = 'TR2-需求分解和規格評審';
$lang->review->reviewPoint->titleList['TR3']  = 'TR3-總體方案評審';
$lang->review->reviewPoint->titleList['TR4']  = 'TR4-工程樣機評審';
$lang->review->reviewPoint->titleList['TR4A'] = 'TR4A-設計樣機評審';
$lang->review->reviewPoint->titleList['TR5']  = 'TR5-樣機評審';
$lang->review->reviewPoint->titleList['TR6']  = 'TR6-小批量評審';
$lang->review->reviewPoint->titleList['CDCP'] = 'CDCP-概念決策評審';
$lang->review->reviewPoint->titleList['PDCP'] = 'PDCP-計劃決策評審';
$lang->review->reviewPoint->titleList['ADCP'] = 'ADCP-可獲得性決策評審';
$lang->review->reviewPoint->titleList['LDCP'] = 'LDCP-生命周期終止決策評審';

$lang->review->stageNotStartTip   = '該評審點所屬階段還未開始，暫不能發起評審';
$lang->review->prePointNotPassTip = '前一個評審點通過評審後，當前評審點才能發起評審';

$lang->review->errorLetter      = "計劃完成時間應當不小於計劃開始時間。";
$lang->review->errorBeginTip    = "計劃開始時間應當大於階段計劃開始時間[%s]。";
$lang->review->errorDeadlineTip = "計劃完成時間應當小於階段計劃完成時間[%s]。";
