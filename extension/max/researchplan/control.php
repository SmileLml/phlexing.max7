<?php
/**
 * The control file of researchplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     researchplan
 * @version     $Id: control.php 5107 2021-06-08 14:06:12Z
 * @link        https://www.zentao.net
 */
?>
<?php
class researchplan extends control
{
    /**
     * Browse research plan.
     *
     * @param  int    $projectID
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($projectID = 0, $browseType = 'all', $param = 0, $orderBy = 'begin_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('project')->setMenu($projectID);

        $this->session->set('researchplanList', $this->app->getURI(true), 'project');
        $this->session->set('researchreportList', $this->app->getURI(true), 'project');

        $browseType = strtolower($browseType);

        /* By search. */
        $queryID   = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink('researchplan', 'browse', "projectID=$projectID&browseType=bySearch&param=myQueryID");
        $this->researchplan->buildSearchForm($queryID, $actionURL);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title       = $this->lang->researchplan->browse;
        $this->view->planList    = $this->researchplan->getList($projectID, $browseType, $param, $orderBy, $pager);
        $this->view->reportPairs = $this->researchplan->getReportPairs($projectID);
        $this->view->projectID   = $projectID;
        $this->view->project     = $this->project->fetchByID($projectID);
        $this->view->browseType  = $browseType;
        $this->view->param       = $param;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;

        $this->display();
    }

    /**
     * Create a research plan.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function create($projectID = 0)
    {
        $this->loadModel('project')->setMenu($projectID);

        if($_POST)
        {
            $planID = $this->researchplan->create($projectID);

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $this->loadModel('action')->create('researchplan', $planID, 'Opened');
            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $planID));

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = inlink('browse', "projectID=$projectID");
            return $this->send($response);
        }

        $this->view->title       = $this->lang->researchplan->create;
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed|noletter|all');
        $this->view->insideUsers = $this->user->getPairs('noclosed|noletter');

        $this->display();
    }

    /**
     * Edit a research plan.
     *
     * @param  int    $planID
     * @access public
     * @return void
     */
    public function edit($planID)
    {
        $plan = $this->researchplan->getByID($planID);
        if(empty($plan)) die(js::error($this->lang->notFound) . js::locate('back'));
        $this->loadModel('project')->setMenu($plan->project);

        if($_POST)
        {
            $changes = $this->researchplan->update($planID);
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('researchplan', $planID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = inlink('view', "planID=$planID");
            return $this->send($response);
        }

        $this->view->title       = $this->lang->researchplan->edit;
        $this->view->plan        = $plan;
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed|noletter|all');
        $this->view->insideUsers = $this->user->getPairs('noclosed|noletter');

        $this->display();
    }

    /**
     * View a research plan.
     *
     * @param  int    $planID
     * @access public
     * @return void
     */
    public function view($planID)
    {
        $plan = $this->researchplan->getByID($planID);
        if(empty($plan)) die(js::error($this->lang->notFound) . js::locate('back'));
        $this->loadModel('project')->setMenu($plan->project);

        $this->view->title   = $this->lang->researchplan->view;
        $this->view->plan    = $plan;
        $this->view->users   = $this->loadModel('user')->getPairs('noclosed|noletter|all');
        $this->view->actions = $this->loadModel('action')->getList('researchplan', $planID);

        $this->display();
    }

    /**
     * Delete a research plan.
     *
     * @param  int    $planID
     * @param  string $confirm    yes|no
     * @access public
     * @return void
     */
    public function delete($planID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->researchplan->confirmDelete, $this->createLink('researchplan', 'delete', "planID=$planID&confirm=yes")));
        }
        else
        {
            $plan = $this->researchplan->getByID($planID);
            if(empty($plan)) die(js::error($this->lang->notFound) . js::locate('back'));
            $this->researchplan->delete(TABLE_RESEARCHPLAN, $planID);

            die(js::locate($this->createLink('researchplan', 'browse', "projectID=$plan->project"), 'parent'));
        }
    }

    /**
     * Ajax get plan info.
     *
     * @param  int    $planID
     * @access public
     * @return json
     */
    public function ajaxGetPlanInfo($planID)
    {
        $plan = $this->researchplan->getByID($planID);
        echo json_encode($plan);
    }
}
