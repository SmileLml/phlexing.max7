<?php
namespace zin;

global $lang;
$browseBtn = common::hasPriv('metric', 'browse') ? btn
(
    setClass('btn primary'),
    set::url(helper::createLink('metric', 'browse')),
    $lang->metric->manage
) : null;

query('#topbar')->append($browseBtn);
?>
