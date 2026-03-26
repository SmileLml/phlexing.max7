<?php
$config->workflowlabel->require = new stdclass();
$config->workflowlabel->require->create = 'label';
$config->workflowlabel->require->edit   = 'label';

$config->workflowlabel->default = new stdclass();
$config->workflowlabel->default->params['all'][0]['field']    = 'deleted';
$config->workflowlabel->default->params['all'][0]['operator'] = 'equal';
$config->workflowlabel->default->params['all'][0]['value']    = '0';

$config->workflowlabel->approval = new stdclass();
$config->workflowlabel->approval->params['review'][0]['field']    = 'deleted';
$config->workflowlabel->approval->params['review'][0]['operator'] = 'equal';
$config->workflowlabel->approval->params['review'][0]['value']    = '0';
$config->workflowlabel->approval->params['review'][1]['field']    = 'reviewStatus';
$config->workflowlabel->approval->params['review'][1]['operator'] = 'equal';
$config->workflowlabel->approval->params['review'][1]['value']    = 'doing';
$config->workflowlabel->approval->params['review'][2]['field']    = 'reviewers';
$config->workflowlabel->approval->params['review'][2]['operator'] = 'include';
$config->workflowlabel->approval->params['review'][2]['value']    = 'currentUser';
