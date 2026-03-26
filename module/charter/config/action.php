<?php
global $lang;

$config->charter->actionList = array();
$config->charter->actionList['close']['icon']        = 'off';
$config->charter->actionList['close']['text']        = $lang->charter->close;
$config->charter->actionList['close']['hint']        = $lang->charter->abbr->close;
$config->charter->actionList['close']['url']         = array('module' => 'charter', 'method' => 'close', 'params' => 'charterID={id}');
$config->charter->actionList['close']['data-toggle'] = 'modal';
$config->charter->actionList['close']['class']       = 'charter-close-btn';

$config->charter->actionList['review']['icon']        = 'review';
$config->charter->actionList['review']['text']        = $lang->charter->approval;
$config->charter->actionList['review']['hint']        = $lang->charter->approval;
$config->charter->actionList['review']['url']         = array('module' => 'charter', 'method' => 'review', 'params' => 'charterID={id}');
$config->charter->actionList['review']['data-toggle'] = 'modal';
$config->charter->actionList['review']['class']       = 'charter-review-btn';

$config->charter->actionList['edit']['icon'] = 'edit';
$config->charter->actionList['edit']['text'] = $lang->charter->editAction;
$config->charter->actionList['edit']['hint'] = $lang->charter->editAction;
$config->charter->actionList['edit']['url']  = array('module' => 'charter', 'method' => 'edit', 'params' => 'charterID={id}');

$config->charter->actionList['delete']['icon']         = 'trash';
$config->charter->actionList['delete']['text']         = $lang->charter->deleteAction;
$config->charter->actionList['delete']['hint']         = $lang->charter->deleteAction;
$config->charter->actionList['delete']['url']          = array('module' => 'charter', 'method' => 'delete', 'params' => 'demandID={id}');
$config->charter->actionList['delete']['className']    = 'ajax-submit';
$config->charter->actionList['delete']['data-confirm'] = array('message' => $lang->charter->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->charter->actionList['delete']['notInModal']   = true;

$config->charter->actionList['projectApproval']['icon']        = 'play';
$config->charter->actionList['projectApproval']['text']        = $lang->charter->abbr->create;
$config->charter->actionList['projectApproval']['hint']        = $lang->charter->projectApproval;
$config->charter->actionList['projectApproval']['url']         = array('module' => 'charter', 'method' => 'projectApproval', 'params' => 'charterID={id}');
$config->charter->actionList['projectApproval']['data-toggle'] = 'modal';

$config->charter->actionList['completionApproval']['icon'] = 'checked';
$config->charter->actionList['completionApproval']['text'] = $lang->charter->abbr->finish;
$config->charter->actionList['completionApproval']['hint'] = $lang->charter->completionApproval;
$config->charter->actionList['completionApproval']['url']  = array('module' => 'charter', 'method' => 'completionApproval', 'params' => 'charterID={id}&from={from}');

$config->charter->actionList['cancelProjectApproval']['icon'] = 'cancel';
$config->charter->actionList['cancelProjectApproval']['text'] = $lang->charter->cancel;
$config->charter->actionList['cancelProjectApproval']['hint'] = $lang->charter->cancelProjectApproval;
$config->charter->actionList['cancelProjectApproval']['url']  = array('module' => 'charter', 'method' => 'cancelProjectApproval', 'params' => 'charterID={id}&from={from}');

$config->charter->actionList['activateProjectApproval']['icon']        = 'magic';
$config->charter->actionList['activateProjectApproval']['text']        = $lang->charter->activate;
$config->charter->actionList['activateProjectApproval']['hint']        = $lang->charter->activateProjectApproval;
$config->charter->actionList['activateProjectApproval']['url']         = array('module' => 'charter', 'method' => 'activateProjectApproval', 'params' => 'charterID={id}');
$config->charter->actionList['activateProjectApproval']['data-toggle'] = 'modal';

$config->charter->actionList['approvalCancel']['icon']      = 'back';
$config->charter->actionList['approvalCancel']['text']      = $lang->charter->approvalCancel;
$config->charter->actionList['approvalCancel']['hint']      = $lang->charter->approvalCancel;
$config->charter->actionList['approvalCancel']['url']       = array('module' => 'charter', 'method' => 'approvalCancel', 'params' => 'charterID={id}');
$config->charter->actionList['approvalCancel']['className'] = 'ajax-submit';

$config->charter->actionList['approvalProgress']['icon']        = 'review';
$config->charter->actionList['approvalProgress']['text']        = $lang->charter->approvalProgress;
$config->charter->actionList['approvalProgress']['hint']        = $lang->charter->approvalProgress;
$config->charter->actionList['approvalProgress']['url']         = array('module' => 'charter', 'method' => 'approvalProgress', 'params' => 'approvalID={approval}');
$config->charter->actionList['approvalProgress']['data-toggle'] = 'modal';

$config->charter->actionList['createProgram']['text']        = $lang->charter->createProgram;
$config->charter->actionList['createProgram']['hint']        = $lang->charter->createProgram;
$config->charter->actionList['createProgram']['url']         = array('module' => 'program', 'method' => 'create', 'params' => 'parentProgramID=0&charterID={id}');
$config->charter->actionList['createProgram']['data-size']   = 'lg';
$config->charter->actionList['createProgram']['data-toggle'] = 'modal';

$config->charter->actionList['createProject']['text']        = $lang->charter->createProject;
$config->charter->actionList['createProject']['hint']        = $lang->charter->createProject;
$config->charter->actionList['createProject']['url']         = array('module' => 'project', 'method' => 'createGuide', 'params' => 'programID=0&from=project&productID=0&branchID=0&charterID={id}');
$config->charter->actionList['createProject']['data-toggle'] = 'modal';

$config->charter->actionList['createProgramAndProject']['type']      = 'dropdown';
$config->charter->actionList['createProgramAndProject']['icon']      = 'plus';
$config->charter->actionList['createProgramAndProject']['hint']      = $lang->charter->createAB;
$config->charter->actionList['createProgramAndProject']['text']      = $lang->charter->createAB;
$config->charter->actionList['createProgramAndProject']['key']       = 'createProgramAndProject';
$config->charter->actionList['createProgramAndProject']['caret']     = 'up';
$config->charter->actionList['createProgramAndProject']['placement'] = 'top-end';
$config->charter->actionList['createProgramAndProject']['items']     = !common::hasPriv('project', 'create') ? array('createProgram') : array('createProgram', 'createProject');

$config->charter->actions = new stdclass();
$config->charter->actions->view = array();
$config->charter->actions->view['mainActions']   = array('projectApproval', 'completionApproval', 'cancelProjectApproval', 'activateProjectApproval', 'approvalCancel', 'review', 'close');
$config->charter->actions->view['suffixActions'] = array('edit', 'delete');
