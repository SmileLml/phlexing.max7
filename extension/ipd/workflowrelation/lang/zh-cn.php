<?php
$lang->workflowrelation->common           = '工作流跨流程设置';
$lang->workflowrelation->admin            = '跨流程设置';
$lang->workflowrelation->createForeignKey = '新建';

$lang->workflowrelation->prev       = '前置流程';
$lang->workflowrelation->next       = '后置流程';
$lang->workflowrelation->action     = '动作';
$lang->workflowrelation->foreignKey = '外键';

$lang->workflowrelation->relationActionList['one2one']   = '一个前置流程创建一个后置流程';
$lang->workflowrelation->relationActionList['one2many']  = '一个前置流程创建多个后置流程';
$lang->workflowrelation->relationActionList['many2one']  = '多个前置流程创建一个后置流程';
$lang->workflowrelation->relationActionList['many2many'] = '多个前置流程创建多个后置流程';

$lang->workflowrelation->tableWidth = 900;

/* Tips */
$lang->workflowrelation->tips = new stdclass();
$lang->workflowrelation->tips->foreignKey = '<strong>外键</strong>是当前流程中用来关联显示前置流程数据的字段。设为外键的字段应该使用<strong>下拉菜单</strong>或者<strong>单选按钮</strong>作为控件，如果设为外键的字段控件不是<strong>下拉菜单</strong>或者<strong>单选按钮</strong>，系统将默认更新控件为<strong>下拉菜单</strong>并选择数据源为<strong>前置流程</strong>。';

/* Error */
$lang->workflowrelation->error = new stdclass();
$lang->workflowrelation->error->existNextField  = '该字段已经在<strong> %s </strong>流程的跨流程设置中使用。';
