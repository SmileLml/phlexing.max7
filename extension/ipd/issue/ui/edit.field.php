<?php
namespace zin;

$fields = defineFieldList('issue.edit', 'issue');

$fields->field('project')
    ->width('1/4')
    ->control('picker', array('required' => true))
    ->items(data('projectList'))
    ->value(data('issue.project'));

$fields->field('files')
    ->width('full')
    ->control('fileSelector', data('issue.files') ? array('defaultFiles' => array_values(data('issue.files'))) : array());

$fields->field('execution')->width('1/4');
