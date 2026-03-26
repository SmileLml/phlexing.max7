<?php
$lang->workflowrelation->common           = '工作流跨流程設置';
$lang->workflowrelation->admin            = '跨流程設置';
$lang->workflowrelation->createForeignKey = '新建';

$lang->workflowrelation->prev       = '前置流程';
$lang->workflowrelation->next       = '後置流程';
$lang->workflowrelation->action     = '動作';
$lang->workflowrelation->foreignKey = '外鍵';

$lang->workflowrelation->relationActionList['one2one']   = '一個前置流程創建一個後置流程';
$lang->workflowrelation->relationActionList['one2many']  = '一個前置流程創建多個後置流程';
$lang->workflowrelation->relationActionList['many2one']  = '多個前置流程創建一個後置流程';
$lang->workflowrelation->relationActionList['many2many'] = '多個前置流程創建多個後置流程';

$lang->workflowrelation->tableWidth = 900;

/* Tips */
$lang->workflowrelation->tips = new stdclass();
$lang->workflowrelation->tips->foreignKey = '<strong>外鍵</strong>是當前流程中用來關聯顯示前置流程數據的欄位。設為外鍵的欄位應該使用<strong>下拉菜單</strong>或者<strong>單選按鈕</strong>作為控件，如果設為外鍵的欄位控件不是<strong>下拉菜單</strong>或者<strong>單選按鈕</strong>，系統將預設更新控件為<strong>下拉菜單</strong>並選擇數據源為<strong>前置流程</strong>。';

/* Error */
$lang->workflowrelation->error = new stdclass();
$lang->workflowrelation->error->existNextField  = '該欄位已經在<strong> %s </strong>流程的跨流程設置中使用。';
