<?php
helper::import(dirname(__FILE__) . DS . 'bizext.class.php');
class zentaobizUpgrade extends bizextUpgrade
{
    /**
     * Adjust feedback view data.
     *
     * @access public
     * @return bool
     */
    public function adjustFeedbackViewData()
    {
        $this->saveLogs('Run Method ' . __FUNCTION__);
        $desc = $this->dao->query('DESC ' . TABLE_FEEDBACKVIEW)->fetchAll();
        $this->saveLogs($this->dao->get());
        $hasProductsField = false;
        foreach($desc as $field)
        {
            if($field->Field == 'products') $hasProductsField = true;
        }
        if(!$hasProductsField) return true;

        $feedbackView = $this->dao->select('account, products')->from(TABLE_FEEDBACKVIEW)->fetchPairs();
        $this->dao->delete()->from(TABLE_FEEDBACKVIEW)->exec();
        $this->saveLogs($this->dao->get());
        foreach($feedbackView as $account => $products)
        {
            if(empty($products)) continue;

            foreach(explode(',', $products) as $productID)
            {
                $productID = trim($productID);
                if(empty($productID)) continue;

                $view = new stdclass();
                $view->account = $account;
                $view->product = $productID;
                $this->dao->delete()->from(TABLE_FEEDBACKVIEW)->where('account')->eq($account)->andWhere('product')->eq($productID)->exec();
                $this->dao->insert(TABLE_FEEDBACKVIEW)->data($view)->exec();
                $this->saveLogs($this->dao->get());
            }
        }
        $this->dao->exec("ALTER TABLE " . TABLE_FEEDBACKVIEW . " DROP `products`");
        $this->saveLogs($this->dao->get());
        return true;
    }

    /**
     * Import zentao module for workflow.
     *
     * @param  string $vision
     * @access public
     * @return void
     */
    public function importBuildinModules($vision = 'all')
    {
        $this->saveLogs('Run Method ' . __FUNCTION__);
        $this->loadModel('workflow');
        $this->loadModel('workflowaction');
        $this->loadModel('workflowfield');

        $modules         = $this->config->workflow->buildin->modules;
        $visions         = $this->config->workflow->buildin->visions;
        $actions         = $this->config->workflowaction->buildin->actions;
        $actionTypes     = $this->config->workflowaction->buildin->types;
        $actionMethods   = $this->config->workflowaction->buildin->methods;
        $actionOpens     = $this->config->workflowaction->buildin->opens;
        $actionLayouts   = $this->config->workflowaction->buildin->layouts;
        $actionPositions = $this->config->workflowaction->buildin->positions;
        $actionShows     = $this->config->workflowaction->buildin->shows;
        $fields          = $this->config->workflowfield->buildin->fields;

        $account = isset($this->app->user->account) ? $this->app->user->account : 'admin';
        $now     = helper::now();

        /* Insert buildin modules to TABLE_WORKFLOW. */
        $data = new stdclass();
        $data->buildin     = 1;
        $data->createdBy   = $account;
        $data->createdDate = $now;
        $data->status      = 'normal';
        foreach($modules as $app => $appModules)
        {
            $data->app = $app;
            foreach($appModules as $module => $options)
            {
                $this->app->loadLang($module);

                $data->vision    = 'rnd';
                $data->module    = $module;
                $data->name      = isset($this->lang->$module->common) ? $this->lang->$module->common : $module;
                $data->table     = str_replace('`', '', zget($options, 'table', ''));
                $data->navigator = zget($options, 'navigator', 'secondary');

                if(!empty($visions[$module]))  $data->vision = $visions[$module];
                if($module == 'execution')     $data->name   = $this->lang->workflow->execution;
                if($module == 'story')         $data->name   = $this->lang->SRCommon;
                if($module == 'epic')          $data->name   = $this->lang->ERCommon;
                if($module == 'requirement')   $data->name   = $this->lang->URCommon;

                if($vision != 'all' && $vision != $data->vision) continue;

                $this->dao->delete()->from(TABLE_WORKFLOW)->where('app')->eq($app)->andWhere('module')->eq($module)->andWhere('vision')->eq($data->vision)->exec();
                $this->dao->insert(TABLE_WORKFLOW)->data($data)->exec();
            }
        }

        /* Insert actions of buildin modules to TABLE_WORKFLOWACTION. */
        $data = new stdclass();
        $data->buildin       = 1;
        $data->role          = 'buildin';
        $data->extensionType = 'none';
        $data->createdBy     = $account;
        $data->createdDate   = $now;
        foreach($actions as $module => $moduleActions)
        {
            $data->module = $module;
            foreach($moduleActions as $action)
            {
                $data->action = $action;

                /* Use default action name if not set flow action name. */
                $name = '';
                if(isset($this->lang->$module->$action)) $name = $this->lang->$module->$action;
                if($data->module == 'execution' && $action == 'all') $name = $this->lang->execution->allExecutionAB;
                if(empty($name) && in_array($action, array_keys($this->config->flowAction)))
                {
                    $methodName = $this->config->flowAction[$action];
                    if(isset($this->lang->$module->$methodName)) $name = $this->lang->$module->$methodName;
                }
                if(empty($name) && isset($this->lang->workflowaction->default->actions[$action])) $name = $this->lang->workflowaction->default->actions[$action];
                if(empty($name)) $name = $action;

                $data->name     = $name;
                $data->method   = $data->action;
                $data->open     = 'normal';
                $data->layout   = 'normal';
                $data->type     = 'single';
                $data->position = 'browseandview';
                $data->show     = 'direct';
                $data->vision   = 'rnd';
                if(isset($actionMethods[$module][$action]))   $data->method   = $actionMethods[$module][$action];
                if(isset($actionOpens[$module][$action]))     $data->open     = $actionOpens[$module][$action];
                if(isset($actionLayouts[$module][$action]))   $data->layout   = $actionLayouts[$module][$action];
                if(isset($actionTypes[$module][$action]))     $data->type     = $actionTypes[$module][$action];
                if(isset($actionPositions[$module][$action])) $data->position = $actionPositions[$module][$action];
                if(isset($actionShows[$module][$action]))     $data->show     = $actionShows[$module][$action];
                if(!empty($visions[$module]))                 $data->vision   = $visions[$module];

                if($vision != 'all' && $vision != $data->vision) continue;

                $this->dao->delete()->from(TABLE_WORKFLOWACTION)->where('module')->eq($module)->andWhere('action')->eq($action)->andWhere('vision')->eq($data->vision)->exec();
                $this->dao->insert(TABLE_WORKFLOWACTION)->data($data)->exec();
            }
        }

        $this->dao->delete()->from(TABLE_WORKFLOWACTION)->where('module')->eq('feedback')->andWhere('action')->eq('view')->andWhere('vision')->eq('rnd')->exec();

        /* Insert fields of buildin modules to TABLE_WORKFLOWFIELD. */
        $data = new stdclass();
        $data->role        = 'buildin';
        $data->createdBy   = $account;
        $data->createdDate = $now;
        foreach($fields as $module => $moduleFields)
        {
            $order = 1;
            $data->module = $module;

            $currentVision = 'rnd';
            if(!empty($visions[$module])) $currentVision = $visions[$module];
            if($vision != 'all' && $vision != $currentVision) continue;

            foreach($moduleFields as $field => $options)
            {
                $data->field    = $field;
                $data->name     = isset($this->lang->$module->$field) ? $this->lang->$module->$field : $field;
                $data->type     = zget($options, 'type', 'varchar');
                $data->length   = zget($options, 'length', '');
                $data->control  = zget($options, 'control', 'input');
                $data->options  = zget($options, 'options', '[]');
                $data->default  = zget($options, 'default', '');
                $data->buildin  = zget($options, 'buildin', 1);
                $data->order    = $order++;
                $data->readonly = ($field == 'subStatus') ? '0' : '1';
                $data->rules    = zget($options, 'rules', '');

                if($module == 'execution')
                {
                    $execField = 'exec' . ucfirst($field);
                    if(isset($this->lang->$module->$execField)) $data->name = $this->lang->$module->$execField;
                }

                if(is_object($data->options) or is_array($data->options)) $data->options = helper::jsonEncode($data->options);

                $this->dao->delete()->from(TABLE_WORKFLOWFIELD)->where('module')->eq($module)->andWhere('field')->eq($field)->exec();
                $this->dao->insert(TABLE_WORKFLOWFIELD)->data($data)->exec();
            }
        }

        /* Insert labels of buildin modules to TABLE_WORKFLOWLABEL. */
        $data = new stdclass();
        $data->buildin     = 1;
        $data->role        = 'buildin';
        $data->params      = '[]';
        $data->createdBy   = $account;
        $data->createdDate = $now;
        foreach($modules as $app => $appModules)
        {
            foreach($appModules as $module => $options)
            {
                $labels = array();
                if($module == 'product')
                {
                    if(isset($this->lang->product->featureBar['all'])) $labels = $this->lang->product->featureBar['all'];
                }
                elseif($module == 'story')
                {
                    if(isset($this->lang->product->featureBar['browse'])) $labels = $this->lang->product->featureBar['browse'];
                }
                elseif($module == 'execution')
                {
                    if(isset($this->lang->execution->featureBar['all'])) $labels = $this->lang->execution->featureBar['all'];
                }
                elseif($module == 'task')
                {
                    if(isset($this->lang->execution->featureBar['task'])) $labels = $this->lang->execution->featureBar['task'];
                }
                elseif($module == 'bug')
                {
                    if(isset($this->lang->bug->featureBar['browse'])) $labels = $this->lang->bug->featureBar['browse'];
                }
                elseif($module == 'feedback')
                {
                    if(isset($this->lang->feedback->menu) && (is_object($this->lang->feedback->menu) || is_array($this->lang->feedback->menu)))
                    {
                        foreach($this->lang->feedback->menu as $key => $menuItem)
                        {
                            $menus = explode('|', zget($menuItem, 'link', $menuItem));
                            $labels[$key] = zget($menus, 0, $menuItem);
                        }
                    }
                }
                else
                {
                    if(isset($this->lang->$module->featureBar['browse'])) $labels = $this->lang->$module->featureBar['browse'];
                }

                $order = 1;
                $data->module = $module;

                $currentVision = 'rnd';
                if(!empty($visions[$module])) $currentVision = $visions[$module];
                if($vision != 'all' && $vision != $currentVision) continue;

                foreach($labels as $key => $label)
                {
                    $data->code  = $key;
                    $data->label = trim(strip_tags($label));
                    $data->order = $order++;

                    $this->dao->delete()->from(TABLE_WORKFLOWLABEL)->where('module')->eq($module)->andWhere('code')->eq($key)->exec();
                    $this->dao->insert(TABLE_WORKFLOWLABEL)->data($data)->exec();
                }
            }
        }
        if($vision == 'all') $this->importLiteModules();
    }

