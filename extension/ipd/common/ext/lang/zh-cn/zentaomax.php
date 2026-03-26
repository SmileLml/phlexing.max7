<?php
$lang->maxName     = '旗舰版';
$lang->userCenter  = '个人中心';
$lang->importIcon  = "<i class='icon-import'> </i>";
$lang->dragAndSort = "拖动排序";
$lang->importToLib = "导入资产库";
$lang->scurmModel  = '敏捷模型';

$lang->navIcons['assetlib'] = "<i class='icon icon-assets'></i>";

$lang->navIconNames['assetlib'] = 'assets';

$lang->risk               = new stdclass();
$lang->issue              = new stdclass();
$lang->weekly             = new stdclass();
$lang->measrecord         = new stdclass();
$lang->opportunity        = new stdclass();
$lang->assetlib           = new stdclass();
$lang->meeting            = new stdclass();
$lang->approvalflow       = new stdclass();
$lang->process            = new stdclass();
$lang->cmcl               = new stdclass();
$lang->reviewcl           = new stdclass();
$lang->auditcl            = new stdclass();
$lang->reviewissue        = new stdclass();
$lang->researchplan       = new stdclass();
$lang->gapanalysis        = new stdclass();
$lang->trainplan          = new stdclass();
$lang->researchreport     = new stdclass();
$lang->reviewissue        = new stdclass();
$lang->storylib           = new stdclass();
$lang->issuelib           = new stdclass();
$lang->risklib            = new stdclass();
$lang->opportunitylib     = new stdclass();
$lang->practicelib        = new stdclass();
$lang->componentlib       = new stdclass();
$lang->projectreport      = new stdclass();
$lang->projectresearch    = new stdclass();
$lang->projectauditplan   = new stdclass();
$lang->projectgapanalysis = new stdclass();

$lang->report->common             = '度量报表';
$lang->issue->common              = '问题';
$lang->risk->common               = '风险';
$lang->opportunity->common        = '机会';
$lang->assetlib->common           = '资产库';
$lang->meeting->common            = '会议';
$lang->process->common            = '过程';
$lang->cmcl->common               = '审计';
$lang->reviewcl->common           = '评审';
$lang->auditcl->common            = 'QA检查项';
$lang->storylib->common           = '需求库';
$lang->issuelib->common           = '问题库';
$lang->risklib->common            = '风险库';
$lang->opportunitylib->common     = '机会库';
$lang->practicelib->common        = '最佳实践库';
$lang->componentlib->common       = '组件库';
$lang->projectreport->common      = '报告';
$lang->projectresearch->common    = '调研';
$lang->projectauditplan->common   = 'QA';
$lang->projectgapanalysis->common = '培训';
$lang->reviewcl->clcategory       = '检查单分类';

$lang->mainNav->assetlib      = "{$lang->navIcons['assetlib']} {$lang->assetlib->common}|assetlib|storylib|";
$lang->mainNav->menuOrder[57] = 'assetlib';

$lang->navGroup->assetlib       = 'assetlib';
$lang->navGroup->storylib       = 'assetlib';
$lang->navGroup->caselib        = 'assetlib';
$lang->navGroup->issuelib       = 'assetlib';
$lang->navGroup->risklib        = 'assetlib';
$lang->navGroup->opportunitylib = 'assetlib';
$lang->navGroup->practicelib    = 'assetlib';
$lang->navGroup->componentlib   = 'assetlib';

$lang->navGroup->issue              = 'project';
$lang->navGroup->risk               = 'project';
$lang->navGroup->weekly             = 'project';
$lang->navGroup->budget             = 'project';
$lang->navGroup->workestimation     = 'project';
$lang->navGroup->durationestimation = 'project';
$lang->navGroup->opportunity        = 'project';
$lang->navGroup->trainplan          = 'project';
$lang->navGroup->gapanalysis        = 'project';
$lang->navGroup->researchplan       = 'project';
$lang->navGroup->researchreport     = 'project';
$lang->navGroup->meeting            = 'project';
$lang->navGroup->reviewissue        = 'project';
$lang->navGroup->projectreport      = 'project';
$lang->navGroup->projectresearch    = 'project';
$lang->navGroup->projectauditplan   = 'project';
$lang->navGroup->projectgapanalysis = 'project';

$lang->navGroup->holiday       = 'admin';
$lang->navGroup->stage         = 'admin';
$lang->navGroup->measurement   = 'admin';
$lang->navGroup->sqlbuilder    = 'admin';
$lang->navGroup->auditcl       = 'admin';
$lang->navGroup->cmcl          = 'admin';
$lang->navGroup->process       = 'admin';
$lang->navGroup->activity      = 'admin';
$lang->navGroup->zoutput       = 'admin';
$lang->navGroup->classify      = 'admin';
$lang->navGroup->subject       = 'admin';
$lang->navGroup->baseline      = 'admin';
$lang->navGroup->auditcl       = 'admin';
$lang->navGroup->reviewcl      = 'admin';
$lang->navGroup->reviewsetting = 'admin';
$lang->navGroup->meetingroom   = 'admin';
$lang->navGroup->deliverable   = 'admin';

