<?php
namespace zin;

jsVar('currentReviewers', array_values($currentReviewers));
if(count($currentReviewers) < 2) unset($lang->approval->addNodeMethodList['current']);

$noticeTypeLang = $lang->approvalflow->noticeTypeList;
set::title($lang->approval->addNode);
formPanel
(
    set::labelWidth('100px'),
    on::change('[name=addNodeMethod]', 'changeNodeType'),
    formGroup
    (
        set::label($lang->approval->addNodeMethod),
        set::name('addNodeMethod'),
        set::control('radioListInline'),
        set::required(true),
        set::value('next'),
        set::items($lang->approval->addNodeMethodList)
    ),
    formGroup
    (
        set::label($lang->approval->nodeName),
        set::name('addNodeTitle'),
        set::control('input'),
        set::required(true),
        set::value($lang->approval->addNodeTitle)
    ),
    formGroup
    (
        set::label($lang->approval->reviewer),
        set::name('reviewer'),
        set::control('picker'),
        set::multiple(true),
        set::required(true),
        set::items($users)
    ),
    formGroup
    (
        setClass('multipleType'),
        set::label($lang->approval->multipleType),
        set::width('full'),
        div
        (
            setClass('check-list'),
            checkbox
            (
                set::name('multiple'), set::type('radio'), set::text($noticeTypeLang['multipleAnd']), set::value('and'), set::checked(true),
                setData(array('on' => 'change', 'call' => 'changeMultiple', 'params' => 'event')),
                checkBox(set::name('needAll'), set::text($lang->approvalflow->needAll), set::value(1))
            ),
            checkbox(set::name('multiple'), set::type('radio'), set::text($noticeTypeLang['multipleOr']), set::value('or'), setData(array('on' => 'change', 'call' => 'changeMultiple', 'params' => 'event')), checkBox(set::name('needAll'), set::text($lang->approvalflow->needAll), set::value(2), set::rootClass('mx-4 hidden'))),
            checkbox
            (
                set::rootClass('items-center h-5'),
                set::name('multiple'), set::type('radio'), set::text($noticeTypeLang['multiplePercent']), set::value('percent'),
                setData(array('on' => 'change', 'call' => 'changeMultiple', 'params' => 'event')),
                div
                (
                    setClass('input-control flex items-center has-suffix-sm w-28 mx-4 hidden'),
                    cell(setClass('flex-1'), set::width('1/1'), $lang->approvalflow->percent),
                    input(set::name('percent'), set::value('50'), setData(array('on' => 'change', 'call' => 'checkPercent', 'params' => 'event'))),
                    span(setClass('input-control-suffix'), '%')
                ),
                checkBox(set::name('needAll'), set::text($lang->approvalflow->needAll), set::value(3), set::rootClass('hidden'))
            ),
            checkbox(set::name('multiple'), set::type('radio'), set::text($noticeTypeLang['multipleSolicit']), set::value('solicit'), setData(array('on' => 'change', 'call' => 'changeMultiple', 'params' => 'event')))
        )
    ),
    formGroup
    (
        set::label($lang->approval->addNodeOpinion),
        set::name('addNodeOpinion'),
        set::required(true),
        set::control('editor')
    ),
    formHidden('currentNodeID', $currentNode->id),
    set::actions(array(
        array('text' => $lang->save,   'btnType' => 'submit', 'type' => 'primary'),
        array('text' => $lang->cancel, 'data-dismiss' => 'modal')
    ))
);
