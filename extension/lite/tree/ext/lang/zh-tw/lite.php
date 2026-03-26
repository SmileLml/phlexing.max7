<?php

$lang->tree->all             = '所有目錄';
$lang->tree->allMenu         = $lang->tree->all;
$lang->tree->manageMenu      = '維護目錄';
$lang->tree->manage          = '維護目錄';

global $app;
if ($app->rawModule == 'tree' and $app->rawMethod == 'browsetask') 
{
    $lang->tree->common          = '目錄維護';
    $lang->tree->manageExecution = "維護{$lang->executionCommon}視圖目錄";
    $lang->tree->manageTaskChild = "維護{$lang->executionCommon}子目錄";
    $lang->tree->name            = '目錄名稱';
    $lang->tree->child           = '子目錄';
    $lang->tree->edit            = '編輯目錄';
    $lang->tree->delete          = '刪除目錄';
}

if($app->rawModule == 'tree' and $app->rawMethod == 'browse')
{
    $lang->tree->edit             = '編輯目錄';
    $lang->tree->delete           = '刪除目錄';
    $lang->tree->child            = '子目錄';
    $lang->tree->manageStoryChild = '維護子目錄';
    $lang->tree->name             = '目錄名稱';
    $lang->tree->syncFromProduct  = '複製目錄';
}
if($app->rawModule == 'story') $lang->tree->manage = $lang->tree->manageMenu;
