<?php
namespace zin;
global $lang, $config;

$doc       = data('doc');
$spaceType = data('spaceType');
$groups    = data('groups');
$users     = data('users');
$defaultAcl = $spaceType == 'mine' ? 'private' : $doc->acl;

$readListBox = formGroup
(
    setID('readListBox'),
    setClass(($spaceType != 'mine' && $defaultAcl == 'private') ? '' : 'hidden'),
    set::label($lang->doc->readonly),
    div
    (
        setClass('w-full check-list'),
        inputGroup
        (
            setClass('w-full'),
            $lang->doc->groupLabel,
            picker
            (
                set::name('readGroups[]'),
                set::items($groups),
                set::value($doc->readGroups),
                set::multiple(true)
            )
        ),
        div
        (
            setClass('w-full'),
            userPicker
            (
                set::label($lang->doc->userLabel),
                set::name('readUsers[]'),
                set::items($users),
                set::value($doc->readUsers)
            )
        )
    )
);

query('#whiteListBox')
    ->before($readListBox)
    ->find('label.form-label span.text')->text($lang->doc->editable);
