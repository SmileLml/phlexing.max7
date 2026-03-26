<?php
$config->product->form->create['feedback']      = array('type' => 'account', 'control' => 'select', 'required' => false, 'default' => '', 'options' => array());
$config->product->form->create['ticket']        = array('type' => 'account', 'control' => 'select', 'required' => false, 'default' => '', 'options' => array());
$config->product->form->create['workflowGroup'] = array('type' => 'int',     'control' => 'select', 'required' => false, 'default' => '', 'options' => array());

$config->product->form->edit['feedback'] = array('type' => 'account', 'control' => 'select', 'required' => false, 'default' => '', 'options' => array());
$config->product->form->edit['ticket']   = array('type' => 'account', 'control' => 'select', 'required' => false, 'default' => '', 'options' => array());
