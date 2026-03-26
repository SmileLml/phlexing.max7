<?php
$config->bug->listFields   .= ',injection,identify';
$config->bug->exportFields .= ',injection,identify';
$config->bug->sysListFields = 'injection,identify';
$config->bug->templateFields .= ',injection,identify';

$config->bug->form->create['injection'] = array('required' => false, 'type' => 'int', 'default' => 0);
$config->bug->form->create['identify']  = array('required' => false, 'type' => 'int', 'default' => 0);

$config->bug->form->edit['injection'] = array('required' => false, 'type' => 'int', 'default' => 0);
$config->bug->form->edit['identify']  = array('required' => false, 'type' => 'int', 'default' => 0);

$config->bug->form->batchCreate['injection'] = array('required' => false, 'type' => 'int', 'default' => 0);
$config->bug->form->batchCreate['identify']  = array('required' => false, 'type' => 'int', 'default' => 0);
