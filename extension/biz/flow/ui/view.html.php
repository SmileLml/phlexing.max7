<?php
/**
 * The view view file of flow module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@chandao.com>
 * @package     flow
 * @link        https://www.zentao.net
 */
namespace zin;
include 'header.html.php';

$activeTab = $flow->module;
if($currentType)
{
    if($currentMode == 'bysearch') $activeTab = 'common';
    if(isset($linkedDatas[$currentType])) $activeTab = $currentType;
}
$canLink     = $this->flow->isClickable($flow->module, 'link', $data);
$sessionName = $flow->module . 'List';
$browseLink  = $this->session->$sessionName ? $this->session->$sessionName : $this->createLink($flow->module, 'browse');
$loadLink    = $this->createLink($flow->module, 'link', "dataID=$data->id&linkType=LINKTYPE&mode=MODE");

jsVar('moduleName', $flow->module);
jsVar('linkType', $currentType);
jsVar('viewMode', $currentMode);
jsVar('loadLink', $loadLink);

detailHeader
(
    to::prefix(btn
    (
        set::icon('back'),
        set::type('secondary'),
        set::url($browseLink),
        setData('app', $app->tab),
        $lang->goback
    ), entityLabel(set(array('entityID' => $data->id, 'text' => zget($dataPairs, $data->id), 'textClass' => array('font-bold')))))
);

$obj = $this;
$showFields = function($fields, $childFields, $relations, $data, $position = 'info') use ($childDatas, $obj)
{
    global $lang;

    $html     = '';
    $children = array();
    foreach($fields as $field)
    {
        if(empty($field->show)) continue;
        if(isset($childFields[$field->field]))
        {
            $children[$field->field] = $field->name;
            continue;
        }

        if($field->position != $position) continue;

        if($field->control == 'file')
        {
            $filesName = "{$field->field}files";
            if(!empty($data->{$filesName})) $html .= '<p><strong>' . $field->name . $lang->hyphen . '</strong>' . $this->flow->getFieldValue($field, $data) . '</p>';
        }
        else
        {
            $attr     = '';
            $relation = zget($relations, $field->field, '');
            if($relation && strpos(",$relation->actions,", ',many2one,') === false && strpos(",$relation->actions,", ',many2many,') === false)
            {
                $prevDataID = isset($data->{$field->field}) ? $data->{$field->field} : 0;
                if($prevDataID) $attr = "class='prevP' data-prev='{$relation->prev}' data-next='{$relation->next}' data-field='{$relation->field}' data-dataID='$prevDataID'";
            }

            $html .= "<p $attr><strong>{$field->name}</strong>";

            $fieldValue = '';
            if(!empty($data->{$field->field}))
            {
                if(is_array($data->{$field->field}))
                {
                    foreach($data->{$field->field} as $value) $fieldValue .= $obj->flow->processFieldValue($field, $relation, $value) . ' ';
                }
                else
                {
                    $fieldValue = $obj->flow->processFieldValue($field, $relation, $data->{$field->field});
                }
            }
            else
            {
                if(isset($data->{$field->field}) && $data->{$field->field} === '0') $fieldValue = '0';
            }
            if($fieldValue !== '') $html .= ' ' . $lang->hyphen . ' ' . $fieldValue;
            $html .= '</p>';
        }
    }

    foreach($children as $child => $childName)
    {
        if(empty($childDatas[$child])) continue;
        $html .= "<div class='panel panel-block mt-2'>
            <div class='panel-heading'><strong>{$childName}</strong></div>
            <table class='table table-hover condensed table-fixed'>
            <thead><tr>";
        foreach($childFields[$child] as $childField)
        {
            if(!$childField->show) continue;
            $childWidth = ($childField->width && $childField->width != 'auto' ? $childField->width . 'px' : 'auto');
            $html      .= "<th class='text-left' style='width:{$childWidth}'>{$childField->name}</th>";
        }
        $html .= "</tr></thead><tbody>";
        foreach($childDatas[$child] as $childData)
        {
            $html .= "<tr>";
            foreach($childFields[$child] as $childField)
            {
                if(!$childField->show) continue;
                if(strpos(',date,datetime,', ",$childField->control,") !== false)
                {
                    $childValue = formatTime($childData->{$childField->field});
                }
                else
                {
                    if(is_array($childData->{$childField->field}))
                    {
                        $childValues = array();
                        foreach($childData->{$childField->field} as $value)
                        {
                            if(!empty($value)) $childValues[] = zget($childField->options, $value);
                        }
                        $childValue = implode(',', $childValues);
                    }
                    else
                    {
                        $childValue = zget($childField->options, $childData->{$childField->field});
                    }
                }
                $html .= "<td title='{$childValue}'>{$childValue}</td>";
            }
            $html .= "</tr>";
        }
        $html .= "</tbody></table></div>";
    }
    return $html;
};

