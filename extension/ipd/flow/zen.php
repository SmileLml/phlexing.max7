<?php
/**
 * The zen file of flow module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     flow
 * @link        https://www.zentao.net
 */
class flowZen extends flow
{
    /**
     * 生成标签栏的菜单项。
     * Generate the menu items of the feature bar.
     *
     * @param  object $flow     流程对象。
     * @param  int    $label    标签ID。
     * @param  int    $recTotal 记录总数。
     * @param  int    $groupID  分组ID。
     * @access public
     * @return array
     */
    public function buildFeatureBarItems($flow, $label, $recTotal, $groupID = 0)
    {
        $labelGroups = $this->dao->select('id,`group`,label')->from(TABLE_WORKFLOWLABEL)
            ->where('module')->eq($flow->module)
            ->andWhere('group')->in("0,{$groupID}")
            ->beginIF($flow->approval == 'disabled')->andWhere('role')->ne('approval')->fi()
            ->orderBy('order')
            ->fetchGroup('group', 'id');
        $labels = isset($labelGroups[$groupID]) ? $labelGroups[$groupID] : $labelGroups[0];

        $items = array();
        if(!isset($labels[$label])) $label = key($labels);
        foreach($labels as $labelID => $thisLabel)
        {
            if(!commonModel::hasPriv($flow->module, (string)$labelID)) continue;
            $items[] = array(
                'text'   => $thisLabel->label,
                'active' => $labelID == $label,
                'url'    => $this->createLink($flow->module, 'browse', "mode=browse&label=$labelID"),
                'badge'  => $labelID == $label && $recTotal != '' ? array('text' => $recTotal, 'class' => 'size-sm rounded-full white') : null,
                'props'  => array('data-id' => $labelID)
            );
        }

        if($flow->navigator == 'secondary') $items = $this->setSecondaryLink($items);
        return $items;
    }

    /**
     * 生成工具栏的菜单项。
     * Generate the menu items of the toolbar.
     *
     * @param  string $module 模块名称。
     * @param  string $navigator 导航位置。
     * @access public
     * @return array
     * @param int $groupID
     */
    public function buildToolbarItems($module, $navigator, $groupID = 0)
    {
        $actionGroups = $this->dao->select('action,`group`,name')->from(TABLE_WORKFLOWACTION)
             ->where('module')->eq($module)
             ->andWhere('status')->eq('enable')
             ->andWhere('group')->in("0,{$groupID}")
             ->andWhere('action')->in('report,export,exporttemplate,import,showimport,create,batchcreate')
             ->fetchGroup('group', 'action');
        $pairs   = array();
        $actions = isset($actionGroups[$groupID]) ? $actionGroups[$groupID] : $actionGroups[0];
        foreach($actions as $action) $pairs[$action->action] = $action->name;

        $canReport         = common::hasPriv($module, 'report')         && isset($actions['report']);
        $canExport         = common::hasPriv($module, 'export')         && isset($actions['export']);
        $canExportTemplate = common::hasPriv($module, 'exportTemplate') && isset($actions['exporttemplate']);
        $canImport         = common::hasPriv($module, 'import')         && isset($actions['import']);
        $canShowImport     = common::hasPriv($module, 'showImport')     && isset($actions['showimport']);
        $canCreate         = common::hasPriv($module, 'create')         && isset($actions['create']);
        $canBatchCreate    = common::hasPriv($module, 'batchCreate')    && isset($actions['batchcreate']);

        $this->loadModel('workflowaction');
        $actionLang = $this->lang->workflowaction->default->actions;

        $items = array();

        if($canReport) $items['reportItem'] = array('class' => 'btn ghost', 'text' => zget($pairs, 'report', $actionLang['report']), 'icon' => 'bar-chart', 'url' => $this->createLink($module, 'report'), 'props' => array('data-id' => 'report'));

        if($canExport)
        {
            $items['exportItems'][] = array('text' => $this->lang->exportAll, 'url' => $this->createLink($module, 'export', 'mode=all'), 'data-toggle' => 'modal');
            $items['exportItems'][] = array('text' => $this->lang->exportThisPage, 'url' => $this->createLink($module, 'export', 'mode=thisPage'), 'data-toggle' => 'modal');
        }

        if(($canImport && $canShowImport) || $canExportTemplate)
        {
            $importItems = array();
            if($canImport && $canShowImport) $items['importItems'][] = array('text' => zget($pairs, 'import', $actionLang['import']), 'url' => $this->createLink($module, 'import'), 'data-toggle' => 'modal');
            if($canExportTemplate) $items['importItems'][] = array('text' => zget($pairs, 'exportTemplate', $actionLang['exporttemplate']), 'url' => $this->createLink($module, 'exportTemplate'), 'data-toggle' => 'modal');
        }

        if($canCreate) $items['createItem'] = array('text' => zget($pairs, 'create', $actionLang['create']), 'url' => $this->createLink($module, 'create'));
        if($canBatchCreate) $items['batchCreateItem'] = array('text' => zget($pairs, 'batchCreate', $actionLang['batchcreate']), 'url' => $this->createLink($module, 'batchCreate'));

        if($navigator == 'secondary') $items = $this->setSecondaryLink($items);
        return $items;
    }

