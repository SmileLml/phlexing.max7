<?php
global $lang;

$tableField = array();
foreach($config->company->user->dtable->fieldList as $key => $fieldList)
{
    $tableField[$key] = $fieldList;
    if($key == 'role')
    {
        $tableField['superior']['name']       = 'superior';
        $tableField['superior']['title']      = $lang->user->superior;
        $tableField['superior']['type']       = 'user';
        $tableField['superior']['sortType']   = true;
        $tableField['superior']['width']      = '100';
        $tableField['superior']['group']      = '3';
        $tableField['superior']['dataSource'] = array('module' => 'user', 'method' => 'getPairs', 'params' => 'noletter|nodeleted|noclosed');
    }
}
$config->company->user->dtable->fieldList = $tableField;

$config->company->browse->search['fields']['superior'] = $lang->user->superior;
$config->company->browse->search['params']['superior'] = array('operator' => '=', 'control' => 'select', 'values' => 'users');
