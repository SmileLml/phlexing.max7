<?php
namespace zin;
global $app;
$story = data('story');
$relatedObjects = $app->control->loadModel('custom')->getRelatedObjectList($story->id, $story->type, 'byObject');
query('detail')->each(function($node) use($story, $relatedObjects)
{
    global $lang;
    $tabs = $node->prop('tabs');
    $tabs['linkStories'] = setting()
        ->group('relatives')
        ->title($lang->custom->relateObject)
        ->control('relatedObjectList')
        ->objectID($story->id)
        ->objectType($story->type)
        ->relatedObjects($relatedObjects)
        ->browseType('byObject');
    unset($tabs['storyRelatedList']);

    $node->setProp('tabs', $tabs);
});
