<?php
namespace zin;

global $lang;

jsVar('currentMethod', 'edit');

$projectID = data('projectID');
$from      = data('from');
$programID = data('programID');
query('formGridPanel')->each(function($node) use($lang)
{
    $fields = $node->prop('fields');

    $fields->field('hasProduct')->hidden(data('model') == 'ipd');

    $fields->field('category')
        ->class('categoryBox')
        ->control('picker')
        ->items($lang->project->categoryList)
        ->value(data('project.category'))
        ->moveAfter('charter');

    $fields->field('budget')
        ->foldable(false)
        ->moveAfter('category');

    if(data('model') != 'ipd')
    {
        $fields->remove('category');
        $fields->orders('hasProduct,workflowGroup,budget');
    }
    else
    {
        $fields->field('category')->width('1/4');
        $fields->moveAfter('workflowGroup', 'category');
        $fields->orders('category,workflowGroup,budget');
    }

    $node->setProp('fields', $fields);
});

query('.categoryBox .picker-box')->on('change', jsCallback()->call('changeCategory'));