$showSide = function() use ($processBlocks, $childFields, $relations, $obj, $data, $flow)
{
    $buildBlock = function($blockFields) use ($obj, $childFields, $relations, $data)
    {
        $html = "<table class='table borderless condensed mb-2'>";
        foreach($blockFields as $field)
        {
            if(!$field->show) continue;
            if(isset($childFields[$field->field])) continue;

            /* Display data of the prev flow. */
            $attr     = '';
            $link     = '';
            $relation = zget($relations, $field->field, '');
            if($relation && strpos(",$relation->actions,", ',many2one,') === false)
            {
                $prevDataID = isset($data->{$field->field}) ? $data->{$field->field} : 0;
                $attr       = "class='prevTR' data-prev='{$relation->prev}' data-next='{$relation->next}' data-field='{$relation->field}' data-dataID='$prevDataID'";

                if(hasPriv($relation->prev, 'view')) $link = createLink($relation->prev, 'view', "dataID=$prevDataID");
            }

            $html .= "<tr {$attr}><th class='w-24 text-right'>{$field->name}</th><td>";
            if($field->control == 'file')
            {
                $filesName = "{$field->field}files";
                $html     .= $obj->fetch('file', 'printFiles', array('files' => $data->{$filesName}, 'fieldset' => 'false'));
            }
            elseif(is_array($data->{$field->field}))
            {
                foreach($data->{$field->field} as $value) $html .= zget($field->options, $value) . ' ';
            }
            else
            {
                if(strpos(',date,datetime,', ",$field->control,") !== false)
                {
                    $html .= formatTime($data->{$field->field});
                }
                else
                {
                    $fieldValue = zget($field->options, $data->{$field->field});
                    $html .= $link ? html::a($link, $fieldValue) : $fieldValue;
                }
            }
            $html .= "</td></tr>";
        }
        $html .= "</table>";
        return $html;
    };

    $html = '';
    foreach($processBlocks as $blockKey => $block)
    {
        $html .= "<div class='panel canvas mb-3'>";
        if(empty($block->tabs))
        {
            if($block->name) $html .= "<div class='panel-heading'><strong>{$block->name}</strong></div>";
        }
        else
        {
            $html .= "<div class='panel-heading'>";
            $html .= "<ul class='tabs-nav nav nav-tabs gap-x-2'>";
            $index = 1;
            foreach($block->tabs as $tabKey => $tab)
            {
                $class = $index == 1 ? "font-medium active font-bold text-md" : '';
                $html .= "<li class='nav-item'><a class='{$class}' data-toggle='tab' href='#{$blockKey}_{$tabKey}Tab'>{$tab->name}</a></li>";
                $index ++;
            }
            $html .= "</ul></div>";
        }

        if(!empty($block->tabs))
        {
            $html .= "<div class='tab-content'>";
            $index = 1;
            foreach($block->tabs as $tabKey => $tab)
            {
                $class = $index == 1 ? 'active' : '';
                $html .= "<div id='{$blockKey}_{$tabKey}Tab' class='{$class} tab-pane'>";
                $html .= $buildBlock($tab->fields);
                $html .= "</div>";
                $index ++;
            }
            $html .= "</div>";
        }
        elseif(!empty($block->fields))
        {
            $html .= $buildBlock($block->fields);
        }
        $html .= "</div>";
    }

    global $lang;
    $relatedObjectHTML = wg(relatedObjectList(set::objectID($data->id), set::objectType("workflow_{$flow->module}"), set::browseType('byObject')))->renderInner();
    $html .= "<div class='panel canvas mb-3'><div class='panel-heading'><strong>{$lang->custom->relateObject}</strong></div><div class='px-2 pb-2'>$relatedObjectHTML</div></div>";
    return $html;
};

