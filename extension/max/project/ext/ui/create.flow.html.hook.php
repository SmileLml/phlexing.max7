<?php
namespace zin;

$workflowGroups     = data('workflowGroups');
$copyWorkflowGroup  = data('copyWorkflowGroup');
$workflowGroupPairs = data('workflowGroupPairs');
$copyProject        = data('copyProject');
$copyType           = data('copyType');
$pageType           = data('pageType');
$workflowGroup      = isset($copyProject->workflowGroup) && isset($workflowGroupPairs[$copyProject->workflowGroup]) ? $copyProject->workflowGroup : '';
$loadUrl            = helper::createLink('project', 'create', 'model=' . data('model') . '&program={parent}&copyProjectID=' . data('copyProjectID') . '&extra=charter={charter},hasProduct={hasProduct}' . (!empty($copyType) ? ",copyType={$copyType}" : '') . '&pageType=' . $pageType);
$hasProductValue    = data('hasProduct') === null ? (data('copyProjectID') ? data('copyProject.hasProduct') : '1') : data('hasProduct');
query('formGridPanel')->each(function($node) use($workflowGroups, $workflowGroup, $loadUrl, $hasProductValue, $copyWorkflowGroup)
{
    $lang   = data('lang');
    $model  = data('model');
    $fields = $node->prop('fields');

    if($model != 'kanban')
    {
        $fields->field('charter')
            ->class('charterBox')
            ->control(array('control' => 'picker', 'data-on' => 'change', 'data-call' => 'changeCharter'))
            ->items(data('charters'))
            ->value(data('charter'))
            ->hidden(data('config.systemMode') == 'light')
            ->moveAfter('parent');

        $fields->field('linkType')->value(data('linkType'))->hidden(true);

        $fields->field('productsBox')
            ->width('full')
            ->required(data('copyProject.parent') || data('parentProgram.id') || data('project.parent'))
            ->control(array
            (
                'control'           => 'productsBox',
                'productItems'      => data('charter') ? data('charterProductPairs') : data('allProducts'),
                'branchGroups'      => data('charter') ? data('branchPairs') : data('branchGroups'),
                'planGroups'        => data('charter') ? data('charterPlans') : data('productPlans'),
                'roadmapGroups'     => data('productRoadmaps'),
                'productPlans'      => data('productPlans'),
                'linkedProducts'    => data('charter') ? data('charterProducts') : data('linkedProducts'),
                'linkedBranches'    => data('linkedBranches'),
                'project'           => data('project') ? data('project') : data('copyProject'),
                'hasNewProduct'     => data('app.rawMethod') == 'create',
                'isStage'           => data('isStage'),
                'type'              => data('linkType'),
                'errorSameProducts' => $lang->project->errorSameProducts,
                'selectTip'         => $lang->project->selectProductTip,
                'hidden'            => !$hasProductValue && !data('charter')
            ));

        $fields->autoLoad('parent', 'charter,acl,productsBox,hasProduct,linkType')
            ->autoLoad('charter', 'productsBox,linkType');

        if(!empty($copyWorkflowGroup) && $copyWorkflowGroup->objectID)
        {
            $fields->field('workflowGroup')
                ->label($lang->project->workflowGroup)
                ->control('picker')
                ->required(true)
                ->value($copyWorkflowGroup->id)
                ->items(array($copyWorkflowGroup->id => $copyWorkflowGroup->name));
        }
        else
        {
            $fields->field('workflowGroup')
                ->label($lang->project->workflowGroup)
                ->control('picker')
                ->required(true)
                ->value($workflowGroup)
                ->items($workflowGroups);
        }

        $fields->field('workflowGroup')->width('1/2');
        $fields->field('hasProduct')->width('1/2');

        $fields->moveAfter('workflowGroup', 'hasProduct');
    }

    $fields->fullModeOrders('parent,charter,category,hasProduct,workflowGroup');
    $fields->orders('parent,charter,category,hasProduct,workflowGroup');

    $node->setProp('fields', $fields);
    $node->setProp('loadUrl', $loadUrl);
});
