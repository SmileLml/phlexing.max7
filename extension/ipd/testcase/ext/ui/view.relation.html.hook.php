<?php
namespace zin;
global $app;
$case = data('case');
$relatedObjects = $app->control->loadModel('custom')->getRelatedObjectList($case->id, 'testcase', 'byObject');
query('detail')->each(function($node) use($case, $relatedObjects)
{
    global $lang;
    $tabs = $node->prop('tabs');
    $tabs['caseRelatedList'] = setting()
        ->group('relatives')
        ->title($lang->custom->relateObject)
        ->control('relatedObjectList')
        ->objectID($case->id)
        ->objectType('testcase')
        ->relatedObjects($relatedObjects)
        ->browseType('byObject');

    $node->setProp('tabs', $tabs);
});
