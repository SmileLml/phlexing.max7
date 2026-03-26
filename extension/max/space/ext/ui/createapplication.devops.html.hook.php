<?php
namespace zin;

global $app, $lang;
$colWidth = isInModal() ? 'full' : '2/3';

query('.dbService')->after(
    formRowGroup(set::title($lang->space->customFields)),
    div
    (
        setID('customField'),
        isInModal() ? null : setClass('w-2/3')
    )
);
