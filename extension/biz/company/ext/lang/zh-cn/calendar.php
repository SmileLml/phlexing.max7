<?php
/**
 * The lang file of calendar module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     calendar
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->company->calendar       = '日志';
$lang->company->todo           = '待办';
$lang->company->selectDept     = '请选择部门';
$lang->company->date           = '日期';
$lang->company->allDept        = '所有部门';
$lang->company->to             = '至';
$lang->company->user           = '用户';
$lang->company->dept           = '部门';
$lang->company->show           = '显示';
$lang->company->userType       = '类型';
$lang->company->effortCalendar = '日志日历';
$lang->company->todoCalendar   = '待办日历';
$lang->company->beginDate      = '开始';
$lang->company->endDate        = '结束';
$lang->company->companyTodo    = '组织待办';
$lang->company->todoList       = '组织待办列表';
$lang->company->effortList     = '日志日历列表';
$lang->company->allTodo        = '查看所属部门待办';

$lang->company->showUsers['all']       = '所有成员';
$lang->company->showUsers['logged']    = '填写日志成员';
$lang->company->showUsers['notLogged'] = '未填写日志成员';

$lang->company->userTypes['']        = '';
$lang->company->userTypes['inside']  = '内部人员';
$lang->company->userTypes['outside'] = '外部人员';

if(!isset($lang->company->effort)) $lang->company->effort = new stdclass();
$lang->company->effort->selectDate    = '日期';
$lang->company->effort->executionSelect = $lang->executionCommon;
$lang->company->effort->productSelect = $lang->productCommon;
$lang->company->effort->userSelect    = '用户';
$lang->company->effort->view          = '查看';

$lang->company->currentDept = '当前部门';
$lang->company->noDept      = '无部门';

$lang->company->pageUserCount = '本页共有%s个用户';

$lang->company->featureBar['calendar']['calendar']  = '日志';
$lang->company->featureBar['calendar']['today']     = '今日';
$lang->company->featureBar['calendar']['yesterday'] = '昨日';
$lang->company->featureBar['calendar']['thisweek']  = '本周';
$lang->company->featureBar['calendar']['lastweek']  = '上周';
$lang->company->featureBar['calendar']['thismonth'] = '本月';
$lang->company->featureBar['calendar']['lastmonth'] = '上月';
$lang->company->featureBar['calendar']['all']       = '全部';
