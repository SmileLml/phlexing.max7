<?php
$lang->metriclib->common         = "度量庫";
$lang->metriclib->browse         = '查看度量庫';
$lang->metriclib->libraryName    = "%s%s";
$lang->metriclib->details        = '詳情';
$lang->metriclib->libraryDetails = '度量庫詳情';
$lang->metriclib->latestView     = '查看最新數據';
$lang->metriclib->historyView    = '查看歷史數據';
$lang->metriclib->name           = '度量庫名稱';
$lang->metriclib->code           = '度量庫代號';
$lang->metriclib->scope          = '所屬範圍';
$lang->metriclib->period         = '時間屬性';
$lang->metriclib->desc           = '度量庫描述';
$lang->metriclib->createdBy      = '由誰創建';
$lang->metriclib->createdInfo    = 'system';

$lang->metriclib->calcDate       = '採集日期';
$lang->metriclib->calcDateFormat = 'Y年m月d日 H:i';
$lang->metriclib->null           = '（空）';

$lang->metriclib->metriclibTip['empty']  = '暫無度量項';
$lang->metriclib->metriclibTip['nodata'] = '度量範圍內未產生數據，暫無數據';
$lang->metriclib->metriclibTip['notrun'] = '未到採集時間，暫無數據';

$lang->metriclib->createMetric = '添加度量項';

$lang->metriclib->scopeList = array();
$lang->metriclib->scopeList['project']   = "項目";
$lang->metriclib->scopeList['product']   = "產品";
$lang->metriclib->scopeList['execution'] = "執行";
$lang->metriclib->scopeList['user']      = "個人";
$lang->metriclib->scopeList['system']    = "系統";

$lang->metriclib->periodList = array();
$lang->metriclib->periodList['nodate'] = "無";
$lang->metriclib->periodList['year']   = "年份";
$lang->metriclib->periodList['month']  = "月份";
$lang->metriclib->periodList['week']   = "周";
$lang->metriclib->periodList['day']    = "日";

$lang->metriclib->periodTextList = array();
$lang->metriclib->periodTextList['nodate'] = "彙總數據度量庫";
$lang->metriclib->periodTextList['year']   = "年度數據度量庫";
$lang->metriclib->periodTextList['month']  = "月度數據度量庫";
$lang->metriclib->periodTextList['week']   = "每週數據度量庫";
$lang->metriclib->periodTextList['day']    = "每日數據度量庫";

$lang->metriclib->headerList = new stdclass();
$lang->metriclib->headerList->scope = array();
$lang->metriclib->headerList->scope['project']   = "項目名稱";
$lang->metriclib->headerList->scope['product']   = "產品名稱";
$lang->metriclib->headerList->scope['execution'] = "執行名稱";
$lang->metriclib->headerList->scope['user']      = "姓名";

$lang->metriclib->headerList->period = array();
$lang->metriclib->headerList->period['nodate'] = "採集日期";
$lang->metriclib->headerList->period['year']   = "年份";
$lang->metriclib->headerList->period['month']  = "月份";
$lang->metriclib->headerList->period['week']   = "周";
$lang->metriclib->headerList->period['day']    = "日期";

$lang->metriclib->parentLabel = array();
$lang->metriclib->parentLabel['program'] = '所屬項目集';
$lang->metriclib->parentLabel['project'] = '所屬項目';
$lang->metriclib->parentLabel['dept']    = '所屬部門';

$lang->metriclib->dateLabel = array();
$lang->metriclib->dateLabel['nodate'] = '採集日期';
$lang->metriclib->dateLabel['year']   = '年份';
$lang->metriclib->dateLabel['month']  = '月份';
$lang->metriclib->dateLabel['week']   = '周';
$lang->metriclib->dateLabel['day']    = '日期';
