<?php
namespace zin;
global $lang;
$viewType   = data('viewType');
$rootID     = data('rootID');
$syncConfig = data('syncConfig');

if($viewType == 'feedback' && common::hasPriv('feedback', 'syncProduct') && !isset($syncConfig[$rootID]) && !isInModal())
{
    $syncHtml = btn
    (
        set::text($lang->feedback->syncProduct),
        set::type('primary'),
        set::url(createLink('feedback', 'syncProduct', "productID=$rootID", '', true)),
        setData(array('toggle' => 'modal', 'size' => 'sm', 'type' => 'iframe'))
    );
    query('#modulePanel')->find('.panel-heading')->append($syncHtml);
}

if($viewType == 'ticket' and common::hasPriv('ticket', 'syncProduct') && !isset($syncConfig[$rootID]) && !isInModal())
{
    $syncHtml = btn
    (
        set::text($lang->feedback->syncProduct),
        set::type('primary'),
        set::url(createLink('ticket', 'syncProduct', "productID=$rootID", '', true)),
        setData(array('toggle' => 'modal', 'size' => 'sm', 'type' => 'iframe'))
    );
    query('#modulePanel')->find('.panel-heading')->append($syncHtml);
}
