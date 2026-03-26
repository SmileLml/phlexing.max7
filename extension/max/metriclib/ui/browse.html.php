<?php
/**
 * The browse view file of company module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     company
 * @link        https://www.zentao.net
 */
namespace zin;

$fnGenerateScopeFilter = function() use ($scope, $parentScope, $scopeOptions, $parentOptions, $filters)
{
    $parentFilter = formGroup
    (
        set::id('parentPicker'),
        set::width('1/3'),
        setClass('query-inline picker-nowrap'),
        set::label($this->lang->metriclib->parentLabel[$parentScope]),
        set::control(array('type' => 'picker', 'multiple' => true)),
        set::name('parent'),
        set::items($parentOptions),
        set::placeholder($this->lang->metric->placeholder->$parentScope),
        on::change("window.loadScopeOptions('$scope')"),
        !empty($filters['parent']) ? set::value($filters['parent']) : null
    );

    $scopeFilter = formGroup
    (
        set::id('scopePicker'),
        set::width('1/3'),
        setClass('query-inline picker-nowrap'),
        set::label($this->lang->$scope->common),
        set::control(array('type' => 'picker', 'multiple' => true)),
        set::name('objectType'),
        set::items($scopeOptions),
        set::placeholder($this->lang->metric->placeholder->$scope),
        !empty($filters['objectType']) ? set::value($filters['objectType']) : null
    );

    return array($parentFilter, $scopeFilter);
};

$fnGenerateDateFilter = function() use ($scope, $period, $filters)
{
    $width = $scope == 'system' ? '1/2' : '1/3';
    return formGroup
    (
        set::label($this->lang->metriclib->dateLabel[$period]),
        set::width($width),
        inputGroup
        (
            datePicker
            (
                setClass('query-date-picker'),
                set::name('dateBegin'),
                set('id', 'dateBegin'),
                set::placeholder($this->lang->metric->placeholder->select),
                !empty($filters['dateBegin']) ? set::value($filters['dateBegin']) : null
            ),
            $this->lang->metric->to,
            datePicker
            (
                setClass('query-date-picker'),
                set::name('dateEnd'),
                set('id', 'dateEnd'),
                set::placeholder($this->lang->metric->placeholder->select),
                !empty($filters['dateEnd']) ? set::value($filters['dateEnd']) : null
            )
        )
    );
};

$fnGenerateFilterForm = function() use ($isHistory, $metricOptions, $filters, $fnGenerateScopeFilter, $fnGenerateDateFilter, $scope, $period, $viewType)
{
    $formGroups = array();
    if($isHistory)
    {
        if($scope != 'system') $formGroups = $fnGenerateScopeFilter();
        $formGroups[] = $fnGenerateDateFilter();
    }

    $metricFilter = formGroup(set::label($this->lang->metric->common), setClass('query-inline picker-nowrap'), set::name('metric'), set::control(array('type' => 'picker', 'multiple' => true)), set::items($metricOptions), set::value($filters['metric']));

    return row
    (
        set::justify('start'),
        set::align('center'),
        setClass('px-4'),
        cell
        (
            set::flex('auto'),
            form
            (
                set::id('queryForm'),
                !empty($formGroups) ? formRow(set::width('full'), $formGroups) : null,
                formRow
                (
                    set::width('full'),
                    $metricFilter
                ),
                set::actions(array())
            )

        ),
        cell
        (
            set::width(100),
            setClass('ml-4'),
            set::flex('none'),
            formGroup
            (
                setClass('query-btn'),
                btn
                (
                    setClass('btn secondary w-full'),
                    set::text($this->lang->metric->query->action),
                    set::onclick("window.handleFilter('$scope', '$period', '$viewType')")
                )
            )
        )
    );
};

sidebar
(
    moduleMenu
    (
        to::header
        (
            div
            (
                setClass('bg-canvas'),
                dropmenu
                (
                    set::defaultValue($scope),
                    set::text($scopeText),
                    set::caret(false),
                    set::popWidth(128),
                    set::popClass('popup text-md'),
                    set::data(array('search' => false, 'checkIcon' => false, 'data' => $scopeMenu))
                )
            )
        ),
        set::titleShow(false),
        set::modules($libTree),
        set::activeKey("{$scope}_{$period}"),
        set::closeLink(''),
        set::showDisplay(false)
    )
);

div
(
    setClass('main'),
    div
    (
        setClass('canvas'),
        div
        (
            setClass('border-b h-12 px-4 flex justify-between items-center'),
            div(span(setClass('text-root font-bold'), $libName), (isset($latestDate) and $latestDate) ? label
            (
                setClass('gray-200-pale pl-4'),
                $lang->metriclib->calcDate . ':' . date($lang->metriclib->calcDateFormat, strtotime($latestDate))
            ) : null),
            div
            (
                setClass('justify-start'),
                toolbar
                (
                    $canChangeView ? btn
                    (
                        set::icon('exchange'),
                        set::iconClass('text-xl'),
                        setClass('ghost primary hover-primary'),
                        set::url($this->inlink('browse', "scope=$scope&period=$period&viewType=$viewBtnType&isClearFilter=0")),
                        $viewBtnText
                    ) : null
                    /*
                    haspriv('metriclib', 'details') ? item(set(array
                    (
                        'text'        => $this->lang->metriclib->details,
                        'class'       => 'ghost primary hover-primary',
                        'url'         => $this->inlink('details', "scope=$scope&period=$period"),
                        'data-toggle' => 'modal'
                    ))) : null
                     */
                )
            )
        ),
        div(setClass('pt-4'), $fnGenerateFilterForm()),
        div
        (
            setClass('p-4'),
            dtable
            (
                set::id('library'),
                set::bordered(true),
                setClass('shadow rounded'),
                set::height(jsRaw('window.innerHeight - ' . ($isHistory ? '252' : '222'))),
                set::rowHeight(32),
                set::scrollbarSize(8),
                set::header($isHistory),
                set::headerHeight($tableHeaderHeight),
                set::cols($cols),
                set::data($data),
                set::nested(true),
                set::footPager(usePager('dtablePager', '', array(
                    'recPerPage' => $dtablePager->recPerPage,
                    'recTotal' => $dtablePager->recTotal,
                    'linkCreator' => createLink('metriclib', 'browse', "scope=$scope&period=$period&viewType=$viewType&isClearFilter=0&recTotal={$dtablePager->recTotal}&recPerPage={recPerPage}&page={page}"),
                ),
                $config->metriclib->pageRangeList)),
                set::onRenderCell(jsRaw('window.renderCell')),
                set::onRenderHeaderCell(jsRaw('window.renderHeaderCell')),
                set::emptyTip($libDTableTip),
                set::createTip($lang->metriclib->createMetric),
                set::createLink(hasPriv('metric', 'create') ? createLink('metric', 'create', "scope=$scope&period=$period&from=metriclib") : ''),
                set::createAttr("data-toggle='modal'")
            )
        )
    )
);

render();
