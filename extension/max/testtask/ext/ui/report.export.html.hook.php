<?php
namespace zin;

$productID = data('productID');
$taskID    = data('taskID');
if(hasPriv('report', 'export'))
{
    global $lang;


    $exportBtn = btn(set(array('type' => 'primary', 'text' => $lang->export, 'url' => createLink('report', 'export', "module=testtask&productID={$productID}&taskID={$taskID}"), 'data-toggle' => 'modal')));
    query('.detail-header')->append($exportBtn);
}
