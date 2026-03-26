<?php
$config->workflowrelation = new stdclass();
$config->workflowrelation->one2one   = 'create';
$config->workflowrelation->many2one  = 'create';
$config->workflowrelation->one2many  = 'batchcreate';
$config->workflowrelation->many2many = 'batchcreate';

$config->workflowrelation->buildin = new stdclass();
$config->workflowrelation->buildin->relations = array();
$config->workflowrelation->buildin->relations['crm']['order']['sign']               = array('next' => 'contract', 'actionCodes' => array('one2one' => 'create'));
$config->workflowrelation->buildin->relations['crm']['contract']['invoice']         = array('next' => 'invoice',  'actionCodes' => array('one2one' => 'createsale'));
$config->workflowrelation->buildin->relations['crm']['purchasecontract']['invoice'] = array('next' => 'invoice',  'actionCodes' => array('one2one' => 'createpurchase'));
$config->workflowrelation->buildin->relations['crm']['customer']['invoice']         = array('next' => 'invoice',  'actionCodes' => array('one2one' => 'createsale'));

$config->workflowrelation->buildin->actions = array();
$config->workflowrelation->buildin->actions['crm']['order']['sign']               = array('type' => 'single', 'batchMode' => 'same', 'open' => 'normal', 'position' => 'browseandview', 'show' => 'direct');
$config->workflowrelation->buildin->actions['crm']['contract']['invoice']         = array('type' => 'single', 'batchMode' => 'same', 'open' => 'normal', 'position' => 'browseandview', 'show' => 'dropdownlist');
$config->workflowrelation->buildin->actions['crm']['purchasecontract']['invoice'] = array('type' => 'single', 'batchMode' => 'same', 'open' => 'normal', 'position' => 'browseandview', 'show' => 'dropdownlist');
$config->workflowrelation->buildin->actions['crm']['customer']['invoice']         = array('type' => 'single', 'batchMode' => 'same', 'open' => 'normal', 'position' => 'browseandview', 'show' => 'dropdownlist');
