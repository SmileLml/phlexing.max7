<?php
$lang->custom->libreOffice       = 'Office Convert';
$lang->custom->libreOfficeTurnon = 'LibreOffice';
$lang->custom->type              = 'Type';
$lang->custom->libreOfficePath   = 'Soffice Path';
$lang->custom->collaboraPath     = 'Collabora Path';

$lang->custom->errorSofficePath   = 'Soffice file does not exist.';
$lang->custom->errorRunSoffice    = "Failed to run soffice. Error: %s";
$lang->custom->errorRunCollabora  = "Failed to connect to Collabora. Check Collabora settings and see whether it is connected to the network.";
$lang->custom->cannotUseCollabora = "If you choose Collabora, ZenTao must be configured in static access.";

$lang->custom->turnonList[1] = 'On';
$lang->custom->turnonList[0] = 'Off';

$lang->custom->typeList['libreoffice'] = 'LibreOffice';
$lang->custom->typeList['collabora']   = 'Collabora Online';

$lang->custom->sofficePlaceholder   = 'Write the path for soffice in LibreOffice, e.g. /opt/libreoffice/program/soffice';
$lang->custom->collaboraPlaceholder = 'Write the path for Collabora URL, e.g. https://127.0.0.1:9980';

$lang->custom->feedback = new stdclass();
$lang->custom->feedback->fields['required']         = $lang->custom->required;
$lang->custom->feedback->fields['review']           = 'Feedback Review';
$lang->custom->feedback->fields['closedReasonList'] = 'Closed Reason';
$lang->custom->feedback->fields['typeList']         = 'Type List';
$lang->custom->feedback->fields['priList']          = 'Pri List';

$lang->custom->ticket = new stdclass();
$lang->custom->ticket->fields['required']         = $lang->custom->required;
$lang->custom->ticket->fields['priList']          = 'Pri List';
$lang->custom->ticket->fields['typeList']         = 'Type List';
$lang->custom->ticket->fields['closedReasonList'] = 'Closed Reason';

$lang->custom->browseRelation    = "Browse Relation List";
$lang->custom->createRelation    = "Create Relation";
$lang->custom->editRelation      = "Edit Relation";
$lang->custom->deleteRelation    = "Delete Relation";
$lang->custom->relativeRelation  = "Relative Relation";
$lang->custom->relationTip       = "Users can configure relations between stories, tasks, bugs, cases, documents, designs, issues, risks, submissions, feedback, tickets, and workflow objects. 'Relation' and 'Relative Relation' are bidirectional, For example, if A is Dependence B, then B is Depended On A.";
$lang->custom->hasRelationTip    = '"%s" relationship already exists in the system, do you still want to save it?';
$lang->custom->relation          = 'Relation';
$lang->custom->relateObject      = 'Related Objects';
$lang->custom->removeObjects     = 'Remove Objects';
$lang->custom->removeObjectTip   = 'Are you sure to dissolve this relation?';
$lang->custom->deleteRelationTip = 'This relationship is already configured for objects in the system and cannot be deleted.';
$lang->custom->defaultRelation   = 'The built-in relationships follow the user operation process records, and cannot be unlinked here.';
$lang->custom->relationGraph     = 'Relation Graph';

$lang->custom->relationList = array();
$lang->custom->relationList['transferredto']   = 'Transferred To';
$lang->custom->relationList['transferredfrom'] = 'Transferred From';
$lang->custom->relationList['twin']            = 'Twin';
$lang->custom->relationList['subdivideinto']   = 'Subdivide Into';
$lang->custom->relationList['subdividefrom']   = 'Subdivide From';
$lang->custom->relationList['generated']       = 'Generated';
$lang->custom->relationList['derivedfrom']     = 'Derived From';
$lang->custom->relationList['completedin']     = 'Completed From';
$lang->custom->relationList['completedfrom']   = 'Completed';
$lang->custom->relationList['interrated']      = 'Related to';

$lang->custom->setCharterInfo   = 'Set Charter';
$lang->custom->resetCharterInfo = 'Reset Charter';

$lang->custom->charter = new stdclass();
$lang->custom->charter->level            = 'Project Level';
$lang->custom->charter->projectApproval  = 'Project Approval Files';
$lang->custom->charter->completeApproval = 'Complete Approval Files';
$lang->custom->charter->cancelApproval   = 'Cancel Approval Files';

$lang->custom->charter->tips = new stdclass();
$lang->custom->charter->tips->sameLevel = 'The same project level already exists.';
$lang->custom->charter->tips->leastOne  = 'Please set up the information.';

$lang->custom->charterFiles = array();
$lang->custom->charterFiles['1'] = array('key' => '1', 'type' => 'plan', 'level' => '1', 'projectApproval'  => array(array('index' => 'BP', 'name' => 'BP'), array('index' => 'charter', 'name' => 'Charter'), array('index' => 'other', 'name' => 'Other')), 'completeApproval' => array(array('index' => 'summary', 'name' => 'Summary'), array('index' => 'finance', 'name' => 'Finance'), array('index' => 'assess', 'name' => 'Assess'), array('index' => 'other', 'name' => 'Other')), 'cancelApproval' => array(array('index' => 'reason', 'name' => 'Reason'), array('index' => 'finance', 'name' => 'Finance'), array('index' => 'assess', 'name' => 'Assess'), array('index' => 'other', 'name' => 'Other')));
$lang->custom->charterFiles['2'] = array('key' => '2', 'type' => 'plan', 'level' => '2', 'projectApproval'  => array(array('index' => 'BP', 'name' => 'BP'), array('index' => 'charter', 'name' => 'Charter'), array('index' => 'other', 'name' => 'Other')), 'completeApproval' => array(array('index' => 'summary', 'name' => 'Summary'), array('index' => 'finance', 'name' => 'Finance'), array('index' => 'assess', 'name' => 'Assess'), array('index' => 'other', 'name' => 'Other')), 'cancelApproval' => array(array('index' => 'reason', 'name' => 'Reason'), array('index' => 'finance', 'name' => 'Finance'), array('index' => 'assess', 'name' => 'Assess'), array('index' => 'other', 'name' => 'Other')));
$lang->custom->charterFiles['3'] = array('key' => '3', 'type' => 'plan', 'level' => '3', 'projectApproval'  => array(array('index' => 'BP', 'name' => 'BP'), array('index' => 'charter', 'name' => 'charter'), array('index' => 'other', 'name' => 'Other')), 'completeApproval' => array(array('index' => 'summary', 'name' => 'Summary'), array('index' => 'finance', 'name' => 'Finance'), array('index' => 'other', 'name' => 'Other')), 'cancelApproval' => array(array('index' => 'reason', 'name' => 'Reason'), array('index' => 'finance', 'name' => 'Finance'), array('index' => 'other', 'name' => 'Other')));
$lang->custom->charterFiles['4'] = array('key' => '4', 'type' => 'plan', 'level' => '4', 'projectApproval'  => array(array('index' => 'BP', 'name' => 'BP'), array('index' => 'charter', 'name' => 'charter'), array('index' => 'other', 'name' => 'Other')), 'completeApproval' => array(array('index' => 'summary', 'name' => 'Summary'), array('index' => 'finance', 'name' => 'Finance'), array('index' => 'other', 'name' => 'Other')), 'cancelApproval' => array(array('index' => 'reason', 'name' => 'Reason'), array('index' => 'finance', 'name' => 'Finance'), array('index' => 'other', 'name' => 'Other')));

$lang->custom->approvalflow = new stdclass();
$lang->custom->approvalflow->fields['browse'] = 'Approval Flow';
$lang->custom->approvalflow->fields['role']   = 'Role';
