<?php
/**
 * The comment view file of feedback module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@chandao.com>
 * @package     feedback
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader();

formPanel
(
    set::submitBtnText($title),
    $type == 'replied' ? formGroup
    (
        set::label($lang->feedback->faq),
        set::width('1/2'),
        picker
        (
            set::name('faq'),
            set::items($faqs),
            on::change('faqChange')
        )
    ) : null,
    formGroup
    (
        set::label($title),
        set::name('comment'),
        set::required(true),
        set::control('editor'),
        set::rows(6)
    ),
    $type == 'asked' ? formGroup
    (
        set::label($lang->feedback->files),
        fileSelector()
    ) : null,
    formHidden('status', $type)
);
