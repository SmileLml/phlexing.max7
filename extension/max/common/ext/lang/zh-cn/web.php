<?php
$lang->required = '必填';

global $config;
/* Main Navigation. */
$lang->webMainNav = new stdclass();
$lang->webMainNav->my        = "{$lang->my->shortCommon}|my|index|";
if($config->systemMode == 'ALM') $lang->webMainNav->program = "{$lang->program->common}|program|product|";
$lang->webMainNav->product   = "{$lang->productCommon}|product|index|";
$lang->webMainNav->project   = "{$lang->project->common}|project|execution|";
$lang->webMainNav->execution = "{$lang->executionCommon}|execution|task|";
$lang->webMainNav->qa        = "{$lang->qa->common}|qa|index|";
$lang->webMainNav->company   = "{$lang->system->common}|company|index|";

$lang->webMenuOrder = array();
$lang->webMenuOrder[5]  = 'my';
if($config->systemMode == 'ALM') $lang->webMenuOrder[8] = 'program';
$lang->webMenuOrder[10] = 'product';
$lang->webMenuOrder[12] = 'project';
$lang->webMenuOrder[15] = 'execution';
$lang->webMenuOrder[20] = 'qa';
$lang->webMenuOrder[25] = 'company';

$lang->my->webMenu = new stdclass();
$lang->my->webMenu->index    = array('link' => "首页|my|index|");
$lang->my->webMenu->calendar = array('link' => "待办|my|calendar|", 'subModule' => 'todo', 'alias' => 'todo');
$lang->my->webMenu->effort   = array('link' => "日志|my|effort|", 'subModule' => 'effort');
$lang->my->webMenu->task     = array('link' => "任务|my|task|", 'subModule' => 'task');
$lang->my->webMenu->bug      = array('link' => "Bug|my|bug|", 'subModule' => 'bug');
$lang->my->webMenu->qa       = array('link' => "测试|my|testtask|", 'subModule' => 'testtask,testcase', 'alias' => 'testcase');
$lang->my->webMenu->story    = array('link' => "需求|my|story|", 'subModule' => 'story');
$lang->my->webMenu->dynamic  = array('link' => "动态|my|dynamic|");

$lang->todo->webMenu = $lang->my->webMenu;

$lang->my->webMenuOrder[5]  = 'index';
$lang->my->webMenuOrder[10] = 'calendar';
$lang->my->webMenuOrder[15] = 'effort';
$lang->my->webMenuOrder[20] = 'task';
$lang->my->webMenuOrder[25] = 'bug';
$lang->my->webMenuOrder[30] = 'qa';
$lang->my->webMenuOrder[35] = 'story';
$lang->my->webMenuOrder[40] = 'dynamic';

$lang->todo->webMenuOrder = $lang->my->webMenuOrder;

$lang->program->webMenu = new stdclass();
$lang->program->webMenu->product     = array('link' => "产品|program|product|programID=%s");
$lang->program->webMenu->project     = array('link' => "项目|program|project|programID=%s");
$lang->program->webMenu->team        = array('link' => "人员|personnel|invest|programID=%s", 'subModule' => 'personnel');
$lang->program->webMenu->stakeholder = array('link' => "干系人|program|stakeholder|programID=%s");

$lang->personnel->webMenu = $lang->program->webMenu;

$lang->program->webMenuOrder[5]  = 'product';
$lang->program->webMenuOrder[10] = 'project';
$lang->program->webMenuOrder[15] = 'team';
$lang->program->webMenuOrder[20] = 'stakeholder';

$lang->personnel->webMenuOrder   = $lang->program->webMenuOrder;

$lang->product->webMenu = new stdclass();
$lang->product->webMenu->story   = array('link' => "需求|product|browse|productID=%s", 'alias' => 'batchedit', 'subModule' => 'story');
$lang->product->webMenu->plan    = array('link' => '计划|productplan|browse|productID=%s', 'subModule' => 'productplan');
$lang->product->webMenu->release = array('link' => '发布|release|browse|productID=%s',     'subModule' => 'release');
$lang->product->webMenu->roadmap = '路线图|product|roadmap|productID=%s';
$lang->product->webMenu->project = '项目|product|project|status=all&productID=%s';
$lang->product->webMenu->dynamic = '动态|product|dynamic|productID=%s';
$lang->product->webMenu->view    = array('link' => '概况|product|view|productID=%s', 'alias' => 'edit');
$lang->product->webMenu->all     = array('link' => "所有{$lang->productCommon}|product|all|");

