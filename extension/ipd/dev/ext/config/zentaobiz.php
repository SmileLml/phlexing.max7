<?php
$config->dev->group['ops']              = 'ops';
$config->dev->group['host']             = 'ops';
$config->dev->group['asset']            = 'ops';
$config->dev->group['serverroom']       = 'ops';
$config->dev->group['service']          = 'ops';
$config->dev->group['deploy']           = 'ops';
$config->dev->group['deploystep']       = 'ops';
$config->dev->group['deployproduct']    = 'ops';
$config->dev->group['deployscope']      = 'ops';
$config->dev->group['account']          = 'ops';
$config->dev->group['vm']               = 'ops';
$config->dev->group['vmtemplate']       = 'ops';
$config->dev->group['domain']           = 'ops';
$config->dev->group['baseimage']        = 'ops';
$config->dev->group['baseimagebrowser'] = 'ops';
$config->dev->group['browser']          = 'ops';

$config->dev->group['workflow']               = 'flow';
$config->dev->group['workflowaction']         = 'flow';
$config->dev->group['workflowfield']          = 'flow';
$config->dev->group['workflowdatasource']     = 'flow';
$config->dev->group['workflowrule']           = 'flow';
$config->dev->group['workflowlabel']          = 'flow';
$config->dev->group['workflowrelation']       = 'flow';
$config->dev->group['workflowlinkdata']       = 'flow';
$config->dev->group['workflowrelationlayout'] = 'flow';
$config->dev->group['workflowreport']         = 'flow';
$config->dev->group['workflowsql']            = 'flow';
$config->dev->group['workflowversion']        = 'flow';
$config->dev->group['workflowlayout']         = 'flow';
$config->dev->group['workflowhook']           = 'flow';
$config->dev->group['workflowcondition']      = 'flow';
$config->dev->group['workflowlinkage']        = 'flow';

$config->dev->group['traincourse'] = 'traincourse';

$config->dev->tableMap['asset']            = 'host';
$config->dev->tableMap['deploystep']       = 'deploy';
$config->dev->tableMap['deployproduct']    = 'deploy';
$config->dev->tableMap['deployscope']      = 'deploy';
$config->dev->tableMap['vmtemplate']       = 'vm';
$config->dev->tableMap['baseimage']        = 'vm';
$config->dev->tableMap['baseimagebrowser'] = 'vm';
$config->dev->tableMap['browser']          = 'vm';
