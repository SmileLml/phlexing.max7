<?php
namespace zin;

if(helper::hasFeature('deliverable'))
{
    global $lang, $app;
    $execution      = data('execution');
    $hasDeliverable = false;
    if($execution->status == 'closed' && !empty($execution->deliverable) && $app->control->loadModel('project')->checkUploadedDeliverable($execution)) $hasDeliverable = true;
    query('formGridPanel')->each(function($node) use ($hasDeliverable)
    {
        $fields = $node->prop('fields');

        $fields->field('attribute')->disabled($hasDeliverable);
        $fields->field('lifetime')->disabled($hasDeliverable);

        $node->setProp('fields', $fields);
    });
}
