<?php
$config->flow->defaultFields->createdBy['product']     = 'createdBy';
$config->flow->defaultFields->createdBy['productplan'] = '';
$config->flow->defaultFields->createdBy['release']     = '';
$config->flow->defaultFields->createdBy['story']       = 'openedBy';
$config->flow->defaultFields->createdBy['epic']        = 'openedBy';
$config->flow->defaultFields->createdBy['requirement'] = 'openedBy';
$config->flow->defaultFields->createdBy['project']     = 'openedBy';
$config->flow->defaultFields->createdBy['build']       = '';
$config->flow->defaultFields->createdBy['task']        = 'openedBy';
$config->flow->defaultFields->createdBy['bug']         = 'openedBy';
$config->flow->defaultFields->createdBy['testcase']    = 'openedBy';
$config->flow->defaultFields->createdBy['testtask']    = '';
$config->flow->defaultFields->createdBy['testsuite']   = 'addedBy';
$config->flow->defaultFields->createdBy['caselib']     = 'addedBy';
$config->flow->defaultFields->createdBy['feedback']    = 'openedBy';

$config->flow->defaultFields->createdDate['product']     = 'createdDate';
$config->flow->defaultFields->createdDate['productplan'] = '';
$config->flow->defaultFields->createdDate['release']     = '';
$config->flow->defaultFields->createdDate['story']       = 'openedDate';
$config->flow->defaultFields->createdDate['epic']        = 'openedDate';
$config->flow->defaultFields->createdDate['requirement'] = 'openedDate';
$config->flow->defaultFields->createdDate['project']     = 'openedDate';
$config->flow->defaultFields->createdDate['build']       = '';
$config->flow->defaultFields->createdDate['task']        = 'openedDate';
$config->flow->defaultFields->createdDate['bug']         = 'openedDate';
$config->flow->defaultFields->createdDate['testcase']    = 'openedDate';
$config->flow->defaultFields->createdDate['testtask']    = '';
$config->flow->defaultFields->createdDate['testsuite']   = 'addedDate';
$config->flow->defaultFields->createdDate['caselib']     = 'addedDate';
$config->flow->defaultFields->createdDate['feedback']    = 'openedDate';

$config->flow->defaultFields->editedBy['product']     = '';
$config->flow->defaultFields->editedBy['productplan'] = '';
$config->flow->defaultFields->editedBy['release']     = '';
$config->flow->defaultFields->editedBy['story']       = 'lastEditedBy';
$config->flow->defaultFields->editedBy['epic']        = 'lastEditedBy';
$config->flow->defaultFields->editedBy['requirement'] = 'lastEditedBy';
$config->flow->defaultFields->editedBy['project']     = 'lastEditedBy';
$config->flow->defaultFields->editedBy['execution']   = 'lastEditedBy';
$config->flow->defaultFields->editedBy['build']       = '';
$config->flow->defaultFields->editedBy['task']        = 'lastEditedBy';
$config->flow->defaultFields->editedBy['bug']         = 'lastEditedBy';
$config->flow->defaultFields->editedBy['testcase']    = 'lastEditedBy';
$config->flow->defaultFields->editedBy['testtask']    = '';
$config->flow->defaultFields->editedBy['testsuite']   = 'lastEditedBy';
$config->flow->defaultFields->editedBy['caselib']     = 'lastEditedBy';
$config->flow->defaultFields->editedBy['feedback']    = 'editedBy';
$config->flow->defaultFields->editedBy['charter']     = '';

$config->flow->defaultFields->editedDate['product']     = '';
$config->flow->defaultFields->editedDate['productplan'] = '';
$config->flow->defaultFields->editedDate['release']     = '';
$config->flow->defaultFields->editedDate['story']       = 'lastEditedDate';
$config->flow->defaultFields->editedDate['epic']        = 'lastEditedDate';
$config->flow->defaultFields->editedDate['requirement'] = 'lastEditedDate';
$config->flow->defaultFields->editedDate['project']     = 'lastEditedDate';
$config->flow->defaultFields->editedDate['execution']   = 'lastEditedDate';
$config->flow->defaultFields->editedDate['build']       = '';
$config->flow->defaultFields->editedDate['task']        = 'lastEditedDate';
$config->flow->defaultFields->editedDate['bug']         = 'lastEditedDate';
$config->flow->defaultFields->editedDate['testcase']    = 'lastEditedDate';
$config->flow->defaultFields->editedDate['testtask']    = '';
$config->flow->defaultFields->editedDate['testsuite']   = 'lastEditedDate';
$config->flow->defaultFields->editedDate['caselib']     = 'lastEditedDate';
$config->flow->defaultFields->editedDate['feedback']    = 'editedDate';
$config->flow->defaultFields->editedDate['charter']     = '';

$config->flow->editor = new stdclass();
$config->flow->editor->view = array('id' => 'comment,lastComment', 'tools' => 'simpleTools');

$config->flow->realModule = array();
$config->flow->realModule['testcase'] = 'case';

$this->config->flowLimit = 0;
