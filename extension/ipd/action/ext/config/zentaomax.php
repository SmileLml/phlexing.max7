<?php
$config->action->objectNameFields['review']         = 'title';
$config->action->objectNameFields['opportunity']    = 'name';
$config->action->objectNameFields['trainplan']      = 'name';
$config->action->objectNameFields['gapanalysis']    = 'account';
$config->action->objectNameFields['auditcl']        = 'title';
$config->action->objectNameFields['reviewissue']    = 'title';
$config->action->objectNameFields['meeting']        = 'name';
$config->action->objectNameFields['meetingroom']    = 'name';
$config->action->objectNameFields['researchplan']   = 'name';
$config->action->objectNameFields['researchreport'] = 'title';
$config->action->objectNameFields['assetlib']       = 'name';
$config->action->objectNameFields['storylib']       = 'name';
$config->action->objectNameFields['risklib']        = 'name';
$config->action->objectNameFields['issuelib']       = 'name';
$config->action->objectNameFields['opportunitylib'] = 'name';
$config->action->objectNameFields['practicelib']    = 'name';
$config->action->objectNameFields['componentlib']   = 'name';
$config->action->objectNameFields['nc']             = 'title';
$config->action->objectNameFields['auditresult']    = 'comment';
$config->action->objectNameFields['process']        = 'name';
$config->action->objectNameFields['activity']       = 'name';
$config->action->objectNameFields['zoutput']        = 'name';
$config->action->objectNameFields['basicmeas']      = 'name';
$config->action->objectNameFields['measurement']    = 'name';
$config->action->objectNameFields['sqlview']        = 'name';
$config->action->objectNameFields['cmcl']           = 'contents';
$config->action->objectNameFields['rule']           = 'name';

$config->action->assetType = ',story,issue,risk,opportunity,doc,';

$config->action->assetViewMethod['story']       = 'storyView';
$config->action->assetViewMethod['issue']       = 'issueView';
$config->action->assetViewMethod['risk']        = 'riskView';
$config->action->assetViewMethod['opportunity'] = 'opportunityView';
