<?php
$lang->doc->templateType         = 'Template Type';
$lang->doc->template             = 'Templates';
$lang->doc->selectTemplate       = 'Select Template';
$lang->doc->createByTemplate     = 'Create By Template';
$lang->doc->assignedTo           = 'Assigned To';
$lang->doc->approver             = 'Approver';
$lang->doc->importToPracticeLib  = 'Import To Parcitce Lib';
$lang->doc->importToComponentLib = 'Import To Component Lib';
$lang->doc->practiceLib          = 'Practice Lib';
$lang->doc->componentLib         = 'Component Lib';
$lang->doc->isExistPracticeLib   = 'This doc is already added in the Practice Lib. Please do not add it again.';
$lang->doc->isExistComponentLib  = 'This doc is already added in the Component Lib. Please do not add it again';
$lang->doc->replaceContentTip    = 'Are you sure you want to use this template? After using the template, the text will be cleared.';
$lang->doc->selectExecution      = 'Select Execution';
$lang->doc->selectProduct        = 'Select Product';
$lang->doc->selectProject        = 'Select Project';

$lang->docTemplate->filter     = 'Filter';
$lang->docTemplate->param      = 'Set Params';
$lang->docTemplate->searchTab  = 'Search Tab';
$lang->docTemplate->zentaoData = 'Zentao List Data Config';
$lang->docTemplate->filterTip  = 'Use the search tab to quickly configure the corresponding object filter.';
$lang->docTemplate->paramTip   = 'This block displays the corresponding list data according to the configured parameters and the configuration of the object filter in the selected template.';
$lang->docTemplate->configTip  = "When using this template, this block will display the corresponding %s data according to the filter configuration.";
$lang->docTemplate->next       = 'Next';
$lang->docTemplate->noPriv     = 'You do not have %s permissions.';

$lang->doc->docLang->createByTemplate = $lang->doc->createByTemplate;

$lang->docTemplate->zentaoList = array();
$lang->docTemplate->zentaoList['story']          = $lang->SRCommon;
$lang->docTemplate->zentaoList['productStory']   = $lang->productCommon . $lang->SRCommon;
$lang->docTemplate->zentaoList['projectStory']   = $lang->projectCommon . $lang->SRCommon;
$lang->docTemplate->zentaoList['executionStory'] = $lang->execution->common . $lang->SRCommon;

$lang->docTemplate->zentaoList['design'] = $lang->design->common;
$lang->docTemplate->zentaoList['HLDS']   = $lang->design->HLDS;
$lang->docTemplate->zentaoList['DDS']    = $lang->design->DDS;
$lang->docTemplate->zentaoList['DBDS']   = $lang->design->DBDS;
$lang->docTemplate->zentaoList['ADS']    = $lang->design->ADS;

$lang->docTemplate->zentaoList['task']         = $lang->task->common;
$lang->docTemplate->zentaoList['case']         = $lang->testcase->common;
$lang->docTemplate->zentaoList['productCase']  = $lang->productCommon . $lang->testcase->common;
$lang->docTemplate->zentaoList['projectCase']  = $lang->projectCommon . $lang->testcase->common;
$lang->docTemplate->zentaoList['bug']          = $lang->bug->common;
$lang->docTemplate->zentaoList['gantt']       = 'Gantt Chart';

$lang->docTemplate->searchTabList = array();
$lang->docTemplate->searchTabList['productStory'] = array();
$lang->docTemplate->searchTabList['productStory']['allstory']       = 'All';
$lang->docTemplate->searchTabList['productStory']['unclosed']       = 'Unclosed';
$lang->docTemplate->searchTabList['productStory']['draftstory']     = 'Draft';
$lang->docTemplate->searchTabList['productStory']['activestory']    = 'Activated';
$lang->docTemplate->searchTabList['productStory']['changingstory']  = 'Changing';
$lang->docTemplate->searchTabList['productStory']['reviewingstory'] = 'Reviewing';
$lang->docTemplate->searchTabList['productStory']['willclose']      = 'ToBeClosed';
$lang->docTemplate->searchTabList['productStory']['closedstory']    = 'Closed';
$lang->docTemplate->searchTabList['productStory']['feedback']       = 'From Feedback';

