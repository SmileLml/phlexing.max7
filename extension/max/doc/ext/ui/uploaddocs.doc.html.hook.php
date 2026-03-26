<?php
namespace zin;
global $lang;

$groups = data('groups');
$users  = data('users');

$readListBox = formGroup
(
    setID('readListBox'),
    setClass('hidden'),
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
                set::items($users)
            )
        )
    )
);

query('#whiteListBox')
    ->before($readListBox)
    ->find('label.form-label span.text')->text($lang->doc->editable);
