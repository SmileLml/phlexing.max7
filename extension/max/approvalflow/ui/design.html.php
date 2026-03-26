<?php
/**
 * The design file of approval module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     approval
 * @link        https://www.zentao.net
 */
namespace zin;

$reviewTypeLang    = $lang->approvalflow->reviewTypeList;
$noticeTypeLang    = $lang->approvalflow->noticeTypeList;
$productRoles      = $lang->approvalflow->productRoleList;
$projectRoles      = $lang->approvalflow->projectRoleList;
$executionRoles    = $lang->approvalflow->executionRoleList;
$superiorList      = $lang->approvalflow->superiorList;
$userRange         = $lang->approvalflow->userRangeList;
if($node)
{
    $node = json_decode($node);
    if(!empty($node->type) && $node->type != 'conditions')
    {
        $reviewerTypes = array();
        $ccTypes       = array();
        $reviewerBox   = array();
        $ccerBox       = array();
        foreach($lang->approvalflow->reviewerTypeList as $type => $info)
        {
            if((!empty($node->nodeIndex) && $node->nodeIndex > 1) || $type != 'setByPrev') $reviewerTypes[$type] = $info['name'];
            if($type != 'self' && $type != 'setByPrev' && $type != 'productRole' && $type != 'projectRole' && $type != 'executionRole') $ccTypes[$type] = $info['name'];

            if(empty($info['options'])) continue;

            $multiple      = $type != 'select' && $type != 'superiorList' ? true : false;
            $reviewerBox[] = picker(set::name($info['options']), set::required(true), setClass($type == 'select' ? '' : 'hidden'), set::multiple($multiple), set::items(${$info['options']}), setData(array('on' => 'change', 'call' => 'changeOption', 'params' => 'event', 'type' => $type)));
            $ccerBox[]     = picker(set::name("cc{$info['options']}"), set::required(true), setClass($type == 'select' ? '' : 'hidden'), set::multiple($multiple), set::items(${$info['options']}), setData(array('on' => 'change', 'call' => 'changeOption', 'params' => 'event', 'type' => $type)));
        }
        foreach($lang->approvalflow->userRangeList as $range => $label)
        {
            if($range == 'all') continue;

            $rangeType     = "{$range}s";
            $reviewerBox[] = picker(set::name("range{$range}"),   setClass('hidden'), set::multiple(true), set::items(${$rangeType}), setData(array('type' => "range$range")));
            $ccerBox[]     = picker(set::name("ccrange{$range}"), setClass('hidden'), set::multiple(true), set::items(${$rangeType}), setData(array('type' => "ccrange$range")));
        }

        $reviewerBox[] = picker(set::name('required'), set::required(true), set::items($lang->approvalflow->required), setData(array('type' => 'required')));

        if(!empty($node->reviewers))
        {
            foreach($node->reviewers as $index => $reviewer)
            {
                if(!empty($reviewer->type) && $reviewer->type == 'select')
                {
                    $dd = new stdclass();
                    foreach($reviewer as $key => $value)
                    {
                        $field = $key;
                        if($key != 'type' && $key != 'userRange' && $key != 'required') $field = "range" . substr($key, 0, -1);

                        $dd->{$field} = $value;
                    }
                    $node->reviewers[$index] = $dd;
                }
            }
        }

        if(!empty($node->ccs))
        {
            foreach($node->ccs as $index => $cc)
            {
                $before = 'cc';
                if(!empty($cc->type) && $cc->type == 'select') $before = 'ccrange';

                $dd = new stdclass();
                foreach($cc as $key => $value)
                {
                    if($key == 'type')      $field = 'ccType';
                    if($key == 'userRange') $field = 'ccuserRange';
                    if($key != 'type' && $key != 'userRange')
                    {
                        $field = $before . $key;
                        if($cc->type == 'select') $field = substr($field, 0, -1);
                    }

                    $dd->{$field} = $value;
                }
                $node->ccs[$index] = $dd;
            }
        }
    }
}

