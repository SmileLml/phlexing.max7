<?php
$lang->action->desc->createrequirements    = '$date, Decomposed ' . $lang->SRCommon . ' by <strong>$actor</strong> <strong>$extra</strong>。' . "\n";
$lang->action->desc->linked2process        = '$date, Linked process by <strong>$actor</strong>.';
$lang->action->desc->linked2activity       = '$date, Linked activity by <strong>$actor</strong>.';
$lang->action->desc->linked2output         = '$date, Linked output by <strong>$actor</strong>.';
$lang->action->desc->checked               = '$date, Checked by <strong>$actor</strong>';
$lang->action->desc->communicate           = '$date, Communicated by <strong>$actor</strong>.';
$lang->action->desc->designconfirmed       = '$date, Confirmed design by <strong>$actor</strong>, latest version <strong>#$extra</strong>。' . "\n";
$lang->action->desc->issueconfirmed        = '$date, Confirmed by <strong>$actor</strong>.' . "\n";
$lang->action->desc->minuted               = '$date, Minuted by <strong>$actor</strong>.' . "\n";
$lang->action->desc->import2storylib       = '$date, Imported by <strong>$actor</strong>.' . "\n";
$lang->action->desc->import2issuelib       = '$date, Imported by <strong>$actor</strong>.' . "\n";
$lang->action->desc->import2risklib        = '$date, Imported by <strong>$actor</strong>.' . "\n";
$lang->action->desc->import2opportunitylib = '$date, Imported by <strong>$actor</strong>.' . "\n";
$lang->action->desc->import2practicelib    = '$date, Imported by <strong>$actor</strong>.' . "\n";
$lang->action->desc->import2componentlib   = '$date, Imported by <strong>$actor</strong>.' . "\n";
$lang->action->desc->removed               = '$date, Removed by <strong>$actor</strong>' . "\n";
$lang->action->desc->reverted              = '$date, Reverted by <strong>$actor</strong>.' . "\n";
$lang->action->desc->forwarded             = '$date, Forwarded by <strong>$actor</strong>.' . "\n";
$lang->action->desc->addnode               = '$date, Added node by <strong>$actor</strong>.' . "\n";
$lang->action->desc->managedeliverable     = '$date, Managed deliverable by <strong>$actor</strong>.' . "\n";
$lang->action->desc->changeDeliverable     = 'Changed <strong><i>%s</i></strong> deliverable，old value is "%s"，new value is "%s"。<br />' . "\n";
$lang->action->desc->addDeliverable        = 'Added <strong><i>%s</i></strong> deliverable，new value is "%s"。<br />' . "\n";
$lang->action->desc->removeDeliverable     = 'Deleted <strong><i>%s</i></strong> deliverable，old value is "%s"。<br />' . "\n";

$lang->action->approve = new stdclass();
$lang->action->approve->pass   = '$date, Approved by <strong>$actor</strong>.' . 'The result is <strong>passed</strong>。' ."\n";
$lang->action->approve->reject = '$date, Approved by <strong>$actor</strong>.' . 'The result is <strong>rejected</strong>。' ."\n";

$lang->action->label->toaudit                  = 'submit audit';
$lang->action->label->issueconfirmed           = 'confirmed';
$lang->action->label->checked                  = 'checked';
$lang->action->label->editbasic                = 'edited';
$lang->action->label->minuted                  = 'minuted';
$lang->action->label->import2storylib          = "imported";
$lang->action->label->import2issuelib          = "imported";
$lang->action->label->import2risklib           = "imported";
$lang->action->label->import2opportunitylib    = "imported";
$lang->action->label->import2practicelib       = "imported";
$lang->action->label->import2componentlib      = "imported";
$lang->action->label->importfromstorylib       = 'imported';
$lang->action->label->importfromissuelib       = 'imported';
$lang->action->label->importfromrisklib        = 'imported';
$lang->action->label->importfromopportunitylib = 'imported';
$lang->action->label->approved                 = 'approved';
$lang->action->label->removed                  = 'removed';
$lang->action->label->recall                   = 'recalled';
$lang->action->label->audited                  = 'audited';
$lang->action->label->createbasic              = 'created';
$lang->action->label->submit                   = 'submited';
$lang->action->label->designconfirmed          = 'Confirmed design';
$lang->action->label->enabled                  = 'Enabled';
$lang->action->label->disabled                 = 'Disabled';
$lang->action->label->managedeliverable        = 'managed deliverable';

