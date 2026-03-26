<?php
namespace zin;
global $lang, $config;

$objectType = data('objectType');
if($objectType == 'template') return;

$doc        = data('doc');
$libID      = data('libID');
$groups     = data('groups');
$users      = data('users');
$objectType = data('objectType');
$modalType  = data('modalType');

if($modalType == 'doc' || $modalType == 'subDoc')
{
    $readListBox = formGroup
    (
        setID('readListBox'),
        setClass((!empty($doc) && $libID == $doc->lib && $objectType != 'mine' && $doc->acl == 'private') ? '' : 'hidden'),
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
                    set::value(empty($doc) ? null : $doc->readGroups),
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
                    set::value(empty($doc) ? null : $doc->readUsers)
                )
            )
        )
    );

    query('#whiteListBox')
        ->before($readListBox)
        ->find('label.form-label span.text')->text($lang->doc->editable);
}