    /**
     * 生成数据表格的菜单配置。
     * Generate the menu configuration of the data table.
     *
     * @param  array $actions 动作列表。
     * @param  int   $groupID 分组ID。
     * @access public
     * @return array
     * @param string $module
     */
    public function buildDtableMenu($module, $groupID = 0)
    {
        $actionGroups = $this->dao->select('action,`group`,`show`,`virtual`')->from(TABLE_WORKFLOWACTION)
            ->where('module')->eq($module)
            ->andWhere('status')->eq('enable')
            ->andWhere('group')->in("0,{$groupID}")
            ->andWhere('type', true)->eq('single')
            ->andWhere('position')->in('browse,browseandview')
            ->orWhere('`virtual`')->eq('1')
            ->markRight(1)
            ->andWhere('position')->in('browse,browseandview')
            ->orderBy('order')
            ->fetchGroup('group', 'action');

        $actions   = isset($actionGroups[$groupID]) ? $actionGroups[$groupID] : $actionGroups[0];
        $relations = $this->dao->select('next, actions')->from(TABLE_WORKFLOWRELATION)->where('prev')->eq($module)->fetchPairs();
        foreach($actions as $key => $action)
        {
            if($action->virtual == '1')
            {
                $moduleName = $module;
                $methodName = $action->action;
                if(strpos($methodName, '_') !== false && strpos($methodName, '_') > 0) list($moduleName, $methodName) = explode('_', $methodName);

                $relationConfig = $relations[$moduleName];
                if($methodName == 'create'      && strpos(",{$relationConfig},", ",one2one,")  === false) unset($actions[$key]);
                if($methodName == 'batchcreate' && strpos(",{$relationConfig},", ",one2many,") === false) unset($actions[$key]);
            }
        }

        /**
         * 如果有审批提交和审批取消两个动作，合并为一个菜单。
         * If there are two actions, approval submit and approval cancel, merge them into one menu.
         */
        if(isset($actions['approvalcancel']) && isset($actions['approvalsubmit']))
        {
            $actions['approvalsubmit']->action = 'approvalsubmit|approvalcancel';
            unset($actions['approvalcancel']);
        }

        $directActions = array_filter(array_map(function($action){if($action->show == 'direct') return $action->action;}, $actions));
        $moreActions   = array_filter(array_map(function($action){if($action->show == 'dropdownlist') return $action->action;}, $actions));

        $menu = array_values($directActions);
        if($moreActions) $menu['more'] = array_values($moreActions);

        return $menu;
    }

