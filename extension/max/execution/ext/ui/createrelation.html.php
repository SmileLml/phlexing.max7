<?php
namespace zin;

jsVar('executionID', $executionID);
jsVar('projectID', $projectID);

$items = array();

$items[] = array
(
    'name'    => 'id',
    'label'   => $lang->execution->gantt->id,
    'control' => 'index',
    'width'   => '40px'
);

$items[] = array
(
    'name'     => 'pretask',
    'label'    => $lang->execution->gantt->pretask,
    'width'    => 'auto',
    'control'  => 'picker',
    'items'    => array(),
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
    'control'  => 'picker',
    'items'    => array(),
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
    set::title($lang->execution->createRelation),
    set::items($items),
    on::click('[data-name="pretask"]', 'loadTasks'),
    on::click('[data-name="task"]', 'loadTasks')
);