$renderConditionText = function($conditions) use($users, $depts, $roles, $positions, $fields)
{
    global $lang;

    $conditionTextLang = (array)$lang->approvalflow->conditionTypeList;
    $operatorList      = (array)$lang->workflowcondition->operatorList;
    $conditionText      = '';
    $conditionTitleText = '';
    $conditionNum       = 0;
    foreach($conditions as $condition)
    {
        $conditionNum ++;
        if(!$condition->conditionValue) continue;

        if($condition->conditionField == 'submitUsers')
        {
            $userConditionText = '';
            $userName = zget($users, $condition->conditionValue, '');
            if($userName) $userConditionText .= $userName . ' ';

            if($conditionNum <= 2) $conditionText .= $conditionTextLang['submitUsers'] . $operatorList[$condition->conditionOperator] . trim($userConditionText, ' ') . '<br>';
            $conditionTitleText .= $conditionTextLang['submitUsers'] . $operatorList[$condition->conditionOperator] . trim($userConditionText, ' ');
        }
        elseif($condition->conditionField == 'submitDepts')
        {
            $deptConditionText = '';
            $deptName = zget($depts, $condition->conditionValue, '');
            if($deptName) $deptConditionText .= $deptName . '、';

            if($conditionNum <= 2) $conditionText .= $conditionTextLang['submitDepts'] . $operatorList[$condition->conditionOperator] . trim($deptConditionText, ' ') . '<br>';
            $conditionTitleText .= $conditionTextLang['submitDepts'] . $operatorList[$condition->conditionOperator] . trim($deptConditionText, ' ');
        }
        elseif($condition->conditionField == 'submitRoles')
        {
            $roleConditionText = '';
            $roleName = zget($roles, $condition->conditionValue, '');
            if($roleName) $roleConditionText .= $roleName . '、';;

            if($conditionNum <= 2) $conditionText .= $conditionTextLang['submitRoles'] . $operatorList[$condition->conditionOperator] . trim($roleConditionText, ' ') . '<br>';
            $conditionTitleText .= $conditionTextLang['submitRoles'] . $operatorList[$condition->conditionOperator] . trim($roleConditionText, ' ');
        }
        elseif($condition->conditionField == 'submitPositions')
        {
            $positionConditionText = '';
            $positionName = zget($positions, $condition->conditionValue, '');
            $positionConditionText .= $positionName . '、';

            if($conditionNum <= 2) $conditionText .= $conditionTextLang['submitPositions'] . $operatorList[$condition->conditionOperator] . trim($positionConditionText, ' ') . '<br>';
            $conditionTitleText .= $conditionTextLang['submitPositions'] . $operatorList[$condition->conditionOperator] . trim($positionConditionText, ' ');
        }
        elseif(isset($fields[$condition->conditionField]))
        {
            $field = $fields[$condition->conditionField];

            $text  = '';
            $value = $field->options ? zget($field->options, $condition->conditionValue, '') : $condition->conditionValue;
            if($value) $text .= $value . '、';

            if($conditionNum <= 2) $conditionText .= $field->name . $operatorList[$condition->conditionOperator] . trim($text, ' ') . '<br>';
            $conditionTitleText .= $field->name . $operatorList[$condition->conditionOperator] . trim($text, ' ');
        }

        if($conditionNum == 3) $conditionText .= '...';

        $conditionTitleText .= '&#10;';
    }

    return array('text' => $conditionText, 'titleText' => trim($conditionTitleText, '&#10;'));
};

