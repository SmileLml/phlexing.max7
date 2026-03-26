<?php
namespace zin;
global $lang;

$fields = defineFieldList('opportunity.create');

$fields->field('name')
    ->width('full')
    ->control('input');

$fields->field('source')
    ->width('1/2')
    ->control('picker')
    ->items($lang->opportunity->sourceList);

$fields->field('execution')
    ->control('picker')
    ->wrapAfter()
    ->items(data('executions'))
    ->value(data('executionID'))
    ->hidden(!data('project.multiple'));

$fields->field('type')
    ->control('picker')
    ->items($lang->opportunity->typeList);

$fields->field('strategy')
    ->control('picker')
    ->items($lang->opportunity->strategyList);

$fields->field('impact')
    ->width('1/4')
    ->control(array('control' => 'picker', 'required' => true))
    ->items($lang->opportunity->impactList)
    ->value(3);

$fields->field('chance')
    ->width('1/4')
    ->control(array('control' => 'picker', 'required' => true))
    ->items($lang->opportunity->chanceList)
    ->value(3);

$fields->field('ratio')
    ->width('1/4')
    ->control('input')
    ->value(9)
    ->readonly(true);

$fields->field('pri')
    ->width('1/4')
    ->control(array('control' => 'priPicker', 'required' => true))
    ->items($lang->opportunity->priList)
    ->value('middle')
    ->readonly(true);

$fields->field('identifiedDate')
    ->width('1/4')
    ->control('datePicker');

$fields->field('plannedClosedDate')
    ->width('1/4')
    ->control('datePicker');

$fields->field('assignedTo')
    ->control('picker')
    ->items(data('users'));

$fields->field('desc')
    ->width('full')
    ->control('editor');

$fields->field('prevention')
    ->width('full')
    ->control('editor');
