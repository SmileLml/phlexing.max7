<?php
/**
 * The create view file of dataview module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Xin Zhou <zhouxin@easycorp.ltd>
 * @package     dataview
 * @link        https://www.zentao.net
 */
namespace zin;
$this->app->loadLang('bi');

formPanel
(
    setID('createForm'),
    set::title($title),
    formGroup
    (
        setID('groupControl'),
        set::label($lang->dataview->group),
        set::required(true),
        picker
        (
            set::name('group'),
            set::items($groups),
            on::change()->do('handleChangeGroup(event)')
        ),
        span(setClass('text-danger hidden'))
    ),
    formGroup
    (
        setID('nameControl'),
        set::label($lang->dataview->name),
        set::required(true),
        input
        (
            set::name('name'),
            on::change()->do('handleChangeName(event)')
        ),
        span(setClass('text-danger hidden'))
    ),
    formGroup
    (
        setID('codeControl'),
        set::label($lang->dataview->code),
        set::required(true),
        input
        (
            set::name('code'),
            on::change()->do('handleChangeCode(event)')
        ),
        span(setClass('text-danger hidden'))
    ),
    formGroup(set::label($lang->bi->driver), picker
    (
        set::name('driver'),
        set::items($lang->bi->driverList),
        set::required(true)
    )),
    set::submitBtnText($lang->save)
);