$renderNodes = function($nodes) use(&$renderNodes, &$renderConditionText)
{
    global $lang;

    $editorNodes      = array();
    $noticeTypeLang   = $lang->approvalflow->noticeTypeList;
    $reviewerTypeLang = $lang->approvalflow->reviewerTypeList;
    $reviewTypeLang   = $lang->approvalflow->reviewTypeList;
    $nodeTypeLang     = $lang->approvalflow->nodeTypeList;
    foreach($nodes as $node)
    {
        if($node->type == 'branch')
        {
            $nodeType     = $node->branchType == 'parallel'  ? 'default'     : 'other';
            $branchType   = $node->branchType == 'parallel'  ? 'addParallel' : 'addCondition';
            $noticeType   = $node->branchType == 'condition' ? 'otherBranch' : 'defaultBranch';
            $branchesHTML = array();
            foreach($node->branches as $index => $branches)
            {
                $brancheNodes   = $branches->nodes;
                $conditions     = $branches->conditions;
                $conditionLabel = $renderConditionText($conditions);
                $branchesHTML[] = div
                (
                    setData('id', $branches->id),
                    setClass('editor-node branch'),
                    div(setClass('top-line-mask')),
                    div(setClass('top-v-line')),
                    div
                    (
                        setClass('nodes'),
                        div
                        (
                            setClass('editor-node condition'),
                            div
                            (
                                setClass('editor-node-container'),
                                div
                                (
                                    setClass('node-title node-title-condition'),
                                    span(setClass('node-title-name'), $nodeTypeLang['condition'] . ' ' . ((int)$index + 1)),
                                    div
                                    (
                                        setClass('node-btns'),
                                        button
                                        (
                                            set::type('button'),
                                            setClass('delete-btn operate-icon'),
                                            setData(array('on' => 'click', 'call' => 'deleteBranch', 'params' => 'event')),
                                            span
                                            (
                                                setClass('universe-icon'),
                                                h::svg
                                                (
                                                    set::width('1em'),
                                                    set::height('1em'),
                                                    set::viewBox('0 0 24 24'),
                                                    set::fill('none'),
                                                    set::xmlns('http://www.w3.org/2000/svg'),
                                                    h::path(set::d('M12.0001 10.5858L19.7176 2.86825C19.9129 2.67299 20.2295 2.67299 20.4247 2.86825L21.1318 3.57536C21.3271 3.77062 21.3271 4.0872 21.1318 4.28246L13.4143 12L21.1318 19.7175C21.3271 19.9128 21.3271 20.2293 21.1318 20.4246L20.4247 21.1317C20.2295 21.327 19.9129 21.327 19.7176 21.1317L12.0001 13.4142L4.28258 21.1317C4.08732 21.327 3.77074 21.327 3.57548 21.1317L2.86837 20.4246C2.67311 20.2293 2.67311 19.9128 2.86837 19.7175L10.5859 12L2.86837 4.28246C2.67311 4.0872 2.67311 3.77062 2.86837 3.57536L3.57548 2.86825C3.77074 2.67299 4.08732 2.67299 4.28258 2.86825L12.0001 10.5858Z'), set::fill('currentColor'))
                                                )
                                            )
                                        )
                                    )
                                ),
                                div
                                (
                                    setClass('node-content node-content-placeholder'),
                                    setData(array('on' => 'click', 'call' => 'clickConditionNode', 'params' => 'event', 'title' => $conditionLabel['titleText'])),
                                    set::title($conditionLabel['titleText']),
                                    div
                                    (
                                        setClass('node-detail-item'),
                                        div(setClass('text-ellipsis'), html($conditionLabel['text']))
                                    )
                                ),
                                icon('arrow', setClass('iconfont-approval-admin'))
                            ),
                            div(setClass('bottom-v-line')),
                            div
                            (
                                setClass('add-node-btn'),
                                setData(array('on' => 'click', 'call' => 'addNodeType', 'params' => 'event')),
                                div
                                (
                                    setClass('add-btn'),
                                    icon('plus', setClass('add-btn-icon iconfont-approval-admin'))
                                )
                            )
                        ),
                        $brancheNodes ? $renderNodes($brancheNodes) : null
                    ),
                    div(setClass('bottom-v-line')),
                    div(setClass('bottom-line-mask'))
                );
            }

            $branchesHTML[] = div
            (
                setData('id', $node->default->id),
                setClass('editor-node branch'),
                div(setClass('top-line-mask')),
                div(setClass('top-v-line')),
                div
                (
                    setClass('nodes'),
                    div
                    (
                        setClass('editor-node condition'),
                        div
                        (
                            setClass('editor-node-container'),
                            setData('condition-id', 'CONDITION_648325_5857434'),
                            setData('data-node-id', 'l10f2gm2-004'),
                            div
                            (
                                setClass('node-title node-title-condition'),
                                span(setClass('node-title-name'), $nodeTypeLang[$nodeType])
                            ),
                            div
                            (
                                setClass('node-content'),
                                div
                                (
                                    setClass('node-detail-item'),
                                    div($noticeTypeLang[$noticeType])
                                )
                            ),
                            icon('arrow', setClass('iconfont-approval-admin'))
                        ),
                        div(setClass('bottom-v-line')),
                        div
                        (
                            setClass('add-node-btn'),
                            setData(array('on' => 'click', 'call' => 'addNodeType', 'params' => 'event')),
                            div
                            (
                                setClass('add-btn'),
                                icon('plus', setClass('add-btn-icon iconfont-approval-admin'))
                            )
                        )
                    ),
                    $renderNodes($node->default->nodes)
                ),
                div(setClass('bottom-v-line')),
                div(setClass('bottom-line-mask'))
            );

            $editorNodes[] = div
            (
                setData('id', $node->id),
                setClass('editor-node route'),
                div(setClass('top-h-line')),
                div
                (
                    setClass('add-condition'),
                    setData(array('on' => 'click', 'call' => 'addCondition', 'params' => 'event')),
                    div
                    (
                        setClass('add-condition-inner'),
                        $noticeTypeLang[$branchType],
                        icon('arrow', setClass('iconfont-approval-admin'))
                    )
                ),
                div
                (
                    setClass('branches'),
                    $branchesHTML
                ),
                div(setClass('bottom-h-line')),
                div(setClass('bottom-v-line')),
                div
                (
                    setClass('add-node-btn'),
                    setData(array('on' => 'click', 'call' => 'addNodeType', 'params' => 'event')),
                    div
                    (
                        setClass('add-btn'),
                        icon('plus', setClass('add-btn-icon iconfont-approval-admin'))
                    )
                )
            );
        }
        else
        {
            $id     = '';
            $title  = '';
            $notice = '';
            switch($node->type)
            {
                case 'start':
                case 'end':
                    $id     = $node->type;
                    $title  = $noticeTypeLang['setCC'];
                    $notice = $noticeTypeLang['setCC'];
                    if(!empty($node->ccs[0]->type))
                    {
                        foreach($node->ccs as $cc) $notice = $reviewerTypeLang[$cc->type]['name'] . ' ';
                    }
                    break;
                case 'approval':
                    $id    = $node->id;
                    $title = $noticeTypeLang['setReviewer'];
                    if(empty($node->reviewType) || $node->reviewType == 'manual')
                    {
                        foreach($node->reviewers as $reviewer) $notice = $reviewerTypeLang[$reviewer->type]['name'] . ' ';
                    }
                    else
                    {
                        $notice = $reviewTypeLang[$node->reviewType];
                    }
                    break;
                case 'cc':
                    $id    = $node->id;
                    $title = $noticeTypeLang['setCC'];
                    foreach($node->ccs as $cc) $notice = $reviewerTypeLang[$cc->type]['name'] . ' ';
                    break;
            }
            $editorNodes[] = div
            (
                setData('id', $id),
                setClass("editor-node {$node->type}"),
                div(setClass('editor-node-container'), div
                (
                    setClass('node-title'),
                    div
                    (
                        setClass('node-title-name text-ellipsis'),
                        set::title(!empty($node->title) ? $node->title : $nodeTypeLang[$node->type]),
                        !empty($node->title) ? $node->title : $nodeTypeLang[$node->type]
                    )
                ), div
                (
                    setClass('node-content'),
                    setData(array('on' => 'click', 'call' => 'clickNode', 'params' => 'event')),
                    set::title($title),
                    div
                    (
                        setClass('node-detail'),
                        div
                        (
                            setClass('node-detail-item'),
                            $notice
                        )
                    )
                ), $node->type != 'start' && $node->type != 'end' ? icon('close', setClass('delete-btn iconfont-approval-admin'), setData(array('on' => 'click', 'call' => 'deleteNode', 'params' => 'event'))) : null),
                $node->type != 'end' ? div
                (
                    div(setClass('bottom-v-line')),
                    div
                    (
                        setClass('add-node-btn'),
                        setData(array('on' => 'click', 'call' => 'addNodeType', 'params' => 'event')),
                        div
                        (
                            setClass('add-btn'),
                            icon('plus', setClass('add-btn-icon iconfont-approval-admin'))
                        )
                    )
                ) : null
            );
        }
    }

    return $editorNodes;
};

