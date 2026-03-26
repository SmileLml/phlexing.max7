<?php
namespace zin;

if(hasPriv('report', 'export'))
{
    global $lang;

    $exportBtn = btn(set(array('type' => 'primary', 'text' => $lang->export, 'url' => createLink('report', 'export', "module=bug"), 'data-toggle' => 'modal')));
    query('.detail-header')->append($exportBtn);
}