$lang->navGroup->approvalflow  = 'workflow';

$lang->my->icon['my']      = 'icon-menu-my';
$lang->my->icon['program'] = 'icon-menu-project';
$lang->my->icon['system']  = 'icon-cube';
$lang->my->icon['attend']  = 'icon-file';
$lang->my->icon['report']  = 'icon-menu-report';
$lang->my->icon['admin']   = 'icon-menu-backend';

$lang->my->menu->meeting = array('link' => "会议|my|meeting|", 'subModule' => 'meeting');
$lang->my->menuOrder[41] = 'meeting';

$lang->my->menu->work['subMenu']->issue     = '问题|my|work|mode=issue';
$lang->my->menu->work['subMenu']->risk      = '风险|my|work|mode=risk';
$lang->my->menu->work['subMenu']->nc        = array('link' => 'QA|my|work|mode=auditplan&type=mychecking', 'alias' => 'auditplan');
$lang->my->menu->work['subMenu']->myMeeting = '会议|my|work|mode=mymeeting&type=futureMeeting';

$lang->my->menu->work['menuOrder'][40] = 'issue';
$lang->my->menu->work['menuOrder'][45] = 'risk';
$lang->my->menu->work['menuOrder'][50] = 'nc';
$lang->my->menu->work['menuOrder'][55] = 'myMeeting';

$lang->my->menu->contribute['subMenu']->myAudit  = array('link' => '审计|my|contribute|mode=myAudit&type=auditByMe', 'alias' => 'myaudit');
$lang->my->menu->contribute['subMenu']->baseline = array('link' => '基线|my|contribute|mode=baseline&type=createdBy', 'alias' => 'baseline');
$lang->my->menu->contribute['subMenu']->issue    = array('link' => '问题|my|contribute|mode=issue', 'alias' => 'issue');
$lang->my->menu->contribute['subMenu']->risk     = array('link' => '风险|my|contribute|mode=risk', 'alias' => 'risk');
$lang->my->menu->contribute['subMenu']->nc       = array('link' => 'QA|my|contribute|mode=nc&type=createdByMe', 'alias' => 'nc');

$lang->my->menu->contribute['menuOrder'][35] = 'audit';
$lang->my->menu->contribute['menuOrder'][40] = 'myAudit';
$lang->my->menu->contribute['menuOrder'][45] = 'baseline';
$lang->my->menu->contribute['menuOrder'][50] = 'doc';
$lang->my->menu->contribute['menuOrder'][55] = 'issue';
$lang->my->menu->contribute['menuOrder'][60] = 'risk';
$lang->my->menu->contribute['menuOrder'][65] = 'nc';
$lang->my->menu->contribute['menuOrder'][70] = 'feedback';
$lang->my->menu->contribute['menuOrder'][75] = 'ticket';

$lang->report->projectMenu = new stdclass();
$lang->report->projectMenu->reports     = array('link' => '统计报表|report|projectsummary|project=%s', 'alias' => 'projectworkload,reportmodule,customeredreport,custom,show,viewreport');
$lang->report->projectMenu->measurement = array('link' => '度量列表|measrecord|browse|project=%s');

$lang->project->homeMenu->browse['alias'] .= ',copyproject,copyconfirm';
$lang->project->homeMenu->template         = array('link' => "项目模板|project|template|", 'alias' => 'createtemplate');

$lang->scrum->menu->other   = array('link' => "$lang->other|project|other|project=%s", 'class' => 'dropdown dropdown-hover');
$lang->scrum->menuOrder[50] = 'other';

$lang->scrum->menu->other['dropMenu'] = new stdclass();
$lang->scrum->menu->other['dropMenu']->deliverable = array('link' => "{$lang->deliverable->common}|project|deliverable|projectID=%s");
$lang->scrum->menu->other['dropMenu']->issue       = array('link' => '问题|issue|browse|projectID=%s', 'subModule' => 'issue');
$lang->scrum->menu->other['dropMenu']->risk        = array('link' => '风险|risk|browse|projectID=%s', 'subModule' => 'risk');
$lang->scrum->menu->other['dropMenu']->opportunity = array('link' => "机会|opportunity|browse|projectID=%s", 'subModule' => 'opportunity');
$lang->scrum->menu->other['dropMenu']->meeting     = array('link' => '会议|meeting|browse|projectID=%s', 'subModule' => 'meeting');
$lang->scrum->menu->other['dropMenu']->report      = array('link' => '度量|report|projectsummary|projectID=%s', 'subModule' => 'measrecord,report');

$lang->scrum->menu->report['subMenu'] = new stdclass();
$lang->scrum->menu->report['subMenu']->summary    = array('link' => '统计报表|report|projectsummary|projectID=%s', 'alias' => 'projectworkload,show,customeredreport,viewreport');
$lang->scrum->menu->report['subMenu']->measrecord = array('link' => '度量列表|measrecord|browse|projectID=%s');

