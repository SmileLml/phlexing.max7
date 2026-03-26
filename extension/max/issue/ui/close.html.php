<?php
/**
 * The close view file of issue module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     issue
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::title($lang->issue->close));

formPanel
(
    set::id('closePanel'),
    set::submitBtnText($lang->issue->close),
    formGroup(set::width('1/3'), set::label($lang->issue->closedDate), set::name('closedDate'), set::control('datePicker'), set::value(date('Y-m-d'))),
    formGroup
    (
        set::label($lang->comment),
        set::name('comment'),
        set::control('editor'),
        set::rows(6)
    )
);
