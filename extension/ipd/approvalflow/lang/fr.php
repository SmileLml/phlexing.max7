<?php
$lang->approvalflow->browse        = 'Flow List';
$lang->approvalflow->create        = 'Create Flow';
$lang->approvalflow->edit          = 'Edit Flow';
$lang->approvalflow->view          = 'View Flow';
$lang->approvalflow->delete        = 'Delete Flow';
$lang->approvalflow->design        = 'Design Flow';
$lang->approvalflow->roleList      = 'Role List';
$lang->approvalflow->createRole    = 'Create Role';
$lang->approvalflow->editRole      = 'Edit Role';
$lang->approvalflow->deleteRole    = 'Delete Role';

$lang->approvalflow->common        = 'Approval Flow';
$lang->approvalflow->id            = 'ID';
$lang->approvalflow->name          = 'Name';
$lang->approvalflow->createdBy     = 'Created By';
$lang->approvalflow->createdDate   = 'Created Date';
$lang->approvalflow->noFlow        = 'No flows.';
$lang->approvalflow->title         = 'Title';
$lang->approvalflow->reviewer      = 'Reviewers';
$lang->approvalflow->workflow      = 'Workflow';
$lang->approvalflow->ccer          = 'CCers';
$lang->approvalflow->condition     = 'Condition';
$lang->approvalflow->parallel      = 'Parallel';
$lang->approvalflow->priv          = 'Approval Priv';
$lang->approvalflow->approval      = 'Approval Flow';
$lang->approvalflow->desc          = 'Description';
$lang->approvalflow->basicInfo     = 'Basic Infomation';
$lang->approvalflow->confirmDelete = 'Do you confirm delete it?';
$lang->approvalflow->setNode       = 'Set Node';
$lang->approvalflow->select        = 'Select';
$lang->approvalflow->needAll       = 'Requires review by all';
$lang->approvalflow->percent       = 'Percent';

$lang->approvalflow->nameList = array();
$lang->approvalflow->nameList['stage']  = 'Stage';

$lang->approvalflow->nodeTypeList = array();
$lang->approvalflow->nodeTypeList['branch']    = 'Branch';
$lang->approvalflow->nodeTypeList['condition'] = 'Condition';
$lang->approvalflow->nodeTypeList['default']   = 'Default';
$lang->approvalflow->nodeTypeList['other']     = 'Other';
$lang->approvalflow->nodeTypeList['approval']  = 'Approval';
$lang->approvalflow->nodeTypeList['cc']        = 'Mail To';
$lang->approvalflow->nodeTypeList['start']     = 'Start';
$lang->approvalflow->nodeTypeList['end']       = 'Finish';

$lang->approvalflow->userTypeList = array();
$lang->approvalflow->userTypeList['cc']        = 'Mailto List';
$lang->approvalflow->userTypeList['submitter'] = 'Submitter';
$lang->approvalflow->userTypeList['reviewer']  = 'Reviewer';

