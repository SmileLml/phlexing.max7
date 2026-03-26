<?php
namespace zin;
global $app;
$bug = data('bug');
$relatedObjects = $app->control->loadModel('custom')->getRelatedObjectList($bug->id, 'bug', 'byObject');
query('detail')->each(function($node) use($bug, $relatedObjects)
{
    global $lang;
    $tabs = $node->prop('tabs');
    $tabs['bugRelatedList'] = setting()
        ->group('related')
        ->title($lang->custom->relateObject)
        ->control('relatedObjectList')
        ->objectID($bug->id)
        ->objectType('bug')
        ->relatedObjects($relatedObjects)
        ->browseType('byObject');

    $node->setProp('tabs', $tabs);
});
