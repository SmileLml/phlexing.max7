<?php
namespace zin;

set::title($lang->approval->forward);
formPanel
(
    formGroup
    (
        set::width('1/2'),
        set::label($lang->approval->forwardTo),
        set::name('forwardTo'),
        set::control('picker'),
        set::required(true),
        set::items($users)
    ),
    formGroup
    (
        set::label($lang->approval->forwardOpinion),
        set::name('forwardOpinion'),
        set::required(true),
        set::control('editor')
    ),
    formHidden('currentNodeID', $currentNode->id),
    set::actions(array(
        array('text' => $lang->save,   'btnType' => 'submit', 'type' => 'primary'),
        array('text' => $lang->cancel, 'class'   => 'cancel-revert', 'data-dismiss' => 'modal')
    ))
);
