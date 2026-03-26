<?php
namespace zin;

formPanel
(
    set::layout('grid'),
    set::actions(array('submit')),
    formGroup
    (
        set::label($this->lang->project->deliverableList['close'] . ($execution->status == 'closed' ? '' : $this->lang->execution->whenClosedTips)),
        set::width('full'),
        set::strong(true),
        deliverable
        (
            set::formName('whenClosed'),
            set::items($deliverables['close']),
            set::extraCategory('other')
        )
    )
);

if(!empty($actions))
{
    history
    (
        setClass('panel panel-form size-lg is-lite'),
        set::commentBtn(''),
        set::editCommentUrl('')
    );
}