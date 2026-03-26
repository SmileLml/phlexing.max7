<?php
/**
 * The design view file of pivot module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenxuan Song <songchenxuan@easycorp.ltd>
 * @package     pivot
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('pivotID', $pivot->id);
jsVar('pivot', $pivot);
jsVar('designActions', $config->pivot->designActions);
jsVar('actionLoadTarget', $config->pivot->actionLoadTarget);
jsVar('confirmLeave', $lang->pivot->confirmLeave);
jsVar('prefix', $config->db->prefix);
jsVar('resetSettingsTip', $lang->pivot->resetSettings);
jsVar('draftSave', $lang->pivot->draftSave);
jsVar('confirmDraft', $lang->pivot->confirm->draft);
jsVar('confirmPublish', $lang->pivot->confirm->publish);
jsVar('objectFields', $objectFields);
jsVar('tableOptions', $tableOptions);
jsVar('stepOrder', $this->config->pivot->stepOrder);
jsVar('changeModeTip', $lang->bi->changeModeTip);
jsVar('cannotNextStep', $lang->pivot->cannotNextStep);
jsVar('clearLang', $lang->pivot->clear);
jsVar('keepLang', $lang->pivot->keep);

$step = $pivotState->step;

$this->app->loadLang('dataview');
$this->loadModel('bi');

$fnGenerateNav = function($currStep) use ($lang)
{
    $items = array();
    $selected = true;
    foreach($this->config->pivot->stepOrder as $step)
    {
        $title      = $lang->pivot->designStepNav[$step];
        $isCurrStep = $currStep == $step;
        if($isCurrStep) $selected = false;
        $items[] = array('text' => $title, 'active' => $isCurrStep, 'selected' => $selected, 'step' => $step);
    }

    return div
    (
        setID('stepNav'),
        setClass('self-center m-auto absolute'),
        nav
        (
            setClass('step-nav'),
            set::type('steps'),
            set::items($items),
            on::click()->do('changeStep(event)')
        )
    );
};

$fnGenerateCommonFilters = function() use ($pivotState)
{
    if(empty($pivotState->pivotFilters)) return null;

    list($queryFilters, $resultFilters) = $pivotState->pivotFilters;

    $filters = array();
    if(!empty($queryFilters))  foreach($queryFilters as $filter) $filters[] = filter(set($filter));
    if(!empty($resultFilters) && $pivotState->step !== 'query') foreach($resultFilters as $filter) $filters[] = resultFilter(set($filter));

    return $filters;
};

$biPath    = $this->app->getModuleExtPath('bi', 'ui');
$pivotPath = $this->app->getModuleExtPath('pivot', 'ui');
include $biPath['common']    . 'query.dictionary.html.php';
include $biPath['common']    . 'aclbox.html.php';
include $pivotPath['common'] . 'exportdata.html.php';

include "./block/query.step.html.php";
include "./block/design.step.html.php";
include "./block/drill.step.html.php";
include "./block/filter.step.html.php";
include "./block/publish.step.html.php";

featurebar
(
    setClass('relative'),
    div
    (
        setClass('row gap-2'),
        backBtn
        (
            setClass('px-1'),
            set::icon('back'),
            set::type('ghost'),
            set::back('pivot-browse'),
            $lang->pivot->cancelAndBack
        ),
        div(setClass('divider h-5 mt-1.5')),
        div
        (
            setClass('entity-label flex items-center gap-x-2 text-normal'),
            $lang->pivot->design
        )
    ),
    to::trailing
    (
        $fnGenerateNav($step)
    )
);

toolbar
(
    setID('saveAsDraft'),
    btn
    (
        set::type('ghost'),
        set::icon('save'),
        $lang->pivot->draft,
        on::click()->do('savePivot("draft")')
    )
);

div
(
    setID('pivotState'),
    setClass('hidden'),
    set('data-state', $pivotState),
    set('data-changed', array()),
    set('data-url', createLink('pivot', 'design', "pivotID={$pivot->id}")),
    set('data-actions', $config->pivot->designActions),
    h::js(<<<JS
$(function(){
$('html').css('overflow', 'auto');
});
JS
    )
);

$fnGenerateDictionary($pivotState->mode == 'text' && $step == 'query');

div(setID('stepContent'), $step == 'query' ? $fnGenerateStepQueryContent() : div(setID("step$step"), setClass('flex gap-4'), pivotTable
(
    setClass('h-full overflow-auto', array('hidden' => $step == 'query')),
    set::width('calc(100% - 450px)'),
    set::title($pivotState->name),
    set::cols($pivotState->pivotCols),
    set::data($pivotState->pivotData),
    set::cellSpan($pivotState->pivotCellSpan),
    set::filters($fnGenerateCommonFilters()),
    set::onCellClick(jsRaw('clickCell')),
    to::heading
    (
        !empty($pivotState->pivotData) ? array
        (
            toolbar
            (
                hasPriv('pivot', 'export') ? item(set(array
                (
                    'text'  => $this->lang->export,
                    'icon'  => 'export',
                    'class' => 'ghost',
                    'data-target' => '#export',
                    'data-toggle' => 'modal',
                    'data-size'   => 'sm'
                ))) : null
            ),
            div
            (
                setClass('hidden'),
                rawContent(),
                !empty($originData) ? $this->pivot->buildPivotTable($originData, $originConfigs) : null
            )
        ) : null
    )
), $step == 'design'  ? $fnGenerateStepDesignConfig($pivotState) : null, $step == 'drill'   ? $fnGenerateStepDrillConfig($pivotState) : null, $step == 'filter'  ? $fnGenerateStepFilterConfig($pivotState) : null, $step == 'publish' ? $fnGenerateStepPublishConfig($pivotState) : null), on::click('#export-data-button')->do('exportData()'), on::click('.next-step')->do('nextStep()'), on::click('.load-custom-pivot')->do('loadCustomPivot(event)'));
