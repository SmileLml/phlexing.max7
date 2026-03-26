<?php
/**
 * The batchEdit view file of feedback module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     feedback
 * @link        https://www.zentao.net
 */
namespace zin;
if(!empty($errorTips)) pageJS("zui.Modal.alert({message: {html: '{$errorTips}'}, icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'});\n");
formBatchPanel
(
    set::title($lang->feedback->batchClose),
    set::mode('edit'),
    set::data(array_values($feedbacks)),
    on::change('[data-name="closedReasons"]', 'changeReason'),
    formBatchItem
    (
        set::name('id'),
        set::label($lang->idAB),
        set::control('index'),
        set::width('50px')
    ),
    formBatchItem
    (
        set::name('feedbackIdList'),
        set::control('hidden'),
        set::width('50px')
    ),
    formBatchItem(set::name('title'), set::label($lang->feedback->title), set::control('input'), set::disabled(true)),
    formBatchItem
    (
        set::name('status'),
        set::label($lang->feedback->status),
        set::control('picker'),
        set::items($lang->feedback->statusList),
        set::disabled(true),
        set::width('160px')
    ),
    formBatchItem
    (
        set::label($lang->feedback->closedReason),
        set::control('inputGroup'),
        set::width('330px'),
        inputGroup
        (
            picker
            (
                set::name('closedReasons'),
                set::items($reasonList)
            ),
            picker
            (
                setClass('duplicate-select hidden'),
                set::name('repeatFeedbackIDList'),
                set::placeholder($lang->bug->placeholder->duplicate),
                set::items($feedbackList)
            )
        )
    ),
    formBatchItem
    (
        set::name('comments'),
        set::label($lang->feedback->commentAB),
        set::control('input')
    )
);

render();

