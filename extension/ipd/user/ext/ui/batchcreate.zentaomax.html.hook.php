<?php
namespace zin;
global $lang, $app;

$users = $app->control->loadModel('user')->getPairs('noletter|noclosed');

$superior = formBatchItem
(
    set::name('superior'),
    set::label($lang->user->superior),
    set::control('picker'),
    set::items($users),
    set::width('100px'),
    set::hidden(!in_array('superior', data('showFields')))
);
query('formBatchPanel')->append($superior);
