<?php
namespace zin;
global $app, $config, $lang;

$fields      = defineFieldList('meeting');
$project     = data('project');
$isWaterfall = $app->tab == 'project' && !empty($project->model) && $project->model == 'waterfall';

$fields->field('room')
    ->label($lang->meeting->room)
    ->items(data('rooms'))
    ->value(data('meeting.room') ? data('roomID') : data('roomID'));

$fields->field('datePlan')
    ->lable($lang->meeting->datePlan)
    ->control('inputGroup')
    ->itemBegin('begin')->control('datetimePicker')->placeholder($lang->meeting->begin)->value(data('meeting.date') . ' ' . data('meeting.begin'))->itemEnd()
    ->item(array('control' => 'span', 'text' => '-'))
    ->itemBegin('end')->control('timePicker')->placeholder($lang->meeting->end)->value(data('meeting.date') . ' ' . data('meeting.end'))->itemEnd();

$fields->field('name')
    ->label($lang->meeting->name)
    ->required(true)
    ->value(data('meeting.name'));

$fields->field('mode')
    ->width($isWaterfall ? '1/2' : '1/4')
    ->label($lang->meeting->mode)
    ->required(true)
    ->control(array('control' => 'picker', 'required' => false))
    ->items($lang->meeting->modeList)
    ->value(data('meeting.mode'));

$fields->field('host')
    ->width($isWaterfall ? '1/2' : '1/4')
    ->label($lang->meeting->host)
    ->items(data('users'))
    ->value(data('meeting.host') ? data('meeting.host') : $app->user->account);

if($isWaterfall)
{
    $fields->field('type')
        ->label($lang->meeting->type)
        ->items(data('typeList'))
        ->value(data('meeting.type'));
}

$fields->field('objectType')
    ->label($lang->meeting->objectType)
    ->items($config->meeting->objectTypeList)
    ->value(data('objectType'));

$fields->field('objectID')
    ->label($lang->meeting->objectID)
    ->items(data('objects') ? data('objects') : array())
    ->value(data('meeting.objectID') ? data('meeting.objectID') : data('objectID'));

$fields->field('participant')
    ->width('full')
    ->label($lang->meeting->participant)
    ->control('mailto')
    ->items(data('users'))
    ->value(data('meeting.participant'));

$fields->field('files')
    ->width('full')
    ->foldable()
    ->label($lang->meeting->files)
    ->control('fileSelector', array('defaultFiles' => data('meeting.files')));

$fields->field('project')
    ->foldable()
    ->label($lang->meeting->project)
    ->items(data('projects'))
    ->value(data('projectID'));

$fields->field('execution')
    ->foldable()
    ->label($lang->meeting->execution)
    ->items(data('executions'))
    ->value(data('executionID'));

$fields->field('dept')
    ->foldable()
    ->label($lang->meeting->dept)
    ->items(data('depts'))
    ->value(data('meeting.dept'));