/* No sprint menu. */
$lang->project->noMultiple->scrum->menu->other   = array('link' => "$lang->other|issue|browse|project=%s", 'class' => 'dropdown dropdown-hover');
$lang->project->noMultiple->scrum->menuOrder[55] = 'other';

$lang->project->noMultiple->scrum->menu->other['dropMenu'] = new stdclass();
$lang->project->noMultiple->scrum->menu->other['dropMenu']->deliverable = array('link' => "{$lang->deliverable->common}|project|deliverable|projectID=%s");
$lang->project->noMultiple->scrum->menu->other['dropMenu']->issue       = array('link' => '问题|issue|browse|projectID=%s', 'subModule' => 'issue');
$lang->project->noMultiple->scrum->menu->other['dropMenu']->risk        = array('link' => '风险|risk|browse|projectID=%s', 'subModule' => 'risk');
$lang->project->noMultiple->scrum->menu->other['dropMenu']->opportunity = array('link' => "机会|opportunity|browse|projectID=%s", 'subModule' => 'opportunity');
$lang->project->noMultiple->scrum->menu->other['dropMenu']->pssp        = array('link' => '过程|pssp|browse|projectID=%s&from=project', 'subModule' => 'pssp', 'data-app' => 'project');
$lang->project->noMultiple->scrum->menu->other['dropMenu']->auditplan   = array('link' => "{$lang->qa->shortCommon}|auditplan|browse|projectID=%s&from=project", 'subModule' => 'auditplan,nc', 'data-app' => 'project');
$lang->project->noMultiple->scrum->menu->other['dropMenu']->meeting     = array('link' => '会议|meeting|browse|projectID=%s', 'subModule' => 'meeting');

$lang->project->noMultiple->scrum->menu->auditplan['subMenu'] = new stdclass();
$lang->project->noMultiple->scrum->menu->auditplan['subMenu']->auditplan = array('link' => '质量保证计划|auditplan|browse|project=%s&from=project', 'alias' => 'create,batchcreate,edit,batchcheck,batchedit');
$lang->project->noMultiple->scrum->menu->auditplan['subMenu']->nc        = array('link' => '不符合项|nc|browse|project=%s&from=project', 'alias' => 'create,edit,view');

/* Execution menu. */
$lang->execution->menu->other   = array('link' => "$lang->other|issue|browse|project=%s&from=execution", 'class' => 'dropdown dropdown-hover');
$lang->execution->menuOrder[67] = 'other';

$lang->execution->menu->other['dropMenu'] = new stdclass();
$lang->execution->menu->other['dropMenu']->issue       = array('link' => '问题|issue|browse|executionID=%s&from=execution', 'subModule' => 'issue');
$lang->execution->menu->other['dropMenu']->risk        = array('link' => '风险|risk|browse|executionID=%s&from=execution', 'subModule' => 'risk');
$lang->execution->menu->other['dropMenu']->opportunity = array('link' => "机会|opportunity|browse|executionID=%s&from=execution", 'subModule' => 'opportunity');
$lang->execution->menu->other['dropMenu']->pssp        = array('link' => '过程|pssp|browse|executionID=%s&from=execution', 'subModule' => 'pssp');
$lang->execution->menu->other['dropMenu']->auditplan   = array('link' => "{$lang->qa->shortCommon}|auditplan|browse|executionID=%s&from=execution", 'subModule' => 'auditplan,nc');
$lang->execution->menu->other['dropMenu']->meeting     = array('link' => '会议|meeting|browse|executionID=%s&from=execution', 'subModule' => 'meeting');

$lang->execution->menu->auditplan['subMenu'] = new stdclass();
$lang->execution->menu->auditplan['subMenu']->auditplan = array('link' => '质量保证计划|auditplan|browse|project=%s&from=execution', 'alias' => 'create,batchcreate,edit,batchcheck,batchedit');
$lang->execution->menu->auditplan['subMenu']->nc        = array('link' => '不符合项|nc|browse|project=%s&from=execution', 'alias' => 'create,edit,view');

/* Waterfall menu. */
$lang->waterfall->menu->track    = array('link' => "$lang->track|projectstory|track|project=%s", 'alias' => 'track');
$lang->waterfall->menu->review   = array('link' => '评审|review|browse|project=%s', 'subModule' => 'review,reviewissue');
$lang->waterfall->menu->cm       = array('link' => '配置|cm|browse|project=%s', 'subModule' => 'cm');
$lang->waterfall->menu->weekly   = array('link' => "{$lang->project->report}|weekly|index|project=%s", 'subModule' => ',milestone,');
$lang->waterfall->menu->other    = array('link' => "$lang->other|project|other|", 'class' => 'dropdown dropdown-hover');

$lang->waterfall->dividerMenu = ',programplan,review,build,dynamic,';