$lang->action->objectTypes['projecttemplate'] = 'Project Template';
$lang->action->objectTypes['review']          = 'Review';
$lang->action->objectTypes['waterfall']       = 'Waterfall Review';
$lang->action->objectTypes['approval']        = 'Approval';
$lang->action->objectTypes['approvalflow']    = 'Approval Flow';
$lang->action->objectTypes['opportunity']     = 'Opportunity';
$lang->action->objectTypes['trainplan']       = 'Trainplan';
$lang->action->objectTypes['meeting']         = 'Meeting';
$lang->action->objectTypes['meetingroom']     = 'Meeting Room';
$lang->action->objectTypes['gapanalysis']     = 'Gap Analysis';
$lang->action->objectTypes['auditcl']         = 'Auditcl';
$lang->action->objectTypes['auditplan']       = 'Audit Plan';
$lang->action->objectTypes['auditresult']     = 'Audit Result';
$lang->action->objectTypes['nc']              = 'Non-conformity';
$lang->action->objectTypes['measurement']     = 'Measurement';
$lang->action->objectTypes['cmcl']            = 'Configuration Checklist';
$lang->action->objectTypes['cm']              = 'Baseline';
$lang->action->objectTypes['reviewissue']     = 'Review Issue';
$lang->action->objectTypes['researchplan']    = 'Research Plan';
$lang->action->objectTypes['researchreport']  = 'Research Report';
$lang->action->objectTypes['assetlib']        = 'Asset Lib';
$lang->action->objectTypes['pssp']            = 'Process Tailoring';
$lang->action->objectTypes['reviewcl']        = 'Review';
$lang->action->objectTypes['activity']        = 'Activity';
$lang->action->objectTypes['zoutput']         = 'Document';
$lang->action->objectTypes['process']         = 'Process';
$lang->action->objectTypes['basicmeas']       = 'Basicmeas';
$lang->action->objectTypes['rule']            = 'Rule';

$lang->action->label->opportunity         = 'Opportunity|opportunity|view|opportunityID=%s';
$lang->action->label->trainplan           = 'Trainplan|trainplan|view|trainplanID=%s';
$lang->action->label->gapanalysis         = 'Gap Analysis|gapanalysis|view|gapanalysisID=%s';
$lang->action->label->auditcl             = 'Auditcl|auditcl|view|auditclID=%s';
$lang->action->label->review              = 'Review|review|view|reviewID=%s';
$lang->action->label->reviewissue         = 'Review Issue|reviewissue|view|reviewissueID=%s';
$lang->action->label->researchplan        = 'Research Plan|researchplan|view|planID=%s';
$lang->action->label->researchreport      = 'Research Report|researchreport|view|reportID=%s';
$lang->action->label->meeting             = 'Meeting|meeting|view|meetingID=%s';
$lang->action->label->meetingroom         = 'Meeting Room|meetingroom|view|roomID=%s';
$lang->action->label->storylib            = 'Story Lib|assetlib|story|libID=%s';
$lang->action->label->issuelib            = 'Issue Lib|assetlib|issue|libID=%s';
$lang->action->label->risklib             = 'Risk Lib|assetlib|risk|libID=%s';
$lang->action->label->opportunitylib      = 'Opportunity Lib|assetlib|opportunity|libID=%s';
$lang->action->label->practicelib         = 'Practice Lib|assetlib|practice|libID=%s';
$lang->action->label->componentlib        = 'Component Lib|assetlib|component|libID=%s';
$lang->action->label->storyassetlib       = 'Story Lib|assetlib|storyLibView|libID=%s';
$lang->action->label->issueassetlib       = 'Issue Lib|assetlib|issueLibView|libID=%s';
$lang->action->label->riskassetlib        = 'Risk Lib|assetlib|riskLibView|libID=%s';
$lang->action->label->opportunityassetlib = 'Opportunity Lib|assetlib|opportunityLibView|libID=%s';
$lang->action->label->practiceassetlib    = 'Practice Lib|assetlib|practiceLibView|libID=%s';
$lang->action->label->componentassetlib   = 'Component Lib|assetlib|componentLibView|libID=%s';
$lang->action->label->nc                  = 'Nc|nc|view|ncID=%s';
$lang->action->label->measurement         = 'Measurement|measurement|setSQL|ID=%s';
$lang->action->label->sqlview             = 'SQL View|sqlbuilder|browsesqlview|';
$lang->action->label->process             = 'Process|process|view|processID=%s';
$lang->action->label->activity            = 'Activity|activity|view|activityID=%s';
$lang->action->label->zoutput             = 'Document|zoutput|view|zoutputID=%s';
$lang->action->label->reverted            = 'Reverted';
$lang->action->label->forwarded           = 'Forwarded';
$lang->action->label->addnode             = 'Added node';

