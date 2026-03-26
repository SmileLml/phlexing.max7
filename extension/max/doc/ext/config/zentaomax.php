<?php
$config->doc->editor->assignto = array('id' => 'comment', 'tools' => 'simpleTools');

$config->docTemplate = new stdclass();
$config->docTemplate->zentaoList = array();
$config->docTemplate->zentaoList['story']  = array('key' => 'story',  'name' => $lang->docTemplate->zentaoList['story'] . $lang->docTemplate->filter,  'icon' => 'lightbulb',  'subMenu' => array(), 'reviewObject' => 'SRS');
$config->docTemplate->zentaoList['design'] = array('key' => 'design', 'name' => $lang->docTemplate->zentaoList['design'] . $lang->docTemplate->filter, 'icon' => 'design',     'subMenu' => array(), 'reviewObject' => 'HLDS,DDS,DBDS,ADS');
$config->docTemplate->zentaoList['task']   = array('key' => 'task',   'name' => $lang->docTemplate->zentaoList['task'] . $lang->docTemplate->filter,   'icon' => 'check-sign', 'modalSize' => 'sm', 'module' => 'doc', 'method' => 'buildzentaoconfig', 'params' => 'type=task');
$config->docTemplate->zentaoList['case']   = array('key' => 'case',   'name' => $lang->docTemplate->zentaoList['case'] . $lang->docTemplate->filter,   'icon' => 'sitemap',    'modalSize' => 'sm', 'module' => 'doc', 'method' => 'buildzentaoconfig', 'params' => 'type=case', 'reviewObject' => 'STTC,ITTC');
$config->docTemplate->zentaoList['bug']    = array('key' => 'bug',    'name' => $lang->docTemplate->zentaoList['bug'] . $lang->docTemplate->filter,    'icon' => 'bug',        'modalSize' => 'sm', 'module' => 'doc', 'method' => 'buildzentaoconfig', 'params' => 'type=bug');
$config->docTemplate->zentaoList['gantt']  = array('key' => 'gantt',  'name' => $lang->docTemplate->zentaoList['gantt'] . $lang->docTemplate->filter,  'icon' => 'gantt',      'isModal' => false, 'module' => 'doc', 'method' => 'buildzentaoconfig', 'params' => 'type=gantt', 'reviewObject' => 'PP');

$config->docTemplate->zentaoList['story']['subMenu'][] = array('key' => 'productStory',   'name' => $lang->docTemplate->zentaoList['productStory'] . $lang->docTemplate->filter,   'icon' => 'lightbulb-alt', 'modalSize' => 'sm', 'module' => 'doc', 'method' => 'buildZentaoConfig', 'params' => 'type=productStory');
$config->docTemplate->zentaoList['story']['subMenu'][] = array('key' => 'projectStory',   'name' => $lang->docTemplate->zentaoList['projectStory'] . $lang->docTemplate->filter,   'icon' => 'project',       'modalSize' => 'sm', 'module' => 'doc', 'method' => 'buildZentaoConfig', 'params' => 'type=projectStory', 'reviewObject' => 'SRS');
$config->docTemplate->zentaoList['story']['subMenu'][] = array('key' => 'executionStory', 'name' => $lang->docTemplate->zentaoList['executionStory'] . $lang->docTemplate->filter, 'icon' => 'run',           'modalSize' => 'sm', 'module' => 'doc', 'method' => 'buildZentaoConfig', 'params' => 'type=executionStory');

$config->docTemplate->zentaoList['design']['subMenu'][] = array('key' => 'HLDS', 'name' => $lang->docTemplate->zentaoList['HLDS'] . $lang->docTemplate->filter, 'icon' => 'list-alt',       'modalSize' => 'sm', 'module' => 'doc', 'method' => 'buildZentaoConfig', 'params' => 'type=HLDS', 'reviewObject' => 'HLDS');
$config->docTemplate->zentaoList['design']['subMenu'][] = array('key' => 'DDS',  'name' => $lang->docTemplate->zentaoList['DDS'] . $lang->docTemplate->filter,  'icon' => 'audit',          'modalSize' => 'sm', 'module' => 'doc', 'method' => 'buildZentaoConfig', 'params' => 'type=DDS', 'reviewObject' => 'DDS');
$config->docTemplate->zentaoList['design']['subMenu'][] = array('key' => 'DBDS', 'name' => $lang->docTemplate->zentaoList['DBDS'] . $lang->docTemplate->filter, 'icon' => 'data-structure', 'modalSize' => 'sm', 'module' => 'doc', 'method' => 'buildZentaoConfig', 'params' => 'type=DBDS', 'reviewObject' => 'DBDS');
$config->docTemplate->zentaoList['design']['subMenu'][] = array('key' => 'ADS',  'name' => $lang->docTemplate->zentaoList['ADS'] . $lang->docTemplate->filter,  'icon' => 'interface-lib',  'modalSize' => 'sm', 'module' => 'doc', 'method' => 'buildZentaoConfig', 'params' => 'type=ADS', 'reviewObject' => 'ADS');

