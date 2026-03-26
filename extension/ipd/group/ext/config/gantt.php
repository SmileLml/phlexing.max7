<?php
$config->group->package->executionGantt = new stdclass();
$config->group->package->executionGantt->order  = 5;
$config->group->package->executionGantt->subset = 'executionview';
$config->group->package->executionGantt->privs  = array();
$config->group->package->executionGantt->privs['execution-gantt']        = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd,lite', 'order' => 0, 'depend' => array(), 'recommend' => array('execution-calendar', 'execution-ganttEdit', 'execution-ganttsetting', 'execution-grouptask', 'execution-taskEffort', 'execution-tree'));
$config->group->package->executionGantt->privs['execution-ganttsetting'] = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd', 'order' => 2, 'depend' => array('execution-gantt'), 'recommend' => array());
$config->group->package->executionGantt->privs['execution-ganttEdit']    = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd', 'order' => 3, 'depend' => array('execution-gantt'), 'recommend' => array());

$config->group->package->executionRelation->privs['execution-relation']['edition']            .= ',open';
$config->group->package->executionRelation->privs['execution-createrelation']['edition']      .= ',open';
$config->group->package->executionRelation->privs['execution-editrelation']['edition']        .= ',open';
$config->group->package->executionRelation->privs['execution-batcheditrelation']['edition']   .= ',open';
$config->group->package->executionRelation->privs['execution-deleterelation']['edition']      .= ',open';
$config->group->package->executionRelation->privs['execution-batchdeleterelation']['edition'] .= ',open';
