<?php
/**
 * The control file of budget currentModule of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     budget
 * @version     $Id: control.php 5107 2013-07-12 01:46:12Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class budget extends control
{
    /**
     * __construct
     *
     * @param  string $module
     * @param  string $method
     * @access public
     * @return void
     */
    public function __construct($module = '', $method = '')
    {
        parent::__construct($module, $method);
    }

    /**
     * The budget browse page.
     *
     * @param  string  $projectID
     * @param  string  $orderBy
     * @param  int     $recTotal
     * @param  int     $recPerPage
     * @param  int     $pageID
     * @access public
     * @return void
     */
    public function browse($projectID = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('project')->setMenu($projectID);

        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $this->view->title     = $this->lang->budget->list . $this->lang->hyphen . $this->lang->budget->common;
        $this->view->budgets   = $this->budget->getList($projectID, $orderBy, $pager);
        $this->view->orderBy   = $orderBy;
        $this->view->pager     = $pager;
        $this->view->projectID = $projectID;
        $this->view->project   = $this->project->fetchByID($projectID);
        $this->view->modules   = $this->loadModel('tree')->getOptionMenu(0, 'subject');
        $this->view->stages    = $this->loadModel('execution')->getByProject($projectID, 'all', 0, true);
        $this->view->users     = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->display();
    }

    /**
     * The budget summary page.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function summary($projectID = 0)
    {
        $this->loadModel('project')->setMenu($projectID);
        $subjects = $this->budget->getSubjectStructure();

        $this->view->title         = $this->lang->budget->summary . $this->lang->hyphen . $this->lang->budget->common;
        $this->view->subjects      = $subjects;
        $this->view->hasSubSubject = $this->budget->checkSubSubject($subjects);
        $this->view->plans         = $this->loadModel('execution')->getByProject($projectID, 'all', 0, true);
        $this->view->summary       = $this->budget->getSummary($projectID, $subjects);
        $this->view->modules       = $this->loadModel('tree')->getOptionMenu(0, $viewType = 'subject', $startModuleID = 0);
        $this->view->projectID     = $projectID;
        $this->view->project       = $this->project->getByID($projectID);

        $this->display();
    }

    /**
     * Create a budget.
     *
     * @param  int  $projectID
     * @access public
     * @return void
     */
    public function create($projectID = 0)
    {
        $this->loadModel('project')->setMenu($projectID);

        if($_POST)
        {
            $budgetID = $this->budget->create($projectID);
            if(dao::isError())
            {
                $response = array('result' => 'fail', 'message' => dao::getError());
                return $this->send($response);
            }

            $this->loadModel('action')->create('budget', $budgetID, 'created');
            $response = array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse', "projectID=$projectID"));
            return $this->send($response);
        }

        $this->view->title    = $this->lang->budget->create . $this->lang->hyphen . $this->lang->budget->common;
        $this->view->subjects = array(0 => '') + $this->budget->getSubjectOption();
        $this->view->plans    = $this->loadModel('execution')->getByProject($projectID, 'all', 0, true);
        $this->display();
    }

    /**
     * Edit a budget.
     *
     * @param  int    $budgetID
     * @access public
     * @return void
     */
    public function edit($budgetID)
    {
        $budget = $this->budget->getByID($budgetID);

        $this->loadModel('project')->setMenu($budget->project);

        if($_POST)
        {
            $changes = $this->budget->update($budgetID);
            if(dao::isError())
            {
                $response = array('result' => 'fail', 'message' => dao::getError());
                return $this->send($response);
            }

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('budget', $budgetID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            $response = array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse', "projectID={$budget->project}"));
            return $this->send($response);
        }

        $this->view->title    = $this->lang->budget->edit . $this->lang->hyphen . $this->lang->budget->common;
        $this->view->subjects = array(0 => '') + $this->budget->getSubjectOption();
        $this->view->plans    = $this->loadModel('execution')->getByProject($budget->project, 'all', 0, true);
        $this->view->project  = $this->project->getByID($budget->project);
        $this->view->budget   = $budget;
        $this->display();
    }

    /**
     * Budget details.
     *
     * @param  int  $budgetID
     * @access public
     * @return void
     */
    public function view($budgetID)
    {
        $budget = $this->budget->getByID($budgetID);

        $this->loadModel('project')->setMenu($budget->project);

        $this->view->title    = $this->lang->budget->common . $this->lang->hyphen . $this->lang->budget->view;
        $this->view->subjects = $this->budget->getSubjectOption();
        $this->view->plans    = $this->loadModel('execution')->getByProject($budget->project, 'all', 0, true);
        $this->view->budget   = $this->budget->getByID($budgetID);
        $this->view->actions  = $this->loadModel('action')->getList('budget', $budgetID);
        $this->view->users    = $this->loadModel('user')->getPairs('noclosed|noletter');

        $this->display();
    }

    /**
     * Delete a budget.
     *
     * @param  int     $budgetID
     * @param  varchar $confirm
     * @access public
     * @return void
     */
    public function delete($budgetID = 0, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            echo js::confirm($this->lang->budget->confirmDelete, inLink('delete', "budgetID=$budgetID&confirm=yes"));
            die();
        }
        else
        {
            $this->budget->delete(TABLE_BUDGET, $budgetID);
            die(js::locate(inlink('browse', "projectID={$this->session->project}"), 'parent'));
        }
    }

    /**
     * Batch create budgets.
     *
     * @param  int  $projectID
     * @access public
     * @return void
     */
    public function batchCreate($projectID = 0)
    {
        $this->loadModel('project')->setMenu($projectID);

        if($_POST)
        {
            $this->budget->batchCreate();
            if(dao::isError())
            {
                $response = array('result' => 'fail', 'message' => dao::getError());
                return $this->send($response);
            }

            $response = array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse', "projectID=$projectID"));
            return $this->send($response);
        }

        $this->view->title    = $this->lang->budget->batchCreate . $this->lang->hyphen . $this->lang->budget->common;
        $this->view->subjects = arrayUnion(array('' => ''), $this->budget->getSubjectOption());
        $this->view->plans    = $this->loadModel('execution')->getByProject($projectID, 'all', 0, true);
        $this->display();
    }
}
