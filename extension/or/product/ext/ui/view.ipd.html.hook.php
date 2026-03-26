<?php
namespace zin;

global $lang;

$productURs = data('productURs');

$otherInfo = panel
(
    setClass('otherInfoBox'),
    set::title($lang->product->otherInfo),
    div
    (
        setClass('flex flex-wrap'),
        div
        (
            setClass('w-1/4 item mb-3'),
            span(setClass('text-gray'), $lang->story->statusList['draft'] . $lang->URCommon),
            span(setClass('ml-2'), $productURs->stories['draft'])
        ),
        div
        (
            setClass('w-1/4 item mb-3'),
            span(setClass('text-gray'), $lang->story->statusList['active'] . $lang->URCommon),
            span(setClass('ml-2'), $productURs->stories['active'])
        ),
        div
        (
            setClass('w-1/4 item mb-3'),
            span(setClass('text-gray'), $lang->story->statusList['reviewing'] . $lang->URCommon),
            span(setClass('ml-2'), $productURs->stories['reviewing'])
        ),
        div
        (
            setClass('w-1/4 item mb-3'),
            span(setClass('text-gray'), $lang->story->statusList['changing'] . $lang->URCommon),
            span(setClass('ml-2'), $productURs->stories['changing'])
        )
    )
);

query('.otherInfoBox')->replaceWith($otherInfo);
