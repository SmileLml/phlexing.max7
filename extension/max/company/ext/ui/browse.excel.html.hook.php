<?php
namespace zin;

$lang = data('lang');

$btnItems = array();
if(hasPriv('user', 'import')) $btnItems[] = btn
(
    setClass('ghost'),
    set::icon('import'),
    set::text($lang->user->import),
    set::url(createLink('user', 'import')),
    setData(array('toggle' => 'modal'))
);

$canExport         = hasPriv('user', 'export');
$canExportTemplate = hasPriv('user', 'exportTemplate');
$exportItem        = $exportTemplateItem  = null;
if($canExport)         $exportItem         = array('text' => $lang->user->export,         'data-toggle' => 'modal', 'url' => createLink('user', 'export', 'browseType=' . data('browseType') . '&param=' . data('param') . '&type=' . data('type') . '&orderBy=' . data('orderBy')));
if($canExportTemplate) $exportTemplateItem = array('text' => $lang->user->exportTemplate, 'data-toggle' => 'modal', 'url' => createLink('user', 'exportTemplate'));

if($canExport && $canExportTemplate) $btnItems[] = dropdown
(
    btn
    (
        setClass('btn ghost dropdown-toggle'),
        set::icon('export'),
        $lang->export
    ),
    set::items(array($exportItem, $exportTemplateItem)),
    set::placement('bottom-end')
);

query('#actionBar')->prepend($btnItems);