/* Waterfall menu order. */
$lang->waterfall->menuOrder[40] = 'track';
$lang->waterfall->menuOrder[45] = 'review';
$lang->waterfall->menuOrder[50] = 'cm';
$lang->waterfall->menuOrder[75] = 'weekly';
$lang->waterfall->menuOrder[85] = 'other';

$lang->waterfall->menu->other['dropMenu'] = new stdclass();
$lang->waterfall->menu->other['dropMenu']->research    = array('link' => '调研|researchplan|browse|projectID=%s', 'subModule' => 'researchplan,researchreport');
$lang->waterfall->menu->other['dropMenu']->estimation  = array('link' => "$lang->estimation|workestimation|index|projectID=%s", 'subModule' => 'workestimation,durationestimation,budget');
$lang->waterfall->menu->other['dropMenu']->deliverable = array('link' => "{$lang->deliverable->common}|project|deliverable|projectID=%s");
$lang->waterfall->menu->other['dropMenu']->issue       = array('link' => "问题|issue|browse|projectID=%s", 'subModule' => 'issue');
$lang->waterfall->menu->other['dropMenu']->risk        = array('link' => "风险|risk|browse|projectID=%s", 'subModule' => 'risk');
$lang->waterfall->menu->other['dropMenu']->opportunity = array('link' => "机会|opportunity|browse|projectID=%s", 'subModule' => 'opportunity');
$lang->waterfall->menu->other['dropMenu']->pssp        = array('link' => '过程|pssp|browse|projectID=%s', 'subModule' => 'pssp');
$lang->waterfall->menu->other['dropMenu']->report      = array('link' => '度量|report|projectsummary|projectID=%s', 'subModule' => 'measrecord,report');
$lang->waterfall->menu->other['dropMenu']->auditplan   = array('link' => "{$lang->qa->shortCommon}|auditplan|browse|projectID=%s", 'subModule' => 'auditplan,nc');
$lang->waterfall->menu->other['dropMenu']->train       = array('link' => '培训|gapanalysis|browse|projectID=%s', 'subModule' => 'trainplan,gapanalysis');
$lang->waterfall->menu->other['dropMenu']->meeting     = array('link' => '会议|meeting|browse|projectID=%s', 'subModule' => 'meeting');

$lang->waterfall->menu->research['subMenu'] = new stdclass();
$lang->waterfall->menu->research['subMenu']->researchplan   = array('link' => '调研计划|researchplan|browse|projectID=%s', 'alias' => 'create,edit,view');
$lang->waterfall->menu->research['subMenu']->researchreport = array('link' => '调研报告|researchreport|browse|projectID=%s', 'alias' => 'create,edit,view');

$lang->waterfall->menu->estimation = array();
$lang->waterfall->menu->estimation['subMenu'] = new stdclass();
$lang->waterfall->menu->estimation['subMenu']->workestimation = '工作量估算|workestimation|index|project=%s';
$lang->waterfall->menu->estimation['subMenu']->duration       = array('link' => '工期估算|durationestimation|index|project=%s', 'subModule' => 'durationestimation');
$lang->waterfall->menu->estimation['subMenu']->budget         = array('link' => '费用估算|budget|summary|project=%s', 'subModule' => 'budget');

$lang->waterfall->menu->auditplan['subMenu'] = new stdclass();
$lang->waterfall->menu->auditplan['subMenu']->auditplan = array('link' => '质量保证计划|auditplan|browse|project=%s', 'alias' => 'create,batchcreate,edit,batchcheck,batchedit');
$lang->waterfall->menu->auditplan['subMenu']->nc        = array('link' => '不符合项|nc|browse|project=%s', 'alias' => 'edit,view,create');

//$lang->stakeholder->menu->plan        = array('link' => '介入计划|stakeholder|plan|');
//$lang->stakeholder->menu->expectation = array('link' => '期望管理|stakeholder|expectation|', 'alias' => 'createexpect');

$lang->waterfall->menu->weekly['subMenu'] = new stdclass();
$lang->waterfall->menu->weekly['subMenu']->index     = "{$lang->project->report}|weekly|index|project=%s";
$lang->waterfall->menu->weekly['subMenu']->milestone = '里程碑报告|milestone|index|project=%s';

$lang->waterfall->menu->weekly['menuOrder'][5]  = 'index';
$lang->waterfall->menu->weekly['menuOrder'][10] = 'milestone';

$lang->waterfall->menu->review['subMenu'] = new stdclass();
$lang->waterfall->menu->review['subMenu']->browse = array('link' => '基线评审列表|review|browse|project=%s', 'alias' => 'report,assess,result,audit,create,edit,view');
$lang->waterfall->menu->review['subMenu']->issue  = array('link' => '问题列表|reviewissue|issue|project=%s',  'alias' => 'create,edit,view');

$lang->waterfall->menu->review['menuOrder'][5]  = 'browse';
$lang->waterfall->menu->review['menuOrder'][10] = 'issue';