    /**
     * 生成数据表格的动作列表配置。
     * Generate the action list configuration of the data table.
     *
     * @param  string $module 模块名称。
     * @param  string $navigator 导航位置。
     * @param int $groupID 分组ID。
     * @access public
     * @return array
     */
    public function buildDtableActions($module, $navigator, $groupID = 0)
    {
        $actions    = array();
        $actionGroups = $this->dao->select('action,`group`,name,open,`virtual`')->from(TABLE_WORKFLOWACTION)
            ->where('module')->eq($module)
            ->andWhere('status')->eq('enable')
            ->andWhere('group')->in("0,{$groupID}")
            ->orderBy('order')
            ->fetchGroup('group', 'action');

        $actionList = isset($actionGroups[$groupID]) ? $actionGroups[$groupID] : $actionGroups[0];
        foreach($actionList as $key => $action)
        {
            if(!common::hasPriv($module, $action->action)) continue;

            $moduleName = $module;
            $methodName = $action->action;
            if(strpos($methodName, '_') !== false && strpos($methodName, '_') > 0) list($moduleName, $methodName) = explode('_', $methodName);

            $actions[$action->action]['text'] = $action->name;
            $actions[$action->action]['hint'] = $action->name;
            $actions[$action->action]['url']  = $this->createLink($moduleName, $methodName, $action->virtual ? 'step=form&prevModule=' . $module . '&dataID={id}' : 'dataID={id}');

            if($action->action == 'delete')
            {
                $actions[$action->action]['class']        = 'ajax-submit';
                $actions[$action->action]['innerClass']   = 'ajax-submit';
                $actions[$action->action]['data-confirm'] = $this->lang->confirmDelete;
            }

            if($action->open == 'modal')
            {
                $actions[$action->action]['data-toggle'] = 'modal';
                $actions[$action->action]['data-size']   = 'lg';
            }
        }

        if($navigator == 'secondary') $actions = $this->setSecondaryLink($actions);
        return $actions;
    }

    /**
     * 生成数据表格的批量操作工具栏。
     * Generate the batch action toolbar of the data table.
     *
     * @param  string $module 模块名称。
     * @access public
     * @return array
     * @param int $groupID
     */
    public function buildDtableFootToolbar($module, $groupID = 0)
    {
        $actionGroups = $this->dao->select('action,`group`,name,`virtual`')->from(TABLE_WORKFLOWACTION)
            ->where('module')->eq($module)
            ->andWhere('group')->in("0,{$groupID}")
            ->andWhere('status')->eq('enable')
            ->andWhere('type', true)->eq('batch')
            ->andWhere('position')->eq('browse')
            ->orWhere('`virtual`')->eq('1')
            ->markRight(1)
            ->orderBy('order')
            ->fetchGroup('group', 'action');

        if(empty($actionGroups)) return array();
        $actions   = isset($actionGroups[$groupID]) ? $actionGroups[$groupID] : $actionGroups[0];
        $relations = $this->dao->select('next, actions')->from(TABLE_WORKFLOWRELATION)->where('prev')->eq($module)->fetchPairs();
        foreach($actions as $key => $action)
        {
            if(!common::hasPriv($module, $action->action)) unset($actions[$key]);
            if($action->virtual == '1')
            {
                $moduleName = $module;
                $methodName = $action->action;
                if(strpos($methodName, '_') !== false && strpos($methodName, '_') > 0) list($moduleName, $methodName) = explode('_', $methodName);

                $relationConfig = $relations[$moduleName];
                if($methodName == 'create'      && strpos(",{$relationConfig},", ",one2one,")  === false) unset($actions[$key]);
                if($methodName == 'batchcreate' && strpos(",{$relationConfig},", ",one2many,") === false) unset($actions[$key]);
            }
        }

        if(!$actions) return array();

        $firstAction = array_shift($actions);

        $moduleName = $module;
        $methodName = $firstAction->action;
        if(strpos($methodName, '_') !== false && strpos($methodName, '_') > 0) list($moduleName, $methodName) = explode('_', $methodName);
        $firstAction = array('text' => $firstAction->name, 'className' => 'secondary open-url', 'data-load' => 'post', 'data-data-map' => 'dataIDList[]:#dataList~checkedIDList', 'data-url' => $this->createLink($moduleName, $methodName, $firstAction->virtual ? 'step=form&prevModule=' . $module . '&prevDataID=' : 'step=form'));
        if(!$actions) return array($firstAction);

        $batchItems = array();
        foreach($actions as $action)
        {
            $moduleName = $module;
            $methodName = $action->action;
            if(strpos($methodName, '_') !== false && strpos($methodName, '_') > 0) list($moduleName, $methodName) = explode('_', $methodName);

            $batchItems[] = array('text' => $action->name, 'data-load' => 'post', 'data-data-map' => 'dataIDList[]:#dataList~checkedIDList', 'url' => $this->createLink($moduleName, $methodName, $action->virtual ? 'step=form&prevModule=' . $module . '&prevDataID=' : 'step=form'));
        }
        return array
        (
            array('type' => 'btn-group', 'items' => array
            (
                $firstAction,
                array('caret' => 'up', 'data-placement' => 'top-start' , 'className' => 'secondary', 'items' => $batchItems)
            ))
        );
    }

