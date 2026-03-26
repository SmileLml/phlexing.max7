<?php
$lang->metriclib->common         = "度量库";
$lang->metriclib->browse         = '查看度量库';
$lang->metriclib->libraryName    = "%s%s";
$lang->metriclib->details        = '详情';
$lang->metriclib->libraryDetails = '度量库详情';
$lang->metriclib->latestView     = '查看最新数据';
$lang->metriclib->historyView    = '查看历史数据';
$lang->metriclib->name           = '度量库名称';
$lang->metriclib->code           = '度量库代号';
$lang->metriclib->scope          = '所属范围';
$lang->metriclib->period         = '时间属性';
$lang->metriclib->desc           = '度量库描述';
$lang->metriclib->createdBy      = '由谁创建';
$lang->metriclib->createdInfo    = 'system';

$lang->metriclib->calcDate       = '采集日期';
$lang->metriclib->calcDateFormat = 'Y年m月d日 H:i';
$lang->metriclib->null           = '（空）';

$lang->metriclib->metriclibTip['empty']  = '暂无度量项';
$lang->metriclib->metriclibTip['nodata'] = '度量范围内未产生数据，暂无数据';
$lang->metriclib->metriclibTip['notrun'] = '未到采集时间，暂无数据';

$lang->metriclib->createMetric = '添加度量项';

$lang->metriclib->scopeList = array();
$lang->metriclib->scopeList['project']   = "项目";
$lang->metriclib->scopeList['product']   = "产品";
$lang->metriclib->scopeList['execution'] = "执行";
$lang->metriclib->scopeList['user']      = "个人";
$lang->metriclib->scopeList['system']    = "系统";

$lang->metriclib->periodList = array();
$lang->metriclib->periodList['nodate'] = "无";
$lang->metriclib->periodList['year']   = "年份";
$lang->metriclib->periodList['month']  = "月份";
$lang->metriclib->periodList['week']   = "周";
$lang->metriclib->periodList['day']    = "日";

$lang->metriclib->periodTextList = array();
$lang->metriclib->periodTextList['nodate'] = "汇总数据度量库";
$lang->metriclib->periodTextList['year']   = "年度数据度量库";
$lang->metriclib->periodTextList['month']  = "月度数据度量库";
$lang->metriclib->periodTextList['week']   = "每周数据度量库";
$lang->metriclib->periodTextList['day']    = "每日数据度量库";

$lang->metriclib->headerList = new stdclass();
$lang->metriclib->headerList->scope = array();
$lang->metriclib->headerList->scope['project']   = "项目名称";
$lang->metriclib->headerList->scope['product']   = "产品名称";
$lang->metriclib->headerList->scope['execution'] = "执行名称";
$lang->metriclib->headerList->scope['user']      = "姓名";

$lang->metriclib->headerList->period = array();
$lang->metriclib->headerList->period['nodate'] = "采集日期";
$lang->metriclib->headerList->period['year']   = "年份";
$lang->metriclib->headerList->period['month']  = "月份";
$lang->metriclib->headerList->period['week']   = "周";
$lang->metriclib->headerList->period['day']    = "日期";

$lang->metriclib->parentLabel = array();
$lang->metriclib->parentLabel['program'] = '所属项目集';
$lang->metriclib->parentLabel['project'] = '所属项目';
$lang->metriclib->parentLabel['dept']    = '所属部门';

$lang->metriclib->dateLabel = array();
$lang->metriclib->dateLabel['nodate'] = '采集日期';
$lang->metriclib->dateLabel['year']   = '年份';
$lang->metriclib->dateLabel['month']  = '月份';
$lang->metriclib->dateLabel['week']   = '周';
$lang->metriclib->dateLabel['day']    = '日期';
