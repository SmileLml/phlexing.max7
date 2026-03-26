<?php
class flowCommon extends commonModel
{
    /**
     * Load custom lang.
     *
     * @param  string $rawModule
     * @param  string $rawMethod
     * @access public
     * @return void
     */
    public function loadCustomLang($rawModule = '', $rawMethod = '')
    {
        if(!$this->app->isServing()) return;

        $rawModule = !$rawModule ? $this->app->rawModule : $rawModule;
        $rawMethod = !$rawMethod ? $this->app->rawMethod : $rawMethod;

        $flowModule = $rawModule;
        if($rawModule == 'execution'      && $rawMethod == 'task')      $flowModule = 'task';
        if($rawModule == 'projectrelease' && $rawMethod == 'browse')    $flowModule = 'release';
        if($rawModule == 'project'        && $rawMethod == 'execution') $flowModule = 'execution';
        if($rawModule == 'execution'      && $rawMethod == 'bug')       $flowModule = 'bug';
        if($rawModule == 'assetlib'       && $rawMethod == 'caselib')   $flowModule = 'caselib';
        if(($rawModule == 'project' || $rawModule == 'execution') && $rawMethod == 'build') $flowModule = 'build';

        $flow = $this->dao->select('id,module,`table`,approval,buildin')->from(TABLE_WORKFLOW)->where('module')->eq($flowModule)->fetch();
        if($flow)
        {
            $table      = $this->processFlowTable($flow->table);
            $flowFields = $this->dao->select('id,module,field,name')->from(TABLE_WORKFLOWFIELD)->where('module')->eq($rawModule)->beginIF($flow->buildin)->andWhere('buildin')->eq('0')->fi()->fetchAll();
            if($flowFields)
            {
                if(!isset($this->lang->{$rawModule})) $this->lang->{$rawModule} = new stdclass();
                if($_POST and $table != $rawModule and !isset($this->lang->{$table})) $this->lang->{$table} = new stdclass();
                if(($rawModule == 'execution' or $rawModule == 'program') and !isset($this->lang->project)) $this->lang->project = new stdclass();
                if(($rawModule == 'caselib') and !isset($this->lang->testsuite)) $this->lang->testsuite = new stdclass();

                foreach($flowFields as $field)
                {
                    $this->lang->{$rawModule}->{$field->field} = $field->name;
                    if($_POST and $table != $rawModule) $this->lang->{$table}->{$field->field} = $field->name;
                    if($rawModule == 'execution' or $rawModule == 'program') $this->lang->project->{$field->field} = $field->name;
                    if($rawModule == 'caselib') $this->lang->testsuite->{$field->field} = $field->name;
                }
            }

            $flowLabels = $this->dao->select('id,module,label')->from(TABLE_WORKFLOWLABEL)
                ->where('module')->eq($flowModule)
                ->andWhere('code')->eq('review')
                ->andWhere('buildin')->eq(0)
                ->andWhere('role')->eq('approval')
                ->fetchAll();

            if($flowLabels && $flow->approval == 'enabled')
            {
                foreach($flowLabels as $label)
                {
                    if($rawModule == 'project' && $label->module == 'execution')
                    {
                        $this->lang->execution->featureBar['all']['review'] = $label->label;
                    }
                    if($rawModule == 'execution' && $label->module == 'bug')
                    {
                        $this->lang->execution->featureBar['bug']['review'] = $label->label;
                    }
                    else if($label->module == 'execution' || $label->module == 'product')
                    {
                        $this->lang->{$rawModule}->featureBar['all']['review'] = $label->label;
                    }
                    else if($label->module == 'task' || $label->module == 'build' || $label->module == 'caselib')
                    {
                        $this->lang->{$rawModule}->featureBar[$flowModule]['review'] = $label->label;
                    }
                    else
                    {
                        $this->lang->{$rawModule}->featureBar['browse']['review'] = $label->label;
                    }
                }
            }

            /* Append children flow fields. */
            $childrenFlows = $this->dao->select('id,module,`table`')->from(TABLE_WORKFLOW)->where('parent')->eq($rawModule)->andWhere('type')->eq('table')->fetchAll('module');
            if($childrenFlows)
            {
                $childrenFlowFields = $this->dao->select('id,module,field,name')->from(TABLE_WORKFLOWFIELD)->where('module')->in(array_keys($childrenFlows))->beginIF($flow->buildin)->andWhere('buildin')->eq('0')->fi()->fetchGroup('module', 'id');
                foreach($childrenFlowFields as $childModule => $flowFields)
                {
                    $childFlow = $childrenFlows[$childModule];
                    $table     = $this->processFlowTable($childFlow->table);

                    if(!isset($this->lang->{$childModule})) $this->lang->{$childModule} = new stdclass();
                    if($_POST and $table != $childModule and !isset($this->lang->{$table})) $this->lang->{$table} = new stdclass();
                    foreach($flowFields as $field)
                    {
                        $this->lang->{$childModule}->{$field->field} = $field->name;
                        if($_POST and $table != $childModule) $this->lang->{$table}->{$field->field} = $field->name;
                    }
                }
            }
        }
    }