    /**
     * Import lite modules.
     *
     * @access public
     * @return void
     */
    public function importLiteModules()
    {
        $this->loadModel('workflow');
        $this->loadModel('workflowaction');
        $this->loadModel('workflowlayout');

        $liteModules = $this->config->workflow->buildin->liteModules;
        foreach($liteModules as $moduleName)
        {
            $workflow = $this->dao->select('*')->from(TABLE_WORKFLOW)->where('module')->eq($moduleName)->fetch();
            if(empty($workflow)) continue;

            unset($workflow->id, $workflow->editedBy, $workflow->editedDate);
            $workflow->vision = 'lite';
            if($workflow->module == 'task') $workflow->app = 'project';
            $this->dao->delete()->from(TABLE_WORKFLOW)->where('app')->eq($workflow->app)->andWhere('module')->eq($workflow->module)->andWhere('vision')->eq('lite')->exec();
            $this->dao->insert(TABLE_WORKFLOW)->data($workflow)->exec();

            $workflowActions = $this->dao->select('*')->from(TABLE_WORKFLOWACTION)->where('module')->eq($moduleName)->fetchAll('id', false);
            foreach($workflowActions as $workflowAction)
            {
                unset($workflowAction->id);
                $workflowAction->vision = 'lite';
                $this->dao->delete()->from(TABLE_WORKFLOWACTION)->where('module')->eq($workflowAction->module)->andWhere('action')->eq($workflowAction->action)->andWhere('vision')->eq('lite')->exec();

                if(empty($workflowAction->editeDate)) unset($workflowAction->editedDate);
                $this->dao->insert(TABLE_WORKFLOWACTION)->data($workflowAction)->exec();
            }

            $this->dao->delete()->from(TABLE_WORKFLOWACTION)->where('module')->eq('feedback')->andWhere('action')->eq('adminView')->andWhere('vision')->eq('lite')->exec();

            $workflowLayouts = $this->dao->select('*')->from(TABLE_WORKFLOWLAYOUT)
                ->where('module')->eq($moduleName)
                ->fetchAll('id', false);
            foreach($workflowLayouts as $workflowLayout)
            {
                unset($workflowLayout->id);
                $workflowLayout->vision = 'lite';
                $this->dao->delete()->from(TABLE_WORKFLOWLAYOUT)->where('module')->eq($workflowLayout->module)->andWhere('action')->eq($workflowLayout->action)->andWhere('field')->eq($workflowLayout->field)->andWhere('vision')->eq('lite')->exec();
                $this->dao->insert(TABLE_WORKFLOWLAYOUT)->data($workflowLayout)->exec();
            }
        }
    }

    /**
     * Add sub status for built-in modules.
     *
     * @access public
     * @return bool
     */
    public function addSubStatus()
    {
        $this->saveLogs('Run Method ' . __FUNCTION__);
        $this->app->loadModuleConfig('workflow');
        $modules = $this->config->workflow->buildin->subStatus->modules;

        $statusOrders = $this->dao->select('module, `order`')->from(TABLE_WORKFLOWFIELD)
            ->where('field')->eq('status')
            ->andWhere('module')->in($modules)
            ->fetchPairs();

        $field = new stdclass();
        $field->field    = 'subStatus';
        $field->type     = 'varchar';
        $field->length   = 30;
        $field->control  = 'select';
        $field->buildin  = 0;
        $field->role     = 'buildin';
        $field->options  = '[]';

        foreach($modules as $module)
        {
            $this->app->loadLang($module);

            $order = $statusOrders[$module] + 1;

            $field->module      = $module;
            $field->name        = $this->lang->$module->subStatus;
            $field->order       = $order;
            $field->createdBy   = isset($this->app->user->account) ? $this->app->user->account : 'admin';
            $field->createdDate = helper::now();

            $this->dao->delete()->from(TABLE_WORKFLOWFIELD)->where('module')->eq($module)->andWhere('field')->eq('subStatus')->exec();
            $this->dao->insert(TABLE_WORKFLOWFIELD)->data($field)->exec();
            $this->dao->update(TABLE_WORKFLOWFIELD)->set('`order` = `order` + 1')
                ->where('module')->eq($module)
                ->andWhere('`order`')->gt($order)
                ->exec();
        }

        return !dao::isError();
    }