jsVar('nodeTypeLang', $lang->approvalflow->nodeTypeList);
jsVar('warningLang',  $lang->approvalflow->warningList);
jsVar('link',         inlink('design', "flowID={$flow->id}"));
jsVar('workflow',     $flow->workflow);

$btnClass = isInModal() ? 'mr-4' : '';
detailHeader
(
    to::title
    (
        entityLabel
        (
            set::entityID($flow->id),
            set::level(1),
            set::text($flow->name)
        )
    ),
    to::suffix
    (
        btn
        (
            setClass("btn primary $btnClass"),
            set::icon('save'),
            setData(array('on' => 'click', 'call' => "$('#editorForm [type=submit]').trigger('click')")),
            $lang->save
        )
    )
);

div
(
    formPanel
    (
        setID('editorForm'),
        set::ajax(array('beforeSubmit' => jsRaw('clickSubmit'))),
        set::size('full'),
        set::width('auto'),
        set::actionsClass('hidden'),
        div
        (
            setID('graph'),
            div
            (
                setID('editor'),
                setClass('editor-node branch'),
                setData(array('on' => 'click', 'call' => 'hideAddType', 'params' => 'event')),
                div
                (
                    setID('root'),
                    setClass('nodes')
                ),
                $renderNodes(json_decode($flow->nodes)),
                formHidden('nodes', $flow->nodes)
            )
        )
    ),
    modal
    (
        setID('modal'),
        set::title(!empty($node->type) && $node->type == 'conditions' ? $lang->approvalflow->noticeTypeList['setCondition'] : $lang->approvalflow->noticeTypeList['setNode']),
        !empty($node->type) ? formPanel
        (
            set::layout('grid'),
            set::actions(array('submit' => array('text' => $noticeTypeLang['confirm'], 'class' => 'primary', 'data-on' => 'click', 'data-call' => $node->type == 'conditions' ? 'saveCondition' : 'saveNode', 'data-params' => 'event', 'data-id' => isset($node->id) ? $node->id : $node->type))),
            $node->type == 'approval' || $node->type == 'cc' ? formGroup
            (
                set::label($noticeTypeLang["{$node->type}Title"]),
                set::width('full'),
                set::id('title'),
                set::name('title'),
                set::value(!empty($node->title) ? $node->title : $lang->approvalflow->nodeTypeList[$node->type])
            ) : null,
            $node->type == 'approval' ? formGroup
            (
                set::label($lang->approvalflow->type),
                set::width('full'),
                radioList(set::name('reviewType'), set::inline(true), set::items(array('manual' => $reviewTypeLang['manual'], 'pass' => $reviewTypeLang['pass'], 'reject' => $reviewTypeLang['reject'])), set::value(!empty($node->reviewType) ? $node->reviewType : 'manual'), setData(array('on' => 'change', 'call' => 'changeReviewType', 'params' => 'event')))
            ) : null,
            $node->type != 'conditions' ? formGroup
            (
                setID('reviewTypeBox'),
                set::width('full'),
                setClass(!empty($node->reviewType) && $node->reviewType == 'reject' ? 'hidden' : ''),
                tabs
                (
                    set::navClass('flex-1'),
                    set::titleClass('w-full'),
                    $node->type == 'approval' ? tabPane
                    (
                        set::key('reviewer'),
                        set::title($lang->approvalflow->reviewer),
                        set::active(empty($node->reviewType) || $node->reviewType == 'manual'),
                        set::hide(!empty($node->reviewType) && $node->reviewType != 'manual'),
                        div
                        (
                            setClass('section-list', 'canvas', 'col', 'pt-4', 'pb-6'),
                            formBatch
                            (
                                set::actions(''),
                                set::actionsWidth('64px'),
                                set::tagName('div'),
                                set::mode('add'),
                                set::minRows(1),
                                set::onRenderRow(jsRaw('renderRowData')),
                                !empty($node->reviewers) ? set::data(array_values($node->reviewers)) : null,
                                formBatchItem
                                (
                                    set::label($noticeTypeLang['reviewType']),
                                    set::name('type'),
                                    set::width('1/4'),
                                    set::control(array('control' => 'picker', 'required' => true, 'data-on' => 'change', 'data-call' => 'changeType', 'data-params' => 'event')),
                                    set::items($reviewerTypes)
                                ),
                                formBatchItem
                                (
                                    set::label($noticeTypeLang['reviewRange']),
                                    set::name('approval'),
                                    set::control(array('control' => 'inputGroup')),
                                    inputGroup(setClass('gap-4'), $reviewerBox)
                                )
                            ),
                            formGroup
                            (
                                set::label($lang->approvalflow->priv),
                                set::width('full'),
                                checkList(set::name('priv'), set::inline(true), set::items($lang->approvalflow->privList), set::value(!empty($node->priv) ? $node->priv : ''))
                            )
                        )
                    ) : null,
                    tabPane
                    (
                        set::key('ccer'),
                        set::title($lang->approvalflow->ccer),
                        set::active(!empty($node->reviewType) && $node->reviewType == 'pass'),
                        div
                        (
                            setClass('section-list', 'canvas', 'col', 'pt-4', 'pb-6'),
                            toolbar
                            (
                                btn
                                (
                                    setClass('btn primary ghost hidden add-ccer-btn'),
                                    set::icon('plus'),
                                    set::text($lang->approvalflow->noticeTypeList['addCC'] ),
                                    set::size('md')
                                )
                            ),
                            formBatch
                            (
                                setID('ccerForm'),
                                set::actions(''),
                                set::actionsWidth('64px'),
                                set::tagName('div'),
                                set::mode('add'),
                                set::minRows(1),
                                set::onRenderRow(jsRaw('renderRowData')),
                                !empty($node->ccs) ? set::data(array_values($node->ccs)) : null,
                                formBatchItem(set::label($noticeTypeLang['ccType']), set::name('ccType'), set::width('1/4'), set::control(array('control' => 'picker', 'required' => true, 'data-on' => 'change', 'data-call' => 'changeType', 'data-params' => 'event')), set::items($ccTypes)),
                                formBatchItem
                                (
                                    set::label($noticeTypeLang['ccRange']),
                                    set::name('approval'),
                                    set::control(array('control' => 'inputGroup')),
                                    inputGroup(setClass('gap-4'), $ccerBox)
                                )
                            )
                        )
                    ),
                    $node->type == 'approval' ? tabPane
                    (
                        set::key('approvalflow'),
                        set::title($lang->approvalflow->approval),
                        set::hide(!empty($node->reviewType) && $node->reviewType != 'manual'),
                        div(setClass('section-list', 'canvas', 'col', 'pt-4', 'pb-6'), $node->type == 'approval' ? formGroup
                        (
                            set::label($noticeTypeLang['multipleType']),
                            set::width('full'),
                            div
                            (
                                setClass('check-list'),
                                checkbox(set::name('multiple'), set::type('radio'), set::text($noticeTypeLang['multipleAnd']), set::value('and'), set::checked(empty($node->multiple) || $node->multiple == 'and'), setData(array('on' => 'change', 'call' => 'changeMultiple', 'params' => 'event')), checkBox(set::name('needAll'), set::text($lang->approvalflow->needAll), set::value(1), set::checked(!empty($node->needAll) && !empty($node->multiple) && $node->multiple == 'and'), set::rootClass('mx-4', empty($node->multiple) || $node->multiple == 'and' ? '' : 'hidden'))),
                                checkbox(set::name('multiple'), set::type('radio'), set::text($noticeTypeLang['multipleOr']), set::value('or'), set::checked(!empty($node->multiple) && $node->multiple == 'or'), setData(array('on' => 'change', 'call' => 'changeMultiple', 'params' => 'event')), checkBox(set::name('needAll'), set::text($lang->approvalflow->needAll), set::value(2), set::checked(!empty($node->needAll) && !empty($node->multiple) && $node->multiple == 'or'), set::rootClass('mx-4', !empty($node->multiple) && $node->multiple == 'or' ? '' : 'hidden'))),
                                checkbox
                                (
                                    set::rootClass('items-center h-5'),
                                    set::name('multiple'), set::type('radio'), set::text($noticeTypeLang['multiplePercent']), set::value('percent'), set::checked(!empty($node->multiple) && $node->multiple == 'percent'),
                                    setData(array('on' => 'change', 'call' => 'changeMultiple', 'params' => 'event')),
                                    div
                                    (
                                        setClass('input-control flex items-center has-suffix-sm w-28 mx-4', !empty($node->multiple) && $node->multiple == 'percent' ? '' : 'hidden'),
                                        cell(setClass('flex-1'), set::width('1/1'), $lang->approvalflow->percent),
                                        input(set::name('percent'), set::value(!empty($node->percent) ? $node->percent : '50'), setData(array('on' => 'change', 'call' => 'checkPercent', 'params' => 'event'))),
                                        span(setClass('input-control-suffix'), '%')
                                    ),
                                    checkBox(set::name('needAll'), set::text($lang->approvalflow->needAll), set::value(3), set::checked(!empty($node->needAll) && !empty($node->multiple) && $node->multiple == 'percent'), set::rootClass(!empty($node->multiple) && $node->multiple == 'percent' ? '' : 'hidden'))
                                ),
                                checkbox(set::name('multiple'), set::type('radio'), set::text($noticeTypeLang['multipleSolicit']), set::value('solicit'), set::checked(!empty($node->multiple) && $node->multiple == 'solicit'), setData(array('on' => 'change', 'call' => 'changeMultiple', 'params' => 'event')))
                            )
                        ) : null, $node->type == 'approval' ? formGroup
                        (
                            set::label($noticeTypeLang['commentType']),
                            set::width('full'),
                            radioList
                            (
                                set::name('commentType'),
                                set::inline(true),
                                set::items(array('noRequired' => $noticeTypeLang['noRequired'], 'required' => $noticeTypeLang['required'])),
                                set::value(!empty($node->commentType) ? $node->commentType : 'noRequired')
                            )
                        ) : null, $node->type == 'approval' ? formGroup
                        (
                            set::label($noticeTypeLang['agentType']),
                            set::width('full'),
                            radioList(set::name('agentType'), set::inline(true), set::items(array('pass' => $noticeTypeLang['agentPass'], 'reject' => $noticeTypeLang['agentReject'], 'appointee' => $noticeTypeLang['agentUser'], 'admin' => $noticeTypeLang['agentAdmin'])), set::value(!empty($node->agentType) ? $node->agentType : 'pass'), setData(array('on' => 'change', 'call' => 'changeAgentType', 'params' => 'event'))),
                            picker(set::name('agentUser'), set::items($users), set::value(!empty($node->agentUser) ? $node->agentUser : ''), setClass(!empty($node->agentType) && $node->agentType == 'appointee' ? '' : 'hidden'), set::width('200px'), set::required(true))
                        ) : null, $node->type == 'approval' ? formGroup
                        (
                            set::label($noticeTypeLang['selfType']),
                            set::width('full'),
                            radioList
                            (
                                set::name('selfType'),
                                set::inline(true),
                                set::items(array('selfReview' => $noticeTypeLang['selfReview'], 'selfPass' => $noticeTypeLang['selfPass'], 'selfNext' => $noticeTypeLang['selfNext'], 'selfManager' => $noticeTypeLang['selfManager'])),
                                set::value(!empty($node->selfType) ? $node->selfType : 'selfReview')
                            )
                        ) : null, $node->type == 'approval' ? formGroup
                        (
                            set::label($noticeTypeLang['deletedType']),
                            set::width('full'),
                            radioList(set::name('deletedType'), set::inline(true), set::items(array('autoPass' => $noticeTypeLang['autoPass'], 'autoReject' => $noticeTypeLang['autoReject'], 'setUser' => $noticeTypeLang['setUser'], 'setSuperior' => $noticeTypeLang['setSuperior'], 'setManager' => $noticeTypeLang['setManager'], 'setAdmin' => $noticeTypeLang['setAdmin'])), set::value(!empty($node->deletedType) ? $node->deletedType : 'setAdmin'), setData(array('on' => 'change', 'call' => 'changeDeletedType', 'params' => 'event'))),
                            picker(set::name('setUser'), set::items($users), set::value(!empty($node->setUser) ? $node->setUser : ''), setClass(!empty($node->deletedType) && $node->deletedType == 'setUser' ? '' : 'hidden'), set::width('200px'), set::required(true))
                        ) : null)
                    ) : null
                )
            ) : null,
            $node->type == 'conditions' ? formBatch
            (
                set::actions(''),
                set::actionsWidth('64px'),
                set::tagName('div'),
                set::mode('add'),
                set::minRows(1),
                set::onRenderRow(jsRaw('renderConditionRowData')),
                !empty($node->conditions) ? set::data(array_values($node->conditions)) : null,
                formBatchItem
                (
                    set::width('1/6'),
                    set::name('conditionLogical'),
                    set::control(array('control' => 'picker', 'required' => true)),
                    set::items($lang->workflowcondition->logicalOperatorList),
                    set::value('or')
                ),
                formBatchItem
                (
                    set::width('1/2'),
                    set::label($noticeTypeLang['when']),
                    set::name('conditionField'),
                    set::control(array('control' => 'picker', 'required' => true, 'data-on' => 'change', 'data-call' => 'changeConditionField', 'data-params' => 'event')),
                    set::items($conditionFields)
                ),
                formBatchItem
                (
                    set::width('1/6'),
                    set::name('conditionOperator'),
                    set::control(array('control' => 'picker', 'required' => true)),
                    set::value('equal'),
                    set::items($lang->workflowcondition->operatorList)
                ),
                formBatchItem
                (
                    set::width('1/2'),
                    set::label($noticeTypeLang['value']),
                    set::name('conditionValue')
                )
            ) : null
        ) : div()
    ),
    div
    (
        setID('addNode'),
        setClass('hidden'),
        div
        (
            setClass('add-node-types'),
            div
            (
                setClass('node-type add-reviewer'),
                setData(array('on' => 'click', 'call' => 'addNode', 'params' => 'event', 'type' => 'reviewer')),
                icon('review', setClass('node-type-icon iconfont-approval-admin')),
                div(setClass('node-type-name'), $lang->approvalflow->reviewer)
            ),
            div
            (
                setClass('node-type add-cc'),
                setData(array('on' => 'click', 'call' => 'addNode', 'params' => 'event', 'type' => 'cc')),
                icon('send', setClass('node-type-icon iconfont-approval-admin')),
                div(setClass('node-type-name'), $lang->approvalflow->ccer)
            ),
            div
            (
                setClass('node-type add-branch type-condition'),
                setData(array('on' => 'click', 'call' => 'addNode', 'params' => 'event', 'type' => 'condition')),
                icon('treemap', setClass('node-type-icon iconfont-approval-admin')),
                div(setClass('node-type-name'), $lang->approvalflow->condition)
            ),
            div
            (
                setClass('node-type add-branch type-parallel'),
                setData(array('on' => 'click', 'call' => 'addNode', 'params' => 'event', 'type' => 'parallel')),
                icon('tree', setClass('node-type-icon iconfont-approval-admin')),
                div(setClass('node-type-name'), $lang->approvalflow->parallel)
            )
        )
    )
);
