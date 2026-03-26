<?php
/**
 * The buildzentaoconfig view file of document module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     doc 
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    setID('buildZentaoConfig'),
    set::action(inlink('buildZentaoConfig', "type={$type}&oldBlockID={$oldBlockID}")),
    set::ajax(array('beforeSubmit' => jsRaw('clickSubmit'))),
    set::title($lang->docTemplate->zentaoList[$type] . $lang->doc->list . ($isTemplate ? $lang->docTemplate->filter : $lang->docTemplate->param)),
    on::change('[name^=project]')->call("loadProduct", jsRaw("event")),
    to::titleSuffix
    (
        span
        (
            setClass('text-muted text-sm text-gray-600 font-light'),
            span(setClass('text-warning mr-1'), icon('help')),
            $isTemplate ? $lang->docTemplate->filterTip : $lang->docTemplate->paramTip
        )
    ),
    formGroup
    (
        set::label($lang->docTemplate->searchTab),
        set::name('searchTab'),
        set::required(true),
        set::hidden(!$isTemplate),
        set::items($tabs),
        set::value($searchTab)
    ),
    $type == 'productCase' || $type == 'projectCase' ? formGroup
    (
        set::label($lang->testcase->stage),
        set::name('caseStage'),
        set::value($caseStage),
        set::hidden(!$isTemplate),
        set::items($lang->testcase->stageList)
    ) : null,
    !$isTemplate && strpos(',HLDS,DDS,DBDS,ADS,projectCase,projectStory,gantt,', ",{$type},") !== false ? formGroup
    (
        set::label($lang->doc->selectProject),
        set::name('project'),
        set::required(true),
        set::value($project),
        set::items($projects),
        $type != 'gantt' ? set::multiple(true) : null
    ) : null,
    !$isTemplate && strpos(',productStory,bug,productCase,HLDS,DDS,DBDS,ADS,projectCase,projectStory,', ",{$type},") !== false ? formGroup
    (
        set::label($lang->doc->selectProduct),
        set::name('product'),
        set::required(strpos(',productStory,bug,productCase,', ",{$type},") !== false),
        set::multiple(true),
        set::value($product),
        set::items($products)
    ) : null,
    !$isTemplate && strpos(',executionStory,task,', ",{$type},") !== false ? formGroup
    (
        set::label($lang->doc->selectExecution),
        set::name('execution'),
        set::required(true),
        set::multiple(true),
        set::value($execution),
        set::items($executions)
    ) : null,
    formGroup
    (
        set::hidden(),
        set::name('templateID')
    )
);

render();