    /**
     * Add batch create action and batch edit action for exist flows.
     *
     * @access public
     * @return bool
     */
    public function addDefaultActions()
    {
        $this->saveLogs('Run Method ' . __FUNCTION__);
        $this->loadModel('workflowaction');

        $actionLang     = $this->lang->workflowaction->default;
        $actionConfig   = $this->config->workflowaction->default;
        $defaultActions = $this->config->workflowaction->defaultActions;
        $flows          = $this->dao->select('module,buildin')->from(TABLE_WORKFLOW)->where('type')->eq('flow')->fetchPairs('module', 'buildin');

        $action = new stdclass();
        $action->show        = 'direct';
        $action->createdBy   = isset($this->app->user->account) ? $this->app->user->account : 'admin';
        $action->createdDate = helper::now();

        foreach($flows as $module => $buildin)
        {
            if($buildin) continue;

            $action->module = $module;
            foreach($defaultActions as $actionCode)
            {
                $action->action    = $actionCode;
                $action->name      = $actionLang->actions[$actionCode];
                $action->type      = $actionConfig->types[$actionCode];
                $action->batchMode = $actionConfig->batchModes[$actionCode];
                $action->open      = $actionConfig->opens[$actionCode];
                $action->position  = $actionConfig->positions[$actionCode];

                $this->dao->delete()->from(TABLE_WORKFLOWACTION)->where('module')->eq($module)->andWhere('action')->eq($actionCode)->andWhere('vision')->eq('rnd')->exec();
                $this->dao->insert(TABLE_WORKFLOWACTION)->data($action)->autoCheck()->exec();
            }
        }

        return !dao::isError();
    }

    /**
     * process sub tables.
     *
     * @access public
     * @return void
     */
    public function processSubTables()
    {
        $this->saveLogs('Run Method ' . __FUNCTION__);
        $subTables  = $this->dao->select('parent, module')->from(TABLE_WORKFLOW)
            ->where('parent')->ne('')
            ->andWhere('type')->eq('table')
            ->fetchPairs();

        foreach($subTables as $parent => $module)
        {
            $this->dao->update(TABLE_WORKFLOWLAYOUT)
                ->set('field')->eq('sub_' . $module)
                ->where('module')->eq($parent)
                ->andWhere('field')->eq($module)
                ->exec();
        }

        return !dao::isError();
    }

    /**
     * Import caselib module for workflow.
     *
     * @access public
     * @return void
     */
    public function importCaseLibModule()
    {
        $this->saveLogs('Run Method ' . __FUNCTION__);
        $this->loadModel('workflow');
        $this->loadModel('workflowaction');
        $this->loadModel('workflowfield');

        $this->dao->delete()->from(TABLE_WORKFLOW)->where('module')->eq('caselib')->exec();
        $this->dao->delete()->from(TABLE_WORKFLOWACTION)->where('module')->eq('caselib')->exec();
        $this->dao->delete()->from(TABLE_WORKFLOWFIELD)->where('module')->eq('caselib')->exec();

        $caselibOptions = $this->config->workflow->buildin->modules->qa->caselib;
        $actions        = $this->config->workflowaction->buildin->actions['caselib'];
        $actionOpens    = $this->config->workflowaction->buildin->opens['caselib'];
        $fields         = $this->config->workflowfield->buildin->fields['caselib'];

        $account = isset($this->app->user->account) ? $this->app->user->account : 'admin';
        $now     = helper::now();

        /* Insert buildin modules to TABLE_WORKFLOW. */
        $this->app->loadLang('caselib');
        $data = new stdclass();
        $data->buildin     = 1;
        $data->vision      = 'rnd';
        $data->createdBy   = $account;
        $data->createdDate = $now;
        $data->app         = 'qa';
        $data->module      = 'caselib';
        $data->status      = 'normal';
        $data->name        = isset($this->lang->caselib->common) ? $this->lang->caselib->common : 'caselib';
        $data->table       = str_replace('`', '', zget($caselibOptions, 'table', ''));
        $data->navigator   = zget($caselibOptions, 'navigator', 'secondary');
        $this->dao->insert(TABLE_WORKFLOW)->data($data)->exec();

        /* Insert actions of buildin modules to TABLE_WORKFLOWACTION. */
        $data = new stdclass();
        $data->buildin       = 1;
        $data->extensionType = 'none';
        $data->createdBy     = $account;
        $data->createdDate   = $now;
        $data->module        = 'caselib';
        $data->role          = 'buildin';
        foreach($actions as $action)
        {
            $data->action = $action;
            $data->name   = isset($this->lang->caselib->$action) ? $this->lang->caselib->$action : $action;
            $data->open   = isset($actionOpens['caselib'][$action]) ? $actionOpens['caselib'][$action] : 'normal';
            $data->method = isset($this->config->workflowaction->buildin->methods['caselib'][$action]) ? $this->config->workflowaction->buildin->methods['caselib'][$action] : $action;
            $data->layout = 'normal';

            $this->dao->insert(TABLE_WORKFLOWACTION)->data($data)->exec();
        }

        /* Insert fields of buildin modules to TABLE_WORKFLOWFIELD. */
        $data = new stdclass();
        $data->createdBy   = $account;
        $data->createdDate = $now;
        $data->module      = 'caselib';

        $order = 1;
        foreach($fields as $field => $options)
        {
            $data->field    = $field;
            $data->name     = isset($this->lang->caselib->$field) ? $this->lang->caselib->$field : $field;
            $data->type     = zget($options, 'type', 'varchar');
            $data->length   = zget($options, 'length', '');
            $data->control  = zget($options, 'control', 'input');
            $data->options  = zget($options, 'options', '[]');
            $data->default  = zget($options, 'default', '');
            $data->buildin  = zget($options, 'buildin', 1);
            $data->order    = $order++;
            $data->role     = 'buildin';
            $data->readonly = ($field == 'subStatus') ? '0' : '1';

            if(is_object($data->options) or is_array($data->options)) $data->options = helper::jsonEncode($data->options);

            $this->dao->insert(TABLE_WORKFLOWFIELD)->data($data)->exec();
        }
    }

