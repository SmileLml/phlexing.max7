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
$lang->effort = new stdclass();
if(isset($lang->my->webMenu))      $lang->effort->webMenu      = $lang->my->webMenu;
if(isset($lang->my->webMenuOrder)) $lang->effort->webMenuOrder = $lang->my->webMenuOrder;

$lang->my->menu->effort  = array('link' => '日志|effort|calendar|', 'exclude' => 'my-todo');
$lang->my->menuOrder[11] = 'effort';

/* Insert effort into $lang->user->menu.*/
$lang->system->menu->effort  = array('link' => '日志|company|effort', 'subModule' => 'effort', 'alias' => 'calendar');
$lang->system->menu->todo    = array('link' => '日程|company|todo|');
$lang->system->menuOrder[6]  = 'todo';
$lang->system->menuOrder[16] = 'effort';

$lang->execution->menu->effort  = array('link' => '日志|execution|effortcalendar|executionID=%s', 'alias' => 'effort');
$lang->execution->menuOrder[44] = 'effort';

$lang->execution->menu->view['subMenu']->taskeffort = array('link' => '工时明细表|execution|taskeffort|executionID=%s');
$lang->execution->menu->view['subMenu']->calendar   = array('link' => '任务日历|execution|calendar|executionID=%s', 'alias' => 'calendar');
$lang->execution->menu->view['menuOrder'][20] = 'taskeffort';
$lang->execution->menu->view['menuOrder'][25] = 'calendar';

$lang->today = '今天';
$lang->textNetworkError = '网络错误';
$lang->textHasMoreItems = '还有 {0} 项...';

$lang->project->noMultiple->scrum->menu->effort  = $lang->execution->menu->effort;
$lang->project->noMultiple->scrum->menuOrder[31] = 'effort';
