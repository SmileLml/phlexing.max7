<?php
/**
 * The operate view file of flow module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     flow
 * @link        https://www.zentao.net
 */
namespace zin;
include 'header.html.php';

if(in_array($flow->module, array('story', 'epic', 'requirement'))) data('activeMenuID', $data->type); // 工作流扩展需求的动作高亮正确的菜单

$fieldList = defineFieldList("{$flow->module}.operate");

if(!empty($config->openedApproval) && $flow->approval == 'enabled' && $action->action == 'approvalsubmit')
{
    $loadReviewers = function()
    {
        $cols = array();
        $cols['node']     = array('name' => 'approval_node',     'type' => 'text',    'title' => $this->lang->approval->node, 'width' => '200', 'fixed' => 'left');
        $cols['reviewer'] = array('name' => 'approval_reviewer', 'type' => 'control', 'title' => $this->lang->approval->reviewer, 'control' => array('type' => 'picker', 'props' => "RAWJS<window.getReviewerCellProps>RAWJS"));
        $cols['ccer']     = array('name' => 'approval_ccer',     'type' => 'control', 'title' => $this->lang->approval->ccer,     'control' => array('type' => 'picker', 'props' => "RAWJS<window.getCcerCellProps>RAWJS"));

        return dtable
        (
            set::bordered(true),
            set::rowHeight(45),
            set::cols(array_values($cols)),
            set::data(data('approvalReviewDatas')),
            set::onRenderCell(jsRaw('window.renderReviewerItem')),
            set::plugins(array('form'))
        );
    };

    $fieldList->field('reviewerBox')->className('reviewerBox')->width('full')->label($this->lang->approval->reviewer)->control($loadReviewers)->readonly(false)->layoutRules('');
}
if(!empty($config->openedApproval) && $flow->approval == 'enabled' && $action->action == 'approvalreview') $data->reviewOpinion = '';

$fieldList = $this->flow->buildFormFields($fieldList, $fields, $childFields, $data, $childDatas);

formGridPanel
(
    set::actionsClass('review-actions'),
    set::title($title),
    set::ajax(array('submitDisabledValue' => false)),
    set::url($actionURL),
    set::defaultMode('full'),
    set::modeSwitcher(false),
    set::fields($fieldList)
);

html($formulaScript);
html($linkageScript);

if(!empty($config->openedApproval) && $flow->approval == 'enabled' && $action->action == 'approvalreview')
{
    if(isset($currentNode->priv))
    {
        $actionItems = array();
        if(in_array('revert', $currentNode->priv))  $actionItems[] = btn(set(array('text' => $lang->approval->revert,  'url' => createLink('approval', 'revert', "objectType={$flow->module}&objectID=$dataID"),  'innerClass' => 'revert-btn',  'data-toggle' => "modal", 'data-hide-others' => "true")));
        if(in_array('forward', $currentNode->priv)) $actionItems[] = btn(set(array('text' => $lang->approval->forward, 'url' => createLink('approval', 'forward', "objectType={$flow->module}&objectID=$dataID"), 'innerClass' => 'forward-btn', 'data-toggle' => "modal", 'data-hide-others' => "true")));
        if(in_array('addnode', $currentNode->priv)) $actionItems[] = btn(set(array('text' => $lang->approval->addNode, 'url' => createLink('approval', 'addNode', "objectType={$flow->module}&objectID=$dataID"), 'innerClass' => 'forward-btn', 'data-toggle' => 'modal', 'data-hide-others' => 'true')));

        if($actionItems) query('form .review-actions')->append($actionItems);
    }
}
