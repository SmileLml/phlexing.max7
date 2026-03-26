<?php
/**
 * The create view file of workflowdatasource module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     workflowdatasource
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader();

formPanel
(
    on::change('#type', 'changeType'),
    on::focusout('#sql', 'changeSql'),
    on::change('[name=module]', 'changeModule'),
    on::change('[name=method]', 'changeMethod'),
    on::click('.btn-add', 'addOption'),
    on::click('.btn-del', 'delOption'),
    set::submitBtnText($lang->save),
    formGroup
    (
        set::label($lang->workflowdatasource->name),
        set::name('name'),
        set::required(true)
    ),
    formGroup
    (
        set::label($lang->workflowdatasource->code),
        set::name('code')
    ),
    formGroup
    (
        set::label($lang->workflowdatasource->type),
        set::name('type'),
        set::items($lang->workflowdatasource->typeList),
        set::required(true)
    ),
    formGroup
    (
        set::label($lang->workflowdatasource->typeList['option']),
        set::required(true),
        inputGroup
        (
            inputGroupAddon($lang->workflowdatasource->key),
            input(set::name('options[value][]'), set::placeholder($lang->workflowdatasource->placeholder->optionCode)),
            inputGroupAddon($lang->workflowdatasource->value),
            input(set::name('options[text][]')),
            inputGroupAddon(button(setClass('btn-add'), icon('plus'))),
            inputGroupAddon(button(setClass('btn-del'), icon('minus')))
        ),
        div(setID('options'))
    ),
    formGroup
    (
        setClass('hidden'),
        set::label($lang->workflowdatasource->sql),
        set::name('sql'),
        set::control('textarea'),
        set::placeholder($lang->workflowdatasource->placeholder->sql),
        set::required(true)
    ),
    formGroup
    (
        setClass('hidden'),
        set::label($lang->workflowdatasource->key),
        set::required(true),
        inputGroup
        (
            picker(set::name('keyField'), set::items(array())),
            inputGroupAddon($lang->workflowdatasource->value),
            picker(set::name('valueField'), set::items(array()))
        ),
        div(setID('keyValue'))
    ),
    formGroup
    (
        setClass('hidden'),
        set::label($lang->workflowdatasource->typeList['system']),
        set::required(true),
        div
        (
            setClass('w-full'),
            inputGroup
            (
                inputGroupAddon($lang->workflowdatasource->module),
                picker(set::name('module'), set::items($modules)),
                inputGroupAddon($lang->workflowdatasource->method),
                picker(set::name('method'), set::items(array()))
            ),
            inputGroup
            (
                inputGroupAddon($lang->workflowdatasource->desc),
                input(set::name('methodDesc'), set::readonly(true))
            ),
            div(setID('paramsDIV'), setClass('mt-2.5'))
        )
    ),
    formGroup
    (
        setClass('hidden'),
        set::label($lang->workflowdatasource->typeList['lang']),
        set::name('lang'),
        set::items($lang->workflowdatasource->langList),
        set::required(true)
    ),
    formHidden('app', '')
);

render();