$lang->waterfall->menu->cm['subMenu'] = new stdclass();
$lang->waterfall->menu->cm['subMenu']->browse = array('link' => '基线|cm|browse|project=%s', 'alias' => 'create,edit,view');
$lang->waterfall->menu->cm['subMenu']->report = '基线状态报告|cm|report|project=%s';

$lang->waterfall->menu->report['subMenu'] = new stdclass();
$lang->waterfall->menu->report['subMenu']->summary    = array('link' => '统计报表|report|projectsummary|projectID=%s', 'alias' => 'projectworkload,show,customeredreport,viewreport');
$lang->waterfall->menu->report['subMenu']->measrecord = array('link' => '度量列表|measrecord|browse|projectID=%s');

$lang->waterfall->menu->train['subMenu'] = new stdclass();
$lang->waterfall->menu->train['subMenu']->gapanalysis = array('link' => '能力差距分析|gapanalysis|browse|projectID=%s', 'alias' => 'create,edit,view,batchcreate,batchedit');
$lang->waterfall->menu->train['subMenu']->trainplan   = array('link' => '培训计划|trainplan|browse|projectID=%s', 'alias' => 'create,edit,view,batchcreate,batchedit');

$lang->waterfall->menu->settings['subMenu'] = clone $lang->scrum->menu->settings['subMenu'];
unset($lang->waterfall->menu->settings['subMenu']->workflow); // 企业版已加，此处要删除为了排序
$lang->waterfall->menu->settings['subMenu']->approval = array('link' => "审批|project|approval|project=%s", 'alias' => 'approval');
$lang->waterfall->menu->settings['subMenu']->workflow = array('link' => "项目流程|project|workflowgroup|project=%s", 'alias' => 'workflowgroup');
$lang->waterfall->menu->settings['alias'] .= ',approval,workflowgroup';

$lang->assetlib->menu = new stdclass();
$lang->assetlib->menu->storylib       = array('link' => '需求库|assetlib|storylib', 'alias' => 'createstorylib,storylibview,story,importstory,editstorylib,storyview,editstory,assigntostory');
$lang->assetlib->menu->caselib        = array('link' => '用例库|assetlib|caselib');
$lang->assetlib->menu->issuelib       = array('link' => '问题库|assetlib|issuelib', 'alias' => 'createissuelib,issuelibview,issue,importissue,editissuelib,issueview,editissue,assigntoissue');
$lang->assetlib->menu->risklib        = array('link' => '风险库|assetlib|risklib', 'alias' => 'createrisklib,risklibview,risk,importrisk,editrisklib,riskview,editrisk,assigntorisk');
$lang->assetlib->menu->opportunitylib = array('link' => '机会库|assetlib|opportunitylib', 'alias' => 'createopportunitylib,opportunitylibview,opportunity,importopportunity,editopportunitylib,opportunityview,editopportunity,assigntoopportunity');
$lang->assetlib->menu->practicelib    = array('link' => '最佳实践库|assetlib|practicelib', 'alias' => 'createpracticelib,practicelibview,practice,importpractice,editpracticelib,practiceview,editpractice,assigntopractice');
$lang->assetlib->menu->componentlib   = array('link' => '组件库|assetlib|componentlib', 'alias' => 'createcomponentlib,componentlibview,component,importcomponent,editcomponentlib,componentview,editcomponent,assigntocomponent');

$lang->assetlib->menuOrder[5]  = 'storylib';
$lang->assetlib->menuOrder[10] = 'caselib';
$lang->assetlib->menuOrder[15] = 'issuelib';
$lang->assetlib->menuOrder[20] = 'risklib';
$lang->assetlib->menuOrder[25] = 'opportunitylib';
$lang->assetlib->menuOrder[30] = 'practicelib';
$lang->assetlib->menuOrder[35] = 'componentlib';

if(helper::hasFeature('issue'))       $lang->searchObjects['issue']       = '问题';
if(helper::hasFeature('risk'))        $lang->searchObjects['risk']        = '风险';
if(helper::hasFeature('opportunity')) $lang->searchObjects['opportunity'] = '机会';
if(helper::hasFeature('gapanalysis')) $lang->searchObjects['trainplan']   = '培训计划';

$lang->stage->attribute['dev'] = new stdclass();
$lang->stage->attribute['dev']->menu = new stdclass();
$lang->stage->attribute['dev']->menu = clone $lang->execution->menu;

unset($lang->stage->attribute['dev']->menu->other);

$lang->stage->attribute['dev']->dividerMenu = ',story,build,';