    /**
     * Process flow table.
     *
     * @param  string    $table
     * @access public
     * @return string
     */
    public function processFlowTable($table)
    {
        if(isset($this->config->db->prefix))
        {
            $table = strtolower(str_replace(array($this->config->db->prefix, '`'), '', $table));
        }
        elseif(strpos($this->table, '_') !== false)
        {
            $table = strtolower(substr($table, strpos($table, '_') + 1));
            $table = str_replace('`', '', $table);
        }

        return $table;
    }

    /**
     * Merge primary menu for flows
     *
     * @access public
     * @return void
     */
    public function mergePrimaryFlows()
    {
        if(!$this->app->isServing()) return;

        $this->app->setOpenApp();

        $flows = $this->dao->select('*')->from(TABLE_WORKFLOW)
            ->where('buildin')->eq('0')
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('status')->eq('normal')
            ->andWhere('navigator')->eq('primary')
            ->andWhere('type')->eq('flow')
            ->andWhere('`group`')->eq(0)
            ->fetchAll('id');

        $primaryFlows = array();
        foreach($flows as $flow)
        {
            $primaryFlows[$flow->module] = $flow;
            $this->lang->navGroup->{$flow->module} = $flow->module;
        }
        $this->mergePrimaryLang($primaryFlows);
    }

    /**
     * Merge primary lang.
     *
     * @param  array  $primaryFlows
     * @access public
     * @return void
     */
    public function mergePrimaryLang($primaryFlows)
    {
        $tab = $this->app->tab;

        $this->sortFlows($primaryFlows, 'primary');
        foreach($primaryFlows as $flow)
        {
            if(!isset($this->lang->{$flow->module})) $this->lang->{$flow->module} = new stdclass();
            $this->lang->{$flow->module}->common     = $flow->name;
            $this->lang->mainNav->{$flow->module}    = "<i class='icon icon-{$flow->icon}'></i> {$flow->name}|{$flow->module}|browse|";
            $this->lang->navIconNames[$flow->module] = $flow->icon;
            if($tab == $flow->module)
            {
                if(!isset($this->lang->{$flow->module}))       $this->lang->{$flow->module} = new stdclass();
                if(!isset($this->lang->{$flow->module}->menu)) $this->lang->{$flow->module}->menu = new stdclass();
                $this->lang->{$flow->module}->menu->{$flow->module} = array('link' => "{$flow->name}|{$flow->module}|browse|");
                $this->lang->{$flow->module}->menuOrder[5]          = $flow->module;
            }
        }
    }

