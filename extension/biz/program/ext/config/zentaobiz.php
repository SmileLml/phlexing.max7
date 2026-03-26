<?php
$config->program->form->create['charter'] = array('type' => 'int', 'required' => false, 'default' => 0);

$config->program->form->edit['charter'] = array('type' => 'int', 'required' => false, 'default' => 0);

$browseConfig = array();
foreach($config->program->browse->dtable->fieldList as $field => $options)
{
    $browseConfig[$field] = $options;
    if($field == 'PM')
    {
        $browseConfig['charter']['name']     = 'charter';
        $browseConfig['charter']['title']    = $lang->program->charter;
        $browseConfig['charter']['width']    = 90;
        $browseConfig['charter']['type']     = 'html';
        $browseConfig['charter']['sortType'] = true;
        $browseConfig['charter']['link']     = array('module' => 'charter', 'method' => 'view', 'params' => 'charterID={charter}');
        $browseConfig['charter']['show']     = true;
        $browseConfig['charter']['group']    = 2;
    }
}
$config->program->browse->dtable->fieldList = $browseConfig;

$config->program->search['fields']['charter'] = $lang->program->charter;
$searchConfig = array();
foreach($config->program->search['params'] as $field => $options)
{
    $searchConfig[$field] = $options;

    if($field == 'PM') $searchConfig['charter'] = array('operator' => '=', 'control' => 'select', 'values' => '');
}
$config->program->search['params'] = $searchConfig;