$lang->stage->attribute['request'] = new stdclass();
$lang->stage->attribute['request']->menu = new stdclass();
$lang->stage->attribute['request']->menu->task        = $lang->execution->menu->task;
$lang->stage->attribute['request']->menu->kanban      = $lang->execution->menu->kanban;
$lang->stage->attribute['request']->menu->burn        = $lang->execution->menu->burn;
$lang->stage->attribute['request']->menu->view        = $lang->execution->menu->view;
$lang->stage->attribute['request']->menu->story       = $lang->execution->menu->story;
$lang->stage->attribute['request']->menu->effort      = $lang->execution->menu->effort;
$lang->stage->attribute['request']->menu->doc         = $lang->execution->menu->doc;
$lang->stage->attribute['request']->menu->action      = $lang->execution->menu->action;
$lang->stage->attribute['request']->menu->settings    = $lang->execution->menu->settings;
if(isset($lang->execution->menu->more)) $lang->stage->attribute['request']->menu->more = $lang->execution->menu->more;

/* Execution menu order. */
$lang->stage->attribute['request']->menuOrder[5]  = 'task';
$lang->stage->attribute['request']->menuOrder[10] = 'kanban';
$lang->stage->attribute['request']->menuOrder[15] = 'burn';
$lang->stage->attribute['request']->menuOrder[20] = 'view';
$lang->stage->attribute['request']->menuOrder[22] = 'view';
$lang->stage->attribute['request']->menuOrder[25] = 'effort';
$lang->stage->attribute['request']->menuOrder[30] = 'doc';
$lang->stage->attribute['request']->menuOrder[40] = 'action';
$lang->stage->attribute['request']->menuOrder[45] = 'settings';
$lang->stage->attribute['request']->menuOrder[50] = 'more';

$lang->stage->attribute['request']->menu->settings['subMenu'] = new stdclass();
$lang->stage->attribute['request']->menu->settings['subMenu']->view      = $lang->execution->menu->settings['subMenu']->view;
$lang->stage->attribute['request']->menu->settings['subMenu']->team      = $lang->execution->menu->settings['subMenu']->team;
$lang->stage->attribute['request']->menu->settings['subMenu']->whitelist = $lang->execution->menu->settings['subMenu']->whitelist;

$lang->stage->attribute['request']->menu->settings['menuOrder'][5]  = 'view';
$lang->stage->attribute['request']->menu->settings['menuOrder'][10] = 'team';
$lang->stage->attribute['request']->menu->settings['menuOrder'][15] = 'whitelist';

$lang->stage->attribute['request']->dividerMenu = ',effort,';

$lang->stage->attribute['design'] = new stdclass();
$lang->stage->attribute['design']->menu = new stdclass();
$lang->stage->attribute['design']->menu->task        = $lang->execution->menu->task;
$lang->stage->attribute['design']->menu->kanban      = $lang->execution->menu->kanban;
$lang->stage->attribute['design']->menu->burn        = $lang->execution->menu->burn;
$lang->stage->attribute['design']->menu->view        = $lang->execution->menu->view;
$lang->stage->attribute['design']->menu->story       = $lang->execution->menu->story;
$lang->stage->attribute['design']->menu->effort      = $lang->execution->menu->effort;
$lang->stage->attribute['design']->menu->doc         = $lang->execution->menu->doc;
$lang->stage->attribute['design']->menu->action      = $lang->execution->menu->action;
$lang->stage->attribute['design']->menu->settings    = $lang->execution->menu->settings;
if(isset($lang->execution->menu->more)) $lang->stage->attribute['design']->menu->more = $lang->execution->menu->more;

/* Execution menu order. */
$lang->stage->attribute['design']->menuOrder[5]  = 'task';
$lang->stage->attribute['design']->menuOrder[10] = 'kanban';
$lang->stage->attribute['design']->menuOrder[15] = 'burn';
$lang->stage->attribute['design']->menuOrder[20] = 'view';
$lang->stage->attribute['design']->menuOrder[25] = 'story';
$lang->stage->attribute['design']->menuOrder[30] = 'effort';
$lang->stage->attribute['design']->menuOrder[35] = 'doc';
$lang->stage->attribute['design']->menuOrder[45] = 'action';
$lang->stage->attribute['design']->menuOrder[50] = 'settings';
$lang->stage->attribute['design']->menuOrder[55] = 'more';

$lang->stage->attribute['design']->menu->settings['subMenu'] = new stdclass();
$lang->stage->attribute['design']->menu->settings['subMenu']->view      = $lang->execution->menu->settings['subMenu']->view;
$lang->stage->attribute['design']->menu->settings['subMenu']->team      = $lang->execution->menu->settings['subMenu']->team;
$lang->stage->attribute['design']->menu->settings['subMenu']->whitelist = $lang->execution->menu->settings['subMenu']->whitelist;

$lang->stage->attribute['design']->menu->settings['menuOrder'][5]  = 'view';
$lang->stage->attribute['design']->menu->settings['menuOrder'][10] = 'team';
$lang->stage->attribute['design']->menu->settings['menuOrder'][15] = 'whitelist';

$lang->stage->attribute['design']->dividerMenu = ',story,';

