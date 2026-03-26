<?php
/**
 * The control file of workestimation module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     workestimation
 * @version     $Id
 * @link        http://www.zentao.net
 */
class workestimation extends control
{
    /**
     * Project workload estimations.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function index($projectID = 0)
    {
        $this->loadModel('project')->setMenu($projectID);

        if($_POST)
        {
            $result = $this->workestimation->save($projectID);
            if($result) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->server->http_referer));
            return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }

        $scale  = $this->workestimation->getProjectScale($projectID);
        $budget = $this->workestimation->getBudget($projectID);
        if(!isset($this->config->project)) $this->config->project = new stdclass();
        if(empty($budget))
        {
            $this->app->loadConfig('estimate');
            $budget = new stdclass();
            $budget->scale         = $scale;
            /* The default value is 1 to ensure that the process can proceed properly. */
            $budget->productivity  = zget($this->config->custom, 'efficiency', 1);
            $budget->unitLaborCost = zget($this->config->custom, 'cost', '');
            $budget->dayHour       = zget($this->config->project, 'defaultWorkhours', '');
        }

        $this->view->title        = $this->lang->workestimation->common;
        $this->view->position[]   = $this->lang->workestimation->common;
        $this->view->hourPoint    = $this->config->custom->hourPoint;
        $this->view->scale        = $scale;
        $this->view->budget       = $budget;
        $this->view->project      = $this->project->fetchByID($projectID);
        $this->display();
    }
}

