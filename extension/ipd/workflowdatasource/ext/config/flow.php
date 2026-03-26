<?php
$config->workflowdatasource->methods = array();
$config->workflowdatasource->methods['branch']      = array('getPairs');
$config->workflowdatasource->methods['bug']         = array('getUserBugPairs', 'getProductBugPairs', 'getBugInfoFromResult');
$config->workflowdatasource->methods['build']       = array('getBuildPairs');
$config->workflowdatasource->methods['dept']        = array('getOptionMenu', 'getAllChildID', 'getDeptUserPairs');
$config->workflowdatasource->methods['group']       = array('getPairs', 'getUserPairs');
$config->workflowdatasource->methods['message']     = array('getObjectTypes');
$config->workflowdatasource->methods['product']     = array('getPairs', 'getTeamMemberPairs');
$config->workflowdatasource->methods['productplan'] = array('getPairs', 'getPairsForStory', 'getForProducts');
$config->workflowdatasource->methods['program']     = array('getPairs', 'getProductPairs');
$config->workflowdatasource->methods['project']     = array('getPairsByProgram', 'getPairsByModel');
$config->workflowdatasource->methods['execution']   = array('getPairs', 'getTeams2Import');
$config->workflowdatasource->methods['search']      = array('getQueryPairs');
$config->workflowdatasource->methods['story']       = array('getProductStoryPairs', 'getExecutionStoryPairs', 'getUserStoryPairs', 'getVersions');
$config->workflowdatasource->methods['task']        = array('getParentTaskPairs', 'getUserTaskPairs', 'getMemberPairs');
$config->workflowdatasource->methods['testcase']    = array('getStoryCaseCounts');
$config->workflowdatasource->methods['caselib']     = array('getLibraries');
$config->workflowdatasource->methods['testtask']    = array('getRelatedTestTasks');
$config->workflowdatasource->methods['tree']        = array('getOptionMenu', 'getModulePairs', 'getTaskOptionMenu', 'getTaskTreeModules', 'getAllChildID', 'getProjectModule', 'getModulesName');
$config->workflowdatasource->methods['user']        = array('getPairs', 'getCommiters', 'getUserRoles', 'getGroups', 'getContactLists', 'getListByAccount', 'getContactUserPairs', 'getTeamMemberPairs');

$config->workflowdatasource->langList = array();
$config->workflowdatasource->langList['bugSeverity']   = array('module' => 'bug', 'field' => 'severityList');
$config->workflowdatasource->langList['bugPri']        = array('module' => 'bug', 'field' => 'priList');
$config->workflowdatasource->langList['bugOs']         = array('module' => 'bug', 'field' => 'osList');
$config->workflowdatasource->langList['bugBrowser']    = array('module' => 'bug', 'field' => 'browserList');
$config->workflowdatasource->langList['bugType']       = array('module' => 'bug', 'field' => 'typeList');
$config->workflowdatasource->langList['bugStatus']     = array('module' => 'bug', 'field' => 'statusList');
$config->workflowdatasource->langList['bugResolution'] = array('module' => 'bug', 'field' => 'resolutionList');

$config->workflowdatasource->langList['productType']   = array('module' => 'product', 'field' => 'typeList');
$config->workflowdatasource->langList['productAcl']    = array('module' => 'product', 'field' => 'aclList');
$config->workflowdatasource->langList['productStatus'] = array('module' => 'product', 'field' => 'statusList');

$config->workflowdatasource->langList['projectType']   = array('module' => 'execution', 'field' => 'typeList');
$config->workflowdatasource->langList['projectAcl']    = array('module' => 'execution', 'field' => 'aclList');
$config->workflowdatasource->langList['projectStatus'] = array('module' => 'execution', 'field' => 'statusList');
$config->workflowdatasource->langList['projectModel']  = array('module' => 'project',   'field' => 'modelList');

$config->workflowdatasource->langList['releaseStatus'] = array('module' => 'release', 'field' => 'statusList');

$config->workflowdatasource->langList['storyStatus']       = array('module' => 'story', 'field' => 'statusList');
$config->workflowdatasource->langList['storyType']         = array('module' => 'story', 'field' => 'typeList');
$config->workflowdatasource->langList['storyStage']        = array('module' => 'story', 'field' => 'stageList');
$config->workflowdatasource->langList['storyReason']       = array('module' => 'story', 'field' => 'reasonList');
$config->workflowdatasource->langList['storyReviewResult'] = array('module' => 'story', 'field' => 'reviewResultList');
$config->workflowdatasource->langList['storySource']       = array('module' => 'story', 'field' => 'sourceList');
$config->workflowdatasource->langList['storyPri']          = array('module' => 'story', 'field' => 'priList');

