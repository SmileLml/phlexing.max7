<?php
global $lang, $app;
$app->loadLang('bug');
$config->excel->testcase = new stdclass();
$config->excel->testcase->initField['stepDesc']   = "1. \n2. \n3. ";
$config->excel->testcase->initField['stepExpect'] = "1. \n2. \n3. ";

$config->excel->caselib = new stdclass();
$config->excel->caselib->initField['stepDesc']   = "1. \n2. \n3. ";
$config->excel->caselib->initField['stepExpect'] = "1. \n2. \n3. ";

$config->excel->bug = new stdclass();
$config->excel->bug->initField['steps']       = str_replace(array('<p>', '</p>'), array('', "\n"), $lang->bug->tplStep . $lang->bug->tplResult . $lang->bug->tplExpect);
$config->excel->bug->initField['openedBuild'] = $lang->trunk . '(#trunk)';

$config->excel->freeze           = new stdclass();
$config->excel->freeze->testcase = 'title';
$config->excel->freeze->bug      = 'title';
$config->excel->freeze->task     = 'name';
$config->excel->freeze->story    = 'title';
$config->excel->freeze->tree     = 'title';
$config->excel->freeze->todo     = 'name';
$config->excel->freeze->leave    = 'id';
$config->excel->freeze->makeup   = 'id';
$config->excel->freeze->overtime = 'id';

$config->excel->tipsHeight = new stdclass();
$config->excel->tipsHeight->task        = '70';
$config->excel->tipsHeight->story       = '30';
$config->excel->tipsHeight->epic        = $config->excel->tipsHeight->story;
$config->excel->tipsHeight->requirement = $config->excel->tipsHeight->story;