$lang->story->webMenu       = $lang->product->webMenu;
$lang->productplan->webMenu = $lang->product->webMenu;
$lang->release->webMenu     = $lang->product->webMenu;

$lang->product->webMenuOrder[5]  = 'story';
$lang->product->webMenuOrder[10] = 'plan';
$lang->product->webMenuOrder[15] = 'release';
$lang->product->webMenuOrder[20] = 'roadmap';
$lang->product->webMenuOrder[25] = 'project';
$lang->product->webMenuOrder[30] = 'dynamic';
$lang->product->webMenuOrder[35] = 'view';
$lang->product->webMenuOrder[40] = 'all';
$lang->story->webMenuOrder       = $lang->product->webMenuOrder;
$lang->productplan->webMenuOrder = $lang->product->webMenuOrder;
$lang->release->webMenuOrder     = $lang->product->webMenuOrder;

$lang->project->webMenu = new stdclass();
$lang->project->webMenu->execution = array('link' => "{$lang->executionCommon}|project|execution|status=all&projectID=%s");
$lang->project->webMenu->story     = array('link' => "{$lang->SRCommon}|projectstory|story|projectID=%s");
$lang->project->webMenu->bug       = array('link' => 'Bug|project|bug|projectID=%s');
$lang->project->webMenu->testcase  = array('link' => '用例|project|testcase|projectID=%s');
$lang->project->webMenu->testtask  = array('link' => "测试单|project|testtask|projectID=%s");
$lang->project->webMenu->build     = array('link' => "版本|project|build|projectID=%s");
$lang->project->webMenu->dynamic   = array('link' => '动态|project|dynamic|projectID=%s');
$lang->project->webMenu->view      = array('link' => '概况|project|view|projectID=%s');
$lang->project->webMenu->members   = array('link' => "团队|project|manageMembers|projectID=%s");
$lang->project->webMenu->whitelist = array('link' => "白名单|project|whitelist|projectID=%s");

$lang->projectstory->webMenu = $lang->project->webMenu;

$lang->project->webMenuOrder[5]   = 'execution';
$lang->project->webMenuOrder[10]  = 'story';
$lang->project->webMenuOrder[15]  = 'bug';
$lang->project->webMenuOrder[20]  = 'testcase';
$lang->project->webMenuOrder[25]  = 'testtask';
$lang->project->webMenuOrder[30]  = 'build';
$lang->project->webMenuOrder[35]  = 'dynamic';
$lang->project->webMenuOrder[40]  = 'view';
$lang->project->webMenuOrder[50]  = 'members';
$lang->project->webMenuOrder[55]  = 'whitelist';
$lang->projectstory->webMenuOrder = $lang->project->webMenuOrder;

$lang->execution->webMenu = new stdclass();
$lang->execution->webMenu->task     = array('link' => '任务|execution|task|executionID=%s', 'subModule' => 'task,tree', 'alias' => 'importtask,importbug');
$lang->execution->webMenu->story    = array('link' => "需求|execution|story|executionID=%s", 'subModule' => 'story', 'alias' => 'linkstory,storykanban');
$lang->execution->webMenu->bug      = 'Bug|execution|bug|executionID=%s';
$lang->execution->webMenu->build    = array('link' => '版本|execution|build|executionID=%s', 'subModule' => 'build');
$lang->execution->webMenu->testtask = array('link' => '测试单|execution|testtask|executionID=%s', 'subModule' => 'testreport,testtask');
$lang->execution->webMenu->team     = array('link' => '团队|execution|team|executionID=%s', 'alias' => 'managemembers');
$lang->execution->webMenu->action   = array('link' => '动态|execution|dynamic|executionID=%s', 'subModule' => 'dynamic');
$lang->execution->webMenu->view     = array('link' => '概况|execution|view|executionID=%s', 'alias' => 'edit,start,suspend,putoff,close');
$lang->execution->webMenu->all      = array('link' => "所有{$lang->executionCommon}|execution|all|");

$lang->task->webMenu  = $lang->execution->webMenu;
$lang->build->webMenu = $lang->execution->webMenu;

