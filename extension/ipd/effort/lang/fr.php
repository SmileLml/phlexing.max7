<?php
/**
 * The effort module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     effort
 * @version     $Id: en.php 2605 2012-02-21 07:22:58Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->effort->common          = 'Effort';
$lang->effort->index           = "Effort List";
$lang->effort->create          = "Log Effort";
$lang->effort->batchCreate     = "Log Effort";
$lang->effort->createForObject = "Log Effort for Items";
$lang->effort->edit            = "Edit Effort";
$lang->effort->batchEdit       = "Batch Edit";
$lang->effort->view            = "Effort Details";
$lang->effort->viewAB          = "Info";
$lang->effort->comment         = 'Comment';
$lang->effort->export          = "Export";
$lang->effort->exportAction    = "Export Effort";
$lang->effort->delete          = "Delete Effort";
$lang->effort->browse          = "View";
$lang->effort->history         = "History";
$lang->effort->hour            = "h";
$lang->effort->clean           = "Clean";
$lang->effort->workHour        = "Hours";
$lang->effort->handleTask      = "Handle Task";

$lang->effort->id          = 'ID';
$lang->effort->account     = 'Recorder';
$lang->effort->date        = 'Date';
$lang->effort->dept        = 'Department';
$lang->effort->left        = 'Hours Left';
$lang->effort->leftAB      = 'Left';
$lang->effort->consumed    = 'Hours Cost';
$lang->effort->consumedAB  = 'Cost';
$lang->effort->objectType  = 'Item';
$lang->effort->objectID    = 'Item ID';
$lang->effort->product     = $lang->productCommon;
$lang->effort->execution   = $lang->execution->common;
$lang->effort->project     = $lang->project->common;
$lang->effort->work        = 'Work Log';
$lang->effort->deal        = 'Handle';
$lang->effort->allDept     = 'All';
$lang->effort->begin       = 'Begin';
$lang->effort->end         = 'End';

$lang->effort->week         = '(l)';  // date function's param.
$lang->effort->today        = 'Today';
$lang->effort->weekDateList = '';

global $config;
$lang->effort->objectTypeList['custom']      = '';
if($config->enableER) $lang->effort->objectTypeList['epic']        = $lang->ERCommon;
if($config->URAndSR)  $lang->effort->objectTypeList['requirement'] = $lang->URCommon;
$lang->effort->objectTypeList['story']       = 'Story';
$lang->effort->objectTypeList['productplan'] = 'Product Plan';
$lang->effort->objectTypeList['release']     = 'Release';
$lang->effort->objectTypeList['task']        = 'Task';
$lang->effort->objectTypeList['build']       = 'Build';
$lang->effort->objectTypeList['bug']         = 'Bug';
$lang->effort->objectTypeList['case']        = 'Case';
$lang->effort->objectTypeList['testcase']    = 'Test Case';
$lang->effort->objectTypeList['testtask']    = 'Test';
$lang->effort->objectTypeList['doc']         = 'Doc';
$lang->effort->objectTypeList['todo']        = 'Todo';
$lang->effort->objectTypeList['ticket']      = 'Ticket';

$lang->effort->todayEfforts     = 'Today';
$lang->effort->yesterdayEfforts = 'Yesterday';
$lang->effort->thisWeekEfforts  = 'This Week';
$lang->effort->lastWeekEfforts  = 'Last Week';
$lang->effort->thisMonthEfforts = 'This Month';
$lang->effort->lastMonthEfforts = 'Last Month';
$lang->effort->allDaysEfforts   = 'All';

$lang->effort->featureBar['effort']['today']     = $lang->effort->todayEfforts;
$lang->effort->featureBar['effort']['yesterday'] = $lang->effort->yesterdayEfforts;
$lang->effort->featureBar['effort']['thisweek']  = $lang->effort->thisWeekEfforts;
$lang->effort->featureBar['effort']['lastweek']  = $lang->effort->lastWeekEfforts;
$lang->effort->featureBar['effort']['thismonth'] = $lang->effort->thisMonthEfforts;
$lang->effort->featureBar['effort']['lastmonth'] = $lang->effort->lastMonthEfforts;
$lang->effort->featureBar['effort']['all']       = $lang->effort->allDaysEfforts;

$lang->effort->periods['all']       = $lang->effort->allDaysEfforts;
$lang->effort->periods['today']     = $lang->effort->todayEfforts;
$lang->effort->periods['yesterday'] = $lang->effort->yesterdayEfforts;
$lang->effort->periods['thisweek']  = $lang->effort->thisWeekEfforts;
$lang->effort->periods['lastweek']  = $lang->effort->lastWeekEfforts;
$lang->effort->periods['thismonth'] = $lang->effort->thisMonthEfforts;
$lang->effort->periods['lastmonth'] = $lang->effort->lastMonthEfforts;

$lang->effort->deleted           = 'Deleted';
$lang->effort->notEmpty          = 'It should not be empty.';
$lang->effort->notNegative       = "It should not be negative.";
$lang->effort->isNumber          = 'It must be numbers.';
$lang->effort->tooLang           = 'ID%s is too long.';
$lang->effort->nowork            = "ID%s  should not be empty!";
$lang->effort->notice            = '(Note: 1. If the %s is empty, it is invalid; 2. If the %s is not equal %s and %s is empty, it is invalid)';
$lang->effort->notFuture         = 'Effort for future dates cannot be added.';
$lang->effort->confirmDelete     = "Do you want to delete this effort?";
$lang->effort->noticeClean       = "Only remove efforts generated automatically.";
$lang->effort->noticeFinish      = "The left hour is 0, and the status will be Done. Do you want to continue?";
$lang->effort->noticeSaveRecord  = 'Please save the estimates.';
$lang->effort->remindSubject     = 'Effort reminder';
$lang->effort->remindContent     = "You didn't log in yesterday, <a href='%s' target='_blank'> Log Effort </a>";
$lang->effort->leftTip           = "Only tasks can enter remaining work";
$lang->effort->operateTips       = "Only the department head have the authority to %s logs belonging to others.";
