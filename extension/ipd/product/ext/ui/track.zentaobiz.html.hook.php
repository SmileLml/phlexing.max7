<?php
namespace zin;

$app        = data('app');
$lang       = data('lang');
$productID  = data('productID');
$branch     = data('branch');
$projectID  = data('projectID');
$browseType = data('browseType');
$param      = data('param');
$storyType  = data('storyType');
$orderBy    = data('orderBy');
$moduleName = $app->rawModule;
if(common::hasPriv($moduleName, 'exportTrack'))
{
    $paramTemplate = "productID={$productID}&branch={$branch}&projectID={$projectID}&browseType={$browseType}&param={$param}&storyType={$storyType}&orderBy={$orderBy}";
    if($moduleName == 'projectstory') $paramTemplate = "projectID={$projectID}&productID={$productID}&branch={$branch}&browseType={$browseType}&param={$param}&storyType={$storyType}&orderBy={$orderBy}";
    $exportHtml = btn(setClass('ghost'), set::url(createLink($moduleName, 'exportTrack', $paramTemplate)), setData(array('toggle' => 'modal', 'size' => 'sm')), set::icon('export'), set::text($lang->export));

    query('#mainMenu #actionBar.toolbar')->prepend($exportHtml);
}
