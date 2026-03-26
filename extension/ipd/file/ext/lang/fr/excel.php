<?php
$lang->file->onlySupportXLSX = 'Only xlsx format import is supported. Please convert xlsx format and import again.';

$lang->excel->fileField = 'File';

$lang->excel->title              = new stdclass();
$lang->excel->title->testcase    = 'Case';
$lang->excel->title->bug         = 'Bug';
$lang->excel->title->task        = 'Task';
$lang->excel->title->story       = 'Story';
$lang->excel->title->epic        = 'Epic';
$lang->excel->title->requirement = 'Requirement';
$lang->excel->title->caselib     = 'Library';
$lang->excel->title->sysValue    = 'System';
$lang->excel->title->tree        = 'Tree';
$lang->excel->title->user        = 'User';
$lang->excel->title->project     = 'Project';

$lang->excel->error = new stdclass();
$lang->excel->error->info       = 'The value you entered is not in the dropdown.';
$lang->excel->error->title      = 'Input Error';
$lang->excel->error->noFile     = 'No File.';
$lang->excel->error->noData     = 'No Data.';
$lang->excel->error->canNotRead = 'It cannot parse this file.';

$lang->excel->help              = new stdclass();
$lang->excel->help->testcase    = "Use + '.' to mark case steps in a new line. Use + '.' for expectations corresponded to each steps. %s are required. If left empty, data in the same row will be ommitted. ";
$lang->excel->help->caselib     = "Use + '.' to mark case steps in a new line. Use + '.' for expectations corresponded to each steps. name are required. If left empty, data in the same row will be ommitted. ";
$lang->excel->help->bug         = "When adding bugs, %s are required. If left empty, data in the same row will be ommitted.";
$lang->excel->help->task        = "When adding a task, %s are required fields. If not filled in, the data will be ignored when importing;\nIf you need to import parent and child tasks, you need to fill in the level column, the level format: x|x.y|x.y.z. For example: 1 is The parent task of 1.1, 1.1 is the parent task of 1.1.1,the level must be unique. \nIf you need to add a multi-person task, please add it in the \"Initial Expected\" column in the format of \"Username: {$lang->hourCommon}\", and separate multiple users with newlines. The user name is viewed in column G of the \"System Data\" worksheet. \nPlease fill in \"Mode\" for multiplayer tasks. Fill in \"Mode\" for non-multiplayer tasks. When importing, the system will automatically leave \"Mode\" blank. \nSubtasks can no longer be split in multiplayer tasks.";
$lang->excel->help->story       = "When adding stories, %s are required. If left empty, data in the same row will be ommitted. \nIf you need to import parent and child stories, you need to fill in the level column, the level format: x|x.y|x.y.z. For example: 1 is The parent story of 1.1, 1.1 is the parent story of 1.1.1, the level must be unique.";
$lang->excel->help->epic        = $lang->excel->help->story;
$lang->excel->help->requirement = $lang->excel->help->story;
$lang->excel->help->user        = "Account and name is required. If left empty, data in the same row will be ommitted.";
$lang->excel->help->feedback    = "When adding feedbacks, %s are required. If left empty, data in the same row will be ommitted.";
$lang->excel->help->ticket      = "When adding tickets, title,{$lang->productCommon},module are required. If left empty, data in the same row will be ommitted.";
$lang->excel->help->demand      = "When adding stories, %s are required. If left empty, data in the same row will be ommitted.";