$lang->docTemplate->searchTabList['projectStory'] = array();
$lang->docTemplate->searchTabList['projectStory']['allstory']          = 'All';
$lang->docTemplate->searchTabList['projectStory']['unclosed']          = 'Unclosed';
$lang->docTemplate->searchTabList['projectStory']['draft']             = 'Draft';
$lang->docTemplate->searchTabList['projectStory']['reviewing']         = 'Reviewing';
$lang->docTemplate->searchTabList['projectStory']['changing']          = 'Changing';
$lang->docTemplate->searchTabList['projectStory']['closed']            = 'Closed';
$lang->docTemplate->searchTabList['projectStory']['linkedexecution']   = 'Linked' . $lang->execution->common;
$lang->docTemplate->searchTabList['projectStory']['unlinkedexecution'] = 'Unlinked' . $lang->execution->common;

$lang->docTemplate->searchTabList['executionStory'] = array();
$lang->docTemplate->searchTabList['executionStory']['all']       = 'All';
$lang->docTemplate->searchTabList['executionStory']['unclosed']  = 'Unclosed';
$lang->docTemplate->searchTabList['executionStory']['draft']     = 'Draft';
$lang->docTemplate->searchTabList['executionStory']['reviewing'] = 'Reviewing';

$lang->docTemplate->searchTabList['task'] = array();
$lang->docTemplate->searchTabList['task']['all']         = 'All';
$lang->docTemplate->searchTabList['task']['unclosed']    = 'Unclosed';
$lang->docTemplate->searchTabList['task']['needconfirm'] = "{$lang->SRCommon} Changed";
$lang->docTemplate->searchTabList['task']['wait']        = 'Waiting';
$lang->docTemplate->searchTabList['task']['doing']       = 'Doing';
$lang->docTemplate->searchTabList['task']['undone']      = 'Unfinished';
$lang->docTemplate->searchTabList['task']['done']        = 'Done';
$lang->docTemplate->searchTabList['task']['closed']      = 'Closed';
$lang->docTemplate->searchTabList['task']['cancel']      = 'Cancelled';
$lang->docTemplate->searchTabList['task']['delayed']     = 'Delayed';

$lang->docTemplate->searchTabList['bug']['all']           = 'All';
$lang->docTemplate->searchTabList['bug']['unclosed']      = 'Unclosed';
$lang->docTemplate->searchTabList['bug']['unresolved']    = 'Active';
$lang->docTemplate->searchTabList['bug']['unconfirmed']   = 'Unconfirmed';
$lang->docTemplate->searchTabList['bug']['assigntonull']  = 'Unassigned';
$lang->docTemplate->searchTabList['bug']['longlifebugs']  = 'Stalled';
$lang->docTemplate->searchTabList['bug']['toclosed']      = 'ToBeClosed';
$lang->docTemplate->searchTabList['bug']['postponedbugs'] = 'Postponed';
$lang->docTemplate->searchTabList['bug']['overduebugs']   = 'Overdue';
$lang->docTemplate->searchTabList['bug']['needconfirm']   = "{$lang->SRCommon} Changed";
$lang->docTemplate->searchTabList['bug']['feedback']      = 'From Feedback';

$lang->docTemplate->searchTabList['productCase'] = array();
$lang->docTemplate->searchTabList['productCase']['all']         = 'All';
$lang->docTemplate->searchTabList['productCase']['wait']        = 'Waiting';
$lang->docTemplate->searchTabList['productCase']['needconfirm'] = "{$lang->common->story} Changed";

$lang->docTemplate->searchTabList['projectCase'] = array();
$lang->docTemplate->searchTabList['projectCase']['all']         = 'All';
$lang->docTemplate->searchTabList['projectCase']['wait']        = 'Waiting';
$lang->docTemplate->searchTabList['projectCase']['needconfirm'] = "{$lang->common->story} Changed";

$lang->docTemplate->searchTabList['HLDS'] = array();
$lang->docTemplate->searchTabList['HLDS']['all'] = 'All';

$lang->docTemplate->searchTabList['DDS'] = array();
$lang->docTemplate->searchTabList['DDS']['all'] = 'All';

$lang->docTemplate->searchTabList['DBDS'] = array();
$lang->docTemplate->searchTabList['DBDS']['all'] = 'All';

$lang->docTemplate->searchTabList['ADS'] = array();
$lang->docTemplate->searchTabList['ADS']['all'] = 'All';

$lang->doc->featureBar['selecttemplate'] = array();
$lang->doc->featureBar['selecttemplate']['all'] = 'All';
