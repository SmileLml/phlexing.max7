<?php
namespace zin;
global $lang,$config;

$fields = defineFieldList('researchtask.create');

$fields->field('execution')
    ->control('picker')
    ->required(true)
    ->items(data('stages'))
    ->value(data('stageID'));

$fields->field('assignedTo')
    ->control(array('control' => 'picker', 'name' => 'assignedTo[]'))
    ->items(data('users'));

$fields->field('datePlan')
    ->control('inputGroup')
    ->lable($lang->task->datePlan)
    ->itemBegin('estStarted')->control('datePicker')->placeholder($lang->task->estStarted)->value(data('task.estStarted'))->itemEnd()
    ->item(array('control' => 'span', 'text' => '-'))
    ->itemBegin('deadline')->control('datePicker')->placeholder($lang->task->deadline)->value(data('task.deadline'))->itemEnd();

$fields->field('pri')
    ->label($lang->task->pri)
    ->width('1/4')
    ->control('priPicker')
    ->items($lang->task->priList)
    ->value(data('task.pri'));

$fields->field('estimate')
    ->control('input')
    ->label($lang->task->estimateLabel)
    ->width('1/4');

$fields->field('name')
    ->control('colorInput', array('colorValue' => data('task.color')))
    ->required(true)
    ->value(data('task.name'));

$fields->field('desc')
    ->width('full')
    ->control(array('control' => 'editor', 'templateType' => 'task'));

$fields->field('after')
    ->label($lang->task->afterSubmit)
    ->width('full')
    ->control(array('control' => 'radioList', 'inline' => true))
    ->value(data('task.id') ? 'continueAdding' : 'toTaskList')
    ->items($lang->researchtask->afterChoices);

$fields->field('files')
    ->width('full')
    ->value('')
    ->control('fileSelector');

$fields->field('mailto')
    ->control('mailto')
    ->multiple(true)
    ->foldable()
    ->items(data('users'));

$fields->field('type')->value('research')->hidden(true);
