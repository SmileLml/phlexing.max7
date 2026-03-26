<?php
$config->marketresearch->form = new stdclass();

$config->marketresearch->form->create = array();
$config->marketresearch->form->create['name']       = array('type' => 'string', 'control' => 'text',   'filter'  => 'trim');
$config->marketresearch->form->create['market']     = array('type' => 'int',    'control' => 'picker', 'default' => 0);
$config->marketresearch->form->create['marketName'] = array('type' => 'string', 'control' => 'input',  'default' => '');
$config->marketresearch->form->create['PM']         = array('type' => 'string', 'control' => 'picker', 'default' => '');
$config->marketresearch->form->create['begin']      = array('type' => 'date');
$config->marketresearch->form->create['end']        = array('type' => 'date',   'default' => null);
$config->marketresearch->form->create['days']       = array('type' => 'int',    'default' => 0);
$config->marketresearch->form->create['desc']       = array('type' => 'string', 'default' => '', 'control' => 'editor');
$config->marketresearch->form->create['acl']        = array('type' => 'string', 'default' => '');
$config->marketresearch->form->create['whitelist']  = array('type' => 'array',  'default' => '');

$config->marketresearch->form->edit = array();
$config->marketresearch->form->edit['name']       = array('type' => 'string', 'control' => 'text',   'filter'  => 'trim');
$config->marketresearch->form->edit['market']     = array('type' => 'int',    'control' => 'picker', 'default' => 0);
$config->marketresearch->form->edit['PM']         = array('type' => 'string', 'control' => 'picker', 'default' => '');
$config->marketresearch->form->edit['begin']      = array('type' => 'date');
$config->marketresearch->form->edit['end']        = array('type' => 'date',   'default' => null);
$config->marketresearch->form->edit['days']       = array('type' => 'int',    'default' => 0);
$config->marketresearch->form->edit['desc']       = array('type' => 'string', 'control' => 'editor', 'default' => '');
$config->marketresearch->form->edit['acl']        = array('type' => 'string', 'default' => '');
$config->marketresearch->form->edit['whitelist']  = array('type' => 'array',  'default' => '');

$config->marketresearch->form->close = array();
$config->marketresearch->form->close['realEnd']      = array('type' => 'date',   'control' => 'date');
$config->marketresearch->form->close['status']       = array('type' => 'string', 'control' => 'hidden', 'value' => 'closed');
$config->marketresearch->form->close['closedReason'] = array('type' => 'string', 'control' => 'picker');
$config->marketresearch->form->close['comment']      = array('type' => 'string', 'control' => 'editor');

$config->marketresearch->form->closestage = array();
$config->marketresearch->form->closestage['realEnd']        = array('type' => 'date',   'default' => helper::today());
$config->marketresearch->form->closestage['status']         = array('type' => 'string', 'default' => 'closed');
$config->marketresearch->form->closestage['closedBy']       = array('type' => 'string', 'default' => '');
$config->marketresearch->form->closestage['closedDate']     = array('type' => 'date',   'default' => helper::now());
$config->marketresearch->form->closestage['lastEditedBy']   = array('type' => 'string', 'default' => '');
$config->marketresearch->form->closestage['lastEditedDate'] = array('type' => 'date',   'default' => helper::now());
$config->marketresearch->form->closestage['comment']        = array('type' => 'string', 'default' => '', 'control' => 'editor');

$config->marketresearch->form->activatestage = array();
$config->marketresearch->form->activatestage['begin']          = array('type' => 'date',   'default' => null);
$config->marketresearch->form->activatestage['end']            = array('type' => 'date',   'default' => null);
$config->marketresearch->form->activatestage['realEnd']        = array('type' => 'date',   'default' => null);
$config->marketresearch->form->activatestage['status']         = array('type' => 'string', 'default' => 'doing');
$config->marketresearch->form->activatestage['closedBy']       = array('type' => 'string', 'default' => '');
$config->marketresearch->form->activatestage['closedDate']     = array('type' => 'date',   'default' => null);
$config->marketresearch->form->activatestage['lastEditedBy']   = array('type' => 'string', 'default' => '');
$config->marketresearch->form->activatestage['lastEditedDate'] = array('type' => 'date',   'default' => helper::now());
$config->marketresearch->form->activatestage['comment']        = array('type' => 'string', 'default' => '', 'control' => 'editor');
