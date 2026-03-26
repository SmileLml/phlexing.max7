<?php
namespace zin;

formPanel
(
    set::layout('grid'),
    set::actions(common::canModify('project', $project) ? array('submit') : array()),
    formGroup
    (
        set::label($this->lang->project->deliverableList['create']),
        set::strong(true),
        set::width('full'),
        set::hidden(true),
        deliverable
        (
            set::formName('whenCreated'),
            set::items($deliverables['create']),
            set::extraCategory('other')
        )
    ),
    formGroup
    (
        set::label($this->lang->project->deliverableList['close'] . ($project->status == 'closed' ? '' : $this->lang->project->whenClosedTips)),
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