    /**
     * Merge menu lang from flow.
     *
     * @access public
     * @return void
     */
    public function mergeFlowMenuLang()
    {
        if(!$this->app->isServing()) return;
        if(!$this->config->db->name) return;

        $this->app->setOpenApp();

        $groupID = $this->loadModel('workflowgroup')->getGroupIDBySession('', false);
        $group   = $this->workflowgroup->getByID($groupID);
        $disabledModules = $group ? $group->disabledModules : '';

        $tab   = $this->app->tab;
        $flows = $this->dao->select('*')->from(TABLE_WORKFLOW)->where('buildin')->eq('0')
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('status')->eq('normal')
            ->andWhere('group')->eq('0')
            ->andWhere('type')->eq('flow')
            ->beginIF($disabledModules)->andWhere('module')->notin($disabledModules)->fi()
            ->orderBy('navigator_asc')
            ->fetchAll('id');

        $primaryFlows   = array();
        $secondaryFlows = array();
        foreach($flows as $flow)
        {
            if($flow->navigator == 'primary')
            {
                $primaryFlows[$flow->module] = $flow;
                $this->lang->navGroup->{$flow->module} = $flow->module;
            }
            elseif($flow->navigator == 'secondary')
            {
                $flowApp = $flow->app;
                if($flowApp == 'scrum' or $flowApp == 'waterfall') $flowApp = 'project';
                $this->lang->navGroup->{$flow->module} = $flowApp;
                if($tab == 'project')
                {
                    $project = $this->dao->select('id,model')->from(TABLE_PROJECT)->where('id')->eq((int)$this->session->project)->fetch();
                    if($project)
                    {
                        if($flow->app == 'scrum'     && $project->model != 'scrum'     && $project->model != 'agileplus')     continue;
                        if($flow->app == 'waterfall' && $project->model != 'waterfall' && $project->model != 'waterfallplus' && $project->model != 'ipd') continue;
                    }
                }

                if($flow->navigator == 'secondary' and ($flowApp == $tab or $tab == 'workflow')) $secondaryFlows[$flow->module] = $flow;
            }
        }

        $this->mergePrimaryLang($primaryFlows);

        $this->sortFlows($secondaryFlows, 'secondary');
        foreach($secondaryFlows as $flow)
        {
            $flowApp = $flow->app;
            if($flowApp == 'scrum' or $flowApp == 'waterfall') $flowApp = 'project';
            if(!isset($this->lang->{$flow->module})) $this->lang->{$flow->module} = new stdclass();

            $this->lang->{$flow->module}->common = $flow->name;
            if(!isset($this->lang->{$flowApp})) $this->lang->{$flowApp} = new stdclass();
            if(!isset($this->lang->{$flowApp}->menu)) $this->lang->{$flowApp}->menu = new stdclass();
            if(strpos($flow->position, '|') === false) $this->lang->{$flowApp}->menu->{$flow->module} = array('link' => "{$flow->name}|{$flow->module}|browse|");
        }
    }

    public function setMenuForFlow($tab = '', $objectID = 0)
    {
        if(empty($tab)) $tab = $this->app->tab;
        $moduleName = $this->app->rawModule;
        if($tab == 'program' && $moduleName != 'charter')
        {
            $programID = !empty($objectID) ? $objectID : (int)$this->session->program;
            $programID = $this->loadModel('program')->checkAccess($programID, $this->program->getPairs());
            $this->loadModel('program')->setMenu($programID);
        }
        if($tab == 'execution')
        {
            $executionID = !empty($objectID) ? $objectID : (int)$this->session->execution;
            $executionID = $this->loadModel('execution')->checkAccess($executionID, $this->execution->getPairs(0, 'all', "nocode,noprefix,multiple"));
            $this->loadModel('execution')->setMenu($executionID);
        }
        if($tab == 'product' or $tab == 'qa')
        {
            $productID = !empty($objectID) ? $objectID : (int)$this->session->product;
            $productID = $this->loadModel('product')->checkAccess($productID, $this->product->getPairs('nocode'));
            $branch    = (int)$this->cookie->preBranch;
            if($tab == 'product') $this->product->setMenu($productID, $branch, 0);
            if($tab == 'qa') $this->loadModel('qa')->setMenu($productID, $branch);
        }
        elseif($tab == 'project')
        {
            $projectID = !empty($objectID) ? $objectID : (int)$this->session->project;
            $projectID = $this->loadModel('project')->checkAccess($projectID, $this->project->getPairsByProgram());
            $this->project->setMenu($projectID);
        }
    }

