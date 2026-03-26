<?php
/**
 * The adddrill view file of pivot module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenxuan Song <songchenxuan@easycorp.ltd>
 * @package     pivot
 * @link        https://www.zentao.net
 */
namespace zin;

$fnGenTabText = function($tabText)
{
    return div
    (
        setClass('border-b border-gray-100'),
        span($tabText, setClass('bg-gray-100 text-base font-medium leading-tight bg-text-padding'))
    );
};

$fnGenFieldAndObject = function($drill, $fields) use ($lang)
{
    return div(setClass('flex items-center gap-x-4'), div
    (
        $lang->pivot->drill->selectField
    ), div
    (
        setClass('field-select drill-select-picker'),
        picker
        (
            setID('picker_field'),
            set::name('field'),
            set::items($fields),
            set::value($drill['field']),
            on::change()->do('changeField(event)')
        ),
        div
        (
            setClass('form-tip hidden text-danger'),
            $lang->pivot->stepDrill->fieldEmpty
        )
    ), div
    (
        $lang->pivot->drill->selectObject
    ), div
    (
        setClass('object-select drill-select-picker'),
        picker
        (
            setID('picker_object'),
            set::name('object'),
            set::items($this->bi->getTableList($hasDatavew = false, $withPrefix = false)),
            set::value($drill['object']),
            on::change()->do('changeObject(event)')
        ),
        div
        (
            setClass('form-tip hidden text-danger'),
            $lang->pivot->stepDrill->objectEmpty
        )
    ));
};

$fnGenConditionLine = function($index, $conditionCount, $drillFields, $queryFields, $condition) use ($lang)
{
    $drillFieldValue = null;
    if($condition['drillAlias'])
    {
        $parts = array($condition['drillAlias'], $condition['drillObject'], $condition['drillField']);
        $parts = array_filter($parts);
        $drillFieldValue = implode('.', $parts);
    }
    return div
    (
        setClass("flex condition-line items-center gap-x-2"),
        set('data-index', $index),
        div
        (
            $lang->pivot->drill->inDrillField
        ),
        div
        (
            setClass("drillField-select-{$index} drill-condition-picker"),
            inputGroup
            (
                setID('drillFieldGroup'),
                picker
                (
                    setID("picker_drillField$index"),
                    set::name('drillField[]'),
                    set::items($drillFields),
                    set::value($drillFieldValue),
                    on::change()->do('changeDrillField(event)')
                ),
                btn
                (
                    setClass('refresh-conditions'),
                    set::icon('refresh'),
                    on::click()->do('refreshConditions(event)')
                )
            ),
            div
            (
                setClass('form-tip hidden text-danger'),
                $lang->pivot->stepDrill->drillEmpty
            )
        ),
        div
        (
            $lang->pivot->drill->equal
        ),
        div
        (
            $lang->pivot->drill->inQueryField
        ),
        div
        (
            setClass("queryField-select-{$index} drill-condition-picker"),
            picker
            (
                setID("picker_queryField$index"),
                set::name('queryField[]'),
                set::items($queryFields),
                set::value($condition['queryField']),
                on::change()->do('changeQueryField(event)')
            ),
            div
            (
                setClass('form-tip hidden text-danger'),
                $lang->pivot->stepDrill->queryEmpty
            )
        ),
        btnGroup
        (
            btn
            (
                setClass('btn btn-link text-gray condition-add'),
                set::icon('plus'),
                on::click()->do('addCondition(event)')
            ),
            $conditionCount > 1 ? btn
            (
                setClass('btn btn-link text-gray condition-delete'),
                set::icon('close'),
                on::click()->do('deleteCondition(event)')
            ) : null
        )
    );
};

$fnGenConditionLines = function($pivotState, $drill) use ($lang, $fnGenConditionLine)
{
    $drillFields = $drill['object'] ? $this->pivot->getDrillFieldList($drill) : array();
    $queryFields = $pivotState->getFieldOptions();
    $conditions  = array();
    $index       = 1;
    if(!empty($drill['condition']))
    {
        $conditionCount = count($drill['condition']);
        foreach($drill['condition'] as $condition)
        {
            $conditions[] = $fnGenConditionLine($index, $conditionCount, $drillFields, $queryFields, $condition);
            $index ++;
        }
    }

    return div
    (
        setClass('condition-lines flex col gap-y-2'),
        $conditions
    );
};

