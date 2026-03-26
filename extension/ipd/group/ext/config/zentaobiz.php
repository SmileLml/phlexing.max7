<?php
$config->group->package->deployPlan = new stdclass();
$config->group->package->deployPlan->order  = 5;
$config->group->package->deployPlan->subset = 'deployment';
$config->group->package->deployPlan->privs  = array();
$config->group->package->deployPlan->privs['deploy-browse']   = array('edition' => 'biz,max,ipd', 'vision' => 'rnd', 'order' => 0,  'depend' => array(),                'recommend' => array('deploy-activate', 'deploy-create', 'deploy-edit', 'deploy-finish', 'deploy-view'));
$config->group->package->deployPlan->privs['deploy-create']   = array('edition' => 'biz,max,ipd', 'vision' => 'rnd', 'order' => 2,  'depend' => array('deploy-browse'), 'recommend' => array('deploy-activate', 'deploy-edit', 'deploy-finish'));
$config->group->package->deployPlan->privs['deploy-edit']     = array('edition' => 'biz,max,ipd', 'vision' => 'rnd', 'order' => 3,  'depend' => array('deploy-browse'), 'recommend' => array('deploy-activate', 'deploy-create', 'deploy-finish'));
$config->group->package->deployPlan->privs['deploy-delete']   = array('edition' => 'biz,max,ipd', 'vision' => 'rnd', 'order' => 6,  'depend' => array('deploy-browse'), 'recommend' => array('deploy-create', 'deploy-edit'));
$config->group->package->deployPlan->privs['deploy-activate'] = array('edition' => 'biz,max,ipd', 'vision' => 'rnd', 'order' => 4,  'depend' => array('deploy-browse'), 'recommend' => array('deploy-create', 'deploy-edit', 'deploy-finish'));
$config->group->package->deployPlan->privs['deploy-finish']   = array('edition' => 'biz,max,ipd', 'vision' => 'rnd', 'order' => 5,  'depend' => array('deploy-browse'), 'recommend' => array('deploy-activate', 'deploy-create', 'deploy-edit'));
$config->group->package->deployPlan->privs['deploy-view']     = array('edition' => 'biz,max,ipd', 'vision' => 'rnd', 'order' => 1,  'depend' => array('deploy-browse'), 'recommend' => array('deploy-activate', 'deploy-create', 'deploy-edit', 'deploy-finish'));
$config->group->package->deployPlan->privs['deploy-publish']  = array('edition' => 'biz,max,ipd', 'vision' => 'rnd', 'order' => 10, 'depend' => array('deploy-browse'), 'recommend' => array('deploy-activate', 'deploy-create', 'deploy-edit', 'deploy-finish'));

$config->group->package->deployStep = new stdclass();
$config->group->package->deployStep->order  = 15;
$config->group->package->deployStep->subset = 'deployment';
$config->group->package->deployStep->privs  = array();
$config->group->package->deployStep->privs['deploy-steps']      = array('edition' => 'biz,max,ipd', 'vision' => 'rnd', 'order' => 3, 'depend' => array('deploy-browse', 'deploy-view'),  'recommend' => array('deploy-assignTo', 'deploy-manageStep', 'deploy-viewStep'));
$config->group->package->deployStep->privs['deploy-manageStep'] = array('edition' => 'biz,max,ipd', 'vision' => 'rnd', 'order' => 2, 'depend' => array('deploy-browse', 'deploy-steps'), 'recommend' => array('deploy-assignTo'));
$config->group->package->deployStep->privs['deploy-finishStep'] = array('edition' => 'biz,max,ipd', 'vision' => 'rnd', 'order' => 5, 'depend' => array('deploy-browse', 'deploy-steps'), 'recommend' => array('deploy-assignTo', 'deploy-manageStep'));
$config->group->package->deployStep->privs['deploy-assignTo']   = array('edition' => 'biz,max,ipd', 'vision' => 'rnd', 'order' => 4, 'depend' => array('deploy-browse', 'deploy-steps'), 'recommend' => array('deploy-editStep', 'deploy-finishStep', 'deploy-manageStep'));
$config->group->package->deployStep->privs['deploy-viewStep']   = array('edition' => 'biz,max,ipd', 'vision' => 'rnd', 'order' => 1, 'depend' => array('deploy-browse', 'deploy-steps'), 'recommend' => array('deploy-assignTo', 'deploy-finishStep', 'deploy-manageStep'));

$config->group->package->deployCase = new stdclass();
$config->group->package->deployCase->order  = 20;
$config->group->package->deployCase->subset = 'deployment';
$config->group->package->deployCase->privs  = array();
$config->group->package->deployCase->privs['deploy-cases']            = array('edition' => 'biz,max,ipd', 'vision' => 'rnd', 'order' => 50, 'depend' => array('deploy-browse'), 'recommend' => array('deploy-linkCases', 'deploy-unlinkCase'));
$config->group->package->deployCase->privs['deploy-linkCases']        = array('edition' => 'biz,max,ipd', 'vision' => 'rnd', 'order' => 55, 'depend' => array('deploy-browse', 'deploy-cases'), 'recommend' => array('deploy-unlinkCase'));
$config->group->package->deployCase->privs['deploy-unlinkCase']       = array('edition' => 'biz,max,ipd', 'vision' => 'rnd', 'order' => 60, 'depend' => array('deploy-browse', 'deploy-cases'), 'recommend' => array('deploy-batchUnlinkCases', 'deploy-linkCases'));
$config->group->package->deployCase->privs['deploy-batchUnlinkCases'] = array('edition' => 'biz,max,ipd', 'vision' => 'rnd', 'order' => 65, 'depend' => array('deploy-browse', 'deploy-cases'), 'recommend' => array('deploy-unlinkCase'));

$config->group->package->host = new stdclass();
$config->group->package->host->order  = 2220;
$config->group->package->host->subset = 'deployment';
$config->group->package->host->privs  = array();
$config->group->package->host->privs['host-browse']       = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd', 'order' => 5, 'depend' => array('deploy-browse'), 'recommend' => array('host-create', 'host-edit', 'host-treemap', 'host-view'));
$config->group->package->host->privs['host-create']       = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd', 'order' => 10, 'depend' => array('host-browse'), 'recommend' => array('host-changeStatus', 'host-edit'));
$config->group->package->host->privs['host-edit']         = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd', 'order' => 15, 'depend' => array('host-browse'), 'recommend' => array('host-changeStatus', 'host-create'));
$config->group->package->host->privs['host-view']         = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd', 'order' => 20, 'depend' => array('host-browse'), 'recommend' => array('host-create', 'host-edit'));
$config->group->package->host->privs['host-delete']       = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd', 'order' => 25, 'depend' => array('host-browse'), 'recommend' => array('host-create', 'host-edit'));
$config->group->package->host->privs['host-changeStatus'] = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd', 'order' => 30, 'depend' => array('host-browse'), 'recommend' => array('host-create', 'host-edit'));
$config->group->package->host->privs['host-treemap']      = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd', 'order' => 35, 'depend' => array('host-browse'), 'recommend' => array());
