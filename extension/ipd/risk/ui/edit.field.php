<?php
namespace zin;
global $lang;

$fields = defineFieldList('risk.edit');

$fields->field('name')
    ->width('full')
    ->control('input')
    ->required()
    ->value(data('risk.name'));

$fields->field('source')
    ->control('picker')
    ->items($lang->risk->sourceList)
    ->value(data('risk.source'));

$fields->field('project')
    ->width('1/4')
    ->control('picker')
    ->items(data('projectList'))
    ->value(data('risk.project'));

$fields->field('execution')
    ->width('1/4')
    ->control('picker')
    ->items(data('executions'))
    ->value(data('risk.execution'))
    ->hidden(!data('project.multiple'));

$fields->field('category')
    ->control('picker')
    ->items($lang->risk->categoryList)
    ->value(data('risk.category'));

$fields->field('strategy')
    ->control('picker')
    ->items($lang->risk->strategyList)
    ->value(data('risk.strategy'));

$fields->field('impact')
    ->width('1/4')
    ->control('picker')
    ->items($lang->risk->impactList)
    ->required()
    ->value(data('risk.impact'));

$fields->field('probability')
    ->width('1/4')
    ->control('picker')
    ->items($lang->risk->probabilityList)
    ->required()
    ->value(data('risk.probability'));

$fields->field('rate')
    ->width('1/4')
    ->control('input')
    ->disabled(true)
    ->value(data('risk.rate'));

$fields->field('pri')
    ->width('1/4')
    ->control('priPicker')
    ->items($lang->risk->priList)
    ->required()
    ->readonly(true)
    ->value(data('risk.pri'));

$fields->field('identifiedDate')
    ->control('datePicker')
    ->value(data('risk.identifiedDate'));

$fields->field('plannedClosedDate')
    ->control('datePicker')
    ->value(data('risk.plannedClosedDate'));

$fields->field('actualClosedDate')
    ->control('datePicker')
    ->value(data('risk.actualClosedDate'));

$fields->field('resolvedBy')
    ->width('1/4')
    ->control('picker')
    ->items(data('users'))
    ->value(data('risk.resolvedBy'));

$fields->field('assignedTo')
    ->width('1/4')
    ->control('picker')
    ->items(data('users'))
    ->value(data('risk.assignedTo'));

$fields->field('prevention')
    ->width('full')
    ->control('editor');

$fields->field('remedy')
    ->width('full')
    ->control('editor');

$fields->field('resolution')
    ->width('full')
    ->control('editor');
