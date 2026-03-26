<?php
namespace zin;

global $app;
$project        = data('project');
$disableModel   = data('disableModel');
$workflowGroups = $app->control->loadModel('workflowgroup')->getPairs('project', $project->model, $project->hasProduct, 'normal', '0');
$workflowGroup  = $project->workflowGroup;
if(!isset($workflowGroups[$workflowGroup]) && $workflowGroup)
{
    $group = $app->control->workflowgroup->getByID($workflowGroup);
    if($group) $workflowGroups[$workflowGroup] = $group->name;
}

query('formGridPanel')->each(function($node) use($workflowGroups, $workflowGroup, $disableModel)
{
    $lang   = data('lang');
    $model  = data('model');
    $fields = $node->prop('fields');

    $fields->field('workflowGroup')
           ->label($lang->project->workflowGroup)
           ->control('picker')
           ->required(true)
           ->disabled($disableModel)
           ->value($workflowGroup)
           ->items($workflowGroups)
           ->width('1/4')
           ->moveAfter('hasProduct');

    if($model == 'kanban') $fields->field('workflowGroup')->className('hidden');
    if($model != 'kanban') $fields->field('hasProduct')->width('1/4');

    $fields->fullModeOrders('charter,category,hasProduct,workflowGroup,budget');
    $fields->orders('charter,category,hasProduct,workflowGroup,budget');

    $node->setProp('fields', $fields);
});

if(!$disableModel)
{
    jsVar('hasProduct', $project->hasProduct);
    pageJS(<<<'JAVASCRIPT'
$(document).off('click', '.model-drop').on('click', '.model-drop', function()
{
    let model = $(this).find('.listitem').attr('data-key');
    $.getJSON($.createLink('workflowgroup', 'ajaxGetWorkflowGroups', 'type=project&model=' + model + '&hasProduct=' + hasProduct + '&status=normal&exclusive=0'), function(data)
    {
        let $picker = $('[name=workflowGroup]').zui('picker');
        $picker.render({items: data});
        $picker.$.setValue('');

        if(model == 'kanban')
        {
            $('.form-group[data-name=workflowGroup]').addClass('hidden');
            $('.form-group[data-name=hasProduct]').removeClass('w-1/4').addClass('w-1/2');
        }
        else
        {
            $('.form-group[data-name=workflowGroup]').removeClass('hidden');
            $('.form-group[data-name=hasProduct]').removeClass('w-1/2').addClass('w-1/4');
        }
    })
});
JAVASCRIPT
    );
}
