<?php
$config->group->package->relation = new stdclass();
$config->group->package->relation->order  = 40;
$config->group->package->relation->subset = 'featureconfig';
$config->group->package->relation->privs  = array();
$config->group->package->relation->privs['custom-browserelation'] = array('edition' => 'biz,max,ipd', 'vision' => 'rnd,lite,or', 'order' => 20, 'depend' => array(), 'recommend' => array());
$config->group->package->relation->privs['custom-createrelation'] = array('edition' => 'biz,max,ipd', 'vision' => 'rnd,lite,or', 'order' => 21, 'depend' => array('custom-browserelation'), 'recommend' => array());
$config->group->package->relation->privs['custom-editrelation']   = array('edition' => 'biz,max,ipd', 'vision' => 'rnd,lite,or', 'order' => 22, 'depend' => array('custom-browserelation'), 'recommend' => array());
$config->group->package->relation->privs['custom-deleterelation'] = array('edition' => 'biz,max,ipd', 'vision' => 'rnd,lite,or', 'order' => 23, 'depend' => array('custom-browserelation'), 'recommend' => array());

$config->group->subset->relateObject = new stdclass();
$config->group->subset->relateObject->order = 1781;

$config->group->package->relateObject = new stdclass();
$config->group->package->relateObject->order  = 5;
$config->group->package->relateObject->subset = 'relateObject';
$config->group->package->relateObject->privs  = array();
$config->group->package->relateObject->privs['custom-relateObject']      = array('edition' => 'biz,max,ipd', 'vision' => 'rnd,or',      'order' => 24, 'depend' => array(), 'recommend' => array());
$config->group->package->relateObject->privs['custom-removeObjects']     = array('edition' => 'biz,max,ipd', 'vision' => 'rnd,or',      'order' => 25, 'depend' => array(), 'recommend' => array());
$config->group->package->relateObject->privs['custom-showRelationGraph'] = array('edition' => 'biz,max,ipd', 'vision' => 'rnd,or',      'order' => 26, 'depend' => array(), 'recommend' => array());