    /**
     * 生成批量表单配置项。
     * Generate the batch form items.
     *
     * @param  array  $fields       字段列表。
     * @param  string $defaultDitto 默认是否勾选“同上”。
     * @access public
     * @return array
     */
    public function buildBatchFormItems($fields, $defaultDitto = 'on')
    {
        $defaultDitto = $this->app->rawMethod == 'showimport' ? 'off' : $defaultDitto;
        $dittoControl = array('select', 'multi-select', 'radio', 'checkbox', 'date', 'datetime');
        $notEmptyRule = $this->loadModel('workflowrule', 'flow')->getByTypeAndRule('system', 'notempty');

        $items = array();
        foreach($fields as $field)
        {
            if(!$field->show) continue;

            $items[] = array
            (
                'name'         => $field->field,
                'label'        => $field->name,
                'control'      => $this->flow->buildFormControl($field, 'batch'),
                'items'        => array_filter($field->options),
                'width'        => $field->width == 'auto' ? '160px' : $field->width,
                'value'        => $field->default ? $field->default : $field->defaultValue,
                'required'     => $notEmptyRule && (strpos(",{$field->layoutRules},", ",{$notEmptyRule->id},") !== false || strpos(",{$field->rules},", ",{$notEmptyRule->id},") !== false),
                'ditto'        => in_array($field->control, $dittoControl),
                'defaultDitto' => $defaultDitto,
                'placeholder'  => $field->placeholder
            );
        }

        return $items;
    }

    /**
     * 获取当前审批节点的数据。
     * Build approval for review.
     *
     * @param  string $module
     * @param  int    $dataID
     * @access public
     * @return void
     */
    public function buildApprovalForReview($module, $dataID)
    {
        $approval  = $this->loadModel('approval')->getByObject($module, $dataID);
        $doingNode = $this->dao->select('node,COUNT(1) as count')->from(TABLE_APPROVALNODE)->where('approval')->eq($approval->id)->andWhere('status')->eq('doing')->andWhere('type')->eq('review')->groupBy('node')->fetch();
        if(!$doingNode || !$this->loadModel('approval')->isReviewed($module, $dataID)) return array('result' => 'fail', 'callback' => "zui.Modal.alert({icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x', message: '{$this->lang->hasReviewed}'}).then((res) => {loadCurrentPage()});");

        $nodeGroups     = $this->approval->getNodeOptions(json_decode($approval->nodes));
        $currentNode    = zget($nodeGroups, $doingNode->node);
        $canRevertNodes = $this->loadModel('approval')->getCanRevertNodes($approval->id, $currentNode);

        $this->view->currentNode    = $currentNode;
        $this->view->canRevertNodes = $canRevertNodes;
    }

    /**
     * 根据模块设置不同的导航菜单。
     * Set operate menu.
     *
     * @param  object $object
     * @access public
     * @return void
     */
    public function setOperateMenu($object)
    {
        $module = $this->app->rawModule;
        if($module == 'demand')
        {
            $this->loadModel('demandpool')->setMenu($object->pool);
            $this->view->poolID = $object->pool;
        }

        if(in_array($this->app->tab, $this->config->hasDropmenuApps))
        {
            $objectType = $this->app->tab;
            if(in_array($this->app->tab, array('qa', 'feedback'))) $objectType = 'product';
            if($this->app->tab == 'bi') $objectType = 'dimension';

            $objectIDVar = $objectType . 'ID';
            if(!isset($this->view->{$objectIDVar})) $this->view->{$objectIDVar} = 0;
            if(isset($object->{$objectType})) $this->view->{$objectIDVar} = $object->{$objectType};
            if($objectType == 'product') $this->view->branchID = isset($object->branch) ? $object->branch : 0;
        }
    }

    /**
     * 设置二级菜单下的链接。
     * Set the link under the secondary menu.
     *
     * @param  array     $items
     * @access protected
     * @return array
     */
    protected function setSecondaryLink($items)
    {
        foreach($items as $key => $item)
        {
            if(!isset($item['url']))
            {
                foreach($item as $childKey => $childItem)
                {
                    if(!isset($childItem['data-app'])) $items[$key][$childKey]['data-app'] = $this->app->tab;
                }
            }
            elseif(!isset($item['data-app']))
            {
                $items[$key]['data-app'] = $this->app->tab;
            }
        }

        return $items;
    }
}
