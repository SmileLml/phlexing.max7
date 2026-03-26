<?php
/**
 * The export view file of dataview module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     dataview
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('type', $type);

formPanel
(
    set::formClass('form-ajax'),
    set::title($lang->export),
    set::ajax(array('closeModal' => 'onlySuccess')),
    formRow
    (
        formGroup
        (
            set::label($lang->setFileName),
            inputGroup
            (
                input
                (
                    set::name('fileName'),
                    set::value($fileName)
                ),
                picker
                (
                    zui::width('120px'),
                    set::name('fileType'),
                    set::items($lang->exportFileTypeList),
                    set::required()
                ),
                input
                (
                    set::type('hidden'),
                    set::name('sql')
                ),
                input
                (
                    set::type('hidden'),
                    set::name('fields')
                ),
                input
                (
                    set::type('hidden'),
                    set::name('langs')
                )
            )
        )
    )
);

render();