$lang->stage->attribute['qa'] = new stdclass();
$lang->stage->attribute['qa']->menu = new stdclass();
$lang->stage->attribute['qa']->menu->task        = $lang->execution->menu->task;
$lang->stage->attribute['qa']->menu->kanban      = $lang->execution->menu->kanban;
$lang->stage->attribute['qa']->menu->burn        = $lang->execution->menu->burn;
$lang->stage->attribute['qa']->menu->view        = $lang->execution->menu->view;
$lang->stage->attribute['qa']->menu->story       = $lang->execution->menu->story;
$lang->stage->attribute['qa']->menu->qa          = $lang->execution->menu->qa;
$lang->stage->attribute['qa']->menu->effort      = $lang->execution->menu->effort;
$lang->stage->attribute['qa']->menu->doc         = $lang->execution->menu->doc;
$lang->stage->attribute['qa']->menu->build       = $lang->execution->menu->build;
$lang->stage->attribute['qa']->menu->action      = $lang->execution->menu->action;
$lang->stage->attribute['qa']->menu->settings    = $lang->execution->menu->settings;
if(isset($lang->execution->menu->more)) $lang->stage->attribute['qa']->menu->more = $lang->execution->menu->more;

/* Execution menu order. */
$lang->stage->attribute['qa']->menuOrder[5]  = 'task';
$lang->stage->attribute['qa']->menuOrder[10] = 'kanban';
$lang->stage->attribute['qa']->menuOrder[15] = 'burn';
$lang->stage->attribute['qa']->menuOrder[20] = 'view';
$lang->stage->attribute['qa']->menuOrder[25] = 'story';
$lang->stage->attribute['qa']->menuOrder[30] = 'qa';
$lang->stage->attribute['qa']->menuOrder[35] = 'effort';
$lang->stage->attribute['qa']->menuOrder[40] = 'doc';
$lang->stage->attribute['qa']->menuOrder[50] = 'build';
$lang->stage->attribute['qa']->menuOrder[55] = 'action';
$lang->stage->attribute['qa']->menuOrder[60] = 'settings';
$lang->stage->attribute['qa']->menuOrder[65] = 'more';

$lang->stage->attribute['qa']->dividerMenu = ',story,build,';

$lang->stage->attribute['release'] = new stdclass();
$lang->stage->attribute['release']->menu = new stdclass();
$lang->stage->attribute['release']->menu->task        = $lang->execution->menu->task;
$lang->stage->attribute['release']->menu->kanban      = $lang->execution->menu->kanban;
$lang->stage->attribute['release']->menu->burn        = $lang->execution->menu->burn;
$lang->stage->attribute['release']->menu->view        = $lang->execution->menu->view;
$lang->stage->attribute['release']->menu->story       = $lang->execution->menu->story;
$lang->stage->attribute['release']->menu->qa          = $lang->execution->menu->qa;
$lang->stage->attribute['release']->menu->effort      = $lang->execution->menu->effort;
$lang->stage->attribute['release']->menu->doc         = $lang->execution->menu->doc;
$lang->stage->attribute['release']->menu->build       = $lang->execution->menu->build;
$lang->stage->attribute['release']->menu->action      = $lang->execution->menu->action;
$lang->stage->attribute['release']->menu->settings    = $lang->execution->menu->settings;
if(isset($lang->execution->menu->devops)) $lang->stage->attribute['release']->menu->devops = $lang->execution->menu->devops;
if(isset($lang->execution->menu->more))   $lang->stage->attribute['release']->menu->more   = $lang->execution->menu->more;

/* Execution menu order. */
$lang->stage->attribute['release']->menuOrder[5]  = 'task';
$lang->stage->attribute['release']->menuOrder[10] = 'kanban';
$lang->stage->attribute['release']->menuOrder[15] = 'burn';
$lang->stage->attribute['release']->menuOrder[20] = 'view';
$lang->stage->attribute['release']->menuOrder[25] = 'story';
$lang->stage->attribute['release']->menuOrder[30] = 'qa';
$lang->stage->attribute['release']->menuOrder[35] = 'devops';
$lang->stage->attribute['release']->menuOrder[40] = 'effort';
$lang->stage->attribute['release']->menuOrder[45] = 'doc';
$lang->stage->attribute['release']->menuOrder[55] = 'build';
$lang->stage->attribute['release']->menuOrder[60] = 'action';
$lang->stage->attribute['release']->menuOrder[65] = 'settings';
$lang->stage->attribute['release']->menuOrder[65] = 'more';

$lang->stage->attribute['release']->dividerMenu = ',story,build,';

$lang->stage->attribute['review'] = new stdclass();
$lang->stage->attribute['review']->menu = new stdclass();
$lang->stage->attribute['review']->menu->task        = $lang->execution->menu->task;
$lang->stage->attribute['review']->menu->kanban      = $lang->execution->menu->kanban;
$lang->stage->attribute['review']->menu->burn        = $lang->execution->menu->burn;
$lang->stage->attribute['review']->menu->view        = $lang->execution->menu->view;
$lang->stage->attribute['review']->menu->effort      = $lang->execution->menu->effort;
$lang->stage->attribute['review']->menu->doc         = $lang->execution->menu->doc;
$lang->stage->attribute['review']->menu->action      = $lang->execution->menu->action;
$lang->stage->attribute['review']->menu->settings    = $lang->execution->menu->settings;
if(isset($lang->execution->menu->more)) $lang->stage->attribute['review']->menu->more = $lang->execution->menu->more;

