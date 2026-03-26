<?php
namespace zin;
global $app;
$task = data('task');
$relatedObjects = $app->control->loadModel('custom')->getRelatedObjectList($task->id, 'task', 'byObject');
query('detail')->each(function($node) use($task, $relatedObjects)
{
    global $lang;
    $tabs = $node->prop('tabs');
    $tabs['taskMiscInfo'] = setting()
        ->group('related')
        ->title($lang->custom->relateObject)
        ->control('relatedObjectList')
        ->objectID($task->id)
        ->objectType('task')
        ->relatedObjects($relatedObjects)
        ->browseType('byObject');

    $node->setProp('tabs', $tabs);
});
