<?php
/**
 * The control file of workflowgroup module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@chandao.com>
 * @package     workflowgroup
 * @link        https://www.zentao.net
 */
class workflowgroup extends control
{
    /**
     * 工作流产品流程列表。
     * The product workflow list.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
    *  @param  int    $pageID
     * @access public
     * @return void
     */
    public function product($orderBy = '', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadLang('workflow');
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title   = $this->lang->workflowgroup->product;
        $this->view->groups  = $this->workflowgroup->getList('product', $orderBy, $pager);
        $this->view->pager   = $pager;
        $this->view->orderBy = $orderBy;
        $this->display();
    }

    /**
     * 工作流项目流程列表。
     * The project workflow list.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
    *  @param  int    $pageID
     * @access public
     * @return void
     */
    public function project($orderBy = '', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadLang('workflow');
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title   = $this->lang->workflowgroup->project;
        $this->view->groups  = $this->workflowgroup->getList('project', $orderBy, $pager);
        $this->view->pager   = $pager;
        $this->view->orderBy = $orderBy;
        $this->display();
    }

    /**
     * 创建一个流程模板。
     * Create a workflowgroup.
     *
     * @param  string $type     product 产品流程模板 | project 项目流程模板
     * @access public
     * @return void
     */
    public function create($type)
    {
        if($_POST)
        {
            $group = form::data($this->config->workflowgroup->form->create)
                ->add('type', $type)
                ->add('createdBy', $this->app->user->account)
                ->add('createdDate', helper::now())
                ->get();
            $groupID = $this->workflowgroup->create($group);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('workflowgroup', $groupID, 'created');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
        }

        $this->view->title = $this->lang->workflowgroup->create;
        $this->view->type  = $type;
        $this->display();
    }

    /**
     * 编辑一个流程模板。
     * Edit a workflowgroup.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function edit($id)
    {
        $oldGroup = $this->workflowgroup->getByID($id);

        if($_POST)
        {
            $group = form::data($this->config->workflowgroup->form->edit)
                ->add('editedBy', $this->app->user->account)
                ->add('editedDate', helper::now())
                ->get();
            $changes = $this->workflowgroup->update($group, $oldGroup);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('workflowgroup', $id, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
        }

        $this->view->title = $this->lang->workflowgroup->edit;
        $this->view->group = $oldGroup;
        $this->display();
    }

    /**
     * 查看一个流程模板。
     * View a workflowgroup.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function view($id)
    {
        $this->view->title   = $this->lang->workflowgroup->view;
        $this->view->actions = $this->loadModel('action')->getList('workflowgroup', $id);
        $this->view->group   = $this->workflowgroup->getByID($id);
        $this->display();
    }

    /**
     * 删除一个流程模板。
     * Delete a workflowgroup.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function delete($id)
    {
        $this->workflowgroup->delete(TABLE_WORKFLOWGROUP, $id);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * 发布一个流程模板。
     * Release a workflowgroup.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function release($id)
    {
        $this->workflowgroup->changeStatus($id, 'normal');
        $this->loadModel('action')->create('workflowgroup', $id, 'released');
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * 停用一个流程模板。
     * Deactivate a workflowgroup.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function deactivate($id)
    {
        $this->workflowgroup->changeStatus($id, 'pause');
        $this->loadModel('action')->create('workflowgroup', $id, 'deactivated');
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * 设计流程模板。
     * Design workflowgroup.
     *
     * @param  int    $id
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function design($id, $orderBy = 'id')
    {
        $group = $this->workflowgroup->getByID($id);
        if(empty($group)) return false;

        /* Set height light menu. */
        $this->lang->workflow->menu->flowgroup['subMenu']->{$group->type}['alias'] = 'design';

        $this->session->set('workflowList', $this->app->getURI());

        $apps = $this->loadModel('workflow')->getApps();
        $apps['project'] = $this->lang->project->common;

        $this->view->title   = $this->lang->workflowgroup->design;
        $this->view->group   = $group;
        $this->view->apps    = $apps;
        $this->view->flows   = $this->workflowgroup->getFlows($group, $orderBy);
        $this->view->orderBy = $orderBy;
        $this->display();
    }

    /**
     * 设置专属流程。
     * Set exclusive flow.
     *
     * @param  int    $flowID
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function setExclusive($flowID, $groupID)
    {
        $this->workflowgroup->setExclusive($flowID, $groupID);
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * 启用流程
     * Activate flow.
     *
     * @param  string $module
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function activateFlow($module, $groupID)
    {
        $group = $this->workflowgroup->getByID($groupID);

        $disabledModules = explode(',', $group->disabledModules);
        foreach($disabledModules as $i => $disabledModule)
        {
            if($disabledModule == $module) unset($disabledModules[$i]);
        }
        $this->dao->update(TABLE_WORKFLOWGROUP)->set('disabledModules')->eq(implode(',', $disabledModules))->where('id')->eq($groupID)->exec();

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * 停用流程
     * Activate flow.
     *
     * @param  string $module
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function deactivateFlow($module, $groupID)
    {
        $group = $this->workflowgroup->getByID($groupID);

        $disabledModules   = explode(',', $group->disabledModules);
        $disabledModules[] = $module;
        $this->dao->update(TABLE_WORKFLOWGROUP)->set('disabledModules')->eq(implode(',', array_unique($disabledModules)))->where('id')->eq($groupID)->exec();

        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * Ajax get workflow groups.
     *
     * @param  string $type
     * @param  string $projectModel
     * @param  int    $hasProduct
     * @param  string $status
     * @param  string $exclusive
     * @access public
     * @return void
     */
    public function ajaxGetWorkflowGroups($type = 'product', $projectModel = 'scrum', $hasProduct = 1, $status = 'normal', $exclusive = 'all')
    {
        $pairs = $this->workflowgroup->getPairs($type, $projectModel, $hasProduct, $status, $exclusive);

        $groups = array();
        foreach($pairs as $groupID => $groupName) $groups[] = array('text' => $groupName, 'value' => $groupID);
        return print(json_encode($groups));
    }
}