$canUnlink = $this->flow->isClickable($flow->module, 'unlink', $data);
$buildLinked = function($linkType, $dataList) use($canUnlink, $obj, $flow, $data, $currentType, $uiID, $activeTab)
{
    global $config, $lang;
    $html = '';
    if(in_array($linkType, $config->flow->linkPairs))
    {
        list($linkedApp, $linkedModule) = $obj->flow->extractAppAndModule($linkType);

        $html .= $obj->fetch($linkedModule, 'linked', "module=$flow->module&dataID=$data->id&linkType=$currentType", $linkedApp);
        return $html;
    }

    $linkedFields   = $obj->workflowaction->getFields($linkType, 'browse', true, $dataList, $uiID);
    $active         = $activeTab == $linkType ? 'active' : '';
    $batchActions   = '';
    $canBatchAction = $config->flow->showBatchActionsInLinkedPage;
    if($canBatchAction)
    {
        $batchActions   = $obj->flow->buildBatchActions($linkType);
        $canBatchAction = !empty($batchActions);
    }
    if($currentMode != 'browse') return $html;

    $html .= "<form id='unlink{$linkType}Form' method='post' class='main-table' data-ride='table'>";
    $html .= "<table class='table table-form linkedTable'><thead>";
    $index = 1;
    foreach($linkedFields as $field)
    {
        if(!$field->show) continue;
        if(!$canUnlink && $field->field == 'actions') continue;
        $width = ($field->width && $field->width != 'auto' ? $field->width . 'px' : 'auto');
        $fieldName = $field->name;
        if($index == 1 && ($canUnlink or $canBatchAction)) $fieldName = "<div class='checkbox-primary check-all' title='{$this->lang->selectAll}'><label>{$field->name}</label></div>";
        $html .= "<th class='text-{$field->position}' style='width:{$width}'>{$fieldName}</th>";
        $index++;
    }
    $html .= "</thead><tbody>";
    foreach($dataList as $linkedData)
    {
        $html .= "<tr>";
        $index = 1;
        foreach($linkedFields as $field)
        {
            if(!$field->show or $field->field == 'actions') continue;
            $output = '';
            if(is_array($linkedData->{$field->field}))
            {
                foreach($linkedData->{$field->field} as $value) $output .= zget($field->options, $value) . ' ';
            }
            else
            {
                if($field->field == 'id')
                {
                    $output = $linkedData->id;
                    if(hasPriv($linkType, 'view'))
                    {
                        $action = $obj->workflowaction->getByModuleAndAction($linkType, 'view');
                        $attr   = $action->open == 'modal' ? "data-toggle='modal'" : '';
                        $output = baseHTML::a(helper::createLink($linkType, 'view', "dataID={$linkedData->id}"), $linkedData->id, $attr);
                    }
                }
                else
                {
                    $output = zget($field->options, $linkedData->{$field->field});
                }
            }
            $html .= "<td class='text-{$field->position}' title='" . strip_tags(str_replace("</p>", "\n", str_replace(array("\n", "\r"), "", $output))) . "'>";
            $fieldValue = $output;
            if($index == 1 && ($canUnlink or $canBatchAction))
            {
                $fieldValue = "<div class='checkbox-primary'><input type='checkbox' name='dataIDList[]' value='{$linkedData->id}' id='dataIDList{$linkedData->id}'><label for='dataIDList{$linkedData->id}'>" . ($field->field == 'id' ? $output : '') . "</label></div>";
                if($field->field == 'id')$fieldValue .= $output;
            }
            $index++;
        }
        if($canUnlink)
        {
            $html .= "<td class='nowrap'>";
            $confirm = sprintf($lang->flow->unlinkConfirm, zget($linkPairs, $linkType));
            $html .= baseHTML::a(createLink($flow->module, 'unlink', "dataID=$data->id&linkType=$linkType&linkedID=$linkedData->id"), $lang->flow->unlink, "class='unlink' data-confirm='$confirm' onclick='unlinkData(this)'");
            $html .= "</td>";
        }
        $html .= "</tr>";
    }
    $html .= "</tbody></table><div class='table-footer'>";
    if($canUnlink or $canBatchAction)
    {
        $html .= "<div class='checkbox-primary check-all'><label>{$lang->selectAll}</label></div>";
        $html .= "<div class='table-actions btn-toolbar'>";
        if($canUnlink)
        {
            $actionLink = $this->createLink($flow->module, 'unlink', "dataID=$data->id&linkType=$linkType");
            $html .= baseHTML::a('javascript:;', $lang->flow->unlink, "class='btn' onclick=\"setFormAction('$actionLink', '', this)\"");
        }
        if($canBatchAction) $html .= $batchActions;
        $html .= "</div>";
    }
    $html .= "<div class='table-statistic'></div></div></form>";
    return $html;
};

