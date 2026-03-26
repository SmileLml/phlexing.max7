<?php
namespace zin;

jsVar('model', $model);
jsVar('ignore', $lang->project->ignore);
jsVar('budgetOverrun', $lang->project->budgetOverrun);
jsVar('currencySymbol', $lang->project->currencySymbol);
jsVar('parentBudget', $lang->project->parentBudget);
jsVar('budgetUnitLabel', $lang->project->tenThousandYuan);
jsVar('+projectID', 0);
jsVar('LONG_TIME', LONG_TIME);
jsVar('weekend', $config->execution->weekend);
jsVar('beginLessThanParent', $lang->project->beginLessThanParent);
jsVar('endGreatThanParent', $lang->project->endGreatThanParent);

$projectsBox = array();
if(!empty($projects))
{
    $count = 1;
    foreach($projects as $projectID => $project)
    {
        if($count > 10) break;
        $projectsBox[] = btn
            (
                setClass('project-block replace-item justify-start'),
                setClass($count == 1 ? 'primary-outline' : ''),
                setData('id', $projectID),
                setData('multiple', $project->multiple),
                setData('title', $project->name),
                icon('project'),
                span(setClass('text-left'), $project->name)
            );

        $count ++;
    }
}

render();
