<?php
/**
 * The close view file of feedback module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@chandao.com>
 * @package     feedback
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader();
formPanel
(
    set::id('closeFeedbackForm'),
    formGroup
    (
        set::label($lang->feedback->closedReason),
        set::width('1/3'),
        picker
        (
            set::name('closedReason'),
            set::items($lang->feedback->closedReasonList),
            set::value($closedReason),
            on::change('reasonChange')
        )
    ),
    formGroup
    (
        setClass('repeatFeedbackBox hidden'),
        set::label($lang->feedback->repeatFeedback),
        set::width('1/3'),
        set::required(true),
        picker
        (
            set::name('repeatFeedback'),
            set::items($feedbacks)
        )
    ),
    formGroup
    (
        set::label($lang->comment),
        set::name('comment'),
        set::control('editor'),
        set::rows(6)
    ),
    formHidden('status', 'closed')
);