$lang->approvalflow->noticeTypeList = array();
$lang->approvalflow->noticeTypeList['setReviewer']     = 'Set reviewer';
$lang->approvalflow->noticeTypeList['setCondition']    = 'Set condition';
$lang->approvalflow->noticeTypeList['addCondition']    = 'Add condition';
$lang->approvalflow->noticeTypeList['addParallel']     = 'Add parallel';
$lang->approvalflow->noticeTypeList['addCond']         = 'Add condition';
$lang->approvalflow->noticeTypeList['addReviewer']     = 'Add reviewer';
$lang->approvalflow->noticeTypeList['addCC']           = 'Add ccer';
$lang->approvalflow->noticeTypeList['setCC']           = 'Set ccer';
$lang->approvalflow->noticeTypeList['setNode']         = 'Set node';
$lang->approvalflow->noticeTypeList['defaultBranch']   = 'All conditions will be run';
$lang->approvalflow->noticeTypeList['otherBranch']     = 'Other condition will be run';
$lang->approvalflow->noticeTypeList['conditionOr']     = 'If one conditon is ok, run it';
$lang->approvalflow->noticeTypeList['when']            = 'When';
$lang->approvalflow->noticeTypeList['type']            = 'Type';
$lang->approvalflow->noticeTypeList['confirm']         = 'Confirm';
$lang->approvalflow->noticeTypeList['reviewType']      = 'Review type';
$lang->approvalflow->noticeTypeList['ccType']          = 'CC type';
$lang->approvalflow->noticeTypeList['reviewRange']     = 'Review range';
$lang->approvalflow->noticeTypeList['ccRange']         = 'CC range';
$lang->approvalflow->noticeTypeList['range']           = 'Range';
$lang->approvalflow->noticeTypeList['value']           = 'Value';
$lang->approvalflow->noticeTypeList['set']             = 'Set';
$lang->approvalflow->noticeTypeList['node']            = 'node';
$lang->approvalflow->noticeTypeList['approvalTitle']   = 'Approval title';
$lang->approvalflow->noticeTypeList['ccTitle']         = 'CC Title';
$lang->approvalflow->noticeTypeList['multipleType']    = 'If multiple person is reviewing';
$lang->approvalflow->noticeTypeList['multipleAnd']     = 'And(All reviewers must agree)';
$lang->approvalflow->noticeTypeList['multiplePercent'] = 'Percent(Over percent reviewers must agree)';
$lang->approvalflow->noticeTypeList['multipleOr']      = 'Or(Only need one reviewer agree)';
$lang->approvalflow->noticeTypeList['multipleSolicit'] = 'Solicit(Always pass)';
$lang->approvalflow->noticeTypeList['commentType']     = 'Approval opinion is required';
$lang->approvalflow->noticeTypeList['required']        = 'Required';
$lang->approvalflow->noticeTypeList['noRequired']      = 'No Required';
$lang->approvalflow->noticeTypeList['agentType']       = 'When reviewer is empty';
$lang->approvalflow->noticeTypeList['agentPass']       = 'Pass';
$lang->approvalflow->noticeTypeList['agentReject']     = 'Reject';
$lang->approvalflow->noticeTypeList['agentUser']       = 'Appointer';
$lang->approvalflow->noticeTypeList['agentAdmin']      = 'Administrator';
$lang->approvalflow->noticeTypeList['selfType']        = 'When the approver and initiator are the same person';
$lang->approvalflow->noticeTypeList['selfReview']      = 'Initiator review';
$lang->approvalflow->noticeTypeList['selfPass']        = 'Auto Pass';
$lang->approvalflow->noticeTypeList['selfNext']        = 'Transfer to superior';
$lang->approvalflow->noticeTypeList['selfManager']     = 'Transferred to department head';
$lang->approvalflow->noticeTypeList['deletedType']     = 'When the approver has been deleted';
$lang->approvalflow->noticeTypeList['autoPass']        = 'Pass';
$lang->approvalflow->noticeTypeList['autoReject']      = 'Reject';
$lang->approvalflow->noticeTypeList['setUser']         = 'Appointer';
$lang->approvalflow->noticeTypeList['setSuperior']     = 'Transfer to superior';
$lang->approvalflow->noticeTypeList['setManager']      = 'Transferred to department head';
$lang->approvalflow->noticeTypeList['setAdmin']        = 'Administrator';

$lang->approvalflow->warningList = array();
$lang->approvalflow->warningList['needReview']     = 'Please add one node at least';
$lang->approvalflow->warningList['save']           = 'Are you sure leave?';
$lang->approvalflow->warningList['selectUser']     = 'Please select user';
$lang->approvalflow->warningList['selectDept']     = 'Please select department';
$lang->approvalflow->warningList['selectRole']     = 'Please select role';
$lang->approvalflow->warningList['selectPosition'] = 'Please select position';
$lang->approvalflow->warningList['needReviewer']   = 'Please select reviewer';
$lang->approvalflow->warningList['needValue']      = 'Please select value';
$lang->approvalflow->warningList['oneSelect']      = 'Only one type is "Submitter select"';
$lang->approvalflow->warningList['percent']        = 'Percent must be between 1 and 100, and must be integer';
$lang->approvalflow->warningList['workflow']       = 'After binding the workflow, you can use its field to configure the approval flow condition, and only use it under the bound workflow.';

$lang->approvalflow->userRangeList = array();
$lang->approvalflow->userRangeList['all']      = 'All';
$lang->approvalflow->userRangeList['role']     = 'Role';
$lang->approvalflow->userRangeList['dept']     = 'Dept';
$lang->approvalflow->userRangeList['position'] = 'Position';

$lang->approvalflow->reviewTypeList = array();
$lang->approvalflow->reviewTypeList['manual'] = 'Manual';
$lang->approvalflow->reviewTypeList['pass']   = 'Auto Pass';
$lang->approvalflow->reviewTypeList['reject'] = 'Auto Reject';

$lang->approvalflow->errorList = array();
$lang->approvalflow->errorList['needReivewer'] = 'Need reviewers';
$lang->approvalflow->errorList['needCcer']     = 'Need mailtos';
$lang->approvalflow->errorList['hasWorkflow']  = 'The flow has been bound to the workflow and cannot be delete.';