    /**
     * Import epic and requirement modules for workflow.
     *
     * @access public
     * @return void
     */
    public function importERURModules()
    {
        $this->saveLogs('Run Method ' . __FUNCTION__);
        $this->loadModel('workflow');
        $this->loadModel('workflowaction');
        $this->loadModel('workflowfield');
        $this->loadModel('workflowlayout');

        $this->dao->delete()->from(TABLE_WORKFLOW)->where('module')->in('requirement, epic')->exec();
        $this->dao->delete()->from(TABLE_WORKFLOWACTION)->where('module')->in('requirement, epic')->exec();
        $this->dao->delete()->from(TABLE_WORKFLOWFIELD)->where('module')->in('requirement, epic')->exec();
        $this->dao->delete()->from(TABLE_WORKFLOWLAYOUT)->where('module')->in('requirement, epic')->exec();

        $account = isset($this->app->user->account) ? $this->app->user->account : 'admin';
        $now     = helper::now();

        /* Insert buildin modules to TABLE_WORKFLOW. */
        $this->app->loadLang('story');
        foreach(array('requirement', 'epic') as $module)
        {
            $this->app->loadLang($module);

            $data = new stdclass();
            $data->buildin     = 1;
            $data->vision      = 'rnd';
            $data->createdBy   = $account;
            $data->createdDate = $now;
            $data->app         = 'product';
            $data->module      = $module;
            $data->name        = $module == 'epic' ? $this->lang->ERCommon : $this->lang->URCommon;
            $data->table       = str_replace('`', '', TABLE_STORY);
            $data->navigator   = 'secondary';
            $data->status      = 'normal';
            $this->dao->insert(TABLE_WORKFLOW)->data($data)->exec();

            /*用户需求直接用软件需求的工作流，业务需求用配置里的。 */
            if($module == 'requirement')
            {
                $actions = $this->dao->select('*')->from(TABLE_WORKFLOWACTION)->where('module')->eq('story')->fetchAll('id', false);
                foreach($actions as $action)
                {
                    unset($action->id);
                    $action->module = 'requirement';
                    $action->name   = str_replace($this->lang->story->common, $this->lang->URCommon, $action->name);
                    $this->dao->insert(TABLE_WORKFLOWACTION)->data($action)->exec();
                }

                $fields = $this->dao->select('*')->from(TABLE_WORKFLOWFIELD)->where('module')->eq('story')->fetchAll('id', false);
                foreach($fields as $field)
                {
                    unset($field->id);
                    $field->module = 'requirement';
                    $field->name   = str_replace($this->lang->story->common, $this->lang->URCommon, $field->name);
                    $this->dao->insert(TABLE_WORKFLOWFIELD)->data($field)->exec();
                }

                $layouts = $this->dao->select('*')->from(TABLE_WORKFLOWLAYOUT)->where('module')->eq('story')->fetchAll('id', false);
                foreach($layouts as $layout)
                {
                    unset($layout->id);
                    $layout->module = 'requirement';
                    $this->dao->insert(TABLE_WORKFLOWLAYOUT)->data($layout)->exec();
                }
            }
            else
            {
                $actions     = $this->config->workflowaction->buildin->actions['epic'];
                $actionOpens = $this->config->workflowaction->buildin->opens['epic'];
                $fields      = $this->config->workflowfield->buildin->fields['epic'];
                $layouts     = $this->config->workflowlayout->buildin->layouts->epic;

                /* Insert actions of buildin modules to TABLE_WORKFLOWACTION. */
                $data = new stdclass();
                $data->buildin       = 1;
                $data->role          = 'buildin';
                $data->extensionType = 'none';
                $data->createdBy     = $account;
                $data->createdDate   = $now;
                $data->module        = 'epic';
                foreach($actions as $action)
                {
                    $data->action = $action;
                    $data->name   = isset($this->lang->epic->$action) ? $this->lang->epic->$action : $action;
                    $data->open   = isset($actionOpens['epic'][$action]) ? $actionOpens['epic'][$action] : 'normal';
                    $data->layout = 'normal';

                    $this->dao->insert(TABLE_WORKFLOWACTION)->data($data)->exec();
                }

                /* Insert fields of buildin modules to TABLE_WORKFLOWFIELD. */
                $data = new stdclass();
                $data->createdBy   = $account;
                $data->createdDate = $now;
                $data->module      = 'epic';

                $order = 1;
                foreach($fields as $field => $options)
                {
                    $data->field    = $field;
                    $data->role     = 'buildin';
                    $data->name     = isset($this->lang->epic->field) ? $this->lang->epic->$field : $field;
                    $data->type     = zget($options, 'type', 'varchar');
                    $data->length   = zget($options, 'length', '');
                    $data->control  = zget($options, 'control', 'input');
                    $data->options  = zget($options, 'options', '[]');
                    $data->default  = zget($options, 'default', '');
                    $data->buildin  = zget($options, 'buildin', 1);
                    $data->order    = $order++;
                    $data->readonly = ($field == 'subStatus') ? '0' : '1';

                    if(is_object($data->options) or is_array($data->options)) $data->options = helper::jsonEncode($data->options);

                    $this->dao->insert(TABLE_WORKFLOWFIELD)->data($data)->exec();
                }

                /* Insert layouts of buildin modules to TABLE_WORKFLOWLAYOUT. */
                $data = new stdclass();
                $data->module = 'epic';
                foreach($layouts as $action => $layoutFields)
                {
                    $order = 1;
                    $data->action = $action;
                    foreach($layoutFields as $field => $options)
                    {
                        $data->field      = $field;
                        $data->width      = zget($options, 'width', 0);
                        $data->mobileShow = zget($options, 'mobileShow', 0);
                        $data->order      = $order++;

                        if($data->width == 'auto') $data->width = 0;

                        $this->dao->insert(TABLE_WORKFLOWLAYOUT)->data($data)->exec();
                    }
                }
            }
        }
    }

    /**
     * Delete buildin fields.
     *
     * @access public
     * @return void
     */
    public function deleteBuildinFields()
    {
        $this->saveLogs('Run Method ' . __FUNCTION__);
        $deleteIdList = $this->dao->select('t1.id')->from(TABLE_WORKFLOWLAYOUT)->alias('t1')
            ->leftJoin(TABLE_WORKFLOWFIELD)->alias('t2')->on('t1.field=t2.field && t1.module = t2.module')
            ->leftJoin(TABLE_WORKFLOWACTION)->alias('t3')->on('t1.action=t3.action && t1.module = t3.module')
            ->where('t2.buildin')->eq(1)
            ->andWhere('t3.extensionType')->eq('extend')
            ->fetchPairs('id', 'id');
        $this->dao->delete()->from(TABLE_WORKFLOWLAYOUT)->where('id')->in($deleteIdList)->exec();
    }

    /**
     * Add new actions for exist flows.
     *
     * @access public
     * @return bool
     */
    public function addWorkflowActions()
    {
        $this->saveLogs('Run Method ' . __FUNCTION__);
        $this->loadModel('workflowaction');

        $newActions   = array('batchcreate', 'batchedit', 'link', 'unlink', 'exporttemplate', 'import', 'showimport');
        $actionLang   = $this->lang->workflowaction->default;
        $actionConfig = $this->config->workflowaction->default;
        $flows        = $this->dao->select('module,buildin')->from(TABLE_WORKFLOW)->where('type')->eq('flow')->fetchPairs('module', 'buildin');

        $action = new stdclass();
        $action->show        = 'direct';
        $action->layout      = 'normal';
        $action->createdBy   = isset($this->app->user->account) ? $this->app->user->account : 'admin';
        $action->createdDate = helper::now();

        foreach($flows as $module => $buildin)
        {
            $action->module        = $module;
            $action->buildin       = $buildin;
            $action->extensionType = $buildin ? 'none' : 'override';

            foreach($newActions as $actionCode)
            {
                if($buildin and !in_array($actionCode, zget($this->config->workflowaction->buildin->actions, $module, array()))) continue;

                $open = $actionConfig->opens[$actionCode];
                if($buildin and isset($this->config->workflowaction->buildin->opens[$module][$actionCode])) $open = $this->config->workflowaction->buildin->opens[$module][$actionCode];

                $action->open          = $open;
                $action->action        = $actionCode;
                $action->name          = $actionLang->actions[$actionCode];
                $action->type          = $actionConfig->types[$actionCode];
                $action->batchMode     = $actionConfig->batchModes[$actionCode];
                $action->position      = $actionConfig->positions[$actionCode];

                $this->dao->delete()->from(TABLE_WORKFLOWACTION)->where('module')->eq($module)->andWhere('action')->eq($actionCode)->andWhere('vision')->eq('rnd')->exec();
                $this->dao->insert(TABLE_WORKFLOWACTION)->data($action)->autoCheck()->exec();
            }
        }

        $this->dao->update(TABLE_WORKFLOWACTION)->set('name')->eq($actionLang->actions['export'])->where('action')->eq('export')->exec();

        return !dao::isError();
    }

