<?php
namespace zin;

if(helper::hasFeature('deliverable'))
{
    global $lang;
    $project      = data('project');
    $execution    = data('execution');
    $deliverables = data('deliverables');
    if($project->model != 'ipd' && $project->model != 'kanban' && $execution->status == 'doing' && $execution->grade == 1)
    {
        /* 追加交付物组件。 */
        $deliverable = formGroup
        (
            set::label($lang->project->deliverableAbbr),
            deliverable(set::items($deliverables))
        );
        query('formPanel')->append($deliverable);
    }
}
