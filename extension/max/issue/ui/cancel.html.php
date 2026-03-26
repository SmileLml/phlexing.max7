<?php
/**
 * The cancel view file of issue module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     issue
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::title($lang->issue->cancel));

formPanel
(
    set::submitBtnText($lang->issue->cancel),
    formGroup
    (
        set::width('1/3'),
        set::label($lang->issue->title),
        set::control('static'),
        set::value($issue->title)
    ),
    formGroup
    (
        set::label($lang->issue->desc),
        set::name('desc'),
        set::control('editor'),
        set::value($issue->desc),
        set::rows(6)
    )
);
