<?php
/**
 * The control file of workflow module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflow
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
class workflow extends control
{
    /**
     * Browse userdefined flows.
     *
     * @param  string $mode         browse | bysearch
     * @param  string $status       wait | normal | pause
     * @param  string $app
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browseFlow($mode = 'browse', $status = '', $app = '', $param = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* Build search form. */
        $this->config->workflow->search['actionURL'] = inlink('browseFlow', "mode=bysearch&status=$status&app=$app&param=myQueryID");
        $this->config->workflow->search['params']['app']['values'] = arrayUnion(array('' => ''), $this->workflow->getApps());
        $this->loadModel('search')->setSearchParams($this->config->workflow->search);

        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->session->set('workflowList', $this->app->getURI());
        $this->session->set('workflowGroupID', 0);

        $queryID = $mode == 'bysearch' ? $param : '';
        $flows   = $this->workflow->getList($mode, 'flow', $status, '', $app, $orderBy, $pager, $queryID);
        foreach($flows as $flow) $flow->newVersion = $this->workflow->getVersionPairs($flow);

        $apps = $this->workflow->getApps();
        $apps['project'] = $this->lang->project->common;

        $this->view->title      = $this->lang->workflow->browseFlow;
        $this->view->apps       = $apps;
        $this->view->flows      = $flows;
        $this->view->mode       = $mode;
        $this->view->status     = $status;
        $this->view->currentApp = $app;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->param      = $param;
        $this->display();
    }

    /**
     * Browse userdefined tables.
     *
     * @param  string $parent
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browseDB($parent = '', $table = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $tables     = $this->workflow->getList('browse', 'table', '', $parent, '', $orderBy, $pager);
        $tablePairs = $this->workflow->getPairs($parent, 'table');

        if($tables && (!$table || !isset($tablePairs[$table]))) $table = current($tables)->module;

        $subTableTipsReaders = zget($this->config->workflow, 'subTableTipsReaders', ',');
        if(strpos($subTableTipsReaders, ",{$this->app->user->account},") === false)
        {
            $this->loadModel('setting')->setItem('system.flow.workflow.subTableTipsReaders', $subTableTipsReaders . $this->app->user->account . ',');
        }

        $tables = $this->loadModel('workflowfield')->queryGroupNameByList($parent, $tables, (int)$this->session->workflowGroupID, 'table');
        $fields = array();
        if($table)
        {
            $fields = $this->workflowfield->getList($table,'`order`, id', 0);
            /* If flow is table, filter the useless field.*/
            $disabledFields = $this->config->workflowfield->disabledFields['subTables'];
            foreach($fields as $key => $field)
            {
                if($disabledFields and strpos(",{$disabledFields},", ",{$field->field},") !== false) unset($fields[$key]);
            }
        }

        $this->view->title               = $this->lang->workflow->browseDB;
        $this->view->tables              = $tables;
        $this->view->fields              = $fields;
        $this->view->flow                = $this->workflow->getByModule($parent);
        $this->view->currentTable        = $this->workflow->getByModule($table);
        $this->view->subTableTipsReaders = $subTableTipsReaders;
        $this->view->orderBy             = $orderBy;
        $this->view->pager               = $pager;
        $this->view->editorMode          = 'advanced';
        $this->view->quoteTables         = $this->workflow->getQuoteTables(array_keys($tablePairs));
        $this->display();
    }

    /**
     * 引用其他模板子表。
     * Quote fields.
     *
     * @param  string $module
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function quoteDB($module, $groupID = 0)
    {
        $this->app->loadLang('workflowfield');

        if(strtolower($this->server->request_method) == 'post')
        {
            $this->workflow->saveQuote($groupID);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'parent'));
        }

        $this->view->title       = $this->lang->workflow->quoteDB;
        $this->view->module      = $module;
        $this->view->groupID     = $groupID;
        $this->view->tableGroups = $this->workflowZen->buildCanQuoteTree($module, $groupID);
        $this->display();
    }

    /**
     * Create a flow or table.
     *
     * @param  string $type
     * @param  string $parent
     * @param  string $app
     * @access public
     * @return void
     */
    public function create($type = 'flow', $parent = '', $app = '')
    {
        if($type == 'table')
        {
            $this->lang->workflow->create = $this->lang->workflowtable->create;
            $this->lang->workflow->module = $this->lang->workflowtable->module;
            $this->lang->workflow->name   = $this->lang->workflowtable->name;
        }

        if($_POST)
        {
            $flowID = $this->workflow->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $flow = $this->workflow->getByID($flowID);

            $this->workflow->createFields($flow);    // Create table and default fields.
            $this->workflow->createActions($flow);   // Create default actions.
            $this->workflow->createLabels($flow);    // Create default labels.

            if(!empty($this->config->openedApproval) && $this->post->approval == 'enabled')
            {
                $this->workflow->createApprovalRelation($flow);
                $this->workflow->createApprovalObject($this->post->approvalFlow, $flow->module);
            }

            if(dao::isError())
            {
                $errors = dao::getError();

                $this->workflow->delete($flowID);

                return $this->send(array('result' => 'fail', 'message' => $errors));
            }

            $this->loadModel('action')->create('workflow', $flowID, 'Created');

            if($type == 'table') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload', 'module' => $flow->module));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'locate' => inlink('ui', "module={$flow->module}")));
        }

        if($type == 'flow')
        {
            $this->view->apps = $this->workflow->getApps('admin', false);

            if(!empty($this->config->openedApproval) && $this->config->edition != 'biz') $this->view->approvalFlows = $this->loadModel('approvalflow')->getPairs('workflow');
        }

        $this->view->title      = $this->lang->workflow->create;
        $this->view->type       = $type;
        $this->view->parent     = $parent;
        $this->view->currentApp = $app;
        $this->display();
    }

    /**
     * Copy a flow to create a new one.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function copy($id)
    {
        if($_POST)
        {
            $flowID = $this->workflow->copy($id);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($this->config->openedApproval) && $this->post->approval == 'enabled')
            {
                $this->workflow->createApprovalObject($this->post->approvalFlow, $this->post->module);
            }

            $this->loadModel('action')->create('workflow', $flowID, 'Created');

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $flow = $this->workflow->getByID($id);
        if($flow->type == 'flow')
        {
            if($flow->navigator == 'primary')
            {
                $this->view->apps  = [];
                $this->view->menus = $this->workflow->getApps('admin', false);
            }
            else
            {
                $this->view->apps  = $this->workflow->getApps();
                $this->view->menus = $this->workflow->getAppMenus($flow->app);
            }

            if(!empty($this->config->openedApproval) && $this->config->edition != 'biz')
            {
                $this->view->approvalFlows = $this->loadModel('approvalflow')->getPairs('workflow');
                $this->view->approvalFlow  = $this->dao->select('flow')->from(TABLE_APPROVALFLOWOBJECT)->where('objectType')->eq($flow->module)->fetch('flow');
            }
        }

        $this->view->title     = $this->lang->workflow->copy;
        $this->view->dropMenus = $this->workflow->getAppDropMenus($flow->app, $flow->positionModule);
        $this->view->flow      = $flow;
        $this->display();
    }

    /**
     * Edit a flow or table.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function edit($id)
    {
        $flow = $this->workflow->getByID($id);
        if($_POST)
        {
            $changes = $this->workflow->update($id);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $alert = $this->workflow->fixBelongLayoutField($id);
            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('workflow', $id, 'Edited');
                if($changes) $this->action->logHistory($actionID, $changes);
            }

            $locate = true;
            if($this->post->navigator != $flow->navigator) $locate = inlink('browseFlow');
            if($this->post->navigator == $flow->navigator && $this->post->app != $flow->app) $locate = inlink('browseFlow');

            if($alert) return $this->send(array('result' => 'success', 'message' => $alert, 'load' => $locate));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $locate));
        }

        if($flow->type == 'table')
        {
            $this->lang->workflow->edit   = $this->lang->workflowtable->edit;
            $this->lang->workflow->module = $this->lang->workflowtable->module;
            $this->lang->workflow->name   = $this->lang->workflowtable->name;
        }
        if($flow->type == 'flow')
        {
            if($flow->navigator == 'primary')
            {
                $this->view->apps  = [];
                $this->view->menus = $this->workflow->getApps("admin,{$flow->module}", false);
            }
            else
            {
                $this->view->apps  = $this->workflow->getApps();
                $this->view->menus = $this->workflow->getAppMenus($flow->app, $flow->module);
            }

            $this->view->groupID = $this->dao->select('`group`')->from(TABLE_WORKFLOW)->where('module')->eq($flow->module)->andWhere('group')->ne('0')->fetch('group');
        }

        $this->view->title       = $this->lang->workflow->edit;
        $this->view->flow        = $flow;
        $this->view->dropMenus   = $this->workflow->getAppDropMenus($flow->app, $flow->positionModule);
        $this->view->quoteTables = $this->workflow->getQuoteTables($flow->module);
        $this->display();
    }

    /**
     * Backup a flow.
     *
     * @param  string $module
     * @access public
     * @return void
     */
    public function backup($module)
    {
        $result = $this->workflow->backup($module);
        if(!$result) return $this->send(array('result' => 'fail', 'message' => implode("\n", dao::getError())));

        return $this->send(array('result' => 'success', 'message' => $this->lang->workflow->upgrade->backupSuccess));
    }

    /**
     * Upgrade a flow.
     *
     * @param  string $module
     * @param  string $step
     * @param  string $toVersion
     * @param  string $mode         install | upgrade
     * @access public
     * @return void
     */
    public function upgrade($module, $step = 'start', $toVersion = '', $mode = '')
    {
        if($step == 'start')
        {
            if($toVersion)
            {
                $this->locate(inlink('upgrade', "module=$module&step=confirm&toVersion=$toVersion"));
            }
            else
            {
                $flow = $this->workflow->getByModule($module);

                $this->view->title    = $this->lang->workflow->upgrade->selectVersion;
                $this->view->versions = $this->workflow->getVersionPairs($flow);
                $this->view->flow     = $flow;
            }
        }
        elseif($step == 'confirm')
        {
            if($mode)
            {
                $this->locate(inlink('upgrade', "module=$module&step=result&toVersion=$toVersion&mode=$mode"));
            }
            else
            {
                $sqls = $this->workflow->compare($module, $toVersion);
                //if(!$sqls) $this->locate(inlink('upgrade', "module=$module&step=result&toVersion=$toVersion&mode=upgrade"));

                $this->view->title = $this->lang->workflow->upgrade->confirm;
                $this->view->sqls  = implode("\n", $sqls);
            }
        }
        elseif($step == 'result')
        {
            $result = $this->workflow->backup($module);
            if($result)
            {
                $this->view->result = $this->workflow->$mode($module, $toVersion);
            }
            else
            {
                $this->view->result = array('result' => 'fail', 'errors' => dao::getError());
            }
        }

        $this->view->module     = $module;
        $this->view->step       = $step;
        $this->view->toVersion  = $toVersion;
        $this->view->mode       = $mode;
        $this->view->modalWidth = 400;
        $this->display();
    }

    /**
     * View a flow or table.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function view($id)
    {
        $flow = $this->workflow->getByID($id);
        if($flow->type == 'table')
        {
            $this->lang->workflow->view   = $this->lang->workflowtable->view;
            $this->lang->workflow->module = $this->lang->workflowtable->name;
        }
        if($flow->type == 'flow')
        {
            if($flow->navigator == 'primary')
            {
                $this->view->apps  = [];
                $this->view->menus = $this->workflow->getApps('admin', false);
            }
            else
            {
                $this->view->apps  = $this->workflow->getApps();
                $this->view->menus = $this->workflow->getAppMenus($flow->app);
            }

            if(!empty($this->config->openedApproval) && $this->config->edition != 'biz')
            {
                $this->view->approvalFlows = $this->loadModel('approvalflow')->getPairs('workflow');
                $this->view->approvalFlow  = $this->dao->select('flow')->from(TABLE_APPROVALFLOWOBJECT)->where('objectType')->eq($flow->module)->fetch('flow');
            }
        }

        $this->view->title     = $this->lang->workflow->view;
        $this->view->users     = $this->loadModel('user')->getDeptPairs();
        $this->view->dropMenus = $this->workflow->getAppDropMenus($flow->app, $flow->positionModule);
        $this->view->flow      = $flow;
        $this->display();
    }

    /**
     * Workflow editor ui page for quick mode.
     *
     * @param  string $module
     * @param  string $action
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function ui($module, $action = 'create', $groupID = 0)
    {
        $this->session->set('workflowGroupID', $groupID);

        if($_POST)
        {
            $result = $this->workflow->saveLayout($module, $action);
            return $this->send($result);
        }

        $flow    = $this->workflow->getByModule($module);
        $fields  = $this->loadModel('workflowaction', 'flow')->getFields($module, $action, false);
        $actions = $this->dao->select('action, name, type')->from(TABLE_WORKFLOWACTION)
            ->where('module')->eq($module)
            ->andWhere('open')->ne('none')
            ->andWhere('`virtual`')->eq(0)
            ->andWhere('status')->eq('enable')
            ->andWhere('group')->eq((int)$this->session->workflowGroupID)
            ->orderBy('order_asc')
            ->fetchAll('action');

        if(!empty($flow->belong) && ($action == 'create' || $action == 'batchcreate') && empty($fields[$flow->belong]->show))
        {
            $notEmptyRule = $this->loadModel('workflowrule')->getByTypeAndRule('system', 'notempty');
            $fields[$flow->belong]->show        = '1';
            $fields[$flow->belong]->layoutRules = $notEmptyRule->id;
        }

        $this->loadModel('workflowlayout', 'flow');
        $disabledFields = in_array($action, $this->config->workflowaction->defaultActions) ? zget($this->config->workflowlayout->disabledFields, $action, '') : $this->config->workflowlayout->disabledFields['custom'];
        foreach($fields as $key => $field)
        {
            if(strpos(",{$disabledFields},", ",{$field->field},") !== false or strpos($field->field, 'sub_') === 0) unset($fields[$key]);
        }

        if($action == 'view') $this->app->loadLang('action');

        foreach($fields as $field) $field->optionsData = $this->workflowaction->getRealOptions($field);

        $this->view->title         = $this->lang->workfloweditor->uiDesign;
        $this->view->flow          = $flow;
        $this->view->editorMode    = 'quick';
        $this->view->notEmptyRule  = $this->loadModel('workflowrule', 'flow')->getByTypeAndRule('system', 'notempty');
        $this->view->actions       = $actions;
        $this->view->currentAction = zget($actions, $action, $actions['create']);
        $this->view->fields        = $fields;
        $this->view->groupID       = $groupID;
        $this->view->datasources   = $this->loadModel('workflowfield', 'flow')->getDatasourcePairs($flow->type);
        $this->view->rules         = $this->loadModel('workflowrule', 'flow')->getPairs();

        $this->display();
    }

    /**
     * Release a flow.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function release($id)
    {
        if($_POST)
        {
            $changes = $this->workflow->release($id);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $alert = $this->workflow->fixBelongLayoutField($id);
            if($changes)
            {
                $action = $this->loadModel('action')->create('workflow', $id, 'created');
                $this->action->logHistory($action, $changes);
            }

            $locate   = inlink('browseFlow');
            $callback = 'setTimeout(function(){loadPage("' . $locate . '");}, 1000)';
            if($alert) return $this->send(array('result' => 'success', 'message' => $alert, 'callback' => $callback));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'callback' => $callback));
        }

        $flow   = $this->workflow->getByID($id);
        $errors = $this->workflow->checkFieldAndLayout($flow->module);
        if(empty($errors))
        {
            if($flow->type == 'flow')
            {
                if($flow->navigator == 'primary')
                {
                    $this->view->apps  = [];
                    $this->view->menus = $this->workflow->getApps("admin,{$flow->module}", false);
                }
                else
                {
                    $this->view->apps  = $this->workflow->getApps();
                    $this->view->menus = $this->workflow->getAppMenus($flow->app, $flow->module);
                }
            }
        }
        else
        {
            $this->view->errors = $errors;
        }

        $this->view->title     = $this->lang->workflow->release;
        $this->view->dropMenus = $this->workflow->getAppDropMenus($flow->app, $flow->positionModule);
        $this->view->flow      = $flow;
        $this->display();
    }

    /**
     * Deactivate flow.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function deactivate($id)
    {
        $workflow = $this->workflow->getByID($id);
        $this->dao->update(TABLE_WORKFLOW)->set('status')->eq('pause')
             ->where('module')->eq($workflow->module)
             ->exec();

        $this->loadModel('workflowgroup')->updateDisabledModules($workflow->module, 'add');

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * Activate flow.
     *
     * @param  int    $id
     * @param  string $type all | single
     * @access public
     * @return void
     */
    public function activate($id, $type = '')
    {
        $workflow = $this->workflow->getByID($id);
        $this->dao->update(TABLE_WORKFLOW)
             ->set('status')->eq('normal')
             ->where('module')->eq($workflow->module)
             ->exec();

        $this->loadModel('workflowgroup')->updateDisabledModules($workflow->module, 'remove', $type);

        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * Delete a flow or table.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function delete($id)
    {
        $this->workflow->delete($id);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return $this->send(array('result' => 'success', 'message' => $this->lang->deleteSuccess, 'load' => true));
    }

    /**
     * Set js.
     *
     * @param  int    $id
     * @param  string $type     flow | action
     * @access public
     * @return void
     */
    public function setJS($id, $type = 'flow')
    {
        if($_POST)
        {
            $this->workflow->setJS($id, $type);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $closeModal = $type == 'action' ? 'true' : '';
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => $closeModal, 'locate' => 'reload'));
        }

        if($type == 'action')
        {
            $action = $this->loadModel('workflowaction')->getByID($id);
            $flow   = $this->workflow->getByModule($action->module);
        }
        else
        {
            $flow = $this->workflow->getByID($id);
        }

        $table = $type == 'flow' ? TABLE_WORKFLOW : TABLE_WORKFLOWACTION;

        $this->view->title      = $this->lang->workflow->setJS;
        $this->view->js         = $this->dao->select('js')->from($table)->where('id')->eq($id)->fetch('js');
        $this->view->type       = $type;
        $this->view->id         = $id;
        $this->view->flow       = $flow;
        $this->view->editorMode = 'advanced';
        $this->display();
    }

    /**
     * Set css.
     *
     * @param  int    $id
     * @param  string $type     flow | action
     * @access public
     * @return void
     */
    public function setCSS($id, $type = 'flow')
    {
        if($_POST)
        {
            $this->workflow->setCSS($id, $type);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $closeModal = $type == 'action' ? 'true' : '';
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => $closeModal, 'locate' => 'reload'));
        }

        if($type == 'action')
        {
            $action = $this->loadModel('workflowaction')->getByID($id);
            $flow   = $this->workflow->getByModule($action->module);
        }
        else
        {
            $flow = $this->workflow->getByID($id);
        }

        $table = $type == 'flow' ? TABLE_WORKFLOW : TABLE_WORKFLOWACTION;

        $this->view->title      = $this->lang->workflow->setCSS;
        $this->view->css        = $this->dao->select('css')->from($table)->where('id')->eq($id)->fetch('css');
        $this->view->type       = $type;
        $this->view->id         = $id;
        $this->view->flow       = $flow;
        $this->view->editorMode = 'advanced';
        $this->display();
    }

    /**
     * Set fulltext search of a flow.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function setFulltextSearch($id)
    {
        if($_POST)
        {
            $this->workflow->setFulltextSearch($id);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $flow = $this->workflow->getByID($id);

        $this->view->title      = $this->lang->workflow->fullTextSearch->common;
        $this->view->fields     = arrayUnion(array(''), $this->loadModel('workflowfield', 'flow')->getFieldPairs($flow->module));
        $this->view->flow       = $flow;
        $this->view->editorMode = 'advanced';
        $this->display();
    }

    /**
     * Set approval.
     *
     * @param  string $module
     * @access public
     * @return void
     */
    public function setApproval($module)
    {
        if(empty($this->config->openedApproval)) $this->locate(inlink('browseflow'));

        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $result = $this->workflow->setApproval($module);
            if($result['result'] != 'success') return $this->send($result);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }
        $this->app->loadLang('workflowrelation');

        $this->view->title         = $this->lang->workflow->setApproval;
        $this->view->flow          = $this->loadModel('workflow', 'flow')->getByModule($module);
        $this->view->approvalFlows = $this->loadModel('approvalflow')->getPairs($module);
        $this->view->approvalFlow  = $this->dao->select('extra,flow')->from(TABLE_APPROVALFLOWOBJECT)->where('objectType')->eq($module)->andWhere('root')->eq((int)$this->session->workflowGroupID)->fetchPairs();
        $this->view->module        = $module;
        $this->view->editorMode    = 'advanced';
        $this->display();
    }

    /**
     * Build index for full text search.
     *
     * @param  string $module
     * @param  int    $lastID
     * @access public
     * @return void
     */
    public function buildIndex($module, $lastID = 0)
    {
        $flow = $this->workflow->getByModule($module);
        if($flow->buildin or !$flow->titleField) return $this->send(array('result' => 'finished', 'message' => $this->lang->workflow->error->buildIndexFail));

        $this->workflow->appendSearchConfig();
        $result = $this->loadModel('search')->buildAllIndex($module, $lastID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        if(isset($result['finished']) and $result['finished'])
        {
            return $this->send(array('result' => 'finished', 'message' => $this->lang->search->buildSuccessfully));
        }
        else
        {
            return $this->send(array('result' => 'unfinished', 'message' => sprintf($this->lang->search->buildResult, zget($this->lang->searchObjects, $module, $module), $result['count'], $result['count']), 'next' => inlink('buildIndex', "module={$module}&lastID={$result['lastID']}")));
        }
    }

    /**
     * Get apps by ajax.
     *
     * @param  string $exclude
     * @access public
     * @return void | array
     * @param bool $splitProject
     */
    public function ajaxGetApps($splitProject = false, $exclude = '')
    {
        $apps = $this->workflow->getApps("admin,{$exclude}", $splitProject);

        $items = [];
        foreach($apps as $key => $value) $items[] = ['text' => $value, 'value' => $key];
        return $this->send($items);
    }

    /**
     * Get menus of an app by ajax.
     *
     * @param  string   $app
     * @param  string   $exclude    The exclude menu, separated by comma.
     * @access public
     * @return string
     */
    public function ajaxGetAppMenus($app, $exclude = '')
    {
        $items = [];
        $menus = $this->workflow->getAppMenus($app, $exclude);
        foreach($menus as $key => $label) $items[] = ['text' => $label, 'value' => $key];
        return $this->send($items);
    }

    /**
     * Ajax get dropMenus.
     *
     * @param  string $app
     * @param  string $menu
     * @access public
     * @return void
     */
    public function ajaxGetDropMenus($app, $menu = '')
    {
        $items = [];
        $menus = $this->workflow->getAppDropMenus($app, $menu);
        foreach($menus as $key => $label) $items[] = ['text' => $label, 'value' => $key];
        return $this->send($items);
    }

    /**
     * Ajax view table.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function ajaxViewDB($id)
    {
        $this->app->loadLang('workflowfield');
        $this->view->table = $this->workflow->getByID($id);
        $this->display();
    }
}
