<?php
/**
 * The step query view file of pivot design module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenxuan Song <songchenxuan@easycorp.ltd>
 * @package     pivot
 * @link        https://www.zentao.net
 */
namespace zin;

$fnGenerateStepQueryContent = function() use ($pivotState, $lang, $tableList, $tableOptions, $fnGenerateCommonFilters, $fnGenerateDictionary)
{
    $step          = $pivotState->step;
    $filters       = $fnGenerateCommonFilters();
    $mode          = $pivotState->mode;
    $canChangeMode = $pivotState->canChangeMode;
    $isTextMode    = $mode == 'text';

    $canUseQuery = $pivotState->isQueryFilter() || empty($pivotState->filters);
    $isQueryStep = $pivotState->step == 'query';

    if($canUseQuery)
    {
        $modalFilters = $pivotState->filters;
        foreach($modalFilters as $index => $filter)
        {
            $optionUrl  = $this->getFilterOptionUrl($filter, $pivotState->sql, $pivotState->fieldSettings);
            $modalFilters[$index]['items'] = $optionUrl;
        }

        $addQueryFilter = $pivotState->addQueryFilter;
        if(!empty($addQueryFilter) && strpos($addQueryFilter['type'], 'select') !== false) $addQueryFilter['items'] = $this->getFilterOptionUrl($addQueryFilter, $pivotState->sql, $pivotState->fieldSettings);
    }

    return div
    (
        setID("step$step"),
        div
        (
            queryBase
            (
                set::title($lang->bi->sqlQuery),
                set::titleTip($lang->pivot->step1QueryTip),
                set::mode($mode),
                set::sql($pivotState->sql),
                set::cols($pivotState->queryCols),
                set::data($pivotState->queryData),
                set::settings($pivotState->fieldSettings),
                set::tableOptions($tableOptions),
                set::error($pivotState->errorMsg),
                set::pager(usePager('pager', 'customLink', null, null, 'window.postQueryResult')),
                set::onQuery('ajaxQuery()'),
                set::onSqlChange('handleSqlChange()'),
                set::onSaveFields('saveFields()'),
                to::heading
                (
                    div
                    (
                        setClass('absolute right-4 top-2'),
                        modalTrigger
                        (
                            btn
                            (
                                setClass('ghost', array('hidden' => $isTextMode)),
                                $lang->bi->previewSql
                            ),
                            set::target('#sqlModal')
                        ),
                        modal
                        (
                            setID('sqlModal'),
                            set::title($lang->bi->previewSql),
                            div
                            (
                               html(str_replace(PHP_EOL, '<br/ >', empty($pivotState->sql) ? $lang->bi->noSql : $pivotState->sql))
                            )
                        ),
                        span(setClass('divider', array('hidden' => $isTextMode))),
                        btn
                        (
                            setID('changeMode'),
                            setClass('ghost', array('hidden' => !$canChangeMode)),
                            set('data-mode', $mode),
                            set::icon('exchange'),
                            $isTextMode ? $lang->bi->toggleSqlBuilder : $lang->bi->toggleSqlText,
                            on::click()->do('changeMode(event)')
                        ),
                        btn
                        (
                            setID('changeModeDisabled'),
                            setClass('ghost', array('hidden' => $canChangeMode)),
                            set('data-mode', $mode),
                            set::hint($lang->bi->modeDisableTip),
                            set::icon('exchange'),
                            set::disabled(),
                            $isTextMode ? $lang->bi->toggleSqlBuilder : $lang->bi->toggleSqlText
                        )
                    )
                ),
                to::formActions
                (
                    modalTrigger
                    (
                        setClass(array('hidden' => !$isQueryStep)),
                        btn
                        (
                            setClass(array('hidden' => !$isTextMode)),
                            set::type('ghost'),
                            set::icon('cog-outline'),
                            set::disabled(!$canUseQuery),
                            $lang->dataview->add . $lang->dataview->queryFilters,
                            !$canUseQuery ? set::hint($this->lang->pivot->cannotAddQuery) : null
                        ),
                        $canUseQuery ? set::target('#queryFilterModal') : null
                    ),
                    btn
                    (
                        setClass('ml-auto next-step query-next'),
                        $lang->pivot->nextStep
                    ),
                    btn
                    (
                        setClass('ml-auto query-next-disabled hidden'),
                        set::disabled(),
                        set::hint($lang->pivot->cannotNextStep),
                        $lang->pivot->nextStep
                    )
                ),
                to::builder
                (
                    !$isTextMode ? sqlBuilder
                    (
                        set::data($pivotState->sqlbuilder),
                        set::onUpdate('updateDesignPage'),
                        set::tableList($tableList)
                    ) : null
                ),
                to::formFooter
                (
                    div
                    (
                        setID('queryFilterContent'),
                        setClass('flex justify-start bg-canvas stepquery-query-filters', array('hidden' => empty($filters))),
                        $filters
                    )
                )
            ),
            $canUseQuery ? queryFilterModal
            (
                set::data($modalFilters),
                set::addData($addQueryFilter),
                set::onAdd('addQueryFilter()'),
                set::onRemove('deleteQueryFilter(event)'),
                set::onChange('handleQueryFilterChange(event)'),
                set::onSave('saveQueryFilter()')
            ) : null
        )
    );
};