    public function sortFlows($flows, $navigator = 'primary')
    {
        $tab       = $this->app->tab;
        $menuOrder = array();
        if($navigator == 'primary')   $menuOrder = $this->lang->mainNav->menuOrder;
        if($navigator == 'secondary') $menuOrder = isset($this->lang->{$tab}->menuOrder) ? $this->lang->{$tab}->menuOrder : array();
        if(empty($menuOrder)) return true;

        foreach($flows as $module => $flow)
        {
            if($flow->app != $tab) continue;
            $menuOrder[] = $flow->module;
        }

        $menuOrderFlip = array_flip($menuOrder);

        foreach($flows as $flow) $menuOrderFlip = $this->computeMenuOrder($flow, $menuOrderFlip, $flows);

        $menuOrder = $this->flipMenuOrder($menuOrderFlip);
        if($navigator == 'primary')   $this->lang->mainNav->menuOrder = $menuOrder;
        if($navigator == 'secondary') $this->lang->{$tab}->menuOrder  = $menuOrder;

        return true;
    }

    public function computeMenuOrder($flow, $menuOrderFlip, $flows)
    {
        static $computedModules = array();
        if(isset($computedModules[$flow->module])) return $menuOrderFlip;

        if(strpos($flow->position, 'before') === 0 or strpos($flow->position, 'after') === 0)
        {
            $computedModules[$flow->module] = true;

            $mode   = strpos($flow->position, 'before') === 0 ? 'before' : 'after';
            $module = substr($flow->position, strlen($mode));

            if(strpos($module, '|') !== false)
            {
                $this->mergeDropMenus($flow, $module, $mode);
                return $menuOrderFlip;
            }

            if(isset($flows[$module]) and $module != $flow->module and !isset($computedModules[$module])) $menuOrderFlip = $this->computeMenuOrder($flows[$module], $menuOrderFlip, $flows);

            if(isset($menuOrderFlip[$module]))
            {
                $order = $menuOrderFlip[$module];
                $step  = (is_numeric($order) and strpos($order, '.') !== false) ? '0.01' : '0.1';
                $order = $mode == 'before' ? (string)($order - $step) : (string)($order + $step);
                $menuOrderFlip[$flow->module] = $order;
            }
        }

        return $menuOrderFlip;
    }

    public function mergeDropMenus($flow, $module, $mode)
    {
        global $app;

        list($module, $dropMenu) = explode('|', $module);
        if(!isset($this->lang->{$flow->app}->menu->$module['dropMenu'])) return;

        $dropMenus = new stdclass();
        foreach($this->lang->{$flow->app}->menu->$module['dropMenu'] as $menuKey => $menuValue)
        {
            if($menuKey == $dropMenu)
            {
                if($mode == 'before') $dropMenus->{'flow' . $flow->id} = array('link' => "{$flow->name}|{$flow->module}|browse|", 'subModule' => $flow->module, 'data-app' => $app->tab);
                $dropMenus->{$menuKey} = $menuValue;
                if($mode == 'after')  $dropMenus->{'flow' . $flow->id} = array('link' => "{$flow->name}|{$flow->module}|browse|", 'subModule' => $flow->module, 'data-app' => $app->tab);
            }
            else
            {
                $dropMenus->{$menuKey} = $menuValue;
            }
        }
        $this->lang->{$flow->app}->menu->$module['dropMenu'] = $dropMenus;

        if($flow->app == 'scrum')
        {
            $dropMenus = new stdclass();
            foreach($this->lang->project->noMultiple->scrum->menu->$module['dropMenu'] as $menuKey => $menuValue)
            {
                if($menuKey == $dropMenu)
                {
                    if($mode == 'before') $dropMenus->{'flow' . $flow->id} = array('link' => "{$flow->name}|{$flow->module}|browse|", 'subModule' => $flow->module, 'data-app' => $app->tab);
                    $dropMenus->{$menuKey} = $menuValue;
                    if($mode == 'after')  $dropMenus->{'flow' . $flow->id} = array('link' => "{$flow->name}|{$flow->module}|browse|", 'subModule' => $flow->module, 'data-app' => $app->tab);
                }
                else
                {
                    $dropMenus->{$menuKey} = $menuValue;
                }
            }
            $this->lang->project->noMultiple->scrum->menu->$module['dropMenu'] = $dropMenus;
        }
    }

