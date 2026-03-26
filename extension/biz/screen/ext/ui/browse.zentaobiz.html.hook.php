<?php
namespace zin;

global $lang;
$dimensionID = data('dimensionID');
if(hasPriv('screen', 'create'))
{
    $createBtn = div
    (
        set::className('clearfix pt-3 pr-2.5'),
        div
        (
            set::className('pull-right'),
            btn
            (
                set::text($lang->screen->create),
                set::type('primary'),
                set::icon('plus'),
                set('data-toggle', 'modal'),
                set::url(inlink('create', "dimensionID=$dimensionID"))
            )
        )
    );
    query('#mainContent')->before($createBtn);
}
?>
