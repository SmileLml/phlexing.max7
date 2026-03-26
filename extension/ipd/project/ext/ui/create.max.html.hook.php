<?php
namespace zin;
global $lang, $config, $app;

$copyType          = data('copyType');
$programListSet    = data('programListSet');
$model             = data('model');
$copyProject       = data('copyProject');
$deliverables      = data('deliverables');
$copyWorkflowGroup = data('copyWorkflowGroup');
$hasCode           = !empty($config->setCode);

jsVar('rawMethod',    $app->rawMethod);
jsVar('programID',    data('programID'));
jsVar('programID',    data('programID'));
jsVar('pageType',     data('pageType'));
jsVar('copyType',     $copyType);
jsVar('nextStepText', $lang->project->nextStep);
jsVar('LONG_TIME',    LONG_TIME);
jsVar('end',          isset($copyProject->end) ? $copyProject->end : '');
jsVar('budget',       isset($copyProject->budget) ? $copyProject->budget : '');
jsVar('days',         isset($copyProject->days) ? $copyProject->days : '');
jsVar('future',       isset($copyProject->future) ? $copyProject->future : 'off');
jsVar('multiple',     isset($copyProject->multiple) ? $copyProject->multiple : 1);

$allDataItems = array();
$allList      = $model == 'ipd' ? $lang->project->copyProject->ipdAllList : $lang->project->copyProject->allList;
foreach($allList as $name)
{
    $executionTitle = $model == 'scrum' || $model == 'agileplus' ? sprintf($name, $lang->project->copyProject->sprint) : sprintf($name, $lang->project->stage);
    $allDataItems[] = div
        (
            setClass('all-item clip'),
            set::title($executionTitle),
            icon('success', setClass('mr-2')),
            $executionTitle
        );
}

$noSprintItems = array();
$noSprintList  = $lang->project->copyProject->noSprintList;
foreach($noSprintList as $name)
{
    $noSprintItems[] = div
        (
            setClass('all-item clip'),
            set::title($name),
            icon('success', setClass('mr-2')),
            $name
        );
}

query('#copyProjectModal')->replaceWith
(
    modal
    (
        set::id('copyProjectModal'),
        div
        (
            setClass('copy-container'),
            ul
            (
                setClass('nav nav-tabs'),
                li
                (
                    setClass('active'),
                    a(setClass('pointer-events-none'), setID('firstTab'), setData('target', 'tabContent1'), setData('toggle', 'tab'), icon(setClass('number'), 1), span($lang->project->copyProject->select)),
                    div(setClass('line'))
                ),
                li
                (
                    a(setClass('pointer-events-none'), setData('toggle', 'tab'), icon(setClass('number'), 2), span($lang->project->copyProject->confirmData)),
                    div(setClass('line'))
                ),
                li
                (
                    a(setClass('pointer-events-none'), setData('toggle', 'tab'), icon(setClass('number'), 3), span($lang->project->copyProject->improveData)),
                    div(setClass('line'))
                ),
                li
                (
                    a(setClass('pointer-events-none'), setData('toggle', 'tab'), icon(setClass('number'), 4), span($lang->project->copyProject->completeData)),
                    div(setClass('line'))
                )
            ),
            div
            (
                setClass('tab-content'),
                div
                (
                    setID('tabContent1'),
                    setClass('tab-pane fade active in'),
                    div
                    (
                        setClass('content'),
                        div
                        (
                            setClass('copy-title'),
                            $lang->project->copyProject->selectPlz
                        ),
                        div
                        (
                            setClass('select-box'),
                            $config->systemMode == 'ALM' ? picker
                            (
                                setClass('mr-4'),
                                set::width('150'),
                                set::name('parent'),
                                set::items($programListSet),
                                set::placeholder($lang->project->copyProject->selectProgram)
                            ) : null,
                            picker
                            (
                                set::width('300'),
                                set::name('selectCopyprojectID'),
                                set::items(array()),
                                set::placeholder($lang->project->copyProject->selectProjectPlz)
                            )
                        ),
                        div(setID('replaceList'), setClass('flex items-center flex-wrap gap-4 mb-4'))
                    ),
                    div
                    (
                        setClass('footer-btns'),
                        button
                        (
                            setClass('btn primary next-btn'),
                            setData('target', '#tabContent2'),
                            setData('toggle', 'tab'),
                            $lang->project->nextStep
                        ),
                        button
                        (
                            setClass('btn cancel-btn'),
                            setData('dismiss', 'modal'),
                            $lang->project->copyProject->cancel
                        )
                    )
                ),
                div
                (
                    setID('tabContent2'),
                    setClass('tab-pane fade'),
                    div
                    (
                        setClass('content'),
                        div(setClass('copy-title'), $lang->project->copyProject->confirmCopyDataTip),
                        div(setClass('copy-project-title clip mb-4')),
                        div
                        (
                            setClass('all-content'),
                            radio
                            (
                                set::rootClass('allData'),
                                set::name('allCheckbox'),
                                $lang->project->copyProject->all
                            ),
                            div
                            (
                                setClass('all-data normal-data'),
                                $allDataItems
                            ),
                            div
                            (
                                setClass('all-data no-sprint-data hidden'),
                                $noSprintItems
                            )
                        ),
                        div
                        (
                            setClass('basic-content'),
                            radio
                            (
                                set::rootClass('basicData'),
                                set::name('basicCheckbox'),
                                $lang->project->copyProject->basic
                            ),
                            div
                            (
                                setClass('all-data'),
                                div
                                (
                                    setClass('all-item clip'),
                                    icon('success', setClass('mr-2')),
                                    $lang->project->copyProject->basicInfo
                                )
                            )
                        )
                    ),
                    div
                    (
                        setClass('footer-btns'),
                        button
                        (
                            setClass('btn primary complete-btn'),
                            $lang->project->copyProject->toComplete
                        ),
                        button
                        (
                            setClass('btn cancel-btn'),
                            setData('dismiss', 'modal'),
                            $lang->project->copyProject->cancel
                        ),
                        formHidden('choseCopyprojectID', '')
                    )
                )
            )
        )
    )
);

