<?php
/**
 * The task module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禪道軟件（青島）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     task
 * @version     $Id: zh-tw.php 5040 2013-07-06 06:22:18Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->task->index               = "任務一覽";
$lang->task->create              = "建任務";
$lang->task->batchCreateChildren = "批量建子任務";
$lang->task->edit                = "編輯任務";
$lang->task->deleteAction        = "刪除任務";
$lang->task->view                = "查看任務";
$lang->task->startAction         = "開始任務";
$lang->task->restartAction       = "繼續任務";
$lang->task->finishAction        = "完成任務";
$lang->task->pauseAction         = "暫停任務";
$lang->task->closeAction         = "關閉任務";
$lang->task->cancelAction        = "取消任務";
$lang->task->activateAction      = "激活任務";
$lang->task->exportAction        = "導出任務";
$lang->task->copy                = '複製任務';
$lang->task->waitTask            = '未開始的任務';
$lang->task->region              = '所屬區域';
$lang->task->lane                = '所屬泳道';

$lang->task->module       = '所屬目錄';
$lang->task->allModule    = '所有目錄';
$lang->task->common       = '任務';
$lang->task->name         = '任務名稱';
$lang->task->type         = '任務類型';
$lang->task->status       = '任務狀態';
$lang->task->desc         = '任務描述';
$lang->task->assignAction = '指派任務';
$lang->task->multiple     = '多人任務';
$lang->task->children     = '子任務';
$lang->task->parent       = '父任務';

/* Fields of zt_taskestimate. */
$lang->task->task = '任務';

$lang->task->dittoNotice       = "該任務與上一任務不屬於同一%s！";
$lang->task->yesterdayFinished = '昨日完成任務數';
$lang->task->allTasks          = '總任務';

$lang->task->afterChoices['continueAdding'] = "繼續為該{$lang->SRCommon}添加任務";
$lang->task->afterChoices['toTaskList']     = '返回任務列表';

$lang->task->legendLife   = '任務的一生';
$lang->task->legendDesc   = '任務描述';
$lang->task->legendDetail = '任務詳情';

$lang->task->confirmDelete         = "您確定要刪除這個任務嗎？";
$lang->task->confirmDeleteEffort   = "您確定要刪除這個記錄嗎？";
$lang->task->confirmFinish         = '"預計剩餘"為0，確認將任務狀態改為"已完成"嗎？';
$lang->task->confirmRecord         = '"剩餘"為0，任務將標記為"已完成"，您確定嗎？';
$lang->task->confirmTransfer       = '"當前剩餘"為0，任務將被轉交，您確定嗎？';
$lang->task->noTask                = '暫時沒有任務。';
$lang->task->kanbanDenied          = '請先創建看板';
$lang->task->createDenied          = "你不能在該{$lang->projectCommon}添加任務";
$lang->task->cannotDeleteParent    = '不能刪除父任務。';
$lang->task->addChildTask          = '因該任務已經產生消耗，為保證數據一致性，我們會幫您創建一條同名子任務記錄該消耗。';

$lang->task->error->skipClose       = '任務：%s 不是“已完成”或“已取消”狀態，確定要關閉嗎？';
$lang->task->error->consumed        = '任務：%s工時不能小於0，忽略該任務工時的改動';
$lang->task->error->assignedTo      = '當前狀態的多人任務不能指派給任務團隊外的成員。';
$lang->task->error->alreadyStarted  = '此任務已被啟動，不能重複啟動！';
$lang->task->error->alreadyConsumed = '當前選中的父任務已有消耗。';

/* Report. */
$lang->task->report->value = '任務數';

$lang->task->report->charts['tasksPerExecution'] = '按' . $lang->executionCommon . '任務數統計';
$lang->task->report->charts['tasksPerModule']    = '按模組任務數統計';
$lang->task->report->charts['tasksPerType']      = '按任務類型統計';
$lang->task->report->charts['tasksPerStatus']    = '按任務狀態統計';
