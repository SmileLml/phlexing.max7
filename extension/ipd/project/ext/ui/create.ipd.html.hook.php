<?php
namespace zin;

global $lang;

jsVar('currentMethod', 'create');

$model         = data('model');
$copyProjectID = data('copyProjectID');

if(data('model') != 'kanban')
{
    query('formGridPanel')->each(function($node) use($lang)
    {
        $fields = $node->prop('fields');

        $fields->field('hasProduct')->hidden(data('model') == 'ipd');

        $fields->field('category')
            ->class('categoryBox')
            ->labelControl
            (
                setting()
                    ->control('radioList')
                    ->inline()
                    ->className('ml-1 hasProduct hidden')
                    ->addToList('items', array('name' => 'hasProduct', 'text' => $lang->project->projectTypeList[1], 'value' => '1'))
                    ->addToList('items', array('name' => 'hasProduct', 'text' => $lang->project->projectTypeList[0], 'value' => '0'))
            )
            ->control('picker')
            ->items($lang->project->categoryList)
            ->value('')
            ->width('1/4')
            ->moveAfter('charter');

        $fields->field('budget')
            ->foldable(false)
            ->moveAfter('workflowGroup');

        if(data('model') != 'ipd')
        {
            $fields->remove('category');
            $fields->orders('hasProduct,workflowGroup,budget');
        }
        else
        {
            $fields->moveAfter('workflowGroup', 'category');
            $fields->orders('category,workflowGroup,budget');
        }

        $hasProductValue = data('hasProduct') === null ? (data('copyProjectID') ? data('copyProject.hasProduct') : '1') : data('hasProduct');
        $fields->field('hasProduct')->value($hasProductValue)->width('1/4');

        $fields->field('workflowGroup')->width('1/4');

        $node->setProp('fields', $fields);
    });

    query('.categoryBox .picker-box')->on('change', jsCallback()->call('changeCategory'));
}