$lang->webMenuGroup['task']  = 'execution';
$lang->webMenuGroup['build'] = 'execution';

$lang->execution->webMenuOrder[5]  = 'task';
$lang->execution->webMenuOrder[10] = 'story';
$lang->execution->webMenuOrder[15] = 'build';
$lang->execution->webMenuOrder[20] = 'testtask';
$lang->execution->webMenuOrder[25] = 'team';
$lang->execution->webMenuOrder[30] = 'action';
$lang->execution->webMenuOrder[35] = 'view';
$lang->execution->webMenuOrder[40] = 'all';

$lang->task->webMenuOrder        = $lang->execution->webMenuOrder;
$lang->build->webMenuOrder       = $lang->execution->webMenuOrder;

$lang->qa->webMenu = new stdclass();
$lang->qa->webMenu->bug      = array('link' => 'Bug|bug|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,resolve,close,activate,report,batchedit,batchactivate,confirmbug,assignto');
$lang->qa->webMenu->testcase = array('link' => '用例|testcase|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,batchedit,showimport,groupcase,importfromlib,zerocase');
$lang->qa->webMenu->testtask = array('link' => '测试单|testtask|browse|productID=%s', 'alias' => 'view,create,edit,linkcase,cases,start,close,batchrun,groupcase,report');

$lang->bug->webMenu = new stdclass();
$lang->bug->webMenu->bug       = array('link' => 'Bug|bug|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,resolve,close,activate,report,batchedit,batchactivate,confirmbug,assignto', 'subModule' => 'tree');
$lang->bug->webMenu->testcase  = array('link' => '用例|testcase|browse|productID=%s');
$lang->bug->webMenu->testtask  = array('link' => '测试单|testtask|browse|productID=%s');

$lang->testcase->webMenu = new stdclass();
$lang->testcase->webMenu->bug      = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testcase->webMenu->testcase = array('link' => '用例|testcase|browse|productID=%s', 'alias' => 'view,create,batchcreate,edit,batchedit,showimport,groupcase,importfromlib', 'subModule' => 'tree,story', 'class' => 'dropdown dropdown-hover');
$lang->testcase->webMenu->testtask = array('link' => '测试单|testtask|browse|productID=%s');

$lang->testtask->webMenu = new stdclass();
$lang->testtask->webMenu->bug      = array('link' => 'Bug|bug|browse|productID=%s');
$lang->testtask->webMenu->testcase = array('link' => '用例|testcase|browse|productID=%s');
$lang->testtask->webMenu->testtask = array('link' => '测试单|testtask|browse|productID=%s', 'subModule' => 'testtask', 'alias' => 'view,create,edit,linkcase,cases,start,close,batchrun,groupcase,report');

$lang->qa->webMenuOrder[15]     = 'bug';
$lang->qa->webMenuOrder[20]     = 'testcase';
$lang->qa->webMenuOrder[25]     = 'testtask';
$lang->bug->webMenuOrder        = $lang->qa->webMenuOrder;
$lang->testcase->webMenuOrder   = $lang->qa->webMenuOrder;
$lang->testtask->webMenuOrder   = $lang->qa->webMenuOrder;

$lang->webMenuGroup['bug']      = 'qa';
$lang->webMenuGroup['testcase'] = 'qa';
$lang->webMenuGroup['testtask'] = 'qa';
$lang->webMenuGroup['user']     = 'company';

$lang->company->webMenu = new stdclass();
$lang->company->webMenu->browseUser = array('link' => '用户|company|browse|', 'subModule' => 'user');
$lang->company->webMenu->dynamic    = '动态|company|dynamic|';
$lang->company->webMenu->view       = array('link' => '公司|company|view|');

$lang->dept->webMenu  = $lang->company->webMenu;
$lang->group->webMenu = $lang->company->webMenu;
$lang->user->webMenu  = $lang->company->webMenu;

$lang->company->webMenuOrder[5]  = 'browseUser';
$lang->company->webMenuOrder[20] = 'dynamic';
$lang->company->webMenuOrder[25] = 'view';
$lang->dept->webMenuOrder        = $lang->company->webMenuOrder;
$lang->group->webMenuOrder       = $lang->company->webMenuOrder;
$lang->user->webMenuOrder        = $lang->company->webMenuOrder;
