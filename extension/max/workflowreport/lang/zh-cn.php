<?php
$lang->workflowreport->common   = '工作流报表';
$lang->workflowreport->report   = '报表';
$lang->workflowreport->browse   = '报表设置';
$lang->workflowreport->preview  = '报表预览';
$lang->workflowreport->property = '报表及属性设置';

$lang->workflowreport->brow     = '浏览报表';
$lang->workflowreport->create   = '新增报表';
$lang->workflowreport->edit     = '编辑报表';
$lang->workflowreport->delete   = '删除报表';
$lang->workflowreport->sort     = '报表排序';
$lang->workflowreport->generate = '生成报表';

$lang->workflowreport->select      = '请选择报表';
$lang->workflowreport->name        = '名称';
$lang->workflowreport->type        = '类型';
$lang->workflowreport->countType   = '统计类型';
$lang->workflowreport->displayType = '显示方式';
$lang->workflowreport->dimension   = '维度';
$lang->workflowreport->fields      = '统计数值';

$lang->workflowreport->typeList['pie']  = '饼状图';
$lang->workflowreport->typeList['line'] = '折线图';
$lang->workflowreport->typeList['bar']  = '柱状图';
$lang->workflowreport->iconList['pie']  = '饼状图  <i class="icon icon-chart-pie"></i>';
$lang->workflowreport->iconList['line'] = '折线图  <i class="icon icon-chart-line"></i>';
$lang->workflowreport->iconList['bar']  = '柱状图  <i class="icon icon-chart-bar"></i>';

$lang->workflowreport->countTypeList['sum']   = '求和';
$lang->workflowreport->countTypeList['count'] = '计数';

$lang->workflowreport->displayTypeList['value']   = '实际值';
$lang->workflowreport->displayTypeList['percent'] = '百分比';

$lang->workflowreport->granularityList['year']      = '按年统计';
$lang->workflowreport->granularityList['quarter']   = '按季度统计';
$lang->workflowreport->granularityList['month']     = '按月统计';
$lang->workflowreport->granularityList['week']      = '按周统计';
$lang->workflowreport->granularityList['day']       = '按天统计';

/* Tips */
$lang->workflowreport->tips = new stdclass();
$lang->workflowreport->tips->noReport = '当前没有报表，';
$lang->workflowreport->tips->toCreate = '去新增';
$lang->workflowreport->tips->source   = '注：统计报表的数据，来源于列表页面的检索结果，生成统计报表前请先在列表页面进行检索。';

/* Demo chart data. */
$lang->workflowreport->demo = new stdclass();
$lang->workflowreport->demo->pie1    = '蓝色';
$lang->workflowreport->demo->pie2    = '红色';
$lang->workflowreport->demo->pie3    = '绿色';
$lang->workflowreport->demo->pie4    = '黄色';
$lang->workflowreport->demo->dataset = '数据';
$lang->workflowreport->demo->month1  = '一月';
$lang->workflowreport->demo->month2  = '二月';
$lang->workflowreport->demo->month3  = '三月';
$lang->workflowreport->demo->month4  = '四月';
$lang->workflowreport->demo->month5  = '五月';
$lang->workflowreport->demo->month6  = '六月';

/* Error */
$lang->workflowreport->error = new stdclass();
$lang->workflowreport->error->notOneTable = '<strong> ' . $lang->workflowreport->dimension . ' </strong>和<strong> ' . $lang->workflowreport->fields . ' </strong>必须是同一个表中的字段。';