$lang->approvalflow->reviewerTypeList = array();
$lang->approvalflow->reviewerTypeList['select']        = array('name' => 'Submitter select',                'options' => 'userRange',      'tips' => 'Range');
$lang->approvalflow->reviewerTypeList['self']          = array('name' => 'Oneself',                         'options' => '',               'tips' => '');
$lang->approvalflow->reviewerTypeList['appointee']     = array('name' => 'Appointee',                       'options' => 'users',          'tips' => 'Users');
$lang->approvalflow->reviewerTypeList['role']          = array('name' => 'Roles',                           'options' => 'roles',          'tips' => 'Roles');
$lang->approvalflow->reviewerTypeList['position']      = array('name' => 'Position',                        'options' => 'positions',      'tips' => 'Position');
$lang->approvalflow->reviewerTypeList['upLevel']       = array('name' => 'Department Head',                 'options' => '',               'tips' => '');
$lang->approvalflow->reviewerTypeList['superior']      = array('name' => 'Higher level',                    'options' => '',               'tips' => '');
$lang->approvalflow->reviewerTypeList['superiorList']  = array('name' => 'Multiple Higher level',           'options' => '',               'tips' => 'Endpoint');
$lang->approvalflow->reviewerTypeList['setByPrev']     = array('name' => 'Designated by the previous node', 'options' => '',               'tips' => '');
$lang->approvalflow->reviewerTypeList['productRole']   = array('name' => 'Product Roles',                   'options' => 'productRoles',   'tips' => 'Roles');
$lang->approvalflow->reviewerTypeList['projectRole']   = array('name' => 'Project Roles',                   'options' => 'projectRoles',   'tips' => 'Roles');
$lang->approvalflow->reviewerTypeList['executionRole'] = array('name' => 'Execution Roles',                 'options' => 'executionRoles', 'tips' => 'Roles');

$lang->approvalflow->conditionTypeList = array();
$lang->approvalflow->conditionTypeList['submitUsers']     = 'Submitter';
$lang->approvalflow->conditionTypeList['submitDepts']     = 'Submitter Department';
$lang->approvalflow->conditionTypeList['submitRoles']     = 'Submitter Role';
$lang->approvalflow->conditionTypeList['submitPositions'] = 'Submitter Position';

$lang->approvalflow->superiorList[0] = 'Top Level';
$lang->approvalflow->superiorList[2] = 'Second-level superior';
$lang->approvalflow->superiorList[3] = 'Third-level superior';
$lang->approvalflow->superiorList[4] = 'Fourth-level superior';
$lang->approvalflow->superiorList[5] = 'Fifth-level superior';

$lang->approvalflow->productRoleList['PO']       = 'Product Owner';
$lang->approvalflow->productRoleList['QD']       = 'Tester Owner';
$lang->approvalflow->productRoleList['RD']       = 'Release Owner';
$lang->approvalflow->productRoleList['feedback'] = 'Feedback Owner';
$lang->approvalflow->productRoleList['ticket']   = 'Ticket Owner';
$lang->approvalflow->productRoleList['reviewer'] = 'Story Reviewer';

$lang->approvalflow->projectRoleList['PM']          = 'Project Manager';
$lang->approvalflow->projectRoleList['stakeholder'] = 'Stakeholder';

$lang->approvalflow->executionRoleList['PO'] = 'Product Owner';
$lang->approvalflow->executionRoleList['PM'] = 'Execution Manager';
$lang->approvalflow->executionRoleList['QD'] = 'Tester Owner';
$lang->approvalflow->executionRoleList['RD'] = 'Release Owner';

$lang->approvalflow->privList['forward']   = 'Can Forward';
$lang->approvalflow->privList['revert']    = 'Can Revert';
$lang->approvalflow->privList['addnode']   = 'Add Node';
$lang->approvalflow->privList['withdrawn'] = 'The initiator withdraws';

$lang->approvalflow->required['yes'] = 'Reviewer required';
$lang->approvalflow->required['no']  = 'Reviewer no required';

$lang->approvalflow->emptyName       = 'Please input name!';
$lang->approvalflow->passOverPercent = 'System judge pass percent over %d%%, result is pass';
$lang->approvalflow->failOverPercent = 'System judge reject percent don\'t reach %d%%, result is reject';

$lang->approvalflow->role = new stdclass();
$lang->approvalflow->role->create = 'Create Role';
$lang->approvalflow->role->browse = 'Role List';
$lang->approvalflow->role->edit   = 'Edit Role';
$lang->approvalflow->role->member = 'Member';
$lang->approvalflow->role->delete = 'Delete Role';

$lang->approvalflow->role->name   = 'Role Name';
$lang->approvalflow->role->code   = 'Role Code';
$lang->approvalflow->role->desc   = 'Role Description';