/* Execution menu order. */
$lang->stage->attribute['review']->menuOrder[5]  = 'task';
$lang->stage->attribute['review']->menuOrder[10] = 'kanban';
$lang->stage->attribute['review']->menuOrder[15] = 'burn';
$lang->stage->attribute['review']->menuOrder[20] = 'view';
$lang->stage->attribute['review']->menuOrder[25] = 'effort';
$lang->stage->attribute['review']->menuOrder[30] = 'doc';
$lang->stage->attribute['review']->menuOrder[40] = 'action';
$lang->stage->attribute['review']->menuOrder[45] = 'settings';
$lang->stage->attribute['review']->menuOrder[50] = 'more';

$lang->stage->attribute['review']->menu->settings['subMenu'] = new stdclass();
$lang->stage->attribute['review']->menu->settings['subMenu']->view      = $lang->execution->menu->settings['subMenu']->view;
$lang->stage->attribute['review']->menu->settings['subMenu']->team      = $lang->execution->menu->settings['subMenu']->team;
$lang->stage->attribute['review']->menu->settings['subMenu']->whitelist = $lang->execution->menu->settings['subMenu']->whitelist;

$lang->stage->attribute['review']->menu->settings['menuOrder'][5]  = 'view';
$lang->stage->attribute['review']->menu->settings['menuOrder'][10] = 'team';
$lang->stage->attribute['review']->menu->settings['menuOrder'][15] = 'whitelist';

$lang->stage->attribute['review']->dividerMenu = ',effort,';

if($config->vision != 'rnd') unset($lang->project->homeMenu->template);

if(!helper::hasFeature('issue'))
{
    unset($lang->my->menu->work['subMenu']->issue,       $lang->my->menu->work['menuOrder'][40]);
    unset($lang->my->menu->contribute['subMenu']->issue, $lang->my->menu->contribute['menuOrder'][45]);
}
if(!helper::hasFeature('risk'))
{
    unset($lang->my->menu->work['subMenu']->risk,       $lang->my->menu->work['menuOrder'][45]);
    unset($lang->my->menu->contribute['subMenu']->risk, $lang->my->menu->contribute['menuOrder'][50]);
}
if(!helper::hasFeature('auditplan'))
{
    unset($lang->my->menu->work['subMenu']->nc,       $lang->my->menu->work['menuOrder'][50]);
    unset($lang->my->menu->contribute['subMenu']->nc, $lang->my->menu->contribute['menuOrder'][55]);
}
if(!helper::hasFeature('meeting'))
{
    unset($lang->my->menu->meeting,                    $lang->my->menuOrder[41]);
    unset($lang->my->menu->work['subMenu']->myMeeting, $lang->my->menu->work['menuOrder'][55]);
}

if(!helper::hasFeature('storylib'))       unset($lang->assetlib->menu->storylib);
if(!helper::hasFeature('caselib'))        unset($lang->assetlib->menu->caselib);
if(!helper::hasFeature('issuelib'))       unset($lang->assetlib->menu->issuelib);
if(!helper::hasFeature('risklib'))        unset($lang->assetlib->menu->risklib);
if(!helper::hasFeature('opportunitylib')) unset($lang->assetlib->menu->opportunitylib);
if(!helper::hasFeature('practicelib'))    unset($lang->assetlib->menu->practicelib);
if(!helper::hasFeature('componentlib'))   unset($lang->assetlib->menu->componentlib);

if(!helper::hasFeature('deliverable'))
{
    unset($lang->scrum->menu->other['dropMenu']->deliverable);
    unset($lang->waterfall->menu->other['dropMenu']->deliverable);
}

if(!helper::hasFeature('kanban')) $lang->dividerMenu = str_replace(',kanban,', ',assetlib,', $lang->dividerMenu);

if(!helper::hasFeature('assetlib'))
{
    unset($lang->mainNav->assetlib, $lang->mainNav->menuOrder[57]);
}
else
{
    $assetlibMenus = array_keys((array)$lang->assetlib->menu);
    $methodName    = reset($assetlibMenus);
    $lang->mainNav->assetlib = "{$lang->navIcons['assetlib']} {$lang->assetlib->common}|assetlib|$methodName|";
}

$lang->doc->menu->template = array('link' => "模板广场|doc|browseTemplate|", 'alias' => 'browsetemplate');
$lang->doc->menuOrder[35]  = 'template';
$lang->doc->dividerMenu   .= ',template,';

$lang->workflow->menu->approval = array('link' => '审批流|approvalflow|browse|', 'subModule' => 'approvalflow,approvalrole');

$lang->workflow->menuOrder[25] = 'approval';

$lang->workflow->dividerMenu = ',approval,';
