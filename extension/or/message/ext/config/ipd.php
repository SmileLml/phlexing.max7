<?php
$config->message->objectTypes = array();
$config->message->objectTypes['product']    = array('opened', 'edited', 'closed', 'undeleted');
if($config->enableER) $config->message->objectTypes['epic']        = array('opened', 'edited', 'commented', 'changed', 'submitreview', 'reviewed', 'closed', 'activated', 'assigned');
if($config->URAndSR)  $config->message->objectTypes['requirement'] = array('opened', 'edited', 'commented', 'changed', 'submitreview', 'reviewed', 'closed', 'activated', 'assigned');
$config->message->objectTypes['demandpool'] = array('created', 'edited', 'closed', 'activated');
$config->message->objectTypes['demand']     = array('created', 'edited', 'commented', 'changed', 'submitreview', 'reviewed', 'closed', 'activated', 'distributed', 'assigned');

$config->message->available = array();
if($config->enableER) $config->message->available['mail']['epic']        = $config->message->objectTypes['epic'];
if($config->URAndSR)  $config->message->available['mail']['requirement'] = $config->message->objectTypes['requirement'];

$config->message->available['webhook'] = $config->message->objectTypes;

if($config->enableER) $config->message->available['message']['epic']        = $config->message->objectTypes['epic'];
if($config->URAndSR)  $config->message->available['message']['requirement'] = $config->message->objectTypes['requirement'];

if($config->enableER) $config->message->available['sms']['epic']        = $config->message->objectTypes['epic'];
if($config->URAndSR)  $config->message->available['sms']['requirement'] = $config->message->objectTypes['requirement'];

$config->message->available['mail']['demandpool']     = $config->message->objectTypes['demandpool'];
$config->message->available['message']['demandpool']  = $config->message->objectTypes['demandpool'];
$config->message->available['sms']['demandpool']      = $config->message->objectTypes['demandpool'];
$config->message->available['xuanxuan']['demandpool'] = $config->message->objectTypes['demandpool'];

$config->message->available['mail']['demand']     = $config->message->objectTypes['demand'];
$config->message->available['message']['demand']  = $config->message->objectTypes['demand'];
$config->message->available['sms']['demand']      = $config->message->objectTypes['demand'];
$config->message->available['xuanxuan']['demand'] = $config->message->objectTypes['demand'];
