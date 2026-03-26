<?php
/**
 * The edit view file of workflowdatasource module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     workflowdatasource
 * @link        https://www.zentao.net
 */
namespace zin;

$buildOptions = function() use ($lang, $datasource)
{
    $options = array();
    foreach($datasource->options as $key => $value)
    {
        $options[] = inputGroup
        (
            inputGroupAddon($lang->workflowdatasource->key),
            input(set::name('options[value][]'), set::value($key), set::placeholder($lang->workflowdatasource->placeholder->optionCode)),
            inputGroupAddon($lang->workflowdatasource->value),
            input(set::name('options[text][]'), set::value($value)),
            inputGroupAddon(button(setClass('btn-add'), icon('plus'))),
            inputGroupAddon(button(setClass('btn-del'), icon('minus')))
        );
    }
    if(!$options)
    {
        $options[] = inputGroup
        (
            inputGroupAddon($lang->workflowdatasource->key),
            input(set::name('options[value][]'), set::placeholder($lang->workflowdatasource->placeholder->optionCode)),
            inputGroupAddon($lang->workflowdatasource->value),
            input(set::name('options[text][]')),
            inputGroupAddon(button(setClass('btn-add'), icon('plus'))),
            inputGroupAddon(button(setClass('btn-del'), icon('minus')))
        );
    }
    return $options;
};

$buildParams = function() use($lang, $datasource)
{
    $params = array();
    foreach($datasource->params as $param)
    {
        $params[] = inputGroup
        (
            inputGroupAddon($lang->workflowdatasource->param),
            input
            (
                set::name('paramName[]'),
                set::value($param->name),
                set::title($param->name),
                set::readonly(true)
            ),
            inputGroupAddon($lang->workflowdatasource->paramType),
            input
            (
                set::name('paramType[]'),
                set::value($param->type),
                set::title($param->type),
                set::readonly(true)
            ),
            inputGroupAddon($lang->workflowdatasource->desc),
            input
            (
                set::name('paramDesc[]'),
                set::value($param->desc),
                set::title($param->desc),
                set::readonly(true)
            ),
            inputGroupAddon($lang->workflowdatasource->paramValue),
            input
            (
                set::name('paramValue[]'),
                set::value($param->value),
                set::title($param->value)
            )
        );
    }
    return $params;
};

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
        set::value($datasource->name),
        set::required(true)
    ),
    formGroup
    (
        set::label($lang->workflowdatasource->code),
        set::name('code'),
        set::value($datasource->code)
    ),
    formGroup
    (
        set::label($lang->workflowdatasource->type),
        set::name('type'),
        set::items($lang->workflowdatasource->typeList),
        set::value($datasource->type),
        set::required(true)
    ),
    formGroup
    (
        $datasource->type != 'option' ? setClass('hidden') : null,
        set::label($lang->workflowdatasource->typeList['option']),
        set::required(true),
        div
        (
            setID('options'),
            setClass('w-full'),
            $buildOptions()
        )
    ),
    formGroup
    (
        $datasource->type != 'sql' ? setClass('hidden') : null,
        set::label($lang->workflowdatasource->sql),
        set::control('textarea'),
        set::name('sql'),
        set::value($datasource->sql),
        set::placeholder($lang->workflowdatasource->placeholder->sql),
        set::required(true)
    ),
    formGroup
    (
        $datasource->type != 'sql' ? setClass('hidden') : null,
        set::label($lang->workflowdatasource->key),
        set::required(true),
        inputGroup
        (
            picker
            (
                set::name('keyField'),
                set::items($fields),
                set::value($datasource->keyField)
            ),
            inputGroupAddon($lang->workflowdatasource->value),
            picker
            (
                set::name('valueField'),
                set::items($fields),
                set::value($datasource->valueField)
            )
        ),
        div(setID('keyValue'))
    ),
    formGroup
    (
        $datasource->type != 'system' ? setClass('hidden') : null,
        set::label($lang->workflowdatasource->typeList['system']),
        set::required(true),
        div
        (
            setClass('w-full'),
            inputGroup
            (
                inputGroupAddon($lang->workflowdatasource->module),
                picker
                (
                    set::name('module'),
                    set::items($modules),
                    set::value($datasource->module)
                ),
                inputGroupAddon($lang->workflowdatasource->method),
                picker
                (
                    set::name('method'),
                    set::items($methods),
                    set::value($datasource->method)
                )
            ),
            inputGroup
            (
                inputGroupAddon($lang->workflowdatasource->desc),
                input
                (
                    set::name('methodDesc'),
                    set::value($datasource->methodDesc),
                    set::readonly(true)
                )
            ),
            div
            (
                setID('paramsDIV'),
                setClass('mt-2.5'),
                $buildParams()
            )
        )
    ),
    formGroup
    (
        $datasource->type != 'lang' ? setClass('hidden') : null,
        set::label($lang->workflowdatasource->typeList['lang']),
        set::name('lang'),
        set::items($lang->workflowdatasource->langList),
        set::value($datasource->lang),
        set::required(true)
    ),
    formHidden('app', '')
);

render();
