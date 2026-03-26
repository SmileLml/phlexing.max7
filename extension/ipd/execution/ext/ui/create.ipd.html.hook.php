<?php
namespace zin;

$projectModel = data('project.model');

if($projectModel == 'ipd')
{
    global $lang, $app;

    $projectID = data('projectID');

    $parents        = $app->control->loadModel('execution')->getPairs($projectID, 'stage', 'all');
    $executionTasks = $app->control->execution->getTaskGroupByExecution(array_keys($parents));

    foreach($executionTasks as $executionID => $tasks)
    {
        if(isset($parents[$executionID])) unset($parents[$executionID]);
    }

    query('formGridPanel')->each(function($node) use($lang, $parents)
    {
        $fields = $node->prop('fields');

        $fields->field('parent')
            ->label($lang->execution->parentStage)
            ->control('picker')
            ->items($parents)
            ->value(data('parentStage'))
            ->wrapBefore()
            ->required(true)
            ->moveAfter('project');

        $node->setProp('fields', $fields);
    });
}
