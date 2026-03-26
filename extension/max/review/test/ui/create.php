<?php
#!/usr/bin/env php

include '../lib/create.ui.class.php';

$project = zenData('project');
$project->type->range('project');
$project->model->range('waterfall');
$project->path->range(',1,');
$project->grade->range('1');
$project->name->range('项目一');
$project->code->range('项目一');
$project->begin->range('2024\-01\-01');
$project->end->range('2024\-12\-31');
$project->gen(1);

zenData('user')->loadYaml('user')->gen(1);
zenData('approvalflowspec')->loadYaml('approvalflowspec')->gen(1);

$tester = new createTester();
$tester->login();

$tester->closeBrowser();
