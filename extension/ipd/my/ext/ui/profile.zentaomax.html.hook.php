<?php
namespace zin;
global $lang, $app;

$users = $app->control->loadModel('user')->getPairs('noletter|noclosed');

$superior = cell
(
    set::width('50%'),
    set::className('flex py-2'),
    cell
    (
        set::width('70px'),
        set::className('text-right'),
        span(set::className('text-gray'), $lang->user->superior)
    ),
    cell
    (
        set::flex('1'),
        set::className('ml-2'),
        zget($users, data('user.superior'))
    )
);

query('.basic-info .role')->after($superior);
