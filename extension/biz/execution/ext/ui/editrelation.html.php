<?php
/**
 * The edit relation file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Qiyu Xie <xieqiyu@cnezsoft.com>
 * @package     execution
 * @version     $Id: editrelation.html.php 935 2024-08-08 15:14:24Z $
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('executionID', $executionID);
jsVar('projectID', $projectID);
jsVar('relationID',  $relationID);

modalHeader(set::title($lang->execution->editRelation), set::entityID($relationID), set::entityText(''), to::suffix(span(setClass('title-suffix text-sm font-normal ml-2'), $toolTips ? icon('exclamation-sign', setData(array('toggle' => 'tooltip', 'title' => $toolTips, 'placement' => 'top-end'))) : null, span(setClass('text-danger ml-1'), $titleTips))));

$items = array();
$items[] = array(
    'hidden'   => true,
    'name'     => 'id',
    'value'    => $relation->id
);
$items[] = array(
    'label'    => $lang->execution->gantt->pretask,
    'name'     => 'pretask',
    'items'    => $tasks,
    'value'    => $relation->pretask,
    'required' => true
);

$items[] = array(
    'label'    => $lang->execution->gantt->condition,
    'name'     => 'condition',
    'items'    => $lang->execution->gantt->preTaskStatus,
    'value'    => $relation->condition,
    'required' => true
);

$items[] = array(
    'label'    => $lang->execution->gantt->task,
    'name'     => 'task',
    'items'    => $tasks,
    'value'    => $relation->task,
    'required' => true
);

$items[] = array(
    'label'    => $lang->execution->gantt->action,
    'name'     => 'action',
    'items'    => $lang->execution->gantt->taskActions,
    'value'    => $relation->action,
    'required' => true
);

formPanel
(
    set::items($items),
    on::click('[data-name="pretask"]', 'loadTasks'),
    on::click('[data-name="task"]', 'loadTasks'),
    on::change('[name=pretask], [name=task]', 'changeTask')
);
