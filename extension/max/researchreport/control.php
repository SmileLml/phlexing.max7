<?php
/**
 * The control file of researchreport module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     researchreport
 * @version     $Id: control.php 5107 2021-06-08 14:06:12Z
 * @link        https://www.zentao.net
 */
?>
<?php
class researchreport extends control
{
    /**
     * Browse research report.
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
    public function browse($projectID = 0, $browseType = 'all', $param = 0, $orderBy = 'createdDate_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->loadModel('project')->setMenu($projectID);

        $this->session->set('researchreportList', $this->app->getURI(true), 'project');

        $browseType = strtolower($browseType);

        /* By search. */
        $queryID   = ($browseType == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink('researchreport', 'browse', "projectID=$projectID&browseType=bySearch&param=myQueryID");
        $this->researchreport->buildSearchForm($projectID, $queryID, $actionURL);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->researchreport->browse;
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->reportList = $this->researchreport->getList($projectID, $browseType, $param, $orderBy, $pager);
        $this->view->projectID  = $projectID;
        $this->view->project    = $this->project->fetchByID($projectID);
        $this->view->browseType = $browseType;
        $this->view->param      = $param;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;

        $this->display();
    }

    /**
     * Create a research report.
     *
     * @param  int    $projectID
     * @param  int    $researchPlanID
     * @access public
     * @return void
     */
    public function create($projectID = 0, $relatedPlanID = 0)
    {
        $this->loadModel('project')->setMenu($projectID);

        if($_POST)
        {
            $reportID = $this->researchreport->create($projectID);
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            $this->loadModel('action')->create('researchreport', $reportID, 'Opened');
            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $reportID));

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = isonlybody() ? 'parent' : inlink('view', "reportID=$reportID");
            return $this->send($response);
        }

        $planPairs   = $this->loadModel('researchplan')->getPairs($projectID);
        $reportPairs = $this->researchplan->getReportPairs($projectID);
        foreach(array_keys($planPairs) as $planID)
        {
            if(isset($reportPairs[$planID])) unset($planPairs[$planID]);
        }

        $this->view->title       = $this->lang->researchreport->create;
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->relatedPlan = $relatedPlanID;
        $this->view->planPairs   = arrayUnion(array(''), $planPairs);

        $this->display();
    }

    /**
     * Edit a research report.
     *
     * @param  int    $reportID
     * @access public
     * @return void
     */
    public function edit($reportID)
    {
        $report = $this->researchreport->getByID($reportID);
        if(empty($report)) die(js::error($this->lang->notFound) . js::locate('back'));
        $this->loadModel('project')->setMenu($report->project);

        if($_POST)
        {
            $changes = $this->researchreport->update($reportID);
            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();
                return $this->send($response);
            }

            if($changes)
            {
                $actionID = $this->loadModel('action')->create('researchreport', $reportID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }

            $response['result']  = 'success';
            $response['message'] = $this->lang->saveSuccess;
            $response['locate']  = inlink('view', "reportID=$reportID");
            return $this->send($response);
        }

        $planPairs   = $this->loadModel('researchplan')->getPairs($report->project);
        $reportPairs = $this->researchplan->getReportPairs($report->project);
        foreach(array_keys($planPairs) as $planID)
        {
            if($planID == $report->relatedPlan) continue;
            if(isset($reportPairs[$planID])) unset($planPairs[$planID]);
        }

        $this->view->title     = $this->lang->researchreport->edit;
        $this->view->users     = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->report    = $report;
        $this->view->planPairs = arrayUnion(array(''), $planPairs);

        $this->display();
    }

    /**
     * View a research report.
     *
     * @param  int    $reportID
     * @access public
     * @return void
     */
    public function view($reportID)
    {
        $report = $this->researchreport->getByID($reportID);
        if(empty($report)) die(js::error($this->lang->notFound) . js::locate('back'));
        $this->loadModel('project')->setMenu($report->project);

        $this->view->title     = $this->lang->researchreport->view;
        $this->view->report    = $report;
        $this->view->users     = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->actions   = $this->loadModel('action')->getList('researchreport', $reportID);
        $this->view->planPairs = $this->loadModel('researchplan')->getPairs($report->project);
        $this->view->relatedUR = $this->researchreport->getRelatedUR($reportID);

        $this->display();
    }

    /**
     * Delete a research report.
     *
     * @param  int    $reportID
     * @param  string $confirm    yes|no
     * @access public
     * @return void
     */
    public function delete($reportID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->researchreport->confirmDelete, $this->createLink('researchreport', 'delete', "reportID=$reportID&confirm=yes")));
        }
        else
        {
            $report = $this->researchreport->getByID($reportID);
            if(empty($report)) die(js::error($this->lang->notFound) . js::locate('back'));
            $this->researchreport->delete(TABLE_RESEARCHREPORT, $reportID);

            die(js::locate($this->createLink('researchreport', 'browse', "projectID=$report->project"), 'parent'));
        }
    }
}
