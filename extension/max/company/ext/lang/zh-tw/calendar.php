<?php
/**
 * The lang file of calendar module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禪道軟件（青島）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商業軟件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     calendar
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->company->calendar       = '日誌';
$lang->company->todo           = '待辦';
$lang->company->selectDept     = '請選擇部門';
$lang->company->date           = '日期';
$lang->company->allDept        = '所有部門';
$lang->company->to             = '至';
$lang->company->user           = '用戶';
$lang->company->dept           = '部門';
$lang->company->show           = '顯示';
$lang->company->userType       = '類型';
$lang->company->effortCalendar = '日誌日曆';
$lang->company->todoCalendar   = '待辦日曆';
$lang->company->beginDate      = '開始';
$lang->company->endDate        = '結束';
$lang->company->companyTodo    = '組織待辦';
$lang->company->todoList       = '組織待辦列表';
$lang->company->effortList     = '日誌日曆列表';
$lang->company->allTodo        = '查看所屬部門待辦';

$lang->company->showUsers['all']       = '所有成員';
$lang->company->showUsers['logged']    = '填寫日誌成員';
$lang->company->showUsers['notLogged'] = '未填寫日誌成員';

$lang->company->userTypes['']        = '';
$lang->company->userTypes['inside']  = '內部人員';
$lang->company->userTypes['outside'] = '外部人員';

if(!isset($lang->company->effort)) $lang->company->effort = new stdclass();
$lang->company->effort->selectDate    = '日期';
$lang->company->effort->executionSelect = $lang->executionCommon;
$lang->company->effort->productSelect = $lang->productCommon;
$lang->company->effort->userSelect    = '用戶';
$lang->company->effort->view          = '查看';

$lang->company->currentDept = '當前部門';
$lang->company->noDept      = '無部門';

$lang->company->pageUserCount = '本頁共有%s個用戶';

$lang->company->featureBar['calendar']['calendar']  = '日誌';
$lang->company->featureBar['calendar']['today']     = '今日';
$lang->company->featureBar['calendar']['yesterday'] = '昨日';
$lang->company->featureBar['calendar']['thisweek']  = '本週';
$lang->company->featureBar['calendar']['lastweek']  = '上周';
$lang->company->featureBar['calendar']['thismonth'] = '本月';
$lang->company->featureBar['calendar']['lastmonth'] = '上月';
$lang->company->featureBar['calendar']['all']       = '全部';