$config->workflowdatasource->langList['taskStatus'] = array('module' => 'task', 'field' => 'statusList');
$config->workflowdatasource->langList['taskType']   = array('module' => 'task', 'field' => 'typeList');
$config->workflowdatasource->langList['taskPri']    = array('module' => 'task', 'field' => 'priList');
$config->workflowdatasource->langList['taskReason'] = array('module' => 'task', 'field' => 'reasonList');

$config->workflowdatasource->langList['testcasePri']    = array('module' => 'testcase', 'field' => 'priList');
$config->workflowdatasource->langList['testcaseType']   = array('module' => 'testcase', 'field' => 'typeList');
$config->workflowdatasource->langList['testcaseStage']  = array('module' => 'testcase', 'field' => 'stageList');
$config->workflowdatasource->langList['testcaseStatus'] = array('module' => 'testcase', 'field' => 'statusList');
$config->workflowdatasource->langList['testcaseResult'] = array('module' => 'testcase', 'field' => 'resultList');

$config->workflowdatasource->langList['testsuiteAuth'] = array('module' => 'testsuite', 'field' => 'authorList');

$config->workflowdatasource->langList['testtaskStatus'] = array('module' => 'testtask', 'field' => 'statusList');
$config->workflowdatasource->langList['testtaskPri']    = array('module' => 'testtask', 'field' => 'priList');

$config->workflowdatasource->langList['todoStatus'] = array('module' => 'todo', 'field' => 'statusList');
$config->workflowdatasource->langList['todoPri']    = array('module' => 'todo', 'field' => 'priList');
$config->workflowdatasource->langList['todoType']   = array('module' => 'todo', 'field' => 'typeList');

$config->workflowdatasource->langList['userRole']   = array('module' => 'user', 'field' => 'roleList');
$config->workflowdatasource->langList['userGender'] = array('module' => 'user', 'field' => 'genderList');
$config->workflowdatasource->langList['userStatus'] = array('module' => 'user', 'field' => 'statusList');

$config->workflowdatasource->langList['feedbackStatus']       = array('module' => 'feedback', 'field' => 'statusList');
$config->workflowdatasource->langList['feedbackType']         = array('module' => 'feedback', 'field' => 'typeList');
$config->workflowdatasource->langList['feedbackSolution']     = array('module' => 'feedback', 'field' => 'solutionList');
$config->workflowdatasource->langList['feedbackclosedReason'] = array('module' => 'feedback', 'field' => 'closedReasonList');
$config->workflowdatasource->langList['feedbackPri']          = array('module' => 'feedback', 'field' => 'priList');

$config->workflowdatasource->langList['storyClosedReason'] = array('module' => 'story', 'field' => 'reasonList');

$config->workflowdatasource->langList['ticketType']   = array('module' => 'ticket', 'field' => 'typeList');
$config->workflowdatasource->langList['ticketPri']    = array('module' => 'ticket', 'field' => 'priList');
$config->workflowdatasource->langList['ticketStatus'] = array('module' => 'ticket', 'field' => 'statusList');

$config->workflowdatasource->langList['demandPri']      = array('module' => 'demand', 'field' => 'priList');
$config->workflowdatasource->langList['demandSource']   = array('module' => 'demand', 'field' => 'sourceList');
$config->workflowdatasource->langList['demandCategory'] = array('module' => 'demand', 'field' => 'categoryList');
$config->workflowdatasource->langList['demandStatus']   = array('module' => 'demand', 'field' => 'statusList');
$config->workflowdatasource->langList['demandDuration'] = array('module' => 'demand', 'field' => 'durationList');
$config->workflowdatasource->langList['demandBSA']      = array('module' => 'demand', 'field' => 'bsaList');

$config->workflowdatasource->langList['charterLevel']        = array('module' => 'charter', 'field' => 'levelList');
$config->workflowdatasource->langList['charterCategory']     = array('module' => 'charter', 'field' => 'categoryList');
$config->workflowdatasource->langList['charterMarket']       = array('module' => 'charter', 'field' => 'marketList');
$config->workflowdatasource->langList['charterStatus']       = array('module' => 'charter', 'field' => 'statusList');
$config->workflowdatasource->langList['charterCloseReason']  = array('module' => 'charter', 'field' => 'closeReasonList');
$config->workflowdatasource->langList['charterReviewResult'] = array('module' => 'charter', 'field' => 'reviewResultList');
$config->workflowdatasource->langList['charterReviewStatus'] = array('module' => 'charter', 'field' => 'reviewStatusList');
