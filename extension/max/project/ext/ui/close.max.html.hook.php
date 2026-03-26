<?php
namespace zin;
global $lang;

$project      = data('project');
$deliverables = data('deliverables');
if(helper::hasFeature('deliverable') && $project->model != 'ipd' && $project->model != 'kanban' && $project->status == 'doing')
{
    /* 追加交付物组件。 */
    $deliverable = formGroup
    (
        set::label($lang->project->deliverableAbbr),
        deliverable(set::items($deliverables))
    );
    query('formPanel')->append($deliverable);
}