$lang->action->dynamicAction->researchplan['opened']  = 'Create Research Plan';
$lang->action->dynamicAction->researchplan['edited']  = 'Edit Research Plan';
$lang->action->dynamicAction->researchplan['deleted'] = 'Delete Research Plan';

$lang->action->dynamicAction->researchreport['opened']  = 'Create Research Report';
$lang->action->dynamicAction->researchreport['edited']  = 'Edit Research Report';
$lang->action->dynamicAction->researchreport['deleted'] = 'Delete Research Report';

$lang->action->dynamicAction->meetingroom['opened']  = 'Create Meeting Room';
$lang->action->dynamicAction->meetingroom['edited']  = 'Edit Meeting Room';
$lang->action->dynamicAction->meetingroom['deleted'] = 'Delete Meeting Room';

$lang->action->dynamicAction->meeting['opened']  = 'Create Meeting';
$lang->action->dynamicAction->meeting['edited']  = 'Edit Meeting';
$lang->action->dynamicAction->meeting['deleted'] = 'Delete Meeting';
$lang->action->dynamicAction->meeting['minuted'] = 'Minutes Meeting';

$lang->action->dynamicAction->reviewissue['deleted']  = 'Delete Reviewissue';

$lang->action->dynamicAction->story['import2storylib'] = 'Import Story Lib';
$lang->action->dynamicAction->story['approved']        = 'Approved Story';
$lang->action->dynamicAction->story['removed']         = 'Removed Story';
$lang->action->dynamicAction->story['importfromlib']   = 'Import From Story Lib';

$lang->action->dynamicAction->issue['import2issuelib'] = 'Import Issue Lib';
$lang->action->dynamicAction->issue['opened']          = 'Create Issue';
$lang->action->dynamicAction->issue['approved']        = 'Approve Issue';
$lang->action->dynamicAction->issue['edited']          = 'Edit Issue';
$lang->action->dynamicAction->issue['deleted']         = 'Delete Issue';
$lang->action->dynamicAction->issue['removed']         = 'Remove Issue';
$lang->action->dynamicAction->issue['importfromlib']   = 'Import From Issue Lib';
$lang->action->dynamicAction->issue['minuted']         = 'Minutes Issue Lib';
$lang->action->dynamicAction->issue['closed']          = 'Close Issue';
$lang->action->dynamicAction->issue['canceled']        = 'Cancel Issue';
$lang->action->dynamicAction->issue['assigned']        = 'Assign Issue';
$lang->action->dynamicAction->issue['resolved']        = 'Resolve Issue';
$lang->action->dynamicAction->issue['activated']       = 'Activate Issue';

$lang->action->dynamicAction->risk['import2risklib'] = 'Import Risk Lib';
$lang->action->dynamicAction->risk['opened']         = 'Create Risk';
$lang->action->dynamicAction->risk['edited']         = 'Edit Risk';
$lang->action->dynamicAction->risk['closed']         = 'Close Risk';
$lang->action->dynamicAction->risk['deleted']        = 'Delete Risk';
$lang->action->dynamicAction->risk['activated']      = 'Activate Risk';
$lang->action->dynamicAction->risk['canceled']       = 'Cancel Risk';
$lang->action->dynamicAction->risk['assigned']       = 'Assign Risk';
$lang->action->dynamicAction->risk['hangup']         = 'Hangup Risk';
$lang->action->dynamicAction->risk['tracked']        = 'track Risk';
$lang->action->dynamicAction->risk['minuted']        = 'Minutes Risk Log';
$lang->action->dynamicAction->risk['importfromlib']  = 'Import From Risk Lib';