$config->docTemplate->zentaoList['case']['subMenu'][] = array('key' => 'productCase', 'name' => $lang->docTemplate->zentaoList['productCase'] . $lang->docTemplate->filter, 'icon' => 'lightbulb-alt', 'modalSize' => 'sm', 'module' => 'doc', 'method' => 'buildZentaoConfig', 'params' => 'type=productCase');
$config->docTemplate->zentaoList['case']['subMenu'][] = array('key' => 'projectCase', 'name' => $lang->docTemplate->zentaoList['projectCase'] . $lang->docTemplate->filter, 'icon' => 'project',       'modalSize' => 'sm', 'module' => 'doc', 'method' => 'buildZentaoConfig', 'params' => 'type=projectCase', 'reviewObject' => 'STTC,ITTC');

$config->doc->getTableDataParams = array();
$config->doc->getTableDataParams['productStory']   = array('product', 'searchTab');
$config->doc->getTableDataParams['projectStory']   = array('project', 'product', 'searchTab');
$config->doc->getTableDataParams['executionStory'] = array('execution', 'searchTab');
$config->doc->getTableDataParams['task']           = array('execution', 'searchTab');
$config->doc->getTableDataParams['bug']            = array('product', 'searchTab');
$config->doc->getTableDataParams['productCase']    = array('product', 'searchTab', 'caseStage');
$config->doc->getTableDataParams['projectCase']    = array('project', 'product', 'searchTab', 'caseStage');
$config->doc->getTableDataParams['HLDS']           = array('project', 'product');
$config->doc->getTableDataParams['DDS']            = array('project', 'product');
$config->doc->getTableDataParams['DBDS']           = array('project', 'product');
$config->doc->getTableDataParams['ADS']            = array('project', 'product');

$config->docTemplate->zentaoListPrivs = array();
$config->docTemplate->zentaoListPrivs['productStory']   = array('module' => 'product', 'method' => 'browse');
$config->docTemplate->zentaoListPrivs['projectStory']   = array('module' => 'projectstory', 'method' => 'story');
$config->docTemplate->zentaoListPrivs['executionStory'] = array('module' => 'execution', 'method' => 'story');
$config->docTemplate->zentaoListPrivs['task']           = array('module' => 'execution', 'method' => 'task');
$config->docTemplate->zentaoListPrivs['bug']            = array('module' => 'bug', 'method' => 'browse');
$config->docTemplate->zentaoListPrivs['productCase']    = array('module' => 'testcase', 'method' => 'browse');
$config->docTemplate->zentaoListPrivs['projectCase']    = array('module' => 'project', 'method' => 'testcase');
$config->docTemplate->zentaoListPrivs['HLDS']           = array('module' => 'design', 'method' => 'browse');
$config->docTemplate->zentaoListPrivs['DDS']            = array('module' => 'design', 'method' => 'browse');
$config->docTemplate->zentaoListPrivs['DBDS']           = array('module' => 'design', 'method' => 'browse');
$config->docTemplate->zentaoListPrivs['ADS']            = array('module' => 'design', 'method' => 'browse');
$config->docTemplate->zentaoListPrivs['gantt']          = array('module' => 'programplan', 'method' => 'browse');

$config->docTemplate->builtInTypes = array();
$config->docTemplate->builtInTypes['plan']   = array('PP');
$config->docTemplate->builtInTypes['story']  = array('SRS');
$config->docTemplate->builtInTypes['design'] = array('HLDS', 'DDS', 'DBDS', 'ADS');
$config->docTemplate->builtInTypes['test']   = array('ITTC', 'STTC');