$loadUrl = helper::createLink('project', 'create', 'model=' . data('model') . '&program={parent}&copyProjectID=' . data('copyProjectID') . '&extra=charter={charter},hasProduct={hasProduct}' . (!empty($copyType) ? ",copyType={$copyType}" : '') . ",workflowGroup={workflowGroup}" . '&pageType=' . $pageType);
/* 追加交付物组件。 */
query('formGridPanel')->each(function($node) use($deliverables, $loadUrl, $lang, $model, $app, $hasCode, $copyWorkflowGroup)
{
    $fields = $node->prop('fields');
    $blocks = $node->prop('blocks');

    if(helper::hasFeature('deliverable') && $model != 'ipd' && $model != 'kanban')
    {
        $fields->field('deliverable')->label($lang->project->deliverableAbbr)->control(array('control' => 'deliverable', 'items' => !empty($deliverables) ? $deliverables : array()))->width('full')->hidden(true);
        $fields->autoLoad('workflowGroup', '[data-name=deliverable]');
    }

    if(data('copyFrom') == 'template')
    {
        $fields->field('workflowGroup')->readonly();
        unset($node->blocks['titleSuffix']);
        unset($node->blocks['headingActions'][0]);
        unset($node->blocks['headingActions'][1]);
    }

    if($app->rawMethod == 'createtemplate')
    {
        $copyProject = data('copyProject');
        $loadUrl = helper::createLink('project', 'createTemplate', 'model=' . data('model') . '&program={parent}&copyProjectID={copyProjectID}&extra=charter={charter},hasProduct={hasProduct},copyType=all,workflowGroup={workflowGroup}&pageType=copy');
        $fields->field('parent')->hidden();
        $fields->field('charter')->hidden();
        $fields->field('productsBox')->hidden();
        $fields->field('deliverable')->hidden();
        $fields->field('budget')->hidden();
        $fields->field('PM')->hidden();
        $fields->field('begin')->hidden();
        if(data('model') == 'waterfall' || data('model') == 'waterfallplus') $fields->field('stageBy')->hidden();
        $fields->field('end')->value(date('Y-m-d', strtotime('+30 days')))->hidden();
        $fields->field('days')->value('30')->hidden();
        if(!empty($copyProject->category)) $fields->field('category')->width('1/2')->value(!empty($copyProject) ? $copyProject->category :  '')->disabled();
        $fields->field('copyProjectID')->width('1/2')->control(array('control' => 'picker', 'required' => false))->required(true)->items(data('copyProjectList'))->value(data('copyProjectID'))->moveAfter('parent');
        $fields->field('model')->width('1/2')->control('picker')->items($lang->project->modelList)->value(!empty($copyProject) ? $copyProject->model : '')->disabled();
        $fields->field('workflowGroup')->width('1/2')->items(!empty($copyWorkflowGroup) ? array($copyWorkflowGroup->id => $copyWorkflowGroup->name) : array())->value(!empty($copyProject) ? $copyProject->workflowGroup :  '')->disabled();
        $fields->field('hasProduct')->width('1/2')->disabled();
        $fields->field('name')->label($lang->project->templateName)->width($hasCode ? '1/2' : 'full');
        $fields->field('isTpl')->value('1')->hidden();
        $fields->field('taskDateLimit')->value(!empty($copyProject) ? $copyProject->taskDateLimit : 'auto');
        $fields->autoLoad('copyProjectID');

        $fields->defaultOrders = array_merge($fields->defaultOrders, array('copyProjectID,model,category,hasProduct,workflowGroup,name,code'));
        $fields->ordersForFull = array_merge($fields->ordersForFull, array('copyProjectID,model,category,hasProduct,workflowGroup,name,code'));

        unset($node->blocks['titleSuffix']);
        unset($node->blocks['headingActions'][0]);
        unset($node->blocks['headingActions'][1]);

        $ajaxProp['submitDisabledValue'] = true;

        $node->setProp('ajax', $ajaxProp);
    }

    $node->setProp('fields', $fields);
    $node->setProp('loadUrl', $loadUrl);
});
