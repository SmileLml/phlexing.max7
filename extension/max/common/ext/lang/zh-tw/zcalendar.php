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
$lang->effort = new stdclass();
if(isset($lang->my->webMenu))      $lang->effort->webMenu      = $lang->my->webMenu;
if(isset($lang->my->webMenuOrder)) $lang->effort->webMenuOrder = $lang->my->webMenuOrder;

$lang->my->menu->effort  = array('link' => '日誌|effort|calendar|', 'exclude' => 'my-todo');
$lang->my->menuOrder[11] = 'effort';

/* Insert effort into $lang->user->menu.*/
$lang->system->menu->effort  = array('link' => '日誌|company|effort', 'subModule' => 'effort', 'alias' => 'calendar');
$lang->system->menu->todo    = array('link' => '日程|company|todo|');
$lang->system->menuOrder[6]  = 'todo';
$lang->system->menuOrder[16] = 'effort';

$lang->execution->menu->effort  = array('link' => '日誌|execution|effortcalendar|executionID=%s', 'alias' => 'effort');
$lang->execution->menuOrder[44] = 'effort';

$lang->execution->menu->view['subMenu']->taskeffort = array('link' => '工時明細表|execution|taskeffort|executionID=%s');
$lang->execution->menu->view['subMenu']->calendar   = array('link' => '任務日曆|execution|calendar|executionID=%s', 'alias' => 'calendar');
$lang->execution->menu->view['menuOrder'][20] = 'taskeffort';
$lang->execution->menu->view['menuOrder'][25] = 'calendar';

$lang->today = '今天';
$lang->textNetworkError = '網絡錯誤';
$lang->textHasMoreItems = '還有 {0} 項...';

$lang->project->noMultiple->scrum->menu->effort  = $lang->execution->menu->effort;
$lang->project->noMultiple->scrum->menuOrder[31] = 'effort';
