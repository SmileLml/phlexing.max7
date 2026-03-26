<?php
$config->testcase->import         = new stdclass();
$config->testcase->listFields     = 'module,type,stage,pri,story,status,branch,results';
$config->testcase->cascade        = array('story' => 'module');
$config->testcase->templateFields = "product,branch,module,story,title,precondition,keywords,pri,type,stage,status,stepDesc,stepExpect";
$config->testcase->exportFields   = '
    id, product, branch, module, story, scene,
    title, precondition, stepDesc, stepExpect, real, keywords,
    pri, type, stage, status, bugs, results, stepNumber, lastRunner, lastRunDate, lastRunResult, openedBy, openedDate,
    lastEditedBy, lastEditedDate, version, linkCase, files';

