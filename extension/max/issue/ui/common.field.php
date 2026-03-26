<?php
namespace zin;
global $lang;

$fields = defineFieldList('issue');

$fields->field('title')
    ->width('1/2')
    ->control('input')
    ->value(data('issue.title'));

$fields->field('type')
    ->control('picker')
    ->items($lang->issue->typeList)
    ->value(data('issue.type'));

$fields->field('execution')
    ->control('picker')
    ->items(data('executions'))
    ->value(data('issue.execution'))
    ->hidden(!data('project.multiple'));

$fields->field('severity')
    ->width('1/4')
    ->control('severityPicker', array('required' => true))
    ->items($lang->issue->severityList)
    ->value(data('issue.severity'));

$fields->field('pri')
    ->width('1/4')
    ->control('priPicker', array('required' => true))
    ->items($lang->issue->priList)
    ->value(data('issue.pri'));

$fields->field('assignedTo')
    ->width('1/4')
    ->control('picker')
    ->items(data('teamMembers'))
    ->value(data('issue.assignedTo'));

$fields->field('owner')
    ->width('1/4')
    ->control('picker')
    ->items(data('teamMembers'))
    ->value(data('issue.owner'));

$fields->field('deadline')
    ->width('1/2')
    ->control('datePicker')
    ->value(data('issue.deadline'));

$fields->field('desc')
    ->width('full')
    ->control('editor')
    ->value(data('issue.desc'));

$fields->field('files')
    ->width('full')
    ->control('fileSelector');