    /**
     * Process workflow layout.
     *
     * @access public
     * @return bool
     */
    public function processWorkflowLayout()
    {
        $this->saveLogs('Run Method ' . __FUNCTION__);
        $subTables  = $this->dao->select('parent, module')->from(TABLE_WORKFLOW)
            ->where('parent')->ne('')
            ->andWhere('type')->eq('table')
            ->fetchPairs();

        foreach($subTables as $parent => $module)
        {
            $this->dao->update(TABLE_WORKFLOWLAYOUT)
                ->set('field')->eq('sub_' . $module)
                ->where('module')->eq($parent)
                ->andWhere('field')->eq($module)
                ->exec();
        }

        return !dao::isError();
    }

    /**
     * Make sure the params of a label has the condition deleted='0'.
     *
     * @access public
     * @return bool
     */
    public function processWorkflowLabel()
    {
        $this->saveLogs('Run Method ' . __FUNCTION__);
        $labels = $this->dao->select('id, params')->from(TABLE_WORKFLOWLABEL)->where('module')->eq('test')->fetchPairs();
        foreach($labels as $id => $params)
        {
            $index   = 0;
            $deleted = false;
            $params  = json_decode($params, true);

            foreach($params as $index => $param)
            {
                if(zget($param, 'field') == 'deleted' && zget($param, 'operator') == '=' && zget($param, 'value') == '0')
                {
                    $deleted = true;
                    break;
                }
            }

            if(!$deleted)
            {
                $index++;

                $params[$index]['field']    = 'deleted';
                $params[$index]['operator'] = '=';
                $params[$index]['value']    = '0';

                $params = array_reverse($params);
            }

            $params = helper::jsonEncode($params);

            $this->dao->update(TABLE_WORKFLOWLABEL)->set('params')->eq($params)->where('id')->eq($id)->exec();
        }

        return !dao::isError();
    }

    /**
     * Process workflow conditions to array.
     *
     * @access public
     * @return bool
     */
    public function processWorkflowCondition()
    {
        $this->saveLogs('Run Method ' . __FUNCTION__);
        $actions = $this->dao->select('id, conditions')->from(TABLE_WORKFLOWACTION)->fetchPairs();

        foreach($actions as $id => $conditions)
        {
            $conditions = json_decode($conditions);
            if(!$conditions) continue;

            $conditions = helper::jsonEncode(array($conditions));

            $this->dao->update(TABLE_WORKFLOWACTION)->set('conditions')->eq($conditions)->where('id')->eq($id)->exec();
        }

        return !dao::isError();
    }

    /**
     * Process workflow fields.
     *
     * @access public
     * @return void
     */
    public function processWorkflowFields()
    {
        $sqls['id']           = "ALTER TABLE %s CHANGE `id`           `id`           mediumint(8) unsigned NOT NULL AUTO_INCREMENT";
        $sqls['parent']       = "ALTER TABLE %s CHANGE `parent`       `parent`       mediumint(8) unsigned NOT NULL";
        $sqls['assignedTo']   = "ALTER TABLE %s CHANGE `assignedTo`   `assignedTo`   varchar(30) NOT NULL";
        $sqls['status']       = "ALTER TABLE %s CHANGE `status`       `status`       varchar(30) NOT NULL";
        $sqls['subStatus']    = "ALTER TABLE %s CHANGE `subStatus`    `subStatus`    varchar(30) NOT NULL";
        $sqls['createdBy']    = "ALTER TABLE %s CHANGE `createdBy`    `createdBy`    varchar(30) NOT NULL";
        $sqls['createdDate']  = "ALTER TABLE %s CHANGE `createdDate`  `createdDate`  datetime NOT NULL";
        $sqls['editedBy']     = "ALTER TABLE %s CHANGE `editedBy`     `editedBy`     varchar(30) NOT NULL";
        $sqls['editedDate']   = "ALTER TABLE %s CHANGE `editedDate`   `editedDate`   datetime NOT NULL";
        $sqls['assignedBy']   = "ALTER TABLE %s CHANGE `assignedBy`   `assignedBy`   varchar(30) NOT NULL";
        $sqls['assignedDate'] = "ALTER TABLE %s CHANGE `assignedDate` `assignedDate` datetime NOT NULL";
        $sqls['deleted']      = "ALTER TABLE %s CHANGE `deleted`      `deleted`      enum('0', '1') NOT NULL DEFAULT '0'";

        $flows = $this->dao->select('module, `table`')->from(TABLE_WORKFLOW)
            ->where('type')->eq('flow')
            ->andWhere('buildin')->eq('0')
            ->orderBy('id_desc')
            ->fetchPairs();
        $fields = $this->dao->select('module, field, `default`')->from(TABLE_WORKFLOWFIELD)
            ->where('module')->in(array_keys($flows))
            ->fetchGroup('module', 'field');

        $magicQuote = (version_compare(phpversion(), '5.4', '<') and function_exists('get_magic_quotes_gpc') and get_magic_quotes_gpc());

        foreach($flows as $module => $table)
        {
            foreach($sqls as $field => $sql)
            {
                if(!isset($fields[$module][$field])) continue;

                $sql = sprintf($sql, $table);

                if($field == 'status' or $field == 'subStatus')
                {
                    $default = $fields[$module][$field]->default;
                    if($default)
                    {
                        if($magicQuote) $default = stripslashes($default);

                        $default = $this->dbh->quote($default);

                        $sql .= " DEFAULT {$default}";
                    }
                }

                try
                {
                    $this->dbh->query($sql);
                }
                catch(PDOException $e)
                {
                    self::$errors[] = $e->getMessage() . "<p>The sql is: $sql</p>";

                    return false;
                }
            }
        }

        return true;
    }

    public function processFlowStatus()
    {
        $this->loadModel('workflow', 'flow');
        $this->app->loadLang('workflowfield', 'flow');
        $this->app->loadModuleConfig('workflowfield', 'flow');

        $flowPairs = $this->dao->select('module')->from(TABLE_WORKFLOW)->where('type')->eq('flow')->andWhere('buildin')->eq(0)->fetchPairs();
        foreach($flowPairs as $module)
        {
            $errors       = array();
            $actions      = $this->dao->select('action, name')->from(TABLE_WORKFLOWACTION)->where('module')->eq($module)->andWhere('open')->ne('none')->fetchPairs();
            $fields       = $this->dao->select('id')->from(TABLE_WORKFLOWFIELD)->where('module')->eq($module)->andWhere('field')->notin(array_keys($this->config->workflowfield->default->fields))->fetchPairs();
            $layoutFields = $this->dao->select('DISTINCT action')->from(TABLE_WORKFLOWLAYOUT)->where('module')->eq($module)->andWhere('action')->in(array_keys($actions))->andWhere('field')->notin('actions,file')->fetchPairs();

            foreach($actions as $action => $name)
            {
                if(isset($layoutFields[$action]))
                {
                    unset($actions[$action]);
                    continue;
                }
            }

            if(empty($fields))   $errors[] = 'emptyCustomField';
            if(!empty($actions)) $errors[] = 'emptyLayout';

            $status = !empty($errors) ? 'wait' : 'normal';
            $this->dao->update(TABLE_WORKFLOW)->set('status')->eq($status)->where('module')->eq($module)->exec();
        }

        return !dao::isError();
    }

