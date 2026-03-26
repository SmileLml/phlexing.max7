<?php
namespace zin;
global $lang, $app;

$users = $app->control->loadModel('user')->getPairs('noletter|noclosed');

$superior = formRow
(
    formGroup
    (
        set::width('1/2'),
        set::label($lang->user->superior),
        set::control('picker'),
        set::name('superior'),
        set::items($users)
    )
);

query('#role')->closest('.form-row')->after($superior);