    /**
     * Flip menuOrder
     *
     * @param  array    $orderFlip
     * @access public
     * @return array
     */
    public function flipMenuOrder($orderFlip)
    {
        asort($orderFlip);
        $orders = array();
        $index  = 0;
        foreach($orderFlip as $moduleName => $order)
        {
            $index += 5;
            $orders[$index] = $moduleName;
        }
        return $orders;
    }

    /**
     * 按照模块生成详情页的操作按钮。
     * Build operate actions menu.
     *
     * @param  object $data
     * @param  string $moduleName
     * @access public
     * @return array
     */
    public function buildOperateMenu($data, $moduleName = '')
    {
        global $app, $config;
        $this->loadModel('flow');
        $module  = $moduleName ? $moduleName : $app->moduleName;
        $method  = $app->rawMethod;
        $flow    = $this->loadModel('workflow')->getByModule($module);
        $groupID = $this->loadModel('workflowgroup')->getGroupIDByData($module, $data);
        $enables = array();
        if($flow)
        {
            $actions = $this->loadModel('workflowaction')->getList($module, 'status_desc,order_asc', $groupID);
            foreach($actions as $action)
            {
                $enables[$action->action] = $action->status == 'enable';
                if($action->status == 'enable' && $action->extensionType != 'none' && !empty($action->conditions)) $enables[$action->action] = $this->flow->checkConditions($action->conditions, $data);
                if($action->status == 'enable' && $action->extensionType != 'none' && isset($config->{$module}->actionList[$action->action]))
                {
                    $config->{$module}->actionList[$action->action]['text'] = $action->name;
                    $config->{$module}->actionList[$action->action]['hint'] = $action->name;
                }
            }
        }

        if($method == 'storyview' && $module == 'story') $method = 'view';
        if(!empty($config->{$module}->actions->{$method}))
        {
            foreach($config->{$module}->actions->{$method} as $menu => $actionList)
            {
                $config->{$module}->actions->{$method}[$menu] = array_values(array_filter(array_map(function($action) use($enables){return zget($enables, $action, true) ? $action : null;}, $actionList)));
            }
        }

        $actionsMenu = parent::buildOperateMenu($data, $moduleName);

        if(!$flow) return $actionsMenu;
        $this->loadModel('approval');
        foreach($actions as $action)
        {
            if($action->buildin == '0' && strpos($action->position, 'view') !== false && $action->status == 'enable')
            {
                if(!zget($enables, $action->action, true)) continue;

                $moduleName = $module;
                $methodName = $action->action;
                if(strpos($methodName, '_') !== false && strpos($methodName, '_') > 0) list($moduleName, $methodName) = explode('_', $methodName);

                if($action->action == 'approvalsubmit' && !in_array($data->reviewStatus, array('', 'wait', 'reject', 'reverting'))) continue;
                if($action->action == 'approvalreview' && !$this->approval->canApproval($data)) continue;
                if($action->action == 'approvalcancel' && !$this->approval->canCancel($data))   continue;

                if(!commonModel::hasPriv($moduleName, $methodName, $data)) continue;

                $actionData = array();
                $actionData['hint']      = $actionData['text'] = $action->name;
                $actionData['data-app']  = $app->tab;
                $actionData['className'] = 'ghost' . ($action->open == 'none' ? 'ajax-submit' : '');
                $actionData['url']       = helper::createLink($moduleName, $methodName, $action->virtual ? ('step=form&prevModule=' . $module . '&dataID={id}') : ($module . '={id}'));
                if($action->open == 'modal') $actionData['data-toggle'] = 'modal';

                $actionsMenu['mainActions'][] = $actionData;
            }
        }

        if(!empty($this->config->openedApproval) && $flow->approval == 'enabled' && commonModel::hasPriv('approval', 'progress') && !empty($data->approval) && $flow->module != 'charter')
        {
            $actionsMenu['mainActions'][] = array('text' => $this->lang->flow->approvalProgress, 'hint' => $this->lang->flow->approvalProgress, 'url' => helper::createLink('approval', 'progress', "approvalID={$data->approval}"), 'data-toggle' => 'modal', 'data-id' => 'progressModal');
        }
        return $actionsMenu;
    }
}
