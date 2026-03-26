<?php
$config->bug->browseTypeList[] = 'feedback';

$config->bug->form->create['feedback'] = array('required' => false, 'type' => 'int',    'default' => 0);
$config->bug->form->create['found']    = array('required' => false, 'type' => 'string', 'default' => '');
