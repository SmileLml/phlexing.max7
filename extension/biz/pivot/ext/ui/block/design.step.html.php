<?php
/**
 * The step design view file of pivot design module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenxuan Song <songchenxuan@easycorp.ltd>
 * @package     pivot
 * @link        https://www.zentao.net
 */
namespace zin;

include_once 'groupitem.html.php';
include_once 'columnitem.html.php';

$fnGenerateGroups = function() use ($lang, $pivotState, $settingErrors, $fnGenerateGroupItem)
{
    $options           = array();
    $options['fields'] = $pivotState->getFieldOptions();

    $items = array();
    $groups = $pivotState->getSettingGroups();
    foreach($groups as $key => $value) $items[] = $fnGenerateGroupItem($key, $value, count($groups), $options, $settingErrors);

    return div
    (
        setID('groupSetting'),
        setClass('pb-3 border-b'),
        div
        (
            setClass('flex text-base font-bold pl-1 py-3 leading-4 items-center'),
            $lang->pivot->stepDesign->group,
            div
            (
                setClass('flex items-center pl-1'),
                sqlBuilderHelpIcon(set::text($lang->pivot->stepDesign->groupsTip))
            )
        ),
        div
        (
            setClass('flex col gap-4'),
            $items
        )
    );
};

$fnGenerateColumns = function() use ($lang, $pivotState, $settingErrors, $fnGenerateColumnItem)
{
    $options               = array();
    $fields                = $pivotState->getFieldOptions();
    $options['fields']     = $fields;
    $options['slice']      = $lang->pivot->stepDesign->sliceFieldList + $fields;
    $options['stat']       = $lang->pivot->stepDesign->statList;
    $options['showMode']   = $lang->pivot->stepDesign->showModeList;
    $options['monopolize'] = array('1' => $lang->pivot->monopolize);
    $options['showTotal']  = $lang->pivot->stepDesign->showTotalList;

    $items   = array();
    $columns = $pivotState->getSettingColumns();
    $index   = 1;
    foreach($columns as $column)
    {
        $errors = isset($settingErrors['columns'][$index - 1]) ? $settingErrors['columns'][$index - 1] : array();
        $items[] = $fnGenerateColumnItem($index, $column, count($columns), $options, $errors);
        $index ++;
    }

    return div
    (
        setID('columnSetting'),
        setClass('pb-3 border-b'),
        div
        (
            setClass('flex justify-between text-base font-bold pl-1 py-3'),
            div
            (
                setClass('flex items-center'),
                $lang->pivot->stepDesign->column,
                div
                (
                    setClass('flex pl-1'),
                    sqlBuilderHelpIcon(set::text($lang->pivot->stepDesign->columnsTip))
                )
            ),
            div
            (
                setClass('flex'),
                toolbar
                (
                    setClass('pr-1 add-button'),
                    btn
                    (
                        setClass('ghost size-sm squre column-add'),
                        set::icon('plus'),
                        $lang->pivot->addColumn,
                        on::click()->do('addSettingColumn(event)')
                    )
                )
            )
        ),
        div
        (
            setID('columnContainer'),
            setClass('flex col gap-4'),
            $items
        )
    );
};

$fnGenerateSummary = function() use ($lang, $pivotState)
{
    $summary = $pivotState->settings['summary'];

    return div
    (
        setClass('flex text-base font-bold pl-1 py-3 border-b leading-4 items-center'),
        checkbox
        (
            setClass('pt-0.5'),
            set::id('summary'),
            set::name('summary[]'),
            set::checked($summary === 'use'),
            set::text($lang->pivot->stepDesign->summary),
            on::change()->do('handleSummaryChange(event)')
        ),
        div
        (
            setClass('flex items-center pl-1'),
            sqlBuilderHelpIcon(set::text($lang->pivot->stepDesign->summaryTip))
        )
    );
};

$fnGenerateColumnSummary = function() use ($lang, $pivotState)
{
    $columnTotal    = $pivotState->settings['columnTotal'];
    $columnPosition = $pivotState->settings['columnPosition'];
    $showPosition   = $columnTotal == 'sum';
    return div
    (
        setID('summary-column'),
        div(setClass('flex text-base font-bold pl-1 py-3'), div
        (
            setClass('flex items-center leading-4'),
            $lang->pivot->stepDesign->columnTotal,
            div
            (
                setClass('flex pl-1'),
                sqlBuilderHelpIcon(set::text($lang->pivot->stepDesign->columnTotalTip))
            )
        )),
        div
        (
            setClass('pl-1 py-2 picker-columnTotal'),
            div
            (
                setClass('flex items-center'),
                div
                (
                    setClass('whitespace-nowrap pr-2'),
                    $lang->pivot->stepDesign->columnCalc
                ),
                div
                (
                    setClass('w-full'),
                    picker
                    (
                        setID('picker_columnTotal'),
                        set::name('columnTotal'),
                        set::value($columnTotal),
                        set::required(true),
                        set::items($lang->pivot->stepDesign->columnTotalList),
                        on::change()->do('handleColumnTotalChange(event)')
                    )
                )
            )
        ),
        $showPosition ? div
        (
            setClass('pl-1 py-2 picker-columnTotal'),
            div
            (
                setClass('flex items-center'),
                div
                (
                    setClass('whitespace-nowrap pr-2'),
                    $lang->pivot->stepDesign->columnPosition
                ),
                div
                (
                    setClass('w-full'),
                    picker
                    (
                        setID('picker_columnPosition'),
                        set::name('columnPosition'),
                        set::value($columnPosition),
                        set::required(true),
                        set::items($lang->pivot->stepDesign->columnPositionList),
                        on::change()->do('handleColumnPositionChange(event)')
                    )
                )
            )
        ) : null
    );
};

$fnGenerateStepDesignConfig = function($pivotState) use($lang, $fnGenerateGroups, $fnGenerateColumns, $fnGenerateSummary, $fnGenerateColumnSummary)
{
    $summary = $pivotState->settings['summary'];
    return pivotConfig
    (
        set::title($lang->pivot->baseSetting),
        set::saveText($lang->pivot->saveSetting),
        set::onSave('saveSettings()'),
        set::onNext('nextStep()'),
        $fnGenerateSummary(),
        $summary === 'use' ? div
        (
            setID('summaryForm'),
            $fnGenerateGroups(),
            $fnGenerateColumns(),
            $fnGenerateColumnSummary()
        ) : null
    );
};
