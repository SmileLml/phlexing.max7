<?php
namespace zin;
global $lang, $app;
$design = data('design');
$relateObject = tabPane
(
    set::title($lang->custom->relateObject),
    relatedObjectList
    (
        set::objectID($design->id),
        set::objectType('design'),
        set::relatedObjects($app->control->loadModel('custom')->getRelatedObjectList($design->id, 'design', 'byObject')),
        set::browseType('byObject')
    )
);
query('#detailTabs')->append($relateObject);
