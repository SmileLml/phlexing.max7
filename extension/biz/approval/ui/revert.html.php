<?php
namespace zin;

set::title($lang->approval->revert);
formPanel
(
    set::labelWidth('100px'),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->approval->toNodeID),
        set::name('toNodeID'),
        set::control('picker'),
        set::required(true),
        set::items($canRevertNodes)
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->approval->revertType),
        set::name('revertType'),
        set::control('radioListInline'),
        set::items($lang->approval->revertTypeList),
        set::value('order')
    ),
    formGroup
    (
        set::label($lang->approval->revertOpinion),
        set::name('revertOpinion'),
        set::required(true),
        set::control('editor')
    ),
    formHidden('currentNodeID', $currentNode->id),
    set::actions(array(
        array('text' => $lang->save,   'btnType' => 'submit', 'type' => 'primary'),
        array('text' => $lang->cancel, 'class'   => 'cancel-revert', 'data-dismiss' => 'modal')
    ))
);
