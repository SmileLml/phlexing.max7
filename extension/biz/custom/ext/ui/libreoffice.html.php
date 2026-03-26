<?php
/**
 * The steps view file of deploy module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang<yidong@easycorp.ltd>
 * @package     custom
 * @link        https://www.zentao.net
 */
namespace zin;

if($config->requestType != 'PATH_INFO') unset($lang->custom->typeList['collabora']);
formPanel
(
    setID('setOfficeForm'),
    set::title($lang->custom->libreOffice),
    set::actions(array('submit')),
    formGroup
    (
        set::label($lang->custom->libreOfficeTurnon),
        radioList(set::name('libreOfficeTurnon'), set::items($lang->custom->turnonList), set::value($config->file->libreOfficeTurnon), set::inline(true))
    ),
    formGroup
    (
        set::label($lang->custom->type),
        on::change('[name=convertType]', 'togglePathBoxByType'),
        radioList(set::name('convertType'), set::items($lang->custom->typeList), set::value(zget($config->file, 'convertType', 'libreoffice')), set::inline(true))
    ),
    formRow
    (
        setClass('libreofficeBox'),
        formGroup
        (
            set::label($lang->custom->libreOfficePath),
            input(set::name('sofficePath'), set::value(zget($config->file, 'sofficePath', '')), set::autocomplete('off'), set::placeholder($lang->custom->sofficePlaceholder))
        )
    ),
    formRow
    (
        setClass('collaboraBox hidden'),
        formGroup
        (
            set::label($lang->custom->collaboraPath),
            input(set::name('collaboraPath'), set::value(zget($config->file, 'collaboraPath', '')), set::autocomplete('off'), set::placeholder($lang->custom->collaboraPlaceholder))
        )
    )
);