$buildOperateMenu = function() use($flow, $data, $obj, $browseLink, $groupID)
{
    if(zget($data, 'deleted', '0') == '1' || !$flow) return '';

    global $lang, $config, $app;
    $obj->loadModel('workflowaction');

    $menu  = "<div class='center sticky mt-4 bottom-4'><div class='bg-black text-fore-in-dark backdrop-blur bg-opacity-60 rounded p-1.5'><div class='toolbar'>";
    $menu .= "<a href='{$browseLink}' class='btn ghost'><i class='icon icon-back'></i><span class='text'>{$lang->goback}</span></a>";
    $menu .= "<div class='divider toolbar-divider'></div>";

    $relations = $obj->dao->select('next, actions')->from(TABLE_WORKFLOWRELATION)->where('prev')->eq($flow->module)->fetchPairs();
    $actions   = $obj->workflowaction->getList($flow->module, 'status_desc,order_asc', $groupID);
    foreach($actions as $action) $menu .= $obj->flow->buildActionMenu($flow->module, $action, $data, 'view', $relations);

    if(!empty($config->openedApproval) && $flow->approval == 'enabled' && hasPriv('approval', 'progress') && !empty($data->approval))
    {
        $menu .= "<div class='divider toolbar-divider'></div>";
        $menu .= html::a(helper::createLink('approval', 'progress', "approvalID={$data->approval}"), $lang->flow->approvalProgress, '', "class='btn ghost' data-toggle='modal'");
    }
    $menu .= '</div></div></div>';

    $menu = str_replace(array('<a href', 'btn-link', 'deleter'), array("<a data-app='{$app->tab}' href", 'ghost', 'ajax-submit deleter'), $menu);
    $menu = preg_replace("/(class=[\"|\'][^\"\']+ajax-submit deleter[\"|\'])/",  "data-confirm='{$lang->confirmDelete}' $1", $menu);
    return $menu;
};

$tabPanes   = array();
$tabPanes[] = tabPane
(
    set::key($flow->module),
    set::title($flow->name . $lang->flow->detail),
    set::active($activeTab == $flow->module),
    setClass('relative'),
    $canLink ? div
    (
        setClass('tab-actions'),
        btn(setClass('primary'), setData('toggle', 'modal'), set::url('#linkTypeBox'), set::text($lang->workflowaction->default->actions['link']))
    ) : null,
    row
    (
        cell
        (
            setClass('mainbar w-3/4 mr-3'),
            html("<div class='panel canvas mb-3'><div class='panel-body'>" . $showFields($fields, $childFields, $relations, $data) . '</div></div>'),
            history(set::objectType($flow->module), set::objectID($data->id)),
            html($buildOperateMenu())
        ),
        cell
        (
            setClass('sidebar w-1/4'),
            html($showSide())
        )
    )
);

foreach($linkedDatas as $linkType => $datas)
{
    $tabPanes[] = tabPane
    (
        set::key($linkType),
        set::title(zget($linkPairs, $linkType, '')),
        set::active($activeTab == $linkType),
        setClass('relative'),
        $canLink ? div
        (
            setClass('tab-actions'),
            btn(setClass('primary'), setData('toggle', 'modal'), set::url('#linkTypeBox'), set::text($lang->workflowaction->default->actions['link']))
        ) : null,
        html($buildLinked($linkType, $datas))
    );
}
if($canLink)
{
    $tabPanes[] = tabPane
    (
        set::key('common'),
        set::active($activeTab == 'common'),
        div
        (
            setClass('tab-actions'),
            btn(setClass('primary'), setData('toggle', 'modal'), set::url('#linkTypeBox'), set::text($lang->workflowaction->default->actions['link']))
        )
    );
    modal
    (
        setID('linkTypeBox'),
        set::closeBtn(true),
        set::title($lang->workflowaction->default->actions['link']),
        on::change('[name=linkType]', 'linkData'),
        formGroup
        (
            set::label($lang->flow->selectLinkType),
            picker(set::name('linkType'), set::items(array('' => '') + $linkPairs))
        )
    );
}

$buttons     = array();
$linkCreator = createLink($app->rawModule, $app->rawMethod, 'dataID={id}');
if(!empty($preAndNext->pre))
{
    $buttons[] = btn
        (
            setClass('detail-prev-btn absolute top-0 left-0 inverse rounded-full w-12 h-12 center bg-opacity-40 backdrop-blur ring-0'),
            set::icon('angle-left icon-2x text-canvas'),
            setData('app', $app->tab),
            set('hint', "#{$preAndNext->pre->id}"),
            set('url', str_replace('{id}', "{$preAndNext->pre->id}", $linkCreator))
        );
}

if(!empty($preAndNext->next))
{
    $buttons[] = btn
        (
            setClass('detail-next-btn absolute top-0 right-0 inverse rounded-full w-12 h-12 center bg-opacity-40 backdrop-blur ring-0'),
            set::icon('angle-right icon-2x text-canvas'),
            setData('app', $app->tab),
            set('hint', "#{$preAndNext->next->id}"),
            set('url', str_replace('{id}', "{$preAndNext->next->id}", $linkCreator))
        );
}

div
(
    tabs($tabPanes),
    !empty($buttons) ? div
    (
        setClass('detail-prev-next fixed top-0 left-0 bottom-0 right-0 z-10 pointer-events-none'),
        div
        (
            setClass('container relative pointer-events-auto'),
            setStyle(array('top' => '50%', 'margin' => '-24px auto auto')),
            $buttons
        )
    ) : null
);
