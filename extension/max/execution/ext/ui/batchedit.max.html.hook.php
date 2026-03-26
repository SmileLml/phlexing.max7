<?php
namespace zin;

if(helper::hasFeature('deliverable'))
{
    global $lang, $app;

    $app->control->loadModel('project');

    $executions = data('executions');
    foreach($executions as $execution)
    {
        if($execution->status == 'closed' && !empty($execution->deliverable) && $app->control->project->checkUploadedDeliverable($execution)) $execution->hasDeliverable = true;
    }

    query('formBatchPanel')->each(function($node) use ($executions)
    {
        $data = array_values($executions);

        $node->setProp('data', $data);
    });
}
