<?php
/**
 * The step filter view file of pivot design module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenxuan Song <songchenxuan@easycorp.ltd>
 * @package     pivot
 * @link        https://www.zentao.net
 */
namespace zin;

$fnGenerateEmptyTip = function($canUseResult)
{
    return div
    (
        setClass('filter-empty-tip'),
        div
        (
            setClass('flex items-center'),
            div(setClass('text-gray-500'), $this->lang->pivot->noQueryTip),
            div
            (
                toolbar
                (
                    setClass('pr-1 add-button'),
                    btn
                    (
                        setClass('ghost squre filter-add'),
                        set::icon('plus'),
                        set::disabled(!$canUseResult),
                        set::hint(!$canUseResult ? $this->lang->pivot->cannotAddResult : null),
                        $this->lang->pivot->resultFilter,
                        on::click()->do('addFilter(event)')
                    )
                )
            )
        )
    );
};

$fnGenerateFilterFormControl = function($class, $label, $children)
{
    return div
    (
        setClass('flex items-center', $class),
        div
        (
            setClass('body-th text-right pr-4'),
            $label
        ),
        div
        (
            setClass('body-picker'),
            $children
        )
    );
};

$fnGenerateFilterFormItems = function($filter) use ($lang, $config, $pivotState, $fnGenerateFilterFormControl)
{
    $isSelect      = strpos($filter['type'], 'select') !== false;
    $options       = $pivotState->getFieldOptions();
    $isQueryFilter = $pivotState->isQueryFilter();
    $optionUrl     = $this->getFilterOptionUrl($filter, $pivotState->sql, $pivotState->fieldSettings);

    return array
    (
        $fnGenerateFilterFormControl
        (
            '',
            $lang->pivot->type,
            picker
            (
                set::disabled($isQueryFilter),
                set::required(),
                set::name('type'),
                set::value($filter['type']),
                set::items($isQueryFilter ? $lang->dataview->varFilter->requestTypeList : $lang->pivot->fieldTypeList),
                on::change()->do("changeFilter(event, 'type')")
            )
        ),
        $fnGenerateFilterFormControl
        (
            '',
            $isQueryFilter ? $lang->dataview->varFilter->varCode : $lang->pivot->field,
            $isQueryFilter ? input(set::disabled(), set::name('field'), set::value($filter['field'])) : picker
            (
                set::required(),
                set::name('field'),
                set::value($filter['field']),
                set::items($options),
                on::change()->do("changeFilter(event, 'field')")
            )
        ),
        !$isQueryFilter ? $fnGenerateFilterFormControl
        (
            array('hidden' => !$isSelect),
            $lang->pivot->showAs,
            picker
            (
                set::name('saveAs'),
                set::value(isset($filter['saveAs']) ? $filter['saveAs'] : ''),
                set::items($options),
                on::change()->do("changeFilter(event, 'saveAs')")

            )
        ) : null,
        $fnGenerateFilterFormControl
        (
            '',
            $lang->pivot->default,
            $isQueryFilter ? filter
            (
                set::type($filter['type']),
                set::layout('normal'),
                set::name('default'),
                set::value($filter['default']),
                set::multiple($filter['type'] == 'multipleselect'),
                set::items($optionUrl),
                set::onChange('changeFilterDefault')
            ) : resultFilter
            (
                set::type($filter['type']),
                set::layout('normal'),
                set::name('default'),
                set::value($filter['default']),
                set::items($optionUrl),
                set::onChange('changeFilterDefault')
            )
        ),
        $fnGenerateFilterFormControl
        (
            '',
            $lang->pivot->name,
            input
            (
                set::disabled($isQueryFilter),
                set::name('name'),
                set::value($filter['name']),
                on::change()->do("changeFilter(event, 'name')")
            )
        )
    );
};

$fnGenerateFilterItem = function($filter, $index) use ($lang, $fnGenerateFilterFormItems)
{
    $from  = \zget($filter, 'from', 'result');
    $field = $filter['field'];
    $name  = $filter['name'];

    $titleDiv = div
    (
        setClass('flex column-header-begin items-center gap-4'),
        div
        (
            setClass('text-base font-semibold'),
            $this->lang->pivot->{"{$from}Filter"} . '-' . $name
        )
    );

    return div
    (
        setClass('pl-1 flex col gap-y-3 filter-form'),
        setID("filterIndex$index"),
        set('data-index', $index),
        div
        (
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
                        $titleDiv,
                        toolbar
                        (
                            setClass('flex', array('hidden' => $from == 'query')),
                            btn
                            (
                                setClass('ghost size-sm squre filter-delete'),
                                set::icon('minus'),
                                on::click()->do('removeFilter(event)')
                            )
                        )
                    )
                ),
                div
                (
                    setClass('column-body pt-3'),
                    div
                    (
                        setClass('flex col gap-y-3'),
                        $fnGenerateFilterFormItems($filter)
                    )
                )
            )
        )
    );
};

$fnGenerateFilters = function($filters) use ($fnGenerateFilterItem)
{
    $items = array();
    foreach($filters as $index => $filter) $items[] = $fnGenerateFilterItem($filter, $index);

    return div
    (
        setClass('py-3 border-b flex col gap-y-4'),
        $items
    );
};

$fnGenerateStepFilterConfig = function() use ($lang, $pivotState, $fnGenerateEmptyTip, $fnGenerateFilters)
{
    $filters = $pivotState->filters;
    $canUseResult = !$pivotState->isQueryFilter();

    return pivotConfig
    (
        set::title($lang->pivot->filter),
        set::saveText($lang->pivot->saveSetting),
        set::onSave('saveFilters(event)'),
        set::onNext('nextStep()'),
        to::heading
        (
            div
            (
                setClass('flex'),
                toolbar
                (
                    setClass('pr-1 add-button'),
                    btn
                    (
                        setClass('ghost squre filter-add'),
                        set::icon('plus'),
                        set::text($this->lang->pivot->resultFilter),
                        set::disabled(!$canUseResult),
                        !$canUseResult ? set::hint($this->lang->pivot->cannotAddResult) : null,
                        on::click()->do('addFilter(event)')
                    )
                )
            )
        ),
        div
        (
            setID('filterForm'),
            empty($filters) ? $fnGenerateEmptyTip($canUseResult) : $fnGenerateFilters($filters)
        )
    );
};
