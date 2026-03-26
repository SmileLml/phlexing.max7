<?php
/**
 * The exportFiles view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    setID('exportFilesPanel'),
    set::title($lang->export),
    set::ajax(array('beforeSubmit' => jsRaw("clickSubmit"))),
    set::submitBtnText($lang->export),
    formGroup
    (
        set::label($lang->doc->export->fileName),
        set::name('fileName'),
        set::value($fileName)
    ),
    formGroup
    (
        set::label($lang->doc->export->range),
        picker
        (
            set::name('range'),
            set::items($lang->doc->exportFilesRanger),
            set::required(true)
        )
    ),
    formGroup
    (
        set::label($lang->doc->export->encode),
        picker
        (
            set::name('encode'),
            set::items(array('UTF-8')),
            set::required(true),
            set::disabled(true)
        )
    ),
    formGroup
    (
        set::label($lang->doc->export->format),
        radioList
        (
            set::name('format'),
            set::items(array('zip' => $lang->doc->zip)),
            set::value('zip'),
            set::inline(true)
        )
    )
);
