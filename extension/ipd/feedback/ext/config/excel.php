<?php
$config->feedback->listFields     = "module,product,type,public,notify,pri";
$config->feedback->sysListFields  = "module,product,type,public,notify,pri";
$config->feedback->sysLangFields  = "type,public,notify,pri";
$config->feedback->templateFields = 'product,module,type,title,pri,desc,source,feedbackBy,notifyEmail,public,notify';
$config->feedback->cascade        = array('module' => 'product');

$config->feedback->dtable->fieldList['product']['dataSource'] = array('module' => 'feedback', 'method' => 'getGrantProducts', 'params' => ['isPairs' => true, 'isDefault' => false, 'params' => 'all']);
$config->feedback->dtable->fieldList['module']['dataSource']  = array('module' => 'tree', 'method' => 'getOptionMenu', 'params' => ['rootID' => 0, 'type' => 'feedback', 'startModule' => 0, 'branch' => 'all']);
