<?php
$config->assetlib = new stdclass();
$config->assetlib->create = new stdclass();
$config->assetlib->create->requiredFields = 'name';

$config->assetlib->editasset = new stdclass();
$config->assetlib->editasset->requiredFields = 'name,title,type,severity';

$config->assetlib->editor = new stdclass();
$config->assetlib->editor->create               = array('id' => 'desc', 'tools' => 'simpleTools');
$config->assetlib->editor->createstorylib       = array('id' => 'desc', 'tools' => 'simpleTools');
$config->assetlib->editor->createissuelib       = array('id' => 'desc', 'tools' => 'simpleTools');
$config->assetlib->editor->createrisklib        = array('id' => 'desc', 'tools' => 'simpleTools');
$config->assetlib->editor->createopportunitylib = array('id' => 'desc', 'tools' => 'simpleTools');
$config->assetlib->editor->createpracticelib    = array('id' => 'desc', 'tools' => 'simpleTools');
$config->assetlib->editor->createcomponentlib   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->assetlib->editor->edit                 = array('id' => 'desc', 'tools' => 'simpleTools');
$config->assetlib->editor->editstorylib         = array('id' => 'desc', 'tools' => 'simpleTools');
$config->assetlib->editor->editissuelib         = array('id' => 'desc', 'tools' => 'simpleTools');
$config->assetlib->editor->editrisklib          = array('id' => 'desc', 'tools' => 'simpleTools');
$config->assetlib->editor->editopportunitylib   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->assetlib->editor->editpracticelib      = array('id' => 'desc', 'tools' => 'simpleTools');
$config->assetlib->editor->editcomponentlib     = array('id' => 'desc', 'tools' => 'simpleTools');
$config->assetlib->editor->assigntostory        = array('id' => 'comment', 'tools' => 'simpleTools');
$config->assetlib->editor->assigntoissue        = array('id' => 'comment', 'tools' => 'simpleTools');
$config->assetlib->editor->assigntorisk         = array('id' => 'comment', 'tools' => 'simpleTools');
$config->assetlib->editor->assigntoopportunity  = array('id' => 'comment', 'tools' => 'simpleTools');
$config->assetlib->editor->assigntopractice     = array('id' => 'comment', 'tools' => 'simpleTools');
$config->assetlib->editor->assigntocomponent    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->assetlib->editor->approvestory         = array('id' => 'comment', 'tools' => 'simpleTools');
$config->assetlib->editor->approveissue         = array('id' => 'comment', 'tools' => 'simpleTools');
$config->assetlib->editor->approverisk          = array('id' => 'comment', 'tools' => 'simpleTools');
$config->assetlib->editor->approveopportunity   = array('id' => 'comment', 'tools' => 'simpleTools');
$config->assetlib->editor->approvepractice      = array('id' => 'comment', 'tools' => 'simpleTools');
$config->assetlib->editor->approvecomponent     = array('id' => 'comment', 'tools' => 'simpleTools');
$config->assetlib->editor->storyview            = array('id' => 'comment', 'tools' => 'simpleTools');
$config->assetlib->editor->issueview            = array('id' => 'comment', 'tools' => 'simpleTools');
$config->assetlib->editor->riskview             = array('id' => 'comment', 'tools' => 'simpleTools');
$config->assetlib->editor->opportunityview      = array('id' => 'comment', 'tools' => 'simpleTools');
$config->assetlib->editor->practiceview         = array('id' => 'comment', 'tools' => 'simpleTools');
$config->assetlib->editor->componentview        = array('id' => 'comment', 'tools' => 'simpleTools');
$config->assetlib->editor->editstory            = array('id' => 'spec,verify', 'tools' => 'simpleTools');
$config->assetlib->editor->editissue            = array('id' => 'desc', 'tools' => 'simpleTools');
$config->assetlib->editor->editrisk             = array('id' => 'prevention,remedy,resolution', 'tools' => 'simpleTools');
$config->assetlib->editor->editopportunity      = array('id' => 'prevention,resolution,desc', 'tools' => 'simpleTools');
$config->assetlib->editor->editasset            = array('id' => 'spec,verify,desc,prevention,remedy,resolution', 'tools' => 'simpleTools');
$config->assetlib->editor->editpractice         = array('id' => 'content', 'tools' => 'simpleTools');
$config->assetlib->editor->editcomponent        = array('id' => 'content', 'tools' => 'simpleTools');

$config->assetlib->assignToMethod['story']       = 'assignToStory';
$config->assetlib->assignToMethod['issue']       = 'assignToIssue';
$config->assetlib->assignToMethod['risk']        = 'assignToRisk';
$config->assetlib->assignToMethod['opportunity'] = 'assignToOpportunity';
$config->assetlib->assignToMethod['practice']    = 'assignToPractice';
$config->assetlib->assignToMethod['component']   = 'assignToComponent';

$config->assetlib->approveMethods['story']       = array('approveStory', 'batchApproveStory');
$config->assetlib->approveMethods['issue']       = array('approveIssue', 'batchApproveIssue');
$config->assetlib->approveMethods['risk']        = array('approveRisk', 'batchApproveRisk');
$config->assetlib->approveMethods['opportunity'] = array('approveOpportunity', 'batchApproveOpportunity');
$config->assetlib->approveMethods['practice']    = array('approvePractice', 'batchApprovePractice');
$config->assetlib->approveMethods['component']   = array('approveComponent', 'batchApproveComponent');

global $lang;
$config->assetlib->searchFields['story']       = 'title,id,pri,lib,openedBy,openedDate,assignedTo,approvedDate,lastEditedBy,lastEditedDate,status,category,estimate,keywords';
$config->assetlib->searchFields['issue']       = 'title,id,lib,pri,severity,status,type,createdBy,createdDate,assignedTo,approvedDate,editedBy,editedDate';
$config->assetlib->searchFields['risk']        = 'name,id,lib,pri,source,category,strategy,status,impact,probability,rate,createdBy,createdDate,assignedTo,approvedDate,editedBy,editedDate';
$config->assetlib->searchFields['opportunity'] = 'name,id,pri,lib,source,type,strategy,status,impact,chance,ratio,createdBy,createdDate,assignedTo,approvedDate,editedBy,editedDate';
$config->assetlib->searchFields['practice']    = 'title,id,assetLib,keywords,addedBy,addedDate,assignedTo,approvedDate,editedBy,editedDate';
$config->assetlib->searchFields['component']   = 'title,id,assetLib,keywords,addedBy,addedDate,assignedTo,approvedDate,editedBy,editedDate';

$config->assetlib->search['fields']['assignedTo']   = $lang->assetlib->approvedBy;
$config->assetlib->search['fields']['approvedDate'] = $lang->assetlib->approvedDate;

$config->assetlib->search['params']['assignedTo']   = array('operator' => '=', 'control' => 'select', 'values' => 'users');
$config->assetlib->search['params']['approvedDate'] = array('operator' => '=', 'control' => 'input',  'values' => '', 'class' => 'date');
