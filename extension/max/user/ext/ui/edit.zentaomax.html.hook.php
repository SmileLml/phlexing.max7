<?php
namespace zin;
global $lang, $app;

$user  = data('user');
$users = $app->control->loadModel('user')->getPairs('noletter|noclosed');
unset($users[$user->account]);

$superior = formRow(
    formGroup(
        set::width('1/2'),
        set::label($lang->user->superior),
        set::control('picker'),
        set::name('superior'),
        set::items($users),
        set::value($user->superior)
    )
);

query('#genderm')->closest('.form-row')->after($superior);
