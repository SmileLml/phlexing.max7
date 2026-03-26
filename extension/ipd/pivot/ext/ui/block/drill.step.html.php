<?php
/**
 * The step drill view file of pivot design module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenxuan Song <songchenxuan@easycorp.ltd>
 * @package     pivot
 * @link        https://www.zentao.net
 */
namespace zin;

$defaultDrillModalID = 'drillModalDefault';
include_once 'adddrill.html.php';

$fnGenerateDrillingModal = function($pivotState) use ($lang)
{
    $drillingModals = array();
    foreach($pivotState->pivotCols as $pivotCol)
    {
        $isDrilling = isset($pivotCol['isDrilling']) ? $pivotCol['isDrilling'] : false;
        if(!$isDrilling) continue;

        $drillingModals[] = modal
        (
            setID('drilling-' . $pivotCol['name']),
            set::title($lang->pivot->stepDrill->drillView),
            set::size('lg'),
            dtable
            (
                set::striped(true),
                set::bordered(true),
                set::cols($pivotCol['drillingCols']),
                set::data($pivotCol['drillingDatas'])
            )
        );
    }

    return $drillingModals;
};

$fnGenerateEmptyTip = function() use ($lang, $defaultDrillModalID)
{
    return div
    (
        setClass('drill-empty-tip'),
        div
        (
            setClass('flex items-center'),
            div(setClass('text-gray-500'), $lang->pivot->noDrillTip),
            div
            (
                toolbar
                (
                    setClass('pr-1 add-button'),
                    modalTrigger
                    (
                        btn(setClass('ghost squre drill-add'), set::icon('plus'), $lang->pivot->stepDrill->addDrill),
                        set::target("#$defaultDrillModalID")
                    )
                )
            )
        )
    );
};

$fnGenerateAddDrillButton = function() use ($lang, $defaultDrillModalID)
{
    return div
    (
        setClass('flex'),
        toolbar
        (
            setClass('pr-1 add-button'),
            modalTrigger
            (
                btn(setClass('ghost squre drill-add'), set::icon('plus'), $lang->pivot->stepDrill->addDrill),
                set::target("#$defaultDrillModalID")
            )
        )
    );
};

$fnGenerateDrillItem = function($drill, $modalID, $index) use ($lang)
{
    return div
    (
        setClass('flex drill-line items-center'),
        set('data-index', $index),
        div
        (
            setClass('flex p-2 drill-container'),
            $drill['warning'] ? button
            (
                setClass('btn capitalize danger-outline size-sm'),
                $lang->pivot->drill->designChangedTip
            ) : div
            (
                setClass('text-md pl-2'),
                $drill['text'],
                label
                (
                    setClass('label size-sm capitalize secondary-outline ml-2', array('hidden' => empty($drill['type']) || $drill['type'] != 'auto')),
                    $lang->pivot->drill->auto
                )
            )
        ),
        toolbar
        (
            setClass('pl-2 gap-1'),
            modalTrigger
            (
                btn
                (
                    setClass('drill-edit ghost squre'),
                    set::icon('backend')
                ),
                set::target("#$modalID")
            ),
            btn
            (
                setClass('ghost size-sm squre drill-delete'),
                set::icon('close'),
                on::click()->do('deleteDrill(event)')
            )
        )
    );
};

$fnGenerateDrills = function($pivotState) use ($fnGenerateDrillItem, $fnGenerateAddDrillModal)
{
    $drills         = $pivotState->drills;
    $drillFields    = $this->pivot->getFieldList($pivotState);
    $fieldTextPairs = array();
    foreach($drillFields as $fieldInfo) $fieldTextPairs[$fieldInfo['value']] = $fieldInfo['text'];

    $items  = array();
    $modals = array();
    foreach($drills as $index => $drill)
    {
        if(empty($drill)) continue;
        $modalID          = "drillModal$index";
        $drill['text']    = isset($fieldTextPairs[$drill['field']]) ? $fieldTextPairs[$drill['field']] : $drill['field'];

        if(empty($drill['field'])) $drill['warning'] = true;
        $drill['warning'] = isset($drill['warning']) ? $drill['warning'] : false;

        $items[]  = $fnGenerateDrillItem($drill, $modalID, $index);
        $modals[] = $fnGenerateAddDrillModal($pivotState, $modalID);
    }

    return div
    (
        setClass('py-3 flex col gap-y-4'),
        $items,
        $modals
    );
};

$fnGenerateStepDrillConfig = function($pivotState) use ($lang, $defaultDrillModalID, $fnGenerateAddDrillModal, $fnGenerateDrillingModal, $fnGenerateEmptyTip, $fnGenerateAddDrillButton, $fnGenerateDrills)
{
    return pivotConfig
    (
        set::title($lang->pivot->stepDrill->drill),
        set::titleTip
        (
            sqlBuilderHelpIcon
            (
                set::text($lang->pivot->drillingTip),
                set::placement('left')
            )
        ),
        set::saveText($lang->pivot->saveSetting),
        set::onSave('saveSettings(event)'),
        set::onNext('nextStep()'),
        to::heading($fnGenerateAddDrillButton()),
        div
        (
            setID('drillForm'),
            empty($pivotState->drills) ? $fnGenerateEmptyTip() : $fnGenerateDrills($pivotState)
        ),
        $fnGenerateAddDrillModal($pivotState, $defaultDrillModalID),
        $fnGenerateDrillingModal($pivotState)
    );
};