$errorMessage = isset($errorMessage) ? $errorMessage : null;
$previewCols  = isset($previewCols)  ? $previewCols  : array();
$previewData  = isset($previewData)  ? $previewData  : array();
$fnGenerateQueryCondition = function($pivotState, $isDefault, $modalIndex) use ($lang, $fnGenTabText, $fnGenFieldAndObject, $fnGenConditionLines, $previewCols, $previewData, $errorMessage, $users)
{
    $drill  = $isDefault ? $pivotState->defaultDrill : $pivotState->drills[$modalIndex];
    $fields = $this->pivot->getFieldList($pivotState, $isDefault ? null : $modalIndex);
    return div
    (
        setID("queryConditionContent$modalIndex"),
        setClass('flex col gap-y-4 queryConditionContent'),
        $fnGenFieldAndObject($drill, $fields),
        div
        (
            div
            (
                setClass('drill-condition-tab border-b border-gray-100 flex items-center gap-x-2'),
                $fnGenTabText($lang->pivot->drill->setCondition),
                sqlBuilderHelpIcon(),
                span(setClass('text-gray-500'), $lang->pivot->queryConditionTip)
            ),
            div
            (
                setClass('flex'),
                div
                (
                    setClass('flex col gap-y-2 border drill-condition py-4'),
                    div
                    (
                        setClass('refer-sql px-4'),
                        !empty($drill['object']) ? $this->pivot->autoGenReferSQL($drill['object']) : null
                    ),
                    div
                    (
                        formGroup
                        (
                            setClass('where-sql px-4'),
                            setID("textarea_whereSql"),
                            set::name('whereSql'),
                            !empty($drill['whereSql']) ? set::value($drill['whereSql']) : null,
                            set::control(array('type' => 'textarea', 'rows' => 3)),
                            set::placeholder($lang->pivot->drillSQLTip),
                            on::change()->do('changeWhereSQL(event)')
                        )
                    ),
                    div
                    (
                        formGroup
                        (
                            setClass('error-message'),
                            set::tipClass('text-danger'),
                            set::tip($errorMessage)
                        )
                    ),
                    div
                    (
                        setClass('flex items-center'),
                        div(setClass('flex items-center basis-32'), div
                        (
                            setClass('text-base font-medium align-self py-1 px-3'),
                            $lang->pivot->drill->drillCondition
                        ), sqlBuilderHelpIcon(set::text($lang->pivot->drillConditionTip))),
                        $fnGenConditionLines($pivotState, $drill)
                    )
                ),
                div
                (
                    setClass('border p-3 break-all origin-sql'),
                    $pivotState->sql
                )
            )
        ),
        div
        (
            div
            (
                setClass('drill-condition-tab border-b border-gray-100 flex items-center gap-x-2'),
                $fnGenTabText($lang->pivot->drill->drillResult),
                sqlBuilderHelpIcon(),
                span(setClass('text-gray-500'), $lang->pivot->previewResultTip)
            ),
            div
            (
                dtable
                (
                    set::height(160),
                    set::id('drillResult'),
                    set::cols((array)$previewCols),
                    set::data((array)$previewData),
                    set::userMap($users),
                    set::onRenderCell(jsRaw('window.renderDrillResult')),
                    set::emptyTip($lang->pivot->drillResultEmptyTip)
                )
            )
        )
    );
};

$fnGenerateAddDrillModal = function($pivotState, $modalID) use ($lang, $fnGenerateQueryCondition)
{
    $isDefault  = $modalID == 'drillModalDefault';
    $modalIndex = $isDefault ? substr($modalID, 10) : (int)substr($modalID, 10);
    return modal
    (
        setID($modalID),
        setClass('drill-modal'),
        setData('backdrop', 'static'),
        set::size('lg'),
        set::title($lang->pivot->drill->common),
        set::titleClass('flex-none'),
        to::header
        (
            sqlBuilderHelpIcon(set::text($lang->pivot->drillModalTip))
        ),
        $fnGenerateQueryCondition($pivotState, $isDefault, $modalIndex),
        set::footerClass('form-actions gap-4 mt-4'),
        to::footer
        (
            btn
            (
                set::type('ghost'),
                setID('previewDrill'),
                setClass('px-6 drill-preview squre'),
                $lang->pivot->drill->preview,
                on::click()->do('previewDrillResult(event)')
            ),
            btn
            (
                set::type('ghost'),
                setClass('px-6 drill-save squre'),
                $lang->pivot->drill->save,
                on::click()->do('saveDrill(event)')
            )
        )
    );
};