$lang->action->dynamicAction->opportunity['import2opportunitylib'] = 'Import Opportunity Lib';
$lang->action->dynamicAction->opportunity['opened']                = 'Create Opportunity';
$lang->action->dynamicAction->opportunity['edited']                = 'Edit Opportunity';
$lang->action->dynamicAction->opportunity['closed']                = 'Close Opportunity';
$lang->action->dynamicAction->opportunity['deleted']               = 'Delete Opportunity';
$lang->action->dynamicAction->opportunity['canceled']              = 'Cancel Opportunity';
$lang->action->dynamicAction->opportunity['assigned']              = 'Assign Opportunity';
$lang->action->dynamicAction->opportunity['hangup']                = 'Hangup Opportunity';
$lang->action->dynamicAction->opportunity['tracked']               = 'track Opportunity';
$lang->action->dynamicAction->opportunity['activated']             = 'Activate Opportunity';
$lang->action->dynamicAction->opportunity['importfromlib']         = 'Import From Opportunity Lib';

$lang->action->dynamicAction->auditplan['opened']   = 'Create Auditplan';
$lang->action->dynamicAction->auditplan['edited']   = 'Edit Auditplan';
$lang->action->dynamicAction->auditplan['deleted']  = 'Delete Auditplan';
$lang->action->dynamicAction->auditplan['checked']  = 'Check Auditplan';
$lang->action->dynamicAction->auditplan['assigned'] = 'Assign Auditplan';

$lang->action->dynamicAction->nc['opened']    = 'Create Nc';
$lang->action->dynamicAction->nc['deleted']   = 'Delete Nc';
$lang->action->dynamicAction->nc['eidted']    = 'Edit Nc';
$lang->action->dynamicAction->nc['activated'] = 'Activate Nc';

$lang->action->dynamicAction->pssp['opened']  = 'Process Tailoring';

$lang->action->dynamicAction->doc['import2practicelib']  = 'Import Practice Lib';
$lang->action->dynamicAction->doc['import2componentlib'] = 'Import Component Lib';
$lang->action->dynamicAction->doc['approved']            = 'Approved';
$lang->action->dynamicAction->doc['removed']             = 'Removed';
$lang->action->dynamicAction->doc['assigned']            = 'AssignedTo';

$lang->action->dynamicAction->assetlib['opened'] = 'Created Lib';
$lang->action->dynamicAction->assetlib['edited'] = 'Edited Lib';

if(!helper::hasFeature('meeting'))
{
    unset($lang->action->dynamicAction->meetingroom);
    unset($lang->action->dynamicAction->meeting);
}
if(!helper::hasFeature('process')) unset($lang->action->dynamicAction->pssp);
if(!helper::hasFeature('auditplan'))
{
    unset($lang->action->dynamicAction->auditplan);
    unset($lang->action->dynamicAction->nc);
}
if(!helper::hasFeature('opportunity')) unset($lang->action->dynamicAction->opportunity);
if(!helper::hasFeature('issue'))       unset($lang->action->dynamicAction->issue);
if(!helper::hasFeature('risk'))        unset($lang->action->dynamicAction->risk);
if(!helper::hasFeature('storylib'))
{
    unset($lang->action->dynamicAction->story['import2storylib']);
    unset($lang->action->dynamicAction->story['importfromlib']);
}
if(!helper::hasFeature('issuelib'))
{
    unset($lang->action->dynamicAction->issue['import2issuelib']);
    unset($lang->action->dynamicAction->issue['importfromlib']);
}
if(!helper::hasFeature('risklib'))
{
    unset($lang->action->dynamicAction->risk['import2risklib']);
    unset($lang->action->dynamicAction->risk['importfromlib']);
}
if(!helper::hasFeature('opportunitylib'))
{
    unset($lang->action->dynamicAction->opportunity['import2opportunitylib']);
    unset($lang->action->dynamicAction->opportunity['importfromlib']);
}
if(!helper::hasFeature('practicelib'))  unset($lang->action->dynamicAction->doc['import2practicelib']);
if(!helper::hasFeature('componentlib')) unset($lang->action->dynamicAction->doc['import2componentlib']);
