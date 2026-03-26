<?php
$lang->programplan->stageCustom['point'] = '顯示評審點';

$lang->programplan->parallel      = '一級階段是否允許並行';
$lang->programplan->sortableTip   = '一級階段不支持排序';
$lang->programplan->addSiblingTip = '一級階段不支持添加';
$lang->programplan->pointTip      = '僅一級階段可配置評審點';

$lang->programplan->parallelTip = "<i class='icon icon-help text-gray' title='不支持並行是指上一階段完成之後下一階段才能開始，例如：概念階段完成之後，計劃階段才能開始，包括階段下的任務。
並行是指階段與階段之間沒有依賴關係，階段及任務的開始狀態不受其他階段狀態的影響。'></i>";

$lang->programplan->parallelList[0] = '否';
$lang->programplan->parallelList[1] = '是';

$lang->programplan->error->outOfDate  = '計劃開始時間應當大於上一階段的計劃完成時間';
$lang->programplan->error->lessOfDate = '計劃完成時間應當小於下一階段的計劃開始時間';