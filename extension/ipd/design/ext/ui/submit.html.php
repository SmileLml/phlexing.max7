<?php
/**
 * The submit view file of design module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     design
 * @link        https://www.zentao.net
 */
namespace zin;

global $lang;
formPanel
(
    set::title($lang->design->submit),
    set::submitBtnText($lang->save),
    formGroup
    (
        set::label($lang->design->reviewObject),
        picker
        (
            set::name('object'),
            set::items(array_filter($typeList)),
            set::value($type),
            set::required(true)
        )
    ),
    formGroup
    (
        set::label($lang->design->reviewRange),
        picker
        (
            set::name('range'),
            set::items($lang->exportTypeList),
            set::required(true)
        )
    )
);
