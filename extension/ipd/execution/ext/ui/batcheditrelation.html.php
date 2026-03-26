<?php
namespace zin;

jsVar('executionID',    $executionID);
jsVar('projectID',      $projectID);
jsVar('relationErrors', $relationErrors);

$items = array();

$items[] = array
(
    'name'    => 'index',
    'label'   => $lang->execution->gantt->id,
    'control' => 'index',
    'width'   => '60px'
);

$items[] = array
(
    'name'     => 'id',
    'width'    => 'auto',
    'hidden'   => true,
    'control'  => 'input'
);

$items[] = array
(
    'name'     => 'pretask',
    'label'    => $lang->execution->gantt->pretask,
    'width'    => 'auto',
    'control'  => array('control' => 'picker', 'required' => true),
    'items'    => $tasks,
);

$items[] = array
(
    'name'     => 'condition',
    'label'    => $lang->execution->gantt->condition,
    'width'    => '150px',
    'control'  => array('control' => 'picker', 'required' => true),
    'items'    => $lang->execution->gantt->preTaskStatus,
);

$items[] = array
(
    'name'     => 'task',
    'label'    => $lang->execution->gantt->task,
    'width'    => 'auto',
    'control'  => array('control' => 'picker', 'required' => true),
    'items'    => $tasks,
);

$items[] = array
(
    'name'     => 'action',
    'label'    => $lang->execution->gantt->action,
    'width'    => '150px',
    'control'  => array('control' => 'picker', 'required' => true),
    'items'    => $lang->execution->gantt->taskActions,
);

formBatchPanel
(
    set::mode('edit'),
    set::title($lang->execution->batchEditRelation),
    to::titleSuffix(span(setClass('text-sm text-danger font-normal'), $titleTips)),
    set::items($items),
    set::data(array_values($relations)),
    set::onRenderRow(jsRaw('renderRowData')),
    on::click('[data-name="pretask"]', 'loadTasks'),
    on::click('[data-name="task"]', 'loadTasks'),
    on::change('[name^=pretask], [name^=task]', 'changeTask')
);