    public function addMailtoFields()
    {
        $this->app->loadLang('workflowfield');

        $mailto = new stdclass();
        $mailto->field    = 'mailto';
        $mailto->control  = 'multi-select';
        $mailto->type     = 'text';
        $mailto->name     = $this->lang->workflowfield->default->fields['mailto'];
        $mailto->options  = 'user';
        $mailto->readonly = 1;

        $flows = $this->dao->select('module, `table`')->from(TABLE_WORKFLOW)->where('`type`')->eq('flow')->fetchPairs();
        foreach($flows as $module => $table)
        {
            $hasMailto = false;

            $fields = $this->dbh->query("DESC $table")->fetchAll();
            foreach($fields as $field)
            {
                if($field->Field == 'mailto')
                {
                    $hasMailto = true;
                    break;
                }
            }

            if(!$hasMailto)
            {
                $mailto->module = $module;

                $this->dao->insert(TABLE_WORKFLOWFIELD)->data($mailto)->autoCheck()->exec();

                if(dao::isError()) return false;

                try
                {
                    $sql = "ALTER TABLE `{$table}` ADD `mailto` text NOT NULL";
                    $this->dbh->query($sql);
                }
                catch(PDOException $exception)
                {
                    self::$errors[] = $exception->getMessage() . "<p>The sql is: $sql</p>";

                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Update attend status.
     *
     * @access public
     * @return bool
     */
    public function updateAttendStatus()
    {
        /* Get leave list. */
        $leaveList = $this->loadModel('leave')->getList('personal', '', '', '', '', 'pass');

        /* Cycle leave list to modify the attend status. */
        foreach($leaveList as $leave)
        {
            $this->dao->update(TABLE_ATTEND)
                ->set('status')->eq('leave')
                ->set('reason')->eq('leave')
                ->where('`date`')->between($leave->begin, $leave->end)
                ->andWhere('account')->eq($leave->createdBy)
                ->exec();
        }

        return true;
    }

    /**
     * Init view for workflow datasource.
     *
     * @access public
     * @return bool
     */
    public function initView4WorkflowDatasource()
    {
        $datasources = $this->dao->select('*')->from(TABLE_WORKFLOWDATASOURCE)->where('type')->eq('sql')->andWhere('view')->eq('')->fetchAll('id', false);
        foreach($datasources as $id => $datasource)
        {
            $options = array();

            try
            {
                $view = 'view_datasource_tmp';
                $sql  = "CREATE VIEW $view AS {$datasource->datasource}";
                $this->dbh->query($sql);

                $fields  = $this->dbh->query("DESC $view")->fetchAll();
                foreach($fields as $field) $options[$field->Field] = $field->Field;

                $sql = "DROP VIEW $view";
                $this->dbh->query($sql);
            }
            catch(PDOException $exception)
            {
            }

            try
            {
                $view       = "view_datasource_$id";
                $keyField   = array_shift($options);
                $valueField = array_shift($options);

                $sqls   = array();
                $sqls[] = "DROP VIEW IF EXISTS $view";
                $sqls[] = "CREATE VIEW $view AS $datasource->datasource";

                foreach($sqls as $sql) $this->dbh->query($sql);

                $this->dao->update(TABLE_WORKFLOWDATASOURCE)->set('view')->eq($view)->set('keyField')->eq($keyField)->set('valueField')->eq($valueField)->where('id')->eq($id)->exec();
            }
            catch(PDOException $exception)
            {
                dao::getError();
            }
        }

        return true;
    }

    /**
     * Adjust priv for biz5_0_1.
     *
     * @access public
     * @return bool
     */
    public function adjustPrivBiz5_0_1()
    {
        $groups = $this->dao->select('id')->from(TABLE_GROUP)->where('developer')->eq('0')->fetchPairs();
        $this->dao->delete()->from(TABLE_GROUPPRIV)->where('module')->eq('my')->andWhere('method')->in('calendar,todo')->andWhere('`group`')->in($groups)->exec();
        $this->dao->delete()->from(TABLE_GROUPPRIV)->where('module')->eq('todo')->andWhere('method')->eq('calendar')->andWhere('`group`')->in($groups)->exec();

        $priv = new stdclass();
        foreach($groups as $group)
        {
            $priv->group  = $group;
            $priv->module = 'my';
            $priv->method = 'calendar';
            $this->dao->insert(TABLE_GROUPPRIV)->data($priv)->exec();

            $priv->method = 'todo';
            $this->dao->insert(TABLE_GROUPPRIV)->data($priv)->exec();

            $priv->module = 'todo';
            $priv->method = 'calendar';
            $this->dao->insert(TABLE_GROUPPRIV)->data($priv)->exec();
        }
        return true;
    }

    public function updateWorkflow4Execution()
    {
        $workflowAction = $this->dao->select('*')->from(TABLE_WORKFLOWACTION)->where('module')->eq('execution')->andWhere('action')->eq('create')->fetch();
        if(empty($workflowAction))
        {
            $this->dao->update(TABLE_WORKFLOWACTION)->set('module')->eq('execution')->where('module')->eq('project')->exec();
            $this->dao->delete()->from(TABLE_WORKFLOWACTION)->where('module')->eq('execution')->andWhere('action')->eq('browse')->exec();
            $this->dao->exec("REPLACE INTO " . TABLE_WORKFLOWACTION . "(`module`, `action`, `name`, `type`, `batchMode`, `extensionType`, `open`, `position`, `layout`, `show`, `order`, `buildin`, `virtual`, `conditions`, `verifications`, `hooks`, `linkages`, `js`, `css`, `toList`, `blocks`, `desc`, `status`, `createdBy`, `createdDate`, `editedBy`, `editedDate`) VALUES
                ('project',	'browse',	'项目列表',	'single',	'different',	'none',	'normal',	'browseandview',	'normal',	'dropdownlist',	0,	1,	0,	'',	'',	'',	'',	'',	'',	'',	'',	'',	'enable',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'create',	'创建项目',	'single',	'different',	'none',	'normal',	'browseandview',	'normal',	'dropdownlist',	0,	1,	0,	'',	'',	'',	'',	'',	'',	'',	'',	'',	'enable',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'batchedit',	'批量编辑',	'single',	'different',	'none',	'normal',	'browseandview',	'normal',	'dropdownlist',	0,	1,	0,	'',	'',	'',	'',	'',	'',	'',	'',	'',	'enable',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'edit',	'编辑项目',	'single',	'different',	'none',	'normal',	'browseandview',	'normal',	'dropdownlist',	0,	1,	0,	'',	'',	'',	'',	'',	'',	'',	'',	'',	'enable',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'view',	'项目概况',	'single',	'different',	'none',	'normal',	'browseandview',	'side',	'dropdownlist',	0,	1,	0,	'',	'',	'',	'',	'',	'',	'',	'',	'',	'enable',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'delete',	'删除项目',	'single',	'different',	'none',	'none',	'browseandview',	'normal',	'dropdownlist',	0,	1,	0,	'',	'',	'',	'',	'',	'',	'',	'',	'',	'enable',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'close',	'关闭项目',	'single',	'different',	'none',	'modal',	'browseandview',	'normal',	'dropdownlist',	0,	1,	0,	'',	'',	'',	'',	'',	'',	'',	'',	'',	'enable',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'activate',	'激活项目',	'single',	'different',	'none',	'modal',	'browseandview',	'normal',	'dropdownlist',	0,	1,	0,	'',	'',	'',	'',	'',	'',	'',	'',	'',	'enable',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'start',	'启动项目',	'single',	'different',	'none',	'modal',	'browseandview',	'normal',	'dropdownlist',	0,	1,	0,	'',	'',	'',	'',	'',	'',	'',	'',	'',	'enable',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'suspend',	'挂起项目',	'single',	'different',	'none',	'modal',	'browseandview',	'normal',	'dropdownlist',	0,	1,	0,	'',	'',	'',	'',	'',	'',	'',	'',	'',	'enable',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('execution',	'task',	'任务列表',	'single',	'different',	'none',	'normal',	'browseandview',	'normal',	'dropdownlist',	0,	1,	0,	'',	'',	'',	'',	'',	'',	'',	'',	'',	'enable',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00')");
        }

        $workflowField = $this->dao->select('*')->from(TABLE_WORKFLOWFIELD)->where('module')->eq('execution')->limit(1)->fetch();
        if(empty($workflowField))
        {
            $this->dao->update(TABLE_WORKFLOWFIELD)->set('module')->eq('execution')->where('module')->eq('project')->exec();
            $this->dao->update(TABLE_WORKFLOWFIELD)->set('field')->eq('project')->where('module')->eq('execution')->andWhere('field')->eq('parent')->exec();
            $this->dao->exec("REPLACE INTO " . TABLE_WORKFLOWFIELD . " (`module`, `field`, `type`, `length`, `name`, `control`, `expression`, `options`, `default`, `rules`, `placeholder`, `order`, `searchOrder`, `exportOrder`, `canExport`, `canSearch`, `isValue`, `readonly`, `buildin`, `desc`, `createdBy`, `createdDate`, `editedBy`, `editedDate`) VALUES
                ('project',	'id',	'mediumint',	'8',	'编号',	'input',	'',	'',	'',	'',	'',	1,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'admin',	'2021-07-28 16:58:48'),
                ('project',	'type',	'varchar',	'20',	'项目类型',	'select',	'',	'16',	'sprint',	'',	'',	2,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'model',	'char',	'30',	'项目管理方式',	'input',	'',	'',	'',	'',	'',	3,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'admin',	'2021-07-28 16:58:30'),
                ('project',	'parent',	'mediumint',	'8',	'所属项目集',	'input',	'',	'',	'0',	'',	'',	4,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'name',	'varchar',	'90',	'项目名称',	'input',	'',	'',	'',	'',	'',	5,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'budget',	'varchar',	'30',	'预算',	'input',	'',	'',	'0',	'',	'',	7,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'budgetUnit',	'char',	'30',	'预算单位',	'input',	'',	'',	'CNY',	'',	'',	8,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'admin',	'2021-07-28 16:58:42'),
                ('project',	'begin',	'date',	'',	'计划开始',	'input',	'',	'',	'',	'',	'',	9,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'end',	'date',	'',	'计划完成',	'input',	'',	'',	'',	'',	'',	10,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'days',	'smallint',	'5',	'可用工作日',	'input',	'',	'',	'',	'',	'',	11,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'status',	'varchar',	'10',	'状态',	'select',	'',	'17',	'',	'',	'',	12,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'stage',	'enum',	'',	'阶段',	'input',	'',	'',	'1',	'',	'',	13,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'pri',	'enum',	'',	'优先级',	'input',	'',	'',	'1',	'',	'',	15,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'desc',	'text',	'',	'项目描述',	'input',	'',	'',	'',	'',	'',	16,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'openedBy',	'varchar',	'30',	'由谁创建',	'select',	'',	'user',	'',	'',	'',	17,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'openedDate',	'datetime',	'',	'创建日期',	'input',	'',	'',	'',	'',	'',	18,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'closedBy',	'varchar',	'30',	'由谁关闭',	'select',	'',	'user',	'',	'',	'',	19,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'closedDate',	'datetime',	'',	'关闭日期',	'input',	'',	'',	'',	'',	'',	20,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'canceledBy',	'varchar',	'30',	'由谁取消',	'select',	'',	'user',	'',	'',	'',	21,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'canceledDate',	'datetime',	'',	'取消日期',	'input',	'',	'',	'',	'',	'',	22,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'PO',	'varchar',	'30',	'PO',	'select',	'',	'user',	'',	'',	'',	23,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'PM',	'varchar',	'30',	'负责人',	'select',	'',	'user',	'',	'',	'',	24,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'QD',	'varchar',	'30',	'QD',	'select',	'',	'user',	'',	'',	'',	25,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'RD',	'varchar',	'30',	'RD',	'select',	'',	'user',	'',	'',	'',	26,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'team',	'varchar',	'90',	'团队名称',	'input',	'',	'',	'',	'',	'',	27,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'acl',	'enum',	'',	'访问控制',	'select',	'',	'18',	'open',	'',	'',	28,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'whitelist',	'text',	'',	'项目白名单',	'select',	'',	'7',	'',	'',	'',	29,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'order',	'mediumint',	'8',	'排序',	'input',	'',	'',	'',	'',	'',	30,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00'),
                ('project',	'deleted',	'enum',	'',	'已删除',	'select',	'',	'[\"\\u672a\\u5220\\u9664\",\"\\u5df2\\u5220\\u9664\"]',	'0',	'',	'',	31,	0,	0,	'0',	'0',	'0',	'1',	1,	'',	'',	'2021-07-28 16:54:38',	'',	'0000-00-00 00:00:00')");
        }
    }

    /**
     * Add file fields.
     *
     * @access public
     * @return bool
     */
    public function addFileFields()
    {
        $layoutModules   = $this->dao->select('DISTINCT module')->from(TABLE_WORKFLOWLAYOUT)->where('field')->eq('file')->fetchPairs();
        $relationModules = $this->dao->select('DISTINCT prev')->from(TABLE_WORKFLOWRELATIONLAYOUT)->where('field')->eq('file')->fetchPairs();
        $modules         = arrayUnion($layoutModules, $relationModules);
        if(!$modules) return true;

        $fileFields = $this->dao->select('*')->from(TABLE_WORKFLOWFIELD)->where('module')->in($modules)->andWhere('control')->eq('file')->andWhere('field')->eq('file')->fetchGroup('module', 'field');

        $file = new stdclass();
        $file->field   = 'file';
        $file->type    = 'varchar';
        $file->length  = '255';
        $file->name    = $this->lang->files;
        $file->control = 'file';
        $file->options = '[]';

        foreach($modules as $module)
        {
            if(isset($fileFields[$module]['file'])) continue;

            $file->module = $module;
            $this->dao->delete()->from(TABLE_WORKFLOWFIELD)->where('module')->eq($module)->andWhere('field')->eq('file')->exec();
            $this->dao->insert(TABLE_WORKFLOWFIELD)->data($file)->exec();
        }

        return true;
    }

    /**
     * Process feedback field.
     *
     * @access public
     * @return void
     */
    public function processFeedbackField()
    {
        /* If feedback's closed reason is totask/tostory/tobug, change it to solution and set closed reason commented.*/
        $feedbacks = $this->dao->select('id, closedReason')->from(TABLE_FEEDBACK)->where('deleted')->eq('0')->fetchAll();
        foreach($feedbacks as $feedback)
        {
            $feedback->closedReason = strtolower($feedback->closedReason);
            if(in_array($feedback->closedReason, array('totask', 'tobug', 'totask', 'totodo')))
            {
                $this->dao->update(TABLE_FEEDBACK)
                    ->set('solution')->eq($feedback->closedReason)
                    ->set('closedReason')->eq('commented')
                    ->where('id')->eq($feedback->id)
                    ->exec();
            }
        }

        /* Process actions. */
        $this->dao->update(TABLE_ACTION)->set('extra')->eq('commented')
            ->where('objectType')->eq('feedback')
            ->andWhere('action')->eq('closed')
            ->andWhere('(extra')->eq('ToStory')
            ->orWhere('extra')->eq('ToBug')
            ->orWhere('extra')->eq('ToTask')
            ->orWhere('extra')->eq('ToTodo')
            ->markRight(1)
            ->exec();
    }

    /**
     * Add report actions.
     *
     * @access public
     * @return bool
     */
    public function addReportActions()
    {
        $this->loadModel('workflowaction');

        $actionCode   = 'report';
        $actionLang   = $this->lang->workflowaction->default;
        $actionConfig = $this->config->workflowaction->default;
        $flows        = $this->dao->select('module')->from(TABLE_WORKFLOW)->where('type')->eq('flow')->andWhere('buildin')->eq(0)->fetchPairs();
        $actions      = $this->dao->select('module')->from(TABLE_WORKFLOWACTION)->where('action')->eq($actionCode)->fetchPairs();

        $action = new stdclass();
        $action->action      = $actionCode;
        $action->name        = $actionLang->actions[$actionCode];
        $action->type        = zget($actionConfig->types, $actionCode, 'single');
        $action->batchMode   = zget($actionConfig->batchModes, $actionCode, 'different');
        $action->open        = $actionConfig->opens[$actionCode];
        $action->position    = $actionConfig->positions[$actionCode];
        $action->show        = 'direct';
        $action->createdBy   = isset($this->app->user->account) ? $this->app->user->account : 'admin';
        $action->createdDate = helper::now();

        foreach($flows as $module)
        {
            if(isset($actions[$module])) continue;

            $action->module = $module;
            $this->dao->delete()->from(TABLE_WORKFLOWACTION)->where('module')->eq($module)->andWhere('action')->eq($actionCode)->andWhere('vision')->eq('rnd')->exec();
            $this->dao->insert(TABLE_WORKFLOWACTION)->data($action)->autoCheck()->exec();
        }
        return !dao::isError();
    }

    /**
     * Process view fields.
     *
     * @access public
     * @return bool
     */
    public function processViewFields()
    {
        $datasources = $this->dao->select('id, datasource, keyField, valueField')->from(TABLE_WORKFLOWDATASOURCE)->where('view')->ne('')->andWhere('buildin')->eq('0')->fetchAll();

        foreach($datasources as $datasource)
        {
            try
            {
                $tmpView = 'view_datasource_tmp';

                $sqls = array();
                $sqls[] = "DROP VIEW IF EXISTS $tmpView";
                $sqls[] = "CREATE VIEW $tmpView AS $datasource->datasource";
                foreach($sqls as $sql) $this->dbh->query($sql);

                $sqlFields = $this->dbh->query("DESC $tmpView")->fetchAll();

                $i = 1;
                $fields = array();
                foreach($sqlFields as $field)
                {
                    $fields[$field->Field] = "field{$i}";;
                    $i++;
                }

                $sql = "DROP VIEW $tmpView";
                $this->dbh->query($sql);

                $viewFields = implode(',', $fields);
                $view       = "view_datasource_{$datasource->id}";

                $sqls   = array();
                $sqls[] = "DROP VIEW IF EXISTS $view";
                $sqls[] = "CREATE VIEW $view ($viewFields) AS $datasource->datasource";
                foreach($sqls as $sql) $this->dbh->query($sql);

                $keyField   = zget($fields, $datasource->keyField);
                $valueField = zget($fields, $datasource->valueField);
                $this->dao->update(TABLE_WORKFLOWDATASOURCE)->set('keyField')->eq($keyField)->set('valueField')->eq($valueField)->where('id')->eq($datasource->id)->exec();
            }
            catch(PDOException $exception)
            {
                dao::$errors = $exception->getMessage();
                return false;
            }
        }
    }

    /**
     * Process flow position.
     *
     * @access public
     * @return void
     */
    public function processFlowPosition()
    {
        $customPrimaryFlows = $this->dao->select('module')->from(TABLE_WORKFLOW)
            ->where('buildin')->eq(0)
            ->andWhere('type')->eq('flow')
            ->andWhere('status')->eq('normal')
            ->andWhere('navigator')->eq('primary')
            ->fetchPairs('module', 'module');
        $flows = $this->dao->select('id,app,position,module')->from(TABLE_WORKFLOW)
            ->where('buildin')->eq(0)
            ->andWhere('type')->eq('flow')
            ->andWhere('app')->in($customPrimaryFlows)
            ->andWhere('status')->eq('normal')
            ->andWhere('navigator')->eq('secondary')
            ->fetchAll();
        foreach($flows as $flow)
        {
            $direction = strpos($flow->position, 'after') === 0 ? 'after' : 'before';
            $position  = substr($flow->position, strlen($direction));
            if(preg_match('/^browse(\d+)/', $position))
            {
                $position = $direction . $flow->app;
                $this->dao->update(TABLE_WORKFLOW)->set('position')->eq($position)->where('id')->eq($flow->id)->exec();
            }
        }
    }

    /**
     * 处理工作流内置模块的列表字段。
     * Process buildin browse fields.
     *
     * @access public
     * @return void
     */
    public function processBuildinBrowseFields()
    {
        $fieldGroup = $this->dao->select('t1.module, t1.field')->from(TABLE_WORKFLOWFIELD)->alias('t1')
            ->leftJoin(TABLE_WORKFLOW)->alias('t2')->on('t1.module = t2.module')
            ->where('t2.buildin')->eq('1')
            ->andWhere('t1.role')->eq('custom')
            ->fetchGroup('module', 'field');

        $layout = new stdclass();
        $layout->ui = 0;

        foreach($fieldGroup as $module => $fields)
        {
            $method = 'browse';
            if($module == 'product' || $module == 'execution')
            {
                $method = 'all';
            }
            else if($module == 'story')
            {
                $module = 'product';
            }
            else if($module == 'feedback')
            {
                $method = 'admin';
            }
            else if($module == 'task')
            {
                $module = 'execution';
                $method = 'task';
            }
            else if($module == 'build')
            {
                $module = 'execution';
                $method = 'build';
            }

            /* 如果内置模块的字段扩展过，则将该模块的列表动作设置成扩展。 */
            $this->dao->update(TABLE_WORKFLOWACTION)->set('extensionType')->eq('extend')->where('module')->eq($module)->andWhere('action')->eq($method)->exec();

            /* 将内置模块新增的字段插入到列表页的layout表。 */
            $visions = $this->dao->select('vision')->from(TABLE_WORKFLOWACTION)->where('module')->eq($module)->andWhere('action')->eq($method)->fetchPairs();

            $this->dao->delete()->from(TABLE_WORKFLOWLAYOUT)->where('module')->eq($module)->andWhere('action')->eq($method)->andWhere('ui')->eq(0)->andWhere('field')->in(array_keys($fields))->andWhere('vision')->in($visions)->exec();

            $layout->order  = 1;
            $layout->module = $module;
            $layout->action = $method;

            foreach($visions as $vision)
            {
                $layout->vision = $vision;

                foreach($fields as $field)
                {
                    $layout->field = $field->field;

                    $this->dao->insert(TABLE_WORKFLOWLAYOUT)->data($layout)->exec();

                    $layout->order ++;
                }
            }
        }
    }
}
