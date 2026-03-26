<?php
global $lang;

$config->user->form->create['superior']      = array('required' => false, 'type' => 'string', 'default' => '');
$config->user->form->edit['superior']        = array('required' => false, 'type' => 'string', 'default' => '');

$tableField = array();
foreach($config->user->form->batchCreate as $key => $field)
{
    $tableField[$key] = $field;
    if($key == 'role') $tableField['superior'] = array('required' => false, 'type' => 'string', 'width' => '120px', 'name' => 'superior', 'label' => $lang->user->superior, 'control' => 'picker', 'items' => array());
}
$config->user->form->batchCreate = $tableField;

$tableField = array();
foreach($config->user->form->batchEdit as $key => $field)
{
    $tableField[$key] = $field;
    if($key == 'role') $tableField['superior'] = array('required' => false, 'type' => 'string', 'width' => '120px', 'name' => 'superior', 'label' => $lang->user->superior, 'control' => 'picker', 'items' => array());
}
$config->user->form->batchEdit = $tableField;

$config->user->listFields     = 'dept,role,superior,gender,type';
$config->user->templateFields = 'account,realname,dept,gender,type,role,superior,join,email,phone,mobile,weixin,qq,address';

$config->user->export->listFields     = explode(',', "dept,role,superior,gender,type");;
$config->user->export->templateFields = explode(',', "account,realname,dept,gender,type,role,superior,join,email,phone,mobile,weixin,qq,address");;

$config->user->list->customBatchCreateFields .= ',superior';
$config->user->list->customBatchEditFields   .= ',superior';
$config->user->list->exportFields             = 'id,account,realname,dept,gender,type,group,role,superior,join,email,phone,mobile,weixin,qq,address';
$config->user->list->importFields             = 'id,account,realname,dept,vision,type,group,role,superior,email,gender,password,join,phone,mobile,weixin,qq,address';;

if(!isset($config->user->batchAppendFields)) $config->user->batchAppendFields = '';
$config->user->batchAppendFields .= ',superior';
