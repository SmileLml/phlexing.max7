<?php
/**
 * The columnitem file of pivot design module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenxuan Song <songchenxuan@easycorp.ltd>
 * @package     pivot
 * @link        https://www.zentao.net
 */
namespace zin;

$fnGenPicker = function($showOrigin, $id, $field, $column, $options, $required, $isError = false)
{
    $value   = $showOrigin ? '' : $column[$field];
    $options = $showOrigin ? array() : $options[$field];
    $onChange = "changeSettingColumn" . ucfirst($field);
    return $showOrigin ? picker
    (
        setID($id),
        set::name($field),
        set::placeholder($this->lang->pivot->showOriginPlaceholder->$field),
        set::items($options),
        set::disabled(true)
    ) : picker
    (
        setID($id),
        $isError ? setClass('has-error') : null,
        set::name($field),
        set::value($value),
        set::items($options),
        set::required($required),
        on::change()->do("$onChange(event)")
    );
};

$fnGenerateColumnItem = function($index, $column, $itemCount, $options, $errors) use ($pivotState, $fnGenPicker)
{
    $showDelete      = $itemCount != 1;
    $checkOrigin     = $column['showOrigin'] === 1;
    $showTotal       = (!$checkOrigin && $column['slice'] != 'noSlice');
    $showMonopolize  = (!$checkOrigin && $column['showMode'] != 'default');
    $checkMonopolize = (!$checkOrigin && $column['monopolize'] === 1);

    $isFieldError = isset($errors['field']);
    $fieldError   = $this->lang->pivot->emptyColumnFieldError;
    $isStatError  = isset($errors['stat']);
    $statError    = $this->lang->pivot->emptyColumnStatError;

    return div
    (
        setClass('pl-1 column-line'),
        set('data-index', $index),
        div
        (
            setID("columnIndex$index"),
            setClass('column'),
            div
            (
                setClass('p-3'),
                div
                (
                    setClass('column-header border-bottom-300 pb-3'),
                    div
                    (
                        setClass('flex justify-between'),
                        div(setClass('flex column-header-begin items-center gap-4'), div
                        (
                            setClass('text-base font-semibold'),
                            sprintf($this->lang->pivot->columnIndex, $index)
                        ), div
                        (
                            setClass('picker-column'),
                            picker
                            (
                                setID("picker_column$index"),
                                $isFieldError ? setClass('has-error') : null,
                                set::name('column'),
                                set::value($column['field']),
                                set::items($options['fields']),
                                on::change()->do('changeSettingColumnField(event)')
                            ),
                            $isFieldError ? span
                            (
                                setClass('text-danger'),
                                $fieldError
                            ) : null
                        ), div
                        (
                            checkbox
                            (
                                setID("checkbox_showOrigin$index"),
                                set::name('showOrigin[]'),
                                set::checked($checkOrigin),
                                set::text($this->lang->pivot->showOriginItem),
                                on::change()->do('changeSettingColumnOrigin(event)')
                            )
                        )),
                        $showDelete ? toolbar
                        (
                            setClass('flex'),
                            btn
                            (
                                setClass('ghost size-sm squre column-delete'),
                                set::icon('minus'),
                                on::click()->do('deleteSettingColumn(event)')
                            )
                        ) : null
                    )
                ),
                div
                (
                    setClass('column-body pt-3'),
                    div
                    (
                        setClass('flex col gap-y-3'),
                        div
                        (
                            setClass('flex items-center'),
                            div
                            (
                                setClass('body-th text-right pr-4'),
                                $this->lang->pivot->slice
                            ),
                            div
                            (
                                setClass('body-picker'),
                                $fnGenPicker($checkOrigin, "picker_slice$index", 'slice', $column, $options, true)
                            )
                        ),
                        div
                        (
                            setClass('flex items-center'),
                            div
                            (
                                setClass('body-th text-right pr-4'),
                                $this->lang->pivot->stat
                            ),
                            div
                            (
                                setClass('body-picker'),
                                $fnGenPicker($checkOrigin, "picker_stat$index", 'stat', $column, $options, false, $isStatError),
                                $isStatError ? span
                                (
                                    setClass('text-danger'),
                                    $statError
                                ) : null
                            )
                        ),
                        div
                        (
                            setClass('flex items-center'),
                            div
                            (
                                setClass('body-th text-right pr-4'),
                                $this->lang->pivot->showMode
                            ),
                            div
                            (
                                setClass($showMonopolize ? 'showmode-picker' : 'body-picker'),
                                $fnGenPicker($checkOrigin, "picker_showMode$index", 'showMode', $column, $options, true)
                            ),
                            $showMonopolize ? div
                            (
                                setClass('pl-2'),
                                checkbox
                                (
                                    setID("checkbox_monopolize$index"),
                                    set::name('monopolize[]'),
                                    set::checked($checkMonopolize),
                                    set::text($this->lang->pivot->monopolize),
                                    on::change()->do('changeSettingColumnMonopolize(event)')
                                )
                            ) : null
                        ),
                        div
                        (
                            setClass('flex items-center' . (!$showTotal ? ' hidden' : '')),
                            div
                            (
                                setClass('body-th text-right pr-4'),
                                $this->lang->pivot->showTotal
                            ),
                            div
                            (
                                setClass('body-picker'),
                                picker
                                (
                                    setID("picker_showTotal$index"),
                                    set::name('showTotal'),
                                    set::value(zget($column, 'showTotal', 'noShow')),
                                    set::items($options['showTotal']),
                                    set::required(true),
                                    on::change()->do('changeShowTotal(event)')
                                )
                            )
                        )
                    )
                )
            )
        )
    );
};
